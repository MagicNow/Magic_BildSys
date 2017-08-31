<?php

namespace App\Repositories;

use App\Models\RetroalimentacaoObra;
use InfyOm\Generator\Common\BaseRepository;
use Carbon\Carbon;

class RetroalimentacaoObraRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'obra_id',
        'user_id',
        'user_id_responsavel',
        'origem',
        'categoria',
        'situacao_atual',
        'situacao_proposta',
        'data_inclusao'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return RetroalimentacaoObra::class;
    }

    public function update(array $attributes,$id) {

        $r = parent::findWithoutFail($id);

        $attributes["data_prevista"] = (!empty($attributes["data_prevista"])) ? Carbon::createFromFormat('d/m/Y', $attributes["data_prevista"])->format('Y-m-d') : NULL;
        $attributes["data_conclusao"] = (!empty($attributes["data_conclusao"])) ? Carbon::createFromFormat('d/m/Y', $attributes["data_conclusao"])->format('Y-m-d') : NULL;

        if(isset($attributes['aceite'])){
            $attributes['aceite'] = 1;
        }

        parent::update($attributes, $id);
    }
}
