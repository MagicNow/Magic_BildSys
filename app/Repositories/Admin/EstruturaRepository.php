<?php

namespace App\Repositories\Admin;

use App\Models\Estrutura;
use InfyOm\Generator\Common\BaseRepository;

class EstruturaRepository extends BaseRepository
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
        return Estrutura::class;
    }
	
}
