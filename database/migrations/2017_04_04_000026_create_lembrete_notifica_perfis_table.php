<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLembreteNotificaPerfisTable extends Migration
{
    /**
     * Run the migrations.
     * @table lembrete_notifica_perfis
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lembrete_notifica_perfis', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('lembrete_id');
            $table->unsignedInteger('perfil_id');


            $table->foreign('lembrete_id')
                ->references('id')->on('lembretes')
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
       Schema::dropIfExists('lembrete_notifica_perfis');
     }
}
