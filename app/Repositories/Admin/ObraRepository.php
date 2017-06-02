<?php

namespace App\Repositories\Admin;

use App\Models\Obra;
use InfyOm\Generator\Common\BaseRepository;

class ObraRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Obra::class;
    }

    public function findByUser($user_id)
    {
        return $this->model->whereHas('users', function($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })
        ->orderBy('nome','ASC')
        ->get();
    }

    public function comContrato()
    {
        return $this->model->has('contratos')->get();
    }
}
