<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPagamentosAddStatusIntegracao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagamentos', function (Blueprint $table){
            $table->string('status_integracao')->nullable();
            $table->string('codigo_integracao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagamentos', function (Blueprint $table){
            $table->dropColumn('status_integracao')->nullable();
            $table->dropColumn('codigo_integracao')->nullable();
        });
    }
}
