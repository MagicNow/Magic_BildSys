<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\CatalogoContratoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateCatalogoContratoRequest;
use App\Http\Requests\Admin\UpdateCatalogoContratoRequest;
use App\Models\CatalogoContratoInsumo;
use App\Models\CatalogoContrato;
use App\Models\Insumo;
use App\Models\MegaFornecedor;
use App\Repositories\Admin\CatalogoContratoRepository;
use App\Repositories\CodeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
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
        return $catalogoContratoDataTable->render('admin.catalogo_contratos.index');
    }

    /**
     * Show the form for creating a new CatalogoContrato.
     *
     * @return Response
     */
    public function create()
    {
        $insumos = Insumo::get();
        $fornecedores = [];
        
        return view('admin.catalogo_contratos.create', compact('insumos', 'fornecedores'));
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
        $input = $request->except('arquivo');

        if($input['periodo_termino'] < $input['periodo_inicio']){
            Flash::error('O período de término não pode ser menor que o período de início.');
            return redirect('/admin/contratos/create')->withInput($input);
        }

        $pontos = array(",");
        $valor_maximo = str_replace('.','',$input['valor_maximo']);
        $valor_maximo = str_replace( $pontos, ".", $valor_maximo);

        $valor_minimo = str_replace('.','',$input['valor_minimo']);
        $valor_minimo = str_replace( $pontos, ".", $valor_minimo);

        if($valor_maximo < $valor_minimo){
            Flash::error('O valor máximo não pode ser menor que o valor mínimo.');
            return redirect('/admin/contratos/create')->withInput($input);
        }

        if($input['qtd_maxima'] < $input['qtd_minima']){
            Flash::error('O quantidade máxima não pode ser menor que a quantidade mínima.');
            return redirect('/admin/contratos/create')->withInput($input);
        }

        $catalogoContrato = new CatalogoContrato($input);

        $nome_fornecedor = MegaFornecedor::select(DB::raw("CONVERT(agn_st_nome,'UTF8','WE8ISO8859P15' ) as agn_st_nome"))
            ->where('agn_in_codigo', $request->fornecedor_cod)
            ->first();

        $catalogoContrato->fornecedor_nome = $nome_fornecedor->agn_st_nome;
        $catalogoContrato->fornecedor_id = 1;
        $catalogoContrato->save();

        if($request->arquivo) {
            $destinationPath = CodeRepository::saveFile($request->arquivo, 'contratos/' . $catalogoContrato->id);

            $catalogoContrato->arquivo = $destinationPath;
            $catalogoContrato->save();
        }

        if (count($request->insumos)) {
            foreach ($request->insumos as $item) {
                if ($item['insumo_id'] != '') {
                    $contrato_insumo = new CatalogoContratoInsumo();
                    $contrato_insumo->catalogo_contrato_id = $catalogoContrato->id;
                    $contrato_insumo->insumo_id = $item['insumo_id'];
                    $contrato_insumo->valor_unitario = $item['valor_unitario'];
                    $contrato_insumo->valor_maximo = $item['valor_maximo'];
                    $contrato_insumo->pedido_minimo = $item['pedido_minimo'];
                    $contrato_insumo->pedido_multiplo_de = $item['pedido_multiplo_de'];
                    $catalogoContrato->contratoInsumos()->save($contrato_insumo);
                }
            }
        }
        
        Flash::success('Catalogo Contrato '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.catalogo_contratos.index'));
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

            return redirect(route('admin.catalogo_contratos.index'));
        }

        return view('admin.catalogo_contratos.show')->with('catalogoContrato', $catalogoContrato);
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

            return redirect(route('admin.catalogo_contratos.index'));
        }

        $insumos = Insumo::get();

        $fornecedores = MegaFornecedor::select(DB::raw("CONVERT(agn_st_nome,'UTF8','WE8ISO8859P15' ) as agn_st_nome"), 'agn_in_codigo')
            ->where('agn_in_codigo', $catalogoContrato->fornecedor_cod)
            ->pluck('agn_st_nome', 'agn_in_codigo')->toArray();

        return view('admin.catalogo_contratos.edit', compact('insumos', 'fornecedores'))->with('catalogoContrato', $catalogoContrato);
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

            return redirect(route('admin.catalogo_contratos.index'));
        }

        $input = $request->all();

        if($input['periodo_termino'] < $input['periodo_inicio']){
            Flash::error('O período de término não pode ser menor que o período de início.');
            return redirect('/admin/contratos/'.$id.'/edit')->withInput($input);
        }

        $pontos = array(",");
        $valor_maximo = str_replace('.','',$input['valor_maximo']);
        $valor_maximo = str_replace( $pontos, ".", $valor_maximo);

        $valor_minimo = str_replace('.','',$input['valor_minimo']);
        $valor_minimo = str_replace( $pontos, ".", $valor_minimo);

        if($valor_maximo < $valor_minimo){
            Flash::error('O valor máximo não pode ser menor que o valor mínimo.');
            return redirect('/admin/contratos/'.$id.'/edit')->withInput($input);
        }

        if($input['qtd_maxima'] < $input['qtd_minima']){
            Flash::error('O quantidade máxima não pode ser menor que a quantidade mínima.');
            return redirect('/admin/contratos/'.$id.'/edit')->withInput($input);
        }

        if($request->arquivo){
            @unlink(public_path() . $catalogoContrato->arquivo);
            $destinationPath = CodeRepository::saveFile($request->arquivo, 'contratos/' . $catalogoContrato->id);
            $catalogoContrato->arquivo = $destinationPath;
            $catalogoContrato->save();
        }

        $catalogoContrato = $this->catalogoContratoRepository->update($request->except('arquivo'), $id);

        $nome_fornecedor = MegaFornecedor::select(DB::raw("CONVERT(agn_st_nome,'UTF8','WE8ISO8859P15' ) as agn_st_nome"))
            ->where('agn_in_codigo', $catalogoContrato->fornecedor_cod)
            ->first();

        $catalogoContrato->fornecedor_nome = $nome_fornecedor->agn_st_nome;
        $catalogoContrato->update();

        if (count($request->insumos)) {
            foreach ($request->insumos as $item) {
                if (!isset($item['id'])) {
                    if ($item['insumo_id'] != '') {
                        $contrato_insumo = new CatalogoContratoInsumo();
                        $contrato_insumo->catalogo_contrato_id = $catalogoContrato->id;
                        $contrato_insumo->insumo_id = $item['insumo_id'];
                        $contrato_insumo->valor_unitario = $item['valor_unitario'];
                        $contrato_insumo->valor_maximo = $item['valor_maximo'];
                        $contrato_insumo->pedido_minimo = $item['pedido_minimo'];
                        $contrato_insumo->pedido_multiplo_de = $item['pedido_multiplo_de'];
                        $catalogoContrato->contratoInsumos()->save($contrato_insumo);
                    }
                } else {
                    $contrato_insumo = CatalogoContratoInsumo::find($item['id']);
                    if ($contrato_insumo) {
                        if ($item['insumo_id'] != '') {
                            $contrato_insumo->catalogo_contrato_id = $catalogoContrato->id;
                            $contrato_insumo->insumo_id = $item['insumo_id'];
                            $contrato_insumo->valor_unitario = $item['valor_unitario'];
                            $contrato_insumo->valor_maximo = $item['valor_maximo'];
                            $contrato_insumo->pedido_minimo = $item['pedido_minimo'];
                            $contrato_insumo->pedido_multiplo_de = $item['pedido_multiplo_de'];
                            $contrato_insumo->update();
                        }
                    }
                }
            }
        }

        Flash::success('Catalogo Contrato '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.catalogo_contratos.index'));
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

            return redirect(route('admin.catalogo_contratos.index'));
        }

        if($catalogoContrato->arquivo){
            @unlink(public_path() . $catalogoContrato->arquivo);
        }

        $this->catalogoContratoRepository->delete($id);

        Flash::success('Catalogo Contrato '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.catalogo_contratos.index'));
    }

    public function deleteInsumo(Request $request)
    {
        try {
            $insumo = null;
            if ($request->insumo) {
                $insumo = CatalogoContratoInsumo::find($request->insumo);
            }
            if ($insumo) {
                $acao = $insumo->delete();
                $mensagem = "Insumo deletado com sucesso";
            } else {
                $acao = false;
                $mensagem = "Ocorreu um erro ao deletar o insumo";
            }
            return response()->json(['sucesso' => $acao, 'resposta' => $mensagem]);
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

    public function buscaFornecedor(Request $request){
        $fornecedores = MegaFornecedor::select([
            'agn_in_codigo as id',
            DB::raw("CONVERT(agn_st_nome,'UTF8','WE8ISO8859P15' ) as agn_st_nome")
        ])
            ->where('agn_st_nome','like', '%'.$request->q.'%')->paginate();

        return $fornecedores;
    }
}
