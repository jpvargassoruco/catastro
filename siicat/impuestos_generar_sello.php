<?php

$gestion = $_POST["gestion"];
$no_orden_sello = $_POST["no_orden_sello"];
$cuota_sello = $_POST["cuota_sello"];
$control_sello = $_POST["control_sello"];
$forma_pago_sello = $_POST["forma_pago_sello"];
if (isset($_POST["d10"])) {
   $d10 = $_POST["d10"];
   $mant_val = $_POST["mant_val"];
   $interes = $_POST["interes"];
   $deb_for = $_POST["deb_for"];
   $por_form = $_POST["por_form"];
   $monto = $_POST["monto"];
   $descont = $_POST["decont"];
   pg_query("UPDATE imp_pagados SET d10 = '$d10', mant_val = '$mant_val', interes = '$interes', deb_for = '$deb_for', por_form = '$por_form', monto = '$monto', 
			    descont = '$descont', cuota = '$cuota_sello' 
					WHERE gestion = '$gestion' AND  cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");
}
#echo "NO_ORDEN: $no_orden_sello, COD_CAT: $cod_cat, GESTION: $gestion, TOTAL_A_PAGAR: $total_a_pagar_sello, CONTROL: $control_sello, FORMA PAGO: $forma_pago_sello<br>";
################################################################################
#--------------------- LEER REGISTRO DE IMP_PAGADOS ---------------------------#
################################################################################	
$sql = "SELECT * FROM imp_pagados WHERE gestion = '$gestion' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
$result = pg_query($sql);
$info_imp = pg_fetch_array($result, null, PGSQL_ASSOC);

include "siicat_impuestos_pagados.php";

################################################################################
#---------------------- CHEQUEAR SI HAN CAMBIADO LOS DATOS --------------------#
################################################################################	
if ($total_a_pagar != $cuota_sello) {
   $error = true;
   $mensaje_de_error = "Error: Ha cambiado el monto a pagar en el registro. Tiene que imprimir nuevamente la boleta de pago!";
} elseif ($nro_de_orden != $no_orden_sello) {
   $error = true;
   $mensaje_de_error = "Error: Ha cambiado el n�mero de �rden en el registro. Tiene que imprimir nuevamente la boleta de pago!";
} elseif ($control != $control_sello) {
   $error = true;
   $mensaje_de_error = "Error: Ha cambiado el n�mero de la boleta en el registro. Tiene que imprimir nuevamente la boleta de pago!";
} elseif ($forma_pago == "CONTADO") {
   $error = true;
   $mensaje_de_error = "Error: Ya se registr� este pago en el sistema!";
} else
   $error = false;
################################################################################
#------------ GRABAR REGISTROS EN IMP_PAGADOS Y IMP_PLAN_DE_PAGOS -------------#
################################################################################	
if (!$error) {
   if ($forma_pago_sello == "CONTADO") {
      pg_query("UPDATE imp_pagados SET forma_pago = 'CONTADO', fech_imp = '$fecha', hora = '$hora' WHERE gestion = '$gestion' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");
      pg_query("UPDATE imp_control SET control = '$control_sello', observ = 'CON SELLO' WHERE gestion = '$gestion' AND no_orden = '$no_orden_sello' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");
   }

   ################################################################################
   #---------------------------------- AJUSTES -----------------------------------#
   ################################################################################	
   if ($forma_pago == "PLAN") {
      #	 $sql="SELECT no_cuota FROM imp_plan_de_pago WHERE cod_cat = '$cod_cat' AND gestion = '$gestion'";
#	 $check_pdp = pg_num_rows(pg_query($sql));
      $plan_pago = "PLAN";
      $no_cuota_temp = $no_cuota + 1;
      $liquidacion = $no_cuota_temp . "/" . $check_pdp1;
      $sql = "SELECT monto_cuota FROM imp_plan_de_pago WHERE gestion = '$gestion' AND no_cuota = '$no_cuota' AND  cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'";
      $result = pg_query($sql);
      $info_plan = pg_fetch_array($result, null, PGSQL_ASSOC);
      $monto_cuota = $info_plan['monto_cuota'];
      $total_a_pagar = $monto_cuota + $por_form;
      $monto_en_letras = numeros_a_letras($total_a_pagar);
      $observ_control = "Plan de Pago - Cuota $no_cuota_temp";
   } else
      $observ_control = "";

}
################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	
$filename = "C:/apache/htdocs/tmp/sello" . $cod_cat . ".html";

################################################################################
#---------------------------------- SELLO -------------------------------------#
################################################################################	

if (strlen($user_id) > 9) {
   $user_id = substr($user_id, 0, 9);
}

$sello_fila0 = "***** No $control_sello *****";
$sello_fila1 = "GAM";
$sello_fila2 = "DE $municipio";
$sello_fila3 = "Orden: " . $no_orden_sello . " &nbsp Cajero: " . $user_id;
$sello_fila4 = "Monto: " . $cuota_sello . " Bs.";
$sello_fila5 = "Fecha: " . $fecha2 . " &nbsp&nbsp Hora: " . $hora;
$sello_fila6 = "************************";
################################################################################
#------------------------ PREPARAR CONTENIDO PARA GRABAR ----------------------#
################################################################################	<font face='Tahoma' size='2'>
if ($error) {
   $content = " 
   <div align='left'>
      <!-- Fila 1: ERROR -->
      <table border='1' width='100%' style='font-family: Arial; font-size: 4pt'>
         <tr height='100'>			
            <td align='center' valign='center'>
               &nbsp
            </td>			
         </tr> 
         <tr height='100'>			
            <td align='center' valign='center'  style='font-family: Arial; font-size: 10pt;'>
               <font color='red'>$mensaje_de_error</font>
            </td>			
         </tr>
      </table>
   </div>";
} else {
   $content = " 
<style type='text/css'>@media print { .no-print { display: none !important; } }</style>
<div align='left'>
<!-- Fila 1: CONTRIBUYENTE -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>
   <tr height='375'>
      <td width='100%'  style='font-family: Arial; font-size: 8pt'></td>			
   </tr>          
</table>
<table border='0' width='100%'>	
   <tr>
   <!-- TABLA Fila 7: COLUMNA IZQUIERDA-->		 
   <td width='78%'>	
   <table border='0' width='100%' style='font-family: Arial; font-size: 6pt'>		 
   <tr>	 	 
      <td align='left' colspan='9'>&nbsp</td>
   </tr>   
   <tr>	 	 
      <td colspan='9'>&nbsp</td>
   </tr>
   <tr>	 	 
      <td colspan='9'>&nbsp</td>			
   </tr> 	 
   <tr style='font-family: Arial; font-size: 10pt'>
      <td colspan='9'>&nbsp</td>						
   </tr>
</table>
<!-- Fila 8 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>		 
   <tr>	 	 
      <td colspan='6'>&nbsp</td>	
   </tr>
   <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td colspan='6'>&nbsp</td>
   </tr>	
</table>
<!-- Fila 9 -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>			 
   <tr>	 	 
      <td align='left' colspan='2' >&nbsp</td>
   </tr>	   
   <tr style='font-family: Arial; font-size: 9pt'>
      <td align='center' width='12%'>&nbsp</td>		 	 	 
      <td align='left'  width='88%'>&nbsp&nbsp&nbsp</td>
   </tr>
   <tr height='9'>	 	 
      <td align='left' colspan='2' font-size: 7pt'>&nbsp&nbsp&nbsp&nbsp </td>	
   </tr>	 	 	  	  	 
</table>
</td>
   <!-- TABLA Fila 7: COLUMNA DERECHA -->	
   <td valign='top' width='22%'>		
      <table border='0' width='100%' style='font-family: Arial; font-size: 3pt'>		 
         <tr>
            <td align='left' width='2%'> &nbsp</td>						
            <td align='center' width='38%' >&nbsp</td>
            <td align='center' width='60%' >&nbsp</td>
         </tr>
         <tr align='left' style='font-family: Arial; font-size: 9pt'>
            <td>&nbsp</td>																				 							 
            <td align='center'>&nbsp</td>
            <td align='center'>&nbsp</td>	
         </tr>
         <tr>	
            <td colspan='3' style='font-family: Arial; font-size: 6pt'>&nbsp</td>	
         </tr>
         <tr>	
            <td align='center'>&nbsp</td>									
            <td align='center' colspan='2' style='font-family: Arial; color:red; font-size: 6pt'>	
                        $sello_fila0<br />
                        $sello_fila1<br />
                        $sello_fila2					 													 	
            </td>	
         </tr>
         <tr>	
            <td align='center'>&nbsp</td>									
            <td align='center' colspan='2' style='font-family: Arial; color:red; font-size: 6pt'>						 													 	               
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
   <tr height='40'>
      <td>&nbsp</td>	 
   </tr>
</table>	 

<!-- ************************* SEGUNDO SELLO ********************************* -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>
   <tr height='375'>
      <td width='100%'  style='font-family: Arial; font-size: 8pt'></td>			
   </tr>       
</table>
<table border='0' width='100%'>	
   <tr>
   <!-- TABLA Fila 7: COLUMNA IZQUIERDA-->		 
   <td width='78%'>	
      <table border='0' width='100%' style='font-family: Arial; font-size: 6pt'>		 
         <tr>	 	 
            <td colspan='9'>&nbsp</td>
         </tr>   
         <tr>	 	 
            <td colspan='9'>&nbsp</td>
         </tr>
         <tr>	 	 
            <td colspan='9'>&nbsp</td>		
         </tr> 	 
         <tr style='font-family: Arial; font-size: 9pt'>
            <td colspan='9'>&nbsp</td>						
         </tr>
      </table>
      <!-- Fila 8 -->
      <table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>		 
         <tr>	 	 
            <td colspan='6'>&nbsp</td>	
         </tr>
         <tr style='font-family: Arial; font-size: 9pt'>	 	 
            <td colspan='6'>&nbsp</td>
         </tr>	
      </table>
      <!-- Fila 9 -->
      <table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>			 
         <tr>	 	 
            <td colspan='2' >&nbsp</td>
         </tr>	   
         <tr style='font-family: Arial; font-size: 6pt'>
            <td colspan='2' >&nbsp</td>
         </tr>
         <tr height='9'>	 	 
            <td colspan='2' font-size: 6pt'>&nbsp</td>	
         </tr>	 	 	  	  	 
      </table>
   </td>
   <!-- TABLA Fila 7: COLUMNA DERECHA -->	
   <td valign='top' width='22%'>		
      <table border='0' width='100%' style='font-family: Arial; font-size: 3pt'>		 
         <tr style='font-family: Arial; font-size: 10pt'>
            <td colspan='3' style='font-family: Arial; font-size: 10pt'>&nbsp</td>
         </tr>
         <tr style='font-family: Arial; font-size: 9pt'>
            <td colspan='3' style='font-family: Arial; font-size: 10pt'>&nbsp</td>
         </tr>
         <tr style='font-family: Arial; font-size: 9pt'>
            <td colspan='3' style='font-family: Arial; font-size: 10pt'>&nbsp</td>
         </tr>       
         <tr>	
            <td>&nbsp</td>									
            <td align='center' color='red' colspan='2' style='font-family: Arial; color:red; font-size: 6pt'>	
                        $sello_fila0<br/>
                        $sello_fila1<br/>
                        $sello_fila2					 													 	
            </td>	
         </tr>
         <tr>	
            <td align='center'>&nbsp</td>									
            <td align='center' colspan='2' style='font-family: Arial; color:red; font-size: 6pt'>						 													 	               
               $sello_fila3<br/>
               $sello_fila4<br/>
               $sello_fila5<br/>
               $sello_fila6
            </td>	
         </tr>												
      </table>							
   </td>																
</tr>
</table>

<table border='0' width='100%'>
   <tr height='20'>
      <td align='center' valign='bottom'>
      <a href='javascript:print(this.document)'>
      <img class='no-print' border='0' src='http://$server/$folder/graphics/printer.png' width='20' height='20'></a>
      </td>
   </tr>
</table>

</div>";
} #END_OF_IF (ERROR)
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