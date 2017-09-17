<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\TarefaPadraoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateTarefaPadraoRequest;
use App\Http\Requests\Admin\UpdateTarefaPadraoRequest;
use App\Repositories\Admin\TarefaPadraoRepository;
use App\Repositories\CodeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Storage;
use Response;
use DB;

class TarefaPadraoController extends AppBaseController
{
    /** @var  TarefaPadraoRepository */
    private $tarefaPadraoRepository;    

    public function __construct(TarefaPadraoRepository $tarefaPadraoRepo)
    {
        $this->tarefaPadraoRepository = $tarefaPadraoRepo;        
    }

    /**
     * Display a listing of the TarefaPadrao.
     *
     * @param TarefaPadraoDataTable $tarefaPadraoDataTable
     * @return Response
     */
    public function index(TarefaPadraoDataTable $tarefaPadraoDataTable)
    {
        return $tarefaPadraoDataTable->render('admin.tarefa_padrao.index');
    }

    /**
     * Show the form for creating a new TarefaPadrao.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.tarefa_padrao.create');
    }

    /**
     * Store a newly created TarefaPadrao in storage.
     *
     * @param CreateTarefaPadraoRequest $request
     *
     * @return Response
     */
    public function store(CreateTarefaPadraoRequest $request)
    {
        $input = $request->all();

        $tarefaPadrao = $this->tarefaPadraoRepository->create($input);

        Flash::success(' Tarefa Padrão '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.tarefa_padrao.index'));
    }

    /**
     * Display the specified TarefaPadrao.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tarefaPadrao = $this->tarefaPadraoRepository->findWithoutFail($id);

        if (empty($tarefaPadrao)) {
            Flash::error(' Tarefa Padrão '.trans('common.not-found'));

            return redirect(route('admin.tarefa_padrao.index'));
        }

        return view('admin.tarefa_padrao.show')->with('tarefaPadrao', $tarefaPadrao);
    }

    /**
     * Show the form for editing the specified TarefaPadrao.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tarefaPadrao = $this->tarefaPadraoRepository->findWithoutFail($id);

        if (empty($tarefaPadrao)) {
            Flash::error(' Tarefa Padrão '.trans('common.not-found'));

            return redirect(route('admin.tarefa_padrao.index'));
        }    
        
        return view('admin.tarefa_padrao.edit', compact('tarefaPadrao'));
    }

    /**
     * Update the specified TarefaPadrao in storage.
     *
     * @param  int              $id
     * @param UpdateTarefaPadraoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTarefaPadraoRequest $request)
    {
        $tarefaPadrao = $this->tarefaPadraoRepository->findWithoutFail($id);

        if (empty($tarefaPadrao)) {
            Flash::error(' Tarefa Padrão '.trans('common.not-found'));

            return redirect(route('admin.tarefa_padrao.index'));
        }       

        $tarefaPadrao = $this->tarefaPadraoRepository->update($request->all(), $id);

        Flash::success('Tarefa Padrão '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.tarefa_padrao.index'));
    }

    /**
     * Remove the specified TarefaPadrao from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tarefaPadrao = $this->tarefaPadraoRepository->findWithoutFail($id);

        if (empty($tarefaPadrao)) {
            Flash::error(' Tarefa Padrão '.trans('common.not-found'));
            return redirect(route('admin.tarefa_padrao.index'));
        }
		
        $this->tarefaPadraoRepository->delete($id);

        Flash::success(' Tarefa Padrão '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.tarefa_padrao.index'));
    }
}
