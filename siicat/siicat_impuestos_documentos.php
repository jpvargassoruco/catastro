<?php

#$accion = "base_legal";
$docu = $borrar = $error = false;

################################################################################
#------------------------- BOTON SUBIR DOCUMENTO ------------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Subir Documento")) {
   $docu = true;
}
################################################################################
#------------------------- BOTON BORRAR DOCUMENTO -----------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Borrar Documento")) {
   $borrar = true;
}
################################################################################
#------------------------------- SUBIR DOCUMENTO ------------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Subir")) {
	 $tipo = $_POST["tipo"];
	 if  ($_POST["fecha_emision"] == "") {
	    $fecha_emision = $fecha;
	 } else {
	    $fecha_emision = $_POST["fecha_emision"];
			if (!check_fecha($fecha_emision,$dia_actual,$mes_actual,$ano_actual)) {
			   $error = true;
				 $mensaje_de_error = "Error: El formato de la fecha ingresada no es correcto!";
			}
	 }
#   $no_de_foto = 1;
	 include "siicat_upload_documento.php";
	 if ($error) {
	    $docu = true;
#echo "ERROR";
	 } else { 
      #$foto1_temp = $cod_cat.$format;		 
      #pg_query("UPDATE fotos SET f1 = 'TRUE' WHERE cod_cat = '$cod_cat'");
			$accion = "Documento subido";
			if ($_POST["titulo"] == "") {    
			   $titulo = $file_nombre;
			} else $titulo = $_POST["titulo"];
			$sql="SELECT tipo FROM imp_documentos WHERE titulo = '$titulo'";
      $check_documentos = pg_num_rows(pg_query($sql));		
			if ($check_documentos == 1) {
			   $docu = true;
         $docupath = "C:/apache/htdocs/$folder/documentos/$file_completo";		
         unlink($docupath);				 
         $error = true;
         $mensaje_de_error = "Error: No se pude subir el archivo. Ya existe un documento con el nombre '$titulo' en la base de datos!";	
			} else {
			   $archivo = utf8_encode($file_completo);
		     pg_query("INSERT INTO imp_documentos (tipo, titulo, archivo, emision, subido) 
		            VALUES ('$tipo','$titulo','$archivo','$fecha_emision','$fecha')");			
		     #pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		           # VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");	
	    }			 
	 }
}
################################################################################
#---------------------------- BORRAR DOCUMENTO --------------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Borrar")) {
	 if (isset($_POST["doc_tit"])) {
      $doc_tit = $_POST["doc_tit"];
      $sql="SELECT archivo FROM imp_documentos WHERE titulo = '$doc_tit'";
      $result = pg_query($sql);
      $info = pg_fetch_array($result, null, PGSQL_ASSOC);
      $archivo = $info['archivo'];			
      $docupath = "C:/apache/htdocs/$folder/documentos/$archivo";		
      unlink($docupath);			
      pg_query("DELETE FROM imp_documentos WHERE titulo = '$doc_tit'");
      pg_free_result($result);
   }		
}
################################################################################
#-------------------------------- BASE LEGAL ----------------------------------#
################################################################################	 
#if (((isset($_POST["submit"])) AND (($_POST["submit"]) == "Base Legal")) OR ($base_legal)) {	 
   $sql="SELECT tipo, titulo, archivo, emision, subido FROM imp_documentos ORDER BY emision DESC, titulo ASC";
   $no_de_documentos = pg_num_rows(pg_query($sql));
   $result = pg_query($sql);
   $i = $j = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
	       if ($i == 0) { $lista_tipo[$j] = $col_value; 			
	       } elseif ($i == 1) { $lista_titulo[$j] = utf8_decode($col_value); 	 
	       } elseif ($i == 2) { 
				    $lista_archivo[$j] = utf8_decode($col_value);
						$stringlength = strlen($lista_archivo[$j]);
            $lista_formato[$j] = strtoupper (substr($lista_archivo[$j],$stringlength-3,3)); 
			   } elseif ($i == 3) { $lista_emision[$j] = $col_value; 
			   } else { 
			      $lista_subido[$j] = $col_value;
				    $i = -1;
			   }
			   $i++;
      }
	    $j++;
   } 			
   pg_free_result($result);		 
