<?php

namespace App\Http\Controllers;

use App\DataTables\ContratoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateContratoRequest;
use App\Http\Requests\UpdateContratoRequest;
use App\Repositories\ContratoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Repositories\Admin\FornecedoresRepository;
use App\Repositories\Admin\ObraRepository;
use App\Repositories\ContratoStatusRepository;

class ContratoController extends AppBaseController
{
    /** @var  ContratoRepository */
    private $contratoRepository;

    public function __construct(ContratoRepository $contratoRepo)
    {
        $this->contratoRepository = $contratoRepo;
    }

    /**
     * Display a listing of the Contrato.
     *
     * @param ContratoDataTable $contratoDataTable
     * @return Response
     */
    public function index(
        ContratoDataTable $contratoDataTable,
        FornecedoresRepository $fornecedorRepository,
        ObraRepository $obraRepository,
        ContratoStatusRepository $contratoStatusRepository
    ) {

        $status = $contratoStatusRepository
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->all();

        $fornecedores = $fornecedorRepository
            ->comContrato()
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->all();

        $obras = $obraRepository
            ->comContrato()
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->all();

        return $contratoDataTable->render(
            'contratos.index',
            compact('status', 'fornecedores', 'obras')
        );
    }

    public function show($id)
    {
        $contrato = $this->contratoRepository->findWithoutFail($id);

        if (empty($contrato)) {
            Flash::error('Contrato '.trans('common.not-found'));

            return redirect(route('contratos.index'));
        }

        return view('contratos.show')->with('contrato', $contrato);
    }

    public function save($id)
    {
        $contrato = $this->contratoRepository->findWithoutFail($id);

        if (empty($contrato)) {
            Flash::error('Contrato '.trans('common.not-found'));

            return redirect(route('contratos.index'));
        }

        return view('contratos.edit')->with('contrato', $contrato);
    }
}
