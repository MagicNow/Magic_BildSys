<?php

namespace App\DataTables\Admin;

use App\Models\CarteiraInsumo;
use App\Models\Insumo;
use Form;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class SemPlanejamentoInsumoDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'admin.planejamento_orcamentos.datatables_actions')
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
            ->join('orcamentos','orcamentos.insumo_id','=','insumos.id')
            ->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('planejamento_compras')
                ->join('planejamentos', 'planejamentos.id', '=', 'planejamento_compras.planejamento_id')
                ->where('orcamentos.insumo_id', '=', 'planejamento_compras.insumo_id')
                ->where('planejamentos.obra_id', '=', 'orcamentos.obra_id')
                ->where('orcamentos.grupo_id', '=', 'planejamento_compras.grupo_id')
                ->where('orcamentos.subgrupo1_id', '=', 'planejamento_compras.subgrupo1_id')
                ->where('orcamentos.subgrupo2_id', '=', 'planejamento_compras.subgrupo2_id')
                ->where('orcamentos.subgrupo3_id', '=', 'planejamento_compras.subgrupo3_id')
                ->where('orcamentos.servico_id', '=', 'planejamento_compras.servico_id')
                ->whereNull('planejamento_compras.deleted_at');
        });

        $insumos->where('orcamentos.obra_id',intval($this->request()->get('obra')));

//        echo $insumos->toSql();
//        die();
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
