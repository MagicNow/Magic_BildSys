<?php

namespace App\Http\Controllers;

use App\DataTables\QcFornecedorDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateQcFornecedorRequest;
use App\Http\Requests\UpdateQcFornecedorRequest;
use App\Repositories\QcFornecedorRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class QcFornecedorController extends AppBaseController
{
    /** @var  QcFornecedorRepository */
    private $qcFornecedorRepository;

    public function __construct(QcFornecedorRepository $qcFornecedorRepo)
    {
        $this->qcFornecedorRepository = $qcFornecedorRepo;
    }

    /**
     * Display a listing of the QcFornecedor.
     *
     * @param QcFornecedorDataTable $qcFornecedorDataTable
     * @return Response
     */
    public function index(QcFornecedorDataTable $qcFornecedorDataTable)
    {
        return $qcFornecedorDataTable->render('qc_fornecedors.index');
    }

    /**
     * Show the form for creating a new QcFornecedor.
     *
     * @return Response
     */
    public function create()
    {
        return view('qc_fornecedors.create');
    }

    /**
     * Store a newly created QcFornecedor in storage.
     *
     * @param CreateQcFornecedorRequest $request
     *
     * @return Response
     */
    public function store(CreateQcFornecedorRequest $request)
    {
        $input = $request->all();

        $qcFornecedor = $this->qcFornecedorRepository->create($input);

        Flash::success('Qc Fornecedor '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('qcFornecedors.index'));
    }

    /**
     * Display the specified QcFornecedor.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $qcFornecedor = $this->qcFornecedorRepository->findWithoutFail($id);

        if (empty($qcFornecedor)) {
            Flash::error('Qc Fornecedor '.trans('common.not-found'));

            return redirect(route('qcFornecedors.index'));
        }

        return view('qc_fornecedors.show')->with('qcFornecedor', $qcFornecedor);
    }

    /**
     * Show the form for editing the specified QcFornecedor.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $qcFornecedor = $this->qcFornecedorRepository->findWithoutFail($id);

        if (empty($qcFornecedor)) {
            Flash::error('Qc Fornecedor '.trans('common.not-found'));

            return redirect(route('qcFornecedors.index'));
        }

        return view('qc_fornecedors.edit')->with('qcFornecedor', $qcFornecedor);
    }

    /**
     * Update the specified QcFornecedor in storage.
     *
     * @param  int              $id
     * @param UpdateQcFornecedorRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQcFornecedorRequest $request)
    {
        $qcFornecedor = $this->qcFornecedorRepository->findWithoutFail($id);

        if (empty($qcFornecedor)) {
            Flash::error('Qc Fornecedor '.trans('common.not-found'));

            return redirect(route('qcFornecedors.index'));
        }

        $qcFornecedor = $this->qcFornecedorRepository->update($request->all(), $id);

        Flash::success('Qc Fornecedor '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('qcFornecedors.index'));
    }

    /**
     * Remove the specified QcFornecedor from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $qcFornecedor = $this->qcFornecedorRepository->findWithoutFail($id);

        if (empty($qcFornecedor)) {
            Flash::error('Qc Fornecedor '.trans('common.not-found'));

            return redirect(route('qcFornecedors.index'));
        }

        $this->qcFornecedorRepository->delete($id);

        Flash::success('Qc Fornecedor '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('qcFornecedors.index'));
    }
}
