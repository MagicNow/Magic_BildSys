<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\ObraDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateObraRequest;
use App\Http\Requests\Admin\UpdateObraRequest;
use App\Models\Cidade;
use App\Models\ObraUser;
use App\Models\PadraoEmpreendimento;
use App\Models\Regional;
use App\Models\User;
use App\Repositories\Admin\ObraRepository;
use App\Repositories\CodeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Storage;
use Response;
use DB;
use App\Repositories\Admin\UserRepository;

class ObraController extends AppBaseController
{
    /** @var  ObraRepository */
    private $obraRepository;

    public function __construct(ObraRepository $obraRepo)
    {
        $this->obraRepository = $obraRepo;
    }

    /**
     * Display a listing of the Obra.
     *
     * @param ObraDataTable $obraDataTable
     * @return Response
     */
    public function index(ObraDataTable $obraDataTable)
    {
        return $obraDataTable->render('admin.obras.index');
    }

    /**
     * Show the form for creating a new Obra.
     *
     * @return Response
     */
    public function create()
    {
        $relacionados = [];

        $cidades = Cidade::orderBy("nome_completo")
            ->select(DB::raw('concat(nome_completo, " - ", uf) as nome_final'), 'id')
            ->get()
            ->pluck('nome_final', 'id');

        $regionais = Regional::pluck('nome', 'id')->prepend('', '')->all();
        $padrao_empreendimentos = PadraoEmpreendimento::pluck('nome', 'id')->prepend('', '')->all();

        return view('admin.obras.create', compact('relacionados', 'cidades', 'regionais', 'padrao_empreendimentos'));
    }

    /**
     * Store a newly created Obra in storage.
     *
     * @param CreateObraRequest $request
     *
     * @return Response
     */
    public function store(CreateObraRequest $request)
    {
        $input = $request->except('logo');

        foreach ($input as $item => $value){
            if($value == ''){
                $input[$item] = null;
            }
        }

        $obra = $this->obraRepository->create($input);

        if($request->logo) {
            $destinationPath = CodeRepository::saveFile($request->logo, 'obras/' . $obra->id);

            $obra->logo = Storage::url($destinationPath);
            $obra->save();
        }

        $obra->save();

        Flash::success('Obra '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.obras.index'));
    }

    /**
     * Display the specified Obra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $obra = $this->obraRepository->findWithoutFail($id);

        if (empty($obra)) {
            Flash::error('Obra '.trans('common.not-found'));

            return redirect(route('admin.obras.index'));
        }

        return view('admin.obras.show')->with('obra', $obra);
    }

    /**
     * Show the form for editing the specified Obra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id, UserRepository $userRepository)
    {
        $obra = $this->obraRepository->findWithoutFail($id);

        if (empty($obra)) {
            Flash::error('Obra '.trans('common.not-found'));

            return redirect(route('admin.obras.index'));
        }

        $relacionados = $userRepository->usuariosDaObra($id);
        $obraUsers = $relacionados->pluck('id')->all();
        $relacionados = $relacionados->pluck('name', 'id')->all();

        $cidades = Cidade::orderBy("nome_completo")
            ->select(DB::raw('concat(nome_completo, " - ", uf) as nome_final'), 'id')
            ->get()
            ->pluck('nome_final', 'id');

        $regionais = Regional::pluck('nome', 'id')->prepend('', '')->all();
        $padrao_empreendimentos = PadraoEmpreendimento::pluck('nome', 'id')->prepend('', '')->all();


        return view('admin.obras.edit', compact('obra', 
            'relacionados', 
            'obraUsers', 
            'cidades', 
            'regionais',
            'padrao_empreendimentos'));
    }

    /**
     * Update the specified Obra in storage.
     *
     * @param  int              $id
     * @param UpdateObraRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateObraRequest $request)
    {
        $obra = $this->obraRepository->findWithoutFail($id);

        if (empty($obra)) {
            Flash::error('Obra '.trans('common.not-found'));

            return redirect(route('admin.obras.index'));
        }

        if($request->logo){
            $destinationPath = CodeRepository::saveFile($request->logo, 'obras/' . $obra->id);
            $obra->logo = Storage::url($destinationPath);
            $obra->save();
        }

        $input = $request->except('logo');
        foreach ($input as $item => $value){
            if($value == ''){
                $input[$item] = null;
            }
        }

        $obra = $this->obraRepository->update($input, $id);

        $obra->update();

        Flash::success('Obra '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.obras.index'));
    }

    /**
     * Remove the specified Obra from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $obra = $this->obraRepository->findWithoutFail($id);

        if (empty($obra)) {
            Flash::error('Obra '.trans('common.not-found'));

            return redirect(route('admin.obras.index'));
        }

        if(count($obra->ordemDeCompras)){
            Flash::error('A obra não pode ser removida, pois tem ordens de compra.');

            return redirect(route('admin.obras.index'));
        }

        $this->obraRepository->delete($id);

        Flash::success('Obra '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.obras.index'));
    }
}
