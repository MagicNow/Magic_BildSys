<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\WorkflowAlcadaDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateWorkflowAlcadaRequest;
use App\Http\Requests\Admin\UpdateWorkflowAlcadaRequest;
use App\Models\User;
use App\Models\WorkflowAlcada;
use App\Repositories\Admin\WorkflowAlcadaRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\WorkflowTipo;

class WorkflowAlcadaController extends AppBaseController
{
    /** @var  WorkflowAlcadaRepository */
    private $workflowAlcadaRepository;

    public function __construct(WorkflowAlcadaRepository $workflowAlcadaRepo)
    {
        $this->workflowAlcadaRepository = $workflowAlcadaRepo;
    }

    /**
     * Display a listing of the WorkflowAlcada.
     *
     * @param WorkflowAlcadaDataTable $workflowAlcadaDataTable
     * @return Response
     */
    public function index(WorkflowAlcadaDataTable $workflowAlcadaDataTable)
    {
        return $workflowAlcadaDataTable->render('admin.workflow_alcadas.index');
    }

    /**
     * Show the form for creating a new WorkflowAlcada.
     *
     * @return Response
     */
    public function create()
    {
        $relacionados = [];

        $tipos = WorkflowTipo::pluck('nome', 'id')->prepend('Escolha...', '')->toArray();
        $workflow_tipo_id_contrato = WorkflowTipo::CONTRATO;

        return view(
            'admin.workflow_alcadas.create',
            compact('relacionados', 'workflow_tipo_id_contrato', 'tipos')
        );
    }

    /**
     * Store a newly created WorkflowAlcada in storage.
     *
     * @param CreateWorkflowAlcadaRequest $request
     *
     * @return Response
     */
    public function store(CreateWorkflowAlcadaRequest $request)
    {
        $input = $request->all();

        if($input['valor_minimo']) {
            $input['valor_minimo'] = money_to_float($input['valor_minimo']);
        }

        $this->workflowAlcadaRepository->validateBusinessLogic($input);

        $workflowAlcada = $this->workflowAlcadaRepository->create($input);

        Flash::success('Workflow Alçada ' . trans('common.saved') . ' ' . trans('common.successfully') . '.');

        return redirect(route('admin.workflowAlcadas.index'));
    }

    /**
     * Display the specified WorkflowAlcada.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $workflowAlcada = $this->workflowAlcadaRepository->findWithoutFail($id);

        if (empty($workflowAlcada)) {
            Flash::error('Workflow Alçada '.trans('common.not-found'));

            return redirect(route('admin.workflowAlcadas.index'));
        }

        $relacionados = [];
        $workflowUsuarios_ids = $workflowAlcada->workflowUsuarios()->pluck('user_id','user_id')->toArray();
        $relacionados = User::whereIn('id', $workflowUsuarios_ids)->pluck('name','id')->toArray();

        return view('admin.workflow_alcadas.show', compact('workflowAlcada', 'relacionados', 'workflowUsuarios_ids' ));
    }

    /**
     * Show the form for editing the specified WorkflowAlcada.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $workflowAlcada = $this->workflowAlcadaRepository->findWithoutFail($id);

        if (empty($workflowAlcada)) {
            Flash::error('Workflow Alçada '.trans('common.not-found'));

            return redirect(route('admin.workflowAlcadas.index'));
        }

        $relacionados = [];
        $workflowUsuarios_ids = $workflowAlcada->workflowUsuarios()->pluck('user_id','user_id')->toArray();
        $relacionados = User::whereIn('id', $workflowUsuarios_ids)->pluck('name','id')->toArray();

        $tipos = WorkflowTipo::pluck('nome', 'id')->prepend('Escolha...', '')->toArray();
        $workflow_tipo_id_contrato = WorkflowTipo::CONTRATO;

        return view(
            'admin.workflow_alcadas.edit',
            compact(
                'workflowAlcada',
                'relacionados',
                'workflowUsuarios_ids',
                'tipos',
                'workflow_tipo_id_contrato'
            )
        );
    }

    /**
     * Update the specified WorkflowAlcada in storage.
     *
     * @param  int              $id
     * @param UpdateWorkflowAlcadaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWorkflowAlcadaRequest $request)
    {
        $input = $request->all();

        $workflowAlcada = $this->workflowAlcadaRepository->findWithoutFail($id);

        $this->workflowAlcadaRepository->validateBusinessLogic($input, $id);

        if($input['valor_minimo']) {
            $input['valor_minimo'] = money_to_float($input['valor_minimo']);
        }

        $workflowAlcada = $this->workflowAlcadaRepository->update($input, $id);

        Flash::success('Workflow Alçada '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.workflowAlcadas.index'));
    }

    /**
     * Remove the specified WorkflowAlcada from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $workflowAlcada = $this->workflowAlcadaRepository->findWithoutFail($id);

        if (empty($workflowAlcada)) {
            Flash::error('Workflow Alçada '.trans('common.not-found'));

            return redirect(route('admin.workflowAlcadas.index'));
        }

        $this->workflowAlcadaRepository->delete($id);

        Flash::success('Workflow Alçada '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.workflowAlcadas.index'));
    }
}
