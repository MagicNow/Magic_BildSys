<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCatalogoContratoInsumosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogo_contrato_insumos', function (Blueprint $table) {
            $table->decimal('qtd_maxima', 19, 2)->nullable();
            $table->date('periodo_inicio')->nullable();
            $table->date('periodo_termino')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalogo_contrato_insumos', function (Blueprint $table) {
            $table->dropColumn('qtd_maxima');
            $table->dropColumn('periodo_inicio');
            $table->dropColumn('periodo_termino');
        });
    }
}
