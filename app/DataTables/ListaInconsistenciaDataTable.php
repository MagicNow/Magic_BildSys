<?php

namespace App\DataTables;

use App\Models\RequisicaoItem;
use Form;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class ListaInconsistenciaDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'requisicao.processo_saida.lista_inconsistencia_datatables_actions')
            ->editColumn('inconsistencia', function($obj) {
                if($obj->inconsistencia == 'OK') {
                    return '<span style="color: #7ed321">'.$obj->inconsistencia.'</span>';
                } else {
                    return '<span style="color: #eb0000">'.$obj->inconsistencia.'</span>';
                }
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
        $requisicao = RequisicaoItem::select([
                'requisicao_itens.id',
                DB::raw('0 AS agrupamento'),
                'insumos.nome AS insumo',
                'insumos.unidade_sigla AS unidade_medida',
                DB::raw("format(requisicao_itens.qtde,2,'de_DE') AS qtd_solicitada"),
                DB::raw('(
                        SELECT 
                            FORMAT(SUM(qtd_lida), 2, "de_DE")
                        FROM
                            requisicao_saida_leitura
                        WHERE
                            requisicao_item_id = requisicao_itens.id
                ) AS qtd_lida'),
                DB::raw('(
                        SELECT 
                            COUNT(id)
                        FROM
                            requisicao_saida_leitura
                        WHERE
                            requisicao_item_id = requisicao_itens.id
                ) AS numero_leituras'),
                DB::raw(
                    'IF(
                        (SELECT 
                            FORMAT(SUM(qtd_lida), 2, "de_DE")
                        FROM
                            requisicao_saida_leitura
                        WHERE
                            requisicao_item_id = requisicao_itens.id)
                     = 
                        (format(requisicao_itens.qtde, 2, "de_DE"))
                    , "OK", "NOK") AS inconsistencia'),
                ])
            ->join('estoque','estoque.id','requisicao_itens.estoque_id')
            ->join('insumos','insumos.id','estoque.insumo_id')
            ->where('requisicao_itens.requisicao_id', $this->request()->segments()[2]);

        return $this->applyScopes($requisicao);
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
                'dom' => 'tip',
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
            'Agrupamento' => ['name' => 'agrupamento', 'data' => 'agrupamento'],
            'Insumo' => ['name' => 'insumos.nome', 'data' => 'insumo'],
            'Unidade_de_medida' => ['name' => 'insumos.unidade_sigla', 'data' => 'unidade_medida'],
            'Qtd_solicitada' => ['name' => 'requisicao_itens.qtde', 'data' => 'qtd_solicitada'],
            'Qtd_lida' => ['name' => 'qtd_lida', 'data' => 'qtd_lida'],
            'Número_de_leituras' => ['name' => 'numero_leituras', 'data' => 'numero_leituras'],
            'Inconsistência' => ['name' => 'inconsistencia', 'data' => 'inconsistencia'],
            'action' => ['name'=>'Ações', 'title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'15%', 'class' => 'all']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'requisicao';
    }
}
