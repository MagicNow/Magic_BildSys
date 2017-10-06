<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcAnexosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_anexos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('qc_id');         
            $table->string('arquivo')->nullable();
            $table->string('descricao')->nullable();
            $table->string('tipo')->nullable();
            $table->timestamps();

            $table->foreign('qc_id')
                ->references('id')->on('qc')
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
        Schema::dropIfExists('qc_anexos');
    }
}
