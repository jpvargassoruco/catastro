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
} 
################################################################################
#--------------------- LEER REGISTRO DE IMP_PAGADOS ---------------------------#
################################################################################	
$sql="SELECT * FROM imp_pagados WHERE gestion = '$gestion' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
$result = pg_query($sql);
$info_imp = pg_fetch_array($result, null, PGSQL_ASSOC);
include "siicat_impuestos_pagados.php";
if ($descont_exen > 0) {
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

$sello_fila0 = $sello_fila1 = $sello_fila2 = $sello_fila3 = $sello_fila4 = $sello_fila5 = $sello_fila6 = "";
$efectivo = "X";
$cheque = "-";
$cheque_otro_banco = "-";

$nro_de_orden = $no_orden_form;
$ancho_primera_fila = 45;
$ancho_entre_contrib_banco = 30;   #30
$ancho_entre_banco_alcaldia = 40;  #35
$boleta = '"boleta_impuesto.png"';
$servicio = $fact_agu+$fact_alc+$fact_luz+$fact_tel+$fact_min;
$direccion = substr($direccion,0,36);

#<td align='center' width='6%' >&nbsp $ben_zona</td>
#<td align='center' width='10%'>$via_mat</td>


#<td align='center'>$tp_viv      </td>
#<td align='center' bgcolor='#E9E9E9'>$tp_exen</td>

#<td align='center' width='10%'>$t_camb</td>
#<td align='center' width='10%'>$imp_neto</td>          
#<td align='center' width='12%'>$sal_favor</td>

#<td align='center'>$saldo_prox_gestion</td>  
#<td align='center' width='20%'>$nro_form</td>

#<td align='center' width='12%' bgcolor='#E9E9E9'>$liquidacion</td>
#$nro_de_orden
################################################################################
#----------- PREPARAR CONTENIDO PARA GRABAR $control=$nro_de_orden ------------#
################################################################################	
$content = " 
<div style=\"background-image: url('http://$server/$folder/boleta_impuesto.png'); background-size: cover;\">   

<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>
   <tr>
      <td align='left' width='25%' >&nbsp</td>
      <td align='left' width='5%' >&nbsp</td>
      <td align='left' width='50%' >&nbsp</td>
      <td align='left' width='20%' >&nbsp</td>     
   </tr> 
   <tr height='$ancho_primera_fila'>
      <td align='center' width='25%' >&nbsp</td>		 
      <td align='center' width='5%' '>&nbsp</td>				
      <td align='center' width='50%' '>&nbsp</td>
      <td align='left' valign='bottom' width='20%'  style='font-family: Arial; font-size: 16pt'>
        $control
      </td>
   </tr>
</table>
<table border='0' width='100%' style='font-family: Arial; font-size: 10pt'>	 
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
      <td align='left' colspan='6'>$fecha_emision</td>				
   </tr> 
</table>
<!-- Fila 4 -->
<table border='0' width='100%'>		  	 	    
	 <tr style='font-family: Arial; font-size: 3pt'>	 	 
      <td align='left' width='15%' >&nbsp</td>
      <td align='left' width='25%' >&nbsp</td>
      <td align='left' width='25%' >&nbsp</td>
      <td align='left' width='9%' >&nbsp</td>	
      <td align='left' width='9%' >&nbsp</td>
      <td align='left' width='9%' >&nbsp</td>
      <td align='left' width='8%' >&nbsp</td>	
   </tr> 
   <tr style='font-family: Arial Narrow; font-size: 8pt'>	 	 
      <td align='left'>&nbsp&nbsp</td>
      <td align='center'>$direccion</td>
      <td align='center'>U.V. $cod_uv &nbsp $urbanizacion </td>
      <td align='center'>$puerta</td>
      <td align='center'>$dir_bloq</td>
      <td align='center'>$dir_piso</td>	
      <td align='center'>$dir_apto</td>								
   </tr> 
</table>
<!-- Fila 5 -->
<table border='0' width='100%'>	 
   <tr height='15'>
      <td align = 'left' colspan='11' >&nbsp</td>
      <td>&nbsp</td>	
      <td align = 'left' colspan='2' >&nbsp</td>
      <td>&nbsp</td>				
   </tr> 
   <tr  style='font-family: Arial; font-size: 3pt'>
      <td align='left' >&nbsp</td>
      <td align='left' >&nbsp</td>
      <td align='left' >&nbsp</td>
      <td align='left' >&nbsp</td>	
      <td align='left' >&nbsp</td>
      <td align='left' >&nbsp</td>
      <td align='left' >&nbsp</td>
      <td align='left'>&nbsp</td>
      <td align='left'>&nbsp</td>
   </tr>
   <tr style='font-family: Arial; font-size: 9pt'>
      <td align='center' width='15%' >&nbsp</td>
      <td align='center' width='17%' >$sup_terr</td>
      <td align='center' width='17%' >$val_m2_terr</td>
      <td align='center' width='8%' >$fact_via</td>	
      <td align='center' width='8%' >$fact_incl</td>
      <td align='center' width='9%' >$fact_form</td>
      <td align='center' width='8%' >$fact_ubi</td>      
      <td align='center' width='8%' >$servicio </td>
      <td align='center' width='8%' >$fac_frefon</td>														
   </tr>	
</table>
<!-- Fila 6 -->
<table border='0' width='100%' style='font-family: Tahoma; font-size: 5pt'>			  
   <tr>	 	 
      <td align='left' width='15%' >&nbsp</td>
      <td align='left' width='15%' >&nbsp</td>
      <td align='left' width='15%'>&nbsp</td>
      <td align='left' width='15%' >&nbsp</td>	
      <td align='left' width='15%'>&nbsp</td>
      <td align='left' width='15%'>&nbsp</td>
   </tr> 
   <tr style='font-family: Arial; font-size: 9pt'>	 
      <td align='center' >&nbsp</td>	     
      <td align='center' >$sup_const</td>
      <td align='center' >$val_m2_const</td>
      <td align='center' >$antig</td>
      <td align='center' >1</td>  
      <td align='center' >1</td>  	
   </tr>
</table>
<!-- Fila 7 -->
<table border='0' width='100%' style='font-family: Tahoma; font-size: 5pt'>           
   <tr>      
      <td align='left' width='15%' >&nbsp</td>
      <td align='left' width='15%' >&nbsp</td>
      <td align='left' width='15%'>&nbsp</td>
      <td align='left' width='15%' >&nbsp</td>  
      <td align='left' width='15%'>&nbsp</td>
      <td align='left' width='15%'>&nbsp</td>
   </tr> 
   <tr style='font-family: Arial; font-size: 10pt'>  
      <td align='center' >&nbsp</td>        
      <td align='center' >&nbsp</td>
      <td align='center' >&nbsp</td>
      <td align='center' >&nbsp</td>
      <td align='center' >&nbsp</td>  
      <td align='center' >&nbsp</td>    

   </tr>
</table>
<!-- Fila 8 -->
<table border='0' width='100%' style='font-family: Tahoma; font-size: 6pt'>           
   <tr>      
      <td align='left' width='23%' >&nbsp</td>
      <td align='left' width='25%' >&nbsp</td>
      <td align='left' width='9%'>&nbsp</td>
      <td align='left' width='17%' >&nbsp</td>  
      <td align='left' width='12%'>&nbsp</td>
      <td align='left' width='14%'>&nbsp</td>
   </tr> 
   <tr style='font-family: Arial; font-size: 9pt'>  
      <td align='center' >&nbsp</td>
      <td align='center' >$fecha_emp</td>
      <td align='center' >&nbsp</td>
      <td align='center' >$base_imp_emp</td>     
      <td align='center' >&nbsp</td>  
      <td align='center' >&nbsp</td>
   </tr>
</table>
<!-- Fila 9 -->
<table border='0' width='100%'>	
   <tr>
   <!-- COLUMNA IZQUIERDA-->		 
   <td width='83%'>	
   <!-- Tabla 1 -->
   <table border='0' width='100%' style='font-family: Arial; font-size: 9pt'>		 
       <tr>
         <td align='center' width='27%' >&nbsp</td>
         <td align='center' width='10%' >$avaluo_terr</td>
         <td align='center' width='10%' >&nbsp</td>
         <td align='center' width='10%' >$avaluo_const</td>
         <td align='center' width='10%' >&nbsp</td> 
         <td align='center' width='10%' >$monto_exen</td>  
         <td align='center' width='10%' >&nbsp</td> 
         <td align='center' width='10%' >$avaluo_total</td>
      </tr>
   </table>
   <!-- Tabla 2 -->
   <table border='0' width='100%' >
      <tr style='font-family: Arial; font-size: 5pt'>
         <td align='center' width='17%'>&nbsp</td>
         <td align='center' width='11%'>&nbsp</td>          
         <td align='center' width='10%'>&nbsp</td>
         <td align='center' width='10%'>&nbsp</td>                
         <td align='center' width='10%'>&nbsp</td>
         <td align='center' width='8%'>&nbsp</td>   
         <td align='center' width='8%'>&nbsp</td> 
         <td align='center' width='8%'>&nbsp</td> 
         <td align='center' width='9%'>&nbsp</td>
         <td align='center' width='9%'>&nbsp</td>                    
      </tr>
   </table>
   <!-- Tabla 3 -->
   <table border='0' width='100%' style='font-family: Arial; font-size: 9pt'>
      <tr style='font-family: Arial; font-size: 2pt'>
         <td align='center' width='17%'>&nbsp</td>
         <td align='center' width='11%'>&nbsp</td>          
         <td align='center' width='10%'>&nbsp</td>
         <td align='center' width='10%'>&nbsp</td>                
         <td align='center' width='10%'>&nbsp</td>
         <td align='center' width='8%'>&nbsp</td>   
         <td align='center' width='8%'>&nbsp</td> 
         <td align='center' width='8%'>&nbsp</td> 
         <td align='center' width='9%'>&nbsp</td>
         <td align='center' width='9%'>&nbsp</td>                    
      </tr>   
      <tr style='font-family: Arial; font-size: 9pt'>
         <td align='center' width='17%'>&nbsp</td>
         <td align='center' width='11%' >$imp_neto</td>   
         <td align='center' width='10%' >$descuento</td> 
         <td align='center' width='10%' >$sal_favor</td> 
         <td align='center' width='10%' >$pago_ant</td> 
         <td align='center' width='8%' >$mant_val</td>
         <td align='center' width='8%' >$interes</td>
         <td align='center' width='8%' >$multa_admin</td> 
         <td align='center' width='9%' >$multa_mora</td>
         <td align='center' width='9%' >$multa_incum</td> 
      </tr>
   </table>       
   <!-- Tabla 4 -->
   <table border='0' width='100%' style='font-family: Arial; font-size: 8pt'>		
   <tr>      
      <td align='left' colspan='2' width='80%'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspReposición formulario: $por_form Bs. - $texto_exencion</td>
      <td align='left' width='20%'>&nbsp</td>
   </tr>        
   <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='left'  width='60%'>&nbsp&nbsp&nbsp$monto_en_letras 00/100 Bs.</td>     
      <td align='center' width='20%'>$total_a_pagar</td>	
      <td align='left' width='20%'>&nbsp</td>
   </tr>	

   </table>

   <table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>    
   <tr style='font-family: Arial; font-size: 5pt'>      
      <td align='left' width='20%' >&nbsp</td>
      <td align='left' width='20%' >&nbsp</td>
      <td align='left' width='20%'>&nbsp</td> 
      <td align='left' width='20%'>&nbsp</td>
      <td align='left' width='20%'>&nbsp</td>  
   </tr>     
   <tr style='font-family: Arial; font-size: 9pt'>     
      <td align='center' width='20%'>$forma_pago</td>
      <td align='center' width='20%'>$fecha_venc</td>
      <td align='center' width='20%'>$t_cam_actual</td> 
      <td align='center' width='20%'>$control</td>    
      <td align='center' width='20%'>$t_camb</td>     

   </tr> 
   </table>


</td>
<!-- COLUMNA DERECHA -->
<td valign='top' width='17%'>
<table border='0' width='100%' style='font-family: Arial; font-size: 5pt'>		 
	<tr align='left' style='font-family: Arial; font-size: 9pt'>
		<td>&nbsp</td>		 							 
	</tr>
	<tr height='10'>	
		<td align='center' colspan='3'>&nbsp</td>	
	</tr>
   <tr>	
      <td align='center'>&nbsp</td>									
      <td align='center' colspan='2' style='font-family: Arial; font-size: 7pt'>	
      $sello_fila0<br />
      $sello_fila1<br />
      $sello_fila2					 													 	
      </td>
   </tr>
   <tr>	
      <td align='center'>&nbsp</td>									
      <td align='center' colspan='2' style='font-family: Arial; font-size: 7pt'> 
      $sello_fila3<br />
      $sello_fila4<br />
      $sello_fila5<br />
      $sello_fila6
      </td>
   </tr>	
   <tr height='10'>  
      <td align='center' colspan='10'>&nbsp</td> 
   </tr> 
   <tr height='10'>  
      <td align='center' colspan='10'>&nbsp</td> 
   </tr>											
</table>							
</td>
</tr>
</table>

<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>         
   <tr style='font-family: Arial; font-size: 5pt'>
      <td align='center' width='15%'>&nbsp</td> 
      <td align='center' width='60%'>&nbsp</td>
      <td align='center' width='25%'>&nbsp</td>
   </tr>
   <tr height='9'>
      <td align='center' style='font-family: Arial; font-size: 7pt'>&nbsp</td>               
      <td align='center' style='font-family: Arial; font-size: 6pt'>&nbsp</td>
      <td align='center' style='font-family: Arial; font-size: 7pt'>&nbsp</td>            
   </tr>              
</table>


<table border='0' width='100%'>
   <tr height='56'>
      <td align='center' valign='bottom'>
      <a href='javascript:print(this.document)'>
      <img border='0' src='http://$server/$folder/graphics/printer.png' width='22' height='22'></a>
      </td>	 
   </tr>
</table>	 


<!-- ************************************************************************************* -->
<!-- **************************** NUMERO DE FORMULARIO 2 ********************************* -->
<!-- ************************************************************************************* -->



<table border='0' width='100%' style='font-family: Arial; font-size: 6pt'>
   <tr height='$ancho_primera_fila'>
      <td align='center' width='25%' >&nbsp</td>       
      <td align='center' width='5%' '>&nbsp</td>            
      <td align='center' width='50%' '>&nbsp</td>
      <td align='center' valign='bottom' width='20%'  style='font-family: Arial; font-size: 16pt'>
        $control
      </td>
   </tr>
</table>
<table border='0' width='100%' style='font-family: Arial; font-size: 10pt'>    
   <tr>
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
      <td align='left' colspan='6'>$fecha_emision</td>            
   </tr> 
</table>
<!-- Fila 4 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 5pt'>              
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
   <tr style='font-family: Arial Narrow; font-size: 8pt'>       
      <td align='left'>&nbsp&nbsp</td>
      <td align='center'>$direccion</td>
      <td align='center'>U.V. $cod_uv &nbsp $urbanizacion </td>
      <td align='center'>$puerta</td>
      <td align='center'>$dir_bloq</td>
      <td align='center'>$dir_piso</td>   
      <td align='center'>$dir_apto</td>                        
   </tr> 
</table>
<!-- Fila 5 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 6pt'>  
   <tr height='15'>
      <td align = 'left' colspan='11' >&nbsp</td>
      <td>&nbsp</td> 
      <td align = 'left' colspan='2' >&nbsp</td>
      <td>&nbsp</td>          
   </tr> 
   <tr>
      <td align='left' >&nbsp</td>
      <td align='left' >&nbsp</td>
      <td align='left' >&nbsp</td>
      <td align='left' >&nbsp</td>  
      <td align='left' >&nbsp</td>
      <td align='left' >&nbsp</td>
      <td align='left' >&nbsp</td>
      <td align='left'>&nbsp</td>
      <td align='left'>&nbsp</td>
   </tr>
   <tr style='font-family: Arial; font-size: 9pt'>
      <td align='center' width='15%' >&nbsp</td>
      <td align='center' width='17%' >$sup_terr</td>
      <td align='center' width='17%' >$val_m2_terr</td>
      <td align='center' width='8%' >$fact_via</td>   
      <td align='center' width='8%' >$fact_incl</td>
      <td align='center' width='9%' >$fact_form</td>
      <td align='center' width='8%' >$fact_ubi</td>      
      <td align='center' width='8%' >$servicio </td>
      <td align='center' width='8%' >$fac_frefon</td>                                        
   </tr> 
</table>
<!-- Fila 6 -->
<table border='0' width='100%' style='font-family: Tahoma; font-size: 5pt'>           
   <tr>      
      <td align='left' width='15%' >&nbsp</td>
      <td align='left' width='15%' >&nbsp</td>
      <td align='left' width='15%'>&nbsp</td>
      <td align='left' width='15%' >&nbsp</td>  
      <td align='left' width='15%'>&nbsp</td>
      <td align='left' width='15%'>&nbsp</td>
   </tr> 
   <tr style='font-family: Arial; font-size: 9pt'>  
      <td align='center' >&nbsp</td>        
      <td align='center' >$sup_const</td>
      <td align='center' >$val_m2_const</td>
      <td align='center' >$antig</td>
      <td align='center' >1</td>  
      <td align='center' >1</td>    
   </tr>
</table>
<!-- Fila 7 -->
<table border='0' width='100%' style='font-family: Tahoma; font-size: 5pt'>           
   <tr>      
      <td align='left' width='15%' >&nbsp</td>
      <td align='left' width='15%' >&nbsp</td>
      <td align='left' width='15%'>&nbsp</td>
      <td align='left' width='15%' >&nbsp</td>  
      <td align='left' width='15%'>&nbsp</td>
      <td align='left' width='15%'>&nbsp</td>
   </tr> 
   <tr style='font-family: Arial; font-size: 10pt'>  
      <td align='center' >&nbsp</td>        
      <td align='center' >&nbsp</td>
      <td align='center' >&nbsp</td>
      <td align='center' >&nbsp</td>
      <td align='center' >&nbsp</td>  
      <td align='center' >&nbsp</td>    

   </tr>
</table>
<!-- Fila 8 -->
<table border='0' width='100%' style='font-family: Tahoma; font-size: 6pt'>           
   <tr>      
      <td align='left' width='23%' >&nbsp</td>
      <td align='left' width='25%' >&nbsp</td>
      <td align='left' width='9%'>&nbsp</td>
      <td align='left' width='17%' >&nbsp</td>  
      <td align='left' width='12%'>&nbsp</td>
      <td align='left' width='14%'>&nbsp</td>
   </tr> 
   <tr style='font-family: Arial; font-size: 9pt'>  
      <td align='center' >&nbsp</td>
      <td align='center' >Fecha Pago $fecha_emp</td>
      <td align='center' >&nbsp</td>
      <td align='center' >Base $base_imp_emp</td>     
      <td align='center' >&nbsp</td>  
      <td align='center' >&nbsp</td>
   </tr>
</table>
<!-- Fila 9 -->
<table border='0' width='100%'>  
   <tr>
   <!-- COLUMNA IZQUIERDA-->      
   <td width='83%'>  
   <!-- Tabla 1 -->
   <table border='0' width='100%' style='font-family: Arial; font-size: 9pt'>     
       <tr>
         <td align='center' width='27%' >&nbsp</td>
         <td align='center' width='10%' >$avaluo_terr</td>
         <td align='center' width='10%' >&nbsp</td>
         <td align='center' width='10%' >$avaluo_const</td>
         <td align='center' width='10%' >&nbsp</td> 
         <td align='center' width='10%' >$monto_exen</td>  
         <td align='center' width='10%' >&nbsp</td> 
         <td align='center' width='10%' >$avaluo_total</td>
      </tr>
   </table>
   <!-- Tabla 2 -->
   <table border='0' width='100%' >
      <tr style='font-family: Arial; font-size: 9pt'>
         <td align='center' width='16%'>&nbsp</td>
         <td align='center' width='9%'>&nbsp</td>          
         <td align='center' width='9%'>&nbsp</td>
         <td align='center' width='10%'>&nbsp</td>                
         <td align='center' width='9%'>&nbsp</td>
         <td align='center' width='10%'>&nbsp</td>   
         <td align='center' width='10%'>&nbsp</td>  
         <td align='center' width='10%'>&nbsp</td>
         <td align='center' width='9%'>&nbsp</td>                    
      </tr>
   </table>
   <!-- Tabla 3 -->
   <table border='0' width='100%' style='font-family: Arial; font-size: 9pt'>
      <tr style='font-family: Arial; font-size: 9pt'>
         <td align='center' width='17%'>&nbsp</td>
         <td align='center' width='11%' >$imp_neto</td>   
         <td align='center' width='10%' >$descuento</td> 
         <td align='center' width='10%' >$sal_favor</td> 
         <td align='center' width='10%' >$pago_ant</td> 
         <td align='center' width='8%' >$mant_val</td>
         <td align='center' width='8%' >$interes</td>
         <td align='center' width='8%' >$multa_admin</td> 
         <td align='center' width='9%' >$multa_mora</td>
         <td align='center' width='9%' >$multa_incum</td> 
      </tr>
   </table>       
   <!-- Tabla 4 -->
   <table border='0' width='100%' style='font-family: Arial; font-size: 8pt'>    
   <tr>      
      <td align='left' colspan='2' width='80%'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspReposición formulario: $por_form Bs. - $texto_exencion</td>
      <td align='left' width='20%'>&nbsp</td>
   </tr>     
   <tr style='font-family: Arial; font-size: 9pt'>     
      <td align='left'  width='60%'>&nbsp&nbsp&nbsp$monto_en_letras 00/100 Bs.</td>     
      <td align='center' width='20%'>$total_a_pagar</td> 
      <td align='center' width='20%'>$fecha_venc</td>
   </tr> 

   </table>

   <table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>    
   <tr style='font-family: Arial; font-size: 5pt'>      
      <td align='left' width='20%' >&nbsp</td>
      <td align='left' width='20%' >&nbsp</td>
      <td align='left' width='20%'>&nbsp</td> 
      <td align='left' width='20%'>&nbsp</td>
      <td align='left' width='20%'>&nbsp</td>  
   </tr>     
   <tr style='font-family: Arial; font-size: 9pt'>     
      <td align='center' width='20%'>$forma_pago</td>
      <td align='center' width='20%'>$fecha_venc</td>
      <td align='center' width='20%'>$t_cam_actual</td> 
      <td align='center' width='20%'>$control</td>    
      <td align='center' width='20%'>$t_camb</td>     
   </tr> 
   </table>


</td>
<!-- COLUMNA DERECHA -->
<td valign='top' width='17%'>
<table border='0' width='100%' style='font-family: Arial; font-size: 5pt'>     
   <tr align='left' style='font-family: Arial; font-size: 9pt'>
      <td>&nbsp</td>                          
   </tr>
   <tr height='10'>  
      <td align='center' colspan='3'>&nbsp</td> 
   </tr>
   <tr>  
      <td align='center'>&nbsp</td>                         
      <td align='center' colspan='2' style='font-family: Arial; font-size: 7pt'> 
      $sello_fila0<br />
      $sello_fila1<br />
      $sello_fila2                                                         
      </td>
   </tr>
   <tr>  
      <td align='center'>&nbsp</td>                         
      <td align='center' colspan='2' style='font-family: Arial; font-size: 7pt'> 
      $sello_fila3<br />
      $sello_fila4<br />
      $sello_fila5<br />
      $sello_fila6
      </td>
   </tr> 
   <tr height='10'>  
      <td align='center' colspan='10'>&nbsp</td> 
   </tr> 
   <tr height='10'>  
      <td align='center' colspan='10'>&nbsp</td> 
   </tr>                               
</table>                   
</td>
</tr>
</table>
<table border='0' width='100%' style='font-family: Arial; font-size: 9pt'>         
   <tr style='font-family: Arial; font-size: 9pt'>
      <td align='center' width='15%' bgcolor='#E9E9E9'>&nbsp</td> 
      <td align='center' width='60%' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='center' width='25%' bgcolor='#E9E9E9'>&nbsp</td>
   </tr>         
</table>
<table border='0' width='100%' style='font-family: Arial; font-size: 9pt'>         
   <tr style='font-family: Arial; font-size: 9pt'>
      <td align='center' width='15%' >&nbsp</td> 
      <td align='center' width='60%' >&nbsp</td>
      <td align='center' width='25%' >&nbsp</td>
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