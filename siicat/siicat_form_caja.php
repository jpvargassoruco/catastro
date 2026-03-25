<?php

$error1 = $error2 = $calcular = $formulario = false;
include "siicat_lista_contribuyentes.php";
include "siicat_lista_tasas.php";
   if ((isset($_POST["submit"])) AND (($_POST["submit"] == "Calcular") OR ($_POST["submit"] == "Formulario"))) {
	    if ($_POST["submit"] == "Formulario") {
			   $formulario = true;
				 $numero = 1000;
			}
			$calcular = true;
      $id_contrib = $_POST["id_contrib"];
      $nombre = trim($_POST["nombre"]);				
      $detalle = $_POST["detalle"];
      $item1 = $_POST["item1"];
      $cant1 = $_POST["cant1"];
      $item2 = $_POST["item2"];
      $cant2 = $_POST["cant2"];	
      $item3 = $_POST["item3"];
      $cant3 = $_POST["cant3"];
			if (($id_contrib == "") AND ($nombre	== "")) {		
			   $error1 = true;
				 $mensaje_de_error1 = "Error: Tiene que elegir un nombre de la lista o ingresar un nombre!";	
				 $calcular = false;
			}	elseif (($item1 == "") AND ($item2 == "") AND ($item3 == "")) {
				 $error2 = true;
				 $mensaje_de_error2 = "Error: Tiene que elegir al menos un item de la lista!";	
				 $calcular = false;		
			}	else {
			   $rubro = $no_rubro;
				 $nombre_rubro = "Tasas Administrativas";
				 $monto_total = 0;
			   $i = 0;
			   if ($item1 != "") {
			      $sql = "SELECT descrip, monto FROM tasas WHERE id_tasa = '$item1'";
			      $result = pg_query($sql);
            $info = pg_fetch_array($result, null, PGSQL_ASSOC);
            $descrip_lista[$i] = utf8_decode($info['descrip']);	
	          $monto_lista[$i] = $info['monto'];
            pg_free_result($result);
						$cant_lista[$i] = $cant1;	
						$monto_lista_total[$i] = $monto_lista[$i] * $cant1;
						$monto_total = $monto_total + $monto_lista_total[$i];
				    $i++;
			   }
			   if ($item2 != "") {
			      $sql = "SELECT descrip, monto FROM tasas WHERE id_tasa = '$item2'";
			      $result = pg_query($sql);
            $info = pg_fetch_array($result, null, PGSQL_ASSOC);
            $descrip_lista[$i] = utf8_decode($info['descrip']);	
	          $monto_lista[$i] = $info['monto'];
            pg_free_result($result);	
						$cant_lista[$i] = $cant2;							
						$monto_lista_total[$i] = $monto_lista[$i] * $cant2;
						$monto_total = $monto_total + $monto_lista_total[$i];						
				    $i++;
			   }	
			   if ($item3 != "") {
			      $sql = "SELECT descrip, monto FROM tasas WHERE id_tasa = '$item3'";
			      $result = pg_query($sql);
            $info = pg_fetch_array($result, null, PGSQL_ASSOC);
            $descrip_lista[$i] = utf8_decode($info['descrip']);	
	          $monto_lista[$i] = $info['monto'];
            pg_free_result($result);	
						$cant_lista[$i] = $cant3;
						$monto_lista_total[$i] = $monto_lista[$i] * $cant3;
						$monto_total = $monto_total + $monto_lista_total[$i];						
				    $i++;
			   }
				 $cant_de_items = $i;				 			 
			}
	 } else {
	    $item1 = $item2 = $item3 = "default";
			$cant1 = $cant2 = $cant3 = "1";
      $detalle = $nombre = "";			
	 }

