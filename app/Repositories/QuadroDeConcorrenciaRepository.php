<?php

namespace App\Repositories;

use App\Mail\IniciaConcorrenciaFornecedorNaoUsuario;
use App\Models\CatalogoContrato;
use App\Models\CatalogoContratoInsumo;
use App\Models\CompradorInsumo;
use App\Models\ConfiguracaoEstatica;
use App\Models\ContratoTemplate;
use App\Models\Fornecedor;
use App\Models\OrdemDeCompraItem;
use App\Models\QcFornecedor;
use App\Models\QcItem;
use App\Models\QcItemQcFornecedor;
use App\Models\QcStatusLog;
use App\Models\QuadroDeConcorrencia;
use App\Notifications\IniciaConcorrencia;
use App\Notifications\QCConcorrenciaNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use InfyOm\Generator\Common\BaseRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Contrato;
use App\Models\QcStatus;
use Laracasts\Flash\Flash;

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

    public static function verificaQCAutomatico()
    {
        // Varre itens aprovados existe algum catalogo_contrato vigente
        $itens_aprovados_com_acordos = OrdemDeCompraItem::query()
            ->select([
                'ordem_de_compra_itens.insumo_id',
                DB::raw('GROUP_CONCAT(ordem_de_compra_itens.id) oc_itens_ids'),
                DB::raw('SUM(ordem_de_compra_itens.qtd) qtd'),
                'catalogo_contrato_insumos.id as catalogo_contrato_insumo_id',
                DB::raw('catalogo_contratos.id as cat_contrato_id')
            ])
            ->join('ordem_de_compras', 'ordem_de_compras.id', 'ordem_de_compra_itens.ordem_de_compra_id')
            ->join('obras', 'obras.id', 'ordem_de_compra_itens.obra_id')
            ->join('insumos', 'insumos.id', 'ordem_de_compra_itens.insumo_id')
            ->join('catalogo_contrato_insumos', 'catalogo_contrato_insumos.insumo_id', 'ordem_de_compra_itens.insumo_id')
            ->join('catalogo_contratos', 'catalogo_contratos.id', 'catalogo_contrato_insumos.catalogo_contrato_id')
            ->join('catalogo_contrato_obra', function($join){
                $join->on('catalogo_contrato_obra.obra_id','=','obras.id');
                $join->on('catalogo_contrato_obra.catalogo_contrato_id','=','catalogo_contratos.id');
            })
            ->where('ordem_de_compras.aprovado', '1')
            ->where('catalogo_contratos.catalogo_contrato_status_id',3) // Acordo Ativo
            ->where('catalogo_contrato_obra.catalogo_contrato_status_id',3) // Obra Acordo Ativa
            ->where('catalogo_contrato_insumos.periodo_inicio','<=',date('Y-m-d'))
            ->where('catalogo_contrato_insumos.periodo_termino','>=',date('Y-m-d'))
            ->whereNotExists(function ($query) {
                $query->select(DB::raw('1'))
                    ->from('oc_item_qc_item')
                    ->where('ordem_de_compra_item_id', DB::raw('ordem_de_compra_itens.id'));
            })
//            ->where('catalogo_contratos.periodo_inicio', '<=', date('Y-m-d')) // O CAMPO FOI REMOVIDO
//            ->where('catalogo_contratos.periodo_termino', '>=', date('Y-m-d')) // O CAMPO FOI REMOVIDO
            ->groupBy('ordem_de_compra_itens.insumo_id', 'catalogo_contrato_insumos.id')
            ->get();

        if (!$itens_aprovados_com_acordos->count()) {
            return false;
        }

        // Se existe verifica se as quantidades requisitadas estão dentro dos requisitos
        $gerar_qc_itens = [];
        $fornecedores_ids = [];
        $item_valores = [];
        foreach ($itens_aprovados_com_acordos as $item) {
            $item_acordo = CatalogoContratoInsumo::find($item->catalogo_contrato_insumo_id);
            // Verifica se a qtd mínima é atendidida
            if ($item->getOriginal('qtd') >= $item_acordo->getOriginal('pedido_minimo')) {
                // Verifica se a qtd multipla é atendida
                $multiplo_de = floatval($item_acordo->getOriginal('pedido_multiplo_de'));
                $multiplo_de = $multiplo_de==0?1:$multiplo_de; // se tem acordo, o múltiplo deve ser diferente de zero
                if (($item->getOriginal('qtd') % $multiplo_de) == 0) {
                    $gerar_qc_itens[$item->insumo_id] = [
                        'ids' => explode(',', $item->oc_itens_ids),
                        'insumo_id' => $item->insumo_id,
                        'qtd' => $item->getOriginal('qtd'),
                        'fornecedor_id' => $item_acordo->catalogo->fornecedor_id,
                        'valor' => $item_acordo->getOriginal('valor_unitario'),
                        'cat_contrato_id' => $item->cat_contrato_id
                    ];
                    $item_valores[$item->insumo_id][$item_acordo->catalogo->fornecedor_id] = $item_acordo->getOriginal('valor_unitario');
                    $fornecedores_ids[$item->insumo_id][$item_acordo->catalogo->fornecedor_id] = $item_acordo->catalogo->fornecedor_id;
                }
            }
        }

        // Se estão gera um QC automatico com os itens que são atendidos por aquele catálogo
        if (count($gerar_qc_itens))
        {
            // Antes de gerar os QCs fazer a análise de quantos QCs terão
            $QCs_por_forncedor = [];
            foreach ($gerar_qc_itens as $qc_item_array)
            {
                // Verifica se só tem um fornecedor pra este insumo
                if(count($fornecedores_ids[$qc_item_array['insumo_id']])===1){
                    if(!isset($QCs_por_forncedor[$qc_item_array['fornecedor_id']])){
                        $QCs_por_forncedor[$qc_item_array['fornecedor_id']] = [];
                    }
                    // Se tiver já salva o item pra gerar neste fornecedor
                    $QCs_por_forncedor[$qc_item_array['fornecedor_id']][] = $qc_item_array;
                }else{
                    // Senão joga numa chave chamada Misto
                    if(!isset($QCs_por_forncedor['misto'])){
                        $QCs_por_forncedor['misto'] = [];
                    }
                    // Adiciona o item pra gerar no misto
                    $QCs_por_forncedor['misto'][] = $qc_item_array;
                }

            }

            $contratoTemplateContrato = ContratoTemplate::where('tipo', 'M')->first(); // Busca template do tipo Material (só tem um no sistema)
            foreach ($QCs_por_forncedor as $QC)
            {
                $quadroDeConcorrencia = QuadroDeConcorrencia::create([
                    'user_id' => null,
                    'qc_status_id' => 1,
                    'obrigacoes_fornecedor' => ConfiguracaoEstatica::find(1)->valor,
                    'obrigacoes_bild' => ConfiguracaoEstatica::find(2)->valor,
                    'rodada_atual' => 1,
                    'contrato_template_id'=> $contratoTemplateContrato->id
                ]);

                foreach ($QC as $qc_item_array) {

                    // Cadastra os itens do quadro de concorrência

                    $qc_item = QcItem::create([
                        'quadro_de_concorrencia_id' => $quadroDeConcorrencia->id,
                        'qtd' => $qc_item_array['qtd'],
                        'insumo_id' => $qc_item_array['insumo_id']
                    ]);
                    $qc_item->oc_itens()->sync($qc_item_array['ids']);


                    // Salva o primeiro status log
                    QcStatusLog::firstOrCreate([
                        'quadro_de_concorrencia_id' => $quadroDeConcorrencia->id,
                        'qc_status_id' => $quadroDeConcorrencia->qc_status_id,
                    ]);
                    $quadroDeConcorrencia->qc_status_id = 2;
                    QcStatusLog::firstOrCreate([
                        'quadro_de_concorrencia_id' => $quadroDeConcorrencia->id,
                        'qc_status_id' => $quadroDeConcorrencia->qc_status_id,
                    ]);

                    // Amarra os fornecedores no QC
                    foreach ($fornecedores_ids[$qc_item_array['insumo_id']] as $fornecedor_id) {
                        $catalogoContrato = CatalogoContrato::find($qc_item_array['cat_contrato_id']);

                        $qc_fornecedor = QcFornecedor::firstOrCreate([
                            'quadro_de_concorrencia_id' => $quadroDeConcorrencia->id,
                            'fornecedor_id' => $fornecedor_id,
                            'rodada' => $quadroDeConcorrencia->rodada_atual,
                            'nf_material' => 1,
                            'campos_extras_contrato' => $catalogoContrato->campos_extras_contrato
                        ]);
                    }


                    $quadroDeConcorrencia->qc_status_id = 7;
                    QcStatusLog::firstOrCreate([
                        'quadro_de_concorrencia_id' => $quadroDeConcorrencia->id,
                        'qc_status_id' => $quadroDeConcorrencia->qc_status_id,
                    ]);
                    $quadroDeConcorrencia->save();

                    // Define se é apenas um fornecedor como vencedor, caso contrário, deixa vazio
                    $vencedor = $quadroDeConcorrencia->qcFornecedores()->count() == 1 ? 1 : 0;
                }
                // Após gerado, já lança os valores daqueles fornecedores pelo firmado no acordo
                foreach ($quadroDeConcorrencia->itens as $item) {
                    $acordo_item = $gerar_qc_itens[$item->insumo_id];
                    foreach ($item_valores[$item->insumo_id] as $fornecedorID => $valorItem) {
                        $qc_fornecedor = QcFornecedor::where('quadro_de_concorrencia_id', $quadroDeConcorrencia->id)
                            ->where('fornecedor_id', $fornecedorID)
                            ->first();
                        QcItemQcFornecedor::create([
                            'qc_item_id' => $item->id,
                            'qc_fornecedor_id' => $qc_fornecedor->id,
                            'qtd' => $item->getOriginal('qtd'),
                            'valor_unitario' => $valorItem,
                            'valor_total' => ($valorItem * $item->getOriginal('qtd')),
                            'vencedor' => $vencedor,
                            'data_decisao' =>  date('Y-m-d H:i:s')
                        ]);
                    }
                }
                // Se existe apenas um fornecedor
                if($quadroDeConcorrencia->qcFornecedores()->count() == 1){
                    // Finaliza o QC

                    $quadroDeConcorrencia->qc_status_id = 8;
                    QcStatusLog::create([
                        'quadro_de_concorrencia_id' => $quadroDeConcorrencia->id,
                        'qc_status_id' => $quadroDeConcorrencia->qc_status_id,
                    ]);
                    $quadroDeConcorrencia->save();

                    // Já gera os contratos
                    $contratoTemplateContrato = \App\Models\ContratoTemplate::where('tipo','M')->first();
                    $gerarContrato = [
                        'qcFornecedor' => $qc_fornecedor->id,
                        'contrato_template_id'=> $contratoTemplateContrato->id,
                    ];

                    // Campos extras
                    if($qc_fornecedor->campos_extras_contrato){
                        $campos_extras_contrato = json_decode($qc_fornecedor->campos_extras_contrato);
                        foreach ($campos_extras_contrato as $key => $value){
                            $gerarContrato['CAMPO_EXTRA'][$key] = $value;
                        }
                    }

                    ContratoRepository::criar($gerarContrato);

                }else{
                    $avaliadores = collect();
                    // Notifica os usuários que cuidam de QC para escolherem um vencedor
                    $compradores_avaliadores = CompradorInsumo::where('insumo_id', $qc_item_array['insumo_id'])->get();
                    if(count($compradores_avaliadores)){
                        foreach ($compradores_avaliadores as $compradorInsumo){
                            $avaliadores->push($compradorInsumo->user);
                        }
                    }else{
                        $avaliadores = User::where('active','1')
                            ->join('role_user','role_user.user_id','users.id')
                            ->join('roles','roles.id','role_user.role_id')
                            ->where('roles.name','Suprimentos')
                        ->get();
                        if(!$avaliadores->count()){
                            $avaliadores = User::where('active','1')
                                ->join('role_user','role_user.user_id','users.id')
                                ->join('roles','roles.id','role_user.role_id')
                                ->where('roles.name','Administrador')
                                ->get();
                        }
                    }
                    Notification::send($avaliadores, new QCConcorrenciaNotification($quadroDeConcorrencia));
                }
            }
        }
    }

    public function create(array $attributes)
    {
        $itens = $attributes['itens'];

        $attributes = [
            'user_id' => $attributes['user_id'],
            'qc_status_id' => QcStatus::ABERTO,
            'obrigacoes_fornecedor' => ConfiguracaoEstatica::find(1)->valor,
            'obrigacoes_bild' => ConfiguracaoEstatica::find(2)->valor,
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

        $this->adicionaItens($itens, $model);

        return $this->parserResult($model);
    }

    public function adicionaItens($itens, QuadroDeConcorrencia $quadroDeConcorrencia)
    {
        // Busca e agrupa intens conforme o tipo
        $oc_itens = $itens instanceof Collection
            ? $itens
            : OrdemDeCompraItem::whereIn('id', $itens)->get();

        $qc_itens_array = [];
        foreach ($oc_itens as $oc_item) {
            if (isset($qc_itens_array[$oc_item->insumo_id])) {
                $qc_itens_array[$oc_item->insumo_id]['qtd'] += floatval($oc_item->getOriginal('qtd'));
            } else {
                $qc_itens_array[$oc_item->insumo_id]['qtd'] = floatval($oc_item->getOriginal('qtd'));
            }
            $qc_itens_array[$oc_item->insumo_id]['insumo_id'] = $oc_item->insumo_id;
            $qc_itens_array[$oc_item->insumo_id]['ids'][] = $oc_item->id;
        }

        // Cadastra os itens do quadro de concorrência
        foreach ($qc_itens_array as $qc_item_array) {
            $qc_item = QcItem::create([
                'quadro_de_concorrencia_id' => $quadroDeConcorrencia->id,
                'qtd' => $qc_item_array['qtd'],
                'insumo_id' => $qc_item_array['insumo_id']
            ]);
            $qc_item->oc_itens()->sync($qc_item_array['ids']);
        }
    }

    public function update(array $attributes, $id)
    {
        $qc = $this->findWithoutFail($id);

        if (isset($attributes['itens'])) {
            $model = $this->findWithoutFail($id);
            $this->adicionaItens($attributes['itens'], $model);
            return $model;
        }
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
                    $attributes['qcFornecedores'][] = [
                        'fornecedor_id' => $fornecedor->id,
                        'user_id' => $attributes['user_update_id']
                    ];
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

        if(isset($attributes['qcFornecedores'])){
//            dd($attributes['qcFornecedores']);
            foreach ($attributes['qcFornecedores'] as $qcFornecedor){
//                echo 'QCFORNECEDOR';
//                var_dump($qcFornecedor);
                $qcF = QcFornecedor::firstOrCreate([
                    'fornecedor_id'=> $qcFornecedor['fornecedor_id'],
                    'rodada'=>$qc->rodada_atual,
                    'quadro_de_concorrencia_id'=>$qc->id
                    ],[
                    'user_id' => $attributes['user_update_id']
                ]);
//                echo 'QCF';
//                var_dump($qcF);
            }
            unset($attributes['qcFornecedores']);
        }
//        dd($attributes);

        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $model = parent::update($attributes, $id);
        $this->skipPresenter($temporarySkipPresenter);

        $model = $this->updateRelations($model, $attributes);
        $model->save();

        if (isset($attributes['fechar_qc'])) {
            // Verifica se existe pelo menos um item e um fornecedor
            if(!$model->qcFornecedores()->count() || !$model->itens()->count()){
                Flash::error('Escolha fornecedores / itens para o Q.C.');
                return false;
            }

            // Muda status do QC
            $model->qc_status_id = QcStatus::EM_APROVACAO;
            $model->save();
            QcStatusLog::create([
                'quadro_de_concorrencia_id' => $model->id,
                'qc_status_id' => QcStatus::FECHADO,
                'user_id' => $attributes['user_update_id']
            ]);

            QcStatusLog::create([
                'quadro_de_concorrencia_id' => $model->id,
                'qc_status_id' => QcStatus::EM_APROVACAO,
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
                $quadroDeConcorrencia->qc_status_id = QcStatus::EM_CONCORRENCIA;
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
                $quadroDeConcorrencia->qc_status_id = QcStatus::CANCELADO;
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

    /**
     * Aditivar Contratos
     * Função onde recebe os itens da Ordem de Compra após aprovada e já cria Quadros de Concorrência com o Fornecedor
     * do contrato apontado como destino, aguardando o lançamento de valores
     * @param Collection $itens
     * @param $user_id
     * @return \Illuminate\Support\Collection
     */
    public function aditivarContratos(Collection $itens, $user_id)
    {
        $contratos = $itens->groupBy('sugestao_contrato_id');

        $qcs = $contratos->map(function ($itens, $contrato_id) use ($user_id) {
            $contrato = Contrato::find($contrato_id);

            $qc = $this->model->create([
                'user_id' => $user_id,
                'qc_status_id' => QcStatus::ABERTO,
                'obrigacoes_fornecedor' => ConfiguracaoEstatica::find(1)->valor,
                'obrigacoes_bild' => ConfiguracaoEstatica::find(2)->valor,
                'rodada_atual' => 1
            ]);

            QcStatusLog::create([
                'quadro_de_concorrencia_id' => $qc->id,
                'qc_status_id' => $qc->qc_status_id,
                'user_id' => $qc->user_id
            ]);

            QcFornecedor::create([
                'quadro_de_concorrencia_id' => $qc->id,
                'fornecedor_id' => $contrato->fornecedor_id,
                'rodada' => 1,
                'user_id' => $user_id
            ]);

            $this->adicionaItens($itens, $qc);

            return $qc;
        });

        return $qcs;
    }
}
