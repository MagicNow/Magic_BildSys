<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class NomeclaturaMapa
 * @package App\Models
 * @version June 28, 2017, 7:16 pm BRT
 */
class NomeclaturaMapa extends Model
{

    public $table = 'nomeclatura_mapas';

    public $timestamps = false;


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
        'nome' => 'string',
        'apenas_cartela'=>'integer',
        'apenas_unidade'=>'integer'
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
