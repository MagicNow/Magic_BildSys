<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MascaraPadraoInsumo
 * @package App\Models
 * @version May 11, 2017, 8:46 pm BRT
 */
class MascaraPadraoInsumo extends Model
{
    public $table = 'mascara_padrao_insumos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [        
		'mascara_padrao_id',
		'codigo_estruturado',
		'insumo_id',
		'tipos_levantamento_id',
		'coeficiente',
		'indireto',		
		'grupo_id',
		'subgrupo1_id',
        'subgrupo2_id',
        'subgrupo3_id',
		'servico_id'
    ];
	
	public $campos = [        
		'mascara_padrao_id',
		'codigo_estruturado',
		'insumo_id',
		'tipos_levantamento_id',
		'coeficiente',
		'indireto',
		'grupo_id',
		'subgrupo1_id',
        'subgrupo2_id',
        'subgrupo3_id',
		'servico_id'        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mascara_padrao_id' => 'integer',
        'insumo_id' => 'integer',
		'coeficiente' => 'decimal'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
	
	public function getCoeficienteAttribute($value)
    {
        if (strlen($value) == 4) {
            $value = '0'.$value;
        }

        return number_format($value, 2, ',', '.');
    }

    public function setCoeficienteAttribute($value)
    {
        $pontos = [","];
        $value = str_replace('.', '', $value);
        $result = str_replace($pontos, ".", $value);
        if ($result == '') {
            $result = null;
        }
        $this->attributes['coeficiente'] = $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mascaraPadrao()
    {
        return $this->belongsTo(MascaraPadrao::class);
    }

}
