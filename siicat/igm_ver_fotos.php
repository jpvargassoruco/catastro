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
#------------------------------ GENERAR COD_CAT -------------------------------#
################################################################################
$cod_cat = get_codcat_foto($cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto);

################################################################################
#------------------------- VERIFICARSI EXISTEN FOTOS --------------------------#
################################################################################	

#$filename1 = "C:/apache/htdocs/vallegande/fotos/".$cod_cat.".jpg";
#$filename2 = "C:/apache/htdocs/vallegande/fotos/".$cod_cat."A.jpg";

$filename1 = "C:/apache/htdocs/".$folder."/fotos/".$cod_cat.".jpg";
$filename2 = "C:/apache/htdocs/".$folder."/fotos/".$cod_cat."A.jpg";
$filename3 = "C:/apache/htdocs/".$folder."/fotos/".$cod_cat."B.jpg";
$filename4 = "C:/apache/htdocs/".$folder."/fotos/".$cod_cat."C.jpg";

if (file_exists($filename1)) {	 
	$foto1_exists = "t";
} else $foto1_exists = "f";

if (file_exists($filename2)) {   
	$foto2_exists = "t";
} else $foto2_exists = "f";	

if (file_exists($filename3)) {   
	$foto3_exists = "t";
} else $foto3_exists = "f";

if (file_exists($filename4)) {   
	$foto4_exists = "t";
} else $foto4_exists = "f";	

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
	 include "igm_upload_fotos.php";
	 if ($error) {
	    $foto1 = true;
	 } else { 
			$accion = "Foto subida";
		  pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
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
		pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
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
	 include "igm_upload_fotos.php";
	 if ($error) {
	    $foto2 = true;
	 } else {
			$accion = "Foto subida";
		  pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
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
   $fotopath = "C:/apache/htdocs/$folder/fotos/".$cod_cat."A.JPG";		
   if ($foto2_exists == "t") {   
      unlink($fotopath);
			$foto2_exists = "f";
			$accion = "Foto borrada";
		  pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");	
	 } 
}




################################################################################
#------------------------------ ELEGIR FOTO 3 ---------------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Subir Foto 3")) {
   $foto3 = true;
}
################################################################################
#------------------------------ SUBIR FOTO 3 -----------------------------------#
################################################################################	
if ((isset($_POST["accion"])) AND (($_POST["accion"]) == "Subir Foto 3")) {
   $no_de_foto = 3;
	 include "igm_upload_fotos.php";
	 if ($error) {
	    $foto3 = true;
	 } else {
			$accion = "Foto subida";
		  pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");				 
	 }
}
################################################################################
#------------------------------ BORRAR FOTO 3 ---------------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Borrar Foto 3")) {
   $foto3 = true;   
	 $borrar_foto3 = true;
}
################################################################################
#------------------------ CONFIRMAR BORRAR FOTO 3 -----------------------------#
################################################################################	
if ((isset($_POST["accion"])) AND (($_POST["accion"]) == "Borrar Foto 3") AND (($_POST["submit"]) == "SI") ) {																		 
   $fotopath = "C:/apache/htdocs/$folder/fotos/".$cod_cat."B.JPG";		
   if ($foto3_exists == "t") {   
      unlink($fotopath);
			$foto3_exists = "f";
			$accion = "Foto borrada";
		  pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");	
	 } 
}


################################################################################
#------------------------------ ELEGIR FOTO 4 ---------------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Subir Foto 4")) {
   $foto4 = true;
}
################################################################################
#------------------------------ SUBIR FOTO 4 -----------------------------------#
################################################################################	
if ((isset($_POST["accion"])) AND (($_POST["accion"]) == "Subir Foto 4")) {
   $no_de_foto = 4;
	 include "igm_upload_fotos.php";
	 if ($error) {
	    $foto4 = true;
	 } else {
			$accion = "Foto subida";
		  pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");				 
	 }
}
################################################################################
#------------------------------ BORRAR FOTO 4 ---------------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Borrar Foto 4")) {
	$foto4 = true;   
	$borrar_foto4 = true;
}
################################################################################
#------------------------ CONFIRMAR BORRAR FOTO 4 -----------------------------#
################################################################################	
if ((isset($_POST["accion"])) AND (($_POST["accion"]) == "Borrar Foto 4") AND (($_POST["submit"]) == "SI") ) {																		 
   $fotopath = "C:/apache/htdocs/$folder/fotos/".$cod_cat."C.JPG";		
   if ($foto4_exists == "t") {   
      unlink($fotopath);
			$foto4_exists = "f";
			$accion = "Foto borrada";
		  pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");	
	 } 
}


