<?php

namespace App\DataTables\Admin;

use App\Models\Levantamento;
use Form;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class LevantamentoMascaraInsumoDataTable extends DataTable
{
    protected $obra = null;
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('action', 'admin.levantamentos.datatables_actions')
            ->editColumn('obra_id',function ($obj){
                return $obj->obra_id ? $obj->obra->nome : '';
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
		
		//'levantamento', 'apropriacao', 'descricao', 'unidadde'
				
        $levantamentos = Levantamento::query()
            ->select([
                'levantamentos.id',
                'obras.nome as obra',
				'levantamentos.apropriacao',
				'levantamentos.insumo',               
				'levantamentos.torre',
				'levantamentos.andar',
				'levantamentos.pavimento',
				'levantamentos.trecho',
				'levantamentos.comodo',
				'levantamentos.parede',
				'levantamentos.trecho_parede',
				'levantamentos.personalizavel',
				'levantamentos.quantidade',
				'levantamentos.perda',
				'levantamentos.created_at'					
            ])
        ->join('obras','obras.id','levantamentos.obra_id');
				
		
        if($this->obra){
            $levantamentos>where('levantamentos.obra_id', $this->obra);
        }

        return $this->applyScopes($levantamentos);
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

    public function porObra($id){
        $this->obra = $id;
        return $this;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {				

        return [
            'levantamento' => ['name' => 'levantamento', 'data' => 'levantamento'], 
			'apropriacao' => ['name' => 'apropriacao', 'data' => 'apropriacao'], 			
			'descricao' => ['name' => 'descricao', 'data' => 'descricao'],
			'unidadde' => ['name' => 'unidadde', 'data' => 'unidadde'],					
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
        return 'levantamentos';
    }
}
