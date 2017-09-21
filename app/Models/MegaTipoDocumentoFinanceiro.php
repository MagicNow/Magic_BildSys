<?php

namespace App\Models;

use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class MegaTipoDocumentoFinanceiro extends Eloquent
{
    protected $connection = 'oracle';

    public $table = 'mgfin.fin_tipo_documento';
    // define the sequence name used for incrementing
    // default value would be {table}_{primaryKey}_seq if not set
    protected $sequence = 'tpd_st_codigo';

    protected $casts = [
        'tpd_st_codigo' => 'string',
        'tpd_st_descricao' => 'string',
        'tpd_bo_retemirrf' => 'string',
        'tpd_bo_retemimpostos' => 'string',
    ];

    public function getTpdStCodigoAttribute($value){
        return trim(utf8_encode($value));
    }

    public function getTpdStDescricaoAttribute($value){
        return trim(utf8_encode($value));
    }

    public function getRetemIrrfAttribute($value){
        return intval(strtoupper(trim(utf8_encode($value)))=='S');
    }
    public function getRetemImpostosAttribute($value){
        return intval(strtoupper(trim(utf8_encode($value)))=='S');
    }
}
