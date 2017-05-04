<?php

namespace App\Http\Controllers;

use App\DataTables\QuadroDeConcorrenciaDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateQuadroDeConcorrenciaRequest;
use App\Http\Requests\UpdateQuadroDeConcorrenciaRequest;
use App\Models\Fornecedores;
use App\Models\TipoEqualizacaoTecnica;
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
            ['ordem_de_compra_itens'=>'required'],
            ['ordem_de_compra_itens.required'=>'É necessário escolher ao menos um item!']
        )->validate();

        # Cria QC pra ficar em aberto com os itens passados
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->create([
                'itens'=>$request->ordem_de_compra_itens,
                'user_id'=>Auth::id()
        ]);

        # Relacionados
        $fornecedoresRelacionados = [];

        $qcFornecedores_ids = [];

        $tiposEqualizacaoTecnicasRelacionadas = [];

        $tiposEqualizacaoTecnicasRelacionadas_ids = [];
        
        
        return view('quadro_de_concorrencias.edit',compact('quadroDeConcorrencia',
            'fornecedoresRelacionados',
            'qcFornecedores_ids',
            'tiposEqualizacaoTecnicasRelacionadas',
            'tiposEqualizacaoTecnicasRelacionadas_ids')
        );
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

        # Relacionados
        $fornecedoresRelacionados = [];

        $qcFornecedores_ids = $quadroDeConcorrencia->qcFornecedores()
            ->where('qc_fornecedor.rodada',$quadroDeConcorrencia->rodada_atual)
            ->select('qc_fornecedor.fornecedor_id')
            ->pluck('qc_fornecedor.fornecedor_id', 'qc_fornecedor.fornecedor_id')
            ->toArray();


        if(count($qcFornecedores_ids)){
            $fornecedoresRelacionados = Fornecedores::whereIn('id',$qcFornecedores_ids)
                ->pluck('nome','id')
                ->toArray();
        }

        $tiposEqualizacaoTecnicasRelacionadas = [];

        $tiposEqualizacaoTecnicasRelacionadas_ids = $quadroDeConcorrencia->tiposEqualizacaoTecnicas()
            ->select('tipo_equalizacao_tecnica_id')
            ->pluck('tipo_equalizacao_tecnica_id', 'tipo_equalizacao_tecnica_id')
            ->toArray();


        if(count($tiposEqualizacaoTecnicasRelacionadas_ids)){
            $tiposEqualizacaoTecnicasRelacionadas = TipoEqualizacaoTecnica::whereIn(
                'id',
                $tiposEqualizacaoTecnicasRelacionadas_ids)
                ->pluck('nome','id')
                ->toArray();
        }

        return view('quadro_de_concorrencias.edit',compact('quadroDeConcorrencia',
            'fornecedoresRelacionados',
            'qcFornecedores_ids',
            'tiposEqualizacaoTecnicasRelacionadas',
            'tiposEqualizacaoTecnicasRelacionadas_ids'));
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
        dd($request->all());
        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrencia)) {
            Flash::error('Quadro De Concorrencia '.trans('common.not-found'));

            return redirect(route('quadroDeConcorrencias.index'));
        }

        $quadroDeConcorrencia = $this->quadroDeConcorrenciaRepository->update($request->all(), $id);

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
}
