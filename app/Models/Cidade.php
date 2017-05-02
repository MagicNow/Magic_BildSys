<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Cidade
 * @package App\Models
 * @version May 1, 2017, 9:38 pm BRT
 */
class Cidade extends Model
{

    public $table = 'cidades';
    
    public $timestamps = false;

    public $fillable = [
        'nome',
        'nome_completo',
        'cep',
        'uf',
        'tipo_localidade',
        'cidade_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
        'nome_completo' => 'string',
        'cep' => 'string',
        'uf' => 'string',
        'tipo_localidade' => 'string',
        'cidade_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function fornecedores()
    {
        return $this->hasMany(\App\Models\Fornecedore::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function obras()
    {
        return $this->hasMany(\App\Models\Obra::class);
    }
}
