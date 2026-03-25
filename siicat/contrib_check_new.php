<?php

$error = $error1 = $error2 = $error3 = $error4 = $error5 = false;
$delete_file = false;
$accion = "";
$cambio_de_codigo = false;
$bottom = true;

################################################################################
#--------------------------- AÑADIR CONTRIBUYENTE -----------------------------#
################################################################################	 
if (($mod == 122) and (!(isset($_POST["submit"])))) {
	$accion = "Registrar";
	$disabled = "";

	$con_form = $con_num = $con_pmc = $pmc_ant = "";
	$con_tipo = "PER";
	$con_raz_form = $con_pat_form = $con_mat_form = $con_nom1_form = $con_nom2_form = "";
	$con_fech = getdate();
	$tit_pers = "UNI";
	$con_fech_ini = date("Y-m-d");
	$con_nit_form = $con_tel = "";
	$doc_tipo = "CI";
	$doc_num = "";
	$doc_exp = $depart_2digit;
	$con_fecnac_form = "";
	$con_fecnac = date("Y-m-d");
	$con_eciv_form = "NIN";
	$dom_dpto = $depart_3digit;
	$dom_ciu = $dom_ciu_form = $dom_ciu_default;
	$dom_bar = $dom_bar_form = "";
	$dom_tipo = "C";
	$dom_nom_form = $dom_num = $dom_edif_form = $dom_bloq = $dom_piso = $dom_apto = "";
	$med_agu = $med_luz = "";
	$con_obs_form = "";

	$con_eciv_select_nin = pg_escape_string('selected=\"selected\"');
	$con_eciv_select_sol = $con_eciv_select_cas = $con_eciv_select_div = $con_eciv_select_viu = "";
}

