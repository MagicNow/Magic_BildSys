<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class ObraUser
 * @package App\Models
 * @version April 25, 2017, 6:11 pm BRT
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function obra()
    {
        return $this->belongsTo(Obra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function fornecedores()
    {
        return $this->hasMany(Fornecedore::class);
    }
}
