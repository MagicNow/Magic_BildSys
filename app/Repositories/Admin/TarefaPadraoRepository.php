<?php

namespace App\Repositories\Admin;

use App\Models\TarefaPadrao;
use InfyOm\Generator\Common\BaseRepository;

class TarefaPadraoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TarefaPadrao::class;
    }
	
}
