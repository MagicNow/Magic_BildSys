<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use Exception;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\QcInformarValorRequest;
use App\DataTables\QuadroDeConcorrenciaDataTable;
use App\Http\Requests\CreateQuadroDeConcorrenciaRequest;
use App\Http\Requests\UpdateQuadroDeConcorrenciaRequest;
use App\Repositories\QuadroDeConcorrenciaRepository;
use App\Repositories\Admin\FornecedoresRepository;
use App\Repositories\QcFornecedorRepository;
use App\Repositories\QcItemQcFornecedorRepository;
use App\Repositories\QcFornecedorEqualizacaoCheckRepository;
use Illuminate\Support\Facades\DB;
use App\Repositories\DesistenciaMotivoRepository;
use Illuminate\Support\Str;


class QuadroDeConcorrenciaController extends AppBaseController
{
    /** @var  QuadroDeConcorrenciaRepository */
    private $quadroDeConcorrenciaRepository;

    public function __construct(QuadroDeConcorrenciaRepository $quadroDeConcorrenciaRepo)
    {
        $this->quadroDeConcorrenciaRepository = $quadroDeConcorrenciaRepo;
    }

    /**
     * Display a listing of the QuadroDeConcorrencia.
     *
     * @param QuadroDeConcorrenciaDataTable $quadroDeConcorrenciaDataTable
     * @return Response
     */
    public function index(QuadroDeConcorrenciaDataTable $quadroDeConcorrenciaDataTable)
    {
        return $quadroDeConcorrenciaDataTable->render('quadro_de_concorrencias.index');
    }

    /**
     * Show the form for creating a new QuadroDeConcorrencia.
     *
     * @return Response
     */
    public function create()
    {
        return view('quadro_de_concorrencias.create');
    }

    /**
     * Store a newly created QuadroDeConcorrencia in storage.
     *
     * @param CreateQuadroDeConcorrenciaRequest $request
     *
     * @return Response
     */
    public function store(CreateQuadroDeConcorrenciaRequest $request)
    {
        $input = $request->all();

        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->create($input);

        Flash::success('Quadro De Concorrencia '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('quadro-de-concorrencias.index'));
    }

    /**
     * Display the specified QuadroDeConcorrencia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            Flash::error('Quadro De Concorrencia '.trans('common.not-found'));

            return redirect(route('quadro-de-concorrencias.index'));
        }

        return view('quadro_de_concorrencias.show')->with('quadroDeConcorrencia', $quadroDeConcorrencia);
    }

    /**
     * Show the form for editing the specified QuadroDeConcorrencia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            Flash::error('Quadro De Concorrencia '.trans('common.not-found'));

            return redirect(route('quadro-de-concorrencias.index'));
        }

        return view('quadro_de_concorrencias.edit')->with('quadroDeConcorrencia', $quadroDeConcorrencia);
    }

    /**
     * Update the specified QuadroDeConcorrencia in storage.
     *
     * @param  int              $id
     * @param UpdateQuadroDeConcorrenciaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQuadroDeConcorrenciaRequest $request)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            Flash::error('Quadro De Concorrencia '.trans('common.not-found'));

            return redirect(route('quadro-de-concorrencias.index'));
        }

        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->update($request->all(), $id);

        Flash::success('Quadro De Concorrencia '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('quadro-de-concorrencias.index'));
    }

    /**
     * Remove the specified QuadroDeConcorrencia from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            Flash::error('Quadro De Concorrencia '.trans('common.not-found'));

            return redirect(route('quadro-de-concorrencias.index'));
        }

        $this->quadroDeConcorrenciaRepository->delete($id);

        Flash::success('Quadro De Concorrencia '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('quadro-de-concorrencias.index'));
    }

    /**
     * Formulário para adicionar valores do fornecedor
     *
     * @param int $id
     *
     * @return Response
     */
    public function informarValor(
        $id,
        FornecedoresRepository $fornecedorRepository,
        DesistenciaMotivoRepository $desistenciaMotivoRepository
    ) {
        $quadro = $this->quadroDeConcorrenciaRepository
            ->with(
                'equalizacoes.itens',
                'equalizacoes.anexos',
                'itens.insumo',
                'itens.ordemDeCompraItens'
            )
            ->findWithoutFail($id);

        if (empty($quadro)) {
            Flash::error('Quadro De Concorrencia '.trans('common.not-found'));

            return redirect(route('quadro-de-concorrencias.index'));
        }

        $fornecedores = $fornecedorRepository
            ->podemPreencherQuadroNaRodada($id, $quadro->rodada_atual)
            ->pluck('nome', 'id')
            ->prepend('Selecione um fornecedor...','')
            ->toArray();

        if(count($fornecedores) === 1) {
            Flash::error('
                Este quadro já foi preenchido por todos os fornecedores possíveis'
            );

            return redirect(route('quadro-de-concorrencias.index'));
        }

        $equalizacoes = $quadro->equalizacoes
            ->pluck('itens')
            ->merge($quadro->equalizacoesExtras)
            ->flatten();

        $anexos = $quadro->equalizacoes
            ->pluck('anexos')
            ->flatten()
            ->merge($quadro->anexos);

        $motivos = $desistenciaMotivoRepository
            ->pluck('nome', 'id')
            ->prepend('Selecione um motivo...','')
            ->toArray();

        return view('quadro_de_concorrencias.informar_valor')
            ->with(compact(
                'anexos',
                'equalizacoes',
                'quadro',
                'fornecedores',
                'motivos'
            ));
    }

