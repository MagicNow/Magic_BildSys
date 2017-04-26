<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\ObraDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateObraRequest;
use App\Http\Requests\Admin\UpdateObraRequest;
use App\Repositories\Admin\ObraRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class ObraController extends AppBaseController
{
    /** @var  ObraRepository */
    private $obraRepository;

    public function __construct(ObraRepository $obraRepo)
    {
        $this->obraRepository = $obraRepo;
    }

    /**
     * Display a listing of the Obra.
     *
     * @param ObraDataTable $obraDataTable
     * @return Response
     */
    public function index(ObraDataTable $obraDataTable)
    {
        return $obraDataTable->render('admin.obras.index');
    }

    /**
     * Show the form for creating a new Obra.
     *
     * @return Response
     */
    public function create()
    {
        $relacionados = [];
        return view('admin.obras.create', compact('relacionados'));
    }

    /**
     * Store a newly created Obra in storage.
     *
     * @param CreateObraRequest $request
     *
     * @return Response
     */
    public function store(CreateObraRequest $request)
    {
        $input = $request->all();

        $obra = $this->obraRepository->create($input);

        Flash::success('Obra '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.obras.index'));
    }

    /**
     * Display the specified Obra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $obra = $this->obraRepository->findWithoutFail($id);

        if (empty($obra)) {
            Flash::error('Obra '.trans('common.not-found'));

            return redirect(route('admin.obras.index'));
        }

        return view('admin.obras.show')->with('obra', $obra);
    }

    /**
     * Show the form for editing the specified Obra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $obra = $this->obraRepository->findWithoutFail($id);

        if (empty($obra)) {
            Flash::error('Obra '.trans('common.not-found'));

            return redirect(route('admin.obras.index'));
        }

        $relacionados = [];
        $obrasUsers_ids = $obra->obraUsers()->pluck('user_id','user_id')->toArray();
        $relacionados = User::whereIn('id', $obrasUsers_ids)->pluck('name','id')->toArray();

        return view('admin.obras.edit', compact('obra', 'relacionados', 'obrasUsers_ids'));
    }

    /**
     * Update the specified Obra in storage.
     *
     * @param  int              $id
     * @param UpdateObraRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateObraRequest $request)
    {
        $obra = $this->obraRepository->findWithoutFail($id);

        if (empty($obra)) {
            Flash::error('Obra '.trans('common.not-found'));

            return redirect(route('admin.obras.index'));
        }

        $obra = $this->obraRepository->update($request->all(), $id);

        Flash::success('Obra '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.obras.index'));
    }

    /**
     * Remove the specified Obra from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $obra = $this->obraRepository->findWithoutFail($id);

        if (empty($obra)) {
            Flash::error('Obra '.trans('common.not-found'));

            return redirect(route('admin.obras.index'));
        }

        $this->obraRepository->delete($id);

        Flash::success('Obra '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.obras.index'));
    }
}
