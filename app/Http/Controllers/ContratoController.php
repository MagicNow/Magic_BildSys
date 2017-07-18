<?php

namespace App\Http\Controllers;

use App\DataTables\ContratoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateContratoRequest;
use App\Http\Requests\EditarItemRequest;
use App\Http\Requests\UpdateContratoRequest;
use App\Models\ContratoStatusLog;
use App\Models\Fornecedor;
use App\Models\Insumo;
use App\Models\McMedicaoPrevisao;
use App\Models\MemoriaCalculo;
use App\Models\NomeclaturaMapa;
use App\Models\Obra;
use App\Models\ObraTorre;
use App\Models\Planejamento;
use App\Models\WorkflowAprovacao;
use App\Repositories\CodeRepository;
use App\Repositories\ContratoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Response;
use App\Repositories\Admin\FornecedoresRepository;
use App\Repositories\Admin\ObraRepository;
use App\Repositories\ContratoStatusRepository;
use Illuminate\Support\Facades\App;
use App\Repositories\WorkflowAprovacaoRepository;
use Illuminate\Http\Request;
use App\Repositories\Admin\WorkflowReprovacaoMotivoRepository;
use App\Models\WorkflowTipo;
use App\DataTables\ContratoItemDataTable;
use App\Models\ContratoItem;
use App\Http\Requests\ReajustarRequest;
use App\Http\Requests\DistratarRequest;
use App\Http\Requests\ReapropriarRequest;
use App\Http\Requests\AtualizarValorRequest;
use App\Repositories\ContratoItemModificacaoRepository;
use App\Repositories\ContratoItemRepository;
use App\Models\ContratoStatus;
use App\Models\ContratoItemModificacao;
use App\Repositories\ContratoItemApropriacaoRepository;
use App\Models\WorkflowAlcada;
use App\Models\ContratoItemApropriacao;
use App\Models\Cnae;
use App\Repositories\SolicitacaoEntregaRepository;

class ContratoController extends AppBaseController
{
    /** @var  ContratoRepository */
    private $contratoRepository;

    public function __construct(ContratoRepository $contratoRepo)
    {
        $this->contratoRepository = $contratoRepo;
    }

    /**
     * Display a listing of the Contrato.
     *
     * @param ContratoDataTable $contratoDataTable
     * @return Response
     */
    public function index(
        ContratoDataTable $contratoDataTable,
        FornecedoresRepository $fornecedorRepository,
        ObraRepository $obraRepository,
        ContratoStatusRepository $contratoStatusRepository
    ) {
        $status = $contratoStatusRepository
            ->orderBy('nome', 'ASC')
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->all();

        $fornecedores = $fornecedorRepository
            ->orderBy('nome', 'ASC')
            ->comContrato()
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->all();

        $obras = $obraRepository
            ->orderBy('nome', 'ASC')
            ->comContrato()
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->all();

        return $contratoDataTable->render(
            'contratos.index',
            compact('status', 'fornecedores', 'obras')
        );
    }

