<?php

$mostrar_casilla = true;
########################################
#----- GENERAR NOMBRE Y DIRECCION -----#
########################################	
$titular1 = get_prop1_from_id_inmu ($id_inmu);
$direccion = get_direccion_from_id_inmu ($id_inmu);
$direccion = utf8_decode($direccion);
########################################
#---------------- SUBMIT --------------#
########################################	
if (isset($_POST["submit"])) { 
   if ($_POST["submit"] == "AÃ±adir") {
	    $mostrar_casilla = false; 
      $texto_gravamen = ""; 
	 } 
}
########################################
#---------------- AÑADIR --------------#
########################################	
if ((isset($_POST["confirmar"])) AND ($_POST["confirmar"] == "AÃ±adir")) { 
   $texto_gravamen = trim($_POST["texto_gravamen"]);
	 if ($texto_gravamen != "") {	  
     pg_query("INSERT INTO gravamen (cod_geo, id_inmu, fecha, user_id, texto) 
		           VALUES ('$cod_geo','$id_inmu','$fecha','$user_id','$texto_gravamen')");	
   }
}
########################################
#---------------- BORRAR --------------#
########################################	
if ((isset($_POST["confirmar"])) AND ($_POST["confirmar"] == "SI")) { 
	 pg_query("DELETE FROM gravamen WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");
   $accion = "Gravamen borrado";
	 $username = get_username($session_id);
	 pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");		
}
########################################
#-------- LEER CAMBIOS DE TABLA -------#
########################################	
   $mensaje_cambio = "No se ha registrado ningun cambio con ese $predio en la base de datos.";

################################################################################
#------------------------------ CHEQUEAR GRAVAMEN -----------------------------#
################################################################################	
$sql="SELECT * FROM gravamen WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
$check_gravamen = pg_num_rows(pg_query($sql)); 
if ($check_gravamen == 0) {
   $gravamen = false;
	 $texto = "";
} else {
   $result=pg_query($sql);
   $info = pg_fetch_array($result, null, PGSQL_ASSOC);
	 $fecha_gravamen = change_date ($info['fecha']);
   $usuario_gravamen = utf8_decode(get_username2 ($info['user_id']));		 
   $texto_gravamen = utf8_decode($info['texto']);   
   $gravamen = true;
}
################################################################################
#------------------ CHEQUEAR SI EL PREDIO ESTA ACTIVO -------------------------#
################################################################################	
#$sql="SELECT activo FROM codigos WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'";
#$result_act = pg_query($sql);
#$act = pg_fetch_array($result_act, null, PGSQL_ASSOC);
#$activo = $act['activo'];
#pg_free_result($result_act);	
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	
	 echo "<td>\n";
	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
   # Fila 1
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"10%\">\n";  #Col. 1 
   echo "            &nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&id=$session_id\">\n";		
#   echo "            <img border='1' src='http://$server/$folder/graphics/boton_atras.png' width='35' height='35'></a>\n"; 
   echo "            <img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";	
	 echo "         </td>\n";   	    
   echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"65%\" class=\"pageName\">\n"; 
	 echo "            Gravamen\n";
	# if ($activo == 0) {
	#    echo "            <font color=\"red\"> - Archivo</font>\n";
	# } 	                           
   echo "         </td>\n";
	 echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";	
   echo "      <tr>\n";  
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	
	 echo "            <table width=\"100%\" border=\"0\">\n";		# 5 Columnas		
 	 echo "               <tr>\n";		
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">Código</td>\n";
	 echo "                  <td width=\"1%\"></td>\n";	 
	 echo "                  <td align=\"center\" width=\"43%\" class=\"bodyTextH\">Nombre</td>\n";
	 echo "                  <td width=\"1%\"></td>\n";		 
	 echo "                  <td align=\"center\" width=\"43%\" class=\"bodyTextH\">Dirección</td>\n";			
	 echo "               </tr>\n";
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"center\" class=\"bodyTextD\">$cod_cat</td>\n";
	 echo "                  <td></td>\n";	 	  
	 echo "                  <td align=\"center\" class=\"bodyTextD\">$titular1</td>\n";
	 echo "                  <td></td>\n";	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">$direccion</td>\n";				 				 
   echo "               </tr>\n";		  
	 echo "            </table>\n"; 
	 echo "         </td>\n";
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 3   		
	 echo "      </tr>\n";
 	 echo "      <tr>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1  
 	 echo "         <td align=\"left\">\n"; 
	 echo "         </td>\n";	
	 echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	 echo "      </tr>\n";	
   # Fila 2
	 if (($nivel == 2) OR ($nivel == 4) OR ($nivel == 5)) {
	    echo "					<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=15&id=$session_id\" accept-charset=\"utf-8\">\n";	 	 
	 }
if ($mostrar_casilla) {
	 echo "      <tr>\n";  
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	  
	 echo "         <fieldset><legend>Gravamen del Inmueble</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";  #TABLE 4 Columnas   
	 if ($gravamen) {
	    echo "               <tr>\n"; 
	    echo "                  <td></td>\n";   #Col. 1		  	                       
	    echo "                  <td align=\"left\" colspan=\"2\" class=\"bodyTextD\">\n";   #Col. 4-7	  
	    echo "                     GRAVAMEN:\n";	 
	    echo "                  </td>\n"; 
	    echo "                  <td></td>\n";   #Col. 8	    		 	   	 	 	    
	    echo "               </tr>\n";	
	    echo "               <tr>\n"; 
	    echo "                  <td></td>\n";   #Col. 1		  	                       
	    echo "                  <td align=\"left\" colspan=\"2\" class=\"bodyTextD\">\n";   #Col. 4-7	  
	    echo "                     <font color=\"red\">$texto_gravamen</font>\n";	 
	    echo "                  </td>\n"; 
	    echo "                  <td></td>\n";   #Col. 8	    		 	   	 	 	    
	    echo "               </tr>\n";			
	    echo "               <tr>\n"; 
	    echo "                  <td colspan=\"5\">&nbsp</td>\n";   #Col. 1		  	                      		 	   	 	 	    
	    echo "               </tr>\n";			
	    echo "               <tr>\n"; 
	    echo "                  <td width=\"5%\"></td>\n";   #Col. 1		  	                     
	    echo "                  <td align=\"left\" width=\"30%\" class=\"bodyTextD\">\n";   #Col. 2  
	    echo "                     FECHA: &nbsp&nbsp <font color=\"red\">$fecha_gravamen</font>\n";	 
	    echo "                  </td>\n";   	                        
	    echo "                  <td align=\"left\" width=\"60%\" class=\"bodyTextD\">\n";   #Col. 3  
	    echo "                     USUARIO: &nbsp&nbsp <font color=\"red\">$usuario_gravamen</font>\n";	 
	    echo "                  </td>\n"; 			
	    echo "                  <td width=\"5%\"></td>\n";   #Col. 4	    		 	   	 	 	    
	    echo "               </tr>\n";
   } else {
	    echo "               <tr>\n"; 
	    echo "                  <td width=\"5%\"></td>\n";   #Col. 1		  	                     
	    echo "                  <td align=\"center\" width=\"90%\" class=\"bodyTextD\">\n";   #Col. 2    	  	 
	    echo "                     El inmueble no tiene ningún gravamen!\n"; 	   		
	    echo "                  </td>\n"; 
	    echo "                  <td width=\"5%\"></td>\n";   #Col. 5	    		 	   	 	 	    
	    echo "               </tr>\n";
	 } 
#	    echo "               <tr>\n"; 
#	    echo "                  <td align=\"center\" class=\"bodyTextD_small\" colspan=\"7\">$mensaje_cambio</td>\n";   #Col. 1-7			 
#	    echo "               </tr>\n";
	 echo "            </table>\n";  
	 echo "         </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 3   		
	 echo "      </tr>\n";
} #END_OF_IF ($mostrar_casilla)	 
	 if (($nivel == 2) OR ($nivel == 4) OR ($nivel == 5)) {
	    if (!isset($_POST["submit"])) {  
         echo "      <tr height=\"40px\">\n";
	       echo "         <td> &nbsp</td>\n";   #Col. 1 	    
         echo "         <td align=\"center\" valign=\"center\">\n";   #Col. 2 
	       echo "            <input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";		 
	       echo "            <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";	
				 if ($check_gravamen == 0) {
	          echo "            <input name=\"submit\" type=\"submit\" value=\"Añadir\" class=\"smallText\">\n";
				 } else {				   	                            
	          echo "            <input name=\"submit\" type=\"submit\" value=\"Borrar\" class=\"smallText\">\n";
				 }
         echo "         </td>\n";
	       echo "         <td> &nbsp</td>\n";   #Col. 3 			 
         echo "      </tr>\n";
			} else {		
		     if ($_POST["submit"] == "AÃ±adir") { 	
				    echo "      <tr height=\"15\">\n"; 
	          echo "         <td colspan=\"3\"></td>\n";   #Col. 1		  	                        		 	   	 	 	    
	          echo "      </tr>\n";					  
				    echo "      <tr>\n"; 
	          echo "         <td></td>\n";   #Col. 1		  	                     
	          echo "         <td align=\"center\" class=\"bodyTextH\">\n";   #Col. 2    	  	 
	          echo "            <label>TEXTO DE GRAVAMEN:</label>\n"; 
	          echo "         </td>\n"; 
	          echo "         <td></td>\n";   #Col. 3	    		 	   	 	 	    
	          echo "      </tr>\n";	
				    echo "      <tr>\n"; 
	          echo "         <td></td>\n";   #Col. 1		  	                     
	          echo "         <td align=\"center\" cellpadding=10>\n";   #Col. 2   												   		
            echo "             <textarea name=\"texto_gravamen\" id=\"form_anadir3\" class=\"navTextS\"></textarea>\n";					
	          echo "         </td>\n"; 
	          echo "         <td></td>\n";   #Col. 3	    		 	   	 	 	    
	          echo "      </tr>\n";
			      echo "      <tr>\n";
	          echo "         <td> &nbsp</td>\n";   #Col. 1 				  
 	          echo "         <td align=\"center\">\n"; #Col. 2	
			      echo "            <input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n"; 		
	          echo "            <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";			 									 							
			      echo "            <input name=\"confirmar\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Añadir\">\n"; 		
	          echo "         </td>\n";	
 	          echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	          echo "      </tr>\n";																			 			
			   } elseif ($_POST["submit"] == "Borrar") { 
			      echo "      <tr>\n";
	          echo "         <td> &nbsp</td>\n";   #Col. 1 				  
 	          echo "         <td align=\"center\">\n"; #Col. 2	
			      echo "            <font color=\"red\"> Está seguro de borrar el gravamen?</font>\n"; 
			      echo "            <input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n"; 		
	          echo "            <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";				 									 							
			      echo "            <input name=\"confirmar\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"SI\">\n"; 
 	          echo "            &nbsp&nbsp&nbsp&nbsp&nbsp<input name=\"confirmar\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"NO\">\n"; 			
	          echo "         </td>\n";	
 	          echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	          echo "      </tr>\n";
				 }
	    } 
	 }  	  	 	 	 	  
	 echo "      </form>\n";		 
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
	 echo "   <br />&nbsp;<br />\n";
	 echo "</td>\n";	
	
?>