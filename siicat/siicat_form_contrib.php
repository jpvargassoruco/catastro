<?php

	 # Fila 1
#   echo "<td>\n";
   echo "  <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";	 
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"5%\"> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"90%\" class=\"pageName\">\n"; 
	 echo "            $accion Contribuyente\n";                          
   echo "         </td>\n";
	 echo "         <td width=\"5%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";	
   echo "			 <form name=\"form1\" method=\"post\" action=\"index.php?mod=122&id=$session_id\" accept-charset=\"utf-8\">\n";	
	 ##################################################
	 #------------------- P.M.C. ---------------------#
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td> &nbsp</td>\n";   #Col. 1 		 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Padron Municipal</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  9 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"9\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"24%\" class=\"bodyTextH\">P.M.C.</td>\n";   #Col. 2	
	 #$act_ciu_texto = textconvert($act_ciu);	   
	 echo "                  <td align=\"center\" width=\"25%\" class=\"bodyTextD\"><input type=\"text\" name=\"con_pmc\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_pmc\" value=\"$con_pmc\"></td>\n";   #Col. 5	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 3		  	 
	 echo "                  <td align=\"center\" width=\"24%\" class=\"bodyTextH\">Padron Antiguo:</td>\n";   #Col. 4
	 #$act_dir_texto = textconvert($act_dir);	 
	 echo "                  <td align=\"center\" width=\"24%\" class=\"bodyTextD\"><input type=\"text\" name=\"pmc_ant\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_pmc\" value=\"$pmc_ant\"></td>\n";   #Col. 8  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 5 	 	 	    
	 echo "               </tr>\n";
	 echo "               <tr>\n";
	 echo "                  <td align=\"center\" colspan=\"9\" class=\"bodyText\">\n";
	 echo "                  Si deja en blanco el campo del P.M.C. el sistema asignará un número automáticamente!\n";	 
	 echo "                  </td>\n";   #Col. 1	 
	 echo "               </tr>\n";	 	 
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "         <td> &nbsp</td>\n";   #Col. 3
	 echo "      </tr>\n";
	 if ($error1) {
      echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	    echo "         <font color=\"red\">$mensaje_de_error1</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "      </tr>\n";
	 }	
	 ##################################################
	 #------------ NOMBRE DEL CONTRIBUYENTE ----------#
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td> &nbsp</td>\n";   #Col. 1 		 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2 
	 echo "         <fieldset><legend>Nombre del Contribuyente</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  9 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"9\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"18%\" class=\"bodyTextH\">Tipo de Contribuyente</td>\n";   #Col. 2	    	  	 
	 echo "                  <td align=\"center\" width=\"20%\" class=\"bodyTextD\">\n";   #Col. 3	  
	 $valores = get_abr('con_tipo'); 
   echo "                     <select class=\"navText\" name=\"con_tipo\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) { 
	    $texto = abr($valores[$i]);	
      if ($valores[$i] == $con_tipo) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";   
	 echo "                  <td align=\"center\" width=\"13%\" class=\"bodyTextH\">Razon Social</td>\n";   #Col. 4	 
	# $act_raz_texto = utf8_decode($act_raz);	  
	 echo "                  <td align=\"center\" width=\"26%\" class=\"bodyTextD\"><input type=\"text\" name=\"con_raz\" id=\"form_anadir1\" class=\"navText\" maxlength=\"30\" value=\"$con_raz\"></td>\n";   #Col. 5	
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 6	   	 
	 echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">NIT</td>\n";   #Col. 7  
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextD\"><input type=\"text\" name=\"con_nit\" id=\"form_anadir1\" class=\"navText\" maxlength=\"30\" value=\"$con_nit\"></td>\n";   #Col. 5		  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 9	 	 	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n"; 	
	 echo "            <table border=\"0\" width=\"100%\">\n";   #TABLE  15 Columnas   
	 echo "               <tr>\n";
	 echo "                  <td></td>\n";   #Col. 1	 
	 echo "                  <td align=\"left\" colspan=\"8\" class=\"bodyText\">\n";
	 echo "                     Ingresar Apellido y Nombre del Contribuyente (si es empresa, ingresar el nombre del representante):\n";	 
	 echo "                  </td>\n";   #Col. 1	 
	 echo "               </tr>\n";		  
	# echo "               <tr>\n";
	# echo "                  <td align=\"right\" colspan=\"9\" class=\"bodyText\"></td>\n";   #Col. 1	 
	# echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"2%\"></td>\n";   #Col. 1		 
	 echo "                  <td align=\"center\" width=\"27%\" class=\"bodyTextH\">Apellido Paterno</td>\n"; 	    	  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 3	  	  	 
	 echo "                  <td align=\"center\" width=\"26%\" class=\"bodyTextH\">Apellido Materno</td>\n";  
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 5	 	 
	 echo "                  <td align=\"center\" width=\"18%\" class=\"bodyTextH\">1er Nombre</td>\n"; 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 7	 
	 echo "                  <td align=\"center\" width=\"18%\" class=\"bodyTextH\">2ndo Nombre</td>\n"; 
	 echo "                  <td width=\"2%\"></td>\n";   #Col. 9	    	 	   	 	 	    
	 echo "               </tr>\n";
	 echo "               <tr>\n"; 
	 echo "                  <td></td>\n";   #Col. 1	
	 #$tit_1pat_texto = textconvert($tit_1pat);	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"con_pat\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_apellido\" value=\"$con_pat\"></td>\n";   	    	  	 
	 echo "                  <td></td>\n";   #Col. 3	
	 #$tit_1mat_texto = textconvert($tit_1mat);		   	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"con_mat\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_apellido\" value=\"$con_mat\"></td>\n";	  
	 echo "                  <td></td>\n";   #Col. 5
	 #$tit_1nom1_texto = textconvert($tit_1nom1);		 	 	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"con_nom1\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_nombre\" value=\"$con_nom1\"></td>\n";
	 echo "                  <td></td>\n";   #Col. 7	
	 #$tit_1nom2_texto = textconvert($tit_1nom2);		  
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"con_nom2\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_nombre\" value=\"$con_nom2\"></td>\n";
	 echo "                  <td></td>\n";   #Col. 9	   	 	   	 	 	    
	 echo "               </tr>\n";	  	 
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "         <td> &nbsp</td>\n";   #Col. 3		 
	 echo "      </tr>\n";
	 if ($error2) {
      echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	    echo "         <font color=\"red\">$mensaje_de_error2</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "      </tr>\n";
	 }		
	 ##################################################
	 #------- IDENTIFICACION DEL CONTRIBUYENTE -------#
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td> &nbsp</td>\n";   #Col. 1 		 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2 
	 echo "         <fieldset><legend>Identificación del Contribuyente</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  9 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"9\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"18%\" class=\"bodyTextH\">&nbsp Tipo de Identificación &nbsp</td>\n";   #Col. 2	    	  	 
	 echo "                  <td align=\"center\" width=\"22%\" class=\"bodyTextD\">\n";   #Col. 3	  
	 $doc_tipo = trim($doc_tipo);
	 $valores = get_abr('doc_tipo');	  
   echo "                     <select class=\"navText\" name=\"doc_tipo\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = abr($valores[$i]);	
      if ($valores[$i] == $doc_tipo) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";   
	 echo "                  <td align=\"center\" width=\"18%\" class=\"bodyTextH\">Nº de Identificación</td>\n";   #Col. 4	 
	# $act_raz_texto = utf8_decode($act_raz);	  
	 echo "                  <td align=\"center\" width=\"20%\" class=\"bodyTextD\"><input type=\"text\" name=\"doc_num\" id=\"form_anadir1\" class=\"navText\" maxlength=\"15\" value=\"$doc_num\"></td>\n";   #Col. 5	
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 6	   	 
	 echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextH\">Expedido en</td>\n";   #Col. 7 
	 echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextD\">\n";   #Col. 8  	 
	 $valores = get_abr('doc_exp');	 
   echo "                     <select class=\"navText\" name=\"doc_exp\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = abr($valores[$i]);		
      if ($valores[$i] == $doc_exp) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	 
	 echo "                  </td>\n";   	  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 9	 	 	 	    
	 echo "               </tr>\n";

	 	 
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		  
	 echo "      </tr>\n"; 
	 if ($error3) {
      echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	    echo "         <font color=\"red\">$mensaje_de_error3</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "      </tr>\n";
	 }		
	 ##################################################
	 #------------------- DIRECCION ------------------#
	 ##################################################
	 echo "      <tr>\n"; 	
	 echo "         <td> &nbsp</td>\n";   #Col. 1 		  
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2 
	 echo "         <fieldset><legend>Domicilio del Contribuyente</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  9 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"9\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"13%\" class=\"bodyTextH\">Departamento</td>\n";   #Col. 2	    	  	 
	 echo "                  <td align=\"center\" width=\"17%\" class=\"bodyTextD\">\n";   #Col. 3	  
	 $dom_dpto = trim ($dom_dpto);
	 $valores = get_abr('dom_dpto');	
   echo "                     <select class=\"navText\" name=\"dom_dpto\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));
	    if ($valores[$i] == $dom_dpto) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";    
	 echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">Ciudad</td>\n";   #Col. 4	
	 #$act_ciu_texto = textconvert($act_ciu);	   
	 echo "                  <td align=\"center\" width=\"24%\" class=\"bodyTextD\"><input type=\"text\" name=\"dom_ciu\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_ciu\" value=\"$dom_ciu\"></td>\n";   #Col. 5	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 6		  	 
	 echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextH\">Barrio</td>\n";   #Col. 7 
	 #$act_dir_texto = textconvert($act_dir);	 
	 echo "                  <td align=\"center\" width=\"29%\" class=\"bodyTextD\"><input type=\"text\" name=\"dom_bar\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_bar\" value=\"$dom_bar\"></td>\n";   #Col. 8  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 9	 	 	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n"; 	 
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  21 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"21\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"4%\" class=\"bodyTextH\">Tipo</td>\n";   #Col. 2	    	  	 
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextD\">\n";   #Col. 3	  
	 $valores = get_abr('dir_tipo');	 
   echo "                     <select class=\"navText\" name=\"dir_tipo\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $dom_tipo) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";   
	 echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextH\">Nombre</td>\n";   #Col. 4	
	 $dir_nom_texto = textconvert($dom_nom); 
	 echo "                  <td align=\"center\" width=\"24%\" class=\"bodyTextD\"><input type=\"text\" name=\"dir_nom\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_nom\" value=\"$dir_nom_texto\"></td>\n";   #Col. 5	  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 6	
	 echo "                  <td align=\"center\" width=\"3%\" class=\"bodyTextH\">Nº</td>\n";   #Col. 7 
	 echo "                  <td align=\"center\" width=\"5%\" class=\"bodyTextD\"><input type=\"text\" name=\"dir_num\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_num\" value=\"$dom_num\"></td>\n";   #Col. 8  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 9	
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">Edificio</td>\n";   #Col. 10 
	 $dir_edif_texto = textconvert($dom_edif);
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextD\"><input type=\"text\" name=\"dir_edif\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_edif\" value=\"$dom_edif_texto\"></td>\n";	 #Col. 11  
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 12	
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">Bloque</td>\n";   #Col. 13
	 echo "                  <td align=\"center\" width=\"3%\" class=\"bodyTextD\"><input type=\"text\" name=\"dir_bloq\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_bloq\" value=\"$dom_bloq\"></td>\n";   #Col. 14  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 15
	 echo "                  <td align=\"center\" width=\"4%\" class=\"bodyTextH\">Piso</td>\n";   #Col. 16 
	 echo "                  <td align=\"center\" width=\"3%\" class=\"bodyTextD\"><input type=\"text\" name=\"dir_piso\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_piso\" value=\"$dom_piso\"></td>\n";	 #Col. 17 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 18		  
	 echo "                  <td align=\"center\" width=\"4%\" class=\"bodyTextH\">Apto.</td>\n";   #Col. 19 
	 echo "                  <td align=\"center\" width=\"3%\" class=\"bodyTextD\"><input type=\"text\" name=\"dir_apto\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_apto\" value=\"$dom_apto\"></td>\n";	 #Col. 20  
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 21  	 	 	   	 	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		  
	 echo "      </tr>\n"; 
	 if ($error4) {
      echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	    echo "         <font color=\"red\">$mensaje_de_error4</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "      </tr>\n";
	 }	
	 ##################################################
	 #--------------- DATOS ADICIONALES --------------#
	 ##################################################
	 echo "      <tr>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1 			  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col.2 
	 echo "         <fieldset><legend>Datos Adicionales</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  10 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"10\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	 
	 echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextH\">Telefono(s)</td>\n";   #Col. 2	   
	 echo "                  <td align=\"center\" width=\"22%\" class=\"bodyTextD\"><input type=\"text\" name=\"con_tel\" id=\"form_anadir1\" class=\"navText\" maxlength=\"20\" value=\"$con_tel\"></td>\n";   #Col. 3	
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 4	   	 
	 echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">Nº Medidor Agua</td>\n";   #Col. 5 
	 echo "                  <td align=\"center\" width=\"17%\" class=\"bodyTextD\"><input type=\"text\" name=\"med_agu\" id=\"form_anadir1\" class=\"navText\" maxlength=\"10\" value=\"$med_agu\"></td>\n";   #Col. 6  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 7
	 echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">Nº Medidor Luz</td>\n";   #Col. 8 
	 echo "                  <td align=\"center\" width=\"17%\" class=\"bodyTextD\"><input type=\"text\" name=\"med_luz\" id=\"form_anadir1\" class=\"navText\" maxlength=\"10\" value=\"$med_luz\"></td>\n";   #Col. 9  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 10	 	 	 	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n";	 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "         <td> &nbsp</td>\n";   #Col. 3
	 echo "      </tr>\n"; 
	 ##################################################
	 #----------------- OBSERVACIONES ----------------#
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td> &nbsp</td>\n";   #Col. 1 	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2  
	 echo "         <fieldset><legend>Observaciones</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  9 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"4\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1		  	 
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">Observaciones</td>\n";   #Col. 2  
	 echo "                  <td align=\"center\" width=\"86%\" class=\"bodyTextD\"><input type=\"text\" name=\"con_obs\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_obs\" value=\"$con_obs\"></td>\n";   #Col. 3  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 4	 	 	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3
	 echo "      </tr>\n";	
	 if ($error5) {
      echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	    echo "         <font color=\"red\">$mensaje_de_error5</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "      </tr>\n";
	 }		  		  	 
	 ################################################## 
	 echo "      <tr>\n"; 	 
	 echo "         <td align=\"center\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3 	
	 if ($accion == "Modificar") {
	    echo "         <input name=\"id_contrib\" type=\"hidden\" value=\"$id_contrib\">\n";				
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