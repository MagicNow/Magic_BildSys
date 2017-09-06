<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Lpu
 * @package App\Models
 * @version May 18, 2017, 6:08 pm BRT
 */
class Lpu extends Model
{
    public $table = 'lpu';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'insumo_id',
		'codigo_insumo',
		'regional_id',
		'grupo_id',
		'subgrupo1_id',
		'subgrupo2_id',
		'subgrupo3_id',
		'servico_id',
		'valor_sugerido',
		'valor_contrato',
		'valor_catalogo',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'             => 'integer',
        'insumo_id'      => 'integer',
        'codigo_insumo' => 'string',
        'regional_id' => 'integer',
        'grupo_id' => 'integer',
        'subgrupo1_id' => 'integer',
        'subgrupo2_id' => 'integer',
        'subgrupo3_id' => 'integer',
        'servico_id' => 'integer',
        'valor_sugerido' => 'float',
        'valor_contrato' => 'float',
        'valor_catalogo' => 'float'
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
    public function regional()
    {
        return $this->belongsTo(\App\Models\Regional::class);
    }
    
}
