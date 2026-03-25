<?php
################################################################################
#--------------------- LEER REGISTRO DE IMP_PAGADOS ---------------------------#
################################################################################	
$sql="SELECT * FROM imp_pagados WHERE gestion = '$gestion' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
$result = pg_query($sql);
$info_imp = pg_fetch_array($result, null, PGSQL_ASSOC);
include "siicat_impuestos_pagados.php";
$servicios_letras = "Servicios Basicos:&nbspMinimo SI &nbsp&nbsp&nbsp Agua:".$ser_agu."&nbsp&nbsp&nbsp Electricidad:";
$servicios_letras = $servicios_letras.$ser_luz."&nbsp&nbsp&nbsp&nbsp Alcantarillado:".$ser_alc;
$LineaDiv = str_repeat("-", 600);
$pmcCeros = str_pad($pmc, 6, "0", STR_PAD_LEFT);
$id_inmuCeros = str_pad($id_inmu, 6, "0", STR_PAD_LEFT);
if ($ter_topo == "") {
   $ter_topo_texto = "-";     
} else $ter_topo_texto = utf8_decode(abr($ter_topo));

$via_mat = edg_material_de_via ($id_inmu);
if ($via_mat == "") {
  $via_mat_texto = "NO DEF.";
} else $via_mat_texto = abr($via_mat);
################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	

$filename = "C:/apache/htdocs/tmp/boleta_reimpr".$cod_cat.".html";

################################################################################
#------------------------ PREPARAR CONTENIDO PARA GRABAR ----------------------#
################################################################################	
$content = " 
<div>   
<table width='830px' border-top='1px solid black' style='font-family: Arial; font-size: 6pt'>

   <tr>
      <td align='center' width='10%' >&nbsp</td> 
      <td align='center' width='17%' bgcolor='#E9E9E9'>$dir_mun $dir_fon</td>
      <td align='center' width='52%' bgcolor='#E9E9E9' style='font-family: Arial; font-size: 16pt'>COMPROBANTE DE PAGO</td>		 
      <td align='center' width='17%' bgcolor='#E9E9E9' style='font-family: Arial; font-size: 8pt'>Nro.: $control </td>		
   </tr>
   <tr>
      <td align='center' width='10%' >&nbsp</td> 
      <td align='center' width='17%' bgcolor='#E9E9E9'>PMC: $pmcCeros </td>
      <td align='center' width='56%' bgcolor='#E9E9E9' style='font-family: Arial; font-size: 8pt'>GOBIERNO AUTONOMO MUNICIPAL $municipio_min</td>       
      <td align='center' width='17%' bgcolor='#E9E9E9' style='font-family: Arial; font-size: 6pt'>Copia</td>    
   </tr>   
   <tr height='1' style='font-family: Arial; font-size: 3pt'>
      <td align='center' width='10%'>&nbsp</td> 
      <td align='center'   width='90%'colspan='3'>$LineaDiv</td>
   </tr>   
</table>

<!-- Tabla 2 -->
<table border='0' width='830px' style='font-family: Arial; font-size: 8pt'>
   <tr>
      <td align='center' width='10%' >&nbsp</td> 
      <td align='center' width='17%' bgcolor='#E9E9E9'>Gestion.:$gestion</td>
      <td align='center' width='56%' bgcolor='#E9E9E9'>IMPUESTO MUNICIPAL A LA PROPIEDAD DE BIENES (IMPBI)</td>       
      <td align='center' width='17%' bgcolor='#E9E9E9'>Fec.Emi:$fecha_emision</td>          
   </tr>
   <tr style='font-family: Arial; font-size: 3pt'>
      <td align='center' width='10%'>&nbsp</td> 
      <td align='center'   width='90%'colspan='3'>$LineaDiv</td>
   </tr>
</table>

