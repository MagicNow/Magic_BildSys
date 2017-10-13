<?php

namespace App\Repositories;

use Exception;
use App\Models\Qc;
use App\Models\QcStatus;
use App\Models\QcAvulsoStatusLog;
use Illuminate\Support\Facades\DB;
use InfyOm\Generator\Common\BaseRepository;

class QcRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Qc::class;
    }

    public function create(array $attributes)
    {
        $attributes['valor_pre_orcamento'] =  money_to_float($attributes['valor_pre_orcamento']);
        $attributes['valor_orcamento_inicial'] = money_to_float($attributes['valor_orcamento_inicial']);
        $attributes['valor_gerencial'] = money_to_float($attributes{'valor_gerencial'});
        $attributes['qc_status_id'] = QcStatus::EM_APROVACAO;
        $attributes['user_id'] = auth()->id();

        DB::beginTransaction();
        try {
            $qc = parent::create($attributes);

            QcAvulsoStatusLog::create([
                'user_id' => auth()->id(),
                'qc_status_id' => QcStatus::EM_APROVACAO,
                'qc_id' => $qc->id,
            ]);

            $anexos = $this->saveAttachments($attributes, $qc);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return $qc;
    }

    public function saveAttachments($attachments, $qc)
    {
        $qcAnexoRepository = app(QcAnexoRepository::class);

        return collect(array_get($attachments, 'anexo_arquivo', []))
            ->map(function($file, $key) use ($attachments) {
                return [
                    'file' => $file,
                    'tipo' => $attachments['anexo_tipo'][$key],
                    'descricao' => $attachments['anexo_descricao'][$key],
                ];
            })
            ->map(function ($anexo) use ($qcAnexoRepository, $qc) {
                $destinationPath = $anexo['file']->store('qc_anexos/' . date('Y') . '/' . date('m') . '/' . $qc->id, 'public');

                $attach = $qcAnexoRepository->create([
                    'qc_id' => $qc->id,
                    'arquivo' => $destinationPath,
                    'tipo' => $anexo['tipo'],
                    'descricao' => $anexo['descricao'],
                ]);

                $qc->anexos()->save($attach);

                return $attach;
            });
    }

    public function cancelar($id)
    {
        $quadroDeConcorrencia = $this->findWithoutFail($id);
        $acao_executada = false;
        $mensagens = [];

        DB::beginTransaction();

        try {
            // Altera o status do Q.C.
            $quadroDeConcorrencia->qc_status_id = QcStatus::CANCELADO;
            $quadroDeConcorrencia->save();

            QcAvulsoStatusLog::create([
                'user_id' => auth()->id(),
                'qc_status_id' => QcStatus::CANCELADO,
                'qc_id' => $id,
            ]);

        } catch(Exception $e) {
            DB::rollback();
            return [false, 'Não foi possível realizar a operação!'];
        }

        DB::commit();

        return [true, $mensagens];
    }

}
