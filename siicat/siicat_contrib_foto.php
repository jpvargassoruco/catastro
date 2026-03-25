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
$foto1 = $foto2 = false;
$borrar_foto1 = $borrar_foto2 = false;
$username = get_username($session_id);
################################################################################
#------------------------- CHEQUEAR SI EXISTEN FOTOS --------------------------#
################################################################################	
$filename1 = "C:/apache/htdocs/$folder/fotos/".$cod_cat.".jpg";
$filename2 = "C:/apache/htdocs/$folder/fotos/".$cod_cat."-A.jpg";
if (file_exists($filename1)) {	 
   $foto1_exists = "t";
} else $foto1_exists = "f";	   
if (file_exists($filename2)) {   
   $foto2_exists = "t";
} else $foto2_exists = "f";	
################################################################################
#------------------------------ ELEGIR FOTO 1 ---------------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Subir Foto 1")) {
   $foto1 = true;
}
################################################################################
#------------------------------ SUBIR FOTO 1 -----------------------------------#
################################################################################	
if ((isset($_POST["accion"])) AND (($_POST["accion"]) == "Subir Foto 1")) {
   $no_de_foto = 1;
	 include "siicat_upload_foto.php";
	 if ($error) {
	    $foto1 = true;
	 } else { 
			$accion = "Foto subida";
		  pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");				 
	 }
}
################################################################################
#------------------------------ BORRAR FOTO 1 ---------------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Borrar Foto 1")) {
   $foto1 = true;   
	 $borrar_foto1 = true;
}
################################################################################
#------------------------ CONFIRMAR BORRAR FOTO 1 -----------------------------#
################################################################################	
if ((isset($_POST["accion"])) AND (($_POST["accion"]) == "Borrar Foto 1") AND (($_POST["submit"]) == "SI") ) {																 
   $fotopath = "C:/apache/htdocs/$folder/fotos/".$cod_cat.".JPG";		
   if ($foto1_exists == "t") {   
      unlink($fotopath);
			$foto1_exists = "f";
			$accion = "Foto borrada";
		  pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");			
	 } 
}
################################################################################
#------------------------------ ELEGIR FOTO 2 ---------------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Subir Foto 2")) {
   $foto2 = true;
}
################################################################################
#------------------------------ SUBIR FOTO 2 -----------------------------------#
################################################################################	
if ((isset($_POST["accion"])) AND (($_POST["accion"]) == "Subir Foto 2")) {
   $no_de_foto = 2;
	 include "siicat_upload_foto.php";
	 if ($error) {
	    $foto2 = true;
	 } else {
			$accion = "Foto subida";
		  pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");				 
	 }
}
################################################################################
#------------------------------ BORRAR FOTO 2 ---------------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Borrar Foto 2")) {
   $foto2 = true;   
	 $borrar_foto2 = true;
}
################################################################################
#------------------------ CONFIRMAR BORRAR FOTO 2 -----------------------------#
################################################################################	
if ((isset($_POST["accion"])) AND (($_POST["accion"]) == "Borrar Foto 2") AND (($_POST["submit"]) == "SI") ) {																		 
   $fotopath = "C:/apache/htdocs/$folder/fotos/".$cod_cat."-A.JPG";		
   if ($foto2_exists == "t") {   
      unlink($fotopath);
			$foto2_exists = "f";
			$accion = "Foto borrada";
		  pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");	
	 } 
}

$error = false;
################################################################################
#-------------------- LEER DATOS DE TABLA INFO_EDIF ---------------------------#
################################################################################	
#if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Subir")) { 
	
$cod_uv = get_uv($cod_cat);
$cod_man = get_man($cod_cat);
$cod_lote = get_lote($cod_cat);	  
$cod_subl = get_subl($cod_cat);	
################################################################################
#---------------------------- BUSQUEDA TRANSMITIDA ----------------------------#
################################################################################	 
if ((isset($_POST["Submit"])) AND ((($_POST["Submit"]) == "Ver") OR(($_POST["Submit"]) == "Volver"))) {	 
   $mostrar = true;
	 $cod_cat = $_POST["cod_cat"];
}
################################################################################
#                        CHEQUEAR SI EXISTE INFO_PREDIO                        #
################################################################################	
/*
$sql="SELECT cod_uv FROM info_predio WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'";
$check_info_predio = pg_num_rows(pg_query($sql));
################################################################################
#                         CHEQUEAR SI EXISTE INFO_PREDIO                       #
################################################################################	
if ($check_info_predio > 0 ) {	 
   $resultado = true;
   $factor_zoom = 2.1;
   include "siicat_lista_datos.php";
   if ($check_predio > 0) {
	    pg_query("INSERT INTO temp_poly (edi_num, edi_piso, the_geom) SELECT edi_num, edi_piso, the_geom FROM edificaciones WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'");
		  pg_query("UPDATE temp_poly SET numero = 44 WHERE edi_num > '0'");
   }	 	
}  */
################################################################################
#------------------ CHEQUEAR SI EL PREDIO ESTA ACTIVO -------------------------#
################################################################################	
#$sql="SELECT activo FROM codigos WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'";
#$result_act = pg_query($sql);
#$act = pg_fetch_array($result_act, null, PGSQL_ASSOC);
#$activo = $act['activo'];
#pg_free_result($result_act);
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################		

	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas

