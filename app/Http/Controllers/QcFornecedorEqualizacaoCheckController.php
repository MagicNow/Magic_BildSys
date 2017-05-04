<?php

namespace App\Http\Controllers;

use App\DataTables\QcFornecedorEqualizacaoCheckDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateQcFornecedorEqualizacaoCheckRequest;
use App\Http\Requests\UpdateQcFornecedorEqualizacaoCheckRequest;
use App\Repositories\QcFornecedorEqualizacaoCheckRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class QcFornecedorEqualizacaoCheckController extends AppBaseController
{
    /** @var  QcFornecedorEqualizacaoCheckRepository */
    private $qcFornecedorEqualizacaoCheckRepository;

    public function __construct(QcFornecedorEqualizacaoCheckRepository $qcFornecedorEqualizacaoCheckRepo)
    {
        $this->qcFornecedorEqualizacaoCheckRepository = $qcFornecedorEqualizacaoCheckRepo;
    }

    /**
     * Display a listing of the QcFornecedorEqualizacaoCheck.
     *
     * @param QcFornecedorEqualizacaoCheckDataTable $qcFornecedorEqualizacaoCheckDataTable
     * @return Response
     */
    public function index(QcFornecedorEqualizacaoCheckDataTable $qcFornecedorEqualizacaoCheckDataTable)
    {
        return $qcFornecedorEqualizacaoCheckDataTable->render('qc_fornecedor_equalizacao_checks.index');
    }

    /**
     * Show the form for creating a new QcFornecedorEqualizacaoCheck.
     *
     * @return Response
     */
    public function create()
    {
        return view('qc_fornecedor_equalizacao_checks.create');
    }

    /**
     * Store a newly created QcFornecedorEqualizacaoCheck in storage.
     *
     * @param CreateQcFornecedorEqualizacaoCheckRequest $request
     *
     * @return Response
     */
    public function store(CreateQcFornecedorEqualizacaoCheckRequest $request)
    {
        $input = $request->all();

        $qcFornecedorEqualizacaoCheck = $this->qcFornecedorEqualizacaoCheckRepository->create($input);

        Flash::success('Qc Fornecedor Equalizacao Check '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('qcFornecedorEqualizacaoChecks.index'));
    }

    /**
     * Display the specified QcFornecedorEqualizacaoCheck.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $qcFornecedorEqualizacaoCheck = $this->qcFornecedorEqualizacaoCheckRepository->findWithoutFail($id);

        if (empty($qcFornecedorEqualizacaoCheck)) {
            Flash::error('Qc Fornecedor Equalizacao Check '.trans('common.not-found'));

            return redirect(route('qcFornecedorEqualizacaoChecks.index'));
        }

        return view('qc_fornecedor_equalizacao_checks.show')->with('qcFornecedorEqualizacaoCheck', $qcFornecedorEqualizacaoCheck);
    }

    /**
     * Show the form for editing the specified QcFornecedorEqualizacaoCheck.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $qcFornecedorEqualizacaoCheck = $this->qcFornecedorEqualizacaoCheckRepository->findWithoutFail($id);

        if (empty($qcFornecedorEqualizacaoCheck)) {
            Flash::error('Qc Fornecedor Equalizacao Check '.trans('common.not-found'));

            return redirect(route('qcFornecedorEqualizacaoChecks.index'));
        }

        return view('qc_fornecedor_equalizacao_checks.edit')->with('qcFornecedorEqualizacaoCheck', $qcFornecedorEqualizacaoCheck);
    }

    /**
     * Update the specified QcFornecedorEqualizacaoCheck in storage.
     *
     * @param  int              $id
     * @param UpdateQcFornecedorEqualizacaoCheckRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQcFornecedorEqualizacaoCheckRequest $request)
    {
        $qcFornecedorEqualizacaoCheck = $this->qcFornecedorEqualizacaoCheckRepository->findWithoutFail($id);

        if (empty($qcFornecedorEqualizacaoCheck)) {
            Flash::error('Qc Fornecedor Equalizacao Check '.trans('common.not-found'));

            return redirect(route('qcFornecedorEqualizacaoChecks.index'));
        }

        $qcFornecedorEqualizacaoCheck = $this->qcFornecedorEqualizacaoCheckRepository->update($request->all(), $id);

        Flash::success('Qc Fornecedor Equalizacao Check '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('qcFornecedorEqualizacaoChecks.index'));
    }

    /**
     * Remove the specified QcFornecedorEqualizacaoCheck from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $qcFornecedorEqualizacaoCheck = $this->qcFornecedorEqualizacaoCheckRepository->findWithoutFail($id);

        if (empty($qcFornecedorEqualizacaoCheck)) {
            Flash::error('Qc Fornecedor Equalizacao Check '.trans('common.not-found'));

            return redirect(route('qcFornecedorEqualizacaoChecks.index'));
        }

        $this->qcFornecedorEqualizacaoCheckRepository->delete($id);

        Flash::success('Qc Fornecedor Equalizacao Check '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('qcFornecedorEqualizacaoChecks.index'));
    }
}
