<?php

namespace App\Repositories\Admin;

use Flash;
use App\Models\WorkflowAlcada;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Http\Exception\HttpResponseException;

class WorkflowAlcadaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'workflow_tipo_id',
        'nome',
        'ordem'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return WorkflowAlcada::class;
    }

    public function validateBusinessLogic($input, $id = null)
    {
        if(!empty(@$input['valor_minimo'])) {
            $input['valor_minimo'] = money_to_float($input['valor_minimo']);
        }

        $alcadas = $this->model
            ->where('workflow_tipo_id', $input['workflow_tipo_id'])
            ->where('id', '!=', $id)
            ->get();

        if($alcadas->count()) {
            $alcada_anterior = $alcadas->where('ordem', '<', intval($input['ordem']))
                ->first();

            if(!$alcada_anterior) {
                Flash::error('Ordem inválida, não há alçadas anteriores.');

                throw new HttpResponseException(back()->withInput());
            }

            $alcada_igual = $alcadas->where('valor_minimo', $input['valor_minimo'])->first();

            if($alcada_igual) {
                Flash::error('Já existe uma alçada com o valor mínimo informado');

                throw new HttpResponseException(back()->withInput());
            }

            $alcada_anterior_valor_maior = $alcadas->where('ordem', '<', $input['ordem'])
                ->where('valor_minimo', '>', $input['valor_minimo'])
                ->first();

            if($alcada_anterior_valor_maior) {
                Flash::error(
                    'Existe uma alçada anterior com um valor maior que o utilizado'
                );

                throw new HttpResponseException(back()->withInput());
            }

        } else {
            $input['ordem'] = 1;

            if($input['valor_minimo'] > 0) {
                Flash::error(
                    'É necessário que a primeira alçada tenha valor mínimo de R$0,00'
                );

                throw new HttpResponseException(back()->withInput());
            }
        }
    }
}
