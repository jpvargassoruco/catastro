<?php


if (isset($_GET["cod_cat"])) {
   $cod_cat = $_GET["cod_cat"];	 
}
if (isset($_POST["accion"])) {
   $accion = $_POST["accion"];	 
} else {
   $accion = "";
}

if (!isset($_POST["submit"])) {
	 $reportes = true;
} else $reportes = false; 
#   $esc= pg_escape_string('selected=\"selected\"');
	 
#$mostrar = false; 
#$resultado = false;
#$dos_resultados = false;
$error = false;
#$cotizaciones = $tablas = $base_legal = $ajustes = $reportes = false;
################################################################################
#----------------------- ACCESO DIRECTO A COTIZACIONES ------------------------#
################################################################################	
/*$acceso_directo = false;
if (isset($_GET["cot"])) {
   $acceso_directo = true;
	 $coti_nueva = true;
   $cot = $_GET["cot"];	 
	 if ($cot == "tapr") {
	    $reportes = false;
			$accion = "cotizaciones";
			$indicador = "tapr_ufv";
	 }
}*/
################################################################################
#--------------------------------- REPORTES -----------------------------------#
################################################################################	 
if (((isset($_POST["submit"])) AND (($_POST["submit"]) == "Reportes")) OR ($accion == "reportes")) {	
   $reportes = true;
}
################################################################################
#------------------------------- COTIZACIONES ---------------------------------#
################################################################################	 
if (((isset($_POST["submit"])) AND (($_POST["submit"]) == "Cotizaciones")) OR ($accion == "cotizaciones")) {	
   $cotizaciones = true;
}
################################################################################
#--------------------------------- TABLAS -------------------------------------#
################################################################################	 
if (((isset($_POST["submit"])) AND (($_POST["submit"]) == "Tablas")) OR ($accion == "tablas")) {
   $tablas = true; 
} 
################################################################################
#--------------------------------- TABLAS -------------------------------------#
################################################################################	 
if (((isset($_POST["submit"])) AND (($_POST["submit"]) == "Base Legal")) OR ($accion == "base_legal")) {	 
	 $base_legal = true;
}
################################################################################
#---------------------------------- AJUSTES -----------------------------------#
################################################################################	 
if (((isset($_POST["submit"])) AND (($_POST["submit"]) == "Ajustes")) OR ($accion == "ajustes")) {	
   $ajustes = true;
}
################################################################################
#---------------------------- BUSQUEDA TRANSMITIDA ----------------------------#
################################################################################	 
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Ver") AND ($accion != "reportes")) {	 
	 $gestion = $_POST["gestion"];
} else {
   $gestion = $date['year']-1;
}
################################################################################
#-------------------- IMPORTAR TABLA SATFECHA DEL SIIM ------------------------#
################################################################################	
# ACTIVAR FILAS 272-278 
if ((isset($_POST["submit"])) AND ($_POST["submit"] == "Importar")) {	 
   $sql="SELECT fecha, coti, ufv FROM satfecha ORDER BY fecha ASC";
	 $result = pg_query($sql);
	 $i = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	    $valido = true;	 
      foreach ($line as $col_value) {
	       if ($i == 0) {
				    if (strlen ($col_value) != 8) {
						    $valido = false;									
						} else { 				   
						   $ano_temp = substr($col_value,0,4);
							 if ($ano_temp < 2000) {
							    $valido = false;
							 }
						   $mes_temp = substr($col_value,4,2);
						   $dia_temp = substr($col_value,6,2);
						   $fecha = $ano_temp."-".$mes_temp."-".$dia_temp; 
            }		
	       } elseif ($i == 1) { 
				    $coti = trim($col_value); 	 
			   } else { 
			      $ufv = trim($col_value);
				    $i = -1;
			   }
			   $i++;
      }
			if ($valido) {
			   $sql="SELECT fecha FROM imp_cotizaciones WHERE fecha = '$fecha'";
			   $check_coti = pg_num_rows(pg_query($sql));
			   if ($check_coti == 0) {
			      pg_query("INSERT INTO imp_cotizaciones (fecha, usd, ufv) VALUES ('$fecha','$coti','$ufv')");
			   }
			} 
   } 			
   pg_free_result($result);		    
}
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################		
	 echo "<td>\n";
	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
	 # Fila 1
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"10%\"> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"70%\" class=\"pageName\">\n"; 
	 echo "            Sistema\n";                          
   echo "         </td>\n";
	 echo "         <td width=\"20%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";
	 # Fila 2	 
	 echo "      <tr height=\"80px\">\n";
	 echo "         <td > &nbsp</td>\n";   #Col. 1 	  
	 echo "         <td align=\"center\">\n";   #COLUMNA 2	
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 5 Columnas   	 
	 echo "               <tr>\n";
	 echo "			          <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=97&id=$session_id\" accept-charset=\"utf-8\">\n"; 	 
	 if ($nivel == 5) {
	    echo "                  <td align=\"center\" width=\"20%\">\n";   #Col. 1
      echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Contraseña\">\n";			
	 } else {
	    echo "                  <td align=\"center\" width=\"50%\">\n";   #Col. 1	
      echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Cambiar Contraseña\">\n";			
	 } 			  	
	 echo "                  </td>\n";	 
	 echo "               </form>\n";	
	 echo "			          <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=91&id=$session_id\" accept-charset=\"utf-8\">\n"; 
	 if ($nivel == 5) {
	    echo "                  <td align=\"center\" width=\"20%\">\n";   #Col. 4	
      echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Backup\">\n";					
	 } elseif ($nivel > 1) {
	    echo "                  <td align=\"center\" width=\"50%\">\n";   #Col. 1	
      echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Copia de Seguridad\">\n";			
	 } 		  		 

	 echo "                  </td>\n";	 
	 echo "               </form>\n";	  
	 if ($nivel == 5) {	 	 	 
	    echo "			          <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=99&id=$session_id\" accept-charset=\"utf-8\">\n"; 	 
	    echo "                  <td align=\"center\" width=\"20%\">\n";   #Col. 2		  	
      echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Usuarios\">\n";
	    echo "                  </td>\n";	 
	    echo "               </form>\n";	 
	    echo "			          <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=98&id=$session_id\" accept-charset=\"utf-8\">\n"; 	 
	    echo "                  <td align=\"center\" width=\"20%\">\n";   #Col. 3		  	
      echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Registro\">\n";
	    echo "                  </td>\n";	 
	    echo "               </form>\n";
	    echo "			          <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=95&id=$session_id\" accept-charset=\"utf-8\">\n"; 
	    echo "                  <td align=\"center\" width=\"20%\">\n";   #Col. 5	 		 
      echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Contacto\">\n";
	    echo "                  </td>\n";	 
	    echo "               </form>\n";	
	 } 
