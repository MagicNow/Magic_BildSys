<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QcStatusTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('qc_status')->truncate();

        $items = [
            [
                'id'=>1,
                'nome' => 'Em Elaboração',
                'cor' => '#FCE9C0'
            ],
            [
                'id'=>2,
                'nome' => 'Fechada',
                'cor' => '#2B55B5'
            ],
            [
                'id'=>3,
                'nome' => 'Em Validação',
                'cor' => '#DEA447'
            ],
            [
                'id'=>4,
                'nome' => 'Reprovado',
                'cor' => '#CC2910'
            ],
            [
                'id'=>5,
                'nome' => 'Aprovado',
                'cor' => '#2BBD30'
            ],
            [
                'id'=>6,
                'nome' => 'Cancelado',
                'cor' => '#454545'
            ],
            [
                'id'=>7,
                'nome' => 'Em Negociação',
                'cor' => '#8C2EB8'
            ],
            [
                'id'=>8,
                'nome' => 'Fechado',
                'cor' => '#518709'
            ],
            [
                'id'=>9,
                'nome' => 'Finalizada',
                'cor' => '#66780B'
            ],
            [
                'id'=>10,
                'nome' => 'Rejeitado',
                'cor' => '#8D2036'
            ]
        ];

        DB::table('qc_status')->insert($items);

        Schema::enableForeignKeyConstraints();
    }
}
