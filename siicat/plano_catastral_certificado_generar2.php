<?php
################################################################################
#---------------------- ESCRIBIR FACTOR ZOOM EN TABLA -------------------------#
################################################################################	
if (isset($_POST['factor'])) {
	$factor = $_POST['factor'];
	$sql="SELECT factor FROM plano_cat_zoom WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
	$check_zoom = pg_num_rows(pg_query($sql));		
	if ($check_zoom > 0) {
		if ($factor == 1) {
			pg_query("DELETE FROM plano_cat_zoom WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");	
		} else {
			pg_query("UPDATE plano_cat_zoom SET factor = '$factor' WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");	
		} 
	 } elseif ($factor != 1) {
      pg_query("INSERT INTO plano_cat_zoom (cod_geo, id_inmu, factor) VALUES ('$cod_geo','$id_inmu','$factor')");	 
	 }
}


		########################################
      #      Chequear si existen filas       #
      ########################################	
      $sql="SELECT cod_uv FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
      $check_predio = pg_num_rows(pg_query($sql));		
		################################################################################
		#---------------------- SELECCIONAR GEOMETRIA PARA IFRAME ---------------------#
		################################################################################	
      if ($check_predio > 0) {
			$predio_existe = true;
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
					$xtent_x[$x] =ROUND($xt_x[$x],3);
					$j=$i+1;
					$z = 1;
					#			echo "$point_x[$x]<br /> ";
				} else if (($char == ' ') AND ($z == 1)) {
					$xt_y[$x] = substr($extent,$j,$i-$j);
					$xtent_y[$x] =ROUND($xt_y[$x],3);		
					$j=$i+3;			
					$z = 0;
					$x++;
				} 
				$i++;   
			}
			$centerx = ($xtent_x[0] + $xtent_x[1])/2;
			$centery = ($xtent_y[0] + $xtent_y[1])/2;
			########################################
			#------ LEER FACTOR ZOOM DE TABLA -----#
			########################################	
			$sql="SELECT factor FROM plano_cat_zoom WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
         $check_zoom = pg_num_rows(pg_query($sql));		
         if ($check_zoom > 0) {
            $result=pg_query($sql);
            $info = pg_fetch_array($result, null, PGSQL_ASSOC);
            $factor_delta = $info['factor'];
						$factor_zoom = $factor_zoom_plano_catastral*$factor_delta;	
						pg_free_result($result);	
				 
				} else {

				$factor_zoom = $factor_zoom_plano_catastral;
				}
				$ext_x = sqrt(($xtent_x[0] - $xtent_x[1])*($xtent_x[0] - $xtent_x[1]));
				$ext_y = sqrt(($xtent_y[0] - $xtent_y[1])*($xtent_y[0] - $xtent_y[1]));				 
		 
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
					$factor_zoom1 = 12;
				}  
         if ($centerx-$xtent_x[0] > $centery-$xtent_y[0]) {
            $delta = ($centerx-$xtent_x[0])* $factor_zoom;
						$delta1 = ($centerx-$xtent_x[0])* $factor_zoom1; 
         } else {
            $delta = ($centery-$xtent_y[0])* $factor_zoom;
						$delta1 = ($centery-$xtent_y[0])* $factor_zoom1;
		}
		$xmin=$centerx- $delta;
		$xmax=$centerx+ $delta; 
		$ymin=$centery- $delta;
		$ymax=$centery+ $delta;	
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
		pg_query("UPDATE temp_poly SET user_id = '$user_id', cod_cat = '$cod_cat', numero = 55 WHERE user_id IS NULL AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
		pg_query("INSERT INTO temp_poly (cod_uv, cod_man, cod_pred, the_geom) SELECT cod_uv, cod_man, cod_pred, the_geom FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");      
		pg_query("UPDATE temp_poly SET user_id = '$user_id', cod_cat = '$cod_cat', numero = 58 WHERE user_id IS NULL AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");		 
      }
