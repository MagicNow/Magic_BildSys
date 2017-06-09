<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PerfilSaveRequest extends FormRequest
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

    public function rules()
    {
        return  [
            'email' => [
                'required', Rule::unique('users')->ignore(auth()->id())
            ],
            'name' => [
                'required'
            ],
            'current_password' => [
                'required_with:password'
            ],
            'password' => [
                'confirmed'
            ],
            'password_confirmation' => [
                'required_with:password'
            ]
        ];
    }

    public function messages()
    {
        return [
            'email.required'                      => 'Por favor insira seu email no formulário',
            'email.unique'                        => 'Já existe um usuário utilizando este email',
            'name.required'                       => 'Por favor insira o seu nome no formulário',
            'current_password.required_with'      => 'Por favor insira a senha atual no formulário para que a troca de senha aconteça',
            'password.confirmed'                  => 'A nova senha e sua confirmação não batem',
            'password_confirmation.required_with' => 'Por favor insira a senha novamente no campo "CONFIRME NOVA SENHA"'
        ];
    }
}
