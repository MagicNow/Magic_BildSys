<?php

namespace App\Repositories\Admin;

use Exception;
use App\Models\MedicaoFisica;
use App\Models\MedicaoFisicaLog;
use Illuminate\Support\Facades\DB;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Support\Arr;

class MedicaoFisicaRepository extends BaseRepository
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
        return MedicaoFisica::class;
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
        $medicaoFisica = $this->find($id);

        DB::beginTransaction();
        try {
			
			MedicaoFisicaLog::create([
                'medicao_fisica_id' => $medicaoFisica->id,
				'valor_medido_anterior' => $medicaoFisica['valor_medido'],
				'valor_medido_atual' => $request['valor_medido'],
                'user_id' => auth()->id(),                
            ]);
            
            $medicaoFisica->update([                
                'valor_medido' => $request['valor_medido'],
				'periodo_inicio' => $request['periodo_inicio'],
				'periodo_termino' => $request['periodo_termino'],
            ]);
            
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();


        return $medicaoFisica;
    }

	
}
