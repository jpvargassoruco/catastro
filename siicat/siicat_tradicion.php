<?php

/*
if (isset($_GET["cod_cat"])) {   
	 $cod_cat = $_GET["cod_cat"];
}	 
if (isset($_POST["cod_cat"])) {   
	 $cod_cat = $_POST["cod_cat"];
}	

if (isset($_POST["search_string"])) {   
	 $search_string = $_POST["search_string"];
} else $search_string = "";  
*/

$error = false;
################################################################################
#------------------------------- RECTIFICACION --------------------------------#
################################################################################	
if ((isset($_POST["Rectificar"])) AND ($_POST["Rectificar"] == "SI")) {	
	 $id_inmu = $_POST["id_inmu"];
   $sql="SELECT adq_fech, tit_1pat_ant, tit_1mat_ant, tit_1nom1_ant, tit_1nom2_ant, tit_1ci_ant,
	         tit_2pat_ant, tit_2mat_ant, tit_2nom1_ant, tit_2nom2_ant, tit_2ci_ant,
	         tit_cara_ant, dom_dpto_ant, dom_ciu_ant, dom_dir_ant, der_num_ant, der_fech_ant,
      adq_modo_ant, adq_doc_ant, adq_fech_ant FROM transfer WHERE id_inmu = '$id_inmu' ORDER BY adq_fech DESC LIMIT 1";			 
   $result = pg_query($sql);
   $info = pg_fetch_array($result, null, PGSQL_ASSOC);
   if (($info['adq_fech'] == "1900-01-01") OR (trim($info['adq_fech']) == "")) {
      $adq_fech = "";
   } else $adq_fech = change_date ($info['adq_fech']);	 
   $cod_pad_rect = $info['cod_pad_ant'];
   $tit_1nom1_rect = utf8_decode($info['tit_1nom1_ant']);
   $tit_1nom2_rect = utf8_decode($info['tit_1nom2_ant']);
   $tit_1pat_rect = utf8_decode($info['tit_1pat_ant']);
   $tit_1mat_rect = utf8_decode($info['tit_1mat_ant']);
   $tit_1ci_rect = $info['tit_1ci_ant'];
   $tit_2nom1_rect = utf8_decode($info['tit_2nom1_ant']);
   $tit_2nom2_rect = utf8_decode($info['tit_2nom2_ant']);
   $tit_2pat_rect = utf8_decode($info['tit_2pat_ant']);
   $tit_2mat_rect = utf8_decode($info['tit_2mat_ant']);
   $tit_2ci_rect = $info['tit_2ci_ant'];	 
   $tit_cara_rect = $info['tit_cara_ant'];
   $dom_dpto_rect = $info['dom_dpto_ant'];
   $dom_ciu_rect = utf8_decode($info['dom_ciu_ant']);	 	 
   $dom_dir_rect = utf8_decode($info['dom_dir_ant']);	 
   $der_num_rect = $info['der_num_ant'];	 	 
   if (($info['der_fech_ant'] == "1900-01-01") OR (trim($info['der_fech_ant']) == "")) {
      $der_fech_rect = "1900-01-01";
   } else $der_fech_rect = change_date ($info['der_fech_ant']);	 	 
   $adq_modo_rect = $info['adq_modo_ant'];
   $adq_doc_rect = $info['adq_doc_ant'];	 	 
   if (($info['adq_fech_ant'] == "1900-01-01") OR (trim($info['adq_fech_ant']) == "")) {
      $adq_fech_rect = "1900-01-01";
   } else $adq_fech_rect = change_date ($info['adq_fech_ant']); 		 
   $sql="UPDATE info_predio SET tit_1pat = '$tit_1pat_rect', tit_1mat = '$tit_1mat_rect', tit_1nom1 = '$tit_1nom1_rect', tit_1nom2 = '$tit_1nom2_rect', 
 			      tit_1ci = '$tit_1ci_rect', tit_2pat = '$tit_2pat_rect', tit_2mat = '$tit_2mat_rect', tit_2nom1 = '$tit_2nom1_rect', tit_2nom2 = '$tit_2nom2_rect', 
 			      tit_2ci = '$tit_2ci_rect', tit_cara = '$tit_cara_rect', dom_dpto = '$dom_dpto_rect', dom_ciu = '$dom_ciu_rect', dom_dir = '$dom_dir_rect',
 			      der_num = '$der_num_rect', der_fech = '$der_fech_rect', adq_modo = '$adq_modo_rect', adq_doc = '$adq_doc_rect', adq_fech = '$adq_fech_rect'						
						WHERE cod_cat = '$cod_cat'";
#echo "$sql";	
	 pg_query($sql);			 	 	 	 	 	  
	 pg_query("DELETE FROM transfer WHERE id_inmu = '$id_inmu' AND adq_fech = '$adq_fech'");
   ########################################
	 #-------------- REGISTRO --------------#
   ########################################
	 $accion = "Rectificado Transf.";
	 $username = get_username($session_id);
	 pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		         VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");							 	 
}
########################################
#-- GENERAR NOMBRE Y DIRECCION ACTUAL -#
########################################	
$sql="SELECT cod_pad, tit_1nom1, tit_1nom2, tit_1pat, tit_1mat, tit_1ci, 
    tit_cara, dir_tipo, dir_nom, dir_num, dir_edif, der_num, der_fech, dom_dpto, dom_ciu, dom_dir, adq_modo, 
		adq_doc, adq_fech FROM info_predio WHERE cod_cat = '$cod_cat'";
