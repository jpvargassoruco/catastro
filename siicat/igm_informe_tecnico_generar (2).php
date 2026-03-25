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

### LEER DATOS DE PROPIETARIO DE INFO_INMU Y DATOS DEL PREDIO DE INFO_PREDIO

include "siicat_planos_leer_datos.php";
/*	
$sql="SELECT * FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result=pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$cod_pad =  $info['cod_pad'];
if ($cod_pad == '') {
   $cod_pad = "-";
}
$tit_1nom1 = utf8_decode(trim($info['tit_1nom1']));
$tit_1nom2 = utf8_decode(trim($info['tit_1nom2']));
$tit_1pat = utf8_decode(trim($info['tit_1pat']));
$tit_1mat = utf8_decode(trim($info['tit_1mat']));
$propietario1 = $tit_1nom1." ".$tit_1nom2." ".$tit_1pat." ".$tit_1mat;
$propietario1 = trim ($propietario1);
$tit_2nom1 = utf8_decode(trim($info['tit_2nom1']));
$tit_2nom2 = utf8_decode(trim($info['tit_2nom2']));
$tit_2pat = utf8_decode(trim($info['tit_2pat']));
$tit_2mat = utf8_decode(trim($info['tit_2mat']));
if ($tit_2pat != "") {
   $propietario2 = " Y ";
	 if ($tit_2nom1 != "") {
      $propietario2 = $propietario2." ".$tit_2nom1;
	 } 
	 if ($tit_2nom2 != "") {
      $propietario2 = $propietario2." ".$tit_2nom2;					
   }	
	 $propietario2 = $propietario2." ".$tit_2pat; 
	 if ($tit_2mat != "") {
      $propietario2 = $propietario2." ".$tit_2mat;
	 }
} else $propietario2 = "";
$tit_1ci = trim ($info['tit_1ci']);
if ($tit_1ci == "") { 
   $tit_1ci = "";
} else {
   $tit_1ci = "(C.I.: $tit_1ci)";
}
$tit_2ci = trim ($info['tit_2ci']);
if ($tit_2ci == "") { 
   $tit_2ci = "";
} else {
   $tit_2ci = "(C.I.: $tit_2ci)";
}
$prop_string = trim ($propietario1." ".$tit_1ci." ".$propietario2." ".$tit_2ci);
$max_prop_stringlength1 = 104;
$max_prop_stringlength2 = 95;
if (strlen ($prop_string) > $max_prop_stringlength1) {
   $font_size_prop = "7pt";
} elseif (strlen ($prop_string) > $max_prop_stringlength2) {
   $font_size_prop = "8pt";
} else {
   $font_size_prop = "9pt";
}
$dir_tipo = $info['dir_tipo'];
$dir_tipo = strtoupper(utf8_decode(abr($dir_tipo)));
$dir_nom = utf8_decode (strtoupper($info['dir_nom']));
$dir_num = $info['dir_num'];
$dir_edif = $info['dir_edif'];
$dir_bloq = $info['dir_bloq'];
$dir_piso = $info['dir_piso'];
$dir_apto = $info['dir_apto'];
$direccion = $dir_tipo." ".$dir_nom." ".$dir_num;
if ($dir_edif != "") {
   $direccion = $direccion.", ECIO. ".$dir_edif;
}
if ($dir_bloq != "") {
   $direccion = $direccion.", BLQ. ".$dir_bloq;
}
if ($dir_piso != "") {
   $direccion = $direccion.", PISO ".$dir_piso;
}
if ($dir_apto != "") {
   $direccion = $direccion.", APTO. ".$dir_apto;
}  
$via_mat = utf8_decode(abr($info['via_mat']));
if ($via_mat == "") {
   $via_mat = "-";
}
if ($info['ser_alc'] == "") {
  $ser_alc = "-";
} else $ser_alc = $info['ser_alc'];
if ($info['ser_agu'] == "") {
  $ser_agu = "-";
} else $ser_agu = $info['ser_agu'];
if ($info['ser_luz'] == "") {
  $ser_luz = "-";
} else $ser_luz = $info['ser_luz'];
if ($info['ser_tel'] == "") {
  $ser_tel = "-";
} else $ser_tel = $info['ser_tel'];
$ter_topo = utf8_decode(abr($info['ter_topo']));
if ($ter_topo == "") {
  $ter_topo = "-";
} 
$ter_sdoc = $info['ter_sdoc']; 
if ($ter_sdoc == "") { $ter_sdoc = "---"; }
$ctr_fech = $info['ctr_fech'];
if (($ctr_fech == '') OR ($ctr_fech == '1900-01-01')) {
   $ctr_fech = "-";
} else $ctr_fech = change_date ($ctr_fech);
$ctr_obs = utf8_decode($info['ctr_obs']);
pg_free_result($result); */
################################################################################
#-------------------------- DEFINIR ZONA DEL PREDIO ---------------------------#
################################################################################
$zona = get_zona_brujula ($cod_cat, $centro_del_pueblo_para_zonas_x, $centro_del_pueblo_para_zonas_y);
if ($zona == "SE")  { $zona = "SUR ESTE";
} elseif ($zona == "SO")  { $zona = "SUR OESTE";
} elseif ($zona == "NE")  { $zona = "NOR ESTE";
} elseif ($zona == "NO")  { $zona = "NOR OESTE"; 
}
################################################################################
#------------------------- INFORMACION DE INFO_EDIF ---------------------------#
################################################################################	
$sql="SELECT edi_num, edi_piso, edi_tipo, edi_ano, edi_edo FROM info_edif WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' ORDER BY edi_num, edi_piso";
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
/*		 
$sql="SELECT area(the_geom) FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' ORDER BY edi_num, edi_piso";
$check = pg_num_rows(pg_query($sql));
$i = 0;
if ($check == 0) {
	 $edi_area = 0;
} else {
   $result=pg_query($sql);
   $edi_area = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
      foreach ($line as $col_value) {
         $edi_area = $edi_area + $col_value;
				 $area_edif[$i] = ROUND($col_value,2);; 	
      }
			$i++;
   } # END_OF_WHILE	
	 $edi_area = ROUND($edi_area,2);	
	 pg_free_result($result);			
}  */
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
#echo "LINE_VALUE: $line_value[$i], OBJETOS: $no_de_objetos_validos[$i] <br>";								 						 
				 }																																			
