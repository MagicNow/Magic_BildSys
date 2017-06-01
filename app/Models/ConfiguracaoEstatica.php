<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class ConfiguracaoEstatica
 * @package App\Models
 * @version June 1, 2017, 4:54 pm BRT
 */
class ConfiguracaoEstatica extends Model
{
    public $table = 'configuracao_estaticas';

    public $timestamps = false;

    public $fillable = [
        'chave',
        'valor'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'chave' => 'string',
        'valor' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
