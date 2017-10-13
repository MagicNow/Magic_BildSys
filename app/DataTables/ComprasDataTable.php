<?php

namespace App\DataTables;

use App\Models\Insumo;
use App\Models\Obra;
use App\Models\OrdemDeCompra;
use App\Models\Planejamento;
use App\Repositories\OrdemDeCompraRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Services\DataTable;

class ComprasDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'ordem_de_compras.obras-insumos-datatables-actions')
            ->editColumn('quantidade_compra', function($obj){
                if ($obj->insumo_grupo_id != 1570) {
                    return "<input value='$obj->quantidade_compra' class='form-control
                    js-blur-on-enter money' onblur='quantidadeCompra($obj->id, $obj->obra_id, $obj->grupo_id, $obj->subgrupo1_id, $obj->subgrupo2_id, $obj->subgrupo3_id, $obj->servico_id, this.value)'>";
                }
            })
            ->editColumn('total', function($obj){
                if($obj->quantidade_compra) {
                    if($obj->quantidade_compra && $obj->total === 1) {
                        return "<input type='checkbox' value='1' checked onchange='totalCompra($obj->id, $obj->obra_id, $obj->grupo_id, $obj->subgrupo1_id, $obj->subgrupo2_id, $obj->subgrupo3_id, $obj->servico_id, this.value)'>";
                    }elseif($obj->quantidade_compra){
                        return "<input type='checkbox' value='0' data-toggle='tooltip' data-placement='top' title='$obj->motivo_nao_finaliza_obra' onchange='totalCompra($obj->id, $obj->obra_id, $obj->grupo_id, $obj->subgrupo1_id, $obj->subgrupo2_id, $obj->subgrupo3_id, $obj->servico_id, this.value)'>";
                    }
                }
            })
            ->editColumn('troca', function ($obj) {
                if($obj->substitui) {
                    return '<button data-toggle="popover" title="Substitui Insumo" data-content="' . $obj->substitui . '" type="button" data-placement="left" class="btn btn-info btn-flat btn-xs"> <i class="fa fa-exchange"></i> </button>';
                }
                
                if ($obj->insumo_grupo_id == 1570) {
                    return link_to(
                        'compras/trocar/' . $obj->orcamento_id,
                        '<i class="fa fa-exchange"></i>',
                        [ 'class' => 'btn btn-ms btn-primary btn-flat' ],
                        null,
                        false
                    );
                }
            })
            ->editColumn('codigo', function ($obj) {
                if($obj->substitui){
                    return "<strong  data-toggle=\"tooltip\" data-placement=\"top\" data-html=\"true\"
                    title=\"". $obj->tooltip_grupo . ' <br> ' .
                    $obj->tooltip_subgrupo1 . ' <br> ' .
                    $obj->tooltip_subgrupo2 . ' <br> ' .
                    $obj->tooltip_subgrupo3 . ' <br> ' .
                    $obj->tooltip_servico . ' <br> <i class=\'fa fa-exchange\'></i> ' . $obj->substitui .
                    "\">
                    $obj->codigo
                    </strong>";
                } else {
                    return "<strong  data-toggle=\"tooltip\" data-placement=\"top\" data-html=\"true\"
                    title=\"". $obj->tooltip_grupo . ' <br> ' .
                    $obj->tooltip_subgrupo1 . ' <br> ' .
                    $obj->tooltip_subgrupo2 . ' <br> ' .
                    $obj->tooltip_subgrupo3 . ' <br> ' .
                    $obj->tooltip_servico  ."\">
                    $obj->codigo
                    </strong>";
                }
            })
            ->editColumn('nome', function ($obj) {
                if($obj->substitui){
                    return "<strong  data-toggle=\"tooltip\" data-placement=\"top\" data-html=\"true\"
                    title=\"". $obj->tooltip_grupo . ' <br> ' .
                    $obj->tooltip_subgrupo1 . ' <br> ' .
                    $obj->tooltip_subgrupo2 . ' <br> ' .
                    $obj->tooltip_subgrupo3 . ' <br> ' .
                    $obj->tooltip_servico . ' <br> <i class=\'fa fa-exchange\'></i> ' . $obj->substitui .
                    "\">
                    $obj->nome
                    </strong>";
                } else {
                    return "<strong  data-toggle=\"tooltip\" data-placement=\"top\" data-html=\"true\"
                    title=\"". $obj->tooltip_grupo . ' <br> ' .
                    $obj->tooltip_subgrupo1 . ' <br> ' .
                    $obj->tooltip_subgrupo2 . ' <br> ' .
                    $obj->tooltip_subgrupo3 . ' <br> ' .
                    $obj->tooltip_servico  ."\">
                    $obj->nome
                    </strong>";
                }
            })
            ->editColumn('valor_total', function($obj){
                $insumo_catalogo = OrdemDeCompraRepository::existeNoCatalogo($obj->id, $obj->obra_id);
                $pedido_minimo_invalido = false;
                $multiplo_invalido = false;

                if($insumo_catalogo) {
                    if(money_to_float($obj->quantidade_compra) < money_to_float($insumo_catalogo->pedido_minimo)) {
                        $pedido_minimo_invalido = true;
                    }


                    if(fmod(money_to_float($obj->quantidade_compra), money_to_float($insumo_catalogo->pedido_multiplo_de))) {
                        $multiplo_invalido = true;
                    }

                    if(!$pedido_minimo_invalido && !$multiplo_invalido) {
                        $obj->preco_unitario = $insumo_catalogo->valor_unitario;
                    }
                }

                return $obj->quantidade_compra ? "R$ ".number_format($obj->preco_unitario * money_to_float($obj->quantidade_compra), 2,',','.') : 'R$ 0,00';
            })
            ->editColumn('preco_unitario', function($obj){
                $insumo_catalogo = OrdemDeCompraRepository::existeNoCatalogo($obj->id, $obj->obra_id);
                $pedido_minimo_invalido = false;
                $multiplo_invalido = false;

                if($insumo_catalogo) {
                    if(money_to_float($obj->quantidade_compra) < money_to_float($insumo_catalogo->pedido_minimo)) {
                        $pedido_minimo_invalido = true;
                    }


                    if(fmod(money_to_float($obj->quantidade_compra), money_to_float($insumo_catalogo->pedido_multiplo_de))) {
                        $multiplo_invalido = true;
                    }

                    if(!$pedido_minimo_invalido && !$multiplo_invalido) {
                        $obj->preco_unitario = $insumo_catalogo->valor_unitario;
                    }
                }

                if($obj->insumo_incluido || $obj->orcamento_que_substitui) {
                    if($insumo_catalogo) {
                        $preco_unitario = float_to_money($obj->preco_unitario). '<button type="button" title="
                        <b>Origem:</b> Catálogo '.$insumo_catalogo->id.'<br>'.
                        '<b>Valor unitário:</b> '.float_to_money($insumo_catalogo->valor_unitario).'<br>'.
                        '<b>Pedido mínimo:</b> '.float_to_money($insumo_catalogo->pedido_minimo, '').
                        '<br> <b>Pedido múltiplo de:</b> '.float_to_money($insumo_catalogo->pedido_multiplo_de, '').'
                        " data-toggle="tooltip" data-placement="top" data-html="true" class="btn btn-primary btn-sm" style="border-radius: 9px !important;width: 20px;height: 20px;padding: 0px;margin-left: 5px;">
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                             </button>';

                        return $preco_unitario;
                    } else {
                        return "<div class='input-group'>
                                <span class='input-group-addon'>R$</span>
                                <input type='text' value='".number_format($obj->preco_unitario,2,',','.')."' class='form-control
                                        js-blur-on-enter money' onblur='alteraValorUnitario(this.value,
                                                                                            $obj->id,
                                                                                            $obj->grupo_id,
                                                                                            $obj->subgrupo1_id,
                                                                                            $obj->subgrupo2_id,
                                                                                            $obj->subgrupo3_id,
                                                                                            $obj->servico_id)'>
                            </div>";
                    }
                }else{
                    if($insumo_catalogo) {
                        $preco_unitario = float_to_money($obj->preco_unitario). '<button type="button" title="
                        <b>Origem:</b> Catálogo '.$insumo_catalogo->id.'<br>'.
                        '<b>Valor unitário:</b> '.float_to_money($insumo_catalogo->valor_unitario).'<br>'.
                        '<b>Pedido mínimo:</b> '.float_to_money($insumo_catalogo->pedido_minimo, '').
                        '<br> <b>Pedido múltiplo de:</b> '.float_to_money($insumo_catalogo->pedido_multiplo_de, '').'
                        " data-toggle="tooltip" data-placement="top" data-html="true" class="btn btn-primary btn-sm" style="border-radius: 9px !important;width: 20px;height: 20px;padding: 0px;margin-left: 5px;">
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                             </button>';
                    } else {
                        $preco_unitario = float_to_money($obj->preco_unitario);
                    }
                    return $preco_unitario;
                }
            })
            ->filterColumn('nome',function($query, $keyword){
                $query->where(DB::raw("CONCAT(insumos.codigo,' - ' ,insumos.nome)"),'LIKE','%'.$keyword.'%');
            })
            ->make(true);

    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $insumo_query = Insumo::query();
        # OBRA
        $obra = Obra::find($this->request()->get('obra_id'));
        $insumos = $insumo_query->join('orcamentos', 'orcamentos.insumo_id', '=', 'insumos.id')
            ->where('orcamentos.obra_id', $this->request()->get('obra_id'))
            ->where('orcamentos.ativo', 1);

        // Verificar se existe OC aberta deste usuário ou se ele está editando alguma OC (SESSÃO)

        #dando prioridade a sessão
        $ordem = null;
