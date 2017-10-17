<?php

namespace App\Http\Controllers;

use App\DataTables\NotafiscalDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateNotafiscalRequest;
use App\Http\Requests\UpdateNotafiscalRequest;
use App\Models\Contrato;
use App\Models\Cte;
use App\Models\DocumentoTipo;
use App\Models\Fornecedor;
use App\Models\Notafiscal;
use App\Models\NotaFiscalFatura;
use App\Models\NotaFiscalItem;
use App\Repositories\ConsultaCteRepository;
use App\Repositories\ConsultaNfeRepository;
use App\Repositories\MegaNfeIntegracaoRepository;
use App\Repositories\MegaXmlRepository;
use App\Repositories\NotafiscalRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Response;

class NotafiscalController extends AppBaseController
{
    /** @var  NotafiscalRepository */
    private $notafiscalRepository;
    private $consultaRepository;
    private $consultaCteRepository;

    public function __construct(NotafiscalRepository $notafiscalRepo,
                                ConsultaNfeRepository $consultaRepo,
                                ConsultaCteRepository $consultaCteRepository)
    {
        $this->notafiscalRepository = $notafiscalRepo;
        $this->consultaRepository = $consultaRepo;
        $this->consultaCteRepository = $consultaCteRepository;
    }

    /**
     * Display a listing of the Notafiscal.
     *
     * @param NotafiscalDataTable $notafiscalDataTable
     * @return Response
     */
    public function index(NotafiscalDataTable $notafiscalDataTable)
    {
        return $notafiscalDataTable->render('notafiscals.index');
    }

    /**
     * Show the form for creating a new Notafiscal.
     *
     * @return Response
     */
    public function create()
    {
        $contrato = Contrato::select([
            'contratos.id',
            DB::raw("CONCAT('Contrato: ', contratos.id, ' - ','Fornecedor: ', fornecedores.nome) as nome")
        ])
            ->join('fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id')
            ->pluck('nome', 'contratos.id')->toArray();
        return view('notafiscals.create', compact('contrato'));
    }

    /**
     * Store a newly created Notafiscal in storage.
     *
     * @param CreateNotafiscalRequest $request
     *
     * @return Response
     */
    public function store(CreateNotafiscalRequest $request)
    {
        $input = $request->all();

        $notafiscal = $this->notafiscalRepository->create($input);

        Flash::success('Notafiscal ' . trans('common.saved') . ' ' . trans('common.successfully') . '.');

        return redirect(route('notafiscals.index'));
    }

