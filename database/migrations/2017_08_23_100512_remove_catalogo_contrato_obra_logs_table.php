<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCatalogoContratoObraLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('catalogo_contrato_obra_logs');
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('catalogo_contrato_obra_logs', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('catalogo_contrato_obra_id');
            $table->unsignedInteger('catalogo_contrato_status_id');
            $table->timestamps();

        });
        Schema::enableForeignKeyConstraints();
    }
}
