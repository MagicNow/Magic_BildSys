<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LpuStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('lpu_status')->truncate();

        $items = [
            [
                'id'   => 1,
                'nome' => 'Automático',
                'cor'  => '#DEA447'
            ],
            [
                'id'   => 2,
                'nome' => 'Manual',
                'cor'  => '#CC2910'
            ],
        ];

        DB::table('lpu_status')->insert($items);

        Schema::enableForeignKeyConstraints();
    }
}
