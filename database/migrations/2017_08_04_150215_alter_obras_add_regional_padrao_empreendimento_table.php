<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterObrasAddRegionalPadraoEmpreendimentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('obras', function (Blueprint $table) {
            $table->unsignedInteger('regional_id')->nullable();
            $table->unsignedInteger('padrao_empreendimento_id')->nullable();

            $table->foreign('regional_id')
                ->references('id')->on('regionais')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('padrao_empreendimento_id')
                ->references('id')->on('padrao_empreendimentos')
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
        Schema::table('obras', function (Blueprint $table) {
            $table->dropForeign(['regional_id']);
            $table->dropColumn('regional_id');

            $table->dropForeign(['padrao_empreendimento_id']);
            $table->dropColumn('padrao_empreendimento_id');
        });
    }
}
