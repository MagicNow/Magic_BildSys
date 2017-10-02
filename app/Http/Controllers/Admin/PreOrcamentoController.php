<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\PreOrcamentoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreatePreOrcamentoRequest;
use App\Http\Requests\Admin\UpdatePreOrcamentoRequest;
use App\Models\Obra;
use App\Models\TipoOrcamento;
use App\Models\MascaraPadrao;
use App\Models\MascaraPadraoInsumo;
use App\Models\Levantamento;
use App\Repositories\Admin\PreOrcamentoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;
use Maatwebsite\Excel\Facades\Excel;

class PreOrcamentoController extends AppBaseController
{
    /** @var  PreOrcamentoRepository */
    private $preOrcamentoRepository;

    public function __construct(PreOrcamentoRepository $preOrcamentoRepo)
    {
        $this->preOrcamentoRepository = $preOrcamentoRepo;
    }

    /**
     * Display a listing of the PreOrcamento.
     *
     * @param PreOrcamentoDataTable $preOrcamentoDataTable
     * @return Response
     */
    public function index(PreOrcamentoDataTable $preOrcamentoDataTable)
    {
        return $preOrcamentoDataTable->render('admin.pre_orcamentos.index');
    }

    /**
     * Show the form for creating a new PreOrcamento.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.pre_orcamentos.create');
    }

    /**
     * Store a newly created PreOrcamento in storage.
     *
     * @param CreatePreOrcamentoRequest $request
     *
     * @return Response
     */
    public function store(CreatePreOrcamentoRequest $request)
    {
        $input = $request->all();

        $preOrcamento = $this->preOrcamentoRepository->create($input);

        Flash::success('Pré Orçamento '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.pre_orcamentos.index'));
    }

    /**
     * Display the specified PreOrcamento.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $preOrcamento = $this->preOrcamentoRepository->findWithoutFail($id);

        if (empty($preOrcamento)) {
            Flash::error('Pré Orçamento '.trans('common.not-found'));

            return redirect(route('admin.pre_orcamentos.index'));
        }

        return view('admin.pre_orcamentos.show')->with('orcamento', $preOrcamento);
    }

    /**
     * Show the form for editing the specified PreOrcamento.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $preOrcamento = $this->preOrcamentoRepository->findWithoutFail($id);

        if (empty($preOrcamento)) {
            Flash::error('Pré Orçamento '.trans('common.not-found'));

            return redirect(route('admin.pre_orcamentos.index'));
        }

        return view('admin.pre_orcamentos.edit')->with('orcamento', $preOrcamento);
    }

    /**
     * Update the specified PreOrcamento in storage.
     *
     * @param  int              $id
     * @param UpdatePreOrcamentoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePreOrcamentoRequest $request)
    {
        $preOrcamento = $this->preOrcamentoRepository->findWithoutFail($id);

        if (empty($preOrcamento)) {
            Flash::error('Pré Orçamento '.trans('common.not-found'));

            return redirect(route('admin.pre_orcamentos.index'));
        }

        $preOrcamento = $this->preOrcamentoRepository->update($request->all(), $id);

        Flash::success('Pré Orçamento '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.pre_orcamentos.index'));
    }

    /**
     * Remove the specified PreOrcamento from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $preOrcamento = $this->preOrcamentoRepository->findWithoutFail($id);

        if (empty($preOrcamento)) {
            Flash::error('Pré Orçamento '.trans('common.not-found'));

            return redirect(route('admin.pre_orcamentos.index'));
        }

        $this->preOrcamentoRepository->delete($id);

        Flash::success('Pré Orçamento '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.pre_orcamentos.index'));
    }
	
	public function exportarIndex()
    {
		
		$mascaraPadrao = MascaraPadrao::pluck('nome', 'id')
                        ->toArray();
		
		$tipoOrcamento = TipoOrcamento::pluck('nome', 'id')
                        ->toArray();
		
		
        return view('admin.pre_orcamentos.exportar_index', compact('mascaraPadrao', 'tipoOrcamento'));
    }
	
	public function exportarPlan(Request $request)
	{
		//Filtros Obra, Mês e Semana de Referências		
		if($request->obra_id) {   
		
		}
		
		if($request->mascara_padrao_id) {
			
		}   		
	
		$insumos = MascaraPadraoInsumo::select([                      			
            'mascara_padrao_insumos.codigo_estruturado as apropriacao',
			'insumo_grupos.nome as descricao_apropriacao',
			'insumos.unidade_sigla',			
        ])
		->join('insumos', 'insumos.id', 'mascara_padrao_insumos.insumo_id') 
		->join('insumo_grupos', 'insumo_grupos.id', 'insumos.insumo_grupo_id') 
        ->groupBy('mascara_padrao_insumos.id')
		->get();
		
		$estrutura = Levantamento::query()->select([
				'levantamentos.torre',
				'levantamentos.andar',
				'levantamentos.pavimento',
				'levantamentos.trecho'
            ])
        ->join('obras','obras.id','levantamentos.obra_id')
		->groupBy('levantamentos.id')
		->get();
		
		Excel::create('Exportar-Tipos-Levantamentos', function($excel) use($insumos,$estrutura) {
			
			$excel->sheet('Insumos', function($sheet) use($insumos) {
				$sheet->fromArray($insumos, null, 'A1', false,false);
			});
			
			$excel->sheet('Estrutura', function($sheet) use($estrutura) {
				$sheet->fromArray($estrutura);
			});
			
		})->export('xls');
		
	}
}
