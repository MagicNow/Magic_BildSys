<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CatalogoContratoObraLog
 * @package App\Models
 * @version June 20, 2017, 5:44 pm BRT
 */
class CatalogoContratoObraLog extends Model
{

    public $table = 'catalogo_contrato_obra_logs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'catalogo_contrato_obra_id',
        'catalogo_contrato_status_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'catalogo_contrato_obra_id' => 'integer',
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
        return $this->belongsTo(\App\Models\CatalogoContratoObra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function catalogoContratoStatus()
    {
        return $this->belongsTo(\App\Models\CatalogoContratoStatus::class);
    }
}
