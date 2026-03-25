<?php
################################################################################
#                     EL SIGUIENTE CODIGO FUE CREADO POR:                      #
#        MERCATORGIS - SISTEMAS DE INFORMACION GEOGRAFICA PERSONALIZADOS       #
#     ***** http://www.mercatorgis.com  *****  info@mercatorgis.com *****      #
################################################################################

################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	
$filename = "C:/apache2/htdocs/tmp/boleta_trans_".$cod_cat.".html";

################################################################################
#------------------------------- PREPARAR DATOS -------------------------------#
################################################################################	

$monto_en_letras = numeros_a_letras($total_a_pagar);
if (strlen($monto_en_letras) > 36) {
   if (strlen($monto_en_letras) < 41) { 
      $monto_en_letras1 = $monto_en_letras;
      $monto_en_letras2 = "00/100 BOLIVIANOS";	 
	 } else {
      $monto_en_letras1 = substr($monto_en_letras, 0, 41);
      $monto_en_letras2 = substr($monto_en_letras, 41, strlen($monto_en_letras)-41)." 00/100 BOLIVIANOS";
	 }
} else {
   $monto_en_letras1 = $monto_en_letras." 00/100 BOLIVIANOS";
   $monto_en_letras2 = "";
}	
$importe_literal = $monto_en_letras1;
### VARIABLES BANCO
$efectivo = "X";
$cheque = "-";
$cheque_otro_banco = "-";
################################################################################
#------------------------------- OTROS DATOS  ---------------------------------#
################################################################################
/*
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
*/
$nro_form = "-";
$orig = "x";
$rect = "-";
$no_control = 0;
$multa_incumpl = $multa_incump_ufv * $ufv_actual;
$interes = $interes * $ufv_actual;
$mant_val = $total_a_pagar-$monto_det-$multa_incumpl-$interes;
########################################
#----------- ANCHO DE FILAS  ----------#
########################################	
$ancho_primera_fila = 32;
$ancho_primera_fila_banco = 30;
$ancho_primera_fila_alcaldia = 32;
$ancho_filas_en_medio = 9;
$ancho_entre_contrib_banco = 50;  
$ancho_entre_banco_alcaldia = 30; 
################################################################################
#------------------------ PREPARAR CONTENIDO PARA GRABAR ----------------------#
################################################################################	<font face='Tahoma' size='2'>
$content = " 
<div align='left'>
<!-- **************************** CONTRIBUYENTE **************************** --> 
<table border='0' width='100%'>
<!-- Fila 1 Form 1690 -->
   <tr height='$ancho_primera_fila'>
      <td rowspan='3' width='18%' bgcolor='#E9E9E9'>
        &nbsp
      </td>		 
      <td bgcolor='#E9E9E9'>
        &nbsp
      </td>				
      <td colspan='2' bgcolor='#E9E9E9'>
        &nbsp
      </td>	
      <td align='center' valign='bottom' style='font-family: Arial; font-size: 8pt'>
        $folio
      </td>			
   </tr>
<!-- Fila 2 Periodo -->	 
   <tr style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>
      <td width='8%' bgcolor='#E9E9E9'>
         &nbsp
      </td>		 
      <td width='29%' bgcolor='#E9E9E9'>
         &nbsp
      </td>				
      <td width='27%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='18%' bgcolor='#E9E9E9'>
         &nbsp
      </td>			
   </tr>	  
   <tr style='font-family: Arial; font-size: 9pt'>
      <td align='center' width='7%'>
        $periodo
      </td>		 
      <td align='center' width='30%'>
        $tipo_de_inmueble
      </td>				
      <td align='center' width='27%'>
        $cod_cat
      </td>
      <td align='center' width='18%'>
        $min_fech
      </td>			
   </tr>	 
