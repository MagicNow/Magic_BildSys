<?php

namespace App\Repositories;

use Exception;
use App\Models\SeStatus;
use App\Models\SeStatusLog;
use App\Models\SeApropriacao;
use App\Models\SolicitacaoEntrega;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitacaoEntregaItem;
use InfyOm\Generator\Common\BaseRepository;
use App\Models\ContratoItem;
use Illuminate\Support\Arr;

class SolicitacaoEntregaRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SolicitacaoEntrega::class;
    }

    public function create(array $request)
    {
        $solicitacao = collect($request['solicitacao']);
        $fornecedor_id = Arr::get($request, 'fornecedor_id');

        DB::beginTransaction();
        try {
            $solicitacaoEntrega = parent::create([
                'contrato_id'   => $request['contrato_id'],
                'user_id'       => auth()->id(),
                'se_status_id'  => SeStatus::EM_APROVACAO,
                'valor_total'   => 0,
                'fornecedor_id' => $fornecedor_id
            ]);

            SeStatusLog::create([
                'se_status_id'           => SeStatus::EM_APROVACAO,
                'user_id'                => auth()->id(),
                'solicitacao_entrega_id' => $solicitacaoEntrega->id,
            ]);

            $solicitacao
                ->groupBy('contrato_item_id')
                ->each(function($solicitacao, $contrato_id) use($solicitacaoEntrega) {
                    $contratoItem = ContratoItem::find($contrato_id);

                    if(!in_array($contratoItem->insumo->codigo, [34007, 30019]) && !$fornecedor_id) {
                        $solicitacaoEntregaItem = SolicitacaoEntregaItem::create([
                            'solicitacao_entrega_id' => $solicitacaoEntrega->id,
                            'contrato_item_id'       => $contrato_id,
                            'insumo_id'              => $contratoItem->insumo_id,
                            'qtd'                    => $solicitacao->sum('qtd'),
                            'valor_unitario'         => $contratoItem->valor_unitario,
                            'valor_total'            => $contratoItem->valor_unitario * $solicitacao->sum('qtd'),
                        ]);

                        $solicitacao->map(function($apropriacao) use ($solicitacaoEntregaItem) {
                            return SeApropriacao::create([
                                'contrato_item_apropriacao_id' => $apropriacao['apropriacao'],
                                'solicitacao_entrega_item_id'  => $solicitacaoEntregaItem->id,
                                'qtd'                          => $apropriacao['qtd']
                            ]);
                        });
                    } else {
                        $solicitacao->map(function($solicitacao) use ($contratoItem, $solicitacaoEntrega) {
                            $solicitacaoEntregaItem = SolicitacaoEntregaItem::create([
                                'solicitacao_entrega_id' => $solicitacaoEntrega->id,
                                'contrato_item_id'       => $contratoItem->id,
                                'insumo_id'              => $contratoItem->insumo_id,
                                'qtd'                    => $solicitacao['qtd'],
                                'valor_unitario'         => $solicitacao['valor_unitario'],
                                'valor_total'            => (float) $solicitacao['valor_unitario'] * (float) $solicitacao['qtd'],
                            ]);

                            return SeApropriacao::create([
                                'contrato_item_apropriacao_id' => $solicitacao['apropriacao'],
                                'solicitacao_entrega_item_id'  => $solicitacaoEntregaItem->id,
                                'qtd'                          => $solicitacao['qtd']
                            ]);
                        });
                    }
                });

            $solicitacaoEntrega->updateTotal();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return $solicitacaoEntrega;
    }

    public function fromContrato($id)
    {
        return $this->model->where('contrato_id', $id)->get();
    }

}
