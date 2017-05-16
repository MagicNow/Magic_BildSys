<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\SolicitacaoInsumoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateSolicitacaoInsumoRequest;
use App\Http\Requests\Admin\UpdateSolicitacaoInsumoRequest;
use App\Models\InsumoGrupo;
use App\Repositories\Admin\SolicitacaoInsumoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;

class SolicitacaoInsumoController extends AppBaseController
{
    /** @var  SolicitacaoInsumoRepository */
    private $solicitacaoInsumoRepository;

    public function __construct(SolicitacaoInsumoRepository $solicitacaoInsumoRepo)
    {
        $this->solicitacaoInsumoRepository = $solicitacaoInsumoRepo;
    }

    /**
     * Display a listing of the SolicitacaoInsumo.
     *
     * @param SolicitacaoInsumoDataTable $solicitacaoInsumoDataTable
     * @return Response
     */
    public function index(SolicitacaoInsumoDataTable $solicitacaoInsumoDataTable)
    {
        return $solicitacaoInsumoDataTable->render('admin.solicitacao_insumos.index');
    }

    /**
     * Show the form for creating a new SolicitacaoInsumo.
     *
     * @return Response
     */
    public function create()
    {
        $insumo_grupos = [];
        return view('admin.solicitacao_insumos.create', compact('insumo_grupos'));
    }

    /**
     * Store a newly created SolicitacaoInsumo in storage.
     *
     * @param CreateSolicitacaoInsumoRequest $request
     *
     * @return Response
     */
    public function store(CreateSolicitacaoInsumoRequest $request)
    {
        $input = $request->all();

        $solicitacaoInsumo = $this->solicitacaoInsumoRepository->create($input);

        Flash::success('Solicitacao Insumo '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.solicitacaoInsumos.index'));
    }

    /**
     * Display the specified SolicitacaoInsumo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $solicitacaoInsumo = $this->solicitacaoInsumoRepository->findWithoutFail($id);

        if (empty($solicitacaoInsumo)) {
            Flash::error('Solicitacao Insumo '.trans('common.not-found'));

            return redirect(route('admin.solicitacaoInsumos.index'));
        }

        return view('admin.solicitacao_insumos.show')->with('solicitacaoInsumo', $solicitacaoInsumo);
    }

    /**
     * Show the form for editing the specified SolicitacaoInsumo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $solicitacaoInsumo = $this->solicitacaoInsumoRepository->findWithoutFail($id);

        $insumo_grupos = InsumoGrupo::pluck('nome', 'id')->toArray();

        if (empty($solicitacaoInsumo)) {
            Flash::error('Solicitacao Insumo '.trans('common.not-found'));

            return redirect(route('admin.solicitacaoInsumos.index'));
        }

        return view('admin.solicitacao_insumos.edit', compact('insumo_grupos'))->with('solicitacaoInsumo', $solicitacaoInsumo);
    }

    /**
     * Update the specified SolicitacaoInsumo in storage.
     *
     * @param  int              $id
     * @param UpdateSolicitacaoInsumoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSolicitacaoInsumoRequest $request)
    {
        $solicitacaoInsumo = $this->solicitacaoInsumoRepository->findWithoutFail($id);

        if (empty($solicitacaoInsumo)) {
            Flash::error('Solicitacao Insumo '.trans('common.not-found'));

            return redirect(route('admin.solicitacaoInsumos.index'));
        }

        $solicitacaoInsumo = $this->solicitacaoInsumoRepository->update($request->all(), $id);

        Flash::success('Solicitacao Insumo '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.solicitacaoInsumos.index'));
    }

    /**
     * Remove the specified SolicitacaoInsumo from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $solicitacaoInsumo = $this->solicitacaoInsumoRepository->findWithoutFail($id);

        if (empty($solicitacaoInsumo)) {
            Flash::error('Solicitacao Insumo '.trans('common.not-found'));

            return redirect(route('admin.solicitacaoInsumos.index'));
        }

        $this->solicitacaoInsumoRepository->delete($id);

        Flash::success('Solicitacao Insumo '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.solicitacaoInsumos.index'));
    }

    public function buscaGruposInsumos(Request $request){
        $insumo_grupos = InsumoGrupo::select([
            'id',
            'nome'
        ])
            ->where('nome','like', '%'.$request->q.'%')->paginate();

        return $insumo_grupos;
    }

    public function solicitarInsumo($obra_id)
    {
        $insumo_grupos = [];
        return view('admin.solicitacao_insumos.create-front', compact('insumo_grupos', 'obra_id'));
    }

    public function solicitarInsumoSalvar(CreateSolicitacaoInsumoRequest $request, $obra_id)
    {
        $input = $request->all();

        $solicitacaoInsumo = $this->solicitacaoInsumoRepository->create($input);

        Flash::success('Solicitação de insumo realizada.');

        return redirect('/compras/insumos/orcamento/'.$obra_id);
    }
}
