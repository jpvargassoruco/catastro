<?php

#####################################################
#---- CHEQUEAR SI EXISTE UNA ENTRADA EN LA TABLA ---#
#####################################################				 	
$sql="SELECT * FROM imp_base";
$check_table = pg_num_rows(pg_query($sql));	
if ($check_table == 0) {
   pg_query("INSERT INTO imp_base (descuento, multa_mora, multa_incum, multa_admin, rep_form) VALUES ('10','0','10','0','0')");
}

#if (((isset($_POST["submit"])) AND (($_POST["submit"]) == "Ajustes")) OR ($accion == "ajustes")) {	
#   $ajustes = true;
#}
########################################
#-------- MODIFICAR VALORES -----------#
########################################
if (isset($_POST["guardar"])) {
   $username = get_username($session_id);
   if (isset($_POST["descuento_nuevo"])) {
	    $columna = "descuento";
	    $valor = $_POST["descuento_nuevo"];
			if ((!check_float($valor)) OR ($valor < 0) OR ($valor == "")) {
	    } else {
			   pg_query("UPDATE imp_base SET $columna = '$valor'");
				 $accion = "Valor DESCUENTO cambiado";
				 $texto = $valor." %";
         pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		                  VALUES ('$username','$ip','$fecha','$hora','$accion','$texto')");
			}
	 }
   if (isset($_POST["multa_mora_nueva"])) {
      $columna = "multa_mora";	 
      $valor = $_POST["multa_mora_nueva"];	
			if ((!check_float($valor)) OR ($valor < 0) OR ($valor == "")) {
	    } else {
			   pg_query("UPDATE imp_base SET $columna = '$valor'");
				 $accion = "Valor MULTA MORA cambiado";
				 $texto = $valor." Bs.";
         pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		                  VALUES ('$username','$ip','$fecha','$hora','$accion','$texto')");
			}				 			 
	 }
   if (isset($_POST["multa_incum_nueva"])) {
      $columna = "multa_incum";	 
      $valor = $_POST["multa_incum_nueva"];
			if ((!check_float($valor)) OR ($valor < 0) OR ($valor == "")) {
	    } else {
			   pg_query("UPDATE imp_base SET $columna = '$valor'");
				 $accion = "Valor MULTA INCUM cambiado";
				 $texto = $valor." %";
         pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		                  VALUES ('$username','$ip','$fecha','$hora','$accion','$texto')");
			}				 				 
	 }	 
   if (isset($_POST["multa_admin_nueva"])) {
      $columna = "multa_admin";	 
      $valor = $_POST["multa_admin_nueva"];
			if ((!check_float($valor)) OR ($valor < 0) OR ($valor == "")) {
	    } else { 
			   pg_query("UPDATE imp_base SET $columna = '$valor'");	
				 $accion = "Valor MULTA ADMIN cambiado";
				 $texto = $valor." Bs.";
         pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		                  VALUES ('$username','$ip','$fecha','$hora','$accion','$texto')");
			}				 			 
	 }
   if (isset($_POST["rep_form_nuevo"])) {
      $columna = "rep_form";	 
      $valor = $_POST["rep_form_nuevo"];
			if ((!check_float($valor)) OR ($valor < 0) OR ($valor == "")) {
	    } else {
			   pg_query("UPDATE imp_base SET $columna = '$valor'");
				 $accion = "Valor REP FORM cambiado";
				 $texto = $valor." Bs.";
         pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		                  VALUES ('$username','$ip','$fecha','$hora','$accion','$texto')");
			}				 				 
	 }   
	 if (isset($_POST["nota_tec"])) {
      $columna = "nota_tec";	 
      $valor = $_POST["nota_tec"];
			pg_query("UPDATE imp_base SET $columna = '$valor'");					 
	 }
	 if (isset($_POST["nota_plano"])) {
      $columna = "nota_plano";	 
      $valor = $_POST["nota_plano"];
			pg_query("UPDATE imp_base SET $columna = '$valor'");							 
	 }	 
	 if (isset($_POST["nota_cert"])) {
      $columna = "nota_cert";	 
      $valor = $_POST["nota_cert"];
			pg_query("UPDATE imp_base SET $columna = '$valor'");							 
	 }	
	 if (isset($_POST["nota_list"])) {
      $columna = "nota_list";	 
      $valor = $_POST["nota_list"];
			pg_query("UPDATE imp_base SET $columna = '$valor'");						 
	 }	
	 if (isset($_POST["obs_linea"])) {
      $columna = "obs_linea";	 
      $valor = $_POST["obs_linea"];
			pg_query("UPDATE imp_base SET $columna = '$valor'");						 
	 }	
	 if (isset($_POST["nota_linea"])) {
      $columna = "nota_linea";	 
      $valor = $_POST["nota_linea"];
			pg_query("UPDATE imp_base SET $columna = '$valor'");						 
	 }		 	 	  
   if ($_POST["guardar"] == "Importar") {	
			include "siicat_import_siim.php";
			if (!$error) {
			   pg_query("UPDATE imp_base SET fecha_siim = '$fecha'");
			   $accion = "Tablas SIIM importados";
		     $texto = "-";
         pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		                  VALUES ('$username','$ip','$fecha','$hora','$accion','$texto')");						 	
			}	 
	 } 
}		
########################################
#----------- LEER TABLA ---------------#
########################################
$fecha_coti = $fecha;
$ultimo_ufv = 0;
while ($ultimo_ufv == 0) {
   $sql="SELECT ufv FROM imp_cotizaciones WHERE fecha = '$fecha_coti'";
   $result = pg_query($sql);
   $info = pg_fetch_array($result, null, PGSQL_ASSOC);
   $ultimo_ufv = $info['ufv'];
   pg_free_result($result);		 
   $timestamp = strtotime($fecha_coti.' - 1 day');
   $fecha_coti = date('Y-m-d', $timestamp);
}
$sql="SELECT * FROM imp_base";
$result = pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$descuento = $info['descuento'];
$multa_mora = $info['multa_mora'];
$multa_incum = $info['multa_incum'];
$multa_incum_bs = ROUND ($multa_incum*$ultimo_ufv,0);
$multa_admin = $info['multa_admin'];
$rep_form = $info['rep_form'];
$fecha_siim = $info['fecha_siim'];
$fecha_siim = change_date ($fecha_siim);
$nota_informe_tecnico = utf8_decode ($info['nota_tec']);
$nota_plano_catastral = utf8_decode ($info['nota_plano']);
$nota_certificado_catastral = utf8_decode ($info['nota_cert']);
$nota_listado_por_rubro = utf8_decode ($info['nota_list']);
$obs_linea = utf8_decode ($info['obs_linea']);
$nota_linea = utf8_decode ($info['nota_linea']);
pg_free_result($result);	

