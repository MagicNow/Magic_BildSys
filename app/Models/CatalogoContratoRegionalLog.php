<?php

namespace App\Models;

use Eloquent as Model;


class CatalogoContratoRegionalLog extends Model
{

    public $table = 'catalogo_contrato_regional_logs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'catalogo_contrato_regional_id',
        'catalogo_contrato_status_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'catalogo_contrato_regional_id' => 'integer',
        'catalogo_contrato_status_id' => 'integer'
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
    public function catalogoContratoObra()
    {
        return $this->belongsTo(\App\Models\CatalogoContratoRegional::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function catalogoContratoStatus()
    {
        return $this->belongsTo(\App\Models\CatalogoContratoStatus::class);
    }
}
