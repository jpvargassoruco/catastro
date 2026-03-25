<?php  

$no_de_ochaves = 0;
$position = 0;
while ($position < $no_de_vertices) { 
   $cambio = true;
	 $level = $position+1;
	 $point_ocha_x = $point_x[$position];
	 $point_ocha_y = $point_y[$position];	  
   ##### CHEQUEAR SI EL PUNTO SELECCIONADO TIENE COLINDANTE #####
	 $ocha_col = false;
   $i_col = 0;
   while ($i_col < $no_de_colindantes) {
      $cod_cat_ocha = $col_cod[$i_col];
echo "CODIGO DEL COLINDANTE: $cod_cat_ocha<br>";			
      $sql = "SELECT st_dwithin(ST_GeomFromText('POINT($point_ocha_x $point_ocha_y)'), (SELECT the_geom FROM predios WHERE cod_cat = '$cod_cat_ocha' AND activo = '1'),0.25) AS result";
      $result_ocha=pg_query($sql);
      $res_ocha = pg_fetch_array($result_ocha, null, PGSQL_ASSOC);
      if ($res_ocha['result'] == "t") {
         $ocha_col = true;		 
	    }
			pg_free_result($result_ocha);	
	    $i_col++;
   } 
	 if ($ocha_col) {
	    $coords_string[$position] = "$point_ocha_x $point_ocha_y";
	    if ($position == 0) {		
	       $punto_final = "$point_ocha_x $point_ocha_y";
			} 
	 } else {
#echo "No se encontró ningun colindante para ese vertice, por eso se procede en el calculo del ochave:<br>";	 
      ##### DEFINIR EL RADIO DEL OCHAVE A PARTIR DE CALLES/AVENIDAS #####	 
	    $dist_calle = 20;
      $sql = "SELECT nombre FROM calles WHERE st_dwithin (ST_GeomFromText('POINT($point_ocha_x $point_ocha_y)'),the_geom,$dist_calle)";
      $check_calle= pg_num_rows(pg_query($sql));
	    if ($check_calle == 0) {
#echo "... pero no se encontró ninguna calle cerca<br>";
	       $coords_string[$position] = "$point_ocha_x $point_ocha_y";
	       if ($position == 0) {		
	          $punto_final = "$point_ocha_x $point_ocha_y";
			   } 				
        # $error = true;
		    # $mensaje_de_error = "Error: No se encontró ninguna avenida, calle o pasaje a $dist_calle del predio $cod_cat!";			   
			} elseif ($check_calle == 1) {
#echo "No se genera Ochave porque se encontró solo una calle cerca<br>";
	       $coords_string[$position] = "$point_ocha_x $point_ocha_y";
	       if ($position == 0) {		
	          $punto_final = "$point_ocha_x $point_ocha_y";
			   }			
        # $error = true;
		    # $mensaje_de_error = "Error: No se encontró ninguna avenida, calle o pasaje a $dist_calle del predio $cod_cat!";			   
			} else {
			   $result_calle = pg_query($sql);
			   $ocha_avenidas = $ocha_calles = $ocha_pasajes = 0; 
         while ($line = pg_fetch_array($result_calle, null, PGSQL_ASSOC)) {
            foreach ($line as $col_value) {
						   $char = substr($col_value, 0, 2);		
			         if ($char == "AV") {
				          $ocha_avenidas++;							 
						   } elseif ($char == "P/") {
							    $ocha_pasajes++;							 
						   } else {
							    $ocha_calles++;	
							 }						
						}
				 }
				 pg_free_result($result_calle);		
         if ($ocha_avenidas > 1) {
				    $ocha_radio = 10;							 
				 } elseif (($ocha_avenidas == 1) AND ($ocha_calles > 0)) {
				    $ocha_radio = 7.5;	
				 } elseif (($ocha_avenidas == 1) AND ($ocha_pasajes > 0)) {
				    $ocha_radio = 5;													 
				 } elseif ($ocha_calles > 1) {
				    $ocha_radio = 5;	
				 } else {
				    $ocha_radio = 2.5;	
				 }
#echo "Avenidas: $ocha_avenidas, Calles: $ocha_calles, Pasajes: $ocha_pasajes<br>";				 
         ##### CREAR UN BUFFER ALREDEDOR DE CADA LINEA Y HACER UNA INTERSECCION ENTRE LOS BUFFER #####
				 if ($position == 0) {	 
	          $point_ocha_x_ant = $point_x[$no_de_vertices-1];
	          $point_ocha_y_ant = $point_y[$no_de_vertices-1];
	          $point_ocha_x_prox = $point_x[$position+1];
	          $point_ocha_y_prox = $point_y[$position+1];
				 } elseif ($position == $no_de_vertices-1) {
	          $point_ocha_x_ant = $point_x[$position-1];
	          $point_ocha_y_ant = $point_y[$position-1];
	          $point_ocha_x_prox = $point_x[0];
	          $point_ocha_y_prox = $point_y[0];
				 } else {
	          $point_ocha_x_ant = $point_x[$position-1];
	          $point_ocha_y_ant = $point_y[$position-1];
	          $point_ocha_x_prox = $point_x[$position+1];
	          $point_ocha_y_prox = $point_y[$position+1];										 
				 }
         # CALCULAR DISTANCIA ENTRE EL PUNTO P1 Y LA ESQUINA #
				 $dist_line1 = get_linelen ($point_ocha_x_ant, $point_ocha_y_ant, $point_ocha_x, $point_ocha_y);			 
         # CALCULAR DISTANCIA ENTRE LA ESQUINA y PUNTO P2 #
				 $dist_line2 = get_linelen ($point_ocha_x, $point_ocha_y, $point_ocha_x_prox, $point_ocha_y_prox);				 
				 # FILTRAR TODAS LOS PUNTOS DEMASIADOS JUNTOS #
				 if (($dist_line1 < 5) OR ($dist_line2 <5)) {
#echo "Puntos demasiados cercas para crear ochave<br>";	
         } else {		 						 
				    # INTERSECCION ENTRE BUFFER #					 
#echo "ANT: $point_ocha_x_ant $point_ocha_y_ant, PROX: $point_ocha_x_prox $point_ocha_y_prox<br>";				 				 				 
            $sql = "SELECT ST_AsText(ST_Intersection(st_buffer(ST_GeomFromText('LINESTRING($point_ocha_x_ant $point_ocha_y_ant,$point_ocha_x $point_ocha_y)'),$ocha_radio), st_buffer(ST_GeomFromText('LINESTRING($point_ocha_x_prox $point_ocha_y_prox,$point_ocha_x $point_ocha_y)'),$ocha_radio))) AS result";
            $result_buffer=pg_query($sql);
            $res_buff = pg_fetch_array($result_buffer, null, PGSQL_ASSOC);
            $coord_buff = $res_buff['result'];
				    pg_free_result($result_buffer); 
#echo "BUFFER: $coord_buff<br>";	
            ##### LEER LAS COORDENADAS DEL POLIGONO RESULTANTE #####
            $ii = 0;
            $jj = 9;
            $x_buff = 0; 
            while ($ii <= strlen($coord_buff)) {
               $char = trim(substr($coord_buff, $ii, 1));
#echo "CHAR ES: $char<br>";							
	             if ($char == "") {
							    $buffer_x = substr($coord_buff,$jj,$ii-$jj);
							    $jj=$ii+1;		        		 
	             }			
	             if ($char == ',') {
							    $buffer_y = substr($coord_buff,$jj,$ii-$jj);
		              $jj=$ii+1;			
			            $x_buff++;
#echo "RESULT:  $buffer_x, $buffer_y <br>";
                  ##### SOLO PERMITIR PUNTOS DENTRO DEL PREDIO #####
                  $sql = "SELECT ST_Within(ST_GeomFromText('POINT($buffer_x $buffer_y)'), (SELECT the_geom FROM predios WHERE cod_cat = '$cod_cat')) AS pos";		
                  $result_p=pg_query($sql);
                  $res_pos = pg_fetch_array($result_p, null, PGSQL_ASSOC);
                  $point_position = $res_pos['pos'];					 									 
							    pg_free_result($result_p);
#echo "POSITION ADENTRO DEL PREDIO?: $point_position<br>";							 
							    if ($point_position == "t") {
                     ##### CHEQUEAR LA DISTANCIA HACIA LA LINEAS #####
                     $sql = "SELECT ST_Distance(ST_GeomFromText('POINT($buffer_x $buffer_y)'), ST_GeomFromText('LINESTRING($point_ocha_x_ant $point_ocha_y_ant,$point_ocha_x $point_ocha_y)'))AS dist";		
                     $result_dist=pg_query($sql);
                     $res_dist = pg_fetch_array($result_dist, null, PGSQL_ASSOC);
                     $distancia_line1 = $res_dist['dist'];					 									 
							       pg_free_result($result_dist);
                     $sql = "SELECT ST_Distance(ST_GeomFromText('POINT($buffer_x $buffer_y)'),ST_GeomFromText('LINESTRING($point_ocha_x_prox $point_ocha_y_prox,$point_ocha_x $point_ocha_y)')) AS dist";		
                     $result_dist=pg_query($sql);
                     $res_dist = pg_fetch_array($result_dist, null, PGSQL_ASSOC);
                     $distancia_line2= $res_dist['dist'];									 
							       pg_free_result($result_dist);	
#echo "DISTANCIA DEL BUFFER HACIA LAS LINEAS: $distancia_line1, $distancia_line2<br>";																 	 
                     $dist1 = (int) ROUND($distancia_line1 * 100000,0)/100000;
									   $dist2 = (int) ROUND($distancia_line2 * 100000,0)/100000; 
                     if (($dist1 == $ocha_radio) AND ($dist2 == $ocha_radio)) {
									      $centro_ocha_x = ROUND ($buffer_x*1000,0)/1000;
									      $centro_ocha_y = ROUND ($buffer_y*1000,0)/1000;										 
#echo "CENTRO DEL OCHAVE:  $centro_ocha_x $centro_ocha_y, $dist1, $dist2<br>";								
									   }
                  }				 						 
	             } 
	             $ii++;   
            } #end_of_while	
				    #################################################################################
            ##### PUNTO 1: EN DE LA LINEA 1 EL PUNTO MAS CERCA AL CENTRO DEL OCHAVE #####
				    #################################################################################
				    $sql="SELECT x(ST_Line_Interpolate_Point(foo.the_line, ST_Line_Locate_Point(foo.the_line, ST_GeomFromText('POINT($centro_ocha_x $centro_ocha_y)')))) FROM (SELECT ST_GeomFromText('LINESTRING($point_ocha_x_ant $point_ocha_y_ant,$point_ocha_x $point_ocha_y)') As the_line) As foo";				 
            $result_p=pg_query($sql);				 
            $res_p1 = pg_fetch_array($result_p, null, PGSQL_ASSOC);
            $pos_p1_x = ROUND ($res_p1['x']*1000,0)/1000;			 									 
				    pg_free_result($result_p);
				    $sql="SELECT y(ST_Line_Interpolate_Point(foo.the_line, ST_Line_Locate_Point(foo.the_line, ST_GeomFromText('POINT($centro_ocha_x $centro_ocha_y)')))) FROM (SELECT ST_GeomFromText('LINESTRING($point_ocha_x_ant $point_ocha_y_ant,$point_ocha_x $point_ocha_y)') As the_line) As foo";				 
            $result_p=pg_query($sql);				 
            $res_p1 = pg_fetch_array($result_p, null, PGSQL_ASSOC);
            $pos_p1_y = ROUND ($res_p1['y']*1000,0)/1000;						 									 
				    pg_free_result($result_p);				 
#echo "POSICION DEL PUNTO 1:  $pos_p1_x, $pos_p1_y <br>";
				    #################################################################################
            ##### PUNTO 2: EN DE LA LINEA 2 EL PUNTO MAS CERCA AL CENTRO DEL OCHAVE #####
				    #################################################################################
				    $sql="SELECT x(ST_Line_Interpolate_Point(foo.the_line, ST_Line_Locate_Point(foo.the_line, ST_GeomFromText('POINT($centro_ocha_x $centro_ocha_y)')))) FROM (SELECT ST_GeomFromText('LINESTRING($point_ocha_x_prox $point_ocha_y_prox,$point_ocha_x $point_ocha_y)') As the_line) As foo";				 
            $result_p=pg_query($sql);				 
            $res_p2 = pg_fetch_array($result_p, null, PGSQL_ASSOC);
            $pos_p2_x = ROUND ($res_p2['x']*1000,0)/1000;					 									 
				    pg_free_result($result_p);
				    $sql="SELECT y(ST_Line_Interpolate_Point(foo.the_line, ST_Line_Locate_Point(foo.the_line, ST_GeomFromText('POINT($centro_ocha_x $centro_ocha_y)')))) FROM (SELECT ST_GeomFromText('LINESTRING($point_ocha_x_prox $point_ocha_y_prox,$point_ocha_x $point_ocha_y)') As the_line) As foo";				 
            $result_p=pg_query($sql);				 
            $res_p2 = pg_fetch_array($result_p, null, PGSQL_ASSOC);
            $pos_p2_y = ROUND ($res_p2['y']*1000,0)/1000;					 									 
				    pg_free_result($result_p);				 
#echo "POSICION DEL PUNTO 2:  $pos_p2_x, $pos_p2_y <br>";	
            #####################################################################
				    ##### ENCONTRAR PUNTO 3 ENTRE EL CENTRO DEL OCHAVE Y LA ESQUINA #####
				    #####################################################################				 
            # CALCULAR DISTANCIA ENTRE EL CENTRO DEL OCHAVE Y LA ESQUINA #
				    $sql="SELECT ST_Length(ST_GeomFromText('LINESTRING($centro_ocha_x $centro_ocha_y, $point_ocha_x $point_ocha_y)'))AS dist";				 
            $result_p=pg_query($sql);				 
            $res_dis = pg_fetch_array($result_p, null, PGSQL_ASSOC);
            $dist_ocha_esq = ROUND ($res_dis['dist']*1000,0)/1000;					 									 
				    pg_free_result($result_p);		
#echo "DISTANCIA ENTRE ESQUINA Y CENTRO DE OCHAVE:  $dist_ocha_esq <br>";
            # CALCULAR PORCENTAJE DONDE EL RADIO DEL OCHAVE CORTA LA LINEA (CENTRO DE OCHAVE - ESQUINA) #
            $porc_dist = $ocha_radio/$dist_ocha_esq;
#echo "PORCENTAJE DEL OCHAVE EN LA LINEA:  $porc_dist <br>";	
            # CALCULAR NUEVO PUNTO EN LA LINEA 'CENTRO DE OCHAVE - ESQUINA' CON EL RADIO DEL OCHAVE #
				    $sql="SELECT x(ST_Line_Interpolate_Point(the_line, $porc_dist))
	                FROM (SELECT ST_GeomFromText('LINESTRING($centro_ocha_x $centro_ocha_y, $point_ocha_x $point_ocha_y)') As the_line) As foo";
            $result_p=pg_query($sql);				 
            $res_p3 = pg_fetch_array($result_p, null, PGSQL_ASSOC);
            $pos_p3_x = ROUND ($res_p3['x']*1000,0)/1000;					 									 
				    pg_free_result($result_p);	
				    $sql="SELECT y(ST_Line_Interpolate_Point(the_line, $porc_dist))
	                FROM (SELECT ST_GeomFromText('LINESTRING($centro_ocha_x $centro_ocha_y, $point_ocha_x $point_ocha_y)') As the_line) As foo";
            $result_p=pg_query($sql);				 
            $res_p3 = pg_fetch_array($result_p, null, PGSQL_ASSOC);
            $pos_p3_y = ROUND ($res_p3['y']*1000,0)/1000;					 									 
				    pg_free_result($result_p);				 
#echo "POSICION DEL PUNTO 3:  $pos_p3_x, $pos_p3_y <br>";
            ##############################################
            ##### CALCULAR PUNTO 4 ENTRE PUNTO 1 Y 3 #####
				    ##############################################
				    # CALCULAR PUNTO EN MEDIO DE LA LINEA 'PUNTO P1 - PUNTO ESQUINA' #
				    $porc_dist = 0.5;
            $temp_p4_x = get_point_x ($pos_p1_x,$pos_p1_y, $point_ocha_x,$point_ocha_y,$porc_dist);		 
				    $temp_p4_y = get_point_y ($pos_p1_x,$pos_p1_y, $point_ocha_x,$point_ocha_y,$porc_dist);					 	
            # CALCULAR DISTANCIA ENTRE EL CENTRO DEL OCHAVE Y EL PUNTO TEMP_P4 #
				    $dist_p4 = get_linelen ($centro_ocha_x, $centro_ocha_y, $temp_p4_x, $temp_p4_y);
            # CALCULAR PORCENTAJE DONDE EL RADIO DEL OCHAVE CORTA LA LINEA (CENTRO DE OCHAVE - PUNTO TEMP_P4) #
            $porc_dist = $ocha_radio/$dist_p4;				 
            # CALCULAR NUEVO PUNTO EN LA LINEA 'CENTRO DE OCHAVE - PUNTO TEMP_P4' CON EL RADIO DEL OCHAVE #
            $pos_p4_x = get_point_x ($centro_ocha_x, $centro_ocha_y, $temp_p4_x, $temp_p4_y,$porc_dist);		 
				    $pos_p4_y = get_point_y ($centro_ocha_x, $centro_ocha_y, $temp_p4_x, $temp_p4_y,$porc_dist);				 			 
#echo "POSICION DEL PUNTO 4:  $pos_p4_x, $pos_p4_y, $dist_p4, $porc_dist <br>";
            ##############################################
            ##### CALCULAR PUNTO 5 ENTRE PUNTO 3 Y 2 #####
				    ##############################################
				    # CALCULAR PUNTO EN MEDIO DE LA LINEA 'PUNTO P2 - PUNTO ESQUINA' #
				    $porc_dist = 0.5;
            $temp_p5_x = get_point_x ($pos_p2_x,$pos_p2_y, $point_ocha_x,$point_ocha_y,$porc_dist);		 
				    $temp_p5_y = get_point_y ($pos_p2_x,$pos_p2_y, $point_ocha_x,$point_ocha_y,$porc_dist);					 	
            # CALCULAR DISTANCIA ENTRE EL CENTRO DEL OCHAVE Y EL PUNTO TEMP_P4 #
				    $dist_p5 = get_linelen ($centro_ocha_x, $centro_ocha_y, $temp_p5_x, $temp_p5_y);
            # CALCULAR PORCENTAJE DONDE EL RADIO DEL OCHAVE CORTA LA LINEA (CENTRO DE OCHAVE - PUNTO TEMP_P4) #
            $porc_dist = $ocha_radio/$dist_p5;				 
            # CALCULAR NUEVO PUNTO EN LA LINEA 'CENTRO DE OCHAVE - PUNTO TEMP_P4' CON EL RADIO DEL OCHAVE #
            $pos_p5_x = get_point_x ($centro_ocha_x, $centro_ocha_y, $temp_p5_x, $temp_p5_y,$porc_dist);		 
				    $pos_p5_y = get_point_y ($centro_ocha_x, $centro_ocha_y, $temp_p5_x, $temp_p5_y,$porc_dist);				 			 
#echo "POSICION DEL PUNTO 5:  $pos_p5_x, $pos_p5_y, $dist_p5, $porc_dist <br>";
            ##############################################
            ##### CALCULAR PUNTO 6 ENTRE PUNTO 1 Y 4 #####
				    ##############################################
				    # CALCULAR PUNTO EN MEDIO DE LA LINEA 'PUNTO P1 - PUNTO TEMP_P4' #
				    $porc_dist = 0.5;
            $temp_p6_x = get_point_x ($pos_p1_x,$pos_p1_y, $temp_p4_x,$temp_p4_y,$porc_dist);		 
				    $temp_p6_y = get_point_y ($pos_p1_x,$pos_p1_y, $temp_p4_x,$temp_p4_y,$porc_dist);					 	
            # CALCULAR DISTANCIA ENTRE EL CENTRO DEL OCHAVE Y EL PUNTO TEMP_P6 #
				    $dist_p6 = get_linelen ($centro_ocha_x, $centro_ocha_y, $temp_p6_x, $temp_p6_y);
            # CALCULAR PORCENTAJE DONDE EL RADIO DEL OCHAVE CORTA LA LINEA (CENTRO DE OCHAVE - PUNTO TEMP_P6) #
            $porc_dist = $ocha_radio/$dist_p6;				 
            # CALCULAR NUEVO PUNTO EN LA LINEA 'CENTRO DE OCHAVE - PUNTO TEMP_P6' CON EL RADIO DEL OCHAVE #
            $pos_p6_x = get_point_x ($centro_ocha_x, $centro_ocha_y, $temp_p6_x, $temp_p6_y,$porc_dist);		 
				    $pos_p6_y = get_point_y ($centro_ocha_x, $centro_ocha_y, $temp_p6_x, $temp_p6_y,$porc_dist);				 			 
#echo "POSICION DEL PUNTO 6:  $pos_p6_x, $pos_p6_y, $dist_p6, $porc_dist <br>";
            ##############################################
            ##### CALCULAR PUNTO 7 ENTRE PUNTO 4 Y 3 #####
				    ##############################################
				    # CALCULAR PUNTO EN MEDIO DE LA LINEA 'PUNTO TEMP_P4 - PUNTO ESQUINA' #
				    $porc_dist = 0.5;
            $temp_p7_x = get_point_x ($temp_p4_x,$temp_p4_y, $point_ocha_x,$point_ocha_y,$porc_dist);		 
				    $temp_p7_y = get_point_y ($temp_p4_x,$temp_p4_y, $point_ocha_x,$point_ocha_y,$porc_dist);					 	
            # CALCULAR DISTANCIA ENTRE EL CENTRO DEL OCHAVE Y EL PUNTO TEMP_P7 #
				    $dist_p7 = get_linelen ($centro_ocha_x, $centro_ocha_y, $temp_p7_x, $temp_p7_y);
            # CALCULAR PORCENTAJE DONDE EL RADIO DEL OCHAVE CORTA LA LINEA (CENTRO DE OCHAVE - PUNTO TEMP_P7) #
            $porc_dist = $ocha_radio/$dist_p7;				 
            # CALCULAR NUEVO PUNTO EN LA LINEA 'CENTRO DE OCHAVE - PUNTO TEMP_P7' CON EL RADIO DEL OCHAVE #
            $pos_p7_x = get_point_x ($centro_ocha_x, $centro_ocha_y, $temp_p7_x, $temp_p7_y,$porc_dist);		 
				    $pos_p7_y = get_point_y ($centro_ocha_x, $centro_ocha_y, $temp_p7_x, $temp_p7_y,$porc_dist);				 			 
#echo "POSICION DEL PUNTO 7:  $pos_p7_x, $pos_p7_y, $dist_p7, $porc_dist <br>";
            ##############################################
            ##### CALCULAR PUNTO 8 ENTRE PUNTO 3 Y 5 #####
				    ##############################################
				    # CALCULAR PUNTO EN MEDIO DE LA LINEA 'PUNTO ESQUINA - PUNTO TEMP_P5' #
				    $porc_dist = 0.5;
            $temp_p8_x = get_point_x ($point_ocha_x,$point_ocha_y,$temp_p5_x,$temp_p5_y, $porc_dist);		 
				    $temp_p8_y = get_point_y ($point_ocha_x,$point_ocha_y,$temp_p5_x,$temp_p5_y, $porc_dist);					 	
            # CALCULAR DISTANCIA ENTRE EL CENTRO DEL OCHAVE Y EL PUNTO TEMP_P8 #
				    $dist_p8 = get_linelen ($centro_ocha_x, $centro_ocha_y, $temp_p8_x, $temp_p8_y);
            # CALCULAR PORCENTAJE DONDE EL RADIO DEL OCHAVE CORTA LA LINEA (CENTRO DE OCHAVE - PUNTO TEMP_P8) #
            $porc_dist = $ocha_radio/$dist_p8;				 
            # CALCULAR NUEVO PUNTO EN LA LINEA 'CENTRO DE OCHAVE - PUNTO TEMP_P8' CON EL RADIO DEL OCHAVE #
            $pos_p8_x = get_point_x ($centro_ocha_x, $centro_ocha_y, $temp_p8_x, $temp_p8_y,$porc_dist);		 
				    $pos_p8_y = get_point_y ($centro_ocha_x, $centro_ocha_y, $temp_p8_x, $temp_p8_y,$porc_dist);				 			 
#echo "POSICION DEL PUNTO 8:  $pos_p8_x, $pos_p8_y, $dist_p8, $porc_dist <br>";
            ##############################################
            ##### CALCULAR PUNTO 9 ENTRE PUNTO 5 Y 2 #####
				    ##############################################
				    # CALCULAR PUNTO EN MEDIO DE LA LINEA 'PUNTO TEMP_P5 - PUNTO P2' #
				    $porc_dist = 0.5;
            $temp_p9_x = get_point_x ($temp_p5_x,$temp_p5_y,$pos_p2_x,$pos_p2_y, $porc_dist);		 
				    $temp_p9_y = get_point_y ($temp_p5_x,$temp_p5_y,$pos_p2_x,$pos_p2_y, $porc_dist);					 	
            # CALCULAR DISTANCIA ENTRE EL CENTRO DEL OCHAVE Y EL PUNTO TEMP_P9 #
				    $dist_p9 = get_linelen ($centro_ocha_x, $centro_ocha_y, $temp_p9_x, $temp_p9_y);
            # CALCULAR PORCENTAJE DONDE EL RADIO DEL OCHAVE CORTA LA LINEA (CENTRO DE OCHAVE - PUNTO TEMP_P9) #
            $porc_dist = $ocha_radio/$dist_p9;				 
            # CALCULAR NUEVO PUNTO EN LA LINEA 'CENTRO DE OCHAVE - PUNTO TEMP_P9' CON EL RADIO DEL OCHAVE #
            $pos_p9_x = get_point_x ($centro_ocha_x, $centro_ocha_y, $temp_p9_x, $temp_p9_y,$porc_dist);		 
				    $pos_p9_y = get_point_y ($centro_ocha_x, $centro_ocha_y, $temp_p9_x, $temp_p9_y,$porc_dist);				 			 
#echo "POSICION DEL PUNTO 9:  $pos_p9_x, $pos_p9_y, $dist_p9, $porc_dist <br>";
            ##############################################################
            ##### GENERAR NUEVOS PUNTOS (ORDEN ES 1,6,4,7,3,8,5,9,2) #####
				    ##############################################################
				    $coords_string[$position] = "$pos_p1_x $pos_p1_y, $pos_p6_x $pos_p6_y, $pos_p4_x $pos_p4_y, $pos_p7_x $pos_p7_y, $pos_p3_x $pos_p3_y, $pos_p8_x $pos_p8_y, $pos_p5_x $pos_p5_y, $pos_p9_x $pos_p9_y, $pos_p2_x $pos_p2_y";	
				    if ($position == 0) {
               $punto_final = "$pos_p1_x $pos_p1_y";
				    }
            # INSERTAR LINEA DE RADIO EN TEMP_LINE #	
				    $ocha_radio_anot = "R".$ocha_radio;				
				    pg_query("INSERT INTO temp_line (user_id, id, nombre, descrip, tipo, the_geom) 
                      VALUES ('$user_id','0','$cod_cat','$position','$ocha_radio_anot','{$esc2}$centro_ocha_x $centro_ocha_y, $pos_p3_x $pos_p3_y {$esc3}')");			
				    $no_de_ochaves++;
         }#END_OF_ELSE ($dist_line1 > 5) AND ($dist_line2 > 5)
			} 
   }	   
	 #$vertice_count = $_POST["vertice_count"];	 
	# pg_query("DELETE FROM temp_poly WHERE user_id = '$user_id' AND number = '5' AND cod_cat != '$cod_cat'");

#$sql="SELECT cod_cat FROM temp_poly WHERE cod_cat ='$cod_cat' AND user_id = '$user_id' AND number = '2'"; 
#$check_temp_poly = pg_num_rows(pg_query($sql));  	 

   $position++;
} #END_OF_WHILE

##############################################
########### GENERAR COORD-STRING #############
##############################################
$coords = $coords_string[0];		 
$i_coord = 1;
while ($i_coord < $no_de_vertices) {	 
   $coords = "$coords, $coords_string[$i_coord]";								 		
   $i_coord++;	    
} 
$coords = "$coords, $punto_final";
#echo "Numero de Ochaves: $no_de_ochaves<br>";
#echo "COORDS_STRING generado: $coords<br>";		
if ($no_de_ochaves > 0) {
   pg_query("INSERT INTO temp_poly (user_id, cod_cat, number, the_geom) 
      VALUES ('$user_id','$cod_cat','10','{$esc4}$coords{$esc5}')");
}
$no_de_objetos = $no_de_objetos + $no_de_ochaves;
?>