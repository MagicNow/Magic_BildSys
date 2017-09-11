<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MascaraInsumo
 * @package App\Models
 * @version April 5, 2017, 12:30 pm BRT
 */
class MascaraInsumo extends Model
{

    public $table = 'mascara_insumos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'levantamento_tipos_id',
        'apropriacao',
        'descricao_apropriacao',
        'unidade_medidas'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'levantamento_tipos_id' => 'integer',        
        'apropriacao' => 'string',
        'descricao_apropriacao' => 'string',
		'unidade_medidas' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'levantamento_tipos_id' => 'required',
        'apropriacao' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function levantamentoTipo()
    {
        return $this->belongsTo(LevantamentoTipo::class);
    }

}
