<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNotaFiscalFaturas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('nota_fiscal_faturas');
        Schema::create("nota_fiscal_faturas", function(Blueprint $table){
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('nota_fiscal_id');
            $table->string("numero")->nullable();
            $table->date("vencimento")->nullable();
            $table->decimal("valor", 19, 2)->nullable();
            $table->index(["nota_fiscal_id"], 'fk_nota_fiscal_itens_notas_fiscais2_idx');
            $table->foreign('nota_fiscal_id', 'fk_nota_fiscal_itens_notas_fiscais2_idx')
                ->references('id')
                ->on('notas_fiscais')
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
        Schema::drop("nota_fiscal_faturas");
    }
}
