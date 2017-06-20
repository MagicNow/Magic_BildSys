<?php

namespace App\Repositories;

use App\Models\ContratoItem;
use InfyOm\Generator\Common\BaseRepository;
use App\Models\ContratoStatus;
use Illuminate\Support\Facades\DB;
use App\Repositories\WorkflowAprovacaoRepository;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WorkflowNotification;
use App\Models\ContratoItemApropriacao;

class ContratoItemRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return ContratoItem::class;
    }

    public function editarAditivo($id, array $request)
    {
        DB::beginTransaction();
        try {
            $item = $this->find($id);
            $item->qtd = money_to_float($request['qtd']);
            $item->valor_unitario = money_to_float($request['valor']);
            $item->valor_total = $item->qtd * $item->valor_unitario;
            $item->pendente = true;

            $item->save();

            $mod = $item->modificacoes->first()->replicate()->fill([
                'valor_unitario_atual' => $item->valor_unitario,
                'qtd_atual'            => $item->qtd,
                'contrato_status_id'   => ContratoStatus::EM_APROVACAO,
                'contrato_item_id'
            ]);

            $mod->save();

            $aprovadores = WorkflowAprovacaoRepository::usuariosDaAlcadaAtual($mod);

            Notification::send($aprovadores, new WorkflowNotification($mod));

        } catch (Exception $e) {
            logger()->error((string) $e);
            DB::rollback();
        }

        DB::commit();

        return $item;
    }

    public function forContratoDetails($contrato_id)
    {
        return $this->model->select([
            'contrato_itens.*',
            'insumos.nome as insumo_nome',
            'insumos.unidade_sigla as insumo_unidade',
            'insumos.codigo as insumo_codigo',
            'insumos.aliq_irrf',
            'insumos.aliq_inss',
            'insumos.aliq_pis',
            'insumos.aliq_cofins',
            'insumos.aliq_csll'
        ])
        ->join('insumos', 'insumos.id', 'contrato_itens.insumo_id')
        ->leftJoin('contrato_item_modificacoes', 'contrato_itens.id', 'contrato_item_modificacoes.contrato_item_id')
        ->join('contrato_item_apropriacoes', 'contrato_itens.id', 'contrato_item_apropriacoes.contrato_item_id')
        ->where('contrato_itens.contrato_id', $contrato_id)
        ->groupBy('contrato_itens.id')
        ->get();
    }

    public function forContratoApproval($contrato)
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
}
