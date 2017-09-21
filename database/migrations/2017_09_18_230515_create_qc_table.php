<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('obra_id');
            $table->string('tipologia',50);
            $table->unsignedInteger('carteira_id');
            $table->text('descricao')->nullable();
            $table->float('valor_pre_orcamento', 12, 2);
            $table->float('valor_orcamento_inicial', 12, 2);
            $table->string('status', 50)->default('Em aprovação');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('carteira_id')
                ->references('id')->on('carteiras')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('obra_id')
                ->references('id')->on('obras')
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
        Schema::dropIfExists('qc');
    }
}