<!-- Tabla 3 -->
<table border='0' width='830px' style='font-family: Arial; font-size: 8pt'>
   <tr>
      <td align='center' width='10%'>&nbsp</td> 
      <td align='left'   width='30%' bgcolor='#E9E9E9'>CODIGO MUNICIPAL: $cod_geo</td>
      <td align='center' width='30%' bgcolor='#E9E9E9'>CODIGO CATASTRAL: $cod_cat</td>       
      <td align='right'  width='30%' bgcolor='#E9E9E9'>INMUEBLE No.$id_inmuCeros</td>  
   </tr>
   <tr>
      <td width='10%' >&nbsp </td> 
      <td width='30%' bgcolor='#E9E9E9'>Manzano: $man_ant &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp $cod_predio</td> 
      <td width='30%' bgcolor='#E9E9E9' align='center'>&nbsp Bloque:</td> 
      <td width='30%' bgcolor='#E9E9E9' >&nbsp Piso: &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Dpto.: </td>
   </tr>   
   <tr style='font-family: Arial; font-size: 3pt'>
      <td align='center' width='10%'>&nbsp</td> 
      <td align='center'   width='90%' colspan='3'>$LineaDiv</td>        
   </tr>
</table>

<!-- Tabla 4 -->
<table border='0' width='830px' style='font-family: Arial; font-size: 8pt'>
   <tr>
      <td align='center' width='10%' >&nbsp</td>
      <td align='left'   width='50%' colspan='2' bgcolor='#E9E9E9'><b>IDENTIFICACION CONSTRIBUYENTE</b></td>  
      <td align='left'   width='40%' colspan='2' bgcolor='#E9E9E9'><b>UBICACION DEL INMUEBLE</b></td>   
   </tr>
   <tr>
      <td align='center' width='10%' >&nbsp</td>
      <td align='left'   width='360px' bgcolor='#E9E9E9'>Sujeto Pasivo 1: $titular   </td> 
      <td align='left'   width='70px'  bgcolor='#E9E9E9'>$tit_1ci_texto</td>    
      <td align='left'   width='258px' bgcolor='#E9E9E9'>Direccion:<font size=1> $direccion</font>  </td>      
   </tr>
   <tr>
      <td align='center' width='10%' >&nbsp</td>
      <td align='left'   width='360px' bgcolor='#E9E9E9'>Sujeto Pasivo 2: $titular2</td>
      <td align='left'   width='70px'  bgcolor='#E9E9E9'>$tit_2ci_texto</td>
      <td align='left'   width='258px' bgcolor='#E9E9E9'>Ciudad/Localidad: $distrito</td>
   </tr>
   <tr>
      <td align='center' width='10%' >&nbsp</td>
      <td align='left'   width='360px' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='left'   width='70px'  bgcolor='#E9E9E9'>&nbsp</td>  
      <td align='left'   width='258px' bgcolor='#E9E9E9'>Urb: $dir_zonurb</td>     
   </tr>
   <tr style='font-family: Arial; font-size: 3pt'>
      <td align='center' width='10%'>&nbsp</td> 
      <td align='center' width='90%' colspan='3'>$LineaDiv</td>    
   </tr>
</table>

