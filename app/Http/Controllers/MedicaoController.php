<?php

namespace App\Http\Controllers;

use App\DataTables\MedicaoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateMedicaoRequest;
use App\Http\Requests\CreateMedicaoServicoRequest;
use App\Http\Requests\UpdateMedicaoRequest;
use App\Models\Contrato;
use App\Models\ContratoItemApropriacao;
use App\Models\Fornecedor;
use App\Models\Insumo;
use App\Models\McMedicaoPrevisao;
use App\Models\Medicao;
use App\Models\MedicaoServico;
use App\Models\MemoriaCalculo;
use App\Models\Obra;
use App\Models\Planejamento;
use App\Models\Servico;
use App\Repositories\Admin\ObraRepository;
use App\Repositories\MedicaoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psy\Util\Json;
use Response;

class MedicaoController extends AppBaseController
{
    /** @var  MedicaoRepository */
    private $medicaoRepository;

    public function __construct(MedicaoRepository $medicaoRepo)
    {
        $this->medicaoRepository = $medicaoRepo;
    }

    /**
     * Display a listing of the Medicao.
     *
     * @param MedicaoDataTable $medicaoDataTable
     * @return Response
     */
    public function index(MedicaoDataTable $medicaoDataTable)
    {
        return $medicaoDataTable->render('medicoes.index');
    }

    public function preCreate()
    {
        $obras = Obra::whereHas('users', function($query){
                $query->where('user_id', auth()->id());
            })
            ->whereRaw('EXISTS (SELECT 1 FROM mc_medicao_previsoes 
                    JOIN obra_torres ON obra_torre_id = obra_torres.id
                    WHERE obras.id = obra_torres.obra_id
                     LIMIT 1)')
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->toArray();

        return view('medicoes.pre-create', compact('obras'));
    }

    public function fornecedoresPorObra(Request $request)
    {
        $this->validate($request, ['obra'=>'required|min:1']);
        $obra = $request->obra;

        return Fornecedor::whereHas('contratos', function ($query) use ($obra) {
                $query->where('contrato_status_id', 5);
                $query->where('obra_id', $obra);
                $query->whereExists(function ($query2) {
                    $query2->select(DB::raw(1))
                        ->from('mc_medicao_previsoes')
                        ->join('obra_torres','obra_torres.id','mc_medicao_previsoes.obra_torre_id')
                        ->whereRaw('obra_torres.obra_id = contratos.obra_id');
                });
            })
            ->select([
                'id',
                DB::raw("CONCAT(nome,' - ',cnpj) as nome"),
            ])
            ->orderBy('nome', 'ASC')
            ->paginate();
    }

    public function contratosPorObra(){
        $this->validate(request(), ['obra'=>'required|min:1']);
        $obra = request()->get('obra');

        return Contrato::where('contrato_status_id', 5)
            ->where('obra_id', $obra)
            ->whereExists(function ($query2) {
                $query2->select(DB::raw(1))
                    ->from('mc_medicao_previsoes')
                    ->join('obra_torres','obra_torres.id','mc_medicao_previsoes.obra_torre_id')
                    ->whereRaw('obra_torres.obra_id = contratos.obra_id');
            })
            ->with('fornecedor')
            ->orderBy('id', 'ASC')
            ->paginate();
    }

    public function tarefasPorObra(){
        $this->validate(request(), ['obra'=>'required|min:1']);
        $obra = request()->get('obra');
        $contrato = request()->get('contrato');

        $tarefas = Planejamento::whereExists(function ($query2) use ($obra,$contrato) {
                $query2->select(DB::raw(1))
                    ->from('mc_medicao_previsoes')
                    ->join('contrato_item_apropriacoes','contrato_item_apropriacoes.id','mc_medicao_previsoes.contrato_item_apropriacao_id')
                    ->join('obra_torres','obra_torres.id','mc_medicao_previsoes.obra_torre_id')
                    ->where('obra_torres.obra_id',$obra);
                if($contrato){
                    $query2->join('contrato_itens','contrato_itens.id','mc_medicao_previsoes.contrato_item_id');
                    $query2->where('contrato_itens.contrato_id',$contrato);
                }
                $query2->whereRaw('mc_medicao_previsoes.planejamento_id = planejamentos.id');
            });

            return $tarefas->orderBy('tarefa', 'ASC')->paginate();
    }

