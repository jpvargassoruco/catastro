<?php

$patente_existe = false;
########################################
#------- RESULTADO DE BUSQUEDA---------#
########################################	
if ((isset($_POST["id_patente"])) OR (isset($_GET["id_patente"])) OR (isset($_POST["act_pat"]))) { 
   if (isset($_POST["id_patente"])) {
      $id_patente = $_POST["id_patente"];
			$sql="SELECT * FROM patentes WHERE id_patente = '$id_patente'";
	 } elseif (isset($_GET["id_patente"])) {
      $id_patente = $_GET["id_patente"];
      $sql="SELECT * FROM patentes WHERE id_patente = '$id_patente'";				 
	 } else {
	    $act_pat = $_POST["act_pat"];
			$sql="SELECT * FROM patentes WHERE act_pat = '$act_pat'";
	 }
   ########################################
   #-------- LEER DATOS DE TABLA ---------#
   ########################################	
   $check_patentes = pg_num_rows(pg_query($sql)); 
   if ($check_patentes == 1) {	
	       $patente_existe = true; 
         $result=pg_query($sql);
         $info = pg_fetch_array($result, null, PGSQL_ASSOC);
				 $id_patente = $info['id_patente'];
         $cod_geo = $info['cod_geo'];
         $id_inmu = $info['id_inmu'];
         $id_contrib = $info['id_contrib'];
         $act_pat = $info['act_pat'];
         $act_rub = $info['act_rub'];
				 $act_rub = get_rubro($act_rub);
         $act_raz = utf8_decode($info['act_raz']);
         $act_nit = $info['act_nit'];
				 if ($act_nit == "-1") {
				    $act_nit = "---";
				 }
         $act_tel = $info['act_tel'];
				 if ($act_tel == "") {
				    $act_tel = "---";
				 }				 
         $act_fech = $info['act_fech'];
				 if ($act_fech == "1900-01-01") {
				    $act_fech = "---";
				 } else $act_fech = change_date($act_fech);				 
         $act_sup = $info['act_sup'];
				 if (($act_sup == "") OR ($act_sup == "-1")) {
				    $act_sup = "---";
				 } else $act_sup = $act_sup." m²";		 
				/* if ($act_ciu != "") {
				    $act_dom = $act_dpto." ".$act_ciu;
						if ($act_dir != "") {
						   $act_dom = $act_dpto." ".$act_ciu." ".$act_dir;
						} else $act_dom = $act_dpto." ".$act_ciu;
				 } else $act_dom = $act_dpto;   */
				 $act_obs = utf8_decode($info['act_obs']);
			   pg_free_result($result);
				 ### OTROS DATOS ###
	       $act_prop = get_contrib_nombre($id_contrib);
				 $act_dir = get_direccion_from_id_inmu($id_inmu);	
				 $cod_cat = get_codcat_from_id_inmu($id_inmu);		 
   } else $patente_existe = false;			
}
########################################
#----- PATENTE RECIEN REGISTRADO ------#
########################################	
if ((isset($_POST["submit"])) AND ($_POST["submit"] == "Registrar")) { 
   $patente_existe = true;
	 $act_prop = get_contrib_nombre($id_contrib);
	 $act_dom = get_contrib_dom($id_contrib);
}	

$distrito = "CONCEPCION";
$deuda_existe = true;

$con_act_temp = "INACTIVO";
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	
	 echo "<td>\n";
	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
   # Fila 1
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"10%\">\n";  #Col. 1 
   echo "            &nbsp&nbsp <a href=\"index.php?mod=101&id=$session_id\">\n";		
#   echo "            <img border='1' src='http://$server/$folder/graphics/boton_atras.png' width='35' height='35'></a>\n"; 
   echo "            <img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";	
	 echo "         </td>\n";   	    
   echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n"; 
	 echo "            Patente\n";	                           
   echo "         </td>\n";
	 echo "         <td width=\30%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";	
