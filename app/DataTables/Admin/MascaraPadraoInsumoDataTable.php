<?php

namespace App\DataTables\Admin;

use App\Models\MascaraPadraoInsumo;
use Form;
use Yajra\Datatables\Services\DataTable;

class MascaraPadraoInsumoDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'admin.mascara_padrao_insumos.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $mascaraPadraoInsumos = MascaraPadraoInsumo::query()
            ->select([
                'mascara_padrao_insumos.id',				
                'mascara_padrao.nome as name',
                'insumos.nome',
				'mascara_padrao_insumos.codigo_estruturado',
				'mascara_padrao_insumos.coeficiente',
				'mascara_padrao_insumos.indireto',
                'insumo_grupos.nome as nome_grupo_insumo'
            ])
        ->join('mascara_padrao_estruturas','mascara_padrao_estruturas.id','=','mascara_padrao_insumos.mascara_padrao_estrutura_id')
        ->join('mascara_padrao','mascara_padrao.id','=','mascara_padrao_estruturas.mascara_padrao_id')
        ->join('insumos','insumos.id','=','mascara_padrao_insumos.insumo_id')
        ->join('insumo_grupos','insumo_grupos.id','insumos.insumo_grupo_id');

        return $this->applyScopes($mascaraPadraoInsumos);
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
            'Máscara Padrão' => ['name' => 'mascara_padrao.nome', 'data' => 'name'],
            'Grupo_de_insumos' => ['name' => 'insumo_grupos.nome', 'data' => 'nome_grupo_insumo'],
            'Insumos' => ['name' => 'insumos.nome', 'data' => 'nome'],
			'Código Estruturado' => ['name' => 'mascara_padrao.codigo_estruturado', 'data' => 'codigo_estruturado'],
			'Coeficiente' => ['name' => 'mascara_padrao.coeficiente', 'data' => 'coeficiente'],
			'Indireto' => ['name' => 'mascara_padrao.indireto', 'data' => 'indireto'],
            'action' => ['title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10%']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'mascaraPadraoInsumos';
    }
}
