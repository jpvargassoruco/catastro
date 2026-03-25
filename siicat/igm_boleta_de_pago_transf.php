<?php
################################################################################
#                     EL SIGUIENTE CODIGO FUE CREADO POR:                      #
#         MERCATORGIS - SISTEMAS DE INFORMACION GEOGRAFICA Y DE CATASTRO       #
#     ***** http://www.mercatorgis.com  *****  info@mercatorgis.com *****      #
################################################################################	

# LINEA 73 SELECCION DE IMPUESTO
# LINEA 93 CONVALIDAR PAGO
# LINEA 135 BORRAR PAGO
# LINEA 232 RECTIFICAR PAGO
# LINEA 288 REGISTRAR PRE-LIQUIDACION
# LINEA 416 REGISTRAR PRELIQUIDACION SIIM
# LINEA 466 MOSTRAR SOLO PRELIQUIDACION CANCELADA DEL SIIM
# LINEA 484 CHECK ESTATUS DEL PAGO
# LINEA 567 CHEQUEAR POR PAGOS BANCO
# LINEA 546 APLICAR VALOR EN LIBROS
# LINEA 607 CHEQUEAR SI SE PUEDE APLICAR PRESCRIPCION
# LINEA 635 EXENCION APLICADA /NO APLICADA
# LINEA 655 CONDONACION DE MULTAS APLICADA /NO APLICADA
# LINEA 676 LEER DATOS DE PRE-LIQUIDACION(ES) SI EXISTE(N)
# LINEA 753 CALCULAR MONTO
# LINEA 887 LEER REGISTROS CANCELADOS O VALIDADOS DE TABLA IMP_PAGADOS
# LINEA 920 MENU SELECCION DE EXENCIONES
# LINEA 949 MENU SELECCION DE CONDONACIONES
# LINEA 984 IMPRIMIR PRE-LIQUIDACION
# LINEA 1179 LEER SALDO A FAVOR
# LINEA 1382 FORMULARIO PARA IMPRIMIR BOLETA


if (isset($_POST["gestion"])) {
	$gestion = $_POST["gestion"];
} elseif (isset($_GET["gestion"])) {
	$gestion = $_GET["gestion"];
} elseif (!isset($gestion)) {
	$gestion = 0;
}

if (isset($_POST["forma_pago"])) {
	$forma_pago = $_POST["forma_pago"];
} else
	$forma_pago = "CONTADO";

if ((isset($_POST["id_predio_rural"])) or (isset($_GET["idpr"]))) {
	if (isset($_POST["id_predio_rural"])) {
		$id_predio_rural = $_POST["id_predio_rural"];
	} else
		$id_predio_rural = $_GET["idpr"];
}

if ((isset($_POST["id_patente"])) or (isset($_GET["id_pat"]))) {
	if (isset($_POST["id_patente"])) {
		$id_patente = $_POST["id_patente"];
	} else
		$id_patente = $_GET["id_pat"];
	$cod_pat = get_patente_no_de_patente($id_patente);
}

if (isset($_POST["imp_neto"])) {
	$imp_neto_post = $_POST["imp_neto"];
}

if (isset($_GET["folio"])) {
	$folio_select = $_GET["folio"];
	$mostrar_solo_preliquid = true;
} else {
	$mostrar_solo_preliquid = false;
}