<!-- Tabla 5 -->
<table border='0' width='830px' style='font-family: Arial; font-size: 8pt'>
   <tr>
      <td align='center' width='10%' >&nbsp</td>
      <td align='left'   width='72%' colspan='4' bgcolor='#E9E9E9'><b>AVALUO DEL TERRENO:&nbsp&nbsp$avaluo_terr Bs.-</b></td>
      <td align='center' width='14%' colspan='2' bgcolor='#E9E9E9'><b>VALOR EMPRESA</b></td> 
   </tr>
   <tr>
      <td align='left' width='10%' >&nbsp</td>
      <td align='left' width='20%' bgcolor='#E9E9E9'>Superficie: </td>
      <td align='left' width='10%' bgcolor='#E9E9E9'>$sup_terr m2</td> 
      <td align='left' width='20%' bgcolor='#E9E9E9'>Forma: $ter_form_texto</td>
      <td align='left' width='6%' bgcolor='#E9E9E9'>$fact_form</td>
      <td align='center' width='7%' bgcolor='#E9E9E9'>Val en Libre al</td>
      <td align='center' width='7%' bgcolor='#E9E9E9'>Base Imponible</td>             
   </tr>
   <tr>
      <td align='center' width='10%' >&nbsp</td>
      <td align='left'   width='20%' bgcolor='#E9E9E9'>Zona Tributaria: $ben_zona </td>
      <td align='left'   width='10%' bgcolor='#E9E9E9'>$val_m2_terr</td> 
      <td align='left'   width='20%' bgcolor='#E9E9E9'>Ubicacion: $ter_ubi</td>  
      <td align='left'   width='6%' bgcolor='#E9E9E9'>$fact_ubi</td> 
      <td align='center' width='15%' bgcolor='#E9E9E9'>$fecha_emp</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>$base_imp_emp</td>      
   </tr>
   <tr>
      <td align='center' width='10%' >&nbsp</td>
      <td align='left'   width='20%' bgcolor='#E9E9E9'>Topografia: $ter_topo_texto</td>
      <td align='left'   width='10%' bgcolor='#E9E9E9'>$fact_incl</td> 
      <td align='left'   width='20%' bgcolor='#E9E9E9'>Frente/Fondo: A menor B</td> 
      <td align='left'   width='6%' bgcolor='#E9E9E9'>1</td>  
      <td align='center' width='29%' colspan='2' bgcolor='#E9E9E9'><b>EXENCION</b></td>
   </tr>

   <tr>
      <td align='center' width='10%' >&nbsp</td>
      <td align='left'   width='20%' bgcolor='#E9E9E9'>Material de Via: </td>
      <td align='left'   width='10%' bgcolor='#E9E9E9'>$via_mat_texto</td> 
      <td align='left'   width='20%' bgcolor='#E9E9E9'>No de Frente</td> 
      <td align='left'   width='6%' bgcolor='#E9E9E9'>$ter_nofr</td>  
      <td align='center' width='29%' colspan='2' bgcolor='#E9E9E9'>$texto_exencion</td>
   </tr>

   <tr>
      <td align='center' width='10%'>&nbsp</td>
      <td align='left'   width='38%' colspan='4' bgcolor='#E9E9E9'>$servicios_letras</td>
      <td align='left'   width='29%' colspan='2' bgcolor='#E9E9E9'><b>DESCUENTO:   $des_int</b></td>
   </tr>   
   <tr style='font-family: Arial; font-size: 3pt'>
      <td align='left' width='10%'>&nbsp</td> 
      <td align='left' width='90%' colspan='7'>$LineaDiv</td> 
   </tr>
</table>


<!-- Tabla 6 -->
<table border='0' width='830px' style='font-family: Arial; font-size: 8pt'>
   <tr>
      <td align='center' width='10%'>&nbsp</td>
      <td align='left' width='43%' colspan='5' bgcolor='#E9E9E9'><b>AVALUO DE LA CONSTRUCCION:&nbsp&nbsp $avaluo_const Bs.-</b></td>
      <td align='left' width='43%' colspan='3' bgcolor='#E9E9E9'><b>AVALUO TOTAL:&nbsp $avaluo_total Bs.-</b></td>    
   </tr>
   <tr>
      <td align='center' width='10%'>&nbsp</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>Destino/Uso</td> 
      <td align='center' width='14%' bgcolor='#E9E9E9'>Total No. Bloques</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>Superficie Const.</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>Tipologia</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>Tipo Ex.</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>Monto Exento</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>Base Imponible</td>    
   </tr>
   <tr>
      <td align='center' width='10%'>&nbsp</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>$tp_inmu</td> 
      <td align='center' width='14%' bgcolor='#E9E9E9'>$no_de_edificaciones</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>$sup_const </td> 
      <td align='center' width='14%' bgcolor='#E9E9E9'>MIXTA</td> 
      <td align='center' width='10%' bgcolor='#E9E9E9'>$tp_exen</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>$monto_exen</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>$avaluo_total</td>      
   </tr>   
   <tr style='font-family: Arial; font-size: 3pt'>
      <td align='center' width='10%'>&nbsp</td> 
      <td align='center'   width='86%' colspan='8'>$LineaDiv</td>       
   </tr>
