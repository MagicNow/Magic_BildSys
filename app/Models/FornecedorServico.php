<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class FornecedorServico
 * @package App\Models
 * @version May 10, 2017, 2:31 pm BRT
 */
class FornecedorServico extends Model
{
    public $table = 'fornecedor_servicos';

    public $timestamps = false;

    public $fillable = [
        'codigo_fornecedor_id',
        'codigo_servico_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'codigo_fornecedor_id' => 'integer',
        'codigo_servico_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
