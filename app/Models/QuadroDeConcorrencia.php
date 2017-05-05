<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class QuadroDeConcorrencia
 * @package App\Models
 * @version May 2, 2017, 7:53 pm BRT
 */
class QuadroDeConcorrencia extends Model
{

    public $table = 'quadro_de_concorrencias';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    

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
    public function status()
    {
        return $this->belongsTo(QcStatus::class,'qc_status_id');
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
    public function equalizacaoTecnicaAnexoExtras()
    {
        return $this->hasMany(QcEqualizacaoTecnicaAnexoExtra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function equalizacaoTecnicaExtras()
    {
        return $this->hasMany(QcEqualizacaoTecnicaExtra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function qcFornecedores()
    {
        return $this->hasMany(QcFornecedor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function itens()
    {
        return $this->hasMany(QcItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function logs()
    {
        return $this->hasMany(QcStatusLog::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     **/
    public function tipoEqualizacaoTecnicas()
    {
        return $this->belongsToMany(TipoEqualizacaoTecnica::class, 'qc_tipo_equalizacao_tecnica','quadro_de_concorrencia_id','tipo_equalizacao_tecnica_id')->withTimestamps();
    }
}
