<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CatalogoContrato;

class CreateCatalogoContratoRequest extends FormRequest
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
        return CatalogoContrato::$rules;
    }

    public function messages()
    {
        return ['obra.required' => 'Escolha uma obra e clique em adicionar'];
    }
}
