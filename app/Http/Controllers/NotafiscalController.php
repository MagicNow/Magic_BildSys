<?php

namespace App\Http\Controllers;

use App\DataTables\NotafiscalDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateNotafiscalRequest;
use App\Http\Requests\UpdateNotafiscalRequest;
use App\Models\Contrato;
use App\Models\Cte;
use App\Models\Fornecedor;
use App\Models\Notafiscal;
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
        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        if (empty($notafiscal)) {
            Flash::error('Notafiscal ' . trans('common.not-found'));

            return redirect(route('notafiscals.index'));
        }

        $cnpj = $notafiscal->cnpj;
        $fornecedor = Fornecedor::where(\DB::raw("replace(replace(replace(fornecedores.cnpj,'-',''),'.',''),'/','')"), $cnpj)->first();
        $contratos = [];
        if ($fornecedor) {
            $contratos = $fornecedor->contratos;
        }

        $solicitacoes_de_entrega = [];

        if ($contratos) {
            foreach ($contratos as $contrato) {
                $solicitacoes_de_entrega[$contrato->id] = [];
                if ($entregas = $contrato->entregas) {
                    $solicitacoes_de_entrega[$contrato->id] = $entregas;
                }
            }
        }

        return view('notafiscals.edit', compact('notafiscal',
                                                'fornecedor',
                                                'contratos',
                                                'solicitacoes_de_entrega'));
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
        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        if (empty($notafiscal)) {
            Flash::error('Notafiscal ' . trans('common.not-found'));

            return redirect(route('notafiscals.index'));
        }

        $notafiscal = $this->notafiscalRepository->update($request->all(), $id);

        Flash::success('Notafiscal ' . trans('common.updated') . ' ' . trans('common.successfully') . '.');

        return redirect(route('notafiscals.index'));
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
        $notafiscal = Notafiscal::find($id);
        $mega = new MegaXmlRepository();

        $data = [
            'OPERACAO' => 'I',
            'FIL_IN_CODIGO' => 1,//Codigo Filial
            'ACAO_IN_CODIGO' => 891, //Codigo da Ação
            'CPAG_TPD_ST_CODIGO' => 1,
            'AGN_IN_CODIGO' => 1,
            'AGN_TAU_ST_CODIGO' => 1,
            'RCB_ST_NOTA' => 1,
            'SER_ST_CODIGO' => 1,
            'TDF_ST_SIGLA' => 1,
            'RCB_DT_DOCUMENTO' => 1,
            'RCB_DT_MOVIMENTO' => 1,
            'TPR_ST_TIPOPRECO' => 1,
            'COND_ST_CODIGO' => 1,
            'CCF_IN_REDUZIDO' => 1,
            'PROJ_IN_REDUZIDO' => 1,
            'RCB_RE_VALDESCGERAL' => 1,
            'RCB_RE_VALACREGERAL' => 1,
            'RCB_RE_VALDESCONTOS' => 1,
            'RCB_RE_TOTALFRETE' => 1,
            'RCB_RE_TOTALSEGURO' => 1,
            'RCB_RE_TOTALDESPACESS' => 1,
            'RCB_RE_TOTALNOTA' => 1,
            'RCB_RE_VLMERCADORIA' => 1,
            'RCB_RE_VLICMS' => 1,
            'RCB_RE_VLICMSRETIDO' => 1,
            'RCB_RE_VLIPI' => 1,
            'RCB_RE_TOTALMAOOBRA' => 1,
            'RCB_RE_BASEICMS' => 1,
            'RCB_RE_TOTALISS' => 1,
            'RCB_RE_TOTALIRRF' => 1,
            'RCB_RE_TOTALIMPORTACAO' => 1,
            'RCB_RE_DESPNAOTRIB' => 1,
            'RCB_RE_BASESUBTRIB' => 1,
            'RCB_RE_TOTALINSS' => 1,
            'RCB_CL_OBSTRF' => 1,
            'RCB_CL_INFADIC' => 1,
            'RCB_ST_OBSFIN' => 1,
            'RCB_RE_SESTSENAT' => 1,
            'RCB_RE_VLPIS' => 1,
            'RCB_RE_VLCOFINS' => 1,
            'RCB_RE_TOTALCSLL' => 1,
            'RCB_CH_TIPOTRANS' => 1,
            'RCB_ST_PLACA1' => 1,
            'RCB_ST_PLACA2' => 1,
            'RCB_ST_PLACA3' => 1,
            'RCB_RE_VLDESADUANEIRA' => 1,
            'RCB_RE_OUTRASDESPIMP' => 1,
            'DRF_ST_CODIGOIR' => 1,
            'RCB_BO_CALCULARVALORES' => 1,
            'RCB_ST_CHAVEACESSO' => 1,
            'RCB_RE_ICMSSTRECUPERA' => 1,
            'RCB_RE_BASESUBTRIBANT' => 1,
            'RCB_RE_VLICMSRETIDOANT' => 1,
            'RCB_RE_BASEFUNRURAL' => 1,
            'RCB_RE_VALORFUNRURAL' => 1,
            //'ItensRecebimento' => 1,
            'ITENS_OPERACAO' => 'I',
            'ITENS_RCI_IN_SEQUENCIA' => 1,
            'ITENS_PRO_ST_ALTERNATIVO' => 1,
            'ITENS_PRO_IN_CODIGO' => 1,
            'ITENS_RCI_RE_QTDEACONVERTER' => 1,
            'ITENS_UNI_ST_UNIDADEFMT' => 1,
            'ITENS_RCI_RE_VLMERCADORIA' => 1,
            'ITENS_RCI_RE_VLIPI' => 1,
            'ITENS_RCI_RE_VLFRETE' => 1,
            'ITENS_RCI_RE_VLSEGURO' => 1,
            'ITENS_RCI_RE_VLDESPESA' => 1,
            'ITENS_RCI_RE_PERCICM' => 1,
            'ITENS_RCI_RE_PERCIPI' => 1,
            'ITENS_RCI_RE_VLMOBRAP' => 1,
            'ITENS_RCI_RE_PEDESC' => 1,
            'ITENS_RCI_RE_VLDESC' => 1,
            'ITENS_RCI_RE_VLDESCPROP' => 1,
            'ITENS_RCI_RE_VLFINANCPROP' => 1,
            'ITENS_RCI_RE_VLIMPORTACAO' => 1,
            'ITENS_RCI_RE_VLICMS' => 1,
            'ITENS_RCB_ST_NOTA' => 1,
            'ITENS_UNI_ST_UNIDADE' => 1,
            'ITENS_FMT_ST_CODIGO' => 1,
            'ITENS_APL_IN_CODIGO' => 1,
            'ITENS_TPC_ST_CLASSE' => 1,
            'ITENS_CFOP_IN_CODIGO' => 1,
            'ITENS_COS_IN_CODIGO' => 1,
            'ITENS_UF_LOC_ST_SIGLA' => 1,
            'ITENS_MUN_LOC_IN_CODIGO' => 1,
            'ITENS_ALM_IN_CODIGO' => 1,
            'ITENS_LOC_IN_CODIGO' => 1,
            'ITENS_RCI_RE_VALORPVV' => 1,
            'ITENS_RCI_RE_VLICMRETIDO' => 1,
            'ITENS_RCI_RE_VLISENIPI' => 1,
            'ITENS_RCI_RE_IPIRECUPERA' => 1,
            'ITENS_RCI_RE_VLOUTRIPI' => 1,
            'ITENS_RCI_RE_VLBASEIPI' => 1,
            'ITENS_RCI_RE_ICMSRECUPERA' => 1,
            'ITENS_RCI_RE_VLISENICM' => 1,
            'ITENS_RCI_RE_VLOUTRICM' => 1,
            'ITENS_RCI_RE_VLBASEICM' => 1,
            'ITENS_RCI_RE_VALDIFICMS' => 1,
            'ITENS_RCI_RE_BASEISS' => 1,
            'ITENS_RCI_RE_PERISS' => 1,
            'ITENS_RCI_RE_VLISS' => 1,
            'ITENS_RCI_RE_BASEINSS' => 1,
            'ITENS_RCI_RE_PERINSS' => 1,
            'ITENS_RCI_RE_VLINSS' => 1,
            'ITENS_RCI_RE_BASEIRRF' => 1,
            'ITENS_RCI_RE_PERIRRF' => 1,
            'ITENS_RCI_RE_VLIRRF' => 1,
            'ITENS_RCI_RE_BASESUBTRIB' => 1,
            'ITENS_RCI_RE_PERDIFICMS' => 1,
            'ITENS_RCI_ST_NCM_EXTENSO' => 1,
            'ITENS_RCI_CH_STICMS_A' => 1,
            'ITENS_RCI_CH_STICMS_B' => 1,
            'ITENS_RCI_RE_VLDESPNAOTRIB' => 1,
            'ITENS_RCI_RE_VALORMOEDA' => 1,
            'ITENS_RCI_RE_VLICMRETIDOANT' => 1,
            'ITENS_RCI_RE_BASESUBTRIBANT' => 1,
            'ITENS_RCI_RE_VLPISRETIDO' => 1,
            'ITENS_RCI_RE_VLPISRECUPERA' => 1,
            'ITENS_RCI_RE_PERCPIS' => 1,
            'ITENS_RCI_RE_VLPIS' => 1,
            'ITENS_RCI_RE_VLBASEPIS' => 1,
            'ITENS_RCI_RE_VLCOFINSRETIDO' => 1,
            'ITENS_RCI_RE_VLCOFINSRECUPERA' => 1,
            'ITENS_RCI_RE_PERCCOFINS' => 1,
            'ITENS_RCI_RE_VLCOFINS' => 1,
            'ITENS_RCI_RE_VLBASECOFINS' => 1,
            'ITENS_RCI_RE_PERCSLL' => 1,
            'ITENS_RCI_RE_VLBASECSLL' => 1,
            'ITENS_RCI_RE_VLCSLL' => 1,
            'ITENS_NAT_ST_CODIGO' => 1,
            'ITENS_RCI_RE_VLICMSDIFERIDO' => 1,
            'ITENS_RCI_RE_VLDESADUANEIRA' => 1,
            'ITENS_RCI_RE_OUTRASDESPIMP' => 1,
            'ITENS_RCI_ST_REFERENCIA' => 1,
            'ITENS_RCI_BO_GENERICO' => 1,
            'ITENS_COMPL_ST_DESCRICAO' => 1,
            'ITENS_COSM_IN_CODIGO' => 1,
            'ITENS_NCM_IN_CODIGO' => 1,
            'ITENS_RCO_ST_COMPLEMENTO' => 1,
            'ITENS_RCI_BO_CALCULARVALORES' => 1,
            'ITENS_RCI_RE_ICMSSTRECUPERA' => 1,
            'ITENS_RCI_RE_BASEFUNRURAL' => 1,
            'ITENS_RCI_RE_ALIQFUNRURAL' => 1,
            'ITENS_RCI_RE_VALORFUNRURAL' => 1,
            'ITENS_STS_ST_CSOSN' => 1,
            'ITENS_RCI_ST_STIPI' => 1,
            'ITENS_STP_ST_CSTPIS' => 1,
            'ITENS_STC_ST_CSTCOFINS' => 1,
            'ITENS_RCI_RE_VLBASESESTSENAT' => 1,
            'ITENS_RCI_RE_PERCSESTSENAT' => 1,
            'ITENS_RCI_RE_VLSESTSENAT' => 1,
            'ITENS_RCI_CH_DEFIPI' => 1,
            'ITENS_RCI_RE_PAUTAIPI' => 1,
            'ITENS_ENI_ST_CODIGO' => 1,
            //'LotesVinculados' => 1,
            'LOTES_OPERACAO' => 'I',
            'LOTES_MVS_ST_LOTEFORNE' => 1,
            'LOTES_MVT_DT_MOVIMENTO' => 1,
            'LOTES_MVS_DT_VALIDADE' => 1,
            'LOTES_MVS_ST_REFERENCIA' => 1,
            'LOTES_ALM_IN_CODIGO' => 1,
            'LOTES_LOC_IN_CODIGO' => 1,
            'LOTES_NAT_ST_CODIGO' => 1,
            'LOTES_MVS_DT_ENTRADA' => 1,
            'LOTES_LMS_RE_QUANTIDADE' => 1,
            'LOTES_RCI_IN_SEQUENCIA' => 1,
            //'CentroCusto' => 1,
            'CENTROCUSTO_OPERACAO' => 'I',
            'CENTROCUSTO_RCB_ST_NOTA' => 1,
            'CENTROCUSTO_RCI_IN_SEQUENCIA' => 1,
            'CENTROCUSTO_IRC_IN_SEQUENCIA' => 1,
            'CENTROCUSTO_CCF_IN_REDUZIDO' => 1,
            'CENTROCUSTO_LOTES_CCF_IN_REDUZIDO' => 1,
            'CENTROCUSTO_LOTES_TPC_ST_CLASSE' => 1,
            'CENTROCUSTO_IRC_RE_PERC' => 1,
            'CENTROCUSTO_IRC_RE_VLPROP' => 1,
            'CENTROCUSTO_TPC_ST_CLASSE' => 1,
            //'Projetos' => 1,
            'PROJETOS_OPERACAO' => 'I',
            'PROJETOS_RCB_ST_NOTA' => 1,
            'PROJETOS_RCI_IN_SEQUENCIA' => 1,
            'PROJETOS_IRC_IN_SEQUENCIA' => 1,
            'PROJETOS_IRP_IN_SEQUENCIA' => 1,
            'PROJETOS_PROJ_IN_REDUZIDO' => 1,
            'PROJETOS_TPC_ST_CLASSE' => 1,
            'PROJETOS_IRP_RE_PERC' => 1,
            'PROJETOS_IRP_RE_VLPROP' => 1,
            //'Parcelas' => 1,
            'PARCELAS_OPERACAO' => 'I',
            'PARCELAS_RCB_ST_NOTA' => 1,
            'PARCELAS_MOV_ST_DOCUMENTO' => 1,
            'PARCELAS_MOV_ST_PARCELA' => 1,
            'PARCELAS_MOV_DT_VENCTO' => 1,
            'PARCELAS_MOV_RE_VALORMOE' => 1
        ];

        $xml = $mega->montaXML($notafiscal, $data);
        // $xml = str_replace('<?xml version="1.0" encoding="UTF-8"? >', '', $xml);

        // -- Eu criaria um parametro pos caso mude não preisariamos acionar o desenvolvedor do produto
        // -- Eu criaria um parametro para essa porta, pois ela muda constantemente
        // -- Numero da Tarefa (para Entrada de Nota = (701 - Insumos / 705 - Recebimentos / 306 - Faturas a Pagar)
        // -- XML desenvolvimento pela Mazzatech

        $porta = 306;
        //$xml = '<x></x>';

        $Prexml = $xml; //addslashes($xml);
        /*
        try {
            $sql = "BEGIN
                    MGCLI.pck_bld_app.F_Int_Integrador({$porta}, '{$Prexml}');
                    END;";
            //$sql = str_replace("\n", "",$sql);
            $results = DB::connection('oracle')
                ->getPdo()
                ->exec($sql);
            dump($results);
        } catch (\Exception $e) {
            dump($e);
        }
        */
        try {
            $sql = "BEGIN
                    MGCLI.pck_bld_app.F_Int_Integrador(:porta, :xml);
                    END;";

            $pdo = DB::connection('oracle')
                ->getPdo();
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':porta', $porta);
            $stmt->bindParam(':xml', $xml);

            $result = $stmt->execute();

            dump($result, $xml);

        } catch (\Exception $e) {
            dump($e);
        }

    }

}
