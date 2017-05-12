<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class QcAvaliarRequest extends FormRequest
{

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
        return [
            'fornecedores.*' => 'required_if:gerar_nova_rodada,1',
            'vencedores.*' => 'required_unless:gerar_nova_rodada,1'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        Flash::error('Por favor verifique os dados enviados e tente novamente!');

        throw new ValidationException($validator, $this->response([]));
    }
}
