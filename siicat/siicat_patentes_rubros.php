<?php

$ver_tabla = $tabla_ciiu = false;
$accion = "";

$i = 0;
while ($i < 10) {
   $selected_table[$i] = "";
	 $i++;
}

if (isset($_POST["gestion"])) {
   $gestion = $_POST["gestion"];
} else $gestion = $ano_actual;
$siguiente_ano = $gestion+1;
$gestion_actual = $ano_actual;
$no_de_gestiones = $ano_actual-2009;
$gestion_temp = $ano_actual+1;
$i = 0;
while ($i < $no_de_gestiones) {
   $gestion_temp = $gestion_temp-1;
   $gestion_lista[$i]	= $gestion_temp;
	 if ($gestion_temp == $gestion) {
      $selected_gestion[$i] = pg_escape_string('selected = "selected"');
   } else {
      $selected_gestion[$i] = "";
   }			
	 $i++;
}
################################################################################
#----------------------------- CIIU SELECCIONADO ------------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND ($_POST["submit"] == "CIIU")) { 
   $ver_tabla = $tabla_ciiu =true;		 
	 $legend = "Clasificación Industrial Internacional Uniforme (CIIU)";	
   $anadir = $modificar = $borrar = $error = false;
   if (isset($_POST["guardar2"])) {
	    $gestion = $_POST["gestion"];
		  $id_rubro_select = $_POST["id_rubro"];
		  $pat_max_mod = trim($_POST["pat_max"]);
      if (!check_int ($pat_max_mod)) {
	       $error = true;
				 $mensaje_de_error = "Error: El Patente Max. Anual tiene que ser un número!";
			} elseif ($pat_max_mod == "") {
				    $error = true;
						$mensaje_de_error = "Error: Tiene que especificar un porcentaje de exención entre 0 y 100%!";
		  } else {				 
         pg_query("UPDATE patentes_rubro_imp SET imp_max = '$pat_max_mod' WHERE id_rubro = '$id_rubro_select' AND gestion = '$gestion'");
	       ##### REGISTRO #####
				 $username = get_username($session_id);
				 $accion_reg = "Modificar Cobro Patente";
				 $valor_reg = $gestion;
		     pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		                  VALUES ('$username','$ip','$fecha','$hora','$accion_reg','$valor_reg')");	
			}
   }
      #$sql="SELECT id_rubro, codigo, act_rub, descrip FROM patentes_rubro ORDER BY codigo";
   $sql="SELECT id, nivel, descrip FROM ciiu ORDER BY id";			
