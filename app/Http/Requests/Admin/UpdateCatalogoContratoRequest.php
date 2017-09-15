<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CatalogoContrato;

class UpdateCatalogoContratoRequest extends FormRequest
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
        $rules = CatalogoContrato::$rules;
        unset($rules['fornecedor_cod']);
        $rules['reajuste.*.periodo_termino'] = 'required|after_or_equal:reajuste.*.periodo_inicio';
        $rules['contratoInsumos.*.periodo_termino'] = 'required|after_or_equal:contratoInsumos.*.periodo_inicio';
        return $rules;
    }

    public function messages()
    {
        return ['regional.required' => 'Escolha uma regional e clique em adicionar',
            'reajuste.*.periodo_termino.after_or_equal'=>'O período de término passado precisa ser maior ou igual ao período de início',
            'contratoInsumos.*.periodo_termino.after_or_equal'=>'O período de término passado precisa ser maior ou igual ao período de início',
        ];
    }
}
