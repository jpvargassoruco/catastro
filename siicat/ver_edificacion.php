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
#-------------------- LEER DATOS DE TABLA INFO_EDIF ---------------------------#
################################################################################	
$columna_edi_num = 4;

$sql="SELECT * FROM info_edif WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' ORDER BY edi_num, edi_piso";
$check = pg_num_rows(pg_query($sql));
if ($check == 0) {
   $sql="SELECT cod_uv FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
   $check_geo_edif = pg_num_rows(pg_query($sql));
	 if ($check_geo_edif > 0) {
	    $aviso_geometria = true;
			$mensaje_aviso = "Aviso: Hay una o m²s geometría de edificaciones sin datos!";
			$no_de_edificaciones = 0;
	 } else { 
      $error = true;
      $mensaje_de_error = "No se encuentra ninguna información sobre edificaciones en la base de datos!!";
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
   } 	
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
   } 
   $area_total = ROUND($area_total,2);		
			
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
}
################################################################################
#------------------ CHEQUEAR SI EDIFICACIONES TIENEN GEOMETRIA ----------------#
################################################################################	
   $sql="SELECT cod_uv FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
   $check_geo_edif = pg_num_rows(pg_query($sql));
	 if (($no_de_edificaciones > 0) AND ($no_de_edificaciones > $check_geo_edif)) {
	    $aviso_geometria = true;
			$mensaje_aviso = "Aviso: Hay datos de edificaciones que no tienen geometr�a!";		
	 } elseif (($no_de_edificaciones > 0) AND ($no_de_edificaciones < $check_geo_edif)) {
	    $aviso_geometria = true;
			$mensaje_aviso = "Aviso: Hay edificaciones sin datos!";		
	 }
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
			$mensaje_intersect = "Se encontrá una edificación con el código $is_cod_edi en el predio!";
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
			$mensaje_intersect = "Se encontr� $check_intersect_edif edificaciones con los c�digos $is_cod_edi en el predio!";
   }
}																	
################################################################################
#------------------------------- GENERAR MAPFILE ------------------------------#
################################################################################	

include "siicat_generar_mapfile_edif.php";
	 

