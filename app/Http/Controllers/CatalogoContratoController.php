<?php

namespace App\Http\Controllers;

use App\DataTables\Admin\CatalogoContratoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateCatalogoContratoRequest;
use App\Http\Requests\Admin\UpdateCatalogoContratoRequest;
use App\Models\CatalogoContratoInsumo;
use App\Models\CatalogoContrato;
use App\Models\CatalogoContratoInsumoLog;
use App\Models\CatalogoContratoObra;
use App\Models\CatalogoContratoObraLog;
use App\Models\CatalogoContratoStatusLog;
use App\Models\Fornecedor;
use App\Models\Insumo;
use App\Models\MegaFornecedor;
use App\Repositories\Admin\CatalogoContratoRepository;
use App\Repositories\CodeRepository;
use App\Repositories\ImportacaoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Response;
use DB;

class CatalogoContratoController extends AppBaseController
{
    /** @var  CatalogoContratoRepository */
    private $catalogoContratoRepository;

    public function __construct(CatalogoContratoRepository $catalogoContratoRepo)
    {
        $this->catalogoContratoRepository = $catalogoContratoRepo;
    }

    /**
     * Display a listing of the CatalogoContrato.
     *
     * @param CatalogoContratoDataTable $catalogoContratoDataTable
     * @return Response
     */
    public function index(CatalogoContratoDataTable $catalogoContratoDataTable)
    {
        return $catalogoContratoDataTable->render('catalogo_contratos.index');
    }

    /**
     * Show the form for creating a new CatalogoContrato.
     *
     * @return Response
     */
    public function create()
    {
        $fornecedores = [];

        return view('catalogo_contratos.create', compact('fornecedores'));
    }

    /**
     * Store a newly created CatalogoContrato in storage.
     *
     * @param CreateCatalogoContratoRequest $request
     *
     * @return Response
     */
    public function store(CreateCatalogoContratoRequest $request)
    {
        $input = $request->except('fornecedor_cod');

        $catalogoContrato = new CatalogoContrato($input);

        $fornecedor_mega = MegaFornecedor::select(['AGN_ST_CGC'])
            ->where('agn_in_codigo', $request->fornecedor_cod)
            ->first();

        $cnpj = $fornecedor_mega->agn_st_cgc;

        $fornecedor_cadastrado = Fornecedor::where('cnpj', $cnpj)
            ->first();

        if($fornecedor_cadastrado){
            $catalogoContrato->fornecedor_id = $fornecedor_cadastrado->id;
        }else{
            $fornecedor = ImportacaoRepository::fornecedores($cnpj);
            $catalogoContrato->fornecedor_id = $fornecedor->id;
        }

        $catalogoContrato->catalogo_contrato_status_id = 1;
        $catalogoContrato->save();

        $catalogoContratoStatus = CatalogoContratoStatusLog::create([
            'catalogo_contrato_id' => $catalogoContrato->id,
            'catalogo_contrato_status_id' => 1,
            'user_id' => auth()->id()
        ]);

        $input = $request->only(['CAMPO_EXTRA_MINUTA','CAMPO_EXTRA_CONTRATO']);

        // Template
        $campos_extras_minuta = [];
        if(isset($input['CAMPO_EXTRA_MINUTA'])){
            foreach ($input['CAMPO_EXTRA_MINUTA'] as $campo => $valor){
                $campos_extras_minuta[$campo] = $valor;
            }

            $campos_extras_minuta = json_encode($campos_extras_minuta);
        }else{
            $campos_extras_minuta = null;
        }
        $input['campos_extras_minuta'] = $campos_extras_minuta;
        // Contrato
        $campos_extras_contrato = [];
        if(isset($input['CAMPO_EXTRA_CONTRATO'])){
            foreach ($input['CAMPO_EXTRA_CONTRATO'] as $campo => $valor){
                $campos_extras_contrato[$campo] = $valor;
            }
            $campos_extras_contrato = json_encode($campos_extras_contrato);
        }else{
            $campos_extras_contrato = null;
        }
        $input['campos_extras_contrato'] = $campos_extras_contrato;

        $catalogoContrato = $this->catalogoContratoRepository->update($input, $catalogoContrato->id);

        if($request->obra){
            foreach ($request->obra as $obra_id){
                $catalogoContratoObra = CatalogoContratoObra::create([
                    'catalogo_contrato_id' => $catalogoContrato->id,
                    'obra_id' => $obra_id,
                    'user_id' => auth()->id(),
                    'catalogo_contrato_status_id' => 2
                ]);
            }
        }

        if (count($request->contratoInsumos)) {
            foreach ($request->contratoInsumos as $item) {
                if ($item['insumo_id'] != '' && floatval($item['valor_unitario']) > 0 ) {
                    $contrato_insumo = new CatalogoContratoInsumo();
                    $contrato_insumo->catalogo_contrato_id = $catalogoContrato->id;
                    $contrato_insumo->insumo_id = $item['insumo_id'];
                    $contrato_insumo->valor_unitario = $item['valor_unitario'] ? money_to_float($item['valor_unitario']) : 0;
                    $contrato_insumo->pedido_minimo = $item['pedido_minimo'];
                    $contrato_insumo->pedido_multiplo_de = $item['pedido_multiplo_de'];
                    $contrato_insumo->periodo_inicio = $item['periodo_inicio'];
                    $contrato_insumo->periodo_termino = $item['periodo_termino'];
                    $contrato_insumo->user_id = auth()->id();
                    $contrato_insumo = $catalogoContrato->contratoInsumos()->save($contrato_insumo);

                    $logCatInsumo = new CatalogoContratoInsumoLog();
                    $logCatInsumo->contrato_insumo_id = $contrato_insumo->id;
                    $logCatInsumo->user_id = auth()->id();
                    $logCatInsumo->valor_unitario = $contrato_insumo->valor_unitario;
                    $logCatInsumo->pedido_minimo = $contrato_insumo->pedido_minimo;
                    $logCatInsumo->pedido_multiplo_de = $contrato_insumo->pedido_multiplo_de;
                    $logCatInsumo->periodo_inicio = $contrato_insumo->periodo_inicio;
                    $logCatInsumo->periodo_termino = $contrato_insumo->periodo_termino;
                    $logCatInsumo->save();
                }
            }
        }

        if($request->gerar_minuta){
            // Status do acordo
            $catalogoContrato->catalogo_contrato_status_id = 2;
            $catalogoContrato->save();
            $catalogoContratoStatus = CatalogoContratoStatusLog::create([
                'catalogo_contrato_id' => $catalogoContrato->id,
                'catalogo_contrato_status_id' => 2,
                'user_id' => auth()->id()
            ]);
            Flash::success('Catalogo Contrato '.trans('common.saved').' e minuta disponível.');

        }else{
            Flash::success('Catalogo Contrato '.trans('common.saved').' '.trans('common.successfully').'.');
        }



        return redirect(route('catalogo_contratos.index'));
    }

