<?php
/**
 * Created by PhpStorm.
 * User: Raul
 * Date: 27/06/2017
 * Time: 19:19
 */

namespace App\Repositories;
use DB;
use NFePHP\CTe\Tools;

class ConsultaCteRepository
{

    public function __construct()
    {

    }

    public static function downloadXML($fromCommand = 0)
    {
        $aResposta = array();
        $chave = '';
        $cnpj  = '';
        $tpAmb = 1;

        if ($fromCommand) {
            $cteTools = new Tools(config_path('nfe-command.json'));
        } else {
            $cteTools = new Tools(config_path('nfe.json'));
        }

        $ultNSU = 0;
        if ($cte = DB::table("ctes")->orderBy("nsu", "desc")->first()) {
            $ultNSU = $cte->nsu;
        }

        $retorno = $cteTools->cteDownload($chave, $tpAmb, $cnpj, $aResposta, $ultNSU);

        return true;
    }

    public function syncXML($download = 0, $fromCommand = 0)
    {
        if ($download) {
            $this->downloadXML($fromCommand);
        }

        $tpAmb = 1;
        $urlTpAmb = 'homologacao';
        if ($tpAmb == 1) {
            $urlTpAmb = 'producao';
        }

        $path = storage_path(sprintf('cte/%s/temporarias/%s/-retCTe.xml', $urlTpAmb, date('Ym')));
        $xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', file_get_contents($path));

        $procCTe_v2 = 'procCTe_v2.00.xsd';
        $procCTe_v3 = 'procCTe_v3.00.xsd';
        $procEventoCTe_v3 = 'procEventoCTe_v3.00.xsd';

        $resp = array(
            'bStat' => false,
            'cStat' => 0,
            'xMotivo' => '',
            'dhResp' => '',
            'ultNSU' => 0,
            'maxNSU' => 0,
            'docs' => array()
        );

        try {
            //tratar dados de retorno
            $dom = new \DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $dom->formatOutput = false;
            $dom->preserveWhiteSpace = false;
            $dom->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $retDistDFeInt = $dom->getElementsByTagName("retDistDFeInt")->item(0);
            $cStat = !empty($dom->getElementsByTagName('cStat')->item(0)->nodeValue) ?
                $dom->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $xMotivo = !empty($dom->getElementsByTagName('xMotivo')->item(0)->nodeValue) ?
                $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';

            $bStat = true;
            $dhResp = !empty($dom->getElementsByTagName('dhResp')->item(0)->nodeValue) ?
                $dom->getElementsByTagName('dhResp')->item(0)->nodeValue : '';
            $ultNSU = !empty($dom->getElementsByTagName('ultNSU')->item(0)->nodeValue) ?
                $dom->getElementsByTagName('ultNSU')->item(0)->nodeValue : '';
            $maxNSU = !empty($dom->getElementsByTagName('maxNSU')->item(0)->nodeValue) ?
                $dom->getElementsByTagName('maxNSU')->item(0)->nodeValue : '';
            $resp = array(
                'bStat' => $bStat,
                'cStat' => (int)$cStat,
                'xMotivo' => (string)$xMotivo,
                'dhResp' => (string)$dhResp,
                'ultNSU' => (int)$ultNSU,
                'maxNSU' => (int)$maxNSU,
                'docs' => array()
            );

            $aDocs = [];
            $docs = $dom->getElementsByTagName('docZip');
            foreach ($docs as $doc) {

                $nsu = (int)$doc->getAttribute('NSU');
                $schema = (string)$doc->getAttribute('schema');
                //o conteudo desse dado é um zip em base64
                //para deszipar deve primeiro descomverter de base64
                //e depois aplicar a descompactação
                $zip = (string)$doc->nodeValue;
                $zipdata = base64_decode($zip);
                $zip = $this->pGunzip1($zipdata);

                $cte = simplexml_load_string($zip);
                $arrayCte = json_decode(json_encode((array)$cte), TRUE);

                if ($schema == $procCTe_v2 || $procCTe_v3 == $schema) {

                    $aDoc = array(
                        'NSU' => $nsu,
                        'schema' => $schema,
                        'dados' => $zip,
                        'obj' => $arrayCte
                    );

                    array_push($aDocs, $aDoc);
                }
            }

            $cont = 0;
            $nfObj = null;
            $nota = null;
            $dadosNfe = [];
            foreach ($aDocs as $doc) {
                try {

                    $dados = [];
                    $dados['nfes'] = [];
                    $dados['nsu'] = $doc['NSU'];
                    $dados['xml'] = $doc['dados'];
                    $dados['schema'] = $doc['schema'];
                    $dados['versao'] = $doc['obj']['protCTe']['@attributes']['versao'];
                    $dados['chave'] = $doc['obj']['protCTe']['infProt']['chCTe'];
                    $dados['data_recibo'] = $doc['obj']['protCTe']['infProt']['dhRecbto'];
                    $dados['data_emissao'] = $doc['obj']['CTe']['infCte']['ide']['dhEmi'];

                    if (is_array($doc['obj']['CTe']['infCte']['infCTeNorm']['infDoc']['infNFe'])) {
                        foreach ($doc['obj']['CTe']['infCte']['infCTeNorm']['infDoc']['infNFe'] as $index => $chave) {
                            if (is_array($chave)) {
                                if (strlen($chave['chave']) == 44) {
                                    $dados['nfes'][] = $chave['chave'];
                                }
                            } else {
                                if (strlen($chave) == 44) {
                                    $dados['nfes'][] = $chave;
                                }
                            }
                        }
                    }

                    $dados['numero'] = $doc['obj']['CTe']['infCte']['ide']['nCT'];
                    $dados['cnpj_emitente'] = $doc['obj']['CTe']['infCte']['emit']['CNPJ'];
                    $dados['nome_emitente'] = $doc['obj']['CTe']['infCte']['emit']['xNome'];
                    $dados['cnpj_remetente'] = $doc['obj']['CTe']['infCte']['rem']['CNPJ'];
                    $dados['nome_remetente'] = $doc['obj']['CTe']['infCte']['rem']['xNome'];
                    $dados['cnpj_destinatario'] = $doc['obj']['CTe']['infCte']['dest']['CNPJ'];
                    $dados['nome_destinatario'] = $doc['obj']['CTe']['infCte']['dest']['xNome'];

                    $dados['origem'] = $doc['obj']['CTe']['infCte']['rem']['enderReme']['xMun'];
                    $dados['origem_uf'] = $doc['obj']['CTe']['infCte']['rem']['enderReme']['UF'];
                    $dados['destino'] = $doc['obj']['CTe']['infCte']['dest']['enderDest']['xMun'];
                    $dados['destino_uf'] = $doc['obj']['CTe']['infCte']['dest']['enderDest']['UF'];

                    $dados['valor_carga'] = $doc['obj']['CTe']['infCte']['infCTeNorm']['infCarga']['vCarga'];
                    $dados['valor_cobrado'] = $doc['obj']['CTe']['infCte']['vPrest']['vTPrest'];
                    $dados['natureza_operacao'] = $doc['obj']['CTe']['infCte']['ide']['natOp'];

                    array_push($dadosNfe, $dados);

                } catch (\Exception $e) {
                    dd($e);
                    Log::error($e);
                }
            }

            if (count($dadosNfe) > 0) {
                foreach ($dadosNfe as $dados) {
                    $cte = DB::table("ctes")->where("chave", $dados['chave'])->first();
                    if (!$cte) {
                        $dataItems  = $dados['nfes'];
                        $dataInsert = array_except($dados, 'nfes');
                        $cteId = DB::table("ctes")->insertGetId($dataInsert);
                        if ($cteId > 0) {
                            foreach ($dataItems as $nf) {
                                $item = DB::table("cte_notas")->insert(['cte_id' => $cteId, 'chave_nfe' => $nf]);
                            }
                        }
                    }
                }
            }

            return true;
        } catch (\Exception $e) {

            dd($e);
            \Log::error($e);
            return false;
        }
    }

