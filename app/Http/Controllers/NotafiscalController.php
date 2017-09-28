<?php

namespace App\Http\Controllers;

use App\DataTables\NotafiscalDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateNotafiscalRequest;
use App\Http\Requests\UpdateNotafiscalRequest;
use App\Models\Contrato;
use App\Models\Cte;
use App\Models\DocumentoTipo;
use App\Models\Fornecedor;
use App\Models\Notafiscal;
use App\Models\NotaFiscalFatura;
use App\Models\NotaFiscalItem;
use App\Repositories\ConsultaCteRepository;
use App\Repositories\ConsultaNfeRepository;
use App\Repositories\MegaXmlRepository;
use App\Repositories\NotafiscalRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Response;

class NotafiscalController extends AppBaseController
{
    /** @var  NotafiscalRepository */
    private $notafiscalRepository;
    private $consultaRepository;
    private $consultaCteRepository;

    public function __construct(NotafiscalRepository $notafiscalRepo,
                                ConsultaNfeRepository $consultaRepo,
                                ConsultaCteRepository $consultaCteRepository)
    {
        $this->notafiscalRepository = $notafiscalRepo;
        $this->consultaRepository = $consultaRepo;
        $this->consultaCteRepository = $consultaCteRepository;
    }

    /**
     * Display a listing of the Notafiscal.
     *
     * @param NotafiscalDataTable $notafiscalDataTable
     * @return Response
     */
    public function index(NotafiscalDataTable $notafiscalDataTable)
    {
        return $notafiscalDataTable->render('notafiscals.index');
    }

    /**
     * Show the form for creating a new Notafiscal.
     *
     * @return Response
     */
    public function create()
    {
        $contrato = Contrato::select([
            'contratos.id',
            DB::raw("CONCAT('Contrato: ', contratos.id, ' - ','Fornecedor: ', fornecedores.nome) as nome")
        ])
            ->join('fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id')
            ->pluck('nome', 'contratos.id')->toArray();
        return view('notafiscals.create', compact('contrato'));
    }

    /**
     * Store a newly created Notafiscal in storage.
     *
     * @param CreateNotafiscalRequest $request
     *
     * @return Response
     */
    public function store(CreateNotafiscalRequest $request)
    {
        $input = $request->all();

        $notafiscal = $this->notafiscalRepository->create($input);

        Flash::success('Notafiscal ' . trans('common.saved') . ' ' . trans('common.successfully') . '.');

        return redirect(route('notafiscals.index'));
    }

