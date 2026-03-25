<?php
$calcular_urbano = $calcular_rural = false;

if (isset($_POST["id_inmu"])) {
   $calcular_urbano = true;
   $id_inmu = $id_item = $_POST["id_inmu"];
   $tit_1id = get_tit_1id_from_id_inmu($id_inmu);
   $tit_2id = get_tit_1id_from_id_inmu($id_inmu);
   $tabla_para_adq_fech = "info_inmu";
   $columna_id_item = "id_inmu";
   $tabla_transfer = "transfer";
   $where_option = "id_inmu = '$id_inmu'";
   $tabla_imp_pagados = "imp_pagados";
}

$min_num = trim($_POST["min_num"]);
if ($min_num == "") {
   $min_num = rand(1, 1000000);
}
$not_nom = trim($_POST["not_nom"]);
if ($not_nom == "") {
   $not_nom = "Sin nombre de notario";
}
$not_num = trim($_POST["not_num"]);
if ($not_num == "") {
   $not_num = rand(1, 1000000);
}
$not_cls = trim($_POST["not_cls"]);
if ($not_cls == "") {
   $not_cls = 1;
}
$not_exp = trim($_POST["not_exp"]);
if ($not_exp == "") {
   $not_exp = "sin dato";
}

$min_val = trim($_POST["min_val"]);
$min_mon = $_POST["min_mon"];
$fecha_minuta = $_POST["min_fech"];



if ($_POST["min_fech"] == "") {
   $min_fech = $min_fech_temp = $min_fech_ymd = $min_fech_texto = "";
} else {
   $min_fech = $min_fech_temp = $min_fech_ymd = change_date_to_ymd_10char(trim($_POST["min_fech"]));
   $min_fech_texto = ($min_fech);
   $gestion_minuta = substr($min_fech_ymd, 0, 4);
}


// Agregado: búsqueda por carnet para comprador 1 y comprador 2 (adaptar nombres de columnas según su tabla)
file_put_contents('C:\apache\siicat\post_dump.txt', print_r($_POST, true));

// Aceptar campos del formulario 'comprador' / 'comprador2' (textbox)
// Si vienen numéricos se tratan como id_contrib, si no como carnet
if (isset($_POST['comprador']) && trim($_POST['comprador']) !== '') {
    $v = trim($_POST['comprador']);
    if (ctype_digit($v)) {
        $_POST['comprador1_id'] = (int)$v;
    } else {
        $_POST['comprador1_carnet'] = $v;
    }
}
if (isset($_POST['comprador2']) && trim($_POST['comprador2']) !== '') {
    $v2 = trim($_POST['comprador2']);
    if (ctype_digit($v2)) {
        $_POST['comprador2_id'] = (int)$v2;
    } else {
        $_POST['comprador2_carnet'] = $v2;
    }
}

$comprador1_carnet = isset($_POST['comprador1_carnet']) ? trim($_POST['comprador1_carnet']) : '';
$comprador1_id   = isset($_POST['comprador1_id']) ? (int)$_POST['comprador1_id'] : 0;

$comprador2_carnet = isset($_POST['comprador2_carnet']) ? trim($_POST['comprador2_carnet']) : '';
$comprador2_id   = isset($_POST['comprador2_id']) ? (int)$_POST['comprador2_id'] : 0;

$comprador1_name = $comprador2_name = '';
// Use the submitted IDs as defaults so the script doesn't zero them out
$id_comp  = $comprador1_id;
$id_comp2 = $comprador2_id;

