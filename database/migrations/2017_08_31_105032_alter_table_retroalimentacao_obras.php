<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableRetroalimentacaoObras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('retroalimentacao_obras', function (Blueprint $table) {

            $table->dropColumn('categoria');
            $table->dropColumn('status');


            $table->unsignedInteger('categoria_id')->after('obra_id')->nullable();
            $table->foreign('categoria_id')->references('id')->on('retroalimentacao_obras_categorias')->onUpdate('cascade')->onDelete('restrict');

            $table->unsignedInteger('status_id')->after('obra_id')->nullable();
            $table->foreign('status_id')->references('id')->on('retroalimentacao_obras_status')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('retroalimentacao_obras', function (Blueprint $table) {

            $table->dropColumn('categoria');
            $table->dropColumn('status');

            $table->string('categoria')->nullable();
            $table->string('status')->nullable();
        });
    }
}
