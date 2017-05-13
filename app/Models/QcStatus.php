<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class QcStatus
 * @package App\Models
 * @version May 3, 2017, 3:12 pm BRT
 */
class QcStatus extends Model
{

    public $table = 'qc_status';

    public $timestamps = false;

    const ABERTO                  = 1;
    const FECHADO                 = 2;
    const EM_APROVACAO            = 3;
    const REPROVADO               = 4;
    const APROVADO                = 5;
    const CANCELADO               = 6;
    const EM_CONCORRENCIA         = 7;
    const CONCORRENCIA_FINALIZADA = 8;
    const FINALIZADO              = 9;

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
    public function qcStatusLogs()
    {
        return $this->hasMany(\App\Models\QcStatusLog::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function quadroDeConcorrencias()
    {
        return $this->hasMany(\App\Models\QuadroDeConcorrencia::class);
    }
}