// COMPRADOR 1
if (isset($_POST['comprador1_carnet']) && $comprador1_carnet !== '') {
    $c = pg_escape_string($comprador1_carnet);
    
	$sqlc = "SELECT
		id_contrib,
		(
			SELECT string_agg(valor, ' ')
			FROM unnest(ARRAY[
				NULLIF(TRIM(con_nom1), ''),
				NULLIF(TRIM(con_nom2), ''),
				NULLIF(TRIM(con_pat),  ''),
				NULLIF(TRIM(con_mat),  ''),
				NULLIF(TRIM(con_cas),  '')
			]) AS valor
			WHERE valor IS NOT NULL
		) AS nombre_completo
	FROM contribuyentes
	WHERE doc_num = '$c' LIMIT 1";

    $resc = pg_query($sqlc);
    if ($resc && pg_num_rows($resc) > 0) {
        $rowc = pg_fetch_assoc($resc);
        $id_comp = $rowc['id_contrib'];
        $comprador1_name = $rowc['nombre_completo'];
    } else {
        $comprador1_name = '';
        $mensaje_de_error_comp1 = "Comprador 1 no encontrado";
    }
}
// COMPRADOR 2
if (isset($_POST['comprador2_carnet']) && $comprador2_carnet !== '') {
    $c2 = pg_escape_string($comprador2_carnet);

	$sqlc2 = "SELECT
		id_contrib,
		(
			SELECT string_agg(valor, ' ')
			FROM unnest(ARRAY[
				NULLIF(TRIM(con_nom1), ''),
				NULLIF(TRIM(con_nom2), ''),
				NULLIF(TRIM(con_pat),  ''),
				NULLIF(TRIM(con_mat),  ''),
				NULLIF(TRIM(con_cas),  '')
			]) AS valor
			WHERE valor IS NOT NULL
		) AS nombre_completo
	FROM contribuyentes
	WHERE doc_num = '$c2' LIMIT 1";

    $resc2 = pg_query($sqlc2);
    if ($resc2 && pg_num_rows($resc2) > 0) {
        $rowc2 = pg_fetch_assoc($resc2);
        $id_comp2 = $rowc2['id_contrib'];
        $comprador2_name = $rowc2['nombre_completo'];
    } else {
        $comprador2_name = '';
        $mensaje_de_error_comp2 = "Comprador 2 no encontrado";
    }
}


$modo_trans = $_POST["modo_trans"];
$modo_trans_texto = strtoupper(abr($_POST["modo_trans"]));

####################################################
#-------- FECHA DE ADQUISICION DEL IMUEBLE --------#
#################################################### 
$sql = "SELECT adq_fech FROM $tabla_para_adq_fech WHERE $columna_id_item = '$id_item'";
$result = pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$adq_fech_ant = $info['adq_fech'];

$adq_fech_ant_texto = ($adq_fech_ant);
pg_free_result($result);
################################################################################
#---------------------------- CHEQUEAR POR ERRORES ----------------------------#
################################################################################	`
$transfer_check = true;
if ((!check_int($min_val)) and ($modo_trans == "CPV")) {
   $transfer_check = false;
   $mensaje_de_error_transfer = "Error: El valor de minuta debe ser un número!";
} elseif (!check_fecha($min_fech, $dia_actual, $mes_actual, $ano_actual)) {
   $transfer_check = false;
   $mensaje_de_error_transfer = "Error: La fecha de minuta tiene un formato incorrecto! Formatos correctos son DD/MM/AAAA o AAAA-MM-DD.";
} elseif ($min_fech < $adq_fech_ant) {
   $transfer_check = false;
   $mensaje_de_error_transfer = "Error: $min_fech La fecha de la firma minuta está erronea. El propietario actual recien obtuvo el inmueble en fecha $adq_fech_ant_texto.";
} elseif ($id_comp == 0) {
   $transfer_check = false;
   $mensaje_de_error_transfer = "Error: Ingrese un numero de carnet valido!";
} elseif ($tit_1id == $id_comp) {
   $transfer_check = false;
   $mensaje_de_error_transfer = "Error: El propietario actual y el comprador es la misma persona!";
} elseif ($id_comp  == $id_comp2) {
   $transfer_check = false;
   $mensaje_de_error_transfer = "Error: el comprador 1 y el comprador 2 es la misma persona!";
}

