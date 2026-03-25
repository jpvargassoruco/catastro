<?php
$area_predio_manual = $area_edif_manual = $deuda2002 = false;
################################################################################
#-------------------------- SELECCION DE IMPUESTO -----------------------------#
################################################################################	
$gestion = $ano_actual - 1;
$calcular_urbano = $calcular_rural = $calcular_patente = $calcular_transfer_urbano = $calcular_transfer_rural = false;
if ((isset($_POST["id_inmu"])) or (isset($_GET["inmu"])) or (isset($_POST["asignar_valor_inmueble"]))) {
	if (((isset($_GET["mod"])) and ($_GET["mod"] == 69)) or (isset($_POST["modo_trans"]))) {
		$calcular_transfer_urbano = true;
	} else
		$calcular_urbano = true;
} elseif ((isset($_POST["id_predio_rural"])) or (isset($_GET["idpr"]))) {
	if (((isset($_GET["mod"])) and ($_GET["mod"] == 69)) or (isset($_POST["modo_trans"]))) {
		$calcular_transfer_rural = true;
	} else
		$calcular_rural = true;
} elseif ((isset($_POST["id_patente"])) or (isset($_GET["id_pat"]))) {
	$calcular_patente = true;
}
################################################################################
#----------------------- BOLETA INMUEBLE URBANO/RURAL -------------------------#
################################################################################	
if ($calcular_urbano) {
	$ancho_primera_fila = 32;
	$tabla_imp_pagados = "imp_pagados";
	$where_option = "cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
} elseif ($calcular_transfer_urbano) {
	$ancho_primera_fila = 32;
	$tabla_imp_pagados = "imp_transfer";
	$tabla_para_val_lib = "imp_pagados";
	$where_option = "cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
	$concepto_temp = "TRANSFER URBANO";
	$id_item_temp = $id_inmu;
}

if ($calcular_patente) {
	$sql = "SELECT exen_id FROM $tabla_imp_pagados WHERE gestion = '$gestion' AND $where_option";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$exen_select = $info['exen_id'];
	pg_free_result($result);
	$valor_en_libros_texto = $valor_lib = 0;
} elseif ((!$calcular_transfer_urbano) and (!$calcular_transfer_rural)) {
	if (!isset($_POST["imp_neto"])) {
		$sql = "SELECT exen_id, valor_lib FROM $tabla_imp_pagados WHERE gestion = '$gestion' AND $where_option";
		$result = pg_query($sql);
		$info = pg_fetch_array($result, null, PGSQL_ASSOC);
		$exen_select = $info['exen_id'];
		$valor_lib = $info['valor_lib'];
		pg_free_result($result);

	}
} else {
	
	$sql = "SELECT valor_lib FROM $tabla_para_val_lib WHERE $where_option AND gestion = '$gestion' AND (forma_pago = 'CONTADO' OR forma_pago = 'VALIDADO' OR forma_pago = 'PRESCRIP')";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$valor_lib = trim($info['valor_lib']);
	pg_free_result($result);
	if (($valor_lib == 0) or ($valor_lib == "")) {
		$valor_en_libros_texto = "-";
		$valor_lib = 0;		
	} else
		$valor_en_libros_texto = $valor_lib;
}

################################################################################
#-------------------------------- EXENCIONES ----------------------------------#
################################################################################	
if ($exen_select > 0) {
	$sql = "SELECT * FROM imp_exenciones WHERE numero = '$exen_select'";
	$result = pg_query($sql);
	$info_exen = pg_fetch_array($result, null, PGSQL_ASSOC);
	$ley = utf8_decode($info_exen['ley']);
	$fecha_exen = change_date($info_exen['fecha']);
	$descripcion = utf8_decode($info_exen['descripcion']);
	$porc_imp = $info_exen['porc_imp'];
	$porc_mul = $info_exen['porc_mul'];
	$porc_int = $info_exen['porc_int'];
	$porc_form = $info_exen['porc_form'];
	pg_free_result($result);
} else {
	$porc_imp = $porc_mul = $porc_int = $porc_form = 0;
}