$sello = $plan_de_pago = $boleta = false;
$error = false;
$no_control = "";
$pago_al_contado = $plan_de_pago_submit = false;
$convalidar_pago = false;
$mostrar_botones = true;
$descont_exen = $porc_select = 0;
$exencion_seleccionada = false;
$descuento_por_edad = false;
$registrar_preliq = false;
################################################################################
#-------------------------- SELECCION DE IMPUESTO -----------------------------#
################################################################################	
$calcular_urbano = $calcular_rural = $calcular_patente = $calcular_transfer_urbano = $calcular_transfer_rural = false;
if (((isset($_POST["id_inmu"])) or (isset($_GET["inmu"]))) and ((isset($_POST["modo_trans"])) or ((isset($_POST["submit"])) and ($_POST["submit"] == "Registrar Transferencia"))) or (isset($_GET["tfurb"]))) {
	### $_GET["tfurb"] DEFINIDO EN siicat_impuestos_transferencia.php
	$calcular_transfer_urbano = true;
	$concepto_temp = "TRANSFER URBANO";
	$id_item_temp = $id_inmu;
	$tabla_liq = "imp_transfer";
	$columna_id = "id_inmu";
} elseif ((isset($_POST["id_inmu"])) or (isset($_GET["inmu"]))) {
	$calcular_urbano = true;
	$concepto_temp = "LIQUIDACION IPBI";
	$id_item_temp = $id_inmu;
	$tabla_liq = "imp_pagados";
	$columna_id = "id_inmu";
} elseif (((isset($_POST["id_predio_rural"])) or (isset($_GET["idpr"]))) and ((isset($_POST["modo_trans"])) or ((isset($_POST["submit"])) and ($_POST["submit"] == "Registrar Transferencia"))) or (isset($_GET["tfrur"]))) {
	$calcular_transfer_rural = true;
	$concepto_temp = "TRANSFER RURAL";
	$id_item_temp = $id_predio_rural;
	$tabla_liq = "imp_transfer_rural";
	$columna_id = "id_predio_rural";
} elseif ((isset($_POST["id_predio_rural"])) or (isset($_GET["idpr"]))) {
	$calcular_rural = true;
	$concepto_temp = "LIQUIDACION IPA";
	$id_item_temp = $id_predio_rural;
	$tabla_liq = "imp_pagados_rural";
	$columna_id = "id_predio_rural";
} elseif ((isset($_POST["id_patente"])) or (isset($_GET["id_pat"]))) {
	$calcular_patente = true;
	$concepto_temp = "LIQUIDACION PAT";
	$id_item_temp = $id_patente;
	$tabla_liq = "patentes_pagados";
	$columna_id = "id_patente";
}
################################################################################
#------------------------------ CONVALIDAR PAGO -------------------------------#
################################################################################	
if ((isset($_POST["convalidar"])) and ($_POST["convalidar"] == "REGISTRAR")) {
	$gestion_conval = $_POST["gestion"];
	$no_orden_conval = (int) $_POST["no_orden"];
	$fech_pago_conval = $_POST["fech_pago"];
	$cuota_conval = (int) $_POST["cuota"];
	$nombre_banco_conval = $_POST["nombre_banco"];
	$control_conval = $_POST["control"];
	if ($calcular_urbano) {
		#$monto_imp = $descuento = $cantidad_de_dias = $tributo_omitido = $multa_omision_pago = $multa_incump_ufv = 0;
		#$condon_select = $monto_condonacion = $interes = $deuda_trib = $deuda_bs = $rep_form = $sal_favor = $total_a_pagar = 0;			
		$sql = "INSERT INTO imp_pagados (folio, cod_geo, id_inmu, gestion, deuda_bs, total_a_pagar, estatus, fech_pago, fech_reg, hora_reg, userid, control)
				    VALUES ('$no_orden_conval','$cod_geo','$id_item_temp','$gestion_conval','$cuota_conval','$cuota_conval','VALIDADO','$fech_pago_conval','$fecha','$hora','$user_id','$control_conval')";

		#echo "L99 SQL: $sql<br>";
	} elseif ($calcular_transfer_urbano) {
		$sql = "INSERT INTO imp_transfer (folio, cod_geo, id_inmu, tit_1id, min_num, not_nom,
				         not_num, not_cls, not_exp, min_val, min_mon, min_fech, id_comp, modo_trans,
								 base_imp, deuda_bs, total_a_pagar, fech_imp, estatus, fech_pago, fech_reg, hora_reg, userid, control)
				        VALUES ('$no_orden_conval','$cod_geo','$id_item_temp','$tit_1id','$min_num','$not_nom',
								 '$not_num','$not_cls','$not_exp','$min_val','$min_mon','$min_fech','$id_comp','$modo_trans',
								 '$min_val','$cuota_conval','$cuota_conval','$fech_pago_conval','VALIDADO','$fech_pago_conval','$fecha','$hora','$user_id','$control_conval')";
		#echo "L137 SQL: $sql<br />";								 
	} elseif ($calcular_rural) {
		$sql = "INSERT INTO imp_pagados_rural (folio, id_predio_rural, gestion, deuda_bs, total_a_pagar, estatus, fech_pago, fech_reg, hora_reg, userid, control)
				    VALUES ('$no_orden_conval','$id_item_temp','$gestion_conval','$cuota_conval','$cuota_conval','VALIDADO','$fech_pago_conval','$fecha','$hora','$user_id','$control_conval')";
		#echo "L103 SQL: $sql<br>";
	} elseif ($calcular_transfer_rural) {
		$sql = "INSERT INTO imp_transfer_rural (folio, id_predio_rural, tit_1id, min_num, not_nom,
				         not_num, not_cls, not_exp, min_val, min_mon, min_fech, id_comp, modo_trans,
								 base_imp, deuda_bs, total_a_pagar, fech_imp, estatus, fech_pago, fech_reg, hora_reg, userid, control)
				        VALUES ('$no_orden_conval','$cod_geo','$id_item_temp','$tit_1id','$min_num','$not_nom',
								 '$not_num','$not_cls','$not_exp','$min_val','$min_mon','$min_fech','$id_comp','$modo_trans',
								 '$min_val','$cuota_conval','$cuota_conval','$fech_pago_conval','VALIDADO','$fech_pago_conval','$fecha','$hora','$user_id','$control_conval')";
		#echo "L146 SQL: $sql<br />";	
	} elseif ($calcular_patente) {
		$sql = "INSERT INTO patentes_pagados (folio, id_patente, gestion, deuda_bs, total_a_pagar, estatus, fech_pago, fech_reg, hora_reg, userid, control)
				    VALUES ('$no_orden_conval','$id_item_temp','$gestion_conval','$cuota_conval','$cuota_conval','VALIDADO','$fech_pago_conval','$fecha','$hora','$user_id','$control_conval')";
		#echo "L107 SQL: $sql<br>";	         
	}
	pg_query($sql);
	### REGISTRAR EL PAGO EN EL BANCO EN IMP_CONTROL_BANCO ###	
	$sql = "INSERT INTO imp_control_banco (fech_pago, nombre_banco, no_boleta_banco, monto_banco, concepto, id_item, gestion, modo_pago, folio, fech_reg, hora_reg, userid_reg)
           VALUES('$fech_pago_conval','$nombre_banco_conval','$control_conval','$cuota_conval','$concepto_temp','$id_item_temp','$gestion_conval','VALIDADO','$no_orden_conval','$fecha','$hora','$user_id')";
	#echo "L121 SQL: $sql<br>";
	pg_query($sql);
	### SUPRIMIR PRELIQUIDACIONES DEL MISMO ITEM ###
	if ($calcular_urbano) {
		$sql = "UPDATE imp_pagados SET estatus = 'OBSOLETO' WHERE folio != '$no_orden_conval' AND id_inmu = '$id_item_temp' AND gestion = '$gestion_conval' AND estatus = 'PRELIQUID'";
	} elseif ($calcular_transfer_urbano) {
		$sql = "UPDATE imp_transfer SET estatus = 'OBSOLETO' WHERE folio != '$no_orden_conval' AND id_inmu = '$id_item_temp' AND estatus = 'PRELIQUID'";
	} elseif ($calcular_rural) {
		$sql = "UPDATE imp_pagados_rural SET estatus = 'OBSOLETO' WHERE folio != '$no_orden_conval' AND id_predio_rural = '$id_item_temp' AND gestion = '$gestion_conval' AND estatus = 'PRELIQUID'";
	} elseif ($calcular_transfer_rural) {
		$sql = "UPDATE imp_transfer_rural SET estatus = 'OBSOLETO' WHERE folio != '$no_orden_conval' AND id_predio_rural = '$id_item_temp' AND estatus = 'PRELIQUID'";
	} elseif ($calcular_patente) {
		$sql = "UPDATE patentes_pagados SET estatus = 'OBSOLETO' WHERE folio != '$no_orden_conval' AND id_patente = '$id_item_temp' AND gestion = '$gestion_conval' AND estatus = 'PRELIQUID'";
	}
	#echo "L248 SQL: $sql<br />";
	pg_query($sql);
}
################################################################################
#-------------------------------- BORRAR PAGO ---------------------------------#
################################################################################	
if ((isset($_POST["rect"])) and ($_POST["rect"] == "BORRAR")) {
	$id_conban_select = $_POST["id_conban"];
	### SELECCIONAR DATOS DEL REGISTRO ###
	$sql = "SELECT concepto, id_item, gestion, folio FROM imp_control_banco WHERE id = '$id_conban_select'";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$concepto_borrar = $info['concepto'];
	$id_item_borrar = $info['id_item'];
	$gestion_borrar = $info['gestion'];
	$folio_borrar = $info['folio'];
	#echo "CUOTA_TABLA: $cuota_tabla, FECH_VENC: $fech_venc<br>";
	pg_free_result($result);
	### BORRAR DE imp_control_banco ###
	$sql = "DELETE FROM imp_control_banco WHERE id = '$id_conban_select'";
	#echo "L100 SQL: $sql<br>";		 
	pg_query($sql);
	### CHEQUEAR SI EL ESTATUS ERA 'CANCELADO' O 'VALIDADO' ###
	$sql = "SELECT estatus FROM $tabla_liq WHERE folio = '$folio_borrar'";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$estatus_borrar = $info['estatus'];
	pg_free_result($result);
	if ($estatus_borrar == "CANCELADO") {
		### RE-ESTABLECER PRELIQUIDACION CANCELADA EN imp_pagados, imp_pagados_rural o patentes_pagados ###
		$sql = "UPDATE $tabla_liq SET estatus = 'PRELIQUID', fech_pago = '1900-01-01', fech_reg = '1900-01-01', hora_reg = '', userid = '' WHERE folio = '$folio_borrar'";
		pg_query($sql);
	} else {
		### BORRAR PAGO VALIDADO EN imp_pagados, imp_pagados_rural o patentes_pagados ###
		$sql = "DELETE FROM $tabla_liq WHERE folio = '$folio_borrar'";
		pg_query($sql);
	}
	### RE-ESTABLECER PRELIQUIDACIONES OBSOLETAS ###		
	if ($calcular_urbano) {
		$sql = "UPDATE imp_pagados SET estatus = 'PRELIQUID' WHERE estatus = 'OBSOLETO' AND id_inmu = '$id_item_borrar' AND gestion = '$gestion_borrar'";
	} elseif ($calcular_rural) {
		$sql = "UPDATE imp_pagados_rural SET estatus = 'PRELIQUID' WHERE id_predio_rural = '$id_item_borrar' AND gestion = '$gestion_borrar'";
	} elseif ($calcular_patente) {
		$sql = "UPDATE patentes_pagados SET estatus = 'PRELIQUID' WHERE id_patente = '$id_item_borrar' AND gestion = '$gestion_borrar'";
	}
	pg_query($sql);
	#echo "L127 SQL: $sql<br>";		    	 	  
}
################################################################################
#------------------------------ RECTIFICAR PAGO -------------------------------#
################################################################################	
if ((isset($_POST["rect"])) and ($_POST["rect"] == "RECTIFICAR")) {
	$id_conban_select = $_POST["id_conban"];
	### SELECCIONAR DATOS DEL REGISTRO ###
	$sql = "SELECT monto_banco, concepto, id_item, gestion, folio FROM imp_control_banco WHERE id = '$id_conban_select'";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$monto_rect = $info['monto_banco'];
	$concepto_rect = $info['concepto'];
	$id_item_rect = $info['id_item'];
	$gestion_rect = $info['gestion'];
	$folio_rect = $info['folio'];
	#echo "CUOTA_TABLA: $cuota_tabla, FECH_VENC: $fech_venc<br>";
	pg_free_result($result);

	### MODIFICAR *_pagados ###
	if ($calcular_urbano) {
		$sql = "UPDATE imp_pagados SET estatus = 'RECTIFIC' WHERE id_inmu = '$id_item_rect' AND gestion = '$gestion_rect'";
	} elseif ($calcular_rural) {
		$sql = "UPDATE imp_pagados_rural SET estatus = 'RECTIFIC' WHERE id_predio_rural = '$id_item_rect' AND gestion = '$gestion_rect'";
	} elseif ($calcular_patente) {
		$sql = "UPDATE patentes_pagados SET estatus = 'RECTIFIC' WHERE id_patente = '$id_item_rect' AND gestion = '$gestion_rect'";
	}
	#echo "L278 SQL: $sql<br>";		 
	pg_query($sql);
}
################################################################################
#--------------------------- AUMENTAR SALDO A FAVOR ---------------------------#
################################################################################	
if ((isset($_POST["saldo"])) and ($_POST["saldo"] == "AUMENTAR")) {

}
################################################################################
#-------------------------- REGISTRAR PRELIQUIDACION --------------------------#
################################################################################	
if ((isset($_POST["registro"])) and ($_POST["registro"] == "REGISTRAR") and (!isset($_POST["preliquid_siim"]))) {
	$registrar_preliq = false;
	$folio_select = $_POST["folio_select"];
	$fecha_banco = trim($_POST["fecha_banco"]);
	$fecha_banco_ymd = change_date_to_ymd_10char($fecha_banco);
	$nombre_banco = trim($_POST["nombre_banco"]);
	$nombre_banco_form = utf8_decode($nombre_banco);
	$no_boleta_banco = trim($_POST["no_boleta_banco"]);
	# $total_a_pagar = $_POST["total_a_pagar"];
	######################################	
	# LEER DATOS DEL FOLIO ESPECIFICADO ##
	######################################		
	if ($calcular_urbano) {
		$sql = "SELECT monto_imp, exen_id, mul_incum, condonacion, interes, ufv_actual, rep_form, total_a_pagar, fech_imp, fech_imp_venc FROM imp_pagados WHERE folio = '$folio_select'";
	} elseif ($calcular_rural) {
		$sql = "SELECT monto_imp, exen_id, mul_incum, condonacion, interes, ufv_actual, rep_form, total_a_pagar, fech_imp, fech_imp_venc FROM imp_pagados_rural WHERE folio = '$folio_select'";
	} elseif ($calcular_patente) {
		$sql = "SELECT monto_imp, exen_id, mul_incum, condonacion, interes, ufv_actual, rep_form, total_a_pagar, fech_imp, fech_imp_venc FROM patentes_pagados WHERE folio = '$folio_select'";
	}
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$monto_imp_tabla = $info['monto_imp'];
	$exen_id_select = $info['exen_id'];
	$mul_incum_tabla = $info['mul_incum'];
	$condonacion_tabla = $info['condonacion'];
	$interes_tabla = $info['interes'];
	$ufv_actual_tabla = $info['ufv_actual'];
	$rep_form_tabla = $info['rep_form'];
	$cuota_tabla = $info['total_a_pagar'];
	$fech_imp = $info['fech_imp'];
	$fech_imp_venc = $info['fech_imp_venc'];
	pg_free_result($result);
	$interes_para_boleta3 = ROUND($interes_tabla * $ufv_actual_tabla, 0);
	$multa_para_boleta3 = ROUND(($mul_incum_tabla - $condonacion_tabla) * $ufv_actual_tabla, 0);
	$mantval_para_boleta3 = $cuota_tabla - $monto_imp_tabla - $interes_para_boleta3 - $multa_para_boleta3 - $rep_form_tabla;
	######################################
	#---------- CHEQUEAR DATOS ----------#
	######################################
	if ($cuota_tabla == "0") {
		$registro_valido = true;
		$fecha_banco = $fech_imp;
		$nombre_banco = "---";
		$no_boleta_banco = 0;
	} else {
		$registro_valido = false;
		if (!check_fecha($fecha_banco, $dia_actual, $mes_actual, $ano_actual)) {
			$error_registro = true;
			$mensaje_de_error_registro = "Error: La fecha ingresada no es v�lida!";
		} elseif (($nombre_banco == "") or ($nombre_banco == "---------------")) {
			$error_registro = true;
			$mensaje_de_error_registro = "Error: Tiene que ingresar el nombre de la instituci�n donde cancel� el pago!";
		} elseif ($no_boleta_banco == "") {
			$error_registro = true;
			$mensaje_de_error_registro = "Error: Tiene que ingresar el n�mero de la boleta del pago!";
		} else
			$registro_valido = true;
	}
	######################################
	#---------- REGISTRAR PAGO ----------#
	######################################
	if ($registro_valido) {
		$imprimir_boleta_de_pago = true;
		### DEFINIR ESTATUS ###
		if ($exen_id_select == '99') {
			$estatus_reg = $forma_pago = "PRESCRIP";
			$fech_imp_venc = change_date_to_ymd_10char($fecha);
		} else {
			$estatus_reg = "CANCELADO";
			$forma_pago = "CONTADO";
		}
		######################################
		# REGISTRO EN TIEMPO/FUERA DE TIEMPO #
		######################################	
		$f1 = strtotime($fech_imp_venc);
		$f2 = strtotime($fecha_banco_ymd);
		if ($f2 > $f1) {  ### YA PASO LA FECHA DE VENCIMIENTO
			$conciliacion = true;
			$forma_pago = "CREDITO";
			$estatus_reg = "PARCIAL";
			### REGISTRAR EL PAGO COMO PAGO PARCIAL ###					 
			if ($calcular_urbano) {
				$sql = "UPDATE imp_pagados SET forma_pago = '$forma_pago', estatus = '$estatus_reg', fech_pago = '$fecha_banco_ymd', fech_reg = '$fecha', hora_reg = '$hora', userid = '$user_id' WHERE folio = '$folio_select'";
			} elseif ($calcular_rural) {
				$sql = "UPDATE imp_pagados_rural SET forma_pago = '$forma_pago', estatus = '$estatus_reg', fech_pago = '$fecha_banco_ymd', fech_reg = '$fecha', hora_reg = '$hora', userid = '$user_id' WHERE folio = '$folio_select'";
			} elseif ($calcular_patente) {
				$sql = "UPDATE patentes_pagados SET forma_pago = '$forma_pago', estatus = '$estatus_reg', fech_pago = '$fecha_banco_ymd', fech_reg = '$fecha', hora_reg = '$hora', userid = '$user_id' WHERE folio = '$folio_select'";
			}
			#echo "L337 SQL: $sql<br />";
			pg_query($sql);
		} else {  ### TODO EN ORDEN --> REGISTRAR PAGO
			$conciliacion = false;
			if ($calcular_urbano) {
				$sql = "UPDATE imp_pagados SET forma_pago = '$forma_pago', estatus = '$estatus_reg', fech_pago = '$fecha_banco_ymd', fech_reg = '$fecha', hora_reg = '$hora', userid = '$user_id' WHERE folio = '$folio_select'";
			} elseif ($calcular_rural) {
				$sql = "UPDATE imp_pagados_rural SET forma_pago = '$forma_pago', estatus = '$estatus_reg', fech_pago = '$fecha_banco_ymd', fech_reg = '$fecha', hora_reg = '$hora', userid = '$user_id' WHERE folio = '$folio_select'";
			} elseif ($calcular_patente) {
				$sql = "UPDATE patentes_pagados SET forma_pago = '$forma_pago', estatus = '$estatus_reg', fech_pago = '$fecha_banco_ymd', fech_reg = '$fecha', hora_reg = '$hora', userid = '$user_id' WHERE  folio = '$folio_select'";
			}
			#echo "L344 SQL: $sql<br />";
			pg_query($sql);

		}
		### REGISTRAR EL PAGO EN EL BANCO EN IMP_CONTROL_BANCO ###		
		$sql = "INSERT INTO imp_control_banco (fech_pago, nombre_banco, no_boleta_banco, monto_banco, concepto, id_item, gestion, modo_pago, folio, fech_reg, hora_reg, userid_reg)
              VALUES('$fecha_banco_ymd','$nombre_banco','$no_boleta_banco','$cuota_tabla','$concepto_temp','$id_item_temp','$gestion','$forma_pago','$folio_select','$fecha','$hora','$user_id')";
		#echo "SQL: $sql<br />";
		pg_query($sql);
		### SUPRIMIR PRELIQUIDACIONES DEL MISMO ITEM ###
		if ($calcular_urbano) {
			$sql = "UPDATE imp_pagados SET estatus = 'OBSOLETO' WHERE folio != '$folio_select' AND id_inmu = '$id_inmu' AND gestion = '$gestion' AND estatus = 'PRELIQUID'";
		} elseif ($calcular_rural) {
			$sql = "UPDATE imp_pagados_rural SET estatus = 'OBSOLETO' WHERE folio != '$folio_select' AND id_predio_rural = '$id_predio_rural' AND gestion = '$gestion' AND (estatus = '' OR estatus IS NULL)";
		} elseif ($calcular_patente) {
			$sql = "UPDATE patentes_pagados SET estatus = 'OBSOLETO' WHERE folio != '$folio_select' AND id_patente = '$id_patente' AND gestion = '$gestion' AND (estatus = '' OR estatus IS NULL)";
		}
		#echo "SQL: $sql<br />";
		pg_query($sql);
	}
} else {
	$imprimir_boleta_de_pago = false;
	$fecha_banco = $fecha2;
	$nombre_banco_form = "";
	$no_boleta_banco = "";
}
################################################################################
#------------------------ REGISTRAR PRELIQUIDACION SIIM -----------------------#
################################################################################	
$registro_valido_siim = $registro_cancelado_siim = false;
if ((isset($_POST["registro"])) and ($_POST["registro"] == "REGISTRAR") and (isset($_POST["preliquid_siim"]))) {
	$control = $_POST["folio_select"];
	$id = $_POST["id"];
	$id_inmu_siim = $_POST["id_inmu_siim"];
	$sistema_siim = $_POST["sistema_siim"];
	$fecha_banco = trim($_POST["fecha_banco"]);
	$fecha_banco_ymd = change_date_to_ymd_10char($fecha_banco);
	$nombre_banco = trim($_POST["nombre_banco"]);
	$nombre_banco_form = utf8_decode($nombre_banco);
	$no_boleta_banco = trim($_POST["no_boleta_banco"]);
	# $total_a_pagar = $_POST["total_a_pagar"];
	if (!check_fecha($fecha_banco, $dia_actual, $mes_actual, $ano_actual)) {
		$error_registro = true;
		$mensaje_de_error_registro = "Error: La fecha ingresada no es v�lida!";
	} elseif (($nombre_banco == "") or ($nombre_banco == "---------------")) {
		$error_registro = true;
		$mensaje_de_error_registro = "Error: Tiene que ingresar el nombre de la instituci�n donde cancel� el pago!";
	} elseif ($no_boleta_banco == "") {
		$error_registro = true;
		$mensaje_de_error_registro = "Error: Tiene que ingresar el n�mero de la boleta del pago!";
	} else
		$registro_valido_siim = true;
	#echo "L437 REGISTRO ES $registro_valido<br />";
	######################################
	#---------- REGISTRAR PAGO ----------#
	######################################
	if ($registro_valido_siim) {
		$ano_siim = substr($fecha_banco_ymd, 0, 4);
		$mes_siim = substr($fecha_banco_ymd, 5, 2);
		$dia_siim = substr($fecha_banco_ymd, 8, 2);
		$fech_pago = $ano_siim . $mes_siim . $dia_siim;
		if ($sistema_siim == "SMX") {
			$tabla_satliqin = "satliqin";
		} else {
			$tabla_satliqin = "satliqin2";
		}
		### ACTUALIZAR REGISTRO SATLIQIN ###
		$sql = "UPDATE $tabla_satliqin SET pagado = '$fech_pago' WHERE id = '$id' AND id_inmu = '$id_inmu_siim' AND gestion = '$gestion'";
		#echo "L449 SQL: $sql<br />";
		pg_query($sql);
		$mostrar_solo_preliquid = $registro_cancelado_siim = true;
	} else {
		$fecha_banco = $fecha2;
		$nombre_banco_form = "";
		$no_boleta_banco = "";
	}
}
################################################################################
#---------------- MOSTRAR SOLO PRELIQUIDACION CANCELADA DEL SIIM --------------#
################################################################################	
if ((isset($_POST["cancelado_siim"])) or ((isset($_POST["preliquid_siim"])) and (isset($_POST["registro"])) and ($_POST["registro"] == "REGISTRAR"))) {
	$id = $_POST["id"];
	$id_inmu_siim = $_POST["id_inmu_siim"];
	$sistema_siim = $_POST["sistema_siim"];
	if ($sistema_siim == "SMX") {
		$tabla_satliqin = "satliqin";
		$tabla_satnombr = "satnombr";
		$tabla_satinmus = "satinmus";
	} else {
		$tabla_satliqin = "satliqin2";
		$tabla_satnombr = "satnombr2";
		$tabla_satinmus = "satinmus2";
	}
	$mostrar_solo_preliquid = $registro_cancelado_siim = true;
}
################################################################################
#-------------------------- CHECK ESTATUS DEL PAGO ----------------------------#
################################################################################	
if (isset($_POST["estatus"])) {
	$estatus = $_POST["estatus"];
} else {
	if ($calcular_urbano) {
		$sql = "SELECT folio FROM imp_pagados WHERE id_inmu = '$id_inmu' AND gestion = '$gestion' AND estatus = 'CANCELADO'";
	} elseif ($calcular_transfer_urbano) {
		$sql = "SELECT folio FROM imp_transfer WHERE id_inmu = '$id_inmu' AND min_fech = '$min_fech' AND estatus = 'CANCELADO'";
	} elseif ($calcular_rural) {
		$sql = "SELECT folio FROM imp_pagados_rural WHERE id_predio_rural = '$id_predio_rural' AND gestion = '$gestion' AND estatus = 'CANCELADO'";
	} elseif ($calcular_transfer_rural) {
		$sql = "SELECT folio FROM imp_transfer_rural WHERE id_predio_rural = '$id_predio_rural' AND min_fech = '$min_fech' AND estatus = 'CANCELADO'";
	} elseif ($calcular_patente) {
		$sql = "SELECT folio FROM patentes_pagados WHERE id_patente = '$id_patente' AND gestion = '$gestion' AND estatus = 'CANCELADO'";
	}
	$check_estatus = pg_num_rows(pg_query($sql));
	if ($check_estatus == 1) {
		$estatus = "CANCELADO";
	} else {
		if ($calcular_urbano) {
			$sql = "SELECT folio FROM imp_pagados WHERE id_inmu = '$id_inmu' AND gestion = '$gestion' AND estatus = 'VALIDADO'";
		} elseif ($calcular_transfer_urbano) {
			$sql = "SELECT folio FROM imp_transfer WHERE id_inmu = '$id_inmu' AND min_fech = '$min_fech' AND estatus = 'VALIDADO'";
		} elseif ($calcular_rural) {
			$sql = "SELECT folio FROM imp_pagados_rural WHERE id_predio_rural = '$id_predio_rural' AND gestion = '$gestion' AND estatus = 'VALIDADO'";
		} elseif ($calcular_transfer_rural) {
			$sql = "SELECT folio FROM imp_transfer_rural WHERE id_predio_rural = '$id_predio_rural' AND min_fech = '$min_fech' AND estatus = 'VALIDADO'";
		} elseif ($calcular_patente) {
			$sql = "SELECT folio FROM patentes_pagados WHERE id_patente = '$id_patente' AND gestion = '$gestion' AND estatus = 'VALIDADO'";
		}
		$check_estatus = pg_num_rows(pg_query($sql));
		if ($check_estatus == 1) {
			$estatus = "VALIDADO";
		} else {
			if ($calcular_urbano) {
				$sql = "SELECT folio FROM imp_pagados WHERE id_inmu = '$id_inmu' AND gestion = '$gestion' AND estatus = 'PRESCRIP'";
			} elseif ($calcular_rural) {
				$sql = "SELECT folio FROM imp_pagados_rural WHERE id_predio_rural = '$id_predio_rural' AND gestion = '$gestion' AND estatus = 'PRESCRIP'";
			} elseif ($calcular_patente) {
				$sql = "SELECT folio FROM patentes_pagados WHERE id_patente = '$id_patente' AND gestion = '$gestion' AND estatus = 'PRESCRIP'";
			}
			$check_estatus = pg_num_rows(pg_query($sql));
			if ($check_estatus == 1) {
				$estatus = "PRESCRIP";
			} else {
				if ($calcular_urbano) {
					$sql = "SELECT folio FROM imp_pagados WHERE id_inmu = '$id_inmu' AND gestion = '$gestion' AND estatus = 'PARCIAL'";
				} elseif ($calcular_rural) {
					$sql = "SELECT folio FROM imp_pagados_rural WHERE id_predio_rural = '$id_predio_rural' AND gestion = '$gestion' AND estatus = 'PARCIAL'";
				} elseif ($calcular_patente) {
					$sql = "SELECT folio FROM patentes_pagados WHERE id_patente = '$id_patente' AND gestion = '$gestion' AND estatus = 'PARCIAL'";
				}
				$check_estatus = pg_num_rows(pg_query($sql));
				if ($check_estatus == 1) {
					$estatus = "PARCIAL";
				} else {
					if ($calcular_urbano) {
						$sql = "SELECT folio FROM imp_pagados WHERE id_inmu = '$id_inmu' AND gestion = '$gestion' AND estatus = 'RECTIFIC'";
					} elseif ($calcular_rural) {
						$sql = "SELECT folio FROM imp_pagados_rural WHERE id_predio_rural = '$id_predio_rural' AND gestion = '$gestion' AND estatus = 'RECTIFIC'";
					} elseif ($calcular_patente) {
						$sql = "SELECT folio FROM patentes_pagados WHERE id_patente = '$id_patente' AND gestion = '$gestion' AND estatus = 'RECTIFIC'";
					}
					$check_estatus = pg_num_rows(pg_query($sql));
					if ($check_estatus > 0) {
						$estatus = "RECTIFIC";
					} else {
						if ($registro_valido_siim) {
							$estatus = "CANCELADO";
						} else {
							$estatus = "PRELIQUID";
						}
					}
				}
			}
		}
		# $sql="SELECT folio FROM imp_pagados WHERE id_inmu = '$id_inmu' AND gestion = '$gestion' AND (estatus = 'CANCELADO' OR estatus = 'VALIDADO')";	 
		# $check_can_o_val = pg_num_rows(pg_query($sql));			
	}
}
#echo "L556 CHECK ESTATUS: $estatus, SOLO_PRELIQUID: $mostrar_solo_preliquid, REGISTRO CANCELADO SIIM: $registro_cancelado_siim<br />";
################################################################################
#------------------------- CHEQUEAR POR PAGOS BANCO ---------------------------#
################################################################################	

