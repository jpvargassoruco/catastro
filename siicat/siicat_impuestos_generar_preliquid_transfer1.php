<?php

################################################################################
#                        LEER MANZANA ANTERIRO Y PREDIO                        #
################################################################################	
$sql = "SELECT man_ant FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check = pg_num_rows(pg_query($sql));
$result = pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$man_ant = $info['man_ant'];
$cod_predio = substr($cod_cat,9,3);

$ancho_primera_fila_preliquidacion = 1;
$ancho_entre_contrib_banco = 30;   #30
$ancho_entre_banco_alcaldia = 39;  #35

$monto_en_letras = numeros_a_letras($total_a_pagar);
################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	
$filename = "C:/apache2/htdocs/tmp/preliq".$cod_cat.".html";
 
#$fontfam = "Terminal";   #Arial, Tahoma, Verdana, Calibri
################################################################################
#------------------------ PREPARAR CONTENIDO PARA GRABAR ----------------------#
################################################################################	<font face='Tahoma' size='2'>
$content = " 
<div align='left'>
<!-- Fila 0: FILA EN BLANCO ARRIBA -->
<table border='0' width='100%' style='font-family: $fontfam; font-size: 1pt'>
   <tr height='$ancho_primera_fila_preliquidacion'>
      <td>
        &nbsp
      </td>		 
   </tr>
</table>
<!-- Fila 1: FORMULARIO UNICO DE RECAUDACIONES -->
<table border='0' width='100%' style='font-family: $fontfam; font-size: 4pt'>
   <tr>
      <td align='center' width='32%'>
         &nbsp
      </td>		 
      <td align='center' width='48%' bgcolor='#E9E9E9'  style='font-family: $fontfam; font-size: 4pt'>
         &nbsp
      </td>				
      <td align='right' width='20%'>
         &nbsp
      </td>	
   </tr>
</table>
<!-- Fila 2: CASILLA ROJITA Y GOBIERNO MUNICIPAL -->
<table border='0' width='100%' style='font-family: $fontfam; font-size: 4pt'>
   <tr>
      <td rowspan='7' align='left' width='2%' bgcolor='#E9E9E9' style='font-family: $fontfam; font-size: 7pt'>
         &nbsp			 
      </td>		 
      <td rowspan='7' align='left' width='15%' bgcolor='#E9E9E9' style='font-family: $fontfam; font-size: 7pt'>
			   <table border='1' width='100%' style='border-collapse:collapse; font-family: $fontfam; font-size: 7pt'>
				 <tr>
				 <td>
				 <br />&nbsp&nbsp&nbsp SISTEMA1: $sistema<br />         
				 &nbsp&nbsp&nbsp FORM: 1980<br />
				 &nbsp&nbsp&nbsp $casilla_trans_objeto<br />
				 &nbsp&nbsp&nbsp IMPUESTO A LA<br />
				 &nbsp&nbsp&nbsp TRANSFERENCIA<br />
				 &nbsp&nbsp&nbsp FOLIO:<br />
				 &nbsp&nbsp&nbsp $folio<br />
				 &nbsp&nbsp&nbsp $casilla_trans_numero<br />
				 &nbsp&nbsp&nbsp PAGO TOTAL<br />
				 &nbsp&nbsp&nbsp GESTION: $ano_actual<br /><br />		
				 </td>
				 </tr>
				 </table> 
      </td>		 
      <td align='center' bgcolor='#E9E9E9'>
         &nbsp
      </td>				
      <td colspan='2' align='right' style='font-family: $fontfam; font-size: 10pt'>
         Gobierno Aut�nomo Municipal de $municipio &nbsp
      </td>		
      <td align='left' style='font-family: $fontfam; font-size: 7pt'>
         DISTRITO $distrito
      </td>					
      <td align='right' bgcolor='#E9E9E9'>";
