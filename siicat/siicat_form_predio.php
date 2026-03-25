<?php
	 if ($accion == "Modificar") {
      $form = "027";
	 } else $form = "019";
	 $disabled = "";
	 
	 $ter_smen = $ben_zona = "[-]";
	 $ctr_enc = $ctr_sup = $ctr_fech = $ctr_obs = "";
	 ##################################################
	 #-------------- MAS QUE 2 TITULARES -------------#
	 ##################################################
	 if (isset($_GET['tit'])) {
	    $tit = $_GET['tit'];
	 } else {
	    if (isset($_POST['id_titx0'])) {
			   $id_titx[0] = $_POST['id_titx0'];
			   if (isset($_POST['id_titx1'])) {
			      $id_titx[1] = $_POST['id_titx1'];				 
			      if (isset($_POST['id_titx2'])) {
							 $id_titx[2] = $_POST['id_titx2'];
			         if (isset($_POST['id_titx3'])) {
							 	  $id_titx[3] = $_POST['id_titx3'];
			            if (isset($_POST['id_titx4'])) {
										 $id_titx[4] = $_POST['id_titx4'];
			               if (isset($_POST['id_titx5'])) {
										    $id_titx[5] = $_POST['id_titx5'];										 
			                  if (isset($_POST['id_titx6'])) {
										       $id_titx[6] = $_POST['id_titx6'];												
			                     if (isset($_POST['id_titx7'])) {
													 		$id_titx[7] = $_POST['id_titx7'];
			                        if (isset($_POST['id_titx8'])) {
																 $id_titx[8] = $_POST['id_titx8'];
			                           if (isset($_POST['id_titx9'])) {
																 		$id_titx[9] = $_POST['id_titx9'];
			                              if (isset($_POST['id_titx10'])) {
																			 $id_titx[10] = $_POST['id_titx10'];
			                                 if (isset($_POST['id_titx11'])) {
																			 		$id_titx[11] = $_POST['id_titx11'];
			                                    if (isset($_POST['id_titx12'])) {
																						 $id_titx[12] = $_POST['id_titx12'];
                                             $tit = 13;					 
				                                  } else $tit = 12;				 
				                               } else $tit = 11;				 
				                            } else $tit = 10;				 
				                         } else $tit = 9;					 
				                      } else $tit = 8;						 
				                   } else $tit = 7;				 
				                } else $tit = 6;					 
				             } else $tit = 5;				 
				          } else $tit = 4;				 
				       } else $tit = 3;				 
				    } else $tit = 2;				 
				 } else $tit = 1;
			} else $tit = 0;
	 }  
	 ##################################################
	 #------------ LISTA DE CONTRIBUYENTES -----------#
	 ##################################################
include "siicat_lista_contribuyentes.php";
			
	 #############################################################################
	 #------------------------------ FORMULARIO ---------------------------------#
	 #############################################################################			
	 # Fila 1
