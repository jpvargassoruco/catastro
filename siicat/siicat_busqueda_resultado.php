<?php
$mostrar = false;
$resultado = false;
$error = false;
$predio_existe = false;
$geometria_existe = true;

################################################################################
#---------------------- ACEPTAR CAMBIOS DE GEOMETRIA --------------------------#
################################################################################	

if ((isset($_POST["submit"])) and ($_POST["submit"] == "Aceptar Cambios")) {
	if (isset($_POST["action"])) {
		$action = $_POST["action"];
	} else
		$action = "";
	$cod_cat = $_POST["cod_cat"];
	echo $cod_cat;
	$sql = "SELECT cod_cat, the_geom FROM temp_poly WHERE user_id = '$user_id' AND number = '10' ORDER BY cod_cat";
	$check_rows = pg_num_rows(pg_query($sql));
	if ($check_rows == 2) { #DIVISION DE PREDIO
		$mismo_codigo = false;

		#-------- BORRAR COLINDANTES ----------#					
		$sql_col = "SELECT cod_cat FROM predios WHERE st_dwithin ((SELECT the_geom FROM predios WHERE cod_cat ='$cod_cat'),the_geom,1) AND activo = '1'";
		$result_col = pg_query($sql_col);
		$i = $j = 0;
		while ($line_col = pg_fetch_array($result_col, null, PGSQL_ASSOC)) {
			foreach ($line_col as $col_value_col) {
				pg_query("DELETE FROM colindantes WHERE cod_cat = '$col_value_col'");
			}
		}
		pg_free_result($result_col);

		#-- MOVER NUEVOS PREDIOS DE TEMP_POLY -#
		$result = pg_query($sql);
		$i = 0;
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
			foreach ($line as $col_value) {
				if ($i == 0) {
					$cod_cat_nuevo = $col_value;
				} else {
					$the_geom_nuevo = $col_value;

					# CHEQUEAR SI COD_CAT == COD_CAT_NUEVO #
					if ($cod_cat == $cod_cat_nuevo) {
						$mismo_codigo = true;
						$sql = "SELECT area(the_geom) FROM predios WHERE cod_cat = '$cod_cat'";
						$result_mismo = pg_query($sql);
						$valor_area = pg_fetch_array($result_mismo, null, PGSQL_ASSOC);
						$ter_smen_ant = ROUND($valor_area['area'], 2);
						pg_free_result($result_mismo);
						pg_query("INSERT INTO cambios (cod_cat, fecha_cambio, variable, valor_ant)
				                VALUES ('$cod_cat_nuevo','$fecha','ter_smen','$ter_smen_ant')");
						pg_query("UPDATE predios SET the_geom = '$the_geom_nuevo' WHERE cod_cat = '$cod_cat_nuevo'");
						pg_query("UPDATE predios_ocha SET the_geom = '$the_geom_nuevo' WHERE cod_cat = '$cod_cat_nuevo'");
					} else {

						#----- CREAR GEOMETRIA EN PREDIOS -----#
						$cod_uv = (int) substr($cod_cat_nuevo, 0, 2);
						$cod_man = (int) substr($cod_cat_nuevo, 3, 2);
						$cod_pred = (int) substr($cod_cat_nuevo, 6, 3);
						#echo "<br>COD:$cod_uv ,$cod_man,$cod_pred,$the_geom_nuevo";									
						pg_query("INSERT INTO predios (cod_cat, cod_uv, cod_man, cod_pred, activo, the_geom)
				                VALUES ('$cod_cat_nuevo','$cod_uv','$cod_man','$cod_pred','1','$the_geom_nuevo')");
						pg_query("INSERT INTO predios_ocha (cod_cat, cod_uv, cod_man, cod_pred, activo, the_geom)
				                VALUES ('$cod_cat_nuevo','$cod_uv','$cod_man','$cod_pred','1','$the_geom_nuevo')");
						########################################
						#----- AÑADIR REGISTRO EN CODIGOS -----#
						########################################	
						$sql = "SELECT cod_cat FROM codigos WHERE cod_cat = '$cod_cat_nuevo'";
						$check_codigos = pg_num_rows(pg_query($sql));
						if ($check_codigos == 0) {
							pg_query("INSERT INTO codigos (cod_cat, activo) VALUES ('$cod_cat_nuevo','1')");
						}
						########################################
						#---- CREAR REGISTRO EN INFO_PREDIO ---#
						########################################			
						$sql = "SELECT cod_cat FROM info_predio WHERE cod_cat = '$cod_cat_nuevo'";
						$check_info_predio = pg_num_rows(pg_query($sql));
						if ($check_info_predio == 0) {
							pg_query("INSERT INTO info_predio (cod_geo, cod_cat, cod_uv, cod_man, cod_pred) 
                            VALUES ('$cod_geo','$cod_cat_nuevo','$cod_uv','$cod_man','$cod_pred')");
							########################################
							#--- LLENAR REGISTRO EN INFO_PREDIO ---#
							########################################	
							$sql = "SELECT * FROM info_predio WHERE cod_cat = '$cod_cat'";
							$result2 = pg_query($sql);
							$info = pg_fetch_array($result2, null, PGSQL_ASSOC);
							$cod_pad = $info['cod_pad'];
							$dir_tipo = $info['dir_tipo'];
							$dir_nom = $info['dir_nom'];
							$dir_num = $info['dir_num'];
							$dir_edif = $info['dir_edif'];
							$dir_bloq = $info['dir_bloq'];
							$dir_piso = $info['dir_piso'];
							$dir_apto = $info['dir_apto'];
							$tit_pers = $info['tit_pers'];
							$tit_cant = $info['tit_cant'];
							$tit_bene = $info['tit_bene'];
							$tit_cara = $info['tit_cara'];
							$tit_1nom1 = utf8_decode($info['tit_1nom1']);
							$tit_1nom2 = utf8_decode($info['tit_1nom2']);
							$tit_1pat = utf8_decode($info['tit_1pat']);
							$tit_1mat = utf8_decode($info['tit_1mat']);
							$tit_1nom1 = utf8_decode($info['tit_1nom1']);
							$tit_1nom2 = utf8_decode($info['tit_1nom2']);
							$tit_1ci = $info['tit_1ci'];
							$tit_1nit = $info['tit_1nit'];
							$tit_2pat = utf8_decode($info['tit_2pat']);
							$tit_2mat = utf8_decode($info['tit_2mat']);
							$tit_2nom1 = utf8_decode($info['tit_2nom1']);
							$tit_2nom2 = utf8_decode($info['tit_2nom2']);
							$tit_2ci = $info['tit_2ci'];
							$tit_2nit = $info['tit_2nit'];
							$dom_dpto = $info['dom_dpto'];
							$dom_ciu = utf8_decode($info['dom_ciu']);
							$dom_dir = utf8_decode($info['dom_dir']);
							$adq_modo = $info['adq_modo'];
							$adq_doc = $info['adq_doc'];
							$adq_fech = $fecha;
							$der_num = $info['der_num'];
							if ($info['der_fech'] == "") {
								$der_fech = "1900-01-01";
							} else
								$der_fech = $info['der_fech'];
							$via_tipo = $info['via_tipo'];
							$via_clas = $info['via_clas'];
							$via_uso = $info['via_uso'];
							$via_mat = $info['via_mat'];
							$ser_alc = $info['ser_alc'];
							$ser_agu = $info['ser_agu'];
							$ser_luz = $info['ser_luz'];
							$ser_tel = $info['ser_tel'];
							$ser_gas = $info['ser_gas'];
							$ser_alu = $info['ser_alu'];
							$ser_cab = $info['ser_cab'];
							$ter_topo = $info['ter_topo'];
							$ter_form = $info['ter_form'];
							$ter_ubi = $info['ter_ubi'];
							$ter_nofr = $info['ter_nofr'];
							$ter_fond = $info['ter_fond'];
							$ter_fren = $info['ter_fren'];
							$ter_sdoc = "";
							$ter_eesp = $info['ter_eesp'];
							$esp_aac = $info['esp_aac'];
							$esp_tas = $info['esp_tas'];
							$esp_tae = $info['esp_tae'];
							$esp_ser = $info['esp_ser'];
							$esp_gar = $info['esp_gar'];
							$esp_dep = $info['esp_dep'];
							$mej_lav = $info['mej_lav'];
							$mej_par = $info['mej_par'];
							$mej_hor = $info['mej_hor'];
							$mej_pis = $info['mej_pis'];
							$mej_otr = $info['mej_otr'];
							$ter_uso = $info['ter_uso'];
							$ter_mur = $info['ter_mur'];
							$ter_san = $info['ter_san'];
							$res_enc = textconvert($info['res_enc']);
							$res_sup = textconvert($info['res_sup']);
							$res_obs = $info['res_obs'];
							$res_fech = $info['res_fech'];
							if ($res_fech == "") {
								$res_fech = "1900-01-01";
							}
							pg_free_result($result2);
							pg_query("UPDATE info_predio SET cod_uv = '$cod_uv', cod_man = '$cod_man', cod_pred= '$cod_pred', cod_pad = '$cod_pad',
			               dir_tipo = '$dir_tipo', dir_nom = '$dir_nom', dir_num = '$dir_num',
				             dir_edif = '$dir_edif', dir_bloq = '$dir_bloq', dir_piso = '$dir_piso', dir_apto = '$dir_apto',
				             tit_pers = '$tit_pers', tit_cant = '$tit_cant', tit_bene = '$tit_bene', 
				             tit_1pat = '$tit_1pat', tit_1mat = '$tit_1mat', tit_1nom1 = '$tit_1nom1', tit_1nom2 = '$tit_1nom2',
				             tit_1ci = '$tit_1ci', tit_1nit = '$tit_1nit',
				             tit_2pat = '$tit_2pat', tit_2mat = '$tit_2mat', tit_2nom1 = '$tit_2nom1', tit_2nom2 = '$tit_2nom2',
				             tit_2ci = '$tit_2ci', tit_2nit = '$tit_2nit', tit_cara = '$tit_cara',
				             dom_dpto = '$dom_dpto', dom_ciu = '$dom_ciu', dom_dir = '$dom_dir',	
				             adq_modo = '$adq_modo', adq_doc = '$adq_doc', adq_fech = '$adq_fech', der_num = '$der_num', der_fech = '$der_fech',	
				             via_tipo = '$via_tipo', via_clas = '$via_clas', via_uso = '$via_uso', via_mat = '$via_mat',
				             ser_alc	= '$ser_alc', ser_agu	= '$ser_agu',	ser_luz	= '$ser_luz',	ser_tel	= '$ser_tel',	
				             ser_gas	= '$ser_gas',	ser_alu	= '$ser_alu',	ser_cab	= '$ser_cab',
				             ter_topo = '$ter_topo', ter_form = '$ter_form', ter_ubi = '$ter_ubi', ter_fren = '$ter_fren', 
				             ter_fond = '$ter_fond', ter_nofr = '$ter_nofr', ter_sdoc = '$ter_sdoc', ter_eesp = '$ter_eesp',
				             esp_aac = '$esp_aac', esp_tas = '$esp_tas', esp_tae = '$esp_tae',
				             esp_ser = '$esp_ser', esp_gar = '$esp_gar', esp_dep = '$esp_dep', 
				             mej_lav = '$mej_lav', mej_par = '$mej_par', mej_hor = '$mej_hor', mej_pis = '$mej_pis', mej_otr = '$mej_otr',
				             ter_uso = '$ter_uso', ter_mur = '$ter_mur', ter_san = '$ter_san',
				             res_enc = '$res_enc', res_sup = '$res_sup', res_fech = '$res_fech', res_obs = '$res_obs'		  
			               WHERE cod_cat = '$cod_cat_nuevo'");
						}
						########################################				 
						#- CHEQUEAR GEOMETRIA DE EDIFICACIONES #
						########################################			
						$sql = "SELECT cod_cat FROM edificaciones WHERE cod_cat = '$cod_cat'";
						$check = pg_num_rows(pg_query($sql));
						if ($check > 0) {
							#									   $sql="SELECT edi_num, st_intersects (the_geom,(SELECT the_geom from predios where cod_cat ='$cod_cat')) AS inter 
							$sql = "SELECT edi_num, the_geom
										       FROM edificaciones WHERE cod_cat = '$cod_cat' AND edi_piso = '1'  ORDER BY edi_num";
							$result = pg_query($sql);
							$i = 0;
							$no_de_edificacion = 1;
							$edif_borrada = false;
							while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
								foreach ($line as $col_value) {
									if ($i == 0) {
										$edi_num = $col_value;
									} else {
										$the_geom_edi = $col_value;
										### CHEQUEAR EL AREA DE LA EDIFICACION EN LOS PREDIOS														
										$sql = "SELECT area(ST_Intersection((SELECT the_geom FROM predios WHERE cod_cat = '$cod_cat'), (SELECT the_geom FROM edificaciones WHERE cod_cat = '$cod_cat' AND edi_num = '$edi_num' AND edi_piso = '1')))";
										$result_area = pg_query($sql);
										$valor_area = pg_fetch_array($result_area, null, PGSQL_ASSOC);
										$area1 = $valor_area['area'];
										pg_free_result($result_area);
										$sql = "SELECT area(ST_Intersection((SELECT the_geom FROM predios WHERE cod_cat = '$cod_cat_nuevo'), (SELECT the_geom FROM edificaciones WHERE cod_cat = '$cod_cat' AND edi_num = '$edi_num' AND edi_piso = '1')))";
										$result_area = pg_query($sql);
										$valor_area = pg_fetch_array($result_area, null, PGSQL_ASSOC);
										$area2 = $valor_area['area'];
										pg_free_result($result_area);
										### SI LA EDIFICACION SE ENCUENTRA EN SU MAYORIA DENTRO DEL PREDIO CON EL NUEVOI CODIGO															
										if ($area2 > $area1) {
											pg_query("INSERT INTO edificaciones SELECT '99-99-999', edi_num, edi_piso, the_geom
				                                   FROM edificaciones WHERE cod_cat = '$cod_cat' AND edi_num = '$edi_num' ORDER BY edi_num, edi_piso");
											pg_query("UPDATE edificaciones SET cod_cat = '$cod_cat_nuevo', edi_num = '$no_de_edificacion' WHERE cod_cat = '99-99-999'");
											### MOVER LOS DATOS DE LA EDIFICACION AL PREDIO CON EL NUEVO CODIGO
											pg_query("INSERT INTO info_edif SELECT cod_geo, '99-99-999', edi_num, edi_piso, edi_ubi, edi_tipo, edi_edo,
							                             edi_ano, edi_cim, edi_est, edi_mur, edi_acab, edi_rvin, edi_rvex, edi_rvba, edi_rvco, edi_cest, edi_ctec,
							                             edi_ciel, edi_coc, edi_ban, edi_carp, edi_elec
				                                   FROM info_edif WHERE cod_cat = '$cod_cat' AND edi_num = '$edi_num' ORDER BY edi_num, edi_piso");
											pg_query("UPDATE info_edif SET cod_cat = '$cod_cat_nuevo', edi_num = '$no_de_edificacion' WHERE cod_cat = '99-99-999'");
											$no_de_edificacion++;
											### BORRAR LA GEOMETRIA Y LOS DATOS DEL PREDIO CON EL ANTIGUO CODIGO
											pg_query("DELETE FROM edificaciones WHERE cod_cat = '$cod_cat' AND edi_num = '$edi_num'");
											pg_query("DELETE FROM info_edif WHERE cod_cat = '$cod_cat' AND edi_num = '$edi_num'");
											$edif_borrada = true;
										}
										$i = -1;
									}
									$i++;
								}
							}
							pg_free_result($result);
							### REORDENAR LOS NUMEROS DE LAS EDIFICACIONES
							if ($edif_borrada) {
								$sql = "SELECT DISTINCT edi_num FROM edificaciones WHERE cod_cat = '$cod_cat' ORDER BY edi_num";
								$result = pg_query($sql);
								$i = 1;
								while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
									foreach ($line as $col_value) {
										$edi_num = $col_value;
										if ($edi_num != $i) {
											pg_query("UPDATE edificaciones SET edi_num = '$i' WHERE cod_cat = '$cod_cat' AND edi_num = '$edi_num'");
											pg_query("UPDATE info_edif SET edi_num = '$i' WHERE cod_cat = '$cod_cat' AND edi_num = '$edi_num'");
										}
									}
									$i++;
								}
							}
						}
						# CHEQUEAR SI EXISTEN REGISTROS EN IMP_PAGADOS
						$sql = "SELECT cod_cat FROM imp_pagados WHERE cod_cat = '$cod_cat' AND fech_imp IS NOT NULL";
						$check = pg_num_rows(pg_query($sql));
						if ($check > 0) {
							pg_query("INSERT INTO imp_pagados SELECT '99-99-999', cod_pad, cod_pmc, no_inmu, gestion, forma_pago, ci_nit,
							       tp_inmu, titular, dom_ciu, dom_dir, zona, via_mat, val_tab, sup_terr, fact_agu, fact_alc, fact_luz, fact_tel,
							       fact_min, fact_incl, factor, valor_t, tp_viv, valcm2, sup_const, ant_const, fd_an, valor_vi, avaluo_total,
							       tp_exen, monto_exen, base_imp, imp_neto, fech_venc, cotido, cotiufv, d10, mant_val, interes, mul_mora, deb_for, 
							       san_adm, por_form, monto, descont, credito, sal_favor, cuota, exen_id, fech_imp, hora, usuario, control, no_orden
				             FROM imp_pagados WHERE cod_cat = '$cod_cat' AND fech_imp IS NOT NULL ORDER BY gestion");
							pg_query("UPDATE imp_pagados SET cod_cat = '$cod_cat_nuevo', forma_pago = 'COPIA' WHERE cod_cat = '99-99-999'");
						}
						########################################				 
						#----- CHEQUEAR SI EXISTEN FOTOS ------#
						########################################
						$filename1 = "C:/apache/htdocs/" . $folder . "/fotos/" . $cod_cat . ".jpg";
						$filename1_nuevo = "C:/apache/htdocs/" . $folder . "/fotos/" . $cod_cat_nuevo . ".jpg";
						$filename2 = "C:/apache/htdocs/" . $folder . "/fotos/" . $cod_cat . "-A.jpg";
						$filename2_nuevo = "C:/apache/htdocs/" . $folder . "/fotos/" . $cod_cat_nuevo . "-A.jpg";
						echo $filename1;
						if (file_exists($filename1)) {
							copy($filename1, $filename1_nuevo);
						}
						if (file_exists($filename2)) {
							copy($filename2, $filename2_nuevo);
						}
						#--- CHEQUEAR SI EXISTE UN TRANSFER ---#
						$sql = "SELECT cod_cat FROM transfer WHERE cod_cat = '$cod_cat'";
						$check = pg_num_rows(pg_query($sql));
						if ($check > 0) {
							pg_query("INSERT INTO transfer SELECT id, '99-99-999', adq_fech, adq_mont, cod_cat_ant, cod_pad_ant, tit_1pat_ant, tit_1mat_ant, tit_1nom1_ant, tit_1nom2_ant, tit_1ci_ant, tit_cara_ant, dom_dpto_ant, dom_ciu_ant, dom_dir_ant, der_num_ant, der_fech_ant, adq_modo_ant, adq_doc_ant, adq_fech_ant, tit_2pat_ant, tit_2mat_ant, tit_2nom1_ant, tit_2nom2_ant, tit_2ci_ant
				                       FROM transfer WHERE cod_cat = '$cod_cat'");
							pg_query("UPDATE transfer SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat = '99-99-999'");
						}
						########################################				 
						#--- CHEQUEAR SI EXISTE UN GRAVAMEN ---#
						########################################									
						$sql = "SELECT cod_cat FROM gravamen WHERE cod_cat = '$cod_cat'";
						$check = pg_num_rows(pg_query($sql));
						if ($check > 0) {
							pg_query("INSERT INTO gravamen SELECT '99-99-999', fecha, user_id, texto
				                       FROM gravamen WHERE cod_cat = '$cod_cat'");
							pg_query("UPDATE gravamen SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat = '99-99-999'");
						}
						########################################				 
						#---- CHEQUEAR SI EXISTEN CAMBIOS -----#
						########################################									
						$sql = "SELECT cod_cat FROM cambios WHERE cod_cat = '$cod_cat'";
						$check = pg_num_rows(pg_query($sql));
						if ($check > 0) {
							pg_query("INSERT INTO cambios SELECT id, '99-99-999', fecha_cambio, variable, valor_ant
				                       FROM cambios WHERE cod_cat = '$cod_cat'");
							pg_query("UPDATE cambios SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat = '99-99-999'");
						}
						# INSERTAR CAMBIOS
						pg_query("INSERT INTO cambios (cod_cat, fecha_cambio, variable, valor_ant) 
				                    VALUES ('$cod_cat_nuevo', '$fecha', 'cod_cat', '$cod_cat')");
					} # END_OF_ELSE ($cod_cat != $cod_cat_nuevo)
					$i = -1;
				} # END_OF_ELSE ($i == 1)
				$i++;
			}
		}

	} elseif ($action == "Fusionar") {
		$cod_cat = $_POST["cod_fusion"];
		if ($cod_cat == $_POST["col_cod"]) {
			$cod_cat_ant = $_POST["cod_cat_ant"];
		} else
			$cod_cat_ant = $_POST["col_cod"];
		########################################
		#-- LEER GEOM DEL PREDIO DE TEMP_POLY -#
		########################################
		$sql = "SELECT cod_cat, the_geom FROM temp_poly WHERE user_id = '$user_id' AND number = '2'";
		$result = pg_query($sql);
		$info = pg_fetch_array($result, null, PGSQL_ASSOC);
		#$cod_cat_nuevo = $info['cod_cat'];	
		$cod_cat_nuevo = $cod_cat;
		$the_geom_nuevo = $info['the_geom'];
		pg_free_result($result);
		########################################
		#------- DETERMINAR AREA ANTES  -------#
		########################################					 
		$sql = "SELECT area(the_geom) FROM predios WHERE cod_cat = '$cod_cat_nuevo'";
		$result = pg_query($sql);
		$value = pg_fetch_array($result, null, PGSQL_ASSOC);
		$area_ant = ROUND($value['area'], 2);
		pg_free_result($result);
		########################################
		#------------- CAMBIOS ----------------#
		########################################
		pg_query("INSERT INTO cambios (cod_cat, fecha_cambio, variable, valor_ant) VALUES ('$cod_cat_nuevo','$fecha','ter_smen','$area_ant')");
		pg_query("INSERT INTO cambios (cod_cat, fecha_cambio, variable, valor_ant) VALUES ('$cod_cat_nuevo','$fecha','cod_cat','$cod_cat_ant')");
		########################################
		#----- UPDATE REGISTRO EN PREDIOS -----#
		########################################								 
		$cod_uv = (int) substr($cod_cat_nuevo, 0, 2);
		$cod_man = (int) substr($cod_cat_nuevo, 3, 2);
		$cod_pred = (int) substr($cod_cat_nuevo, 6, 3);
		# pg_query("INSERT INTO predios (cod_cat, cod_uv, cod_man, cod_pred, activo, the_geom)
		#   VALUES ('$cod_cat_nuevo','$cod_uv','$cod_man','$cod_pred','1','$the_geom_nuevo')");

		echo $cod_uv . "   " . $cod_man . "    " . $cod_pred;
		pg_query("UPDATE predios SET the_geom = '$the_geom_nuevo' WHERE cod_cat = '$cod_cat_nuevo'");
		pg_query("UPDATE predios_ocha SET the_geom = '$the_geom_nuevo' WHERE cod_cat = '$cod_cat_nuevo'");
		pg_query("DELETE FROM predios_ocha_orig WHERE cod_cat = '$cod_cat'");
		pg_query("DELETE FROM ochaves_linea WHERE cod_cat = '$cod_cat'");
		########################################
		#-------- BORRAR COLINDANTES ----------#
		########################################
		pg_query("DELETE FROM colindantes WHERE cod_cat = '$cod_cat_nuevo'");
		$sql_col = "SELECT cod_cat FROM predios WHERE st_dwithin ((SELECT the_geom FROM predios WHERE cod_cat ='$cod_cat_nuevo'),the_geom,1) AND activo = '1'";
		$result_col = pg_query($sql_col);
		$i = $j = 0;
		while ($line_col = pg_fetch_array($result_col, null, PGSQL_ASSOC)) {
			foreach ($line_col as $col_value_col) {
				pg_query("DELETE FROM colindantes WHERE cod_cat = '$col_value_col'");
			}
		}
		pg_free_result($result_col);
		########################################
		#----- AÑADIR REGISTRO EN CODIGOS -----#
		########################################	
		#$sql="SELECT cod_cat FROM codigos WHERE cod_cat = '$cod_cat_nuevo'";
		#$check_codigos = pg_num_rows(pg_query($sql));	
		#if ($check_codigos == 0) { 											    
		#   pg_query("INSERT INTO codigos (cod_cat, activo) VALUES ('$cod_cat_nuevo','1')");
		#}					  
		########################################
		#---- DETERMINAR PREDIO MAS GRANDE ----#
		########################################					 
		/* $sql="SELECT area(the_geom) FROM predios WHERE cod_cat = '$cod_cat_ant'";
		 $result=pg_query($sql);
		 $value = pg_fetch_array($result, null, PGSQL_ASSOC);	 			           
		 $area1 = ROUND($value['area'],2); 
		   pg_free_result($result); 
		 $sql="SELECT area(the_geom) FROM predios WHERE cod_cat = '$col_cod'";
		 $result=pg_query($sql);
		 $value = pg_fetch_array($result, null, PGSQL_ASSOC);	 			           
		 $area2 = ROUND($value['area'],2); 
		   pg_free_result($result); 	 
		   if ($area2 > $area1) {
			  $predio_principal = $col_cod;
					$predio_segundario = $cod_cat_ant;
					$area_ant = $area2;
		   } else {
				  $predio_principal = $cod_cat_ant;
					$predio_segundario = $col_cod;
					$area_ant = $area1;
			   } */
		########################################
		#---- CREAR REGISTRO EN INFO_PREDIO ---#
		########################################		
#			$sql="SELECT cod_cat FROM info_predio WHERE cod_cat = '$cod_cat_nuevo'";
#      $check_info_predio = pg_num_rows(pg_query($sql));
#			if ($check_info_predio == 0) {
		/*   pg_query("INSERT INTO info_predio (cod_geo, cod_cat, cod_uv, cod_man, cod_pred) 
			   VALUES ('$cod_geo','$cod_cat_nuevo','$cod_uv','$cod_man','$cod_pred')");		
		 ########################################
	 #--- LLENAR REGISTRO EN INFO_PREDIO ---#
	 ########################################	
		   $sql="SELECT * FROM info_predio WHERE cod_cat = '$predio_principal'";
	 $result = pg_query($sql);
	 $info = pg_fetch_array($result, null, PGSQL_ASSOC);			 	
			 $cod_pad = "";			
	 $dir_tipo = $info['dir_tipo']; $dir_nom = $info['dir_nom']; $dir_num = $info['dir_num']; $dir_edif = $info['dir_edif'];
			 $dir_bloq = $info['dir_bloq']; $dir_piso = $info['dir_piso']; $dir_apto = $info['dir_apto'];
	   $tit_pers = $info['tit_pers']; $tit_cant = $info['tit_cant']; $tit_bene = $info['tit_bene'];$tit_cara = $info['tit_cara'];
			 $tit_1nom1 = utf8_decode($info['tit_1nom1']); $tit_1nom2 = utf8_decode($info['tit_1nom2']);
		   $tit_1pat = utf8_decode($info['tit_1pat']); $tit_1mat = utf8_decode($info['tit_1mat']);
			 $tit_1nom1 = utf8_decode($info['tit_1nom1']); $tit_1nom2 = utf8_decode($info['tit_1nom2']);
			 $tit_1ci = $info['tit_1ci']; $tit_1nit = $info['tit_1nit'];
		   $tit_2pat = utf8_decode($info['tit_2pat']); $tit_2mat = utf8_decode($info['tit_2mat']);
			 $tit_2nom1 = utf8_decode($info['tit_2nom1']); $tit_2nom2 = utf8_decode($info['tit_2nom2']);
			 $tit_2ci = $info['tit_2ci']; $tit_2nit = $info['tit_2nit'];
	   $dom_dpto = $info['dom_dpto']; $dom_ciu	= utf8_decode ($info['dom_ciu']); $dom_dir = utf8_decode ($info['dom_dir']);
	 $adq_modo = $info['adq_modo']; $adq_doc = $info['adq_doc']; $adq_fech = $fecha; $der_num = $info['der_num']; $der_fech = $fecha;	 
	 $via_tipo = $info['via_tipo']; $via_clas= $info['via_clas']; $via_uso= $info['via_uso']; $via_mat= $info['via_mat'];
	 $ter_topo = $info['ter_topo']; $ter_form = $info['ter_form']; $ter_ubi = $info['ter_ubi']; $ter_nofr = $info['ter_nofr'];
	 $ter_fond = $info['ter_fond']; $ter_fren = $info['ter_fren']; $ter_sdoc = ""; $ter_eesp = $info['ter_eesp'];				 
	 $ter_uso = $info['ter_uso']; $ter_mur = $info['ter_mur']; $ter_san = $info['ter_san'];
	   $res_enc = textconvert($info['res_enc']); $res_sup = textconvert($info['res_sup']);
			 $res_obs = $info['res_obs']; $res_fech = $info['res_fech'];	
	   pg_free_result($result);  */
		$sql = "SELECT * FROM info_predio WHERE cod_cat = '$cod_cat_ant'";
		$result1 = pg_query($sql);
		$info1 = pg_fetch_array($result1, null, PGSQL_ASSOC);
		$sql = "SELECT * FROM info_predio WHERE cod_cat = '$col_cod'";
		$result2 = pg_query($sql);
		$info2 = pg_fetch_array($result2, null, PGSQL_ASSOC);

		if (($info1['via_mat'] == "ASF") or ($info2['via_mat'] == "ASF")) {
			$via_mat = "ASF";
		} elseif (($info1['via_mat'] == "RIP") or ($info2['via_mat'] == "RIP")) {
			$via_mat = "RIP";
		} else
			$via_mat = "TRR";
		if (($info1['ser_alc'] == "SI") or ($info2['ser_alc'] == "SI")) {
			$ser_alc = "SI";
		} else
			$ser_alc = "NO";
		if (($info1['ser_agu'] == "SI") or ($info2['ser_agu'] == "SI")) {
			$ser_agu = "SI";
		} else
			$ser_agu = "NO";
		if (($info1['ser_luz'] == "SI") or ($info2['ser_luz'] == "SI")) {
			$ser_luz = "SI";
		} else
			$ser_luz = "NO";
		if (($info1['ser_tel'] == "SI") or ($info2['ser_tel'] == "SI")) {
			$ser_tel = "SI";
		} else
			$ser_tel = "NO";
		if (($info1['ser_gas'] == "SI") or ($info2['ser_gas'] == "SI")) {
			$ser_gas = "SI";
		} else
			$ser_gas = "NO";
		if (($info1['ser_alu'] == "SI") or ($info2['ser_alu'] == "SI")) {
			$ser_alu = "SI";
		} else
			$ser_alu = "NO";
		if (($info1['ser_cab'] == "SI") or ($info2['ser_cab'] == "SI")) {
			$ser_cab = "SI";
		} else
			$ser_cab = "NO";
		if (($info1['esp_aac'] == "SI") or ($info2['esp_aac'] == "SI")) {
			$esp_aac = "SI";
		} else
			$esp_aac = "NO";
		if (($info1['esp_tas'] == "SI") or ($info2['esp_tas'] == "SI")) {
			$esp_tas = "SI";
		} else
			$esp_tas = "NO";
		if (($info1['esp_tae'] == "SI") or ($info2['esp_tae'] == "SI")) {
			$esp_tae = "SI";
		} else
			$esp_tae = "NO";
		if (($info1['esp_ser'] == "SI") or ($info2['esp_ser'] == "SI")) {
			$esp_ser = "SI";
		} else
			$esp_ser = "NO";
		if (($info1['esp_gar'] == "SI") or ($info2['esp_gar'] == "SI")) {
			$esp_gar = "SI";
		} else
			$esp_gar = "NO";
		if (($info1['esp_dep'] == "SI") or ($info2['esp_dep'] == "SI")) {
			$esp_dep = "SI";
		} else
			$esp_dep = "NO";
		if (($info1['mej_lav'] == "SI") or ($info2['mej_lav'] == "SI")) {
			$mej_lav = "SI";
		} else
			$mej_lav = "NO";
		if (($info1['mej_par'] == "SI") or ($info2['mej_par'] == "SI")) {
			$mej_par = "SI";
		} else
			$mej_par = "NO";
		if (($info1['mej_hor'] == "SI") or ($info2['mej_hor'] == "SI")) {
			$mej_hor = "SI";
		} else
			$mej_hor = "NO";
		if (($info1['mej_pis'] == "SI") or ($info2['mej_pis'] == "SI")) {
			$mej_pis = "SI";
		} else
			$mej_pis = "NO";
		if (($info1['mej_otr'] == "SI") or ($info2['mej_otr'] == "SI")) {
			$mej_otr = "SI";
		} else
			$mej_otr = "NO";
		$res_obs1 = utf8_decode($info1['res_obs']);
		$res_obs2 = $info2['res_obs'];
		$res_obs = $res_obs1 . " Se añadió el predio $cod_cat_ant en fecha $fecha";
		pg_free_result($result1);
		pg_free_result($result2);
		$sql = "UPDATE info_predio SET via_mat = '$via_mat',
				          ser_alc	= '$ser_alc', ser_agu	= '$ser_agu',	ser_luz	= '$ser_luz',	ser_tel	= '$ser_tel',	
				          ser_gas	= '$ser_gas',	ser_alu	= '$ser_alu',	ser_cab	= '$ser_cab',
				          ter_fren = '', ter_fond = '',
				          esp_aac = '$esp_aac', esp_tas = '$esp_tas', esp_tae = '$esp_tae',
				          esp_ser = '$esp_ser', esp_gar = '$esp_gar', esp_dep = '$esp_dep', 
				          mej_lav = '$mej_lav', mej_par = '$mej_par', mej_hor = '$mej_hor', mej_pis = '$mej_pis', mej_otr = '$mej_otr',
				          res_obs = '$res_obs'		  
			            WHERE cod_cat = '$cod_cat_nuevo'";
		#echo $sql;
		pg_query($sql);
		########################################
		#----- CHEQUEAR POR EDIFICACIONES -----#
		########################################	
		$sql = "SELECT * FROM edificaciones WHERE cod_cat = '$cod_cat'";
		$check_edif1 = pg_num_rows(pg_query($sql));
		$sql = "SELECT edi_num, edi_piso FROM edificaciones WHERE cod_cat = '$cod_cat_ant' ORDER BY edi_num, edi_piso";
		$check_edif2 = pg_num_rows(pg_query($sql));
		if ($check_edif2 > 0) {
			$result = pg_query($sql);
			$i = 0;
			$proximo_numero = $check_edif1 + 1;
			while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
				foreach ($line as $col_value) {
					if ($i == 0) {
						$edi_num = $col_value;
					} else {
						$edi_piso = $col_value;
						pg_query("UPDATE edificaciones SET cod_cat = '$cod_cat', edi_num = '$proximo_numero' WHERE cod_cat = '$cod_cat_ant' AND edi_num = '$edi_num' AND edi_piso = '$edi_piso'");
						pg_query("UPDATE info_edif SET cod_cat = '$cod_cat', edi_num = '$proximo_numero' WHERE cod_cat = '$cod_cat_ant' AND edi_num = '$edi_num' AND edi_piso = '$edi_piso'");
						$proximo_numero++;
						$i = -1;
					}
					$i++;
				}
			}
			pg_free_result($result);
		}
		########################################
		#------ HACER ANT. PREDIO INACTIVO ----#
		########################################								
		pg_query("UPDATE predios SET activo = '0' WHERE cod_cat = '$cod_cat_ant'");
		pg_query("UPDATE predios_ocha SET activo = '0' WHERE cod_cat = '$cod_cat_ant'");
		pg_query("UPDATE codigos SET activo = '0' WHERE cod_cat = '$cod_cat_ant'");
		pg_query("DELETE FROM predios_ocha_orig WHERE cod_cat = '$cod_cat_ant'");
		pg_query("DELETE FROM ochaves_linea WHERE cod_cat = '$cod_cat_ant'");
		#			} # END_OF_IF ($check_info_predio == 0)

	} elseif ($action == "Ochaves") {
		#echo "Insertar ochave<br />";		 
		$sql = "SELECT the_geom, AsText(the_geom) FROM temp_poly WHERE user_id = '$user_id' AND cod_cat = '$cod_cat' AND number = '10'";
		$result = pg_query($sql);
		$info = pg_fetch_array($result, null, PGSQL_ASSOC);
		$the_geom_nuevo = $info['the_geom'];
		$the_geom_nuevo_astext = $info['astext'];
		pg_free_result($result);
		#echo "NUEVA GEOMETRIA: $the_geom_nuevo_astext<br />";				 
		pg_query("UPDATE predios_ocha SET the_geom = '$the_geom_nuevo' WHERE cod_cat = '$cod_cat'");



		##############################################################
		#---- ESCRIBIR LAS LINEAS DE TEMP_LINE EN OCHAVES_LINEA -----#
		##############################################################	

		pg_query("DELETE FROM ochaves_linea WHERE cod_cat = '$cod_cat'");
		pg_query("INSERT INTO ochaves_linea SELECT '0', nombre, descrip, tipo, the_geom FROM temp_line WHERE user_id = '$user_id' AND id = '0'");

		#############################################################
		#---- ESCRIBIR EL PREDIO ORIGINAL EN PREDIOS_OCHA_ORIG -----#
		#############################################################	

		pg_query("DELETE FROM predios_ocha_orig WHERE cod_cat = '$cod_cat'");
		pg_query("INSERT INTO predios_ocha_orig SELECT '$cod_cat', the_geom FROM predios WHERE cod_cat = '$cod_cat'");

		#pg_query("DELETE FROM temp_poly WHERE user_id = '$user_id'");
		#pg_query("DELETE FROM temp_line WHERE user_id = '$user_id'");
		#pg_query("DELETE FROM temp_point WHERE user_id = '$user_id'");	
	} elseif ($action == "Borrar Ochave") {
		#echo "Borrar ochave<br />";		 
		pg_query("DELETE FROM predios_ocha WHERE cod_cat = '$cod_cat'");
		pg_query("INSERT INTO predios_ocha SELECT cod_cat, cod_uv, cod_man, cod_pred, activo, the_geom FROM predios WHERE cod_cat = '$cod_cat'");
		pg_query("DELETE FROM ochaves_linea WHERE cod_cat = '$cod_cat'");
		pg_query("DELETE FROM predios_ocha_orig WHERE cod_cat = '$cod_cat'");
	} else {  # SOLO CAMBIO DE LA GEOMETRIA DEL PREDIO
		########################################
		#---- DETERMINAR AREA ANT Y NUEVO -----#
		########################################					 
		$sql = "SELECT area(the_geom) FROM predios WHERE cod_cat = '$cod_cat'";
		$result = pg_query($sql);
		$value = pg_fetch_array($result, null, PGSQL_ASSOC);
		$area_ant = ROUND($value['area'], 2);
		pg_free_result($result);
		$sql = "SELECT the_geom, area(the_geom) FROM temp_poly WHERE cod_cat = '$cod_cat' AND number = '2'";
		$result = pg_query($sql);
		$value = pg_fetch_array($result, null, PGSQL_ASSOC);
		$the_geom_nuevo = $value['the_geom'];
		$area_nuevo = ROUND($value['area'], 2);
		pg_free_result($result);
		if ($area_ant != $area_nuevo) {
			########################################
			#------------- CAMBIOS ----------------#
			########################################
			pg_query("INSERT INTO cambios (cod_cat, fecha_cambio, variable, valor_ant) VALUES ('$cod_cat','$fecha','ter_smen','$area_ant')");
		}
		########################################
		#-------- REEMPLAZAR GEOMETRIA --------#
		######################################## 	 
		pg_query("UPDATE predios SET the_geom = '$the_geom_nuevo' WHERE cod_cat = '$cod_cat'");
		pg_query("UPDATE predios_ocha SET the_geom = '$the_geom_nuevo' WHERE cod_cat = '$cod_cat'");
	} #END_OF_ELSE (SOLO CAMBIO DE LA GEOMETRIA DEL PREDIO)			
	######################################################
	#- CHEQUEAR SI SE HA AÑADIDO PUNTOS CON COLINDANTES -#
	######################################################	  
	if (isset($_POST["col_point_string"])) {

		$col_point_string = $_POST["col_point_string"];
		$col_cod_string = $_POST["col_cod_string"];
		#echo "$col_point_string $col_cod_string<br />";				 
		### LEER STRING 1 ###
		$i = $j = $m = 0;
		while ($i <= strlen($col_point_string)) {
			$char = substr($col_point_string, $i, 1);
			if ($char == ' ') {
				$col_point_x[$m] = substr($col_point_string, $j, $i - $j);
				$j = $i + 1;
			}
			if (($char == ',') or ($i == strlen($col_point_string))) {
				$col_point_y[$m] = substr($col_point_string, $j, $i - $j);
				$j = $i + 1;
				$m++;
			}
			$i++;
		} #end_of_while	
		### LEER STRING 2 ###
		$i = $j = $n = 0;
		while ($i <= strlen($col_cod_string)) {
			$char = substr($col_cod_string, $i, 1);
			if (($char == ',') or ($i == strlen($col_cod_string))) {
				$col_point_cod[$n] = trim(substr($col_cod_string, $j, $i - $j));
				$j = $i + 1;
				$n++;
			}
			$i++;
		} #end_of_while					 			 	
	} # END_OF_IF (isset)
#echo "Numero de puntos: $m, Numero de predios: $n<br />";
	$iii = 0;
	while ($iii < $m) {
		#echo "X: $col_point_x[$iii], Y: $col_point_y[$iii], Codigo: $col_point_cod[$iii]<br />";	
		########################################
		#-- CAMBIAR GEOMETRIA DEL COLINDANTE --#
		######################################## 
		### LEER LOS PUNTOS DEL PREDIO
		$sql = "SELECT AsText(the_geom),npoints(the_geom) FROM predios WHERE cod_cat ='$col_point_cod[$iii]'";
		#echo "$sql<br />";	
		$result_col = pg_query($sql);
		$info_col = pg_fetch_array($result_col, null, PGSQL_ASSOC);
		$coord_poly = $info_col['astext'];
		$no_de_vertices = $info_col['npoints'] - 1;
		pg_free_result($result_col);
		#echo $coord_poly;
		include "siicat_extract_coordpoly.php";
		### AVERIGUAR ENTRE CUALES DE LOS PUNTOS ESTA EL NUEVO PUNTO
#echo "Numero de vertices para colindante $iii: $no_de_vertices<br />";				 
		$j = 0;
		while ($j < $no_de_vertices) {
			$line_x1 = $point_x[$j];
			$line_y1 = $point_y[$j];
			$k = $j + 1;
			if ($k == $no_de_vertices) {
				$line_x2 = $point_x[0];
				$line_y2 = $point_y[0];
			} else {
				$line_x2 = $point_x[$k];
				$line_y2 = $point_y[$k];
			}
			$temp_point_x = $col_point_x[$iii];
			$temp_point_y = $col_point_y[$iii];
			$sql = "SELECT ST_Distance(ST_GeomFromText('POINT($temp_point_x $temp_point_y)'), ST_GeomFromText('LINESTRING($line_x1 $line_y1,$line_x2 $line_y2)'))AS dist";
			#echo "$sql<br />";	
			$result_dist = pg_query($sql);
			$res_dist = pg_fetch_array($result_dist, null, PGSQL_ASSOC);
			$distancia_line1 = $res_dist['dist'];
			#echo "Distancia del punto hasta la linea numero $k: $distancia_line1<br />";						 									 
			pg_free_result($result_dist);
			if ($distancia_line1 < 0.1) {
				$pos_col_point = $j;
				#echo "Posicion del punto: $pos_col_point<br />";							
			}
			$j++;
		}
		### CREAR NUEVO COORD_STRING ###			 
		$j = 0;
		$k = 1;
		$coords = "$point_x[0] $point_y[0]";
		while ($j < $no_de_vertices) {
			if ($j == 0) {
				if ($j == $pos_col_point) {
					$coords = "$coords, $temp_point_x $temp_point_y";
				}
			} else {
				if ($j == $pos_col_point) {
					$coords = "$coords, $point_x[$j] $point_y[$j], $temp_point_x $temp_point_y";
				} else {
					$coords = "$coords, $point_x[$j] $point_y[$j]";
				}
			}
			$j++;
		}
		$coords = "$coords, $point_x[0] $point_y[0]";
		#echo "NUEVO COORD-STRING ES: $coords<br />\n";
		### REEMPLAZAR COORD_STRING ###	
		pg_query("UPDATE predios SET the_geom ='SRID=-1;MULTIPOLYGON((($coords)))' WHERE cod_cat = '$col_point_cod[$iii]'");
		pg_query("UPDATE predios_ocha SET the_geom ='SRID=-1;MULTIPOLYGON((($coords)))' WHERE cod_cat = '$col_point_cod[$iii]'");
		pg_query("DELETE FROM predios_ocha_orig WHERE cod_cat = '$col_point_cod[$iii]'");
		pg_query("DELETE FROM ochaves_linea WHERE cod_cat = '$col_point_cod[$iii]'");
		$iii++;

	}
	pg_query("DELETE FROM temp_poly WHERE user_id = '$user_id'");
}


################################################################################
#------------------------- RECTIFICAR TRANSFERENCIA ---------------------------#
################################################################################	
if ((isset($_POST["rectificar_transfer"])) AND ($_POST["rectificar_transfer"] == "SI")) {

	include "siicat_transfer_rectificar.php";

}
################################################################################
#------------------------------ RECUPERAR PREDIO ------------------------------#
################################################################################	
if ((isset($_POST["recuperar"])) and ($_POST["recuperar"] == "OK")) {
	$cod_cat = $_POST["cod_cat"];
	$no_de_herederos = $_POST["no_de_herederos"];
	while ($i < $no_de_herederos) {
		$cod_cat_actual = $_POST["cod_cat_heredero$i"];
		$sql = "SELECT cod_cat FROM cambios WHERE variable = 'cod_cat' AND  valor_ant = '$cod_cat_actual' ORDER BY cod_cat DESC";
		$check_cambios = pg_num_rows(pg_query($sql));
		if ($check_cambios > 0) {
			$error = true;
			$mensaje_de_error = "Error: Se ha dividido/renombrado el predio $cod_cat_actual. Tiene que recuperar primero ese predio!";
		}
		$sql = "SELECT cod_cat FROM imp_pagados WHERE cod_cat = '$cod_cat_actual' AND forma_pago != ''";
		$check_cambios = pg_num_rows(pg_query($sql));
		if ($check_cambios > 0) {
			$error = true;
			$mensaje_de_error = "Error: No se puede borrar el predio $cod_cat_actual, porque ya tiene $check_cambios pago(s) de impuestos registrados. Tiene que borrar primero estos pagos!";
		}
		$i++;
	}
	if (!$error) {
		########################################
		#---- RESTABLECER PREDIO ORIGINAL -----#
		########################################	
		pg_query("UPDATE codigos SET activo = '1' WHERE cod_cat = '$cod_cat'");
		pg_query("UPDATE predios SET activo = '1' WHERE cod_cat = '$cod_cat'");
		########################################
		#-- BORRAR DATOS DE PREDIOS ACTUALES --#
		########################################	
		$i = 0;
		while ($i < $no_de_herederos) {
			$cod_cat_actual = $_POST["cod_cat_heredero$i"];
			#echo "$cod_cat_actual";
			pg_query("DELETE FROM predios WHERE cod_cat = '$cod_cat_actual'");
			pg_query("DELETE FROM codigos WHERE cod_cat = '$cod_cat_actual'");
			pg_query("DELETE FROM info_predio WHERE cod_cat = '$cod_cat_actual'");
			pg_query("DELETE FROM edificaciones WHERE cod_cat = '$cod_cat_actual'");
			pg_query("DELETE FROM info_edif WHERE cod_cat = '$cod_cat_actual'");
			$filename1 = "C:/apache/htdocs/buenavista/fotos/" . $cod_cat_actual . ".jpg";
			$filename2 = "C:/apache/htdocs/buenavista/fotos/" . $cod_cat_actual . "-A.jpg";
			if (file_exists($filename1)) {
				unlink($filename1);
			}
			if (file_exists($filename2)) {
				unlink($filename2);
			}
			pg_query("DELETE FROM gravamen WHERE cod_cat = '$cod_cat_actual'");
			pg_query("DELETE FROM imp_pagados WHERE cod_cat = '$cod_cat_actual'");
			pg_query("DELETE FROM imp_plan_de_pago WHERE cod_cat = '$cod_cat_actual'");
			pg_query("DELETE FROM cambios WHERE cod_cat = '$cod_cat_actual'");
			$i++;
		}
		########################################
		#-------------- REGISTRO --------------#
		########################################
		$accion = "Predio recuperado";
		$username = get_username($session_id);
		pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");
	}
}
################################################################################
#---------------------------- BUSQUEDA TRANSMITIDA ----------------------------#
################################################################################	 
if (((isset($_POST["Submit"])) and (($_POST["Submit"] == "Ver") or ($_POST["Submit"] == "Volver"))) or (isset($_GET["inmu"]))) {
	$mostrar = true;
	# $cod_cat = $_POST["cod_cat"];
	# $cod_uv = get_uv ($cod_cat); $cod_man = get_man($cod_cat);  $cod_pred = get_lote ($cod_cat); $cod_subl = get_subl ($cod_cat);  
}
################################################################################
#--------------------------- BUSQUEDA 1 TRANSMITIDA ---------------------------#
################################################################################	
if ((isset($_POST["busqueda1"])) and ($_POST["busqueda1"] == "Buscar")) {
	$mostrar = true;
	$cod_uv = $_POST["cod_uv"];
	$cod_man = $_POST["cod_man"];
	$cod_pred = $_POST["cod_pred"];
	$cod_blq = $_POST["cod_blq"];
	$cod_piso = $_POST["cod_piso"];
	$cod_apto = $_POST["cod_apto"];
	$cod_cat = get_codcat($cod_uv, $cod_man, $cod_pred, $cod_blq, $cod_piso, $cod_apto);
}
################################################################################
#                         CHEQUEAR SI EXISTE INFO_EDIF                         #
################################################################################	
$sql = "SELECT cod_uv FROM info_edif WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check_info_edif = pg_num_rows(pg_query($sql));
if (($check_info_edif == 0) and (($nivel == 2) or ($nivel == 5))) {
	$accion_info_edif = "Añadir";
} else
	$accion_info_edif = "Ver";
################################################################################
#                        CHEQUEAR SI EXISTE GEO_PREDIO                         #
################################################################################	
$accion_geo_predio = "Ver";
$sql = "SELECT cod_uv FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check_geo_predio = pg_num_rows(pg_query($sql));
if ($check_geo_predio == 0) {
	$geometria_existe = false;
	if (($nivel == 2) or ($nivel == 5)) {
		$accion_geo_predio = "Añadir";
	}
}
################################################################################
#------------- CHEQUEAR SI EXISTEN VARIOS INMUEBLES EN EL PREDIO --------------#
################################################################################	
$sql = "SELECT id_inmu FROM info_inmu WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check_id_inmu = pg_num_rows(pg_query($sql));
################################################################################
#-------------------------- LEER DATOS DE INFO_INMU ---------------------------#
################################################################################	
# NECESITA ID_INMU
include "siicat_info_inmu_leer_datos.php";
if ($check_info_inmu == 0) {
	#echo "SIN DATOS EN INFO_INMU<br />";
}
################################################################################
#------------------------- LEER DATOS DE INFO_PREDIO --------------------------#
################################################################################	
# NECESITA COD_GEO, COD_UV, COD_MAN, COD_PRED
include "siicat_info_predio_leer_datos.php";
if ($check_info_predio == 0) {
	#echo "SIN DATOS EN INFO_PREDIO<br />";
}
################################################################################
#-------------------------- DEFINIR ZONA HOMOGENEA ----------------------------#
################################################################################	
# NECESITA COD_GEO, COD_UV, COD_MAN, COD_PRED
$ben_zona = get_zona($id_inmu);
if ($ben_zona == "0") {
	$ben_zona = "NO DEF.";
}
################################################################################
#-------------------------- DEFINIR MATERIAL DE VIA ---------------------------#
################################################################################	
# NECESITA COD_GEO, COD_UV, COD_MAN, COD_PRED
$via_mat = edg_material_de_via($id_inmu);
if ($via_mat == "") {
	$via_mat_texto = "NO DEF.";
} else
	$via_mat_texto = utf8_decode(abr($via_mat));
################################################################################
#-------------  PREPARAR Y GENERAR BARRA DE NAVEGACION E I-FRAME --------------#
################################################################################
#echo "COD_CAT: $cod_uv - $cod_man - $cod_pred, CHECK_INFO: $check_info_predio<br />"; 
if ($check_info_predio > 0) {
	$resultado = true;
	include "siicat_lista_datos.php";
}
################################################################################
#------------------ CHEQUEAR SI EL PREDIO ESTA ACTIVO -------------------------#
################################################################################	
$sql = "SELECT activo FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result_act = pg_query($sql);
$act = pg_fetch_array($result_act, null, PGSQL_ASSOC);
$activo = $act['activo'];

$activo = 1;

pg_free_result($result_act);
########################################
#- SELECCIONAR PREDIOS PARA HISTORIAL -#
########################################	
if (($activo == 0) and ($resultado)) {
	#   $sql="SELECT activo, cod_cat, area(the_geom) FROM predios WHERE hist_cod1 = '$cod_cat' OR hist_cod2 = '$cod_cat' ORDER BY activo DESC";
	$sql = "SELECT fecha_cambio, cod_uv, cod_man, cod_pred, cod_subl FROM cambios WHERE variable = 'cod_cat' AND  valor_ant = '$cod_cat' ORDER BY cod_uv,cod_man,cod_pred,cod_subl DESC";
	$check_cambios = pg_num_rows(pg_query($sql));
	$i = $j = $k = $m = 0;
	if ($check_cambios > 0) {
		$result_hist0 = pg_query($sql);
		while ($line = pg_fetch_array($result_hist0, null, PGSQL_ASSOC)) {
			foreach ($line as $col_value) {
				if ($i == 0) {
					$fecha_cambio_heredero[$k] = $col_value;
					$fecha_cambio_heredero[$k] = change_date($fecha_cambio_heredero[$k]);
				} elseif ($i == 1) {
					$cod_uv_cam = $col_value;
				} elseif ($i == 2) {
					$cod_man_cam = $col_value;
				} elseif ($i == 3) {
					$cod_pred_cam = $col_value;
				} elseif ($i == 4) {
					$cod_subl_cam = $col_value;
					$cod_cat_heredero[$k] = get_codcat($cod_uv_cam, $cod_man_cam, $cod_pred_cam, $cod_subl_cam);
					#echo "COL_VALUE: $col_value,$cod_cat_heredero[$k]<br>";							
					### PROPIETARIO ACTUAL ###
					$sql = "SELECT tit_1pat, tit_1mat, tit_1nom1, tit_1nom2 FROM info_predio WHERE cod_uv = '$cod_uv_cam' AND cod_man = '$cod_man_cam' AND cod_pred = '$cod_pred_cam' AND cod_subl = '$cod_subl_cam'";
					$result2 = pg_query($sql);
					$tit = pg_fetch_array($result2, null, PGSQL_ASSOC);
					$tit_1pat = utf8_decode($tit['tit_1pat']);
					$tit_1mat = utf8_decode($tit['tit_1mat']);
					$tit_1nom1 = utf8_decode($tit['tit_1nom1']);
					$tit_1nom2 = utf8_decode($tit['tit_1nom2']);
					$titular1_heredero[$k] = $tit_1nom1 . " " . $tit_1nom2 . " " . $tit_1pat . " " . $tit_1mat;
					pg_free_result($result2);
					### SUPERFICIE ACTUAL ###
					$sql = "SELECT area(the_geom) FROM predios WHERE cod_uv = '$cod_uv_cam' AND cod_man = '$cod_man_cam' AND cod_pred = '$cod_pred_cam' AND cod_subl = '$cod_subl_cam'";
					$result3 = pg_query($sql);
					$info3 = pg_fetch_array($result3, null, PGSQL_ASSOC);
					$area_heredero[$k] = ROUND($info3['area'], 2);
					#echo "AREA:$area_heredero[$k],$col_value<br>";						
					pg_free_result($result3);
					### ACTIVADO ###
					$sql = "SELECT activo FROM codigos WHERE cod_uv = '$cod_uv_cam' AND cod_man = '$cod_man_cam' AND cod_pred = '$cod_pred_cam' AND cod_subl = '$cod_subl_cam'";
					$result4 = pg_query($sql);
					$info4 = pg_fetch_array($result4, null, PGSQL_ASSOC);
					$temp_activo = $info4['activo'];
					pg_free_result($result4);
					if ($temp_activo == 0) {
						$activo_heredero[$k] = "NO";
					} else
						$activo_heredero[$k] = "SI";
					#echo "AREA:$activo_heredero[$k],$col_value<br>";						
					$i = -1;
					$k++;
				}
				$i++;
			}
		}
		pg_free_result($result_hist0);
	} else {   # NO HAY ENTRADAS EN CAMBIOS
		$fecha_cambio_heredero[$k] = $cod_cat_heredero[$k] = $titular1_heredero[$k] = $area_heredero[$k] = $activo_heredero[$k] = "---";
		$k++;
	}
	$no_de_intermed = $j;
	$no_de_herederos = $k;
	#echo "INTERMED: $no_de_intermed, HEREDERO: $no_de_herederos<br>";
	# $activo = 0;
}
################################################################################
#------------------------------ CHEQUEAR GRAVAMEN -----------------------------#
################################################################################	
$sql = "SELECT texto FROM gravamen WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
$check_gravamen = pg_num_rows(pg_query($sql));
if ($check_gravamen == 0) {
	$gravamen = false;
} else {
	$gravamen = true;
}
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################		
echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";
echo "<tr height=\"40px\">\n";
echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=15&id=$session_id\" accept-charset=\"utf-8\">\n";
echo "<td align=\"center\" valign=\"bottom\" width=\"15%\">\n";
if ($resultado) {
	if ($gravamen) {
		echo "<input type=\"image\" src=\"graphics/boton_gravamen.gif\" width=\"100\" height=\"30\" border=\"0\" name=\"gravamen\" value=\"Gravamen\">\n";
	} else {
		echo "<input type=\"image\" src=\"graphics/boton_gravamen.png\" width=\"100\" height=\"30\" border=\"0\" name=\"gravamen\" value=\"Gravamen\">\n";
	}
}
echo "<input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";
echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";
echo "</td>\n";
echo "</form>\n";
echo "<td align=\"center\" valign=\"center\" width=\"60%\" class=\"pageName\">\n";
echo "Datos del Predio\n";
if (($activo == 0) and ($resultado)) {
	echo "<font color=\"red\"> - Archivo</font>\n";
}
echo "</td>\n";
echo "<td width=\"25%\"> &nbsp</td>\n";
echo "</tr>\n";
if ($resultado) {
	$mod_lista = 5;
	include "lista_formulario.php";
	if ($activo == 1) {
		echo "<tr>\n";
		echo "<td valign=\"top\" height=\"180\" colspan=\"3\" class=\"bodyText\">\n";
		echo "<div id=\"tabs\">\n";
		echo "<ul>\n";
		echo "<li><a href=\"#tab-1\"><span>Datos</span></a></li>\n";
		echo "<li><a href=\"#tab-2\"><span>Edificaciones</span></a></li>\n";
		echo "<li><a href=\"#tab-3\"><span>Fotos</span></a></li>\n";
		echo "<li><a href=\"#tab-4\"><span>Informes</span></a></li>\n";
		echo "<li><a href=\"#tab-5\"><span>Planos</span></a></li>\n";
		echo "<li><a href=\"#tab-6\"><span>Modificar</span></a></li>\n";
		echo "<li><a href=\"#tab-7\"><span>Documentos</span></a></li>\n";
		echo "<li><a href=\"#tab-8\"><span>Cambios</span></a></li>\n";
		echo "<li><a href=\"#tab-9\"><span>Transfer</span></a></li>\n";
		echo "<li><a href=\"#tab-10\"><span>Impuestos</span></a></li>\n";
		echo "</ul>\n";
		echo "<div id=\"tab-1\">\n";
		include "ver_datos.php";
		echo "</div>\n";
		echo "<div id=\"tab-2\">\n";
		include "ver_edificacion.php";
		echo "</div>\n";
		echo "<div id=\"tab-3\">\n";
		include "igm_ver_fotos.php";
		echo "</div>\n";
		echo "<div id=\"tab-4\">\n";
		include "informes.php";
		echo "</div>\n";
		echo "<div id=\"tab-5\">\n";
		include "planos.php";
		echo "</div>\n";
		echo "<div id=\"tab-6\">\n";
		include "siicat_modificar.php";
		echo "</div>\n";

		echo "<div id=\"tab-7\">\n";
			// Incluir directamente la gestión de documentos
			$_POST['id_inmu'] = $id_inmu;
			$_POST['folder'] = $folder;
			include "docs_gestion_tab.php";
		echo "</div>\n";

		echo "<div id=\"tab-8\">\n";
		include "cambios.php";
		echo "</div>\n";
		echo "<div id=\"tab-9\">\n";
		include "igm_transfer_botones.php";
		echo "</div>\n";
		echo "<div id=\"tab-10\">\n";
		include "impuestos.php";
		echo "</div>\n";

		echo "</div>\n";

		echo "</td>\n";
		echo "</tr>\n";

	} else {
		echo "<tr>\n";
		echo "<td valign=\"top\" height=\"180\" colspan=\"3\" class=\"bodyText\">\n";
		echo "<fieldset><legend>Historial del Predio</legend>\n";
		echo "<table border=\"0\" width=\"100%\">\n";   # 8 
		echo "<tr>\n";
		echo "<td align=\"center\" colspan=\"12\">\n";
		echo "<font color=\"red\">El Predio ya no está activado en la base de datos por causa de división de predio o asignación de un nuevo código!<br /><br /></font>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td width=\"4%\"> &nbsp</td>\n";
		echo "<td width=\"18%\" class=\"bodyTextD_Small\"> Predio en el archivo:</td>\n";
		echo "<td align=\"center\" width=\"14%\" class=\"bodyTextH\">Fecha Adquisición</td>\n";
		echo "<td width=\"1%\"> &nbsp</td>\n";
		echo "<td align=\"center\" width=\"11%\" class=\"bodyTextH\">Código</td>\n";
		echo "<td width=\"1%\"> &nbsp</td>\n";
		echo "<td align=\"center\" width=\"25%\" class=\"bodyTextH\">Propietario</td>\n";
		echo "<td width=\"1%\"> &nbsp</td>\n";
		echo "<td align=\"center\" width=\"14%\" class=\"bodyTextH\">Superficie</td>\n";
		echo "<td width=\"1%\"> &nbsp</td>\n";
		echo "<td align=\"center\" width=\"6%\" class=\"bodyTextH\">Activo</td>\n";
		echo "<td width=\"4%\"> &nbsp</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td> &nbsp</td>\n";
		echo "<td> &nbsp</td>\n";
		echo "<td align=\"center\" class=\"bodyTextD_Small\">$adq_fech</td>\n";
		echo "<td> &nbsp</td>\n";
		echo "<td align=\"center\" class=\"bodyTextD_Small\">$cod_cat</td>\n";
		echo "<td> &nbsp</td>\n";
		echo "<td align=\"center\" class=\"bodyTextD_Small\">$titular1</td>\n";
		echo "<td> &nbsp</td>\n";
		echo "<td align=\"center\" class=\"bodyTextD_Small\">$ter_smen m²</td>\n";
		echo "<td> &nbsp</td>\n";
		echo "<td align=\"center\" class=\"bodyTextD_Small\">NO</td>\n";
		echo "<td> &nbsp</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align=\"center\" colspan=\"12\">\n";
		echo "&nbsp\n";
		echo "</td>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		echo "<td> &nbsp</td>\n";
		echo "<td class=\"bodyTextD_Small\"> Predio(s) actual(es):</td>\n";
		echo "<td align=\"center\" class=\"bodyTextH\">Fecha Cambio</td>\n";
		echo "<td> &nbsp</td>\n";
		echo "<td align=\"center\" class=\"bodyTextH\">Código</td>\n";
		echo "<td> &nbsp</td>\n";
		echo "<td align=\"center\" class=\"bodyTextH\">Propietario</td>\n";
		echo "<td> &nbsp</td>\n";
		echo "<td align=\"center\" class=\"bodyTextH\">Superficie</td>\n";
		echo "<td> &nbsp</td>\n";
		echo "<td align=\"center\" class=\"bodyTextH\">Activo</td>\n";
		echo "<td> &nbsp</td>\n";
		echo "</tr>\n";
		$i = 0;
		while ($i < $no_de_herederos) {
			echo "<tr>\n";
			echo "<td> &nbsp</td>\n";
			echo "<td> &nbsp</td>\n";
			echo "<td align=\"center\" class=\"bodyTextD_Small\">$fecha_cambio_heredero[$i]</td>\n";
			echo "<td> &nbsp</td>\n";
			echo "<td align=\"center\" class=\"bodyTextD_Small\">$cod_cat_heredero[$i]</td>\n";
			echo "<td> &nbsp</td>\n";
			echo "<td align=\"center\" class=\"bodyTextD_Small\">$titular1_heredero[$i]</td>\n";
			echo "<td> &nbsp</td>\n";
			echo "<td align=\"center\" class=\"bodyTextD_Small\">$area_heredero[$i] m²</td>\n";
			echo "<td> &nbsp</td>\n";
			echo "<td align=\"center\" class=\"bodyTextD_Small\">$activo_heredero[$i]</td>\n";
			echo "<td> &nbsp</td>\n";
			echo "</tr>\n";
			$i++;
		}
		echo "<tr>\n";
		echo "<td align=\"center\" colspan=\"12\">\n";
		echo "&nbsp\n";
		echo "</td>\n";
		echo "</tr>\n";

		echo "</table>\n";
		echo "<table border=\"0\" width=\"100%\">\n";
		echo "<tr>\n";
		echo "<td width=\"5%\"></td>\n";   #Col. 1		 
		if ($nivel > 1) {
			echo "<td align=\"center\" width=\"15%\" class=\"bodyText\">\n";
		} else {
			echo "<td align=\"center\" width=\"22%\" class=\"bodyText\">\n";
		}
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=20&id=$session_id\" accept-charset=\"utf-8\">\n";
		echo "<input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";
		echo "<input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";
		echo "<input name=\"nada\" type=\"submit\" class=\"smallText\" value=\"Edificaciones\">\n";
		echo "</form>\n";
		echo "</td>\n";
		if ($nivel > 1) {
			echo "<td align=\"center\" width=\"15%\" class=\"bodyText\">\n";
		} else {
			echo "<td align=\"center\" width=\"23%\" class=\"bodyText\">\n";
		}
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=10&id=$session_id\" accept-charset=\"utf-8\">\n";
		echo "<input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";
		echo "<input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";
		echo "<input name=\"volver\" type=\"submit\" class=\"smallText\" value=\"Ver Geometría\">\n";
		echo "</form>\n";
		echo "</td>\n";
		if ($nivel > 1) {
			echo "<td align=\"center\" width=\"15%\" class=\"bodyText\">\n";
		} else {
			echo "<td align=\"center\" width=\"23%\" class=\"bodyText\">\n";
		}
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=7&id=$session_id\" accept-charset=\"utf-8\">\n";
		echo "<input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";
		echo "<input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";
		echo "<input name=\"volver\" type=\"submit\" class=\"smallText\" value=\" Ver Fotos\">\n";
		echo "</form>\n";
		echo "</td>\n";
		if ($nivel > 1) {
			echo "<td align=\"center\" width=\"15%\" class=\"bodyText\">\n";
		} else {
			echo "<td align=\"center\" width=\"22%\" class=\"bodyText\">\n";
		}
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=55&id=$session_id\" accept-charset=\"utf-8\">\n";
		echo "<input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";
		echo "<input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";
		echo "<input name=\"volver\" type=\"submit\" class=\"smallText\" value=\"Cambios\">\n";
		echo "</form>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</fieldset>\n";
		echo "</td>\n";
		echo "</tr>\n";
		if ($nivel > 1) {
			echo "<tr>\n";
			echo "<td valign=\"top\" height=\"180\" colspan=\"3\" class=\"bodyText\">\n";
			echo "<table border=\"0\" width=\"100%\">\n";   # 8 
			if ((isset($_POST["recuperar"])) and ($_POST["recuperar"] == "Recuperar Predio")) {
				echo "<tr>\n";
				echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id\" accept-charset=\"utf-8\">\n";
				echo "<td align=\"center\">\n";
				echo "<font color=\"red\"> Realmente quiere recuperar el Predio Original? Todos los Predios Actuales se perderán! </font>\n";
				echo "<input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";
				echo "<input name=\"no_de_intermed\" type=\"hidden\" value=\"$no_de_intermed\">\n";
				echo "<input name=\"no_de_herederos\" type=\"hidden\" value=\"$no_de_herederos\">\n";
				$i = 0;
				while ($i < $no_de_herederos) {
					echo "                     <input name=\"cod_cat_heredero$i\" type=\"hidden\" value=\"$cod_cat_heredero[$i]\">\n";
					$i++;
				}
				echo "                     <input name=\"recuperar\" type=\"submit\" class=\"smallText\" value=\"OK\">&nbsp&nbsp&nbsp\n";
				echo "                     <input name=\"recuperar\" type=\"submit\" class=\"smallText\" value=\"No recuperar\">\n";
				echo "                  </td>\n";
				echo "               </form>\n";
				echo "               </tr>\n";
			} elseif (!$error) {
				echo "               <tr>\n";
				echo "			          <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=70&id=$session_id\" accept-charset=\"utf-8\">\n";
				echo "                  <td align=\"left\" width=\"50%\">\n";
				echo "                     <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";
				echo "                     <input name=\"recuperar\" type=\"submit\" class=\"smallText\" value=\"Borrar Predio\">\n";
				echo "                  </td>\n";
				echo "               </form>\n";
				echo "               </tr>\n";
			} else {
				echo "<tr class=\"warning danger\">\n";
				echo "<td align=\"center\" height=\"20\">\n";   #Col. 1+2+3  	 			 
				echo "<font color=\"red\">$mensaje_de_error</font> <br />\n";
				echo "</td>\n";
				echo "</tr>\n";
			}
			echo "            </table>\n";
			echo "         </td>\n";
			echo "      </tr>\n";
		}
	}
} else { # IF (!$resultado) {
	echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=3&id=$session_id&iframe\" accept-charset=\"utf-8\">\n";
	echo "      <tr>\n";
	echo "         <td align=\"center\" colspan=\"3\">\n"; #Col. 1+2+3
	echo "            <br /><font color=\"red\"> Error: No se encuentran ningunos datos con el código $cod_cat en la base de datos. <br />Posiblemente el $predio ha sido borrado, re-codificado o unido con otro $predio!</font>\n";
	echo "         </td>\n";
	echo "      </tr>\n";
	echo "      <tr>\n";
	echo "      </tr>\n";
	echo "      </form>\n";
}
# Ultima Fila
echo "      <tr height=\"100%\"></tr>\n";
echo "   </table>\n";
echo "   <br />&nbsp;<br />\n";

?>