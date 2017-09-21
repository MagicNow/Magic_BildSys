<?php

namespace App\Http\Controllers;

use App\DataTables\DocumentoFinanceiroTipoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateDocumentoFinanceiroTipoRequest;
use App\Http\Requests\UpdateDocumentoFinanceiroTipoRequest;
use App\Repositories\DocumentoFinanceiroTipoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class DocumentoFinanceiroTipoController extends AppBaseController
{
    /** @var  DocumentoFinanceiroTipoRepository */
    private $documentoFinanceiroTipoRepository;

    public function __construct(DocumentoFinanceiroTipoRepository $documentoFinanceiroTipoRepo)
    {
        $this->documentoFinanceiroTipoRepository = $documentoFinanceiroTipoRepo;
    }

    /**
     * Display a listing of the DocumentoFinanceiroTipo.
     *
     * @param DocumentoFinanceiroTipoDataTable $documentoFinanceiroTipoDataTable
     * @return Response
     */
    public function index(DocumentoFinanceiroTipoDataTable $documentoFinanceiroTipoDataTable)
    {
        return $documentoFinanceiroTipoDataTable->render('documento_financeiro_tipos.index');
    }

    /**
     * Show the form for creating a new DocumentoFinanceiroTipo.
     *
     * @return Response
     */
    public function create()
    {
        return view('documento_financeiro_tipos.create');
    }

    /**
     * Store a newly created DocumentoFinanceiroTipo in storage.
     *
     * @param CreateDocumentoFinanceiroTipoRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoFinanceiroTipoRequest $request)
    {
        $input = $request->all();

        $documentoFinanceiroTipo = $this->documentoFinanceiroTipoRepository->create($input);

        Flash::success('Documento Financeiro Tipo '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('documentoFinanceiroTipos.index'));
    }

    /**
     * Display the specified DocumentoFinanceiroTipo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $documentoFinanceiroTipo = $this->documentoFinanceiroTipoRepository->findWithoutFail($id);

        if (empty($documentoFinanceiroTipo)) {
            Flash::error('Documento Financeiro Tipo '.trans('common.not-found'));

            return redirect(route('documentoFinanceiroTipos.index'));
        }

        return view('documento_financeiro_tipos.show')->with('documentoFinanceiroTipo', $documentoFinanceiroTipo);
    }

    /**
     * Show the form for editing the specified DocumentoFinanceiroTipo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $documentoFinanceiroTipo = $this->documentoFinanceiroTipoRepository->findWithoutFail($id);

        if (empty($documentoFinanceiroTipo)) {
            Flash::error('Documento Financeiro Tipo '.trans('common.not-found'));

            return redirect(route('documentoFinanceiroTipos.index'));
        }

        return view('documento_financeiro_tipos.edit')->with('documentoFinanceiroTipo', $documentoFinanceiroTipo);
    }

    /**
     * Update the specified DocumentoFinanceiroTipo in storage.
     *
     * @param  int              $id
     * @param UpdateDocumentoFinanceiroTipoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentoFinanceiroTipoRequest $request)
    {
        $documentoFinanceiroTipo = $this->documentoFinanceiroTipoRepository->findWithoutFail($id);

        if (empty($documentoFinanceiroTipo)) {
            Flash::error('Documento Financeiro Tipo '.trans('common.not-found'));

            return redirect(route('documentoFinanceiroTipos.index'));
        }

        $documentoFinanceiroTipo = $this->documentoFinanceiroTipoRepository->update($request->all(), $id);

        Flash::success('Documento Financeiro Tipo '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('documentoFinanceiroTipos.index'));
    }

    /**
     * Remove the specified DocumentoFinanceiroTipo from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $documentoFinanceiroTipo = $this->documentoFinanceiroTipoRepository->findWithoutFail($id);

        if (empty($documentoFinanceiroTipo)) {
            Flash::error('Documento Financeiro Tipo '.trans('common.not-found'));

            return redirect(route('documentoFinanceiroTipos.index'));
        }

        $this->documentoFinanceiroTipoRepository->delete($id);

        Flash::success('Documento Financeiro Tipo '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('documentoFinanceiroTipos.index'));
    }
}
