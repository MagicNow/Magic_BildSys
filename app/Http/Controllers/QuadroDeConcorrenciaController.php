<?php

namespace App\Http\Controllers;

use App\DataTables\QuadroDeConcorrenciaDataTable;
use App\Http\Requests;
use App\Http\Requests\UpdateQuadroDeConcorrenciaRequest;
use App\Http\Requests\CreateEqualizacaoTecnicaExtraRequest;
use App\Http\Requests\UpdateEqualizacaoTecnicaExtraRequest;
use App\Http\Requests\CreateEqualizacaoTecnicaAnexoExtraRequest;
use App\Http\Requests\UpdateEqualizacaoTecnicaAnexoExtraRequest;
use App\Models\QcEqualizacaoTecnicaAnexoExtra;
use App\Models\QcEqualizacaoTecnicaExtra;
use App\Models\QcFornecedor;
use App\Repositories\QuadroDeConcorrenciaRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;

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
            ['ordem_de_compra_itens' => 'required'],
            ['ordem_de_compra_itens.required' => 'É necessário escolher ao menos um item!']
        )->validate();

        # Cria QC pra ficar em aberto com os itens passados
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->create([
            'itens' => $request->ordem_de_compra_itens,
            'user_id' => Auth::id()
        ]);

        return view('quadro_de_concorrencias.edit', compact('quadroDeConcorrencia'));
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
            Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

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
            Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

            return redirect(route('quadroDeConcorrencias.index'));
        }

        return view('quadro_de_concorrencias.edit', compact('quadroDeConcorrencia'));
    }

    /**
     * Update the specified QuadroDeConcorrencia in storage.
     *
     * @param  int $id
     * @param UpdateQuadroDeConcorrenciaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQuadroDeConcorrenciaRequest $request)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

            return redirect(route('quadroDeConcorrencias.index'));
        }
        $input = $request->all();
        $input['user_update_id'] = Auth::id();
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->update($input, $id);

        Flash::success('Quadro De Concorrencia ' . trans('common.updated') . ' ' . trans('common.successfully') . '.');

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
            Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

            return redirect(route('quadroDeConcorrencias.index'));
        }

        $this->quadroDeConcorrenciaRepository->delete($id);

        Flash::success('Quadro De Concorrencia ' . trans('common.deleted') . ' ' . trans('common.successfully') . '.');

        return redirect(route('quadroDeConcorrencias.index'));
    }

    public function adicionaEqt($id, CreateEqualizacaoTecnicaExtraRequest $request)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }
        $qcEqualizacaoTecnicaExtra = QcEqualizacaoTecnicaExtra::create([
            'quadro_de_concorrencia_id' => $id,
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'obrigatorio' => $request->obrigatorio
        ]);

        return $qcEqualizacaoTecnicaExtra;
    }

    public function removerEqt($id, $eqtId)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }
        $qcEqualizacaoTecnicaExtra = QcEqualizacaoTecnicaExtra::find($eqtId);

        if (!$qcEqualizacaoTecnicaExtra) {
            return response()->json(['error' => 'Item não encontrado ' . trans('common.not-found')], 404);
        }

        return response()->json(['success' => $qcEqualizacaoTecnicaExtra->delete()]);
    }

    public function exibirEqt($id, $eqtId)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }
        $qcEqualizacaoTecnicaExtra = QcEqualizacaoTecnicaExtra::find($eqtId);

        if (!$qcEqualizacaoTecnicaExtra) {
            return response()->json(['error' => 'Item não encontrado ' . trans('common.not-found')], 404);
        }

        return $qcEqualizacaoTecnicaExtra;
    }

    public function editarEqt($id, $eqtId, UpdateEqualizacaoTecnicaExtraRequest $request)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }

        $qcEqualizacaoTecnicaExtra = QcEqualizacaoTecnicaExtra::find($eqtId);
        if (!$qcEqualizacaoTecnicaExtra) {
            return response()->json(['error' => 'Item não encontrado ' . trans('common.not-found')], 404);
        }

        $qcEqualizacaoTecnicaExtra->nome = $request->nome;
        $qcEqualizacaoTecnicaExtra->descricao = $request->descricao;
        $qcEqualizacaoTecnicaExtra->obrigatorio = $request->obrigatorio;
        $qcEqualizacaoTecnicaExtra->save();

        return $qcEqualizacaoTecnicaExtra;
    }

    public function removerFornecedor($id, $fornecedorId)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }
        $qcFornecedor = QcFornecedor::find($fornecedorId);

        if (!$qcFornecedor) {
            return response()->json(['error' => 'Item não encontrado ' . trans('common.not-found')], 404);
        }

        return response()->json(['success' => $qcFornecedor->delete()]);
    }

    public function adicionaEqtAnexo($id, CreateEqualizacaoTecnicaAnexoExtraRequest $request)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }
        $qcEqualizacaoTecnicaExtra = QcEqualizacaoTecnicaAnexoExtra::create([
            'quadro_de_concorrencia_id' => $id,
            'nome' => $request->nome,
            'arquivo' => $request->arquivo->store('public/anexos'),
        ]);

        return $qcEqualizacaoTecnicaExtra;
    }

    public function removerEqtAnexo($id, $eqtId)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }
        $qcEqualizacaoTecnicaAnexoExtra = QcEqualizacaoTecnicaAnexoExtra::find($eqtId);

        if (!$qcEqualizacaoTecnicaAnexoExtra) {
            return response()->json(['error' => 'Item não encontrado ' . trans('common.not-found')], 404);
        }

        return response()->json(['success' => $qcEqualizacaoTecnicaAnexoExtra->delete()]);
    }

    public function exibirEqtAnexo($id, $eqtId)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }
        $qcEqualizacaoTecnicaAnexoExtra = QcEqualizacaoTecnicaAnexoExtra::find($eqtId);

        if (!$qcEqualizacaoTecnicaAnexoExtra) {
            return response()->json(['error' => 'Item não encontrado ' . trans('common.not-found')], 404);
        }

        return $qcEqualizacaoTecnicaAnexoExtra;
    }

    public function editarEqtAnexo($id, $eqtId, UpdateEqualizacaoTecnicaAnexoExtraRequest $request)
    {
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            if (!$request->ajax()) {
                Flash::error('Quadro De Concorrencia ' . trans('common.not-found'));

                return redirect(route('quadroDeConcorrencias.edit', $id));
            }
            return response()->json(['error' => 'Quadro De Concorrencia ' . trans('common.not-found')], 404);
        }

        $qcEqualizacaoTecnicaAnexoExtra = QcEqualizacaoTecnicaAnexoExtra::find($eqtId);
        if (!$qcEqualizacaoTecnicaAnexoExtra) {
            return response()->json(['error' => 'Item não encontrado ' . trans('common.not-found')], 404);
        }

        $qcEqualizacaoTecnicaAnexoExtra->nome = $request->nome;
        if($request->arquivo){
            $qcEqualizacaoTecnicaAnexoExtra->arquivo = $request->arquivo->store('public/anexos');
        }
        $qcEqualizacaoTecnicaAnexoExtra->save();

        return $qcEqualizacaoTecnicaAnexoExtra;
    }
}
