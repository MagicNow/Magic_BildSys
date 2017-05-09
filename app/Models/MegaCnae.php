<?php

namespace App\Models;

use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class MegaCnae extends Eloquent
{
    protected $connection = 'oracle';

    public $table = 'MGTRF.TRF_CODSERVICO';
    // define the sequence name used for incrementing
    // default value would be {table}_{primaryKey}_seq if not set
    protected $sequence = 'COS_IN_CODIGO';

    protected $casts = [
        'COS_IN_CODIGO' => 'integer',
        'COS_ST_DESCRICAO' => 'string'
    ];
}