</table>
<!-- Fila 3 Fecha Posesion -->
<table border='0' width='100%'>	 		 
	 <tr style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>	 	 
      <td width='18%' align='left' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='12%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='14%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='19%' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
      <td  width='22%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='15%' bgcolor='#E9E9E9'>
         &nbsp
      </td>		
   </tr> 	
	 <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='center'>
         &nbsp&nbsp $fecha_posesion
      </td>
      <td align='center'>
         $cod_alc
      </td>
      <td align='center'>
         $nit
      </td>
      <td align='center'>
         $ciudad
      </td>	
      <td align='center'>
         $fecha_emision
      </td>
      <td align='center'>
         $pmc
      </td>			
   </tr> 
</table>
<!-- Fila 4 Ubicación del Inmueble -->
<table border='0' width='100%'>		 			 
	 <tr style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>	 	 
      <td width='15%' rowspan='2' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='34%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='24%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='8%' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
      <td width='7%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='6%' bgcolor='#E9E9E9'>
         &nbsp
      </td>		
      <td width='6%' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
   </tr> 		
	 <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='left'>
         &nbsp $direccion
      </td>
      <td align='left'>
         &nbsp $barrio
      </td>
      <td align='center'>
         $puerta
      </td>
      <td align='center'>
         $bloque
      </td>
      <td align='center'>
         $piso
      </td>
      <td align='center'>
         $dpto
      </td>															
   </tr> 
</table>
<!-- Fila 5 Vendedor o Cedente -->
<table border='0' width='100%'>		  	 	    
	 <tr style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>	 	 
      <td width='15%' rowspan='2' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td  width='37%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='8%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='30%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='10%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
   </tr> 
	 <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='left'>
         &nbsp $vendedor
      </td>
      <td align='center'>
         100
      </td>
      <td align='left'>
         &nbsp $dom_dir
      </td>
      <td align='center'>
         $dom_num
      </td>						
   </tr> 
<!-- Fila 6 Comprador o Cesionario -->	 
	 <tr style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>	 	 
      <td align='left' rowspan='2' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='center' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         &nbsp
      </td>
   </tr> 
	 <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='left'>
         &nbsp $comprador
      </td>
      <td align='center'>
         100
      </td>
      <td align='left'>
         &nbsp $dom_dir_comp
      </td>
      <td align='center'>
         $cod_pmc_comp
      </td>						
   </tr> 	 
</table>
<!-- Fila 7 Valuación del Inmueble -->
<table border='0' width='100%'>			  	  
   <tr style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>
      <td width='15%' rowspan='2' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='16%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='14%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='18%' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
      <td width='14%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='23%' bgcolor='#E9E9E9'>
         &nbsp
      </td>																	
   </tr>
   <tr style='font-family: Arial; font-size: 9pt'>
      <td align='center'>
         $valor_cat
      </td>
      <td align='center'>
         $valor_min
      </td>
      <td align='center'>
         $valor_doc_priv
      </td>	
      <td align='center'>
         $valor_usd
      </td>
      <td align='center'>
         $base_imp
      </td>																	
   </tr>	
</table>
<!-- Fila 8 Cálculo del Impuesto -->
<table border='0' width='100%'>			  
   <tr style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>	 	 
      <td width='15%' rowspan='2' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='12%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='12%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='12%' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
      <td width='12%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='11%' bgcolor='#E9E9E9'>
         &nbsp			
      </td>
      <td width='11%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='15%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
   </tr> 
   <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='center'>
         $monto_det
      </td>
      <td align='center'>
         $saldo_a_favor
      </td>
      <td align='center'>
         $pagos_ant
      </td>
      <td align='center'>
         $mant_val
      </td>	
      <td align='center'>
         $interes
      </td>
      <td align='center'>
			   $multa_mora
      </td>
      <td align='center'>
         $multa_incumpl
      </td>
   </tr>
