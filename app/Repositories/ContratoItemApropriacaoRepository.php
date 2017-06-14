<?php

namespace App\Repositories;

use App\Models\ContratoItemApropriacao;
use InfyOm\Generator\Common\BaseRepository;
use App\Models\ContratoItem;

class ContratoItemApropriacaoRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return ContratoItemApropriacao::class;
    }

    public function reapropriar(ContratoItem $contratoItem, $data)
    {
        $data['insumo_id']                      = $contratoItem->qcItem->insumo_id;
        $data['contrato_item_id']               = $contratoItem->id;
        $data['qtd']                            = money_to_float($data['qtd']);
        $data['user_id']                        = auth()->id();
        $data['codigo_insumo']                  = $contratoItem->insumo->codigo;
        $data['contrato_item_reapropriacao_id'] = $data['item_id'];

        return $this->create($data);
    }
}
