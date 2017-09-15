<?php

namespace App\Repositories\Admin;

use App\Models\CatalogoContrato;
use App\Models\CatalogoContratoInsumo;
use App\Models\ConfiguracaoEstatica;
use App\Models\ContratoItem;
use App\Models\ContratoStatus;
use App\Models\ContratoTemplate;
use App\Models\Fornecedor;
use App\Models\Obra;
use App\Repositories\ContratoItemModificacaoRepository;
use Illuminate\Support\Facades\DB;
use InfyOm\Generator\Common\BaseRepository;
use PDF;

class CatalogoContratoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'fornecedor_id',
        'data',
        'valor',
        'arquivo',
        'periodo_inicio',
        'periodo_termino',
        'valor_minimo',
        'valor_maximo',
        'qtd_minima',
        'qtd_maxima'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CatalogoContrato::class;
    }

    /**
     * Gera a impressão da Minuta aplicando as variáveis
     * Retorna o local do arquivo gerado
     * @param $id
     * @return string
     */
    public static function geraImpressao($id){
        $catalogoContrato = CatalogoContrato::find($id);
        if(!$catalogoContrato){
            return null;
        }

        $nomeArquivo = 'catalogo-' .$catalogoContrato->id.'-'.str_slug($catalogoContrato->fornecedor->nome);

        // Busca o nome das regionais que fazem parte do contrato
        $regionais = \App\Models\Regional::whereIn('id',$catalogoContrato->regionais()->pluck('regional_id','regional_id')->toArray())->pluck('nome','id')->toArray();

        $template = ContratoTemplate::where('tipo','A')->first();

        $arquivoFinal = '';

        //foreach ($catalogoContrato->regionais as $acordoObra) {

            $templateRenderizado = $template->template;

            //$obra = $acordoObra->obra;

            // Tenta aplicar variáveis de Obra
            /*foreach (Obra::$campos as $campo) {
                $templateRenderizado = str_replace('[' . strtoupper($campo) . '_OBRA]', $obra->$campo, $templateRenderizado);
            }*/

            // Busca o cabecalho com os dados da matriz
            $model = new ConfiguracaoEstatica();
            $r = $model->find(4);

            $templateRenderizado = str_replace("[CABECALHO_MATRIZ]", $r->valor, $templateRenderizado);

            // Tenta aplicar variáveis de Fornecedor
            foreach (Fornecedor::$campos as $campo) {
                $templateRenderizado = str_replace('[' . strtoupper($campo) . '_FORNECEDOR]', $catalogoContrato->fornecedor->$campo, $templateRenderizado);
            }

            // Tenta aplicar variáveis de Contrato

            $tabela_itens = '<table>
            <thead>
                <tr>
                    <th align="left">Descrição</th>
                    <th width="20%" align="right">Valor Unitário</th>
                    <th width="5%" align="center">Quant. Mínima</th>
                    <th width="5%" align="center">Múltiplo de</th>
                    <th width="15%" align="center">Período início</th>
                    <th width="15%" align="center">Período término</th>
                </tr>
            </thead>
            <tbody>';
            foreach ($catalogoContrato->contratoInsumos as $item) {
                $tabela_itens .= '<tr>';
                $tabela_itens .= '    <td>' . $item->insumo->nome . '</td>';
                $tabela_itens .= '    <td align="right">' . float_to_money($item->getOriginal('valor_unitario')). ' / ' . $item->insumo->unidade_sigla  . '</td>';
                $tabela_itens .= '    <td align="center">' . $item->pedido_minimo . '</td>';
                $tabela_itens .= '    <td align="center">' . $item->pedido_multiplo_de . '</td>';
                $tabela_itens .= '    <td align="center">' . $item->periodo_inicio->format('d/m/Y') . '</td>';
                $tabela_itens .= '    <td align="center">' . $item->periodo_termino->format('d/m/Y') . '</td>';

                $tabela_itens .= '</tr>';
            }

            $tabela_itens .= '</tbody></table>';


            $tabela_regionais = '<table width="100%">
                <thead>
                    <tr>
                        <th align="left">Regional</th>
                    </tr>
                </thead>
                <tbody>';
            foreach ($regionais as $regional) {
                $tabela_regionais .= '<tr>';
                $tabela_regionais .= '<td>' . $regional . '</td>';
                $tabela_regionais .= '</tr>';
            }

            $tabela_regionais .= '</tbody></table>';

            $meses = [
                1 => 'Janeiro',
                2 => 'Fevereiro',
                3 => 'Março',
                4 => 'Abril',
                5 => 'Maio',
                6 => 'Junho',
                7 => 'Julho',
                8 => 'Agosto',
                9 => 'Setembro',
                10 => 'Outubro',
                11 => 'Novembro',
                12 => 'Dezembro'
            ];

            $catalogoContratoCampos = [
                'catalogo_contrato_itens' => $tabela_itens,
                'regionais' => $tabela_regionais,
                'DIA_ATUAL' => date('d'),
                'MES_ATUAL_EXTENSO' => $meses[intval(date('m'))],
                'ANO_ATUAL' => date('Y'),

            ];
            foreach ($catalogoContratoCampos as $campo => $valor) {
                $templateRenderizado = str_replace('[' . strtoupper($campo) . ']', $valor, $templateRenderizado);
            }
            // Tenta aplicar variáveis do Template (dinâmicas)
            if (strlen($catalogoContrato->campos_extras_minuta)) {
                $variaveis_dinamicas = json_decode($catalogoContrato->campos_extras_minuta);
                foreach ($variaveis_dinamicas as $campo => $valor) {
                    $templateRenderizado = str_replace('[' . strtoupper($campo) . ']', $valor, $templateRenderizado);
                }
            }

            $arquivoFinal .= '<div style="page-break-before: always;"> </div>'. $templateRenderizado;
        //}
        if(is_file(base_path().'/storage/app/public/contratos/acordo_'.$catalogoContrato->id.'.pdf')){
            unlink(base_path().'/storage/app/public/contratos/acordo_'.$catalogoContrato->id.'.pdf');
        }
        PDF::loadHTML(utf8_decode($arquivoFinal))->setPaper('a4')->setOrientation('portrait')->save( base_path().'/storage/app/public/contratos/'.$nomeArquivo.'.pdf');
        return 'contratos/'.$nomeArquivo.'.pdf';
    }

    /**
     * Atualiza os contratos existentes
     * Busca nos catálogos que tenham alguma modificação de valor ocorrendo hoje e atualiza os contratos ativos com
     * saldo disponível
     * @return bool
     */
    public static function atualizaContratosExistentes(){
        // Busca itens que tem modificação de preço no dia atual
        $itensModificados = CatalogoContratoInsumo::where('periodo_inicio', date('Y-m-d'))
            ->whereHas('catalogo', function($query){
                $query->where('catalogo_contrato_status_id',3);
            })
            ->get();
        if(!$itensModificados->count()) {
            return true;
        }
        $atualizacoes = [];
        foreach ($itensModificados as $itemModificado) {
            $atualizacoes['CatalogoContratoInsumo: '.$itemModificado->id] = self::buscaContratoItens($itemModificado);
        }
        
        return $atualizacoes;
    }

    /**
     * @param CatalogoContratoInsumo $catalogoContratoInsumo
     * @return bool
     */
    private static function buscaContratoItens(CatalogoContratoInsumo $catalogoContratoInsumo){
        // Busca se existem contratos com os estes itens
        $contratoItens = ContratoItem::select(['contrato_itens.*'])
            ->where('insumo_id',$catalogoContratoInsumo->insumo_id)
            ->join('contratos','contratos.id','contrato_itens.contrato_id')
            ->where('contratos.fornecedor_id',$catalogoContratoInsumo->catalogo->fornecedor_id)
            ->where('contratos.contrato_status_id',ContratoStatus::ATIVO)
            // e que ainda tem saldo disponível (não foi solicitado tudo ainda)
            ->whereRaw('contrato_itens.qtd > (SELECT SUM(solicitacao_entrega_itens.qtd) 
                                                    FROM solicitacao_entrega_itens 
                                                    WHERE solicitacao_entrega_itens.contrato_item_id = contrato_itens.id)')
            ->where('valor_unitario','!=', money_to_float($catalogoContratoInsumo->valor_unitario))
            // Verifica se não foi já criado uma modificação pra este reajuste
            ->whereRaw("NOT EXISTS(SELECT 1 
                                    FROM contrato_item_modificacoes 
                                    WHERE contrato_item_modificacoes.contrato_item_id = contrato_itens.id
                                    AND tipo_modificacao = 'Reajuste de valor unitário'
                                    AND DATE(created_at) >= DATE('".$catalogoContratoInsumo->periodo_inicio."')
                                    AND contrato_item_modificacoes.valor_unitario_atual = "
                                        .money_to_float($catalogoContratoInsumo->valor_unitario).
                                    ")")
            ->get();
        if(!$contratoItens->count()) {
            return false;
        }
        $modificacoesCriadas = 0;
        // Gera um reajuste de valor nos que o valor está diferente do novo preço
        foreach ($contratoItens as $contratoItem) {
            $contratoItemModificacaoRepository = app(ContratoItemModificacaoRepository::class);
            $modificacao = $contratoItemModificacaoRepository->reajustar($contratoItem->id, [
                'valor_unitario'=>$catalogoContratoInsumo->valor_unitario,
                'observacao'=>'Atualização automática por catálogo '.$catalogoContratoInsumo->catalogo_contrato_id
            ],[]);
            if($modificacao){
                $modificacoesCriadas++;
            }
        }
        return $modificacoesCriadas;

    }
}
