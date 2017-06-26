<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Servico
 * @package App\Models
 * @version April 5, 2017, 6:37 pm BRT
 */
class Servico extends Model
{
    use SoftDeletes;

    public $table = 'servicos';

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function grupo()
    {
        return $this->belongsTo(\App\Models\Grupo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function insumos()
    {
        return $this->belongsToMany(
            Insumo::class,
            'insumo_servico',
            'servico_id',
            'insumo_id'
        )
        ->withPivot('id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function orcamentos()
    {
        return $this->hasMany(\App\Models\Orcamento::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ordemDeCompraItens()
    {
        return $this->hasMany(\App\Models\OrdemDeCompraItens::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function planejamentoCompras()
    {
        return $this->hasMany(\App\Models\PlanejamentoCompra::class);
    }
}
