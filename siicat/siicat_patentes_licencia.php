<?php


################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################		

 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"420px\" height=\"100%\">\n";   # 3 Columnas
 echo "			 <form name=\"form1\" method=\"post\" action=\"index.php?mod=105&id=$session_id\" accept-charset=\"utf-8\">\n";	
 echo "      <tr>\n";
 echo "      <td align=\"center\" class=\"bodyText\">\n";
 echo "         Para imprimir la licencia de funcionamiento presione aki:<br />\n";	
 echo "         <input name=\"id_patente\" type=\"hidden\" value=\"$id_patente\">\n";  
 echo "         <input name=\"accion\" type=\"submit\" class=\"smallText\" value=\"Imprimir Licencia\">\n"; 
 echo "         </td>\n"; 
 echo "      </tr>\n";	
 echo "      </form>\n";	 	 
 echo "   </table>\n";

?>