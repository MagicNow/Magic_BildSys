<?php

namespace App\Repositories\Admin;

use App\Models\TipoEqualizacaoTecnica;
use InfyOm\Generator\Common\BaseRepository;

class TipoEqualizacaoTecnicaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TipoEqualizacaoTecnica::class;
    }

    public function create(array $attributes)
    {
        if(isset($attributes['idiomas'])){
            foreach ($attributes['idiomas'] as $index => $idioma){
                $attributes['idiomas'][$index]['slug'] = str_slug($idioma['nome']);
                $attributes['idiomas'][$index]['user_id'] = $attributes['user_id'];
            }
        }

        if(isset($attributes['arquivos'])){
            foreach ($attributes['arquivos'] as $index => $arquivo){
                $attributes['arquivos'][$index]['arquivo'] = $arquivo['arquivo']->store('public/produtos/arquivos');
            }
        }

        if(isset($attributes['imagens'])){
            foreach ($attributes['imagens'] as $index => $imagem){
                $attributes['imagens'][$index]['imagem'] = $imagem['imagem']->store('public/produtos/imagens');
            }
        }

        if(!strlen($attributes['referencia'])){
            $attributes['referencia'] = str_slug($attributes['nome']);
        }

        $model = parent::create($attributes);

        return $model;
    }

    public function update(array $attributes, $id)
    {
        if(isset($attributes['idiomas'])){
            foreach ($attributes['idiomas'] as $index => $idioma){
                $attributes['idiomas'][$index]['slug'] = str_slug($idioma['nome']);
                $attributes['idiomas'][$index]['user_id'] = $attributes['user_id'];
            }
        }

        if(isset($attributes['arquivos'])){
            foreach ($attributes['arquivos'] as $index => $arquivo){
                if( !isset($arquivo['arquivo']) || (isset($arquivo['arquivo']) && is_null($arquivo['arquivo'])) ){
                    $attributes['arquivos'][$index]['arquivo'] = $arquivo['arquivo_atual'];
                }else{
                    if (isset($arquivo['arquivo_atual'])) {
                        Storage::delete($arquivo['arquivo_atual']);
                    }
                    $attributes['arquivos'][$index]['arquivo'] = $arquivo['arquivo']->store('public/produtos/arquivos');
                }
                if(isset($attributes['arquivos'][$index]['arquivo_atual'])){
                    unset($attributes['arquivos'][$index]['arquivo_atual']);
                }
            }
        }else{
            $attributes['arquivos'] = [];
        }

        if(isset($attributes['imagens'])){
            foreach ($attributes['imagens'] as $index => $imagem){
                if( !isset($imagem['imagem']) || (isset($imagem['imagem']) && is_null($imagem['imagem'])) ){
                    $attributes['imagens'][$index]['imagem'] = $imagem['imagem_atual'];
                }else{
                    if (isset($imagem['imagem_atual'])) {
                        Storage::delete($imagem['imagem_atual']);
                    }
                    $attributes['imagens'][$index]['imagem'] = $imagem['imagem']->store('public/produtos/imagens');
                }
                if(isset($attributes['imagens'][$index]['imagem_atual'])){
                    unset($attributes['imagens'][$index]['imagem_atual']);
                }
            }
        }else{
            $attributes['imagens'] = [];
        }

        if(isset($attributes['combinacoes'])){
            foreach ($attributes['combinacoes'] as $index => $combinacao){
                if( $combinacao['materiais_id_1'] == '' || $combinacao['materiais_id_2'] == '' ){
                    unset($attributes['combinacoes'][$index]);
                }
            }
        }else{
            $attributes['combinacoes'] = [];
        }

        $model = parent::update($attributes, $id);


        return $model;
    }

}