################################################################################
#----------------------------- CALCULAR ESCALA --------------------------------#
################################################################################	
$extension_real = 2*$delta*0.63;
$extension_en_papel = 0.185;
$escala = $extension_real/$extension_en_papel;
$escala = ROUND($escala/100,0)*100;
################################################################################
#--------------------- SACAR INFORMACION DE LA BASE DE DATOS ------------------#
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

################################################################################
#-------------------------- DEFINIR ZONA DEL PREDIO ---------------------------#
################################################################################
$zona = get_zona_brujula ($cod_cat, $centro_del_pueblo_para_zonas_x, $centro_del_pueblo_para_zonas_y);
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
$sql="SELECT area(the_geom) FROM predios_ocha WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result=pg_query($sql);
$value = pg_fetch_array($result, null, PGSQL_ASSOC);	 			           
$sup_terr= ROUND($value['area'],2); 
pg_free_result($result); 

################################################################################
#------------------ INFORMACION Y AREA DE EDIFICACIONES -----------------------#
################################################################################	
$edi_area = 0;
########################################
#----- CALCULAR AREA EDIFICACIONES ----#
######################################## $area_edif[$i]     AND edi_piso = '$edi_piso[$j]'
$sql="	SELECT sum(area(the_geom)) AS edi_area
		FROM edificaciones 
		WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check = pg_num_rows(pg_query($sql));
if ($check == 0) {
	$edi_area = 0;
} else {
	$result_edif=pg_query($sql);
	$value_edif = pg_fetch_array($result_edif, null, PGSQL_ASSOC);			 			           
	$edi_area= ROUND($value_edif['edi_area'],2);
	pg_free_result($result_edif); 	 	
}
$edi_area = ROUND($edi_area,2);
pg_free_result($result);

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

			$col_cod[$j] = $cod_pred_col;
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
				 
			########################################
			#----------- RELLENAR TABLA -----------#
			########################################
			$result2=pg_query("SELECT x(centroid(the_geom)),y(centroid(the_geom))
				                FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_col' AND cod_man = '$cod_man_col' AND cod_pred = '$cod_pred_col'");     
			$cstr = pg_fetch_array($result2, null, PGSQL_ASSOC);

			$col_centroid_x = $cstr['x']; $col_centroid_y = $cstr['y'];
			pg_free_result($result2);
			$result2=pg_query("SELECT xmin(extent3d(the_geom)),xmax(extent3d(the_geom)),ymin(extent3d(the_geom)),ymax(extent3d(the_geom))
									FROM predios 
									WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'"); 	
         $cstr = pg_fetch_array($result2, null, PGSQL_ASSOC);																	 
			#$xmin = $cstr['xmin']; $xmax = $cstr['xmax'];
			#$ymin = $cstr['ymin']; $ymax = $cstr['ymax'];
			pg_free_result($result2); 
			#$col_pos[$j] = get_position4($col_centroid_x, $col_centroid_y, $centroid_x, $centroid_y);
			#$col_pos[$j] = get_position8($col_centroid_x, $col_centroid_y, $centroid_x, $centroid_y, $xmin, $xmax, $ymin, $ymax); 
			pg_query("INSERT INTO temp_poly (cod_uv,cod_man, cod_pred, the_geom) SELECT cod_uv, cod_man, cod_pred, the_geom FROM predios WHERE activo = '1' AND cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_col' AND cod_man = '$cod_man_col' AND cod_pred = '$cod_pred_col'");
			#pg_query("UPDATE temp_poly SET user_id = '$user_id', cod_cat = '$col_cod[$j]', numero = '66', label = '$col_tit[$j]' WHERE user_id is NULL AND cod_uv = '$cod_uv_col' AND cod_man = '$cod_man_col' AND cod_pred = '$cod_pred_col'");	
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
   $cod_uv_col = get_uv ($col_cod[$i]); 
   $cod_man_col = get_man($col_cod[$i]);  
   $cod_pred_col = get_pred ($col_cod[$i]);
   pg_query("INSERT INTO temp_poly (cod_uv, cod_man, cod_pred, the_geom) SELECT cod_uv, cod_man, cod_pred, the_geom FROM predios WHERE activo = '1' AND cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_col' AND cod_man = '$cod_man_col' AND cod_pred = '$cod_pred_col'");
   pg_query("UPDATE temp_poly SET user_id = '$user_id', cod_cat = '$col_cod[$i]', numero = '5' WHERE user_id is NULL AND cod_uv = '$cod_uv_col' AND cod_man = '$cod_man_col' AND cod_pred = '$cod_pred_col'");
	 $i++;
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
	$limite1 = "Noreste";
	$Colind1 = $col_norest_nom;
	$Medida1 = $col_norest_med;

} else { 
	$limite1 = "Norte";
	$Colind1 = $col_norte_nom;
	$Medida1 = $col_norte_med;

}
if ($col_sur_nom == "") {
	$limite2 = "Noroeste";
	$Colind2 = $col_noroes_nom;
	$Medida2 = $col_noroes_med;

} else { 
	$limite2 = "Sud";
	$Colind2 = $col_sur_nom;
	$Medida2 = $col_sur_med;

}

if ($col_este_nom == "") {
	$limite3 = "Sudeste";
	$Colind3 = $col_surest_nom;
	$Medida3 = $col_surest_med;

} else { 
	$limite3 = "Este";
	$Colind3 = $col_este_nom;
	$Medida3 = $col_este_med;

}

if ($col_oeste_nom == "") {
	$limite4 = "Sudoeste";
	$Colind4 = $col_suroes_nom;
	$Medida4 = $col_suroes_med;

} else { 
	$limite4 = "Oeste";
	$Colind4 = $col_oeste_nom;
	$Medida4 = $col_oeste_med;

}

################################################################################
#--------------- SELECCIONAR MANZANOS PARA MAPA DE UBICACION ------------------#
################################################################################
$distancia_desde_predio = 150;
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
				 $label_temp = "Dist. ".$cod_uv_temp."@MZ. ".$cod_man_temp;
				 pg_query("INSERT INTO temp_poly (user_id, cod_uv, cod_man, numero, label, the_geom) VALUES ('$user_id','$cod_uv_temp','$cod_man_temp','$number_temp','$label_temp','$the_geom_temp')");
         $i = -1;
			}
      $i++;
   }
	 $i = 0;
}
pg_free_result($result);

