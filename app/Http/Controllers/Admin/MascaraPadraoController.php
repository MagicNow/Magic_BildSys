<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\MascaraPadraoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateMascaraPadraoRequest;
use App\Http\Requests\Admin\UpdateMascaraPadraoRequest;
use App\Models\TipoOrcamento;
use App\Repositories\Admin\MascaraPadraoRepository;
use App\Repositories\CodeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Storage;
use Response;
use DB;

class MascaraPadraoController extends AppBaseController
{
    /** @var  MascaraPadraoRepository */
    private $mascaraPadraoRepository;

    private $userRepository;

    public function __construct(MascaraPadraoRepository $mascaraPadraoRepo)
    {
        $this->mascaraPadraoRepository = $mascaraPadraoRepo;        
    }

    /**
     * Display a listing of the MascaraPadrao.
     *
     * @param MascaraPadraoDataTable $mascaraPadraoDataTable
     * @return Response
     */
    public function index(MascaraPadraoDataTable $mascaraPadraoDataTable)
    {
        return $mascaraPadraoDataTable->render('admin.mascara_padrao.index');
    }

    /**
     * Show the form for creating a new MascaraPadrao.
     *
     * @return Response
     */
    public function create()
    {
		$relacionadoTipoOrcamentos = [];
        $orcamentos = TipoOrcamento::pluck('nome', 'id')->all();

        return view('admin.mascara_padrao.create', compact('relacionadoTipoOrcamentos', 'orcamentos'));
    }

    /**
     * Store a newly created MascaraPadrao in storage.
     *
     * @param CreateMascaraPadraoRequest $request
     *
     * @return Response
     */
    public function store(CreateMascaraPadraoRequest $request)
    {
        $input = $request->except('logo');

        foreach ($input as $item => $value){
            if($value == ''){
                $input[$item] = null;
            }
        }

        $mascaraPadrao = $this->mascaraPadraoRepository->create($input);

        if($request->logo) {
            $destinationPath = CodeRepository::saveFile($request->logo, 'mascara_padrao/' . $mascaraPadrao->id);

            $mascaraPadrao->logo = Storage::url($destinationPath);
            $mascaraPadrao->save();
        }

        $mascaraPadrao->save();

        Flash::success(' Máscara Padrão '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.mascara_padrao.index'));
    }

    /**
     * Display the specified MascaraPadrao.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $mascaraPadrao = $this->mascaraPadraoRepository->findWithoutFail($id);

        if (empty($mascaraPadrao)) {
            Flash::error(' Máscara Padrão '.trans('common.not-found'));

            return redirect(route('admin.mascara_padrao.index'));
        }

        return view('admin.mascara_padrao.show')->with('mascaraPadrao', $mascaraPadrao);
    }

    /**
     * Show the form for editing the specified MascaraPadrao.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $mascaraPadrao = $this->mascaraPadraoRepository->findWithoutFail($id);

        if (empty($mascaraPadrao)) {
            Flash::error(' Máscara Padrão '.trans('common.not-found'));

            return redirect(route('admin.mascara_padrao.index'));
        }    

        $tipo_orcamentos = TipoOrcamento::pluck('nome', 'id')->prepend('', '')->all();
        
        return view('admin.mascara_padrao.edit', compact('mascaraPadrao', 'tipo_orcamentos'));
    }

    /**
     * Update the specified MascaraPadrao in storage.
     *
     * @param  int              $id
     * @param UpdateMascaraPadraoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMascaraPadraoRequest $request)
    {
        $mascaraPadrao = $this->mascaraPadraoRepository->findWithoutFail($id);

        if (empty($mascaraPadrao)) {
            Flash::error(' Máscara Padrão '.trans('common.not-found'));

            return redirect(route('admin.mascara_padrao.index'));
        }
       

        $mascaraPadrao->update();

        Flash::success(' Máscara Padrão '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.mascara_padrao.index'));
    }

    /**
     * Remove the specified MascaraPadrao from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $mascaraPadrao = $this->mascaraPadraoRepository->findWithoutFail($id);

        if (empty($mascaraPadrao)) {
            Flash::error(' Máscara Padrão '.trans('common.not-found'));
            return redirect(route('admin.mascara_padrao.index'));
        }
		
        $this->mascaraPadraoRepository->delete($id);

        Flash::success(' Máscara Padrão '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.mascara_padrao.index'));
    }
}
