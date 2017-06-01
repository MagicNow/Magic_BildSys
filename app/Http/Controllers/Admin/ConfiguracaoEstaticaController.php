<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\ConfiguracaoEstaticaDataTable;
use App\Http\Requests;
use App\Http\Requests\Admin\CreateConfiguracaoEstaticaRequest;
use App\Http\Requests\Admin\UpdateConfiguracaoEstaticaRequest;
use App\Repositories\Admin\ConfiguracaoEstaticaRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class ConfiguracaoEstaticaController extends AppBaseController
{
    /** @var  ConfiguracaoEstaticaRepository */
    private $configuracaoEstaticaRepository;

    public function __construct(ConfiguracaoEstaticaRepository $configuracaoEstaticaRepo)
    {
        $this->configuracaoEstaticaRepository = $configuracaoEstaticaRepo;
    }

    /**
     * Display a listing of the ConfiguracaoEstatica.
     *
     * @param ConfiguracaoEstaticaDataTable $configuracaoEstaticaDataTable
     * @return Response
     */
    public function index(ConfiguracaoEstaticaDataTable $configuracaoEstaticaDataTable)
    {
        return $configuracaoEstaticaDataTable->render('admin.configuracao_estaticas.index');
    }

    /**
     * Show the form for creating a new ConfiguracaoEstatica.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.configuracao_estaticas.create');
    }

    /**
     * Store a newly created ConfiguracaoEstatica in storage.
     *
     * @param CreateConfiguracaoEstaticaRequest $request
     *
     * @return Response
     */
    public function store(CreateConfiguracaoEstaticaRequest $request)
    {
        $input = $request->all();

        $configuracaoEstatica = $this->configuracaoEstaticaRepository->create($input);

        Flash::success('Configuracao Estatica '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.configuracaoEstaticas.index'));
    }

    /**
     * Display the specified ConfiguracaoEstatica.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $configuracaoEstatica = $this->configuracaoEstaticaRepository->findWithoutFail($id);

        if (empty($configuracaoEstatica)) {
            Flash::error('Configuracao Estatica '.trans('common.not-found'));

            return redirect(route('admin.configuracaoEstaticas.index'));
        }

        return view('admin.configuracao_estaticas.show')->with('configuracaoEstatica', $configuracaoEstatica);
    }

    /**
     * Show the form for editing the specified ConfiguracaoEstatica.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $configuracaoEstatica = $this->configuracaoEstaticaRepository->findWithoutFail($id);

        if (empty($configuracaoEstatica)) {
            Flash::error('Configuracao Estatica '.trans('common.not-found'));

            return redirect(route('admin.configuracaoEstaticas.index'));
        }

        return view('admin.configuracao_estaticas.edit')->with('configuracaoEstatica', $configuracaoEstatica);
    }

    /**
     * Update the specified ConfiguracaoEstatica in storage.
     *
     * @param  int              $id
     * @param UpdateConfiguracaoEstaticaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateConfiguracaoEstaticaRequest $request)
    {
        $configuracaoEstatica = $this->configuracaoEstaticaRepository->findWithoutFail($id);

        if (empty($configuracaoEstatica)) {
            Flash::error('Configuracao Estatica '.trans('common.not-found'));

            return redirect(route('admin.configuracaoEstaticas.index'));
        }

        $configuracaoEstatica = $this->configuracaoEstaticaRepository->update($request->all(), $id);

        Flash::success('Configuracao Estatica '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.configuracaoEstaticas.index'));
    }

    /**
     * Remove the specified ConfiguracaoEstatica from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $configuracaoEstatica = $this->configuracaoEstaticaRepository->findWithoutFail($id);

        if (empty($configuracaoEstatica)) {
            Flash::error('Configuracao Estatica '.trans('common.not-found'));

            return redirect(route('admin.configuracaoEstaticas.index'));
        }

        $this->configuracaoEstaticaRepository->delete($id);

        Flash::success('Configuracao Estatica '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.configuracaoEstaticas.index'));
    }
}