$Titutlo1 = utf8_decode("N° 001 - 23");
$Titutlo2 = utf8_decode("Según P. de Ubicación (m²)");
$Titutlo3 = utf8_decode("Según Mensura (m²)");
$Titutlo4 = utf8_decode("Según Título");
$Titutlo5 = utf8_decode("Según Declaración Jurada");
$Titutlo6 = utf8_decode("Plano de Mensura Nº");

$provincia_may = strtoupper($provincia);

################################################################################
#------------------------------- AVALUO CATASTRAL -----------------------------#
################################################################################	
$sql="SELECT gestion,valor_t,valor_vi,avaluo_total FROM imp_pagados WHERE id_inmu = '$id_inmu' ORDER BY gestion DESC LIMIT 1";
$result=pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$avaluo_terreno = $info['valor_t'];
$avaluo_const = $info['valor_vi'];
$avaluo_total = $info['avaluo_total'];
pg_free_result($result);

$fecha_actual = date("d-m-Y");
$gestion = date("Y",strtotime($fecha_actual."-1 year")); 
include "imp_avaluo_edif.php";
include "imp_avaluo_pred.php";
include "siicat_generar_mapfile_planocatastral.php";
include "siicat_generar_mapfile_planocatastral_ubicacion.php";


$total_avaluo = $avaluo_terr + $savaluo;
$filename = "C:/apache/htdocs/tmp/pc".$cod_cat.".html";
################################################################################
#----------------------------  GENERAR CODIGO QR   ----------------------------#
################################################################################	
   include "C:/apache/htdocs/phpqrcode/qrlib.php";

   $filen= "C:/apache/htdocs/tmp/test.png";
   $tamanio = 12;
   $level = "M";
   $framSize = 3;
   $contenido = $prop1." ".$tit_1ci;
   QRcode::png($contenido, $filen, $level, $tamanio, $framSize);
   $propietarios = $prop1." ".$tit_1ci." ".$prop2." ".$tit_2ci;
 
