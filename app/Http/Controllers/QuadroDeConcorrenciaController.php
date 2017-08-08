<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Insumo;
use App\Models\OrdemDeCompraItem;
use App\Models\QuadroDeConcorrencia;
use App\Models\WorkflowAlcada;
use App\Models\WorkflowAprovacao;
use App\Models\WorkflowTipo;
use App\Notifications\WorkflowNotification;
use App\Repositories\ContratoRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\WorkflowAprovacaoRepository;
use Flash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Response;
use Exception;
use App\DataTables\QcItensDataTable;
use App\DataTables\QuadroDeConcorrenciaDataTable;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\QcInformarValorRequest;
use App\Http\Requests\QcAvaliarRequest;
use App\Http\Requests\CreateQuadroDeConcorrenciaRequest;
use App\Http\Requests\UpdateQuadroDeConcorrenciaRequest;
use App\Http\Requests\CreateEqualizacaoTecnicaExtraRequest;
use App\Http\Requests\UpdateEqualizacaoTecnicaExtraRequest;
use App\Http\Requests\CreateEqualizacaoTecnicaAnexoExtraRequest;
use App\Http\Requests\UpdateEqualizacaoTecnicaAnexoExtraRequest;
use App\Models\QcEqualizacaoTecnicaAnexoExtra;
use App\Models\QcEqualizacaoTecnicaExtra;
use App\Models\QcFornecedor;
use App\Models\QcItem;
use App\Models\WorkflowReprovacaoMotivo;
use App\Repositories\QuadroDeConcorrenciaRepository;
use App\Repositories\Admin\FornecedoresRepository;
use App\Repositories\QcFornecedorRepository;
use App\Repositories\QcItemQcFornecedorRepository;
use App\Repositories\QcFornecedorEqualizacaoCheckRepository;
use Illuminate\Support\Facades\DB;
use App\Repositories\DesistenciaMotivoRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\DataTables\InsumoPorFornecedorDataTable;
use App\Repositories\QcStatusLogRepository;
use App\Notifications\IniciaConcorrencia;
use Carbon\Carbon;
use App\Models\QcStatus;
use App\Repositories\QuadroDeConcorrenciaItemRepository;
use App\Models\ContratoItemModificacaoLog;
use App\Models\ContratoItemModificacao;
use App\Models\ContratoItem;
use App\Models\ContratoStatus;

class QuadroDeConcorrenciaController extends AppBaseController
{
    /** @var  QuadroDeConcorrenciaRepository */
    private $quadroDeConcorrenciaRepository;

    public function __construct(QuadroDeConcorrenciaRepository $quadroDeConcorrenciaRepo)
    {
        $this->quadroDeConcorrenciaRepository = $quadroDeConcorrenciaRepo;
    }

    /**
     * Display a listing of the QuadroDeConcorrencia.
     *
     * @param QuadroDeConcorrenciaDataTable $quadroDeConcorrenciaDataTable
     * @return Response
     */
    public function index(QuadroDeConcorrenciaDataTable $quadroDeConcorrenciaDataTable)
    {
        return $quadroDeConcorrenciaDataTable->render('quadro_de_concorrencias.index');
    }

    /**
     * Show the form for creating a new QuadroDeConcorrencia.
     *
     * @return Response
     */
    public function create(Request $request, QcItensDataTable $qcItensDataTable)
    {
        # Validação básica
        validator($request->all(),
            ['ordem_de_compra_itens' => 'required'],
            ['ordem_de_compra_itens.required' => 'É necessário escolher ao menos um item!']
        )->validate();

        # Cria QC pra ficar em aberto com os itens passados
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->create([
            'itens' => $request->ordem_de_compra_itens,
            'user_id' => Auth::id()
        ]);

        return redirect(route('quadroDeConcorrencias.edit', $quadroDeConcorrencia->id));

//        return $qcItensDataTable->qc($quadroDeConcorrencia->id)->render('quadro_de_concorrencias.edit', compact('quadroDeConcorrencia') );
    }

