<?php

namespace App\DataTables;

use App\Models\OrdemDeCompraItem;
use Form;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class DetalhesServicosDataTable extends DataTable
{
    protected $obra_id = null;
    protected $servico_id = null;
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('valor_previsto', function($obj){
                return '<small class="pull-left">R$</small>'.number_format( doubleval($obj->valor_previsto), 2, ',','.');
            })
            ->editColumn('valor_realizado', function($obj){
                return '<small class="pull-left">R$</small>'.number_format( doubleval($obj->valor_realizado), 2, ',','.');
            })
            ->editColumn('a_gastar', function($obj){
                return '<small class="pull-left">R$</small>'.number_format( doubleval($obj->a_gastar), 2, ',','.');
            })
            ->editColumn('saldo_orcamento', function($obj){
                return '<small class="pull-left">R$</small>'.number_format( doubleval($obj->saldo_orcamento), 2, ',','.');
            })
            ->editColumn('valor_oc', function($obj){
                return '<small class="pull-left">R$</small>'.number_format( doubleval($obj->valor_oc), 2, ',','.');
            })
            ->editColumn('saldo_disponivel', function($obj){
                return '<small class="pull-left">R$</small>'.number_format( doubleval($obj->saldo_disponivel), 2, ',','.');
            })
            ->filterColumn('descricao',function($query, $keyword){
                $query->where(DB::raw("CONCAT(SUBSTRING_INDEX(orcamentos.codigo_insumo, '.', -1),' - ' ,orcamentos.descricao)"),'LIKE','%'.$keyword.'%');
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
        $ordemDeCompras = OrdemDeCompraItem::select([
            DB::raw("CONCAT(SUBSTRING_INDEX(orcamentos.codigo_insumo, '.', -1),' - ' ,orcamentos.descricao) as descricao"),
            'orcamentos.unidade_sigla',
            'orcamentos.preco_total as valor_previsto',
            DB::raw('0 as valor_realizado'),
            DB::raw('0 as a_gastar'),
            DB::raw('orcamentos.preco_total as saldo_orcamento'),
            DB::raw('SUM(ordem_de_compra_itens.valor_total) as valor_oc'),
            DB::raw('(orcamentos.preco_total - ordem_de_compra_itens.valor_total) as saldo_disponivel')
        ])
            ->join('orcamentos', function ($join) {
                $join->on('orcamentos.insumo_id','=', 'ordem_de_compra_itens.insumo_id');
                $join->on('orcamentos.grupo_id','=', 'ordem_de_compra_itens.grupo_id');
                $join->on('orcamentos.subgrupo1_id','=', 'ordem_de_compra_itens.subgrupo1_id');
                $join->on('orcamentos.subgrupo2_id','=', 'ordem_de_compra_itens.subgrupo2_id');
                $join->on('orcamentos.subgrupo3_id','=', 'ordem_de_compra_itens.subgrupo3_id');
                $join->on('orcamentos.servico_id','=', 'ordem_de_compra_itens.servico_id');
                $join->on('orcamentos.obra_id','=', 'ordem_de_compra_itens.obra_id');
                $join->on('orcamentos.ativo','=', DB::raw('1'));
            })
            ->join('ordem_de_compras', 'ordem_de_compras.id', '=', 'ordem_de_compra_itens.ordem_de_compra_id')
            ->where('ordem_de_compra_itens.servico_id','=', DB::raw($this->servico_id))
            ->where('ordem_de_compra_itens.obra_id','=', DB::raw($this->obra_id))
            ->whereIn('ordem_de_compras.oc_status_id',[2,3,5])
            ->groupBy('ordem_de_compra_itens.insumo_id');

        return $this->applyScopes($ordemDeCompras);
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
                'responsive'=> 'true',
                'initComplete' => 'function () {
                    max = this.api().columns().count();
                    this.api().columns().every(function (col) {
                        if((col+6)<max){
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
                'dom' => 'Bfrltip',
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
            'Descrição_do_insumo' => ['name' => 'descricao', 'data' => 'descricao'],
            'Und_de_medida' => ['name' => 'unidade_sigla', 'data' => 'unidade_sigla'],
            'Valor_previsto_no_orçamento' => ['name' => 'orcamentos.preco_total', 'data' => 'valor_previsto', 'searchable' => false],
            'Valor_comprometido_realizado' => ['name' => 'valor_realizado', 'data' => 'valor_realizado', 'searchable' => false],
            'Valor_comprometido_à_gastar' => ['name' => 'a_gastar', 'data' => 'a_gastar', 'searchable' => false],
            'Saldo_de_orçamento' => ['name' => 'saldo_orcamento', 'data' => 'saldo_orcamento', 'searchable' => false],
            'Valor_da_Oc' => ['name' => 'valor_oc', 'data' => 'valor_oc', 'searchable' => false],
            'Saldo_disponível' => ['name' => 'saldo_disponivel', 'data' => 'saldo_disponivel', 'searchable' => false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'detalhes-servicos';
    }

    public function getObra($id){
        $this->obra_id = $id;
        return $this;
    }

    public function getServico($id){
        $this->servico_id = $id;
        return $this;
    }
}