#   echo "<td>\n";
   echo "  <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";	 
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"15%\">\n";   #Col. 1
	 if ($accion == "Modificar") {		 
      echo "            &nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&id=$session_id#tab-6\">\n";		
      echo "            <img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";	
	 } else {
      echo "            &nbsp \n";	    
	 }	
   echo "         </td>\n";	 	  	    
   echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n"; 
	# echo "            $accion Datos del $predio (Form. $form)\n"; 
	 echo "            $accion Datos\n";	                         
   echo "         </td>\n";
	 echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";
   echo "			 <form name=\"form1\" method=\"post\" action=\"index.php?mod=7&id=$session_id\" accept-charset=\"utf-8\">\n";			 
	 ##################################################
	 #--------------- CAMPO PARA CODIGO --------------#
	 ##################################################
	 if ($accion == "Añadir") {	 
	    echo "      <tr>\n"; 	 
	    echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	    echo "         <fieldset><legend>Códificación del Inmueble</legend>\n";
	    echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  13 Columnas   
      echo "               <tr>\n";
      echo "                  <td align=\"right\" colspan=\"14\" class=\"bodyText\"></td>\n";   #Col. 1	 
      echo "               </tr>\n";	   
      echo "               <tr>\n";  	                     
      echo "                  <td width=\"2%\"></td>\n";   #Col. 1	
      echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">Dist.</td>\n";   #Col. 2	    	  	 
      echo "                  <td align=\"left\" width=\"8%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_uv_nuevo\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_uv\" value=\"$cod_uv_nuevo\" $disabled></td>\n";	   
      echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">Mz.</td>\n";   #Col. 4	  
      echo "                  <td align=\"left\" width=\"8%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_man_nuevo\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_man\" value=\"$cod_man_nuevo\" $disabled></td>\n";  	 
      echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">Predio</td>\n";   #Col. 6 
      echo "                  <td align=\"left\" width=\"8%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_pred_nuevo\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_pred\" value=\"$cod_pred_nuevo\" $disabled></td>\n";   	 
      echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextH\">Bloque</td>\n";   #Col. 8 
      echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_blq_nuevo\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_blq\" value=\"$cod_blq_nuevo\" $disabled></td>\n";   	 
      echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextH\">Piso</td>\n";   #Col. 10 
      echo "                  <td align=\"left\" width=\"8%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_piso_nuevo\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_piso\" value=\"$cod_piso_nuevo\" $disabled></td>\n";	   	 
      echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextH\">Apto.</td>\n";   #Col. 12 
      echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_apto_nuevo\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_apto\" value=\"$cod_apto_nuevo\" $disabled></td>\n"; 
      echo "                  <td width=\"2%\"></td>\n";   #Col. 14  	 	 	   	 	 	    
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
      if ($accion == "Modificar") {
	       echo "      <tr>\n"; 	 
	       echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3 		 
	       echo "         <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";	
	       echo "         <input name=\"cod_pad_ant\" type=\"hidden\" value=\"$cod_pad\">\n";								
	       echo "         <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";		
	       echo "         <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Modificar Código\">\n";
	       echo "         </td>\n"; 
	       echo "      </tr>\n";			 		 
	     }
   } 	
	 ##################################################
	 #------------------- DIRECCION ------------------#
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Dirección del Inmueble</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  21 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"15\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"4%\" class=\"bodyTextH\">Tipo</td>\n";   #Col. 2	    	  	 
	 echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextD\">\n";   #Col. 3	  
	 $valores = get_abr('dir_tipo');	 
   echo "                     <select class=\"navText\" name=\"dir_tipo\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $dir_tipo) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";   
	 echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextH\">Nombre</td>\n";   #Col. 4	
	 $dir_nom_texto = textconvert($dir_nom); 
	 echo "                  <td align=\"center\" width=\"25%\" class=\"bodyTextD\"><input type=\"text\" name=\"dir_nom\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_nom\" value=\"$dir_nom_texto\"></td>\n";   #Col. 5	  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 6	
	 echo "                  <td align=\"center\" width=\"3%\" class=\"bodyTextH\">Nº</td>\n";   #Col. 7 
	 echo "                  <td align=\"center\" width=\"5%\" class=\"bodyTextD\"><input type=\"text\" name=\"dir_num\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_num\" value=\"$dir_num\"></td>\n";   #Col. 8  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 9	
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">Edificio</td>\n";   #Col. 10 
	 $dir_edif_texto = textconvert($dir_edif);
	 echo "                  <td align=\"center\" width=\"14%\" class=\"bodyTextD\"><input type=\"text\" name=\"dir_edif\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_edif\" value=\"$dir_edif_texto\"></td>\n";	 #Col. 11  
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 12	
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">Condominio</td>\n";   #Col. 13 
	 $dir_cond_texto = textconvert($dir_cond);
	 echo "                  <td align=\"center\" width=\"14%\" class=\"bodyTextD\"><input type=\"text\" name=\"dir_cond\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_cond\" value=\"$dir_cond_texto\"></td>\n";	 #Col. 14  
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 15  	 	 	   	 	 	    
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
	 echo "         <fieldset><legend>Identificación del Titular</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  10 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"10\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextH\">&nbsp Personería &nbsp</td>\n";   #Col. 2	    	  	 
	 echo "                  <td align=\"center\" width=\"23%\" class=\"bodyTextD\">\n";   #Col. 3	  
	 $valores = get_abr('tit_pers');	 
   echo "                     <select class=\"navText\" name=\"tit_pers\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $tit_pers) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";   
	 echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">Cant. de Titulares</td>\n";   #Col. 4	  
	 echo "                  <td align=\"left\" width=\"4%\" class=\"bodyTextD\"><input type=\"text\" name=\"tit_cant\" id=\"form_anadir2\" class=\"navText\" maxlength=\"2\" value=\"$tit_cant\"></td>\n";   #Col. 5	  	 
	 echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">Cant. de Beneficiarios</td>\n";   #Col. 6 
	 echo "                  <td align=\"left\" width=\"4%\" class=\"bodyTextD\"><input type=\"text\" name=\"tit_bene\" id=\"form_anadir2\" class=\"navText\" maxlength=\"2\" value=\"$tit_bene\"></td>\n";   #Col. 7  	 
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextH\">Titularidad</td>\n";   #Col. 8	    	  	 
	 echo "                  <td align=\"center\" width=\"20%\" class=\"bodyTextD\">\n";   #Col. 9	  
	 $valores = get_abr('tit_cara');	 
   echo "                     <select class=\"navText\" name=\"tit_cara\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));	
      if ($valores[$i] == $tit_cara) {
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
	 echo "            </table>\n";  
	 ##################################################	 
	 echo "            <table border=\"0\" width=\"100%\">\n";   #TABLE  3 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" width=\"3%\" class=\"bodyTextD\">1 &nbsp</td>\n";   #Col. 1	 
	 echo "                  <td align=\"right\" width=\"42%\" class=\"bodyText\">\n";   #Col. 1	 	 
   echo "                     <select class=\"navText\" name=\"tit_1id\" size=\"1\">\n";
   if ((!isset($_POST['tit_1id'])) OR ($_POST['tit_1id'] == 0)) {	 
	    echo "                        <option id=\"form0\" value=\"0\" selected=\"selected\"> --- Seleccionar de la lista de Contribuyentes ---</option>\n";    
	 }
	 $i = 0;
	 while ($i < $no_de_contribuyentes) {
		  $value_temp = $id_contrib_lista[$i]; 	
			if ($value_temp == $tit_1id) {
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
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" class=\"bodyTextD\">2 &nbsp</td>\n";   #Col. 1		 
	 echo "                  <td align=\"right\" class=\"bodyText\">\n";   #Col. 1	 	 
   echo "                     <select class=\"navText\" name=\"tit_2id\" size=\"1\">\n";
   if ((!isset($_POST['tit_2id'])) OR ($_POST['tit_2id'] == 0)) {	 
	    echo "                        <option id=\"form0\" value=\"0\" selected=\"selected\"> --- Seleccionar de la lista de Contribuyentes ---</option>\n";    
	 }
	 $i = 0;
	 while ($i < $no_de_contribuyentes) {
		  $value_temp = $id_contrib_lista[$i]; 	
			if ($value_temp == $tit_2id) {
			   echo "                   <option id=\"form0\" value=\"$value_temp\" selected=\"selected\"> $contribuyente[$i]</option>\n";
		  } else {
			   echo "                   <option id=\"form0\" value=\"$value_temp\"> $contribuyente[$i]</option>\n";
	    }
	    $i++;
   } 	
   echo "                     </select>\n";	 
	 echo "                  </td>\n";
	 echo "                  <td align=\"left\"> &nbsp </td>\n"; 		     	   	 	   	 	 	    
	 echo "               </tr>\n";
	 $j = 0;
	 while ($j < $tit) {
	    $tit_text = $j + 3;
	    echo "               <tr>\n";
	    echo "                  <td align=\"right\" class=\"bodyTextD\">$tit_text &nbsp</td>\n";   #Col. 1		 
	    echo "                  <td align=\"right\" class=\"bodyText\">\n";   #Col. 1	 	 
      echo "                     <select class=\"navText\" name=\"id_titx$j\" size=\"1\">\n";
      if (!isset($_POST['id_titx$j'])) {	 
	       echo "                        <option id=\"form0\" value=\"0\" selected=\"selected\"> --- Seleccionar de la lista de Contribuyentes ---</option>\n";    
	    }
	    $i = 0;
	    while ($i < $no_de_contribuyentes) {
		     $value_temp = $id_contrib_lista[$i]; 	
			   if ($value_temp == $id_titx[$j]) {
			      echo "                   <option id=\"form0\" value=\"$value_temp\" selected=\"selected\"> $contribuyente[$i]</option>\n";
		     } else {
			      echo "                   <option id=\"form0\" value=\"$value_temp\"> $contribuyente[$i]</option>\n";
	       }
	       $i++;
      } 	
      echo "                     </select>\n";	 
	    echo "                  </td>\n";
	    echo "                  <td align=\"left\"> &nbsp </td>\n"; 		     	   	 	   	 	 	    
	    echo "               </tr>\n";	 
	    $j++;
	 }
	 $tit++;
	 echo "               <tr>\n";
	 if ($tit < 14) {
	    if ($accion == "Añadir") {
	       echo "                  <td align=\"left\" colspan=\"3\"><a href=\"index.php?mod=3&inmu=$id_inmu&tit=$tit&id=$session_id\">Añadir Titular</a></td>\n";   #Col. 1		
	    } else {
	       echo "                  <td align=\"left\" colspan=\"3\"><a href=\"index.php?mod=6&inmu=$id_inmu&tit=$tit&id=$session_id\">Añadir Titular</a></td>\n";   #Col. 1				
			}
	 }
	 echo "               </tr>\n";	  	 
	 echo "            </table>\n";  
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "      </tr>\n";
	 if ($error3) {
      echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\" class=\"bodyTextD\">\n";   #Col. 1+2+3  	 			 
	    echo "         <font color=\"red\">$mensaje_de_error3</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "      </tr>\n";
	 }	 
	 ##################################################
	 #                  ADQUISICION                   #
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Adquisición del Inmueble</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  9 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"9\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">&nbsp Modo de adquisición &nbsp</td>\n";   #Col. 2	    	  	 
	 echo "                  <td align=\"center\" width=\"21%\" class=\"bodyTextD\">\n";   #Col. 3	  
	 $valores = get_abr('adq_modo');	 
   echo "                     <select class=\"navText\" name=\"adq_modo\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $adq_modo) {
	       echo "                   <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                   <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";   
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextH\">Documento</td>\n";   #Col. 4	 
	 $adq_doc_texto = utf8_decode($adq_doc);	  
	 echo "                  <td align=\"center\" width=\"37%\" class=\"bodyTextD\"><input type=\"text\" name=\"adq_doc\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_adq_doc\" value=\"$adq_doc_texto\"></td>\n";   #Col. 5	
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 6	   	 
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">Fecha</td>\n";   #Col. 7 
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextD\"><input type=\"text\" name=\"adq_fech\" id=\"form_anadir1\" class=\"navText\" maxlength=\"10\" value=\"$adq_fech\"></td>\n";   #Col. 8  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 9	 	 	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "      </tr>\n"; 
	 if ($error5) {
      echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	    echo "         <font color=\"red\">$mensaje_de_error5</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "      </tr>\n";
	 }		
	 ##################################################
	 #---------------- DERECHOS REALES ---------------#
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Inscripción en Derechos Reales</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  7 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"7\" class=\"bodyText\"></td>\n";   #Col. 1-7	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"30%\" class=\"bodyTextH\">&nbsp Número de Inscripción en Derechos Reales &nbsp</td>\n";   #Col. 2	    	  	    
	 echo "                  <td align=\"center\" width=\"52%\" class=\"bodyTextD\"><input type=\"text\" name=\"der_num\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_der_num\" value=\"$der_num\"></td>\n";   #Col. 3	
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 4	   	 
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">Fecha</td>\n";   #Col. 5 
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextD\"><input type=\"text\" name=\"der_fech\" id=\"form_anadir1\" class=\"navText\" maxlength=\"10\" value=\"$der_fech\"></td>\n";   #Col. 6  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 7	 	 	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "      </tr>\n"; 
	 if ($error6) {
      echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	    echo "         <font color=\"red\">$mensaje_de_error6</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "      </tr>\n";
	 }
	 ##################################################
	 #-------------- DATOS DEL TERRENO ---------------#
	 ##################################################	     
	# echo "      <tr>\n";  
	# echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	# echo "         <td valign=\"top\" height=\"180\" colspan=\"3\" class=\"bodyText\">\n";  #Col. 1+2+3	   
   echo "      <tr>\n"; 	 
	 echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 
	 echo "            <table border=\"0\" width=\"800px\">\n";   # 8 Columnas
	 echo "               <tr>\n"; 	 
	 echo "                  <td valign=\"top\" height=\"40\" width=\"50%\">\n";   #Col. 1+2+3  
	 echo "                     <fieldset><legend>Datos del Predio</legend>\n";
	 echo "                     <table border=\"0\" width=\"100%\">\n"; #TABLE  2 Columnas	 
 	 echo "                        <tr>\n";
	 echo "                           <td align=\"center\" colspan=\"2\">Superficie</td>\n";
#	 echo "                           <td> &nbsp</td>\n";
	 echo "                           <td align=\"center\" colspan=\"2\">Servicios Públicos</td>\n";
#	 echo "                           <td> &nbsp</td>\n";		 	
	 echo "                        </tr>\n";
 	 echo "                        <tr>\n";
 	 echo "                           <td align=\"left\" width=\"25%\" class=\"bodyTextH_Small\">&nbsp Superf. s/mens:</td>\n";	 	
	 echo "                           <td align=\"left\" width=\"30%\" class=\"bodyTextD_Small\">&nbsp $ter_smen m²</td>\n";	
 	 echo "                           <td align=\"left\" width=\"25%\" class=\"bodyTextH_Small\">&nbsp Agua:</td>\n";	 	
	 echo "                           <td align=\"left\" width=\"20%\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('ser_agu');	 
   echo "                              <select class=\"navText\" name=\"ser_agu\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $ser_agu) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";	 	                    
	 echo "                        </tr>\n";
 	 echo "                        <tr>\n";	 
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Superf. s/doc:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp&nbsp<input type=\"text\" name=\"ter_sdoc\" id=\"form_anadir2\" class=\"navText\" maxlength=\"8\" value=\"$ter_sdoc\"> m²</td>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Luz:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('ser_luz');	 
   echo "                              <select class=\"navText\" name=\"ser_luz\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $ser_luz) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";	 
 	 echo "                        </tr>\n";	 
	 echo "                        <tr>\n"; 
	 echo "                           <td align=\"center\" colspan=\"2\">Información sobre la vía</td>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Telefono:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('ser_tel');	 
   echo "                              <select class=\"navText\" name=\"ser_tel\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == "$ser_tel") {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";	 	
	 echo "                        </tr>\n";	
 	 echo "                        <tr>\n";	
	 echo "                           <td class=\"bodyTextH_Small\">&nbsp Material de vía:</td>\n"; 
	 echo "                           <td class=\"bodyTextD_Small\">&nbsp [-]\n";
/*	 $valores = get_abr ('via_mat');	 
   echo "                              <select class=\"navText\" name=\"via_mat\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == "$via_mat") {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";		*/ 
	 echo "                           </td>\n";
	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Alcantarillado:</td>\n";
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('ser_alc');	 
   echo "                              <select class=\"navText\" name=\"ser_alc\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $ser_alc) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";  
	 echo "                        </tr>\n"; 
	 echo "                        <tr>\n";
	 echo "                           <td align=\"center\" colspan=\"2\">&nbsp Topografía</td>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp TV Cable:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('ser_cab');	 
   echo "                              <select class=\"navText\" name=\"ser_cab\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $ser_cab) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";	  	
 	 echo "                        </tr>\n";
	 echo "                        <tr>\n";
	 echo "                           <td class=\"bodyTextH_Small\">&nbsp Topografía</td>\n";	 	 
	 echo "                           <td class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('ter_topo');	 
   echo "                              <select class=\"navText\" name=\"ter_topo\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $ter_topo) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";
	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Gas Domiciliario:</td>\n";	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('ser_gas');	 
   echo "                              <select class=\"navText\" name=\"ser_gas\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $ser_gas) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";	 
 	 echo "                        </tr>\n";	
	 echo "                        <tr>\n";
	 echo "                           <td class=\"bodyTextH_Small\">&nbsp Forma</td>\n";	 	 
	 echo "                           <td class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('ter_form');	 
   echo "                              <select class=\"navText\" name=\"ter_form\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $ter_form) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";
	 echo "                           <td align=\"center\" colspan=\"2\">Ubicación</td>\n";
 	 echo "                        </tr>\n";
	 echo "                        <tr>\n"; 
	 echo "                           <td align=\"center\" colspan=\"2\">Información adicional</td>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Ubicación:</td>\n";	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('ter_ubi');	 
   echo "                              <select class=\"navText\" name=\"ter_ubi\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $ter_ubi) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 	 
	 echo "                           </td>\n"; 		 	  	 	 
	 echo "                        </tr>\n"; 
	 echo "                        <tr>\n";		 
	 echo "                           <td class=\"bodyTextH_Small\">&nbsp Destino de Uso:</td>\n";	
	 echo "                           <td class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('ter_uso');	 
   echo "                           <select class=\"navText\" name=\"ter_uso\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $ter_uso) {
	       echo "                        <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                        <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                           </select>\n";	 	 
	 echo "                           </td>\n";	 
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp No. de Frentes:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";	
	 echo "                              &nbsp&nbsp<input type=\"text\" name=\"ter_nofr\" id=\"form_anadir4\" class=\"navText\" maxlength=\"1\" value=\"$ter_nofr\"></td>\n";
	 echo "                           </td>\n";	
	 echo "                           <td> &nbsp</td>\n";		 	  	 	 
	 echo "                        </tr>\n"; 
	 echo "                        <tr>\n";		 
	 echo "                           <td class=\"bodyTextH_Small\">&nbsp Edif. especiales:</td>\n";	 
	 echo "                           <td class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('ter_eesp');	 
   echo "                              <select class=\"navText\" name=\"ter_eesp\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $ter_eesp) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 	 
	 echo "                           </td>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Medida Frente:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 echo "                              &nbsp&nbsp<input type=\"text\" name=\"ter_fren\" id=\"form_anadir2\" class=\"navText\" maxlength=\"7\" value=\"$ter_fren_texto\"> m\n";
	 echo "                           </td>\n";	 	 	  	 	 
	 echo "                        </tr>\n";  	
	 echo "                        <tr>\n";		 
	 echo "                           <td class=\"bodyTextH_Small\">&nbsp Inst. Sanitaria:</td>\n"; 	 	 
	 echo "                           <td class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('ter_san');	 
   echo "                              <select class=\"navText\" name=\"ter_san\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $ter_san) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Medida Fondo:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n"; 
	 echo "                              &nbsp&nbsp<input type=\"text\" name=\"ter_fond\" id=\"form_anadir2\" class=\"navText\" maxlength=\"7\" value=\"$ter_fond_texto\"> m\n";
	 echo "                           </td>\n";	 	  	 	 
	 echo "                        </tr>\n";
	 echo "                        <tr>\n";		 
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Muro perimetral:</td>\n";
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('ter_mur');	 
   echo "                           <select class=\"navText\" name=\"ter_mur\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $ter_mur) {
	       echo "                        <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                        <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                           </select>\n";	 
	 echo "                           </td>\n";
	 echo "                           <td align=\"center\" colspan=\"2\">&nbsp </td>\n";	 	 
	 echo "                        </tr>\n";	 		  	 	   	 	 
	 echo "                     </table>\n";
	 echo "                     </fieldset>\n";
	 echo "                  </td>\n";
	 ### DATOS DEL INMUEBLE
	 echo "                  <td valign=\"top\" height=\"40\" width=\"50%\">\n";   #Col. 1+2+3  
	 echo "                     <fieldset><legend>Datos del Inmueble</legend>\n";
	 echo "                     <table border=\"0\" width=\"100%\">\n"; #TABLE  2 Columnas	
 	 echo "                        <tr>\n";
	 echo "                           <td align=\"center\" colspan=\"2\">Superficie</td>\n";
	 echo "                           <td align=\"center\" colspan=\"2\">Instalaciones Especiales</td>\n"; 	
	 echo "                        </tr>\n";	 
	 echo "                        <tr>\n";	  
 	 echo "                           <td align=\"left\" width=\"30%\" class=\"bodyTextH_Small\">&nbsp Superficie s/mens:</td>\n";	 	
	 echo "                           <td align=\"left\" width=\"25%\" class=\"bodyTextD_Small\">&nbsp $ter_smen m²</td>\n";
	 echo "                           <td align=\"left\" width=\"32%\" class=\"bodyTextH_Small\">&nbsp Aire Acondicionado:</td>\n";
	 echo "                           <td align=\"left\" width=\"13%\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('esp_aac');	 
   echo "                              <select class=\"navText\" name=\"esp_aac\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $esp_aac) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";		 
	 echo "                           </td>\n";	                
	 echo "                        </tr>\n";
 	 echo "                        <tr>\n";	 
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Superficie s/doc:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp&nbsp<input type=\"text\" name=\"adq_sdoc\" id=\"form_anadir2\" class=\"navText\" maxlength=\"8\" value=\"$adq_sdoc\"> m²</td>\n";	 
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Tanque Subterraneo:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('esp_tas');	 
   echo "                              <select class=\"navText\" name=\"esp_tas\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $esp_tas) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";
 	 echo "                        </tr>\n";	 
	 echo "                        <tr>\n"; 
	 echo "                           <td align=\"center\" colspan=\"2\">Valoración Catastral</td>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Tanque Elevado:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";	
	 $valores = get_abr ('esp_tae');	 
   echo "                              <select class=\"navText\" name=\"esp_tae\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $esp_tae) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";	 
	 echo "                        </tr>\n";	
	 echo "                        <tr>\n"; 
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Zona Homogénea:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp [-]</td>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Area de Servicio:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('esp_ser');	 
   echo "                              <select class=\"navText\" name=\"esp_ser\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $esp_ser) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";	
	 echo "                        </tr>\n";	
	 echo "                        <tr>\n";
	 echo "                           <td align=\"center\" colspan=\"2\">Conexión a servicios públicos</td>\n";	
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Garaje:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('esp_gar');	 
   echo "                              <select class=\"navText\" name=\"esp_gar\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $esp_gar) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";		 
	 echo "                           </td>\n";			 	 
	 echo "                        </tr>\n";	
	 echo "                        <tr>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Agua:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('cnx_agu');	 
   echo "                              <select class=\"navText\" name=\"cnx_agu\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $cnx_agu) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";	 	  
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Depositos:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('esp_dep');	 
   echo "                              <select class=\"navText\" name=\"esp_dep\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $esp_dep) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";	 		 
	 echo "                        </tr>\n";	
	 echo "                        <tr>\n";	
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Luz:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('cnx_luz');	 
   echo "                              <select class=\"navText\" name=\"cnx_luz\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $cnx_luz) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";	 	  
	 echo "                           <td align=\"center\" colspan=\"2\">Mejoras</td>\n";		 
	 echo "                        </tr>\n";	
	 echo "                        <tr>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Telefono:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('cnx_tel');	 
   echo "                              <select class=\"navText\" name=\"cnx_tel\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == "$cnx_tel") {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";	 
	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Lavanderia</td>\n";		
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('mej_lav');	 
   echo "                              <select class=\"navText\" name=\"mej_lav\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $mej_lav) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";		 
	 echo "                           </td>\n";	 	 	 
	 echo "                        </tr>\n";	
	 echo "                        <tr>\n";
	 echo "                           <td align=\"left\" width=\"15%\" class=\"bodyTextH_Small\">&nbsp Alcantarillado:</td>\n";
	 echo "                           <td align=\"left\" width=\"15%\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('cnx_alc');	 
   echo "                              <select class=\"navText\" name=\"cnx_alc\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $cnx_alc) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";		 
	 echo "                           </td>\n";	
	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Parrillero:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('mej_par');	 
   echo "                              <select class=\"navText\" name=\"mej_par\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $mej_par) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";		 
	 echo "                           </td>\n";	 	 
	 echo "                        </tr>\n";	
	 echo "                        <tr>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp TV Cable:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('cnx_cab');	 
   echo "                              <select class=\"navText\" name=\"cnx_cab\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $cnx_cab) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";	  
	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Horno:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('mej_hor');	 
   echo "                           <select class=\"navText\" name=\"mej_hor\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $mej_hor) {
	       echo "                        <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                        <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                           </select>\n";	 	 
	 echo "                           </td>\n";	  	 
	 echo "                        </tr>\n";	
	 echo "                        <tr>\n";
	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Gas Domiciliario:</td>\n";
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('cnx_gas');	 
   echo "                              <select class=\"navText\" name=\"cnx_gas\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $cnx_gas) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";	 
	 echo "                           </td>\n";	 	 
	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Piscina:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('mej_pis');	 
   echo "                           <select class=\"navText\" name=\"mej_pis\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $mej_pis) {
	       echo "                        <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                        <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                           </select>\n";		 
	 echo "                           </td>\n";	   	 
	 echo "                        </tr>\n";
	 echo "                        <tr>\n";
	 echo "                           <td colspan=\"2\"> &nbsp</td>\n";		 
	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Otros:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">\n";
	 $valores = get_abr ('mej_otr');	 
   echo "                              <select class=\"navText\" name=\"mej_otr\" size=\"1\">\n";
   $i = 0;
   foreach ($valores  as $i => $j) {
	    $texto = utf8_decode(abr($valores[$i]));		
      if ($valores[$i] == $mej_otr) {
	       echo "                           <option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n"; 
	    } else {
		     echo "                           <option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	    }
			$i++;
   } 	
   echo "                              </select>\n";		 
	 echo "                           </td>\n";	   	 
	 echo "                        </tr>\n";	 	 	 	 	 
	 echo "                     </table>\n";
	 echo "                     </fieldset>\n";
	 echo "                  </td>\n";	 
	 echo "               </tr>\n";	 	 	 	 
	 echo "            </table>\n";	 	 
   echo "         </td>\n"; 
   echo "      </tr>\n";	 
	 if ($error7) {
      echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	    echo "         <font color=\"red\">$mensaje_de_error7</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "      </tr>\n";
	 }		 
	 
	 ##################################################
	 #                  COLINDANTES                   #
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Colindantes</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  10 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"13\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">NORTE:</td>\n";   #Col. 2	  	  	 
	 echo "                  <td align=\"center\" width=\"60%\" class=\"smallText\"><input type=\"text\" name=\"col_norte_nom\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_col\" value=\"$col_norte_nom\"></td>\n"; #Col. 3
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 4	
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">Mts.</td>\n";   #Col. 5	   
	 echo "                  <td align=\"center\" width=\"25%\" class=\"bodyTextD\"><input type=\"text\" name=\"col_norte_med\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_col_med\" value=\"$col_norte_med\"></td>\n";   #Col. 6	  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 7	
	 echo "               </tr>\n";
	 echo "               <tr>\n";  	                     
	 echo "                  <td></td>\n";   #Col. 1		 	 
	 echo "                  <td align=\"center\" class=\"bodyTextH\">SUR:</td>\n";   #Col. 8	  	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"col_sur_nom\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_col\" value=\"$col_sur_nom\"></td>\n"; #Col. 9
	 echo "                  <td></td>\n";   #Col. 10	
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Mts.</td>\n";   #Col. 11   
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"col_sur_med\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_col_med\" value=\"$col_sur_med\"></td>\n";   #Col. 12 
	 echo "                  <td></td>\n";   #Col. 13	 	 	 	    
	 echo "               </tr>\n";
	 echo "               <tr>\n";  	                     
	 echo "                  <td></td>\n";   #Col. 1		 	 
	 echo "                  <td align=\"center\" class=\"bodyTextH\">ESTE:</td>\n";   #Col. 8	  	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"col_este_nom\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_col\" value=\"$col_este_nom\"></td>\n"; #Col. 9
	 echo "                  <td></td>\n";   #Col. 10	
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Mts.</td>\n";   #Col. 11   
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"col_este_med\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_col_med\" value=\"$col_este_med\"></td>\n";   #Col. 12 
	 echo "                  <td></td>\n";   #Col. 13	 	 	 	    
	 echo "               </tr>\n";
	 echo "               <tr>\n";  	                     
	 echo "                  <td></td>\n";   #Col. 1		 	 
	 echo "                  <td align=\"center\" class=\"bodyTextH\">OESTE:</td>\n";   #Col. 8	  	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"col_oeste_nom\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_col\" value=\"$col_oeste_nom\"></td>\n"; #Col. 9
	 echo "                  <td></td>\n";   #Col. 10	
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Mts.</td>\n";   #Col. 11   
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"col_oeste_med\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_col_med\" value=\"$col_oeste_med\"></td>\n";   #Col. 12 
	 echo "                  <td></td>\n";   #Col. 13	 	 	 	    
	 echo "               </tr>\n";	 
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "      </tr>\n";
	 #if ($error8) {
    #  echo "      <tr>\n"; 	 
	  #  echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	 #   echo "         <font color=\"red\">$mensaje_de_error8</font> <br />\n";				 	    
		#  echo "         </td>\n"; 
   #   echo "      </tr>\n";
	 #}	  	 
	 ##################################################
	 #----------------- OBSERVACIONES ----------------#
	 ##################################################
	 echo "      <tr>\n"; 	 
	 echo "         <td valign=\"top\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Observaciones</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  10 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"10\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">Encuestador</td>\n";   #Col. 2
	# $ctr_enc_texto = textconvert($ctr_enc);		  	  	 
	 echo "                  <td align=\"center\" width=\"28%\" class=\"bodyTextD\"><input type=\"text\" name=\"ctr_enc\" id=\"form_anadir1\" class=\"navText\" maxlength=\"30\" value=\"$ctr_enc_texto\"></td>\n"; #Col. 3
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 4	
	 echo "                  <td align=\"center\" width=\"13%\" class=\"bodyTextH\">Responsable</td>\n";   #Col. 5
	# $ctr_sup_texto = textconvert($ctr_sup);	   
	 echo "                  <td align=\"center\" width=\"28%\" class=\"bodyTextD\"><input type=\"text\" name=\"ctr_sup\" id=\"form_anadir1\" class=\"navText\" maxlength=\"30\" value=\"$ctr_sup_texto\"></td>\n";   #Col. 6	  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 7	
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">Fecha</td>\n";   #Col. 8
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextD\"><input type=\"text\" name=\"ctr_fech\" id=\"form_anadir1\" class=\"navText\" maxlength=\"10\" value=\"$ctr_fech\"></td>\n";   #Col. 9 	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 10	 	 	 	    
	 echo "               </tr>\n";
	 echo "               <tr>\n";  	                     
	 echo "                  <td></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Observaciones</td>\n";   #Col. 2
	# $ctr_obs_texto = textconvert($ctr_obs);		 	    	  	 
	 echo "                  <td align=\"center\" colspan=\"7\" class=\"bodyTextD\"><input type=\"text\" name=\"ctr_obs\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_obs\" value=\"$ctr_obs_texto\"></td>\n"; #Col. 3-9	 
	 echo "                  <td></td>\n";   #Col. 10 	 	 	    
	 echo "               </tr>\n";	 
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "      </tr>\n";
	 if ($error8) {
      echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	    echo "         <font color=\"red\">$mensaje_de_error8</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "      </tr>\n";
	 }	  	 
	 echo "      <tr>\n"; 	 
	 echo "         <td align=\"center\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3 	
	 echo "         <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"$accion Datos\">\n"; 
	 if ($accion == "Modificar") {
	    echo "         <input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";	
	    echo "         <input type=\"hidden\" name=\"tipo_inmu\" value=\"$tipo_inmu\">\n";	
	    echo "         <input type=\"hidden\" name=\"ctr_x\" value=\"$ctr_x\">\n";
	    echo "         <input type=\"hidden\" name=\"ctr_y\" value=\"$ctr_y\">\n";														
	    echo "         <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";
      echo "         <br /><input name=\"registrar_cambios\" type=\"checkbox\" $reg_checked> Registrar Cambios \n";
      echo "         (Active esta casilla cuando la modificación es importante para el historial del lote) \n";								
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