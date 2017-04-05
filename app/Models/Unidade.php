<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Unidade
 * @package App\Models
 * @version April 5, 2017, 6:52 pm BRT
 */
class Unidade extends Model
{

    public $table = 'unidades';

    protected $primary = 'sigla';

    public $fillable = [
        'descricao'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'sigla' => 'string',
        'descricao' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'sigla' => 'required',
        'descricao' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function insumos()
    {
        return $this->hasMany(Insumo::class);
    }

//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function orcamentos()
//    {
//        return $this->hasMany(Orcamento::class);
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function ordemDeCompraItens()
//    {
//        return $this->hasMany(OrdemDeCompraIten::class);
//    }
}
