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
# FUNCION GET_USO
$uso = get_uso ($id_inmu);	
if ($uso == "0") {			
   $uso = "-";
}
### LEER DATOS DE PROPIETARIO DE INFO_INMU Y DATOS DEL PREDIO DE INFO_PREDIO
include "siicat_planos_leer_datos.php";

################################################################################
#-------------------------- DEFINIR ZONA DEL PREDIO ---------------------------#
################################################################################
$zona = get_zona_brujula ($cod_cat, $centro_del_pueblo_para_zonas_x, $centro_del_pueblo_para_zonas_y);
if ($zona == "NE") {
   $zona = "NORESTE";
} elseif ($zona == "NO") {
   $zona = "NOROESTE";
} elseif ($zona == "SE") {
   $zona = "SURESTE";
} else {
   $zona = "SUROESTE";
}
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
if ($edi_area == 0) {
   $edi_area_porc = 0;
} else $edi_area_porc = ROUND($area/$edi_area,2);
################################################################################
#------------------------------------ FECHA -----------------------------------#
################################################################################	
$nombre_mes = monthconvert ($mes_actual);

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
$edi_area_porc = ROUND(($edi_area/$area_sin_ocha)*100,2);
################################################################################
#---------------------------------- NOTA --------------------------------------#
################################################################################
$sql="SELECT obs_linea, nota_linea FROM imp_base";
$result_nota = pg_query($sql);
$info = pg_fetch_array($result_nota, null, PGSQL_ASSOC);
$obs_linea = utf8_decode ($info['obs_linea']);
$nota_linea = utf8_decode ($info['nota_linea']);
pg_free_result($result_nota);	
/*
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
}  */
			########################################
      #      Chequear si existen filas       #
      ########################################	
      $sql="SELECT cod_uv FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
      $check_predio = pg_num_rows(pg_query($sql));		
			################################################################################
      #---------------------- SELECCIONAR GEOMETRIA PARA IFRAME ---------------------#
      ################################################################################	
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
	 
			   $factor_zoom = $factor_zoom_linea_y_nivel; #(definido en siicat_config)

#echo "FACTOR ZOOM ES: $factor_zoom";			 
				 
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
	          $factor_zoom = 3.2;
					#	$factor_zoom1 = 12;
         }
#echo "FACTOR ZOOM ES: $factor_zoom";					   
         if ($centerx-$xtent_x[0] > $centery-$xtent_y[0]) {
            $delta = ($centerx-$xtent_x[0])* $factor_zoom;
					#	$delta1 = ($centerx-$xtent_x[0])* $factor_zoom1; 
#echo "DELTA viene de X y es $delta<br />\n";
         } else {
            $delta = ($centery-$xtent_y[0])* $factor_zoom;
				#		$delta1 = ($centery-$xtent_y[0])* $factor_zoom1;
#echo "DELTA viene de Y y es $delta<br />\n";
         }
         $xmin=$centerx- $delta;
         $xmax=$centerx+ $delta; 
         $ymin=$centery- $delta;
         $ymax=$centery+ $delta;	
     #    $xmin1=$centerx- $delta1;
       #  $xmax1=$centerx+ $delta1; 
      #   $ymin1=$centery- $delta1;
      #   $ymax1=$centery+ $delta1;				 	
				 pg_free_result($result1);
				 ########################################
         #   ENVIAR SELECCIONADO A TEMP-POLY    #
         ########################################		
