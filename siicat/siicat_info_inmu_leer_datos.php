<?php

########################################
#----- LEER REGISTRO DE INFO_INMU -----#
########################################	
$sql = "SELECT * FROM info_inmu WHERE id_inmu = '$id_inmu'";
$check_info_inmu = pg_num_rows(pg_query($sql));
$result_inmu = pg_query($sql);
$info = pg_fetch_array($result_inmu, null, PGSQL_ASSOC);
$tipo_inmu = trim($info['tipo_inmu']);
$tit_cant = $info['tit_cant'];
$tit_cara = $info['tit_cara'];
$cod_ma_ddrr = $info['cod_ma_ddrr'];
$cod_pr_ddrr = $info['cod_pr_ddrr'];
if (trim($info['tit_cara']) == "") {
	$tit_cara_texto = "-";
} else {
	$tit_cara_texto = abr($info['tit_cara']);
}
$tit_1id = $info['tit_1id'];
$tit_2id = $info['tit_2id'];
$tit_xid = $info['tit_xid'];
$adq_sdoc = $info['adq_sdoc'];
$ano_imp = $info['ano_imp'];
if (trim($adq_sdoc) == "") {
	$adq_sdoc_texto = "-";
} else
	$adq_sdoc_texto = $adq_sdoc . " m2";
$adq_modo = trim($info['adq_modo']);
if ($adq_modo == "") {
	$adq_modo_texto = "-";
} else
	$adq_modo_texto = abr($adq_modo);
$adq_doc = $info['adq_doc'];
if (trim($adq_doc) == "") {
	$adq_doc_texto = "-";
} else
	$adq_doc_texto = $adq_doc;
$adq_fech = $info['adq_fech'];
if (($adq_fech == "") or ($adq_fech == "1900-01-01")) {
	$adq_fech_texto = "-";
} else
	$adq_fech_texto = change_date($adq_fech);
$adq_mont = $info['adq_mont'];
#   $adq_mont_usd = $info['adq_mont_usd'];	
$val_lib = $info['val_lib'];
$der_num = $info['der_num'];
if (trim($der_num) == "") {
	$der_num_texto = "-";
} else
	$der_num_texto = $der_num;
$der_fech = $info['der_fech'];
if (($der_fech == "") or ($der_fech == "1900-01-01")) {
	$der_fech_texto = "-";
} else
	$der_fech_texto = change_date($der_fech);

$ben_tipo = $info['ben_tipo'];
$ben_ano = $info['ben_ano'];
$ben_por = $info['ben_por'];
$cnx_alc = $cnx_alc_texto = $info['cnx_alc'];
if ($cnx_alc == "") {
	$cnx_alc_texto = "-";
}
$cnx_agu = $cnx_agu_texto = $info['cnx_agu'];
if ($cnx_agu == "") {
	$cnx_agu_texto = "-";
}
$cnx_luz = $cnx_luz_texto = $info['cnx_luz'];
if ($cnx_luz == "") {
	$cnx_luz_texto = "-";
}
$cnx_tel = $cnx_tel_texto = $info['cnx_tel'];
if ($cnx_tel == "") {
	$cnx_tel_texto = "-";
}
$cnx_gas = $cnx_gas_texto = $info['cnx_gas'];
if ($cnx_gas == "") {
	$cnx_gas_texto = "-";
}
$cnx_cab = $cnx_cab_texto = $info['cnx_cab'];
if ($cnx_cab == "") {
	$cnx_cab_texto = "-";
}
$esp_aac = $esp_aac_texto = $info['esp_aac'];
if ($esp_aac == "") {
	$esp_aac_texto = "-";
}
$esp_tas = $esp_tas_texto = $info['esp_tas'];
if ($esp_tas == "") {
	$esp_tas_texto = "-";
}
$esp_tae = $esp_tae_texto = $info['esp_tae'];
if ($esp_tae == "") {
	$esp_tae_texto = "-";
}
$esp_ser = $esp_ser_texto = $info['esp_ser'];
if ($esp_ser == "") {
	$esp_ser_texto = "-";
}
$esp_gar = $esp_gar_texto = $info['esp_gar'];
if ($esp_gar == "") {
	$esp_gar_texto = "-";
}
$esp_dep = $esp_dep_texto = $info['esp_dep'];
if ($esp_dep == "") {
	$esp_dep_texto = "-";
}
$mej_lav = $mej_lav_texto = $info['mej_lav'];
if ($mej_lav == "") {
	$mej_lav_texto = "-";
}
$mej_par = $mej_par_texto = $info['mej_par'];
if ($mej_par == "") {
	$mej_par_texto = "-";
}
$mej_hor = $mej_hor_texto = $info['mej_hor'];
if ($mej_hor == "") {
	$mej_hor_texto = "-";
}
$mej_pis = $mej_pis_texto = $info['mej_pis'];
if ($mej_pis == "") {
	$mej_pis_texto = "-";
}
$mej_otr = $mej_otr_texto = $info['mej_otr'];
if ($mej_otr == "") {
	$mej_otr_texto = "-";
}
$ctr_x = $info['ctr_x'];
$ctr_y = $info['ctr_y'];
$ctr_enc = textconvert($info['ctr_enc']);
$ctr_enc_texto = $ctr_enc;
$ctr_sup = textconvert($info['ctr_sup']);
$ctr_sup_texto = $ctr_sup;
$ctr_fech = $info['ctr_fech'];
$ctr_obs = $info['ctr_obs'];
if (trim($info['ctr_obs']) == "") {
	$ctr_obs_texto = "-";
} else
	$ctr_obs_texto = $ctr_obs;
pg_free_result($result_inmu);
?>