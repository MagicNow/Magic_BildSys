<?php

namespace App\Models;

use Eloquent as Model;

class Notification extends Model
{
    public $table = 'notifications';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'type',
        'notifiable_id',
        'notifiable_type',
        'data',
        'read_at',
        'task_done'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'type' => 'string',
        'task_done' => 'integer',
        'notifiable_id' => 'integer',
        'notifiable_type' => 'string',
        'data' => 'object'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
}
