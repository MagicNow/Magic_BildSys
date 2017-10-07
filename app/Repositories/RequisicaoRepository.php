<?php

namespace App\Repositories;

use App\Models\Requisicao;
use App\Models\RequisicaoItem;
use InfyOm\Generator\Common\BaseRepository;

class RequisicaoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'obra_id',
        'user_id',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Requisicao::class;
    }

    public function create(array $input) {

        try {

            $r = parent::create([
                'obra_id' => $input['obra_id'],
                'local' => $input['local'],
                'torre' => $input['torre'],
                'pavimento' => $input['pavimento'],
                'trecho' => $input['trecho'],
                'andar' => $input['andar'],
                'user_id' => auth()->id(),
                'status' => 1
            ]);

            $this->requisicaoItem($input, $r->id);

            return true;

        } catch (Exception $e) {
            throw $e;
        }

    }

    public function requisicaoItem(array $input, $requisicao_id) {

        $insumos = json_decode($input['insumos']);

        foreach ($insumos as $insumo) {

            if ($insumo->qtde > 0) {

                RequisicaoItem::create([
                    'requisicao_id' => $requisicao_id,
                    'estoque_id' => $insumo->estoque_id,
                    'unidade' => '',
                    'qtde' => $insumo->qtde,
                    'local' => $input['local'],
                    'torre' => $input['torre'],
                    'pavimento' => $input['pavimento'],
                    'trecho' => $input['trecho'],
                    'andar' => $input['andar'],
                ]);

            }
        }

        $comodos = json_decode($input['comodos']);

        foreach ($comodos as $comodo) {

            if ($comodo->qtde > 0) {

                RequisicaoItem::create([
                    'requisicao_id' => $requisicao_id,
                    'estoque_id' => $comodo->estoque_id,
                    'unidade' => '',
                    'qtde' => $comodo->qtde,
                    'local' => $input['local'],
                    'torre' => $input['torre'],
                    'pavimento' => $input['pavimento'],
                    'trecho' => $input['trecho'],
                    'andar' => $input['andar'],
                    'apartamento' => $comodo->apartamento,
                    'comodo' => $comodo->comodo,
                ]);

            }
        }

    }
}
