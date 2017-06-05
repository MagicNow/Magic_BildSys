<?php

namespace App\DataTables;

use App\Models\QcItemQcFornecedor;
use Form;
use Yajra\Datatables\Services\DataTable;

class QcItemQcFornecedorDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'qc_item_qc_fornecedors.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $qcItemQcFornecedors = QcItemQcFornecedor::query();

        return $this->applyScopes($qcItemQcFornecedors);
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
            ->addAction(['width' => '10%', 'class' => 'all'])
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
            'qc_item_id' => ['name' => 'qc_item_id', 'data' => 'qc_item_id'],
            'qc_fornecedor_id' => ['name' => 'qc_fornecedor_id', 'data' => 'qc_fornecedor_id'],
            'user_id' => ['name' => 'user_id', 'data' => 'user_id'],
            'qtd' => ['name' => 'qtd', 'data' => 'qtd'],
            'valor_unitario' => ['name' => 'valor_unitario', 'data' => 'valor_unitario'],
            'valor_total' => ['name' => 'valor_total', 'data' => 'valor_total'],
            'vencedor' => ['name' => 'vencedor', 'data' => 'vencedor'],
            'data_decisao' => ['name' => 'data_decisao', 'data' => 'data_decisao']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'qcItemQcFornecedors';
    }
}