if ($calcular_urbano) {
	$sql = "SELECT id, fech_pago, nombre_banco, no_boleta_banco, monto_banco, modo_pago, folio, fech_reg FROM imp_control_banco WHERE concepto = 'LIQUIDACION IPBI' AND gestion = '$gestion' AND id_item = '$id_inmu' ORDER BY fech_pago";
} elseif ($calcular_transfer_urbano) {
	$sql = "SELECT id, fech_pago, nombre_banco, no_boleta_banco, monto_banco, modo_pago, folio, fech_reg FROM imp_control_banco WHERE concepto = 'TRANSFER URBANO' AND gestion = '$gestion' AND id_item = '$id_inmu' ORDER BY fech_pago";
} elseif ($calcular_rural) {
	$sql = "SELECT id, fech_pago, nombre_banco, no_boleta_banco, monto_banco, modo_pago, folio, fech_reg FROM imp_control_banco WHERE concepto = 'LIQUIDACION IPA' AND gestion = '$gestion' AND id_item = '$id_predio_rural' ORDER BY fech_pago";
} elseif ($calcular_transfer_rural) {
	$sql = "SELECT id, fech_pago, nombre_banco, no_boleta_banco, monto_banco, modo_pago, folio, fech_reg FROM imp_control_banco WHERE concepto = 'TRANSFER RURAL' AND gestion = '$gestion' AND id_item = '$id_predio_rural' ORDER BY fech_pago";
} elseif ($calcular_patente) {
	$sql = "SELECT id, fech_pago, nombre_banco, no_boleta_banco, monto_banco, modo_pago, folio, fech_reg FROM imp_control_banco WHERE concepto = 'LIQUIDACION PAT' AND gestion = '$gestion' AND id_item = '$id_patente' ORDER BY fech_pago";
}
$check_control_banco = pg_num_rows(pg_query($sql));
#echo "L593 SQL: $sql, $id_inmu, $check_control_banco<br />";
$monto_conban_total = $deuda_pagada_sin_repform = 0;
if ($check_control_banco > 0) {
	$registro_banco_existe = true;
	$result = pg_query($sql);
	$i = $j = 0;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		foreach ($line as $col_value) {
			if ($i == 0) {
				$id_conban[$j] = $col_value;
			} elseif ($i == 1) {
				$fech_pago_orig[$j] = $col_value;
				$fech_pago_conban[$j] = change_date($fech_pago_orig[$j]);
			} elseif ($i == 2) {
				$nombre_banco_conban[$j] = $col_value;
			} elseif ($i == 3) {
				$no_boleta_banco_conban[$j] = $col_value;
			} elseif ($i == 4) {
				$monto_conban[$j] = $col_value;
			} elseif ($i == 5) {
				$modo_pago_conban[$j] = $col_value;
			} elseif ($i == 6) {
				$folio_conban[$j] = $col_value;
			} else {
				$fech_reg_orig[$j] = $col_value;
				$fech_reg_conban[$j] = change_date($fech_reg_orig[$j]);
				$monto_conban_total = $monto_conban_total + $monto_conban[$j];
				### MONTO	CANCELADO SIN REP. FORM Y SALDO A FAVOR
				$sql2 = "SELECT deuda_bs, rep_form, sal_favor, total_a_pagar FROM $tabla_liq WHERE folio = '$folio_conban[$j]'";
				$result2 = pg_query($sql2);
				$info2 = pg_fetch_array($result2, null, PGSQL_ASSOC);
				$rep_form_temp = $info2['rep_form'];
				$total_a_pagar_temp = $info2['total_a_pagar'];
				pg_free_result($result2);
				$deuda_pagada_sin_repform = $deuda_pagada_sin_repform + $total_a_pagar_temp - $rep_form_temp;
				$i = -1;
			}
			$i++;
		}
		$j++;
	} # END_OF_WHILE	
	pg_free_result($result);
	$no_de_registros_banco = $j;
} else {
	$registro_banco_existe = false;
}

