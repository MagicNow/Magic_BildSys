<?php

namespace App\DataTables\Admin;

use App\Models\MascaraInsumo;
use DB;
use Form;
use Yajra\Datatables\Services\DataTable;

class MascaraInsumoDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())  
			->editColumn('action', 'admin.mascara_insumos.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
	
		$mascaraInsumos = MascaraInsumo::query()
            ->select([
                'mascara_insumos.id',
                'levantamento_tipos.nome as nome',
                'mascara_insumos.apropriacao',
				'mascara_insumos.descricao_apropriacao',
				'mascara_insumos.unidade_sigla',				
            ])
        ->join('levantamento_tipos','levantamento_tipos.id','mascara_insumos.levantamento_tipos_id');                

        return $this->applyScopes($mascaraInsumos);
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
            'tipo_levantamento' => ['name' => 'levantamento_tipos.nome', 'data' => 'nome'],
            'apropriação' => ['name' => 'apropriacao', 'data' => 'apropriacao', 'searchable' => false],
            'descrição_apropriação' => ['name' => 'descricao_apropriacao', 'data' => 'descricao_apropriacao', 'searchable' => false],
            'unidade_sigla' => ['name' => 'unidade_sigla', 'data' => 'unidade_sigla', 'searchable' => false],			
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
        return 'mascaraInsumo';
    }
}
