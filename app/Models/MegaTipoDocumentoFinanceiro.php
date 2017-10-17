<?php

namespace App\Models;

use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;
use Illuminate\Database\Eloquent\Builder;

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
        return trim(utf8_encode($value));
    }
    public function getRetemImpostosAttribute($value){
        return trim(utf8_encode($value));
    }

    /**
     * Adicionando uma condição passada pelo cliente, pois as condições de pagamento à ser utilizadas são apenas as
     * que o cond_tab_in_codigo é igual à 56
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('tpd_bo_permitidocpa', function (Builder $builder) {
            $builder->where('tpd_bo_permitidocpa', '=', 'S');
        });
        static::addGlobalScope('tpd_bo_permitidocre', function (Builder $builder) {
            $builder->where('tpd_bo_permitidocre', '=', 'N');
        });
        static::addGlobalScope('tpd_bo_permitidomvcre', function (Builder $builder) {
            $builder->where('tpd_bo_permitidomvcre', '=', 'N');
        });
    }
}
