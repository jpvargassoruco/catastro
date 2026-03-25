<?php

# INDICE
# BOTON VER ACTIVADO/ NO ACTIVADO                LINEA  15
# OBTENER LOS REGISTROS DE LA BASE DE DATOS      LINEA 120
# FORMULARIO PARA MOSTRAR LOS REGISTROS          LINEA 152

if (check_user_level($user_id) == 5) {
} else die ("No tiene el permiso de acceder a la página solicitada!"); 

################################################################################
#--------------------- BOTON VER ACTIVADO/ NO ACTIVADO ------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Ver")) {
   $vista_inicial = false;
   $ver_usuario = $_POST["usuario"];
	 $ver_ip = $_POST["ip"];
	 $ver_fecha = $_POST["fecha"];
	 $ver_hora = $_POST["hora"]; 
   $ver_accion = $_POST["accion"];
	 $ver_cod_cat = $_POST["cod_cat"];
   $sql="SELECT * FROM registro";	
	 $where_clause = false;
	 if ($ver_usuario != "All") {
      $sql=$sql." WHERE usuario = '$ver_usuario'";
			$where_clause = true;
	 }
	 if ($ver_ip != "All") {
	    if ($where_clause) {  
			   $sql=$sql." AND ip = '$ver_ip'"; 
			} else { 
			   $sql=$sql." WHERE ip = '$ver_ip'";
				 $where_clause = true; 
			}
	 }
	 if ($ver_fecha != "All") {
	    if ($where_clause) {  
			   $sql=$sql." AND"; 
			} else { 
			   $sql=$sql." WHERE";
				 $where_clause = true; 
			}
			if ($ver_fecha == "Hoy") {
			   $selected_fecha[0] = "";
			   $selected_fecha[1] = pg_escape_string('selected = "selected"');				
			   $sql=$sql." fecha = '$fecha'";
			} elseif ($ver_fecha == "Dia") {
			   $selected_fecha[0] = "";
			   $selected_fecha[2] = pg_escape_string('selected = "selected"');				 
				 $timestamp = strtotime($fecha.' - 1 day');
         $fecha_reg = date('Y-m-d', $timestamp);
         $sql=$sql." fecha >= '$fecha_reg'";		
			} elseif ($ver_fecha == "Semana") {
			   $selected_fecha[0] = "";
			   $selected_fecha[3] = pg_escape_string('selected = "selected"');				 
				 $timestamp = strtotime($fecha.' - 1 week');
         $fecha_reg = date('Y-m-d', $timestamp);
         $sql=$sql." fecha >= '$fecha_reg'";					 
			} elseif ($ver_fecha == "2 Semanas") {
			   $selected_fecha[0] = "";
			   $selected_fecha[4] = pg_escape_string('selected = "selected"');				 
				 $timestamp = strtotime($fecha.' - 14 days');
         $fecha_reg = date('Y-m-d', $timestamp);
         $sql=$sql." fecha >= '$fecha_reg'";			 
			} elseif ($ver_fecha == "Mes") {
			   $selected_fecha[0] = "";
			   $selected_fecha[5] = pg_escape_string('selected = "selected"');				 
				 $timestamp = strtotime($fecha.' - 1 month');
         $fecha_reg = date('Y-m-d', $timestamp);
         $sql=$sql." fecha >= '$fecha_reg'";				
			} elseif ($ver_fecha == "6 Meses") {
			   $selected_fecha[0] = "";
			   $selected_fecha[6] = pg_escape_string('selected = "selected"');				 
				 $timestamp = strtotime($fecha.' - 6 months');
         $fecha_reg = date('Y-m-d', $timestamp);
         $sql=$sql." fecha >= '$fecha_reg'";						 
			} else {
			   $selected_fecha[0] = "";
			   $selected_fecha[7] = pg_escape_string('selected = "selected"');				
         $ano = $date['year']-1;
			   $fecha_ano =$ano."-".$date['mon']."-".$date['mday'];			
         $sql=$sql." fecha >= '$fecha_ano'";
			}
	 }	
	 if ($ver_hora != "All") {
	    if ($where_clause) {
			   $sql=$sql." AND"; 
			} else { 
			   $sql=$sql." WHERE";
				 $where_clause = true; 
			}
			if ($ver_hora == "MaÃ±anas") {
			   $selected_hora[0] = "";
			   $selected_hora[1] = pg_escape_string('selected = "selected"');				
			   $sql=$sql." hora >= '08:00:00' AND hora < '14:00:00'";
			} elseif ($ver_hora == "Tardes") {
			   $selected_hora[0] = "";
			   $selected_hora[2] = pg_escape_string('selected = "selected"');			
			   $sql=$sql." hora >= '14:00:00' AND hora < '20:00:00'";
			} else { 
			   $selected_hora[0] = "";
			   $selected_hora[3] = pg_escape_string('selected = "selected"');			
				 $sql=$sql." hora >= '20:00:00' OR hora < '08:00:00'";
			} 			
	 }
	 if ($ver_accion != "All") {
	    if ($where_clause) {  
			   $sql=$sql." AND accion = '$ver_accion'"; 
			} else { 
			   $sql=$sql." WHERE accion = '$ver_accion'";
				 $where_clause = true; 
			}
	 }
	 if ($ver_cod_cat != "All") {
	    if ($where_clause) {  
			   $sql=$sql." AND cod_cat = '$ver_cod_cat'"; 
			} else { 
			   $sql=$sql." WHERE cod_cat = '$ver_cod_cat'";
				 $where_clause = true; 
			}
	 }	 
	 $sql=$sql." ORDER BY fecha, hora";	    
} else {
   $vista_inicial = true;
   $sql="SELECT * FROM registro ORDER BY fecha, hora"; 
#	 $check_registros = pg_num_rows(pg_query($sql));	
#	 if ($check_registros > 14) {
#	    $timestamp = strtotime($fecha.' - 14 days');
#      $fecha_reg = date('Y-m-d', $timestamp);
#	    $sql="SELECT * FROM registro WHERE fecha > '$fecha_reg' ORDER BY fecha, hora";
#	 }
} 
################################################################################
#----------------- OBTENER LOS REGISTROS DE LA BASE DE DATOS ------------------#
################################################################################	

