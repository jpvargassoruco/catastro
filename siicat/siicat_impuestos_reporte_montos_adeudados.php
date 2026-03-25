<?php


$imp_neto = $d10 = $impuestos = $mant_val = $interes = $mul_mora = $deb_for = $monto = $por_form = $total = $total_check = 0;

################################################################################
#-------------------- SELECCIONAR DATOS DE IMP_PAGADOS ------------------------#
################################################################################	
$sql="SELECT imp_neto, d10, mant_val, interes, mul_mora, deb_for, monto, por_form FROM imp_pagados WHERE fech_venc >= '$fecha_inicio' AND fech_venc <= '$fecha_final' AND fech_imp IS NULL";
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
				    $mant_val = $mant_val + $col_value;
			   } elseif ($i == 3) {
				    $interes = $interes + $col_value;
			   } elseif ($i == 4) {
				    $mul_mora = $mul_mora + $col_value;
			   } elseif ($i == 5) {
				    $deb_for = $deb_for + $col_value;
			   } elseif ($i == 6) {
				    $monto = $monto + $col_value;
			   } else {
				    $por_form = $por_form + $col_value;
						$i = -1;
			   }
			   $i++;	 
			}
   }
	 pg_free_result($result);
}
#echo "IMP_NETO: $imp_neto, D10: $d10, MANT_VAL: $mant_val";
$impuestos = $imp_neto - $d10;
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
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	

$filename = "C:/apache/htdocs/tmp/reporte".$user_id.".html";

################################################################################
#------------------------ PREPARAR CONTENIDO PARA IFRAME ----------------------#
################################################################################	
$content = " 
<div align='left'>
<table border='0' width='100%' height='500' style='font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'><font face='Tahoma' size='2'>
   <tr height='50px'>
      <td> &nbsp</td>
      <td align='right' valign='top' colspan='2'>
          <a href='javascript:print(this.document)'>
          <img border='0' src='http://$server/catastro_br/graphics/printer.png' width='20' height='20' title='Imprimir en hoja tamaño Carta'></a>
      </td>
   </tr>		 	 	 	  
	 <tr height='700px'>
	    <td width='10%'> &nbsp</td>
      <td align='center' valign='top' width='80%' bgcolor='#FFFFFF'>
			   <table border='0' width='100%' bgcolor='#FFFFFF' style='font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
				    <tr height='30'>
						   <td align='left'>&nbsp </td>
               <td align='left' valign='middle' colspan='2'>				 
                  <br /><br />SISTEMA DE CATASTRO URBANO DE BUEN RETIRO
							 </td>
               <td align='right' valign='middle'>				 
                  <br /><br />Fecha: $fecha2
							 </td>							 
							 <td align='left'>&nbsp </td>
						</tr>
				    <tr height='30'>
						   <td align='left'>&nbsp </td>
               <td align='left' valign='middle' colspan='2'>				 
                   GOBIERNO MUNICIPAL: SAN CARLOS, DISTRITO BUEN RETIRO
							 </td>
               <td align='right' valign='middle'>				 
                  Hora: $hora
							 </td>								 
							 <td align='left'>&nbsp </td>
						</tr>	
				    <tr height='40'>
						   <td align='left'>&nbsp </td>
               <td align='center' valign='middle' colspan='3' style='font-family: Tahoma; font-size: 10pt; font-weight:bold;'>				 
                   REPORTE: MONTOS ADEUDADOS (PAGOS ABIERTOS CON FECHA DE PAGO VENCIDA)
							 </td>
							 <td align='left'>&nbsp </td>
						</tr>	
				    <tr height='30'>
						   <td align='left'>&nbsp </td>
               <td align='center' valign='middle' colspan='3'>				 
                   RANGO DE FECHAS DEL  $fecha_inicio  AL  $fecha_final
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
if ($check_imp == 0) {
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
					  </tr>						
				    <tr height='10'>
               <td align='center' colspan='5'>								
				       --------------------------------------------------------------------------------------------------------------------------------------<br />
						   </td>
				    </tr>";
}
$content = $content."
						<tr height='20' style='font-family: Tahoma; font-size: 8pt;'>					 					
						   <td align='left'>&nbsp </td>
						   <td align='right' colspan='4'>
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