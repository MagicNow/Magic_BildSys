<?php

namespace App\DataTables\Admin;

use App\Models\QcAvulsoCarteira;
use Form;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class QcAvulsoCarteiraDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('action', 'admin.qc_avulso_carteiras.datatables_actions')
            ->editColumn('created_at', function($obj){
                return $obj->created_at ? with(new\Carbon\Carbon($obj->created_at))->format('d/m/Y H:i') : '';
            })
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
            'id',
            'nome',
            'sla_start',
            'sla_negociacao',
            'sla_mobilizacao',
            'created_at',
            DB::raw('(
                    SELECT
                        GROUP_CONCAT(users.name SEPARATOR ", ")
                    FROM
                        qc_avulso_carteira_users
                        JOIN users ON qc_avulso_carteira_users.user_id = users.id
                    WHERE
                        qc_avulso_carteira_id = qc_avulso_carteiras.id
                ) as compradores')
        ]);

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
            'nome' => ['name' => 'nome', 'data' => 'nome'],
            'compradores' => ['name' => 'compradores', 'data' => 'compradores'],
            'sla_start' => ['name' => 'sla_start', 'data' => 'sla_start', 'width'=>'10%'],
            'sla_negociação' => ['name' => 'sla_negociacao', 'data' => 'sla_negociacao', 'width'=>'10%'],
            'sla_mobilização' => ['name' => 'sla_mobilizacao', 'data' => 'sla_mobilizacao', 'width'=>'10%'],
            'cadastradaEm' => ['name' => 'created_at', 'data' => 'created_at', 'width'=>'15%'],
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
