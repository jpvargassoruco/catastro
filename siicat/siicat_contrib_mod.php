<?php

################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################		

   echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"420px\" height=\"100%\">\n";   # 3 Columnas
#   echo "			 <form name=\"form1\" method=\"post\" action=\"index.php?mod=122&id=$session_id\" accept-charset=\"utf-8\">\n";	
   echo "      <tr>\n";
   echo "         <td align=\"center\" width=\"50%\" class=\"bodyText\">\n";
   echo "			       <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=122&id=$session_id\" accept-charset=\"utf-8\">\n";
   echo "               <fieldset><legend>Modificar Contribuyente</legend>\n";	
   echo "                  <table border=\"0\" width=\"100%\">\n"; 	 	 
	 echo "                     <tr>\n";  	 
   echo "                        <td align=\"center\" width=\"80%\">\n";   #Col. 1			
   if ($nivel == 1) {
	    echo "                            No tiene el nivel de usuario para modificar los datos del contribuyente. \n";
	    echo "                            <br /><br /> &nbsp\n";							
 	 } else {
      echo "              Para modificar los datos del contribuyente<br />\n";		 
	    echo "                           <input name=\"id_contrib\" type=\"hidden\" value=\"$id_contrib\">\n";	 		  	
      echo "                           <input name=\"accion\" type=\"submit\" class=\"smallText\" value=\"Modificar\">\n";	
	 }
   echo "                        </td>\n";	 
   echo "                     </tr>\n";	
	 echo "                  </table>\n"; 		  
	 echo "               </fieldset>\n";	  
	 echo "            </form>\n";
   echo "         </td>\n";
   echo "         <td align=\"center\" width=\"50%\" class=\"bodyText\">\n";
   echo "			       <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=123&id=$session_id#tab-2\" accept-charset=\"utf-8\">\n";
   echo "               <fieldset><legend>Borrar Contribuyente</legend>\n";	
   echo "                  <table border=\"0\" width=\"100%\">\n"; 	 	 
	 echo "                     <tr>\n";  	 
   echo "                        <td align=\"center\" width=\"80%\">\n";   #Col. 1			
   if ($nivel == 1) {
	    echo "                            No tiene el nivel de usuario para borrar el contribuyente. \n";
	    echo "                            <br /><br /> &nbsp\n";							
 	 } else {
      echo "              Para borrar el contribuyente<br />\n";		 
	    echo "                           <input name=\"id_contrib\" type=\"hidden\" value=\"$id_contrib\">\n";	 		  	
      echo "                           <input name=\"accion\" type=\"submit\" class=\"smallText\" value=\"Borrar\">\n";	
	 }
   echo "                        </td>\n";	 
   echo "                     </tr>\n";	
	 echo "                  </table>\n"; 		  
	 echo "               </fieldset>\n";	  
	 echo "            </form>\n";
   echo "         </td>\n";  
 echo "      </tr>\n";	
# echo "      </form>\n";	 	 
 echo "   </table>\n";

?>