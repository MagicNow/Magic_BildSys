--------Insumo x NCM x Classe x Definição Fiscal x Aplicação---------------
Select prd.pro_in_codigo       ,        
       prd.ncm_in_codigo       ,        
       ncm.ncm_st_descricao    ,   
       ncm.ncm_st_extenso      ,    
       prd.pro_ch_deffiscalitem, 
       def.def_st_descricao    ,   
       pcl.apl_in_codigo       ,       
       apl.apl_st_nome         ,        
       pcl.cla_in_reduzido     ,     
       tpc.tpc_st_descricao    ,
       prd.pro_pad_in_codigo   ,
       pcl.pro_pad_in_codigo   ,
       tpc.tpc_pad_in_codigo   ,
       apl.apl_pad_in_codigo   ,
       ncm.ncm_pad_in_codigo 
    From mgadm.est_produtos            prd,
         mgadm.est_produtoclasse       pcl,
         mgadm.est_tipoclasses         tpc,
         mgadm.est_definicaofiscalitem def,
         mgtrf.trf_aplicacao           apl,
         mgtrf.trf_ncm                 ncm
    Where 1=1
    And   prd.gru_ide_st_codigo    = 07
    And   prd.pro_in_codigo        = pcl.pro_in_codigo(+)
    And   prd.pro_pad_in_codigo    = pcl.pro_pad_in_codigo(+)
    And   prd.pro_tab_in_codigo    = pcl.pro_tab_in_codigo(+)
    
    And   pcl.tpc_tab_in_codigo    = tpc.tpc_tab_in_codigo
    And   pcl.tpc_pad_in_codigo    = tpc.tpc_pad_in_codigo
    And   pcl.tpc_st_classe        = tpc.tpc_st_classe 
    
    And   prd.apl_tab_in_codigo    = apl.apl_tab_in_codigo
    And   prd.apl_pad_in_codigo    = apl.apl_pad_in_codigo
    And   prd.apl_in_codigo        = apl.apl_in_codigo
    
    And   prd.ncm_tab_in_codigo    = ncm.ncm_tab_in_codigo
    And   prd.ncm_pad_in_codigo    = ncm.ncm_pad_in_codigo
    And   prd.ncm_in_codigo        = ncm.ncm_in_codigo
    
    And   prd.pro_ch_deffiscalitem = def.def_ch_codigo
    And   not exists (Select 1 
                         From mgadm.est_inativaproduto ip
                         Where ip.pro_tab_in_codigo = prd.pro_tab_in_codigo
                         And   ip.pro_pad_in_codigo = prd.pro_pad_in_codigo
                         And   ip.pro_in_codigo     = prd.pro_in_codigo)

------------Fornecedor x Serviço-------------------------
Select agn.agn_in_codigo,
       agn.agn_st_fantasia,
       agn.agn_st_nome,
       agn.agn_st_logradouro,
       agn.agn_st_numero,
       agn.agn_st_bairro,
       agn.agn_st_municipio,
       agn.uf_st_sigla,       
       csa.cos_in_codigo,
       csa.cos_re_aliqiss,
       csa.cos_re_aliqirrf,
       csa.cos_re_percredirrf,
       csa.cos_re_percredinss,
       csa.cos_re_aliqcsll,
       csa.cos_re_aliqpis,
       csa.cos_re_aliqcofins,
       csa.cos_Re_Percrediss,
       csa.cos_re_percredsestsenat,
       csa.agn_pad_in_codigo,
       agn.agn_pad_in_codigo
    From mgtrf.trf_codservicoagn csa, 
         mgglo.glo_agentes_id    agi,
         mgglo.glo_agentes       agn
    Where 1=1
    And   agi.agn_ch_status     <> 'I'

    And   mgcli.pck_util_afh.F_StatusAgente(agn.agn_tab_in_codigo
                                           ,agn.agn_pad_in_codigo
                                           ,agn.agn_in_codigo
                                           ,'F') <> 'I'

    And   csa.agn_tab_in_codigo = agi.agn_tab_in_codigo
    And   csa.agn_pad_in_codigo = agi.agn_pad_in_codigo
    And   csa.agn_in_codigo     = agi.agn_in_codigo
    And   csa.agn_tau_st_codigo = agi.agn_tau_st_codigo
    
    And   agi.agn_tab_in_codigo = agn.agn_tab_in_codigo
    And   agi.agn_pad_in_codigo = agn.agn_pad_in_codigo
    And   agi.agn_in_codigo     = agn.agn_in_codigo
   
