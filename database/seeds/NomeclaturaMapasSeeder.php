<?php

use Illuminate\Database\Seeder;

class NomeclaturaMapasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('nomeclatura_mapas')->delete();

        $items = [
            [
                'id'=>1,
                'nome' => 'Pré-Tipo',
                'tipo' => '1',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
            ],
            [
                'id'=>2,
                'nome' => 'Tipo',
                'tipo' => '1',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
            ],
            [
                'id'=>3,
                'nome' => 'Pós-Tipo',
                'tipo' => '1',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
            ],
            [
                'id'=>4,
                'nome' => 'Subsolo 1',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
            ],
            [
                'id'=>5,
                'nome' => 'Térreo',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
            ],
            [
                'id'=>6,
                'nome' => 'Hall de Entrada',
                'tipo' => '3',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
            ],
            [
                'id'=>7,
                'nome' => 'Quadrante',
                'tipo' => '3',
                'apenas_cartela' => 1,
                'apenas_unidade' => 0,
            ],
            [
                'id'=>8,
                'nome' => 'Dias',
                'tipo' => '3',
                'apenas_cartela' => 0,
                'apenas_unidade' => 1,
            ],

        ];

        DB::table('nomeclatura_mapas')->insert($items);
    }
}
