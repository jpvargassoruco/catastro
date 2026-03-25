<?php
################################################################################
#--------------------- SACAR INFORMACION DE LA BASE DE DATOS ------------------#
################################################################################	

# FUNCION GET_ZONA
$ben_zona = get_zona ($cod_cat);	
if ($ben_zona == "0") {			
   $ben_zona = "-";
}
# FUNCION GET_BARRIO
$barrio = get_barrio ($cod_cat);	
if ($barrio == "0") {			
   $barrio = "-";
}		
### LEER DATOS DE PROPIETARIO DE INFO_INMU	

$prop_string = $propietario = get_propietarios_con_ci_from_id_inmu ($id_inmu);

$max_prop_stringlength1 = 104;
$max_prop_stringlength2 = 95;
if (strlen ($prop_string) > $max_prop_stringlength1) {
   $font_size_prop = "7pt";
} elseif (strlen ($prop_string) > $max_prop_stringlength2) {
   $font_size_prop = "8pt";
} else {
   $font_size_prop = "9pt";
}
$ter_sdoc = 20;
#$ter_sdoc = $info['ter_sdoc']; 
if ($ter_sdoc == "") { $ter_sdoc = "---"; }
################################################################################
#-------------------------- DEFINIR ZONA DEL PREDIO ---------------------------#
################################################################################
$zona = get_zona_brujula ($cod_cat, $centro_del_pueblo_para_zonas_x, $centro_del_pueblo_para_zonas_y);
################################################################################
#---------------------------------- NOTA --------------------------------------#
################################################################################
$sql="SELECT nota_plano FROM imp_base";
$result_nota = pg_query($sql);
$info = pg_fetch_array($result_nota, null, PGSQL_ASSOC);
$nota_plano_catastral = utf8_decode ($info['nota_plano']);
pg_free_result($result_nota);	
########################################
#------- CALCULAR AREA PREDIO ---------#
########################################
$sql="SELECT area(the_geom) FROM predios_ocha WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result=pg_query($sql);
$value = pg_fetch_array($result, null, PGSQL_ASSOC);	 			           
$area= ROUND($value['area'],2); 
pg_free_result($result); 
########################################
#----- CALCULAR AREA EDIFICACIONES ----#
########################################
$sql="SELECT area(the_geom) FROM edificaciones WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check = pg_num_rows(pg_query($sql));
if ($check == 0) {
	 $edi_area = 0;
} else {
   $result=pg_query($sql);
   $edi_area = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
      foreach ($line as $col_value) {
         $edi_area = $edi_area + $col_value; 	
      }
   } # END_OF_WHILE	
	 $edi_area = ROUND($edi_area,2);	
	 pg_free_result($result);			
}
################################################################################
#------------------------------------ FECHA -----------------------------------#
################################################################################	
$nombre_mes = monthconvert ($mes_actual);

################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	

$filename = "C:/apache/htdocs/tmp/us".$cod_cat.".html";

################################################################################
#------------------------ PREPARAR CONTENIDO PARA IFRAME ----------------------#
################################################################################	
$content = " 
<div align='left'>
<table border='1' width='100%' height='161' style='border:2px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'><font face='Tahoma' size='2'>
   <tr>
      <td rowspan='2' width='50%'>
			   <table border='0' width='100%' style='font-family: Tahoma; font-size: 9pt' bgcolor='#FFFFFF'><font face='Tahoma' size='2'>
            <tr>
               <td width='20%'>
					<img src='http://$server/$folder/css/$nomlog' alt='imagen' width='115' height='101' border='0'>
               </td>
               <td width='80%' align='center'>
                  <p>GOBIERNO MUNICIPAL DE $municipio</p>
									<p>CONSEJO DEL PLAN REGULADOR</p> 
									- Distrito $distrito_min -
               </td>
            </tr>  							 
         </table>
			</td>	   							 			 
      <td align='right' valign='top' width='50%'>
          $fecha2 - $hora <a href='javascript:print(this.document)'>
          <img border='0' src='http://$server/$folder/graphics/printer.png' width='15' height='15'></a>			 
          <h1>USO DE SUELOS &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp </h1>
      </td>
   </tr>
   <tr height='30'>
      <td align='center'>
         <font style='font-family: Tahoma; font-size: 10pt; font-weight:bold;'>CODIGO : $cod_geo/$cod_cat</font>
      </td>		 				
   </tr>  	 	 	  
	 <tr>
      <td align='center' valign='top' height='1050px' colspan='2' bgcolor='#FFFFFF'>
			   <table border='0' width='100%' height='1050px' style='border:0px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'>
						<tr height='160px'>
               <td colspan='5' style='font-family: Times New Roman; font-size: 12pt'>
                  &nbsp  
               </td>				 
            </tr>				    
						<tr valign='top' height='160px'>
               <td height='50px' align='center' colspan='5' style='font-family: Times New Roman; font-size: 13pt; font-weight:bold;'>
                  Certificado de Uso de Suelos Urbanos 
               </td>				 
            </tr>
	          <tr valign='top' height='400px'>
						   <td></td>	
               <td align='left' colspan='3' style='font-family: Times New Roman; font-size: 10pt; font-weight:bold;'>	 
        CERTIFICA:<br \><br \>
				
				&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Que, los terrenos Urbanos situados en el Distrito $cod_uv, Barrio \"$barrio\", Manzano $cod_man, Predio No. $cod_pred\n
				<br \>Perteneciente al Señor/a la Señora $prop_string <br \>
				&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Estén comprendidas en: ÁREA DE DOMINIO DE LA ALCALDÍA $municipio, Distrito $distrito.
				TRAMITE CONCLUIDO DE ADJUDICACIÓN DEFINITIVA y por consiguiente pueden realizarse LOS TRABAJOS DE CONSTRUCCIÓN DE CASA DE MATERIAL.
				<br \><br \>
				&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Estándole prohibido realizar EXCAVACIONES FUERA DE LINEA, SIN PLANO DE CONSTRUCCIÓN.
				Sin previa autorizaciÓn de la Jefatura de Catastro $municipio.
				<br \><br \>
				Es cuanto certifico en honor a la verdad.
				<br \><br \>
				$municipio_min, $dia_actual de $nombre_mes de $ano_actual
				       </td>
							 <td></td>
				    </tr>
	          <tr valign='bottom'>
						   <td width='10%'></td>	
               <td width='27%' align='center'>							 									
				          ........................................
				       </td>
							 <td width='26%'></td>
               <td width='27%' align='center'>							 									
				          ........................................
				       </td>
			         </td>
						   <td width='10%'></td>								 
				    </tr>
	          <tr valign='top'>
						   <td></td>	
               <td align='center' style='font-family: Times New Roman; font-size: 10pt; font-weight:bold;'>							 									
				          Inspector
				       </td>
							 <td></td>
               <td align='center' style='font-family: Times New Roman; font-size: 10pt; font-weight:bold;'>							 									
				          Jefe del Plan Regulador
				       </td>
			         </td>
						   <td></td>								 
				    </tr> 
						<tr>
               <td>
                  &nbsp 
               </td>				 
            </tr>																	 					
         </table>		
			</td>						 
   </tr>								  	 
</table>
</div>";
################################################################################
#------------------- CHEQUEAR SI SE PUEDE ABRIR EL ARCHIVO --------------------#
################################################################################	
$content = utf8_decode($content);
if (!$handle = fopen($filename, 'w')) {
   $error = 2; 
}
if (!fwrite($handle, $content)) {
   $error = 3; 
}
fclose($handle);

?>