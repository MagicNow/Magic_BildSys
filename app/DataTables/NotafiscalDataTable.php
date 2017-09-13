<?php

namespace App\DataTables;

use App\Models\Notafiscal;
use Form;
use Yajra\Datatables\Services\DataTable;

class NotafiscalDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->filterColumn('cnpj', function ($query, $keyword) {
                $keyword = str_replace(['-', '.', '/'], '', $keyword);
                $query->whereRaw("cnpj like ?", ["%$keyword%"]);
            })
            ->editColumn('chave', function ($obj){
                return sprintf('<div title="%s">%s</div>', $obj->chave, substr($obj->chave,0,10) . '...');
            })
            ->editColumn('cnpj', function ($obj){
                return $obj->cnpj ? mask($obj->cnpj, '##.###.###/####-##') : '';
            })
            ->editColumn('data_emissao', function ($obj){
                return sprintf('<div title="">%s</div>', $obj->data_emissao ? $obj->data_emissao->format("d/m/Y H:i") : "");
            })
            ->editColumn('data_saida', function ($obj){
                return sprintf('<div title="">%s</div>', $obj->data_saida ? $obj->data_saida->format("d/m/Y H:i") : "");
            })
            ->addColumn('action', 'notafiscals.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $notafiscals = Notafiscal::query();

        return $this->applyScopes($notafiscals);
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
            //->addAction(['width' => '10%'])
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
            'contrato' => ['name' => 'contrato_id', 'data' => 'contrato_id'],
            //'solicitacao_entrega_id' => ['name' => 'solicitacao_entrega_id', 'data' => 'solicitacao_entrega_id'],
            //'nsu' => ['name' => 'nsu', 'data' => 'nsu'],
            'numero' => ['name' => 'codigo', 'data' => 'codigo'],
            'razao_social' => ['name' => 'razao_social', 'data' => 'razao_social'],
            'fantasia' => ['name' => 'fantasia', 'data' => 'fantasia'],
            'natureza_operacao' => ['name' => 'natureza_operacao', 'data' => 'natureza_operacao'],
            'data_emissao' => ['name' => 'data_emissao', 'data' => 'data_emissao'],
            'data_saida' => ['name' => 'data_saida', 'data' => 'data_saida'],
            'cnpj' => ['name' => 'cnpj', 'data' => 'cnpj'],
            'chave' => ['name' => 'chave', 'data' => 'chave'],
            //'cnpj_destinatario' => ['name' => 'cnpj_destinatario', 'data' => 'cnpj_destinatario']
            'action' => [
                'title' => 'Ações',
                'printable' => false,
                'exportable' => false,
                'searchable' => false,
                'orderable' => false,
                'width'=>'10%'
            ]

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'notafiscals';
    }
}
