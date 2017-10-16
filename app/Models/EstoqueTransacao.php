<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstoqueTransacao extends Model
{
    public $table = 'estoque_transacao';

    public $fillable = [
        'estoque_id',
        'requisicao_id',
        'tipo',
        'qtde',
        'nf_se_item_id',
        'contrato_item_apropriacao_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function estoque()
    {
        return $this->belongsTo(\App\Models\Estoque::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function contratoItemApropriacao()
    {
        return $this->belongsTo(\App\Models\ContratoItemApropriacao::class);
    }
}