    /**
     * printDebug
     * Adiciona descrição do erro ao contenedor dos erros
     *
     * @name printDebug
     * @param   string $msg Descrição do erro
     * @return  none
     */
    public function printDebug($value)
    {
        echo sprintf('<pre>%s</pre>', print_r($value, true));
    }

    private function pGunzip1($data)
    {
        $len = strlen($data);
        if ($len < 18 || strcmp(substr($data, 0, 2), "\x1f\x8b")) {
            $msg = "Não é dado no formato GZIP.";
            $this->printDebug($msg);
            return false;
        }
        $method = ord(substr($data, 2, 1));  // metodo de compressão
        $flags = ord(substr($data, 3, 1));  // Flags
        if ($flags & 31 != $flags) {
            $msg = "Não são permitidos bits reservados.";
            $this->printDebug($msg);
            return false;
        }
        // NOTA: $mtime pode ser negativo (limitações nos inteiros do PHP)
        $mtime = unpack("V", substr($data, 4, 4));
        $mtime = $mtime[1];
        $headerlen = 10;
        $extralen = 0;
        $extra = "";
        if ($flags & 4) {
            // dados estras prefixados de 2-byte no cabeçalho
            if ($len - $headerlen - 2 < 8) {
                $msg = "Dados inválidos.";
                $this->printDebug($msg);
                return false;
            }
            $extralen = unpack("v", substr($data, 8, 2));
            $extralen = $extralen[1];
            if ($len - $headerlen - 2 - $extralen < 8) {
                $msg = "Dados inválidos.";
                $this->printDebug($msg);
                return false;
            }
            $extra = substr($data, 10, $extralen);
            $headerlen += 2 + $extralen;
        }
        $filenamelen = 0;
        $filename = "";
        if ($flags & 8) {
            // C-style string
            if ($len - $headerlen - 1 < 8) {
                $msg = "Dados inválidos.";
                $this->printDebug($msg);
                return false;
            }
            $filenamelen = strpos(substr($data, $headerlen), chr(0));
            if ($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
                $msg = "Dados inválidos.";
                $this->printDebug($msg);
                return false;
            }
            $filename = substr($data, $headerlen, $filenamelen);
            $headerlen += $filenamelen + 1;
        }
        $commentlen = 0;
        $comment = "";
        if ($flags & 16) {
            // C-style string COMMENT data no cabeçalho
            if ($len - $headerlen - 1 < 8) {
                $msg = "Dados inválidos.";
                $this->printDebug($msg);
                return false;
            }
            $commentlen = strpos(substr($data, $headerlen), chr(0));
            if ($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
                $msg = "Formato de cabeçalho inválido.";
                $this->printDebug($msg);
                return false;
            }
            $comment = substr($data, $headerlen, $commentlen);
            $headerlen += $commentlen + 1;
        }
        $headercrc = "";
        if ($flags & 2) {
            // 2-bytes de menor ordem do CRC32 esta presente no cabeçalho
            if ($len - $headerlen - 2 < 8) {
                $msg = "Dados inválidos.";
                $this->printDebug($msg);
                return false;
            }
            $calccrc = crc32(substr($data, 0, $headerlen)) & 0xffff;
            $headercrc = unpack("v", substr($data, $headerlen, 2));
            $headercrc = $headercrc[1];
            if ($headercrc != $calccrc) {
                $msg = "Checksum do cabeçalho falhou.";
                $this->printDebug($msg);
                return false;
            }
            $headerlen += 2;
        }
        // Rodapé GZIP
        $datacrc = unpack("V", substr($data, -8, 4));
        $datacrc = sprintf('%u', $datacrc[1] & 0xFFFFFFFF);
        $isize = unpack("V", substr($data, -4));
        $isize = $isize[1];
        // decompressão
        $bodylen = $len - $headerlen - 8;
        if ($bodylen < 1) {
            $msg = "BUG da implementação.";
            $this->printDebug($msg);
            return false;
        }
        $body = substr($data, $headerlen, $bodylen);
        $data = "";
        if ($bodylen > 0) {
            switch ($method) {
                case 8:
                    // Por hora somente é suportado esse metodo de compressão
                    $data = gzinflate($body, null);
                    break;
                default:
                    $msg = "Método de compressão desconhecido (não suportado).";
                    $this->printDebug($msg);
                    return false;
            }
        }  // conteudo zero-byte é permitido
        // Verificar CRC32
        $crc = sprintf("%u", crc32($data));
        $crcOK = $crc == $datacrc;
        $lenOK = $isize == strlen($data);
        if (!$lenOK || !$crcOK) {
            $msg = ($lenOK ? '' : 'Verificação do comprimento FALHOU. ') . ($crcOK ? '' : 'Checksum FALHOU.');
            $this->printDebug($msg);
            return false;
        }
        return $data;
    }//fim gunzip1


    public function geraDacte($cte)
    {
        $cteTools = new Tools(config_path('nfe.json'));
        $dacte = new \NFePHP\Extras\Dacte(
            $cte->xml,
            'P',
            'A4',
            $cteTools->aConfig['aDocFormat']->pathLogoFile,
            'I',
            '');
        $id = $dacte->montaDACTE();
        //Salva o PDF na pasta
        //$salva = $danfe->printDANFE($pdfDanfe, 'F');
        //Abre o PDF no Navegador
        return $dacte->printDACTE("{$id}-dacte.pdf", 'I');
    }
}