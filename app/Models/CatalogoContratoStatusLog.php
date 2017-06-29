<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CatalogoContratoStatusLog
 * @package App\Models
 * @version June 23, 2017, 7:40 pm BRT
 */
class CatalogoContratoStatusLog extends Model
{

    public $table = 'catalogo_contrato_status_logs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'catalogo_contrato_id',
        'catalogo_contrato_status_id',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'catalogo_contrato_id' => 'integer',
        'catalogo_contrato_status_id' => 'integer',
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
    public function catalogoContratoStatus()
    {
        return $this->belongsTo(\App\Models\CatalogoContratoStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function catalogoContrato()
    {
        return $this->belongsTo(\App\Models\CatalogoContrato::class);
    }
}