if ($patente_existe) {	 
   echo "      <tr>\n";  
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	
	 echo "            <fieldset><legend>Patente</legend>\n";		 
	 echo "            <table width=\"100%\" border=\"0\">\n";		# 5 Columnas		
 	 echo "               <tr>\n";				 			           
	 echo "                  <td width=\"1%\">&nbsp</td>\n";	 
	 echo "                  <td align=\"right\" width=\"16%\" class=\"bodyTextD\">Estado &nbsp</td>\n";
	 echo "                  <td align=\"left\" width=\"21%\" class=\"bodyTextH\">&nbsp $con_act_temp</td>\n"; 	 	  
	 echo "                  <td align=\"right\" width=\"28%\" class=\"bodyTextD\">No. de Patente &nbsp</td>\n";
	 echo "                  <td align=\"left\" width=\"34%\" class=\"bodyTextH\">&nbsp $act_pat</td>\n"; 				 				 
   echo "               </tr>\n";
	 echo "            </table>\n";
	 echo "            </fieldset>\n";			 
	 echo "         </td>\n";  #Col. 2		 
   echo "      </tr>\n"; 
   echo "      <tr>\n";  
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	
#	 echo "         <fieldset><legend>Datos de la Actividad Económica</legend>\n";	 
	 echo "            <table width=\"100%\" border=\"0\">\n";		# 5 Columnas		
	 /*
 	 echo "               <tr height=\"40\">\n";		
	 echo "                  <td align=\"left\" colspan=\"2\"><b>IDENTIFICACION</b></td>\n";
	# echo "                  <td width=\"1%\"></td>\n";	 
	# echo "                  <td></td>\n";
	# echo "                  <td width=\"1%\"></td>\n";		 
	 echo "                  <td></td>\n";			
	 echo "               </tr>\n";
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextD\"></td>\n";
	# echo "                  <td></td>\n";	 	  
	 echo "                  <td align=\"right\" width=\"20%\" class=\"bodyTextD\">Patente &nbsp</td>\n";
	# echo "                  <td></td>\n";	 
	 echo "                  <td align=\"left\" width=\"70%\" class=\"bodyTextH\">&nbsp $act_pat</td>\n";				 				 
   echo "               </tr>\n";	
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"center\" class=\"bodyTextD\"></td>\n";
	# echo "                  <td></td>\n";	 	  
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Cod. Catastral &nbsp</td>\n";
	# echo "                  <td></td>\n";	 
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $cod_cat</td>\n";				 				 
   echo "               </tr>\n";	
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"center\" class=\"bodyTextD\"></td>\n";
	# echo "                  <td></td>\n";	 	  
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Distrito &nbsp</td>\n";
	# echo "                  <td></td>\n";	 
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $distrito</td>\n";				 				 
   echo "               </tr>\n";		 	 	  */
	# echo "            </table>\n"; 
	# echo "            <table width=\"100%\" border=\"0\">\n";		# 5 Columnas		
 	 echo "               <tr height=\"40px\">\n";		
	 echo "                  <td align=\"left\" colspan=\"3\"><b>DATOS ACTIVIDAD ECONOMICA</b></td>\n";
	# echo "                  <td width=\"1%\"></td>\n";	 
	# echo "                  <td></td>\n";
	# echo "                  <td width=\"1%\"></td>\n";		 
	 #echo "                  <td></td>\n";			
	 echo "               </tr>\n";
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"center\" class=\"bodyTextD\"></td>\n";
	# echo "                  <td></td>\n";	 	  
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Actividad &nbsp</td>\n";
	# echo "                  <td></td>\n";	 
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $act_rub</td>\n";				 				 
   echo "               </tr>\n";	
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"center\" class=\"bodyTextD\"></td>\n";
	# echo "                  <td></td>\n";	 	  
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Razon Social &nbsp</td>\n";
	# echo "                  <td></td>\n";	 
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $act_raz</td>\n";				 				 
   echo "               </tr>\n";	
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"center\" class=\"bodyTextD\"></td>\n";
	# echo "                  <td></td>\n";	 	  
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Propietario &nbsp</td>\n";
	# echo "                  <td></td>\n";	 
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $act_prop (<a href=\"index.php?mod=123&con=$id_contrib&id=$session_id\">ver</a>)</td>\n";				 				 
   echo "               </tr>\n";
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"center\" class=\"bodyTextD\"></td>\n";
	# echo "                  <td></td>\n";	 	  
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Cod. Catastral &nbsp</td>\n";
	# echo "                  <td></td>\n";	 
	 if (($cod_cat == "") OR ($cod_cat == 0)) {
	 	  echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp --- </td>\n";
	 } else {
	    echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $cod_cat (<a href=\"index.php?mod=5&inmu=$id_inmu&id=$session_id\">ver</a>)</td>\n";				 				 
   }
	 echo "               </tr>\n";			 	
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"center\" class=\"bodyTextD\"></td>\n";
	# echo "                  <td></td>\n";	 	  
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Dirección &nbsp</td>\n";
	# echo "                  <td></td>\n";	 
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $act_dir</td>\n";				 				 
   echo "               </tr>\n"; 	 	 	 	  
	# echo "            </table>\n";	 
	# echo "            <table width=\"100%\" border=\"0\">\n";		# 5 Columnas		act_nit act_tel act_fech act_sup
 	 echo "               <tr>\n";		
	 echo "                  <td align=\"left\" colspan=\"3\" height=\"30px\">&nbsp</td>\n";
	# echo "                  <td width=\"1%\"></td>\n";	 
	 #echo "                  <td align=\"center\" class=\"bodyTextH\"></td>\n";
	# echo "                  <td width=\"1%\"></td>\n";		 
	# echo "                  <td></td>\n";			
	 echo "               </tr>\n";
	 echo "               <tr>\n";                       
	 echo "                  <td valign=\"top\" height=\"180\" colspan=\"3\" class=\"bodyText\">\n";  #Col. 1+2+3	
	 echo "                  <div id=\"tabs\">\n";
	 echo "                     <ul>\n";
	 echo "                        <li><a href=\"#tab-1\"><span>Mas Datos</span></a></li>\n";
   echo "                        <li><a href=\"#tab-2\"><span>Licencia</span></a></li>\n";
	 echo "                        <li><a href=\"#tab-3\"><span>Modificar</span></a></li>\n";	
	 echo "                        <li><a href=\"#tab-4\"><span>Dar de baja</span></a></li>\n";	  
	 echo "                        <li><a href=\"#tab-5\"><span>Deudas</span></a></li>\n";  		 		 	  
	 echo "                     </ul>\n";
	 echo "                     <div id=\"tab-1\">\n";
include "siicat_patentes_datos.php"; 
 #       <p>First tab is active by default:</p>
 #       <pre><code>$('#example').tabs();</code></pre>
	 echo "                     </div>\n";
   echo "                     <div id=\"tab-2\">\n";
include "siicat_patentes_licencia.php"; 
	 echo "                     </div>\n";
	 echo "                     <div id=\"tab-3\">\n";
include "siicat_patentes_mod.php";  
	 echo "                     </div>\n";
   echo "                     <div id=\"tab-4\">\n";
include "siicat_patentes_deudas.php";  
	 echo "                     </div>\n";
	 echo "                     <div id=\"tab-5\">\n";
include "siicat_patentes_deudas.php";	
	 echo "                     </div>\n";	 		  	 
	 echo "                  </div>\n"; 	 
	 echo "                  </td>\n";	 	 	  	 
	 echo "               </tr>\n";
	 echo "            </table>\n";
	 echo "         </td>\n";  #Col. 2		 
   echo "      </tr>\n";     
} else { ### NO DE PATENTE NO EXISTE

   echo "      <tr height=\"40\">\n";  
	 echo "         <td> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td align=\"center\" class=\"bodyText\">\n";  #Col. 2	
 	 echo "         <fieldset><legend>Datos de la Actividad Económica</legend>\n";	
	 echo "             NO HAY REGISTRO CON ESE NUMERO DE PATENTE EN LA BASE DE DATOS! <br />POR FAVOR, REVISE EL NUMERO INGRESADO!";
	# echo "            <table width=\"100%\" border=\"0\">\n";		# 5 Columnas		
 	 #echo "               <tr height=\"40\">\n";	
	# echo "            </table>\n";	 	 
	 echo "         </fieldset>\n";	 
	 echo "         <td> &nbsp</td>\n";  	 	 	 
 	 echo "         </td>\n";		 
 	 echo "      </tr>\n";	
 
 #	 echo "      <tr height=\"20\">\n";
	## echo "         <td> &nbsp</td>\n";   
 # echo "         <td align=\"right\" class=\"bodyTextD\"> Fecha de censo: Junio 2011\n"; 
	# echo "         </td>\n";	
   	 
	# echo "      </tr>\n";		 	 	 	  
	 
}
	
	/* 
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
	 echo "      <tr>\n";  
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	  

	 echo "         </td>\n";
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 3   		
	 echo "      </tr>\n";
	 if (($nivel == 2) OR ($nivel == 4) OR ($nivel == 5)) {
	    if (!isset($_POST["submit"])) {  
         echo "      <tr height=\"40px\">\n";
	       echo "         <td> &nbsp</td>\n";   #Col. 1 	    
         echo "         <td align=\"center\" valign=\"center\">\n";   #Col. 2 
	       echo "            <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";		 
	       echo "            <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";	
				# if ($check_gravamen == 0) {
	          echo "            <input name=\"submit\" type=\"submit\" value=\"Modificar\" class=\"smallText\">\n";
			#	 } else {				   	                            
	    #      echo "            <input name=\"submit\" type=\"submit\" value=\"Borrar\" class=\"smallText\">\n";
			#	 }
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
			      echo "            <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n"; 		
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
			      echo "            <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n"; 		
	          echo "            <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";				 									 							
			      echo "            <input name=\"confirmar\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"SI\">\n"; 
 	          echo "            &nbsp&nbsp&nbsp&nbsp&nbsp<input name=\"confirmar\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"NO\">\n"; 			
	          echo "         </td>\n";	
 	          echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	          echo "      </tr>\n";
				 }
	    } 
	 }  	  	 	 	 	  
	 echo "      </form>\n";		 */
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
#	 echo "   <br />&nbsp;<br />\n";
	 echo "</td>\n";	
	
?>