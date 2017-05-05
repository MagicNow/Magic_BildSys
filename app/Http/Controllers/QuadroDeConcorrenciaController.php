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
use Illuminate\Support\Facades\Auth;

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
    public function create(Request $request)
    {
        # Validação básica
        validator($request->all(),
            ['ordem_de_compra_itens'=>'required'],
            ['ordem_de_compra_itens.required'=>'É necessário escolher ao menos um item!']
        )->validate();

        # Cria QC pra ficar em aberto com os itens passados
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->create([
                'itens'=>$request->ordem_de_compra_itens,
                'user_id'=>Auth::id()
        ]);

        return view('quadro_de_concorrencias.edit',compact('quadroDeConcorrencia'));
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

            return redirect(route('quadroDeConcorrencias.index'));
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

            return redirect(route('quadroDeConcorrencias.index'));
        }

        return view('quadro_de_concorrencias.edit',compact('quadroDeConcorrencia'));
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

            return redirect(route('quadroDeConcorrencias.index'));
        }

        $input = $request->all();
        $input['user_update_id'] = Auth::id();
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->update($input, $id);

        Flash::success('Quadro De Concorrencia '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('quadroDeConcorrencias.index'));
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

            return redirect(route('quadroDeConcorrencias.index'));
        }

        $this->quadroDeConcorrenciaRepository->delete($id);

        Flash::success('Quadro De Concorrencia '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('quadroDeConcorrencias.index'));
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
                'tipoEqualizacaoTecnicas.itens',
                'tipoEqualizacaoTecnicas.anexos',
                'itens.insumo',
                'itens.ordemDeCompraItens'
            )
            ->findWithoutFail($id);

        if (empty($quadro)) {
            Flash::error('Quadro De Concorrencia '.trans('common.not-found'));

            return redirect(route('quadroDeConcorrencias.index'));
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

            return redirect(route('quadroDeConcorrencias.index'));
        }

        $equalizacoes = $quadro->tipoEqualizacaoTecnicas
            ->pluck('itens')
            ->merge($quadro->equalizacaoTecnicaExtras)
            ->flatten();

        $anexos = $quadro->tipoEqualizacaoTecnicas
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

            $qcFornecedor = $qcFornecedorRepository->buscarPorQuadroEFornecedor(
                $id,
                $request->fornecedor_id
            );

            if($request->reject) {
                $qcFornecedor->update([
                    'desistencia_motivo_id' => $request->desistencia_motivo_id,
                    'desistencia_texto' => $request->desistencia_texto
                ]);
            }

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

        return redirect(route('quadroDeConcorrencias.index'));
    }
}
