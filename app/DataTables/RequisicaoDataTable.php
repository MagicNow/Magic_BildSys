<?php

namespace App\DataTables;

use App\Models\Requisicao;
use Form;
use Yajra\Datatables\Services\DataTable;

class RequisicaoDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'requisicao.datatables_actions')
            ->editColumn('created_at', function($obj){
                return $obj->created_at ? with(new\Carbon\Carbon($obj->created_at))->format('d/m/Y H:i') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(requisicao.created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
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
        $requisicao = Requisicao::query()
            ->select([
                'requisicao.*',
                'users.name as usuario',
                'obras.nome as obra',
                'requisicao_status.nome as status',
                ])
            ->join('requisicao_status','requisicao_status.id','requisicao.status_id')
            ->join('obras','obras.id','requisicao.obra_id')
            ->join('users','users.id','requisicao.user_id');

        return $this->applyScopes($requisicao);
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
                'dom' => 'Bfrtip',
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
            'Id' => ['name' => 'id', 'data' => 'id'],
            'data' => ['name' => 'created_at', 'data' => 'created_at'],
            'obra' => ['name' => 'obra', 'data' => 'obra'],
            'local' => ['name' => 'local', 'data' => 'local'],
            'torre' => ['name' => 'torre', 'data' => 'torre'],
            'pavimento' => ['name' => 'pavimento', 'data' => 'pavimento'],
            'trecho' => ['name' => 'trecho', 'data' => 'trecho'],
            'andar' => ['name' => 'andar', 'data' => 'andar'],
            'solicitante' => ['name' => 'usuário', 'data' => 'usuario'],
            'status' => ['name' => 'status', 'data' => 'status'],
            'action' => ['name'=>'Ações', 'title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10%', 'class' => 'all']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'requisicao';
    }
}
