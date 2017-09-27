<?php

namespace App\Repositories\Admin;

use App\Models\Tipologia;
use InfyOm\Generator\Common\BaseRepository;

class TipologiaRepository extends BaseRepository
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
        return Tipologia::class;
    }
}
