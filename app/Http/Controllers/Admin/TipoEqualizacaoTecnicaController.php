<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\TipoEqualizacaoTecnicaDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateTipoEqualizacaoTecnicaRequest;
use App\Http\Requests\Admin\UpdateTipoEqualizacaoTecnicaRequest;
use App\Repositories\Admin\TipoEqualizacaoTecnicaRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class TipoEqualizacaoTecnicaController extends AppBaseController
{
    /** @var  TipoEqualizacaoTecnicaRepository */
    private $tipoEqualizacaoTecnicaRepository;

    public function __construct(TipoEqualizacaoTecnicaRepository $tipoEqualizacaoTecnicaRepo)
    {
        $this->tipoEqualizacaoTecnicaRepository = $tipoEqualizacaoTecnicaRepo;
    }

    /**
     * Display a listing of the TipoEqualizacaoTecnica.
     *
     * @param TipoEqualizacaoTecnicaDataTable $tipoEqualizacaoTecnicaDataTable
     * @return Response
     */
    public function index(TipoEqualizacaoTecnicaDataTable $tipoEqualizacaoTecnicaDataTable)
    {
        return $tipoEqualizacaoTecnicaDataTable->render('admin.tipo_equalizacao_tecnicas.index');
    }

    /**
     * Show the form for creating a new TipoEqualizacaoTecnica.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.tipo_equalizacao_tecnicas.create');
    }

    /**
     * Store a newly created TipoEqualizacaoTecnica in storage.
     *
     * @param CreateTipoEqualizacaoTecnicaRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoEqualizacaoTecnicaRequest $request)
    {
        dd($request->all());
        $input = $request->all();

        $this->tipoEqualizacaoTecnicaRepository->create($input);

        Flash::success('Tipo Equalizacao Tecnica '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.tipoEqualizacaoTecnicas.index'));
    }

    /**
     * Display the specified TipoEqualizacaoTecnica.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tipoEqualizacaoTecnica = $this->tipoEqualizacaoTecnicaRepository->findWithoutFail($id);

        if (empty($tipoEqualizacaoTecnica)) {
            Flash::error('Tipo Equalizacao Tecnica '.trans('common.not-found'));

            return redirect(route('admin.tipoEqualizacaoTecnicas.index'));
        }

        return view('admin.tipo_equalizacao_tecnicas.show')->with('tipoEqualizacaoTecnica', $tipoEqualizacaoTecnica);
    }

    /**
     * Show the form for editing the specified TipoEqualizacaoTecnica.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tipoEqualizacaoTecnica = $this->tipoEqualizacaoTecnicaRepository->findWithoutFail($id);

        if (empty($tipoEqualizacaoTecnica)) {
            Flash::error('Tipo Equalizacao Tecnica '.trans('common.not-found'));

            return redirect(route('admin.tipoEqualizacaoTecnicas.index'));
        }

        return view('admin.tipo_equalizacao_tecnicas.edit')->with('tipoEqualizacaoTecnica', $tipoEqualizacaoTecnica);
    }

    /**
     * Update the specified TipoEqualizacaoTecnica in storage.
     *
     * @param  int              $id
     * @param UpdateTipoEqualizacaoTecnicaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoEqualizacaoTecnicaRequest $request)
    {
        $tipoEqualizacaoTecnica = $this->tipoEqualizacaoTecnicaRepository->findWithoutFail($id);

        if (empty($tipoEqualizacaoTecnica)) {
            Flash::error('Tipo Equalizacao Tecnica '.trans('common.not-found'));

            return redirect(route('admin.tipoEqualizacaoTecnicas.index'));
        }

        $tipoEqualizacaoTecnica = $this->tipoEqualizacaoTecnicaRepository->update($request->all(), $id);

        Flash::success('Tipo Equalizacao Tecnica '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.tipoEqualizacaoTecnicas.index'));
    }

    /**
     * Remove the specified TipoEqualizacaoTecnica from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tipoEqualizacaoTecnica = $this->tipoEqualizacaoTecnicaRepository->findWithoutFail($id);

        if (empty($tipoEqualizacaoTecnica)) {
            Flash::error('Tipo Equalizacao Tecnica '.trans('common.not-found'));

            return redirect(route('admin.tipoEqualizacaoTecnicas.index'));
        }

        $this->tipoEqualizacaoTecnicaRepository->delete($id);

        Flash::success('Tipo Equalizacao Tecnica '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.tipoEqualizacaoTecnicas.index'));
    }
}
