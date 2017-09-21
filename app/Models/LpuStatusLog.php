<?php

namespace App\Models;

use Eloquent as Model;

class LpuStatusLog extends Model
{
    public $table = 'lpu_status_log';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    public $fillable = [
        'lpu_id',
		'valor_sugerido_anterior',
		'valor_sugerido_atual',
        'lpu_status_id',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                     => 'integer',
        'lpu_id' 				=> 'integer',
        'lpu_status_id'           => 'integer',
        'user_id'                => 'integer'
    ];
	
	public function getValorSugeridoAnteriorAttribute($value)
    {
        if (strlen($value) == 4) {
            $value = '0'.$value;
        }

        return number_format($value, 2, ',', '.');
    }
	
	public function setValorSugeridoAnteriorAttribute($value)
    {
        $pontos = [","];
        $value = str_replace('.', '', $value);
        $result = str_replace($pontos, ".", $value);
        if ($result == '') {
            $result = null;
        }
        $this->attributes['valor_sugerido_anterior'] = $result;
    }
	
	public function getValorSugeridoAtualAttribute($value)
    {
        if (strlen($value) == 4) {
            $value = '0'.$value;
        }

        return number_format($value, 2, ',', '.');
    }
	
	public function setValorSugeridoAtualAttribute($value)
    {
        $pontos = [","];
        $value = str_replace('.', '', $value);
        $result = str_replace($pontos, ".", $value);
        if ($result == '') {
            $result = null;
        }
        $this->attributes['valor_sugerido_atual'] = $result;
    }
}
