<?php

namespace App\Models;

use Eloquent as Model;


class CatalogoContratoRegional extends Model
{
    public $table = 'catalogo_contrato_regional';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'catalogo_contrato_id',
        'regional_id',
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
        'regional_id' => 'integer',
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
    public function status()
    {
        return $this->belongsTo(\App\Models\CatalogoContratoStatus::class, 'catalogo_contrato_status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function regional()
    {
        return $this->belongsTo(\App\Models\Regional::class);
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
