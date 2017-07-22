<?php

namespace App\DataTables;

use App\Models\MedicaoBoletim;
use Form;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class MedicaoBoletimDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('action', 'medicao_boletims.datatables_actions')
            ->editColumn('situacao', function($obj){
                return '<i class="fa fa-circle" aria-hidden="true" style="color:'
                . $obj->status_cor
                . '"></i> '
                . $obj->situacao;
            })
            ->editColumn('obs', function($obj){
                return strlen($obj->obs) ? str_limit($obj->obs, 50) : '';
            })
            ->editColumn('total', function($obj){
                return float_to_money($obj->total);
            })
            ->filterColumn('total',function($query, $search){
                $keyword = str_replace(',','.', str_replace('.','',$search));
                $query->whereRaw('(
                            SELECT SUM(medicoes.qtd) 
                            FROM medicao_boletim_medicao_servico
                            JOIN medicoes ON medicoes.medicao_servico_id = medicao_boletim_medicao_servico.id
                            WHERE medicao_boletim_medicao_servico.medicao_boletim_id = medicao_boletins.id
                        ) LIKE ?', ["%$keyword%"]);
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
        $medicaoBoletims = MedicaoBoletim::query()
            ->select([
                'medicao_boletins.id',
                'medicao_boletins.obs',
                'medicao_boletins.contrato_id',
                'obras.nome as obra',
                'users.name as user',
                'medicao_boletim_status.nome as situacao',
                'medicao_boletim_status.cor as status_cor',
                DB::raw('(
                            SELECT SUM(medicoes.qtd) 
                            FROM medicao_boletim_medicao_servico
                            JOIN medicoes ON medicoes.medicao_servico_id = medicao_boletim_medicao_servico.id
                            WHERE medicao_boletim_medicao_servico.medicao_boletim_id = medicao_boletins.id
                        )
                        as total')
            ])
            ->join('users','users.id','medicao_boletins.user_id')
            ->join('obras','obras.id','medicao_boletins.obra_id')
            ->join('medicao_boletim_status','medicao_boletim_status.id','medicao_boletins.medicao_boletim_status_id');

        return $this->applyScopes($medicaoBoletims);
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
            'id' => ['name' => 'id', 'data' => 'id', 'width'=>'10%'],
            'obra' => ['name' => 'obras.nome', 'data' => 'obra'],
            'contrato' => ['name' => 'contrato_id', 'data' => 'contrato_id', 'width'=>'10%'],
            'status' => ['name' => 'medicao_boletim_status.nome', 'data' => 'situacao', 'width'=>'10%'],
            'obs' => ['name' => 'obs', 'data' => 'obs', 'width'=>'10%'],
            'total' => ['name' => 'total', 'data' => 'total', 'width'=>'10%'],
            'usuário' => ['name' => 'users.name', 'data' => 'user', 'width'=>'10%'],
            'action' => ['title' => 'Ações',
                'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10%']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'medicaoBoletims';
    }
}
