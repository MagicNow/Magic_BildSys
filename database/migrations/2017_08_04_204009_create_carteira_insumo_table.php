<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarteiraInsumoTable extends Migration
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
            $table->unsignedInteger('carteira_id');
            $table->foreign('carteira_id')->references('id')->on('carteiras')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('insumo_id');
            $table->foreign('insumo_id')->references('id')->on('insumos')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::table('carteira_insumos', function(Blueprint $table) {
            $table->dropForeign(['carteira_id']);
            $table->dropForeign(['insumo_id']);
        });

        Schema::dropIfExists('carteira_insumos');

    }
}
