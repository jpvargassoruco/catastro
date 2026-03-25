<?php

	 # Fila 1
#   echo "<td>\n";
   echo "  <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";	 
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"15%\"> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n"; 
	 echo "            $accion Vehículo\n";                          
   echo "         </td>\n";
	 echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";	
   echo "			 <form name=\"form1\" method=\"post\" action=\"index.php?mod=102&id=$session_id\" accept-charset=\"utf-8\">\n";	
	 ##################################################
	 #----------- UBICACION DE LA ACTIVIDAD ----------#
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Ubicación de la Actividad</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  13 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"16\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">&nbsp Distrito &nbsp</td>\n";   #Col. 2	    	  	 
	 echo "                  <td align=\"center\" width=\"14%\" class=\"bodyTextD\">\n";   #Col. 3	  
   echo "                     <select class=\"navText\" name=\"distrito\" size=\"1\">\n";
   echo "                        <option id=\"form0\" value=\"\" selected=\"selected\"> Concepción</option>\n"; 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n"; 	                      
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 4	
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">&nbsp U.V. &nbsp</td>\n";   #Col. 5	    	  	 
	 echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_uv\" id=\"form_anadir2\" class=\"navText\" maxlength=\"2\" value=\"$cod_uv\"></td>\n";   #Col. 6 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 7	   
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">&nbsp No. Manzano &nbsp</td>\n";   #Col.8	  
	 echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_man\" id=\"form_anadir2\" class=\"navText\" maxlength=\"3\" value=\"$cod_man\"></td>\n";   #Col. 9	
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 10   	 
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">&nbsp No. Lote &nbsp</td>\n";   #Col. 11 
	 echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_lote\" id=\"form_anadir2\" class=\"navText\" maxlength=\"2\" value=\"$cod_lote\"></td>\n";   #Col. 12
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 13		   	 
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">&nbsp No. Sub-Lote &nbsp</td>\n";   #Col. 14 
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_subl\" id=\"form_anadir1\" class=\"navText\" maxlength=\"2\" value=\"$cod_subl\"></td>\n";	 #Col. 15  
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 16  	 	 	   	 	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "      </tr>\n"; 
	 if ($error1) {
      echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	    echo "         <font color=\"red\">$mensaje_de_error1</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "      </tr>\n";
	 }		 	 
	 ##################################################
	 #-------------- DATOS DE LA ACTIVIDAD -----------#
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Datos de la Actividad</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  9 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"9\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextH\">&nbsp Actividad &nbsp</td>\n";   #Col. 2	    	  	 
	 echo "                  <td align=\"center\" width=\"25%\" class=\"bodyTextD\">\n";   #Col. 3	  
	 $valores = get_abr('act_rub');	 
   echo "                     <select class=\"navText\" name=\"act_rub\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = get_rubro ($valores[$i]);		
      if ($valores[$i] == $act_rub) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";   
	 echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextH\">Razon Social</td>\n";   #Col. 4	 
	# $act_raz_texto = utf8_decode($act_raz);	  
	 echo "                  <td align=\"center\" width=\"35%\" class=\"bodyTextD\"><input type=\"text\" name=\"act_raz\" id=\"form_anadir1\" class=\"navText\" maxlength=\"36\" value=\"$act_raz\"></td>\n";   #Col. 5	
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 6	   	 
	 echo "                  <td align=\"center\" width=\"5%\" class=\"bodyTextH\">NIT</td>\n";   #Col. 7 
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextD\"><input type=\"text\" name=\"act_nit\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_ci\" value=\"$act_nit\"></td>\n";   #Col. 8  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 9	 	 	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  9 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"13\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">&nbsp No. de Patente &nbsp</td>\n";   #Col. 2	    	  	 
	 echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextD\">\n";   #Col. 3
	 if ($accion == "Modificar") {	  
	    echo "                     <input type=\"text\" name=\"act_pat\" id=\"form_anadir1\" class=\"navText_grey\" maxlength=\"8\" value=\"$act_pat\" disabled=\"disabled\">\n";
	 } else {
	 	  echo "                     <input type=\"text\" name=\"act_pat\" id=\"form_anadir1\" class=\"navText\" maxlength=\"8\" value=\"$act_pat\">\n";
 	 }
	 echo "                  </td>\n";   
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 4	 	 
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextH\">Telefono</td>\n";   #Col. 5	   
	 echo "                  <td align=\"center\" width=\"19%\" class=\"bodyTextD\"><input type=\"text\" name=\"act_tel\" id=\"form_anadir1\" class=\"navText\" maxlength=\"20\" value=\"$act_tel\"></td>\n";   #Col. 6	
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 7	   	 
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">Fecha Inicio</td>\n";   #Col. 8 
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextD\"><input type=\"text\" name=\"act_fech\" id=\"form_anadir1\" class=\"navText\" maxlength=\"10\" value=\"$act_fech\"></td>\n";   #Col. 9  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 10
	 echo "                  <td align=\"center\" width=\"14%\" class=\"bodyTextH\">Superficie (en m²)</td>\n";   #Col. 11 
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextD\"><input type=\"text\" name=\"act_sup\" id=\"form_anadir1\" class=\"navText\" maxlength=\"8\" value=\"$act_sup\"></td>\n";   #Col. 12  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 13	 	 	 	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n";	 
	 
	  
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "      </tr>\n"; 
	 if ($error2) {
      echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	    echo "         <font color=\"red\">$mensaje_de_error2</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "      </tr>\n";
	 }			  
	 ##################################################
	 #          IDENTIFICACION DEL TITULAR            #
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Identificación del Propietario</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  15 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"11\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1		 
	 echo "                  <td align=\"center\" width=\"25%\" class=\"bodyTextH\">Apellido Pat. o Razon Social</td>\n"; 	    	  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 3	  	  	 
	 echo "                  <td align=\"center\" width=\"25%\" class=\"bodyTextH\">Apellido Materno</td>\n";  
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 5	 	 
	 echo "                  <td align=\"center\" width=\"14%\" class=\"bodyTextH\">1er Nombre</td>\n"; 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 7	 
	 echo "                  <td align=\"center\" width=\"14%\" class=\"bodyTextH\">2ndo Nombre</td>\n"; 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 9	 
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">C.I.</td>\n";
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 11 	    	 	   	 	 	    
	 echo "               </tr>\n";
	 echo "               <tr>\n"; 
	 echo "                  <td></td>\n";   #Col. 1	
	 #$tit_1pat_texto = textconvert($tit_1pat);	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"act_1pat\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_apellido\" value=\"$act_1pat\"></td>\n";   	    	  	 
	 echo "                  <td></td>\n";   #Col. 3	
	 #$tit_1mat_texto = textconvert($tit_1mat);		   	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"act_1mat\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_apellido\" value=\"$act_1mat\"></td>\n";	  
	 echo "                  <td></td>\n";   #Col. 5
	 #$tit_1nom1_texto = textconvert($tit_1nom1);		 	 	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"act_1nom1\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_nombre\" value=\"$act_1nom1\"></td>\n";
	 echo "                  <td></td>\n";   #Col. 7	
	 #$tit_1nom2_texto = textconvert($tit_1nom2);		  
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"act_1nom2\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_nombre\" value=\"$act_1nom2\"></td>\n";
	 echo "                  <td></td>\n";   #Col. 9	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"act_1ci\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_ci\" value=\"$act_1ci\"></td>\n";
	 echo "                  <td></td>\n";   #Col. 11 	   	 	   	 	 	    
	 echo "               </tr>\n";	  	 
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "      </tr>\n";
	 if ($error3) {
      echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	    echo "         <font color=\"red\">$mensaje_de_error3</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "      </tr>\n";
	 }			  
	 ##################################################
	 #             DOMICILIO DEL TITULAR              #
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Domicilio del Propietario</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  9 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"9\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"5%\" class=\"bodyTextH\">Depto.</td>\n";   #Col. 2	    	  	 
	 echo "                  <td align=\"center\" width=\"14%\" class=\"bodyTextD\">\n";   #Col. 3	  
	 $valores = get_abr('act_dpto');	 
   echo "                     <select class=\"navText\" name=\"act_dpto\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $act_dpto) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";    
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">Ciudad</td>\n";   #Col. 4	
	 #$act_ciu_texto = textconvert($act_ciu);	   
	 echo "                  <td align=\"center\" width=\"24%\" class=\"bodyTextD\"><input type=\"text\" name=\"act_ciu\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_ciu\" value=\"$act_ciu\"></td>\n";   #Col. 5	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 6		  	 
	 echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextH\">Dirección</td>\n";   #Col. 7 
	 #$act_dir_texto = textconvert($act_dir);	 
	 echo "                  <td align=\"center\" width=\"42%\" class=\"bodyTextD\"><input type=\"text\" name=\"act_dir\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir\" value=\"$act_dir\"></td>\n";   #Col. 8  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 9	 	 	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "      </tr>\n";
	 if ($error4) {
      echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	    echo "         <font color=\"red\">$mensaje_de_error4</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "      </tr>\n";
	 }	
	 ##################################################
	 #----------------- OBSERVACIONES ----------------#
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Observaciones</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  9 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 6		  	 
	 echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">Observaciones</td>\n";   #Col. 7  
	 echo "                  <td align=\"center\" width=\"90%\" class=\"bodyTextD\"><input type=\"text\" name=\"act_obs\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_obs\" value=\"$act_obs\"></td>\n";   #Col. 8  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 9	 	 	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "      </tr>\n";
	 if ($error4) {
      echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	    echo "         <font color=\"red\">$mensaje_de_error4</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "      </tr>\n";
	 }			 		  	 
	 ################################################## 
	 echo "      <tr>\n"; 	 
	 echo "         <td align=\"center\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3 	
	 if ($accion == "Modificar") {
	    #echo "         <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";	
	    #echo "         <input name=\"cod_uv\" type=\"hidden\" value=\"$cod_uv\">\n";
	    #echo "         <input name=\"cod_man\" type=\"hidden\" value=\"$cod_man\">\n";
	    #echo "         <input name=\"cod_pred\" type=\"hidden\" value=\"$cod_pred\">\n";									
	    #echo "         <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";
     # echo "         <input name=\"registrar_cambios\" type=\"checkbox\" $reg_checked> Registrar Cambios \n";
      #echo "         (Active esta casilla cuando la modificación es importante para el historial del lote) \n";		
	    echo "         <input name=\"act_pat\" type=\"hidden\" value=\"$act_pat\">\n";				
	    echo "         <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Modificar\">\n";											
	 } else {
	    echo "         <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Registrar\">\n";
	 }	 		 
	 echo "         </td>\n";
	 echo "      </tr>\n";
	 echo "      </form>\n";	
   # Ultima Fila 
   echo "      <tr height=\"100%\"></tr>\n";			 
   echo "   </table>\n";
#   echo "   <br />&nbsp;<br />\n";
#   echo "</td>\n";	  
?>