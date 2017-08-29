<?php

namespace App\Console\Commands;

use App\Repositories\ConsultaNfeRepository;
use Illuminate\Console\Command;
use Exception;
use Log;

class ManifestaNfeGeradas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manifesta:nfe';

    protected $consultaNfeRepository;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manifesta as notas fiscais resumidas no ambiente sefaz';

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
            Log::info("Manifestando NF-e's no Sefaz!");
            $this->info("Manifestando NF-e's no Sefaz!");
            $fromCommand = 1;
            $this->consultaNfeRepository->manifestaNotas($fromCommand);
            $this->info("Manifestando NF-e's no Sefaz finalizada!");
        } catch (Exception $e) {
            Log::error($e);
            $this->error(sprintf('Error: %s', $e->getMessage()));
        }
    }
}
