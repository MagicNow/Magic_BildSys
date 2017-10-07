<?php

namespace App\DataTables;

use App\Models\QcFornecedorEqualizacaoCheck;
use Form;
use Yajra\Datatables\Services\DataTable;

class QcFornecedorEqualizacaoCheckDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'qc_fornecedor_equalizacao_checks.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $qcFornecedorEqualizacaoChecks = QcFornecedorEqualizacaoCheck::query();

        return $this->applyScopes($qcFornecedorEqualizacaoChecks);
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
                'responsive'=> 'true',
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
            'qc_fornecedor_id' => ['name' => 'qc_fornecedor_id', 'data' => 'qc_fornecedor_id'],
            'user_id' => ['name' => 'user_id', 'data' => 'user_id'],
            'checkable_type' => ['name' => 'checkable_type', 'data' => 'checkable_type'],
            'checkable_id' => ['name' => 'checkable_id', 'data' => 'checkable_id'],
            'checked' => ['name' => 'checked', 'data' => 'checked'],
            'obs' => ['name' => 'obs', 'data' => 'obs']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'qcFornecedorEqualizacaoChecks';
    }
}
