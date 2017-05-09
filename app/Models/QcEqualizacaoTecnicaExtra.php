<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class QcEqualizacaoTecnicaExtra
 * @package App\Models
 * @version May 5, 2017, 11:47 am BRT
 */
class QcEqualizacaoTecnicaExtra extends Model
{
    public $table = 'qc_equalizacao_tecnica_extras';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'quadro_de_concorrencia_id',
        'nome',
        'descricao',
        'obrigatorio'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'quadro_de_concorrencia_id' => 'integer',
        'nome' => 'string',
        'descricao' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nome' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function quadroDeConcorrencia()
    {
        return $this->belongsTo(\App\Models\QuadroDeConcorrencia::class);
    }

    public function checks()
    {
        return $this->morphMany(QcFornecedorEqualizacaoCheck::class, 'checkable');
    }
}
