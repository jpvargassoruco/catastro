<?php
################################################################################
#                     EL SIGUIENTE CODIGO FUE CREADO POR:                      #
#         MERCATORGIS - SISTEMAS DE INFORMACION GEOGRAFICA Y DE CATASTRO       #
#     ***** http://www.mercatorgis.com  *****  info@mercatorgis.com *****      #
################################################################################

$calcular_transfer_urbano = true;
$concepto = "TRANSFER URBANO";
$id_item = $id_inmu;
$tabla_transfer = "imp_transfer";
$tabla_contrib_add = "info_inmu_contrib_add";
$where = "id_inmu = '$id_inmu'";
$accion_reg = "Transfer Urbano";
### LEER DATOS ANTERIORES ###	
include "siicat_info_inmu_leer_datos.php";	 
########################################
#----- DATOS PARA TABLA TRANSFER ------#
########################################	
$x = $_POST["convalidar"];
if ((isset($_POST["convalidar"])) AND ($_POST["convalidar"] == "REGISTRAR")) {
   $tit_1id_ant = $tit_1id;
   $tit_2id_ant = $tit_2id;
   $tit_cara_ant = $tit_cara;
   $adq_fech_ant = $adq_fech_ant_temp = $adq_fech_temp;
   $der_num_ant = $der_num;
   $der_fech_ant = $der_fech_temp;
   $adq_modo_ant = $adq_modo;
   $adq_doc_ant = $adq_doc;
   $adq_mont_bs_ant = $adq_mont_bs;
   $adq_mont_usd_ant = $adq_mont_usd;
} else {
   $tit_1id_ant = $_POST["tit_1id_ant"];
   $tit_2id_ant = $_POST["tit_2id_ant"];
   $tit_cara_ant = $_POST["tit_cara_ant"];
   $adq_fech_ant = $_POST["adq_fech_ant"];
   $adq_fech_ant_temp = change_date ($adq_fech_ant);  
   $der_num_ant = $_POST["der_num_ant"];
   $der_fech_ant = $_POST["der_fech_ant"];

   if ($der_fech_ant == "") {
      $der_fech_ant = "1900-01-01";
   }
   $adq_modo_ant = $_POST["adq_modo_ant"];
   $adq_doc_ant = utf8_decode($_POST["adq_doc_ant"]);

   if ($adq_fech_ant == "") {
      $adq_fech_ant = $adq_fech_ant_temp = "1900-01-01";
   }

   $adq_mont_bs_ant = $_POST["adq_mont_bs_ant"];
   if ($adq_mont_bs_ant == "") {
      $adq_mont_bs_ant = -1;
   }
   $adq_mont_usd_ant = $_POST["adq_mont_usd_ant"];
   if ($adq_mont_usd_ant == "") {
      $adq_mont_usd_ant = -1;
   }
}
########################################
#------ LEER CONTRIB ADICIONALES ------#
########################################	
$sql = "SELECT tit_xid FROM $tabla_contrib_add WHERE $where";
$check_contrib_add = pg_num_rows(pg_query($sql));
$i = $no_contrib_add_ant = 0;
if ($check_contrib_add > 0) { 
   $result = pg_query($sql);
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {				 	 			           
      foreach ($line as $col_value) {			 
         $tit_xid_ant[$i] = $col_value;
				 $no_contrib_add_ant++;
				 $i++;
	    }
   }	
}

