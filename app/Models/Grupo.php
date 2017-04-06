<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Grupo
 * @package App\Models
 * @version April 5, 2017, 6:38 pm BRT
 */
class Grupo extends Model
{
    use SoftDeletes;

    public $table = 'grupos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'codigo',
        'nome',
        'grupo_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'codigo' => 'string',
        'nome' => 'string',
        'grupo_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function grupo(){
        return $this->belongsTo(Grupo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function servicos()
    {
        return $this->hasMany(Servico::class);
    }

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
//    public function subgrupo1_orcamentos()
//    {
//        return $this->hasMany(\App\Models\Orcamento::class, 'subgrupo1_id');
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function subgrupo2_orcamentos()
//    {
//        return $this->hasMany(\App\Models\Orcamento::class, 'subgrupo2_id');
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function subgrupo3_orcamentos()
//    {
//        return $this->hasMany(\App\Models\Orcamento::class, 'subgrupo3_id');
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function ordemDeCompraItens()
//    {
//        return $this->hasMany(\App\Models\OrdemDeCompraItem::class);
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function subgrupo1_ordemDeCompraItens()
//    {
//        return $this->hasMany(\App\Models\OrdemDeCompraItem::class,'subgrupo1_id');
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function subgrupo2_ordemDeCompraItens()
//    {
//        return $this->hasMany(\App\Models\OrdemDeCompraItem::class,'subgrupo2_id');
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function subgrupo3_ordemDeCompraItens()
//    {
//        return $this->hasMany(\App\Models\OrdemDeCompraItem::class,'subgrupo3_id');
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function planejamentoCompras()
//    {
//        return $this->hasMany(\App\Models\PlanejamentoCompra::class);
//    }


}
