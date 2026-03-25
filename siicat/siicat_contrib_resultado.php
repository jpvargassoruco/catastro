<?php

########################################
#----------- ACTIVAR CUENTA -----------#
########################################	
if ((isset($_POST["cuenta"])) AND ($_POST["cuenta"] == "Activar")) { 
   $id_contrib = $_POST["id_contrib"];
   $sql = "UPDATE contribuyentes SET con_act='1' WHERE id_contrib='$id_contrib'";						
	 pg_query($sql);
}
########################################
#---------- INACTIVAR CUENTA ----------#
########################################	
if ((isset($_POST["cuenta"])) AND ($_POST["cuenta"] == "Desactivar")) { 
   $id_contrib = $_POST["id_contrib"];
   $sql = "UPDATE contribuyentes SET con_act='0' WHERE id_contrib='$id_contrib'";						
	 pg_query($sql);
}
########################################
#--------- BUSQUEDA POR PMC -----------#
########################################	
if ((isset($_POST["con_pmc"])) AND ($_POST["con_pmc"] != "")) { 
   $con_pmc = $_POST["con_pmc"];
   $sql="SELECT id_contrib FROM contribuyentes WHERE con_pmc = '$con_pmc'"; 
   $check_pmc = pg_num_rows(pg_query($sql)); 
   if ($check_pmc == 0) {
	    $contrib_existe = false;	
	 } else {
	    $contrib_existe = true;
      $result=pg_query($sql);
      $info = pg_fetch_array($result, null, PGSQL_ASSOC);
	    $id_contrib = $info["id_contrib"];			
      pg_free_result($result);				
	 }
}	else $check_pmc = 1;
########################################
#------- BUSQUEDA CON ID_CONTRIB-------#
########################################	
if ((isset($_POST["id_contrib"])) OR (isset($_GET["con"]))) { 
   if (isset($_POST["id_contrib"])) {
      $id_contrib = $_POST["id_contrib"];
	 } else {
      $id_contrib = $_GET["con"];	 
	 }
   $sql="SELECT id_contrib FROM contribuyentes WHERE id_contrib = '$id_contrib'"; 
   $check_id = pg_num_rows(pg_query($sql)); 
   if ($check_id == 0) {
	    $contrib_existe = false;	
	 } else $contrib_existe = true;
}	
########################################
#--- CONTRIBUYENTE RECIEN REGISTRADO --#
########################################	
if ((isset($_POST["submit"])) AND ($_POST["submit"] == "Registrar")) { 
   $contrib_existe = true;
}	
########################################
#-------- LEER DATOS DE TABLA ---------#
########################################		 
if  ($contrib_existe) {
	$sql="SELECT * FROM contribuyentes WHERE id_contrib = '$id_contrib'"; 
	$result=pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	include "c:/apache/siicat/siicat_contrib_leer_tabla.php"; 
	pg_free_result($result);
	if ($con_act == 1) {
		$con_act_temp = "ACTIVO";
		$con_act_color = "black";
		$con_act_boton = "Desactivar";
	} else {
		$con_act_temp = "INACTIVO";
		$con_act_color = "red";
		$con_act_boton = "Activar";
	}
	if ($con_tipo == "Persona Natural") {
		$con_raz = "";
	}
}

$deuda_existe = true;

################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	
echo "<td>\n";
echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas

