<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Grupo;

class QcInformarValorRequest extends FormRequest
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
            'fornecedor_id'          => 'required|numeric',
            'equalizacoes.*.checked' => 'required_unless:reject,1|numeric',
            'itens.*.valor_unitario' => 'money',
            'pagamento_condicao_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'fornecedor_id.required'                 => 'Selecione um fornecedor',
            'equalizacoes.*.checked.required_unless' => 'Cheque todos os itens de equalização tecnica',
            'itens.*.valor_unitario.required_unless' => 'Adicione o valor de todos os itens',
            'itens.*.valor_unitario.money'           => 'Adiciones valores válidos nos itens',
            'pagamento_condicao_id.required'         => 'Selecione uma condição de pagamento',
        ];
    }
}