</table>


<!-- Tabla 7 -->
<table border='0' width='830px' style='font-family: Arial; font-size: 8pt'>
   <tr>
      <td align='center' colspan='1' width='112px'>&nbsp</td>
      <td align='left'   colspan='7' width='344px' bgcolor='#E9E9E9'><b>CALCULO DE IMPUESTO</b></td> 
      <td align='left'   colspan='4' width='344px' bgcolor='#E9E9E9'><b>FECHA DE VENCIMIENTO: $fecha_venc </b></td> 
   </tr>
   <tr>
      <td align='center' width='10%'>&nbsp</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>Importe</td> 
      <td align='center' width='10%' bgcolor='#E9E9E9'>Valor</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>Tip.Cam</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>Mant.Valor</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>Interes</td> 
      <td align='center' width='7%'  bgcolor='#E9E9E9'>Mora</td>
      <td align='center' width='7%'  bgcolor='#E9E9E9'>Incump.</td>
      <td align='center' width='6%'  bgcolor='#E9E9E9'>Adm.</td>
      <td align='center' width='7%'  bgcolor='#E9E9E9'>Form.</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>Descuento</td>
      <td align='center' width='7%'  bgcolor='#E9E9E9'>Exencion</td>    
   </tr>
   <tr>
      <td align='center' width='10%'>&nbsp</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>$imp_neto</td> 
      <td align='center' width='10%' bgcolor='#E9E9E9'>$sal_favor</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>$t_cam_actual</td> 
      <td align='center' width='8%'  bgcolor='#E9E9E9'>$mant_val</td> 
      <td align='center' width='8%'  bgcolor='#E9E9E9'>$interes</td> 
      <td align='center' width='7%'  bgcolor='#E9E9E9'>$multa_mora</td>
      <td align='center' width='7%'  bgcolor='#E9E9E9'>$multa_incum</td>
      <td align='center' width='6%'  bgcolor='#E9E9E9'>$multa_admin</td>
      <td align='center' width='7%'  bgcolor='#E9E9E9'>$por_form</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>$descuento</td> 
      <td align='center' width='7%'  bgcolor='#E9E9E9'>&nbsp</td>     
   </tr>
   <tr>
      <td align='center' colspan='1' width='10%'>&nbsp</td>
      <td align='left'   colspan='2' width='45%' bgcolor='#E9E9E9'><b>TOTAL A PAGAR: $total_a_pagar Bs.-</b></td> 
      <td align='left'   colspan='9' width='45%' bgcolor='#E9E9E9'><b>Son: $monto_en_letras 00/100 Bolivianos</b></td>
   </tr>

     
</table>

<table border='0' width='830px' style='font-family: Arial; font-size: 9pt'>
   <tr>
      <td align='center' colspan='1'  colspan='1'  width='10%'>&nbsp</td>
      <td align='center' colspan='3' colspan='11' width='90%'>&nbsp</td>
   </tr>
   <tr>
      <td align='center' colspan='1'  colspan='1'  width='10%'>&nbsp</td>
      <td align='center' colspan='3' colspan='11' width='90%'>&nbsp</td>
   </tr>
    <tr>
      <td align='center' colspan='1'  colspan='1'  width='10%'>&nbsp</td>
      <td align='center' colspan='3' colspan='11' width='90%'>&nbsp</td>
   </tr>
    <tr>
      <td align='center' colspan='4'  width='100%'>&nbsp</td>
   </tr>     
   <tr style='font-family: Arial; font-size: 3pt'>
      <td align='center' width='10%' colspan='1'>&nbsp</td>
      <td align='center'   width='90%' colspan='3' >$LineaDiv</td>       
   </tr>   
   <tr>
      <td align='center' width='10%'>&nbsp</td>
      <td align='center' width='26%'>&nbsp</td> 
      <td align='center' width='30%'>FECHA DE CANCELACION</td>
      <td align='center' width='30%'>SELLO DE CAJA: $user_id</td>   
   </tr>
