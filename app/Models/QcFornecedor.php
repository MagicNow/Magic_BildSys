<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class QcFornecedor
 * @package App\Models
 * @version May 4, 2017, 10:16 am BRT
 */
class QcFornecedor extends Model
{
    public $table = 'qc_fornecedor';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'quadro_de_concorrencia_id',
        'fornecedor_id',
        'user_id',
        'rodada',
        'porcentagem_material',
        'porcentagem_servico',
        'porcentagem_faturamento_direto',
        'desistencia_motivo_id',
        'desistencia_texto'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'quadro_de_concorrencia_id' => 'integer',
        'fornecedor_id' => 'integer',
        'user_id' => 'integer',
        'rodada' => 'integer',
        'desistencia_motivo_id' => 'integer',
        'desistencia_texto' => 'string'
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
    public function desistenciaMotivo()
    {
        return $this->belongsTo(\App\Models\DesistenciaMotivo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function fornecedore()
    {
        return $this->belongsTo(\App\Models\Fornecedore::class);
    }

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
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function qcFornecedorEqualizacaoChecks()
    {
        return $this->hasMany(\App\Models\QcFornecedorEqualizacaoCheck::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function qcItemQcFornecedors()
    {
        return $this->hasMany(\App\Models\QcItemQcFornecedor::class);
    }
}
