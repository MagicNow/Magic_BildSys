<?php namespace App\Repositories\API;

use App\Models\OrdemDeCompra;
use InfyOm\Generator\Common\BaseRepository;

class ListagemOCRepository extends BaseRepository {

    public function model()  {
        return OrdemDeCompra::class;
    }

}