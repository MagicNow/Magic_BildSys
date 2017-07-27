<?php

namespace App\Console;

use App\Console\Commands\CapturaNfeGeradas;
use App\Console\Commands\CapturaCTeGerados;
use App\Repositories\ImportacaoRepository;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CapturaNfeGeradas::class,
        CapturaCteGerados::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Log::info('Inicio de execucao importação de grupos');
            $importaInsumoGrupos = ImportacaoRepository::insumo_grupos();
            Log::info('Executado script de importação de grupo', $importaInsumoGrupos);
        })->twiceDaily(9, 18);

        $schedule->call(function () {
            Log::info('Inicio de execucao importação de Insumos');
            $importaInsumo = ImportacaoRepository::insumos();
            Log::info('Executado script de importação de Insumos', $importaInsumo);
        })->twiceDaily(10, 19);

        $schedule->command('captura:nfe')
            ->hourly();

        $schedule->command('captura:cte')
            ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
