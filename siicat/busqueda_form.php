<?php
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################		
	echo "<td>\n";
	echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
	# Fila 1

	echo "      <tr height=\"40px\">\n";
	echo "         <td width=\"10%\"> &nbsp</td>\n";   #Col. 1 	    
	echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"70%\" class=\"pageName\">\n"; 
	if ($mod == 1) {
	    echo "Buscar Predios\n"; 
	 } elseif ($mod == 41) {
	    echo "Buscar Propiedad Rural\n"; 				
	 } elseif ($mod == 101) {
	    echo "Buscar Patente\n"; 	 
	 } elseif ($mod == 111) {
	    echo "Buscar Vehículo\n"; 	 
	 } elseif ($mod == 121) {
	    echo "Buscar Contribuyente\n"; 	 
	 }                          
	echo "         </td>\n";
	echo "         <td width=\"20%\"> &nbsp</td>\n";   #Col. 3 			 
	echo "      </tr>\n";	 
	echo "      <tr>\n";
	echo "         <td> &nbsp</td>\n";   #Col. 1  
	echo "         <td align=\"left\"> &nbsp \n"; #Col. 2 
	echo "         </td>\n";	
	echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	echo "      </tr>\n";	 
	# Fila 2
	echo "      <tr>\n";  
	echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                      
	echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	  
	echo "<fieldset><legend>Ingrese el atributo que quiere buscar</legend>\n";
   if ($mod == 41) {
      echo "	       <form name=\"isc\" method=\"post\" action=\"index.php?mod=43&id=$session_id\" accept-charset=\"utf-8\">\n"; 
      echo "            <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";  # TABLE 6 Columns
 	    echo "               <tr>\n";
 	    echo "                  <td> &nbsp</td>\n"; #TCol. 1
	    echo "                  <td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Pol�gono</td>\n";						
	    echo "                  <td align=\"left\" colspan=\"3\" class=\"bodyTextD\">Parcela</td>\n";             
	    echo "                </tr>\n";  	 
 	    echo "               <tr>\n";
 	    echo "                  <td width=\"10%\"> &nbsp</td>\n"; #TCol. 1
	    echo "                  <td align=\"left\" width=\"10%\" class=\"bodyTextD\">\n"; #TCol. 2
	    echo "                     <input name=\"cod_pol\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_pol\" value=\"$cod_pol\">\n";
	    echo "                  </td>\n";
 	    echo "                  <td width=\"2%\"> &nbsp</td>\n"; #TCol. 3			
	    echo "                  <td align=\"left\" width=\"10%\" class=\"bodyTextD\">\n"; #TCol. 4
	    echo "                     <input name=\"cod_par\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_par\" value=\"$cod_par\">\n";
	    echo "                  </td>\n";		
 	    echo "                  <td width=\"2%\"> &nbsp</td>\n"; #TCol. 5					
 	    echo "                  <td width=\"66%\">\n";  #TCol. 6				
	    echo "                     <input name=\"busqueda1\" type=\"submit\" class=\"smallText\" value=\"Buscar\" onClick=\"go()\">\n";			
	    echo "                  </td>\n";		
	    echo "               </tr>\n";  
	    echo "            </table>\n";		
	    echo "         </form>\n";	 
   } elseif ($mod == 101) {
      echo "	       <form name=\"isc\" method=\"post\" action=\"index.php?mod=103&id=$session_id\" accept-charset=\"utf-8\">\n"; 
      echo "            <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";  # TABLE 6 Columns
 	    echo "               <tr>\n";
 	    echo "                  <td> &nbsp</td>\n"; #TCol. 1
	    echo "                  <td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Número de Patente\n";
	    echo "                  </td>\n";		
	    echo "                </tr>\n";  	 
 	    echo "               <tr>\n";
 	    echo "                  <td width=\"10%\"> &nbsp</td>\n"; #TCol. 1
	    echo "                  <td align=\"left\" width=\"30%\" class=\"bodyTextD\">\n";
	    echo "                     <input name=\"act_pat\" type=\"text\" class=\"navText\" value=\"$act_pat\">\n";
	    echo "                  </td>\n";
 	    echo "                  <td width=\"60%\">\n";
	    echo "                     <input name=\"busqueda\" type=\"submit\" class=\"smallText\" value=\"Buscar\" onClick=\"go()\">\n";			
	    echo "                  </td>\n"; #TCol. 1			
	    echo "               </tr>\n";  
	    echo "            </table>\n"; #TCol. 1			
	    echo "         </form>\n";	 
	 } elseif ($mod == 111) {
      echo "	       <form name=\"isc\" method=\"post\" action=\"index.php?mod=113&id=$session_id\" accept-charset=\"utf-8\">\n"; 
      echo "            <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";  # TABLE 6 Columns
 	    echo "               <tr>\n";
 	    echo "                  <td> &nbsp</td>\n"; #TCol. 1
	    echo "                  <td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Número de Placa\n";
	    echo "                  </td>\n";		
	    echo "                </tr>\n";  	 
 	    echo "               <tr>\n";
 	    echo "                  <td width=\"10%\"> &nbsp</td>\n"; #TCol. 1
	    echo "                  <td align=\"left\" width=\"30%\" class=\"bodyTextD\">\n";
	    echo "                     <input name=\"veh_plc\" type=\"text\" class=\"navText\" value=\"$veh_plc\">\n";
	    echo "                  </td>\n";
 	    echo "                  <td width=\"60%\">\n";
	    echo "                     <input name=\"busqueda\" type=\"submit\" class=\"smallText\" value=\"Buscar\" onClick=\"go()\">\n";			
	    echo "                  </td>\n"; #TCol. 1			
	    echo "               </tr>\n";  
	    echo "            </table>\n"; #TCol. 1			
	    echo "         </form>\n";
	 } elseif ($mod == 121) {
      echo "	       <form name=\"isc\" method=\"post\" action=\"index.php?mod=123&id=$session_id\" accept-charset=\"utf-8\">\n"; 
      echo "            <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";  # TABLE 6 Columns
 	    echo "               <tr>\n";
 	    echo "                  <td> &nbsp</td>\n"; #TCol. 1
	    echo "                  <td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Padrón Municipal (PMC)\n";
	    echo "                  </td>\n";		
	    echo "                </tr>\n";  	 
 	    echo "               <tr>\n";
 	    echo "                  <td width=\"10%\"> &nbsp</td>\n"; #TCol. 1
	    echo "                  <td align=\"left\" width=\"25%\" class=\"bodyTextD\">\n";
	    echo "                     <input name=\"con_pmc\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_pmc\" value=\"$con_pmc\">\n";
	    echo "                  </td>\n";
 	    echo "                  <td width=\"65%\">\n";
	    echo "                     <input name=\"busqueda\" type=\"submit\" class=\"smallText\" value=\"Buscar\" onClick=\"go()\">\n";			
	    echo "                  </td>\n"; #TCol. 1			
	    echo "               </tr>\n";  
	    echo "            </table>\n"; #TCol. 1			
	    echo "         </form>\n";					 
	 } else {
		echo "	       <form name=\"isc\" method=\"post\" action=\"index.php?mod=4&id=$session_id\" accept-charset=\"utf-8\">\n"; 
		echo "            <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";  # TABLE 8 Columns
		echo "               <tr>\n";
		echo "                  <td> &nbsp</td>\n"; #TCol. 1
		echo "                  <td align=\"left\" colspan=\"2\" class=\"bodyTextD\">$uv_dist</td>\n";
		echo "                  <td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Mz.</td>\n";
		echo "                  <td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Pred.</td>\n";
		echo "                  <td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Blq.</td>\n";
		echo "                  <td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Piso</td>\n";
		echo "                  <td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Apto.</td>\n";											
		echo "                  <td align=\"left\" colspan=\"1\" class=\"bodyTextD\"> &nbsp</td>\n";            
		echo "                </tr>\n";  	 
		echo "               <tr>\n";
		echo "                  <td width=\"10%\"> &nbsp</td>\n"; #TCol. 1
		echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\">\n";
		echo "                     <input name=\"cod_uv\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_uv\" value=\"$cod_uv\">\n";
		echo "                  </td>\n";
		echo "                  <td width=\"2%\"> &nbsp</td>\n"; #TCol. 3		
		echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\">\n";
		echo "                     <input name=\"cod_man\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_man\" value=\"$cod_man\">\n";
		echo "                  </td>\n";	
		echo "                  <td width=\"2%\"> &nbsp</td>\n"; #TCol. 5						
		echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\">\n";
		echo "                     <input name=\"cod_pred\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_pred\" value=\"$cod_pred\">\n";
		echo "                  </td>\n";
		echo "                  <td width=\"2%\"> &nbsp</td>\n"; #TCol. 7			
		echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\">\n";
		echo "                     <input name=\"cod_blq\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_blq\" value=\"$cod_blq\">\n";
		echo "                  </td>\n";	
		echo "                  <td width=\"2%\"> &nbsp</td>\n"; #TCol. 7			
		echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\">\n";
		echo "                     <input name=\"cod_piso\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_piso\" value=\"$cod_piso\">\n";
		echo "                  </td>\n";	
		echo "                  <td width=\"2%\"> &nbsp</td>\n"; #TCol. 7			
		echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\">\n";
		echo "                     <input name=\"cod_apto\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_apto\" value=\"$cod_apto\">\n";
		echo "                  </td>\n";					
		echo "                  <td width=\"2%\"> &nbsp</td>\n"; #TCol. 7					
		echo "                  <td width=\"40%\">\n";
		echo "                     <input name=\"old_example\" type=\"hidden\" class=\"smallText\" value=\"$example\">\n";
		echo "                     <input name=\"old_stage2\" type=\"hidden\" class=\"smallText\" value=\"$stage2\">\n";	 				
		echo "                     <input name=\"busqueda1\" type=\"submit\" class=\"smallText\" value=\"Buscar\" onClick=\"go()\">\n";			
		echo "                  </td>\n"; #TCol. 1			
		echo "               </tr>\n";  
		echo "            </table>\n"; #TCol. 1			
		echo "         </form>\n";		 
	 }	 
   echo "	        <form name=\"isc\" method=\"post\" action=\"index.php?mod=$mod&id=$session_id\" accept-charset=\"utf-8\">\n";
   echo "            <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";  # TABLE 6 Columns	 
 	 echo "               <tr>\n";
 	 echo "                  <td width=\"10%\"> &nbsp</td>\n"; #TCol. 1
	 if ($mod == 1) {	 
	    echo "<td align=\"left\" width=\"60%\" class=\"bodyTextD\">Nombre, Apellido, No. de Carnet, PMC y/o Dirección\n";
	 } elseif ($mod == 41) {
	    echo "<td align=\"left\" width=\"60%\" class=\"bodyTextD\">Nombre de la Propiedad o del Propietario\n";	
	 } elseif ($mod == 101) {
	    echo "<td align=\"left\" width=\"60%\" class=\"bodyTextD\">Razón Social, Propietario o NIT\n";
	 } elseif ($mod == 111) {
	    echo "<td align=\"left\" width=\"60%\" class=\"bodyTextD\">Nombre de Propietario o No. de Carnet \n";
	 } elseif ($mod == 121) {
	    echo "<td align=\"left\" width=\"60%\" class=\"bodyTextD\">Nombre del Contribuyente o No. de Carnet \n";
	 }
	 echo "                     <input name=\"search_string\" type=\"text\" class=\"navText\" value=\"$search_string\">\n";
	 echo "                  </td>\n";
 	 echo "                  <td width=\"30%\" valign=\"bottom\">\n"; 	 	 	 
	 echo "                     <input name=\"busqueda2\" type=\"submit\" class=\"smallText\" value=\"Buscar\" onClick=\"go()\">\n";	
	 echo "                  </td>\n";
	 echo "               </tr>\n";  	 
	 echo "            </table>\n"; 
	 echo "         </form>\n";
	 echo "		      <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=$mod&id=$session_id\" accept-charset=\"utf-8\">\n";
   echo "            <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";  # TABLE 3 Columns	 	 
   echo "               <tr height=\"35\">\n";
   echo "                  <td>&nbsp</td>\n";			
   echo "                  <td align=\"center\">\n";
   echo "                     <input name=\"busqueda3\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Listado Completo\" />\n";	
   echo "                  </td>\n";
   echo "                  <td>&nbsp</td>\n";								
   echo "               </tr>\n";				
	 echo "            </table>\n"; 
   echo "         </form>\n";			 	 	 
	 echo "         </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 3   		
	 echo "      </tr>\n";	
 	  
	 if ($error) {
 	    # Fila 2a
			echo "      <tr>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 1		  
	    echo "         <td align=\"center\" height=\"40\" class=\"error\">$mensaje_de_error</font>\n";   #Col. 2
	    echo "         <td> &nbsp</td>\n";   #Col. 3
	    echo "      </tr>\n";			 
	} elseif ($buscar AND $resultado) {	
		# Fila 2a
		echo "      <tr>\n";
		echo "         <td> &nbsp</td>\n";   #Col. 1		  
		echo "         <td align=\"left\">Resultado de la búsqueda:</td>\n";   #Col. 2
		echo "         <td> &nbsp</td>\n";   #Col. 3
		echo "      </tr>\n";				            
		# Fila 2b		
		echo "		  <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=$pag&id=$session_id\" accept-charset=\"utf-8\">\n";	 
		echo "      <tr>\n";  	
		echo "         <td> &nbsp</td>\n";   #Col. 1                       
		echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	
		echo "            <table width=\"100%\" border=\"0\">\n";
		echo "               <tr>\n";
		echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextH\">\n";
		echo "                     <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";			
		echo "                     <input name=\"Submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Ver\" />\n";
		echo "                	</td>\n";
		if ($mod == 41) {							
	       echo "                  <td align=\"center\" width=\"20%\" class=\"bodyTextH\">$titulo1</td>\n";
	       echo "                  <td align=\"center\" width=\"35%\" class=\"bodyTextH\">$titulo2</td>\n";
			} else {
	       echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">$titulo1</td>\n";
	       echo "                  <td align=\"center\" width=\"40%\" class=\"bodyTextH\">$titulo2</td>\n";
		  }						
	    echo "                  <td align=\"center\" width=\"38%\" class=\"bodyTextH\">$titulo3</td>\n";			
	    echo "               </tr>\n";
	    echo "            </table>\n"; 						
	    echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3   		
	    echo "      </tr>\n";
	    echo "      <tr>\n";  	
	    echo "         <td> &nbsp</td>\n";   #Col. 1                       
	    echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	
      echo "            <div style=\"height:400px; overflow:auto\">\n";				
	    echo "            <table width=\"100%\" border=\"0\" id=\"registros2\">\n";							
			$i = $j = $k = 0;		
			$m = 25;
			$show_color = false;
      while ($j < $filas) {
			   if (!$show_color){
			      echo "               <tr>\n";
						$show_color = true;
				 } else {
 	          echo "      <tr class=\"alt\">\n";	
						$show_color = false;		 
				 }    		 
				 if ($j == 0) {	 
            echo "                  <td align=\"center\"><input name=\"$var_submit\" value=\"$valor_submit[$j]\" type=\"radio\" checked=\"checked\"></td>\n"; 
	       } else {
            echo "                  <td align=\"center\"><input name=\"$var_submit\" value=\"$valor_submit[$j]\" type=\"radio\"></td>\n"; 				 
				 }
				 echo "                  <td align=\"center\">$valor1[$j]</td>\n";
				 if ($mod == 121) {	
	          echo "                  <td align=\"center\">&nbsp $valor2[$j]</td>\n";
				 } else {
				    echo "                  <td align=\"center\">$valor2[$j]</td>\n";
				 }
	       echo "                  <td align=\"center\">$valor3[$j]</td>\n";				 				 
         echo "               </tr>\n";	
#				 echo "OUTPUT: $output[$j], CODIGO: $codigo[$j] <br />\n";				 
	       $j++;
				 $k++;
			   if (($k == 5525) AND ($filas - $m > 10)) {	
 	          echo "               <tr>\n";
	          echo "                  <td align=\"center\" class=\"bodyTextH\">\n";		
            echo "                     <input name=\"Submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Ver\" />\n";
			      echo "                	</td>\n";			
	          echo "                  <td align=\"center\" class=\"bodyTextH\">$titulo1</td>\n";
	          echo "                  <td align=\"center\" class=\"bodyTextH\">$titulo2</td>\n";
	          echo "                  <td align=\"center\" class=\"bodyTextH\">$titulo3</td>\n";			
	          echo "               </tr>\n";
						$m = $m + $k;
						$k = 0;		
				 }
      } # END_OF_WHILE			
      pg_free_result($result);		
			echo "               <tr>\n";
	    echo "                  <td width=\"7%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";	
			if ($mod == 41) {		
	       echo "                  <td width=\"20%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";			
	       echo "                  <td width=\"35%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";
			} else {
	       echo "                  <td width=\"15%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";			
	       echo "                  <td width=\"40%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";
			}
	    echo "                  <td width=\"38%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";			
      echo "               </tr>\n";			
	    echo "            </table>\n"; 
	    echo "            </div>\n";									
	    echo "         </td>\n";
	    echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 3   		
	    echo "      </tr>\n";	
	    echo "      <tr>\n";  	
	    echo "         <td> &nbsp</td>\n";   #Col. 1                       
	    echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	
	    echo "            <table width=\"100%\" border=\"0\">\n";
 	    echo "               <tr>\n";
	    echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextH\">\n";
	    echo "                     <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";			
      echo "                     <input name=\"Submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Ver\" />\n";
			echo "                	</td>\n";			
	    echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">$titulo1</td>\n";
	    echo "                  <td align=\"center\" width=\"40%\" class=\"bodyTextH\">$titulo2</td>\n";
	    echo "                  <td align=\"center\" width=\"38%\" class=\"bodyTextH\">$titulo3</td>\n";			
	    echo "               </tr>\n";
	    echo "            </table>\n"; 						
	    echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3   		
	    echo "      </tr>\n";							  
			echo "      </form>\n";	
			
	} elseif ($buscar AND !$resultado) {
		echo "<h3><font color=\"red\">Busqueda sin resultado...</font></h3>\n";	
		echo "<p>Código catastral no existe: $cod_cat,\n";
		echo "el padron municipal: $cod_pad, el nombre del\n";	
		echo "títular: $nombre1 o el \n";
		echo "apellido del titular: $apellido1 en la base de datos</p>\n";	         		 
	}

    ?>