<?php

################################################################################
#---------------- ASIGNAR COLINDANTES Y MEDIDAS AUTOMATICAMENTE ---------------#
################################################################################	
#$dist_asf = 12;
if (isset($_POST["asignar_colindantes"])) {
   $sql = "SELECT id_predio FROM predios WHERE activo = '1' ORDER BY cod_uv, cod_man, cod_pred";
echo "$sql";
	 $check_pred = pg_num_rows(pg_query($sql)); 	
	 if ($check_pred == 0) {		
	    $mensaje_pred = "No hay ningun predio en la tabla 'predios'!";
	 } else {
	    $result=pg_query($sql);
			$predios_con_col = 0;
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
         foreach ($line as $col_value) {			 
				    $id_predio = $col_value;
            include "siicat_anadir_colindantes.php";
 		   }
      } # END_OF_WHILE	
      pg_free_result($result); 
			$mensaje_col = "Se ha añadido los colindantes de $predios_con_col predio(s)."; 			
	 }
   ### REGISTRO ###
   $username = get_username($session_id);
   $accion_reg = "Asignar Colindantes";
   pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		         VALUES ('$username','$ip','$fecha','$hora','$accion_reg','-')");	 
}	
################################################################################
#---------------------------- ASIGNAR TITULARIDAD -----------------------------#
################################################################################	
if (isset($_POST["asignar_titularidad"])) {
   $sql = "SELECT id_inmu FROM info_inmu ORDER BY id_inmu";
	 $check_inmu = pg_num_rows(pg_query($sql)); 	
	 if ($check_inmu == 0) {		
	    $mensaje_tit = "No hay ningun inmueble en la base de datos!";
	 } else {
	    $result=pg_query($sql);
			$inmu_con_tit = 0;
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
         foreach ($line as $col_value) {			 
				    $id_inmu = $col_value;
            include "siicat_asignar_titularidad.php";
			   }
      } # END_OF_WHILE	
      pg_free_result($result); 
			$mensaje_tit = "Se ha asignado la titularidad a $inmu_con_tit inmuebles(s)."; 			
	 }
   ### REGISTRO ###
   $username = get_username($session_id);
   $accion_reg = "Asignar Titularidad";
   pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		         VALUES ('$username','$ip','$fecha','$hora','$accion_reg','-')");	 
}	
################################################################################
#---------------------------- DETERMINAR REGIMEN ------------------------------#
################################################################################	
if (isset($_POST["determinar_regimen"])) {
   $sql = "SELECT id_inmu FROM info_inmu ORDER BY id_inmu";
	 $check_inmu = pg_num_rows(pg_query($sql));	 
   $result=pg_query($sql);
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
      foreach ($line as $col_value) {			 
         $id_inmu = $col_value;
		     $cod_uv = get_cod_uv_from_id_inmu ($id_inmu);$cod_man = get_cod_man_from_id_inmu ($id_inmu);$cod_pred = get_cod_pred_from_id_inmu ($id_inmu); 
         ### CHEQUEAR SI EXISTE GEOMETRIA PREDIO
         $sql="SELECT cod_uv FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
         $check_geo_predio = pg_num_rows(pg_query($sql));
         ### CHEQUEAR SI EXISTE INFORMACION PREDIO
         $sql="SELECT cod_uv FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
         $check_info_predio = pg_num_rows(pg_query($sql));		 		 
         ### CHEQUEAR SI EXISTE GEOMETRIA EDIF		 
         $sql="SELECT cod_uv FROM info_edif WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
         $check_info_edif = pg_num_rows(pg_query($sql));
         ### CHEQUEAR SI EXISTEN VARIOS INMUEBLES EN EL PREDIO
         $sql="SELECT id_inmu FROM info_inmu WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
         $check_id_inmu = pg_num_rows(pg_query($sql));
         ### DEFINIR REGIMEN
         if ($check_geo_predio == 0) {
            $regimen = "SIN";
         } elseif ($check_info_predio == 0) {	 
            $regimen = "PRE";				 
         } elseif ($check_info_edif == 0) {	 
            $regimen = "TER"; 
         } elseif ($check_id_inmu == 1) {	 
            $regimen = "CAS"; 
         }	elseif ($check_id_inmu > 1) {
            $regimen = "PH";
			   } else {
			      $regimen = "";
			   }
			   ### INSERTAR EN INFO_INMU
			   $sql = "UPDATE info_inmu SET tipo_inmu = '$regimen' WHERE id_inmu = '$id_inmu'";
         pg_query($sql);
			}
   } # END_OF_WHILE	
   pg_free_result($result); 
	 $mensaje_reg = "Se ha modificado el tipo de inmueble en un total de $check_inmu inmuebles(s)."; 		
   ### REGISTRO ###
   $username = get_username($session_id);
   $accion_reg = "Determinar Regimen";
   pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		         VALUES ('$username','$ip','$fecha','$hora','$accion_reg','-')");
}	
################################################################################
#--------------------------- ASIGNAR MATERIAL DE VIA --------------------------#
################################################################################	
/*
if (isset($_POST["asignar_via_mat"])) {
	 $dist_asf = $_POST["dist_asf"];
   $sql = "SELECT cod_uv FROM predios WHERE st_dwithin ((SELECT the_geom FROM calles WHERE observ ='290603'),the_geom,$dist_asf) AND activo = '1'";
	 $check_mat = pg_num_rows(pg_query($sql)); 	
	 if ($check_mat != 10000) {		
	    $mensaje_mat = "No hay ningun predio a $dist_asf m distancia del asfalto!";
	 } else {
	    $mensaje_mat = "Hay $check_asf predio(s) a $dist_asf m distancia del asfalto!";
	    $result=pg_query($sql);
			$i = 0;
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
         foreach ($line as $col_value) {
            pg_query("UPDATE info_predio SET via_mat = 'ASF' WHERE cod_cat = '$col_value'");
						pg_query("INSERT INTO cambios (cod_cat, fecha_cambio, variable, valor_ant) VALUES ('$col_value','2010-01-01','via_mat','RIP')");
						$i++;
			   }
      } # END_OF_WHILE	
      pg_free_result($result); 
			$mensaje_mat = $mensaje_mat." Se ha modificado $i predio(s) en la base de datos."; 			
	 }
   ### REGISTRO ###
   $username = get_username($session_id);
   $accion_reg = "Asignar Material de Via";
   pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		         VALUES ('$username','$ip','$fecha','$hora','$accion_reg','-')");	 	
} */
/*
$dist_asf = 12;
if (isset($_POST["asignar_asfalto"])) {
	 $dist_asf = $_POST["dist_asf"];
   $sql = "SELECT cod_cat FROM predios WHERE st_dwithin ((SELECT the_geom FROM calles WHERE observ ='290603'),the_geom,$dist_asf) AND activo = '1'";
	 $check_asf = pg_num_rows(pg_query($sql)); 	
	 if ($check_asf == 0) {		
	    $mensaje_asf = "No hay ningun predio a $dist_asf m distancia del asfalto!";
	 } else {
	    $mensaje_asf = "Hay $check_asf predio(s) a $dist_asf m distancia del asfalto!";
	    $result=pg_query($sql);
			$i = 0;
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
         foreach ($line as $col_value) {
            pg_query("UPDATE info_predio SET via_mat = 'ASF' WHERE cod_cat = '$col_value'");
						pg_query("INSERT INTO cambios (cod_cat, fecha_cambio, variable, valor_ant) VALUES ('$col_value','2010-01-01','via_mat','RIP')");
						$i++;
			   }
      } # END_OF_WHILE	
      pg_free_result($result); 
			$mensaje_asf = $mensaje_asf." Se ha modificado $i predio(s) en la base de datos."; 			
	 }
   ### REGISTRO ###
   $username = get_username($session_id);
   $accion_reg = "Asignar Material de Via";
   pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		         VALUES ('$username','$ip','$fecha','$hora','$accion_reg','-')");	 	
} */
################################################################################
#------------- ASIGNAR AGUA A LOS PREDIOS CERCA DE LA RED DE AGUA -------------#
################################################################################	
$dist_agua = 20;
if (isset($_POST["asignar_agua"])) {
	 $dist_agua = $_POST["dist_agua"];
   $sql = "SELECT cod_cat FROM predios WHERE st_dwithin ((SELECT ST_AsText(st_union (the_geom )) FROM objetos_linea WHERE id ='45'),the_geom,$dist_agua) AND activo = '1'";
	 $check_agua = pg_num_rows(pg_query($sql)); 	
	 if ($check_agua == 0) {		
	    $mensaje_agua = "No hay ningun predio a $dist_agua m distancia de la red de agua!";
	 } else {
	    $mensaje_agua = "Hay $check_agua predio(s) a $dist_agua m distancia de la red de agua!";
	    $result=pg_query($sql);
			$i = 0;
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
         foreach ($line as $col_value) {
            pg_query("UPDATE info_predio SET ser_agu = 'SI' WHERE cod_cat = '$col_value'");
#						pg_query("INSERT INTO cambios (cod_cat, fecha_cambio, variable, valor_ant) VALUES ('$col_value','2010-01-01','ser_agu','NO')");
						$i++;
			   }
      } # END_OF_WHILE	
      pg_free_result($result); 
			$mensaje_agua = $mensaje_agua." Se ha modificado $i predio(s) en la base de datos."; 			
	 }	
   ### REGISTRO ###
   $username = get_username($session_id);
   $accion_reg = "Asignar Agua";
   pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		         VALUES ('$username','$ip','$fecha','$hora','$accion_reg','-')");	 
}
################################################################################
#-------- ASIGNAR SERVICIO DE LUZ A LOS PREDIOS CERCA DE LA RED DE LUZ --------#
################################################################################	
$dist_luz = 25;
if (isset($_POST["asignar_luz"])) {
	 $dist_luz = $_POST["dist_luz"];
   $sql = "SELECT cod_cat FROM predios WHERE st_dwithin ((SELECT ST_AsText(st_union (the_geom )) FROM objetos_linea WHERE id ='55'),the_geom,$dist_luz) AND activo = '1'";
	 $check_luz = pg_num_rows(pg_query($sql)); 	
	 if ($check_luz == 0) {		
	    $mensaje_luz = "No hay ningun predio a $dist_luz m distancia de la red de luz!";
	 } else {
	    $mensaje_luz = "Hay $check_luz predio(s) a $dist_luz m distancia de la red de luz!";
	    $result=pg_query($sql);
			$i = 0;
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
         foreach ($line as $col_value) {
            pg_query("UPDATE info_predio SET ser_luz = 'SI' WHERE cod_cat = '$col_value'");
#						pg_query("INSERT INTO cambios (cod_cat, fecha_cambio, variable, valor_ant) VALUES ('$col_value','2010-01-01','ser_agu','NO')");
						$i++;
			   }
      } # END_OF_WHILE	
      pg_free_result($result); 
			$mensaje_luz = $mensaje_luz." Se ha modificado $i predio(s) en la base de datos."; 			
	 }	
   ### REGISTRO ###
   $username = get_username($session_id);
   $accion_reg = "Asignar Luz";
   pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		         VALUES ('$username','$ip','$fecha','$hora','$accion_reg','-')");	 
}
################################################################################
#-------------------------- SUBIR FOTOS ORIGINALES ----------------------------#
################################################################################	
#$dist_asf = 12;
if (isset($_POST["subir_fotos"])) {
   $sql = "SELECT cod_uv, cod_man, cod_pred FROM info_predio WHERE activo = '1' ORDER BY cod_uv, cod_man, cod_pred";
	 $check_pred = pg_num_rows(pg_query($sql)); 	
	 if ($check_pred == 0) {		
	    $mensaje_foto = "No se puede subir las fotos porque no hay ningun predio en la base de datos!";
	 } else {
	    $result=pg_query($sql);
			$predios_con_foto = $cantidad_de_fotos = 0;
			$i = 0;
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
         foreach ($line as $col_value) {
				    if ($i == 0) {
						   $cod_uv_temp = $col_value;
				    } elseif ($i == 1) {
						   $cod_man_temp = $col_value;
				    } else {
						   $cod_pred_temp = $col_value;
							 if ($cod_uv_temp < 10) {
							    $cod_uv_temp = "0".$cod_uv_temp;
							 }
							 if ($cod_man_temp < 10) {
							    $cod_man_temp = "0".$cod_man_temp;
							 }	
							 if ($cod_pred_temp < 10) {
							    $cod_pred_temp = "0".$cod_pred_temp;
							 }								 						 	   
							 $codigo_foto_orig = $cod_uv_temp.$cod_man_temp.$cod_pred_temp;					 							 							 			 
				       $cod_cat_nuevo = get_codcat ($cod_uv_temp, $cod_man_temp, $cod_pred_temp, 0, 0, 0);
#echo "COD_CAT: $cod_cat_temp<br />";
							 ########################################				 
				       #----- CHEQUEAR SI EXISTEN FOTOS ------#
							 ########################################
               $filename1 = "C:/apache/htdocs/$folder/fotos_orig/".$codigo_foto_orig."-1.JPG";
				       $filename1_nuevo = "C:/apache/htdocs/$folder/fotos/".$cod_cat_nuevo.".jpg";
               $filename2 = "C:/apache/htdocs/$folder/fotos_orig/".$codigo_foto_orig."-2.JPG";
				       $filename2_nuevo = "C:/apache/htdocs/$folder/fotos/".$cod_cat_nuevo."-A.jpg";
               $filename3 = "C:/apache/htdocs/$folder/fotos_orig/".$codigo_foto_orig."-3.JPG";
				       $filename3_nuevo = "C:/apache/htdocs/$folder/fotos/".$cod_cat_nuevo."-B.jpg";
               $filename4 = "C:/apache/htdocs/$folder/fotos_orig/".$codigo_foto_orig."-4.JPG";
				       $filename4_nuevo = "C:/apache/htdocs/$folder/fotos/".$cod_cat_nuevo."-C.jpg";
               $filename5 = "C:/apache/htdocs/$folder/fotos_orig/".$codigo_foto_orig."-5.JPG";
				       $filename5_nuevo = "C:/apache/htdocs/$folder/fotos/".$cod_cat_nuevo."-D.jpg";							 							 							 
#echo "NOMBRE DE FOTO 1: $filename1, NOMBRE DE FOTO 2: $filename2<br />";							 
               if (file_exists($filename1)) {
						      copy($filename1,$filename1_nuevo);
									$predios_con_foto++;
									$cantidad_de_fotos++;
               }
               if (file_exists($filename2)) {
						      copy($filename2,$filename2_nuevo);
									$cantidad_de_fotos++;
               }			
               if (file_exists($filename3)) {
						      copy($filename3,$filename3_nuevo);
									$cantidad_de_fotos++;
               }	
               if (file_exists($filename4)) {
						      copy($filename4,$filename4_nuevo);
									$cantidad_de_fotos++;
               }	
               if (file_exists($filename5)) {
						      copy($filename5,$filename5_nuevo);
									$cantidad_de_fotos++;
               }								 							 							 		
						   $i = -1;
            }
						$i++;
			   }
      } # END_OF_WHILE	
      pg_free_result($result); 
			$mensaje_foto = "Se ha(n) añadido $cantidad_de_fotos foto(s) de $predios_con_foto predio(s)."; 			
	 }
   ### REGISTRO ###
   $username = get_username($session_id);
   $accion_reg = "Subir Fotos Originales";
   pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		         VALUES ('$username','$ip','$fecha','$hora','$accion_reg','-')");	 
}			
################################################################################
################################################################################
#-------------------------------- FORMULARIOS ---------------------------------#
################################################################################	
################################################################################
$checked_pred = $checked_edif = $checked_acteco = $checked_vehic = "";
if ((isset($_POST["select"])) AND (($_POST["select"]) == "Predios")) { 
   $checked_pred = pg_escape_string('checked=\"checked\"');
} elseif ((isset($_POST["select"])) AND (($_POST["select"]) == "Edificaciones")) { 	
   $checked_edif = pg_escape_string('checked=\"checked\"');
} elseif ((isset($_POST["select"])) AND (($_POST["select"]) == "Actividades")) { 	
   $checked_acteco = pg_escape_string('checked=\"checked\"');
} elseif ((isset($_POST["select"])) AND (($_POST["select"]) == "VehÃ­culos")) { 	
   $checked_vehic = pg_escape_string('checked=\"checked\"');
} else {
   $checked_pred = pg_escape_string('checked=\"checked\"');
}
################################################################################
#---------------------------- FORMULARIO INICIAL ------------------------------#
################################################################################	
#if (!$manual) { # IF SUBIDO ARCHIVO CSV
#   if (!$tabla_rellenada){  # IF TABLA NO RELLENADA
	 
      echo "<td>\n";
      echo "  <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";	 
      # Fila 1
	    echo "      <tr height=\"40px\">\n";  
	    echo "         <td width=\"5%\"> &nbsp</td>\n";   #Col. 1 			
      echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"80%\" class=\"pageName\">\n";  #Col.2
	    echo "            Herramientas\n";                          
      echo "         </td>\n";
	    echo "         <td width=\"15%\"> &nbsp</td>\n";   #Col. 3 	 
      echo "      </tr>\n";
			if ($nivel == 2 OR $nivel == 5) {					
	       echo "      <tr height=\"40px\">\n";  
	       echo "         <td> &nbsp</td>\n";   #Col. 1
	       echo "         <td align=\"center\"> ATENCION: ALGUNAS DE LAS ACCIONES SOBRE-ESCRIBEN LOS VALORES ACTUALES Y SON IRREVERSIBLES! POR FAVOR, HAGA UNA COPIA DE SEGURIDAD DEL SISTEMA ANTES DE SEGUIR!</td>\n";   #Col. 1
			   echo "         <td> &nbsp</td>\n";   #Col. 1						 				 
         echo "      </tr>\n";			
         # Fila 2		
         echo "      <tr>\n";	
	       echo "         <td> &nbsp</td>\n";   #Col. 1   
	       echo "         <td>\n";   #Col. 2 								 						 			 
         echo "            <table border=\"1\" height=\"100%\" width=\"100%\" cellpadding=\"5\" class=\"header\" style=\"border-collapse:collapse;\">\n";
         echo "               <tr>\n";
         echo "                  <td align=\"center\" width=\"30%\" class=\"bodyTextD\">\n";	#Col. 1	   	 
	       echo "                     ACCION\n";	 
         echo "                  </td>\n";	
         echo "                  <td align=\"center\" width=\"70%\" class=\"bodyTextD\">\n";  #Col. 3	
	       echo "                     DESCRIPCION\n";
	       echo "                  </td>\n";		
         echo "               </tr>\n";	
	       echo "		            <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=25&id=$session_id\" accept-charset=\"utf-8\">\n";
         echo "               <tr>\n";
         echo "                  <td align=\"center\">\n";	#Col. 1	  	 
	       echo "                  <input name=\"asignar_colindantes\" type=\"submit\" class=\"smallText\" value=\"Asignar Colindantes\">\n";			 
				 echo "                  </td>\n";	
         echo "                  <td align=\"left\" class=\"bodyTextD\">\n";  #Col. 3	
	       echo "                     Se asignará colindantes con medidas a todos los predios que aún no cuentan con esa información.\n";
	       echo "                  </td>\n";		
         echo "               </tr>\n";													
	       echo "               </form>\n";
	       echo "		            <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=25&id=$session_id\" accept-charset=\"utf-8\">\n";
         echo "               <tr>\n";
         echo "                  <td align=\"center\">\n";	#Col. 1	  	 
	       echo "                  <input name=\"asignar_titularidad\" type=\"submit\" class=\"smallText\" value=\"Asignar Titularidad\">\n";			 
				 echo "                  </td>\n";	
         echo "                  <td align=\"left\" class=\"bodyTextD\">\n";  #Col. 3	
	       echo "                     Se asignará a la persona que vive en el predio su titularidad según los documentos que están registrados en el sistema (será Ocupante, Poseedor o Propietario).\n";
	       echo "                  </td>\n";		
         echo "               </tr>\n";													
	       echo "               </form>\n";				 
	       echo "		            <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=25&id=$session_id\" accept-charset=\"utf-8\">\n";
         echo "               <tr>\n";
         echo "                  <td align=\"center\">\n";	#Col. 1	   	 
	       echo "                     <input name=\"determinar_regimen\" type=\"submit\" class=\"smallText\" value=\"Determinar Regimen\">\n";	 
         echo "                  </td>\n";	
         echo "                  <td align=\"left\" class=\"bodyTextD\">\n";  #Col. 3	
	       echo "                     Se determinará el tipo de inmueble (SIN GEOMETRIA, TERRENO, CASA , PROP. HORIZONTAL).\n";
	       echo "                  </td>\n";		
         echo "               </tr>\n";													
	       echo "               </form>\n";
	   /*    echo "		            <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=25&id=$session_id\" accept-charset=\"utf-8\">\n";
         echo "               <tr>\n";
         echo "                  <td align=\"center\">\n";	#Col. 1	   	 
	       echo "                     <input name=\"asignar_via_mat\" type=\"submit\" class=\"smallText\" value=\"Asignar Material de Vía\">\n";	 
         echo "                  </td>\n";	
         echo "                  <td align=\"left\" class=\"bodyTextD\">\n";  #Col. 2
	       echo "                     Se asignará a los predios el material de vía según lo que está definido en la geometría \"Material de Via\"\n";
	       echo "                  </td>\n";		
         echo "               </tr>\n";
	       echo "               </form>\n";  */
	       echo "		            <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=25&id=$session_id\" accept-charset=\"utf-8\">\n";
         echo "               <tr>\n";
         echo "                  <td align=\"center\">\n";	#Col. 1	   	 
	       echo "                     <input name=\"asignar_agua\" type=\"submit\" class=\"smallText\" value=\"Asignar Servicio de Agua\">\n";	 
         echo "                  </td>\n";	
         echo "                  <td align=\"left\" class=\"bodyTextD\">\n";  #Col. 2
	       echo "                     Distancia del predio a la red de agua (en metros): &nbsp <input type=\"text\" name=\"dist_agua\" id=\"form_anadir4B\" class=\"navText\" maxlength=\"2\" value=\"$dist_agua\">\n";
	       echo "                  </td>\n";			
         echo "               </tr>\n";								
	       echo "               </form>\n";
	       echo "		            <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=25&id=$session_id\" accept-charset=\"utf-8\">\n";
         echo "               <tr>\n";
         echo "                  <td align=\"center\">\n";	#Col. 1	   	 
	       echo "                     <input name=\"asignar_luz\" type=\"submit\" class=\"smallText\" value=\"Asignar Servicio de Luz\">\n";	 
         echo "                  </td>\n";	
         echo "                  <td align=\"left\" class=\"bodyTextD\">\n";  #Col. 2
	       echo "                     Distancia del predio a la red de electricidad (en metros): &nbsp <input type=\"text\" name=\"dist_luz\" id=\"form_anadir4B\" class=\"navText\" maxlength=\"2\" value=\"$dist_luz\">\n";
	       echo "                  </td>\n";			
         echo "               </tr>\n";								
	       echo "               </form>\n";	
#	       echo "		            <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=25&id=$session_id\" accept-charset=\"utf-8\">\n";
#         echo "               <tr>\n";
#         echo "                  <td align=\"center\" width=\"30%\">\n";	#Col. 1	   	 
#	       echo "                     <input name=\"subir_fotos\" type=\"submit\" class=\"smallText\" value=\"Subir Fotos\">\n";	 
#         echo "                  </td>\n";	
#         echo "                  <td align=\"left\" width=\"70%\" class=\"bodyTextD\">\n";  #Col. 3	
#	       echo "                     Se subirán las fotos originales (formato UUMMPP-1 y UUMMPP-2) desde la carpeta ../fotos_orig asignando un código nuevo.\n";
#	       echo "                  </td>\n";		
#         echo "               </tr>\n";
#	       echo "               </form>\n";					 			 				 					 				 				 
         echo "            </table>\n";	
	       echo "         </td>\n";							
	       echo "         <td> &nbsp</td>\n";   #Col. 3  			 				 				 	                     
         echo "      </tr>\n";					 			 							 				 
			   ### AVISO ###
         echo "      <tr height=\"40px\">\n";	
	       echo "         <td> &nbsp</td>\n";   #Col. 1   
	       echo "         <td align=\"center\">\n";   #Col. 2 								 
	       echo "            <font color=\"orange\"> EL OPERADOR DEBE VERIFICAR LA INFORMACION GENERADA AUTOMATICAMENTE!!!</font>\n";		
	       echo "         </td>\n";							
	       echo "         <td> &nbsp</td>\n";   #Col. 3  			 				 				 	                     
         echo "      </tr>\n";				 
			}
			if (isset($_POST["asignar_colindantes"])) {
         echo "      <tr>\n";	
	       echo "         <td align=\"center\" colspan=\"3\"> $mensaje_col</td>\n";   #Col. 1-3
	       echo "      </tr>\n";	 
		  }
			if (isset($_POST["asignar_titularidad"])) {
         echo "      <tr>\n";	
	       echo "         <td align=\"center\" colspan=\"3\"> $mensaje_tit</td>\n";   #Col. 1-3
	       echo "      </tr>\n";	 
		  }				
			if (isset($_POST["determinar_regimen"])) {
         echo "      <tr>\n";	
	       echo "         <td align=\"center\" colspan=\"3\"> $mensaje_reg</td>\n";   #Col. 1-3
	       echo "      </tr>\n";	 
		  }							
	/*		if (isset($_POST["asignar_via_mat"])) {
         echo "      <tr>\n";	
	       echo "         <td align=\"center\" colspan=\"3\"> $mensaje_mat</td>\n";   #Col. 1-3
	       echo "      </tr>\n";	 
		  }  */
			if (isset($_POST["asignar_agua"])) {
         echo "      <tr>\n";	
	       echo "         <td align=\"center\" colspan=\"3\"> $mensaje_agua</td>\n";   #Col. 1-3
	       echo "      </tr>\n";	 
		  }
			if (isset($_POST["asignar_luz"])) {
         echo "      <tr>\n";	
	       echo "         <td align=\"center\" colspan=\"3\"> $mensaje_luz</td>\n";   #Col. 1-3
	       echo "      </tr>\n";	 
		  }									
			if (isset($_POST["subir_fotos"])) {
         echo "      <tr>\n";	
	       echo "         <td align=\"center\" colspan=\"3\"> $mensaje_foto</td>\n";   #Col. 1-3
	       echo "      </tr>\n";	 
		  }						 			
			###############################				
 /*     if ($error) {	 
	       #Fila 6
	       echo "      <tr>\n"; 
	       echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1   	  
	       echo "         <td align=\"center\" valign=\"center\">\n";   #Col. 2  
#      echo "         <font color=\"green\">Todas columnas se han llenado correctamente!</font> <br />\n";			 
         echo "         <font color=\"red\">$mensaje_de_error</font> <br />\n";				 	    	   
	       echo "         </td>\n";
	       echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 3   	 
         echo "      </tr>\n";
	    }	  */
      # Ultima Fila 
      echo "      <tr height=\"100%\"></tr>\n";			 
      echo "   </table>\n";
      echo "   <br />&nbsp;<br />\n";
      echo "</td>\n";				 	  	 
#	 }  
#}	 

?>