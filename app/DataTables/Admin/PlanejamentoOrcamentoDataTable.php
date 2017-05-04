<?php

namespace App\DataTables\Admin;

use App\Models\Admin\PlanejamentoOrcamento;
use Form;
use Yajra\Datatables\Services\DataTable;

class PlanejamentoOrcamentoDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'admin.planejamento_orcamentos.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $planejamentoOrcamentos = PlanejamentoOrcamento::query();

        return $this->applyScopes($planejamentoOrcamentos);
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
            'planejamento_id' => ['name' => 'planejamento_id', 'data' => 'planejamento_id'],
            'insumo_id' => ['name' => 'insumo_id', 'data' => 'insumo_id'],
            'codigo_estruturado' => ['name' => 'codigo_estruturado', 'data' => 'codigo_estruturado'],
            'grupo_id' => ['name' => 'grupo_id', 'data' => 'grupo_id'],
            'subgrupo1_id' => ['name' => 'subgrupo1_id', 'data' => 'subgrupo1_id'],
            'subgrupo2_id' => ['name' => 'subgrupo2_id', 'data' => 'subgrupo2_id'],
            'subgrupo3_id' => ['name' => 'subgrupo3_id', 'data' => 'subgrupo3_id'],
            'servico_id' => ['name' => 'servico_id', 'data' => 'servico_id'],
            'trocado_de' => ['name' => 'trocado_de', 'data' => 'trocado_de'],
            'insumo_pai' => ['name' => 'insumo_pai', 'data' => 'insumo_pai'],
            'quantidade_compra' => ['name' => 'quantidade_compra', 'data' => 'quantidade_compra']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'planejamentoOrcamentos';
    }
}
