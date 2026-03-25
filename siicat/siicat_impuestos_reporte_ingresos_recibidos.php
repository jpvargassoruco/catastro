<?php

################################################################################
#----------------------- PREPARAR FECHAS PARA LEER SIIM -----------------------#
################################################################################	

$stringlength = strlen($fecha_inicio);								 
$i = $j = 0;	  
while ($i <= strlen($fecha_inicio)) {	
   $char = substr($fecha_inicio, $i-1, 1);
   if (($char == "/") AND ($j == 0)) {
	    $dia_fecha_inicio = substr($fecha_inicio, 0, $i-1);
			$dia_fecha_inicio = (int) $dia_fecha_inicio; 
			if ($dia_fecha_inicio < 10) {
			   $dia_fecha_inicio = "0".$dia_fecha_inicio;
			}   
			$corte = $i;
			$j++;
	 } elseif (($char == "/") AND ($j == 1)) {
	    $mes_fecha_inicio = substr($fecha_inicio, $corte, $i-$corte-1);
			$mes_fecha_inicio = (int) $mes_fecha_inicio;
			if ($mes_fecha_inicio < 10) {
			   $mes_fecha_inicio = "0".$mes_fecha_inicio;
			}			
	 }
	 $i++;	
}
$ano_fecha_inicio = substr($fecha_inicio, $stringlength-4, 4);
#echo "ANO: $ano_fecha_inicio, MES: $mes_fecha_inicio, DIA: $dia_fecha_inicio<br>";
$fecha_inicio_temp = $ano_fecha_inicio.$mes_fecha_inicio.$dia_fecha_inicio;

$stringlength = strlen($fecha_final);								 
$i = $j = 0;	  
while ($i <= strlen($fecha_final)) {	
   $char = substr($fecha_final, $i-1, 1);
   if (($char == "/") AND ($j == 0)) {
	    $dia_fecha_final = substr($fecha_final, 0, $i-1);
			$dia_fecha_final = (int) $dia_fecha_final;			
			if ($dia_fecha_final < 10) {
			   $dia_fecha_final = "0".$dia_fecha_final;
			}   
			$corte = $i;
			$j++;
	 } elseif (($char == "/") AND ($j == 1)) {
	    $mes_fecha_final = substr($fecha_final, $corte, $i-$corte-1);
			$mes_fecha_final = (int) $mes_fecha_final;			
			if ($mes_fecha_final < 10) {			
			   $mes_fecha_final = "0".$mes_fecha_final;
			}			
	 }
	 $i++;	
}
$ano_fecha_final = substr($fecha_final, $stringlength-4, 4);
#echo "ANO: $ano_fecha_final, MES: $mes_fecha_final, DIA: $dia_fecha_final";
$fecha_final_temp = $ano_fecha_final.$mes_fecha_final.$dia_fecha_final; 
################################################################################
#----------------------- SELECCIONAR DATOS DE SATLIQIN ------------------------#
################################################################################	
$sql="SELECT DISTINCT no_orden, imp_neto, d10, mant_val, interes, mul_mora, deb_for, monto, por_form FROM satliqin WHERE pagado >= '$fecha_inicio_temp' AND pagado <= '$fecha_final_temp'";
$check_cuota = pg_num_rows(pg_query($sql));
$imp_neto = $d10 = $impuestos = $mant_val = $interes = $mul_mora = $deb_for = $monto = $por_form = $total = $total_check = 0;
if ($check_cuota > 0) {		
   $result=pg_query($sql);
   $i = $j = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
	       if ($i == 0) {
				    $no_orden = $col_value;			
	       } elseif ($i == 1) {
				    $imp_neto = $imp_neto + $col_value;
			   } elseif ($i == 2) {
				    $d10 = $d10 + $col_value;
			   } elseif ($i == 3) {
				    $mant_val = $mant_val + $col_value;
			   } elseif ($i == 4) {
				    $interes = $interes + $col_value;
			   } elseif ($i == 5) {
				    $mul_mora = $mul_mora + $col_value;
			   } elseif ($i == 6) {
				    $deb_for = $deb_for + $col_value;
			   } elseif ($i == 7) {
				    $monto = $monto + $col_value;
			   } else {
				    $por_form = $por_form + $col_value;
						$i = -1;
			   }
			   $i++;	 
			}
   }
	# $impuestos = $imp_neto - $d10;
	# $total = $impuestos + $mant_val + $interes + $mul_mora + $deb_for + $por_form;
	# $total_check = $monto;
	 pg_free_result($result);
} else {
   $imp_neto = $d10 = $descont = $impuestos = $mant_val = $interes = $mul_mora = $deb_for = $monto = $por_form = $total = $total_check = 0;
}	 
################################################################################
#--------------- SELECCIONAR PAGOS AL CONTADO DE IMP_PAGADOS ------------------#
################################################################################	



