<?php
################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	

$filename = "C:/apache/htdocs/tmp/boleta".$cod_cat.".html";

################################################################################
#------------------------ PREPARAR CONTENIDO PARA GRABAR ----------------------#
################################################################################	
$content = " 
<div align='left'>
<table border='0' width='100%' height='600px' bgcolor='#FFFFFF'>
   <tr>
      <td width='5%'></td>
      <td width='90%' valign='top'>			
<table border='0' width='100%' style='font-family: Tahoma; font-size: 9pt'>
	 <tr height='20'>	 	 
      <td colspan='4'></td>
   </tr> 	   
	 <tr height='30'>
      <td align='left' colspan='2'>
         Gobierno Municipal de $municipio - Distrito $distrito
      </td>		 
      <td align='right'>
         Forma Pago:
      </td>				
      <td align='left'>
        &nbsp <b>$forma_pago</b>
      </td>		
   </tr>
   <tr height='30' style='font-family: Tahoma; font-size: 12pt'>
      <td align='center' colspan='4'>
        <b>Boleta Resumen Liquidación del I.P.B.I.</b>
      </td>	
   </tr>	
	 <tr height='10'>	 	 
      <td colspan='4'></td>
   </tr> 
	 <tr height='25'>
      <td align='right' width='10%'>
         &nbsp Cód. Cat.:
      </td>		 
      <td align='left' width='55%'>
         &nbsp <b>$cod_geo/$cod_cat</b>
      </td>	
      <td align='right' width='10%'>
         Gestión:
      </td>		 
      <td align='left' width='25%'>
         &nbsp <b>$gestion</b>
      </td>							  
   </tr> 	 		 				 
	 <tr>
      <td align='right'>
         PMC:
      </td>		 
      <td align='left'>
        &nbsp <b>$pmc</b>
      </td>	
      <td align='right'>
         Fecha:
      </td>		 
      <td align='left'>
         &nbsp <b>$fecha2</b>
      </td>							  
   </tr>  		 
	 <tr>
      <td align='right'>
        Nombre:
      </td>		 
      <td align='left'>
        &nbsp <b>$titular</b>
      </td>	
	    <td align='right'>
        Hora:
      </td>		 
      <td align='left'>
        &nbsp <b>$hora</b>
      </td>							  
   </tr>  
	 <tr>
      <td align='right'>
        Dirección:
      </td>		 
      <td align='left'>
        &nbsp <b>$direccion</b>
      </td>	
	    <td align='right'>
        Usuario:
      </td>		 
      <td align='left'>
        &nbsp <b>$user_id</b>
      </td>							  
   </tr>
   <tr height='30'>
      <td align='center' colspan='4'>								
			   --------------------------------------------------------------------------------------------------------------------------------------<br />
      </td>
	 </tr> 
   </tr> 
