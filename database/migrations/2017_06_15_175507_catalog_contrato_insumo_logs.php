<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CatalogContratoInsumoLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_contrato_insumo_logs', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('contrato_insumo_id');
            $table->unsignedInteger('user_id');
            $table->decimal('valor_unitario_anterior', 19, 2)->nullable();
            $table->decimal('pedido_minimo_anterior', 19, 2)->nullable();
            $table->decimal('pedido_multiplo_de_anterior', 19, 2)->nullable();
            $table->date('periodo_inicio_anterior')->nullable();
            $table->date('periodo_termino_anterior')->nullable();

            $table->decimal('valor_unitario', 19, 2)->nullable();
            $table->decimal('pedido_minimo', 19, 2)->nullable();
            $table->decimal('pedido_multiplo_de', 19, 2)->nullable();
            $table->date('periodo_inicio')->nullable();
            $table->date('periodo_termino')->nullable();

            $table->timestamps();
            
            $table->foreign('contrato_insumo_id')
                ->references('id')->on('catalogo_contrato_insumos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreign('user_id')
                ->references('id')->on('users')
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
        Schema::drop('catalogo_contrato_insumo_logs');
    }
}
