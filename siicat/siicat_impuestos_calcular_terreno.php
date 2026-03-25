<?php
	
	 $gestion_act = $gestion_actual;
	 $sistema_act = "CAT";
	 $forma_pago_act = "";	 
	 $final_gestion_act = "31/12/".$gestion_act;										
   ########################################
   #------- CALCULAR AREA PREDIO ---------#
   ########################################
	 $sup_terr_act = $area;
	 ########################################
   #------------ DEFINIR ZONA ------------#
   ########################################
   $sql="SELECT valor_ant FROM cambios WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' AND variable = 'via_mat' AND fecha_cambio > '$final_gestion_act' ORDER BY fecha_cambio LIMIT 1";
   $check_cambios = pg_num_rows(pg_query($sql)); 
   if ($check_cambios > 0) {
	 	  $texto_para_observ = "El material de vía cambió después del pago de los impuestos de la gestión $gestion!";
      if ($observ_fila1 == "") {
	       $observ_fila1 = $texto_para_observ;
      } elseif ($observ_fila2 == "") {
	       $observ_fila2 = $texto_para_observ;
      } elseif ($observ_fila3 == "") {
	       $observ_fila3 = $texto_para_observ;				 		 
	    } else {
	       $observ_fila3 = $observ_fila3." ".$texto_para_observ;
	    }			 
   }
   # FUNCION GET_ZONA
   $zona_act = get_zona ($id_inmu);	
   if ($zona_act == "0") {			
				 $error_zona = true;
				 $mensaje_de_error_zona = "Aviso: El predio con el código '$cod_cat' no se encuentra en ninguna 'Zona homogénea'!";
			   $zona_act = "-";
				 $val_m2_terr_act = 0;
   } else {						
		     ########################################
         #----------- VALOR POR M2 -------------#
         ########################################						
			   $val_m2_terr_act = imp_valorporm2_terr ($gestion_act, $zona_act, $via_mat_act);
		     if ($val_m2_terr_act == 0) {
				    $error = true;
				    $mensaje_de_error = "Aviso: Por favor ingrese la cotización UFV del 31 de diciembre de $gestion[$j]!";
			   }
   }			
