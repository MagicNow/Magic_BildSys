<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCatalogoAcordosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogo_contratos', function (Blueprint $table) {
            $table->dropColumn('data');
            $table->dropColumn('valor');
            $table->dropColumn('arquivo');
            $table->dropColumn('periodo_inicio');
            $table->dropColumn('periodo_termino');
            $table->dropColumn('valor_minimo');
            $table->dropColumn('valor_maximo');
            $table->dropColumn('qtd_minima');
            $table->dropColumn('qtd_maxima');

            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('user_id_update')->nullable();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id_update')
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
        Schema::table('catalogo_contratos', function (Blueprint $table) {
            $table->date('data');
            $table->decimal('valor', 19, 2);
            $table->string('arquivo', 255)->nullable();
            $table->date('periodo_inicio')->nullable();
            $table->date('periodo_termino')->nullable();
            $table->decimal('valor_minimo', 19, 2)->nullable();
            $table->decimal('valor_maximo', 19, 2)->nullable();
            $table->decimal('qtd_minima', 19, 2)->nullable();
            $table->decimal('qtd_maxima', 19, 2)->nullable();

            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            $table->dropForeign(['user_id_update']);
            $table->dropColumn('user_id_update');
        });
    }
}
