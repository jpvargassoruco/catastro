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
	 $cod_uv = $cod_man = $cod_lote = $cod_subl = "";
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
	 $cod_uv = trim($_POST["cod_uv"]); 
   $cod_man = trim($_POST["cod_man"]);
   $cod_lote = trim($_POST["cod_lote"]);
   $cod_subl = trim($_POST["cod_subl"]);	
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
   $act_1pat = utf8_decode(ucase(strtoupper(trim($_POST["act_1pat"]))));
   $act_1mat = utf8_decode(ucase(strtoupper(trim($_POST["act_1mat"]))));
   $act_1nom1 = utf8_decode(ucase(strtoupper(trim($_POST["act_1nom1"]))));
   $act_1nom2 = utf8_decode(ucase(strtoupper(trim($_POST["act_1nom2"]))));
   $act_1ci = utf8_decode(ucase(strtoupper(trim($_POST["act_1ci"]))));
   $act_dpto = $_POST["act_dpto"];	
   $act_ciu = utf8_decode(ucase(strtoupper(trim($_POST["act_ciu"]))));
   $act_dir = utf8_decode(ucase(strtoupper(trim($_POST["act_dir"]))));
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
	 # CHEQUEAR UBICACION DE LA ACTIVIDAD
	## $error1 = true;		 
	# if ($check == 0) {
	#    $mensaje_de_error1 = "Error: No existe ningun lote con ese código en la base de datos! Tiene que agregar primero la información del lote.";
  # } else
   $error1 = true;
	 if (($cod_uv == "") OR ($cod_uv < 1) OR ($cod_uv > 99) OR (strlen($cod_uv) > 2) OR (!check_int($cod_uv))) {
	    $mensaje_de_error1 = "Error: El número de la Unidad Vecinal (U.V.) tiene que tener un valor entre 1 y 99!";	 
   } elseif (($cod_man == "") OR ($cod_man < 1) OR ($cod_man > 999) OR (strlen($cod_man) > 3)OR  (!check_int($cod_man))) {
      $mensaje_de_error1 = "Error: El número del Manzano tiene que tener un valor entre 1 y 999!";
   } elseif (($cod_lote == "") OR ($cod_lote < 1) OR ($cod_lote > 99) OR (strlen($cod_lote) > 2) OR (!check_int($cod_lote))) {	
		  $mensaje_de_error1 = "Error: El número del Lote tiene que tener un valor entre 1 y 99!";
   } elseif (($cod_subl == "") OR ($cod_subl < 0) OR ($cod_subl > 99) OR (strlen($cod_subl) > 2) OR (!check_int($cod_subl))) {	
      $mensaje_de_error1 = "Error: El número del Sub-Lote tiene que tener un valor entre 0 y 99!";	
	 } elseif (pg_num_rows(pg_query("SELECT cod_uv FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'")) == 0) {
	    $mensaje_de_error1 = "Error: No existe ningun lote con ese código en la base de datos! Tiene que agregar primero la información del lote.";	 	
	 } else $error1 = false;	
   # CHEQUEAR DATOS DE LA ACTIVIDAD
	 $error2 = true; 
	 if (!check_int($act_nit)) {
	    $mensaje_de_error2 = "Error: Letras no se permite para el número NIT, solamente números!";
	 } elseif (!check_int($act_pat)) {
	    $mensaje_de_error2 = "Error: Letras no se permite para el número de patente, solamente números!";			
   } elseif (!check_fecha($act_fech,$dia_actual,$mes_actual,$ano_actual)) {
	       $mensaje_de_error2 = "Error: La fecha ingresada no es válida o no tiene el formato correcto. Formatos válidos son DD/MM/AAAA o AAAA-MM-DD!";	 
	 } elseif (!check_float($act_sup)) {
	    $mensaje_de_error2 = "Error: Letras no se permite para la superficie, solamente números (usando un PUNTO como separador decimal)!";	
	 } elseif (($_POST["submit"] == "Registrar") AND (pg_num_rows(pg_query("SELECT cod_uv FROM patentes WHERE cod_geo = '$cod_geo' AND act_pat = '$act_pat'")) > 0)) {
	    $mensaje_de_error2 = "Error: Ya existe una actividad económica con ese número de patente en el distrito!";	 	
   } else $error2 = false;
   # CHEQUEAR IDENTIFICACION DEL PROPIETARIO
	 $error3 = true; 
   if (($act_1pat == "") AND ($act_1mat != "")) {	  
      $mensaje_de_error3 = "Error: No puede quedar en blanco el Apellido Paterno cuando se rellenó el Apellido Materno!";
   } elseif (($act_1pat == "") AND ($act_1nom1 != "")) {	  
      $mensaje_de_error3 = "Error: No puede quedar en blanco el Apellido Paterno cuando se rellenó el Primer Nombre!";						   
   } elseif (($act_1nom1 == "") AND ($act_1nom2 != "")) {	  
      $mensaje_de_error3 = "Error: No puede quedar en blanco el Primer Nombre cuando se rellenó el Segundo Nombre!";	
   } else $error3 = false;
   # CHEQUEAR DOMICILIO DEL PROPIETARIO
	 $error4 = true; 
   if (($act_ciu == "") AND ($act_dir != "")) {	  
      $mensaje_de_error4 = "Error: No puede quedar en blanco la ciudad de domicilio cuando se rellenó la dirección!";
   } else $error4 = false;	 
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
			$act_1pat_temp = utf8_encode ($act_1pat);$act_1mat_temp = utf8_encode ($act_1mat);
			$act_1nom1_temp = utf8_encode ($act_1nom1);$act_1nom2_temp = utf8_encode ($act_1nom2);
			$act_ciu_temp = utf8_encode ($act_ciu);$act_dir_temp = utf8_encode ($act_dir);	
			$act_obs_temp = utf8_encode ($act_obs);		
			### INGRESAR A LA TABLA ###				 	
			if ($_POST["submit"] == "Registrar") {
			   $reg = "Registrar Patente";
	       $sql = "INSERT INTO patentes (cod_geo,cod_uv,cod_man,cod_lote,act_pat,act_rub,act_raz,act_nit,act_tel,act_fech,
		            act_sup,act_1pat,act_1mat,act_1nom1,act_1nom2,act_1ci,act_dpto,act_ciu,act_dir,act_obs)
				        VALUES ('$cod_geo','$cod_uv','$cod_man','$cod_lote','$act_pat','$act_rub','$act_raz_temp','$act_nit','$act_tel','$act_fech',
		                  '$act_sup','$act_1pat_temp','$act_1mat_temp','$act_1nom1_temp','$act_1nom2_temp','$act_1ci','$act_dpto','$act_ciu_temp','$act_dir_temp','$act_obs_temp')";
	       pg_query($sql);
			}
			### MODIFICAR EN LA TABLA ###
			if ($_POST["submit"] == "Modificar") {
			   $reg = "Modificar Patente";		 
	       $sql = "UPDATE patentes SET cod_geo='$cod_geo',cod_uv='$cod_uv',cod_man='$cod_man',cod_lote='$cod_lote',
				         act_rub='$act_rub',act_raz='$act_raz_temp',act_nit='$act_nit',act_tel='$act_tel',act_fech='$act_fech',
		            act_sup='$act_sup',act_1pat='$act_1pat_temp',act_1mat='$act_1mat_temp',act_1nom1='$act_1nom1_temp',act_1nom2='$act_1nom2_temp',
								act_1ci='$act_1ci',act_dpto='$act_dpto',act_ciu='$act_ciu_temp',act_dir='$act_dir_temp',act_obs='$act_obs_temp' WHERE act_pat='$act_pat'";						
	       pg_query($sql);
			} 			