//        \Session::forget('ordemCompra');
//        \Session::flush('ordemCompra');
//        dd(\Session::get('ordemCompra'));
        if(\Session::get('ordemCompra')){
            $ordem = OrdemDeCompra::where('id', \Session::get('ordemCompra'))
                ->where('oc_status_id', 1)
                ->where('user_id', Auth::user()->id)
                ->where('obra_id', $obra->id)->first();
        }else {
            $ordem = OrdemDeCompra::where('oc_status_id', 1)
                ->where('user_id', Auth::user()->id)
                ->where('obra_id', $obra->id)->first();
        }

        $insumos->select(
            [
                'insumos.id',
                'insumos.codigo',
                'insumos.nome',
                DB::raw("format(orcamentos.qtd_total,2,'de_DE') as qtd_total"),
                DB::raw("CONCAT(insumos_sub.codigo,' - ' ,insumos_sub.nome) as substitui"),
                'orcamentos.id as orcamento_id',
                'insumos.unidade_sigla',
                'insumos.insumo_grupo_id',
                'orcamentos.obra_id',
                'orcamentos.grupo_id',
                'orcamentos.subgrupo1_id',
                'orcamentos.subgrupo2_id',
                'orcamentos.subgrupo3_id',
                'orcamentos.servico_id',
                'orcamentos.preco_total',
                'orcamentos.preco_unitario',
                'orcamentos.insumo_incluido',
                'orcamentos.orcamento_que_substitui',
                DB::raw('(SELECT
                    CONCAT(codigo, \' - \', nome)
                    FROM
                    grupos
                    WHERE
                    orcamentos.grupo_id = grupos.id) AS tooltip_grupo'),
                DB::raw('(SELECT
                    CONCAT(codigo, \' - \', nome)
                    FROM
                    grupos
                    WHERE
                    orcamentos.subgrupo1_id = grupos.id) AS tooltip_subgrupo1'),
                DB::raw('(SELECT
                    CONCAT(codigo, \' - \', nome)
                    FROM
                    grupos
                    WHERE
                    orcamentos.subgrupo2_id = grupos.id) AS tooltip_subgrupo2'),
                DB::raw('(SELECT
                    CONCAT(codigo, \' - \', nome)
                    FROM
                    grupos
                    WHERE
                    orcamentos.subgrupo3_id = grupos.id) AS tooltip_subgrupo3'),
                DB::raw('(SELECT
                    CONCAT(codigo, \' - \', nome)
                    FROM
                    servicos
                    WHERE
                    orcamentos.servico_id = servicos.id) AS tooltip_servico'),
                DB::raw('format((
                        orcamentos.qtd_total 
                        -
                        (
                            IFNULL(
                                (
                                    SELECT sum(ordem_de_compra_itens.qtd) FROM ordem_de_compra_itens
                                    JOIN ordem_de_compras
                                    ON ordem_de_compra_itens.ordem_de_compra_id = ordem_de_compras.id
                                    WHERE ordem_de_compra_itens.insumo_id = orcamentos.insumo_id
                                    AND ordem_de_compra_itens.grupo_id = orcamentos.grupo_id
                                    AND ordem_de_compra_itens.subgrupo1_id = orcamentos.subgrupo1_id
                                    AND ordem_de_compra_itens.subgrupo2_id = orcamentos.subgrupo2_id
                                    AND ordem_de_compra_itens.subgrupo3_id = orcamentos.subgrupo3_id
                                    AND ordem_de_compra_itens.servico_id = orcamentos.servico_id
                                    AND (
                                            ordem_de_compra_itens.aprovado IS NULL
                                            OR
                                            ordem_de_compra_itens.aprovado = 1
                                        )
                                    AND ordem_de_compra_itens.deleted_at IS NULL
                                    AND ordem_de_compra_itens.data_dispensa IS NULL
                                    AND ordem_de_compras.obra_id ='. $obra->id .'
                                    AND ordem_de_compras.oc_status_id != 6
                                    AND ordem_de_compras.oc_status_id != 4
                                ),0
                            )
                        )
                        -
                        (
                            IFNULL(
                                IFNULL(
                                    (
                                        SELECT sum(ordem_de_compra_itens.valor_total) FROM ordem_de_compra_itens
                                        JOIN ordem_de_compras
                                        ON ordem_de_compra_itens.ordem_de_compra_id = ordem_de_compras.id
                                        WHERE 
                                            ordem_de_compra_itens.insumo_id in
                                            (
                                                SELECT 
                                                    insumo_id
                                                FROM
                                                    orcamentos as OrcSub
                                                WHERE
                                                    OrcSub.orcamento_que_substitui = orcamentos.id
                                            )
                                        AND ordem_de_compra_itens.grupo_id = orcamentos.grupo_id
                                        AND ordem_de_compra_itens.subgrupo1_id = orcamentos.subgrupo1_id
                                        AND ordem_de_compra_itens.subgrupo2_id = orcamentos.subgrupo2_id
                                        AND ordem_de_compra_itens.subgrupo3_id = orcamentos.subgrupo3_id
                                        AND ordem_de_compra_itens.servico_id = orcamentos.servico_id
                                        AND (
                                                ordem_de_compra_itens.aprovado IS NULL
                                                OR
                                                ordem_de_compra_itens.aprovado = 1
                                            )
                                        AND ordem_de_compra_itens.data_dispensa IS NULL
                                        AND ordem_de_compra_itens.deleted_at IS NULL
                                        AND ordem_de_compras.obra_id ='. $obra->id .'
                                        AND ordem_de_compras.oc_status_id != 6
                                        AND ordem_de_compras.oc_status_id != 4
                                    ),0
                                ),0
                                
                                / orcamentos.preco_unitario
                            
                            )
                        )
                    ),2,\'de_DE\') as saldo'),
                // Colocar a OC se existir em aberto ou em sessão
                DB::raw('format((
                        SELECT ordem_de_compra_itens.qtd FROM ordem_de_compra_itens
                        JOIN ordem_de_compras
                        ON ordem_de_compra_itens.ordem_de_compra_id = ordem_de_compras.id
                        WHERE ordem_de_compra_itens.insumo_id = insumos.id
                        AND ordem_de_compra_itens.grupo_id = orcamentos.grupo_id
                        AND ordem_de_compra_itens.subgrupo1_id = orcamentos.subgrupo1_id
                        AND ordem_de_compra_itens.subgrupo2_id = orcamentos.subgrupo2_id
                        AND ordem_de_compra_itens.subgrupo3_id = orcamentos.subgrupo3_id
                        AND ordem_de_compra_itens.servico_id = orcamentos.servico_id
                        AND (
                                ordem_de_compra_itens.aprovado IS NULL
                                OR
                                ordem_de_compra_itens.aprovado = 0
                            )
                        AND ordem_de_compra_itens.deleted_at IS NULL
                        AND ordem_de_compras.obra_id ='. $obra->id .'
                        AND ordem_de_compras.oc_status_id = 1
                        '.($ordem ? ' AND ordem_de_compras.id ='. $ordem->id .' ': 'AND ordem_de_compras.id = 0').'
                    ),2,\'de_DE\') as quantidade_compra'),
                DB::raw('format((
                        SELECT ordem_de_compra_itens.qtd FROM ordem_de_compra_itens
                        JOIN ordem_de_compras
                        ON ordem_de_compra_itens.ordem_de_compra_id = ordem_de_compras.id
                        WHERE ordem_de_compra_itens.insumo_id = insumos.id
                        AND ordem_de_compra_itens.grupo_id = orcamentos.grupo_id
                        AND ordem_de_compra_itens.subgrupo1_id = orcamentos.subgrupo1_id
                        AND ordem_de_compra_itens.subgrupo2_id = orcamentos.subgrupo2_id
                        AND ordem_de_compra_itens.subgrupo3_id = orcamentos.subgrupo3_id
                        AND ordem_de_compra_itens.servico_id = orcamentos.servico_id
                        AND (
                                ordem_de_compra_itens.aprovado IS NULL
                                OR
                                ordem_de_compra_itens.aprovado = 0
                            )
                        AND ordem_de_compra_itens.deleted_at IS NULL
                        AND ordem_de_compras.obra_id ='. $obra->id .'
                        AND ordem_de_compras.oc_status_id = 1
                        '.($ordem ? ' AND ordem_de_compras.id ='. $ordem->id .' ': 'AND ordem_de_compras.id = 0').'
                    ),2,\'de_DE\') as quantidade_comprada'),
                DB::raw('(SELECT total FROM ordem_de_compra_itens
                    JOIN ordem_de_compras
                    ON ordem_de_compra_itens.ordem_de_compra_id = ordem_de_compras.id
                    WHERE ordem_de_compra_itens.insumo_id = insumos.id
                    AND ordem_de_compra_itens.grupo_id = orcamentos.grupo_id
                    AND ordem_de_compra_itens.subgrupo1_id = orcamentos.subgrupo1_id
                    AND ordem_de_compra_itens.subgrupo2_id = orcamentos.subgrupo2_id
                    AND ordem_de_compra_itens.subgrupo3_id = orcamentos.subgrupo3_id
                    AND ordem_de_compra_itens.servico_id = orcamentos.servico_id
                    AND (
                            ordem_de_compra_itens.aprovado IS NULL
                            OR
                            ordem_de_compra_itens.aprovado = 0
                        )
                    AND ordem_de_compra_itens.aprovado IS NULL
                    AND ordem_de_compra_itens.deleted_at IS NULL
                    AND ordem_de_compras.oc_status_id = 1
                    '.($ordem ? ' AND ordem_de_compras.id ='. $ordem->id .' ': 'AND ordem_de_compras.id = 0').'
                    AND ordem_de_compra_itens.obra_id ='. $obra->id .' ) as total'),
                DB::raw('(SELECT motivo_nao_finaliza_obra FROM ordem_de_compra_itens
                    JOIN ordem_de_compras
                    ON ordem_de_compra_itens.ordem_de_compra_id = ordem_de_compras.id
                    WHERE ordem_de_compra_itens.insumo_id = insumos.id
                    AND ordem_de_compra_itens.grupo_id = orcamentos.grupo_id
                    AND ordem_de_compra_itens.subgrupo1_id = orcamentos.subgrupo1_id
                    AND ordem_de_compra_itens.subgrupo2_id = orcamentos.subgrupo2_id
                    AND ordem_de_compra_itens.subgrupo3_id = orcamentos.subgrupo3_id
                    AND ordem_de_compra_itens.servico_id = orcamentos.servico_id
                    AND (
                            ordem_de_compra_itens.aprovado IS NULL
                            OR
                            ordem_de_compra_itens.aprovado = 0
                        )
                    AND ordem_de_compra_itens.aprovado IS NULL
                    AND ordem_de_compra_itens.deleted_at IS NULL
                    AND ordem_de_compras.oc_status_id = 1
                    '.($ordem ? ' AND ordem_de_compras.id ='. $ordem->id .' ': 'AND ordem_de_compras.id = 0').'
                    AND ordem_de_compra_itens.obra_id ='. $obra->id .' ) as motivo_nao_finaliza_obra'),
            ]
        )
            ->whereNotNull('orcamentos.qtd_total')
            ->where(DB::raw('IFNULL(
                                (SELECT
                                    ordem_de_compras.oc_status_id
                                FROM
                                    ordem_de_compra_itens
                                JOIN
                                    ordem_de_compras ON ordem_de_compra_itens.ordem_de_compra_id = ordem_de_compras.id
                                WHERE
                                    ordem_de_compra_itens.insumo_id = orcamentos.insumo_id
                                        AND ordem_de_compra_itens.grupo_id = orcamentos.grupo_id
                                        AND ordem_de_compra_itens.subgrupo1_id = orcamentos.subgrupo1_id
                                        AND ordem_de_compra_itens.subgrupo2_id = orcamentos.subgrupo2_id
                                        AND ordem_de_compra_itens.subgrupo3_id = orcamentos.subgrupo3_id
                                        AND ordem_de_compra_itens.servico_id = orcamentos.servico_id
                                        AND (ordem_de_compra_itens.aprovado IS NULL
                                        OR ordem_de_compra_itens.aprovado = 1)
                                        AND ordem_de_compra_itens.deleted_at IS NULL
                                        AND ordem_de_compras.obra_id = '. $obra->id .'
                                        AND ordem_de_compras.oc_status_id != 6
                                        AND ordem_de_compras.oc_status_id != 4
                                        AND orcamentos.orcamento_que_substitui IS NOT NULL
                                LIMIT 1
                                        )
                            , 1)'), 1)
            ->where('orcamentos.ativo', 1);

        $insumo_query->leftJoin(DB::raw('orcamentos orcamentos_sub'),  'orcamentos_sub.id', 'orcamentos.orcamento_que_substitui');
        //ex: left join insumos insumos_sub on `insumos_sub`.`id` = `orcamentos_sub`.`insumo_id` 
		$insumo_query->leftJoin(DB::raw('insumos insumos_sub'), 'insumos_sub.id', 'orcamentos_sub.insumo_id');
		$insumo_query->leftJoin(DB::raw('carteira_insumos'), 'insumos.id', 'carteira_insumos.insumo_id');
		$insumo_query->leftJoin(DB::raw('carteiras'), 'carteiras.id', 'carteira_insumos.carteira_id');

        if ($this->request()->get('grupo_id')) {
            if (count($this->request()->get('grupo_id')) && $this->request()->get('grupo_id')[0] != "") {
                $insumo_query->where('orcamentos.grupo_id', $this->request()->get('grupo_id'));
            }
        }

        if($this->request()->get('subgrupo1_id')){
            if(count($this->request()->get('subgrupo1_id')) && $this->request()->get('subgrupo1_id')[0] != "") {
                $insumo_query->where('orcamentos.subgrupo1_id', $this->request()->get('subgrupo1_id'));
            }
        }
        if($this->request()->get('subgrupo2_id')){
            if(count($this->request()->get('subgrupo2_id')) && $this->request()->get('subgrupo2_id')[0] != "") {
                $insumo_query->where('orcamentos.subgrupo2_id', $this->request()->get('subgrupo2_id'));
            }
        }
        if($this->request()->get('subgrupo3_id')){
            if(count($this->request()->get('subgrupo3_id')) && $this->request()->get('subgrupo3_id')[0] != "") {
                $insumo_query->where('orcamentos.subgrupo3_id', $this->request()->get('subgrupo3_id'));
            }
        }
        if($this->request()->get('servico_id')){
            if(count($this->request()->get('servico_id')) && $this->request()->get('servico_id')[0] != "") {
                $insumo_query->where('orcamentos.servico_id', $this->request()->get('servico_id'));
            }
        }
        if($this->request()->get('planejamento_id')){
            if(count($this->request()->get('planejamento_id')) && $this->request()->get('planejamento_id')[0] != "") {
                $insumo_query->join('planejamento_compras', function ($join) {
                    $join->on('planejamento_compras.grupo_id', 'orcamentos.grupo_id');
                    $join->on('planejamento_compras.subgrupo1_id', 'orcamentos.subgrupo1_id');
                    $join->on('planejamento_compras.subgrupo2_id', 'orcamentos.subgrupo2_id');
                    $join->on('planejamento_compras.subgrupo3_id', 'orcamentos.subgrupo3_id');
                    $join->on('planejamento_compras.servico_id', 'orcamentos.servico_id');
                    $join->on('planejamento_compras.insumo_id', 'orcamentos.insumo_id');
                })
                    ->where('planejamento_compras.planejamento_id', $this->request()->get('planejamento_id'))
                    ->whereNull('planejamento_compras.deleted_at');
            }
        }
		
        if($this->request()->get('insumo_grupos_id')){
            if(count($this->request()->get('insumo_grupos_id')) && $this->request()->get('insumo_grupos_id')[0] != "") {
                $insumo_query->where('insumos.insumo_grupo_id', $this->request()->get('insumo_grupos_id'));
            }
        }
		
		if($this->request()->get('carteira_id')){
            if(count($this->request()->get('carteira_id')) && $this->request()->get('carteira_id')[0] != "") {
                $insumo_query->where('carteiras.id', $this->request()->get('carteira_id'));
            }
        }

//		if($this->request()->get('exibir_por_carteira')){
//            if(count($this->request()->get('exibir_por_carteira')) && $this->request()->get('exibir_por_carteira')[0] != "") {
//                $insumo_query->groupBy('carteiras.nome');
//            }
//        }

        Session::put(['query['.$this->request()->get('random').']' => $insumo_query->toSql(), 'bindings['.$this->request()->get('random').']' => $insumo_query->getBindings()]);

        return $this->applyScopes($insumo_query);
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
                        if((col+5)<max){
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
            "url"=> asset("vendor/datatables/Portuguese-Brasil.json")
        ],
        'buttons' => [
        ]
    ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'código' => ['name' => 'codigo', 'data' => 'codigo'],
            'descrição' => ['name' => 'nome', 'data' => 'nome'],
            'un&period; De Medida' => ['name' => 'unidade_sigla', 'data' => 'unidade_sigla'],
            'Qtd_de_orçamento' => ['name' => 'orcamentos.qtd_total', 'data' => 'qtd_total'],
            'saldo_de_qtd' => ['name' => 'orcamentos.qtd_total', 'data' => 'saldo'],
            'quantidade Compra' => ['name' => 'quantidade_compra', 'data' => 'quantidade_compra', 'searchable' => false, 'width'=>'8%'],
            'preço Unitário' => ['name' => 'orcamentos.preco_unitario', 'data' => 'preco_unitario'],
            'preço Total' => ['name' => 'valor_total', 'data' => 'valor_total', 'searchable' => false],
            'troca' => ['name' => 'troca', 'data' => 'troca', 'searchable' => false, 'orderable' => false, 'width'=>'5%'],
            'finaliza Obra' => ['name' => 'total', 'data' => 'total', 'searchable' => false, 'orderable' => false, 'width'=>'5%'],
            'action' => ['title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'5%']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'compras_' . time();
    }
}
