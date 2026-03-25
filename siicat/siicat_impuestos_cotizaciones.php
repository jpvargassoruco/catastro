<?php

$error = $aviso = false;
################################################################################
#----------------------- ACCESO DIRECTO A COTIZACIONES ------------------------#
################################################################################	
$acceso_directo = false;
if (isset($_GET["cot"])) {
   $acceso_directo = true;
	 $coti_nueva = true;
   $cot = $_GET["cot"];	 
	 if ($cot == "tapr") {
	    $reportes = false;
			$accion = "cotizaciones";
			$indicador = "tapr_ufv";
	 } elseif ($cot == "ufv") {
	    $reportes = false;
			$accion = "cotizaciones";
			$indicador = "ufv";
	 } elseif ($cot == "usd") {
	    $reportes = false;
			$accion = "cotizaciones";
			$indicador = "usd";
	 }
}
################################################################################
#-------------------------------- DEFINIR GESTION -----------------------------#
################################################################################	
if (isset($_POST["gestion"])) {
   $gestion = $_POST["gestion"];	 
} elseif ($acceso_directo) {
   $gestion = $ano_actual;
}  else $gestion = "";
################################################################################
#---------------------------- INGRESAR NUEVA COTIZACION -----------------------#
################################################################################	
$coti_nueva = false;
if (((isset($_POST["submit"])) AND ($_POST["submit"] == "Ingresar Cotización")) OR ($acceso_directo)) {
   $coti_nueva = true;
	 $valor_nuevo = "";
   $selected_dia[0] = "";	 
	 $i = 1;
	 while ($i < 32) {
	    if ($i == $dia_actual) {
	       $selected_dia[$i] = pg_escape_string('selected = "selected"');
			} else $selected_dia[$i] = ""; 
			$i++;
	 } 
	 $i = 0;
	 while ($i < 12) {
	    if ($i == $mes_actual-1) {
	       $selected_mes[$i] = pg_escape_string('selected = "selected"');
			} else $selected_mes[$i] = ""; 
			$i++;
	 }
	 $selected_ano[0] = pg_escape_string('selected = "selected"');
	 $i = 1;
	 while ($i < 10) {
      $selected_ano[$i] = ""; 
			$i++;
	 }	 
}
################################################################################
#---------------------------- INGRESAR NUEVA COTIZACION -----------------------#
################################################################################	
if (isset($_POST["guardar"]))  {
   $coti_nueva = true;
	 if (isset($_POST["dia_nuevo_valor"])) {
      $dia_nuevo_valor = $_POST["dia_nuevo_valor"];
	 } else  $dia_nuevo_valor = 1;
   $mes_nuevo_valor = $_POST["mes_nuevo_valor"];
   $ano_nuevo_valor = $_POST["ano_nuevo_valor"];
	 $gestion = $ano_nuevo_valor;
	 $fecha_nuevo_valor = $dia_nuevo_valor."/".$mes_nuevo_valor."/".$ano_nuevo_valor;
   $valor_nuevo = trim($_POST["valor_nuevo"]);	
	 $valor_nuevo = str_replace (",", "." , $valor_nuevo);
#echo "$fecha_nuevo_valor,$dia_actual<br>";	
   $selected_dia[0] = "";
   $i = 1;
   while ($i < 32) {
      if ($i == $dia_nuevo_valor) {
         $selected_dia[$i] = pg_escape_string('selected = "selected"');
			} else $selected_dia[$i] = ""; 
			$i++;
	 }
	 $i = 0;
	 while ($i < 12) {
	    if ($i == $mes_nuevo_valor-1) {
	       $selected_mes[$i] = pg_escape_string('selected = "selected"');
			} else $selected_mes[$i] = ""; 
			$i++;
	 }
   if ($ano_nuevo_valor == $ano_actual) {
	    $selected_ano[0] = pg_escape_string('selected = "selected"');
	 } $selected_ano[0] = "";     
	 $ano_anterior = $ano_actual;
	 $i = 1;
	 while ($i < 10) {
	    $ano_anterior = $ano_anterior-1;
			if ($ano_anterior == $ano_nuevo_valor) {
			   $selected_ano[$i] = pg_escape_string('selected = "selected"');
      } else $selected_ano[$i] = "";			
		  $i++;
	 }	
	 ########################################
   #-------- CHEQUEAR POR ERRORES---------#
   ########################################		  
	 if (!check_fecha ($fecha_nuevo_valor,$dia_actual,$mes_actual,$ano_actual)) {
	    $error = true;
			$mensaje_de_error = "Error: La fecha ingresada no es válida!";
	 } elseif ($valor_nuevo < 0) {
	    $error = true;
			$mensaje_de_error = "Error: El valor ingresado tiene que ser un número positivo!";
	 } elseif ($valor_nuevo > 100) {
	    $error = true;
			$mensaje_de_error = "Error: El valor ingresado es major a 100!";			
	 } elseif (($valor_nuevo != "") AND (!check_float ($valor_nuevo))) {
	    $error = true;
			$mensaje_de_error = "Error: El valor tiene que ser un número (usar PUNTO como separador de decimales)!";
	 } elseif (($valor_nuevo == "") AND ($_POST["guardar"] == "Guardar TAPR_UFV")) {
	    $error = true;
			$mensaje_de_error = "Error: No ha ingresado ningún valor!";
	 }  else {
	 ########################################
   #--- INGRESAR VALOR EN BASE DE DATOS --#
   ########################################			 	 	
      $guardar = $_POST["guardar"];	  	 	 	 
      if ($guardar == "Guardar UFV") {
			   $indicador = "ufv";
			} elseif ($guardar == "Guardar USD") {
			   $indicador = "usd";
			} else {
			   $indicador = "tapr_ufv";
			}
	    ########################################
      #--- CHEQUAER UFV NUEVO > VALOR ANT ---#
      ########################################	
	 	 	if ($indicador == "ufv") {
			   $i = 1;
				 $ufv_antiguo = 0;
				 $fecha_check = $fecha_nuevo_valor;
				 $fecha_10char = change_date_to_10char ($fecha_check);
				 $fecha_change = change_date ($fecha_10char);
         $timestamp = strtotime($fecha_change.' -1 day');
				 $fecha_check2 = date('Y-m-d', $timestamp);				 
#echo "FECHA: $fecha_check,FECHA_10char: $fecha_10char,FECHA_change: $fecha_change, TIMESTAMP: $timestamp, FECHA2: $fecha_check2<br>"; 					 
				 while (($ufv_antiguo == 0) AND ($i < 100)) {
            $sql="SELECT ufv FROM imp_cotizaciones WHERE fecha = '$fecha_check2'";		
			      $result_imp = pg_query($sql);
            $info_imp = pg_fetch_array($result_imp, null, PGSQL_ASSOC);			  
            $ufv_antiguo = trim($info_imp['ufv']);						
		        $timestamp = strtotime($fecha_check2.' -1 day'); 
				    $fecha_check2 = date('Y-m-d', $timestamp);		
#echo "FECHA: $fecha_check2, UFV_antiguo: $ufv_antiguo<br>"; 											
						$i++;
				 }
#echo "VALOR NUEVO: $valor_nuevo, VALOR ANTIGUO: $ufv_antiguo<br>"; 				 
				 $ufv_diff = $valor_nuevo - $ufv_antiguo;
				 if ($valor_nuevo < $ufv_antiguo) {
	          $aviso = true;
			      $mensaje_de_aviso = "Aviso: El valor UFV ingresado está inferior a un valor ingresado anteriormente. Compruebe si está correcto!";
				 } elseif ($ufv_diff > 1) {
	          $aviso = true;
			      $mensaje_de_aviso = "Aviso: El valor UFV ingresado parece ser muy alto. Compruebe si está correcto!";
				 }				 
			}
			########################################		
      $sql="SELECT fecha, usd, ufv, tapr_ufv FROM imp_cotizaciones WHERE fecha = '$fecha_nuevo_valor'";
	    $check_imp_cotizaciones = pg_num_rows(pg_query($sql));
		  if ($check_imp_cotizaciones > 0) {
			   if ($valor_nuevo == "") {
            $result = pg_query($sql);
					  $info = pg_fetch_array($result, null, PGSQL_ASSOC);
						$usd_from_table = $info['usd'];
						$ufv_from_table = $info['ufv'];
						$tapr_ufv_from_table = $info['tapr_ufv'];
						pg_free_result($result);											
	          if ((($indicador == "usd") AND ($ufv_from_table == "") AND ($tapr_ufv_from_table == "")) OR
						   (($indicador == "ufv") AND ($usd_from_table == "") AND ($tapr_ufv_from_table == "")) OR 
							 (($indicador == "tapr_ufv") AND ($ufv_from_table == "") AND ($usd_from_table == ""))) {
               pg_query("DELETE FROM imp_cotizaciones WHERE fecha = '$fecha_nuevo_valor'");
						} elseif (($indicador == "usd") AND ($ufv_from_table != "") AND ($tapr_ufv_from_table == "")) {
               pg_query("DELETE FROM imp_cotizaciones WHERE fecha = '$fecha_nuevo_valor'");
               pg_query("INSERT INTO imp_cotizaciones (fecha, ufv) VALUES ('$fecha_nuevo_valor','$ufv_from_table')");	
						} elseif (($indicador == "usd") AND ($ufv_from_table == "") AND ($tapr_ufv_from_table != "")) {
               pg_query("DELETE FROM imp_cotizaciones WHERE fecha = '$fecha_nuevo_valor'");
               pg_query("INSERT INTO imp_cotizaciones (fecha, tapr_ufv) VALUES ('$fecha_nuevo_valor','$tapr_ufv_from_table')");	
						} elseif (($indicador == "usd") AND ($ufv_from_table != "") AND ($tapr_ufv_from_table != "")) {
               pg_query("DELETE FROM imp_cotizaciones WHERE fecha = '$fecha_nuevo_valor'");
               pg_query("INSERT INTO imp_cotizaciones (fecha, ufv, tapr_ufv) VALUES ('$fecha_nuevo_valor','$ufv_from_table','$tapr_ufv_from_table')");
						} elseif (($indicador == "ufv") AND ($usd_from_table != "") AND ($tapr_ufv_from_table == "")) {
               pg_query("DELETE FROM imp_cotizaciones WHERE fecha = '$fecha_nuevo_valor'");
               pg_query("INSERT INTO imp_cotizaciones (fecha, usd) VALUES ('$fecha_nuevo_valor','$usd_from_table')");	
						} elseif (($indicador == "ufv") AND ($usd_from_table == "") AND ($tapr_ufv_from_table != "")) {
               pg_query("DELETE FROM imp_cotizaciones WHERE fecha = '$fecha_nuevo_valor'");
               pg_query("INSERT INTO imp_cotizaciones (fecha, tapr_ufv) VALUES ('$fecha_nuevo_valor','$tapr_ufv_from_table')");	
						} elseif (($indicador == "ufv") AND ($usd_from_table != "") AND ($tapr_ufv_from_table != "")) {
               pg_query("DELETE FROM imp_cotizaciones WHERE fecha = '$fecha_nuevo_valor'");
               pg_query("INSERT INTO imp_cotizaciones (fecha, usd, tapr_ufv) VALUES ('$fecha_nuevo_valor','$usd_from_table','$tapr_ufv_from_table')");
						}  
            $username = get_username($session_id);
		        pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		                 VALUES ('$username','$ip','$fecha','$hora','Borrado cot. $indicador','-')");  					 
				 } else {
					 
            pg_query("UPDATE imp_cotizaciones SET $indicador = '$valor_nuevo' WHERE fecha = '$fecha_nuevo_valor'");
				 }				
      } else {
			   if ($valor_nuevo == "") {
				    $aviso = false;
	          $error = true;
			      $mensaje_de_error = "Error: No ha ingresado ningún valor!";				 
         } else {			
            pg_query("INSERT INTO imp_cotizaciones (fecha, $indicador) VALUES ('$fecha_nuevo_valor','$valor_nuevo')");
			   }
			}
		#	$guardado = true;
      $coti_nueva = false;			
	 }
}
################################################################################
#------------------------- VER INDICADOR SELECCIONADO -------------------------#
################################################################################	
$selected[0] = pg_escape_string('selected = "selected"');
$selected[1] = $selected[2] = "";
$indi_tapr = false;
if ((isset($_POST["indicador"])) OR ($acceso_directo)) {
   if (isset($_POST["indicador"])) {
	    $indicador = $_POST["indicador"];
	 }
   ########################################
   #--------- INDICADOR TAPR-UFV ---------#
   ########################################	
	 if ($indicador == "tapr_ufv") {
	    $indi_tapr = true;
      $selected[2] = pg_escape_string('selected = "selected"');
      $selected[0] = $selected[1] = "";	
	    $i = 0;
      while ($i < 12) {
			   $mes = $i + 1;
			   $tapr_fecha[$i] = $gestion."-".$mes."-1";
         $sql="SELECT tapr_ufv FROM imp_cotizaciones WHERE fecha = '$tapr_fecha[$i]'";
	       $check_imp_cotizaciones = pg_num_rows(pg_query($sql));
				 if ($check_imp_cotizaciones > 0) {
            $result = pg_query($sql);
					  $info = pg_fetch_array($result, null, PGSQL_ASSOC);
	          $tapr_value[$i] = $info['tapr_ufv'];
						if ($tapr_value[$i] == "") {
					     $tapr_value[$i] = "-";
						}
						pg_free_result($result);									    
				 } else $tapr_value[$i] = "-"; 
				 $tapr_fecha[$i] = change_date_to_10char ($tapr_fecha[$i]);
#echo "TAPR-Fecha: $tapr_fecha[$i]<br>";
				 $tapr_fecha[$i] = change_date ($tapr_fecha[$i]);
				 if ($mes == 2) {	
				    $tapr_fecha_final[$i] = "28/".$mes."/".$gestion;
				 } elseif (($mes == 4) OR ($mes == 6) OR ($mes == 9) OR ($mes == 11)) {
				    $tapr_fecha_final[$i] = "30/".$mes."/".$gestion;
				 } else $tapr_fecha_final[$i] = "31/".$mes."/".$gestion;		
				 $tapr_fecha_final[$i] = change_date_to_10char ($tapr_fecha_final[$i]);				 	 
				 $i++;
			}			    
   } else {
   ########################################
   #--------- INDICADOR USD-UFV ----------#
   ########################################		 
	    if ($indicador == "usd") {
         $selected[1] = pg_escape_string('selected = "selected"');
         $selected[0] = $selected[2] = "";
			} 
	    $i = $j = 1;
	    $d = $m = 0;
	    while ($j < 13) {
	       while ($i < 32) {
						if (($j == 2) AND ($i > 28)) {
						   $check_table = 0;
						} elseif ((($j == 4) OR ($j == 6)OR ($j == 9) OR ($j == 11)) AND ($i > 30)) { 
						   $check_table = 0;
						} else {     
               $coti_fecha = $gestion."-".$j."-".$i;
						   $sql="SELECT usd, ufv FROM imp_cotizaciones WHERE fecha = '$coti_fecha'";
	             $check_table = pg_num_rows(pg_query($sql));
						}
				    if ($check_table > 0) {
               $result = pg_query($sql);
						   $info = pg_fetch_array($result, null, PGSQL_ASSOC);
	             if ($indicador == "usd") {
							    if (($info['usd'] == "") OR ($info['usd'] == "0")) {
									   $indi_value[$m][$d] = "-";
									} else $indi_value[$m][$d] = $info['usd'];
	             } else {
							    if (($info['ufv'] == "") OR ($info['ufv'] == "0")) {
									   $indi_value[$m][$d] = "-";									
									} else $indi_value[$m][$d] = $info['ufv'];
							 }
						   pg_free_result($result);	
				    } else $indi_value[$m][$d] = "-";
				    $d++;
				    $i++;
         }
			   $m++;
			   $j++;
			   $i = 1;
			   $d = 0;
      }
	 }		 
}
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	
	 echo "<td>\n";
	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
   # Fila 1
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"10%\">\n";   #Col. 1 	
   echo "            &nbsp&nbsp <a href=\"index.php?mod=$ref&id=$session_id\">\n";		
   echo "            <img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";	
	 echo "         </td>\n";	 
	     
   echo "         <td width=\"70%\" align=\"center\" valign=\"center\" height=\"40\" class=\"pageName\">\n"; 
	 echo "            Cotizaciones\n";                          
   echo "         </td>\n";
	 echo "         <td width=\"20%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";		
	
	
	 # Fila 1
   echo "      <tr>\n";    
   echo "         <td colspan=\"3\"> &nbsp</td>\n";  #Col. 1-3	 
   echo "      </tr>\n";
	 
	 # Fila 2	 
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Elegir Indicador y Gestión</legend>\n";
   echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=64&ref=$ref&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 6 TColumnas
	 echo "               <tr>\n";	 	 
	 echo "                  <td align=\"right\" width=\"11%\"> Indicador: </td>\n";   #TCol. 3 	     	  	 
	 echo "                  <td align=\"center\" width=\"18%\" class=\"bodyTextD\">\n";   #TCol. 4	  
   echo "                     <select class=\"navText\" name=\"indicador\" size=\"1\">\n";                      	 
   echo "                        <option id=\"form0\" value=\"ufv\" $selected[0]> UFV</option>\n";       
   echo "                        <option id=\"form0\" value=\"usd\" $selected[1]> USD</option>\n";
   echo "                        <option id=\"form0\" value=\"tapr_ufv\" $selected[2]> TAPR-UFV</option>\n";	 
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";	
	 echo "                  <td width=\"1%\"> &nbsp </td>\n";   #TCol. 1 	 
	 echo "                  <td align=\"right\" width=\"8%\"> Gestión: </td>\n";   #TCol. 3 	     	  	 
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextD\">\n";   #TCol. 4	  
   echo "                     <select class=\"navText\" name=\"gestion\" size=\"1\">\n";   
	 $aaaa = $ano_actual;                   	   	 
   while ($aaaa >= 2000) {      
	#		   if ($col_value == $ano_actual) {
	#			    $gestion_actual = true;
	#			 } elseif (($col_value != $ano_actual) AND (!$gestion_actual)) {
	#			    $gestion_actual = true;
	#			    echo "                   <option id=\"form0\" value=\"$ano_actual\" selected=\"selected\"> $ano_actual</option>\n";				    
	#			 }
				 if ($aaaa == $gestion) {
				    echo "                   <option id=\"form0\" value=\"$aaaa\" selected=\"selected\"> $aaaa</option>\n";  
				 } else { 	     
				    echo "                   <option id=\"form0\" value=\"$aaaa\"> $aaaa</option>\n";
				 }
				 $aaaa--;
	#    }
   } 	
   echo "                     </select>\n";	  	 
	 echo "                  </td>\n";		 	 	 	 	 	  	 	     
	 echo "                  <td align=\"center\" width=\"10%\">\n"; #TCol. 2
	 echo "                     <input name=\"accion\" type=\"hidden\" value=\"cotizaciones\">\n";	 
	 echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Ver\">\n";
	 echo "                  </td>\n";   	
	 echo "                  <td width=\"6%\"></td>\n";   #TCol. 5 
	 echo "                  <td align=\"center\" width=\"35%\">\n";   #TCol. 6  
	 echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Ingresar Cotización\">\n";	 
	 echo "                  </td>\n"; 
	 echo "               </tr>\n";
	 echo "            </table>\n"; 
	 echo "         </form>\n";	  
	 echo "         </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	 echo "      </tr>\n";
	 # Fila
   echo "      <tr height=\"15px\">\n";
	 echo "         <td colspan=\"3\"> &nbsp</td>\n";   #Col. 1 	    	 
   echo "      </tr>\n";		 
