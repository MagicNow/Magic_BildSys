<?php

namespace App\Repositories\Admin;

use App\Models\CronogramaFisico;
use App\Models\MedicaoFisica;
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
		
		//$fromDate = Carbon::parse($fromDate); //Transform string to Carbon
		//$toDate = Carbon::parse($toDate); //Transform string to Carbon
		
		$months = [""];

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
			
			$valorPrevisto = $diasUteisSemana/ $diasUteisTarefa;
		}		
		
		return $valorPrevisto;		
			
	}
	
	public static function getRealizadoPorcentagem($inicioSemana, $fimSemana, $obraId, $tarefa){
					
		/*$tabColetaSemanal = MedicaoFisica::select([
			'cronograma_fisicos.id',
			'cronograma_fisicos.tarefa',
			'cronograma_fisicos.data_inicio',
			'cronograma_fisicos.data_termino'
		])
		->join('obras','obras.id','cronograma_fisicos.obra_id')
		->join('medicao_fisicas','obras.id','cronograma_fisicos.obra_id')
		->join('template_planilhas','template_planilhas.id','cronograma_fisicos.template_id')
		->where('cronograma_fisicos.obra_id', $obraId)
		->where('cronograma_fisicos.resumo','Não')
		->where('cronograma_fisicos.data_termino','>=',$inicioMes)
		->where('cronograma_fisicos.data_inicio','<=',$fimMes)		
		->where('template_planilhas.nome',$tipoPlanejamento)		
		->orderBy('cronograma_fisicos.data_inicio', 'desc')
		->groupBy('cronograma_fisicos.tarefa')		
		->get()
		->toArray();		
		
		return $valorRealizado;	*/	
			
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
