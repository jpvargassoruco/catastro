<?php  
################################################################################
#                     EL SIGUIENTE CODIGO FUE CREADO POR:                      #
#         MERCATORGIS - SISTEMAS DE INFORMACION GEOGRAFICA Y DE CATASTRO       #
#     ***** http://www.mercatorgis.com  *****  info@mercatorgis.com *****      #
################################################################################	

$result = pg_query($sql);
$info_imp = pg_fetch_array($result, null, PGSQL_ASSOC);
$folio = $info_imp['folio'];
$forma_pago = $info_imp['forma_pago'];
$tit_1id = $info_imp['tit_1id'];
$min_num = $info_imp['min_num'];
$not_nom = $info_imp['not_nom'];
$not_num = $info_imp['not_num'];
$not_cls = $info_imp['not_cls'];
$not_exp = $info_imp['not_exp'];
$min_val = $info_imp['min_val'];
$min_mon = $info_imp['min_mon'];
$min_fech = $info_imp['min_fech'];

if (($min_fech == "") OR ($min_fech == "1900-01-01")) {
   $min_fech = $min_fech_texto = "-";
} else $min_fech_texto = change_date($min_fech);

$id_comp = $info_imp['id_comp'];
$id_comp2 = $info_imp['id_comp2'];
$modo_trans = $info_imp['modo_trans'];
$modo_trans_texto = strtoupper(abr($modo_trans)); 
$valor_t = $info_imp['valor_t'];
$valor_vi = $info_imp['valor_vi'];
$valor_total = $info_imp['valor_total'];
$valor_lib = $valor_en_libros_texto = $info_imp['valor_lib'];
$base_imp = $info_imp['base_imp']; 
$monto_det = $info_imp['monto_det'];
$fech_venc = $info_imp['fech_venc'];

if (($fech_venc == "") OR ($fech_venc == "1900-01-01")) {
   $fech_venc = $fech_venc_temp = "-";
} else $fech_venc_temp = change_date($fech_venc);

$descuento = $info_imp['descont'];
$exen_select = $info_imp['exen_id'];
$exencion = $info_imp['exencion'];	
$monto_imp = $info_imp['monto_imp'];	 
$dias_venc = $cantidad_de_dias = $info_imp['dias_venc'];
$ufv_venc = $info_imp['ufv_venc'];
$trib_omit = $info_imp['trib_omit'];	 
$mul_mora = $info_imp['mul_mora'];
$multa_incump_ufv = $info_imp['mul_incum'];
$condon_select = $info_imp['condon_id'];	
$condonacion = $info_imp['condonacion'];
$int_porc = $info_imp['int_porc'];
$interes = $info_imp['interes'];	 
$deuda_trib = $info_imp['deuda_trib'];
$ufv_actual = $info_imp['ufv_actual'];
$deuda_bs = $info_imp['deuda_bs'];
$rep_form = $info_imp['rep_form']; 
$pagos_ant = $info_imp['pagos_ant'];	 
$saldo_a_favor = $sal_favor = $info_imp['sal_favor'];
$total_a_pagar = $pago_efectivo = $info_imp['total_a_pagar'];
$sal_prox_gest = $info_imp['sal_prox_gest'];	 
$credito = $info_imp['credito'];
$saldo = $info_imp['saldo'];	
$fech_imp = $info_imp['fech_imp'];

if (($fech_imp == "") OR ($fech_imp == "1900-01-01")) {
   $fech_imp = $fech_imp_temp = "1900-01-01";
   $fech_imp_texto = "-";
} else $fech_imp_texto = change_date ($fech_imp);	

$hora_imp = $info_imp['hora_imp'];
$userid_imp = $info_imp['userid_imp'];
$fech_imp_venc = $info_imp['fech_imp_venc'];

if (($fech_imp_venc == "") OR ($fech_imp_venc == "1900-01-01")) {
   $fech_imp_venc = $fech_imp_venc_temp = "1900-01-01";
   $fech_imp_venc_texto = "-";
} else $fech_imp_venc_texto = change_date ($fech_imp_venc);		 
   $estatus = trim($info_imp['estatus']);
   $fech_pago = $info_imp['fech_pago'];	 
if (($fech_pago == "") OR ($fech_pago == "1900-01-01")) {
   $fech_pago = $fech_pago_temp = "1900-01-01";
   $fech_pago_texto = "-";
} else $fech_pago_texto = change_date ($fech_pago);	
   $fech_reg = $info_imp['fech_reg'];
if (($fech_reg == "") OR ($fech_reg == "1900-01-01")) {
   $fech_reg = $fech_reg_temp = "1900-01-01";
   $fech_reg_texto = "-";
} else $fech_reg_texto = change_date ($fech_reg);

$hora_reg = $info_imp['hora_reg'];
$userid = $info_imp['userid'];	
$control = $info_imp['control'];		 	
pg_free_result($result);

$monto_condonacion = $condonacion;
if ($monto_condonacion > 0) {
   $monto_condonacion_neg = $monto_condonacion*(-1);
} else $monto_condonacion_neg = $monto_condonacion;

$monto_en_letras = numeros_a_letras($total_a_pagar);	 
	 
?>