################################################################################
#---------------------------------- AJUSTES -----------------------------------#
################################################################################	
if ((isset($forma_pago)) and ($forma_pago == "PLAN")) {
	$plan_pago = "PLAN";
	$no_cuota_temp = $no_cuota + 1;
	$liquidacion = $no_cuota_temp . "/" . $check_pdp1;
	$sql = "SELECT monto_cuota FROM imp_plan_de_pago WHERE gestion = '$gestion' AND no_cuota = '$no_cuota' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
	$result = pg_query($sql);
	$info_plan = pg_fetch_array($result, null, PGSQL_ASSOC);
	$monto_cuota = $info_plan['monto_cuota'];
	$total_a_pagar = $monto_cuota + $por_form;
	$monto_en_letras = numeros_a_letras($total_a_pagar);
	$observ_control = "Plan de Pago - Cuota $no_cuota_temp";
} else
	$observ_control = "";
################################################################################
#--------------------------- LEER DATOS GENERALES  ----------------------------#
################################################################################
if (($calcular_urbano) or ($calcular_transfer_urbano)) {
	include "siicat_info_inmu_leer_datos.php";
	include "siicat_info_predio_leer_datos.php";
	$direccion = get_direccion_from_id_inmu($id_inmu);
	$titular = get_contrib_nombre($tit_1id);
	$titular2 = get_contrib_nombre($tit_2id);
	$tit_edad = get_contrib_edad($tit_1id);

	$tit_tipo = get_contrib_tipo($tit_1id);
	$cod_pad = $pmc = get_contrib_pmc($tit_1id);
	$ci_nit = get_contrib_ci($tit_1id);

	if (strlen($ci_nit) > 12) {
		$ci_nit = substr($ci_nit, 0, 12);
	}

	$dom_dir = get_contrib_dom($tit_1id);
	$dom_ciu = "";
	$dom_dir_nom = get_contrib_dom_dir_nom($tit_1id);
}

$puerta = "-";
$dir_bloq = "-";
$dir_piso = "-";
$dir_apto = "-";
$nro_inmu = "-";

################################################################################
#--------------------- INFORMACION Y AREA DE TERRENO --------------------------#
################################################################################	
if (($calcular_urbano) or ($calcular_transfer_urbano)) {
	$final_gestion = $gestion . "-12-31";
	$sql = "SELECT valor_ant FROM cambios WHERE id_inmu = '$id_inmu' AND variable = 'sup_aprob' AND fecha_cambio > '$final_gestion' ORDER BY fecha_cambio LIMIT 1";

	$check_cambios = pg_num_rows(pg_query($sql));
	if ($check_cambios > 0) {
		$result_cambios = pg_query($sql);
		$info_cambios = pg_fetch_array($result_cambios, null, PGSQL_ASSOC);
		$sup_terr = ROUND($info_cambios['valor_ant'], 2);
		pg_free_result($result_cambios);
	} else {
		$sup_terr = $sup_aprob;
		$sup_terr = $superficie;

	}

	########################################
	#------- CALCULAR SUPERFICIE ----------#
	########################################	    
	$sql = "SELECT area(the_geom) FROM predios_ocha WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
	$check = pg_num_rows(pg_query($sql));
	if ($check == 0) {
		$ter_smen = 0;
	} else {
		$result = pg_query($sql);
		$value = pg_fetch_array($result, null, PGSQL_ASSOC);
		$ter_smen = ROUND($value['area'], 2);
		$sup_terr = ROUND($value['area'], 2);
	}

	########################################
	#------ DEFINIR MATERIAL DE VIA -------#
	########################################			
	$via_mat = get_material_de_via($id_inmu, $final_gestion);

	if ($via_mat == "NO EXISTE") {
		$via_mat = get_material_de_via_alt($id_inmu);
	}
	########################################
	#------------ DEFINIR ZONA ------------#
	########################################
	$ben_zona = get_zona($id_inmu);
	if ($ben_zona == "0") {
		$ben_zona = get_zona_alt($id_inmu);
	}
	### CHEQUEAR SI EXISTE UN REGISTRO HISTORICO SOBRE LA ZONA ###
	$sql = "SELECT valor_ant FROM cambios WHERE id_inmu = '$id_inmu' AND variable = 'zona_tribut' AND fecha_cambio > '$final_gestion' ORDER BY fecha_cambio LIMIT 1";
	$check_cambios = pg_num_rows(pg_query($sql));
	if ($check_cambios > 0) {
		$result_cambios = pg_query($sql);
		$info_cambios = pg_fetch_array($result_cambios, null, PGSQL_ASSOC);
		$zona = $zona_tribut = $ben_zona = $info_cambios['valor_ant'];
		pg_free_result($result_cambios);
	} else
		$zona = $ben_zona;

	if ($zona == "0") {
		$val_m2_terr = 0;
	} else {
		#----------- VALOR POR M2 -------------#	
		$val_m2_terr = imp_valorporm2_terr($gestion, $zona, $via_mat);
	}
	### INCLINACION ###
	$fact_incl = imp_factor_incl($gestion, $ter_topo);
	### SUB-TOTAL TERRENO ###
	$valor_t_subt = ROUND($sup_terr * $val_m2_terr * $fact_incl, 0);
	### SERVICIO DE AGUA ###
	$ser_agu = get_servicio($id_inmu, $final_gestion, "ser_agu");
	$fact_agu = imp_factor_serv($gestion, "serv_agua", $ser_agu);
	### SERVICIO DE ALCANTARILLADO ###
	$ser_alc = get_servicio($id_inmu, $final_gestion, "ser_alc");
	$fact_alc = imp_factor_serv($gestion, "serv_alc", $ser_alc);
	### SERVICIO DE LUZ ###		
	$ser_luz = get_servicio($id_inmu, $final_gestion, "ser_luz");
	$fact_luz = imp_factor_serv($gestion, "serv_luz", $ser_luz);
	### SERVICIO DE TELEFONO ###
	$ser_tel = get_servicio($id_inmu, $final_gestion, "ser_tel");
	$fact_tel = imp_factor_serv($gestion, "serv_tel", $ser_tel);
	### SERVICIO MINIMO ###
	$fact_min = imp_factor_serv($gestion, "serv_min", "SI");
	$factor = ($fact_agu + $fact_alc + $fact_luz + $fact_tel + $fact_min);
	#----------- AVALUO TERRENO -----------#		
	$valor_t = ROUND($valor_t_subt * $factor, 0);
}