######################################
#-- LEER DATOS DE BANCO Y TRANSFER --#
######################################
if ((isset($_POST["convalidar"])) AND ($_POST["convalidar"] == "REGISTRAR")) {
	$error_registro = false;
	$fech_imp_venc = $fecha;
	$modo_pago = "VALIDADO";
	### DATOS DE LA TRANSFERENCIA
	$gestion = trim($_POST["gestion"]);	 
	$min_num = trim($_POST["min_num"]);
	$not_nom = trim($_POST["not_nom"]);
	$not_num = trim($_POST["not_num"]);
	$not_cls = trim($_POST["not_cls"]);
	$not_exp = trim($_POST["not_exp"]);
	$min_val = trim($_POST["min_val"]);
	$min_mon = $_POST["min_mon"];
	$min_fech = $min_fech_temp = $min_fech_ymd = change_date_to_ymd_10char(trim($_POST["min_fech"]));
	$min_fech_texto = change_date($min_fech);	
	$id_comp = $_POST["comprador"];
	$modo_trans = $_POST["modo_trans"];
	$modo_trans_texto = strtoupper(abr($_POST["modo_trans"]));
	### DATOS DE LA CONVALIDACION
	$gestion_conval = $_POST["gestion"];
	$folio = $folio_select = $no_orden_conval = (int) $_POST["no_orden"];
	$fecha_banco = $fech_pago_conval = $_POST["fech_pago"];
	$fecha_banco_ymd = change_date_to_ymd_10char ($fecha_banco);
	$cuota_tabla = $cuota_conval = (int) $_POST["cuota"];
	$nombre_banco = $nombre_banco_conval = $_POST["nombre_banco"]; 
	$no_boleta_banco = $control_conval = $_POST["control"];	 
} else {
   $modo_pago = "CONTADO";
   ######################################
   #------- LEER DATOS DE BANCO --------#
   ######################################
   $folio_select = $_POST["folio_select"];
   $fecha_banco = trim ($_POST["fecha_banco"]);
   $fecha_banco_ymd = change_date_to_ymd_10char ($fecha_banco);
   $nombre_banco = trim ($_POST["nombre_banco"]);
   $nombre_banco_form = utf8_decode($nombre_banco);	 
   $no_boleta_banco = trim ($_POST["no_boleta_banco"]);
   ######################################	
   # LEER DATOS DEL FOLIO ESPECIFICADO ##
   ######################################		
   $sql="SELECT min_num, id_comp, modo_trans, min_val, min_mon, min_fech, total_a_pagar, fech_imp, fech_imp_venc, id_comp2 FROM $tabla_transfer WHERE folio = '$folio_select'";	 	
   $result = pg_query($sql);
   $info = pg_fetch_array($result, null, PGSQL_ASSOC);
   $min_num = $info['min_num'];	
   $id_comp = $info['id_comp'];		
   $id_comp2 = $info['id_comp2'];	
   $modo_trans = $info['modo_trans'];		
   $min_val = $info['min_val'];	
   $min_mon = $info['min_mon'];	
   $min_fech = $info['min_fech'];	
   $cuota_tabla = $info['total_a_pagar'];	 
   $fech_imp = $info['fech_imp'];	 
   $fech_imp_venc = $info['fech_imp_venc'];			
   #echo "CUOTA_TABLA: $cuota_tabla, FECH_VENC: $fech_imp_venc<br>";
   pg_free_result($result);	
   $gestion = substr($min_fech,0,4); 
   ######################################
   #------- CHEQUEAR PAGO BANCO --------#
   ######################################
   $error_registro = false;
   if ((!check_fecha($fecha_banco,$dia_actual,$mes_actual,$ano_actual)) AND ($modo_trans == "CPV")) {	 	
	    $error_registro = true;
			$mensaje_de_error_registro = "Error: La fecha de pago ingesada no es válida!";
   } elseif ((($nombre_banco == "") OR ($nombre_banco == "---------------")) AND ($modo_trans == "CPV")) {	
	    $error_registro = true;
			$mensaje_de_error_registro = "Error: Tiene que ingresar el nombre de la institución donde canceló el pago!";
   } elseif (($no_boleta_banco == "") AND ($modo_trans == "CPV")) {	
	    $error_registro = true;
			$mensaje_de_error_registro = "Error: Tiene que ingresar el número de la boleta del pago!";							
   }

}
########################################
#---------- RELLENAR TABLAS -----------#
########################################		
if (!$error_registro) {

	 ### VALOR MINUTA	 ###
   $cambio_usd = imp_getcoti($min_fech,"usd"); 
   if ($min_mon == "bs") {
      $valor_min = $min_val;
	    $valor_usd = -1;
   } else {
      $valor_min = ROUND($cambio_usd * $min_val,0);
	    $valor_usd = $min_val;
   }	 
   ######################################
   #----- CHEQUEAR MONTO A PAGAR -------#
   ######################################
	 if ($cuota_tabla == "0") {
	    $registro_imp_control_banco = false;
			$fecha_banco = $fech_imp;
			$nombre_banco = "---";
			$no_boleta_banco = 0;
	 } else {
	    $registro_imp_control_banco = true;
   }
	#echo "L214 REGISTRO ES $registro_valido<br />";
   ######################################
   #---------- REGISTRAR PAGO ----------#
   ######################################
	if ($registro_imp_control_banco) {			
      ### REGISTRAR EL PAGO EN EL BANCO EN IMP_CONTROL_BANCO ###	
      $sql = "INSERT INTO imp_control_banco (fech_pago, nombre_banco, no_boleta_banco, monto_banco, concepto, id_item, gestion, modo_pago, folio, fech_reg, hora_reg, userid_reg)
              VALUES('$fecha_banco_ymd','$nombre_banco','$no_boleta_banco','$cuota_tabla','$concepto','$id_inmu','$gestion','$modo_pago','$folio_select','$fecha','$hora','$user_id')"; 
      pg_query($sql);
	 }	
   ######################################
   # REGISTRO EN TIEMPO/FUERA DE TIEMPO #
   ######################################	
#echo "L311 FECHA_VENC: $fech_imp_venc, PAGO_BANCO: $fecha_banco_ymd<br />";			
   $f1 = strtotime($fech_imp_venc);
   $f2 = strtotime($fecha_banco_ymd);
   if (($f2 > $f1) AND ($modo_trans == "CPV")) {  ### YA PASO LA FECHA DE VENCIMIENTO
      $conciliacion = true;		 				 
		$sql = "UPDATE $tabla_transfer SET forma_pago = 'CREDITO', estatus = 'PARCIAL', fech_pago = '$fecha_banco', fech_reg = '$fecha', hora_reg = '$hora', userid = '$user_id'
                 WHERE folio = '$folio_select'";
      pg_query($sql);		
   } else {  ### TODO EN ORDEN --> REGISTRAR PAGO
		$conciliacion = false;
      if ((isset($_POST["convalidar"])) AND ($_POST["convalidar"] == "REGISTRAR")) {
			if ($calcular_transfer_urbano) {
				$sql = "INSERT INTO imp_transfer (folio, cod_geo, id_inmu, tit_1id, min_num, not_nom, not_num, not_cls, not_exp, min_val, min_mon, min_fech, id_comp, modo_trans,
								 base_imp, deuda_bs, total_a_pagar, fech_imp, estatus, fech_pago, fech_reg, hora_reg, userid, control)
				         VALUES ('$no_orden_conval','$cod_geo','$id_inmu','$tit_1id','$min_num','$not_nom','$not_num','$not_cls','$not_exp','$min_val','$min_mon','$min_fech','$id_comp','$modo_trans',
								 '$min_val','$cuota_conval','$cuota_conval','$fech_pago_conval','VALIDADO','$fech_pago_conval','$fecha','$hora','$user_id','$control_conval')";			
			} else {
				$sql = "INSERT INTO imp_transfer_rural (folio, id_predio_rural, tit_1id, min_num, not_nom,
				         not_num, not_cls, not_exp, min_val, min_mon, min_fech, id_comp, modo_trans,
								 base_imp, deuda_bs, total_a_pagar, fech_imp, estatus, fech_pago, fech_reg, hora_reg, userid, control)
				         VALUES ('$no_orden_conval','$id_predio_rural','$tit_1id','$min_num','$not_nom',
								 '$not_num','$not_cls','$not_exp','$min_val','$min_mon','$min_fech','$id_comp','$modo_trans',
								 '$min_val','$cuota_conval','$cuota_conval','$fech_pago_conval','VALIDADO','$fech_pago_conval','$fecha','$hora','$user_id','$control_conval')";
				 }
			} else {
         $sql = "UPDATE $tabla_transfer SET forma_pago = 'CONTADO', estatus = 'CANCELADO', fech_pago = '$fecha_banco', fech_reg = '$fecha', hora_reg = '$hora', userid = '$user_id'
                 WHERE folio = '$folio_select'";
			}
      pg_query($sql);				 
		  ### SUPRIMIR PRELIQUIDACIONES DEL MISMO ITEM ###
		if ($calcular_transfer_urbano) {
			$sql = "UPDATE imp_transfer SET estatus = 'OBSOLETO' WHERE folio != '$folio_select' AND id_inmu = '$id_inmu' AND cod_geo = '$cod_geo' AND estatus = 'PRELIQUID'";	
#echo "L335 SQL: $sql<br />";
		}
			pg_query($sql); 
      ### LLENAR TABLA TRANSFER ###
		$adq_doc_ant = utf8_encode($adq_doc_ant);
		if ($calcular_transfer_urbano) {
			$sql = "INSERT INTO transfer (cod_geo, id_inmu, id_proc, tan_fech_ini, tan_fech_fin, tan_modo, tan_doc, tan_mont_usd, tan_mont_bs, tan_cara, tan_1id, tan_2id, tan_der_fech, tan_der_num, tan_folio) 
				VALUES ('$cod_geo','$id_inmu','0','$adq_fech_ant_temp','$min_fech', '$adq_modo_ant','$adq_doc_ant','$adq_mont_usd_ant','$adq_mont_bs_ant','$tit_cara_ant','$tit_1id_ant','$tit_2id_ant','$der_fech_ant','$der_num_ant','$folio_select')";
      }			
      pg_query($sql);
      ### GUARDAR PROP. ADICIONALES ANTIGUAS EN TABLA TRANSFER_CONTRIB_ADD ###
      if ($calcular_transfer_urbano) {
			if ($no_contrib_add_ant > 0) {
				$i = 0;
				while ($i < $no_contrib_add_ant) {
					$contrib_adicional = $tit_xid_ant[$i];
					$sql = "INSERT INTO transfer_contrib_add (cod_geo, id_inmu, tan_fech_ini, tan_fech_fin, tan_xid) 
					VALUES ('$cod_geo','$id_inmu','$adq_fech_ant_temp','$adq_fech_temp','$contrib_adicional')";		        
					pg_query($sql);
					$i++;
				}
         }
		} elseif ($calcular_transfer_rural) {
			   if ($no_contrib_add_ant > 0) {
					$i = 0;
					while ($i < $no_contrib_add_ant) {
						$contrib_adicional = $tit_xid_ant[$i];
						$sql = "INSERT INTO transfer_rural_contrib_add (id_predio_rural, tan_fech_ini, tan_fech_fin, tan_xid) 
		                    VALUES ('$id_predio_rural','$adq_fech_ant_temp','$adq_fech_temp','$contrib_adicional')";				        
						pg_query($sql);
               $i++;
				}
         }
		}					
		### ACTUALIZAR DATOS EN INFO_INMU O INFO_PREDIO_RURAL ###
		$tit_1id = $id_comp;	
		$tit_2id = $id_comp2;
		$adq_modo = $modo_trans;
		$adq_doc = "Minuta de Transferencia No. ".$min_num;
		$adq_fech = $adq_fech_temp = $min_fech;				
		$adq_mont_bs = $valor_min;
		$adq_mont_usd = $valor_usd;

		if ($der_fech_temp == "") {
			$der_fech_temp = "1900-01-01";
		}

		if ($calcular_transfer_urbano) {
			$id_inmu_update = $id_inmu;
			include "siicat_info_inmu_update.php";
		}	 
		### BORRAR PROPIETARIOS ADICIONALES EN INFO_INMU_CONTRIB_ADD
		if ($calcular_transfer_urbano) {
			pg_query("DELETE FROM info_inmu_contrib_add WHERE id_inmu = '$id_inmu'");
		} elseif ($calcular_transfer_rural) {
			pg_query("DELETE FROM info_predio_rural_contrib_add WHERE id_predio_rural = '$id_predio_rural'");
		}	 
		########################################
		#--------------- REGISTRO -------------#
		########################################
		pg_query("INSERT INTO registro (userid, ip, fecha, hora, accion, valor) 
			VALUES ('$user_id','$ip','$fecha','$hora','$accion_reg','$id_item')");			
	}
	include "c:/apache/siicat/igm_boleta_de_pago_transf.php";		   	  
} else {
	include "c:/apache/siicat/igm_transferencia.php";
}

?>