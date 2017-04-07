<?php

namespace App\Jobs;

use App\Models\Planilha;
use App\Repositories\Admin\SpreadsheetRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PlanilhaProcessa implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $planilha;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Planilha $planilha)
    {
        $this->planilha = $planilha;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        # Processo será adicionado na FILA
        SpreadsheetRepository::SpreadsheetProcess($this->planilha);

    }
}
