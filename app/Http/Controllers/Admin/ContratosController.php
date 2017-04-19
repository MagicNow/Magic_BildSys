<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\ContratosDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateContratosRequest;
use App\Http\Requests\Admin\UpdateContratosRequest;
use App\Models\ContratoInsumo;
use App\Models\Insumo;
use App\Models\MegaFornecedor;
use App\Repositories\Admin\ContratosRepository;
use App\Repositories\CodeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;

class ContratosController extends AppBaseController
{
    /** @var  ContratosRepository */
    private $contratosRepository;

    public function __construct(ContratosRepository $contratosRepo)
    {
        $this->contratosRepository = $contratosRepo;
    }

    /**
     * Display a listing of the Contratos.
     *
     * @param ContratosDataTable $contratosDataTable
     * @return Response
     */
    public function index(ContratosDataTable $contratosDataTable)
    {
        return $contratosDataTable->render('admin.contratos.index');
    }

    /**
     * Show the form for creating a new Contratos.
     *
     * @return Response
     */
    public function create()
    {
        $insumos = Insumo::get();
        $fornecedores = [];

        return view('admin.contratos.create', compact('insumos', 'fornecedores'));
    }

    /**
     * Store a newly created Contratos in storage.
     *
     * @param CreateContratosRequest $request
     *
     * @return Response
     */
    public function store(CreateContratosRequest $request)
    {
        $input = $request->except('arquivo');

        $contratos = $this->contratosRepository->create($input);
        
        if($request->arquivo) {
            $destinationPath = CodeRepository::saveFile($request->arquivo, 'contratos/' . $contratos->id);

            $contratos->arquivo = $destinationPath;
            $contratos->save();
        }
        
        if (count($request->insumos)) {
            foreach ($request->insumos as $item) {
                if ($item['insumo_id'] != '' && $item['qtd'] != '' && $item['valor_unitario'] != '' && $item['valor_total'] != '') {
                    $insumo = new ContratoInsumo();
                    $insumo->contrato_id = $contratos->id;
                    $insumo->insumo_id = $item['insumo_id'];
                    $insumo->qtd = $item['qtd'];
                    $insumo->valor_unitario = $item['valor_unitario'];
                    $insumo->valor_total = $item['valor_total'];
                    $contratos->contratoInsumos()->save($insumo);
                }
            }
        }

        Flash::success('Contratos '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.contratos.index'));
    }

    /**
     * Display the specified Contratos.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $contratos = $this->contratosRepository->findWithoutFail($id);

        if (empty($contratos)) {
            Flash::error('Contratos '.trans('common.not-found'));

            return redirect(route('admin.contratos.index'));
        }

        return view('admin.contratos.show')->with('contratos', $contratos);
    }

    /**
     * Show the form for editing the specified Contratos.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $contratos = $this->contratosRepository->findWithoutFail($id);

        if (empty($contratos)) {
            Flash::error('Contratos '.trans('common.not-found'));

            return redirect(route('admin.contratos.index'));
        }

        $insumos = Insumo::get();

        $fornecedores = MegaFornecedor::select(DB::raw("CONVERT(agn_st_nome,'UTF8','WE8ISO8859P15' ) as agn_st_nome"), 'agn_in_codigo')
            ->where('agn_in_codigo', $contratos->fornecedor_cod)
            ->pluck('agn_st_nome', 'agn_in_codigo')->toArray();

        return view('admin.contratos.edit', compact('insumos', 'fornecedores'))->with('contratos', $contratos);
    }

    /**
     * Update the specified Contratos in storage.
     *
     * @param  int              $id
     * @param UpdateContratosRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContratosRequest $request)
    {
        $contratos = $this->contratosRepository->findWithoutFail($id);

        if (empty($contratos)) {
            Flash::error('Contratos '.trans('common.not-found'));

            return redirect(route('admin.contratos.index'));
        }

        if($request->arquivo){
            @unlink(public_path() . $contratos->arquivo);
            $destinationPath = CodeRepository::saveFile($request->arquivo, 'contratos/' . $contratos->id);
            $contratos->arquivo = $destinationPath;
            $contratos->save();
        }

        $contratos = $this->contratosRepository->update($request->except('arquivo'), $id);

        if (count($request->insumos)) {
            foreach ($request->insumos as $item) {
                if (!isset($item['id'])) {
                    if ($item['insumo_id'] != '' && $item['qtd'] != '' && $item['valor_unitario'] != '' && $item['valor_total'] != '') {
                        $insumo = new ContratoInsumo();
                        $insumo->contrato_id = $contratos->id;
                        $insumo->insumo_id = $item['insumo_id'];
                        $insumo->qtd = $item['qtd'];
                        $insumo->valor_unitario = $item['valor_unitario'];
                        $insumo->valor_total = $item['valor_total'];
                        $contratos->contratoInsumos()->save($insumo);
                    }
                } else {
                    $insumo = ContratoInsumo::find($item['id']);
                    if ($insumo) {
                        if ($item['insumo_id'] != '' && $item['qtd'] != '' && $item['valor_unitario'] != '' && $item['valor_total'] != '') {
                            $insumo->contrato_id = $contratos->id;
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

        Flash::success('Contratos '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.contratos.index'));
    }

    /**
     * Remove the specified Contratos from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $contratos = $this->contratosRepository->findWithoutFail($id);

        if (empty($contratos)) {
            Flash::error('Contratos '.trans('common.not-found'));

            return redirect(route('admin.contratos.index'));
        }

        if($contratos->arquivo){
            @unlink(public_path() . $contratos->arquivo);
        }
        
        $this->contratosRepository->delete($id);

        Flash::success('Contratos '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.contratos.index'));
    }
    
    public function calcularValorTotalInsumo(Request $request) {
        $pontos = array(",");
        $value = str_replace('.','',$request->valor_unitario);
        $valor_unitario = str_replace( $pontos, ".", $value);

        $valor_total = ($request->quantidade * $valor_unitario);

        $valor_total = number_format($valor_total,2,',','.');

        return response()->json(['valor_total' => $valor_total]);
    }

    public function deleteInsumo(Request $request)
    {
        try {
            $insumo = null;
            if ($request->insumo) {
                $insumo = ContratoInsumo::find($request->insumo);
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
