<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MedicaoBoletimStatus
 * @package App\Models
 * @version July 21, 2017, 11:37 am BRT
 */
class MedicaoBoletimStatus extends Model
{
    use SoftDeletes;

    public $table = 'medicao_boletim_status';
    
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
    public function medicaoBoletimStatusLogs()
    {
        return $this->hasMany(\App\Models\MedicaoBoletimStatusLog::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function medicaoBoletins()
    {
        return $this->hasMany(\App\Models\MedicaoBoletin::class);
    }
}
