<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SolicitacaoEntregaRepository;

class SolicitacaoEntregaController extends AppBaseController
{
    /**
     * Show an especific resource
     *
     * @param mixed $id
     *
     * @return Response
     */
    public function show(SolicitacaoEntregaRepository $repository, $id)
    {
        $entrega = $repository->find($id)->load('itens.apropriacoes');

        return view('solicitacao_entrega.show', compact('entrega'));
    }
}
