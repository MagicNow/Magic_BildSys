<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\DataTables\QuadroDeConcorrenciaDataTable;
use App\Http\Requests\CreateQuadroDeConcorrenciaRequest;
use App\Http\Requests\UpdateQuadroDeConcorrenciaRequest;
use App\Repositories\QuadroDeConcorrenciaRepository;
use App\Http\Controllers\AppBaseController;


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
    public function informarValor($id)
    {
        $quadro = $this->quadroDeConcorrenciaRepository
            ->with('equalizacoes.itens', 'equalizacoes.anexos', 'itens.insumo')
            ->findWithoutFail($id);

        if (empty($quadro)) {
            Flash::error('Quadro De Concorrencia '.trans('common.not-found'));

            return redirect(route('quadro-de-concorrencias.index'));
        }

        $equalizacoes = $quadro->equalizacoes
            ->pluck('itens')
            ->merge($quadro->equalizacoesExtras)
            ->flatten();

        $anexos = $quadro->equalizacoes->pluck('anexos')->flatten();

        return view('quadro_de_concorrencias.informar_valor')
                ->with(compact('anexos', 'equalizacoes', 'quadro'));
    }

    /**
     * Salvar valores do fornecedor
     *
     * @param int $id
     *
     * @return Response
     */
    public function informarValorSave(Request $request, $id)
    {
        dd($request->all());
    }
}
