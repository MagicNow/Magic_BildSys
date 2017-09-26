<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class MegaCondicaoPagamento extends Eloquent
{
    protected $connection = 'oracle';

    public $table = 'MGGLO.GLO_CONDPAGTO';
    // define the sequence name used for incrementing
    // default value would be {table}_{primaryKey}_seq if not set
    protected $sequence = 'cond_st_codigo';

    protected $casts = [
        'cond_st_codigo' => 'string',
        'cond_st_nome' => 'string'
    ];

    /**
     * Adicionando uma condição passada pelo cliente, pois as condições de pagamento à ser utilizadas são apenas as
     * que o cond_tab_in_codigo é igual à 56
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('cond_tab_in_codigo', function (Builder $builder) {
            $builder->where('cond_tab_in_codigo', '=', 56);
        });
    }

    public function getCondStCodigoAttribute($value){
        return trim(utf8_encode($value));
    }
    public function getCondStNomeAttribute($value){
        return trim(utf8_encode($value));
    }
}