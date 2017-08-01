<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Models\InsumoGrupo;
use Illuminate\Http\Request;
use App\Repositories\SolicitacaoInsumoRepository;
use App\DataTables\Admin\SolicitacaoInsumoDataTable;
use App\Http\Requests\CreateSolicitacaoInsumoRequest;

class SolicitacaoInsumoController extends AppBaseController
{
    private $solicitacaoInsumoRepository;

    /**
     * @param  SolicitacaoInsumoRepository $solicitacaoInsumoRepository
     */
    public function __construct(
        SolicitacaoInsumoRepository $solicitacaoInsumoRepository
    ) {
        $this->solicitacaoInsumoRepository = $solicitacaoInsumoRepository;
    }

    public function create(Request $request)
    {
        return view('solicitacao_insumos.create');
    }

    public function store(
        CreateSolicitacaoInsumoRequest $request
    ) {
        $input = $request->all();

        $solicitacaoInsumo = $this->solicitacaoInsumoRepository->create($input);

        Flash::success('Solicitação de insumo realizada');

        return redirect($request->next ?: url()->previous());
    }
}
