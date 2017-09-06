<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\CarteiraDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateCarteiraRequest;
use App\Http\Requests\Admin\UpdateCarteiraRequest;
use App\Models\CarteiraUser;
use App\Models\TipoEqualizacaoTecnica;
use App\Repositories\Admin\CarteiraRepository;
use App\Repositories\CodeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Storage;
use Response;
use DB;
use App\Repositories\Admin\UserRepository;
use App\Repositories\Admin\TipoEqualizacaoTecnicaRepository;

class CarteiraController extends AppBaseController
{
    /** @var  CarteiraRepository */
    private $carteiraRepository;

    private $userRepository;

    public function __construct(CarteiraRepository $carteiraRepo, UserRepository $userRepository)
    {
        $this->carteiraRepository = $carteiraRepo;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the Carteira.
     *
     * @param CarteiraDataTable $carteiraDataTable
     * @return Response
     */
    public function index(CarteiraDataTable $carteiraDataTable)
    {
        return $carteiraDataTable->render('admin.carteiras.index');
    }

    /**
     * Show the form for creating a new Carteira.
     *
     * @return Response
     */
    public function create()
    {
        $relacionadoUsers = [];    
		$relacionadoTipoEqualizacaoTecnicas = [];

        $equalizacoesTecnicas = TipoEqualizacaoTecnica::pluck('nome', 'id')->all();

        $usuarios = $this->userRepository->getUsersByType(2)->pluck('name', 'id')->toArray(); // suprimentos

        return view('admin.carteiras.create', compact('relacionadoUsers'), compact('relacionadoTipoEqualizacaoTecnicas', 'equalizacoesTecnicas', 'usuarios'));
    }

    /**
     * Store a newly created Carteira in storage.
     *
     * @param CreateCarteiraRequest $request
     *
     * @return Response
     */
    public function store(CreateCarteiraRequest $request)
    {
        $input = $request->except('logo');

        foreach ($input as $item => $value){
            if($value == ''){
                $input[$item] = null;
            }
        }

        $carteira = $this->carteiraRepository->create($input);

        if($request->logo) {
            $destinationPath = CodeRepository::saveFile($request->logo, 'carteiras/' . $carteira->id);

            $carteira->logo = Storage::url($destinationPath);
            $carteira->save();
        }

        $carteira->save();

        Flash::success('Carteira '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.carteiras.index'));
    }

    /**
     * Display the specified Carteira.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carteira = $this->carteiraRepository->findWithoutFail($id);

        if (empty($carteira)) {
            Flash::error('Carteira '.trans('common.not-found'));

            return redirect(route('admin.carteiras.index'));
        }

        return view('admin.carteiras.show')->with('carteira', $carteira);
    }

    /**
     * Show the form for editing the specified Carteira.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id, UserRepository $userRepository, TipoEqualizacaoTecnicaRepository $tipoEqualizacaoTecnicaRepository)
    {
        $carteira = $this->carteiraRepository->findWithoutFail($id);

        if (empty($carteira)) {
            Flash::error('Carteira '.trans('common.not-found'));

            return redirect(route('admin.carteiras.index'));
        }

        $relacionadoUsers = $userRepository->usuariosDaCarteira($id);
        $carteiraUsers = $relacionadoUsers->pluck('id')->all();
        $relacionadoUsers = $relacionadoUsers->pluck('name', 'id')->all();		
		
		$relacionadoTipoEqualizacaoTecnicas = $tipoEqualizacaoTecnicaRepository->tiposEqualizacaoTecnicasDaCarteira($id);
        $carteiraTipoEqualizacaoTecnicas = $relacionadoTipoEqualizacaoTecnicas->pluck('id')->all();
        $relacionadoTipoEqualizacaoTecnicas = $relacionadoTipoEqualizacaoTecnicas->pluck('nome', 'id')->all();

        $equalizacoesTecnicas = TipoEqualizacaoTecnica::pluck('nome', 'id')->all();

        $usuarios = $this->userRepository->getUsersByType(2)->pluck('name', 'id')->toArray(); // suprimentos

        
        return view('admin.carteiras.edit', compact('carteira', 'relacionadoUsers', 'carteiraUsers' ,'relacionadoTipoEqualizacaoTecnicas', 'carteiraTipoEqualizacaoTecnicas', 'equalizacoesTecnicas', 'usuarios'));
    }

    /**
     * Update the specified Carteira in storage.
     *
     * @param  int              $id
     * @param UpdateCarteiraRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarteiraRequest $request)
    {
        $carteira = $this->carteiraRepository->findWithoutFail($id);

        if (empty($carteira)) {
            Flash::error('Carteira '.trans('common.not-found'));

            return redirect(route('admin.carteiras.index'));
        }

        if($request->logo){
            $destinationPath = CodeRepository::saveFile($request->logo, 'carteiras/' . $carteira->id);
            $carteira->logo = Storage::url($destinationPath);
            $carteira->save();
        }

        $input = $request->except('logo');
        foreach ($input as $item => $value){
            if($value == ''){
                $input[$item] = null;
            }
        }

        $carteira = $this->carteiraRepository->update($input, $id);

        $carteira->update();

        Flash::success('Carteira '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.carteiras.index'));
    }

    /**
     * Remove the specified Carteira from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $carteira = $this->carteiraRepository->findWithoutFail($id);

        if (empty($carteira)) {
            Flash::error('Carteira '.trans('common.not-found'));

            return redirect(route('admin.carteiras.index'));
        }

        if(count($carteira->ordemDeCompras)){
            Flash::error('A carteira não pode ser removida, pois tem ordens de compra.');

            return redirect(route('admin.carteiras.index'));
        }

        $this->carteiraRepository->delete($id);

        Flash::success('Carteira '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.carteiras.index'));
    }
}
