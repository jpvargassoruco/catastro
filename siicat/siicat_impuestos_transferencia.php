<?php  
################################################################################
#                     EL SIGUIENTE CODIGO FUE CREADO POR:                      #
#         MERCATORGIS - SISTEMAS DE INFORMACION GEOGRAFICA Y DE CATASTRO       #
#     ***** http://www.mercatorgis.com  *****  info@mercatorgis.com *****      #
################################################################################	

$calcular_urbano = $calcular_rural = false;
if (isset($_POST["id_inmu"])) {
   $calcular_urbano = true;	
   $id_inmu = $id_item = $_POST["id_inmu"];
	 $tit_1id = get_tit_1id_from_id_inmu ($id_inmu);
	 $tabla_para_adq_fech = "info_inmu";
	 $columna_id_item = "id_inmu";
	 $tabla_transfer = "transfer";
	 $where_option = "id_inmu = '$id_inmu'";
	 $tabla_imp_pagados = "imp_pagados";
} elseif (isset($_POST["id_predio_rural"])) {
   $calcular_rural = true;	
   $id_predio_rural = $id_item = $_POST["id_predio_rural"];
	 $tit_1id = get_tit_1id_from_id_predio_rural ($id_predio_rural);	
	 $tabla_para_adq_fech = "info_predio_rural";
	 $columna_id_item = "id_predio_rural";	
	 $tabla_transfer = "transfer_rural";
	 $where_option = "id_predio_rural = '$id_predio_rural'";
	 $tabla_imp_pagados = "imp_pagados_rural";  
}

$min_num = trim($_POST["min_num"]);
$not_nom = trim($_POST["not_nom"]);
$not_num = trim($_POST["not_num"]);
$not_cls = trim($_POST["not_cls"]);
$not_exp = trim($_POST["not_exp"]);
$min_val = trim($_POST["min_val"]);
$min_mon = $_POST["min_mon"];
if ($_POST["min_fech"] == "") {
   $min_fech = $min_fech_temp = $min_fech_ymd = $min_fech_texto = ""; 
} else {
   $min_fech = $min_fech_temp = $min_fech_ymd = change_date_to_ymd_10char(trim($_POST["min_fech"]));
	 $min_fech_texto = change_date($min_fech);
	 $gestion_minuta = substr($min_fech_ymd,0,4);
}
$id_comp = $_POST["comprador"];
$modo_trans = $_POST["modo_trans"];
$modo_trans_texto = strtoupper(abr($_POST["modo_trans"]));
#$dias_festivos = $_POST["dias_festivos"];
########################################
#-------- FECHA DE ADQUISICION --------#
######################################## 
$sql="SELECT adq_fech FROM $tabla_para_adq_fech WHERE $columna_id_item = '$id_item'";	 	
$result = pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);	
$adq_fech_ant = $info['adq_fech'];
$adq_fech_ant_texto = change_date($adq_fech_ant);			
pg_free_result($result);	
################################################################################
#---------------------------- CHEQUEAR POR ERRORES ----------------------------#
################################################################################	`
$transfer_check = true;
if (!check_int($not_num)) {
   $transfer_check = false;
   $mensaje_de_error_transfer = "Error: El valor para el número de notario debe ser un número!";
} elseif (!check_int($not_cls)) {
   $transfer_check = false;
   $mensaje_de_error_transfer = "Error: El valor para la clase de notario debe ser un número!";	 
} elseif ((!check_int($min_val)) AND ($modo_trans == "CPV")) {
   $transfer_check = false;
   $mensaje_de_error_transfer = "Error: El valor de minuta debe ser un número!";
} elseif (!check_fecha($min_fech,$dia_actual,$mes_actual,$ano_actual)) {
   $transfer_check = false;
   $mensaje_de_error_transfer = "Error: La fecha de minuta tiene un formato incorrecto! Formatos correctos son DD/MM/AAAA o AAAA-MM-DD.";
} elseif ($min_fech < $adq_fech_ant) {
   $transfer_check = false;
   $mensaje_de_error_transfer = "Error: La fecha de la firma minuta está erronea. El propietario actual recien obtuvo el inmueble en fecha $adq_fech_ant_texto.";
} elseif ($id_comp == 0) {
   $transfer_check = false;
   $mensaje_de_error_transfer = "Error: Debe elegir un comprador/cesionario/heredero de la lista de contribuyentes!";
} elseif ($tit_1id == $id_comp) {
   $transfer_check = false;
   $mensaje_de_error_transfer = "Error: El propietario actual y el comprador es la misma persona!";
}
################################################################################
#---- CHEQUEAR SI LAS GESTIONES ANTES DE LA TRANSFERENCIA ESTAN CANCELADAS ----#	
################################################################################
if ($transfer_check) {
   if ($db != "cc") {	
	    $deudas = false;
	    ### AVERIGUAR LA PRIMERA GESTION CANCELADA 
      $sql = "SELECT gestion FROM $tabla_imp_pagados WHERE $where_option AND (estatus = 'CANCELADO' OR estatus = 'VALIDADO' OR estatus = 'PRESCRIP') ORDER BY gestion LIMIT 1";
	    $check_gestion = pg_num_rows(pg_query($sql));
	    if ($check_gestion == 1) {
         $result = pg_query($sql);
         $info = pg_fetch_array($result, null, PGSQL_ASSOC);
         $primera_gestion = $info['gestion'];	
         pg_free_result($result);	
			   $check_gestion = $primera_gestion + 1;
      } elseif (($adq_fech_ant != "") AND ($adq_fech_ant != NULL) AND ($adq_fech_ant != "1900-01-01")) {
	       $primera_gestion = $check_gestion = substr($adq_fech_ant, 0, 4);;
      } else {
	       $primera_gestion = $check_gestion = $ano_actual - 6;
      }
#echo "L94 PRIMERA GESTION: $primera_gestion<br />";
      ### DEFINIR SI YA SE PUEDE CANCELAR LA GESTION ANTERIOR ###
			$fecha_venc_gest_ant = imp_get_fecha_venc_1st ($ano_actual-1);
			if ($fecha_venc_gest_ant == -1) {
#echo "L68 FECHA VENC. PARA LA GESTION $ano_actual-1: $fecha_venc_gest_ant<br />";
         $ano_actual = $ano_actual-1;
      }	 
      while ($check_gestion < $gestion_minuta) {
         $sql = "SELECT gestion FROM $tabla_imp_pagados WHERE $where_option AND gestion = '$check_gestion' AND (estatus = 'CANCELADO' OR estatus = 'VALIDADO' OR estatus = 'PRESCRIP')";
	       $check = pg_num_rows(pg_query($sql));
	       if ($check == 0) {			  
			      $deudas = true; 
			   }
#echo "L107 CHECK GESTION: $check_gestion, $check<br />";				 
		     $check_gestion++;
      }
      if ($deudas) {
         $transfer_check = false;
         $mensaje_de_error_transfer = "Error: No puede pagar los impuestos de la transferencia si el predio tiene pagos de impuestos pendientes antes de la fecha de transferencia! Debe pagar las gestiones anteriores bajo el nombre del propietario anterior!</font>!";	 
      }
   }
}
### CHEQUEAR POR REGISTRO EXISTENTE ###	
if (($calcular_urbano) AND ($transfer_check)) {
   $sql="SELECT id_inmu FROM imp_transfer WHERE min_fech = '$min_fech' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' AND estatus = 'CANCELADO'";
   $check = pg_num_rows(pg_query($sql));
   if ($check > 0) {
      $transfer_check = false;
      $mensaje_de_error_transfer = "Error: Ya se registró un transfer del inmueble con esa fecha de minuta!";	 
   }
} elseif (($calcular_rural) AND ($transfer_check)) {
   $sql="SELECT id_predio_rural FROM imp_transfer_rural WHERE min_fech = '$min_fech' AND id_predio_rural = '$id_predio_rural' AND estatus = 'CANCELADO'";
   $check = pg_num_rows(pg_query($sql));
   if ($check > 0) {
      $transfer_check = false;
      $mensaje_de_error_transfer = "Error: Ya se registró un transfer de la propiedad con esa fecha de minuta!";	 
   }
}

