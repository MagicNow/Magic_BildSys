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
	
	public static function getPrevistoPorcentagem($inicioTarefa, $fimTarefa, $inicioSemana){		
		
		//("19/06/2017", "11/07/2017", "07/07/2017", "14/07/2017" , "      REBAIXAMENTO DO LENÇOL");			

		$inicioTarefa = strtotime(str_replace('/', '-', $inicioTarefa));
		$fimTarefa = strtotime(str_replace('/', '-', $fimTarefa));	
		$inicioSemana = strtotime(str_replace('/', '-', $inicioSemana));
		
		$diasUteisTarefa = 0;	
		$diasUteisSemana = 0;
		
		if($inicioTarefa >= $inicioSemana){ 
			
			$valorPrevisto = 0;
		
		}elseif($inicioSemana > $fimTarefa){
			
			$valorPrevisto = 100.00;
		
		}else{			
			
			$diasUteisSemana = CronogramaFisicoRepository::getWorkingDays($inicioSemana, $inicioTarefa);
			$diasUteisTarefa = CronogramaFisicoRepository::getWorkingDays($fimTarefa , $inicioTarefa);
			
			$valorPrevisto = ($diasUteisSemana / $diasUteisTarefa)*100;				
			
		}		
		
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
		->where(DB::raw('DATE_FORMAT(medicao_fisica_logs.periodo_inicio, "%d/%m/%Y")'), '>=', $inicioSemana)
		->where(DB::raw('DATE_FORMAT(medicao_fisica_logs.periodo_termino, "%d/%m/%Y")'), '<=', $fimSemana)
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
		
	//The function returns the no. of business days between two dates and it skips the holidays
	public static function getWorkingDays($endDate, $startDate){		
		
		$holidays=array("2017-01-01","2017-02-27","2017-04-14","2017-09-07","2017-12-25");
		
		// do strtotime calculations just once 
		//$endDate = strtotime(str_replace('/', '-', $endDate));
		//$startDate = strtotime(str_replace('/', '-', $startDate));			

		//The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
		//We add one to inlude both dates in the interval.
		$days = ($endDate - $startDate) / 86400 + 1;

		$no_full_weeks = floor($days / 7);
		$no_remaining_days = fmod($days, 7);

		//It will return 1 if it's Monday,.. ,7 for Sunday
		$the_first_day_of_week = date("N", $startDate);
		$the_last_day_of_week = date("N", $endDate);

		//---->The two can be equal in leap years when february has 29 days, the equal sign is added here
		//In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
		if ($the_first_day_of_week <= $the_last_day_of_week) {
			if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
			if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
		}
		else {
			// (edit by Tokes to fix an edge case where the start day was a Sunday
			// and the end day was NOT a Saturday)

			// the day of the week for start is later than the day of the week for end
			if ($the_first_day_of_week == 7) {
				// if the start date is a Sunday, then we definitely subtract 1 day
				$no_remaining_days--;

				if ($the_last_day_of_week == 6) {
					// if the end date is a Saturday, then we subtract another day
					$no_remaining_days--;
				}
			}
			else {
				// the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
				// so we skip an entire weekend and subtract 2 days
				$no_remaining_days -= 2;
			}
		}

		//The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder //---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
		$workingDays = $no_full_weeks * 5;
		if ($no_remaining_days > 0 )
		{
		  $workingDays += $no_remaining_days;
		}

		//We subtract the holidays
		foreach($holidays as $holiday){
			$time_stamp=strtotime($holiday);
			//If the holiday doesn't fall in weekend
			if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
				$workingDays--;
		}
		
		//$workingDays = round($workingDays);
			
		return $workingDays;
	}	
	
    /**
     * Configure the Model
     **/
    public function model()
    {
        return CronogramaFisico::class;
    }

}