    /**
     * Salvar valores do fornecedor
     *
     * @param int $id
     *
     * @return Response
     */
    public function informarValorSave(
        QcInformarValorRequest $request,
        QcFornecedorRepository $qcFornecedorRepository,
        QcFornecedorEqualizacaoCheckRepository $checksRepository,
        QcItemQcFornecedorRepository $qcItemFornecedorRepository,
        $id
    ) {

        DB::beginTransaction();

        try {
            $quadro = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

            if (empty($quadro)) {
                Flash::error('Quadro De Concorrencia '.trans('common.not-found'));

                return back()->withInput();
            }

            $qcFornecedor = [
                'quadro_de_concorrencia_id' => $id,
                'fornecedor_id' => $request->fornecedor_id,
                'rodada' => $quadro->rodada_atual,
                'user_id' => $request->user()->id
            ];

            if($request->reject) {
                $qcFornecedor['desistencia_motivo_id'] = $request->desistencia_motivo_id;
                $qcFornecedor['desistencia_texto'] = $request->desistencia_texto;
            }

            $qcFornecedor = $qcFornecedorRepository->create($qcFornecedor);

            if(!$request->reject) {
                foreach($request->equalizacoes as $check) {
                    $check['qc_fornecedor_id'] = $qcFornecedor->id;
                    $check['user_id'] = $request->user()->id;
                    $checksRepository->create($check);
                }

                foreach($request->itens as $qcItemId => $item) {
                    $item['qc_fornecedor_id'] = $qcFornecedor->id;
                    $item['user_id'] = $request->user()->id;
                    $item['qc_item_id'] = $qcItemId;

                    $item['qtd'] = (float) $item['qtd'];

                    if(Str::length($item['valor_unitario'])) {
                        $item['valor_unitario'] = money_to_float($item['valor_unitario']);
                        $item['valor_total'] = $item['valor_unitario'] * $item['qtd'];
                    } else {
                        $item['valor_unitario'] = null;
                    }

                    $qcItemFornecedorRepository->create($item);
                }
            }
        } catch (Exception $e) {
            DB::rollback();
            Flash::error('Ocorreu um erro ao salvar os dados, tente novamente');

            return back()->withInput();
        }

        DB::commit();
        Flash::success('Dados salvos com sucesso');

        return redirect()->route('quadro-de-concorrencias.index');
    }
}