$espacio20 = "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
################################################################################
#------------------------  PREPARAR CONTENIDO DEL HTML   ----------------------#
################################################################################	

$content = " 

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Document</title>
    <link rel='stylesheet' href='css/siicat.css'>
</head>
<body>

<div id='ImprimeMapa' align='left'>

<table width='100%' height='100' solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt'>
	<tr>
		<td colspan='2'>
		</td>
		<td colspan='6' width='800' height='100px'>
			<table width='100%' height='100px'>
				<tr colspan='6'>
					<td align='right' colspan='6'>
						<a href='javascript:print(this.document)'>
						<img border='0' src='http://$server/$folder/graphics/printer.png' width='15' height='15'></a><br/>
					</td>
				</tr>	
				<tr  colspan='6'>
					<td align='center' colspan='6'>
						<font style='font-family: Arial; font-size: 12pt; font-weight: bold;'> &nbsp </font>
					</td>
				</tr>								
				<tr colspan='6'>
					<td align='center' colspan='6'>
						<font style='font-family: Bahnschrift Condensed; font-size: 10pt; font-weight:bold; '>UNIDAD DE CATASTRO URBANO</font>
					</td>
				
				</tr>	
				<tr colspan='6'> 
					<td align='right' colspan='5'>
						<font style='font-family: Bahnschrift Condensed; font-size: 18pt; font-weight:bold; color:#0b6730;'>CERTIFICADO CATASTRAL</font>
					</td>			
					<td align='left' style='border: 1px solid black;' colspan='1'>
						<font style='font-family: Arial; font-size: 10pt; font-weight:bold;'>No. $espacio20</font>
					</td>					
				</tr>              	 
			</table>
		</td>

        <td colspan='2' style='border:1px solid black; width:100px' align='right'>
            <img src='http://$server/tmp/test.png' alt='imagen' width='90' height='90' border='0'>
        </td>	        
	</tr>	
</table>

<table  width='100%' cellspacing='0' cellpadding='0'>
	<tr>
		<td width='15%'>
			<table  border='0' width='100%'  cellspacing='0' cellpadding='0'>
				<tr>
					<td align='center'>
						<font style='font-family: Arial; font-size: 10pt; font-weight:bold;'>&nbsp </font>
					</td>
				</tr>
				<tr height='22px'>
					<td align='center'>
						<font style='font-family: Arial; font-size: 10pt; font-weight:bold;'>&nbsp</font>
					</td>
				</tr>				
			</table>
		</td>	
		<td  width='25%'>
			<table width='100%' style='border: 1px solid black;' cellspacing='0' cellpadding='0'>
				<tr >
					<td colspan='2'   align='center' width='100%'>
						<font style='font-family: Arial; font-size: 8pt; font-weight:bold;'>CODIGO</font>
					</td>
				</tr>
				<tr height='22px'>
					<td colspan='2'  align='center' width='100%'>
						<font style='font-family: Arial; font-size: 8pt; font-weight:bold;'>CATASTRAL</font>
					</td>
				</tr>				
			</table>
		</td>
		<td>
			<table  colspan='5' style='border: 1px solid black;' width='100%' cellspacing='0' cellpadding='0'>
				<tr height='35px'>
					<td colspan='2'   align='center' width='100%'>
						<font style='font-family: Bahnschrift Condensed; font-size: 24pt; font-weight:bold;'>$cod_cat</font>
					</td>
				</tr>				
			</table>
		</td>
		<td>
			<table  colspan='2' style='border: 1px solid black;' width='100%' cellspacing='0' cellpadding='0'>
				<tr>
					<td colspan='2'  align='center'  colspan='1' width='100%'>
						<font style='font-family: Arial; font-size: 8pt; font-weight:bold;'>GEOCODIGO</font>
					</td>
				</tr>
				<tr height='22px'>
					<td align='center'  colspan='2' width='100%'>
						<font style='font-family: Arial; font-size: 10pt; font-weight:bold;'>&nbsp $geocodigo</font>
					</td>
				</tr>				
			</table>
		</td>
	</tr>