$error = false;

################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################		

echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas

##################################################
#------------------- FOTOS 1---------------------#
##################################################	
echo "<tr>\n";
echo "<td valign=\"top\" height=\"40\" colspan=\"3\">\n";  
echo "<fieldset><legend>Fotos del $cod_cat</legend>\n";
echo "<table border=\"0\" width=\"100%\">\n";
if (($foto1_exists == "t") OR ($foto2_exists == "t")) {	 
	echo "<tr>\n";
		echo "<td align=\"right\" colspan=\"2\" class=\"bodyText_Small\"></td>\n";   
		echo "</tr>\n";	
		echo "<tr>\n";
		echo "<td width=\"50%\">\n";
		$fotos1=$cod_cat.".JPG";
		echo "<img border=\"0\" src=\"http://$server/$folder/fotos/$fotos1\" width=\"100%\" height=\"270\">\n";
		echo "</td>\n";   	 	 	 	  	 	     
		echo "<td width=\"50%\">\n";  
		$fotos2=$cod_cat."A.JPG";
		echo "<img border=\"0\" src=\"http://$server/$folder/fotos/$fotos2\" width=\"100%\" height=\"270\">\n";
		echo "</td>\n";  	 	    
	echo "</tr>\n";	
} else {
	echo "<tr>\n";
	echo "<td align=\"center\" class=\"alert alert-danger\">No se encuentra la foto1 y foto2 del predio $cod_cat!</td>\n";	
	echo "</tr>\n";			 
}	  
echo "</table>\n"; 
echo "</fieldset>\n";	 	 
echo "</td>\n"; 
echo "</tr>\n";



##################################################
#---------------- BOTONES 1 ---------------------#
##################################################	

