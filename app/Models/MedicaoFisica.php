<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MedicaoFisica
 * @package App\Models
 * @version May 18, 2017, 6:08 pm BRT
 */
class MedicaoFisica extends Model
{
    public $table = 'medicao_fisicas';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'obra_id',
		'tarefa',
		'periodo_inicio',
		'periodo_termino',
		'valor_medido',
		'created_at',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'             => 'integer',
        'obra_id'      => 'integer',
        'tarefa' => 'string',
        'periodo_inicio' => 'date',
        'periodo_termino' => 'date',
		'valor_medido' => 'decimal',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
	
	public function getValorMedidoAttribute($value)
    {
        if (strlen($value) == 4) {
            $value = '0'.$value;
        }

        return number_format($value, 2, ',', '.');
    }
	
	public function setValorMedidoAttribute($value)
    {
        $pontos = [","];
        $value = str_replace('.', '', $value);
        $result = str_replace($pontos, ".", $value);
        if ($result == '') {
            $result = null;
        }
        $this->attributes['valor_medido'] = $result;
    }
	
}
