<?php

namespace App\Repositories\Admin;

use App\Models\TipoEqualizacaoTecnica;
use Illuminate\Support\Facades\Storage;
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
        if(isset($attributes['itens'])){
            foreach ($attributes['itens'] as $index => $item){
                $attributes['itens'][$index]['nome'] = $item['nome'];
                $attributes['itens'][$index]['descricao'] = $item['descricao'];
                $attributes['itens'][$index]['obrigatorio'] = isset($item['obrigatorio']) ? 1 : 0;
            }
        }

        if(isset($attributes['anexos'])){
            foreach ($attributes['anexos'] as $index => $anexo){
                $attributes['anexos'][$index]['nome'] = $anexo['nome'];
                $attributes['anexos'][$index]['arquivo'] = $anexo['arquivo']->store('public/anexos');
            }
        }

        $model = parent::create($attributes);

        return $model;
    }

    public function update(array $attributes, $id)
    {
        if(isset($attributes['itens'])){
            foreach ($attributes['itens'] as $index => $item){
                $attributes['itens'][$index]['obrigatorio'] = isset($item['obrigatorio']) ? 1 : 0;
            }
        }else{
            $attributes['itens'] = [];
        }

        if(isset($attributes['anexos'])){
            foreach ($attributes['anexos'] as $index => $anexo){
                if( !isset($anexo['arquivo']) || (isset($anexo['arquivo']) && is_null($anexo['arquivo'])) ){
                    $attributes['anexos'][$index]['arquivo'] = $anexo['arquivo_atual'];
                }else{
                    if (isset($anexo['arquivo_atual'])) {
                        Storage::delete($anexo['arquivo_atual']);
                    }
                    $attributes['anexos'][$index]['arquivo'] = $anexo['arquivo']->store('public/anexos');
                }
                if(isset($attributes['anexos'][$index]['arquivo_atual'])){
                    unset($attributes['anexos'][$index]['arquivo_atual']);
                }
            }
        }else{
            $attributes['anexos'] = [];
        }

        $model = parent::update($attributes, $id);


        return $model;
    }

}
