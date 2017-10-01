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
	
	public function create(array $request)
    {

        DB::beginTransaction();
        try {
            
            $medicaoFisica = parent::create([
                'obra_id' => $request['obra_id'],
				'tarefa' => $request['tarefa'],
				'periodo_inicio' => $request['periodo_inicio'],
				'periodo_termino' => $request['periodo_termino'],
				'valor_medido' => $request['valor_medido'],
            ]);

            MedicaoFisicaLog::create([
                'medicao_fisica_id' => $medicaoFisica->id,
				'periodo_inicio' => $request['periodo_inicio'],
				'periodo_termino' => $request['periodo_termino'],
				'valor_medido_anterior' => $medicaoFisica['valor_medido'],
				'valor_medido_atual' => $request['valor_medido'],
                'user_id' => auth()->id(), 
            ]);           
			
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return $medicaoFisica;
    }


    public function update(array $request, $id)
    {
        $medicaoFisica = $this->find($id);

        DB::beginTransaction();
        try {
			
			MedicaoFisicaLog::create([
                'medicao_fisica_id' => $medicaoFisica->id,
				'periodo_inicio' => $request['periodo_inicio'],
				'periodo_termino' => $request['periodo_termino'],
				'valor_medido_anterior' => floatval($medicaoFisica['valor_medido']),
				'valor_medido_atual' => floatval($request['valor_medido']),
                'user_id' => auth()->id(),                
            ]);
            
            $medicaoFisica->update([                
                'valor_medido' => floatval($request['valor_medido']),
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
