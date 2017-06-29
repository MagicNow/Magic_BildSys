<?php

namespace App\Repositories\Admin;

use App\Models\CatalogoContrato;
use App\Models\ContratoTemplate;
use App\Models\Fornecedor;
use App\Models\Obra;
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

        $template = ContratoTemplate::where('tipo','A')->first();
        $arquivoFinal = '';
        foreach ($catalogoContrato->obras as $acordoObra) {
            $templateRenderizado = $template->template;

            $obra = $acordoObra->obra;

            // Tenta aplicar variáveis de Obra
            foreach (Obra::$campos as $campo) {
                $templateRenderizado = str_replace('[' . strtoupper($campo) . '_OBRA]', $obra->$campo, $templateRenderizado);
            }

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
        }
        if(is_file(base_path().'/storage/app/public/contratos/acordo_'.$catalogoContrato->id.'.pdf')){
            unlink(base_path().'/storage/app/public/contratos/acordo_'.$catalogoContrato->id.'.pdf');
        }
        PDF::loadHTML(utf8_decode($arquivoFinal))->setPaper('a4')->setOrientation('portrait')->save( base_path().'/storage/app/public/contratos/acordo_'.$catalogoContrato->id.'.pdf');
        return 'contratos/acordo_'.$catalogoContrato->id.'.pdf';
    }
}
