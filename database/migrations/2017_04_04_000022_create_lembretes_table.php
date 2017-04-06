<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLembretesTable extends Migration
{
    /**
     * Run the migrations.
     * @table lembretes
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lembretes', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('lembrete_tipo_id');
            $table->unsignedInteger('planejamento_id');
            $table->unsignedInteger('user_id');
            $table->string('nome');
            $table->integer('dias_prazo_minimo');
            $table->integer('dias_prazo_maximo')->nullable();
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('planejamento_id')
                ->references('id')->on('planejamentos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('lembrete_tipo_id')
                ->references('id')->on('lembrete_tipos')
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
       Schema::dropIfExists('lembretes');
     }
}
