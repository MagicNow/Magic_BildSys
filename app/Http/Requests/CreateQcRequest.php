<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Qc;

class CreateQcRequest extends FormRequest
{
    public $dontFlash = [
        'anexo_descricao',
        'anexo_tipo',
        'anexo_arquivo',
    ];
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
    public function rules(Request $request)
    {
        return Qc::$rules;
    }

    public function messages()
    {
        return Qc::$messages;
    }
}
