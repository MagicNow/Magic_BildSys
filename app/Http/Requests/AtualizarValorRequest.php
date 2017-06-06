<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AtualizarValorRequest extends FormRequest
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
            'fornecedor_id'   => 'required',
            'obra_id'   => 'required',
            'valor_unitario' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'obra_id.required'   => 'É necessário uma obra',
            'fornecedor_id.required'   => 'É necessário um fornecedor',
            'valor_unitario.required' => 'É necessário pelo menos um valor à ser ajustado'
        ];
    }
}
