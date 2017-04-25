<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuadroDeConcorrenciasTable extends Migration
{
    /**
     * Run the migrations.
     * @table quadro_de_concorrencias
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quadro_de_concorrencias', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('qc_status_id');
            $table->text('obrigacoes_fornecedor')->nullable();
            $table->text('obrigacoes_bild')->nullable();
            $table->integer('rodada_atual')->nullable()->default('1');
            $table->timestamps();


            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('qc_status_id')
                ->references('id')->on('qc_status')
                ->onDelete('restrict')
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
       Schema::dropIfExists('quadro_de_concorrencias');
     }
}
