<?php

namespace App\Http\Controllers;

use App\DataTables\QcEqualizacaoTecnicaAnexoExtraDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateQcEqualizacaoTecnicaAnexoExtraRequest;
use App\Http\Requests\UpdateQcEqualizacaoTecnicaAnexoExtraRequest;
use App\Repositories\QcEqualizacaoTecnicaAnexoExtraRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class QcEqualizacaoTecnicaAnexoExtraController extends AppBaseController
{
    /** @var  QcEqualizacaoTecnicaAnexoExtraRepository */
    private $qcEqualizacaoTecnicaAnexoExtraRepository;

    public function __construct(QcEqualizacaoTecnicaAnexoExtraRepository $qcEqualizacaoTecnicaAnexoExtraRepo)
    {
        $this->qcEqualizacaoTecnicaAnexoExtraRepository = $qcEqualizacaoTecnicaAnexoExtraRepo;
    }

    /**
     * Display a listing of the QcEqualizacaoTecnicaAnexoExtra.
     *
     * @param QcEqualizacaoTecnicaAnexoExtraDataTable $qcEqualizacaoTecnicaAnexoExtraDataTable
     * @return Response
     */
    public function index(QcEqualizacaoTecnicaAnexoExtraDataTable $qcEqualizacaoTecnicaAnexoExtraDataTable)
    {
        return $qcEqualizacaoTecnicaAnexoExtraDataTable->render('qc_equalizacao_tecnica_anexo_extras.index');
    }

    /**
     * Show the form for creating a new QcEqualizacaoTecnicaAnexoExtra.
     *
     * @return Response
     */
    public function create()
    {
        return view('qc_equalizacao_tecnica_anexo_extras.create');
    }

    /**
     * Store a newly created QcEqualizacaoTecnicaAnexoExtra in storage.
     *
     * @param CreateQcEqualizacaoTecnicaAnexoExtraRequest $request
     *
     * @return Response
     */
    public function store(CreateQcEqualizacaoTecnicaAnexoExtraRequest $request)
    {
        $input = $request->all();

        $qcEqualizacaoTecnicaAnexoExtra = $this->qcEqualizacaoTecnicaAnexoExtraRepository->create($input);

        Flash::success('Qc Equalizacao Tecnica Anexo Extra '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('qcEqualizacaoTecnicaAnexoExtras.index'));
    }

    /**
     * Display the specified QcEqualizacaoTecnicaAnexoExtra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $qcEqualizacaoTecnicaAnexoExtra = $this->qcEqualizacaoTecnicaAnexoExtraRepository->findWithoutFail($id);

        if (empty($qcEqualizacaoTecnicaAnexoExtra)) {
            Flash::error('Qc Equalizacao Tecnica Anexo Extra '.trans('common.not-found'));

            return redirect(route('qcEqualizacaoTecnicaAnexoExtras.index'));
        }

        return view('qc_equalizacao_tecnica_anexo_extras.show')->with('qcEqualizacaoTecnicaAnexoExtra', $qcEqualizacaoTecnicaAnexoExtra);
    }

    /**
     * Show the form for editing the specified QcEqualizacaoTecnicaAnexoExtra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $qcEqualizacaoTecnicaAnexoExtra = $this->qcEqualizacaoTecnicaAnexoExtraRepository->findWithoutFail($id);

        if (empty($qcEqualizacaoTecnicaAnexoExtra)) {
            Flash::error('Qc Equalizacao Tecnica Anexo Extra '.trans('common.not-found'));

            return redirect(route('qcEqualizacaoTecnicaAnexoExtras.index'));
        }

        return view('qc_equalizacao_tecnica_anexo_extras.edit')->with('qcEqualizacaoTecnicaAnexoExtra', $qcEqualizacaoTecnicaAnexoExtra);
    }

    /**
     * Update the specified QcEqualizacaoTecnicaAnexoExtra in storage.
     *
     * @param  int              $id
     * @param UpdateQcEqualizacaoTecnicaAnexoExtraRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQcEqualizacaoTecnicaAnexoExtraRequest $request)
    {
        $qcEqualizacaoTecnicaAnexoExtra = $this->qcEqualizacaoTecnicaAnexoExtraRepository->findWithoutFail($id);

        if (empty($qcEqualizacaoTecnicaAnexoExtra)) {
            Flash::error('Qc Equalizacao Tecnica Anexo Extra '.trans('common.not-found'));

            return redirect(route('qcEqualizacaoTecnicaAnexoExtras.index'));
        }

        $qcEqualizacaoTecnicaAnexoExtra = $this->qcEqualizacaoTecnicaAnexoExtraRepository->update($request->all(), $id);

        Flash::success('Qc Equalizacao Tecnica Anexo Extra '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('qcEqualizacaoTecnicaAnexoExtras.index'));
    }

    /**
     * Remove the specified QcEqualizacaoTecnicaAnexoExtra from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $qcEqualizacaoTecnicaAnexoExtra = $this->qcEqualizacaoTecnicaAnexoExtraRepository->findWithoutFail($id);

        if (empty($qcEqualizacaoTecnicaAnexoExtra)) {
            Flash::error('Qc Equalizacao Tecnica Anexo Extra '.trans('common.not-found'));

            return redirect(route('qcEqualizacaoTecnicaAnexoExtras.index'));
        }

        $this->qcEqualizacaoTecnicaAnexoExtraRepository->delete($id);

        Flash::success('Qc Equalizacao Tecnica Anexo Extra '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('qcEqualizacaoTecnicaAnexoExtras.index'));
    }
}