################################################################################
#------------------------------- FORMULARIO -----------------------------------#
################################################################################	 
if (!$formulario) {
   echo "<td>\n";
   echo "  <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";	 
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"15%\"> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n"; 
	 echo "            Generar Formulario Unico de Caja\n";                          
   echo "         </td>\n";
	 echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";
   echo "      <tr height=\"10px\">\n";
	 echo "         <td colspan=\"3\"> &nbsp</td>\n";   #Col. 1-3
   echo "      </tr>\n";	 	
   echo "			 <form name=\"form1\" method=\"post\" action=\"index.php?mod=75&id=$session_id\" accept-charset=\"utf-8\">\n";	
	 ##################################################
	 #------------ NOMBRE O RAZON SOCIAL -------------#
	 ##################################################
	 echo "      <tr>\n"; 	
	 echo "         <td> &nbsp</td>\n";   #Col. 1 	  
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Nombre o Razon Social</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  15 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" class=\"bodyText\" colspan=\"4\" ></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 6		  	 
	 echo "                  <td align=\"center\" width=\"13%\" class=\"bodyTextH\">NOMBRE:</td>\n";   #Col. 7 
	 echo "                  <td width=\"66%\" align=\"center\" class=\"bodyText\">\n";   #Col. 2	 	 
   echo "                     <select class=\"navText\" name=\"id_contrib\" size=\"1\">\n";
   if ((!isset($_POST['id_contrib'])) OR ($_POST['id_contrib'] == "")) {	 
	    echo "                        <option id=\"form0\" value=\"\" selected=\"selected\"> --- Seleccionar de la lista de Contribuyentes ---</option>\n";    
	 } else {
	    echo "                        <option id=\"form0\" value=\"\"> --- Seleccionar de la lista de Contribuyentes ---</option>\n";	 
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
	 echo "                  <td width=\"20%\"></td>\n";   #Col. 3		      	   	 	   	 	 	    
	 echo "               </tr>\n";	 
	 echo "            </table>\n"; 
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  9 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 6		  	 
	 echo "                  <td align=\"center\" width=\"13%\" class=\"bodyTextH\">NOMBRE:</td>\n";   #Col. 7  
	 echo "                  <td align=\"center\" width=\"85%\" class=\"bodyTextD\"><input type=\"text\" name=\"nombre\" id=\"form_anadir1\" class=\"navText\" value=\"$nombre\"></td>\n";   #Col. 8  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 9	 	 	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n";	 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "         <td> &nbsp</td>\n";   #Col. 3 	 
	 echo "      </tr>\n";
	 if ($error1) {
      echo "      <tr>\n"; 
	    echo "         <td> &nbsp</td>\n";   #Col. 1				 
	    echo "         <td align=\"center\" height=\"20\">\n";   #Col. 2  	 			 
	    echo "            <font color=\"red\">$mensaje_de_error1</font> <br />\n";				 	    
		  echo "         </td>\n"; 
	    echo "         <td> &nbsp</td>\n";   #Col. 3 			
      echo "      </tr>\n";
	 } 
	 ##################################################
	 #------------------- DETALLE --------------------#
	 ##################################################
	 echo "      <tr>\n"; 	
	 echo "         <td> &nbsp</td>\n";   #Col. 1   
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Detalle</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  9 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 6		  	 
	 echo "                  <td align=\"center\" width=\"13%\" class=\"bodyTextH\">DETALLE:</td>\n";   #Col. 7  
	 echo "                  <td align=\"center\" width=\"85%\" class=\"bodyTextD\"><input type=\"text\" name=\"detalle\" id=\"form_anadir1\" class=\"navText\" value=\"$detalle\"></td>\n";   #Col. 8  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 9	 	 	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "         <td> &nbsp</td>\n";   #Col. 3 	 
	 echo "      </tr>\n";
	/* if ($error2) {
      echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	    echo "         <font color=\"red\">$mensaje_de_error2</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "      </tr>\n";
	 }			 	*/  	 
	 ##################################################
	 #------------------ ITEMS -----------------------#
	 ##################################################
	 echo "      <tr>\n"; 	
	 echo "         <td> &nbsp</td>\n";   #Col. 1 	  
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Items</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  15 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" class=\"bodyText\" colspan=\"3\" ></td>\n";   #Col. 1	 
	 echo "               </tr>\n";
	 ### ITEM 1	   
	 echo "               <tr>\n";
	 #echo "                  <td width=\"2%\"> &nbsp </td>\n";   #Col. 1	 
	 echo "                  <td width=\"10%\"> &nbsp Item 1:</td>\n";   #Col. 1		 
	 echo "                  <td width=\"74%\" align=\"center\" class=\"bodyText\">\n";   #Col. 2	 	 
   echo "                     <select class=\"navText\" name=\"item1\" size=\"1\">\n";
   if ((!isset($_POST['item1'])) OR ($_POST['item1'] == "")) {	 
	    echo "                        <option id=\"form0\" value=\"\" selected=\"selected\"> --- Seleccionar de la lista ---</option>\n";    
	 } else {
	    echo "                        <option id=\"form0\" value=\"\"> --- Seleccionar de la lista ---</option>\n";    
	 }	 
	 $i = 0;
	 while ($i < $no_de_subniveles) {
		  $value_temp = $id_tasa_lista[$i]; 	
			if ($value_temp == $item1) {
			   echo "                   <option id=\"form0\" value=\"$value_temp\" selected=\"selected\"> &nbsp $descrip_lista[$i]</option>\n";
		  } else {
			   echo "                   <option id=\"form0\" value=\"$value_temp\"> &nbsp $descrip_lista[$i]</option>\n";
	    }
	    $i++;
   } 	
   echo "                     </select>\n";	 
	 echo "                  </td>\n";		
	 echo "                  <td width=\"8%\"> Cant.: </td>\n";   #Col. 3	 
	 echo "                  <td width=\"8%\"><input type=\"text\" name=\"cant1\" id=\"form_anadir2\" class=\"navText\" value=\"$cant1\"></td>\n";   #Col. 4	  	   	 	   	 	 	    
	 echo "               </tr>\n";
	 ### ITEM 2
	 echo "               <tr>\n";
#	 echo "                  <td> &nbsp </td>\n";   #Col. 1		 
	 echo "                  <td> &nbsp Item 2:</td>\n";   #Col. 1	 
	 echo "                  <td align=\"center\" class=\"bodyText\">\n";   #Col. 2	 	 
   echo "                     <select class=\"navText\" name=\"item2\" size=\"1\">\n";
   if ((!isset($_POST['item2'])) OR ($_POST['item2'] == "")) {	 
	    echo "                        <option id=\"form0\" value=\"\" selected=\"selected\"> --- Seleccionar de la lista ---</option>\n";    
	 } else {
	    echo "                        <option id=\"form0\" value=\"\"> --- Seleccionar de la lista ---</option>\n";    
	 }	 
	 $i = 0;
	 while ($i < $no_de_subniveles) {
		  $value_temp = $id_tasa_lista[$i]; 	
			if ($value_temp == $item2) {
			   echo "                   <option id=\"form0\" value=\"$value_temp\" selected=\"selected\"> &nbsp $descrip_lista[$i]</option>\n";
		  } else {
			   echo "                   <option id=\"form0\" value=\"$value_temp\"> &nbsp $descrip_lista[$i]</option>\n";
	    }
	    $i++;
   } 	 	
   echo "                     </select>\n";	 
	 echo "                  </td>\n";	
	 echo "                  <td> Cant.: </td>\n";   #Col. 3	 
	 echo "                  <td><input type=\"text\" name=\"cant2\" id=\"form_anadir2\" class=\"navText\" value=\"$cant2\"></td>\n";   #Col. 4	      	   	 	   	 	 	    
	 echo "               </tr>\n";
	 ### ITEM 3
	 echo "               <tr>\n";
#	 echo "                  <td> &nbsp </td>\n";   #Col. 1		 
	 echo "                  <td> &nbsp Item 3:</td>\n";   #Col. 1	 
	 echo "                  <td align=\"center\" class=\"bodyText\">\n";   #Col. 2	 	 
   echo "                     <select class=\"navText\" name=\"item3\" size=\"1\">\n";
   if ((!isset($_POST['item3'])) OR ($_POST['item3'] == "")) {	 
	    echo "                        <option id=\"form0\" value=\"\" selected=\"selected\"> --- Seleccionar de la lista ---</option>\n";    
	 } else {
	    echo "                        <option id=\"form0\" value=\"\"> --- Seleccionar de la lista ---</option>\n";    
	 }	 
	 $i = 0;
	 while ($i < $no_de_subniveles) {
		  $value_temp = $id_tasa_lista[$i]; 	
			if ($value_temp == $item3) {
			   echo "                   <option id=\"form0\" value=\"$value_temp\" selected=\"selected\"> &nbsp $descrip_lista[$i]</option>\n";
		  } else {
			   echo "                   <option id=\"form0\" value=\"$value_temp\"> &nbsp $descrip_lista[$i]</option>\n";
	    }
	    $i++;
   } 	 	
   echo "                     </select>\n";	 
	 echo "                  </td>\n";	
	 echo "                  <td> Cant.: </td>\n";   #Col. 3	 
	 echo "                  <td><input type=\"text\" name=\"cant3\" id=\"form_anadir2\" class=\"navText\" value=\"$cant3\"></td>\n";   #Col. 4	     	   	 	   	 	 	    
	 echo "               </tr>\n";	 	 
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "         <td> &nbsp</td>\n";   #Col. 3 	  	
	 echo "      </tr>\n";	
	 if ($error2) {
      echo "      <tr>\n"; 
	    echo "         <td> &nbsp</td>\n";   #Col. 1				 
	    echo "         <td align=\"center\" height=\"20\">\n";   #Col. 2  	 			 
	    echo "            <font color=\"red\">$mensaje_de_error2</font> <br />\n";				 	    
		  echo "         </td>\n"; 
	    echo "         <td> &nbsp</td>\n";   #Col. 3 			
      echo "      </tr>\n";
	 } 	 
	 ##################################################
	 #--------------- BOTON CALCULAR -----------------#
	 ##################################################
	 echo "      <tr>\n"; 	
	 echo "         <td> &nbsp</td>\n";   #Col. 1   
	 echo "         <td align=\"center\" height=\"40\">\n";   #Col. 1+2+3  
	 echo "            <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Calcular\">\n";
	 echo "         </td>\n"; 
	 echo "         <td> &nbsp</td>\n";   #Col. 3 	 
	 echo "      </tr>\n";	  
	 ##################################################
	 #-------------------- MONTO ---------------------#
	 ##################################################
	 if ($calcular) {
	    echo "      <tr>\n"; 	
	    echo "         <td> &nbsp</td>\n";   #Col. 1   
	    echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 1+2+3  
#	    echo "         <fieldset><legend>Monto</legend>\n";
	    echo "            <table border=\"1\" width=\"100%\" style=\"border-collapse:collapse;\">\n"; #TABLE  9 Columnas   
	    echo "               <tr>\n";  	                     	  	 
#	    echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextD\">Rubro</td>\n";   #Col. 2  
	    echo "                  <td align=\"center\" width=\"55%\" class=\"bodyTextD\">Descripción</td>\n";
	    echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextD\">Monto</td>\n";
	    echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextD\">Cant.</td>\n";
	    echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextD\">Total</td>\n";			 	 	 	 	    
	    echo "               </tr>\n";
	    echo "               <tr>\n";  	                     
	#    echo "                  <td width=\"5%\"></td>\n";   #Col. 1		  	 
#	    echo "                  <td align=\"right\" valign=\"top\" class=\"bodyTextD\">$rubro &nbsp</td>\n";   #Col. 2  
	    echo "                  <td align=\"left\" class=\"bodyTextD\">&nbsp \n"; 
			$i = 0;
			while ($i < $cant_de_items) {
	       echo "                  &nbsp $descrip_lista[$i]<br />\n";			
			   $i++;
			}
			echo "                  </td>\n";
	    echo "                  <td align=\"right\" class=\"bodyTextD\">&nbsp \n";
			$i = 0;
			while ($i < $cant_de_items) {
	       echo "                  &nbsp $monto_lista[$i] &nbsp<br />\n";			
			   $i++;
			}
			echo "                  </td>\n";			 
	    echo "                  <td align=\"right\" class=\"bodyTextD\">&nbsp \n";
			$i = 0;
			while ($i < $cant_de_items) {
	       echo "                  &nbsp $cant_lista[$i] &nbsp<br />\n";			
			   $i++;
			}
			echo "                  </td>\n";			
	    echo "                  <td align=\"right\" class=\"bodyTextD\">&nbsp \n";		 	 
			$i = 0;
			while ($i < $cant_de_items) {
	       echo "                  &nbsp $monto_lista_total[$i] &nbsp<br />\n";			
			   $i++;
			}
			echo "                  </td>\n"; 	 	    
	    echo "               </tr>\n";
	    echo "               <tr>\n";  	                       	 
	    echo "                  <td align=\"left\" colspan=\"3\" class=\"bodyTextD\">&nbsp </td>\n";   #Col. 2  
	    echo "                  <td align=\"right\" class=\"bodyTextD\">$monto_total &nbsp </td>\n";	 	  	 	 	    
	    echo "               </tr>\n";						
	    echo "            </table>\n"; 
#	    echo "         </fieldset>\n";	 	 
	    echo "         </td>\n"; 
	    echo "         <td> &nbsp</td>\n";   #Col. 3 	 
	    echo "      </tr>\n";
	    ### BOTON GENERAR FORMULARIO	 
	    echo "      <tr>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 1 			 	 
	    echo "         <td align=\"center\" height=\"40\">\n";   #Col. 2 			
	    echo "            <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Formulario\">\n";	
	    echo "         </td>\n";													
	    echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	    echo "      </tr>\n"; 
   }  			
	 echo "      </form>\n";	
   # Ultima Fila 
   echo "      <tr height=\"100%\"></tr>\n";			 
   echo "   </table>\n";
#   echo "   <br />&nbsp;<br />\n";
   echo "</td>\n";	  
} else { ### $formulario = true
   echo "<td>\n";
   echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"770px\">\n";   # 3 Columnas
   echo "      <tr height=\"40px\">\n";
   echo "         <td align=\"left\">\n";  #Col. 1 
 #echo "            &nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&id=$session_id#tab-5\" alt='' title='Volver a la pantalla anterior'>\n";		
   echo "            &nbsp&nbsp <a href='javascript:history.back()'>\n";	
#   echo "            <img border='1' src='http://$server/$folder/graphics/boton_atras.png' width='35' height='35'></a>\n"; 
   echo "            <img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";	
   echo "         </td>\n";  
   echo "      </tr>\n";	 
   echo "      <tr>\n";
   echo "         <td valign=\"top\">\n";   #Col. 1 
# echo "          <a href='javascript:history.back()'>\n";		
# echo "           <img border='0' src='http://$server/siicat_concep/graphics/boton_atras.png' width='35' height='35'></a>\n";
include "siicat_form_caja_generar.php";
   echo "            <iframe frameborder=\"0\" name=\"mapserver\" src=\"http://$server/tmp/fc$numero.html\" id=\"content\" width=\"750px\" height=\"1270px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
   echo "            </iframe>\n";	
   echo "         </td>\n";	 
   echo "      </tr>\n";	 		
   echo "   </table>\n";
   echo "</td>\n";
}
?>