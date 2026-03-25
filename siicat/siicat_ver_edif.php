<?php

$mostrar = false; 
$resultado = false;
#$dos_resultados = false;
$error = false;
$aviso_geometria = false;
$predio_existe = false;
if (isset($_POST["search_string"])) {
   $search_string = $_POST["search_string"];
} else $search_string = "";
################################################################################
#---------------------- BOTONES ANTERIOR Y POSTERIOR --------------------------#
################################################################################	
# YA ESTA INCLUIDO EN catbr_lista.php en INDEX.PHP	

################################################################################
#-------------------- LEER DATOS DE TABLA INFO_EDIF ---------------------------#
################################################################################	
$columna_edi_num = 4;

$sql="SELECT * FROM info_edif WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' ORDER BY edi_num, edi_piso";
#$sql="SELECT * FROM info_edif WHERE id_inmu = '$id_inmu' ORDER BY edi_num, edi_piso";
$check = pg_num_rows(pg_query($sql));
#echo "EDIFICACIONES ENCONTRADAS: $check<br />\n";	 
if ($check == 0) {
   $sql="SELECT cod_uv FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
   $check_geo_edif = pg_num_rows(pg_query($sql));
	 if ($check_geo_edif > 0) {
	    $aviso_geometria = true;
			$mensaje_aviso = "Aviso: Hay una o más geometría de edificaciones sin datos!";		
			$no_de_edificaciones = 0;
	 } else { 
      $error = true;
      $mensaje_de_error = "No se encuentra ninguna información sobre edificaciones en la base de datos";
	    $no_de_edificaciones = 0;
	 }
} else {
   $result=pg_query($sql);
   $i = $j = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
      foreach ($line as $col_value) {
         $edi[$i][$j] = $col_value; 	
         $j++; 
      }
			$j=0;
	    $i++;	
   } # END_OF_WHILE		
	 pg_free_result($result);
   $no_de_edificaciones = $i;	 
   ########################################
   #----------- CALCULAR AREA ------------#
   ########################################	
	 $i = 0;
	 $area_total = 0;
	 while ($i < $no_de_edificaciones) {
	    $edi_num = $edi[$i][$columna_edi_num];
	    $edi_piso = $edi[$i][$columna_edi_num+1];	 
#echo "EDI_NUM es: $edi_num, EDI_PISO es: $edi_piso<br />\n";	 
	    #$sql="SELECT area(the_geom) FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND edi_num = '$edi_num' AND edi_piso = '$edi_piso'";
      $sql="SELECT area(the_geom) FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND edi_num = '$edi_num'";
			$check = pg_num_rows(pg_query($sql));
	    if ($check == 0) {
	       $area[$i] = 0;
				 $value = 0;
	    } else {
         $result=pg_query($sql);
         $value = pg_fetch_array($result, null, PGSQL_ASSOC);	 			           
         $area[$i] = ROUND($value['area'],2); 
				 pg_free_result($result);         
      }
			$i++;
			$area_total = $area_total+$value['area'];
   } # END_OF_WHILE	
#   $area_total = ROUND($area_total/10000,4);	# en hectareas
   $area_total = ROUND($area_total,2);	# en hectareas			
#echo "AREA_TOTAL: $area_total <br />\n";					
}

################################################################################
#---------------------------- BUSQUEDA TRANSMITIDA ----------------------------#
################################################################################	 
if ((isset($_POST["Submit"])) AND ((($_POST["Submit"]) == "Ver") OR(($_POST["Submit"]) == "Volver"))) {	 
   $mostrar = true;
	 $id_inmu = $_POST["id_inmu"];
}
################################################################################
#                        CHEQUEAR SI EXISTE INFO_PREDIO                        #
################################################################################	
$sql="SELECT cod_uv FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check_info_predio = pg_num_rows(pg_query($sql));
################################################################################
#                         CHEQUEAR SI EXISTE INFO_PREDIO                       #
################################################################################	
if ($check_info_predio > 0 ) {	 
      $resultado = true;
#			$factor_zoom = 2.1;
#			include "siicat_lista_datos.php";
	#	if ($check_predio > 0) {
	##		   pg_query("INSERT INTO temp_poly (edi_num, edi_piso, the_geom) SELECT edi_num, edi_piso, the_geom FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
	#		   pg_query("UPDATE temp_poly SET numero = 44 WHERE edi_num > '0'");
	#		}	 	
}
################################################################################
#------------------ CHEQUEAR SI EDIFICACIONES TIENEN GEOMETRIA ----------------#
################################################################################	
   $sql="SELECT cod_uv FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
   $check_geo_edif = pg_num_rows(pg_query($sql));
	 if (($no_de_edificaciones > 0) AND ($no_de_edificaciones > $check_geo_edif)) {
	    $aviso_geometria = true;
			$mensaje_aviso = "Aviso: Hay datos de edificaciones que no tienen geometría!";		
	 } elseif (($no_de_edificaciones > 0) AND ($no_de_edificaciones < $check_geo_edif)) {
	    $aviso_geometria = true;
			$mensaje_aviso = "Aviso: Hay edificaciones sin datos!";		
	 }
