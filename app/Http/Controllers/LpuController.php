<?php

namespace App\Http\Controllers;

use App\DataTables\LpuDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateContratoRequest;
use App\Http\Requests\EditarItemRequest;
use App\Http\Requests\UpdateContratoRequest;
use App\Models\ContratoStatusLog;
use App\Models\Fornecedor;
use App\Models\Insumo;
use App\Models\McMedicaoPrevisao;
use App\Models\MemoriaCalculo;
use App\Models\NomeclaturaMapa;
use App\Models\Obra;
use App\Models\ObraTorre;
use App\Models\Orcamento;
use App\Models\Planejamento;
use App\Models\WorkflowAprovacao;
use App\Notifications\WorkflowNotification;
use App\Repositories\CodeRepository;
use App\Repositories\ContratoRepository;
use App\Repositories\NotificationRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Response;
use App\Repositories\Admin\FornecedoresRepository;
use App\Repositories\Admin\ObraRepository;
use App\Repositories\ContratoStatusRepository;
use Illuminate\Support\Facades\App;
use App\Repositories\WorkflowAprovacaoRepository;
use Illuminate\Http\Request;
use App\Repositories\Admin\WorkflowReprovacaoMotivoRepository;
use App\Models\WorkflowTipo;
use App\DataTables\ContratoItemDataTable;
use App\Models\ContratoItem;
use App\Http\Requests\ReajustarRequest;
use App\Http\Requests\DistratarRequest;
use App\Http\Requests\ReapropriarRequest;
use App\Http\Requests\AtualizarValorRequest;
use App\Repositories\ContratoItemModificacaoRepository;
use App\Repositories\ContratoItemRepository;
use App\Models\ContratoStatus;
use App\Models\ContratoItemModificacao;
use App\Repositories\ContratoItemApropriacaoRepository;
use App\Models\WorkflowAlcada;
use App\Models\ContratoItemApropriacao;
use App\Models\Cnae;
use App\Repositories\SolicitacaoEntregaRepository;

use App\Http\Requests\UpdateLpuRequest;
use App\Repositories\LpuRepository;
use App\Models\Regional;


class LpuController extends AppBaseController
{
    /** @var  LpuRepository */
    private $lpuRepository;

    public function __construct(LpuRepository $lpuRepo)
    {
        $this->lpuRepository = $lpuRepo;
    }

    /**
     * Display a listing of the Contrato.
     *
     * @param ContratoDataTable $contratoDataTable
     * @return Response
     */
    public function index( LpuDataTable $lpuDataTable) {
        
		$regionais = Regional::pluck('nome', 'id')->prepend('', '')->all();

        return $lpuDataTable->render(
            'lpu.index',
			compact('regionais')
        );
    }

    public function show(){
        /*$carteira = $this->carteiraRepository->findWithoutFail($id);

        if (empty($carteira)) {
            Flash::error('Carteira '.trans('common.not-found'));

            return redirect(route('admin.carteiras.index'));
        }

        return view('lpu.show')->with('carteira', $carteira);*/
    }    

    public function edit($id)
    {
        $lpu = $this->lpuRepository->findWithoutFail($id);

        if (empty($lpu)) {
            Flash::error('Lpu '.trans('common.not-found'));

            return redirect(route('lpu.index'));
        }
     
        return view('lpu.edit', compact('lpu'));
    }

    public function update($id, UpdateLpuRequest $request)
    {
        $lpu = $this->lpuRepository->findWithoutFail($id);

        if (empty($lpu)) {
            Flash::error('LPU '.trans('common.not-found'));

            return redirect(route('lpu.index'));
        }

        if($request->logo){
            $destinationPath = CodeRepository::saveFile($request->logo, 'lpu/' . $lpu->id);
            $lpu->logo = Storage::url($destinationPath);
            $lpu->save();
        }

        $input = $request->except('logo');
        foreach ($input as $item => $value){
            if($value == ''){
                $input[$item] = null;
            }
        }

        $lpu = $this->lpuRepository->update($input, $id);

        $lpu->update();

        Flash::success('LPU '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('lpu.index'));
    }
}
