<?php

namespace App\Repositories;

use PDF;
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
     * Configure the Model
     **/
    public function model()
    {
        return Contrato::class;
    }

    public static function criar(array $attributes)
    {
        // Busca o Fornecedor que vai ser gerado o contrato
        $qcFornecedor = QcFornecedor::where('qc_fornecedor.id',$attributes['qcFornecedor'])
            ->with(['itens'=> function($query){
                $query->where('vencedor','1');
            }])
            ->first();
        // Valida o valor final do frete
        if($qcFornecedor->valor_frete > 0){
            $soma_frete = 0;

            foreach ($attributes['valor_frete'] as $vl_frete){
                $soma_frete += money_to_float($vl_frete);
            }
            if($soma_frete != $qcFornecedor->getOriginal('valor_frete')){
                return [
                    'success' => false,
                    'contratos'=>[],
                    'erro'=>'Valor do Frete ('.$soma_frete.') não confere com o passado R$ '. $qcFornecedor->valor_frete
                ];
            }
        }

        // Valida se o fornecedor já está cadastrado no Mega
        if($qcFornecedor->fornecedor->codigo_mega == ''){

                return [
                    'success' => false,
                    'contratos'=>[],
                    'erro'=>'O Fornecedor '. $qcFornecedor->fornecedor->nome.' não está cadastrado no Mega, por favor
                    solicite a inclusão para que o contrato possa ser gerado'
                ];

        }
        $quadroDeConcorrencia = $qcFornecedor->quadroDeConcorrencia;
        // Monta os itens do contrato
        $primeiroItem = $qcFornecedor->itens()->where('vencedor','1')->first();
        $obra_id = $primeiroItem->qcItem->oc_itens()->first()->obra_id;


        $contratoItens = [];
        $contratoCampos = [];

        $valorMaterial = [];
        $valorFaturamentoDireto = [];
        $valorLocacao = [];

        $fatorServico = 1;
        $fatorMaterial = 0;
        $fatorFatDireto = 0;
        $fatorLocacao = 0;

        if($quadroDeConcorrencia->hasServico()){
            if($qcFornecedor->porcentagem_servico < 100){
                $fatorServico = $qcFornecedor->porcentagem_servico / 100;
                $fatorMaterial = $qcFornecedor->porcentagem_material / 100;
                $fatorFatDireto = $qcFornecedor->porcentagem_faturamento_direto / 100;
                $fatorLocacao = $qcFornecedor->porcentagem_locacao / 100;

                // Se não marcou NF material, coloca o fator material como zero
                if(!$qcFornecedor->nf_material){
                    $fatorServico += $fatorMaterial;
                    $fatorMaterial = 0;
                }
                // Se não marcou NF locacao, coloca o fator locacao como zero
                if(!$qcFornecedor->nf_locacao){
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

                $qtd = $qcItem->oc_itens()->where('obra_id', $obra_id)->sum('qtd');
                $valor_item = $valor_item_unitario * $qtd;

                if (!isset($contratoItens[$obra_id])) {
                    $contratoItens[$obra_id] = [];
                }
                if (!isset($contratoCampos[$obra_id]['valor_total'])) {
                    $contratoCampos[$obra_id]['valor_total'] = 0;
                }
                if (!isset($valorMaterial[$obra_id])) {
                    $valorMaterial[$obra_id] = 0;
                }
                if (!isset($valorFaturamentoDireto[$obra_id])) {
                    $valorFaturamentoDireto[$obra_id] = 0;
                }
                if (!isset($valorLocacao[$obra_id])) {
                    $valorLocacao[$obra_id] = 0;
                }

                $contratoCampos[$obra_id]['valor_total'] += $valor_item;
                $tipo = explode(' ', $insumo->grupo->nome);
                if ($fatorServico < 1) {
                    if ($tipo[0] == 'SERVIÇO') {
                        if($fatorFatDireto > 0){
                            $valorFaturamentoDireto[$obra_id] += $valor_item * $fatorFatDireto;
                        }
                        if($fatorMaterial > 0){
                            $valorMaterial[$obra_id] += $valor_item * $fatorMaterial;
                        }
                        if($fatorLocacao > 0){
                            $valorLocacao[$obra_id] += $valor_item * $fatorLocacao;
                        }
                        $valor_item = $valor_item * $fatorServico;
                        $valor_item_unitario = $item->valor_unitario * $fatorServico;
                    }
                }

                $contratoItens[$obra_id][] = [
                    'insumo_id' => $insumo->id,
                    'qc_item_id' => $qcItem->id,
                    'qtd' => $qtd,
                    'valor_unitario' => $valor_item_unitario,
                    'valor_total' => $valor_item,
                    'aprovado' => 1
                ];

            }
        }
        // Itens Material da Contratada
        if(count($valorMaterial)){
            foreach($valorMaterial as $obraId => $vl){
                if($vl>0){
                    $insumo = Insumo::where('codigo','34007')->first();
                    $contratoItens[$obraId][] = [
                        'insumo_id'         => $insumo->id,
                        'qc_item_id'        => null,
                        'qtd'               => $vl,
                        'valor_unitario'    => 1,
                        'valor_total'       => $vl,
                        'aprovado'          => 1
                    ];
                }
            }
        }
        // Itens de Faturamento Direto
        if(count($valorFaturamentoDireto)){
            foreach ($valorFaturamentoDireto as $obraId => $fd){
                if($fd>0){
                    $insumo = Insumo::where('codigo','30019')->first();
                    $contratoItens[$obraId][] = [
                        'insumo_id'         => $insumo->id,
                        'qc_item_id'        => null,
                        'qtd'               => $fd,
                        'valor_unitario'    => 1,
                        'valor_total'       => $fd,
                        'aprovado'          => 1
                    ];
                }
            }
        }
        // Itens de Locação
        if(count($valorLocacao)){
            foreach ($valorLocacao as $obraId => $valorLoc){
                if($valorLoc>0){
                    $insumo = Insumo::where('codigo','37367')->first(); // trocado temporariamente para 37367 pois o 37674 não existe
                    $contratoItens[$obraId][] = [
                        'insumo_id'         => $insumo->id,
                        'qc_item_id'        => null,
                        'qtd'               => $valorLoc,
                        'valor_unitario'    => 1,
                        'valor_total'       => $valorLoc,
                        'aprovado'          => 1
                    ];
                }
            }
        }

        $tipo_frete = 'CIF';
        $valor_frete = 0;
        if($quadroDeConcorrencia->hasMaterial() && $qcFornecedor->tipo_frete == 'FOB'){
            foreach ($attributes['valor_frete'] as $obraID => $vl_frete){

                $valorFrete = !is_null($vl_frete)?money_to_float($vl_frete):0;
                if($valorFrete>0){
                    $insumo = Insumo::where('codigo','28675')->first();
                    $contratoItens[$obraID][] = [
                        'insumo_id'         => $insumo->id,
                        'qc_item_id'        => null,
                        'qtd'               => $valorFrete,
                        'valor_unitario'    => 1,
                        'valor_total'       => $valorFrete,
                        'aprovado'          => 1
                    ];

                    $contratoCampos[$obraID]['tipo_frete'] = $qcFornecedor->tipo_frete;
                    $contratoCampos[$obraID]['valor_frete'] = $valorFrete;
                    $contratoCampos[$obraID]['valor_total'] += $valorFrete;
                }

            }
        }

        // Template
        $campos_extras = [];
        if(isset($attributes['CAMPO_EXTRA'])){
            foreach ($attributes['CAMPO_EXTRA'] as $campo => $valor){
                $campos_extras[$campo] = $valor;
            }
        }

        $campos_extras = json_encode($campos_extras);

        $contratos = [];

        foreach ($contratoCampos as $obraId => &$contratoArray){
            $contratoArray['contrato_template_id'] = $attributes['contrato_template_id'];
            $contratoArray['campos_extras'] = $campos_extras;
            $contratoArray['obra_id'] = $obraId;
            $contratoArray['contrato_status_id'] = ContratoStatus::EM_APROVACAO;
            $contratoArray['fornecedor_id'] = $qcFornecedor->fornecedor_id;
            $contratoArray['quadro_de_concorrencia_id'] = $qcFornecedor->quadro_de_concorrencia_id;
            $contratoArray['valor_total'] = number_format($contratoArray['valor_total'],2,'.','');

            // Salva o contrato
            $contrato = Contrato::create($contratoArray);
            // Salva os itens do contrato
            foreach ($contratoItens[$obraId] as &$item){
                $item['contrato_id'] = $contrato->id;
                ContratoItem::create($item);
            }
//            $contratos[] = $contrato;
            $contratos[] = Contrato::where('id',$contrato->id)->with('itens')->first();
        }

        $aprovadores = WorkflowAprovacaoRepository::usuariosDaAlcadaAtual($contrato);

        Notification::send($aprovadores, new WorkflowNotification($contrato));

        return [
            'success' => true,
            'contratos'=> $contratos
        ];
    }

    /**
     * Gera a impressão do Contrato aplicando as variáveis
     * Retorna o local do arquivo gerado
     * @param $id
     * @return string
     */
    public static function geraImpressao($id){
        $contrato = Contrato::find($id);
        if(!$contrato){
            return null;
        }

        $template = $contrato->contratoTemplate;

        $templateRenderizado = $template->template;

        // Tenta aplicar variáveis de Obra
        foreach (Obra::$campos as $campo){
            $templateRenderizado = str_replace('['.strtoupper($campo).'_OBRA]', $contrato->obra->$campo,$templateRenderizado );
        }

        // Tenta aplicar variáveis de Fornecedor
        foreach (Fornecedor::$campos as $campo){
            $templateRenderizado = str_replace('['.strtoupper($campo).'_FORNECEDOR]', $contrato->fornecedor->$campo,$templateRenderizado );
        }

        // Tenta aplicar variáveis de Contrato

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
        foreach ($contrato->itens as $item){
            $tabela_itens .= '<tr>';
            $tabela_itens .= '<td>'.$item->insumo->nome.'</td>';
            $tabela_itens .= '<td align="right">'.float_to_money($item->qtd,'').' '. $item->insumo->unidade_sigla.'</td>';
            $tabela_itens .= '<td align="right">'.float_to_money($item->valor_unitario).'</td>';
            $tabela_itens .= '<td align="right">'.float_to_money($item->valor_total).'</td>';

            $tabela_itens .= '</tr>';
        }

        $tabela_itens .= '</tbody></table>';

        $contratoCampos = [
            'valor_total' => $contrato->valor_total,
            'tabela_itens' => $tabela_itens

        ];
        foreach ($contratoCampos as $campo => $valor){
            $templateRenderizado = str_replace('['.strtoupper($campo).'_CONTRATO]', $valor,$templateRenderizado );
        }
        // Tenta aplicar variáveis do Template (dinâmicas)
        if(strlen($contrato->campos_extras)){
            $variaveis_dinamicas = json_decode($contrato->campos_extras) ;
            foreach ($variaveis_dinamicas as $campo => $valor){
                $templateRenderizado = str_replace('['.strtoupper($campo).']', $valor,$templateRenderizado );
            }
        }
        if(is_file(base_path().'/storage/app/public/contratos/contrato_'.$contrato->id.'.pdf')){
            unlink(base_path().'/storage/app/public/contratos/contrato_'.$contrato->id.'.pdf');
        }
        PDF::loadHTML(utf8_decode($templateRenderizado))->setPaper('a4')->setOrientation('portrait')->save( base_path().'/storage/app/public/contratos/contrato_'.$contrato->id.'.pdf');
        return 'contratos/contrato_'.$contrato->id.'.pdf';
    }

    public static function notifyFornecedor($id)
    {
        $contrato = Contrato::find($id);
        if(!$contrato){
            return [
                'success'=>false,
                'messages'=>[
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
                'success'=>true
            ];
        } else {
            // Se não tiver envia um e-mail para o fornecedor
            if (!strlen($fornecedor->email)) {
                $mensagens[] = 'O Fornecedor ' . $fornecedor->nome . ' não possui acesso e e-mail cadastrado,
                    <a href="'.Storage::url($arquivo).'" target="_blank">Imprima o contrato</a> e faça o fornecedor assinar.
                    O telefone do fornecedor é ' . $fornecedor->telefone;
                return [
                    'success'=>true,
                    'messages'=>$mensagens
                ];
            } else {
                Mail::to($fornecedor->email)->send(new ContratoServicoFornecedorNaoUsuario($contrato, $arquivo));
                return [
                    'success'=>true
                ];
            }
        }
    }
}
