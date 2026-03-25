<?php

################################################################################
#---------------------------- LISTA DE GESTIONES ------------------------------#
################################################################################	

if (isset($_POST["gestion"])) {
   $gestion = $_POST["gestion"];
} else $gestion = $ano_actual-1;
$siguiente_ano = $gestion+1;
$gestion_actual = $ano_actual-1;
$no_de_gestiones = $ano_actual-2004;
$gestion_temp = $ano_actual;
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

############################################################################
$accion = "";
$fecha_inicio = $fecha2;
$fecha_final = $fecha2;
$error = false;
# Ingresos Recibidos (IR), Boletas Impresas (BI), Boletas con Sello (BS), Montos Adeudados (MA)
$selected_0 = $selected_ma = $selected_bi = $selected_bs = "";
if (isset($_POST["tabla"])) {
   $accion = "reportes";
   $tabla_reporte = $_POST["tabla"];
	 if ($tabla_reporte == "0") {
      $selected_0 = pg_escape_string('selected = "selected"'); 
	 } 	 
	 if ($tabla_reporte == "MA") {
      $selected_ma = pg_escape_string('selected = "selected"'); 
	 } 
	 if ($tabla_reporte == "BI") {
      $selected_bi = pg_escape_string('selected = "selected"'); 
	 } 
	 if ($tabla_reporte == "BS") {
      $selected_bs = pg_escape_string('selected = "selected"'); 
	 } 	 
} else $selected_bs = pg_escape_string('selected = "selected"'); 
########################################
#---------- CHEQUEAR FECHAS -----------#
########################################	   
if (isset($_POST["fecha_inicio"])) {	
   $fecha_inicio = $_POST["fecha_inicio"];
	 if (!check_fecha($fecha_inicio, $dia_actual, $mes_actual, $ano_actual)) {
	    $error = true;
		  $mensaje_de_error = "Error: La fecha de inicio est� incorrecto!";
			$fecha_inicio = $fecha2;
   }
}
if (isset($_POST["fecha_final"])) {	
   $fecha_final = $_POST["fecha_final"];
   if (!check_fecha($fecha_final, $dia_actual, $mes_actual, $ano_actual)) {
	    $error = true;
			$mensaje_de_error = "Error: La fecha final est� incorrecto!";
		  $fecha_final = $fecha2;
   }
}		
########################################
#---------- CHEQUEAR FECHAS -----------#
########################################	   
if (isset($_POST["user"])) {	
   $usuario_reporte = $_POST["user"];
}
########################################
#--------- CHEQUEAR USUARIOS ----------#
########################################
$sql="SELECT DISTINCT usuario FROM imp_control ORDER BY usuario";
$check_usuarios = pg_num_rows(pg_query($sql));
if ($check_usuarios > 0) {
   $result = pg_query($sql);
	 $i = 0;	 
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
			   $usuario_control[$i] = $col_value;
				 $i++;
			}
	 }
}
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	

