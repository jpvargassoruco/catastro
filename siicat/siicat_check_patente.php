<?php

$error = $error1 = $error2 = $error3 = $error4 = false;
$delete_file = false;
$accion = "";
$cambio_de_codigo = false;
################################################################################
#------------------------------- AÑADIR PATENTE --------------------------------#
################################################################################	 
if (($mod == 102) AND (!(isset($_POST["submit"])))) {
	# $error1 = true;	
   $accion = "Registrar";	 
	 $disabled = "";
	
	 $act_rub = $act_pat = "1";	
	 $act_raz = $act_nit = $act_tel = $act_fech = $act_sup = "";
	 $cod_uv = $cod_man = $cod_pred = $cod_blq = $cod_piso = $cod_apto = "";
   $act_1pat = $act_1mat = $act_1nom1 = $act_1nom2 = $act_1ci = "";	 
	 $act_dpto = "SCZ"; $act_ciu = "Concepción"; $act_dir = ""; $act_obs = "";
}
################################################################################
#------------------------------- MODIFICAR DATOS ------------------------------#
################################################################################	 
if ((isset($_POST["submit"])) AND ($_POST["submit"] == "Modificar")) {
	 $accion = "Modificar";  
}
################################################################################
#----------------------- CHEQUEAR VALORES TRANSMITIDOS ------------------------#
################################################################################	 
if (($mod == 102) AND ((isset($_POST["submit"])) AND (($_POST["submit"] == "Registrar") OR ($_POST["submit"] == "Modificar")))) {
   $distrito = $_POST["distrito"];
	 $cod_uv = $cod_uv_temp = trim($_POST["cod_uv"]);
   $cod_man = $cod_man_temp = trim($_POST["cod_man"]);
   $cod_pred = $cod_pred_temp = trim($_POST["cod_pred"]);
   $cod_blq = $cod_blq_temp = trim($_POST["cod_blq"]);
   $cod_piso = $cod_piso_temp = trim($_POST["cod_piso"]);		 	 
   $cod_apto = $cod_apto_temp = trim($_POST["cod_apto"]);	
   $act_rub = $_POST["act_rub"];
   $act_raz = utf8_decode(strtoupper(trim($_POST["act_raz"])));	
	 $act_nit = trim($_POST["act_nit"]);  
	 $act_pat = trim($_POST["act_pat"]);
   $act_tel = utf8_decode(trim($_POST["act_tel"]));	
	 $act_fech = trim($_POST["act_fech"]);
	 if ($act_fech == "") {
	    $act_fech = $fecha;
   } 	 	  
	 $act_sup = trim($_POST["act_sup"]);
	 $id_contrib = $_POST["id_contrib"];	  	 
   #$act_1pat = utf8_decode(ucase(strtoupper(trim($_POST["act_1pat"]))));
   #$act_1mat = utf8_decode(ucase(strtoupper(trim($_POST["act_1mat"]))));
   #$act_1nom1 = utf8_decode(ucase(strtoupper(trim($_POST["act_1nom1"]))));
   #$act_1nom2 = utf8_decode(ucase(strtoupper(trim($_POST["act_1nom2"]))));
   #$act_1ci = utf8_decode(ucase(strtoupper(trim($_POST["act_1ci"]))));
   #$act_dpto = $_POST["act_dpto"];	
   #$act_ciu = utf8_decode(ucase(strtoupper(trim($_POST["act_ciu"]))));
 #$act_dir = utf8_decode(ucase(strtoupper(trim($_POST["act_dir"]))));
   $act_obs = utf8_decode(ucase(strtoupper(trim($_POST["act_obs"]))));	 	
	 ########################################
	 #---------- DEFINIR COD_GEO -----------#
	 ########################################		 
	 if ($distrito == "Concepción") {
	    $cod_geo = "07-11-01-01";
	 }
	 ########################################
	 #-------- CHEQUEAR POR ERRORES --------#
	 ########################################	
	# $sql="SELECT cod_uv FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'";
  # $check = pg_num_rows(pg_query($sql));
	 
	 # CHEQUEAR SI LA ACTIVIDAD TIENE UNA UBICACION FIJA
	 if (($cod_uv == "") AND ($cod_man == "") AND ($cod_pred == "") AND ($cod_blq == "") AND ($cod_piso == "") AND ($cod_apto == "")) {    
			$act_ubi_fija = false;
      $cod_uv_temp = $cod_man_temp = $cod_pred_temp = $cod_blq_temp = $cod_piso_temp = $cod_apto_temp = -1;			
	    $id_inmu = -1;
			$check_inmu = 1;
	 } else {
	    $act_ubi_fija = true;
			$sql = "SELECT id_inmu FROM info_inmu WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_temp' AND cod_man = '$cod_man_temp' AND cod_pred = '$cod_pred_temp' AND cod_blq = '$cod_blq_temp' AND cod_piso = '$cod_piso_temp' AND cod_apto = '$cod_apto_temp'";
      $check_inmu = pg_num_rows(pg_query($sql));
			if ($check_inmu == 1) {
			   $result=pg_query($sql);
         $info = pg_fetch_array($result, null, PGSQL_ASSOC);
         $id_inmu = $info['id_inmu'];
			}
	 }
	 # CHEQUEAR SI YA EXISTE LA RAZON SOCIAL
	 $sql = "SELECT id_patente FROM patentes WHERE act_raz = '$act_raz'";
   $check_actraz = pg_num_rows(pg_query($sql));	

	## $error1 = true;		 
	# if ($check == 0) {
	#    $mensaje_de_error1 = "Error: No existe ningun lote con ese código en la base de datos! Tiene que agregar primero la información del lote.";
  # } else
   $error1 = true;
	 if (($cod_uv == "") AND (($cod_man != "") OR ($cod_pred != ""))) {
	    $mensaje_de_error1 = "Error: Tiene que ingresar un valor para la U.V.!";	 	 	 
	 } elseif ((!check_int($cod_uv)) AND ($act_ubi_fija)) {
	    $mensaje_de_error1 = "Error: El valor para la Unidad Vecinal (U.V.) tiene que ser un número!";	 
   } elseif ((!check_int($cod_man)) AND ($act_ubi_fija)) {
      $mensaje_de_error1 = "Error: El valor para el Manzano tiene que ser un número!";
   } elseif ((!check_int($cod_pred)) AND ($act_ubi_fija)) {
		  $mensaje_de_error1 = "Error: El valor para el Predio tiene que ser un número!";
#	 } elseif ((pg_num_rows(pg_query("SELECT cod_uv FROM info_inmu WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_temp' AND cod_man = '$cod_man_temp' AND cod_pred = '$cod_pred_temp' AND cod_blq = '$cod_blq_temp' AND cod_piso = '$cod_piso_temp' AND cod_apto = '$cod_apto_temp'")) == 0) AND ($act_ubi_fija)) {
	 } elseif ($check_inmu == 0) {
	    $mensaje_de_error1 = "Error: No existe ningun inmueble con ese código en la base de datos! Tiene que agregar primero la información del inmueble.";	 	
	 } else $error1 = false;	
   # CHEQUEAR DATOS DE LA ACTIVIDAD
	 $error2 = true;
	 if ($check_actraz > 0) {
	    $mensaje_de_error2 = "Error: Tiene que especificar un nombre único para la actividad (Razon Social)!";	  
	 } elseif ((!check_int($act_nit)) AND ($act_nit != NULL)) {
	    $mensaje_de_error2 = "Error: Letras no se permite para el número NIT, solamente números!";
	 } elseif (!check_int($act_pat)) {
	    $mensaje_de_error2 = "Error: Letras no se permite para el número de patente, solamente números!";			
   } elseif (!check_fecha($act_fech,$dia_actual,$mes_actual,$ano_actual)) {
	       $mensaje_de_error2 = "Error: La fecha ingresada no es válida o no tiene el formato correcto. Formatos válidos son DD/MM/AAAA o AAAA-MM-DD!";	 
	 } elseif ((!check_float($act_sup)) AND ($act_sup != NULL)) {
	    $mensaje_de_error2 = "Error: Letras no se permite para la superficie, solamente números (usando un PUNTO como separador decimal)!";	
	 } elseif (($_POST["submit"] == "Registrar") AND (pg_num_rows(pg_query("SELECT id_patente FROM patentes WHERE act_pat = '$act_pat'")) > 0)) {
	    $mensaje_de_error2 = "Error: Ya existe una actividad económica con ese número de patente en el distrito!";	 	
   } else $error2 = false;
   # CHEQUEAR IDENTIFICACION DEL PROPIETARIO
	 $error3 = true; 
   if ($id_contrib == "") {	  
      $mensaje_de_error3 = "Error: No ha elegido ningun propietario de la lista de los contribuyentes!"; 
   } else $error3 = false;
   # CHEQUEAR DOMICILIO DEL PROPIETARIO
	 $error4 = true; 
   /*if (($act_ciu == "") AND ($act_dir != "")) {	  
      $mensaje_de_error4 = "Error: No puede quedar en blanco la ciudad de domicilio cuando se rellenó la dirección!";
   } else */
	 $error4 = false;	 
	 ########################################
	 #----------- RELLENAR TABLAS ----------#
	 ########################################		
	 if ((!$error1) AND (!$error2) AND (!$error3) AND (!$error4)) {
	    ### ADECUACION DE NULL DATA ###
	    if ($act_nit == "") {
	       $act_nit = "-1";
      } 	
			if ($act_sup == "") {
	       $act_sup = "-1";
      }
			### ENCODIFICAR ###		
			$act_raz_temp = utf8_encode ($act_raz);
	#		$act_1pat_temp = utf8_encode ($act_1pat);$act_1mat_temp = utf8_encode ($act_1mat);
		#	$act_1nom1_temp = utf8_encode ($act_1nom1);$act_1nom2_temp = utf8_encode ($act_1nom2);
		#	$act_ciu_temp = utf8_encode ($act_ciu);$act_dir_temp = utf8_encode ($act_dir);	
			$act_obs_temp = utf8_encode ($act_obs);		
			### INGRESAR A LA TABLA ###				 	
			if ($_POST["submit"] == "Registrar") {
	       ### PREPARAR ID, PATENTE ###
			   if (pg_num_rows(pg_query("SELECT id_contrib FROM patentes")) > 0) {
			      $sql = "SELECT id_patente FROM patentes ORDER BY id_patente DESC LIMIT 1";
            $result = pg_query($sql);
            $info = pg_fetch_array($result, null, PGSQL_ASSOC);
            $id_patente = $info['id_patente'];
			      $id_patente++;
				    pg_free_result($result);
			   } else $id_patente = 1;
			   if (pg_num_rows(pg_query("SELECT act_pat FROM patentes")) > 0) {
			      $sql = "SELECT act_pat FROM patentes ORDER BY act_pat DESC LIMIT 1";
            $result = pg_query($sql);
            $info = pg_fetch_array($result, null, PGSQL_ASSOC);
            $act_pat = (int) $info['act_pat'];
			      $act_pat++;
				    pg_free_result($result);
			   } else $act_pat = 1;				 
			   $reg = "Registrar Patente";
	       $sql = "INSERT INTO patentes (id_patente,cod_geo,id_inmu,id_contrib,act_pat,act_rub,
				                act_raz,act_nit,act_tel,act_fech,act_sup,act_obs)
				        VALUES ('$id_patente','$cod_geo','$id_inmu','$id_contrib','$act_pat','$act_rub',
								        '$act_raz_temp','$act_nit','$act_tel','$act_fech','$act_sup','$act_obs_temp')";
	       if (!pg_query($sql)) {
#echo $sql;				 
				    $error4 = true;
            $mensaje_de_error4 = "Error: Ocurrió un error en escribir el registro en la base de datos. Por favor, verifique los datos!"; 				 
				 }
			}
			### MODIFICAR EN LA TABLA ###
			if ($_POST["submit"] == "Modificar") {
			   $reg = "Modificar Patente";		 
	       $sql = "UPDATE patentes SET id_contrib='$id_contrib',id_inmu='$id_inmu',act_pat='$act_pat',act_rub='$act_rub',
				        act_raz='$act_raz_temp',act_nit='$act_nit',act_tel='$act_tel',act_fech='$act_fech',act_sup='$act_sup',
								act_obs='$act_obs_temp' WHERE id_patente='$id_patente'";						
	       pg_query($sql);
			} 			
#echo "$sql";
      ########################################
      #--------------- REGISTRO -------------#
      ########################################
			$accion="";
      $username = get_username($session_id);
      pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		           VALUES ('$username','$ip','$fecha','$hora','$reg','$act_pat')");
   }	
}				

