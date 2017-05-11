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
                        SELECT `status` FROM `ordem_de_compras` OC1
                        JOIN (
                            SELECT
                            z.id,
                            IF(z.igual , 0 , IF(z.maior , 1 , - 1)) AS STATUS
                            FROM
                            (
                                    SELECT
                                    OC2.id,
                                    IF(qtd_total = qtd_itens , 1 , 0) AS igual ,
                                    IF(qtd_itens > qtd_total , 1 , 0) AS maior
                                    FROM
                                    (
                                        SELECT
                                            OC3.id,
                                            (
                                                SELECT
                                                    SUM(orcamentos.qtd_total) AS total
                                                FROM
                                                    ordem_de_compra_itens
                                                INNER JOIN orcamentos ON orcamentos.obra_id = ordem_de_compra_itens.obra_id
                                                    AND orcamentos.grupo_id = ordem_de_compra_itens.grupo_id
                                                    AND orcamentos.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                                                    AND orcamentos.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                                                    AND orcamentos.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                                                    AND orcamentos.servico_id = ordem_de_compra_itens.servico_id
                                                    AND orcamentos.insumo_id = ordem_de_compra_itens.insumo_id
                                                    AND orcamentos.obra_id = ordem_de_compra_itens.obra_id
                                                WHERE
                                                    orcamentos.orcamento_tipo_id = 1
                                                    AND orcamentos.ativo = 1
                                                    AND ordem_de_compra_itens.deleted_at IS NULL
                                                    AND ordem_de_compra_itens.ordem_de_compra_id = OC3.`id`
                                            ) AS qtd_total ,
                                            (
                                                SELECT
                                                    SUM(ordem_de_compra_itens.qtd) AS qtd
                                                FROM
                                                    ordem_de_compra_itens
                                                INNER JOIN orcamentos ON orcamentos.obra_id = ordem_de_compra_itens.obra_id
                                                    AND orcamentos.grupo_id = ordem_de_compra_itens.grupo_id
                                                    AND orcamentos.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                                                    AND orcamentos.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                                                    AND orcamentos.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                                                    AND orcamentos.servico_id = ordem_de_compra_itens.servico_id
                                                    AND orcamentos.insumo_id = ordem_de_compra_itens.insumo_id
                                                    AND orcamentos.obra_id = ordem_de_compra_itens.obra_id
                                                WHERE orcamentos.orcamento_tipo_id = 1 
                                                AND ordem_de_compra_itens.deleted_at IS NULL
                                                AND orcamentos.ativo = 1
                                                AND ordem_de_compra_itens.ordem_de_compra_id = OC3.`id`
                                            ) AS qtd_itens
                                        FROM ordem_de_compras OC3
                                    ) AS x
                                    JOIN ordem_de_compras OC2 ON OC2.id = x.id
                            ) AS z
                        ) AS y ON y.id = OC1.id
                
                        WHERE OC1.id = `ordem_de_compras`.id
                        LIMIT 1
                    ) as status')
                ])
            ->join('obras', 'obras.id', '=', 'ordem_de_compras.obra_id')
            ->join('oc_status', 'oc_status.id', '=', 'ordem_de_compras.oc_status_id')
            ->join('users', 'users.id', '=', 'ordem_de_compras.user_id')
            ->where('ordem_de_compras.oc_status_id', '!=', 1)
            ->where('ordem_de_compras.oc_status_id', '!=', 6)
            ->orderBy('ordem_de_compras.id','DESC')
            ->paginate(10);

        return response()->json($listagem_oc, 200);
    }
}
