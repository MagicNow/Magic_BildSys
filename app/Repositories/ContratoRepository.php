<?php

namespace App\Repositories;

use App\Models\Contrato;
use App\Models\ContratoItem;
use App\Models\Insumo;
use App\Models\QcFornecedor;
use InfyOm\Generator\Common\BaseRepository;

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
            if($soma_frete != $qcFornecedor->valor_frete){
                return [
                    'success' => false,
                    'contratos'=>[],
                    'erro'=>'Valor do Frete não confere com o passado R$ '. $qcFornecedor->valor_frete
                ];
            }
        }

        $quadroDeConcorrencia = $qcFornecedor->quadroDeConcorrencia;
        // Monta os itens do contrato
        $primeiroItem = $qcFornecedor->itens()->where('vencedor','1')->first();
        $obra_id = $primeiroItem->qcItem->oc_itens()->first()->obra_id;


        $contratoItens = [];
        $contratoCampos = [];

        $valorMaterial = [];
        $valorFaturamentoDireto = [];

        $fatorServico = 1;
        $fatorMaterial = 0;
        $fatorFatDireto = 0;


        if($quadroDeConcorrencia->hasServico()){
            if($qcFornecedor->porcentagem_servico < 100){
                $fatorServico = $qcFornecedor->porcentagem_servico / 100;
                $fatorMaterial = $qcFornecedor->porcentagem_material / 100;
                $fatorFatDireto = $qcFornecedor->porcentagem_faturamento_direto / 100;
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

                $contratoCampos[$obra_id]['valor_total'] += $valor_item;
                $tipo = explode(' ', $insumo->grupo->nome);
                if ($fatorServico < 1) {
                    if ($tipo[0] == 'SERVIÇO') {
                        $valorFaturamentoDireto[$obra_id] += $valor_item * $fatorFatDireto;
                        $valorMaterial[$obra_id] += $valor_item * $fatorMaterial;
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

        $tipo_frete = 'CIF';
        $valor_frete = 0;
        if($quadroDeConcorrencia->hasMaterial() && $qcFornecedor->tipo_frete != 'CIF'){
            foreach ($attributes['valor_frete'] as $obraID => $vl_frete){
                $contratoCampos[$obraID]['tipo_frete'] = $qcFornecedor->tipo_frete;
                $contratoCampos[$obraID]['valor_frete'] = money_to_float($vl_frete);
                $contratoCampos[$obraID]['valor_total'] += money_to_float($vl_frete);
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
            $contratoArray['contrato_status_id'] = 1; // Inicia em aprovação
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
            $contratos[] = Contrato::where('id',$contrato->id)->with('contratoItens')->first();
        }

        return [
            'success' => true,
            'contratos'=>$contratos
        ];
    }
}