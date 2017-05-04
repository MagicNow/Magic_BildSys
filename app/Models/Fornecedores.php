<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Fornecedores
 * @package App\Models\Admin
 * @version April 28, 2017, 1:41 pm BRT
 */
class Fornecedores extends Model
{
    public $table = 'fornecedores';

    public $timestamps = false;

    public $fillable = [
        'codigo_mega',
        'nome',
        'cnpj',
        'tipo_logradouro',
        'logradouro',
        'numero',
        'complemento',
        'cidade_id',
        'municipio',
        'estado',
        'situacao_cnpj',
        'inscricao_estadual',
        'email',
        'site',
        'telefone',
        'cep'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'codigo_mega' => 'integer',
        'nome' => 'string',
        'cnpj' => 'string',
        'tipo_logradouro' => 'string',
        'logradouro' => 'string',
        'numero' => 'string',
        'complemento' => 'string',
        'cidade_id' => 'integer',
        'municipio' => 'string',
        'estado' => 'string',
        'situacao_cnpj' => 'string',
        'inscricao_estadual' => 'string',
        'email' => 'string',
        'site' => 'string',
        'telefone' => 'string',
        'cep' => 'string'
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
    public function cidade()
    {
        return $this->belongsTo(\App\Models\Cidade::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function catalogoContratos()
    {
        return $this->hasMany(\App\Models\CatalogoContrato::class);
    }

    public function valoresEmQuadros()
    {
        return $this->hasMany(\App\Models\QcFornecedor::class, 'fornecedor_id');
    }
}
