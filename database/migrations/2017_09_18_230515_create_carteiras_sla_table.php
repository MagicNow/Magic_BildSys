<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarteirasSlaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carteiras_sla', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('obra_id');
            $table->unsignedInteger('carteira_id');
            $table->date('obra_inicio')->nullable();
            $table->date('obra_subir_qc')->nullable();
            $table->date('obra_aprovar_qc')->nullable();
            $table->date('obra_finalizar_qc')->nullable();
            $table->date('inicio_atividade')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('carteira_id')
                ->references('id')->on('carteiras')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('obra_id')
                ->references('id')->on('obras')
                ->onDelete('cascade')
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
        Schema::dropIfExists('carteiras_sla');
    }
}
