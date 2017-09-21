<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQcColumnTipografia extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('qc', function (Blueprint $table){
			$table->dropColumn('tipologia');
			$table->float('valor_gerencial', 12, 2)->after('valor_orcamento_inicial');
			$table->tinyInteger('carteira_comprada')->after('status');
			$table->unsignedInteger('topologia_id')->after('carteira_id');
			$table->foreign('topologia_id')
				->references('id')
				->on('topologias')
				->onUpdate('cascade')
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
		Schema::table('qc', function (Blueprint $table){
			$table->dropColumn('topologia_id');
			$table->dropColumn('valor_gerencial');
			$table->dropColumn('carteira_comprada');
			$table->dropForeign(['tipo_levantamento_id']);
			$table->string('topologia', 50);
		});
	}
}
