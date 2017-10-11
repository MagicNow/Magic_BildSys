<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableMascaraPadraoEstruturasDropColumnCoeficienteIndireto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mascara_padrao_estruturas', function (Blueprint $table){
            $table->dropColumn('coeficiente');
            $table->dropColumn('indireto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mascara_padrao_estruturas', function (Blueprint $table){
            $table->decimal('coeficiente', 19,2);
            $table->decimal('indireto', 19,2);
        });
    }
}
