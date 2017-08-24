<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CronogramaFisico
 * @package App\Models
 * @version April 5, 2017, 11:58 am BRT
 */
class CronogramaFisico extends Model
{    

    public $table = 'cronograma_fisicos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'obra_id',                
        'custo',
		'resumo',
		'torre',
		'pavimento',
		'tarefa',
		'critica',
		'data_inicio',
        'data_termino',
        'concluida',        
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
        'custo' => 'string',
        'resumo' => 'string',
		'torre' => 'string',
		'pavimento' => 'string',
		'tarefa' => 'string',
		'critica' => 'string',
		'concluida' => 'string'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
	 //["custo","resumo","torre","pavimento","tarefa","critica","data_inicio","data_termino","concluida"]
    public static $relation = [
        'custo' => 'string',
        'resumo' => 'string',
		'torre' => 'string',
		'pavimento' => 'string',
		'tarefa' => 'string',
		'critica' => 'string',
        'data_inicio' => 'date',
        'data_termino' => 'date',
		'concluida' => 'string'

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'obra_id' => 'required',
        'custo' => 'required',
        'resumo' => 'required',
		'torre' => 'required',
		'pavimento' => 'required',
		'tarefa' => 'required',
		'critica' => 'required',
        'data_inicio' => 'required',
        'data_termino' => 'required',
		'concluida' => 'required'
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
    

}
