<?php

################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	

$filename = "C:/apache/htdocs/tmp/reporte$id_reporte.html";

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
          <img border='0' src='http://$server/siicat/graphics/printer.png' width='20' height='20' title='Imprimir en hoja tamaño Carta'></a>
      </td>
   </tr>		 	 	 	  
	 <tr height='700px'>
	    <td width='5%'> &nbsp</td>
      <td align='center' valign='top' width='90%' bgcolor='#FFFFFF'>
			   <table border='0' width='100%' bgcolor='#FFFFFF' style='font-family: Tahoma; font-size: 8pt; font-weight:normal;'>
				    <tr height='30'>
						   <td align='left'>&nbsp </td>
               <td align='left' valign='middle' colspan='5'>				 
                  <br /><br />SISTEMA INTEGRAL DE INGRESOS MUNICIPALES Y CATASTRO
							 </td>
               <td align='right' valign='middle' colspan='2'>				 
                  <br /><br />Fecha: $fecha2
							 </td>							 
							 <td align='left'>&nbsp </td>
						</tr>
				    <tr height='30'>
						   <td align='left'>&nbsp </td>
               <td align='left' valign='middle' colspan='6'>				 
                   GOBIERNO MUNICIPAL AUTONOMO DE $municipio<br />DISTRITO: $distrito
							 </td>
               <td align='right' valign='middle'>				 
                  Hora: $hora<br />&nbsp
							 </td>								 
							 <td align='left'>&nbsp </td>
						</tr>		
				    <tr height='30'>
						   <td align='left'>&nbsp </td>
               <td align='left' valign='middle' colspan='7' style='font-family: Tahoma; font-size: 10pt; font-weight:bold;'>				 
                   REPORTE: PAGOS INDIVIDUALES $titulo_reporte
							 </td>
							 <td align='left'>&nbsp </td>
						</tr>	
				    <tr height='30'>
						   <td align='left'>&nbsp </td>
               <td align='center' valign='middle' colspan='7'>				 
                   RANGO DE FECHA DEL &nbsp&nbsp $fecha_inicio &nbsp&nbsp AL &nbsp&nbsp $fecha_final
							 </td>
							 <td align='left'>&nbsp </td>
						</tr>																		
				    <tr height='10'>
               <td align='center' colspan='9'>								
				          <hr>
						   </td>
				    </tr> 						
						<tr height='20'>
						   <td width='2%'>
				          &nbsp
							 </td>	
						   <td width='5%' style='font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
				          #
							 </td>							 						
							 <td align='center' width='12%' style='font-family: Tahoma; font-size: 8pt; font-weight:bold;'>";
if ($tipo_reporte == "PI_TASAS2") {
   $content = $content."Fecha Imp.";
} else {
   $content = $content."Fecha Pago";
}
$content = $content."
							 </td>
							<td align='center' width='20%' style='font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
				          Lugar Pago
							 </td>	
							 <td align='center' width='17%' style='font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
				          No. de Boleta
							 </td>	
							 <td align='center' width='18%' style='font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
				          Concepto
							 </td>							 								 						 					 
							 <td align='center' width='11%' style='font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
				          Folio
							 </td>	
							 <td align='center' width='13%' style='font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
				          Monto
							 </td>								 						 								 					 	
						   <td width='2%'>
				          &nbsp
							 </td>								 						 				 							 						 
					  </tr> 
				    <tr height='10'>
               <td align='center' colspan='9'>								
				          <hr>
						   </td>
				    </tr>";
if ($check_imp == 0) {
   $content = $content."
						<tr height='20' style='font-family: Tahoma; font-size: 8pt;'>
						   <td align='left'>&nbsp </td>
               <td align='center' valign='middle' colspan='7'>				 
                  NO HAY ENTRADAS REGISTRADAS EN EL RANGO DE FECHA SELECCIONADO
							 </td>
							 <td align='left'>&nbsp </td>	
					  </tr>";								 
} else {
   $i = 0;
   while ($i < $no_de_impresiones) {
	    $numero = $i + 1;
   	  $content = $content."		
						<tr>					 					
						   <td align='left'>&nbsp</td>
							 <td align='right'>
							    <font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'>$numero &nbsp</font>
							 </td>
							 <td align='center'>
				          <font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $fech_pago[$i]</font>
							 </td>
							 <td align='center'>
				          <font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $nombre_banco[$i]</font>
							 </td>
							 <td align='center'>
				          <font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $no_boleta_banco[$i]</font>
							 </td>
							 <td align='center'>
				          <font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $concepto[$i] &nbsp </font>
							 </td>							 
							 <td align='center'>
				          <font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $folio[$i] &nbsp </font>
							 </td>	
							 <td align='center'>
				          <font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> $monto_banco[$i]</font>
							 </td>							 						 
						   <td align='left'>&nbsp </td>	
					  </tr>";
      $i++;
   }
   $content = $content."	
				    <tr height='10'>
               <td align='center' colspan='9'>								
				          <hr>
						   </td>
				    </tr>
						<tr height='20' style='font-family: Tahoma; font-size: 8pt;'>					 					
						   <td align='left'>&nbsp </td>
						   <td align='right' colspan='6'>
				          <font style='font-family: Tahoma; font-size: 8pt; font-weight:bold'> Total Bs.</font>
							 </td>
							 <td align='center'>
				          <font style='font-family: Tahoma; font-size: 8pt; font-weight:bold'> $monto_total &nbsp </font>
							 </td>
						   <td align='left'>&nbsp </td>	
					  </tr>						
				    <tr height='10'>
               <td align='center' colspan='9'>								
				          <hr>
						   </td>
				    </tr>";						
}
if  ($pagos_convalidados) {
$content = $content."
						<tr height='20' style='font-family: Tahoma; font-size: 8pt;'>					 					
						   <td align='left'>&nbsp </td>
						   <td align='left' colspan='7'>
				          <font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> 
									   Nota: <br />
									</font>
							 </td>
					  </tr>";
}
$content = $content."
						<tr height='20' style='font-family: Tahoma; font-size: 8pt;'>					 					
						   <td align='left'>&nbsp </td>
						   <td align='right' colspan='7'>
				          <font style='font-family: Tahoma; font-size: 8pt; font-weight:normal'> </font>
							 </td>
					  </tr>																																			
         </table>				 			 
			</td>
	    <td width='5%'> &nbsp </td>			
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