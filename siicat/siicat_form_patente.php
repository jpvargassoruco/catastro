<?php
include "siicat_lista_patentes.php";
include "siicat_lista_contribuyentes.php";
	 #############################################################################
	 #------------------------------ FORMULARIO ---------------------------------#
	 #############################################################################	 
#   echo "<td>\n";
   echo "  <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";	 
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"15%\"> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n"; 
	 echo "            $accion Actividad Económica\n";                          
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
	 echo "                  <td align=\"right\" colspan=\"22\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	
	 echo "                  <td width=\"1%\">&nbsp</td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextH\">Distrito</td>\n";   #Col. 2	    	  	 
	 echo "                  <td align=\"center\" width=\"16%\" class=\"bodyTextD\">\n";   #Col. 3	  
   echo "                     <select class=\"navText\" name=\"distrito\" size=\"1\">\n";
   echo "                        <option id=\"form0\" value=\"\" selected=\"selected\"> Concepción</option>\n"; 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n"; 	                      
	 echo "                  <td width=\"1%\">&nbsp</td>\n";   #Col. 4	
	 echo "                  <td align=\"center\" width=\"5%\" class=\"bodyTextH\">U.V.</td>\n";   #Col. 5	    	  	 
	 echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_uv\" id=\"form_anadir2\" class=\"navText\" maxlength=\"2\" value=\"$cod_uv\"></td>\n";   #Col. 6 
	 echo "                  <td width=\"1%\">&nbsp</td>\n";   #Col. 7	   
	 echo "                  <td align=\"center\" width=\"5%\" class=\"bodyTextH\">Mz.</td>\n";   #Col.8	  
	 echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_man\" id=\"form_anadir2\" class=\"navText\" maxlength=\"3\" value=\"$cod_man\"></td>\n";   #Col. 9	
	 echo "                  <td width=\"1%\">&nbsp</td>\n";   #Col. 10   	 
	 echo "                  <td align=\"center\" width=\"5%\" class=\"bodyTextH\">Pred.</td>\n";   #Col. 11 
	 echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_pred\" id=\"form_anadir2\" class=\"navText\" maxlength=\"2\" value=\"$cod_pred\"></td>\n";   #Col. 12
	 echo "                  <td width=\"1%\">&nbsp</td>\n";   #Col. 13		   	 
	 echo "                  <td align=\"center\" width=\"5%\" class=\"bodyTextH\">Blq.</td>\n";   #Col. 14 
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_blq\" id=\"form_anadir1\" class=\"navText\" maxlength=\"2\" value=\"$cod_blq\"></td>\n";	 #Col. 15  
	 echo "                  <td width=\"1%\">&nbsp</td>\n";   #Col. 16  	
	 echo "                  <td align=\"center\" width=\"5%\" class=\"bodyTextH\">Piso</td>\n";   #Col. 17 
	 echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_piso\" id=\"form_anadir2\" class=\"navText\" maxlength=\"2\" value=\"$cod_piso\"></td>\n";   #Col. 12
	 echo "                  <td width=\"1%\">&nbsp</td>\n";   #Col. 19		   	 
	 echo "                  <td align=\"center\" width=\"5%\" class=\"bodyTextH\">Apto</td>\n";   #Col. 20 
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_apto\" id=\"form_anadir1\" class=\"navText\" maxlength=\"2\" value=\"$cod_apto\"></td>\n";	 #Col. 15  
	 echo "                  <td width=\"1%\">&nbsp</td>\n";   #Col. 22  		  	 	   	 	 	    
	 echo "               </tr>\n";
	 echo "               <tr>\n";
	 echo "                  <td align=\"center\" colspan=\"22\" class=\"bodyText\">\n";
	 echo "                     Si la actividad no se puede asignar a un predio, dejar las casillas en blanco !\n";		 
	 echo "                  </td>\n";	  
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
   echo "                     <select class=\"navText\" name=\"id_rubro\" size=\"1\">\n";
   if (!isset($_POST['id_rubro'])) {	 
	    echo "                        <option id=\"form0\" value=\"\" selected=\"selected\"> --- Seleccionar de la lista de Rubros ---</option>\n";    
	 }
	 $i = 0;
	 while ($i < $no_de_rubros) {
		  $value_temp = $id_rubro_lista[$i]; 	
			if ($value_temp == $id_rubro) {
			   echo "                   <option id=\"form0\" value=\"$value_temp\" selected=\"selected\"> $rubro[$i]</option>\n";
		  } else {
			   echo "                   <option id=\"form0\" value=\"$value_temp\"> $rubro[$i]</option>\n";
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
	 #--------- IDENTIFICACION DEL TITULAR -----------#
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Identificación del Propietario</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  15 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"2\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\"  width=\"45%\" class=\"bodyText\">\n";   #Col. 1	 	 
   echo "                     <select class=\"navText\" name=\"id_contrib\" size=\"1\">\n";
   if (!isset($_POST['id_contrib'])) {	 
	    echo "                        <option id=\"form0\" value=\"\" selected=\"selected\"> --- Seleccionar de la lista de Contribuyentes ---</option>\n";    
	 }
	 $i = 0;
	 while ($i < $no_de_contribuyentes) {
     # if ($valores[$i] == $act_rub) {
	   #   echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	   # } else {
		  $value_temp = $id_contrib_lista[$i]; 	
			if ($value_temp == $id_contrib) {
			   echo "                   <option id=\"form0\" value=\"$value_temp\" selected=\"selected\"> $contribuyente[$i]</option>\n";
		  } else {
			   echo "                   <option id=\"form0\" value=\"$value_temp\"> $contribuyente[$i]</option>\n";
	    }
	    $i++;
   } 	
   echo "                     </select>\n";	 
	 echo "                  </td>\n";
	 echo "                  <td align=\"left\" width=\"55%\"> &nbsp&nbsp Si el propietario no se encuentra en la lista de contribuyentes registrarlo <a href=\"index.php?mod=122&id=$session_id\">aki</a> !</td>\n"; 		     	   	 	   	 	 	    
	 echo "               </tr>\n";	 
/*	 
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
	 echo "               </tr>\n";	  	 */
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