    public function show(
        $id,
        Request $request,
        WorkflowReprovacaoMotivoRepository $workflowReprovacaoMotivoRepository,
        ContratoItemApropriacaoRepository $apropriacaoRepository,
        ContratoItemRepository $contratoItemRepository,
        FornecedoresRepository $fornecedorRepository
    ) {
        $contrato = $this->contratoRepository->findWithoutFail($id);

        if (empty($contrato)) {
            Flash::error('Contrato ' . trans('common.not-found'));

            return redirect(route('contratos.index'));
        }

        $orcamentoInicial = $totalAGastar = $realizado = $totalSolicitado = 0;

        $orcamentoInicial = $apropriacaoRepository->orcamentoInicial(
            $contrato
        );

        $avaliado_reprovado = [];

        $fornecedor = $fornecedorRepository->updateImposto($contrato->fornecedor_id);

        $alcadas = WorkflowAlcada::where('workflow_tipo_id', WorkflowTipo::CONTRATO)
            ->orderBy('ordem', 'ASC')
            ->get();

        $alcadas_count = $alcadas->count();

        if ($contrato->isStatus(ContratoStatus::EM_APROVACAO)) {
            $workflowAprovacao = WorkflowAprovacaoRepository::verificaAprovacoes(
                'Contrato',
                $contrato->id,
                $request->user()
            );

            foreach ($alcadas as $alcada) {
                $avaliado_reprovado[$alcada->id] = WorkflowAprovacaoRepository::verificaTotalJaAprovadoReprovado(
                    'Contrato',
                    $contrato->irmaosIds(),
                    null,
                    null,
                    $alcada->id);

                $avaliado_reprovado[$alcada->id]['aprovadores'] = WorkflowAprovacaoRepository::verificaQuantidadeUsuariosAprovadores(
                    WorkflowTipo::find(WorkflowTipo::CONTRATO),
                    $contrato->obra_id,
                    $alcada->id
                );

                $avaliado_reprovado[$alcada->id] ['faltam_aprovar'] = WorkflowAprovacaoRepository::verificaUsuariosQueFaltamAprovar(
                    'Contrato',
                    WorkflowTipo::CONTRATO,
                    $contrato->obra_id,
                    $alcada->id,
                    [$alcada->id]
                );

                // Data do início da  Alçada
                if ($alcada->ordem === 1) {
                    $contrato_log = $contrato->logs()
                        ->where('contrato_status_id', 4)->first();

                    if ($contrato_log) {
                        $avaliado_reprovado[$alcada->id] ['data_inicio'] = $contrato_log->created_at
                            ->format('d/m/Y H:i');
                    }
                } else {
                    $primeiro_voto = WorkflowAprovacao::where('aprovavel_type', 'App\\Models\\Contrato')
                        ->where('aprovavel_id', $contrato->id)
                        ->where('workflow_alcada_id', $alcada->id)
                        ->orderBy('id', 'ASC')
                        ->first();
                    if ($primeiro_voto) {
                        $avaliado_reprovado[$alcada->id]['data_inicio'] = $primeiro_voto->created_at->format('d/m/Y H:i');
                    }
                }
            }
        }

        $aprovado = $contrato->isStatus(ContratoStatus::APROVADO);

        $motivos = $workflowReprovacaoMotivoRepository
            ->porTipo(WorkflowTipo::CONTRATO)
            ->pluck('nome', 'id')
            ->prepend('Motivos...', '')
            ->all();

        $pendencias = ContratoItemModificacao::whereHas('item', function ($itens) use ($id) {
            return $itens->where('contrato_id', $id)->where('pendente', true);
        })
        ->where('contrato_status_id', ContratoStatus::EM_APROVACAO)
        ->get()
        ->map(function ($pendencia) {
            $pendencia->workflow = WorkflowAprovacaoRepository::verificaAprovacoes(
                'ContratoItemModificacao',
                $pendencia->id,
                auth()->user()
            );

            return $pendencia;
        });

        $status = $contrato->status->nome;

        $isEmAprovacao = $contrato->em_aprovacao;

        $itens = $isEmAprovacao
            ? $apropriacaoRepository->forContratoApproval($contrato)
            : $contratoItemRepository->forContratoDetails($contrato);

        $iss = Cnae::$iss;

        return view('contratos.show', compact(
            'isEmAprovacao',
            'contrato',
            'orcamentoInicial',
            'itens',
            'workflowAprovacao',
            'motivos',
            'aprovado',
            'pendencias',
            'alcadas_count',
            'avaliado_reprovado',
            'status',
            'fornecedor',
            'iss'
        ));
    }

    public function reajustar(
        $contrato_item_id,
        ReajustarRequest $request,
        ContratoItemModificacaoRepository $contratoItemModificacaoRepository
    ) {
        $contratoItemModificacaoRepository->reajustar($contrato_item_id, $request->all());

        return response()->json([
            'success' => true
        ]);
    }

    public function distratar(
        $contrato_item_id,
        DistratarRequest $request,
        ContratoItemModificacaoRepository $contratoItemModificacaoRepository
    ) {
        $contratoItemModificacaoRepository->distratar(
            $contrato_item_id,
            $request->distrato
        );

        return response()->json([
            'success' => true
        ]);
    }