$exen_id_tab = $condon_id_tab = 0;
################################################################################
#--------------------------- APLICAR VALOR EN LIBROS --------------------------#
################################################################################
$solo_empresa = $valor_en_libros_ingresado = false;
if (($calcular_urbano) or ($calcular_rural)) {
	if ((isset($_POST["valor_en_libros"])) or (isset($_POST["solo_empresa"]))) {
		if (isset($_POST["solo_empresa"])) {
			$valor_lib = $valor_en_libros = $_POST["solo_empresa"];
		} else {
			$valor_lib = $valor_en_libros = $_POST["valor_en_libros"];
		}
		if ($valor_lib > 0) {
			$solo_empresa = $valor_en_libros_ingresado = true;
			#   $imp_neto_real = $imp_neto;
			$sql = "SELECT * FROM imp_escala_imp WHERE gestion = '$gestion' AND exced <= '$valor_lib' ORDER BY mas_porc DESC LIMIT 1";
			$result_vl = pg_query($sql);
			$info_vl = pg_fetch_array($result_vl, null, PGSQL_ASSOC);
			$cuota_fija = $info_vl['cuota'];
			$tp_exen = $info_vl['mas_porc'];
			$monto_exen = $info_vl['exced'];
			#echo "CUOTA_FIJA: $cuota_fija,$tp_exen,$monto_exen<br>";				 
			########################################
			#------------ BASE IMPONIBLE ----------#
			########################################	
			$base_imp = $valor_lib - $monto_exen;
		}
		#echo "L522 VALOR_LIB: $valor_lib<br>";
	} else {
		$valor_lib = 0;
	}
}
################################################################################
#----------------- CHEQUEAR SI SE PUEDE APLICAR PRESCRIPCION ------------------#
################################################################################	
$prescripcion = false;
if (($gestion < $ano_actual - 5) and (!$mostrar_solo_preliquid) and ($calcular_urbano or $calcular_rural or $calcular_patente)) {
	$gestiones_canceladas = true;
	### DEFINIR SI YA SE PUEDE CANCELAR LA GESTION ANTERIOR ###
	$fecha_venc_gest_ant = imp_get_fecha_venc_1st($ano_actual - 1);
	if ($fecha_venc_gest_ant == -1) {
		$ultima_gestion_para_cobrar = $ano_actual - 2;
	} else
		$ultima_gestion_para_cobrar = $ano_actual - 1;
	#echo "L882 ESTATUS: $estatus, GESTION: $gestion, ULTIMA GESTION PARA COBRAR: $ultima_gestion_para_cobrar<br />";	 
	### CHEQUEAR LAS ULTIMAS 5 GESTIONES ###	
	$gestion_check = $ultima_gestion_para_cobrar - 4;
	while ($gestion_check <= $ultima_gestion_para_cobrar) {
		$sql = "SELECT folio FROM $tabla_liq WHERE $columna_id = '$id_item_temp' AND gestion = '$gestion_check' AND (estatus = 'CANCELADO' OR estatus = 'VALIDADO')";
		$check_pagado = pg_num_rows(pg_query($sql));
		if ($check_pagado == 0) {
			$gestiones_canceladas = false;
		}
		#echo "L885 GESTION_CHECK: $gestion_check, PAGADO: $check_pagado<br />";			
		$gestion_check++;
	}
	if ($gestiones_canceladas) {
		$prescripcion = true;
	}
}
################################################################################
#---------------------- EXENCION APLICADA /NO APLICADA ------------------------#
################################################################################	
$error_exencion = false;
if (((isset($_POST["exen_selected"])) and ($_POST["exen_selected"] > 0)) or (($exen_id_tab > 0) and (!isset($_POST["exen"])))) {
	$exencion_seleccionada = true;
	if (isset($_POST["exen_selected"])) {
		$exen_select = $_POST["exen_selected"];
	} else
		$exen_select = $exen_id_tab;
	$sql = "SELECT descripcion FROM imp_exenciones WHERE numero = '$exen_select'";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$desc_select = utf8_decode($info['descripcion']);
	#$porc_select = $info['porcentaje'];
	pg_free_result($result);
	#echo "L666 EXENCION APLICADA, EXEN_SELECT: $exen_select, $desc_select<br />";
	if (($desc_select == "PRESCRIPCION DE IMPUESTOS") and (!$prescripcion)) {
		$error_exencion = true;
		$exencion_seleccionada = false;
		$exen_select = $porc_select = 0;
	}
} else {
	$exencion_seleccionada = false;
	$exen_select = $porc_select = 0;
	#echo "L675 EXENCION NO APLICADA, PORC_SELECT: $porc_select<br>";	 
}
################################################################################
#---------------- CONDONACION DE MULTAS APLICADA /NO APLICADA -----------------#
################################################################################	
if (((isset($_POST["condon_selected"])) and ($_POST["condon_selected"] > 0)) or (($condon_id_tab > 0) and (!isset($_POST["condon"])))) {
	$condonacion_seleccionada = true;
	if (isset($_POST["condon_selected"])) {
		$condon_select = $_POST["condon_selected"];
	} else
		$condon_select = $condon_id_tab;
	$sql = "SELECT descripcion, porcentaje FROM imp_condonaciones WHERE numero = '$condon_select'";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$condon_desc_select = utf8_decode($info['descripcion']);
	$condon_porc_select = $info['porcentaje'];
	pg_free_result($result);
	#echo "CONDONACION APLICADA, CONDON_SELECT: $condon_select, PORC_SELECT: $porc_select<br>";	 
} else {
	$condonacion_seleccionada = false;
	$condon_select = $condon_porc_select = 0;
	#echo "EXENCION NO APLICADA, PORC_SELECT: $porc_select<br>";	 
}
################################################################################
#--------------- LEER DATOS DE PRE-LIQUIDACION(ES) SI EXISTE(N) ---------------#
################################################################################	
$preliquid_exists = false;
if ($calcular_urbano) {
	#$sql = "SELECT folio, concepto, fech_imp, cuota, fech_venc FROM imp_control_preliquid WHERE concepto = 'LIQUIDACION IPBI' AND id_item = '$id_inmu' AND gestion = '$gestion' AND (estatus = '' OR estatus IS NULL) ORDER BY folio";
	$sql = "SELECT folio, fech_imp, total_a_pagar, fech_imp_venc FROM imp_pagados WHERE id_inmu = '$id_inmu' AND gestion = '$gestion' AND estatus = 'PRELIQUID' ORDER BY folio";
	$concepto_temp = "LIQUIDACION IPBI";
} elseif ($calcular_transfer_urbano) {
	$sql = "SELECT folio, fech_imp, total_a_pagar, fech_imp_venc FROM imp_transfer WHERE id_inmu = '$id_inmu' AND min_fech = '$min_fech' AND estatus = 'PRELIQUID' ORDER BY folio";
	$concepto_temp = "TRANSFER URBANO";
} elseif ($calcular_rural) {
	$sql = "SELECT folio, fech_imp, total_a_pagar, fech_imp_venc FROM imp_pagados_rural WHERE id_predio_rural = '$id_predio_rural' AND gestion = '$gestion' AND estatus = 'PRELIQUID' ORDER BY folio";
	$concepto_temp = "LIQUIDACION IPA";
} elseif ($calcular_transfer_rural) {
	$sql = "SELECT folio, fech_imp, total_a_pagar, fech_imp_venc FROM imp_transfer_rural WHERE id_predio_rural = '$id_predio_rural' AND min_fech = '$min_fech' AND estatus = 'PRELIQUID' ORDER BY folio";
	$concepto_temp = "TRANSFER RURAL";
} elseif ($calcular_patente) {
	$sql = "SELECT folio, fech_imp, total_a_pagar, fech_imp_venc FROM patentes_pagados WHERE id_patente = '$id_patente' AND gestion = '$gestion' AND estatus = 'PRELIQUID' ORDER BY folio";
	$concepto_temp = "LIQUIDACION PAT";
}
$no_de_preliquid = pg_num_rows(pg_query($sql));
#echo "NO. DE PRELIQUIDs: $no_de_preliquid, SQL: $sql<br>";
if ($no_de_preliquid > 0) {
	$preliquid_exists = true;
	$result = pg_query($sql);
	$i = $j = 0;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		foreach ($line as $col_value) {
			if ($i == 0) {
				$folio_array[$j] = $col_value;
			} elseif ($i == 1) {
				$fech_imp_preliquid[$j] = $col_value;
			} elseif ($i == 2) {
				$cuota_preliquid[$j] = $col_value;
			} else {
				$fech_venc_preliquid[$j] = $col_value;
				$concepto[$j] = $concepto_temp;
				$i = -1;
			}
			$i++;
		}
		$j++;
	}
	pg_free_result($result);
}
################################################################################
#--------------------------- CONCILIACION EXISTE ------------------------------#
################################################################################	
$conciliacion_exists = false;

