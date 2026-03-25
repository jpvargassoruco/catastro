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
			      $factor_zoom = $factor_zoom_plano_catastral; #(definido en siicat_config)
				 }
				 # FACTOR ZOOM PARA PLANO DE UBICACION
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
#0.63 valor tomado de la hoja de imprecion
$extension_real = 2*$delta*0.63;
$extension_en_papel = 0.185;
$escala = $extension_real/$extension_en_papel;
$escala = ROUND($escala/100,0)*100;
# FUNCION GET_ZONA
$ben_zona = get_zona ($id_inmu);	
if ($ben_zona == "0") {			
   $ben_zona = "-";
}

$barrio = get_barrio ($id_inmu);	
if ($barrio == "0") {			
   $barrio = "-";
}	
### LEER DATOS DE PROPIETARIO DE INFO_INMU Y DATOS DEL PREDIO DE INFO_PREDIO

include "siicat_planos_leer_datos.php";
################################################################################
#-------------------- NOMBRE DE LOS 5 PROPIETARIO SIN CI ----------------------#
################################################################################
if ($prop1 == "-") {
    $propietario = "S/N";
 } else {
    $propietario = $prop1;
    $font_size_prop = "12pt";
    if ($prop2 != "-") {
        $propietario = $propietario." , ".$prop2;
        $font_size_prop = "11pt";	
        if ($prop3 != "-") {
            $propietario = $propietario." , ".$prop3;
            $font_size_prop = "10pt";
            if ($prop4 != "-") {
                $propietario = $propietario." , ".$prop4;
                $font_size_prop = "10pt";
                if ($prop5 != "-") {
                    $propietario = $propietario." , ".$prop5;
                    $font_size_prop = "10pt";
                }                  
            }            
        }

    }

 }

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
$area= ROUND($value['area'],2); 
pg_free_result($result); 
if ($adq_sdoc < $area) {			
    $dif_sup = ROUND($area - $adq_sdoc,2);
} else {    
    $dif_sup = ROUND($adq_sdoc - $area,2);
}	


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
	######################################## $area_edif[$i]     AND edi_piso = '$edi_piso[$j]'
	$sql="SELECT area(the_geom) FROM edificaciones WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND edi_num = '$edi_num[$j]'";
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
   $point_x[$i] = number_format($point_x[$i] ,3,".",""); 	
   $point_y[$i] = number_format($point_y[$i] ,3,".","");
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
				 #SOLO MOSTRAR CODIGO EN EL PLANO
				 $col_tit[$j] = "Lote ".$col_cod[$j];
				 
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
				# $label_temp = "U.V. ".$cod_uv_temp."@MZ. ".$cod_man_temp;
                 $label_temp = "MZ. ".$cod_man_temp;
				 pg_query("INSERT INTO temp_poly (user_id, cod_uv, cod_man, numero, label, the_geom) VALUES ('$user_id','$cod_uv_temp','$cod_man_temp','$number_temp','$label_temp','$the_geom_temp')");
         $i = -1;
			}
      $i++;
   }
	 $i = 0;
}
pg_free_result($result);

################################################################################
#----------------------- PREPARAR CODIGOS CON CEROS----------------------------#
################################################################################
$cod_uvCero = str_pad($cod_uv, 2, "0", STR_PAD_LEFT);
$cod_manCero = str_pad($cod_man, 4, "0", STR_PAD_LEFT);
$cod_predCero = str_pad($cod_pred, 3, "0", STR_PAD_LEFT);
################################################################################
#------------------------------- GENERAR MAPFILE ------------------------------#
################################################################################	

include "siicat_generar_mapfile_planocatastral.php";
include "siicat_generar_mapfile_planocatastral_ubicacion.php";

################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	

$filename = "C:/apache/htdocs/tmp/pc".$cod_cat.".html";

################################################################################
#------------------------ PREPARAR CONTENIDO PARA IFRAME ----------------------#
################################################################################



