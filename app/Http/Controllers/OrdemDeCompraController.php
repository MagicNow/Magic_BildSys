<?php

namespace App\Http\Controllers;

use App\DataTables\OrdemDeCompraDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateOrdemDeCompraRequest;
use App\Http\Requests\UpdateOrdemDeCompraRequest;
use App\Models\Grupo;
use App\Models\Insumo;
use App\Models\Obra;
use App\Repositories\OrdemDeCompraRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\DB;

class OrdemDeCompraController extends AppBaseController
{
    /** @var  OrdemDeCompraRepository */
    private $ordemDeCompraRepository;

    public function __construct(OrdemDeCompraRepository $ordemDeCompraRepo)
    {
        $this->ordemDeCompraRepository = $ordemDeCompraRepo;
    }

    /**
     * Display a listing of the OrdemDeCompra.
     *
     * @param OrdemDeCompraDataTable $ordemDeCompraDataTable
     * @return Response
     */
    public function index(OrdemDeCompraDataTable $ordemDeCompraDataTable)
    {
        return $ordemDeCompraDataTable->render('ordem_de_compras.index');
    }

    /**
     * Show the form for creating a new OrdemDeCompra.
     *
     * @return Response
     */
    public function create()
    {
        return view('ordem_de_compras.create');
    }

    /**
     * Store a newly created OrdemDeCompra in storage.
     *
     * @param CreateOrdemDeCompraRequest $request
     *
     * @return Response
     */
    public function store(CreateOrdemDeCompraRequest $request)
    {
        $input = $request->all();

        $ordemDeCompra = $this->ordemDeCompraRepository->create($input);

        Flash::success('Ordem De Compra ' . trans('common.saved') . ' ' . trans('common.successfully') . '.');

        return redirect(route('ordemDeCompras.index'));
    }

    /**
     * Display the specified OrdemDeCompra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $ordemDeCompra = $this->ordemDeCompraRepository->findWithoutFail($id);

        if (empty($ordemDeCompra)) {
            Flash::error('Ordem De Compra ' . trans('common.not-found'));

            return redirect(route('ordemDeCompras.index'));
        }

        return view('ordem_de_compras.show')->with('ordemDeCompra', $ordemDeCompra);
    }

    /**
     * Show the form for editing the specified OrdemDeCompra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $ordemDeCompra = $this->ordemDeCompraRepository->findWithoutFail($id);

        if (empty($ordemDeCompra)) {
            Flash::error('Ordem De Compra ' . trans('common.not-found'));

            return redirect(route('ordemDeCompras.index'));
        }

        return view('ordem_de_compras.edit')->with('ordemDeCompra', $ordemDeCompra);
    }

    /**
     * Update the specified OrdemDeCompra in storage.
     *
     * @param  int $id
     * @param UpdateOrdemDeCompraRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOrdemDeCompraRequest $request)
    {
        $ordemDeCompra = $this->ordemDeCompraRepository->findWithoutFail($id);

        if (empty($ordemDeCompra)) {
            Flash::error('Ordem De Compra ' . trans('common.not-found'));

            return redirect(route('ordemDeCompras.index'));
        }

        $ordemDeCompra = $this->ordemDeCompraRepository->update($request->all(), $id);

        Flash::success('Ordem De Compra ' . trans('common.updated') . ' ' . trans('common.successfully') . '.');

        return redirect(route('ordemDeCompras.index'));
    }

    /**
     * Remove the specified OrdemDeCompra from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ordemDeCompra = $this->ordemDeCompraRepository->findWithoutFail($id);

        if (empty($ordemDeCompra)) {
            Flash::error('Ordem De Compra ' . trans('common.not-found'));

            return redirect(route('ordemDeCompras.index'));
        }

        $this->ordemDeCompraRepository->delete($id);

        Flash::success('Ordem De Compra ' . trans('common.deleted') . ' ' . trans('common.successfully') . '.');

        return redirect(route('ordemDeCompras.index'));
    }

    public function compras()
    {
        $obras = Obra::pluck('nome', 'id')->toArray();
        return view('ordem_de_compras.compras', compact('obras'));
    }

    /**
     * Tela que traz os insumos de uma tarefa especifica de uma obra.
     *
     * @param  Request $request ->planejamento_id
     *
     * @filters Request $request->grupo_id
     *
     * @return Response
     */
    public function obrasInsumos(Request $request)
    {
        $planejamento_compras = DB::table('planejamento_compras')
            ->select('grupo_id','servico_id','codigo_insumo')
            ->where('planejamento_compras.planejamento_id',$request->planejamento_id)
            ->get();



        $servicos = array();
        $grupos = array();
        $codigo = array();

        foreach ($planejamento_compras as $planejamento)
        {
            $flag_cod = false;
            if(isset($planejamento->codigo_insumo) && !empty($planejamento->codigo_insumo)){
                $flag_cod = true;
                $codigo[] = $planejamento->codigo_insumo;
            }

            $flag_servico = false;
            if(isset($planejamento->servico_id) && !empty($planejamento->servico_id) && !$flag_cod){
                $flag_servico = true;
                $servicos[] = $planejamento->servico_id;
            }

            if(isset($planejamento->grupo_id)&& !empty($planejamento->grupo_id) && !$flag_cod && !$flag_servico){
                $grupos[] = $planejamento->grupo_id;
            }
        }

        $insumos_cod = Insumo::join('planejamento_compras','planejamento_compras.codigo_insumo','=','insumos.codigo')
            ->select([
                'insumos.id',
                'insumos.nome',
                'insumos.unidade_sigla',
                'insumos.codigo',
                'planejamento_compras.grupo_id',
                'planejamento_compras.servico_id'
            ])
            ->whereIn('insumos.codigo',$codigo);


        $insumos_servicos = Insumo::join('orcamentos','insumos.id','=','orcamentos.insumo_id')
            ->whereIn('orcamentos.servico_id',$servicos)
            ->select([
                'insumos.id',
                'insumos.nome',
                'insumos.unidade_sigla',
                'insumos.codigo',
                'orcamentos.grupo_id',
                'orcamentos.servico_id'
            ]);


        $insumos = Insumo::join('orcamentos','insumos.id','=','orcamentos.insumo_id')
                ->whereIn('orcamentos.grupo_id',$grupos,'or')
                ->whereIn('orcamentos.subgrupo1_id',$grupos,'or')
                ->whereIn('orcamentos.subgrupo2_id',$grupos,'or')
                ->whereIn('orcamentos.subgrupo3_id',$grupos,'or')
            ->select([
                'insumos.id',
                'insumos.nome',
                'insumos.unidade_sigla',
                'insumos.codigo',
                'orcamentos.grupo_id',
                'orcamentos.servico_id'
            ])
            ->union($insumos_cod)
            ->union($insumos_servicos)
            ->get();

        $grupos = Grupo::pluck('nome', 'id')->toArray();
    }
}
