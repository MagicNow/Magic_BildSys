<?php

namespace App\Repositories\Admin;

use App\Models\MascaraPadraoEstrutura;
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

                            #Busca codigo estruturado do serviço
                            $codigo_estruturado = Servico::find($servico);
                            $estrutura['grupos'][] =
                                [
                                    'grupo_id' => $grupo,
                                    'subgrupo1_id' => $subgrupo1,
                                    'subgrupo2_id' => $subgrupo2,
                                    'subgrupo3_id' => $subgrupo3,
                                    'servico_id' => $servico,
                                    'codigo' => $codigo_estruturado->codigo
                                ];
                        }
                    }
                }
            }
        }
        foreach($estrutura['grupos'] as $grupo){
            $grupo['mascara_padrao_id'] = $attributes['mascara_padrao_id'];
            parent::create($grupo);
        }
        return true;
    }

    public function update(array $attributes, $id)
    {
        $model = parent::update($attributes, $id);
        return $model;
    }
}
