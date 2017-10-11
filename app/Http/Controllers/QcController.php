<?php

namespace App\Http\Controllers;

use Exception;
use App\DataTables\QcDataTable;
use App\DataTables\QcAnexosDataTable;
use Illuminate\Http\Request;

use Laracasts\Flash\Flash;
use App\Repositories\QcRepository;
use App\Repositories\QcAnexoRepository;
use App\Repositories\CodeRepository;
use App\Http\Requests\CreateQcRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Obra;
use App\Models\Carteira;
use App\Models\Tipologia;
use App\Models\Qc;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\WorkflowAlcada;
use App\Models\WorkflowAprovacao;
use App\Models\WorkflowTipo;
use App\Notifications\WorkflowNotification;
use App\Repositories\WorkflowAprovacaoRepository;
use App\Repositories\NotificationRepository;
use App\Models\WorkflowReprovacaoMotivo;
use App\Models\QcStatus;

class QcController extends AppBaseController
{
    /** @var  QcRepository */
    private $qcRepository;

    public function __construct(QcRepository $qcRepo, QcAnexoRepository $qcAnexoRepo)
    {
        $this->qcRepository = $qcRepo;
        $this->qcAnexoRepository = $qcAnexoRepo;
    }

    /**
     * Display a listing of the Carteiras Sla.
     *
     * @param QcDataTable $qcDataTable
     * @return Response
     */
    public function index(QcDataTable $qcDataTable) {
        $obras = Obra::pluck('nome','id')
            ->prepend('Filtrar por obra...', '');
        $tipologias = Tipologia::pluck('nome','id')
            ->prepend('Filtrar por tipologia...', '');

        $status = [
            '' => 'Filtrar por status...',
            QcStatus::EM_APROVACAO => 'Em Validação',
            QcStatus::REPROVADO => 'Reprovado',
            QcStatus::APROVADO => 'Aprovado',
            QcStatus::EM_CONCORRENCIA => 'Em Negociação',
            QcStatus::FINALIZADO => 'Finalizada',
        ];

        return $qcDataTable->render('qc.index', compact('obras', 'tipologias', 'status'));
    }

    /**
     * Show the form for creating a new Qc.
     *
     * @return Response
     */
    public function create()
    {
        $obras = Obra::pluck('nome','id')->prepend('Escolha a obra...', '');
        $carteiras = Carteira::pluck('nome','id')
            ->prepend('Escolha a carteira...', '');
        $tipologias = Tipologia::pluck('nome','id')
            ->prepend('Escolha a tipologia...', '');

        return view('qc.create', compact('obras', 'carteiras', 'tipologias'));
    }

    /**
     * Store a newly created Qc in storage.
     *
     * @param CreateQcRequest $request
     *
     * @return Response
     */
    public function store(CreateQcRequest $request)
    {
        $qc = $this->qcRepository->create($request->all());

        Flash::success('QC '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('qc.index'));
    }