#	 if ($user_id == "AAA") {
#	    echo "			          <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n"; 
#	    echo "                  <td align=\"center\" width=\"20%\">\n";   #Col. 5	 		 
#      echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Importar\">\n";
#	    echo "                  </td>\n";	 
#	    echo "               </form>\n";	
#	 }	  	 
	 echo "               </tr>\n"; 	
	 echo "            </table>\n"; 	 
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 	 	 	  	 
	 echo "      </tr>\n";	 
/*if ($reportes) {
   include "catbr_impuestos_reportes.php";
}	elseif ($cotizaciones) {
   include "catbr_impuestos_cotizaciones.php";
} elseif ($base_legal) {
   include "catbr_impuestos_documentos.php";
} elseif ($ajustes) {
   include "catbr_impuestos_ajustes.php";	 
}	elseif ($tablas) { 
   include "catbr_impuestos_tablas.php";
} # END_OF_ELSE SUBMIT "BASE LEGAL"  */
   ############################################################################# 	 
/* 	 echo "			 <form name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	 
	 echo "      <tr>\n"; 	 
	 echo "         <td align=\"center\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3 	 
#	 echo "         <input name=\"cod_cat\" type=\"hidden\" class=\"smallText\" value=\"$cod_cat\" />\n"; 	  
	 echo "         <input name=\"Submit\" type=\"submit\" class=\"smallText\" value=\"Modificar Valores\" />\n"; 		 
	 echo "         </td>\n";
	 echo "      </tr>\n";
	 echo "      </form>\n";	
	 echo "			 <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	 
	 echo "      <tr>\n"; 	 
	 echo "         <td align=\"center\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3 	   
	 echo "         <input name=\"Submit\" type=\"submit\" class=\"smallText\" value=\"Imprimir Tablas\" />\n"; 		 
	 echo "         </td>\n";
	 echo "      </tr>\n";
	 echo "      </form>\n";		 */	
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
	 echo "   <br />&nbsp;<br />\n";
	 echo "</td>\n";
 
?>