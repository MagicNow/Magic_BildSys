<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\Cidade;
use App\Models\TipoOrcamento;
use Illuminate\Http\Request;
use Flash;

class CodesController extends AppBaseController
{
    public function getForeignKey(Request $request){
        $model = 'App\Models\\'.$request->model;
        $field_value = $request->field_value;
        $field_key = $request->field_key;
        $foreign_key = null;

        if($model && $field_value && $field_key){
            $foreign_key =  $model::pluck($field_value, $field_key)->toArray();

            if(count($foreign_key)){
                $success = true;
            }else{
                $success = false;
            }
        }else{
            $success = false;
            Flash::success('Model não configurado.');
        }

        return response()->json(['success' => $success, 'foreign_key' => $foreign_key]);
    }

    public function buscaCidade(Request $request){
        $cidades = Cidade::select([
            'id',
            'nome',
            'uf'
        ])
            ->where('nome','like', '%'.$request->q.'%');
        return $cidades->paginate();
    }
	
	public function buscaTipoOrcamento(Request $request){
        $tipo_orcamentos = TipoOrcamento::select([
            'id',
            'nome'
        ])
            ->where('nome','like', '%'.$request->q.'%');
        return $tipo_orcamentos->paginate();
    }
}

