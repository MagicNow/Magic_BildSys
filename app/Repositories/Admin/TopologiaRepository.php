<?php

namespace App\Repositories\Admin;

use App\Models\Topologia;
use InfyOm\Generator\Common\BaseRepository;

class TopologiaRepository extends BaseRepository
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
        return Topologia::class;
    }
}
