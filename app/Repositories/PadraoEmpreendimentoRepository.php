<?php

namespace App\Repositories;

use App\Models\PadraoEmpreendimento;
use InfyOm\Generator\Common\BaseRepository;

class PadraoEmpreendimentoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome',
        'cor'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PadraoEmpreendimento::class;
    }
}
