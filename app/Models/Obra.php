<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Obra
 * @package App\Models
 * @version April 4, 2017, 6:25 pm BRT
 */
class Obra extends Model
{
    use SoftDeletes;

    public $table = 'obras';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'nome',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nome' => 'required'
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function contratos()
    {
        return $this->hasMany(Contrato::class);
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
        return $this->hasMany(OrdemDeCompraItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ordemDeCompras()
    {
        return $this->hasMany(OrdemDeCompra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function planejamentos()
    {
        return $this->hasMany(Planejamento::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function retroalimentacaoObras()
    {
        return $this->hasMany(RetroalimentacaoObra::class);
    }
}
