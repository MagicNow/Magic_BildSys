<?php

namespace App\DataTables\Admin;

use App\Models\Fornecedor;
use Form;
use Yajra\Datatables\Services\DataTable;

class FornecedoresDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('is_user', function($fornecedor) {
                return $fornecedor->is_user
                    ? '<i class="fa fa-check text-success"></i>'
                    : '<i class="fa fa-times text-danger"></i>';
            })
            ->addColumn('action', 'admin.fornecedores.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $fornecedores = Fornecedor::query();

        return $this->applyScopes($fornecedores);
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
//            ->addAction(['width' => '10%'])
            ->ajax('')
            ->parameters([
                'responsive' => 'true',
                 'initComplete' => 'function () {
                    max = this.api().columns().count();
                    this.api().columns().every(function (col) {
                        if((col+2)<max){
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
            'nome' => ['name' => 'nome', 'data' => 'nome'],
            'cnpj' => ['name' => 'cnpj', 'data' => 'cnpj'],
//            'logradouro' => ['name' => 'logradouro', 'data' => 'logradouro'],
//            'numero' => ['name' => 'numero', 'data' => 'numero'],
//            'complemento' => ['name' => 'complemento', 'data' => 'complemento'],
            'municipio' => ['name' => 'municipio', 'data' => 'municipio'],
            'estado' => ['name' => 'estado', 'data' => 'estado'],
//            'situacao_cnpj' => ['name' => 'situacao_cnpj', 'data' => 'situacao_cnpj'],
//            'inscricao_estadual' => ['name' => 'inscricao_estadual', 'data' => 'inscricao_estadual'],
            'email' => ['name' => 'email', 'data' => 'email'],
            'site' => ['name' => 'site', 'data' => 'site'],
            'telefone' => ['name' => 'telefone', 'data' => 'telefone'],
            'is_user' => ['name' => 'is_user', 'data' => 'is_user', 'searchable' => false, 'title' => 'Usuário', 'orderable' => false],
            'action' => ['title' => '#', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10%']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'fornecedores';
    }
}
