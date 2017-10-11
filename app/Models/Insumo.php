<?php

namespace App\Models;

use Eloquent as Model;

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
        'insumo_grupo_id',
        'active',
        'ncm_codigo',
        'ncm_texto',
        'ncm_codigo_texto',
        'aliq_irrf',
        'aliq_csll',
        'aliq_pis',
        'aliq_inss',
        'aliq_cofins',
        'servico_cnae_id',
        'qtd_minima',
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
        'insumo_grupo_id' => 'integer',
        'aliq_irrf' => 'float',
        'aliq_csll' => 'float',
        'aliq_pis' => 'float',
        'aliq_inss' => 'float',
        'aliq_cofins' => 'float'
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
        return $this->hasMany(CatalogoContratoInsumo::class);
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cnae()
    {
        return $this->belongsTo(Cnae::class, 'servico_cnae_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ordemDeCompraItens()
    {
        return $this->hasMany(OrdemDeCompraItens::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function planejamentoCompras()
    {
        return $this->hasMany(PlanejamentoCompra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function grupo()
    {
        return $this->belongsTo(InsumoGrupo::class, 'insumo_grupo_id', 'id');
    }

    public function contratoItem(){
        return $this->hasMany(ContratoItem::class,'insumo_id');
    }

    public function getIsFaturamentoDiretoAttribute()
    {
        return in_array($this->codigo, [34007, 30019]);
    }

    public function carteiras()
    {
        return $this->belongsToMany(Carteira::class, 'carteira_insumos', 'insumo_id', 'carteira_id')->withPivot('deleted_at')->withTimestamps();
    }

}
