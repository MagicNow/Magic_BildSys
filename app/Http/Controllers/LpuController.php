<?php

namespace App\Http\Controllers;

use App\DataTables\LpuDataTable;
use App\Http\Requests;
use App\Repositories\CodeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Response;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Requests\AtualizarValorRequest;
use App\Http\Requests\UpdateLpuRequest;
use App\Repositories\LpuRepository;
use App\Models\Regional;
use App\Models\InsumoGrupo;

class LpuController extends AppBaseController
{
    /** @var  LpuRepository */
    private $lpuRepository;

    public function __construct(LpuRepository $lpuRepo)
    {
        $this->lpuRepository = $lpuRepo;
    }

    /**
     * Display a listing of the Contrato.
     *
     * @param ContratoDataTable $contratoDataTable
     * @return Response
     */
    public function index( LpuDataTable $lpuDataTable) {
        
		$regionais = Regional::pluck('nome', 'id')->prepend('', '')->all();
		$insumoGrupo = InsumoGrupo::pluck('nome', 'id')->prepend('', '')->all();

        return $lpuDataTable->render('lpu.index',compact('regionais', 'insumoGrupo')
        );
    }

    public function show(){
        /*$carteira = $this->carteiraRepository->findWithoutFail($id);

        if (empty($carteira)) {
            Flash::error('Carteira '.trans('common.not-found'));

            return redirect(route('admin.carteiras.index'));
        }

        return view('lpu.show')->with('carteira', $carteira);*/
    }    

    public function edit($id)
    {
        $lpu = $this->lpuRepository->findWithoutFail($id);

        if (empty($lpu)) {
            Flash::error('Lpu '.trans('common.not-found'));

            return redirect(route('lpu.index'));
        }
     
        return view('lpu.edit', compact('lpu'));
    }

    public function update($id, UpdateLpuRequest $request)
    {
		
		$lpu = $this->lpuRepository->findWithoutFail($id);

        if (empty($lpu)) {
            Flash::error(' Lista de Preço Unitário'.trans('common.not-found'));

            return redirect(route('lpu.index'));
        }

        $lpu = $this->lpuRepository->update($request->all(), $id);

        Flash::success('Lista de Preço Unitário '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('lpu.index'));
		
        
    }
}
