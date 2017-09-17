<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\MascaraPadraoInsumoDataTable;
use App\DataTables\Admin\SemMascaraPadraoInsumoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateMascaraPadraoInsumoRequest;
use App\Http\Requests\Admin\UpdateMascaraPadraoInsumoRequest;
use App\Models\MascaraPadraoInsumo;
use App\Models\Insumo;
use App\Models\InsumoGrupo;
use App\Models\MascaraPadrao;
use App\Repositories\Admin\MascaraPadraoInsumoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;

class MascaraPadraoInsumoController extends AppBaseController
{
    /** @var  MascaraPadraoInsumoRepository */
    private $mascaraPadraoInsumoRepository;

    public function __construct(MascaraPadraoInsumoRepository $mascaraPadraoInsumoRepo)
    {
        $this->mascaraPadraoInsumoRepository = $mascaraPadraoInsumoRepo;
    }

    /**
     * Display a listing of the MascaraPadraoInsumo.
     *
     * @param MascaraPadraoInsumoDataTable $mascaraPadraoInsumoDataTable
     * @return Response
     */
    public function index(MascaraPadraoInsumoDataTable $mascaraPadraoInsumoDataTable)
    {
        return $mascaraPadraoInsumoDataTable->render('admin.mascara_padrao_insumos.index');
    }

    /**
     * Show the form for creating a new MascaraPadraoInsumo.
     *
     * @return Response
     */
    public function create()
    {
        $grupoInsumos = InsumoGrupo::where('active', true)->pluck('nome', 'id')->toArray();
		
		$mascaraPadrao = MascaraPadrao::pluck('nome', 'id')->toArray();
        
        return view('admin.mascara_padrao_insumos.create', compact('grupoInsumos', 'mascaraPadrao'));
    }

    /**
     * Store a newly created MascaraPadraoInsumo in storage.
     *
     * @param CreateMascaraPadraoInsumoRequest $request
     *
     * @return Response
     */
    public function store(CreateMascaraPadraoInsumoRequest $request)
    {
        if (isset($request->insumo_id)) {
            foreach ($request->insumo_id as $insumo_id) {
				
				$insumo = Insumo::where('id', $request->insumo_id)->first();
				$codigo_estruturado = "01.01.01.01.001.".$insumo->codigo;
				
                MascaraPadraoInsumo::firstOrCreate([
                    'mascara_padrao_id' => $request->mascara_padrao_id,
					'codigo_insumo' => $codigo_estruturado,					
                    'insumo_id' => $insumo_id,
					'coeficiente' => $request->coeficiente
                ]);
            }
        } else {
            Flash::error('Você esqueceu de escolher os insumos!');
            return redirect(route('admin.mascara_padrao_insumos.index'));
        }

        Flash::success(
            'Relacionamento '.trans('common.saved').' '.trans('common.successfully').'.'
        );

        return redirect(route('admin.mascara_padrao_insumos.index'));
    }