    /** Insumos
     * @param Request $request
     * @return Json
     */
    public function insumos(Request $request)
    {
        $insumos = ContratoItemApropriacao::whereHas('contratoItem', function ($query) use ($request) {
            $query->join('contratos', 'contratos.id', 'contrato_itens.contrato_id');
            $query->where('contrato_status_id', 5);
            if($request->contrato){
                $query->where('contratos.id', $request->contrato);
            }
            if($request->obra) {
                $query->where('obra_id', $request->obra);
            }
            $query->whereExists(function ($query2) use ($request) {

                if($request->tarefa){
                    $query2->select(DB::raw(1))
                        ->from('mc_medicao_previsoes')
                        ->join('obra_torres','obra_torres.id','mc_medicao_previsoes.obra_torre_id')
                        ->where('mc_medicao_previsoes.planejamento_id',$request->tarefa)
                        ->whereRaw('obra_torres.obra_id = contratos.obra_id');
                }else{
                    $query2->select(DB::raw(1))
                        ->from('mc_medicao_previsoes')
                        ->join('obra_torres','obra_torres.id','mc_medicao_previsoes.obra_torre_id')
                        ->whereRaw('obra_torres.obra_id = contratos.obra_id');
                }
            });
            $query->whereExists(function ($query2) use ($request){
                if($request->tarefa){
                    $query2->select(DB::raw(1))
                        ->from('mc_medicao_previsoes')
                        ->where('mc_medicao_previsoes.planejamento_id',$request->tarefa)
                        ->whereRaw('mc_medicao_previsoes.contrato_item_id = contrato_itens.id');
                }else{
                    $query2->select(DB::raw(1))
                        ->from('mc_medicao_previsoes')
                        ->whereRaw('mc_medicao_previsoes.contrato_item_id = contrato_itens.id');
                }

            });
        })
            ->join('insumos', 'contrato_item_apropriacoes.insumo_id', 'insumos.id')
            ->join('contrato_itens', 'contrato_itens.insumo_id', 'insumos.id')
            ->select([
                'contrato_item_apropriacoes.id',
                DB::raw("CONCAT(contrato_item_apropriacoes.codigo_insumo,' - ',insumos.nome) as nome"),
            ])
            ->join('contratos', 'contratos.id', 'contrato_itens.contrato_id');
        if($request->contrato){
            $insumos->where('contratos.id', $request->contrato);
        }
        if($request->obra){
            $insumos->where('obra_id', $request->obra);
        }

        return $insumos->orderBy('nome', 'ASC')->paginate();
    }

    /**
     * Show the form for creating a new Medicao.
     *
     * @return Response
     */
    public function create()
    {
        if(!request()->get('contrato_item_apropriacao_id')){
            flash('Não foi passado um insumo!','error');
            return back();
        }
        $contratoItemApropriacao = ContratoItemApropriacao::find(request()->get('contrato_item_apropriacao_id'));
        $previsoes = McMedicaoPrevisao::where('contrato_item_apropriacao_id',request()->get('contrato_item_apropriacao_id'))->get();
        $memoriaCalculo = MemoriaCalculo::whereHas('blocos', function ($query){
            $query->join('mc_medicao_previsoes','mc_medicao_previsoes.memoria_calculo_bloco_id','memoria_calculo_blocos.id');
            $query->where('contrato_item_apropriacao_id',request()->get('contrato_item_apropriacao_id') );
        })->first();
        $blocos = $memoriaCalculo->blocosEstruturados(false);
        $previsoes = $previsoes->keyBy('memoria_calculo_bloco_id');

        $previsoes_ids = McMedicaoPrevisao::where('contrato_item_apropriacao_id',request()->get('contrato_item_apropriacao_id'))->pluck('id','id')->toArray();
        if(request()->get('mc_medicao_previsao_id')){
            $previsoes_ids = [0=>request()->get('mc_medicao_previsao_id') ];
        }
        $medicoes = Medicao::select([
            DB::raw('SUM(qtd) qtd'),
            'mc_medicao_previsao_id'
        ])->whereIn('mc_medicao_previsao_id',$previsoes_ids)
            ->groupBy('mc_medicao_previsao_id')
            ->get();
        if($medicoes->count()){
            $medicoes = $medicoes->keyBy('mc_medicao_previsao_id');
        }

        $medicaoServico = null;
        if(request()->get('medicao_servico_id')){
            $medicaoServico = MedicaoServico::find(request()->get('medicao_servico_id'));
        }else{
            $medicaoServico = MedicaoServico::where('contrato_item_apropriacao_id',request()->get('contrato_item_apropriacao_id'))
                                ->whereRaw('DATE(created_at) = CURDATE()')
                                ->first();
        }

        $mcMedicaoPrevisao = null;
        if(request()->get('mc_medicao_previsao_id')){
            $mcMedicaoPrevisao = McMedicaoPrevisao::find(request()->get('mc_medicao_previsao_id'));
        }
        
        return view('medicoes.create',compact('contratoItemApropriacao','memoriaCalculo', 'previsoes','blocos', 'medicoes','medicaoServico', 'mcMedicaoPrevisao'));
    }

