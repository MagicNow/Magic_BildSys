<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MascaraPadrao
 * @package App\Models
 * @version May 2, 2017, 6:01 pm BRT
 */
class MascaraPadrao extends Model
{

    public $table = 'mascara_padrao';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [        
		'nome',
		'obra_id',
		'orcamento_tipo_id',
		'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'obra_id' => 'integer',
        'orcamento_tipo_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        /*'fornecedor_cod' => 'required',
        'contratoInsumos.*.insumo_id'=>'required',
        'contratoInsumos.*.valor_unitario'=>'required|min:0.01',
        'contratoInsumos.*.pedido_minimo'=>'required|min:0.01',
        'contratoInsumos.*.pedido_multiplo_de'=>'required|min:0.01',
        'contratoInsumos.*.periodo_inicio'=>'required',
        'contratoInsumos.*.periodo_termino'=>'required',
        'regional' => 'required'*/
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function obra()
    {
        return $this->belongsTo(\App\Models\Obra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mascaraPadraoInsumo()
    {
        return $this->hasMany(\App\Models\MascaraPadraoInsumo::class, 'mascara_padrao_id');
    }
    
}
