<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MedicaoBoletimStatusLog
 * @package App\Models
 * @version July 21, 2017, 2:44 pm BRT
 */
class MedicaoBoletimStatusLog extends Model
{
    public $table = 'medicao_boletim_status_logs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'medicao_boletim_id',
        'medicao_boletim_status_id',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'medicao_boletim_id' => 'integer',
        'medicao_boletim_status_id' => 'integer',
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
    public function medicaoBoletim()
    {
        return $this->belongsTo(MedicaoBoletim::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function medicaoBoletimStatus()
    {
        return $this->belongsTo(MedicaoBoletimStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
