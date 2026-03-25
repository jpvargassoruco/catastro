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
#echo "FACTOR DELTA ES: $factor_delta. ";						 
				 } else {
			      # VALOR MAS PEQUEÑO --> MAS ZOOM
			      #$factor_zoom = 2.6; (entrega)
            #$factor_zoom_plano_catastral = 3.0;  #(valor ajustado)				 
			      $factor_zoom = $factor_zoom_plano_catastral; #(definido en igm_config)
				 }
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
################################################################################
#--------------------- SACAR INFORMACION DE LA BASE DE DATOS ------------------#
################################################################################	
#$cod_uv = (int) substr($cod_cat, 0, 2);
#$cod_man = (int)substr($cod_cat, 3, 2);
#$cod_pred = (int) substr($cod_cat, 6, 3);
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

include "igm_planos_leer_datos.php";

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
########################################
#------ COORDENADAS DE VERTICES -------#
########################################
$sql="SELECT AsText(the_geom),npoints(the_geom) FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result = pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$coord_poly = $info['astext'];
$no_de_vertices = $info['npoints']-1;
pg_free_result($result);
include "igm_extract_coordpoly.php";
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
################################################################################
#------------------------------- GENERAR MAPFILE ------------------------------#
################################################################################	

include "igm_generar_mapfile_planocatastral.php";
include "igm_generar_mapfile_planocatastral_ubicacion.php";

################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	

$filename = "C:/apache/htdocs/tmp/pc".$cod_cat.".html";

################################################################################
#------------------------ PREPARAR CONTENIDO PARA IFRAME ----------------------#
################################################################################	

$content = " 
<div align='left'>
<table border='1' width='100%' height='161' style='border:2px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'><font face='Tahoma' size='2'>
   <tr>
      <td rowspan='2' colspan='3'>
			    <table>
					   <tr>
						    <td width='20%'>
                   <img src='http://$server/$folder/css/$nomlog' alt='imagen' width='115' height='101' border='0'>
                </td>
								<td width='80%' align='center'>
								   <font style='font-family: Tahoma; font-size: 9pt;'>GOBIERNO MUNICIPAL DE $municipio</font><br />
									 <font style='font-family: Tahoma; font-size: 3pt;'>&nbsp</font><br />
									 <font style='font-family: Tahoma; font-size: 9pt;'>CONSEJO DEL PLAN REGULADOR</font><br />
									 <font style='font-family: Tahoma; font-size: 3pt;'>&nbsp</font><br />
									 <font style='font-family: Tahoma; font-size: 8pt;'>- DISTRITO $distrito -</font>
								</td> 
						 </tr>
					</table>
			 </td>
       <td align='right' valign='top' colspan='4'>
          $fecha2 - $hora <a href='javascript:print(this.document)'>
          <img border='0' src='http://$server/$folder/graphics/printer.png' width='15' height='15'></a>			 
          <h1>PLANO CATASTRAL &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp </h1>
      </td>
   </tr>
   <tr height='30'>
      <td align='center' colspan='4'>
         <font style='font-family: Tahoma; font-size: 10pt; font-weight:bold;'>CODIGO : $cod_geo/$cod_cat</font>
      </td>		 				
   </tr>		 
   <tr height='20'>
      <td align='left' colspan='7'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp PROPIETARIO(S) : </font>
				 <font style='font-family: Tahoma; font-size: $font_size_prop;'>$propietario</font>
      </td>					 				
   </tr>
   <tr height='20'>
      <td align='left' colspan='2'>
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
      <td align='left' colspan='1'>
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
      <td align='left' width='26%'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp ZONA : $zona</font>
      </td>
      <td align='left' width='12%'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp U.V. : $cod_uv</font>
      </td>			
      <td align='left' width='12%'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp MZ : $cod_man</font>
      </td>			
      <td align='left' width='12%'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp PRED. : $cod_pred</font>
      </td>		
      <td align='left' width='12%'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp BLQ : $cod_blq</font>
      </td>							
      <td align='left' width='12%'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp PISO : $cod_piso</font>
      </td>		
      <td align='left' width='14%'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp APTO : $cod_apto</font>
      </td>								 				
   </tr>	  
   <tr height='20'>
      <td align='left' colspan='1'>
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
      <td align='left' colspan='5'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp DIRECCION : </font> 
				 <font style='font-family: Tahoma; font-size: $font_size_dir;'>$direccion</font>
      </td>	
      <td align='left' colspan='2'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp ESCALA 1 : $escala</font>
      </td>							 				
   </tr>
   <tr>
      <td align='center' height='550px' colspan='7' bgcolor='#FFFFFF'>	 
         <iframe frameborder='0' name='mapserver' src='http://$server/cgi-bin/mapserv.exe?map=c:/apache/catastro/mapa/igm_planocatastral.map&program=http://$server/cgi-bin/mapserv.exe&zoomsize=2&layer=Predios&layer=Manzanos&layer=Puntos&layer=Calles&imgext=$xmin $ymin $xmax $ymax&imgxy=700+550&mapext=&savequery=false&zoomdir=1&mode=map&imgbox=&imgsize=1400+1100&mapsize=1400+1100' id='content' width='700px' height='550px' align='middle' valign='center' scrolling='no' noresize='no' marginwidth='0' marginheight='0'>
				 </iframe>
			</td>
   </tr>	
   <tr>
	    <td align='center' colspan='7'>
			   <table border='1' width='100%' height='161' style='border:1px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'>
				    <tr>
               <td width='40%' align='center'>
                  PLANO DE UBICACION  
               </td>
               <td width='0%' align='center'></td>							 
               <td align='center' colspan='2'>
                  COORDENADAS UTM ZONA 20S, DATUM WGS-84 
               </td>	 
            </tr>	
						<tr>
               <td align='center' bgcolor='#FFFFFF' rowspan='2'>	 
                  <iframe frameborder='0' name='mapserver' src='http://$server/cgi-bin/mapserv.exe?map=c:/apache/catastro/mapa/igm_planocatastral_ubicacion.map&program=http://$server/cgi-bin/mapserv.exe&zoomsize=2&layer=Predios&layer=Manzanos&layer=Calles&imgext=$xmin1 $ymin1 $xmax1 $ymax1&imgxy=750+625&mapext=&savequery=false&zoomdir=1&mode=map&imgbox=&imgsize=1500+1250&mapsize=750+625' id='content' width='300px' height='250px' align='middle' valign='center' scrolling='no' noresize='no' marginwidth='0' marginheight='0'>
                  </iframe>
			         </td>
               <td align='center' height='200px' bgcolor='#FFFFFF'></td>								 
               <td valign='top' colspan='2'>
			            <table border='1' width='100%' height='100%' style='border-collapse:collapse; font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'>
				             <tr height='10%'>
                        <td width='10%' align='center'>
                           ID
                        </td>
                        <td width='20%' align='center'>
												   ESTE 
                         </td>							 
                        <td width='20%' align='center'>
												   NORTE 
                        </td>
                        <td width='10%' align='center'>
                           ID 
                        </td>
                        <td width='20%' align='center'>
												   ESTE 
                         </td>							 
                        <td width='20%' align='center'>
												   NORTE 
                        </td>													 
                     </tr>
				             <tr height='90%'>
                        <td align='center' valign='top'>";
