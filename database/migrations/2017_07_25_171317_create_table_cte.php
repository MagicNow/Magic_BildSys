<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCte extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("ctes", function(Blueprint $table){
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('nsu');
            $table->string('chave');
            $table->string('schema', 50);
            $table->string('versao', 10);
            $table->string('numero', 100);
            $table->dateTime('data_recibo')->nullable();
            $table->dateTime('data_emissao')->nullable();
            $table->string('natureza_operacao')->nullable();
            $table->longText('xml')->nullable();
            $table->string('cnpj_emitente', 20)->nullable();
            $table->string('nome_emitente')->nullable();
            $table->string('cnpj_remetente', 20)->nullable();
            $table->string('nome_remetente')->nullable();
            $table->string('cnpj_destinatario', 20)->nullable();
            $table->string('nome_destinatario')->nullable();
            $table->string('origem')->nullable();
            $table->string('origem_uf', 2)->nullable();
            $table->string('destino')->nullable();
            $table->string('destino_uf', 2)->nullable();
            $table->decimal('valor_carga', 19, 2)->nullable();
            $table->decimal('valor_cobrado', 19, 2)->nullable();
        });

        Schema::create("cte_notas", function(Blueprint $table){
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('cte_id');
            $table->string('chave_nfe');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("ctes");
        Schema::drop("cte_notas");
    }
}
