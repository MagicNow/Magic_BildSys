<?php

namespace App\Models;

use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class MegaFornecedorServico extends Eloquent
{
    protected $connection = 'oracle';

    public $primaryKey = 'agn_in_codigo';

    public $table = 'MGTRF.TRF_CODSERVICOAGN';
    // define the sequence name used for incrementing
    // default value would be {table}_{primaryKey}_seq if not set
    protected $sequence = 'AGN_IN_CODIGO';

    protected $casts = [
        'agn_in_codigo' => 'integer'
    ];
}
