<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Obra
 * @package App\Models
 * @version April 4, 2017, 6:25 pm BRT
 */
class Obra extends Model
{

    public $table = 'obras';



    protected $dates = [];


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

    ];

//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function contratos()
//    {
//        return $this->hasMany(\App\Models\Contrato::class);
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function orcamentos()
//    {
//        return $this->hasMany(\App\Models\Orcamento::class);
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function ordemDeCompraItens()
//    {
//        return $this->hasMany(\App\Models\OrdemDeCompraIten::class);
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function ordemDeCompras()
//    {
//        return $this->hasMany(\App\Models\OrdemDeCompra::class);
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function planejamentos()
//    {
//        return $this->hasMany(\App\Models\Planejamento::class);
//    }
}
