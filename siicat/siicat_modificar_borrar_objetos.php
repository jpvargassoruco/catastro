<?php  

$submit = $_POST["submit"];
$objeto = $_POST["object"];
$object_id = $_POST["object_id"];
#$description = $_POST["description"];
$imgext = $_POST["imgext"];
#include "fes_sessioninput.php";
$session_id = $_POST["session_id"];

#$new_description = $_POST["new_description"];

$error_shp = false;
$locked = false;
################################################################################
#------------------------------- MODIFICAR OBJETO -----------------------------#
################################################################################	 		
if ($submit == "Modificar objeto") {
   $titulo = "Modificar";
   if ($objeto == "Calle") {
	    $tipo = $_POST["tipo_calle"];
			if ($tipo == "Avenida") { $abrv = "Av.";
			} elseif ($tipo == "Calle") { $abrv = "C/";
			} elseif ($tipo == "Pasaje") { $abrv = "P/";
			} else $abrv = "";				 			
      $descrip = trim($_POST["new_description"]);	
	    if ($descrip == "") {
			   $descrip = "Sin Nombre";
				 $nombre = "N.N.";
			} else {
			   $nombre = $abrv." ".$descrip;
			}
		  pg_query("UPDATE calles SET tipo = '$tipo', descrip = '$descrip', nombre = '$nombre' WHERE observ ='$object_id'");	
			$shp_action = "Se ha modificado la calle en la base de datos!";	
      ########################################
	    #-------------- REGISTRO --------------#
	    ########################################
		  $username = get_username($session_id);
			$accion = "Objeto $objeto borrado";
		  pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		                 VALUES ('$username','$ip','$fecha','$hora','$accion','-')");				
   } else {
	    $shp_action = "NO se ha modificado el objeto!";	
   }
} 
################################################################################
#------------------------------- BORRAR OBJETO --------------------------------#
################################################################################	
else {
   $titulo = "Borrar";
	 $shp_action = "Se ha borrado el objeto de la base de datos!";
	 if ($objeto == "Calle") {				
		  pg_query("DELETE FROM calles WHERE observ = '$object_id'"); 	 
	 } elseif (($objeto == "Canal") OR ($objeto == "Limite") OR ($objeto == "Plaza")) {				
		  pg_query("DELETE FROM objetos_linea WHERE observ = '$object_id'"); 
	 } else {
		  pg_query("DELETE FROM objetos WHERE observ = '$object_id'"); 	 
	 }
      ########################################
	    #-------------- REGISTRO --------------#
	    ########################################
		  $username = get_username($session_id);
			$accion = "Objeto $objeto borrado";
		  pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		                 VALUES ('$username','$ip','$fecha','$hora','$accion','-')");	
	/*		 #########################################################################
       #------------------- RENUMERACION LABELS RESTANTES ---------------------#
       #########################################################################			    
		   $sql_renum="SELECT object_id FROM fes_load_shp";
       $check_objects = pg_num_rows(pg_query($sql_renum));
       if ($check_objects > 0) {
		      $i = $j = 1;
	 	      while ($check_objects >= 0) {
			       if ($i != $object_id) {
			          pg_query("UPDATE fes_load_shp SET object_id = '$j' WHERE object_id = '$i'");								
								$j++;
				     }
			       $i++;
				     $check_objects--;
			    } # END_OF_WHILE
		   } # END_OF_IF              */
#	 } else {
#	    $error_hp = true;
#			$errormessage = "NO puede borrar el objeto porque se ha deshabilitada la modificación (LOCKED)!";
#	 }		  	
} # END_OF_ELSE
		
###########################################################################################################
#----------------------------------------------- FORMULARIO ----------------------------------------------#
###########################################################################################################

 echo "<div align='center'><br /><br /><br />\n";	
 echo "   <form action=\"http://$server/cgi-bin/mapserv.exe?map=c:/apache/cat_br/mapa/catbr_zoom.map&layer=Predios&layer=Manzanos&layer=Lineas&layer=Calles&layer=Elementos especiales&zoomsize=2&mode=browse&program=c:/apache/cgi-bin/mapserv.exe&map_web=imagepath+'c:/apache/htdocs/tmp/'+imageurl+'http://$server/tmp/'\" method=\"post\" class=\"formular\">\n";  
