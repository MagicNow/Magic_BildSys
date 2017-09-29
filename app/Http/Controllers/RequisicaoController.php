<?php

namespace App\Http\Controllers;

use App\DataTables\RequisicaoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateRequisicaoRequest;
use App\Http\Requests\UpdateRequisicaoRequest;
use App\Models\Levantamento;
use App\Models\Obra;
use App\Repositories\RequisicaoRepository;
use App\Repositories\Admin\ObraRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Response;

class RequisicaoController extends AppBaseController
{
    /** @var  RequisicaoRepository */
    private $requisicaoRepository;
    private $obraRepository;

    public function __construct(RequisicaoRepository $requisicaoRepo,
                                ObraRepository $obraRepo)
    {
        $this->requisicaoRepository = $requisicaoRepo;
        $this->obraRepository = $obraRepo;
    }

    /**
     * Display a listing of the Requisicao.
     *
     * @param RequisicaoDataTable $requisicaoDataTable
     * @return Response
     */
    public function index(RequisicaoDataTable $requisicaoDataTable)
    {
        return $requisicaoDataTable->render('requisicao.index');
    }

    /**
     * Show the form for creating a new Requisicao.
     *
     * @return Response
     */
    public function create()
    {
        $obras = $this->obraRepository->findByUser(auth()->id())->pluck('nome','id')->toArray();

        return view('requisicao.create', compact('obras'));
    }

    /**
     * Store a newly created Requisicao in storage.
     *
     * @param CreateRequisicaoRequest $request
     *
     * @return Response
     */
    public function store(CreateRequisicaoRequest $request)
    {
        $input = $request->all();

        $requisicao = $this->requisicaoRepository->create($input);

        Flash::success('Requisicao '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('requisicao.index'));
    }