    public function apropriacoes(
        $id,
        ContratoItemRepository $contratoItemRepository
    ) {
        $item = $contratoItemRepository->find($id);

        $itens = $item->apropriacoes->filter(function($apropriacao) {
            return $apropriacao->qtd_sobra;
        });

        return view('contratos.' . request('view'), compact('itens', 'item'));
    }

    public function reapropriarItem(
        $id,
        ContratoItemRepository $contratoItemRepository,
        ContratoItemApropriacaoRepository $contratoItemReapropriacaoRepository,
        ReapropriarRequest $request
    ) {
        $item = $contratoItemRepository->find($id);

        $contratoItemReapropriacaoRepository->reapropriar($item, $request->all());

        return response()->json([
            'success' => true
        ]);
    }

    public function editarItem(
        $id,
        ContratoItemRepository $contratoItemRepository,
        EditarItemRequest $request
    ) {
        $contratoItemRepository->editarAditivo($id, $request->all());

        return response()->json([
            'success' => true
        ]);
    }

    public function imprimirContrato($id)
    {
        return response()->file(storage_path('/app/public/') . str_replace('storage/', '', ContratoRepository::geraImpressao($id)));
    }

    public function edit($id)
    {
        $contrato = $this->contratoRepository->findWithoutFail($id);

        if (empty($contrato)) {
            Flash::error('Contrato ' . trans('common.not-found'));

            return redirect(route('contratos.index'));
        }

        return view('contratos.edit', compact('contrato'));
    }

    public function update($id, Request $request)
    {
        $contrato = $this->contratoRepository->findWithoutFail($id);

        $type_resposta = 'info';
        $resposta = 'Contrato não modificado.';

        if (empty($contrato)) {
            Flash::error('Contrato ' . trans('common.not-found'));

            return redirect(route('contratos.index'));
        }

        $workflow_aprovacao = WorkflowAprovacao::where('aprovavel_type', 'App\Models\Contrato')
            ->where('aprovavel_id', $contrato->id)
            ->first();

        if (count($request->quantidade)) {
            foreach ($request->quantidade as $item) {
                $contrato_item = ContratoItem::find($item['id']);
                if ($contrato_item && $item['qtd'] != '' && $contrato_item->qtd != money_to_float($item['qtd']) && $workflow_aprovacao) {
                    $contrato_item->qtd = money_to_float($item['qtd']);
                    $contrato_item->valor_total = money_to_float($item['qtd']) * money_to_float($contrato_item->valor_unitario);
                    $contrato_item->update();

                    $contrato->contrato_status_id = 1;
                    $contrato->update();

                    $workflow_aprovacao->delete();

                    $type_resposta = 'success';
                    $resposta = 'Contrato em aprovação.';
                }
            }
        }

        Flash::$type_resposta($resposta);

        return redirect(route('contratos.index'));
    }

    public function validaEnvioContrato($id, Request $request)
    {
        $contrato = $this->contratoRepository->findWithoutFail($id);

        if (empty($contrato)) {
            Flash::error('Contrato ' . trans('common.not-found'));
            return redirect(route('contratos.index'));
        }

        if ($request->arquivo) {
            $destinationPath = CodeRepository::saveFile($request->arquivo, 'contratos/' . $contrato->id);

            $contrato->arquivo = $destinationPath;
            $contrato->save();
            $acao = 'Arquivo enviado!';


            if ($contrato->contrato_status_id == 4) {
                $contrato->contrato_status_id = 5;
                $contrato->save();
                ContratoStatusLog::create([
                    'contrato_id' => $contrato->id,
                    'contrato_status_id' => $contrato->contrato_status_id,
                    'user_id' => auth()->id()
                ]);
                $acao = 'Arquivo enviado e Contrato Liberado!';
            }

            Flash::success($acao);
            return redirect(route('contratos.show', $contrato->id));
        }

        Flash::error('É necessário enviar um arquivo!');
        return redirect(route('contratos.show', $contrato->id));
    }

