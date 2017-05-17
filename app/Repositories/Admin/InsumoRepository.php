<?php

namespace App\Repositories\Admin;

use App\Models\Insumo;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Http\Exception\HttpResponseException;

class InsumoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome',
        'unidade_sigla',
        'codigo',
        'insumo_grupo_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Insumo::class;
    }

    public function enable($id)
    {
        $insumo = $this->find($id);

        if(!$insumo->grupo->active) {
            throw new HttpResponseException(response()->json([
                'error' => "Insumo \"{$insumo->nome}\" não pode ser alterado pois seu grupo ({$insumo->grupo->nome}) está indisponível para uso.",
                'link_option' => route('admin.insumoGrupos.index'),
                'type' => 'warning'
            ], 422));
        }

        $insumo->update(['active' => true]);

        return $insumo;
    }

    public function disable($id)
    {
        $insumo = $this->find($id);

        return $this->update(['active' => false], $id);
    }
}
