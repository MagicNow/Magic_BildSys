<?php

namespace App\Http\Controllers;

use App\DataTables\PadraoEmpreendimentoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePadraoEmpreendimentoRequest;
use App\Http\Requests\UpdatePadraoEmpreendimentoRequest;
use App\Repositories\PadraoEmpreendimentoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class PadraoEmpreendimentoController extends AppBaseController
{
    /** @var  PadraoEmpreendimentoRepository */
    private $padraoEmpreendimentoRepository;

    public function __construct(PadraoEmpreendimentoRepository $padraoEmpreendimentoRepo)
    {
        $this->padraoEmpreendimentoRepository = $padraoEmpreendimentoRepo;
    }

    /**
     * Display a listing of the PadraoEmpreendimento.
     *
     * @param PadraoEmpreendimentoDataTable $padraoEmpreendimentoDataTable
     * @return Response
     */
    public function index(PadraoEmpreendimentoDataTable $padraoEmpreendimentoDataTable)
    {
        return $padraoEmpreendimentoDataTable->render('padrao_empreendimentos.index');
    }

    /**
     * Show the form for creating a new PadraoEmpreendimento.
     *
     * @return Response
     */
    public function create()
    {
        return view('padrao_empreendimentos.create');
    }

    /**
     * Store a newly created PadraoEmpreendimento in storage.
     *
     * @param CreatePadraoEmpreendimentoRequest $request
     *
     * @return Response
     */
    public function store(CreatePadraoEmpreendimentoRequest $request)
    {
        $input = $request->all();

        $padraoEmpreendimento = $this->padraoEmpreendimentoRepository->create($input);

        Flash::success('Padrao Empreendimento '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('padraoEmpreendimentos.index'));
    }

    /**
     * Display the specified PadraoEmpreendimento.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $padraoEmpreendimento = $this->padraoEmpreendimentoRepository->findWithoutFail($id);

        if (empty($padraoEmpreendimento)) {
            Flash::error('Padrao Empreendimento '.trans('common.not-found'));

            return redirect(route('padraoEmpreendimentos.index'));
        }

        return view('padrao_empreendimentos.show')->with('padraoEmpreendimento', $padraoEmpreendimento);
    }

    /**
     * Show the form for editing the specified PadraoEmpreendimento.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $padraoEmpreendimento = $this->padraoEmpreendimentoRepository->findWithoutFail($id);

        if (empty($padraoEmpreendimento)) {
            Flash::error('Padrao Empreendimento '.trans('common.not-found'));

            return redirect(route('padraoEmpreendimentos.index'));
        }

        return view('padrao_empreendimentos.edit')->with('padraoEmpreendimento', $padraoEmpreendimento);
    }

    /**
     * Update the specified PadraoEmpreendimento in storage.
     *
     * @param  int              $id
     * @param UpdatePadraoEmpreendimentoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePadraoEmpreendimentoRequest $request)
    {
        $padraoEmpreendimento = $this->padraoEmpreendimentoRepository->findWithoutFail($id);

        if (empty($padraoEmpreendimento)) {
            Flash::error('Padrao Empreendimento '.trans('common.not-found'));

            return redirect(route('padraoEmpreendimentos.index'));
        }

        $padraoEmpreendimento = $this->padraoEmpreendimentoRepository->update($request->all(), $id);

        Flash::success('Padrao Empreendimento '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('padraoEmpreendimentos.index'));
    }

    /**
     * Remove the specified PadraoEmpreendimento from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $padraoEmpreendimento = $this->padraoEmpreendimentoRepository->findWithoutFail($id);

        if (empty($padraoEmpreendimento)) {
            Flash::error('Padrao Empreendimento '.trans('common.not-found'));

            return redirect(route('padraoEmpreendimentos.index'));
        }

        $this->padraoEmpreendimentoRepository->delete($id);

        Flash::success('Padrao Empreendimento '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('padraoEmpreendimentos.index'));
    }
}
