<?php

namespace App\Repositories\Admin;

use App\Models\Grupo;
use App\Models\Insumo;
use App\Models\MascaraPadraoEstrutura;
use App\Models\MascaraPadraoInsumo;
use App\Models\Servico;
use InfyOm\Generator\Common\BaseRepository;

class MascaraPadraoEstruturaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'codigo',
        'coeficiente',
        'indireto',
        'mascara_padrao_id',
        'grupo_id',
        'subgrupo1_id',
        'subgrupo2_id',
        'subgrupo3_id',
        'servico_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MascaraPadraoEstrutura::class;
    }

    public function create(array $attributes)
    {
//        dd($attributes);
        $estrutura['grupos'] = [];
        foreach ($attributes['estrutura'] as $item){
            $grupo = $item['id'];
            foreach($item['itens'] as $item_subgrupo1){
                $subgrupo1 = $item_subgrupo1['id'];
                foreach($item_subgrupo1['itens'] as $item_subgrupo2){
                    $subgrupo2 = $item_subgrupo2['id'];
                    foreach($item_subgrupo2['itens'] as $item_subgrupo3){
                        $subgrupo3 = $item_subgrupo3['id'];
                        foreach($item_subgrupo3['itens'] as $item_servico){
                            $servico = $item_servico['id'];

                            #Busca estrutura ex: 01.01.01.01.001
                            $codigo_servico = Servico::find($servico);
                            $estrutura['grupos'][] =
                                [
                                    'grupo_id' => $grupo,
                                    'subgrupo1_id' => $subgrupo1,
                                    'subgrupo2_id' => $subgrupo2,
                                    'subgrupo3_id' => $subgrupo3,
                                    'servico_id' => $servico,
                                    'codigo' => $codigo_servico->codigo
                                ];
                        }
                    }
                }
            }
        }
//        dd($estrutura['grupos']);
        foreach($estrutura['grupos'] as $grupo) {
            $grupo['mascara_padrao_id'] = $attributes['mascara_padrao_id'];
            $model = parent::updateOrCreate(
                [
                    'codigo'     => $grupo['codigo'],
                    'mascara_padrao_id' => $grupo['mascara_padrao_id']
                ],
                [
                    'grupo_id'     => $grupo['grupo_id'],
                    'subgrupo1_id' => $grupo['subgrupo1_id'],
                    'subgrupo2_id' => $grupo['subgrupo2_id'],
                    'subgrupo3_id' => $grupo['subgrupo3_id'],
                    'servico_id'   => $grupo['servico_id']
                ]
            );
        }
        return $model;
    }

    public function update(array $attributes, $id)
    {
        $model = parent::update($attributes, $id);
        return $model;
    }
}
