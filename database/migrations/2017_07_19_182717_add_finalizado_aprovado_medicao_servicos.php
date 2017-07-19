<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFinalizadoAprovadoMedicaoServicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medicao_servicos', function (Blueprint $table){
            $table->tinyInteger('finalizado')->default(0);
            $table->tinyInteger('aprovado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medicao_servicos', function (Blueprint $table){
            $table->dropColumn('finalizado');
            $table->dropColumn('aprovado');
        });
    }
}
