<?php

namespace App\DataTables;

use App\Models\Qc;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\QcStatus;

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
            ->addColumn('etapa', function($qc) {
                if($qc->isStatus(QcStatus::EM_APROVACAO)) {
                    return 'Workflow';
                }

                if($qc->isStatus(QcStatus::REPROVADO)) {
                    return 'Workflow (Reprovado)';
                }

                if($qc->isStatus(QcStatus::EM_CONCORRENCIA)) {
                    return 'Negociação';
                }

                if($qc->isStatus(QcStatus::CONCORRENCIA_FINALIZADA)) {
                    return 'Mobilização';
                }
            })
            /* ->addColumn('sla', function($qc) { */
            /*     if(!$qc->obra_id) { */
            /*         return 'Não tem'; */
            /*     } */

            /*     $tarefa = $qc->carteira->tarefas->where('obra_id', $qc->obra_id)->first(); */

            /*     return */
            /* }) */
            ->editColumn('created_at', datatables_format_date('created_at'))
            ->editColumn('valor_pre_orcamento', datatables_float_to_money('valor_pre_orcamento'))
            ->editColumn('valor_orcamento_inicial', datatables_float_to_money('valor_orcamento_inicial'))
            ->editColumn('valor_fechamento', datatables_float_to_money('valor_fechamento', 'Valor ainda não informado'))
            ->editColumn('obra_nome', datatables_empty_column('obra_nome', 'Sem obra vinculada'))
            ->editColumn('comprador', datatables_empty_column('comprador', 'Comprador ainda não vinculado'))
            ->editColumn('data_aprovacao', datatables_format_date('data_aprovacao', 'Em andamento'))
            ->editColumn('data_fechamento', datatables_format_date('data_fechamento', 'Em negociação'))
            ->editColumn('numero_contrato_mega', datatables_empty_column('numero_contrato_mega', 'Contrato ainda não vinculado'))
            ->editColumn('fornecedor_nome', datatables_empty_column('fornecedor_nome', 'Fornecedor ainda não vinculado'))
            ->editColumn('status_nome', function($qc) {
                if(!$qc->status) {
                    return 'Sem status';
                }

                return  '<i class="fa fa-circle" style="color:'. $qc->status->cor . '"></i> ' . $qc->status_nome;
            })
            ->editColumn('action', function($qc) {
                return view('qc.datatables_actions', compact('qc'))->render();
            })
            ->filterColumn('obra_id',function($query, $keyword){
              return $query->join('obras', 'obras.id', '=', "qc.obra_id")
                ->where('obras.nome','LIKE','%'.trim($keyword).'%');
            })
            ->filterColumn('carteira_id',function($query, $keyword){
              return $query->join('qc_avulso_carteiras', 'qc_avulso_carteiras.id', '=', "qc.carteira_id")
                ->where('qc_avulso_carteiras.nome','LIKE','%'.trim($keyword).'%');
            })
            ->filterColumn('tipologia_id',function($query, $keyword){
              return $query->join('tipologias', 'tipologias.id', '=', "qc.tipologia_id")
                ->where('tipologias.nome','LIKE','%'.trim($keyword).'%');
            })
            ->filterColumn('status_nome',function($query, $keyword){
              return $query->join('qc_status', 'qc_status.id', '=', "qc.qc_status_id")
                ->where('qc_status.nome','LIKE','%'.trim($keyword).'%');
            })
            ->filterColumn('created_at', function ($query, $keyword) {
              return $query->whereRaw("DATE_FORMAT(qc.created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('valor_orcamento_inicial', function ($query, $keyword) {
              return $query->where("valor_orcamento_inicial", "Like", '%'.str_replace(',', '.', str_replace('.', '', $keyword).'%'));
            })
	          ->filterColumn('valor_pre_orcamento', function ($query, $keyword) {
              return $query->where("valor_pre_orcamento", "Like", '%'.str_replace(',', '.', str_replace('.', '', $keyword).'%'));
            })
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
            'qc_avulso_carteiras.nome as carteira_nome',
            'tipologias.nome as tipologia_nome',
            'fornecedores.nome as fornecedor_nome',
            'qc_status.nome as status_nome',
            'qc_status.cor as status_cor',
            'comprador.name as comprador',
            DB::raw("(
                select created_at from workflow_aprovacoes
                    where aprovavel_type = '" . addslashes(Qc::class) ."'
                    and aprovavel_id = qc.id
                    order by created_at desc
                    limit 1
            ) as data_aprovacao")
        ])
        ->leftJoin('qc_avulso_carteiras', 'qc_avulso_carteiras.id', 'carteira_id')
        ->leftJoin('obras', 'obras.id', 'obra_id')
        ->leftJoin('tipologias', 'tipologias.id', 'tipologia_id')
        ->leftJoin('qc_status', 'qc_status.id', 'qc.qc_status_id')
        ->leftJoin('users as comprador', 'comprador.id', 'qc.comprador_id')
        ->leftJoin('fornecedores', 'fornecedores.id', 'fornecedor_id')
        ->groupBy('qc.id');

        $request = $this->request();

        if($request->obra_id) {
            $query->where('obra_id', $request->obra_id);
        }

        if($request->qc_status_id) {
            $query->where('qc_status_id', $request->qc_status_id);
        }

        if($request->tipologia_id) {
            $query->where('tipologia_id', $request->tipologia_id);
        }

        if($request->carteira_id) {
            $query->where('tipologia_id', $request->tipologia_id);
        }

        if($request->has('comprador_id')) {
            $query->where('comprador_id', $request->comprador_id ? $request->comprador_id : null);
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
            'id' => ['name' => 'id', 'data' => 'id', 'title' => 'Nro Q.C.'],
            'etapa' => ['name' => 'etapa', 'title' => 'Etapa'],
            'comprador_id' => ['name' => 'comprador_id', 'data' => 'comprador', 'title' => 'Comprador'],
            'data_aprovacao' => ['name' => 'data_aprovacao', 'data' => 'data_aprovacao', 'title' => 'Data da aprovação (workflow)'],
			'numero_contrato_mega' => ['name' => 'numero_contrato_mega', 'data' => 'numero_contrato_mega', 'title' => 'Número do contrato (MEGA) '],
			'data_fechamento' => ['name' => 'data_fechamento', 'data' => 'data_fechamento', 'title' => 'Data de fechamento'],
            'fornecedor_id' => ['name' => 'fornecedor_id', 'data' => 'fornecedor_nome', 'title' => 'Fornecedor'],
            'obra' => ['name' => 'obra_id', 'data' => 'obra_nome', 'title' => 'Obra'],
            'carteira_id' => ['name' => 'carteira_id', 'data' => 'carteira_nome', 'title' => 'Carteira'],
            'tipologia_id' => ['name' => 'tipologia_id', 'data' => 'tipologia_nome', 'title' => 'Tipologia'],
            'status_nome' => ['name' => 'status_nome', 'data' => 'status_nome', 'title' => 'Status'],
			'created_at' => ['name' => 'created_at', 'data' => 'created_at', 'title' => 'Data'],
			'valor_fechamento' => ['name' => 'valor_fechamento', 'data' => 'valor_fechamento', 'title' => 'Valor fechamento'],
            'valor_orcamento_inicial' => ['name' => 'valor_orcamento_inicial', 'data' => 'valor_orcamento_inicial', 'title' => 'Valor Orçamento Inicial'],
            'valor_pre_orcamento' => ['name' => 'valor_pre_orcamento', 'data' => 'valor_pre_orcamento', 'title' => 'Valor Pré-Orçamento'],
			'action' => ['name' => 'Ações', 'title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'15%', 'class' => 'all']
		];
    }
}
