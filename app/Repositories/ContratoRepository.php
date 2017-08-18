<?php

namespace App\Repositories;

use App\Models\ContratoStatusLog;
use PDF;
use Exception;
use App\Mail\ContratoServicoFornecedorNaoUsuario;
use App\Models\Contrato;
use App\Models\ContratoItem;
use App\Models\Fornecedor;
use App\Models\Insumo;
use App\Models\Obra;
use App\Models\QcFornecedor;
use App\Notifications\NotificaFornecedorContratoServico;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WorkflowNotification;
use App\Models\ContratoStatus;
use App\Models\ContratoItemApropriacao;
use Illuminate\Support\Facades\DB;
use App\Models\ApropriacaoLigacao;

class ContratoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'contrato_status_id',
        'obra_id',
        'quadro_de_concorrencia_id',
        'fornecedor_id',
        'valor_total',
        'contrato_template_id',
        'arquivo',
        'campos_extras'
    ];

    /**
     * criar Criação de contrato
     * @param array $attributes
     * @return array [ success => Boolean, contratos => Array, erro => String]
     * @throws Exception
     */
    public static function criar(array $attributes)
    {
        // Busca o Fornecedor que vai ser gerado o contrato
        $qcFornecedor = QcFornecedor::where('qc_fornecedor.id', $attributes['qcFornecedor'])
            ->with(['itens' => function ($query) {
                $query->where('vencedor', '1');
            }])
            ->first();

        // Valida o valor final do frete
        if ($qcFornecedor->valor_frete > 0) {
            $soma_frete = 0;

            foreach ($attributes['valor_frete'] as $vl_frete) {
                $soma_frete += money_to_float($vl_frete);
            }
            if ($soma_frete != $qcFornecedor->getOriginal('valor_frete')) {
                return [
                    'success' => false,
                    'contratos' => [],
                    'erro' => 'Valor do Frete (' . $soma_frete . ') não confere com o passado R$ ' . $qcFornecedor->valor_frete
                ];
            }
        }

        // Valida se o fornecedor já está cadastrado no Mega
        if ($qcFornecedor->fornecedor->codigo_mega == '') {
            return [
                'success' => false,
                'contratos' => [],
                'erro' => 'O Fornecedor ' . $qcFornecedor->fornecedor->nome . ' não está cadastrado no Mega, por favor
                solicite a inclusão para que o contrato possa ser gerado'
            ];
        }
        $quadroDeConcorrencia = $qcFornecedor->quadroDeConcorrencia;
        // Monta os itens do contrato
        $primeiroItem = $qcFornecedor->itens()->where('vencedor', '1')->first();
        $obra_id = $primeiroItem->qcItem->oc_itens()->first()->obra_id;


        $contratoItens = [];
        $contratoCampos = [];

        $valores = [
            'material' => [],
            'faturamento_direto' => [],
            'locacao' => [],
        ];

        $fatorServico = 1;
        $fatorMaterial = 0;
        $fatorFatDireto = 0;
        $fatorLocacao = 0;

        if ($quadroDeConcorrencia->hasServico()) {
            if ($qcFornecedor->porcentagem_servico < 100) {
                $fatorServico = $qcFornecedor->porcentagem_servico / 100;
                $fatorMaterial = $qcFornecedor->porcentagem_material / 100;
                $fatorFatDireto = $qcFornecedor->porcentagem_faturamento_direto / 100;
                $fatorLocacao = $qcFornecedor->porcentagem_locacao / 100;

                // Se não marcou NF material, coloca o fator material como zero
                if (!$qcFornecedor->nf_material) {
                    $fatorServico += $fatorMaterial;
                    $fatorMaterial = 0;
                }
                // Se não marcou NF locacao, coloca o fator locacao como zero
                if (!$qcFornecedor->nf_locacao) {
                    $fatorServico += $fatorLocacao;
                    $fatorLocacao = 0;
                }
            }
        }

        foreach ($qcFornecedor->itens as $item) {
            $valor_item = $item->valor_total;
            $valor_item_unitario = $item->valor_unitario;

            $qcItem = $item->qcItem;
            $insumo = $qcItem->insumo;
            $obras = $qcItem->oc_itens()->select('obra_id')->groupBy('obra_id')->get();


            foreach ($obras as $obra) {
                $obra_id = $obra->obra_id;

                $ocItens = $qcItem->oc_itens()->where('obra_id', $obra_id)->get();

                $qtd = $qcItem->oc_itens()->where('obra_id', $obra_id)->sum('qtd');

                $valor_item = $valor_item_unitario * $qtd;

                if (!isset($contratoItens[$obra_id])) {
                    $contratoItens[$obra_id] = [];
                }
                if (!isset($contratoCampos[$obra_id]['valor_total_inicial'])) {
                    $contratoCampos[$obra_id]['valor_total_inicial'] = 0;
                }

                if (!isset($valores['material'][$obra_id])) {
                    $valores['material'][$obra_id] = [];
                }

                if (!isset($valores['faturamento_direto'][$obra_id])) {
                    $valores['faturamento_direto'][$obra_id] = [];
                }

                if (!isset($valores['locacao'][$obra_id])) {
                    $valores['locacao'][$obra_id] = [];
                }

                $contratoCampos[$obra_id]['valor_total_inicial'] += $valor_item;

                $tipo = head(explode(' ', $insumo->grupo->nome));
                if ($fatorServico < 1) {
                    if ($tipo == 'SERVIÇO') {
                        if ($fatorFatDireto > 0) {
                            $v_f = [
                                'item' => $item,
                                'qc_item' => $qcItem,
                                'oc_itens' => $ocItens,
                                'valor_item' => $valor_item * $fatorFatDireto,
                                'valor_unitario' => $valor_item_unitario,
                                'valor_total_item' => $valor_item,
                                'fator' => $fatorFatDireto,
                            ];

                            $valores['faturamento_direto'][$obra_id][] = $v_f;
                        }

                        if ($fatorMaterial > 0) {
                            $v_m = [
                                'item' => $item,
                                'qc_item' => $qcItem,
                                'oc_itens' => $ocItens,
                                'valor_item' => $valor_item * $fatorMaterial,
                                'valor_unitario' => $valor_item_unitario,
                                'valor_total_item' => $valor_item,
                                'fator' => $fatorMaterial,
                            ];

                            $valores['material'][$obra_id][] = $v_m;
                        }

                        if ($fatorLocacao > 0) {
                            $v_l = [
                                'item' => $item,
                                'qc_item' => $qcItem,
                                'oc_itens' => $ocItens,
                                'valor_item' => $valor_item * $fatorLocacao,
                                'valor_unitario' => $valor_item_unitario,
                                'valor_total_item' => $valor_item,
                                'fator' => $fatorLocacao,
                            ];
                            $valores['locacao'][$obra_id][] = $v_l;
                        }

                        $valor_item = $valor_item * $fatorServico;
                        $valor_item_unitario = $item->valor_unitario * $fatorServico;
                    }
                }

                $contrato_item = [
                    'insumo_id' => $insumo->id,
                    'qc_item_id' => $qcItem->id,
                    'qtd' => $qtd,
                    'valor_unitario' => $valor_item_unitario,
                    'valor_total' => $valor_item,
                    'aprovado' => 1
                ];

                $contrato_item['apropriacoes'] = $ocItens->map(function ($oc_item) {
                    $oc_item_arr = $oc_item->toArray();
                    $oc_item_arr['qtd'] = floatval($oc_item->getOriginal('qtd'));
                    return array_only($oc_item_arr, [
                        'codigo_insumo',
                        'grupo_id',
                        'subgrupo1_id',
                        'subgrupo2_id',
                        'subgrupo3_id',
                        'servico_id',
                        'insumo_id',
                        'qtd',
                    ]);
                })
                    ->toArray();

                $contratoItens[$obra_id][] = $contrato_item;
            }
        }

        $adicionar_novos_insumos = function ($tipo, $insumo_codigo) use ($valores, &$contratoItens) {
            $atual = $valores[$tipo];

            if (count($atual)) {
                foreach ($atual as $obraId => $valores_atuais) {
                    $valores_atuais = collect($valores_atuais);
                    $valor_total = $valores_atuais->sum('valor_item');
                    if ($valor_total > 0) {
                        $insumo = Insumo::where('codigo', $insumo_codigo)->first();

                        $contrato_item = [
                            'insumo_id' => $insumo->id,
                            'qc_item_id' => null,
                            'qtd' => $valor_total,
                            'valor_unitario' => 1,
                            'valor_total' => $valor_total,
                            'aprovado' => 1
                        ];

                        $contrato_item['apropriacoes'] = $valores_atuais->map(function ($valor) use ($insumo) {
                            return $valor['oc_itens']->map(function ($oc_item) use ($valor, $insumo) {
                                $oc_item_arr = $oc_item->toArray();
                                $porcentagem_apropriacao = money_to_float($oc_item->qtd) * $valor['valor_unitario'] / $valor['valor_total_item'];
                                $oc_item_arr['qtd'] = $valor['valor_item'] * $porcentagem_apropriacao;
                                $oc_item_arr['ligacao_id'] = $oc_item->insumo_id;
                                $oc_item_arr['insumo_id'] = $insumo->id;

                                return array_only($oc_item_arr, [
                                    'codigo_insumo',
                                    'grupo_id',
                                    'subgrupo1_id',
                                    'subgrupo2_id',
                                    'subgrupo3_id',
                                    'servico_id',
                                    'insumo_id',
                                    'ligacao_id',
                                    'qtd',
                                ]);
                            });
                        })
                            ->collapse()
                            ->toArray();

                        $contratoItens[$obraId][] = $contrato_item;
                    }
                }
            }
        };

        // Itens Material da Contratada
        $adicionar_novos_insumos('material', '34007');

        // Itens de Faturamento Direto
        $adicionar_novos_insumos('faturamento_direto', '30019');

        // Itens de Locação
        $adicionar_novos_insumos('locacao', '37367');

        $tipo_frete = 'CIF';
        $valor_frete = 0;

        $qcItensMateriais = $quadroDeConcorrencia->itensMateriais()->load('ordemDeCompraItens');

        if ($qcItensMateriais->isNotEmpty() && $qcFornecedor->tipo_frete == 'FOB') {
            foreach ($attributes['valor_frete'] as $obraID => $vl_frete) {
                $ocItens = $qcItensMateriais
                    ->pluck('ordemDeCompraItens')
                    ->collapse()
                    ->where('obra_id', $obraID);

                $ocItensCount = $ocItens->count();

                $valorFrete = !is_null($vl_frete) ? money_to_float($vl_frete) : 0;
                if ($valorFrete > 0) {
                    $insumo = Insumo::where('codigo', '28675')->first();
                    $valorApropriacao = $valorFrete / $ocItensCount;

                    $contrato_item = [
                        'insumo_id' => $insumo->id,
                        'qc_item_id' => null,
                        'qtd' => $valorFrete,
                        'valor_unitario' => 1,
                        'valor_total' => $valorFrete,
                        'aprovado' => 1
                    ];

                    $contrato_item['apropriacoes'] = $ocItens->map(function ($ocItem) use ($valorApropriacao, $insumo) {
                        $oc_item_arr = $ocItem->toArray();
                        $oc_item_arr['qtd'] = $valorApropriacao;
                        $oc_item_arr['insumo_id'] = $insumo->id;
                        $oc_item_arr['ligacao_id'] = $ocItem->insumo_id;

                        return array_only($oc_item_arr, [
                            'codigo_insumo',
                            'grupo_id',
                            'subgrupo1_id',
                            'subgrupo2_id',
                            'subgrupo3_id',
                            'servico_id',
                            'insumo_id',
                            'ligacao_id',
                            'qtd',
                        ]);
                    })
                        ->toArray();

                    $contratoItens[$obraID][] = $contrato_item;

                    $contratoCampos[$obraID]['tipo_frete'] = $qcFornecedor->tipo_frete;
                    $contratoCampos[$obraID]['valor_frete'] = $valorFrete;
                    $contratoCampos[$obraID]['valor_total_inicial'] += $valorFrete;
                }
            }
        }

        // Template
        $campos_extras = [];
        if (isset($attributes['CAMPO_EXTRA'])) {
            foreach ($attributes['CAMPO_EXTRA'] as $campo => $valor) {
                $campos_extras[$campo] = $valor;
            }
        }

        $campos_extras = json_encode($campos_extras);
        $contratos = [];
        DB::beginTransaction();
        try {
            foreach ($contratoCampos as $obraId => &$contratoArray) {
                $contratoArray['contrato_template_id'] = $attributes['contrato_template_id'];
                $contratoArray['campos_extras'] = $campos_extras;
                $contratoArray['obra_id'] = $obraId;
                $contratoArray['contrato_status_id'] = ContratoStatus::EM_APROVACAO;
                $contratoArray['fornecedor_id'] = $qcFornecedor->fornecedor_id;
                $contratoArray['quadro_de_concorrencia_id'] = $qcFornecedor->quadro_de_concorrencia_id;
                $contratoArray['valor_total_atual'] = $contratoArray['valor_total_inicial'];

                // Salva o contrato
                $contrato = Contrato::create($contratoArray);

                // Salva o primeiro status
                ContratoStatusLog::create([
                    'contrato_id' => $contrato->id,
                    'contrato_status_id' => $contrato->contrato_status_id,
                    'user_id' => auth()->id()
                ]);

                // Salva os itens do contrato
                foreach ($contratoItens[$obraId] as &$item) {
                    $item['contrato_id'] = $contrato->id;
                    $saved_item = ContratoItem::create($item);
                    if (isset($item['apropriacoes']) && count($item['apropriacoes'])) {
                        foreach ($item['apropriacoes'] as $apropriacao) {
                            $apropriacao_created = ContratoItemApropriacao::create(
                                array_merge(
                                    $apropriacao,
                                    ['contrato_item_id' => $saved_item->id]
                                )
                            );
                            if (isset($apropriacao['ligacao_id'])) {
                                ApropriacaoLigacao::create(
                                    array_merge(
                                        $apropriacao_created->toArray(),
                                        [
                                            'contrato_item_apropriacao_id' => $apropriacao_created->id,
                                            'insumo_id' => $apropriacao['ligacao_id']
                                        ]
                                    )
                                );
                            }
                        }
                    }
                }

                $contratos[] = Contrato::where('id', $contrato->id)
                    ->with('itens')
                    ->first();
            }


            $aprovadores = WorkflowAprovacaoRepository::usuariosDaAlcadaAtual($contrato);

            Notification::send($aprovadores, new WorkflowNotification($contrato));
        } catch (Exception $e) {
            throw $e;
            DB::rollback();
        }

        DB::commit();

        return [
            'success' => true,
            'contratos' => $contratos
        ];
    }

    /**
     * notifyFornecedor
     * @param $id
     * @return array
     */
    public static function notifyFornecedor($id)
    {
        $contrato = Contrato::find($id);
        if (!$contrato) {
            return [
                'success' => false,
                'messages' => [
                    'O contrato não foi encontrado!'
                ]
            ];
        }

        $arquivo = self::geraImpressao($id);
        $fornecedor = $contrato->fornecedor;
        $mensagens = [];

        if ($user = $fornecedor->user) {
            //se tiver já envia uma notificação
            $user->notify(new NotificaFornecedorContratoServico($contrato, $arquivo));
            return [
                'success' => true
            ];
        } else {
            // Se não tiver envia um e-mail para o fornecedor
            if (!strlen($fornecedor->email)) {
                $mensagens[] = 'O Fornecedor ' . $fornecedor->nome . ' não possui acesso e e-mail cadastrado,
                    <a href="' . Storage::url($arquivo) . '" target="_blank">Imprima o contrato</a> e faça o fornecedor assinar.
                    O telefone do fornecedor é ' . $fornecedor->telefone;
                return [
                    'success' => true,
                    'messages' => $mensagens
                ];
            } else {
                Mail::to($fornecedor->email)->send(new ContratoServicoFornecedorNaoUsuario($contrato, $arquivo));
                return [
                    'success' => true
                ];
            }
        }
    }

    /**
     * Gera a impressão do Contrato aplicando as variáveis
     * Retorna o local do arquivo gerado
     *
     * @param int $id
     *
     * @return string
     */
    public static function geraImpressao($id)
    {
        $contrato = Contrato::find($id);
        if (!$contrato) {
            return null;
        }

        $template = $contrato->contratoTemplate;

        $templateRenderizado = $template->template;

        // Tenta aplicar variáveis de Obra
        foreach (Obra::$campos as $campo) {
            $templateRenderizado = str_replace('[' . strtoupper($campo) . '_OBRA]', $contrato->obra->$campo, $templateRenderizado);
        }

        // Tenta aplicar variáveis de Fornecedor
        foreach (Fornecedor::$campos as $campo) {
            $templateRenderizado = str_replace('[' . strtoupper($campo) . '_FORNECEDOR]', $contrato->fornecedor->$campo, $templateRenderizado);
        }

        // Tenta aplicar variáveis de Contrato

        // [TABELA_ITENS_CONTRATO]
        $tabela_itens = '<table>
            <thead>
                <tr>
                    <th align="left">Descrição</th>
                    <th width="10%" align="right">Qtd.</th>
                    <th width="20%" align="right">Valor Unitário</th>
                    <th width="20%" align="right">Valor Total</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($contrato->itens as $item) {
            $tabela_itens .= '<tr>';
            $tabela_itens .= '<td>' . $item->insumo->nome . '</td>';
            $tabela_itens .= '<td align="right">' . float_to_money($item->qtd, '') . ' ' . $item->insumo->unidade_sigla . '</td>';
            $tabela_itens .= '<td align="right">' . float_to_money($item->valor_unitario) . '</td>';
            $tabela_itens .= '<td align="right">' . float_to_money($item->valor_total) . '</td>';

            $tabela_itens .= '</tr>';
        }

        $tabela_itens .= '</tbody></table>';

        $contratoCampos = [
            'valor_total' => $contrato->valor_total,
            'tabela_itens' => $tabela_itens

        ];
        foreach ($contratoCampos as $campo => $valor) {
            $templateRenderizado = str_replace('[' . strtoupper($campo) . '_CONTRATO]', $valor, $templateRenderizado);
        }

        // Campos do Q.C.

        // [COMPOSICAO_DO_PRECO]
        $composicao_preco = '';
        $tipo_frete = 'Incluso';
        $valor_frete = '';
        $qcFornecedor = $item->qcItem->ofertas()->where('vencedor',1)->first()->qcFornecedor;

        if($qcFornecedor){
            $composicao_preco .='<h3>Composição do Preço</h3>';
            if($qcFornecedor->porcentagem_servico>0){
                $composicao_preco .='<h5>Serviço: '.$qcFornecedor->porcentagem_servico.'%</h5>';
            }
            if($qcFornecedor->porcentagem_material>0){
                $composicao_preco .='<h5>Material: '.$qcFornecedor->porcentagem_material.'%</h5>';
            }
            if($qcFornecedor->porcentagem_locacao>0){
                $composicao_preco .='<h5>Locação: '.$qcFornecedor->porcentagem_locacao.'%</h5>';
            }
            if($qcFornecedor->nf_material &&
                (
                    !$qcFornecedor->porcentagem_servico
                    &&
                    !$qcFornecedor->porcentagem_material
                    && !$qcFornecedor->porcentagem_locacao
                )
            ){
                $composicao_preco .='<h5>100% MATERIAL</h5>';
            }
            $tipo_frete = $qcFornecedor->tipo_frete?$qcFornecedor->tipo_frete:'Incluso';
            $valor_frete = 'R$ '.$qcFornecedor->valor_frete;
        }

        $qcCampos = [
            'composicao_do_preco' => $composicao_preco,
            'frete_tipo' => $tipo_frete,
            'frete_valor' => $valor_frete,

        ];
        foreach ($qcCampos as $campo => $valor) {
            $templateRenderizado = str_replace('[' . strtoupper($campo) . ']', $valor, $templateRenderizado);
        }


        // Tenta aplicar variáveis do Template (dinâmicas)
        if (strlen($contrato->campos_extras)) {
            $variaveis_dinamicas = json_decode($contrato->campos_extras);
            foreach ($variaveis_dinamicas as $campo => $valor) {
                $templateRenderizado = str_replace('[' . strtoupper($campo) . ']', $valor, $templateRenderizado);
            }
        }
        if (is_file(base_path() . '/storage/app/public/contratos/contrato_' . $contrato->id . '.pdf')) {
            unlink(base_path() . '/storage/app/public/contratos/contrato_' . $contrato->id . '.pdf');
        }
        PDF::loadHTML(utf8_decode($templateRenderizado))->setPaper('a4')
            ->setOrientation('portrait')
            ->save(base_path() . '/storage/app/public/contratos/contrato_' . $contrato->id . '.pdf');
        return 'contratos/contrato_' . $contrato->id . '.pdf';
    }

    /**
     * Configure the Model
     *
     * @return string
     **/
    public function model()
    {
        return Contrato::class;
    }
}
