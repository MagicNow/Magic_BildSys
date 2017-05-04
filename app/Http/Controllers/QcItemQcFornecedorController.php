<?php

namespace App\Http\Controllers;

use App\DataTables\QcItemQcFornecedorDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateQcItemQcFornecedorRequest;
use App\Http\Requests\UpdateQcItemQcFornecedorRequest;
use App\Repositories\QcItemQcFornecedorRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class QcItemQcFornecedorController extends AppBaseController
{
    /** @var  QcItemQcFornecedorRepository */
    private $qcItemQcFornecedorRepository;

    public function __construct(QcItemQcFornecedorRepository $qcItemQcFornecedorRepo)
    {
        $this->qcItemQcFornecedorRepository = $qcItemQcFornecedorRepo;
    }

    /**
     * Display a listing of the QcItemQcFornecedor.
     *
     * @param QcItemQcFornecedorDataTable $qcItemQcFornecedorDataTable
     * @return Response
     */
    public function index(QcItemQcFornecedorDataTable $qcItemQcFornecedorDataTable)
    {
        return $qcItemQcFornecedorDataTable->render('qc_item_qc_fornecedors.index');
    }

    /**
     * Show the form for creating a new QcItemQcFornecedor.
     *
     * @return Response
     */
    public function create()
    {
        return view('qc_item_qc_fornecedors.create');
    }

    /**
     * Store a newly created QcItemQcFornecedor in storage.
     *
     * @param CreateQcItemQcFornecedorRequest $request
     *
     * @return Response
     */
    public function store(CreateQcItemQcFornecedorRequest $request)
    {
        $input = $request->all();

        $qcItemQcFornecedor = $this->qcItemQcFornecedorRepository->create($input);

        Flash::success('Qc Item Qc Fornecedor '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('qcItemQcFornecedors.index'));
    }

    /**
     * Display the specified QcItemQcFornecedor.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $qcItemQcFornecedor = $this->qcItemQcFornecedorRepository->findWithoutFail($id);

        if (empty($qcItemQcFornecedor)) {
            Flash::error('Qc Item Qc Fornecedor '.trans('common.not-found'));

            return redirect(route('qcItemQcFornecedors.index'));
        }

        return view('qc_item_qc_fornecedors.show')->with('qcItemQcFornecedor', $qcItemQcFornecedor);
    }

    /**
     * Show the form for editing the specified QcItemQcFornecedor.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $qcItemQcFornecedor = $this->qcItemQcFornecedorRepository->findWithoutFail($id);

        if (empty($qcItemQcFornecedor)) {
            Flash::error('Qc Item Qc Fornecedor '.trans('common.not-found'));

            return redirect(route('qcItemQcFornecedors.index'));
        }

        return view('qc_item_qc_fornecedors.edit')->with('qcItemQcFornecedor', $qcItemQcFornecedor);
    }

    /**
     * Update the specified QcItemQcFornecedor in storage.
     *
     * @param  int              $id
     * @param UpdateQcItemQcFornecedorRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQcItemQcFornecedorRequest $request)
    {
        $qcItemQcFornecedor = $this->qcItemQcFornecedorRepository->findWithoutFail($id);

        if (empty($qcItemQcFornecedor)) {
            Flash::error('Qc Item Qc Fornecedor '.trans('common.not-found'));

            return redirect(route('qcItemQcFornecedors.index'));
        }

        $qcItemQcFornecedor = $this->qcItemQcFornecedorRepository->update($request->all(), $id);

        Flash::success('Qc Item Qc Fornecedor '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('qcItemQcFornecedors.index'));
    }

    /**
     * Remove the specified QcItemQcFornecedor from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $qcItemQcFornecedor = $this->qcItemQcFornecedorRepository->findWithoutFail($id);

        if (empty($qcItemQcFornecedor)) {
            Flash::error('Qc Item Qc Fornecedor '.trans('common.not-found'));

            return redirect(route('qcItemQcFornecedors.index'));
        }

        $this->qcItemQcFornecedorRepository->delete($id);

        Flash::success('Qc Item Qc Fornecedor '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('qcItemQcFornecedors.index'));
    }
}
