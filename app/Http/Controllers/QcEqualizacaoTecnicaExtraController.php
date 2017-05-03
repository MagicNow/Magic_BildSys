<?php

namespace App\Http\Controllers;

use App\DataTables\QcEqualizacaoTecnicaExtraDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateQcEqualizacaoTecnicaExtraRequest;
use App\Http\Requests\UpdateQcEqualizacaoTecnicaExtraRequest;
use App\Repositories\QcEqualizacaoTecnicaExtraRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class QcEqualizacaoTecnicaExtraController extends AppBaseController
{
    /** @var  QcEqualizacaoTecnicaExtraRepository */
    private $qcEqualizacaoTecnicaExtraRepository;

    public function __construct(QcEqualizacaoTecnicaExtraRepository $qcEqualizacaoTecnicaExtraRepo)
    {
        $this->qcEqualizacaoTecnicaExtraRepository = $qcEqualizacaoTecnicaExtraRepo;
    }

    /**
     * Display a listing of the QcEqualizacaoTecnicaExtra.
     *
     * @param QcEqualizacaoTecnicaExtraDataTable $qcEqualizacaoTecnicaExtraDataTable
     * @return Response
     */
    public function index(QcEqualizacaoTecnicaExtraDataTable $qcEqualizacaoTecnicaExtraDataTable)
    {
        return $qcEqualizacaoTecnicaExtraDataTable->render('qc_equalizacao_tecnica_extras.index');
    }

    /**
     * Show the form for creating a new QcEqualizacaoTecnicaExtra.
     *
     * @return Response
     */
    public function create()
    {
        return view('qc_equalizacao_tecnica_extras.create');
    }

    /**
     * Store a newly created QcEqualizacaoTecnicaExtra in storage.
     *
     * @param CreateQcEqualizacaoTecnicaExtraRequest $request
     *
     * @return Response
     */
    public function store(CreateQcEqualizacaoTecnicaExtraRequest $request)
    {
        $input = $request->all();

        $qcEqualizacaoTecnicaExtra = $this->qcEqualizacaoTecnicaExtraRepository->create($input);

        Flash::success('Qc Equalizacao Tecnica Extra '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('qcEqualizacaoTecnicaExtras.index'));
    }

    /**
     * Display the specified QcEqualizacaoTecnicaExtra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $qcEqualizacaoTecnicaExtra = $this->qcEqualizacaoTecnicaExtraRepository->findWithoutFail($id);

        if (empty($qcEqualizacaoTecnicaExtra)) {
            Flash::error('Qc Equalizacao Tecnica Extra '.trans('common.not-found'));

            return redirect(route('qcEqualizacaoTecnicaExtras.index'));
        }

        return view('qc_equalizacao_tecnica_extras.show')->with('qcEqualizacaoTecnicaExtra', $qcEqualizacaoTecnicaExtra);
    }

    /**
     * Show the form for editing the specified QcEqualizacaoTecnicaExtra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $qcEqualizacaoTecnicaExtra = $this->qcEqualizacaoTecnicaExtraRepository->findWithoutFail($id);

        if (empty($qcEqualizacaoTecnicaExtra)) {
            Flash::error('Qc Equalizacao Tecnica Extra '.trans('common.not-found'));

            return redirect(route('qcEqualizacaoTecnicaExtras.index'));
        }

        return view('qc_equalizacao_tecnica_extras.edit')->with('qcEqualizacaoTecnicaExtra', $qcEqualizacaoTecnicaExtra);
    }

    /**
     * Update the specified QcEqualizacaoTecnicaExtra in storage.
     *
     * @param  int              $id
     * @param UpdateQcEqualizacaoTecnicaExtraRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQcEqualizacaoTecnicaExtraRequest $request)
    {
        $qcEqualizacaoTecnicaExtra = $this->qcEqualizacaoTecnicaExtraRepository->findWithoutFail($id);

        if (empty($qcEqualizacaoTecnicaExtra)) {
            Flash::error('Qc Equalizacao Tecnica Extra '.trans('common.not-found'));

            return redirect(route('qcEqualizacaoTecnicaExtras.index'));
        }

        $qcEqualizacaoTecnicaExtra = $this->qcEqualizacaoTecnicaExtraRepository->update($request->all(), $id);

        Flash::success('Qc Equalizacao Tecnica Extra '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('qcEqualizacaoTecnicaExtras.index'));
    }

    /**
     * Remove the specified QcEqualizacaoTecnicaExtra from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $qcEqualizacaoTecnicaExtra = $this->qcEqualizacaoTecnicaExtraRepository->findWithoutFail($id);

        if (empty($qcEqualizacaoTecnicaExtra)) {
            Flash::error('Qc Equalizacao Tecnica Extra '.trans('common.not-found'));

            return redirect(route('qcEqualizacaoTecnicaExtras.index'));
        }

        $this->qcEqualizacaoTecnicaExtraRepository->delete($id);

        Flash::success('Qc Equalizacao Tecnica Extra '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('qcEqualizacaoTecnicaExtras.index'));
    }
}
