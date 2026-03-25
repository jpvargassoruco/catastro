<?php

########################################
#      Chequear si existen filas       #
########################################	
$sql="SELECT cod_uv FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check_predio = pg_num_rows(pg_query($sql));		
if ($check_predio > 0) {
   $predio_existe = true;
} else $predio_existe = false;
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

$prop_string = $propietario = get_propietarios_con_ci_from_id_inmu ($id_inmu);

$ter_sdoc = 20; 
if ($ter_sdoc == "") { $ter_sdoc = "---"; }
#$ctr_fech = change_date ($info['ctr_fech']);
#pg_free_result($result);
################################################################################
#-------------------------- DEFINIR ZONA DEL PREDIO ---------------------------#
################################################################################
$zona = get_zona_brujula ($cod_cat, $centro_del_pueblo_para_zonas_x, $centro_del_pueblo_para_zonas_y);

########################################
#------- CALCULAR AREA PREDIO ---------#
########################################
$sql="SELECT area(the_geom) FROM predios_ocha WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result=pg_query($sql);
$value = pg_fetch_array($result, null, PGSQL_ASSOC);	 			           
$area= ROUND($value['area'],2); 
pg_free_result($result); 
########################################
#----- CALCULAR AREA EDIFICACIONES ----#
########################################
$sql="SELECT area(the_geom) FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
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
#------------------------------------ ZONA ------------------------------------#
################################################################################	
if ($zona == "NE") {
   $zona = "NORESTE";
} elseif ($zona == "NO") {
   $zona = "NOROESTE";
} elseif ($zona == "SE") {
   $zona = "SURESTE";
} else {
   $zona = "SUROESTE";
}
################################################################################
#---------------------------- DEFINIR ANCHOS DE VIA ---------------------------#
################################################################################	
	 $dist_calle = 10;
   $sql = "SELECT DISTINCT tipo, descrip FROM calles WHERE st_dwithin ((SELECT the_geom FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'),the_geom,$dist_calle)";
	 $check_calle = pg_num_rows(pg_query($sql)); 	
	 if ($check_calle == 0) {		
	    $ancho_de_vias = "El lote no tiene acceso a ninguna calle!";
	 } else {
	    $ancho_de_vias = "";
	    $result=pg_query($sql);
			$i = $j = 0;
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
         foreach ($line as $col_value) {
				    if ($i == 0) {
               $tipo_de_calle = $col_value;
						   if ($j ==0) {
                  $ancho_de_vias = $tipo_de_calle;
							 } else $ancho_de_vias = $ancho_de_vias.", ".$tipo_de_calle;
						} else {
               $ancho_de_vias = $ancho_de_vias." ".$col_value;
							 if ($tipo_de_calle	== "AVENIDA") {
							    $ancho_de_vias = $ancho_de_vias." ES DE 18mts DE ANCHO";
							 } elseif ($tipo_de_calle	== "PASAJE") {
							    $ancho_de_vias = $ancho_de_vias." ES DE 8mts DE ANCHO";									
							 } else {
							    $ancho_de_vias = $ancho_de_vias." ES DE 12mts DE ANCHO";							 
							 } 
							 $i=-1;  					
						}
#echo "I:$i, J: $j; Ancho_de_vias = $ancho_de_vias, Tipo_de_calle = $tipo_de_calle <br>";							
						$i++;
			   }
				 $j++;
      } # END_OF_WHILE	
      pg_free_result($result); 
	 }	
