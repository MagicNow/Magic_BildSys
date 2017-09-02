<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetroalimentacaoObrasStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $seeder = new RetroalimentacaoObrasStatusTableSeeder();

        Schema::create('retroalimentacao_obras_status', function (Blueprint $table){

            $table->increments('id');

            $table->string('nome',100);

            $table->timestamps();
            $table->softDeletes();
        });

        $seeder->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('retroalimentacao_obras_status');
        Schema::enableForeignKeyConstraints();
    }
}
