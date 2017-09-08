<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Estrutura
 * @package App\Models
 * @version April 25, 2017, 2:16 pm BRT
 */
class Estrutura extends Model
{
    public $table = 'estruturas';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];


    public $fillable = [
        'torre',
		'pavimento',
		'trecho'
		
    ];

    public static $campos = [
        'torre',
		'pavimento',
		'trecho'        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer'       
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
	
    ];
	
    
}
