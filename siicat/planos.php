<?php
echo "<table border=\"0\" width=\"800px\">\n";

	echo "<tr>&nbsp</tr>\n";  	
	echo "<tr>\n";  	 
		echo "<td align=\"center\" width=\"30%\">\n";
			echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=30&id=$session_id\" accept-charset=\"utf-8\">\n"; 	 
				echo "<div class=\"card\">\n";
					echo "<div class=\"card-body\">\n";
						echo "<h2 class=\"card-title\">Con coordenadas</h2>\n";
						echo "<p class=\"card-text\">Impresion del formulario de empadronamiento solo para el nivel 5.</p>\n";
						if ($nivel == 1) {
							echo "No tiene el nivel de usuario para ver el contenido. \n";
							echo "<br /><br /> &nbsp\n";	        
						} else {
							echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";	 		  	
							echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Plano con coord.\">\n";
						}
						echo "<p class=\"card-text\"> </p>\n";      
					echo "</div>\n";
				echo "</div>\n";
			echo "</form>\n";
		echo "</td>\n";

		echo "<td align=\"center\" width=\"30%\">\n";
			echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=35&id=$session_id\" accept-charset=\"utf-8\">\n"; 	 
				echo "<div class=\"card\">\n";
					echo "<div class=\"card-body\">\n";
						echo "<h2 class=\"card-title\">Sin coordenadas</h2>\n";
						echo "<p class=\"card-text\">Impresion del formulario de empadronamiento solo para el nivel 5.</p>\n";
						if ($nivel == 1) {
							echo "No tiene el nivel de usuario para ver el contenido. \n";
							echo "<br /><br /> &nbsp\n";	        
						} else {
							echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";	 		  	
							echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Plano sin coordenadas\">\n";
						}
						echo "<p class=\"card-text\"> </p>\n";      
					echo "</div>\n";
				echo "</div>\n";
			echo "</form>\n";
		echo "</td>\n";

		echo "<td align=\"center\" width=\"30%\">\n";
			echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=38&id=$session_id\" accept-charset=\"utf-8\">\n";   	 
				echo "<div class=\"card\">\n";
					echo "<div class=\"card-body\">\n";
						echo "<h2 class=\"card-title\">Con plano y ubicacion</h2>\n";
						echo "<p class=\"card-text\">Impresion del formulario de empadronamiento solo para el nivel 5.</p>\n";
						if ($nivel == 1) {
							echo "No tiene el nivel de usuario para ver el contenido. \n";
							echo "<br /><br /> &nbsp\n";	        
						} else {
							echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n"; 	 		 
							echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Certificado Con plano y ubicacion\">\n"; 
						}
						echo "<p class=\"card-text\"> </p>\n";      
					echo "</div>\n";
				echo "</div>\n";
			echo "</form>\n";
		echo "</td>\n";		

	echo "</tr>\n";

	echo "<tr>\n";


		echo "<td align=\"center\" width=\"30%\">\n";
        	echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=301&id=$session_id\" accept-charset=\"utf-8\">\n";				echo "<div class=\"card\">\n";
					echo "<div class=\"card-body\">\n";
						echo "<h2 class=\"card-title\">Plano del lote</h2>\n";
						echo "<p class=\"card-text\">Impresion del formulario de empadronamiento solo para el nivel 5.</p>\n";
						if ($nivel == 1) {
							echo "No tiene el nivel de usuario para ver el contenido. \n";
							echo "<br /><br /> &nbsp\n";	        
						} else {
							echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n"; 	 		 
							echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Plano del lote\">\n"; 
						}
						echo "<p class=\"card-text\"> </p>\n";      
					echo "</div>\n";
				echo "</div>\n";
			echo "</form>\n";
		echo "</td>\n";

		echo "<td align=\"center\" width=\"30%\">\n";
        	echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=302&id=$session_id\" accept-charset=\"utf-8\">\n";				echo "<div class=\"card\">\n";
					echo "<div class=\"card-body\">\n";
						echo "<h3 class=\"card-title\">Plano de Ubicacion y uso de suelo</h3>\n";
						echo "<p class=\"card-text\">Impresion del formulario de empadronamiento solo para el nivel 5.</p>\n";
						if ($nivel == 1) {
							echo "No tiene el nivel de usuario para ver el contenido. \n";
							echo "<br /><br /> &nbsp\n";	        
						} else {
							echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n"; 	 		 
							echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Plano del lote\">\n"; 
						}
						echo "<p class=\"card-text\"> </p>\n";      
					echo "</div>\n";
				echo "</div>\n";
			echo "</form>\n";
		echo "</td>\n";



    echo "</tr>\n";

	if (isset($_GET['plano'])) {
		echo "<tr>\n";
			echo "<td valign=\"top\" colspan=\"4\">\n";   #Col. 1 
			include "siicat_uso_de_suelo_generar.php";
			echo "<iframe frameborder=\"0\" name=\"mapserver\" src=\"http://$server/tmp/us$cod_cat.html\" id=\"content\" width=\"750px\" height=\"1270px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
			echo "</iframe>\n";	
			echo "</td>\n";	 
		echo "</tr>\n";	
	}
echo "</table>\n"; 	 	 
?>