    /**
     * Display the specified Requisicao.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $requisicao = $this->requisicaoRepository->findWithoutFail($id);

        if (empty($requisicao)) {
            Flash::error('Requisicao '.trans('common.not-found'));

            return redirect(route('requisicaos.index'));
        }

        return view('requisicao.show')->with('requisicao', $requisicao);
    }

    /**
     * Show the form for editing the specified Requisicao.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $requisicao = $this->requisicaoRepository->findWithoutFail($id);

        if (empty($requisicao)) {
            Flash::error('Requisicao '.trans('common.not-found'));

            return redirect(route('requisicaos.index'));
        }

        return view('requisicao.edit')->with('requisicao', $requisicao);
    }

    /**
     * Update the specified Requisicao in storage.
     *
     * @param  int              $id
     * @param UpdateRequisicaoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRequisicaoRequest $request)
    {
        $requisicao = $this->requisicaoRepository->findWithoutFail($id);

        if (empty($requisicao)) {
            Flash::error('Requisicao '.trans('common.not-found'));

            return redirect(route('requisicao.index'));
        }

        $requisicao = $this->requisicaoRepository->update($request->all(), $id);

        Flash::success('Requisicao '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('requisicao.index'));
    }

    /**
     * Remove the specified Requisicao from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $requisicao = $this->requisicaoRepository->findWithoutFail($id);

        if (empty($requisicao)) {
            Flash::error('Requisicao '.trans('common.not-found'));

            return redirect(route('requisicao.index'));
        }

        $this->requisicaoRepository->delete($id);

        Flash::success('Requisicao '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('requisicao.index'));
    }






    public function getPavimentosByObraAndTorre($obra,$torre) {

        $r = DB::table('levantamentos')
            ->distinct()
            ->where('obra_id',$obra)
            ->where('torre',$torre)
            ->orderBy('pavimento')
            ->get(['pavimento']);

        if ($r) {

            return response()->json(['pavimentos' => $r, 'success' => true]);

        } else {

            return response()->json(['success' => false]);
        }
    }

    public function getTrechoByObraTorrePavimento($obra,$torre,$pavimento) {

        $r = DB::table('levantamentos')
            ->distinct()
            ->where('obra_id',$obra)
            ->where('torre',$torre)
            ->where('pavimento',$pavimento)
            ->orderBy('trecho')
            ->get(['trecho']);

        if ($r) {

            return response()->json(['trechos' => $r, 'success' => true]);

        } else {

            return response()->json(['success' => false]);
        }
    }

    public function getAndarByObraTorrePavimento($obra,$torre,$pavimento) {

        $r = DB::table('levantamentos')
            ->distinct()
            ->where('obra_id',$obra)
            ->where('torre',$torre)
            ->where('pavimento',$pavimento)
            ->orderBy('andar')
            ->get(['andar']);

        if ($r) {

            return response()->json(['andares' => $r, 'success' => true]);

        } else {

            return response()->json(['success' => false]);
        }
    }


    public function getInsumos(Request $request) {

        $r = DB::table('levantamentos as l');

        $r->select(DB::raw('i.nome insumo, i.id insumo_id, i.unidade_sigla , sum(l.quantidade) previsto, e.qtde estoque,IF(comodo <> " ","true","false") as comodo'));
        $r->leftJoin('insumos as i','i.id', '=', 'l.insumo');
        $r->leftJoin('estoque as e','e.insumo_id', '=', 'l.insumo');
        $r->where('l.obra_id',$request->query('obra'));
        $r->where('l.torre',urldecode($request->query('torre')));
        $r->where('l.pavimento',$request->query('pavimento'));

        if($request->query('andar'))
            $r->where('l.andar',$request->query('andar'));

        if($request->query('trecho'))
            $r->where('l.trecho',$request->query('trecho'));

        $r->groupBy('l.insumo');
        $r->orderBy('l.insumo');

        $insumos = $r->get();

        $html = '';

        foreach ($insumos as $insumo) {

            $qtde_usada = $this->getTotalUtilizadoByInsumo($request, $insumo);

            $qtde_disponivel = $insumo->previsto - $qtde_usada;

           $html .= '<tr>';
           $html .= '<td>'.$insumo->insumo.'</td>';
           $html .= '<td>'.$insumo->unidade_sigla.'</td>';
           $html .= '<td id="previsto-'.$insumo->insumo_id.'">'.$insumo->previsto.'</td>';
           $html .= '<td id="disponivel-'.$insumo->insumo_id.'">'.$qtde_disponivel.'</td>';
           $html .= '<td id="estoque-'.$insumo->insumo_id.'">'.$insumo->estoque.'</td>';
           $html .= '<td><input type="number" min="0" step=".01" class="form-control js-input-qtde" name="'.$insumo->insumo_id.'" id="'.$insumo->insumo_id.'"></td>';

           if ($insumo->comodo == 'true')

            $html .= '<td><button type="button" class="btn btn-primary js-btn-modal-comodo" data-id="'.$insumo->insumo_id.'" >
                    Detalhar
                </button></td>';

           else

               $html .= '<td></td>';

           $html .= '</tr>';

        }

        return $html;
    }


    public function getInsumosByComodo(Request $request) {

        $r = DB::table('levantamentos as l');

        $r->select(DB::raw('l.insumo insumo_id,l.apartamento, l.comodo, l.quantidade, l.id levantamento_id'));
        $r->leftJoin('insumos as i','i.id', '=', 'l.insumo');
        $r->leftJoin('estoque as e','e.insumo_id', '=', 'l.insumo');
        $r->where('l.obra_id',$request->query('obra'));
        $r->where('l.torre',urldecode($request->query('torre')));
        $r->where('l.pavimento',$request->query('pavimento'));
        $r->where('l.insumo',$request->query('insumo_id'));
        $r->where('l.andar',$request->query('andar'));

        $r->orderBy('l.apartamento');
        $r->orderBy('l.comodo');

        $insumos = $r->get();

        $html = '';

        foreach ($insumos as $insumo) {

            $qtde_usada = $this->getTotalUtilizadoByInsumo($request, $insumo, true);

            $qtde_disponivel = $insumo->quantidade - $qtde_usada;

            $html .= '<tr>';
            $html .= '<td id="apartamento-'.$insumo->levantamento_id.'">'.$insumo->apartamento.'</td>';
            $html .= '<td id="comodo-'.$insumo->levantamento_id.'">'.$insumo->comodo.'</td>';
            $html .= '<td id="disponivel-comodo-'.$insumo->levantamento_id.'">'.$qtde_disponivel.'</td>';

            if ($qtde_disponivel > 0)

                $html .= '<td><input type="number" min="0" step=".01" class="form-control js-input-qtde-comodo" data-id="'.$insumo->insumo_id.'" data-levantamento="'.$insumo->levantamento_id.'" id="insumo-'.$insumo->levantamento_id.'" name="insumo-'.$insumo->levantamento_id.'" value=""></td>';
            else

                $html .= '<td></td>';

            $html .= '</tr>';

        }

        return $html;
    }


    private function getTotalUtilizadoByInsumo(Request $request, $insumo,$comodo = false) {

        $r = DB::table('estoque as e');

        $r->select(DB::raw('sum(et.qtde) qtde_usada'));

        $r->leftJoin('estoque_transacao as et','e.id', '=', 'et.estoque_id');
        $r->leftJoin('requisicao as r','r.id', '=', 'et.requisicao_id');
        $r->leftJoin('requisicao_itens as ri','ri.requisicao_id', '=', 'r.id');

        $r->where('e.obra_id',$request->query('obra'));
        $r->where('torre',urldecode($request->query('torre')));
        $r->where('pavimento',$request->query('pavimento'));
        $r->where('e.insumo_id',$insumo->insumo_id);
        $r->where('et.tipo','S');

        if ($request->query('andar'))
            $r->where('andar',$request->query('andar'));

        if ($request->query('trecho'))
            $r->where('trecho',$request->query('trecho'));

        if ($comodo) {

            $r->where('apartamento', $insumo->apartamento);
            $r->where('comodo', $insumo->comodo);
        }

        $total = $r->get()->first();

        return $total->qtde_usada;
    }
}
