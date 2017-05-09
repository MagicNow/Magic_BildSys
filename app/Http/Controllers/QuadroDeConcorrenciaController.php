<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use Exception;
use App\DataTables\QcItensDataTable;
use App\DataTables\QuadroDeConcorrenciaDataTable;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\QcInformarValorRequest;
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

        return $qcItensDataTable->render('quadro_de_concorrencias.edit', compact('quadroDeConcorrencia') );
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

        if (empty($quadroDeConcorrencia)) {
            Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

            return redirect(route('quadroDeConcorrencias.index'));
        }
        $show = 1;

        $motivos_reprovacao = WorkflowReprovacaoMotivo::where(function($query){
            $query->where('workflow_tipo_id',2);
            $query->orWhereNull('workflow_tipo_id');
        })->pluck('nome','id')->toArray();

        return $qcItensDataTable->with('show', $show)->render('quadro_de_concorrencias.show', compact('quadroDeConcorrencia', 'show', 'motivos_reprovacao') );
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
        if($quadroDeConcorrencia->qc_status_id == 3){
            Flash::error('Quadro De Concorrencia <strong>EM APROVAÇÃO</strong>, não é possível editar');
            return redirect(route('quadroDeConcorrencias.show',$quadroDeConcorrencia->id));
        }

        return $qcItensDataTable->render('quadro_de_concorrencias.edit', compact('quadroDeConcorrencia') );
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
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->update($input, $id);

        if(!$request->has('fechar_qc')){
            Flash::success('Quadro De Concorrencia ' . trans('common.updated') . ' ' . trans('common.successfully') . '.');
        }else{
            Flash::success('Quadro De Concorrencia colocado em aprovação.');
        }


        if(!$request->has('manter')){
            return redirect(route('quadroDeConcorrencias.index'));
        }else{
            return redirect(route('quadroDeConcorrencias.edit',$quadroDeConcorrencia->id));
        }

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
                ->prepend('Selecione um fornecedor...','')
                ->toArray();
        }

        if(count($fornecedores) === 1) {
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
            ->prepend('Selecione um motivo...','')
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

            if (empty($quadro)) {
                DB::rollback();
                Flash::error('Quadro De Concorrencia '.trans('common.not-found'));

                return back()->withInput();
            }

            $qcFornecedor = $qcFornecedorRepository->buscarPorQuadroEFornecedor(
                $id,
                $request->fornecedor_id
            );

            if($request->reject) {
                $qcFornecedor->update([
                    'desistencia_motivo_id' => $request->desistencia_motivo_id,
                    'desistencia_texto' => $request->desistencia_texto
                ]);
            }

            if($quadro->hasServico() && !$request->reject) {
                $porcentagens = array_values($request->only([
                    'porcentagem_faturamento_direto',
                    'porcentagem_material',
                    'porcentagem_servico',
                ]));

                $porcentagens = array_sum($porcentagens);

                if($porcentagens !== 100) {
                    DB::rollback();
                    Flash::error('As porcentagens não somam 100%');

                    return back()->withInput();
                }

                if(empty(array_filter($request->only(['nf_material', 'nf_servico'])))) {
                    DB::rollback();
                    Flash::error('Selecione pelo menos um tipo de nota fiscal');

                    return back()->withInput();
                }
            }

            if(!$request->reject) {
                foreach($request->equalizacoes as $check) {
                    $check['qc_fornecedor_id'] = $qcFornecedor->id;
                    $check['user_id'] = $request->user()->id;
                    $checksRepository->create($check);
                }

                foreach($request->itens as $qcItemId => $item) {
                    $item['qc_fornecedor_id'] = $qcFornecedor->id;
                    $item['user_id'] = $request->user()->id;
                    $item['qc_item_id'] = $qcItemId;

                    $item['qtd'] = (float) $item['qtd'];

                    if(Str::length($item['valor_unitario'])) {
                        $item['valor_unitario'] = money_to_float($item['valor_unitario']);
                        $item['valor_total'] = $item['valor_unitario'] * $item['qtd'];
                    } else {
                        $item['valor_unitario'] = null;
                    }

                    $qcItemFornecedorRepository->create($item);
                }
            }
        } catch (Exception $e) {
            DB::rollback();
            Flash::error('Ocorreu um erro ao salvar os dados, tente novamente');

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
        if($request->arquivo){
            $qcEqualizacaoTecnicaAnexoExtra->arquivo = $request->arquivo->store('public/anexos');
        }
        $qcEqualizacaoTecnicaAnexoExtra->save();

        return $qcEqualizacaoTecnicaAnexoExtra;
    }

    public function desagrupar($QCid, $id){
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($QCid);

        if (empty($quadroDeConcorrencia)) {
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }

        $qcItem = QcItem::find($id);
        $ordemDeCompraItens = $qcItem->oc_itens;

        foreach ($ordemDeCompraItens as $ocItem){
            $novoQcItem = QcItem::create([
                'quadro_de_concorrencia_id'=>$quadroDeConcorrencia->id,
                'qtd'=> $ocItem->getOriginal('qtd'),
                'insumo_id' => $ocItem->insumo_id
            ]);
            $novoQcItem->oc_itens()->attach($ocItem->id);
        }

        return response()->json(['success'=>$qcItem->delete()]);
    }

    public function agrupar($QCid, Request $request){

        $this->validate($request,['itens'=>'required|min:2'],['itens.min'=>'São necessários no mínimo 2 itens']);

        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($QCid);

        if (empty($quadroDeConcorrencia)) {
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }

        $qcItens = QcItem::whereIn('id',$request->itens)->get();
        $qcItensQtd = QcItem::whereIn('id',$request->itens)->sum('qtd');
        $qcItem = QcItem::whereIn('id',$request->itens)->first();

        // Cria o novo QCitem agrupado
        $novoQcItem = QcItem::create([
            'quadro_de_concorrencia_id'=>$quadroDeConcorrencia->id,
            'qtd'=> $qcItensQtd,
            'insumo_id' => $qcItem->insumo_id
        ]);

        // Amarra os itens de ordem de compra neste novo QC Item
        foreach ($qcItens as $qcItem){
            $ordemDeCompraItens = $qcItem->oc_itens;

            foreach ($ordemDeCompraItens as $ocItem) {
                $novoQcItem->oc_itens()->attach($ocItem->id);
            }
        }
        // Depois de amarrados remove todos os antigos
        $remover = QcItem::whereIn('id',$request->itens)->delete();

        return response()->json(['success'=>$remover]);
    }

    public function acao($QCid, $acao){
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($QCid);

        if (empty($quadroDeConcorrencia)) {
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }

        $acao_executada = $this->quadroDeConcorrenciaRepository->acao($acao,$QCid, Auth::id());
        if($acao_executada[0]){
            $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($QCid);
            return response()->json(['success' => true,'quadroDeConcorrencia'=>$quadroDeConcorrencia,'mensagens'=>$acao_executada[1]]);
        }else{
            return response()->json(['error' => 'Esta ação não foi possível: ' . $acao_executada[1]], 422);
        }
    }
}
