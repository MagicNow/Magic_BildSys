<?php

namespace App\Repositories;

use App\Models\MascaraPadrao;
use App\Models\ConfiguracaoEstatica;
use App\Models\ContratoTemplate;
use App\Models\Fornecedor;
use App\Models\Obra;
use InfyOm\Generator\Common\BaseRepository;
use PDF;

class MascaraPadraoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [        
		'nome',
		'obra_id',
		'orcamento_tipo_id',
		'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MascaraPadrao::class;
    }

}
