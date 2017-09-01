<?php

namespace App\Http\Requests\Admin;

use App\Models\Fornecedor;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFornecedoresRequest extends FormRequest
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
        $fornecedor = Fornecedor::find(collect( request()->segments() )->last());
        $user_id = '';
        if($fornecedor && $fornecedor->user_id){
            $user_id = ','.$fornecedor->user_id;
        }
        $rules = Fornecedor::$rules;
        $rules['email'] = 'required|email|unique:fornecedores,email,'. collect( request()->segments() )->last().'|unique:users,email'. $user_id ;
        return $rules;
    }

    public function messages()
    {
        return ['email.unique'=>'Este e-mail já está sendo utilizado em outro fornecedor ou em algum usuário do sistema'];
    }
}
