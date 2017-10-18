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
        $attributes['obra_id'] = $attributes['obra_id'] ?: null;

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

        $attributes['qc_status_id'] = QcStatus::EM_APROVACAO;
        $attributes['obra_id'] = $attributes['obra_id'] ?: null;

        DB::beginTransaction();

        try {
            $qc->update($attributes);

            QcAvulsoStatusLog::create([
                'user_id' => auth()->id(),
                'qc_status_id' => QcStatus::EM_APROVACAO,
                'qc_id' => $qc->id,
            ]);

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
                'comprador_id' => $attr['comprador_id'],
                'data_fechamento' => Carbon::today(),
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
        $round = function($number) {
            return floatval(substr_replace(round($number, 3), '', -1));
        };

        $timeline = [];
        $steps = [];
        $qc = $this->find($id);

        if(!$qc->obra_id || !$qc->cateira) {
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

        $workflowIsReproved = $qc->isStatus(QcStatus::REPROVADO);

        $timeline['is_reproved'] = $workflowIsReproved;

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

        $steps['start'] = [
            'name' => 'Start',
            'sla' => $qc->carteira->sla_start,
            'percent' => $round((100 * $qc->carteira->sla_start) / $timeline['sla_total']),
            'percent_alone' => $round((100 * $qc->carteira->sla_start) / $timeline['sla_total']),
            'start_date' => $inicio->copy(),
            'started_date' => $inicio->copy(),
            'end_date' => $startEndDate,
            'is_finished' => true,
            'is_started' => true,
            'adjusted_date' => null,
            'finished_date' => $qc->created_at->copy(),
            'finished_by' => $qc->user,
            'was_finished_late' => $qc->created_at->gt($startEndDate),
            'is_late' => (new Carbon)->gt($startEndDate),
        ];

        $workflowEndDate = $steps['start']['end_date']
            ->copy()
            ->addDays($slaWorkflow);

        $steps['workflow'] = [
            'name' => 'Workflow',
            'sla' => $qc->carteira->sla_start + $slaWorkflow,
            'percent' => $round((100 * $qc->carteira->sla_start + $slaWorkflow) / $timeline['sla_total']),
            'percent_alone' => $round((100 * $slaWorkflow) / $timeline['sla_total']),
            'start_date' => $steps['start']['end_date']->copy(),
            'started_date' => $qc->created_at->copy(),
            'is_started' => true,
            'is_finished' => false,
            'end_date' => $workflowEndDate,
            'is_late' => (new Carbon)->gt($workflowEndDate)
        ];

        $steps['negociacao'] = [
            'name' => 'Negociação',
            'is_finished' => false,
            'is_started' => false,
            'start_date' => $steps['workflow']['end_date']->copy(),
            'sla' => $qc->carteira->sla_start + $slaWorkflow + $qc->carteira->sla_negociacao,
            'percent' => $round((100 * $qc->carteira->sla_start + $slaWorkflow + $qc->carteira->sla_negociacao) / $timeline['sla_total']),
            'percent_alone' => $round((100 * $qc->carteira->sla_negociacao) / $timeline['sla_total']),
            'end_date' => $steps['workflow']['end_date']
                ->copy()
                ->addDays($qc->carteira->sla_negociacao),
        ];

        if($workflowIsFinished || $workflowIsReproved) {
            $finishEvent = with(new QcAvulsoStatusLog)
                ->where('qc_id', $id)
                ->where('qc_status_id', $workflowIsReproved ? QcStatus::REPROVADO : QcStatus::EM_CONCORRENCIA)
                ->orderBy('created_at', 'desc')
                ->first();

            $steps['workflow']['is_finished'] = true;
            $steps['workflow']['finished_date'] = $finishEvent->created_at->copy();
            $steps['workflow']['finished_by'] = $finishEvent->user;
            $steps['workflow']['was_finished_late'] = $finishEvent->created_at->gt($workflowEndDate);

            if($workflowIsFinished) {
                $steps['negociacao']['is_started'] = true;
                $steps['negociacao']['started_date'] = $finishEvent->created_at->copy();
            }
        }

        $steps['mobilizacao'] = [
            'name' => 'Mobilização',
            'is_finished' => false,
            'is_started' => false,
            'start_date' => $steps['negociacao']['end_date']->copy(),
            'sla' => $timeline['sla_total'],
            'percent' => 100,
            'percent_alone' => $round((100 * $qc->carteira->sla_mobilizacao) / $timeline['sla_total']),
            'end_date' => $steps['negociacao']['end_date']
                ->copy()
                ->addDays($qc->carteira->sla_mobilizacao),
        ];

        $steps['mobilizacao']['percent_alone'] = $steps['mobilizacao']['percent_alone'] + (100 - collect($steps)->pluck('percent_alone')->sum());

        if($negociacaoIsFinished) {
            $finishEvent = with(new QcAvulsoStatusLog)
                ->where('qc_id', $id)
                ->where('qc_status_id', QcStatus::CONCORRENCIA_FINALIZADA)
                ->orderBy('created_at', 'desc')
                ->first();

            $steps['negociacao']['is_finished'] = true;
            $steps['negociacao']['finished_date'] = $finishEvent->created_at->copy();
            $steps['negociacao']['finished_by'] = $finishEvent->user;
            $steps['negociacao']['was_finished_late'] = $finishEvent->created_at->gt($workflowEndDate);
            $steps['mobilizacao']['is_started'] = true;
            $steps['mobilizacao']['started_date'] = $finishEvent->created_at->copy();
        }

        $steps['workflow']['timeline'] = $alcadas->map(function($alcada) use ($qc, $steps, $workflowIsFinished) {
            $isReproved = $qc->isStatus(QcStatus::REPROVADO);
            $isApproved = $workflowIsFinished;

            return [
                'name' => $alcada->nome,
                'end_date' => $steps['workflow']['start_date']->addDays($alcada->dias_prazo),
                'finished_date' => $workflowIsFinished || $isReproved ? $steps['workflow']['finished_date']->copy() : null,
                'is_finished' => $isReproved || $isApproved,
                'is_approved' => $isApproved,
                'approvers' => $alcada->users->map(function($user) use ($alcada, $qc) {
                    return [
                        'user' => $user,
                        'worked' => !!$alcada
                            ->workflowAprovacoes()
                            ->where('created_at', '>=', $qc->updated_at->toDateTimeString())
                            ->where('aprovavel_id', $qc->id)
                            ->where('user_id', $user->id)
                            ->count(),
                        'approved' => !!$alcada
                            ->workflowAprovacoes()
                            ->where('created_at', '>=', $qc->created_at->toDateTimeString())
                            ->where('aprovavel_id', $qc->id)
                            ->where('user_id', $user->id)
                            ->where('aprovado', 1)
                            ->count(),
                    ];
                })->toArray()
            ];
        })->toArray();

        $steps['workflow']['is_approved'] = !collect($steps['workflow']['timeline'])->pluck('is_approved')->values()->contains(false);

        $timeline['steps'] = $steps;

        $timeline['current'] = collect($steps)
            ->filter(function($step) {
                return !$step['is_finished'];
            })
            ->keys()
            ->first();

        return $timeline;
    }
}
