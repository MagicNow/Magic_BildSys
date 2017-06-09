<?php

namespace App\DataTables\Admin;

use App\Models\CompradorInsumo;
use Form;
use Yajra\Datatables\Services\DataTable;

class CompradorInsumoDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'admin.comprador_insumos.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $compradorInsumos = CompradorInsumo::query()
            ->select([
                'comprador_insumos.id',
                'users.name',
                'insumos.nome',
                'insumo_grupos.nome as nome_grupo_insumo'
            ])
        ->join('users','users.id','=','comprador_insumos.user_id')
        ->join('insumos','insumos.id','=','comprador_insumos.insumo_id')
        ->join('insumo_grupos','insumo_grupos.id','insumos.insumo_grupo_id');

        return $this->applyScopes($compradorInsumos);
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
            'Comprador' => ['name' => 'users.name', 'data' => 'name'],
            'Grupo_de_insumos' => ['name' => 'insumo_grupos.nome', 'data' => 'nome_grupo_insumo'],
            'Insumos' => ['name' => 'insumos.nome', 'data' => 'nome'],
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
        return 'compradorInsumos';
    }
}
