<?php

namespace App\Models;

use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class MegaInsumo extends Eloquent
{
    protected $connection = 'oracle';

    public $table = 'MGADM.EST_PRODUTOS';
    // define the sequence name used for incrementing
    // default value would be {table}_{primaryKey}_seq if not set
    protected $sequence = 'PRO_IN_CODIGO';

    protected $casts = [
        'PRO_IN_CODIGO' => 'integer',
        'PRO_ST_DESCRICAO' => 'string',
        'GRU_IN_CODIGO' => 'integer',
        'UNI_ST_UNIDADE' => 'string',
    ];
}
