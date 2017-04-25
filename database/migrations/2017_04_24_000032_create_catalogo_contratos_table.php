<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogoContratosTable extends Migration
{
    /**
     * Run the migrations.
     * @table catalogo_contratos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_contratos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('fornecedor_id');
            $table->date('data');
            $table->decimal('valor', 19, 2);
            $table->string('arquivo', 255)->nullable();
            $table->date('periodo_inicio')->nullable();
            $table->date('periodo_termino')->nullable();
            $table->decimal('valor_minimo', 19, 2)->nullable();
            $table->decimal('valor_maximo', 19, 2)->nullable();
            $table->decimal('qtd_minima', 19, 2)->nullable();
            $table->decimal('qtd_maxima', 19, 2)->nullable();
            $table->timestamps();



            $table->foreign('fornecedor_id')
                ->references('id')->on('fornecedores')
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
       Schema::dropIfExists('catalogo_contratos');
     }
}
