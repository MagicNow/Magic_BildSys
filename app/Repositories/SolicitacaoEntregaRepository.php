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
            $nome_anexo = CodeRepository::saveFile($request['anexo'], 'contratos/'.$request['contrato_id'].'/solicitacoes-de-entrega');

            $solicitacaoEntrega = parent::create([
                'contrato_id'   => $request['contrato_id'],
                'user_id'       => auth()->id(),
                'se_status_id'  => SeStatus::EM_APROVACAO,
                'valor_total'   => 0,
                'fornecedor_id' => $fornecedor_id,
                'anexo'         => $nome_anexo
            ]);

            SeStatusLog::create([
                'se_status_id'           => SeStatus::EM_APROVACAO,
                'user_id'                => auth()->id(),
                'solicitacao_entrega_id' => $solicitacaoEntrega->id,
            ]);

            $solicitacao
                ->groupBy('contrato_item_id')
                ->each(function($solicitacao, $contrato_id) use($solicitacaoEntrega, $fornecedor_id) {
                    $contratoItem = ContratoItem::find($contrato_id);

                    if(!$contratoItem->insumo->is_faturamento_direto && !$fornecedor_id) {
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
                                'insumo_id'              => $solicitacao->insumo,
                                'qtd'                    => $solicitacao->qtd,
                                'valor_unitario'         => $solicitacao->valor_unitario,
                                'valor_total'            => (float) $solicitacao->valor_unitario * (float) $solicitacao->qtd,
                            ]);

                            return SeApropriacao::create([
                                'contrato_item_apropriacao_id' => $solicitacao->apropriacao,
                                'solicitacao_entrega_item_id'  => $solicitacaoEntregaItem->id,
                                'qtd'                          => $solicitacao->qtd
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

    public function update(array $request, $id)
    {
        $solicitacao = $this->find($id);

        DB::beginTransaction();
        try {
            foreach($request['solicitacao'] as $se_apropriacao) {
                $se_apropriacao_model = SeApropriacao::findOrFail($se_apropriacao['apropriacao']);

                if(isset($se_apropriacao['qtd'])) {
                    $se_apropriacao_model->qtd = $se_apropriacao['qtd'];
                    $se_apropriacao_model->save();
                }

                if(isset($se_apropriacao['value'])) {
                    $se_apropriacao_model
                        ->solicitacaoEntregaItem
                        ->update([
                            'valor_unitario' => $se_apropriacao['value'],
                            'valor_total' => $se_apropriacao['value'] * $se_apropriacao_model->solicitacaoEntregaItem->qtd
                        ]);
                }

                $se_apropriacao_model->solicitacaoEntregaItem->updateQtd();
            }

            $solicitacao->update([
                'se_status_id' => SeStatus::EM_APROVACAO,
                'valor_total' => $solicitacao->total
            ]);
            SeStatusLog::create([
                'se_status_id'           => SeStatus::EM_APROVACAO,
                'user_id'                => auth()->id(),
                'solicitacao_entrega_id' => $solicitacao->id,
            ]);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();


        return $solicitacao;
    }

    public function cancel($id)
    {
        $solicitacao = $this->find($id);

        $solicitacao->update([
            'se_status_id' => SeStatus::CANCELADO
        ]);

        return $solicitacao;
    }

    public function received($id)
    {
        $solicitacao = $this->find($id);

        $solicitacao->update([
            'se_status_id' => SeStatus::RECEBIDO
        ]);

        return $solicitacao;
    }
}
