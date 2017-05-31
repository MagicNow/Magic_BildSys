<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class ContratoItemReapropriacao
 * @package App\Models
 * @version May 18, 2017, 6:08 pm BRT
 */
class ContratoItemReapropriacao extends Model
{
    public $table = 'contrato_item_reapropriacoes';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'contrato_item_id',
        'ordem_de_compra_item_id',
        'codigo_insumo',
        'grupo_id',
        'subgrupo1_id',
        'subgrupo2_id',
        'subgrupo3_id',
        'servico_id',
        'insumo_id',
        'qtd',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'contrato_item_id' => 'integer',
        'ordem_de_compra_item_id' => 'integer',
        'codigo_insumo' => 'string',
        'grupo_id' => 'integer',
        'subgrupo1_id' => 'integer',
        'subgrupo2_id' => 'integer',
        'subgrupo3_id' => 'integer',
        'servico_id' => 'integer',
        'insumo_id' => 'integer',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
}
