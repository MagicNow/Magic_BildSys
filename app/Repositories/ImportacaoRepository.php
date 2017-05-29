<?php

namespace App\Repositories;

use App\Models\Cidade;
use App\Models\Fornecedor;
use App\Models\Cnae;
use App\Models\FornecedorServico;
use App\Models\Insumo;
use App\Models\InsumoGrupo;
use App\Models\MegaCnae;
use App\Models\MegaFornecedor;
use App\Models\MegaFornecedorServico;
use App\Models\MegaInsumo;
use App\Models\MegaInsumoGrupo;
use App\Models\Unidade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportacaoRepository
{
    # Importação de insumos do BANCO DE DADOS BILD-SYS MEGA
    public static function insumos()
    {
        $insumos = MegaInsumo::select([
            'PRO_IN_CODIGO',
            'PRO_ST_DESCRICAO',
            'GRU_IN_CODIGO',
            'UNI_ST_UNIDADE',
            'MGADM.EST_PRODUTOS.ncm_in_codigo',
            'NCM.ncm_st_descricao',
            'NCM.ncm_st_extenso'
        ])
            ->leftJoin('mgtrf.trf_ncm as NCM',function ($join){
                $join->on('NCM.ncm_tab_in_codigo','MGADM.EST_PRODUTOS.ncm_tab_in_codigo');
                $join->on('NCM.ncm_pad_in_codigo','MGADM.EST_PRODUTOS.ncm_pad_in_codigo');
                $join->on('NCM.ncm_in_codigo','MGADM.EST_PRODUTOS.ncm_in_codigo');
            })
            ->where('gru_ide_st_codigo','07')
            ->whereRaw(DB::raw(" NOT EXISTS (
                                            SELECT 1 
                                              FROM MGADM.EST_INATIVAPRODUTO ipr
                                              WHERE ipr.PRO_TAB_IN_CODIGO = MGADM.EST_PRODUTOS.PRO_TAB_IN_CODIGO
                                              AND ipr.PRO_PAD_IN_CODIGO = MGADM.EST_PRODUTOS.PRO_PAD_IN_CODIGO
                                              AND ipr.PRO_IN_CODIGO = MGADM.EST_PRODUTOS.PRO_IN_CODIGO)")
                        )
            ->get();
        foreach ($insumos as $produto) {
            try {
                $unidade = Unidade::where('sigla', trim(utf8_encode($produto->uni_st_unidade)))->first();
                if (!$unidade) {
                    $unidade = Unidade::create([
                        'sigla' => trim(utf8_encode($produto->uni_st_unidade)),
                        'descricao' => trim(utf8_encode($produto->uni_st_unidade)),
                    ]);
                }

                $insumo = Insumo::firstOrCreate([
                    'nome' => trim(utf8_encode($produto->pro_st_descricao)),
                    'unidade_sigla' => trim(utf8_encode($produto->uni_st_unidade)),
                    'codigo' => $produto->pro_in_codigo,
                    'insumo_grupo_id' => $produto->gru_in_codigo
                ]);
                $insumo->ncm_codigo = $produto->ncm_in_codigo;
                    $insumo->ncm_texto  =  trim(utf8_encode($produto->ncm_st_descricao));
                    $insumo->ncm_codigo_texto  =  trim(utf8_encode($produto->ncm_st_extenso));

                $insumo->tems = self::getTems($produto->pro_in_codigo);

                if($insumo->tems == ''){
                    $insumo->tems = null;
                }
            
                $insumo->save();
            } catch (\Exception $e) {
                Log::error('Erro ao importar insumo '. $produto->pro_in_codigo. ': '.$e->getMessage());
            }
        }

        return ['total-mega' => $insumos->count(), 'total-sys' => Insumo::count()];
    }

    # Importação de grupo de insumos do BANCO DE DADOS BILD-SYS MEGA
    public static function insumo_grupos()
    {
        $grupos_mega = MegaInsumoGrupo::select([
            'GRU_IDE_ST_CODIGO',
            'GRU_IN_CODIGO',
            'GRU_ST_NOME',])
            ->where('gru_ide_st_codigo' , '07')
            ->get();
        foreach ($grupos_mega as $grupo) {
            try {
                $insumoGrupo = InsumoGrupo::firstOrCreate([
                    'id' => $grupo->gru_in_codigo,
                    'codigo_identificador' => $grupo->gru_ide_st_codigo,
                    'nome' => trim(utf8_encode($grupo->gru_st_nome))
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao importar grupo de insumo '.$grupo->gru_in_codigo.': '.$e->getMessage());
            }
        }
        return ['total-mega' => $grupos_mega->count(), 'total-sys' => InsumoGrupo::count()];
    }


    /**
     * Importa Fornecedor
     * @param $param_value
     * @param string $param_type
     * @return bool
     */
    public static function fornecedores($param_value, $param_type = 'AGN_ST_CGC')
    # Importação de Fornecedores do BANCO DE DADOS BILD-SYS - MEGA
    {

            $fornecedores_mega = MegaFornecedor::select([
                'AGN_IN_CODIGO',
                'AGN_ST_FANTASIA',
                'AGN_ST_NOME',
                'UF_ST_SIGLA',
                'AGN_ST_MUNICIPIO',
                'TPL_ST_SIGLA',
                'AGN_ST_LOGRADOURO',
                'AGN_ST_NUMERO',
                'AGN_ST_BAIRRO',
                'AGN_ST_CEP',
                'AGN_ST_COMPLEMENTO',
                'AGN_ST_CGC',
                'AGN_CH_STATUSCGC',
                'AGN_ST_INSCRESTADUAL',
                'AGN_ST_EMAIL',
                'AGN_ST_URL'])
                ->where(DB::raw('trim('.$param_type.')'), trim($param_value))
                ->first();
        try {
            if ($fornecedores_mega) {
                $cidade = Cidade::where('nome', 'LIKE', '%' . $fornecedores_mega->agn_st_municipio . '%')
                    ->first();

                $fornecedor = Fornecedor::firstOrCreate([
                    'codigo_mega' => trim($fornecedores_mega->agn_in_codigo),
                    'nome' => trim(utf8_encode($fornecedores_mega->agn_st_nome)),
                    'cnpj' => trim($fornecedores_mega->agn_st_cgc),
                    'tipo_logradouro' => trim($fornecedores_mega->tpl_st_sigla),
                    'logradouro' => trim(utf8_encode($fornecedores_mega->agn_st_logradouro)),
                    'numero' => trim($fornecedores_mega->agn_st_numero),
                    'complemento' => trim(utf8_encode($fornecedores_mega->agn_st_complemento)),
                    'municipio' => trim(utf8_encode($fornecedores_mega->agn_st_municipio)),
                    'estado' => trim(utf8_encode($fornecedores_mega->uf_st_sigla)),
                    'situacao_cnpj' => trim(utf8_encode($fornecedores_mega->agn_ch_statuscgc)),
                    'inscricao_estadual' => trim($fornecedores_mega->agn_st_inscrestadual),
                    'email' => trim(utf8_encode($fornecedores_mega->agn_st_email)),
                    'site' => trim(utf8_encode($fornecedores_mega->agn_st_url)),
                    'telefone' => null,
                    'cep' => trim(str_replace('.','',$fornecedores_mega->agn_st_cep)),
                    'cidade_id' => isset($cidade) ? $cidade->id : null
                ]);
                ImportacaoRepository::fornecedor_servicos($fornecedor->codigo_mega);
                return $fornecedor;
            }
        } catch (\Exception $e) {
            Log::error('Erro ao importar insumo '. $fornecedores_mega->agn_in_codigo. ': '.$e->getMessage());
            return false;
        }
    }

    public static function fornecedor_servicos($param_value)
    {
        $fornecedor = Fornecedor::where('codigo_mega', $param_value)->first();

        $fornecedorServicos = MegaFornecedorServico::select([
            'TRF_CODSERVICOAGN.agn_in_codigo',
            'TRF_CODSERVICOAGN.cos_in_codigo'

        ])
            ->join('mgglo.glo_agentes_id','glo_agentes_id.agn_in_codigo','=','trf_codservicoagn.agn_in_codigo')
            ->join('mgglo.glo_agentes','glo_agentes.agn_in_codigo','=','glo_agentes_id.agn_in_codigo')
            ->join('mgtrf.trf_codservico','trf_codservico.cos_in_codigo','=','trf_codservicoagn.cos_in_codigo')
            ->where('glo_agentes.agn_in_codigo', $param_value)
            ->get();

        foreach ($fornecedorServicos as $servico) {
            try {
                $servico_cnae = Cnae::where('id', $servico->cos_in_codigo)->first();
                if (!$servico_cnae) {
                    ImportacaoRepository::cnae_servicos();
                }

                FornecedorServico::create([
                    'codigo_fornecedor_id' => $fornecedor->id,
                    'codigo_servico_id' => $servico->cos_in_codigo
                ]);

            } catch (\Exception $e) {
                Log::error('Erro ao importar serviço '. $servico->cos_in_codigo. ': '.$e->getMessage());
            }
        }

        return ['success' => true, 'total-sys' => count($fornecedorServicos) ];
    }

    # Importação de CNAE do BANCO DE DADOS BILD-SYS - MEGA
    public static function cnae_servicos(){
        $cnae_servicos = MegaCnae::select([
            'COS_IN_CODIGO',
            'COS_ST_DESCRICAO'
        ])
            ->get();

        foreach ($cnae_servicos as $servico) {
            try {
                Cnae::firstOrCreate([
                    'id' => $servico->cos_in_codigo,
                    'nome' => trim(utf8_encode($servico->cos_st_descricao))
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao importar cnae '. $servico->cos_in_codigo. ': '.$e->getMessage());
            }
        }

        return ['total-mega' => $cnae_servicos->count(), 'total-sys' => Cnae::count()];

    }

    public static function getTems($insumo_codigo)
    {
        $tems = \DB::connection('oracle')->select('(
                       Select p.pro_tab_in_codigo,
                           p.pro_pad_in_codigo,
                           p.pro_in_codigo,
                           p.pro_st_descricao,
                           p.uni_st_unidade,
                           p.gru_in_codigo,
                           grp.gru_st_nome,
                           dp.pro_st_dettecnico
                       From mgadm.est_produtos      p,
                            mgadm.est_detprodutos  dp,
                            mgadm.est_grupos      grp      
                       Where dp.pro_tab_in_codigo = p.pro_tab_in_codigo
                       And   dp.pro_pad_in_codigo = p.pro_pad_in_codigo
                       And   dp.pro_in_codigo     = p.pro_in_codigo
                      
                       And   p.gru_tab_in_codigo  = grp.gru_tab_in_codigo
                       And   p.gru_pad_in_codigo  = grp.gru_pad_in_codigo
                       And   p.gru_ide_st_codigo  = grp.gru_ide_st_codigo
                       And   p.gru_in_codigo      = grp.gru_in_codigo
                      
                       And   grp.gru_ide_st_codigo = 07
                           
                       And exists (Select 1
                                       From mgadm.est_detprodutos dp
                                       Where dp.pro_tab_in_codigo = p.pro_tab_in_codigo
                                       And   dp.pro_pad_in_codigo = p.pro_pad_in_codigo
                                       And   dp.pro_in_codigo     = p.pro_in_codigo)
                       And   p.pro_in_codigo = '.$insumo_codigo.'
                   )');

        $todos_tems = '';

        if(count($tems)){
            foreach ($tems as $tem){
                $todos_tems .= $tem->pro_st_dettecnico;
            }
            $todos_tems = trim(utf8_encode($todos_tems));
        }

        return $todos_tems;
    }
}
