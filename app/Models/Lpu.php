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
		'valor_sugerido',
		'valor_contrato',
		'valor_catalogo',
		'observacao',
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
		'valor_sugerido' => 'decimal',
        'valor_contrato' => 'decimal',
        'valor_catalogo' => 'decimal'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
	
	public function getValorSugeridoAttribute($value)
    {
        if (strlen($value) == 4) {
            $value = '0'.$value;
        }

        return number_format($value, 2, ',', '.');
    }
	
	public function setValorSugeridoAttribute($value)
    {
        $pontos = [","];
        $value = str_replace('.', '', $value);
        $result = str_replace($pontos, ".", $value);
        if ($result == '') {
            $result = null;
        }
        $this->attributes['valor_sugerido'] = $result;
    }
	
}
