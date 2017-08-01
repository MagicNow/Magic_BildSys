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

        foreach ($attributes['trecho'] as $estruturaT => $pavimentos){
            $estruturaID = $attributes['estrutura_bloco'][$estruturaT];
            $ordemBloco = $attributes['estrutura_bloco_ordem'][$estruturaT];
            foreach($pavimentos as $pavimentoT => $trechos){
                $pavimentoID = $attributes['pavimentos'][$estruturaT][$pavimentoT];
                $ordemLinha = $attributes['pavimento_bloco_ordem'][$estruturaT][$pavimentoT];
                foreach($trechos as $indexTrecho => $trecho){
                    $ordem = $attributes['trecho_bloco_ordem'][$estruturaT][$pavimentoT][$indexTrecho];
                    $attributes['blocos'][] =
                        [
                            'estrutura' => $estruturaID,
                            'pavimento' => $pavimentoID,
                            'trecho' => $trecho,
                            'ordem' => $ordem,
                            'ordem_linha' => $ordemLinha,
                            'ordem_bloco' => $ordemBloco
                        ];
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

        foreach ($attributes['trecho'] as $estruturaT => $pavimentos){
            $estruturaID = $attributes['estrutura_bloco'][$estruturaT];
            $ordemBloco = $attributes['estrutura_bloco_ordem'][$estruturaT];
            foreach($pavimentos as $pavimentoT => $trechos){
                $pavimentoID = $attributes['pavimentos'][$estruturaT][$pavimentoT];
                $ordemLinha = $attributes['pavimento_bloco_ordem'][$estruturaT][$pavimentoT];
                foreach($trechos as $indexTrecho => $trecho){
                    $ordem = $attributes['trecho_bloco_ordem'][$estruturaT][$pavimentoT][$indexTrecho];

                    $idBloco = null;
                    $bloco = [
                        'estrutura' => $estruturaID,
                        'pavimento' => $pavimentoID,
                        'trecho' => $trecho,
                        'ordem' => $ordem,
                        'ordem_linha' => $ordemLinha,
                        'ordem_bloco' => $ordemBloco
                    ];
                    if(isset($attributes['trecho_id'][$estruturaT][$pavimentoT][$indexTrecho])){
                        $bloco['id'] = $attributes['trecho_id'][$estruturaT][$pavimentoT][$indexTrecho];
                    }
                    $attributes['blocos'][] = $bloco;
                }
            }
        }

        $model = parent::update($attributes, $id);

        return $model;
    }
}
