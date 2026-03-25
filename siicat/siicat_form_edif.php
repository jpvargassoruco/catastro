<?php

if ($error2) {
   $codigo_fijo = false;
}		
		
################################################################################
#--------------- FORMULARIO PARA AÑADIR/MODIFICAR EDIFICACIONES ---------------#
################################################################################	
   # Fila 1
   echo "<td>\n";
   echo "  <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";	 	 
	 echo "      <tr height=\"40px\">\n";  
	 echo "         <td width=\"15%\"> &nbsp</td>\n";   #Col. 1 			
   echo "         <td align=\"center\" valign=\"center\" width=\"60%\" class=\"pageName\">\n";  #Col.2
	 echo "            $accion Edificaciones\n";                          
   echo "         </td>\n";
	 echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 	 
   echo "      </tr>\n";	
   # Fila 2  	 
	 echo "			 <form name=\"form1\" method=\"post\" action=\"index.php?mod=29&id=$session_id\" accept-charset=\"utf-8\">\n";	
	 echo "      <tr>\n"; 	 
	 echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Códificación del Inmueble</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  13 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"13\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"17%\" class=\"bodyTextH\">Unidad Vecinal (U.V.)</td>\n";   #Col. 2	
	 if ($codigo_fijo) {    	  	 
	     echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\">&nbsp $cod_uv</td>\n";   #Col. 3	
	 } else {
	     echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\">\n";   #Col. 3
       echo "                     <input type=\"text\" name=\"cod_uv\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_uv\" value=\"$cod_uv\"></td>\n";			 	
	     echo "                  </td>\n";   #Col. 3				 	 
	 } 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 4		   
	 echo "                  <td align=\"center\" width=\"16%\" class=\"bodyTextH\">No. de Manzano</td>\n";   #Col. 5	
	 if ($codigo_fijo) { 	   
	    echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\">&nbsp $cod_man</td>\n";   #Col. 6	
	 } else {
	     echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\">\n";   #Col. 6
       echo "                     <input type=\"text\" name=\"cod_man\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_man\" value=\"$cod_man\"></td>\n";			 	
	     echo "                  </td>\n";   #Col. 6				 	 
	 } 			
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 7	   	 
	 echo "                  <td align=\"center\" width=\"16%\" class=\"bodyTextH\">No. de Predio</td>\n";   #Col. 8 
	 if ($codigo_fijo) {  
	    echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\">&nbsp $cod_pred</td>\n";   #Col. 9
	 } else {
	     echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\">\n";   #Col. 9
       echo "                     <input type=\"text\" name=\"cod_pred\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_pred\" value=\"$cod_pred\"></td>\n";			 	
	     echo "                  </td>\n";   #Col. 9			 	 
	 } 			
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 10	
	 echo "                  <td align=\"center\" width=\"22%\">&nbsp </td>\n";   #Col. 11 	 
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
	 $x = $no_de_edificaciones;
	 $y = 0;
	 $z = 1;
	 while ($x > 0) {
	 ##################################################
	 #                 EDIFICACIONES                  #
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Unidad Constructiva Nº $z</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  14 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"14\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n"; 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
   echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextH\">Edificación</td>\n";   #Col. 2 
	 $texto = $edi_num[$y];
	 echo "                  <td align=\"center\" width=\"3%\" class=\"bodyTextD\"><input type=\"text\" name=\"edi_num[$y]\" id=\"form_anadir1\" class=\"navText\" maxlength=\"2\" value=\"$texto\"></td>\n";   #Col. 3 	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 4		  	                     	 
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">Tipo</td>\n";   #Col. 5	    	  	 
	 echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextD\">\n";   #Col. 6	  
	 $valores = get_abr('edi_tipo');	 
   echo "                     <select class=\"navText\" name=\"edi_tipo[$y]\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $edi_tipo[$y]) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";
   echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">Piso</td>\n";   #Col. 7 
	 echo "                  <td align=\"center\" width=\"3%\" class=\"bodyTextD\"><input type=\"text\" name=\"edi_piso[$y]\" id=\"form_anadir1\" class=\"navText\" maxlength=\"2\" value=\"$edi_piso[$y]\"></td>\n";   #Col. 8 	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 9		
	 echo "                  <td align=\"center\" width=\"20%\" class=\"bodyTextH\">Estado de Conservación</td>\n";   #Col. 10	    	  	 
	 echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextD\">\n";   #Col. 11	  
	 $valores = get_abr('edi_edo');	 
   echo "                     <select class=\"navText\" name=\"edi_edo[$y]\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $edi_edo[$y]) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n"; 
	 echo "                  <td align=\"center\" width=\"20%\" class=\"bodyTextH\">Año de Construcción</td>\n";   #Col. 12
	 echo "                  <td align=\"center\" width=\"5%\" class=\"bodyTextD\"><input type=\"text\" name=\"edi_ano[$y]\" id=\"form_anadir1\" class=\"navText\" maxlength=\"4\" value=\"$edi_ano[$y]\"></td>\n";   #Col. 13  		 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 14 	 	 	   	 	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n";
	 ##################################################	 
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  10 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"10\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	
	 #TABLA FILA 1	 
	 echo "               <tr>\n";
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1 	 	 	 	  	 	     
	 echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">Cimientos</td>\n";   #Col. 2	    	  	 
	 echo "                  <td align=\"center\" width=\"16%\" class=\"bodyTextD\">\n";   #Col. 3	  
	 $valores = get_abr('edi_cim');	 
   echo "                     <select class=\"navText\" name=\"edi_cim[$y]\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $edi_cim[$y]) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextH\">Estructura</td>\n";   #Col. 4	    	  	 
	 echo "                  <td align=\"center\" width=\"16%\" class=\"bodyTextD\">\n";   #Col. 5	  
	 $valores = get_abr('edi_est');	 
   echo "                     <select class=\"navText\" name=\"edi_est[$y]\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $edi_est[$y]) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextH\">Muros</td>\n";   #Col. 6	    	  	 
	 echo "                  <td align=\"center\" width=\"16%\" class=\"bodyTextD\">\n";   #Col. 7	  
	 $valores = get_abr('edi_mur');	 
   echo "                     <select class=\"navText\" name=\"edi_mur[$y]\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $edi_mur[$y]) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";
	 echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">Acab. Piso</td>\n";   #Col. 8	    	  	 
	 echo "                  <td align=\"center\" width=\"16%\" class=\"bodyTextD\">\n";   #Col. 9	  
	 $valores = get_abr('edi_acab');	 
   echo "                     <select class=\"navText\" name=\"edi_acab[$y]\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $edi_acab[$y]) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";	 		 		 	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 10  	 	 	   	 	 	    
	 echo "               </tr>\n";
	 ###### TABLA FILA 2 #######
	 echo "               <tr>\n";
	 echo "                  <td></td>\n";   #Col. 1 	 	 	 	  	 	     
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Revest. Int.</td>\n";   #Col. 2	    	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 3  
	 $valores = get_abr('edi_rvin');	 
   echo "                     <select class=\"navText\" name=\"edi_rvin[$y]\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $edi_rvin[$y]) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";	
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Revest. Ext.</td>\n";   #Col. 4	    	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 5	  
	 $valores = get_abr('edi_rvex');	 
   echo "                     <select class=\"navText\" name=\"edi_rvex[$y]\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $edi_rvex[$y]) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Revest. Baño</td>\n";   #Col. 6	    	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 7	  
	 $valores = get_abr('edi_rvba');	 
   echo "                     <select class=\"navText\" name=\"edi_rvba[$y]\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $edi_rvba[$y]) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Rev. Cocina</td>\n";   #Col. 8	    	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 9	  
	 $valores = get_abr('edi_rvco');	 
   echo "                     <select class=\"navText\" name=\"edi_rvco[$y]\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $edi_rvco[$y]) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";	 		 		 	 
	 echo "                  <td></td>\n";   #Col. 10 	 	 	   	 	 	    
	 echo "               </tr>\n";
	 ###### TABLA FILA 3 #######
	 echo "               <tr>\n";
	 echo "                  <td></td>\n";   #Col. 1 	 	 	 	  	 	     
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Cub. Estr.</td>\n";   #Col. 2	    	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 3  
	 $valores = get_abr('edi_cest');	 
   echo "                     <select class=\"navText\" name=\"edi_cest[$y]\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $edi_cest[$y]) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";	
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Cub. Techo</td>\n";   #Col. 4	    	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 5	  
	 $valores = get_abr('edi_ctec');	 
   echo "                     <select class=\"navText\" name=\"edi_ctec[$y]\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $edi_ctec[$y]) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Cielo Raso</td>\n";   #Col. 6	    	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 7	  
	 $valores = get_abr('edi_ciel');	 
   echo "                     <select class=\"navText\" name=\"edi_ciel[$y]\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $edi_ciel[$y]) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Cocina</td>\n";   #Col. 8	    	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 9	  
	 $valores = get_abr('edi_coc');	 
   echo "                     <select class=\"navText\" name=\"edi_coc[$y]\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $edi_coc[$y]) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";	 		 		 	 
	 echo "                  <td></td>\n";   #Col. 10 	 	 	   	 	 	    
	 echo "               </tr>\n";	
	 ###### TABLA FILA 4 #######
	 echo "               <tr>\n";
	 echo "                  <td></td>\n";   #Col. 1 	 	 	 	  	 	     
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Baño</td>\n";   #Col. 2	    	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 3  
	 $valores = get_abr('edi_ban');	 
   echo "                     <select class=\"navText\" name=\"edi_ban[$y]\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $edi_ban[$y]) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";	
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Carpintería</td>\n";   #Col. 4	    	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 5	  
	 $valores = get_abr('edi_carp');	 
   echo "                     <select class=\"navText\" name=\"edi_carp[$y]\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $edi_carp[$y]) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Eléctrica</td>\n";   #Col. 6	    	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 7	  
	 $valores = get_abr('edi_elec');	 
   echo "                     <select class=\"navText\" name=\"edi_elec[$y]\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $edi_elec[$y]) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";
	 echo "                  <td align=\"center\" class=\"bodyTextD\"> &nbsp</td>\n";   #Col. 8	 
	 if ($no_de_edificaciones > 0) {   	  	 
	    echo "                  <td align=\"right\" valign=\"bottom\" colspan=\"2\">\n";   #Col. 9+10	  
	    echo "                     <input name=\"edif\" type=\"submit\" class=\"smallText\" value=\"Borrar Unidad $z\" />\n"; 		 
	    echo "                  </td>\n"; 
	 } else {
	    echo "                  <td align=\"center\" valign=\"bottom\" colspan=\"2\"> &nbsp </td>\n";   #Col. 9+10	  
   } 		 		 	 	 	   	 	 	    
	 echo "               </tr>\n";	  	 
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "      </tr>\n";
	 $x--;
	 $y++;
	 $z++;
	 } # END_OF_WHILE ($x > 0) 
	 echo "      <tr>\n"; 	 
	 echo "         <td align=\"left\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3 	
	 if ($codigo_fijo) {
      echo "         <input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";	 
#      echo "         <input type=\"hidden\" name=\"cod_uv\" value=\"$cod_uv\">\n";	  		 
#      echo "         <input type=\"hidden\" name=\"cod_man\" value=\"$cod_man\">\n";	  		 
#      echo "         <input type=\"hidden\" name=\"cod_lote\" value=\"$cod_lote\">\n";	
#			echo "         <input type=\"hidden\" name=\"cod_subl\" value=\"$cod_subl\">\n";	  		 
#      echo "         <input type=\"hidden\" name=\"cod_cat\" value=\"$cod_cat\">\n";
	 }	  	
   echo "         <input type=\"hidden\" name=\"no_de_edificaciones\" value=\"$no_de_edificaciones\">\n";	  	  
