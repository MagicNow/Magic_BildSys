<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQtdMinimasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qtd_minimas', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('qtd', 19, 2);
            $table->unsignedInteger('obra_id');
            $table->unsignedInteger('insumo_id');

            $table->timestamps();

            $table->foreign('obra_id')
                ->references('id')->on('obras')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('insumo_id')
                ->references('id')->on('insumos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });

        Schema::table('insumos', function (Blueprint $table) {
            $table->dropColumn('qtd_minima');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qtd_minimas');

        Schema::table('insumos', function (Blueprint $table) {
            $table->decimal('qtd_minima', 19, 2)->nullable();
        });
    }
}
