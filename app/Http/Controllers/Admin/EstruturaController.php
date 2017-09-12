<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\EstruturaDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateEstruturaRequest;
use App\Http\Requests\Admin\UpdateEstruturaRequest;
use App\Models\EstruturaUser;
use App\Models\User;
use App\Repositories\Admin\EstruturaRepository;
use App\Repositories\CodeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Storage;
use Response;
use DB;

class EstruturaController extends AppBaseController
{
    /** @var  EstruturaRepository */
    private $estruturaRepository;

    public function __construct(EstruturaRepository $estruturaRepo)
    {
        $this->estruturaRepository = $estruturaRepo;
    }

    /**
     * Display a listing of the Estrutura.
     *
     * @param EstruturaDataTable $estruturaDataTable
     * @return Response
     */
    public function index(EstruturaDataTable $estruturaDataTable)
    {
        return $estruturaDataTable->render('admin.estruturas.index');
    }

    /**
     * Show the form for creating a new Estrutura.
     *
     * @return Response
     */
    public function create()
    {
        $relacionadoUsers = [];    
		$relacionadoTipoEqualizacaoTecnicas = [];

        return view('admin.estruturas.create', compact('relacionadoUsers'), compact('relacionadoTipoEqualizacaoTecnicas'));
    }

    /**
     * Store a newly created Estrutura in storage.
     *
     * @param CreateEstruturaRequest $request
     *
     * @return Response
     */
    public function store(CreateEstruturaRequest $request)
    {
        $input = $request->except('logo');

        foreach ($input as $item => $value){
            if($value == ''){
                $input[$item] = null;
            }
        }

        $estrutura = $this->estruturaRepository->create($input);

        if($request->logo) {
            $destinationPath = CodeRepository::saveFile($request->logo, 'estruturas/' . $estrutura->id);

            $estrutura->logo = Storage::url($destinationPath);
            $estrutura->save();
        }

        $estrutura->save();

        Flash::success('Estrutura '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.estruturas.index'));
    }

    /**
     * Display the specified Estrutura.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $estrutura = $this->estruturaRepository->findWithoutFail($id);

        if (empty($estrutura)) {
            Flash::error('Estrutura '.trans('common.not-found'));

            return redirect(route('admin.estruturas.index'));
        }

        return view('admin.estruturas.show')->with('estrutura', $estrutura);
    }

    /**
     * Show the form for editing the specified Estrutura.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $estrutura = $this->estruturaRepository->findWithoutFail($id);

        if (empty($estrutura)) {
            Flash::error('Estrutura '.trans('common.not-found'));

            return redirect(route('admin.estruturas.index'));
        }
        
        return view('admin.estruturas.edit', compact('estrutura'));
    }

    /**
     * Update the specified Estrutura in storage.
     *
     * @param  int              $id
     * @param UpdateEstruturaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEstruturaRequest $request)
    {
        $estrutura = $this->estruturaRepository->findWithoutFail($id);

        if (empty($estrutura)) {
            Flash::error('Estrutura '.trans('common.not-found'));

            return redirect(route('admin.estruturas.index'));
        }

        if($request->logo){
            $destinationPath = CodeRepository::saveFile($request->logo, 'estruturas/' . $estrutura->id);
            $estrutura->logo = Storage::url($destinationPath);
            $estrutura->save();
        }

        $input = $request->except('logo');
        foreach ($input as $item => $value){
            if($value == ''){
                $input[$item] = null;
            }
        }

        $estrutura = $this->estruturaRepository->update($input, $id);

        $estrutura->update();

        Flash::success('Estrutura '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.estruturas.index'));
    }

    /**
     * Remove the specified Estrutura from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $estrutura = $this->estruturaRepository->findWithoutFail($id);

        if (empty($estrutura)) {
            Flash::error('Estrutura '.trans('common.not-found'));

            return redirect(route('admin.estruturas.index'));
        }

        if(count($estrutura->ordemDeCompras)){
            Flash::error('A estrutura não pode ser removida, pois tem ordens de compra.');

            return redirect(route('admin.estruturas.index'));
        }

        $this->estruturaRepository->delete($id);

        Flash::success('Estrutura '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.estruturas.index'));
    }
}
