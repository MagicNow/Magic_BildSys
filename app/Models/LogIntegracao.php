<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Levantamento
 * @package App\Models
 * @version April 5, 2017, 11:58 am BRT
 */
class LogIntegracao extends Model
{
    public $table = 'logs_integracao';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function loggable()
    {
        return $this->morphTo();
    }

}
