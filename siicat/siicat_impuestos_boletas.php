<?php

$sistema = $_POST["sistema"];
$gestion = $_POST["gestion"];
$boleta = $_POST["boleta"];
$texto_exencion = "";

################################################################################
#----------- VERIFICAR SI HAY UN PAGO CON EL CODIGO EN ESA GESTION ------------#
################################################################################
if ($boleta == "Boleta") {
	$sql = "SELECT id_inmu FROM imp_pagados WHERE gestion = '$gestion' AND forma_pago != '' AND forma_pago != 'COPIA'
	       AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
	$check_pago = pg_num_rows(pg_query($sql));
	if ($check_pago == 0) {
		$gest_tmp = $gestion + 1;
		$gestion_tmp = $gest_tmp . "-01-01";
		$sql = "SELECT fecha_cambio, valor_ant FROM cambios WHERE variable = 'cod_cat' AND fecha_cambio > '$gestion_tmp'
		AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' ORDER BY fecha_cambio ASC LIMIT 1";
		$check_cambios = pg_num_rows(pg_query($sql));
		if (($check_cambios > 0) and ($boleta == "Boleta")) {
			$cambio_codigo = true;
			$result_cambios = pg_query($sql);
			$info_cambios = pg_fetch_array($result_cambios, null, PGSQL_ASSOC);
			$fecha_cambio = $info_cambios['fecha_cambio'];
			$anio_cambio = substr($fecha_cambio, 0, 4);
			$ultimo_ano_pagado = substr($fecha_cambio, 0, 4) - 1;
			$fecha_cambio = change_date($fecha_cambio);
			$valor_ant = $info_cambios['valor_ant'];
			pg_free_result($result_cambios);
			$mostrar_boton_rectificar = true;
			$anio_cambio_temp = $anio_cambio - 1;
			if (($cambio_codigo) and ($gestion <= $anio_cambio_temp)) {
				$cod_cat = $valor_ant;
			}
		}
	}
}
################################################################################
#------------------- PAGO REALIZADO EN EL PROGRAMA SIIM -----------------------#
################################################################################
if ($sistema == "SIIM") {
	$siim_id = $_POST["siim_id"];
	$id_inmu_siim = $_POST["id_inmu_siim"];
	### DATOS DE SATNOMBR
	$sql = "SELECT paterno, materno, nombre, nombrecall FROM satnombr WHERE id = '$siim_id'";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$paterno = utf8_decode($info['paterno']);
	$materno = utf8_decode($info['materno']);
	$nombre = utf8_decode($info['nombre']);
	$titular = $paterno . " " . $materno . " " . $nombre;
	$direccion = "AV./CALLE " . $info['nombrecall'];
	$pmc = $siim_id . "/" . $id_inmu;
	pg_free_result($result);
	### DATOS DE SATINMUS
	$sql = "SELECT superficie FROM satinmus WHERE id = '$siim_id' AND id_inmu = '$id_inmu_siim'";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$sup_terr = $info['superficie'];
	pg_free_result($result);
	### DATOS DE SATLIQIN 
	$sql = "SELECT * FROM satliqin WHERE id = '$siim_id' AND id_inmu = '$id_inmu_siim' AND gestion = '$gestion'";
	$result = pg_query($sql);
	$info_imp = pg_fetch_array($result, null, PGSQL_ASSOC);
	$forma_pago = "CONTADO";
	$tp_inmu = trim($info_imp['tp_inmu']);
	if ($tp_inmu == 1) {
		$tp_inmu = "CASA";
	} else
		$tp_inmu = "TERRENO";
	$val_m2_terr = $info_imp['val_tab'];
	$factor = $info_imp['factor'];
	$avaluo_terr = $info_imp['valor_t'];
	$val_m2_const = $info_imp['valcm2'];
	$antig = $info_imp['fd_an'];
	$avaluo_const = $info_imp['valor_vi'];
	$avaluo_total = $info_imp['base_imp'];
	$imp_neto = $info_imp['imp_neto'];
	$sal_favor = $info_imp['sal_favor'];
	$cotido = $info_imp['cotido'];
	$cotiufv = $info_imp['cotiufv'];
	$descuento = $info_imp['d10'];
	$mant_val = $info_imp['mant_val'];
	$interes = $info_imp['interes'];
	$multa_mora = $info_imp['mul_mora'];
	$multa_incum = $info_imp['deb_for'];
	$multa_admin = $info_imp['san_adm'];
	$por_form = $info_imp['por_form'];
	$monto = $info_imp['monto'];
	$descont = $info_imp['descont'];
	$credito = $info_imp['credito'];
	$fecha_imp = $info_imp['fech_imp'];
	$ano = substr($fecha_imp, 0, 4);
	$mes = substr($fecha_imp, 4, 2);
	$dia = substr($fecha_imp, 6, 2);
	$fecha_imp = $dia . "/" . $mes . "/" . $ano;
	$fecha_venc = $info_imp['fech_venc'];
	$ano = substr($fecha_venc, 0, 4);
	$mes = substr($fecha_venc, 4, 2);
	$dia = substr($fecha_venc, 6, 2);
	$fecha_venc = $dia . "/" . $mes . "/" . $ano;
	#$cuota = $info_imp['cuota']; 
	$cuota = $info_imp['monto'];
	$usuario = $info_imp['usuario'];
	$control = $info_imp['control'];
	$total_a_pagar = $cuota;
} else {
	include "siicat_info_inmu_leer_datos.php";
	include "siicat_info_predio_leer_datos.php";

	$titular = get_contrib_nombre2($tit_1id);
	$titular2 = get_contrib_nombre2($tit_2id);
	$pmc = get_contrib_pmc($tit_1id);
	#$direccion = get_direccion_from_id_inmu ($id_inmu);
	$direccion = $dir_nom;
	$tit_1ci = get_contrib_ci($tit_1id);
	if ($tit_1ci == "") {
		$tit_1ci_texto = "-";
	} else {
		$tit_1ci_texto = $tit_1ci;
		$tit_2ci = get_contrib_ci($tit_2id);
		if ($tit_2ci == "") {
			$tit_2ci_texto = "-";
		} else {
			$tit_2ci_texto = $tit_2ci;
		}
	}

	$dom_dpto = "-";
	$dom_ciu = "-";
	$dom_dir = "-";
	$dir_bloq = "-";
	$dir_piso = "-";
	$dir_apto = "-";

	$sql = "SELECT * FROM imp_pagados WHERE gestion = '$gestion' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
	$result = pg_query($sql);
	$info_imp = pg_fetch_array($result, null, PGSQL_ASSOC);
	########################################
	#------- LEER DATOS DE LA TABLA  ------#
	########################################	
	include "siicat_impuestos_pagados.php";
	pg_free_result($result);
	if ($exen_id > 0) {
		$sql = "SELECT * FROM imp_exenciones WHERE numero = '$exen_id'";
		$result = pg_query($sql);
		$info_exen = pg_fetch_array($result, null, PGSQL_ASSOC);
		$ley = utf8_decode($info_exen['ley']);
		$fecha_exen = change_date($info_exen['fecha']);
		$descripcion = utf8_decode($info_exen['descripcion']);
		$porcentaje = utf8_decode($info_exen['porcentaje']);
		pg_free_result($result);
		$texto_exencion = "Se aplic� una rebaja de $porcentaje % para $descripcion seg�n $ley de $fecha_exen";
	}
	################################################################################
	#-------------------------- DEFINIR ZONA DEL PREDIO ---------------------------#
	################################################################################
	$zona = get_zona_brujula($cod_cat, $centro_del_pueblo_para_zonas_x, $centro_del_pueblo_para_zonas_y);
}
if ($forma_pago == "PLAN") {
	$forma_pago = "PLAN DE PAGO";
} elseif ($forma_pago == "") {
	$forma_pago = "-";
}

