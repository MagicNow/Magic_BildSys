<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class ContratoItemModificacaoLog
 * @package App\Models
 * @version May 18, 2017, 6:09 pm BRT
 */
class ContratoItemModificacaoLog extends Model
{
    public $table = 'contrato_item_modificacao_log';

    const UPDATED_AT = null;

    public $timestamps = ['created_at'];

    public $fillable = [
        'contrato_item_modificacao_id',
        'contrato_status_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'contrato_item_modificacao_id' => 'integer',
        'contrato_status_id' => 'integer'
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
    public function contratoStatus()
    {
        return $this->belongsTo(\App\Models\ContratoStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function contratoItemModificaco()
    {
        return $this->belongsTo(\App\Models\ContratoItemModificaco::class);
    }
}