################################################################################
#------------------ CHEQUEAR SI EL PREDIO ESTA ACTIVO -------------------------#
################################################################################	
#$sql="SELECT activo FROM codigos WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
#$result_act = pg_query($sql);
#$act = pg_fetch_array($result_act, null, PGSQL_ASSOC);
#$activo = $act['activo'];
#pg_free_result($result_act);
################################################################################
#------------------ CHEQUEAR SI HAY EDIFICACIONES EN EL PREDIO ----------------#
################################################################################	
$mensaje_is = false;
if ((isset($_POST["submit"])) AND ($_POST["submit"] == "Detectar geometria")) {	
   $mensaje_is = true;
   $sql = "SELECT oid, cod_uv, cod_man, cod_pred, edi_num, edi_piso from edificaciones where (ST_intersects((SELECT the_geom FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'),the_geom))";
   $check_intersect_edif = pg_num_rows(pg_query($sql));
	 if ($check_intersect_edif == 0) {	
			$mensaje_intersect = "No se encontró ninguna edificación dentro del predio!"; 
	 } elseif ($check_intersect_edif == 1) {
      $result=pg_query($sql);
      $info_is = pg_fetch_array($result, null, PGSQL_ASSOC);
      $is_oid = $info_is['oid'];
      $is_cod_uv = $info_is['cod_uv'];
			$is_cod_man = $info_is['cod_man'];
			$is_cod_pred = $info_is['cod_pred'];
			$is_edi_num = $info_is['edi_num']; 
			$is_edi_piso = $info_is['edi_piso'];
			$is_cod_cat = get_codcat($is_cod_uv,$is_cod_man,$is_cod_pred,0,0,0); 
			$is_cod_edi = get_codedi($is_cod_cat,$is_edi_num,$is_edi_piso);		
			$mensaje_intersect = "Se encontró una edificación con el código $is_cod_edi en el predio!";
			pg_free_result($result);
	 } else {							 
	    $result=pg_query($sql);
      $i = $j = 0;
			$is_cod_edi = "";
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
         foreach ($line as $col_value) {
				    if ($i == 0) {
			         $is_oid[$j] = $col_value;	 
            } elseif ($i == 1) {
			         $is_cod_uv[$j] = $col_value;	 
            } elseif ($i == 2) {
			         $is_cod_man[$j] = $col_value;	
            } elseif ($i == 3) {
			         $is_cod_pred[$j] = $col_value;							 
            } elseif ($i == 4) {
			         $is_edi_num[$j] = $col_value;							  
            } else {	
			         $is_edi_piso[$j] = $col_value;
			         $is_cod_cat[$j] = get_codcat($is_cod_uv[$j],$is_cod_man[$j],$is_cod_pred[$j],0,0,0);					 
							 $temp = get_codedi($is_cod_cat[$j],$is_edi_num[$j],$is_edi_piso[$j]);
							 if ($j == 0) {
								  $is_cod_edi = $temp;
							 } else {
							    $is_cod_edi = $is_cod_edi.", ".$temp;
							 } 
						   $i = -1;
						}
						$i++;
				 }
				 $j++;
			}
      pg_free_result($result);
			$mensaje_intersect = "Se encontró $check_intersect_edif edificaciones con los códigos $is_cod_edi en el predio!";
   }
}																	
################################################################################
#------------------------------- GENERAR MAPFILE ------------------------------#
################################################################################	

include "siicat_generar_mapfile_edif.php";
	 

################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################		

