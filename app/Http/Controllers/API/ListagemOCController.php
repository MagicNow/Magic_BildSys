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
                        IF(igual, 0, IF(maior, 1, - 1)) AS status
                    FROM
                        (SELECT 
                            IF(qtd_total = qtd_itens, 1, 0) AS igual,
                                IF(qtd_itens > qtd_total, 1, 0) AS maior
                        FROM
                            (SELECT 
                            (SELECT 
                                        SUM(orcamentos.qtd_total) AS total
                                    FROM
                                        ordem_de_compra_itens
                                    INNER JOIN orcamentos ON orcamentos.obra_id = ordem_de_compra_itens.obra_id
                                    INNER JOIN orcamentos orc_grupo ON orc_grupo.grupo_id = ordem_de_compra_itens.grupo_id
                                    INNER JOIN orcamentos orc_subgrupo1 ON orc_subgrupo1.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                                    INNER JOIN orcamentos orc_subgrupo2 ON orc_subgrupo2.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                                    INNER JOIN orcamentos orc_subgrupo3 ON orc_subgrupo3.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                                    INNER JOIN orcamentos orc_servico ON orc_servico.servico_id = ordem_de_compra_itens.servico_id
                                    INNER JOIN orcamentos orc_insumo ON orc_insumo.insumo_id = ordem_de_compra_itens.insumo_id
                                    WHERE
                                        orcamentos.orcamento_tipo_id = 1
                                            AND ordem_de_compra_itens.deleted_at IS NULL) AS qtd_total,
                                (SELECT 
                                        SUM(ordem_de_compra_itens.qtd) AS qtd
                                    FROM
                                        ordem_de_compra_itens
                                    INNER JOIN orcamentos ON orcamentos.obra_id = ordem_de_compra_itens.obra_id
                                    INNER JOIN orcamentos orc_grupo ON orc_grupo.grupo_id = ordem_de_compra_itens.grupo_id
                                    INNER JOIN orcamentos orc_subgrupo1 ON orc_subgrupo1.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                                    INNER JOIN orcamentos orc_subgrupo2 ON orc_subgrupo2.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                                    INNER JOIN orcamentos orc_subgrupo3 ON orc_subgrupo3.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                                    INNER JOIN orcamentos orc_servico ON orc_servico.servico_id = ordem_de_compra_itens.servico_id
                                    INNER JOIN orcamentos orc_insumo ON orc_insumo.insumo_id = ordem_de_compra_itens.insumo_id
                                    WHERE
                                        orcamentos.orcamento_tipo_id = 1
                                            AND ordem_de_compra_itens.deleted_at IS NULL) AS qtd_itens
                        ) AS x) AS y
                    ) as status')
                ])
            ->join('obras', 'obras.id', '=', 'ordem_de_compras.obra_id')
            ->join('oc_status', 'oc_status.id', '=', 'ordem_de_compras.oc_status_id')
            ->join('users', 'users.id', '=', 'ordem_de_compras.user_id')
            ->where('ordem_de_compras.oc_status_id', '!=', 1)
            ->where('ordem_de_compras.oc_status_id', '!=', 6)
            ->paginate(10);

        return response()->json($listagem_oc, 200);
    }
}