$result = pg_query($sql); 	  	 
$i = $j = 0;
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {      
   foreach ($line as $col_value) {
	    if ($j == 0) {
			   $col_value = textconvert($col_value);			          
			   $reg_usuario[$i] = $col_value;
			} elseif ($j == 1) {
			   $reg_ip[$i] = $col_value;								
      } elseif ($j == 2) {
			   $reg_fecha[$i] = $col_value;									
      } elseif ($j == 3) {
			   $reg_hora[$i] = $col_value;								
      } elseif ($j == 4) {
			   $col_value = textconvert($col_value);	
				 $reg_accion[$i] = $col_value;									
      } else {
			   $reg_cod_cat[$i] = $col_value;
				 $j = -1;										
      }
			$j++;
   }
	 $i++; 
} # END_OF_WHILE
$filas_registradas = $i;
################################################################################
#-------------------------------- FORMULARIO ----------------------------------#
################################################################################	
   echo "<td>\n";
   echo "  <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";
   # Fila 1
	 echo "      <tr height=\"40px\">\n";  
	 echo "         <td width=\"15%\"> &nbsp</td>\n";   #Col. 1 			
   echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n";  #Col.2
	 echo "            Registro de Acciones\n";                          
   echo "         </td>\n";
	 echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 	 
   echo "      </tr>\n";	
   # Fila 2
	 echo "			 <form name=\"form1\" method=\"post\" action=\"index.php?mod=98&id=$session_id\" accept-charset=\"utf-8\">\n";	
	 echo "      <tr>\n"; 	 
	 echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  15 Columnas	 
	 #TABLA FILA 1	 
	 echo "               <tr>\n";
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1 	 	 	 	  	 	     
	 echo "                  <td align=\"center\" width=\"4%\"><input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Ver\"></td>\n";   #Col. 2	
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 3 	     	  	 
	 echo "                  <td align=\"center\" width=\"22%\" class=\"bodyTextD\">\n";   #Col. 4	  
   echo "                     <select class=\"navText\" name=\"usuario\" size=\"1\">\n";
   echo "                        <option id=\"form0\" value=\"All\" selected=\"selected\"> Todos</option>\n"; 	 
   $sql="SELECT DISTINCT usuario FROM registro ORDER BY usuario";
   $result = pg_query($sql); 	  	 
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {      
      foreach ($line as $col_value) {  
         $col_value = textconvert ($col_value);	
		     echo "                   <option id=\"form0\" value=\"$col_value\"> $col_value</option>\n";
	    }
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 5     	  	 
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextD\">\n";   #Col. 6	  
   echo "                     <select class=\"navText\" name=\"ip\" size=\"1\">\n";
   echo "                        <option id=\"form0\" value=\"All\" selected=\"selected\"> Todos</option>\n"; 	 
   $sql="SELECT DISTINCT ip FROM registro ORDER BY ip";
   $result = pg_query($sql); 	  	 
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {      
      foreach ($line as $col_value) {  
         $col_value = textconvert ($col_value);
		     echo "                   <option id=\"form0\" value=\"$col_value\"> $col_value</option>\n";
	    }
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 7   	  	 
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextD\">\n";   #Col. 8	  
   echo "                     <select class=\"navText\" name=\"fecha\" size=\"1\">\n";
   echo "                        <option id=\"form0\" value=\"All\" $selected_fecha[0]>Todas</option>\n";
   echo "                        <option id=\"form0\" value=\"Hoy\" $selected_fecha[1]>Hoy</option>\n";
   echo "                        <option id=\"form0\" value=\"Dia\" $selected_fecha[2]>1 Día atrás</option>\n";	 
   echo "                        <option id=\"form0\" value=\"Semana\" $selected_fecha[3]>1 Semana atrás</option>\n";
   echo "                        <option id=\"form0\" value=\"2 Semanas\" $selected_fecha[4]>2 Semanas atrás</option>\n";	 
   echo "                        <option id=\"form0\" value=\"Mes\" $selected_fecha[5]>1 Mes atrás</option>\n";	 
   echo "                        <option id=\"form0\" value=\"6 Meses\" $selected_fecha[6]>6 Meses atrás</option>\n";	 	 
   echo "                        <option id=\"form0\" value=\"Ano\" $selected_fecha[7]>1 Año atrás</option>\n";	 	 
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 9     	  	 
	 echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextD\">\n";   #Col. 10	  
   echo "                     <select class=\"navText\" name=\"hora\" size=\"1\">\n";
   echo "                        <option id=\"form0\" value=\"All\" $selected_hora[0]>Todas</option>\n";
   echo "                        <option id=\"form0\" value=\"Mañanas\" $selected_hora[1]>8.00 - 14.00</option>\n";
   echo "                        <option id=\"form0\" value=\"Tardes\" $selected_hora[2]>14.00 - 20.00</option>\n";
   echo "                        <option id=\"form0\" value=\"Noches\" $selected_hora[3]>20.00 - 8.00</option>\n";	 	 
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n"; 		 		 	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 11 
	 echo "                  <td align=\"center\" width=\"30%\" class=\"bodyTextD\">\n";   #Col. 12	  
   echo "                     <select class=\"navText\" name=\"accion\" size=\"1\">\n";
   echo "                        <option id=\"form0\" value=\"All\" selected=\"selected\"> Todas</option>\n"; 	 
   $sql="SELECT DISTINCT accion FROM registro ORDER BY accion";
   $result = pg_query($sql); 	  	 
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {      
      foreach ($line as $col_value) {  
         $col_value = textconvert ($col_value);
		     echo "                   <option id=\"form0\" value=\"$col_value\"> $col_value</option>\n";
	    }
   } 	
   echo "                     </select>\n";		 
	 echo "                  </td>\n"; 		 		 	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 13 
	 echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextD\">\n";   #Col. 14	  
   echo "                     <select class=\"navText\" name=\"cod_cat\" size=\"1\">\n";
   echo "                        <option id=\"form0\" value=\"All\" selected=\"selected\"> Todos</option>\n"; 	 
   $sql="SELECT DISTINCT cod_cat FROM registro ORDER BY cod_cat";
   $result = pg_query($sql); 	  	 
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {      
      foreach ($line as $col_value) {  
         $col_value = textconvert ($col_value);
		     echo "                   <option id=\"form0\" value=\"$col_value\"> $col_value</option>\n";
	    }
   } 	
   echo "                     </select>\n";		 
	 echo "                  </td>\n";
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 15 	 	 	  	 	 	   	 	 	    
	 echo "               </tr>\n";	 
	 echo "            </table>\n";
	 #############################################################################		 
   # Fila 3	 	 	 
#	 echo "      <tr>\n"; 	 
#	 echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Acciones registradas en la base de datos</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  15 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"15\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"5%\" class=\"bodyTextH\">No.</td>\n";   #Col. 2
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 3		 
	 echo "                  <td align=\"center\" width=\"23%\" class=\"bodyTextH\">Usuario responsable</td>\n";   #Col. 4	 	    	  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 5	  	  	 
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">Número IP</td>\n";   #Col. 6	  
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 7	 	 
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextH\">Fecha</td>\n";   #Col. 8 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 9	 
	 echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextH\">Hora</td>\n";   #Col. 10 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 11	 
	 echo "                  <td align=\"center\" width=\"30%\" class=\"bodyTextH\">Acción registrada</td>\n";   #Col. 12 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 13 	 
	 echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">Variable</td>\n";   #Col. 14 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 15	   	 	   	 	 	    
	 echo "               </tr>\n";
	 $j = 1;
	 $i = $filas_registradas-1;
	 while ($i >= 0) {
	    echo "               <tr>\n"; 
	    echo "                  <td></td>\n";   #Col. 1	
	    echo "                  <td align=\"center\" class=\"bodyTextD\">$j</td>\n";   #Col. 2
	    echo "                  <td></td>\n";   #Col. 3		 
	    echo "                  <td align=\"center\" class=\"bodyTextD\">$reg_usuario[$i]</td>\n";   #Col. 4	 	    	  	 
	    echo "                  <td></td>\n";   #Col. 5			   	  	 
	    echo "                  <td align=\"center\" class=\"bodyTextD\">$reg_ip[$i]</td>\n";   #Col. 6	  
	    echo "                  <td></td>\n";   #Col. 7	 	 	 
	    echo "                  <td align=\"center\" class=\"bodyTextD\">$reg_fecha[$i]</td>\n";   #Col. 8 
	    echo "                  <td></td>\n";   #Col. 9		  
	    echo "                  <td align=\"center\" class=\"bodyTextD\">$reg_hora[$i]</td>\n";   #Col. 10 
	    echo "                  <td></td>\n";   #Col. 11	 
	    echo "                  <td align=\"center\" class=\"bodyTextD\">$reg_accion[$i]</td>\n";   #Col. 12 
	    echo "                  <td></td>\n";   #Col. 13 	 
	    echo "                  <td align=\"center\" class=\"bodyTextD\">$reg_cod_cat[$i]</td>\n";   #Col. 14 
	    echo "                  <td></td>\n";   #Col. 15	 	  	 	   	 	 	    
	    echo "               </tr>\n";
			$i--;
			$j++;	
			if (($j == 16) AND ($vista_inicial)) {
			   $i = -1;
			} 
	 }
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 	 
	 echo "         </td>\n"; 
	 echo "      </tr>\n";
	 echo "      </form>\n";	 
   # Ultima Fila 
	 if ($vista_inicial) {
      echo "      <tr>\n"; 	 
      echo "         <td align=\"center\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3 	 
      echo "            Solo mostrando los ultimos 15 registros. Para ver todos los registros pulse 'Ver'\n"; 		 
      echo "         </td>\n";
      echo "      </tr>\n";		 
   }
#	 echo "      <tr>\n"; 	 
#	 echo "         <td align=\"center\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3 	 
#	 echo "            <a href=\"javascript:print(this.document)\"><img border=\"0\" src=\"http://localhost/catastro_br/graphics/printer.png\" width=\"22\" height=\"22\"></a>\n"; 		 
#	 echo "         </td>\n";
#	 echo "      </tr>\n";	
   echo "      <tr height=\"100%\"></tr>\n";	   		 
   echo "   </table>\n";
   echo "   <br />&nbsp;<br />\n";
   echo "</td>\n"; 
?>