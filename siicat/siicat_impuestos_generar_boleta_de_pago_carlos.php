<?php

################################################################################
#------------ GRABAR REGISTROS EN IMP_PAGADOS Y IMP_PLAN_DE_PAGOS -------------#
################################################################################	
$sql="SELECT DISTINCT no_orden FROM imp_control WHERE form = 'F1980' ORDER BY no_orden DESC LIMIT 1";
$check_control = pg_num_rows(pg_query($sql));
if ($check_control == 0) {
   $no_orden_form = 10000000;
} else {
   $result = pg_query($sql);
   $info_orden = pg_fetch_array($result, null, PGSQL_ASSOC);
   $no_orden_form = $info_orden['no_orden'];
}
$no_orden_form++;
if ($forma_pago == "CONTADO") {	
   ########################################
   #---- INGRESAR EXEN EN IMP_PAGADOS  ---#
   ########################################				
   pg_query("UPDATE imp_pagados SET d10 = '$d10', mant_val = '$mant_valor', interes = '$interes', deb_for = '$multa_incum', por_form = '$por_form', monto = '$monto', 
			    descont = '$descont_exen', cuota = '$cuota', exen_id = '$exen_id'
			    WHERE gestion = '$gestion' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");
   pg_query("UPDATE imp_pagados SET fech_imp = '$fecha', hora = '$hora', usuario = '$user_id', control = '$control', no_orden = '$no_orden_form' 
			     WHERE gestion = '$gestion' AND  cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");
   ########################################
   #------ INGRESAR VALOR EN LIBROS ------#
   ########################################		
  # if (isset($_POST["solo_empresa"])) {
  #    $valor_en_libros = $_POST["solo_empresa"];
  #    $total_a_pagar = $_POST["total_a_pagar"];			
  #    pg_query("UPDATE imp_pagados SET avaluo_total = '$valor_en_libros', imp_neto = '$total_a_pagar'
	#		     WHERE cod_cat = '$cod_cat' AND gestion = '$gestion'");	
  # }			  			 
#   pg_query("UPDATE imp_pagados SET forma_pago = 'CONTADO', descont = '$descont_exen', exen_id = '$exen_id', fech_imp = '$fecha', hora = '$hora', usuario = '$user_id', control = '$control', no_orden = '$no_orden' 
#			     WHERE cod_cat = '$cod_cat' AND gestion = '$gestion'");
} /*else {
   if ($no_cuota == 0) {
      #pg_query("INSERT INTO imp_control (control, form, fech_imp, hora, usuario, cod_cat, gestion, cuota, observ) 
			#       VALUES ('$no_orden','F1980','$fecha','$hora','$user_id','$cod_cat','$gestion','','')");
			#        WHERE cod_cat = '$cod_cat' AND gestion = '$gestion'");		 
      pg_query("UPDATE imp_pagados SET no_orden = '$no_orden_form' 
			        WHERE cod_cat = '$cod_cat' AND gestion = '$gestion'");					  
	 }	 
   pg_query("UPDATE imp_plan_de_pago SET fech_pago = '$fecha', hora = '$hora', usuario = '$user_id', control = '$control'
			     WHERE cod_cat = '$cod_cat' AND gestion = '$gestion' AND no_cuota = '$no_cuota'");	
	 $sql="SELECT no_cuota FROM imp_plan_de_pago WHERE cod_cat = '$cod_cat' AND gestion = '$gestion'";
	 $check_pdp1 = pg_num_rows(pg_query($sql));
	 $sql="SELECT no_cuota FROM imp_plan_de_pago WHERE cod_cat = '$cod_cat' AND gestion = '$gestion' AND control != ''";
	 $check_pdp2 = pg_num_rows(pg_query($sql));	 
	 if ($check_pdp1 == $check_pdp2) {
      pg_query("UPDATE imp_pagados SET exen_id = '0', fech_imp = '$fecha', hora = '$hora', usuario = '$user_id', control = 'PLAN'
			        WHERE cod_cat = '$cod_cat' AND gestion = '$gestion'");  
	 }	
}*/

################################################################################
#--------------------- LEER REGISTRO DE IMP_PAGADOS ---------------------------#
################################################################################	
$sql="SELECT * FROM imp_pagados WHERE gestion = '$gestion' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
$result = pg_query($sql);
$info_imp = pg_fetch_array($result, null, PGSQL_ASSOC);

include "siicat_impuestos_pagados.php";
if ($descont_exen > 0) {
 #  $total_a_pagar = $total_a_pagar - $descont_exen;
 #  pg_query("UPDATE imp_pagados SET cuota = '$total_a_pagar', exen_id = '$exen_id' WHERE cod_cat = '$cod_cat' AND gestion = '$gestion'"); 	 
   $sql="SELECT * FROM imp_exenciones WHERE numero = '$exen_id'";
   $result = pg_query($sql);
   $info_exen = pg_fetch_array($result, null, PGSQL_ASSOC);	
   $ley = utf8_decode ($info_exen['ley']);
	 $fecha_exen = change_date($info_exen['fecha']);
	 $descripcion = utf8_decode ($info_exen['descripcion']); 
	 $porcentaje = $info_exen['porcentaje'];	
	 pg_free_result($result); 
	 $texto_exencion = "Se aplicó rebaja de $porcentaje % para $descripcion s/ $ley";
	 if (strlen($texto_exencion) > 86){
      $texto_exencion = substr($texto_exencion,0,86);	 
	 }
} else $texto_exencion = "";
################################################################################
#---------------------------------- AJUSTES -----------------------------------#
################################################################################	
if ($forma_pago == "PLAN") {
#	 $sql="SELECT no_cuota FROM imp_plan_de_pago WHERE cod_cat = '$cod_cat' AND gestion = '$gestion'";
#	 $check_pdp = pg_num_rows(pg_query($sql));
	 $plan_pago = "PLAN";
	 $no_cuota_temp = $no_cuota + 1;
	 $liquidacion = $no_cuota_temp."/".$check_pdp1;
   $sql="SELECT monto_cuota FROM imp_plan_de_pago WHERE gestion = '$gestion' AND no_cuota = '$no_cuota' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
   $result = pg_query($sql);
   $info_plan = pg_fetch_array($result, null, PGSQL_ASSOC);
   $monto_cuota = $info_plan['monto_cuota'];	 	
   $total_a_pagar = $monto_cuota + $por_form;
   $monto_en_letras = numeros_a_letras($total_a_pagar);	 
	 $observ_control = "Plan de Pago - Cuota $no_cuota_temp"; 
} else $observ_control = "";
################################################################################
#-------------------------------- CONTROL -------------------------------------#
################################################################################	
pg_query("INSERT INTO imp_control (no_orden, form, fech_imp, hora, usuario,cod_geo,id_inmu, gestion, cuota, control, observ)
				  VALUES ('$no_orden_form','F1980','$fecha','$hora','$user_id','$cod_geo','$id_inmu','$gestion','$total_a_pagar','$control','$observ_control')");
################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	
$filename = "C:/apache/htdocs/tmp/boleta".$cod_cat.".html";
 
################################################################################
#------------------------- DATOS GENERALES DEL PREDIO -------------------------#
################################################################################
include "siicat_info_inmu_leer_datos.php";
include "siicat_info_predio_leer_datos.php";

$titular = get_contrib_nombre2 ($tit_1id);
$cod_pad = $pmc = get_contrib_pmc ($tit_1id);
$direccion = get_direccion_from_id_inmu ($id_inmu);

if (strlen ($titular) > 27) {
    $titular = substr($titular, 0, 27).".";
}
if (strlen ($ci_nit) > 12) {
    $ci_nit = substr($ci_nit, 0, 12);
}
/*
$sql="SELECT cod_pad, tit_1nom1, tit_1nom2, tit_1pat, tit_1mat, tit_1ci, dom_dpto, dom_ciu, dom_dir FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'";
$result = pg_query($sql);
$infox = pg_fetch_array($result, null, PGSQL_ASSOC);
if ($infox['cod_pad'] != "") {
   $pmc = $infox['cod_pad'];
} else $pmc = "-";
$tit_1nom1 = strtoupper(utf8_decode($infox['tit_1nom1']));
$tit_1nom2 = strtoupper(utf8_decode($infox['tit_1nom2']));
$tit_1pat = strtoupper(utf8_decode($infox['tit_1pat']));
$tit_1mat = strtoupper(utf8_decode($infox['tit_1mat']));
if ($tit_1nom1 != "") {
   $titular = $tit_1nom1;
} else $titular = "";
if ($tit_1nom2 != "") {
   $titular = $titular." ".$tit_1nom2;
} 
$titular = $titular." ".$tit_1pat;
if ($tit_1mat != "") {
   $titular = $titular." ".$tit_1mat;
}

if ($infox['tit_1ci'] == "") {
    $ci_nit = "-";
} else $ci_nit = $infox['tit_1ci'];

if ($infox['dom_dpto'] != "") {
  $dom_dpto = $infox['dom_dpto'];
} else $dom_dpto = "-";
if ($infox['dom_ciu'] != "") {
  $dom_ciu = strtoupper(utf8_decode($infox['dom_ciu']));
} else $dom_ciu = "-"; 	
if ($infox['dom_dir'] != "") {
  $dom_dir = strtoupper(utf8_decode($infox['dom_dir']));
} else $dom_dir = "-";
pg_free_result($result); 

################################################################################
#--------------------------- DIRECCION DEL PREDIO ----------------------------#
################################################################################
$sql="SELECT dir_tipo, dir_nom, dir_num, dir_edif, dir_bloq, dir_piso, dir_apto FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'";
$result = pg_query($sql);
$infox = pg_fetch_array($result, null, PGSQL_ASSOC);
if ($infox['dir_tipo'] == "A") {
   $dir_tipo = "AV.";
} elseif ($infox['dir_tipo'] == "P") {
   $dir_tipo = "P/";
} else $dir_tipo = "C/";
$dir_nom = strtoupper(utf8_decode($infox['dir_nom']));
$direccion = $dir_tipo." ".$dir_nom;
if ($infox['dir_num'] != "") {
   $dir_num = $infox['dir_num'];
} else $dir_num = "S/N";
$puerta = $dir_num;
if ($infox['dir_edif'] != "") {
   $dir_edif = strtoupper(utf8_decode($infox['dir_edif']));
	 $direccion = $direccion.", ".$dir_edif;
} else $dir_edif = "-";
if ($infox['dir_bloq'] != "") {
  $dir_bloq = $infox['dir_bloq'];
} else $dir_bloq = "-";
if ($infox['dir_piso'] != "") {
  $dir_piso = $infox['dir_piso'];
} else $dir_piso = "-";
if ($infox['dir_apto'] != "") {
  $dir_apto = $infox['dir_apto'];
} else $dir_apto = "-";
pg_free_result($result); 
*/
$puerta = $dir_num;
$dir_bloq = "-";
$dir_piso = "-";
$dir_apto = "-";
################################################################################
#------------------------------- OTROS DATOS  ---------------------------------#
################################################################################

$monto_en_letras = numeros_a_letras($total_a_pagar);
if (strlen($monto_en_letras) > 36) {
   if (strlen($monto_en_letras) < 41) { 
      $monto_en_letras1 = $monto_en_letras;
      $monto_en_letras2 = "00/100 Bs.";	 
	 } else {
      $monto_en_letras1 = substr($monto_en_letras, 0, 41);
      $monto_en_letras2 = substr($monto_en_letras, 41, strlen($monto_en_letras)-41)." 00/100 Bs.";
	 }
} else {
   $monto_en_letras1 = $monto_en_letras." 00/100 Bs.";
   $monto_en_letras2 = "";
}	 

#$sello_fila0 = "************";
#$sello_fila1 = "GOBIERNO MUNICIPAL";
#$sello_fila2 = "DE XXXXXXXXX";
#$sello_fila3 = "Cajero: ".$user_id;
#$sello_fila4 = "Monto: ".$total_a_pagar." Bs.";
#$sello_fila5 = "Fecha: ".$fecha2." &nbsp&nbsp Hora: ".$hora;
#$sello_fila6 = "************";
$sello_fila0 = $sello_fila1 = $sello_fila2 = $sello_fila3 = $sello_fila4 = $sello_fila5 = $sello_fila6 = "";
$efectivo = "X";
$cheque = "-";
$cheque_otro_banco = "-";

$nro_de_orden = $no_orden_form;
#$ancho_primera_fila = 18;
$ancho_primera_fila = 45;
$ancho_entre_contrib_banco = 30;   #30
$ancho_entre_banco_alcaldia = 40;  #35
$boleta = '"boleta_impuesto.png"';

################################################################################
#----------- PREPARAR CONTENIDO PARA GRABAR $control=$nro_de_orden ------------#
################################################################################	
$content = " 
<div style=\"background-image: url('http://localhost/vallegrande/boleta_impuesto.png'); background-size: cover;\">   

<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>


   <tr height='$ancho_primera_fila'>
      <td align='center' width='12%' >&nbsp</td>		 
      <td align='center' width='9%' '>&nbsp</td>				
      <td align='center' width='60%' '>&nbsp</td>
      <td align='center' valign='bottom' width='19%'  style='font-family: Arial; font-size: 16pt'>
        $nro_de_orden
      </td>			
   </tr>
</table>
<table border='0' width='100%' style='font-family: Arial; font-size: 10pt'>	 
   <tr height='1'>
      <td colspan='10'></td>		  
   </tr>
   <tr>
      <td align='left' width='9%' >&nbsp</td>
      <td align='left' width='38%' >&nbsp</td>
      <td align='left' width='10%' >&nbsp</td>		
   </tr> 	
	 <tr style='font-family: Tahoma; font-size: 9pt'>	 	 
      <td align='center'>$gestion</td>
      <td align='left'>$titular</td>	
      <td align='center'>$pmc</td>	
   </tr> 
</table>
<!-- Fila 3 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 5pt'>		 			 
	 <tr>	 	 
      <td align='left' width='14%' >&nbsp</td>
      <td align='left' width='14%' >&nbsp</td>
      <td align='left' width='14%' >&nbsp</td>
      <td align='left' width='14%' >&nbsp</td>	
      <td align='left' width='14%' >&nbsp</td>
      <td align='left' width='14%' >&nbsp</td>			
   </tr> 		
	 <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='center'>$tp_inmu</td>
      <td align='center'>$cod_cat</td>
      <td align='center'>$ci_nit</td>
      <td align='center'>$alcaldia</td>
      <td align='center'>$dom_ciu</td>
      <td align='left' colspan='6'>$dom_dir</td>				
   </tr> 
</table>
<!-- Fila 4 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 3pt'>		  	 	    
	 <tr>	 	 
      <td align='left' width='15%' >&nbsp</td>
      <td align='left' width='25%' >&nbsp</td>
      <td align='left' width='25%' >&nbsp</td>
      <td align='left' width='8%' >&nbsp</td>	
      <td align='left' width='8%' >&nbsp</td>
      <td align='left' width='8%' >&nbsp</td>
      <td align='left' width='8%' >&nbsp</td>	
      <td align='left' width='8%' >&nbsp</td>		
   </tr> 
   <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='left'>&nbsp&nbsp</td>
      <td align='center'>$direccion</td>
      <td align='center'>U.V. $cod_uv $urbanizacion </td>
      <td align='center'>$puerta</td>
      <td align='center'>$dir_bloq</td>
      <td align='center'>$dir_piso</td>	
      <td align='center'>$dir_apto</td>								
   </tr> 
</table>
<!-- Fila 5 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 0pt'>              
    <tr>     
      <td align='left' width='15%' >&nbsp</td>
      <td align='left' width='25%' >&nbsp</td>
      <td align='left' width='25%' >&nbsp</td>
      <td align='left' width='8%' >&nbsp</td>   
      <td align='left' width='8%' >&nbsp</td>
      <td align='left' width='8%' >&nbsp</td>
      <td align='left' width='8%' >&nbsp</td>   
      <td align='left' width='8%' >&nbsp</td>      
   </tr> 
   <tr style='font-family: Arial; font-size: 9pt'>     
      <td align='left'>&nbsp&nbsp</td>
      <td align='center'>$direccion</td>
      <td align='center'>&nbsp&nbsp edgarr</td>
      <td align='center'>&nbsp&nbsp</td>
      <td align='center'>&nbsp&nbsp</td>
      <td align='center'>&nbsp&nbsp</td>   
      <td align='center'>&nbsp&nbsp</td>                        
   </tr> 
</table>
<!-- Fila 5 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 3pt'>	 
   <tr height='1'>
      <td colspan='14'></td>		  
   </tr>  	 	  
   <tr height='11'>
      <td align = 'left' colspan='11' >&nbsp</td>
      <td></td>	
      <td align = 'left' colspan='2' >&nbsp</td>				
   </tr> 
   <tr>
      <td align='left' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='left' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='left' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='left' bgcolor='#E9E9E9'>&nbsp</td>	
      <td align='left' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='left' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='left' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='left'>&nbsp</td>
      <td align='left'>&nbsp</td>
      <td align='left'>&nbsp</td>
      <td align='left'>&nbsp</td>
      <td align='left'>&nbsp</td>
      <td align='left'>&nbsp</td>
      <td align='left'>&nbsp</td>																			
   </tr>
   <tr style='font-family: Arial; font-size: 9pt'>
      <td align='left'>&nbsp&nbsp</td>
      <td align='center' width='6%' >&nbsp $ben_zona</td>
      <td align='center' width='10%'>$via_mat</td>
      <td align='center' width='8%'>$val_m2_terr</td>
      <td align='center' width='9%'>$sup_terr</td>	
      <td align='center' width='4%'>$fact_agu</td>
      <td align='center' width='4%'>$fact_alc</td>
      <td align='center' width='4%'>$fact_luz</td>
      <td align='center' width='4%'>$fact_tel</td>
      <td align='center' width='4%'>$fact_min</td>
      <td align='center' width='5%'>$fact_incl</td>
      <td align='center' width='14%'>$avaluo_terr</td>
      <td align='center' width='2%'></td>
      <td align='center' width='13%'>$fecha_emp</td>
      <td align='center' width='13%'>$base_imp_emp</td>																			
   </tr>	
</table>
<!-- Fila 6 -->
<table border='0' width='100%' style='font-family: Tahoma; font-size: 7pt'>			  
   <tr>
      <td align='left' colspan='5' bgcolor='#E9E9E9'>         &nbsp      </td>
	    <td align='left' colspan='5' bgcolor='#E9E9E9'>			   &nbsp      </td>							
   </tr>
   <tr>	 	 
      <td align='left' width='7%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='12%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='10%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='7%' bgcolor='#E9E9E9'>         &nbsp      </td>	
      <td align='left' width='15%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='14%' bgcolor='#E9E9E9'>         &nbsp		 </td>
      <td align='left' width='7%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='13%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='2%'>         &nbsp      </td>
      <td align='left' width='14%' bgcolor='#E9E9E9'>         &nbsp      </td>
   </tr> 
   <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='center'>         $tp_viv      </td>
      <td align='center'>         $val_m2_const      </td>
      <td align='center'>         $sup_const      </td>
      <td align='center'>         $antig      </td>	
      <td align='center'>         $avaluo_const      </td>
      <td align='center'>			   $avaluo_total      </td>
      <td align='center'>         $tp_exen      </td>
      <td align='center'>         $monto_exen      </td>
      <td align='center'>         &nbsp      </td>
      <td align='center'>         $avaluo_total      </td>
   </tr>
</table>
<!-- Fila 7 -->
<table border='0' width='100%'>	
	 <tr>
<!-- TABLA Fila 7: COLUMNA IZQUIERDA-->		 
      <td width='78%'>	
<table border='0' width='100%' style='font-family: Arial; font-size: 6pt'>		 
	 <tr>	 	 
      <td align='left' colspan='9'>
         &nbsp
      </td>
   </tr>   
	 <tr>	 	 
      <td align='left' rowspan='2' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' colspan='3' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>         &nbsp      </td>	
      <td align='left' rowspan='2' bgcolor='#E9E9E9'>         &nbsp      </td>
   </tr>
   <tr>	 	 
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
	    <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>		
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>						
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>				
   </tr> 	 
   <tr style='font-family: Arial; font-size: 9pt'>
      <td align='center' width='17%'>         $imp_neto      </td>	 	 	 
      <td align='center' width='12%'>         $sal_favor      </td>
	    <td align='center' width='9%'>         $t_camb      </td>		
      <td align='center' width='10%'>         $mant_val      </td>
      <td align='center' width='11%'>         $interes      </td>
      <td align='center' width='10%'>         $multa_mora      </td>						
      <td align='center' width='10%'>         $multa_incum      </td>
      <td align='center' width='10%'>         $multa_admin      </td>	
      <td align='center' width='11%'>         $descuento      </td>							
   </tr>
</table>
<!-- Fila 8 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>		 
   <tr>	 	 
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>	
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>			
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>	
   </tr>
   <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='center' width='18%'>         $pago_ant      </td>
      <td align='center' width='20%'>         $nro_form      </td>
      <td align='center' width='16%'>         $forma_pago      </td>
      <td align='center' width='15%'>         $liquidacion      </td>	
      <td align='center' width='15%'>         $fecha_emision      </td>			
      <td align='center' width='16%'>         $fecha_venc      </td>
   </tr>	
</table>
<!-- Fila 9 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>			 
   <tr>	 	 
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         &nbsp
      </td>
   </tr>	   
	 <tr style='font-family: Arial; font-size: 9pt'>
	    <td align='center' width='12%'>
			$total_a_pagar
      </td>		 	 	 
      <td align='left'  width='888%'>
         &nbsp&nbsp&nbsp $monto_en_letras 00/100 Bs.
      </td>
   </tr>
   <tr height='9'>	 	 
      <td align='left' colspan='2' style='font-family: Arial; font-size: 7pt'>
         &nbsp&nbsp&nbsp Reposición formulario: $por_form Bs. *** $texto_exencion
      </td>	
   </tr>	 	 	  	  	 
</table>
      </td>
<!-- TABLA Fila 7: COLUMNA DERECHA -->	
	<td valign='top' width='22%'>		
	<table border='0' width='100%' style='font-family: Arial; font-size: 3pt'>		 
		<tr>
			<td align='left' width='2%'>			            &nbsp               </td>						
			<td align='center' width='38%' bgcolor='#E9E9E9'>                  &nbsp               </td>
			<td align='center' width='60%' bgcolor='#E9E9E9'>                  &nbsp               </td>
		</tr>
		<tr align='left' style='font-family: Arial; font-size: 9pt'>
			<td>			            &nbsp               </td>		 							 
			<td align='center'>                  $t_cam_actual               </td>
			<td align='center'>                  $saldo_prox_gestion               </td>	
		</tr>
		<tr height='10'>	
			<td align='center' colspan='3'>			            &nbsp               </td>	
		</tr>
		<tr>	
			<td align='center'>			            &nbsp               </td>									
			<td align='center' colspan='2' style='font-family: Arial; font-size: 7pt'>	
							 		$sello_fila0<br />
									$sello_fila1<br />
									$sello_fila2					 													 	
               </td>
		</tr>
		<tr>	
			<td align='center'>			            &nbsp               </td>									
               <td align='center' colspan='2' style='font-family: Arial; font-size: 7pt'>						 													 	               
                  $sello_fila3<br />
                  $sello_fila4<br />
                  $sello_fila5<br />
									$sello_fila6
               </td>
		</tr>												
		</table>							
      </td>																
   </tr>
</table>
<table border='0' width='100%'>
   <tr height='36'>
      <td align='center' valign='bottom'>
      <a href='javascript:print(this.document)'>
      <img border='0' src='http://$server/vallegrande/graphics/printer.png' width='22' height='22'></a>
      </td>	 
   </tr>
</table>	 
<!-- ******************** Fila 10: ARCHIVO ********************************* -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>
   <tr height='$ancho_entre_contrib_banco'>
      <td align='center' width='11%' bgcolor='#E9E9E9'>        &nbsp      </td>		 
      <td align='center' width='9%' bgcolor='#E9E9E9'>        &nbsp      </td>				
      <td align='center' width='60%' bgcolor='#E9E9E9'>        &nbsp      </td>
      <td align='center' valign='bottom' width='20%' bgcolor='#E9E9E9' style='font-family: Arial; font-size: 8pt'>
        $control
      </td>			
   </tr>
</table>
<!-- Fila 11 -->
<table border='0' width='100%'>	
	 <tr>
<!-- TABLA Fila 11: COLUMNA IZQUIERDA-->		 
      <td width='78%'>	
<table border='0' width='100%' style='font-family: Arial; font-size: 3pt'>			  	 
   <tr>	 	 
      <td align='left' width='18%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='10%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='2%'>         &nbsp      </td>
      <td align='left' width='26%' bgcolor='#E9E9E9'>         &nbsp      </td>	
      <td align='left' width='2%'>         &nbsp      </td>
      <td align='left' width='21%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='4%'>         &nbsp      </td>
      <td align='left' width='17%' bgcolor='#E9E9E9'>         &nbsp      </td>
   </tr> 
   <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='left'>         $nro_de_orden      </td>
      <td align='center'>         $gestion      </td>
      <td align='center'>         &nbsp      </td>
      <td align='center'>         $cod_geo/$cod_cat      </td>	
      <td align='center'>         &nbsp      </td>
      <td align='center'>			   $pmc      </td>
      <td align='right'>         &nbsp      </td>
      <td align='right'>         $alcaldia      </td>
   </tr>
</table>
<!-- Fila 12 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>			 	 
   <tr>	 	 
      <td align='left' width='44%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='1%'>         &nbsp      </td>
      <td align='left' width='18%' bgcolor='#E9E9E9'>         &nbsp      </td>	
      <td align='left' width='1%'>         &nbsp      </td>
      <td align='left' width='15%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='4%'>         &nbsp      </td>
      <td align='left' width='17%' bgcolor='#E9E9E9'>         &nbsp      </td>
   </tr> 
   <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='left'>         &nbsp&nbsp $titular      </td>
      <td align='center'>         &nbsp      </td>
      <td align='center'>         $ci_nit      </td>	
      <td align='center'>         &nbsp      </td>
      <td align='center'>			   $t_cam_actual      </td>
      <td align='center'>         &nbsp      </td>
      <td align='center'>         $fecha_emision      </td>
   </tr>
</table>
<!-- Fila 13 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>			 	 	  
   <tr>
		<td align='left' width='15%' bgcolor='#E9E9E9'>         &nbsp      </td>
		<td align='left' width='1%'>         &nbsp      </td>
		<td align='left' width='14%' bgcolor='#E9E9E9'>         &nbsp      </td>
		<td align='left' width='1%'>         &nbsp      </td>	
		<td align='left' width='13%' bgcolor='#E9E9E9'>         &nbsp     </td>
		<td align='left' width='1%'>         &nbsp      </td>
		<td align='left' width='9%' bgcolor='#E9E9E9'>         &nbsp      </td>
		<td align='left' width='1%'>         &nbsp      </td>
		<td align='left' width='8%' bgcolor='#E9E9E9'>         &nbsp      </td>
		<td align='left' width='1%'>         &nbsp      </td>
		<td align='left' width='15%' bgcolor='#E9E9E9'>         &nbsp      </td>
		<td align='left' width='4%'>			   &nbsp			</td>
		<td align='left' width='17%' bgcolor='#E9E9E9'>         &nbsp      </td>		
   </tr>
   <tr style='font-family: Arial; font-size: 9pt'>
      <td align='center'>         $nro_inmu      </td>
      <td align='center'>         &nbsp      </td>
      <td align='center'>         $forma_pago      </td>
      <td align='center'>         &nbsp      </td>	
      <td align='center'>         $liquidacion      </td>
      <td align='center'>         &nbsp      </td>
      <td align='center'>         $efectivo      </td>
      <td align='center'>         &nbsp      </td>
      <td align='center'>         $cheque      </td>
      <td align='center'>         &nbsp      </td>
      <td align='center'>         $cheque_otro_banco      </td>
      <td align='center'>			   &nbsp      </td>
      <td align='center'>         $fecha_venc      </td>															
   </tr>	
</table>
<!-- Fila 14 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>			 
   <tr>	 	 
      <td align='left' width='12%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='88%' bgcolor='#E9E9E9'>         &nbsp      </td>				
   </tr>	   
	 <tr style='font-family: Arial; font-size: 9pt'>
	    <td align='center'>			   $total_a_pagar      </td>		 	 	 
      <td align='left'>         &nbsp&nbsp&nbsp $monto_en_letras 00/100 Bs.      </td>
   </tr>
   <tr height='15'>	 	 
      <td align='left' colspan='4'>
         &nbsp			
      </td>			
   </tr>	 	 	  	  	 
</table>
      </td>
<!-- TABLA Fila 11: COLUMNA DERECHA -->			
      <td align='center' valign='top' width='22%' style='font-family: Arial; font-size: 7pt'>
         <table border='0' width='100%' style='font-family: Arial; font-size: 3pt'>		 
	          <tr height='9'>
               <td align='left' width='2%'>
			            &nbsp
               </td>						
               <td align='center' width='98%'>
                  &nbsp
               </td>
	          </tr>
               <td align='center'>
			            &nbsp
               </td>									
               <td align='center' style='font-family: Arial; font-size: 7pt'>	
							 		$sello_fila0<br />
									$sello_fila1<br />
									$sello_fila2					 													 	
               </td>	
	          </tr>
	          <tr>	
               <td align='center'>
			            &nbsp
               </td>									
               <td align='center' style='font-family: Arial; font-size: 7pt'>						 													 	               
                  $sello_fila3<br />
                  $sello_fila4<br />
                  $sello_fila5<br />
									$sello_fila6
               </td>	
	          </tr>												
         </table>			 				 				 				 				 
      </td>									
   </tr>
</table>
<!-- Fila 15: ALCALDIA -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>
   <tr height='$ancho_entre_banco_alcaldia'>
      <td align='center' width='12%' bgcolor='#E9E9E9'>
        &nbsp
      </td>		 
      <td align='center' width='9%' bgcolor='#E9E9E9'>
        &nbsp
      </td>				
      <td align='center' width='60%' bgcolor='#E9E9E9'>
        &nbsp
      </td>
      <td align='center' valign='bottom' width='19%' bgcolor='#E9E9E9' style='font-family: Arial; font-size: 8pt'>
        $control
      </td>			
   </tr>
</table>
<!-- Fila 16 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 5pt'>	 	 
	 <tr>	 	 
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>	
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>		
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>	
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>		
   </tr> 	
	 <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='center' width='13%'>         $nro_de_orden      </td>
      <td align='center' width='8%'>         $gestion      </td>
      <td align='center' width='17%'>         $pmc      </td>
      <td align='center' width='8%'>         $alcaldia      </td>	
      <td align='center' width='19%'>         $cod_geo/$cod_cat      </td>
      <td align='center' width='13%'>         -      </td>			
      <td align='center' width='12%'>         $ci_nit      </td>	
      <td align='center' width='10%'>         $tp_inmu      </td>		
   </tr> 

</table>
<!-- Fila 17 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>		 	 
	 <tr>	 	 
      <td align='left' width='32%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='16%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='19%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='10%' bgcolor='#E9E9E9'>         &nbsp      </td>	
      <td align='left' width='6%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='6%' bgcolor='#E9E9E9'>         &nbsp      </td>		
      <td align='left' width='5%' bgcolor='#E9E9E9'>         &nbsp      </td>	
      <td align='left' width='6%' bgcolor='#E9E9E9'>         &nbsp      </td>		
   </tr> 		
	 <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='left'>         &nbsp&nbsp;$titular      </td>
      <td align='center'>         $dom_ciu      </td>
      <td align='left' colspan='6'>         &nbsp&nbsp $dom_dir      </td>				
   </tr> 
</table>
<!-- Fila 18 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>		 	 	    
	 <tr>	 	 
      <td align='left' width='32%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='15%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='20%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='10%' bgcolor='#E9E9E9'>         &nbsp      </td>	
      <td align='left' width='6%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='6%' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' width='5%' bgcolor='#E9E9E9'>         &nbsp      </td>	
      <td align='left' width='6%' bgcolor='#E9E9E9'>         &nbsp
      </td>		
   </tr> 
	 <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='left'>         &nbsp&nbsp;$direccion      </td>
      <td align='center'>         U.V. $cod_uv      </td>
      <td align='center'>         $urbanizacion      </td>
      <td align='center'>         $dir_bloq      </td>
      <td align='center'>         $dir_bloq      </td>
      <td align='center'>         $dir_piso      </td>	
      <td align='center'>         $dir_apto      </td>	
      <td align='center'>         $nro_inmu      </td>								
   </tr> 
</table>
<!-- Fila 19 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 6pt'>			  	  
	 <tr>
      <td align='left' colspan='11' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left'>         &nbsp      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>         &nbsp      </td>  
	 <tr>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>	
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left'></td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>
      <td align='left' bgcolor='#E9E9E9'>         &nbsp      </td>																			
   </tr>
   <tr style='font-family: Arial; font-size: 9pt'>
      <td align='center' width='6%'>         &nbsp $ben_zona      </td>
      <td align='center' width='10%'>         $via_mat      </td>
      <td align='center' width='8%'>         $val_m2_terr      </td>
      <td align='center' width='9%'>         $sup_terr      </td>	
      <td align='center' width='4%'>         $fact_agu      </td>
      <td align='center' width='4%'>         $fact_alc      </td>
      <td align='center' width='4%'>         $fact_luz      </td>
      <td align='center' width='4%'>         $fact_tel      </td>
      <td align='center' width='4%'>         $fact_min      </td>
      <td align='center' width='5%'>         $fact_incl      </td>
      <td align='center' width='14%'>         $avaluo_terr      </td>
      <td align='center' width='2%'>      </td>
      <td align='center' width='13%'>         $fecha_emp      </td>
      <td align='center' width='13%'>         $base_imp_emp      </td>																			
   </tr>	
</table>
<!-- Fila 20 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>			 
   <tr>	 	 
      <td align='left' width='7%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='12%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='10%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='7%' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
      <td align='left' width='15%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='14%' bgcolor='#E9E9E9'>
         &nbsp			
      </td>
      <td align='left' width='7%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='13%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='2%'>
         &nbsp
      </td>
      <td align='left' width='14%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
   </tr> 
   <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='center'>
         $tp_viv
      </td>
      <td align='center'>
         $val_m2_const
      </td>
      <td align='center'>
         $sup_const
      </td>
      <td align='center'>
         $antig
      </td>	
      <td align='center'>
         $avaluo_const
      </td>
      <td align='center'>
			   $avaluo_total
      </td>
      <td align='center'>
         $tp_exen
      </td>
      <td align='center'>
         $monto_exen
      </td>
      <td align='center'>
         &nbsp
      </td>
      <td align='center'>
         $base_imp
      </td>
   </tr>
</table>
<!-- Fila 21 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 3pt'>		 
	 <tr height='1'>
      <td colspan='9'></td>
      <td align='left'>
			   &nbsp
      </td>
      <td align='left' width='9%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='12%' colspan='2' bgcolor='#E9E9E9'>
         &nbsp
      </td>				  
   </tr> 	 
   <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='left' rowspan='2' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' colspan='3' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
      <td align='left' rowspan='2' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left'>
			   &nbsp
      </td>
      <td align='center'>
         $t_cam_actual
      </td>
      <td align='center' colspan='2'>
         $saldo_prox_gestion
      </td>
   </tr>
   <tr style='font-family: Arial; font-size: 5pt'>	 	 
      <td align='left' bgcolor='#E9E9E9'>
         &nbsp
      </td>
	    <td align='left' bgcolor='#E9E9E9'>
         &nbsp
      </td>		
      <td align='left' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         &nbsp
      </td>						
      <td align='left' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         &nbsp
      </td>				
      <td align='left'>
			   &nbsp
      </td>
      <td align='left' colspan='2' width='11%' bgcolor='#E9E9E9'>
			   &nbsp
      </td>		
      <td align='left' width='10%' bgcolor='#E9E9E9'>
			   &nbsp
      </td>						
   </tr> 	 
   <tr style='font-family: Arial; font-size: 9pt'>
      <td align='center' width='13%'>
         $imp_neto
      </td>	 	 	 
      <td align='center' width='10%'>
         $sal_favor
      </td>
	    <td align='center' width='7%'>
         $t_camb
      </td>		
      <td align='center' width='8%'>
         $mant_val
      </td>
      <td align='center' width='8%'>
         $interes
      </td>
      <td align='center' width='8%'>
         $multa_mora
      </td>						
      <td align='center' width='8%'>
         $multa_incum
      </td>
      <td align='center' width='8%'>
         $multa_admin
      </td>	
      <td align='center' width='8%'>
         $descuento
      </td>							
      <td align='center' width='1%'>
			   &nbsp
      </td>	
      <td align='center' colspan='2'>
         $pago_ant
      </td>
      <td align='center' width='10%'>
			   $nro_form
      </td>			
   </tr>
</table>
<!-- Fila 22 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 7pt'>		 
   <tr>	 	 
      <td align='left' width='13%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='43%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='1%'>
         &nbsp
      </td>
      <td align='left' width='11%' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
      <td align='left' width='11%' bgcolor='#E9E9E9'>
         &nbsp
      </td>			
      <td align='left' width='1%'>
         &nbsp
      </td>
      <td align='left' width='10%' bgcolor='#E9E9E9'>
			   &nbsp
      </td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>
			   &nbsp
      </td>			
   </tr>
   <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='center'>
         $total_a_pagar
      </td>
      <td align='left'>
         &nbsp&nbsp&nbsp $monto_en_letras1
      </td>
      <td align='center'>
         &nbsp
      </td>
      <td align='center'>
         $fecha_emision
      </td>	
      <td align='center'>
         $fecha_venc
      </td>			
      <td align='center'>
         &nbsp
      </td>
      <td align='center'>
			   $forma_pago
      </td>
      <td align='center'>
			   $liquidacion
      </td>			
   </tr>	
   <tr height='7' style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='center'>
         &nbsp
      </td>
      <td align='left' valign='top'>
         &nbsp&nbsp&nbsp $monto_en_letras2
      </td>
      <td align='center' colspan='4'>
         &nbsp
      </td>	
      <td align='center' colspan='2' style='font-family: Arial; font-size: 7pt'>
         $sello_fila1
      </td>		
   </tr>
   <tr style='font-family: Arial; font-size: 7pt'>	 	 
      <td align='center' colspan='6'>
         &nbsp
      </td>	
      <td align='center' colspan='2'>
         $sello_fila2
      </td>		
   </tr>
   <tr style='font-family: Arial; font-size: 7pt'>	 	 
      <td align='center' colspan='6'>
         &nbsp
      </td>	
      <td align='center' colspan='2'>
         $sello_fila3
      </td>		
   </tr>
   <tr style='font-family: Arial; font-size: 7pt'>	 	 
      <td align='center' colspan='6'>
         &nbsp
      </td>	
      <td align='center' colspan='2'>
         $sello_fila4
      </td>		
   </tr>
   <tr style='font-family: Arial; font-size: 7pt'>	 	 
      <td align='center' colspan='6'>
         &nbsp
      </td>	
      <td align='center' colspan='2'>
         $sello_fila5
      </td>		
   </tr>  		 		 
</table>		
</div>";
################################################################################
#------------------- CHEQUEAR SI SE PUEDE ABRIR EL ARCHIVO --------------------#
################################################################################	
if (!$handle = fopen($filename, 'w')) {
   $error = 2; 
}
if (!fwrite($handle, $content)) {
   $error = 3; 
}
fclose($handle);

?>