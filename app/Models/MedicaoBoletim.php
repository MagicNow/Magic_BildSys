<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MedicaoBoletim
 * @package App\Models
 * @version July 21, 2017, 2:46 pm BRT
 */
class MedicaoBoletim extends Model
{
    public $table = 'medicao_boletins';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'obra_id',
        'contrato_id',
        'medicao_boletim_status_id',
        'obs',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'obra_id' => 'integer',
        'contrato_id' => 'integer',
        'medicao_boletim_status_id' => 'integer',
        'obs' => 'string',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'obra_id' => 'required',
        'contrato_id' => 'required',
        'medicaoServicos' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
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
    public function obra()
    {
        return $this->belongsTo(Obra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function medicaoServicos()
    {
        return $this->belongsToMany(MedicaoServico::class,'medicao_boletim_medicao_servico','medicao_boletim_id','medicao_servico_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function medicaoBoletimStatusLogs()
    {
        return $this->hasMany(MedicaoBoletimStatusLog::class);
    }
}