</table> 


<table border='0' width='100%x'>
   <tr height='40'> 
      <td align='center' valign='bottom' width='100%'>
      <a href='javascript:print(this.document)'>
      <img border='0' src='http://$server/$folder/graphics/printer.png' width='20' height='20'></a>
      </td>  
   </tr>
</table>   



<table width='830px' border-top='1px solid black' style='font-family: Arial; font-size: 6pt'>

   <tr>
      <td align='center' width='10%' >&nbsp</td> 
      <td align='center' width='17%' bgcolor='#E9E9E9'>$dir_mun $dir_fon</td>
      <td align='center' width='52%' bgcolor='#E9E9E9' style='font-family: Arial; font-size: 16pt'>COMPROBANTE DE PAGO</td>		 
      <td align='center' width='17%' bgcolor='#E9E9E9' style='font-family: Arial; font-size: 8pt'>Nro.: $control </td>		
   </tr>
   <tr>
      <td align='center' width='10%' >&nbsp</td> 
      <td align='center' width='17%' bgcolor='#E9E9E9'>PMC: $pmcCeros </td>
      <td align='center' width='56%' bgcolor='#E9E9E9' style='font-family: Arial; font-size: 8pt'>GOBIERNO AUTONOMO MUNICIPAL $municipio_min</td>       
      <td align='center' width='17%' bgcolor='#E9E9E9' style='font-family: Arial; font-size: 6pt'>Copia</td>    
   </tr>   
   <tr height='1' style='font-family: Arial; font-size: 3pt'>
      <td align='center' width='10%'>&nbsp</td> 
      <td align='center'   width='90%'colspan='3'>$LineaDiv</td>
   </tr>   
</table>

<!-- Tabla 2 -->
<table border='0' width='830px' style='font-family: Arial; font-size: 8pt'>
   <tr>
      <td align='center' width='10%' >&nbsp</td> 
      <td align='center' width='17%' bgcolor='#E9E9E9'>Gestion.:$gestion</td>
      <td align='center' width='56%' bgcolor='#E9E9E9'>IMPUESTO MUNICIPAL A LA PROPIEDAD DE BIENES (IMPBI)</td>       
      <td align='center' width='17%' bgcolor='#E9E9E9'>Fec.Emi:$fecha_emision</td>          
   </tr>
   <tr style='font-family: Arial; font-size: 3pt'>
      <td align='center' width='10%'>&nbsp</td> 
      <td align='center'   width='90%'colspan='3'>$LineaDiv</td>
   </tr>
</table>

<!-- Tabla 3 -->
<table border='0' width='830px' style='font-family: Arial; font-size: 8pt'>
   <tr>
      <td align='center' width='10%'>&nbsp</td> 
      <td align='left'   width='30%' bgcolor='#E9E9E9'>CODIGO MUNICIPAL: $cod_geo</td>
      <td align='center' width='30%' bgcolor='#E9E9E9'>CODIGO CATASTRAL: $cod_cat</td>       
      <td align='right'  width='30%' bgcolor='#E9E9E9'>INMUEBLE No.$id_inmuCeros</td>  
   </tr>
   <tr>
      <td width='10%' >&nbsp </td> 
      <td width='30%' bgcolor='#E9E9E9'>Manzano: $man_ant &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp $cod_predio</td> 
      <td width='30%' bgcolor='#E9E9E9' align='center'>&nbsp Bloque:</td> 
      <td width='30%' bgcolor='#E9E9E9' >&nbsp Piso: &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Dpto.: </td>
   </tr>   
   <tr style='font-family: Arial; font-size: 3pt'>
      <td align='center' width='10%'>&nbsp</td> 
      <td align='center'   width='90%' colspan='3'>$LineaDiv</td>        
   </tr>
