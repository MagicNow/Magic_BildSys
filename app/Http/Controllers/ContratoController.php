<?php

namespace App\Http\Controllers;

use App\DataTables\ContratoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateContratoRequest;
use App\Http\Requests\UpdateContratoRequest;
use App\Repositories\ContratoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
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
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->all();

        $fornecedores = $fornecedorRepository
            ->comContrato()
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->all();

        $obras = $obraRepository
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
    ) {
        $contrato = $this->contratoRepository->findWithoutFail($id);

        if (empty($contrato)) {
            Flash::error('Contrato '.trans('common.not-found'));

            return redirect(route('contratos.index'));
        }

        if($contrato->isStatus(ContratoStatus::EM_APROVACAO)) {
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
            return $itens->where('contrato_id', $id)->where('aprovado', false);
        })
        ->where('contrato_status_id', ContratoStatus::EM_APROVACAO)
        ->get()
        ->map(function($pendencia) {
            $pendencia->workflow =  WorkflowAprovacaoRepository::verificaAprovacoes(
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
                compact('contrato', 'workflowAprovacao', 'motivos', 'aprovado', 'pendencias')
            );
    }

    public function reajustarItem(
        $id,
        ReajustarRequest $request,
        ContratoItemModificacaoRepository $contratoItemModificacaoRepository
    ) {
        $contratoItemModificacaoRepository->reajustar($id, $request->all());

        return response()->json([
            'success' => true
        ]);
    }

    public function distratarItem(
        $id,
        DistratarRequest $request,
        ContratoItemModificacaoRepository $contratoItemModificacaoRepository
    ) {
        $contratoItemModificacaoRepository->distratar($id, $request->qtd);

        return response()->json([
            'success' => true
        ]);
    }

    public function reapropriarItemForm(
        $id,
        ContratoItemRepository $contratoItemRepository
    ) {
        $item = $contratoItemRepository->find($id);

        $itens = $item->qcItem->ordemDeCompraItens()
            ->whereDoesntHave('reapropriacoes')
            ->get()
            ->merge($item->reapropriacoes);

        return view('contratos.modal-reapropriacao', compact('itens', 'item'));
    }

    public function reapropriarItem(
        $id,
        ContratoItemRepository $contratoItemRepository,
        ContratoItemReapropriacaoRepository $contratoItemReapropriacaoRepository,
        ReapropriarRequest $request
    ) {
        $item = $contratoItemRepository->find($id);

        $contratoItemReapropriacaoRepository->reapropriar($item, $request->all());

        return response()->json([
            'success' => true
        ]);
    }
    
    public function imprimirContrato($id){
        return ContratoRepository::geraImpressao($id);
    }

    public function edit($id){
        $contrato = $this->contratoRepository->findWithoutFail($id);

        if (empty($contrato)) {
            Flash::error('Contrato '.trans('common.not-found'));

            return redirect(route('contratos.index'));
        }

        return view('contratos.edit', compact('contrato'));
    }
}
