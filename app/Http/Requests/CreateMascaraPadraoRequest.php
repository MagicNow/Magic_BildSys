<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\MascaraPadrao;

class CreateMascaraPadraoRequest extends FormRequest
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
        return MascaraPadrao::$rules;
    }

    public function messages()
    {
        //return ['regional.required' => 'Escolha uma regional e clique em adicionar'];
    }
}
