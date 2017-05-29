<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ReapropriarRequest extends FormRequest
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
            'qtd'          => 'required',
            'grupo_id'     => 'required',
            'subgrupo1_id' => 'required',
            'subgrupo2_id' => 'required',
            'subgrupo3_id' => 'required',
            'servico_id'   => 'required',
        ];
    }

    public function messages()
    {
        return [
            'qtd.required' => 'A quantidade para reapropriação é obrigatória',
            '*.required' => 'Por favor, selecione todos os grupos da reaproriação',
        ];
    }
}
