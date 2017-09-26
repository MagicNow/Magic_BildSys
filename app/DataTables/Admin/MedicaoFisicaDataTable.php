<?php

namespace App\DataTables\Admin;

use App\Models\MedicaoFisica;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MedicaoFisicaDataTable extends DataTable
{

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())	
			->editColumn('periodo_inicio', function ($medicaoFisica) {
                return $medicaoFisica->periodo_inicio
                    ? $medicaoFisica->periodo_inicio->format('d/m/Y')
                    : '';
            })
			->editColumn('periodo_termino', function ($medicaoFisica) {
                return $medicaoFisica->periodo_termino
                    ? $medicaoFisica->periodo_termino->format('d/m/Y')
                    : '';
            })
            ->editColumn('valor_medido', function ($medicaoFisica) {
                return $medicaoFisica->valor_medido;
            })
			->editColumn('created_at', function ($medicaoFisica) {
                return $medicaoFisica->created_at
                    ? $medicaoFisica->created_at->format('d/m/Y h:i:s')
                    : '';
            })            
            ->editColumn('action', 'admin.medicao_fisicas.datatables_actions')
			->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     */
    public function query()
    {
        $query = MedicaoFisica::query();
		
		//somar os valores medidos
		
        $query->select([
            'medicao_fisicas.id',            				
            'obras.nome as obra',
			'medicao_fisicas.tarefa',
			'medicao_fisicas.periodo_inicio',
			'medicao_fisicas.periodo_termino',
			'medicao_fisicas.valor_medido',
			'medicao_fisicas.created_at',			
        ])
		->join('obras', 'obras.id', 'medicao_fisicas.obra_id') 
        ->groupBy('medicao_fisicas.tarefa');

        $request = $this->request();
		
		if($request->obra_id) {
            $query->where('medicao_fisicas.obra_id', $request->obra_id);
        }

        if(!is_null($request->days)) {
            $query->whereDate(
                'medicao_fisicas.created_at',
                '>=',
                Carbon::now()->subDays($request->days)->toDateString()
            );
        }

        if($request->data_start) {
            if(strpos($request->data_start, '/')){
                $query->whereDate(
                    'medicao_fisicas.created_at',
                    '>=',
                    Carbon::createFromFormat('d/m/Y', $request->data_start)->toDateString()
                );
            }
        }

        if($request->data_end) {
            $query->whereDate(
                'medicao_fisicas.created_at',
                '<=',
                Carbon::createFromFormat('d/m/Y', $request->data_end)->toDateString()
            );
        }			
	
        return $this->applyScopes($query);
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
                'responsive'=> 'true',
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
                'dom' => 'Blfrtip',
                'scrollX' => false,
                'language'=> [
                    "url"=> "/vendor/datatables/Portuguese-Brasil.json"
                ],
                // Ordena para que inicialmente carregue os mais novos
                'order' => [
                    0,
                    'desc'
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
    protected function getColumns()
    {
        return [            
            'Obra' => ['name' => 'obra', 'data' => 'obra', 'title' => 'Obra'],
			'Tarefa' => ['name' => 'tarefa', 'data' => 'tarefa', 'title' => 'Tarefa'],
			'periodo_inicio' => ['name' => 'periodo_inicio', 'data' => 'periodo_inicio', 'title' => 'Data Início'],			
			'periodo_termino' => ['name' => 'periodo_termino', 'data' => 'periodo_termino', 'title' => 'Data Termino'],
			'valor_medido' => ['name' => 'valor_medido', 'data' => 'valor_medido', 'title' => 'Valor Total Medido'],
			'created_at'        => ['name' => 'created_at', 'data' => 'created_at', 'title' => 'Medido em'],
			'action' => ['title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10%']
		];
    }

    /**
     * Get filename for export
     *
     * @return string
     */
    protected function filename()
    {
        return 'medicaoFisica_' . time();
    }


}
