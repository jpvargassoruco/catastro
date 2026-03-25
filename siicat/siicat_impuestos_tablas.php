<?php

$error = false;
$tabla_escala_impositiva = false;
$tabla_materiales = false;
$tabla_valua_const = false;
$tabla_valua_const_vf = false;
$tabla_inclinacion = false;
$tabla_servicios = false;
$tabla_factores_deprec = false;
$valuacion_terreno = false;
$tabla_fecha_venc = false;
$tabla_exenciones = false;
$accion = "";

$i = 0;
while ($i < 10) {
   $selected_table[$i] = "";
	 $i++;
}

if (isset($_POST["gestion"])) {
   $gestion = $_POST["gestion"];
} else $gestion = $ano_actual-1;
$siguiente_ano = $gestion+1;
$gestion_actual = $ano_actual-1;
$no_de_gestiones = $ano_actual-2013;
$gestion_temp = $ano_actual;
$i = 0;
while ($i < $no_de_gestiones) {
   $gestion_temp = $gestion_temp-1;
   $gestion_lista[$i]	= $gestion_temp;
	 if ($gestion_temp == $gestion) {
      $selected_gestion[$i] = pg_escape_string('selected = "selected"');
   } else {
      $selected_gestion[$i] = "";
   }			
	 $i++;
}
################################################################################
#----------------------------- TABLA SELECCIONADA -----------------------------#
################################################################################	
if (isset($_POST["tabla"])) { 
   $valor = $_POST["tabla"];
   if ($valor == "imp") {
	    $tabla_escala_impositiva = true;
			$selected_table[0] = pg_escape_string('selected = "selected"');
	    $nuevos_valores = false;
			$guardado = false;
	    if ((isset($_POST["guardar"])) AND ($_POST["guardar"] == "Guardar")) { 
			   $error = false;
			   $monto = $_POST["monto"];
				 $cuota = $_POST["cuota"];
				 $mas_porc = $_POST["mas_porc"];
				 $i = 0;
				 while ($i < 4) {
				    if (($monto[$i] == "") OR ($monto[$i] < 0 ) OR (!check_numeros($monto[$i]))) {
					     $error = true;
				       $mensaje_de_error = "Error: Hay un valor erróneo en la columna 'MONTO DE VALUACION'!";
						} elseif (($cuota[$i] == "") OR ($cuota[$i] < 0 ) OR (!check_numeros($cuota[$i]))) {
					     $error = true;
				       $mensaje_de_error = "Error: Hay un valor erróneo en la columna 'CUOTA FIJA'!";						
						}  elseif (($mas_porc[$i] == "") OR ($mas_porc[$i] < 0 ) OR ($mas_porc[$i] > 100 ) OR (!check_numeros($mas_porc[$i]))) {
					     $error = true;
				       $mensaje_de_error = "Error: Hay un valor erróneo en la columna 'MAS %'!";						
						} else {
						   if ($i == 0) {
							    $exced[$i] = 1;
						   } else {
							    $exced[$i] = $monto[$i]-1;
							 }
						} 
				    $i++;
				 }	
				 if (!$error) {
				    $guardado = true;	
						$sql="SELECT monto FROM imp_escala_imp WHERE gestion = '$gestion' ORDER BY monto";
			      $check_escala_imp = pg_num_rows(pg_query($sql));
						if ($check_escala_imp > 0) {
					     pg_query("DELETE FROM imp_escala_imp WHERE gestion = '$gestion'");
						}
				    $i = 0;
				    while ($i < 4) {
						   pg_query("INSERT INTO imp_escala_imp (gestion, monto, cuota, mas_porc, exced) 
							           VALUES ('$gestion','$monto[$i]','$cuota[$i]','$mas_porc[$i]','$exced[$i]')");
						   $i++;
				    }								 
				 }		 
			}
	    if ((isset($_POST["guardar"])) AND ($_POST["guardar"] == "Modificar")) {
			   $nuevos_valores = true;
			}  
      $sql="SELECT monto, cuota, mas_porc, exced FROM imp_escala_imp WHERE gestion = '$gestion' ORDER BY monto";
			$check_escala_imp = pg_num_rows(pg_query($sql));
      # NUEVA GESTION SIN DATOS
			if ($check_escala_imp == 0) {
				 $nuevos_valores = true;	
				 $no_de_filas = 4;
				 $gestion_temp = $gestion-1;
				 $sql="SELECT monto, cuota, mas_porc, exced FROM imp_escala_imp WHERE gestion = '$gestion_temp' ORDER BY monto";
				 $check_escala_imp = pg_num_rows(pg_query($sql));	 		   			 
			} else { 	
			   $no_de_filas = $check_escala_imp;
			}
			if (!$guardado) {
			   $result = pg_query($sql);	
			   $i = $j = 0;
			   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {      
            foreach ($line as $col_value) { 
			         if ($i == 0) {
				          $monto[$j] = $col_value;
				       } elseif ($i == 1) {
				          $cuota[$j] = $col_value;
				       } elseif ($i == 2) {
				          $mas_porc[$j] = ROUND ($col_value,2);								 			    
				       } elseif ($i == 3) {
				          $exced[$j] = $col_value;	
							    $i = -1;
				       }
						   $i++;
	          }
				    $j++;
         }
			   $monto[$j] = "En adelante"; 	
         pg_free_result($result); 
	    }
	 }	
   if ($valor == "a") {
	    $tabla_materiales = true;
		  $selected_table[1] = pg_escape_string('selected = "selected"');
			$legend_tabla_a = "Vivienda Familiar";  
      $sql="SELECT * FROM imp_valua_viv_materiales ORDER BY oid";
      $result = pg_query($sql);
			$i = $j = 0;
			while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {      
         foreach ($line as $col_value) { 
			      if ($i == 0) {
				       $concepto[$j] = $col_value;    
				    } elseif ($i == 1) {
				       $material[$j] = $col_value;	
				    } else {
				       $col_value = trim($col_value);
							 if ($col_value == "lujoso") {
							    $clase[$j] = "Lujoso";
							 } elseif ($col_value == "mbueno") {
							    $clase[$j] = "Muy Bueno";
							 } elseif ($col_value == "bueno") {
							    $clase[$j] = "Bueno";
							 } elseif ($col_value == "econo") {
							    $clase[$j] = "Económico";
							 } elseif ($col_value == "mecono") {
							    $clase[$j] = "Muy Económico";	
							 } elseif ($col_value == "margin") {
							    $clase[$j] = "Marginal";							 							
               } else $clase[$j] = "-";				
							 $i = -1;						
						}
						$i++;
	       }
				 $j++;
      }
			$no_de_materiales = $j;
      pg_free_result($result);	 			 
	 }
	 #############################################################################		 		 
   if ($valor == "a1") {
	    if ((isset($_POST["guardar"])) AND ($_POST["guardar"] == "Modificar")) {		
			   $lujoso = (int) trim($_POST["lujoso"]);
				 $mbueno = (int) trim($_POST["mbueno"]);
         $bueno = (int) trim($_POST["bueno"]);
         $econo = (int) trim($_POST["econo"]);
         $mecono = (int) trim($_POST["mecono"]);
         $margin = (int) trim($_POST["margin"]);					 
				 if (($lujoso > $mbueno) AND ($mbueno > $bueno) AND ($bueno > $econo) AND ($econo > $mecono) AND ($mecono > $margin)
            AND ($lujoso > 0) AND ($mbueno > 0) AND ($bueno > 0) AND ($econo > 0) AND ($mecono > 0) AND ($margin > 0)) {	
						$sql="SELECT gestion FROM imp_valua_viv_vf WHERE gestion = '$gestion'";
			      $check_tabla = pg_num_rows(pg_query($sql));
						if ($check_tabla == 0) {
						   pg_query("INSERT INTO imp_valua_viv_vf (gestion, lujoso, mbueno, bueno, econo, mecono, margin) 
							           VALUES ('$gestion','$lujoso','$mbueno','$bueno','$econo','$mecono','$margin')");
            } else {		 
				       $sql = "UPDATE imp_valua_viv_vf SET lujoso = '$lujoso', mbueno = '$mbueno', bueno = '$bueno',
						           econo = '$econo', mecono = '$mecono', margin = '$margin' WHERE gestion = '$gestion'";	
#echo "SQL: $sql<br />";		
				       pg_query($sql);
						}
						$username = get_username($session_id);
				    $reg_accion = "Mod. Tabla Valua Viv. VF";
		        pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		                  VALUES ('$username','$ip','$fecha','$hora','$reg_accion','$gestion')");								 
				 } else {
				    $error = true;
						$mensaje_de_error = "Error: El valor de 'lujos' tiene que ser mas alto que el valor de 'muy bueno' y asi correspondiente. Tambi�n no se permite el valor 0.";
				 }		 
			}
		$tabla_valua_const = true;
		$tabla_valua_const_vf = true;
		$selected_table[2] = pg_escape_string('selected = "selected"');
		$legend_tabla_a = "Vivienda Familiar";  
		$sql="SELECT * FROM imp_valua_viv_vf WHERE gestion = '$gestion'";
		$result = pg_query($sql);
		$info = pg_fetch_array($result, null, PGSQL_ASSOC);
		$lujoso = $info['lujoso'];
		$mbueno = $info['mbueno'];
		$bueno = $info['bueno'];
		$econo = $info['econo'];
		$mecono = $info['mecono'];
		$margin = $info['margin']; 	
		pg_free_result($result);	 			 
	 }	
   if ($valor == "a2") {
	    if ((isset($_POST["guardar"])) AND ($_POST["guardar"] == "Modificar")) {		
			   $lujoso = (int) trim($_POST["lujoso"]);
				 $mbueno = (int) trim($_POST["mbueno"]);
         $bueno = (int) trim($_POST["bueno"]);
         $econo = (int) trim($_POST["econo"]);
				 if (($lujoso >= 0) AND ($mbueno >= 0) AND ($bueno >= 0) AND ($econo >= 0)) {
						$sql="SELECT gestion FROM imp_valua_viv_ph WHERE gestion = '$gestion'";
			      $check_tabla = pg_num_rows(pg_query($sql));
						if ($check_tabla == 0) {
						   pg_query("INSERT INTO imp_valua_viv_ph (gestion, lujoso, mbueno, bueno, econo) 
							           VALUES ('$gestion','$lujoso','$mbueno','$bueno','$econo')");
            } else {
               pg_query("UPDATE imp_valua_viv_ph SET lujoso = '$lujoso', mbueno = '$mbueno', bueno = '$bueno',
						          econo = '$econo' WHERE gestion = '$gestion'");	
						}							
				    $username = get_username($session_id);
				    $reg_accion = "Mod. Tabla Valua Viv. PH";
		        pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		                  VALUES ('$username','$ip','$fecha','$hora','$reg_accion','$gestion')");								 
				 }		 
			}	 
	    $tabla_valua_const = true;
			$selected_table[3] = pg_escape_string('selected = "selected"');
			$legend_tabla_a = "Propiedad Horizontal";			 
      $sql="SELECT * FROM imp_valua_viv_ph WHERE gestion = '$gestion'";
      $result = pg_query($sql);
      $info = pg_fetch_array($result, null, PGSQL_ASSOC);
      $lujoso = $info['lujoso'];
      $mbueno = $info['mbueno'];
      $bueno = $info['bueno'];
      $econo = $info['econo']; 	
      pg_free_result($result);	 			 
	 }	
	 #############################################################################		 	  
   if ($valor == "b") {
      $tabla_inclinacion = true;  
			$selected_table[4] = pg_escape_string('selected = "selected"');
			if (isset($_POST["guardar"])) {
			   $tabla_cambiada = false;
			   $fact_terr_plano = $_POST["fact_terr_plano"];
			   if (($fact_terr_plano != "") AND ($fact_terr_plano < 10) AND (check_numeros ($fact_terr_plano))) {
				    pg_query("UPDATE imp_fact_inclinacion SET plano = '$fact_terr_plano' WHERE gestion = '$gestion'");	
						$tabla_cambiada = true;			  
			   }
			   $fact_terr_incl = $_POST["fact_terr_incl"];
			   if (($fact_terr_incl != "") AND ($fact_terr_incl < 10) AND (check_numeros ($fact_terr_incl))) {
				    pg_query("UPDATE imp_fact_inclinacion SET inclinado = '$fact_terr_incl' WHERE gestion = '$gestion'");		
						$tabla_cambiada = true;									  
			   }
			   $fact_terr_minc = $_POST["fact_terr_minc"];
			   if (($fact_terr_minc != "") AND ($fact_terr_minc < 10) AND (check_numeros ($fact_terr_minc))) {
				    pg_query("UPDATE imp_fact_inclinacion SET muy_inclinado = '$fact_terr_minc' WHERE gestion = '$gestion'");	
						$tabla_cambiada = true;										  
			   }	
				 if ($tabla_cambiada) {
						$username = get_username($session_id);
				    $reg_accion = "Factores Inclinacion modificadas";
		        pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		                  VALUES ('$username','$ip','$fecha','$hora','$reg_accion','-')");					 
				 }			 
			}   
      $sql="SELECT * FROM imp_fact_inclinacion WHERE gestion = '$gestion'";
			$check_incl = pg_num_rows(pg_query($sql));
      # NUEVA GESTION SIN DATOS
			if ($check_incl == 0) {
				 $actualizar_tabla = actualizar_tabla ("imp_fact_inclinacion", $gestion, "1"); 		   			 
			}				
      $result = pg_query($sql);
      $info = pg_fetch_array($result, null, PGSQL_ASSOC);
      $terreno[0] = $info['plano'];
      $terreno[1] = $info['semiplano'];
			$terreno[2] = $info['inclinado'];
      $terreno[3] = $info['muy_inclinado'];
      $terreno[4] = $info['barranco'];
			pg_free_result($result);		 
	 }
	 #############################################################################		 
   if ($valor == "c") {
      $tabla_servicios = true;  
			$selected_table[5] = pg_escape_string('selected = "selected"'); 
      $sql="SELECT * FROM imp_fact_servicios WHERE gestion = '$gestion'";
			$check_servicios = pg_num_rows(pg_query($sql));
      # NUEVA GESTION SIN DATOS
			if ($check_servicios == 0) {
				 $actualizar_tabla = actualizar_tabla ("imp_fact_servicios", $gestion,"1");
#			   $check_escala_imp = pg_num_rows(pg_query($sql));		 		   			 
			}	 			
      $result = pg_query($sql);
      $info = pg_fetch_array($result, null, PGSQL_ASSOC);
      $serv_luz = $info['serv_luz'];
      $serv_agua = $info['serv_agua'];
      $serv_alc = $info['serv_alc'];
      $serv_tel = $info['serv_tel'];
      $serv_min = $info['serv_min'];
      $serv_serv = $info['serv_serv']; 	
      pg_free_result($result);	 			 
	 }	
	 #############################################################################		  
	 if ($valor == "d") {
	    $tabla_factores_deprec = true;
		  $selected_table[6] = pg_escape_string('selected = "selected"'); 
      $sql="SELECT antig, factor FROM imp_fact_deprec WHERE gestion = '$gestion' ORDER BY antig";
			$check_fact_deprec = pg_num_rows(pg_query($sql));
      # NUEVA GESTION SIN DATOS
			if ($check_fact_deprec == 0) {
				 $actualizar_tabla = actualizar_tabla ("imp_fact_deprec", $gestion,"1");
			   $check_fact_deprec = pg_num_rows(pg_query($sql));		 		   			 
			}	 
			$no_de_filas = $check_fact_deprec;	
			$result = pg_query($sql);	
			$antig[0] = $factor[0] = 0;		
			$i = 0;
			$j = 1;
			while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {      
         foreach ($line as $col_value) { 
			      if ($i == 0) {
				       $antig[$j] = $col_value;    
				    } elseif ($i == 1) {
				       $factor[$j] = $col_value;	
							 $i = -1;
				    }
						$i++;
	       }
				 $j++;
      }
			$antig[$j] = "En adelante"; 	
      pg_free_result($result);	 
	 }
	 #############################################################################		
   if ($valor == "e") {
	    if ((isset($_POST["guardar"])) AND ($_POST["guardar"] == "Modificar")) {		
			   $zona_new = trim($_POST["zona_new"]);
				 $asf_new = (float) trim($_POST["asf_new"]);
         $adq_new = (float) trim($_POST["adq_new"]);
         $cem_new = (float) trim($_POST["cem_new"]);
         $los_new = (float) trim($_POST["los_new"]);
         $pdr_new = (float) trim($_POST["pdr_new"]);	
         $rip_new = (float) trim($_POST["rip_new"]);
         $trr_new = (float) trim($_POST["trr_new"]);
         $lad_new = (float) trim($_POST["lad_new"]);	
#echo "GESTION:$gestion,$ufv,$zona_new,$asf_new,$adq_new,$cem_new,$los_new,$pdr_new,$rip_new,$trr_new,$lad_new";			
         if (($zona_new != "") AND ($asf_new == 0) AND ($adq_new == 0) AND ($cem_new == 0) AND ($los_new == 0) 
				 AND ($pdr_new == 0) AND ($rip_new == 0) AND ($trr_new == 0) AND ($lad_new == 0)) {	
						$sql="SELECT zona FROM imp_fact_zona WHERE gestion = '$gestion' AND zona = '$zona_new'";
						$check_zona = pg_num_rows(pg_query($sql));	
						if ($check_zona > 0) {
						   pg_query("DELETE FROM imp_fact_zona WHERE gestion = '$gestion' AND zona = '$zona_new'");
						}			 
				 } elseif (($zona_new != "") AND ($asf_new >= 0) AND ($adq_new >= 0) AND ($cem_new >= 0) AND ($los_new >= 0) 
				 AND ($pdr_new >= 0) AND ($rip_new >= 0) AND ($trr_new >= 0) AND ($lad_new >= 0)) {	
					
						$sql="SELECT zona FROM imp_fact_zona WHERE gestion = '$gestion' AND zona = '$zona_new'";
            $check_zona = pg_num_rows(pg_query($sql));	
						if ($check_zona > 0) {				 
				       pg_query("UPDATE imp_fact_zona SET asf = '$asf_new', adq = '$adq_new', cem = '$cem_new',
						          los = '$los_new', pdr = '$pdr_new', rip = '$rip_new', trr = '$trr_new', lad = '$lad_new' 
											WHERE gestion = '$gestion' AND zona = '$zona_new'");	
						} else {
							 $fecha_temp = $gestion."-12-31";
						   $ufv = get_coti_de_hoy ($fecha_temp, "ufv");
#echo " GESTION: $gestion, UFV: $ufv, ZONA: $zona_new";								 
						   pg_query("INSERT INTO imp_fact_zona (gestion, ufv, zona, asf, adq, cem, los, pdr, rip, trr, lad) 
		                  VALUES ('$gestion','$ufv','$zona_new','$asf_new','$adq_new','$cem_new','$los_new','$pdr_new','$rip_new','$trr_new','$lad_new')");
						}		
				    $username = get_username($session_id);
				    $reg_accion = "Mod. Tabla E. Valua. de Terrenos";
		        pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		                  VALUES ('$username','$ip','$fecha','$hora','$reg_accion','$gestion')");								 
				 }		 
			}
      $valuacion_terreno = true; 
			$selected_table[7] = pg_escape_string('selected = "selected"');	 
      $sql="SELECT ufv,zona,asf,adq,cem,los,pdr,rip,trr,lad FROM imp_fact_zona WHERE gestion = '$gestion'";
      $check_fact_zona = pg_num_rows(pg_query($sql));
      # NUEVA GESTION SIN DATOS
			if ($check_fact_zona == 0) {
				 $gestion_ant = $gestion-1;
			   $ufv = imp_getcoti ($gestion."-12-31","ufv");
				 $ufv_ant = imp_getcoti($gestion_ant."-12-31","ufv");
				 $factor_ufv = $ufv/$ufv_ant;
				 $actualizar_tabla = actualizar_tabla ("imp_fact_zona", $gestion, $factor_ufv);
			   $check_fact_zona = pg_num_rows(pg_query($sql));		 		   			 
			}	
			$no_de_zonas = $check_fact_zona; 				
      $result = pg_query($sql);
      $i = $j = 0;
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
         foreach ($line as $col_value) {
	          if ($i == 0) { $ufv[$j] = $col_value; 	 
	          } elseif ($i == 1) { $zona[$j] = $col_value; 
			      } elseif ($i == 2) { $asf[$j] = $col_value; 
			      } elseif ($i == 3) { $adq[$j] = $col_value; 
			      } elseif ($i == 4) { $cem[$j] = $col_value; 
			      } elseif ($i == 5) { $los[$j] = $col_value; 
			      } elseif ($i == 6) { $pdr[$j] = $col_value; 
			      } elseif ($i == 7) { $rip[$j] = $col_value;
			      } elseif ($i == 8) { $trr[$j] = $col_value; 
			      } else { 
			         $lad[$j] = $col_value;
				       $i = -1;
			      }
			      $i++;
         }
	       $j++;
      } 			
      pg_free_result($result);				   			 
	 }
	 #############################################################################		 
   if ($valor == "f") {
	    $tabla_fecha_venc = true;
			$aviso_fech_color = "";
			$selected_table[8] = pg_escape_string('selected = "selected"'); 
			if (isset($_POST['borrar_venc'])) {
			   pg_query("DELETE FROM imp_fecha_venc WHERE gestion = '$gestion'");
			}
			if (isset($_POST['borrar_mod1'])) {
			   pg_query("UPDATE imp_fecha_venc SET fecha_mod1 = NULL WHERE gestion = '$gestion'");
			}
			if (isset($_POST['borrar_mod2'])) {
			   pg_query("UPDATE imp_fecha_venc SET fecha_mod2 = NULL WHERE gestion = '$gestion'");
			}
			if (isset($_POST['borrar_mod3'])) {
			   pg_query("UPDATE imp_fecha_venc SET fecha_mod3 = NULL WHERE gestion = '$gestion'");
			}
			$aviso_fech_venc = false;									
		  if (isset($_POST['fecha'])) {
			   $nueva_fecha = $_POST['fecha'];
				 $nueva_fecha = change_date (change_date_to_10char($nueva_fecha));
				 $prox_ano = $gestion+1;
				 $check_fecha = $prox_ano."-12-31";
				 $check_fecha2 = change_date ($check_fecha);
#echo "NUEVA: $nueva_fecha, CHECK $check_fecha<br>";				 
				 if ($nueva_fecha > $check_fecha) {
				    $aviso_fech_venc = true;
						$aviso_fech_color = "orange";						
						$aviso_fech_venc_mensaje = "Aviso: La fecha de vencimiento de la gestión $gestion no debería pasar el $check_fecha2";
				 }
         $sql="SELECT fecha_venc, fecha_mod1, fecha_mod2, fecha_mod3 FROM imp_fecha_venc WHERE gestion = '$gestion'";
			   $fila_con_fechas = pg_num_rows(pg_query($sql));	
         $result = pg_query($sql);
         $info = pg_fetch_array($result, null, PGSQL_ASSOC);				 			 
				 if (check_fecha($nueva_fecha,"31","12",$ano_actual+10)) {
				    if (isset($_POST['ingresar_venc'])) {
					     pg_query("INSERT INTO imp_fecha_venc (gestion, fecha_venc) VALUES ('$gestion','$nueva_fecha')");					 
						}
				    if (isset($_POST['ingresar_mod1'])) {
						   $check1 = $info['fecha_venc'];
						   if ($nueva_fecha <= $check1) {
							    $aviso_fech_color = "red";
							 } else {   
					        pg_query("UPDATE imp_fecha_venc SET fecha_mod1 = '$nueva_fecha' WHERE gestion = '$gestion'");
						   }
						}	
				    if (isset($_POST['ingresar_mod2'])) {
						   $check2 = $info['fecha_mod1'];
						   if ($nueva_fecha <= $check2) {
							    $aviso_fech_color = "red";
							 } else {						
					        pg_query("UPDATE imp_fecha_venc SET fecha_mod2 = '$nueva_fecha' WHERE gestion = '$gestion'");
							 }		
						}	
				    if (isset($_POST['ingresar_mod3'])) {
						   $check3 = $info['fecha_mod2'];
						   if ($nueva_fecha <= $check3) {
							    $aviso_fech_color = "red";
							 } else {							
					        pg_query("UPDATE imp_fecha_venc SET fecha_mod3 = '$nueva_fecha' WHERE gestion = '$gestion'");
							 }
						}				
						$username = get_username($session_id);
				    $reg_accion = "Ingresar Fecha Venc.";
		        pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		                  VALUES ('$username','$ip','$fecha','$hora','$reg_accion','-')");																	 
				 } else {
				    $aviso_fech_color = "red";
				 }
				 if ($aviso_fech_color == "red") {
				    $aviso_fech_venc = true;
						$aviso_fech_venc_mensaje = "Error: La fecha ingresada no es válida";				 
				 }
				 pg_free_result($result);	 
			}	 				
      $sql="SELECT fecha_venc, fecha_mod1, fecha_mod2, fecha_mod3 FROM imp_fecha_venc WHERE gestion = '$gestion'";
			$fila_con_fechas = pg_num_rows(pg_query($sql));	
      $result = pg_query($sql);
      $info = pg_fetch_array($result, null, PGSQL_ASSOC);
			if ($info['fecha_venc'] == "") {
			   $fecha_venc = "-";
      } else $fecha_venc = change_date ($info['fecha_venc']);
			if ($info['fecha_mod1'] == "") {
			   $fecha_mod1 = "-";
      } else $fecha_mod1 = change_date ($info['fecha_mod1']);	
			if ($info['fecha_mod2'] == "") {
			   $fecha_mod2 = "-";
      } else $fecha_mod2 = change_date ($info['fecha_mod2']);	
			if ($info['fecha_mod3'] == "") {
			   $fecha_mod3 = "-";
      } else $fecha_mod3 = change_date ($info['fecha_mod3']);							
      pg_free_result($result);	 	
	 }
	 #############################################################################	
   if ($valor == "g") {
      $tabla_exenciones = true;	 
		  $selected_table[9] = pg_escape_string('selected = "selected"');
			$anadir = $modificar = $borrar = $error = false;
		  if ((isset($_POST["guardar"])) AND ($_POST["guardar"] == "Borrar")) {	 
			   $no_select_borrar = $_POST["no_select"];
         $sql="SELECT numero FROM imp_exenciones ORDER BY fecha";				 
         $no_de_filas = pg_num_rows(pg_query($sql));				 
				 pg_query("DELETE FROM imp_exenciones WHERE numero = '$no_select_borrar'");
				 #while ($no_select_borrar < $no_de_filas) {
				 #   $no_sig = $no_select_borrar+1;
				 #   pg_query("UPDATE imp_exenciones SET numero = $no_select_borrar WHERE numero = '$no_sig'");
				 #   $no_select_borrar++;
				 #}	   					
			}	elseif (isset($_POST["guardar2"])) {
			   $gestion = $gestion_actual;
			   $ley_temp = trim($_POST["ley"]);
				 $fecha_exen_temp = trim($_POST["fecha_exen"]);
				 $descripcion_temp = trim($_POST["descripcion"]);
				 $porcentaje_temp = trim($_POST["porcentaje"]);
				 $no_select = $_POST["no_select"];
				 if (!check_fecha ($fecha_exen_temp,$dia_actual,$mes_actual,$ano_actual)) {
				    $error = true;
						$mensaje_de_error = "Error: El formato de la fecha no es correcto. Formatos válidos son DD/MM/AAAA o AAAA-MM-DD";
				 } elseif (!check_float ($porcentaje_temp)) {
				    $error = true;
						$mensaje_de_error = "Error: El porcentaje tiene que ser un número entre 0 y 100 (Decimales con punto!)";
				 } elseif ($porcentaje_temp == "") {
				    $error = true;
						$mensaje_de_error = "Error: Tiene que especificar un porcentaje de exención entre 0 y 100%!";
				 } else {				 
				    if ($_POST["guardar2"] == "Añadir") { 
				       $sql="SELECT numero FROM imp_exenciones ORDER BY numero DESC LIMIT 1";
               $check_exenciones = pg_num_rows(pg_query($sql));
							 if ($check_exenciones == 0) {
							    $numero_temp = 1;
							 } else {
							    $result = pg_query($sql);
                  $info = pg_fetch_array($result, null, PGSQL_ASSOC);
			            $numero_temp = $info['numero']; 
						      $numero_temp++;
							 }
				       pg_query("INSERT INTO imp_exenciones (gestion, numero, ley, fecha, descripcion, porcentaje) 
				              VALUES ('$gestion','$numero_temp','$ley_temp','$fecha_exen_temp','$descripcion_temp','$porcentaje_temp')");
						} else {
				       pg_query("UPDATE imp_exenciones SET gestion = '$gestion', ley = '$ley_temp', fecha = '$fecha_exen_temp', 
						          descripcion = '$descripcion_temp', porcentaje = '$porcentaje_temp' 
											WHERE numero = '$no_select'");	 
				    }
			   }
			}
      $sql="SELECT numero, ley, fecha, descripcion, porcentaje FROM imp_exenciones ORDER BY fecha, ley";
      $check_exenciones = pg_num_rows(pg_query($sql));
			if ($check_exenciones == 0) {					
			   $no_de_filas = 0;
			} else {
			   $no_de_filas = $check_exenciones;
         $result = pg_query($sql);
         $i = $j = 0;
         while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            foreach ($line as $col_value) {
	             if ($i == 0) { $numero[$j] = $col_value;						
	             } elseif ($i == 1) { $ley[$j] = utf8_decode($col_value);
		           } elseif ($i == 2) { 
							    $fecha_exen[$j] = $col_value;
									$fecha_exen[$j] = change_date ($fecha_exen[$j]); 
			         } elseif ($i == 3) { $descripcion[$j] = utf8_decode($col_value);
			         } else { 
			           $porcentaje[$j] = $col_value;
				         $i = -1;
			         }
			         $i++;						 
            }
	          $j++;
         } 			
         pg_free_result($result);							  					 
			}	
			if (isset($_POST["guardar"])) {
			   if ($_POST["guardar"] == "Añadir") {
			      $anadir = true;
				    $exen_accion = "Añadir";
			      $no_select = $ley_mod = $descripcion_mod = $porcentaje_mod = "";
						$fecha_exen_mod = $fecha2; 
				 } elseif ($_POST["guardar"] == "Modificar") {
			      $modificar = true;
				    $exen_accion = "Modificar";				 
				    $no_select = $_POST["no_select"];
						$sql="SELECT ley, fecha, descripcion, porcentaje FROM imp_exenciones WHERE numero = '$no_select'";
            $result = pg_query($sql);
            $info = pg_fetch_array($result, null, PGSQL_ASSOC);
			      $ley_mod = utf8_decode($info['ley']);
						$fecha_exen_mod = change_date ($info['fecha']);
						$descripcion_mod = utf8_decode($info['descripcion']);
						$porcentaje_mod = $info['porcentaje'];   
				 }		  				 	
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
echo "         <td width=\"10%\"> &nbsp</td>\n";   #Col. 1 	    
echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"70%\" class=\"pageName\">\n"; 
echo "            Tablas\n";                          
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
echo "         <fieldset><legend>Elegir gestión y Tabla</legend>\n";
echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
echo "            <table border=\"0\" width=\"100%\">\n";   # 6 TColumnas
echo "               <tr>\n";	
echo "                  <td align=\"right\" width=\"11%\"> gestión: </td>\n";   #TCol. 3 	     	  	 
echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextD\">\n";   #TCol. 4	  
echo "                     <select class=\"navText\" name=\"gestion\" size=\"1\">\n";       
$i = 0;
while ($i < $no_de_gestiones) {               	 
   echo "                        <option id=\"form0\" value=\"$gestion_lista[$i]\" $selected_gestion[$i]> $gestion_lista[$i]</option>\n";				    
	 $i++;
}
echo "                     </select>\n";	  	 
echo "                  </td>\n";			  	 
echo "                  <td align=\"right\" width=\"8%\"> Tabla: </td>\n";   #TCol. 3 	     	  	 
echo "                  <td align=\"center\" width=\"52%\" class=\"bodyTextD\">\n";   #TCol. 4	  
echo "                     <select class=\"navText\" name=\"tabla\" size=\"1\">\n";                      	 
echo "                        <option id=\"form0\" value=\"imp\" $selected_table[0]> Escala Impositiva</option>\n";  
echo "                        <option id=\"form0\" value=\"a\" $selected_table[1]> A. Materiales de Construcción</option>\n";     
echo "                        <option id=\"form0\" value=\"a1\" $selected_table[2]> A1. Valuación de Construcciones - VF</option>\n";
echo "                        <option id=\"form0\" value=\"a2\" $selected_table[3]> A2. Valuación de Construcciones - PH</option>\n";
echo "                        <option id=\"form0\" value=\"b\" $selected_table[4]> B. Inclinación del Terreno</option>\n";       
echo "                        <option id=\"form0\" value=\"c\" $selected_table[5]> C. Existencia de Servicios</option>\n";
echo "                        <option id=\"form0\" value=\"d\" $selected_table[6]> D. Antiguedad de las Construcciones</option>\n";
echo "                        <option id=\"form0\" value=\"e\" $selected_table[7]> E. Valuación de Terrenos</option>\n";       
echo "                        <option id=\"form0\" value=\"f\" $selected_table[8]> F. Plazos de Vencimiento</option>\n";	
echo "                        <option id=\"form0\" value=\"g\" $selected_table[9]> G. Exenciones</option>\n";	 	 	 	 
echo "                     </select>\n";	  	 
echo "                  </td>\n";	
echo "                  <td width=\"1%\"> &nbsp </td>\n";   #TCol. 1 	  	  	 	     
echo "                  <td align=\"center\" width=\"11%\">\n"; #TCol. 2
echo "                     <input name=\"accion\" type=\"hidden\" value=\"tablas\">\n";	 
echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Ver\">\n";
echo "                  </td>\n";   	
echo "                  <td width=\"6%\"></td>\n";   #TCol. 5 
#	 echo "                  <td align=\"center\" width=\"35%\">\n";   #TCol. 6  
#	 echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Ingresar Cotizaci�n\">\n";	 
#	 echo "                  </td>\n"; 
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
################################################################################ 
if ($tabla_escala_impositiva) {	 # TABLA IMP
	 # Fila 4 
   if (($nuevos_valores) AND ($nivel >= 4)) {
      echo "			 <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";		 
	 }
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Escala Impositiva gestión $gestion</legend>\n";	   
	 echo "            <table border=\"0\" width=\"100%\">\n";  #TABLE 11 Columnas   
	 echo "               <tr>\n"; 
	 echo "                  <td></td>\n";   #Col. 1		  	                     
	 echo "                  <td align=\"center\" colspan=\"3\" class=\"bodyTextH\">\n";   #Col. 2-4	    	  	 
	 echo "                     MONTO DE VALUACION (EN BS.)<br />DESDE &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp HASTA\n"; 	   		
	 echo "                  </td>\n"; 
	 echo "                  <td></td>\n";   #Col. 5	   
	 echo "                  <td align=\"center\" class=\"bodyTextH\">\n";   #Col. 6	  
	 echo "                     CUOTA FIJA<br />(EN BS.)\n";	 
	 echo "                  </td>\n"; 
	 echo "                  <td></td>\n";   #Col. 7 	 
	 echo "                  <td align=\"center\" class=\"bodyTextH\">\n";   #Col. 8 
	 echo "                     MAS<br />%\n";
	 echo "                  </td>\n";
   echo "                  <td></td>\n";   #Col. 9
	 echo "                  <td align=\"center\" class=\"bodyTextH\">\n";   #Col. 10 
	 echo "                     S/EXCEDENTE<br />(EN BS.)\n";
	 echo "                  </td>\n";
   echo "                  <td></td>\n";   #Col. 11	 		 	   		 	   	 	 	    
	 echo "               </tr>\n";
	 $i = 0;
	 while ($i < $no_de_filas) {
	    $j = $i+1;   
	    echo "               <tr>\n";  	                     
	    echo "                  <td width=\"5%\"></td>\n";   #Col. 1		  	                     
	    echo "                  <td align=\"center\" width=\"21%\" class=\"bodyTextD\">\n";   #Col. 2	
			if ($nuevos_valores) {
			   echo "                     <input name=\"monto[$i]\" id=\"form_anadir2\" class=\"navText\" maxlength=\"8\" value=\"$monto[$i]\">\n"; 		
			} else {  	  	 
	       echo "                     $monto[$i]\n"; 
			}	   		
	    echo "                  </td>\n"; 
	    echo "                  <td width=\"1%\"></td>\n";   #Col. 3	   
	    echo "                  <td align=\"center\" width=\"21%\" class=\"bodyTextD\">\n";   #Col. 4  
			if ($nuevos_valores) {
			   echo "                     <input name=\"temp[$i]\" id=\"form_anadir2\" class=\"navText\" disabled=\"disabled\" value=\"\">\n";		
			} else {
			   if ($i == 3) {
	          echo "                     En adelante\n";				 
				 } else {
			      $monto_temp = $monto[$j]-1;  	  	 
	          echo "                     $monto_temp\n";
				 } 
			}	
	    echo "                  </td>\n"; 
	    echo "                  <td width=\"1%\"></td>\n";   #Col. 5  	 
	    echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextD\">\n";   #Col. 6 
			if ($nuevos_valores) {
			   echo "                     <input name=\"cuota[$i]\" id=\"form_anadir2\" class=\"navText\" maxlength=\"6\" value=\"$cuota[$i]\">\n";	
			} else {  	  	 
	       echo "                     $cuota[$i]\n"; 
			}				
	    echo "                  </td>\n";
	    echo "                  <td width=\"1%\"></td>\n";   #Col. 7  	 
	    echo "                  <td align=\"center\" width=\"14%\" class=\"bodyTextD\">\n";   #Col. 8
			if ($nuevos_valores) {
			   echo "                     <input name=\"mas_porc[$i]\" id=\"form_anadir2\" class=\"navText\" maxlength=\"5\" value=\"$mas_porc[$i]\">\n";			
			} else {  	  	 
	       echo "                     $mas_porc[$i]\n"; 
			}				 
	    echo "                     \n"; 	 
	    echo "                  </td>\n";	
      echo "                  <td width=\"1%\"></td>\n";   #Col. 9		
	    echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextD\">\n";   #Col. 8 
			if ($nuevos_valores) {
			   echo "                     <input name=\"temp[$i]\" id=\"form_anadir2\" class=\"navText\" disabled=\"disabled\" value=\"\">\n";		
			} else {  	  	 
	       echo "                     $exced[$i]\n"; 
			}				
	    echo "                     \n"; 	 
	    echo "                  </td>\n";	
      echo "                  <td width=\"5%\"></td>\n";   #Col. 9								 	   		 	   	 	 	    
	    echo "               </tr>\n";
			$i++;
	 }
	 echo "            </table>\n";  
	 echo "          </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 	   		
	 echo "      </tr>\n";	
	 if ($error) {
	 	  echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" colspan=\"3\">\n";   #Col. 1-3
      echo "            <font color=\"red\">$mensaje_de_error</font>\n";	 
	    echo "         </td>\n";	 	 	   		
	    echo "      </tr>\n";	
   }
   if (($nuevos_valores) AND ($nivel >= 4)) {
	 	 	echo "      <tr>\n";
      echo "         <td> &nbsp</td>\n";   #Col. 1  			 	 
	    echo "         <td align=\"center\">\n";   #Col. 2
      echo "            <font color=\"orange\">No existen valores guardados en la base de datos. Por favor, ingrese los valores de la gestión actual!</font>\n";	 
	    echo "         </td>\n";	
	    echo "         <td> &nbsp</td>\n";   #Col. 3 				 	 	   		
	    echo "      </tr>\n";	
	    echo "      <tr>\n";
      echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	    echo "         <td align=\"center\" height=\"40\">\n";   #Col. 2
      echo "            <input name=\"gestion\" type=\"hidden\" value=\"$gestion\">\n";
      echo "            <input name=\"tabla\" type=\"hidden\" value=\"$valor\">\n";
      echo "            <input name=\"accion\" type=\"hidden\" value=\"$accion\">\n";	
      echo "            <input name=\"submit\" type=\"hidden\" value=\"Ver\">\n";											
      echo "            <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Guardar\">\n";		 
	    echo "         </td>\n";	 
	    echo "         <td> &nbsp</td>\n";   #Col. 3 		 	   		
	    echo "      </tr>\n";	
	    echo "      </form>\n";				 	 
	 } elseif (($gestion == $gestion_actual) AND ($nivel >= 4)) {
      echo "			<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";		 
	    echo "      <tr>\n";
      echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	    echo "         <td align=\"center\" height=\"40\">\n";   #Col. 2
      echo "            <input name=\"gestion\" type=\"hidden\" value=\"$gestion\">\n";
      echo "            <input name=\"tabla\" type=\"hidden\" value=\"$valor\">\n";
      echo "            <input name=\"accion\" type=\"hidden\" value=\"$accion\">\n";	
      echo "            <input name=\"submit\" type=\"hidden\" value=\"Ver\">\n";											
      echo "            <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Modificar\">\n";		 
	    echo "         </td>\n";	 
	    echo "         <td> &nbsp</td>\n";   #Col. 3 		 	   		
	    echo "      </tr>\n";	
	    echo "      </form>\n";
   }	 	  	 
}	 
################################################################################ 	 	 
if ($tabla_materiales) {	 # TABLA A 
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Materiales de Construcción</legend>\n";	   
	 echo "            <table border=\"0\" width=\"100%\">\n";  #TABLE 7 Columnas   
	 echo "               <tr>\n"; 
	 echo "                  <td width=\"10%\"></td>\n";   #Col. 1		  	                     
	 echo "                  <td align=\"center\" width=\"20%\" class=\"bodyTextH\">\n";   #Col. 2    	  	 
	 echo "                     CONCEPTO\n"; 	   		
	 echo "                  </td>\n"; 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 3	   
	 echo "                  <td align=\"center\" width=\"20%\" class=\"bodyTextH\">\n";   #Col. 4	  
	 echo "                     MATERIAL\n";	 
	 echo "                  </td>\n"; 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 5	 
   echo "                  <td align=\"center\" width=\"16%\" class=\"bodyTextH\">\n";   #Col. 6	
	 echo "                     CLASE\n";
	 echo "                   </td>\n";
	 echo "                  <td width=\"9%\"></td>\n";   #Col. 7	  	   		 	   	 	 	    
	 echo "               </tr>\n";
	 $i = 0;
	 while ($i < $no_de_materiales) {
	    $j = $i-1;   
	    echo "               <tr>\n"; 
	    echo "                  <td></td>\n";   #Col. 1					 	                     
			if ($i != 0) {
			   if ($concepto[$i] == $concepto[$j]) { 	                     
	          echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 2					 
				    $texto = ""; 
				 } else { 	                     
	          echo "                  <td align=\"center\" colspan=\"5\" >\n";   #Col. 2-6					 
				    echo "                  <hr length=100%>\n";
	          echo "                  </td>\n";
            echo "                  <td></td>\n";   #Col. 7													 
	          echo "               </tr>\n";				 
	          echo "               <tr>\n";  	                     
	          echo "                  <td></td>\n";   #Col. 1		  	                     
	          echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 2					 
				    $texto = utf8_decode (abr($concepto[$i]));
				 }
			} else {  	                     
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 2					
			   $texto = utf8_decode (abr($concepto[$i]));
			} 	  	 
	    echo "                     $texto\n"; 	   		
	    echo "                  </td>\n"; 
	    echo "                  <td></td>\n";   #Col. 3				   
	    echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 4  
			$texto = utf8_decode (abr($material[$i]));
	    echo "                     $texto\n";	 
	    echo "                  </td>\n";  
      echo "                  <td></td>\n";   #Col. 5				
	    echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 6 
	    echo "                     $clase[$i]\n";
	    echo "                  </td>\n";
      echo "                  <td></td>\n";   #Col. 7					 	   		 	   	 	 	    
	    echo "               </tr>\n";
			$i++;
	 }
	 echo "            </table>\n";  
	 echo "          </fieldset>\n";
	# echo "         </form>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 	   		
	 echo "      </tr>\n";	 	 
}	 
################################################################################ 
if ($tabla_valua_const) {	 # TABLAS A1 y A2 
	 # Fila 4 
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Valuación de Construcciones de $legend_tabla_a gestión $gestion</legend>\n";	   
	 echo "            <table border=\"0\" width=\"100%\">\n";  #TABLE 6 Columnas
	# echo "               <tr>\n";
	# echo "                  <td align=\"right\" colspan=\"11\" class=\"bodyText\"></td>\n";   #Col. 1-13	 
	# echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">\n";   #Col. 1	    	  	 
	 echo "                     Lujoso\n"; 	   		
	 echo "                  </td>\n"; 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 2	   
	 echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">\n";   #Col. 3	  
	 echo "                     Muy Bueno\n";	 
	 echo "                  </td>\n"; 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 4  	 
	 echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">\n";   #Col. 5 
	 echo "                     Bueno\n";
	 echo "                  </td>\n";
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 6  	 
	 echo "                  <td align=\"center\" width=\"16%\" class=\"bodyTextH\">\n";   #Col. 7 
	 echo "                     Económico\n"; 	 
	 echo "                  </td>\n";	
	 if ($tabla_valua_const_vf) {	 
	    echo "                  <td width=\"1%\"></td>\n";   #Col. 8  	 
	    echo "                  <td align=\"center\" width=\"18%\" class=\"bodyTextH\">\n";   #Col. 9 
	    echo "                     Muy Económico\n";
	    echo "                  </td>\n";	
	    echo "                  <td width=\"1%\"></td>\n";   #Col. 10	  	 
	    echo "                  <td align=\"center\" width=\"16%\" class=\"bodyTextH\">\n";   #Col. 11 
	    echo "                     Marginal\n"; 
	    echo "                  </td>\n";
	 } else {
	    echo "                  <td width=\"36%\"></td>\n";   #Col. 8
	 } 	  		 	   		 	   	 	 	    
	 echo "               </tr>\n";   
	 echo "               <tr>\n";  	                     
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 2	    	  	 
	 echo "                     $lujoso\n"; 	   		
	 echo "                  </td>\n"; 
	 echo "                  <td></td>\n";   #Col. 3	   
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 4	  
	 echo "                     $mbueno\n";	 
	 echo "                  </td>\n"; 
	 echo "                  <td></td>\n";   #Col. 5	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 6 
	 echo "                     $bueno\n";
	 echo "                  </td>\n";
	 echo "                  <td></td>\n";   #Col. 7  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 8 
	 echo "                     $econo\n"; 	 
	 echo "                  </td>\n";	
	 if ($tabla_valua_const_vf) {		 	 
	    echo "                  <td></td>\n";   #Col. 9	  	 
	    echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 10 
	    echo "                     $mecono\n";
	    echo "                  </td>\n";	
	    echo "                  <td></td>\n";   #Col. 11	  	 
	    echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 12 
	    echo "                     $margin\n"; 
	    echo "                  </td>\n";
	 } else {
	    echo "                  <td></td>\n";   #Col. 8
	 } 					 	   		 	   	 	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n";  
	 echo "          </fieldset>\n";
	# echo "         </form>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 	   		
	 echo "      </tr>\n";
   if ($nivel >= 4) {
	    echo "			<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	
	    echo "      <tr>\n";
      echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	    echo "         <td valign=\"center\">\n";   #Col. 2
	    echo "            <table border=\"0\" width=\"100%\">\n";  #TABLE 6 Columnas
			if (isset($_POST['mod'])) {
	       echo "               <tr>\n";
	       echo "                  <td width=\"2%\"></td>\n";   #Col. 1					   	                     
	       echo "                  <td align=\"center\" width=\"14%\" class=\"bodyTextD\">\n";   #Col. 2	    	  	 
			   echo "                     <input name=\"lujoso\" id=\"form_anadir2\" class=\"navText\" maxlength=\"5\" value=\"\">\n";	   		
	       echo "                  </td>\n"; 
	       echo "                  <td width=\"1%\"></td>\n";   #Col. 3   
	       echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextD\">\n";   #Col. 4  
			   echo "                     <input name=\"mbueno\" id=\"form_anadir2\" class=\"navText\" maxlength=\"5\" value=\"\">\n";	 
	       echo "                  </td>\n"; 
	       echo "                  <td width=\"1%\"></td>\n";   #Col. 5	  	 
	       echo "                  <td align=\"center\" width=\"14%\" class=\"bodyTextD\">\n";   #Col. 6 
			   echo "                     <input name=\"bueno\" id=\"form_anadir2\" class=\"navText\" maxlength=\"5\" value=\"\">\n";	
	       echo "                  </td>\n";
	       echo "                  <td width=\"1%\"></td>\n";   #Col. 7	 
	       echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextD\">\n";   #Col. 8 
			   echo "                     <input name=\"econo\" id=\"form_anadir2\" class=\"navText\" maxlength=\"5\" value=\"\">\n";	 
	       echo "                  </td>\n";	
	       if ($tabla_valua_const_vf) {		 	 
	          echo "                  <td width=\"1%\"></td>\n";   #Col. 9	  	 
	          echo "                  <td align=\"center\" width=\"18%\" class=\"bodyTextD\">\n";   #Col. 10 
			   echo "                     <input name=\"mecono\" id=\"form_anadir2\" class=\"navText\" maxlength=\"5\" value=\"\">\n";	
	          echo "                  </td>\n";	
	          echo "                  <td width=\"1%\"></td>\n";   #Col. 11	  	 
	          echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextD\">\n";   #Col. 12 
			   echo "                     <input name=\"margin\" id=\"form_anadir2\" class=\"navText\" maxlength=\"5\" value=\"\">\n";	
	          echo "                  </td>\n";
	       } else {
	          echo "                  <td width=\"35%\"></td>\n";   #Col. 8
	       }
				 echo "                  <td width=\"2%\"></td>\n";   #Col. 9 o 13	 					 	   		 	   	 	 	    
	       echo "               </tr>\n";
		  }	
	    echo "               <tr>\n";
			if ($tabla_valua_const_vf) {	
	       echo "                  <td align=\"center\" colspan=\"13\">\n";   #Col. 1-11
			} else {
	       echo "                  <td align=\"center\" colspan=\"9\">\n";   #Col. 1-8
			}			
      echo "                     <input name=\"gestion\" type=\"hidden\" value=\"$gestion\">\n";
      echo "                     <input name=\"tabla\" type=\"hidden\" value=\"$valor\">\n";
      echo "                     <input name=\"accion\" type=\"hidden\" value=\"$accion\">\n";	
      echo "                     <input name=\"submit\" type=\"hidden\" value=\"Ver\">\n";			
			if (isset($_POST['mod'])) {								
         echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Modificar\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";
         echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"NO Modificar\">\n";				 
			} else {
         echo "                     <input name=\"mod\" type=\"submit\" class=\"smallText\" value=\"Modificar\">\n";			
			}		 
	    echo "                  </td>\n";	 	 	   		
	    echo "               </tr>\n";
      if ($error) {
	 	     echo "      <tr>\n"; 	 
	       echo "         <td></td>\n";   #Col. 1 	 			
	       echo "         <td align=\"center\">\n";   #Col. 2
         echo "            <font color=\"red\">$mensaje_de_error</font>\n";	 
	       echo "         </td>\n";	
	       echo "         <td></td>\n";   #Col. 3 				 	 	   		
	       echo "      </tr>\n";	
      }				
	    echo "            </table>\n";  
	    echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3 		 	   		
	    echo "      </tr>\n";	    
			echo "      </form>\n";				 	 
	 }#END_OF_IF  	 	 
}
################################################################################ 	 	  
if ($tabla_inclinacion) {	 # TABLA B 
	    # Fila 3 
	    echo "      <tr>\n";
      echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	    echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	    echo "         <fieldset><legend>Factores de Inclinación de Terreno gestión $gestion</legend>\n";
      echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
	    echo "            <table border=\"0\" width=\"100%\">\n";   # 7 TColumnas
			# TABLA Fila 1
	    echo "               <tr>\n";	 	 
	    echo "                  <td colspan=\"2\"> &nbsp </td>\n";   #TCol. 1 		 
	    echo "                  <td align=\"center\">\n";   #TCol. 2  
	    echo "                     <b>FACTORES</b>\n";
	    echo "                  </td>\n";  	
	    echo "                  <td align=\"center\" colspan=\"4\">\n";
	    if (($nivel < 4) OR ($gestion != $gestion_actual)) {
			   echo "                     &nbsp\n"; 
			} else {
	       echo "                     <b>NUEVOS VALORES</b>\n";			
			}
			echo "                  </td>\n";   #TCol. 1 				     	  	  	 	 	  	 	     
	    echo "               </tr>\n";
	    echo "               <tr>\n";	 	 
	    echo "                  <td width=\"5%\"> &nbsp </td>\n";   #TCol. 1 		 
	    echo "                  <td align=\"left\" width=\"42%\" class=\"bodyTextH\">\n";   #TCol. 2  
	    echo "                     <b>&nbsp TERRENO PLANO</b><br />&nbsp Con una inclinación de 0 a 5 grados\n";
	    echo "                  </td>\n";  	     	  	 
	    echo "                  <td align=\"center\" width=\"14%\" class=\"bodyTextD\">\n";   #TCol. 3	
	    echo "                     $terreno[0]\n";
	    echo "                  </td>\n";	
	    if (($nivel < 4) OR ($gestion != $gestion_actual)) {
         echo "                  <td align=\"right\" width=\"39%\">&nbsp</td>\n"; #Col. 4					
      } else { 	  
         echo "                  <td align=\"right\" width=\"15%\">\n"; #Col. 4					
         echo "                     &nbsp\n";	 
	       echo "                  </td>\n";		 
         echo "                  <td align=\"left\" width=\"10%\">\n"; #Col. 5					
         echo "                     <input name=\"fact_terr_plano\" id=\"form_anadir0\" class=\"navText\" maxlength=\"5\">\n";	 
	       echo "                  </td>\n";	 
	       echo "                  <td align=\"left\" width=\"9%\">\n";   #TCol. 6  
	       echo "                     &nbsp\n"; 
	       echo "                  </td>\n"; 		
	       echo "                  <td width=\"5%\"> &nbsp</td>\n";   #TCol. 7	
      }  	 	 	 	  	 	     
	    echo "               </tr>\n";
      # TABLA Fila 2-edgar
	    echo "               <tr>\n";	 	 
	    echo "                  <td colspan=\"2\"> &nbsp </td>\n";   #TCol. 1 		 
	    echo "                  <td align=\"center\">\n";   #TCol. 2  
	    echo "                     &nbsp\n";
	    echo "                  </td>\n";  	
	    echo "                  <td colspan=\"4\"> &nbsp </td>\n";   #TCol. 1 				     	  	  	 	 	  	 	     
	    echo "               </tr>\n";
	    echo "               <tr>\n";	 	 
	    echo "                  <td> &nbsp </td>\n";   #TCol. 1 		 
	    echo "                  <td align=\"left\"class=\"bodyTextH\">\n";   #TCol. 2  
	    echo "                     <b>&nbsp TERRENO SEMIPLANO</b><br />&nbsp Con una inclinación de 6 a 10 grados\n";
	    echo "                  </td>\n";  	     	  	 
	    echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #TCol. 3	
	    echo "                     $terreno[1]\n";
	    echo "                  </td>\n";	
	    if (($nivel < 4) OR ($gestion != $gestion_actual)) {
         echo "                  <td align=\"right\">&nbsp</td>\n"; #Col. 4					
      } else { 	  
         echo "                  <td align=\"right\">\n"; #Col. 4					
         echo "                     &nbsp\n";	 
	       echo "                  </td>\n";		 
         echo "                  <td align=\"left\">\n"; #Col. 5					
         echo "                     <input name=\"fact_terr_incl\" id=\"form_anadir0\" class=\"navText\" maxlength=\"5\">\n";	 
	       echo "                  </td>\n";	 
	       echo "                  <td align=\"left\">\n";   #TCol. 6  	 		 
	       echo "                     &nbsp\n";	 
	       echo "                  </td>\n"; 		
	       echo "                  <td> &nbsp</td>\n";   #TCol. 7	
      }  	 	 	 	  	 	     
	    echo "               </tr>\n";
      # TABLA Fila 3
	    echo "               <tr>\n";	 	 
	    echo "                  <td colspan=\"2\"> &nbsp </td>\n";   #TCol. 1 		 
	    echo "                  <td align=\"center\">\n";   #TCol. 2  
	    echo "                     &nbsp\n";
	    echo "                  </td>\n";  	
	    echo "                  <td colspan=\"4\"> &nbsp </td>\n";   #TCol. 1 				     	  	  	 	 	  	 	     
	    echo "               </tr>\n";
	    echo "               <tr>\n";	 	 
	    echo "                  <td> &nbsp </td>\n";   #TCol. 1 		 
	    echo "                  <td align=\"left\"class=\"bodyTextH\">\n";   #TCol. 2  
	    echo "                     <b>&nbsp TERRENO INCLINADO</b><br />&nbsp Con una inclinación de 11 a 20 grados\n";
	    echo "                  </td>\n";  	     	  	 
	    echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #TCol. 3	
	    echo "                     $terreno[2]\n";
	    echo "                  </td>\n";	
	    if (($nivel < 4) OR ($gestion != $gestion_actual)) {
         echo "                  <td align=\"right\">&nbsp</td>\n"; #Col. 4					
      } else { 	  
         echo "                  <td align=\"right\">\n"; #Col. 4					
         echo "                     &nbsp\n";	 
	       echo "                  </td>\n";		 
         echo "                  <td align=\"left\">\n"; #Col. 5					
         echo "                     <input name=\"fact_terr_incl\" id=\"form_anadir0\" class=\"navText\" maxlength=\"5\">\n";	 
	       echo "                  </td>\n";	 
	       echo "                  <td align=\"left\">\n";   #TCol. 6  	 		 
	       echo "                     &nbsp\n";	 
	       echo "                  </td>\n"; 		
	       echo "                  <td> &nbsp</td>\n";   #TCol. 7	
      }  	 	 	 	  	 	     
	    echo "               </tr>\n";
      # TABLA Fila 4
	    echo "               <tr>\n";	 	 
	    echo "                  <td colspan=\"2\"> &nbsp </td>\n";   #TCol. 1 		 
	    echo "                  <td align=\"center\">\n";   #TCol. 2  
	    echo "                     &nbsp\n";
	    echo "                  </td>\n";  	
	    echo "                  <td colspan=\"4\"> &nbsp </td>\n";   #TCol. 1 				     	  	  	 	 	  	 	     
	    echo "               </tr>\n";
	    echo "               <tr>\n";	 	 
	    echo "                  <td> &nbsp </td>\n";   #TCol. 1 		 
	    echo "                  <td align=\"left\" class=\"bodyTextH\">\n";   #TCol. 2  
	    echo "                     <b>&nbsp TERRENO MUY INCLINADO</b><br />&nbsp Con una inclinación 21 a 40 grados\n";
	    echo "                  </td>\n";  	     	  	 
	    echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #TCol. 3	
	    echo "                     $terreno[3]\n";
	    echo "                  </td>\n";	
	    if (($nivel < 4) OR ($gestion != $gestion_actual)) {
         echo "                  <td>&nbsp</td>\n"; #Col. 4					
      } else { 	  
         echo "                  <td align=\"right\">\n"; #Col. 4					
         echo "                     &nbsp\n";	 
	       echo "                  </td>\n";		 
         echo "                  <td align=\"left\">\n"; #Col. 5					
         echo "                     <input name=\"fact_terr_minc\" id=\"form_anadir0\" class=\"navText\" maxlength=\"5\">\n";	 
	       echo "                  </td>\n";	 
	       echo "                  <td align=\"left\">\n";   #TCol. 6  		 
 
	       echo "                  </td>\n"; 		
	       echo "                  <td> &nbsp</td>\n";   #TCol. 7	
      }  	 	 	 	  	 	     
	    echo "               </tr>\n";		
			
      # TABLA Fila 5
	    echo "               <tr>\n";	 	 
	    echo "                  <td colspan=\"2\"> &nbsp </td>\n";   #TCol. 1 		 
	    echo "                  <td align=\"center\">\n";   #TCol. 2  
	    echo "                     &nbsp\n";
	    echo "                  </td>\n";  	
	    echo "                  <td colspan=\"4\"> &nbsp </td>\n";   #TCol. 1 				     	  	  	 	 	  	 	     
	    echo "               </tr>\n";
	    echo "               <tr>\n";	 	 
	    echo "                  <td> &nbsp </td>\n";   #TCol. 1 		 
	    echo "                  <td align=\"left\" class=\"bodyTextH\">\n";   #TCol. 2  
	    echo "                     <b>&nbsp BARRANCO</b><br />&nbsp Con una inclinación superior a 40 grados\n";
	    echo "                  </td>\n";  	     	  	 
	    echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #TCol. 3	
	    echo "                     $terreno[4]\n";
	    echo "                  </td>\n";	
	    if (($nivel < 4) OR ($gestion != $gestion_actual)) {
         echo "                  <td>&nbsp</td>\n"; #Col. 4					
      } else { 	  
         echo "                  <td align=\"right\">\n"; #Col. 4					
         echo "                     &nbsp\n";	 
	       echo "                  </td>\n";		 
         echo "                  <td align=\"left\">\n"; #Col. 5					
         echo "                     <input name=\"fact_terr_minc\" id=\"form_anadir0\" class=\"navText\" maxlength=\"5\">\n";	 
	       echo "                  </td>\n";	 
	       echo "                  <td align=\"left\">\n";   #TCol. 6  		 
 
	       echo "                  </td>\n"; 		
	       echo "                  <td> &nbsp</td>\n";   #TCol. 7	
      }  	 	 	 	  	 	     
	    echo "               </tr>\n";	
	
				
	    echo "               <tr>\n";	 	 
	    echo "                  <td colspan=\"3\"> &nbsp </td>\n";   #TCol. 1-3 		 
	    echo "                  <td align=\"center\" colspan=\"4\">\n";   #TCol. 4-7 
	    if (($nivel < 4) OR ($gestion != $gestion_actual)) {
         echo "                  &nbsp\n";		
			} else { 						 
	       echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Cambiar\">\n";	
         echo "                     <input name=\"gestion\" type=\"hidden\" value=\"$gestion\">\n";
         echo "                     <input name=\"tabla\" type=\"hidden\" value=\"b\">\n";	
         echo "                     <input name=\"accion\" type=\"hidden\" value=\"tablas\">\n";
         echo "                     <input name=\"submit\" type=\"hidden\" value=\"Ver\">\n";	 
			} 		 		
	    echo "                  </td>\n";  	     	  	  	 	 	  	 	     
	    echo "               </tr>\n";										
	    echo "            </table>\n"; 
  	  echo "          </form>\n";	  
	    echo "         </fieldset>\n";
	    echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	    echo "      </tr>\n";		 
}
################################################################################ 
if ($tabla_servicios) {	 # TABLA C
	 # Fila 4 
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Factores de Existencia de Servicios gestión $gestion</legend>\n";	   
	 echo "            <table border=\"0\" width=\"100%\">\n";  #TABLE 6 Columnas
	# echo "               <tr>\n";
	# echo "                  <td align=\"right\" colspan=\"11\" class=\"bodyText\"></td>\n";   #Col. 1-13	 
	# echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">\n";   #Col. 1	    	  	 
	 echo "                     LUZ\n"; 	   		
	 echo "                  </td>\n"; 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 2	   
	 echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">\n";   #Col. 3	  
	 echo "                     AGUA\n";	 
	 echo "                  </td>\n"; 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 4  	 
	 echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">\n";   #Col. 5 
	 echo "                     ALCANT.\n";
	 echo "                  </td>\n";
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 6  	 
	 echo "                  <td align=\"center\" width=\"16%\" class=\"bodyTextH\">\n";   #Col. 7 
	 echo "                     TELEFONO\n"; 	 
	 echo "                  </td>\n";	
   echo "                  <td width=\"1%\"></td>\n";   #Col. 8  	 
   echo "                  <td align=\"center\" width=\"18%\" class=\"bodyTextH\">\n";   #Col. 9 
   echo "                     MINIMO\n";
	 echo "                  </td>\n";	
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 10	  	 
	 echo "                  <td align=\"center\" width=\"16%\" class=\"bodyTextH\">\n";   #Col. 11 
	 echo "                     SERVICIOS\n"; 
	 echo "                  </td>\n";  		 	   		 	   	 	 	    
	 echo "               </tr>\n";   
	 echo "               <tr>\n";  	                     
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 2	    	  	 
	 echo "                     $serv_luz\n"; 	   		
	 echo "                  </td>\n"; 
	 echo "                  <td></td>\n";   #Col. 3	   
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 4	  
	 echo "                     $serv_agua\n";	 
	 echo "                  </td>\n"; 
	 echo "                  <td></td>\n";   #Col. 5	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 6 
	 echo "                     $serv_alc\n";
	 echo "                  </td>\n";
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 7  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 8 
	 echo "                     $serv_tel\n"; 	 
	 echo "                  </td>\n";		 
	 echo "                  <td></td>\n";   #Col. 9	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 10 
	 echo "                     $serv_min\n";
	 echo "                  </td>\n";	
	 echo "                  <td></td>\n";   #Col. 11	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 12 
	 echo "                     $serv_serv\n"; 
	 echo "                  </td>\n"; 					 	   		 	   	 	 	    
	 echo "               </tr>\n";
	 echo "            </table>\n";  
	 echo "          </fieldset>\n";
	# echo "         </form>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 	   		
	 echo "      </tr>\n";	 	 
}
################################################################################ 	 	 
if ($tabla_factores_deprec) {	 # TABLA D 
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Factores de Depreciación gestión $gestion</legend>\n";	   
	 echo "            <table border=\"0\" width=\"100%\">\n";  #TABLE 6 Columnas
	# echo "               <tr>\n";
	# echo "                  <td align=\"right\" colspan=\"11\" class=\"bodyText\"></td>\n";   #Col. 1-13	 
	# echo "               </tr>\n";	   
	 echo "               <tr>\n"; 
	 echo "                  <td></td>\n";   #Col. 1		  	                     
	 echo "                  <td align=\"center\" colspan=\"2\" class=\"bodyTextH\">\n";   #Col. 2-3	    	  	 
	 echo "                     ANTIGUEDAD DE LA CONSTRUCCION<br />DESDE &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp HASTA\n"; 	   		
	 echo "                  </td>\n"; 
	 echo "                  <td></td>\n";   #Col. 4	   
	 echo "                  <td align=\"center\" class=\"bodyTextH\">\n";   #Col. 5	  
	 echo "                     FACTOR\n";	 
	 echo "                  </td>\n"; 
   echo "                  <td></td>\n";   #Col. 6	 	   		 	   	 	 	    
	 echo "               </tr>\n";
	 $i = 0;
	 while ($i < $no_de_filas) {
	    $j = $i+1;   
	    echo "               <tr>\n";  	                     
	    echo "                  <td width=\"15%\"></td>\n";   #Col. 1		  	                     
	    echo "                  <td align=\"center\" width=\"23%\" class=\"bodyTextD\">\n";   #Col. 2	
			if ($antig[$i] != 0) { 
			   $ant_para_tabla = $antig[$i]+1;
			} else $ant_para_tabla = $antig[$i];    	  	 
	    echo "                     $ant_para_tabla\n"; 	   		
	    echo "                  </td>\n";    
	    echo "                  <td align=\"center\" width=\"23%\" class=\"bodyTextD\">\n";   #Col. 3  
	    echo "                     $antig[$j]\n";	 
	    echo "                  </td>\n";  
      echo "                  <td width=\"1%\"></td>\n";   #Col. 4				
	    echo "                  <td align=\"center\" width=\"13%\" class=\"bodyTextD\">\n";   #Col. 5 
	    echo "                     $factor[$j]\n";
	    echo "                  </td>\n";
      echo "                  <td width=\"25%\"></td>\n";   #Col. 6					 	   		 	   	 	 	    
	    echo "               </tr>\n";
			$i++;
	 }
	 echo "            </table>\n";  
	 echo "          </fieldset>\n";
	# echo "         </form>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 	   		
	 echo "      </tr>\n";	 	 
}	 
################################################################################ 
if ($valuacion_terreno) {	 # TABLA E    
	 # Fila 3 
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Valor Metro Cuadrado por Zonas y material de Vias gestión $gestion</legend>\n";	   
	 echo "            <table border=\"0\" width=\"100%\">\n";  #TABLE 9 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"9\" class=\"bodyText\"></td>\n";   #Col. 1-9	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
#	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"11%\" class=\"bodyText\">\n";   #Col. 1	    	  	 
	 echo "                     <b>Zona</b>\n"; 	   		
	 echo "                  </td>\n"; 
#	 echo "                  <td width=\"1%\"></td>\n";   #Col. 3	   
	 echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextH\">\n";   #Col. 2	  
	 echo "                     Asfalto\n";	 
	 echo "                  </td>\n"; 
#	 echo "                  <td width=\"1%\"></td>\n";   #Col. 5	  	 
	 echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextH\">\n";   #Col. 3 
	 echo "                     Adoquin\n";
	 echo "                  </td>\n";
#	 echo "                  <td width=\"1%\"></td>\n";   #Col. 7  	 
	 echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextH\">\n";   #Col. 4 
	 echo "                     Cemento\n"; 	 
	 echo "                  </td>\n";		 
#	 echo "                  <td width=\"1%\"></td>\n";   #Col. 9	  	 
	 echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextH\">\n";   #Col. 5 
	 echo "                     Loseta\n";
	 echo "                  </td>\n";	
#	 echo "                  <td width=\"1%\"></td>\n";   #Col. 11	  	 
	 echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextH\">\n";   #Col. 6 
	 echo "                     Piedra\n"; 
	 echo "                  </td>\n";	
#	 echo "                  <td width=\"1%\"></td>\n";   #Col. 13	
	 echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextH\">\n";   #Col. 7 
	 echo "                     Ripio\n"; 
	 echo "                  </td>\n";	
	 echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextH\">\n";   #Col. 8 
	 echo "                     Tierra\n"; 
	 echo "                  </td>\n";	
	 echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextH\">\n";   #Col. 9 
	 echo "                     Ladrillo\n"; 
	 echo "                  </td>\n";		 	   		 	   	 	 	    
	 echo "               </tr>\n";
	 $i = 0;
   while ($i < $no_de_zonas) { 
	    echo "               <tr>\n";  	                     
#	    echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	    echo "                  <td align=\"center\" class=\"bodyTextD\">\n";  #Col. 1	    	  	 
	    echo "                     &nbsp $zona[$i] &nbsp\n"; 	   		
	    echo "                  </td>\n"; 
#	    echo "                  <td width=\"1%\"></td>\n";   #Col. 3	   
	    echo "                  <td align=\"right\" class=\"bodyTextD\">\n";   #Col. 2	  
	    echo "                     &nbsp $asf[$i] &nbsp\n";	 
	    echo "                  </td>\n"; 
#	    echo "                  <td width=\"1%\"></td>\n";   #Col. 5	  	 
	    echo "                  <td align=\"right\" class=\"bodyTextD\">\n";   #Col. 3 
	    echo "                     &nbsp $adq[$i] &nbsp\n";
	    echo "                  </td>\n";
#	    echo "                  <td width=\"1%\"></td>\n";   #Col. 7  	 
	    echo "                  <td align=\"right\" class=\"bodyTextD\">\n";   #Col. 4
	    echo "                     &nbsp $cem[$i] &nbsp\n"; 	 
	    echo "                  </td>\n";		 
#	    echo "                  <td width=\"1%\"></td>\n";   #Col. 9	  	 
	    echo "                  <td align=\"right\" class=\"bodyTextD\">\n";   #Col. 5 
	    echo "                     &nbsp $los[$i] &nbsp\n";
	    echo "                  </td>\n";	
#	    echo "                  <td width=\"1%\"></td>\n";   #Col. 11	  	 
	    echo "                  <td align=\"right\" class=\"bodyTextD\">\n";   #Col. 6 
	    echo "                     &nbsp $pdr[$i] &nbsp\n"; 
	    echo "                  </td>\n";	
#	    echo "                  <td width=\"1%\"></td>\n";   #Col. 13	
	    echo "                  <td align=\"right\" class=\"bodyTextD\">\n";   #Col. 7
	    echo "                     &nbsp $rip[$i] &nbsp\n"; 
	    echo "                  </td>\n";	
	    echo "                  <td align=\"right\" class=\"bodyTextD\">\n";   #Col. 8 
	    echo "                     &nbsp $trr[$i] &nbsp\n"; 
	    echo "                  </td>\n";	
	    echo "                  <td align=\"right\" class=\"bodyTextD\">\n";   #Col. 9
	    echo "                     &nbsp $lad[$i] &nbsp\n"; 
	    echo "                  </td>\n";		 	   		 	   	 	 	    
	    echo "               </tr>\n";
	    $i++;	 
   }
	 echo "            </table>\n";  
	 echo "          </fieldset>\n";
	# echo "         </form>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	 echo "      </tr>\n";
   if ($nivel >= 4) {
	    echo "			<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	
	    echo "      <tr>\n";
      echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	    echo "         <td valign=\"center\">\n";   #Col. 2
	    echo "            <table border=\"0\" width=\"100%\">\n";  #TABLE 6 Columnas
			if (isset($_POST['mod'])) {			
	       echo "               <tr>\n";  	                     
 	       echo "                  <td width=\"2%\"></td>\n";   #Col. 1	
	       echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextD\">\n";   #Col. 2	    	  	 
			   echo "                     <input name=\"zona_new\" id=\"form_anadir2\" class=\"navText\" maxlength=\"5\" value=\"\">\n";   		
	       echo "                  </td>\n";   
	       echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextD\">\n";   #Col. 3	  
			   echo "                     <input name=\"asf_new\" id=\"form_anadir2\" class=\"navText\" maxlength=\"5\" value=\"\">\n";
	       echo "                  </td>\n";  	 
	       echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextD\">\n";   #Col. 4 
			   echo "                     <input name=\"adq_new\" id=\"form_anadir2\" class=\"navText\" maxlength=\"5\" value=\"\">\n";
	       echo "                  </td>\n"; 	 
	       echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextD\">\n";   #Col. 5 
			   echo "                     <input name=\"cem_new\" id=\"form_anadir2\" class=\"navText\" maxlength=\"5\" value=\"\">\n"; 
	       echo "                  </td>\n";		   	 
	       echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextD\">\n";   #Col. 6 
			   echo "                     <input name=\"los_new\" id=\"form_anadir2\" class=\"navText\" maxlength=\"5\" value=\"\">\n";
	       echo "                  </td>\n";	 	 
	       echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextD\">\n";   #Col. 7 
			   echo "                     <input name=\"pdr_new\" id=\"form_anadir2\" class=\"navText\" maxlength=\"5\" value=\"\">\n";
	       echo "                  </td>\n";	
	       echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextD\">\n";   #Col. 8 
			   echo "                     <input name=\"rip_new\" id=\"form_anadir2\" class=\"navText\" maxlength=\"5\" value=\"\">\n";
	       echo "                  </td>\n";	
	       echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextD\">\n";   #Col. 9 
			   echo "                     <input name=\"trr_new\" id=\"form_anadir2\" class=\"navText\" maxlength=\"5\" value=\"\">\n";
	       echo "                  </td>\n";	
	       echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextD\">\n";   #Col. 10 
			   echo "                     <input name=\"lad_new\" id=\"form_anadir2\" class=\"navText\" maxlength=\"5\" value=\"\">\n"; 
	       echo "                  </td>\n";		
         echo "                  <td width=\"1%\"></td>\n";   #Col. 11				  	   		 	   	 	 	    
	       echo "               </tr>\n";			
			}
	    echo "               <tr>\n"; 			
	    echo "                  <td align=\"center\" colspan=\"11\">\n";   #Col. 1-11	
      echo "                     <input name=\"gestion\" type=\"hidden\" value=\"$gestion\">\n";
      echo "                     <input name=\"tabla\" type=\"hidden\" value=\"$valor\">\n";
      echo "                     <input name=\"accion\" type=\"hidden\" value=\"$accion\">\n";	
      echo "                     <input name=\"submit\" type=\"hidden\" value=\"Ver\">\n";			
			if (isset($_POST['mod'])) {								
         echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Modificar\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";
         echo "                     <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"NO Modificar\">\n";				 
			} else {
         echo "                     <input name=\"mod\" type=\"submit\" class=\"smallText\" value=\"Modificar\">\n";			
			}		 
	    echo "                  </td>\n";	 	 	   		
	    echo "               </tr>\n";	
	    echo "            </table>\n";  
	    echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3 		 	   		
	    echo "      </tr>\n";	    
			echo "      </form>\n";
   }					 
} #END_OF_IF ($valuacion_terreno)  
################################################################################ 	
if ($tabla_fecha_venc) {	 # TABLA F 
	 # Fila 4 
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Plazos de Vencimiento gestión $gestion</legend>\n";	 
   echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";		   
	 echo "            <table border=\"0\" width=\"100%\">\n";  #TABLE 9 Columnas
	# echo "               <tr>\n";
	# echo "                  <td align=\"right\" colspan=\"11\" class=\"bodyText\"></td>\n";   #Col. 1-13	 
	# echo "               </tr>\n";	   
	 echo "               <tr>\n";
	 echo "                  <td width=\"15%\"></td>\n";   #Col. 1	 	   	                     
	 echo "                  <td align=\"center\" width=\"17%\" class=\"bodyTextH\">\n";   #Col. 2	    	  	 
	 echo "                     Fecha Venc. RS\n"; 	   		
	 echo "                  </td>\n"; 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 3	   
	 echo "                  <td align=\"center\" width=\"17%\" class=\"bodyTextH\">\n";   #Col. 4	  
	 echo "                     Fecha Mod.1\n";	 
	 echo "                  </td>\n"; 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 5  	 
	 echo "                  <td align=\"center\" width=\"17%\" class=\"bodyTextH\">\n";   #Col. 6
	 echo "                     Fecha Mod.2\n";
	 echo "                  </td>\n";
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 7  	 
	 echo "                  <td align=\"center\" width=\"17%\" class=\"bodyTextH\">\n";   #Col. 8 
	 echo "                     Fecha Mod.3\n"; 	 
	 echo "                  </td>\n";	
	 echo "                  <td width=\"14%\"></td>\n";   #Col. 9	  		 	   		 	   	 	 	    
	 echo "               </tr>\n";   
	 echo "               <tr>\n"; 
	 $cambiado = false;
   echo "                  <td></td>\n";   #Col. 1		  	                     
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 2
	 if (isset($_POST['fecha_venc'])) { 
      echo "                     <input name=\"fecha\" id=\"form_anadir0\" class=\"navText\" maxlength=\"10\" value=\"31/12/$siguiente_ano\">\n";	 
			$cambiado = true;
	 } elseif (($fecha_venc == "-") AND (!$cambiado) AND ($nivel > 3)) { 
      echo "                     <input name=\"fecha_venc\" type=\"submit\" class=\"smallText\" value=\"Ingresar\">\n";
			$cambiado = true;
	 } else {	   
	    echo "                     $fecha_venc\n";
	 }	    		
	 echo "                  </td>\n"; 
	 echo "                  <td></td>\n";   #Col. 3	   
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 4	
	 if (isset($_POST['fecha_mod1'])) { 
      echo "                     <input name=\"fecha\" id=\"form_anadir0\" class=\"navText\" maxlength=\"10\" value=\"31/12/$siguiente_ano\">\n";
			$cambiado = true;				
	 } elseif (($fecha_mod1 == "-") AND (!$cambiado) AND ($nivel > 3)) { 
      echo "                     <input name=\"fecha_mod1\" type=\"submit\" class=\"smallText\" value=\"Ingresar\">\n";
			$cambiado = true; 
	 } else {	   
	    echo "                     $fecha_mod1\n";
	 }	 
	 echo "                  </td>\n"; 
	 echo "                  <td></td>\n";   #Col. 5	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 6 
	 if (isset($_POST['fecha_mod2'])) { 
      echo "                     <input name=\"fecha\" id=\"form_anadir0\" class=\"navText\" maxlength=\"10\" value=\"31/12/$siguiente_ano\">\n";
			$cambiado = true;	
	 } elseif (($fecha_mod2 == "-") AND (!$cambiado) AND ($nivel > 3)) { 
      echo "                     <input name=\"fecha_mod2\" type=\"submit\" class=\"smallText\" value=\"Ingresar\">\n";
			$cambiado = true;			 			
	 } else {	   
	    echo "                     $fecha_mod2\n";
	 }	
	 echo "                  </td>\n";
	 echo "                  <td></td>\n";   #Col. 7  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 8 
	 if ((isset($_POST['fecha_mod3'])) OR ((isset($_POST['ingresar_mod4'])) AND (!isset($_POST['fecha'])))) { 
	    if (isset($_POST['fecha_mod3'])) {
         echo "                     <input name=\"fecha\" id=\"form_anadir0\" class=\"navText\" maxlength=\"10\" value=\"31/12/$siguiente_ano\">\n";	
			} else {
         echo "                     <input name=\"fecha\" id=\"form_anadir0\" class=\"navText\" maxlength=\"10\" value=\"$fecha_mod3\">\n";				
			}  			
			$cambiado = true; 
	 } elseif (($fecha_mod3 == "-") AND (!$cambiado) AND ($nivel > 3)) { 
      echo "                     <input name=\"fecha_mod3\" type=\"submit\" class=\"smallText\" value=\"Ingresar\">\n";
			$cambiado = true;			
	 } else {	   
	    echo "                     $fecha_mod3\n";
	 }	 
	 echo "                  </td>\n";	
   if (($fecha_mod3 != "-") AND (!isset($_POST['ingresar_mod4'])) AND ($nivel > 3)) {
      echo "                     <td><input name=\"ingresar_mod4\" type=\"submit\" class=\"smallText\" value=\"Ingresar\"></td>\n";			   
   } else {	 
      echo "                  <td></td>\n";   #Col. 9	
	 }	
   echo "                  <input name=\"gestion\" type=\"hidden\" value=\"$gestion\">\n";
   echo "                  <input name=\"tabla\" type=\"hidden\" value=\"f\">\n";	
   echo "                  <input name=\"accion\" type=\"hidden\" value=\"tablas\">\n";
   echo "                  <input name=\"submit\" type=\"hidden\" value=\"Ver\">\n";	   	 		 	   		 	   	 	 	    
	 echo "               </tr>\n";
	 ##### SEGUNDA FILA #####
#	 if ((isset($_POST['fecha_venc'])) OR (isset($_POST['fecha_mod1'])) OR (isset($_POST['fecha_mod2'])) OR (isset($_POST['fecha_mod3']))) {
	 if ((($fila_con_fechas > 0) OR ($cambiado = true)) AND ($nivel > 3)) {
	    echo "               <tr>\n"; 
      echo "                  <td></td>\n";   #Col. 1		  	                     
	    echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 2
      if (isset($_POST['fecha_venc'])) { 	
         echo "                     <input name=\"ingresar_venc\" type=\"submit\" class=\"smallText\" value=\"Ingresar\">\n";
	    } elseif (($fecha_venc != '-') AND ($fecha_mod1 == '-')) {	   
         echo "                     <input name=\"borrar_venc\" type=\"submit\" class=\"smallText\" value=\"Borrar\">\n";
	    } else {	   
	       echo "                     &nbsp\n";
	    }		    		
	    echo "                  </td>\n"; 
	    echo "                  <td></td>\n";   #Col. 3	   
	    echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 4	
      if (isset($_POST['fecha_mod1'])) {
         echo "                     <input name=\"ingresar_mod1\" type=\"submit\" class=\"smallText\" value=\"Ingresar\">\n";
	    } elseif (($fecha_mod1 != '-') AND ($fecha_mod2 == '-')) {	   
         echo "                     <input name=\"borrar_mod1\" type=\"submit\" class=\"smallText\" value=\"Borrar\">\n";
	    } else {	   
	       echo "                     &nbsp\n";
	    }	 
	    echo "                  </td>\n"; 
	    echo "                  <td></td>\n";   #Col. 5	  	 
	    echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 6 
      if (isset($_POST['fecha_mod2'])) {
         echo "                     <input name=\"ingresar_mod2\" type=\"submit\" class=\"smallText\" value=\"Ingresar\">\n";
	    } elseif (($fecha_mod2 != '-') AND ($fecha_mod3 == '-')) {	   
         echo "                     <input name=\"borrar_mod2\" type=\"submit\" class=\"smallText\" value=\"Borrar\">\n";
	    } else {	   
	       echo "                     &nbsp\n";
	    }	
	    echo "                  </td>\n";
	    echo "                  <td></td>\n";   #Col. 7  	 
	    echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 8 
      if ((isset($_POST['fecha_mod3'])) OR (isset($_POST['ingresar_mod4']))) {
         echo "                     <input name=\"ingresar_mod3\" type=\"submit\" class=\"smallText\" value=\"Ingresar\">\n";
	    } elseif ($fecha_mod3 != '-') {	   
         echo "                     <input name=\"borrar_mod3\" type=\"submit\" class=\"smallText\" value=\"Borrar\">\n";
	    } else {	   
	       echo "                     &nbsp\n";
	    }	 
	    echo "                  </td>\n";	
			echo "                  <td></td>\n";   #Col. 9	 		 	   		 	   	 	 	    
	    echo "               </tr>\n";
	 }	 
	 echo "            </table>\n"; 
	 echo "          </form>\n";	  
	 echo "          </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 	   		
	 echo "      </tr>\n";	
	 if ($aviso_fech_venc) {	
	    echo "      <tr>\n";
      echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	    echo "         <td align=\"center\">\n";   #Col. 2	
	    echo "         <font color=\"$aviso_fech_color\">$aviso_fech_venc_mensaje</font>\n";	 
	    echo "         </td>\n";	 
      echo "         <td> &nbsp</td>\n";   #Col. 1 	
	    echo "      </tr>\n";
	 }
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td align=\"center\">\n";   #Col. 2	
	 echo "         <font color=\"grey\">Atención: El descuento de 10% solo se aplica hasta la fecha de vencimiento indicada en la Resolución Suprema. Si el Municipio amplia esa fecha se pagará el monto normal sin descuento. Después de la fecha de vencimiento se pagará el monto con multa (50 UFV como mínimo) !</font>\n";	 
	 echo "         </td>\n";	 
   echo "         <td> &nbsp</td>\n";   #Col. 1 	
	 echo "      </tr>\n";	     
}
################################################################################ 
if ($tabla_exenciones) {	 # TABLA G
	 # Fila 4 
  # if (($nuevos_valores) AND ($nivel >= 4)) {
   #   echo "			 <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";		 
	 #}
	 echo "			<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";	
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	 echo "         <fieldset><legend>Exenciones</legend>\n";	   
	 echo "            <table border=\"0\" width=\"100%\">\n";  #TABLE 9 Columnas   
	 echo "               <tr>\n"; 
	 echo "                  <td width=\"3%\">&nbsp</td>\n";   #Col. 1		  	                     
	 echo "                  <td width=\"30%\" align=\"center\" class=\"bodyTextH\">\n";   #Col. 2	    	  	 
	 echo "                     LEY/RESOLUCION\n"; 	   		
	 echo "                  </td>\n"; 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 3	   
	 echo "                  <td width=\"12%\" align=\"center\" class=\"bodyTextH\">\n";   #Col. 4  
	 echo "                     FECHA\n";	 
	 echo "                  </td>\n"; 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 5 	 
	 echo "                  <td width=\"41%\" align=\"center\" class=\"bodyTextH\">\n";   #Col. 6
	 echo "                     DESCRIPCION\n";
	 echo "                  </td>\n";
   echo "                  <td width=\"1%\"></td>\n";   #Col. 7
	 echo "                  <td width=\"10%\" align=\"center\" class=\"bodyTextH\">\n";   #Col. 8
	 echo "                     EXEN.\n";
	 echo "                  </td>\n";
   echo "                  <td width=\"1%\"></td>\n";   #Col. 9 		 	   		 	   	 	 	    
	 echo "               </tr>\n";
	 if ($check_exenciones == 0) {
	    echo "               <tr>\n"; 
	    echo "                  <td></td>\n";   #Col. 1		  	                     
	    echo "                  <td colspan=\"7\" align=\"center\" class=\"bodyTextD\">\n";   #Col. 2	    	  	 
	    echo "                     No existen Exenciones registrados en la Base de Datos\n"; 	   		
	    echo "                  </td>\n"; 
	    echo "                  <td></td>\n";   #Col. 3	 
	    echo "               </tr>\n";				 
	 }
	 $i = $j = 0;
	 while ($i < $no_de_filas) {
	#    $j = $i+1;   
	    echo "               <tr>\n"; 
			if ((!$anadir) AND (!$modificar) AND (!$borrar) AND ($nivel > 3)) {
	       if ($j == 0){
			      echo "                   <td class=\"bodyTextD_Small\"><input name=\"no_select\" value=\"$numero[$i]\" type=\"radio\" checked=\"checked\"></td>\n";   #Col. 1
						$j++;
		     } else {
			      echo "                   <td class=\"bodyTextD_Small\"><input name=\"no_select\" value=\"$numero[$i]\" type=\"radio\"></td>\n";   #Col. 1						 
			   }	 
	    }	else {
			   if ((($modificar) OR ($borrar)) AND ($nivel > 3)) {
            if ($numero[$i] == $no_select) { 
			         echo "                   <td><font color=red size=4> ></font></td>\n";	 
			      } else {
               echo "                   <td> &nbsp</td>\n";	 
			      }
         } else {
            echo "                  <td> &nbsp</td>\n";   #Col. 1	
				 }			
			}				                     	  	                     
	    echo "                  <td align=\"left\" class=\"bodyTextD\">\n";   #Col. 2	
      echo "                     &nbsp $ley[$i]\n";   		
	    echo "                  </td>\n"; 
	    echo "                  <td></td>\n";   #Col. 3	   
	    echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 4  
      echo "                     $fecha_exen[$i]\n";
	    echo "                  </td>\n"; 
	    echo "                  <td></td>\n";   #Col. 5  	 
	    echo "                  <td align=\"left\" class=\"bodyTextD\">\n";   #Col. 6  
	    echo "                     &nbsp $descripcion[$i]\n"; 	
	    echo "                  </td>\n";
	    echo "                  <td></td>\n";   #Col. 7  	 
	    echo "                  <td align=\"center\" class=\"bodyTextD\">\n";   #Col. 8  	 
	    echo "                     $porcentaje[$i] %\n"; 		  
	    echo "                  </td>\n";	
      echo "                  <td></td>\n";   #Col. 9								 	   		 	   	 	 	    
	    echo "               </tr>\n";
			$i++;
	 }
	 echo "            </table>\n";  
	 echo "          </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 	   		
	 echo "      </tr>\n";	
	 if ($error) {
	 	  echo "      <tr>\n"; 	 
	    echo "         <td></td>\n";   #Col. 1 	 			
	    echo "         <td align=\"center\">\n";   #Col. 2
      echo "            <font color=\"red\">$mensaje_de_error</font>\n";	 
	    echo "         </td>\n";	
	    echo "         <td></td>\n";   #Col. 3 				 	 	   		
	    echo "      </tr>\n";	
   }		 	 
	 if ($nivel >= 4) {
      echo "			<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=65&id=$session_id\" accept-charset=\"utf-8\">\n";		 
			if (($anadir) OR ($modificar)) {
	       echo "      <tr>\n";
         echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	       echo "         <td align=\"center\" valign=\"top\" height=\"40\">\n";   #Col. 2  
	       echo "            <table border=\"0\" width=\"97%\">\n";  #TABLE 9 Columnas   						
	       echo "               <tr>\n";  	                     
	       echo "                  <td width=\"4%\"></td>\n";   #Col. 1		  	                     
	       echo "                  <td width=\"30%\" align=\"center\" class=\"bodyTextD\">\n";   #Col. 2	
         echo "                     <input name=\"ley\" id=\"form_anadir1\" class=\"smallText\" maxlength=\"24\" value=\"$ley_mod\">\n";   		
	       echo "                  </td>\n"; 
	       echo "                  <td width=\"1%\"></td>\n";   #Col. 3	   
	       echo "                  <td width=\"12%\" align=\"center\" class=\"bodyTextD\">\n";   #Col. 4  
         echo "                     <input name=\"fecha_exen\" id=\"form_anadir1\" class=\"smallText\" maxlength=\"10\" value=\"$fecha_exen_mod\">\n";
	       echo "                  </td>\n"; 
	       echo "                  <td width=\"1%\"></td>\n";   #Col. 5  	 
	       echo "                  <td width=\"40%\" align=\"center\" class=\"bodyTextD\">\n";   #Col. 6  
         echo "                     <input name=\"descripcion\" id=\"form_anadir1\" class=\"smallText\" maxlength=\"33\" value=\"$descripcion_mod\">\n";
	       echo "                  </td>\n";
	       echo "                  <td width=\"1%\"></td>\n";   #Col. 7  	 
	       echo "                  <td width=\"10%\" align=\"center\" class=\"bodyTextD\">\n";   #Col. 8  	 
         echo "                     <input name=\"porcentaje\" id=\"form_anadir1\" class=\"smallText\" maxlength=\"5\" value=\"$porcentaje_mod\">\n";		  
	       echo "                  </td>\n";	
         echo "                  <td width=\"1%\">%</td>\n";   #Col. 9								 	   		 	   	 	 	    
	       echo "               </tr>\n";	
	       echo "               <tr>\n";  	                     		  	                     
	       echo "                  <td align=\"center\" height=\"40\" colspan=\"9\">\n";   #Col. 2
         echo "                     <input name=\"no_select\" type=\"hidden\" value=\"$no_select\">\n";				 
         echo "                     <input name=\"guardar2\" type=\"submit\" class=\"smallText\" value=\"$exen_accion\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";	
         echo "                     <input name=\"\" type=\"submit\" class=\"smallText\" value=\"NO $exen_accion\">\n";				 
	       echo "                  </td>\n";		  				 	
	       echo "            </table>\n";  
	       echo "         </td>\n";
	       echo "         <td> &nbsp</td>\n";   #Col. 3 		 	   		
	       echo "      </tr>\n";					 	
			} else {
	       echo "      <tr>\n";
         echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	       echo "         <td align=\"center\" height=\"40\">\n";   #Col. 2
         echo "            <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Añadir\">&nbsp&nbsp&nbsp&nbsp\n";
				 if ($check_exenciones > 0) {													
            echo "            <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Modificar\">&nbsp&nbsp&nbsp&nbsp\n";
            echo "            <input name=\"guardar\" type=\"submit\" class=\"smallText\" value=\"Borrar\">\n";
				 }					 
			   echo "         </td>\n";	 
	       echo "         <td> &nbsp</td>\n";   #Col. 3 		 	   		
	       echo "      </tr>\n";
			}
      echo "            <input name=\"gestion\" type=\"hidden\" value=\"$gestion\">\n";
      echo "            <input name=\"tabla\" type=\"hidden\" value=\"$valor\">\n";
      echo "            <input name=\"accion\" type=\"hidden\" value=\"$accion\">\n";	
      echo "            <input name=\"submit\" type=\"hidden\" value=\"Ver\">\n";				
	    echo "      </form>\n";
   }	 	  	 
}	 
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
	 echo "   <br />&nbsp;<br />\n";
	 echo "</td>\n";
################################################################################ 	 	  
?>
