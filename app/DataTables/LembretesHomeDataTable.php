<?php

namespace App\DataTables;

use App\Models\InsumoGrupo;
use App\Models\Lembrete;
use App\Repositories\OrdemDeCompraRepository;
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
            if (!$this->request()->exibir_por_tarefa && !$this->request()->exibir_por_carteira) {
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
        $query = OrdemDeCompraRepository::queryCalendarioLembretes(
                    $this->request()->get('obra_id'),
                    $this->request()->get('planejamento_id'),
                    $this->request()->get('insumo_grupo_id'),
                    $this->request()->get('carteira_id'),
                    $this->request()->exibir_por_tarefa,
                    $this->request()->exibir_por_carteira,
//                    $this->request()->get('from'),
//                    $this->request()->get('to')
                    null,
                    null
                );

        return $this->applyScopes($query);
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
                    "url"=> asset("vendor/datatables/Portuguese-Brasil.json")
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