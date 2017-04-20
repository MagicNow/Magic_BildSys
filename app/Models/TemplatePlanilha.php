<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class TemplatePlanilha
 * @package App\Models\Admin
 * @version April 20, 2017, 12:33 pm BRT
 */
class TemplatePlanilha extends Model
{
    public $table = 'template_planilhas';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'nome',
        'modulo',
        'colunas'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
        'modulo' => 'string',
        'colunas' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
