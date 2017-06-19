<?php

use Illuminate\Database\Seeder;

class CatalogoContratoStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('catalogo_contrato_status')->delete();

        $items = [
            [
                'id'=>1,
                'nome' => 'Em Aberto',
                'cor' => '#dedede'
            ],
            [
                'id'=>2,
                'nome' => 'Aguardando Validação',
                'cor' => '#f2cc22'
            ],
            [
                'id'=>3,
                'nome' => 'Ativo',
                'cor' => '#2BBD30'
            ],
            [
                'id'=>4,
                'nome' => 'Inativo',
                'cor' => '#dd4b39'
            ]
        ];

        DB::table('catalogo_contrato_status')->insert($items);
    }
}
