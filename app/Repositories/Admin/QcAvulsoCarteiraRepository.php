<?php

namespace App\Repositories\Admin;

use App\Models\QcAvulsoCarteira;
use InfyOm\Generator\Common\BaseRepository;

class QcAvulsoCarteiraRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome',
        'sla_start',
        'sla_negociacao',
        'sla_mobilizacao',
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return QcAvulsoCarteira::class;
    }

    public function findByUser($user_id)
    {
        return $this->model->whereHas('users', function($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })
        ->orderBy('nome','ASC')
        ->get();
    }


	public function comTarefasObra($obra_id)
    {
        return $this->model
            ->whereHas('tarefas', function($query) use ($obra_id) {
                $query
                    ->join('planejamentos', 'planejamentos.id', '=', 'qc_avulso_carteira_id.planejamento_id')
                    ->where('planejamentos.obra_id', $obra_id);
            })
            ->orderBy('nome','ASC')
            ->get();
    }

    public function todasComObraVinculada()
    {
        return $this->model->whereHas('tarefas', function($query) {
            $query
                ->join('obras', 'planejamentos.obra_id', 'obras.id')
                ->join('obra_users', 'obra_users.obra_id', 'obras.id')
                ->where('obra_users.user_id', auth()->id());
        })->get();
    }
}
