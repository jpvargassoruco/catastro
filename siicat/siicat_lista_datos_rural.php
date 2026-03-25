<?php  
      
			########################################
	    #----- OBTENER DATOS DEL INMUEBLE -----#
	    ########################################	
#include "siicat_info_inmu_leer_datos.php";
     # $cod_pad = 0; #$info['cod_pad'];																			
      $direccion = "-";
			$titular1 = get_contrib_nombre ($tit_1id); 
			$titular2 = get_contrib_nombre ($tit_2id);			   
			$tit_1ci = get_contrib_ci ($tit_1id);
			if ($tit_1ci == "") {
			   $tit_1ci_texto = "-";
			} else $tit_1ci_texto = $tit_1ci;
			$tit_2ci = get_contrib_ci ($tit_2id);			
			########################################
	    #---------- DEFINIR REGIMEN -----------#
	    ########################################
			$regimen = "PROPIEDAD RURAL";		 				 			 
			########################################
      #------- CALCULAR SUPERFICIE ----------#
      ########################################	    
		/*	$sql="SELECT area(the_geom) FROM predios_ocha WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
      $check = pg_num_rows(pg_query($sql));
	    if ($check == 0) {
	       $ter_smen = 0;
	    } else {
         $result=pg_query($sql);
         $value = pg_fetch_array($result, null, PGSQL_ASSOC);	 
				 $ter_smen = ROUND($value['area'],2);			           
#         $ter_smen = $ter_smen." m²";      
			}	
			########################################
      #----- CALCULAR AREA EDIFICACIONES ----#
      ########################################
      $sql="SELECT area(the_geom) FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
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
				       $area_edif[$i] = ROUND($col_value,2);	
            }
			      $i++;
         } # END_OF_WHILE	
	       $edi_area = ROUND($edi_area,2);
	       pg_free_result($result);			
      }   */
			########################################
      #------ NAVEGACION POR COD_GEO --------#
      ########################################
			$sql="SELECT DISTINCT cod_geo FROM info_predio_rural ORDER BY cod_geo";
      $check_cod_geo = pg_num_rows(pg_query($sql));
			if ($check_cod_geo == 1) {
			   $flecha_geo_ant = $flecha_geo_post = $id_predio_rural;
			} elseif ($check_cod_geo > 1) {
		     $result=pg_query($sql);
         $i = 0;
         while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
            foreach ($line as $col_value) {
						   $cod_geo_array[$i] = $col_value;
							 if ($col_value == $cod_geo) {
							    $position = $i;
							 }
							 $i++;
						}
			   }
				 pg_free_result($result);
				 ### SELECCIONAR GEO ANTERIOR
				 if ($position == 0) {
				    $geo_ant = $cod_geo_array[$i-1];
				 } else $geo_ant = $cod_geo_array[$position-1];
				 $sql="SELECT id_predio_rural FROM info_predio_rural WHERE cod_geo = '$geo_ant' ORDER BY cod_geo, cod_poly, cod_predio LIMIT 1";
				 $result=pg_query($sql);
         $value = pg_fetch_array($result, null, PGSQL_ASSOC);	 
				 $flecha_geo_ant = $value['id_predio_rural'];
				 pg_free_result($result);
				 ### SELECCIONAR GEO POSTERIOR
				 if ($position == $i-1) {
				    $geo_post = $cod_geo_array[0];
				 } else $geo_post = $cod_geo_array[$position+1];
				 $sql="SELECT id_predio_rural FROM info_predio_rural WHERE cod_geo = '$geo_post' ORDER BY cod_geo, cod_poly, cod_predio LIMIT 1";
				 $result=pg_query($sql);
         $value = pg_fetch_array($result, null, PGSQL_ASSOC);	 
				 $flecha_geo_post = $value['id_predio_rural'];
				 pg_free_result($result);			  
			}
			########################################
      #------ NAVEGACION POR POLYGONO -------#
      ########################################
			$sql="SELECT DISTINCT cod_poly FROM info_predio_rural WHERE cod_poly = '$cod_poly' ORDER BY cod_poly";
      $check_cod_poly = pg_num_rows(pg_query($sql));
			if ($check_cod_poly == 1) {
			   $flecha_poly_ant = $flecha_poly_post = $id_predio_rural;
			} elseif ($check_cod_poly > 1) {
		     $result=pg_query($sql);
         $i = 0;
         while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
            foreach ($line as $col_value) {
						   $cod_poly_array[$i] = $col_value;
							 if ($col_value == $cod_poly) {
							    $position = $i;
							 }
							 $i++;
						}
			   }
				 pg_free_result($result);
				 ### SELECCIONAR POLY ANTERIOR
				 if ($position == 0) {
				    $poly_ant = $cod_poly_array[$i-1];
				 } else {
				    $poly_ant = $cod_poly_array[$position-1];
				 }
				 $sql="SELECT id_predio_rural FROM info_predio_rural WHERE cod_geo = '$cod_geo' AND cod_poly = '$poly_ant' ORDER BY cod_geo, cod_poly, cod_predio LIMIT 1";
				 $result=pg_query($sql);
         $value = pg_fetch_array($result, null, PGSQL_ASSOC);	 
				 $flecha_poly_ant = $value['id_predio_rural'];
				 pg_free_result($result);
				 ### SELECCIONAR POLY POSTERIOR
				 if ($position == $i-1) {
				    $poly_post = $cod_poly_array[0];
				 } else $poly_post = $cod_poly_array[$position+1];
				 $sql="SELECT id_predio_rural FROM info_predio_rural WHERE cod_geo = '$cod_geo' AND cod_poly = '$poly_post' ORDER BY cod_geo, cod_poly, cod_predio LIMIT 1";
				 $result=pg_query($sql);
         $value = pg_fetch_array($result, null, PGSQL_ASSOC);	 
				 $flecha_poly_post = $value['id_predio_rural'];
				 pg_free_result($result);
