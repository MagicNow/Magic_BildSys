<?php

namespace App\Repositories;

use Exception;
use App\Models\Lpu;
use App\Models\LpuStatus;
use App\Models\LpuStatusLog;
use Illuminate\Support\Facades\DB;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Support\Arr;

class LpuRepository extends BaseRepository
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
        return Lpu::class;
    }
	
	/*public function create(array $request)
    {
        $lpu = collect($request['lpu']);        

        DB::beginTransaction();
        try {
            
            $lpu = parent::create([
                'contrato_id'   => $request['contrato_id'],
                'user_id'       => auth()->id(),
                'se_status_id'  => SeStatus::EM_APROVACAO,
                'valor_total'   => 0,
                'fornecedor_id' => $fornecedor_id,
                'anexo'         => $nome_anexo
            ]);

            LpuStatusLog::create([
                'se_status_id'           => 1,
                'user_id'                => auth()->id(),
                'solicitacao_entrega_id' => $lpu->id,
            ]);           

            $lpu->updateTotal();
			
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return $lpu;
    }*/


    public function update(array $request, $id)
    {
        $lpu = $this->find($id);

        DB::beginTransaction();
        try {
            
            $lpu->update([                
                'valor_sugerido' => $request['valor_sugerido'],
            ]);
            LpuStatusLog::create([
                'lpu_id' => $lpu->id,
				'valor_sugerido_anterior' => $lpu['valor_sugerido'],
				'valor_sugerido_atual' => $request['valor_sugerido'],
				'lpu_status_id' => LpuStatus::MANUAL,
                'user_id' => auth()->id(),                
            ]);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();


        return $lpu;
    }

	
}
