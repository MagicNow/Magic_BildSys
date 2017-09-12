<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MascaraPadraoInsumo
 * @package App\Models
 * @version May 2, 2017, 6:02 pm BRT
 */
class MascaraPadraoInsumo extends Model
{

    public $table = 'mascara_padrao_insumos';
    public $timestamps = true;

    public $fillable = [
        'insumo_id',
		'tipos_levantamento_id',
		'coeficiente',
		'indireto',
		'terreo_externo_solo',
		'terreo_externo_estrutura',
		'terreo_interno',
		'primeiro_pavimento',
		'segundo_ao_penultimo',
		'cobertura_ultimo_piso',
		'atico',
		'reservatorio',
		'qtd_total',
		'preco_unitario',
		'preco_total',
		'referencia_preco',
		'obs',
		'porcentagem_orcamento'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'insumo_id' => 'integer',
		'tipos_levantamento_id' => 'integer',
		'coeficiente' => 'float',
		'indireto' => 'float',
		'terreo_externo_solo' => 'float',
		'terreo_externo_estrutura' => 'float',
		'terreo_interno' => 'float',
		'primeiro_pavimento' => 'float',
		'segundo_ao_penultimo' => 'float',
		'cobertura_ultimo_piso' => 'float',
        'atico' => 'float',
        'reservatorio' => 'float',
        'qtd_total' => 'integer',
        'preco_unitario' => 'float',
        'preco_total' => 'float',
		'referencia_preco' => 'float',
		'obs' => 'text',
		'porcentagem_orcamento' => 'float'
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
    public function mascaraPadrao()
    {
        return $this->belongsTo(MascaraPadrao::class, 'mascara_padrao_id');
    }
	
	/**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoLevantamentos()
    {
        return $this->belongsTo(TipoLevantamentos::class, 'tipos_levantamento_id');
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
