<?php

$error = $error1 = $error2 = $error3 = $error4 = $error5 = false;
$delete_file = false;
$accion = "";
$cambio_de_codigo = false;
$bottom = true; /* show submit button by default */

/* Buscar contribuyente por documento (CI) y preparar para modificar */
if (($mod == 124) && (isset($_POST["buscar"])) && ($_POST["buscar"] == "Buscar")) {
	$doc_tipo = $_POST["doc_tipo"];
	$doc_num = trim($_POST["doc_num"]);
	$doc_exp = $_POST["doc_exp"];
	if ($doc_num == "") {
		$mensaje_de_error5 = "Error: Ingrese el número de identificación para buscar.";
		$error5 = true;
	} else {
		$sql = "SELECT id_contrib FROM contribuyentes WHERE upper(trim(doc_tipo)) = upper(trim('$doc_tipo')) AND regexp_replace(doc_num, '\\D', '', 'g') = regexp_replace('$doc_num', '\\D', '', 'g') AND upper(trim(doc_exp)) = upper(trim('$doc_exp'))";
		$res = pg_query($sql);
		$cnt = pg_num_rows($res);
		if ($cnt == 1) {
			$info = pg_fetch_array($res, null, PGSQL_ASSOC);
			$id_contrib = $info['id_contrib'];
			pg_free_result($res);
			$accion = "Modificar";
			$_POST["accion"] = "Modificar"; 
			$_POST["id_contrib"] = $id_contrib; // ensure id is available to the modification block		// Load record immediately so form variables are populated for the same request
			$sql2 = "SELECT * FROM contribuyentes WHERE id_contrib = '$id_contrib'";
			$res2 = pg_query($sql2);
			if (pg_num_rows($res2) == 1) {
				$info2 = pg_fetch_array($res2, null, PGSQL_ASSOC);
				$info = $info2; // reutilizar la variable $info para que siicat_contrib_leer_tabla.php funcione si se incluye más adelante
				include "c:/apache/siicat/contrib_leer_tabla.php";
				pg_free_result($res2);
			} else {
				$mensaje_de_error5 = "Error: No se pudo cargar el contribuyente encontrado.";
				$error5 = true;
			}
		} elseif ($cnt > 1) {
			$mensaje_de_error5 = "Error: Se encontraron varios contribuyentes con ese documento. Consulte la lista de contribuyentes.";
			$error5 = true;
			pg_free_result($res);
		} else {
			pg_free_result($res);
			$sql_alt = "SELECT id_contrib, doc_tipo, doc_exp FROM contribuyentes WHERE regexp_replace(doc_num, '\\D', '', 'g') = regexp_replace('$doc_num', '\\D', '', 'g')";
			$res_alt = pg_query($sql_alt);
			$cnt_alt = pg_num_rows($res_alt);

			if ($cnt_alt == 1) {
				$info_alt = pg_fetch_array($res_alt, null, PGSQL_ASSOC);
				$id_contrib = $info_alt['id_contrib'];
				$_POST["accion"] = "Modificar";
				$_POST["id_contrib"] = $id_contrib;
				$accion = "Modificar";
				$mensaje_de_error5 = "Aviso: Se encontró un contribuyente con ese número de documento (tipo/expedido diferente). Cargando ese registro para modificar.";
				$error5 = true; // use error5 to display the message area (it's an informative notice here)
				// Load record immediately so form variables are populated
				$sql2 = "SELECT * FROM contribuyentes WHERE id_contrib = '$id_contrib'";
				$res2 = pg_query($sql2);
				if (pg_num_rows($res2) == 1) {
					$info2 = pg_fetch_array($res2, null, PGSQL_ASSOC);
					$info = $info2;
					include "c:/apache/siicat/contrib_leer_tabla.php";
					pg_free_result($res2);
				} else {
					$mensaje_de_error5 = "Error: No se pudo cargar el contribuyente alternativo encontrado.";
					$error5 = true;
				}
				pg_free_result($res_alt);
			} elseif ($cnt_alt > 1) {
				$mensaje_de_error5 = "Error: Se encontraron varios contribuyentes con ese número de identificación (sin coincidencia exacta de tipo/expedido). Consulte la lista de contribuyentes.";
				$error5 = true;
				pg_free_result($res_alt);
			} else {
				$mensaje_de_error5 = "Error: No se encontró contribuyente con ese número de identificación.";
				$error5 = true;
			}
		}
	}
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
if ((($mod == 124)) AND ((isset($_POST["submit"])) AND (($_POST["submit"] == "Modificar")))) {
	if ($_POST["submit"] == "Modificar") {
		$id_contrib = $_POST["id_contrib"];
	}
	$con_form = trim($_POST["con_form"]);
	$con_pmc = $_POST["id_contrib"];
	$pmc_ant = trim($_POST["pmc_ant"]);
	$con_tipo = $con_tipo_temp = $_POST["con_tipo"];
	$con_raz = ucase(strtoupper(trim($_POST["con_raz"])));
	$con_raz_form = utf8_decode($con_raz);
	$con_nit = $con_nit_temp = $con_nit_form = trim($_POST["con_nit"]);
	if (($con_nit == "") OR ($con_nit == NULL)) {
		$con_nit = -1;
		$con_nit_temp = 0;
		$con_nit_form = "";
	}
	$con_pat = ucase(strtoupper(trim($_POST["con_pat"])));
	$con_pat_form = utf8_decode($con_pat);
	$con_mat = ucase(strtoupper(trim($_POST["con_mat"])));
	$con_mat_form = utf8_decode($con_mat);
	$con_nom1 = ucase(strtoupper(trim($_POST["con_nom1"])));
	$con_nom1_form = utf8_decode($con_nom1);
	$con_nom2 = ucase(strtoupper(trim($_POST["con_nom2"])));
	$con_nom2_form = utf8_decode($con_nom2);
	$doc_tipo = $_POST["doc_tipo"];
	$doc_num = trim($_POST["doc_num"]);
	$doc_exp = $_POST["doc_exp"];
	if ($doc_num == "") {
		$doc_tipo = $doc_exp = "";
	}
	$con_fech_nac = $con_fech_nac_temp = trim($_POST["con_fech_nac"]);
	if ($con_fech_nac == "") {
		$con_fech_nac_temp = "1900-01-01";
		$con_fech_nac_form = "";
	}
	$con_act = $_POST["con_act"];
	$con_eciv = $_POST["con_eciv"];
	$dom_dpto = $_POST["dom_dpto"];
	$dom_ciu = ucase(strtoupper(trim($_POST["dom_ciu"])));
	$dom_ciu_form = utf8_decode($dom_ciu);
	$dom_bar = ucase(strtoupper(trim($_POST["dom_bar"])));
	$dom_bar_form = utf8_decode($dom_bar);
	$dom_tipo = $_POST["dom_tipo"];
	$dom_nom = ucase(strtoupper(trim($_POST["dom_nom"])));
	$dom_nom_form = utf8_decode($dom_nom);
	if ($dom_nom == "") {
		$dom_tipo = "";
	}
	$dom_num = trim($_POST["dom_num"]);
	$dom_edif = ucase(strtoupper(trim($_POST["dom_edif"])));
	$dom_edif_form = utf8_decode($dom_edif);
	$dom_bloq = trim($_POST["dom_bloq"]);
	$dom_piso = trim($_POST["dom_piso"]);
	$dom_apto = trim($_POST["dom_apto"]);
	$con_tel = trim($_POST["con_tel"]);
	$med_agu = trim($_POST["med_agu"]);
	$med_luz = trim($_POST["med_luz"]);
	$con_obs = ucase(strtoupper(trim($_POST["con_obs"])));
	$con_obs_form = utf8_decode($con_obs);

	########################################
	#-------- CHEQUEAR POR ERRORES --------#
	########################################	
	$error1 = true;
	if ($_POST["submit"] == "Modificar") {
		$sql = "SELECT con_pmc FROM contribuyentes WHERE con_pmc = '$con_pmc' AND id_contrib != '$id_contrib'";
	} elseif ($con_pmc == "") {
		$sql = "SELECT con_pmc FROM contribuyentes WHERE con_pmc = '9999999'"; #DUMMY			
	} else {
		$sql = "SELECT con_pmc FROM contribuyentes WHERE con_pmc = '$con_pmc'";
	}
	if ((check_int($pmc_ant)) AND ($pmc_ant > 0)) {
		$sql2 = "SELECT con_pmc FROM contribuyentes WHERE con_pmc = '$pmc_ant' AND con_act = '1'";
	} else {
		$sql2 = "SELECT con_pmc FROM contribuyentes WHERE  con_pmc = '9999999'"; #DUMMY		 
	}
	if (pg_num_rows(pg_query($sql)) > 0) {
		$mensaje_de_error1 = "Error: Ya existe un contribuyente con ese codigo en la base de datos!";
	} elseif (pg_num_rows(pg_query($sql2)) > 0) {
		$mensaje_de_error1 = "Error: No se puede ingresar el padron antiguo porque existe un registro activo con ese c�digo! Debe desactivar el registro antiguo primero o registrar el contribuyente sin padron antiguo y depurar ambos registros despu�s.";
	} else
		$error1 = false;
	### CHEQUEAR RAZON SOCIAL, NIT Y NOMBRE ###
	$error2 = true;
	# GENERAR SQL SI YA EXISTE LA RAZON SOCIAL
	if ($_POST["submit"] == "Modificar") {
		$sql = "SELECT con_raz FROM contribuyentes WHERE id_contrib = '$id_contrib'";
		$result = pg_query($sql);
		$info = pg_fetch_array($result, null, PGSQL_ASSOC);
		$con_raz_ant = ucase(strtoupper(trim($info["con_raz"])));
		pg_free_result($result);
		if ($con_raz != $con_raz_ant) {
			$sql1 = "SELECT id_contrib FROM contribuyentes WHERE con_raz = '$con_raz' AND con_tipo != 'PER'";
		} else {
			$sql1 = "SELECT id_contrib FROM contribuyentes WHERE con_raz = 'NO HA CAMBIADO LA RAZON SOCIAL'";
		}
	} else {
		$sql1 = "SELECT id_contrib FROM contribuyentes WHERE con_raz = '$con_raz' AND con_tipo != 'PER'";
	}
	# GENERAR SQL SI YA EXISTE EL NOMBRE	  
	if ($_POST["submit"] == "Modificar") {
		$sql = "SELECT con_raz, con_pat, con_mat, con_nom1, con_nom2, doc_num FROM contribuyentes WHERE id_contrib = '$id_contrib'";
		$result = pg_query($sql);
		$info = pg_fetch_array($result, null, PGSQL_ASSOC);
		// LEEMOS EL VALOR DE TABLA DEL CONTRIBUYENTE
		$con_raz_ant = ucase(strtoupper(trim($info["con_raz"])));
		$con_pat_ant = ucase(strtoupper(trim($info["con_pat"])));
		$con_mat_ant = ucase(strtoupper(trim($info["con_mat"])));
		$con_nom1_ant = ucase(strtoupper(trim($info["con_nom1"])));
		$con_nom2_ant = ucase(strtoupper(trim($info["con_nom2"])));
		$doc_num_ant = trim($info["doc_num"]);
		pg_free_result($result);
		if (($con_tipo == "EMP") AND ($con_raz != $con_raz_ant)) {
			$sql2 = "SELECT id_contrib FROM contribuyentes WHERE con_raz = '$con_raz'";
		} elseif (($con_tipo == "PER") AND (($con_pat != $con_pat_ant) OR ($con_mat != $con_mat_ant) OR ($con_nom1 != $con_nom1_ant) OR ($con_nom2 != $con_nom2_ant))) {
			$sql2 = "SELECT id_contrib FROM contribuyentes WHERE con_raz = '$con_pat' AND con_pat = '$con_pat'  AND con_mat = '$con_mat' AND con_nom1 = '$con_nom1' AND con_nom2 = '$con_nom2' AND trim(doc_num) = '$doc_num'";
		} else {
			$sql2 = "SELECT id_contrib FROM contribuyentes WHERE con_raz = 'NO HA CAMBIADO EL NOMBRE'";
		}
	} else {
		$sql2 = "SELECT id_contrib FROM contribuyentes WHERE con_pat = '$con_raz' AND con_mat = '$con_mat' AND con_nom1 = '$con_nom1' AND con_nom2 = '$con_nom2' AND con_tipo = 'PER' AND trim(doc_num) = '$doc_num'";
	}
	if (($con_tipo == "PER") AND ($con_pat == "")) {
		$mensaje_de_error2 = "Error: Si el contribuyente es persona natural, el Apellido Paterno no puede quedar en blanco!";
	} elseif (pg_num_rows(pg_query($sql1)) > 0) {
		$mensaje_de_error2 = "Error: Ya existe un contribuyente con esa razon social en la base de datos!";
	} elseif (pg_num_rows(pg_query($sql2)) > 0) {
		$mensaje_de_error2 = "Error: Ya existe un contribuyente con el ese nombre en la base de datos! Si existen dos contribuyentes con el mismo nombre, obligatoriomente hay que especificar el numero de identificacion en el campo abajo.";
	} elseif (!check_int($con_nit_temp)) {
		$mensaje_de_error2 = "Error: El NIT tiene que ser un numero!";
	} elseif (($con_pat == "") AND ($con_mat != "")) {
		$mensaje_de_error2 = "Error: No puede quedar en blanco el Apellido Paterno cuando se rellen� el Apellido Materno!";
	} elseif (($con_pat == "") AND ($con_nom1 != "")) {
		$mensaje_de_error2 = "Error: No puede quedar en blanco el Apellido Paterno cuando se rellen� el Primer Nombre!";
	} elseif (($con_nom1 == "") AND ($con_nom2 != "")) {
		$mensaje_de_error2 = "Error: No puede quedar en blanco el Primer Nombre cuando se rellen� el Segundo Nombre!";
	} elseif (($con_tipo == "Empresa") AND ($con_raz == "")) {
		$mensaje_de_error2 = "Error: No puede quedar en blanco el Raz�n Social si el tipo de contribuyente es una empresa!";
	} elseif (($con_tipo == "Empresa") AND ($con_pat == "")) {
		$mensaje_de_error2 = "Error: Tiene que ingresar el nombre del representante de la empresa!";
	} else
		$error2 = false;
	# CHEQUEAR IDENTIFICACION
	$error3 = true;




	if ($_POST["submit"] == "Modificar") {
		 $sql = "SELECT con_pmc FROM contribuyentes WHERE con_pmc = '$con_pmc' AND id_contrib != '$id_contrib'";
	} else {
		$sql = "SELECT id_contrib FROM contribuyentes WHERE doc_tipo = '$doc_tipo' AND trim(doc_num) = '$doc_num' AND doc_exp = '$doc_exp'";
	}
	if (($doc_num == "") AND ($con_tipo == "PER")) {
		$mensaje_de_error3 = "Error: No se puede registrar el contribuyente si no tiene un documento de identificaci�n!";
	} elseif (!check_fecha($con_fech_nac_temp, $dia_actual, $mes_actual, $ano_actual)) {
		$mensaje_de_error3 = "Error: La fecha ingresada no es v�lida o no tiene el formato correcto. Formatos v�lidos son DD/MM/AAAA o AAAA-MM-DD!";
	} else
		$error3 = false;
	# CHEQUEAR DOMICILIO DEL PROPIETARIO
	$error4 = true;
	if (($dom_ciu == "") AND ($dom_bar != "")) {
		$mensaje_de_error4 = "Error: No puede quedar en blanco la ciudad, domicilio cuando se rellena el campo para barrio!";
	} elseif (($dom_ciu == "") AND ($dom_nom != "")) {
		$mensaje_de_error4 = "Error: No puede quedar en blanco la ciudad de domicilio cuando se rellena el campo para el nombre de calle!";
	} elseif (($dom_nom == "") AND ($dom_num != "")) {
		$mensaje_de_error4 = "Error: No puede quedar en blanco el nombre de calle cuando se rellena el campo para el numero de calle!";
	} elseif (($dom_piso == "") AND ($dom_apto != "")) {
		$mensaje_de_error4 = "Error: No puede quedar en blanco el piso cuando se rellene el campo para el numero de apartamento!";
	} else
		$error4 = false;
	########################################
	#----------- RELLENAR TABLAS ----------#
	########################################			
	// If user clicked Modify but there are validation errors, show a combined message
	if (isset($_POST["submit"]) && $_POST["submit"] == "Modificar") {
		if (($error1) OR ($error2) OR ($error3) OR ($error4)) {
			$mensaje_de_error5 = "Error: Corrija los errores marcados arriba antes de intentar modificar.";
			$error5 = true;
		}
	}
	if ((!$error1) AND (!$error2) AND (!$error3) AND (!$error4)) {
		if ($con_tipo == "PER") {
			$con_raz_form = utf8_decode(ucase(strtoupper(trim($_POST["con_pat"]))));
		} else
			$con_raz_form = $con_raz;
		if ($con_tipo == "PER") {
			if ($_POST["submit"] == "Registrar") {
				$sql = "SELECT id_contrib FROM contribuyentes WHERE con_pat = '$con_pat' AND con_mat = '$con_mat' AND con_nom1 = '$con_nom1' AND con_nom2 = '$con_nom2'";
			} else {
				$sql = "SELECT id_contrib FROM contribuyentes WHERE con_pat = '$con_pat' AND con_mat = '$con_mat' AND con_nom1 = '$con_nom1' AND con_nom2 = '$con_nom2' AND id_contrib != '$id_contrib'";
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
	
		### MODIFICAR EN LA TABLA ###
		if ($_POST["submit"] == "Modificar") {
			$accion = "";
			$reg = "Modificar Contribuyente";
			$id_contrib_update = $id_contrib;
			if ($con_raz == "") {
				$con_raz = $con_pat;
			}
			// Build UPDATE safely: treat numeric fields as NULL when empty and escape strings
			$sql_update = "UPDATE contribuyentes SET con_pmc='$con_pmc',pmc_ant='$pmc_ant',
				con_tipo='$con_tipo',con_raz='$con_raz',con_pat='$con_pat',con_mat='$con_mat',con_nom1='$con_nom1',con_nom2='$con_nom2',con_nit='$con_nit',con_tel='$con_tel',doc_tipo='$doc_tipo',doc_num='$doc_num',doc_exp='$doc_exp', con_fecnac='$con_fech_nac_temp',
				dom_dpto='$dom_dpto',dom_ciu='$dom_ciu',dom_bar='$dom_bar',dom_tipo='$dom_tipo',dom_nom='$dom_nom',	dom_num='$dom_num',dom_edif='$dom_edif',dom_bloq='$dom_bloq',dom_piso='$dom_piso',dom_apto='$dom_apto',med_agu='$med_agu',med_luz='$med_luz',con_obs='$con_obs'
				WHERE id_contrib='$id_contrib_update'";

			if (isset($sql_update) && trim($sql_update) != "") {
				echo "<!-- SQL Update: $sql_update -->\n";
				$res_up = @pg_query($sql_update);
				if ($res_up === false) {
					$error5 = true;
					$accion = "Modificar";
					$mensaje_de_error5 = "Error: No se pudo modificar la base de datos. " . pg_last_error();
				} else {
					$error5 = false;
					$mensaje_de_error5 = "OK: Modificado correctamente. Filas afectadas: " . pg_affected_rows($res_up);
				}
			} else {
				$error5 = true;
				$accion = "Modificar";
				$mensaje_de_error5 = "Error interno: consulta de actualización ausente (sql_update). Contacte con soporte técnico.";
			}

		}
	}

	########################################
	#--------------- REGISTRO -------------#
	########################################
	if (!$error5) {
		#$username = get_username($session_id);
		pg_query("INSERT INTO registro (userid, ip, fecha, hora, accion, valor) 
		           VALUES ('$user_id','$ip','$fecha','$hora','$reg','$id_contrib')");
	}
}

################################################################################
#---------------------------- MODIFICAR CONTRIBUYENTE -------------------------#
################################################################################	 
if ((isset($_POST["accion"])) AND ($_POST["accion"] == "Modificar")) {
	$accion = "Modificar";
	$search_string = "";
	// only override $id_contrib if an id was posted (avoid erasing id from the search result)
	if (isset($_POST["id_contrib"]) && $_POST["id_contrib"] != "") {
		$id_contrib = $_POST["id_contrib"];
	}
}
################################################################################
#---------------------------- LEER DATOS DE TABLA -----------------------------#
################################################################################			 
if (((isset($_POST["accion"])) AND ($_POST["accion"] == "Modificar")) OR ((isset($_POST["submit"])) AND ($_POST["submit"] == "Modificar"))) {
	$sql = "SELECT * FROM contribuyentes WHERE id_contrib = '$id_contrib'";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	include "c:/apache/siicat/contrib_leer_tabla.php";

	#echo "CON_FECH_INI: $con_fech_ini, $con_tipo";
	pg_free_result($result);
}
################################################################################
#----------------------------------- SELECT -----------------------------------#
################################################################################	
/* Mostrar formulario de búsqueda por documento si no se está registrando/modificando */
if (($accion == "") && (!isset($_POST["submit"]))) {
	echo "<table align=\"center\" width=\"600\" class=\"bodyText\">";
	echo "<tr><td align=\"center\" colspan=\"4\"><b>Buscar Contribuyente por Documento</b></td></tr>\n";
	echo "<form method=\"post\" action=\"index.php?mod=124&id=$session_id\">\n";
	echo "<tr>";
	$valores = get_abr('doc_tipo');
	echo "<td align=\"right\">Tipo:</td><td>";
	echo "<select name=\"doc_tipo\" class=\"navText\">\n";
	foreach ($valores as $v) {
		$sel = ($v == $doc_tipo) ? " selected=\"selected\"" : "";
		echo "<option value=\"$v\"$sel>" . abr($v) . "</option>\n";
	}
	echo "</select></td>";
	echo "<td align=\"right\">Nro:</td><td><input type=\"text\" name=\"doc_num\" class=\"navText\" value=\"$doc_num\"></td>";
	$valores = get_abr('doc_exp');
	echo "</tr><tr><td align=\"right\">Expedido en:</td><td>";
	echo "<select name=\"doc_exp\" class=\"navText\">\n";
	foreach ($valores as $v) {
		$sel = ($v == $doc_exp) ? " selected=\"selected\"" : "";
		echo "<option value=\"$v\"$sel>" . abr($v) . "</option>\n";
	}
	echo "</select></td>";
	echo "<td colspan=\"2\" align=\"center\"><button name=\"buscar\" type=\"submit\" value=\"Buscar\" class=\"smallText\">Buscar</button></td>";
	echo "</form>\n";
	if ($error5) {
		echo "<tr><td colspan=\"4\" class=\"alerta alerta-danger\">$mensaje_de_error5</td></tr>\n";
	}
	echo "</table><br />\n";
}

if (($error1) AND ($error2) AND ($error3) AND ($error4) AND ($error5)) {

}else{
	if (($accion == "Registrar") OR ($accion == "Modificar")) {
		include "c:/apache/siicat/contrib_formulario_mod1.php";
	}
}

?>