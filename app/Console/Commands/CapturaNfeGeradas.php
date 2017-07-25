<?php

namespace App\Console\Commands;

use App\Repositories\ConsultaNfeRepository;
use Illuminate\Console\Command;

class CapturaNfeGeradas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'captura:nfe';

    protected $consultaNfeRepository = 'captura:nfe';

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
        $download = 1;
        $fromCommand = 1;
        $this->consultaNfeRepository->syncXML($download, $fromCommand);
    }
}
