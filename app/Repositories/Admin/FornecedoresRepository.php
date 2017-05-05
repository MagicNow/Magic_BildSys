<?php

namespace App\Repositories\Admin;

use App\Models\Fornecedor;
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
        return Fornecedor::class;
    }

    /**
     * Retorna os fornecedores que podem preencher um certo quadro em sua rodada
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function podemPreencherQuadroNaRodada($quadro_id, $rodada_atual)
    {
        return $this->model->select('fornecedores.*')
            ->whereHas(
                'qcFornecedor',
                function($query) use ($quadro_id, $rodada_atual) {
                    $query->where('quadro_de_concorrencia_id', $quadro_id);
                    $query->where('rodada', $rodada_atual);
                    $query->doesntHave('itens');
                }
            )
            ->get();
    }
}
