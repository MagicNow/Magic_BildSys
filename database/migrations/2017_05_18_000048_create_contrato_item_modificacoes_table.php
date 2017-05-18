<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoItemModificacoesTable extends Migration
{
    /**
     * Run the migrations.
     * @table contrato_item_modificacoes
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_item_modificacoes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('contrato_item_id');
            $table->decimal('qtd_aterior', 19, 2);
            $table->decimal('qtd_atual', 19, 2);
            $table->decimal('valor_unitario_anterior', 19, 2);
            $table->decimal('valor_unitario_atual', 19, 2);
            $table->string('tipo_modificacao', 45)->nullable();
            $table->unsignedInteger('contrato_status_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->nullableTimestamps();


            $table->foreign('contrato_item_id')
                ->references('id')->on('contrato_itens')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('contrato_status_id')
                ->references('id')->on('contrato_status')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
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
       Schema::dropIfExists('contrato_item_modificacoes');
     }
}
