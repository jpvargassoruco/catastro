<?php

########################################
#      Chequear si existen filas       #
########################################	
$sql="SELECT cod_uv FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check_predio = pg_num_rows(pg_query($sql));		

################################################################################
#------------------------ INFORMACION DE INFO_PREDIO --------------------------#
################################################################################	

# FUNCION GET_ZONA
$ben_zona = get_zona ($id_inmu);
if ($ben_zona == "0") {			
   $ben_zona = "-";
}
# FUNCION GET_BARRIO
$barrio = get_barrio ($id_inmu);	
if ($barrio == "0") {			
   $barrio = "-";
}		

include "siicat_planos_leer_datos.php";
################################################################################
#-------------------------- DEFINIR ZONA DEL PREDIO ---------------------------#
################################################################################
$zona = get_zona_brujula ($cod_cat, $centro_del_pueblo_para_zonas_x, $centro_del_pueblo_para_zonas_y);
echo $zona;
if ($zona === "SE")  { 
	$zona = "SUR ESTE";
} elseif ($zona === "SO")  { $zona = "SUR OESTE";
} elseif ($zona === "NE")  { $zona = "NOR ESTE";
} elseif ($zona === "NO")  { $zona = "NOR OESTE"; 
} elseif ($zona === "N")  { $zona = "NORTE"; 
} elseif ($zona === "S")  { $zona = "SUR"; 
} elseif ($zona === "E")  { $zona = "ESTE"; 
} elseif ($zona === "O")  { $zona = "OESTE"; 
}
################################################################################
#------------------------- INFORMACION DE INFO_EDIF ---------------------------#
################################################################################	
$sql="SELECT edi_num, edi_piso, edi_tipo, edi_ano, edi_edo 
		FROM info_edif 
		WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' 
		ORDER BY edi_num, edi_piso";
		
$no_de_edificaciones = pg_num_rows(pg_query($sql));
$result=pg_query($sql);
$i = $j = 0;
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
   foreach ($line as $col_value) {
	    if ($i == 0) {
			   $edi_num[$j] = $col_value;
			} elseif ($i == 1) {
			   $edi_piso[$j] = $col_value;
			} elseif ($i == 2) {
			   $edi_tipo[$j] = utf8_decode(abr($col_value));
			} elseif ($i == 3) {
			   $edi_ano[$j] = $col_value;
				 $ant = $ano_actual - $edi_ano[$j];
				 if (($ant >= 0) AND ($ant <= 5)) {
				    $antiguedad[$j] = "0 - 5 años";
				 } elseif (($ant >= 6) AND ($ant <= 10)) {
				    $antiguedad[$j] = "6 - 10 años";
				 } elseif (($ant >= 11) AND ($ant <= 15)) {
				    $antiguedad[$j] = "11 - 15 años";
				 } elseif (($ant >= 16) AND ($ant <= 20)) {
				    $antiguedad[$j] = "16 - 20 años";
				 } elseif (($ant >= 21) AND ($ant <= 25)) {
				    $antiguedad[$j] = "21 - 25 años";
				 } elseif (($ant >= 26) AND ($ant <= 30)) {
				    $antiguedad[$j] = "26 - 30 años";
				 } elseif (($ant >= 31) AND ($ant <= 35)) {
				    $antiguedad[$j] = "31 - 35 años";
				 } elseif (($ant >= 36) AND ($ant <= 40)) {
				    $antiguedad[$j] = "36 - 40 años";
				 } elseif (($ant >= 41) AND ($ant <= 45)) {
				    $antiguedad[$j] = "41 - 45 años";
				 } elseif (($ant >= 46) AND ($ant <= 50)) {
				    $antiguedad[$j] = "46 - 50 años";
				 } else {
				    $antiguedad[$j] = "> 51 años";
				 } 
			} else {
			   $edi_edo[$j] = utf8_decode(abr($col_value));
				 $i = -1;
				 $j++;
			}
			$i++;
   }
}
pg_free_result($result);
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
$sql="SELECT edi_num,edi_piso FROM info_edif WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' ORDER BY edi_num, edi_piso";
$check = pg_num_rows(pg_query($sql));
if ($check == 0) {
	 $edi_area = 0;
} else {
   $edi_area = 0;
   $result=pg_query($sql);
   $i = $j = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
      foreach ($line as $col_value) {
			   if ($i == 0) {
				    $edi_num_temp = $col_value;
				 } else {
				    $edi_piso_temp = $col_value;
						$sql2="SELECT area(the_geom) FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND edi_num = '$edi_num_temp' AND edi_piso = '$edi_piso_temp'";
						$check2 = pg_num_rows(pg_query($sql2));
            if ($check2 == 0) {
	             $edi_area = $edi_area + 0;
							 $area_edif[$j] = 0;
						} else {
						   $result2=pg_query($sql2);
               $value = pg_fetch_array($result2, null, PGSQL_ASSOC);	 			           
               $area_edif[$j]= ROUND($value['area'],2); 
               pg_free_result($result2);
							 $edi_area = $edi_area + $area_edif[$j];
						}
						$i = -1;
				 }
				 $i++;
			}
			$j++;
	 } # END_OF_WHILE
}

