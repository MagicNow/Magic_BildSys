<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\TemplatePlanilhaDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateTemplatePlanilhaRequest;
use App\Http\Requests\Admin\UpdateTemplatePlanilhaRequest;
use App\Repositories\Admin\TemplatePlanilhaRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class TemplatePlanilhaController extends AppBaseController
{
    /** @var  TemplatePlanilhaRepository */
    private $templatePlanilhaRepository;

    public function __construct(TemplatePlanilhaRepository $templatePlanilhaRepo)
    {
        $this->templatePlanilhaRepository = $templatePlanilhaRepo;
    }

    /**
     * Display a listing of the TemplatePlanilha.
     *
     * @param TemplatePlanilhaDataTable $templatePlanilhaDataTable
     * @return Response
     */
    public function index(TemplatePlanilhaDataTable $templatePlanilhaDataTable)
    {
        return $templatePlanilhaDataTable->render('admin.template_planilhas.index');
    }

    /**
     * Show the form for creating a new TemplatePlanilha.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.template_planilhas.create');
    }

    /**
     * Store a newly created TemplatePlanilha in storage.
     *
     * @param CreateTemplatePlanilhaRequest $request
     *
     * @return Response
     */
    public function store(CreateTemplatePlanilhaRequest $request)
    {
        $input = $request->all();

        $templatePlanilha = $this->templatePlanilhaRepository->create($input);

        Flash::success('Template Planilha '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.templatePlanilhas.index'));
    }

    /**
     * Display the specified TemplatePlanilha.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $templatePlanilha = $this->templatePlanilhaRepository->findWithoutFail($id);

        if (empty($templatePlanilha)) {
            Flash::error('Template Planilha '.trans('common.not-found'));

            return redirect(route('admin.templatePlanilhas.index'));
        }

        return view('admin.template_planilhas.show')->with('templatePlanilha', $templatePlanilha);
    }

    /**
     * Show the form for editing the specified TemplatePlanilha.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $templatePlanilha = $this->templatePlanilhaRepository->findWithoutFail($id);

        if (empty($templatePlanilha)) {
            Flash::error('Template Planilha '.trans('common.not-found'));

            return redirect(route('admin.templatePlanilhas.index'));
        }

        return view('admin.template_planilhas.edit')->with('templatePlanilha', $templatePlanilha);
    }

    /**
     * Update the specified TemplatePlanilha in storage.
     *
     * @param  int              $id
     * @param UpdateTemplatePlanilhaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTemplatePlanilhaRequest $request)
    {
        $templatePlanilha = $this->templatePlanilhaRepository->findWithoutFail($id);

        if (empty($templatePlanilha)) {
            Flash::error('Template Planilha '.trans('common.not-found'));

            return redirect(route('admin.templatePlanilhas.index'));
        }

        $templatePlanilha = $this->templatePlanilhaRepository->update($request->all(), $id);

        Flash::success('Template Planilha '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.templatePlanilhas.index'));
    }

    /**
     * Remove the specified TemplatePlanilha from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $templatePlanilha = $this->templatePlanilhaRepository->findWithoutFail($id);

        if (empty($templatePlanilha)) {
            Flash::error('Template Planilha '.trans('common.not-found'));

            return redirect(route('admin.templatePlanilhas.index'));
        }

        $this->templatePlanilhaRepository->delete($id);

        Flash::success('Template Planilha '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.templatePlanilhas.index'));
    }
}