#				 pg_query("DELETE FROM temp_poly WHERE user_id = '$user_id' AND (number = '44' OR number = '55')");
				 pg_query("DELETE FROM temp_poly WHERE user_id = '$user_id' OR user_id IS NULL");				 
				 pg_query("INSERT INTO temp_poly (edi_num, edi_piso, the_geom) SELECT edi_num, edi_piso, the_geom FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
			   pg_query("UPDATE temp_poly SET user_id = '$user_id', numero = 44 WHERE edi_num > '0' AND user_id IS NULL");				 
				 pg_query("INSERT INTO temp_poly (cod_uv, cod_man, cod_pred, the_geom) SELECT cod_uv, cod_man, cod_pred, the_geom FROM predios_ocha WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");      
			   pg_query("UPDATE temp_poly SET user_id = '$user_id', cod_cat = '$cod_cat', numero = 55 WHERE user_id IS NULL AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
				 pg_query("INSERT INTO temp_poly (cod_uv, cod_man, cod_pred, the_geom) SELECT cod_uv, cod_man, cod_pred, the_geom FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");      
			   pg_query("UPDATE temp_poly SET user_id = '$user_id', cod_cat = '$cod_cat', numero = 58 WHERE user_id IS NULL AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");		 
      }
################################################################################
#----------------------------- CALCULAR ESCALA --------------------------------#
################################################################################	
#Factor 0.63 generado por medicion en la impresion
$extension_real = 2*$delta*0.63;
$extension_en_papel = 0.185;
$escala = $extension_real/$extension_en_papel;
$escala = ROUND($escala/100,0)*100;
########################################
#------ COORDENADAS DE VERTICES -------#
########################################
$sql="SELECT AsText(the_geom),npoints(the_geom) FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result = pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$coord_poly = $info['astext'];
$no_de_vertices = $info['npoints']-1;
pg_free_result($result);
include "siicat_extract_coordpoly.php";
################################################################################
#--------------------- DEFINIR POSICION DE ETIQUETAS --------------------------#
################################################################################
$result=pg_query("SELECT x(centroid(the_geom)),y(centroid(the_geom)) FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");  
$cstr = pg_fetch_array($result, null, PGSQL_ASSOC);
$centroid_x = $cstr['x'];
$centroid_y = $cstr['y'];
pg_free_result($result);
$i = 0;			
while ($i < $no_de_vertices) {		
   $pos[$i] = get_position4($point_x[$i], $point_y[$i], $centroid_x, $centroid_y); # FUNCION
   $i++;    
}			
################################################################################
#--------------------- ESCRIBIR PUNTOS EN TEMP_POINT --------------------------#
################################################################################
pg_query("DELETE FROM temp_point WHERE user_id = '$user_id'");
$i = 0;
while ($i < $no_de_vertices) {
   $no_de_punto = $i+1;
   pg_query("INSERT INTO temp_point (user_id, cod_cat, text, pos, the_geom) 
     VALUES ('$user_id','$cod_cat','P$no_de_punto','$pos[$i]','{$esc1}$point_x[$i] $point_y[$i])')");
   $i++;    
}
################################################################################
#---------------------- ESCRIBIR LINEAS EN TEMP_LINE --------------------------#
################################################################################
pg_query("DELETE FROM temp_line WHERE user_id = '$user_id'");
$window_ext = $xmax - $xmin;
$min_value = 0.01 * $window_ext;
#echo "MIN_VALUE is $min_value m";	 
$i = $a = $b = $c = 0; 
$j = 1;
while ($i <= $no_de_vertices-1) {
   if ($i == $no_de_vertices-1) {
		  $j = 0;
   }
  # $a=$point_x[$j]-$point_x[$i];
  # $b=$point_y[$j]-$point_y[$i];
 #  $c=ROUND(SQRT($a*$a+$b*$b),2);
	 $c = ROUND (get_linelen ($point_x[$j], $point_y[$j], $point_x[$i], $point_y[$i]),2);
	 if ($c > $min_value) {
	    $c = $c." m";        
	    pg_query("INSERT INTO temp_line (user_id, id, nombre , the_geom) VALUES ('$user_id','$j','$c', '{$esc2}$point_x[$i] $point_y[$i],$point_x[$j] $point_y[$j]{$esc3}')");
   }
	 $i++;
	 $j++;	
}
pg_query("INSERT INTO temp_line SELECT '$user_id', '99' ,radio, the_geom FROM ochaves_linea WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");

################################################################################
#------------------------- CHEQUEAR POR COLINDANTES ---------------------------#
################################################################################
$sql="SELECT cod_uv, cod_man, cod_pred FROM predios WHERE st_dwithin ((SELECT the_geom FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'),the_geom,1)
      AND activo = '1' AND NOT (cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred')";	
$result = pg_query($sql);
$i = $j = 0;
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
   foreach ($line as $col_value) { 
      if ($i == 0) {
				 $cod_uv_col = $col_value;
	    } elseif ($i == 1) {
				 $cod_man_col = $col_value;
		  } else {
				 $cod_pred_col = $col_value;												 
			   $col_cod[$j] = get_codcat ($cod_uv_col,$cod_man_col,$cod_pred_col,0,0,0);
				 $sql="SELECT tit_1id FROM info_inmu WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_col' AND cod_man = '$cod_man_col' AND cod_pred = '$cod_pred_col'";
         $check = pg_num_rows(pg_query($sql));
				 if ($check == 0) {
				    $titular = "S/N";
				 } elseif ($check == 1) {
				    $result2 = pg_query($sql);
				    $col_nom = pg_fetch_array($result2, null, PGSQL_ASSOC);
				    $id_contrib = $col_nom['tit_1id'];
						pg_free_result($result2);
				    $titular = get_contrib_nombre ($id_contrib);
         } else {
				    $titular = "Varios";
				 }
				 #SOLO MOSTRAR CODIGO EN EL PLANO
				 $col_tit[$j] = $col_cod[$j];
				 
				 #$col_tit[$j] = trim($tit_1nom1." ".$tit_1pat." ".$tit_1mat);
				 #if ($col_tit[$j] == "") {
				 #   $col_tit[$j] = $col_value."@S/N";
				 #} else {			 
				 #   $col_tit[$j] = $col_value."@".$col_tit[$j];
         #}
				 #$col_tit[$j] = utf8_decode ($col_tit[$j]);
				 
				 ########################################
	       #----------- RELLENAR TABLA -----------#
	       ########################################
				 $result2=pg_query("SELECT x(centroid(the_geom)),y(centroid(the_geom))
				                    FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_col' AND cod_man = '$cod_man_col' AND cod_pred = '$cod_pred_col'");     
         $cstr = pg_fetch_array($result2, null, PGSQL_ASSOC);
         $col_centroid_x = $cstr['x']; $col_centroid_y = $cstr['y'];
				 pg_free_result($result2);
				 $result2=pg_query("SELECT xmin(extent3d(the_geom)),xmax(extent3d(the_geom)),
														ymin(extent3d(the_geom)),ymax(extent3d(the_geom))
													  FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'"); 	
         $cstr = pg_fetch_array($result2, null, PGSQL_ASSOC);																	 
				 #$xmin = $cstr['xmin']; $xmax = $cstr['xmax'];
				 #$ymin = $cstr['ymin']; $ymax = $cstr['ymax'];
				 pg_free_result($result2); 
				 #$col_pos[$j] = get_position4($col_centroid_x, $col_centroid_y, $centroid_x, $centroid_y);
				 #$col_pos[$j] = get_position8($col_centroid_x, $col_centroid_y, $centroid_x, $centroid_y, $xmin, $xmax, $ymin, $ymax); 
				 ########################################
	       #------------ RELLENAR TABLA ----------#
	       ########################################
				 pg_query("INSERT INTO temp_poly (cod_uv,cod_man, cod_pred, the_geom) SELECT cod_uv, cod_man, cod_pred, the_geom FROM predios WHERE activo = '1' AND cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_col' AND cod_man = '$cod_man_col' AND cod_pred = '$cod_pred_col'");
			   pg_query("UPDATE temp_poly SET user_id = '$user_id', cod_cat = '$col_cod[$j]', numero = '66', label = '$col_tit[$j]' WHERE user_id is NULL AND cod_uv = '$cod_uv_col' AND cod_man = '$cod_man_col' AND cod_pred = '$cod_pred_col'");				 				    
				 $j++;
				 $i = -1;
	    }
			$i++;
	 }
}
$no_de_colindantes = $j;
pg_free_result($result);
################################################################################
#--------------------- ESCRIBIR COLINDANTES EN TEMP_POLY ----------------------#
################################################################################
$i = 0;
pg_query("DELETE FROM temp_poly WHERE user_id = '$user_id' AND numero = '5' AND NOT (cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred')");
while ($i < $no_de_colindantes) {
   $cod_uv_col = get_uv ($col_cod[$i]); $cod_man_col = get_man($col_cod[$i]);  $cod_pred_col = get_pred ($col_cod[$i]);
   pg_query("INSERT INTO temp_poly (cod_uv, cod_man, cod_pred, the_geom) SELECT cod_uv, cod_man, cod_pred, the_geom FROM predios WHERE activo = '1' AND cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_col' AND cod_man = '$cod_man_col' AND cod_pred = '$cod_pred_col'");
   pg_query("UPDATE temp_poly SET user_id = '$user_id', cod_cat = '$col_cod[$i]', numero = '5' WHERE user_id is NULL AND cod_uv = '$cod_uv_col' AND cod_man = '$cod_man_col' AND cod_pred = '$cod_pred_col'");
	 $i++;
}		
################################################################################
#--------------- SELECCIONAR MANZANOS PARA MAPA DE UBICACION ------------------#
################################################################################
/*$distancia_desde_predio = 150;
$sql="SELECT cod_uv, cod_man, the_geom FROM manzanos WHERE st_dwithin ((SELECT the_geom FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'),the_geom,$distancia_desde_predio)";
$result = pg_query($sql);
$i = $j = 0;
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
   foreach ($line as $col_value) {
	    if ($i == 0) {
			   $cod_uv_temp = $col_value;
			} elseif ($i == 1) {
			   $cod_man_temp = $col_value;
			} else {
			   $the_geom_temp = $col_value;
			   $number_temp = 115;
				 $label_temp = "U.V. ".$cod_uv_temp."@MZ. ".$cod_man_temp;
				 pg_query("INSERT INTO temp_poly (user_id, cod_uv, cod_man, numero, label, the_geom) VALUES ('$user_id','$cod_uv_temp','$cod_man_temp','$number_temp','$label_temp','$the_geom_temp')");
         $i = -1;
			}
      $i++;
   }
	 $i = 0;
}
pg_free_result($result);*/
################################################################################
#------------------------------- GENERAR MAPFILE ------------------------------#
################################################################################	

include "siicat_generar_mapfile_planocatastral.php";

################################################################################
#---------------------- LEER DATOS DE INFO_USO_DE_SUELO -----------------------#
################################################################################	
$sql="SELECT * FROM info_uso_de_suelo WHERE area = '$uso'";
$result=pg_query($sql);
$value = pg_fetch_array($result, null, PGSQL_ASSOC);
$area_uso = $value['area'];
$descrip = utf8_decode ($value['descrip']);
$area_actuacion = $descrip." (".$area_uso.")";
$sup_min = $value['sup_min'];
$sup_min_esq = $value['sup_min_esq'];
$fren_min = utf8_decode ($value['fren_min']);
$fren_min_esq = utf8_decode ($value['fren_min_esq']);
$ret_fron = utf8_decode ($value['ret_fron']);
$ret_fond = utf8_decode ($value['ret_fond']);
$ret_lat_izq = utf8_decode ($value['ret_lat_izq']);
$ret_lat_der = utf8_decode ($value['ret_lat_der']);
$sup_max_cub = utf8_decode ($value['sup_max_cub']);
$sup_max_edi = utf8_decode ($value['sup_max_edi']);
$alt_max_edi = utf8_decode ($value['alt_max_edi']);
$alt_max_fach = utf8_decode ($value['alt_max_fach']);
$anch_gal_ext = utf8_decode ($value['anch_gal_ext']);
$anch_gal_int = utf8_decode ($value['anch_gal_int']);
$pend_cub = utf8_decode ($value['pend_cub']);
$alt_ant_vent = utf8_decode ($value['alt_ant_vent']);
$alt_max_muro = utf8_decode ($value['alt_max_muro']);		         
pg_free_result($result); 
################################################################################
#---------------------------- SELECCIONAR GRAFICO -----------------------------#
################################################################################	
$filename = "C:/apache/htdocs/$folder/uso/".$area_uso."_seccion.jpg";
if (file_exists($filename)) {	 
   $grafico = $area_uso."_seccion";
} else $grafico = "sin";
################################################################################
#----------------------------- NOMBRE DE USUARIO ------------------------------#
################################################################################
$username = utf8_decode (get_username($session_id));
################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	

$filename = "C:/apache/htdocs/tmp/ln".$cod_cat.".html";

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
          <font style='font-family: Tahoma; font-size: 8pt; font-weight:bold; color:red'>No. </font>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp			 
          $fecha2 - $hora <a href='javascript:print(this.document)'>
          <img border='0' src='http://$server/$folder/graphics/printer.png' width='15' height='15' title='Imprimir en tamaño CARTA'></a>			 
          <h1>LINEA Y NIVEL &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp </h1>
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
   <tr height='15'>			
      <td align='left' colspan='8'>
         <font style='font-family: Tahoma; font-size: 7pt;'>&nbsp </font>
      </td>							 				
   </tr> 	 
   <tr height='30px' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='bottom' colspan='3'>
         &nbsp AREA DE ACTUACION (CATALOGACION):
      </td>	
      <td rowspan='8' align='left' valign='bottom' colspan='5'>
         &nbsp ESQUEMA DE ALTURAS 
				 <img src='http://$server/$folder/uso/$grafico.jpg' alt='imagen'  height='320' border='0'>
      </td>								 				
   </tr>	
	 <tr height='30px' style='font-family: Tahoma; font-size: 8pt;'>					
		  <td align='left' valign='top' colspan='3'>
			   &nbsp $area_actuacion
		  </td>
   </tr>
   <tr height='30px' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='bottom' colspan='3'>
         &nbsp AREAS MAXIMAS :
      </td>								 				
   </tr>
   <tr height='70px' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='top' colspan='3'>
         &nbsp AREA CUBIERTA ACTUAL : $edi_area m2<br />
				 &nbsp AREA CUBIERTA (PORCENTAJE): $edi_area_porc %<br />						 
				 &nbsp AREA PERMITIDA A CUBRIR : $sup_max_cub<br />
				 &nbsp ALTURA MAXIMA DE EDIFICACION : $alt_max_edi<br />				 
      </td>								 				
   </tr>
   <tr height='30px' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='bottom' colspan='3'>
         &nbsp RETIROS MINIMOS :
      </td>								 				
   </tr>
   <tr height='70px' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='top' colspan='3'>
         &nbsp FRONTAL : $ret_fron<br />
				 &nbsp POSTERIOR : $ret_fond<br />						 
				 &nbsp LATERAL IZQ. : $ret_lat_izq<br />
				 &nbsp LATERAL DER. : $ret_lat_der<br />				 
      </td>								 				
   </tr> 
   <tr height='30px' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='bottom' colspan='3'>
         &nbsp NIVEL :
      </td>								 				
   </tr>
   <tr height='50px' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='top' colspan='3'>
         &nbsp COTA INICIAL : -/+ 0.00 Mts.<br />
				 &nbsp COTA NIVEL : &nbsp <br />						 			 
      </td>								 				
   </tr>	  
  	  	 
   <tr height='$espacio_entre_tablas' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='bottom' colspan='8'>
         &nbsp ESQUEMA DE EDIFICACION : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
				 &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
         &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
				 OBSERVACIONES :
			</td>								 				
   </tr>	
	 	 
   <tr height='250' style='font-family: Tahoma; font-size: 8pt'>
      <td align='center' bgcolor='#FFFFFF' colspan='3'>";	 
if ($geometria_existe) {	
$content = $content."
         <iframe frameborder='0' name='mapserver' src='http://$server/cgi-bin/mapserv.exe?map=c:/apache/siicat/mapa/siicat_planocatastral.map&program=http://$server/cgi-bin/mapserv.exe&zoomsize=2&layer=Predios&layer=Manzanos&layer=Puntos&layer=Calles&imgext=$xmin $ymin $xmax $ymax&imgxy=750+625&mapext=&savequery=false&zoomdir=1&mode=map&imgbox=&imgsize=1500+1250&mapsize=750+625' id='content' width='300px' height='250px' align='middle' valign='center' scrolling='no' noresize='no' marginwidth='0' marginheight='0'>
				 </iframe>";
} else {
$content = $content."	
   <br />NO EXISTE LA GEOMETRIA DEL PREDIO";
}
$content = $content."			 
			</td>	 
      <td align='left' valign='top' colspan='5' rowspan='2'>
			   <table border='0'>
				    <tr height='140' style='font-family: Tahoma; font-size: 8pt'>
						   <td valign='top'>
			            <br /> $obs_linea	 			 
               </td>
			      </tr>
				    <tr height='150' style='font-family: Tahoma; font-size: 8pt'>
						   <td valign='top'>
							    <fieldset>
							    <table border='0' height='100%' width='100%'>
								     <tr height='115' style='font-family: Tahoma; font-size: 8pt'>
								        <td align='left' valign='top'>
			                     FICHA ELABORADO POR:
										   </td>
			               </tr>	
								     <tr height='30' style='font-family: Tahoma; font-size: 8pt'>
								        <td align='center' valign='bottom'>
										       $username
										   </td>
			               </tr>											 			
			            </table> 
							    </fieldset>			 
               </td>
			      </tr>				
			   </table>
			</td>					 				
   </tr>			 	 	  				    
   <tr height='50'>
      <td align='left' colspan='3'>
         <font style='font-family: Tahoma; font-size: 7pt;'> &nbsp NOTA: &nbsp $nota_linea </font>
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