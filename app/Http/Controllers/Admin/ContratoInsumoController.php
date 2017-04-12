<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\ContratoInsumoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateContratoInsumoRequest;
use App\Http\Requests\Admin\UpdateContratoInsumoRequest;
use App\Repositories\Admin\ContratoInsumoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class ContratoInsumoController extends AppBaseController
{
    /** @var  ContratoInsumoRepository */
    private $contratoInsumoRepository;

    public function __construct(ContratoInsumoRepository $contratoInsumoRepo)
    {
        $this->contratoInsumoRepository = $contratoInsumoRepo;
    }

    /**
     * Display a listing of the ContratoInsumo.
     *
     * @param ContratoInsumoDataTable $contratoInsumoDataTable
     * @return Response
     */
    public function index(ContratoInsumoDataTable $contratoInsumoDataTable)
    {
        return $contratoInsumoDataTable->render('admin.contrato_insumos.index');
    }

    /**
     * Show the form for creating a new ContratoInsumo.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.contrato_insumos.create');
    }

    /**
     * Store a newly created ContratoInsumo in storage.
     *
     * @param CreateContratoInsumoRequest $request
     *
     * @return Response
     */
    public function store(CreateContratoInsumoRequest $request)
    {
        $input = $request->all();

        $contratoInsumo = $this->contratoInsumoRepository->create($input);

        Flash::success('Contrato Insumo '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.contratoInsumos.index'));
    }

    /**
     * Display the specified ContratoInsumo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $contratoInsumo = $this->contratoInsumoRepository->findWithoutFail($id);

        if (empty($contratoInsumo)) {
            Flash::error('Contrato Insumo '.trans('common.not-found'));

            return redirect(route('admin.contratoInsumos.index'));
        }

        return view('admin.contrato_insumos.show')->with('contratoInsumo', $contratoInsumo);
    }

    /**
     * Show the form for editing the specified ContratoInsumo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $contratoInsumo = $this->contratoInsumoRepository->findWithoutFail($id);

        if (empty($contratoInsumo)) {
            Flash::error('Contrato Insumo '.trans('common.not-found'));

            return redirect(route('admin.contratoInsumos.index'));
        }

        return view('admin.contrato_insumos.edit')->with('contratoInsumo', $contratoInsumo);
    }

    /**
     * Update the specified ContratoInsumo in storage.
     *
     * @param  int              $id
     * @param UpdateContratoInsumoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContratoInsumoRequest $request)
    {
        $contratoInsumo = $this->contratoInsumoRepository->findWithoutFail($id);

        if (empty($contratoInsumo)) {
            Flash::error('Contrato Insumo '.trans('common.not-found'));

            return redirect(route('admin.contratoInsumos.index'));
        }

        $contratoInsumo = $this->contratoInsumoRepository->update($request->all(), $id);

        Flash::success('Contrato Insumo '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.contratoInsumos.index'));
    }

    /**
     * Remove the specified ContratoInsumo from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $contratoInsumo = $this->contratoInsumoRepository->findWithoutFail($id);

        if (empty($contratoInsumo)) {
            Flash::error('Contrato Insumo '.trans('common.not-found'));

            return redirect(route('admin.contratoInsumos.index'));
        }

        $this->contratoInsumoRepository->delete($id);

        Flash::success('Contrato Insumo '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.contratoInsumos.index'));
    }
}
