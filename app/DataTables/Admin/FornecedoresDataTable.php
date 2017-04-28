<?php

namespace App\DataTables\Admin;

use App\Models\Fornecedores;
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
        $fornecedores = Fornecedores::query();

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
            'codigo_mega' => ['name' => 'codigo_mega', 'data' => 'codigo_mega'],
            'nome' => ['name' => 'nome', 'data' => 'nome'],
            'cnpj' => ['name' => 'cnpj', 'data' => 'cnpj'],
            'tipo_logradouro' => ['name' => 'tipo_logradouro', 'data' => 'tipo_logradouro'],
            'logradouro' => ['name' => 'logradouro', 'data' => 'logradouro'],
            'numero' => ['name' => 'numero', 'data' => 'numero'],
            'complemento' => ['name' => 'complemento', 'data' => 'complemento'],
            'cidade_id' => ['name' => 'cidade_id', 'data' => 'cidade_id'],
            'municipio' => ['name' => 'municipio', 'data' => 'municipio'],
            'estado' => ['name' => 'estado', 'data' => 'estado'],
            'situacao_cnpj' => ['name' => 'situacao_cnpj', 'data' => 'situacao_cnpj'],
            'inscricao_estadual' => ['name' => 'inscricao_estadual', 'data' => 'inscricao_estadual'],
            'email' => ['name' => 'email', 'data' => 'email'],
            'site' => ['name' => 'site', 'data' => 'site'],
            'telefone' => ['name' => 'telefone', 'data' => 'telefone']
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
