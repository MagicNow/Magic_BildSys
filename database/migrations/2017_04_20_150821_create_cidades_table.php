<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCidadesTable extends Migration
{
    /**
     * Run the migrations.
     * @table cidades
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cidades', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nome', 100);
            $table->string('nome_completo', 250)->nullable();
            $table->string('cep', 16)->nullable();
            $table->char('uf', 2);
            $table->string('tipo_localidade', 1)->nullable()
                ->default('M')
                ->comment('M = Município
                P = Província
                D = Distrito');
            $table->unsignedInteger('cidade_id')->nullable();
            $table->foreign('cidade_id')
                ->references('id')->on('cidades')
                ->onDelete('set null')
                ->onUpdate('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cidades');
    }
}
