<?php

namespace App\Repositories;

use App\Models\MedicaoServico;
use InfyOm\Generator\Common\BaseRepository;

class MedicaoServicoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'qtd_funcionarios',
        'qtd_ajudantes',
        'qtd_outros',
        'descontos',
        'descricao_descontos',
        'user_id',
        'periodo_inicio',
        'periodo_termino',
        'contrato_item_apropriacao_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MedicaoServico::class;
    }
}