</table>
<!-- Fila 9 Número de Control -->
<table border='0' width='100%'>			  
   <tr style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>	 	 
      <td width='15%' rowspan='2' align='center' valign='center' bgcolor='#E9E9E9'>
         <a href='javascript:print(this.document)'>
         <img border='0' src='http://$server/siicat/graphics/printer.png' width='22' height='22' title='Imprimir en formato OFICIO'></a>
      </td>
      <td width='15%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='15%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='9%' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
      <td width='14%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='8%' bgcolor='#E9E9E9'>
         &nbsp			
      </td>
      <td width='7%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='18%' rowspan='5' bgcolor='#E9E9E9'>
         &nbsp
      </td>
   </tr> 
   <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='center'>
         $no_control
      </td>
      <td align='center'>
         $fecha_venc
      </td>
      <td align='center'>
         $cambio_usd
      </td>
      <td align='center'>
         $nro_form
      </td>	
      <td align='center'>
         $orig
      </td>
      <td align='center'>
			   $rect
      </td>
   </tr>
<!-- Fila 10 Literal -->	 
   <tr>	 	 
      <td colspan='5' rowspan='2' valign='bottom' style='font-family: Arial; font-size: 9pt'>
         &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp $importe_literal
      </td>
      <td colspan='2' bgcolor='#E9E9E9' style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>
         &nbsp
      </td>
   </tr> 
   <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td colspan='2' align='right'>
         $total_a_pagar &nbsp&nbsp&nbsp&nbsp
      </td>
   </tr>	
<!-- Fila 11 Juro y Declaro -->	 
   <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td colspan='3' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td colspan='3' bgcolor='#E9E9E9'>
         &nbsp
      </td>			
   </tr> 	  
</table>
<!-- Fila 12 ESPACIO ENTRE CONTRIBUYENTE Y BANCO -->
<table border='0' width='100%'>
   <tr height='$ancho_entre_contrib_banco'>
      <td align='center'>
        &nbsp
      </td>		 		
   </tr> 	  
</table>
<!-- ******************************* BANCO ********************************* --> 
<table border='0' width='100%'>
<!-- Fila 13 Form 1690 -->
   <tr height='$ancho_primera_fila_banco'>
      <td rowspan='3' width='18%' bgcolor='#E9E9E9'>
        &nbsp
      </td>		 
      <td bgcolor='#E9E9E9'>
        &nbsp
      </td>
      <td colspan='3' bgcolor='#E9E9E9'>
        &nbsp
      </td>			
      <td bgcolor='#E9E9E9'>
        &nbsp
      </td>			
   </tr>
<!-- Fila 14 Periodo -->	 
   <tr style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>
      <td width='8%' bgcolor='#E9E9E9'>
         &nbsp
      </td>		 
      <td width='15%' bgcolor='#E9E9E9'>
         &nbsp
      </td>				
      <td width='19%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='15%' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
      <td width='25%' bgcolor='#E9E9E9'>
         &nbsp
      </td>						
   </tr>	  
   <tr style='font-family: Arial; font-size: 9pt'>
      <td align='center'>
        $periodo
      </td>		 
      <td align='center'>
        $folio
      </td>	
      <td align='center'>
        $cod_cat
      </td>						
      <td align='center'>
        $cod_alc
      </td>
      <td align='center'>
        $ciudad
      </td>			
   </tr>	 
</table>
<!-- Fila 15 Banco: Ubicación del Inmueble -->
<table border='0' width='100%'>		 			 
	 <tr style='font-family: Arial; font-size: 3 pt'>	 	 
      <td width='15%' rowspan='2' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='34%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='25%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='6%' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
      <td width='6%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='7%' bgcolor='#E9E9E9'>
         &nbsp
      </td>		
      <td width='7%' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
   </tr> 		
	 <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='left'>
         &nbsp $direccion
      </td>
      <td align='left'>
         &nbsp $barrio
      </td>
      <td align='center'>
         $puerta
      </td>
      <td align='center'>
         $bloque
      </td>
      <td align='center'>
         $piso
      </td>
      <td align='center'>
         $dpto
      </td>															
   </tr> 
