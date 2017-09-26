<?php

namespace App\Console;

use App\Console\Commands\CapturaNfeGeradas;
use App\Console\Commands\CapturaCTeGerados;
use App\Console\Commands\ManifestaNfeGeradas;
use App\Repositories\Admin\CatalogoContratoRepository;
use App\Repositories\ImportacaoRepository;
use App\Repositories\LpuGerarRepository;
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
        ManifestaNfeGeradas::class,
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
            })
            ->name('importacao:repository')
            ->twiceDaily(9, 18)
            ->withoutOverlapping();

        $schedule->call(function () {
                Log::info('Inicio de execucao importação de Insumos');
                $importaInsumo = ImportacaoRepository::insumos();
                Log::info('Executado script de importação de Insumos', $importaInsumo);
            })
            ->twiceDaily(10, 19)
            ->name('importacao:repository')
            ->withoutOverlapping();
		
		/*$schedule->call(function () {
                Log::info('Inicio de execucao gerar Lpu');
                $lpuGerar = LpuGerarRepository::calcular();
                Log::info('Executado script de gerar Lpu', $lpuGerar);
            })
			->monthlyOn(date('t'), time);            
            ->name('lpu:repository');
            ->withoutOverlapping();*/

        $schedule->command('captura:nfe')
            ->everyThirtyMinutes()
            ->sendOutputTo(storage_path('nfe/captura-nfe.log'))
            ->name('captura:nfe')
            ->withoutOverlapping();

        $schedule->command('captura:cte')
            ->hourly()
            ->sendOutputTo(storage_path('cte/captura-cte.log'))
            ->name('captura:cte')
            ->withoutOverlapping();

        $schedule->command('manifesta:nfe')
            ->daily()
            ->sendOutputTo(storage_path('nfe/manifesta-nfe.log'))
            ->name('manifesta:nfe')
            ->withoutOverlapping();
		
		$schedule->call(function () {
                Log::info('Inicio de execucao importação de Insumos');
                $importaInsumo = ImportacaoRepository::insumos();
                Log::info('Executado script de importação de Insumos', $importaInsumo);

                Log::info('Inicio de execucao importação Condições de Pagamento');
                $importa = ImportacaoRepository::pagamentoCondicoes();
                Log::info('Executado script de importação de Condições de Pagamento', $importa);

                Log::info('Inicio de execucao importação de Tipos de Documentos Fiscais');
                $importaDocumentoFiscal = ImportacaoRepository::documentoTipos();
                Log::info('Executado script de importação de Tipos de Documentos Fiscais', $importaDocumentoFiscal);

                Log::info('Inicio de execucao importação de Tipos de Documentos Financeiros');
                $importaDocumentoFinanceiros = ImportacaoRepository::documentoFinanceiroTipos();
                Log::info('Executado script de importação de Tipos de Documentos Financeiros', $importaDocumentoFinanceiros);

            })
            ->twiceDaily(10, 19)
            ->name('importacao:repository')
            ->withoutOverlapping();

        $schedule->call(function () {
            Log::info('Inicio de execucao Atualização de Contratos com os valores dos acordos');
            $atualizaContratosExistentes = CatalogoContratoRepository::atualizaContratosExistentes();
            Log::info('Executado script de Atualização de Contratos com os valores dos acordos', $atualizaContratosExistentes);
        })
            ->name('CatalogoContratoRepository:atualizaContratosExistentes')
            ->dailyAt('00:01')
            ->withoutOverlapping();
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
