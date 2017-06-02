<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class ContratoStatus
 * @package App\Models
 * @version May 18, 2017, 6:07 pm BRT
 */
class ContratoStatus extends Model
{
    public $table = 'contrato_status';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Id do status de Em Aprovação
     *
     * @const int
     */
    const EM_APROVACAO = 1;

    /**
     * Id do status de Aprovado
     *
     * @const int
     */
    const APROVADO = 2;

    /**
     * Id do status de Reprovado
     *
     * @const int
     */
    const REPROVADO = 3;


    /**
     * Id do status de Reprovado
     *
     * @const int
     */
    const AGUARDANDO = 4;

    /**
     * Id do status de Ativo
     *
     * @const int
     */
    const ATIVO = 5;

    protected $dates = ['deleted_at'];

    public $fillable = [
        'nome',
        'cor'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
        'cor' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function contratoItemModificacaoLogs()
    {
        return $this->hasMany(\App\Models\ContratoItemModificacaoLog::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function contratoItemModificacos()
    {
        return $this->hasMany(\App\Models\ContratoItemModificaco::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function contratoStatusLogs()
    {
        return $this->hasMany(\App\Models\ContratoStatusLog::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function contratos()
    {
        return $this->hasMany(\App\Models\Contrato::class);
    }
}
