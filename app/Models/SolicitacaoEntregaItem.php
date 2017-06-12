<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitacaoEntregaItem extends Model
{
    public $table = 'solicitacao_entrega_itens';

    public $fillable = [
        'solicitacao_entrega_id',
        'contrato_item_id',
        'insumo_id',
        'qtd',
        'valor_total',
        'valor_unitario',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'solicitacao_entrega_id' => 'integer',
        'insumo_id' => 'integer',
        'contrato_item_id' => 'integer',
        'valor_total' => 'float',
        'valor_decimal' => 'float',
        'qtd' => 'float'
    ];

    public function solicitacaoEntrega()
    {
        return $this->belongsTo(
            SolicitacaoEntrega::class,
            'solicitacao_entrega_id'
        );
    }

    public function contratoItem()
    {
        return $this->belongsTo(
            ContratoItem::class,
            'contrato_item_id'
        );
    }

    public function insumo()
    {
        return $this->belongsTo(
            Insumo::class,
            'insumo_id'
        );
    }
}