    /**
     * Display the specified QuadroDeConcorrencia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id, QcItensDataTable $qcItensDataTable)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);
        // Limpa qualquer notificação que tiver deste item
        NotificationRepository::marcarLido(WorkflowTipo::QC,$id);

        if (empty($quadroDeConcorrencia)) {
            Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

            return redirect(route('quadroDeConcorrencias.index'));
        }

        $show = 1;

        $motivos_reprovacao = WorkflowReprovacaoMotivo::where(function ($query) {
            $query->where('workflow_tipo_id', 2);
            $query->orWhereNull('workflow_tipo_id');
        })->pluck('nome', 'id')->toArray();

        $avaliado_reprovado = [];
        $itens_ids = $quadroDeConcorrencia->itens()->pluck('id', 'id')->toArray();
        $aprovavelTudo = WorkflowAprovacaoRepository::verificaAprovaGrupo('QuadroDeConcorrenciaItem', $itens_ids, Auth::user());
        $alcadas = WorkflowAlcada::where('workflow_tipo_id', WorkflowTipo::QC)->orderBy('ordem', 'ASC')->get();

        if ($quadroDeConcorrencia->qc_status_id == QcStatus::EM_APROVACAO) {
            foreach ($alcadas as $alcada) {
                $avaliado_reprovado[$alcada->id] = WorkflowAprovacaoRepository::verificaTotalJaAprovadoReprovado(
                    'QuadroDeConcorrenciaItem',
                    $quadroDeConcorrencia->itens()->pluck('id', 'id')->toArray(),
                    null,
                    null,
                    $alcada->id);

                $avaliado_reprovado[$alcada->id] ['aprovadores'] = WorkflowAprovacaoRepository::verificaQuantidadeUsuariosAprovadores(
                    WorkflowTipo::find(WorkflowTipo::QC),
                    $quadroDeConcorrencia->obra_id,
                    $alcada->id);

                $avaliado_reprovado[$alcada->id] ['faltam_aprovar'] = WorkflowAprovacaoRepository::verificaUsuariosQueFaltamAprovar(
                    'QuadroDeConcorrenciaItem',
                    WorkflowTipo::QC,
                    $quadroDeConcorrencia->obra_id,
                    $alcada->id,
                    $itens_ids);

                // Data do início da  Alçada
                if ($alcada->ordem === 1) {
                    $ordem_status_log = $quadroDeConcorrencia->logs()
                        ->where('qc_status_id', QcStatus::FECHADO)->first();
                    if ($ordem_status_log) {
                        $avaliado_reprovado[$alcada->id] ['data_inicio'] = $ordem_status_log->created_at
                            ->format('d/m/Y H:i');
                    }
                } else {
                    $primeiro_voto = WorkflowAprovacao::where('aprovavel_type', 'App\\Models\\QuadroDeConcorrencia')
                        ->whereIn('aprovavel_id', $itens_ids)
                        ->where('workflow_alcada_id', $alcada->id)
                        ->orderBy('id', 'ASC')
                        ->first();
                    if ($primeiro_voto) {
                        $avaliado_reprovado[$alcada->id]['data_inicio'] = $primeiro_voto->created_at->format('d/m/Y H:i');
                    }
                }
            }
        }

        $alcadas_count = $alcadas->count();
        $oc_status = $quadroDeConcorrencia->status->nome;

        return $qcItensDataTable->qc($quadroDeConcorrencia->id)
            ->with('show', $show)
            ->render('quadro_de_concorrencias.show',
                compact('quadroDeConcorrencia',
                        'show',
                        'motivos_reprovacao',
                        'aprovavelTudo',
                        'avaliado_reprovado',
                        'qtd_itens',
                        'alcadas_count',
                        'oc_status'
                ));
    }

    /**
     * Show the form for editing the specified QuadroDeConcorrencia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id, QcItensDataTable $qcItensDataTable)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

            return redirect(route('quadroDeConcorrencias.index'));
        }
        // Se estiver em aprovação não pode editar, redireciona para show
        if ($quadroDeConcorrencia->qc_status_id == 3) {
            Flash::error('Quadro De Concorrencia <strong>EM APROVAÇÃO</strong>, não é possível editar');
            return redirect(route('quadroDeConcorrencias.show', $quadroDeConcorrencia->id));
        }

        return $qcItensDataTable->qc($quadroDeConcorrencia->id)->render('quadro_de_concorrencias.edit', compact('quadroDeConcorrencia'));
    }

    /**
     * Update the specified QuadroDeConcorrencia in storage.
     *
     * @param  int $id
     * @param UpdateQuadroDeConcorrenciaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQuadroDeConcorrenciaRequest $request)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

            return redirect(route('quadroDeConcorrencias.index'));
        }

        $input = $request->all();
        $input['user_update_id'] = Auth::id();

        if($request->has('abrir_concorrencia')){

            $fornecedores = count($request->qcFornecedores) + count($request->qcFornecedoresMega);

            if($fornecedores > $quadroDeConcorrencia->qcFornecedores->count()){
                $input['qc_status_id'] = 7; // Em concorrência
            }else{
                Flash::error('Escolha novos fornecedores para o Q.C. '.$id);
                return back();
            }
        }

        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->update($input, $id);

        if(!$quadroDeConcorrencia){
            return back();
        }

        if (!$request->has('fechar_qc')) {
            if (!$request->has('adicionar_itens')) {
                Flash::success('Quadro De Concorrencia ' . trans('common.updated') . ' ' . trans('common.successfully') . '.');
            } else {
                Flash::success('Escolha os insumos para adicionar no Q.C. '.$id);
            }
        } else {
            $aprovadores = WorkflowAprovacaoRepository::usuariosDaAlcadaAtual($quadroDeConcorrencia);
            Notification::send($aprovadores, new WorkflowNotification($quadroDeConcorrencia));
            Flash::success('Quadro De Concorrencia colocado em aprovação.');
        }


        if (!$request->has('manter')) {
            if (!$request->has('adicionar_itens')) {
                return redirect(route('quadroDeConcorrencias.index'));
            } else {
                return redirect('/ordens-de-compra/insumos-aprovados?qc='.$quadroDeConcorrencia->id);
            }
        } else {
            return redirect(route('quadroDeConcorrencias.edit', $quadroDeConcorrencia->id));
        }
    }

    public function adicionar($id, UpdateQuadroDeConcorrenciaRequest $request)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

            return redirect(route('quadroDeConcorrencias.index'));
        }

        $input = $request->all();
        # Validação básica
        validator($request->all(),
            ['ordem_de_compra_itens' => 'required'],
            ['ordem_de_compra_itens.required' => 'É necessário escolher ao menos um item!']
        )->validate();

        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->update([
            'itens' => $request->ordem_de_compra_itens,
            'user_update_id' => Auth::id()
        ], $id);

        Flash::success('Insumos addicionados no Q.C.');

        return redirect(route('quadroDeConcorrencias.edit', $quadroDeConcorrencia->id));
    }

    /**
     * Remove the specified QuadroDeConcorrencia from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

            return redirect(route('quadroDeConcorrencias.index'));
        }

        $this->quadroDeConcorrenciaRepository->delete($id);

        Flash::success('Quadro De Concorrencia ' . trans('common.deleted') . ' ' . trans('common.successfully') . '.');

        return redirect(route('quadroDeConcorrencias.index'));
    }

    public function avaliar(
        $id,
        Request $request,
        FornecedoresRepository $fornecedorRepository,
        DesistenciaMotivoRepository $desistenciaMotivoRepository,
        QcFornecedorRepository $qcFornecedorRepository,
        InsumoPorFornecedorDataTable $view
    ) {
        $user = Auth::user();

        $isFornecedor = !is_null($user->fornecedor);

        $quadro = $this->quadroDeConcorrenciaRepository
            ->with(
                'itens.insumo',
                'itens.ordemDeCompraItens'
            )
            ->findWithoutFail($id);

        if (empty($quadro)) {
            Flash::error('Quadro De Concorrencia '.trans('common.not-found'));

            return redirect(route('quadroDeConcorrencias.index'));
        }

        if ($quadro->qc_status_id != 7) {
            Flash::error('Quadro De Concorrencia deve estar EM CONCORRÊNCIA para ser avaliado!');

            return redirect(route('quadroDeConcorrencias.index'));
        }

        if (!$quadro->temOfertas()) {
            Flash::error('Você não pode avaliar um quadro de concorrência sem ofertas.');

            return redirect(route('quadroDeConcorrencias.index'));
        }

        $rodadaSelecionada = (int) $request->get('rodada', $quadro->rodada_atual);

        $qcFornecedores = $qcFornecedorRepository->queOfertaramNoQuadroNaRodada(
            $id,
            $rodadaSelecionada
        );


        $ofertas = $quadro->itens->reduce(function ($ofertas, $item) use ($qcFornecedores) {
            $ofertas[] = $qcFornecedores
                ->filter(function ($qcFornecedor) use ($item) {
                    return $qcFornecedor->itens->where('qc_item_id', $item->id)->first();
                })
                ->map(function ($qcFornecedor) use ($item) {
                $oferta = $qcFornecedor->itens->where('qc_item_id', $item->id)->first();

                return [
                    'insumo_id'      => $item->insumo->id,
                    'insumo'         => $item->insumo->nome,
                    'fornecedor_id'  => $qcFornecedor->fornecedor_id,
                    'valor_total'    => (float) $oferta->valor_total,
                    'valor_unitario' => (float) $oferta->valor_unitario,
                    'valor_oi' => floatval($item->ordemDeCompraItens->sortBy('valor_unitario')->first() ? $item->ordemDeCompraItens->sortBy('valor_unitario')->first()->valor_unitario : 0),
                ];
            })->all();

            return $ofertas;
        }, collect())
            ->collapse()
            ->all();

        return $view->setQuadroDeConcorrencia($quadro)
            ->setQcFornecedores($qcFornecedores)
            ->render(
                'quadro_de_concorrencias.avaliar',
                compact('qcFornecedores', 'quadro', 'ofertas', 'rodadaSelecionada')
            );
    }

    public function avaliarSave(
        $id,
        QcAvaliarRequest $request,
        QcFornecedorRepository $qcFornecedorRepository,
        QcItemQcFornecedorRepository $qcItemFornecedorRepository,
        QcStatusLogRepository $qcStatusLogRepository
    ) {
        $quadro = $this->quadroDeConcorrenciaRepository
            ->with(
                'itens.insumo',
                'itens.ordemDeCompraItens'
            )
            ->findWithoutFail($id);


        if (empty($quadro)) {
            Flash::error('Quadro De Concorrencia '.trans('common.not-found'));

            return redirect(route('quadroDeConcorrencias.index'));
        }

        DB::beginTransaction();

        try {
            if ($request->gerar_nova_rodada) {

                $quadro->update(['rodada_atual' => (int) $quadro->rodada_atual + 1]);

                #Inserir novos fornecedores que vão participar da nova RODADA
                $input = $request->except('fornecedores','fornecedores_temp','gerar_nova_rodada','_token');
                $input['user_update_id'] = Auth::id();
                $this->quadroDeConcorrenciaRepository->update($input, $id);

                $mensagens = collect($request->fornecedores)
                    ->map(function ($fornecedor) use ($quadro, $request) {
                        return [
                            'fornecedor_id' => $fornecedor,
                            'quadro_de_concorrencia_id' => $quadro->id,
                            'user_id' => $request->user()->id,
                            'rodada' => $quadro->rodada_atual
                        ];
                    })
                    ->map([$qcFornecedorRepository, 'create'])
                    ->map(function ($qcFornecedor) use ($quadro) {
                        return $this->quadroDeConcorrenciaRepository->notifyFornecedor(
                            $qcFornecedor->fornecedor,
                            $quadro
                        );
                    })
                    ->filter()
                    ->flatten();

                if (!empty($mensagens)) {
                    Flash::warning(
                        '<p> Quadro de Concorrência #' . $quadro->id . ' foi enviado para rodada ' . $quadro->rodada_atual . '</p>'
                        . '<ul><li> ' . $mensagens->implode('</li><li>') . ' </li></ul>'
                    );
                } else {
                    Flash::success(
                        'Quadro de Concorrência #' . $quadro->id . ' foi enviado para rodada ' . $quadro->rodada_atual
                    );
                }

                DB::commit();
                return redirect(route('quadroDeConcorrencias.index'));
            }

            $vencedores = collect($request->vencedores)
                ->map(function ($qcItemQcFornecedorId) use ($qcItemFornecedorRepository) {
                    return $qcItemFornecedorRepository->find($qcItemQcFornecedorId);
                })
                ->each(function ($qcItemQcFornecedor) {
                    $qcItemQcFornecedor->update([
                        'vencedor' => true,
                        'data_decisao' => Carbon::now()
                    ]);
                });

            $quadro->update([
                'qc_status_id' => QcStatus::CONCORRENCIA_FINALIZADA
            ]);

            if ($request->valor_frete) {
                foreach ($request->valor_frete as $qcFornecedorId => $valor) {
                    $valor = !is_null($valor)?money_to_float($valor):0;
                    $qcFornecedor = QcFornecedor::find($qcFornecedorId);
                    $qcFornecedor->valor_frete = money_to_float($valor);
                    $qcFornecedor->save();
                }
            }

            $qcStatusLogRepository->create([
                'qc_status_id' => QcStatus::CONCORRENCIA_FINALIZADA,
                'quadro_de_concorrencia_id' => $quadro->id,
                'user_id' => $request->user()->id
            ]);

            $qcItensAditivos = $quadro->itens->load('ordemDeCompraItens')
                    ->map(function($qcItem) {
                        $qcItem->contratos = $qcItem->ordemDeCompraItens
                            ->pluck('sugestao_contrato_id')
                            ->filter();

                        return $qcItem;
                    })
                    ->reject(function($qcItem) {
                        return $qcItem->contratos->isEmpty();
                    });

            $qcItensAditivos->each(function($qcItem) use ($vencedores) {
                $qcItem->contratos->each(function($contrato_id) use ($qcItem, $vencedores) {

                    $contrato = Contrato::find($contrato_id);

                    $vencedor = $vencedores->where('qc_item_id', $qcItem->id)->first();

                    // Se o contrato sugerido não é do fornecedor vencedor, não aditivar.
                    if((int) $contrato->fornecedor_id !== (int) $vencedor->qcFornecedor->fornecedor_id) {
                        return true;
                    }

                    $contratoItem = ContratoItem::create([
                        'qc_item_id'     => $qcItem->id,
                        'insumo_id'      => $qcItem->insumo_id,
                        'qtd'            => $qcItem->ordemDeCompraItens->where('obra_id', $contrato->obra_id)->sum('qtd'),
                        'valor_unitario' => $vencedor->valor_unitario,
                        'valor_total'    => $vencedor->valor_total,
                        'aprovado'       => 0,
                        'pendente'       => 1,
                        'contrato_id'    => $contrato->id,
                    ]);

                    $mod = ContratoItemModificacao::create([
                        'contrato_item_id'        => $contratoItem->id,
                        'qtd_anterior'            => 0,
                        'qtd_atual'               => $contratoItem->qtd,
                        'valor_unitario_anterior' => 0,
                        'valor_unitario_atual'    => $contratoItem->valor_unitario,
                        'contrato_status_id'      => ContratoStatus::EM_APROVACAO,
                        'tipo_modificacao'        => 'Aditivo',
                        'user_id'                 => auth()->id()
                    ]);

                    ContratoItemModificacaoLog::create([
                        'contrato_item_modificacao_id' => $mod->id,
                        'contrato_status_id'           => $mod->contrato_status_id
                    ]);
                });
            });

        } catch (Exception $e) {
            DB::rollback();
            logger()->error((string) $e);
            Flash::error('Ocorreu um erro ao salvar os dados, tente novamente');

            return back();
        }

        DB::commit();

        Flash::success(
            'Quadro de Concorrência #' . $quadro->id . ' foi finalizado com sucesso.'
        );

        return redirect(url('quadro-de-concorrencia/'.$quadro->id.'/gerar-contrato'));
    }

    /**
     * Formulário para adicionar valores do fornecedor
     *
     * @param int $id
     *
     * @return Response
     */
    public function informarValor(
        $id,
        FornecedoresRepository $fornecedorRepository,
        DesistenciaMotivoRepository $desistenciaMotivoRepository
    ) {
        $user = auth()->user();
        $isFornecedor = !is_null($user->fornecedor);

        $quadro = $this->quadroDeConcorrenciaRepository
            ->with(
                'tipoEqualizacaoTecnicas.itens',
                'tipoEqualizacaoTecnicas.anexos',
                'itens.insumo',
                'itens.ordemDeCompraItens'
            )
            ->findWithoutFail($id);

        if (empty($quadro)) {
            Flash::error('Quadro De Concorrencia '.trans('common.not-found'));

            return redirect(route('quadroDeConcorrencias.index'));
        }

        if ($quadro->qc_status_id != 7) {
            Flash::error('Quadro De Concorrencia deve estar EM CONCORRÊNCIA para lançar valores!');

            return redirect(route('quadroDeConcorrencias.index'));
        }

        if(
            $isFornecedor &&
            !$fornecedorRepository->podePreencherQuadroNaRodada(
                $user->fornecedor->id,
                $quadro->id,
                $quadro->rodada_atual
            )
        ) {
            Flash::error(
                'Você já preencheu este quadro ou não está presente na rodada atual.'
            );

            return redirect(route('quadroDeConcorrencias.index'));
        } else {
            $fornecedores = $fornecedorRepository
                ->todosQuePodemPreencherQuadroNaRodada($quadro->id, $quadro->rodada_atual)
                ->pluck('nome', 'id')
                ->prepend('Selecione um fornecedor...', '')
                ->toArray();
        }

        if (count($fornecedores) === 1) {
            Flash::error('
                Este quadro já foi preenchido por todos os fornecedores possíveis'
            );

            return redirect(route('quadroDeConcorrencias.index'));
        }

        $equalizacoes = $quadro->tipoEqualizacaoTecnicas
            ->pluck('itens')
            ->merge($quadro->equalizacaoTecnicaExtras)
            ->flatten();

        $anexos = $quadro->tipoEqualizacaoTecnicas
            ->pluck('anexos')
            ->flatten()
            ->merge($quadro->anexos);

        $motivos = $desistenciaMotivoRepository
            ->pluck('nome', 'id')
            ->prepend('Selecione um motivo...', '')
            ->toArray();

        return view('quadro_de_concorrencias.informar_valor')
            ->with(compact(
                'anexos',
                'equalizacoes',
                'quadro',
                'fornecedores',
                'motivos'
            ));
    }

