<?php

namespace App\Repositories;

use App\Models\ContratoItem;
use App\Models\ContratoStatus;
use Illuminate\Support\Facades\DB;
use App\Models\ContratoItemModificacao;
use InfyOm\Generator\Common\BaseRepository;
use App\Repositories\ContratoItemRepository;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Support\Facades\Notification;
use App\Repositories\WorkflowAprovacaoRepository;
use App\Notifications\WorkflowNotification;
use App\Models\ContratoItemModificacaoApropriacao;

class ContratoItemModificacaoRepository extends BaseRepository
{
    public function model()
    {
        return ContratoItemModificacao::class;
    }

    public function reajustar($contrato_item_id, $data)
    {
        $modificacao = DB::transaction(function () use ($contrato_item_id, $data) {
            $contratoItemRepository = app(ContratoItemRepository::class);
            $modificacaoLogRepository = app(ContratoItemModificacaoLogRepository::class);

            $item = $contratoItemRepository->find($contrato_item_id);

            if(isset($data['reajuste'])) {
                $reajustes = collect($data['reajuste']);
                $qtd = $reajustes->map('money_to_float')->sum();
                $apropriacoes = app(ContratoItemApropriacaoRepository::class)
                    ->findWhereIn('id', $reajustes->keys()->all());

                $modApropriacoes = $reajustes
                    ->map(function($qtd, $apropriacao_id) use ($apropriacoes) {
                        $apropriacao = $apropriacoes->where('id', $apropriacao_id)
                            ->first();

                        return [
                            'contrato_item_apropriacao_id' => $apropriacao_id,
                            'qtd_anterior' => $apropriacao->qtd,
                            'qtd_atual' => $qtd + $apropriacao->qtd,
                        ];
                    });
            } else {
                $qtd = 0;
                $modApropriacoes = collect();
            }

            $destinationPath = null;

            if($data['anexo']) {
                $destinationPath = CodeRepository::saveFile($data['anexo'], 'contratos/reajustes/' . $item->id);
            }
            
            $modificacao = $this->create([
                'qtd_anterior'            => $item->qtd,
                'qtd_atual'               => $item->qtd + $qtd,
                'valor_unitario_anterior' => $item->valor_unitario,
                'valor_unitario_atual'    => money_to_float($data['valor_unitario']),
                'contrato_status_id'      => ContratoStatus::EM_APROVACAO,
                'contrato_item_id'        => $item->id,
                'tipo_modificacao'        => 'Reajuste',
                'anexo'                   => $destinationPath,
                'user_id'                 => auth()->id(),
            ]);

            $modificacaoLogRepository->create([
                'contrato_item_modificacao_id' => $modificacao->id,
                'contrato_status_id'           => ContratoStatus::EM_APROVACAO,
                'user_id'                      => $modificacao->user_id,
            ]);

            $modApropriacoes->map(function($modApro) use ($modificacao) {
                $modApro['contrato_item_modificacao_id'] = $modificacao->id;
                return ContratoItemModificacaoApropriacao::create($modApro);
            });

            $item->update(['pendente' => 1]);

            return $modificacao;
        });

        $aprovadores = WorkflowAprovacaoRepository::usuariosDaAlcadaAtual($modificacao);

        Notification::send($aprovadores, new WorkflowNotification($modificacao));

        return $modificacao;
    }

