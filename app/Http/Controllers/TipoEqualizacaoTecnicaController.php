<?php

namespace App\Http\Controllers;

use App\DataTables\Admin\TipoEqualizacaoTecnicaDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateTipoEqualizacaoTecnicaRequest;
use App\Http\Requests\Admin\UpdateTipoEqualizacaoTecnicaRequest;
use App\Models\TipoEqualizacaoTecnica;
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
        return $tipoEqualizacaoTecnicaDataTable->render('tipo_equalizacao_tecnicas.index');
    }

    /**
     * Show the form for creating a new TipoEqualizacaoTecnica.
     *
     * @return Response
     */
    public function create()
    {
        return view('tipo_equalizacao_tecnicas.create');
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
        $input = $request->all();

        $this->tipoEqualizacaoTecnicaRepository->create($input);

        Flash::success('Tipo Equalizacao Tecnica '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('tipoEqualizacaoTecnicas.index'));
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

            return redirect(route('tipoEqualizacaoTecnicas.index'));
        }

        return view('tipo_equalizacao_tecnicas.show')->with('tipoEqualizacaoTecnica', $tipoEqualizacaoTecnica);
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

            return redirect(route('tipoEqualizacaoTecnicas.index'));
        }

        return view('tipo_equalizacao_tecnicas.edit')->with('tipoEqualizacaoTecnica', $tipoEqualizacaoTecnica);
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

            return redirect(route('tipoEqualizacaoTecnicas.index'));
        }

        $tipoEqualizacaoTecnica = $this->tipoEqualizacaoTecnicaRepository->update($request->all(), $id);

        Flash::success('Tipo Equalizacao Tecnica '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('tipoEqualizacaoTecnicas.index'));
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

            return redirect(route('tipoEqualizacaoTecnicas.index'));
        }

        $this->tipoEqualizacaoTecnicaRepository->delete($id);

        Flash::success('Tipo Equalizacao Tecnica '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('tipoEqualizacaoTecnicas.index'));
    }

    public function busca(Request $request){
        $retorno = TipoEqualizacaoTecnica::select([
            'id',
            'nome'
        ])
            ->where('nome','like', '%'.$request->q.'%');
        return $retorno->paginate();
    }
    
    public function buscaItens($id)
    {
        $tipoEqualizacaoTecnica = $this->tipoEqualizacaoTecnicaRepository->findWithoutFail($id);
        if (empty($tipoEqualizacaoTecnica)) {
            return response()->json(['error'=>'Não encontrado'],404);
        }
        
        return response()->json($tipoEqualizacaoTecnica->itens);
    }
    public function buscaAnexos($id)
    {
        $tipoEqualizacaoTecnica = $this->tipoEqualizacaoTecnicaRepository->findWithoutFail($id);
        if (empty($tipoEqualizacaoTecnica)) {
            return response()->json(['error'=>'Não encontrado'],404);
        }

        return response()->json($tipoEqualizacaoTecnica->anexos);
    }

}