</table>
<!-- Fila 16 Banco: Fecha de Vencimiento -->
<table border='0' width='100%'>		  	 	    
	 <tr style='font-family: Arial; font-size: 4pt'>	 	 
      <td width='19%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='11%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='15%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='15%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='20%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='20%' bgcolor='#E9E9E9'>
         &nbsp
      </td>			
   </tr> 
	 <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='center'>
         &nbsp $fecha_venc
      </td>
      <td align='center'>
         $cambio_usd
      </td>
      <td align='center'>
         $fecha_emision
      </td>
      <td align='center'>
         $efectivo
      </td>	
      <td align='center'>
         $cheque
      </td>
      <td align='center'>
         $cheque_otro_banco
      </td>									
   </tr> 
</table>	 
<!-- Fila 17 Banco: Importe a Pagar -->	 
<table border='0' width='100%'>
   <tr style='font-family: Arial; font-size: 3pt'>
      <td>
        &nbsp
      </td>		 		
   </tr> 	
	 <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td width='12%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='76%' align='left' valign='bottom'>
         &nbsp $importe_literal
      </td>
      <td width='12%' align='center' valign='bottom'>
         $total_a_pagar
      </td>
   </tr>  	 
</table>
<!-- Fila 18 ESPACIO ENTRE BANCO Y ALCALDIA -->
<table border='0' width='100%'>
   <tr height='$ancho_entre_banco_alcaldia'>
      <td align='center'>
        &nbsp
      </td>		 		
   </tr> 	  
</table>
<!-- ***************************** ALCALDIA ******************************** --> 
<table border='0' width='100%'>
<!-- Fila 1 Form 1690 -->
   <tr height='$ancho_primera_fila_alcaldia'>
      <td rowspan='3' width='18%' bgcolor='#E9E9E9'>
        &nbsp
      </td>		 
      <td bgcolor='#E9E9E9'>
        &nbsp
      </td>				
      <td colspan='2' bgcolor='#E9E9E9'>
        &nbsp
      </td>	
      <td align='center' valign='bottom' style='font-family: Arial; font-size: 8pt'>
        $folio
      </td>			
   </tr> 
<!-- Copia Fila 2 Periodo -->	 
   <tr style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>
      <td width='8%' bgcolor='#E9E9E9'>
         &nbsp
      </td>		 
      <td width='29%' bgcolor='#E9E9E9'>
         &nbsp
      </td>				
      <td width='27%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='18%' bgcolor='#E9E9E9'>
         &nbsp
      </td>			
   </tr>	  
   <tr style='font-family: Arial; font-size: 9pt'>
      <td align='center' width='7%'>
        $periodo
      </td>		 
      <td align='center' width='30%'>
        $tipo_de_inmueble
      </td>				
      <td align='center' width='27%'>
        $cod_cat
      </td>
      <td align='center' width='18%'>
        $min_fech
      </td>			
   </tr>	 
</table>
<!-- Copia Fila 3 Fecha Posesion -->
<table border='0' width='100%'>	 		 
	 <tr style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>	 	 
      <td width='18%' align='left' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='12%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='14%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='19%' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
      <td  width='22%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='15%' bgcolor='#E9E9E9'>
         &nbsp
      </td>		
   </tr> 	
	 <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='center'>
         &nbsp&nbsp $fecha_posesion
      </td>
      <td align='center'>
         $cod_alc
      </td>
      <td align='center'>
         $nit
      </td>
      <td align='center'>
         $ciudad
      </td>	
      <td align='center'>
         $fecha_emision
      </td>
      <td align='center'>
         $pmc
      </td>			
   </tr> 
</table>
<!-- Copia Fila 4 Ubicación del Inmueble -->
<table border='0' width='100%'>		 			 
	 <tr style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>	 	 
      <td width='15%' rowspan='2' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='34%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='24%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='8%' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
      <td width='7%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='6%' bgcolor='#E9E9E9'>
         &nbsp
      </td>		
      <td width='6%' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
   </tr> 		
	 <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='left'>
         &nbsp $direccion
      </td>
      <td align='left'>
         &nbsp $barrio
      </td>
      <td align='center'>
         $puerta
      </td>
      <td align='center'>
         $bloque
      </td>
      <td align='center'>
         $piso
      </td>
      <td align='center'>
         $dpto
      </td>															
   </tr> 
