<?php

namespace App\DataTables;

use App\Models\Lpu;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LpuDataTable extends DataTable
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
            ->editColumn('created_at', function ($lpu) {
                return $lpu->created_at
                    ? $lpu->created_at->format('m/Y')
                    : '';
            })
            ->editColumn('valor_sugerido', function ($lpu) {
                return $lpu->valor_sugerido;
            })
			->editColumn('valor_contrato', function ($lpu) {
                return float_to_money($lpu->valor_contrato);
            })
			->editColumn('valor_catalogo', function ($lpu) {
                return float_to_money($lpu->valor_catalogo);
            })
            ->editColumn('action', 'lpu.datatables_actions')
			->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     */
    public function query()
    {
        $query = Lpu::query();

        $query->select([
            'lpu.id',
            'lpu.created_at',
			'lpu.valor_sugerido',
			'lpu.valor_contrato',
			'lpu.valor_catalogo',
            'insumos.codigo',
			'insumos.unidade_sigla',
			'insumos.nome as descricao'
        ])
        ->join('insumos', 'insumos.id', 'lpu.insumo_id')
		->join('insumo_grupos', 'insumo_grupos.id', 'insumos.insumo_grupo_id')        
        ->groupBy('lpu.id');

        $request = $this->request();
		
		if($request->regional_id) {
            $query->where('lpu.regional_id', $request->regional_id);
        }
		
		if($request->insumo_grupo_id) {
            $query->where('insumo_grupos.id', $request->insumo_grupo_id);
        }

        if(!is_null($request->days)) {
            $query->whereDate(
                'lpu.created_at',
                '>=',
                Carbon::now()->subDays($request->days)->toDateString()
            );
        }

        if($request->data_start) {
            if(strpos($request->data_start, '/')){
                $query->whereDate(
                    'lpu.created_at',
                    '>=',
                    Carbon::createFromFormat('d/m/Y', $request->data_start)->toDateString()
                );
            }
        }

        if($request->data_end) {
            $query->whereDate(
                'lpu.created_at',
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
                    "url"=> asset("vendor/datatables/Portuguese-Brasil.json")
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
            'codigo' => ['name' => 'codigo', 'data' => 'codigo', 'title' => 'Código Insumo'],            
            'descricao' => ['name' => 'descricao', 'data' => 'descricao', 'title' => 'Descrição'],
			'unidade_sigla' => ['name' => 'unidade_sigla', 'data' => 'unidade_sigla', 'title' => 'UN Medida'],
			'valor_sugerido' => ['name' => 'valor_sugerido', 'data' => 'valor_sugerido', 'title' => 'Valor Sugerido'],
			'valor_contrato' => ['name' => 'valor_unitario', 'data' => 'valor_contrato', 'title' => 'Valor Contrato'],
			'valor_catalogo' => ['name' => 'valor_unitario', 'data' => 'valor_catalogo', 'title' => 'Valor Catálogo'],
			'created_at'        => ['name' => 'created_at', 'data' => 'created_at', 'title' => 'Mês Ref.'],
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
