<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcItemQcFornecedorTable extends Migration
{
    /**
     * Run the migrations.
     * @table qc_item_qc_fornecedor
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_item_qc_fornecedor', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('qc_item_id');
            $table->unsignedInteger('qc_fornecedor_id');
            $table->unsignedInteger('user_id');
            $table->decimal('qtd', 19, 2)->nullable();
            $table->decimal('valor_unitario', 19, 2)->nullable();
            $table->decimal('valor_total', 19, 2)->nullable();
            $table->tinyInteger('vencedor')->nullable();
            $table->dateTime('data_decisao')->nullable();
            $table->timestamps();


            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('qc_item_id')
                ->references('id')->on('qc_itens')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('qc_fornecedor_id')
                ->references('id')->on('qc_fornecedor')
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
       Schema::dropIfExists('qc_item_qc_fornecedor');
     }
}
