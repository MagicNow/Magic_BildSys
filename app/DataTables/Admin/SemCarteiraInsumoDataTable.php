<?php

namespace App\DataTables\Admin;

use App\Models\CarteiraInsumo;
use App\Models\Insumo;
use Form;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class SemCarteiraInsumoDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'admin.carteira_insumos.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {		

        $insumos = Insumo::select([
            'insumos.id',
            'insumos.nome',
            'insumo_grupos.nome as nome_grupo_insumo'
        ])
            ->join('insumo_grupos','insumo_grupos.id','=','insumos.insumo_grupo_id')
            ->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('carteira_insumos')
                ->whereRaw('carteira_insumos.insumo_id = insumos.id');
        });

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
            ->ajax('')
            ->parameters([
                'responsive' => 'true',
                 'initComplete' => 'function () {
                    max = this.api().columns().count();
                    this.api().columns().every(function (col) {
                        if(col==0){
                            var column = this;
                            var input = document.createElement("input");
                            $(input).attr(\'title\',\'Para uma faixa utilize hífen(-), ex:01/01/2018-31/01/2018\');
                            $(input).attr(\'placeholder\',\'Filtrar Insumos...\');
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
                            $(input).attr(\'placeholder\',\'Filtrar Grupo de Insumos...\');
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
            'Insumos' => ['name' => 'insumos.nome', 'data' => 'nome'],
			'Grupo_de_insumos' => ['name' => 'insumo_grupos.nome', 'data' => 'nome_grupo_insumo']
			/*'action' => ['title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10%']*/
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'semCarteiraInsumos';
    }
}