echo "<tr>\n"; 	 
echo "<td valign=\"top\" colspan=\"3\">\n"; 
echo "<table border=\"0\" width=\"100%\">\n";
if ($nivel > 1) {
	if ($iframe) {
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id&iframe\" accept-charset=\"utf-8\" enctype=\"multipart/form-data\">\n";	
	} else {
	echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id#tab-3\" accept-charset=\"utf-8\" enctype=\"multipart/form-data\">\n";		 
    } 
    echo "<tr>\n";      
		if (!$foto1) {
      echo "<td align=\"center\" width=\"50%\" colspan=\"2\">\n"; 
       echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Subir Foto 1\">&nbsp&nbsp&nbsp&nbsp\n";
      if ($foto1_exists == "t") {				   		  
          echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Borrar Foto 1\">\n";
			 }
       echo "                  </td>\n";
		} else {
		if ($borrar_foto1) {
			echo "<td align=\"right\" width=\"30%\">\n"; 
			echo "Est� seguro de borrar la foto  $fotos1?\n";
			echo "</td>\n";			
			echo "<td align=\"left\" width=\"20%\">\n";  #Col. 1 				   		 
			echo "<input type=\"hidden\" name=\"accion\" value=\"Borrar Foto 1\">\n";	  		 
			echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"SI\">&nbsp&nbsp&nbsp&nbsp\n";
			echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"NO\">\n";						
			echo "</td>\n";				 
			} else {
			echo "<td align=\"right\" width=\"30%\">\n"; 
			echo "<input type=\"file\" name=\"file1\" class=\"smallText\">\n";
			echo "</td>\n";			
			echo "<td align=\"left\" width=\"20%\">\n";  #Col. 1 				   		 
			echo "<input type=\"hidden\" name=\"accion\" value=\"Subir Foto 1\">\n";	  		 
			echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Subir\">\n";
			echo "</td>\n";
			}	
		} 	 	 	 	  	 	     
	if (!$foto2) {
		echo "<td align=\"center\" width=\"50%\">\n"; 
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Subir Foto 2\">&nbsp&nbsp&nbsp&nbsp\n";
		echo "<input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";
		if ($foto2_exists == "t") {	  
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Borrar Foto 2\">\n";
		}
		echo "</td>\n";
		} else {
	if ($borrar_foto2) {
		echo "<td align=\"right\" width=\"30%\">\n"; 
		echo "Est� seguro de borrar la foto  $fotos2?\n";
		echo "</td>\n";			
		echo "<td align=\"left\" width=\"20%\">\n";  #Col. 1 		
		echo "<input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";	 							   		 
		echo "<input type=\"hidden\" name=\"accion\" value=\"Borrar Foto 2\">\n";	  		 
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"SI\">&nbsp&nbsp&nbsp&nbsp\n";
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"NO\">\n";						
		echo "</td>\n";
	} else {				
		echo "<td align=\"right\" width=\"30%\">\n"; 
		echo "<input type=\"file\" name=\"file1\" class=\"smallText\">\n";
		echo "</td>\n";			
		echo "<td align=\"left\" width=\"20%\">\n";  #Col. 1 			
		echo "<input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";	  								   		 
		echo "<input type=\"hidden\" name=\"accion\" value=\"Subir Foto 2\">\n";	  		 
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Subir\">\n";
		echo "</td>\n";				 
	}		
} 
echo "</tr>\n";
echo "</form>\n";						
} 
if ($error) {
	echo "<tr>\n";      
	echo "<td align=\"center\"colspan=\"4\">\n";
	echo "<font color=\"red\"> $mensaje_de_error</font>\n";
	echo "</td>\n";			
	echo "</tr>\n";			
} elseif ((isset($_POST["accion"])) AND (($_POST["accion"] == "Borrar Foto 1") OR ($_POST["accion"] == "Borrar Foto 2") OR ($_POST["accion"] == "Subir Foto 1") OR ($_POST["accion"] == "Subir Foto 2"))) {
echo "<tr>\n";      
echo "<td align=\"center\"colspan=\"4\">\n";
echo "<font color=\"orange\"> Aviso: Con el bot�n F5 para actualizar la pantalla!</font>\n";
echo "</td>\n";			
echo "</tr>\n";		 
}			 
echo "</table>\n";  
echo "</td>\n"; 
echo "</tr>\n";





##################################################
#------------------- FOTOS 2---------------------#
##################################################	
echo "<tr>\n";
echo "<td valign=\"top\" height=\"40\" colspan=\"3\">\n";  
echo "<fieldset><legend>Fotos del $cod_cat B</legend>\n";
echo "<table border=\"0\" width=\"100%\">\n";
if (($foto3_exists == "t") OR ($foto4_exists == "t")) {	 
	echo "<tr>\n";
		echo "<td align=\"right\" colspan=\"2\" class=\"bodyText_Small\"></td>\n";   
	echo "</tr>\n";	
	echo "<tr>\n";
		echo "<td width=\"50%\">\n";
		$fotos3=$cod_cat."B.JPG";
		echo "<img border=\"0\" src=\"http://$server/$folder/fotos/$fotos3\" width=\"100%\" height=\"270\">\n";
		echo "</td>\n";   	 	 	 	  	 	     
		echo "<td width=\"50%\">\n";  
		$fotos4=$cod_cat."C.JPG";
		echo "<img border=\"0\" src=\"http://$server/$folder/fotos/$fotos4\" width=\"100%\" height=\"270\">\n";
		echo "</td>\n";  	 	    
	echo "</tr>\n";	
} else {
	echo "<tr>\n";
	echo "<td align=\"center\" class=\"alert alert-danger\">No se encuentra la foto3 y foto4 del predio $cod_cat!!</td>\n";
	echo "</tr>\n";			 
}	  
echo "</table>\n"; 
echo "</fieldset>\n";	 	 
echo "</td>\n"; 
echo "</tr>\n";