################################################################################
#------------------ INFORMACION Y AREA DE EDIFICACIONES -----------------------#
################################################################################	

if (($calcular_urbano) or ($calcular_transfer_urbano)) {
	$edi_area = 0;
	$sql = "SELECT edi_num, edi_piso, edi_apto, edi_tipo, edi_ano, edi_edo, edi_simp, edi_val 
	        FROM info_edif 
			WHERE edi_ano <= '$gestion' AND cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' 
			ORDER BY edi_num, edi_piso ASC";
	
	$no_de_edificaciones = pg_num_rows(pg_query($sql));

	$result = pg_query($sql);
	$i = $j = $no_de_edificaciones = 0;
	$edi_tipo_diferente = false;

	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		foreach ($line as $col_value) {
			if ($i == 0) {
				$edi_num[$j] = $col_value;
			} elseif ($i == 1) {
				$edi_piso[$j] = $col_value;
			} elseif ($i == 2) {
				$edi_apto[$j] = $col_value;
			} elseif ($i == 3) {
				$edi_tipo[$j] = abr(trim($col_value));
				if ($edi_tipo[$j] == "") {
					$edi_tipo[$j] = "No def.";
				}
			} elseif ($i == 4) {
				$edi_ano[$j] = $col_value;
			} elseif ($i == 5) {
				$edi_edo[$j] = abr(trim($col_value));
				if ($edi_edo[$j] == "") {
					$edi_edo[$j] = "No def.";
				}
			} elseif ($i == 6) {
				$edi_simp[$j] = $col_value;
			} else {
				$edi_avaluo[$j] = $col_value;
				$i = -1;
				$no_de_edificaciones++;
			}
			$i++;
		}
		### CHEQUEAR TIPO DE EDIFICACIONES ###
		if ($j > 0) {
			if ($edi_tipo[$j - 1] != $edi_tipo[$j]) {
				$edi_tipo_diferente = true;
			}
		}

		########################################
		#----- CALCULAR AREA EDIFICACIONES ----#
		########################################
		$edi_area = $edi_area + $edi_simp[$j];
		$area_edif[$j] = ROUND($edi_simp[$j], 2);
		$j++;
	}

	if ($edi_tipo_diferente) {
		$edi_tipo_total = "Varios";
	} else {
		if ($no_de_edificaciones > 0) {
			$edi_tipo_total = $edi_tipo[$j - 1];
		} else {
			$edi_tipo[0] = "";
			$edi_tipo_total = "";
		}
	}
	$edi_area = ROUND($edi_area, 2);

	pg_free_result($result);
}

