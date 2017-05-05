<?php
/**
 * Created by PhpStorm.
 * User: Raul
 * Date: 02/05/2017
 * Time: 12:32
 */

namespace App\Repositories\Admin;


use App\Models\Fornecedor;

class ValidationRepository
{
    public static function validaCnpj($valor, $cpf){
        $validar = [
            1 => 'cnpj'
        ];
        $messages = [
            'cnpj' => 'CNPJ inválido.',
        ];
        if(!isset($validar[$cpf])){
            return \Validator::make(['numero'=>$valor], ['numero'=>'required']);
        }

        $rules = array(
            $validar[$cpf] => $validar[$cpf].'|required'
        );

        $data = array(
            $validar[$cpf] => $valor
        );
        return \Validator::make($data, $rules, $messages);
    }

    public static function CnpjUnico($valor){
        if($valor) {
            $existeCnpj = null;
            $cadastrado = Fornecedor::where('cnpj', $valor)
                ->first();

            if($cadastrado) {
                return true;
            }
            return false;
        }
    }

}