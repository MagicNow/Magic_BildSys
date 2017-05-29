<?php

namespace App\Models;

use Eloquent as Model;

class ContratoStatusLog extends Model
{
    public $table = 'contrato_status_log';

    const UPDATED_AT = null;

    protected $dates = ['deleted_at'];

    public $timestamps = ['created_at'];

    public $fillable = [
        'contrato_id',
        'contrato_status_id',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'contrato_id' => 'integer',
        'contrato_status_id' => 'integer',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function contrato()
    {
        return $this->belongsTo(\App\Models\Contrato::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function contratoStatus()
    {
        return $this->belongsTo(\App\Models\ContratoStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
