<?php

namespace App\Repositories;

use App\Models\ContratoItemApropriacao;
use InfyOm\Generator\Common\BaseRepository;
use App\Models\ContratoItem;
use App\Models\Contrato;
use Illuminate\Support\Facades\DB;

class ContratoItemApropriacaoRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return ContratoItemApropriacao::class;
    }

    public function reapropriar(ContratoItem $contratoItem, $data)
    {
        $data['insumo_id']                      = $contratoItem->qcItem->insumo_id;
        $data['contrato_item_id']               = $contratoItem->id;
        $data['qtd']                            = money_to_float($data['qtd']);
        $data['user_id']                        = auth()->id();
        $data['codigo_insumo']                  = $contratoItem->insumo->codigo;
        $data['contrato_item_reapropriacao_id'] = $data['item_id'];

        return $this->create($data);
    }

    public function forContratoApproval(Contrato $contrato)
    {
        $query = <<<EOFSQL
SELECT
    contrato_item_apropriacoes.*,
    0 as qtd_realizada,
    0 as valor_realizado,
    orcamentos.qtd_total as qtd_inicial,
    orcamentos.preco_total as preco_inicial,
    ordem_de_compra_itens.total,
    ordem_de_compra_itens.motivo_nao_finaliza_obra,
    ordem_de_compra_itens.justificativa,
    ordem_de_compra_itens.obs,
    ordem_de_compra_itens.tems,
    ordem_de_compra_itens.emergencial,
    ordem_de_compra_itens.id as ordem_de_compra_item_id,
    (
        SELECT
            SUM(orcamentos.preco_total)
        FROM
            orcamentos
        WHERE
            orcamentos.grupo_id = contrato_item_apropriacoes.grupo_id
                AND orcamentos.subgrupo1_id = contrato_item_apropriacoes.subgrupo1_id
                AND orcamentos.subgrupo2_id = contrato_item_apropriacoes.subgrupo2_id
                AND orcamentos.subgrupo3_id = contrato_item_apropriacoes.subgrupo3_id
                AND orcamentos.servico_id = contrato_item_apropriacoes.servico_id
                AND orcamentos.obra_id = $contrato->obra_id
                AND orcamentos.ativo = 1
    ) as valor_servico
FROM
	`contrato_item_apropriacoes`
INNER JOIN `contrato_itens` ON
	`contrato_itens`.`id` = `contrato_item_apropriacoes`.`contrato_item_id`
LEFT JOIN `ordem_de_compra_itens` ON
	`ordem_de_compra_itens`.`insumo_id` = `contrato_item_apropriacoes`.`insumo_id`
	AND `ordem_de_compra_itens`.`grupo_id` = `contrato_item_apropriacoes`.`grupo_id`
	AND `ordem_de_compra_itens`.`subgrupo1_id` = `contrato_item_apropriacoes`.`subgrupo1_id`
	AND `ordem_de_compra_itens`.`subgrupo2_id` = `contrato_item_apropriacoes`.`subgrupo2_id`
	AND `ordem_de_compra_itens`.`subgrupo3_id` = `contrato_item_apropriacoes`.`subgrupo3_id`
	AND `ordem_de_compra_itens`.`servico_id` = `contrato_item_apropriacoes`.`servico_id`
	AND `ordem_de_compra_itens`.`obra_id` = $contrato->obra_id
	AND `ordem_de_compra_itens`.`id` IN (
		SELECT
			`oc_item_qc_item`.`ordem_de_compra_item_id`
		FROM
			`oc_item_qc_item` WHERE
			`oc_item_qc_item`.`qc_item_id` = `contrato_itens`.`qc_item_id`
	)
LEFT JOIN `orcamentos` ON
	`orcamentos`.`insumo_id` = `contrato_item_apropriacoes`.`insumo_id`
	AND `orcamentos`.`grupo_id` = `contrato_item_apropriacoes`.`grupo_id`
	AND `orcamentos`.`subgrupo1_id` = `contrato_item_apropriacoes`.`subgrupo1_id`
	AND `orcamentos`.`subgrupo2_id` = `contrato_item_apropriacoes`.`subgrupo2_id`
	AND `orcamentos`.`subgrupo3_id` = `contrato_item_apropriacoes`.`subgrupo3_id`
	AND `orcamentos`.`servico_id` = `contrato_item_apropriacoes`.`servico_id`
	AND `orcamentos`.`obra_id` = $contrato->obra_id
	AND `orcamentos`.`ativo` = 1
WHERE
	`contrato_itens`.`contrato_id` = $contrato->id
GROUP BY
	`contrato_item_apropriacoes`.`id`
EOFSQL;

        return ContratoItemApropriacao::hydrate(DB::select(DB::raw($query)));
    }

    public function orcamentoInicial(Contrato $contrato)
    {
        return $this->model
            ->whereHas('contratoItem', function($query) use ($contrato) {
                $query->where('contrato_itens.contrato_id', $contrato->id);
            })
            ->join('orcamentos', function ($join) use ($contrato) {
                $join->on('orcamentos.insumo_id', 'contrato_item_apropriacoes.insumo_id');
                $join->on('orcamentos.grupo_id', 'contrato_item_apropriacoes.grupo_id');
                $join->on('orcamentos.subgrupo1_id', 'contrato_item_apropriacoes.subgrupo1_id');
                $join->on('orcamentos.subgrupo2_id', 'contrato_item_apropriacoes.subgrupo2_id');
                $join->on('orcamentos.subgrupo3_id', 'contrato_item_apropriacoes.subgrupo3_id');
                $join->on('orcamentos.servico_id', 'contrato_item_apropriacoes.servico_id');
                $join->on('orcamentos.obra_id', DB::raw($contrato->obra_id));
                $join->on('orcamentos.ativo', DB::raw('1'));
            })
            ->sum('orcamentos.preco_total');
    }
}
