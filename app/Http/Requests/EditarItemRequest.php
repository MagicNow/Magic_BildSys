<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EditarItemRequest extends FormRequest
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
        return [
            'qtd'   => 'required|money',
            'valor' => 'required|money',
        ];
    }

    public function messages()
    {
        return [
            'qtd.required'   => 'A quantidade para reapropriação é obrigatória',
            'valor.required' => 'O valor é obrigatório',
            'qtd.money'      => 'Quantidade inválida',
            'valor.money'    => 'Valor inválido',
        ];
    }
}
