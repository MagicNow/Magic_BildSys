<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\ContratosDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateContratosRequest;
use App\Http\Requests\Admin\UpdateContratosRequest;
use App\Repositories\Admin\ContratosRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class ContratosController extends AppBaseController
{
    /** @var  ContratosRepository */
    private $contratosRepository;

    public function __construct(ContratosRepository $contratosRepo)
    {
        $this->contratosRepository = $contratosRepo;
    }

    /**
     * Display a listing of the Contratos.
     *
     * @param ContratosDataTable $contratosDataTable
     * @return Response
     */
    public function index(ContratosDataTable $contratosDataTable)
    {
        return $contratosDataTable->render('admin.contratos.index');
    }

    /**
     * Show the form for creating a new Contratos.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.contratos.create');
    }

    /**
     * Store a newly created Contratos in storage.
     *
     * @param CreateContratosRequest $request
     *
     * @return Response
     */
    public function store(CreateContratosRequest $request)
    {
        $input = $request->all();

        $contratos = $this->contratosRepository->create($input);

        Flash::success('Contratos '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.contratos.index'));
    }

    /**
     * Display the specified Contratos.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $contratos = $this->contratosRepository->findWithoutFail($id);

        if (empty($contratos)) {
            Flash::error('Contratos '.trans('common.not-found'));

            return redirect(route('admin.contratos.index'));
        }

        return view('admin.contratos.show')->with('contratos', $contratos);
    }

    /**
     * Show the form for editing the specified Contratos.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $contratos = $this->contratosRepository->findWithoutFail($id);

        if (empty($contratos)) {
            Flash::error('Contratos '.trans('common.not-found'));

            return redirect(route('admin.contratos.index'));
        }

        return view('admin.contratos.edit')->with('contratos', $contratos);
    }

    /**
     * Update the specified Contratos in storage.
     *
     * @param  int              $id
     * @param UpdateContratosRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContratosRequest $request)
    {
        $contratos = $this->contratosRepository->findWithoutFail($id);

        if (empty($contratos)) {
            Flash::error('Contratos '.trans('common.not-found'));

            return redirect(route('admin.contratos.index'));
        }

        $contratos = $this->contratosRepository->update($request->all(), $id);

        Flash::success('Contratos '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.contratos.index'));
    }

    /**
     * Remove the specified Contratos from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $contratos = $this->contratosRepository->findWithoutFail($id);

        if (empty($contratos)) {
            Flash::error('Contratos '.trans('common.not-found'));

            return redirect(route('admin.contratos.index'));
        }

        $this->contratosRepository->delete($id);

        Flash::success('Contratos '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.contratos.index'));
    }
}
