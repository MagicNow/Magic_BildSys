<?php

namespace App\Repositories\Admin;

use App\Models\CronogramaFisico;
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
			$fridays[] = $date->format('Y-m-d');
		}
		
		$endDate= $endDate->format('Y-m-d');
		
		
		if(end($fridays) != $endDate){
			$fridays[] = $endDate;
		}				
		
		return $fridays;
	}
	
	public static function getIntervalMonthsByDates($fromDate, $toDate){
		
		//$fromDate = Carbon::parse($fromDate); //Transform string to Carbon
		//$toDate = Carbon::parse($toDate); //Transform string to Carbon
		
		$months = [];

		for($date = $fromDate; $date->lte($toDate); $date->addMonth()) {
			$months[] = $date->format('m/Y');
		}
		
		return $months;
	}
	
	public static function getDiasUteis($fromDate, $toDate){
		
		$diasUteis = 0;
		
		for($date = $fromDate; $date->lte($toDate); $date->addDay()) {
			 if($date->isWeekday()){
				$diasUteis++;
			 }
		}		
		
		return $diasUteis;
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
