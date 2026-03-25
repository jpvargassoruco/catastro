<?php

$imp_neto = $d10 = $impuestos = $mant_val = $interes = $mul_mora = $deb_for = $monto = $por_form = $total = $total_check = 0;
$pagos_convalidados = false;
################################################################################
#-------------------- SELECCIONAR DATOS DE IMP_PAGADOS ------------------------#
################################################################################	
if (isset($_POST["user"])) {
	$usuario_reporte = $_POST["user"];
}


if ($usuario_reporte == "Todos" or $usuario_reporte == "") {
   $and_user = "";
} else {
   $and_user = "AND userid_reg = '$usuario_reporte'";
}

if ($tipo_reporte == "PI") {
   $sql="SELECT fech_pago, nombre_banco, no_boleta_banco, monto_banco, concepto, id_item, gestion, folio FROM imp_control_banco WHERE fech_pago >= '$fecha_inicio' AND fech_pago <= '$fecha_final' AND monto_banco > '0' ORDER BY fech_pago, id";
} elseif ($tipo_reporte == "PI_IPBI") {
   $sql="SELECT fech_pago, nombre_banco, no_boleta_banco, monto_banco, concepto, id_item, gestion, folio FROM imp_control_banco WHERE concepto = 'LIQUIDACION IPBI' AND fech_pago >= '$fecha_inicio' AND fech_pago <= '$fecha_final' AND monto_banco > '0' ORDER BY fech_pago, id";
} elseif ($tipo_reporte == "PI_IPA") {
   $sql="SELECT fech_pago, nombre_banco, no_boleta_banco, monto_banco, concepto, id_item, gestion, folio FROM imp_control_banco WHERE concepto = 'LIQUIDACION IPA' AND fech_pago >= '$fecha_inicio' AND fech_pago <= '$fecha_final' AND monto_banco > '0' ORDER BY fech_pago, id";
} elseif ($tipo_reporte == "PI_IPBI_TRANS") {
   $sql="SELECT fech_pago, nombre_banco, no_boleta_banco, monto_banco, concepto, id_item, gestion, folio FROM imp_control_banco WHERE concepto = 'TRANSFER URBANO' AND fech_pago >= '$fecha_inicio' AND fech_pago <= '$fecha_final' AND monto_banco > '0' $and_user  ORDER BY fech_pago, id";
} elseif ($tipo_reporte == "PI_IPA_TRANS") {
   $sql="SELECT fech_pago, nombre_banco, no_boleta_banco, monto_banco, concepto, id_item, gestion, folio FROM imp_control_banco WHERE concepto = 'TRANSFER RURAL' AND fech_pago >= '$fecha_inicio' AND fech_pago <= '$fecha_final' AND monto_banco > '0' ORDER BY fech_pago, id";
} elseif ($tipo_reporte == "PI_PAT") {
   $sql="SELECT fech_pago, nombre_banco, no_boleta_banco, monto_banco, concepto, id_item, gestion, folio FROM imp_control_banco WHERE concepto = 'LIQUIDACION PAT' AND fech_pago >= '$fecha_inicio' AND fech_pago <= '$fecha_final' AND monto_banco > '0' ORDER BY fech_pago, id";
} elseif ($tipo_reporte == "PI_TASAS") {
   $sql="SELECT fech_pago, nombre_banco, no_boleta_banco, monto_banco, concepto, id_item, gestion, folio FROM imp_control_banco WHERE (concepto = 'TASAS' OR concepto = 'FORM. CAJA') AND fech_pago >= '$fecha_inicio' AND fech_pago <= '$fecha_final' AND monto_banco > '0' ORDER BY fech_pago, id";
} elseif ($tipo_reporte == "PI_TASAS2") {
   $sql="SELECT fech_imp, nombre_banco, no_boleta_banco, monto_banco, concepto, id_item, gestion, folio FROM imp_control_banco icb INNER JOIN tasas_pagados tp ON icb.folio = tp.no_orden WHERE tp.fech_imp >= '$fecha_inicio' AND tp.fech_imp <= '$fecha_final' AND tp.monto > '0' AND (icb.concepto = 'TASAS' OR icb.concepto = 'FORM. CAJA') ORDER BY fech_imp";
}

$check_imp = pg_num_rows(pg_query($sql));
$no_de_impresiones = $check_imp;
$monto_total = 0;
if ($check_imp > 0) {		
   $result=pg_query($sql);
   $i = $j = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
	       if ($i == 0) {
				    $fech_pago[$j] = $fech_imp_temp = $col_value;
						$fech_pago[$j] = change_date ($fech_pago[$j]);
			   } elseif ($i == 1) { 
						$col_value = preg_replace('/ALCALDIA DE/', 'ALC.', $col_value);
				    $nombre_banco[$j] = $col_value;
			   } elseif ($i == 2) {
				    $no_boleta_banco[$j] = $col_value;
			   } elseif ($i == 3) {
				    $monto_banco[$j] = $monto_banco_temp = $col_value;	
			   } elseif ($i == 4) {
				    $concepto[$j] = $concepto_temp = $col_value;											
			   } elseif ($i == 5) {
				    $id_item[$j] = $col_value;
						$codigo[$j] = get_codcat_from_id_inmu ($col_value);
			   } elseif ($i == 6) {
				    $gestion_control[$j] = $col_value;	
			   }  else {
				    $folio[$j] = $col_value;
            $monto_total = $monto_total + $monto_banco[$j];
						$i = -1;
			   }
			   $i++;	 
			}
			$j++;
   }
	 pg_free_result($result);
}


?>