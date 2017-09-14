<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMascaraPadraoInsumosTable extends Migration
{
    /**
     * Run the migrations.
     * @table orcamentos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mascara_padrao_insumos', function (Blueprint $table) {
            
            $table->increments('id');			            
            $table->unsignedInteger('insumo_id');
			$table->unsignedInteger('tipos_levantamento_id');            
            $table->decimal('coeficiente', 19, 6)->nullable();
            $table->decimal('indireto', 19, 2)->nullable();
            $table->decimal('terreo_externo_solo', 19, 2)->nullable();
            $table->decimal('terreo_externo_estrutura', 19, 2)->nullable();
            $table->decimal('terreo_interno', 19, 2)->nullable();
            $table->decimal('primeiro_pavimento', 19, 2)->nullable();
            $table->decimal('segundo_ao_penultimo', 19, 2)->nullable();
            $table->decimal('cobertura_ultimo_piso', 19, 2)->nullable();
            $table->decimal('atico', 19, 2)->nullable();
            $table->decimal('reservatorio', 19, 2)->nullable();
            $table->decimal('qtd_total', 19, 2)->nullable();
            $table->decimal('preco_unitario', 19, 2)->nullable();
            $table->decimal('preco_total', 19, 2)->nullable();
            $table->string('referencia_preco')->nullable();
            $table->text('obs')->nullable();
            $table->decimal('porcentagem_orcamento', 19, 2)->nullable();
            
            $table->foreign('insumo_id')
                ->references('id')->on('insumos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
			
			$table->foreign('tipos_levantamento_id')
                ->references('id')->on('tipo_levantamentos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
				
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
       Schema::dropIfExists('mascara_padrao_insumos');
     }
}
