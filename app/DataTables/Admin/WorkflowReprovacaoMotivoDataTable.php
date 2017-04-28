<?php

namespace App\DataTables\Admin;

use App\Models\WorkflowReprovacaoMotivo;
use Form;
use Yajra\Datatables\Services\DataTable;

class WorkflowReprovacaoMotivoDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('action', 'admin.workflow_reprovacao_motivos.datatables_actions')
            ->editColumn('created_at', function($obj){
                return $obj->created_at->format('d/m/Y');
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(workflow_reprovacao_motivos.created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('tipo', function($obj){
                return $obj->tipo=='' ? 'Todos' : $obj->tipo;
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
        $workflowReprovacaoMotivos = WorkflowReprovacaoMotivo::query()
            ->select([
                'workflow_reprovacao_motivos.id',
                'workflow_reprovacao_motivos.nome',
                'workflow_reprovacao_motivos.created_at',
                'workflow_tipos.nome as tipo',
            ])
        ->leftJoin('workflow_tipos','workflow_tipo_id','workflow_tipos.id');

        return $this->applyScopes($workflowReprovacaoMotivos);
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
                'dom' => 'Bfrtip',
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
            'nome' => ['name' => 'nome', 'data' => 'nome'],
            'tipo' => ['name' => 'workflow_tipos.nome', 'data' => 'tipo'],
            'cadastradoEm' => ['name' => 'created_at', 'data' => 'created_at'],
            'action' => ['title'          => '#', 'printable'      => false, 'width'=>'10%'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'workflowReprovacaoMotivos';
    }
}
