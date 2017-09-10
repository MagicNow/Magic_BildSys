<?php

namespace App\Http\Requests\Admin;

use App\Models\Fornecedor;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class UpdateUserRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user_id = collect( request()->segments() )->last();
        $rules = User::$rules;

        $fornecedor_excessao = '';
        $fornecedor_amarrado = Fornecedor::where('user_id', $user_id)->first();
        if($fornecedor_amarrado){
            $fornecedor_excessao= ', '.$fornecedor_amarrado->id;
        }

        $rules['email'] = 'required|unique:users,email,'. $user_id.'|unique:fornecedores,email'.$fornecedor_excessao;
        unset($rules['password']);
        return $rules;
    }

    public function messages()
    {
        return ['email.unique'=>'Já existe um usuário ou fornecedor com este e-mail.'];
    }
}
