<?php

namespace App\Http\Controllers;

use App\DataTables\MedicaoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateMedicaoRequest;
use App\Http\Requests\UpdateMedicaoRequest;
use App\Models\Contrato;
use App\Models\Fornecedor;
use App\Models\Insumo;
use App\Models\Obra;
use App\Models\Servico;
use App\Repositories\Admin\ObraRepository;
use App\Repositories\MedicaoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function servicosPorObra(){
        $this->validate(request(), ['obra'=>'required|min:1']);
        $obra = request()->get('obra');

        return Servico::whereExists(function ($query2) use ($obra) {
                $query2->select(DB::raw(1))
                    ->from('mc_medicao_previsoes')
                    ->join('contrato_item_apropriacoes','contrato_item_apropriacoes.id','mc_medicao_previsoes.contrato_item_apropriacao_id')
                    ->join('obra_torres','obra_torres.id','mc_medicao_previsoes.obra_torre_id')
                    ->where('obra_torres.obra_id',$obra)
                    ->whereRaw('contrato_item_apropriacoes.servico_id = servicos.id');
            })
            ->orderBy('nome', 'ASC')
            ->paginate();
    }

    public function insumosPorFornecedor(Request $request)
    {
        $fornecedor_id = $request->fornecedor;
        if(is_array($request->obras)){
            $obras = $request->obras;
        }else{
            $obras[] = $request->obras;
        }
        return Insumo::whereHas('contratoItem', function ($query) use ($fornecedor_id, $obras) {
            $query->join('contratos', 'contratos.id', 'contrato_itens.contrato_id');
            $query->where('contrato_status_id', 5);
            $query->where('fornecedor_id', $fornecedor_id);
            $query->whereIn('obra_id', $obras);
            $query->whereExists(function ($query2) {
                $query2->select(DB::raw(1))
                    ->from('mc_medicao_previsoes')
                    ->join('obra_torres','obra_torres.id','mc_medicao_previsoes.obra_torre_id')
                    ->whereRaw('obra_torres.obra_id = contratos.obra_id');
            });
            $query->whereExists(function ($query2) {
                $query2->select(DB::raw(1))
                    ->from('mc_medicao_previsoes')
                    ->whereRaw('mc_medicao_previsoes.contrato_item_id = contrato_itens.id');
            });
        })
            ->join('contrato_itens', 'contrato_itens.insumo_id', 'insumos.id')
            ->select([
                'insumos.id',
                'contrato_itens.id as contrato_item_id',
                DB::raw("CONCAT(insumos.codigo,' - ',insumos.nome) as nome"),
            ])
            ->join('contratos', 'contratos.id', 'contrato_itens.contrato_id')
            ->where('fornecedor_id', $fornecedor_id)
            ->whereIn('obra_id', $obras)
            ->orderBy('nome', 'ASC')
            ->paginate();
    }

    /**
     * Show the form for creating a new Medicao.
     *
     * @return Response
     */
    public function create()
    {
        return view('medicoes.create');
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

        Flash::success('Medicao '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('medicoes.index'));
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

        return view('medicoes.show')->with('medicao', $medicao);
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

        return view('medicoes.edit')->with('medicao', $medicao);
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
            Flash::error('Medicao '.trans('common.not-found'));

            return redirect(route('medicoes.index'));
        }

        $medicao = $this->medicaoRepository->update($request->all(), $id);

        Flash::success('Medicao '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('medicoes.index'));
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

        $this->medicaoRepository->delete($id);

        Flash::success('Medicao '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('medicoes.index'));
    }
}
