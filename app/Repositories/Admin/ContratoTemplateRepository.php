<?php

namespace App\Repositories\Admin;

use App\Models\ContratoTemplate;
use InfyOm\Generator\Common\BaseRepository;

class ContratoTemplateRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome',
        'template',
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ContratoTemplate::class;
    }
}
