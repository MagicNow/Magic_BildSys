<?php

namespace App\DataTables;

use App\Models\Qc;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QcDataTable extends DataTable
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
            ->editColumn('created_at', datatables_format_date('created_at'))
            ->editColumn('valor_pre_orcamento', datatables_float_to_money('valor_pre_orcamento'))
            ->editColumn('valor_orcamento_inicial', datatables_float_to_money('valor_orcamento_inicial'))
            ->editColumn('status_nome', function($qc) {
                if(!$qc->status) {
                    return 'Sem status';
                }

                return  '<i class="fa fa-circle" style="color:'. $qc->status->cor . '"></i> ' . $qc->status_nome;
            })
            ->editColumn('action', 'qc.datatables_actions')
			->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     */
    public function query()
    {
        $query = Qc::query();

        $query->select([
            'qc.*',
            'obras.nome as obra_nome',
            'carteiras.nome as carteira_nome',
            'tipologias.nome as tipologia_nome',
            'qc_status.nome as status_nome',
            'qc_status.cor as status_cor',
        ])
        ->leftJoin('carteiras', 'carteiras.id', 'carteira_id')
        ->leftJoin('obras', 'obras.id', 'obra_id')
        ->leftJoin('tipologias', 'tipologias.id', 'tipologia_id')
        ->leftJoin('qc_status', 'qc_status.id', 'qc.qc_status_id')
        ->groupBy('qc.id');

        $request = $this->request();

        if(!is_null($request->days)) {
            $query->whereDate(
                'qc.created_at',
                '>=',
                Carbon::now()->subDays($request->days)->toDateString()
            );
        }

        if($request->data_start) {
            if(strpos($request->data_start, '/')){
                $query->whereDate(
                    'qc.created_at',
                    '>=',
                    Carbon::createFromFormat('d/m/Y', $request->data_start)->toDateString()
                );
            }
        }

        if($request->data_end) {
            $query->whereDate(
                'qc.created_at',
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
            'tipologia_id' => ['name' => 'tipologia_id', 'data' => 'tipologia_nome', 'title' => 'Tipologia'],
            'status_nome' => ['name' => 'status_nome', 'data' => 'status_nome', 'title' => 'Status'],
            'carteira_id' => ['name' => 'carteira_id', 'data' => 'carteira_nome', 'title' => 'Carteira'],
            'descricao' => ['name' => 'descricao', 'data' => 'descricao', 'title' => 'Descrição do serviço'],
            'obra' => ['name' => 'obra_id', 'data' => 'obra_nome', 'title' => 'Obra'],
            'valor_pre_orcamento' => ['name' => 'valor_pre_orcamento', 'data' => 'valor_pre_orcamento', 'title' => 'Valor Pré-Orçamento'],
            'valor_orcamento_inicial' => ['name' => 'valor_orcamento_inicial', 'data' => 'valor_orcamento_inicial', 'title' => 'Valor Orçamento Inicial'],
			'created_at' => ['name' => 'created_at', 'data' => 'created_at', 'title' => 'Data'],
			'action' => ['name' => 'Ações', 'title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'15%', 'class' => 'all']
		];
    }
}