$content = " 
<div align='left'>
<table border='0' width='100%' style='border:1px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 7pt' bgcolor='#FFFFFF'>
    <tr>
        <td valign='top' colspan='7'>
            $fecha2 - $hora <a href='javascript:print(this.document)'>
            <img border='0' src='http://$server/$folder/graphics/printer.png' width='15' height='15'></a>			 
            <h1 align='center' >PLANO DE LOTE </h1>
        </td>
    </tr>

    <tr>
        <td align='center' height='550px' colspan='7' bgcolor='#FFFFFF'>	 
            <iframe frameborder='0' name='mapserver' src='http://$server/cgi-bin/mapserv.exe?map=c:/apache/siicat/mapa/siicat_planocatastral.map&program=http://$server/cgi-bin/mapserv.exe&zoomsize=2&layer=Predios&layer=Manzanos&layer=Puntos&layer=Calles&imgext=$xmin $ymin $xmax $ymax&imgxy=700+550&mapext=&savequery=false&zoomdir=1&mode=map&imgbox=&imgsize=1400+1100&mapsize=1400+1100' 
                id='content' 
                width='700px' 
                height='550px' 
                align='middle' 
                valign='center' 
                scrolling='no' 
                noresize='no' 
                marginwidth='0' 
                marginheight='0'>
            </iframe>
        </td>
    </tr>           
    <tr>
        <td width='40%' align='center' > 
            <table border='1' style='border-collapse:collapse; font-family: Century Gothic; font-size: 8pt' bgcolor='#FFFFFF'>
                <tr height='10' style='font-family:Century Gothic; font-size: 8pt; font-weight: bold'>
                    <td align='center'>Bl.</td>
                    <td align='center'>NIVEL</td>
                    <td align='center'>TIPO CONST.</td>
                    <td align='center'>ANO CONST.</td>							 
                    <td align='center'>SUP. CONST.</td>					 							 						 
                </tr>";

                $i = 0;
                if ($no_de_edificaciones > 5) {
                    $fila_activa = true;
                    $no_de_edif_real = $no_de_edificaciones;
                    $no_de_edificaciones = 5;
                } else $fila_activa = false;
                $filas_vacias = 5 - $no_de_edificaciones;
                while ($i < $no_de_edificaciones) {
                $content = $content."						
                <tr height='10'>
                    <td align='center'>$edi_num[$i]</td>
                    <td align='center'>$edi_piso[$i]</td>
                    <td align='center'>$edi_tipo[$i]</td>
                    <td align='center'> $edi_ano[$i]</td>							 
                    <td align='center'>$area_edif[$i]</td>	
				 							 						 
            </tr>";	
            $i++;
            } 
            $i = 0;
            while ($i < $filas_vacias) {
            $content = $content."						
                <tr height='14'>
                <td align='center'>-</td>
                <td align='center'>-</td>
                <td align='center'>-</td>
                <td align='center'>-</td>							 
                <td align='center'>-</td>	
				 							 						 
            </tr>";	
            $i++;
            }

            $content = $content."$texto

			 							 						 
            </tr>
            </table>
        </td>

        <td width='10%' align='center' >            
            <table border='1' width='80%' style='border-collapse:collapse; font-family: Century Gothic; font-size: 8pt' bgcolor='#FFFFFF'>
                <tr>
                    <td align='left' valign='top' colspan='2'>AGUA</td>
                    <td align='center' valign='top' colspan='1'>SI</td>
                </tr>
                <tr>
                    <td align='left' valign='top' colspan='2'>LUZ</td>
                    <td align='center' valign='top' colspan='1'>SI</td>
                </tr> 
                <tr>
                    <td align='left' valign='top' colspan='2'>ALCANT.</td>
                    <td align='center' valign='top' colspan='1'>SI</td>
                </tr>         
            </table>
        </td>

        <td width='50%' >            
            <table border='1' width='100%' style='border:1px solid black; border-collapse:collapse; font-family: Century Gothic; font-size: 8pt' bgcolor='#FFFFFF'>
                <tr>
                    <td align='center'>$NomSisCoo</td>	 
                </tr>	
                <tr>							 
                    <td valign='top'>
                        <table border='1' width='100%' style='border-collapse:collapse; font-size: 8pt' bgcolor='#FFFFFF'>
                            <tr>
                                <td width='7%' align='center'>ID</td>
                                <td width='21%' align='center'>ESTE</td>							 
                                <td width='22%' align='center'>NORTE</td>
                                <td width='7%' align='center'>ID</td>
                                <td width='21%' align='center'>ESTE</td>							 
                                <td width='22%' align='center'>NORTE</td>													 
                            </tr>
                            <tr>
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
        </td>                      
    </tr>
    <tr>
        <td align='center' valign='top' colspan='1'>&nbsp </td>
    </tr>    
    <tr>
        <td width='40%' align='left'>
            <font style='font-family: Tahoma; font-size: 7pt; font-weight: bold;'>URBANIZACION:</font>                           
            <font style='font-family: Tahoma; font-size: 7pt;'>$depart</font>  
        </td>
        <td width='20%' align='center'>
            <font style='font-family: Tahoma; font-size: 7pt; font-weight: bold;'>R.T.A.:</font>                           
            <font style='font-family: Tahoma; font-size: 7pt;'>$depart</font>
        </td>
        <td width='40%' align='right'>
            <font style='font-family: Tahoma; font-size: 7pt; font-weight: bold;'>BARRIO:</font>                           
            <font style='font-family: Tahoma; font-size: 7pt;'>$depart</font>    
        </td>
    </tr>

