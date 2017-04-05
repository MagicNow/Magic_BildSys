<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGruposTable extends Migration
{
    /**
     * Run the migrations.
     * @table grupos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupos', function (Blueprint $table) {

            $table->increments('id');
            $table->string('codigo', 45);
            $table->string('nome');
            $table->unsignedInteger('grupo_id')->nullable();
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('grupo_id')
                ->references('id')->on('grupos')
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
       Schema::dropIfExists('grupos');
     }
}
