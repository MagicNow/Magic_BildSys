<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\MascaraInsumoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateMascaraInsumoRequest;
use App\Http\Requests\Admin\UpdateMascaraInsumoRequest;
use App\Repositories\Admin\MascaraInsumoRepository;
use App\Repositories\CodeRepository;
use Flash;
use App\Models\LevantamentoTipo;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Storage;
use Response;
use DB;

class MascaraInsumoController extends AppBaseController
{
    /** @var  MascaraInsumoRepository */
    private $mascaraInsumoRepository;

    public function __construct(MascaraInsumoRepository $mascaraInsumoRepo)
    {
        $this->mascaraInsumoRepository = $mascaraInsumoRepo;
    }

    /**
     * Display a listing of the MascaraInsumo.
     *
     * @param MascaraInsumoDataTable $mascaraInsumoDataTable
     * @return Response
     */
    public function index(MascaraInsumoDataTable $mascaraInsumoDataTable)
    {
        return $mascaraInsumoDataTable->render('admin.mascara_insumos.index');
    }

    /**
     * Show the form for creating a new MascaraInsumo.
     *
     * @return Response
     */
    public function create()
    {
        $levantamentoTipos = LevantamentoTipo::pluck('nome','id')->toArray();        
        return view('admin.mascara_insumos.create', compact('levantamentoTipos'));
        
    }

    /**
     * Store a newly created MascaraInsumo in storage.
     *
     * @param CreateMascaraInsumoRequest $request
     *
     * @return Response
     */
    public function store(CreateMascaraInsumoRequest $request)
    {
        $input = $request->except('logo');

        foreach ($input as $item => $value){
            if($value == ''){
                $input[$item] = null;
            }
        }

        $mascaraInsumo = $this->mascaraInsumoRepository->create($input);

        if($request->logo) {
            $destinationPath = CodeRepository::saveFile($request->logo, 'mascara_insumos/' . $mascaraInsumo->id);

            $mascaraInsumo->logo = Storage::url($destinationPath);
            $mascaraInsumo->save();
        }

        $mascaraInsumo->save();

        Flash::success('Mascara de Insumo '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.mascara_insumos.index'));
    }

    /**
     * Display the specified MascaraInsumo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $mascaraInsumo = $this->mascaraInsumoRepository->findWithoutFail($id);
		
		//$levantamentoTipos = LevantamentoTipo::pluck('nome','id')->toArray();        

        if (empty($mascaraInsumo)) {
            Flash::error('Mascara de Insumo '.trans('common.not-found'));

            return redirect(route('admin.mascara_insumos.index'));
        }

        return view('admin.mascara_insumos.show')->with('mascaraInsumo', $mascaraInsumo);
    }

    /**
     * Show the form for editing the specified MascaraInsumo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $mascaraInsumo = $this->mascaraInsumoRepository->findWithoutFail($id);

        if (empty($mascaraInsumo)) {
            Flash::error('Mascara de Insumo '.trans('common.not-found'));

            return redirect(route('admin.mascara_insumos.index'));
        }

        $levantamentoTipos = LevantamentoTipo::pluck('nome','id')->toArray();	
		
        return view('admin.mascara_insumos.edit', compact('mascaraInsumo', 'levantamentoTipos'));
    }

    /**
     * Update the specified MascaraInsumo in storage.
     *
     * @param  int              $id
     * @param UpdateMascaraInsumoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMascaraInsumoRequest $request)
    {
        $mascaraInsumo = $this->mascaraInsumoRepository->findWithoutFail($id);

        if (empty($mascaraInsumo)) {
            Flash::error('MascaraInsumo '.trans('common.not-found'));

            return redirect(route('admin.mascara_insumos.index'));
        }

        if($request->logo){
            $destinationPath = CodeRepository::saveFile($request->logo, 'mascaraInsumos/' . $mascaraInsumo->id);
            $mascaraInsumo->logo = Storage::url($destinationPath);
            $mascaraInsumo->save();
        }

        $input = $request->except('logo');
        foreach ($input as $item => $value){
            if($value == ''){
                $input[$item] = null;
            }
        }

        $mascaraInsumo = $this->mascaraInsumoRepository->update($input, $id);

        $mascaraInsumo->update();

        Flash::success('Mascara de Insumo '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.mascara_insumos.index'));
    }

    /**
     * Remove the specified MascaraInsumo from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $mascaraInsumo = $this->mascaraInsumoRepository->findWithoutFail($id);

        if (empty($mascaraInsumo)) {
            Flash::error('MascaraInsumo '.trans('common.not-found'));

            return redirect(route('admin.mascara_insumos.index'));
        }

        if(count($mascaraInsumo->ordemDeCompras)){
            Flash::error('A mascaraInsumo não pode ser removida, pois tem ordens de compra.');

            return redirect(route('admin.mascara_insumos.index'));
        }

        $this->mascaraInsumoRepository->delete($id);

        Flash::success('Mascara de Insumo '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.mascara_insumos.index'));
    }
}
