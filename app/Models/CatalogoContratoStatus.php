<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CatalogoContratoStatus
 * @package App\Models
 * @version June 20, 2017, 5:18 pm BRT
 */
class CatalogoContratoStatus extends Model
{

    public $table = 'catalogo_contrato_status';
    
    public $timestamps = false;


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
    public function catalogoContratoStatusLogs()
    {
        return $this->hasMany(\App\Models\CatalogoContratoStatusLog::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function catalogoContratos()
    {
        return $this->hasMany(\App\Models\CatalogoContrato::class);
    }
}
