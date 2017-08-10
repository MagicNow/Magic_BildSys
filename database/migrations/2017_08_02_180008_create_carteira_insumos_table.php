<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarteiraInsumosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carteira_insumos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('insumo_id');
            $table->unsignedInteger('carteira_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('insumo_id')
                ->references('id')->on('insumos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('carteira_id')
                ->references('id')->on('carteiras')
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
        Schema::dropIfExists('carteira_insumos');

    }
}
