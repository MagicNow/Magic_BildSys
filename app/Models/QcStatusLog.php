<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class QcStatusLog
 * @package App\Models
 * @version May 3, 2017, 3:18 pm BRT
 */
class QcStatusLog extends Model
{
    public $table = 'qc_status_log';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    public $fillable = [
        'quadro_de_concorrencia_id',
        'qc_status_id',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'quadro_de_concorrencia_id' => 'integer',
        'qc_status_id' => 'integer',
        'user_id' => 'integer'
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
}
