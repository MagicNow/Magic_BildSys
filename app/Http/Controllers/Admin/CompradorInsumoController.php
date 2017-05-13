<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\CompradorInsumoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateCompradorInsumoRequest;
use App\Http\Requests\Admin\UpdateCompradorInsumoRequest;
use App\Models\CompradorInsumo;
use App\Models\Insumo;
use App\Models\InsumoGrupo;
use App\Models\User;
use App\Repositories\Admin\CompradorInsumoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;

class CompradorInsumoController extends AppBaseController
{
    /** @var  CompradorInsumoRepository */
    private $compradorInsumoRepository;

    public function __construct(CompradorInsumoRepository $compradorInsumoRepo)
    {
        $this->compradorInsumoRepository = $compradorInsumoRepo;
    }

    /**
     * Display a listing of the CompradorInsumo.
     *
     * @param CompradorInsumoDataTable $compradorInsumoDataTable
     * @return Response
     */
    public function index(CompradorInsumoDataTable $compradorInsumoDataTable)
    {
        return $compradorInsumoDataTable->render('admin.comprador_insumos.index');
    }

    /**
     * Show the form for creating a new CompradorInsumo.
     *
     * @return Response
     */
    public function create()
    {
        $grupoInsumos = InsumoGrupo::pluck('nome','id')->toArray();
        $users = User::pluck('name','id')->toArray();
        return view('admin.comprador_insumos.create', compact('grupoInsumos','users'));
    }

    /**
     * Store a newly created CompradorInsumo in storage.
     *
     * @param CreateCompradorInsumoRequest $request
     *
     * @return Response
     */
    public function store(CreateCompradorInsumoRequest $request)
    {

        if(isset($request->insumo_id)){
            foreach($request->insumo_id as $insumo_id){
                CompradorInsumo::firstOrCreate([
                    'user_id' => $request->usuario_id,
                    'insumo_id' => $insumo_id
                ]);

            }
        }else{
            Flash::error('Você esqueceu de escolher os insumos!');
            return redirect(route('admin.compradorInsumos.index'));
        }

        Flash::success('Relacionamento '.trans('common.saved').' '.trans('common.successfully').'.');
        return redirect(route('admin.compradorInsumos.index'));
    }

    /**
     * Display the specified CompradorInsumo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $compradorInsumo = $this->compradorInsumoRepository->findWithoutFail($id);

        if (empty($compradorInsumo)) {
            Flash::error('Comprador Insumo '.trans('common.not-found'));

            return redirect(route('admin.compradorInsumos.index'));
        }

        return view('admin.comprador_insumos.show')->with('compradorInsumo', $compradorInsumo);
    }

    /**
     * Show the form for editing the specified CompradorInsumo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $compradorInsumo = $this->compradorInsumoRepository->findWithoutFail($id);

        if (empty($compradorInsumo)) {
            Flash::error('Comprador Insumo '.trans('common.not-found'));

            return redirect(route('admin.compradorInsumos.index'));
        }

        return view('admin.comprador_insumos.edit')->with('compradorInsumo', $compradorInsumo);
    }

    /**
     * Update the specified CompradorInsumo in storage.
     *
     * @param  int              $id
     * @param UpdateCompradorInsumoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCompradorInsumoRequest $request)
    {
        $compradorInsumo = $this->compradorInsumoRepository->findWithoutFail($id);

        if (empty($compradorInsumo)) {
            Flash::error('Comprador Insumo '.trans('common.not-found'));

            return redirect(route('admin.compradorInsumos.index'));
        }

        $compradorInsumo = $this->compradorInsumoRepository->update($request->all(), $id);

        Flash::success('Comprador Insumo '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.compradorInsumos.index'));
    }

    /**
     * Remove the specified CompradorInsumo from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $compradorInsumo = $this->compradorInsumoRepository->findWithoutFail($id);

        if (empty($compradorInsumo)) {
            Flash::error('Comprador Insumo '.trans('common.not-found'));

            return redirect(route('admin.compradorInsumos.index'));
        }

        $this->compradorInsumoRepository->delete($id);

        Flash::success('Comprador Insumo '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.compradorInsumos.index'));
    }

    public function deleteBlocoView()
    {

        $users = User::pluck('name','id')->toArray();
//        $grupoInsumos = InsumoGrupo::select([
//            'insumo_grupos.nome',
//            'insumo_grupos.id'
//            ])
//            ->join('insumos','insumos.insumo_grupo_id','=', 'insumo_grupos.id')
//            ->join('comprador_insumos','comprador_insumos.insumo_id','insumos.id')
//            ->pluck('nome','id')->toArray();

        return view('admin.comprador_insumos.blocoview', compact('users'));
    }

    public function buscaGrupoInsumo($usuario_id){
        $grupoInsumos = InsumoGrupo::select([
            'insumo_grupos.nome',
            'insumo_grupos.id'
        ])
            ->join('insumos','insumos.insumo_grupo_id','=', 'insumo_grupos.id')
            ->join('comprador_insumos','comprador_insumos.insumo_id','insumos.id')
            ->where('comprador_insumos.user_id', $usuario_id)
            ->pluck('nome','id')->toArray();
        return $grupoInsumos;
    }

    public function deleteBloco(Request $request)
    {
        $removendo = CompradorInsumo::whereRaw(
            'insumo_id IN
                (SELECT id
                    FROM insumos
                    WHERE insumo_grupo_id = '.$request->grupo_insumo_id.')
            ')
            ->where('user_id', $request->usuario_id)
            ->delete();

        return Response()->json(['success' => $removendo]);
    }

    public function getInsumos($grupo_insumo_id){
        $insumos = Insumo::select([
            'insumos.id',
            'insumos.nome'
        ])
            ->join('insumo_grupos','insumo_grupos.id','=','insumos.insumo_grupo_id')
            ->where('insumos.insumo_grupo_id', $grupo_insumo_id)
            ->get();

        return $insumos;
    }
}