$result=pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$cod_pad = $info['cod_pad'];
if ($cod_pad == "") {
  $cod_pad = "-";  
}
$tit_1nom1 = utf8_decode($info['tit_1nom1']);
$tit_1nom2 = utf8_decode($info['tit_1nom2']);
$tit_1pat = strtoupper(utf8_decode($info['tit_1pat']));
$tit_1mat = strtoupper(utf8_decode($info['tit_1mat']));
if ($tit_1pat == "") {
   $titular1 = "-";
} else {
   $titular1 = trim ($tit_1nom1." ".$tit_1nom2." ".$tit_1pat." ".$tit_1mat);
}
if ($info['tit_1ci'] == "") {
   $tit_1ci = "-";
} else $tit_1ci = $info['tit_1ci'];
$tit_cara = $info['tit_cara'];
$direccion = abr($info['dir_tipo'])." ".$info['dir_nom']." ".$info['dir_num']." ".$info['dir_edif'];
$direccion = trim(utf8_decode($direccion));
$der_num = $info['der_num'];
if (($info['der_fech'] == "1900-01-01") OR (trim($info['der_fech']) == "")) {
   $der_fech = "";
} else $der_fech = change_date ($info['der_fech']);
$dom_dpto = utf8_decode($info['dom_dpto']);
$dom_ciu = utf8_decode($info['dom_ciu']);
$dom_dir = utf8_decode($info['dom_dir']);
$adq_modo = $info['adq_modo'];
$adq_doc = utf8_decode($info['adq_doc']);
if (($info['adq_fech'] == "1900-01-01") OR (trim($info['adq_fech']) == "")) {
   $adq_fech = "";
} else $adq_fech = change_date ($info['adq_fech']);
pg_free_result($result);
########################################
#------------ LEER MONTO --------------#
########################################
$sql="SELECT adq_mont FROM transfer WHERE cod_cat = '$cod_cat' ORDER BY adq_fech DESC LIMIT 1";		
$check_monto = pg_num_rows(pg_query($sql));
if ($check_monto > 0 ) {	
   $result=pg_query($sql);
   $info = pg_fetch_array($result, null, PGSQL_ASSOC);
   $adq_mont = $info['adq_mont'];
   pg_free_result($result);
	 $adq_mont = $adq_mont." Bs.";
} else {
   $adq_mont = "-";
} 
########################################
#------ LEER TABLA TRANSFERENCIA ------#
########################################	
#$sql="SELECT cod_pad, tit_1nom1, tit_1nom2, tit_1pat, tit_1mat, tit_1ci, tit_cara, 
#    dir_tipo, dir_nom, dir_num, dir_edif, der_num, der_fech, dom_dpto, dom_ciu, dom_dir, adq_modo, 
#		adq_doc, adq_fech FROM transfer WHERE cod_cat = '$cod_cat' ORDER BY adq_fech";