</table>	
<table width='100%' height='5px'>
	<tr>
		<td>
		</td>
	</tr>
</tabla>

<table id='plano'  border='1' cellspacing='0' width='100%' >
	<tr>
		<td align='left'  colspan='1'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp Matricula </font>
		</td>
		<td align='left'  colspan='4'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp $matricula </font>
		</td>	
		<td align='left'  colspan='1'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp Partida: </font>
		</td>
		<td align='left'  colspan='4'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp $partida</font>
		</td>					
	</tr>  
	<tr>
		<td align='left'  colspan='1'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp Propietarios: </font>
		</td>
		<td align='left'  colspan='9'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp $propietario</font>
		</td>				
	</tr>
	<tr>
		<td align='left'  colspan='1'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp Direccion: </font>
		</td>
		<td align='left'  colspan='9'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp $direccion</font>
		</td>				
	</tr> 	  
	<tr>
		<td align='left'  colspan='1'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp Ubicacion: </font>
		</td>
		<td align='left'  colspan='9'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp </font>
		</td>				
	</tr> 	  
	<tr>
		<td align='left' width='15%'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp Zona: </font>
		</td>
		<td align='center' width='10%'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp $zona </font>
		</td>	
		<td align='left' width='15%'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp Distrito: </font>
		</td>
		<td align='center' width='10%'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp $cod_uv </font>
		</td>						
		<td align='left' width='15%'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp Manzana: </font>
		</td>
		<td align='center' width='10%'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp $cod_man </font>
		</td>				
		<td align='left' width='15%'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp Lote: </font>
		</td>
		<td align='center' width='10%'>
			<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp $cod_pred </font>
		</td>															
	</tr> 	    				 	      
</table>




