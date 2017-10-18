<?php

namespace App\DataTables;

use App\Models\QcAvulsoCarteira;
use Form;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class CarteiraQcAvulsoFarolDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('action', 'qc.datatable_farol_actions')
            ->editColumn('created_at', function($obj){
                return $obj->created_at ? with(new\Carbon\Carbon($obj->created_at))->format('d/m/Y H:i') : '';
            })
            ->editColumn('farol_start', function($obj){
                // VERDE ( ATÉ 20% ACIMA DA DATA DE INÍCIO)
                // AMARELO (ATÉ 60% ACIMA DA DATA DE INÍCIO)
                // VERMELHO (ATÉ A DATA LIMITE)
                // PRETO (ACIMA DO LIMITE)
                $percentual = (($obj->sla_start + $obj->farol_start)*100)/$obj->sla_start;
                if($percentual > -20 ){
                    $classe = 'text-success';
                }elseif($percentual > -60 && $percentual < -20 ){
                    $classe = 'text-warning';
                }elseif($percentual > -100 && $percentual < -60 ){
                    $classe = 'text-danger';
                }elseif($percentual < -100 ){
                    $classe = '';
                }

                return '<div class="text-center"><i class="fa fa-circle ' .$classe.'" aria-hidden="true"></i></div>';
            })
