<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CronogramaFisico
 * @package App\Models
 * @version April 5, 2017, 11:58 am BRT
 */
class CronogramaFisico extends Model
{
    use SoftDeletes;

    public $table = 'cronograma_fisicos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'obra_id',
        'tarefa',
        'data',
        'data_fim',
        'prazo',
        'resumo',
        'planejamento_id',
        'data_upload'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'obra_id' => 'integer',
        'tarefa' => 'string',
        'prazo' => 'integer',
        'resumo' => 'string',
        'planejamento_id' => 'integer'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    public static $relation = [
        'resumo' => 'string',
        'tarefa' => 'string',
        'prazo' => 'integer',
        'data' => 'date',
        'data_fim' => 'date',


    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'obra_id' => 'required',
        'tarefa' => 'required',
        'data' => 'required',
        'data_fim' => 'required',
        'prazo' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cronogramaFisico()
    {
        return $this->belongsTo(\App\Models\CronogramaFisico::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function obra()
    {
        return $this->belongsTo(\App\Models\Obra::class);
    }
    

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function planejamentoCompras()
    {
        return $this->hasMany(\App\Models\PlanejamentoCompra::class);
    }
}
