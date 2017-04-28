<?php

namespace App\Repositories\Admin;

use App\Models\Fornecedores;
use InfyOm\Generator\Common\BaseRepository;

class FornecedoresRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
        'telefone'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Fornecedores::class;
    }
}