##################################################
#------------------- BOTONES 2-------------------#
##################################################	
echo "<tr>\n"; 	 
echo "<td valign=\"top\" colspan=\"3\">\n"; 
echo "<table border=\"0\" width=\"100%\">\n";
if ($nivel > 1) {
	if ($iframe) {
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id&iframe\" accept-charset=\"utf-8\" enctype=\"multipart/form-data\">\n";	
	} else {
	echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id#tab-3\" accept-charset=\"utf-8\" enctype=\"multipart/form-data\">\n";		 
    } 
    echo "<tr>\n";      
	if (!$foto3) {
		echo "<td align=\"center\" width=\"50%\" colspan=\"2\">\n"; 
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Subir Foto 3\">&nbsp&nbsp&nbsp&nbsp\n";
		if ($foto3_exists == "t") {				   		  
			echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Borrar Foto 3\">\n";
		}
		echo "</td>\n";
	} else {
	if ($borrar_foto3) {
		echo "<td align=\"right\" width=\"30%\">\n"; 
		echo "Est� seguro de borrar la foto $fotos3?\n";
		echo "</td>\n";			
		echo "<td align=\"left\" width=\"20%\">\n";  #Col. 1 				   		 
		echo "<input type=\"hidden\" name=\"accion\" value=\"Borrar Foto 3\">\n";	  		 
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"SI\">&nbsp&nbsp&nbsp&nbsp\n";
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"NO\">\n";						
		echo "</td>\n";				 
		} else {
		echo "<td align=\"right\" width=\"30%\">\n"; 
		echo "<input type=\"file\" name=\"file1\" class=\"smallText\">\n";
		echo "</td>\n";			
		echo "<td align=\"left\" width=\"20%\">\n";  #Col. 1 				   		 
		echo "<input type=\"hidden\" name=\"accion\" value=\"Subir Foto 3\">\n";	  		 
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Subir\">\n";
		echo "</td>\n";
		}	
	} 	 	 	 	  	 	     
	if (!$foto4) {
		echo "<td align=\"center\" width=\"50%\">\n"; 
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Subir Foto 4\">&nbsp&nbsp&nbsp&nbsp\n";
		echo "<input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";
		if ($foto4_exists == "t") {	  
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Borrar Foto 4\">\n";
		}
		echo "</td>\n";
		} else {
	if ($borrar_foto4) {
		echo "<td align=\"right\" width=\"30%\">\n"; 
		echo "Est� seguro de borrar la foto  $fotos4?\n";
		echo "</td>\n";			
		echo "<td align=\"left\" width=\"20%\">\n";	
		echo "<input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";	 							   		 
		echo "<input type=\"hidden\" name=\"accion\" value=\"Borrar Foto 4\">\n";	  		 
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"SI\">&nbsp&nbsp&nbsp&nbsp\n";
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"NO\">\n";						
		echo "</td>\n";
	} else {				
		echo "<td align=\"right\" width=\"30%\">\n"; 
		echo "<input type=\"file\" name=\"file1\" class=\"smallText\">\n";
		echo "</td>\n";			
		echo "<td align=\"left\" width=\"20%\">\n"; 	
		echo "<input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";	  								   		 
		echo "<input type=\"hidden\" name=\"accion\" value=\"Subir Foto 4\">\n";	  		 
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Subir\">\n";
		echo "</td>\n";				 
	}		
} 
echo "</tr>\n";
echo "</form>\n";						
} 
if ($error) {
	echo "<tr>\n";      
	echo "<td align=\"center\"colspan=\"4\">\n";
	echo "<font color=\"red\"> $mensaje_de_error</font>\n";
	echo "</td>\n";			
	echo "</tr>\n";			
} elseif ((isset($_POST["accion"])) AND (($_POST["accion"] == "Borrar Foto 3") OR ($_POST["accion"] == "Borrar Foto 4") OR ($_POST["accion"] == "Subir Foto 3") OR ($_POST["accion"] == "Subir Foto 4"))) {
echo "<tr>\n";      
echo "<td align=\"center\"colspan=\"4\">\n";
echo "<font color=\"orange\"> Aviso: Con el bot�n F5 para actualizar la pantalla!</font>\n";
echo "</td>\n";			
echo "</tr>\n";		 
}			 
echo "</table>\n";  
echo "</td>\n"; 
echo "</tr>\n";







echo "<tr>\n"; 	 
echo "<td align=\"left\" height=\"20\" colspan=\"3\"></td>\n";   #Col. 1+2+3 	
echo "</tr>\n";	 
# Ultima Fila
echo "<tr height=\"100%\"></tr>\n";			 
echo "</table>\n";
echo "<br />&nbsp;<br />\n";

?>