################################################################################
#---------------- CATASTRO URBANO: VALORACION DE EDIFICACIONES ----------------#
################################################################################
if (($calcular_urbano) or ($calcular_transfer_urbano)) {

	if ($gestion >= 2022) {
		$gestion_vencida = $gestion;
	} else {
		$gestion_vencida = $gestion - 1;
	}

	$gestion = $ano_actual - 1;
	$sql = "SELECT gestion,valor_t,valor_vi, avaluo_total FROM imp_pagados WHERE id_inmu = '$id_inmu' AND gestion = '$gestion_vencida' AND (forma_pago = 'CONTADO' OR forma_pago = 'VALIDADO' OR forma_pago = 'PRESCRIP')";
	$no_de_gestion = pg_num_rows(pg_query($sql));
	$result_gestion = pg_query($sql);
	$info_impuestos = pg_fetch_array($result_gestion, null, PGSQL_ASSOC);

	if ($no_de_gestion == 1) {
		$gestion_vencida = $info_impuestos['gestion'];
		$valor_te = $info_impuestos['valor_t'];
		$valor_vi = $info_impuestos['valor_vi'];
		$valor_total = $info_impuestos['avaluo_total'];
	}
	if ($no_de_gestion == 0 and $division == 1) {
		include "edificacion_valor.php";
		$valor_total = $valor_t + $valor_vi;
	}

}



########################################
#------------ TASA TAPR_UFV -----------#
########################################	
$tasa_taprufv = imp_tasa_taprufv($fecha);
if ($tasa_taprufv == -1) {
	$timestamp = strtotime($fecha . ' - 1 month');
	$fecha_ant = date('Y-m-d', $timestamp);
	$tasa_taprufv = imp_tasa_taprufv($fecha_ant);
	if ($tasa_taprufv == -1) {
		$timestamp = strtotime($fecha_ant . ' - 1 month');
		$fecha_ant = date('Y-m-d', $timestamp);
		$tasa_taprufv = imp_tasa_taprufv($fecha_ant);
		if ($tasa_taprufv == -1) {
			$tasa_taprufv = 0;
		}
	}
}
########################################
#--------- FECHA VENC GESTION ---------#
########################################	
if ($gestion < 1996) {
	$gestion_temp = 1996;
} else
	$gestion_temp = $gestion;

if (($calcular_transfer_urbano) or ($calcular_transfer_rural)) {
	$fecha_venc_1st = '1900-01-01';
	$fecha_venc = change_date(get_fecha_plus_dias_habiles($min_fech_ymd, 10));
	$fecha_venc = change_date($fecha_venc);
	######################################## FECHA PARA CAMBIAR EL 30/09/2024 #####################################v 
	#$fecha_venc = "2024/09/30";	  
} elseif ($calcular_patente) {
	$fecha_venc_1st = imp_get_fecha_venc_1st_patente($gestion_temp);
	$fecha_venc = imp_get_fecha_venc_patente($gestion_temp);
} else {
	$fecha_venc_1st = imp_get_fecha_venc_1st($gestion_temp);
	$fecha_venc = imp_get_fecha_venc($gestion_temp);
}
$fecha_venc_texto = change_date($fecha_venc);
########################################
#--------- DESCUENTO POR EDAD ---------#
########################################
if (($calcular_urbano) and ($tit_edad == 60) and ($dom_dir_nom == $dir_nom)) {
	$descuento_por_edad = $nota_descuento = true;
	$descuento_edad_porc = 20;
	$texto_descuento = "Descuento del 20% para contribuyentes mayores a 60 años de acuerdo a Ley 1886.";
} else {
	$descuento_por_edad = $nota_descuento = false;
	$descuento_edad_porc = 0;
	$texto_descuento = "";
}
########################################
#--------- DESCUENTO Y MULTAS ---------#
########################################	
$sql = "SELECT * FROM imp_base";
$result_base = pg_query($sql);
$info_base = pg_fetch_array($result_base, null, PGSQL_ASSOC);
$descuento_pagoentiempo_porc = $info_base['descuento'];
$multa_mora = $info_base['multa_mora'];
$multa_incum_porc = $info_base['multa_incum'];
$multa_admin = $info_base['multa_admin'];
$rep_form = $info_base['rep_form'];
pg_free_result($result_base);
########################################
#--------------- CREDITO --------------#
########################################
$credito = $monto_conban_total;
########################################
#------------ SALDO A FAVOR -----------#
########################################
$sql = "SELECT monto FROM imp_saldo_a_favor WHERE concepto = '$concepto_temp' AND id_item = '$id_item_temp'";
$check_saldo = pg_num_rows(pg_query($sql));
if ($check_saldo == 0) {
	$saldo_a_favor_tabla = 0;
} else {
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$saldo_a_favor_tabla = $info['monto'];
	pg_free_result($result);
}

