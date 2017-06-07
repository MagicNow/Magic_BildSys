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
use App\Models\Obra;
use App\Models\WorkflowAprovacao;
use App\Repositories\CodeRepository;
use App\Repositories\ContratoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
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
use App\Repositories\ContratoItemReapropriacaoRepository;

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
        ContratoItemDataTable $contratoItemDataTable
    )
    {
        $contrato = $this->contratoRepository->findWithoutFail($id);

        if (empty($contrato)) {
            Flash::error('Contrato ' . trans('common.not-found'));

            return redirect(route('contratos.index'));
        }

        $valor_inicial = $contrato->itens()
            ->where('aprovado', 1)
            ->get()
            ->pluck('qcItem')
            ->pluck('ordemDeCompraItens')
            ->collapse()
            ->sum('valor_total');


        if ($contrato->isStatus(ContratoStatus::EM_APROVACAO)) {
            $workflowAprovacao = WorkflowAprovacaoRepository::verificaAprovacoes(
                'Contrato',
                $contrato->id,
                $request->user()
            );
        }

        $aprovado = $contrato->isStatus(ContratoStatus::APROVADO);

        $motivos = $workflowReprovacaoMotivoRepository
            ->porTipo(WorkflowTipo::CONTRATO)
            ->pluck('nome', 'id')
            ->prepend('Motivos...', '')
            ->all();

        $pendencias = ContratoItemModificacao::whereHas('item', function($itens) use ($id) {
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

        return $contratoItemDataTable
            ->setContrato($contrato)
            ->render(
                'contratos.show',
                compact('contrato', 'workflowAprovacao', 'motivos', 'aprovado', 'pendencias', 'valor_inicial')
            );
    }

    public function reajustarItem(
        $id,
        ReajustarRequest $request,
        ContratoItemModificacaoRepository $contratoItemModificacaoRepository
    )
    {
        $contratoItemModificacaoRepository->reajustar($id, $request->all());

        return response()->json([
            'success' => true
        ]);
    }

    public function distratarItem(
        $id,
        DistratarRequest $request,
        ContratoItemModificacaoRepository $contratoItemModificacaoRepository
    )
    {
        $contratoItemModificacaoRepository->distratar($id, $request->qtd);

        return response()->json([
            'success' => true
        ]);
    }

    public function reapropriarItemForm(
        $id,
        ContratoItemRepository $contratoItemRepository
    )
    {
        $item = $contratoItemRepository->find($id);

        $itens = $item->qcItem->ordemDeCompraItens;

        $reapropriacoes = $item->reapropriacoes;

        $reapropriacoes->each(function ($re) use (&$itens) {
            if ($re->ordem_de_compra_item_id) {
                $item = $itens->where('id', $re->ordem_de_compra_item_id)->shift();
                $item->qtd = $item->qtd - $re->qtd;
                $item->modificado_por = true;
                $itens->push($item);
            }
        });

        $reapropriacoes->each(function ($re) use (&$reapropriacoes) {
            if ($re->contrato_item_reapropriacao_id) {
                $_re = $reapropriacoes->where('id', $re->contrato_item_reapropriacao_id)->shift();
                $_re->qtd = $_re->qtd - $re->qtd;
                $_re->modificado_por = true;
                $reapropriacoes->push($_re);
            }
        });

        $itens = $itens->merge($reapropriacoes)
            ->filter(function ($item) {
                return (float)$item->qtd;
            })
            ->sortBy(function ($item) {
                return $item->created_at->getTimestamp();
            });


        return view('contratos.modal-reapropriacao', compact('itens', 'item'));
    }

    public function reapropriarItem(
        $id,
        ContratoItemRepository $contratoItemRepository,
        ContratoItemReapropriacaoRepository $contratoItemReapropriacaoRepository,
        ReapropriarRequest $request
    )
    {
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
    )
    {

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
        $this->validate($request,['obras'=>'required|min:1']);
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

    public function atualizarValorSave(AtualizarValorRequest $request,
                                       ContratoItemModificacaoRepository $contratoItemModificacaoRepository)
    {
        $reajustes =  $contratoItemModificacaoRepository->reajusteFornecedor($request->fornecedor_id, $request->obra_id, $request->valor_unitario);
        if(count($reajustes)){
            Flash::success('Reajustes de valores criados.');
        }else{
            Flash::error('Nenhum reajuste foi criado.');
        }
        return redirect(route('contratos.index'));
    }
}
