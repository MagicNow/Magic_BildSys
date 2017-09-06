Select tpd.tpd_st_codigo,         
       tpd.tpd_st_descricao,      
       tpd.tpd_ch_natureza,      
       tpd.tpd_bo_numerado,       
       tpd.tpd_bo_retemirrf,      
       tpd.tpd_bo_retemcpmf,     
       tpd.tpd_bo_mesmonumfat,    
       tpd.tpd_ch_parcnumletras,  
       tpd.tpd_st_doctoparc,      
       tpd.tpd_st_tipolancto,     
       tpd.tpd_bo_conciliadata,   
       tpd.tpd_bo_conciliatpdoc,  
       tpd.tpd_bo_conciliadoc,    
       tpd.tpd_bo_cheque,         
       tpd.tpd_bo_pagtoeletronico,
       tpd.tpd_bo_permitedoctorep,
       tpd.tpd_bo_retemimpostos,  
       tpd.tpd_bo_gerades,        
       tpd.tpd_st_tipodocdes,     
       tpd.tpd_bo_permitidocpa,   
       tpd.tpd_bo_permitidocre,   
       tpd.tpd_bo_permitidomvf,   
       tpd.tpd_bo_permitidomvcpa, 
       tpd.tpd_bo_permitidomvcre, 
       tpd.tpd_bo_hbktransf,      
       tpd.tpd_bo_renumera       
   From mgfin.fin_tipo_documento tpd
   Where tpd.tpd_bo_permitidocpa   = 'S'
   And   tpd.tpd_bo_permitidocre   = 'N'
   And   tpd.tpd_bo_permitidocpa   = 'S'
   And   tpd.tpd_bo_permitidomvcre = 'N';

-- tpd_st_codigo  tpd_st_descricao

-- IMP_REF  Impostos Diversos BXREF
-- BOLETO  Boleto
-- RECIBO  Recibo
-- NF MAT  Nota Fiscal de Materiais
-- RPA Recibo de Pagamento a Autônomo
-- CUP Cupom de Pedágio
-- AV DEB  Aviso de Débito
-- CO  Conhecimento de Frete
-- NFS Nota Fiscal de Serviço
-- CT  Conhecimento de Transporte
-- NFCEE Nota Fiscal Conta Energia Elet
-- NFCT  Nota Fiscal Conta Telecomunica
-- FERIAS  Recibo de Férias
-- NFS/E Nota Fiscal Eletronica
-- INSCR.  Inscrição Contribuinte
-- CONTA Contas de Água e Gás
-- GFIP  Guia FGTS
-- DEBCC Débito em Conta Corrente
-- GPS Guia da Previdência Social
-- RELAT Relatório Publicações
-- IR  Provisão de IR
-- GUIA  Guia
-- FOLHA Folha de Pagamento
-- NF  Nota Fiscal
-- NF_REF  NF s Baixadas por Referência
-- INSS_REF  INSS Baixadas por Referência
-- FAT LOC Fatura de Locação
-- ISS_REF ISS BAixadas por Referência
-- IR_REF  IRRF Baixadas por Referência
-- PCC_REF PCC Baixadas por Referência
-- NFSC  Nota Fiscal Serv Comunicação