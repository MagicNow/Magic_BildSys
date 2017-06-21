<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoItemModificacaoApropriacaoChangeQtdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrato_item_modificacao_apropriacao', function($table) {
            $table->renameColumn('qtd', 'qtd_atual');
            $table->decimal('qtd_anterior', 19, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contrato_item_modificacao_apropriacao', function($table) {
            $table->renameColumn('qtd_atual', 'qtd');
            $table->dropColumn('qtd_anterior');
        });
    }
}
