<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OcStatusTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oc_status')->delete();

        $items = [
            [
                'id'=>1,
                'nome' => 'Em Aberto'
            ],
            [
                'id'=>2,
                'nome' => 'Fechada'
            ],
            [
                'id'=>3,
                'nome' => 'Em Aprovação'
            ],
            [
                'id'=>4,
                'nome' => 'Reprovada'
            ],
            [
                'id'=>5,
                'nome' => 'Aprovada'
            ],
            [
                'id'=>6,
                'nome' => 'Cancelada'
            ]
        ];

        DB::table('oc_status')->insert($items);
    }
}
