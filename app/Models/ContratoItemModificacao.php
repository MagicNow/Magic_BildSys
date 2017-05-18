<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ContratoItemModificacao
 * @package App\Models
 * @version May 18, 2017, 6:09 pm BRT
 */
class ContratoItemModificacao extends Model
{
    use SoftDeletes;

    public $table = 'contrato_item_modificacoes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'contrato_item_id',
        'qtd_aterior',
        'qtd_atual',
        'valor_unitario_anterior',
        'valor_unitario_atual',
        'tipo_modificacao',
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
        'contrato_item_id' => 'integer',
        'tipo_modificacao' => 'string',
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
    public function contratoIten()
    {
        return $this->belongsTo(\App\Models\ContratoIten::class);
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function contratoItemModificacaoLogs()
    {
        return $this->hasMany(\App\Models\ContratoItemModificacaoLog::class);
    }
}