#echo "SEL: $sql";
   $check_rubros = pg_num_rows(pg_query($sql));
   if ($check_rubros == 0) {					
	    $no_de_filas = 0;
   } else {
		  $no_de_filas = $check_rubros;
      $result = pg_query($sql);
      $i = $j = 0;
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            foreach ($line as $col_value) {
	             if ($i == 0) { 
							    $codigo[$j] = $col_value;					
	             } elseif ($i == 1) { 
							    $id_rubro[$j] = $col_value;
			         } else { 
			            $act_rub[$j] = utf8_decode($col_value);
				          $i = -1;
			         }
			         $i++;						 
            }
	          $j++;
      } 			
      pg_free_result($result);							  					 
	 }	
   if (isset($_POST["guardar"])) {
	    if (!isset($_POST["id_rubro"])) {
	       $error = true;
				 $mensaje_de_error = "Error: No ha seleccionado ningun rubro para modificar!";
		  } else {
			   $modificar = true;
		     $exen_accion = "Modificar";				 
			   $id_rubro_select = $_POST["id_rubro"];
         $sql="SELECT codigo, act_rub, descrip FROM patentes_rubro WHERE id_rubro = '$id_rubro_select'";
         $result = pg_query($sql);
         $info = pg_fetch_array($result, null, PGSQL_ASSOC);
			   $codigo_mod = $info['codigo'];
			   $act_rub_mod = utf8_decode($info['act_rub']);
			   $descrip_mod = utf8_decode($info['descrip']);
			   pg_free_result($result);
		     $sql2="SELECT imp_max FROM patentes_rubro_imp WHERE id_rubro = '$id_rubro_select' AND gestion = '$gestion'";
#echo "SEL: $sql2<br />";								 
		     $result2 = pg_query($sql2);
         $info2 = pg_fetch_array($result2, null, PGSQL_ASSOC);
		     $pat_max_mod = $info2['imp_max'];
			   pg_free_result($result2);
			}   		  				 	
   } 	 
}
################################################################################
#----------------------------- TABLA SELECCIONADA -----------------------------#
################################################################################	
elseif (isset($_POST["tabla"])) { 
   $valor = $_POST["tabla"];
   $ver_tabla = true;		 
   if ($valor == "a") {
			$clase = 1;
		  $selected_table[0] = pg_escape_string('selected = "selected"');
			$legend = "Patente de Funcionamiento";			
	 } elseif ($valor == "b") {
	    $clase = 2;
		  $selected_table[1] = pg_escape_string('selected = "selected"');	
			$legend = "Patente a la Publicidad y Propaganda";
	 } elseif ($valor == "c") {
	    $clase = 3;
		  $selected_table[2] = pg_escape_string('selected = "selected"');	
			$legend = "Patente a la extración de agregados de la construcción";
	 } elseif ($valor == "d") {
	    $clase = 4;
		  $selected_table[3] = pg_escape_string('selected = "selected"');	
			$legend = "Patente a los espectaculos y recreaciones";							
	 }
   $anadir = $modificar = $borrar = $error = false;
   if (isset($_POST["guardar2"])) {
	    $gestion = $_POST["gestion"];
		  $id_rubro_select = $_POST["id_rubro"];
		  $pat_max_mod = trim($_POST["pat_max"]);
      if (!check_int ($pat_max_mod)) {
	       $error = true;
				 $mensaje_de_error = "Error: El Patente Max. Anual tiene que ser un número!";
			} elseif ($pat_max_mod == "") {
				    $error = true;
						$mensaje_de_error = "Error: Tiene que especificar un porcentaje de exención entre 0 y 100%!";
		  } else {				 
         pg_query("UPDATE patentes_rubro_imp SET imp_max = '$pat_max_mod' WHERE id_rubro = '$id_rubro_select' AND gestion = '$gestion'");
	       ##### REGISTRO #####
				 $username = get_username($session_id);
				 $accion_reg = "Modificar Cobro Patente";
				 $valor_reg = $gestion;
		     pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		                  VALUES ('$username','$ip','$fecha','$hora','$accion_reg','$valor_reg')");	
			}
   }
      #$sql="SELECT id_rubro, codigo, act_rub, descrip FROM patentes_rubro ORDER BY codigo";
   $sql="SELECT id_rubro, codigo, act_rub, descrip FROM patentes_rubro WHERE clase = '$clase' ORDER BY id_rubro";			
#echo "SEL: $sql";
   $check_rubros = pg_num_rows(pg_query($sql));
   if ($check_rubros == 0) {					
	    $no_de_filas = 0;
   } else {
		  $no_de_filas = $check_rubros;
      $result = pg_query($sql);
      $i = $j = 0;
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            foreach ($line as $col_value) {
	             if ($i == 0) { 
							    $id_rubro[$j] = $col_value;						
	             } elseif ($i == 1) { 
							    $codigo[$j] = $col_value;
		           } elseif ($i == 2) { 
							    $act_rub[$j] = utf8_decode($col_value);
			         } else { 
			            $descripcion[$j] = utf8_decode($col_value);
						      $sql2="SELECT imp_max FROM patentes_rubro_imp WHERE id_rubro = '$id_rubro[$j]' AND gestion = '$gestion'";
#echo "SEL: $sql2<br />";								 
							    $result2 = pg_query($sql2);
                  $info2 = pg_fetch_array($result2, null, PGSQL_ASSOC);
			            $patente_max[$j] = $info2['imp_max'];
								  pg_free_result($result2);
				          $i = -1;
			         }
			         $i++;						 
            }
	          $j++;
      } 			
      pg_free_result($result);							  					 
	 }	
   if (isset($_POST["guardar"])) {
	    if (!isset($_POST["id_rubro"])) {
	       $error = true;
				 $mensaje_de_error = "Error: No ha seleccionado ningun rubro para modificar!";
		  } else {
			   $modificar = true;
		     $exen_accion = "Modificar";				 
			   $id_rubro_select = $_POST["id_rubro"];
         $sql="SELECT codigo, act_rub, descrip FROM patentes_rubro WHERE id_rubro = '$id_rubro_select'";
         $result = pg_query($sql);
         $info = pg_fetch_array($result, null, PGSQL_ASSOC);
			   $codigo_mod = $info['codigo'];
			   $act_rub_mod = utf8_decode($info['act_rub']);
			   $descrip_mod = utf8_decode($info['descrip']);
			   pg_free_result($result);
		     $sql2="SELECT imp_max FROM patentes_rubro_imp WHERE id_rubro = '$id_rubro_select' AND gestion = '$gestion'";
#echo "SEL: $sql2<br />";								 
		     $result2 = pg_query($sql2);
         $info2 = pg_fetch_array($result2, null, PGSQL_ASSOC);
		     $pat_max_mod = $info2['imp_max'];
			   pg_free_result($result2);
			}   		  				 	
   } 		  
}
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	

