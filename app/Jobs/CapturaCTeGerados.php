<?php

namespace App\Jobs;

use App\Repositories\ConsultaCteRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CapturaCTeGerados implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $consultaCTeRepository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ConsultaCteRepository $consultaCteRepository)
    {
        $this->consultaCteRepository = $consultaCteRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $download = 1;
        $fromCommand = 1;
        $this->consultaCteRepository->syncXML($download, $fromCommand);
    }
}
