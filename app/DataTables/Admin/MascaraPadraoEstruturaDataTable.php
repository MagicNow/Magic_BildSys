<?php

namespace App\DataTables\Admin;

use App\Models\MascaraPadraoEstrutura;
use Form;
use Yajra\Datatables\Services\DataTable;

class MascaraPadraoEstruturaDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'admin.mascara_padrao_estruturas.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $mascaraPadraoEstruturas = MascaraPadraoEstrutura::query()
            ->select([
                'mascara_padrao_estruturas.id',
                'mascara_padrao.nome as nm_mascara_padrao',
                'mascara_padrao_estruturas.codigo',
                'grupos.nome as grupo',
                'subgrupo1.nome as subgrupo1',
                'subgrupo2.nome as subgrupo2',
                'subgrupo3.nome as subgrupo3',
                'servicos.nome as servico'

            ])
            ->join('mascara_padrao', 'mascara_padrao.id', 'mascara_padrao_estruturas.mascara_padrao_id')
            ->join('grupos', 'grupos.id', 'mascara_padrao_estruturas.grupo_id')
            ->join('grupos as subgrupo1', 'subgrupo1.id', 'mascara_padrao_estruturas.subgrupo1_id')
            ->join('grupos as subgrupo2', 'subgrupo2.id', 'mascara_padrao_estruturas.subgrupo2_id')
            ->join('grupos as subgrupo3', 'subgrupo3.id', 'mascara_padrao_estruturas.subgrupo3_id')
            ->join('servicos', 'servicos.id', 'mascara_padrao_estruturas.servico_id');

        return $this->applyScopes($mascaraPadraoEstruturas);
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
            'máscaraPadrão' => ['name' => 'mascara_padrao.nome', 'data' => 'nm_mascara_padrao'],
            'códigoEstruturado' => ['name' => 'codigo', 'data' => 'codigo'],
            'grupo' => ['name' => 'grupos.nome', 'data' => 'grupo'],
            'subgrupo1' => ['name' => 'subgrupo1.nome', 'data' => 'subgrupo1'],
            'subgrupo2' => ['name' => 'subgrupo2.nome', 'data' => 'subgrupo2'],
            'subgrupo3' => ['name' => 'subgrupo3.nome', 'data' => 'subgrupo3'],
            'serviço' => ['name' => 'servicos.nome', 'data' => 'servico']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'mascaraPadraoEstruturas';
    }
}