//            ->filterColumn('created_at', function ($query, $keyword) {
//                $query->whereRaw("DATE_FORMAT(qc_avulso_carteiras.created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
//            })
//            ->filterColumn('compradores', function($query, $keyword){
//                $query->whereRaw('(
//                    SELECT
//                        GROUP_CONCAT(users.name SEPARATOR ", ")
//                    FROM
//                        qc_avulso_carteira_users
//                        JOIN users ON qc_avulso_carteira_users.user_id = users.id
//                    WHERE
//                        qc_avulso_carteira_id = qc_avulso_carteiras.id
//                ) LIKE ?', ['%'.$keyword.'%']);
//            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carteiras = QcAvulsoCarteira::query()
            ->select([
                'qc_avulso_carteiras.nome',
                'qc.id as qc_id',
                'obras.nome as obra',
                'planejamentos.tarefa',
                'planejamentos.data',
                'qc_avulso_carteiras.sla_start',
                'qc_avulso_carteiras.sla_negociacao',
                'qc_avulso_carteiras.sla_mobilizacao',
                DB::raw('(SELECT
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
                            AND workflow_alcadas.workflow_tipo_id = 7
                            AND workflow_alcadas.deleted_at IS NULL
                            AND workflow_alcadas.ordem = 1) dias_prazo_alcada_1'),
                DB::raw('(SELECT
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
                            AND workflow_alcadas.workflow_tipo_id = 7
                            AND workflow_alcadas.deleted_at IS NULL
                            AND workflow_alcadas.ordem = 2) dias_prazo_alcada_2'),
                DB::raw('(SELECT
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
                            AND workflow_alcadas.workflow_tipo_id = 7
                            AND workflow_alcadas.deleted_at IS NULL
                            AND workflow_alcadas.ordem = 3) dias_prazo_alcada_3'),
                DB::raw("
                (
                    DATEDIFF(
                        SUBDATE(
                            planejamentos.`data` , 
                            INTERVAL(
                                qc_avulso_carteiras.sla_start + 
                                qc_avulso_carteiras.sla_negociacao + 
                                qc_avulso_carteiras.sla_mobilizacao
                                + IFNULL(
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
                                            AND workflow_alcadas.workflow_tipo_id = 7
                                            AND workflow_alcadas.deleted_at IS NULL
                                    ) ,
                                    0
                                )
                            )
                            DAY
                        ) ,
                        IFNULL(qc.created_at, CURDATE())
                    ) 
                ) as farol_start"),
                DB::raw("
                (
                    DATEDIFF(
                        SUBDATE(
                            planejamentos.`data` , 
                            INTERVAL(
                                qc_avulso_carteiras.sla_negociacao + 
                                qc_avulso_carteiras.sla_mobilizacao
                                + IFNULL(
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
                                            AND workflow_alcadas.workflow_tipo_id = 7
                                            AND workflow_alcadas.deleted_at IS NULL
                                            AND workflow_alcadas.ordem >= 1
                                    ) ,
                                    0
                                )
                            )
                            DAY
                        ) ,
                        IFNULL( 
                                (
                                     SELECT MAX(wap.created_at) 
                                     FROM workflow_aprovacoes wap 
                                     JOIN workflow_alcadas wa ON wap.workflow_alcada_id = wa.id 
                                        WHERE
                                            EXISTS (
                                                SELECT
                                                    1
                                                FROM
                                                    workflow_usuarios
                                                WHERE
                                                    workflow_alcada_id = wa.id
                                            )
                                            AND wa.workflow_tipo_id = 7
                                            AND wa.deleted_at IS NULL
                                            AND wa.ordem = 1
                                            AND wap.aprovavel_id = qc.id
                                            AND wap.aprovavel_type = 'App\\Models\\Qc'
                                            AND (
                                                SELECT COUNT(1) 
                                                FROM workflow_aprovacoes wapQTD
                                                WHERE wapQTD.aprovavel_id = qc.id
                                                    AND wapQTD.aprovavel_type = 'App\\Models\\Qc'
                                                    AND wapQTD.created_at > qc.updated_at
                                            ) >= (
                                                SELECT
                                                    COUNT(1)
                                                FROM
                                                    workflow_usuarios
                                                WHERE
                                                    workflow_alcada_id = wa.id
                                            )
                                ), 
                                SUBDATE(CURDATE(), INTERVAL (qc_avulso_carteiras.sla_start) DAY ) 
                        )

                    ) 
                ) as farol_workflow_1"),
                DB::raw("
                (
                    DATEDIFF(
                        SUBDATE(
                            planejamentos.`data` , 
                            INTERVAL(
                                qc_avulso_carteiras.sla_negociacao + 
                                qc_avulso_carteiras.sla_mobilizacao
                                + IFNULL(
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
                                            AND workflow_alcadas.workflow_tipo_id = 7
                                            AND workflow_alcadas.deleted_at IS NULL
                                            AND workflow_alcadas.ordem >= 2
                                    ) ,
                                    0
                                )
                            )
                            DAY
                        ) ,
                        IFNULL(qc.created_at, CURDATE())
                    ) 
                ) as farol_workflow_2"),
                DB::raw("
                (
                    DATEDIFF(
                        SUBDATE(
                            planejamentos.`data` , 
                            INTERVAL(
                                qc_avulso_carteiras.sla_negociacao + 
                                qc_avulso_carteiras.sla_mobilizacao
                                + IFNULL(
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
                                            AND workflow_alcadas.workflow_tipo_id = 7
                                            AND workflow_alcadas.deleted_at IS NULL
                                            AND workflow_alcadas.ordem >= 3
                                    ) ,
                                    0
                                )
                            )
                            DAY
                        ) ,
                        IFNULL(qc.created_at, CURDATE())
                    ) 
                ) as farol_workflow_3"),
                DB::raw("
                (
                    DATEDIFF(
                        SUBDATE(
                            planejamentos.`data` , 
                            INTERVAL(
                                qc_avulso_carteiras.sla_negociacao +
                                qc_avulso_carteiras.sla_mobilizacao
                            )
                            DAY
                        ) ,
                        IFNULL(qc.created_at, CURDATE())
                    ) 
                ) as farol_negociacao"),
                DB::raw("
                (
                    DATEDIFF(
                        SUBDATE(
                            planejamentos.`data` , 
                            INTERVAL(
                                qc_avulso_carteiras.sla_start + 
                                qc_avulso_carteiras.sla_negociacao + 
                                qc_avulso_carteiras.sla_mobilizacao
                                + IFNULL(
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
                                            AND workflow_alcadas.workflow_tipo_id = 7
                                            AND workflow_alcadas.deleted_at IS NULL
                                    ) ,
                                    0
                                )
                            )
                            DAY
                        ) ,
                        IFNULL(qc.created_at, CURDATE())
                    ) 
                ) as farol_mobilizacao"),
//                DB::raw('(
//                    SELECT
//                        GROUP_CONCAT(users.name SEPARATOR ", ")
//                    FROM
//                        qc_avulso_carteira_users
//                        JOIN users ON qc_avulso_carteira_users.user_id = users.id
//                    WHERE
//                        qc_avulso_carteira_id = qc_avulso_carteiras.id
//                ) as compradores')
                'qc_avulso_carteiras.created_at',
            ])
            ->join('qc_avulso_carteira_planejamento','qc_avulso_carteira_planejamento.qc_avulso_carteira_id','qc_avulso_carteiras.id')
            ->join('planejamentos','planejamentos.id','qc_avulso_carteira_planejamento.planejamento_id')
            ->join('obras','obras.id','planejamentos.obra_id')
            ->leftJoin('qc','qc.carteira_id','qc_avulso_carteiras.id')
        ;

        return $this->applyScopes($carteiras);
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
            // ->addAction(['width' => '10%'])
            ->ajax('')
            ->parameters([
                'responsive' => 'true',
                'initComplete' => 'function () {
                    max = this.api().columns().count();
                    this.api().columns().every(function (col) {
                        if((col+1)<max){
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
                'dom' => 'Bfrltip',
                'scrollX' => false,
                'language'=> [
                    "url"=> asset("vendor/datatables/Portuguese-Brasil.json")
                ],
                'buttons' => [
                    'print',
                    'reset',
                    'reload',
                    [
                        'extend'  => 'collection',
                        'text'    => '<i class="fa fa-download"></i> Export',
                        'buttons' => [
                            'csv',
                            'excel',
                            'pdf',
                        ],
                    ],
                    'colvis'
                ]
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'Q&period;C&period;' => ['name' => 'qc.id', 'data' => 'qc_id', 'width'=>'5%'],
            'nome' => ['name' => 'nome', 'data' => 'nome'],
            'obra' => ['name' => 'obras.nome', 'data' => 'obra'],
            'tarefa' => ['name' => 'tarefa', 'data' => 'tarefa'],
            'tarefa_data' => ['name' => 'data', 'data' => 'data'],
//            'compradores' => ['name' => 'compradores', 'data' => 'compradores'],
            'início' => ['name' => 'farol_start', 'data' => 'farol_start', 'width'=>'6%'],
            'WorkFlowAlçada_1' => ['name' => 'farol_workflow_1', 'data' => 'farol_workflow_1', 'width'=>'6%'],
            'WorkFlowAlçada_2' => ['name' => 'farol_workflow_2', 'data' => 'farol_workflow_2', 'width'=>'6%'],
            'WorkFlowAlçada_3' => ['name' => 'farol_workflow_3', 'data' => 'farol_workflow_3', 'width'=>'6%'],
            'Negociação' => ['name' => 'farol_negociacao', 'data' => 'farol_negociacao', 'width'=>'6%'],
            'Mobilização' => ['name' => 'farol_mobilizacao', 'data' => 'farol_mobilizacao', 'width'=>'6%'],
            'action' => ['title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'5%']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carteiras';
    }
}
