<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\CatalogoContratoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateCatalogoContratoRequest;
use App\Http\Requests\Admin\UpdateCatalogoContratoRequest;
use App\Models\CatalogoContratoInsumo;
use App\Models\CatalogoContrato;
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
        $insumos = CatalogoContrato::get();
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

        $catalogoContrato = new CatalogoContrato($input);

        $nome_fornecedor = MegaFornecedor::select(DB::raw("CONVERT(agn_st_nome,'UTF8','WE8ISO8859P15' ) as agn_st_nome"))
            ->where('agn_in_codigo', $request->fornecedor_cod)
            ->first();

        $catalogoContrato->fornecedor_nome = $nome_fornecedor->agn_st_nome;
        $catalogoContrato->save();

        if($request->arquivo) {
            $destinationPath = CodeRepository::saveFile($request->arquivo, 'contratos/' . $catalogoContrato->id);

            $catalogoContrato->arquivo = $destinationPath;
            $catalogoContrato->save();
        }

        if (count($request->insumos)) {
            foreach ($request->insumos as $item) {
                if ($item['insumo_id'] != '' && $item['qtd'] != '' && $item['valor_unitario'] != '' && $item['valor_total'] != '') {
                    $insumo = new CatalogoContratoInsumo();
                    $insumo->contrato_id = $catalogoContrato->id;
                    $insumo->insumo_id = $item['insumo_id'];
                    $insumo->qtd = $item['qtd'];
                    $insumo->valor_unitario = $item['valor_unitario'];
                    $insumo->valor_total = $item['valor_total'];
                    $catalogoContrato->contratoInsumos()->save($insumo);
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

        $insumos = CatalogoContrato::get();

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
                    if ($item['insumo_id'] != '' && $item['qtd'] != '' && $item['valor_unitario'] != '' && $item['valor_total'] != '') {
                        $insumo = new CatalogoContratoInsumo();
                        $insumo->contrato_id = $catalogoContrato->id;
                        $insumo->insumo_id = $item['insumo_id'];
                        $insumo->qtd = $item['qtd'];
                        $insumo->valor_unitario = $item['valor_unitario'];
                        $insumo->valor_total = $item['valor_total'];
                        $catalogoContrato->contratoInsumos()->save($insumo);
                    }
                } else {
                    $insumo = CatalogoContratoInsumo::find($item['id']);
                    if ($insumo) {
                        if ($item['insumo_id'] != '' && $item['qtd'] != '' && $item['valor_unitario'] != '' && $item['valor_total'] != '') {
                            $insumo->contrato_id = $catalogoContrato->id;
                            $insumo->insumo_id = $item['insumo_id'];
                            $insumo->qtd = $item['qtd'];
                            $insumo->valor_unitario = $item['valor_unitario'];
                            $insumo->valor_total = $item['valor_total'];
                            $insumo->update();
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

    public function calcularValorTotalInsumo(Request $request) {
        $pontos = array(",");

        $value = str_replace('.','',$request->valor_unitario);
        $valor_unitario = str_replace( $pontos, ".", $value);

        $value_qtd = str_replace('.','',$request->quantidade);
        $quantidade = str_replace( $pontos, ".", $value_qtd);

        $valor_total = ($quantidade * $valor_unitario);

        $valor_total = number_format($valor_total,2,',','.');

        return response()->json(['valor_total' => $valor_total]);
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
