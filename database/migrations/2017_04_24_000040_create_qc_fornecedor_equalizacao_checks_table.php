<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcFornecedorEqualizacaoChecksTable extends Migration
{
    /**
     * Run the migrations.
     * @table qc_fornecedor_equalizacao_checks
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_fornecedor_equalizacao_checks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('qc_fornecedor_id');
            $table->unsignedInteger('user_id');
            $table->string('checkable_type', 255);
            $table->unsignedInteger('checkable_id');
            $table->tinyInteger('checked')->nullable()->default('0');
            $table->text('obs')->nullable();
            $table->timestamps();



            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('qc_fornecedor_id')
                ->references('id')->on('qc_fornecedor')
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
       Schema::dropIfExists('qc_fornecedor_equalizacao_checks');
     }
}