################################################################################
#---------------------- TOMAR VALORES INGRESADO POR TECLADO -------------------#
################################################################################	 
if (($mod == 122) and ((isset($_POST["submit"])) and (($_POST["submit"] == "Registrar")))) {
	$con_form = trim($_POST["con_form"]);
	$con_pmc = trim($_POST["con_pmc"]);
	$pmc_ant = trim($_POST["pmc_ant"]);
	$con_tipo = $con_tipo_temp = $_POST["con_tipo"];
	$con_raz = strtoupper(trim($_POST["con_raz"]));
	$con_raz_form = utf8_decode($con_raz);
	$con_nit = $con_nit_temp = $con_nit_form = trim($_POST["con_nit"]);

	if (($con_nit == "") or ($con_nit == NULL)) {
		$con_nit = -1;
		$con_nit_temp = 0;
		$con_nit_form = "";
	}

	$con_pat = strtoupper(trim($_POST["con_pat"]));
	$con_pat_form = utf8_decode($con_pat);

	$con_mat = strtoupper(trim($_POST["con_mat"]));
	$con_mat_form = utf8_decode($con_mat);

	$con_nom1 = strtoupper(trim($_POST["con_nom1"]));
	$con_nom1_form = utf8_decode($con_nom1);

	$con_nom2 = strtoupper(trim($_POST["con_nom2"]));
	$con_nom2_form = utf8_decode($con_nom2);

	$con_cas = trim($_POST["con_cas"]);
	$con_cas_form = utf8_decode($con_cas);
	
	$doc_tipo = $_POST["doc_tipo"];
	$doc_num = trim($_POST["doc_num"]);
	$doc_exp = $_POST["doc_exp"];
	if ($doc_num == "") {
		$doc_tipo = $doc_exp = "";
	}
	$con_fecnac = $con_fecnac_temp = $_POST["con_fecnac"];

	$con_eciv = $_POST["con_eciv"];
	$dom_dpto = $_POST["dom_dpto"];
	$dom_ciu = strtoupper(trim($_POST["dom_ciu"]));
	$dom_ciu_form = utf8_decode($dom_ciu);
	$dom_bar = strtoupper(trim($_POST["dom_bar"]));
	$dom_bar_form = utf8_decode($dom_bar);
	$dom_tipo = $_POST["dom_tipo"];
	$dom_nom = strtoupper(trim($_POST["dom_nom"]));
	$dom_nom_form = utf8_decode($dom_nom);
	if ($dom_nom == "") {
		$dom_tipo = "";
	}
	$dom_num = trim($_POST["dom_num"]);
	$dom_edif = strtoupper(trim($_POST["dom_edif"]));
	$dom_edif_form = utf8_decode($dom_edif);
	$dom_bloq = trim($_POST["dom_bloq"]);
	$dom_piso = trim($_POST["dom_piso"]);
	$dom_apto = trim($_POST["dom_apto"]);
	$con_tel = trim($_POST["con_tel"]);
	$med_agu = trim($_POST["med_agu"]);
	$med_luz = trim($_POST["med_luz"]);
	$con_obs = strtoupper(trim($_POST["con_obs"]));
	$con_obs_form = utf8_decode($con_obs);
	$con_fech_ini = date("Y-m-d");
	$tit_pers = "UNI";
	########################################
	#-------- CHEQUEAR POR ERRORES --------#
	########################################	

	$error1 = true;
	if  ($con_pmc == "") {
		$sql = "SELECT con_pmc FROM contribuyentes WHERE con_pmc = '9999999'";
	} else {
		$sql = "SELECT con_pmc FROM contribuyentes WHERE con_pmc = '$con_pmc'";
	}

	if ((check_int($pmc_ant)) and ($pmc_ant > 0)) {
		$sql2 = "SELECT con_pmc FROM contribuyentes WHERE con_pmc = '$pmc_ant' AND con_act = '1'";
	} else {
		$sql2 = "SELECT con_pmc FROM contribuyentes WHERE  con_pmc = '9999999'";
	}

	if (pg_num_rows(pg_query($sql)) > 0) {
		$mensaje_de_error1 = "Error: Ya existe un contribuyente con ese padron municipal en la base de datos!";
	} elseif (pg_num_rows(pg_query($sql2)) > 0) {
		$mensaje_de_error1 = "Error: No se puede ingresar el padron antiguo porque existe un registro activo con ese codigo!";
	} else {
		$error1 = false;
	}


	### CHEQUEAR RAZON SOCIAL, NIT Y NOMBRE ###	
	$error2 = true;
	# GENERAR SQL SI YA EXISTE LA RAZON SOCIAL
	$sql1 = "SELECT id_contrib FROM contribuyentes WHERE con_raz = '$con_raz' AND con_tipo != 'PER'";
	# GENERAR SQL SI YA EXISTE EL NOMBRE
	$sql2 = "SELECT id_contrib FROM contribuyentes WHERE con_tipo = 'PER' AND doc_num = '$doc_num'";
	$sql3 = "SELECT id_contrib FROM contribuyentes WHERE con_pat = '$con_pat' AND con_mat = '$con_mat' AND con_nom1 = '$con_nom1' AND con_nom2 = '$con_nom2'";

	if (($con_tipo == "PER") and ($con_pat == "")) {
		$mensaje_de_error2 = "Error: Si el contribuyente es persona natural, el Apellido Paterno no puede quedar en blanco!";
	} elseif (($con_tipo != "PER") and (pg_num_rows(pg_query($sql1)) > 0)) {
		if ($con_raz == ""){
			$mensaje_de_error2 = "Error: La razon social no tiene que estar en blanco !";
		}else{
			$mensaje_de_error2 = "Error: Ya existe un contribuyente con esa razon social en la base de datos!";
		}
	} elseif ($con_tipo == "EMP" AND !check_int($con_nit_temp)) {
		$mensaje_de_error2 = "Error: El NIT tiene que ser un numero!";
	} elseif (($con_pat == "") and ($con_mat != "")) {
		$mensaje_de_error2 = "Error: No puede quedar en blanco el Apellido Paterno cuando se rellene el Apellido Materno!";
	} elseif (($con_pat == "") and ($con_nom1 != "")) {
		$mensaje_de_error2 = "Error: No puede quedar en blanco el Apellido Paterno cuando se rellene el Primer Nombre!";
	} elseif (($con_nom1 == "") and ($con_nom2 != "")) {
		$mensaje_de_error2 = "Error: No puede quedar en blanco el Primer Nombre cuando se rellene el Segundo Nombre!";
	} elseif (($con_tipo == "EMP") and ($con_raz == "")) {
		$mensaje_de_error2 = "Error: No puede quedar en blanco el Razon Social si el tipo de contribuyente es una empresa!";
	} elseif (($con_tipo == "EMP") and ($con_pat == "")) {
		$mensaje_de_error2 = "Error: Tiene que ingresar el nombre del representante de la empresa!";
	} else {
		$error2 = false;
	}
	# CHEQUEAR IDENTIFICACION
	$error3 = true;
	$sql = "SELECT id_contrib FROM contribuyentes WHERE doc_num = '$doc_num'";
	
	if (($doc_num == "") and ($con_tipo == "PER")) {
		$mensaje_de_error3 = "Error: No se puede registrar el contribuyente si no tiene un documento de identificación!";
	} elseif (pg_num_rows(pg_query($sql)) > 0) {
		$mensaje_de_error3 = "Error: El numero de carnet de identidad ya existe registrado!";
	}else{
		$error3 = false;
	}
	
	# CHEQUEAR DOMICILIO DEL PROPIETARIO
	$error4 = true;
	if (($dom_ciu == "") and ($dom_bar != "")) {
		$mensaje_de_error4 = "Error: No puede quedar en blanco la ciudad de domicilio cuando se rellené el campo para barrio!";
	} elseif (($dom_ciu == "") and ($dom_nom != "")) {
		$mensaje_de_error4 = "Error: No puede quedar en blanco la ciudad de domicilio cuando se rellené el campo para el nombre de calle!";
	} elseif (($dom_nom == "") and ($dom_num != "")) {
		$mensaje_de_error4 = "Error: No puede quedar en blanco el nombre de calle cuando se rellené el campo para el número de calle!";
	} elseif (($dom_piso == "") and ($dom_apto != "")) {
		$mensaje_de_error4 = "Error: No puede quedar en blanco el piso cuando se rellené el campo para el número de apartamento!";
	} else {
		$error4 = false;
	}
		
	########################################
	#----------- RELLENAR TABLAS ----------#
	########################################	
	if ((!$error1) and (!$error2) and (!$error3) and (!$error4)) {
			
		if ($con_tipo == "PER") {
			if ($_POST["submit"] == "Registrar") {
				$sql = "SELECT id_contrib 
				FROM contribuyentes 
				WHERE con_pat = '$con_pat' AND con_mat = '$con_mat' AND con_nom1 = '$con_nom1' AND con_nom2 = '$con_nom2'";
			} else {
				$sql = "SELECT id_contrib 
				FROM contribuyentes 
				WHERE con_pat = '$con_pat' AND con_mat = '$con_mat' AND con_nom1 = '$con_nom1' AND con_nom2 = '$con_nom2' AND id_contrib != '$id_contrib'";
			}
			if (pg_num_rows(pg_query($sql)) == 1) {
				$result = pg_query($sql);
				$info = pg_fetch_array($result, null, PGSQL_ASSOC);
				$id_contrib_igual = $info["id_contrib"];
				pg_free_result($result);
				if ($con_obs == "") {
					$con_obs = "EXISTE UN CONTRIBUYENTE CON EL MISMO NOMBRE (PMC: $id_contrib_igual).-";
				} else {
					$con_obs = $con_obs . " EXISTE UN CONTRIBUYENTE CON EL MISMO NOMBRE (PMC: $id_contrib_igual).-";
				}
			} elseif (pg_num_rows(pg_query($sql)) > 1) {
				$result = pg_query($sql);
				$primer_id = true;
				while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
					foreach ($line as $col_value) {
						if ($primer_id) {
							$id_contrib_iguales = $col_value;
							$primer_id = false;
						} else {
							$id_contrib_iguales = $id_contrib_iguales . ", " . $col_value;
						}
					}
					$i++;
				} # END_OF_WHILE	
				if ($con_obs == "") {
					$con_obs = "EXISTEN AL MENOS DOS CONTRIBUYENTES MAS CON EL MISMO NOMBRE (PMCS SON: $id_contrib_iguales).-";
				} else {
					$con_obs = $con_obs . " EXISTEN AL MENOS DOS CONTRIBUYENTES MAS CON EL MISMO NOMBRE (PMCS SON: $id_contrib_iguales).-";
				}
			}
		}
		### REGISTRAR NUEVO CONTRIBUYENTE ###		

		if ($_POST["submit"] == "Registrar") {
			if ($con_raz == "") {
				$con_raz = $con_pat;
			}
			if (pg_num_rows(pg_query("SELECT id_contrib FROM contribuyentes")) > 0) {
				$sql = "SELECT id_contrib FROM contribuyentes ORDER BY id_contrib DESC LIMIT 1";
				$result = pg_query($sql);
				$info = pg_fetch_array($result, null, PGSQL_ASSOC);
				$id_contrib = $info['id_contrib'];
				$id_contrib++;
				pg_free_result($result);
			} else {
				$id_contrib = 1;
			}
				
			if (pg_num_rows(pg_query("SELECT con_pmc FROM contribuyentes")) > 0) {
				$sql = "SELECT con_pmc FROM contribuyentes ORDER BY con_pmc DESC LIMIT 1";
				$result = pg_query($sql);
				$info = pg_fetch_array($result, null, PGSQL_ASSOC);
				$con_pmc = (int) $info['con_pmc'];
				$con_pmc++;
				pg_free_result($result);
			} else
				$con_pmc = 1;
			$con_act = 1;
			$con_fech_ini_temp = $fecha;
			### INSERTAR DATOS EN TABLA ###
			$reg = "Registrar Contribuyente";
			$pmc_nuevo = -1;
			$con_fech_fin_temp = "1900-01-01";
			$sql_contrib = "INSERT INTO contribuyentes 
					(id_contrib,con_pmc,pmc_ant,con_act,con_fech_ini,con_tipo,con_raz,con_pat,con_mat,
							con_nom1,con_nom2,con_nit,con_tel,doc_tipo,doc_num,doc_exp,con_fecnac,dom_dpto,dom_ciu, 
					dom_bar,dom_tipo,dom_nom,dom_num,dom_edif,dom_bloq,dom_piso,dom_apto,con_obs,tit_pers,con_cas) 
					VALUES
					('$id_contrib','$con_pmc','$pmc_ant','$con_act','$con_fech_ini','$con_tipo','$con_raz','$con_pat','$con_mat',
							'$con_nom1','$con_nom2','$con_nit','$con_tel','$doc_tipo','$doc_num','$doc_exp','$con_fecnac','$dom_dpto','$dom_ciu', 
					'$dom_bar','$dom_tipo','$dom_nom','$dom_num','$dom_edif','$dom_bloq','$dom_piso','$dom_apto','$con_obs','$tit_pers', '$con_cas')";
			$Resp = pg_query($sql_contrib);

			if ($Resp) {
				$mensaje_de_error5 = "Nota: Los datos se grabaron con exito.";
				$error5 = true;
				$bottom = false;
			}else{
				$error5 = true;
				$mensaje_de_error5 = "Error: Ocurrio un error al grabar los datos";
				$error = pg_last_error($conexion);
    			echo "Error: " . $error . "\n";
			}
		}
		########################################
		#--------------- REGISTRO -------------#
		########################################
		if (!$error5) {
			pg_query("INSERT INTO registro (userid, ip, fecha, hora, accion, valor) 
		           VALUES ('$user_id','$ip','$fecha','$hora','$reg','$id_contrib')");
		}
	}
}

################################################################################
#----------------------------------- SELECT -----------------------------------#
################################################################################	
if (($accion == "Registrar") or  ($error1) or ($error2) or ($error3) or ($error4) or ($error5)) {
	include "contrib_formulario_new2.php";
} else {
	include "siicat_contrib_resultado.php";
}

?>