</table>
<table border='0' width='100%' style='font-family: Tahoma; font-size: 9pt'>	 	   	
	 <tr height='5'>	 	 
      <td colspan='5'></td>
   </tr> 					 
	 <tr>	 	 
      <td align='right' width='20%'>
         Tipo Inmueble:
      </td>
      <td align='right' width='15%'>
         &nbsp $tp_inmu
      </td>	
      <td align='right' width='35%'>
         Impuesto Neto:
      </td>	
      <td align='right' width='15%'>
         &nbsp $imp_neto
      </td>	
      <td align='center' width='15%'>
         &nbsp
      </td>				
   </tr> 	
	 <tr>	 	 
      <td align='right'>
         Superficie Terreno:
      </td>
      <td align='right'>
         &nbsp $sup_terr
      </td>	
      <td align='right'>
         Mant. Valor:
      </td>	
      <td align='right'>
         &nbsp $mant_val
      </td>	
      <td align='center'>
         &nbsp
      </td>				
   </tr> 	
	 <tr>	 	 
      <td align='right'>
         Valor Tablas:
      </td>
      <td align='right'>
         &nbsp $val_m2_terr
      </td>	
      <td align='right'>
         Interes:
      </td>	
      <td align='right'>
         &nbsp $interes
      </td>	
      <td align='center'>
         &nbsp
      </td>				
   </tr> 	
	 <tr>	 	 
      <td align='right'>
         Servicios:
      </td>
      <td align='right'>
         &nbsp $factor
      </td>	
      <td align='right'>
         Multa Mora:
      </td>	
      <td align='right'>
         &nbsp $multa_mora
      </td>	
      <td align='center'>
         &nbsp
      </td>				
   </tr> 		  
	 <tr>	 	 
      <td align='right'>
         Valor Terreno:
      </td>
      <td align='right'>
         &nbsp $avaluo_terr
      </td>	
      <td align='right'>
         Deberes Form.:
      </td>	
      <td align='right'>
         &nbsp $multa_incum
      </td>	
      <td align='center'>
         &nbsp
      </td>				
   </tr> 	
	 <tr>	 	 
      <td align='right'>
         Calidad Const.:
      </td>
      <td align='right'>
         &nbsp $val_m2_const
      </td>	
      <td align='right'>
         Sanción Adm.:
      </td>	
      <td align='right'>
         &nbsp $multa_admin
      </td>	
      <td align='center'>
         &nbsp
      </td>				
   </tr> 	 	 
	 <tr>	 	 
      <td align='right'>
         Valor Construcción:
      </td>
      <td align='right'>
         &nbsp $avaluo_const
      </td>	
      <td align='right'>
         Por Formulario:
      </td>	
      <td align='right'>
         &nbsp $por_form
      </td>	
      <td align='center'>
         &nbsp
      </td>				
   </tr>
	 <tr>	 	 
      <td align='right'>
         Depreciación:
      </td>
      <td align='right'>
         &nbsp $antig
      </td>	
      <td align='right'>
         Pago Termino (-$tasa_descuento %):
      </td>	
      <td align='right'>
         &nbsp $descuento
      </td>	
      <td align='center'>
         &nbsp
      </td>				
   </tr> 		  	
	 <tr>	 	 
      <td align='right' rowspan='2'>
         Base Imponible:
      </td>
      <td align='right' rowspan='2'>
         &nbsp $avaluo_total
      </td>	
      <td align='right'>
         Otros Descuentos:
      </td>	
      <td align='right'>
         &nbsp $descont
      </td>	
      <td align='center'>
         &nbsp
      </td>				
   </tr> 
	 <tr>	 	 
      <td align='right'>
         <b>Total a Pagar:</b>
      </td>	
      <td align='right'>
         &nbsp <b>$total_a_pagar</b>
      </td>	
      <td align='center'>
         &nbsp
      </td>				
   </tr> 	 	 
	 <tr>	 	 
      <td align='right'>
         Fecha Vencimiento:
      </td>
      <td align='right'>
         &nbsp $fecha_venc
      </td>	
      <td align='right'>
         Pagos Anteriores:
      </td>	
      <td align='right'>
         &nbsp $sal_favor
      </td>	
      <td align='center'>
         &nbsp
      </td>				
   </tr> 
	 <tr>	 	 
      <td align='right'>
         Fecha Liquidación:
      </td>
      <td align='right'>
         &nbsp $fecha_imp
      </td>	
      <td align='right'>
         Otros Créditos:
      </td>	
      <td align='right'>
         &nbsp $credito
      </td>	
      <td align='center'>
         &nbsp
      </td>				
   </tr>
	 <tr>	 	 
      <td align='right'>
         Cambio UFV:
      </td>
      <td align='right'>
         &nbsp $cotiufv
      </td>	
      <td align='right'>
         Saldo Favor:
      </td>	
      <td align='right'>
         &nbsp $sal_favor
      </td>	
      <td align='center'>
         &nbsp
      </td>				
   </tr>
   <tr height='10'>
      <td align='center' colspan='6'>								
			   --------------------------------------------------------------------------------------------------------------------------------------<br />
      </td>
	 </tr> 
	 <tr height='10'>
      <td align='center' colspan='6' style='font-family: Tahoma; font-size: 7pt'>			
	       $texto_exencion 
      </td>
	 </tr> 	  	    	  	 
   <tr height='35'>
      <td align='center' colspan='5'>
      <a href='javascript:print(this.document)'>
      <img border='0' src='http://localhost/catastro_br/graphics/printer.png' width='22' height='22'></a>
      </td>	 
   </tr>
</table>
      </td>
      <td width='5%'></td>
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