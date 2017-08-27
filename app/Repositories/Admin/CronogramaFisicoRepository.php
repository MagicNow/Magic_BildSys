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
	
	public static function getFridaysBydate($fromDate){
		
		$lastday = date('Y-m-t',strtotime($fromDate));		
		
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

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CronogramaFisico::class;
    }

}
