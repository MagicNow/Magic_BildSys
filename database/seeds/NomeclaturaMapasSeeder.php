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
                'id' => 1,
                'nome' => 'Pré-Tipo',
                'tipo' => '1',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

                'largura_visual' => 100,
            ],
            [
                'id' => 2,
                'nome' => 'Tipo',
                'tipo' => '1',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 85,

            ],
            [
                'id' => 3,
                'nome' => 'Pós-Tipo',
                'tipo' => '1',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 60,

            ],
            [
                'id' => 4,
                'nome' => 'Subsolo 1',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 5,
                'nome' => 'Térreo',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 6,
                'nome' => 'Hall de Entrada',
                'tipo' => '3',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 7,
                'nome' => 'Quadrante',
                'tipo' => '3',
                'apenas_cartela' => 1,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 8,
                'nome' => 'Dias',
                'tipo' => '3',
                'apenas_cartela' => 0,
                'apenas_unidade' => 1,
                'largura_visual' => 100,

            ],
            [
                'id' => 9,
                'nome' => 'Bloco',
                'tipo' => '1',
                'apenas_cartela' => 1,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 10,
                'nome' => 'Linha',
                'tipo' => '2',
                'apenas_cartela' => 1,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 11,
                'nome' => 'Bloco',
                'tipo' => '1',
                'apenas_cartela' => 0,
                'apenas_unidade' => 1,
                'largura_visual' => 100,

            ],
            [
                'id' => 12,
                'nome' => 'Linha',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 1,
                'largura_visual' => 100,

            ],
            [
                'id' => 13,
                'nome' => 'Horas',
                'tipo' => '3',
                'apenas_cartela' => 0,
                'apenas_unidade' => 1,
                'largura_visual' => 100,

            ],
            [
                'id' => 14,
                'nome' => 'Quantidade',
                'tipo' => '3',
                'apenas_cartela' => 0,
                'apenas_unidade' => 1,
                'largura_visual' => 100,

            ],
            [
                'id' => 15,
                'nome' => 'Mês',
                'tipo' => '1',
                'apenas_cartela' => 0,
                'apenas_unidade' => 1,
                'largura_visual' => 100,

            ],
            [
                'id' => 16,
                'nome' => 'Semana',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 1,
                'largura_visual' => 100,

            ],
            [
                'id' => 17,
                'nome' => '1º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 18,
                'nome' => '2º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 19,
                'nome' => '3º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 20,
                'nome' => '4º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 21,
                'nome' => '5º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 22,
                'nome' => '6º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 23,
                'nome' => '7º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 24,
                'nome' => '8º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 25,
                'nome' => '9º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 26,
                'nome' => '10º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 27,
                'nome' => '11º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 28,
                'nome' => '12º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 29,
                'nome' => '13º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 30,
                'nome' => '14º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 31,
                'nome' => '15º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 32,
                'nome' => '16º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 33,
                'nome' => '17º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 34,
                'nome' => '18º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 35,
                'nome' => '19º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 36,
                'nome' => '20º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 37,
                'nome' => '21º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 38,
                'nome' => '22º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 39,
                'nome' => '23º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 40,
                'nome' => '24º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 41,
                'nome' => '25º Andar',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 42,
                'nome' => 'Terraço',
                'tipo' => '2',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 43,
                'nome' => 'Estacionamento',
                'tipo' => '3',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 44,
                'nome' => 'Salão de Festas',
                'tipo' => '3',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],
            [
                'id' => 45,
                'nome' => 'Apartamento',
                'tipo' => '3',
                'apenas_cartela' => 0,
                'apenas_unidade' => 0,
                'largura_visual' => 100,

            ],

        ];

        DB::table('nomeclatura_mapas')->insert($items);
    }
}