# echo "   <form method='post' action='http://$server/catastro_br/index.php?mod=2' accept-charset='utf-8'>\n";	  
 echo "   <table border='1' width='300px' height='200px' style='font-family: Tahoma; font-size: 10pt'><font face='Tahoma' size='2'>\n";	
 echo "      <tr height='20'>\n";	
 echo "         <td align='center' width='300px' bordercolor='#CCCCCC' bgcolor='#CCCCCC'>\n";	
 echo "            <font color='black' size='2'><b>$titulo $objeto</b></font></p>\n";	
 echo "         </td>\n";	
 echo "      </tr>\n";	
 echo "		  <tr height='15'>\n";		
 echo "         <td align='center' bordercolor='#CCCCCC' bgcolor='#E9E9E9'>\n";	
 echo "             <b>$shp_action</b>\n";	 
 echo "         </td>\n";	
 echo "     </tr>\n";	  
 echo "     <tr height='20'>\n";	
 echo "        <td align='center' valign='center' bordercolor='#CCCCCC' bgcolor='#CCCCCC'>\n";	 		 
# echo "           <input type='button' value='Volver al plano' onClick='javascript:history.back();' style='font-family: Tahoma; font-size: 10pt; font-weight: bold'>\n";	
 echo "           <input type=\"submit\" name=\"submit\" value=\"Volver al mapa\">\n"; 
 echo "				</td>\n";	 
 echo "     </tr>\n";					
 echo " </table>\n";	    
 echo " </form>\n";		
 echo "</div>\n";		
	
/*	 echo "<div align=\"center\"><br /><br /><br />\n";	
   #echo "  <table background=\"graphics/background_select.png\" border=\"1\" align=\"center\" cellpadding=\"0\" width=\"300px\" height=\"100%\">\n";
   echo "  <table bgcolor=\"#ffffcc\" border=\"1\" align=\"center\" cellpadding=\"0\" width=\"300px\" height=\"100%\">\n";  
	 #Primera fila
   echo "     <tr height=\"24px\"><td width=\"100%\" align=\"center\" valign=\"center\">\n";
   echo "        <h2>Objetos geográficos en polígono \"$poly\"</h2>\n";
   echo "     </td></tr>\n";
   #Segunda fila
   echo "     <form action=\"http://$server/cgi-bin/mapserv.exe?map=c:/apache/cat_br/mapa/catbr_zoom.map&layer=Predios&layer=Manzanos&layer=Lineas&layer=Elementos especiales&zoomsize=2&mode=browse&program=c:/apache/cgi-bin/mapserv.exe&map_web_imagepath=c:/apache/htdocs/tmp/&map_web_imageurl=http://$server/tmp/\" method=\"post\" class=\"formular\">\n"; 
#	    echo "            <iframe frameborder=\"0\" name=\"mapserver\" src=\"http://localhost/cgi-bin/mapserv.exe?map=c:/apache/cat_br/mapa/catbr_zoom.map&layer=Predios&layer=Manzanos&layer=Lineas&layer=Elementos especiales&zoomsize=2&mode=browse&program=c:/apache/cgi-bin/mapserv.exe&map_web_imagepath=c:/apache/htdocs/tmp/&map_web_imageurl=http://$server/tmp/\" id=\"content\" width=\"800px\" height=\"565px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
	 echo "     <tr height=\"24px\" class=\"header\"><td width=\"100%\" align=\"center\">\n";		
	 if (!$error_shp) {
	    echo "        <br />$shp_action<br /><br />\n";
	 } else {
	    echo "        <br />$errormessage<br /><br />\n";
	 }	     			
   #echo "         <td align=\"center\">\n";
   #echo "            <input type=\"hidden\" name=\"poly\" value=\"$poly\">\n";
	 #include "fes_sessionoutput.php";
   echo "            <input type=\"submit\" name=\"submit\" value=\"Volver al mapa\"><br /><br />\n";
   #echo "         </td>\n";
   #echo "     <tr height=\"24px\" class=\"header\"><td width=\"100%\" align=\"center\">\n";
	 #echo "        <br />Se ha guardado la etiqueta en la base de datos!<br />\n"; 
	 #echo "        <br /><input type=\"button\" value=\"atrás\" onClick=\"javascript:history.back();javascript:history.back();\" style=\"font-family: Tahoma; font-size: 10pt; font-weight: bold\"><br /><br />\n";
	 #echo "        <a href=\"http://$server/cgi-bin/mapserv.exe?map=c:/apache/igm_fes/igm_fes.map&program=http://$server/cgi-bin/mapserv.exe&zoomsize=1&layer=Rios&layer=Division_Politica&layer=Poligono&layer=Lineas&layer=Predio&layer=Puntos&layer=Grid&imgext=$imgext&imgxy=700+700&mapext=&savequery=false&zoomdir=1&mode=browse&imgbox=&imgsize=1400+1400&mapsize=1400+1400\">Volver al mapa</a>\n";
   echo "     </td></tr>\n";    
	 echo "     </form>\n";
   echo " </table></div>\n"; */
		
?>
