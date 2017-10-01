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
		'periodo_inicio',
		'periodo_termino',
		'valor_medido_anterior',
		'valor_medido_atual'       
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
		'periodo_inicio' 		=> 'date',
		'periodo_termino'		=> 'date',
		'valor_medido_anterior' => 'integer',
		'valor_medido_atual' 	=> 'integer'        
    ];
	
	public function getValorMedidoAnteriorAttribute($value)
    {
        if (strlen($value) == 4) {
            $value = '0'.$value;
        }

        return number_format($value, 2, ',', '.');
    }
	
	public function setValorMedidoAnteriorAttribute($value)
    {
        $pontos = [","];
        $value = str_replace('.', '', $value);
        $result = str_replace($pontos, ".", $value);
        if ($result == '') {
            $result = null;
        }
        $this->attributes['valor_medido_anterior'] = $result;
    }
	
	public function getValorMedidoAtualAttribute($value)
    {
        if (strlen($value) == 4) {
            $value = '0'.$value;
        }

        return number_format($value, 2, ',', '.');
    }
	
	public function setValorMedidoAtualAttribute($value)
    {
        $pontos = [","];
        $value = str_replace('.', '', $value);
        $result = str_replace($pontos, ".", $value);
        if ($result == '') {
            $result = null;
        }
        $this->attributes['valor_medido_atual'] = $result;
    }
}