################################################################################
#------------------- AJUSTES PARA LA BOLETA Y OTROS DATOS  --------------------#
################################################################################

$sql = "SELECT folio FROM $tabla_imp_pagados ORDER BY folio DESC LIMIT 1";
$check_preliquid2 = pg_num_rows(pg_query($sql));
if ($check_preliquid2 == 0) {
	$folio = 10000001;
} else {
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$folio_ant = (int) $info['folio'];
	pg_free_result($result);
	if ($folio_ant < 1000000) {
		$folio = 10000001;
	} else
		$folio = $folio_ant + 1;
}

if ($calcular_urbano) {
	$val_ter_subtotal = ROUND($val_m2_terr * $sup_terr, 0);
	$tipo_inmu = "CASA";
	if ($no_de_edificaciones < 6) {
		$edi_tipo[5] = $calidad_const[5] = $area_edif[5] = $factor_deprec[5] = $avaluo_edif[5] = "-";
		$edi_tipo_tabla = $edi_tipo[0];
		if ($no_de_edificaciones < 5) {
			$edi_tipo[4] = $calidad_const[4] = $area_edif[4] = $factor_deprec[4] = $avaluo_edif[4] = "-";
			if ($no_de_edificaciones < 4) {
				$edi_tipo[3] = $calidad_const[3] = $area_edif[3] = $factor_deprec[3] = $avaluo_edif[3] = "-";
				if ($no_de_edificaciones < 3) {
					$edi_tipo[2] = $calidad_const[2] = $area_edif[2] = $factor_deprec[2] = $avaluo_edif[2] = "-";
					if ($no_de_edificaciones < 2) {
						$edi_tipo[1] = $calidad_const[1] = $area_edif[1] = $factor_deprec[1] = $avaluo_edif[1] = "-";
						if ($no_de_edificaciones < 1) {
							$edi_tipo[0] = $calidad_const[0] = $area_edif[0] = $factor_deprec[0] = $avaluo_edif[0] = "-";
						}
					}
				}
			}
		}
	} elseif ($no_de_edificaciones > 6) {
		$edi_tipo[5] = $edi_tipo_tabla = "Varios";
		$calidad_const[5] = $area_edif[5] = $factor_deprec[5] = "XXX";
		$avaluo_edif[5] = $valor_vi - $avaluo_edif[0] - $avaluo_edif[1] - $avaluo_edif[2] - $avaluo_edif[3] - $avaluo_edif[4] - $avaluo_edif[5];
	}
}

################################################################################
#-------------------------- CALCULO DE IMPUESTO NETO --------------------------#
################################################################################
if ($calcular_urbano) {
	$imp_neto = ROUND($base_imp_s_ex * $esc_imp / 100, 0) + $cuota_fija;
	$monto_det = $imp_neto;
} elseif (($calcular_transfer_urbano) or ($calcular_transfer_rural)) {

	#######################
	# VALOR DE LA MINUTA	 #
	#######################
	$cambio_usd = imp_getcoti($min_fech, "usd");

	if ($modo_trans == "HER") {
		$min_val = 0;
	} 

	if ($min_mon == "bs") {
		$valor_min = $min_val;
		$valor_usd = "-";
	} else {
		$valor_min = ROUND($cambio_usd * $min_val, 0);
		$valor_usd = $min_val;
	}

	$valor_doc_priv = "-";
	$valor_cat = $base_imp;
	### CHEQUEAR SI SE MODIFICÓ EL PREDIO EN EL AÑO ACTUAL ###
	$sql = "SELECT valor_ant FROM cambios WHERE id_inmu = '$id_inmu' AND (variable = 'sup_aprob' OR variable = 'cod_cat_ant') AND fecha_cambio > '$final_gestion'";
	$check_cambios = pg_num_rows(pg_query($sql));
	if ($check_cambios > 0) {
		$valor_cat = $valor_t = $valor_vi = $valor_total = "0";
	}
	### VALOR CATASTRAL VS. VALOR MINUTA ###	
	if ($modo_trans == "HER") {
		$base_imp = 0;
		$base_imp_seleccion = "---";
	} elseif ($valor_total > $valor_min) {
		$base_imp = $valor_total;
		$base_imp_seleccion = "VALOR CATASTRAL";
	} else {
		$base_imp = $valor_min;
		$base_imp_seleccion = "VALOR MINUTA";
	}
	$monto_det = $imp_neto = ROUND(($base_imp / 100) * 3, 0);
	$porc_select = 0;
} elseif ($calcular_rural) {
	$alicuota = 0.25;
	$imp_neto = ROUND(($base_imp / 100) * $alicuota, 0);
	$monto_det = $imp_neto;
}

