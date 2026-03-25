<?php

$accion = "";
$fecha_inicio = $fecha2;
$fecha_final = $fecha2;
$error = false;
# Ingresos Recibidos (IR), Boletas Impresas (BI), Boletas con Sello (BS), Montos Adeudados (MA)
$selected_ir = $selected_ma = $selected_bi = $selected_bs = "";
if (isset($_POST["tipo"])) {
   $accion = "reportes";
   $tipo_reporte = $_POST["tipo"];
	 if ($tipo_reporte == "IR") {
      $selected_ir = pg_escape_string('selected = "selected"'); 
	 } 	 
	 if ($tipo_reporte == "MA") {
      $selected_ma = pg_escape_string('selected = "selected"'); 
	 } 
	 if ($tipo_reporte == "BI") {
      $selected_bi = pg_escape_string('selected = "selected"'); 
	 } 
	 if ($tipo_reporte == "BS") {
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
		  $mensaje_de_error = "Error: La fecha de inicio está incorrecto!";
			$fecha_inicio = $fecha2;
   }
}
if (isset($_POST["fecha_final"])) {	
   $fecha_final = $_POST["fecha_final"];
   if (!check_fecha($fecha_final, $dia_actual, $mes_actual, $ano_actual)) {
	    $error = true;
			$mensaje_de_error = "Error: La fecha final está incorrecto!";
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
echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
# Fila 1
echo "      <tr height=\"40px\">\n";
echo "         <td width=\"10%\"> &nbsp</td>\n";   #Col. 1 	    
echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"80%\" class=\"pageName\">\n"; 
echo "            Reportes\n";                          
echo "         </td>\n";
echo "         <td width=\"10%\"> &nbsp</td>\n";   #Col. 3 			 
echo "      </tr>\n";	

	 # Fila 3
 #  echo "      <tr>\n";    
 #  echo "         <td colspan=\"3\"> &nbsp</td>\n";  #Col. 1-3	 
 #  echo "      </tr>\n";
	 # Fila 4
	 echo "      <tr height=\"40px\">\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1
   echo "         <td align=\"left\" valign=\"center\" height=\"40\">\n";
	 echo "         <fieldset><legend>Seleccione la fecha y el reporte</legend>\n";	 
	 echo "            <table border=\"0\" width=\"100%\">\n";    # 8 Columnas	
   echo "			          <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=70&id=$session_id\" accept-charset=\"utf-8\">\n"; 	  
	 echo "               <tr>\n"; 
   echo "                  <td align=\"left\" class=\"bodyTextD_Small\">\n";   #Col. 1 		
#   echo "                     <input name=\"radio_fecha\" value=\"fecha_de_hoy\" type=\"radio\" $radio_fecha_checked1>\n";
   echo "                     Usuario:\n";
	 echo "                  </td>\n";  
   echo "                  <td class=\"bodyTextD_Small\" colspan=\"4\">\n";  #Col. 2-5
	 echo "                     Rango de Fecha:\n";				 				 
   echo "                  </td>\n";	
   echo "                  <td> &nbsp</td>\n";  #Col. 6 	 
   echo "                  <td class=\"bodyTextD_Small\" colspan=\"4\">\n";  #Col. 7
	 echo "                     Reporte:\n";				 				 
   echo "                  </td>\n";	
   echo "                  <td> &nbsp</td>\n";  #Col. 8 		   
   echo "               </tr>\n";
	 echo "               <tr>\n"; 
   echo "                  <td align=\"left\" width=\"14%\" class=\"bodyTextD_Small\">\n";   #Col. 1 		
   echo "                     <select class=\"navText\" name=\"user\" size=\"1\">\n";		
   echo "                        <option id=\"form0\" value=\"Todos\" selected = \"selected\"> Todos</option>\n";
	 $i = 0;
	 while ($i < $check_usuarios) {
      echo "                        <option id=\"form0\" value=\"$usuario_control[$i]\"> $usuario_control[$i]</option>\n"; 	 
	    $i++;
	 }
   echo "                     </select>\n";	
	 echo "                  </td>\n";  
   echo "                  <td align=\"left\" width=\"5%\" class=\"bodyTextD_Small\"> Del </td>\n";  #Col. 2
   echo "                  <td align=\"left\" width=\"13%\" class=\"bodyTextD_Small\">\n";  #Col. 3	 
	 echo "                     <input type=\"text\" name=\"fecha_inicio\" id=\"form_anadir0\" class=\"navText\" maxlength=\"10\" value=\"$fecha_inicio\">\n";
   echo "                  </td>\n";		
   echo "                  <td align=\"left\" width=\"3%\" class=\"bodyTextD_Small\"> al </td>\n";  #Col. 4	
   echo "                  <td align=\"left\" width=\"13%\" class=\"bodyTextD_Small\">\n";  #Col. 5		  
	 echo "                     <input type=\"text\" name=\"fecha_final\" id=\"form_anadir0\" class=\"navText\" maxlength=\"10\" value=\"$fecha_final\">\n";				 				 
   echo "                  </td>\n";
   echo "                  <td width=\"2%\"> &nbsp</td>\n";  #Col. 6 	 	
   echo "                  <td align=\"left\" width=\"30%\">\n";   #Col. 7 		
   echo "                     <select class=\"navText\" name=\"tipo\" size=\"1\">\n";		
   echo "                        <option id=\"form0\" value=\"BS\" $selected_bi> Boletas con Sello</option>\n";	 	 
   echo "                        <option id=\"form0\" value=\"BI\" $selected_bi> Boletas Impresas</option>\n";	 		
   echo "                        <option id=\"form0\" value=\"IR\" $selected_ir> Ingresos Recibidos</option>\n"; 
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
			if ($tipo_reporte == "IR") { 	 
         include "siicat_impuestos_reporte_ingresos_recibidos.php";
			} elseif ($tipo_reporte == "MA") {
         include "siicat_impuestos_reporte_montos_adeudados.php";
			} elseif ($tipo_reporte == "BI") {
         include "siicat_impuestos_reporte_boletas_impresas.php";
			} elseif ($tipo_reporte == "BS") {
         include "siicat_impuestos_reporte_boletas_con_sello.php";
			}				
      echo "                     <iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/reporte$user_id.html\" id=\"content\" width=\"750px\" height=\"750px\" align=\"left\" scrolling=\"yes\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
      echo "                    </iframe>\n";	
      echo "                  </td>\n";	 
      echo "               </tr>\n";	 		
      echo "            </table>\n";
      echo "         </td>\n";
      echo "      </tr>\n";	
   }
?>
