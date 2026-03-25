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
#			echo "$point_y[$x]<br /> ";
			         $x++;
	          } 
#  echo "$char $i $j<br />";
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
#echo "DELTA viene de X y es $delta<br />\n";
         } else {
            $delta = ($centery-$xtent_y[0])* $factor_zoom;
						$delta1 = ($centery-$xtent_y[0])* $factor_zoom1;
#echo "DELTA viene de Y y es $delta<br />\n";
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
    } 
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
$sup_terr= ROUND($value['area']+0.001,2); 
pg_free_result($result); 

################################################################################
#------------------ INFORMACION Y AREA DE EDIFICACIONES -----------------------#
################################################################################	
$edi_area = 0;
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
        } else {
            $edi_edo[$j] = utf8_decode(abr($col_value));
            $i = -1;			 
        }
        $i++;
   }
	 ########################################
   #----- CALCULAR AREA EDIFICACIONES ----#
   ########################################
   $sql="SELECT area(the_geom) FROM edificaciones WHERE cod_geo = '$cod_geo' AND  cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND edi_num = '$edi_num[$j]'";
 	 $check = pg_num_rows(pg_query($sql));
   if ($check == 0) {
		$edi_area = $edi_area + 0;
		$area_edif[$j] = 0;
   } else {
		$result_edif=pg_query($sql);
		$value_edif = pg_fetch_array($result_edif, null, PGSQL_ASSOC);		
		$edi_area = $edi_area + $value_edif['area'];	 			           
		$area_edif[$j]= ROUND($value_edif['area'],2);
		pg_free_result($result_edif); 	 	
	 }
	 $j++;	 
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
				 $label_temp = "U.V. ".$cod_uv_temp."@MZ. ".$cod_man_temp;
				 pg_query("INSERT INTO temp_poly (user_id, cod_uv, cod_man, numero, label, the_geom) VALUES ('$user_id','$cod_uv_temp','$cod_man_temp','$number_temp','$label_temp','$the_geom_temp')");
         $i = -1;
			}
      $i++;
   }
	 $i = 0;
}
pg_free_result($result);


$titulo7 = 'El Gobierno Autonomo Municipal de '.$municipio.', a través de la Dirección Urbana, CERTIFICA que mediante el Código Catastral y el registro en Derechos Reales (DD.RR). registra la siguiente información.';

$provincia_may = strtoupper($provincia);

################################################################################
#------------------------------- AVALUO CATASTRAL -----------------------------#
################################################################################	
$sql="SELECT gestion,valor_t,valor_vi,avaluo_total FROM imp_pagados WHERE id_inmu = '$id_inmu' ORDER BY gestion DESC LIMIT 1";
$result=pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$ges_ult = $info['gestion']; 
$avaluo_terreno = $info['valor_t'];
$avaluo_const = $info['valor_vi'];
$avaluo_total = $info['avaluo_total'];
pg_free_result($result);


$fecha_actual = date("d-m-Y");
$gestion = date("Y",strtotime($fecha_actual."-1 year")); 
$gestion_actual = date("Y"); 
include "imp_avaluo_edif.php";
include "imp_avaluo_pred.php";
include "siicat_generar_mapfile_planocatastral.php";
$total_avaluo = $avaluo_terr + $savaluo;
$filename = "C:/apache/htdocs/tmp/informetecnico_".$cod_cat.".html";
################################################################################
#----------------------------  GENERAR CODIGO QR   ----------------------------#
################################################################################	
   include "C:/apache/htdocs/phpqrcode/qrlib.php";

   $filen= "C:/apache/htdocs/tmp/test.png";
   $tamanio = 15;
   $level = "M";
   $framSize = 3;
   $contenido = $prop1." ".$tit_1ci." Cod.Cat ".$cod_cat."Fecha de emision:".$fecha2." GAM ".$municipio ;
   QRcode::png($contenido, $filen, $level, $tamanio, $framSize);
   
$cod_uv_c   = str_pad($cod_uv, 2, "0", STR_PAD_LEFT);
$cod_man_c  = str_pad($cod_man, 4, "0", STR_PAD_LEFT);
$cod_pred_c = str_pad($cod_pred, 3, "0", STR_PAD_LEFT);

################################################################################
#------------------------       CONTROL DE No C.C.       ----------------------#
################################################################################
$sql="SELECT * FROM control_doc WHERE id_doc = 2";
$result=pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$nro_doc = $info['nro_doc']+1;
$nro_doc   = str_pad($nro_doc, 6, "0", STR_PAD_LEFT);
$titulo5 = utf8_decode($info['titulo1']);

