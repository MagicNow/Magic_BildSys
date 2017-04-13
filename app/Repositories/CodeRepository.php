<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CodeRepository
{
    public static function filter($query, $filters)
    {
        if($filters){
            foreach ($filters as $field => $value){
                $explode = explode('-', $field);
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
            'ordem_compra_updated_at' => 'ordem_de_compras.updated_at'
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
            $filename = $file->getClientOriginalName();

            $destinationPath = public_path() . '/appfiles/' . $path;
            $file->move($destinationPath, $filename);

            return '/appfiles/' . $path . '/' . $filename;
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }
}