    /**
     * Display the specified CatalogoContrato.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $catalogoContrato = $this->catalogoContratoRepository->findWithoutFail($id);

        if (empty($catalogoContrato)) {
            Flash::error('Catalogo Contrato '.trans('common.not-found'));

            return redirect(route('catalogo_contratos.index'));
        }

        return view('catalogo_contratos.show')->with('catalogoContrato', $catalogoContrato);
    }

    /**
     * Show the form for editing the specified CatalogoContrato.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $catalogoContrato = $this->catalogoContratoRepository->findWithoutFail($id);

        if (empty($catalogoContrato)) {
            Flash::error('Catalogo Contrato '.trans('common.not-found'));

            return redirect(route('catalogo_contratos.index'));
        }

        return view('catalogo_contratos.edit', compact('catalogoContrato'));
    }

    /**
     * Update the specified CatalogoContrato in storage.
     *
     * @param  int              $id
     * @param UpdateCatalogoContratoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCatalogoContratoRequest $request)
    {
        $catalogoContrato = $this->catalogoContratoRepository->findWithoutFail($id);

        if (empty($catalogoContrato)) {
            Flash::error('Catalogo Contrato '.trans('common.not-found'));

            return redirect(route('catalogo_contratos.index'));
        }

        $input = $request->except('fornecedor_cod','contratoInsumos');

        // Template
        $campos_extras_minuta = [];
        if(isset($input['CAMPO_EXTRA_MINUTA'])){
            foreach ($input['CAMPO_EXTRA_MINUTA'] as $campo => $valor){
                $campos_extras_minuta[$campo] = $valor;
            }

            $campos_extras_minuta = json_encode($campos_extras_minuta);
        }else{
            $campos_extras_minuta = null;
        }
        $input['campos_extras_minuta'] = $campos_extras_minuta;
        // Contrato
        $campos_extras_contrato = [];
        if(isset($input['CAMPO_EXTRA_CONTRATO'])){
            foreach ($input['CAMPO_EXTRA_CONTRATO'] as $campo => $valor){
                $campos_extras_contrato[$campo] = $valor;
            }
            $campos_extras_contrato = json_encode($campos_extras_contrato);
        }else{
            $campos_extras_contrato = null;
        }
        $input['campos_extras_contrato'] = $campos_extras_contrato;

        $catalogoContrato = $this->catalogoContratoRepository->update($input, $id);


        if($request->obra){
            foreach ($request->obra as $obra_id){
                $catalogoContratoObra = CatalogoContratoObra::where('obra_id',$obra_id)->where('catalogo_contrato_id', $catalogoContrato->id)->first();
                if(!$catalogoContratoObra){
                    $catalogoContratoObra = CatalogoContratoObra::create([
                        'catalogo_contrato_id' => $catalogoContrato->id,
                        'obra_id' => $obra_id,
                        'user_id' => auth()->id(),
                        'catalogo_contrato_status_id' => 2
                    ]);
                }
            }
        }

        $alteraStatusParaValidacao = false;
        if (count($request->contratoInsumos)) {
            foreach ($request->contratoInsumos as $item) {
                if ($item['insumo_id'] != '' && floatval($item['valor_unitario']) > 0 ) {
                    if(!isset($item['id'])){
                        $contrato_insumo = new CatalogoContratoInsumo();
                        $contrato_insumo->catalogo_contrato_id = $catalogoContrato->id;
                        $contrato_insumo->insumo_id = $item['insumo_id'];
                        $contrato_insumo->valor_unitario = $item['valor_unitario'] ? money_to_float($item['valor_unitario']) : 0;
                        $contrato_insumo->pedido_minimo = $item['pedido_minimo'];
                        $contrato_insumo->pedido_multiplo_de = $item['pedido_multiplo_de'];
                        $contrato_insumo->periodo_inicio = $item['periodo_inicio'];
                        $contrato_insumo->periodo_termino = $item['periodo_termino'];
                        $contrato_insumo->user_id = auth()->id();
                        $contrato_insumo = $catalogoContrato->contratoInsumos()->save($contrato_insumo);

                        $logCatInsumo = new CatalogoContratoInsumoLog();
                        $logCatInsumo->contrato_insumo_id = $contrato_insumo->id;
                        $logCatInsumo->user_id = auth()->id();
                        $logCatInsumo->valor_unitario = $contrato_insumo->valor_unitario;
                        $logCatInsumo->pedido_minimo = $contrato_insumo->pedido_minimo;
                        $logCatInsumo->pedido_multiplo_de = $contrato_insumo->pedido_multiplo_de;
                        $logCatInsumo->periodo_inicio = $contrato_insumo->periodo_inicio;
                        $logCatInsumo->periodo_termino = $contrato_insumo->periodo_termino;
                        $logCatInsumo->save();

                        $alteraStatusParaValidacao = true;

                    }else{
                        if($catalogoContrato->catalogo_contrato_status_id != 3){
                            $contrato_insumo = CatalogoContratoInsumo::find($item['id']);
                            $logCatInsumo = new CatalogoContratoInsumoLog();
                            $logCatInsumo->contrato_insumo_id = $contrato_insumo->id;
                            $logCatInsumo->user_id = auth()->id();
                            $logCatInsumo->valor_unitario_anterior = $contrato_insumo->valor_unitario;
                            $logCatInsumo->pedido_minimo_anterior = $contrato_insumo->pedido_minimo;
                            $logCatInsumo->pedido_multiplo_de_anterior = $contrato_insumo->pedido_multiplo_de;
                            $logCatInsumo->periodo_inicio_anterior = $contrato_insumo->periodo_inicio;
                            $logCatInsumo->periodo_termino_anterior = $contrato_insumo->periodo_termino;
                            $logCatInsumo->save();

                            $contrato_insumo->insumo_id = $item['insumo_id'];
                            $contrato_insumo->valor_unitario = $item['valor_unitario'] ? money_to_float($item['valor_unitario']) : 0;
                            $contrato_insumo->pedido_minimo = $item['pedido_minimo'];
                            $contrato_insumo->pedido_multiplo_de = $item['pedido_multiplo_de'];
                            $contrato_insumo->periodo_inicio = $item['periodo_inicio'];
                            $contrato_insumo->periodo_termino = $item['periodo_termino'];
                            $contrato_insumo->user_id = auth()->id();

                            $contrato_insumo->update();

                            $logCatInsumo->valor_unitario = $contrato_insumo->valor_unitario;
                            $logCatInsumo->pedido_minimo = $contrato_insumo->pedido_minimo;
                            $logCatInsumo->pedido_multiplo_de = $contrato_insumo->pedido_multiplo_de;
                            $logCatInsumo->periodo_inicio = $contrato_insumo->periodo_inicio;
                            $logCatInsumo->periodo_termino = $contrato_insumo->periodo_termino;
                            $logCatInsumo->save();

                            $alteraStatusParaValidacao = true;

                        }


                    }

                }
            }
        }

        if(count($request->reajuste)) {
            foreach ($request->reajuste as $reajuste) {
                if ($reajuste['insumo_id'] != ''
                    && floatval($reajuste['valor_unitario']) > 0
                    && floatval($reajuste['pedido_minimo']) > 0
                    && floatval($reajuste['pedido_multiplo_de']) > 0
                    && strlen($reajuste['periodo_inicio']) > 0
                    && strlen($reajuste['periodo_termino']) > 0
                ) {
                    $contrato_insumo = new CatalogoContratoInsumo();
                    $contrato_insumo->catalogo_contrato_id = $catalogoContrato->id;
                    $contrato_insumo->insumo_id = $reajuste['insumo_id'];
                    $contrato_insumo->valor_unitario = $reajuste['valor_unitario'] ? money_to_float($reajuste['valor_unitario']) : 0;
                    $contrato_insumo->pedido_minimo = $reajuste['pedido_minimo'];
                    $contrato_insumo->pedido_multiplo_de = $reajuste['pedido_multiplo_de'];
                    $contrato_insumo->periodo_inicio = $reajuste['periodo_inicio'];
                    $contrato_insumo->periodo_termino = $reajuste['periodo_termino'];
                    $contrato_insumo->user_id = auth()->id();
                    $contrato_insumo->save();

                    $logCatInsumo = new CatalogoContratoInsumoLog();
                    $logCatInsumo->contrato_insumo_id = $contrato_insumo->id;
                    $logCatInsumo->user_id = auth()->id();
                    $logCatInsumo->valor_unitario_anterior = $contrato_insumo->valor_unitario;
                    $logCatInsumo->pedido_minimo_anterior = $contrato_insumo->pedido_minimo;
                    $logCatInsumo->pedido_multiplo_de_anterior = $contrato_insumo->pedido_multiplo_de;
                    $logCatInsumo->periodo_inicio_anterior = $contrato_insumo->periodo_inicio;
                    $logCatInsumo->periodo_termino_anterior = $contrato_insumo->periodo_termino;

                    $logCatInsumo->valor_unitario = $contrato_insumo->valor_unitario;
                    $logCatInsumo->pedido_minimo = $contrato_insumo->pedido_minimo;
                    $logCatInsumo->pedido_multiplo_de = $contrato_insumo->pedido_multiplo_de;
                    $logCatInsumo->periodo_inicio = $contrato_insumo->periodo_inicio;
                    $logCatInsumo->periodo_termino = $contrato_insumo->periodo_termino;
                    $logCatInsumo->save();

                    $alteraStatusParaValidacao = true;
                }
            }
        }

        if($request->gerar_minuta){
            // Status do acordo
            $catalogoContrato->catalogo_contrato_status_id = 2;
            $catalogoContrato->save();

            foreach ($catalogoContrato->obras()->where('catalogo_contrato_status_id',3)->get() as $catalogoContratoObra){
                $catalogoContratoObra->catalogo_contrato_status_id = 2;
                $catalogoContratoObra->save();
                $catalogoContratoObra = CatalogoContratoObraLog::create([
                    'catalogo_contrato_obra_id' => $catalogoContratoObra->id,
                    'catalogo_contrato_status_id' => $catalogoContratoObra->catalogo_contrato_status_id
                ]);
            }

            $catalogoContratoStatus = CatalogoContratoStatusLog::create([
                'catalogo_contrato_id' => $catalogoContrato->id,
                'catalogo_contrato_status_id' => 2,
                'user_id' => auth()->id()
            ]);
            Flash::success('Catalogo Contrato '.trans('common.updated').' e minuta disponível para baixar.');
        }else{

            if( $alteraStatusParaValidacao ){
                // Status do acordo
                $catalogoContrato->catalogo_contrato_status_id = 2;
                $catalogoContrato->save();

                foreach ($catalogoContrato->obras()->where('catalogo_contrato_status_id',3)->get() as $catalogoContratoObra){
                    $catalogoContratoObra->catalogo_contrato_status_id = 2;
                    $catalogoContratoObra->save();
                    $catalogoContratoObra = CatalogoContratoObraLog::create([
                        'catalogo_contrato_obra_id' => $catalogoContratoObra->id,
                        'catalogo_contrato_status_id' => $catalogoContratoObra->catalogo_contrato_status_id
                    ]);
                }
            }

            if ($request->minuta_assinada) {
                $destinationPath = CodeRepository::saveFile($request->minuta_assinada, 'acordos/assinado_' . $catalogoContrato->id);

                $catalogoContrato->minuta_assinada = $destinationPath;
                $catalogoContrato->save();
                $acao = 'Arquivo enviado!';



                    $catalogoContrato->catalogo_contrato_status_id = 3;
                    $catalogoContrato->save();
                    $catalogoContratoStatus = CatalogoContratoStatusLog::create([
                        'catalogo_contrato_id' => $catalogoContrato->id,
                        'catalogo_contrato_status_id' => 3,
                        'user_id' => auth()->id()
                    ]);
                    foreach ($catalogoContrato->obras()->whereIn('catalogo_contrato_status_id',[1,2])->get() as $catalogoContratoObra){
                        $catalogoContratoObra->catalogo_contrato_status_id = 3;
                        $catalogoContratoObra->save();
                        $catalogoContratoObra = CatalogoContratoObraLog::create([
                            'catalogo_contrato_obra_id' => $catalogoContratoObra->id,
                            'catalogo_contrato_status_id' => $catalogoContratoObra->catalogo_contrato_status_id
                        ]);
                    }
                    $acao = 'Arquivo enviado e Acordo ativado!';


                Flash::success($acao);
            }else{
                Flash::success('Catalogo Contrato '.trans('common.updated').' '.trans('common.successfully').'.');
            }



        }



        return redirect(route('catalogo_contratos.index'));
    }

    public function removeObra($id, $cc_obra){
        $remove = CatalogoContratoObra::find($cc_obra);
        if($remove){
            $remove->delete();
            return response()->json(['success'=>true ]);
        }
        return response()->json(['success'=>false],400);

    }

    /**
     * Remove the specified CatalogoContrato from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $catalogoContrato = $this->catalogoContratoRepository->findWithoutFail($id);

        if (empty($catalogoContrato)) {
            Flash::error('Catalogo Contrato '.trans('common.not-found'));

            return redirect(route('catalogo_contratos.index'));
        }

        $this->catalogoContratoRepository->delete($id);

        Flash::success('Catalogo Contrato '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('catalogo_contratos.index'));
    }

    public function deleteInsumo(Request $request)
    {
        try {
            $acao = false;
            $mensagem = "Ocorreu um erro ao deletar o insumo";

            $catalogo_contrato_insumo = CatalogoContratoInsumo::find($request->insumo);

            $insumos = CatalogoContratoInsumo::where('insumo_id', $catalogo_contrato_insumo->insumo_id)->get();

            if ($insumos) {
                foreach ($insumos as $insumo){
                    $acao = $insumo->delete();
                    $mensagem = "Insumo deletado com sucesso";
                }
            }

            return response()->json(['sucesso' => $acao, 'resposta' => $mensagem, 'insumo_id' => $catalogo_contrato_insumo->insumo_id]);
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

    public function buscaFornecedor(Request $request){
        $fornecedores = MegaFornecedor::select([
            'agn_in_codigo as id',
            'agn_st_cgc',
            DB::raw("(CONVERT(agn_st_nome,'UTF8','WE8ISO8859P15') || ' - ' || agn_st_cgc) as agn_st_nome")
        ])
        ->whereRaw("LOWER(agn_st_nome) LIKE '%' || LOWER('{$request->q}') || '%'")
        ->orderBy('agn_st_nome', 'ASC')
        ->paginate();

        return $fornecedores;
    }

    public function imprimirMinuta($id){
        return response()->file(storage_path('/app/public/') . str_replace('storage/', '', CatalogoContratoRepository::geraImpressao($id)));
    }

    public function ativarDesativar(Request $request)
    {
        $catalogo_contrato = CatalogoContrato::find($request->id);
        $novo_status = null;

        if($catalogo_contrato) {
            if($catalogo_contrato->catalogo_contrato_status_id == 3) {
                $novo_status = 4;
            }

            if($catalogo_contrato->catalogo_contrato_status_id == 4) {
                $novo_status = 3;
            }

            if($novo_status){
                $catalogo_contrato->catalogo_contrato_status_id = $novo_status;
                $catalogo_contrato->save();
            }
        }
        
        return response()->json(true);
    }
}
