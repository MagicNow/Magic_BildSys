<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class QuadroDeConcorrencia
 * @package App\Models
 * @version May 2, 2017, 7:53 pm BRT
 */
class QuadroDeConcorrencia extends Model
{
    use SoftDeletes;

    public $table = 'quadro_de_concorrencias';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_id',
        'qc_status_id',
        'obrigacoes_fornecedor',
        'obrigacoes_bild',
        'rodada_atual'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'qc_status_id' => 'integer',
        'obrigacoes_fornecedor' => 'string',
        'obrigacoes_bild' => 'string',
        'rodada_atual' => 'integer'
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
    public function qcStatus()
    {
        return $this->belongsTo(\App\Models\QcStatus::class);
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
    public function qcEqualizacaoTecnicaAnexoExtras()
    {
        return $this->hasMany(\App\Models\QcEqualizacaoTecnicaAnexoExtra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function qcEqualizacaoTecnicaExtras()
    {
        return $this->hasMany(\App\Models\QcEqualizacaoTecnicaExtra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function qcFornecedors()
    {
        return $this->hasMany(\App\Models\QcFornecedor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function qcItens()
    {
        return $this->hasMany(\App\Models\QcIten::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function qcStatusLogs()
    {
        return $this->hasMany(\App\Models\QcStatusLog::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function qcTipoEqualizacaoTecnicas()
    {
        return $this->hasMany(\App\Models\QcTipoEqualizacaoTecnica::class);
    }
}
