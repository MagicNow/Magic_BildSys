<?php

namespace App\Repositories\Admin;

use App\Models\TemplatePlanilha;
use InfyOm\Generator\Common\BaseRepository;

class TemplatePlanilhaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome',
        'modulo',
        'colunas'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TemplatePlanilha::class;
    }
}
