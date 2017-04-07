<?php

namespace App\Http\Controllers\API;

use App\Http\Requests;
use App\Repositories\API\ListagemOCRepository;
use App\Http\Controllers\AppBaseController;
use Flash;
use Response;

class ListagemOCController extends AppBaseController
{
    /**
     * @var ListagemOCRepository
     */
    protected $listagemOCRepository;

    /**
     * @param ListagemOCRepository $listagemOCRepository
     */
    function __construct(ListagemOCRepository $listagemOCRepository)
    {
        $this->listagemOCRepository = $listagemOCRepository;
    }

    /**
     * Returns all OrdemDeCompra - paginated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $listagem_oc = $this->listagemOCRepository->paginate(10);

        return response()->json($listagem_oc, 200);
    }
}
