<?php

namespace App\DataTables\Admin;

use App\Models\Planejamento;
use Form;
use Yajra\Datatables\Services\DataTable;

class PlanejamentoDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'admin.planejamentos.datatables_actions')
            ->editColumn('obra_id',function ($obj){
                return $obj->obra_id ? $obj->obra->nome : '';
            })
            ->editColumn('data',function ($obj){
                return $obj->data ? with(new\Carbon\Carbon($obj->data))->format('d/m/Y') : '';
            })
            ->editColumn('data_fim',function ($obj){
                return $obj->data ? with(new\Carbon\Carbon($obj->data))->format('d/m/Y') : '';
            })
            ->editColumn('prazo',function ($obj){
                return $obj->prazo ? $obj->prazo . ' dias ' : '';
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
        $planejamentos = Planejamento::query();

        return $this->applyScopes($planejamentos);
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
            ->addAction(['width' => '10%'])
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
            'obra' => ['name' => 'obra_id', 'data' => 'obra_id'],
            'tarefa' => ['name' => 'tarefa', 'data' => 'tarefa'],
            'data_início' => ['name' => 'data', 'data' => 'data'],
            'prazo' => ['name' => 'prazo', 'data' => 'prazo'],
            'data_fim' => ['name' => 'data_fim', 'data' => 'data_fim'],
            'resumo' => ['name' => 'resumo', 'data' => 'resumo']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'planejamentos';
    }
}