#            $edi_temp[$i][$k] = $col_value; 	
         $k++; 
      } # END_OF_FOREACH	 
			$line_media[$i] = ROUND($line_value[$i]/$no_de_objetos_validos[$i],2); 
#echo "LINE_VALUE TOTAL $i: $line_value[$i], OBJETOS: $no_de_objetos_validos[$i], MEDIA: $line_media[$i] <br>";   		
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
			########################################
      #----------- CALCULAR VALOR -----------#
      ########################################			
   #   $calidad_const[$i] = imp_calidad_const($gestion_actual,$line_media[$i]);
	  #  if ($calidad_const[$i] == 0) {
		#	   $avaluo_edif_separado[$i] = "-";
		#	}	else {
 	 #      $factor_deprec[$i] = imp_factor_deprec($gestion_actual,$edi_ano[$i],$ano_actual);	
		#	   $avaluo_edif_separado[$i] = avaluo_const($calidad_const[$i], $area_edif[$i], $factor_deprec[$i]);			
		#	}	
#echo "CALIDAD CONST $gestion_actual : $calidad_const[$i], FACTOR_DEPREC: $factor_deprec[$i], AVALUO CONST: $avaluo_edif_separado[$i]<br>";								
		  $k = 0;
      $i++;				
   } # END_OF_WHILE	
}
################################################################################
#------------------------------- COLINDANTES ----------------------------------#
################################################################################	
$id_predio = get_id_predio ($cod_geo,$cod_uv,$cod_man,$cod_pred);
#$sql="SELECT * FROM colindantes WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$sql="SELECT * FROM colindantes WHERE id_predio = '$id_predio'";
$check_col = pg_num_rows(pg_query($sql));
if ($check_col > 0 ) {	
      $result_col = pg_query($sql);
      $info_col = pg_fetch_array($result_col, null, PGSQL_ASSOC);
			$col_norte_nom = utf8_decode ($info_col['norte_nom']);
			if (strlen ($col_norte_nom) < 116) {
			   $font_size_norte =  "8pt";
			} else $font_size_norte =  "6pt";
			$col_norte_med = utf8_decode ($info_col['norte_med']);
			$font_size_norte_med =  "8pt";					
			$col_sur_nom = utf8_decode ($info_col['sur_nom']);
			if (strlen ($col_sur_nom) < 116) {
			   $font_size_sur = "8pt";
			} else $font_size_sur = "6pt";							
			$col_sur_med = utf8_decode ($info_col['sur_med']);	
			$font_size_sur_med =  "8pt";			
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
			$col_oeste_med = utf8_decode ($info_col['oeste_med']);
			$font_size_oeste_med =  "8pt";							
			pg_free_result($result_col);
} else { 
   $col_norte_nom = $col_sur_nom = $col_este_nom = $col_oeste_nom = "";	
   $col_norte_med = $col_sur_med = $col_este_med = $col_oeste_med = "";
	 $font_size_norte = $font_size_sur = $font_size_este = $font_size_oeste = "8pt";
	 $font_size_norte_med = $font_size_sur_med = $font_size_este_med = $font_size_oeste_med = "8pt";	 
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
#         $xmin=$centerx- $delta;
 #        $xmax=$centerx+ $delta; 
 #        $ymin=$centery- $delta;
#         $ymax=$centery+ $delta;	
         $xmin1=$centerx- $delta1;
         $xmax1=$centerx+ $delta1; 
         $ymin1=$centery- $delta1;
         $ymax1=$centery+ $delta1;				 	
				 pg_free_result($result1);
				 ########################################
         #   ENVIAR SELECCIONADO A TEMP-POLY    #
         ########################################		
#				 pg_query("DELETE FROM temp_poly WHERE user_id = '$user_id' AND (number = '44' OR number = '55')");
				 pg_query("DELETE FROM temp_poly WHERE user_id = '$user_id' OR user_id IS NULL");				 
				 pg_query("INSERT INTO temp_poly (edi_num, edi_piso, the_geom) SELECT edi_num, edi_piso, the_geom FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
			   pg_query("UPDATE temp_poly SET user_id = '$user_id', numero = 44 WHERE edi_num > '0' AND user_id IS NULL");				 
				 pg_query("INSERT INTO temp_poly (cod_uv, cod_man, cod_pred, the_geom) SELECT cod_uv, cod_man, cod_pred, the_geom FROM predios_ocha WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
			   pg_query("UPDATE temp_poly SET cod_cat = '$cod_cat', user_id = '$user_id', numero = 55 WHERE user_id IS NULL AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
      } else $geometria_existe = false;
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
$espacio_entre_tablas = 30;
$content = "
<div align='left'>
<table border='1' width='100%' height='161' style='border:2px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'><font face='Tahoma' size='2'>
   <tr>
      <td rowspan='2' colspan='4'>
			   <table border='0' width='100%' style='font-family: Tahoma; font-size: 9pt' bgcolor='#FFFFFF'><font face='Tahoma' size='2'>
            <tr>
               <td width='20%'>
				          <img src='http://$server/$folder/css/banner_blanco.jpg' alt='imagen' width='115' height='101' border='0'>
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
          <font style='font-family: Tahoma; font-size: 8pt; font-weight:bold; color:red'>No. </font>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp			 
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
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp Dist. : $cod_uv</font>
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
      <td align='left' valign='bottom' colspan='8'>
         &nbsp DATOS DEL TERRENO :
      </td>					 				
   </tr>		 	 	 	  
   <tr height='20' style='font-family: Tahoma; font-size: 8pt; font-weight: bold'>					
	    <td align='center' width='12%'>
		     Z. Homog.
			</td>
		  <td align='center' width='12%'>
			   Material Vía
		  </td>
			<td align='center' width='12%'>
			   Agua
		  </td>							 
		  <td align='center' width='12%'>
		     Alcantarillado
			</td>	
		  <td align='center' width='12%'>
			   Luz
		  </td>							 
		  <td align='center' width='13%'>
			   Teléfono
			</td>	
			<td align='center' width='12%'>
			   Inclinación
			</td>
			<td align='center' width='13%'>
			   Superf. Mens.
			</td>							 			 							 						 
   </tr> 
   <tr height='20' style='font-family: Tahoma; font-size: 8pt;'>
	    <td align='center'>
			   $ben_zona
      </td>
			<td align='center'>
			   $via_mat
			</td>
		  <td align='center'>
		     $ser_agu
			</td>							 
	    <td align='center'>
			   $ser_alc
		  </td>	
		  <td align='center'>
		     $ser_luz
	    </td>							 
		  <td align='center'>
			   $ser_tel
		  </td>	
		  <td align='center'>
		     $ter_topo
			</td>
		  <td align='center'>
			   $area  m²
		  </td>							 						 							 						 
   </tr>		
<tr height='$espacio_entre_tablas' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='bottom' colspan='8'>
         &nbsp DATOS DE LAS CONSTRUCCIONES :
      </td>					 				
   </tr>																	
	 <tr height='10' style='font-family: Tahoma; font-size: 8pt; font-weight: bold'>
	    <td align='center'>
		     Unidad
		  </td>
	    <td align='center'>
		     Piso
		  </td>
	    <td align='center'>
			   Tipo de Const.
		  </td>
	    <td align='center'>
			   Año Constr.
		  </td>							 
	    <td align='center'>
		     Estado Visual
		  </td>	
	    <td align='center'>
		     Valuación
			</td>							 
	    <td align='center'>
			   Clase
	    </td>	
	    <td align='center'>
			   Superf. Mens.
		  </td>				 							 						 
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
      <td align='center'>
			   $edi_num[$i]
			</td>
      <td align='center'>
			   $edi_piso[$i]
		  </td>
      <td align='center'>
		     $edi_tipo[$i]
			</td>
      <td align='center'>
		     $edi_ano[$i]
			</td>							 
      <td align='center'>
			   $edi_edo[$i]
		  </td>	
      <td align='center'>
			   $line_media[$i]
		  </td>							 
      <td align='center'>
			   $clase[$i]
			</td>	
      <td align='center'>
			   $area_edif[$i] m²
			</td>					 							 						 
   </tr>";	
   $i++;
} 
$i = 0;
while ($i < $filas_vacias) {
  $content = $content."						
	 <tr height='20'>
      <td align='center'>
			   ---
			</td>
      <td align='center'>
			   ---
		  </td>
      <td align='center'>
		     ---
			</td>
      <td align='center'>
		     ---
			</td>							 
      <td align='center'>
			   ---
		  </td>	
      <td align='center'>
			   ---
		  </td>							 
      <td align='center'>
			   ---
			</td>	
      <td align='center'>
			   ---
			</td>					 							 						 
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
      <td align='center'>
	       <b>TOTAL</b>
		  </td>
      <td align='center'>
	       <b>$edi_area</b>
		  </td>		 							 						 
   </tr>		
   <tr height='$espacio_entre_tablas' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='bottom' colspan='8'>
         &nbsp COLINDANCIAS Y MEDIDAS :
      </td>					 				
   </tr>		 	 	 	  
   <tr height='20' style='font-family: Tahoma; font-size: 8pt; font-weight: bold'>					
	    <td align='center'>
		     Limites
			</td>
		  <td align='center' colspan='4'>
			   Colindantes
		  </td>						 
		  <td align='center' colspan='3'>
		     Medidas s/mens
			</td>						 			 							 						 
   </tr> 
   <tr height='32' style='font-family: Tahoma; font-size: 8pt;'>
	    <td align='center'>
			   NORTE
      </td>
			<td align='left' colspan='4' style='font-family: Tahoma; font-size: $font_size_norte;'>
			   &nbsp $col_norte_nom
			</td>						 
	    <td align='center' colspan='3' style='font-family: Tahoma; font-size: $font_size_norte_med;'>
			   $col_norte_med
		  </td>	
   </tr> 
   <tr height='32' style='font-family: Tahoma; font-size: 8pt;'>			
		  <td align='center'>
		     ESTE
	    </td>							 
		  <td align='left' colspan='4' style='font-family: Tahoma; font-size: $font_size_este;'>
			   &nbsp $col_este_nom
		  </td>	
		  <td align='center' colspan='3' style='font-family: Tahoma; font-size: $font_size_este_med;'>
			   $col_este_med
		  </td>							 						 							 						 
   </tr>		 
   <tr height='32' style='font-family: Tahoma; font-size: 8pt;'>
	    <td align='center'>
			   SUR
      </td>
			<td align='left' colspan='4' style='font-family: Tahoma; font-size: $font_size_sur;'>
			   &nbsp $col_sur_nom
			</td>						 
	    <td align='center' colspan='3' style='font-family: Tahoma; font-size: $font_size_sur_med;'>
			   $col_sur_med
		  </td>	
   </tr> 	 
   <tr height='32' style='font-family: Tahoma; font-size: 8pt;'>			
		  <td align='center'>
		     OESTE
	    </td>							 
		  <td align='left' colspan='4' style='font-family: Tahoma; font-size: $font_size_oeste;'>
			   &nbsp $col_oeste_nom
		  </td>	
		  <td align='center' colspan='3' style='font-family: Tahoma; font-size: $font_size_oeste_med;'>
			   $col_oeste_med
		  </td>							 						 							 						 
   </tr>	
   <tr height='$espacio_entre_tablas' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='bottom' colspan='8'>
         &nbsp PLANO DE UBICACION : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
				 &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
         &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 
				 OBSERVACIONES :
			</td>								 				
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