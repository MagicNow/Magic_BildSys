<?php

namespace App\Repositories;

use App\Models\ConfiguracaoEstatica;
use InfyOm\Generator\Common\BaseRepository;

class ConfiguracaoEstaticaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'chave',
        'valor',
        'teste'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ConfiguracaoEstatica::class;
    }
}