$sql="SELECT adq_fech, cod_pad_ant, tit_1pat_ant, tit_1mat_ant, tit_1nom1_ant, tit_1nom2_ant, tit_1ci_ant, tit_cara_ant, 
      adq_modo_ant, adq_doc_ant, adq_fech_ant FROM transfer WHERE cod_cat = '$cod_cat' ORDER BY adq_fech DESC";		
$no_de_registros = pg_num_rows(pg_query($sql));
if ($no_de_registros > 0 ) {	
   $result = pg_query($sql);
	 $i = $j = 0; 
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {	
	       if ($i == 0) {
				    $adq_fech2[$j] = change_date ($col_value);
           # if (($adq_fech_ant[$j] == "1900-01-01") OR ($adq_fech_ant[$j] == "")) {
           #     $adq_fech_ant[$j] = "-";
           # } else $adq_fech_ant[$j] = change_date ($adq_fech_ant[$j]);											 			 
	       } elseif ($i == 1) {
				    if ($col_value == "") {
						   $cod_pad_ant[$j] = "-";
						} else $cod_pad_ant[$j] = $col_value;
				 } elseif ($i == 2) {
				    $tit_1pat_ant[$j] = utf8_decode($col_value);
				 } elseif ($i == 3) {
				    $tit_1mat_ant[$j] = utf8_decode($col_value);
				 } elseif ($i == 4) {
				    $tit_1nom1_ant[$j] = utf8_decode($col_value);
				 } elseif ($i == 5) {
				    $tit_1nom2_ant[$j] = utf8_decode($col_value);
            if ($tit_1pat_ant == "") {
               $titular1_ant[$j] = "-";
            } else {
               $titular1_ant[$j] = $tit_1nom1_ant[$j]." ".$tit_1nom2_ant[$j]." ".$tit_1pat_ant[$j]." ".$tit_1mat_ant[$j];
               $titular1_ant[$j] = trim($titular1_ant[$j]);
						}
				 } elseif ($i == 6) {
				    $tit_1ci_ant[$j] = utf8_decode($col_value);	
				 } elseif ($i == 7) {
				    $tit_cara_ant[$j] = utf8_decode($col_value);
				 } elseif ($i == 8) {
				    $adq_modo_ant[$j] = utf8_decode($col_value);
				 } elseif ($i == 9) {
				    $adq_doc_ant[$j] = utf8_decode($col_value);																													
				 } else {
				    $adq_fech_ant[$j] = change_date ($col_value);
				    $i = -1;
				 }
         $i++;
	    }
			$j++;			 
   }
}

				 					 			
/*$tit_1ci_ant = $info['tit_1ci'];
$tit_cara_ant = $info['tit_cara'];
$direccion = abr($info['dir_tipo'])." ".$info['dir_nom']." ".$info['dir_num']." ".$info['dir_edif'];
$direccion = trim(utf8_decode($direccion));
$der_num_ant = $info['der_num'];
if (($info['der_fech'] == "1900-01-01") OR (trim($info['der_fech']) == "")) {
   $der_fech_ant = "";
} else $der_fech_ant = change_date ($info['der_fech']);
$dom_dpto_ant = utf8_decode($info['dom_dpto']);
$dom_ciu_ant = utf8_decode($info['dom_ciu']);
$dom_dir_ant = utf8_decode($info['dom_dir']);
$adq_modo_ant = $info['adq_modo'];
$adq_doc_ant = utf8_decode($info['adq_doc']);
if (($info['adq_fech'] == "1900-01-01") OR (trim($info['adq_fech']) == "")) {
   $adq_fech_ant = "";
} else $adq_fech_ant = change_date ($info['adq_fech']);
pg_free_result($result);  */