if ($mostrartodo == "Todos") {
	$sql="SELECT imp_neto, d10, descont, mant_val, interes, mul_mora, deb_for, monto, cuota, por_form FROM imp_pagados WHERE fech_imp >= '$fecha_inicio' AND fech_imp <= '$fecha_final' AND forma_pago = 'CONTADO' AND cuota > '0'";
} else {
	$sql="SELECT imp_neto, d10, descont, mant_val, interes, mul_mora, deb_for, monto, cuota, por_form FROM imp_pagados WHERE cod_geo = '$cod_mun' AND fech_imp >= '$fecha_inicio' AND fech_imp <= '$fecha_final' AND forma_pago = 'CONTADO' AND cuota > '0'";
}



$check_imp = pg_num_rows(pg_query($sql));
if ($check_imp > 0) {		
   $result=pg_query($sql);
   $i = $j = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
	       if ($i == 0) {
				    $imp_neto = $imp_neto + $col_value;
			   } elseif ($i == 1) {
				    $d10 = $d10 + $col_value;
			   } elseif ($i == 2) {
				    $descont = $descont + $col_value;						
			   } elseif ($i == 3) {
				    $mant_val = $mant_val + $col_value;
			   } elseif ($i == 4) {
				    $interes = $interes + $col_value;
			   } elseif ($i == 5) {
				    $mul_mora = $mul_mora + $col_value;
			   } elseif ($i == 6) {
				    $deb_for = $deb_for + $col_value;
			   } elseif ($i == 7) {
				    $monto = $monto + $col_value;
			   } elseif ($i == 8) {
				    $cuota = $cuota + $col_value;						
			   } else {
				    $por_form = $por_form + $col_value;
						$i = -1;
			   }
			   $i++;	 
			}
   }
	 pg_free_result($result);
}

