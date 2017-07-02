<?php

namespace App\Models;

use Eloquent as Model;

class SeStatusLog extends Model
{
    public $table = 'se_status_log';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    public $fillable = [
        'solicitacao_entrega_id',
        'se_status_id',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                     => 'integer',
        'solicitacao_entrega_id' => 'integer',
        'se_status_id'           => 'integer',
        'user_id'                => 'integer'
    ];
}
