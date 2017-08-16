<?php

namespace App\Jobs;

use App\Repositories\ConsultaNfeRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CapturaNfeGeradas implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $consultaNfeRepository;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ConsultaNfeRepository $consultaNfeRepository)
    {
        $this->consultaNfeRepository = $consultaNfeRepository;
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
        $this->consultaNfeRepository->syncXML($download, $fromCommand);
    }
}
