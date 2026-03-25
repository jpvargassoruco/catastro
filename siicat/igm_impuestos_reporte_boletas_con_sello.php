<?php


$imp_neto = $d10 = $impuestos = $mant_val = $interes = $mul_mora = $deb_for = $monto = $por_form = $total = $total_check = 0;
echo "mostrar $mostrartodo ";
################################################################################
#-------------------- SELECCIONAR DATOS DE IMP_PAGADOS ------------------------#
################################################################################	
#$sql="SELECT control, fech_imp, hora, usuario, cuota FROM imp_pagados WHERE fech_imp >= '$fecha_inicio' AND fech_imp <= '$fecha_final' AND control != 'PLAN' AND control IS NOT NULL
#UNION SELECT control, fech_pago, hora, usuario, monto_cuota FROM imp_plan_de_pago WHERE fech_pago >= '$fecha_inicio' AND fech_pago <= '$fecha_final' AND control IS NOT NULL ORDER BY control ASC";

if ($usuario_reporte == "Todos") {
   $and_user = "";
} else {
   $and_user = "AND usuario = '$usuario_reporte'";
}

if ($mostrartodo == "Todos") {
	
	$sql="SELECT DISTINCT no_orden, fech_imp, hora, usuario, id_inmu, gestion, cuota, control, forma_pago FROM imp_pagados WHERE fech_imp >= '$fecha_inicio' AND fech_imp <= '$fecha_final' AND (forma_pago = 'CONTADO') $and_user AND cuota > '0' ORDER BY fech_imp, hora";

} else {
	$sql="SELECT DISTINCT no_orden, fech_imp, hora, usuario, id_inmu, gestion, cuota, control, forma_pago FROM imp_pagados WHERE cod_geo = '$cod_geo' AND  fech_imp >= '$fecha_inicio' AND fech_imp <= '$fecha_final' AND (forma_pago = 'CONTADO') $and_user AND cuota > '0' ORDER BY fech_imp, hora";


}

$check_imp = pg_num_rows(pg_query($sql));
if ($check_imp > 0) {		
   $result=pg_query($sql);
   $i = $j = 0;
	 $monto_total = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
	       if ($i == 0) {
				    $no_orden[$j] = $col_value;
			   } elseif ($i == 1) {
				    $fech_imp[$j] = $col_value;
						$fech_imp[$j] = change_date ($fech_imp[$j]);
			   } elseif ($i == 2) {
				    $hora_control[$j] = $col_value;
			   } elseif ($i == 3) {
				    $usuario[$j] = $col_value;
			   } elseif ($i == 4) {
				    $id_inmu_control = $col_value;
						$cod_cat_control[$j] = get_codcat_from_id_inmu ($id_inmu_control);																
			   } elseif ($i == 5) {
				    $gestion_control[$j] = $col_value;	
			   } elseif ($i == 6) {
				    $cuota[$j] = $col_value;	
			   } elseif ($i == 7) {
				    $control[$j] = $col_value;	
			   }  else {
				    $observ[$j] = $col_value;
						$monto_total = $monto_total + $cuota[$j];
						$i = -1;
			   }
			   $i++;	 
			}
			$j++;
   }
	 pg_free_result($result);
}
$no_de_registros = $check_imp;

################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	

$filename = "C:/apache/htdocs/tmp/reporte".$user_id.".html";

