<?php

namespace App\Repositories;

use App\Models\ContratoItemApropriacao;
use InfyOm\Generator\Common\BaseRepository;
use App\Models\ContratoItem;
use App\Models\Contrato;
use Illuminate\Support\Facades\DB;
use App\Models\OrdemDeCompraItemAnexo;
use App\Models\QcFornecedor;

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
        $data['insumo_id']                      = $contratoItem->insumo_id;
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
    orcamentos.trocado,
    orcamentos.orcamento_que_substitui,
    ordem_de_compra_itens.total,
    ordem_de_compra_itens.motivo_nao_finaliza_obra,
    ordem_de_compra_itens.justificativa,
    ordem_de_compra_itens.obs,
    ordem_de_compra_itens.tems,
    ordem_de_compra_itens.id as oc_id,
    ordem_de_compra_itens.emergencial,
    ordem_de_compra_itens.id as ordem_de_compra_item_id,
    orcamentos.id as orcamento_id,
    insumo_troca.nome as insumo_troca_nome,
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
    ) as valor_servico,
    qc_fornecedor.porcentagem_material,
    qc_fornecedor.porcentagem_faturamento_direto,
    qc_fornecedor.porcentagem_servico,
    qc_fornecedor.porcentagem_locacao
FROM
	`contrato_item_apropriacoes`
INNER JOIN `contrato_itens` ON
	`contrato_itens`.`id` = `contrato_item_apropriacoes`.`contrato_item_id`
INNER JOIN `contratos` ON
	`contrato_itens`.`contrato_id` = `contratos`.`id`
INNER JOIN `quadro_de_concorrencias` ON
	`quadro_de_concorrencias`.`id` = `contratos`.`quadro_de_concorrencia_id`
INNER JOIN `qc_fornecedor` ON
    `qc_fornecedor`.`quadro_de_concorrencia_id` = `contratos`.`quadro_de_concorrencia_id`
    AND `qc_fornecedor`.`fornecedor_id` = `contratos`.`fornecedor_id`
    AND `qc_fornecedor`.`rodada` = `quadro_de_concorrencias`.`rodada_atual`
INNER JOIN `ordem_de_compra_itens` ON
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
LEFT JOIN `orcamentos` `orcamento_troca` ON
    `orcamento_troca`.`id` = `orcamentos`.`orcamento_que_substitui`
LEFT JOIN `insumos` `insumo_troca` ON
    `insumo_troca`.`id` = `orcamento_troca`.`insumo_id`
WHERE
	`contrato_itens`.`contrato_id` = $contrato->id
GROUP BY
	`contrato_item_apropriacoes`.`id`
EOFSQL;

        $collection = ContratoItemApropriacao::hydrate(DB::select(DB::raw($query)));

        $oc_ids = $collection->pluck('oc_id')->filter()->all();

        $anexos = OrdemDeCompraItemAnexo::whereIn('ordem_de_compra_item_id', $oc_ids)
        ->get();

        $collection = $collection->map(function($item) use ($anexos) {
            $item->anexos = $anexos->where('ordem_de_compra_item_id', $item->oc_id);

            return $item;
        });

        $oc_itens = $collection;

        $contrato_itens = ContratoItem::where('contrato_id', $contrato->id)
            ->whereNull('qc_item_id')
            ->get();

        $qc_fornecedor = QcFornecedor::where('quadro_de_concorrencia_id', $contrato->quadro_de_concorrencia_id)
            ->where('rodada', $contrato->quadroDeConcorrencia->rodada_atual)
            ->where('fornecedor_id', $contrato->fornecedor_id)
            ->first();

        $contrato_itens->map(function($item) use ($qc_fornecedor) {
            $column = get_percentual_column($item->insumo->codigo);
            $item->porcentagem = $qc_fornecedor->{$column} ?: false;

            return $item;
        });

        return (object) compact('oc_itens', 'contrato_itens');
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

    public function fromContrato($contrato_id)
    {
        return $this->model
            ->whereHas('contratoItem', function($query) use ($contrato_id) {
                $query->where('contrato_id', $contrato_id);
            })
            ->get();
    }
}
