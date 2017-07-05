<?php

namespace App\Http\Controllers;

use App\Models\SeStatus;
use Illuminate\Http\Request;
use App\Models\WorkflowTipo;
use App\Repositories\WorkflowAprovacaoRepository;
use App\Repositories\SolicitacaoEntregaRepository;
use App\Models\WorkflowAlcada;
use App\Repositories\Admin\WorkflowReprovacaoMotivoRepository;

class SolicitacaoEntregaController extends AppBaseController
{
    /**
     * Show an especific resource
     *
     * @param mixed $id
     *
     * @return Response
     */
    public function show(
        WorkflowReprovacaoMotivoRepository $workflowReprovacaoMotivoRepository,
        SolicitacaoEntregaRepository $repository,
        Request $request,
        $id
    ) {
        $entrega      = $repository->find($id)->load('itens.apropriacoes');
        $apropriacoes = $entrega->itens
            ->pluck('apropriacoes')
            ->collapse();

        $alcadas = WorkflowAlcada::where('workflow_tipo_id', WorkflowTipo::SOLICITACAO_ENTREGA)
            ->orderBy('ordem', 'ASC')
            ->get();

        $alcadas_count = $alcadas->count();

        if ($entrega->isStatus(SeStatus::EM_APROVACAO)) {
            $workflowAprovacao = WorkflowAprovacaoRepository::verificaAprovacoes(
                'SolicitacaoEntrega',
                $entrega->id,
                $request->user()
            );

            foreach ($alcadas as $alcada) {
                $avaliado_reprovado[$alcada->id] = WorkflowAprovacaoRepository::verificaTotalJaAprovadoReprovado(
                    'SolicitacaoEntrega',
                    $entrega->irmaosIds(),
                    null,
                    null,
                    $alcada->id
                );

                $avaliado_reprovado[$alcada->id]['aprovadores'] = WorkflowAprovacaoRepository::verificaQuantidadeUsuariosAprovadores(
                    WorkflowTipo::find(WorkflowTipo::SOLICITACAO_ENTREGA),
                    $entrega->obra_id,
                    $alcada->id
                );

                $avaliado_reprovado[$alcada->id]['faltam_aprovar'] = WorkflowAprovacaoRepository::verificaUsuariosQueFaltamAprovar(
                    'SolicitacaoEntrega',
                    WorkflowTipo::SOLICITACAO_ENTREGA,
                    $entrega->obra_id,
                    $alcada->id,
                    [$alcada->id]
                );

                // Data do início da  Alçada
                if ($alcada->ordem === 1) {
                    $entrega_log = $entrega->logs()
                        ->where('se_status_id', 4)
                        ->first();

                    if ($entrega_log) {
                        $avaliado_reprovado[$alcada->id]['data_inicio'] = $entrega_log->created_at
                            ->format('d/m/Y H:i');
                    }
                } else {
                    $primeiro_voto = WorkflowAprovacao::where('aprovavel_type', 'App\\Models\\SolicitacaoEntrega')
                        ->where('aprovavel_id', $entrega->id)
                        ->where('workflow_alcada_id', $alcada->id)
                        ->orderBy('id', 'ASC')
                        ->first();
                    if ($primeiro_voto) {
                        $avaliado_reprovado[$alcada->id]['data_inicio'] = $primeiro_voto->created_at->format('d/m/Y H:i');
                    }
                }
            }
        }

        $aprovado = $entrega->isStatus(SeStatus::APROVADO);

        $motivos = $workflowReprovacaoMotivoRepository
            ->porTipoForSelect(WorkflowTipo::SOLICITACAO_ENTREGA)
            ->toArray();

        return view('solicitacao_entrega.show', compact(
            'entrega',
            'aprovado',
            'apropriacoes',
            'workflowAprovacao',
            'alcadas_count',
            'avaliado_reprovado',
            'motivos'
        ));
    }
}