if ($forma_pago == "VALIDADO") {
	$texto_exencion = "Por validación de pago posiblemente no se aplica Mant. Valor, Interes y Deberes Formales.";
}
#$forma_pago = "CONTADO";
################################################################################
#------------------------------- TASA DESCUENTO -------------------------------#
################################################################################	
$sql = "SELECT descuento FROM imp_base";
$result = pg_query($sql);
$info_base = pg_fetch_array($result, null, PGSQL_ASSOC);
$tasa_descuento = $info_base['descuento'];
pg_free_result($result);
################################################################################
#-------------------------------- PLAN DE PAGO --------------------------------#
################################################################################	
if ((isset($_POST["forma_pago"])) and ($_POST["forma_pago"] == "PLAN")) {
	$no_cuota = $_POST["no_cuota"];
	$sql = "SELECT monto_cuota, fech_venc, fech_pago FROM imp_plan_de_pago WHERE gestion = '$gestion' AND no_cuota = '$no_cuota'
	      AND cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'";
	$result = pg_query($sql);
	$info_pdp = pg_fetch_array($result, null, PGSQL_ASSOC);
	pg_free_result($result);
	$fecha_venc = $info_pdp['fech_venc'];
	$fecha_venc = change_date($fecha_venc);
	$fecha_imp = $info_pdp['fech_pago'];
	if ($fecha_imp == "") {
		$fecha_imp = "-";
	} else
		$fecha_imp = change_date($fecha_imp);
	$total_a_pagar = $info_pdp['monto_cuota'] + $por_form;
	$monto_en_letras = numeros_a_letras($total_a_pagar);
	$sql = "SELECT monto_cuota FROM imp_plan_de_pago WHERE gestion = '$gestion' AND control != '' 
	      AND cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'";
	$result = pg_query($sql);
	$pago_ant = 0;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		foreach ($line as $col_value) {
			$pago_ant = $pago_ant + $col_value;
		}
	} # END_OF_WHILE	
	$sql = "SELECT no_cuota FROM imp_plan_de_pago WHERE gestion = '$gestion' AND cod_geo = '$cod_geo' AND  cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'";
	$check_pdp = pg_num_rows(pg_query($sql));
	$no_cuoto_temp = $no_cuota + 1;
	$liquidacion = $no_cuoto_temp . "/" . $check_pdp;
}

