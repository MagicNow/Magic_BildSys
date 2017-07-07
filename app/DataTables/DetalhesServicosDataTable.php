<?php

namespace App\DataTables;

use App\Models\Orcamento;
use App\Models\OrdemDeCompraItem;
use App\Repositories\OrdemDeCompraRepository;
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
            ->editColumn('valor_comprometido_a_gastar', function($obj){
                $valor_comprometido_a_gastar = OrdemDeCompraRepository::valorComprometidoAGastarItem($obj->grupo_id, $obj->subgrupo1_id, $obj->subgrupo2_id, $obj->subgrupo3_id, $obj->servico_id, $obj->insumo_id);
                
                return '<small class="pull-left">R$</small>'.number_format( doubleval($valor_comprometido_a_gastar), 2, ',','.');
            })
            ->editColumn('saldo_orcamento', function($obj){
//              Se o insumo foi incluído no orçamento, o SALDO DE ORÇAMENTO fica com o valor comprado negativo.
//                if($obj->insumo_incluido){
//                    return '<small class="pull-left">R$</small> - '.number_format( doubleval($obj->valor_oc), 2, ',','.');
//                }else{
                    return '<small class="pull-left">R$</small>'.number_format( doubleval($obj->saldo_orcamento), 2, ',','.');
//                }
            })
            ->editColumn('valor_oc', function($obj){
                return '<small class="pull-left">R$</small>'.number_format( doubleval($obj->valor_oc), 2, ',','.');
            })
            ->editColumn('saldo_disponivel', function($obj){
                if($obj->saldo_disponivel){
                    return '<small class="pull-left">R$</small>'.number_format( doubleval($obj->saldo_disponivel), 2, ',','.');
                }else {
                    if($obj->insumo_incluido || $obj->substitui){
                        if($obj->valor_oc){
                            return '<small class="pull-left">R$</small> - '.number_format( doubleval($obj->valor_oc), 2, ',','.');
                        }else{
                            return '<small class="pull-left">R$</small>'.number_format( doubleval($obj->valor_oc), 2, ',','.');
                        }
                    }else{
                        return '<small class="pull-left">R$</small>'.number_format( doubleval($obj->saldo_orcamento), 2, ',','.');
                    }
                }
            })
            ->editColumn('descricao', function($obj){
                if($obj->substitui){
                    return "<strong  data-toggle=\"tooltip\" data-placement=\"top\" data-html=\"true\"
                    title=\"". '<i class=\'fa fa-exchange\'></i> ' . $obj->substitui . "\">
                    $obj->descricao <button type=\"button\" class=\"btn btn-info btn-flat btn-xs\"> <i class=\"fa fa-exchange\"></i> </button>
                    </strong>";
                } else {
                    return $obj->descricao;
                }
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
        $orcamentos = Orcamento::select([
            DB::raw("CONCAT(SUBSTRING_INDEX(orcamentos.codigo_insumo, '.', -1),' - ' ,orcamentos.descricao) as descricao"),
            'orcamentos.unidade_sigla',
            DB::raw("
                IF (orcamentos.insumo_incluido = 1, 0, orcamentos.preco_total) as valor_previsto
            "),
            'orcamentos.insumo_incluido',
            'orcamentos.grupo_id',
            'orcamentos.subgrupo1_id',
            'orcamentos.subgrupo2_id',
            'orcamentos.subgrupo3_id',
            'orcamentos.servico_id',
            'orcamentos.insumo_id',
            DB::raw('0 as valor_realizado'),
            DB::raw('orcamentos.preco_total as saldo_orcamento'),
            DB::raw("CONCAT(insumos_sub.codigo,' - ' ,insumos_sub.nome) as substitui"),
            DB::raw('
                    (SELECT 
                        SUM(ordem_de_compra_itens.valor_total) 
                    FROM ordem_de_compra_itens
                    JOIN ordem_de_compras
                        ON ordem_de_compra_itens.ordem_de_compra_id = ordem_de_compras.id
                    WHERE ordem_de_compra_itens.insumo_id = orcamentos.insumo_id
                    AND ordem_de_compra_itens.grupo_id = orcamentos.grupo_id
                    AND ordem_de_compra_itens.subgrupo1_id = orcamentos.subgrupo1_id
                    AND ordem_de_compra_itens.subgrupo2_id = orcamentos.subgrupo2_id
                    AND ordem_de_compra_itens.subgrupo3_id = orcamentos.subgrupo3_id
                    AND ordem_de_compra_itens.servico_id = orcamentos.servico_id
                    AND (
                            ordem_de_compras.oc_status_id = 2
                            OR
                            ordem_de_compras.oc_status_id = 3                            
                            OR
                            ordem_de_compras.oc_status_id = 5
                        )
                    AND ordem_de_compra_itens.deleted_at IS NULL
                    AND ordem_de_compra_itens.servico_id = '.$this->servico_id.'
                    AND ordem_de_compra_itens.obra_id ='. $this->obra_id .' ) as valor_oc'),

            DB::raw('orcamentos.preco_total - (SELECT 
                        SUM(ordem_de_compra_itens.valor_total) 
                    FROM ordem_de_compra_itens
                    JOIN ordem_de_compras
                        ON ordem_de_compra_itens.ordem_de_compra_id = ordem_de_compras.id
                    WHERE ordem_de_compra_itens.insumo_id = orcamentos.insumo_id
                    AND ordem_de_compra_itens.grupo_id = orcamentos.grupo_id
                    AND ordem_de_compra_itens.subgrupo1_id = orcamentos.subgrupo1_id
                    AND ordem_de_compra_itens.subgrupo2_id = orcamentos.subgrupo2_id
                    AND ordem_de_compra_itens.subgrupo3_id = orcamentos.subgrupo3_id
                    AND ordem_de_compra_itens.servico_id = orcamentos.servico_id
                    AND (
                            ordem_de_compras.oc_status_id = 2
                            OR
                            ordem_de_compras.oc_status_id = 3                            
                            OR
                            ordem_de_compras.oc_status_id = 5
                        )
                    AND ordem_de_compra_itens.deleted_at IS NULL
                    AND ordem_de_compra_itens.servico_id = '.$this->servico_id.'
                    AND ordem_de_compra_itens.obra_id ='. $this->obra_id .' ) as saldo_disponivel')
            ])
            ->leftJoin(DB::raw('orcamentos orcamentos_sub'),  'orcamentos_sub.id', 'orcamentos.orcamento_que_substitui')
            ->leftJoin(DB::raw('insumos insumos_sub'), 'insumos_sub.id', 'orcamentos_sub.insumo_id')
            ->where('orcamentos.servico_id','=', DB::raw($this->servico_id))
            ->where('orcamentos.obra_id','=', DB::raw($this->obra_id))
            ->groupBy('orcamentos.insumo_id');

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
            'Valor_comprometido_à_gastar' => ['name' => 'valor_comprometido_a_gastar', 'data' => 'valor_comprometido_a_gastar', 'searchable' => false],
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