#}
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	
	
	 echo "<td>\n";
	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
	 # Fila 1
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"10%\"> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"70%\" class=\"pageName\">\n"; 
	 echo "            Base Legal\n";                          
   echo "         </td>\n";
	 echo "         <td width=\"20%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";	
	
	 # Fila 3
   echo "      <tr>\n";    
   echo "         <td colspan=\"3\"> &nbsp</td>\n";  #Col. 1-3	 
   echo "      </tr>\n";
	 # Fila 4
	 if ($borrar) {
	    echo "		  <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=59&id=$session_id\" accept-charset=\"utf-8\">\n";
   }
	 echo "      <tr height=\"40px\">\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1
   echo "         <td align=\"left\" valign=\"center\" height=\"40\">\n";
	 echo "         <fieldset><legend>Documentos en la base de datos</legend>\n";	 
	 echo "            <table border=\"0\" width=\"100%\">\n";    # 10 Columnas	 
	 echo "               <tr>\n"; 
	 echo "                  <td width=\"3%\"> &nbsp</td>\n";   #Col. 1 
	 echo "                  <td align=\"center\" width=\"5%\" class=\"bodyTextH\"> No.</td>\n";   #Col. 2
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";   #Col. 3 	  
	 echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextH\"> Tipo </td>\n";   #Col. 4 
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";   #Col. 5	 	 	 	 
	 echo "                  <td align=\"center\" width=\"53%\" class=\"bodyTextH\">Título del Documento</td>\n";   #Col. 6
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";   #Col. 7	 		  	
	 echo "                  <td align=\"center\" width=\"13%\" class=\"bodyTextH\">Formato</td>\n";   #Col. 8		
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";   #Col. 9	   	
	 echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">Emisión</td>\n";   #Col. 10	
   echo "               </tr>\n";			  		 
	 if ($no_de_documentos == 0) { 
	    echo "               <tr>\n";		 
      echo "                  <td align=\"center\" colspan=\"10\" class=\"bodyTextD\">\n";  #Col. 1-6 	    
      echo "                     <font>No se encuentra ningun archivo en la base de datos!</font>\n";	
      echo "                  </td>\n";	
      echo "               </tr>\n";						
	 } else {
	    #echo "            <a target=\"_blank\" href=\"http://$server/catastro_br/documentos/RS_228773-IPBI.pdf\">RS_228773-IPBI.pdf</a><br />\n";  
	    #echo "            <a href=\"javascript:neuesfenster('http://$server/catastro_br/documentos/RS_228773-IPBI.pdf','yes','yes','800','600','Yes','Yes','Yes','Yes')\"> RS_228773-IPBI.pdf</a><br />\n";                         
      #echo "            <a href=\"http://$server/catastro_br/documentos/RS_228773-IPBI.pdf\" onClick=\"window.open(this.href,'Tuts','width=300, height=300')\";>RS_228773-IPBI.pdf</a><br />\n";
	    $i = $k = 0;
	    while ($i < $no_de_documentos) {
			   $j = $i+1;
	       echo "               <tr>\n";
				 if ($borrar) {
	          if ($k == 0){
			         echo "                   <td class=\"bodyTextD_Small\"><input name=\"doc_tit\" value=\"$lista_titulo[$i]\" type=\"radio\" checked=\"checked\"></td>\n";   #Col. 1
							 $k++;
		        } else {
			         echo "                   <td class=\"bodyTextD_Small\"><input name=\"doc_tit\" value=\"$lista_titulo[$i]\" type=\"radio\"></td>\n";   #Col. 1						 
			      }	 
	       } else {
	          echo "                  <td> &nbsp</td>\n";   #Col. 1 
	       }				 
	       echo "                  <td align=\"right\"> $j</td>\n";   #Col. 2 
				 echo "                  <td> &nbsp</td>\n";   #Col. 3
	       echo "                  <td align=\"center\"> $lista_tipo[$i] </td>\n";   #Col. 4
				 echo "                  <td> &nbsp</td>\n";   #Col. 5				  	 	 	 
	       echo "                  <td align=\"left\"> \n";   #Col. 6		
         echo "                     &nbsp <a href=\"AQUI\" onClick=\"window.open('http://$server/$folder/documentos/$lista_archivo[$i]','Doc','width=600, height=600'); return false\";>$lista_titulo[$i]</a>\n";
         echo "                  </td>\n";
				 echo "                  <td> &nbsp</td>\n";   #Col. 7				 				 
	       echo "                  <td align=\"center\"> $lista_formato[$i]</td>\n";   #Col. 8
				 echo "                  <td> &nbsp</td>\n";   #Col. 9				 		  	
	       echo "                  <td align=\"center\"> $lista_emision[$i]</td>\n";   #Col. 10
         echo "               </tr>\n";				 				  
	       $i++;
	    }
   }
	 echo "               </tr>\n"; 	
	 echo "            </table>\n";
	 echo "         </fieldset>\n";	  		 
   echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";
	 # Fila 5
   echo "      <tr height=\"10px\">\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td align=\"left\">\n"; 
	 echo "            &nbsp (DS = Decreto Supremo, L = Ley, OM = Ordenanza Municipal, RN = Res. Normativa, RS = Res. Suprema)\n";                          
   echo "         </td>\n";
	 echo "         <td width=\"20%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";	 
	 if (($nivel == 4) OR ($nivel == 5)) {
	    if ((!$docu) AND (!$borrar)) {
	       # Fila 5
	       echo "			 <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=59&id=$session_id\" accept-charset=\"utf-8\">\n"; 						 
         echo "      <tr height=\"30px\">\n"; 
	       echo "         <td> &nbsp</td>\n";   #Col. 1  
	       echo "         <td align=\"center\">\n";   #Col. 2	
         echo "            <input type=\"hidden\" name=\"accion\" value=\"base_legal\">\n";				   	
         echo "            <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Subir Documento\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";
         echo "            <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Borrar Documento\">\n";				 
	       echo "         </td>\n";
	       echo "         <td> &nbsp</td>\n";   #Col. 3				 	   	 
         echo "      </tr>\n";	
	       echo "      </form>\n";					 
			} else {
			   if ($docu) {
	          # Fila 5
			      echo "		  <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=59&id=$session_id\" accept-charset=\"utf-8\" enctype=\"multipart/form-data\">\n";
            echo "      <tr height=\"30px\">\n";			
	          echo "         <td> &nbsp</td>\n";   #Col. 1 	
	          echo "         <td align=\"left\">\n";  #Col. 2 
	          echo "            <table border=\"0\" width=\"100%\">\n";    # 3 Columnas	
	          echo "               <tr>\n";
	          echo "                  <td align=\"center\" colspan=\"3\" class=\"bodyTextD_Small\"> Subir documento</td>\n";   #Col. 1 
	          echo "               </tr>\n";				  
	          echo "               <tr>\n";
	          echo "                  <td align=\"left\" width=\"19%\"> Tipo de documento:</td>\n";   #Col. 1 
	          echo "                  <td align=\"left\" width=\"51%\"> Título del documento:</td>\n";   #Col. 2				 				 
            echo "                  <td align=\"left\" width=\"30%\"> Fecha Emisión (DD/MM/AAAA):</td>\n";   #Col. 3 
	          echo "               </tr>\n";
	          echo "               <tr>\n";
	          echo "                  <td align=\"left\">\n";   #Col. 1 		
            echo "                     <select class=\"navText\" name=\"tipo\" size=\"1\">\n";
	          echo "                        <option id=\"form0\" value=\"---\" selected=\"selected\"> &nbsp ----------  </option>\n";	
	          echo "                        <option id=\"form0\" value=\"DS\"> Decreto Supremo (DS)</option>\n";		
	          echo "                        <option id=\"form0\" value=\"L\"> Ley (L)</option>\n";																
		        echo "                        <option id=\"form0\" value=\"OM\"> Ordenanza Municipal (OM)</option>\n";
		        echo "                        <option id=\"form0\" value=\"RN\"> Res. Normativa (RN)</option>\n";	
	          echo "                        <option id=\"form0\" value=\"RS\"> Res. Suprema (RS)</option>\n"; 							
            echo "                     </select>\n";	 				 				 
	          echo "                  </td>\n";
	          echo "                  <td align=\"left\">\n";   #Col. 2 				 
            echo "                     <input type=\"text\" name=\"titulo\" id=\"form_anadir0\" class=\"navText\" value=\"\">\n";					 
	          echo "                  </td>\n";
	          echo "                  <td align=\"left\">\n";   #Col. 3 				 
            echo "                     <input type=\"text\" name=\"fecha_emision\" id=\"form_anadir0\" class=\"navText\" value=\"\">\n";					 
	          echo "                  </td>\n";				 				 
	          echo "               </tr>\n";	
            echo "               <tr>\n";				
	          echo "                  <td align=\"center\" colspan=\"3\">\n";  #Col. 1-2
            echo "                     <input type=\"file\" name=\"file1\" id=\"form_anadir0\" class=\"smallText\">\n";
	          echo "                  </td>\n";
	          #echo "                  <td align=\"left\"></td>\n";   #Col. 3 				 
	          echo "               </tr>\n";	
            echo "               <tr>\n";				
	          echo "                  <td align=\"center\" colspan=\"3\">\n";  #Col. 1-2		 										   		 
            echo "                     <input type=\"hidden\" name=\"accion\" value=\"base_legal\">\n";	  		 
	          echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Subir\">\n";
	          echo "                  </td>\n";			 
	          echo "               </tr>\n";
	          echo "            </table>\n";				 				 			 								   		 		  		 
	          echo "         </td>\n";
	          echo "         <td> &nbsp</td>\n";   #Col. 3 					 
            echo "      </tr>\n";
	          echo "      </form>\n";
				    if ($error) {
               echo "      <tr height=\"30px\">\n";			
	             echo "         <td> &nbsp</td>\n";   #Col. 1 	
	             echo "         <td align=\"left\">\n";  #Col. 2 
               echo "            <font color=\"red\"> $mensaje_de_error</font>\n";
	             echo "         </td>\n";
	             echo "         <td> &nbsp</td>\n";   #Col. 3 							 
               echo "      </tr>\n";	
			      }
			   } else {  # BORRAR
				    # Fila 5
            echo "      <tr height=\"30px\">\n";			
	          echo "         <td> &nbsp</td>\n";   #Col. 1 	
	          echo "         <td align=\"left\">\n";  #Col. 2 	
            echo "            Seleccione el documento y pulse el botón para borrar\n";															   		 
            echo "            <input type=\"hidden\" name=\"accion\" value=\"base_legal\">\n";	  		 
	          echo "            <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Borrar\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";
            echo "            <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"No Borrar\">\n";
	          echo "         </td>\n";	
	          echo "         <td> &nbsp</td>\n";   #Col. 3	
            echo "      </tr>\n";
	          echo "      </form>\n";																 				 
				 }		 							
	    }		 
	 }
	 	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
	 echo "   <br />&nbsp;<br />\n";
	 echo "</td>\n";
?>
