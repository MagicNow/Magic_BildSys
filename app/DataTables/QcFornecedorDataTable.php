<?php

namespace App\DataTables;

use App\Models\QcFornecedor;
use Form;
use Yajra\Datatables\Services\DataTable;

class QcFornecedorDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'qc_fornecedors.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $qcFornecedors = QcFornecedor::query();

        return $this->applyScopes($qcFornecedors);
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
            'quadro_de_concorrencia_id' => ['name' => 'quadro_de_concorrencia_id', 'data' => 'quadro_de_concorrencia_id'],
            'fornecedor_id' => ['name' => 'fornecedor_id', 'data' => 'fornecedor_id'],
            'user_id' => ['name' => 'user_id', 'data' => 'user_id'],
            'rodada' => ['name' => 'rodada', 'data' => 'rodada'],
            'porcentagem_material' => ['name' => 'porcentagem_material', 'data' => 'porcentagem_material'],
            'porcentagem_servico' => ['name' => 'porcentagem_servico', 'data' => 'porcentagem_servico'],
            'porcentagem_faturamento_direto' => ['name' => 'porcentagem_faturamento_direto', 'data' => 'porcentagem_faturamento_direto'],
            'desistencia_motivo_id' => ['name' => 'desistencia_motivo_id', 'data' => 'desistencia_motivo_id'],
            'desistencia_texto' => ['name' => 'desistencia_texto', 'data' => 'desistencia_texto']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'qcFornecedors';
    }
}
