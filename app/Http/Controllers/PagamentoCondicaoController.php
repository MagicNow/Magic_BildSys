<?php

namespace App\Http\Controllers;

use App\DataTables\PagamentoCondicaoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePagamentoCondicaoRequest;
use App\Http\Requests\UpdatePagamentoCondicaoRequest;
use App\Repositories\PagamentoCondicaoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class PagamentoCondicaoController extends AppBaseController
{
    /** @var  PagamentoCondicaoRepository */
    private $pagamentoCondicaoRepository;

    public function __construct(PagamentoCondicaoRepository $pagamentoCondicaoRepo)
    {
        $this->pagamentoCondicaoRepository = $pagamentoCondicaoRepo;
    }

    /**
     * Display a listing of the PagamentoCondicao.
     *
     * @param PagamentoCondicaoDataTable $pagamentoCondicaoDataTable
     * @return Response
     */
    public function index(PagamentoCondicaoDataTable $pagamentoCondicaoDataTable)
    {
        return $pagamentoCondicaoDataTable->render('pagamento_condicaos.index');
    }

    /**
     * Show the form for creating a new PagamentoCondicao.
     *
     * @return Response
     */
    public function create()
    {
        return view('pagamento_condicaos.create');
    }

    /**
     * Store a newly created PagamentoCondicao in storage.
     *
     * @param CreatePagamentoCondicaoRequest $request
     *
     * @return Response
     */
    public function store(CreatePagamentoCondicaoRequest $request)
    {
        $input = $request->all();

        $pagamentoCondicao = $this->pagamentoCondicaoRepository->create($input);

        Flash::success('Pagamento Condicao '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('pagamentoCondicaos.index'));
    }

    /**
     * Display the specified PagamentoCondicao.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $pagamentoCondicao = $this->pagamentoCondicaoRepository->findWithoutFail($id);

        if (empty($pagamentoCondicao)) {
            Flash::error('Pagamento Condicao '.trans('common.not-found'));

            return redirect(route('pagamentoCondicaos.index'));
        }

        return view('pagamento_condicaos.show')->with('pagamentoCondicao', $pagamentoCondicao);
    }

    /**
     * Show the form for editing the specified PagamentoCondicao.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $pagamentoCondicao = $this->pagamentoCondicaoRepository->findWithoutFail($id);

        if (empty($pagamentoCondicao)) {
            Flash::error('Pagamento Condicao '.trans('common.not-found'));

            return redirect(route('pagamentoCondicaos.index'));
        }

        return view('pagamento_condicaos.edit')->with('pagamentoCondicao', $pagamentoCondicao);
    }

    /**
     * Update the specified PagamentoCondicao in storage.
     *
     * @param  int              $id
     * @param UpdatePagamentoCondicaoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePagamentoCondicaoRequest $request)
    {
        $pagamentoCondicao = $this->pagamentoCondicaoRepository->findWithoutFail($id);

        if (empty($pagamentoCondicao)) {
            Flash::error('Pagamento Condicao '.trans('common.not-found'));

            return redirect(route('pagamentoCondicaos.index'));
        }

        $pagamentoCondicao = $this->pagamentoCondicaoRepository->update($request->all(), $id);

        Flash::success('Pagamento Condicao '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('pagamentoCondicaos.index'));
    }

    /**
     * Remove the specified PagamentoCondicao from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $pagamentoCondicao = $this->pagamentoCondicaoRepository->findWithoutFail($id);

        if (empty($pagamentoCondicao)) {
            Flash::error('Pagamento Condicao '.trans('common.not-found'));

            return redirect(route('pagamentoCondicaos.index'));
        }

        $this->pagamentoCondicaoRepository->delete($id);

        Flash::success('Pagamento Condicao '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('pagamentoCondicaos.index'));
    }
}
