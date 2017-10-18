<?php
/**
 * Created by PhpStorm.
 * User: Raul
 * Date: 27/06/2017
 * Time: 19:19
 */

namespace App\Repositories;

use App\Models\Notafiscal;
use App\Models\NotaFiscalFatura;
use App\Models\NotaFiscalItem;
use Carbon\Carbon;
use NFePHP\NFe\ToolsNFe;
use App\Repositories\NotafiscalRepository;
use Log;

class ConsultaNfeRepository
{
    public $notaFiscalRepository;

    public function __construct(NotafiscalRepository $notaFiscalRepository)
    {
        $this->notaFiscalRepository = $notaFiscalRepository;
    }

    public static function downloadXML($fromCommand = 0)
    {

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
        $ultNSU = 0;
        if ($nota) {
            $ultNSU = (int)$nota->nsu;
        }
        $nfe->sefazDownload($chNFe, $tpAmb, $cnpj, $aResposta, $ultNSU);
        return true;
    }

    public function reprocessaXML($xml, $NSU, $schema)
    {
        $notaData = $this->extraiData($xml, $NSU, $schema);

        $this->saveData($notaData);

        return $notaData;
    }

    public function extraiData($xml, $NSU, $schema)
    {
        $nota = simplexml_load_string($xml);
        $notaData = [];
        $arrayNota = json_decode(json_encode((array)$nota), TRUE);

        if (is_null($schema)) {
            $schema = 'procNFe_v' . $arrayNota["@attributes"]['versao'] . '.xsd';
        }


        try {

            $dataSaida = isset($arrayNota['NFe']['infNFe']['ide']['dhSaiEnt']) ? str_replace('T', ' ', substr($arrayNota['NFe']['infNFe']['ide']['dhSaiEnt'], 0, 19)) : null;

            $fantasia = isset($arrayNota['NFe']['infNFe']['emit']['xFant']) ? $arrayNota['NFe']['infNFe']['emit']['xFant'] : null;

            $notaData = [
                'nsu' => $NSU,
                'schema' => $schema,
                'xml' => $xml,
                'codigo' => $arrayNota['NFe']['infNFe']['ide']['nNF'],
                'versao' => $arrayNota['NFe']['infNFe']["@attributes"]['versao'],
                'natureza_operacao' => $arrayNota['NFe']['infNFe']['ide']['natOp'],
                'data_emissao' => isset($arrayNota['NFe']['infNFe']['ide']['dhEmi']) ? str_replace('T', ' ', substr($arrayNota['NFe']['infNFe']['ide']['dhEmi'], 0, 19)) : null,
                'data_saida' => $dataSaida,
                'cnpj' => $arrayNota['NFe']['infNFe']['emit']['CNPJ'],
                'razao_social' => $arrayNota['NFe']['infNFe']['emit']['xNome'],
                'fantasia' => $fantasia,
                'cnpj_destinatario' => $arrayNota['NFe']['infNFe']['dest']['CNPJ'],
                'arquivo_nfe' => null,
                'chave' => str_replace('NFe', '', $arrayNota['NFe']['infNFe']["@attributes"]['Id'])
            ];

            $notaData['serie'] = $arrayNota['NFe']['infNFe']['ide']['serie'];
            $notaData['tipo_entrada_saida'] = $arrayNota['NFe']['infNFe']['ide']['tpNF'];
            $notaData["protocolo"] = $arrayNota['protNFe']['infProt']['nProt'];

            $notaData["remetente_inscricao_estadual_sub"] = '';
            $notaData["remetente_endereco"] = $arrayNota['NFe']['infNFe']['emit']['enderEmit']['xLgr'];
            $notaData["remetente_numero"] = $arrayNota['NFe']['infNFe']['emit']['enderEmit']['nro'];
            $notaData["remetente_bairro"] = $arrayNota['NFe']['infNFe']['emit']['enderEmit']['xBairro'];
            $notaData["remetente_cep"] = $arrayNota['NFe']['infNFe']['emit']['enderEmit']['CEP'];
            $notaData["remetente_cidade"] = $arrayNota['NFe']['infNFe']['emit']['enderEmit']['xMun'];
            $notaData["remetente_uf"] = $arrayNota['NFe']['infNFe']['emit']['enderEmit']['UF'];
            $notaData["remetente_fone_fax"] = isset($arrayNota['NFe']['infNFe']['emit']['enderEmit']['fone']) ? $arrayNota['NFe']['infNFe']['emit']['enderEmit']['fone'] : '';

            $notaData["destinatario_nome"] = $arrayNota['NFe']['infNFe']['dest']['xNome'];
            $notaData["destinatario_endereco"] = $arrayNota['NFe']['infNFe']['dest']['enderDest']['xLgr'];
            $notaData["destinatario_numero"] = $arrayNota['NFe']['infNFe']['dest']['enderDest']['nro'];
            $notaData["destinatario_bairro"] = $arrayNota['NFe']['infNFe']['dest']['enderDest']['xBairro'];
            $notaData["destinatario_cep"] = $arrayNota['NFe']['infNFe']['dest']['enderDest']['CEP'];
            $notaData["destinatario_cidade"] = $arrayNota['NFe']['infNFe']['dest']['enderDest']['xMun'];
            $notaData["destinatario_cidade_codigo"] = $arrayNota['NFe']['infNFe']['dest']['enderDest']['cMun'];
            $notaData["destinatario_uf"] = $arrayNota['NFe']['infNFe']['dest']['enderDest']['UF'];
            $notaData["destinatario_fone_fax"] = isset($arrayNota['NFe']['infNFe']['dest']['enderDest']['fone']) ? $arrayNota['NFe']['infNFe']['dest']['enderDest']['fone'] : '';
            $notaData["destinatario_inscricao_estadual"] = isset($arrayNota['NFe']['infNFe']['dest']['IE']) ? $arrayNota['NFe']['infNFe']['dest']['IE'] : '';
            $notaData["destinatario_inscricao_estadual_sub"] = '';

            $notaData["base_calculo_icms"] = $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vBC'];
            $notaData["valor_icms"] = $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vICMS'];
            $notaData["base_calculo_icms_sub"] = $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vBCST'];
            $notaData["valor_icms_sub"] = $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vST'];
            $notaData["valor_imposto_importacao"] = $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vII'];
            $notaData["valor_icms_uf_remetente"] = isset($arrayNota['NFe']['infNFe']['total']['ICMSTot']['vICMSUFRemet']) ? $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vICMSUFRemet'] : 0;
            $notaData["valor_fcp"] = isset($arrayNota['NFe']['infNFe']['total']['ICMSTot']['vFCPUFDest']) ? $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vFCPUFDest'] : 0;
            $notaData["valor_pis"] = $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vPIS'];
            $notaData["valor_total_produtos"] = $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vProd'];
            $notaData["valor_frete"] = $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vFrete'];
            $notaData["valor_seguro"] = $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vSeg'];
            $notaData["desconto"] = $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vDesc'];
            $notaData["outras_despesas"] = $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vOutro'];
            $notaData["valor_total_ipi"] = $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vIPI'];
            $notaData["valor_icms_uf_destinatario"] = isset($arrayNota['NFe']['infNFe']['total']['ICMSTot']['vICMSUFDest']) ? $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vICMSUFDest'] : 0;
            $notaData["valor_total_tributos"] = isset($arrayNota['NFe']['infNFe']['total']['ICMSTot']['vTotTrib']) ? $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vTotTrib'] : 0;
            $notaData["valor_confins"] = $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vCOFINS'];
            $notaData["valor_total_nota"] = $arrayNota['NFe']['infNFe']['total']['ICMSTot']['vNF'];

            $notaData['frete_por_conta'] = $arrayNota['NFe']['infNFe']['transp']['modFrete'];

            if (isset($arrayNota['NFe']['infNFe']['transp']['transporta'])) {
                $notaData["transportadora_nome"] = isset($arrayNota['NFe']['infNFe']['transp']['transporta']['xNome']) ? $arrayNota['NFe']['infNFe']['transp']['transporta']['xNome'] : '';
                $notaData["codigo_antt"] = '';

                if (isset($arrayNota['NFe']['infNFe']['transp']['veicTransp'])) {
                    $notaData["placa_veiculo"] = $arrayNota['NFe']['infNFe']['transp']['veicTransp']['placa'];
                    $notaData["veiculo_uf"] = $arrayNota['NFe']['infNFe']['transp']['veicTransp']['UF'];
                }

                $notaData["transportadora_cnpj"] = isset($arrayNota['NFe']['infNFe']['transp']['transporta']['CPF']) ? $arrayNota['NFe']['infNFe']['transp']['transporta']['CPF'] : '';
                $notaData["transportadora_endereco"] = isset($arrayNota['NFe']['infNFe']['transp']['transporta']['xEnder']) ? $arrayNota['NFe']['infNFe']['transp']['transporta']['xEnder'] : '';
                $notaData["transportadora_municipio"] = isset($arrayNota['NFe']['infNFe']['transp']['transporta']['xMun']) ? $arrayNota['NFe']['infNFe']['transp']['transporta']['xMun'] : '';
                $notaData["transportadora_uf"] = isset($arrayNota['NFe']['infNFe']['transp']['transporta']['UF']) ? $arrayNota['NFe']['infNFe']['transp']['transporta']['UF'] : '';
                $notaData["transportadora_inscricao"] = isset($arrayNota['NFe']['infNFe']['transp']['transporta']['IE']) ? $arrayNota['NFe']['infNFe']['transp']['transporta']['IE'] : '';
                $notaData["transportadora_quantidade"] = isset($arrayNota['NFe']['infNFe']['transp']['vol']['qVol']) ? $arrayNota['NFe']['infNFe']['transp']['vol']['qVol'] : 0;
                $notaData["especie"] = isset($arrayNota['NFe']['infNFe']['transp']['vol']['esp']) ? $arrayNota['NFe']['infNFe']['transp']['vol']['esp'] : '';
                $notaData["marca"] = isset($arrayNota['NFe']['infNFe']['transp']['vol']['marca']) ? $arrayNota['NFe']['infNFe']['transp']['vol']['marca'] : '';
                $notaData["numeracao"] = '';
                $notaData["peso_bruto"] = isset($arrayNota['NFe']['infNFe']['transp']['vol']['pesoB']) ? $arrayNota['NFe']['infNFe']['transp']['vol']['pesoB'] : '';
                $notaData["peso_liquido"] = isset($arrayNota['NFe']['infNFe']['transp']['vol']['pesoL']) ? $arrayNota['NFe']['infNFe']['transp']['vol']['pesoL'] : '';
            }

            $notaData["dados_adicionais"] = isset($arrayNota['NFe']['infNFe']['infAdic']['infCpl']) ? $arrayNota['NFe']['infNFe']['infAdic']['infCpl'] : '';

            $faturas = [];
            $items = [];

            $detalhes = $arrayNota['NFe']['infNFe']['det'];

            if (count($detalhes) > 0) {

                if (isset($detalhes["prod"])) {
                    $detalhes[] = $detalhes;
                }

                foreach ($detalhes as $detalhe) {

                    try {

                        if (isset($detalhe["prod"])) {

                            $icms = 0;
                            $pis = 0;
                            $ipi = 0;
                            $confins = 0;
                            $totalTributo = 0;

                            if (isset($detalhe['imposto']['ICMS']['ICMS00']['vICMS'])) {
                                $icms = $detalhe['imposto']['ICMS']['ICMS00']['vICMS'];
                            }

                            if (isset($detalhe['imposto']['PIS']['PISAliq']['vPIS'])) {
                                $pis = $detalhe['imposto']['PIS']['PISAliq']['vPIS'];
                            }

                            if (isset($detalhe['imposto']['IPI']['IPIAliq']['vIPI'])) {
                                $ipi = $detalhe['imposto']['IPI']['IPIAliq']['vIPI'];
                            }

                            if (isset($detalhe['imposto']['COFINS']['COFINSAliq']['vCOFINS'])) {
                                $confins = $detalhe['imposto']['COFINS']['COFINSAliq']['vCOFINS'];
                            }

                            if (isset($detalhe['prod']['vUnTrib'])) {
                                $totalTributo = $detalhe['prod']['qTrib'] * $detalhe['prod']['vUnTrib'];
                            }

                            $itemData = [
                                'ncm' => $detalhe['prod']['NCM'],
                                'cfop' => $detalhe['prod']['CFOP'],
                                'nome_produto' => $detalhe['prod']['xProd'],
                                'codigo_produto' => $detalhe['prod']['cProd'],
                                'ean' => count($detalhe['prod']['cEAN']) > 0 ? json_encode($detalhe['prod']['cEAN']) : '',
                                'qtd' => $detalhe['prod']['qCom'],
                                'valor_unitario' => $detalhe['prod']['vUnCom'],
                                'valor_total' => $detalhe['prod']['vProd'],
                                'unidade' => $detalhe['prod']['uCom'],
                                'base_calculo_icms' => isset($detalhe['imposto']['ICMS']['ICMS00']['vBC']) ? $detalhe['imposto']['ICMS']['ICMS00']['vBC'] : 0,
                                'aliquota_icms' => isset($detalhe['imposto']['ICMS']['ICMS00']['pICMS']) ? $detalhe['imposto']['ICMS']['ICMS00']['pICMS'] : 0,
                                'valor_icms' => isset($detalhe['imposto']['ICMS']['ICMS00']['vICMS']) ? $detalhe['imposto']['ICMS']['ICMS00']['vICMS'] : 0,

                                'valor_ipi' => '',
                                'aliquota_ipi' => '',
                                'situacao_tributacao_ipi' => isset($detalhe['imposto']['IPI']['IPINT']['CST']) ? $detalhe['imposto']['IPI']['IPINT']['CST'] : 0,
                                'codigo_enquadramento_ipi' => isset($detalhe['imposto']['IPI']['cEnq']) ? $detalhe['imposto']['IPI']['cEnq'] : NULL,

                                'aliquota_cofins' => isset($detalhe['imposto']['COFINS']['COFINSAliq']['pCOFINS']) ? $detalhe['imposto']['COFINS']['COFINSAliq']['pCOFINS'] : 0,
                                'valor_cofins' => isset($detalhe['imposto']['COFINS']['COFINSAliq']['vCOFINS']) ? $detalhe['imposto']['COFINS']['COFINSAliq']['vCOFINS'] : 0,
                                'base_calculo_cofins' => isset($detalhe['imposto']['COFINS']['COFINSAliq']['vBC']) ? $detalhe['imposto']['COFINS']['COFINSAliq']['vBC'] : 0,
                                'situacao_tributacao_cofins' => isset($detalhe['imposto']['COFINS']['COFINSAliq']['CST']) ? $detalhe['imposto']['COFINS']['COFINSAliq']['CST'] : 0,

                                'aliquota_pis' => isset($detalhe['imposto']['PIS']['PISAliq']['pPIS']) ? $detalhe['imposto']['PIS']['PISAliq']['pPIS'] : 0,
                                'valor_pis' => isset($detalhe['imposto']['PIS']['PISAliq']['vPIS']) ? $detalhe['imposto']['PIS']['PISAliq']['vPIS'] : 0,
                                'base_calculo_pis' => isset($detalhe['imposto']['PIS']['PISAliq']['vBC']) ? $detalhe['imposto']['PIS']['PISAliq']['vBC'] : 0,
                                'situacao_tributacao_pis' => isset($detalhe['imposto']['PIS']['PISAliq']['CST']) ? $detalhe['imposto']['PIS']['PISAliq']['CST'] : 0,
                            ];

                            if (isset($detalhe['imposto']['ICMSUFDest'])) {
                                $itemData['base_calculo_icms_uf_dest'] = $detalhe['imposto']['ICMSUFDest']['vBCUFDest'];
                                $itemData['aliquota_fcp_icms_uf_dest'] = $detalhe['imposto']['ICMSUFDest']['pFCPUFDest'];
                                $itemData['aliquota_icms_uf_dest'] = $detalhe['imposto']['ICMSUFDest']['pICMSUFDest'];
                                $itemData['aliquota_icms_uf_interna'] = $detalhe['imposto']['ICMSUFDest']['pICMSInter'];
                                $itemData['aliquota_icms_uf_interna_part'] = $detalhe['imposto']['ICMSUFDest']['pICMSInterPart'];
                                $itemData['valor_fcp_icms_uf_dest'] = $detalhe['imposto']['ICMSUFDest']['vFCPUFDest'];
                                $itemData['valor_icms_uf_dest'] = $detalhe['imposto']['ICMSUFDest']['vICMSUFDest'];
                                $itemData['valor_icms_uf_remetente'] = $detalhe['imposto']['ICMSUFDest']['vICMSUFRemet'];
                            }

                            array_push($items, $itemData);
                        }

                    } catch (\Exception $e) {
                        \Log::error($e);
                        dd($e, $detalhe);
                    }
                }

                $faturasArr = isset($arrayNota['NFe']['infNFe']['cobr']) ? $arrayNota['NFe']['infNFe']['cobr'] : null;
                if ($faturasArr) {
                    if (isset($faturasArr['dup'][0])) {

                        foreach ($faturasArr['dup'] as $f) {
                            $faturas[] = [
                                'numero' => $f['nDup'],
                                'vencimento' => $f['dVenc'],
                                'valor' => $f['vDup'],
                            ];
                        }

                    } else if (isset($faturasArr['dup'])) {
                        $faturas[] = [
                            'numero' => $faturasArr['dup']['nDup'],
                            'vencimento' => $faturasArr['dup']['dVenc'],
                            'valor' => $faturasArr['dup']['vDup'],
                        ];
                    } else if (isset($faturasArr['fat'])) {
                        $faturas[] = [
                            'numero' => $faturasArr['fat']['nFat'],
                            'vencimento' => null,
                            'valor' => $faturasArr['fat']['vLiq'],
                        ];
                    }
                }
            }

            $notaData['itens'] = $items;
            $notaData['faturas'] = $faturas;

            return $notaData;
        } catch (\Exception $e) {
            \Log::error($e);
            dd($e, $notaData);
        }

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

        $contadorNFe = 0;

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
            $aDocs = array();
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
            $notaData = [];
            foreach ($aDocs as $doc) {

                $NSU = $doc['NSU'];

                if ($doc['schema'] == $procNFe) {

                        $notaData = $this->extraiData($doc['dados'], $NSU, $doc['schema']);

                        $this->saveData($notaData, $contadorNFe);


                } elseif ($doc['schema'] == $resNFe) {

                    $nota = simplexml_load_string($doc['dados']);
                    $arrayNota = json_decode(json_encode((array)$nota), TRUE);

                    $nfObjRes = Notafiscal::where("chave", $arrayNota['chNFe'])->first();

                    if (!$nfObjRes) {

                        $notaData = [
                            'nsu' => $NSU,
                            'schema' => $doc['schema'],
                            'xml' => $doc['dados'],
                            'data_emissao' => isset($arrayNota['dhEmi']) ? str_replace('T', ' ', substr($arrayNota['dhEmi'], 0, 19)) : null,
                            'cnpj' => $arrayNota['CNPJ'],
                            'razao_social' => $arrayNota['xNome'],
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
                            $nfObjRes->fill($notaData);
                            $nfObjRes->save();
                        }
                    }

                    $contadorNFe++;
                }
            }

            return $contadorNFe;
        } catch (\Exception $e) {
            \Log::error($e);
            dd($e);
            return false;
        }
    }

    public function saveData($notaData, &$contadorNFe = 0)
    {
        $nfObjArr = $this->notaFiscalRepository->findByField('chave', $notaData['chave']);
        $nfObj = isset($nfObjArr[0]) ? $nfObjArr[0] : null;

        $itens = $notaData['itens'];
        unset($notaData['itens']);

        $faturas = $notaData['faturas'];
        unset($notaData['faturas']);

        if (!$nfObj) {
            $nfObj = $this->notaFiscalRepository->create($notaData);
        } else {
            $nfObj->fill($notaData);
            $nfObj->save();
        }

        $contadorNFe++;

        foreach ($itens as $item) {
            if (!$itemObj = $nfObj->itens()->where('codigo_produto', $item['codigo_produto'])->first()) {
                $nfObj->itens()->save(new NotaFiscalItem($item));
            } else {
                $itemObj->fill($item);
                $itemObj->save();
            }
        }

        foreach ($faturas as $fatura) {
            if (!$itemObj = $nfObj->faturas()->where('numero', $fatura['numero'])->first()) {
                $nfObj->faturas()->save(new NotaFiscalFatura($fatura));
            } else {
                $itemObj->fill($fatura);
                $itemObj->save();
            }
        }

        return $nfObj;
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

    public function geraDanfe($notaFiscal)
    {
        $nfe = new ToolsNFe(config_path('nfe.json'));
        $danfe = new \NFePHP\Extras\Danfe(
            $notaFiscal->xml,
            'P',
            'A4',
            $nfe->aConfig['aDocFormat']->pathLogoFile,
            'I',
            '');
        $id = $danfe->montaDANFE();
        return $abre = $danfe->printDANFE("{$id}-danfe.pdf", 'I');
    }

    public function manifesta($notaFiscal, $operacao = '210210', $fromCommand = 0)
    {
        if ($fromCommand) {
            $nfe = new ToolsNFe(config_path('nfe-command.json'));
        } else {
            $nfe = new ToolsNFe(config_path('nfe.json'));
        }

        $nfe->setModelo('55');

        //210200 – Confirmação da Operação
        //210210 – Ciência da Operação
        //210220 – Desconhecimento da Operação
        //210240 – Operação não Realizada ===> é obritatoria uma justificativa para esse caso
        $chave = $notaFiscal->chave;
        $tpAmb = '1';
        $xJust = '';
        $tpEvento = $operacao; //ciencia da operação
        $aResposta = array();
        $nfe->sefazManifesta($chave, $tpAmb, $xJust, $tpEvento, $aResposta);

        try {

            $cStat = $aResposta['evento'][0]['cStat'];
            $xMotivo = $aResposta['evento'][0]['xMotivo'];
            $notaFiscal->manifesto += 1;
            $notaFiscal->retorno_manifesto_motivo = $xMotivo;
            $notaFiscal->manifesto_status = $cStat;
            $notaFiscal->save();

            return [
                'id' => $notaFiscal->id,
                'chave' => $notaFiscal->chave,
                'status' => $cStat,
                'motivo' => $xMotivo,
            ];

        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }

    public function manifestaNotas($fromCommand = 0, $operacao = '210210')
    {
        $notas = Notafiscal::where('schema', 'resNFe_v1.00.xsd')
            ->where('manifesto', 0)
            ->orderBy('id', 'asc')
            ->take(100)
            ->get();

        $nfe = [];
        foreach ($notas as $nfeObj)
        {
            $nfe[] = $this->manifesta($nfeObj, $operacao, $fromCommand);
        }

        return $nfe;
    }

}
