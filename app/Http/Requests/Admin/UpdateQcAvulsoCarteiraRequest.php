<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\QcAvulsoCarteira;

class UpdateQcAvulsoCarteiraRequest extends FormRequest
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
        $rules = QcAvulsoCarteira::$rules;

        $rules['nome'] = 'required|unique:qc_avulso_carteiras,nome,'.collect( request()->segments() )->last();
        unset($rules['users']);
        return $rules;
    }

    public function messages()
    {
        return ['users.required'=>'É necessário escolher no mínimo um comprador para esta carteira'];
    }
}
