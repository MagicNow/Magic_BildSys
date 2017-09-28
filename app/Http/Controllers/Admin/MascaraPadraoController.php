<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\MascaraPadraoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateMascaraPadraoRequest;
use App\Http\Requests\Admin\UpdateMascaraPadraoRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Grupo;
use App\Models\Servico;
use App\Repositories\Admin\MascaraPadraoRepository;
use App\Repositories\CodeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Flash;
use Response;
use DB;

class MascaraPadraoController extends AppBaseController
{
    /** @var  MascaraPadraoRepository */
    private $mascaraPadraoRepository;

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
		return view('admin.mascara_padrao.create');
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
        $input = $request->all();

        $mascaraPadrao = $this->mascaraPadraoRepository->create($input);

        Flash::success(' Máscara Padrão '.trans('common.saved').' '.trans('common.successfully').'.');

        if ($request->get('save') != 'SAVE-CONTINUE') {
            return redirect(route('admin.mascara_padrao.index'));
        } else {
            return redirect(route('admin.mascara_padrao.edit', [$mascaraPadrao->id]));
        }
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

            return redirect(route('admin.solicitacaoInsumos.index'));
        }

        return view('admin.mascara_padrao.edit', compact('mascaraPadrao'));
		
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
            Flash::error('Máscara Padrão '.trans('common.not-found'));

            return redirect(route('admin.mascara_padrao.index'));
        }

        $mascaraPadrao = $this->mascaraPadraoRepository->update($request->all(), $id);

        Flash::success('Máscara Padrão '.trans('common.updated').' '.trans('common.successfully').'.');

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

    public function getGrupos($id)
    {
        $grupo = Grupo::select([
            'grupos.id',
            DB::raw("CONCAT(grupos.codigo, ' ', grupos.nome) as nome")
        ])
            ->where('grupos.grupo_id', $id)
            ->orderBy('grupos.nome', 'ASC');


        $grupo = $grupo->pluck('grupos.nome','grupos.id')
            ->toArray();

        dd($grupo);
        return $grupo;
    }

    public function getServicos($id)
    {
        $servico = Servico::select([
            'servicos.id',
            DB::raw("CONCAT(servicos.codigo, ' ', servicos.nome) as nome")
        ])
            ->where('servicos.grupo_id', $id)
            ->orderBy('servicos.nome', 'ASC');

        $servico = $servico->pluck('nome', 'id')->toArray();

        return $servico;
    }
}
