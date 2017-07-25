<?php
/**
 * Created by PhpStorm.
 * User: Raul
 * Date: 27/06/2017
 * Time: 19:19
 */

namespace App\Repositories;
use App\Models\Notafiscal;
use NFePHP\NFe\ToolsNFe;
use App\Repositories\NotafiscalRepository;

class ConsultaNfeRepository
{
    public $notaFiscalRepository;

    public function __construct(NotafiscalRepository $notaFiscalRepository)
    {
        $this->notaFiscalRepository = $notaFiscalRepository;
    }

    public static function downloadXML($fromCommand = 0){
        if ($fromCommand) {
            $nfe = new ToolsNFe(config_path('nfe-command.json'));
        } else {
            $nfe = new ToolsNFe(config_path('nfe.json'));
        }

        $nfe->setModelo('55');
        $ultNSU = 0; // se estiver como zero irá retornar os dados dos ultimos 15 dias até o limite de 50 registros
                     // se for diferente de zero irá retornar a partir desse numero os dados dos
                     // últimos 15 dias até o limite de 50 registros

        $numNSU = 0; // se estiver como zero irá usar o ultNSU
                     // se for diferente de zero não importa o que está contido em ultNSU será retornado apenas
                     // os dados deste NSU em particular

        $tpAmb = '1';// esses dados somente existirão em ambiente de produção pois em ambiente de testes
                     // não existem dados de eventos, nem de NFe emitidas para o seu CNPJ

        $cnpj = ''; // deixando vazio irá pegar o CNPJ default do config
                    // se for colocado um CNPJ tenha certeza que o certificado está autorizado a
                    // baixar os dados desse CNPJ pois se não estiver autorizado haverá uma
                    // mensagem de erro da SEFAZ
                    //array que irá conter os dados de retorno da SEFAZ

        $aResposta = array();
                    //essa rotina deve rá ser repetida a cada hora até que o maxNSU retornado esteja contido no NSU da mensagem
                    //se estiver já foram baixadas todas as referencias a NFe, CTe e outros eventos da NFe e não a mais nada a buscar
                    //outro detalhe é que não adianta tentar buscar dados muito antigos o sistema irá informar que
                    //nada foi encontrado, porque a SEFAZ não mantêm os NSU em base de dados por muito tempo, em
                    //geral são mantidos apenas os dados dos últimos 15 dias.
                    //Os dados são retornados em formato ZIP dento do xml, mas no array os dados
                    //já são retornados descompactados para serem lidos

        $chNFe = '';
        $nota = Notafiscal::orderBy('nsu', 'desc')->first();
        $ultNSU = (int) $nota->nsu;
        $resp = $nfe->sefazDownload($chNFe, $tpAmb, $cnpj, $aResposta, $ultNSU );
        return true;
    }

    public function syncXML($download = 0, $fromCommand = 0)
    {
        if ($download) {
            $this->downloadXML($fromCommand);
        }

        $path = storage_path(sprintf('nfe/producao/temporarias/%s/-retDownnfe.xml', date('Ym')));
        $xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', file_get_contents($path));

        $resNFe = 'resNFe_v1.00.xsd';
        $procNFe = 'procNFe_v3.10.xsd';

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

                $aDocs[] = array(
                    'NSU' => $nsu,
                    'schema' => $schema,
                    'dados' => $zip
                );
            }

            // Percorrendo array para popular tabela de notas_fiscais e nota_fiscal_itens
            $resp['docs'] = $aDocs;

