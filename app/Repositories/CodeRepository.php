<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CodeRepository
{
    public static function filter($query){
        $initial_date = session()->get('initial_date');
        $final_date = session()->get('final_date');

        if($initial_date){
            $query->where(DB::raw('DATE(created_at)'), '>=', $initial_date);
        }

        if($final_date){
            $query->where(DB::raw('DATE(created_at)'), '<=', $final_date);
        }

        $filters = session()->get('filters');
        
        if($filters){
            foreach ($filters as $field => $value){
                $explode = explode('|', $field);
                
                if($explode[1] == 'integer') {
                    if(array_key_exists($field.'_option', $filters)){
                        if($filters[$field.'_option'] == 'between'){
                            if(array_key_exists($field.'_final', $filters)){
                                $query->where(function ($q) use($explode, $value, $filters, $field) {
                                    $q->where($explode[0], '>=' ,$value);
                                    $q->where($explode[0], '<=' ,$filters[$field.'_final']);
                                });
                            }
                        }else if($filters[$field.'_option'] == 'bigger'){
                            $query->where($explode[0], '>' , $value);
                        }else if($filters[$field.'_option'] == 'smaller'){
                            $query->where($explode[0], '<', $value);
                        }else if($filters[$field.'_option'] == 'bigger_equal'){
                            $query->where($explode[0], '>=', $value);
                        }else if($filters[$field.'_option'] == 'smaller_equal'){
                            $query->where($explode[0], '<=', $value);
                        }else if($filters[$field.'_option'] == 'equal'){
                            $query->where($explode[0], $value);
                        }
                    }
                }else if($explode[1] == 'boolean'){
                    $query->where($explode[0], $value);
                }else if($explode[1] == 'foreign_key'){

                }else if($explode[1] == 'date_initial'){
                    $query->where(DB::raw('DATE('.$explode[0].')'), '>=', $value);
                }else if($explode[1] == 'date_final'){
                    $query->where(DB::raw('DATE('.$explode[0].')'), '<=', $value);
                }else if($explode[1] == 'string'){
                    if(array_key_exists($field.'_option', $filters)){
                        if($filters[$field.'_option'] == 'between'){
                            $query->where($explode[0], 'like', '%' . $value . '%');
                        }else if($filters[$field.'_option'] == 'start'){
                            $query->where($explode[0], 'like', $value . '%');
                        }else if($filters[$field.'_option'] == 'end'){
                            $query->where($explode[0], 'like', '%' . $value);
                        }
                    }
                }
            }
        }
    }
}