#echo "MANZ anterior: $man_ant, MANZ. posterior: $man_post, $flecha_man_ant, $flecha_man_post<br />";	
			}
			########################################
      #------- NAVEGACION POR PREDIO --------#
      ########################################
			$sql="SELECT DISTINCT cod_predio FROM info_predio_rural WHERE cod_geo = '$cod_geo' AND cod_poly = '$cod_poly' ORDER BY cod_pred";
      $check_cod_pred = pg_num_rows(pg_query($sql));
			if ($check_cod_pred == 1) {
			   $flecha_pred_ant = $flecha_pred_post = $id_predio_rural;
			} elseif ($check_cod_pred > 1) {
		     $result=pg_query($sql);
         $i = 0;
         while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
            foreach ($line as $col_value) {
						   $cod_pred_array[$i] = $col_value;
							 if ($col_value == $cod_pred) {
							    $position = $i;
							 }
							 $i++;
						}
			   }
				 pg_free_result($result);
				 ### SELECCIONAR PREDIO ANTERIOR
				 if ($position == 0) {
				    $pred_ant = $cod_pred_array[$i-1];
				 } else {
				    $pred_ant = $cod_pred_array[$position-1];
				 }
				 $sql="SELECT id_predio_rural FROM info_predio_rural WHERE cod_geo = '$cod_geo' AND cod_poly = '$cod_poly' AND cod_predio = '$pred_ant' ORDER BY cod_geo, cod_poly, cod_predio LIMIT 1";
				 $result=pg_query($sql);
         $value = pg_fetch_array($result, null, PGSQL_ASSOC);	 
				 $flecha_pred_ant = $value['id_predio_rural'];
				 pg_free_result($result);
				 ### SELECCIONAR PREDIO POSTERIOR
				 if ($position == $i-1) {
				    $pred_post = $cod_pred_array[0];
				 } else $pred_post = $cod_pred_array[$position+1];
				 $sql="SELECT id_predio_rural FROM info_predio_rural WHERE cod_geo = '$cod_geo' AND cod_poly = '$cod_poly' AND cod_predio = '$pred_post' ORDER BY cod_geo, cod_poly, cod_predio LIMIT 1";
				 $result=pg_query($sql);
         $value = pg_fetch_array($result, null, PGSQL_ASSOC);	 
				 $flecha_pred_post = $value['id_predio_rural'];
				 pg_free_result($result);					 			  
			}	
			#########################################################
      #------- NAVEGACION POR INMUEBLES DEL TITULAR 1 --------#
      #########################################################
			if (($tit_1id == "") OR ($tit_1id == 0)) {
			   $flecha_inmu_ant = $flecha_inmu_post = $id_inmu;
				 $pos_inmu = 1;
				 $check_inmuebles_tit1 = 1;
			} else {			
			   $sql="SELECT id_predio_rural FROM info_predio_rural WHERE tit_1id = '$tit_1id' OR tit_2id = '$tit_1id' ORDER BY cod_geo, cod_poly, cod_predio";
         $check_inmuebles_tit1 = pg_num_rows(pg_query($sql));
			   if ($check_inmuebles_tit1 == 1) {
			      $flecha_inmu_ant = $flecha_inmu_post = $id_predio_rural;
				    $pos_inmu = 1;
			   } elseif ($check_inmuebles_tit1 > 1) {
		        $result=pg_query($sql);
            $i = 0;
            while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
               foreach ($line as $col_value) {
						      $cod_inmu_array[$i] = $col_value;
							    if ($col_value == $id_inmu) {
							       $position = $i;
							    }
							    $i++;
						   }
			      }
				    pg_free_result($result);
				    ### SELECCIONAR PREDIO ANTERIOR
				    if ($position == 0) {
				       $inmu_ant = $cod_inmu_array[$i-1];
				    } else {
				       $inmu_ant = $cod_inmu_array[$position-1];
				    }
				    $sql="SELECT id_predio_rural FROM info_predio_rural WHERE id_predio_rural = '$inmu_ant'";
				    $result=pg_query($sql);
            $value = pg_fetch_array($result, null, PGSQL_ASSOC);	 
				    $flecha_inmu_ant = $value['id_predio_rural'];
				    pg_free_result($result);
				    ### SELECCIONAR PREDIO POSTERIOR
				    if ($position == $i-1) {
				       $inmu_post = $cod_inmu_array[0];
				    } else $inmu_post = $cod_inmu_array[$position+1];
				    $sql="SELECT id_predio_rural FROM info_inmu WHERE id_predio_rural = '$inmu_post'";
				    $result=pg_query($sql);
            $value = pg_fetch_array($result, null, PGSQL_ASSOC);	 
				    $flecha_inmu_post = $value['id_predio_rural'];
				    pg_free_result($result);
				    $pos_inmu = $position +1;					 			  
			   }
			}								
			########################################
      #---- CHEQUEAR SI EXISTE GEOMETRIA ----#
      ########################################	
  /*    $sql="SELECT cod_uv FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
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
			   # VALOR MAS PEQUEÑO --> MAS ZOOM
			   #$factor_zoom = 2.8;
			   $factor_zoom = 4;
				 # ---------> MOVIDO AL INICIO
				 if (isset($_GET["zoom"])) {			 
				    $factor_zoom = 2;						
				 }
         if ($centerx-$xtent_x[0] > $centery-$xtent_y[0]) {
            $delta = ($centerx-$xtent_x[0])* $factor_zoom; 
#echo "DELTA viene de X y es $delta<br />\n";
         } else {
            $delta = ($centery-$xtent_y[0])* $factor_zoom;
#echo "DELTA viene de Y y es $delta<br />\n";
         }
         $xmin=$centerx- $delta;
         $xmax=$centerx+ $delta; 
         $ymin=$centery- $delta;
         $ymax=$centery+ $delta;		
				 pg_free_result($result1);
				 ########################################
         #----- ENVIAR PREDIO A TEMP-POLY ------#
         ########################################		
				 #pg_query("DELETE FROM temp_poly WHERE user_id = '$user_id' AND numero = '55'");
				 pg_query("DELETE FROM temp_poly WHERE user_id = '$user_id'");				 
				 $sql = "INSERT INTO temp_poly (cod_uv, cod_man, cod_pred, the_geom) SELECT cod_uv, cod_man, cod_pred, the_geom FROM predios_ocha WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
#echo "$sql <br />";
				 pg_query($sql);
			   pg_query("UPDATE temp_poly SET user_id = '$user_id', cod_cat = '$cod_cat', numero = '55' WHERE numero = '99' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
         ########################################
         #------ ENVIAR EDIF A TEMP-POLY ------#
         ########################################					 
				 if (isset($_GET["zoom"])) {			 
				#    pg_query("DELETE FROM temp_poly WHERE user_id = '$user_id' AND numero = '55'");
				#    $sql = "INSERT INTO temp_poly (cod_uv, cod_man, cod_pred, the_geom) SELECT cod_uv, cod_man, cod_pred, the_geom FROM predios_ocha WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
			      pg_query("INSERT INTO temp_poly (edi_num, edi_piso, the_geom) SELECT edi_num, edi_piso, the_geom FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
			  #    pg_query("UPDATE temp_poly SET numero = 44 WHERE edi_num > '0'");
				#    pg_query($sql);
			      pg_query("UPDATE temp_poly SET user_id = '$user_id', numero = '44' WHERE numero = '99'");				
				 }				 
         ########################################
         #--------------- CALLES ---------------#
         ########################################		 
	       pg_query("DELETE FROM temp_line WHERE user_id = '$user_id' OR user_id IS NULL");	 
         pg_query("INSERT INTO temp_line (id, nombre, the_geom) SELECT id, nombre, the_geom FROM calles WHERE id='0'
			          AND the_geom && (select 'polygon(($xmin $ymax,$xmax $ymax,$xmax $ymin,$xmin $ymin, $xmin $ymax))'::geometry);");
			   pg_query("UPDATE temp_line SET user_id = '$user_id', id = '1' WHERE id = '0'");	
#			   pg_query("UPDATE temp_line SET user_id = '$user_id', id = '2' WHERE id = '0'");  
		  }  */
?>