    /**
     * Salvar valores do fornecedor
     *
     * @param int $id
     *
     * @return Response
     */
    public function informarValorSave(
        QcInformarValorRequest $request,
        QcFornecedorRepository $qcFornecedorRepository,
        QcFornecedorEqualizacaoCheckRepository $checksRepository,
        QcItemQcFornecedorRepository $qcItemFornecedorRepository,
        $id
    ) {
        DB::beginTransaction();

        try {
            $quadro = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

            if (!$request->reject) {
                $itens = $request->itens;
                $array = [];

                foreach ($itens as $chave => $item) {
                    if ($item['valor_unitario'] == "") {
                        array_push($array, $chave);
                    }
                }

                if (count($itens) == count($array)) {
                    Flash::error('Necessário pelo menos um valor unitário.');
                    return back()->withInput();
                }
            }
//            dd(count($itens), count($array));


            if (empty($quadro)) {
                DB::rollback();
                Flash::error('Quadro De Concorrencia '.trans('common.not-found'));

                return back()->withInput();
            }

            $qcFornecedor = $qcFornecedorRepository->buscarPorQuadroEFornecedor(
                $id,
                $request->fornecedor_id
            );

            if ($request->reject) {
                $qcFornecedor->update([
                    'desistencia_motivo_id' => $request->desistencia_motivo_id,
                    'desistencia_texto' => $request->desistencia_texto
                ]);

                $fornecedoresDoQc = $quadro->qcFornecedores()->count();
                $fornecedoresQueRejeitaramQc = $quadro->qcFornecedores()
                    ->whereNotNull('desistencia_motivo_id')
                    ->whereNotNull('desistencia_texto')
                    ->count();

                if($fornecedoresDoQc === $fornecedoresQueRejeitaramQc) {
                    $quadro->update(['qc_status_id' => QcStatus::REJEITADO]);
                }
            }

            if ($quadro->hasServico() && !$request->reject) {
                $porcentagens = array_values($request->only([
                    'porcentagem_faturamento_direto',
                    'porcentagem_material',
                    'porcentagem_servico',
                    'porcentagem_locacao',
                ]));

                $porcentagens = array_sum($porcentagens);

                if ($porcentagens !== 100) {
                    DB::rollback();
                    Flash::error('As porcentagens não somam 100%');

                    return back()->withInput();
                }

                if (empty(array_filter($request->only(['nf_material', 'nf_servico', 'nf_locacao'])))) {
                    DB::rollback();
                    Flash::error('Selecione pelo menos um tipo de nota fiscal');

                    return back()->withInput();
                }

                $qcFornecedor->update([
                    'nf_material' => $request->nf_material,
                    'nf_servico' => $request->nf_servico,
                    'nf_locacao' => $request->nf_locacao,
                    'porcentagem_faturamento_direto' => $request->porcentagem_faturamento_direto ?: 0,
                    'porcentagem_material' => $request->porcentagem_material ?: 0,
                    'porcentagem_servico' => $request->porcentagem_servico ?: 0,
                    'porcentagem_locacao' => $request->porcentagem_locacao ?: 0,
                ]);
            } elseif (!$quadro->hasServico() && !$request->reject) {
                $qcFornecedor->update([
                    'nf_material' => 1,
                    'nf_servico' => 0,
                    'nf_locacao' => 0,
                    'porcentagem_faturamento_direto' => 0,
                    'porcentagem_material' => 100,
                    'porcentagem_servico' => 0,
                    'porcentagem_locacao' => 0,
                ]);
            }

            if (!$request->reject) {
                    if (!intval($request->frete_incluso) && !$request->tipo_frete) {
                        if ($quadro->hasMaterial()) {

                            DB::rollback();
                            Flash::error('Selecione o Tipo do Frete');

                            return back()->withInput();
                        }
                    } else {
                        if (!intval($request->frete_incluso)) {
                            if ($request->tipo_frete=='FOB' && (is_null($request->valor_frete) || floatval($request->valor_frete) == 0)) {
                                DB::rollback();
                                Flash::error('O tipo de Frete FOB é necessário informar um valor');

                                return back()->withInput();
                            }
                        }
                    }

                    $qcFornecedor->update([
                        'tipo_frete' => intval($request->frete_incluso)?'INC': $request->tipo_frete,
                        'valor_frete' => ($request->tipo_frete=='FOB'? money_to_float($request->get('valor_frete', 0)): 0),
                    ]);


                if(!empty($request->equalizacoes)) {
                    foreach ($request->equalizacoes as $check) {
                        $check['qc_fornecedor_id'] = $qcFornecedor->id;
                        $check['user_id'] = $request->user()->id;
                        $checksRepository->create($check);
                    }
                }

                foreach ($request->itens as $qcItemId => $item) {
                    $item['qc_fornecedor_id'] = $qcFornecedor->id;
                    $item['user_id'] = $request->user()->id;
                    $item['qc_item_id'] = $qcItemId;

                    $item['qtd'] = (float) $item['qtd'];

                    if (Str::length($item['valor_unitario'])) {
                        $item['valor_unitario'] = money_to_float($item['valor_unitario']);
                        $item['valor_total'] = $item['valor_unitario'] * $item['qtd'];

                        $qcItemFornecedorRepository->create($item);
                    }


                }
            }
        } catch (Exception $e) {
            DB::rollback();
            Flash::error('Ocorreu um erro ao salvar os dados, tente novamente ');
            Log::error('Erro ao salvar proposta de Fornecedor', [$e->getMessage().' File '.$e->getFile().' linha '.$e->getLine(),'Stack trace:'=>$e->getTraceAsString()]);
            return back()->withInput();
        }

        DB::commit();
        Flash::success('Dados salvos com sucesso');

        return redirect(route('quadroDeConcorrencias.index'));
    }

