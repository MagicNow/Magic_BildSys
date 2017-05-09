<?php

namespace App\Providers;

use Validator;
use App\Models\EqualizacaoTecnicaItem;
use Illuminate\Support\ServiceProvider;
use App\Models\QcEqualizacaoTecnicaExtra;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('money', function($attributes, $value, $parameters) {
            return is_money($value);
        });

        Relation::morphMap([
            'equalizacao_tecnica_itens'     => EqualizacaoTecnicaItem::class,
            'qc_equalizacao_tecnica_extras' => QcEqualizacaoTecnicaExtra::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
