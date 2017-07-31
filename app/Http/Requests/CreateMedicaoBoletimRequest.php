<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\MedicaoBoletim;

class CreateMedicaoBoletimRequest extends FormRequest
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
        return MedicaoBoletim::$rules;
    }

    public function messages()
    {
        return ['medicaoServicos.required' => 'Selecione uma ou mais medições.'];
    }
}
