<?php

namespace App\DataTables;

use App\Models\Pagamento;
use Form;
use Yajra\Datatables\Services\DataTable;

class PagamentoDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'pagamentos.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $pagamentos = Pagamento::query();

        return $this->applyScopes($pagamentos);
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
            'contrato_id' => ['name' => 'contrato_id', 'data' => 'contrato_id'],
            'obra_id' => ['name' => 'obra_id', 'data' => 'obra_id'],
            'numero_documento' => ['name' => 'numero_documento', 'data' => 'numero_documento'],
            'fornecedor_id' => ['name' => 'fornecedor_id', 'data' => 'fornecedor_id'],
            'data_emissao' => ['name' => 'data_emissao', 'data' => 'data_emissao'],
            'valor' => ['name' => 'valor', 'data' => 'valor'],
            'pagamento_condicao_id' => ['name' => 'pagamento_condicao_id', 'data' => 'pagamento_condicao_id'],
            'documento_tipo_id' => ['name' => 'documento_tipo_id', 'data' => 'documento_tipo_id'],
            'notas_fiscal_id' => ['name' => 'notas_fiscal_id', 'data' => 'notas_fiscal_id'],
            'enviado_integracao' => ['name' => 'enviado_integracao', 'data' => 'enviado_integracao'],
            'integrado' => ['name' => 'integrado', 'data' => 'integrado']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'pagamentos';
    }
}