if ($imprimir_preliq) {
$content = $content." 
         <a href='javascript:print(this.document)'>		 
         <img border='0' src='http://$server/siicat/graphics/printer.png' width='22' height='22'></a>&nbsp";
}
$content = $content."   
      </td>					
   </tr>
   <tr> 
      <td colspan='5' align='center' style='font-family: $fontfam; font-size: 3pt'>
         &nbsp
      </td>									
   </tr>		 
   <tr> 
      <td align='left' width='14%' valign='top' style='font-family: $fontfam; font-size: 7pt'>&nbsp FECHA EMISION:</td>
      <td align='left' width='28%' valign='top' style='font-family: $fontfam; font-size: 7pt'>$fecha2 $hora</td>			
      <td align='left' width='23%' valign='top' style='font-family: $fontfam; font-size: 7pt'>CAJERO: $user_id</td>	
      <td align='left' width='15%' valign='top' style='font-family: $fontfam; font-size: 7pt'>FOLIO: $folio</td>
      <td align='right' width='20%' valign='top' style='font-family: $fontfam; font-size: 7pt'>&nbsp</td>																		
   </tr>		 
   <tr> 
      <td colspan='5' align='left' valign='top' style='font-family: $fontfam; font-size: 7pt'>&nbsp <b>IDENTIFICACION DEL CONTRIBUYENTE</b> </td>								
   </tr>	
   <tr> 
      <td colspan='2' align='left' valign='top' style='font-family: $fontfam; font-size: 7pt'>
         &nbsp NOMBRE: $titular<br />&nbsp DOCUMENTACION: $ci_nit<br />&nbsp DOMICILIO LEGAL: $dom_ciu, $dom_dir
      </td>	
      <td colspan='3' align='left' valign='top' style='font-family: $fontfam; font-size: 7pt'>
         P.M.C.: $cod_pad<br />TIPO CONTRIBUYENTE: $tit_tipo<br />NRO. CUENTA LUZ: 
      </td>											
   </tr>
   <!-- Fila 3: DATOS DEL INMUEBLE -->	 
   <tr> 
      <td colspan='5' align='left' valign='top' style='font-family: $fontfam; font-size: 7pt'>
         &nbsp <b>DATOS DEL INMUEBLE</b>         
      </td>											
   </tr>	
   <tr> 
      
      <td colspan='2' align='left' valign='top' style='font-family: $fontfam; font-size: 7pt'>
         &nbsp TIPO INMUEBLE: $tipo_inmu_texto<br />&nbsp DIRECCION: $direccion
      </td>	
      <td colspan='2' align='left' valign='top' style='font-family: $fontfam; font-size: 7pt'>
         &nbsp <br />CODIGO CATASTRAL1: <br />$codcat_transfer <b> Manzana: $man_ant Lote: $cod_pred </b>
      </td>					
       <td colspan='2' align='left' valign='top' style='font-family: $fontfam; font-size: 8pt'>
         Manzana: <br> $man_ant Lote: $cod_pred 
      </td>          
   </tr> 		 	 		  
</table>
<!-- Fila 4: IDENTIFICACION DEL COMPRADOR O CESIONARIO --> 
<table border='0' width='100%' style='font-family: $fontfam; font-size: 1pt'>	 
	 <tr> 
      <td colspan='3' style='font-family: $fontfam; font-size: 3pt'> &nbsp </td>			 							
   </tr>   
   <tr> 
      <td> &nbsp </td>		 
      <td colspan='2' align='left' valign='top' style='font-family: $fontfam; font-size: 7pt'>
         <b>IDENTIFICACION DEL COMPRADOR O CESIONARIO</b>
      </td>								
   </tr>	
   <tr>  
      <td width='2%'>
         &nbsp 		 
      </td>		 
      <td width='58%' align='left' valign='top' style='font-family: $fontfam; font-size: 7pt'>
         &nbsp NOMBRE 1: $comprador<br />&nbsp NOMBRE 2: $comprador2<br />&nbsp DOMICILIO LEGAL: $dom_dir_comp
      </td>	
      <td width='40%' align='left' valign='top' style='font-family: $fontfam; font-size: 7pt'>
         P.M.C.: $cod_pmc_comp<br />TIPO CONTRIBUYENTE: $comp_tipo<br />NRO. CUENTA LUZ: 
      </td>											
   </tr>	 
   <tr> 
      <td colspan='3' style='font-family: $fontfam; font-size: 3pt'>
         &nbsp 		 
      </td>			 									
   </tr>		 