################################################################################
#---------------- SELECCIONAR PAGOS VALIDADOS DE IMP_PAGADOS ------------------#
################################################################################	
#$sql="SELECT cuota FROM imp_pagados WHERE fech_imp >= '$fecha_inicio' AND fech_imp <= '$fecha_final' AND forma_pago = 'VALIDADO' AND cuota > '0'";
#echo $sql;
#$check_validado = pg_num_rows(pg_query($sql));
#if ($check_validado > 0) {		
#   $result=pg_query($sql);
#   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
#      foreach ($line as $col_value) {	 
#			$cuota = $cuota + $col_value;
#			$imp_neto = $imp_neto + $col_value - 2;
#			$por_form = $por_form + 2;
#		}
#   }
#	 pg_free_result($result);	 
#}
#echo "IMP_NETO: $imp_neto, D10: $d10, MANT_VAL: $mant_val, CUOTA: $cuota";
$impuestos = $imp_neto - $d10 - $descont;
$total = $impuestos + $mant_val + $interes + $mul_mora + $deb_for + $por_form;
$total_check = $monto;
################################################################################
#------------------------------- RELLENAR LISTA -------------------------------#
################################################################################	
$i = 0;
if ($por_form > 0) {
   $rubro[$i] = "12000";
	 $descripcion[$i] = "VENTA DE BIENES Y SERVICIOS DE LAS ADMINISTRACIONES";
	 $monto_tabla[$i] = $por_form;
	 $i++;
   $rubro[$i] = "12200";
	 $descripcion[$i] = "&nbsp&nbsp Venta de Servicios de las Administraciones Públicas";
	 $monto_tabla[$i] = $por_form;	 
	 $i++;
}
if ($impuestos > 0) {
   $rubro[$i] = "13000";
	 $descripcion[$i] = "INGRESOS TRIBUTARIOS";
	 $monto_tabla[$i] = $impuestos + $mant_val;	 
	 $i++;
   $rubro[$i] = "13300";
	 $descripcion[$i] = "&nbsp&nbsp Impuestos Directos Municipales";
	 $monto_tabla[$i] = $impuestos + $mant_val;	 
	 $i++;
   $rubro[$i] = "13310";
	 $descripcion[$i] = "&nbsp&nbsp&nbsp&nbsp Impuesto a la Propiedad de Bienes Inmuebles";
	 $monto_tabla[$i] = $impuestos + $mant_val;	 
	 $i++;	 
}
if (($mul_mora > 0) OR ($deb_for > 0)) {
   $rubro[$i] = "15000";
	 $descripcion[$i] = "OTROS INGRESOS";
	 $monto_tabla[$i] = $mul_mora + $deb_for;
	 $i++;
   $rubro[$i] = "15500";
	 $descripcion[$i] = "&nbsp&nbsp Multas";
	 $monto_tabla[$i] = $mul_mora + $deb_for;	 
	 $i++;
}	
if ($interes > 0) {
   $rubro[$i] = "16000";
	 $descripcion[$i] = "INTERESES Y OTRAS RENTAS DE LA PROPIEDAD";
	 $monto_tabla[$i] = $interes;
	 $i++;
   $rubro[$i] = "16100";
	 $descripcion[$i] = "&nbsp&nbsp Intereses";
	 $monto_tabla[$i] = $interes;
	 $i++;	 
   $rubro[$i] = "16130";
	 $descripcion[$i] = "&nbsp&nbsp&nbsp&nbsp Otros Intereses";
	 $monto_tabla[$i] = $interes;	 
	 $i++;
}	
$no_de_rubros = $i; 
################################################################################
#----------------------------------- NOTA -------------------------------------#
################################################################################	
$sql="SELECT nota_list FROM imp_base";
$result = pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$nota_listado_por_rubro = utf8_decode ($info['nota_list']);
pg_free_result($result);	
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
          <img border='0' src='http://$server/$folder/graphics/printer.png' width='20' height='20' title='Imprimir en hoja tamaño Carta'></a>
      </td>
   </tr>		 	 	 	  
	 <tr height='700px'>
	    <td width='10%'> &nbsp</td>
      <td align='center' valign='top' width='80%' bgcolor='#FFFFFF'>
			   <table border='0' width='100%' bgcolor='#FFFFFF' style='font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
				    <tr height='30'>
						   <td align='left'>&nbsp </td>
               <td align='left' valign='middle' colspan='2'>				 
                  <br /><br />SISTEMA DE CATASTRO URBANO DE $dom_ciu_MAYUS
							 </td>
               <td align='right' valign='middle'>				 
                  <br /><br />Fecha: $fecha2
							 </td>							 
							 <td align='left'>&nbsp </td>
						</tr>
				    <tr height='30'>
						   <td align='left'>&nbsp </td>
               <td align='left' valign='middle' colspan='2'>				 
                   GOBIERNO MUNICIPAL: $dom_ciu_MAYUS, DISTRITO $dom_ciu_default
							 </td>
               <td align='right' valign='middle'>				 
                  Hora: $hora
							 </td>								 
							 <td align='left'>&nbsp </td>
						</tr>	
				    <tr height='30'>
						   <td align='left'>&nbsp </td>
               <td align='center' valign='middle' colspan='3' style='font-family: Tahoma; font-size: 10pt; font-weight:bold;'>				 
                   REPORTE: LISTADO MAYORIZADO POR RUBROS DE RECAUDACION
							 </td>
							 <td align='left'>&nbsp </td>
						</tr>	
				    <tr height='30'>
						   <td align='left'>&nbsp </td>
               <td align='center' valign='middle' colspan='3'>				 
                   RANGO DE FECHAS DEL &nbsp&nbsp $dia_fecha_inicio/$mes_fecha_inicio/$ano_fecha_inicio &nbsp&nbsp AL &nbsp&nbsp $dia_fecha_final/$mes_fecha_final/$ano_fecha_final
							 </td>
							 <td align='left'>&nbsp </td>
						</tr>																		
				    <tr height='10'>
               <td align='center' colspan='5'>								
				       --------------------------------------------------------------------------------------------------------------------------------------<br />
						   </td>
				    </tr> 						
						<tr height='20'>
						   <td width='5%'>
				          &nbsp
							 </td>							
						   <td align='center' width='10%'>
				          Rubro
							 </td>
							 <td align='center' width='63%'>
				          Descripción
							 </td>
							 <td align='center' width='17%'>
				          Monto en Bs.
							 </td>	
						   <td width='5%'>
				          &nbsp
							 </td>								 						 				 							 						 
					  </tr> 
				    <tr height='10'>
               <td align='center' colspan='5'>								
				       --------------------------------------------------------------------------------------------------------------------------------------<br />
						   </td>
				    </tr>";