################################################################################
#---------------------- MOSTRAR BOLETA DE PAGO TRANSFERENCIA ------------------#
################################################################################	
if (((isset($_POST["submit"])) and ($_POST["submit"] == "Registrar Transferencia"))) {
	$imprimir_boleta_de_pago = true;
	$mostrar_solo_preliquid = true;
	#echo "L876 ESTATUS: $estatus, $calcular_transfer_urbano<br />";
}
################################################################################
#----------------- MOSTRAR BOLETA DE PAGO TRANSFERENCIA ANTIGUA ---------------#
################################################################################	
if ((isset($_GET["tfurb"])) or (isset($_GET["tfrur"]))) {
	$imprimir_boleta_de_pago = false;
	$mostrar_solo_preliquid = true;
}
################################################################################
#----------------------------- CALCULAR MONTO ---------------------------------#
################################################################################	
if ((!$mostrar_solo_preliquid) and (!$registrar_preliq) and ($estatus != "VALIDADO")) {
	#echo "L882 INICIO BLOQUE CALCULAR MONTO, ESTATUS: $estatus<br />";
	include "siicat_impuestos_calcular_monto.php";
	#echo "L908 FIN BLOQUE CALCULAR MONTO, ESTATUS: $estatus, TRIB_OMIT: $trib_omit, TOTAL_A_PAGAR: $total_a_pagar, DESCUENTO: $descuento, MULTA INCUMP: $multa_incump_ufv, $valor_lib, $valor_t,MULTAS BS: $multas_total_bs<br />";
}
################################################################################
#--- LEER REGISTROS CANCELADOS, VALIDADOS O PRESCRITOS DE TABLA IMP_PAGADOS ---#
################################################################################	
if (($estatus == "VALIDADO") or ($estatus == "CANCELADO") or ($estatus == "PRESCRIP") or ($mostrar_solo_preliquid)) {
	$nota_fecha_venc_preliquid = false;
	$mostrar_fecha_de_pago = true;
	#echo "L892 CANCELADO, VALIDADO O PRESCRIP<br />";	 
	if (($calcular_urbano) and (!$mostrar_solo_preliquid)) {
		$sql = "SELECT * FROM imp_pagados WHERE id_inmu = '$id_inmu' AND gestion = '$gestion' AND (estatus = 'VALIDADO' OR estatus = 'CANCELADO' OR estatus = 'PRESCRIP')";
		include "siicat_impuestos_pagados_leer_datos.php";
		#echo "L896 ESTATUS: $estatus, TRIB_OMIT: $trib_omit, DESCUENTO: $descuento, MULTA INCUMP: $multa_incump_ufv<br />";					
		if ($forma_pago == "SIIM") {
			$interes_bs = $interes;
			$multa_incump_bs = $multa_incump_ufv;
			$mul_mora_bs = $mul_mora;
			$multas_total_bs = $multa_incump_bs + $mul_mora_bs;
			$mantenimiento_de_valor = $mant_val_bs = $deuda_bs - $monto_imp - $interes_bs - $multas_total_bs;
			$multa_incump_bs_en_ufv = $multa_incump_bs / $ufv_venc;
			if (($multa_incump_bs_en_ufv > 0) and ($multa_incump_bs_en_ufv < 50)) {
				$nota_condonacion = true;
				$texto_condonacion = "Se aplic� la reducci�n de multa seg�n el articulo 156 del c�digo tributario en el SIIM!";
			}
		}
	} elseif (($calcular_urbano) and ($mostrar_solo_preliquid) and (!$registro_cancelado_siim)) {
		$sql = "SELECT * FROM imp_pagados WHERE id_inmu = '$id_inmu' AND gestion = '$gestion' AND folio = '$folio_select'";
		include "siicat_impuestos_pagados_leer_datos.php";
		#echo "L912 SQL: $sql<br />";			  
	} elseif ($calcular_rural) {
		$sql = "SELECT * FROM imp_pagados_rural WHERE id_predio_rural = '$id_predio_rural' AND gestion = '$gestion' AND (estatus = 'VALIDADO' OR estatus = 'CANCELADO' OR estatus = 'PRESCRIP')";
		include "siicat_impuestos_pagados_rural_leer_datos.php";
	} elseif ($calcular_patente) {
		$sql = "SELECT * FROM patentes_pagados WHERE id_patente = '$id_patente' AND gestion = '$gestion' AND (estatus = 'VALIDADO' OR estatus = 'CANCELADO' OR estatus = 'PRESCRIP')";
		#echo "L904 SQL: $sql<br />";			
		include "siicat_patentes_pagados_leer_datos.php";
	} elseif (($mostrar_solo_preliquid) and ($registro_cancelado_siim)) {
		$sql = "SELECT * FROM $tabla_satliqin WHERE id = '$id' AND id_inmu = '$id_inmu_siim' AND gestion = '$gestion'";
		$sql2 = "SELECT * FROM $tabla_satnombr WHERE id = '$id'";
		$sql3 = "SELECT * FROM $tabla_satinmus WHERE id = '$id' AND id_inmu = '$id_inmu_siim'";
		#echo "L910 SQL: $sql<br />";		      
		include "siicat_impuestos_pagados_siim_leer_datos.php";
	} elseif ($calcular_transfer_urbano) {
		$sql = "SELECT * FROM imp_transfer WHERE folio = '$folio_select'";
		include "siicat_imp_transfer_leer_datos.php";
		#echo "L953 SQL: $sql<br />";		 
	} elseif ($calcular_transfer_rural) {
		$sql = "SELECT * FROM imp_transfer_rural WHERE folio = '$folio_select'";
		include "siicat_imp_transfer_rural_leer_datos.php";
		#echo "L919 SQL: $sql<br />";		 
	}
	if ($forma_pago == "SIIM") {
		$sistema = "SIIM";
	} else
		$sistema = "SIICAT";
	if ($estatus == "VALIDADO") {
		if ((!isset($monto_dias)) or ($monto_dias == "")) {
			$monto_dias = 0;
		}
		if ($exencion == "") {
			$exencion = 0;
		}
		if ((!isset($descont)) or ($descont == "")) {
			$descont = 0;
		}
		if ($monto_imp == "") {
			$monto_imp = 0;
		}
		$via_mat = $val_m2_terr = $sup_terr = $fact_incl = $valor_t_subt = $fact_min = $fact_agu = $fact_luz = $fact_tel = $fact_alc = $valor_t = "-";
		$valor_t = $valor_vi = $valor_total = $valor_en_libros_texto = $tp_exen = $base_imp = $cuota_fija = $esc_imp = $base_imp_s_ex = "-";
		$fecha_venc_texto = "-";
		$monto_det = $total_a_pagar;
		$descuento = $cantidad_de_dias = $ufv_fecha_venc = 0;
		$trib_omit = $tasa_interes = $interes = $interes_red = $multa_incump_ufv = $multa_omision_pago = $multa_omision_registro = 0;
		$multas_total = $monto_condonacion = $monto_condonacion_neg = $ufv_actual = $deuda_trib = 0;
		$rep_form = $pagos_ant = $saldo_a_favor = $pago_efectivo = $sal_prox_gest = $pago_credito = $saldo = 0;
		$monto_a_pagar = $monto_det;
		$username = $hora = "-";
		$fecha2 = change_date($fech_pago);
		$fecha_venc_preliquid = "-";
		### DATOS PARA BOLETA TRANSFER  ###	
	}
	if ((($estatus == "CANCELADO") or ($estatus == "VALIDADO")) and (($calcular_transfer_urbano) or ($calcular_transfer_rural))) {
		#echo "L989 CHECK<br />";	 
		$user_id = $userid_imp;
		$hora = $hora_imp;
		$fecha2 = change_date($fech_imp);
		$titular = get_contrib_nombre($tit_1id);
		$titular2 = get_contrib_nombre($tit_2id);
		$tit_tipo = get_contrib_tipo($tit_1id);
		$ci_nit = get_contrib_ci($tit_1id);
		$ci_nit2 = get_contrib_ci($tit_2id);
		$dom_ciu = get_contrib_dom($tit_1id);
		$dom_dir = get_contrib_dom($tit_1id);
		$cod_pad = get_contrib_pmc($tit_1id);

		$comprador = get_contrib_nombre($id_comp);
		$comprador2 = get_contrib_nombre($id_comp2);
		$comp_tipo = get_contrib_tipo($id_comp);
		$comp_ci_texto = get_contrib_ci($id_comp);
		$comp_ci_texto2 = get_contrib_ci($id_comp2);
		$dom_dir_comp = get_contrib_dom($id_comp);
		$cod_pmc_comp = get_contrib_pmc($id_comp);
		$tipo_inmu_texto = get_tipo_inmu_from_id_inmu($id_inmu);
		$direccion = get_direccion_from_id_inmu($id_inmu);
		$cambio_usd = imp_getcoti($min_fech, "usd");
		if ($min_mon == "bs") {
			$valor_min = $min_val;
			$valor_usd = "-";
		} else {
			$valor_min = ROUND($cambio_usd * $min_val, 0);
			$valor_usd = $min_val;
		}
		if (($valor_min > $valor_total) and ($valor_min > $valor_lib)) {
			$base_imp_seleccion = "VALOR MINUTA";
		} elseif (($valor_total > $valor_min) and ($valor_total > $valor_lib)) {
			$base_imp_seleccion = "VALOR CATASTRAL";
		} elseif (($valor_lib > $valor_min) and ($valor_lib > $valor_total)) {
			$base_imp_seleccion = "VALOR EN LIBROS";
		}
		$ufv_fecha_venc = imp_getcoti($min_fech, "ufv");
		### TASA TAPR_UFV ###
		$tasa_taprufv = imp_tasa_taprufv($fech_imp);
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
		$tasa_interes = $tasa_taprufv + 3;
		#echo "L1034 INTERES: $interes, MULTA_INCUMP: $multa_incump_ufv, TASA UFV: $tasa_taprufv<br />";
		$interes_bs = ROUND($interes * $ufv_fecha_venc, 0);
		$multa_omision_pago = 0;
		$multas_total_bs = ROUND(($multa_incump_ufv + $multa_omision_pago - $monto_condonacion) * $ufv_fecha_venc, 0);
		$mant_val_bs = $deuda_bs - $monto_imp - $interes_bs - $multas_total_bs;
		$fecha_venc_texto = change_date($fech_venc);
		if (($fech_imp_venc == "") or ($fech_imp_venc == "1900-01-01")) {
			$fecha_venc_preliquid_texto = "-";
		} else
			$fecha_venc_preliquid_texto = change_date($fech_imp_venc);
		$fech_pago_texto = change_date($fech_pago);
		$nota_condonacion = $nota_exencion = $area_predio_manual = "";
		if ($forma_pago == "SIIM") {
			$sistema = "SIIM";
		} else
			$sistema = "SIICAT";
		$fecha_venc = $fech_venc;
	} elseif (($estatus == "CANCELADO") and (!$mostrar_solo_preliquid)) {
		#echo "L1012 CHECK<br />";		 
		$user_id = $userid_imp;
		$hora = $hora_imp;
		$fecha2 = change_date($fech_imp);
		$fecha_venc_texto = change_date($fech_venc);
		if (($fech_imp_venc == "") or ($fech_imp_venc == "1900-01-01")) {
			$fecha_venc_preliquid_texto = "-";
		} else
			$fecha_venc_preliquid_texto = change_date($fech_imp_venc);
		$fech_pago_texto = change_date($fech_pago);
		if ($forma_pago == "SIIM") {
			$sistema = "SIIM";
		} else
			$sistema = "SIICAT";
	} elseif ($estatus == "PRESCRIP") {
		$fecha_venc_preliquid = "-";
		$nota_exencion = true;
		$texto_exencion = "Se aplica una rebaja de 100% por motivo de PRESCRIPCION.";
	} elseif ($mostrar_solo_preliquid) {
		#echo "L963 CHECK<br />";		 
		if ($registro_cancelado_siim) {
			if ($persona == 2) {
				$titular = $razon_social;
				$ci_nit = $ruc;
			} else {
				$titular = $nombre . " " . $paterno . " " . $materno;
				$ci_nit = $documento;
			}
			$dom_ciu = $barrio_contrib;
			$dom_dir = "C/" . $nombrecall_contrib;
			$cod_pad = (int) $id;
			if ($barrio != "") {
				$direccion = $barrio . ", C/" . $nombrecall;
			} else
				$direccion = "C/" . $nombrecall;
			$via_mat = get_siim_via_mat($mat_vias);
			$sup_terr = $superficie;
			$fact_agu = $agua;
			$fact_luz = $luz;
			$fact_alc = $alcantari;
			$fact_tel = $telefono;
			$fact_min = "1";
			if ($sup_const > 0) {
				$tp_viv_texto = "S/D";
			} else
				$tp_viv_texto = "-";
			$fecha2 = $fech_pago;
			$hora = "";
			$user_id = $usuario;
			$fech_imp_venc_texto = $fech_venc_temp;
			$int_porc = $fact_incl = $valor_t_subt = $tp_exen = $cuota_fija = $esc_imp = $cantidad_de_dias = $trib_omit = $monto_condonacion_neg = $deuda_trib = $pagos_ant = $saldo_a_favor = $exencion = $sal_prox_gest = "-";
		} else {
			$titular = get_contrib_nombre($tit_1id);
			$titular2 = get_contrib_nombre($tit_2id);
			$ci_nit = get_contrib_ci($tit_1id);
			$ci_nit2 = get_contrib_ci($tit_2id);
			$dom_ciu = get_contrib_dom($tit_1id);
			$dom_dir = get_contrib_dom($tit_1id);
			$cod_pad = get_contrib_pmc($tit_1id);
			$tipo_inmu = $tp_inmu;
			$direccion = get_direccion_from_id_inmu($id_inmu);
			$val_m2_const = "-";

			
		}
		$ben_zona = $zona;
		$area_predio_manual = $area_edif_manual = false;
		$edi_tipo[0] = $tp_viv_texto;
		$edi_tipo[1] = $edi_tipo[2] = $edi_tipo[3] = $edi_tipo[4] = $edi_tipo[5] = "-";
		$calidad_const[0] = $val_m2_const;
		$calidad_const[1] = $calidad_const[2] = $calidad_const[3] = $calidad_const[4] = $calidad_const[5] = "-";
		$area_edif[0] = $sup_const;
		$area_edif[1] = $area_edif[2] = $area_edif[3] = $area_edif[4] = $area_edif[5] = "-";
		$factor_deprec[0] = $deprec;
		$factor_deprec[1] = $factor_deprec[2] = $factor_deprec[3] = $factor_deprec[4] = $factor_deprec[5] = "-";
		$avaluo_edif[0] = $valor_vi;
		$avaluo_edif[1] = $avaluo_edif[2] = $avaluo_edif[3] = $avaluo_edif[4] = $avaluo_edif[5] = "-";
		$nota_exencion = "";
		$fecha_venc_texto = $fecha_venc = $fech_venc_temp;
		$ufv_fecha_venc = $ufv_venc;
		$nota_condonacion = "";
		$tasa_interes = $int_porc;
		$multa_omision_pago = $mul_mora;
		$fecha_venc_preliquid = $fecha_venc_preliquid_texto = $fech_imp_venc_texto;
	} else {
		$username = utf8_decode(get_username2($userid));
		$hora = $hora_reg;
		$fecha2 = change_date($fech_pago);
		$fecha_venc_preliquid = "-";
	}
} else {
	$mostrar_fecha_de_pago = false;
	$sistema = "SIICAT";
}

