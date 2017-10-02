<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreOrcamentosTable extends Migration
{
    /**
     * Run the migrations.
     * @table orcamentos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_orcamentos', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('obra_id');
            $table->string('codigo_insumo', 45);
            $table->unsignedInteger('insumo_id');
            $table->unsignedInteger('servico_id');
            $table->unsignedInteger('grupo_id');
            $table->string('unidade_sigla', 5);
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
            $table->unsignedInteger('orcamento_tipo_id');
            $table->tinyInteger('ativo')->nullable()->default('1');
            $table->unsignedInteger('subgrupo1_id');
            $table->unsignedInteger('subgrupo2_id');
            $table->unsignedInteger('subgrupo3_id');
            $table->unsignedInteger('user_id');
			$table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('obra_id')
                ->references('id')->on('obras')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('insumo_id')
                ->references('id')->on('insumos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('unidade_sigla')
                ->references('sigla')->on('unidades')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('servico_id')
                ->references('id')->on('servicos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('grupo_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('orcamento_tipo_id')
                ->references('id')->on('orcamento_tipos')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('subgrupo1_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('subgrupo2_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('subgrupo3_id')
                ->references('id')->on('grupos')
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
       Schema::dropIfExists('pre_orcamento');
     }
}
