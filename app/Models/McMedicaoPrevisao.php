<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class McMedicaoPrevisao
 * @package App\Models
 * @version June 28, 2017, 6:59 pm BRT
 */
class McMedicaoPrevisao extends Model
{
    use SoftDeletes;

    public $table = 'mc_medicao_previsoes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'obra_torre_id',
        'insumo_id',
        'memoria_calculo_bloco_id',
        'contrato_item_apropriacao_id',
        'contrato_item_id',
        'planejamento_id',
        'qtd',
        'unidade_sigla',
        'user_id',
        'data_competencia'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'obra_torre_id' => 'integer',
        'insumo_id' => 'integer',
        'memoria_calculo_bloco_id' => 'integer',
        'contrato_item_apropriacao_id' => 'integer',
        'contrato_item_id' => 'integer',
        'planejamento_id' => 'integer',
        'unidade_sigla' => 'string',
        'user_id' => 'integer',
        'data_competencia' => 'date'
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
    public function contratoItemApropriaco()
    {
        return $this->belongsTo(\App\Models\ContratoItemApropriaco::class);
    }

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
    public function insumo()
    {
        return $this->belongsTo(\App\Models\Insumo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function memoriaCalculoBloco()
    {
        return $this->belongsTo(\App\Models\MemoriaCalculoBloco::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function obraTorre()
    {
        return $this->belongsTo(\App\Models\ObraTorre::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function planejamento()
    {
        return $this->belongsTo(\App\Models\Planejamento::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function unidade()
    {
        return $this->belongsTo(\App\Models\Unidade::class);
    }
}
