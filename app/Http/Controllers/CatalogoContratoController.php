<?php

namespace App\Http\Controllers;

use App\DataTables\Admin\CatalogoContratoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateCatalogoContratoRequest;
use App\Http\Requests\Admin\UpdateCatalogoContratoRequest;
use App\Models\CatalogoContratoInsumo;
use App\Models\CatalogoContrato;
use App\Models\CatalogoContratoInsumoLog;
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

        $catalogoContrato->save();
        
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

        Flash::success('Catalogo Contrato '.trans('common.saved').' '.trans('common.successfully').'.');

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

        $fornecedores = MegaFornecedor::select(DB::raw("CONVERT(agn_st_nome,'UTF8','WE8ISO8859P15' ) as agn_st_nome"), 'agn_in_codigo')
            ->where('agn_st_cgc', $catalogoContrato->fornecedor->cnpj)
            ->pluck('agn_st_nome', 'agn_in_codigo')
            ->toArray();
        
        return view('catalogo_contratos.edit', compact('fornecedores'))->with('catalogoContrato', $catalogoContrato);
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

        $catalogoContrato = $this->catalogoContratoRepository->update($request->except('fornecedor_cod','contratoInsumos'), $id);

        $catalogoContrato->update();

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

                    }else{
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


                        $contrato_insumo->update();

                        $logCatInsumo->valor_unitario = $contrato_insumo->valor_unitario;
                        $logCatInsumo->pedido_minimo = $contrato_insumo->pedido_minimo;
                        $logCatInsumo->pedido_multiplo_de = $contrato_insumo->pedido_multiplo_de;
                        $logCatInsumo->periodo_inicio = $contrato_insumo->periodo_inicio;
                        $logCatInsumo->periodo_termino = $contrato_insumo->periodo_termino;

                        $logCatInsumo->save();
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
                }
            }
        }

        Flash::success('Catalogo Contrato '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('catalogo_contratos.index'));
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
            DB::raw("CONVERT(agn_st_nome,'UTF8','WE8ISO8859P15') as agn_st_nome")
        ])
        ->whereRaw("LOWER(agn_st_nome) LIKE '%' || LOWER('{$request->q}') || '%'")
        ->orderBy('agn_st_nome', 'ASC')
        ->paginate();

        return $fornecedores;
    }

    public function buscaInsumos(Request $request){

        $insumos = Insumo::select([
            'id',
            DB::raw("CONCAT(nome, ' - ', unidade_sigla) as nome")
        ])
        ->where(function ($query) use($request){
            $query->where('nome', 'like', '%' . $request->q . '%')
                ->orWhere('unidade_sigla','like', '%'.$request->q.'%');
        })
        ->where('active', 1)
        ->whereHas('grupo', function($query) {
            return $query->where('active', 1);
        })
        ->orderBy('nome', 'ASC');

        if($request->insumos_trocados) {
            $insumos = $insumos->whereNotIn('id', explode(',', $request->insumos_trocados));
        }

        $insumos = $insumos->paginate();

        return $insumos;
    }
}
