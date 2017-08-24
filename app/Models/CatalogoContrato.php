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
        'fornecedor_id',
        'minuta_assinada',
        'catalogo_contrato_status_id',
        'campos_extras_contrato',
        'campos_extras_minuta',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'fornecedor_id' => 'integer',
        'catalogo_contrato_status_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'fornecedor_cod' => 'required',
        'contratoInsumos.*.insumo_id'=>'required',
        'contratoInsumos.*.valor_unitario'=>'required|min:0.01',
        'contratoInsumos.*.pedido_minimo'=>'required|min:0.01',
        'contratoInsumos.*.pedido_multiplo_de'=>'required|min:0.01',
        'contratoInsumos.*.periodo_inicio'=>'required',
        'contratoInsumos.*.periodo_termino'=>'required',
        'regional' => 'required'
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

    public function status(){
        return $this->belongsTo(CatalogoContratoStatus::class,'catalogo_contrato_status_id');
    }

    public function obras(){
        return $this->hasMany(CatalogoContratoObra::class, 'catalogo_contrato_id');
    }

    public function regionais(){
        return $this->hasMany(CatalogoContratoRegional::class, 'catalogo_contrato_id');
    }
}
