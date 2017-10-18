<?php

namespace App\Http\Controllers;

use App\DataTables\RequisicaoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateRequisicaoRequest;
use App\Http\Requests\UpdateRequisicaoRequest;
use App\Models\AplicacaoEstoqueInsumo;
use App\Models\AplicacaoEstoqueLocal;
use App\Models\Requisicao;
use App\Models\RequisicaoItem;
use App\Models\RequisicaoStatus;
use App\Repositories\RequisicaoItemRepository;
use App\Models\RequisicaoSaidaLeitura;
use App\Repositories\RequisicaoRepository;
use App\Repositories\Admin\ObraRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Response;
use PDF;

class RequisicaoController extends AppBaseController
{
    /** @var  RequisicaoRepository */
    private $requisicaoRepository;
    private $requisicaoItemRepository;
    private $obraRepository;

    public function __construct(RequisicaoRepository $requisicaoRepo,
                                ObraRepository $obraRepo,
                                RequisicaoItemRepository $requisicaoItemRepository
                                )
    {
        $this->requisicaoRepository = $requisicaoRepo;
        $this->requisicaoItemRepository = $requisicaoItemRepository;
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

        if ($requisicao) {

            Flash::success('Requisicao ' . trans('common.saved') . ' ' . trans('common.successfully') . '.');

            return response()->json(['success' => true]);

        } else {

            return response()->json(['success' => false]);
        }

    }

