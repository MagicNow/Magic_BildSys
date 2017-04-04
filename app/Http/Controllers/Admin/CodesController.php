<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;

class CodesController extends AppBaseController
{
    public function putSession(Request $request){

        if($request->filters){
            session()->put('filters', $request->filters);
            foreach ($request->filters as $key => $value) {
                session()->put($key, $value);
            }
        }

        session()->put('initial_date', $request->initial_date);
        session()->put('final_date', $request->final_date);
        
        return response()->json(['success' => true]);
    }

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

    public function checkSession(){

        $filters = session()->get('filters');
        $success = false;
        
        if($filters){
            $success = true;
        }
        return response()->json(['success' => $success, 'filters' => $filters]);
    }
}

