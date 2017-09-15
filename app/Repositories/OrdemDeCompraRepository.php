<?php

namespace App\Repositories;

use App\Models\CatalogoContrato;
use App\Models\Contrato;
use App\Models\ContratoItemApropriacao;
use App\Models\Obra;
use App\Models\Orcamento;
use App\Models\OrdemDeCompra;
use App\Models\OrdemDeCompraItem;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use InfyOm\Generator\Common\BaseRepository;

class OrdemDeCompraRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'oc_status_id',
        'obra_id',
        'aprovado'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return OrdemDeCompra::class;
    }
    
    public static function valorPrevistoOrcamento($ordem_de_compra_id, $obra_id)
    {
        $orcamentos_iniciais = OrdemDeCompraItem::select([
            '*',
            DB::raw("(
                        SELECT
                        SUM(orcamentos.preco_total)
                        FROM
                        orcamentos
                        WHERE
                        orcamentos.grupo_id = ordem_de_compra_itens.grupo_id
                        AND orcamentos.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                        AND orcamentos.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                        AND orcamentos.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                        AND orcamentos.servico_id = ordem_de_compra_itens.servico_id
                        AND orcamentos.obra_id = ordem_de_compra_itens.obra_id
                        AND orcamentos.orcamento_que_substitui IS NULL
                        AND orcamentos.ativo = 1

                    ) as valor_previsto_orcamento_pai"),
        ])
            ->where('ordem_de_compra_id', $ordem_de_compra_id)
            ->join('orcamentos', function ($join) use ($obra_id) {
                $join->on('orcamentos.insumo_id', '=', 'ordem_de_compra_itens.insumo_id');
                $join->on('orcamentos.grupo_id', '=', 'ordem_de_compra_itens.grupo_id');
                $join->on('orcamentos.subgrupo1_id', '=', 'ordem_de_compra_itens.subgrupo1_id');
                $join->on('orcamentos.subgrupo2_id', '=', 'ordem_de_compra_itens.subgrupo2_id');
                $join->on('orcamentos.subgrupo3_id', '=', 'ordem_de_compra_itens.subgrupo3_id');
                $join->on('orcamentos.servico_id', '=', 'ordem_de_compra_itens.servico_id');
                $join->on('orcamentos.obra_id', '=', DB::raw($obra_id));
                $join->on('orcamentos.ativo', '=', DB::raw('1'));
            });
        $orcamentoInicial = $orcamentos_iniciais->sum('orcamentos.preco_total');
        
        // Se os itens do orçamento for trocado pega o valor do pai
        $array_orcamentos_substitui = [];

        if(count($orcamentos_iniciais->get())) {
            foreach($orcamentos_iniciais->get() as $orcamento) {
                if ($orcamento->orcamento_que_substitui) {
                    if (!in_array($orcamento->orcamento_que_substitui, $array_orcamentos_substitui)) {
                        array_push($array_orcamentos_substitui, $orcamento->orcamento_que_substitui);
                        $orcamentoInicial += $orcamento->valor_previsto_orcamento_pai;
                    }
                }
            }
        }
        
        return $orcamentoInicial;
    }

    public static function saldoDisponivel($ordem_de_compra_id, $obra_id)
    {
        $orcamentoInicial = self::valorPrevistoOrcamento($ordem_de_compra_id, $obra_id);

        $ordemDeCompra = OrdemDeCompra::find($ordem_de_compra_id);

        $realizado = 0;
        $valor_comprometido_a_gastar = 0;

        if($ordemDeCompra) {
            if(count($ordemDeCompra->itens())) {
//                $realizado = OrdemDeCompraItem::join('ordem_de_compras', 'ordem_de_compras.id', '=', 'ordem_de_compra_itens.ordem_de_compra_id')
//                    ->where('ordem_de_compras.obra_id', $obra_id)
//                    ->whereIn('oc_status_id', [2,3,5])
//                    ->whereIn('ordem_de_compra_itens.insumo_id', $ordemDeCompra->itens()->pluck('insumo_id', 'insumo_id')->toArray())
//                    ->sum('ordem_de_compra_itens.valor_total');
                $realizado = $ordemDeCompra->itens()->sum('valor_total');

                foreach($ordemDeCompra->itens()->get() as $item) {
                    $valor_comprometido_a_gastar += OrdemDeCompraRepository::valorComprometidoAGastarItem($item->grupo_id, $item->subgrupo1_id, $item->subgrupo2_id, $item->subgrupo3_id, $item->servico_id, $item->insumo_id, $item->obra_id, $item->id, $item->ordemDeCompra->dataUltimoPeriodoAprovacao());
                }
            }
        }

        $saldoDisponivel = doubleval($orcamentoInicial) - $valor_comprometido_a_gastar - doubleval($realizado);

        if($ordemDeCompra->saldo_disponivel_temp !== $saldoDisponivel){
            $ordemDeCompra->saldo_disponivel_temp = $saldoDisponivel;
            $ordemDeCompra->save();
        }

        return $saldoDisponivel;
    }
    
    public static function valorComprometidoAGastarItem($grupo_id, $subgrupo1_id, $subgrupo2_id, $subgrupo3_id, $servico_id, $insumo_id, $obra_id, $item_id = null, $ordem_de_compra_ultima_aprovacao = null)
    {
        $valores_comprometido_a_gastar = ContratoItemApropriacao::select([
            'contrato_item_apropriacoes.id',
            'contratos.id as contrato_id',
            DB::raw('(contrato_item_apropriacoes.qtd * contrato_itens.valor_unitario) as valor_comprometido_a_gastar')
            ])
            ->join('contrato_itens', 'contrato_itens.id' ,'=', 'contrato_item_apropriacoes.contrato_item_id')
            ->join('contratos', 'contratos.id' ,'=', 'contrato_itens.contrato_id')
            ->where('contrato_item_apropriacoes.grupo_id', $grupo_id)
            ->where('contrato_item_apropriacoes.subgrupo1_id', $subgrupo1_id)
            ->where('contrato_item_apropriacoes.subgrupo2_id', $subgrupo2_id)
            ->where('contrato_item_apropriacoes.subgrupo3_id', $subgrupo3_id)
            ->where('contrato_item_apropriacoes.servico_id', $servico_id)
            ->where('contrato_item_apropriacoes.insumo_id', $insumo_id)
            ->where('contratos.obra_id', $obra_id);

        if($item_id){
            $valores_comprometido_a_gastar = $valores_comprometido_a_gastar->whereRaw('NOT EXISTS(
                SELECT 1 
                FROM contrato_itens CI
                JOIN oc_item_qc_item OCQC ON OCQC.qc_item_id = CI.qc_item_id
                WHERE CI.id = contrato_item_apropriacoes.contrato_item_id
                AND OCQC.ordem_de_compra_item_id = '.$item_id.'
            )');
        }

        if($ordem_de_compra_ultima_aprovacao) {
            $valores_comprometido_a_gastar = $valores_comprometido_a_gastar->where('contrato_item_apropriacoes.created_at', '<', $ordem_de_compra_ultima_aprovacao);
        }

        $valores_comprometido_a_gastar = $valores_comprometido_a_gastar->get();

        $valor_comprometido_a_gastar = 0;
        $valor_apropriacoes = 0;

        if(count($valores_comprometido_a_gastar)){
            foreach ($valores_comprometido_a_gastar as $valor) {
                $valor_comprometido_a_gastar += $valor->valor_comprometido_a_gastar;

                //Valor das apropriações
                $contrato = Contrato::find($valor->contrato_id);

                $itens = ContratoItemApropriacaoRepository::forContratoApproval($contrato);

                if($itens->contrato_itens->isNotEmpty()) {
                    foreach ($itens->contrato_itens as $c_item) {
                        $valor_apropriacoes += $c_item->apropriacoes
                            ->where('grupo_id', $grupo_id)
                            ->where('subgrupo1_id', $subgrupo1_id)
                            ->where('subgrupo2_id', $subgrupo2_id)
                            ->where('subgrupo3_id', $subgrupo3_id)
                            ->where('servico_id', $servico_id)
                            ->sum('qtd');
                    }
                    $valor_comprometido_a_gastar += $valor_apropriacoes;
                }
                //Valor das apropriações
            }
        }

        return $valor_comprometido_a_gastar;
    }

    public static function qtdComprometidaAGastarItem($grupo_id, $subgrupo1_id, $subgrupo2_id, $subgrupo3_id, $servico_id, $insumo_id, $obra_id, $item_id = null, $ordem_de_compra_ultima_aprovacao = null)
    {
        $qtds_comprometida_a_gastar = ContratoItemApropriacao::select([
            'contrato_item_apropriacoes.id',
            'contrato_item_apropriacoes.qtd'
        ])
            ->join('contrato_itens', 'contrato_itens.id' ,'=', 'contrato_item_apropriacoes.contrato_item_id')
            ->join('contratos', 'contratos.id' ,'=', 'contrato_itens.contrato_id')
            ->where('contrato_item_apropriacoes.insumo_id', $insumo_id)
            ->where('contrato_item_apropriacoes.grupo_id', $grupo_id)
            ->where('contrato_item_apropriacoes.subgrupo1_id', $subgrupo1_id)
            ->where('contrato_item_apropriacoes.subgrupo2_id', $subgrupo2_id)
            ->where('contrato_item_apropriacoes.subgrupo3_id', $subgrupo3_id)
            ->where('contrato_item_apropriacoes.servico_id', $servico_id)
            ->where('contratos.obra_id', $obra_id);

        if($item_id){
            $qtds_comprometida_a_gastar->whereRaw('NOT EXISTS(
                SELECT 1 
                FROM contrato_itens CI
                JOIN oc_item_qc_item OCQC ON OCQC.qc_item_id = CI.qc_item_id
                WHERE CI.id = contrato_item_apropriacoes.contrato_item_id
                AND OCQC.ordem_de_compra_item_id = '.$item_id.'
            )');
        }

        if($ordem_de_compra_ultima_aprovacao) {
            $qtds_comprometida_a_gastar = $qtds_comprometida_a_gastar->where('contrato_item_apropriacoes.created_at', '<', $ordem_de_compra_ultima_aprovacao);
        }
        
        $qtds_comprometida_a_gastar = $qtds_comprometida_a_gastar->get();

        $qtd_comprometida_a_gastar = 0;

        if(count($qtds_comprometida_a_gastar)){
            foreach ($qtds_comprometida_a_gastar as $valor) {
                $qtd_comprometida_a_gastar += $valor->qtd;
            }
        }

        return $qtd_comprometida_a_gastar;
    }

    public static function existeNoCatalogo($insumo_id, $obra_id)
    {
        $obra = Obra::find($obra_id);

        $insumo_catalogo = CatalogoContrato::select(
            'valor_unitario',
            'pedido_minimo',
            'pedido_multiplo_de',
            DB::raw("DATE_FORMAT(periodo_termino,'%d/%m/%Y') as periodo_termino")
        )
            ->join('catalogo_contrato_insumos', 'catalogo_contrato_insumos.catalogo_contrato_id', '=', 'catalogo_contratos.id')
            ->join('catalogo_contrato_regional', 'catalogo_contrato_regional.catalogo_contrato_id', '=', 'catalogo_contratos.id')
            ->where('insumo_id', $insumo_id)
            ->where('regional_id', $obra->regional_id)
            ->where('periodo_inicio', '<=', date('Y-m-d'))
            ->where('periodo_termino', '>=', date('Y-m-d'))
            ->where('catalogo_contratos.catalogo_contrato_status_id', 3) //ATIVO
            ->first();

        return $insumo_catalogo;
    }

    public static function origemComprometidoAGastar($grupo_id, $subgrupo1_id, $subgrupo2_id, $subgrupo3_id, $servico_id, $insumo_id, $obra_id, $item_id = null, $ordem_de_compra_ultima_aprovacao = null)
    {
        $valores_comprometido_a_gastar = ContratoItemApropriacao::select([
            'contrato_item_apropriacoes.id',
            'contratos.id as contrato_id',
            DB::raw('(contrato_item_apropriacoes.qtd * contrato_itens.valor_unitario) as valor_comprometido_a_gastar')
        ])
            ->join('contrato_itens', 'contrato_itens.id' ,'=', 'contrato_item_apropriacoes.contrato_item_id')
            ->join('contratos', 'contratos.id' ,'=', 'contrato_itens.contrato_id')
            ->where('contrato_item_apropriacoes.grupo_id', $grupo_id)
            ->where('contrato_item_apropriacoes.subgrupo1_id', $subgrupo1_id)
            ->where('contrato_item_apropriacoes.subgrupo2_id', $subgrupo2_id)
            ->where('contrato_item_apropriacoes.subgrupo3_id', $subgrupo3_id)
            ->where('contrato_item_apropriacoes.servico_id', $servico_id)
            ->where('contrato_item_apropriacoes.insumo_id', $insumo_id)
            ->where('contratos.obra_id', $obra_id);

        if($item_id){
            $valores_comprometido_a_gastar = $valores_comprometido_a_gastar->whereRaw('NOT EXISTS(
                SELECT 1 
                FROM contrato_itens CI
                JOIN oc_item_qc_item OCQC ON OCQC.qc_item_id = CI.qc_item_id
                WHERE CI.id = contrato_item_apropriacoes.contrato_item_id
                AND OCQC.ordem_de_compra_item_id = '.$item_id.'
            )');
        }

        if($ordem_de_compra_ultima_aprovacao) {
            $valores_comprometido_a_gastar = $valores_comprometido_a_gastar->where('contrato_item_apropriacoes.created_at', '<', $ordem_de_compra_ultima_aprovacao);
        }

        $valores_comprometido_a_gastar = $valores_comprometido_a_gastar->get();

        $origem_comprometido_a_gastar = '';

        if(count($valores_comprometido_a_gastar)){
            foreach ($valores_comprometido_a_gastar as $valor) {
                if($valor->valor_comprometido_a_gastar > 0) {
                    $origem_comprometido_a_gastar .= '<b>Contrato ' . $valor->contrato_id . ' - </b>' . float_to_money($valor->valor_comprometido_a_gastar) . '<br>';
                }

                //Valor das apropriações
                $contrato = Contrato::find($valor->contrato_id);

                $itens = ContratoItemApropriacaoRepository::forContratoApproval($contrato);

                if($itens->contrato_itens->isNotEmpty()) {
                    foreach ($itens->contrato_itens as $c_item) {
                       $contrato_item_apropriacoes = $c_item->apropriacoes
                           ->where('grupo_id', $grupo_id)
                           ->where('subgrupo1_id', $subgrupo1_id)
                           ->where('subgrupo2_id', $subgrupo2_id)
                           ->where('subgrupo3_id', $subgrupo3_id)
                           ->where('servico_id', $servico_id);

                        if(count($contrato_item_apropriacoes)) {
                            foreach ($contrato_item_apropriacoes as $apropriacao) {
                                if($apropriacao->contratoItem) {
                                    if($apropriacao->qtd > 0) {
                                        $origem_comprometido_a_gastar .= '<b>Apropriação do contrato ' . $apropriacao->contratoItem->contrato_id . ' - </b>' . float_to_money($apropriacao->qtd) . '<br>';
                                    }
                                }
                            }
                        }
                    }
                }
                //Valor das apropriações
            }
        }

        return $origem_comprometido_a_gastar;
    }

    public static function origemValorOc(
        $orcamento_insumo_id,
        $orcamento_grupo_id,
        $orcamento_subgrupo1_id,
        $orcamento_subgrupo2_id,
        $orcamento_subgrupo3_id,
        $orcamento_servico_id,
        $obra_id,
        $servico_id,
        $ordem_de_compra_ultima_aprovacao,
        $oc_id)
    {
        $query = '(SELECT 
                        GROUP_CONCAT(
                            CONCAT("<b>O.C.",ordem_de_compras.id, " - </b>", FORMAT(ordem_de_compra_itens.valor_total, 2, "de_DE"), " ", unidade_sigla) SEPARATOR "<br>"
                        ) as oc_origem
                    FROM ordem_de_compra_itens
                    JOIN ordem_de_compras
                        ON ordem_de_compra_itens.ordem_de_compra_id = ordem_de_compras.id
                    WHERE ordem_de_compra_itens.insumo_id = '.$orcamento_insumo_id.'
                    AND ordem_de_compra_itens.grupo_id = '.$orcamento_grupo_id.'
                    AND ordem_de_compra_itens.subgrupo1_id = '.$orcamento_subgrupo1_id.'
                    AND ordem_de_compra_itens.subgrupo2_id = '.$orcamento_subgrupo2_id.'
                    AND ordem_de_compra_itens.subgrupo3_id = '.$orcamento_subgrupo3_id.'
                    AND ordem_de_compra_itens.servico_id = '.$orcamento_servico_id.'
                    AND (
                            ordem_de_compras.oc_status_id = 2
                            OR
                            ordem_de_compras.oc_status_id = 3                            
                            OR
                            ordem_de_compras.oc_status_id = 5
                        )
                    AND ordem_de_compra_itens.deleted_at IS NULL
                    '.($oc_id ? "AND ordem_de_compras.id = '".$oc_id."'" : 'AND NOT EXISTS(
                        SELECT 1 
                        FROM contrato_itens CI
                        JOIN contrato_item_apropriacoes CIT ON CIT.contrato_item_id = CI.id
                        JOIN oc_item_qc_item OCQC ON OCQC.qc_item_id = CI.qc_item_id
                        WHERE CI.id = CIT.contrato_item_id
                        AND OCQC.ordem_de_compra_item_id = ordem_de_compra_itens.id
                    )').'
                    AND ordem_de_compra_itens.servico_id = '.$servico_id.'
                    '.(isset($ordem_de_compra_ultima_aprovacao) ? "AND ordem_de_compras.created_at <='".$ordem_de_compra_ultima_aprovacao."'" : '').'
                    AND ordem_de_compra_itens.obra_id ='. $obra_id .')';

        $origem_oc = DB::select($query);

        return $origem_oc[0]->oc_origem;
    }
    
    public static function calculosDetalhesServicos($obra_id, $servico_id , $oc_id = null)
    {
        // Se alterar, modificar tbm DetalhesServicosDataTable::query
        if($oc_id) {
            $ordem_de_compra_ultima_aprovacao = OrdemDeCompra::find($oc_id)->dataUltimoPeriodoAprovacao();
        }

        $orcamentos = Orcamento::select([
            DB::raw("CONCAT(SUBSTRING_INDEX(orcamentos.codigo_insumo, '.', -1),' - ' ,orcamentos.descricao) as descricao"),
            'orcamentos.unidade_sigla',
            DB::raw("
                IF (orcamentos.insumo_incluido = 1, 0, orcamentos.preco_total) as valor_previsto
            "),
            'orcamentos.id',
            'orcamentos.insumo_incluido',
            'orcamentos.grupo_id',
            'orcamentos.subgrupo1_id',
            'orcamentos.subgrupo2_id',
            'orcamentos.subgrupo3_id',
            'orcamentos.servico_id',
            'orcamentos.insumo_id',
            'insumos.codigo',
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
                    '.($oc_id ? "AND ordem_de_compras.id = '".$oc_id."'" : 'AND NOT EXISTS(
                        SELECT 1 
                        FROM contrato_itens CI
                        JOIN contrato_item_apropriacoes CIT ON CIT.contrato_item_id = CI.id
                        JOIN oc_item_qc_item OCQC ON OCQC.qc_item_id = CI.qc_item_id
                        WHERE CI.id = CIT.contrato_item_id
                        AND OCQC.ordem_de_compra_item_id = ordem_de_compra_itens.id
                    )').'
                    AND ordem_de_compra_itens.servico_id = '.$servico_id.'
                    '.(isset($ordem_de_compra_ultima_aprovacao) ? "AND ordem_de_compras.created_at <='".$ordem_de_compra_ultima_aprovacao."'" : '').'
                    AND ordem_de_compra_itens.obra_id ='. $obra_id .' ) as valor_oc'),

            DB::raw('0
                    as saldo_disponivel'),
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
                    orcamentos.servico_id = servicos.id) AS tooltip_servico')
        ])
            ->join('insumos',  'insumos.id', 'orcamentos.insumo_id')
            ->leftJoin(DB::raw('orcamentos orcamentos_sub'),  'orcamentos_sub.id', 'orcamentos.orcamento_que_substitui')
            ->leftJoin(DB::raw('insumos insumos_sub'), 'insumos_sub.id', 'orcamentos_sub.insumo_id')
            ->where('orcamentos.servico_id','=', DB::raw($servico_id))
            ->where('orcamentos.obra_id','=', DB::raw($obra_id));

        $orcamentos = $orcamentos->groupBy('orcamentos.insumo_id');
        $orcamentos = $orcamentos->get();


        $valor_previsto = 0;
        $valor_realizado = 0;
        $valor_comprometido_a_gastar = 0;
        $saldo_orcamento = 0;
        $valor_oc = 0;

        foreach($orcamentos as $orcamento) {
            //valor_previsto
            $valor_previsto += $orcamento->valor_previsto;
            
            //valor_comprometido_a_gastar
            if($oc_id) {
                $ordem_de_compra_ultima_aprovacao = OrdemDeCompra::find($oc_id)->dataUltimoPeriodoAprovacao();
            } else {
                $ordem_de_compra_ultima_aprovacao = null;
            }
            $valor_comprometido_a_gastar += OrdemDeCompraRepository::valorComprometidoAGastarItem($orcamento->grupo_id, $orcamento->subgrupo1_id, $orcamento->subgrupo2_id, $orcamento->subgrupo3_id, $orcamento->servico_id, $orcamento->insumo_id, $obra_id, null, $ordem_de_compra_ultima_aprovacao);

            //valor_oc
            $valor_oc += $orcamento->valor_oc;
        }

        //saldo_orcamento
        $saldo_orcamento += $valor_previsto - $valor_realizado - $valor_comprometido_a_gastar;

        //saldo_disponivel
        $saldo_disponivel = $saldo_orcamento - $valor_oc;
        
        return [
                'valor_previsto' => $valor_previsto,
                'valor_realizado' => $valor_realizado,
                'valor_comprometido_a_gastar' => $valor_comprometido_a_gastar,
                'saldo_orcamento' => $saldo_orcamento,
                'valor_oc' => $valor_oc,
                'saldo_disponivel' => $saldo_disponivel
        ];
    }
}