<table width='100%' style='border: 1px solid black;'>
	<tr>
        <td align='left'  colspan='8' width='50%' >
            <table width='100%' border='1' cellspacing='0'>
				<tr >
					<td align='center'  colspan='8' width='100%'>
						<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> DATOS FISICO </font>
					</td>                    			
				</tr>			
                <tr >
                    <td align='center'  colspan='5' width='50%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> SUPERFICIE DEL TERRENO </font>
                    </td>
                    <td align='center'  colspan='3' width='50%'>
                        <font style='font-family: Arial; font-size: 8pt; font-weight:bold;'> SUP. CONSTRUIDA</font>
                    </td>	                           			
                </tr>
                <tr>
                    <td align='center'  colspan='2' width='30%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> Sup/Legal</font>
                    </td>
                    <td align='center'  colspan='3' width='35%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> Sup.Seg.Men</font>
                    </td>
                    <td align='center'  colspan='3' width='35%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> Sup/Levant.</font>
                    </td>						                           			
                </tr>
                <tr>
                    <td align='center'  colspan='2' width='30%'>
                        <font style='font-family: Arial; font-size: 9pt;'> &nbsp $adq_sdoc</font>
                    </td>
                    <td align='center'  colspan='3' width='35%'>
                        <font style='font-family: Arial; font-size: 9pt;'> &nbsp  $sup_terr</font>
                    </td>	  
                    <td align='center'  colspan='2' width='35%'>
                        <font style='font-family: Arial; font-size: 9pt;'> &nbsp $edi_area</font>
                    </td>                         			
                </tr>
                <tr>
                    <td align='left'  colspan='2' width='30%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> &nbsp Referencia </font>
                    </td>
                    <td align='center'  colspan='6' width='70%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> &nbsp Distancia (m)</font>
                    </td>	                         			
                </tr>
                <tr>
                    <td align='left'  colspan='2' width='30%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> &nbsp Frente</font>
                    </td>
                    <td align='center'  colspan='6' width='70%'>
                        <font style='font-family: Arial; font-size: 9pt; '> &nbsp $ter_fren</font>
                    </td>	                         			
                </tr>	
                <tr>
                    <td align='left'  colspan='2' width='30%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> &nbsp Contrafrente</font>
                    </td>
                    <td align='center'  colspan='6' width='70%'>
                        <font style='font-family: Arial; font-size: 9pt; '> &nbsp $ter_con_fre</font>
                    </td>	                         			
                </tr>	
                <tr>
                    <td align='left'  colspan='2' width='30%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> &nbsp Fondo</font>
                    </td>
                    <td align='center'  colspan='6' width='70%'>
                        <font style='font-family: Arial; font-size: 9pt;'> &nbsp $ter_fond</font>
                    </td>	                         			
                </tr>	
                <tr>
                    <td align='left'  colspan='2' width='30%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> &nbsp Contrafondo</font>
                    </td>
                    <td align='center'  colspan='6' width='70%'>
                        <font style='font-family: Arial; font-size: 9pt; '> &nbsp $ter_con_fon</font>
                    </td>	                         			
                </tr>																					
            </table>
        </td>
        <td align='left'  colspan='8' width='50%'>
            <table width='100%'  border='1' cellspacing='0'>
                <tr>
                    <td align='center'  colspan='8' width='100%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> COLINDANTES </font>
                    </td>
                </tr>
                <tr>
                    <td align='left'  colspan='2' width='20%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp $limite1</font>
                    </td>
                    <td align='left'  colspan='6' width='80%'>
                        <font style='font-family: Arial; font-size: 9pt; '>&nbsp $Colind1 $Medida1 </font>
                    </td>
                </tr>

                <tr>
                    <td align='left'  colspan='2' width='20%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp $limite2 </font>
                    </td>
                    <td align='left'  colspan='6' width='80%'>
                        <font style='font-family: Arial; font-size: 9pt; '> &nbsp  $Colind2 $Medida2 </font>
                    </td>
                </tr>	
                <tr>
                    <td align='left'  colspan='2' width='20%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp $limite3 </font>
                    </td>
                    <td align='left'  colspan='6' width='80%'>
                        <font style='font-family: Arial; font-size: 9pt; '> &nbsp $Colind3 $Medida3  </font>
                    </td>
                </tr>
                <tr>
                    <td align='left'  colspan='2' width='20%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp $limite4 </font>
                    </td>
                    <td align='left'  colspan='6' width='80%'>
                        <font style='font-family: Arial; font-size: 9pt; '> &nbsp $Colind4 $Medida4</font>
                    </td>
                </tr>
                <tr>
                    <td align='left'  colspan='8' width='100%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> &nbsp P.H. Nombre </font>
                    </td>
                </tr>	
                <tr>
                    <td align='left'  colspan='2' width='30%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp  Bloque </font>
                    </td>
                    <td align='left'  colspan='2' width='20%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> &nbsp </font>
                    </td>
                    <td align='center'  colspan='4' width='50%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> &nbsp Colindancia</font>
                    </td>					
                </tr>
                <tr>
                    <td align='left'  colspan='2' width='30%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp Departamento </font>
                    </td>
                    <td align='left'  colspan='2' width='20%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> &nbsp </font>
                    </td>
                    <td align='left'  colspan='2' width='25%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> &nbsp Superior </font>
                    </td>
                    <td align='left'  colspan='2' width='25%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> &nbsp </font>
                    </td>															
                </tr>		
                <tr>
                    <td align='left'  colspan='2' width='30%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp Piso </font>
                    </td>
                    <td align='left'  colspan='2' width='20%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> &nbsp </font>
                    </td>
                    <td align='left'  colspan='2' width='25%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> &nbsp Inferior </font>
                    </td>
                    <td align='left'  colspan='2' width='25%'>
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> &nbsp </font>
                    </td>															
                </tr>																                	                                	                	 
            </table>
        </td>	        			
	</tr>    	
</table>