    /**
     * Display the specified Qc.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $qc = $this->qcRepository->findWithoutFail($id);

        if (empty($qc)) {
            Flash::error('Qc '.trans('common.not-found'));

            return redirect(route('qc.index'));
        }

        NotificationRepository::marcarLido(WorkflowTipo::QC_AVULSO, $qc->id);

        $motivos = (new WorkflowReprovacaoMotivo)
            ->where(function ($query) {
                $query->where('workflow_tipo_id', 2);
                $query->orWhereNull('workflow_tipo_id');
            })
            ->pluck('nome', 'id')
            ->toArray();

        $dataUltimoPeriodo = $qc->dataUltimoPeriodoAprovacao();

        $emAprovacao = $qc->isStatus(QcStatus::EM_APROVACAO);

        if(!$dataUltimoPeriodo) {
            $dataUltimoPeriodo = $qc->updated_at;
        }

        $alcadas = WorkflowAlcada::where('workflow_tipo_id', WorkflowTipo::QC_AVULSO)
            ->orderBy('ordem', 'ASC')
            ->where('created_at', '<=', $dataUltimoPeriodo)
            ->get();

        if ($emAprovacao) {
            $workflowAprovacao = WorkflowAprovacaoRepository::verificaAprovacoes(
                'Qc',
                $qc->id,
                auth()->user()
            );

            foreach ($alcadas as $alcada) {
                $avaliado_reprovado[$alcada->id] = WorkflowAprovacaoRepository::verificaTotalJaAprovadoReprovado(
                    'Qc',
                    $qc->irmaosIds(),
                    null,
                    $qc->id,
                    $alcada->id);

                $avaliado_reprovado[$alcada->id]['aprovadores'] = WorkflowAprovacaoRepository::verificaQuantidadeUsuariosAprovadores(
                    WorkflowTipo::find(WorkflowTipo::QC_AVULSO),
                    $qc->obra_id,
                    $alcada->id,
                    [$qc->id=>$qc->id],
                    'Qc'
                );

                $avaliado_reprovado[$alcada->id] ['faltam_aprovar'] = WorkflowAprovacaoRepository::verificaUsuariosQueFaltamAprovar(
                    'Qc',
                    WorkflowTipo::QC_AVULSO,
                    $qc->obra_id,
                    $alcada->id,
                    [$qc->id]
                );

                // Data do início da  Alçada
                if ($alcada->ordem === 1) {
                    $qc_log = $qc->logs()
                        ->where('qc_status_id', 4)->first();

                    if ($qc_log) {
                        $avaliado_reprovado[$alcada->id] ['data_inicio'] = $qc_log->created_at
                            ->format('d/m/Y H:i');
                    }
                } else {
                    $primeiro_voto = WorkflowAprovacao::where('aprovavel_type', 'App\\Models\\Qc')
                        ->where('aprovavel_id', $qc->id)
                        ->where('workflow_alcada_id', $alcada->id)
                        ->orderBy('id', 'ASC')
                        ->first();
                    if ($primeiro_voto) {
                        $avaliado_reprovado[$alcada->id]['data_inicio'] = $primeiro_voto->created_at->format('d/m/Y H:i');
                    }
                }
            }
        }

        $attachments = $qc->anexos->groupBy('tipo');

        return view('qc.show', compact(
            'qc',
            'attachments',
            'motivos',
            'aprovavelTudo',
            'workflowAprovacao',
            'avaliado_reprovado',
            'qtd_itens',
            'alcadas_count',
            'emAprovacao',
            'oc_status'
        ));
    }

    /**
     * Update the specified Q.C. in storage.
     *
     * @param  int              $id
     * @param UpdateQcRequest $request
     *
     * @return Response
     */
    public function update($id, CreateQcRequest $request)
    {
        $input = $request->except('file');
        $qc = $this->qcRepository->findWithoutFail($id);

        if (empty($qc)) {
            Flash::error('Qc '.trans('common.not-found'));

            return redirect(route('qc.index'));
        }

        $qc = $this->qcRepository->update($input, $id);

        if($request->anexo_arquivo){
            foreach($request->anexo_arquivo as $key => $file) {
                $destinationPath = CodeRepository::saveFile($file, 'qc/' . $qc->id);

                $attach = $this->qcAnexoRepository->create([
                    'arquivo' => $destinationPath,
                    'tipo' => $request->anexo_tipo[$key],
                    'descricao' => $request->anexo_descricao[$key],
                ]);

                $qc->anexos()->save($attach);
            }
        }

        Flash::success('Q.C. '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('qc.index'));
    }

    /**
     * Remove the specified Qc from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $qc = $this->qcRepository->findWithoutFail($id);

        if (empty($qc)) {
            Flash::error('Qc '.trans('common.not-found'));

            return redirect(route('qc.index'));
        }

        $this->qcRepository->delete($id);

        Flash::success('Qc '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('qc.index'));
    }
}
