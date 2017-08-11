<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQcAddTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quadro_de_concorrencias', function (Blueprint $table){
            $table->integer('contrato_template_id');
            $table->text('campos_extras_contrato')->nullable();

            $table->foreign('contrato_template_id')
                ->references('id')->on('contrato_templates')
                ->onDelete('restrict')
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
        Schema::table('quadro_de_concorrencias', function (Blueprint $table){
            $table->dropForeign(['contrato_template_id']);
            $table->dropColumn('contrato_template_id');
            $table->dropColumn('campos_extras_contrato');
        });
    }
}