<table width='100%' style='border: 1px solid black;'>
	<tr>
        <td width='50%' colspan='3' rowspan='3' style='border: 1px solid black;'>
            <table width='100%'>
                <tr rowspan='2'>
                    <td align='left' height='350px' colspan='4' rowspan='16' bgcolor='#FFFFFF' style='vertical-align:top;'>	 
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> &nbsp </font>
                        <iframe frameborder='0' name='mapserver' src='http://$server/cgi-bin/mapserv.exe?map=c:/apache/siicat/mapa/siicat_planocatastral.map&program=http://$server/cgi-bin/mapserv.exe&zoomsize=2&layer=Predios&layer=Manzanos&layer=Puntos&layer=Calles&imgext=$xmin $ymin $xmax $ymax&imgxy=700+550&mapext=&savequery=false&zoomdir=1&mode=map&imgbox=&imgsize=1400+1000&mapsize=1000+1000' id='content' width='350px' height='350px' align='middle' valign='center' scrolling='no' noresize='no' marginwidth='0' marginheight='0'>
                        </iframe>
                    </td>									
                </tr>
            </table>	
        </td>

		<td width='50%'  colspan='2' rowspan='1' style='border: 1px solid black; vertical-align:top;'>
            <table width='100%'>
				<tr>
					<td align='left'  colspan='2' width='100%'>
						<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'> &nbsp PLANO DE UBICACION </font>
					</td>							
				</tr>
                <tr rowspan='1'>
                    <td align='left' height='240px' colspan='4' rowspan='16' bgcolor='#FFFFFF' style='vertical-align:top;'>	 
                        <font style='font-family: Arial; font-size: 9pt; font-weight:bold;'></font>
		                  <iframe frameborder='0' name='mapserver' src='http://$server/cgi-bin/mapserv.exe?map=c:/apache/siicat/mapa/siicat_planocatastral_ubicacion.map&program=http://$server/cgi-bin/mapserv.exe&zoomsize=2&layer=Predios&layer=Manzanos&layer=Calles&imgext=$xmin1 $ymin1 $xmax1 $ymax1&imgxy=770+550&mapext=&savequery=false&zoomdir=1&mode=map&imgbox=&imgsize=1400+1050&mapsize=770+550' id='content' width='350px' height='250px' align='middle' valign='center' scrolling='no' noresize='no' marginwidth='0' marginheight='0'>
		                  </iframe>
                    </td>									
                </tr>			
            </table>
		</td>
	</tr>

	<tr>
		<td width='50%' colspan='2' style='border: 1px solid black; vertical-align:top;'>

			<table border='1' width='100%' height='100%' style='border-collapse:collapse; font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'>	
				<tr height='10%'>
					<td width='8%' align='center'>ID</td>
					<td width='20%' align='center'>ESTE</td>
					<td width='22%' align='center'>NORTE</td>
					<td width='8%' align='center'>ID</td>
					<td width='20%' align='center'>ESTE</td>
					<td width='22%' align='center'>NORTE</td>
				</tr>
				<tr height='90%'>
					<td align='center' valign='top'>";
						$max_filas = 4;
						$numeros = "P1";
						$i = 1;
						while (($i < $no_de_vertices) AND ($i < $max_filas)) {
							$j = $i + 1;
							$numeros = $numeros."<br />P$j";
							$i++;
							}
							$content = $content."
							$numeros											
					</td>
					<td align='right' valign='top'>";
						$coordenadas_x = "$point_x[0]";
						$i = 1;
						while (($i < $no_de_vertices) AND ($i < $max_filas)) {
							$coordenadas_x = $coordenadas_x."<br/>$point_x[$i]";
							$i++;
							}
							$content = $content."
							$coordenadas_x														
					</td>							 
					<td align='right' valign='top'>";
						$coordenadas_y = "$point_y[0]";
						$i = 1;
						while (($i < $no_de_vertices) AND ($i < $max_filas)) {
							$coordenadas_y = $coordenadas_y."<br />$point_y[$i]";
							$i++;
							}
							$content = $content."
							$coordenadas_y	
					</td>
					<td align='center' valign='top'>";
						$max_filas2 = $max_filas * 2;
						if ($no_de_vertices > $max_filas) {
						$i = $max_filas+1;
						$numeros2 = "P".$i;
						while (($i < $no_de_vertices) AND ($i < $max_filas2)) {
						$j = $i + 1;
						$numeros2 = $numeros2."<br />P$j";
						$i++;
						}
						} else $numeros2 = "&nbsp";
						$content = $content."
						$numeros2													
					</td>
					<td align='right' valign='top'>";
						if ($no_de_vertices > $max_filas) {												
						$coordenadas_x2 = "&nbsp $point_x[$max_filas]";
						$i = $max_filas+1;
						while (($i < $no_de_vertices) AND ($i < $max_filas2)) {
						$coordenadas_x2 = $coordenadas_x2."<br />$point_x[$i]";
						$i++;
						}
						} else $coordenadas_x2 = "&nbsp";
						$content = $content."
						$coordenadas_x2													
					</td>							 
					<td align='right' valign='top'>";
						if ($no_de_vertices > $max_filas) {												
						$coordenadas_y2 = "&nbsp $point_y[$max_filas]";
						$i = $max_filas+1;
						while (($i < $no_de_vertices) AND ($i < $max_filas2)) {
						$coordenadas_y2 = $coordenadas_y2."<br />$point_y[$i]";
						$i++;
						}
						} else $coordenadas_y2 = "&nbsp";
						$content = $content."
						$coordenadas_y2	
					</td>													 
				</tr>											 	
			</table>
		</td>	
	</tr>	