################################################################################
#------------------------------ TIPO DE INMUEBLE ------------------------------#
################################################################################	
$sql="SELECT cod_uv FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check = pg_num_rows(pg_query($sql));
if ($check > 0) {	
   $tp_inmu = "CASA";
} else $tp_inmu = "TERRENO";
################################################################################
#---------------------------- DEFINIR SI HAY OCHAVE ---------------------------#
################################################################################	
$sql="SELECT area(the_geom) FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result=pg_query($sql);
$value = pg_fetch_array($result, null, PGSQL_ASSOC);	 			           
$area_sin_ocha = ROUND($value['area'],2); 
pg_free_result($result); 
if ($area == $area_sin_ocha) {	
   $ochave = "No";
} else $ochave = "Si";
################################################################################
#---------------------------- DEFINIR LOS SERVICIOS ---------------------------#
################################################################################	
$sql="SELECT ser_alc, ser_agu, ser_luz, ser_tel FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result=pg_query($sql);
$value = pg_fetch_array($result, null, PGSQL_ASSOC);
$serv = "";	 			           
if ($value['ser_alc'] == "SI") {
   $serv ="ALCANTARILLADO";
}
if ($value['ser_agu'] == "SI") {
   if ($serv == "") {
	    $serv ="AGUA";
	 } else $serv = $serv.", AGUA";
}
if ($value['ser_luz'] == "SI") {
   if ($serv == "") {
	    $serv ="LUZ";
	 } else $serv = $serv.", LUZ";
}
if ($value['ser_tel'] == "SI") {
   if ($serv == "") {
	    $serv ="TELEFONO";
	 } else $serv = $serv.", TELEFONO";
}
pg_free_result($result); 
################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	

$filename = "C:/apache/htdocs/tmp/ln".$cod_cat.".html";

