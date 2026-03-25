<?php
$foto1 = $foto2 = false;

#$cod_cat = get_codcat_foto($cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto);
$cod_fot = get_codcat_foto($cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto);
$filename1 = "C:/apache/htdocs/".$folder."/fotos/".$cod_fot.".jpg";
$filename2 = "C:/apache/htdocs/".$folder."/fotos/".$cod_fot."-A.jpg";

if (file_exists($filename1)) {	 
   $foto1_exists = "t";
} else $foto1_exists = "f";	   
if (file_exists($filename2)) {   
   $foto2_exists = "t";
} else $foto2_exists = "f";	

$error = false;

###########################################################################
#---------------------------------- FOTOS --------------------------------#
###########################################################################		

echo "<table border=\"0\" cellpadding=\"0\" width=\"200px\" height=\"200px\">\n";
if ($foto1_exists == "t") {	 
	echo "<tr><td align=\"right\" colspan=\"2\" class=\"bodyText_Small\"></td></tr>\n";   
	echo "<tr>\n";
	echo "<td width=\"50%\">\n";
	echo "<img border=\"0\" src=\"http://$server/$folder/fotos/$cod_fot.JPG\" width=\"200px\" height=\"170\">\n";
	echo "</td>\n";   	 	 	 	  	 	     
	echo "</tr>\n";	
	} else {
	echo "<img src=\"graphics/siicat_sin_geometria.png\" height=\"150\" width=\"150\" alt=\"Sin geometría\" title=\"Sin geometría\">\n";
}	  
echo "</table>\n";
echo "<br />&nbsp;<br />\n";
?>