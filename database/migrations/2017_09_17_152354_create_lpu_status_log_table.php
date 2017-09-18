<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLpuStatusLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lpu_status_log', function($table) {
            $table->increments('id');
            $table->unsignedInteger('lpu_id');
			$table->decimal('valor_sugerido_anterior', 19, 2);
			$table->decimal('valor_sugerido_atual', 19, 2);
            $table->unsignedInteger('lpu_status_id');
            $table->unsignedInteger('user_id');
            
			$table->timestamps();
            $table->softDeletes();

            $table->foreign('lpu_status_id')
                ->references('id')
                ->on('se_status');

            $table->foreign('lpu_id')
                ->references('id')
                ->on('lpu')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lpu_status_log');
    }
}
