<?php

namespace App\Repositories\Admin;

use App\Models\CronogramaFisico;
use App\Models\MedicaoFisica;
use App\Models\MedicaoFisicaLog;
use Illuminate\Support\Facades\DB;
use InfyOm\Generator\Common\BaseRepository;
use Carbon\Carbon;

class CronogramaFisicoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'obra_id',
        'custo',
        'resumo',
		'torre',
		'pavimento',
		'tarefa',
		'critica',
        'data_inicio',
        'data_termino',
		'concluida'
    ];	
	
	public static function getFridaysByDate($fromDate){
		
		$lastday = date('t-m-Y',strtotime($fromDate));		
		
		$fridays = [];		
		
		$startDate = Carbon::parse($fromDate)->next(Carbon::FRIDAY); // Get the first friday.
		$endDate = Carbon::parse($lastday);		

		for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
			$fridays[] = $date->format('d/m/Y');
		}
		
		$endDate= $endDate->format('d/m/Y');		
		
		if(end($fridays) != $endDate){
			$fridays[] = $endDate;
		}				
		
		return $fridays;
	}
	
	public static function getIntervalMonthsByDates($fromDate, $toDate){				
		
		$months = [];

		for($date = $fromDate; $date->lte($toDate); $date->addMonth()) {
			$months[] = $date->format('m/Y');
		}
		
		return $months;
	}
	
	public static function getPrevistoPorcentagem($inicioTarefa, $fimTarefa, $inicioSemana, $fimSemana){
				
		$diasUteisTarefa = 0;	
		$diasUteisSemana = 0;
		$valorPrevisto = 0;
		
		if($inicioTarefa > $fimSemana){
			$valorPrevisto = 0;
		}elseif($fimSemana > $fimTarefa){
			$valorPrevisto = 100;
		}else{
			
			//Calcular os dias uteis do periodo da tarefa
			for($date = $inicioTarefa; $date->lte($fimTarefa); $date->addDay()) {
				 if($date->isWeekday()){
					$diasUteisTarefa++;
				 }
			}	
			
			//Calcular os dias uteis do periodo da semana de referencia
			for($date = $inicioSemana; $date->lte($fimSemana); $date->addDay()) {
				 if($date->isWeekday()){
					$diasUteisSemana++;
				 }
			}
			//VIGA DE COROAMENTO
			//14/07 - 07/07 : 18/7 - 7/7 
			
			//07/07 - 26/07 : 10/07 - 1/7 

			$valorPrevisto = $diasUteisSemana/ $diasUteisTarefa;
		}

		//echo "Ta- ".$diasUteisTarefa." Sem- ".$diasUteisSemana;
		
		return $valorPrevisto;		
			
	}
	
	public static function getRealizadoPorcentagem($inicioSemana, $fimSemana, $obraId, $tarefa){						
					
		$valorMedicaoFisica = MedicaoFisica::select([
			'medicao_fisicas.id',
			'medicao_fisicas.tarefa',
			'medicao_fisica_logs.valor_medido',
			'medicao_fisica_logs.periodo_inicio',
			'medicao_fisica_logs.periodo_termino'
		])
		->join('medicao_fisica_logs','medicao_fisica_logs.medicao_fisica_id','medicao_fisicas.id')
		->join('obras','obras.id','medicao_fisicas.obra_id')		
		->where('medicao_fisicas.obra_id', $obraId)		
		->where('medicao_fisicas.tarefa', $tarefa)
		->where(DB::raw('DATE_FORMAT(medicao_fisica_logs.periodo_inicio, "%Y/%m/%d")'), '>=', $inicioSemana)
		->where(DB::raw('DATE_FORMAT(medicao_fisica_logs.periodo_termino, "%Y/%m/%d")'), '>=', $fimSemana)		
		->get()
		->toArray();
						
		if(count($valorMedicaoFisica) > 0){
			
			$valorRealizado = 0;	
			
			//Soma os valores que estao no log e dentro do periodo da semana de referencia
			foreach($valorMedicaoFisica as $valor){
								
				$valorRealizado = floatval($valorRealizado) + floatval($valor['valor_medido']);	
					
			}
			
		}else{
			$valorRealizado = 0;			
		} 		
				
		return $valorRealizado;
			
	}
	
	/*public static function getIntervalBydate($fromDate){
		
		$lastday = date('t-m-Y',strtotime($fromDate));
		$endDate = Carbon::parse($lastday);	
		$endDate= $endDate->format('d/m/Y');				
		
		return $month;
	}*/
	

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CronogramaFisico::class;
    }

}