#	 if (!$iframe) {
#	    echo "<td>\n";
	# }
	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
	 # Fila 1
	 if ($resultado) {	
	    $mod_lista = 20;   
#   include "siicat_lista_formulario.php";    
   # Fila 2
	    $x = $no_de_edificaciones;
	    $i = 0;
	    $j = $columna_edi_num;
	    $z = 1;
	    $accion = "Modificar";
	    if ($x == 0) {
	       $accion = "Añadir";
	       echo "      <tr>\n"; 	 
	       echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
         echo "         <fieldset><legend>Unidad Constructiva Nº 1</legend>\n";
	       echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  14 Columnas
	       echo "               <tr>\n";
	       echo "                  <td align=\"center\" class=\"bodyText\"><font color=\"red\"> No se encuentra ninguna información de edificaciones en la base de datos!</font></td>\n";   #Col. 1	
	       echo "               </tr>\n";
	       echo "            </table>\n"; 
	       echo "         </fieldset>\n";	 	 
         echo "         </td>\n"; 
	       echo "      </tr>\n";					 
	    } else {
	       echo "      <tr>\n"; 	 
	       echo "         <td align=\"right\" colspan=\"3\">\n";   #Col. 1+2+3 
				 ### VER EN siicat_lista_datos.php 
	       echo "            <a href=\"index.php?mod=5&inmu=$id_inmu&id=$session_id&zoom#tab-2\" alt='' title='Mostrar la númeracion de las edificaciones en el mapa'>Mostrar en mapa</a>&nbsp\n";	
         echo "         </td>\n"; 
	       echo "      </tr>\n";			
			}
	    while ($x > 0) {
	       ##################################################
	       #---------------- EDIFICACIONES -----------------#
	       ##################################################
	       echo "      <tr>\n"; 	 
	       echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	       echo "         <fieldset><legend>Unidad Constructiva Nº $z</legend>\n";
	       echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  14 Columnas
	       echo "               <tr>\n";
	       echo "                  <td align=\"right\" colspan=\"12\" class=\"bodyText_Small\"></td>\n";   #Col. 1	 
	       echo "               </tr>\n";	   
	       echo "               <tr>\n"; 
	       echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
         echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH_Small\">Edificación</td>\n";   #Col. 2 
	       $texto = textconvert(abr($edi[$i][$j]));
	       echo "                  <td align=\"left\" width=\"3%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 3 	 	  	                     	 
	       echo "                  <td align=\"center\" width=\"5%\" class=\"bodyTextH_Small\">Piso</td>\n";   #Col. 4	
	       $j++;
	       $texto = textconvert(abr($edi[$i][$j]));	     	  	 
	       echo "                  <td align=\"left\" width=\"3%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 5	  		 
         echo "                  <td align=\"center\" width=\"5%\" class=\"bodyTextH_Small\">Tipo</td>\n";   #Col. 6 
	       $j = $j+2;
	       $texto = textconvert(abr($edi[$i][$j]));	 
	       echo "                  <td align=\"left\" width=\"7%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 7 	 
	       echo "                  <td align=\"center\" width=\"18%\" class=\"bodyTextH_Small\">Estado de Conservación</td>\n";   #Col. 8	
	       $j++;
	       $texto = textconvert(abr($edi[$i][$j]));		     	  	 
	       echo "                  <td align=\"left\" width=\"10%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 9	   
	       echo "                  <td align=\"center\" width=\"16%\" class=\"bodyTextH_Small\">Año de Construcción</td>\n";   #Col. 10
	       $j++;
	       $texto = textconvert(abr($edi[$i][$j]));	 
	       echo "                  <td align=\"left\" width=\"5%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 11  
	       echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH_Small\">Superficie (m²)</td>\n";   #Col. 12		
	       echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD_Small\">&nbsp $area[$i]</td>\n";   #Col. 11						 
	       echo "                  <td width=\"1%\"></td>\n";   #Col. 14 	 	 	   	 	 	    
	       echo "               </tr>\n";
	       echo "            </table>\n";
	       ##################################################	 
	       echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  10 Columnas
	       echo "               <tr>\n";
	       echo "                  <td align=\"right\" colspan=\"10\" class=\"bodyText_Small\"></td>\n";   #Col. 1	 
	       echo "               </tr>\n";	
	       #TABLA FILA 1	 
	       echo "               <tr>\n";
	       echo "                  <td width=\"1%\"></td>\n";   #Col. 1 	 	 	 	  	 	     
	       echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH_Small\">Cimientos</td>\n";   #Col. 2	  
	       $j++;
	       $texto = textconvert(abr($edi[$i][$j]));		   	  	 
	       echo "                  <td align=\"left\" width=\"16%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 3	  
	       echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextH_Small\">Estructura</td>\n";   #Col. 4	
	       $j++;
	       $texto = textconvert(abr($edi[$i][$j]));		 	     	  	 
	       echo "                  <td align=\"left\" width=\"16%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 5	  
	       echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextH_Small\">Muros</td>\n";   #Col. 6	
	       $j++;
	       $texto = textconvert(abr($edi[$i][$j]));		     	  	 
	       echo "                  <td align=\"left\" width=\"16%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 7	  
	       echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH_Small\">Acab. Piso</td>\n";   #Col. 8	
	       $j++;
	       $texto = textconvert(abr($edi[$i][$j]));	  	  	 
	       echo "                  <td align=\"left\" width=\"16%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 9	   		 		 	 
	       echo "                  <td width=\"1%\"></td>\n";   #Col. 10  	 	 	   	 	 	    
	       echo "               </tr>\n";
	       ###### TABLA FILA 2 #######
	       echo "               <tr>\n";
	       echo "                  <td></td>\n";   #Col. 1 	 	 	 	  	 	     
	       echo "                  <td align=\"center\" class=\"bodyTextH_Small\">Revest. Int.</td>\n";   #Col. 2	
	       $j++;
	       $texto = textconvert(abr($edi[$i][$j]));		     	  	 
	       echo "                  <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 3  
	       echo "                  <td align=\"center\" class=\"bodyTextH_Small\">Revest. Ext.</td>\n";   #Col. 4	    	  	 
	       $j++;
	       $texto = textconvert(abr($edi[$i][$j]));		     	  	 
	       echo "                  <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 5	  
	       echo "                  <td align=\"center\" class=\"bodyTextH_Small\">Revest. Baño</td>\n";   #Col. 6	    	  	 
	       $j++;
	       $texto = textconvert(abr($edi[$i][$j]));		     	  	 
	       echo "                  <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 7	  
	       echo "                  <td align=\"center\" class=\"bodyTextH_Small\">Rev. Cocina</td>\n";   #Col. 8	    	  	 
	       $j++;
	       $texto = textconvert(abr($edi[$i][$j]));		     	  	 
	       echo "                  <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 9	   		 		 	 
	       echo "                  <td></td>\n";   #Col. 10 	 	 	   	 	 	    
	       echo "               </tr>\n";
	       ###### TABLA FILA 3 #######
	       echo "               <tr>\n";
	       echo "                  <td></td>\n";   #Col. 1 	 	 	 	  	 	     
	       echo "                  <td align=\"center\" class=\"bodyTextH_Small\">Cub. Estr.</td>\n";   #Col. 2	    	  	 
	       $j++;
	       $texto = textconvert(abr($edi[$i][$j]));		     	  	 
	       echo "                  <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 3  
	       echo "                  <td align=\"center\" class=\"bodyTextH_Small\">Cub. Techo</td>\n";   #Col. 4	    	  	 
	       $j++;
			   $texto = utf8_decode(abr($edi[$i][$j]));		     	  	 
	       echo "                  <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 5	  
	       echo "                  <td align=\"center\" class=\"bodyTextH_Small\">Cielo Raso</td>\n";   #Col. 6	    	  	 
	       $j++;
	       $texto = textconvert(abr($edi[$i][$j]));		     	  	 
	       echo "                  <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 7	  
	       echo "                  <td align=\"center\" class=\"bodyTextH_Small\">Cocina</td>\n";   #Col. 8	    	  	 
	       $j++;
	       $texto = textconvert(abr($edi[$i][$j]));		     	  	 
	       echo "                  <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 9	   		 		 	 
	       echo "                  <td></td>\n";   #Col. 10 	 	 	   	 	 	    
	       echo "               </tr>\n";	
	       ###### TABLA FILA 4 #######
	       echo "               <tr>\n";
	       echo "                  <td></td>\n";   #Col. 1 	 	 	 	  	 	     
	       echo "                  <td align=\"center\" class=\"bodyTextH_Small\">Baño</td>\n";   #Col. 2	    	  	 
	       $j++;
	       $texto = textconvert(abr($edi[$i][$j]));		     	  	 
	       echo "                  <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 3  
	       echo "                  <td align=\"center\" class=\"bodyTextH_Small\">Carpintería</td>\n";   #Col. 4	    	  	 
	       $j++;
	       $texto = textconvert(abr($edi[$i][$j]));		     	  	 
	       echo "                  <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 5	  
	       echo "                  <td align=\"center\" class=\"bodyTextH_Small\">Electrica</td>\n";   #Col. 6	    	  	 
	       $j++;
	       $texto = textconvert(abr($edi[$i][$j]));		     	  	 
	       echo "                  <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   #Col. 7	  
	       echo "                  <td align=\"center\" class=\"bodyTextD_Small\"> &nbsp</td>\n";   #Col. 8	 
         echo "                  <td align=\"left\" valign=\"bottom\" colspan=\"2\"> &nbsp </td>\n";   #Col. 9+10	  		 		 	 	 	   	 	 	    
	       echo "               </tr>\n";	  	 
	       echo "            </table>\n"; 
	       echo "         </fieldset>\n";	 	 
	       echo "         </td>\n"; 
	       echo "      </tr>\n";
	       $x--;
	       $i++;
	       $j = $columna_edi_num;
			   $z++;
	    } # END_OF_WHILE ($x > 0) 
	    if ($aviso_geometria) {
	       echo "      <tr>\n"; 	 
	       echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	       echo "         <font color=\"orange\"><b>$mensaje_aviso</b></font> <br />\n";				 	    
		     echo "         </td>\n"; 
         echo "      </tr>\n";
	    }
	    echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3 
	    echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  13 Columnas
	    echo "               <tr>\n";
      if (($nivel > 1) AND ($activo == 1)) {
	       if ($accion == "Añadir") {
			      $mod = 21;
			   } else $mod = 22;
	       if ($iframe) {
            echo "			 <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=$mod&id=$session_id&iframe\" accept-charset=\"utf-8\">\n";	
	       } else {
            echo "			 <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=$mod&id=$session_id\" accept-charset=\"utf-8\">\n";		 
	       } 	  	     
			   echo "                  <td align=\"center\" width=\"100%\">\n";   #Col. 1	 
#      echo "                     <input type=\"hidden\" name=\"accion\" value=\"$accion Edificaciones\">\n";					 
         echo "                     <input type=\"hidden\" name=\"cod_uv\" value=\"$cod_uv\">\n";	  		 
         echo "                     <input type=\"hidden\" name=\"cod_man\" value=\"$cod_man\">\n";	  		 
         echo "                     <input type=\"hidden\" name=\"cod_pred\" value=\"$cod_pred\">\n";			  		 
         echo "                     <input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";	  
	       echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"$accion Edificaciones\">&nbsp&nbsp\n";
	       echo "                  </td>\n";			
		     echo "                  </form>\n";
	    } else {
			   echo "                  <td align=\"right\" width=\"100%\"></td>\n";   #Col. 1	 	 
	    }		 
      echo "               </tr>\n";
	 /*   if (($nivel == 2) OR ($nivel == 5)) {
	       echo "               <tr>\n";
         echo "			             <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id#tab-2\" accept-charset=\"utf-8\">\n"; 	     
	       echo "                  <td align=\"center\" colspan=\"2\">\n";   #Col. 1+2 			 
         echo "                     <input type=\"hidden\" name=\"cod_uv\" value=\"$cod_uv\">\n";	  		 
         echo "                     <input type=\"hidden\" name=\"cod_man\" value=\"$cod_man\">\n";	  		 
         echo "                     <input type=\"hidden\" name=\"cod_pred\" value=\"$cod_pred\">\n";			  		 
         echo "                     <input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";	  
         echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Detectar geometria\">\n";
         echo "                  </td>\n";			
         echo "                  </form>\n";	 
         echo "               </tr>\n";
			   if ($mensaje_is) {
	          echo "               <tr>\n";     
	          echo "                  <td align=\"center\" colspan=\"2\">\n";   #Col. 1+2 			 	  
            echo "                     $mensaje_intersect\n";
            echo "                  </td>\n";			
            echo "               </tr>\n";			
			   }
	    }	   	*/ 
      echo "            </table>\n"; 	 	 		 
	    echo "         </td>\n";
	    echo "      </tr>\n";
	    echo "      <tr>\n"; 	 
	    echo "         <td align=\"left\" height=\"20\" colspan=\"3\"></td>\n";   #Col. 1+2+3 	
	    echo "      </tr>\n";
	 } else { # IF (!$resultado) {
      echo "			<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=3&id=$session_id&iframe\" accept-charset=\"utf-8\">\n";	 
	    echo "      <tr>\n";	
	    echo "         <td align=\"center\" colspan=\"3\"> No se encuentran ningunos datos relacionados con el código en la base de datos.</td>\n";   #Col. 1+2+3 
	    echo "      </tr>\n";	
		  echo "      <tr>\n";
			echo "         <td align=\"center\" colspan=\"2\"><input type='button' value='atrás' onClick='javascript:history.back();' style='font-family: Tahoma; font-size: 10pt; font-weight: bold'></td>\n";   #Col. 1+2	
	    echo "         <td align=\"center\"><input name=\"accion\" type=\"submit\" class=\"smallText\" value=\"Añadir Información del Terreno\"></td>\n";   #Col. 3		 
	    echo "      </tr>\n";
	    echo "      </form>\n";							 	
	 }
	 # Ultima Fila
#	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
	# echo "   <br />&nbsp;<br />\n";
	# if (!$iframe) {
	#    echo "</td>\n";
	# } 
?>