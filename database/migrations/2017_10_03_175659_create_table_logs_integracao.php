<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLogsIntegracao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs_integracao', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status', 10);
            $table->string('codigo_integracao_mega', 50);
            $table->text('mensagem');
            $table->integer('object_id');
            $table->string('object_type');
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
        Schema::dropIfExists('logs_integracao');
    }
}
