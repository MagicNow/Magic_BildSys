<?php

namespace App\Http\Controllers;

use App\DataTables\DocumentoTipoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateDocumentoTipoRequest;
use App\Http\Requests\UpdateDocumentoTipoRequest;
use App\Repositories\DocumentoTipoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class DocumentoTipoController extends AppBaseController
{
    /** @var  DocumentoTipoRepository */
    private $documentoTipoRepository;

    public function __construct(DocumentoTipoRepository $documentoTipoRepo)
    {
        $this->documentoTipoRepository = $documentoTipoRepo;
    }

    /**
     * Display a listing of the DocumentoTipo.
     *
     * @param DocumentoTipoDataTable $documentoTipoDataTable
     * @return Response
     */
    public function index(DocumentoTipoDataTable $documentoTipoDataTable)
    {
        return $documentoTipoDataTable->render('documento_tipos.index');
    }

    /**
     * Show the form for creating a new DocumentoTipo.
     *
     * @return Response
     */
    public function create()
    {
        return view('documento_tipos.create');
    }

    /**
     * Store a newly created DocumentoTipo in storage.
     *
     * @param CreateDocumentoTipoRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoTipoRequest $request)
    {
        $input = $request->all();

        $documentoTipo = $this->documentoTipoRepository->create($input);

        Flash::success('Documento Tipo '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('documentoTipos.index'));
    }

    /**
     * Display the specified DocumentoTipo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $documentoTipo = $this->documentoTipoRepository->findWithoutFail($id);

        if (empty($documentoTipo)) {
            Flash::error('Documento Tipo '.trans('common.not-found'));

            return redirect(route('documentoTipos.index'));
        }

        return view('documento_tipos.show')->with('documentoTipo', $documentoTipo);
    }

    /**
     * Show the form for editing the specified DocumentoTipo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $documentoTipo = $this->documentoTipoRepository->findWithoutFail($id);

        if (empty($documentoTipo)) {
            Flash::error('Documento Tipo '.trans('common.not-found'));

            return redirect(route('documentoTipos.index'));
        }

        return view('documento_tipos.edit')->with('documentoTipo', $documentoTipo);
    }

    /**
     * Update the specified DocumentoTipo in storage.
     *
     * @param  int              $id
     * @param UpdateDocumentoTipoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentoTipoRequest $request)
    {
        $documentoTipo = $this->documentoTipoRepository->findWithoutFail($id);

        if (empty($documentoTipo)) {
            Flash::error('Documento Tipo '.trans('common.not-found'));

            return redirect(route('documentoTipos.index'));
        }

        $documentoTipo = $this->documentoTipoRepository->update($request->all(), $id);

        Flash::success('Documento Tipo '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('documentoTipos.index'));
    }

    /**
     * Remove the specified DocumentoTipo from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $documentoTipo = $this->documentoTipoRepository->findWithoutFail($id);

        if (empty($documentoTipo)) {
            Flash::error('Documento Tipo '.trans('common.not-found'));

            return redirect(route('documentoTipos.index'));
        }

        $this->documentoTipoRepository->delete($id);

        Flash::success('Documento Tipo '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('documentoTipos.index'));
    }
}