################################################################################
#---- CHEQUEAR SI LAS GESTIONES ANTES DE LA TRANSFERENCIA ESTAN CANCELADAS ----#	
################################################################################
if ($transfer_check) {
   if ($db != "cc") {

      $deudas = false;

      ########################################################
      ###      PRIMERA GESTION CANCELADA DEL PREDIO EN    ####
      ########################################################

      $sql = "SELECT gestion FROM $tabla_imp_pagados WHERE $where_option AND (forma_pago = 'CONTADO' OR forma_pago = 'CANCELADO' OR forma_pago = 'VALIDADO' OR forma_pago = 'PRESCRIP') ORDER BY gestion LIMIT 1";
      $check_gestion = pg_num_rows(pg_query($sql));
      if ($check_gestion == 1) {
         $result = pg_query($sql);
         $info = pg_fetch_array($result, null, PGSQL_ASSOC);
         $primera_gestion = $info['gestion'];
         pg_free_result($result);
         $check_gestion = $primera_gestion + 1;
      } elseif (($adq_fech_ant != "") and ($adq_fech_ant != NULL) and ($adq_fech_ant != "1900-01-01")) {
         $primera_gestion = $check_gestion = substr($adq_fech_ant, 0, 4);
      } else {
         $primera_gestion = $check_gestion = $ano_actual - 6;
      }
      ### DEFINIR SI YA SE PUEDE CANCELAR LA GESTION ANTERIOR ###
      $fecha_venc_gest_ant = imp_get_fecha_venc_1st($ano_actual - 1);
      if ($fecha_venc_gest_ant == -1) {
         $ano_actual = $ano_actual - 1;
      }
      ##################################################################################################
      #      SOLO SE HACE EL CONTROL APARTIR DE EL AÑO DE COBRO DE IMPUESTO  EN EL NUEVO SISTEMA  2018 #
      ##################################################################################################
      while ($check_gestion < $ano_actual) {
         $sql = "SELECT gestion FROM $tabla_imp_pagados WHERE $where_option AND gestion = '$check_gestion' AND (forma_pago = 'CANCELADO' OR forma_pago = 'VALIDADO' OR forma_pago = 'PRESCRIP' OR forma_pago = 'CONTADO')";
         $check = pg_num_rows(pg_query($sql));
         if ($check == 0) {
            $deudas = true;
         }
         $check_gestion++;
      }

      if ($deudas) {
         $transfer_check = false;
         $mensaje_de_error_transfer = "Error: No puede realizar la transferencia, si el predio tiene pagos de impuestos pendientes antes de la fecha de transferencia! Debe pagar las gestiones anteriores bajo el nombre del propietario anterior!</font>!";
         $sql = "SELECT id_div FROM tramite_div WHERE id_inmu2=$id_inmu";
         $check = pg_num_rows(pg_query($sql));
         if ($check == 0) {
            $mensaje_de_ayuda_transfer = "Nota.: La unica forma que se pueda hacer la transferencia sin tener pagos de impuestos es teniendo una divicion de predio";
         } else {
            $division = true;
            $deudas = false;
            $transfer_check = true;
         }

      }
   }
}

### CHEQUEAR POR REGISTRO EXISTENTE ###	