#   echo "         <input type=\"hidden\" name=\"submit\"  value=\"$accion un Inmueble\">\n";	 
	 echo "         <input type=\"hidden\" name=\"accion\" value=\"$accion\">\n";	 	  
	 echo "         <input name=\"edif\" type=\"submit\" class=\"smallText\" value=\"Más Unidades Constructivas\" />\n"; 		 
	 echo "         </td>\n";
	 echo "      </tr>\n";
	 if ($error1) {
      echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	    echo "         <font color=\"red\">$mensaje_de_error1</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "      </tr>\n";
	 }	 	  
	 echo "      <tr>\n"; 	 
	 echo "         <td align=\"center\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3 	 
   echo "            <input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";	 
	 echo "            <input name=\"edif\" type=\"submit\" class=\"smallText\" value=\"$accion Edificaciones\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";
#	 echo "            <input name=\"volver\" type=\"submit\" class=\"smallText\" value=\"Volver\">\n"; 		 
	 echo "         </td>\n";
	 echo "      </tr>\n";
	 echo "      </form>\n";	
   # Ultima Fila 
   echo "      <tr height=\"100%\"></tr>\n";			 
   echo "   </table>\n";
   echo "   <br />&nbsp;<br /><br />\n";
   echo "</td>\n";	  
?>