<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CatalogoContrato
 * @package App\Models
 * @version May 2, 2017, 6:01 pm BRT
 */
class CatalogoContrato extends Model
{

    public $table = 'catalogo_contratos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'fornecedor_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'fornecedor_id' => 'integer'
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
    public function fornecedor()
    {
        return $this->belongsTo(\App\Models\Fornecedor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function contratoInsumos()
    {
        return $this->hasMany(\App\Models\CatalogoContratoInsumo::class, 'catalogo_contrato_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ordemDeCompraItens()
    {
        return $this->hasMany(\App\Models\OrdemDeCompraItens::class);
    }
}
