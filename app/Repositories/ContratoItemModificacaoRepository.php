<?php

namespace App\Repositories;

use App\Models\ContratoItemModificacaoRepository;
use InfyOm\Generator\Common\BaseRepository;

class ContratoItemModificacaoRepositoryRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return ContratoItemModificacaoRepository::class;
    }

    public function reajustar($id, $data)
    {
       $contratoItemRepository =  app(ContratoItemRepository::class);
    }
}
