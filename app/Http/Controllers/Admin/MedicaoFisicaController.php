<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\MedicaoFisicaDataTable;
use App\Http\Requests;
use App\Repositories\CodeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Response;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\CreateMedicaoFisicaRequest;
use App\Http\Requests\Admin\UpdateMedicaoFisicaRequest;
use App\Repositories\Admin\MedicaoFisicaRepository;
use App\Models\Obra;
use App\Models\CronogramaFisico;
use App\Models\MedicaoFisica;

class MedicaoFisicaController extends AppBaseController
{
    /** @var  MedicaoFisicaRepository */
    private $medicaoFisicaRepository;

    public function __construct(MedicaoFisicaRepository $medicaoFisicaRepo)
    {
        $this->medicaoFisicaRepository = $medicaoFisicaRepo;
    }

    /**
     * Display a listing of the Contrato.
     *
     * @param MedicaoFisicaDataTable $medicaoFisicaDataTable
     * @return Response
     */
    public function index( MedicaoFisicaDataTable $medicaoFisicaDataTable) {
        
		$obras = Obra::join('cronograma_fisicos', 'cronograma_fisicos.obra_id', '=', 'obras.id')                                                
                        ->pluck('obras.nome', 'obras.id')
                        ->toArray();

        return $medicaoFisicaDataTable->render('admin.medicao_fisicas.index',compact('obras'));
    }

    public function show(){
        
    }
	
	/**
     * Show the form for creating a new Carteira.
     *
     * @return Response
     */
    public function create()
    {
		
		$obras = Obra::join('cronograma_fisicos', 'cronograma_fisicos.obra_id', '=', 'obras.id')                        
                        ->orderBy('obras.nome', 'ASC')
                        ->pluck('obras.nome', 'obras.id')
                        ->toArray();

        return view('admin.medicao_fisicas.create', compact('obras'));
    }

    public function edit($id)
    {
        $medicaoFisica = $this->medicaoFisicaRepository->findWithoutFail($id);
		
        if (empty($medicaoFisica)) {
            Flash::error(' Medição Física'.trans('common.not-found'));

            return redirect(route('admin.medicao_fisicas.index'));
        }
     
        return view('admin.medicao_fisicas.edit', compact('medicaoFisica'));
    }
	
	/**
     * Store a newly created Medicao Fisica in storage.
     *
     * @param CreateInsumoRequest $request
     *
     * @return Response
     */
    public function store(CreateMedicaoFisicaRequest $request)
    {
        $input = $request->all();

        $medicaoFisica = $this->medicaoFisicaRepository->create($input);

        Flash::success(' Medição Física '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.medicao_fisicas.index'));
    }

    public function update($id, UpdateMedicaoFisicaRequest $request)
    {
		
		$medicaoFisica = $this->medicaoFisicaRepository->findWithoutFail($id);

        if (empty($medicaoFisica)) {
            Flash::error(' Medição Física '.trans('common.not-found'));

            return redirect(route('admin.medicao_fisicas.index'));
        }

        $medicaoFisica = $this->medicaoFisicaRepository->update($request->all(), $id);

        Flash::success('Medição Física '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.medicao_fisicas.index'));        
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
        $medicaoFisica = $this->medicaoFisicaRepository->findWithoutFail($id);

        if (empty($medicaoFisica)) {
            Flash::error('Medição Física '.trans('common.not-found'));

            return redirect(route('admin.medicao_fisicas.index'));
        }

        $this->medicaoFisicaRepository->delete($id);

        Flash::success('Medição Física '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.medicao_fisicas.index'));
    }
	
	
	
	public function tarefasPorObra(){		
		 
        $this->validate(request(), ['obra_id'=>'required|min:1']);
        $obraId = request()->get('obra_id');
		
		$array_mc_id = MedicaoFisica::select([			
			'medicao_fisicas.tarefa'							
		])
		->join('obras','obras.id','=','medicao_fisicas.obra_id')
		->where('obras.id', $obraId)
		//->where('medicao_fisicas.valor_medido_total', '=', 100)
		->get()->toArray();
		        
		$tarefas = CronogramaFisico::select([
			'cronograma_fisicos.id',
			'cronograma_fisicos.tarefa'							
		])	
		->join('obras','obras.id','=','cronograma_fisicos.obra_id')
		->where('obras.id', $obraId)
		->where('cronograma_fisicos.resumo', 'Não')
		->whereNotIn('cronograma_fisicos.tarefa', $array_mc_id)
		->groupBy('cronograma_fisicos.tarefa')
		->orderBy('cronograma_fisicos.id', 'ASC')
		->get()->toArray();
								
        return $tarefas;
    }
	
}