#echo "GESTION: $gestion_act Zona: $zona_act, VIA_MAT: $via_mat_act, Valor por m2 terreno: $val_m2_terr_act<br>";						
   ########################################
   #------ SERVICIOS Y INCLINACION -------#
   ########################################
   # SERVICIO DE AGUA
   $sql="SELECT valor_ant FROM cambios WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' AND variable = 'ser_agu' AND fecha_cambio > '$final_gestion_act' ORDER BY fecha_cambio LIMIT 1";
   $check_cambios = pg_num_rows(pg_query($sql)); 
   if ($check_cambios > 0) {
	 	  $texto_para_observ = "Se instaló el servicio de agua después del pago de los impuestos de la gestión $gestion!";
      if ($observ_fila1 == "") {
	       $observ_fila1 = $texto_para_observ;
      } elseif ($observ_fila2 == "") {
	       $observ_fila2 = $texto_para_observ;
      } elseif ($observ_fila3 == "") {
	       $observ_fila3 = $texto_para_observ;				 		 
	    } else {
	       $observ_fila3 = $observ_fila3." ".$texto_para_observ;
	    }
   }			
   $fact_agu_act = imp_factor_serv ($gestion_act, "serv_agua", $ser_agu_act);
   # SERVICIO DE ALCANTARILLADO		
   $sql="SELECT valor_ant FROM cambios WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' AND variable = 'ser_alc' AND fecha_cambio > '$final_gestion_act' ORDER BY fecha_cambio LIMIT 1";
   $check_cambios = pg_num_rows(pg_query($sql)); 
   if ($check_cambios > 0) {
	 	  $texto_para_observ = "Se instaló el servicio de alcantarillado después del pago de los impuestos de la gestión $gestion!";
      if ($observ_fila1 == "") {
	       $observ_fila1 = $texto_para_observ;
      } elseif ($observ_fila2 == "") {
	       $observ_fila2 = $texto_para_observ;
      } elseif ($observ_fila3 == "") {
	       $observ_fila3 = $texto_para_observ;				 		 
	    } else {
	       $observ_fila3 = $observ_fila3." ".$texto_para_observ;
	    }
   }				
   $fact_alc_act = imp_factor_serv ($gestion_act, "serv_alc", $ser_alc_act);
   # SERVICIO DE LUZ					
   $sql="SELECT valor_ant FROM cambios WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' AND variable = 'ser_luz' AND fecha_cambio > '$final_gestion_act' ORDER BY fecha_cambio LIMIT 1";
   $check_cambios = pg_num_rows(pg_query($sql)); 
   if ($check_cambios > 0) {			
	 	  $texto_para_observ = "Se instaló el servicio de luz después del pago de los impuestos de la gestión $gestion!";
      if ($observ_fila1 == "") {
	       $observ_fila1 = $texto_para_observ;
      } elseif ($observ_fila2 == "") {
	       $observ_fila2 = $texto_para_observ;
      } elseif ($observ_fila3 == "") {
	       $observ_fila3 = $texto_para_observ;				 		 
	    } else {
	       $observ_fila3 = $observ_fila3." ".$texto_para_observ;
	    }
   }				
   $fact_luz_act = imp_factor_serv ($gestion_act, "serv_luz", $ser_luz_act);		
   # SERVICIO DE TELEFONO	
   $sql="SELECT valor_ant FROM cambios WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' AND variable = 'ser_tel' AND fecha_cambio > '$final_gestion_act' ORDER BY fecha_cambio LIMIT 1";
   $check_cambios = pg_num_rows(pg_query($sql)); 
   if ($check_cambios > 0) {
	 	  $texto_para_observ = "Se instaló el servicio de teléfono después del pago de los impuestos de la gestión $gestion!";
      if ($observ_fila1 == "") {
	       $observ_fila1 = $texto_para_observ;
      } elseif ($observ_fila2 == "") {
	       $observ_fila2 = $texto_para_observ;
      } elseif ($observ_fila3 == "") {
	       $observ_fila3 = $texto_para_observ;				 		 
	    } else {
	       $observ_fila3 = $observ_fila3." ".$texto_para_observ;
	    }
   }				
   $fact_tel_act = imp_factor_serv ($gestion_act, "serv_tel", $ser_tel_act);	
   # SERVICIO MINIMO
   $fact_min_act = imp_factor_serv ($gestion_act, "serv_min", "SI");						
   $fact_incl_act = imp_factor_incl ($gestion_act, $ter_topo_act);
   $error_fact_incl = false;
   if ($fact_incl_act == -1) {
#echo "FACTOR_INCL es -1 <br>";
				 $error = $error_fact_incl = true;
				 $fact_incl_color = "red";
				 $mensaje_de_error_fact_incl = "Tiene que especificar la inclinación del terreno!";
   }			
   $factores_terreno_act = ($fact_agu_act + $fact_alc_act + $fact_luz_act + $fact_tel_act + $fact_min_act) * $fact_incl_act;
#echo "AGUA: $fact_agu_act, ALC: $fact_alc_act, LUZ: $fact_luz_act, TEL: $fact_tel_act, MIN: $fact_min_act, INCL: $fact_incl_act<br>";		
		  #if ($factores_terreno[$j] == 0) {
			#	 $error = true;
			#	 $mensaje_de_error = "Aviso: Hasta ahora no se ha ingresado los factores  para la gestión $gestion[$j]!";
			#}
			########################################
      #----------- AVALUO TERRENO -----------#
      ########################################	
      #echo "VALALOR POR M2: $val_m2_terr_act, SUP_TERRENO: $sup_terr_act, FACTORES: $factores_terreno_act<br>";						
			$avaluo_terr_actual = avaluo_terreno($val_m2_terr_act, $sup_terr_act, $factores_terreno_act);
?>