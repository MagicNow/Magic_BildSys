<?php

namespace App\DataTables\Admin;

use App\Models\Orcamento;
use Form;
use Yajra\Datatables\Services\DataTable;

class OrcamentoDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'admin.orcamentos.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $orcamentos = Orcamento::query();

        return $this->applyScopes($orcamentos);
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
            'obra_id' => ['name' => 'obra_id', 'data' => 'obra_id'],
            'codigo_insumo' => ['name' => 'codigo_insumo', 'data' => 'codigo_insumo'],
            'insumo_id' => ['name' => 'insumo_id', 'data' => 'insumo_id'],
            'servico_id' => ['name' => 'servico_id', 'data' => 'servico_id'],
            'grupo_id' => ['name' => 'grupo_id', 'data' => 'grupo_id'],
            'unidade_sigla' => ['name' => 'unidade_sigla', 'data' => 'unidade_sigla'],
            'coeficiente' => ['name' => 'coeficiente', 'data' => 'coeficiente'],
            'indireto' => ['name' => 'indireto', 'data' => 'indireto'],
            'terreo_externo_solo' => ['name' => 'terreo_externo_solo', 'data' => 'terreo_externo_solo'],
            'terreo_externo_estrutura' => ['name' => 'terreo_externo_estrutura', 'data' => 'terreo_externo_estrutura'],
            'terreo_interno' => ['name' => 'terreo_interno', 'data' => 'terreo_interno'],
            'primeiro_pavimento' => ['name' => 'primeiro_pavimento', 'data' => 'primeiro_pavimento'],
            'segundo_ao_penultimo' => ['name' => 'segundo_ao_penultimo', 'data' => 'segundo_ao_penultimo'],
            'cobertura_ultimo_piso' => ['name' => 'cobertura_ultimo_piso', 'data' => 'cobertura_ultimo_piso'],
            'atico' => ['name' => 'atico', 'data' => 'atico'],
            'reservatorio' => ['name' => 'reservatorio', 'data' => 'reservatorio'],
            'qtd_total' => ['name' => 'qtd_total', 'data' => 'qtd_total'],
            'preco_unitario' => ['name' => 'preco_unitario', 'data' => 'preco_unitario'],
            'preco_total' => ['name' => 'preco_total', 'data' => 'preco_total'],
            'referencia_preco' => ['name' => 'referencia_preco', 'data' => 'referencia_preco'],
            'obs' => ['name' => 'obs', 'data' => 'obs'],
            'porcentagem_orcamento' => ['name' => 'porcentagem_orcamento', 'data' => 'porcentagem_orcamento'],
            'orcamento_tipo_id' => ['name' => 'orcamento_tipo_id', 'data' => 'orcamento_tipo_id'],
            'ativo' => ['name' => 'ativo', 'data' => 'ativo'],
            'subgrupo1_id' => ['name' => 'subgrupo1_id', 'data' => 'subgrupo1_id'],
            'subgrupo2_id' => ['name' => 'subgrupo2_id', 'data' => 'subgrupo2_id'],
            'subgrupo3_id' => ['name' => 'subgrupo3_id', 'data' => 'subgrupo3_id'],
            'user_id' => ['name' => 'user_id', 'data' => 'user_id'],
            'descricao' => ['name' => 'descricao', 'data' => 'descricao']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'orcamentos';
    }
}
