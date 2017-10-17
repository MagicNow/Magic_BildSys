<?php

namespace App\Repositories;

use Exception;
use App\Models\Qc;
use App\Models\QcStatus;
use App\Models\QcAvulsoStatusLog;
use Illuminate\Support\Facades\DB;
use InfyOm\Generator\Common\BaseRepository;
use App\Models\WorkflowAlcada;
use App\Models\WorkflowTipo;
use Carbon\Carbon;

class QcRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Qc::class;
    }

    public function create(array $attributes)
    {
        $attributes['valor_pre_orcamento'] =  money_to_float($attributes['valor_pre_orcamento']);
        $attributes['valor_orcamento_inicial'] = money_to_float($attributes['valor_orcamento_inicial']);
        $attributes['valor_gerencial'] = money_to_float($attributes{'valor_gerencial'});
        $attributes['qc_status_id'] = QcStatus::EM_APROVACAO;
        $attributes['user_id'] = auth()->id();

        DB::beginTransaction();
        try {
            $qc = parent::create($attributes);

            QcAvulsoStatusLog::create([
                'user_id' => auth()->id(),
                'qc_status_id' => QcStatus::EM_APROVACAO,
                'qc_id' => $qc->id,
            ]);

            $anexos = $this->saveAttachments($attributes, $qc);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return $qc;
    }

    public function update(array $attributes, $id)
    {
        $qc = $this->find($id);

        $attributes['valor_fechamento'] = money_to_float(
            $attributes['valor_fechamento']
        );

        DB::beginTransaction();

        try {
            $qc->update($attributes, ['timestamps' => false]);
            $anexos = $this->saveAttachments($attributes, $qc);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return $qc;
    }

    public function fechar($id, $attr)
    {
        $qc = $this->find($id);

        DB::beginTransaction();
        try {
            $qc->update([
                'qc_status_id' => QcStatus::CONCORRENCIA_FINALIZADA,
                'fornecedor_id' => $attr['fornecedor_id'],
                'numero_contrato_mega' => $attr['numero_contrato_mega'],
                'valor_fechamento' => money_to_float($attr['valor_fechamento']),
            ]);

            QcAvulsoStatusLog::create([
                'user_id' => auth()->id(),
                'qc_status_id' => QcStatus::CONCORRENCIA_FINALIZADA,
                'qc_id' => $qc->id,
            ]);

            $this->saveAttachments($attr, $qc);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return $qc;
    }

    public function saveAttachments($attachments, $qc)
    {
        $qcAnexoRepository = app(QcAnexoRepository::class);

        return collect(array_get($attachments, 'anexo_arquivo', []))
            ->map(function ($file, $key) use ($attachments) {
                return [
                    'file' => $file,
                    'tipo' => $attachments['anexo_tipo'][$key],
                    'descricao' => $attachments['anexo_descricao'][$key],
                ];
            })
            ->map(function ($anexo) use ($qcAnexoRepository, $qc) {
                $destinationPath = $anexo['file']->store('qc_anexos/' . date('Y') . '/' . date('m') . '/' . $qc->id, 'public');

                $attach = $qcAnexoRepository->create([
                    'qc_id' => $qc->id,
                    'arquivo' => $destinationPath,
                    'tipo' => $anexo['tipo'],
                    'descricao' => $anexo['descricao'],
                ]);

                $qc->anexos()->save($attach);

                return $attach;
            });
    }

    public function cancelar($id)
    {
        $quadroDeConcorrencia = $this->findWithoutFail($id);
        $acao_executada = false;
        $mensagens = [];

        DB::beginTransaction();

        try {
            // Altera o status do Q.C.
            $quadroDeConcorrencia->qc_status_id = QcStatus::CANCELADO;
            $quadroDeConcorrencia->save();

            QcAvulsoStatusLog::create([
                'user_id' => auth()->id(),
                'qc_status_id' => QcStatus::CANCELADO,
                'qc_id' => $id,
            ]);

        } catch(Exception $e) {
            DB::rollback();
            return [false, 'Não foi possível realizar a operação!'];
        }

        DB::commit();

        return [true, $mensagens];
    }

    public function timeline($id)
    {
        $timeline = [];
        $qc = $this->find($id);

        if(!$qc->obra_id) {
            return false;
        }

        $tarefa = $qc->carteira->tarefas->where('obra_id', $qc->obra_id)->first();

        $alcadas = with(new WorkflowAlcada)
            ->where('workflow_tipo_id', WorkflowTipo::QC_AVULSO)
            ->get();

        $slaWorkflow = $alcadas->sum('dias_prazo');

        $workflowIsFinished = $qc->isStatus(
            QcStatus::EM_CONCORRENCIA,
            QcStatus::CONCORRENCIA_FINALIZADA
        );

        $negociacaoIsFinished = $qc->isStatus(QcStatus::CONCORRENCIA_FINALIZADA);

        $inicio = $tarefa->data
            ->copy()
            ->subDays($qc->carteira->sla_start)
            ->subDays($slaWorkflow)
            ->subDays($qc->carteira->sla_negociacao)
            ->subDays($qc->carteira->sla_mobilizacao);

        $timeline['sla_total'] = array_sum([
            $qc->carteira->sla_start,
            $slaWorkflow,
            $qc->carteira->sla_negociacao,
            $qc->carteira->sla_mobilizacao,
        ]);

        $timeline['end_date'] = $tarefa->data->copy();

        $startEndDate = $inicio->copy()->addDays($qc->carteira->sla_start);

        $timeline['start'] = [
            'name' => 'Start',
            'start_date' => $inicio->copy(),
            'end_date' => $startEndDate,
            'is_finished' => true,
            'is_started' => true,
            'finished_date' => $qc->created_at->copy(),
            'finished_by' => $qc->user,
            'was_finished_late' => $qc->created_at->gt($startEndDate),
            'is_late' => (new Carbon)->gt($startEndDate),
        ];

        $workflowEndDate = $timeline['start']['end_date']
            ->copy()
            ->addDays($slaWorkflow);

        $timeline['workflow'] = [
            'name' => 'Workflow',
            'start_date' => $timeline['start']['end_date']->copy(),
            'started_date' => $qc->created_at->copy(),
            'is_started' => true,
            'is_finished' => false,
            'end_date' => $workflowEndDate,
            'is_late' => (new Carbon)->gt($workflowEndDate)
        ];

        $timeline['negociacao'] = [
            'name' => 'Negociação',
            'is_finished' => false,
            'is_started' => false,
            'start_date' => $timeline['workflow']['end_date']->copy(),
            'end_date' => $timeline['workflow']['end_date']
                ->copy()
                ->addDays($qc->carteira->sla_negociacao),
        ];

        if($workflowIsFinished) {
            $finishEvent = with(new QcAvulsoStatusLog)
                ->where('qc_id', $id)
                ->where('qc_status_id', QcStatus::EM_CONCORRENCIA)
                ->orderBy('created_at', 'desc')
                ->first();

            $timeline['workflow']['is_finished'] = true;
            $timeline['workflow']['finished_date'] = $finishEvent->created_at->copy();
            $timeline['workflow']['finished_by'] = $finishEvent->user;
            $timeline['workflow']['was_finished_late'] = $finishEvent->created_at->gt($workflowEndDate);
            $timeline['negociacao']['is_started'] = true;
            $timeline['negociacao']['started_date'] = $finishEvent->created_at->copy();
        }

        $timeline['mobilizacao'] = [
            'name' => 'Mobilização',
            'is_finished' => false,
            'is_started' => false,
            'start_date' => $timeline['negociacao']['end_date']->copy(),
            'end_date' => $timeline['negociacao']['end_date']
                ->copy()
                ->addDays($qc->carteira->sla_mobilizacao),
        ];

        if($negociacaoIsFinished) {
            $finishEvent = with(new QcAvulsoStatusLog)
                ->where('qc_id', $id)
                ->where('qc_status_id', QcStatus::CONCORRENCIA_FINALIZADA)
                ->orderBy('created_at', 'desc')
                ->first();

            $timeline['negociacao']['is_finished'] = true;
            $timeline['negociacao']['finished_date'] = $finishEvent->created_at->copy();
            $timeline['negociacao']['finished_by'] = $finishEvent->user;
            $timeline['negociacao']['was_finished_late'] = $finishEvent->created_at->gt($workflowEndDate);
            $timeline['mobilizacao']['is_started'] = true;
            $timeline['mobilizacao']['started_date'] = $finishEvent->created_at->copy();
        }

        $timeline['workflow']['timeline'] = $alcadas->map(function($alcada) use ($qc, $timeline) {
            $isReproved = $alcada
                ->workflowAprovacoes()
                ->where('created_at', '>=', $qc->updated_at->toDateTimeString())
                ->where('aprovado', 0)
                ->exists();

            $isApproved = $alcada
                ->workflowAprovacoes()
                ->where('created_at', '>=', $qc->updated_at->toDateTimeString())
                ->where('aprovado', 1)
                ->count() === $alcada->users()->count();

            return [
                'name' => $alcada->nome,
                'end_date' => $timeline['workflow']['start_date']->addDays($alcada->dias_prazo),
                'is_finished' => $isReproved || $isApproved,
                'is_approved' => $isApproved,
                'approvers' => $alcada->users->map(function($user) use ($alcada, $qc) {
                    return [
                        'user' => $user,
                        'worked' => !!$alcada
                            ->workflowAprovacoes()
                            ->where('created_at', '>=', $qc->updated_at->toDateTimeString())
                            ->where('user_id', $user->id)
                            ->count(),
                        'approved' => !!$alcada
                            ->workflowAprovacoes()
                            ->where('created_at', '>=', $qc->created_at->toDateTimeString())
                            ->where('user_id', $user->id)
                            ->where('aprovado', 1)
                            ->count(),
                    ];
                })->toArray()
            ];
        })->toArray();

        $timeline['current'] = collect($timeline)
            ->filter(function($step) {
                return is_array($step) && !$step['is_finished'];
            })
            ->first()['name'];

        return $timeline;
    }
}