if ((!isset($_POST["imp_neto"])) and (!$mostrar_solo_preliquid)) {
	$imp_neto_post = $imp_neto;
}
#echo "L874 ESTATUS: $estatus, TRIB_OMIT: $trib_omit, TOTAL_A_PAGAR: $total_a_pagar, DESCUENTO: $descuento, MULTA INCUMP: $multa_incump_ufv<br />";
################################################################################
#-------------------------- VALOR_EN_LIBROS_TEXTO -----------------------------#
################################################################################	
if (($calcular_urbano) or ($calcular_rural)) {
	if ($valor_lib > $valor_total) {
		$fecha_balance = $gestion . "-12-31";
	} else {
		$fecha_balance = "-";
	}
	$valor_en_libros_texto = $valor_lib;
}
################################################################################
#------------ SOLO MOSTRAR IMPRIMIR SELLO CUANDO COINCIDE EXENCION ------------#
################################################################################	
if ($exen_id_tab != $exen_select) {
	$sello = false;
}
################################################################################
#-------------- SOLO MOSTRAR CONDONACIONES SI PASO LA FECHA VENC --------------#
################################################################################	
if ($fecha_venc >= $fecha) {
	$show_condonacion = false;
} else {
	$show_condonacion = true;
}
################################################################################
#------------------------------- PLAN DE PAGO ------------------------------#
################################################################################	
if ((isset($_POST["submit"])) and ($_POST["submit"] == "Liquidar") and (isset($_POST["forma_pago"])) and ($_POST["forma_pago"] == "PLAN")) {
	$mostrar_botones = false;
}
################################################################################
#------------------------------- BOLETA IMPRESA ------------------------------#
################################################################################	
if ($boleta) {
	$sello = true;
	#echo "BOLETA ES TRUE<br>";
	$pago_al_contado = true;
	$sql = "SELECT por_form, monto, cuota, exen_id, fech_imp, hora, usuario, control, no_orden FROM imp_pagados WHERE gestion = '$gestion' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	#	 $total_a_pagar = $info['por_form'] + $info['monto'];
	$cuota_sello = $info['cuota'];
	#	 $exen_id = $info['exen_id'];
#	 $fech_imp = $info['fech_imp'];
#	 $hora = $info['hora'];	
#	 $usuario = $info['usuario'];
	$control_sello = $info['control'];
	$no_orden_sello = $info['no_orden'];
	pg_free_result($result);

	$no_cuota = 0;

	#echo "GESTION: $gestion, CODIGO: $cod_cat, FORMA_PAGO: $forma_pago, EXEN_ID: $exen_id<br>";
} else
	$exen_id = 0;

################################################################################
#------------------------ MENU SELECCION DE EXENCIONES ------------------------#
################################################################################		
$sql = "SELECT numero, descripcion FROM imp_exenciones ORDER BY numero";
$check_exenciones = pg_num_rows(pg_query($sql));
$exen_numero[0] = 0;
$exen[0] = "---------------";
$porcentaje[0] = 0;
if ($check_exenciones > 0) {
	$result = pg_query($sql);
	$i = 0;
	$j = 1;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		foreach ($line as $col_value) {
			if ($i == 0) {
				$exen_numero[$j] = $col_value;
			} else {
				$exen[$j] = $descripcion = utf8_decode($col_value);
				#$exen[$j] = $descripcion." - ".$exen_porcentaje[$j]." %";						
				$i = -1;
			}
			$i++;
		}
		$j++;
	} # END_OF_WHILE	
	pg_free_result($result);
}
################################################################################
#----------------------- MENU SELECCION DE CONDONACIONES ----------------------#
################################################################################	
$sql = "SELECT numero, descripcion, porcentaje FROM imp_condonaciones ORDER BY numero";
$check_condonaciones = pg_num_rows(pg_query($sql));
$condon_numero[0] = 0;
$condon[0] = "---------------";
$porcentaje[0] = 0;
if ($check_condonaciones > 0) {
	$result = pg_query($sql);
	$i = 0;
	$j = 1;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		foreach ($line as $col_value) {
			if ($i == 0) {
				$condon_numero[$j] = $col_value;
			} elseif ($i == 1) {
				$descripcion = utf8_decode($col_value);
			} else {
				$porcentaje[$j] = $col_value;
				#			$condon[$j] = $descripcion." - ".$porcentaje[$j]." %";
				$condon[$j] = $descripcion;
				$i = -1;
			}
			$i++;
		}
		$j++;
	} # END_OF_WHILE	
	pg_free_result($result);
}
################################################################################
#----------------------- MENU SELECCION DE LUGAR DE PAGO ----------------------#
################################################################################	
$sql = "SELECT numero, banco FROM imp_bancos ORDER BY numero";
$check_bancos = pg_num_rows(pg_query($sql));
$banco_numero[0] = 0;
$banco_nombre[0] = "---------------";
if ($check_bancos > 0) {
	$result = pg_query($sql);
	$i = 0;
	$j = 1;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		foreach ($line as $col_value) {
			if ($i == 0) {
				$banco_numero[$j] = $col_value;
			} else {
				$banco_nombre[$j] = utf8_decode($col_value);
				$i = -1;
			}
			$i++;
		}
		$j++;
	} # END_OF_WHILE	
	pg_free_result($result);
}
################################################################################
#------------------------- IMPRIMIR PRE_LIQUIDACION ---------------------------#
################################################################################	
if ((isset($_POST["preliquid"])) and ($_POST["preliquid"] == "PRE-LIQUIDACION")) {
	$preliquid = true;
	### PREPARAR DATOS ###	 
	$usuario = get_userid($session_id);
	$forma_pago = "CONTADO";
	$fech_imp = $fecha;
	$hora_imp = $hora;
	$userid_imp = $user_id;
	$fech_imp_venc = $fecha_venc_preliquid;
	$estatus = "PRELIQUID";
	### REGISTRAR IMPRESION EN TABLAS IMP_PAGADOS, IMP_PAGADOS_RURAL O PATENTES_PAGADOS ###	
	if ($calcular_urbano) {
		#$concepto_temp = "LIQUIDACION IPBI";
		#$id_item_temp = $id_inmu;
		if ($valor_vi > 0) {
			$tp_inmu = "CASA";
		} else
			$tp_inmu = "TERRENO";
		if ($edi_tipo_total == "No def.") {
			$edi_tipo_total_tabla = "NO DEF";
		} else
			$edi_tipo_total_tabla = $edi_tipo_total;
		if ($edi_tipo_total_tabla == "Galp�n") {
			$edi_tipo_total_tabla = "GAL";
		}
		#$sql = "SELECT id_inmu FROM imp_pagados WHERE id_inmu = '$id_inmu' AND gestion = '$gestion'";
		#$check_imppag = pg_num_rows(pg_query($sql));
		/*    if ($check_imppag == 0) {  */
		# $mant_valor = 0;

		$sql = "INSERT INTO imp_pagados (folio, cod_geo, id_inmu, gestion, forma_pago, tp_inmu, tit_1id, zona, via_mat, 
			           sup_terr, val_tab, fact_incl, valor_t_subt, fact_agu, fact_alc, fact_luz, fact_tel, fact_min, factor, 
								 valor_t, tp_viv, valcm2, sup_const, ant_const, fd_an, valor_vi, valor_total, 
								 valor_lib, tp_exen, base_imp, cuota_fija, esc_imp, monto_exen, base_imp_s_ex, 
				         monto_det, fech_venc, descont, exen_id, exencion, monto_imp, dias_venc, ufv_venc, 
								 trib_omit, mul_mora, mul_incum, condon_id, condonacion, int_porc, interes, 
								 deuda_trib, ufv_actual, deuda_bs, rep_form, pagos_ant, sal_favor, total_a_pagar, sal_prox_gest, 
								 credito, saldo, fech_imp, hora_imp, userid_imp, fech_imp_venc, estatus)
				        VALUES ('$folio','$cod_geo','$id_inmu','$gestion','$forma_pago','$tp_inmu','$tit_1id','$zona','$via_mat',
								 '$sup_terr','$val_m2_terr','$fact_incl','$valor_t_subt','$fact_agu','$fact_alc','$fact_luz','$fact_tel','$fact_min','$factor',
								 '$valor_t','$edi_tipo_total_tabla','$calidad_const_tabla','$edi_area','$ant_const','$factor_depreciacion','$valor_vi','$valor_total',
								 '$valor_lib','$tp_exen','$base_imp','$cuota_fija','$esc_imp','$monto_exen','$base_imp_s_ex',
								 '$monto_det','$fecha_venc','$descuento','$exen_select','$exencion','$monto_imp','$cantidad_de_dias','$ufv_fecha_venc',
								 '$trib_omit','$multa_omision_pago','$multa_incump_ufv','$condon_select','$monto_condonacion','$tasa_interes','$interes',
								 '$deuda_trib','$ufv_actual','$deuda_bs','$rep_form','$pagos_ant','$saldo_a_favor','$total_a_pagar','$sal_prox_gest',
								 '$credito','$saldo','$fech_imp','$hora_imp','$userid_imp','$fech_imp_venc','$estatus')";
		pg_query($sql);
	} elseif ($calcular_transfer_urbano) {
		$cat_val = $valor_cat;
		$min_val = $valor_min;
		### PREPARAR DATOS ###	 
		$forma_pago = "";
		$condon_select = 0;
		$fech_imp = $fecha;
		$hora_imp = $hora;
		$userid_imp = $user_id;
		$fech_imp_venc = $fecha_venc_preliquid;
		$sql = "INSERT INTO imp_transfer (folio, cod_geo, id_inmu, forma_pago, tit_1id, min_num, not_nom,
				         not_num, not_cls, not_exp, min_val, min_mon, min_fech, id_comp, modo_trans,
								 valor_t, valor_vi, valor_total, valor_lib, base_imp, 
				         monto_det, fech_venc, descont, exen_id, exencion, monto_imp, dias_venc, ufv_venc, 
								 trib_omit, mul_mora, mul_incum, condon_id, condonacion, int_porc, interes, 
								 deuda_trib, ufv_actual, deuda_bs, rep_form, pagos_ant, sal_favor, total_a_pagar, sal_prox_gest, 
								 credito, saldo, fech_imp, hora_imp, userid_imp, fech_imp_venc, estatus)
				        VALUES ('$folio','$cod_geo','$id_inmu','$forma_pago','$tit_1id','$min_num','$not_nom',
								 '$not_num','$not_cls','$not_exp','$min_val','$min_mon','$min_fech','$id_comp','$modo_trans',
                 '$valor_t','$valor_vi','$valor_total','$valor_lib','$base_imp',
								 '$monto_det','$fecha_venc','$descuento','$exen_select','$exencion','$monto_imp','$cantidad_de_dias','$ufv_fecha_venc',
								 '$trib_omit','$multa_omision_pago','$multa_incump_ufv','$condon_select','$monto_condonacion','$tasa_interes','$interes',
								 '$deuda_trib','$ufv_actual','$deuda_bs','$rep_form','$pagos_ant','$saldo_a_favor','$total_a_pagar','$sal_prox_gest',
								 '$credito','$saldo','$fech_imp','$hora_imp','$userid_imp','$fech_imp_venc','$estatus')";
		pg_query($sql);
	} elseif ($calcular_rural) {

		$sql = "INSERT INTO imp_pagados_rural (folio, id_predio_rural, gestion, forma_pago, tit_1id, zona, superf, sup_noaprov,
			           valor_ha, valor_t, valor_vi, valor_mej, valor_total, valor_lib, alicuota,
				         monto_det, fech_venc, descont, exen_id, exencion, monto_imp, dias_venc, ufv_venc, 
								 trib_omit, mul_mora, mul_incum, condon_id, condonacion, int_porc, interes, 
								 deuda_trib, ufv_actual, deuda_bs, rep_form, pagos_ant, sal_favor, total_a_pagar, sal_prox_gest, 
								 credito, saldo, fech_imp, hora_imp, userid_imp, fech_imp_venc, estatus)
				         VALUES ('$folio','$id_predio_rural','$gestion','$forma_pago','$tit_1id','$zona','$superf','$sup_no_aprov',
								 '$valor_ha','$valor_t','$valor_vi','$valor_mej','$valor_total','$valor_lib','$alicuota',
								 '$monto_det','$fecha_venc','$descuento','$exen_select','$exencion','$monto_imp','$cantidad_de_dias','$ufv_fecha_venc',
								 '$trib_omit','$multa_omision_pago','$multa_incump_ufv','$condon_select','$monto_condonacion','$tasa_interes','$interes',
								 '$deuda_trib','$ufv_actual','$deuda_bs','$rep_form','$pagos_ant','$saldo_a_favor','$total_a_pagar','$sal_prox_gest',
								 '$credito','$saldo','$fech_imp','$hora_imp','$userid_imp','$fech_imp_venc','$estatus')";

		pg_query($sql);
	
	} elseif ($calcular_transfer_rural) {
		$cat_val = $valor_cat;
		$min_val = $valor_min;
		### PREPARAR DATOS ###	 
		$forma_pago = "";
		$condon_select = 0;
		$fech_imp = $fecha;
		$hora_imp = $hora;
		$userid_imp = $user_id;
		$fech_imp_venc = $fecha_venc_preliquid;
		if ($sup_no_aprov == "") {
			$sup_no_aprov = 0;
		}
		if ($valor_ha == "") {
			$valor_ha = 0;
		}
		if ($valor_t == "") {
			$valor_t = 0;
		}
		if ($valor_vi == "") {
			$valor_vi = 0;
		}
		if ($valor_total == "") {
			$valor_total = 0;
		}
		$sql = "INSERT INTO imp_transfer_rural (folio, id_predio_rural, forma_pago, tit_1id, min_num, not_nom,
				         not_num, not_cls, not_exp, min_val, min_mon, min_fech, id_comp, modo_trans,
								 superf, sup_noaprov, valor_ha, valor_t, valor_vi, valor_total, valor_lib, base_imp, 
				         monto_det, fech_venc, descont, exen_id, exencion, monto_imp, dias_venc, ufv_venc, 
								 trib_omit, mul_mora, mul_incum, condon_id, condonacion, int_porc, interes, 
								 deuda_trib, ufv_actual, deuda_bs, rep_form, pagos_ant, sal_favor, total_a_pagar, sal_prox_gest, 
								 credito, saldo, fech_imp, hora_imp, userid_imp, fech_imp_venc, estatus)
				        VALUES ('$folio','$id_predio_rural','$forma_pago','$tit_1id','$min_num','$not_nom',
								 '$not_num','$not_cls','$not_exp','$min_val','$min_mon','$min_fech','$id_comp','$modo_trans',
                 '$sup_pred','$sup_no_aprov','$valor_ha','$valor_t','$valor_vi','$valor_total','$valor_lib','$base_imp',
								 '$monto_det','$fecha_venc','$descuento','$exen_select','$exencion','$monto_imp','$cantidad_de_dias','$ufv_fecha_venc',
								 '$trib_omit','$multa_omision_pago','$multa_incump_ufv','$condon_select','$monto_condonacion','$tasa_interes','$interes',
								 '$deuda_trib','$ufv_actual','$deuda_bs','$rep_form','$pagos_ant','$saldo_a_favor','$total_a_pagar','$sal_prox_gest',
								 '$credito','$saldo','$fech_imp','$hora_imp','$userid_imp','$fech_imp_venc','$estatus')";
		#echo "L1186 SQL: $sql, VALOR_LIB: $valor_lib<br />";		
		pg_query($sql);
	} elseif ($calcular_patente) {
		#$concepto_temp = "LIQUIDACION PAT";
		#$id_item_temp = $id_patente;	
		$sql = "SELECT id_patente FROM patentes_pagados WHERE id_patente = '$id_patente' AND gestion = '$gestion'";
		$check_patpag = pg_num_rows(pg_query($sql));
		#if ($check_patpag == 0) {
		# VALORES CALCULADOS EN siicat_impuestos_calcular_monto lineas 464
		$sql = "INSERT INTO patentes_pagados (folio, id_patente, gestion, forma_pago, tit_1id, id_rubro, pat_max, dias_act, monto_dias, superf, zona, porc, 
				         monto_det, fech_venc, descont, exen_id, exencion, monto_imp, dias_venc, ufv_venc, 
								 trib_omit, mul_mora, mul_incum, condon_id, condonacion, int_porc, interes, 
								 deuda_trib, ufv_actual, deuda_bs, rep_form, pagos_ant, sal_favor, total_a_pagar, sal_prox_gest, 
								 credito, saldo, fech_imp, hora_imp, userid_imp, fech_imp_venc, estatus)
			           VALUES ('$folio','$id_patente','$gestion','$forma_pago','$id_contrib1','$id_rubro','$pat_max','$dias_act','$monto_dias','$act_sup','$act_zona','$factor_zs',
								 '$monto_det','$fecha_venc','$descuento','$exen_select','$exencion','$monto_imp','$cantidad_de_dias','$ufv_fecha_venc',
								 '$trib_omit','$multa_omision_pago','$multa_incump_ufv','$condon_select','$monto_condonacion','$tasa_interes','$interes',
								 '$deuda_trib','$ufv_actual','$deuda_bs','$rep_form','$pagos_ant','$saldo_a_favor','$total_a_pagar','$sal_prox_gest',
								 '$credito','$saldo','$fech_imp','$hora_imp','$userid_imp','$fech_imp_venc','$estatus')";
		#echo "L977 SQL: $sql<br />";
		pg_query($sql);

	}
 

} else
	$preliquid = false;
