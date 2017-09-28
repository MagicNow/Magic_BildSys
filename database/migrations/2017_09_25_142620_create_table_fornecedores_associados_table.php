<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFornecedoresAssociadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('fornecedores_associados');
        Schema::create('fornecedores_associados', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('fornecedor_id');
            $table->unsignedInteger('fornecedor_associado_id');
            $table->foreign('fornecedor_id')->references('id')->on('fornecedores')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('fornecedor_associado_id')->references('id')->on('fornecedores')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fornecedores_associados');
    }
}
