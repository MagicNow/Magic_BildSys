<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Fornecedor
 * @package App\Models
 * @version May 4, 2017, 12:21 pm BRT
 */
class Fornecedor extends Model
{
    use SoftDeletes;

    public $table = 'fornecedores';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


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
        'nome' => 'required',
        'cnpj' => 'required',
        'email' => 'required|email',
        'telefone' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cidade()
    {
        return $this->belongsTo(Cidade::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function catalogoContratos()
    {
        return $this->hasMany(CatalogoContrato::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function qcFornecedor()
    {
        return $this->hasMany(QcFornecedor::class);
    }
}
