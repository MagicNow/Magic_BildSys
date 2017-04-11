<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\WorkflowReprovacaoMotivoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateWorkflowReprovacaoMotivoRequest;
use App\Http\Requests\Admin\UpdateWorkflowReprovacaoMotivoRequest;
use App\Repositories\Admin\WorkflowReprovacaoMotivoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class WorkflowReprovacaoMotivoController extends AppBaseController
{
    /** @var  WorkflowReprovacaoMotivoRepository */
    private $workflowReprovacaoMotivoRepository;

    public function __construct(WorkflowReprovacaoMotivoRepository $workflowReprovacaoMotivoRepo)
    {
        $this->workflowReprovacaoMotivoRepository = $workflowReprovacaoMotivoRepo;
    }

    /**
     * Display a listing of the WorkflowReprovacaoMotivo.
     *
     * @param WorkflowReprovacaoMotivoDataTable $workflowReprovacaoMotivoDataTable
     * @return Response
     */
    public function index(WorkflowReprovacaoMotivoDataTable $workflowReprovacaoMotivoDataTable)
    {
        return $workflowReprovacaoMotivoDataTable->render('admin.workflow_reprovacao_motivos.index');
    }

    /**
     * Show the form for creating a new WorkflowReprovacaoMotivo.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.workflow_reprovacao_motivos.create');
    }

    /**
     * Store a newly created WorkflowReprovacaoMotivo in storage.
     *
     * @param CreateWorkflowReprovacaoMotivoRequest $request
     *
     * @return Response
     */
    public function store(CreateWorkflowReprovacaoMotivoRequest $request)
    {
        $input = $request->all();

        $workflowReprovacaoMotivo = $this->workflowReprovacaoMotivoRepository->create($input);

        Flash::success('Motivo de Reprovação '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.workflowReprovacaoMotivos.index'));
    }

    /**
     * Display the specified WorkflowReprovacaoMotivo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $workflowReprovacaoMotivo = $this->workflowReprovacaoMotivoRepository->findWithoutFail($id);

        if (empty($workflowReprovacaoMotivo)) {
            Flash::error('Motivo de Reprovação '.trans('common.not-found'));

            return redirect(route('admin.workflowReprovacaoMotivos.index'));
        }

        return view('admin.workflow_reprovacao_motivos.show')->with('workflowReprovacaoMotivo', $workflowReprovacaoMotivo);
    }

    /**
     * Show the form for editing the specified WorkflowReprovacaoMotivo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $workflowReprovacaoMotivo = $this->workflowReprovacaoMotivoRepository->findWithoutFail($id);

        if (empty($workflowReprovacaoMotivo)) {
            Flash::error('Motivo de Reprovação '.trans('common.not-found'));

            return redirect(route('admin.workflowReprovacaoMotivos.index'));
        }

        return view('admin.workflow_reprovacao_motivos.edit')->with('workflowReprovacaoMotivo', $workflowReprovacaoMotivo);
    }

    /**
     * Update the specified WorkflowReprovacaoMotivo in storage.
     *
     * @param  int              $id
     * @param UpdateWorkflowReprovacaoMotivoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWorkflowReprovacaoMotivoRequest $request)
    {
        $workflowReprovacaoMotivo = $this->workflowReprovacaoMotivoRepository->findWithoutFail($id);

        if (empty($workflowReprovacaoMotivo)) {
            Flash::error('Motivo de Reprovação '.trans('common.not-found'));

            return redirect(route('admin.workflowReprovacaoMotivos.index'));
        }

        $workflowReprovacaoMotivo = $this->workflowReprovacaoMotivoRepository->update($request->all(), $id);

        Flash::success('Motivo de Reprovação '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.workflowReprovacaoMotivos.index'));
    }

    /**
     * Remove the specified WorkflowReprovacaoMotivo from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $workflowReprovacaoMotivo = $this->workflowReprovacaoMotivoRepository->findWithoutFail($id);

        if (empty($workflowReprovacaoMotivo)) {
            Flash::error('Motivo de Reprovação '.trans('common.not-found'));

            return redirect(route('admin.workflowReprovacaoMotivos.index'));
        }

        $this->workflowReprovacaoMotivoRepository->delete($id);

        Flash::success('Motivo de Reprovação '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.workflowReprovacaoMotivos.index'));
    }
}
