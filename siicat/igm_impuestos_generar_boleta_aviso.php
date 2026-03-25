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
<table border='1' width='100%' height='161' style='font-family: Tahoma; font-size: 9pt'><font face='Tahoma' size='2'>
   <tr height='30'>
      <td align='center' colspan='2' bgcolor='#E9E9E9'>
        <b>HON. ALCALDIA MUNICIPAL</b>
      </td>		 
      <td align='center' bgcolor='#E9E9E9'>
        &nbsp
      </td>				
      <td align='center' colspan='13' bgcolor='#E9E9E9'>
        <b>IMPUESTO A LA PROPIEDAD DE BIENES INMUEBLES</b>
      </td>
      <td align='center' colspan='3' bgcolor='#E9E9E9'>
        <b>$municipio</b>
      </td>			
   </tr>
	 <tr height='1'>
      <td colspan='19'></td>		  
   </tr>  		 
	 <tr>	 	 
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         &nbsp;No. de Orden
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         &nbsp;Gestión
      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         &nbsp;P.M.C.
      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         &nbsp;Alcaldía
      </td>	
      <td align='left' colspan='7' bgcolor='#E9E9E9'>
         &nbsp;Código Catastral
      </td>
      <td align='left' colspan='3' bgcolor='#E9E9E9'>
         &nbsp;C.I./NIT
      </td>	
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         &nbsp;Tipo Inmueble
      </td>		
   </tr> 	
	 <tr>	 	 
      <td align='center' colspan='2'>
         $nro_de_orden
      </td>
      <td align='center'>
         $gestion
      </td>
      <td align='center' colspan='2'>
         $pmc
      </td>
      <td align='center' colspan='2'>
         $cod_mun
      </td>	
      <td align='center' colspan='7'>
         $cod_cat
      </td>
      <td align='center' colspan='3'>
         -
      </td>	
      <td align='center' colspan='2'>
         $tp_inmu
      </td>		
   </tr> 	
	 <tr>	 	 
      <td align='left' colspan='5' bgcolor='#E9E9E9'>
         &nbsp;Propietario (Nombre/Razon Social)
      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         &nbsp;Dpto.
      </td>	
      <td align='left' colspan='5' bgcolor='#E9E9E9'>
         &nbsp;Ciudad
      </td>	
      <td align='left' colspan='7' bgcolor='#E9E9E9'>
         &nbsp;Domicilio
      </td>	
   </tr> 	
	 <tr>	 	 
      <td align='left' colspan='5'>
         &nbsp;$titular
      </td>
      <td align='center' colspan='2'>
         $dom_dpto
      </td>
      <td align='center' colspan='5'>
         $dom_ciu
      </td>			
      <td align='left' colspan='7'>
         &nbsp;$dom_dir
      </td>
   </tr> 
	 <tr height='1'>
      <td colspan='19'></td>		  
   </tr>  	 	    
	 <tr>	 	 
      <td align='left' colspan='7' bgcolor='#E9E9E9'>
         &nbsp;<b>I. UBICACION DEL INMUEBLE</b>
      </td>
      <td align='left' colspan='3' bgcolor='#E9E9E9'>
         &nbsp;Zona
      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         &nbsp;U.V.
      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         &nbsp;Manzano
      </td>	
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         &nbsp;Lote
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         &nbsp;Bloque
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         &nbsp;Piso
      </td>	
      <td align='left' bgcolor='#E9E9E9'>
         &nbsp;Apto.
      </td>		
   </tr> 
	 <tr>	 	 
      <td align='left' colspan='7'>
         &nbsp;$direccion
      </td>
      <td align='center' colspan='3'>
         $zona
      </td>
      <td align='center' colspan='2'>
         $cod_uv
      </td>
      <td align='center' colspan='2'>
         $cod_man
      </td>	
      <td align='center' colspan='2'>
         $cod_pred
      </td>
      <td align='center'>
         $dir_bloq
      </td>
      <td align='center'>
         $dir_piso
      </td>	
      <td align='center'>
         $dir_apto
      </td>		
   </tr> 
	 <tr height='1'>
      <td colspan='19'></td>		  
   </tr>  	 	  
   <tr height='20'>
      <td align = 'left' colspan='13' bordercolor='#CCCCCC' bgcolor='#E9E9E9'>
         &nbsp;<b>II. TIPIFICACION Y VALUACION DEL TERRENO</b>
      </td>
	    <td></td>	
      <td align = 'left' colspan='5' bordercolor='#CCCCCC' bgcolor='#E9E9E9'>
         &nbsp;<b>IV. VAL. EMPRESAS</b>
      </td>				
   </tr> 
   <tr height='20'>
      <td align='left' bgcolor='#E9E9E9'>
         Zona.Hom.
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         Material Vía
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         Vale por M²
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         Superficie
      </td>	
      <td align='left' bgcolor='#E9E9E9'>
         Agua
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         Alcant.
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         Luz
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         Teléf.
      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         Min.
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         Inclin.
      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         Avalúo Terreno
      </td>
      <td align='left'>
      </td>
      <td align='left' colspan='3' bgcolor='#E9E9E9'>
         Valor en Libros al:
      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         Base imponible
      </td>																			
   </tr>
   <tr height='5'>
      <td align='center'>
         $ben_zona
      </td>
      <td align='center'>
         $via_mat
      </td>
      <td align='center'>
         $val_m2_terr
      </td>
      <td align='center'>
         $sup_terr
      </td>	
      <td align='center'>
         $fact_agu
      </td>
      <td align='center'>
         $fact_alc
      </td>
      <td align='center'>
         $fact_luz
      </td>
      <td align='center'>
         $fact_tel
      </td>
      <td align='center' colspan='2'>
         $fact_min
      </td>
      <td align='center'>
         $fact_incl
      </td>
      <td align='center' colspan='2'>
         $avaluo_terr
      </td>
      <td align='center'>
      </td>
      <td align='center' colspan='3'>
         $fecha_emp
      </td>
      <td align='center' colspan='2'>
         $base_imp_emp
      </td>																			
   </tr>	
	 <tr height='1'>
      <td colspan='19'></td>		  
   </tr>  	 
   <tr>
      <td align='left' height='20' colspan='8' bordercolor='#CCCCCC' bgcolor='#E9E9E9'>
         &nbsp;<b>III. TIPIFICACION Y VALUACION DE LA CONSTRUCCION</b>
      </td>
	    <td align='left' width='1%'>
      </td>	
      <td align = 'left' colspan='10' bgcolor='#E9E9E9'>
         &nbsp;<b>V. AVALUO TOTAL</b>
      </td>				
   </tr>
   <tr>	 	 
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         Tipo de Vivienda
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         Vale por M²
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         Sup. Const.
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         Antig.
      </td>	
      <td align='left' colspan='3' bgcolor='#E9E9E9'>
         Avalúo Construc.
      </td>
      <td align='left'>
      </td>
      <td align='left' colspan='3' bgcolor='#E9E9E9'>
         Avalúo Inmueble
      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         Tipo Ex.
      </td>
      <td align='left' colspan='3' bgcolor='#E9E9E9'>
         Monto Exento
      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         Base Imponible
      </td>
   </tr> 
   <tr>	 	 
      <td align='center' colspan='2'>
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
      <td align='center' colspan='3'>
         $avaluo_const
      </td>
      <td align='center'>
      </td>
      <td align='center' colspan='3'>
         $avaluo_total
      </td>
      <td align='center' colspan='2'>
         $tp_exen
      </td>
      <td align='center' colspan='3'>
         $monto_exen
      </td>
      <td align='center' colspan='2'>
         $avaluo_total
      </td>
   </tr>
	 <tr height='1'>
      <td colspan='19'></td>		  
   </tr> 	 
   <tr>	 	 
      <td align='left' colspan='2' rowspan='2' bgcolor='#E9E9E9'>
         Monto Determinado
      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         Saldo anterior a favor
      </td>
      <td align='left' colspan='3' bgcolor='#E9E9E9'>
         Accesorios
      </td>
      <td align='left' colspan='5' bgcolor='#E9E9E9'>
         Multas
      </td>	
      <td align='left' colspan='3' rowspan='2' bgcolor='#E9E9E9'>
         Descuento pago en término
      </td>
      <td align='left'>
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         T.C. act.
      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         Saldo Próx. Gestión
      </td>
   </tr>
   <tr>	 	 
      <td align='left' bgcolor='#E9E9E9'>
         Valor
      </td>
	    <td align='left' bgcolor='#E9E9E9'>
         T.C.
      </td>		
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         Mant. Valor
      </td>
      <td align='left' colspan='1' bgcolor='#E9E9E9'>
         Inter.
      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         Mora
      </td>						
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         Incump.
      </td>
      <td align='left' bgcolor='#E9E9E9'>
         Adm.
      </td>				
      <td align='left'>
      </td>
      <td align='center'>
         $tc_act
      </td>			
      <td align='center' colspan='2'>
         0
      </td>
   </tr>	 
   <tr>
      <td align='center' colspan='2'>
         $imp_neto
      </td>	 	 	 
      <td align='center'>
         $sal_favor
      </td>
	    <td align='center'>
         $t_camb
      </td>		
      <td align='center' colspan='2'>
         $mant_val
      </td>
      <td align='center'>
         $interes
      </td>
      <td align='center' colspan='2'>
         $multa_mora
      </td>						
      <td align='center' colspan='2'>
         $multa_incum
      </td>
      <td align='center'>
         $multa_admin
      </td>	
      <td align='center' colspan='3'>
         $descuento
      </td>							
      <td align='center'>
      </td>	
      <td align='center' colspan='3' rowspan='5'>
         PRE-AVISO DE PAGO
      </td>
   </tr>
   <tr>	 	 
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         Pago Anterior
      </td>
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         Nro. Form.
      </td>
      <td align='left' colspan='3' bgcolor='#E9E9E9'>
         Forma de Pago
      </td>
      <td align='left' colspan='3' bgcolor='#E9E9E9'>
         Liquidación
      </td>	
      <td align='left' colspan='2' bgcolor='#E9E9E9'>
         Fecha Emisión
      </td>			
      <td align='left' colspan='3' bgcolor='#E9E9E9'>
         Fecha Vencimiento
      </td>
      <td align='left'>
      </td>
   </tr>
   <tr>	 	 
      <td align='center' colspan='2'>
         $pago_ant
      </td>
      <td align='center' colspan='2'>
         PRE-AVISO DE PAGO
      </td>
      <td align='center' colspan='3'>
         $forma_pago
      </td>
      <td align='center' colspan='3'>
         $liquidacion
      </td>	
      <td align='center' colspan='2'>
         $fecha_emision
      </td>			
      <td align='center' colspan='3'>
         $fecha_venc
      </td>
      <td align='center'>
      </td>
   </tr>	
   <tr>	 	 
      <td align='left' colspan='15'>
         <b>Total a pagar en Bs.</b>
      </td>
      <td align='left'>
      </td>			
   </tr>	   
	 <tr>
	    <td align='center' colspan='2'>
			$total_a_pagar
      </td>		 	 	 
      <td align='left' colspan='13'>
         &nbsp;$monto_en_letras 00/100 Bs.
      </td>
      <td align='left'>
      </td>			
   </tr>	
		 	  	 	 		  	 
	 <tr height='1'>
      <td align='left' colspan='19' style='font-family: Arial; font-size: 7pt'> &nbsp&nbsp Reposición de formulario: $por_form Bs.</td>		  
   </tr> 	  	  	 
   <tr height='35'>
      <td align='center' colspan='19' bgcolor='#E9E9E9'>
      <a href='javascript:print(this.document)'>
      <img border='0' src='http://$server/$folder/graphics/printer.png' width='22' height='22'></a>
      </td>	 
   </tr>
   <tr height='1'>
      <td align='left' width='4%'>
      </td>
      <td align='left' width='9%'>
      </td>
      <td align='left' width='9%'>
      </td>
      <td align='left' width='9%'>
      </td>	
      <td align='left' width='5%'>
      </td>
      <td align='left' width='5%'>
      </td>
      <td align='left' width='4%'>
      </td>
      <td align='left' width='5%'>
      </td>
      <td align='left' width='1%'>
      </td>
      <td align='left' width='4%'>
      </td>			
      <td align='left' width='5%'>
      </td>
      <td align='left' width='5%'>
      </td>
      <td align='left' width='6%'>
      </td>			
      <td align='left' width='1%'>
      </td>
      <td align='left' width='6%'>
      </td>
	    <td align='left' width='1%'>
      </td>		
      <td align='left' width='7%'>
      </td>	
      <td align='left' width='7%'>
      </td>
      <td align='left' width='7%'>
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