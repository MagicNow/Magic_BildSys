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
use App\Models\Contrato;

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

    public function forContratoDetails(Contrato $contrato)
    {
        return $this->model->select([
            'contrato_itens.*',
        ])
        ->where('contrato_itens.contrato_id', $contrato->id)
        ->get();
    }
}
