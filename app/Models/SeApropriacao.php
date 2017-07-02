<?php

namespace App\Models;

use Eloquent as Model;

class SeApropriacao extends Model
{
    public $table = 'se_apropriacoes';

    public $timestamps = false;

    public $fillable = [
        'solicitacao_entrega_item_id',
        'contrato_item_apropriacao_id',
        'qtd'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                           => 'integer',
        'solicitacao_entrega_item_id'  => 'integer',
        'contrato_item_apropriacao_id' => 'integer',
        'qtd'                          => 'float'
    ];
}
