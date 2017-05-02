<?php

namespace App\DataTables\Admin;

use App\Models\Contrato;
use Form;
use Yajra\Datatables\Services\DataTable;

class ContratosDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('arquivo',function ($obj){
                if($obj->arquivo){
                    return '<a href="'.$obj->arquivo.'" download>Baixar arquivo</a>';
                }else{
                    return '';
                }
            })
            ->editColumn('data',function ($obj){
                return $obj->data ? with(new\Carbon\Carbon($obj->data))->format('d/m/Y') : '';
            })
            ->editColumn('obra_id',function ($obj){
                return $obj->obra->nome;
            })
            ->editColumn('action', 'admin.contratos.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $contratos = Contrato::query();

        return $this->applyScopes($contratos);
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
            'data' => ['name' => 'data', 'data' => 'data'],
            'valor' => ['name' => 'valor', 'data' => 'valor'],
            'arquivo' => ['name' => 'arquivo', 'data' => 'arquivo'],
            'action' => ['title'          => '#', 'printable'      => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'contratos';
    }
}
