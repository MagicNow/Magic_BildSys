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
            ->editColumn('data_inclusao',function ($obj){
                return $obj->data_inclusao ? with(new\Carbon\Carbon($obj->data_inclusao))->format('d/m/Y') : '';
            })
            ->filterColumn('data_inclusao', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(retroalimentacao_obras.data_inclusao,'%d/%m/%Y') like ?", ["%$keyword%"]);
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
                'retroalimentacao_obras.data_inclusao'
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
            'usuario' => ['name' => 'users.name', 'data' => 'user'],
            'origem' => ['name' => 'retroalimentacao_obras.origem', 'data' => 'origem'],
            'categoria' => ['name' => 'retroalimentacao_obras.categoria', 'data' => 'categoria'],
            'situacao_atual' => ['name' => 'retroalimentacao_obras.situacao_atual', 'data' => 'situacao_atual'],
            'situacao_proposta' => ['name' => 'retroalimentacao_obras.situacao_proposta', 'data' => 'situacao_proposta'],
            'data_inclusao' => ['name' => 'retroalimentacao_obras.data_inclusao', 'data' => 'data_inclusao']
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
