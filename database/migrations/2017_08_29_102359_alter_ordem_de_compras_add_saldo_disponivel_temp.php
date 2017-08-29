<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdemDeComprasAddSaldoDisponivelTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordem_de_compras', function (Blueprint $table){
            $table->decimal('saldo_disponivel_temp',19,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordem_de_compras', function (Blueprint $table){
            $table->dropColumn('saldo_disponivel_temp');
        });
    }
}
