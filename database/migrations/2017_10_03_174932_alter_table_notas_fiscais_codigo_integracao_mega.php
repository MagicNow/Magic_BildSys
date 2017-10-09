<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNotasFiscaisCodigoIntegracaoMega extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            $table->tinyInteger("enviado_integracao")->default(0);
            $table->tinyInteger("integrado")->default(0);
            $table->char("status_integracao")->nullable();
            $table->string("codigo_integracao_mega", 50)->nullable();
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
            $table->dropColumn('enviado_integracao');
            $table->dropColumn('integrado');
            $table->dropColumn('status_integracao');
            $table->dropColumn('codigo_integracao_mega');
        });
    }
}
