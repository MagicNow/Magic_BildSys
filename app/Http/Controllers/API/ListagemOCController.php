<?php

namespace App\Http\Controllers\API;

use App\Http\Requests;
use App\Http\Controllers\AppBaseController;
use App\Models\OrdemDeCompra;
use App\Repositories\CodeRepository;
use Flash;
use Illuminate\Http\Request;
use Response;
use DB;

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

        $filters_find = ['ordem_de_compras.id', 'obras.nome'];

        $listagem_oc = CodeRepository::filter($listagem_oc, $request->all(), $filters_find);

        $listagem_oc = $listagem_oc
            ->select([
                    'ordem_de_compras.id',
                    'obras.nome as obra',
                    'users.name as usuario',
                    'oc_status.nome as situacao',
                    DB::raw('(
                        SELECT
                            SUM(qtd)
                        FROM
                            ordem_de_compra_itens
                        WHERE
                            ordem_de_compra_itens.ordem_de_compra_id = ordem_de_compras.id
                        AND 
                            ordem_de_compra_itens.deleted_at IS NULL
                    ) AS total_comprado'),
                    DB::raw('(
                        SELECT
                            SUM(qtd_total)
                        FROM
                            orcamentos
                        WHERE
                            orcamentos.obra_id = ordem_de_compras.obra_id
                        AND
                            orcamentos.orcamento_tipo_id = 1
                    ) AS total_para_comprar')
                ])
            ->join('obras', 'obras.id', '=', 'ordem_de_compras.obra_id')
            ->join('oc_status', 'oc_status.id', '=', 'ordem_de_compras.oc_status_id')
            ->join('users', 'users.id', '=', 'ordem_de_compras.user_id')
            ->paginate(10);

        return response()->json($listagem_oc, 200);
    }
}