################################################################################
#------------------------ VALORACION DE EDIFICACIONES -------------------------#
################################################################################			
$gestion_actual = $ano_actual-1;
$sql="SELECT * FROM info_edif WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' ORDER BY edi_num, edi_piso ASC";
$no_de_edificaciones = pg_num_rows(pg_query($sql));
if ($no_de_edificaciones > 0) {
	$i = $k = 0;
	$result_edif = pg_query($sql);
	while ($line = pg_fetch_array($result_edif, null, PGSQL_ASSOC)) {	
		$line_value[$i] = $no_de_objetos_validos[$i] = 0;				 	 			           
		foreach ($line as $col_value) {
			$column_edif = get_column_edif ($k);
			$sql="SELECT valuacion FROM imp_valua_viv_materiales WHERE tipo = '$column_edif' AND material = '$col_value'";				 
			$check_valua = pg_num_rows(pg_query($sql));
			if ($check_valua > 0) {
				$result_valua = pg_query($sql);
				$info_valua = pg_fetch_array($result_valua, null, PGSQL_ASSOC);						
				$valua_temp = trim($info_valua['valuacion']);
			if ($valua_temp == "margin") {						 
				$line_value[$i] = $line_value[$i] + 1;
			} elseif ($valua_temp == "mecono") {						 
				$line_value[$i] = $line_value[$i] + 2;
			} elseif ($valua_temp == "econo") {							 
				$line_value[$i] = $line_value[$i] + 3;
			} elseif ($valua_temp == "bueno") {						 
				$line_value[$i] = $line_value[$i] + 4;
			} elseif ($valua_temp == "mbueno") {							 
				$line_value[$i] = $line_value[$i] + 5;
			} elseif ($valua_temp == "lujoso") {
				$line_value[$i] = $line_value[$i] + 6;
			}
				$no_de_objetos_validos[$i]++; 							 
			}																																			
			$k++; 
		} 
		$line_media[$i] = ROUND($line_value[$i]/$no_de_objetos_validos[$i],2); 
		if ($line_media[$i] < 1.5) {
			$clase[$i] = "Marginal";
		} elseif ($line_media[$i] < 2.5) {
			$clase[$i] = "Muy Económico";
		} elseif ($line_media[$i] < 3.5) {
			$clase[$i] = "Económico";
		} elseif ($line_media[$i] < 4.5) {
			$clase[$i] = "Bueno";
		} elseif ($line_media[$i] < 5.5) {
			$clase[$i] = "Muy Bueno";
		}	 else {
			$clase[$i] = "Lujoso";
		}							
		$k = 0;
		$i++;				
	} 
}
################################################################################
#------------------------------- COLINDANTES ----------------------------------#
################################################################################	
$id_predio = get_id_predio ($cod_geo,$cod_uv,$cod_man,$cod_pred);
$sql="SELECT * FROM colindantes WHERE id_predio = '$id_inmu'";
$check_col = pg_num_rows(pg_query($sql));
if ($check_col > 0 ) {	
	$result_col = pg_query($sql);
	$info_col = pg_fetch_array($result_col, null, PGSQL_ASSOC);
	
	$col_norte_nom = utf8_decode ($info_col['norte_nom']);
	if (strlen ($col_norte_nom) < 116) {
		$font_size_norte =  "8pt";
	} else $font_size_norte =  "6pt";
		$col_norte_med  = utf8_decode ($info_col['norte_med']);
		$font_size_norte_med =  "8pt";

	$col_noroes_nom = utf8_decode ($info_col['noroes_nom']);
	if (strlen ($col_noroes_nom) < 116) {
		$font_size_noroes =  "8pt";
	} else $font_size_noroes =  "6pt";
		$col_noroes_med  = utf8_decode ($info_col['noroes_med']);
		$font_size_noroes_med =  "8pt";

	$col_norest_nom = utf8_decode ($info_col['norest_nom']);
	if (strlen ($col_norest_nom) < 116) {
		$font_size_norest =  "8pt";
	} else $font_size_norest =  "6pt";
		$col_norest_med  = utf8_decode ($info_col['norest_med']);
		$font_size_norest_med =  "8pt";

		$col_surest_nom = utf8_decode ($info_col['surest_nom']);
	if (strlen ($col_surest_nom) < 116) {
		$font_size_surest = "8pt";
	} else $font_size_surest = "6pt";							
		$col_surest_med = utf8_decode ($info_col['surest_med']);	
		$font_size_surest_med =  "8pt";	

		$col_sur_nom = utf8_decode ($info_col['sur_nom']);
	if (strlen ($col_sur_nom) < 116) {
		$font_size_sur = "8pt";
	} else $font_size_sur = "6pt";							
		$col_sur_med  = utf8_decode ($info_col['sur_med']);
		$font_size_sur_med =  "8pt";			

		$col_suroes_nom = utf8_decode ($info_col['suroes_nom']);
	if (strlen ($col_suroes_nom) < 116) {
		$font_size_suroes = "8pt";
	} else $font_size_suroes = "6pt";							
		$col_suroes_med = utf8_decode ($info_col['suroes_med']);	
		$font_size_suroes_med =  "8pt";

		$col_este_nom = utf8_decode ($info_col['este_nom']);
	if (strlen ($col_este_nom) < 116) {
		$font_size_este =  "8pt";
	} else $font_size_este =  "6pt";									
		$col_este_med = utf8_decode ($info_col['este_med']);
		$font_size_este_med =  "8pt";				

		$col_oeste_nom = utf8_decode ($info_col['oeste_nom']);
	if (strlen ($col_oeste_nom) < 116) {
		$font_size_oeste =  "8pt";
	} else $font_size_oeste =  "6pt";			
		$col_oeste_med  = utf8_decode ($info_col['oeste_med']);
		$font_size_oeste_med =  "8pt";							
###############################################################
	pg_free_result($result_col);
} else { 
	$col_norte_nom = $col_sur_nom = $col_este_nom = $col_oeste_nom = "";	
	$col_norte_med = $col_sur_med = $col_este_med = $col_oeste_med = "";
	$font_size_norte = $font_size_sur = $font_size_este = $font_size_oeste = "8pt";
	$font_size_norte_med = $font_size_sur_med = $font_size_este_med = $font_size_oeste_med = "8pt";	 
}	
if ($col_norte_nom == "") {
	$limite1 = "NORESTE";
	$Colind1 = $col_norest_nom;
	$Medida1 = $col_norest_med;

} else { 
	$limite1 = "NORTE";
	$Colind1 = $col_norte_nom;
	$Medida1 = $col_norte_med;

}
if ($col_sur_nom == "") {
	$limite2 = "NOROESTE";
	$Colind2 = $col_noroes_nom;
	$Medida2 = $col_noroes_med;

} else { 
	$limite2 = "SUD";
	$Colind2 = $col_sur_nom;
	$Medida2 = $col_sur_med;

}

