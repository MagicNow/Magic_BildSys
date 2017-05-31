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
        'porcentagem_locacao',
        'porcentagem_faturamento_direto',
        'desistencia_motivo_id',
        'desistencia_texto',
        'nf_material',
        'nf_servico',
        'nf_locacao',
        'tipo_frete',
        'valor_frete',
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
        'desistencia_texto' => 'string',
        'nf_servico'=> 'integer',
        'nf_material'=> 'integer',
        'nf_locacao'=> 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function desistenciaMotivo()
    {
        return $this->belongsTo(DesistenciaMotivo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function quadroDeConcorrencia()
    {
        return $this->belongsTo(QuadroDeConcorrencia::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function qcFornecedorEqualizacaoChecks()
    {
        return $this->hasMany(QcFornecedorEqualizacaoCheck::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function itens()
    {
        return $this->hasMany(QcItemQcFornecedor::class);
    }

    public function getValorFreteAttribute($value){
        return number_format($value,2,',','.');
    }
}
