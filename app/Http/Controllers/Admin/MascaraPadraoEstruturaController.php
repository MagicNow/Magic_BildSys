<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\MascaraPadraoEstruturaDataTable;
use App\DataTables\Admin\MascaraPadraoRelacionarInsumoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateMascaraPadraoEstruturaRequest;
use App\Http\Requests\Admin\UpdateMascaraPadraoEstruturaRequest;
use App\Models\Grupo;
use App\Models\Insumo;
use App\Models\MascaraPadrao;
use App\Models\MascaraPadraoEstrutura;
use App\Models\Servico;
use App\Repositories\Admin\MascaraPadraoEstruturaRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;

class MascaraPadraoEstruturaController extends AppBaseController
{
    /** @var  MascaraPadraoEstruturaRepository */
    private $mascaraPadraoEstruturaRepository;

    public function __construct(MascaraPadraoEstruturaRepository $mascaraPadraoEstruturaRepo)
    {
        $this->mascaraPadraoEstruturaRepository = $mascaraPadraoEstruturaRepo;
    }

    /**
     * Display a listing of the MascaraPadraoEstrutura.
     *
     * @param MascaraPadraoEstruturaDataTable $mascaraPadraoEstruturaDataTable
     * @return Response
     */
    public function index(MascaraPadraoEstruturaDataTable $mascaraPadraoEstruturaDataTable)
    {
        return $mascaraPadraoEstruturaDataTable->render('admin.mascara_padrao_estruturas.index');
    }

    /**
     * Show the form for creating a new MascaraPadraoEstrutura.
     *
     * @return Response
     */
    public function create()
    {
        $mascaras = MascaraPadrao::pluck('nome','id')->toArray();
        $grupo = Grupo::where('codigo', '01')
            ->where('nome', 'OBRA')
            ->whereNull('grupo_id')
            ->first();
        return view('admin.mascara_padrao_estruturas.create', compact('mascaras','grupo'));
    }

    /**
     * Store a newly created MascaraPadraoEstrutura in storage.
     *
     * @param CreateMascaraPadraoEstruturaRequest $request
     *
     * @return Response
     */
    public function store(CreateMascaraPadraoEstruturaRequest $request)
    {
        $input = $request->all();
        $mascaraPadraoEstrutura = $this->mascaraPadraoEstruturaRepository->create($input);

        Flash::success('Máscara Padrão Estrutura '.trans('common.saved').' '.trans('common.successfully').'.');

        if ($request->get('save') != 'save-continue') {
            return redirect(route('admin.mascaraPadraoEstruturas.index'));
        } else {
            # A variável $request->btn_insumo significa que o post veio do botão add insumos na área de criar a mascara padrão
            if(!$request->btn_insumo) {
                # Retorna o id da tabela mascara_padrao
                return redirect(route('admin.mascaraPadraoEstruturas.mascara-padrao-insumos', $request->mascara_padrao_id));
            }else{
                # se cair aqui é porque o post veio do botão salvar e continuar
                # Retorna o id na tabela mascara_padrao_estrutura
                return redirect(route('admin.mascaraPadraoEstruturas.mascara-padrao-estrutura-insumos', $mascaraPadraoEstrutura->id));
            }
        }
    }

