<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFornecedoresTable extends Migration
{
    /**
     * Run the migrations.
     * @table fornecedores
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fornecedores', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('codigo_mega')->nullable();
            $table->string('nome', 255);
            $table->string('cnpj', 25);
            $table->string('tipo_logradouro', 15)->nullable();
            $table->string('logradouro', 255)->nullable();
            $table->string('numero', 15)->nullable();
            $table->string('complemento', 255)->nullable();
            $table->unsignedInteger('cidade_id')->nullable();
            $table->string('municipio', 255)->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('situacao_cnpj', 1)->nullable();
            $table->string('inscricao_estadual', 25)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('site', 255)->nullable();
            $table->string('telefone', 45)->nullable();


            $table->foreign('cidade_id')
                ->references('id')->on('cidades')
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
       Schema::dropIfExists('fornecedores');
     }
}