if (($fecha <= $fecha_venc_1st) and ($fecha_venc_1st != -1)) {

	$descuento = ROUND($monto_det * ($descuento_pagoentiempo_porc + $descuento_edad_porc) / 100, 0);
	$calcular_multa = false;
	$cantidad_de_dias = $ufv_fecha_venc = $usd_fecha_venc = 0;
	$fecha_venc_preliquid = $fecha_venc_preliquid_texto = change_date($fecha_venc_1st);
	$nota_descuento = true;
	$texto_descuento = $texto_descuento . " Descuento por pago en tiempo $descuento_pagoentiempo_porc %.";
} elseif (($fecha <= $fecha_venc) and ($fecha_venc != -1)) {
	$descuento = ROUND($monto_det * $descuento_edad_porc / 100, 0);
	$calcular_multa = false;
	$cantidad_de_dias = $ufv_fecha_venc = $usd_fecha_venc = 0;
	$fecha_venc_preliquid = $fecha_venc_preliquid_texto = change_date($fecha_venc);
} else {
	$descuento = ROUND($monto_det * $descuento_edad_porc / 100, 0);
	if ($fecha_venc == -1) {
		$cantidad_de_dias = $ufv_fecha_venc = $fecha_venc_preliquid = 0;
		$fecha_venc_preliquid_texto = "-";
		$calcular_multa = false;
	} else {
		$cantidad_de_dias = imp_dias_de_mora($fecha_venc, $fecha);
		$calcular_multa = true;
		### DEUDAS ANTERIORES AL 26/12/2002 ###
		if ($fecha_venc < "2002/12/26") {
			$deuda2002 = true;
			$ufv_fecha_venc = 0;
			$usd_fecha_venc = imp_getcoti($fecha_venc, "usd");
			### DEUDAS POSTERIORES AL 26/12/2002 ###
		} else {
			$ufv_fecha_venc = imp_getcoti($fecha_venc, "ufv");
			$usd_fecha_venc = imp_getcoti($fecha_venc, "usd");
		}
		$fecha_venc_preliquid = $fecha_venc_preliquid_texto = change_date(get_fecha_plus_dias_habiles($fecha, 10));
	}
}
$monto_imp = $monto_det - $descuento;
### CALCULO SEGUNDA COLUMNA ###
if ($calcular_multa) {
	$ufv_actual = imp_getcoti($fecha, "ufv");

	### DEUDAS ANTERIORES AL 26/12/2002 ###
	if ($deuda2002) {
		$mantenimiento_de_valor = calc_mant_valor_2002($usd_fecha_venc, $ufv_actual, $monto_imp);
		$tributo_omitido_actualizado = $monto_imp + $mantenimiento_de_valor;
		$interes_calculo_en_bs = $interes = $interes_bs = calc_interes_2002($monto_imp, $usd_fecha_venc, $tasa_taprufv, $cantidad_de_dias, $ufv_actual, $tributo_omitido_actualizado);
		$usd_20021226 = 7.49;
		$tributo_omit_act = $monto_imp * ($usd_20021226 / $usd_fecha_venc);
		$trib_omit = ROUND($tributo_omit_act / 1.00815, 5);
	} else {

		$mantenimiento_de_valor = calc_mant_valor($ufv_fecha_venc, $ufv_actual, $monto_imp);
		$tributo_omitido_actualizado = $monto_imp + $mantenimiento_de_valor;
		$interes_calculo_en_bs = calc_interes2016($tributo_omitido_actualizado, $tasa_taprufv, $cantidad_de_dias);
		$trib_omit = ROUND($monto_imp / $ufv_fecha_venc, 0);
		$interes = calc_interes2016($trib_omit, $tasa_taprufv, $cantidad_de_dias);
		$interes_bs = ROUND($interes * $ufv_actual, 5);
	}

	$tasa_interes = $tasa_taprufv;
	$interes_red = ROUND($interes, 2);
	$multa_mora_bs = 0;
	#######################################
	#        MULTA POR INCUMPLIMIENTO     #
	#######################################
	if ($monto_imp > 0) {
		$multa_incump_ufv = ROUND($trib_omit * $multa_incum_porc / 100, 5);
		if (($multa_incump_ufv <= 50) and ($tit_tipo == "NATURAL")) {
			$multa_incump_ufv = 50;
		}
	} else
		$multa_incump_ufv = 0;

	$multa_incump_bs = ROUND($multa_incump_ufv * $ufv_actual, 2);
	$multa_incump_bs = ROUND($trib_omit * 10 / 100, 2);
	$x = ROUND($multa_incump_ufv * $ufv_actual, 3);
	$multa_admin_bs = 0;
	$multa_omision_pago = 0;
	$multa_omision_registro = 0;
	$multas_subtotal = $multa_incump_ufv + $multa_omision_pago + $multa_omision_registro;

	if (!isset($condon_porc_select)) {
		$condon_porc_select = 0;
	}
	$condonacion_de_multas = $condon_porc_select;
	$monto_condonacion = ($multas_subtotal / 100) * $condonacion_de_multas;
	if ($monto_condonacion > 0) {
		$monto_condonacion_neg = $monto_condonacion * (-1);
	} else
		$monto_condonacion_neg = $monto_condonacion;
	$multas_total = $multas_subtotal - $monto_condonacion;
	$multas_total_bs = ROUND($multas_total * $ufv_actual, 0);
	$deuda_trib = ROUND($trib_omit + $interes, 5);
	#echo "<br>$deuda_trib = ROUND($trib_omit + $interes + $multas_total,5)<br>";
	$deuda_bs = ROUND($deuda_trib * $ufv_actual + $x, 0);
	$mant_val_bs = $deuda_bs - $monto_imp - $interes_bs - $multas_total_bs;
} else {
	$trib_omit = $tasa_interes = $interes = $interes_bs = $interes_red = $multa_incump_ufv = $multa_omision_pago = $multa_omision_registro = 0;
	$multas_total = $multas_total_bs = $monto_condonacion = $monto_condonacion_neg = $ufv_actual = $mant_val_bs = $deuda_trib = 0;
	$multa_mora_bs = $multa_incump_bs = $multa_admin_bs = $mantenimiento_de_valor = 0;
	$deuda_bs = $monto_imp;
}