</table>

<!-- Tabla 4 -->
<table border='0' width='830px' style='font-family: Arial; font-size: 8pt'>
   <tr>
      <td align='center' width='10%' >&nbsp</td>
      <td align='left'   width='50%' colspan='2' bgcolor='#E9E9E9'><b>IDENTIFICACION CONSTRIBUYENTE</b></td>  
      <td align='left'   width='40%' colspan='2' bgcolor='#E9E9E9'><b>UBICACION DEL INMUEBLE</b></td>   
   </tr>
   <tr>
      <td align='center' width='10%' >&nbsp</td>
      <td align='left'   width='360px' bgcolor='#E9E9E9'>Sujeto Pasivo 1: $titular   </td> 
      <td align='left'   width='70px' bgcolor='#E9E9E9'>$tit_1ci_texto</td>    
      <td align='left'   width='258px' bgcolor='#E9E9E9'>Direccion:<font size=1> $direccion</font>  </td>   
   </tr>
   <tr>
      <td align='center' width='10%' >&nbsp</td>
      <td align='left'   width='360px' bgcolor='#E9E9E9'>Sujeto Pasivo 2: $titular2</td>
      <td align='left'   width='70px' bgcolor='#E9E9E9'>$tit_2ci_texto</td>  
      <td align='left'   width='258px' bgcolor='#E9E9E9'>Ciudad/Localidad: $distrito</td>     
   </tr>
   <tr>
      <td align='center' width='10%' >&nbsp</td>
      <td align='left'   width='360px' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='left'   width='70px' bgcolor='#E9E9E9'>&nbsp</td>  
      <td align='left'   width='258px' bgcolor='#E9E9E9'>Urb: $dir_zonurb</td>     
   </tr>
   <tr style='font-family: Arial; font-size: 3pt'>
      <td align='center' width='10%'>&nbsp</td> 
      <td align='center'   width='90%' colspan='3'>$LineaDiv</td>    
   </tr>
</table>