            $cont = 0;
            $nfObj = null;
            $nota = null;
            foreach ($aDocs as $doc) {

                $NSU = $doc['NSU'];

                if ($doc['schema'] == $procNFe) {

                    $nota = simplexml_load_string($doc['dados']);

                    try {

                        $arrayNota = json_decode(json_encode((array)$nota), TRUE);

                        $dataSaida = isset($arrayNota['NFe']['infNFe']['ide']['dhSaiEnt']) ? $arrayNota['NFe']['infNFe']['ide']['dhSaiEnt'] : null;

                        $fantasia = isset($arrayNota['NFe']['infNFe']['emit']['xFant']) ? $arrayNota['NFe']['infNFe']['emit']['xFant'] : null;

                        $notaData = [
                            'contrato_id' => null,
                            'nsu' => $NSU,
                            'solicitacao_entrega_id' => null,
                            'xml' => $doc['dados'],
                            'codigo' => $arrayNota['NFe']['infNFe']['ide']['nNF'],
                            'versao' => $arrayNota['NFe']['infNFe']["@attributes"]['versao'],
                            'natureza_operacao' => $arrayNota['NFe']['infNFe']['ide']['natOp'],
                            'data_emissao' => $arrayNota['NFe']['infNFe']['ide']['dhEmi'],
                            'data_saida' => $dataSaida,
                            'cnpj' => $arrayNota['NFe']['infNFe']['emit']['CNPJ'],
                            'razao_social' => $arrayNota['NFe']['infNFe']['emit']['xNome'],
                            'fantasia' => $fantasia,
                            'cnpj_destinatario' => $arrayNota['NFe']['infNFe']['dest']['CNPJ'],
                            'arquivo_nfe' => null,
                            'chave' => str_replace('NFe', '', $arrayNota['NFe']['infNFe']["@attributes"]['Id'])
                        ];

                        $items = [];

                        $detalhes = $arrayNota['NFe']['infNFe']['det'];

                        foreach ($detalhes as $detalhe) {

                            try {

                                if (isset($detalhe["prod"])) {

                                    $icms = 0;
                                    $pis = 0;
                                    $confins = 0;
                                    $totalTributo = 0;

                                    if (isset($detalhe['imposto']['ICMS']['ICMS00']['vICMS'])) {
                                        $icms = $detalhe['imposto']['ICMS']['ICMS00']['vICMS'];
                                    }

                                    if (isset($detalhe['imposto']['PIS']['PISAliq']['vPIS'])) {
                                        $pis = $detalhe['imposto']['PIS']['PISAliq']['vPIS'];
                                    }

                                    if (isset($detalhe['imposto']['COFINS']['COFINSAliq']['vCOFINS'])) {
                                        $confins = $detalhe['imposto']['COFINS']['COFINSAliq']['vCOFINS'];
                                    }

                                    if (isset($detalhe['prod']['vUnTrib'])) {
                                        $totalTributo = $detalhe['prod']['qTrib'] * $detalhe['prod']['vUnTrib'];
                                    }

                                    $itemData = [
                                        'ncm' => $detalhe['prod']['NCM'],
                                        'nome_produto' => $detalhe['prod']['xProd'],
                                        'codigo_produto' => $detalhe['prod']['cProd'],
                                        'ean' => count($detalhe['prod']['cEAN']) > 0 ? json_encode($detalhe['prod']['cEAN']) : null,
                                        'qtd' => $detalhe['prod']['qCom'],
                                        'valor_unitario' => $detalhe['prod']['vUnCom'],
                                        'valor_total' => $detalhe['prod']['vProd'],
                                        'unidade' => $detalhe['prod']['uCom'],
                                        'valor_tributavel' => $totalTributo,
                                        'icms' => $icms,
                                        'ipi' => $pis,
                                        'cofins' => $confins,
                                    ];

                                    array_push($items, $itemData);
                                }

                            } catch (\Exception $e) {
                                \Log::error($e);
                            }
                        }

                        $nfObjArr = $this->notaFiscalRepository->findByField('chave', $notaData['chave']);
                        $nfObj = isset($nfObjArr[0]) ? $nfObjArr[0] : null;

                        if (!$nfObj) {
                            $nfObj = $this->notaFiscalRepository->firstOrCreate($notaData);
                        }

                        foreach ($items as $item) {
                            if (!$itemObj = $nfObj->items()->where('codigo_produto', $item['codigo_produto'])->first()) {
                                $nfObj->items()->save(new NotaFiscalItem($item));
                            } else {
                                $itemObj->update($item);
                            }
                        }

                    } catch (\Exception $e) {

                        \Log::error($e);

                    }
                } elseif ($doc['schema'] == $resNFe) {

                    $nota = simplexml_load_string($doc['dados']);
                    $arrayNota = json_decode(json_encode((array)$nota), TRUE);

                    $nfObjRes = Notafiscal::where("chave", $arrayNota['chNFe'])->first();

                    if (!$nfObjRes) {

                        $notaData = [
                            'contrato_id' => null,
                            'nsu' => $NSU,
                            'solicitacao_entrega_id' => null,
                            'xml' => $doc['dados'],
                            'codigo' => '',
                            'versao' => '',
                            'natureza_operacao' => null,
                            'data_emissao' => $arrayNota['dhEmi'],
                            'data_saida' => null,
                            'cnpj' => $arrayNota['CNPJ'],
                            'razao_social' => $arrayNota['xNome'],
                            'fantasia' => null,
                            'cnpj_destinatario' => null,
                            'arquivo_nfe' => null,
                            'chave' => str_replace('NFe', '', $arrayNota['chNFe'])
                        ];

                        $items = [];

                        $nfObjArr = $this->notaFiscalRepository->findByField('chave', $notaData['chave']);
                        $nfObj = isset($nfObjArr[0]) ? $nfObjArr[0] : null;
                        if (!$nfObj) {
                            $nfObj = $this->notaFiscalRepository->firstOrCreate($notaData);
                        }
                    } else {
                        if ($NSU > $nfObjRes->nsu) {
                            $nfObjRes->nsu = $NSU;
                            $nfObjRes->save();
                        }
                    }
                }
            }

            return true;
        } catch (\Exception $e) {
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

}