if ((!$transfer_check) AND ($calcular_urbano)) {
   include "siicat_busqueda_resultado.php";
} elseif ((!$transfer_check) AND ($calcular_rural)) {
   include "siicat_rural_resultado.php";
} else {
   ########################################
   #-------- LEER/CALCULAR DATOS ---------#
   ########################################
   $periodo = $ano_actual;
	 if ($calcular_urbano) {
      $tipo_de_inmueble = get_tipo_inmu_from_id_inmu($id_inmu);
			$barrio = get_barrio ($id_inmu);
			$vendedor = get_prop1_from_id_inmu($id_inmu);
	 } elseif ($calcular_rural) {
      $tipo_de_inmueble = $tipo_inmu_texto = "PROPIEDAD RURAL";
			$barrio = "-";
			$vendedor = get_prop1_from_id_predio_rural($id_predio_rural);
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
   $dom_dir = get_contrib_dom ($tit_1id);
   $dom_num = "-";
	 ########################################
   #--------- DATOS DEL COMPRADOR --------#
   ######################################## 
   $comprador = get_contrib_nombre ($id_comp);
	 $comp_ci = get_contrib_ci ($id_comp);
	 if ($comp_ci == "") {
	    $comp_ci_texto = "-";
	 } else $comp_ci_texto = $comp_ci;		 
   $dom_dir_comp = get_contrib_dom ($id_comp);
   $cod_pmc_comp = get_contrib_pmc ($id_comp);
	 $comp_tipo = get_contrib_tipo ($id_comp);
	 ########################################
   #-------- GESTION PARA VALUACION ------#
   ########################################
	 $min_fech_ymd = change_date_to_ymd_10char ($min_fech);
	 $gestion = substr($min_fech_ymd,0,4);
   ### DEFINIR SI YA SE PUEDE CANCELAR LA GESTION ANTERIOR
   $fecha_venc_gest_ant = imp_get_fecha_venc_1st ($gestion-1);
   if ($fecha_venc_gest_ant == -1) {
      $gestion = $gestion-2;
   } else $gestion = $gestion-1;	 
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
   include "siicat_impuestos_boleta_de_pago.php";	 

}		
?>