################################################################################
#----------------------------- LEER SALDO A FAVOR -----------------------------#
################################################################################	
$sql = "SELECT monto, gestion_traspaso FROM imp_saldo_a_favor WHERE concepto = '$concepto_temp' AND id_item = '$id_item_temp' AND gestion = '$gestion'";
$check_saldo = pg_num_rows(pg_query($sql));
if ($check_saldo == 0) {
	$saldo_a_favor_exists = false;
	$saldo_a_favor_actual = 0;
	$gestion_traspaso = "";
} else {
	$saldo_a_favor_exists = true;
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$saldo_a_favor_actual = $info['monto'];
	$gestion_traspaso = $info['gestion_traspaso'];
	pg_free_result($result);
}
################################################################################
#-------------------------- IMPRIMIR BOLETA DE PAGO ---------------------------#
################################################################################	
if ((isset($_POST["imprimir"])) and ($_POST["imprimir"] == "IMPRIMIR BOLETA")) {
	$boleta_de_pago = true;
	$forma_pago = $_POST["forma_pago"];
	#   $exen_id = $_POST["exen_id"];
	$exen_id = $_POST["exen_selected"];
	$descont_exen = $_POST["descont_exen"];
	$no_control = $_POST["no_control"];
	$total_a_pagar = $_POST["total_a_pagar"];
	#echo "IMPRIMIR BOLETA --> FORMA_PAGO: $forma_pago, EXEN_ID: $exen_id, DESCONT_EXEN: $descont_exen<br>";	  
	if ((!check_int($no_control)) or ($no_control == "")) {
		$error = true;
		$mensaje_de_error = "Error: Tiene que ingresar el n�mero de la boleta!";
		$boleta_de_pago = false;
	} else {
		$control = change_numero_to_8char($no_control);
		$sql = "SELECT no_orden FROM imp_control WHERE control = '$control' AND observ = 'SELLO'";
		$check_control = pg_num_rows(pg_query($sql));
		if ($check_control > 0) {
			$result = pg_query($sql);
			$info_orden = pg_fetch_array($result, null, PGSQL_ASSOC);
			$no_orden = $info_orden['no_orden'];
			pg_free_result($result);
			$error = true;
			$mensaje_de_error = "Error: Ya se registr� un pago con ese n�mero de boleta (con n�mero de �rden: $no_orden)";
			$boleta_de_pago = false;
		}

	}
} else
	$boleta_de_pago = false;
$total_a_pagar = ROUND($total_a_pagar, 0);


########################################
#---- MOSTRAR MONEDA SEGUN SISTEMA ----#
########################################	
if ($sistema == "SIIM") {
	$moneda = "Bs.";
} else
	$moneda = "UFV";
########################################
#----- MOSTRAR CODIGO PROVISIONAL -----#
########################################	
if ((isset($cod_uv)) and ($cod_uv >= 900)) {
	$codigo = "PROVISIONAL";
} else
	$codigo = "CATASTRAL";
