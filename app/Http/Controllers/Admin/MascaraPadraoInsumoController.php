<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\MascaraPadraoInsumoDataTable;
use App\DataTables\Admin\SemMascaraPadraoInsumoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateMascaraPadraoInsumoRequest;
use App\Http\Requests\Admin\UpdateMascaraPadraoInsumoRequest;
use App\Models\MascaraPadraoEstrutura;
use App\Models\MascaraPadraoInsumo;
use App\Models\Grupo;
use App\Models\Servico;
use App\Models\Insumo;
use App\Models\InsumoGrupo;
use App\Models\MascaraPadrao;
use App\Models\TipoLevantamento;
use App\Repositories\Admin\MascaraPadraoInsumoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\DB;

class MascaraPadraoInsumoController extends AppBaseController
{
    /** @var  MascaraPadraoInsumoRepository */
    private $mascaraPadraoInsumoRepository;

    public function __construct(MascaraPadraoInsumoRepository $mascaraPadraoInsumoRepo)
    {
        $this->mascaraPadraoInsumoRepository = $mascaraPadraoInsumoRepo;
    }

    /**
     * Display a listing of the MascaraPadraoInsumo.
     *
     * @param MascaraPadraoInsumoDataTable $mascaraPadraoInsumoDataTable
     * @return Response
     */
    public function index(MascaraPadraoInsumoDataTable $mascaraPadraoInsumoDataTable, $id)
    {
        return $mascaraPadraoInsumoDataTable->mascaraPadrao($id)->render('admin.mascara_padrao_insumos.index');
    }

    /**
     * Store a newly created MascaraPadraoInsumo in storage.
     *
     * @param CreateMascaraPadraoInsumoRequest $request
     *
     * @return Response
     */
    public function store(CreateMascaraPadraoInsumoRequest $request)
    {
        if($request->mascara_padrao_estrutura_id) {
            $estrutura = MascaraPadraoEstrutura::find($request->mascara_padrao_estrutura_id);
            $insumo = Insumo::where('id', $request->id)->first();

            MascaraPadraoInsumo::updateOrCreate(
                [
                    'mascara_padrao_estrutura_id' => $request->mascara_padrao_estrutura_id,
                    'insumo_id' => $request->id
                ],
                [
                    'mascara_padrao_estrutura_id' => $request->mascara_padrao_estrutura_id,
                    'tipo_levantamento_id' => ($request->tipo_levantamento_id) ? $request->tipo_levantamento_id : null,
                    'codigo_estruturado' => $estrutura->codigo . '.' . $insumo->codigo,
                    'insumo_id' => $request->id,
                    'coeficiente' => ($request->coeficiente) ? money_to_float($request->coeficiente) : null,
                    'indireto' => ($request->indireto) ? money_to_float($request->indireto) : null
                ]
            );
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['error'=>true]);
        }
    }

    /**
     * Remove the specified MascaraPadraoInsumo from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $mascaraPadraoInsumo = $this->mascaraPadraoInsumoRepository->findWithoutFail($id);

        if (empty($mascaraPadraoInsumo)) {
            Flash::error('Insumos ' .trans('common.not-found'));

            return redirect(route('admin.mascara_padrao.index'));
        }

        $this->mascaraPadraoInsumoRepository->delete($id);

        Flash::success('Insumos ' .trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.mascara_padrao.index'));
    }
}
