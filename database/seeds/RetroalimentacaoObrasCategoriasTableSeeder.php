<?php

use Illuminate\Database\Seeder;

class RetroalimentacaoObrasCategoriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('retroalimentacao_obras_categorias')->delete();

        $items = [
            [
                'id' => 1,
                'nome' => 'Quantidade',
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 2,
                'nome' => 'Escopo',
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 3,
                'nome' => 'Consumo',
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 4,
                'nome' => 'Máscara',
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 5,
                'nome' => 'Projeto',
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 6,
                'nome' => 'Orçamento',
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 7,
                'nome' => 'Procedimento',
                'created_at' => date('Y-m-d H::s')
            ]

        ];

        DB::table('retroalimentacao_obras_categorias')->insert($items);
        Schema::enableForeignKeyConstraints();

    }
}
