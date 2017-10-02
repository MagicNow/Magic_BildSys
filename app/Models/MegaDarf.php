<?php

namespace App\Models;

use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class MegaDarf extends Eloquent
{
    protected $connection = 'oracle';

    public $table = 'MGGLO.GLO_DARF';
    // define the sequence name used for incrementing
    // default value would be {table}_{primaryKey}_seq if not set
    protected $sequence = 'drf_st_codigo';

    protected $casts = [
        'drf_st_codigo' => 'integer',
        'drf_st_descricao' => 'string'
    ];

    public function getDrfStDescricaoAttribute($value){
        return trim(utf8_encode($value));
    }
}
