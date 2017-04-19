<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Insumo
 * @package App\Models
 * @version April 12, 2017, 1:49 pm BRT
 */
class Insumo extends Model
{

    public $table = 'insumos';
    
    public $timestamps = false;

    public $fillable = [
        'nome',
        'unidade_sigla',
        'codigo',
        'insumo_grupo_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
        'unidade_sigla' => 'string',
        'codigo' => 'string',
        'insumo_grupo_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nome' => 'required',
        'unidade_sigla' => 'required',
        'codigo' => 'required',
        'insumo_grupo_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function insumoGrupo()
    {
        return $this->belongsTo(InsumoGrupo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_sigla');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function contratoInsumos()
    {
        return $this->hasMany(ContratoInsumo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function insumoServicos()
    {
        return $this->hasMany(InsumoServico::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function orcamentos()
    {
        return $this->hasMany(Orcamento::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ordemDeCompraItens()
    {
        return $this->hasMany(OrdemDeCompraIten::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function planejamentoCompras()
    {
        return $this->hasMany(PlanejamentoCompra::class);
    }
}
