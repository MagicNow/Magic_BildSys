<?php

use Illuminate\Database\Seeder;

class RetroalimentacaoObrasStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('retroalimentacao_obras_status')->delete();

        $items = [
            [
                'id' => 1,
                'nome' => 'Status 1',
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 2,
                'nome' => 'Status 2',
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 3,
                'nome' => 'Status 3',
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 4,
                'nome' => 'Status 4',
                'created_at' => date('Y-m-d H::s')
            ]

        ];

        DB::table('retroalimentacao_obras_status')->insert($items);
        Schema::enableForeignKeyConstraints();

    }
}
