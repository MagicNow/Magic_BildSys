<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\TopologiaDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateTopologiaRequest;
use App\Http\Requests\Admin\UpdateTopologiaRequest;
use App\Repositories\Admin\TopologiaRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Storage;
use Response;
use DB;

class TopologiaController extends AppBaseController
{
    /** @var  TopologiaRepository */
    private $topologiaRepository;    

    public function __construct(TopologiaRepository $topologiaRepo)
    {
        $this->topologiaRepository = $topologiaRepo;        
    }

    /**
     * Display a listing of the Topologia.
     *
     * @param TopologiaDataTable $topologiaDataTable
     * @return Response
     */
    public function index(TopologiaDataTable $topologiaDataTable)
    {
        return $topologiaDataTable->render('admin.topologia.index');
    }

    /**
     * Show the form for creating a new Topologia.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.topologia.create');
    }

    /**
     * Store a newly created Topologia in storage.
     *
     * @param CreateTopologiaRequest $request
     *
     * @return Response
     */
    public function store(CreateTopologiaRequest $request)
    {
        $input = $request->all();

        $topologia = $this->topologiaRepository->create($input);

        Flash::success(' Tarefa Padrão '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.topologia.index'));
    }

    /**
     * Display the specified Topologia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $topologia = $this->topologiaRepository->findWithoutFail($id);

        if (empty($topologia)) {
            Flash::error(' Tarefa Padrão '.trans('common.not-found'));

            return redirect(route('admin.topologia.index'));
        }

        return view('admin.topologia.show')->with('topologia', $topologia);
    }

    /**
     * Show the form for editing the specified Topologia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $topologia = $this->topologiaRepository->findWithoutFail($id);

        if (empty($topologia)) {
            Flash::error(' Tarefa Padrão '.trans('common.not-found'));

            return redirect(route('admin.topologia.index'));
        }    
        
        return view('admin.topologia.edit', compact('topologia'));
    }

    /**
     * Update the specified Topologia in storage.
     *
     * @param  int              $id
     * @param UpdateTopologiaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTopologiaRequest $request)
    {
        $topologia = $this->topologiaRepository->findWithoutFail($id);

        if (empty($topologia)) {
            Flash::error(' Tarefa Padrão '.trans('common.not-found'));

            return redirect(route('admin.topologia.index'));
        }       

        $topologia = $this->topologiaRepository->update($request->all(), $id);

        Flash::success('Tarefa Padrão '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.topologia.index'));
    }

    /**
     * Remove the specified Topologia from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $topologia = $this->topologiaRepository->findWithoutFail($id);

        if (empty($topologia)) {
            Flash::error(' Tarefa Padrão '.trans('common.not-found'));
            return redirect(route('admin.topologia.index'));
        }
		
        $this->topologiaRepository->delete($id);

        Flash::success(' Tarefa Padrão '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.topologia.index'));
    }
}
