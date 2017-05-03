<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class QcStatus
 * @package App\Models
 * @version May 3, 2017, 3:12 pm BRT
 */
class QcStatus extends Model
{
    use SoftDeletes;

    public $table = 'qc_status';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


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
