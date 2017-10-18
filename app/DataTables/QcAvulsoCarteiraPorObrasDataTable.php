<?php

namespace App\DataTables;

use App\Models\QcAvulsoCarteira;
use Form;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;
use Carbon\Carbon;

class QcAvulsoCarteiraPorObrasDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('farol', function($qc) {
                $data_inicial = new Carbon($qc->data_inicial);

                return ' <i class="fa fa-circle fa-lg" style="color:'
                    . (with(new Carbon($qc->data_start))->lt($data_inicial) ? 'red' : 'green')
                    . '"></i>';
            })
            ->editColumn('action', 'admin.qc_avulso_carteiras.lista_por_obras_datatables_actions')
            ->editColumn('created_at', function($obj){
                return $obj->created_at ? with(new\Carbon\Carbon($obj->created_at))->format('d/m/Y H:i') : '';
            })
            ->editColumn('data_start', datatables_format_date('data_start'))
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(qc_avulso_carteiras.created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('compradores', function($query, $keyword){
                $query->whereRaw('(
                    SELECT
                        GROUP_CONCAT(users.name SEPARATOR ", ")
                    FROM
                        qc_avulso_carteira_users
                        JOIN users ON qc_avulso_carteira_users.user_id = users.id
                    WHERE
                        qc_avulso_carteira_id = qc_avulso_carteiras.id
                ) LIKE ?', ['%'.$keyword.'%']);
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
        $carteiras = QcAvulsoCarteira::query()
        ->select([
            'qc_avulso_carteiras.*',
            'obras.nome as obra_nome',
            'obras.id as obra_id',
            DB::raw('(
                DATE_SUB(
                    planejamentos.data,
                        INTERVAL (
                        sla_start + sla_mobilizacao + sla_negociacao + (
                            select sum(dias_prazo) from workflow_alcadas where workflow_tipo_id = 7)
                        )
                     DAY
                )
            ) as data_start'),
            DB::raw('(
                select count(qc.id) from qc
                    where qc.carteira_id = qc_avulso_carteiras.id
            ) as qc_count'),
            DB::raw('(
                    SELECT
                        GROUP_CONCAT(users.name SEPARATOR ", ")
                    FROM
                        qc_avulso_carteira_users
                        JOIN users ON qc_avulso_carteira_users.user_id = users.id
                    WHERE
                        qc_avulso_carteira_id = qc_avulso_carteiras.id
                ) as compradores'),
            DB::raw('(
                    SELECT
                        qc.created_at
                    FROM qc
                    WHERE
                        qc.carteira_id = qc_avulso_carteiras.id
                    ORDER BY qc.created_at asc
                    LIMIT 1
                ) as data_inicial'),
        ])
        ->join(
            'qc_avulso_carteira_planejamento',
            'qc_avulso_carteira_planejamento.qc_avulso_carteira_id',
            'qc_avulso_carteiras.id'
        )
        ->join(
            'planejamentos',
            'planejamentos.id',
            'qc_avulso_carteira_planejamento.planejamento_id'
        )
        ->join(
            'obras',
            'obras.id',
            'planejamentos.obra_id'
        )
        ->join('obra_users', function($join) {
            $join->on('obras.id', 'obra_users.obra_id')
                ->where('obra_users.user_id', auth()->id());
        });

        $request = $this->request();

        if($request->obra_id) {
            $carteiras->where('planejamentos.obra_id', $request->obra_id);
        }

        if($request->carteira_id) {
            $carteiras->where('carteiras.id', $request->obra_id);
        }

        if($request->data_start) {
            $carteiras->whereDate(
                'planejamentos.data',
                '>=',
                Carbon::createFromFormat('d/m/Y', $request->data_start)
            );
        }

        if($request->data_end) {
            $carteiras->whereDate(
                'planejamentos.data',
                '<=',
                Carbon::createFromFormat('d/m/Y', $request->data_end)
            );
        }

        return $this->applyScopes($carteiras);
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

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'obras.nome' => ['name' => 'obra_nome', 'data' => 'obra_nome', 'title' => 'Obra'],
            'nome' => ['name' => 'nome', 'data' => 'nome', 'title' => 'Carteira'],
            'compradores' => ['name' => 'compradores', 'data' => 'compradores'],
            'data_start' => ['name' => 'data_start', 'data' => 'data_start', 'title' => 'Data de Start'],
            'farol' => ['name' => 'farol', 'data' => 'farol', 'title' => 'Farol', 'searchable' => false,],
            'qc_count' => ['name' => 'qc_count', 'data' => 'qc_count', 'title' => 'Qtd Q.C.'],
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
        return 'carteiras';
    }
}