if (($check_cuota == 0) AND ($check_imp == 0)) {
   $content = $content."
						<tr height='20' style='font-family: Tahoma; font-size: 8pt;'>
						   <td align='left'>&nbsp </td>
               <td align='center' valign='middle' colspan='3'>				 
                  NO HAY ENTRADAS REGISTRADAS EN EL RANGO DE FECHA SELECCIONADO
							 </td>
							 <td align='left'>&nbsp </td>	
					  </tr>";								 
} else {
   $i = 0;
   while ($i < $no_de_rubros) {
   	  $content = $content."		
						<tr height='20' style='font-family: Tahoma; font-size: 8pt;'>					 					
						   <td align='left'>&nbsp </td>
						   <td align='left'>
				          <font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $rubro[$i] </font>
							 </td>
							 <td align='left'>
				          <font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $descripcion[$i]</font>
							 </td>
							 <td align='right'>
				          <font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $monto_tabla[$i] &nbsp </font>
							 </td>
						   <td align='left'>&nbsp </td>	
					  </tr>";
      $i++;
   }
   $content = $content."	
				    <tr height='10'>
               <td align='center' colspan='5'>								
				       --------------------------------------------------------------------------------------------------------------------------------------<br />
						   </td>
				    </tr>
						<tr height='20' style='font-family: Tahoma; font-size: 8pt;'>					 					
						   <td align='left'>&nbsp </td>
						   <td align='right' colspan='2'>
				          <font style='font-family: Tahoma; font-size: 8pt; font-weight:bold'> Total </font>
							 </td>
							 <td align='right'>
				          <font style='font-family: Tahoma; font-size: 8pt; font-weight:bold'> $total &nbsp </font>
							 </td>
						   <td align='left'>&nbsp </td>	
					  </tr>";						
}
$content = $content."
				    <tr height='10'>
               <td align='center' colspan='5'>								
				       --------------------------------------------------------------------------------------------------------------------------------------<br />
						   </td>
				    </tr>
						<tr height='20' style='font-family: Tahoma; font-size: 8pt;'>					 					
						   <td align='left' colspan='5'> &nbsp </td>
					  </tr>							
						<tr height='20' style='font-family: Tahoma; font-size: 8pt;'>					 					
						   <td align='left' colspan='5'>
				          <font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $nota_listado_por_rubro</font>
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