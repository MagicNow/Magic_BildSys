<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Levantamento
 * @package App\Models
 * @version April 5, 2017, 11:58 am BRT
 */
class Levantamento extends Model
{

    public $table = 'levantamentos';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
		'obra_id',
        'apropriacao',
        'insumo',
		'torre',
		'andar',
		'pavimento',
		'trecho',
		'comodo',
		'parede',
		'apartamento',
		'trecho_parede',
		'personalizavel',
		'quantidade',
		'perda',
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
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
	 //["apropriacao","insumo","torre","andar","pavimento","trecho","apartamento","comodo","parede","trecho_parede","personalizavel","quantidade","perda"]
    public static $relation = [
		'apropriacao' => 'string',
		'insumo' => 'string',
		'torre' => 'string',
		'andar' => 'string',
		'pavimento' => 'string',
		'trecho' => 'string',
		'apartamento' => 'string',
		'comodo' => 'string',
		'parede' => 'string',
		'trecho_parede' => 'string',
		'personalizavel' => 'string',
		'quantidade' => 'integer',
		'perda' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'obra_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cronogramaFisico()
    {
        return $this->belongsTo(\App\Models\Levantamento::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function obra()
    {
        return $this->belongsTo(\App\Models\Obra::class);
    }

	/**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function templatePlanilha()
    {
        return $this->belongsTo(\App\Models\TemplatePlanilha::class);
    }


}
