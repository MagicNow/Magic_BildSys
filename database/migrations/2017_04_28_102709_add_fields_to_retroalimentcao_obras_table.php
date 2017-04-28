<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToRetroalimentcaoObrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('retroalimentacao_obras', function (Blueprint $table){
            //Cadastro
            $table->dropColumn(['nome','descricao']);
            $table->string('origem')->nullable();
            $table->string('categoria')->nullable();
            $table->text('situacao_atual')->nullable();
            $table->text('situacao_proposta')->nullable();
            $table->date('data_inclusao');

            //Listagem
            $table->text('acao')->nullable();
            $table->date('data_prevista')->nullable();
            $table->date('data_conclusao')->nullable();
            $table->string('status')->nullable();
            $table->string('resultado_obtido')->nullable();
            $table->boolean('aceite')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('retroalimentacao_obras', function (Blueprint $table) {
            $table->dropColumn([
                'origem',
                'categoria',
                'situacao_atual',
                'situacao_proposta',
                'data_inclusao',
                'acao',
                'data_prevista',
                'data_conclusao',
                'status',
                'resultado_obtido',
                'aceite'
            ]);
            $table->string('nome');
            $table->text('descricao')->nullable();
        });
    }
}
