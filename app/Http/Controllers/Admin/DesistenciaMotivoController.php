<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\DesistenciaMotivoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateDesistenciaMotivoRequest;
use App\Http\Requests\Admin\UpdateDesistenciaMotivoRequest;
use App\Repositories\Admin\DesistenciaMotivoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class DesistenciaMotivoController extends AppBaseController
{
    /** @var  DesistenciaMotivoRepository */
    private $desistenciaMotivoRepository;

    public function __construct(DesistenciaMotivoRepository $desistenciaMotivoRepo)
    {
        $this->desistenciaMotivoRepository = $desistenciaMotivoRepo;
    }

    /**
     * Display a listing of the DesistenciaMotivo.
     *
     * @param DesistenciaMotivoDataTable $desistenciaMotivoDataTable
     * @return Response
     */
    public function index(DesistenciaMotivoDataTable $desistenciaMotivoDataTable)
    {
        return $desistenciaMotivoDataTable->render('admin.desistencia_motivos.index');
    }

    /**
     * Show the form for creating a new DesistenciaMotivo.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.desistencia_motivos.create');
    }

    /**
     * Store a newly created DesistenciaMotivo in storage.
     *
     * @param CreateDesistenciaMotivoRequest $request
     *
     * @return Response
     */
    public function store(CreateDesistenciaMotivoRequest $request)
    {
        $input = $request->all();

        $desistenciaMotivo = $this->desistenciaMotivoRepository->create($input);

        Flash::success('Motivo para declinar proposta '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.desistenciaMotivos.index'));
    }

    /**
     * Display the specified DesistenciaMotivo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $desistenciaMotivo = $this->desistenciaMotivoRepository->findWithoutFail($id);

        if (empty($desistenciaMotivo)) {
            Flash::error('Motivo para declinar proposta '.trans('common.not-found'));

            return redirect(route('admin.desistenciaMotivos.index'));
        }

        return view('admin.desistencia_motivos.show')->with('desistenciaMotivo', $desistenciaMotivo);
    }

    /**
     * Show the form for editing the specified DesistenciaMotivo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $desistenciaMotivo = $this->desistenciaMotivoRepository->findWithoutFail($id);

        if (empty($desistenciaMotivo)) {
            Flash::error('Motivo para declinar proposta '.trans('common.not-found'));

            return redirect(route('admin.desistenciaMotivos.index'));
        }

        return view('admin.desistencia_motivos.edit')->with('desistenciaMotivo', $desistenciaMotivo);
    }

    /**
     * Update the specified DesistenciaMotivo in storage.
     *
     * @param  int              $id
     * @param UpdateDesistenciaMotivoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDesistenciaMotivoRequest $request)
    {
        $desistenciaMotivo = $this->desistenciaMotivoRepository->findWithoutFail($id);

        if (empty($desistenciaMotivo)) {
            Flash::error('Motivo para declinar proposta '.trans('common.not-found'));

            return redirect(route('admin.desistenciaMotivos.index'));
        }

        $desistenciaMotivo = $this->desistenciaMotivoRepository->update($request->all(), $id);

        Flash::success('Motivo para declinar proposta '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.desistenciaMotivos.index'));
    }

    /**
     * Remove the specified DesistenciaMotivo from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $desistenciaMotivo = $this->desistenciaMotivoRepository->findWithoutFail($id);

        if (empty($desistenciaMotivo)) {
            Flash::error('Motivo para declinar proposta '.trans('common.not-found'));

            return redirect(route('admin.desistenciaMotivos.index'));
        }

        $this->desistenciaMotivoRepository->delete($id);

        Flash::success('Motivo para declinar proposta '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.desistenciaMotivos.index'));
    }
}
