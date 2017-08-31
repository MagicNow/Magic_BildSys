<?php

namespace App\Repositories;

use App\Models\RetroalimentacaoObra;
use App\Models\User;
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

    public function usuariosSistema() {

        $r = User::where('active','1')->select('users.id','users.name')
            ->join('role_user','role_user.user_id','users.id')
            ->join('roles','roles.id','role_user.role_id')
            ->where('roles.name', '!=' ,'Fornecedor')
            ->get();

        return $r;
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