    /**
     * Display the specified Notafiscal.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        if (empty($notafiscal)) {
            Flash::error('Notafiscal ' . trans('common.not-found'));

            return redirect(route('notafiscals.index'));
        }

        return view('notafiscals.show')->with('notafiscal', $notafiscal);
    }

    /**
     * Show the form for editing the specified Notafiscal.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $notafiscal = $this->notafiscalRepository
            ->with('itens')
            ->findWithoutFail($id);

        if (empty($notafiscal)) {
            Flash::error('Notafiscal ' . trans('common.not-found'));

            return redirect(route('notafiscals.index'));
        }

        $cnpj = $notafiscal->cnpj;
        $fornecedor = Fornecedor::where(\DB::raw("replace(replace(replace(fornecedores.cnpj,'-',''),'.',''),'/','')"), $cnpj)->first();
        $contratos = [];

        try {
            $contrato = Contrato::where('id', request('contrato', $notafiscal->contrato_id))
                ->where('fornecedor_id', $fornecedor->id)
                ->first();
        } catch (\Exception $e) {
            $contrato = null;
        }

        $itensSolicitacoes = [];
        if ($contrato) {
            $entregas = $contrato->entregas;
            if (count($entregas) > 0) {
                //$solicitacoes_de_entrega[$contrato->id] = [];
                foreach($entregas as $entrega) {
                    //$solicitacoes_de_entrega[$contrato->id][$entrega->id] = $entrega->id;

                    $itens = $entrega->itens;
                    foreach ($itens as $item)
                    {
                        $itensSolicitacoes[$item->id] = [
                            'id' => $item->id,
                            'nome' => $item->insumo->nome,
                            'qtd' => ($item->qtd),
                            'unidade_sigla' => $item->insumo->unidade_sigla,
                            'valor_unitario' => ($item->valor_unitario),
                            'valor_total' => ($item->valor_total)
                        ];
                    }
                }
            }
        }

        $contratos = Contrato::where('id', request('contrato', $notafiscal->contrato_id))
            ->pluck('id', 'id')
            ->toArray();

        return view('notafiscals.edit', compact('notafiscal',
                                                'fornecedor',
                                                'contratos',
                                                'itensSolicitacoes'));
    }

    /**
     * Update the specified Notafiscal in storage.
     *
     * @param  int $id
     * @param UpdateNotafiscalRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNotafiscalRequest $request)
    {
        $acao = $request->get('acao');

        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        if ($acao == 'Rejeitar') {
            Flash::error('Nota fiscal rejeitada ' . trans('common.successfully') . '.');

            $notafiscal->status = 'Rejeitada';
            $notafiscal->status_data = date('Y-m-d H:i:s');
            $notafiscal->status_user_id = auth()->user()->id;
            $notafiscal->save();

            return redirect(route('notafiscals.edit', [$id]));
        }

        $vinculosRequest = $request->get('vinculos');

        $notafiscal->status = 'Aceita';
        $notafiscal->status_data = date('Y-m-d H:i:s');
        $notafiscal->status_user_id = auth()->user()->id;
        $notafiscal->contrato_id = $request->get('contrato_id');
        $notafiscal->save();

        foreach ($vinculosRequest as $item_id => $solicitacoes_ids) {
            $itemNf = $notafiscal->itens()->where('id', $item_id)->first();
            $itemNf->solicitacaoEntregaItens()->attach($solicitacoes_ids);
        }

        Flash::success('Nota fiscal ' . trans('common.updated') . ' ' . trans('common.successfully') . '.');

        return redirect(route('notafiscals.edit', [$id]));
    }

    /**
     * Remove the specified Notafiscal from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        if (empty($notafiscal)) {
            Flash::error('Notafiscal ' . trans('common.not-found'));

            return redirect(route('notafiscals.index'));
        }

        $this->notafiscalRepository->delete($id);

        Flash::success('Notafiscal ' . trans('common.deleted') . ' ' . trans('common.successfully') . '.');

        return redirect(route('notafiscals.index'));
    }

    public function reprocessaNfe($id)
    {
        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        return $this->consultaRepository->reprocessaXML(
            $notafiscal->xml,
            $notafiscal->nsu,
            $notafiscal->schema
        );
    }

    public function pescadorNfe()
    {
        $result = $this->consultaRepository->syncXML(1);
        if ($result) {
            return "Sucesso - Notas Importadas ou Atualizadas: {$result} - Ultima consulta: " . date("d/m/Y H:i:s");
        }
        return "Não há notas para realizar a importação. Data: " . date("d/m/Y H:i:s");
    }

    public function buscaCTe()
    {
        if ( $this->consultaCteRepository->syncXML(1, 0) ) {
            return "Sucesso - Ultima consulta: " . date("d/m/Y H:i:s");
        }
        return "Não foram encontrados CTe's para download. Data: " . date("d/m/Y H:i:s");
    }

    public function visualizaDanfe($id)
    {
        $notafiscal = Notafiscal::find($id);
        return $this->consultaRepository->geraDanfe($notafiscal);
    }

    public function visualizaDacte($id)
    {
        $cte = Cte::find($id);
        return $this->consultaCteRepository->geraDacte($cte);
    }

    public function visualizaDacteV3($id)
    {
        $cte = Cte::find($id);
        return $this->consultaCteRepository->geraDacteV3($cte);
    }

    public function manifesta()
    {
        try {
            $notas = $this->consultaRepository->manifestaNotas();
            if (count($notas)) {
                return sprintf("%s notas manifestadas com sucesso.", count($notas));
            }
            return "Não há notas para manifestação.";
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function filtraFornecedorContratos()
    {
        $fornecedores = Fornecedor::whereHas('contratos')
            ->whereHas("contratos.entregas")
            ->orderBy('id')
            ->get();

        $contratos = [];
        $contratosArr = [];
        $fornecedoresArr = [];
        if ($fornecedores) {
            foreach ($fornecedores as $fornecedor) {
                if (
                    $contratosFornecedor = $fornecedor->contratos()
                        ->with("entregas", "obra")
                        ->get()
                    AND count($contratosFornecedor) > 0
                ) {
                    $fornecedoresArr[$fornecedor->id] = sprintf("%s [ %s ]", $fornecedor->nome, $fornecedor->cnpj);;

                    $contratos[$fornecedor->id] = $contratosFornecedor;
                    foreach ($contratosFornecedor as $c) {
                        $contratosArr[$fornecedor->id][$c->id] = [
                            'text' => sprintf('Contrato Nº: %s', $c->id),
                            'id' => $c->id
                        ];
                    }
                }
            }
        }

        $notasFiscais = [];
        foreach ($fornecedores as $fornecedor) {
            $notasFiscais[$fornecedor->id] = [];
            $notas = Notafiscal::where(\DB::raw(
                "replace(replace(replace(cnpj,'-',''),'.',''),'/','')"),
                str_replace(['.', '-', '/'], '',$fornecedor->cnpj
                )
            )->where("schema", "like", "%procNFe%")
                ->get();

            foreach ($notas as $nota) {
                $notasFiscais[$fornecedor->id][$nota->id] = [
                    'text' => sprintf('Nota Nº: %s', $nota->codigo),
                    'id' => $nota->id
                ];
            }
        }

        return view('notafiscals.filtro',
            compact('contratos', 'fornecedores', 'fornecedoresArr', 'notasFiscais', 'contratosArr')
        );
    }

    public function integraMega($id)
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
                'ITENS_FMT_ST_CODIGO' => NULL,
                'ITENS_APL_IN_CODIGO' => NULL,
                'ITENS_TPC_ST_CLASSE' => NULL,
                'ITENS_CFOP_IN_CODIGO' => NULL,
                'ITENS_COS_IN_CODIGO' => NULL,
                'ITENS_UF_LOC_ST_SIGLA' => NULL,
                'ITENS_MUN_LOC_IN_CODIGO' => NULL,
                'ITENS_ALM_IN_CODIGO' => NULL,
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
                'ITENS_RCI_RE_VLBASEICM' => NULL,
                'ITENS_RCI_RE_VALDIFICMS' => NULL,
                'ITENS_RCI_RE_BASEISS' => NULL,
                'ITENS_RCI_RE_PERISS' => NULL,
                'ITENS_RCI_RE_VLISS' => NULL,
                'ITENS_RCI_RE_BASEINSS' => NULL,
                'ITENS_RCI_RE_PERINSS' => NULL,
                'ITENS_RCI_RE_VLINSS' => NULL,
                'ITENS_RCI_RE_BASEIRRF' => NULL,
                'ITENS_RCI_RE_PERIRRF' => NULL,
                'ITENS_RCI_RE_VLIRRF' => NULL,
                'ITENS_RCI_RE_BASESUBTRIB' => NULL,
                'ITENS_RCI_RE_PERDIFICMS' => NULL,
                'ITENS_RCI_ST_NCM_EXTENSO' => NULL,
                'ITENS_RCI_CH_STICMS_A' => NULL,
                'ITENS_RCI_CH_STICMS_B' => NULL,
                'ITENS_RCI_RE_VLDESPNAOTRIB' => NULL,
                'ITENS_RCI_RE_VALORMOEDA' => NULL,
                'ITENS_RCI_RE_VLICMRETIDOANT' => NULL,
                'ITENS_RCI_RE_BASESUBTRIBANT' => NULL,
                'ITENS_RCI_RE_VLPISRETIDO' => NULL,
                'ITENS_RCI_RE_VLPISRECUPERA' => NULL,
                'ITENS_RCI_RE_PERCPIS' => NULL,
                'ITENS_RCI_RE_VLPIS' => NULL,
                'ITENS_RCI_RE_VLBASEPIS' => NULL,
                'ITENS_RCI_RE_VLCOFINSRETIDO' => NULL,
                'ITENS_RCI_RE_VLCOFINSRECUPERA' => NULL,
                'ITENS_RCI_RE_PERCCOFINS' => NULL,
                'ITENS_RCI_RE_VLCOFINS' => NULL,
                'ITENS_RCI_RE_VLBASECOFINS' => NULL,
                'ITENS_RCI_RE_PERCSLL' => NULL,
                'ITENS_RCI_RE_VLBASECSLL' => NULL,
                'ITENS_RCI_RE_VLCSLL' => NULL,
                'ITENS_NAT_ST_CODIGO' => NULL,
                'ITENS_RCI_RE_VLICMSDIFERIDO' => NULL,
                'ITENS_RCI_RE_VLDESADUANEIRA' => NULL,
                'ITENS_RCI_RE_OUTRASDESPIMP' => NULL,
                'ITENS_RCI_ST_REFERENCIA' => NULL,
                'ITENS_RCI_BO_GENERICO' => NULL,
                'ITENS_COMPL_ST_DESCRICAO' => NULL,
                'ITENS_COSM_IN_CODIGO' => NULL,
                'ITENS_NCM_IN_CODIGO' => NULL,
                'ITENS_RCO_ST_COMPLEMENTO' => NULL,
                'ITENS_RCI_BO_CALCULARVALORES' => NULL,
                'ITENS_RCI_RE_ICMSSTRECUPERA' => NULL,
                'ITENS_RCI_RE_BASEFUNRURAL' => NULL,
                'ITENS_RCI_RE_ALIQFUNRURAL' => NULL,
                'ITENS_RCI_RE_VALORFUNRURAL' => NULL,
                'ITENS_STS_ST_CSOSN' => NULL,
                'ITENS_RCI_ST_STIPI' => NULL,
                'ITENS_STP_ST_CSTPIS' => NULL,
                'ITENS_STC_ST_CSTCOFINS' => NULL,
                'ITENS_RCI_RE_VLBASESESTSENAT' => NULL,
                'ITENS_RCI_RE_PERCSESTSENAT' => NULL,
                'ITENS_RCI_RE_VLSESTSENAT' => NULL,
                'ITENS_RCI_CH_DEFIPI' => NULL,
                'ITENS_RCI_RE_PAUTAIPI' => NULL,
                'ITENS_ENI_ST_CODIGO' => NULL,

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

            $result = [];
            //$result = $stmt->execute();

            dump($result, $xml);

        } catch (\Exception $e) {
            dump($e);
        }

    }

}
