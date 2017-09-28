SELECT
	TRANSACAO.TRN_IN_ID ,
	TRANSACAO.TRN_ST_STATUS ,
	TRANSACAO_LOG.trn_st_log ,
	TRANSACAO.trn_dt_datatranscao
FROM
	mgint.int_xml XML_INTEGRACAO
JOIN mgint.int_transacao TRANSACAO ON XML_INTEGRACAO.trn_in_id = TRANSACAO.trn_in_id
LEFT JOIN mgint.int_transacaolog TRANSACAO_LOG ON XML_INTEGRACAO.trn_in_id = TRANSACAO_LOG.trn_in_id
WHERE
  -- TRANSACAO.pro_in_id = 705 -- NF
  -- TRANSACAO.pro_in_id = 306 -- PAGAMENTO
	XML_INTEGRACAO.TRN_IN_ID = 100803
ORDER BY
	TRANSACAO.trn_dt_datatranscao DESC;