<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     * @table contrato_templates
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_templates', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nome', 255);
            $table->longText('template');
            $table->timestamps();
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
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
       Schema::dropIfExists('contrato_templates');
     }
}
