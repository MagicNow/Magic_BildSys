<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\TipoLevantamentoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateTipoLevantamentoRequest;
use App\Http\Requests\Admin\UpdateTipoLevantamentoRequest;
use App\Repositories\Admin\TipoLevantamentoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class TipoLevantamentoController extends AppBaseController
{
    /** @var  TipoLevantamentoRepository */
    private $tipoLevantamentoRepository;

    public function __construct(TipoLevantamentoRepository $tipoLevantamentoRepo)
    {
        $this->tipoLevantamentoRepository = $tipoLevantamentoRepo;
    }

    /**
     * Display a listing of the TipoLevantamento.
     *
     * @param TipoLevantamentoDataTable $tipoLevantamentoDataTable
     * @return Response
     */
    public function index(TipoLevantamentoDataTable $tipoLevantamentoDataTable)
    {
        return $tipoLevantamentoDataTable->render('admin.tipo_levantamentos.index');
    }

    /**
     * Show the form for creating a new TipoLevantamento.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.tipo_levantamentos.create');
    }

    /**
     * Store a newly created TipoLevantamento in storage.
     *
     * @param CreateTipoLevantamentoRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoLevantamentoRequest $request)
    {
        $input = $request->all();

        $tipoLevantamento = $this->tipoLevantamentoRepository->create($input);

        Flash::success('Tipo Levantamento '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.tipo_levantamentos.index'));
    }

    /**
     * Display the specified TipoLevantamento.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tipoLevantamento = $this->tipoLevantamentoRepository->findWithoutFail($id);

        if (empty($tipoLevantamento)) {
            Flash::error('Tipo Levantamento '.trans('common.not-found'));

            return redirect(route('admin.tipo_levantamentos.index'));
        }

        return view('admin.tipo_levantamentos.show')->with('tipoLevantamento', $tipoLevantamento);
    }

    /**
     * Show the form for editing the specified TipoLevantamento.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tipoLevantamento = $this->tipoLevantamentoRepository->findWithoutFail($id);

        if (empty($tipoLevantamento)) {
            Flash::error('Tipo Levantamento '.trans('common.not-found'));

            return redirect(route('admin.tipo_levantamentos.index'));
        }

        return view('admin.tipo_levantamentos.edit')->with('tipoLevantamento', $tipoLevantamento);
    }

    /**
     * Update the specified TipoLevantamento in storage.
     *
     * @param  int              $id
     * @param UpdateTipoLevantamentoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoLevantamentoRequest $request)
    {
        $tipoLevantamento = $this->tipoLevantamentoRepository->findWithoutFail($id);

        if (empty($tipoLevantamento)) {
            Flash::error('Tipo Levantamento '.trans('common.not-found'));

            return redirect(route('admin.tipo_levantamentos.index'));
        }

        $tipoLevantamento = $this->tipoLevantamentoRepository->update($request->all(), $id);

        Flash::success('Tipo Levantamento '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.tipo_levantamentos.index'));
    }

    /**
     * Remove the specified TipoLevantamento from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tipoLevantamento = $this->tipoLevantamentoRepository->findWithoutFail($id);

        if (empty($tipoLevantamento)) {
            Flash::error('Tipo Levantamento '.trans('common.not-found'));

            return redirect(route('admin.tipo_levantamentos.index'));
        }

        $this->tipoLevantamentoRepository->delete($id);

        Flash::success('Tipo Levantamento '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.tipo_levantamentos.index'));
    }
}