</table>
<!-- Copia Fila 5 Vendedor o Cedente -->
<table border='0' width='100%'>		  	 	    
	 <tr style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>	 	 
      <td width='15%' rowspan='2' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td  width='37%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='8%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='30%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='10%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
   </tr> 
	 <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='left'>
         &nbsp $vendedor
      </td>
      <td align='center'>
         100
      </td>
      <td align='left'>
         &nbsp $dom_dir
      </td>
      <td align='center'>
         $dom_num
      </td>						
   </tr> 
<!-- Copia Fila 6 Comprador o Cesionario -->	 
	 <tr style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>	 	 
      <td align='left' rowspan='2' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='center' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         &nbsp
      </td>
   </tr> 
	 <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='left'>
         &nbsp $comprador
      </td>
      <td align='center'>
         100
      </td>
      <td align='left'>
         &nbsp $dom_dir_comp
      </td>
      <td align='center'>
         $cod_pmc_comp
      </td>						
   </tr> 	 
</table>
<!-- Copia Fila 7 Valuación del Inmueble -->
<table border='0' width='100%'>			  	  
   <tr style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>
      <td width='15%' rowspan='2' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='16%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='14%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='18%' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
      <td width='14%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='23%' bgcolor='#E9E9E9'>
         &nbsp
      </td>																	
   </tr>
   <tr style='font-family: Arial; font-size: 9pt'>
      <td align='center'>
         $valor_cat
      </td>
      <td align='center'>
         $valor_min
      </td>
      <td align='center'>
         $valor_doc_priv
      </td>	
      <td align='center'>
         $valor_usd
      </td>
      <td align='center'>
         $base_imp
      </td>																	
   </tr>	
</table>
<!-- Copia Fila 8 Cálculo del Impuesto -->
<table border='0' width='100%'>			  
   <tr style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>	 	 
      <td width='15%' rowspan='2' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='12%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='12%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='12%' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
      <td width='12%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='11%' bgcolor='#E9E9E9'>
         &nbsp			
      </td>
      <td width='11%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='15%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
   </tr> 
   <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='center'>
         $monto_det
      </td>
      <td align='center'>
         $saldo_a_favor
      </td>
      <td align='center'>
         $pagos_ant
      </td>
      <td align='center'>
         $mant_val
      </td>	
      <td align='center'>
         $interes
      </td>
      <td align='center'>
			   $multa_mora
      </td>
      <td align='center'>
         $multa_incumpl
      </td>
   </tr>
</table>
<!-- Copia Fila 9 Número de Control -->
<table border='0' width='100%'>			  
   <tr style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>	 	 
      <td width='15%' rowspan='2' align='center' valign='center' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='15%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='15%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='9%' bgcolor='#E9E9E9'>
         &nbsp
      </td>	
      <td width='14%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='8%' bgcolor='#E9E9E9'>
         &nbsp			
      </td>
      <td width='7%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='18%' rowspan='4' bgcolor='#E9E9E9'>
         &nbsp
      </td>
   </tr> 
   <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td align='center'>
         $no_control
      </td>
      <td align='center'>
         $fecha_venc
      </td>
      <td align='center'>
         $cambio_usd
      </td>
      <td align='center'>
         $nro_form
      </td>	
      <td align='center'>
         $orig
      </td>
      <td align='center'>
			   $rect
      </td>
   </tr>
<!-- Copia Fila 10 Literal -->	 
   <tr>	 	 
      <td colspan='5' rowspan='2' valign='bottom' style='font-family: Arial; font-size: 9pt'>
         &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp $importe_literal
      </td>
      <td colspan='2' bgcolor='#E9E9E9' style='font-family: Arial; font-size: $ancho_filas_en_medio pt'>
         &nbsp
      </td>
   </tr> 
   <tr style='font-family: Arial; font-size: 9pt'>	 	 
      <td colspan='2' align='right'>
         $total_a_pagar &nbsp&nbsp&nbsp&nbsp
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