<?php

namespace App\Repositories;

use App\Models\TemplateEmail;
use InfyOm\Generator\Common\BaseRepository;

class TemplateEmailRepository extends BaseRepository
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
        return TemplateEmail::class;
    }
}