$max_filas = 13;
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
                        <td align='left' valign='top'>";
$coordenadas_x = "&nbsp $point_x[0]";
$i = 1;
while (($i < $no_de_vertices) AND ($i < $max_filas)) {
   $coordenadas_x = $coordenadas_x."<br /> &nbsp $point_x[$i]";
	 $i++;
}
$content = $content."
                           $coordenadas_x														
                         </td>							 
                        <td align='left' valign='top'>";
$coordenadas_y = "&nbsp $point_y[0]";
$i = 1;
while (($i < $no_de_vertices) AND ($i < $max_filas)) {
   $coordenadas_y = $coordenadas_y."<br /> &nbsp $point_y[$i]";
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
                        <td align='left' valign='top'>";
if ($no_de_vertices > $max_filas) {												
   $coordenadas_x2 = "&nbsp $point_x[$max_filas]";
   $i = $max_filas+1;
   while (($i < $no_de_vertices) AND ($i < $max_filas2)) {
      $coordenadas_x2 = $coordenadas_x2."<br /> &nbsp $point_x[$i]";
	    $i++;
   }
} else $coordenadas_x2 = "&nbsp";
$content = $content."
                           $coordenadas_x2													
                         </td>							 
                        <td align='left' valign='top'>";
if ($no_de_vertices > $max_filas) {												
   $coordenadas_y2 = "&nbsp $point_y[$max_filas]";
   $i = $max_filas+1;
   while (($i < $no_de_vertices) AND ($i < $max_filas2)) {
      $coordenadas_y2 = $coordenadas_y2."<br /> &nbsp $point_y[$i]";
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
						<tr>
               <td height='50px' bgcolor='#FFFFFF'></td>								 
               <td align='center' valign='top' width='30%' rowspan='3'>
                  <font style='font-family: Tahoma; font-size: 9pt;'>APROBACION O.T.P.R.</font> 
               </td>
               <td align='left' valign='top' width='30%' rowspan='3'>
                  <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp&nbsp&nbsp H.A.M. CONCEPCION</font><br /><br /><br />
                  <img border='0' src='http://$server/$folder/graphics/marca_de_agua.png' width='15' height='62'>
               </td>			 							 
				    </tr> 
						<tr>              							 
               <td align='left'>
                  <font style='font-family: Tahoma; font-size: 7pt;'> &nbsp OBSERVACIONES:<br /> 
									 &nbsp Toda construcción debería contar con la previa autorización de la<br />
									 &nbsp dirección técnica del Plan Regulador.</font>
               </td>
							 <td align='center' height='50px' bgcolor='#FFFFFF'></td>	
				    </tr>
						<tr>              							 
               <td style='font-family: Tahoma; font-size: 5pt'>
                  &nbsp NOTA: $nota_plano_catastral 
               </td>
							 <td align='center' height='25px' bgcolor='#FFFFFF'></td>	
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