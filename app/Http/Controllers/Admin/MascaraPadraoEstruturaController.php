<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\MascaraPadraoEstruturaDataTable;
use App\DataTables\Admin\MascaraPadraoInsumoRelacionadosDataTable;
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
    public function create($id)
    {
        $mascaraPadrao = MascaraPadrao::find($id);
        $mascaras = MascaraPadrao::pluck('nome','id')->toArray();
        $grupo = Grupo::where('codigo', '01')
            ->where('nome', 'OBRA')
            ->whereNull('grupo_id')
            ->first();

        $mascaraPadraoEstruturas = MascaraPadraoEstrutura::where('mascara_padrao_id', $id)->get();

        $selectSubgrupos1 = $selectSubgrupos2 = $selectSubgrupos3 = $selectServicos = [];
        foreach($mascaraPadraoEstruturas as $mascaraPadraoEstrutura){
            $subgrupo1 = Grupo::find($mascaraPadraoEstrutura->subgrupo1_id);
            $subgrupos1[$subgrupo1->codigo] = $subgrupo1;
            $selectSubgrupos1 += Grupo::where('grupo_id', $subgrupo1->grupo_id)->pluck('nome', 'id')->toArray();

            $subgrupo2 = Grupo::find($mascaraPadraoEstrutura->subgrupo2_id);
            $subgrupos2[$subgrupo2->codigo] = $subgrupo2;
            $selectSubgrupos2 += Grupo::where('grupo_id', $subgrupo2->grupo_id)->pluck('nome', 'id')->toArray();

            $subgrupo3 = Grupo::find($mascaraPadraoEstrutura->subgrupo3_id);
            $subgrupos3[$subgrupo3->codigo] = $subgrupo3;
            $selectSubgrupos3 += Grupo::where('grupo_id', $subgrupo3->grupo_id)->pluck('nome', 'id')->toArray();

            $servico = Servico::find($mascaraPadraoEstrutura->servico_id);
            $servicos[$servico->codigo] = $servico;
            $selectServicos += Servico::where('grupo_id', $servico->grupo_id)->pluck('nome', 'id')->toArray();
        }

        return view('admin.mascara_padrao_estruturas.create',
            compact(
                'mascaras',
                'grupo',
                'mascaraPadrao',
                'mascaraPadraoEstruturas',
                'grupos',
                'subgrupos1',
                'subgrupos2',
                'subgrupos3',
                'servicos',
                'selectGrupo',
                'selectSubgrupos1',
                'selectSubgrupos2',
                'selectSubgrupos3',
                'selectServicos'

            ));
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
        if(isset($mascaraPadraoEstrutura['error'])){
            Flash::error('ERRO: Blocos não inserido até o nível de "serviço"');
            return back()->withInput();
        }

        Flash::success('Máscara Padrão Estrutura '.trans('common.saved').' '.trans('common.successfully').'.');

        if ($request->get('save') != 'save-continue') {
            return redirect(route('admin.mascara_padrao.index'));
        } else {
            # $request->btn_insumo click é do botão adicionar insumos
            if(!$request->btn_insumo) {
                # passa como parametro o id da mascara padrão
                return redirect(route('admin.mascaraPadraoEstruturas.mascara-padrao-insumos', $request->mascara_padrao_id));
            }else{
                # O post está vindo do botão adicionar insumos
                $mascaraPadraoEstrutura = $mascaraPadraoEstrutura->where('servico_id', $request->servico_id)
                    ->where('mascara_padrao_id', $request->mascara_padrao_id)
                    ->first();
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
        return $mascaraPadraoRelacionarInsumoDataTable->mp($mascaraPadrao->id)->render('admin.mascara_padrao_estruturas.insumos',compact('mascaraPadrao','selectMascaraPadraoEstruturas'));
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
        return $mascaraPadraoRelacionarInsumoDataTable->mp($mascaraPadraoEstrutura->mascara_padrao_id)->render('admin.mascara_padrao_estruturas.insumos',compact('mascaraPadraoEstrutura','selectMascaraPadraoEstruturas'));
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

    /**
     * Método para cadastrar novo grupo.
     * @param Request $request
     * @return true
     */
    public function cadastrarGrupo(Request $request)
    {
//        dd($request->all());
        $novo_grupo = [];
        if ($request->codigo && $request->nome) {
            if($request->grupo_anterior) {
                $grupo = Grupo::find($request->grupo_anterior); // # Busca grupo
                if ($request->grupo_atual == 'servico_id') {
                    $novo_grupo = Servico::firstOrCreate(
                        [
                            'codigo' => $grupo->codigo . '.' . $request->codigo
                        ],
                        [
                            'codigo' => $grupo->codigo . '.' . $request->codigo,
                            'nome' => $request->nome,
                            'grupo_id' => $request->grupo_anterior
                        ]
                    );
                } else {
                    $novo_grupo = Grupo::firstOrCreate(
                        [
                            'codigo' => $grupo->codigo . '.' . $request->codigo
                        ],
                        [
                            'codigo' => $grupo->codigo . '.' . $request->codigo,
                            'nome' => $request->nome,
                            'grupo_id' => $request->grupo_anterior
                        ]
                    );
                }
            }
        }
        return response()->json(['grupo' => $novo_grupo]);
    }
}
