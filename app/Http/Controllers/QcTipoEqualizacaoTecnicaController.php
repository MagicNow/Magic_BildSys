<?php

namespace App\Http\Controllers;

use App\DataTables\QcTipoEqualizacaoTecnicaDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateQcTipoEqualizacaoTecnicaRequest;
use App\Http\Requests\UpdateQcTipoEqualizacaoTecnicaRequest;
use App\Repositories\QcTipoEqualizacaoTecnicaRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class QcTipoEqualizacaoTecnicaController extends AppBaseController
{
    /** @var  QcTipoEqualizacaoTecnicaRepository */
    private $qcTipoEqualizacaoTecnicaRepository;

    public function __construct(QcTipoEqualizacaoTecnicaRepository $qcTipoEqualizacaoTecnicaRepo)
    {
        $this->qcTipoEqualizacaoTecnicaRepository = $qcTipoEqualizacaoTecnicaRepo;
    }

    /**
     * Display a listing of the QcTipoEqualizacaoTecnica.
     *
     * @param QcTipoEqualizacaoTecnicaDataTable $qcTipoEqualizacaoTecnicaDataTable
     * @return Response
     */
    public function index(QcTipoEqualizacaoTecnicaDataTable $qcTipoEqualizacaoTecnicaDataTable)
    {
        return $qcTipoEqualizacaoTecnicaDataTable->render('qc_tipo_equalizacao_tecnicas.index');
    }

    /**
     * Show the form for creating a new QcTipoEqualizacaoTecnica.
     *
     * @return Response
     */
    public function create()
    {
        return view('qc_tipo_equalizacao_tecnicas.create');
    }

    /**
     * Store a newly created QcTipoEqualizacaoTecnica in storage.
     *
     * @param CreateQcTipoEqualizacaoTecnicaRequest $request
     *
     * @return Response
     */
    public function store(CreateQcTipoEqualizacaoTecnicaRequest $request)
    {
        $input = $request->all();

        $qcTipoEqualizacaoTecnica = $this->qcTipoEqualizacaoTecnicaRepository->create($input);

        Flash::success('Qc Tipo Equalizacao Tecnica '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('qcTipoEqualizacaoTecnicas.index'));
    }

    /**
     * Display the specified QcTipoEqualizacaoTecnica.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $qcTipoEqualizacaoTecnica = $this->qcTipoEqualizacaoTecnicaRepository->findWithoutFail($id);

        if (empty($qcTipoEqualizacaoTecnica)) {
            Flash::error('Qc Tipo Equalizacao Tecnica '.trans('common.not-found'));

            return redirect(route('qcTipoEqualizacaoTecnicas.index'));
        }

        return view('qc_tipo_equalizacao_tecnicas.show')->with('qcTipoEqualizacaoTecnica', $qcTipoEqualizacaoTecnica);
    }

    /**
     * Show the form for editing the specified QcTipoEqualizacaoTecnica.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $qcTipoEqualizacaoTecnica = $this->qcTipoEqualizacaoTecnicaRepository->findWithoutFail($id);

        if (empty($qcTipoEqualizacaoTecnica)) {
            Flash::error('Qc Tipo Equalizacao Tecnica '.trans('common.not-found'));

            return redirect(route('qcTipoEqualizacaoTecnicas.index'));
        }

        return view('qc_tipo_equalizacao_tecnicas.edit')->with('qcTipoEqualizacaoTecnica', $qcTipoEqualizacaoTecnica);
    }

    /**
     * Update the specified QcTipoEqualizacaoTecnica in storage.
     *
     * @param  int              $id
     * @param UpdateQcTipoEqualizacaoTecnicaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQcTipoEqualizacaoTecnicaRequest $request)
    {
        $qcTipoEqualizacaoTecnica = $this->qcTipoEqualizacaoTecnicaRepository->findWithoutFail($id);

        if (empty($qcTipoEqualizacaoTecnica)) {
            Flash::error('Qc Tipo Equalizacao Tecnica '.trans('common.not-found'));

            return redirect(route('qcTipoEqualizacaoTecnicas.index'));
        }

        $qcTipoEqualizacaoTecnica = $this->qcTipoEqualizacaoTecnicaRepository->update($request->all(), $id);

        Flash::success('Qc Tipo Equalizacao Tecnica '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('qcTipoEqualizacaoTecnicas.index'));
    }

    /**
     * Remove the specified QcTipoEqualizacaoTecnica from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $qcTipoEqualizacaoTecnica = $this->qcTipoEqualizacaoTecnicaRepository->findWithoutFail($id);

        if (empty($qcTipoEqualizacaoTecnica)) {
            Flash::error('Qc Tipo Equalizacao Tecnica '.trans('common.not-found'));

            return redirect(route('qcTipoEqualizacaoTecnicas.index'));
        }

        $this->qcTipoEqualizacaoTecnicaRepository->delete($id);

        Flash::success('Qc Tipo Equalizacao Tecnica '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('qcTipoEqualizacaoTecnicas.index'));
    }
}
