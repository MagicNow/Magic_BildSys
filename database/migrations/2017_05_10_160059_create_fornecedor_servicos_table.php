<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFornecedorServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fornecedor_servicos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('codigo_fornecedor_id');
            $table->foreign('codigo_fornecedor_id')->references('id')->on('fornecedores')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('codigo_servico_id');
            $table->foreign('codigo_servico_id')->references('id')->on('servicos_cnae')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fornecedor_servicos', function(Blueprint $table) {
            $table->dropForeign(['codigo_fornecedor_id']);
            $table->dropColumn('codigo_fornecedor_id');
        });
        Schema::table('fornecedor_servicos', function(Blueprint $table) {
            $table->dropForeign(['codigo_servico_id']);
            $table->dropColumn('codigo_servico_id');
        });
        Schema::dropIfExists('fornecedor_servicos');

    }
}