    /**
     * Display the specified Notafiscal.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        if (empty($notafiscal)) {
            Flash::error('Notafiscal ' . trans('common.not-found'));

            return redirect(route('notafiscals.index'));
        }

        return view('notafiscals.show')->with('notafiscal', $notafiscal);
    }

    /**
     * Show the form for editing the specified Notafiscal.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $notafiscal = $this->notafiscalRepository
            ->with('itens')
            ->findWithoutFail($id);

        if (empty($notafiscal)) {
            Flash::error('Notafiscal ' . trans('common.not-found'));

            return redirect(route('notafiscals.index'));
        }

        $cnpj = $notafiscal->cnpj;
        $fornecedor = Fornecedor::where(\DB::raw("replace(replace(replace(fornecedores.cnpj,'-',''),'.',''),'/','')"), $cnpj)->first();
        $contratos = [];

        try {
            $contrato = Contrato::where('id', request('contrato', $notafiscal->contrato_id))
                ->where('fornecedor_id', $fornecedor->id)
                ->first();
        } catch (\Exception $e) {
            $contrato = null;
        }

        $itensSolicitacoes = [];
        if ($contrato) {
            $entregas = $contrato->entregas;
            if (count($entregas) > 0) {
                //$solicitacoes_de_entrega[$contrato->id] = [];
                foreach($entregas as $entrega) {
                    //$solicitacoes_de_entrega[$contrato->id][$entrega->id] = $entrega->id;

                    $itens = $entrega->itens;
                    foreach ($itens as $item)
                    {
                        $itensSolicitacoes[$item->id] = [
                            'id' => $item->id,
                            'nome' => $item->insumo->nome,
                            'qtd' => ($item->qtd),
                            'unidade_sigla' => $item->insumo->unidade_sigla,
                            'valor_unitario' => ($item->valor_unitario),
                            'valor_total' => ($item->valor_total)
                        ];
                    }
                }
            }
        }

        $contratos = Contrato::where('id', request('contrato', $notafiscal->contrato_id))
            ->pluck('id', 'id')
            ->toArray();

        return view('notafiscals.edit', compact('notafiscal',
                                                'fornecedor',
                                                'contratos',
                                                'itensSolicitacoes'));
    }

    /**
     * Update the specified Notafiscal in storage.
     *
     * @param  int $id
     * @param UpdateNotafiscalRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNotafiscalRequest $request)
    {
        $acao = $request->get('acao');

        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        if ($acao == 'Rejeitar') {
            Flash::error('Nota fiscal rejeitada ' . trans('common.successfully') . '.');

            $notafiscal->status = 'Rejeitada';
            $notafiscal->status_data = date('Y-m-d H:i:s');
            $notafiscal->status_user_id = auth()->user()->id;
            $notafiscal->save();

            return redirect(route('notafiscals.edit', [$id]));
        }

        $vinculosRequest = $request->get('vinculos');

        $notafiscal->status = 'Aprovada';
        $notafiscal->status_data = date('Y-m-d H:i:s');
        $notafiscal->status_user_id = auth()->user()->id;
        $notafiscal->contrato_id = $request->get('contrato_id');
        $notafiscal->save();

        foreach ($vinculosRequest as $item_id => $solicitacoes_ids) {
            $itemNf = $notafiscal->itens()->where('id', $item_id)->first();
            $itemNf->solicitacaoEntregaItens()->sync($solicitacoes_ids);
        }

        Flash::success('Nota fiscal ' . trans('common.updated') . ' ' . trans('common.successfully') . '.');

        return redirect(route('notafiscals.pagamentos.filtro', [$notafiscal->contrato_id, $id]));
    }

    public function filtrarPagamentos($contrato_id, $nfe_id)
    {
        $contrato = Contrato::find($contrato_id);
        $nota = Notafiscal::find($nfe_id);
        $pagamentos = $contrato->pagamentos()
            ->where("notas_fiscal_id", "<>", $nfe_id)
            ->whereNull("notas_fiscal_id")
            ->get();

        return view('notafiscals.filtro_pagamentos', compact(
            'contrato',
            'nota',
            'pagamentos'
        ));
    }

    /**
     * Remove the specified Notafiscal from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        if (empty($notafiscal)) {
            Flash::error('Notafiscal ' . trans('common.not-found'));

            return redirect(route('notafiscals.index'));
        }

        $this->notafiscalRepository->delete($id);

        Flash::success('Notafiscal ' . trans('common.deleted') . ' ' . trans('common.successfully') . '.');

        return redirect(route('notafiscals.index'));
    }

    public function reprocessaNfe($id)
    {
        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        return $this->consultaRepository->reprocessaXML(
            $notafiscal->xml,
            $notafiscal->nsu,
            $notafiscal->schema
        );
    }

    public function pescadorNfe()
    {
        $result = $this->consultaRepository->syncXML(1);
        if ($result) {
            return "Sucesso - Notas Importadas ou Atualizadas: {$result} - Ultima consulta: " . date("d/m/Y H:i:s");
        }
        return "Não há notas para realizar a importação. Data: " . date("d/m/Y H:i:s");
    }

    public function buscaCTe()
    {
        if ( $this->consultaCteRepository->syncXML(1, 0) ) {
            return "Sucesso - Ultima consulta: " . date("d/m/Y H:i:s");
        }
        return "Não foram encontrados CTe's para download. Data: " . date("d/m/Y H:i:s");
    }

    public function visualizaDanfe($id)
    {
        $notafiscal = Notafiscal::find($id);
        return $this->consultaRepository->geraDanfe($notafiscal);
    }

    public function visualizaDacte($id)
    {
        $cte = Cte::find($id);
        return $this->consultaCteRepository->geraDacte($cte);
    }

    public function visualizaDacteV3($id)
    {
        $cte = Cte::find($id);
        return $this->consultaCteRepository->geraDacteV3($cte);
    }

    public function manifesta()
    {
        try {
            $notas = $this->consultaRepository->manifestaNotas();
            if (count($notas)) {
                return sprintf("%s notas manifestadas com sucesso.", count($notas));
            }
            return "Não há notas para manifestação.";
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function filtraFornecedorContratos()
    {
        $fornecedores = Fornecedor::whereHas('contratos')
            ->whereHas("contratos.entregas")
            ->orderBy('id')
            ->get();

        $contratos = [];
        $contratosArr = [];
        $fornecedoresArr = [];
        if ($fornecedores) {
            foreach ($fornecedores as $fornecedor) {
                if (
                    $contratosFornecedor = $fornecedor->contratos()
                        ->with("entregas", "obra")
                        ->get()
                    AND count($contratosFornecedor) > 0
                ) {
                    $fornecedoresArr[$fornecedor->id] = sprintf("%s [ %s ]", $fornecedor->nome, $fornecedor->cnpj);;

                    $contratos[$fornecedor->id] = $contratosFornecedor;
                    foreach ($contratosFornecedor as $c) {
                        $contratosArr[$fornecedor->id][$c->id] = [
                            'text' => sprintf('Contrato Nº: %s', $c->id),
                            'id' => $c->id
                        ];
                    }
                }
            }
        }

        $notasFiscais = [];
        foreach ($fornecedores as $fornecedor) {
            $notasFiscais[$fornecedor->id] = [];
            $notas = Notafiscal::where(\DB::raw(
                "replace(replace(replace(cnpj,'-',''),'.',''),'/','')"),
                str_replace(['.', '-', '/'], '',$fornecedor->cnpj
                )
            )->where("schema", "like", "%procNFe%")
                ->get();

            foreach ($notas as $nota) {
                $notasFiscais[$fornecedor->id][$nota->id] = [
                    'text' => sprintf('Nota Nº: %s', $nota->codigo),
                    'id' => $nota->id
                ];
            }
        }

        return view('notafiscals.filtro',
            compact('contratos', 'fornecedores', 'fornecedoresArr', 'notasFiscais', 'contratosArr')
        );
    }

    public function integraMega($id)
    {
        try {
            $nota = MegaNfeIntegracaoRepository::integra($id);

            return sprintf("Integração de Nota fiscal n.o: %s realizada, aguarde a integração.", $nota->codigo);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function importaNfe()
    {
        return view('notafiscals.import');
    }

    public function postImportaNfe(Request $request)
    {
        $xml = file_get_contents($request->file('nota_fiscal'));

        $dataNfe = $this->consultaRepository->extraiData($xml, NULL, NULL);
        $nfObj = $this->consultaRepository->saveData($dataNfe);

        return redirect(route('notafiscals.edit', [$nfObj->id]));
    }
}
