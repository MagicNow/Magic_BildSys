<?php

namespace App\Models;

use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class MegaTipoDocumentoFiscal extends Eloquent
{
    protected $connection = 'oracle';

    public $table = 'MGTRF.TRF_TIPODOCFISCAL';
    // define the sequence name used for incrementing
    // default value would be {table}_{primaryKey}_seq if not set
    protected $sequence = 'tdf_in_codigo';

    protected $casts = [
        'tdf_in_codigo' => 'integer',
        'tdf_st_sigla' => 'string',
        'tdf_st_descricao' => 'string',
    ];

    public function getTdfStSiglaAttribute($value){
        return trim(utf8_encode($value));
    }
    public function getTdfStDescricaoAttribute($value){
        return trim(utf8_encode($value));
    }
}
