<?php

namespace App\Repositories;

use App\Models\QcFornecedor;
use InfyOm\Generator\Common\BaseRepository;

class QcFornecedorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'quadro_de_concorrencia_id',
        'fornecedor_id',
        'user_id',
        'rodada',
        'porcentagem_material',
        'porcentagem_servico',
        'porcentagem_faturamento_direto',
        'desistencia_motivo_id',
        'desistencia_texto'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return QcFornecedor::class;
    }
}
