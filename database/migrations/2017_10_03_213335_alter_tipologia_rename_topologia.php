<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTipologiaRenameTopologia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qc', function (Blueprint $table){
            $table->renameColumn('tipologia_id', 'topologia_id');
        });

        Schema::rename('tipologias', 'topologias');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qc', function (Blueprint $table){
            $table->renameColumn('topologia_id', 'tipologia_id');
        });

        Schema::rename('topologias', 'tipologias');
    }
}
