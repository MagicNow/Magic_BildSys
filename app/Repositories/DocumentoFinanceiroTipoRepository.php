<?php

namespace App\Repositories;

use App\Models\DocumentoFinanceiroTipo;
use InfyOm\Generator\Common\BaseRepository;

class DocumentoFinanceiroTipoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome',
        'codigo',
        'retem_irrf',
        'retem_impostos'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DocumentoFinanceiroTipo::class;
    }
}
