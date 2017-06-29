<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NomeclaturaMapa
 * @package App\Models
 * @version June 28, 2017, 7:16 pm BRT
 */
class NomeclaturaMapa extends Model
{
    use SoftDeletes;

    public $table = 'nomeclatura_mapas';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'nome',
        'tipo',
        'apenas_cartela',
        'apenas_unidade'
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
        'nome' => 'required',
        'tipo' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function blocosComoEstrutura()
    {
        return $this->hasMany(MemoriaCalculoBloco::class,'estrutura');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function blocosComoPavimento()
    {
        return $this->hasMany(MemoriaCalculoBloco::class,'pavimento');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function blocosComoTrecho()
    {
        return $this->hasMany(MemoriaCalculoBloco::class,'trecho');
    }
}