$pagos_ant = $deuda_pagada_sin_repform;

### CHEQUEAR SI EXISTE UN SALDO A FAVOR DE LAS GESTIONES ANTERIORES ###
if ((!$calcular_transfer_urbano) and (!$calcular_transfer_rural)) {
	$sql = "SELECT sum(sal_favor) AS sum1, sum(sal_prox_gest) AS sum2 FROM $tabla_imp_pagados WHERE $where_option AND gestion < '$gestion' AND (estatus = 'CANCELADO' OR estatus = 'VALIDADO')";
	$result_saldo = pg_query($sql);
	$info_saldo = pg_fetch_array($result_saldo, null, PGSQL_ASSOC);
	$sum_saldo_a_favor_ya_usado = $info_saldo['sum1'];
	$sum_sal_prox_gest = $info_saldo['sum2'];
	pg_free_result($result_saldo);
	if ($sum_sal_prox_gest > $sum_saldo_a_favor_ya_usado) {
		$saldo_a_favor = $sum_sal_prox_gest - $sum_saldo_a_favor_ya_usado;
	} else
		$saldo_a_favor = 0;
	#echo "CALCULAR MONTO L921 SQL: $sql, SALDO A FAVOR: $saldo_a_favor <br />";
} else
	$saldo_a_favor = 0;
if (($deuda_bs == 0) or ($deuda_bs <= $pagos_ant)) {
	$rep_form = 0;
}
### APLICAR EXENCION	
$exen_imp = ROUND($monto_imp * $porc_imp / 100, 0);
$exen_mul = ROUND($multas_total_bs * $porc_mul / 100, 0);
$exen_int = ROUND(($interes_bs + $mant_val_bs) * $porc_int / 100, 0);
$exen_form = ROUND($rep_form * $porc_form / 100, 0);
$exencion = $exen_imp + $exen_mul + $exen_int + $exen_form;
### MONTOS APLICANDO EXENCION
$monto_imp_exen = $monto_imp - $exen_imp;
$multas_total_bs_exen = $multas_total_bs - $exen_mul;
$accesorios_exen = ($interes_bs + $mant_val_bs) - $exen_int;
$rep_form_exen = $rep_form - $exen_form;

