<?php

$id_patente = $_POST['id_patente'];

################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	
$filename = "C:/apache/htdocs/tmp/lic".$id_patente.".html";

   ########################################
   #---- LEER DATOS DE TABLA PATENTES ----#
   ########################################	
   $sql="SELECT * FROM patentes WHERE id_patente = '$id_patente'";
   $result=pg_query($sql);
   $info = pg_fetch_array($result, null, PGSQL_ASSOC);
   $cod_geo = $info['cod_geo'];
   $id_inmu = $info['id_inmu'];
   $id_contrib = $info['id_contrib'];
   $act_pat = $info['act_pat'];
   $act_rub = $info['act_rub'];
   $act_rub = get_rubro($act_rub);
   $act_raz = utf8_decode($info['act_raz']);
   $act_nit = $info['act_nit'];
   if ($act_nit == "-1") {
      $act_nit = "---";
   }
   $act_tel = $info['act_tel'];
   if ($act_tel == "") {
      $act_tel = "---";
   }				 
   $act_fech = $info['act_fech'];
   if ($act_fech == "1900-01-01") {
      $act_fech = "---";
   } else $act_fech = change_date($act_fech);				 
   $act_sup = $info['act_sup'];
   if (($act_sup == "") OR ($act_sup == "-1")) {
      $act_sup = "---";
   } else $act_sup = $act_sup." m²";		 
   $act_obs = utf8_decode($info['act_obs']);
   pg_free_result($result);
   ########################################
   #------------ OTROS DATOS -------------#
   ########################################	 
   $act_prop = get_contrib_nombre($id_contrib);
   $act_prop_ci = get_contrib_ci($id_contrib);		
	# $act_dom = get_contrib_dom($id_contrib);		 
   $cod_cat = get_codcat_from_id_inmu($id_inmu);
   $cod_uv = get_cod_uv_from_id_inmu ($id_inmu);
   $cod_man = get_cod_man_from_id_inmu ($id_inmu); 
	 
   ########################################
   #---------- ANCHO DE FILAS ------------#
   ########################################		 
   $ancho_primera_fila = 16;
################################################################################
#------------------------ PREPARAR CONTENIDO PARA GRABAR ----------------------#
################################################################################
$content = " 
<div align='left'>
<!-- Fila 1: BOTON IMPRIMIR -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>
   <tr height='$ancho_primera_fila'>
      <td align='right' valign='top' width='100%' style='font-family: Arial; font-size: 8pt'>
          $fecha2 - $hora <a href='javascript:print(this.document)'>
          <img border='0' src='http://$server/$folder/graphics/printer.png' width='15' height='15'></a>	
      </td>			
   </tr>
</table>
<!-- Fila 2 GOB. MUN. -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>	 
	 <tr height='30'> 	 
      <td align='center' width='20%'>
         &nbsp
      </td>
      <td align='center' width='60%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='center' width='20%'>
         &nbsp
      </td>
   </tr>
</table>
<!-- Fila 3 DE CONCE. -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>	 
	 <tr height='30'> 	 
      <td align='center' width='25%'>
         &nbsp
      </td>
      <td align='center' width='50%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='center' width='25%'>
         &nbsp
      </td>
   </tr>
</table>
<!-- Fila 4  PADRON Nº -->
<table border='0' width='100%' style='font-family: Arial; font-size: 3pt'>		 			 
	 <tr height='30'>	 	 
      <td align='left' width='5%'>
         &nbsp
      </td>
      <td align='left' width='50%'>
         &nbsp
      </td>
      <td align='left' width='10%'>
         &nbsp
      </td>
      <td align='left' width='35%'>
         &nbsp
      </td>
   </tr> 		
	 <tr style='font-family: Arial; font-size: 9pt'>							
      <td>
         &nbsp
      </td>
      <td align='left'>
         &nbsp 00000001
      </td>	
      <td align='center' bgcolor='#E9E9E9'>
         &nbsp 
      </td>
      <td align='left' style='font-family: Arial; font-size: 14pt'>
         &nbsp $act_pat
      </td>		
   </tr> 		
</table>
<!-- Fila 5 LIC. DE FUNC. -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>	
	 <tr height='15'>
      <td colspan='3'>&nbsp </td>		  
   </tr>   
	 <tr height='30'> 	 
      <td align='center' width='5%'>
         &nbsp
      </td>
      <td align='center' width='90%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='center' width='5%'>
         &nbsp
      </td>
   </tr>