</table>	
<!-- Fila 5: DETALLES DE LA TRANSFERENCIA --> 
<table border='0' width='100%' style='font-family: $fontfam; font-size: 4pt'>	 
	 <tr> 
      <td>
         &nbsp			 
      </td>			 
      <td colspan='2' align='left' valign='top' style='font-family: $fontfam; font-size: 7pt'>
         <b>DETALLES DE LA TRANSFERENCIA</b>
      </td>								
   </tr>
   <tr>  
      <td width='2%'>
         &nbsp 		 
      </td>		 
      <td width='29%' align='left' valign='top' style='font-family: $fontfam; font-size: 7pt'>
         &nbsp NO. DE MINUTA: $min_num<br />&nbsp FECHA FIRMA MINUTA: $min_fech_texto
      </td>	      
			<td width='27%' align='left' valign='top' style='font-family: $fontfam; font-size: 7pt'>
         &nbsp MODO DE TRANSFERENCIA:<br />&nbsp $modo_trans_texto
      </td>	
      <td width='42%' align='left' valign='top' style='font-family: $fontfam; font-size: 7pt'>
         VALOR MINUTA (EN USD): $valor_usd<br />VALOR MINUTA (EN BS): $valor_min
      </td>														
   </tr>		 
   <tr> 
      <td colspan='3' style='font-family: $fontfam; font-size: 1pt'>
         &nbsp 		 
      </td>			 									
   </tr>		 
</table>	
<!-- Fila 6: VALUACION INMUEBLE TOTAL --> 
<table border='0' width='100%' style='font-family: $fontfam; font-size: 1pt'>	  
	 <tr> 
      <td>
         &nbsp			 
      </td>			 
      <td colspan='5' align='left' valign='top' style='font-family: $fontfam; font-size: 7pt'>
         <b>VALUACION DEL INMUEBLE (SEGUN GESTION $gestion)</b>
      </td>	
      <td colspan='2' align='left' valign='top' style='font-family: $fontfam; font-size: 7pt'>
         <b>DETERMINACION DE LA BASE IMPONIBLE</b>
      </td>										
   </tr> 
   <tr> 
      <td width='2%'>
         &nbsp			 
      </td>			 
      <td align='right' width='12%' style='font-family: $fontfam; font-size: 7pt'>
         VAL. TERRENO |<br />$valor_t |
      </td>				
      <td align='right' width='12%' style='font-family: $fontfam; font-size: 7pt'>
         VAL. CONST. |<br />$valor_vi |
      </td>				
      <td align='right' width='10%' style='font-family: $fontfam; font-size: 7pt'>
         VAL. TOTAL|<br /> $valor_total|
      </td>	
      <td align='right' width='10%' style='font-family: $fontfam; font-size: 7pt'>
         VALOR LIB. |<br />$valor_en_libros_texto |
      </td>	
      <td align='right' width='14%' style='font-family: $fontfam; font-size: 7pt'>
         &nbsp
      </td>						
      <td align='left' width='38%' style='font-family: $fontfam; font-size: 7pt'>
         BASE IMPONIBLE ($base_imp_seleccion): $base_imp <br /> (El impuesto es 3% sobre la base imponible)
      </td>																	
      <td align='right' width='2%' style='font-family: $fontfam; font-size: 7pt'>
         &nbsp
      </td>																																			
   </tr>
   <tr height='1'> 
      <td colspan='12' style='font-family: $fontfam; font-size: 1pt'>
         &nbsp 		 
      </td>			 									
   </tr>		 
</table>	
<!-- Fila 7: CALCULO --> 
<table border='0' width='100%' style='font-family: $fontfam; font-size: 1pt'>	 
   <tr> 
      <td>
         &nbsp			 
      </td>			 
      <td colspan='10' align='left' valign='top' style='font-family: $fontfam; font-size: 7pt'>
         <b>CALCULO DE IMPUESTO</b>
      </td>								
   </tr>
   <tr> 
      <td width='2%'>
         &nbsp			 
      </td>			 
      <td align='left' width='18%' style='font-family: $fontfam; font-size: 7pt'>
				 MONTO DETERM. (Bs.):<br />
				 DESCUENTO (Bs.):<br />
				 MONTO IMP. (Bs.):<br />				 
				 FECHA VENCIMIENTO:<br />
				 CANTIDAD DE DIAS:<br />
         UFV FECHA VENC.:<br />		
				 TRIBUTO OMITIDO (UFV):<br />				 		 
      </td>	
      <td align='right' width='8%' style='font-family: $fontfam; font-size: 7pt'>
				 $monto_det<br />
				 $descuento<br />
				 $monto_imp<br />				 
				 $fecha_venc_texto<br />
				 $cantidad_de_dias<br />
         $ufv_fecha_venc<br />	
				 $trib_omit<br />
      </td>	
      <td width='4%'>
         &nbsp			 
      </td>						
      <td align='left' width='27%' style='font-family: $fontfam; font-size: 7pt'>
				 TASA INTERES + 3 (en %):<br />
				 INTERES ($moneda):<br />				 
				 MULTA INCUMPLIMIENTO ($moneda):<br />
				 M. OMISION PAGO ($moneda):<br />
				 CONDONACION MULTA ($moneda): ";