/*
if (isset($_POST["submit"])) { 
   if ($_POST["submit"] == "AÃ±adir") { 
      $cambio_id_select = ""; 
 		  $columnas = array('--DATOS PREDIO---','cod_cat','dir','ter_smen','-----TITULAR-----','cod_pad','tit1','tit_1ci','tit2','tit_pers','tit_cant',
			                  'tit_bene','tit_cara','--DOMICILIO TIT--','dom_dpto','dom_ciu','dom_dir','------DDRR-------','der_num','der_fech','---ADQUISICION---',
												'adq_modo','adq_doc','adq_fech','------VIA--------','via_tipo','via_clas','via_uso','via_mat','----SERVICIOS----','ser_alc',
												'ser_agu','ser_luz','ser_tel','ser_gas','ser_alu','ser_cab','--INST. ESPEC.---','ter_eesp','esp_aac','esp_tas',
												'esp_tae','esp_ser','esp_gar','esp_dep','-----MEJORAS-----','mej_lav','mej_par','mej_hor','mej_pis','mej_otr',
												'------OTROS------','ter_uso','ter_ace','ter_mur','ter_san');	
	    $no_de_variables = 56;	
			$i = 0;
	    while ($i < $no_de_variables) {
		     $texto = $columnas[$i];
			   $sql="SELECT num, permitido FROM info_predio_permitido WHERE col_nombre = '$texto'";	
         $check_existence = pg_num_rows(pg_query($sql));
         if ($check_existence > 0 ) {	 
	          $result = pg_query($sql);
            $info_temp = pg_fetch_array($result, null, PGSQL_ASSOC);
				    $numero[$i] = $info_temp['num'];
            $cadena = $info_temp['permitido'];
#echo "NUM: $numero[$i], CADENA: $cadena<br>";
            ##### EXTRAER VALORES DE LA CADENA
						$j = $k = $x = 0;
            while ($j <= strlen($cadena)) {
               $char = substr($cadena, $j, 1);	
	             if ($char == ',') {
                  $valor1[$i][$x] = substr($cadena,$k,$j-$k);
									$valor2[$i][$x] = utf8_decode (abr($valor1[$i][$x]));	
		              $k = $j+1;	
									$x++;		
	             } elseif ($j == strlen($cadena)) {
                  $valor1[$i][$x] = substr($cadena,$k,$j-$k);	
									$valor2[$i][$x] = utf8_decode (abr($valor1[$i][$x]));										
		              $k = $j+1;	
									$x++;		
	             }							  
	             $j++;   
            } #END_OF_WHILE				    
			   } else {
				   $numero[$i] = 1;
					 $valor1[$i][0]= "";
					 $valor2[$i][0]= "-----";					 
			   }
			   $i++;
	    }	
	 } else $cambio_id_select = $_POST["cambio_id_select"]; 
}
########################################
#---------------- AÑADIR --------------#
########################################	
if ((isset($_POST["confirmar"])) AND ($_POST["confirmar"] == "AÃ±adir")) { 
   $fecha_cambio_temp = $_POST["fecha_cambio"];
   $variable_temp = $_POST["variable"];	 
   $valor_ant_temp = trim ($_POST["valor_ant"]);
	 $stage2 = $_POST["stage2"];
	 if ($stage2 != "") {
	    $valor_ant_temp = $stage2;
	 }
   $sql="SELECT id FROM cambios WHERE cod_cat = '$cod_cat' AND fecha_cambio = '$fecha_cambio_temp' AND variable = '$variable_temp'";
   $check_cambios = pg_num_rows(pg_query($sql));	
	 if ($check_cambios > 0) {
	    $error = true;
			$fecha_cambio_temp = change_date ($fecha_cambio_temp);
			$mensaje_de_error = "Error: Ya existe un cambio de esa variable en fecha $fecha_cambio_temp!";
	 } else {  
      pg_query("INSERT INTO cambios (cod_cat, fecha_cambio, variable, valor_ant) VALUES ('$cod_cat','$fecha_cambio_temp','$variable_temp','$valor_ant_temp')");
	 }					
}
########################################
#---------------- BORRAR --------------#
########################################	
if ((isset($_POST["confirmar"])) AND ($_POST["confirmar"] == "SI")) { 
   $cambio_id_select = $_POST["cambio_id"]; 
	 pg_query("DELETE FROM cambios WHERE cod_cat = '$cod_cat' AND id = '$cambio_id_select'");
}
########################################
#-------- LEER CAMBIOS DE TABLA -------#
########################################	
$sql="SELECT id, fecha_cambio, variable, valor_ant FROM cambios WHERE cod_cat = '$cod_cat' ORDER BY fecha_cambio,id";
#$result = pg_query($sql);
#$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$no_de_cambios = pg_num_rows(pg_query($sql));	
if ($no_de_cambios > 0) {
   $result = pg_query($sql); 
	 $i = $j = 0; 
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
				 if ($i == 0) {
            $cambio_id[$j] = $col_value;
				 } elseif ($i == 1) {
            $fecha_cambio[$j] = change_date ($col_value);
				 } elseif ($i == 2) {
            $variable[$j] = abr($col_value);
				 } else {
            $valor_ant[$j] = utf8_decode (abr($col_value));
				    $i = -1;
				 }
			   $i++;
      }
			$j++;
   }										 
   pg_free_result($result);
} else {
   $mensaje_cambio = "No se ha registrado ningun cambio con ese $predio en la base de datos.";
}
################################################################################
#------------------ CHEQUEAR SI EL PREDIO ESTA ACTIVO -------------------------#
################################################################################	
$sql="SELECT activo FROM codigos WHERE cod_cat = '$cod_cat'";
$result_act = pg_query($sql);
$act = pg_fetch_array($result_act, null, PGSQL_ASSOC);
$activo = $act['activo'];
pg_free_result($result_act);  */
   $check_boton = false;
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################			
	 # Fila 1
   echo "<td>\n";
   echo "  <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";	 
   echo "      <tr>\n";
	 echo "         <td width=\"5%\" height=\"30px\"> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td align=\"center\" valign=\"center\" width=\"90%\" class=\"pageName\">\n"; 
	 echo "            Tradición del Inmueble\n";                          
   echo "         </td>\n";
	 echo "         <td width=\"5%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";	
	 ##################################################
	 #-------------- PROPIETARIO ANTERIOR ------------#
	 ##################################################	 
   echo "      <tr>\n";  
	 echo "         <td height=\"30\"> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	
	 echo "            <table width=\"100%\" border=\"0\">\n";		# 3 Columnas		
 	 echo "               <tr>\n";		
	 echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextH\">Código</td>\n";
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	 
	 echo "                  <td align=\"center\" width=\"32%\" class=\"bodyTextH\">Titular Principal Actual</td>\n";
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";		 
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">C.I.</td>\n";
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">P.M.C.</td>\n";
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";		 		 
	 echo "                  <td align=\"center\" width=\"30%\" class=\"bodyTextH\">Dirección</td>\n";			
	 echo "               </tr>\n";
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"center\" class=\"bodyTextD\">$cod_cat</td>\n";
	 echo "                  <td> &nbsp</td>\n";	 	  
	 echo "                  <td align=\"center\" class=\"bodyTextD\">$titular1</td>\n";
	 echo "                  <td> &nbsp</td>\n";	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">$tit_1ci</td>\n";	 
	 echo "                  <td> &nbsp</td>\n";
	 echo "                  <td align=\"center\" class=\"bodyTextD\">$cod_pad</td>\n";	 
	 echo "                  <td> &nbsp</td>\n";		 	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">$direccion</td>\n";				 				 
   echo "               </tr>\n";		  
	 echo "            </table>\n"; 
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3   		
	 echo "      </tr>\n";
	 ##################################################
	 #----------------- ADQUISICION ------------------#
	 ##################################################
	 echo "      <tr>\n";
	 echo "         <td height=\"50\"> &nbsp</td>\n";   #Col. 1	 
	 echo "         <td valign=\"top\">\n";   #Col. 2  
