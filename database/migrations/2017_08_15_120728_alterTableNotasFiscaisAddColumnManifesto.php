<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNotasFiscaisAddColumnManifesto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            $table->integer('manifesto')->default(0);
            $table->integer('manifesto_status')->nullable();
            $table->string('retorno_manifesto_motivo', 400)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            $table->dropColumn('manifesto');
            $table->dropColumn('retorno_manifesto_motivo');
            $table->dropColumn('manifesto_status');
        });
    }
}
