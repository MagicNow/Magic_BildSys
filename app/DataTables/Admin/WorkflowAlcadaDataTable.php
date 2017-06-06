<?php

namespace App\DataTables\Admin;

use App\Models\WorkflowAlcada;
use Form;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class WorkflowAlcadaDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('action', 'admin.workflow_alcadas.datatables_actions')
            ->editColumn('valor_minimo', function($obj) {
                return float_to_money($obj->valor_minimo);
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $workflowAlcadas = WorkflowAlcada::query()
            ->select([
                'workflow_tipos.nome as tipo',
                'workflow_alcadas.id',
                'workflow_alcadas.nome',
                'workflow_alcadas.ordem',
                'workflow_alcadas.valor_minimo',
                DB::raw('(SELECT COUNT(1) FROM workflow_usuarios WU WHERE WU.workflow_alcada_id = workflow_alcadas.id) usuarios')
            ])
            ->join('workflow_tipos','workflow_tipos.id','workflow_tipo_id');

        return $this->applyScopes($workflowAlcadas);
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
                'responsive' => 'true',
                 'initComplete' => 'function () {
                    max = this.api().columns().count();
                    this.api().columns().every(function (col) {
                        if((col+3)<max){
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
                    "url"=> "/vendor/datatables/Portuguese-Brasil.json"
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
            'tipo' => ['name' => 'workflow_tipos.nome', 'data' => 'tipo'],
            'nome' => ['name' => 'nome', 'data' => 'nome'],
            'ordem' => ['name' => 'ordem', 'data' => 'ordem', 'searchable' => false],
            'valor_minimo' => ['name' => 'valor_minimo', 'data' => 'valor_minimo', 'searchable' => false],
            'usuarios' => ['name' => 'usuarios', 'data' => 'usuarios', 'searchable' => false],
            'action' => ['title' => '#', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10%']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'workflowAlcadas';
    }
}
