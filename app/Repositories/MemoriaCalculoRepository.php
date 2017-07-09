<?php

namespace App\Repositories;

use App\Models\MemoriaCalculo;
use InfyOm\Generator\Common\BaseRepository;

class MemoriaCalculoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome',
        'padrao',
        'user_id',
        'modo'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MemoriaCalculo::class;
    }

    public function create(array $attributes)
    {
        $attributes['blocos'] = [];
        $attributes['user_id'] = auth()->id();
        if (isset($attributes['estrutura_bloco'])) {
            foreach ($attributes['estrutura_bloco'] as $indexEstrutura => $estrutura_id) {
                $ordemBloco = $attributes['estrutura_bloco_ordem'][$indexEstrutura];
                if (isset($attributes['pavimentos'][$indexEstrutura])) {
                    foreach ($attributes['pavimentos'][$indexEstrutura] as $indexPavimento => $pavimento_id) {
                        $ordemLinha = $attributes['pavimento_bloco_ordem'][$indexEstrutura][$indexPavimento];

                        if (isset($attributes['trecho'][$indexEstrutura][$indexPavimento])) {
                            foreach ($attributes['trecho'][$indexEstrutura][$indexPavimento] as $indexTrecho => $trecho_id) {
                                $ordem = $attributes['trecho_bloco_ordem'][$indexEstrutura][$indexPavimento][$indexTrecho];
                                $attributes['blocos'][] =
                                    [
                                        'estrutura' => $estrutura_id,
                                        'pavimento' => $pavimento_id,
                                        'trecho' => $trecho_id,
                                        'ordem' => $ordem,
                                        'ordem_linha' => $ordemLinha,
                                        'ordem_bloco' => $ordemBloco
                                    ];
                            }
                        }
                    }
                }

            }

        }

        $model = parent::create($attributes);

        return $model;
    }

    public function update(array $attributes, $id)
    {

        $attributes['blocos'] = [];
        $attributes['user_id'] = auth()->id();
        if (isset($attributes['estrutura_bloco'])) {
            foreach ($attributes['estrutura_bloco'] as $indexEstrutura => $estrutura_id) {
                $ordemBloco = $attributes['estrutura_bloco_ordem'][$indexEstrutura];
                if (isset($attributes['pavimentos'][$indexEstrutura])) {
                    foreach ($attributes['pavimentos'][$indexEstrutura] as $indexPavimento => $pavimento_id) {
                        $ordemLinha = $attributes['pavimento_bloco_ordem'][$indexEstrutura][$indexPavimento];

                        if (isset($attributes['trecho'][$indexEstrutura][$indexPavimento])) {
                            foreach ($attributes['trecho'][$indexEstrutura][$indexPavimento] as $indexTrecho => $trecho_id) {
                                $ordem = $attributes['trecho_bloco_ordem'][$indexEstrutura][$indexPavimento][$indexTrecho];
                                $idBloco = null;
                                if(isset($attributes['trecho_id'][$indexEstrutura][$indexPavimento][$indexTrecho])){
                                    $idBloco = $attributes['trecho_id'][$indexEstrutura][$indexPavimento][$indexTrecho];
                                }
                                $attributes['blocos'][] =
                                    [
                                        'id' => $idBloco,
                                        'estrutura' => $estrutura_id,
                                        'pavimento' => $pavimento_id,
                                        'trecho' => $trecho_id,
                                        'ordem' => $ordem,
                                        'ordem_linha' => $ordemLinha,
                                        'ordem_bloco' => $ordemBloco
                                    ];
                            }
                        }
                    }
                }

            }

        }
        $model = parent::update($attributes, $id);


        return $model;
    }
}
