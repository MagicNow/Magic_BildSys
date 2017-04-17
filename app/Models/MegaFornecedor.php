<?php

namespace App\Models;

use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class MegaFornecedor extends Eloquent
{
    protected $connection = 'oracle';

    public $table = 'MGGLO.GLO_AGENTES';
    // define the sequence name used for incrementing
    // default value would be {table}_{primaryKey}_seq if not set
    protected $sequence = 'GRU_IN_CODIGO';

    protected $casts = [
        'GRU_IDE_ST_CODIGO' => 'string',
        'GRU_IN_CODIGO' => 'integer',
        'GRU_ST_NOME' => 'string',
    ];
}
