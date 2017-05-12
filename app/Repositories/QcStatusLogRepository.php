<?php

namespace App\Repositories;

use App\Models\QcStatusLog;
use InfyOm\Generator\Common\BaseRepository;

class QcStatusLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return QcStatusLog::class;
    }
}
