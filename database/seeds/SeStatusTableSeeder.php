<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('se_status')->truncate();

        $items = [
            [
                'id'   => 1,
                'nome' => 'Em Aprovação',
                'cor'  => '#DEA447'
            ],
            [
                'id'   => 2,
                'nome' => 'Reprovado',
                'cor'  => '#CC2910'
            ],
            [
                'id'   => 3,
                'nome' => 'Aprovado',
                'cor'  => '#2BBD30'
            ],
            [
                'id'   => 4,
                'nome' => 'Cancelado',
                'cor'  => '#454545'
            ],
            [
                'id'   => 5,
                'nome' => 'Recebido',
                'cor'  => '#2B55B5'
            ],
        ];

        DB::table('se_status')->insert($items);

        Schema::enableForeignKeyConstraints();
    }
}
