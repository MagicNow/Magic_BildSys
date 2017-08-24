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
            ],
            [
                'id' => 3,
                'chave' => 'Ciente no boletim da Medição de Serviço',
                'valor' => 'Declaro concordar com os valores e quantidades constantes nesta medição, não restando nada a medir até esta data, e  estar ciente  da necessidade do envio da documentação descriminada em contrato para a liberação desta medição.'
            ],
            [
                'id' => 4,
                'chave' => 'Cabeçalho Bild Matriz',
                'valor' => 'BILD DESENVOLVIMENTO IMOBILIARIO , pessoa jurídica de direito privado, com sede na AV. PROFESSOR JOÃO FIUSA - 2340, Bairro: JD. CANADÁ na cidade de RIBEIRÃO PRETO / SP com o CEP: 14024-260 , inscrita no CNPJ sob nº 08.964.23/0001-50, Inscrição Estadual: 582.884.524.111 e Inscrição Municipal: 12635301 com seu contrato social devidamente registrado na junta Comercial do Estado de São Paulo, neste ato representada na forma de seus atos constitutivos, doravante denominada simplesmente CONTRATANTE.'
            ]
        ];

        foreach ($items as $item) {
            \App\Models\ConfiguracaoEstatica::create($item);
        }
    }
}
