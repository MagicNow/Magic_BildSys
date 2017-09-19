<?php

namespace App\DataTables;

use App\Models\CarteirasSla;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CarteirasSlaDataTable extends DataTable
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
            ->editColumn('created_at', function ($carteiras_sla) {
                return $carteiras_sla->created_at
                    ? $carteiras_sla->created_at->format('d/m/Y')
                    : '';
            })
            ->editColumn('action', 'carteiras_sla.datatables_actions')
			->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     */
    public function query()
    {
        $query = CarteirasSla::query();

        $query->select([
            'carteiras_sla.id',
            'carteiras_sla.created_at',
			'carteiras_sla.obra_inicio',
			'carteiras_sla.obra_subir_qc',
			'carteiras_sla.obra_aprovar_qc',
            'carteiras_sla.obra_finalizar_qc',
            'carteiras_sla.inicio_atividade',
        ])
        ->join('carteiras', 'carteiras.id', 'carteiras_sla.carteira_id')
        ->join('obras', 'obras.id', 'carteiras_sla.obra_id')
        ->groupBy('carteiras_sla.id');

        $request = $this->request();

        if(!is_null($request->days)) {
            $query->whereDate(
                'carteiras_sla.created_at',
                '>=',
                Carbon::now()->subDays($request->days)->toDateString()
            );
        }

        if($request->data_start) {
            if(strpos($request->data_start, '/')){
                $query->whereDate(
                    'carteiras_sla.created_at',
                    '>=',
                    Carbon::createFromFormat('d/m/Y', $request->data_start)->toDateString()
                );
            }
        }

        if($request->data_end) {
            $query->whereDate(
                'carteiras_sla.created_at',
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
            'obra' => ['name' => 'obra', 'data' => 'obras.nome', 'title' => 'Obra'],            
            'carteira' => ['name' => 'carteira', 'data' => 'carteira', 'title' => 'Carteira'],
			'obra_inicio' => ['name' => 'obra_inicio', 'data' => 'obra_inicio', 'title' => 'Data início'],
			'obra_subir_qc' => ['name' => 'obra_subir_qc', 'data' => 'obra_subir_qc', 'title' => 'Data subir QC'],
			'obra_aprovar_qc' => ['name' => 'obra_aprovar_qc', 'data' => 'obra_aprovar_qc', 'title' => 'Data aprovar QC'],
			'obra_finalizar_qc' => ['name' => 'obra_finalizar_qc', 'data' => 'obra_finalizar_qc', 'title' => 'Data finalizar QC'],
            'inicio_atividade' => ['name' => 'inicio_atividade', 'data' => 'inicio_atividade', 'title' => 'Data inicio atividades'],
			'created_at'        => ['name' => 'created_at', 'data' => 'created_at', 'title' => 'Data'],
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
        return 'lpu_' . time();
    }


}