################################################################################
#------------------------ PREPARAR CONTENIDO PARA IFRAME ----------------------#
################################################################################	
#<table border='1' width='100%' height='161' style='font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'><font face='Tahoma' size='2'>
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
          <h1>LINEA Y NIVEL &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp </h1>
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
						<tr height='30px'>
               <td colspan='5' style='font-family: Times New Roman; font-size: 12pt'>
                  &nbsp  
               </td>				 
            </tr>				    
						<tr valign='top' height='30px'>
						   <td></td>
               <td align='left' colspan='3' style='font-family: Times New Roman; font-size: 11pt; font-weight:bold;'>
                  <br \>LINEA Y NIVEL PARA EDIFICACIONES<br \>
									REVALIDACIÓN DE LINEA Y NIVEL<br \>
									LINEA DE INFORMACIÓN<br \><br \>
               </td>	
							 <td></td>			 
            </tr>
						<tr valign='top' height='30px'>
						   <td></td>
               <td align='right' colspan='3' style='font-family: Times New Roman; font-size: 10pt; font-weight:bold;'>
                  $municipio_min, $dia_actual de $nombre_mes de $ano_actual
               </td>
							 <td></td>		 
            </tr>
						<tr valign='top' height='30px'>
						   <td></td>
               <td align='right' colspan='3' style='font-family: Times New Roman; font-size: 10pt; font-weight:bold;'>
                  <hr></hr>
               </td>
							 <td></td>		 
            </tr>												
	          <tr valign='top' height='180px'>
						   <td></td>	
               <td align='left' colspan='3' style='font-family: Times New Roman; font-size: 10pt; font-weight:bold;'>	 
				          &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Que, el Señor/la Señora &nbsp&nbsp $prop_string &nbsp&nbsp solicita el TRAMITE DE ADJUDICACIÓN DEFINITIVA
				          y se fije Línea y Rasante en su bien inmueble que se encuentra ubicado en la zona: $zona DE LA ALCALDIA MUNICIPAL DE $municipio, DISTRITO $distrito,
				          y se encuentra ubicado en la Unidad Vecinal $cod_uv, Barrio \"$barrio\", Manzano $cod_man, Predio No. $cod_pred\n con una SUPERFICIE de $area m2
				       </td>
							 <td></td>
				    </tr>
	          <tr valign='bottom'>
						   <td width='10%'></td>	
               <td width='27%'></td>
							 <td width='26%'></td>
               <td width='27%' align='center'>							 									
				          ........................................
				       </td>
						   <td width='10%'></td>								 
				    </tr>
	          <tr valign='top'>
						   <td></td>	
               <td></td>
							 <td></td>
               <td align='center' style='font-family: Times New Roman; font-size: 10pt; font-weight:bold;'>							 									
				          Firma del interesado y dirección<br \><br \>
				       </td>
						   <td></td>								 
				    </tr> 
						<tr valign='top' height='40px'>
						   <td></td>
               <td align='center' colspan='3'>
                  <hr></hr>
               </td>
							 <td></td>		 
            </tr>
	          <tr valign='top' height='30px'>
						   <td></td>	
               <td align='left' colspan='3' style='font-family: Times New Roman; font-size: 10pt; font-weight:bold;'>							 									
				          Ancho(s) de Vía(s): $ancho_de_vias
				       </td>
						   <td></td>								 
				    </tr>
	          <tr valign='top' height='30px'>
						   <td></td>	
               <td align='left' colspan='3' style='font-family: Times New Roman; font-size: 10pt; font-weight:bold;'>							 									
				          Línea: LA REVALIDACIÓN, PARA LA CONSTRUCCIÓN DE LA VIVIENDA DE MATERIAL
				       </td>
						   <td></td>								 
				    </tr>
	          <tr valign='top' height='30px'>
						   <td></td>	
               <td align='left' colspan='3' style='font-family: Times New Roman; font-size: 10pt; font-weight:bold;'>							 									
				          Tipo: $tp_inmu
				       </td>
						   <td></td>								 
				    </tr>		
	          <tr valign='top' height='30px'>
						   <td></td>	
               <td align='left' colspan='3' style='font-family: Times New Roman; font-size: 10pt; font-weight:bold;'>							 									
				          Ochave: $ochave
				       </td>
						   <td></td>								 
				    </tr>
	          <tr valign='top' height='50px'>
						   <td></td>	
               <td align='left' colspan='3' style='font-family: Times New Roman; font-size: 10pt; font-weight:bold;'>							 									
				          Retiros: PARA LA CONSTRUCCIÓN DE VIVIENDA DE MATERIAL DEBE RETIRARSE 5 mts DE LA CALLE
				       </td>
						   <td></td>								 
				    </tr>
	          <tr valign='top' height='100px'>
						   <td></td>	
               <td align='left' colspan='3' style='font-family: Times New Roman; font-size: 10pt; font-weight:bold;'>							 									
				          Impuesto:<br \>
									El cálculo de Impuestos se realizó sobre los siguientes datos:<br \>
									SUP SEGUN MENSURA $area<br \>
									SUPERFICIE CONSTRUIDA $edi_area<br \>
									SERVICIOS $serv
				       </td>
						   <td></td>								 
				    </tr>
	          <tr valign='top' height='30px'>
						   <td></td>	
               <td align='left' colspan='3' style='font-family: Times New Roman; font-size: 10pt; font-weight:bold;'>							 									
				          Observaciones que deberá tomar muy en cuenta el propietario:
				       </td>
						   <td></td>								 
				    </tr>				
	          <tr>
						   <td></td>
               <td align='center' valign='top' colspan='3'>
			            <table border='0' style='border:1px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'>   
										 <tr height='30px'>
										    <td width='2%'></td>	
										    <td width='96%' align='left' colspan='3' style='font-family: Times New Roman; font-size: 10pt; font-weight:bold;'>							 									
				                   &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp El otorgamiento de la presente línea y nivel no acredita derecho propietario y la Alcaldía Municipal salva su responsabilidad<br \>
									         &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Al cavar cimientos deberá pedirse ratificación de la Línea y Nivel sin cuyo requisito piedre su valor la presente solicitud, no
													 responsabilizándose la H. Alcaldía Municipal, procediéndose a la demolición de lo construido.<br \>
													 &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp La edificación deberá ponerse en línea Municipal y dejar expedita la vía, caso contrario pierde valor el presente Documento.<br \>
													 &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp La concesión de Línea y Nivel en todos los casos es solo válido por un año. 
												<td width='2%'></td>
										 </tr>																 					
                  </table>		
			         </td>
							 <td></td>				
				    </tr>	
	          <tr height='120px' valign='bottom'>
               <td colspan='5' align='center'>							 									
				          ........................................
				       </td>						 
				    </tr>
	          <tr valign='top'>
               <td colspan='5' align='center' style='font-family: Times New Roman; font-size: 10pt; font-weight:bold;'>							 									
				          Jefe del Plan Regulador
				       </td>							 
				    </tr> 						
		        <tr height='100%'>
               <td colspan='5'>							 									
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
if (!$handle = fopen($filename, 'w')) {
   $error = 2; 
}
if (!fwrite($handle, $content)) {
   $error = 3; 
}
fclose($handle);

?>