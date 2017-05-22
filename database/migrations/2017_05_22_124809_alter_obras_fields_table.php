<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterObrasFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('obras', function (Blueprint $table) {
            $table->integer('num_torres')->nullable()->change();
            $table->integer('num_apartamentos')->nullable()->change();
            $table->integer('num_pavimento_tipo')->nullable()->change();
            $table->decimal('indice_bild_pre', 19, 3)->nullable()->change();
            $table->decimal('indice_bild_oi', 19, 3)->nullable()->change();
            $table->text('referencias_comerciais')->nullable()->change();

            $table->string('adm_obra_telefone')->nullable();
            $table->string('eng_obra_telefone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('obras', function (Blueprint $table) {
            $table->decimal('num_torres', 19, 2)->nullable()->change();
            $table->decimal('num_apartamentos', 19, 2)->nullable()->change();
            $table->decimal('num_pavimento_tipo', 19, 2)->nullable()->change();
            $table->decimal('indice_bild_pre', 19, 2)->nullable()->change();
            $table->decimal('indice_bild_oi', 19, 2)->nullable()->change();
            $table->string('referencias_comerciais')->nullable()->change();

            $table->dropColumn('adm_obra_telefone');
            $table->dropColumn('eng_obra_telefone');
        });
    }
}
