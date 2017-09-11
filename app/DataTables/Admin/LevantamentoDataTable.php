<?php

namespace App\DataTables\Admin;

use App\Models\Levantamento;
use Form;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class LevantamentoDataTable extends DataTable
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
		//->join('insumos','insumos.codigo','levantamentos.insumo');		
		
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
            'obra' => ['name' => 'obras.nome', 'data' => 'obra'], 
			'apropriacao' => ['name' => 'apropriacao', 'data' => 'apropriacao'], 			
			'insumo' => ['name' => 'insumo', 'data' => 'insumo'],
			'torre' => ['name' => 'torre', 'data' => 'torre'],
			'andar' => ['name' => 'andar', 'data' => 'andar'],
			'pavimento' => ['name' => 'pavimento', 'data' => 'pavimento'],
			'trecho' => ['name' => 'trecho', 'data' => 'trecho'],
			'comodo' => ['name' => 'comodo', 'data' => 'comodo'],
			'parede' => ['critica' => 'parede', 'data' => 'parede'],
            'trecho_parede' => ['name' => 'trecho_parede', 'data' => 'trecho_parede'],
            'personalizavel' => ['name' => 'personalizavel', 'data' => 'personalizavel'],
			'quantidade' => ['name' => 'quantidade', 'data' => 'quantidade'],
			'perda' => ['name' => 'perda', 'data' => 'perda'],			
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