################################################################################
#------------------------ PREPARAR CONTENIDO PARA IFRAME ----------------------#
################################################################################	
$content = " 
<div align='left'>
<table border='0' width='100%' height='500' style='font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'><font face='Tahoma' size='2'>
   <tr height='20px'>
      <td> &nbsp</td>
      <td align='right' valign='top' colspan='2'>
          <a href='javascript:print(this.document)'>
          <img border='0' src='http://$server/$folder/graphics/printer.png' width='20' height='20' title='Imprimir en hoja tama�o Carta'></a>
      </td>
   </tr>	

	<tr height='700px'>
		<td width='10%'>&nbsp</td>
      <td align='center' valign='top' width='80%' bgcolor='#FFFFFF'>
		<table border='0' width='100%' bgcolor='#FFFFFF' style='font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
			<tr>
				<td align='left'>&nbsp </td>
				<td align='left' valign='middle' colspan='8'><br/><br />SIG-CATASTRO</td>
				<td align='right' valign='middle'><br/><br/>Fecha:$fecha2 </td>							 
				<td align='left'>&nbsp </td>
			</tr>
			<tr>
				<td align='left'>&nbsp </td>
				<td align='left' valign='middle' colspan='8'>				 
				GOBIERNO MUNICIPAL AUTONOMO
				</td>
				<td align='right' valign='middle'>				 
				Hora: $hora
				</td>								 
				<td align='left'>&nbsp </td>
			</tr>	
			<tr>
				<td align='left'>&nbsp </td>
				<td align='left' valign='middle' colspan='8'>				 
				$municipio, DISTRITO $distrito
				</td>
				<td align='right' >User:$user_id</td>								 
				<td align='left'>&nbsp </td>
			</tr>	
			<tr height='40'>
				<td align='left'>&nbsp </td>
				<td align='center' valign='middle' colspan='9' style='font-family: Tahoma; font-size: 10pt; font-weight:bold;'>				 
				REPORTE: BOLETAS DE PAGO CON SELLO</td>
				<td align='left'>&nbsp </td>
			</tr>	
			<tr height='30'>
				<td align='left'>&nbsp </td>
				<td align='center' valign='middle' colspan='9'>				 
				RANGO DE FECHAS DEL  $fecha_inicio  AL  $fecha_final </td>
				<td align='left'>&nbsp </td>
			</tr>																		
			<tr height='10'>
				<td align='center' colspan='11'>								
				--------------------------------------------------------------------------------------------------------------------------------------<br />
				</td>
			</tr> 						
			<tr height='20'>
				<td align='center' width='4%'>#</td>							
				<td align='center' width='9%'>N� Orden</td>
				<td align='center' width='11%'>Fecha</td>
				<td align='center' width='9%'>Hora</td>	
				<td align='center' width='11%'>Usuario</td>	
				<td align='center' width='10%'>C�digo</td>	
				<td align='center' width='5%'>Gesti�n</td>							 								 						 					 
				<td align='center' width='12%'>Monto</td>
				<td align='center' width='10%'>N� Boleta</td>								 
				<td align='center' width='19%'>Observaciones</td>
				<td width='2%'>&nbsp</td>								 						 				 							 						 
			</tr> 
			<tr height='10'>
				<td align='center' colspan='11'>								
				--------------------------------------------------------------------------------------------------------------------------------------<br />
				</td>
			</tr>";
			if ($check_imp == 0) {
				$content = $content."
				<tr height='20' style='font-family: Tahoma; font-size: 8pt;'>
				<td align='left'>&nbsp </td>
				<td align='center' valign='middle' colspan='9'>				 
				NO HAY ENTRADAS REGISTRADAS EN EL RANGO DE FECHA SELECCIONADO
				</td>
				<td align='left'>&nbsp </td>	
				</tr>";								 
} else {
   $i = 0;
   while ($i < $no_de_registros) {
		$j = $i+1;
		$content = $content."		
		<tr height='20' style='font-family: Tahoma; font-size: 8pt;'>					 					
		<td align='center'>$j </td>
		<td align='center'><font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $no_orden[$i] </font></td>
		<td align='center'><font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $fech_imp[$i]</font></td>
		<td align='center'><font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $hora_control[$i]</font></td>
		<td align='center'><font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $usuario[$i]</font></td>
		<td align='center'><font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $cod_cat_control[$i]</font></td>
		<td align='center'><font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $gestion_control[$i]</font></td>	
		<td align='center'><font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $cuota[$i] &nbsp </font></td>
		<td align='center'><font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $control[$i] </font></td>							 
		<td align='center'><font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $observ[$i] &nbsp </font></td>							 
		<td align='left'>&nbsp </td>	
		</tr>";
		$i++;
   }
   $content = $content."	
		<tr height='10'>
			<td align='center' colspan='11'>								
			--------------------------------------------------------------------------------------------------------------------------------------<br />
			</td>
		</tr>
		<tr height='20' style='font-family: Tahoma; font-size: 8pt;'>					 					
			<td align='left'>&nbsp </td>
			<td align='right' colspan='6'>
			<font style='font-family: Tahoma; font-size: 8pt; font-weight:bold'> Total </font>
			</td>
			<td align='center'>
			<font style='font-family: Tahoma; font-size: 8pt; font-weight:bold'> $monto_total &nbsp </font>
			</td>
			<td align='left' colspan='3'>&nbsp </td>	
		</tr>						
		<tr height='10'>
			<td align='center' colspan='11'>								
			--------------------------------------------------------------------------------------------------------------------------------------<br />
			</td>
		</tr>";
}
$content = $content."
	<tr height='20' style='font-family: Tahoma; font-size: 8pt;'>					 					
		<td align='left'>&nbsp </td>
		<td align='right' colspan='10'>
		<font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> </font>
		</td>
	</tr>																																			
	</table>				 			 
	</td>
	<td width='10%'> &nbsp </td>			
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