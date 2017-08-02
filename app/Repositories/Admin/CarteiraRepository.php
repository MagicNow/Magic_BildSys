<?php

namespace App\Repositories\Admin;

use App\Models\Carteira;
use InfyOm\Generator\Common\BaseRepository;

class CarteiraRepository extends BaseRepository
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
        return Carteira::class;
    }

    public function findByUser($user_id)
    {
        return $this->model->whereHas('users', function($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })
        ->orderBy('nome','ASC')
        ->get();
    }
	
}