    /**
     * Store a newly created Medicao in storage.
     *
     * @param CreateMedicaoRequest $request
     *
     * @return Response
     */
    public function store(CreateMedicaoRequest $request)
    {
        $input = $request->all();

        $medicao = $this->medicaoRepository->create($input);

        Flash::success('Medicão salva '.trans('common.successfully').'.');

        return redirect()->to( '/medicoes/create?contrato_item_apropriacao_id='.
                                    $request->get('contrato_item_apropriacao_id').
                                '&medicao_servico_id='.$request->get('medicao_servico_id') );
    }

    public function medicaoServicoStore(CreateMedicaoServicoRequest $request)
    {
        $input = $request->all();
        if($input['descontos']!=''){
            $input['descontos'] = money_to_float($input['descontos']);
        }
        $input['user_id'] = auth()->id();
        $medicaoServico = MedicaoServico::create($input);

        if($medicaoServico){
            return redirect()->to('/medicoes/create?contrato_item_apropriacao_id='.$request->get('contrato_item_apropriacao_id').'&medicao_servico_id='.$medicaoServico->id);
        }else{
            return back();
        }
    }

    /**
     * Display the specified Medicao.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $medicao = $this->medicaoRepository->findWithoutFail($id);

        if (empty($medicao)) {
            Flash::error('Medicao '.trans('common.not-found'));

            return redirect(route('medicoes.index'));
        }

        $medicoes = Medicao::select([
            DB::raw('SUM(qtd) qtd'),
            'mc_medicao_previsao_id'
        ])->where('mc_medicao_previsao_id',$medicao->mc_medicao_previsao_id)
            ->groupBy('mc_medicao_previsao_id')
            ->get();
        if($medicoes->count()){
            $medicoes = $medicoes->keyBy('mc_medicao_previsao_id');
        }

        return view('medicoes.show', compact('medicoes','medicao'));
    }

    /**
     * Show the form for editing the specified Medicao.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $medicao = $this->medicaoRepository->findWithoutFail($id);

        if (empty($medicao)) {
            Flash::error('Medicao '.trans('common.not-found'));

            return redirect(route('medicoes.index'));
        }

        $medicoes = Medicao::select([
            DB::raw('SUM(qtd) qtd'),
            'mc_medicao_previsao_id'
        ])->where('mc_medicao_previsao_id',$medicao->mc_medicao_previsao_id)
            ->where('id','!=',$medicao->id)
            ->groupBy('mc_medicao_previsao_id')
            ->get();
        if($medicoes->count()){
            $medicoes = $medicoes->keyBy('mc_medicao_previsao_id');
        }

        $contratoItemApropriacao = $medicao->mcMedicaoPrevisao->contratoItemApropriacao;
        $mcMedicaoPrevisao = $medicao->mcMedicaoPrevisao;
        $medicaoServico = $medicao->medicaoServico;

        return view('medicoes.edit', compact('medicoes','medicao', 'contratoItemApropriacao', 'mcMedicaoPrevisao', 'medicaoServico'));
    }

    /**
     * Update the specified Medicao in storage.
     *
     * @param  int              $id
     * @param UpdateMedicaoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMedicaoRequest $request)
    {
        $medicao = $this->medicaoRepository->findWithoutFail($id);

        if (empty($medicao)) {
            Flash::error('Medição '.trans('common.not-found'));

            return redirect(route('medicoes.index'));
        }

        $medicao = $this->medicaoRepository->update($request->all(), $id);

        Flash::success('Medição editada '.trans('common.successfully').'.');
        if($medicao->medicao_servico_id){
            $medicaoServico = $medicao->medicaoServico;
            $medicaoServico->finalizado = 0;
            $medicaoServico->aprovado = null;
            $medicaoServico->save();
            return redirect(route('medicaoServicos.edit',$medicao->medicao_servico_id));
        }else{
            return redirect(route('medicoes.index'));
        }
    }

    /**
     * Remove the specified Medicao from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $medicao = $this->medicaoRepository->findWithoutFail($id);

        if (empty($medicao)) {
            Flash::error('Medicao '.trans('common.not-found'));

            return redirect(route('medicoes.index'));
        }
        $medicao_servico_id = $medicao->medicao_servico_id;
        $this->medicaoRepository->delete($id);

        Flash::success('Medição '.trans('common.deleted').' '.trans('common.successfully').'.');

        if($medicao_servico_id){
            return redirect(route('medicaoServicos.edit',$medicao_servico_id));
        }else{
            return redirect(route('medicoes.index'));
        }
    }
}
