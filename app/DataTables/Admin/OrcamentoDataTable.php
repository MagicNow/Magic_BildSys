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
            ->editColumn('created_at', function($obj){
                return $obj->created_at ? $obj->created_at->format('d/m/Y'): '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(orcamentos.created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $orcamentos = Orcamento::query()
            ->select([
                'orcamentos.id',
                'obras.nome as obra',
                'orcamentos.codigo_insumo',
                'insumos.nome as insumo',
                'servicos.nome as servico',
                'orcamentos.unidade_sigla',
                'orcamentos.created_at',
                'grupos.nome as grupo',
                'grupos1.nome as subgrupo1',
                'grupos2.nome as subgrupo2',
                'grupos3.nome as subgrupo3',
            ])
        ->join('obras','obras.id','orcamentos.obra_id')
        ->join('insumos','insumos.id','orcamentos.insumo_id')
        ->join('grupos','grupos.id','orcamentos.grupo_id')
        ->join('grupos as grupos1','grupos1.id','orcamentos.subgrupo1_id')
        ->join('grupos as grupos2','grupos2.id','orcamentos.subgrupo2_id')
        ->join('grupos as grupos3','grupos3.id','orcamentos.subgrupo3_id')
        ->join('servicos','servicos.id','orcamentos.servico_id');

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
            'obra' => ['name' => 'obras.nome', 'data' => 'obra'],
            'codigo' => ['name' => 'codigo_insumo', 'data' => 'codigo_insumo'],
            'insumo' => ['name' => 'insumos.nome', 'data' => 'insumo'],
            'servico' => ['name' => 'servicos.nome', 'data' => 'servico'],
            'sigla' => ['name' => 'unidade_sigla', 'data' => 'unidade_sigla'],
//            'coeficiente' => ['name' => 'coeficiente', 'data' => 'coeficiente'],
//            'indireto' => ['name' => 'indireto', 'data' => 'indireto'],
//            'terreo_externo_solo' => ['name' => 'terreo_externo_solo', 'data' => 'terreo_externo_solo'],
//            'terreo_externo_estrutura' => ['name' => 'terreo_externo_estrutura', 'data' => 'terreo_externo_estrutura'],
//            'terreo_interno' => ['name' => 'terreo_interno', 'data' => 'terreo_interno'],
//            'primeiro_pavimento' => ['name' => 'primeiro_pavimento', 'data' => 'primeiro_pavimento'],
//            'segundo_ao_penultimo' => ['name' => 'segundo_ao_penultimo', 'data' => 'segundo_ao_penultimo'],
//            'cobertura_ultimo_piso' => ['name' => 'cobertura_ultimo_piso', 'data' => 'cobertura_ultimo_piso'],
//            'atico' => ['name' => 'atico', 'data' => 'atico'],
//            'reservatorio' => ['name' => 'reservatorio', 'data' => 'reservatorio'],
//            'qtd_total' => ['name' => 'qtd_total', 'data' => 'qtd_total'],
//            'preco_unitario' => ['name' => 'preco_unitario', 'data' => 'preco_unitario'],
//            'preco_total' => ['name' => 'preco_total', 'data' => 'preco_total'],
//            'referencia_preco' => ['name' => 'referencia_preco', 'data' => 'referencia_preco'],
//            'obs' => ['name' => 'obs', 'data' => 'obs'],
//            'porcentagem_orcamento' => ['name' => 'porcentagem_orcamento', 'data' => 'porcentagem_orcamento'],
//            'orcamento_tipo_id' => ['name' => 'orcamento_tipo_id', 'data' => 'orcamento_tipo_id'],
//            'ativo' => ['name' => 'ativo', 'data' => 'ativo'],
            'grupo' => ['name' => 'grupos.nome', 'data' => 'grupo'],
            'subgrupo1' => ['name' => 'grupos1.nome', 'data' => 'subgrupo1'],
            'subgrupo2' => ['name' => 'grupos2.nome', 'data' => 'subgrupo2'],
            'subgrupo3' => ['name' => 'grupos3.nome', 'data' => 'subgrupo3'],
            'dataUpload' => ['name' => 'created_at', 'data' => 'created_at']
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
