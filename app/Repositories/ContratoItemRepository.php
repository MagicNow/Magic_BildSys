<?php

namespace App\Repositories;

use App\Models\ContratoItem;
use InfyOm\Generator\Common\BaseRepository;

class ContratoItemRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return ContratoItem::class;
    }
}
