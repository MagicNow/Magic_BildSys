<?php

namespace App\Repositories\Admin;

use Flash;
use App\Models\WorkflowAlcada;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Http\Exception\HttpResponseException;
use App\Models\WorkflowTipo;

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
        $workflowTipo_id = null;
        if($id) {
            $alcada = $this->model->find($id);
            $workflowTipo_id = $alcada->workflow_tipo_id;
        }else{
            $workflowTipo_id = $input['workflow_tipo_id'];
        }

        $alcadas = $this->model
            ->where('workflow_tipo_id', $workflowTipo_id)
            ->where('id', '!=', $id)
            ->get();

        $workflowTipo = WorkflowTipo::find($workflowTipo_id);

        if(!empty(@$input['valor_minimo'])) {
            $input['valor_minimo'] = money_to_float($input['valor_minimo']);
        }

        if($alcadas->count()) {
            if(!isset($alcada) || isset($alcada) && $alcada->ordem != $input['ordem']) {
                $alcada_anterior = $alcadas->where('ordem', '<=', intval($input['ordem']))
                    ->where('workflow_tipo_id',$workflowTipo_id)
                    ->first();
                if(!$alcada_anterior) {
                    Flash::error('Ordem inválida, não há alçadas anteriores.');

                    throw new HttpResponseException(back()->withInput());
                }
            }

            if($workflowTipo->usa_valor_minimo) {
                $alcada_igual = $alcadas->where('valor_minimo', $input['valor_minimo'])->first();

                if($alcada_igual && $input['valor_minimo']) {
                    Flash::error('Já existe uma alçada com o valor mínimo informado');

                    throw new HttpResponseException(back()->withInput());
                }

                $alcada_anterior_valor_maior = $alcadas->where('ordem', '<', $input['ordem'])
                    ->where('valor_minimo', '>', $input['valor_minimo'])
                    ->first();

                if($alcada_anterior_valor_maior && $input['valor_minimo']) {
                    Flash::error(
                        'Existe uma alçada anterior com um valor maior que o utilizado'
                    );

                    throw new HttpResponseException(back()->withInput());
                }
            }

        } else {
            if(isset($alcada)){
                if($alcada->ordem > 1 || $input['ordem'] > 1){
                    Flash::error('Ordem inválida, não existem alçadas em ordenação anterior.');

                    throw new HttpResponseException(back()->withInput());
                }
            }else{
                if($input['ordem'] > 1){
                    Flash::error('Ordem inválida, não existem alçadas em ordenação anterior.');

                    throw new HttpResponseException(back()->withInput());
                }
            }
            $input['ordem'] = 1;

            if($workflowTipo->usa_valor_minimo && $input['valor_minimo'] > 0) {
                Flash::error(
                    'É necessário que a primeira alçada tenha valor mínimo de R$0,00'
                );

                throw new HttpResponseException(back()->withInput());
            }
        }
    }
}
