<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DistratarRequest extends FormRequest
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
            'distrato.*'   => 'required|money',
        ];
    }

    public function messages()
    {
        return [
            'distrato.*.required' => 'Não foi encontrado nenhuma modificação',
        ];
    }
}
