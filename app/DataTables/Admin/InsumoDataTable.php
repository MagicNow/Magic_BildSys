<?php

namespace App\DataTables\Admin;

use App\Models\Insumo;
use Form;
use Yajra\Datatables\Services\DataTable;

class InsumoDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $query = $this->query();
        return $this->datatables
            ->eloquent($this->query()
                ->select([
                    'insumos.id',
                    'insumos.nome',
                    'insumos.codigo',
                    'insumos.unidade_sigla',
                    'insumo_grupos.nome as grupo',
                ])
                ->join('insumo_grupos','insumo_grupos.id','=','insumos.insumo_grupo_id'))
            ->editColumn('action', 'admin.insumos.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $insumos = Insumo::query();

        return $this->applyScopes($insumos);
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
            'nome' => ['name' => 'nome', 'data' => 'nome'],
            'unidade' => ['name' => 'unidade_sigla', 'data' => 'unidade_sigla'],
            'codigo' => ['name' => 'codigo', 'data' => 'codigo'],
            'grupo' => ['name' => 'insumo_grupos.nome', 'data' => 'grupo'],
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
        return 'insumos';
    }
}
