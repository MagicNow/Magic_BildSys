<?php

namespace App\Http\Controllers;

use App\DataTables\PagamentoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePagamentoRequest;
use App\Http\Requests\UpdatePagamentoRequest;
use App\Models\Contrato;
use App\Models\DocumentoTipo;
use App\Models\Fornecedor;
use App\Models\Notafiscal;
use App\Models\PagamentoCondicao;
use App\Repositories\MegaNfeIntegracaoRepository;
use App\Repositories\MegaXmlRepository;
use App\Repositories\PagamentoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use Response;
use SimpleXMLElement;

class PagamentoController extends AppBaseController
{
    /** @var  PagamentoRepository */
    private $pagamentoRepository;

    public function __construct(PagamentoRepository $pagamentoRepo)
    {
        $this->pagamentoRepository = $pagamentoRepo;
    }

    /**
     * Display a listing of the Pagamento.
     *
     * @param PagamentoDataTable $pagamentoDataTable
     * @return Response
     */
    public function index(PagamentoDataTable $pagamentoDataTable)
    {
        return $pagamentoDataTable->render('pagamentos.index');
    }

    /**
     * Show the form for creating a new Pagamento.
     *
     * @return Response
     */
    public function create()
    {
        $contrato = Contrato::find(request()->get('contrato_id'));
        if (!$contrato) {
            Flash::error('Contrato ' . trans('common.not-found'));
            return redirect(route('contratos.index'));
        }

        $nota = null;
        if (request()->get('contrato_id')) {
            $nota = Notafiscal::find(request()->get('nota_id'));
        }

        $pagamentoCondicoes = PagamentoCondicao::select([
            DB::raw("CONCAT(nome,' - ',codigo) as nome"),
            'id'
        ])->pluck('nome', 'id')->toArray();

        $documentoTipos = DocumentoTipo::select([
            DB::raw("CONCAT(nome,' - ',sigla) as nome"),
            'id'
        ])->pluck('nome', 'id')->toArray();

        $fornecedores_ids = $contrato->fornecedor->fornecedores_associados_ids();
        $fornecedores_ids[$contrato->fornecedor_id] = $contrato->fornecedor_id;

        $fornecedores = Fornecedor::select([
            DB::raw("CONCAT(nome,' - ',cnpj) nome"),
            'id'
        ])
            ->whereIn('id', $fornecedores_ids)
            ->pluck('nome', 'id')
            ->toArray();

        return view('pagamentos.create', compact('pagamentoCondicoes', 'documentoTipos', 'contrato', 'fornecedores', 'nota'));
    }

    /**
     * Store a newly created Pagamento in storage.
     *
     * @param CreatePagamentoRequest $request
     *
     * @return Response
     */
    public function store(CreatePagamentoRequest $request)
    {
        $input = $request->all();

        $pagamento = $this->pagamentoRepository->create($input);

        if ($pagamento->notas_fiscal_id > 0) {
            $this->integraNfe($pagamento);
        }

        Flash::success('Pagamento ' . trans('common.saved') . ' ' . trans('common.successfully') . '.');

        return redirect(route('pagamentos.index'));
    }

