<?php
echo "<table border=\"0\" width=\"800px\">\n";

    echo "<tr>&nbsp</tr>\n";  
    echo "<tr>\n";  	 
        echo "<td align=\"center\" width=\"30%\">\n";
            echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=320&id=$session_id\" accept-charset=\"utf-8\">\n"; 	 
                echo "<div class=\"card\">\n";
                    echo "<div class=\"card-body\">\n";
                        echo "<h2 class=\"card-title\">Empadronamiento</h2>\n";
                        echo "<p class=\"card-text\">Impresion del formulario de empadronamiento solo para el nivel 5.</p>\n";
                        if ($nivel == 1) {
                            echo "No tiene el nivel de usuario para ver el contenido. \n";
                            echo "<br /><br /> &nbsp\n";	        
                        } else {
                            echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";	
                            echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Formulario Empadronamiento\" disabled>\n";
                        }
                        echo "<p class=\"card-text\"> </p>\n";      
                    echo "</div>\n";
                echo "</div>\n";
            echo "</form>\n";
        echo "</td>\n";

        echo "<td align=\"center\" width=\"30%\">\n";
            echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=321&id=$session_id\" accept-charset=\"utf-8\">\n"; 	 
                echo "<div class=\"card\">\n";
                    echo "<div class=\"card-body\">\n";
                        echo "<h2 class=\"card-title\">Empadronamiento 2</h2>\n";
                        echo "<p class=\"card-text\">Impresion del formulario de empadronamiento solo para el nivel 5.</p>\n";
                        if ($nivel == 1) {
                            echo "No tiene el nivel de usuario para ver el contenido. \n";
                            echo "<br /><br /> &nbsp\n";	        
                        } else {
                            echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";	
                            echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Formulario Empadronamiento\">\n";
                        }
                        echo "<p class=\"card-text\"> </p>\n";      
                    echo "</div>\n";
                echo "</div>\n";
            echo "</form>\n";
        echo "</td>\n";

        echo "<td align=\"center\" width=\"30%\">\n";
            echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=322&id=$session_id\" accept-charset=\"utf-8\">\n"; 	 
                echo "<div class=\"card\">\n";
                    echo "<div class=\"card-body\">\n";
                        echo "<h2 class=\"card-title\">Empadronamiento 3</h2>\n";
                        echo "<p class=\"card-text\">Impresion del formulario de empadronamiento solo para el nivel 5.</p>\n";
                        if ($nivel == 1) {
                            echo "No tiene el nivel de usuario para ver el contenido. \n";
                            echo "<br /><br /> &nbsp\n";	        
                        } else {
                            echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";	
                            echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Formulario Empadronamiento\" disabled>\n";
                        }
                        echo "<p class=\"card-text\"> </p>\n";      
                    echo "</div>\n";
                echo "</div>\n";
            echo "</form>\n";
        echo "</td>\n";
    echo "</tr>\n"; 



    echo "<tr>\n";
        echo "<td align=\"center\" width=\"30%\">\n";
            echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=323&id=$session_id\" accept-charset=\"utf-8\">\n"; 	 
                echo "<div class=\"card\">\n";
                    echo "<div class=\"card-body\">\n";
                        echo "<h2 class=\"card-subtitle mb-2 text-body-secondary\">Informe Técnico</h2>\n";
                        echo "<p class=\"card-text\">Impresion del Informe Técnico, Hoja tamano oficio, solo para el nivel 5.</p>\n";
                        if ($nivel == 1) {
                            echo "No tiene el nivel de usuario para ver el contenido. \n";
                            echo "<br /><br /> &nbsp\n";	        
                        } else {
                            echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";	 		  	
                            echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Informe Técnico\">\n";
                        }
                        echo "<p class=\"card-text\"> </p>\n"; 
                    echo "</div>\n";
                echo "</div>\n";
            echo "</form>\n";
        echo "</td>\n";

        echo "<td align=\"center\" width=\"30%\">\n";
            echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=324&id=$session_id\" accept-charset=\"utf-8\">\n"; 	 
                echo "<div class=\"card\">\n";
                    echo "<div class=\"card-body\">\n";
                        echo "<h2 class=\"card-subtitle mb-2 text-body-secondary\">Técnico con plano 2</h2>\n";
                        echo "<p class=\"card-text\">Impresion del Informe Técnico con plano, Hoja tamano oficio, solo para el nivel 5.</p>\n";
                        if ($nivel == 1) {
                            echo "No tiene el nivel de usuario para ver el contenido. \n";
                            echo "<br /><br /> &nbsp\n";	        
                        } else {
                            echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";	 		  	
                            echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Técnico con plano\">\n";
                        }
                        echo "<p class=\"card-text\"> </p>\n"; 
                    echo "</div>\n";
                echo "</div>\n";
            echo "</form>\n";
        echo "</td>\n";

        echo "<td align=\"center\" width=\"30%\">\n";
            echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=325&id=$session_id\" accept-charset=\"utf-8\">\n"; 	 
                echo "<div class=\"card\">\n";
                    echo "<div class=\"card-body\">\n";
                        echo "<h2 class=\"card-subtitle mb-2 text-body-secondary\">Técnico con plano 3</h2>\n";
                        echo "<p class=\"card-text\">Impresion del Informe Técnico con plano, Hoja tamano oficio, solo para el nivel 5.</p>\n";
                        if ($nivel == 1) {
                            echo "No tiene el nivel de usuario para ver el contenido. \n";
                            echo "<br /><br /> &nbsp\n";	        
                        } else {
                            echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";	 		  	
                            echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Técnico con plano\">\n";
                        }
                        echo "<p class=\"card-text\"> </p>\n"; 
                    echo "</div>\n";
                echo "</div>\n";
            echo "</form>\n";
        echo "</td>\n";
    echo "</tr>\n";  



    echo "<tr>\n";
        echo "<td align=\"center\" width=\"30%\">\n";
            echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=33&id=$session_id\" accept-charset=\"utf-8\">\n"; 	 
                echo "<div class=\"card\">\n";
                    echo "<div class=\"card-body\">\n";
                        echo "<h2 class=\"card-title\">Uso de Suelo</h2>\n";
                        echo "<p class=\"card-text\">Impresion del Uso de Suelo, Hoja tamano oficio solo para el nivel 5.</p>\n";
                        if ($nivel == 1) {
                            echo "No tiene el nivel de usuario para ver el contenido. \n";
                            echo "<br /><br /> &nbsp\n";	        
                        } else {
                            echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";		 		  	
                            echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Uso de Suelo\">\n";
                        } 
                        echo "<p class=\"card-text\"> </p>\n";      
                    echo "</div>\n";
                echo "</div>\n";
            echo "</form>\n";
        echo "</td>\n";



        echo "<td align=\"center\" width=\"30%\">\n";
            echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=34&id=$session_id\" accept-charset=\"utf-8\">\n"; 	 
                echo "<div class=\"card\">\n";
                    echo "<div class=\"card-body\">\n";
                        echo "<h2 class=\"card-title\">Linea y Nivel</h2>\n";
                        echo "<p class=\"card-text\">Impresion de la linea nivel, Hoja tamano oficio, solo para el nivel 5.</p>\n";
                        if ($nivel == 1) {
                            echo "No tiene el nivel de usuario para ver el contenido. \n";
                            echo "<br /><br /> &nbsp\n";	        
                        } else {
                            echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";		 		  	
                            echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Linea y Nivel\">\n";
                        } 
                        echo "<p class=\"card-text\"> </p>\n";      
                    echo "</div>\n";
                echo "</div>\n";
            echo "</form>\n";
        echo "</td>\n";        

        echo "<td align=\"center\" width=\"30%\">\n";
            echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=341&id=$session_id\" accept-charset=\"utf-8\">\n"; 	 
                echo "<div class=\"card\">\n";
                    echo "<div class=\"card-body\">\n";
                        echo "<h2 class=\"card-title\">Linea y Nivel 2</h2>\n";
                        echo "<p class=\"card-text\">Impresion de la linea nivel, Hoja tamano oficio, solo para el nivel 5.</p>\n";
                        if ($nivel == 1) {
                            echo "No tiene el nivel de usuario para ver el contenido. \n";
                            echo "<br /><br /> &nbsp\n";	        
                        } else {
                            echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";		 		  	
                            echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Linea y Nivel\">\n";
                        } 
                        echo "<p class=\"card-text\"> </p>\n";      
                    echo "</div>\n";
                echo "</div>\n";
            echo "</form>\n";
        echo "</td>\n"; 
    echo "</tr>\n";




    echo "<tr>\n";


        echo "<td align=\"center\" width=\"30%\">\n";
            echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=31&id=$session_id\" accept-charset=\"utf-8\">\n"; 	 
                echo "<div class=\"card\">\n";
                    echo "<div class=\"card-body\">\n";
                        echo "<h2 class=\"card-title\">Certificado Catastral</h2>\n";
                        echo "<p class=\"card-text\">Impresion del Certificcado Catastral sin plano, Hoja Oficio, Suelo solo para el nivel 5.</p>\n";
                        if ($nivel == 1) {
                            echo "No tiene el nivel de usuario para ver el contenido. \n";
                            echo "<br /><br /> &nbsp\n";	        
                        } else {
                            echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";		 		  	
                            echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Certificado Catastral sin plano\">\n";
                        }   
                        echo "<p class=\"card-text\"> </p>\n";    
                    echo "</div>\n";
                echo "</div>\n";
            echo "</form>\n";
        echo "</td>\n";

		echo "<td align=\"center\" width=\"30%\">\n";
			echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=37&id=$session_id\" accept-charset=\"utf-8\">\n";  	 
				echo "<div class=\"card\">\n";
					echo "<div class=\"card-body\">\n";
						echo "<h2 class=\"card-title\">Certificado Con plano 1</h2>\n";
						echo "<p class=\"card-text\">Impresion del formulario de empadronamiento solo para el nivel 5.</p>\n";
						if ($nivel == 1) {
							echo "No tiene el nivel de usuario para ver el contenido. \n";
							echo "<br /><br /> &nbsp\n";	        
						} else {
							echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n"; 	 		 
							echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Certificado Con plano 1\">\n";
						}
						echo "<p class=\"card-text\"> </p>\n";      
					echo "</div>\n";
				echo "</div>\n";
			echo "</form>\n";
		echo "</td>\n";

		echo "<td align=\"center\" width=\"30%\">\n";
			echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=371&id=$session_id\" accept-charset=\"utf-8\">\n";  	 
				echo "<div class=\"card\">\n";
					echo "<div class=\"card-body\">\n";
						echo "<h2 class=\"card-title\">Certificado Con plano 2</h2>\n";
						echo "<p class=\"card-text\">Impresion del formulario de empadronamiento solo para el nivel 5.</p>\n";
						if ($nivel == 1) {
							echo "No tiene el nivel de usuario para ver el contenido. \n";
							echo "<br /><br /> &nbsp\n";	        
						} else {
							echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n"; 	 		 
							echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Certificado Con plano 2\">\n";
						}
						echo "<p class=\"card-text\"> </p>\n";      
					echo "</div>\n";
				echo "</div>\n";
			echo "</form>\n";
		echo "</td>\n";     	 	 	     		  
    echo "</tr>\n";

    echo "<tr>\n";
        echo "<td align=\"center\" width=\"30%\">\n";
            echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=36&id=$session_id\" accept-charset=\"utf-8\">\n"; 	 
                echo "<div class=\"card\">\n";
                    echo "<div class=\"card-body\">\n";
                        echo "<h2 class=\"card-title\">Aprobacion de plano</h2>\n";
                        echo "<p class=\"card-text\">Informe de aprobacion de plano, Hoja tamano oficio, solo para el nivel 5.</p>\n";
                        if ($nivel == 1) {
                            echo "No tiene el nivel de usuario para ver el contenido. \n";
                            echo "<br /><br /> &nbsp\n";	        
                        } else {
                            echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";		 		  	
                            echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Aprobacion de plano\">\n";
                        }      
                        echo "<p class=\"card-text\"> </p>\n"; 
                    echo "</div>\n";
                echo "</div>\n";
            echo "</form>\n";
        echo "</td>\n";
    echo "</tr>\n";
    

	if (isset($_GET['informe'])) {
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