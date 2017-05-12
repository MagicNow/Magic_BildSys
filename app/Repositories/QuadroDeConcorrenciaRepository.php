<?php

namespace App\Repositories;

use App\Mail\IniciaConcorrenciaFornecedorNaoUsuario;
use App\Models\Fornecedor;
use App\Models\OrdemDeCompraItem;
use App\Models\QcItem;
use App\Models\QcStatusLog;
use App\Models\QuadroDeConcorrencia;
use App\Notifications\IniciaConcorrencia;
use Illuminate\Support\Facades\Mail;
use InfyOm\Generator\Common\BaseRepository;
use App\Models\User;

class QuadroDeConcorrenciaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'qc_status_id',
        'obrigacoes_fornecedor',
        'obrigacoes_bild',
        'rodada_atual'
    ];

    public function create(array $attributes)
    {
        $itens = $attributes['itens'];
        $attributes = [
            'user_id' => $attributes['user_id'],
            'qc_status_id' => 1,

            'obrigacoes_fornecedor' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur interdum
            rutrum magna, eu dignissim nunc malesuada ac. Vestibulum velit libero, egestas non sapien ac, egestas
            bibendum massa. Donec vel luctus erat. Fusce ultrices lectus justo, a sollicitudin libero vestibulum vitae.
            Nullam at quam metus. Aliquam faucibus sapien vel velit tempor, congue dignissim libero viverra. Morbi
            vestibulum eros eget tempor fermentum.',

        'obrigacoes_bild' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur interdum
            rutrum magna, eu dignissim nunc malesuada ac. Vestibulum velit libero, egestas non sapien ac, egestas
            bibendum massa. Donec vel luctus erat. Fusce ultrices lectus justo, a sollicitudin libero vestibulum vitae.
            Nullam at quam metus. Aliquam faucibus sapien vel velit tempor, congue dignissim libero viverra. Morbi
            vestibulum eros eget tempor fermentum.',

        'rodada_atual' => 1
        ];
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $model = parent::create($attributes);
        $this->skipPresenter($temporarySkipPresenter);

        // Salva o primeiro status log
        QcStatusLog::create([
            'quadro_de_concorrencia_id' => $model->id,
            'qc_status_id' => $model->qc_status_id,
            'user_id' => $model->user_id
        ]);

        // Busca e agrupa intens conforme o tipo
        $oc_itens = OrdemDeCompraItem::whereIn('id', $itens)->get();
        $qc_itens_array = [];
        foreach ($oc_itens as $oc_item) {
            if (isset($qc_itens_array[$oc_item->insumo_id])) {
                $qc_itens_array[$oc_item->insumo_id]['qtd'] += floatval($oc_item->qtd);
            } else {
                $qc_itens_array[$oc_item->insumo_id]['qtd'] = floatval($oc_item->qtd);
            }
            $qc_itens_array[$oc_item->insumo_id]['insumo_id'] = $oc_item->insumo_id;
            $qc_itens_array[$oc_item->insumo_id]['ids'][] = $oc_item->id;
        }

        // Cadastra os itens do quadro de concorrência
        foreach ($qc_itens_array as $qc_item_array) {
            $qc_item = QcItem::create([
                'quadro_de_concorrencia_id' => $model->id,
                'qtd' => $qc_item_array['qtd'],
                'insumo_id'=> $qc_item_array['insumo_id']
            ]);
            $qc_item->oc_itens()->sync($qc_item_array['ids']);
        }

        return $this->parserResult($model);
    }

    public function update(array $attributes, $id)
    {
        if (isset($attributes['qcFornecedoresMega'])) {
            foreach ($attributes['qcFornecedoresMega'] as $codigo_mega) {
                $fornecedor = Fornecedor::where('codigo_mega', $codigo_mega)->first();
                if (!$fornecedor) {
                    $fornecedor = ImportacaoRepository::fornecedores($codigo_mega, 'AGN_IN_CODIGO');
                }
                if ($fornecedor) {
                    if (!isset($attributes['qcFornecedores'])) {
                        $attributes['qcFornecedores'] = [];
                    }
                    $attributes['qcFornecedores'][] = ['fornecedor_id'=>$fornecedor->id,'user_id'=>$attributes['user_update_id']];
                }
            }
        }
        if (isset($attributes['qcFornecedores'])) {
            foreach ($attributes['qcFornecedores'] as $index => $obj) {
                if (!isset($attributes['qcFornecedores'][$index]['id'])) {
                    $attributes['qcFornecedores'][$index]['user_id'] = $attributes['user_update_id'];
                }
            }
        }
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $model = parent::update($attributes, $id);
        $this->skipPresenter($temporarySkipPresenter);

        $model = $this->updateRelations($model, $attributes);
        $model->save();

        if (isset($attributes['fechar_qc'])) {
            // Muda status do QC
            $model->qc_status_id = 3; // em aprovação
            $model->save();
            QcStatusLog::create([
                'quadro_de_concorrencia_id' => $model->id,
                'qc_status_id' => 2, // Fechado
                'user_id' => $attributes['user_update_id']
            ]);

            QcStatusLog::create([
                'quadro_de_concorrencia_id' => $model->id,
                'qc_status_id' => $model->qc_status_id, // Em aprovação
                'user_id' => $attributes['user_update_id']
            ]);
        }

        return $this->parserResult($model);
    }

    public function acao($acao, $id, $user_id)
    {
        $quadroDeConcorrencia = $this->findWithoutFail($id);
        $acao_executada = false;
        $erro = '';
        $mensagens = [];
        switch ($acao) {
            // Ação para Abrir concorrência (onde enviará e-mail aos fornecedores para acessarem e lançarem os preços)
        case 'inicia-concorrencia':
            // Altera o status do Q.C.
            $quadroDeConcorrencia->qc_status_id = 7;
            $quadroDeConcorrencia->save();
            QcStatusLog::create([
                'quadro_de_concorrencia_id' => $quadroDeConcorrencia->id,
                'qc_status_id' => $quadroDeConcorrencia->qc_status_id, // Em Concorrência
                'user_id' => $user_id
            ]);
            // Envia os avisos aos fornecedores sobre o Q.C.
            $qcFornecedores = $quadroDeConcorrencia->qcFornecedores;
            foreach ($qcFornecedores as $qcFornecedor) {
                // Verifica se o fornecedor tem usuário
                if ($qcFornecedor->fornecedor) {
                    $fornecedor = $qcFornecedor->fornecedor;
                    $this->notifyFornecedor($fornecedor, $quadroDeConcorrencia);
                }
            }
            $acao_executada = true;
            break;
        case 'cancelar':
            // Altera o status do Q.C.
            $quadroDeConcorrencia->qc_status_id = 6;
            $quadroDeConcorrencia->save();
            QcStatusLog::create([
                'quadro_de_concorrencia_id' => $quadroDeConcorrencia->id,
                'qc_status_id' => $quadroDeConcorrencia->qc_status_id, // Cancelado
                'user_id' => $user_id
            ]);
            $acao_executada = true;
            break;
        default:
            $erro = 'Ação inexistente!';
            break;
        }
        if (!$acao_executada) {
            return [false, $erro];
        }
        return [true, $mensagens];
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return QuadroDeConcorrencia::class;
    }

    public function quadrosPreenchiveisPeloUsuario(User $user)
    {
        $query = $this->model
            ->select([
                'quadro_de_concorrencias.id',
                'quadro_de_concorrencias.rodada_atual',
                'quadro_de_concorrencias.created_at',
                'quadro_de_concorrencias.updated_at',
                'users.name as usuario',
                'qc_status.nome as situacao',
                'qc_status.cor as situacao_cor',
                'quadro_de_concorrencias.qc_status_id'
            ])
            ->join('users', 'users.id', 'quadro_de_concorrencias.user_id')
            ->join('qc_status', 'qc_status.id', 'quadro_de_concorrencias.qc_status_id')
            ->where('quadro_de_concorrencias.qc_status_id', 7);

        if ($user->fornecedor) {
            $query = $query
                ->join('qc_fornecedor', 'qc_fornecedor.quadro_de_concorrencia_id', 'quadro_de_concorrencias.id')
                ->leftJoin('qc_item_qc_fornecedor', 'qc_item_qc_fornecedor.qc_fornecedor_id', 'qc_fornecedor.id')
                ->where('qc_fornecedor.fornecedor_id', $user->fornecedor->id)
                ->whereNull('qc_item_qc_fornecedor.id')
                ->whereNull('qc_fornecedor.desistencia_motivo_id')
                ->whereNull('qc_fornecedor.desistencia_texto')
                ->whereRaw('qc_fornecedor.rodada = quadro_de_concorrencias.rodada_atual');
        }

        return $query->get();
    }

    public function notifyFornecedor(Fornecedor $fornecedor, QuadroDeConcorrencia $quadroDeConcorrencia)
    {
        if ($user = $fornecedor->user) {
            //se tiver já envia uma notificação
            $user->notify(new IniciaConcorrencia($quadroDeConcorrencia));
        } else {
            // Se não tiver envia um e-mail para o fornecedor
            if (!strlen($fornecedor->email)) {
                $mensagens[] = 'O Fornecedor ' . $fornecedor->nome . ' não possui acesso e e-mail cadastrado,
                    por favor faça contato por telefone ' . $fornecedor->telefone;
                return $mensagens;
            } else {
                Mail::to($fornecedor->email)->send(new IniciaConcorrenciaFornecedorNaoUsuario($quadroDeConcorrencia, $fornecedor));
            }
        }
    }
}
