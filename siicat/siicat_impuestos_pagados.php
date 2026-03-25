<?php  

$alcaldia = $cod_mun;
$urbanizacion = "-";
if (isset($_POST["solo_empresa"])) {
   $fecha_emp = $valor_en_libros;
   $avaluo_terr = $info_imp['valor_t'];
   $avaluo_const = $info_imp['valor_vi'];	 
   $base_imp_emp = $avaluo_terr + $avaluo_const;
} else {
   $fecha_emp = "-";
   $base_imp_emp = "-";
}
$t_cam_actual = $tc_act = imp_getcoti	($fecha,"usd");
$saldo_prox_gestion = 0;
$pago_ant = 0;
$nro_form = "F1980";
$liquidacion = "Original";
$fecha_emision = $fecha2;

$nro_inmu = $info_imp['no_inmu'];
$forma_pago = trim ($info_imp['forma_pago']);
$ci_nit = $info_imp['ci_nit']; 
$tp_inmu = trim ($info_imp['tp_inmu']);

if (trim($info_imp['tit_1id']) == "") {
   $titular = $cod_pad = $cod_pmc = "-";
} else {
   $tit_1id = $info_imp['tit_1id'];
   $titular = get_contrib_nombre ($tit_1id);
   $cod_pad = $cod_pmc = get_contrib_pmc ($tit_1id);
}
if (trim($info_imp['dom_ciu']) == "") {
   $dom_ciu = "-";
} else {
   $dom_ciu = $info_imp['dom_ciu'];
   if (strlen($dom_ciu > 20)) {
      $dom_ciu = substr ($dom_ciu,0,19).".";
   }	 
}	 	
if (trim($info_imp['dom_dir']) != "") {
   $dom_dir = $info_imp['dom_dir'];
	 if (strlen($dom_dir > 40)) {
	    $dom_dir = substr ($dom_dir,0,39).".";
	 }   
} else $dom_dir = "-"; 
$ben_zona = $info_imp['zona'];
$via_mat = $info_imp['via_mat'];
$val_m2_terr = $info_imp['val_tab'];
$sup_terr = $info_imp['sup_terr'];
if ($info_imp['fact_agu'] == 1) {
   $fact_agu = "-";   
} else $fact_agu = $info_imp['fact_agu'];
if ($info_imp['fact_alc'] == 1) {
   $fact_alc = "-";   
} else $fact_alc = $info_imp['fact_alc'];
if ($info_imp['fact_luz'] == 1) {
   $fact_luz = "-";   
} else $fact_luz = $info_imp['fact_luz'];
if ($info_imp['fact_tel'] == 1) {
   $fact_tel = "-";   
} else $fact_tel = $info_imp['fact_tel'];
$fact_min = $info_imp['fact_min']; 

$fact_incl = $info_imp['fact_incl'];
$factor = $info_imp['factor'];
$fact_form = $info_imp['fac_for'];
$fact_ubi = $info_imp['fac_ubi'];
$fact_via = $info_imp['fac_via'];
$fac_frefon = $info_imp['fac_frefon'];
$avaluo_terr = $info_imp['valor_t'];
$tp_viv = trim($info_imp['tp_viv']);
$tp_viv = strtoupper(utf8_decode(abr ($tp_viv)));
$sup_const = $info_imp['sup_const'];
$val_m2_const = $info_imp['valcm2'];
$ant_const = $info_imp['ant_const'];
$antig = $info_imp['fd_an'];
$avaluo_const = $info_imp['valor_vi'];
$avaluo_total = $info_imp['avaluo_total'];
$tp_exen = $info_imp['tp_exen']; 
$monto_exen = $info_imp['monto_exen'];
$base_imp = $info_imp['base_imp']; 
$imp_neto = $info_imp['imp_neto'];
$sal_favor = $info_imp['sal_favor'];

if ($sal_favor > 0) {
   $t_camb = $info_imp['cotido'];
} else $t_camb = "-";
$cotido = $info_imp['cotido'];
$cotiufv = $info_imp['cotiufv'];
$descuento = $info_imp['d10'];
$mant_val = $info_imp['mant_val'];
$interes = $info_imp['interes'];
$multa_mora = $info_imp['mul_mora'];
$multa_incum = $info_imp['deb_for'];
$multa_admin = $info_imp['san_adm'];
$por_form = $info_imp['por_form'];
$monto = $info_imp['monto'];
$descont = $info_imp['descont'];
$credito = $info_imp['credito'];
$exen_id = $info_imp['exen_id'];
$fecha_imp = $info_imp['fech_imp'];
if ($fecha_imp == "") {
   $fecha_imp = "-";
} else $fecha_imp = change_date ($fecha_imp);
$fecha_venc = $info_imp['fech_venc'];
$fecha_venc = change_date ($fecha_venc);
$total_a_pagar = $info_imp['cuota'];
$usuario = $info_imp['usuario'];
$control = $info_imp['control'];
$nro_de_orden = $info_imp['no_orden'];

$monto_en_letras = numeros_a_letras($total_a_pagar);

?>