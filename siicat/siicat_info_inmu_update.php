<?php
$sql = "UPDATE info_inmu SET tipo_inmu = '$tipo_inmu',   cod_geo = '$cod_geo',   cod_uv = '$cod_uv',     cod_man = '$cod_man', 
	cod_pred = '$cod_pred',      cod_blq  = '$cod_blq',  cod_piso = '$cod_piso', cod_apto = '$cod_apto', tit_cara = '$tit_cara', 
	tit_1id  = '$tit_1id',       tit_2id = '$tit_2id',   tit_xid = '$tit_xid',	 adq_modo = '$adq_modo', adq_doc = '$adq_doc', 
	adq_fech = '$adq_fech_temp', adq_mont = '$adq_mont', adq_sdoc = '$adq_sdoc', der_num = '$der_num',   der_fech = '$der_fech_temp',
	ben_tipo = '$ben_tipo',      ben_ano = '$ben_ano',   ben_por = '$ben_por',   cnx_alc = '$cnx_alc',   cnx_agu = '$cnx_agu',  cnx_luz = '$cnx_luz',	
	cnx_tel  = '$cnx_tel',	     cnx_gas = '$cnx_gas',	 cnx_cab = '$cnx_cab',   esp_aac  = '$esp_aac',  esp_tas = '$esp_tas',  esp_tae = '$esp_tae',
	esp_ser  = '$esp_ser',       esp_gar = '$esp_gar',   esp_dep = '$esp_dep', 	 mej_lav  = '$mej_lav',  mej_par = '$mej_par',  mej_hor = '$mej_hor', 
	mej_pis  = '$mej_pis',       mej_otr = '$mej_otr',   ctr_enc = '$ctr_enc',   ctr_sup = '$ctr_sup'	  
	WHERE id_inmu = '$id_inmu'";	
pg_query($sql);
?>