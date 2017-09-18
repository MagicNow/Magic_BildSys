<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class TarefaPadrao
 * @package App\Models
 * @version April 25, 2017, 2:16 pm BRT
 */
class TarefaPadrao extends Model
{
    public $table = 'tarefa_padrao';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'tarefa',
		'resumo',
		'critica',
		'torre',
		'pavimento',
    ];

    public static $campos = [
        'tarefa',        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tarefa' => 'string',        
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'tarefa' => 'required'
    ];
	    
	
	
}