/*	 # Fila 1
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"15%\"> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td align=\"center\" valign=\"center\" width=\"60%\" class=\"pageName\">\n"; 
	 echo "            Fotos del $predio\n";
	 if ($activo == 0) {
	    echo "            <font color=\"red\"> - Archivo</font>\n";
	 } 	                           
   echo "         </td>\n";
	 echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";	
#	 if ($resultado) {	  
	 $mod_lista = 7;   
   include "catbr_lista_formulario.php";  */
   # Fila 2
   ##################################################
	 #------------------- FOTOS ----------------------#
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Fotos del $predio</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n"; #TABLE  2 Columnas
   if (($foto1_exists == "t") OR ($foto2_exists == "t")) {	 
	    echo "               <tr>\n";
	    echo "                  <td align=\"right\" colspan=\"2\" class=\"bodyText_Small\"></td>\n";   #Col. 1+2	 
	    echo "               </tr>\n";	
	    #TABLA FILA 1	 
	    echo "               <tr>\n";
	    echo "                  <td width=\"50%\">\n";  #Col. 1 
      echo "                     <img border=\"0\" src=\"http://$server/$folder/fotos/$cod_cat.JPG\" width=\"100%\" height=\"270\">\n";
	    echo "                  </td>\n";   	 	 	 	  	 	     
	    echo "                  <td width=\"50%\">\n";  #Col. 2 
	    echo "                     <img border=\"0\" src=\"http://$server/$folder/fotos/$cod_cat-A.JPG\" width=\"100%\" height=\"270\">\n";
	    echo "                  </td>\n";  	 	    
	    echo "               </tr>\n";	
   } else {
	    echo "               <tr>\n";
	    echo "                  <td align=\"center\" class=\"bodyText\"><font color=\"red\"> No se encuentra ninguna foto del $predio en la base de datos!</font></td>\n";   #Col. 1	
	    echo "               </tr>\n";			 
	 }	  
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "      </tr>\n";
	 #FILA 2
	 echo "      <tr>\n"; 	 
	 echo "         <td valign=\"top\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  4 Columnas
   if (($nivel > 1) AND ($activo ==1)) {	
		  if ($iframe) {
         echo "			       <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id&iframe\" accept-charset=\"utf-8\" enctype=\"multipart/form-data\">\n";	
	    } else {
         echo "			       <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id#tab-3\" accept-charset=\"utf-8\" enctype=\"multipart/form-data\">\n";		 
	    } 
	    echo "               <tr>\n";      
			if (!$foto1) {
         echo "                  <td align=\"center\" width=\"50%\" colspan=\"2\">\n"; 
	       echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Subir Foto 1\">&nbsp&nbsp&nbsp&nbsp\n";
         if ($foto1_exists == "t") {				   		  
	          echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Borrar Foto 1\">\n";
				 }
	       echo "                  </td>\n";
			} else {
         if ($borrar_foto1) {
				    echo "                  <td align=\"right\" width=\"30%\">\n"; 
            echo "                     Está seguro de borrar la foto?\n";
            echo "                  </td>\n";			
	          echo "                  <td align=\"left\" width=\"20%\">\n";  #Col. 1 				   		 
            echo "                     <input type=\"hidden\" name=\"accion\" value=\"Borrar Foto 1\">\n";	  		 
	          echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"SI\">&nbsp&nbsp&nbsp&nbsp\n";
	          echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"NO\">\n";						
	          echo "                  </td>\n";				 
				 } else {
				    echo "                  <td align=\"right\" width=\"30%\">\n"; 
            echo "                     <input type=\"file\" name=\"file1\" class=\"smallText\">\n";
            echo "                  </td>\n";			
	          echo "                  <td align=\"left\" width=\"20%\">\n";  #Col. 1 				   		 
            echo "                     <input type=\"hidden\" name=\"accion\" value=\"Subir Foto 1\">\n";	  		 
	          echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Subir\">\n";
	          echo "                  </td>\n";
			   }	
			} 	 	 	 	  	 	     
      if (!$foto2) {
         echo "                  <td align=\"center\" width=\"50%\">\n"; 
	       echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Subir Foto 2\">&nbsp&nbsp&nbsp&nbsp\n";
#         echo "                     <input type=\"hidden\" name=\"accion\" value=\"$accion Edificaciones\">\n";					 
         echo "                     <input type=\"hidden\" name=\"cod_uv\" value=\"$cod_uv\">\n";	  		 
         echo "                     <input type=\"hidden\" name=\"cod_man\" value=\"$cod_man\">\n";	  		 
         echo "                     <input type=\"hidden\" name=\"cod_lote\" value=\"$cod_lote\">\n";	
         echo "                     <input type=\"hidden\" name=\"cod_subl\" value=\"$cod_subl\">\n";					   		 
         echo "                     <input type=\"hidden\" name=\"cod_cat\" value=\"$cod_cat\">\n";
				 if ($foto2_exists == "t") {	  
	          echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Borrar Foto 2\">\n";
				 }
	       echo "                  </td>\n";
			} else {
	       if ($borrar_foto2) {
				    echo "                  <td align=\"right\" width=\"30%\">\n"; 
            echo "                     Está seguro de borrar la foto?\n";
            echo "                  </td>\n";			
	          echo "                  <td align=\"left\" width=\"20%\">\n";  #Col. 1 		
	          echo "                     <input type=\"hidden\" name=\"cod_uv\" value=\"$cod_uv\">\n";	  		 
            echo "                     <input type=\"hidden\" name=\"cod_man\" value=\"$cod_man\">\n";	  		 
            echo "                     <input type=\"hidden\" name=\"cod_lote\" value=\"$cod_lote\">\n";	
            echo "                     <input type=\"hidden\" name=\"cod_subl\" value=\"$cod_subl\">\n";	  		 
            echo "                     <input type=\"hidden\" name=\"cod_cat\" value=\"$cod_cat\">\n";	 							   		 
            echo "                     <input type=\"hidden\" name=\"accion\" value=\"Borrar Foto 2\">\n";	  		 
	          echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"SI\">&nbsp&nbsp&nbsp&nbsp\n";
	          echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"NO\">\n";						
	          echo "                  </td>\n";
				 } else {				
				    echo "                  <td align=\"right\" width=\"30%\">\n"; 
            echo "                     <input type=\"file\" name=\"file1\" class=\"smallText\">\n";
            echo "                  </td>\n";			
	          echo "                  <td align=\"left\" width=\"20%\">\n";  #Col. 1 			
            echo "                     <input type=\"hidden\" name=\"cod_uv\" value=\"$cod_uv\">\n";	  		 
            echo "                     <input type=\"hidden\" name=\"cod_man\" value=\"$cod_man\">\n";
            echo "                     <input type=\"hidden\" name=\"cod_lote\" value=\"$cod_lote\">\n";	
            echo "                     <input type=\"hidden\" name=\"cod_subl\" value=\"$cod_subl\">\n";				 
            echo "                     <input type=\"hidden\" name=\"cod_cat\" value=\"$cod_cat\">\n";	  								   		 
            echo "                     <input type=\"hidden\" name=\"accion\" value=\"Subir Foto 2\">\n";	  		 
	          echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Subir\">\n";
	          echo "                  </td>\n";				 
         }		
			} 
      echo "               </tr>\n";
      echo "               </form>\n";						
	 } 
	 if ($error) {
	    echo "               <tr>\n";      
      echo "                  <td align=\"center\"colspan=\"4\">\n";
			echo "                     <font color=\"red\"> $mensaje_de_error</font>\n";
		  echo "                  </td>\n";			
      echo "               </tr>\n";			
   } elseif ((isset($_POST["accion"])) AND (($_POST["accion"] == "Borrar Foto 1") OR ($_POST["accion"] == "Borrar Foto 2") OR ($_POST["accion"] == "Subir Foto 1") OR ($_POST["accion"] == "Subir Foto 2"))) {
	    echo "               <tr>\n";      
      echo "                  <td align=\"center\"colspan=\"4\">\n";
			echo "                      <font color=\"orange\"> Aviso: Si no se ve los cambios realizados, por favor use el botón F5 para actualizar la pantalla!</font>\n";
		  echo "                  </td>\n";			
      echo "               </tr>\n";		 
	 }			 
	 echo "            </table>\n";  
	 echo "         </td>\n"; 
	 echo "      </tr>\n";	  
	 echo "      <tr>\n"; 	 
	 echo "         <td align=\"left\" height=\"20\" colspan=\"3\"></td>\n";   #Col. 1+2+3 	
	 echo "      </tr>\n";	 

#	 } else { # IF (!$resultado) {
#      echo "			<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=3&id=$session_id&iframe\" accept-charset=\"utf-8\">\n";	 
#	    echo "      <tr>\n";	
#	    echo "         <td align=\"center\" colspan=\"3\"> No se encuentran ningunos datos relacionados con el código en la base de datos.</td>\n";   #Col. 1+2+3 
#	    echo "      </tr>\n";	
#		  echo "      <tr>\n";
#			echo "         <td align=\"center\" colspan=\"2\"><input type='button' value='atrás' onClick='javascript:history.back();' style='font-family: Tahoma; font-size: 10pt; font-weight: bold'></td>\n";   #Col. 1+2	
#	    echo "         <td align=\"center\"><input name=\"accion\" type=\"submit\" class=\"smallText\" value=\"Añadir Información del Terreno\"></td>\n";   #Col. 3		 
#	    echo "      </tr>\n";
#	    echo "      </form>\n";							 	
#	 }
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
	 echo "   <br />&nbsp;<br />\n";

?>