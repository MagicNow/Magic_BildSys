<?php

namespace App\Repositories;

use App\Models\ContratoItemApropriacao;
use App\Models\OrdemDeCompra;
use App\Models\OrdemDeCompraItem;
use Illuminate\Support\Facades\DB;
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

        if($ordemDeCompra) {
            if(count($ordemDeCompra->itens())) {
//                $realizado = OrdemDeCompraItem::join('ordem_de_compras', 'ordem_de_compras.id', '=', 'ordem_de_compra_itens.ordem_de_compra_id')
//                    ->where('ordem_de_compras.obra_id', $obra_id)
//                    ->whereIn('oc_status_id', [2,3,5])
//                    ->whereIn('ordem_de_compra_itens.insumo_id', $ordemDeCompra->itens()->pluck('insumo_id', 'insumo_id')->toArray())
//                    ->sum('ordem_de_compra_itens.valor_total');
                $realizado = $ordemDeCompra->itens()->sum('valor_total');
            }
        }

        $saldoDisponivel = $orcamentoInicial - $realizado;
        
        return $saldoDisponivel;
    }

    public static function valorComprometidoAGastar($ordem_de_compra_id, $itens = null)
    {
        $valor_comprometido_a_gastar = ContratoItemApropriacao::select([
            'contrato_item_apropriacoes.qtd',
            'contrato_itens.valor_unitario'
            ])
            ->where('ordem_de_compra_id', $ordem_de_compra_id)
            ->join('ordem_de_compra_itens', function ($join) {
                $join->on('ordem_de_compra_itens.insumo_id', '=', 'contrato_item_apropriacoes.insumo_id');
                $join->on('ordem_de_compra_itens.grupo_id', '=', 'contrato_item_apropriacoes.grupo_id');
                $join->on('ordem_de_compra_itens.subgrupo1_id', '=', 'contrato_item_apropriacoes.subgrupo1_id');
                $join->on('ordem_de_compra_itens.subgrupo2_id', '=', 'contrato_item_apropriacoes.subgrupo2_id');
                $join->on('ordem_de_compra_itens.subgrupo3_id', '=', 'contrato_item_apropriacoes.subgrupo3_id');
                $join->on('ordem_de_compra_itens.servico_id', '=', 'contrato_item_apropriacoes.servico_id');
            })
            ->join('contrato_itens', 'contrato_itens.id' ,'=', 'contrato_item_apropriacoes.contrato_item_id');

            if($itens){
                $valor_comprometido_a_gastar->whereRaw('NOT EXISTS(
                    SELECT 1 
                    FROM contrato_itens CI
                    JOIN oc_item_qc_item OCQC ON OCQC.qc_item_id = CI.qc_item_id
                    WHERE CI.id = contrato_item_apropriacoes.contrato_item_id
                    AND OCQC.ordem_de_compra_item_id IN ('.implode(', ', $itens).')
                )');
            }

            $valor_comprometido_a_gastar = $valor_comprometido_a_gastar->sum(DB::raw('contrato_item_apropriacoes.qtd * contrato_itens.valor_unitario'));

        return $valor_comprometido_a_gastar;
    }
    
    public static function valorComprometidoAGastarItem($grupo_id, $subgrupo1_id, $subgrupo2_id, $subgrupo3_id, $servico_id, $insumo_id, $obra_id, $item_id = null)
    {
        $valores_comprometido_a_gastar = ContratoItemApropriacao::select([
            'contrato_item_apropriacoes.id',
            DB::raw('(contrato_item_apropriacoes.qtd * contrato_itens.valor_unitario) as valor_comprometido_a_gastar')
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
            $valores_comprometido_a_gastar->whereRaw('NOT EXISTS(
                SELECT 1 
                FROM contrato_itens CI
                JOIN oc_item_qc_item OCQC ON OCQC.qc_item_id = CI.qc_item_id
                WHERE CI.id = contrato_item_apropriacoes.contrato_item_id
                AND OCQC.ordem_de_compra_item_id = '.$item_id.'
            )');
        }

        $valores_comprometido_a_gastar = $valores_comprometido_a_gastar->get();

        $valor_comprometido_a_gastar = 0;

        if(count($valores_comprometido_a_gastar)){
            foreach ($valores_comprometido_a_gastar as $valor) {
                $valor_comprometido_a_gastar += $valor->valor_comprometido_a_gastar;
            }
        }

        return $valor_comprometido_a_gastar;
    }

    public static function qtdComprometidaAGastarItem($grupo_id, $subgrupo1_id, $subgrupo2_id, $subgrupo3_id, $servico_id, $insumo_id, $obra_id, $item_id = null)
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

        $qtds_comprometida_a_gastar = $qtds_comprometida_a_gastar->get();

        $qtd_comprometida_a_gastar = 0;

        if(count($qtds_comprometida_a_gastar)){
            foreach ($qtds_comprometida_a_gastar as $valor) {
                $qtd_comprometida_a_gastar += $valor->qtd;
            }
        }

        return $qtd_comprometida_a_gastar;
    }
}