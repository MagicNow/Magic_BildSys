<?php

namespace App\Http\Controllers;


use App\Models\Lembrete;
use App\Models\Planejamento;
use App\Repositories\OrdemDeCompraRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\Admin\PlanejamentoCompraRepository;

class PlanejamentoController extends AppBaseController
{
    public function lembretes(Request $request)
    {

        if($request->exibir_por_tarefa) {
            $title = 'CONCAT(obras.nome,\' - \',planejamentos.tarefa) title';
            $url = 'CONCAT(\'/compras/obrasInsumos?planejamento_id=\',planejamentos.id,\'&obra_id=\',obras.id) as url';
        } else {
            $title = 'CONCAT(obras.nome,\' - \',planejamentos.tarefa,\' - \', lembretes.nome) title';
            $url = 'CONCAT(\'/compras/obrasInsumos?planejamento_id=\',planejamentos.id,\'&insumo_grupos_id=\',insumo_grupos.id,\'&obra_id=\',obras.id) as url';
        }

        if(!$request->exibir_por_tarefa) {
            $lembretes = Lembrete::join('insumo_grupos', 'insumo_grupos.id', '=', 'lembretes.insumo_grupo_id')
                ->join('insumos', 'insumos.insumo_grupo_id', '=', 'insumo_grupos.id')
                ->join('planejamento_compras', 'planejamento_compras.insumo_id', '=', 'insumos.id')
                ->join('planejamentos', 'planejamentos.id', '=', 'planejamento_compras.planejamento_id')
                ->join('obras', 'obras.id', '=', 'planejamentos.obra_id')
                ->join('obra_users', 'obra_users.obra_id', '=', 'obras.id')
                ->whereNull('planejamentos.deleted_at')
                ->where('lembretes.lembrete_tipo_id', 1)
                ->where('obra_users.user_id', $request->user()->id)
                ->select([
                    'planejamentos.id',
                    'obras.nome as obra',
                    'planejamentos.tarefa',
                    DB::raw($title),
                    DB::raw($url),
                    DB::raw("'event-info' as class"),
                    DB::raw("DATE_FORMAT(DATE_SUB(planejamentos.data, INTERVAL (
                    IFNULL(
                        (
                            SELECT
                            SUM(L.dias_prazo_minimo) prazo
                            FROM
                            lembretes L
                            JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                            WHERE
                            EXISTS(
                                SELECT
                                1
                                FROM
                                insumos I
                                WHERE
                                I.id = insumos.id
                                AND I.insumo_grupo_id = IG.id
                            )
                            AND L.deleted_at IS NULL
                        ) ,
                        0
                    ) + IFNULL(
                        (
                            SELECT
                            SUM(dias_prazo) prazo
                            FROM
                            workflow_alcadas
                            WHERE
                            EXISTS(
                                SELECT
                                1
                                FROM
                                workflow_usuarios
                                WHERE
                                workflow_alcada_id = workflow_alcadas.id
                            )
                        ) ,
                        0
                    )
                ) DAY),'%d/%m/%Y') as inicio"), /* inicio */
                    DB::raw("UNIX_TIMESTAMP(DATE_SUB(planejamentos.data, INTERVAL (
                    IFNULL(
                        (
                            SELECT
                            SUM(L.dias_prazo_minimo) prazo
                            FROM
                            lembretes L
                            JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                            WHERE
                            EXISTS(
                                SELECT
                                1
                                FROM
                                insumos I
                                WHERE
                                I.id = insumos.id
                                AND I.insumo_grupo_id = IG.id
                            )
                            AND L.deleted_at IS NULL
                        ) ,
                        0
                    ) + IFNULL(
                        (
                            SELECT
                            SUM(dias_prazo) prazo
                            FROM
                            workflow_alcadas
                            WHERE
                            EXISTS(
                                SELECT
                                1
                                FROM
                                workflow_usuarios
                                WHERE
                                workflow_alcada_id = workflow_alcadas.id
                            )
                        ) ,
                        0
                    )
                ) DAY))*1000 as start"), /* start */
                    DB::raw("UNIX_TIMESTAMP(DATE_SUB(planejamentos.data, INTERVAL (
                    IFNULL(
                        (
                            SELECT
                            SUM(L.dias_prazo_minimo) prazo
                            FROM
                            lembretes L
                            JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                            WHERE
                            EXISTS(
                                SELECT
                                1
                                FROM
                                insumos I
                                WHERE
                                I.id = insumos.id
                                AND I.insumo_grupo_id = IG.id
                            )
                            AND L.deleted_at IS NULL
                        ) ,
                        0
                    ) + IFNULL(
                        (
                            SELECT
                            SUM(dias_prazo) prazo
                            FROM
                            workflow_alcadas
                            WHERE
                            EXISTS(
                                SELECT
                                1
                                FROM
                                workflow_usuarios
                                WHERE
                                workflow_alcada_id = workflow_alcadas.id
                            )
                        ) ,
                        0
                    )
                ) DAY))*1000 as end"),
                ]);

            if ($request->from || $request->to) {
                if ($request->from) {
                    $from = date('Y-m-d', $request->from / 1000);
                    $lembretes->where(DB::raw('DATE_SUB(planejamentos.data, INTERVAL (
                    IFNULL(
                        (
                            SELECT
                            SUM(L.dias_prazo_minimo) prazo
                            FROM
                            lembretes L
                            JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                            WHERE
                            EXISTS(
                                SELECT
                                1
                                FROM
                                insumos I
                                WHERE
                                I.id = insumos.id
                                AND I.insumo_grupo_id = IG.id
                            )
                            AND L.deleted_at IS NULL
                        ) ,
                        0
                    ) + IFNULL(
                        (
                            SELECT
                            SUM(dias_prazo) prazo
                            FROM
                            workflow_alcadas
                            WHERE
                            EXISTS(
                                SELECT
                                1
                                FROM
                                workflow_usuarios
                                WHERE
                                workflow_alcada_id = workflow_alcadas.id
                            )
                        ) ,
                        0
                    )
                ) DAY)'), '>=', $from);
                }
                if ($request->to) {
                    $to = date('Y-m-d', $request->to / 1000);
                    $lembretes->where(DB::raw('DATE_SUB(planejamentos.data, INTERVAL (
                    IFNULL(
                        (
                            SELECT
                            SUM(L.dias_prazo_minimo) prazo
                            FROM
                            lembretes L
                            JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                            WHERE
                            EXISTS(
                                SELECT
                                1
                                FROM
                                insumos I
                                WHERE
                                I.id = insumos.id
                                AND I.insumo_grupo_id = IG.id
                            )
                            AND L.deleted_at IS NULL
                        ) ,
                        0
                    ) + IFNULL(
                        (
                            SELECT
                            SUM(dias_prazo) prazo
                            FROM
                            workflow_alcadas
                            WHERE
                            EXISTS(
                                SELECT
                                1
                                FROM
                                workflow_usuarios
                                WHERE
                                workflow_alcada_id = workflow_alcadas.id
                            )
                        ) ,
                        0
                    )
                ) DAY)'), '<=', $to);
                }
            } else {
                $lembretes->where(DB::raw('DATE_SUB(planejamentos.data, INTERVAL (
                IFNULL(
                    (
                        SELECT
                        SUM(L.dias_prazo_minimo) prazo
                        FROM
                        lembretes L
                        JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                        WHERE
                        EXISTS(
                            SELECT
                            1
                            FROM
                            insumos I
                            WHERE
                            I.id = insumos.id
                            AND I.insumo_grupo_id = IG.id
                        )
                        AND L.deleted_at IS NULL
                    ) ,
                    0
                ) + IFNULL(
                    (
                        SELECT
                        SUM(dias_prazo) prazo
                        FROM
                        workflow_alcadas
                        WHERE
                        EXISTS(
                            SELECT
                            1
                            FROM
                            workflow_usuarios
                            WHERE
                            workflow_alcada_id = workflow_alcadas.id
                        )
                    ) ,
                    0
                )
            ) DAY)'), '<=', DB::raw('CURRENT_DATE'));
            }

            if ($request->obra_id) {
                $lembretes->where('planejamentos.obra_id', $request->obra_id);
            }
            if ($request->planejamento_id) {
                $lembretes->where('planejamentos.id', $request->planejamento_id);
            }
            if ($request->insumo_grupo_id) {
                $lembretes->where('insumos.insumo_grupo_id', $request->insumo_grupo_id);
            }
            $lembretes->whereRaw(PlanejamentoCompraRepository::existeItemParaComprar($request->insumo_grupo_id));

            $lembretes = $lembretes->groupBy(['id', 'obra', 'tarefa', 'title', 'class', 'url', 'inicio', 'start', 'end']);

        }else{
            $lembretes = DB::table(
                DB::raw('(SELECT tarefa, id, obra, title, url, class, inicio, start
                            FROM (SELECT
	                                planejamentos.id,
	                                obras.nome AS obra,
	                                planejamentos.tarefa,
	                                '.$title.',
	                                '.$url.',
	                                \'event-info\' AS class,
	                                DATE_FORMAT(
	                                	DATE_SUB(
	                                		planejamentos.data,
	                                		INTERVAL (
	                                			IFNULL(
	                                				(
	                                					SELECT
	                                						SUM(L.dias_prazo_minimo) prazo
	                                					FROM
	                                						lembretes L
	                                					JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
	                                					WHERE
	                                						EXISTS (
	                                							SELECT
	                                								1
	                                							FROM
	                                								insumos I
	                                							WHERE
	                                								I.id = insumos.id
	                                							AND I.insumo_grupo_id = IG.id
	                                						)
	                                					AND L.deleted_at IS NULL
	                                				),
	                                				0
	                                			) + IFNULL(
	                                				(
	                                					SELECT
	                                						SUM(dias_prazo) prazo
	                                					FROM
	                                						workflow_alcadas
	                                					WHERE
	                                						EXISTS (
	                                							SELECT
	                                								1
	                                							FROM
	                                								workflow_usuarios
	                                							WHERE
	                                								workflow_alcada_id = workflow_alcadas.id
	                                						)
	                                				),
	                                				0
	                                			)
	                                		) DAY
	                                	),
	                                	\'%d/%m/%Y\'
	                                ) AS inicio,
	                                UNIX_TIMESTAMP(
	                                	DATE_SUB(
	                                		planejamentos.data,
	                                		INTERVAL (
	                                			IFNULL(
	                                				(
	                                					SELECT
	                                						SUM(L.dias_prazo_minimo) prazo
	                                					FROM
	                                						lembretes L
	                                					JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
	                                					WHERE
	                                						EXISTS (
	                                							SELECT
	                                								1
	                                							FROM
	                                								insumos I
	                                							WHERE
	                                								I.id = insumos.id
	                                							AND I.insumo_grupo_id = IG.id
	                                						)
	                                					AND L.deleted_at IS NULL
	                                				),
	                                				0
	                                			) + IFNULL(
	                                				(
	                                					SELECT
	                                						SUM(dias_prazo) prazo
	                                					FROM
	                                						workflow_alcadas
	                                					WHERE
	                                						EXISTS (
	                                							SELECT
	                                								1
	                                							FROM
	                                								workflow_usuarios
	                                							WHERE
	                                								workflow_alcada_id = workflow_alcadas.id
	                                						)
	                                				),
	                                				0
	                                			)
	                                		) DAY
	                                	)
	                                ) * 1000 + (100000 * 180) AS START
                                FROM lembretes
                                INNER JOIN insumo_grupos ON insumo_grupos.id = lembretes.insumo_grupo_id
                                INNER JOIN insumos ON insumos.insumo_grupo_id = insumo_grupos.id
                                INNER JOIN planejamento_compras ON planejamento_compras.insumo_id = insumos.id
                                INNER JOIN planejamentos ON planejamentos.id = planejamento_compras.planejamento_id
                                INNER JOIN obras ON obras.id = planejamentos.obra_id
                                INNER JOIN obra_users ON obra_users.obra_id = obras.id
                                WHERE planejamentos.deleted_at IS NULL
                                AND lembretes.lembrete_tipo_id = 1
                                AND obra_users.user_id = '.$request->user()->id.'
                                AND (
	                                SELECT
	                                	1
	                                FROM
	                                	planejamento_compras plc
	                                JOIN planejamentos P ON P.id = plc.planejamento_id
	                                LEFT JOIN ordem_de_compra_itens oci ON oci.insumo_id = plc.insumo_id
	                                AND oci.grupo_id = plc.grupo_id
	                                AND oci.subgrupo1_id = plc.subgrupo1_id
	                                AND oci.subgrupo2_id = plc.subgrupo2_id
	                                AND oci.subgrupo3_id = plc.subgrupo3_id
	                                AND oci.servico_id = plc.servico_id
	                                AND oci.obra_id = P.obra_id
	                                JOIN orcamentos orc ON orc.insumo_id = plc.insumo_id
	                                AND orc.grupo_id = plc.grupo_id
	                                AND orc.subgrupo1_id = plc.subgrupo1_id
	                                AND orc.subgrupo2_id = plc.subgrupo2_id
	                                AND orc.subgrupo3_id = plc.subgrupo3_id
	                                AND orc.servico_id = plc.servico_id
	                                AND orc.ativo = 1
	                                AND orc.obra_id = P.obra_id
	                                LEFT JOIN ordem_de_compras ocs ON ocs.id = oci.ordem_de_compra_id
	                                AND ocs.oc_status_id NOT IN (1, 4, 6)
	                                WHERE
	                                	P.id = planejamentos.id
	                                AND plc.deleted_at IS NULL
	                                AND orc.qtd_total > 0
	                                AND IFNULL(oci.qtd, 0) < orc.qtd_total
	                                LIMIT 1
                                ) IS NOT NULL
                                AND lembretes.deleted_at IS NULL
                                ' . (isset($request->obra_id) ? ' AND planejamentos.obra_id = ' . $request->obra_id : '') . '
                                ' . (isset($request->planejamento_id) ? ' AND planejamentos.id = ' . $request->planejamento_id : '') . '
                                ' . (isset($request->insumo_grupo_id) ? ' AND insumos.insumo_grupo_id = ' . $request->insumo_grupo_id : '') . '
                                ) as queryInterna
                                GROUP BY tarefa
                                ORDER BY
					            STR_TO_DATE(inicio,\'%d/%m/%Y\') ASC) as xpto'
                )
            );
        }
//        echo $lembretes->toSql();

        $query = OrdemDeCompraRepository::queryCalendarioLembretes(
            $request->get('obra_id'),
            $request->get('planejamento_id'),
            $request->get('insumo_grupo_id'),
            $request->get('carteira_id'),
            $request->exibir_por_tarefa,
            $request->exibir_por_carteira,
//            $request->get('from'),
//            $request->get('to')
            null,
            null
        );

        if(count($query)) {
            $query = $query->get();
        }

        return response()->json([
            'success' => true,
            'result' => $query
        ]);
    }

    public function getPlanejamentosByObra(Request $request)
    {
        $planejamentos = Planejamento::where('obra_id', $request->obra_id)
            ->where('planejamentos.tarefa','LIKE', '%'.$request->q.'%')
            ->select([
                'planejamentos.id',
                'planejamentos.tarefa as text'
            ])
            ->where('planejamentos.resumo','Sim')
            ->groupBy('planejamentos.id','planejamentos.tarefa');

        return $planejamentos->paginate();
    }
    public function getListaDePlanejamentosByObra(Request $request)
    {
        $planejamentos = Planejamento::join('planejamento_compras','planejamento_compras.planejamento_id', 'planejamentos.id')
            ->select([
                'planejamentos.id',
                'planejamentos.tarefa'
            ])
            ->where('planejamentos.resumo','Sim')
            ->groupBy('planejamentos.id','planejamentos.tarefa');

        if($request->obra_id != 'todas') {
            $planejamentos = $planejamentos->where('obra_id', $request->obra_id);
        }
        return $planejamentos->get();
    }
}
