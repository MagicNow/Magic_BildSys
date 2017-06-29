<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\NomeclaturaMapaDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateNomeclaturaMapaRequest;
use App\Http\Requests\Admin\UpdateNomeclaturaMapaRequest;
use App\Repositories\Admin\NomeclaturaMapaRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class NomeclaturaMapaController extends AppBaseController
{
    /** @var  NomeclaturaMapaRepository */
    private $nomeclaturaMapaRepository;

    public function __construct(NomeclaturaMapaRepository $nomeclaturaMapaRepo)
    {
        $this->nomeclaturaMapaRepository = $nomeclaturaMapaRepo;
    }

    /**
     * Display a listing of the NomeclaturaMapa.
     *
     * @param NomeclaturaMapaDataTable $nomeclaturaMapaDataTable
     * @return Response
     */
    public function index(NomeclaturaMapaDataTable $nomeclaturaMapaDataTable)
    {
        return $nomeclaturaMapaDataTable->render('admin.nomeclatura_mapas.index');
    }

    /**
     * Show the form for creating a new NomeclaturaMapa.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.nomeclatura_mapas.create');
    }

    /**
     * Store a newly created NomeclaturaMapa in storage.
     *
     * @param CreateNomeclaturaMapaRequest $request
     *
     * @return Response
     */
    public function store(CreateNomeclaturaMapaRequest $request)
    {
        $input = $request->all();

        $nomeclaturaMapa = $this->nomeclaturaMapaRepository->create($input);

        Flash::success('Nomeclatura Mapa '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.nomeclaturaMapas.index'));
    }

    /**
     * Display the specified NomeclaturaMapa.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $nomeclaturaMapa = $this->nomeclaturaMapaRepository->findWithoutFail($id);

        if (empty($nomeclaturaMapa)) {
            Flash::error('Nomeclatura Mapa '.trans('common.not-found'));

            return redirect(route('admin.nomeclaturaMapas.index'));
        }

        return view('admin.nomeclatura_mapas.show')->with('nomeclaturaMapa', $nomeclaturaMapa);
    }

    /**
     * Show the form for editing the specified NomeclaturaMapa.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $nomeclaturaMapa = $this->nomeclaturaMapaRepository->findWithoutFail($id);

        if (empty($nomeclaturaMapa)) {
            Flash::error('Nomeclatura Mapa '.trans('common.not-found'));

            return redirect(route('admin.nomeclaturaMapas.index'));
        }

        return view('admin.nomeclatura_mapas.edit')->with('nomeclaturaMapa', $nomeclaturaMapa);
    }

    /**
     * Update the specified NomeclaturaMapa in storage.
     *
     * @param  int              $id
     * @param UpdateNomeclaturaMapaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNomeclaturaMapaRequest $request)
    {
        $nomeclaturaMapa = $this->nomeclaturaMapaRepository->findWithoutFail($id);

        if (empty($nomeclaturaMapa)) {
            Flash::error('Nomeclatura Mapa '.trans('common.not-found'));

            return redirect(route('admin.nomeclaturaMapas.index'));
        }

        $nomeclaturaMapa = $this->nomeclaturaMapaRepository->update($request->all(), $id);

        Flash::success('Nomeclatura Mapa '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.nomeclaturaMapas.index'));
    }

    /**
     * Remove the specified NomeclaturaMapa from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $nomeclaturaMapa = $this->nomeclaturaMapaRepository->findWithoutFail($id);

        if (empty($nomeclaturaMapa)) {
            Flash::error('Nomeclatura Mapa '.trans('common.not-found'));

            return redirect(route('admin.nomeclaturaMapas.index'));
        }

        $this->nomeclaturaMapaRepository->delete($id);

        Flash::success('Nomeclatura Mapa '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.nomeclaturaMapas.index'));
    }
}