    public function distratar($contrato_item_id, $distratos)
    {
        $distratos = collect($distratos)->map('money_to_float');

        $modificacao = DB::transaction(function () use ($distratos, $contrato_item_id) {
            $contratoItemRepository = app(ContratoItemRepository::class);
            $apropriacaoRepository = app(ContratoItemApropriacaoRepository::class);
            $modificacaoLogRepository = app(ContratoItemModificacaoLogRepository::class);

            $contrato_item = $contratoItemRepository->find($contrato_item_id);

            $itens = $apropriacaoRepository->findWhereIn(
                'id',
                $distratos->keys()->all()
            );

            $modApropriacao = $distratos
                ->map(function($quantidade, $item_id) use ($itens) {
                    $item = $itens->where('id', $item_id)->first();

                    if ($item->qtd < $quantidade) {
                        $response = response()->json([
                            'A nova quantidade não pode ser maior que a atual'
                        ], 422);

                        throw new HttpResponseException($response);
                    }

                    return [
                        'contrato_item_apropriacao_id' => $item_id,
                        'qtd_atual' => $item->qtd - $quantidade,
                        'qtd_anterior' => $item->qtd,
                        'distratar' => $quantidade,
                    ];
                });

            $modificacao = $this->create([
                'qtd_anterior'            => $contrato_item->qtd,
                'qtd_atual'               => $contrato_item->qtd - $modApropriacao->sum('distratar'),
                'valor_unitario_anterior' => $contrato_item->valor_unitario,
                'valor_unitario_atual'    => $contrato_item->valor_unitario,
                'contrato_status_id'      => ContratoStatus::EM_APROVACAO,
                'contrato_item_id'        => $contrato_item->id,
                'tipo_modificacao'        => 'Distrato',
                'user_id'                 => auth()->id(),
            ]);


            $modificacaoLogRepository->create([
                'contrato_item_modificacao_id' => $modificacao->id,
                'contrato_status_id'           => ContratoStatus::EM_APROVACAO,
                'user_id'                      => $modificacao->user_id,
            ]);

            $modApropriacao->map(function($modApro) use ($modificacao) {
                $modApro['contrato_item_modificacao_id'] = $modificacao->id;
                return ContratoItemModificacaoApropriacao::create($modApro);
            });

            $contrato_item->update(['pendente' => 1]);

            return $modificacao;
        });

        $aprovadores = WorkflowAprovacaoRepository::usuariosDaAlcadaAtual($modificacao);

        Notification::send($aprovadores, new WorkflowNotification($modificacao));

        return $modificacao;
    }

    public function reajusteFornecedor($fornecedor_id, $obra_id, $reajustes)
    {
        $reajustes_criados = DB::transaction(function () use ($fornecedor_id, $obra_id, $reajustes) {
            $modificacaoLogRepository = app(ContratoItemModificacaoLogRepository::class);
            $modificacoes = [];
            foreach ($reajustes as $insumo_id => $novo_valor) {

                $itens = ContratoItem::where('insumo_id', $insumo_id)
                    ->select('contrato_itens.*')
                    ->join('contratos', 'contratos.id', 'contrato_itens.contrato_id')
                    ->whereIn('contratos.obra_id', $obra_id)
                    ->where('contratos.fornecedor_id', $fornecedor_id)
                    ->get();

                if ($itens->count()) {
                    foreach ($itens as $item) {
                        $modificacao = $this->create([
                            'qtd_anterior'            => $item->qtd,
                            'qtd_atual'               => $item->qtd,
                            'valor_unitario_anterior' => $item->valor_unitario,
                            'valor_unitario_atual'    => money_to_float($novo_valor),
                            'contrato_status_id'      => ContratoStatus::EM_APROVACAO,
                            'contrato_item_id'        => $item->id,
                            'tipo_modificacao'        => 'Reajuste',
                            'user_id'                 => auth()->id(),
                        ]);

                        $modificacaoLogRepository->create([
                            'contrato_item_modificacao_id' => $modificacao->id,
                            'contrato_status_id'           => ContratoStatus::EM_APROVACAO,
                            'user_id'                      => $modificacao->user_id,
                        ]);

                        $item->update(['pendente' => 1]);

                        $aprovadores = WorkflowAprovacaoRepository::usuariosDaAlcadaAtual($modificacao);
                        Notification::send($aprovadores, new WorkflowNotification($modificacao));

                        $modificacoes[] = $modificacao;
                    }
                }
            }

            return $modificacoes;
        });

        return $reajustes_criados;
    }
}
