<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Requisicao
 * @package App\Models
 * @version September 22, 2017, 8:14 am -03
 */
class RequisicaoSaidaLeitura extends Model
{
    public $table = 'requisicao_itens';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'requisicao_item_id',
        'qtd_lida'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'requisicao_item_id' => 'integer',
        'qtd_lida' => 'integer'

    ];

}