    /**
     * Display the specified Pagamento.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $pagamento = $this->pagamentoRepository->findWithoutFail($id);

        if (empty($pagamento)) {
            Flash::error('Pagamento ' . trans('common.not-found'));

            return redirect(route('pagamentos.index'));
        }

        return view('pagamentos.show')->with('pagamento', $pagamento);
    }

    /**
     * Show the form for editing the specified Pagamento.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $pagamento = $this->pagamentoRepository->findWithoutFail($id);

        if (empty($pagamento)) {
            Flash::error('Pagamento ' . trans('common.not-found'));

            return redirect(route('pagamentos.index'));
        }

        $contrato = $pagamento->contrato;

        $pagamentoCondicoes = PagamentoCondicao::select([
            DB::raw("CONCAT(nome,' - ',codigo) as nome"),
            'id'
        ])->pluck('nome', 'id')->toArray();

        $documentoTipos = DocumentoTipo::select([
            DB::raw("CONCAT(nome,' - ',sigla) as nome"),
            'id'
        ])->pluck('nome', 'id')->toArray();

        $fornecedores_ids = $contrato->fornecedor->fornecedores_associados_ids();
        $fornecedores_ids[$contrato->fornecedor_id] = $contrato->fornecedor_id;

        $fornecedores = Fornecedor::select([
            DB::raw("CONCAT(nome,' - ',cnpj) nome"),
            'id'
        ])
            ->whereIn('id', $fornecedores_ids)
            ->pluck('nome', 'id')
            ->toArray();

        $nota = null;
        if ($pagamento->notas_fiscal_id > 0 || request()->get("nota_id") > 0) {
            $nota = Notafiscal::find($pagamento->notas_fiscal_id ?:request()->get("nota_id"));
        }

        return view('pagamentos.edit', compact('nota', 'pagamento', 'pagamentoCondicoes', 'documentoTipos', 'contrato', 'fornecedores'));
    }

    /**
     * Update the specified Pagamento in storage.
     *
     * @param  int $id
     * @param UpdatePagamentoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePagamentoRequest $request)
    {
        $pagamento = $this->pagamentoRepository->findWithoutFail($id);

        if (empty($pagamento)) {
            Flash::error('Pagamento ' . trans('common.not-found'));

            return redirect(route('pagamentos.index'));
        }

        $integra = false;

        if ((empty($pagamento->notas_fiscal_id) || !$pagamento->notas_fiscal_id)
             AND request()->has('notas_fiscal_id') and request()->get('notas_fiscal_id') > 0
        ) {
            $integra = true;
        }

        $pagamento = $this->pagamentoRepository->update($request->all(), $id);
        $result = null;
        if ($integra) {
            $result = $this->integraNfe($pagamento);
        }

        dd($pagamento, $integra, $result);

        Flash::success('Pagamento ' . trans('common.updated') . ' ' . trans('common.successfully') . '.');

        return redirect(route('pagamentos.index'));
    }

    public function integraNfe($pagamento)
    {
        $nota = Notafiscal::find($pagamento->notas_fiscal_id);
        if ($nota AND $nota->integrado == 0 AND empty($nota->status_integracao)) {
            return MegaNfeIntegracaoRepository::integra($pagamento->notas_fiscal_id);
        }
        return null;
    }

    /**
     * Remove the specified Pagamento from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $pagamento = $this->pagamentoRepository->findWithoutFail($id);

        if (empty($pagamento)) {
            Flash::error('Pagamento ' . trans('common.not-found'));

            return redirect(route('pagamentos.index'));
        }

        $this->pagamentoRepository->delete($id);

        Flash::success('Pagamento ' . trans('common.deleted') . ' ' . trans('common.successfully') . '.');

        return redirect(route('pagamentos.index'));
    }

    public function integrar($id)
    {
        $pagamento = $this->pagamentoRepository->findWithoutFail($id);

        if (empty($pagamento)) {
            Flash::error('Pagamento ' . trans('common.not-found'));

            return redirect(route('pagamentos.index'));
        }

        $megaXML = new MegaXmlRepository();
        $xml = $megaXML->montaXMLPagamento($pagamento);

        $porta = 306; // Porta de integração de Pagamento.

        try {
            $sql = "BEGIN
                    MGCLI.pck_bld_app.F_Int_Integrador(:porta, :xml);
                    END;";

            $pdo = DB::connection('oracle')
                ->getPdo();
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':porta', $porta);
            $stmt->bindParam(':xml', $xml);

            $result = [];
            $result = $stmt->execute();

            dump($result, $xml);

        } catch (\Exception $e) {
            dump($e);
        }
        dd('Executado');
    }
}
