<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCatalogoContratoInsumosRemoveMaximaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogo_contrato_insumos', function (Blueprint $table) {
           $table->dropColumn('valor_maximo');
           $table->dropColumn('qtd_maxima');
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
            $table->decimal('valor_maximo', 19, 2)->nullable();
            $table->decimal('qtd_maxima', 19, 2)->nullable();
        });
    }
}
