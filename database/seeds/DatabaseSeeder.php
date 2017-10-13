<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        //$this->call(UserTableSeeder::class);
        $this->call(RolesAndPermissions::class);
        $this->call(TipoOrcamentoTableSeeder::class);
        $this->call(WorkflowTipoTableSeeder::class);
        $this->call(OcStatusTableSeed::class);
        $this->call(QcStatusTableSeed::class);
        $this->call(CidadesTableSeeder::class);
        $this->call(ContratoStatusTableSeeder::class);
        $this->call(ConfiguracaoEstaticaTableSeeder::class);
        $this->call(LpuStatusTableSeeder::class);
		$this->call(SeStatusTableSeeder::class);
        $this->call(TemplateEmailTableSeeder::class);
		$this->call(TemplatePlanilhasTableSeeder::class);
		$this->call(TipoLevantamentosTableSeeder::class);
        Schema::enableForeignKeyConstraints();
    }
}
