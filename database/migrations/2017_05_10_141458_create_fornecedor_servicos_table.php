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
            $table->unsignedInteger('cod_fornecedor');
//            $table->foreign('cod_fornecedor')
//                ->references('codigo_mega')->on('fornecedores')
//                ->onDelete('cascade')
//                ->onUpdate('cascade');
            $table->unsignedInteger('cod_servico');
//            $table->foreign('cod_servico')
//                ->references('id')->on('servico_cnae')
//                ->onDelete('cascade')
//                ->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fornecedor_servicos');

    }
}
