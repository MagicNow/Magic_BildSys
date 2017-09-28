<?php
/**
 * Created by PhpStorm.
 * User: Raul
 * Date: 27/06/2017
 * Time: 19:19
 */

namespace App\Repositories;

use App\Models\Notafiscal;
use Log;
use DB;

class MegaNfeIntegracaoRepository
{
    public $notaFiscalRepository;

    public function __construct(NotafiscalRepository $notaFiscalRepository)
    {
        $this->notaFiscalRepository = $notaFiscalRepository;
    }

    public static function integra($id)
    {
        $notafiscal = Notafiscal::with(
            [
                "itens",
                "itens.solicitacaoEntregaItens",
                "itens.solicitacaoEntregaItens.insumo",
                "contrato",
                "contrato.obra"
            ])
            ->where('id', $id)
            ->first();

        $mega = new MegaXmlRepository();

        $data = [
            'OPERACAO' => 'I',
            'FIL_IN_CODIGO' => $notafiscal->contrato->obra->filial_id,//Codigo Filial
            'ACAO_IN_CODIGO' => 891, //Codigo da Ação
            'CPAG_TPD_ST_CODIGO' => 'NF MAT',
            'AGN_IN_CODIGO' => $notafiscal->contrato->fornecedor->codigo_mega,
            'AGN_TAU_ST_CODIGO' => 'COD',
            'RCB_ST_NOTA' => $notafiscal->codigo,
            'SER_ST_CODIGO' => $notafiscal->serie,
            'TDF_ST_SIGLA' => 'NF-E',
            'RCB_DT_DOCUMENTO' => $notafiscal->data_emissao->format("d/m/Y"),
            'RCB_DT_MOVIMENTO' => $notafiscal->status_data->format("d/m/Y"),
            'TPR_ST_TIPOPRECO' => $notafiscal->frete_por_conta == 1 ? 'FOB' : 'CIF',
            'COND_ST_CODIGO' => NULL,///Vai pegar do cadastro de pagamentos
            'CCF_IN_REDUZIDO' => $notafiscal->contrato->obra->codigo_centro_de_custo,
            'PROJ_IN_REDUZIDO' => $notafiscal->contrato->obra->codigo_projeto_padrao,
            'RCB_RE_VALDESCGERAL' => $notafiscal->desconto,
            'RCB_RE_VALACREGERAL' => 0.00,//Verificar Campo
            'RCB_RE_VALDESCONTOS' => 0.00,//Verificar Campo
            'RCB_RE_TOTALFRETE' => $notafiscal->valor_frete,
            'RCB_RE_TOTALSEGURO' => $notafiscal->valor_seguro,
            'RCB_RE_TOTALDESPACESS' => 0.00,//Verificar Campo
            'RCB_RE_TOTALNOTA' => $notafiscal->valor_total_nota,
            'RCB_RE_VLMERCADORIA' => $notafiscal->valor_total_produtos,
            'RCB_RE_VLICMS' => $notafiscal->valor_icms,
            'RCB_RE_VLICMSRETIDO' => $notafiscal->valor_icms_sub,//Verificar Campo
            'RCB_RE_VLIPI' => $notafiscal->valor_ipi,
            'RCB_RE_TOTALMAOOBRA' => 0.00,//Verificar Campo
            'RCB_RE_BASEICMS' => $notafiscal->base_calculo_icms,
            'RCB_RE_TOTALISS' => 0.00,//Verificar Campo
            'RCB_RE_TOTALIRRF' => 0.00,//Verificar Campo
            'RCB_RE_TOTALIMPORTACAO' => 0.00,//Verificar Campo
            'RCB_RE_DESPNAOTRIB' => NULL,//Verificar Campo
            'RCB_RE_BASESUBTRIB' => $notafiscal->base_calculo_icms_sub,//Verificar Campo
            'RCB_RE_TOTALINSS' => NULL,//Verificar Campo
            'RCB_CL_OBSTRF' => NULL,//Verificar Campo
            'RCB_CL_INFADIC' => $notafiscal->dados_adicionais,
            'RCB_ST_OBSFIN' => NULL,//Verificar Campo
            'RCB_RE_SESTSENAT' => NULL,//Verificar Campo
            'RCB_RE_VLPIS' => $notafiscal->valor_pis,
            'RCB_RE_VLCOFINS' => $notafiscal->valor_cofins,
            'RCB_RE_TOTALCSLL' => NULL,//Verificar Campo
            //Tipo de Transporte deve ser: AE (Aéreo), (FE) Ferroviário, (MA) Marítimo ou (RO) Rodoviário
            'RCB_CH_TIPOTRANS' => 'RO',
            'RCB_ST_PLACA1' => $notafiscal->placa_veiculo,
            'RCB_ST_PLACA2' => NULL,//Verificar Campo
            'RCB_ST_PLACA3' => NULL,//Verificar Campo
            'RCB_RE_VLDESADUANEIRA' => 0.00,//Verificar Campo
            'RCB_RE_OUTRASDESPIMP' => 0.00,//Verificar Campo
            'DRF_ST_CODIGOIR' => 1708, //Verificar com Fernanda/Lucas
            'RCB_BO_CALCULARVALORES' => 'N',
            'RCB_ST_CHAVEACESSO' => $notafiscal->chave,
            'RCB_RE_ICMSSTRECUPERA' => 0.00,//Verificar Campo
            'RCB_RE_BASESUBTRIBANT' => 0.00,//Verificar Campo
            'RCB_RE_VLICMSRETIDOANT' => 0.00,//Verificar Campo
            'RCB_RE_BASEFUNRURAL' => 0.00,//Verificar Campo
            'RCB_RE_VALORFUNRURAL' => 0.00,//Verificar Campo

            'itens' => []
        ];

        foreach ($notafiscal->itens as $k => $item) {

            $solicitacoes = $item->solicitacaoEntregaItens;
            $insumos = [];
            foreach ($solicitacoes as $solicitacao) {
                $insumos[] = $solicitacao->insumo;
            }

            $codigoInsumo = $insumos[0]->codigo;

            $item = [
                //'ItensRecebimento' => NULL,
                'ITENS_OPERACAO' => 'I',
                'ITENS_RCI_IN_SEQUENCIA' => $k + 1,
                'ITENS_PRO_ST_ALTERNATIVO' => 'COD',
                'ITENS_PRO_IN_CODIGO' => $codigoInsumo,
                'ITENS_RCI_RE_QTDEACONVERTER' => $item->qtd,
                'ITENS_UNI_ST_UNIDADEFMT' => $item->unidade,
                'ITENS_RCI_RE_VLMERCADORIA' => $item->valor_total,
                'ITENS_RCI_RE_VLIPI' => $item->valor_ipi,
                'ITENS_RCI_RE_VLFRETE' => NULL,//Verificar Campo
                'ITENS_RCI_RE_VLSEGURO' => NULL,//Verificar Campo
                'ITENS_RCI_RE_VLDESPESA' => NULL,//Verificar Campo
                'ITENS_RCI_RE_PERCICM' => $item->aliquota_icms,
                'ITENS_RCI_RE_PERCIPI' => $item->aliquota_ipi,
                'ITENS_RCI_RE_VLMOBRAP' => NULL,//Verificar Campo
                'ITENS_RCI_RE_PEDESC' => NULL,//Verificar Campo
                'ITENS_RCI_RE_VLDESC' => NULL,//Verificar Campo
                'ITENS_RCI_RE_VLDESCPROP' => NULL,//Verificar Campo
                'ITENS_RCI_RE_VLFINANCPROP' => NULL,//Verificar Campo
                'ITENS_RCI_RE_VLIMPORTACAO' => NULL,//Verificar Campo
                'ITENS_RCI_RE_VLICMS' => $item->valor_icms,
                'ITENS_RCB_ST_NOTA' => $notafiscal->codigo,
                'ITENS_UNI_ST_UNIDADE' => $insumos[0]->unidade_sigla,
                //Torna-se obrigatório quando a Unidade de Medida utilizada no Recebimento for diferente da Unidade de Medida Padrão.
                'ITENS_FMT_ST_CODIGO' => NULL,
                //121 para serviço,
                //101 para material,
                //111 Energia Elétrica,
                //992 Serviço de Comunicação
                //993 Frete
                'ITENS_APL_IN_CODIGO' => 101,
                // 150 para material p/ construção,
                // 151 serviços técnicos,
                // 174 gastos com canteiro de obra,
                // 152 Mão de Obra para levantamento da obra
                'ITENS_TPC_ST_CLASSE' => 150,
                // Código de Aplicação ->
                // CFOP  = 121 -> 1933
                // 101 -> 1949
                // 111 -> 1253
                // 992 -> 1353
                // 993 -> 1303
                'ITENS_CFOP_IN_CODIGO' => $item->cfop,
                'ITENS_COS_IN_CODIGO' => NULL,//Verificar Campo
                'ITENS_UF_LOC_ST_SIGLA' => $notafiscal->destinatario_uf,
                'ITENS_MUN_LOC_IN_CODIGO' => $notafiscal->destinatario_cidade_codigo,
                // "Almoxarifado do Item"
                'ITENS_ALM_IN_CODIGO' => NULL,
                // "Localização do Item"
                'ITENS_LOC_IN_CODIGO' => NULL,
                'ITENS_RCI_RE_VALORPVV' => NULL,
                'ITENS_RCI_RE_VLICMRETIDO' => NULL,
                'ITENS_RCI_RE_VLISENIPI' => NULL,
                'ITENS_RCI_RE_IPIRECUPERA' => NULL,
                'ITENS_RCI_RE_VLOUTRIPI' => NULL,
                'ITENS_RCI_RE_VLBASEIPI' => NULL,
                'ITENS_RCI_RE_ICMSRECUPERA' => NULL,
                'ITENS_RCI_RE_VLISENICM' => NULL,
                'ITENS_RCI_RE_VLOUTRICM' => NULL,
                'ITENS_RCI_RE_VLBASEICM' => $item->base_calculo_icms,
                'ITENS_RCI_RE_VALDIFICMS' => NULL,
                'ITENS_RCI_RE_BASEISS' => 0.00,
                'ITENS_RCI_RE_PERISS' => 0.00,
                'ITENS_RCI_RE_VLISS' => 0.00,
                'ITENS_RCI_RE_BASEINSS' => NULL,
                'ITENS_RCI_RE_PERINSS' => NULL,
                'ITENS_RCI_RE_VLINSS' => NULL,
                'ITENS_RCI_RE_BASEIRRF' => NULL,
                'ITENS_RCI_RE_PERIRRF' => NULL,
                'ITENS_RCI_RE_VLIRRF' => NULL,
                'ITENS_RCI_RE_BASESUBTRIB' => NULL,
                'ITENS_RCI_RE_PERDIFICMS' => NULL,
                'ITENS_RCI_ST_NCM_EXTENSO' => $item->ncm,
                'ITENS_RCI_CH_STICMS_A' => NULL,
                'ITENS_RCI_CH_STICMS_B' => NULL,
                'ITENS_RCI_RE_VLDESPNAOTRIB' => NULL,
                'ITENS_RCI_RE_VALORMOEDA' => NULL,
                'ITENS_RCI_RE_VLICMRETIDOANT' => NULL,
                'ITENS_RCI_RE_BASESUBTRIBANT' => NULL,
                'ITENS_RCI_RE_VLPISRETIDO' => NULL,
                // "Valor PIS Recuperado"
                'ITENS_RCI_RE_VLPISRECUPERA' => NULL,
                'ITENS_RCI_RE_PERCPIS' => $item->aliquota_pis,
                'ITENS_RCI_RE_VLPIS' => $item->valor_pis,
                'ITENS_RCI_RE_VLBASEPIS' => $item->base_calculo_pis,
                'ITENS_RCI_RE_VLCOFINSRETIDO' => NULL,
                'ITENS_RCI_RE_VLCOFINSRECUPERA' => NULL,
                'ITENS_RCI_RE_PERCCOFINS' => $item->aliquota_cofins,
                'ITENS_RCI_RE_VLCOFINS' => $item->valor_cofins,
                'ITENS_RCI_RE_VLBASECOFINS' => $item->base_calculo_cofins,
                'ITENS_RCI_RE_PERCSLL' => 0,
                'ITENS_RCI_RE_VLBASECSLL' => 0,
                'ITENS_RCI_RE_VLCSLL' => NULL,
                'ITENS_NAT_ST_CODIGO' => NULL,
                'ITENS_RCI_RE_VLICMSDIFERIDO' => NULL,
                'ITENS_RCI_RE_VLDESADUANEIRA' => NULL,
                'ITENS_RCI_RE_OUTRASDESPIMP' => NULL,
                'ITENS_RCI_ST_REFERENCIA' => NULL,
                'ITENS_RCI_BO_GENERICO' => NULL,
                'ITENS_COMPL_ST_DESCRICAO' => NULL,
                'ITENS_COSM_IN_CODIGO' => NULL,
                'ITENS_NCM_IN_CODIGO' => $item->ncm,
                'ITENS_RCO_ST_COMPLEMENTO' => NULL,
                'ITENS_RCI_BO_CALCULARVALORES' => 'N',
                'ITENS_RCI_RE_ICMSSTRECUPERA' => NULL,
                'ITENS_RCI_RE_BASEFUNRURAL' => NULL,
                'ITENS_RCI_RE_ALIQFUNRURAL' => NULL,
                'ITENS_RCI_RE_VALORFUNRURAL' => NULL,
                'ITENS_STS_ST_CSOSN' => NULL,
                //"Situação Tributária do IPI"
                'ITENS_RCI_ST_STIPI' => $item->situacao_tributacao_ipi,
                //"Código da Situação Tributária do PIS"
                'ITENS_STP_ST_CSTPIS' => $item->situacao_tributacao_pis,
                //"Código da Situação Tributária do COFINS"
                'ITENS_STC_ST_CSTCOFINS' => $item->situacao_tributacao_cofins,
                'ITENS_RCI_RE_VLBASESESTSENAT' => NULL,
                'ITENS_RCI_RE_PERCSESTSENAT' => NULL,
                'ITENS_RCI_RE_VLSESTSENAT' => NULL,
                'ITENS_RCI_CH_DEFIPI' => NULL,
                'ITENS_RCI_RE_PAUTAIPI' => NULL,
                // "Cód.Enquadramento IPI"
                'ITENS_ENI_ST_CODIGO' => $item->codigo_enquadramento_ipi,

                //'LotesVinculados' => NULL,
                'LOTES_OPERACAO' => 'I',
                'LOTES_MVS_ST_LOTEFORNE' => NULL,
                'LOTES_MVT_DT_MOVIMENTO' => NULL,
                'LOTES_MVS_DT_VALIDADE' => NULL,
                'LOTES_MVS_ST_REFERENCIA' => NULL,
                'LOTES_ALM_IN_CODIGO' => NULL,
                'LOTES_LOC_IN_CODIGO' => NULL,
                'LOTES_NAT_ST_CODIGO' => NULL,
                'LOTES_MVS_DT_ENTRADA' => NULL,
                'LOTES_LMS_RE_QUANTIDADE' => NULL,
                'LOTES_RCI_IN_SEQUENCIA' => NULL,

                //'CentroCusto' => NULL,
                'CENTROCUSTO_OPERACAO' => 'I',
                'CENTROCUSTO_RCB_ST_NOTA' => NULL,
                'CENTROCUSTO_RCI_IN_SEQUENCIA' => NULL,
                'CENTROCUSTO_IRC_IN_SEQUENCIA' => NULL,
                'CENTROCUSTO_CCF_IN_REDUZIDO' => NULL,
                'CENTROCUSTO_LOTES_CCF_IN_REDUZIDO' => NULL,
                'CENTROCUSTO_LOTES_TPC_ST_CLASSE' => NULL,
                'CENTROCUSTO_IRC_RE_PERC' => NULL,
                'CENTROCUSTO_IRC_RE_VLPROP' => NULL,
                'CENTROCUSTO_TPC_ST_CLASSE' => NULL,

                //'Projetos' => NULL,
                'PROJETOS_OPERACAO' => 'I',
                'PROJETOS_RCB_ST_NOTA' => NULL,
                'PROJETOS_RCI_IN_SEQUENCIA' => NULL,
                'PROJETOS_IRC_IN_SEQUENCIA' => NULL,
                'PROJETOS_IRP_IN_SEQUENCIA' => NULL,
                'PROJETOS_PROJ_IN_REDUZIDO' => NULL,
                'PROJETOS_TPC_ST_CLASSE' => NULL,
                'PROJETOS_IRP_RE_PERC' => NULL,
                'PROJETOS_IRP_RE_VLPROP' => NULL,

                //'Parcelas' => NULL,
                'PARCELAS_OPERACAO' => 'I',
                'PARCELAS_RCB_ST_NOTA' => NULL,
                'PARCELAS_MOV_ST_DOCUMENTO' => NULL,
                'PARCELAS_MOV_ST_PARCELA' => NULL,
                'PARCELAS_MOV_DT_VENCTO' => NULL,
                'PARCELAS_MOV_RE_VALORMOE' => NULL
            ];

            array_push($data['itens'], $item);
        }

        $xml = $mega->montaXMLNotaMaterial($data);
        // $xml = str_replace('<?xml version="1.0" encoding="UTF-8"? >', '', $xml);
        // -- Numero da Tarefa (para Entrada de Nota = (701 - Insumos / 705 - Recebimentos / 306 - Faturas a Pagar)
        // -- XML desenvolvimento pela Mazzatech

        $porta = 705; // Porta de integração de Notas fiscais

        try {
            $sql = "BEGIN
                    MGCLI.pck_bld_app.F_Int_Integrador(:porta, :xml);
                    END;";

            $pdo = DB::connection('oracle')
                ->getPdo();
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':porta', $porta);
            $stmt->bindParam(':xml', $xml);

            //$result = [];
            $result = $stmt->execute();

            dump($result, $xml);

        } catch (\Exception $e) {
            dump($e);
        }

    }
}
