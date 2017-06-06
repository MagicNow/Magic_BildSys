<?php

namespace App\Repositories;

use App\Models\ContratoItem;
use App\Models\ContratoStatus;
use Illuminate\Support\Facades\DB;
use App\Models\ContratoItemModificacao;
use InfyOm\Generator\Common\BaseRepository;
use App\Repositories\ContratoItemRepository;
use Illuminate\Http\Exception\HttpResponseException;

class ContratoItemModificacaoRepository extends BaseRepository
{
    public function model()
    {
        return ContratoItemModificacao::class;
    }

    public function reajustar($id, $data)
    {
        $modificacao = DB::transaction(function() use ($id, $data) {
            $contratoItemRepository = app(ContratoItemRepository::class);
            $modificacaoLogRepository = app(ContratoItemModificacaoLogRepository::class);
            $item = $contratoItemRepository->find($id);

            $qtd = $data['qtd'] ? money_to_float($data['qtd']) : 0;

            $modificacao = $this->create([
                'qtd_anterior'            => $item->qtd,
                'qtd_atual'               => ($item->qtd + money_to_float($data['qtd'] ?: 0)),
                'valor_unitario_anterior' => $item->valor_unitario,
                'valor_unitario_atual'    => money_to_float($data['valor']),
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

            $item->update(['aprovado' => 0]);

            return $modificacao;
        });

        return $modificacao;
    }

    public function distratar($id, $quantidade)
    {
        $quantidade = money_to_float($quantidade ?: 0);

        $modificacao = DB::transaction(function() use ($id, $quantidade) {
            $contratoItemRepository = app(ContratoItemRepository::class);
            $modificacaoLogRepository = app(ContratoItemModificacaoLogRepository::class);
            $item = $contratoItemRepository->find($id);

            if($item->qtd < $quantidade) {
                $response = response()->json([
                    'A nova quantidade não pode ser maior que a atual'
                ], 422);

                throw new HttpResponseException($response);
            }

            $modificacao = $this->create([
                'qtd_anterior'            => $item->qtd,
                'qtd_atual'               => $quantidade,
                'valor_unitario_anterior' => $item->valor_unitario,
                'valor_unitario_atual'    => $item->valor_unitario,
                'contrato_status_id'      => ContratoStatus::EM_APROVACAO,
                'contrato_item_id'        => $item->id,
                'tipo_modificacao'        => 'Distrato',
                'user_id'                 => auth()->id(),
            ]);

            $modificacaoLogRepository->create([
                'contrato_item_modificacao_id' => $modificacao->id,
                'contrato_status_id'           => ContratoStatus::EM_APROVACAO,
                'user_id'                      => $modificacao->user_id,
            ]);

            $item->update(['aprovado' => 0]);

            return $modificacao;
        });

        return $modificacao;
    }

    public function reajusteFornecedor($fornecedor_id, $obra_id, $reajustes)
    {
        $reajustes_criados = DB::transaction(function() use ($fornecedor_id, $obra_id, $reajustes) {
            $modificacaoLogRepository = app(ContratoItemModificacaoLogRepository::class);
            $modificacoes = [];
            foreach ($reajustes as $insumo_id => $novo_valor){
                $itens = ContratoItem::where('insumo_id',$insumo_id)
                    ->select('contrato_itens.*')
                    ->join('contratos','contratos.id','contrato_itens.contrato_id')
                    ->whereIn('contratos.obra_id',$obra_id)
                    ->where('contratos.fornecedor_id',$fornecedor_id)
                    ->get();
                if($itens->count()){
                    foreach ($itens as $item){

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

                        $item->update(['aprovado' => 0]);

                        $modificacoes[] = $modificacao;
                    }
                }
            }

            return $modificacoes;
        });

        return $reajustes_criados;
    }
}
