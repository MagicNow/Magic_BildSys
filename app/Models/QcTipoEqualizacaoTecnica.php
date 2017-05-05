<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class QcTipoEqualizacaoTecnica
 * @package App\Models
 * @version May 3, 2017, 3:19 pm BRT
 */
class QcTipoEqualizacaoTecnica extends Model
{

    public $table = 'qc_tipo_equalizacao_tecnica';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'quadro_de_concorrencia_id',
        'tipo_equalizacao_tecnica_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'quadro_de_concorrencia_id' => 'integer',
        'tipo_equalizacao_tecnica_id' => 'integer'
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
    public function quadroDeConcorrencia()
    {
        return $this->belongsTo(\App\Models\QuadroDeConcorrencia::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoEqualizacaoTecnica()
    {
        return $this->belongsTo(\App\Models\TipoEqualizacaoTecnica::class);
    }
}
