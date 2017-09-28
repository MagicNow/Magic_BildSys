<?php

namespace App\Repositories;

use App\Models\DocumentoTipo;
use InfyOm\Generator\Common\BaseRepository;

class DocumentoTipoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome',
        'codigo_mega'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DocumentoTipo::class;
    }
}