</table>
<!-- Fila 6 DE ACT. ECON. -->
<table border='0' width='100%' style='font-family: Arial; font-size: 4pt'>	 
	 <tr height='30'> 	 
      <td align='center' width='7%'>
         &nbsp
      </td>
      <td align='center' width='86%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='center' width='7%'>
         &nbsp
      </td>
   </tr>
	 <tr height='15'>
      <td colspan='3'>&nbsp </td>		  
   </tr>	 
</table>
<!-- Fila 7 RAZON SOCIAL -->
<table border='0' width='100%' style='font-family: Arial; font-size: 9pt'>		  	 	    
	 <tr>	 	 
      <td width='5%'>
         &nbsp
      </td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='85%' style='font-family: Arial; font-size: 9pt'>
         &nbsp $act_raz
      </td>
   </tr>			
</table>
<!-- Fila 8 PROPIETARIO -->
<table border='0' width='100%' style='font-family: Arial; font-size: 9pt'>		  	 	    
	 <tr>	 	 
      <td width='5%'>
         &nbsp
      </td>
      <td align='center' width='15%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='80%' style='font-family: Arial; font-size: 9pt'>
         &nbsp $act_prop
      </td>
   </tr>			
</table>
<!-- Fila 9 CARNET DE IDENTIDAD -->
<table border='0' width='100%' style='font-family: Arial; font-size: 9pt'>		  	 	    
	 <tr>	 	 
      <td width='5%'>
         &nbsp
      </td>
      <td align='center' width='12%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='83%' style='font-family: Arial; font-size: 9pt'>
         &nbsp $act_prop_ci
      </td>
   </tr>			
</table>
<!-- Fila 10 ACTIVIDAD DESARROLLADA -->
<table border='0' width='100%'>		  	 	    
	 <tr>	 	 
      <td width='5%'>
         &nbsp
      </td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='81%' style='font-family: Arial; font-size: 9pt'>
         &nbsp $act_rub
      </td>
   </tr>			
</table>
<!-- Fila 11 DIRECCION -->
<table border='0' width='100%'>		  	 	    
	 <tr>	 	 
      <td width='5%'>
         &nbsp
      </td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='81%' style='font-family: Arial; font-size: 9pt'>
         &nbsp $act_dir
      </td>
   </tr>			
</table>
<!-- Fila 11 SUPERFICIE -->
<table border='0' width='100%'>		  	 	    
	 <tr>	 	 
      <td width='5%'>
         &nbsp
      </td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='81%' style='font-family: Arial; font-size: 9pt'>
         &nbsp $act_sup
      </td>
   </tr>			
</table>
<!-- Fila 12 CODIGO CATASTRAL -->
<table border='0' width='100%'>		  	 	    
	 <tr>	 	 
      <td width='5%'>
         &nbsp
      </td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='31%' style='font-family: Arial; font-size: 9pt'>
         &nbsp $cod_cat
      </td>
      <td align='center' width='3%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='22%' style='font-family: Arial; font-size: 9pt'>
         &nbsp $cod_uv
      </td>
      <td align='center' width='3%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td align='left' width='22%' style='font-family: Arial; font-size: 9pt'>
         &nbsp $cod_man
      </td>						
   </tr>			
</table>
<table border='0' width='100%'>		  	 	    
	 <tr height='15'>
      <td colspan='3'>&nbsp </td>		  
   </tr>	
	 <tr>	 	 
      <td width='8%'>
         &nbsp
      </td>
      <td align='center' width='84%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='8%'>
         &nbsp
      </td>
   </tr>			
</table>
<table border='0' width='100%'>		  	 	    
	 <tr height='15'>
      <td colspan='3'>&nbsp </td>		  
   </tr>	
	 <tr>	 	 
      <td width='5%'>
         &nbsp
      </td>
      <td align='center' width='90%' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td width='5%'>
         &nbsp
      </td>
   </tr>
	 <tr>	 	 
      <td>
         &nbsp
      </td>
      <td align='center' bgcolor='#E9E9E9'>
         &nbsp
      </td>
      <td>
         &nbsp
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