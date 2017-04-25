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
        //$this->call(UserTableSeeder::class);
        $this->call(RolesAndPermissions::class);
        $this->call(TipoOrcamentoTableSeeder::class);
        $this->call(WorkflowTipoTableSeeder::class);
        $this->call(OcStatusTableSeed::class);
    }
}
