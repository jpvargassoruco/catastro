<?php

##### CHEQUEAR POR COLINDANTES EN TABLA #####
#$sql="SELECT * FROM colindantes WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$sql="SELECT * FROM colindantes WHERE id_predio = '$id_predio'";
$check_por_col = pg_num_rows(pg_query($sql));
if ($check_por_col > 0 ) {
#echo " YA SE INGRESO LOS COLINDANTES<br \>";	
} else {
	 ##### EXTRAER VERTICES DEL PREDIO #####
   $sql="SELECT AsText(the_geom),npoints(the_geom) FROM predios WHERE id_predio = '$id_predio'";
   $result_vert = pg_query($sql);
   $info = pg_fetch_array($result_vert, null, PGSQL_ASSOC);
   $coord_poly = $info['astext'];
   $no_de_vertices = $info['npoints']-1;
   pg_free_result($result_vert);
   include "siicat_extract_coordpoly.php";
	 ##### DEFINIR CENTROIDE DEL PREDIO #####	
   $result_cent=pg_query("SELECT x(centroid(the_geom)),y(centroid(the_geom)) FROM predios WHERE id_predio = '$id_predio'");
   $cstr = pg_fetch_array($result_cent, null, PGSQL_ASSOC);
   $centroid_x = $cstr['x'];
   $centroid_y = $cstr['y'];
   pg_free_result($result_cent);	 
#echo "VERTICES: $no_de_vertices<br \>";	
   ##### PASAR POR CADA VERTICE #####	
   $iii = 1;
	 $jjj = 0;			
   while ($iii <= $no_de_vertices) {
      ##### DEFINIR CENTRO DE LA LINEA ENTRE VERTICES #####	 
      $porc_dist = 0.5;
			if ($iii == $no_de_vertices) {
			   $p0x = $point_x[$iii-1]; $p1x = $point_x[0];
			   $p0y = $point_y[$iii-1]; $p1y = $point_y[0];			
			} else {
			   $p0x = $point_x[$iii-1]; $p1x = $point_x[$iii];
			   $p0y = $point_y[$iii-1]; $p1y = $point_y[$iii]; 
			}
      $checkpoint_x = get_point_x ($p0x,$p0y,$p1x,$p1y,$porc_dist);		 
			$checkpoint_y = get_point_y ($p0x,$p0y,$p1x,$p1y,$porc_dist);
#echo "CHECKPOINT en Vertice $iii: $checkpoint_x $checkpoint_y<br />";
      $distancia_hacia_colindante = 0.25;
      $sql="SELECT cod_uv, cod_man, cod_pred FROM predios WHERE st_dwithin (ST_GeomFromText('POINT($checkpoint_x $checkpoint_y)'),the_geom,$distancia_hacia_colindante)
            AND activo = '1' AND NOT id_predio = '$id_predio'";	
      $numero_de_colindantes = pg_num_rows(pg_query($sql));
#echo "NO DE COLINDANTES: $numero_de_colindantes<br />";	
			if ($numero_de_colindantes > 0) {
         $result_colcod = pg_query($sql);
			   $col_codd = pg_fetch_array($result_colcod, null, PGSQL_ASSOC);
				#$col_nom_temp[$jjj] = $col_codd['cod_cat'];
				 $col_nom_temp_uv[$jjj] = $col_codd['cod_uv'];
				 $col_nom_temp_man[$jjj] = $col_codd['cod_man'];
				 $col_nom_temp_pred[$jjj] = $col_codd['cod_pred'];
				 $col_nom_temp[$jjj] = get_codcat ($col_nom_temp_uv[$jjj],$col_nom_temp_man[$jjj],$col_nom_temp_pred[$jjj],0,0,0);
				 pg_free_result($result_colcod);
	       ##### DEFINIR CENTROIDE DEL COLINDANTE #####	
         $result_cent=pg_query("SELECT x(centroid(the_geom)),y(centroid(the_geom)) FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$col_nom_temp_uv[$jjj]' AND cod_man = '$col_nom_temp_man[$jjj]' AND cod_pred = '$col_nom_temp_pred[$jjj]'");
         $cstr = pg_fetch_array($result_cent, null, PGSQL_ASSOC);
         $centroid_col_x = $cstr['x'];
         $centroid_col_y = $cstr['y'];
         pg_free_result($result_cent);					 
				 $col_nom_temp[$jjj] = "PREDIO ".$col_nom_temp[$jjj];
	/*			 ##### EXTRAER NOMBRE DEL COLINDANTE #####
				 $sql="SELECT tit_1nom1, tit_1pat, tit_1mat FROM info_predio WHERE cod_cat = '$col_nom_temp[$jjj]'";
         $result2 = pg_query($sql);
				 $col_nom = pg_fetch_array($result2, null, PGSQL_ASSOC);
				 #$tit_1nom1_col = utf8_decode ($col_nom['tit_1nom1']);
				 $tit_1nom1_col = textconvert(strtoupper (ucase($col_nom['tit_1nom1'])));				 
				 $tit_1pat_col = textconvert(strtoupper (ucase($col_nom['tit_1pat'])));
				 #$tit_1mat_col = utf8_decode ($col_nom['tit_1mat']);
				 $tit_1mat_col = textconvert(strtoupper (ucase($col_nom['tit_1mat'])));				 
				 if ((trim($tit_1nom1_col	== "")) AND (trim($tit_1pat_col	== "")) AND (trim($tit_1nom1_col	== ""))) {
				    $col_nom_temp[$jjj] = $col_nom_temp[$jjj]." S/N";	 
				 } else {
				    $col_nom_temp[$jjj] = $col_nom_temp[$jjj]." ".$tit_1nom1_col." ".$tit_1pat_col." ".$tit_1mat_col;
				 }
				# $col_tit[$j]
				 pg_free_result($result2);				 */				 
#echo "CODIGO DE COLINDANTE Y NOMBRE: $col_nom_temp[$jjj]<br />";			   			 			
			} else {
				 ##### NO HAY COLINDANTE, BUSCANDO UNA CALLE O UN CANAL #####
	       $dist_calle = 6;
				 $calle_encontrada = false;
				 while ((!$calle_encontrada) AND ($dist_calle < 25)) {
            $sql = "SELECT DISTINCT nombre,oid FROM calles WHERE st_dwithin (ST_GeomFromText('POINT($checkpoint_x $checkpoint_y)'),the_geom,$dist_calle)";
            $check_calle= pg_num_rows(pg_query($sql));
	          if ($check_calle == 0) {
						   $dist_calle++; 
				    }	else {	
						   $col_from = "calles";			
						   $calle_encontrada = true;
               $result_calle = pg_query($sql);
			         $calle_temp = pg_fetch_array($result_calle, null, PGSQL_ASSOC);
				       $col_nom_temp[$jjj] = utf8_decode ($calle_temp['nombre']);
							 $oid_colin = utf8_decode ($calle_temp['oid']);
				       pg_free_result($result_calle);
							 $ttest = $col_nom_temp[$jjj];							 
#echo "$ttest ENCONTRADA A $dist_calle m<br />";
               if ($col_nom_temp[$jjj] == "N.N.") {
							    $col_nom_temp[$jjj] = "CALLE S/N";
							 }	
							 						 	 
						}
				 } # END_OF_WHILE
				 ### CHEQUEAR POR CANAL ###
				 if (!$calle_encontrada) {
				    $dist_canal = 20;
            $sql = "SELECT descrip, oid FROM objetos_linea WHERE st_dwithin (ST_GeomFromText('POINT($checkpoint_x $checkpoint_y)'),the_geom,$dist_canal) AND id='25' LIMIT 1";
#echo "$sql<br />";            
						$check_canal= pg_num_rows(pg_query($sql));
	          if ($check_canal > 0) {
						   $col_from = "objetos_linea";
						   $col_nom_temp[$jjj] = "CANAL";
               $result_canal = pg_query($sql);
			         $canal_temp = pg_fetch_array($result_canal, null, PGSQL_ASSOC);
							 $oid_colin = utf8_decode ($canal_temp['oid']);
				       pg_free_result($result_canal);							  
				    } else {
						   $col_nom_temp[$jjj] = "N.N.";
					  }					 
				 }	
				 if (($calle_encontrada) OR ($check_canal > 0 )) {
				    ### VER CUANTOS VERTICES TIENE LA LINEA/EL CANAL ###
				    $sql="SELECT ST_NumPoints(the_geom) AS number FROM $col_from WHERE oid = '$oid_colin'";
            $result_p=pg_query($sql);				 
            $res_p1 = pg_fetch_array($result_p, null, PGSQL_ASSOC);
            $numero_de_vertices = $res_p1['number'];			 									 
				    pg_free_result($result_p);				 
#echo "NUMERO DE VERTICES EN LINEA: $numero_de_vertices<br />"; 
				    ### SACAR LAS VERTICES Y ESCRIBIRLOS EN UN STRING ###
				    $i = 1;
				    while ($i <= $numero_de_vertices) {
				       $sql="SELECT x(AsText(ST_PointN(the_geom ,$i))), y(AsText(ST_PointN(the_geom ,$i))) from $col_from where oid = '$oid_colin'";
               $result_p=pg_query($sql);				 
               $res_p1 = pg_fetch_array($result_p, null, PGSQL_ASSOC);
               $vert_x = $res_p1['x'];
						   $vert_y = $res_p1['y'];			 									 
				       pg_free_result($result_p);
						   if ($i == 1) {
						      $coordstring = $vert_x." ".$vert_y;
						   } else {
						      $coordstring = $coordstring.",".$vert_x." ".$vert_y;						
						   }					
				       $i++;
				    }
#echo "COORD-STRING DE LA CALLE: $coordstring<br />"; 				 
				    ### ENCONTRAR PUNTO ENCIMA DE LA CALLE MAS CERCA AL CHECKPOINT ###
				    $sql="SELECT x(ST_Line_Interpolate_Point(foo.the_line, ST_Line_Locate_Point(foo.the_line, ST_GeomFromText('POINT($checkpoint_x $checkpoint_y)')))) 
	                FROM (SELECT ST_GeomFromText('LINESTRING($coordstring)') As the_line) As foo;";				 
#echo "$sql<br />"; 
            $result_p=pg_query($sql);				 
            $res_p1 = pg_fetch_array($result_p, null, PGSQL_ASSOC);
            $centroid_col_x = ROUND ($res_p1['x']*1000,0)/1000;			 									 
				    pg_free_result($result_p);
				    $sql="SELECT y(ST_Line_Interpolate_Point(foo.the_line, ST_Line_Locate_Point(foo.the_line, ST_GeomFromText('POINT($checkpoint_x $checkpoint_y)')))) 
	                FROM (SELECT ST_GeomFromText('LINESTRING($coordstring)') As the_line) As foo;";				 
#echo "$sql<br />"; 
            $result_p=pg_query($sql);				 
            $res_p1 = pg_fetch_array($result_p, null, PGSQL_ASSOC);
            $centroid_col_y = ROUND ($res_p1['y']*1000,0)/1000;						 									 
				    pg_free_result($result_p);
				 } else {
				    ### NO SE SABE QUE ES EL COLINDANTE ###
						$centroid_col_x = $checkpoint_x;
						$centroid_col_y = $checkpoint_y;
						 
				 } #END_OF_ELSE --> if (($calle_encontrada) OR ($check_canal > 0 )) 		
			} #END_OF_ELSE --> if ($numero_de_colindantes > 0)
				
#echo "PUNTO INTERPOLADO EN LA LINEA: $centroid_col_x $centroid_col_y<br />"; 			
      ##### DEFINIR LONGITUD DE LA LINEA #####
			$col_dist_temp[$jjj] = ROUND (get_linelen ($p0x, $p0y, $p1x, $p1y),2);
#echo "COLINDANTE: $col_nom_temp[$jjj], LONGITUD: $col_dist_temp[$jjj]<br />";										
# get_position8($point_x, $point_y, $centroid_x, $centroid_y, $xmin, $xmax, $ymin, $ymax)
     
		  ##### DEFINIR POSICION DEL CENTROIDE DEL COLINDANTE/DE LA CALLE ENCONTRADO AL RESPETO DEL CENTROIDE DEL PREDIO #####						
			$punto_pos_cent = get_position4($centroid_col_x, $centroid_col_y, $centroid_x, $centroid_y);
#echo "POSICION RELATIVO CON EL CENTROIDE: $punto_pos_cent<br />"; 
     	 
		  ##### DEFINIR POSICION DEL CENTRIODE DEL COLINDANTE/DE LA CALLE ENCONTRADO AL RESPETO DEL CHECKPOINT #####						
			$punto_pos_gr[$jjj] = get_position4($centroid_col_x, $centroid_col_y, $checkpoint_x, $checkpoint_y);
#echo "POSICION RELATIVO CON EL CHECKPOINT: $punto_pos_gr[$jjj]<br />";

    #  if (($punto_pos_gr2[$jjj] == 'NE') AND ($punto_pos_gr1[$jjj] == 'NO') {
			#   $punto_pos_gr[$jjj] = 'N';
		#	 }

      ##### DEFINIR POSICION FINA DE ESE PUNTO #####	
			$punto_pos_centroide_ns = substr ($punto_pos_cent,0,1);
			$punto_pos_centroide_eo = substr ($punto_pos_cent,1,1);
			$punto_pos_ns = substr ($punto_pos_gr[$jjj],0,1);
			$punto_pos_eo = substr ($punto_pos_gr[$jjj],1,1);
			$delta_xxx = sqrt(($checkpoint_x - $centroid_x)*($checkpoint_x - $centroid_x));
			$delta_yyy = sqrt(($checkpoint_y - $centroid_y)*($checkpoint_y - $centroid_y));			
			if (($delta_xxx > $delta_yyy) AND ($punto_pos_centroide_eo == $punto_pos_eo))  {
			   $punto_pos_fino[$jjj] = $punto_pos_eo;
			} else {
			   $punto_pos_fino[$jjj] = $punto_pos_ns;			
			}
#echo "POSICION FINA: $punto_pos_fino[$jjj]<br />";			
      ##### DEFINIR INCLINACION DE LA LINEA #####			
		  $delta_x  = SQRT (($p0x-$p1x)*($p0x-$p1x));
			$delta_y  = SQRT (($p0y-$p1y)*($p0y-$p1y));	
#echo "DELTA_X: $delta_x, DELTA_Y: $delta_y<br />";						
			if ($delta_x > $delta_y) {
			   $porc_inc = ($delta_y/$delta_x)*100;
			   $inclinacion = "horizontal - $porc_inc";
				 if ((($punto_pos_fino[$jjj] == "E") OR ($punto_pos_fino[$jjj] == "O")) AND ($porc_inc < 75)) {
	          $col_pos[$jjj] = $punto_pos_ns; 
				 } else {
	          $col_pos[$jjj] = $punto_pos_fino[$jjj];
				 }
      } else { 
			   $porc_inc = ($delta_x/$delta_y)*100;
			   $inclinacion = "vertical - $porc_inc";
				 if ((($punto_pos_fino[$jjj] == "N") OR ($punto_pos_fino[$jjj] == "S")) AND ($porc_inc < 75)) {
	          $col_pos[$jjj] = $punto_pos_eo; 
				 } else {
	          $col_pos[$jjj] = $punto_pos_fino[$jjj];
				 }		 			 
			}  
#echo "INCLINACION: $inclinacion, NUEVA POSICION: $col_pos[$jjj]<br />";			
      ##### SI EL NOMBRE ES EL MISMO CHEQUEAR POSICION #####			
			if ($jjj > 0) {
			   if (($col_nom_temp[$jjj] == $col_nom_temp[$jjj-1]) AND ($col_nom_temp[$jjj] != "CALLE S/N") AND ($col_nom_temp[$jjj] != "N.N.")) {
				    ##### COMPARAR LA POSICION GRUESA #####
						$text = $punto_pos_gr[$jjj-1];
#echo "MISMO NOMBRE --> POSICION GRUESA: $punto_pos_gr[$jjj], $text<br />";						
						if ((($punto_pos_gr[$jjj] == "NO") AND ($punto_pos_gr[$jjj-1] == "NE")) OR (($punto_pos_gr[$jjj] == "NE") AND ($punto_pos_gr[$jjj-1] == "NO"))) {
						   $col_pos[$jjj] = $col_pos[$jjj-1] = "N";
						} elseif ((($punto_pos_gr[$jjj] == "NE") AND ($punto_pos_gr[$jjj-1] == "SE")) OR (($punto_pos_gr[$jjj] == "SE") AND ($punto_pos_gr[$jjj-1] == "NE"))) {
						   $col_pos[$jjj] = $col_pos[$jjj-1] = "E";
						} elseif ((($punto_pos_gr[$jjj] == "SE") AND ($punto_pos_gr[$jjj-1] == "SO")) OR (($punto_pos_gr[$jjj] == "SO") AND ($punto_pos_gr[$jjj-1] == "SE"))) {
						   $col_pos[$jjj] = $col_pos[$jjj-1] = "S";
						} elseif ((($punto_pos_gr[$jjj] == "SO") AND ($punto_pos_gr[$jjj-1] == "NO")) OR (($punto_pos_gr[$jjj] == "NO") AND ($punto_pos_gr[$jjj-1] == "SO"))) {
						   $col_pos[$jjj] = $col_pos[$jjj-1] = "O";						 					 							 
				    } else {
						   ##### TOMAR LA POSICION DE LA LINEA MAS LARGA #####
						   if ($col_dist_temp[$jjj] > $col_dist_temp[$jjj-1]) {
							    if ($jjj > 1) {
									   ##### VERIFICAR SI HAY 3 LINEAS ENSEGUIDAS CON EL MISMO NOMBRE #####
									   if (($col_nom_temp[$jjj-2] == $col_nom_temp[$jjj]) AND ($punto_pos_fino[$jjj-2] == $punto_pos_fino[$jjj])) {
										    $col_pos[$jjj] = $col_pos[$jjj-1] = $col_pos[$jjj-2] = $punto_pos_fino[$jjj];
#echo "MISMO NOMBRE --> 3 PUNTOS SEGUIDOS: $col_pos[$jjj]<br />";												
										 } else { 
										    $col_pos[$jjj-1] = $col_pos[$jjj];
										 }
							    } else {
									   $col_pos[$jjj-1] = $col_pos[$jjj];
									}
							    #$col_pos[$jjj] = $col_pos[$jjj-1] = $punto_pos_fino[$jjj];
							 } else {
							    if ($jjj > 1) {
									   if (($col_nom_temp[$jjj-2] == $col_nom_temp[$jjj]) AND ($punto_pos_fino[$jjj-2] == $punto_pos_fino[$jjj])) {
										    $col_pos[$jjj] = $col_pos[$jjj-1] = $col_pos[$jjj-2] = $punto_pos_fino[$jjj-1];
#echo "MISMO NOMBRE --> 3 PUNTOS SEGUIDOS: $col_pos[$jjj]<br />";												
										 } else { 
										    $col_pos[$jjj] = $col_pos[$jjj-1];
										 }
							    } else {
									   $col_pos[$jjj] = $col_pos[$jjj-1];
									}							 
							 }
						}
						$text = $col_pos[$jjj-1];
#echo "MISMO NOMBRE --> POSICION FINA MOD: $col_pos[$jjj], $text<br />";
						
						#if ($col_dist_temp[$jjj] > $col_dist_temp[$jjj-1]) {
						#   $col_pos[$jjj-1] = $col_pos[$jjj];
						#} else {
						#   $col_pos[$jjj] = $col_pos[$jjj-1];						
						#} 
				 } 
			}		
#echo "POSICION FINA: $col_pos[$jjj]<br />";			   
#echo "-----------------------------<br />";
			$iii++;
			$jjj++;
   } #END_OF_WHILE ($iii <= $no_de_vertices)
   ##### CHEQUEAR POSICION DEL ULTIMO VERTICE CON EL PRIMER #####
	 $jjj--;		
	 if (($col_nom_temp[$jjj] == $col_nom_temp[0]) AND ($col_nom_temp[$jjj] != "CALLE S/N") AND ($col_nom_temp[$jjj] != "N.N.")) {
      ##### COMPARAR LA POSICION GRUESA #####
			if ((($punto_pos_gr[$jjj] == "NO") AND ($punto_pos_gr[0] == "NE")) OR (($punto_pos_gr[$jjj] == "NE") AND ($punto_pos_gr[0] == "NO"))) {
			   $col_pos[$jjj] = $col_pos[0] = "N";
		  } elseif ((($punto_pos_gr[$jjj] == "NE") AND ($punto_pos_gr[0] == "SE")) OR (($punto_pos_gr[$jjj] == "SE") AND ($punto_pos_gr[0] == "NE"))) {
			   $col_pos[$jjj] = $col_pos[0] = "E";
			} elseif ((($punto_pos_gr[$jjj] == "SE") AND ($punto_pos_gr[0] == "SO")) OR (($punto_pos_gr[$jjj] == "SO") AND ($punto_pos_gr[0] == "SE"))) {
				 $col_pos[$jjj] = $col_pos[0] = "S";
			} elseif ((($punto_pos_gr[$jjj] == "SO") AND ($punto_pos_gr[0] == "NO")) OR (($punto_pos_gr[$jjj] == "NO") AND ($punto_pos_gr[0] == "SO"))) {
				 $col_pos[$jjj] = $col_pos[0] = "O";							 							 
			} else {
		     if ($col_dist_temp[$jjj] > $col_dist_temp[0]) {
				    $col_pos[$jjj] = $col_pos[0] = $punto_pos_fino[$jjj];
			   } else {
				    $col_pos[$jjj] = $col_pos[0] = $punto_pos_fino[0];
				 }
			}
   }	 
	 
   ##### PREPARAR DATOS PARA INGRESAR #####		 
	 $col_norte_nom = $col_sur_nom = $col_este_nom = $col_oeste_nom = ""; 
	 $col_norte_med = $col_sur_med = $col_este_med = $col_oeste_med = "";
	 $col_norte1 = $col_sur1 = $col_este1 = $col_oeste1 = false;
	 $col_norte2 = $col_sur2 = $col_este2 = $col_oeste2 = false;	 	 
	 $jjj = 0;
	 while ($jjj < $no_de_vertices) {
	    if ($col_pos[$jjj] == "N") {
			   if (!$col_norte1) {
			      $col_norte_nom = $col_nom_temp[$jjj];
						$col_norte1 = true;
				 } else {
				    if ($jjj == $no_de_vertices-1) {
						   if (($col_nom_temp[$jjj] != $col_nom_temp[$jjj-1]) AND ($col_nom_temp[$jjj] != $col_nom_temp[0])) {
							    $col_norte_nom = $col_norte_nom.", ".$col_nom_temp[$jjj]; 
							 }  
				    } elseif ($col_nom_temp[$jjj] != $col_nom_temp[$jjj-1]) {
			         $col_norte_nom = $col_norte_nom.", ".$col_nom_temp[$jjj];
						}		 
				 }
			   if (!$col_norte2) {
				    $col_norte_med = $col_dist_temp[$jjj]." m";
						$col_norte2 = true;
				 } else {
				    $col_norte_med = $col_norte_med.", ".$col_dist_temp[$jjj]." m";				 
				 }				  
			} elseif ($col_pos[$jjj] == "S") {
			   if (!$col_sur1) {
			      $col_sur_nom = $col_nom_temp[$jjj];
						$col_sur1 = true;
				 } else {
				    if ($jjj == $no_de_vertices-1) {
						   if (($col_nom_temp[$jjj] != $col_nom_temp[$jjj-1]) AND ($col_nom_temp[$jjj] != $col_nom_temp[0])) {
							    $col_sur_nom = $col_sur_nom.", ".$col_nom_temp[$jjj]; 
							 }  
				    } elseif ($col_nom_temp[$jjj] != $col_nom_temp[$jjj-1]) {
			         $col_sur_nom = $col_sur_nom.", ".$col_nom_temp[$jjj];
						}		 
				 }
			   if (!$col_sur2) {
				    $col_sur_med = $col_dist_temp[$jjj]." m";
						$col_sur2 = true;
				 } else {
				    $col_sur_med = $col_sur_med.", ".$col_dist_temp[$jjj]." m";				 
				 }				  
			} elseif ($col_pos[$jjj] == "E") {
			   if (!$col_este1) {
			      $col_este_nom = $col_nom_temp[$jjj];
						$col_este1 = true;
				 } else {
				    if ($jjj == $no_de_vertices-1) {
						   if (($col_nom_temp[$jjj] != $col_nom_temp[$jjj-1]) AND ($col_nom_temp[$jjj] != $col_nom_temp[0])) {
							    $col_este_nom = $col_este_nom.", ".$col_nom_temp[$jjj]; 
							 }  
				    } elseif ($col_nom_temp[$jjj] != $col_nom_temp[$jjj-1]) {
			         $col_este_nom = $col_este_nom.", ".$col_nom_temp[$jjj];
						}		 
				 }
			   if (!$col_este2) {
				    $col_este_med = $col_dist_temp[$jjj]." m";
						$col_este2 = true;
				 } else {
				    $col_este_med = $col_este_med.", ".$col_dist_temp[$jjj]." m";				 
				 }
			} elseif ($col_pos[$jjj] == "O") {
			   if (!$col_oeste1) {
			      $col_oeste_nom = $col_nom_temp[$jjj];
						$col_oeste1 = true;
				 } else {
				    if ($jjj == $no_de_vertices-1) {
						   if (($col_nom_temp[$jjj] != $col_nom_temp[$jjj-1]) AND ($col_nom_temp[$jjj] != $col_nom_temp[0])) {
							    $col_oeste_nom = $col_oeste_nom.", ".$col_nom_temp[$jjj]; 
							 }  
				    } elseif ($col_nom_temp[$jjj] != $col_nom_temp[$jjj-1]) {
			         $col_oeste_nom = $col_oeste_nom.", ".$col_nom_temp[$jjj];
						}		 
				 }
			   if (!$col_oeste2) {
				    $col_oeste_med = $col_dist_temp[$jjj]." m";
						$col_oeste2 = true;
				 } else {
				    $col_oeste_med = $col_oeste_med.", ".$col_dist_temp[$jjj]." m";				 
				 }
			}
			$jjj++;
	 } # END_OF_WHILE  
#echo "NORTE: $col_norte_nom $col_norte_med<br />";
#echo "ESTE: $col_este_nom $col_este_med<br />";
#echo "SUR: $col_sur_nom $col_sur_med<br />";						 
#echo "OESTE: $col_oeste_nom $col_oeste_med<br />";		

$col_norte_nom = utf8_encode ($col_norte_nom);
$col_sur_nom = utf8_encode ($col_sur_nom);
$col_este_nom = utf8_encode ($col_este_nom);
$col_oeste_nom = utf8_encode ($col_oeste_nom);

#echo "NORTE: $col_norte_nom $col_norte_med<br />";
#echo "ESTE: $col_este_nom $col_este_med<br />";
#echo "SUR: $col_sur_nom $col_sur_med<br />";						 
#echo "OESTE: $col_oeste_nom $col_oeste_med<br />";	
if ($colind != "Modificar") {
   ##### INGRESAR DATOS #####	 
   pg_query("INSERT INTO colindantes (id_predio, norte_nom, norte_med, sur_nom, sur_med, este_nom, este_med, oeste_nom, oeste_med)
		    VALUES ('$id_predio', '$col_norte_nom', '$col_norte_med', '$col_sur_nom', '$col_sur_med', '$col_este_nom', '$col_este_med', '$col_oeste_nom', '$col_oeste_med')"); 
   $predios_con_col++;  
}    
 /*  }

				 ########################################
	       #------------ POSICION --------------- #
	       ########################################
				 $result2=pg_query("SELECT x(centroid(the_geom)),y(centroid(the_geom))
				                    FROM predios WHERE cod_cat='$col_value'");     
         $cstr = pg_fetch_array($result2, null, PGSQL_ASSOC);
         $col_centroid_x = $cstr['x']; $col_centroid_y = $cstr['y'];
				 pg_free_result($result2);
				 $result2=pg_query("SELECT xmin(extent3d(the_geom)),xmax(extent3d(the_geom)),
														ymin(extent3d(the_geom)),ymax(extent3d(the_geom))
													  FROM predios WHERE cod_cat='$cod_cat'"); 	
         $cstr = pg_fetch_array($result2, null, PGSQL_ASSOC);																	 
				 $xmin = $cstr['xmin']; $xmax = $cstr['xmax'];
				 $ymin = $cstr['ymin']; $ymax = $cstr['ymax'];
				 pg_free_result($result2); 
				 #$col_pos[$j] = get_position4($col_centroid_x, $col_centroid_y, $centroid_x, $centroid_y);
				 $col_pos[$j] = get_position8($col_centroid_x, $col_centroid_y, $centroid_x, $centroid_y, $xmin, $xmax, $ymin, $ymax);
				 if (($col_pos[$j] == "NORTE") OR ($col_pos[$j] == "NE") OR ($col_pos[$j] == "NO")) {
				    if (!$col_norte) {
						   $col_norte_nom = "$col_tit[$j]";
							 $col_norte = true;
						} else $col_norte_nom = $col_norte_nom.", $col_tit[$j]";
				 } elseif (($col_pos[$j] == "SUR") OR ($col_pos[$j] == "SE") OR ($col_pos[$j] == "SO")) {
				    if (!$col_sur) {
						   $col_sur_nom = "$col_tit[$j]";
							 $col_sur = true;
						} else $col_sur_nom = $col_sur_nom.", $col_tit[$j]";
				 } elseif ($col_pos[$j] == "ESTE") {
				    if (!$col_este) {
						   $col_este_nom = "$col_tit[$j]";
							 $col_este = true;
						} else $col_este_nom = $col_este_nom.", $col_tit[$j]";
				 } elseif ($col_pos[$j] == "OESTE") {
				    if (!$col_oeste) {
						   $col_oeste_nom = "$col_tit[$j]";
							 $col_oeste = true;
						} else $col_oeste_nom = $col_oeste_nom.", $col_tit[$j]";
				 }			 		
				 $j++;
	    }
echo "COLINDANTES: $col_norte_nom, $col_sur_nom, $col_este_nom, $col_oeste_nom<br />";			
   }   
	*/ 
	 
  # $no_de_colindantes = $j;
   #$predios_con_col++; 
}
	 
?>
