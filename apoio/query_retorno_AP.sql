-------Query de Retorno Informações Pagamentos ---------
Select bld_vw_alt_ap.Tipo          ,
       bld_vw_alt_ap.CodFilial     ,
       bld_vw_alt_ap.NroDocumento  ,
       bld_vw_alt_ap.Parcela       ,
       bld_vw_alt_ap.Data_Documento,
       bld_vw_alt_ap.New_Vencimento,
       bld_vw_alt_ap.New_Valor
  From mgrel.bld_vw_alt_ap;
/**
*
* ==================>>>>> DETALHE  <<<<<=========================
* ==> Tipo: ALT --> Nota não Baixada
* 		  BAI --> Nota Baixada
*
* ==> CodFilial: Código de Identificação da Filial dentro do Mega
* ==>	NroDocumento: Número do Documento/Nota
* ==>	Parcela: Número da Parcela do Documento/Nota
* ==> Data_Documento: Data de Entrada do Documento/Nota
* ==>	New_Vencimento: Nova Data de Vencimento do Documento/Nota.
* 			        Para o caso do Tipo for igual "BAI", considerar informação como Data de Baixa do Documento/Nota
* ==> New_Valor: Valor Alterado do Documento/Nota
*/
