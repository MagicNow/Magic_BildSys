<?php

use Illuminate\Database\Seeder;

class RequisicaoStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('requisicao_status')->delete();

        $items = [
            [
                'id' => 1,
                'nome' => 'Nova',
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 2,
                'nome' => 'Em Separação',
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 3,
                'nome' => 'Em Trânsito',
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 4,
                'nome' => 'Aplicada Parcial',
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 5,
                'nome' => 'Aplicada Total',
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 6,
                'nome' => 'Cancelada',
                'created_at' => date('Y-m-d H::s')
            ]

        ];

        DB::table('requisicao_status')->insert($items);
        Schema::enableForeignKeyConstraints();

    }
}