################################################################################
#------------------------------- MODIFICAR PATENTE ----------------------------#
################################################################################	 
if ((isset($_POST["accion"])) AND ($_POST["accion"] == "Modificar")) {
	 $accion = "Modificar";
	 $search_string = "";
   $id_patente = $_POST['id_patente'];	 
	 ########################################
	 #----------- RELLENAR TABLAS ----------#
	 ########################################			 
   $sql="SELECT * FROM patentes WHERE id_patente = '$id_patente'"; 
   $result=pg_query($sql);
   $info = pg_fetch_array($result, null, PGSQL_ASSOC);
   $id_contrib = $info['id_contrib'];
   $id_inmu = $info['id_inmu'];
   $act_pat = $info['act_pat'];
   $act_rub = $info['act_rub'];
   $act_raz = utf8_decode($info['act_raz']);
   $act_nit = $info['act_nit'];
	 if ($act_nit == "-1") {
	    $act_nit = "";
   }
   $act_tel = $info['act_tel'];			 
   $act_fech = $info['act_fech'];
	 if ($act_fech == "1900/01/01") {
		  $act_fech = "";
   } else $act_fech = change_date($act_fech);				 
   $act_sup = $info['act_sup'];
   if ($act_sup == "-1") {
      $act_sup = "";
   }	 
   $act_1pat = utf8_decode($info['act_1pat']);
   $act_1mat = utf8_decode($info['act_1mat']);
   $act_1nom1 = utf8_decode($info['act_1nom1']);
   $act_1nom2 = utf8_decode($info['act_1nom2']);
   $act_1ci = $info['act_1ci'];
   $act_dpto = $info['act_dpto'];
   $act_ciu = utf8_decode($info['act_ciu']);
   $act_dir = utf8_decode($info['act_dir']);
   $act_obs = utf8_decode($info['act_obs']);	 
   pg_free_result($result); 
	 ### LEER TABLA INFO_INMU ###		 
   $sql="SELECT * FROM info_inmu WHERE id_inmu = '$id_inmu'"; 
   $result=pg_query($sql);
   $info = pg_fetch_array($result, null, PGSQL_ASSOC);
   $id_contrib = $info['id_contrib'];
   $id_inmu = $info['id_inmu'];
   $act_pat = $info['act_pat'];
   $act_rub = $info['act_rub'];
   $act_raz = utf8_decode($info['act_raz']);
   $act_nit = $info['act_nit'];	 
   pg_free_result($result);	 
	 ### LEER TABLA CONTRIBUYENTES ###		
	 $act_prop = get_contrib ($id_contrib);
	 
   /*$sql="SELECT * FROM contribuyentes WHERE id_contrib = '$id_contrib'"; 
   $result=pg_query($sql);
   $info = pg_fetch_array($result, null, PGSQL_ASSOC);
   $id_contrib = $info['id_contrib'];
   $act_pat = $info['con_pat'];
	 
   $act_rub = $info['act_rub'];
   $act_raz = utf8_decode($info['act_raz']);
   $act_nit = $info['act_nit'];	
   pg_free_result($result);	  */
}
################################################################################
#----------------------------------- SELECT -----------------------------------#
################################################################################	
if (($accion == "Registrar") OR ($accion == "Modificar") OR ($error1) OR ($error2) OR ($error3) OR ($error4)) {
   include "c:/apache/siicat/siicat_form_patente.php";
} else {
   include "c:/apache/siicat/siicat_patentes_resultado.php";				 
}

?>