<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\InsumoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateInsumoRequest;
use App\Http\Requests\Admin\UpdateInsumoRequest;
use App\Repositories\Admin\InsumoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class InsumoController extends AppBaseController
{
    /** @var  InsumoRepository */
    private $insumoRepository;

    public function __construct(InsumoRepository $insumoRepo)
    {
        $this->insumoRepository = $insumoRepo;
    }

    /**
     * Display a listing of the Insumo.
     *
     * @param InsumoDataTable $insumoDataTable
     * @return Response
     */
    public function index(InsumoDataTable $insumoDataTable)
    {
        return $insumoDataTable->render('admin.insumos.index');
    }

    /**
     * Show the form for creating a new Insumo.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.insumos.create');
    }

    /**
     * Store a newly created Insumo in storage.
     *
     * @param CreateInsumoRequest $request
     *
     * @return Response
     */
    public function store(CreateInsumoRequest $request)
    {
        $input = $request->all();

        $insumo = $this->insumoRepository->create($input);

        Flash::success('Insumo '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.insumos.index'));
    }

    /**
     * Display the specified Insumo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $insumo = $this->insumoRepository->findWithoutFail($id);

        if (empty($insumo)) {
            Flash::error('Insumo '.trans('common.not-found'));

            return redirect(route('admin.insumos.index'));
        }

        return view('admin.insumos.show')->with('insumo', $insumo);
    }

    /**
     * Show the form for editing the specified Insumo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $insumo = $this->insumoRepository->findWithoutFail($id);

        if (empty($insumo)) {
            Flash::error('Insumo '.trans('common.not-found'));

            return redirect(route('admin.insumos.index'));
        }

        return view('admin.insumos.edit')->with('insumo', $insumo);
    }

    /**
     * Update the specified Insumo in storage.
     *
     * @param  int              $id
     * @param UpdateInsumoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInsumoRequest $request)
    {
        $insumo = $this->insumoRepository->findWithoutFail($id);

        if (empty($insumo)) {
            Flash::error('Insumo '.trans('common.not-found'));

            return redirect(route('admin.insumos.index'));
        }

        $insumo = $this->insumoRepository->update($request->all(), $id);

        Flash::success('Insumo '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.insumos.index'));
    }

    /**
     * Remove the specified Insumo from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $insumo = $this->insumoRepository->findWithoutFail($id);

        if (empty($insumo)) {
            Flash::error('Insumo '.trans('common.not-found'));

            return redirect(route('admin.insumos.index'));
        }

        $this->insumoRepository->delete($id);

        Flash::success('Insumo '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.insumos.index'));
    }
}