    /**
     * Se o submit vier do botão INSUMOS que fica na estrutura de máscara padrão, então é executado o método abaixo.
     * @param MascaraPadraoRelacionarInsumoDataTable $mascaraPadraoRelacionarInsumoDataTable
     * @param $mascara_padrao_id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function mascaraPadraoInsumos(MascaraPadraoRelacionarInsumoDataTable $mascaraPadraoRelacionarInsumoDataTable, $mascara_padrao_id)
    {
        $mascaraPadrao = MascaraPadrao::find($mascara_padrao_id);
        $selectMascaraPadraoEstruturas = MascaraPadraoEstrutura::select([
            'mascara_padrao_estruturas.id',
            \DB::raw("CONCAT(mascara_padrao_estruturas.codigo, ' - ', servicos.nome) as estrutura")
        ])
            ->join('servicos', 'servicos.id', 'mascara_padrao_estruturas.servico_id')
            ->where('mascara_padrao_id', $mascaraPadrao->id)
            ->pluck('estrutura', 'id')
            ->toArray();
        return $mascaraPadraoRelacionarInsumoDataTable->render('admin.mascara_padrao_estruturas.insumos',compact('mascaraPadrao','selectMascaraPadraoEstruturas'));
    }

    /**
     * Se o submit vier do botão salvar e continuar que fica na estrutura de máscara padrão, então é executado o método abaixo
     * @param MascaraPadraoRelacionarInsumoDataTable $mascaraPadraoRelacionarInsumoDataTable
     * @param $mascara_padrao_estrutura_id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function MascaraPadraoEstruturaInsumos(MascaraPadraoRelacionarInsumoDataTable $mascaraPadraoRelacionarInsumoDataTable, $mascara_padrao_estrutura_id)
    {
        $mascaraPadraoEstrutura = MascaraPadraoEstrutura::select([
            'mascara_padrao_estruturas.id',
            'mascara_padrao.nome',
            'mascara_padrao.id as mascara_padrao_id'
        ])
            ->join('mascara_padrao', 'mascara_padrao.id', 'mascara_padrao_estruturas.mascara_padrao_id')
            ->where('mascara_padrao_estruturas.id', $mascara_padrao_estrutura_id)
            ->first();

        $selectMascaraPadraoEstruturas = MascaraPadraoEstrutura::select([
            'mascara_padrao_estruturas.id',
            \DB::raw("CONCAT(mascara_padrao_estruturas.codigo, ' - ', servicos.nome) as estrutura")
        ])
            ->join('servicos', 'servicos.id', 'mascara_padrao_estruturas.servico_id')
            ->where('mascara_padrao_id', $mascaraPadraoEstrutura->mascara_padrao_id)
            ->pluck('estrutura', 'id')
            ->toArray();
        return $mascaraPadraoRelacionarInsumoDataTable->render('admin.mascara_padrao_estruturas.insumos',compact('mascaraPadraoEstrutura','selectMascaraPadraoEstruturas'));
    }

    /**
     * Display the specified MascaraPadraoEstrutura.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $mascaraPadraoEstrutura = $this->mascaraPadraoEstruturaRepository->findWithoutFail($id);

        if (empty($mascaraPadraoEstrutura)) {
            Flash::error('Mascara Padrao Estrutura '.trans('common.not-found'));

            return redirect(route('admin.mascaraPadraoEstruturas.index'));
        }

        return view('admin.mascara_padrao_estruturas.show')->with('mascaraPadraoEstrutura', $mascaraPadraoEstrutura);
    }

    /**
     * Show the form for editing the specified MascaraPadraoEstrutura.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $mascaraPadrao = MascaraPadrao::find($id);

        if (empty($mascaraPadrao)) {
            Flash::error('Mascara Padrao '.trans('common.not-found'));

            return redirect(route('admin.mascaraPadraoEstruturas.index'));
        }

        return view('admin.mascara_padrao_estruturas.edit', compact('mascaraPadrao'));
    }

    /**
     * Update the specified MascaraPadraoEstrutura in storage.
     *
     * @param  int              $id
     * @param UpdateMascaraPadraoEstruturaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMascaraPadraoEstruturaRequest $request)
    {
        $mascaraPadraoEstrutura = $this->mascaraPadraoEstruturaRepository->findWithoutFail($id);

        if (empty($mascaraPadraoEstrutura)) {
            Flash::error('Mascara Padrao Estrutura '.trans('common.not-found'));

            return redirect(route('admin.mascaraPadraoEstruturas.index'));
        }

        $mascaraPadraoEstrutura = $this->mascaraPadraoEstruturaRepository->update($request->all(), $id);

        Flash::success('Mascara Padrao Estrutura '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.mascaraPadraoEstruturas.index'));
    }

    /**
     * Remove the specified MascaraPadraoEstrutura from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $mascaraPadraoEstrutura = $this->mascaraPadraoEstruturaRepository->findWithoutFail($id);

        if (empty($mascaraPadraoEstrutura)) {
            Flash::error('Mascara Padrao Estrutura '.trans('common.not-found'));

            return redirect(route('admin.mascaraPadraoEstruturas.index'));
        }

        $this->mascaraPadraoEstruturaRepository->delete($id);

        Flash::success('Mascara Padrao Estrutura '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.mascaraPadraoEstruturas.index'));
    }

    public function getGrupos($id)
    {
        $grupos = Grupo::select([
            'grupos.id',
            DB::raw("CONCAT(grupos.codigo, ' ', grupos.nome) as nome")
        ])
            ->where('grupos.grupo_id', $id)
            ->orderBy('grupos.nome', 'ASC');


        $grupos = $grupos->pluck('grupos.nome','grupos.id')
            ->toArray();

        return $grupos;
    }

    public function getServicos($id)
    {
        $servicos = Servico::select([
            'servicos.id',
            DB::raw("CONCAT(servicos.codigo, ' ', servicos.nome) as nome")
        ])
            ->where('servicos.grupo_id', $id)
            ->orderBy('servicos.nome', 'ASC');

        $servicos = $servicos->pluck('nome', 'id')->toArray();

        return $servicos;
    }
//
//    public function getInsumos()
//    {
//        $insumos = Insumo::select([
//            'insumos.id',
//            'insumos.nome'
//        ])
//            ->orderBy('insumos.nome', 'ASC');
//
//        $insumos = $insumos->pluck('nome', 'id')->toArray();
//
//        return $insumos;
//    }
}
