<?php

namespace App\Console\Commands;

use App\Repositories\ConsultaNfeRepository;
use Illuminate\Console\Command;
use Exception;
use Log;

class CapturaNfeGeradas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'captura:nfe';

    protected $consultaNfeRepository;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Captura as notas fiscais no ambiente sefaz geradas contra o CNPJ da empresa';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ConsultaNfeRepository $consultaNfeRepository)
    {
        parent::__construct();
        $this->consultaNfeRepository = $consultaNfeRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            Log::info("Capturando NF-e's gerados!");
            $this->info("Capturando NF-e's gerados!");
            $download = 1;
            $fromCommand = 1;
            $this->consultaNfeRepository->syncXML($download, $fromCommand);
            $this->info("Captura de NF-e's finalizada!");
            Log::info("Captura de NF-e's finalizada!");
        } catch (Exception $e) {
            Log::error($e);
            $this->error(sprintf('Error: %s', $e->getMessage()));
        }
    }
}
