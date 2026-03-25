<?php


################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################		

 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"420px\" height=\"100%\">\n";   # 3 Columnas
 echo "			 <form name=\"form1\" method=\"post\" action=\"index.php?mod=103&id=$session_id\" accept-charset=\"utf-8\">\n";	
 echo "      <tr>\n";
 echo "      <td align=\"center\" class=\"bodyText\">\n";
 echo "         Para modificar los datos de la actividad económica presione aki:<br />\n";	
 echo "         <input name=\"act_pat\" type=\"hidden\" value=\"$act_pat\">\n";  
 echo "         <input name=\"accion\" type=\"submit\" class=\"smallText\" value=\"Modificar\">\n"; 
 echo "         </td>\n"; 
 echo "      </tr>\n";	
 echo "      </form>\n";	 	 
 echo "   </table>\n";

?>