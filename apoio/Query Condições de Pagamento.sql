Select cp.cond_tab_in_codigo,         
       cp.cond_bo_ativo,              
       cp.cond_pad_in_codigo,         
       cp.cond_st_codigo,             
       cp.cond_st_nome,               
       cp.cond_in_parcelas,           
       cp.cond_st_tipocond,          
       cp.cond_st_somipi1,            
       cp.cond_st_somicm1,            
       cp.cond_st_somfre1,            
       cp.cond_st_somseg1,            
       cp.cond_st_somout1,            
       cp.cond_st_somiss1,            
       cp.cond_st_somirf1,            
       cp.cond_st_somicmr1,           
       cp.cond_st_venctomendia,       
       cp.cond_in_diavencto1,         
       cp.cond_in_diasmin,            
       cp.cond_st_parc1quit,          
       cp.cond_re_arredonda,          
       cp.cond_st_tipovencto,         
       cp.cond_st_tparredondamento,   
       cp.cond_ch_difarredondamento,  
       cp.cond_bo_sodiasuteis,        
       cp.cond_bo_prorrogaparadiautil,
       cp.cond_re_acrescimopreco,     
       cp.cond_re_acrescimofinanceiro,
       cp.cond_in_diasaltvencto,      
       cp.cond_in_diascarenciafin,    
       cp.cond_bo_antecipaparadiautil,
       cp.cond_ch_database,           
       cp.cond_bo_parcelasiguais,     
       cp.cond_dt_datafixa,           
       cp.cond_bo_diasparcela,        
       cp.cond_ch_subsequente,        
       cp.cond_bo_ultimodia,          
       cp.cond_bo_sabadoutil,         
       cp.cond_bo_primparcelaliquida 
   From mgglo.glo_condpagto cp
   Where cp.cond_st_tipocond = 'C';

-- cond_st_codigo cond_st_nome cond_in_parcelas
-- 45/90 45/90 Dias  2
-- 25    25 Dias     1
-- 11    11 PARCELAS 11
-- 18 PARC     18 Parcelas 18
-- ISS DIA 10  Iss (Vencto. dia 10)    1
-- 270 DIAS    270   1
-- 30 DFQ      30 Dias Fora Quinzena   1
-- A VISTA     A Vista     1
-- FOLHA5      Folha - 5º dia útil     1
-- 30 DFM      30 Dias Fora o Mês      1
-- 05 DIAS     05 DIAS     1
-- 07 DIAS     07 Dias     1
-- 15 DIAS     15 Dias     1
-- 15 DFM      15 Dias Fora o Mês      1
-- 30/60 DIAS  30/60 Dias  2
-- DIA 10      Dia 10      1
-- 3 PARCELAS  30/60/90    3
-- DIA 20      Dia 20      1
-- ISS RP      ISS Ribeirão Preto (Dia 15)   1
-- FOLHA20     Folha - 15 dias após 5º dia útil    1
-- 30 DIAS     30 Dias     1
-- 28/56/84    28/56/84 Dias     3
-- 5 PARCELAS  30/60/90/120/150  5
-- 4 PARCELAS  Ato/30/60/90      4
-- 28/56 28/56 dias  2
-- 28    28 DIAS     1
-- IRRF 2006   IRRF (20º dia mês subseq)     1
-- CSRET 2006  Unificada Lei 11.196    1
-- 31DEZEMBRO  Último dia de 2006      1
-- FOLHA Folha Pagto.      1
-- 10 PARC     10 Parcelas 10
-- 30/45 30/45 DIAS  2
-- 30/62/90    30/62/90 DIAS     3
-- 12 PARC     12 Parcelas 12
-- 6 PARCELAS  6 Parcelas  6
-- FGTS  Fgts (Dia 07)     1
-- ICMS  Icms (3º dia util)      1
-- INSS  INSS (Dia 20) - Pessoa Juridica     1
-- ISS DIA 15  Iss (Vencto. dia 15)    1
-- PIS/COFINS  Pis e Cofins (Dia 15)   1
-- ISS U.DIA   Iss Último dia Mês      1
-- ISS P. DIA  Iss 1º Dia Após Quinzena      1
-- ISS DIA 20  Iss (Vencto. dia 20)    1
-- 35 DIAS     35 DIAS     1
-- 10 DIAS     10 dias     1
-- 30/45/60    30/45/60    3
-- 16 PARC     16 parcelas 16
-- 24 PARC     24 parcelas 24
-- ÚLTIMOÚTIL  Último dia ùtil do mês  1
-- 15 DFQ      15 Dias Fora Quinzena   1
-- 21 DDF      21 Dias da Data  da Fatura    1
-- 42 DIAS     42 DIAS     1
-- 45 DIAS     45 DIAS     1
-- FOLHA30     Folha - Ultimo Dia do Mes     1
-- 41 DIAS     41 DIAS     1
-- 08 PARC     08 Parcelas 8
-- 09 PARC     09 parc     9
-- 7 PARCELAS  7 PARCELAS  7
-- 15 PARC     15 Parcelas 15
-- 17 PARC     17 parcelas 17
-- 13 DIAS     13 DIAS     1
-- 19 PARC     19 Parcelas 19
-- 22 PARC     22 Parcelas 22
-- 23    23 dias corridos  1
-- 2 PARC      21/36 2
-- 3 PARC      Ato/30/60   3
-- 6 PAGTOS    30/60/90/120/150/180    6
-- 20 PARC     20 Parcelas 20
-- ISS DIA 25  ISS (Vencto. dia 25)    1
-- 28/56/72    28 / 56 / 72      3
-- 4 PARC      30/60/90/120 Dias 4
-- 28 / 56     28 / 56 dias      2
-- 13 PARC     13 Parcelas 13
-- 20/40/50    20/40/50    3
-- 05 PARC     05 PARCELAS 5
-- 36 MESES    36 PARCELAS 36
-- 60 DIAS     60 Dias     1
-- 180 DIAS    180 Dias    1
-- 48    48 parcelas 48
-- 49    49 DIAS     1
-- 56 DIAS     56 DIAS     1
-- 30/60/90    30/60/90 dias     3
-- 90 DIAS     90 DIAS     1
-- 45 PARCELA  45 parc     45
-- 360 DIAS    360 DIAS    1