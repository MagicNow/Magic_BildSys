<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLembreteNotificaUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     * @table lembrete_notifica_usuarios
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lembrete_notifica_usuarios', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('lembrete_id');
            $table->unsignedInteger('user_id');


            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

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
       Schema::dropIfExists('lembrete_notifica_usuarios');
     }
}
