<?php
   
	 $error1 = false;
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
      $cod_lote_copia = (int) trim($_POST["cod_lote"]);
     # $cod_subl_copia = (int) trim($_POST["cod_subl"]);		
	    $error1 = true;	 
			$cod_cat_check = get_codcat($cod_uv_copia,$cod_man_copia,$cod_lote_copia,$cod_subl_copia);	
			if ($cod_cat_check == $cod_cat) {
	       $mensaje_de_error1 = "Error: No se puede copiar los datos al mismo $predio!";				 
	    } elseif (($cod_uv == "") OR (!check_int($cod_uv_copia))) {
#$cod_uv = trim($_POST["cod_uv"]); $cod_man = trim($_POST["cod_man"]);$cod_lote = trim($_POST["cod_lote"]);$cod_subl = trim($_POST["cod_subl"]);	 
	       $mensaje_de_error1 = "Error: El valor para la Unidad Vecinal (U.V.) tiene que ser un número!";
	    } elseif (($cod_man == "") OR (!check_int($cod_man_copia))) {
	 	     #$cod_man = trim($_POST["cod_man"]);$cod_lote = trim($_POST["cod_lote"]);$cod_subl = trim($_POST["cod_subl"]);
	       $mensaje_de_error1 = "Error: El valor para el Manzano tiene que ser un número!";
	    } elseif (($cod_lote == "") OR (!check_int($cod_lote_copia))) {	
	       #$cod_lote = trim($_POST["cod_lote"]);$cod_subl = trim($_POST["cod_subl"]);
			   $mensaje_de_error1 = "Error: El valor para el Lote tiene que ser un número!";
	    } elseif (($cod_subl == "") OR (!check_int($cod_subl_copia))) {	
	       #$cod_subl = trim($_POST["cod_subl"]);
			   $mensaje_de_error1 = "Error: El valor para el Sub-Lote tiene que ser un número!";			 
	    } else {
	      # $cod_cat = get_codcat($cod_uv,$cod_man,$cod_lote,$cod_subl);	
			   #$sql="SELECT cod_uv FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'";
        ## $check = pg_num_rows(pg_query($sql));
			  # #if ($check > 0) {
			  #    $mensaje_de_error1 = "Error: Ya existe un lote con ese código en la base de datos! Tiene que borrar ";
			 #  } else $error1 = false;		
	       $cod_cat_new = get_codcat($cod_uv,$cod_man,$cod_lote,$cod_subl);
			   #if ($cod_cat_new != $cod_cat) {
				 #$cambio_de_codigo = true;
			   #$sql="SELECT cod_uv FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_copia' AND cod_man = '$cod_man_copia' AND cod_lote = '$cod_lote_copia' AND cod_subl = '$cod_subl_copia'";
				 $sql="SELECT cod_uv FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_copia' AND cod_man = '$cod_man_copia' AND cod_pred = '$cod_lote_copia'";
         $check_info_predio = pg_num_rows(pg_query($sql));
				 $delete_file = false;
			   if ($check_info_predio > 0) {
echo "AKIIII???<br />";					 
			      #$sql="SELECT dir_tipo, tit_1pat FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'";
			      $sql="SELECT dir_tipo, tit_1pat FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_lote'";
            $result_check = pg_query($sql);
            $info = pg_fetch_array($result_check, null, PGSQL_ASSOC);
            $dir_tipo_check = trim($info['dir_tipo']);
            $tit_1pat_check = trim($info['tit_1pat']);										
            pg_free_result($result_check);					 
					  if (($dir_tipo_check == "") AND ($tit_1pat_check == "")) {
#echo "AKIIII???<br />";						
					     $error1 = false;
							 $delete_file = true;
			      } else { 
						   $mensaje_de_error1 = "Error: Ya existe un $predio con el código $cod_cat_new con datos! Tiene que borrar primero los datos de ese $predio.";   
						} 
				 } else $error1 = false;
      }	    
			if ($error1) {
         $accion = "Mandar Codigo";
		  } else {
			   $accion = "Copiar Datos";
         ### ELIMINAR SI EXISTE (PERO SIN DATOS) ### 				 
				 if ($delete_file) {
				    $sql = "DELETE FROM info_predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_copia' AND cod_man = '$cod_man_copia' AND cod_lote = '$cod_lote_copia' AND cod_subl = '$cod_subl_copia'";
echo "$sql<br />";
				    pg_query($sql);
						$sql = "DELETE FROM codigos WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_copia' AND cod_man = '$cod_man_copia' AND cod_lote = '$cod_lote_copia' AND cod_subl = '$cod_subl_copia'";
echo "$sql<br />";
				    pg_query($sql);										 
				 }
         ### COPIANDO INFO_PREDIO ### 
				 pg_query("INSERT INTO info_predio SELECT '99-99-99-99', cod_uv, cod_man, cod_lote, cod_subl, cod_pad,
			      dir_tipo, dir_nom, dir_num, dir_edif, dir_bloq, dir_piso, dir_apto, tit_pers, tit_cant, tit_bene, 
				    tit_1pat, tit_1mat, tit_1nom1, tit_1nom2, tit_1ci, tit_1nit,
				    tit_2pat, tit_2mat, tit_2nom1, tit_2nom2, tit_2ci, tit_2nit, tit_cara,
				    dom_dpto, dom_ciu, dom_dir, der_num, der_fech, ter_sdoc, adq_modo, adq_doc, adq_fech, adq_mont,'0','NIN','0','0',	
				    via_tipo, via_clas, via_uso, via_mat,
            ser_alc, ser_agu,	ser_luz, ser_tel, ser_gas,	ser_cab,
				    ter_form, ter_ubi, ter_fren, ter_fond, ter_nofr, ter_san, ter_topo, ter_mur, 
						cnx_alc, cnx_agu,	cnx_luz, cnx_tel, cnx_gas, cnx_cab, ter_eesp,
				    esp_aac, esp_tas, esp_tae, esp_ser, esp_gar, esp_dep, mej_lav, mej_par, mej_hor, mej_pis, mej_otr,
				    ter_uso, ter_ace, ctr_x, ctr_y, ctr_enc, ctr_sup, ctr_fech, ctr_obs	
				    FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'");
				    $sql = "UPDATE info_predio SET cod_geo = '$cod_geo', cod_uv = '$cod_uv_copia', cod_man = '$cod_man_copia', cod_lote = '$cod_lote_copia', 
				           cod_subl = '$cod_subl_copia' WHERE cod_geo = '99-99-99-99'";
echo "$sql<br />";									 
						pg_query($sql);
				 ### HABILITAR EN CODIGOS ###
				 pg_query("INSERT INTO codigos (cod_geo, cod_uv, cod_man, cod_lote, cod_subl, activo) VALUES ('$cod_geo','$cod_uv_copia','$cod_man_copia','$cod_lote_copia','$cod_subl_copia','1')");
			}
   }	 
   #############################################################################
   #-------------------------------- FORMULARIO -------------------------------#
   #############################################################################	
	 echo "            <table border=\"0\" width=\"800px\">\n";                     #TABLE 2 COLUMNAS
	 echo "               <tr>\n";  	 
	 ### VER HISTORIAL ###
   echo "                  <td align=\"center\" valign=\"top\" width=\"50%\">\n";   #Col. 1	
	 echo "			             <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id#tab-9\" accept-charset=\"utf-8\">\n";
	 echo "                     <fieldset><legend>Historial</legend>\n";	
	 echo "                     <table border=\"0\" width=\"100%\">\n"; 	 	 
	 echo "                        <tr>\n";  	 
   echo "                           <td align=\"center\" width=\"33%\">\n";   #Col. 1	
	# echo "                              MODIFICAR DATOS<br /><br />\n";			
   if ($nivel == 1) {
	    echo "                            No tiene el nivel de usuario para modificar los datos. \n";
	    echo "                            <br /><br /> &nbsp\n";							
 	 } else {
	    echo "                           <input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";	 		  	
      echo "                           <input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Ver Historial\">\n";
	    echo "                           <br /><br /> Ver el historial del Inmueble.<br /><br /> \n";			
	 }
   echo "                           </td>\n";	 
   echo "                        </tr>\n";	
	 echo "                     </table>\n"; 		  
	 echo "                     </fieldset>\n";	  
	 echo "                  </form>\n";
	 echo "                  </td>\n";
	 ### REALIZAR TRANSFERENCIA ###	 	  
   echo "                  <td align=\"center\" valign=\"top\" width=\"50%\">\n";   #Col. 1	
	 echo "			             <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=67&id=$session_id\" accept-charset=\"utf-8\">\n";
	 echo "                     <fieldset><legend>Realizar Transferencia</legend>\n";	
	 if ($accion == "Mandar Codigo") { 
	    echo "                     <table border=\"0\" width=\"100%\" bgcolor=\"#c6dbf1\">\n"; 
	 } else {
	    echo "                     <table border=\"0\" width=\"100%\">\n"; 	 
	 }		 	 
	 echo "                        <tr>\n";  	 
   echo "                           <td align=\"center\" width=\"33%\">\n";   #Col. 1	
	# echo "                              COPIAR DATOS<br /><br />\n";			
   if ($nivel == 1) {
	    echo "                            No tiene el nivel de usuario para realizar una transferencia. \n";
	    echo "                            <br /><br /> &nbsp\n";							
 	 } else {
	    echo "                           <input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";	 					  	
      echo "                           <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Transferencia\">\n";
	    echo "                           <br /><br /> Opción para realizar una transferencia.<br /><br />\n";			
	 }
   echo "                           </td>\n";	 
   echo "                        </tr>\n";	
	 echo "                     </table>\n"; 		  
	 echo "                     </fieldset>\n";	  
	 echo "                  </form>\n";
	 echo "                  </td>\n";	  	     		  
	 echo "               </tr>\n";
	 if ($accion == "Mandar Codigo") {
	 
	 ##################################################
	 #-- INGRESAR CODIGO DEL LOTE A DONDE SE COPIA ---#
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Copiar datos a otro lote</legend>\n";
	 echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id#tab-7\" accept-charset=\"utf-8\">\n";	 
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  13 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"13\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"18%\" class=\"bodyTextH\">&nbsp Unidad Vecinal (U.V.) &nbsp</td>\n";   #Col. 2	    	  	 
	 echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_uv\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_uv\" value=\"$cod_uv\"></td>\n";   #Col. 3	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 4		   
	 echo "                  <td align=\"center\" width=\"18%\" class=\"bodyTextH\">&nbsp No. de Manzano &nbsp</td>\n";   #Col. 5	  
	 echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_man\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_man\" value=\"$cod_man\"></td>\n";   #Col. 6	
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 7	   	 
	 echo "                  <td align=\"center\" width=\"18%\" class=\"bodyTextH\">&nbsp No. de Lote &nbsp</td>\n";   #Col. 8 
	 echo "                  <td align=\"left\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_lote\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_lote\" value=\"$cod_lote\"></td>\n";   #Col. 9
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 10		   	 
	 echo "                  <td align=\"center\" width=\"17%\" class=\"bodyTextH\">&nbsp No. de Sub-Lote &nbsp</td>\n";   #Col. 11 
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_subl\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_subl\" value=\"$cod_subl\"></td>\n";	 #Col. 12  
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 13  	 	 	   	 	 	    
	 echo "               </tr>\n";
	 
	    echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"13\">\n";   #Col. 1+2+3 		 
	    echo "         <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";	
	    echo "         <input name=\"cod_pad_ant\" type=\"hidden\" value=\"$cod_pad\">\n";								
	    echo "         <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";		
	    echo "         <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Copiar\">\n";
	    echo "         </td>\n"; 
	    echo "      </tr>\n";		 
	 
	 echo "            </table>\n"; 
	 echo "            </form>\n";	 
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
   #if ($accion == "Modificar") {
		 		 
	 #} 




 #  echo "      <tr>\n";
 #  echo "         <td valign=\"top\" colspan=\"3\">\n";   #Col. 1 
# echo "          <a href='javascript:history.back()'>\n";		
# echo "           <img border='0' src='http://$server/siicat_concep/graphics/boton_atras.png' width='35' height='35'></a>\n";
#   echo "            BAAAAAAAAAAAAAAAAAAH\n";	
 #  echo "         </td>\n";	 
 #  echo "      </tr>\n";	

	 }
	 echo "            </table>\n"; 	 	 
?>