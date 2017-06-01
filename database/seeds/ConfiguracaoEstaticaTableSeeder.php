<?php

use Illuminate\Database\Seeder;

class ConfiguracaoEstaticaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        \Illuminate\Support\Facades\DB::table('configuracao_estaticas')->delete();

        $items = [
            [
                'id' => 1,
                'chave' => 'Obrigação fornecedor',
                'valor' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed facilisis rhoncus pellentesque. Sed at bibendum ipsum. Donec urna eros, congue quis lectus id, aliquam tincidunt nulla. Phasellus pretium feugiat tellus, eget eleifend justo facilisis scelerisque. Nulla congue lorem vel blandit suscipit. Mauris rutrum, mauris et volutpat sodales, erat est venenatis augue, sit amet viverra eros eros at nunc. In erat nunc, dictum at dapibus ac, eleifend finibus sem. In ornare finibus ligula at scelerisque. Nam arcu mi, sodales a turpis ut, lobortis convallis lectus.'
            ],
            [
                'id' => 2,
                'chave' => 'Obrigação bild',
                'valor' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed facilisis rhoncus pellentesque. Sed at bibendum ipsum. Donec urna eros, congue quis lectus id, aliquam tincidunt nulla. Phasellus pretium feugiat tellus, eget eleifend justo facilisis scelerisque. Nulla congue lorem vel blandit suscipit. Mauris rutrum, mauris et volutpat sodales, erat est venenatis augue, sit amet viverra eros eros at nunc. In erat nunc, dictum at dapibus ac, eleifend finibus sem. In ornare finibus ligula at scelerisque. Nam arcu mi, sodales a turpis ut, lobortis convallis lectus.'
            ]
        ];

        foreach ($items as $item) {
            \App\Models\ConfiguracaoEstatica::create($item);
        }
    }
}