</table>

<table border='0' style='font-family: Tahoma; font-size: 2pt'>
    <tr>
        <td align='center' valign='top' colspan='1'>&nbsp </td>
    </tr>
</table>


<table width='100%' height='100' style='border-radius: 25px; border:2px solid black; font-family: Tahoma; font-size: 8pt'>

    <tr height='40' style='border-top-left-radius: 20px; border-top-right-radius: 20px; background-color:  #a9cce3 ' >
        <td align='left' colspan='7'>
            <table width='100%'  style='background-color:  #a9cce3 '>
                <tr>
                    <td align='left' width='20%'>
                    <font style='font-family: Tahoma; font-size: 12pt;'>&nbsp PROPIETARIO (S):</font>
                    </td>
                    <td align='left' width='75%'>
                    <font style='font-family: Tahoma; font-size: $font_size_prop;'>$propietario </font>
                    </td>                    
                </tr>
            </table>
        </td>    
    </tr>

    <tr>
        <td width='100%'>
            <table border='1' width='100%' style='border:1px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'>	
                <tr valing='top'>
                    <td  valign='top' width='45%' height='100px'><font style='font-family: Century Gothic; font-size: 9pt; font-weight: bold;'>&nbsp SELLO DEPARTAMENTO TECNICO</font></td>

                    <td rowspan='2' align='center'>
                        <table width='100%' style='border-collapse:collapse; font-size: 10pt' bgcolor='#FFFFFF'>
                            <tr>
                                <td><font style='font-family: Century Gothic; font-size: 9pt; font-weight: bold;'>CROQUIS DE UBICACION:</font>
                                </td>
                            </tr>
                            <tr>
                                <td align='center'>          
                                    <iframe 
                                        frameborder='0' 
                                        name='mapserver' 
                                        src='http://$server/cgi-bin/mapserv.exe?map=c:/apache/siicat/mapa/siicat_planocatastral_ubicacion.map&program=http://$server/cgi-bin/mapserv.exe&zoomsize=2&layer=Predios&layer=Manzanos&layer=Calles&imgext=$xmin1 $ymin1 $xmax1 $ymax1&imgxy=750+650&mapext=&savequery=false&zoomdir=1&mode=map&imgbox=&imgsize=1500+1200&mapsize=830+550' 
                                        id='content' 
                                        width='350px' height='230px' align='middle' valign='center' 
                                        scrolling='no' noresize='no' marginwidth='0' marginheight='0'>
                                    </iframe>
                                <td>
                            <tr>
                        </table>
                    </td>                       
                </tr>

                <tr valign='top'>
                    <td>
                        <table width='100%' style='border-collapse:collapse; font-size: 10pt' bgcolor='#FFFFFF'>
                            <tr>
                                <td colspas='3'><font style='font-family: Century Gothic; font-size: 9pt; font-weight: bold;'>&nbsp RELACION DE SUPERFICIES:</font></td>
                            </tr>
                            <tr>
                                <td colspas='3'><font style='font-family: Century Gothic; font-size: 4pt; font-weight: bold;'>&nbsp </font></td>
                            </tr>
                            <tr>
                                <td>              <font style='font-family: Century Gothic; font-size:  9pt;'>&nbsp SUP. SEGUN ESCRITURA:</font></td>
                                <td align='right'><font style='font-family: Century Gothic; font-size: 10pt;'>&nbsp $adq_sdoc m2</font></td>
                                <td>&nbsp </td>
                            </tr>
                            <tr>
                                <td>              <font style='font-family: Century Gothic; font-size: 9pt;'>&nbsp SUP. SEGUN MENSURA:</font></td>
                                <td align='right'><font style='font-family: Century Gothic; font-size: 10pt;'>&nbsp $area m2</font></td>
                                <td>&nbsp </td>
                            </tr>
                            <tr>
                                <td>              <font style='font-family: Century Gothic; font-size:  9pt;'>&nbsp SUP. DIFERENCIA:</font></td>
                                <td align='right'><font style='font-family: Century Gothic; font-size: 10pt;'>&nbsp $dif_sup m2</font></td>
                                <td>&nbsp </td>
                            </tr>
                            <tr>
                                <td>              <font style='font-family: Century Gothic; font-size:  9pt;'>&nbsp SUP. CONSTRUIDA:</font></td>
                                <td align='right'><font style='font-family: Century Gothic; font-size: 10pt;'>&nbsp $edi_area m2</font></td>
                                <td>&nbsp </td>
                            </tr>                              
                            <tr>
                                <td><font style='font-family: Century Gothic; font-size: 9pt; font-weight: bold;'>&nbsp SUP. TOTAL UTIL:</font></td>
                                <td align='right'><font style='font-family: Century Gothic; font-size: 9pt; font-weight: bold;'>&nbsp $edi_area m2</font></td>
                                <td>&nbsp </td>
                            </tr>  
                        </table>                   
                    </td>                                                    
                </tr>            
            </table>		
        </td>
    </tr>
    
    <tr>
        <td width='100%'>
            <table border='1' width='100%' style='border:1px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'>	
                <tr>
                    <td valign='top' width='45%' rowspan='6'><font style='font-family: Century Gothic; font-size: 8pt; font-weight: bold;'>&nbsp DIRECTOR DE URBANISMO Y CATASTRO:</font></td>
                </tr>
                <tr>
                    <td><font style='font-family: Century Gothic; font-size: 8pt;'>&nbsp DEPARTAMENTO:</font></td>
                    <td><font style='font-family: Century Gothic; font-size: 7pt;'>&nbsp $depart</font></td>
                    <td><font style='font-family: Century Gothic; font-size: 8pt;'>&nbsp DISTRITO :</font></td>
                    <td><font style='font-family: Century Gothic; font-size: 8pt;'>&nbsp $cod_dis</font></td>
                </tr>
                <tr>
                    <td><font style='font-family: Century Gothic; font-size: 8pt;'>&nbsp PROVINCIA:</font></td>
                    <td><font style='font-family: Century Gothic; font-size: 7pt;'>&nbsp $provincia</font></td>
                    <td><font style='font-family: Century Gothic; font-size: 8pt;'>&nbsp DISTRITO CAT.:</font></td>
                    <td><font style='font-family: Century Gothic; font-size: 8pt;'>&nbsp $cod_uvCero</font></td>
                </tr>
                <tr>
                    <td><font style='font-family: Century Gothic; font-size: 8pt;'>&nbsp MUNICIPIO:</font></td>
                    <td><font style='font-family: Century Gothic; font-size: 7pt;'>&nbsp $municipio</font></td>
                    <td><font style='font-family: Century Gothic; font-size: 8pt;'>&nbsp MANZANO No.: </font></td>
                    <td><font style='font-family: Century Gothic; font-size: 8pt;'>&nbsp $cod_manCero</font></td>
                </tr>                                                            
                <tr>
                    <td><font style='font-family: Century Gothic; font-size: 8pt;'>&nbsp LOCALIDAD:</font></td>   
                    <td><font style='font-family: Century Gothic; font-size: 7pt;'>&nbsp $distrito</font></td> 
                    <td><font style='font-family: Century Gothic; font-size: 8pt;'>&nbsp LOTE No.: </font></td>   
                    <td><font style='font-family: Century Gothic; font-size: 8pt;'>&nbsp $cod_predCero</font></td>                                             
                </tr>
                <tr>
                    <td ><font style='font-family: Century Gothic; font-size: 10pt; font-weight: bold;'>&nbspESCALA:</font></td>   
                    <td ><font style='font-family: Century Gothic; font-size: 10pt;'>&nbsp 1 : $escala</font></td>  
                    <td ><font style='font-family: Century Gothic; font-size: 10pt; font-weight: bold;'>&nbspFECHA:</font></td>  
                    <td ><font style='font-family: Century Gothic; font-size: 10pt;'>&nbsp $fecha2 </font></td>                                               
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