#echo "$sql";
      ########################################
      #--------------- REGISTRO -------------#
      ########################################
			$accion="";
      $username = get_username($session_id);
      pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		           VALUES ('$username','$ip','$fecha','$hora','$reg','$act_pat')");
   }	
}				

################################################################################
#------------------------------- MODIFICAR PATENTE ----------------------------#
################################################################################	 
if ((isset($_POST["accion"])) AND ($_POST["accion"] == "Modificar")) {
	 $accion = "Modificar";
	 $search_string = "";
	 $act_pat = $_POST["act_pat"];
	 ########################################
	 #----------- RELLENAR TABLAS ----------#
	 ########################################			 
   $sql="SELECT * FROM patentes WHERE act_pat = '$act_pat'"; 
   $result=pg_query($sql);
   $info = pg_fetch_array($result, null, PGSQL_ASSOC);
   $cod_geo = $info['cod_geo'];
   $cod_uv = $info['cod_uv'];
   $cod_man = $info['cod_man'];
   $cod_lote = $info['cod_lote'];
   $cod_subl = $info['cod_subl'];
   $cod_cat = get_codcat($cod_uv,$cod_man,$cod_lote,$cod_subl);
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
}
################################################################################
#----------------------------------- SELECT -----------------------------------#
################################################################################	
if (($accion == "Registrar") OR ($accion == "Modificar") OR ($error1) OR ($error2) OR ($error3) OR ($error4)) {
   include "c:/apache/siicat/siicat_form_vehic.php";
} else {
   include "c:/apache/siicat/siicat_vehic_resultado.php";				 
}

?>