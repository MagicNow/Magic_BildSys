<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\InsumoGrupoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateInsumoGrupoRequest;
use App\Http\Requests\Admin\UpdateInsumoGrupoRequest;
use App\Repositories\Admin\InsumoGrupoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class InsumoGrupoController extends AppBaseController
{
    /** @var  InsumoGrupoRepository */
    private $insumoGrupoRepository;

    public function __construct(InsumoGrupoRepository $insumoGrupoRepo)
    {
        $this->insumoGrupoRepository = $insumoGrupoRepo;
    }

    /**
     * Display a listing of the InsumoGrupo.
     *
     * @param InsumoGrupoDataTable $insumoGrupoDataTable
     * @return Response
     */
    public function index(InsumoGrupoDataTable $insumoGrupoDataTable)
    {
        return $insumoGrupoDataTable->render('admin.insumo_grupos.index');
    }

    /**
     * Show the form for creating a new InsumoGrupo.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.insumo_grupos.create');
    }

    /**
     * Store a newly created InsumoGrupo in storage.
     *
     * @param CreateInsumoGrupoRequest $request
     *
     * @return Response
     */
    public function store(CreateInsumoGrupoRequest $request)
    {
        $input = $request->all();

        $insumoGrupo = $this->insumoGrupoRepository->create($input);

        Flash::success('Insumo Grupo '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.insumoGrupos.index'));
    }

    /**
     * Display the specified InsumoGrupo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $insumoGrupo = $this->insumoGrupoRepository->findWithoutFail($id);

        if (empty($insumoGrupo)) {
            Flash::error('Insumo Grupo '.trans('common.not-found'));

            return redirect(route('admin.insumoGrupos.index'));
        }

        return view('admin.insumo_grupos.show')->with('insumoGrupo', $insumoGrupo);
    }

    /**
     * Show the form for editing the specified InsumoGrupo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $insumoGrupo = $this->insumoGrupoRepository->findWithoutFail($id);

        if (empty($insumoGrupo)) {
            Flash::error('Insumo Grupo '.trans('common.not-found'));

            return redirect(route('admin.insumoGrupos.index'));
        }

        return view('admin.insumo_grupos.edit')->with('insumoGrupo', $insumoGrupo);
    }

    /**
     * Update the specified InsumoGrupo in storage.
     *
     * @param  int              $id
     * @param UpdateInsumoGrupoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInsumoGrupoRequest $request)
    {
        $insumoGrupo = $this->insumoGrupoRepository->findWithoutFail($id);

        if (empty($insumoGrupo)) {
            Flash::error('Insumo Grupo '.trans('common.not-found'));

            return redirect(route('admin.insumoGrupos.index'));
        }

        $insumoGrupo = $this->insumoGrupoRepository->update($request->all(), $id);

        Flash::success('Insumo Grupo '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.insumoGrupos.index'));
    }

    /**
     * Remove the specified InsumoGrupo from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $insumoGrupo = $this->insumoGrupoRepository->findWithoutFail($id);

        if (empty($insumoGrupo)) {
            Flash::error('Insumo Grupo '.trans('common.not-found'));

            return redirect(route('admin.insumoGrupos.index'));
        }

        $this->insumoGrupoRepository->delete($id);

        Flash::success('Insumo Grupo '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.insumoGrupos.index'));
    }
}
