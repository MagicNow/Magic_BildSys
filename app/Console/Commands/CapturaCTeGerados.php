<?php

namespace App\Console\Commands;

use App\Repositories\ConsultaCteRepository;
use Illuminate\Console\Command;
use Exception;
use Log;

class CapturaCTeGerados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'captura:cte';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Captura os conhecimentos de notas fiscais no ambiente sefaz';

    protected $consultaCTeRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ConsultaCteRepository $consultaCTeRepository)
    {
        parent::__construct();
        $this->consultaCTeRepository = $consultaCTeRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            Log::info("Capturando Ctes gerados!");
            $this->info("Capturando Ctes gerados!");
            $download = 1;
            $fromCommand = 1;
            $this->consultaCTeRepository->syncXML($download, $fromCommand);
            $this->info("Captura de Ctes finalizada!");
            Log::info("Captura de Ctes finalizada!");
        } catch (Exception $e) {
            Log::error($e);
            $this->error(sprintf('Error: %s', $e->getMessage()));
        }
    }
}
