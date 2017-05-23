<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CodeRepository
{
    public static function filter($query, $filters, $filters_find = [])
    {
        if($filters){
            foreach ($filters as $field => $value){
                $explode = explode('-', $field);

                if(@isset($explode[0])){
                    $sql = $query->toSql();
                    $partial_sql = str_replace('select * from `', '', $sql);
                    $table = strstr($partial_sql, '`', true);

                    if($explode[0] == 'periodo'){
                        if($filters['periodo'] != ''){
                            if($filters['periodo'] == 'hoje'){
                                $query->where(DB::raw('DATE_FORMAT('.$table.'.created_at, "%Y-%m-%d")'), '=', date("Y-m-d"));
                            }else{
                                $until_date = date("Y-m-d",strtotime("-".$filters['periodo']."Day"));

                                $query->where(DB::raw('DATE_FORMAT('.$table.'.created_at, "%Y-%m-%d")'), '<=', date("Y-m-d"))
                                    ->where(DB::raw('DATE_FORMAT('.$table.'.created_at, "%Y-%m-%d")'), '>=', $until_date);
                            }
                        }
                    }

                    if($explode[0] == 'procurar'){
                        if($filters['procurar'] != '') {
                            if (count($filters_find)) {
                                $query->where(function ($q) use ($filters_find, $filters){
                                    foreach ($filters_find as $find) {
                                        $q->orWhere($find, 'LIKE', '%' . $filters['procurar'] . '%');
                                    }
                                });
                            }
                        }
                    }
                }
                if(@isset($explode[0]) && @isset($explode[1])) {
                    $filter = self::translateFields($explode[0]);
                    if ($explode[1] == 'integer') {
                        if (array_key_exists($field . '_option', $filters)) {
                            if ($filters[$field . '_option'] == 'between') {
                                if (array_key_exists($field . '_final', $filters)) {
                                    $query->where(function ($q) use ($explode, $value, $filters, $field, $filter) {
                                        $q->where($filter, '>=', $value);
                                        $q->where($filter, '<=', $filters[$field . '_final']);
                                    });
                                }
                            } else if ($filters[$field . '_option'] == 'bigger') {
                                $query->where($filter, '>', $value);
                            } else if ($filters[$field . '_option'] == 'smaller') {
                                $query->where($filter, '<', $value);
                            } else if ($filters[$field . '_option'] == 'bigger_equal') {
                                $query->where($filter, '>=', $value);
                            } else if ($filters[$field . '_option'] == 'smaller_equal') {
                                $query->where($filter, '<=', $value);
                            } else if ($filters[$field . '_option'] == 'equal') {
                                $query->where($filter, $value);
                            }
                        }
                    } else if ($explode[1] == 'boolean') {
                        $query->where($filter, $value);
                    } else if ($explode[1] == 'foreign_key') {
                        $query->where($filter, $value);
                    } else if ($explode[1] == 'date_initial') {
                        $query->where(DB::raw('DATE(' . $filter . ')'), '>=', $value);
                    } else if ($explode[1] == 'date_final') {
                        $query->where(DB::raw('DATE(' . $filter . ')'), '<=', $value);
                    } else if ($explode[1] == 'string') {
                        if (array_key_exists($field . '_option', $filters)) {
                            if ($filters[$field . '_option'] == 'between') {
                                $query->where($filter, 'like', '%' . $value . '%');
                            } else if ($filters[$field . '_option'] == 'start') {
                                $query->where($filter, 'like', $value . '%');
                            } else if ($filters[$field . '_option'] == 'end') {
                                $query->where($filter, 'like', '%' . $value);
                            }
                        }
                    }
                }

            }
        }

        return $query;
    }

    private static function translateFields($field){
        $fields = [
            'obra' => 'obras.id',
            'ordem_compra_created_at' => 'ordem_de_compras.created_at',
            'ordem_compra_updated_at' => 'ordem_de_compras.updated_at',
            'insumo_servico_servico_id' => 'insumo_servico.servico_id'
        ];

        if(@isset($fields[$field])){
            return $fields[$field];
        }else{
            return $field;
        }
    }

    public static function saveFile($file, $path)
    {
        try {
            $path = str_replace('public/', '', $path);

            if($path[0] == '/'){
                $path = substr($path, 1);
            }

            $arquivo = $file->storeAs(
                'public/'.$path, str_slug(str_replace('.'.$file->clientExtension(), '', $file->getClientOriginalName()).'_'.rand(100,10000)).'.'.$file->clientExtension()
            );
            
            return $arquivo;
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }
}