echo "<td>\n";
echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\">\n"; 
   echo "<tr>\n";
      echo "<td width=\"3%\"> &nbsp</td>\n";    
      echo "<td align=\"center\" valign=\"center\" height=\"10\" width=\"80%\" class=\"pageName\">\n"; 
      echo "Reporte - Tablas Base\n";                          
      echo "</td>\n";
      echo "<td width=\"3%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "</tr>\n";	
   echo "<tr height=\"40px\">\n";
   echo "<td> &nbsp</td>\n";   #Col. 1
   echo "<td align=\"left\" valign=\"center\" height=\"40\">\n";
   echo "<fieldset><legend>Seleccione la gestión y el reporte</legend>\n";	 
   echo "<table border=\"0\" width=\"100%\">\n";    # 8 Columnas	
   echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=71&id=$session_id\" accept-charset=\"utf-8\">\n"; 	  
   echo "<tr>\n"; 
   echo "<td> &nbsp</td>\n";  #Col. 1	 
   echo "<td align=\"left\" class=\"bodyTextD_Small\">\n";   #Col. 2 		
   echo "Gestión:\n";
   echo "</td>\n";  	
   echo "<td> &nbsp</td>\n";  #Col. 6 	 
   echo "<td class=\"bodyTextD_Small\" colspan=\"4\">\n";  #Col. 7
   echo "Reporte:\n";				 				 
   echo "</td>\n";	
   echo "<td> &nbsp</td>\n";  #Col. 8 		   
   echo "</tr>\n";
   echo "<tr>\n"; 
   echo "<td width=\"15%\"> &nbsp</td>\n";  #Col. 1 		 
   echo "<td align=\"left\" width=\"14%\" class=\"bodyTextD_Small\">\n";	
   echo "<select class=\"navText\" name=\"gestion\" size=\"1\">\n";       
   $i = 0;
   while ($i < $no_de_gestiones) {               	 
      echo "                        <option id=\"form0\" value=\"$gestion_lista[$i]\" $selected_gestion[$i]> $gestion_lista[$i]</option>\n";				    
	    $i++;
   }
   echo "                     </select>\n";	
	 echo "                  </td>\n";  
   echo "                  <td width=\"2%\"> &nbsp</td>\n";  #Col. 6 	 	
   echo "                  <td align=\"left\" width=\"30%\">\n";   #Col. 7 		
   echo "                     <select class=\"navText\" name=\"tabla\" size=\"1\">\n";		
   echo "                        <option id=\"form0\" value=\"0\" $selected_0> Todas las tablas base</option>\n";	 	 
   echo "                        <option id=\"form0\" value=\"A\"> Boletas Impresas</option>\n";	 		
   echo "                        <option id=\"form0\" value=\"IR\"> Ingresos Recibidos</option>\n"; 
   #echo "                        <option id=\"form0\" value=\"MA\" $selected_ma> Montos Adeudados </option>\n";	
   echo "                     </select>\n";	 				 				 
   echo "                  </td>\n";
   echo "                  <td width=\"10%\">\n";  #Col. 8 
   echo "                     <input name=\"accion\" type=\"hidden\" class=\"smallText\" value=\"reportes\">\n";	 		 		 
   echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Ver\">\n";
   echo "                  </td>\n";	
   echo "                  <td width=\"10%\">\n";  #Col. 9  		 		 
   echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Crear PDF\">\n";
   echo "                  </td>\n";
   echo "                  <td width=\"5%\"> &nbsp</td>\n";  #Col. 1	 		   	   
   echo "               </tr>\n";
   echo "               </form>\n";	 	 			  		 
	 echo "            </table>\n";
	 echo "         </fieldset>\n";	 	  		 
   echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";
   if ($error) { 
	    echo "      <tr>\n";
      echo "         <td> &nbsp</td>\n";  #Col. 1 					 
      echo "         <td align=\"center\" class=\"bodyTextD\">\n";  #Col. 2	    
      echo "            <font color=\"red\">$mensaje_de_error</font>\n";	
      echo "         </td>\n";
      echo "         <td> &nbsp</td>\n";  #Col. 3				
      echo "      </tr>\n";						
	 } elseif ($accion == "reportes") {
      echo "      <tr>\n";    
      echo "         <td colspan=\"3\">\n";  #Col. 1-3		 
      echo "            <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"770px\">\n";   # 3 Columnas
      echo "               <tr>\n";
      echo "                  <td valign=\"top\">\n";   #Col. 1 
			if ($tabla_reporte == "0") { 	 
         include "siicat_impuestos_reporte_tablas_base_generar.php";
			} elseif ($tabla_reporte == "MA") {
         include "siicat_impuestos_reporte_montos_adeudados.php";
			} elseif ($tabla_reporte == "BI") {
         include "siicat_impuestos_reporte_boletas_impresas.php";
			} elseif ($tabla_reporte == "BS") {
         include "siicat_impuestos_reporte_boletas_con_sello.php";
			}				
      echo "                     <iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/reporte$user_id.html\" id=\"content\" width=\"750px\" height=\"900px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
      echo "                    </iframe>\n";	
      echo "                  </td>\n";	 
      echo "               </tr>\n";	 		
      echo "            </table>\n";
      echo "         </td>\n";
      echo "      </tr>\n";	
   }
?>
