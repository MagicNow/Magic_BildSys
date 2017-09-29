<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQcAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qc', function (Blueprint $table) {
            $table->unsignedInteger('fornecedor_id')->nullable()->after('user_id');
            $table->decimal('valor_fechamento', 19, 2)->nullable()->after('user_id');;
            $table->string('numero_contrato', 30)->after('user_id');;

            $table->foreign('fornecedor_id')
                ->references('id')->on('fornecedores')
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
        Schema::table('qc', function (Blueprint $table) {
            $table->dropForeign(['fornecedor_id']);

            $table->dropColumn('fornecedor_id');
            $table->dropColumn('valor_fechamento');
            $table->dropColumn('numero_contrato');
        });
    }
}
