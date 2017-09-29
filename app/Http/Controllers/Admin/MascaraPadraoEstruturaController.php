<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\MascaraPadraoEstruturaDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateMascaraPadraoEstruturaRequest;
use App\Http\Requests\Admin\UpdateMascaraPadraoEstruturaRequest;
use App\Models\Grupo;
use App\Models\Insumo;
use App\Models\MascaraPadrao;
use App\Models\Servico;
use App\Repositories\Admin\MascaraPadraoEstruturaRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use Response;

class MascaraPadraoEstruturaController extends AppBaseController
{
    /** @var  MascaraPadraoEstruturaRepository */
    private $mascaraPadraoEstruturaRepository;

    public function __construct(MascaraPadraoEstruturaRepository $mascaraPadraoEstruturaRepo)
    {
        $this->mascaraPadraoEstruturaRepository = $mascaraPadraoEstruturaRepo;
    }

    /**
     * Display a listing of the MascaraPadraoEstrutura.
     *
     * @param MascaraPadraoEstruturaDataTable $mascaraPadraoEstruturaDataTable
     * @return Response
     */
    public function index(MascaraPadraoEstruturaDataTable $mascaraPadraoEstruturaDataTable)
    {
        return $mascaraPadraoEstruturaDataTable->render('admin.mascara_padrao_estruturas.index');
    }

    /**
     * Show the form for creating a new MascaraPadraoEstrutura.
     *
     * @return Response
     */
    public function create()
    {
        $mascaras = MascaraPadrao::pluck('nome','id')->toArray();
        $grupo = Grupo::where('codigo', '01')
            ->where('nome', 'OBRA')
            ->whereNull('grupo_id')
            ->first();
        return view('admin.mascara_padrao_estruturas.create', compact('mascaras','grupo'));
    }

    /**
     * Store a newly created MascaraPadraoEstrutura in storage.
     *
     * @param CreateMascaraPadraoEstruturaRequest $request
     *
     * @return Response
     */
    public function store(CreateMascaraPadraoEstruturaRequest $request)
    {
        $input = $request->all();
//        dd($input);
        $mascaraPadraoEstrutura = $this->mascaraPadraoEstruturaRepository->create($input);

        Flash::success('Mascara Padrao Estrutura '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.mascaraPadraoEstruturas.index'));
    }

    /**
     * Display the specified MascaraPadraoEstrutura.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $mascaraPadraoEstrutura = $this->mascaraPadraoEstruturaRepository->findWithoutFail($id);

        if (empty($mascaraPadraoEstrutura)) {
            Flash::error('Mascara Padrao Estrutura '.trans('common.not-found'));

            return redirect(route('admin.mascaraPadraoEstruturas.index'));
        }

        return view('admin.mascara_padrao_estruturas.show')->with('mascaraPadraoEstrutura', $mascaraPadraoEstrutura);
    }

    /**
     * Show the form for editing the specified MascaraPadraoEstrutura.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $mascaraPadraoEstrutura = $this->mascaraPadraoEstruturaRepository->findWithoutFail($id);

        if (empty($mascaraPadraoEstrutura)) {
            Flash::error('Mascara Padrao Estrutura '.trans('common.not-found'));

            return redirect(route('admin.mascaraPadraoEstruturas.index'));
        }

        return view('admin.mascara_padrao_estruturas.edit')->with('mascaraPadraoEstrutura', $mascaraPadraoEstrutura);
    }

    /**
     * Update the specified MascaraPadraoEstrutura in storage.
     *
     * @param  int              $id
     * @param UpdateMascaraPadraoEstruturaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMascaraPadraoEstruturaRequest $request)
    {
        $mascaraPadraoEstrutura = $this->mascaraPadraoEstruturaRepository->findWithoutFail($id);

        if (empty($mascaraPadraoEstrutura)) {
            Flash::error('Mascara Padrao Estrutura '.trans('common.not-found'));

            return redirect(route('admin.mascaraPadraoEstruturas.index'));
        }

        $mascaraPadraoEstrutura = $this->mascaraPadraoEstruturaRepository->update($request->all(), $id);

        Flash::success('Mascara Padrao Estrutura '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.mascaraPadraoEstruturas.index'));
    }

    /**
     * Remove the specified MascaraPadraoEstrutura from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $mascaraPadraoEstrutura = $this->mascaraPadraoEstruturaRepository->findWithoutFail($id);

        if (empty($mascaraPadraoEstrutura)) {
            Flash::error('Mascara Padrao Estrutura '.trans('common.not-found'));

            return redirect(route('admin.mascaraPadraoEstruturas.index'));
        }

        $this->mascaraPadraoEstruturaRepository->delete($id);

        Flash::success('Mascara Padrao Estrutura '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.mascaraPadraoEstruturas.index'));
    }

    public function getGrupos($id)
    {
        $grupos = Grupo::select([
            'grupos.id',
            DB::raw("CONCAT(grupos.codigo, ' ', grupos.nome) as nome")
        ])
            ->where('grupos.grupo_id', $id)
            ->orderBy('grupos.nome', 'ASC');


        $grupos = $grupos->pluck('grupos.nome','grupos.id')
            ->toArray();

        return $grupos;
    }

    public function getServicos($id)
    {
        $servicos = Servico::select([
            'servicos.id',
            DB::raw("CONCAT(servicos.codigo, ' ', servicos.nome) as nome")
        ])
            ->where('servicos.grupo_id', $id)
            ->orderBy('servicos.nome', 'ASC');

        $servicos = $servicos->pluck('nome', 'id')->toArray();

        return $servicos;
    }

    public function getInsumos()
    {
        $insumos = Insumo::select([
            'insumos.id',
            'insumos.nome'
        ])
            ->orderBy('insumos.nome', 'ASC');

        $insumos = $insumos->pluck('nome', 'id')->toArray();

        return $insumos;
    }
}
