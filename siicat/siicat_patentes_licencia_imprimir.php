<?php

 echo "<td>\n";
 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"770px\">\n";   # 3 Columnas
 echo "      <tr height=\"40px\">\n";
 echo "         <td align=\"left\" width=\"15%\">\n";  #Col. 1 
 #echo "            &nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&id=$session_id#tab-5\" alt='' title='Volver a la pantalla anterior'>\n";		
 echo "            &nbsp&nbsp <a href='javascript:history.back()'>\n";	
#   echo "            <img border='1' src='http://$server/$folder/graphics/boton_atras.png' width='35' height='35'></a>\n"; 
 echo "            <img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";	
 echo "         </td>\n"; 
 echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n"; 
 echo "            Licencia de Funcionamiento\n"; 	                           
 echo "         </td>\n";
 echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3   
 echo "      </tr>\n";	 
 echo "      <tr>\n";
 echo "         <td valign=\"top\" colspan=\"3\">\n";   #Col. 1 
# echo "          <a href='javascript:history.back()'>\n";		
# echo "           <img border='0' src='http://$server/siicat_concep/graphics/boton_atras.png' width='35' height='35'></a>\n";
 include "siicat_patentes_licencia_generar.php";
 echo "            <iframe frameborder=\"0\" name=\"mapserver\" src=\"http://$server/tmp/lic$id_patente.html\" id=\"content\" width=\"750px\" height=\"1270px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
 echo "            </iframe>\n";	
 echo "         </td>\n";	 
 echo "      </tr>\n";	 		
 echo "   </table>\n";
 echo "</td>\n";

?>