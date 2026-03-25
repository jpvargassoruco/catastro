<?php
   
	 $error1 = $aviso1 = false;
   ########################################
   #-------- SUBMIT COPIAR DATOS ---------#
   ########################################	
	 if ((isset($_POST['submit'])) AND ($_POST['submit'] == "Copiar Datos")) {
      $accion = "Mandar Codigo";
   }
   ########################################
   #----------- SUBMIT COPIAR ------------#
   ########################################	
	 if ((isset($_POST['submit'])) AND ($_POST['submit'] == "Copiar")) {
	    $cod_uv_copia = (int) trim($_POST["cod_uv"]); 
      $cod_man_copia = (int) trim($_POST["cod_man"]);
      $cod_pred_copia = (int) trim($_POST["cod_pred"]);
	    $error1 = true;	 
			$cod_cat_check = get_codcat($cod_uv_copia,$cod_man_copia,$cod_pred_copia,0,0,0);	
			if ($cod_cat_check == $cod_cat) {
	       $mensaje_de_error1 = "Error: No se puede copiar los datos al mismo $predio!";				 
	    } elseif (($cod_uv == "") OR (!check_int($cod_uv_copia))) {
	       #$cod_uv = trim($_POST["cod_uv"]); $cod_man = trim($_POST["cod_man"]);$cod_lote = trim($_POST["cod_lote"]);$cod_subl = trim($_POST["cod_subl"]);	 
	       $mensaje_de_error1 = "Error: El valor para la Unidad Vecinal (U.V.) tiene que ser un n�mero!";
	    } elseif (($cod_man == "") OR (!check_int($cod_man_copia))) {
	 	     #$cod_man = trim($_POST["cod_man"]);$cod_lote = trim($_POST["cod_lote"]);$cod_subl = trim($_POST["cod_subl"]);
	       $mensaje_de_error1 = "Error: El valor para el Manzano tiene que ser un n�mero!";
	    } elseif (($cod_pred == "") OR (!check_int($cod_pred_copia))) {	
	       #$cod_lote = trim($_POST["cod_lote"]);$cod_subl = trim($_POST["cod_subl"]);
			   $mensaje_de_error1 = "Error: El valor para el Predio tiene que ser un n�mero!";		 
	    } else {
	      # $cod_cat = get_codcat($cod_uv,$cod_man,$cod_lote,$cod_subl);	
			   #$sql="SELECT cod_uv FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'";
        ## $check = pg_num_rows(pg_query($sql));
			  # #if ($check > 0) {
			  #    $mensaje_de_error1 = "Error: Ya existe un lote con ese c�digo en la base de datos! Tiene que borrar ";
			 #  } else $error1 = false;		
	       $cod_cat_new = get_codcat($cod_uv,$cod_man,$cod_pred,0,0,0);
			   #if ($cod_cat_new != $cod_cat) {
				 #$cambio_de_codigo = true;
			   $sql="SELECT cod_uv FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_copia' AND cod_man = '$cod_man_copia' AND cod_pred = '$cod_pred_copia'";
         $check_info_predio = pg_num_rows(pg_query($sql));
				 $delete_file = false;
			   if ($check_info_predio > 0) {
			      $sql="SELECT dir_tipo FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
            $result_check = pg_query($sql);
            $info = pg_fetch_array($result_check, null, PGSQL_ASSOC);
            $dir_tipo_check = trim($info['dir_tipo']);
          #  $tit_1pat_check = trim($info['tit_1pat']);										
            pg_free_result($result_check);					 
					  if ($dir_tipo_check == "") {
#echo "AKI<br />";						
					     $error1 = false;
							 $delete_file = true;
			      } else { 
						   $mensaje_de_error1 = "Error: Ya existe un predio con el c�digo $cod_cat_new con datos! Tiene que borrar primero los datos de ese predio.";   
						} 
				 } else $error1 = false;
      }	    
			if ($error1) {
         $accion = "Mandar Codigo";
		} else {
			$accion = "Copiar Datos";
			### ELIMINAR SI EXISTE (PERO SIN DATOS) ### 				 
			if ($delete_file) {
				$sql = "DELETE FROM info_predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_copia' AND cod_man = '$cod_man_copia' AND cod_pred = '$cod_pred'";
				echo "$sql<br />";
				#	    pg_query($sql);
				$sql = "DELETE FROM codigos WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_copia' AND cod_man = '$cod_man_copia' AND cod_pred = '$cod_pred_copia'";
				echo "$sql<br />";
				#	    pg_query($sql);										 
			}
			$aviso1 = true;
			$mensaje_de_aviso1 = "Los datos del predio han sido copiados con �xito!";
		}
	}	 
	
	echo "<table border=\"0\" width=\"800px\">\n";            
	echo "<tr>\n";
	### MODIFICAR CODIGO  	 
	echo "<td align=\"center\" valign=\"top\" width=\"34%\">\n"; 	
	echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=8&id=$session_id\" accept-charset=\"utf-8\">\n";
	echo "<fieldset><legend>Modificar Codificación</legend>\n";	
		echo "<table border=\"0\" width=\"100%\">\n"; 	 	 
		echo "<tr>\n";  	 
		echo "<td align=\"center\" width=\"33%\">\n";
		if (($nivel == 1) OR ($nivel == 3)) {
			echo "No tiene el nivel de usuario para modificar la codificación. \n";
			echo "<br /><br /> &nbsp\n";							
		} else {
			echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";	 		  	
			echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Modificar Código\">\n";
			echo "<br /><br /> Usa esa opción para re-codificar el lote.  \n";			
			echo "<br> Nota. Todos los datos adjuntos (Geometría, Edificaciones, Fotos, Impuestos, etc.) cambiran de código. \n";			
		}
		echo "</td>\n";	 
		echo "</tr>\n";	
		echo "</table>\n"; 		  
	echo "</fieldset>\n";
	echo "</td>\n";		 	  
	echo "</form>\n";	
	### COPIAR DATOS	   
	echo "<td align=\"center\" valign=\"top\" width=\"33%\">\n";   #Col. 1	
	echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id#tab-6\" accept-charset=\"utf-8\">\n";
	echo "<fieldset><legend>Copiar Datos</legend>\n";	
	if ($accion == "Mandar Codigo") { 
		echo "<table border=\"0\" width=\"100%\" bgcolor=\"#c6dbf1\">\n"; 
	} else {
		echo "<table border=\"0\" width=\"100%\">\n"; 	 
	}
	echo "<tr>\n";  	 
	echo "<td align=\"center\" width=\"33%\">\n";   #Col. 1	
	if ($nivel == 1) {
	    echo "No tiene el nivel de usuario para copiar los datos a otro lote. \n";
	    echo "<br /><br /> &nbsp\n";							
	} else {
		echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";	 					  	
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Copiar Datos\">\n";
		echo "<br /><br /> Usa esa opción para copiar los datos (solo en caso de división de predio).<br /><br />\n";
		echo "<br />\n";
	}
	echo "</td>\n";	 
	echo "</tr>\n";	
	echo "</table>\n"; 		  
	echo "</fieldset>\n";	  
	echo "</form>\n";
	echo "</td>\n";	  	     		  
	echo "</tr>\n";
	if ($aviso1) {
		echo "<tr>\n"; 	 
		echo "<td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
		echo "<font color=\"green\">$mensaje_de_aviso1</font> <br />\n";				 	    
		echo "</td>\n"; 
		echo "</tr>\n";
	}	 
	if ($accion == "Mandar Codigo") {
		##################################################
		#-- INGRESAR CODIGO DEL LOTE A DONDE SE COPIA ---#
		##################################################
		echo "<tr>\n"; 	 
		echo "<td valign=\"top\" height=\"40\" colspan=\"3\">\n";  
		echo "<fieldset><legend>Copiar datos a otro predio</legend>\n";
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id#tab-6\" accept-charset=\"utf-8\">\n";	 
		echo "<table border=\"0\" width=\"100%\">\n";                    
		echo "<tr>\n";
		echo "<td align=\"left\" colspan=\"12\" class=\"bodyText\"> &nbsp Por favor, ingrese el código del predio:</td>\n";  	 
		echo "</tr>\n";	   
		echo "<tr>\n";  	                     
		echo "<td width=\"1%\">&nbsp </td>\n"; 
		echo "<td align=\"center\" width=\"18%\" class=\"bodyTextH\">&nbsp Unidad Vecinal (U.V.) &nbsp</td>\n";       	  	 
		echo "<td align=\"left\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_uv\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_uv\" value=\"$cod_uv\"></td>\n";   #Col. 3	 
		echo "<td width=\"1%\">&nbsp </td>\n"; 	   
		echo "<td align=\"center\" width=\"18%\" class=\"bodyTextH\">&nbsp No. de Manzano &nbsp</td>\n";   
		echo "<td align=\"left\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_man\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_man\" value=\"$cod_man\"></td>\n";   #Col. 6	
		echo "<td width=\"1%\">&nbsp </td>\n";  	 
		echo "<td align=\"center\" width=\"18%\" class=\"bodyTextH\">&nbsp No. de Predio &nbsp</td>\n"; 
		echo "<td align=\"left\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_pred\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_pred\" value=\"$cod_pred\"></td>\n";   #Col. 9
		echo "<td width=\"1%\">&nbsp </td>\n"; 	   	 
		echo "<td align=\"center\" width=\"23%\" class=\"bodyTextD\">&nbsp </td>\n";	 
		echo "<td width=\"1%\">&nbsp </td>\n"; 	 	 	   	 	 	    
		echo "</tr>\n";

		echo "<tr>\n"; 	 
		echo "<td align=\"center\" height=\"20\" colspan=\"12\">\n";		 
		echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";								
		echo "<input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";		
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Copiar\">\n";
		echo "</td>\n"; 
		echo "</tr>\n";
		echo "</table>\n"; 
		echo "</form>\n";	 
		echo "</fieldset>\n";	 	 
		echo "</td>\n"; 
		echo "</tr>\n";
		if ($error1) {
			echo "<tr>\n"; 	 
			echo "<td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
			echo "<font color=\"red\">$mensaje_de_error1</font> <br />\n";				 	    
			echo "</td>\n"; 
			echo "</tr>\n";
		}
		if ($aviso1) {
			echo "<tr>\n"; 	 
			echo "<td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
			echo "<font color=\"red\">$mensaje_de_aviso1</font> <br />\n";				 	    
			echo "</td>\n"; 
			echo "</tr>\n";
		}	
	}
	echo "</table>\n"; 	 	 
?>