<!-- Tabla 5 -->
<table border='0' width='830px' style='font-family: Arial; font-size: 8pt'>
   <tr>
      <td align='center' width='10%' >&nbsp</td>
      <td align='left'   width='72%' colspan='4' bgcolor='#E9E9E9'><b>AVALUO DEL TERRENO:&nbsp&nbsp$avaluo_terr Bs.-</b></td>
      <td align='center' width='14%' colspan='2' bgcolor='#E9E9E9'><b>VALOR EMPRESA</b></td> 
   </tr>
   <tr>
      <td align='left' width='10%' >&nbsp</td>
      <td align='left' width='20%' bgcolor='#E9E9E9'>Superficie: </td>
      <td align='left' width='10%' bgcolor='#E9E9E9'>$sup_terr m2</td> 
      <td align='left' width='20%' bgcolor='#E9E9E9'>Forma: $ter_form_texto</td>
      <td align='left' width='6%'   bgcolor='#E9E9E9'>$fact_form</td>
      <td align='center' width='7%' bgcolor='#E9E9E9'>Val en Libre al</td>
      <td align='center' width='7%' bgcolor='#E9E9E9'>Base Imponible</td>             
   </tr>
   <tr>
      <td align='center' width='10%' >&nbsp</td>
      <td align='left'   width='20%' bgcolor='#E9E9E9'>Zona Tributaria: $ben_zona </td>
      <td align='left'   width='10%' bgcolor='#E9E9E9'>$val_m2_terr</td> 
      <td align='left'   width='20%' bgcolor='#E9E9E9'>Ubicacion: $ter_ubi</td>  
      <td align='left'   width='6%' bgcolor='#E9E9E9'>$fact_ubi</td> 
      <td align='center' width='15%' bgcolor='#E9E9E9'>$fecha_emp</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>$base_imp_emp</td>      
   </tr>
   <tr>
      <td align='center' width='10%' >&nbsp</td>
      <td align='left'   width='20%' bgcolor='#E9E9E9'>Topografia: $ter_topo_texto</td>
      <td align='left'   width='10%' bgcolor='#E9E9E9'>$fact_incl</td> 
      <td align='left'   width='20%' bgcolor='#E9E9E9'>Frente/Fondo: A menor B</td> 
      <td align='left'   width='6%' bgcolor='#E9E9E9'>1</td>  
      <td align='center' width='29%' colspan='2' bgcolor='#E9E9E9'><b>EXENCION</b></td>
   </tr>

   <tr>
      <td align='center' width='10%' >&nbsp</td>
      <td align='left'   width='20%' bgcolor='#E9E9E9'>Material de Via: </td>
      <td align='left'   width='10%' bgcolor='#E9E9E9'>$via_mat_texto</td> 
      <td align='left'   width='20%' bgcolor='#E9E9E9'>No de Frente</td> 
      <td align='left'   width='6%' bgcolor='#E9E9E9'>$ter_nofr</td>  
      <td align='center' width='29%' colspan='2' bgcolor='#E9E9E9'>$texto_exencion</td>
   </tr>

   <tr>
      <td align='center' width='10%'>&nbsp</td>
      <td align='left'   width='38%' colspan='4' bgcolor='#E9E9E9'>$servicios_letras</td>
      <td align='left'   width='29%' colspan='2' bgcolor='#E9E9E9'><b>DESCUENTO:   $des_int</b></td>
   </tr>   
   <tr style='font-family: Arial; font-size: 3pt'>
      <td align='left' width='10%'>&nbsp</td> 
      <td align='left' width='90%' colspan='7'>$LineaDiv</td> 
   </tr>
</table>


<!-- Tabla 6 -->
<table border='0' width='830px' style='font-family: Arial; font-size: 8pt'>
   <tr>
      <td align='center' width='10%'>&nbsp</td>
      <td align='left' width='43%' colspan='5' bgcolor='#E9E9E9'><b>AVALUO DE LA CONSTRUCCION:&nbsp&nbsp $avaluo_const Bs.-</b></td>
      <td align='left' width='43%' colspan='3' bgcolor='#E9E9E9'><b>AVALUO TOTAL:&nbsp $avaluo_total Bs.-</b></td>    
   </tr>
   <tr>
      <td align='center' width='10%'>&nbsp</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>Destino/Uso</td> 
      <td align='center' width='14%' bgcolor='#E9E9E9'>Total No. Bloques</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>Superficie Const.</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>Tipologia</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>Tipo Ex.</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>Monto Exento</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>Base Imponible</td>    
   </tr>
   <tr>
      <td align='center' width='10%'>&nbsp</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>$tp_inmu</td> 
      <td align='center' width='14%' bgcolor='#E9E9E9'>$no_de_edificaciones</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>$sup_const </td> 
      <td align='center' width='14%' bgcolor='#E9E9E9'>MIXTA</td> 
      <td align='center' width='10%' bgcolor='#E9E9E9'>$tp_exen</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>$monto_exen</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>$avaluo_total</td>      
   </tr>   
   <tr style='font-family: Arial; font-size: 3pt'>
      <td align='center' width='10%'>&nbsp</td> 
      <td align='center'   width='86%' colspan='8'>$LineaDiv</td>       
   </tr>
</table>


