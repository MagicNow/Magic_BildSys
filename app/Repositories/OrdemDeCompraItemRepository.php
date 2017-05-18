<?php

namespace App\Repositories;

use App\Models\OrdemDeCompraItem;
use InfyOm\Generator\Common\BaseRepository;

class OrdemDeCompraItemRepository extends BaseRepository
{
    /**
     * Configure the Model
     *
     * @return string
     **/
    public function model()
    {
        return OrdemDeCompraItem::class;
    }
}