if (($gestion != "") AND (!$coti_nueva)) {
   if ($indi_tapr) {
	    echo "      <tr>\n";
      echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	    echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	    echo "         <fieldset><legend>Evolución de la Tasa Activa de Paridad Referencial en UFV en la Gestión $gestion</legend>\n";	   
	    echo "            <table border=\"0\" width=\"100%\">\n";  #TABLE 9 Columnas
	    echo "               <tr>\n";
	    echo "                  <td align=\"right\" colspan=\"5\" class=\"bodyText\"></td>\n";   #Col. 1-9	 
	    echo "               </tr>\n";	   
	    echo "               <tr>\n";  	                     
 	    echo "                  <td width=\"15%\"></td>\n";   #Col. 1	
	    echo "                  <td align=\"center\" width=\"27%\" class=\"bodyTextH\">\n";   #Col. 2	    	  	 
	    echo "                     Fecha de Vigencia\n"; 	   		
	    echo "                  </td>\n"; 
 	    echo "                  <td width=\"1%\"></td>\n";   #Col. 3	   
	    echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">\n";   #Col. 2	  
	    echo "                     TAPR-UFV\n";	 
	    echo "                  </td>\n"; 
 	    echo "                  <td width=\"45%\"></td>\n";   #Col. 5	  	  	 	 	   		 	   	 	 	    
	    echo "               </tr>\n";
	    $i = 0;
      while ($i < 12) {
         $j = $i + 1; 
	       echo "               <tr>\n";  	                     
	       echo "                  <td></td>\n";   #Col. 1	
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";  #Col. 2	    	  	 
	       echo "                     $tapr_fecha[$i] - $tapr_fecha_final[$i]\n"; 	   		
	       echo "                  </td>\n"; 
         echo "                  <td></td>\n";   #Col. 3	   
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 4	
	       echo "                     $tapr_value[$i]\n";	 
	       echo "                  </td>\n"; 
 	       echo "                  <td></td>\n";   #Col. 5	  	 	 	   		 	   	 	 	    
	       echo "               </tr>\n";
	       $i++;	 
      }
	    echo "            </table>\n";  
	    echo "          </fieldset>\n";
#     echo "         </form>\n";
	    echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3  		 
	    echo "      </tr>\n";
			echo "      <tr height=\"15px\">\n";   
	    echo "         <td> &nbsp</td>\n";   #Col. 1  					
	    echo "         <td align=\"center\"> Puede acceder a las cotizaciones TAPR-UFV a través de este\n"; #Col. 2 
			echo "            <a href=\"https://www.bcb.gob.bo/?q=otras-tablas-de-tasas&field_tipo_de_otra_tasa_value=Paridad&field_fecha_otra_tasa_value[value][year]=\" target=\"_blank\">enlace</a> del Banco Central de Bolivia.\n";
			echo "            Seleccione <b>Otras tablas de tasas</b> y <b>Tasa activa de Paridad referencial UFV</b>. Presione <b>Buscar</b>.\n"; 
			echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3  						    	 
      echo "      </tr>\n";		
	 } else {
	    if ($aviso) {
         echo "      <tr>\n"; 
         echo "         <td> &nbsp</td>\n";   #Col. 1				 
	       echo "         <td align=\"center\">\n";   #Col. 2	 			 
	       echo "            <font color=\"orange\">$mensaje_de_aviso</font> <br />\n";				 	    
		     echo "         </td>\n"; 
         echo "         <td> &nbsp</td>\n";   #Col. 3 			
         echo "      </tr>\n";
      }	 
	    echo "      <tr>\n";
      echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	    echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	    $texto = strtoupper ($indicador);
	    echo "         <fieldset><legend>Cotización del $texto en la Gestión $gestion</legend>\n";	   
	    echo "            <table border=\"0\" width=\"100%\">\n";  #TABLE 9 Columnas
	    echo "               <tr>\n";
	    echo "                  <td align=\"right\" colspan=\"13\" class=\"bodyText\"></td>\n";   #Col. 1-9	 
	    echo "               </tr>\n";	   
	    echo "               <tr>\n";  	                     
#	    echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	    echo "                  <td align=\"center\" width=\"4%\" class=\"bodyTextH\">\n";   #Col. 1	    	  	 
	    echo "                     Día\n"; 	   		
	    echo "                  </td>\n"; 
#	    echo "                  <td width=\"1%\"></td>\n";   #Col. 3	   
	    echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">\n";   #Col. 2	  
	    echo "                     Ene\n";	 
	    echo "                  </td>\n"; 
#	    echo "                  <td width=\"1%\"></td>\n";   #Col. 5	  	 
	    echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">\n";   #Col. 3 
	    echo "                     Feb\n";
	    echo "                  </td>\n";
#	    echo "                  <td width=\"1%\"></td>\n";   #Col. 7  	 
	    echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">\n";   #Col. 4 
	    echo "                     Mar\n"; 	 
	    echo "                  </td>\n";		 
#	    echo "                  <td width=\"1%\"></td>\n";   #Col. 9	  	 
	    echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">\n";   #Col. 5 
	    echo "                     Abr\n";
	    echo "                  </td>\n";	
#	    echo "                  <td width=\"1%\"></td>\n";   #Col. 11	  	 
	    echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">\n";   #Col. 6 
	    echo "                     May\n"; 
	    echo "                  </td>\n";	
#	    echo "                  <td width=\"1%\"></td>\n";   #Col. 13	
	    echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">\n";   #Col. 7 
	    echo "                     Jun\n"; 
	    echo "                  </td>\n";	
	    echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">\n";   #Col. 8 
	    echo "                     Jul\n"; 
	    echo "                  </td>\n";	
	    echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">\n";   #Col. 9 
	    echo "                     Ago\n"; 
	    echo "                  </td>\n";	
	    echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">\n";   #Col. 10 
	    echo "                     Sep\n"; 
	    echo "                  </td>\n";	
	    echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">\n";   #Col. 11 
	    echo "                     Oct\n"; 
	    echo "                  </td>\n";	
	    echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">\n";   #Col. 12 
	    echo "                     Nov\n"; 
	    echo "                  </td>\n";	
	    echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">\n";   #Col. 13 
	    echo "                     Dic\n"; 
	    echo "                  </td>\n";		 	 	 	   		 	   	 	 	    
	    echo "               </tr>\n";
	    $i = 0;
      while ($i < 31) {
         $j = $i + 1; 
	       echo "               <tr>\n";  	                     
#	       echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";  #Col. 1	    	  	 
	       echo "                     $j\n"; 	   		
	       echo "                  </td>\n"; 
#	       echo "                  <td width=\"1%\"></td>\n";   #Col. 3	   
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 2	
	       $texto = $indi_value[0][$i];  
	       echo "                     $texto\n";	 
	       echo "                  </td>\n"; 
#	       echo "                  <td width=\"1%\"></td>\n";   #Col. 5	  	 
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 3 
	       $texto = $indi_value[1][$i];  
	       echo "                     $texto\n";	
	       echo "                  </td>\n";
#	       echo "                  <td width=\"1%\"></td>\n";   #Col. 7  	 
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 4
	       $texto = $indi_value[2][$i];  
	       echo "                     $texto\n";	 	 
	       echo "                  </td>\n";		 
#	       echo "                  <td width=\"1%\"></td>\n";   #Col. 9	  	 
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 5 
	       $texto = $indi_value[3][$i];  
	       echo "                     $texto\n";	
	       echo "                  </td>\n";	
#	       echo "                  <td width=\"1%\"></td>\n";   #Col. 11	  	 
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 6 
	       $texto = $indi_value[4][$i];  
	       echo "                     $texto\n";	
	       echo "                  </td>\n";	
#	       echo "                  <td width=\"1%\"></td>\n";   #Col. 13	
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 7
	       $texto = $indi_value[5][$i];  
	       echo "                     $texto\n";	
	       echo "                  </td>\n";	
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 8 
	       $texto = $indi_value[6][$i];  
	       echo "                     $texto\n";	
	       echo "                  </td>\n";	
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 9
	       $texto = $indi_value[7][$i];  
	       echo "                     $texto\n";	
	       echo "                  </td>\n";
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 6 
	       $texto = $indi_value[8][$i];  
	       echo "                     $texto\n";	 
	       echo "                  </td>\n";	
#	       echo "                  <td width=\"1%\"></td>\n";   #Col. 13	
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 7
	       $texto = $indi_value[9][$i];  
	       echo "                     $texto\n";	 
	       echo "                  </td>\n";	
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 8 
	       $texto = $indi_value[10][$i];  
	       echo "                     $texto\n";	
	       echo "                  </td>\n";	
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 9
	       $texto = $indi_value[11][$i];  
	       echo "                     $texto\n";	
	       echo "                  </td>\n";	 		 	   		 	   	 	 	    
	       echo "               </tr>\n";
	       $i++;	 
      }
	    echo "            </table>\n";  
	    echo "          </fieldset>\n";
#     echo "         </form>\n";
	    echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	    echo "      </tr>\n"; 
			echo "      <tr height=\"15px\">\n";   
	    echo "         <td> &nbsp</td>\n";   #Col. 1  					
	    echo "         <td align=\"center\"> Puede acceder a las cotizaciones UFV/USD a través de este\n"; #Col. 2 
			echo "            <a href=\"https://www.bcb.gob.bo/\" target=\"_blank\">enlace</a> del Banco Central de Bolivia.\n";
			echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3  						    	 
      echo "      </tr>\n";				
	 } 
} elseif (($gestion != "") AND ($coti_nueva)) {
   echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
   echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
   $texto = strtoupper ($indicador);	 
   echo "         <fieldset><legend>Ingresar nueva cotización $texto</legend>\n";	 
   echo "			       <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=64&ref=$ref&id=$session_id\" accept-charset=\"utf-8\">\n";		   
   echo "            <table border=\"0\" width=\"100%\">\n";  #TABLE 10-11 Columnas
   echo "               <tr>\n";
   echo "                  <td align=\"right\" colspan=\"5\" class=\"bodyText\"></td>\n";   #Col. 1-9	 
   echo "               </tr>\n";	 	 
   echo "               <tr>\n";
   echo "                  <td align=\"right\" width=\"11%\">\n";	#Col. 1 			
   echo "                     Fecha:\n";
   echo "                  </td>\n";
	 if ($indicador != "tapr_ufv") {	 			
      echo "                  <td align=\"left\" width=\"9%\">\n"; #Col. 2 
      echo "                     <select class=\"navText\" name=\"dia_nuevo_valor\">\n";
			$i = 1;
			while ($i < 32) {
         echo "                        <option value=\"$i\" $selected_dia[$i]>$i\n";	 
				 $i++;
			}
      echo "                     </select>\n";
      echo "                  </td>\n";
      echo "                  <td align=\"left\" width=\"19%\">\n"; #Col. 2	 
	 } else {
      echo "                  <td align=\"left\" width=\"28%\">\n"; #Col. 2	 
	 } 			
   echo "                     <select class=\"navText\" name=\"mes_nuevo_valor\">\n";
   echo "                        <option value = \"1\" $selected_mes[0]>Enero</option>\n";
	 echo "                        <option value = \"2\" $selected_mes[1]>Febrero</option>\n";
	 echo "                        <option value = \"3\" $selected_mes[2]>Marzo</option>\n";
	 echo "                        <option value = \"4\" $selected_mes[3]>Abril</option>\n";
	 echo "                        <option value = \"5\" $selected_mes[4]>Mayo</option>\n";
	 echo "                        <option value = \"6\" $selected_mes[5]>Junio</option>\n";
	 echo "                        <option value = \"7\" $selected_mes[6]>Julio</option>\n";
	 echo "                        <option value = \"8\" $selected_mes[7]>Agosto</option>\n";
	 echo "                        <option value = \"9\" $selected_mes[8]>Septiembre</option>\n";
	 echo "                        <option value = \"10\" $selected_mes[9]>Octubre</option>\n";
	 echo "                        <option value = \"11\" $selected_mes[10]>Noviembre</option>\n";
	 echo "                        <option value = \"12\" $selected_mes[11]>Diciembre</option>\n";
   echo "                     </select>\n";
   echo "                  </td>\n";			
   echo "                  <td align=\"left\" width=\"12%\">\n"; #Col. 2	 
   echo "                     <select class=\"navText\" name=\"ano_nuevo_valor\">\n";
   echo "                        <option value=\"$ano_actual\" $selected_ano[0]>$ano_actual</option>\n";
   $ano_anterior = $ano_actual;
	 $i = 1;
	 while ($i < 10) {
	    $ano_anterior = $ano_anterior-1;
      echo "                        <option value=\"$ano_anterior\" $selected_ano[$i]>$ano_anterior</option>\n";
		  $i++;
	 }
   echo "                     </select>\n";
   echo "                  </td>\n";
   echo "                  <td align=\"right\" width=\"7%\">\n"; #Col. 3				
   echo "                     Valor:\n";
   echo "                  </td>\n";  
   echo "                  <td align=\"left\" width=\"17%\">\n"; #Col. 4					
   echo "                     <input name=\"valor_nuevo\" id=\"form_anadir2\" class=\"navText\" maxlength=\"7\" value=\"$valor_nuevo\">\n";
   echo "                  </td>\n";
	 echo "                  <td align=\"left\" width=\"24%\">\n";   #TCol. 6  
	 echo "                     <input name=\"gestion\" type=\"hidden\" value=\"$gestion\">\n";	 
	 echo "                     <input name=\"accion\" type=\"hidden\" value=\"cotizaciones\">\n";
	 echo "                     <input name=\"indicador\" type=\"hidden\" value=\"$indicador\">\n";	 
	 echo "                     <input name=\"submit\" type=\"hidden\" value=\"Ingresar Cotización\">\n";	 		 
	 echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Guardar $texto\">\n";	 
	 echo "                  </td>\n"; 	 
   echo "                  <td align=\"center\" width=\"1%\">\n"; #Col. 5					
   echo "                     &nbsp\n";
   echo "                  </td>\n";	 	 
   echo "               </tr>\n";			 
   echo "            </table>\n";
   echo "            </form>\n";	   
   echo "          </fieldset>\n";
#  echo "         </form>\n";
   echo "         </td>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 3 		 
   echo "      </tr>\n";
   if ($error) {
      echo "      <tr>\n"; 
      echo "         <td> &nbsp</td>\n";   #Col. 1				 
	    echo "         <td align=\"center\" height=\"20\">\n";   #Col. 2	 			 
	    echo "            <font color=\"red\">$mensaje_de_error</font> <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "         <td> &nbsp</td>\n";   #Col. 3 			
      echo "      </tr>\n";
   }
	 if ($indicador == "ufv") {	 
	 	  echo "      <tr height=\"15px\">\n";   
	    echo "         <td> &nbsp</td>\n";   #Col. 1  	
	    echo "         <td align=\"center\"> Puede acceder a las cotizaciones de UFV a través de este\n"; #Col. 2 							
			echo "            <a href=\"http://www.bcb.gob.bo/?q=indicadores/cotizaciones/ufv\" target=\"_blank\">enlace</a> del Banco Central de Bolivia.\n";
			echo "            Seleccione <b>Unidad de Fomento de Vivienda (UFV)</b> y <b>Valores de la UFV</b>. Presione <b>Buscar</b>.\n"; 
			echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3  						    	 
      echo "      </tr>\n";
	 } elseif ($indicador == "usd") {	 
	 	  echo "      <tr height=\"15px\">\n";   
	    echo "         <td> &nbsp</td>\n";   #Col. 1  	
	    echo "<td align=\"center\"> Puede acceder a las cotizaciones de USD a través de este\n"; #Col. 2 							
			echo "<a href=\"http://www.bcb.gob.bo/?q=indicadores/cotizaciones/dolar\" target=\"_blank\">enlace</a> del Banco Central de Bolivia.\n";
			echo "Seleccione <b>Bs / Dolar USA</b> y <b>Tabla anual de cotizaciones</b>. Presione <b>Buscar</b>. Elige el valor de <b>VENTA</b>\n"; 
			echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3  						    	 
      echo "      </tr>\n";			
   } elseif ($indicador == "tapr_ufv") {
			echo "      <tr height=\"15px\">\n";   
	    echo "         <td> &nbsp</td>\n";   #Col. 1  					
	    echo "         <td align=\"center\"> Puede acceder a las cotizaciones TAPR-UFV a través de este\n"; #Col. 2 
			echo "            <a href=\"http://www.bcb.gob.bo/?q=indicadores/tasas/otras\" target=\"_blank\">enlace</a> del Banco Central de Bolivia.\n";
			echo "            Seleccione <b>Otras tablas de tasas</b> y <b>Tasa activa de Paridad referencial UFV</b>. Presione <b>Buscar</b>.\n"; 
			echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3  						    	 
      echo "      </tr>\n";	 
	 }		
 /*  if ($guardado) {
      echo "      <tr>\n"; 
      echo "         <td> &nbsp</td>\n";   #Col. 1				 
	    echo "         <td align=\"center\" height=\"20\" class=\"bodyTextD\">\n";   #Col. 2	 			 
	    echo "             <br />La nueva cotización ha sido guardada en la base de datos! <br />\n";				 	    
		  echo "         </td>\n"; 
      echo "         <td> &nbsp</td>\n";   #Col. 3 			
      echo "      </tr>\n";
   }	*/ 	
      # Ultima Fila
}			 
      echo "      <tr height=\"100%\"></tr>\n";			 
      echo "   </table>\n";
      echo "   <br />&nbsp;<br />\n";
      echo "</td>\n";			 
	 	 
?>