$monto_a_pagar_temporal = $deuda_bs + $rep_form - $exencion;
#echo "TEMP: $monto_a_pagar_temporal, CREDITO: $credito, SALDO_A_FAVOR_TABLA: $saldo_a_favor_tabla<br>";
if (($pagos_ant + $saldo_a_favor) > $monto_a_pagar_temporal) {
	$monto_a_pagar = 0;
	#$saldo_a_favor = $credito - $monto_a_pagar_temporal;
} else {
	$monto_a_pagar = $monto_a_pagar_temporal - $pagos_ant - $saldo_a_favor;
}

if ($monto_a_pagar_temporal < $pagos_ant) {
	$sal_prox_gest = ($monto_a_pagar_temporal - $pagos_ant) * (-1);
} else
	$sal_prox_gest = 0;

$credito = 0;
$saldo = 0;
$total_a_pagar = ROUND($monto_a_pagar, 0);
$monto_en_letras = numeros_a_letras($total_a_pagar);
if (strlen($monto_en_letras) > 36) {
	if (strlen($monto_en_letras) < 41) {
		$monto_en_letras1 = $monto_en_letras;
		$monto_en_letras2 = "00/100 Bs.";
	} else {
		$monto_en_letras1 = substr($monto_en_letras, 0, 41);
		$monto_en_letras2 = substr($monto_en_letras, 41, strlen($monto_en_letras) - 41) . " 00/100 Bs.";
	}
} else {
	$monto_en_letras1 = $monto_en_letras . " 00/100 Bs.";
	$monto_en_letras2 = "";
}

### ACTIVAR NOTAS DE PIE
if ($total_a_pagar == 0) {
	$nota_fecha_venc_preliquid = false;
	$fecha_venc_preliquid = $fecha;
	$fecha_venc_preliquid_texto = "-";
} else
	$nota_fecha_venc_preliquid = true;
#echo "CALCULAR MONTO L785 EXCENCION: $exencion<br />";
if ($exen_select > 0) {
	$nota_exencion = true;
	$sql = "SELECT * FROM imp_exenciones WHERE numero = '$exen_select'";
	$result = pg_query($sql);
	$info_exen = pg_fetch_array($result, null, PGSQL_ASSOC);
	$ley = utf8_decode($info_exen['ley']);
	$fecha_exen = change_date($info_exen['fecha']);
	$descripcion = utf8_decode($info_exen['descripcion']);
	$porc_imp = $info_exen['porc_imp'];
	$porc_mul = $info_exen['porc_mul'];
	$porc_int = $info_exen['porc_int'];
	$porc_form = $info_exen['porc_form'];
	pg_free_result($result);
	if ($exen_select == "99") {
		$texto_exencion = "Se aplicó una rebaja de 100% de impuestos por motivo de PRESCRIPCION.";
	} else {
		$texto_exencion = "Se aplicó $descripcion según $ley del $fecha_exen.";
		if (strlen($texto_exencion) > 120) {
			$descripcion = substr($descripcion, 0, strlen($descripcion) - (strlen($texto_exencion) - 120));
			$texto_exencion = "Se aplicó $descripcion [...] según $ley del $fecha_exen.";
		}
	}
} else {
	$nota_exencion = false;
	$texto_exencion = "";
}
if ($monto_condonacion > 0) {
	$nota_condonacion = true;
	$sql = "SELECT * FROM imp_condonaciones WHERE numero = '$condon_select'";
	$result = pg_query($sql);
	$info_exen = pg_fetch_array($result, null, PGSQL_ASSOC);
	$ley = utf8_decode($info_exen['ley']);
	$fecha_exen = change_date($info_exen['fecha']);
	$descripcion = utf8_decode($info_exen['descripcion']);
	$porcentaje = utf8_decode($info_exen['porcentaje']);
	pg_free_result($result);
	$texto_condonacion = "Se aplicó una condonación de multa de $porcentaje % según $ley del $fecha_exen.";
} else
	$nota_condonacion = false;
?>