echo "<td>\n";
echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
# Fila 1
echo "      <tr height=\"40px\">\n";
#echo "         <td> &nbsp</td>\n";   #Col. 1 	    
echo "         <td colspan=\"4\" align=\"center\" valign=\"center\" height=\"40\" width=\"80%\" class=\"pageName\">\n"; 
echo "            Rubros de Patentes\n";                          
echo "         </td>\n";
#echo "         <td> &nbsp</td>\n";   #Col. 3 			 
echo "      </tr>\n";	

# Fila 1
echo "      <tr>\n";    
echo "         <td colspan=\"4\"> &nbsp</td>\n";  #Col. 1-3	 
echo "      </tr>\n";
# Fila 2	 
echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=106&id=$session_id\" accept-charset=\"utf-8\">\n";	 	
echo "      <tr height=\"40\">\n";
echo "         <td width=\"10%\"> &nbsp</td>\n";   #Col. 1  	 
echo "         <td width=\"20%\" valign=\"top\">\n";   #Col. 2
echo "         <fieldset><legend>Clasificación CIIU</legend>\n"; 
echo "            <table border=\"0\" width=\"100%\">\n";   # 6 TColumnas
echo "               <tr>\n";	
echo "                  <td align=\"center\" width=\"11%\">\n"; #TCol. 2
echo "                     <input name=\"accion\" type=\"hidden\" value=\"tablas\">\n";	 
echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"CIIU\">\n";
echo "                  </td>\n";
echo "               </tr>\n";
echo "            </table>\n"; 
#echo "         </form>\n";
echo "         </fieldset>\n";	 
#echo "         <td> &nbsp</td>\n";   #Col. 1  	 
echo "         <td width=\"60%\" valign=\"top\">\n";   #Col. 2
echo "            <fieldset><legend>Rubros de Patentes $municipio</legend>\n";
#echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=104&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
echo "            <table border=\"0\" width=\"100%\">\n";   # 6 TColumnas
echo "               <tr>\n";			  	 
echo "                  <td align=\"right\" width=\"8%\"> Tabla: </td>\n";   #TCol. 3 	     	  	 
echo "                  <td align=\"center\" width=\"52%\" class=\"bodyTextD\">\n";   #TCol. 4	  
echo "                     <select class=\"navText\" name=\"tabla\" size=\"1\">\n";                      	 
echo "                        <option id=\"form0\" value=\"a\" $selected_table[0]>1. Patente de Funcionamiento</option>\n";  
echo "                        <option id=\"form0\" value=\"b\" $selected_table[1]> 2. Patente a la Publicidad y Propaganda</option>\n";     
echo "                        <option id=\"form0\" value=\"c\" $selected_table[2]> 3. Patente a la extración de agregados de la construcción</option>\n";
echo "                        <option id=\"form0\" value=\"d\" $selected_table[3]> 4. Patente a los espectaculos y recreaciones</option>\n";	 	 	 
echo "                     </select>\n";	  	 
echo "                  </td>\n";	
echo "                  <td width=\"1%\"> &nbsp </td>\n";   #TCol. 1 	  	  	 	     
echo "                  <td align=\"center\" width=\"11%\">\n"; #TCol. 2
echo "                     <input name=\"accion\" type=\"hidden\" value=\"tablas\">\n";	 
echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Ver\">\n";
echo "                  </td>\n";   	
echo "                  <td width=\"6%\"></td>\n";   #TCol. 5 
#	 echo "                  <td align=\"center\" width=\"35%\">\n";   #TCol. 6  
#	 echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Ingresar Cotización\">\n";	 
#	 echo "                  </td>\n"; 
echo "               </tr>\n";
echo "            </table>\n"; 
echo "         </fieldset>\n";
echo "         </td>\n";
echo "         <td width=\"10%\"> &nbsp</td>\n";   #Col. 3 		 
echo "      </tr>\n";
echo "      </form>\n"; 
# Fila
echo "      <tr height=\"15px\">\n";
echo "         <td colspan=\"4\"> &nbsp</td>\n";   #Col. 1 	    	 
echo "      </tr>\n";	
################################################################################ 
if ($ver_tabla) {	 # TABLA A
	 # Fila 4 
  # if (($nuevos_valores) AND ($nivel >= 4)) {
   #   echo "			 <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";		 
	 #}
	 echo "			<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=104&id=$session_id#mod\" accept-charset=\"utf-8\">\n";	
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td colspan=\"2\" valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>$legend</legend>\n";	    
 	 echo "      	     <table id=\"registros\">\n";
 	 echo "               <tr>\n";
   echo "                  <th width=\"5%\"> &nbsp</th>\n";	 
   echo "                  <th width=\"15%\"> CODIGO</th>\n";
 	 echo "                  <th width=\"80%\"> ACTIVIDAD</th>\n";
 	 echo "               </tr>\n";
	 if ($check_rubros != 0) {
	    $i = 0;
	    while ($i < $no_de_filas) { 
         echo "               <tr>\n";
			   if ((!$modificar) AND ($nivel > 3)) {
	          if ($j == 0){
			         echo "                   <td class=\"bodyTextD_Small\"><input name=\"id_rubro\" value=\"$id_rubro[$i]\" type=\"radio\" checked=\"checked\"></td>\n";   #Col. 1
						   $j++;
		        } else {
			         echo "                   <td class=\"bodyTextD_Small\"><input name=\"id_rubro\" value=\"$id_rubro[$i]\" type=\"radio\"></td>\n";   #Col. 1						 
			      }	 
	       }	else {
			      if ((($modificar) OR ($borrar)) AND ($nivel > 3)) {
               if ($id_rubro[$i] == $id_rubro_select) { 
			            echo "                   <td><font color=red size=4> ></font></td>\n";	 
			         } else {
                  echo "                   <td> &nbsp</td>\n";	 
			         }
            } else {
               echo "                  <td> &nbsp</td>\n";   #Col. 1	
				    }			
			   }
				 #$clase = $id_rubro[$i];
				 if (($tabla_ciiu) AND ($id_rubro[$i] == 1)) {				 
           echo "                  <td class=\"alt\"><b>$codigo[$i]</b></td>\n";
				 } elseif (($tabla_ciiu) AND ($id_rubro[$i] == 2)) {				 
           echo "                  <td class=\"alt\">&nbsp $codigo[$i]</td>\n";
				 } elseif (($tabla_ciiu) AND ($id_rubro[$i] == 3)) {				 
           echo "                  <td class=\"alt\">&nbsp&nbsp&nbsp $codigo[$i]</td>\n";
				 } elseif (($tabla_ciiu) AND ($id_rubro[$i] == 4)) {				 
           echo "                  <td class=\"alt\"><i>&nbsp&nbsp&nbsp&nbsp&nbsp $codigo[$i]</i></td>\n";
				 } elseif (($tabla_ciiu) AND ($id_rubro[$i] == 5)) {				 
           echo "                  <td class=\"alt\"><i>&nbsp&nbsp&nbsp&nbsp $codigo[$i]</i></td>\n";					 					 
				 } else {
				   echo "                  <td>$codigo[$i]</td>\n"; 
				 }
				 if (($tabla_ciiu) AND ($id_rubro[$i] == 1)) {
 	          echo "                  <td class=\"alt\"><b>$act_rub[$i]</b></td>\n";	
				 } elseif (($tabla_ciiu) AND ($id_rubro[$i] == 2)) {	
 	          echo "                  <td class=\"alt\">&nbsp $act_rub[$i]</td>\n";			
				 } elseif (($tabla_ciiu) AND ($id_rubro[$i] == 3)) {	
 	          echo "                  <td class=\"alt\">&nbsp&nbsp&nbsp $act_rub[$i]</td>\n";		
				 } elseif (($tabla_ciiu) AND ($id_rubro[$i] == 4)) {	
 	          echo "                  <td class=\"alt\"><i>&nbsp&nbsp&nbsp&nbsp&nbsp $act_rub[$i]</i></td>\n";
				 } elseif (($tabla_ciiu) AND ($id_rubro[$i] == 5)) {	
 	          echo "                  <td class=\"alt\"><i>&nbsp&nbsp&nbsp&nbsp $act_rub[$i]</i></td>\n";																						 						
				 } else {		
 	          echo "                  <td class=\"alt\"><b>$act_rub[$i]</b><br />$descripcion[$i]</td>\n";	
				 }										  
 	       echo "               </tr>\n";
			   $i++;
			}
      #echo "               <tr class=\"alt\">\n";
      #echo "                  <td>$codigo[$i]</td>\n";
 	    #echo "                  <td>$descripcion[$i]</td>\n";
 	    #echo "                  <td>$patente_max[$i]</td>\n";					  
 	    #echo "               </tr>\n";
	 } else {
 	    echo "               <tr>\n"; 
 	    echo "                  <td align=\"center\" colspan=\"3\"> No hay registros en la base de datos!\n"; 
	    echo "                  </td>\n";	
	    echo "               </tr>\n";		 
	 }	 
 	 echo "            </table>\n";	 
	 echo "          </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 	   		
	 echo "      </tr>\n";	
	 if ($error) {
	 	  echo "      <tr>\n"; 	 
	    echo "         <td>&nbsp</td>\n";   #Col. 1 	 			
	    echo "         <td align=\"center\">\n";   #Col. 2
      echo "            <font color=\"red\">$mensaje_de_error</font>\n";	 
	    echo "         </td>\n";	
	    echo "         <td>&nbsp</td>\n";   #Col. 3 				 	 	   		
	    echo "      </tr>\n";	
   }		 	 
	 if ($nivel >= 4) {
      echo "			<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=104&id=$session_id\" accept-charset=\"utf-8\">\n";		 
			if ($modificar) {
		     echo "      <tr>\n";
         echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	       echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	       echo "         <fieldset><legend>Modificar valor para la gestión $gestion</legend>\n";	    
 	       echo "      	     <table id=\"registros\">\n";
 	       echo "               <tr>\n";
         echo "                  <th width=\"5%\"> <A name=\"mod\">&nbsp</A></th>\n";	 
         echo "                  <th width=\"12%\"> CODIGO</th>\n";
 	       echo "                  <th width=\"65%\"> ACTIVIDAD</th>\n";
 	       echo "                  <th width=\"18%\"> PATENTE MAX. ANUAL (EN BS)</th>\n"; 
 	       echo "               </tr>\n";
         echo "               <tr>\n";
         echo "                  <td>&nbsp</td>\n";	 			 
         echo "                  <td>$codigo_mod</td>\n";
 	       echo "                  <td class=\"alt\"><b>$act_rub_mod</b><br />$descrip_mod</td>\n";
	       echo "                  <td><input name=\"pat_max\" id=\"form_anadir1\" class=\"smallText\" maxlength=\"5\" value=\"$pat_max_mod\"></td>\n";					  
 	       echo "               </tr>\n";
 	       echo "            </table>\n";
 	       echo "      	     <table border=\"0\" align=\"center\" cellpadding=\"0\">\n";				 				 
	       echo "               <tr height=\"40\">\n"; 	                     		  	                     
	       echo "                  <td align=\"center\">\n";   #Col. 2
         echo "                     <input name=\"id_rubro\" type=\"hidden\" value=\"$id_rubro_select\">\n";				 
         echo "                     <input name=\"guardar2\" type=\"submit\" class=\"smallText\" value=\"$exen_accion\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";	
         echo "                     <input name=\"\" type=\"submit\" class=\"smallText\" value=\"NO $exen_accion\">\n";				 
	       echo "                  </td>\n";		 
	       echo "               </tr>\n";				 
 	       echo "            </table>\n";	 
	       echo "          </fieldset>\n";
	       echo "         </td>\n";
	       echo "         <td> &nbsp</td>\n";   #Col. 3 		 	   		
	       echo "      </tr>\n";		 
	/*       echo "      <tr>\n";
         echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	       echo "         <td align=\"center\" valign=\"top\" height=\"40\">\n";   #Col. 2  
	       echo "            <table border=\"0\" width=\"97%\">\n";  #TABLE 9 Columnas   						
	       echo "               <tr>\n";  	                     
	       echo "                  <td width=\"4%\"></td>\n";   #Col. 1		  	                     
	       echo "                  <td width=\"30%\" align=\"center\" class=\"bodyTextD\">\n";   #Col. 2	
         echo "                     <input name=\"ley\" id=\"form_anadir1\" class=\"smallText\" maxlength=\"24\" value=\"$ley_mod\">\n";   		
	       echo "                  </td>\n"; 
	       echo "                  <td width=\"1%\"></td>\n";   #Col. 3	   
	       echo "                  <td width=\"12%\" align=\"center\" class=\"bodyTextD\">\n";   #Col. 4  
         echo "                     <input name=\"fecha_exen\" id=\"form_anadir1\" class=\"smallText\" maxlength=\"10\" value=\"$fecha_exen_mod\">\n";
	       echo "                  </td>\n"; 
	       echo "                  <td width=\"1%\"></td>\n";   #Col. 5  	 
	       echo "                  <td width=\"40%\" align=\"center\" class=\"bodyTextD\">\n";   #Col. 6  
         echo "                     <input name=\"descripcion\" id=\"form_anadir1\" class=\"smallText\" maxlength=\"33\" value=\"$descripcion_mod\">\n";
	       echo "                  </td>\n";
	       echo "                  <td width=\"1%\"></td>\n";   #Col. 7  	 
	       echo "                  <td width=\"10%\" align=\"center\" class=\"bodyTextD\">\n";   #Col. 8  	 
         echo "                     <input name=\"porcentaje\" id=\"form_anadir1\" class=\"smallText\" maxlength=\"5\" value=\"$porcentaje_mod\">\n";		  
	       echo "                  </td>\n";	
         echo "                  <td width=\"1%\">%</td>\n";   #Col. 9								 	   		 	   	 	 	    
	       echo "               </tr>\n";	
	       echo "               <tr>\n";  	                     		  	                     
	       echo "                  <td align=\"center\" height=\"40\" colspan=\"9\">\n";   #Col. 2
         echo "                     <input name=\"no_select\" type=\"hidden\" value=\"$no_select\">\n";				 
         echo "                     <input name=\"guardar2\" type=\"submit\" class=\"smallText\" value=\"$exen_accion\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";	
         echo "                     <input name=\"\" type=\"submit\" class=\"smallText\" value=\"NO $exen_accion\">\n";				 
	       echo "                  </td>\n";
	       echo "               </tr>\n";				 		  				 	
	       echo "            </table>\n";  
	       echo "         </td>\n";
	       echo "         <td> &nbsp</td>\n";   #Col. 3 		 	   		
	       echo "      </tr>\n";	 */				 	
			} else {
	       echo "      <tr>\n";
         echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	       echo "         <td colspan=\"2\" align=\"center\" height=\"40\">\n";   #Col. 2
         echo "            <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Añadir\">&nbsp&nbsp&nbsp&nbsp\n";
				 if ($check_rubros > 0) {													
            echo "            <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Modificar\">&nbsp&nbsp&nbsp&nbsp\n";
            echo "            <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Borrar\">\n";
				 }					 
			   echo "         </td>\n";	 
	       echo "         <td> &nbsp</td>\n";   #Col. 3 		 	   		
	       echo "      </tr>\n";
			}
     # echo "            <input name=\"gestion\" type=\"hidden\" value=\"$gestion\">\n";
			if (!$tabla_ciiu) {
         echo "            <input name=\"tabla\" type=\"hidden\" value=\"$valor\">\n";
			}
      echo "            <input name=\"accion\" type=\"hidden\" value=\"$accion\">\n";	
      echo "            <input name=\"submit\" type=\"hidden\" value=\"Ver\">\n";				
	    echo "      </form>\n";
   }	 	  	 
}
################################################################################ 
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
	 echo "   <br />&nbsp;<br />\n";
	 echo "</td>\n";
################################################################################ 	 	  
?>