################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	
	 echo "<td>\n";
	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
	 # Fila 1
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"10%\"> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"70%\" class=\"pageName\">\n"; 
	 echo "            Ajustes\n";                          
   echo "         </td>\n";
	 echo "         <td width=\"20%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";
	 # Fila 1
   echo "      <tr>\n";    
   echo "         <td colspan=\"3\"> &nbsp</td>\n";  #Col. 1-3	 
   echo "      </tr>\n";
   echo "			 <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=66&id=$session_id\" accept-charset=\"utf-8\">\n";		 
	 # Fila 2	 
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Descuento por pago en tiempo</legend>\n";
#   echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 7 TColumnas
	 echo "               <tr>\n";	 	 
	 echo "                  <td width=\"5%\"> &nbsp </td>\n";   #TCol. 1 		 
	 echo "                  <td align=\"center\" width=\"32%\" class=\"bodyTextH\">\n";   #TCol. 2  
	 echo "                     Descuento por pago en tiempo:\n";
	 echo "                  </td>\n";  	     	  	 
	 echo "                  <td align=\"left\" width=\"24%\" class=\"bodyTextD\">\n";   #TCol. 3	
	 echo "                     &nbsp&nbsp&nbsp $descuento %\n";
	 echo "                  </td>\n";	
	 if ($nivel < 4) {
      echo "                  <td align=\"right\" width=\"39%\">&nbsp</td>\n"; #Col. 4					
   } else { 
      echo "                  <td align=\"right\" width=\"15%\">\n"; #Col. 4					
      echo "                     Nuevo Valor: &nbsp\n";	 
	    echo "                  </td>\n";		 
      echo "                  <td align=\"left\" width=\"9%\">\n"; #Col. 5					
      echo "                     <input name=\"descuento_nuevo\" id=\"form_anadir0\" class=\"navText\" maxlength=\"2\">\n";	 
	    echo "                  </td>\n";	 
	    echo "                  <td align=\"left\" width=\"10%\">\n";   #TCol. 6  
	    echo "                     <input name=\"submit\" type=\"hidden\" class=\"smallText\" value=\"Ajustes\">\n";	 		 
	    echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Cambiar\">\n";	 
	    echo "                  </td>\n"; 		
	    echo "                  <td width=\"5%\"> &nbsp</td>\n";   #TCol. 7	
	 }  	 	 	 	  	 	     
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
#	 echo "         </form>\n";	  
	 echo "         </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	 echo "      </tr>\n";
	 # Fila 3	 
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Multa por Mora</legend>\n";
#   echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 7 TColumnas
	 echo "               <tr>\n";	 	 
	 echo "                  <td width=\"5%\"> &nbsp </td>\n";   #TCol. 1 		 
	 echo "                  <td align=\"center\" width=\"32%\" class=\"bodyTextH\">\n";   #TCol. 2  
	 echo "                     Multa por Mora:\n";
	 echo "                  </td>\n";  	     	  	 
	 echo "                  <td align=\"left\" width=\"24%\" class=\"bodyTextD\">\n";   #TCol. 3	
	 echo "                     &nbsp&nbsp&nbsp $multa_mora Bs.\n";
	 echo "                  </td>\n";	 
	 if ($nivel < 4) {
      echo "                  <td align=\"right\" width=\"39%\">&nbsp</td>\n"; #Col. 4					
   } else { 	 
      echo "                  <td align=\"right\" width=\"15%\">\n"; #Col. 4					
      echo "                     Nuevo Valor: &nbsp\n";	 
	    echo "                  </td>\n";		 
      echo "                  <td align=\"left\" width=\"9%\">\n"; #Col. 5					
      echo "                     <input name=\"multa_mora_nueva\" id=\"form_anadir0\" class=\"navText\" maxlength=\"4\">\n";	 
	    echo "                  </td>\n";	 
	    echo "                  <td align=\"left\" width=\"10%\">\n";   #TCol. 6  
	    echo "                     <input name=\"submit\" type=\"hidden\" class=\"smallText\" value=\"Ajustes\">\n";	 		 
	    echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Cambiar\">\n";	 
	    echo "                  </td>\n"; 		
	    echo "                  <td width=\"5%\"> &nbsp</td>\n";   #TCol. 7	
	 }  	 	 	 	  	 	     
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
#	 echo "         </form>\n";	  
	 echo "         </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	 echo "      </tr>\n";
	 # Fila 4	 
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Multa por Incumplimiento</legend>\n";
#   echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 7 TColumnas
	 echo "               <tr>\n";	 	 
	 echo "                  <td width=\"5%\"> &nbsp </td>\n";   #TCol. 1 		 
	 echo "                  <td align=\"center\" width=\"32%\" class=\"bodyTextH\">\n";   #TCol. 2  
	 echo "                     Multa por Incumplimiento:\n";
	 echo "                  </td>\n";  	     	  	 
	 echo "                  <td align=\"left\" width=\"24%\" class=\"bodyTextD\">\n";   #TCol. 3	
	 echo "                     &nbsp&nbsp&nbsp $multa_incum %\n";
	 echo "                  </td>\n";	
	 if ($nivel < 4) {
      echo "                  <td align=\"right\" width=\"39%\">&nbsp</td>\n"; #Col. 4					
   } else { 	  
      echo "                  <td align=\"right\" width=\"15%\">\n"; #Col. 4					
      echo "                     Nuevo Valor: &nbsp\n";	 
	    echo "                  </td>\n";		 
      echo "                  <td align=\"left\" width=\"9%\">\n"; #Col. 5					
      echo "                     <input name=\"multa_incum_nueva\" id=\"form_anadir0\" class=\"navText\" maxlength=\"4\">\n";	 
	    echo "                  </td>\n";	 
	    echo "                  <td align=\"left\" width=\"10%\">\n";   #TCol. 6  
	    echo "                     <input name=\"submit\" type=\"hidden\" class=\"smallText\" value=\"Ajustes\">\n";	 		 
	    echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Cambiar\">\n";	 
	    echo "                  </td>\n"; 		
	    echo "                  <td width=\"5%\"> &nbsp</td>\n";   #TCol. 7	
   }  	 	 	 	  	 	     
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
#	 echo "         </form>\n";	  
	 echo "         </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	 echo "      </tr>\n";	
   # Fila 5	 
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Multa Administrativa</legend>\n";
#   echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 7 TColumnas
	 echo "               <tr>\n";	 	 
	 echo "                  <td width=\"5%\"> &nbsp </td>\n";   #TCol. 1 		 
	 echo "                  <td align=\"center\" width=\"32%\" class=\"bodyTextH\">\n";   #TCol. 2  
	 echo "                     Multa Administrativa:\n";
	 echo "                  </td>\n";  	     	  	 
	 echo "                  <td align=\"left\" width=\"24%\" class=\"bodyTextD\">\n";   #TCol. 3	
	 echo "                     &nbsp&nbsp&nbsp $multa_admin Bs.\n";
	 echo "                  </td>\n";	 
	 if ($nivel < 4) {
      echo "                  <td align=\"right\" width=\"39%\">&nbsp</td>\n"; #Col. 4					
   } else { 	 
      echo "                  <td align=\"right\" width=\"15%\">\n"; #Col. 4					
      echo "                     Nuevo Valor: &nbsp\n";	 
	    echo "                  </td>\n";		 
      echo "                  <td align=\"left\" width=\"9%\">\n"; #Col. 5					
      echo "                     <input name=\"multa_admin_nueva\" id=\"form_anadir0\" class=\"navText\" maxlength=\"4\">\n";	 
	    echo "                  </td>\n";	 
	    echo "                  <td align=\"left\" width=\"10%\">\n";   #TCol. 6  
	    echo "                     <input name=\"submit\" type=\"hidden\" class=\"smallText\" value=\"Ajustes\">\n";	 		 
	    echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Cambiar\">\n";	 
	    echo "                  </td>\n"; 		
	    echo "                  <td width=\"5%\"> &nbsp</td>\n";   #TCol. 7	 
   } 	 	 	 	  	 	     
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
#	 echo "         </form>\n";	  
	 echo "         </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	 echo "      </tr>\n";	
	 # Fila 6	 
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Reposición de Formulario</legend>\n";
#   echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 7 TColumnas
	 echo "               <tr>\n";	 	 
	 echo "                  <td width=\"5%\"> &nbsp </td>\n";   #TCol. 1 		 
	 echo "                  <td align=\"center\" width=\"32%\" class=\"bodyTextH\">\n";   #TCol. 2  
	 echo "                     Reposición de Formulario:\n";
	 echo "                  </td>\n";  	     	  	 
	 echo "                  <td align=\"left\" width=\"24%\" class=\"bodyTextD\">\n";   #TCol. 3	
	 echo "                     &nbsp&nbsp&nbsp $rep_form Bs.\n";
	 echo "                  </td>\n";	
	 if ($nivel < 4) {
      echo "                  <td align=\"right\" width=\"39%\">&nbsp</td>\n"; #Col. 4					
   } else { 	  
      echo "                  <td align=\"right\" width=\"15%\">\n"; #Col. 4					
      echo "                     Nuevo Valor: &nbsp\n";	 
	    echo "                  </td>\n";		 
      echo "                  <td align=\"left\" width=\"9%\">\n"; #Col. 5					
      echo "                     <input name=\"rep_form_nuevo\" id=\"form_anadir0\" class=\"navText\" maxlength=\"4\">\n";	 
	    echo "                  </td>\n";	 
	    echo "                  <td align=\"left\" width=\"10%\">\n";   #TCol. 6  
	    echo "                     <input name=\"submit\" type=\"hidden\" class=\"smallText\" value=\"Ajustes\">\n";	 		 
	    echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Cambiar\">\n";	 
	    echo "                  </td>\n"; 		
	    echo "                  <td width=\"5%\"> &nbsp</td>\n";   #TCol. 7	 
   } 	 	 	 	  	 	     
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
	# echo "         </form>\n";	  
	 echo "         </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	 echo "      </tr>\n";	
	 # Fila 7	 
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Nota de Informe Técnico</legend>\n";
#   echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 7 TColumnas
	 echo "               <tr>\n";	 	 
	 echo "                  <td width=\"5%\"> &nbsp </td>\n";   #TCol. 1 		  	     	  	 
	 echo "                  <td align=\"left\" width=\"80%\" class=\"bodyTextD\">\n";   #TCol. 3	
	 if ($nivel < 4) {
	    echo "                     &nbsp $nota_informe_tecnico\n";
	    echo "                  </td>\n";	 
      echo "                  <td align=\"right\" width=\"15%\">&nbsp</td>\n"; #Col. 4					
   } else { 	  				
      echo "                     <input name=\"nota_tec\" id=\"form_anadir0\" class=\"navText\" value=\"$nota_informe_tecnico\">\n";	 
	    echo "                  </td>\n";	 
	    echo "                  <td align=\"left\" width=\"10%\">\n";   #TCol. 6  
	    echo "                     <input name=\"submit\" type=\"hidden\" class=\"smallText\" value=\"Ajustes\">\n";	 		 
	    echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Cambiar\">\n";	 
	    echo "                  </td>\n"; 		
	    echo "                  <td width=\"5%\"> &nbsp</td>\n";   #TCol. 7	
   }  	 	 	 	  	 	     
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
#	 echo "         </form>\n";	  
	 echo "         </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	 echo "      </tr>\n";	
 # Fila 8	 
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Nota de Plano Catastral</legend>\n";
#   echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 7 TColumnas
	 echo "               <tr>\n";	 	 
	 echo "                  <td width=\"5%\"> &nbsp </td>\n";   #TCol. 1 		  	     	  	 
	 echo "                  <td align=\"left\" width=\"80%\" class=\"bodyTextD\">\n";   #TCol. 3	
	 if ($nivel < 4) {
	    echo "                     &nbsp $nota_plano_catastral\n";
	    echo "                  </td>\n";	 
      echo "                  <td align=\"right\" width=\"15%\">&nbsp</td>\n"; #Col. 4					
   } else { 	  				
      echo "                     <input name=\"nota_plano\" id=\"form_anadir0\" class=\"navText\" value=\"$nota_plano_catastral\">\n";	 
	    echo "                  </td>\n";	 
	    echo "                  <td align=\"left\" width=\"10%\">\n";   #TCol. 6  
	    echo "                     <input name=\"submit\" type=\"hidden\" class=\"smallText\" value=\"Ajustes\">\n";	 		 
	    echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Cambiar\">\n";	 
	    echo "                  </td>\n"; 		
	    echo "                  <td width=\"5%\"> &nbsp</td>\n";   #TCol. 7	
   }  	 	 	 	  	 	     
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
#	 echo "         </form>\n";	  
	 echo "         </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	 echo "      </tr>\n";
 # Fila 9	 
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Nota de Certificado Catastral</legend>\n";
#   echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 7 TColumnas
	 echo "               <tr>\n";	 	 
	 echo "                  <td width=\"5%\"> &nbsp </td>\n";   #TCol. 1 		  	     	  	 
	 echo "                  <td align=\"left\" width=\"80%\" class=\"bodyTextD\">\n";   #TCol. 3	
	 if ($nivel < 4) {
	    echo "                     &nbsp $nota_certificado_catastral\n";
	    echo "                  </td>\n";	 
      echo "                  <td align=\"right\" width=\"15%\">&nbsp</td>\n"; #Col. 4					
   } else { 	  				
      echo "                     <input name=\"nota_cert\" id=\"form_anadir0\" class=\"navText\" value=\"$nota_certificado_catastral\">\n";	 
	    echo "                  </td>\n";	 
	    echo "                  <td align=\"left\" width=\"10%\">\n";   #TCol. 6  
	    echo "                     <input name=\"submit\" type=\"hidden\" class=\"smallText\" value=\"Ajustes\">\n";	 		 
	    echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Cambiar\">\n";	 
	    echo "                  </td>\n"; 		
	    echo "                  <td width=\"5%\"> &nbsp</td>\n";   #TCol. 7	
   }  	 	 	 	  	 	     
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
#	 echo "         </form>\n";	  
	 echo "         </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	 echo "      </tr>\n";	
 # Fila 10	Observaciones para Linea y Nivel 
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Observaciones para Linea y Nivel</legend>\n";
#   echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 7 TColumnas
	 echo "               <tr>\n";	 	 
	 echo "                  <td width=\"5%\"> &nbsp </td>\n";   #TCol. 1 		  	     	  	 
	 echo "                  <td align=\"left\" width=\"80%\" class=\"bodyTextD\">\n";   #TCol. 3	
	 if ($nivel < 4) {
	    echo "                     &nbsp $obs_linea\n";
	    echo "                  </td>\n";	 
      echo "                  <td align=\"right\" width=\"15%\">&nbsp</td>\n"; #Col. 4					
   } else { 	  				
      echo "                     <input name=\"obs_linea\" id=\"form_anadir0\" class=\"navText\" value=\"$obs_linea\">\n";	 
	    echo "                  </td>\n";	 
	    echo "                  <td align=\"left\" width=\"10%\">\n";   #TCol. 6  
	    echo "                     <input name=\"submit\" type=\"hidden\" class=\"smallText\" value=\"Ajustes\">\n";	 		 
	    echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Cambiar\">\n";	 
	    echo "                  </td>\n"; 		
	    echo "                  <td width=\"5%\"> &nbsp</td>\n";   #TCol. 7	
   }  	 	 	 	  	 	     
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
#	 echo "         </form>\n";	  
	 echo "         </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	 echo "      </tr>\n";	
 # Fila 11 Nota para Linea y Nivel
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Nota para Linea y Nivel</legend>\n";
#   echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 7 TColumnas
	 echo "               <tr>\n";	 	 
	 echo "                  <td width=\"5%\"> &nbsp </td>\n";   #TCol. 1 		  	     	  	 
	 echo "                  <td align=\"left\" width=\"80%\" class=\"bodyTextD\">\n";   #TCol. 3	
	 if ($nivel < 4) {
	    echo "                     &nbsp $nota_linea\n";
	    echo "                  </td>\n";	 
      echo "                  <td align=\"right\" width=\"15%\">&nbsp</td>\n"; #Col. 4					
   } else { 	  				
      echo "                     <input name=\"nota_linea\" id=\"form_anadir0\" class=\"navText\" value=\"$nota_linea\">\n";	 
	    echo "                  </td>\n";	 
	    echo "                  <td align=\"left\" width=\"10%\">\n";   #TCol. 6  
	    echo "                     <input name=\"submit\" type=\"hidden\" class=\"smallText\" value=\"Ajustes\">\n";	 		 
	    echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Cambiar\">\n";	 
	    echo "                  </td>\n"; 		
	    echo "                  <td width=\"5%\"> &nbsp</td>\n";   #TCol. 7	
   }  	 	 	 	  	 	     
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
#	 echo "         </form>\n";	  
	 echo "         </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	 echo "      </tr>\n";		 	 
   ### Fila 12 Nota para el reporte \"Listado Mayorizado por Rubro\" 
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Nota para el reporte \"Listado Mayorizado por Rubro\"</legend>\n";
#   echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 7 TColumnas
	 echo "               <tr>\n";	 	 
	 echo "                  <td width=\"5%\"> &nbsp </td>\n";   #TCol. 1 		  	     	  	 
	 echo "                  <td align=\"left\" width=\"80%\" class=\"bodyTextD\">\n";   #TCol. 3	
	 if ($nivel < 4) {
	    echo "                     &nbsp $nota_listado_por_rubro\n";
	    echo "                  </td>\n";	 
      echo "                  <td align=\"right\" width=\"15%\">&nbsp</td>\n"; #Col. 4					
   } else { 	  				
      echo "                     <input name=\"nota_list\" id=\"form_anadir0\" class=\"navText\" value=\"$nota_listado_por_rubro\">\n";	 
	    echo "                  </td>\n";	 
	    echo "                  <td align=\"left\" width=\"10%\">\n";   #TCol. 6  
	    echo "                     <input name=\"submit\" type=\"hidden\" class=\"smallText\" value=\"Ajustes\">\n";	 		 
	    echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Cambiar\">\n";	 
	    echo "                  </td>\n"; 		
	    echo "                  <td width=\"5%\"> &nbsp</td>\n";   #TCol. 7	
   }  	 	 	 	  	 	     
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
#	 echo "         </form>\n";	  
	 echo "         </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	 echo "      </tr>\n";		 
	  	 		 	  	
	 echo "      </form>\n";	 	     
	 ### Fila 13 Importar SIIM
	 echo "      <tr>\n";	
   echo "         <td> &nbsp</td>\n";   #Col. 1 	 			
   echo "         <td align=\"center\">\n";  #Col. 2-3		 										   		 
   echo "            Importar Datos del programa SIIM\n";
   echo "         </td>\n";	
   echo "         <td> &nbsp</td>\n";   #Col. 1 	 	 		 
   echo "      </tr>\n";
	 echo "      <tr>\n";
   echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=66&id=$session_id\" accept-charset=\"utf-8\">\n";		 
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Importar Datos del SIIM</legend>\n";  
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 6 TColumnas
	 echo "               <tr>\n";	 	 
	 echo "                  <td width=\"5%\"> &nbsp </td>\n";   #TCol. 1 		 
	 echo "                  <td align=\"center\" width=\"32%\" class=\"bodyTextH\">\n";   #TCol. 2  
	 echo "                     Fecha de los registros SIIM:\n";
	 echo "                  </td>\n";  	     	  	 
	 echo "                  <td align=\"left\" width=\"24%\" class=\"bodyTextD\">\n";   #TCol. 3	
	 echo "                     &nbsp&nbsp&nbsp $fecha_siim\n";
	 echo "                  </td>\n";	
	 if ($nivel < 3) {
      echo "                  <td align=\"right\" width=\"39%\">&nbsp</td>\n"; #Col. 4					
   } else { 	  
      echo "                  <td align=\"right\" width=\"24%\">\n"; #Col. 4					
      echo "                     Importar datos del SIIM: &nbsp\n";	 
	    echo "                  </td>\n";		 
	    echo "                  <td align=\"left\" width=\"10%\">\n";   #TCol. 5 
	    echo "                     <input name=\"submit\" type=\"hidden\" class=\"smallText\" value=\"Ajustes\">\n";	 		 
	    echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Importar\">\n";	 
	    echo "                  </td>\n"; 		
	    echo "                  <td width=\"5%\"> &nbsp</td>\n";   #TCol. 6	 
   } 	 	 	 	  	 	     
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
	# echo "         </form>\n";	  
	 echo "         </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	 echo "      </tr>\n";	 	
	 echo "      </form>\n";	
   if ((isset($_POST["guardar"])) AND ($_POST["guardar"] == "Importar")) {
      echo "      <tr height=\"15px\">\n";
      echo "         <td> &nbsp</td>\n";  #Col. 1					
      if ($error) {
	       echo "         <td align=\"center\">\n";   #Col. 2							
         echo "<font color=\"red\">\n";
         echo "   Ha ocurrido un error abriendo los archivos. Por favor, verifique que el programa SIIM esté instalado en la carpeta 'c:/wsiim98/'!<br />\n";
         echo "</font>\n";	 
      } else {
         if (!$error_reg) {
		        echo "         <td align=\"center\">\n";   #Col. 2				 
            echo "<br /><font color=\"green\">Los registros se han importado con éxito desde el programa SIIM</font><br />"; 
	       } else {
				    $i = 0;		
	          echo "<td align=\"left\">\n";   #Col. 2							
						echo "<br /><font color=\"red\">Ha ocurrido un problema con los siguientes registros:</font><br />";        
				    while ($i < $no_de_errores_reg) {					     
			         echo "$sql_reg[$i]<br />";
							 $i++;
						}
		        echo "<font color=\"red\">Compruebe las entradas en las columnas, especialmente apóstrofes mal ingresados, y corrija el error en el SIIM!</font><br /><br />";						
            echo "<font color=\"green\">Los otros registros se han importado con éxito desde el programa SIIM</font><br />"; 	 
	       }
      }
	    echo "         </td>\n";
      echo "         <td> &nbsp</td>\n";  #Col. 3			 	 
      echo "      </tr>\n";		
	 }  
   echo "      <tr height=\"15px\">\n";
	 echo "         <td colspan=\"3\"> &nbsp</td>\n";   #Col. 1 	    	 
   echo "      </tr>\n";		 
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
	 echo "   <br />&nbsp;<br />\n";
	 echo "</td>\n";
?>