#	 echo "         <fieldset><legend>Adquisición del Inmueble</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  8 Columnas   
	 echo "               <tr>\n"; 
	 echo "                  <td align=\"center\" width=\"14%\" class=\"bodyTextH\">Titularidad</td>\n";   #Col. 1	     	  	 
	 echo "                  <td align=\"left\" width=\"18%\" class=\"bodyTextD\">\n";   #Col. 2
	 $texto = utf8_decode(abr($tit_cara));
	 echo "                     &nbsp $texto\n"; 	 
	 echo "                  </td>\n"; 	 	  	                     
	 echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">Modo de adquisición</td>\n";   #Col. 3	    	  	 
	 echo "                  <td align=\"left\" width=\"17%\" class=\"bodyTextD\">\n";   #Col. 4	
	 $texto = utf8_decode(abr($adq_modo));
	 echo "                     &nbsp $texto\n"; 	 
	 echo "                  </td>\n";   
#	 echo "                  <td width=\"1%\"></td>\n";   #Col. 5	
	 echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextH\">Monto (Bs.)</td>\n";   #Col. 5	   
	 echo "                  <td align=\"left\" width=\"11%\" class=\"bodyTextD\">&nbsp $adq_mont</td>\n";   #Col. 6	
#	 echo "                  <td width=\"1%\"></td>\n";   #Col. 9	 	    	 
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">Fecha</td>\n";   #Col. 7
	 echo "                  <td align=\"left\" width=\"9%\" class=\"bodyTextD\">&nbsp $adq_fech</td>\n";   #Col. 8  	 	 	 	    
	 echo "               </tr>\n";
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"8\" class=\"bodyText\"></td>\n";   #Col. 1-8	 
	 echo "               </tr>\n";	 
	 echo "               <tr>\n";  	                     
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Documentación</td>\n";   #Col. 1	    	  	 
	 echo "                  <td align=\"left\" colspan=\"7\" class=\"bodyTextD\">&nbsp $adq_doc</td>\n";   #Col. 2-8	
	 echo "               </tr>\n";	 	 
	 ##################################################
	 #---------------- DOCUMENTACION -----------------#
	 ##################################################
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"8\" class=\"bodyText\"></td>\n";   #Col. 1-7	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td align=\"center\" colspan=\"2\" class=\"bodyTextH\">Documentación o Número de DDRR</td>\n";   #Col. 1	    	  	    
	 echo "                  <td align=\"left\" colspan=\"4\" class=\"bodyTextD\">&nbsp $der_num</td>\n";  #Col. 2-6	 
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Fecha</td>\n";   #Col. 7 
	 echo "                  <td align=\"left\" class=\"bodyTextD\">&nbsp $der_fech</td>\n";   #Col. 8  	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
	# echo "         </fieldset>\n";	 	 
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3   	  
	 echo "      </tr>\n"; 	 
	 ####  	 
