<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\ContratoTemplateDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateContratoTemplateRequest;
use App\Http\Requests\Admin\UpdateContratoTemplateRequest;
use App\Repositories\Admin\ContratoTemplateRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Response;

class ContratoTemplateController extends AppBaseController
{
    /** @var  ContratoTemplateRepository */
    private $contratoTemplateRepository;

    public function __construct(ContratoTemplateRepository $contratoTemplateRepo)
    {
        $this->contratoTemplateRepository = $contratoTemplateRepo;
    }

    /**
     * Display a listing of the ContratoTemplate.
     *
     * @param ContratoTemplateDataTable $contratoTemplateDataTable
     * @return Response
     */
    public function index(ContratoTemplateDataTable $contratoTemplateDataTable)
    {
        return $contratoTemplateDataTable->render('admin.contrato_templates.index');
    }

    /**
     * Show the form for creating a new ContratoTemplate.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.contrato_templates.create');
    }

    /**
     * Store a newly created ContratoTemplate in storage.
     *
     * @param CreateContratoTemplateRequest $request
     *
     * @return Response
     */
    public function store(CreateContratoTemplateRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = Auth::id();
        $input['campos_extras'] = json_encode($request->campos_extras);

        $contratoTemplate = $this->contratoTemplateRepository->create($input);

        Flash::success('Contrato Template '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.contratoTemplates.index'));
    }

    /**
     * Display the specified ContratoTemplate.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $contratoTemplate = $this->contratoTemplateRepository->findWithoutFail($id);

        if (empty($contratoTemplate)) {
            Flash::error('Contrato Template '.trans('common.not-found'));

            return redirect(route('admin.contratoTemplates.index'));
        }

        return view('admin.contrato_templates.show')->with('contratoTemplate', $contratoTemplate);
    }

    /**
     * Show the form for editing the specified ContratoTemplate.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $contratoTemplate = $this->contratoTemplateRepository->findWithoutFail($id);

        if (empty($contratoTemplate)) {
            Flash::error('Contrato Template '.trans('common.not-found'));

            return redirect(route('admin.contratoTemplates.index'));
        }

        return view('admin.contrato_templates.edit')->with('contratoTemplate', $contratoTemplate);
    }

    /**
     * Update the specified ContratoTemplate in storage.
     *
     * @param  int              $id
     * @param UpdateContratoTemplateRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContratoTemplateRequest $request)
    {
        $contratoTemplate = $this->contratoTemplateRepository->findWithoutFail($id);

        if (empty($contratoTemplate)) {
            Flash::error('Contrato Template '.trans('common.not-found'));

            return redirect(route('admin.contratoTemplates.index'));
        }

        $input = $request->all();
        $input['user_id'] = Auth::id();
        $input['campos_extras'] = json_encode($request->campos_extras);
        $contratoTemplate = $this->contratoTemplateRepository->update($input, $id);

        Flash::success('Contrato Template '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.contratoTemplates.index'));
    }

    /**
     * Remove the specified ContratoTemplate from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $contratoTemplate = $this->contratoTemplateRepository->findWithoutFail($id);

        if (empty($contratoTemplate)) {
            Flash::error('Contrato Template '.trans('common.not-found'));

            return redirect(route('admin.contratoTemplates.index'));
        }

        if($contratoTemplate->tipo != 'Q'){
            Flash::error('Não é possível remover este Template ');

            return redirect(route('admin.contratoTemplates.index'));
        }
        $this->contratoTemplateRepository->delete($id);

        Flash::success('Contrato Template '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.contratoTemplates.index'));
    }

    public function camposExtras($id){
        $contratoTemplate = $this->contratoTemplateRepository->findWithoutFail($id);

        if (empty($contratoTemplate)) {
            return response()->json(['error'=>'Contrato Template '.trans('common.not-found')],404);
        }
        $campos_extras = [];
        if( strlen(trim($contratoTemplate->campos_extras)) ){
            $campos_extras = json_decode($contratoTemplate->campos_extras);
        }

        return response()->json(['campos_extras'=>$campos_extras]);
    }
}