$name_foto1 = get_codcat_foto($cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto);
$name_foto1 = "http://$server/$folder/fotos/".$name_foto1.".jpg";
$name_foto2 = get_codcat_foto($cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto)."A";
$name_foto2 = "http://$server/$folder/fotos/".$name_foto2.".jpg";
################################################################################
#------------------------  PREPARAR CONTENIDO DEL HTML   ----------------------#
################################################################################	
$content = " 
<div id='ImprimeMapa' align='left'>
<table border='1' width='100%' height='100' style='border: 2px solid black; border-collapse:collapse;'>
   <tr>
      <td>
         <table width='100%'>
            <tr>
               <td width='80px'><img src='http://$server/$folder/css/$nomlog' alt='imagen' width='50' height='50' border='0'></td>
               <td width='250px' height='100px'>
                  <table width='100%' height='100px' >
                     <tr style='height:14'>
                        <td align='center' style='font-family: Arial; font-size: 9pt;'>GOBIERNO AUTONOMO MUNICIPAL DE</td>
                     </tr>								
                     <tr style='height:14'>
                        <td align='center' style='font-family: Arial; font-size: 9pt;'>$municipio</td>
                     </tr>	
                     <tr style='height:10'>
                        <td align='center' style='font-family: Arial; font-size: 6pt;'>$titulo1</td>
                     </tr>
                     <tr style='height:10'>
                        <td align='center' style='font-family: Arial; font-size: 6pt;'>$titulo2</td>
                     </tr>	    
                     <tr style='height:10'>
                        <td align='center' style='font-family: Arial; font-size: 6pt;'>$titulo3</td>
                     </tr>	                            
                  </table>
               </td>

               <td width='400px'>
                  <table width='100%' height='100px' style='border: 1px solid black;'>
                     <tr>
                        <td colspan='4' align='center'>
                        <a href='javascript:print(this.document)'>
                        <img src='http://$server/$folder/graphics/printer.png' width='15' height='15'></a><br/>
                        </td>
                     </tr>
                     <tr>
                        <td colspan='4' align='center' style='font-family: Arial; font-size: 20pt; font-weight:bold;' align='right'>INFORME TECNICO</td>
                     </tr>
                     <tr style='border: 1px solid black; border-collapse: collapse;'>
                        <td align='center' colspan='1' width='35%' style='border: 1px solid black; border-collapse: collapse;'>
                           <font style='font-family: Arial; font-size: 6pt;'>CODIGO CATASTRAL DEL GAMS</font>
                        </td>
                        <td align='center' colspan='1' width='25%' style='border: 1px solid black; border-collapse: collapse;'>
                           <font style='font-family: Arial; font-size: 11pt;  font-weight:bold;'>$cod_uv_c</font>
                        </td>
                        <td align='center' colspan='1' width='20%' style='border: 1px solid black; border-collapse: collapse;'>
                           <font style='font-family: Arial; font-size: 11pt;  font-weight:bold;'>$cod_man_c</font>
                        </td>  
                        <td align='center' colspan='1' width='20%' style='border: 1px solid black; border-collapse: collapse;'>
                           <font style='font-family: Arial; font-size: 11pt;  font-weight:bold;'>$cod_pred_c</font>
                        </td>                                                               
                     </tr>                        
                  </table>
               </td>
            </tr>
         </table>

         <table width='100%'   style='border: 1px solid black; font-family: Arial; font-size: 9pt;'>
            <tr>
               <td colspan='5' align='right'>
                  <font style='font-family: Arial; font-size: 10pt;'>$municipio_abr &nbsp&nbsp No.$nro_doc/$gestion_actual &nbsp&nbsp</font>
               </td>               
            </tr>	
            
            <tr>
               <td colspan='5' align='left'>
                  <p style='font-family: Arial; font-size: 10pt;'> $titulo5
                  </p> 
               </td>
            </tr>	
            <tr style='font-family: Arial; font-size: 9pt;'>
               <td colspan='1' width='10%'>&nbsp</td>
               <td colspan='1' align='center' width='30%'>TESTIMONIO No</td>
               <td colspan='1' width='20%'>&nbsp</td>
               <td colspan='1' align='center' width='30%'>MATRICULA DD.RR</td>
               <td colspan='1' width='10%'>&nbsp</td>
            </tr>	
            <tr style='font-family: Arial; font-size: 9pt;'>
               <td colspan='1' width='10%'>&nbsp</td>
               <td colspan='1' align='center' width='30%' style='border: 1px solid black; border-color:#9b9b9b'>$partida</td>
               <td colspan='1' width='20%'>&nbsp</td>
               <td colspan='1' align='center' width='30%' style='border: 1px solid black; border-color:#9b9b9b'>$matricula</td>
               <td colspan='1' width='10%'>&nbsp</td>
            </tr>	
            <tr><td style='font-family: Arial; font-size: 4pt; '>&nbsp</td></tr>        
         </table>


         <table width='100%' style='border: 1px solid black; font-family: Arial; font-size: 8pt;'>
            <tr>
               <td align='left'  colspan='8' style='font-family: Arial; font-size: 9pt; font-weight:bold;'>1. DATOS DE UBICACION DEL INMUEBLE</td>			
            </tr>
            <tr>
               <td align='right'  colspan='1' width='15%' style='font-weight:bold;'>Departamento:</td>
               <td align='left'   colspan='1' width='20%' style='border: 1px solid black; border-color:#9b9b9b'>&nbsp $depart</td>
               <td align='right'  colspan='1' width='12%' style='font-weight:bold;'>Lote No.:</td>
               <td align='center' colspan='1' width='10%' style='border: 1px solid black; border-color:#9b9b9b'>&nbsp $cod_pred_c</td>	
               <td align='right'  colspan='1' width='13%' style='font-weight:bold;'>Manzana No.:</td>
               <td align='center' colspan='1' width='10%' style='border: 1px solid black; border-color:#9b9b9b'>$cod_man_c</td>	
               <td align='right'  colspan='1' width='10%' style='font-weight:bold;'>Distrito:</td>
               <td align='center' colspan='1' width='10%' style='border: 1px solid black; border-color:#9b9b9b'>$cod_uv_c</td>                													
            </tr>	
            <tr>
               <td align='right' colspan='1' width='15%' style='font-weight:bold;'>Provincia:</td>
               <td align='left'  colspan='1' width='20%' style='border: 1px solid black; border-color:#9b9b9b'>&nbsp $provincia_may</td>
               <td align='right' colspan='1' width='12%' style='font-weight:bold;'>Urbanizacion:</td>	
               <td align='left'  colspan='5' width='53%' style='border: 1px solid black; border-color:#9b9b9b'>&nbsp $dir_zonurb</td>							
            </tr>	
            <tr>
               <td align='right' colspan='1' width='15%' style='font-weight:bold;'>Municipio:</td>
               <td align='left'  colspan='1' width='20%' style='border: 1px solid black; border-color:#9b9b9b'>&nbsp $municipio</td>
               <td align='right' colspan='1' width='12%' style='font-weight:bold;'>Barrio:</td>	
               <td align='left'  colspan='5' width='53%' style='border: 1px solid black; border-color:#9b9b9b'>&nbsp $barrio</td>									
            </tr>	
            <tr>
               <td align='right' colspan='1' width='15%' style='font-weight:bold;'>Zona:</td>
               <td align='left'  colspan='1' width='20%' style='border: 1px solid black; border-color:#9b9b9b'>&nbsp </td>
               <td align='right' colspan='1' width='12%' style='font-weight:bold;'>Direccion:</td>	
               <td align='left'  colspan='5' width='53%' style='border: 1px solid black; border-color:#9b9b9b'>&nbsp $direccion </td>				
            </tr>
            <tr><td style='font-family: Arial; font-size: 4pt; '>&nbsp</td></tr>
         </table>	


         <table width='100%' style='font-family: Arial; font-size: 8pt; border: 1px solid black;'>
            <tr style='font-family: Arial; font-size: 9pt; font-weight:bold;'>
               <td align='left' colspan='5' width='100%'> 2. DATOS DE IDENTIDAD DEL PROPIETARIO</td>			
            </tr>
            <tr style='font-family: Arial; font-size: 8pt;'>
               <td align='right'  colspan='1' width='15%' style='font-weight:bold;'> Propietario(s):</td>
               <td align='left'   colspan='3' width='50%' style='border: 1px solid black; border-color:#9b9b9b'><font >&nbsp $prop1</font></td>                  
               <td align='center' colspan='1' width='15%' style='border: 1px solid black; border-color:#9b9b9b'>$tit_1ci</td>	
               <td align='right'  colspan='1' width='10%' style='font-weight:bold;'> &nbsp</font></td>                            
            </tr>	
            <tr>
               <td align='right'  colspan='1' width='15%'>&nbsp</td>
               <td colspan='3' width='50%' style='border: 1px solid black; border-color:#9b9b9b'>&nbsp $prop2</td>                   
               <td align='center' colspan='1' width='15%' style='border: 1px solid black; border-color:#9b9b9b'>$tit_2ci</td>	
               <td align='right'  colspan='1' width='10%' style='font-weight:bold;'>&nbsp</td>						
            </tr>	
            <tr>
               <td align='right'  colspan='1' width='15%'>&nbsp</td>
               <td align='left'   colspan='3' width='50%' style='border: 1px solid black; border-color:#9b9b9b'>&nbsp $prop3</td>                   
               <td align='center' colspan='1' width='15%' style='border: 1px solid black; border-color:#9b9b9b'>$tit_3ci</td>	
               <td align='right'  colspan='1' width='10%' style='font-weight:bold;'>&nbsp</td>						
            </tr>	
            <tr>
               <td align='right'  colspan='1' width='15%'>&nbsp</td>
               <td align='left'   colspan='3' width='50%' style='border: 1px solid black; border-color:#9b9b9b'>&nbsp $prop4</td>                   
               <td align='center' colspan='1' width='15%' style='border: 1px solid black; border-color:#9b9b9b'>$tit_4ci</td>	
               <td align='right'  colspan='1' width='10%' style='font-weight:bold;'>&nbsp</td>						
            </tr>
            <tr>
               <td align='right'  colspan='1' width='15%'>&nbsp</td>
               <td align='left'   colspan='3' width='50%' style='border: 1px solid black; border-color:#9b9b9b'>&nbsp $prop5</td>
               <td align='center' colspan='1' width='15%' style='border: 1px solid black; border-color:#9b9b9b'>$tit_5ci</td>	
               <td align='right'  colspan='1' width='10%' style='font-weight:bold;'>&nbsp</td>						
            </tr>                                
               <tr><td style='font-family: Arial; font-size: 4pt; '>&nbsp</td></tr>		
         </table>	


         <table width='100%' style='border: 1px solid black;'>
            <tr>
               <td width='60%' >
                  <table border='0' width='100%''>
                     <tr>
                        <td  style='font-family: Arial; font-size: 9pt; font-weight:bold; border-bottom: 1px solid black;'>3. CROQUIS DE UBICACION</td>
                     </tr>
                     <tr>
                        <td align='left' height='350px' colspan='4' rowspan='16' bgcolor='#FFFFFF' style='vertical-align:top;'>	 
                           <iframe frameborder='0' 
                              name='mapserver' 
                              src='http://$server/cgi-bin/mapserv.exe?map=c:/apache/siicat/mapa/siicat_planocatastral.map&program=http://$server/cgi-bin/mapserv.exe&zoomsize=2&layer=Predios&layer=Manzanos&layer=Puntos&layer=Calles&imgext=$xmin $ymin $xmax $ymax&imgxy=700+550&mapext=&savequery=false&zoomdir=1&mode=map&imgbox=&imgsize=1400+1000&mapsize=1000+1000' 
                              id='content' 
                              width='400px' 
                              height='400px' 
                              align='middle' 
                              valign='center' 
                              scrolling='no' 
                              noresize='no' 
                              marginwidth='0' 
                              marginheight='0'>
                           </iframe>
                        </td>	
                     </tr>  
                  </table>              
               </td>



               <td width='40%'>
                  <table border='0'  width='100%' style='font-family: Arial; font-size: 8pt; border: 1px solid black;'>
                     <tr>
                        <td colspan='4' width='100%' style='font-family: Arial; font-size: 9pt; font-weight:bold; '>4. DATOS DEL TERRENO</td>
                     </tr>                       
                     <tr>
                        <td colspan='2' width='55%' style='font-family: Arial; font-size: 8pt; font-weight:bold;'>
                           <table border='0' width='100%' style='font-family: Arial; font-size: 8pt;'>
                              <tr>
                                 <td colspan='2' width='50%' style='font-family: Arial; font-size: 8pt; font-weight:bold; '>4.1 SUPERFICIE:</td>
                              </tr>
                              <tr>    
                                 <td colspan='1' width='25%' align='center'>Sup. s/doc.</td>
                                 <td colspan='1' width='25%' align='center'>Sup. s/mens.:</td>                                        
                              </tr>
                              <tr>
                                 <td colspan='1' width='25%' align='center' style='border: 1px solid black; border-color:#9b9b9b'>$adq_sdoc</td>
                                 <td colspan='1' width='25%' align='center' style='border: 1px solid black; border-color:#9b9b9b'>$sup_terr </td>                          
                              </tr>  
                              <tr>
                                 <td colspan='1' width='25%'>Zonificación:</td>
                                 <td colspan='1' width='25%' align='center' style='border: 1px solid black; border-color:#9b9b9b'>$ben_zona </td>                    
                              </tr>   
                              <tr>
                                 <td colspan='1' width='25%'>Material de via:</td>
                                 <td colspan='1' width='25%' align='center' style='border: 1px solid black; border-color:#9b9b9b'>$via_mat_texto </td>
                              </tr>  
                              <tr>
                                 <td colspan='1' width='25%'>Topografia:</td>
                                 <td colspan='1' width='25%' align='center' style='border: 1px solid black; border-color:#9b9b9b'>$ter_topo </td>                      
                              </tr>                                            
                              <tr>
                                 <td colspan='1' width='25%'>Forma:</td>
                                 <td colspan='1' width='25%' align='center' style='border: 1px solid black; border-color:#9b9b9b'>$ter_form_tex </td>                      
                              </tr>                                                                                                                                               
                           </table>
                        </td>
                     </tr>       
                  </table>


                  <table border='0'  width='100%' style='font-family: Arial; font-size: 8pt; border: 1px solid black;'>
                     <tr>
                           <td colspan='2' width='45%' >
                              <table border='0' width='100%' style='font-family: Arial; font-size: 8pt;'>
                                    <tr>
                                       <td colspan='2' width='50%' style='font-family: Arial; font-size: 8pt; font-weight:bold; '>4.2 DIMENSIONES:</td>
                                    </tr>
                                    <tr>    
                                       <td colspan='1' width='25%'>&nbsp</td>
                                       <td colspan='1' width='25%'>&nbsp</td>                                          
                                    </tr>
                                    <tr>
                                       <td colspan='1' width='25%'>Frente</td>
                                       <td colspan='1' width='25%' align='center' style='border: 1px solid black; border-color:#9b9b9b'>$ter_fren</td>                            
                                    </tr> 
                                    <tr>
                                       <td colspan='1' width='25%'>Contra frente:</td>
                                       <td colspan='1' width='25%' align='center' style='border: 1px solid black; border-color:#9b9b9b'>$ter_con_fre</td>                            
                                    </tr>     
                                    <tr>
                                       <td colspan='1' width='25%'>Fondo:</td>
                                       <td colspan='1' width='25%' align='center' style='border: 1px solid black; border-color:#9b9b9b'>$ter_fond</td>                            
                                    </tr>  
                                    <tr>
                                       <td colspan='1' width='25%'>Contra fondo:</td>
                                       <td colspan='1' width='25%' align='center' style='border: 1px solid black; border-color:#9b9b9b'>$ter_con_fon</td>                            
                                    </tr>    
                                    <tr>
                                       <td colspan='1' width='25%'>&nbsp</td>
                                       <td colspan='1' width='25%'>&nbsp</td>                            
                                    </tr>                                                                                                                                                                                   
                              </table>
                           </td>
                        </tr>                            
                  </table>        

                  <table width='100%' style='font-family: Arial; font-size: 8pt; border: 1px solid black;'>
                     <tr>
                        <td colspan='2' width='100%' style='font-family: Arial; font-size: 8pt; font-weight:bold; '>4.3.COLINDANCIAS</td>
                     </tr> 
                     <tr>
                        <td colspan='1' width='20%' style='font-family: Arial; font-size: 7pt;'>$limite1:</td>
                        <td colspan='1' width='80%' style='font-family: Arial; font-size: 7pt;'>$Colind1</td>
                     </tr>
                     <tr>
                        <td colspan='1' width='20%' style='font-family: Arial; font-size: 7pt;'>$limite2:</td>
                        <td colspan='1' width='80%' style='font-family: Arial; font-size: 7pt;'>$Colind2</td>
                     </tr>
                     <tr>
                        <td colspan='1' width='20%' style='font-family: Arial; font-size: 7pt;'>$limite3:</td>
                        <td colspan='1' width='80%' style='font-family: Arial; font-size: 7pt;'>$Colind3</td>
                     </tr>                                                                                                                   
                     <tr>
                        <td colspan='1' width='20%' style='font-family: Arial; font-size: 7pt;'>$limite4:</td>
                        <td colspan='1' width='80%' style='font-family: Arial; font-size: 7pt;'>$Colind4</td>
                     </tr>
                  </table>  
                  <table width='100%' border='1' style='font-family: Arial; font-size: 8pt; border: 1px solid black; border-collapse: collapse;'>
                     <tr>
                        <td colspan='4' width='30%' style='font-family: Arial; font-size: 8pt; font-weight:bold;'>4.4.SERVICIOS BASICOS
                           <table width='80%' border='0' >
                              <tr>
                                 <td align='left'  colspan='1'>
                                    <font style='font-family: Arial; font-size: 8pt;'>&nbsp&nbsp&nbsp&nbsp Minimo:</font>
                                 </td>	
                                 <td align='left'  colspan='1' style='border: 1px solid black; vertical-align:top;'>
                                    <font style='font-family: Arial; font-size: 8pt;'> &nbsp SI</font>
                                 </td>										
                              </tr>		
                              <tr>
                                 <td align='left'  colspan='1'>
                                    <font style='font-family: Arial; font-size: 8pt;'>&nbsp&nbsp&nbsp&nbsp Agua</font>
                                 </td>
                                 <td align='left'  colspan='1' style='border: 1px solid black; vertical-align:top;'>
                                    <font style='font-family: Arial; font-size: 8pt;'> &nbsp $ser_agu </font>
                                 </td>														
                              </tr>	
                              <tr style='border: 1px solid black; vertical-align:top;'>
                                 <td align='left'  colspan='1'>
                                    <font style='font-family: Arial; font-size: 8pt;'>&nbsp&nbsp&nbsp&nbsp Electricidad:</font>
                                 </td>
                                 <td align='left'  colspan='1' style='border: 1px solid black; vertical-align:top;'>
                                    <font style='font-family: Arial; font-size: 8pt;'> &nbsp $ser_luz  </font>
                                 </td>												
                              </tr>
                              <tr style='border: 1px solid black; vertical-align:top;'>
                                 <td align='left'  colspan='1'>
                                    <font style='font-family: Arial; font-size: 8pt;'>&nbsp&nbsp&nbsp&nbsp Alcantarillado:</font>
                                 </td>
                                 <td align='left'  colspan='1' style='border: 1px solid black; vertical-align:top;'>
                                    <font style='font-family: Arial; font-size: 8pt;'> &nbsp $ser_alc  </font>
                                 </td>												
                              </tr>
                           </table>
                        </td>       
                     </tr>                                                                                                  
                  </table>  


               </td>
            </tr>   
         </table>
            
         <table width='100%'>
               <tr>
                  <td colspan='1' width='50%'>
                     <table width='100%' style='font-family: Arial; font-size: 8pt;'>
                           <tr>
                              <td colspan='1' width='100%'>Solicitante:</td>                          
                           </tr>
                           <tr>
                              <td colspan='1' width='100%' style='border-top: 1px solid black; border-collapse: collapse;'>Fecha: $fecha2 - $hora </td>                          
                           </tr>                                                                                   
                     </table>
                  </td>
               </tr>                        
         </table>

         <table width='100%' valign='top'>
            <tr height='40' style='font-family: Arial; font-size: 8pt;'>
               <td colspan='1' align='center' rowspan='4' width='15%'>
                  <img src='http://$server/tmp/test.png' alt='imagen' width='72' height='92' border='0'>
               </td>
               <td colspan='2' align='center' valign='bottom' width='40%'>	&nbsp </td>	
               <td colspan='2' align='center' valign='bottom' width='40%'>	&nbsp </td>																	
            </tr>				

            <tr style='font-family: Arial; font-size: 8pt;'>
               <td colspan='2' align='center' valign='bottom' width='40%'>APROBACIÓN</td>
                  <td colspan='2' align='center' valign='bottom' width='50%'>RESPONSABLE</td>																	
            </tr>				
            <tr>
               <td colspan='4' align='center' width='100%' style='font-family: Arial; font-size: 4pt; border-top: 1px solid black; border-collapse: collapse;'>$pie_plano</td>												
            </tr>
            <tr>
               <td colspan='4' align='center' width='100%' style='font-family: Arial; font-size: 6pt;'>
                  EL PRESENTE INFORME SERA DE ENTERA Y TOTAL RESPONSABILIDAD DEL INTERESADO       
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


