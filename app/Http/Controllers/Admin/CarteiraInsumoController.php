<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\CarteiraInsumoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateCarteiraInsumoRequest;
use App\Http\Requests\Admin\UpdateCarteiraInsumoRequest;
use App\Models\CarteiraInsumo;
use App\Models\Insumo;
use App\Models\InsumoGrupo;
use App\Models\Carteira;
use App\Repositories\Admin\CarteiraInsumoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;

class CarteiraInsumoController extends AppBaseController
{
    /** @var  CarteiraInsumoRepository */
    private $carteiraInsumoRepository;

    public function __construct(CarteiraInsumoRepository $carteiraInsumoRepo)
    {
        $this->carteiraInsumoRepository = $carteiraInsumoRepo;
    }

    /**
     * Display a listing of the CarteiraInsumo.
     *
     * @param CarteiraInsumoDataTable $carteiraInsumoDataTable
     * @return Response
     */
    public function index(CarteiraInsumoDataTable $carteiraInsumoDataTable)
    {
        return $carteiraInsumoDataTable->render('admin.carteira_insumos.index');
    }

    /**
     * Show the form for creating a new CarteiraInsumo.
     *
     * @return Response
     */
    public function create()
    {
        $grupoInsumos = InsumoGrupo::where('active', true)->pluck('nome', 'id')->toArray();

       $carteiras = Carteira::where('active', true)->pluck('nome', 'id')->toArray();

        return view('admin.carteira_insumos.create', compact('grupoInsumos', 'carteiras'));
    }

    /**
     * Store a newly created CarteiraInsumo in storage.
     *
     * @param CreateCarteiraInsumoRequest $request
     *
     * @return Response
     */
    public function store(CreateCarteiraInsumoRequest $request)
    {
        if (isset($request->insumo_id)) {
            foreach ($request->insumo_id as $insumo_id) {
                CarteiraInsumo::firstOrCreate([
                    'carteira_id' => $request->carteira_id,
                    'insumo_id' => $insumo_id
                ]);
            }
        } else {
            Flash::error('Você esqueceu de escolher os insumos!');
            return redirect(route('admin.carteiraInsumos.index'));
        }

        Flash::success(
            'Relacionamento '.trans('common.saved').' '.trans('common.successfully').'.'
        );

        return redirect(route('admin.carteiraInsumos.index'));
    }

    /**
     * Display the specified CarteiraInsumo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carteiraInsumo = $this->carteiraInsumoRepository->findWithoutFail($id);

        if (empty($carteiraInsumo)) {
            Flash::error('Carteira Insumo '.trans('common.not-found'));

            return redirect(route('admin.carteiraInsumos.index'));
        }

        return view('admin.carteira_insumos.show')->with('carteiraInsumo', $carteiraInsumo);
    }

    /**
     * Show the form for editing the specified CarteiraInsumo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carteiraInsumo = $this->carteiraInsumoRepository->findWithoutFail($id);

        if (empty($carteiraInsumo)) {
            Flash::error('Carteira Insumo '.trans('common.not-found'));

            return redirect(route('admin.carteiraInsumos.index'));
        }

        return view('admin.carteira_insumos.edit')->with('carteiraInsumo', $carteiraInsumo);
    }

    /**
     * Update the specified CarteiraInsumo in storage.
     *
     * @param  int              $id
     * @param UpdateCarteiraInsumoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarteiraInsumoRequest $request)
    {
        $carteiraInsumo = $this->carteiraInsumoRepository->findWithoutFail($id);

        if (empty($carteiraInsumo)) {
            Flash::error('Carteira Insumo '.trans('common.not-found'));

            return redirect(route('admin.carteiraInsumos.index'));
        }

        $carteiraInsumo = $this->carteiraInsumoRepository->update($request->all(), $id);

        Flash::success('Carteira Insumo '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.carteiraInsumos.index'));
    }

    /**
     * Remove the specified CarteiraInsumo from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $carteiraInsumo = $this->carteiraInsumoRepository->findWithoutFail($id);

        if (empty($carteiraInsumo)) {
            Flash::error('Carteira Insumo '.trans('common.not-found'));

            return redirect(route('admin.carteiraInsumos.index'));
        }

        $this->carteiraInsumoRepository->delete($id);

        Flash::success('Carteira Insumo '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.carteiraInsumos.index'));
    }

    public function deleteBlocoView()
    {
        $carteiras = Carteira::where('active', true)->pluck('nome', 'id')->toArray();
		
//        $grupoInsumos = InsumoGrupo::select([
//            'insumo_grupos.nome',
//            'insumo_grupos.id'
//            ])
//            ->join('insumos','insumos.insumo_grupo_id','=', 'insumo_grupos.id')
//            ->join('carteira_insumos','carteira_insumos.insumo_id','insumos.id')
//            ->pluck('nome','id')->toArray();

        return view('admin.carteira_insumos.blocoview', compact('carteiras'));
    }

    public function buscaGrupoInsumo($carteira_id)
    {
        $grupoInsumos = InsumoGrupo::select([
            'insumo_grupos.nome',
            'insumo_grupos.id'
        ])
        ->join('insumos', 'insumos.insumo_grupo_id', '=', 'insumo_grupos.id')
        ->join('carteira_insumos', 'carteira_insumos.insumo_id', 'insumos.id')
        ->where('carteira_insumos.carteira_id', $carteira_id)
        ->where('insumos.active', true)
        ->where('insumo_grupos.active', true)
        ->pluck('nome', 'id')
        ->toArray();
        return $grupoInsumos;
    }

    public function deleteBloco(Request $request)
    {
        $removendo = false;
        if($request->carteira_id && $request->grupo_insumo_id) {
            $removendo = CarteiraInsumo::whereRaw(
                'insumo_id IN
                (SELECT id
                    FROM insumos
                    WHERE insumo_grupo_id = ' . $request->grupo_insumo_id . ')
            ')
                ->where('carteira_id', $request->carteira_id)
                ->delete();
        }

        return Response()->json(['success' => $removendo]);
    }

    public function getInsumos($grupo_insumo_id)
    {
        $insumos = Insumo::select([
            'insumos.id',
            'insumos.nome'
        ])
            ->join('insumo_grupos', 'insumo_grupos.id', '=', 'insumos.insumo_grupo_id')
            ->where('insumos.insumo_grupo_id', $grupo_insumo_id)
            ->where('insumos.active', true)
            ->where('insumo_grupos.active', true)
            ->get();

        return $insumos;
    }
}
