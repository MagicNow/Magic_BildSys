<?php

namespace App\DataTables;

use App\Models\RetroalimentacaoObra;
use Form;
use Yajra\Datatables\Services\DataTable;

class RetroalimentacaoObraDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'retroalimentacao_obras.datatables_actions')
            ->editColumn('obra_id', function($obj){
                return $obj->obra_id ? $obj->obra: '';
            })
            ->editColumn('user_id', function($obj){
                return $obj->user_nome;
            })
            ->editColumn('created_at',function ($obj){
                return $obj->created_at ? with(new\Carbon\Carbon($obj->created_at))->format('d/m/Y') : '';
            })
            ->editColumn('situacao_atual',function ($obj){
                return str_limit($obj->situacao_atual, $limit = 20, $end = '...');
            })
            ->editColumn('situacao_proposta',function ($obj){
                return str_limit($obj->situacao_proposta, $limit = 20, $end = '...');
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(retroalimentacao_obras.created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
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
        $retroalimentacaoObras = RetroalimentacaoObra::query()
            ->select([
                'obras.nome as obra',
                'users.name as user',
                'retroalimentacao_obras.id',
                'retroalimentacao_obras.origem',
                'retroalimentacao_obras.categoria',
                'retroalimentacao_obras.situacao_atual',
                'retroalimentacao_obras.situacao_proposta',
                'retroalimentacao_obras.created_at'
            ])
            ->join('obras','obras.id','=', 'retroalimentacao_obras.obra_id')
            ->join('users','users.id','=', 'retroalimentacao_obras.user_id');
        return $this->applyScopes($retroalimentacaoObras);
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
            ->addAction(['width' => '10%'])
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
                'dom' => 'Bfrtip',
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
            'obra' => ['name' => 'obras.nome', 'data' => 'obra'],
            'usuário' => ['name' => 'users.name', 'data' => 'user'],
            'origem' => ['name' => 'retroalimentacao_obras.origem', 'data' => 'origem'],
            'categoria' => ['name' => 'retroalimentacao_obras.categoria', 'data' => 'categoria'],
            'situação_atual' => ['name' => 'retroalimentacao_obras.situacao_atual', 'data' => 'situacao_atual'],
            'situação_proposta' => ['name' => 'retroalimentacao_obras.situacao_proposta', 'data' => 'situacao_proposta'],
            'data_de_inclusão' => ['name' => 'retroalimentacao_obras.created_at', 'data' => 'created_at']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'retroalimentacaoObras';
    }
}
