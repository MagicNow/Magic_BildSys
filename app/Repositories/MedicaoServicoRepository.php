<?php

namespace App\Repositories;

use App\Models\MedicaoServico;
use App\Models\ObraUser;
use InfyOm\Generator\Common\BaseRepository;

class MedicaoServicoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'qtd_funcionarios',
        'qtd_ajudantes',
        'qtd_outros',
        'descontos',
        'descricao_descontos',
        'user_id',
        'periodo_inicio',
        'periodo_termino',
        'contrato_item_apropriacao_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MedicaoServico::class;
    }

    public function findWithoutFail($id, $columns = ['*'])
    {
        try {
            $obj = $this->find($id, $columns);
            // Verifica se o usuário pode ver este item (devido ao acesso à obra)
            $obraUser = ObraUser::where('obra_id',$obj->contratoItemApropriacao->contratoItem->contrato->obra_id)
                                ->where('user_id', auth()->id())
                ->first();
            if(!$obraUser){
                return;
            }

            return $obj;
        } catch (Exception $e) {
            return;
        }
    }
}
