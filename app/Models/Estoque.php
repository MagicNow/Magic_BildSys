<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    public $table = 'estoque';

    public $fillable = [
        'obra_id',
        'insumo_id',
        'qtde',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function obra()
    {
        return $this->belongsTo(\App\Models\Obra::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function insumo()
    {
        return $this->belongsTo(\App\Models\Insumo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function estoqueTransacao()
    {
        return $this->hasMany(\App\Models\EstoqueTransacao::class, 'estoque_id');
    }

    /**
     * Soma a quantidade que está em estoque_transacao, para saber a quantidade tem em estoque.
     **/
    public function qtdEmEstoque()
    {
        return $this->estoqueTransacao->sum('qtde');
    }

    /**
     * Define o farol da quantidade que está em estoque
     **/
    public function farolQtdEmEstoque()
    {
        $qtd_minima = QtdMinima::where('insumo_id', $this->insumo->id)
            ->where('obra_id', $this->obra->id)
            ->first();

        $qtd_em_estoque = $this->qtdEmEstoque();
        $insumo_qtd_minima = $qtd_minima ? $qtd_minima->qtd : null;
        $porcentagem = null;
        $cor = null;

        if($insumo_qtd_minima) {
            $porcentagem = ($insumo_qtd_minima / $qtd_em_estoque) * 100;

            if($porcentagem > 150 && $porcentagem <= 200) {
                $cor = '#ffff00';
            } elseif($porcentagem > 100 && $porcentagem <= 150) {
                $cor = '#eb0000';
            } elseif ($porcentagem <= 100) {
                $cor = '#000000';
            }
        }

        return ['cor' => $cor, 'porcentagem' => $porcentagem];
    }

    /**
     * Calcula a quantidade previsto para insumo que está no estoque.
     **/
    public function qtdPrevista()
    {
        $qtd_prevista = 0;
        
        foreach($this->estoqueTransacao as $transacao) {
            $contrato_item_apropriacao = $transacao->contratoItemApropriacao;

            if($contrato_item_apropriacao) {
                $qtd_prevista += Orcamento::where('grupo_id', $contrato_item_apropriacao->grupo_id)
                    ->where('subgrupo1_id', $contrato_item_apropriacao->subgrupo1_id)
                    ->where('subgrupo2_id', $contrato_item_apropriacao->subgrupo2_id)
                    ->where('subgrupo3_id', $contrato_item_apropriacao->subgrupo3_id)
                    ->where('servico_id', $contrato_item_apropriacao->servico_id)
                    ->where('obra_id', $contrato_item_apropriacao->contratoItem->contrato->obra_id)
                    ->where('ativo', 1)
                    ->sum('qtd_total');
            }
        }
        
        return $qtd_prevista;
    }

    /**
    * Calcula a quantidade aplicada para insumo que está no estoque.
    **/
    public function qtdAplicada()
    {
        $qtd_aplicada = 0;

        foreach($this->estoqueTransacao as $transacao) {
            $qtd_aplicada += AplicacaoEstoqueInsumo::where('requisicao_id', $transacao->requisicao_id)->sum('qtd');
        }
        
        return $qtd_aplicada;
    }

    /**
    * Calcula a quantidade requisitada para insumo que está no estoque.
    **/
    public function qtdRequisitada()
    {
        $qtd_requisitada = 0;

        foreach($this->estoqueTransacao as $transacao) {
            $requisicao = Requisicao::find($transacao->requisicao_id);
            if($requisicao) {
                if($requisicao->requisicaoItens) {
                    $qtd_requisitada += $requisicao->requisicaoItens->where('status_id', RequisicaoStatus::NOVA)->sum('qtde');
                }
            }
        }

        return $qtd_requisitada;
    }

    /**
     * Calcula a quantidade em separação do insumo que está no estoque.
     **/
    public function qtdEmSeparacao()
    {
        $qtd_em_separacao = 0;

        foreach($this->estoqueTransacao as $transacao) {
            $requisicao = Requisicao::find($transacao->requisicao_id);
            $qtd_em_separacao += $requisicao->requisicaoItens->where('status_id', RequisicaoStatus::EM_SEPARACAO)->sum('qtde');
        }

        return $qtd_em_separacao;
    }

    /**
     * Calcula a quantidade em trânsito do insumo que está no estoque.
     **/
    public function qtdEmTransito()
    {
        $qtd_em_transito = 0;

        foreach($this->estoqueTransacao as $transacao) {
            $requisicao = Requisicao::find($transacao->requisicao_id);
            $qtd_em_transito += $requisicao->requisicaoItens->where('status_id', RequisicaoStatus::EM_TRANSITO)->sum('qtde');
        }

        return $qtd_em_transito;
    }

    /**
     * Soma de todas as quantidades contratadas de todos os contratos, da obra, nas apropriações.
     **/

    public function qtdContratada()
    {
        $qtd_contratada = 0;

        $contratos = Contrato::where('obra_id', $this->obra->id)
            ->get();

        if($contratos) {
            foreach($contratos as $contrato) {
                if($contrato->itens) {
                    foreach($contrato->itens as $contrato_item) {
                        $qtd_contratada += $contrato_item->apropriacoes->sum('qtd');
                    }
                }
            }
        }

        return $qtd_contratada;
    }

    /**
     * Soma de todas as quantidades realizadas de todos os contratos, da obra, nas apropriações.
     **/

    public function qtdRealizada()
    {
        $qtd_realizada = 0;

        $contratos = Contrato::where('obra_id', $this->obra->id)
            ->pluck('id', 'id');

        $notas_fiscais = Notafiscal::whereIn('contrato_id', $contratos)
            ->get();

        if($notas_fiscais) {
            foreach($notas_fiscais as $nota_fiscal) {
                $qtd_realizada += $nota_fiscal->itens->sum('qtd');
            }
        }
        return $qtd_realizada;
    }
}