    public function atualizarValor(Request $request)
    {
        $obras = Obra::whereHas('contratos', function ($query) {
            $query->where('contrato_status_id', 5);
        })->whereHas('users', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->orderBy('nome', 'ASC')
            ->pluck('nome', 'id')
            ->toArray();
        return view('contratos.atualizar-valor', compact('obras'));
    }

    public function pegaFornecedoresPelasObras(Request $request)
    {
        $this->validate($request, ['obras'=>'required|min:1']);
        $obras = $request->obras;
        return Fornecedor::whereHas('contratos', function ($query) use ($obras) {
            $query->where('contrato_status_id', 5);
            $query->whereIn('obra_id', $obras);
        })
            ->select([
                'id',
                DB::raw("CONCAT(nome,' - ',cnpj) as nome"),
            ])
            ->orderBy('nome', 'ASC')
            ->paginate();
    }

    public function insumosPorFornecedor(Request $request)
    {
        $fornecedor_id = $request->fornecedor;
        $obras = $request->obras;
        return Insumo::whereHas('contratoItem', function ($query) use ($fornecedor_id, $obras) {
            $query->join('contratos', 'contratos.id', 'contrato_itens.contrato_id');
            $query->where('contrato_status_id', 5);
            $query->where('fornecedor_id', $fornecedor_id);
            $query->whereIn('obra_id', $obras);
        })
            ->join('contrato_itens', 'contrato_itens.insumo_id', 'insumos.id')
            ->select([
                'insumos.id',
                'contrato_itens.id as contrato_item_id',
                DB::raw("CONCAT(insumos.codigo,' - ',insumos.nome) as nome"),
            ])
            ->join('contratos', 'contratos.id', 'contrato_itens.contrato_id')
            ->where('fornecedor_id', $fornecedor_id)
            ->whereIn('obra_id', $obras)
            ->orderBy('nome', 'ASC')
            ->paginate();
    }

    public function insumoValor(Request $request)
    {
        $item_id = $request->insumo;
        return ContratoItem::where('id', $item_id)
            ->with('insumo')
            ->first();
    }

    public function atualizarValorSave(
        AtualizarValorRequest $request,
        ContratoItemModificacaoRepository $contratoItemModificacaoRepository
    ) {
        $reajustes =  $contratoItemModificacaoRepository->reajusteFornecedor($request->fornecedor_id, $request->obra_id, $request->valor_unitario);

        if (count($reajustes)) {
            Flash::success('Reajustes de valores criados.');
        } else {
            Flash::error('Nenhum reajuste foi criado.');
        }

        return redirect(route('contratos.index'));
    }

    public function memoriaDeCalculo($contrato_id, $contrato_item_apropriacao_id, Request $request)
    {
        $contrato = $this->contratoRepository->findWithoutFail($contrato_id);
        $contrato_item_apropriacao = ContratoItemApropriacao::find($contrato_item_apropriacao_id);
        $filtro_estruturas = [];
        $planejamentos = [];

        if (empty($contrato)) {
            Flash::error('Contrato ' . trans('common.not-found'));

            return redirect(route('contratos.show', $contrato_id));
        }

        if (empty($contrato_item_apropriacao)) {
            Flash::error('Item do contrato ' . trans('common.not-found'));

            return redirect(route('contratos.show', $contrato_id));
        }

        if (empty($contrato->fornecedor)) {
            Flash::error('Fornecedor do contrato ' . trans('common.not-found'));

            return redirect(route('contratos.show', $contrato_id));
        }

        $insumo = Insumo::find($contrato_item_apropriacao->insumo_id);

        if (empty($insumo)) {
            Flash::error('Insumo ' . trans('common.not-found'));

            return redirect(route('contratos.show', $contrato_id));
        }

        $tarefas = Planejamento::where('obra_id', $contrato->obra_id)
            ->pluck('tarefa', 'id')
            ->prepend('', '')
            ->toArray();

        $obra_torres = ObraTorre::where('obra_id', $contrato->obra_id)
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->toArray();

        $memoria_de_calculo = MemoriaCalculo::select([
            DB::raw('CONCAT(
                        nome, " - ", 
                        (
                        CASE
                            modo
                        WHEN
                            "T"
                        THEN
                            "Torre"
                        WHEN
                            "C"
                        THEN
                            "Cartela"
                        WHEN
                            "U"
                        THEN
                            "Unidade"
                        END
                        )
                    ) as nome'),
            'id'
        ])
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->toArray();

        $previsoes = McMedicaoPrevisao::where('insumo_id',  $insumo->id)
            ->where('contrato_item_apropriacao_id', $contrato_item_apropriacao->id)
            ->where('contrato_item_id', $contrato_item_apropriacao->contrato_item_id)
            ->get();

        if(count($previsoes)) {
            $memoria_de_calculo_id = $previsoes->first()->memoriaCalculoBloco->memoriaCalculo->id;
        }else{
            $memoria_de_calculo_id = $request->memoria_de_calculo;
        }

        if($memoria_de_calculo_id) {
            $memoriaCalculo = MemoriaCalculo::find($memoria_de_calculo_id);

            if (empty($memoriaCalculo)) {
                Flash::error('Memoria Calculo '.trans('common.not-found'));

                return redirect(route('memoriaCalculos.index'));
            }

            $filtro_estruturas = NomeclaturaMapa::where('tipo', 1)
                ->where('apenas_cartela',($memoriaCalculo->modo=='C'?'1':'0') )
                ->where('apenas_unidade',($memoriaCalculo->modo=='U'?'1':'0') )
                ->pluck('nome', 'id')
                ->prepend('', '')
                ->toArray();

            // Montar os blocos
            $blocos = [];
            $memoriaBlocos = $memoriaCalculo->blocos()
                ->orderBy('ordem_bloco','ASC')
                ->orderBy('ordem_linha','ASC')
                ->orderBy('ordem','ASC')
                ->with('estruturaObj','pavimentoObj','trechoObj')
                ->get();
            if(count($memoriaBlocos)){
                $estruturas = [];
                $pavimentos = [];
                $trechos = [];
                foreach ($memoriaBlocos as $memoriaBloco) {
                    $editavel = 1;
                    if(!isset($estruturas[$memoriaBloco->estrutura])){
                        $estruturas[$memoriaBloco->estrutura] = [
                            'id'=>   $memoriaBloco->ordem,
                            'objId'=>   $memoriaBloco->estrutura,
                            'nome'=> $memoriaBloco->estruturaObj->nome,
                            'ordem' => $memoriaBloco->ordem_bloco,
                            'itens' => [],
                            'editavel'=>$editavel
                        ];
                    }else{
                        if(!$editavel){
                            $estruturas[$memoriaBloco->estrutura]['editavel'] = $editavel;
                        }
                    }

                    if(!isset($pavimentos[$memoriaBloco->estrutura][$memoriaBloco->pavimento])){
                        $countEstrutura = !isset($pavimentos[$memoriaBloco->estrutura])?1:count($pavimentos[$memoriaBloco->estrutura])+1;
                        $pavimentos[$memoriaBloco->estrutura][$memoriaBloco->pavimento] = [
                            'id'=>   $countEstrutura,
                            'objId'=>   $memoriaBloco->pavimento,
                            'nome'=> $memoriaBloco->pavimentoObj->nome,
                            'ordem' => $memoriaBloco->ordem_linha,
                            'estrutura' => $memoriaBloco->estrutura,
                            'itens' => [],
                            'editavel'=>$editavel
                        ];
                    }else{
                        if(!$editavel){
                            $pavimentos[$memoriaBloco->estrutura][$memoriaBloco->pavimento]['editavel'] = $editavel;
                        }
                    }

                    if(!isset($trechos[$memoriaBloco->estrutura][$memoriaBloco->pavimento][$memoriaBloco->trecho])){
                        $countTrecho = !isset($trechos[$memoriaBloco->estrutura][$memoriaBloco->pavimento])?1:count($trechos[$memoriaBloco->estrutura][$memoriaBloco->pavimento])+1;
                        $trechos[$memoriaBloco->estrutura][$memoriaBloco->pavimento][$memoriaBloco->id] = [
                            'id'=>   $countTrecho,
                            'blocoId'=>   $memoriaBloco->id,
                            'objId'=>   $memoriaBloco->trecho,
                            'nome'=> $memoriaBloco->trechoObj->nome,
                            'ordem' => $memoriaBloco->ordem,
                            'estrutura' => $memoriaBloco->estrutura,
                            'pavimento' => $memoriaBloco->pavimento,
                            'editavel'=>$editavel
                        ];
                    }else{
                        if(!$editavel){
                            $trechos[$memoriaBloco->estrutura][$memoriaBloco->pavimento][$memoriaBloco->id]['editavel'] = $editavel;
                        }
                    }

                }
                // organiza a array
                foreach ($trechos as $estrutura_id => $estruturaTrechos){
                    foreach ($estruturaTrechos as $pavimento_id => $pavimentoTrechos) {
                        foreach ($pavimentoTrechos as $trecho) {
                            $pavimentos[$trecho['estrutura']][$trecho['pavimento']]['itens'][] = $trecho;
                        }
                    }

                }

                foreach ($pavimentos as $estrutura_id => $pavimentos_internos){
                    foreach ($pavimentos_internos as $pavimento_interno){
                        $estruturas[$pavimento_interno['estrutura']]['itens'][] = $pavimento_interno;
                    }
                }

                foreach ($estruturas as $estrutura){
                    $blocos[$estrutura['ordem']] = $estrutura;
                }

            }
            ksort($blocos);

            $planejamentos = Planejamento::where('obra_id', $memoriaCalculo->obra_id)
                ->where('resumo', 'Sim')
                ->select([
                    DB::raw("CONCAT(tarefa,' - ',DATE_FORMAT( data, '%d/%m/%Y')) as tarefa"),
                    'id'
                ])
                ->pluck('tarefa','id')
                ->prepend('', '')
                ->toArray();
        }

        return view('contratos.memoria_de_calculo.previsao',
            compact(
                'contrato',
                'contrato_item_apropriacao',
                'insumo',
                'tarefas',
                'memoria_de_calculo',
                'obra_torres',
                'previsoes',
                'filtro_estruturas',
                'blocos',
                'memoriaCalculo',
                'planejamentos'
            )
        );
    }

    public function memoriaDeCalculoSalvar(Request $request)
    {
        if(count($request->itens)) {
            foreach ($request->itens as $item) {
                $item['qtd'] = money_to_float($item['qtd']);
                if(@isset($item['id'])){
                    $previsao = McMedicaoPrevisao::find($item['id']);
                    $previsao->update($item);
                } else {
                    $previsao = new McMedicaoPrevisao($item);
                    $previsao->insumo_id = $request->insumo_id;
                    $previsao->unidade_sigla = $request->unidade_sigla;
                    $previsao->contrato_item_apropriacao_id = $request->contrato_item_apropriacao_id;
                    $previsao->contrato_item_id = $request->contrato_item_id;
                    $previsao->obra_torre_id = $request->obra_torre_id;
                    $previsao->user_id = Auth::id();
                    $previsao->save();
                }
            }
        }

        Flash::success('Previsão de memória de cálculo salva com sucesso!');

        return redirect(route('contratos.index'));
    }

    public function memoriaDeCalculoExcluirPrevisao(Request $request)
    {
        $previsao = McMedicaoPrevisao::find($request->id);

        if ($previsao) {
            $previsao->delete();
        }

        return response()->json(true);
    }
    public function solicitarEntrega(
        $contrato_id,
        ContratoItemApropriacaoRepository $apropriacaoRepository
    ) {
        $contrato = $this->contratoRepository->find($contrato_id)
            ->load('materiais.apropriacoes');

        if(!$contrato->hasMaterial()) {
            Flash::warning('Este contrato não contém material para entrega');
            return redirect()->route('contratos.show', $contrato->id);
        }

        if(!$contrato->pode_solicitar_entrega) {
            Flash::warning('Ainda não é possível realizar solicitações de entrega neste contrato');
            return redirect()->route('contratos.show', $contrato->id);
        }

        if($contrato->materiais->isEmpty()) {
            Flash::warning('Este contrato não contem material');
            return redirect()->route('contratos.show', $contrato->id);
        }

        $apropriacoes = $contrato->materiais->pluck('apropriacoes')->collapse();

        return view(
            'contratos.solicitacao_entrega.index',
            compact('contrato', 'apropriacoes')
        );
    }

    public function solicitarEntregaSave(
        Request $request,
        SolicitacaoEntregaRepository $repository,
        $contrato_id
    ) {
        $input = $request->all();
        $input['contrato_id'] = $contrato_id;
        $solicitacao = $repository->create($input);

        return response()->json([
            'success' => true
        ]);
    }
}
