<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FornecedorServico
 * @package App\Models
 * @version May 10, 2017, 2:31 pm BRT
 */
class FornecedorServico extends Model
{
    use SoftDeletes;

    public $table = 'fornecedor_servicos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'cod_fornecedor',
        'cod_servico'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'cod_fornecedor' => 'integer',
        'cod_servico' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
