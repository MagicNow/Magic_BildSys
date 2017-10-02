<?php

namespace App\Models;

use Eloquent as Model;

class MedicaoFisicaLog extends Model
{
    public $table = 'medicao_fisica_logs';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    public $fillable = [
        'medicao_fisica_id',
		'user_id',
		'valor_medido',
		'periodo_inicio',
		'periodo_termino',		
		'observacao'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                    => 'integer',
        'medicao_fisica_id'		=> 'integer',
        'user_id'               => 'integer',
		'valor_medido' 			=> 'integer',
		'periodo_inicio' 		=> 'date',
		'periodo_termino'		=> 'date',
		'observacao'			=> 'text'		      
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