    /**
     * Display the specified MascaraPadraoInsumo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $mascaraPadraoInsumo = $this->mascaraPadraoInsumoRepository->findWithoutFail($id);

        if (empty($mascaraPadraoInsumo)) {
            Flash::error('Máscara Padrão / Insumos'.trans('common.not-found'));

            return redirect(route('admin.mascara_padrao_insumos.index'));
        }

        return view('admin.mascara_padrao_insumos.show')->with('mascaraPadraoInsumo', $mascaraPadraoInsumo);
    }

    /**
     * Show the form for editing the specified MascaraPadraoInsumo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $mascaraPadraoInsumo = $this->mascaraPadraoInsumoRepository->findWithoutFail($id);
		
		$mascaraPadrao = MascaraPadrao::pluck('nome', 'id')->toArray();
		
		$insumos = Insumo::where('active', true)->pluck('nome', 'id')->toArray();

        if (empty($mascaraPadraoInsumo)) {
            Flash::error('Máscara Padrão / Insumos'.trans('common.not-found'));

            return redirect(route('admin.mascara_padrao_insumos.index'));
        }

        return view('admin.mascara_padrao_insumos.edit', compact('mascaraPadraoInsumo','mascaraPadrao','insumos'));
    }

    /**
     * Update the specified MascaraPadraoInsumo in storage.
     *
     * @param  int              $id
     * @param UpdateMascaraPadraoInsumoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMascaraPadraoInsumoRequest $request)
    {
        $mascaraPadraoInsumo = $this->mascaraPadraoInsumoRepository->findWithoutFail($id);

        if (empty($mascaraPadraoInsumo)) {
            Flash::error('Máscara Padrão / Insumos'.trans('common.not-found'));

            return redirect(route('admin.mascara_padrao_insumos.index'));
        }

        $mascaraPadraoInsumo = $this->mascaraPadraoInsumoRepository->update($request->all(), $id);

        Flash::success('Máscara Padrão / Insumos'.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.mascara_padrao_insumos.index'));
    }

    /**
     * Remove the specified MascaraPadraoInsumo from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $mascaraPadraoInsumo = $this->mascaraPadraoInsumoRepository->findWithoutFail($id);

        if (empty($mascaraPadraoInsumo)) {
            Flash::error('Máscara Padrão / Insumos'.trans('common.not-found'));

            return redirect(route('admin.mascara_padrao_insumos.index'));
        }

        $this->mascaraPadraoInsumoRepository->delete($id);

        Flash::success('Máscara Padrão / Insumos'.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.mascara_padrao_insumos.index'));
    }
	
	/**
     * Display the specified MascaraPadraoInsumo without association with any Insumos
     *
     * @return Response
     */	
    public function semInsumoView(SemMascaraPadraoInsumoDataTable $semMascaraPadraoInsumoDataTable)
    {
		$grupoInsumos = InsumoGrupo::where('active', true)->pluck('nome', 'id')->toArray();

		$insumos = Insumo::where('active', true)->pluck('nome', 'id')->toArray();

        return $semMascaraPadraoInsumoDataTable->render('admin.mascara_padrao_insumos.sem_insumo', compact('grupoInsumos', 'insumos'));   
    }

    public function deleteBlocoView()
    {
        $mascaraPadrao = MascaraPadrao::pluck('nome', 'id')->toArray();
		
//        $grupoInsumos = InsumoGrupo::select([
//            'insumo_grupos.nome',
//            'insumo_grupos.id'
//            ])
//            ->join('insumos','insumos.insumo_grupo_id','=', 'insumo_grupos.id')
//            ->join('mascara_padrao_insumos','mascara_padrao_insumos.insumo_id','insumos.id')
//            ->pluck('nome','id')->toArray();

        return view('admin.mascara_padrao_insumos.blocoview', compact('mascaraPadrao'));
    }

    public function buscaGrupoInsumo($mascara_padrao_id)
    {
        $grupoInsumos = InsumoGrupo::select([
            'insumo_grupos.nome',
            'insumo_grupos.id'
        ])
        ->join('insumos', 'insumos.insumo_grupo_id', '=', 'insumo_grupos.id')
        ->join('mascara_padrao_insumos', 'mascara_padrao_insumos.insumo_id', 'insumos.id')
        ->where('mascara_padrao_insumos.mascara_padrao_id', $mascara_padrao_id)
        ->where('insumos.active', true)
        ->where('insumo_grupos.active', true)
        ->pluck('nome', 'id')
        ->toArray();
        return $grupoInsumos;
    }

    public function deleteBloco(Request $request)
    {
        $removendo = false;
        if($request->mascara_padrao_id && $request->grupo_insumo_id) {
            $removendo = MascaraPadraoInsumo::whereRaw(
                'insumo_id IN
                (SELECT id
                    FROM insumos
                    WHERE insumo_grupo_id = ' . $request->grupo_insumo_id . ')
            ')
                ->where('mascara_padrao_id', $request->mascara_padrao_id)
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