########################################
#------- TRANSFER URBANO/RURAL --------#
########################################	
if ($calcular_transfer_urbano) {
	$casilla_trans_objeto = "INMUEBLES";
	$casilla_trans_numero = "NO. INM.: $id_inmu";
	$codcat_transfer = "$cod_geo/$cod_cat";
} elseif ($calcular_transfer_rural) {
	$casilla_trans_objeto = "PROP. RURAL";
	$casilla_trans_numero = "NO. PROP.: $id_predio_rural";
	$codcat_transfer = "$cod_cat";
} elseif ($calcular_patente) {
	if (!isset($cod_uv)) {
		$cod_uv = $cod_man = $cod_pred = 0;
	}
}
################################################################################
#--------------------- CHEQUEAR SI EL PREDIO ESTA ACTIVO ----------------------#
################################################################################	
$sql = "SELECT activo FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check = pg_num_rows(pg_query($sql));
if ($check == 0) {
	$predio_activo = 1;
} else {
	$result = pg_query($sql);
	$act = pg_fetch_array($result, null, PGSQL_ASSOC);
	$predio_activo = $act['activo'];
}
########################################
#------ IMPRIMIR BOLETA DE PAGO -------#
########################################
if ($imprimir_boleta_de_pago) {
	$preliquid = true;
}
################################################################################
#------------------------- FORMULARIO PARA IMPRIMIR ---------------------------#
################################################################################	
if ($boleta_de_pago) {
	echo "<td>\n";
	echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"840px\" height=\"1100px\">\n";   # 3 Columnas
	echo "<tr height=\"40px\">\n";
	echo "<td width=\"15%\">\n";  #Col. 1
	if (isset($_POST["id_inmu"])) {
		echo "&nbsp&nbsp <a href=\"index.php?mod=62&inmu=$id_inmu&gestion=$gestion&db=$db&id=$session_id\">\n";
	} elseif (isset($_POST["id_patente"])) {
		echo "&nbsp&nbsp <a href=\"index.php?mod=108&id_pat=$id_patente&gestion=$gestion&db=$db&id=$session_id\">\n";
	}
	echo "<img border='0' src='http://$server/siicat/graphics/boton_atras.png'></a>\n";
	echo "</td>\n";
	echo "<td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n";
	echo "Boleta de Pago\n";
	echo "</td>\n";
	echo "<td width=\"25%\"> &nbsp</td>\n";   #Col. 3 			 
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td valign=\"top\" colspan=\"3\">\n";   #Col. 1 	 
	include "siicat_impuestos_generar_boleta_de_pago.php";
	echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/boleta$cod_cat.html\" id=\"mapserver\" width=\"840px\" height=\"1100px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
	echo "</iframe>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</td>\n";
	################################################################################
#------------------------------ PRE-LIQUIDACION -------------------------------#
################################################################################		 
} elseif ($preliquid) {
	echo "<td>\n";
	echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"840px\" height=\"1100px\">\n";   # 3 Columnas
	echo "<tr height=\"40px\">\n";
	echo "<td width=\"15%\">\n";  #Col. 1 
	if ($calcular_urbano) {
		if ($imprimir_boleta_de_pago) {
			echo "&nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&calc&db=$db&id=$session_id#tab-10\">\n";
		} else {
			echo "&nbsp&nbsp <a href=\"index.php?mod=62&inmu=$id_inmu&gestion=$gestion&db=$db&id=$session_id#tab-2\">\n";
		}
	} elseif ($calcular_transfer_urbano) {
		if ($imprimir_boleta_de_pago) {
			echo "&nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&db=$db&id=$session_id#tab-9\">\n";
		} else {
			echo "&nbsp&nbsp <a href=\"index.php?mod=67&inmu=$id_inmu&db=$db&id=$session_id\">\n";
		}
	} elseif ($calcular_rural) {
		echo "&nbsp&nbsp <a href=\"index.php?mod=52&idpr=$id_predio_rural&gestion=$gestion&db=$db&id=$session_id\">\n";
	} elseif ($calcular_transfer_rural) {
		echo "&nbsp&nbsp <a href=\"index.php?mod=67&idpr=$id_predio_rural&db=$db&id=$session_id\">\n";
	} elseif ($calcular_patente) {
		echo "&nbsp&nbsp <a href=\"index.php?mod=108&id_pat=$id_patente&gestion=$gestion&db=$db&id=$session_id\">\n";
	}
	#   echo "            <img border='1' src='http://$server/$folder/graphics/boton_atras.png' width='35' height='35'></a>\n"; 
	echo "<img border='0' src='http://$server/siicat/graphics/boton_atras.png'></a>\n";
	echo "</td>\n";
	echo "<td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n";
	if ($calcular_urbano) {
		if ($imprimir_boleta_de_pago) {
			echo "Boleta de pago IPBI\n";
		} else {
			echo "Pre-Liquidaci�n IPBI\n";
		}
	} elseif ($calcular_transfer_urbano) {
		if ($imprimir_boleta_de_pago) {
			echo "Boleta de pago Transferencia Urbana\n";
		} else {
			echo "Pre-Liquidaci�n Transferencia Urbana\n";
		}
	} elseif ($calcular_rural) {
		if ($imprimir_boleta_de_pago) {
			echo "Boleta de pago IPA\n";
		} else {
			echo "Pre-Liquidaci�n IPA\n";
		}
	} elseif ($calcular_transfer_rural) {
		if ($imprimir_boleta_de_pago) {
			echo "Boleta de pago Transferencia Rural\n";
		} else {
			echo "Pre-Liquidaci�n Transferencia Rural\n";
		}
	} elseif ($calcular_patente) {
		if ($imprimir_boleta_de_pago) {
			echo "Boleta de pago Impuestos a los Patentes\n";
		} else {
			echo "Pre-Liquidaci�n Impuestos a los Patentes\n";
		}
	}
	echo "</td>\n";
	echo "<td width=\"25%\"> &nbsp</td>\n";   #Col. 3 			 
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td valign=\"top\" colspan=\"3\">\n";   #Col. 1 	
	$imprimir_preliq = true;
	if ($calcular_urbano) {
		if (($form_ipbi == 1) or (!$imprimir_boleta_de_pago)) {
			include "siicat_impuestos_generar_preliquidacion1.php";
			echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/preliq$cod_cat.html\" id=\"mapserver\" width=\"840px\" height=\"550px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
		} elseif ($form_ipbi == 2) {
			include "siicat_impuestos_generar_preliquidacion2.php";
			echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/preliq$cod_cat.html\" id=\"mapserver\" width=\"840px\" height=\"1100px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
		} else {
			include "siicat_impuestos_generar_preliquidacion3.php";
			echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/preliq$cod_cat.html\" id=\"mapserver\" width=\"840px\" height=\"1100px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
		}
		echo "</iframe>\n";
	} elseif (($calcular_transfer_urbano) or ($calcular_transfer_rural)) {

		if (($form_trans == 1) or (!$imprimir_boleta_de_pago)) {
			include "siicat_impuestos_generar_preliquid_transfer1.php";
			echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/preliq$cod_cat.html\" id=\"mapserver\" width=\"840px\" height=\"550px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
		} elseif ($form_trans == 2) {
			include "siicat_impuestos_generar_preliquid_transfer2.php";
			echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/preliq$cod_cat.html\" id=\"mapserver\" width=\"840px\" height=\"1100px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
		} else {
			include "igm_impuestos_generar_preliquid_transfer3.php";
			echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/boleta_trans_$cod_cat.html\" id=\"mapserver\" width=\"840px\" height=\"600px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
		}
	} elseif ($calcular_rural) {
		if (($form_ipa == 1) or (!$imprimir_boleta_de_pago)) {
			include "siicat_rural_generar_preliquid1.php";
			echo "            <iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/preliq$cod_cat.html\" id=\"mapserver\" width=\"840px\" height=\"550px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
		} elseif ($form_ipa == 2) {
			include "siicat_rural_generar_preliquid2.php";
			echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/preliq$cod_cat.html\" id=\"mapserver\" width=\"840px\" height=\"1100px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
		} else {
			include "siicat_impuestos_generar_preliquidacion3.php";
			echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/preliq$cod_cat.html\" id=\"mapserver\" width=\"840px\" height=\"1100px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
		}
		echo "</iframe>\n";
	} elseif ($calcular_patente) {
		if (($form_pat == 1) or (!$imprimir_boleta_de_pago)) {
			include "siicat_patentes_generar_preliquidacion1.php";
			echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/preliq$id_patente.html\" id=\"mapserver\" width=\"840px\" height=\"550px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
		} elseif ($form_pat == 2) {
			include "siicat_patentes_generar_preliquidacion2.php";
			echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/preliq$id_patente.html\" id=\"mapserver\" width=\"840px\" height=\"1100px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
		} else {
			include "siicat_patentes_generar_preliquidacion3.php";
			echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/preliq$id_patente.html\" id=\"mapserver\" width=\"840px\" height=\"1100px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
		}
		echo "</iframe>\n";
	}
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</td>\n";
} else {
	################################################################################
#--------------- SELECCION CONTADO/CONVALIDAR/VER PRELIQUIDACION --------------#
################################################################################		
	echo "<td valign=\"top\">\n";
	echo "<table border=\"0\" align=\"center\" valign=\"top\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas	 
	# Fila 1
	echo "<tr height=\"20px\">\n";
	echo "<td width=\"10%\">\n";   #Col. 1 	
	if (isset($_GET["ref"])) {
		$ref = $_GET["ref"];
		echo "&nbsp&nbsp <a href=\"index.php?mod=$ref&db=$db&id=$session_id\">\n";
	} else {
		if ($calcular_urbano) {
			if (($ano_actual - $gestion) > 5) {
				echo "&nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&calc&show&db=$db&id=$session_id#tab-10\">\n";
			} else {
				echo "&nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&calc&db=$db&id=$session_id#tab-10\">\n";
			}
		} elseif ($calcular_transfer_urbano) {
			if (isset($_GET["tfurb"])) {
				echo "&nbsp&nbsp <a href='javascript:history.back()'>\n";
			} else {
				echo "&nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&db=$db&id=$session_id#tab-9\">\n";
			}
		} elseif ($calcular_rural) {
			echo "&nbsp&nbsp <a href=\"index.php?mod=45&idpr=$id_predio_rural&calc&db=$db&id=$session_id#tab-10\">\n";
		} elseif ($calcular_transfer_rural) {
			echo "&nbsp&nbsp <a href=\"index.php?mod=45&idpr=$id_predio_rural&db=$db&id=$session_id#tab-9\">\n";
		} elseif ($calcular_patente) {
			echo "&nbsp&nbsp <a href=\"index.php?mod=105&id_pat=$id_patente&db=$db&id=$session_id#tab-5\">\n";
		}
	}
	echo "<img border='0' src='http://$server/siicat/graphics/boton_atras.png'></a>\n";
	echo "</td>\n";
	echo "<td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n";
	if ($calcular_urbano) {
		echo "Liquidaci�n de Impuestos IPBI\n";
	} elseif ($calcular_rural) {
		echo "Liquidaci�n de IPA\n";
	} elseif ($calcular_patente) {
		echo "Liquidaci�n de Patentes\n";
	} elseif (($calcular_transfer_urbano) or ($calcular_transfer_rural)) {
		echo "Impuestos a la Transferencia\n";
	}
	echo "</td>\n";
	echo "<td width=\"30%\"> &nbsp</td>\n";   #Col. 3 			 
	echo "</tr>\n";
	 
	if (!$mostrar_solo_preliquid) {
		echo "<tr>\n";
		echo "<td>&nbsp</td>\n";   #Col. 1 	
		echo "<td align=\"center\" class=\"bodyText\">\n";
		if ($calcular_urbano) {
			echo "<b>Codigo Inmueble: $cod_cat &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Gesti�n: $gestion &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Impuesto Neto: $imp_neto_post Bs.</b>\n";
		} elseif ($calcular_transfer_urbano) {
			echo "<b>Codigo Inmueble: $cod_cat &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Impuesto a la Transferencia: $imp_neto_post Bs.</b>\n";
		} elseif ($calcular_rural) {
			echo "<b>Codigo Propiedad: $cod_cat &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Gesti�n: $gestion &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Impuesto Neto: $imp_neto_post Bs.</b>\n";
		} elseif ($calcular_transfer_rural) {
			echo "<b>Codigo Propiedad: $cod_cat &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Impuesto a la Transferencia: $imp_neto_post Bs.</b>\n";
		} elseif ($calcular_patente) {
			echo "<b>No. Patente: $cod_pat &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Gesti�n: $gestion &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Impuesto Neto: $imp_neto_post Bs.</b>\n";
		}
		echo "</td>\n";
		echo "<td>&nbsp</td>\n";   #Col. 3	 		 
		echo "</tr>\n";
	}
	# Fila 3
	echo "<tr>\n";
	echo "<td valign=\"top\" height=\"180\" colspan=\"3\" class=\"bodyText\">\n";  #Col. 1+2+3	
	echo "<div id=\"tabs\">\n";
	echo "<ul>\n";
	if (($estatus == "VALIDADO") or ($estatus == "CANCELADO") or ($estatus == "PRESCRIP")) {
		echo "<li><a href=\"#tab-1\"><span>DETALLE DEL PAGO</span></a></li>\n";
	} else {
		echo "<li><a href=\"#tab-1\"><span>BOLETA DE RESUMEN</span></a></li>\n";
	}
	if (($estatus != "VALIDADO") and ($estatus != "CANCELADO") and ($estatus != "PRESCRIP") and (!$mostrar_solo_preliquid)) {
		echo "<li><a href=\"#tab-2\"><span>AL CONTADO</span></a></li>\n";
	}
	if (($estatus != "VALIDADO") and ($estatus != "CANCELADO") and ($estatus != "PRESCRIP") and (!$registro_banco_existe) and (!$mostrar_solo_preliquid) and ($nivel > 1)) {
		echo "<li><a href=\"#tab-3\"><span>CONVALIDAR</span></a></li>\n";
	}
	if ((($registro_banco_existe) or ($estatus == "VALIDADO") or ($estatus == "CANCELADO") or ($estatus == "PRESCRIP")) and (!$calcular_transfer_urbano) and (!$calcular_transfer_rural) and (!$registro_cancelado_siim) and (!$predio_activo == 0) and ($nivel > 1)) {
		echo "<li><a href=\"#tab-4\"><span>RECTIFICAR</span></a></li>\n";
	}
	if ((isset($_POST["preliquid_siim"])) and (!$mostrar_solo_preliquid)) {
		echo "<li><a href=\"#tab-6\"><span>REGISTRAR PAGO SIIM</span></a></li>\n";
	}

	echo "</ul>\n";
	echo "<div id=\"tab-1\">\n";
	include "siicat_impuestos_detalle_de_pago.php";
	echo "</div>\n";
	if (($estatus != "VALIDADO") and ($estatus != "CANCELADO") and ($estatus != "PRESCRIP") and (!$mostrar_solo_preliquid)) {
		echo "<div id=\"tab-2\">\n";
		include "siicat_impuestos_al_contado.php";
		echo "</div>\n";
	}
	if (($estatus != "VALIDADO") and ($estatus != "CANCELADO") and ($estatus != "PRESCRIP") and (!$registro_banco_existe) and (!$mostrar_solo_preliquid) and ($nivel > 1)) {
		echo "<div id=\"tab-3\">\n";
		include "siicat_impuestos_convalidar_pago.php";
		echo "</div>\n";
	}
	if ((($registro_banco_existe) or ($estatus == "VALIDADO") or ($estatus == "CANCELADO") or ($estatus == "PRESCRIP")) and (!$calcular_transfer_urbano) and (!$calcular_transfer_rural) and (!$registro_cancelado_siim) and ($nivel > 1)) {
		echo "<div id=\"tab-4\">\n";
		include "siicat_impuestos_rectificar_pago.php";
		echo "</div>\n";
	}
	if ((isset($_POST["preliquid_siim"])) and (!$mostrar_solo_preliquid)) {
		echo "<div id=\"tab-6\">\n";
		include "siicat_impuestos_registrar_pago_siim.php";
		echo "</div>\n";
	}
	echo "</div>\n";
	echo "</td>\n";
	echo "</tr>\n";

	if ($mostrar_botones) {
		echo "<tr>\n";
		echo "<td colspan=\"3\"> &nbsp</td>\n";   #Col. 1-3 	 
		echo "</tr>\n";
		if (($forma_pago == "CONTADO") or ($forma_pago == "PLAN")) {
			########################################
			#---------- VALOR EN LIBROS  ----------#
			########################################

		}
	}

	if ($convalidar_pago) {
	}
	echo "<tr height=\"100%\">\n";
	echo "<td colspan=\"3\"> &nbsp</td>\n";   #Col. 1-3 	 
	echo "</tr>\n";
	echo "</table>\n";
	echo "</td>\n";

}
?>