<!-- Tabla 7 -->
<table border='0' width='830px' style='font-family: Arial; font-size: 8pt'>
   <tr>
      <td align='center' colspan='1' width='112px'>&nbsp</td>
      <td align='left'   colspan='7' width='344px' bgcolor='#E9E9E9'><b>CALCULO DE IMPUESTO</b></td> 
      <td align='left'   colspan='4' width='344px' bgcolor='#E9E9E9'><b>FECHA DE VENCIMIENTO: $fecha_venc </b></td> 
   </tr>
   <tr>
      <td align='center' width='10%'>&nbsp</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>Importe</td> 
      <td align='center' width='10%' bgcolor='#E9E9E9'>Valor</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>Tip.Cam</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>Mant.Valor</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>Interes</td> 
      <td align='center' width='7%'  bgcolor='#E9E9E9'>Mora</td>
      <td align='center' width='7%'  bgcolor='#E9E9E9'>Incump.</td>
      <td align='center' width='6%'  bgcolor='#E9E9E9'>Adm.</td>
      <td align='center' width='7%'  bgcolor='#E9E9E9'>Form.</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>Descuento</td>
      <td align='center' width='7%'  bgcolor='#E9E9E9'>Exencion</td>    
   </tr>
   <tr>
      <td align='center' width='10%'>&nbsp</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>$imp_neto</td> 
      <td align='center' width='10%' bgcolor='#E9E9E9'>$sal_favor</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>$t_cam_actual</td> 
      <td align='center' width='8%'  bgcolor='#E9E9E9'>$mant_val</td> 
      <td align='center' width='8%'  bgcolor='#E9E9E9'>$interes</td> 
      <td align='center' width='7%'  bgcolor='#E9E9E9'>$multa_mora</td>
      <td align='center' width='7%'  bgcolor='#E9E9E9'>$multa_incum</td>
      <td align='center' width='6%'  bgcolor='#E9E9E9'>$multa_admin</td>
      <td align='center' width='7%'  bgcolor='#E9E9E9'>$por_form</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>$descuento</td> 
      <td align='center' width='7%'  bgcolor='#E9E9E9'>&nbsp</td>     
   </tr>
   <tr>
      <td align='center' colspan='1' width='10%'>&nbsp</td>
      <td align='left'   colspan='2' width='45%' bgcolor='#E9E9E9'><b>TOTAL A PAGAR: $total_a_pagar Bs.-</b></td> 
      <td align='left'   colspan='9' width='45%' bgcolor='#E9E9E9'><b>Son: $monto_en_letras 00/100 Bolivianos</b></td>
   </tr>

     
</table>

<table border='0' width='830px' style='font-family: Arial; font-size: 9pt'>
   <tr>
      <td align='center' colspan='1'  colspan='1'  width='10%'>&nbsp</td>
      <td align='center' colspan='3' colspan='11' width='90%'>&nbsp</td>
   </tr>
   <tr>
      <td align='center' colspan='1'  colspan='1'  width='10%'>&nbsp</td>
      <td align='center' colspan='3' colspan='11' width='90%'>&nbsp</td>
   </tr>
    <tr>
      <td align='center' colspan='1'  colspan='1'  width='10%'>&nbsp</td>
      <td align='center' colspan='3' colspan='11' width='90%'>&nbsp</td>
   </tr>
    <tr>
      <td align='center' colspan='4'  width='100%'>&nbsp</td>
   </tr>   
   <tr style='font-family: Arial; font-size: 3pt'>
      <td align='center' width='10%' colspan='1'>&nbsp</td>
      <td align='center'   width='90%' colspan='3' >$LineaDiv</td>       
   </tr>   
   <tr>
      <td align='center' width='10%'>&nbsp</td>
      <td align='center' width='26%'>&nbsp</td> 
      <td align='center' width='30%'>FECHA DE CANCELACION</td>
      <td align='center' width='30%'>SELLO DE CAJA: $user_id</td>   
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

