<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Notificacao
 * @package App\Models
 * @version April 10, 2017, 11:41 am BRT
 */
class Notificacao extends Model
{

    public $table = 'notifications';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'type',
        'notifiable_id',
        'notifiable_type',
        'data',
        'read_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'type' => 'string',
        'notifiable_id' => 'integer',
        'notifiable_type' => 'string',
        'data' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
