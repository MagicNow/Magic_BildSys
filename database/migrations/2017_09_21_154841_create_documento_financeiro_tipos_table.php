<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentoFinanceiroTiposTable extends Migration
{
    /**
     * Run the migrations.
     * @table documento_financeiro_tipos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documento_financeiro_tipos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nome', 255);
            $table->string('codigo_mega', 10);
            $table->tinyInteger('retem_irrf')->nullable()->default('0');
            $table->tinyInteger('retem_impostos')->nullable()->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documento_financeiro_tipos');
    }
}
