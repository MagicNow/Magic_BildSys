<?php

namespace App\Repositories;

use App\Models\CatalogoContrato;
use App\Models\Contrato;
use App\Models\ContratoItemApropriacao;
use App\Models\Lembrete;
use App\Models\Obra;
use App\Models\Orcamento;
use App\Models\OrdemDeCompra;
use App\Models\OrdemDeCompraItem;
use App\Repositories\Admin\PlanejamentoCompraRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
            'catalogo_contratos.id',
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
                    AND ordem_de_compra_itens.data_dispensa IS NULL 
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
                    AND ordem_de_compra_itens.data_dispensa IS NULL 
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
    
    public static function queryCalendarioLembretes(
        $obra_id,
        $planejamento_id,
        $insumo_grupo_id,
        $carteira_id,
        $exibir_por_tarefa,
        $exibir_por_carteira,
        $from,
        $to
    )
    {
        if ($obra_id || $planejamento_id || $insumo_grupo_id || $carteira_id){
            if ($exibir_por_tarefa || $exibir_por_carteira) {
                $url = 'CONCAT(\' . url("compras/obrasInsumos?planejamento_id=") . \',planejamentos.id,\'&obra_id=\',obras.id) as url';
                $url_dispensar = 'CONCAT(\' . url("compras/obrasInsumos/dispensar?planejamento_id=") . \',planejamentos.id,\'&obra_id=\',obras.id) as url_dispensar';

                if($exibir_por_tarefa) {
                    $title = 'CONCAT(obras.nome,\' - \',planejamentos.tarefa) title';
                } elseif($exibir_por_carteira) {
                    $title = 'CONCAT_WS(" ", obras.nome,\' - \',NULLIF(carteiras.nome, "")) title';
                } else {
                    $title = '';
                }
            } else {
                $url = 'CONCAT(\'/compras/obrasInsumos?planejamento_id=\',planejamentos.id,\'&insumo_grupos_id=\',insumo_grupos.id,\'&obra_id=\',obras.id) as url';
                $url_dispensar = 'CONCAT(\'/compras/obrasInsumos/dispensar?planejamento_id=\',planejamentos.id,\'&insumo_grupos_id=\',insumo_grupos.id,\'&obra_id=\',obras.id) as url_dispensar';
                $title = 'CONCAT(obras.nome,\' - \',planejamentos.tarefa,\' - \', lembretes.nome) title';

            }

            //Atualizacao, sem exibir tarefas marcada
            if (!$exibir_por_tarefa && !$exibir_por_carteira) {
                $query = Lembrete::join('insumo_grupos', 'insumo_grupos.id', '=', 'lembretes.insumo_grupo_id')
                    ->join('insumos', 'insumos.insumo_grupo_id', '=', 'insumo_grupos.id')
                    ->join('planejamento_compras', 'planejamento_compras.insumo_id', '=', 'insumos.id')
                    ->join('planejamentos', 'planejamentos.id', '=', 'planejamento_compras.planejamento_id')
                    ->join('obras', 'obras.id', '=', 'planejamentos.obra_id')
                    ->join('obra_users', 'obra_users.obra_id', '=', 'obras.id')
                    ->leftJoin('carteira_insumos', 'carteira_insumos.insumo_id', '=', 'insumos.id')
                    ->leftJoin('carteiras', 'carteiras.id', '=', 'carteira_insumos.carteira_id')
                    ->whereNull('planejamentos.deleted_at')
                    ->whereNull('planejamento_compras.deleted_at')
                    ->where('lembretes.lembrete_tipo_id', 1)
                    ->where('planejamento_compras.dispensado', 0)
                    ->where('obra_users.user_id', Auth::user()->id)
                    ->select([
                        'lembretes.id',
                        'obras.nome as obra',
                        'planejamentos.tarefa',
                        DB::raw("GROUP_CONCAT(DISTINCT insumo_grupos.nome ORDER BY insumo_grupos.nome ASC SEPARATOR ', ') grupo"),
                        DB::raw($url),
                        DB::raw($url_dispensar),
                        DB::raw($title),
                        DB::raw("'event-info' as class"),
                        
                        /* inicio */
                        DB::raw("DATE_FORMAT(DATE_SUB(planejamentos.data, INTERVAL (
                            IFNULL(
                                (
                                    SELECT
                                        SUM(L.dias_prazo_minimo) prazo
                                    FROM
                                        lembretes L
                                    JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                    WHERE
                                        EXISTS(
                                            SELECT
                                                1
                                            FROM
                                                insumos I
                                            WHERE
                                                I.id = insumos.id
                                            AND I.insumo_grupo_id = IG.id
                                        )
                                    AND L.deleted_at IS NULL
                                ) ,
                                0
                            ) + IFNULL(
                                (
                                    SELECT
                                        SUM(dias_prazo) prazo
                                    FROM
                                        workflow_alcadas
                                    WHERE
                                        EXISTS(
                                            SELECT
                                                1
                                            FROM
                                                workflow_usuarios
                                            WHERE
                                                workflow_alcada_id = workflow_alcadas.id
                                        )
                                    AND workflow_alcadas.workflow_tipo_id <= 2
								    AND workflow_alcadas.deleted_at IS NULL
                                ) ,
                                0
                            )
                        ) DAY),'%d/%m/%Y') as inicio"),
                        /* inicio */
                        
                        /* start */
                        DB::raw("UNIX_TIMESTAMP(DATE_SUB(planejamentos.data, INTERVAL (
                            IFNULL(
                                (
                                    SELECT
                                        SUM(L.dias_prazo_minimo) prazo
                                    FROM
                                        lembretes L
                                    JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                    WHERE
                                        EXISTS (
                                            SELECT
                                                1
                                            FROM
                                                insumos I
                                            WHERE
                                                I.id = insumos.id
                                            AND I.insumo_grupo_id = IG.id
                                        )
                                    AND L.deleted_at IS NULL
                                ),
                                0
                            ) + IFNULL(
                                (
                                    SELECT
                                        SUM(dias_prazo) prazo
                                    FROM
                                        workflow_alcadas
                                    WHERE
                                        EXISTS (
                                            SELECT
                                                1
                                            FROM
                                                workflow_usuarios
                                            WHERE
                                                workflow_alcada_id = workflow_alcadas.id
                                        )
                                    AND workflow_alcadas.workflow_tipo_id <= 2
                                    AND workflow_alcadas.deleted_at IS NULL
                                ),
                                0
                            )
                        ) DAY))*1000 as start"),
                        /* start */

                        /* end */
                        DB::raw("UNIX_TIMESTAMP(DATE_SUB(planejamentos.data, INTERVAL (
                            IFNULL(
                                (
                                    SELECT
                                        SUM(L.dias_prazo_minimo) prazo
                                    FROM
                                        lembretes L
                                    JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                    WHERE
                                        EXISTS (
                                            SELECT
                                                1
                                            FROM
                                                insumos I
                                            WHERE
                                                I.id = insumos.id
                                            AND I.insumo_grupo_id = IG.id
                                        )
                                    AND L.deleted_at IS NULL
                                ),
                                0
                            ) + IFNULL(
                                (
                                    SELECT
                                        SUM(dias_prazo) prazo
                                    FROM
                                        workflow_alcadas
                                    WHERE
                                        EXISTS (
                                            SELECT
                                                1
                                            FROM
                                                workflow_usuarios
                                            WHERE
                                                workflow_alcada_id = workflow_alcadas.id
                                        )
                                    AND workflow_alcadas.workflow_tipo_id <= 2
                                    AND workflow_alcadas.deleted_at IS NULL
                                ),
                                0
                            )
                        ) DAY))*1000 as end"),
                        /* end */
                        
                        /* dias */
                        DB::raw("DATEDIFF(
                            (
                            DATE_SUB(planejamentos.data, INTERVAL (
                                IFNULL(
                                    (
                                        SELECT
                                            SUM(L.dias_prazo_minimo) prazo
                                        FROM
                                            lembretes L
                                        JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                        WHERE
                                            EXISTS(
                                                SELECT
                                                    1
                                                FROM
                                                    insumos I
                                                WHERE
                                                    I.id = insumos.id
                                                AND I.insumo_grupo_id = IG.id
                                            )
                                        AND L.deleted_at IS NULL
                                    ) ,
                                    0
                                ) + IFNULL(
                                    (
                                        SELECT
                                            SUM(dias_prazo) prazo
                                        FROM
                                            workflow_alcadas
                                        WHERE
                                            EXISTS(
                                                SELECT
                                                    1
                                                FROM
                                                    workflow_usuarios
                                                WHERE
                                                    workflow_alcada_id = workflow_alcadas.id
                                            )
                                        AND workflow_alcadas.workflow_tipo_id <= 2
                                        AND workflow_alcadas.deleted_at IS NULL
                                    ) ,
                                    0
                                )
                            ) DAY)
                        ),CURDATE()) as dias"),
                        /* dias */
                        
                        'carteiras.nome as carteira',
                        'carteiras.id as carteira_id'
                    ]);

                if ($from || $to) {
                    if ($from) {
                        $from = date('Y-m-d', $from / 1000);
                        $query->where(DB::raw('DATE_SUB(planejamentos.data, INTERVAL (
                                            IFNULL(
                                                (
                                                    SELECT
                                                        SUM(L.dias_prazo_minimo) prazo
                                                    FROM
                                                        lembretes L
                                                    JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                                    WHERE
                                                        EXISTS(
                                                            SELECT
                                                                1
                                                            FROM
                                                                insumos I
                                                            WHERE
                                                                I.id = insumos.id
                                                            AND I.insumo_grupo_id = IG.id
                                                        )
                                                    AND L.deleted_at IS NULL
                                                ) ,
                                                0
                                            ) + IFNULL(
                                                (
                                                    SELECT
                                                        SUM(dias_prazo) prazo
                                                    FROM
                                                        workflow_alcadas
                                                    WHERE
                                                        EXISTS(
                                                            SELECT
                                                                1
                                                            FROM
                                                                workflow_usuarios
                                                            WHERE
                                                                workflow_alcada_id = workflow_alcadas.id
                                                        )
                                                    AND workflow_alcadas.workflow_tipo_id <= 2
								                    AND workflow_alcadas.deleted_at IS NULL
                                                ) ,
                                                0
                                            )
                                        ) DAY)'), '>=', $from);
                    }
                    if ($to) {
                        $to = date('Y-m-d', $to / 1000);
                        $query->where(DB::raw('DATE_SUB(planejamentos.data, INTERVAL (
                                IFNULL(
                                    (
                                        SELECT
                                            SUM(L.dias_prazo_minimo) prazo
                                        FROM
                                            lembretes L
                                        JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                        WHERE
                                            EXISTS(
                                                SELECT
                                                    1
                                                FROM
                                                    insumos I
                                                WHERE
                                                    I.id = insumos.id
                                                AND I.insumo_grupo_id = IG.id
                                            )
                                        AND L.deleted_at IS NULL
                                    ) ,
                                    0
                                ) + IFNULL(
                                    (
                                        SELECT
                                            SUM(dias_prazo) prazo
                                        FROM
                                            workflow_alcadas
                                        WHERE
                                            EXISTS(
                                                SELECT
                                                    1
                                                FROM
                                                    workflow_usuarios
                                                WHERE
                                                    workflow_alcada_id = workflow_alcadas.id
                                            )
                                        AND workflow_alcadas.workflow_tipo_id <= 2
								        AND workflow_alcadas.deleted_at IS NULL
                                    ) ,
                                    0
                                )
                            ) DAY)'), '<=', $to);
                    }
                }

                if ($obra_id && $obra_id != 'todas') {
                    $query->where('planejamentos.obra_id', $obra_id);
                }
                if ($planejamento_id) {
                    $query->where('planejamentos.id', $planejamento_id);
                }
                if ($insumo_grupo_id) {
                    $query->where('insumos.insumo_grupo_id', $insumo_grupo_id);
                }
                if ($carteira_id) {
                    $query->where('carteiras.id', $carteira_id);
                }
                // Busca se existe algum item a  ser comprado desta tarefa
                $query->whereRaw(PlanejamentoCompraRepository::existeItemParaComprarComInsumoGrupo());
                $query->groupBy(['id', 'obra', 'dias', 'tarefa', 'url', 'inicio', 'carteira', 'carteira_id', 'start', 'end', 'class', 'title']);

            }
            elseif($exibir_por_tarefa) {
                $query = DB::table(
                    DB::raw('(SELECT tarefa, id, obra, url, url_dispensar, inicio, start, dias, grupo, carteira, carteira_id, title, class
                         FROM
                             (SELECT tarefa, id, obra, url, url_dispensar, inicio, start, dias, grupo, carteira, carteira_id, title, class
                             FROM
                                (SELECT tarefa, id, obra, url, url_dispensar, inicio, start, dias, grupo, carteira,carteira_id,  title, class
                                FROM (SELECT
                                        planejamentos.id,
                                        obras.nome AS obra,
                                        planejamentos.tarefa,
                                        ' . $url . ',
                                        ' . $url_dispensar . ',
                                        ' . $title . ',
                                        \'event-info\' AS class,
                                        insumo_grupos.nome as grupo,
                                        DATE_FORMAT(
                                            DATE_SUB(
                                                planejamentos.data,
                                                INTERVAL (
                                                    IFNULL(
                                                        (
                                                            SELECT
                                                                SUM(L.dias_prazo_minimo) prazo
                                                            FROM
                                                                lembretes L
                                                            JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                                            WHERE
                                                                EXISTS (
                                                                    SELECT
                                                                        1
                                                                    FROM
                                                                        insumos I
                                                                    WHERE
                                                                        I.id = insumos.id
                                                                    AND I.insumo_grupo_id = IG.id
                                                                )
                                                            AND L.deleted_at IS NULL
                                                        ),
                                                        0
                                                    ) + IFNULL(
                                                        (
                                                            SELECT
                                                                SUM(dias_prazo) prazo
                                                            FROM
                                                                workflow_alcadas
                                                            WHERE
                                                                EXISTS (
                                                                    SELECT
                                                                        1
                                                                    FROM
                                                                        workflow_usuarios
                                                                    WHERE
                                                                        workflow_alcada_id = workflow_alcadas.id
                                                                )
                                                            AND workflow_alcadas.workflow_tipo_id <= 2
                                                            AND workflow_alcadas.deleted_at IS NULL
                                                        ),
                                                        0
                                                    )
                                                ) DAY
                                            ),
                                            \'%d/%m/%Y\'
                                        ) AS inicio,
                                        UNIX_TIMESTAMP(
                                            DATE_SUB(
                                                planejamentos.data,
                                                INTERVAL (
                                                    IFNULL(
                                                        (
                                                            SELECT
                                                                SUM(L.dias_prazo_minimo) prazo
                                                            FROM
                                                                lembretes L
                                                            JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                                            WHERE
                                                                EXISTS (
                                                                    SELECT
                                                                        1
                                                                    FROM
                                                                        insumos I
                                                                    WHERE
                                                                        I.id = insumos.id
                                                                    AND I.insumo_grupo_id = IG.id
                                                                )
                                                            AND L.deleted_at IS NULL
                                                        ),
                                                        0
                                                    ) + IFNULL(
                                                        (
                                                            SELECT
                                                                SUM(dias_prazo) prazo
                                                            FROM
                                                                workflow_alcadas
                                                            WHERE
                                                                EXISTS (
                                                                    SELECT
                                                                        1
                                                                    FROM
                                                                        workflow_usuarios
                                                                    WHERE
                                                                        workflow_alcada_id = workflow_alcadas.id
                                                                )
                                                            AND workflow_alcadas.workflow_tipo_id <= 2
                                                            AND workflow_alcadas.deleted_at IS NULL
                                                        ),
                                                        0
                                                    )
                                                ) DAY
                                            )
                                        ) * 1000 AS start,
                                        DATEDIFF(
                                        (
                                        DATE_SUB(planejamentos.data, INTERVAL (
                                        IFNULL(
                                            (
                                                SELECT
                                                    SUM(L.dias_prazo_minimo) prazo
                                                FROM
                                                    lembretes L
                                                JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                                WHERE
                                                    EXISTS(
                                                        SELECT
                                                            1
                                                        FROM
                                                            insumos I
                                                        WHERE
                                                            I.id = insumos.id
                                                        AND I.insumo_grupo_id = IG.id
                                                    )
                                                AND L.deleted_at IS NULL
                                            ) ,
                                            0
                                            ) + IFNULL(
                                                (
                                                    SELECT
                                                        SUM(dias_prazo) prazo
                                                    FROM
                                                        workflow_alcadas
                                                    WHERE
                                                        EXISTS(
                                                            SELECT
                                                                1
                                                            FROM
                                                                workflow_usuarios
                                                            WHERE
                                                                workflow_alcada_id = workflow_alcadas.id
                                                        )
                                                    AND workflow_alcadas.workflow_tipo_id <= 2
                                                    AND workflow_alcadas.deleted_at IS NULL
                                                ) ,
                                                0
                                            )
                                            ) DAY)
                                        ),CURDATE()) as dias,
                                        carteiras.nome as carteira,
                                        carteiras.id as carteira_id
                                    FROM lembretes
                                    INNER JOIN insumo_grupos ON insumo_grupos.id = lembretes.insumo_grupo_id
                                    INNER JOIN insumos ON insumos.insumo_grupo_id = insumo_grupos.id
                                    INNER JOIN planejamento_compras ON planejamento_compras.insumo_id = insumos.id
                                    INNER JOIN planejamentos ON planejamentos.id = planejamento_compras.planejamento_id
                                    INNER JOIN obras ON obras.id = planejamentos.obra_id
                                    INNER JOIN obra_users ON obra_users.obra_id = obras.id
                                    LEFT JOIN carteira_insumos ON carteira_insumos.insumo_id = insumos.id
                                    LEFT JOIN carteiras ON carteiras.id = carteira_insumos.carteira_id
                                    WHERE planejamentos.deleted_at IS NULL
                                    AND lembretes.lembrete_tipo_id = 1
                                    AND planejamento_compras.dispensado = 0
                                    AND obra_users.user_id = ' . Auth::user()->id . '
                                    AND (
                                        SELECT
                                            1
                                        FROM
                                            planejamento_compras plc
                                        JOIN planejamentos P ON P.id = plc.planejamento_id
                                        JOIN orcamentos orc ON orc.insumo_id = plc.insumo_id
                                        AND orc.grupo_id = plc.grupo_id
                                        AND orc.subgrupo1_id = plc.subgrupo1_id
                                        AND orc.subgrupo2_id = plc.subgrupo2_id
                                        AND orc.subgrupo3_id = plc.subgrupo3_id
                                        AND orc.servico_id = plc.servico_id
                                        AND orc.ativo = 1
                                        AND orc.obra_id = P.obra_id
                                        WHERE
                                            (
                                                IFNULL((
                                                    SELECT
                                                        SUM(oci.qtd)
                                                    FROM ordem_de_compra_itens oci
                                                    JOIN ordem_de_compras ocs ON ocs.id = oci.ordem_de_compra_id
                                                    WHERE
                                                        oci.insumo_id = plc.insumo_id
                                                    AND oci.grupo_id = plc.grupo_id
                                                    AND oci.subgrupo1_id = plc.subgrupo1_id
                                                    AND oci.subgrupo2_id = plc.subgrupo2_id
                                                    AND oci.subgrupo3_id = plc.subgrupo3_id
                                                    AND oci.servico_id = plc.servico_id
                                                    AND oci.obra_id = P.obra_id
                                                    AND ocs.oc_status_id NOT IN(1 , 4 , 6)
                                                ),0) < orc.qtd_total
                                                AND
                                                IFNULL((
                                                    SELECT
                                                        SUM(oci.total)
                                                    FROM ordem_de_compra_itens oci
                                                    JOIN ordem_de_compras ocs ON ocs.id = oci.ordem_de_compra_id
                                                    AND ocs.oc_status_id NOT IN(1 , 4 , 6)
                                                    WHERE
                                                        oci.insumo_id = plc.insumo_id
                                                    AND oci.grupo_id = plc.grupo_id
                                                    AND oci.subgrupo1_id = plc.subgrupo1_id
                                                    AND oci.subgrupo2_id = plc.subgrupo2_id
                                                    AND oci.subgrupo3_id = plc.subgrupo3_id
                                                    AND oci.servico_id = plc.servico_id
                                                    AND oci.obra_id = P.obra_id
                                                ),0) = 0
                                            )
                                            AND P.id = planejamentos.id
                                            AND plc.deleted_at IS NULL
                                            AND orc.qtd_total > 0
                                            LIMIT 1
                                    ) IS NOT NULL
                                    AND lembretes.deleted_at IS NULL
                                    ' . ($obra_id && $obra_id != 'todas' ? ' AND planejamentos.obra_id = ' . $obra_id : '') . '
                                    ' . ($planejamento_id ? ' AND planejamentos.id = ' . $planejamento_id : '') . '
                                    ' . ($insumo_grupo_id ? ' AND insumos.insumo_grupo_id = ' . $insumo_grupo_id : '') . '
                                    GROUP BY id, obra, tarefa, url, url_dispensar, grupo, inicio, dias, carteira, carteira_id
                                    ) as queryInterna
                                    ORDER BY
                                    STR_TO_DATE(inicio,\'%d/%m/%Y\') ASC
                                ) as xpto_ordenado
                             ) as xpto_agrupado
                             GROUP BY tarefa
                         ) as xpto'
                    )
                );
            }
            elseif($exibir_por_carteira){
                $query = DB::table(
                    DB::raw('(SELECT tarefa, id, obra, url, url_dispensar, inicio, start, dias, grupo, carteira, carteira_id, title, class
                         FROM
                             (SELECT tarefa, id, obra, url, url_dispensar, inicio, start, dias, grupo, carteira, carteira_id, title, class
                             FROM
                                (SELECT tarefa, id, obra, url, url_dispensar, inicio, start, dias, grupo, carteira, carteira_id, title, class
                                FROM (SELECT
                                        planejamentos.id,
                                        obras.nome AS obra,
                                        planejamentos.tarefa,
                                        ' . $url . ',
                                        ' . $url_dispensar . ',
                                        ' . $title . ',
                                        \'event-info\' AS class,
                                        insumo_grupos.nome as grupo,
                                        DATE_FORMAT(
                                            DATE_SUB(
                                                planejamentos.data,
                                                INTERVAL (
                                                    IFNULL(
                                                        (
                                                            SELECT
                                                                SUM(L.dias_prazo_minimo) prazo
                                                            FROM
                                                                lembretes L
                                                            JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                                            WHERE
                                                                EXISTS (
                                                                    SELECT
                                                                        1
                                                                    FROM
                                                                        insumos I
                                                                    WHERE
                                                                        I.id = insumos.id
                                                                    AND I.insumo_grupo_id = IG.id
                                                                )
                                                            AND L.deleted_at IS NULL
                                                        ),
                                                        0
                                                    ) + IFNULL(
                                                        (
                                                            SELECT
                                                                SUM(dias_prazo) prazo
                                                            FROM
                                                                workflow_alcadas
                                                            WHERE
                                                                EXISTS (
                                                                    SELECT
                                                                        1
                                                                    FROM
                                                                        workflow_usuarios
                                                                    WHERE
                                                                        workflow_alcada_id = workflow_alcadas.id
                                                                )
                                                            AND workflow_alcadas.workflow_tipo_id <= 2
                                                            AND workflow_alcadas.deleted_at IS NULL
                                                        ),
                                                        0
                                                    )
                                                ) DAY
                                            ),
                                            \'%d/%m/%Y\'
                                        ) AS inicio,
                                        UNIX_TIMESTAMP(
                                            DATE_SUB(
                                                planejamentos.data,
                                                INTERVAL (
                                                    IFNULL(
                                                        (
                                                            SELECT
                                                                SUM(L.dias_prazo_minimo) prazo
                                                            FROM
                                                                lembretes L
                                                            JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                                            WHERE
                                                                EXISTS (
                                                                    SELECT
                                                                        1
                                                                    FROM
                                                                        insumos I
                                                                    WHERE
                                                                        I.id = insumos.id
                                                                    AND I.insumo_grupo_id = IG.id
                                                                )
                                                            AND L.deleted_at IS NULL
                                                        ),
                                                        0
                                                    ) + IFNULL(
                                                        (
                                                            SELECT
                                                                SUM(dias_prazo) prazo
                                                            FROM
                                                                workflow_alcadas
                                                            WHERE
                                                                EXISTS (
                                                                    SELECT
                                                                        1
                                                                    FROM
                                                                        workflow_usuarios
                                                                    WHERE
                                                                        workflow_alcada_id = workflow_alcadas.id
                                                                )
                                                            AND workflow_alcadas.workflow_tipo_id <= 2
                                                            AND workflow_alcadas.deleted_at IS NULL
                                                        ),
                                                        0
                                                    )
                                                ) DAY
                                            )
                                        ) * 1000 AS start,
                                        DATEDIFF(
                                        (
                                        DATE_SUB(planejamentos.data, INTERVAL (
                                        IFNULL(
                                            (
                                                SELECT
                                                    SUM(L.dias_prazo_minimo) prazo
                                                FROM
                                                    lembretes L
                                                JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                                WHERE
                                                    EXISTS(
                                                        SELECT
                                                            1
                                                        FROM
                                                            insumos I
                                                        WHERE
                                                            I.id = insumos.id
                                                        AND I.insumo_grupo_id = IG.id
                                                    )
                                                AND L.deleted_at IS NULL
                                            ) ,
                                            0
                                            ) + IFNULL(
                                                (
                                                    SELECT
                                                        SUM(dias_prazo) prazo
                                                    FROM
                                                        workflow_alcadas
                                                    WHERE
                                                        EXISTS(
                                                            SELECT
                                                                1
                                                            FROM
                                                                workflow_usuarios
                                                            WHERE
                                                                workflow_alcada_id = workflow_alcadas.id
                                                        )
                                                    AND workflow_alcadas.workflow_tipo_id <= 2
                                                    AND workflow_alcadas.deleted_at IS NULL
                                                ) ,
                                                0
                                            )
                                            ) DAY)
                                        ),CURDATE()) as dias,
                                        carteiras.nome as carteira,
                                        carteiras.id as carteira_id
                                        
                                    FROM lembretes
                                    INNER JOIN insumo_grupos ON insumo_grupos.id = lembretes.insumo_grupo_id
                                    INNER JOIN insumos ON insumos.insumo_grupo_id = insumo_grupos.id
                                    INNER JOIN planejamento_compras ON planejamento_compras.insumo_id = insumos.id
                                    INNER JOIN planejamentos ON planejamentos.id = planejamento_compras.planejamento_id
                                    INNER JOIN obras ON obras.id = planejamentos.obra_id
                                    INNER JOIN obra_users ON obra_users.obra_id = obras.id
                                    LEFT JOIN carteira_insumos ON carteira_insumos.insumo_id = insumos.id
                                    LEFT JOIN carteiras ON carteiras.id = carteira_insumos.carteira_id
                                    WHERE planejamentos.deleted_at IS NULL
                                    AND lembretes.lembrete_tipo_id = 1
                                    AND planejamento_compras.dispensado = 0
                                    AND obra_users.user_id = ' . Auth::user()->id . '
                                    AND (
                                        SELECT
                                            1
                                        FROM
                                            planejamento_compras plc
                                        JOIN planejamentos P ON P.id = plc.planejamento_id
                                        JOIN orcamentos orc ON orc.insumo_id = plc.insumo_id
                                        AND orc.grupo_id = plc.grupo_id
                                        AND orc.subgrupo1_id = plc.subgrupo1_id
                                        AND orc.subgrupo2_id = plc.subgrupo2_id
                                        AND orc.subgrupo3_id = plc.subgrupo3_id
                                        AND orc.servico_id = plc.servico_id
                                        AND orc.ativo = 1
                                        AND orc.obra_id = P.obra_id
                                        WHERE
                                            (
                                                IFNULL((
                                                    SELECT
                                                        SUM(oci.qtd)
                                                    FROM ordem_de_compra_itens oci
                                                    JOIN ordem_de_compras ocs ON ocs.id = oci.ordem_de_compra_id
                                                    WHERE
                                                        oci.insumo_id = plc.insumo_id
                                                    AND oci.grupo_id = plc.grupo_id
                                                    AND oci.subgrupo1_id = plc.subgrupo1_id
                                                    AND oci.subgrupo2_id = plc.subgrupo2_id
                                                    AND oci.subgrupo3_id = plc.subgrupo3_id
                                                    AND oci.servico_id = plc.servico_id
                                                    AND oci.obra_id = P.obra_id
                                                    AND ocs.oc_status_id NOT IN(1 , 4 , 6)
                                                ),0) < orc.qtd_total
                                                AND
                                                IFNULL((
                                                    SELECT
                                                        SUM(oci.total)
                                                    FROM ordem_de_compra_itens oci
                                                    JOIN ordem_de_compras ocs ON ocs.id = oci.ordem_de_compra_id
                                                    AND ocs.oc_status_id NOT IN(1 , 4 , 6)
                                                    WHERE
                                                        oci.insumo_id = plc.insumo_id
                                                    AND oci.grupo_id = plc.grupo_id
                                                    AND oci.subgrupo1_id = plc.subgrupo1_id
                                                    AND oci.subgrupo2_id = plc.subgrupo2_id
                                                    AND oci.subgrupo3_id = plc.subgrupo3_id
                                                    AND oci.servico_id = plc.servico_id
                                                    AND oci.obra_id = P.obra_id
                                                ),0) = 0
                                            )
                                            AND P.id = planejamentos.id
                                            AND plc.deleted_at IS NULL
                                            AND orc.qtd_total > 0
                                            LIMIT 1
                                    ) IS NOT NULL
                                    AND lembretes.deleted_at IS NULL
                                    ' . ($obra_id && $obra_id != 'todas' ? ' AND planejamentos.obra_id = ' . $obra_id : '') . '
                                    ' . ($planejamento_id ? ' AND planejamentos.id = ' . $planejamento_id : '') . '
                                    ' . ($insumo_grupo_id ? ' AND insumos.insumo_grupo_id = ' . $insumo_grupo_id : '') . '
                                    GROUP BY id, obra, tarefa, url, url_dispensar, grupo, inicio, dias, carteira, carteira_id
                                    ) as queryInterna
                                    ORDER BY
                                    STR_TO_DATE(inicio,\'%d/%m/%Y\') ASC
                                ) as xpto_ordenado
                             ) as xpto_agrupado
                             GROUP BY carteira
                         ) as xpto'
                    )
                );
            } else {
                $query = collect();
            }
            
            return $query;
        } else {
            return collect();
        }
    }
}