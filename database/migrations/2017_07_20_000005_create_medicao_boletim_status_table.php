<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicaoBoletimStatusTable extends Migration
{
    /**
     * Run the migrations.
     * @table medicao_boletim_status
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicao_boletim_status', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nome', 45);
            $table->string('cor', 7)->nullable();
        });

        $seeder = new MedicaoBoletimStatusTableSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('medicao_boletim_status');
     }
}
