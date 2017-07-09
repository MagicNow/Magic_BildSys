<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemoriaCalculosTable extends Migration
{
    /**
     * Run the migrations.
     * @table memoria_calculos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memoria_calculos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nome', 255)->nullable();
            $table->tinyInteger('padrao')->default('0');
            $table->unsignedInteger('user_id');
            $table->char('modo', 1)->nullable()->default('T')->comment('T = Torre
C = Cartela
U = Unidade');

            $table->foreign('user_id')
                ->references('id')->on('users')
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
       Schema::dropIfExists('memoria_calculos');
     }
}