echo "<tr height=\"40px\">\n";
echo "<td width=\"5%\">\n";  #Col. 1 
echo "&nbsp&nbsp <a href='javascript:history.back()'>\n";	
echo "<img border='0' src='http://$server/$folder/graphics/boton_atras.png' alt='' title='Volver'></a>\n";	
echo "</td>\n";   	    
echo "<td align=\"center\" valign=\"center\" height=\"40\" width=\"70%\" class=\"pageName\">\n"; 
echo "Contribuyente\n";	                           
echo "</td>\n";
echo "<td width=\25%\"> &nbsp</td>\n";   #Col. 3 			 
echo "</tr>\n";	
if ($contrib_existe) {	 
	echo "<tr>\n";  
	echo "<td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	echo "<td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	
	echo "<fieldset><legend>Cuenta del Contribuyente</legend>\n";		 
	echo "<table width=\"100%\" border=\"0\">\n";		# 5 Columnas	
		echo "<form name=\"isc\" method=\"post\" action=\"index.php?mod=123&id=$session_id\" accept-charset=\"utf-8\">\n"; 	 
			echo "<tr height=\"40\">\n";		
			echo "<td align=\"left\" colspan=\"3\"><b>ESTADO DE CUENTA:</b></td>\n";	 
			echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\"><font color=\"$con_act_color\"><b>$con_act_temp</b></font></td>\n";
			echo "<td align=\"left\" colspan=\"2\">\n";		
			echo "<input name=\"cuenta\" type=\"submit\" class=\"smallText\" value=\"$con_act_boton\" onClick=\"go()\">\n";		
			echo "<input name=\"id_contrib\" type=\"hidden\" value=\"$id_contrib\">\n";		 	
			echo "</td>\n";
			echo "</tr>\n";
		echo "</form>\n"; 	 
		echo "<tr height=\"40\">\n";		
		echo "<td align=\"left\" colspan=\"9\"><b>CODIFICACION</b></td>\n";	 
		echo "</tr>\n";  

		echo "<tr>\n";				 			           
			echo "<td width=\"1%\">&nbsp</td>\n";	 
			echo "<td align=\"right\" width=\"9%\" class=\"bodyTextD\">Estado &nbsp</td>\n";
			echo "<td align=\"left\" width=\"15%\" class=\"bodyTextH\">&nbsp $con_act_temp</td>\n"; 	 	  
			echo "<td align=\"right\" width=\"20%\" class=\"bodyTextD\">P.M.C. &nbsp</td>\n";
			echo "<td align=\"left\" width=\"16%\" class=\"bodyTextH\">&nbsp $con_pmc</td>\n";
			echo "<td align=\"right\" width=\"23%\" class=\"bodyTextD\">Padron anterior &nbsp</td>\n";
			echo "<td align=\"left\" width=\"16%\" class=\"bodyTextH\">&nbsp $pmc_ant</td>\n";	 	 				 				 
		echo "</tr>\n";
	echo "</table>\n";
	echo "</fieldset>\n";			 
	echo "</td>\n";  #Col. 2		 
	echo "</tr>\n";   
	echo "<tr>\n";  	   	 		 		
	echo "<td height=\"40\"> &nbsp</td>\n";                     
	echo "<td valign=\"top\" class=\"bodyText\">\n";	 
	echo "<table width=\"100%\" border=\"0\">\n";	  	
	echo "<tr height=\"40\">\n";		
	echo "<td align=\"left\" colspan=\"6\"><b>IDENTIFICACION</b></td>\n";		
	echo "</tr>\n";
	if ($con_tipo == "Empresa") {
		echo "<tr>\n";				 			           
		echo "<td>&nbsp</td>\n";  
		echo "<td align=\"right\" class=\"bodyTextD\">Razon Social &nbsp</td>\n";
		echo "<td align=\"left\" colspan=\"3\" class=\"bodyTextH\">&nbsp <b>$con_raz</b></td>\n";	 				 				 
		echo "</tr>\n";		 
		echo "<tr>\n";				 			           
		echo "<td>&nbsp</td>\n";  
		echo "<td align=\"right\" class=\"bodyTextD\">Representante &nbsp</td>\n";
		echo "<td align=\"left\" colspan=\"3\" class=\"bodyTextH\">&nbsp $con_nom1 $con_nom2 $con_pat $con_mat</td>\n";	 			
	} else {
		echo "<tr>\n";				 			           
		echo "<td>&nbsp</td>\n";  
		echo "<td align=\"right\" class=\"bodyTextD\">Nombre &nbsp</td>\n";
		echo "<td align=\"left\" colspan=\"3\" class=\"bodyTextH\">&nbsp <b>$con_nom1 $con_nom2 $con_pat $con_mat</b></td>\n";				
	}	 				 
	echo "</tr>\n";		 		 	 
	echo "<tr>\n";				 			           
	echo "<td width=\"2%\">&nbsp</td>\n"; 	  
	echo "<td width=\"18%\" align=\"right\" class=\"bodyTextD\">DocumentaciÓn</td>\n";
	echo "<td width=\"25%\" align=\"left\" class=\"bodyTextH\">&nbsp $documentacion</td>\n";	
	echo "<td width=\"20%\" align=\"right\" class=\"bodyTextD\">Fecha Nac. &nbsp</td>\n";	 
	echo "<td width=\"20%\" align=\"left\" class=\"bodyTextH\">&nbsp $con_fech_nac</td>\n";	
	echo "<td width=\"15%\">&nbsp</td>\n";		 					 				 
	echo "</tr>\n";	
	echo "<tr>\n";				 			           
	echo "<td>&nbsp</td>\n";
	echo "<td align=\"right\" class=\"bodyTextD\">Domicilio &nbsp</td>\n";
	echo "<td align=\"left\" colspan=\"3\" class=\"bodyTextH\">&nbsp $direccion</td>\n";
	echo "<td></td>\n";	 	 				 				 
	echo "</tr>\n";		 	 	  
	echo "<tr>\n";		
	echo "<td align=\"left\" colspan=\"6\" height=\"30px\">&nbsp</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";                       
	echo "<td valign=\"top\" height=\"180\" colspan=\"6\" class=\"bodyText\">\n";  #Col. 1+2+3	
	echo "<div id=\"tabs\">\n";
	echo "<ul>\n";
	echo "<li><a href=\"#tab-1\"><span>Mas Datos</span></a></li>\n";
	echo "<li><a href=\"#tab-3\"><span>Bienes</span></a></li>\n";
	echo "<li><a href=\"#tab-4\"><span>Historial tributario</span></a></li>\n";	 	  
	echo "<li><a href=\"#tab-5\"><span>Deudas</span></a></li>\n";

	echo "</ul>\n";

	echo "<div id=\"tab-1\">\n";
	include "contribuyente_datos.php"; 
	echo "</div>\n";

	echo "<div id=\"tab-3\">\n";
	include "siicat_contrib_bienes.php";  
	echo "</div>\n";

	echo "<div id=\"tab-4\">\n";
	include "siicat_patentes_fotos.php";  
	echo "</div>\n";

	echo "<div id=\"tab-5\">\n";
	include "siicat_patentes_deudas.php";	
	echo "</div>\n";	   	 

	echo "</div>\n";
	echo "</td>\n";	 	 	  	 
	echo "</tr>\n";
	echo "</table>\n";
	echo "</td>\n";		 
	echo "</tr>\n";                     

} else { 
	echo "<tr height=\"40\">\n";  
	echo "<td> &nbsp</td>\n";   #Col. 1                       
	echo "<td align=\"center\" class=\"bodyText\">\n";	
	echo "<fieldset><legend>Datos de la Actividad Económica</legend>\n";	
	echo "NO SE ENCUENTRA NINGUN CONTRIBUYENTE REGISTRADO! <br />REVISE EL NUMERO INGRESADO!";	 
	echo "</fieldset>\n";	 
	echo "<td> &nbsp</td>\n";  	 	 	 
	echo "</td>\n";		 
	echo "</tr>\n";	 	 	 	   
}

	echo "      <tr height=\"100%\"></tr>\n";			 
	echo "   </table>\n";
	echo "</td>\n";	
	
?>