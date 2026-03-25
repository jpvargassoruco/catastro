<?php

$mostrar = false; 
$resultado = false;
#$dos_resultados = false;
$error = $error_fecha_venc = $error_tapr = $error_zona = $error_fact_incl = $error_esc_imp = $aviso = false;
$predio_existe = false;

################################################################################
#---------------------- REGISTRAR O CONVALIDAR PAGO ---------------------------#
################################################################################	
if ((isset($_POST["Registrar"])) AND ($_POST["Registrar"] == "REGISTRAR")) {
   #$cod_cat_reg = $_POST["cod_cat"];
	 $gestion_reg = $_POST["gestion"]; 
	 if (isset($_POST["exen_id"])) { 	
	    $exen_id = $_POST["exen_id"];
			$descont = $_POST["descont"];	 
      pg_query("UPDATE imp_pagados SET forma_pago = 'CONTADO', d10 = '0', mant_val = '0', cuota = '0', interes = '0', deb_for = '0', por_form = '0',
			    monto = '0', descont = '$descont', exen_id = '$exen_id', fech_imp = '$fecha', hora = '$hora', usuario = '$user_id', control = '00000000', no_orden = '1'
			    WHERE gestion = '$gestion_reg' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'"); 
	 } else {
      $no_orden_conval = (int) $_POST["no_orden"];
			$fech_imp_conval = $_POST["fech_imp"];
	    $cuota_conval = (int) $_POST["cuota"]; 
	    $control_conval = (int) $_POST["control"];	
			$control_conval = change_numero_to_8char ($control_conval);
      pg_query("UPDATE imp_pagados SET forma_pago = 'VALIDADO', cuota = '$cuota_conval', exen_id = '0', fech_imp = '$fech_imp_conval', hora = '12:00:00', usuario = '$user_id', control = '$control_conval', no_orden = '$no_orden_conval'
			     WHERE gestion = '$gestion_reg' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");		 				 
      pg_query("INSERT INTO imp_control (control, fech_imp, hora, usuario, cod_geo, id_inmu, gestion, cuota, observ)
				  VALUES ('$no_orden_conval','$fech_imp_conval','12:00:00','$user_id','$cod_geo','$id_inmu','$gestion_reg','$cuota_conval','Pago Convalidado')");
	 }	 
}
################################################################################
#-------------------------- RECTIFICACION (BORRAR) ----------------------------#
################################################################################	
if ((isset($_POST["Rectificar"])) AND ($_POST["Rectificar"] == "SI")) {	
	# $cod_cat = $_POST["cod_cat"];
	 $gestion_rect = $_POST["gestion_rect"];
	 $no_orden_rect = $_POST["no_orden_rect"];	
	 $sql = "DELETE FROM imp_pagados WHERE gestion = '$gestion_rect' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
	 pg_query($sql);
	 $sql = "UPDATE imp_control SET observ = 'Borrado por $user_id' 
 			      WHERE no_orden = '$no_orden_rect' AND gestion = '$gestion_rect'
						AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
   pg_query($sql); 
							 
}
################################################################################
#---------------------------- BUSQUEDA TRANSMITIDA ----------------------------#
################################################################################	 
if ((isset($_POST["Submit"])) AND ((($_POST["Submit"]) == "Ver") OR(($_POST["Submit"]) == "Volver"))) {	 
   $mostrar = true;
	# $cod_cat = $_POST["cod_cat"];
	 $id_inmu = $_POST["id_inmu"];	 
}
################################################################################
#---------------------------- SELECCION SIIM ----------------------------------#
################################################################################	 
if (isset($_POST["select"])) {
	 $select_temp = $_POST["select"];
	 $select1 = substr ($select_temp,0,6);
	 $select2 = substr ($select_temp,7,2);
#echo "$select1,$select2"; 	 
	 if ($select1 == "Buscar") {	 
	    pg_query("DELETE FROM siim_selected WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");
	 } elseif ($select1 == "Quitar") {
      $id_selected = $_POST["id"];	 
	    $sql = "UPDATE siim_selected SET selected = '0' WHERE id = '$id_selected' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
      pg_query($sql);
	 } elseif ($select1 == "Selec.") {
      $id_selected = $_POST["id"];		 
	    $sql = "UPDATE siim_selected SET id = '$id_selected', id_inmu = '$select2', selected = '1' WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
      pg_query($sql);
	 }
}
################################################################################
#---------------------------- ACTIVAR SELECCION SIIM --------------------------#
################################################################################	 
if ((isset($_POST["select"])) AND ($_POST["select"] == "Selec.")) {	
   $sql = "UPDATE siim_selected SET selected = '1' WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'"; 
	 pg_query($sql);
}
################################################################################
#                         CHEQUEAR SI EXISTE INFO_EDIF                         #
################################################################################	
$sql="SELECT id_inmu FROM info_edif WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
$check_info_edif = pg_num_rows(pg_query($sql));
if (($check_info_edif == 0 ) AND (($nivel == 2) OR ($nivel == 5))) {
   $accion_info_edif = "Añadir";
} else $accion_info_edif = "Ver";
################################################################################
#                        CHEQUEAR SI EXISTE GEO_PREDIO                         #
################################################################################	
$accion_geo_predio = "Ver";
$sql="SELECT cod_uv FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check_geo_predio = pg_num_rows(pg_query($sql));
if ($check_geo_predio == 0) {
   $geometria_existe = false;
   if (($nivel == 2) OR ($nivel == 5)) {
      $accion_geo_predio = "Añadir";
	 }
} else $geometria_existe = true;
################################################################################
#                        CHEQUEAR SI EXISTE INFO_PREDIO                        #
################################################################################	
$sql="SELECT cod_uv FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check_info_predio = pg_num_rows(pg_query($sql));
if ($check_info_predio > 0 ) {	 
      $resultado = true;
			$factor_zoom = 4;
			include "siicat_info_predio_leer_datos.php";
}
################################################################################
#--------------------------- CHEQUEAR TITULARIDAD -----------------------------#
################################################################################	
#if (($tit_cara == "Ocupante") OR ($tit_cara == "Ocupante") OR ($tit_cara == "Usufructuario")) {	 
if (($tit_cara == "Ocupante") OR ($tit_cara == "Usufructuario")) {	
   $ocupante = true;     
} else $ocupante = false; 
################################################################################
#--------------------- CHEQUEAR SI EL PREDIO ESTA ACTIVO ----------------------#
################################################################################	
$sql="SELECT activo FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check = pg_num_rows(pg_query($sql)); 
if ($check == 0) {
   $activo = 1;
} else {
   $result=pg_query($sql);
   $act = pg_fetch_array($result, null, PGSQL_ASSOC);
   $activo = $act['activo'];
}
################################################################################
#---------------- SACAR NOMBRE Y APELLIDO DE LA BASE DE DATOS -----------------#
################################################################################
$sql="SELECT con_pat, con_mat, con_nom1, pmc_ant, doc_num FROM contribuyentes WHERE id_contrib = '$tit_1id'";
$result_contrib = pg_query($sql);
$info_contrib = pg_fetch_array($result_contrib, null, PGSQL_ASSOC);		
$tit_1pat = $info_contrib['con_pat'];
$tit_1mat = $info_contrib['con_mat'];
$tit_1nom1 = $info_contrib['con_nom1']; 
$cod_pad = $info_contrib['pmc_ant'];
$doc_num = $info_contrib['doc_num'];
pg_free_result($result_contrib);
#echo "ID_CONTRIB: $tit_1id, TIT_1PAT: $tit_1pat, TIT_1MAT: $tit_1mat, TIT_1NOM1: $tit_1nom1<br />";
################################################################################
#------------------- CHEQUEAR REGISTROS EN SIMM-SATNOMBR ----------------------#
################################################################################	
$tit_1pat_temp = utf8_encode ($tit_1pat);
$tit_1mat_temp = utf8_encode ($tit_1mat);
$tit_1nom1_temp = utf8_encode ($tit_1nom1);
$cod_pad_temp = $cod_pad;
$tit_1ci_temp = $doc_num;
#$length_codpad = strlen ($cod_pad);
#$cod_pad_temp = substr($cod_pad,0,$length_codpad-3);
#$cod_pad_ext = (int) substr($cod_pad,$length_codpad-2,2);
#$tit_1ci_temp = "";
#$i = 0;
#while ($i < strlen($tit_1ci)) {
#   $char = substr($tit_1ci,$i,1);
#	 if (check_int($char)) {
#	    $tit_1ci_temp = $tit_1ci_temp.$char;
#	 }
#	 $i++;
#}
#echo "$tit_1ci_temp <br />";
$sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr WHERE documento ~* '$tit_1ci_temp'";
$check_satnombr = pg_num_rows(pg_query($sql));
if ($check_satnombr != 1) {
  $sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr WHERE paterno = '$tit_1pat_temp' AND materno = '$tit_1mat_temp' AND nombre ~* '$tit_1nom1_temp'";
  $check_satnombr = pg_num_rows(pg_query($sql));	
}
if ($check_satnombr != 1) { 
  $sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr WHERE id = '$cod_pad_temp'";
  $check_satnombr = pg_num_rows(pg_query($sql));	
}
############################################################
#---- SI ESTA DESACTIVADO EL REGISTRO EN SIIM-SELECTED ----#
############################################################	
if ($check_satnombr == 1) {
   $result_check_sel = pg_query($sql);
   $info_check_sel = pg_fetch_array($result_check_sel, null, PGSQL_ASSOC);		
   $id_check_sel = $info_check_sel['id']; 
	 pg_free_result($result_check_sel);  
   $sql_sel="SELECT id_inmu FROM siim_selected WHERE id_inmu = '$id_inmu' AND ((id = '$id_check_sel' AND selected = '0') OR (id != '$id_check_sel' AND selected = '1'))";
   $check_siim_sel = pg_num_rows(pg_query($sql_sel));
   if ($check_siim_sel > 0) {	 
      $check_satnombr = 0;
   } 
} 	
########################################
#- NO HAY RESULTADO, PERO HAY TRANSFER-#
########################################	
#echo $sql;
if ($check_satnombr == 0) { 
   $sql="SELECT tan_1id FROM transfer WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' ORDER BY tan_fech_ini DESC";
	 $check_transfer = pg_num_rows(pg_query($sql));
	 if ($check_transfer > 0) {
	    $encontrado_trans = false;
	    $result_trans = pg_query($sql);
      while (($line = pg_fetch_array($result_trans, null, PGSQL_ASSOC)) AND (!$encontrado_trans)) {
         foreach ($line as $col_value) {	
				    $tan_1id_temp = $col_value;
            $sql="SELECT con_pat, con_mat, con_nom1, tan_1ci, pmc_ant, doc_num FROM contribuyentes WHERE id_contrib = '$id_contrib' ORDER BY tan_fech_ini DESC";				 
            $result_contrib = pg_query($sql);
            $info_contrib = pg_fetch_array($result_contrib, null, PGSQL_ASSOC);		
            $tit_1pat_temp = $info_contrib['con_pat'];
            $tit_1mat_temp = $info_contrib['con_mat'];
            $tit_1nom1_temp = $info_contrib['con_nom1']; 
            $tit_1ci_temp = $info_contrib['doc_num'];							
            $cod_pad_temp = $info_contrib['pmc_ant'];
	          pg_free_result($result_contrib); 				     
            $sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr WHERE documento ~* '$tit_1ci_temp'";
            $check_satnombr = pg_num_rows(pg_query($sql));
            if ($check_satnombr != 1) {
               $sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr WHERE paterno = '$tit_1pat_temp' AND materno = '$tit_1mat_temp' AND nombre ~* '$tit_1nom1_temp'";
               $check_satnombr = pg_num_rows(pg_query($sql));	
            } else $encontrado_trans = true;
            if ($check_satnombr != 1) { 
               $sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr WHERE id = '$cod_pad_temp'";
               $check_satnombr = pg_num_rows(pg_query($sql));
							 if ($check_satnombr == 1) {
								  $encontrado_trans = true;
							 }	
            }
				 }
	    } # END_OF_WHILE							
   }
}
#$sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr WHERE paterno = '$tit_1pat_temp'";
#$check_satnombr = pg_num_rows(pg_query($sql)); 
if ($check_satnombr > 0) {
   $i = $j = 0;
   $result_siim = pg_query($sql);	 
   while ($line = pg_fetch_array($result_siim, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
         if ($i == 0) { 
	          $siim_id[$j] = $col_value;
         } elseif ($i == 1) {
				    if (trim($col_value) == "") {
						   $siim_doc[$j] = "-";   
	          } else $siim_doc[$j] = $col_value;			
         } elseif ($i == 2) {
	          $siim_pat[$j] = utf8_decode ($col_value);		
         } elseif ($i == 3) {
	          $siim_mat[$j] = utf8_decode ($col_value);		
         } elseif ($i == 4) {
	          $siim_nom[$j] = utf8_decode ($col_value);		
			   } else {
			      $siim_calle[$j] = utf8_decode ($col_value);
				    $i = -1;
			   }
			   $i++;
	    }
			$j++;
   }
	 $tabla_satinmus = "satinmus";
   $tabla_satliqin = "satliqin";
	 pg_free_result($result_siim);
}
################################################################################
#------------------ CHEQUEAR REGISTROS EN SIMM-SATNOMBR_2 ---------------------#
################################################################################

if ($check_satnombr == 0) {
/*
   $sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr_2 WHERE documento ~* '$tit_1ci_temp'";
   $check_satnombr = pg_num_rows(pg_query($sql)); 
   if ($check_satnombr != 1) {
      $sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr_2 WHERE paterno = '$tit_1pat_temp' AND materno = '$tit_1mat_temp' AND nombre ~* '$tit_1nom1_temp'";
      $check_satnombr = pg_num_rows(pg_query($sql));	
   }
#   if ($check_satnombr != 1) {
#      $sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr_2 WHERE id = '$cod_pad_temp'";
#      $check_satnombr = pg_num_rows(pg_query($sql));	
#   }
   $check_satnombr = pg_num_rows(pg_query($sql)); 
   if ($check_satnombr > 0) {
	    $aviso = true;
			$aviso_color = "red";
			$mensaje_de_aviso = "REGISTRO ENCONTRADO EN EL SIIM DE SAN CARLOS!";
	 }
   $i = $j = 0;
   $result_siim = pg_query($sql);	 
   while ($line = pg_fetch_array($result_siim, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
         if ($i == 0) { 
	          $siim_id[$j] = $col_value;
         } elseif ($i == 1) {
				    if (trim($col_value) == "") {
						   $siim_doc[$j] = "-";   
	          } else $siim_doc[$j] = $col_value;			
         } elseif ($i == 2) {
	          $siim_pat[$j] = utf8_decode ($col_value);		
         } elseif ($i == 3) {
	          $siim_mat[$j] = utf8_decode ($col_value);		
         } elseif ($i == 4) {
	          $siim_nom[$j] = utf8_decode ($col_value);		
			   } else {
			      $siim_calle[$j] = utf8_decode ($col_value);
				    $i = -1;
			   }
			   $i++;
	    }
			$j++;
   }
	 pg_free_result($result_siim);
*/	 
	 $tabla_satinmus = "satinmus_2";
	 $tabla_satliqin = "satliqin_2";	 
} 
################################################################################
#-------------------- CHEQUEAR REGISTROS EN SIMM-SATINMUS ---------------------#
################################################################################	
#$no_de_registros = 0;
$check_satinmus = 0;
if ($check_satnombr == 1) {
   $id_satinmus = $siim_id[0];
   $sql="SELECT id_inmu, zona, nombrecall, superficie, sup_const, ant_const FROM $tabla_satinmus WHERE id = '$id_satinmus' ORDER BY id_inmu ASC";
   $check_satinmus = pg_num_rows(pg_query($sql)); 
	 if ($check_satinmus == 0) {
	    $no_de_reg_satinmus = 0;
	    $satinmus_fila = 0;
	    $id_inmu_sat[$satinmus_fila] = "01";				    
		 /*	$i = 0;
			while ($i < $no_de_registros) {	    
         $id_inmu[$i] = "N.N.";
			   $zona[$i] = "N.N.";
			   $nombrecall[$i] = "N.N.";
			   $superficie[$i] = "N.N.";
			   $sup_const[$i] = "N.N.";
			   $ant_const[$i] = "N.N."; 
				 $i++;
			}	*/			 
	 }
   if ($check_satinmus == 1) {
	    $no_de_reg_satinmus = 1;
	    $satinmus_fila = 0;
	#    $id_inmu_temp[$satinmus_fila] = "01";	    
      $result_siim_satinmus = pg_query($sql);
      $info_siim_satinmus = pg_fetch_array($result_siim_satinmus, null, PGSQL_ASSOC);		
      $id_inmu_sat[0] = $info_siim_satinmus['id_inmu'];
			$zona_sat[0] = $info_siim_satinmus['zona'];
			$nombrecall_sat[0] = utf8_decode($info_siim_satinmus['nombrecall']);
			$sup_terr_sat[0] = $info_siim_satinmus['superficie'];
			$sup_const_sat[0] = $info_siim_satinmus['sup_const'];
			$ant_const_sat[0] = $info_siim_satinmus['ant_const'];
			pg_free_result($result_siim_satinmus);
	 }
   ########################################
   #---- SI EXISTE MAS QUE 1 REGISTRO-----#
   ########################################	 
	 if ($check_satinmus > 1) {
      $i = $k = 0;
      $result_siim = pg_query($sql);	 
      while ($line = pg_fetch_array($result_siim, null, PGSQL_ASSOC)) {
         foreach ($line as $col_value) {
            if ($i == 0) { 
						   $id_inmu_sat[$k] = $col_value;	
            } elseif ($i == 1) {
						   if (trim($col_value) == "") {
							    $zona_sat[$k] = "-";
	             } else $zona_sat[$k] = $col_value;	
			      } elseif ($i == 2) {
						   if (trim($col_value) == "") {
							    $nombrecall_sat[$k] = "-";
							 } else $nombrecall_sat[$k] = utf8_decode($col_value);										 							 		
			      } elseif ($i == 3) { 
	             $sup_terr_sat[$k] = $col_value;
							 $delta_sup_terr[$k] = SQRT (($ter_smen - $sup_terr_sat[$k]) * ($ter_smen - $sup_terr_sat[$k]));
#echo "TERRENO: $ter_smen, $sup_terr_sat[$k], $delta_sup_terr[$k]<br>";							 
			      } elseif ($i == 4) { 
	             $sup_const_sat[$k] = $col_value;	
							 $delta_sup_const[$k] = SQRT (($edi_area - $sup_const_sat[$k]) * ($edi_area - $sup_const_sat[$k]));
#echo "EDIF: $edi_area, $sup_const_sat[$k], $delta_sup_const[$k]<br>";								 								 								 		
			      } else {
						   $ant_const_sat[$k] = $col_value;
				       $i = -1;
			      }
			      $i++;
	       }
			   $k++;
      }
	    pg_free_result($result_siim);
      ########################################
      # DEFINIR CUAL ES EL REGISTRO CORRECTO #
      ########################################				
			$no_de_reg_satinmus = $k;
			if (($cod_pad != "") AND ($cod_pad_ext <= $no_de_reg_satinmus)) {  #TITULAR TIENE COD_PAD
			   $satinmus_fila = $cod_pad_ext-1;   
			} else {
			   $i = 0;
			   $valor_mas_peq = $delta_sup_terr[0];
			   if ($edi_area > 0) {
			      $edificaciones = true;
			   } else $edificaciones = false;
			   $satinmus_fila = 0;
			   while ($i < $no_de_reg_satinmus-1) {
			      $k = $i+1;			
			      if ($delta_sup_terr[$k] < $valor_mas_peq) {
				       $valor_mas_peq = $delta_sup_terr[$k];
						   $satinmus_fila = $k;
			      } elseif ($delta_sup_terr[$k] == $valor_mas_peq) {
				       if ($edificaciones) {
						      if (($sup_const_sat[$k-1] == 0) AND ($sup_const_sat[$k] > 0)) {
				             $valor_mas_peq = $delta_sup_terr[$k];
						         $satinmus_fila = $k;							 
							    }
						   }  
				    }
				    $i++;
			   }
			} # END_OF_ELSE (TITULAR TIENE COD_PAD)		
   }
}
################################################################################
#-------------------- CHEQUEAR REGISTROS EN SIMM-SATLIQIN ---------------------#
################################################################################	
$no_de_registros = 0;
if ($check_satnombr == 1) {
   $id_satliqin = $siim_id[0];
	 ### CHEQUEAR SI SE HA SELECCIONADO UN PREDIO
   $sql_sel="SELECT id_inmu_siim FROM siim_selected WHERE id_inmu = '$id_inmu' AND id = '$id_satliqin'";
#echo "$sql_sel<br />";
   $check_siim_sel = pg_num_rows(pg_query($sql_sel));
   if ($check_siim_sel > 0) {
	    $result_check_sel = pg_query($sql_sel);
      $info_check_sel = pg_fetch_array($result_check_sel, null, PGSQL_ASSOC);		
      $id_inmu_satliqin = $info_check_sel['id_inmu_siim'];
	    pg_free_result($result_check_sel);
			$id_inmu_marcar = (int)$id_inmu_satliqin -1; 	
   } else {
	    $id_inmu_satliqin = $id_inmu_sat[$satinmus_fila];
			$id_inmu_marcar = 99;
	 }
	 #$id_inmu_marcar = (int)$id_inmu_satliqin -1;
   $sql="SELECT gestion, tp_inmu, fd_an, imp_neto, monto, fech_venc, pagado, cuota FROM $tabla_satliqin WHERE id = '$id_satliqin' AND id_inmu = '$id_inmu_satliqin' AND pagado IS NOT NULL ORDER BY gestion ASC";
#echo "$sql<br />";   
	 $check_satliqin = pg_num_rows(pg_query($sql)); 
   if ($check_satliqin > 0) {
      $i = $j = 0;
      $result_siim_satliqin = pg_query($sql);	 
      while ($line = pg_fetch_array($result_siim_satliqin, null, PGSQL_ASSOC)) {
         foreach ($line as $col_value) {
            if ($i == 0) { 
						   if ($tabla_satliqin == "satliqin") {
						      $sistema[$j] = "SIIM";
							 } else $sistema[$j] = "SI_2";	
	             $gestion[$j] = $col_value;	
							 $siim_gestion[$j] = $col_value;	
            } elseif ($i == 1) { 
	             $edi_tipo[$j] = $col_value;			
            } elseif ($i == 2) { 
	             $edi_ano[$j] = (string) $col_value;									 										 
            } elseif ($i == 3) { 
	             $imp_neto[$j] = $col_value;							
	             $siim_imp_neto[$j] = $col_value;	
            } elseif ($i == 4) { 						
	             $monto[$j] = $col_value;								 
			      } elseif ($i == 5) { 
						   $ano_siim = substr($col_value,0,4);
							 $mes_siim = substr($col_value,4,2);
							 $dia_siim = substr($col_value,6,2);
	             $fecha_venc[$j] = $dia_siim."/".$mes_siim."/".$ano_siim;										 							 		
			      } elseif ($i == 6) { 
						   $ano_siim = substr($col_value,0,4);
							 $mes_siim = substr($col_value,4,2);
							 $dia_siim = substr($col_value,6,2);
	             $fecha_pagado[$j] = $dia_siim."/".$mes_siim."/".$ano_siim;									 		
			      } else {
						   $cuota[$j] = $col_value;
			         $siim_cuota[$j] = $col_value;
				       $i = -1;
			      }
			      $i++;
	       }
				 $forma_pago[$j] = $valor_por_m2_terr[$j] = $factores_terreno[$j] = $avaluo_terr[$j] = $calidad_const[$j] = $avaluo_const[$j] = $factor_deprec[$j] = $avaluo_total[$j] = 0;
				 $monto_exen[$j] = $cuota_fija[$j] = $tp_exen[$j] = $base_imp[$j] = $ufv_venc[$j] = $mant_valor[$j] = $interes[$j] = $monto[$j] = $d10[$j] = 0;
			   $j++;
				 $no_de_registros++;
      }
	    pg_free_result($result_siim_satliqin);
      $ultimo_ano_pagado = $gestion[$j-1];	
   } else {
	    $ultimo_ano_pagado = 0;
			$sistema[0] = "NIN";
	 }
      ########################################
      #- ESCRIBIR EN TABLA ID + ID_INMU SIIM #
      ########################################					
      $sql="SELECT id_inmu FROM siim_selected WHERE id_inmu = '$id_inmu'";
      $check_siim_selected = pg_num_rows(pg_query($sql));
			if ($check_siim_selected == 0) { 			
         pg_query("INSERT INTO siim_selected (cod_geo, id_inmu, sistem, id, id_inmu_siim, selected) 
				         VALUES ('$cod_geo','$id_inmu','$sistema[0]','$id_satliqin','$id_inmu_satliqin','1')");
			} 		 
}	 else $ultimo_ano_pagado = 0;
################################################################################
#----------------- RELLENAR FILAS CON EL REGISTRO CORRECTO --------------------#
################################################################################		 
if ($check_satinmus == 1) {
   $i = 0;
   while ($i <= $no_de_registros) {
      $id_inmu[$i] = $id_inmu_sat[0];
		  $zona[$i] = $zona_sat[0];
			$nombrecall[$i] = $nombrecall_sat[0];
			$sup_terr[$i] = $sup_terr_sat[0];
			$sup_const[$i] = $sup_const_sat[0];
			$ant_const[$i] = $ant_const_sat[0];
			$i++;
   }		
} elseif ($check_satinmus > 1) {
   $i = 0;
   while ($i <= $no_de_registros) {
      $id_inmu[$i] = $id_inmu_sat[$satinmus_fila];
      $zona[$i] = $zona_sat[$satinmus_fila];
      $nombrecall[$i] = $nombrecall_sat[$satinmus_fila];
      $sup_terr[$i] = $sup_terr_sat[$satinmus_fila];
      $sup_const[$i] = $sup_const_sat[$satinmus_fila];
      $ant_const[$i] = $ant_const_sat[$satinmus_fila];
      $i++;
   }
} 
########################################
#---- CHEQUEAR TABLA SIIM SELECTED ----#
########################################	
if ($check_satinmus >=1) {
   $sql="SELECT id_inmu FROM siim_selected WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'
	       AND sistem = '$sistema[0]' AND id = '$id_satliqin' AND id_inmu_siim = '$id_inmu_satliqin' AND selected = '1'";
   $check_siim_selected = pg_num_rows(pg_query($sql));
   if ($check_siim_selected == 0) {
      $no_de_registros = $ultimo_ano_pagado = 0;
	    $satinmus_fila = 99;  
   }
} 	
################################################################################
#----------------- CHEQUEAR REGISTROS EN TABLA IMP_PAGADOS --------------------#
################################################################################	
/*$j = $no_de_registros;
$sql="SELECT gestion, forma_pago, tp_inmu, zona, sup_terr, sup_const, fd_an, imp_neto, fech_venc, fech_imp, monto, cuota, no_orden FROM imp_pagados WHERE cod_cat = '$cod_cat' AND forma_pago != '' ORDER BY gestion ASC";
$check_imp_pag = pg_num_rows(pg_query($sql)); 
if ($check_imp_pag > 0) {
echo"GEFUNDEN";
   $result = pg_query($sql);
	 $i = 0;	 
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
            if ($i == 0) { 
						   $sistema[$j] = "CAT";	
	             $gestion[$j] = $col_value;	
            } elseif ($i == 1) { 
	             $forma_pago[$j] = trim($col_value);								 
            } elseif ($i == 2) { 
	             $tp_inmu[$j] = $col_value;	
            } elseif ($i == 3) { 
	             $zona[$j] = $col_value;	
            } elseif ($i == 4) { 
	             $sup_terr[$j] = $col_value;	
            } elseif ($i == 5) { 
	             $sup_const[$j] = $col_value;								 							 							 		
            } elseif ($i == 6) { 
	             $factor_deprec[$j] = $col_value;									 										 
            } elseif ($i == 7) { 
	             $imp_neto[$j] = $col_value;							
			      } elseif ($i == 8) { 
							 $fecha_venc[$j] = $col_value;								 							 		
			      } elseif ($i == 9) { 
							 $fecha_pagado[$j] = $col_value;
							 if ($fecha_pagado[$j] == "") {
							    $fecha_pagado[$j] = "PLAN";
							 } else {
							    $fecha_pagado[$j] = change_date ($fecha_pagado[$j]);
							 }	
            } elseif ($i == 10) { 
	             $monto[$j] = $col_value;													 								 		
			      } elseif ($i == 11) { 
	             $cuota[$j]  = $col_value;													 								 		
			      } else {
						   $no_orden[$j]= $col_value;
				       $i = -1;
			      }
			      $i++;
      }
			$valor_por_m2_terr[$j] = $factores_terreno[$j] = $avaluo_terr[$j] = $calidad_const[$j] = $ant_const[$j] = $avaluo_const[$j] = $avaluo_total[$j] = 0;
			$monto_exen[$j] = $cuota_fija[$j] = $tp_exen[$j] = $base_imp[$j] = $ufv_venc[$j] = $mant_valor[$j] = $interes[$j] = $d10[$j] = 0;			
		  $j++;
			$no_de_registros++;
   }
   pg_free_result($result);
	 $ultimo_ano_pagado = $gestion[$j-1];
}	   */
#echo "ULT. AÑO PAGADO ANTES DE CAMBIO: $ultimo_ano_pagado<br>";   
################################################################################
#-------------- DEFINIR PRIMER AÑO A PAGAR / CHEQUEAR CAMBIOS -----------------#
################################################################################
  $cambio_codigo = false;/* 
$sql="SELECT fecha_cambio, valor_ant FROM cambios WHERE cod_cat = '$cod_cat' AND variable = 'cod_cat' ORDER BY fecha_cambio, cod_cat ASC";
$check_cambios = pg_num_rows(pg_query($sql));

if ($check_cambios > 0) {

   $cambio_codigo = true; 
   $result = pg_query($sql);
	 $i = $k = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
         if ($i == 0) { 
	          $fecha_cambio_cam[$k] = $fecha_cambio = $col_value;													 								 		
			   } else {
					  $valor_ant_cam[$k] = $valor_ant = $col_value;
						$anio_cambio_cam[$k] = $anio_cambio = substr ($fecha_cambio_cam[$k],0,4);
						$buscar_gestion_cam[$k] = $anio_cambio_cam[$k]-1;
#echo "$fecha_cambio_cam[$k] $valor_ant_cam[$k] $buscar_gestion_cam[$k]<br />";							
						if (($k > 0) AND ($buscar_gestion_cam[$k-1] == $buscar_gestion_cam[$k])) {
				       #$buscar_gestion_cam[$k-1] = $buscar_gestion_cam[$k];
							 #$valor_ant_cam[$k-1] = $valor_ant_cam[$k];
						} else {
						   $k++;
						} 
				    $i = -1;
         }
			   $i++;
      }			   
   }
#echo "Anio cambio: $anio_cambio<br />";		 
	 $m = 0;
	 while ($m < $k) {
	    $fecha_cambio_temp = $fecha_cambio_cam[$m];
	    $cod_cat_ant = $valor_ant_cam[$m];
			$gestion_limite = $buscar_gestion_cam[$m];
#echo "Buscar hasta la gestion $buscar_gestion_cam[$m] con el codigo $cod_cat_ant<br />";	
      ### CHEQUEAR SI HAY PAGOS CON CODIGOS ANTERIORES
      $sql="SELECT gestion, forma_pago, tp_inmu, zona, sup_terr, sup_const, fd_an, imp_neto, fech_venc, fech_imp, monto, cuota, no_orden FROM imp_pagados 
			      WHERE cod_cat = '$cod_cat_ant' AND forma_pago != '' AND gestion <= '$gestion_limite' AND fech_imp <= '$fecha_cambio_temp'
						ORDER BY gestion ASC";
#echo "$sql<br />";							
      $check_imp_pag = pg_num_rows(pg_query($sql)); 
      if ($check_imp_pag > 0) { 
         $result = pg_query($sql);
	       $i = 0;	 
         while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            foreach ($line as $col_value) {
               if ($i == 0) { 
						      $sistema[$j] = "CAT";	
	                $gestion[$j] = $col_value;	
               } elseif ($i == 1) { 
	                $forma_pago[$j] = trim($col_value);								 
               } elseif ($i == 2) { 
	                $tp_inmu[$j] = $col_value;	
               } elseif ($i == 3) { 
	                $zona[$j] = $col_value;	
               } elseif ($i == 4) { 
	                $sup_terr[$j] = $col_value;	
               } elseif ($i == 5) { 
	                $sup_const[$j] = $col_value;								 							 							 		
               } elseif ($i == 6) { 
	                $factor_deprec[$j] = $col_value;									 										 
               } elseif ($i == 7) { 
	                $imp_neto[$j] = $col_value;							
			         } elseif ($i == 8) { 
							    $fecha_venc[$j] = $col_value;								 							 		
			         } elseif ($i == 9) { 
							    $fecha_pagado[$j] = $col_value;
							    if ($fecha_pagado[$j] == "") {
							       $fecha_pagado[$j] = "PLAN";
							    } else {
							       $fecha_pagado[$j] = change_date ($fecha_pagado[$j]);
							    }	
               } elseif ($i == 10) { 
	                $monto[$j] = $col_value;													 								 		
			         } elseif ($i == 11) { 
	                $cuota[$j]  = $col_value;													 								 		
			         } else {
						      $no_orden[$j]= $col_value;
				          $i = -1;
			         }
			         $i++;
            }
			      $valor_por_m2_terr[$j] = $factores_terreno[$j] = $avaluo_terr[$j] = $calidad_const[$j] = $ant_const[$j] = $avaluo_const[$j] = $avaluo_total[$j] = 0;
			      $monto_exen[$j] = $cuota_fija[$j] = $tp_exen[$j] = $base_imp[$j] = $ufv_venc[$j] = $mant_valor[$j] = $interes[$j] = $d10[$j] = 0;			
		        $j++;
			      $no_de_registros++;
         }
         pg_free_result($result);
	       $ultimo_ano_pagado = $gestion[$j-1];
      }
			$m++;	 
	 }
	 if ($ultimo_ano_pagado == 0) {
	    $ultimo_ano_pagado = $ult_ano;
		  $j = 0;	
	 } else $ultimo_ano_pagado = $gestion[$j-1];	 
} elseif ($check_cambios == 1) {
   $cambio_codigo = true;  
   $result_cambios = pg_query($sql);
   $info_cambios = pg_fetch_array($result_cambios, null, PGSQL_ASSOC);		
   $fecha_cambio = $info_cambios['fecha_cambio'];
   $anio_cambio = substr ($fecha_cambio,0,4);	 
	 #$ultimo_ano_pagado = substr ($fecha_cambio,0,4)-1;
	 $fecha_cambio = change_date ($fecha_cambio);
   $valor_ant = $info_cambios['valor_ant'];	
	 pg_free_result($result_cambios);	 
   ### CHEQUEAR SI HAY PAGOS CON EL CODIGO ANTERIOR
   $sql="SELECT gestion, forma_pago, tp_inmu, zona, sup_terr, sup_const, fd_an, imp_neto, fech_venc, fech_imp, monto, cuota, no_orden FROM imp_pagados WHERE cod_cat = '$valor_ant' AND forma_pago != '' ORDER BY gestion ASC";
   $check_imp_pag = pg_num_rows(pg_query($sql)); 
   if ($check_imp_pag > 0) { 
      $result = pg_query($sql);
	    $i = 0;	 
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
         foreach ($line as $col_value) {
            if ($i == 0) { 
						   $sistema[$j] = "CAT";	
	             $gestion[$j] = $col_value;	
            } elseif ($i == 1) { 
	             $forma_pago[$j] = trim($col_value);								 
            } elseif ($i == 2) { 
	             $tp_inmu[$j] = $col_value;	
            } elseif ($i == 3) { 
	             $zona[$j] = $col_value;	
            } elseif ($i == 4) { 
	             $sup_terr[$j] = $col_value;	
            } elseif ($i == 5) { 
	             $sup_const[$j] = $col_value;								 							 							 		
            } elseif ($i == 6) { 
	             $factor_deprec[$j] = $col_value;									 										 
            } elseif ($i == 7) { 
	             $imp_neto[$j] = $col_value;							
			      } elseif ($i == 8) { 
							 $fecha_venc[$j] = $col_value;								 							 		
			      } elseif ($i == 9) { 
							 $fecha_pagado[$j] = $col_value;
							 if ($fecha_pagado[$j] == "") {
							    $fecha_pagado[$j] = "PLAN";
							 } else {
							    $fecha_pagado[$j] = change_date ($fecha_pagado[$j]);
							 }	
            } elseif ($i == 10) { 
	             $monto[$j] = $col_value;													 								 		
			      } elseif ($i == 11) { 
	             $cuota[$j]  = $col_value;													 								 		
			      } else {
						   $no_orden[$j]= $col_value;
				       $i = -1;
			      }
			      $i++;
         }
			   $valor_por_m2_terr[$j] = $factores_terreno[$j] = $avaluo_terr[$j] = $calidad_const[$j] = $ant_const[$j] = $avaluo_const[$j] = $avaluo_total[$j] = 0;
			   $monto_exen[$j] = $cuota_fija[$j] = $tp_exen[$j] = $base_imp[$j] = $ufv_venc[$j] = $mant_valor[$j] = $interes[$j] = $d10[$j] = 0;			
		     $j++;
			   $no_de_registros++;
      }
      pg_free_result($result);
	    $ultimo_ano_pagado = $gestion[$j-1];
   } elseif ($ultimo_ano_pagado == 0) { 
#echo "ELSEIF: ULT. AÑO PAGADO: $ultimo_ano_pagado<br>";	 
	    $ultimo_ano_pagado = $ult_ano;
			$j = 0;
	 } else $ultimo_ano_pagado = $gestion[$j-1];	
*/	 

################################################################################
#----------------- CHEQUEAR REGISTROS EN TABLA IMP_PAGADOS --------------------#
################################################################################	
$j = $no_de_registros;
$sql="SELECT gestion, forma_pago, tp_inmu, zona, sup_terr, sup_const, fd_an, imp_neto, fech_venc, fech_imp, monto, cuota, no_orden FROM imp_pagados WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'
      AND forma_pago != '' ORDER BY gestion ASC";
$check_imp_pag = pg_num_rows(pg_query($sql)); 
if ($check_imp_pag > 0) {
   $result = pg_query($sql);
	 $i = 0;	 
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
            if ($i == 0) { 
						   $sistema[$j] = "CAT";	
	             $gestion[$j] = $col_value;	
            } elseif ($i == 1) { 
	             $forma_pago[$j] = trim($col_value);								 
            } elseif ($i == 2) { 
	             $tp_inmu[$j] = $col_value;	
            } elseif ($i == 3) { 
	             $zona[$j] = $col_value;	
            } elseif ($i == 4) { 
	             $sup_terr[$j] = $col_value;	
            } elseif ($i == 5) { 
	             $sup_const[$j] = $col_value;								 							 							 		
            } elseif ($i == 6) { 
	             $factor_deprec[$j] = $col_value;									 										 
            } elseif ($i == 7) { 
	             $imp_neto[$j] = $col_value;							
			      } elseif ($i == 8) { 
							 $fecha_venc[$j] = $col_value;								 							 		
			      } elseif ($i == 9) { 
							 $fecha_pagado[$j] = $col_value;
							 if ($fecha_pagado[$j] == "") {
							    $fecha_pagado[$j] = "PLAN";
							 } else {
							    $fecha_pagado[$j] = change_date ($fecha_pagado[$j]);
							 }	
            } elseif ($i == 10) { 
	             $monto[$j] = $col_value;													 								 		
			      } elseif ($i == 11) { 
	             $cuota[$j]  = $col_value;													 								 		
			      } else {
						   $no_orden[$j]= $col_value;
				       $i = -1;
			      }
			      $i++;
      }
			$valor_por_m2_terr[$j] = $factores_terreno[$j] = $avaluo_terr[$j] = $calidad_const[$j] = $ant_const[$j] = $avaluo_const[$j] = $avaluo_total[$j] = 0;
			$monto_exen[$j] = $cuota_fija[$j] = $tp_exen[$j] = $base_imp[$j] = $ufv_venc[$j] = $mant_valor[$j] = $interes[$j] = $d10[$j] = 0;			
		  $j++;
			$no_de_registros++;
   }
   pg_free_result($result);
	 $ultimo_ano_pagado = $gestion[$j-1];
}	elseif ($ultimo_ano_pagado == 0) {
   $ultimo_ano_pagado = $ult_ano;
   $j = 0;
} else $ultimo_ano_pagado = $gestion[$j-1];

#echo "ULT. AÑO PAGADO: $ultimo_ano_pagado<br>";
################################################################################
#----------------------- CALCULAR NUEVOS AÑOS A PAGAR -------------------------#
################################################################################
$boleta = false;
while ($ultimo_ano_pagado < $ano_actual-1) {
	 $gestion[$j] = $ultimo_ano_pagado+1;
	 $sistema[$j] = "CAT";
	 $forma_pago[$j] = "";	 
	 $final_gestion = "31/12/".$gestion[$j];										
      ########################################
      #------- CALCULAR AREA PREDIO ---------#
      ########################################
      # CALCULADA EN LINEA 170
			$sup_terr[$j] = $ter_smen;
    /*  $sql="SELECT valor_ant FROM cambios WHERE cod_cat = '$cod_cat' AND variable = 'ter_smen' AND fecha_cambio > '$final_gestion' ORDER BY fecha_cambio LIMIT 1";
      $check_cambios = pg_num_rows(pg_query($sql)); 
      if ($check_cambios > 0) {
         #$cambio_codigo = true;  
         $result_cambios = pg_query($sql);
         $info_cambios = pg_fetch_array($result_cambios, null, PGSQL_ASSOC);		
         $sup_terr[$j] = ROUND ($info_cambios['valor_ant'],2);
	       pg_free_result($result_cambios);		
      }	else {		
			   $sup_terr[$j] = $ter_smen;
			}  */
			########################################
      #----- CALCULAR AREA EDIFICACIONES ----#
      ########################################
      # CALCULADA EN LINEA 184
			$sup_const[$j]	= $edi_area;			 
		  ########################################
      #------------ DEFINIR ZONA ------------#
      ########################################
      #$cod_uv = (int) substr($cod_cat, 0, 2);
      #$cod_man = (int)substr($cod_cat, 3, 2);
      #$cod_pred = (int) substr($cod_cat, 6, 3);
      $sql="SELECT valor_ant FROM cambios WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'
			      AND variable = 'via_mat' AND fecha_cambio > '$final_gestion' ORDER BY fecha_cambio LIMIT 1";
      $check_cambios = pg_num_rows(pg_query($sql)); 
      if ($check_cambios > 0) {
         $result_cambios = pg_query($sql);
         $info_cambios = pg_fetch_array($result_cambios, null, PGSQL_ASSOC);		
         $via_mat_array[$j] = $info_cambios['valor_ant'];
	       pg_free_result($result_cambios);				 
      }	else {					
			   $via_mat_array[$j] = $via_mat; 
			}
			# FUNCION GET_ZONA
			$zona[$j] = get_zona ($cod_cat);	
			if ($zona[$j] == "0") {			
				 $error_zona = true;
				 $mensaje_de_error_zona = "Aviso: El predio con el código '$cod_cat' no se encuentra en ninguna 'Zona homogénea'!";
			   $zona[$j] = "-";
				 $val_m2_terr[$j] = 0;
			} else {						
		     ########################################
         #----------- VALOR POR M2 -------------#
         ########################################						
			   $val_m2_terr[$j] = imp_valorporm2_terr ($gestion[$j], $zona[$j], $via_mat_array[$j]);
		     if ($val_m2_terr[$j] == 0) {
				    $error = true;
				    $mensaje_de_error = "Error: No hay ningun valor para el material de vía especificado en la tabla E 'Valuación de Terrenos'!";
			   }
			}			
#echo "$gestion[$j] Zona: $zona[$j], VIA_MAT: $via_mat[$j], Valor por m2 terreno: $val_m2_terr[$j]<br>";						
		  ########################################
      #------ SERVICIOS Y INCLINACION -------#
      ########################################
			### SERVICIO DE AGUA ###
      #$ser_agu = $info['ser_agu'];
			$sql="SELECT valor_ant FROM cambios WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'
			      AND variable = 'ser_agu' AND fecha_cambio > '$final_gestion' ORDER BY fecha_cambio LIMIT 1";
      $check_cambios = pg_num_rows(pg_query($sql)); 
      if ($check_cambios > 0) {
         $result_cambios = pg_query($sql);
         $info_cambios = pg_fetch_array($result_cambios, null, PGSQL_ASSOC);		
         $ser_agu = $info_cambios['valor_ant'];
	       pg_free_result($result_cambios);		
      }			
			$fact_agu = imp_factor_serv ($gestion[$j], "serv_agua", $ser_agu);
      ### SERVICIO DE ALCANTARILLADO ###
      #$ser_alc = $info['ser_alc'];
			$sql="SELECT valor_ant FROM cambios WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'
			      AND variable = 'ser_alc' AND fecha_cambio > '$final_gestion' ORDER BY fecha_cambio LIMIT 1";
      $check_cambios = pg_num_rows(pg_query($sql)); 
      if ($check_cambios > 0) {
         $result_cambios = pg_query($sql);
         $info_cambios = pg_fetch_array($result_cambios, null, PGSQL_ASSOC);		
         $ser_alc = $info_cambios['valor_ant'];
	       pg_free_result($result_cambios);		
      }				
			$fact_alc = imp_factor_serv ($gestion[$j], "serv_alc", $ser_alc);
      ### SERVICIO DE LUZ ###		
      #$ser_luz = $info['ser_luz'];
			$sql="SELECT valor_ant FROM cambios WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'
			      AND variable = 'ser_luz' AND fecha_cambio > '$final_gestion' ORDER BY fecha_cambio LIMIT 1";
      $check_cambios = pg_num_rows(pg_query($sql)); 
      if ($check_cambios > 0) {			
         $result_cambios = pg_query($sql);
         $info_cambios = pg_fetch_array($result_cambios, null, PGSQL_ASSOC);		
         $ser_luz = $info_cambios['valor_ant'];		 
	       pg_free_result($result_cambios);		
      }				
			$fact_luz = imp_factor_serv ($gestion[$j], "serv_luz", $ser_luz);		
		  ### SERVICIO DE TELEFONO ###
      #$ser_tel = $info['ser_tel'];
			$sql="SELECT valor_ant FROM cambios WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'
			      AND variable = 'ser_tel' AND fecha_cambio > '$final_gestion' ORDER BY fecha_cambio LIMIT 1";
      $check_cambios = pg_num_rows(pg_query($sql)); 
      if ($check_cambios > 0) {
         $result_cambios = pg_query($sql);
         $info_cambios = pg_fetch_array($result_cambios, null, PGSQL_ASSOC);		
         $ser_tel = $info_cambios['valor_ant'];
	       pg_free_result($result_cambios);		
      }				
			$fact_tel = imp_factor_serv ($gestion[$j], "serv_tel", $ser_tel);	
			### SERVICIO MINIMO ###
			$fact_min = imp_factor_serv ($gestion[$j], "serv_min", "SI");						
      #$ter_topo = $info['ter_topo'];
			$fact_incl = imp_factor_incl ($gestion[$j], $ter_topo);
			$error_fact_incl = false;
			if ($fact_incl == -1) {
#echo "FACTOR_INCL es -1 <br>";
				 $error = $error_fact_incl = true;
				 $fact_incl_color = "red";
				 $mensaje_de_error_fact_incl = "Tiene que especificar la inclinación del terreno!";
      }			
			$factores_terreno[$j] = ($fact_agu + $fact_alc + $fact_luz + $fact_tel + $fact_min) * $fact_incl;
#echo "AGUA: $fact_agu, ALC: $fact_alc, LUZ: $fact_luz, TEL: $fact_tel, MIN: $fact_min, INCL: $fact_incl<br>";		
		  #if ($factores_terreno[$j] == 0) {
			#	 $error = true;
			#	 $mensaje_de_error = "Aviso: Hasta ahora no se ha ingresado los factores  para la gestión $gestion[$j]!";
			#}
		########################################
      #----------- AVALUO TERRENO -----------#
      ########################################			
			$avaluo_terr[$j] = avaluo_terreno($val_m2_terr[$j], $sup_terr[$j], $factores_terreno[$j]);
#echo "$gestion[$j] FACTORES DEL TERRENO: $factores_terreno[$j], BASE_IMP: $avaluo_terr[$j]<br>";		  
			########################################
      #------ CANTIDAD DE EDIFICACIONES -----#
      ########################################		  
			$sql="SELECT * FROM info_edif WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' ORDER BY edi_num ASC";
      $no_de_edificaciones = pg_num_rows(pg_query($sql));
			if ($no_de_edificaciones == 0) {
			   $edi_tipo[$j] = "---";
				 $ant_const[$j] = 0;
			   $factor_deprec[$j] = 0;
		     $calidad_const[$j] = 0;	
				 $avaluo_const[$j] = 	0;		    
			} else {
			   ########################################
         #- VALUACION POR MATERIALES DE CONST.--#
         ########################################				
			   $i = $k = 0;
			   $result_edif = pg_query($sql);
         while ($line = pg_fetch_array($result_edif, null, PGSQL_ASSOC)) {	
			      $line_value[$i] = $no_de_objetos_validos[$i] = 0;				 	 			           
            foreach ($line as $col_value) {
				       $column_edif = get_column_edif ($k);
						   if ($column_edif == "edi_tipo") {
						      $edi_tipo_temp[$i] = $col_value;
						   }
						   if ($column_edif == "edi_ano") {
						      $edi_ano_temp[$i] = $col_value;
						   }
#echo "COLUMNA: $column_edif tiene el valor: $col_value<br>";
			         $sql="SELECT valuacion FROM imp_valua_viv_materiales WHERE tipo = '$column_edif' AND material = '$col_value'";				 
               $check_valua = pg_num_rows(pg_query($sql));
						   if ($check_valua > 0) {
						      $result_valua = pg_query($sql);
                  $info_valua = pg_fetch_array($result_valua, null, PGSQL_ASSOC);						
						      $valua_temp = trim($info_valua['valuacion']);
							    if ($valua_temp == "margin") {						 
							       $line_value[$i] = $line_value[$i] + 1;
							    } elseif ($valua_temp == "mecono") {						 
						         $line_value[$i] = $line_value[$i] + 2;
							    } elseif ($valua_temp == "econo") {							 
						         $line_value[$i] = $line_value[$i] + 3;
							    } elseif ($valua_temp == "bueno") {						 
						         $line_value[$i] = $line_value[$i] + 4;
							    } elseif ($valua_temp == "mbueno") {							 
						         $line_value[$i] = $line_value[$i] + 5;
							    } elseif ($valua_temp == "lujoso") {
						         $line_value[$i] = $line_value[$i] + 6;
							    }
							    $no_de_objetos_validos[$i]++; 							 
                  #echo "LINE_VALUE: $line_value[$i], OBJETOS: $no_de_objetos_validos[$i] <br>";								 						 
						   }																																			
                  # $edi_temp[$i][$k] = $col_value; 	
               $k++; 
            } 
				    $line_media[$i] = $line_value[$i]/$no_de_objetos_validos[$i]; 
            #echo "LINE_VALUE TOTAL $i: $line_value[$i], OBJETOS: $no_de_objetos_validos[$i], MEDIA: $line_media[$i] <br>";   		
			      $k = 0;
	          $i++;	
         } 	
		     ########################################
         #-------- CALCULAR PORCENTAJES --------#
         ########################################
			   $avaluo_const[$j] = 0;
			   $valuacion_balanceada[$j] = 0;
			   $i = 0;
			   $check_second = false;
			   while ($i < $no_de_edificaciones){
			      $k = $i+1;
            $sql="SELECT area(the_geom) FROM edificaciones WHERE edi_num = '$k' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
            $check_area_edif = pg_num_rows(pg_query($sql));				    
	          if ($check_area_edif > 0) {
               $result=pg_query($sql);
               $area_edif = pg_fetch_array($result, null, PGSQL_ASSOC);	
				       $area_edif_temp[$i] = ROUND($area_edif['area'],2);
						   $factor_edif[$i] = $area_edif_temp[$i]/$edi_area;					
						   $line_media_ajustada[$i] = $line_media[$i] * $factor_edif[$i]; 
						   $valuacion_balanceada[$j] = $valuacion_balanceada[$j] + $line_media_ajustada[$i];
				    } else $area_edif_temp[$i] = 0;
            #echo "AREA $edi_tipo_temp[$i]: $area_edif_temp[$i], VALUACION: $line_media[$i] <br>";					 
				    $calidad_const_temp[$i] = imp_calidad_const($gestion[$j],$line_media[$i]);
				    if ($calidad_const_temp[$i] == 0) {
				       $error = true;
				       $mensaje_de_error = "Aviso: Por favor ingrese la cotización UFV del 31 de diciembre de $gestion[$j]!";
			      }
 				    $factor_deprec_temp[$i] = imp_factor_deprec($gestion[$j],$edi_ano_temp[$i],$ano_actual);	
			      $avaluo_const_temp[$i] = avaluo_const($calidad_const_temp[$i], $area_edif_temp[$i], $factor_deprec_temp[$i]);
				    if ($check_second) {
						   if ($avaluo_const_temp[$i] > $avaluo_const_mas_alto[$j]) {
						      $edi_tipo[$j] = $edi_tipo_temp[$i];
							    $ant_const[$j] = $edi_ano_temp[$i];
			            $factor_deprec[$j] = $factor_deprec_temp[$i];
		              $calidad_const[$j] = $calidad_const_temp[$i];
							    $avaluo_const_mas_alto[$j] = $avaluo_const_temp[$i];
						   }
				    } else {
			         $edi_tipo[$j] = $edi_tipo_temp[0];
						   $ant_const[$j] = $edi_ano_temp[0];
			         $factor_deprec[$j] = $factor_deprec_temp[0];				 
				       $calidad_const[$j] = $calidad_const_temp[0];
				       $avaluo_const_mas_alto[$j] = $avaluo_const_temp[0];
				    }
				    $avaluo_const[$j] = $avaluo_const[$j] + $avaluo_const_temp[$i];
            #echo "$gestion[$j] CALIDAD CONST: $calidad_const_temp[$i], FACTOR_DEPREC: $factor_deprec[$i], AVALUO CONST: $avaluo_const_temp[$i]<br>";					 
				    $check_second = true;
				    $i++;			             
			   }
			} 
      ########################################
      #------------ AVALUO TOTAL ------------#
      ########################################
	  	$avaluo_total[$j] = avaluo_total($avaluo_terr[$j], $avaluo_const[$j]);			
      ########################################
      #------------ MONTO EXCENTO -----------#
      ########################################	
			if ($gestion[$j] < 2003) {
			   $gestion_temp = 2003;
			} else $gestion_temp = $gestion[$j];
			$monto_exen[$j] = imp_getexen ($gestion_temp, $avaluo_total[$j]);
			if ($monto_exen[$j] == -1) {
			   $imp_neto[$j] = -1;
				 $cuota_fija[$j] = 0;
				 $tp_exen[$j] = 0;
				 $base_imp[$j] = 0;	
			   $error = $error_esc_imp = true;
				 $error_esc_imp_color = "orange";
				 $mensaje_de_error_esc_imp = "Por favor ingrese los valores de la Escala Impositiva para la gestión $gestion[$j]!";
			}	else {  
			   $sql="SELECT cuota, mas_porc FROM imp_escala_imp WHERE gestion = '$gestion_temp' AND exced = '$monto_exen[$j]'";
         $result = pg_query($sql);
         $info_esc = pg_fetch_array($result, null, PGSQL_ASSOC);				
			   $cuota_fija[$j] = $info_esc['cuota'];
         $tp_exen[$j] = $info_esc['mas_porc'];
				 ########################################
         #------------ BASE IMPONIBLE ----------#
         ########################################	
				 $base_imp[$j] = $avaluo_total[$j] - $monto_exen[$j];
				 ########################################
         #---------- MONTO DETERMINADO ---------#
         ########################################					 
				 $imp_neto[$j] = ROUND ($base_imp[$j] * $tp_exen[$j]/100,0) + $cuota_fija[$j];
			}	 
#echo "$gestion[$j] AVALUO TOTAL: $avaluo_total[$j], MONTO EXEN: $monto_exen[$j], CUOTA FIJA: $cuota_fija[$j], TP_EXEN: $tp_exen[$j], IMP NETO: $imp_neto[$j] <br>";	
		  ########################################
      #--------- DEFINIR FECHA VENC ---------#
      ########################################	
			if ($gestion[$j] < 2000) {
			   $gest_temp = 2000;
			} else $gest_temp = $gestion[$j];
      $sql="SELECT * FROM imp_fecha_venc WHERE gestion = '$gest_temp'";
      $result_fecha_venc = pg_query($sql);
      $info_fecha_venc = pg_fetch_array($result_fecha_venc, null, PGSQL_ASSOC);
      $fecha_venc_1st[$j] = $info_fecha_venc['fecha_venc'];
      if ($fecha_venc_1st[$j] == "") {
			   $fecha_venc[$j] = "-";
				 $error = $error_fecha_venc = true;
				 $error_fecha_venc_color = "orange";
				 $mensaje_de_error_fecha_venc = "Falta ingresar el 1er plazo de vencimiento para la gestión $gestion[$j]!";
			}	else {
			   $fecha_venc[$j] = $fecha_venc_1st[$j];
			}		
      $fecha_mod1[$j] = $info_fecha_venc['fecha_mod1'];
      $fecha_mod2[$j] = $info_fecha_venc['fecha_mod2'];
      $fecha_mod3[$j] = $info_fecha_venc['fecha_mod3'];						
			if ($fecha_mod3[$j] != "") {
			   $fecha_venc[$j] = $fecha_mod3[$j];
			} elseif ($fecha_mod2[$j] != "") {
			   $fecha_venc[$j] = $fecha_mod2[$j];
			} elseif ($fecha_mod1[$j] != "") {
			   $fecha_venc[$j] = $fecha_mod1[$j];
			} 
			pg_free_result($result_fecha_venc);
		  ########################################
      #-------- DESCUENTO Y MULTAS  ---------#
      ########################################	
      $sql="SELECT * FROM imp_base";
      $result_base = pg_query($sql);
      $info_base = pg_fetch_array($result_base, null, PGSQL_ASSOC);			  
      $descuento = $info_base['descuento'];
      $multa_mora = $info_base['multa_mora'];
      $multa_incum = 0;
      $multa_admin = $info_base['multa_admin'];		
      $por_form = $info_base['rep_form'];																				
      pg_free_result($result_base);							
	    if (!$boleta) {
         $fecha_pagado[$j] = "BOLETA";
			   $boleta = true;
	    } else $fecha_pagado[$j] = "-";
#echo "$fecha,$fecha_venc[$j]<br>";
      if ($fecha_venc[$j] == "-") {
#echo "FECHA_VENC NO ES VALIDO!!!<br>";	
			   $d10[$j] = 0;
         $monto[$j] = 0;
				 $usd_venc[$j] =  999;								 
				 $ufv_venc[$j] =  0;		
				 $mant_valor[$j] = $interes[$j] = 0;			
			} elseif ($fecha <= $fecha_venc_1st[$j]) {
			   $d10[$j] = ROUND ($imp_neto[$j]*$descuento/100,0);
         $monto[$j] = $imp_neto[$j]-$d10[$j];
				 $usd_venc[$j] =  imp_getcoti	($fecha,"usd");								 
				 $ufv_venc[$j] =  imp_getcoti	($fecha,"ufv");		
				 $mant_valor[$j] = $interes[$j] = 0;
			} elseif ($fecha <= $fecha_venc[$j]) {	
#echo "FECHA <= FECHA_VENC --> $fecha,$fecha_venc[$j]<br>";
			   $d10[$j] = 0;
         $monto[$j] = $imp_neto[$j]-$d10[$j];
				 $usd_venc[$j] =  imp_getcoti	($fecha,"usd");								 
				 $ufv_venc[$j] =  imp_getcoti	($fecha,"ufv");		
				 $mant_valor[$j] = $interes[$j] = 0;			
			} else {
#echo "FECHA > FECHA_VENC: $fecha,$fecha_venc[$j]<br>";			
			   $d10[$j] = 0;
         $usd_venc[$j] =  imp_getcoti	($fecha_venc[$j],"usd");
				 $usd_actual =  imp_getcoti	($fecha,"usd");				 
         $ufv_venc[$j] =  imp_getcoti	($fecha_venc[$j],"ufv");
				 $ufv_actual =  imp_getcoti	($fecha,"ufv");
#echo "GESTION: $gestion[$j], FECHA VENC: $fecha_venc[$j], UFV VENC: $ufv_venc[$j], FECHA ACTUAL: $fecha, UFV ACTUAL: $ufv_actual<br>";					 
         $mant_valor[$j] = calc_mant_valor($ufv_venc[$j],$ufv_actual,$imp_neto[$j]);
				 $imp_neto_act[$j] = $imp_neto[$j] + $mant_valor[$j];
				 $tasa_taprufv[$j] = imp_tasa_taprufv ($fecha);
				 if ($tasa_taprufv[$j] == -1) {
						$timestamp = strtotime($fecha.' - 1 month');
					  $fecha_ant = date('Y-m-d', $timestamp);
						$tasa_taprufv[$j] = imp_tasa_taprufv ($fecha_ant);				    
						$error_tapr = true;
						$error_tapr_color = "orange";
				    $mensaje_de_error_tapr = "Por favor ingrese la actual Tasa Activa de Paridad Referencial UFV <a href='index.php?mod=65&cot=tapr&id=$session_id'>aqui</a> (se está usando la TAPR-UFV del mes anterior de $tasa_taprufv[$j]%)!";
						if ($tasa_taprufv[$j] == -1) {
						   $error_tapr = true;
						   $timestamp = strtotime($fecha_ant.' - 1 month');
					     $fecha_ant = date('Y-m-d', $timestamp);
						   $tasa_taprufv[$j] = imp_tasa_taprufv ($fecha_ant);						
						   $error_tapr_color = "red";
				       $mensaje_de_error_tapr = "Por favor ingrese la Tasa Activa de Paridad Referencial UFV actualizada <a href='index.php?mod=65&cot=tapr&id=$session_id'>aqui</a> (TAPR-UFV hace dos meses era $tasa_taprufv[$j]%)";			   
							 if ($tasa_taprufv[$j] == -1) {
							    $error = true;
							    $tasa_taprufv[$j] = 0;
									$mensaje_de_error_tapr = "Error: No se puede calcular los intereses sin la Tasa Activa de Paridad Referencial UFV actualizada (ingresar TAPR-UFV <a href='index.php?mod=64&cot=tapr&id=$session_id'>aqui</a>)";
							 }
						}			
				 }			 
				 $no_dias_de_mora[$j] = imp_dias_de_mora ($fecha_venc[$j],$fecha);		  
#echo "MANT VALOR: $mant_valor[$j], TASA TAPR: $tasa_taprufv[$j], DIAS DE MORA: $no_dias_de_mora[$j]<br>";				 	
				 $interes[$j] = calc_interes($imp_neto_act[$j], $tasa_taprufv[$j], $no_dias_de_mora[$j]);
				 $multa_incum = imp_multa_incum ($imp_neto[$j],$ufv_venc[$j],$ufv_actual);		 						  			 		
			   $monto[$j] = $imp_neto[$j] + $mant_valor[$j] + $interes[$j] + $multa_mora + $multa_incum + $multa_admin;				 			 	
#echo "INTERES: $interes[$j], MULTA INCUM: $multa_incum, MONTO: $monto[$j]<br>";	
			}		
			if (($usd_venc[$j] == "0") OR ($usd_venc[$j] == "")) {		
		     $error = true;
				 $error_color = "red";
				 $mensaje_de_error = "Error: El sistema no encuentra una cotización de dolar actualizada (ingresar USD <a href='index.php?mod=65&cot=usd&id=$session_id'>aqui</a>)";			
			}
			#if ($usd_venc[$j] == "") {
		  #   $error = true;
			#	 $error_color = "red";
			#	 $mensaje_de_error = "Error: El sistema no encuentra una cotización de dolar actualizada (ingresar USD <a href='index.php?mod=65&cot=usd&id=$session_id'>aqui</a>)";			
			#}			
      $decont[$j] = $sal_favor[$j] = 0;
      $cuota[$j] = $monto[$j] - $decont[$j] - $sal_favor[$j] + $por_form;	
#echo "DECONT: $decont[$j], CUOTA: $cuota[$j]<br>";	
				 			  					       
   ########################################
   #-------- INGRESAR DATOS EN TABLA  ----#
   ########################################	
	 if ($error) {	
	    $imp_neto[$j] = "-";
	    $imp_neto_tabla[$j] = 0;
			$monto[$j] = 0;
			$cuota[$j] = "-";
			$cuota_tabla[$j] = 0;	  
	 } else {
	    $imp_neto_tabla[$j] = $imp_neto[$j];
	    $cuota_tabla[$j] = $cuota[$j];
	    $cod_pmc = (int) substr ($cod_pad,0,8);
	    $no_inmu = (int) substr ($cod_pad,9,2);
	    if ($no_inmu == 0) {
	       $no_inmu = 1;
	    }  
	    $ci_nit = $tit_1ci_texto;
	    if ($avaluo_const[$j] > 0) { 
	       $tp_inmu = "CASA";
	    } else $tp_inmu = "TERRENO";
			$cambios_tit1 = check_cambios($cod_geo,$id_inmu,"tit1",$final_gestion);
			if ($cambios_tit1 != -1) {
			   $titular = $cambios_tit1;
#echo "EL NOMBRE DEL TITULAR EN LA GESTION $gestion[$j] ERA: $titular<br>";				 
			} else $titular = utf8_encode ($titular1);
			#$cambios_dom_ciu = check_cambios($cod_geo,$id_inmu,"dom_ciu",$final_gestion);
		#	if ($cambios_dom_ciu != -1) {
		#	   $dom_ciu = $cambios_dom_ciu;
#echo "LA CIUDAD DEL DOMICILIO DEL TITULAR EN LA GESTION $gestion[$j] ERA: $dom_ciu<br>";				 
		#	} else $dom_ciu = utf8_encode (strtoupper ($dom_ciu));
			
#echo "DOM_DIR: $dom_dir<br>";			
#	    $dom_dir2 = utf8_encode (strtoupper ($dom_dir));
#echo "DOM_DIR: $dom_dir2<br>";	
#	    $dom_dir3 = utf8_decode (strtoupper ($dom_dir));
#echo "DOM_DIR: $dom_dir3<br>";				
	    $via_mat[$j] = strtoupper (abr ($via_mat[$j]));
      $sql="SELECT forma_pago FROM imp_pagados WHERE gestion = '$gestion[$j]' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";				 
      $check = pg_num_rows(pg_query($sql));
      $edi_tipo[$j] = trim  ($edi_tipo[$j]);
	    if ($check == 0) {
	       $sql = "INSERT INTO imp_pagados (cod_geo, id_inmu, no_inmu, gestion, forma_pago, ci_nit, tp_inmu, tit_1id, zona, via_mat, 
			          sup_terr, val_tab, fact_agu, fact_alc, fact_luz, fact_tel, fact_min, fact_incl, factor, valor_t, 
								tp_viv, valcm2, sup_const, ant_const, fd_an, valor_vi, avaluo_total, tp_exen, monto_exen, base_imp, 
								imp_neto, fech_venc, cotido, cotiufv, d10, mant_val, interes, mul_mora, deb_for, san_adm, por_form, monto,
								descont, credito, sal_favor, cuota, no_orden)
				        VALUES ('$cod_geo','$id_inmu','$no_inmu','$gestion[$j]','','$ci_nit','$tp_inmu','$tit_1id','$zona[$j]','$via_mat[$j]',
								'$sup_terr[$j]','$val_m2_terr[$j]','$fact_agu','$fact_alc','$fact_luz','$fact_tel','$fact_min','$fact_incl','$factores_terreno[$j]','$avaluo_terr[$j]',
								'$edi_tipo[$j]','$calidad_const[$j]','$sup_const[$j]','$ant_const[$j]','$factor_deprec[$j]','$avaluo_const[$j]','$avaluo_total[$j]','$tp_exen[$j]','$monto_exen[$j]','$base_imp[$j]',
								'$imp_neto_tabla[$j]','$fecha_venc[$j]','$usd_venc[$j]','$ufv_venc[$j]','$d10[$j]','$mant_valor[$j]','$interes[$j]','$multa_mora','$multa_incum','$multa_admin','$por_form','$monto[$j]',
								'0','0','$sal_favor[$j]','$cuota_tabla[$j]','0')";
#echo" $sql";									
				 pg_query($sql);
      } else { 
         $result = pg_query($sql);
         $info_pagados = pg_fetch_array($result, null, PGSQL_ASSOC);	 			           
         $forma_pago = trim ($info_pagados['forma_pago']); 
         pg_free_result($result);
			   if ($forma_pago == "") { 
#echo "UPDATE!<br>";
	          pg_query("UPDATE imp_pagados SET cod_pad = '$cod_pad', cod_pmc = '$cod_pmc', no_inmu = '$no_inmu', tp_inmu = '$tp_inmu', titular = '$titular', dom_ciu = '$dom_ciu', dom_dir = '$dom_dir', zona = '$zona[$j]', via_mat = '$via_mat[$j]', 
					         sup_terr = '$sup_terr[$j]',val_tab = '$val_m2_terr[$j]',fact_agu = '$fact_agu',fact_alc = '$fact_alc',fact_luz = '$fact_luz',fact_tel = '$fact_tel',fact_min = '$fact_min',fact_incl = '$fact_incl',factor = '$factores_terreno[$j]',valor_t = '$avaluo_terr[$j]',
									 tp_viv = '$edi_tipo[$j]',valcm2 = '$calidad_const[$j]',sup_const = '$sup_const[$j]',ant_const = '$ant_const[$j]',fd_an = '$factor_deprec[$j]',valor_vi = '$avaluo_const[$j]',avaluo_total = '$avaluo_total[$j]',tp_exen = '$tp_exen[$j]', monto_exen = '$monto_exen[$j]', base_imp = '$base_imp[$j]',
									 imp_neto = '$imp_neto_tabla[$j]',fech_venc = '$fecha_venc[$j]',cotido = '$usd_venc[$j]',cotiufv = '$ufv_venc[$j]',d10 = '$d10[$j]', mant_val = '$mant_valor[$j]',interes = '$interes[$j]',mul_mora = '$multa_mora',deb_for = '$multa_incum',san_adm = '$multa_admin',por_form = '$por_form', monto = '$monto[$j]',
									 descont = '0',  credito = '0', sal_favor = '$sal_favor[$j]', cuota = '$cuota_tabla[$j]'							 
					         WHERE gestion = '$gestion[$j]' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");
	       }
	    }	
   } #END_OF_IF (SIN ERROR) 
	 $no_de_registros++;
	 $j++;
	 $ultimo_ano_pagado++;
#echo "<br>";	 
}	 
################################################################################
#------------------------------ CHEQUEAR GRAVAMEN -----------------------------#
################################################################################	
$sql="SELECT texto FROM gravamen WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
$check_gravamen = pg_num_rows(pg_query($sql)); 
if ($check_gravamen == 0) {
   $gravamen = false;
} else {
   $gravamen = true;
}	 	
################################################################################
#------------------ CHEQUEAR SI EL PREDIO ESTA ACTIVO -------------------------#
################################################################################	
#$sql="SELECT activo FROM codigos WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
#$result_act = pg_query($sql);
#$act = pg_fetch_array($result_act, null, PGSQL_ASSOC);
#$activo = $act['activo'];
#pg_free_result($result_act);	

$activo = 1;

################################################################################
#------------------ CHEQUEAR SI EL PREDIO TIENE GEOMETRIA ---------------------#
################################################################################	
#$sql="SELECT activo FROM codigos WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
#$result_act = pg_query($sql);
#$act = pg_fetch_array($result_act, null, PGSQL_ASSOC);
#$activo = $act['activo'];
#pg_free_result($result_act);	
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################		

	# if (!$iframe) {
	 #   echo "<td>\n";
	 #}
 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
		/* # Fila 1
   echo "      <tr height=\"40px\">\n";
   echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=15&id=$session_id\" accept-charset=\"utf-8\">\n";		 
	 echo "         <td align=\"center\" valign=\"bottom\" width=\"15%\">\n";
	 if ($gravamen) {
 	    echo "       <input type=\"image\" src=\"graphics/boton_gravamen.gif\" width=\"100\" height=\"30\" border=\"0\" name=\"gravamen\" value=\"Gravamen\">\n";		
	 } else {
 	    echo "       <input type=\"image\" src=\"graphics/boton_gravamen.png\" width=\"100\" height=\"30\" border=\"0\" name=\"gravamen\" value=\"Gravamen\">\n";
	 }
   echo "            <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";		 	 
   echo "            <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";	  
	 echo "         </td>\n";   #Col. 1 	 
	 echo "         </form>\n";   #Col. 1 		    
   echo "         <td align=\"center\" valign=\"center\" width=\"60%\" class=\"pageName\">\n"; 
   echo "            Impuestos\n";
	 if ($activo == 0) {
	    echo "            <font color=\"red\"> - Archivo</font>\n";
	 }                          
   echo "         </td>\n";
	 echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";
	 $mod_lista = 60;
include "catbr_lista_formulario.php";	 */ 
	# if ($resultado) {	  
	 ################################################################################################# 
   # Fila 2	 
	 echo "      <tr>\n";  
	 #echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1    height=\"130\"                    
	 echo "         <td valign=\"top\" colspan=\"3\" class=\"bodyText\">\n";  #Col. 1+2+3
	# echo "					<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=1\" accept-charset=\"utf-8\">\n";
	# echo "         <fieldset><legend>Impuestos del Inmueble</legend>\n";  
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 21 Columnas
 	 echo "               <tr>\n"; 
	 echo "                  <td align=\"center\" width=\"4%\" class=\"bodyTextH\">Sist.</td>\n";
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";		 
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">Gestion</td>\n";
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">Sup. Terreno</td>\n";
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">Sup. Const.</td>\n";	
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"4%\" class=\"bodyTextH\">Zona</td>\n";	
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";		 
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextH\">Imp. Neto</td>\n";		 
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextH\">Monto</td>\n";	
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextH\">Fecha Venc.</td>\n";	
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextH\">Fecha Pago</td>\n";	
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextH\">Imprimir</td>\n";
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";
	 if ($nivel > 3) {		 
	    echo "                  <td align=\"center\" width=\"5%\" class=\"bodyTextH\">Borrar</td>\n";
	 } else {
	    echo "                  <td align=\"center\" width=\"5%\">&nbsp</td>\n";	 
	 }		 	 	 	                      
	 echo "               </tr>\n";
	 $i = 0;	 
	 $j = $no_de_registros-1;
	 $rect_solo1vez = false;
	 while ($i < $no_de_registros) {
	    $mostrar_boton_rectificar = true;
	    $anio_cambio_temp = $anio_cambio_de_sistema-1;	
	    if (($cambio_codigo) AND ($gestion[$j] <= $anio_cambio_temp)) {
	       $mostrar_boton_rectificar = false;		
	    }		  
	    # TABLA - FILA 2
	    echo "               <tr>\n";			 
 	    echo "                  <td align=\"center\" class=\"bodyTextD\">$sistema[$j]</td>\n";
	    echo "                  <td> &nbsp</td>\n";	  
 	    echo "                  <td align=\"center\" class=\"bodyTextD\">$gestion[$j]</td>\n";
	    echo "                  <td> &nbsp</td>\n";
 	    echo "                  <td align=\"center\" class=\"bodyTextD\">$sup_terr[$j] m²</td>\n";
	    echo "                  <td> &nbsp</td>\n"; 
 	    echo "                  <td align=\"center\" class=\"bodyTextD\">$sup_const[$j] m²</td>\n";	 
	    echo "                  <td> &nbsp</td>\n";	
	    echo "                  <td align=\"center\" class=\"bodyTextD\">$zona[$j]</td>\n";			              
	    echo "                  <td> &nbsp</td>\n";	
	    echo "                  <td align=\"right\" class=\"bodyTextD\">$imp_neto[$j] Bs. &nbsp&nbsp</td>\n";
	    echo "                  <td> &nbsp</td>\n";				
	    echo "                  <td align=\"right\" class=\"bodyTextD\">$cuota[$j] Bs.&nbsp&nbsp</td>\n";
	    echo "                  <td> &nbsp</td>\n";
			if (($sistema[$j] == "CAT") AND ($fecha_venc[$j] != "-")) {
			   $texto = change_date ($fecha_venc[$j]);
			} else $texto = $fecha_venc[$j];
	    echo "                  <td align=\"center\" class=\"bodyTextD\">$texto</td>\n";
	    echo "                  <td> &nbsp</td>\n";	
 	    echo "			            <form name=\"form1\" method=\"post\" action=\"index.php?mod=62&id=$session_id\" accept-charset=\"utf-8\">\n";			
	    echo "                  <td align=\"center\" class=\"bodyTextD\">\n";
      if (($fecha_pagado[$j] == "BOLETA") OR ($fecha_pagado[$j] == "PLAN")) {
			   if (($cuota[$j] == "-") OR ($activo == 0) OR (!$predio_existe) OR ($ocupante)) {	       
 	          echo "                     -\n";
				 } else {		
	          echo "                     <input name=\"cod_cat\" type=\"hidden\" class=\"smallText\" value=\"$cod_cat\">\n";			
	          echo "                     <input name=\"gestion\" type=\"hidden\" class=\"smallText\" value=\"$gestion[$j]\">\n";	
	          echo "                     <input name=\"imp_neto\" type=\"hidden\" class=\"smallText\" value=\"$imp_neto[$j]\">\n";													
	          echo "                     <input name=\"cuota\" type=\"hidden\" class=\"smallText\" value=\"$cuota[$j]\">\n";					
						if ($fecha_pagado[$j] == "BOLETA") {			
	             echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Liquidar\">\n";
						} else {
	             echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Plan\">\n";						
						}
				 }
			}	else {
			   echo "                     $fecha_pagado[$j]\n";			
      }
	    echo "                  </td>\n";
	    echo "                  </form>\n";				
	    echo "                  <td> &nbsp</td>\n";
 	    echo "			            <form name=\"form1\" method=\"post\" action=\"index.php?mod=61&id=$session_id\" accept-charset=\"utf-8\">\n"; 
			echo "                  <td align=\"center\" class=\"bodyTextD\">\n";
 	    echo "                     <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n"; 						
 	    echo "                     <input name=\"sistema\" type=\"hidden\" value=\"$sistema[$j]\">\n";
 	    echo "                     <input name=\"gestion\" type=\"hidden\" value=\"$gestion[$j]\">\n";
      if ($sistema[$j] == "SIIM") {			
 	       echo "                     <input name=\"siim_id\" type=\"hidden\" value=\"$siim_id[0]\">\n";			
 	       echo "                     <input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu[$j]\">\n";
			}						
			if ((($fecha_pagado[$j] == "BOLETA") OR ($fecha_pagado[$j] == "-")) AND ($activo == 1) AND ($predio_existe)) {
			   if ($cuota[$j] != "-") {	       
 	          echo "                     <input name=\"boleta\" type=\"hidden\" value=\"AVISO\">\n";
 	          echo "                     <input type=\"image\" src=\"graphics/boton_aviso.png\" width=\"36\" height=\"12\" class=\"smallText\" name=\"submit\" value=\"Aviso\">\n";
				 }
			} elseif (($fecha_pagado[$j] == "PLAN") AND ($activo == 1) AND ($predio_existe)) { 
			   echo "                     &nbsp\n";	
			} elseif (($activo == 1) AND ($predio_existe)) {	 
 	       echo "                     <input name=\"boleta\" type=\"hidden\" value=\"BOLETA\">\n"; 
 	       echo "                     <input type=\"image\" src=\"graphics/boton_boleta.png\" width=\"36\" height=\"12\" class=\"smallText\" name=\"submit\" value=\"Boleta\">\n";	
		  }	else {
 	       echo "                     -\n";				
			}		       
	    echo "                  </td>\n";	
	    echo "                  </form>\n";	
	    echo "                  <td> &nbsp</td>\n";			
 	    echo "			            <form name=\"form1\" method=\"post\" action=\"index.php?mod=60&id=$session_id\" accept-charset=\"utf-8\">\n"; 				
	    echo "                  <td align=\"center\">\n";
 	    echo "                     <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n"; 
 	    echo "                     <input name=\"Submit\" type=\"hidden\" value=\"Ver\">\n"; 
 	    echo "                     <input name=\"gestion\" type=\"hidden\" value=\"$gestion[$j]\">\n"; 
			if (($nivel > 3) AND ($sistema[$j] == "CAT") AND ($fecha_pagado[$j] != "BOLETA") AND ($fecha_pagado[$j] != "PLAN") AND ($fecha_pagado[$j] != "-") AND ($activo == 1) AND ($mostrar_boton_rectificar)) {					
 	       if (!$rect_solo1vez) {
				    echo "                     <input name=\"no_orden_rect\" type=\"hidden\" value=\"$no_orden[$j]\">\n";
						echo "                     <input type=\"image\" src=\"graphics/boton_boleta.png\" width=\"12\" height=\"12\" class=\"smallText\" name=\"Rectificar\" value=\"Rectificar\">\n";				
			      $rect_solo1vez = true;
				 }
			}
			echo "                  </td>\n";
	    echo "                  </form>\n";															 	 	  	  	 	 
	    echo "               </tr>\n";
			$i++;
			$j--;
	 } 
	 if ($ocupante) {	
	    echo "      <tr>\n";
      echo "         <td align=\"center\" colspan=\"21\" class=\"bodyTextD\">\n";  #Col. 1	
	    echo "            <font color=\"orange\"> Si no es Poseedor o Propietario no puede cancelar los impuestos del inmueble!</font>\n";
			echo "         </td>\n";   
	    echo "      </tr>\n";
   } 	 	 
	 if ($cambio_codigo) {
 	    echo "               <tr>\n"; 
	    echo "                  <td align=\"center\" colspan=\"21\" class=\"bodyTextD\">\n";
	    echo "                     El código del predio cambió en fecha $fecha_cambio. El código anterior era $valor_ant.\n";		 
	    echo "                  </td>\n";	
	    echo "               </tr>\n";			
	 }		      		  
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";

	 echo "         </td>\n";
	# echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 3   		
	 echo "      </tr>\n";
   ########################################
   #------------ RECTIFICADO  ------------#
   ########################################		 
   if ((isset($_POST["Rectificar"])) AND ($_POST["Rectificar"] == "Rectificar"))  {
	    $gestion_rect = $_POST["gestion"];
			$no_orden_rect = $_POST["no_orden_rect"];
	    echo "	    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=60&id=$session_id\" accept-charset=\"utf-8\">\n";			
	    echo "      <tr>\n"; 	 
	    echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3 	 
	    echo "            <font color=\"red\"><b> CUIDADO: SE BORRARA EL REGISTRO SELECCIONADO! Realmente quiere borrar el pago para la gestión $gestion_rect?</b></font>\n"; 
	    echo "            <input name=\"Rectificar\" type=\"submit\" class=\"smallText\" value=\"SI\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";				  		 
	    echo "            <input name=\"Rectificar\" type=\"submit\" class=\"smallText\" value=\"NO\">\n";
			echo "            <input name=\"no_orden_rect\" type=\"hidden\" value=\"$no_orden_rect\">\n";				
			echo "            <input name=\"gestion_rect\" type=\"hidden\" value=\"$gestion_rect\">\n";					 			
			echo "            <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";					 
		  echo "            <input name=\"Submit\" type=\"hidden\" value=\"Ver\">\n";		       
		  echo "            <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";					
	    echo "         </td>\n";
	    echo "      </tr>\n";	
			echo "      </form>\n";	
	 }
	 ########################################
   # Fila 2
	 echo "      <tr>\n";  
	 #echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td valign=\"top\" colspan=\"3\" class=\"bodyText\">\n";  #Col. 1+2+3	  
	 echo "					<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id#tab-9\" accept-charset=\"utf-8\">\n";
	# if (($tabla_satliqin == "satliqin") OR ($check_satnombr == 0)) {
	    echo "         <fieldset><legend>Datos del propietario actual registrados en el programa SIIM </legend>\n";
	# } #else {
	 #   echo "         <fieldset><legend>Datos registrados en el programa SIIM - San Carlos</legend>\n";
	 #}
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 13 Columnas 
 	 echo "               <tr>\n";
	 echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextH\">ID</td>\n";
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextH\">Documento</td>\n";
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">Paterno</td>\n";	
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">Materno</td>\n";	
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"18%\" class=\"bodyTextH\">Nombre</td>\n";	
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"22%\" class=\"bodyTextH\">Calle</td>\n";	
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"10%\">\n";
	 #if ($check_satnombr > 0 ) {
	    echo "                     <input name=\"select\" type=\"submit\" class=\"smallText\" value=\"Buscar\">\n";
	 #}
	 echo "                  </td>\n";		 	 	 	                      
	 echo "               </tr>\n";
   if ($check_satnombr == 0) {	
 	    echo "               <tr>\n";
	    echo "                  <td align=\"center\" colspan=\"13\" class=\"bodyTextD\">\n";
	    echo "                     No hay registros relacionados con el Apellido del Títular en la base de datos del programa SIIM!\n";
	    echo "                  </td>\n";
	    echo "               </tr>\n";
   } else { 
      $i = 0;
	    while ($i < $check_satnombr) {
	       # TABLA - FILA 1
	       echo "               <tr>\n";	  
 	       echo "                  <td align=\"center\" class=\"bodyTextD\">$siim_id[$i]</td>\n";
	       echo "                  <td> &nbsp</td>\n";
 	       echo "                  <td align=\"center\" class=\"bodyTextD\">$siim_doc[$i]</td>\n";
	       echo "                  <td> &nbsp</td>\n"; 
 	       echo "                  <td align=\"center\" class=\"bodyTextD\">$siim_pat[$i]</td>\n";	               
	       echo "                  <td> &nbsp</td>\n";	
	       echo "                  <td align=\"center\" class=\"bodyTextD\">$siim_mat[$i]</td>\n";
	       echo "                  <td> &nbsp</td>\n";	
	       echo "                  <td align=\"center\" class=\"bodyTextD\">$siim_nom[$i]</td>\n";	 
	       echo "                  <td> &nbsp</td>\n";	
	       echo "                  <td align=\"center\" class=\"bodyTextD\">$siim_calle[$i]</td>\n";	
	       echo "                  <td> &nbsp</td>\n";	
	       echo "                  <td align=\"center\" class=\"bodyTextD\">&nbsp</td>\n";		  	  	  	 	 
	       echo "               </tr>\n"; 	
         $i++;
      } 	 
   } 
	 if ($check_satinmus >= 1) {
	    echo "            <table border=\"0\" width=\"100%\">\n";   # 13 Columnas 
 	    echo "               <tr>\n";
	    echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">Selec.</td>\n";
	    echo "                  <td width=\"1%\"> &nbsp</td>\n";				
	    echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextH\">ID_Inmueble</td>\n";
	    echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	    echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextH\">Zona</td>\n";
	    echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	    echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">Sup. Terreno</td>\n";	
	    echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	    echo "                  <td align=\"center\" width=\"12%\" class=\"bodyTextH\">Sup. Const.</td>\n";	
	    echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	    echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextH\">Año de Constr.</td>\n";	
	    echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	    echo "                  <td align=\"center\" width=\"22%\" class=\"bodyTextH\">Calle</td>\n";	
	    echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	    echo "                  <td align=\"center\" width=\"10%\"> &nbsp</td>\n";		 	 	 	                      
	    echo "               </tr>\n";
			$i = 0;
			while ($i < $no_de_reg_satinmus) {	
 	       echo "               <tr>\n";
			   if ($i == $id_inmu_marcar) {				 
	          echo "                  <td align=\"center\" class=\"bodyTextD\"> X </td>\n";
				 } else {
	          echo "                  <td align=\"center\" class=\"bodyTextD\"> &nbsp </td>\n";
				 }				 
	       echo "                  <td> &nbsp</td>\n";					 
	       echo "                  <td align=\"center\" class=\"bodyTextD\">$id_inmu_sat[$i]</td>\n";
	       echo "                  <td> &nbsp</td>\n";	
	       echo "                  <td align=\"center\" class=\"bodyTextD\">$zona_sat[$i]</td>\n";
	       echo "                  <td> &nbsp</td>\n";	
	       echo "                  <td align=\"center\" class=\"bodyTextD\">$sup_terr_sat[$i]</td>\n";	
	       echo "                  <td> &nbsp</td>\n";	
	       echo "                  <td align=\"center\" class=\"bodyTextD\">$sup_const_sat[$i]</td>\n";	
	       echo "                  <td> &nbsp</td>\n";	
	       echo "                  <td align=\"center\" class=\"bodyTextD\">$ant_const_sat[$i]</td>\n";	
	       echo "                  <td> &nbsp</td>\n";	
	       echo "                  <td align=\"center\" class=\"bodyTextD\">$nombrecall_sat[$i]</td>\n";	
	       echo "                  <td> &nbsp</td>\n";	
	       echo "                  <td align=\"center\">\n";
				 if ($i == $id_inmu_marcar) {
				    $value_string = "Quitar ".$id_inmu_sat[$i];
	          echo "                  <input name=\"select\" type=\"submit\" class=\"smallText\" value=\"$value_string\">\n";
				 } else {		
				    $value_string = "Selec. ".$id_inmu_sat[$i];
	          echo "                  <input name=\"select\" type=\"submit\" class=\"smallText\" value=\"$value_string\">\n";
			   }
				 echo "                  <input name=\"id\" type=\"hidden\" value=\"$siim_id[0]\">\n";					 			 				 			 
				 echo "                   </td>\n";		 	 	 	                      
	       echo "               </tr>\n";
			   $i++;
	    }			 	
	 }	
	 echo "                  <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";					 
	 echo "                  <input name=\"Submit\" type=\"hidden\" value=\"Ver\">\n";		       
	 echo "                  <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";	      		  
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";
	 echo "         </form>\n";
	 echo "         </td>\n";
	# echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 3   		
	 echo "      </tr>\n";	  
	 if ($error_zona) {	
	    echo "      <tr>\n";
      echo "         <td align=\"center\" colspan=\"3\" class=\"bodyTextD\">\n";  #Col. 1	
	    echo "            <font color=\"red\"> $mensaje_de_error_zona</font>\n";
			echo "         </td>\n";   
	    echo "      </tr>\n";
   } 	 
	 if ($error_fact_incl) {	
	    echo "      <tr>\n";
      echo "         <td align=\"center\" colspan=\"3\" class=\"bodyTextD\">\n";  #Col. 1	
	    echo "            <font color=\"$fact_incl_color\"> $mensaje_de_error_fact_incl</font>\n";
			echo "         </td>\n";   
	    echo "      </tr>\n";
   } 
	 if ($error_fecha_venc) {	
	    echo "      <tr>\n";
      echo "         <td align=\"center\" colspan=\"3\" class=\"bodyTextD\">\n";  #Col. 1	
	    echo "            <font color=\"$error_fecha_venc_color\"> $mensaje_de_error_fecha_venc</font>\n";
			echo "         </td>\n";   
	    echo "      </tr>\n";
   }
	 if ($error_esc_imp) {	
      echo "      <tr>\n";
      echo "         <td align=\"center\" colspan=\"3\" class=\"bodyTextD\">\n";  #Col. 1	
	    echo "            <font color=\"$error_esc_imp_color\"> $mensaje_de_error_esc_imp</font>\n";
			echo "         </td>\n";   
	    echo "      </tr>\n";
   } 
	 if ($error_tapr) {	
	    echo "      <tr>\n";
      echo "         <td align=\"center\" colspan=\"3\" class=\"bodyTextD\">\n";  #Col. 1	
	    echo "            <font color=\"$error_tapr_color\"> $mensaje_de_error_tapr</font>\n";
			echo "         </td>\n";   
	    echo "      </tr>\n";
   } 	 	 
	 if ($error) {	
	    echo "      <tr>\n";
      echo "         <td align=\"center\" colspan=\"3\" class=\"bodyTextD\">\n";  #Col. 1	
	    echo "            <font color=\"red\"> $mensaje_de_error</font>\n";
			echo "         </td>\n";   
	    echo "      </tr>\n";
   }
	 if ($aviso) {	
	    echo "      <tr>\n";
      echo "         <td align=\"center\" colspan=\"3\" class=\"bodyTextD\">\n";  #Col. 1	
	    echo "            <font color=\"$aviso_color\"> $mensaje_de_aviso</font>\n";
			echo "         </td>\n";   
	    echo "      </tr>\n";
   }
	 echo "      <tr height=\"40\">\n";
	 echo "         <td align=\"center\" colspan=\"3\">\n";   #Col. 1+2+3
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 2 Columnas
 	 echo "               <tr>\n";
   echo "			             <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=69&id=$session_id\" accept-charset=\"utf-8\">\n";	  
	 echo "                  <td align=\"right\" width=\"40%\">\n";
 	 echo "                     <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";
   echo "                     <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n"; 
   echo "                     <input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Ver Historial\">\n";
	 echo "                  </td>\n";	
	 echo "                  </form>\n";
   echo "			             <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=67&id=$session_id\" accept-charset=\"utf-8\">\n";	  
	 echo "                  <td align=\"center\" width=\"20%\">\n";
 	 echo "                     <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";
   echo "                     <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n"; 
   echo "                     <input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Transferencia\">\n";
	 echo "                  </td>\n";	
	 echo "                  </form>\n";	 		  
   echo "			             <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=32&id=$session_id\" accept-charset=\"utf-8\">\n"; 
	 echo "                  <td align=\"left\" width=\"40%\">\n";
 	 echo "                     <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n"; 	 		 
   echo "                     <input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Certificado Catastral\">\n"; 
	 echo "                  </td>\n";
	 echo "                  </form>\n";	 
	 echo "               </tr>\n";	 
	 echo "            </table>\n";	 
	 echo "         </td>\n";	  
	 echo "      </tr>\n";
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
	 echo "   <br />&nbsp;<br />\n";
	# if (!$iframe) {
	#    echo "</td>\n";
	# } 
?>