-----------Serviço x Município--------------------
Select cse.cos_in_codigo    ,
       cse.cos_st_descricao ,
       cse.cos_re_aliqirrf  ,
       cse.cos_re_aliqinss  ,
       cse.cos_re_aliqcsll  ,
       cse.drf_st_codigo    ,
       cse.cos_re_aliqpis   ,
       cse.cos_re_aliqcofins,
       smu.uf_st_sigla      ,
       mun.mun_st_nome      ,
       cse.agn_pad_in_codigo
    From mgtrf.trf_codservico    cse,
         mgtrf.trf_servmunicipio smu,
         mgglo.glo_municipio     mun
    Where 1=1
    
    And   cse.cos_in_codigo = smu.cos_in_codigo 
    
    And   smu.uf_st_sigla   = mun.uf_st_sigla
    And   smu.mun_in_codigo = mun.mun_in_codigo 
    
-------------Tabela de Descrição do Serviço-------------------
Select cse.cos_in_codigo    , 
       cse.cos_st_descricao ,
       cse.cos_re_aliqirrf  ,
       cse.cos_re_aliqinss  ,
       cse.cos_re_aliqcsll  ,
       cse.drf_st_codigo    ,
       cse.cos_re_aliqpis   ,
       cse.cos_re_aliqcofins,
       cse.agn_pad_in_codigo
   From mgtrf.trf_codservico cse     
   
---------------------------Classe Insumo---------------
Select prd.pro_in_codigo       ,        
       prd.ncm_in_codigo       ,        
       prd.pro_ch_deffiscalitem, 
       pcl.apl_in_codigo       ,           
       pcl.cla_in_reduzido     ,
       prd.pro_pad_in_codigo
    From mgadm.est_produtos            prd,
         mgadm.est_produtoclasse       pcl
    Where 1=1
    And   prd.gru_ide_st_codigo    = 07
    And   prd.pro_in_codigo        = pcl.pro_in_codigo(+)
    And   prd.pro_pad_in_codigo    = pcl.pro_pad_in_codigo(+)
    And   prd.pro_tab_in_codigo    = pcl.pro_tab_in_codigo(+)

    And   not exists (Select 1 
                         From mgadm.est_inativaproduto ip
                         Where ip.pro_tab_in_codigo = prd.pro_tab_in_codigo
                         And   ip.pro_pad_in_codigo = prd.pro_pad_in_codigo
                         And   ip.pro_in_codigo     = prd.pro_in_codigo)
   
-----------------Aplicação Insumo-------------------------  
Select apl.apl_in_codigo    ,
       apl.apl_bo_geraicms  ,
       apl.apl_bo_geraipi   ,
       apl.apl_st_natureza  ,
       apl.apl_st_ordem     ,
       apl.apl_st_nome      ,
       apl.apl_pad_in_codigo
   From mgtrf.trf_aplicacao apl 
   
-----------------Classe financeira------------------
Select cla.cla_in_reduzido ,
       cla.cla_st_extenso  ,
       cla.cla_ch_anasin   ,
       cla.cla_ch_natureza ,
       cla.cla_in_nivel    ,
       cla.cla_st_grupoext ,
       cla.cla_st_descricao,
       cla.cla_dt_limite   ,
       cla.cla_pad_in_codigo
   From mgfin.fin_classe cla

-----------------Tipo Classe--------------------
Select tpc.tpc_tab_in_codigo,
       tpc.tpc_pad_in_codigo,
       tpc.tpc_st_classe    ,
       tpc.tpc_st_descricao
   From mgadm.est_tipoclasses tpc

----------------Produto Classe------------------ 
Select pcl.pro_in_codigo    ,
       pcl.apl_in_codigo    ,
       pcl.cla_in_reduzido  ,
       pcl.tpc_st_classe    ,
       pcl.pro_pad_in_codigo
   From mgadm.est_produtoclasse pcl
   
   
   
   
   
   
   
   
    
