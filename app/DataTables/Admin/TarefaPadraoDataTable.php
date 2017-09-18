<?php

namespace App\DataTables\Admin;

use App\Models\TarefaPadrao;
use DB;
use Form;
use Yajra\Datatables\Services\DataTable;

class TarefaPadraoDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('action', 'admin.tarefa_padrao.datatables_actions')
            ->editColumn('created_at', function($obj){
                return $obj->created_at ? with(new\Carbon\Carbon($obj->created_at))->format('d/m/Y H:i') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(tarefa_padrao.created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
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
			
		$tarefa_padrao = TarefaPadrao::query()->select([
                'tarefa_padrao.id',
                'tarefa_padrao.nome',
                DB::raw("(IF(resumo=1,'Sim','Não')) as resumo"),
				DB::raw("(IF(critica=1,'Sim','Não')) as critica"),
               'tarefa_padrao.torre',
               'tarefa_padrao.pavimento',
			   'tarefa_padrao.created_at',
            ]);

        return $this->applyScopes($tarefa_padrao);
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
	
    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'nome' => ['name' => 'nome', 'data' => 'nome'],
			'resumo' => ['name' => 'resumo', 'data' => 'resumo'],
			'critica' => ['name' => 'critica', 'data' => 'critica'],			
			'torre' => ['name' => 'torre', 'data' => 'torre'],
			'pavimento' => ['name' => 'pavimento', 'data' => 'pavimento'],			
            'cadastradaEm' => ['name' => 'created_at', 'data' => 'created_at'],
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
        return 'mascara_padrao';
    }
}