    public function adicionaEqt($id, CreateEqualizacaoTecnicaExtraRequest $request)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }
        $qcEqualizacaoTecnicaExtra = QcEqualizacaoTecnicaExtra::create([
            'quadro_de_concorrencia_id' => $id,
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'obrigatorio' => $request->obrigatorio
        ]);

        return $qcEqualizacaoTecnicaExtra;
    }

    public function removerEqt($id, $eqtId)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }
        $qcEqualizacaoTecnicaExtra = QcEqualizacaoTecnicaExtra::find($eqtId);

        if (!$qcEqualizacaoTecnicaExtra) {
            return response()->json(['error' => 'Item não encontrado ' . trans('common.not-found')], 404);
        }

        return response()->json(['success' => $qcEqualizacaoTecnicaExtra->delete()]);
    }

    public function exibirEqt($id, $eqtId)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }
        $qcEqualizacaoTecnicaExtra = QcEqualizacaoTecnicaExtra::find($eqtId);

        if (!$qcEqualizacaoTecnicaExtra) {
            return response()->json(['error' => 'Item não encontrado ' . trans('common.not-found')], 404);
        }

        return $qcEqualizacaoTecnicaExtra;
    }

    public function editarEqt($id, $eqtId, UpdateEqualizacaoTecnicaExtraRequest $request)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }

        $qcEqualizacaoTecnicaExtra = QcEqualizacaoTecnicaExtra::find($eqtId);
        if (!$qcEqualizacaoTecnicaExtra) {
            return response()->json(['error' => 'Item não encontrado ' . trans('common.not-found')], 404);
        }

        $qcEqualizacaoTecnicaExtra->nome = $request->nome;
        $qcEqualizacaoTecnicaExtra->descricao = $request->descricao;
        $qcEqualizacaoTecnicaExtra->obrigatorio = $request->obrigatorio;
        $qcEqualizacaoTecnicaExtra->save();

        return $qcEqualizacaoTecnicaExtra;
    }

    public function removerFornecedor($id, $fornecedorId)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }
        $qcFornecedor = QcFornecedor::find($fornecedorId);

        if (!$qcFornecedor) {
            return response()->json(['error' => 'Item não encontrado ' . trans('common.not-found')], 404);
        }

        return response()->json(['success' => $qcFornecedor->delete()]);
    }

    public function adicionaEqtAnexo($id, CreateEqualizacaoTecnicaAnexoExtraRequest $request)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }

        $qcEqualizacaoTecnicaExtra = QcEqualizacaoTecnicaAnexoExtra::create([
            'quadro_de_concorrencia_id' => $id,
            'nome' => $request->nome,
            'arquivo' => $request->arquivo->store('public/anexos'),
        ]);

        return $qcEqualizacaoTecnicaExtra;
    }

    public function removerEqtAnexo($id, $eqtId)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }
        $qcEqualizacaoTecnicaAnexoExtra = QcEqualizacaoTecnicaAnexoExtra::find($eqtId);

        if (!$qcEqualizacaoTecnicaAnexoExtra) {
            return response()->json(['error' => 'Item não encontrado ' . trans('common.not-found')], 404);
        }

        return response()->json(['success' => $qcEqualizacaoTecnicaAnexoExtra->delete()]);
    }

    public function exibirEqtAnexo($id, $eqtId)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }
        $qcEqualizacaoTecnicaAnexoExtra = QcEqualizacaoTecnicaAnexoExtra::find($eqtId);

        if (!$qcEqualizacaoTecnicaAnexoExtra) {
            return response()->json(['error' => 'Item não encontrado ' . trans('common.not-found')], 404);
        }

        return $qcEqualizacaoTecnicaAnexoExtra;
    }

    public function editarEqtAnexo($id, $eqtId, UpdateEqualizacaoTecnicaAnexoExtraRequest $request)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }

        $qcEqualizacaoTecnicaAnexoExtra = QcEqualizacaoTecnicaAnexoExtra::find($eqtId);
        if (!$qcEqualizacaoTecnicaAnexoExtra) {
            return response()->json(['error' => 'Item não encontrado ' . trans('common.not-found')], 404);
        }

        $qcEqualizacaoTecnicaAnexoExtra->nome = $request->nome;
        if ($request->arquivo) {
            $qcEqualizacaoTecnicaAnexoExtra->arquivo = $request->arquivo->store('public/anexos');
        }
        $qcEqualizacaoTecnicaAnexoExtra->save();

        return $qcEqualizacaoTecnicaAnexoExtra;
    }

    public function desagrupar($QCid, $id)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($QCid);

        if (empty($quadroDeConcorrencia)) {
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }

        $qcItem = QcItem::find($id);
        $ordemDeCompraItens = $qcItem->oc_itens;

        foreach ($ordemDeCompraItens as $ocItem) {
            $novoQcItem = QcItem::create([
                'quadro_de_concorrencia_id'=>$quadroDeConcorrencia->id,
                'qtd'=> $ocItem->getOriginal('qtd'),
                'insumo_id' => $ocItem->insumo_id
            ]);
            $novoQcItem->oc_itens()->attach($ocItem->id);
        }

        return response()->json(['success'=>$qcItem->delete()]);
    }

    public function agrupar($QCid, Request $request)
    {
        $this->validate($request, ['itens'=>'required|min:2'], ['itens.min'=>'São necessários no mínimo 2 itens']);

        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($QCid);

        if (empty($quadroDeConcorrencia)) {
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }

        $qcItens = QcItem::whereIn('id', $request->itens)->get();
        $qcItensQtd = QcItem::whereIn('id', $request->itens)->sum('qtd');
        $qcItem = QcItem::whereIn('id', $request->itens)->first();

        // Cria o novo QCitem agrupado
        $novoQcItem = QcItem::create([
            'quadro_de_concorrencia_id' => $quadroDeConcorrencia->id,
            'qtd'=> $qcItensQtd,
            'insumo_id' => $qcItem->insumo_id
        ]);

        // Amarra os itens de ordem de compra neste novo QC Item
        foreach ($qcItens as $qcItem) {
            $ordemDeCompraItens = $qcItem->oc_itens;

            foreach ($ordemDeCompraItens as $ocItem) {
                $novoQcItem->oc_itens()->attach($ocItem->id);
            }
        }
        // Depois de amarrados remove todos os antigos
        $remover = QcItem::whereIn('id', $request->itens)->delete();

        return response()->json(['success'=>$remover]);
    }

    public function acao($QCid, $acao)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($QCid);

        if (empty($quadroDeConcorrencia)) {
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }

        $acao_executada = $this->quadroDeConcorrenciaRepository->acao($acao, $QCid, Auth::id());
        if ($acao_executada[0]) {
            $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($QCid);
            return response()->json(['success' => true,'quadroDeConcorrencia'=>$quadroDeConcorrencia,'mensagens'=>$acao_executada[1]]);
        } else {
            return response()->json(['error' => 'Esta ação não foi possível: ' . $acao_executada[1]], 422);
        }
    }

    public function getEqualizacaoTecnica(
        $quadro,
        $qcFornecedor,
        Request $request,
        QcFornecedorEqualizacaoCheckRepository $fornecedorCheckRepository
    ) {
        $quadro = $this->quadroDeConcorrenciaRepository
            ->findWithoutFail($quadro);

        if (empty($quadro)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }

            return response()->json([
                'error' => 'Quadro De Concorrencia ' . trans('common.not-found')
            ], 404);
        }

        $checks = $fornecedorCheckRepository->porQcFornecedor($qcFornecedor);

        return view('quadro_de_concorrencias.equalizacoes', compact('checks'));
    }

    /**
     * Gerar Contrato - Tela para especificar Template para gerar contrato
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function gerarContrato($id)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

            return redirect(route('quadroDeConcorrencias.index'));
        }

        $fornecedores = $quadroDeConcorrencia
            ->qcFornecedores()
            ->where('qc_fornecedor.rodada', $quadroDeConcorrencia->rodada_atual)
            ->whereHas('itens', function ($query) {
                $query->where('vencedor', '1');
            })
            ->with(['itens'=> function ($query) {
                $query->where('vencedor', '1');
            }])
            ->get();

        $contratoItens = [];
        $total_contrato = [];

        $valorMaterial = [];
        $valorFaturamentoDireto = [];
        $valorLocacao = [];

        foreach ($fornecedores as $qcFornecedor) {
            // Monta os itens do contrato

            $fatorServico = 1;
            $fatorMaterial = 0;
            $fatorFatDireto = 0;
            $fatorLocacao = 0;
            $contratoComMaterial = [];

            if ($quadroDeConcorrencia->hasServico()) {
                if ($qcFornecedor->porcentagem_servico < 100) {
                    $fatorServico = $qcFornecedor->porcentagem_servico / 100;
                    $fatorMaterial = $qcFornecedor->porcentagem_material / 100;
                    $fatorFatDireto = $qcFornecedor->porcentagem_faturamento_direto / 100;
                    $fatorLocacao = $qcFornecedor->porcentagem_locacao / 100;

                    // Se não marcou NF material, coloca o fator material como zero
                    if (!$qcFornecedor->nf_material) {
                        $fatorServico += $fatorMaterial;
                        $fatorMaterial = 0;
                    }
                    // Se não marcou NF locacao, coloca o fator locacao como zero
                    if (!$qcFornecedor->nf_material) {
                        $fatorServico += $fatorLocacao;
                        $fatorLocacao = 0;
                    }
                }
            }

            foreach ($qcFornecedor->itens as $item) {
                $valor_item = $item->valor_total;
                $valor_item_unitario = $item->valor_unitario;

                $qcItem = $item->qcItem;
                $insumo = $qcItem->insumo;
                $obras = $qcItem->oc_itens()->select('obra_id')->groupBy('obra_id')->get();

                foreach ($obras as $obra) {
                    $obra_id = $obra->obra_id;

                    // Busca a soma da qtd para esta obra
                    $qtd = $qcItem->oc_itens()->where('obra_id', $obra_id)->sum('qtd');
                    $valor_item = $valor_item_unitario * $qtd;

                    // Inicia os contadores caso não existam
                        if (!isset($contratoItens[$qcFornecedor->id][$obra_id])) {
                            $contratoItens[$qcFornecedor->id][$obra_id] = [];
                        }
                    if (!isset($total_contrato[$qcFornecedor->id][$obra_id])) {
                        $total_contrato[$qcFornecedor->id][$obra_id] = 0;
                    }
                    if (!isset($valorMaterial[$qcFornecedor->id][$obra_id])) {
                        $valorMaterial[$qcFornecedor->id][$obra_id] = 0;
                    }
                    if (!isset($valorFaturamentoDireto[$qcFornecedor->id][$obra_id])) {
                        $valorFaturamentoDireto[$qcFornecedor->id][$obra_id] = 0;
                    }
                    if (!isset($valorLocacao[$qcFornecedor->id][$obra_id])) {
                        $valorLocacao[$qcFornecedor->id][$obra_id] = 0;
                    }

                    $total_contrato[$qcFornecedor->id][$obra_id] += $valor_item;
                    $tipo = explode(' ', $insumo->grupo->nome);
                    if ($fatorServico<1) {
                        if ($tipo[0]=='SERVIÇO') {
                            if ($fatorFatDireto > 0) {
                                $valorFaturamentoDireto[$qcFornecedor->id][$obra_id] += $valor_item * $fatorFatDireto;
                            }
                            if ($fatorMaterial > 0) {
                                $valorMaterial[$qcFornecedor->id][$obra_id] += $valor_item * $fatorMaterial;
                            }
                            if ($fatorLocacao > 0) {
                                $valorLocacao[$qcFornecedor->id][$obra_id] += $valor_item * $fatorLocacao;
                            }
                            $valor_item = $valor_item * $fatorServico;
                            $valor_item_unitario = $item->valor_unitario * $fatorServico;
                        } else {
                            $contratoComMaterial[$qcFornecedor->id.'-'.$obra_id] = ['qcFornecedor'=>$qcFornecedor->id,'obraId'=>$obra_id];
                        }
                    }

                    $contratoItens[$qcFornecedor->id][$obra_id][] = [
                        'insumo_id'         => $insumo->id,
                        'insumo'            => $insumo,
                        'qc_item_id'        => $qcItem->id,
                        'qtd'               => $qtd,
                        'valor_unitario'    => $valor_item_unitario,
                        'valor_total'       => $valor_item,
                        'aprovado'          => 1,
                        'tipo'              => $tipo[0]
                    ];
                }
            }


            $tipo_frete = 'CIF';
            $valor_frete = 0;
            if ($quadroDeConcorrencia->hasMaterial() && $qcFornecedor->tipo_frete != 'CIF') {
                $valor_frete = $qcFornecedor->getOriginal('valor_frete');
                $tipo_frete = $qcFornecedor->tipo_frete;
                // Coloca frete em todos os itens que quem material
                foreach ($contratoComMaterial as $ccMaterial) {
                    $vl_frete = $valor_frete/count($contratoComMaterial);

                    $insumo = Insumo::where('codigo', '28675')->first();
                    $contratoItens[$ccMaterial['qcFornecedor']][$ccMaterial['obraId']][] = [
                        'insumo_id'         => $insumo->id,
                        'insumo'            => $insumo,
                        'qc_item_id'        => null,
                        'qtd'               => $vl_frete,
                        'valor_unitario'    => 1,
                        'valor_total'       => $vl_frete,
                        'aprovado'          => 1,
                        'tipo'              => 'SERVIÇO',
                        'frete'             => 1
                    ];
                    $total_contrato[$ccMaterial['qcFornecedor']][$ccMaterial['obraId']] += $vl_frete;
                }
            }
        }

        foreach ($valorMaterial as $qcF => $valorMat) {
            foreach ($valorMat as $obraId => $vl) {
                if ($vl>0) {
                    $insumo = Insumo::where('codigo', '34007')->first();
                    $contratoItens[$qcF][$obraId][] = [
                        'insumo_id'         => $insumo->id,
                        'insumo'            => $insumo,
                        'qc_item_id'        => null,
                        'qtd'               => $vl,
                        'valor_unitario'    => 1,
                        'valor_total'       => $vl,
                        'aprovado'          => 1,
                        'tipo'              => 'MATERIAL'
                    ];
                }
            }
        }

        foreach ($valorLocacao as $qcF => $valorLoc) {
            foreach ($valorLoc as $obraId => $vl) {
                if ($vl>0) {
                    $insumo = Insumo::where('codigo', '37367')->first(); // trocado temporariamente para 37367 pois o 37674 não existe
                    $contratoItens[$qcF][$obraId][] = [
                        'insumo_id'         => $insumo->id,
                        'insumo'            => $insumo,
                        'qc_item_id'        => null,
                        'qtd'               => $vl,
                        'valor_unitario'    => 1,
                        'valor_total'       => $vl,
                        'aprovado'          => 1,
                        'tipo'              => 'SERVIÇO'
                    ];
                }
            }
        }

        foreach ($valorFaturamentoDireto as $qcF => $fatDireto) {
            foreach ($fatDireto as $obraId => $fd) {
                if ($fd>0) {
                    $insumo = Insumo::where('codigo', '30019')->first();
                    $contratoItens[$qcF][$obraId][] = [
                        'insumo_id'         => $insumo->id,
                        'insumo'            => $insumo,
                        'qc_item_id'        => null,
                        'qtd'               => $fd,
                        'valor_unitario'    => 1,
                        'valor_total'       => $fd,
                        'aprovado'          => 1,
                        'tipo'              => 'MATERIAL'
                    ];
                }
            }
        }

        // Verifica se já foi gerado contrato para algum item
        $contratosExistentes = [];

        foreach ($total_contrato as $qcFornecedorId => $obraValores) {
            $qcF = QcFornecedor::find($qcFornecedorId);
            foreach ($obraValores as $obraId => $valorTotal) {
                $contratoExistente = Contrato::where('fornecedor_id', $qcF->fornecedor_id)
                    ->where('obra_id', $obraId)
                    ->where('contrato_status_id', '!=', ContratoStatus::CANCELADO)
                    ->where(function($query) use ($qcF) {
                        $query->where('quadro_de_concorrencia_id', $qcF->quadro_de_concorrencia_id);
                        $query->orWhereHas('itens', function($query) use ($qcF) {
                            $query->whereIn('qc_item_id', $qcF->itens->pluck('qc_item_id')->all());
                        });
                    })
                    ->first();


                if ($contratoExistente) {
                    $contratosExistentes[$qcFornecedorId][$obraId] = $contratoExistente;
                }
            }
        }

        return view('quadro_de_concorrencias.gerar-contrato',
            compact('quadroDeConcorrencia', 'fornecedores', 'contratoItens', 'total_contrato', 'contratosExistentes'));
    }

    public function gerarContratoSave($id, Request $request)
    {
        // Gerar Contrato
        $input = $request->all();
        $retorno = ContratoRepository::criar($input);
        if (!$retorno['success']) {
            return response()->json(['erro'=>$retorno['erro']], 422);
        }
        $input['contratos'] = $retorno['contratos'];
        return response()->json($input);
    }

    public function removerItens(
        QuadroDeConcorrenciaItemRepository $qcItemRepo,
        Request $request
    ) {
        $itens = $qcItemRepo->findWhereIn('id', $request->itens);

        if($itens->isEmpty()) {
            return response()->json([
                'message' => 'Nenhum item encontrado'
            ], 422);
        }

        $itens->each(function($item) {
            $item->delete();
        });

        return response()->json([
            'success' => true
        ]);
    }

    public function dashboard()
    {
        $qcs_por_status = QuadroDeConcorrencia::select([
            'qc_status.nome',
            'qc_status.cor',
            DB::raw('COUNT(1) qtd')
        ])->join('qc_status', 'qc_status.id', 'qc_status_id')
            ->groupBy('qc_status.nome', 'cor')
            ->get();

        $qcs_por_usuario = QuadroDeConcorrencia::select([
            'users.name',
            DB::raw('COUNT(1) qtd')
        ])->join('users', 'users.id', 'quadro_de_concorrencias.user_id')
            ->groupBy('users.name')
            ->get();

        $qcs_por_sla = DB::table(
                        DB::raw(
                            "(SELECT
		                        SUM(verde) as verde,
		                        SUM(vermelho) as vermelho,
		                        SUM(amarelo) as amarelo
                                FROM (
                                    SELECT
                                    IF ( sla > 30, 1, 0) as verde,
                                    IF ( sla < 0, 1, 0) as vermelho,
                                    IF ( sla > 0 AND sla < 30, 1, 0) as amarelo,
                                    id
                                    FROM (
                                        SELECT
	                                        ordem_de_compra_itens.id,
	                                        (
		                                    SELECT
			                                    DATEDIFF(
			                                    	ADDDATE(
			                                    		ordem_de_compra_itens.updated_at,
			                                    		INTERVAL (
			                                    			IFNULL(
			                                    				(
			                                    					SELECT
			                                    						SUM(L.dias_prazo_minimo) prazo
			                                    					FROM
			                                    						lembretes L
			                                    					JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
			                                    					WHERE
			                                    						EXISTS (
			                                    							SELECT
			                                    								1
			                                    							FROM
			                                    								insumos I
			                                    							WHERE
			                                    								I.id = item.insumo_id
			                                    							AND I.insumo_grupo_id = IG.id
			                                    						)
			                                    					AND L.deleted_at IS NULL
			                                    					AND L.lembrete_tipo_id = 2
			                                    				),
			                                    				0
			                                    			)
			                                    		) DAY
			                                    	),
			                                    	CURDATE()
			                                    ) sla
		                                        FROM
		                                        	ordem_de_compra_itens item
		                                        JOIN ordem_de_compras OC ON OC.id = item.ordem_de_compra_id
		                                        JOIN planejamento_compras PC ON PC.insumo_id = item.insumo_id
		                                        AND PC.grupo_id = item.grupo_id
		                                        AND PC.subgrupo1_id = item.subgrupo1_id
		                                        AND PC.subgrupo2_id = item.subgrupo2_id
		                                        AND PC.subgrupo3_id = item.subgrupo3_id
		                                        AND PC.servico_id = item.servico_id
		                                        JOIN planejamentos PL ON PL.id = PC.planejamento_id
		                                        WHERE
		                                        	item.id = ordem_de_compra_itens.id
		                                        AND PL.deleted_at IS NULL
		                                        AND PC.deleted_at IS NULL
		                                        LIMIT 1
	                                        ) AS sla
                                        FROM
                                        	ordem_de_compra_itens
                                        INNER JOIN ordem_de_compras ON ordem_de_compras.id = ordem_de_compra_itens.ordem_de_compra_id
                                        INNER JOIN obras ON obras.id = ordem_de_compra_itens.obra_id
                                        INNER JOIN insumos ON insumos.id = ordem_de_compra_itens.insumo_id
                                        WHERE
                                        	ordem_de_compras.aprovado = 1
                                        AND NOT EXISTS (
	                                        SELECT
	                                        	1
	                                        FROM
	                                        	oc_item_qc_item
	                                        INNER JOIN qc_itens ON qc_itens.id = oc_item_qc_item.qc_item_id
	                                        INNER JOIN quadro_de_concorrencias ON quadro_de_concorrencias.id = qc_itens.quadro_de_concorrencia_id
	                                        WHERE
	                                        	ordem_de_compra_item_id = ordem_de_compra_itens.id
	                                        AND quadro_de_concorrencias.qc_status_id != 6
                                        )
                                        AND ordem_de_compra_itens.deleted_at IS NULL
                                        ) as X
                                    ) as Y
                                ) as Z"
                            )
                        )
                        ->get();

        $qcs_por_media = DB::table(
            DB::raw("
                (SELECT name, ROUND(SUM(dias) / count(user_id),0) as dias
                    FROM
                    (
                        SELECT quadro_de_concorrencias.id, quadro_de_concorrencias.user_id, users.name,
                            (
                                DATEDIFF
                                (
                                    (
                                        SELECT qc_status_log.created_at
                                        FROM qc_status_log
                                        WHERE qc_status_log.qc_status_id = 8
                                        AND quadro_de_concorrencias.id = qc_status_log.quadro_de_concorrencia_id
                                        ORDER BY qc_status_log.created_at
                                        LIMIT 1
                                    ),
                                    (
                                        SELECT qc_status_log.created_at
                                        FROM qc_status_log
                                        WHERE qc_status_log.qc_status_id = 5
                                        AND quadro_de_concorrencias.id = qc_status_log.quadro_de_concorrencia_id
                                        ORDER BY qc_status_log.created_at
                                        LIMIT 1
                                    )
                                )
                            ) dias
                        FROM quadro_de_concorrencias
                        JOIN users ON users.id = quadro_de_concorrencias.user_id
                        WHERE quadro_de_concorrencias.qc_status_id >= 8
                        AND quadro_de_concorrencias.qc_status_id != 10
                    ) as X
                    GROUP BY X.user_id
                ) as Y"
            )
        )->get();

        $qcs_por_media_geral = DB::table(
            DB::raw("
                (SELECT ROUND(SUM(dias) / count(name),0) as media
                    FROM
                    (
                        SELECT name, ROUND(SUM(dias) / count(user_id),0) as dias
                            FROM
                            (
                                SELECT quadro_de_concorrencias.id, quadro_de_concorrencias.user_id, users.name,
                                    (
                                        DATEDIFF
                                        (
                                            (
                                                SELECT qc_status_log.created_at
                                                FROM qc_status_log
                                                WHERE qc_status_log.qc_status_id = 8
                                                AND quadro_de_concorrencias.id = qc_status_log.quadro_de_concorrencia_id
                                                ORDER BY qc_status_log.created_at
                                                LIMIT 1
                                            ),
                                            (
                                                SELECT qc_status_log.created_at
                                                FROM qc_status_log
                                                WHERE qc_status_log.qc_status_id = 5
                                                AND quadro_de_concorrencias.id = qc_status_log.quadro_de_concorrencia_id
                                                ORDER BY qc_status_log.created_at
                                                LIMIT 1
                                            )
                                        )
                                    ) dias
                                FROM quadro_de_concorrencias
                                JOIN users ON users.id = quadro_de_concorrencias.user_id
                                WHERE quadro_de_concorrencias.qc_status_id >= 8
                                AND quadro_de_concorrencias.qc_status_id != 10
                            ) as X
                            GROUP BY X.user_id
                        ) as Z
                ) as Y"
            )
        )->first();

        return view('quadro_de_concorrencias.dashboard', compact('qcs_por_status',
                                                                 'qcs_por_usuario',
                                                                 'qcs_por_sla',
                                                                 'qcs_por_media',
                                                                 'qcs_por_media_geral'));
    }
}