if ($nota_condonacion) {
$content = $content."**)";
}				 
$content = $content."<br />
         DEUDA TRIBUTARIA (UFV):<br /> 
				 UFV ACTUAL:<br />				 				 
      </td>	
      <td align='right' width='8%' style='font-family: $fontfam; font-size: 7pt'>
				 $tasa_interes<br />
				 $interes<br />				 
				 $multa_incump_ufv<br />
				 $multa_omision_pago<br />
				 $monto_condonacion_neg<br />
         $deuda_trib<br />		
				 $ufv_actual<br />				 		 
      </td>		
      <td width='4%'>
         &nbsp			 
      </td>						
      <td align='left' width='19%' style='font-family: $fontfam; font-size: 7pt'>
				 DEUDA TRIB. (Bs.):<br />
				 PAGOS GESTION (Bs.):<br />				 
				 SALDO A FAVOR (Bs.):<br />		
				 REPOSICION FORM.(Bs.):<br />	
				 EXENCION (Bs.): ";
if ($nota_exencion) {
$content = $content."***)";
}				 
$content = $content."<br />				 	 
				 MONTO A PAGAR (Bs.):<br />
				 <br />
      </td>	
      <td align='right' width='7%' style='font-family: $fontfam; font-size: 7pt'>
				 $deuda_bs<br />
				 $pagos_ant<br />
				 $saldo_a_favor<br />		
				 $rep_form<br />	
				 $exencion<br />	 
				 $total_a_pagar<br />
				 <br />
      </td>							
      <td align='right' width='3%' style='font-family: $fontfam; font-size: 7pt'>
         &nbsp
      </td>																																			
   </tr>
   <tr> 
      <td colspan='10' style='font-family: $fontfam; font-size: 1pt'>
         &nbsp 		 
      </td>			 									
   </tr>		 
</table>	
<!-- Fila 8: DETALLE PAGO --> 
<table border='0' width='100%' style='font-family: $fontfam; font-size: 1pt'>	 
   <tr> 
      <td>
         &nbsp			 
      </td>			 
      <td colspan='5' align='left' style='font-family: $fontfam; font-size: 7pt'>
         <b>DETALLE PAGO</b>
      </td>								
   </tr>
   <tr> 
      <td width='2%'>
         &nbsp			 
      </td>			 
      <td align='left' width='24%' style='font-family: $fontfam; font-size: 7pt'>
         TOTAL A PAGAR: <b>$total_a_pagar Bs.</b>
      </td>	
      <td align='left' width='45%' style='font-family: $fontfam; font-size: 7pt'>
         SON: <b>$monto_en_letras 00/100 Bs.</b>
      </td>	
      <td align='left' width='27%' style='font-family: $fontfam; font-size: 7pt'>
         FECHA VENCIMIENTO: <b>$fecha_venc_preliquid_texto</b>";
if ($nota_fecha_venc_preliquid) {
   $content = $content." *)";
}
$content = $content."
      </td>			
      <td width='2%'>
         &nbsp			 
      </td>																				
   </tr> 
   <tr> 
      <td>
         &nbsp			 
      </td>			 
      <td colspan='5' align='left' style='font-family: $fontfam; font-size: 7pt'>";
$espacio_en_filas = 0;
if ($nota_fecha_venc_preliquid) {
   $espacio_en_filas++;
   $content = $content."*) Debe solicitar otra pre-liquidaci�n cuando se pasa la fecha de vencimiento indicada!";
}
if ($nota_condonacion) {
   $espacio_en_filas++;
   $content = $content."<br />**) $texto_condonacion";
}
if ($nota_exencion) {
   $espacio_en_filas++;
   $content = $content."<br />***) $texto_exencion";
}
if (($area_predio_manual) OR ($area_predio_manual)) {
   $espacio_en_filas++;
   $content = $content."<br />x) Por falta de la geometr�a se aplica la superficie s/ documentos, caso de ser distinta de la superficie mensurada se rectificar� el monto de la gesti�n.";
}
while ($espacio_en_filas < 4) {
   $content = $content."<br />";
   $espacio_en_filas++;
}	   			 
$content = $content."      </td>								
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