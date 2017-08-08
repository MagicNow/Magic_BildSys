<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MedicaoBoletimStatus
 * @package App\Models
 * @version July 21, 2017, 2:42 pm BRT
 */
class MedicaoBoletimStatus extends Model
{
    public $table = 'medicao_boletim_status';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


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
        'nome' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function medicaoBoletimStatusLogs()
    {
        return $this->hasMany(MedicaoBoletimStatusLog::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function medicaoBoletins()
    {
        return $this->hasMany(MedicaoBoletim::class);
    }
}