if (($calcular_urbano) and ($transfer_check)) {
   $sql = "SELECT id_inmu FROM imp_transfer WHERE min_fech = '$min_fech' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' AND estatus = 'CANCELADO'";
   $check = pg_num_rows(pg_query($sql));

   if ($check > 0) {
      $transfer_check = false;
      $mensaje_de_error_transfer = "Error: Ya se registró un transferencia del inmueble con esa fecha de minuta!";
   }
} elseif (($calcular_rural) and ($transfer_check)) {
   $sql = "SELECT id_predio_rural FROM imp_transfer_rural WHERE min_fech = '$min_fech' AND id_predio_rural = '$id_predio_rural' AND estatus = 'CANCELADO'";
   $check = pg_num_rows(pg_query($sql));
   if ($check > 0) {
      $transfer_check = false;
      $mensaje_de_error_transfer = "Error: Ya se registró un transferencia de la propiedad con esa fecha de minuta!";
   }
}
if ((!$transfer_check) and ($calcular_urbano)) {
   include "siicat_busqueda_resultado.php";
} else {

   ########################################
   #-------- LEER/CALCULAR DATOS ---------#
   ########################################
   $periodo = $ano_actual;
   if ($calcular_urbano) {
      $tipo_de_inmueble = get_tipo_inmu_from_id_inmu($id_inmu);
      $sql = "SELECT tipo_inmu FROM info_inmu WHERE id_inmu = '$id_inmu'";
      $result = pg_query($sql);
      $info = pg_fetch_array($result, null, PGSQL_ASSOC);
      $tipo_inmu = $info['tipo_inmu'];

      if ($tipo_inmu == "PRE") {
         $tipo_de_inmueble = "SOLO GEOMETRIA";
      } elseif ($tipo_inmu == "TRR" or $tipo_inmu == "TER") {
         $tipo_de_inmueble = "TERRENO";
      } elseif ($tipo_inmu == "VIV") {
         $tipo_de_inmueble = "VIVIENDA";
      } elseif ($tipo_inmu == "RUR") {
         $tipo_de_inmueble = "PROPIEDAD RURAL";
      } elseif ($tipo_inmu == "PH") {
         $tipo_de_inmueble = "PROP. HORIZONTAL";
      } else {
         $tipo_de_inmueble = "-";
      }
      $barrio = get_barrio($id_inmu);
      $vendedor = get_prop1_from_id_inmu($id_inmu);
   }

   $fecha_posesion = "-";
   $nit = "-";
   $ciudad = $dom_ciu_mayus;
   $fecha_emision = $fecha2;
   $pmc = "-";
   $direccion = get_direccion_from_id_inmu($id_inmu);
   $puerta = "-";
   $bloque = "-";
   $piso = "-";
   $dpto = "-";
   $dom_dir = get_contrib_dom($tit_1id);
   $dom_num = "-";

   ########################################
   #--------- DATOS DEL COMPRADOR --------#
   ######################################## 

   $sql = "SELECT id_contrib, con_pat, con_mat, con_nom1, con_nom2  FROM contribuyentes WHERE id_contrib = '$id_comp'";
   $check = pg_num_rows(pg_query($sql));
   if ($check == 0) {
      $con_pmc = "-";
   } else {
      $result = pg_query($sql);
      $info = pg_fetch_array($result, null, PGSQL_ASSOC);
      $id_contrib1 = $info['id_contrib'];
      $con_pat = $info['con_pat'];
      $con_mat = $info['con_mat'];
      $con_nom1 = $info['con_nom1'];
      $con_nom2 = $info['con_nom2'];
      pg_free_result($result);
      $con_pmc;
   }


   $comprador = get_contrib_nombre($id_comp);
   $comp_ci = get_contrib_ci($id_comp);
   $comprador = $comprador . " " . $comp_ci;

   $comprador2 = get_contrib_nombre($id_comp2);
   $comp_ci2 = get_contrib_ci($id_comp2);
   $comprador2 = $comprador2 . " " . $comp_ci2;

   if ($comp_ci == "") {
      $comp_ci_texto = "-";
   } else
      $comp_ci_texto = $comp_ci;

   $dom_dir_comp = get_contrib_dom($id_comp);
   $cod_pmc_comp = get_contrib_pmc($id_comp);
   $comp_tipo = get_contrib_tipo($id_comp);

   ########################################
   #-------- GESTION PARA VALUACION ------#
   ########################################
   $min_fech_ymd = change_date_to_ymd_10char($min_fech);
   $gestion = substr($min_fech_ymd, 0, 4);
   ### DEFINIR SI YA SE PUEDE CANCELAR LA GESTION ANTERIOR
   $fecha_venc_gest_ant = imp_get_fecha_venc_1st($gestion);

   if ($fecha_venc_gest_ant == -1) {
      $gestion = $gestion - 2;
   } else
      $gestion = $gestion - 1;
   ########################################
   #------------ OTROS DATOS -------------#
   ######################################## 
   $monto_conban_total = 0;
   $deuda_pagada_sin_repform = 0;
   $moneda = "UFV";
   $imprimir_preliq = true;
   ########################################
   #----------- PRELIQUIDACION -----------#
   ########################################	
   include "igm_impuestos_boleta_de_pago_transf.php";

}
?>