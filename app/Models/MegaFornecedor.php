<?php

namespace App\Models;

use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class MegaFornecedor extends Eloquent
{
    protected $connection = 'oracle';

    public $primaryKey = 'agn_in_codigo';

    public $table = 'MGGLO.GLO_AGENTES';
    // define the sequence name used for incrementing
    // default value would be {table}_{primaryKey}_seq if not set
    protected $sequence = 'GRU_IN_CODIGO';

    protected $casts = [
        'agn_in_codigo' => 'integer',
        'agn_st_nome' => 'string',
    ];
}