################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################		
	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
	 # Fila 1
	 if ($resultado) {	
	    $mod_lista = 20;   

	    $x = $no_de_edificaciones;
	    $i = 0;
	    $j = $columna_edi_num;
	    $z = 1;
	    $accion = "Modificar";
	    if ($x == 0) {
			$accion = "Añadir";
			echo "<tr>\n"; 	 
			echo "<td valign=\"top\" height=\"40\" colspan=\"3\">\n"; 
			echo "<fieldset><legend>Unidad Constructiva No 0</legend>\n";
				echo "<table border=\"0\" width=\"100%\">\n"; 
					echo "<tr>\n";
					echo "<td align=\"center\" class=\"alert alert-danger\"> $mensaje_de_error </font></td>\n";   #Col. 1	
					echo "</tr>\n";
				echo "</table>\n"; 
			echo "</fieldset>\n";	 	 
			echo "</td>\n"; 
			echo "</tr>\n";					 
	    } else {
			echo "<tr>\n"; 	 
			echo "<td align=\"right\" colspan=\"3\">\n"; 
			echo "<a href=\"index.php?mod=5&inmu=$id_inmu&id=$session_id&zoom#tab-2\" alt='' title='Mostrar la Nomeracion de las edificaciones en el mapa'>Mostrar en mapa</a>&nbsp\n";	
			echo "</td>\n"; 
			echo "</tr>\n";			
		}

	    while ($x > 0) {
			##################################################
			#---------------- EDIFICACIONES -----------------#
			##################################################
			echo "<tr>\n"; 	 
			echo "<td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
			echo "<fieldset><legend>Unidad Constructiva No $z</legend>\n";
				echo "<table border=\"0\" width=\"100%\">\n";  
					echo "<tr>\n";
					echo "<td align=\"right\" colspan=\"14\" class=\"bodyText_Small\"></td>\n"; 
					echo "</tr>\n";	   
					echo "<tr>\n"; 
						echo "<td width=\"1%\"></td>\n";   #Col. 1	
						echo "<td align=\"center\" width=\"8%\" class=\"bodyTextH_Small\">edificación</td>\n"; 
						$texto = textconvert(abr($edi[$i][$j]));
						echo "<td align=\"left\" width=\"3%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";  	  	                     	 
						echo "<td align=\"center\" width=\"5%\" class=\"bodyTextH_Small\">Piso</td>\n"; 
						$j++;
						$texto = textconvert(abr($edi[$i][$j]));	     	  	 
						echo "<td align=\"left\" width=\"3%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n"; 		 
						echo "<td align=\"center\" width=\"5%\" class=\"bodyTextH_Small\">Tipo</td>\n";
						$j = $j+2;
						$texto = textconvert(abr($edi[$i][$j]));	 
						echo "<td align=\"left\" width=\"7%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n"; 
						echo "<td align=\"center\" width=\"18%\" class=\"bodyTextH_Small\">Estado de Conservación</td>\n";
						$j++;
						$texto = textconvert(abr($edi[$i][$j]));		     	  	 
						echo "<td align=\"left\" width=\"10%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n"; 
						echo "<td align=\"center\" width=\"16%\" class=\"bodyTextH_Small\">Año de Construcción</td>\n"; 
						$j++;
						$texto = textconvert(abr($edi[$i][$j]));	 
						echo "<td align=\"left\" width=\"5%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n"; 
						echo "<td align=\"center\" width=\"12%\" class=\"bodyTextH_Small\">Superficie (m²)</td>\n";	
						echo "<td align=\"left\" width=\"6%\" class=\"bodyTextD_Small\">&nbsp $area[$i]</td>\n";				 
						echo "<td width=\"1%\"></td>\n"; 	 	 	   	 	 	    
					echo "</tr>\n";
				echo "</table>\n";

				echo "<table border=\"0\" width=\"100%\">\n"; 
					echo "<tr>\n";
					echo "<td align=\"right\" colspan=\"10\" class=\"bodyText_Small\"></td>\n";  
					echo "</tr>\n";	
					echo "<tr>\n";
						echo "<td width=\"1%\"></td>\n";   #Col. 1 	 	 	 	  	 	     
						echo "<td align=\"center\" width=\"8%\" class=\"bodyTextH_Small\">Cimientos</td>\n";   
						$j++;
						$texto = textconvert(abr($edi[$i][$j]));		   	  	 
						echo "<td align=\"left\" width=\"16%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";    
						echo "<td align=\"center\" width=\"9%\" class=\"bodyTextH_Small\">Estructura</td>\n";  
						$j++;
						$texto = textconvert(abr($edi[$i][$j]));		 	     	  	 
						echo "<td align=\"left\" width=\"16%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   
						echo "<td align=\"center\" width=\"9%\" class=\"bodyTextH_Small\">Muros</td>\n";   
						$j++;
						$texto = textconvert(abr($edi[$i][$j]));		     	  	 
						echo "<td align=\"left\" width=\"16%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   
						echo "<td align=\"center\" width=\"8%\" class=\"bodyTextH_Small\">Acab. Piso</td>\n";  
						$j++;
						$texto = textconvert(abr($edi[$i][$j]));	  	  	 
						echo "<td align=\"left\" width=\"16%\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";     		 		 	 
						echo "<td width=\"1%\"></td>\n";   #Col. 10  	 	 	   	 	 	    
					echo "</tr>\n";

					echo "<tr>\n";
						echo "<td></td>\n";   #Col. 1 	 	 	 	  	 	     
						echo "<td align=\"center\" class=\"bodyTextH_Small\">Revest. Int.</td>\n"; 
						$j++;
						$texto = textconvert(abr($edi[$i][$j]));		     	  	 
						echo "<td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n"; 
						echo "<td align=\"center\" class=\"bodyTextH_Small\">Revest. Ext.</td>\n"; 	    	  	 
						$j++;
						$texto = textconvert(abr($edi[$i][$j]));		     	  	 
						echo "<td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n"; 
						echo "<td align=\"center\" class=\"bodyTextH_Small\">Revest. BAño</td>\n";     	  	 
						$j++;
						$texto = textconvert(abr($edi[$i][$j]));		     	  	 
						echo "<td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n"; 
						echo "<td align=\"center\" class=\"bodyTextH_Small\">Rev. Cocina</td>\n"; 	  	 
						$j++;
						$texto = textconvert(abr($edi[$i][$j]));		     	  	 
						echo "<td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";  		 	 
						echo "<td></td>\n";   #Col. 10 	 	 	   	 	 	    
					echo "</tr>\n";

					echo "<tr>\n";
						echo "<td></td>\n";   #Col. 1 	 	 	 	  	 	     
						echo "<td align=\"center\" class=\"bodyTextH_Small\">Cub. Estr.</td>\n";    	  	 
						$j++;
						$texto = textconvert(abr($edi[$i][$j]));		     	  	 
						echo "<td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n"; 
						echo "<td align=\"center\" class=\"bodyTextH_Small\">Cub. Techo</td>\n";      	  	 
						$j++;
						$texto = utf8_decode(abr($edi[$i][$j]));		     	  	 
						echo "<td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   
						echo "<td align=\"center\" class=\"bodyTextH_Small\">Cielo Raso</td>\n";      	  	 
						$j++;
						$texto = textconvert(abr($edi[$i][$j]));		     	  	 
						echo "<td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n"; 
						echo "<td align=\"center\" class=\"bodyTextH_Small\">Cocina</td>\n";     	  	 
						$j++;
						$texto = textconvert(abr($edi[$i][$j]));		     	  	 
						echo "<td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";     		 		 	 
						echo "<td></td>\n";   #Col. 10 	 	 	   	 	 	    
					echo "</tr>\n";	

					echo "<tr>\n";
						echo "<td></td>\n"; 	 	 	 	  	 	     
						echo "<td align=\"center\" class=\"bodyTextH_Small\">BAño</td>\n";  	    	  	 
						$j++;
						$texto = textconvert(abr($edi[$i][$j]));		     	  	 
						echo "<td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";  
						echo "<td align=\"center\" class=\"bodyTextH_Small\">Carpintería</td>\n";     	  	 
						$j++;
						$texto = textconvert(abr($edi[$i][$j]));		     	  	 
						echo "<td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n"; 
						echo "<td align=\"center\" class=\"bodyTextH_Small\">Electrica</td>\n";       	  	 
						$j++;
						$texto = textconvert(abr($edi[$i][$j]));		     	  	 
						echo "<td align=\"left\" class=\"bodyTextD_Small\">&nbsp $texto</td>\n";   
						echo "<td align=\"center\" class=\"bodyTextD_Small\"> &nbsp</td>\n";   	 
						echo "<td align=\"left\" valign=\"bottom\" colspan=\"1\"> &nbsp </td>\n";
						echo "<td></td>\n";  	 		 	 	 	   	 	 	    
					echo "</tr>\n";	  	 
				echo "</table>\n"; 
			echo "</fieldset>\n";	 	 
			echo "</td>\n"; 
			echo "</tr>\n";
			$x--;
			$i++;
			$j = $columna_edi_num;
			$z++;
	    }
		if ($aviso_geometria) {
			echo "<tr>\n"; 	 
			echo "<td align=\"center\" height=\"20\" colspan=\"3\"  class=\"alert alert-danger\">$mensaje_aviso\n";   #Col. 1+2+3  	 			 			 	    
			echo "</td>\n"; 
			echo "</tr>\n";
	    }
	    echo "<tr>\n"; 	 
	    echo "<td align=\"center\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3 
	    echo "<table border=\"0\" width=\"100%\">\n";                     #TABLE  13 Columnas
	    echo "<tr>\n";
		if (($nivel > 1) AND ($activo == 1)) {
			if ($accion == "Añadir") {
				$mod = 21;
			} else $mod = 22;
				if ($iframe) {
				echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=$mod&id=$session_id&iframe\" accept-charset=\"utf-8\">\n";	
			} else {
				echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=$mod&id=$session_id\" accept-charset=\"utf-8\">\n";		 
			} 	  	     
			echo "<td align=\"center\" width=\"100%\">\n"; 
			echo "<input type=\"hidden\" name=\"cod_uv\" value=\"$cod_uv\">\n";	  		 
			echo "<input type=\"hidden\" name=\"cod_man\" value=\"$cod_man\">\n";	  		 
			echo "<input type=\"hidden\" name=\"cod_pred\" value=\"$cod_pred\">\n";			  		 
			echo "<input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";	  
			echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"$accion Edificaciones\">&nbsp&nbsp\n";
			echo "</td>\n";			
			echo "</form>\n";
	} else {
		echo "<td align=\"right\" width=\"100%\"></td>\n";   #Col. 1	 	 
	}		 
	echo "</tr>\n";
	echo "</table>\n"; 	 	 		 
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n"; 	 
	echo "<td align=\"left\" height=\"20\" colspan=\"3\"></td>\n";   #Col. 1+2+3 	
	echo "</tr>\n";
} else { 
	echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=3&id=$session_id&iframe\" accept-charset=\"utf-8\">\n";	 
	echo "<tr>\n";	
	echo "<td align=\"center\" colspan=\"3\"> No se encuentran ningunos datos relacionados con el código en la base de datos.</td>\n";   #Col. 1+2+3 
	echo "</tr>\n";	
	echo "<tr>\n";
	echo "<td align=\"center\" colspan=\"2\"><input type='button' value='atrás' onClick='javascript:history.back();' style='font-family: Tahoma; font-size: 10pt; font-weight: bold'></td>\n";   #Col. 1+2	
	echo "<td align=\"center\"><input name=\"accion\" type=\"submit\" class=\"smallText\" value=\"Añadir Información del Terreno\"></td>\n";   #Col. 3		 
	echo "</tr>\n";
	echo "</form>\n";							 	
}
		 
echo "</table>\n";

?>