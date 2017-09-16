<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MascaraPadrao
 * @package App\Models
 * @version April 25, 2017, 2:16 pm BRT
 */
class MascaraPadrao extends Model
{
    public $table = 'mascara_padrao';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'nome',		 
		'orcamento_tipo_id',
    ];

    public static $campos = [
        'nome',
		'orcamento_tipo_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
		'orcamento_tipo_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nome' => 'required',
		'orcamento_tipo_id' => 'integer'
    ];
	
	/**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoOrcamentos()
    {
        return $this->belongsTo(\App\Models\TipoOrcamento::class);
    }
	
}
