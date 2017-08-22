<?php

namespace App\Models;

use Eloquent as Model;

class ContratoItemModificacaoApropriacao extends Model
{
    public $table = 'contrato_item_modificacao_apropriacao';

    public $fillable = [
        'contrato_item_modificacao_id',
        'contrato_item_apropriacao_id',
        'qtd_atual',
        'qtd_anterior',
        'descricao',
        'anexo'
    ];

    public $casts = [
        'qtd_anterior' => 'float',
        'qtd_atual' => 'float',
        'contrato_item_modificacao_id' => 'integer',
        'contrato_item_apropriacao_id' => 'integer'
    ];

    public function modificacao()
    {
        return $this->belongsTo(
            ContratoItemModificacao::class,
            'contrato_item_modificacao_id'
        );
    }

    public function apropriacao()
    {
        return $this->belongsTo(
            ContratoItemApropriacao::class,
            'contrato_item_apropriacao_id'
        );
    }
}
