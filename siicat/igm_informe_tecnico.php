<?php
 echo "<td>\n";
 	echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"770px\">\n";
 		echo "<tr height=\"40px\">\n";
 		echo "<td align=\"left\">\n";
 		echo "&nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&id=$session_id#tab-4\" alt='' title='Volver a la pantalla anterior'>\n";		
 		echo "<img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";	
 		echo "</td>\n";  
 		echo "</tr>\n";	 
 		echo "<tr>\n";
 		echo "<td valign=\"top\">\n";	 
 		include "igm_informe_tecnico_generar.php";
 		echo "<iframe frameborder=\"0\" name=\"mapserver\" src=\"http://$server/tmp/informetecnico$cod_cat.html\" id=\"content\" width=\"750px\" height=\"1270px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
 		echo "</iframe>\n";	
 		echo "</td>\n";	 
 		echo "</tr>\n";	 		
 	echo "</table>\n";
 echo "</td>\n";
?>