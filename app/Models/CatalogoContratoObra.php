<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CatalogoContratoObra
 * @package App\Models
 * @version June 20, 2017, 5:44 pm BRT
 */
class CatalogoContratoObra extends Model
{
    public $table = 'catalogo_contrato_obra';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'catalogo_contrato_id',
        'obra_id',
        'user_id',
        'catalogo_contrato_status_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'catalogo_contrato_id' => 'integer',
        'obra_id' => 'integer',
        'user_id' => 'integer',
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
    public function catalogoContrato()
    {
        return $this->belongsTo(\App\Models\CatalogoContrato::class);
    }

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
    public function obra()
    {
        return $this->belongsTo(\App\Models\Obra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function catalogoContratoObraLogs()
    {
        return $this->hasMany(\App\Models\CatalogoContratoObraLog::class);
    }
}