    /**
     * Display the specified Requisicao.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id,$update = false)
    {

        if ($update) {

            $updt['status_id'] = RequisicaoStatus::EM_SEPARACAO;

            $requisicao = $this->requisicaoRepository->update($updt, $id);
        }

        $requisicao = $this->requisicaoRepository->getRequisicao($id);

        if (empty($requisicao)) {
            Flash::error('Requisicao '.trans('common.not-found'));

            return redirect(route('requisicao.index'));
        }

        $table = $this->requisicaoItemRepository->getRequisicaoItensShow($id);



        return view('requisicao.show', compact('requisicao', 'table'));
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
        $requisicao = $this->requisicaoRepository->getRequisicao($id);

        $status = DB::table('requisicao_status')->get()->pluck('nome','id');

        $table = $this->requisicaoItemRepository->getRequisicaoItens($id);

        $itens_comodo = $this->requisicaoItemRepository->getInsumosRequisicaoByComodo($id);

        if (empty($requisicao)) {
            Flash::error('Requisicao '.trans('common.not-found'));

            return redirect(route('requisicaos.index'));
        }

        return view('requisicao.edit', compact('requisicao', 'status', 'table', 'itens_comodo'));
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

        $this->requisicaoItemRepository->updateRequisicaoItem($request->all());

        if ($requisicao) {

            Flash::success('Requisicao ' . trans('common.updated') . ' ' . trans('common.successfully') . '.');

            return response()->json(['success' => true]);

        } else {

            return response()->json(['success' => false]);
        }
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

        $r->select(DB::raw('i.nome insumo, i.id insumo_id, i.unidade_sigla , sum(l.quantidade) previsto, e.qtde estoque, e.id estoque_id ,IF(comodo <> " ","true","false") as comodo'));
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
           $html .= '<td><input type="number" min="0" step=".01" class="form-control js-input-qtde" name="'.$insumo->insumo_id.'" id="'.$insumo->insumo_id.'" data-estoque="'.$insumo->estoque_id.'"></td>';

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


    public function getInsumosByComodoEdit(Request $request) {

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
        $r->where('ri.torre',urldecode($request->query('torre')));
        $r->where('ri.pavimento',$request->query('pavimento'));
        $r->where('e.insumo_id',$insumo->insumo_id);
        $r->where('et.tipo','S');

        if ($request->query('andar'))
            $r->where('ri.andar',$request->query('andar'));

        if ($request->query('trecho'))
            $r->where('ri.trecho',$request->query('trecho'));

        if ($comodo) {

            $r->where('ri.apartamento', $insumo->apartamento);
            $r->where('ri.comodo', $insumo->comodo);
        }

        $total = $r->get()->first();

        return $total->qtde_usada;
    }

    public function processoSaida(Requisicao $requisicao)
    {
        $requisicao_itens = self::itensInconsistentes($requisicao);
        $tem_inconsistencia = false;

        foreach($requisicao_itens as $item) {
            if($item->inconsistencia != 'OK') {
                $tem_inconsistencia = true;
                break;
            }
        }
        
        return view('requisicao.processo_saida.index', compact('requisicao', 'tem_inconsistencia'));
    }

    public function lerInsumoSaida(Requisicao $requisicao)
    {
        return view('requisicao.processo_saida.leitor_saida', compact('requisicao'));
    }

    public function salvarLeituraSaida(Request $request)
    {
        $dados = json_decode($request->dados);
        $requisicao_item = RequisicaoItem::find($dados->requisicao_item_id);
        $sucesso = false;
        
        if($requisicao_item) {
            $requisicao_saida = new RequisicaoSaidaLeitura(
                [
                    'requisicao_item_id' => $dados->requisicao_item_id,
                    'qtd_lida' => money_to_float($dados->qtd_lida)
                ]);

            $sucesso = $requisicao_saida->save();   
        }
        
        return response()->json(['sucesso' => $sucesso]);
    }

    public function listaInconsistencia(Requisicao $requisicao)
    {
        $requisicao_itens = self::itensInconsistentes($requisicao);
        return view('requisicao.processo_saida.lista_inconsistencia', compact('requisicao', 'requisicao_itens'));
    }

    public function excluirLeitura(Request $request)
    {
        $requisicao = Requisicao::find($request->requisicao_id);
        
        if($requisicao) {
            $requisicao_itens = $requisicao->requisicaoItens->pluck('id', 'id')->toArray();

            if(count($requisicao_itens)) {
                $leituras = RequisicaoSaidaLeitura::whereIn('requisicao_item_id', $requisicao_itens)->pluck('id', 'id')->toArray();
                if(count($leituras)) {
                    RequisicaoSaidaLeitura::destroy($leituras);
                }
            }
        }
        
        return response()->json(true);
    }

    public function itensInconsistentes($requisicao)
    {
        $requisicao_itens = RequisicaoItem::select([
            'requisicao_itens.id',
            DB::raw('(
                    SELECT 
                        GROUP_CONCAT(grupos.nome, " - ", servicos.nome)
                    FROM
                        requisicao_itens
                            INNER JOIN
                        estoque ON estoque.id = requisicao_itens.estoque_id
                            INNER JOIN
                        estoque_transacao ON estoque_transacao.estoque_id = estoque.id
                            INNER JOIN
                        contrato_item_apropriacoes ON contrato_item_apropriacoes.id = estoque_transacao.contrato_item_apropriacao_id
                            INNER JOIN
                        grupos ON grupos.id = contrato_item_apropriacoes.subgrupo3_id
                            INNER JOIN
                        servicos ON servicos.id = contrato_item_apropriacoes.servico_id
                    WHERE
                        requisicao_itens.requisicao_id = '.$requisicao->id.'
                ) as agrupamento'),
            'insumos.nome AS insumo',
            'insumos.unidade_sigla AS unidade_medida',
            DB::raw("format(requisicao_itens.qtde,2,'de_DE') AS qtd_solicitada"),
            DB::raw('(
                        SELECT 
                            FORMAT(SUM(qtd_lida), 2, "de_DE")
                        FROM
                            requisicao_saida_leitura
                        WHERE
                            requisicao_item_id = requisicao_itens.id
                ) AS qtd_lida'),
            DB::raw('(
                        SELECT 
                            COUNT(id)
                        FROM
                            requisicao_saida_leitura
                        WHERE
                            requisicao_item_id = requisicao_itens.id
                ) AS numero_leituras'),
            DB::raw(
                'IF(
                        (SELECT 
                            FORMAT(SUM(qtd_lida), 2, "de_DE")
                        FROM
                            requisicao_saida_leitura
                        WHERE
                            requisicao_item_id = requisicao_itens.id)
                     = 
                        (format(requisicao_itens.qtde, 2, "de_DE"))
                    , "OK", "NOK") AS inconsistencia'),
        ])
            ->join('estoque','estoque.id','requisicao_itens.estoque_id')
            ->join('insumos','insumos.id','estoque.insumo_id')
            ->where('requisicao_itens.requisicao_id', $requisicao->id)
            ->get();

        return $requisicao_itens;
    }
    
    public function finalizarSaida(Requisicao $requisicao)
    {
        $requisicao->status_id = RequisicaoStatus::EM_TRANSITO;
        $requisicao->save();

        return redirect(route('requisicao.index'));
    }

    public function salvarLeituraAplicacaoLocal(Request $request)
    {
        $dados = json_decode($request->dados);

        $local_aplicacao = AplicacaoEstoqueLocal::create([
            'pavimento' => $dados->pavimento,
            'andar' => $dados->andar,
            'apartamento' => $dados->apartamento,
            'comodo' => $dados->comodo
        ]);
        
        return response()->json(['sucesso' => true, 'local_aplicacao' => $local_aplicacao->id]);
    }

    public function aplicacaoEstoqueInsumo(AplicacaoEstoqueLocal $local_aplicacao)
    {
        return view('requisicao.aplicacao_estoque.insumos', compact('local_aplicacao'));
    }

    public function salvarLeituraAplicacaoInsumo(Request $request)
    {
        $dados = json_decode($request->dados);
        $sucesso = false;
        $erros = '';
        $existe_insumo = false;

        $local_aplicacao = AplicacaoEstoqueLocal::find($dados->aplicacao_estoque_local_id);

        if ($local_aplicacao) {
            if ($local_aplicacao->pavimento !== $dados->pavimento) {
                $erros .= 'O insumo não pertence a este pavimento.<br>';
            }
            if ($local_aplicacao->andar !== $dados->andar) {
                $erros .= 'O insumo não pertence a este andar.<br>';
            }
            if ($local_aplicacao->apartamento !== $dados->apartamento) {
                $erros .= 'O insumo não pertence a este apartamento.<br>';
            }
            if ($local_aplicacao->comodo !== $dados->comodo) {
                $erros .= 'O insumo não pertence a este cômodo.<br>';
            }
        } else {
            $erros .= 'Não foi encontrado o local para a aplicação deste insumo.<br>';
        }

        $requisicao = Requisicao::find($dados->requisicao_id);

        if(count($requisicao->requisicaoItens)) {
            foreach($requisicao->requisicaoItens as $item) {
                if($item->qtde < $dados->qtd) {
                    $erros .= 'Quantidade do insumo inválida.<br>';
                }

                if($item->estoque->insumo_id == $dados->insumo_id) {
                    $existe_insumo = true;
                    break;
                }
            }
        }

        if(!$existe_insumo) {
            $erros .= 'Não foi encontrado o insumo na requisição.<br>';
        }

        if (!$erros) {
            AplicacaoEstoqueInsumo::create([
                'requisicao_id' => $dados->requisicao_id,
                'aplicacao_estoque_local_id' => $dados->aplicacao_estoque_local_id,
                'obra_id' => $dados->obra_id,
                'insumo_id' => $dados->insumo_id,
                'qtd' => $dados->qtd,
                'unidade_medida' => $dados->unidade_medida,
                'pavimento' => $dados->pavimento,
                'andar' => $dados->andar,
                'apartamento' => $dados->apartamento,
                'comodo' => $dados->comodo
            ]);
            $sucesso = true;
        }

        if($sucesso) {
            RequisicaoRepository::verificaAplicacao($requisicao, $dados->insumo_id, $dados->qtd);
        }

        return response()->json(['sucesso' => $sucesso, 'erros' => $erros]);
    }

    public function modalQrCode(Request $request) {

        $r = DB::table('requisicao_itens as ri');
        $r->select(DB::raw(
            'i.nome insumo, 
            ri.*'));

        $r->leftJoin('requisicao as r','ri.requisicao_id', '=', 'r.id');
        $r->leftJoin('estoque as e','ri.estoque_id', '=', 'e.id');
        $r->leftJoin('insumos as i','e.insumo_id', '=', 'i.id');
        $r->where('ri.requisicao_id',$request->query('requisicao_id'));
        $r->where('ri.estoque_id',$request->query('estoque_id'));
        $r->orderBy('apartamento','asc');
        $r->orderBy('comodo','asc');
        $insumos = $r->get();

        return response()->view('requisicao.modal_impressao', compact('request','insumos'));
    }


    public function impressaoQrCode(Request $request) {

        if ($request->query('all') == 'true') {

            $r = DB::table('requisicao_itens as ri');
            $r->select(DB::raw('i.nome insumo, ri.*, o.nome'));

            $r->leftJoin('requisicao as r', 'ri.requisicao_id', '=', 'r.id');
            $r->leftJoin('estoque as e', 'ri.estoque_id', '=', 'e.id');
            $r->leftJoin('insumos as i', 'e.insumo_id', '=', 'i.id');
            $r->leftJoin('obras as o', 'r.obra_id', '=', 'o.id');
            $r->where('ri.requisicao_id', $request->query('requisicao_id'));
            $r->where('ri.estoque_id', $request->query('estoque_id'));

            $r->orderBy('apartamento', 'comodo');

            $item = $r->get();

        } else {

            $r = DB::table('requisicao_itens as ri');
            $r->select(DB::raw('i.nome insumo, ri.*, o.nome'));

            $r->leftJoin('requisicao as r', 'ri.requisicao_id', '=', 'r.id');
            $r->leftJoin('estoque as e', 'ri.estoque_id', '=', 'e.id');
            $r->leftJoin('insumos as i', 'e.insumo_id', '=', 'i.id');
            $r->leftJoin('obras as o', 'r.obra_id', '=', 'o.id');
            $r->where('ri.id', $request->query('id'));

            $r->orderBy('apartamento', 'comodo');

            $item = $r->first();

        }

        return response()->view('requisicao.impressao_qrcode',compact('item','all', 'request'));
    }
}