</table>


<table width='100%' style='border: 1px solid black; vertical-align:top;'>
	<tr>
		<td width='60%' rowspan='2' >
			<table width='100%'>
				<tr>
					<td align='left'>
						<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp </font>
					</td>								
				</tr>
				<tr>
					<td align='left'>
						<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp </font>
					</td>
					<td align='left'>
						<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp </font>
					</td>							
					<td align='left'>
							<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp </font>
					</td>																		
				</tr>		
				<tr>
					<td align='left'>
						<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp </font>
					</td>								
				</tr>							
			</table>
		</td>

		<td valign='top' width='40%' style='border: 1px solid black; vertical-align:top;'>
			<table border='1' width='100%' height='100%' style='border-collapse:collapse; font-family: Arial; font-size: 8pt' bgcolor='#FFFFFF'>
				<tr >
					<td align='left' width='100%' colspan='2'>
						<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp DATOS ECONOMICOS</font>
					</td>								
				</tr>
				<tr>
					<td align='left' width='50%'>
						<font style='font-family: Arial; font-size: 8pt; font-weight:bold;'>&nbsp Uso Destino</font>
					</td>
					<td align='left' width='50%'>
						<font style='font-family: Arial; font-size: 8pt; font-weight:bold;'>&nbsp $ter_uso</font>
					</td>																			
				</tr>
				<tr>
					<td align='left'>
						<font style='font-family: Arial; font-size: 8pt;'>&nbsp Zona Homogenea</font>
					</td>
					<td align='left'>
						<font style='font-family: Arial; font-size: 8pt; font-weight:bold;'>&nbsp $ben_zona</font>
					</td>																			
				</tr>				
				<tr>
					<td align='left'  colspan='2'>
						<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>&nbsp VALOR CATASTRAL</font>
					</td>								
				</tr>
				<tr>
					<td align='left'>
						<font style='font-family: Arial; font-size: 8pt;'>&nbsp Valor Terreno</font>
					</td>
					<td align='left'>
						<font style='font-family: Arial; font-size: 8pt;'>&nbsp </font>
					</td>																			
				</tr>
				<tr>
					<td align='left'>
						<font style='font-family: Arial; font-size: 8pt;'>&nbsp Valor Construcion</font>
					</td>
					<td align='left'>
						<font style='font-family: Arial; font-size: 8pt;'>&nbsp </font>
					</td>																			
				</tr>
				<tr>
					<td align='left'>
						<font style='font-family: Arial; font-size: 8pt;'>&nbsp Valor Total</font>
					</td>
					<td align='left'>
						<font style='font-family: Arial; font-size: 8pt;'>&nbsp </font>
					</td>																			
				</tr>															
			</table>
		</td>
</table>


<table width='100%' style='border: 0px solid black;'>
	<tr>
		<td width='100%' style='border: 1px solid black;'>
			<table width='100%'>
				<tr>
					<td align='left'>
						<font style='font-family: Arial; font-size: 9pt; font-weight:bold;'>Observaciones: </font>
					</td>								
				</tr>							
			</table>
		</td>
	</tr>
</table>

</div>
</body>";
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


