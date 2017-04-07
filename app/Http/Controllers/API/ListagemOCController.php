<?php

namespace App\Http\Controllers\API;

use App\Http\Requests;
use App\Http\Controllers\AppBaseController;
use App\Models\OrdemDeCompra;
use App\Repositories\CodeRepository;
use Flash;
use Illuminate\Http\Request;
use Response;

class ListagemOCController extends AppBaseController
{
    /**
     * Returns all OrdemDeCompra - paginated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $listagem_oc = OrdemDeCompra::query();

        CodeRepository::filter($listagem_oc, $request->all());

        $listagem_oc = $listagem_oc->paginate(10);

        return response()->json($listagem_oc, 200);
    }
}