#   echo "			 <form name=\"form1\" method=\"post\" action=\"index.php?mod=68&id=$session_id\" accept-charset=\"utf-8\">\n";	 
 
	 ##################################################
	 #------------ TRADICION DE INMUEBLE -------------#
	 ##################################################
	 echo "      <tr>\n";
	 echo "         <td align=\"center\" colspan=\"3\"> &nbsp</td>\n";   #Col. 1-3 
	 echo "      </tr>\n";		 
	 echo "      <tr>\n"; 
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1  	 	 
	 echo "         <td valign=\"top\">\n";   #Col. 2
   echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=69&id=$session_id\" accept-charset=\"utf-8\">\n";	  
	 echo "         <fieldset><legend>Tradición del Inmueble</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  15 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"15\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";
	 if ($no_de_registros == 0) {
	    echo "               <tr>\n"; 
	    echo "                  <td align=\"center\" colspan=\"13\" class=\"bodyTextD\">\n";   #Col. 2
	    echo "                     <font color=\"red\"> No hay registros antiguos del $predio en la base de datos.</font>\n";				
	    echo "                  </td>\n";		
	    echo "               </tr>\n";	
			 				
	 } else {	   
	    echo "               <tr>\n";  	  
	    echo "                  <td width=\"1%\"></td>\n";   #Col. 1	  
	    echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">Desde Fecha</td>\n";		                   
	    echo "                  <td width=\"1%\"></td>\n";   #Col. 3	
	    echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextH\">Hasta Fecha</td>\n";
	    echo "                  <td width=\"1%\"></td>\n";   #Col. 5		 
	    echo "                  <td align=\"center\" width=\"30%\" class=\"bodyTextH\">Nombre y Apellido</td>\n"; 
	    echo "                  <td width=\"1%\"></td>\n";   #Col. 7		 
	    echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">C.I.</td>\n"; 
	    echo "                  <td width=\"1%\"></td>\n";   #Col. 9 	 
	    echo "                  <td align=\"center\" width=\"24%\" class=\"bodyTextH\">Titularidad</td>\n";   
	    echo "                  <td width=\"1%\"></td>\n";   #Col. 11
	    if ($nivel > 3) {		 
	       echo "                  <td align=\"center\" width=\"5%\" class=\"bodyTextH\">Rect.</td>\n";  #Col. 12
	    } else {
	       echo "                  <td align=\"center\" width=\"5%\">&nbsp</td>\n";	 
	    }	
	    echo "                  <td width=\"1%\"></td>\n";   #Col. 13							  	 	   	 	 	    
	    echo "               </tr>\n";
			$i = 0;
			while ($i < $no_de_registros) { 
			   if ($i > 0) {
	          echo "               <tr>\n"; 
	          echo "                  <td colspan=\"11\"><hr width=\"95%\"></td>\n";   #Col. 1-11	 	   	 	 	    
	          echo "               </tr>\n";				 
				 }	 
	       echo "               <tr>\n"; 
	       echo "                  <td></td>\n";   #Col. 1		  
	       echo "                  <td align=\"center\" class=\"bodyTextD\">$adq_fech_ant[$i]</td>\n"; 		 
	       echo "                  <td></td>\n";   #Col. 3	
	       echo "                  <td align=\"center\" class=\"bodyTextD\">$adq_fech2[$i]</td>\n";  
	       echo "                  <td></td>\n";   #Col. 5	
	       echo "                  <td align=\"center\" class=\"bodyTextD\">$titular1_ant[$i]</td>\n";   	    	  	 
	       echo "                  <td></td>\n";   #Col. 7			   	  	 
	       echo "                  <td align=\"center\" class=\"bodyTextD\">$tit_1ci_ant[$i]</td>\n";   
	       echo "                  <td></td>\n";   #Col. 9	
	       $texto = utf8_decode(abr($tit_cara_ant[$i]));	 	 	 	 
	       echo "                  <td align=\"center\" class=\"bodyTextD\">$texto</td>\n";   
	       echo "                  <td></td>\n";   #Col. 11	 
	       if (($nivel > 3) AND (!$check_boton)) {		 
	          echo "               <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 12	
            echo "                  <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";
				    echo "                  <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";		
						echo "                  <input type=\"image\" src=\"graphics/boton_boleta.png\" width=\"12\" height=\"12\" class=\"smallText\" name=\"Rectificar\" value=\"Rectificar\">\n";						
	          echo "               </td>\n";   #Col. 9		
						$check_boton = true;				
	       } else {
	          echo "                  <td align=\"center\">&nbsp</td>\n";	 
	       }			 	 				 
	       echo "                  <td></td>\n";   #Col. 13				  	   	 	 	    
	       echo "               </tr>\n";
	       echo "               <tr>\n"; 
	       echo "                  <td colspan=\"3\"> &nbsp</td>\n";   #Col. 1	
	       echo "                  <td align=\"left\" colspan=\"7\" class=\"bodyTextD\">\n";   #Col. 4	
	       $texto = utf8_decode(abr($adq_modo_ant[$i]));				 
	       echo "                  ($texto según $adq_doc_ant[$i] en fecha $adq_fech_ant[$i])\n";   #Col. 9	
	       echo "                  </td>\n";   #Col. 9						  	    
	       echo "                  <td colspan=\"3\"> &nbsp</td>\n";   #Col. 11	  	   	 	 	    
	       echo "               </tr>\n";				 				 
				 $i++;
      }
   }		 	  
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";
	 echo "         </form>\n";	 	 
	 echo "         </td>\n"; 
	 echo "         <td> &nbsp</td>\n";   #Col. 3 	 
	 echo "      </tr>\n";

   ########################################
   #------------ RECTIFICADO  ------------#
   ########################################		 
   if ((isset($_POST["Rectificar"])) AND ($_POST["Rectificar"] == "Rectificar"))  {
	    #$gestion_rect = $_POST["gestion"];
			#$no_orden_rect = $_POST["no_orden_rect"];
	    echo "	    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=69&id=$session_id\" accept-charset=\"utf-8\">\n";			
	    echo "      <tr height=\"20\">\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 1  	 	 
	    echo "         <td align=\"center\" valign=\"top\">\n";   #Col. 2	
	    echo "            <font color=\"red\"><b> RECTIFICAR TRADICION DEL INMUEBLE:</b></font>\n"; 			
	    echo "         </td>\n";					
	    echo "         <td> &nbsp</td>\n";   #Col. 3 			
	    echo "      </tr>\n";				
	    echo "      <tr>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 1  				 
	    echo "         <td align=\"center\" height=\"20\">\n";   #Col. 2 	 
	    echo "            <font color=\"red\"><b> Se borrará el registro actual y se restablecerá el último propietario! Realmente quiere rectificar?</b></font>\n"; 
	    echo "            <input name=\"Rectificar\" type=\"submit\" class=\"smallText\" value=\"SI\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";				  		 
	    echo "            <input name=\"Rectificar\" type=\"submit\" class=\"smallText\" value=\"NO\">\n";				 			
			echo "            <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";					 
#		  echo "            <input name=\"Submit\" type=\"hidden\" value=\"Ver\">\n";		       
		  echo "            <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";					
	    echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3 				
	    echo "      </tr>\n";	
			echo "      </form>\n";	
	 }
	  
  	 		  	 		 	  	     	 
