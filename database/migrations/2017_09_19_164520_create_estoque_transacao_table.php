<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstoqueEntradaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estoque_transacao', function (Blueprint $table) {

            $table->increments('id');

            $table->unsignedInteger('estoque_id');
            $table->unsignedInteger('nf_se_item_id')->nullable();
            $table->unsignedInteger('requisicao_id')->nullable();

            $table->enum('tipo', ['E', 'S']);

            $table->float('qtde', 8, 2)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('estoque_id')
                ->references('id')->on('estoque')
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
        Schema::dropIfExists('estoque_transacao');
    }
}
