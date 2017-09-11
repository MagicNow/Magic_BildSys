<?php

namespace App\DataTables;

use App\Models\InsumoGrupo;
use App\Models\Lembrete;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;
use App\Repositories\Admin\PlanejamentoCompraRepository;

class LembretesHomeDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        // Se veio algum filtro, caso contrário não retorna nenhum dado
        if (
            $this->request()->get('obra_id') ||
            $this->request()->get('planejamento_id') ||
            $this->request()->get('insumo_grupo_id') ||
            $this->request()->get('carteira_id')
        ) {
            if (!$this->request()->exibir_por_tarefa) {
                return $this->datatables
                    ->eloquent($this->query())
                    ->editColumn('action', 'ordem_de_compras.lembretes_home_datatables_actions')
                    ->filterColumn('inicio', function ($query, $keyword) {
                        if (strrpos($keyword, '-') === false) {
                            $query->whereRaw("DATE_FORMAT(DATE_SUB(planejamentos.data, INTERVAL (
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
                    ) DAY),'%d/%m/%Y') like ?", ["%$keyword%"]);
                        } else {
                            $range = explode('-', $keyword);
                            $inicio_array = explode('/', trim($range[0]));
                            $fim_array = explode('/', trim($range[1]));

                            if (count($inicio_array) == 3 && count($fim_array) == 3) {
                                $inicio = $inicio_array[2] . '-' . $inicio_array[1] . '-' . $inicio_array[0];
                                $fim = $fim_array[2] . '-' . $fim_array[1] . '-' . $fim_array[0];

                                $query->whereRaw("DATE(DATE_SUB(planejamentos.data, INTERVAL (
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
                    ) DAY)
                    ) BETWEEN ? AND ?", [$inicio, $fim]);
                            }
                        }
                    })
                    ->editColumn('inicio', function ($obj) {
                        if ($obj->dias < 0) {
                            $alerta = "danger";
                        } elseif ($obj->dias > 30) {
                            $alerta = "success";
                        } else {
                            $alerta = "warning";
                        }
                        return '<span class="text-' . $alerta . '"> ' . $obj->inicio . '</span>';
                    })
                    ->orderColumn('inicio', 'DATE_FORMAT(DATE_SUB(planejamentos.data, INTERVAL (
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
                    ) DAY), "%y%m%d") $1')
                    ->filterColumn('grupo', function ($query, $keyword) {
                        $query->whereRaw("insumo_grupos.nome LIKE ?", ['%' . $keyword . '%']);
                    })
                    ->filterColumn('carteira', function ($query, $keyword) {
                        $query->whereRaw("carteiras.nome LIKE ?", ['%' . $keyword . '%']);
                    })
                    ->make(true);
            } else {
                return $this->datatables
                    ->of($this->query())
                    ->editColumn('action', 'ordem_de_compras.lembretes_home_datatables_actions')
                    ->filterColumn('inicio', function ($query, $keyword) {
                        if (strrpos($keyword, '-') === false) {
                            $query->whereRaw("inicio like ?", ["%$keyword%"]);
                        } else {
                            $range = explode('-', $keyword);
                            $inicio = $range[0];
                            $fim = $range[1];

                            $query->whereRaw("inicio BETWEEN ? AND ?", [$inicio, $fim]);
                        }
                    })
                    ->editColumn('inicio', function ($obj) {
                        if ($obj->dias < 0) {
                            $alerta = "danger";
                        } elseif ($obj->dias > 30) {
                            $alerta = "success";
                        } else {
                            $alerta = "warning";
                        }
                        return '<span class="text-' . $alerta . '"> ' . $obj->inicio . '</span>';
                    })
                    ->orderColumn('inicio', 'STR_TO_DATE(inicio, \'%d/%m/%Y\')')
                    ->filterColumn('grupo', function ($query, $keyword) {
                        $query->whereRaw("insumo_grupos.nome LIKE ?", ['%' . $keyword . '%']);
                    })
                    ->filterColumn('planejamentos.tarefa', function ($query, $keyword) {
                        if (!$this->request()->exibir_por_tarefa) {
                            $query->whereRaw("planejamentos.tarefa LIKE ?", ['%' . $keyword . '%']);
                        } else {
                            $query->whereRaw("xpto.tarefa LIKE ?", ['%' . $keyword . '%']);
                        }
                    })
                    ->orderColumn('planejamentos.tarefa',
                        $this->request()->exibir_por_tarefa ? 'xpto.tarefa' : 'planejamentos.tarefa')
                    ->filterColumn('obras.nome', function ($query, $keyword) {
                        if (!$this->request()->exibir_por_tarefa) {
                            $query->whereRaw("obras.nome LIKE ?", ['%' . $keyword . '%']);
                        } else {
                            $query->whereRaw("xpto.obra LIKE ?", ['%' . $keyword . '%']);
                        }
                    })
                    ->orderColumn('obras.nome',
                        $this->request()->exibir_por_tarefa ? 'xpto.obra' : 'obras.nome')
                    ->make(true);
            }
        } else {
            return ['data' => [], 'recordsFiltered' => 0, 'recordsTotal' => 0];
        }
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        if (
            $this->request()->get('obra_id') ||
            $this->request()->get('planejamento_id') ||
            $this->request()->get('insumo_grupo_id') ||
            $this->request()->get('carteira_id')
        ) {
            if ($this->request()->exibir_por_tarefa) {
                $url = 'CONCAT(\'/compras/obrasInsumos?planejamento_id=\',planejamentos.id,\'&obra_id=\',obras.id) as url';
            } else {
                $url = 'CONCAT(\'/compras/obrasInsumos?planejamento_id=\',planejamentos.id,\'&insumo_grupos_id=\',insumo_grupos.id,\'&obra_id=\',obras.id) as url';
            }

        if ($this->request()->exibir_por_tarefa) {
            $url_dispensar = 'CONCAT(\'/compras/obrasInsumos/dispensar?planejamento_id=\',planejamentos.id,\'&obra_id=\',obras.id) as url_dispensar';
        } else {
            $url_dispensar = 'CONCAT(\'/compras/obrasInsumos/dispensar?planejamento_id=\',planejamentos.id,\'&insumo_grupos_id=\',insumo_grupos.id,\'&obra_id=\',obras.id) as url_dispensar';
        }
		
		//Atualizacao, sem exibir tarefas marcada
        if (!$this->request()->exibir_por_tarefa) {
            $query = Lembrete::join('insumo_grupos', 'insumo_grupos.id', '=', 'lembretes.insumo_grupo_id')
                ->join('insumos', 'insumos.insumo_grupo_id', '=', 'insumo_grupos.id')
				//->join('carteira_insumos', 'carteira_insumos.insumos_id', '=', 'insumos.id')
                ->join('planejamento_compras', 'planejamento_compras.insumo_id', '=', 'insumos.id')
                ->join('planejamentos', 'planejamentos.id', '=', 'planejamento_compras.planejamento_id')
                ->join('obras', 'obras.id', '=', 'planejamentos.obra_id')
                ->join('obra_users', 'obra_users.obra_id', '=', 'obras.id')
                ->leftJoin('carteira_insumos', 'carteira_insumos.insumo_id', '=', 'insumos.id')
                ->leftJoin('carteiras', 'carteiras.id', '=', 'carteira_insumos.carteira_id')
                ->whereNull('planejamentos.deleted_at')
                ->whereNull('planejamento_compras.deleted_at')
                ->where('lembretes.lembrete_tipo_id', 1)
                ->where('planejamento_compras.dispensado', 0)
                ->where('obra_users.user_id', Auth::user()->id)
                ->select([
                    'lembretes.id',
                    'obras.nome as obra',
                    'planejamentos.tarefa',
                    DB::raw("GROUP_CONCAT(DISTINCT insumo_grupos.nome ORDER BY insumo_grupos.nome ASC SEPARATOR ', ') grupo"),
                    DB::raw($url),
                    DB::raw($url_dispensar),
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
                                AND workflow_alcadas.workflow_tipo_id <= 2
								AND workflow_alcadas.deleted_at IS NULL
                            ) ,
                            0
                        )
                    ) DAY),'%d/%m/%Y') as inicio"),
                        DB::raw("DATEDIFF(
                    (
                    DATE_SUB(planejamentos.data, INTERVAL (
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
                                AND workflow_alcadas.workflow_tipo_id <= 2
								AND workflow_alcadas.deleted_at IS NULL
                            ) ,
                            0
                        )
                    ) DAY)
                ),CURDATE()) as dias"),
                    'carteiras.nome as carteira'
                ]);

                if ($this->request()->get('from ') || $this->request()->get('to')) {
                    if ($this->request()->get('from')) {
                        $from = date('Y-m-d', $this->request()->get('from ') / 1000);
                        $query->where(DB::raw('DATE_SUB(planejamentos.data, INTERVAL (
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
                                                    AND workflow_alcadas.workflow_tipo_id <= 2
								                    AND workflow_alcadas.deleted_at IS NULL
                                                ) ,
                                                0
                                            )
                                        ) DAY)'), '>=', $from);
                    }
                    if ($this->request()->get('to')) {
                        $to = date('Y-m-d', $this->request()->get('to ') / 1000);
                        $query->where(DB::raw('DATE_SUB(planejamentos.data, INTERVAL (
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
                                        AND workflow_alcadas.workflow_tipo_id <= 2
								        AND workflow_alcadas.deleted_at IS NULL
                                    ) ,
                                    0
                                )
                            ) DAY)'), '<=', $to);
                    }
                }

                if ($this->request()->get('obra_id') && $this->request()->get('obra_id') != 'todas') {
                    $query->where('planejamentos.obra_id', $this->request()->get('obra_id'));
                }
                if ($this->request()->get('planejamento_id')) {
                    $query->where('planejamentos.id', $this->request()->get('planejamento_id'));
                }
                if ($this->request()->get('insumo_grupo_id')) {
                    $query->where('insumos.insumo_grupo_id', $this->request()->get('insumo_grupo_id'));
                }
                if ($this->request()->get('carteira_id')) {
                    $query->where('carteiras.id', $this->request()->get('carteira_id'));
                }

                // Busca se existe algum item a  ser comprado desta tarefa
                $query->whereRaw(PlanejamentoCompraRepository::existeItemParaComprarComInsumoGrupo());

            $query->groupBy(['id', 'obra', 'dias', 'tarefa', 'url', 'inicio', 'carteira']);
        } else {

                $query = DB::table(
                    DB::raw('(SELECT tarefa, id, obra, url, url_dispensar, inicio, dias, grupo
                         FROM
                             (SELECT tarefa, id, obra, url, url_dispensar, inicio, dias, grupo
                             FROM
                                (SELECT tarefa, id, obra, url, url_dispensar, inicio, dias, grupo
                                FROM (SELECT
                                        planejamentos.id,
                                        obras.nome AS obra,
                                        planejamentos.tarefa,
                                        ' . $url . ',
                                        ' . $url_dispensar . ',
                                        insumo_grupos.nome as grupo,
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
                                                            AND workflow_alcadas.workflow_tipo_id <= 2
                                                            AND workflow_alcadas.deleted_at IS NULL
                                                        ),
                                                        0
                                                    )
                                                ) DAY
                                            ),
                                            \'%d/%m/%Y\'
                                        ) AS inicio,
                                        DATEDIFF(
                                        (
                                        DATE_SUB(planejamentos.data, INTERVAL (
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
                                                    AND workflow_alcadas.workflow_tipo_id <= 2
                                                    AND workflow_alcadas.deleted_at IS NULL
                                                ) ,
                                                0
                                            )
                                            ) DAY)
                                        ),CURDATE()) as dias
                                    FROM lembretes
                                    INNER JOIN insumo_grupos ON insumo_grupos.id = lembretes.insumo_grupo_id
                                    INNER JOIN insumos ON insumos.insumo_grupo_id = insumo_grupos.id
                                    INNER JOIN planejamento_compras ON planejamento_compras.insumo_id = insumos.id
                                    INNER JOIN planejamentos ON planejamentos.id = planejamento_compras.planejamento_id
                                    INNER JOIN obras ON obras.id = planejamentos.obra_id
                                    INNER JOIN obra_users ON obra_users.obra_id = obras.id
                                    WHERE planejamentos.deleted_at IS NULL
                                    AND lembretes.lembrete_tipo_id = 1
                                    AND planejamento_compras.dispensado = 0
                                    AND obra_users.user_id = ' . Auth::user()->id . '
                                    AND (
                                        SELECT
                                            1
                                        FROM
                                            planejamento_compras plc
                                        JOIN planejamentos P ON P.id = plc.planejamento_id
                                        JOIN orcamentos orc ON orc.insumo_id = plc.insumo_id
                                        AND orc.grupo_id = plc.grupo_id
                                        AND orc.subgrupo1_id = plc.subgrupo1_id
                                        AND orc.subgrupo2_id = plc.subgrupo2_id
                                        AND orc.subgrupo3_id = plc.subgrupo3_id
                                        AND orc.servico_id = plc.servico_id
                                        AND orc.ativo = 1
                                        AND orc.obra_id = P.obra_id
                                        WHERE
                                            (
                                                IFNULL((
                                                    SELECT
                                                        SUM(oci.qtd)
                                                    FROM ordem_de_compra_itens oci
                                                    JOIN ordem_de_compras ocs ON ocs.id = oci.ordem_de_compra_id
                                                    WHERE
                                                        oci.insumo_id = plc.insumo_id
                                                    AND oci.grupo_id = plc.grupo_id
                                                    AND oci.subgrupo1_id = plc.subgrupo1_id
                                                    AND oci.subgrupo2_id = plc.subgrupo2_id
                                                    AND oci.subgrupo3_id = plc.subgrupo3_id
                                                    AND oci.servico_id = plc.servico_id
                                                    AND oci.obra_id = P.obra_id
                                                    AND ocs.oc_status_id NOT IN(1 , 4 , 6)
                                                ),0) < orc.qtd_total
                                                AND
                                                IFNULL((
                                                    SELECT
                                                        SUM(oci.total)
                                                    FROM ordem_de_compra_itens oci
                                                    JOIN ordem_de_compras ocs ON ocs.id = oci.ordem_de_compra_id
                                                    AND ocs.oc_status_id NOT IN(1 , 4 , 6)
                                                    WHERE
                                                        oci.insumo_id = plc.insumo_id
                                                    AND oci.grupo_id = plc.grupo_id
                                                    AND oci.subgrupo1_id = plc.subgrupo1_id
                                                    AND oci.subgrupo2_id = plc.subgrupo2_id
                                                    AND oci.subgrupo3_id = plc.subgrupo3_id
                                                    AND oci.servico_id = plc.servico_id
                                                    AND oci.obra_id = P.obra_id
                                                ),0) = 0
                                            )
                                            AND P.id = planejamentos.id
                                            AND plc.deleted_at IS NULL
                                            AND orc.qtd_total > 0
                                            LIMIT 1
                                    ) IS NOT NULL
                                    AND lembretes.deleted_at IS NULL
                                    ' . ($this->request()->get('obra_id') && $this->request()->get('obra_id') == 'todas' ? ' AND planejamentos.obra_id = ' . $this->request()->get('obra_id') : '') . '
                                    ' . ($this->request()->get('planejamento_id') ? ' AND planejamentos.id = ' . $this->request()->get('planejamento_id') : '') . '
                                    ' . ($this->request()->get('insumo_grupo_id') ? ' AND insumos.insumo_grupo_id = ' . $this->request()->get('insumo_grupo_id') : '') . '
                                    ) as queryInterna
                                    ORDER BY
                                    STR_TO_DATE(inicio,\'%d/%m/%Y\') ASC) as xpto_ordenado) as xpto_agrupado GROUP BY tarefa) as xpto'
                    )
                );
            }

            return $this->applyScopes($query);
        } else {
            return [];
        }
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->ajax('')
            ->parameters([
//                'responsive' => 'true',
                'initComplete' => 'function () {
                    max = this.api().columns().count();
                    this.api().columns().every(function (col) {
                        if(col==0){
                            var column = this;
                            var input = document.createElement("input");
                            $(input).attr(\'title\',\'Para uma faixa utilize hífen(-), ex:01/01/2018-31/01/2018\');
                            $(input).attr(\'placeholder\',\'Filtrar Data...\');
                            $(input).addClass(\'form-control\');
                            $(input).css(\'width\',\'100%\');
                            $(input).appendTo($(column.footer()).empty())
                            .on(\'change\', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }else if(col==1){
                            var column = this;
                            var input = document.createElement("input");
                            $(input).attr(\'id\',\'filtro_obra\');
                            $(input).attr(\'placeholder\',\'Filtrar Obra...\');
                            $(input).addClass(\'form-control\');
                            $(input).css(\'width\',\'100%\');
                            $(input).appendTo($(column.footer()).empty())
                            .on(\'change\', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }else if(col==2){
                            var column = this;
                            var input = document.createElement("input");
                            $(input).attr(\'id\',\'filtro_tarefa\');
                            $(input).attr(\'placeholder\',\'Filtrar Tarefa...\');
                            $(input).addClass(\'form-control\');
                            $(input).css(\'width\',\'100%\');
                            $(input).appendTo($(column.footer()).empty())
                            .on(\'change\', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }else if(col==3){
                            var column = this;
                            var input = document.createElement("input");
                            $(input).attr(\'id\',\'filtro_grupo\');
                            $(input).attr(\'placeholder\',\'Filtrar Grupo...\');
                            $(input).addClass(\'form-control\');
                            $(input).css(\'width\',\'100%\');
                            $(input).appendTo($(column.footer()).empty())
                            .on(\'change\', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }else if(col==4){
                            var column = this;
                            var input = document.createElement("input");
                            $(input).attr(\'id\',\'filtro_carteira\');
                            $(input).attr(\'placeholder\',\'Filtrar Carteira...\');
                            $(input).addClass(\'form-control\');
                            $(input).css(\'width\',\'100%\');
                            $(input).appendTo($(column.footer()).empty())
                            .on(\'change\', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }else if((col+1)<max){
                            var column = this;
                            var input = document.createElement("input");
                            $(input).attr(\'placeholder\',\'Filtrar...\');
                            $(input).addClass(\'form-control\');
                            $(input).css(\'width\',\'100%\');
                            $(input).appendTo($(column.footer()).empty())
                            .on(\'change\', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }
                    });
                }' ,
//                "lengthChange"=> true,
                "pageLength"=> 5,
                'dom' => 'Bfrltip',
                'scrollX' => false,
                'language'=> [
                    "url"=> "/vendor/datatables/Portuguese-Brasil.json"
                ],
                'buttons' => [
                    'reload'
                ]
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            'data' => ['name' => 'inicio', 'data' => 'inicio'],
            'obra' => ['name' => 'obras.nome', 'data' => 'obra'],
            'tarefa' => ['name' => 'planejamentos.tarefa', 'data' => 'tarefa'],
        ];

        $columns['Grupo De Insumo'] = ['name' => 'grupo', 'data' => 'grupo'];
        $columns['Carteira'] = ['name' => 'carteiras.nome', 'data' => 'carteira'];

        $columns['action'] = [
            'title'      => 'Ações',
            'searchable' => false,
            'orderable'  => false,
            'printable'  => false,
            'width'      => '10px',
            'class'      => 'all'
        ];

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'lembreteshomes_' . time();
    }
}