if ($boleta == "Boleta") {
	$cotido = imp_getcoti($fecha, 'usd');
	$cotiufv = imp_getcoti($fecha, 'ufv');
} else {
	$nro_de_orden = "00000000";
}
################################################################################
#-------------------------------- FORMULARIO ----------------------------------#
################################################################################	 
echo "<td>\n";
echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"840px\" height=\"600px\">\n";   # 3 Columnas
echo "<tr height=\"40px\">\n";
echo "<td width=\"15%\">\n";  #Col. 1 
echo "&nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&calc&id=$session_id#tab-10\">\n";
echo "<img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";
echo "</td>\n";
echo "<td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n";
if ($boleta == "Aviso") {
	echo "Pre-Aviso de Pago\n";
} else {
	if ($boleta == "Boleta") {
		echo "Boleta de Pago\n";
	} elseif ($boleta == "ReImp") {
		echo "Re-Impresion de Boleta de Pago\n";
	} 
}
echo "</td>\n";
echo "<td width=\"25%\"> &nbsp</td>\n";   #Col. 3 			 
echo "</tr>\n";
echo "<tr>\n";
echo "<td valign=\"top\" colspan=\"3\">\n";   #Col. 1 	 
if ($boleta == "Aviso") {
	include "siicat_impuestos_generar_boleta_aviso.php";
	echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/boleta$cod_cat.html\" id=\"mapserver\" width=\"840px\" height=\"1000px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
	echo "</iframe>\n";
} else {
	if ($boleta == "Boleta") {
		include "impuestos_generar_boleta_resumen.php";
		echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/boleta$cod_cat.html\" id=\"mapserver\" width=\"840px\" height=\"600px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
		echo "</iframe>\n";
	} elseif ($boleta == "ReImp") {
		include "impuestos_generar_boleta_de_reimpresion.php";
		echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/boleta_reimpr$cod_cat.html\" id=\"mapserver\" width=\"840px\" height=\"1220px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
		echo "</iframe>\n";		
	} 

}
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</td>\n";

?>