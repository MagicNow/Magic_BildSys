<?php

namespace App\Repositories;

use App\Models\ContratoItemModificacaoLog;
use InfyOm\Generator\Common\BaseRepository;
use App\Repositories\ContratoItemRepository;

class ContratoItemModificacaoLogRepository extends BaseRepository
{
    public function model()
    {
        return ContratoItemModificacaoLog::class;
    }
}
