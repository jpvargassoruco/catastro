<?php

########################################
#---- LEER REGISTRO DE INFO_PREDIO ----#
########################################	
$sql = "SELECT * FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check_info_predio = pg_num_rows(pg_query($sql));
$result_predio = pg_query($sql);
$info_predio = pg_fetch_array($result_predio, null, PGSQL_ASSOC);

$activo = $info_predio['activo'];
$dir_tipo = $info_predio['dir_tipo'];
$dir_nom = $info_predio['dir_nom'];
$dir_num = $info_predio['dir_num'];
$dir_edif = $info_predio['dir_edif'];
$dir_cond = $info_predio['dir_cond'];
$zona = $info_predio['via_mat'];
$via_mat = $info_predio['via_mat'];
$dir_zonurb = $info_predio['dir_zonurb'];


$ser_alc = $ser_alc_texto = $info_predio['ser_alc'];
if ($ser_alc == "") {
	$ser_alc_texto = "-";
}
$ser_agu = $ser_agu_texto = $info_predio['ser_agu'];
if ($ser_agu == "") {
	$ser_agu_texto = "-";
}
$ser_luz = $ser_luz_texto = $info_predio['ser_luz'];
if ($ser_luz == "") {
	$ser_luz_texto = "-";
}
$ser_tel = $ser_tel_texto = $info_predio['ser_tel'];
if ($ser_tel == "") {
	$ser_tel_texto = "-";
}
$ser_gas = $ser_gas_texto = $info_predio['ser_gas'];
if ($ser_gas == "") {
	$ser_gas_texto = "-";
}
$ser_cab = $ser_cab_texto = $info_predio['ser_cab'];
if ($ser_cab == "") {
	$ser_cab_texto = "-";
}
$ter_sdoc = $info_predio['ter_sdoc'];
if (trim($ter_sdoc) == "") {
	$ter_sdoc_texto = "-";
} else
	$ter_sdoc_texto = $ter_sdoc . " m²";
$ter_uso = $info_predio['ter_uso'];
if ($ter_uso == "") {
	$ter_uso_texto = "-";
} else
	$ter_uso_texto = abr($info_predio['ter_uso']);
	$ter_form = $info_predio['ter_form'];
if ($ter_form == "") {
	$ter_form_texto = "-";
} else
	$ter_form_texto = abr($info_predio['ter_form']);
	$ter_ubi = $info_predio['ter_ubi'];
	$ter_ubi_texto = abr($info_predio['ter_ubi']);
	$ter_fren = $info_predio['ter_fren'];
if ($ter_fren = -1) {
	$ter_fren_texto = "";
} else
	$ter_fren_texto = $ter_fren;
$ter_fond = $info_predio['ter_fond'];
if ($ter_fond = -1) {
	$ter_fond_texto = "";
} else
	$ter_fond_texto = $ter_fond;
$ter_nofr = $ter_nofr_texto = $info_predio['ter_nofr'];
$ter_san = $info_predio['ter_san'];
if ($ter_san == "") {
	$ter_san_texto = "-";
} else
	$ter_san_texto = abr($ter_san);
$ter_topo = $info_predio['ter_topo'];
if ($ter_topo == "") {
	$ter_topo_texto = "-";
} else
	$ter_topo_texto = abr($ter_topo);
$ter_mur = trim($info_predio['ter_mur']);
if ($ter_mur == "") {
	$ter_mur_texto = "-";
} else
	$ter_mur_texto = abr($ter_mur);
$ter_eesp = $info_predio['ter_eesp'];
if ($ter_eesp == "") {
	$ter_eesp_texto = "-";
} else
	$ter_eesp_texto = abr($ter_eesp);
$ter_ace = $ter_ace_texto = $info_predio['ter_ace'];
if ($ter_ace == "") {
	$ter_ace_texto = "-";
}
pg_free_result($result_predio);
?>