if ($col_este_nom == "") {
	$limite3 = "SUDESTE";
	$Colind3 = $col_surest_nom;
	$Medida3 = $col_surest_med;

} else { 
	$limite3 = "ESTE";
	$Colind3 = $col_este_nom;
	$Medida3 = $col_este_med;

}

if ($col_oeste_nom == "") {
	$limite4 = "SUDOESTE";
	$Colind4 = $col_suroes_nom;
	$Medida4 = $col_suroes_med;

} else { 
	$limite4 = "OESTE";
	$Colind4 = $col_oeste_nom;
	$Medida4 = $col_oeste_med;

}
################################################################################
#--------------------------------- GRAVAMEN -----------------------------------#
################################################################################
$sql="SELECT texto FROM gravamen WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";	 
$gravamen_check = pg_num_rows(pg_query($sql));   
if ($gravamen_check == 0) {
   $observ_fila = "&nbsp";

} else {
   $observ_fila = "EL PREDIO TIENE UN GRAVAMEN !"; 
}	 
################################################################################
#---------------------------------- NOTA --------------------------------------#
################################################################################
$sql="SELECT nota_tec FROM imp_base";
$result_nota = pg_query($sql);
$info = pg_fetch_array($result_nota, null, PGSQL_ASSOC);
$nota_informe_tecnico = utf8_decode ($info['nota_tec']);
pg_free_result($result_nota);	
################################################################################
#---------------------- SELECCIONAR GEOMETRIA PARA IFRAME ---------------------#
################################################################################	
      $sql="SELECT cod_uv FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
      $check_predio = pg_num_rows(pg_query($sql));
      if ($check_predio > 0) {
			   $geometria_existe = true;
         $result1=pg_query("SELECT (extent3d(the_geom)) FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
         $str_extent = pg_fetch_array($result1, null, PGSQL_ASSOC);
         $extent = $str_extent['extent3d'];
         #------------------------- Extraer coordenadas de EXTENT
         $xt_x = $xtent_x = array();
         $xt_y = $xtent_y = array();
         $x =0;
         $z = 0;
         $i =0;
         $j = 6; 
         while ($i <= strlen($extent)) {
            $char = substr($extent, $i, 1);
	          if (($char == ' ') AND ($z == 0)) {
               $xt_x[$x] = substr($extent,$j,$i-$j);
			         $xtent_x[$x] =ROUND($xt_x[$x],2);
			         $j=$i+1;
			         $z = 1;
#			echo "$point_x[$x]<br /> ";
	          } else if (($char == ' ') AND ($z == 1)) {
               $xt_y[$x] = substr($extent,$j,$i-$j);
			         $xtent_y[$x] =ROUND($xt_y[$x],2);		
		           $j=$i+3;			
			         $z = 0;
#			echo "$point_y[$x]<br /> ";
			         $x++;
	          } 
#  echo "$char $i $j<br />";
	          $i++;   
         }
         $centerx = ($xtent_x[0] + $xtent_x[1])/2;
         $centery = ($xtent_y[0] + $xtent_y[1])/2;
			   # VALOR MAS PEQUEÑO --> MAS ZOOM
			   #$factor_zoom = 2.8;
			   #$factor_zoom = 2.6;
				 #$factor_zoom1 = 8;  # VALOR PARA PLANO DE UBICACION
				 # FACTOR ZOOM PARA PLANO DE UBICACION
				 $ext_x = sqrt(($xtent_x[0] - $xtent_x[1])*($xtent_x[0] - $xtent_x[1]));
				 $ext_y = sqrt(($xtent_y[0] - $xtent_y[1])*($xtent_y[0] - $xtent_y[1]));				 
#echo "EXT X y es $ext_x, EXT Y y es $ext_y<br />\n";				 
				 if (($ext_x > 110) OR ($ext_y > 110)) {
						$factor_zoom1 = 5;
         } elseif (($ext_x > 90) OR ($ext_y > 90)) {
						$factor_zoom1 = 6;
         } elseif (($ext_x > 70) OR ($ext_y > 70)) {
						$factor_zoom1 = 7;					
         } elseif (($ext_x > 50) OR ($ext_y > 50)) {		 		 
				   $factor_zoom1 = 8;
         } elseif (($ext_x > 30) OR ($ext_y > 30)) {		 		 
				   $factor_zoom1 = 10;					 
				 } else {
	          #$factor_zoom = 3.2;
						$factor_zoom1 = 12;
         }  				 
         if ($centerx-$xtent_x[0] > $centery-$xtent_y[0]) {
#            $delta = ($centerx-$xtent_x[0])* $factor_zoom;
						$delta1 = ($centerx-$xtent_x[0])* $factor_zoom1; 
#echo "DELTA viene de X y es $delta<br />\n";
         } else {
#            $delta = ($centery-$xtent_y[0])* $factor_zoom;
						$delta1 = ($centery-$xtent_y[0])* $factor_zoom1;
#echo "DELTA viene de Y y es $delta<br />\n";
         }

         $xmin1=$centerx- $delta1;
         $xmax1=$centerx+ $delta1; 
         $ymin1=$centery- $delta1;
         $ymax1=$centery+ $delta1;				 	
				 pg_free_result($result1);
		 ########################################
         #   ENVIAR SELECCIONADO A TEMP-POLY    #
         ########################################		
				 pg_query("DELETE FROM temp_poly WHERE user_id = '$user_id' OR user_id IS NULL");				 
				 pg_query("INSERT INTO temp_poly (edi_num, edi_piso, the_geom) SELECT edi_num, edi_piso, the_geom FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
			   pg_query("UPDATE temp_poly SET user_id = '$user_id', numero = 44 WHERE edi_num > '0' AND user_id IS NULL");				 
				 pg_query("INSERT INTO temp_poly (cod_uv, cod_man, cod_pred, the_geom) SELECT cod_uv, cod_man, cod_pred, the_geom FROM predios_ocha WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
			   pg_query("UPDATE temp_poly SET cod_cat = '$cod_cat', user_id = '$user_id', numero = 55 WHERE user_id IS NULL AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
      } else $geometria_existe = false;

################################################################################
#------------------------       CONTROL DE No C.C.       ----------------------#
################################################################################
$sql="SELECT * FROM docu_control WHERE id_doc = 2";
$result=pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$nro_doc = $info['nro_doc']+1;
$nro_doc   = str_pad($nro_doc, 6, "0", STR_PAD_LEFT);
$titulo5 = $info['titulo1'];

################################################################################
#------------------------- GENERAR PLANO DE UBICACION -------------------------#
################################################################################	
if ($geometria_existe) {
   include "siicat_generar_mapfile_planocatastral_ubicacion.php";
}
################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	

$filename = "C:/apache/htdocs/tmp/informetecnico".$cod_cat.".html";

################################################################################
#------------------------ PREPARAR CONTENIDO PARA IFRAME ----------------------#
################################################################################	
$propietario = utf8_encode($propietario);
$direccion = utf8_encode($direccion);
$espacio_entre_tablas = 30;
$content = "
<div align='left'>
<table border='1' width='100%' height='161' style='border:2px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'><font face='Tahoma' size='2'>
	<tr>
		<td rowspan='2' colspan='4'>
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
		<td align='right' valign='top' colspan='4'>
			<font style='font-family: Tahoma; font-size: 8pt; font-weight:bold; color:red'>No. $nro_doc/$gestion_actual</font>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp			 
			$fecha2 - $hora <a href='javascript:print(this.document)'>
			<img border='0' src='http://$server/$folder/graphics/printer.png' width='15' height='15'></a>			 
			<h1>INFORME TECNICO &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp </h1>
		</td>
	</tr>	
	<tr height='30'>
		<td align='center' colspan='4'>
		<font style='font-family: Tahoma; font-size: 10pt; font-weight:bold;'>CODIGO : $cod_geo/$cod_cat</font>
		</td>		 				
	</tr>	  		 
   <tr height='20'>
      <td align='left' colspan='8'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp PROPIETARIO(S) : </font>
				 <font style='font-family: Tahoma; font-size: $font_size_prop;'>$propietario</font>
      </td>									 				
   </tr>
  <tr height='20'>
      <td align='left' colspan='3'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp DEPARTAMENTO : $depart</font>
      </td>
      <td align='left' colspan='3'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp PROVINCIA : $provincia</font>
      </td>	
      <td align='left' colspan='2'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp SECCION : $seccion</font>
      </td>										 				
   </tr>
   <tr height='20'>
      <td align='left' colspan='2'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp MUNICIPIO : $municipio</font>
      </td>	
      <td align='left' colspan='3'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp DISTRITO : $distrito</font>
      </td>					
      <td align='left' colspan='3'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp COMUNIDAD : $comunidad</font>
      </td>					 				
   </tr>	 	 
   <tr height='20'>
      <td align='left' colspan='2'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp ZONA : $zona</font>
      </td>
      <td align='left'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp U.V. : $cod_uv</font>
      </td>			
      <td align='left'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp MZ : $cod_man</font>
      </td>			
      <td align='left'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp PRED. : $cod_pred</font>
      </td>		
      <td align='left'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp BLQ : $cod_blq</font>
      </td>							
      <td align='left'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp PISO : $cod_piso</font>
      </td>		
      <td align='left'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp APTO : $cod_apto</font>
      </td>								 				
   </tr>	  
   <tr height='20'>
      <td align='left' colspan='2'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp BARRIO : $barrio</font>
      </td>	 
      <td align='left' colspan='3'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp SUP. SEGUN MENS. : $area m²</font>
      </td>
      <td align='left' colspan='3'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp SUP. SEGUN DOC. : $adq_sdoc m²</font>
      </td>													 				
   </tr>
   <tr height='20'>			
      <td align='left' colspan='8'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp DIRECCION : $direccion</font>
      </td>							 				
	</tr>			 	 	  
	<tr height='$espacio_entre_tablas' style='font-family: Tahoma; font-size: 8pt'>
		<td align='left' valign='bottom' colspan='8'>&nbsp DATOS DEL TERRENO :</td>					 				
	</tr>		 	 	 	  
	<tr height='20' style='font-family: Tahoma; font-size: 8pt; font-weight: bold'>					
		<td align='center' width='12%'>Z. Homog.</td>
		<td align='center' width='12%'>Material Via</td>
		<td align='center' width='12%'>Agua</td>							 
		<td align='center' width='12%'>Alcantarillado</td>	
		<td align='center' width='12%'>Luz</td>							 
		<td align='center' width='13%'>Teléfono</td>	
		<td align='center' width='12%'>Inclinación</td>
		<td align='center' width='13%'>Superf. Mens.</td>							 			 							 						 
	</tr> 
	<tr height='20' style='font-family: Tahoma; font-size: 8pt;'>
		<td align='center'>$ben_zona</td>
		<td align='center'>$via_mat</td>
		<td align='center'>$ser_agu</td>							 
		<td align='center'>$ser_alc</td>	
		<td align='center'>$ser_luz</td>							 
		<td align='center'>$ser_tel</td>	
		<td align='center'>$ter_topo</td>
		<td align='center'>$area  m²</td>							 						 							 						 
	</tr>		
	<tr height='$espacio_entre_tablas' style='font-family: Tahoma; font-size: 8pt'>
		<td align='left' valign='bottom' colspan='8'>&nbsp DATOS DE LAS CONSTRUCCIONES :</td>					 				
	</tr>																	
		<tr height='10' style='font-family: Tahoma; font-size: 8pt; font-weight: bold'>
		<td align='center'>Unidad</td>
		<td align='center'>Piso</td>
		<td align='center'>Tipo de Const.</td>
		<td align='center'>Año Constr.</td>							 
		<td align='center'>Estado Visual</td>	
		<td align='center'>Valuación</td>							 
		<td align='center'>Clase</td>	
		<td align='center'>Superf. Mens.</td>				 							 						 
	</tr>";
$i = 0;
if ($no_de_edificaciones > 8) {
   $fila_activa = true;
   $no_de_edif_real = $no_de_edificaciones;
   $no_de_edificaciones = 8;
} else $fila_activa = false;
$filas_vacias = 8 - $no_de_edificaciones;
while ($i < $no_de_edificaciones) {
	$content = $content."						
	<tr height='20'>
		<td align='center'>$edi_num[$i]</td>
		<td align='center'>$edi_piso[$i]</td>
		<td align='center'>$edi_tipo[$i]</td>
		<td align='center'>$edi_ano[$i]</td>							 
		<td align='center'>$edi_edo[$i]</td>	
		<td align='center'>$line_media[$i]</td>							 
		<td align='center'>$clase[$i]</td>	
	<td align='center'>$area_edif[$i] m²</td>					 							 						 
	</tr>";	
	$i++;
} 
$i = 0;
while ($i < $filas_vacias) {
	$content = $content."						
	<tr height='20'>
		<td align='center'>---</td>
		<td align='center'>---</td>
		<td align='center'>---</td>
		<td align='center'>---</td>							 
		<td align='center'>---</td>	
		<td align='center'>---</td>							 
		<td align='center'>---</td>	
		<td align='center'>---</td>					 							 						 
	</tr>";	
   $i++;
}
$content = $content."
	 <tr height='20'>
      <td align='left' colspan='6'>";
if ($fila_activa) {
     $texto = "&nbsp --> El predio tiene en total $no_de_edif_real edificaciones.";
} else {
    $texto = "&nbsp";
}
$content = $content."$texto
		</td>
		<td align='center'><b>TOTAL</b></td>
		<td align='center'><b>$edi_area</b></td>		 							 						 
	</tr>		


	<tr height='$espacio_entre_tablas' style='font-family: Tahoma; font-size: 8pt'>
		<td align='left' valign='bottom' colspan='8'>&nbsp COLINDANCIAS Y MEDIDAS :</td>					 				
	</tr>		 	 	 	  
	<tr height='20' style='font-family: Tahoma; font-size: 8pt; font-weight: bold'>					
		<td align='center'>Limites</td>
		  <td align='center' colspan='4'>Colindantes</td>						 
		  <td align='center' colspan='3'>Medidas s/mens</td>						 			 							 						 
	</tr> 
	<tr height='32' style='font-family: Tahoma; font-size: 8pt;'>
		<td align='center'>$limite1</td>
		<td align='left' colspan='4' style='font-family: Tahoma; font-size: $font_size_norte;'>&nbsp $Colind1</td>						 
		<td align='center' colspan='3' style='font-family: Tahoma; font-size: $font_size_norte_med;'>$Medida1</td>	
	</tr> 
	<tr height='32' style='font-family: Tahoma; font-size: 8pt;'>
		<td align='center'>$limite2</td>							 
		<td align='left' colspan='4' style='font-family: Tahoma; font-size: $font_size_este;'>&nbsp $Colind2</td>	
		<td align='center' colspan='3' style='font-family: Tahoma; font-size: $font_size_este_med;'>$Medida3</td>							 						 							 						 
	</tr>		 
	<tr height='32' style='font-family: Tahoma; font-size: 8pt;'>
	    <td align='center'>$limite3</td>
		<td align='left' colspan='4' style='font-family: Tahoma; font-size: $font_size_sur;'>&nbsp $Colind3</td>						 
	    <td align='center' colspan='3' style='font-family: Tahoma; font-size: $font_size_sur_med;'>$Medida3</td>	
	</tr> 	 
	<tr height='32' style='font-family: Tahoma; font-size: 8pt;'>			
		  <td align='center'>$limite4</td>							 
		  <td align='left' colspan='4' style='font-family: Tahoma; font-size: $font_size_oeste;'> &nbsp $Colind4</td>	
		  <td align='center' colspan='3' style='font-family: Tahoma; font-size: $font_size_oeste_med;'>$Medida4</td>							 						 							 						 
	</tr>	
	<tr height='$espacio_entre_tablas' style='font-family: Tahoma; font-size: 8pt'>
		<td align='left' valign='bottom' colspan='3'>&nbsp PLANO DE UBICACION :</td>		
		<td align='left' valign='bottom' colspan='5'>&nbsp OBSERVACIONES :</td>							 				
   </tr>		 
   <tr height='250' style='font-family: Tahoma; font-size: 8pt'>
      <td align='center' bgcolor='#FFFFFF' colspan='3'>";	 
if ($geometria_existe) {	
$content = $content."
         <iframe frameborder='0' name='mapserver' src='http://$server/cgi-bin/mapserv.exe?map=c:/apache/siicat/mapa/siicat_planocatastral_ubicacion.map&program=http://$server/cgi-bin/mapserv.exe&zoomsize=2&layer=Predios&layer=Manzanos&layer=Calles&imgext=$xmin1 $ymin1 $xmax1 $ymax1&imgxy=750+625&mapext=&savequery=false&zoomdir=1&mode=map&imgbox=&imgsize=1500+1250&mapsize=750+625' id='content' width='300px' height='250px' align='middle' valign='center' scrolling='no' noresize='no' marginwidth='0' marginheight='0'>
         </iframe>";
} else {
$content = $content."	
   <br />NO EXISTE LA GEOMETRIA DEL PREDIO";
}
$content = $content."				 
			</td>	 
      <td align='left' valign='top' colspan='5'>
				 <br />&nbsp $observ_fila1 $observ_fila2 $observ_fila3<br />
				 <div align='center' style='font-family: Tahoma; font-size: 10pt; font-weight: bold'> 
				    $observ_fila
				 </div>				 				 			 
      </td>					 				
   </tr>		 		 	  				    
   <tr height='50'>
      <td align='left' colspan='8'>
         <font style='font-family: Tahoma; font-size: 7pt;'> &nbsp NOTA: <br /> 
				 &nbsp $nota_informe_tecnico </font>
      </td>								 	
   </tr>								  	 
</table>
</div>
</body>";
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