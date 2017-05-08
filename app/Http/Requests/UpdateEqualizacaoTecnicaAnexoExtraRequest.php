<?php

namespace App\Http\Requests;

use App\Models\QcEqualizacaoTecnicaAnexoExtra;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEqualizacaoTecnicaAnexoExtraRequest extends FormRequest
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
        $rules = QcEqualizacaoTecnicaAnexoExtra::$rules;
        unset($rules['arquivo']);
        return $rules;
    }
}
