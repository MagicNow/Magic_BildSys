<?php

namespace App\Repositories;

use App\Models\RetroalimentacaoObra;
use App\Models\RetroalimentacaoObraHistorico;
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


        if ( ($r->user_id_responsavel != $attributes['user_id_responsavel']) or ($r->status_id != $attributes['status_id']) ) {

            $insert['retroalimentacao_obras_id'] = $r->id;
            $insert['user_id_origem'] = !empty($r->user_id_responsavel) ? $r->user_id_responsavel : null;
            $insert['user_id_destino'] = $attributes['user_id_responsavel'];
            $insert['status_origem'] = !empty($r->status_id) ? $r->status_id : null;
            $insert['status_destino'] = $attributes['status_id'];
            $insert['andamento'] = $attributes['andamento'];
            $insert['created_at'] = Carbon::now();

            RetroalimentacaoObraHistorico::insert($insert);

        }


        $attributes["data_prevista"] = (!empty($attributes["data_prevista"])) ? Carbon::createFromFormat('d/m/Y', $attributes["data_prevista"])->format('Y-m-d') : NULL;
        $attributes["data_conclusao"] = (!empty($attributes["data_conclusao"])) ? Carbon::createFromFormat('d/m/Y', $attributes["data_conclusao"])->format('Y-m-d') : NULL;

        if(isset($attributes['aceite'])){
            $attributes['aceite'] = 1;
        }

        parent::update($attributes, $id);
    }
}
