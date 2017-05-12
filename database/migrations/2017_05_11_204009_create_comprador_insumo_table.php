<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompradorInsumoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprador_insumos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::table('comprador_insumos', function(Blueprint $table) {
            $table->dropColumn('user_id', 'user_id');
            $table->dropColumn('insumo_id', 'insumo_id');
        });

        Schema::dropIfExists('comprador_insumos');

    }
}
