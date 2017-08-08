<?php

namespace App\Http\Controllers;

use App\DataTables\MedicaoDataTable;
use App\DataTables\MedicaoServicoDataTable;
use App\DataTables\Scopes\MedicaoServicoScope;
use App\Http\Requests;
use App\Http\Requests\CreateMedicaoServicoRequest;
use App\Http\Requests\UpdateMedicaoServicoRequest;
use App\Models\WorkflowAlcada;
use App\Models\WorkflowAprovacao;
use App\Models\WorkflowReprovacaoMotivo;
use App\Models\WorkflowTipo;
use App\Repositories\MedicaoServicoRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\WorkflowAprovacaoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WorkflowNotification;

class MedicaoServicoController extends AppBaseController
{
    /** @var  MedicaoServicoRepository */
    private $medicaoServicoRepository;

    public function __construct(MedicaoServicoRepository $medicaoServicoRepo)
    {
        $this->medicaoServicoRepository = $medicaoServicoRepo;
    }

    /**
     * Display a listing of the MedicaoServico.
     *
     * @param MedicaoServicoDataTable $medicaoServicoDataTable
     * @return Response
     */
    public function index(MedicaoServicoDataTable $medicaoServicoDataTable)
    {
        return $medicaoServicoDataTable->addScope(new MedicaoServicoScope())->render('medicao_servicos.index');
    }

    /**
     * Show the form for creating a new MedicaoServico.
     *
     * @return Response
     */
    public function create()
    {
        return view('medicao_servicos.create');
    }

    /**
     * Store a newly created MedicaoServico in storage.
     *
     * @param CreateMedicaoServicoRequest $request
     *
     * @return Response
     */
    public function store(CreateMedicaoServicoRequest $request)
    {
        $input = $request->all();

        $medicaoServico = $this->medicaoServicoRepository->create($input);

        Flash::success('Medicao Servico '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('medicaoServicos.index'));
    }

    /**
     * Display the specified MedicaoServico.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(MedicaoDataTable $medicaoDataTable,$id)
    {
        $medicaoServico = $this->medicaoServicoRepository->findWithoutFail($id);

        if (empty($medicaoServico)) {
            Flash::error('Medição de Serviço não encontrada');

            return redirect(route('medicoes.index'));
        }

        // Limpa qualquer notificação que tiver deste item
        NotificationRepository::marcarLido(WorkflowTipo::MEDICAO,$id);

        $avaliado_reprovado = [];
        $itens_ids = $medicaoServico->medicoes()->pluck('id', 'id')->toArray();
        $aprovavelTudo = WorkflowAprovacaoRepository::verificaAprovaGrupo('Medicao', $itens_ids, auth()->user());
        $alcadas = WorkflowAlcada::where('workflow_tipo_id', WorkflowTipo::MEDICAO)->orderBy('ordem', 'ASC')->get();

        if ($medicaoServico->finalizado) { //Em Aprovação
            foreach ($alcadas as $alcada) {
                $avaliado_reprovado[$alcada->id] = WorkflowAprovacaoRepository::verificaTotalJaAprovadoReprovado(
                    'Medicao',
                    $itens_ids,
                    null,
                    null,
                    $alcada->id);

                $avaliado_reprovado[$alcada->id] ['aprovadores'] = WorkflowAprovacaoRepository::verificaQuantidadeUsuariosAprovadores(
                    WorkflowTipo::find(WorkflowTipo::MEDICAO),
                    $medicaoServico->obra_id,
                    $alcada->id);

                $avaliado_reprovado[$alcada->id] ['faltam_aprovar'] = WorkflowAprovacaoRepository::verificaUsuariosQueFaltamAprovar(
                    'Medicao',
                    WorkflowTipo::MEDICAO,
                    $medicaoServico->obra_id,
                    $alcada->id,
                    $itens_ids);

                // Data do início da Alçada
                if ($alcada->ordem === 1) {

                    $avaliado_reprovado[$alcada->id] ['data_inicio'] = $medicaoServico->updated_at->format('d/m/Y H:i');

                } else {
                    $primeiro_voto = WorkflowAprovacao::where('aprovavel_type', 'App\\Models\\Medicao')
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

        $motivos_reprovacao = WorkflowReprovacaoMotivo::where(function ($query) {
            $query->where('workflow_tipo_id', WorkflowTipo::MEDICAO);
            $query->orWhereNull('workflow_tipo_id');
        })
            ->pluck('nome', 'id')
            ->toArray();

        return $medicaoDataTable->servico($id)->render('medicao_servicos.show',compact('medicaoServico','aprovavelTudo','alcadas','motivos_reprovacao'));
    }

    /**
     * Show the form for editing the specified MedicaoServico.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit(MedicaoDataTable $medicaoDataTable,$id)
    {
        $medicaoServico = $this->medicaoServicoRepository->findWithoutFail($id);

        if (empty($medicaoServico)) {
            Flash::error('Medição de Serviço não encontrada');

            return redirect(route('medicaoServicos.index'));
        }
        return $medicaoDataTable->servico($id)->render('medicao_servicos.edit',compact('medicaoServico'));
    }

    /**
     * Update the specified MedicaoServico in storage.
     *
     * @param  int              $id
     * @param UpdateMedicaoServicoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMedicaoServicoRequest $request)
    {
        $medicaoServico = $this->medicaoServicoRepository->findWithoutFail($id);

        if (empty($medicaoServico)) {
            Flash::error('Medição de Serviço não encontrada');

            return redirect(route('medicaoServicos.index'));
        }
        $input = $request->all();
        if(!isset($input['finalizado'])){
            $input['finalizado'] = 0;
        }else{
            if($input['finalizado']){
                $aprovadores = WorkflowAprovacaoRepository::usuariosDaAlcadaAtual($medicaoServico);
                Notification::send($aprovadores, new WorkflowNotification($medicaoServico));
            }
        }
        $input['aprovado'] = null;
        $medicaoServico = $this->medicaoServicoRepository->update($input, $id);

        Flash::success('Medição do Serviço atualizada '.trans('common.successfully').'.');

        return redirect(route('medicoes.index'));
    }

    /**
     * Remove the specified MedicaoServico from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $medicaoServico = $this->medicaoServicoRepository->findWithoutFail($id);

        if (empty($medicaoServico)) {
            Flash::error('Medição de Serviço não encontrada');

            return redirect(route('medicaoServicos.index'));
        }

        $this->medicaoServicoRepository->delete($id);

        Flash::success('Medicao Servico '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('medicaoServicos.index'));
    }
}
