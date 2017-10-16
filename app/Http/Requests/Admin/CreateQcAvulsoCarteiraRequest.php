<?php

namespace App\Http\Requests\Admin;

use App\Models\QcAvulsoCarteira;
use Illuminate\Foundation\Http\FormRequest;

class CreateQcAvulsoCarteiraRequest extends FormRequest
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
        return QcAvulsoCarteira::$rules;
    }

    public function messages()
    {
        return ['users.required'=>'É necessário escolher no mínimo um comprador para esta carteira'];
    }
}