/*	 echo "      <tr>\n"; 	 
	 echo "         <td align=\"center\" height=\"40\" colspan=\"3\">\n";   #Col. 1+2+3 	
   echo "         <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";									
   echo "         <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";
   echo "         <input name=\"cod_pad_ant\" type=\"hidden\" value=\"$cod_pad_ant\">\n";	
   echo "         <input name=\"tit_1pat_ant\" type=\"hidden\" value=\"$tit_1pat_ant\">\n";	
   echo "         <input name=\"tit_1mat_ant\" type=\"hidden\" value=\"$tit_1mat_ant\">\n";
   echo "         <input name=\"tit_1nom1_ant\" type=\"hidden\" value=\"$tit_1nom1_ant\">\n";	
   echo "         <input name=\"tit_1nom2_ant\" type=\"hidden\" value=\"$tit_1nom2_ant\">\n";
   echo "         <input name=\"tit_1ci_ant\" type=\"hidden\" value=\"$tit_1ci_ant\">\n";
   echo "         <input name=\"tit_cara_ant\" type=\"hidden\" value=\"$tit_cara_ant\">\n";
   echo "         <input name=\"dom_dpto_ant\" type=\"hidden\" value=\"$dom_dpto_ant\">\n";
   echo "         <input name=\"dom_ciu_ant\" type=\"hidden\" value=\"$dom_ciu_ant\">\n";
   echo "         <input name=\"dom_dir_ant\" type=\"hidden\" value=\"$dom_dir_ant\">\n";	
   echo "         <input name=\"der_num_ant\" type=\"hidden\" value=\"$der_num_ant\">\n";
   echo "         <input name=\"der_fech_ant\" type=\"hidden\" value=\"$der_fech_ant\">\n";	
   echo "         <input name=\"adq_modo_ant\" type=\"hidden\" value=\"$adq_modo_ant\">\n";
   echo "         <input name=\"adq_doc_ant\" type=\"hidden\" value=\"$adq_doc_ant\">\n";	 
   echo "         <input name=\"adq_fech_ant\" type=\"hidden\" value=\"$adq_fech_ant\">\n";	 	 	  	 	 	 	 	 	 		 	 	 
	 echo "         <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Registrar Transferencia\">\n"; 		 
	 echo "         </td>\n";
	 echo "      </tr>\n";
	 echo "      </form>\n";	*/
   # Ultima Fila 	 
   echo "      <tr height=\"100%\"></tr>\n";			 
   echo "   </table>\n";
   echo "   <br />&nbsp;<br />\n";
   echo "</td>\n";	  
?>