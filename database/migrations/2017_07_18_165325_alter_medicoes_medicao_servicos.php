<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMedicoesMedicaoServicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medicao_servicos', function (Blueprint $table){
            $table->dropForeign(['medicao_id']);
            $table->dropColumn('medicao_id');
            $table->date('periodo_inicio');
            $table->date('periodo_termino');
            $table->timestamps();
        });

        Schema::table('medicoes', function (Blueprint $table){
            $table->unsignedInteger('medicao_servico_id')->nullable();
            $table->foreign('medicao_servico_id')
                ->references('id')
                ->on('medicao_servicos')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medicoes', function (Blueprint $table){
            $table->dropForeign(['medicao_servico_id']);
            $table->dropColumn('medicao_servico_id');
        });
        Schema::table('medicao_servicos', function (Blueprint $table){
            $table->unsignedInteger('medicao_id');
            $table->foreign('medicao_id')
                ->references('id')
                ->on('medicoes')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->dropTimestamps();
        });
    }
}
