<style>
/* Fieldset y legend modernos */
fieldset {
    border: 1px solid #007bff;
    border-radius: 8px;
    padding: 20px 30px 15px 30px;
    background: #f8fafd;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    margin-bottom: 25px;
}
legend {
    font-family: 'Segoe UI', Arial, sans-serif;
    font-size: 1.2em;
    font-weight: normal;
    color: #007bff;
    padding: 0 10px;
    letter-spacing: 1px;
}

/* Labels e inputs */
.bodyTextD, .bodyText, label {
    font-family: 'Segoe UI', Arial, sans-serif;
    font-size: 1em;
    color: #333;
}
input[type="text"], select {
    font-family: 'Segoe UI', Arial, sans-serif;
    font-size: 1em;
    padding: 7px 10px;
    border: 1px solid #bdbdbd;
    border-radius: 5px;
    margin-bottom: 8px;
    background: #fff;
    transition: border 0.2s;
}
input[type="text"]:focus, select:focus {
    border: 1.5px solid #007bff;
    outline: none;
}
input[type="submit"], .smallText {
    font-family: 'Segoe UI', Arial, sans-serif;
    font-size: 1em;
    background: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 8px 18px;
    margin-top: 5px;
    cursor: pointer;
    transition: background 0.2s;
    vertical-align: middle;
}
input[type="submit"]:hover, .smallText:hover {
    background: #0056b3;
}

/* Centrado vertical en celdas */
td {
    vertical-align: middle;
}

/* Tabla de resultados moderna */
#registros2 {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    margin-bottom: 20px;
    border-radius: 8px;
    overflow: hidden;
}
#registros2 th {
    padding: 8px 10px;
    border-bottom: 1px solid #b0c4de;
    position: sticky;
    top: 0;
    background: #c6dbf1;  /* Celeste bajito */
    color: #333;
    font-weight: 600;
    letter-spacing: 0.5px;
    border-right: 1px solid #fff;
    z-index: 10;
	font-size: 1.10em; /* <-- Aumenta el tamaño aquí */
}
#registros2 th:last-child {
    border-right: none;
}
#registros2 td {
    padding: 2px 8px;
    border-bottom: none;
    height: 24px;
    text-align: center;
}
#registros2 tr:nth-child(even) {
    background: #f4f8fb;
}
#registros2 tr:hover {
    background: #e3f2fd;
    transition: background 0.2s;
}
#registros2 td:last-child {
    text-align: center;
}
#registros2 input[type="image"] {
    vertical-align: middle;
    transition: transform 0.2s;
}
#registros2 input[type="image"]:hover {
    transform: scale(1.2);
    filter: drop-shadow(0 2px 4px #007bff44);
}
input.navText {
    height: 32px;
    box-sizing: border-box;
}
</style>


<?php
// Parámetros de paginación
$por_pagina = 20;
$pagina_actual = isset($_GET['pag']) ? max(1, intval($_GET['pag'])) : 1;
$inicio = ($pagina_actual - 1) * $por_pagina;

// Solo muestra los resultados de la página actual
$total_paginas = ceil($filas / $por_pagina);

// Mostrar solo los resultados de la página actual
$inicio_tabla = $inicio;
$fin_tabla = min($inicio + $por_pagina, $filas);


$buscar = $buscar_alfa = false;
$error = false;
$resultado = false;
$search_direccion = false;
$search_string = $act_pat = $veh_plc = $con_pmc = $id_tram = "";
$cod_cat = $cod_pat = $nombre = $apellido = $dir_nom = $uv_man = "";
$sql_existe = false;
$string_entre_comillas = false;
$datos_enviados = false;
$example = $stage2 = "";
$aviso_registro_transfer = false;
if (!isset($error)) {

}
if (!isset($resultado)) {

}
################################################################################
#--------------------------- TITULOS SEGUN RUBRO ------------------------------#
################################################################################		
if ($mod == 1) {   ### CATASTRO URBANO
	$pag = 5;
	$nombre_get = "inmu";
	$var_submit = "id_inmu";
	$titulo1 = "Código";
	$titulo2 = "Propietario";
	$titulo3 = "Dirección";
} elseif ($mod == 41) {   ### CATASTRO RURAL
	$pag = 45;
	$nombre_get = "idpr";
	$var_submit = "id_predio_rural";
	$titulo1 = "Código";
	$titulo2 = "Nombre de la Propiedad";
	$titulo3 = "Propietario/Representante";
} elseif ($mod == 101) {   ### PATENTES
	$pag = 105;
	$nombre_get = "id_pat";
	$var_submit = "id_patente";
	$titulo1 = "No. Patente";
	$titulo2 = "Razon Social";
	$titulo3 = "Propietario/Representante";
} elseif ($mod == 111) {   ### VEHICULOS
	$pag = 115;
	$nombre_get = "idv";
	$var_submit = "veh_plc";
	$titulo1 = "No. Patente";
	$titulo2 = "Propietario";
	$titulo3 = "Razon Social";
} elseif ($mod == 121) {   ### CONTRIBUYENTES
	$pag = 125;
	$nombre_get = "con";
	$var_submit = "id_contrib";
	$titulo1 = "P.M.C.";
	$titulo2 = "Contribuyente/Razon Social";
	$titulo3 = "Documentación";
} elseif ($mod == 131) {   ### TRAMITES
	$pag = 135;
	$nombre_get = "tram";
	$var_submit = "id_tram";
	$titulo1 = "No. Tramite";
	$titulo2 = "Contribuyente/Razon Social";
	$titulo3 = "Concepto";
}
################################################################################
#--------------- CATASTRO RURAL: LISTA DE CODIGOS GEOGRAFICOS -----------------#
################################################################################		 
if ($mod == 41) {   ### CATASTRO RURAL
	$sql = "SELECT DISTINCT cod_geo FROM info_predio_rural ORDER BY cod_geo ASC";
	$result = pg_query($sql);
	$i = $j = 0;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		foreach ($line as $col_value) {
			$cod_geo_lista[$j] = $col_value;
		}
		$j++;
	}
	$no_de_cod_geos = $j;
	pg_free_result($result);
}
################################################################################
#-------------------- CATASTRO URBANO: BUSQUEDA 1 ENVIADO ---------------------#
################################################################################		 
if (((isset($_POST["busqueda1"])) or (isset($_POST["busqueda_alfa"]))) and ($mod == 1)) {
	$buscar = true;
	$i = 0;
	if (check_int($cod_uv)) {
		$busqueda_sql[$i] = "cod_uv = '$cod_uv'";
		$i++;
	} else
		$cod_uv = "";
	if (check_int($cod_man)) {
		$busqueda_sql[$i] = "cod_man = '$cod_man'";
		$i++;
	} else
		$cod_man = "";
	if (check_int($cod_pred)) {
		$busqueda_sql[$i] = "cod_pred = '$cod_pred'";
		$i++;
	} else
		$cod_pred = "";
	if ($cod_blq != "") {
		$busqueda_sql[$i] = "cod_blq = '$cod_blq'";
		$i++;
	} else
		$cod_blq = "";
	if ($cod_piso != "") {
		$busqueda_sql[$i] = "cod_piso = '$cod_piso'";
		$i++;
	} else
		$cod_piso = "";
	if ($cod_apto != "") {
		$busqueda_sql[$i] = "cod_apto = '$cod_apto'";
		$i++;
	} else
		$cod_apto = "";

	$no_de_criterios = $i;
	########################################
	#--------- GENERAR SQL-STRING ---------#
	########################################
	if ($no_de_criterios > 0) {
		$where = $busqueda_sql[0];
		$i = 1;
		while ($i < $no_de_criterios) {
			$where = $where . " AND " . $busqueda_sql[$i];
			$i++;
		}
		$sql = "SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id
	           FROM info_inmu WHERE cod_geo = '$cod_geo' AND $where ORDER BY cod_uv, cod_man, cod_pred";			 
		########################################
		#---------- CHEQUEAR TABLA ------------#
		########################################
		$check_integrity = pg_num_rows(pg_query($sql));
		if ($check_integrity > 0) {
			$resultado = true;
		} else {
			$error = true;
			$mensaje_de_error = "No se encontraron datos.";
			$resultado = false;
		}
	} else {
		$error = true;
		$mensaje_de_error = "Los valores ingresados no validos.";
		$resultado = false;
	}
} else {
	$cod_uv_form = $cod_man_form = $cod_pred_form = $cod_blq_form = $cod_piso_form = $cod_apto_form = "";
}
################################################################################
#-------------- CATASTRO URBANO: BUSQUEDA ALFANUMERICA ENVIADO ----------------#
################################################################################		 
if ((isset($_POST["busqueda_alfaaa"])) and ($mod == 1)) {
	$buscar = $buscar_alfa = true;
	$i = 0;		 
	if (check_int($cod_uv)) {
		$busqueda_sql[$i] = "cod_uv = '$cod_uv'";
		$i++;
	} else
		$cod_uv = "";
	if (($mod == 1) and ($form_codigo == 2) and ($cod_man != "")) {
		$busqueda_sql[$i] = "cod_man_alt = '$cod_man_alfa'";
		$i++;
	} elseif (check_int($cod)) {
		$busqueda_sql[$i] = "cod_man = '$cod_man'";
		$i++;
	} else
		$cod_man = "";
	if (($mod == 1) and ($form_codigo == 2) and ($cod_pred != "")) {
		$busqueda_sql[$i] = "cod_pred_alt = '$cod_pred_alfa'";
		$i++;
	} elseif (check_int($cod_pred_alfa)) {
		$busqueda_sql[$i] = "cod_pred = '$cod_pred_alfa'";
		$i++;
	} else
		$cod_pred = "";
	if ($cod_blq_alfa != "") {
		$busqueda_sql[$i] = "cod_blq = '$cod_blq_alfa'";
		$i++;
	} else
		$cod_blq = "";
	if ($cod_piso_alfa != "") {
		$busqueda_sql[$i] = "cod_piso = '$cod_piso_alfa'";
		$i++;
	} else
		$cod_piso = "";
	if ($cod_apto_alfa != "") {
		$busqueda_sql[$i] = "cod_apto = '$cod_apto'";
		$i++;
	} else
		$cod_apto = "";
	$no_de_criterios = $i;
	########################################
	#--------- GENERAR SQL-STRING ---------#
	########################################
	if ($no_de_criterios > 0) {
		$where = $busqueda_sql[0];
		$i = 1;
		while ($i < $no_de_criterios) {
			$where = $where . " AND " . $busqueda_sql[$i];
			$i++;
		}
		$sql = "SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id, cod_man, cod_pred
			        FROM info_inmu WHERE cod_geo = '$cod_geo' AND $where ORDER BY cod_uv, cod_man, cod_pred";
					 
		########################################
		#---------- CHEQUEAR TABLA ------------#
		########################################
		$check_integrity = pg_num_rows(pg_query($sql));
		if ($check_integrity > 0) {
			$resultado = true;
		} else {
			$error = true;
			$mensaje_de_error = "No se encontraron datos.";
			$resultado = false;
		}
	} else {
		$error = true;
		$mensaje_de_error = "Los valores ingresados no validos.";
		$resultado = false;
	}
} else {
	$cod_uv_alfa = $cod_man_alfa = $cod_pred_alfa = $cod_blq_alfa = $cod_piso_alfa = $cod_apto_alfa = "";
}
################################################################################
#--------------------- PREDIO RURAL : BUSQUEDA 1 ENVIADO ----------------------#
################################################################################		 
if ((isset($_POST["busqueda1"])) and ($_POST["busqueda1"] == "Buscar") and ($mod == 41)) {
	# VARIABLES POST OBTENIDOS EN siicat_check_busqueda_rural.php			 
	$buscar = true;
	$i = 0;
	$busqueda_sql[$i] = "cod_geo = '$cod_geo'";
	$i++;
	if (check_int($cod_poly)) {
		$busqueda_sql[$i] = "cod_poly = '$cod_poly'";
		$i++;
	} else
		$cod_poly = "";
	if (check_int($cod_predio)) {
		$busqueda_sql[$i] = "cod_predio = '$cod_predio'";
		$i++;
	} else
		$cod_predio = "";
	$no_de_criterios = $i;
	########################################
	#--------- GENERAR SQL-STRING ---------#
	########################################
	if ($no_de_criterios > 0) {
		$where = $busqueda_sql[0];
		$i = 1;
		while ($i < $no_de_criterios) {
			$where = $where . " AND " . $busqueda_sql[$i];
			$i++;
		}
		#echo "WHERE-Option: $where <br />\n";				 
		$sql = "SELECT id_predio_rural, cod_geo, cod_poly, cod_predio, nom_pred, tit_1id
	           FROM info_predio_rural WHERE $where ORDER BY cod_geo, cod_poly, cod_predio";
		########################################
		#---------- CHEQUEAR TABLA ------------#
		########################################
		$check_integrity = pg_num_rows(pg_query($sql));
		if ($check_integrity > 0) {
			$resultado = true;
		} else {
			$error = true;
			$mensaje_de_error = "No se encontraron datos.";
			$resultado = false;
		}
	} else {
		$error = true;
		$mensaje_de_error = "Los valores ingresados no validos.";
		$resultado = false;
	}
} else {
	$cod_poly = $cod_predio = "";
}
################################################################################
#----------------------------- BUSQUEDA 2 ENVIADO -----------------------------#
################################################################################		 
if ((isset($_POST["busqueda2"])) and (($_POST["busqueda2"]) == "Buscar")) {
	$buscar = true;
	$no_de_criterios = 0;
	$search_string = trim($_POST["search_string"]);
	########################################
	#    CHEQUAER SI SE LLENO EL CAMPO     #
	########################################  
	if ($search_string === "") {
		$error = true;
		$mensaje_de_error = "No se ha ingresado dato!";
		$resultado = false;
	} else {
		########################################
		#      SEGMENTAR SEARCH STRING         #
		########################################
		$i = $j = 0;
		$init = 0;
		$whats = strlen($search_string);
		while ($i < strlen($search_string)) {
			$char = trim(substr($search_string, $i, 1));
			$char = (string) $char;
			if (($char == "\\") and (!$string_entre_comillas)) {
				$string_entre_comillas = true;
				$init = $init + 2;
				$i++;
			} elseif ($char == "\\") {
				$seg_string[$j] = substr($search_string, $init, $i - $init);
				$seg_string_entre_comillas[$j] = true;
				$j++;
				$init = $i + 2;
				$string_entre_comillas = false;
				$i = $i + 2;
			} elseif ((($char == "+") or ($char == "")) and (!$string_entre_comillas)) {
				$char_ant = trim(substr($search_string, $i - 1, 1));
				if (($char_ant != "") and ($char_ant != "+") and ($char_ant != "\"")) {
					$seg_string[$j] = substr($search_string, $init, $i - $init);
					$seg_string_entre_comillas[$j] = false;
					$j++;
				}
				$init = $i + 1;
			}
			$i++;
		}
		if (strlen($search_string) == $init + ($i - $init)) {
			$seg_string[$j] = trim(substr($search_string, $init, $i - $init));
			$seg_string_entre_comillas[$j] = false;
			$j++;
		}
		########################################
		#      CHEQUEAR TIPO DE STRING         #
		########################################	
		$i = $k = 0;
		$new_search_string = "";
		while ($k < $j) {
			$search_string = $seg_string[$k];
			if ((substr($search_string, 2, 1) == "-") and (substr($search_string, 5, 1) == "-") and (strlen($search_string) == 9)) {
				#echo "STRING $k ES UN COD_CAT !!! <br />\n";				    
				$search_type = "codcat";
				$busqueda_sql[$k] = "cod_cat = '$search_string'";
			} elseif ((substr($search_string, 2, 1) == "_") and (substr($search_string, 5, 1) == "_") and (strlen($search_string) == 9)) {
				#echo "STRING $k ES COD_CAT, PERO USUARIO USO '_' EN VEZ DE '-' !!! <br />\n";	
				$search_type = "codcat";
				$search_string = str_replace("_", "-", $search_string);
				$busqueda_sql[$k] = "cod_cat = '$search_string'";
			} elseif (((substr($search_string, 1, 1) == "-") or (substr($search_string, 1, 1) == "_")) and (strlen($search_string) == 3)) {
				#echo "STRING $k ES cod_uv + COD_MAN !!! <br />\n";
				$search_type = "codcat";
				$texto1 = substr($search_string, 0, 1);
				$texto2 = substr($search_string, 2, 1);
				$busqueda_sql[$k] = "cod_uv = '$texto1' AND cod_man = '$texto2'";
			} elseif (((substr($search_string, 1, 1) == "-") or (substr($search_string, 1, 1) == "_")) and (strlen($search_string) == 4)) {
				#echo "STRING $k ES cod_uv + COD_MAN !!! <br />\n";
				$search_type = "codcat";
				$texto1 = substr($search_string, 0, 1);
				$texto2 = substr($search_string, 2, 2);
				$busqueda_sql[$k] = "cod_uv = '$texto1' AND cod_man = '$texto2'";
			} elseif (((substr($search_string, 2, 1) == "-") or (substr($search_string, 2, 1) == "_")) and (strlen($search_string) == 5)) {
				#echo "STRING $k ES cod_uv + COD_MAN !!! <br />\n";
				$search_type = "codcat";
				$texto1 = (int) substr($search_string, 0, 2);
				$texto2 = (int) substr($search_string, 3, 2);
				$busqueda_sql[$k] = "cod_uv = '$texto1' AND cod_man = '$texto2'";
			} elseif (check_int($search_string)) {
				#echo "STRING $k SON SOLO NUMEROS !!! <br />\n";
				$search_type = "numeros";
				if ($search_string > 100000) {
					#  if ($mod == 121) {
					$busqueda_sql[$k] = "doc_num ~* '$search_string'";
				} else {
					# $busqueda_sql[$k] = "cod_pred = '$search_string'";
					$busqueda_sql[$k] = "con_pmc = '$search_string'";
				}
			} elseif (
				(strlen($search_string) > 3) and ((substr($search_string, strlen($search_string) - 3, 3) == "/01")
					or (substr($search_string, strlen($search_string) - 3, 3) == "/02") or (substr($search_string, strlen($search_string) - 3, 3) == "/03"))
			) {
				#echo "STRING $k ES EL PADRON MUNICIPAL !!! <br />\n";	
				$search_type = "numeros";
				$busqueda_sql[$k] = "cod_pad ~* '$search_string'";
			} elseif (
				(strpos($search_string, "c/") !== false) or (strpos($search_string, "C/") !== false) or
				(strpos($search_string, "Av/") !== false) or (strpos($search_string, "AV/") !== false)
			) {
				#echo "STRING $k ES UNA DIRECCION !!! <br />\n";
				$search_type = "direccion";
				$search_direccion = true;
				$busqueda_sql[$k] = "cod_geo = '$cod_geo'";
	 
			} elseif (
				(strpos($search_string, "Calle") !== false) or (strpos($search_string, "calle") !== false) or (strpos($search_string, "Avenida") !== false) or (strpos($search_string, "avenida") !== false)
				or (strpos($search_string, "Av.") !== false) or (strpos($search_string, "AV.") !== false) or (strpos($search_string, "av.") !== false)
			) {
				#echo "STRING $k ES UNA DIRECCION !!! <br />\n";
				$search_type = "direccion";
				$search_direccion = true;
				$busqueda_sql[$k] = "cod_geo = '$cod_geo'";
			} elseif (
				($search_string == "Calle") or ($search_string == "calle") or ($search_string == "avenida") or ($search_string == "Avenida")
				or ($search_string == "pasillo") or ($search_string == "Pasillo") or ($search_string == "plaza") or ($search_string == "Plaza")
			) {
				#echo "STRING $k ES UNA DIRECCION 2 !!! <br />\n";
				$search_type = "direccion";
				$pos = strpos($search_string, " ") + 1;
				$texto1 = substr($search_string, 0, $pos - 1);
				$texto2 = substr($search_string, $pos, strlen($search_string) - $pos);
				$busqueda_sql[$k] = "dir_nom ~* '$texto2'";
	
			} else {
				if ($search_direccion) {
					#echo "STRING $k ES UN NOMBRE DE CALLE !!! <br />\n";						
					$busqueda_sql[$k] = "dir_nom ~* '$search_string'";
				} else {
					#echo "L440 STRING $k ES UN NOMBRE  !!! <br />\n";						
					$search_type = "nombre";
					if (strpos($search_string, " ") !== false) {
						$pos = strpos($search_string, " ") + 1;
						#$busqueda_columna[$i] = "tit_1nom1";
						$texto1 = substr($search_string, 0, $pos - 1);
						$texto2 = substr($search_string, $pos, strlen($search_string) - $pos);
						$textconv1 = textconvert($texto1);
						$textconv2 = textconvert($texto2);
						$busqueda_sql[$k] = "(con_nom1 ~* '$texto1' AND con_pat ~* '$texto2') 
										 OR (con_pat ~* '$texto1' AND con_mat ~* '$texto2')";
						$busqueda_sql_nompred[$k] = "(nom_pred ~* '$texto1' OR nom_pred ~* '$texto2')";
						$busqueda_sql_razsoc[$k] = "(act_raz ~* '$texto1' OR act_raz ~* '$texto2')";								 
					} elseif ($mod == 131) {
						$busqueda_sql[$k] = "nombre ~* '$search_string'";
					} else {
						$busqueda_sql[$k] = "con_raz ~* '$search_string' OR con_pat ~* '$search_string' OR con_mat ~* '$search_string' OR con_nom1 ~* '$search_string' OR con_nom2 ~* '$search_string'";
						$busqueda_sql_nompred[$k] = "nom_pred ~* '$search_string'";
						$busqueda_sql_razsoc[$k] = "act_raz ~* '$search_string'";
					}
				}
			}
			$new_search_string[$k] = $search_string;
			$k++;
		}
		$no_de_criterios = $k;
		########################################
		#        PREPARAR SEARCH-STRING        #
		########################################	
		$search_string = "";
		$i = 0;
		while ($i < $j) {
			if ($seg_string_entre_comillas[$i]) {
				$search_string = trim($search_string . " '" . $new_search_string[$i] . "' ");
			} else {
				$search_string = trim($search_string . " " . $new_search_string[$i]);
			}
			$i++;
		}
		$search_string = textconvert($search_string);

		########################################
		#---------- GENERAR BUSQUEDA ----------#
		########################################
		$where = $busqueda_sql[0];
		if ($mod == 41) {
			$where_nompred = $busqueda_sql_nompred[0];
		}
		if ($mod == 101) {
			$where_razsoc = $busqueda_sql_razsoc[0];
		}
		$i = 1;
		$k = 1;
		while ($i < $no_de_criterios) {
			$where = "(" . $where . ") AND (" . $busqueda_sql[$i] . ")";
			if ($mod == 41) {
				$where_nompred = "(" . $where_nompred . ") AND (" . $busqueda_sql_nompred[$i] . ")";
			}
			if ($mod == 101) {
				$where_razsoc = "(" . $where_razsoc . ") AND (" . $busqueda_sql_razsoc[$i] . ")";
			}
			$i++;
		}
		########################################
		#------- BUSCAR STRING NOMBRE ---------#
		########################################
		if ($search_type == "nombre") {
			if ($mod == 131) {
				$sql = "SELECT id_tram FROM tramites WHERE $where ORDER BY nombre";
			} else {
				$sql = "SELECT id_contrib FROM contribuyentes WHERE $where ORDER BY id_contrib";
			}
			$check_contrib = pg_num_rows(pg_query($sql));
			if ($check_contrib == 1) {
				#echo "1 RESULTADO <br />\n";	
				$result = pg_query($sql);
				$info = pg_fetch_array($result, null, PGSQL_ASSOC);
				if ($mod == 131) {
					$id_tram = $info['id_tram'];
				} else {
					$id_contrib = $info['id_contrib'];
				}
				pg_free_result($result);
				if ($mod == 1) {   ### CATASTRO URBANO		 
					$sql = "SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id 
			               FROM info_inmu WHERE cod_geo = '$cod_geo' AND (tit_1id = '$id_contrib' OR tit_2id = '$id_contrib') ORDER BY cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto";
					$where_trans = "cod_geo = '$cod_geo' AND (tan_1id = '$id_contrib' OR tan_2id = '$id_contrib')";
				} elseif ($mod == 41) {   ### CATASTRO RURAL
					$sql = "SELECT id_predio_rural, cod_geo, cod_poly, cod_predio, nom_pred, tit_1id 
			               FROM info_predio_rural WHERE cod_geo = '$cod_geo' AND (tit_1id = '$id_contrib' OR tit_2id = '$id_contrib') ORDER BY cod_geo, cod_poly, cod_predio";
				} elseif ($mod == 101) {   ### PATENTES
					$sql = "SELECT id_patente, id_contrib1, id_inmu, cod_pat, act_raz 
							       FROM patentes WHERE id_contrib1 = '$id_contrib' OR id_contrib2 = '$id_contrib' ORDER BY act_raz";
					#echo "SQL: $sql <br />\n";							
				} elseif ($mod == 121) {   ### CONTRIBUYENTES
					$sql = "SELECT id_contrib, con_pmc, con_raz, con_nom1, con_nom2, con_pat, con_mat, 
	                   doc_tipo, doc_num, doc_exp FROM contribuyentes WHERE $where ORDER BY id_contrib";
					#echo "L458 WHERE-Option: $where <br />\n";
				} elseif ($mod == 131) {   ### TRAMITES
					$sql = "SELECT id_tram, nombre, tipo_tram FROM tramites WHERE $where ORDER BY id_tram";
					#echo "L550 WHERE-Option: $where <br />\n";						 					
				}
			} elseif ($check_contrib > 1) {
				#echo "MAS QUE 1 RESULTADO <br />\n";				 
				if ($mod == 1) {   ### CATASTRO URBANO
					$result = pg_query($sql);
					$where = $where_trans = "cod_geo = '$cod_geo' AND (";
					$i = 0;
					while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
						foreach ($line as $col_value) {
							if ($i == 0) {
								$where = $where . "tit_1id = '" . $col_value . "'";
								$where_trans = $where_trans . "tan_1id = '" . $col_value . "'";
								$i++;
							} else {
								$where = $where . " OR tit_1id ='" . $col_value . "'";
								$where_trans = $where_trans . " OR tan_1id ='" . $col_value . "'";
							}
						}
					} # END_OF_WHILE
					$where = $where . ")";
					$where_trans = $where_trans . ")";
					#echo "L479 WHERE-Option: $where <br />\n";
					$sql = "SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id 
			            FROM info_inmu WHERE $where ORDER BY cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto";
				} elseif ($mod == 41) {   ### CATASTRO RURAL
					$result = pg_query($sql);
					$where = "(";
					$i = 0;
					while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
						foreach ($line as $col_value) {
							if ($i == 0) {
								$where = $where . "tit_1id = '" . $col_value . "'";
								$i++;
							} else {
								$where = $where . " OR tit_1id ='" . $col_value . "'";
							}
						}
					} # END_OF_WHILE
					$where = $where . ")";
					#echo "WHERE-Option: $where <br />\n";
					$sql = "SELECT id_predio_rural, cod_geo, cod_poly, cod_predio, nom_pred, tit_1id 
			         FROM info_predio_rural WHERE $where ORDER BY cod_geo, cod_poly, cod_predio";
				} elseif ($mod == 101) {   ### PATENTES
					$result = pg_query($sql);
					$where = "(";
					$i = 0;
					while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
						foreach ($line as $col_value) {
							if ($i == 0) {
								$where = $where . "id_contrib1 = '" . $col_value . "' OR id_contrib2 = '" . $col_value . "'";
								$i++;
							} else {
								$where = $where . " OR id_contrib1 ='" . $col_value . "' OR id_contrib2 = '" . $col_value . "'";
							}
						}
					} # END_OF_WHILE
					$where = $where . ")";
					$sql = "SELECT id_patente, id_contrib1, id_inmu, cod_pat, act_raz 
							       FROM patentes WHERE $where ORDER BY act_raz";
				} elseif ($mod == 121) {   ### CONTRIBUYENTES
					$sql = "SELECT id_contrib, con_pmc, con_raz, con_nom1, con_nom2, con_pat, con_mat, 
	                   doc_tipo, doc_num, doc_exp FROM contribuyentes WHERE $where ORDER BY id_contrib";
					#echo "L521 WHERE-Option: $where <br />\n";										 					
				} elseif ($mod == 131) {   ### TRAMITES
					$sql = "SELECT id_tram, nombre, tipo_tram FROM tramites WHERE $where ORDER BY id_tram";
					#echo "L521 WHERE-Option: $where <br />\n";										 					
				}
			} else {
				$sql = "SELECT id_inmu FROM info_inmu WHERE id_inmu = '-1'";
				$where_trans = "id_inmu = '-1'";
				#echo "ELSE ! <br />\n";				 
			}
			$check_integrity = pg_num_rows(pg_query($sql));
			##### SI NO HAY RESULTADO #####
			if ($check_integrity > 0) {
				$resultado = true;
			} elseif ($mod == 1) {
				##### CHEQUEAR TABLA TRANSFER #####				 
#echo "WHERE_TRANS-Option: $where_trans <br />\n";	
				$sql = "SELECT DISTINCT id_inmu FROM transfer WHERE $where_trans ORDER BY id_inmu";
				#echo "SQL: $sql <br />\n";								 
				$check_id = pg_num_rows(pg_query($sql));
				if ($check_id == 1) {
					$aviso_registro_transfer = true;
					$result = pg_query($sql);
					$info = pg_fetch_array($result, null, PGSQL_ASSOC);
					$id_inmu = $info['id_inmu'];
					pg_free_result($result);
					$sql = "SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id 
			         FROM info_inmu WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' ORDER BY cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto";
				}
			} elseif ($mod == 41) {
				##### CHEQUEAR TABLA INFO_PREDIO_RURAL #####				 
#echo "WHERE_NOMPRED-Option: $where_nompred <br />\n";	
				$sql = "SELECT id_predio_rural FROM info_predio_rural WHERE $where_nompred";
				#echo "SQL: $sql <br />\n";													 
				$check_id = pg_num_rows(pg_query($sql));
				#echo "CHECK: $check_id <br />\n";							
				if ($check_id > 0) {
					$result = pg_query($sql);
					$info = pg_fetch_array($result, null, PGSQL_ASSOC);
					$id_predio_rural = $info['id_predio_rural'];
					pg_free_result($result);
					$sql = "SELECT id_predio_rural, cod_geo, cod_poly, cod_predio, nom_pred, tit_1id 
			               FROM info_predio_rural WHERE $where_nompred ORDER BY cod_geo, cod_poly, cod_predio";
				}
			} elseif ($mod == 101) {
				##### CHEQUEAR TABLA PATENTES #####				 
#echo "WHERE_RAZSOC-Option: $where_razsoc <br />\n";	
				$sql = "SELECT id_patente FROM patentes WHERE $where_razsoc";
				#echo "SQL: $sql <br />\n";													 
				$check_id = pg_num_rows(pg_query($sql));
				#echo "CHECK: $check_id <br />\n";							
				if ($check_id > 0) {
					$result = pg_query($sql);
					$info = pg_fetch_array($result, null, PGSQL_ASSOC);
					$id_patente = $info['id_patente'];
					pg_free_result($result);
					$sql = "SELECT id_patente, id_contrib1, id_inmu, cod_pat, act_raz 
							       FROM patentes WHERE $where_razsoc ORDER BY act_raz";
				}
			}
			########################################
			#------ BUSCAR STRING DIRECCION -------#
			########################################
		} elseif ($search_type == "direccion") {
			$sql = "SELECT cod_uv, cod_man, cod_pred FROM info_predio WHERE $where ORDER BY cod_uv, cod_man, cod_pred";
			#echo "SQL_DIR: $sql <br />\n";				 
			$check_inmu = pg_num_rows(pg_query($sql));
			if ($check_inmu == 1) {
				#echo "1 RESULTADO <br />\n";						 		 			
				$result = pg_query($sql);
				$info = pg_fetch_array($result, null, PGSQL_ASSOC);
				$cod_uv = $info['cod_uv'];
				$cod_man = $info['cod_man'];
				$cod_pred = $info['cod_pred'];
				pg_free_result($result);
				$sql = "SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id 
			            FROM info_inmu WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' 
									ORDER BY cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto";
			} elseif ($check_inmu > 1) {
				$result = pg_query($sql);
				$where =
					$i = $j = 0;
				while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
					foreach ($line as $col_value) {
						if ($i == 0) {
							$cod_uv_temp = $col_value;
						} elseif ($i == 1) {
							$cod_man_temp = $col_value;
						} else {
							$cod_pred_temp = $col_value;
							if ($j == 0) {
								$where = "(cod_geo = '$cod_geo' AND cod_uv = '" . $cod_uv_temp . "' AND cod_man = '" . $cod_man_temp . "' AND cod_pred = '" . $cod_pred_temp . "')";
								$j++;
							} else {
								$where = $where . " OR (cod_geo = '$cod_geo' AND cod_uv = '" . $cod_uv_temp . "' AND cod_man = '" . $cod_man_temp . "' AND cod_pred = '" . $cod_pred_temp . "')";
							}
							$i = -1;
						}
						$i++;
					}
				} # END_OF_WHILE
				$sql = "SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id 
			            FROM info_inmu WHERE $where ORDER BY cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto";
				#echo "SQL_DIR: $sql <br />\n";														
			} else {
				$sql = "SELECT id_inmu FROM info_inmu WHERE id_inmu = '-1'";
				#echo "ELSE ! <br />\n";				 
			}
			########################################
			#----- BUSCAR STRING NUMERO MOD 1 -----#
			########################################
		} elseif (($search_type == "numeros") and ($mod == "1")) {
			$sql = "SELECT id_contrib, con_raz FROM contribuyentes WHERE $where ORDER BY con_raz";
			#echo "SQL_NUM: $sql <br />\n";				 
			$check_contrib = pg_num_rows(pg_query($sql));
			if ($check_contrib == 1) {
				#echo "1 RESULTADO <br />\n";	
				$result = pg_query($sql);
				$info = pg_fetch_array($result, null, PGSQL_ASSOC);
				$id_contrib = $info['id_contrib'];
				pg_free_result($result);
				$sql = "SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id 
			               FROM info_inmu WHERE cod_geo = '$cod_geo' AND (tit_1id = '$id_contrib' OR tit_2id = '$id_contrib') ORDER BY cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto";
				$where_trans = "cod_geo = '$cod_geo' AND (tan_1id = '$id_contrib' OR tan_2id = '$id_contrib')";
				#echo "SQL: $sql <br />\n";														
			} else {
				$sql = "SELECT id_inmu FROM info_inmu WHERE id_inmu = '-1'";
				#echo "ELSE ! <br />\n";				 
			}
			########################################
			#---- BUSCAR STRING NUMERO MOD 121 ----#
			########################################				 		
		} elseif (($search_type == "numeros") and ($mod == "121")) {
			$sql = "SELECT id_contrib, con_raz FROM contribuyentes WHERE $where ORDER BY con_raz";
			#echo "SQL_NUM: $sql <br />\n";				 
			$check_docnum = pg_num_rows(pg_query($sql));
			if ($check_docnum == 1) {
				#echo "1 RESULTADO <br />\n";						 		 			
				$result = pg_query($sql);
				$info = pg_fetch_array($result, null, PGSQL_ASSOC);
				$id_contrib = $info['id_contrib'];
				pg_free_result($result);
				$sql = "SELECT id_contrib, con_pmc, con_raz, con_nom1, con_nom2, con_pat, con_mat, 
	                       doc_tipo, doc_num, doc_exp FROM contribuyentes WHERE id_contrib = '$id_contrib'";
			} elseif ($check_docnum > 1) {
				$result = pg_query($sql);
				$where =
					$i = $j = 0;
				while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
					foreach ($line as $col_value) {
						if ($i == 0) {
							$id_contrib_temp = $col_value;
						} else {
							$raz_temp = $col_value;
							if ($j == 0) {
								$where = "(id_contrib = '" . $id_contrib_temp . "')";
								$j++;
							} else {
								$where = $where . " OR (id_contrib = '" . $id_contrib_temp . "')";
							}
							$i = -1;
						}
						$i++;
					}
				} # END_OF_WHILE
				$sql = "SELECT id_contrib, con_pmc, con_raz, con_nom1, con_nom2, con_pat, con_mat, 
	                       doc_tipo, doc_num, doc_exp FROM contribuyentes WHERE $where ORDER BY con_raz";
				#echo "SQL_DIR: $sql <br />\n";														
			} else {
				$sql = "SELECT id_inmu FROM info_inmu WHERE id_inmu = '-1'";
				#echo "ELSE ! <br />\n";				 
			}
		}
		########################################
		#--------- STRING ENCONTRADO ----------#
		########################################	
		$check_integrity = pg_num_rows(pg_query($sql));
		if ($check_integrity > 0) {
			$resultado = true;
		} else {
			$error = true;
			$mensaje_de_error = "No se encontraron datos en la base de datos";
			$resultado = false;
		}
	} # END_OF_ELSE (search_string != "")			  	 
}

################################################################################
#------------------------------ RELLENAR ARRAYS -------------------------------#
################################################################################	
if (($buscar) and (!$error)) {
	$filas = pg_num_rows(pg_query($sql));
	#echo "L850 FILAS:$filas, $sql<br />";	 
	$result = pg_query($sql);
	$i = $j = $k = 0;
	$m = 25;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		foreach ($line as $col_value) {
			$value[$i] = $col_value;
			$i++;
		}
		if ($mod == 1) {   ### CATASTRO URBANO				
			$valor_submit[$j] = $value[0];
			if (($form_codigo == 2) and ($buscar_alfa)) {
				$cod_man_alfaa = get_codigo_alfa($value[2]);
				$cod_pred_alfaa = get_codigo_alfa($value[3]);
				#echo "L837 $value[1],$cod_man_alfa,$cod_pred_alfa,$value[4],$value[5],$value[6]<br />";						
				$valor1[$j] = get_codcat($value[1], $cod_man_alfaa, $cod_pred_alfaa, $value[4], $value[5], $value[6]);
			} else {
				$valor1[$j] = get_codcat($value[1], $value[2], $value[3], $value[4], $value[5], $value[6]);
			}
			$valor2[$j] = get_contrib_nombre($value[7]);
			$valor3[$j] = get_predio_dir($cod_geo, $value[1], $value[2], $value[3]);
		} elseif ($mod == 41) {   ### CATASTRO RURAL				 
			$valor_submit[$j] = $value[0];
			$valor1[$j] = get_codcat_rural($value[1], $value[2], $value[3]);
			$valor2[$j] = utf8_decode($value[4]);
			$valor3[$j] = get_contrib_nombre($value[5]);
		} elseif ($mod == 101) {   ### PATENTES
			# "No. Patente";"Razon Social";"Propietario";
			$valor_submit[$j] = $value[0];
			$valor1[$j] = $value[3];
			$valor2[$j] = utf8_decode($value[4]);
			$valor3[$j] = get_contrib_nombre($value[1]);
		} elseif ($mod == 111) {   ### VEHICULOS
			$valor_submit[$j] = $value[7];
			$valor1[$j] = $value[7];
			$valor2[$j] = $propietario;
			$texto1 = utf8_decode($value[8]);
			$texto2 = utf8_decode($value[9]);
			$valor3[$j] = $texto1 . " " . $texto2;
		} elseif ($mod == 121) {   ### CONTRIBUYENTES
			# "P.M.C.";   "Contribuyente/Razon Social";  "Documentaci�n";
			$valor_submit[$j] = $value[0];
			$valor1[$j] = $value[1];
			$con_raz = utf8_decode($value[2]);
			$texto1 = utf8_decode($value[3]);
			$texto2 = utf8_decode($value[4]);
			$texto3 = utf8_decode(strtoupper(ucase($value[5])));
			$texto4 = utf8_decode(strtoupper(ucase($value[6])));
			#echo "L768 $con_raz,$texto1,$texto2,$texto3,$texto4<br />"; 
			if ($con_raz == $texto3) {
				$propietario = $texto3 . " " . $texto4 . ", " . $texto1 . " " . $texto2;
			} else
				$propietario = $con_raz;
			$valor2[$j] = $propietario;
			$texto1 = utf8_decode($value[7]);
			$texto2 = utf8_decode($value[8]);
			$texto3 = utf8_decode($value[9]);
			$valor3[$j] = $texto1 . " " . $texto2 . " " . $texto3;
		} elseif ($mod == 131) {   ### TRAMITES
			# "No. Tramite";"Nombre";"Descrip. Tramite";
			$valor_submit[$j] = $value[0];
			$valor1[$j] = $value[0];
			$valor2[$j] = utf8_decode($value[1]);
			$valor3[$j] = get_tramite_descrip($value[2]);
		}

		$i = 0;
		$j++;
		$k++;
	}
	### CHEQUEAR SI EXISTEN REGISTROS EN LA BASE DE DATOS
	$check_integrity = pg_num_rows(pg_query($sql));
	if ($check_integrity > 0) {
		$resultado = true;
	} else {
		$error = true;
		$mensaje_de_error = "No se ha encontrado registro en la base de datos";
		$resultado = false;
	}
}

################################################################################
#----------------------------------   OPCION    -------------------------------#
################################################################################
if ($mod == 1) {
	$opcion1 = "Buscar Inmueble";
	$opcion2 = "Nombre, Apellido, No. de Carnet, PMC y/o Dirección";
} elseif ($mod == 41) {
	$opcion1 = "Buscar Propiedad Rural";
	$opcion2 = "Nombre de la Propiedad o del Propietario";
} elseif ($mod == 101) {
	$opcion1 = "Buscar Patente";
	$opcion2 = "Razón Social, Propietario o NIT";
} elseif ($mod == 111) {
	$opcion1 = "Buscar Vehículo";
	$opcion2 = "Nombre de Propietario o No. de Carnet";
} elseif ($mod == 121) {
	$opcion1 = "Buscar Contribuyente";
	$opcion2 = "Nombre del Contribuyente o No. de Carnet";
} elseif ($mod == 131) {
	$opcion1 = "Buscar Tramite";
	$opcion2 = "Nombre del Contribuyente o No. de Carnet";
}


################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################		
echo "<td>\n";
echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
echo "<tr height=\"50px\">\n";
	echo "<td width=\"5%\"></td>\n";   #Col. 1 	    
	echo "<td align=\"center\" valign=\"center\" height=\"40\" width=\"70%\" class=\"pageName\">$opcion1 </td>\n";
	echo "<td width=\"10%\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td height=\"40\"></td>\n";
echo "<td valign=\"top\" class=\"bodyText\">\n";
echo "<fieldset><legend>Ingrese datos de busqueda</legend>\n";
if ($mod == 41) {
	echo "<form name=\"isc\" method=\"post\" action=\"index.php?mod=44&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";  # TABLE 6 Columns
	echo "<tr>\n";
	echo "<td> </td>\n"; #TCol. 1
	echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Código Geogr.</td>\n";
	echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Polígono</td>\n";
	echo "<td align=\"left\" colspan=\"3\" class=\"bodyTextD\">Parcela</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"10%\"> </td>\n"; #TCol. 1
	echo "<td align=\"left\" width=\"20%\" class=\"bodyText\">\n";   #Col. 2	 	 
	echo "<select class=\"navText\" name=\"cod_geo\" size=\"1\">\n";
	$i = 0;
	while ($i < $no_de_cod_geos) {
		$value_temp = $cod_geo_lista[$i];
		if ($value_temp == $cod_geo) {
			echo "<option id=\"form0\" value=\"$value_temp\" selected=\"selected\"> $cod_geo_lista[$i]</option>\n";
		} else {
			echo "<option id=\"form0\" value=\"$value_temp\"> $cod_geo_lista[$i]</option>\n";
		}
		$i++;
	}
	echo "</select>\n";
	echo "</td>\n";
	echo "<td width=\"1%\"></td>\n"; #TCol. 3				
	echo "<td align=\"left\" width=\"10%\" class=\"bodyTextD\">\n"; #TCol. 4
	echo "<input name=\"cod_poly\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_pol\" value=\"$cod_poly\">\n";
	echo "</td>\n";
	echo "<td width=\"2%\"></td>\n"; #TCol. 5			
	echo "<td align=\"left\" width=\"10%\" class=\"bodyTextD\">\n"; #TCol. 6
	echo "<input name=\"cod_predio\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_par\" value=\"$cod_predio\">\n";
	echo "</td>\n";
	echo "<td width=\"2%\"></td>\n"; #TCol. 7					
	echo "<td width=\"45%\">\n";  #TCol. 8				
	echo "<input name=\"busqueda1\" type=\"submit\" class=\"smallText\" value=\"Buscar\" onClick=\"go()\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
} elseif ($mod == 101) {
	echo "<form name=\"isc\" method=\"post\" action=\"index.php?mod=105&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";  # TABLE 6 Columns
	echo "<tr>\n";
	echo "<td></td>\n"; #TCol. 1
	echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">N�mero de Patente\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"10%\"></td>\n"; #TCol. 1
	echo "<td align=\"left\" width=\"30%\" class=\"bodyTextD\">\n";
	echo "<input name=\"cod_pat\" type=\"text\" class=\"navText\" value=\"$cod_pat\">\n";
	echo "</td>\n";
	echo "<td width=\"60%\">\n";
	echo "<input name=\"busqueda\" type=\"submit\" class=\"smallText\" value=\"Buscar\" onClick=\"go()\">\n";
	echo "</td>\n"; #TCol. 1			
	echo "</tr>\n";
	echo "</table>\n"; #TCol. 1			
	echo "</form>\n";
} elseif ($mod == 111) {
	echo "<form name=\"isc\" method=\"post\" action=\"index.php?mod=115&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";  # TABLE 6 Columns
	echo "<tr>\n";
	echo "<td></td>\n"; #TCol. 1
	echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">N�mero de Placa\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"10%\"></td>\n"; #TCol. 1
	echo "<td align=\"left\" width=\"30%\" class=\"bodyTextD\">\n";
	echo "<input name=\"veh_plc\" type=\"text\" class=\"navText\" value=\"$veh_plc\">\n";
	echo "</td>\n";
	echo "<td width=\"60%\">\n";
	echo "<input name=\"busqueda\" type=\"submit\" class=\"smallText\" value=\"Buscar\" onClick=\"go()\">\n";
	echo "</td>\n"; #TCol. 1			
	echo "</tr>\n";
	echo "</table>\n"; #TCol. 1			
	echo "</form>\n";
} elseif ($mod == 121) {
	echo "<form name=\"isc\" method=\"post\" action=\"index.php?mod=125&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";  # TABLE 6 Columns
	echo "<tr>\n";
	echo "<td></td>\n"; #TCol. 1
	echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Padrón Municipal (PMC)\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"10%\"></td>\n"; #TCol. 1
	echo "<td align=\"left\" width=\"25%\" class=\"bodyTextD\">\n";
	echo "<input name=\"con_pmc\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_pmc\" value=\"$con_pmc\">\n";
	echo "</td>\n";
	echo "<td width=\"65%\">\n";
	echo "<input name=\"busqueda\" type=\"submit\" class=\"smallText\" value=\"Buscar\" onClick=\"go()\">\n";
	echo "</td>\n";			
	echo "</tr>\n";
	echo "</table>\n";		
	echo "</form>\n";
} elseif ($mod == 131) {
	echo "<form name=\"isc\" method=\"post\" action=\"index.php?mod=135&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n"; 
	echo "<tr>\n";
	echo "<td></td>\n";
	echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Número de Tramite\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"10%\"></td>\n"; #TCol. 1
	echo "<td align=\"left\" width=\"30%\" class=\"bodyTextD\">\n";
	echo "<input name=\"id_tram\" type=\"text\" class=\"navText\" value=\"$id_tram\">\n";
	echo "</td>\n";
	echo "<td width=\"60%\">\n";
	echo "<input name=\"busqueda\" type=\"submit\" class=\"smallText\" value=\"Buscar\" onClick=\"go()\">\n";
	echo "</td>\n"; #TCol. 1			
	echo "</tr>\n";
	echo "</table>\n"; #TCol. 1			
	echo "</form>\n";
} else {
echo "<form name=\"isc\" method=\"post\" action=\"index.php?mod=4&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\">\n";
		echo "<tr>\n";
			echo "<td class=\"bodyTextD\">U.V.</td>\n";
			echo "<td class=\"bodyTextD\">Mz.</td>\n";
			echo "<td class=\"bodyTextD\">Pred.</td>\n";
			echo "<td class=\"bodyTextD\">Blq.</td>\n";
			echo "<td class=\"bodyTextD\">Piso</td>\n";
			echo "<td class=\"bodyTextD\">Apto.</td>\n";
			echo "<td></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
			echo "<td><input name=\"cod_uv\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_uv\" value=\"$cod_uv_form\"></td>\n";
			echo "<td><input name=\"cod_man\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_man\" value=\"$cod_man_form\"></td>\n";
			echo "<td><input name=\"cod_pred\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_pred\" value=\"$cod_pred_form\"></td>\n";
			echo "<td><input name=\"cod_blq\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_blq\" value=\"$cod_blq_form\"></td>\n";
			echo "<td><input name=\"cod_piso\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_piso\" value=\"$cod_piso_form\"></td>\n";
			echo "<td><input name=\"cod_apto\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_apto\" value=\"$cod_apto_form\"></td>\n";
			echo "<td  width=\"13%\">";
			echo "<input name=\"old_example\" type=\"hidden\" class=\"smallText\" value=\"$example\">\n";
			echo "<input name=\"old_stage2\"  type=\"hidden\" class=\"smallText\" value=\"$stage2\">\n";
			if ($form_codigo == 2) {
				echo "<input name=\"busqueda_alfa\" type=\"submit\" class=\"smallText\" value=\"REAL\" onClick=\"go()\">";
				echo "<input name=\"busqueda_alfa\" type=\"submit\" class=\"smallText\" value=\"INTERNO\" onClick=\"go()\">";
			} else {
				echo "<input name=\"busqueda1\" type=\"submit\" class=\"smallText\" value=\"Buscar\" onClick=\"go()\">";
			}
			echo "</td>\n";
		echo "</tr>\n";
	echo "</table>\n";
echo "</form>\n";
}
echo "<form name=\"isc\" method=\"post\" action=\"index.php?mod=$mod&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\">\n"; 
		echo "<tr>\n";
			echo "<td align=\"left\" width=\"87%\" class=\"bodyTextD\">$opcion2\n";
			echo "<input name=\"search_string\" type=\"text\" class=\"navText\" value=\"$search_string\">\n";
			echo "</td>\n";
			echo "<td width=\"13%\">\n";
			echo "<input name=\"busqueda2\" type=\"submit\" class=\"smallText\" value=\"Buscar\" onClick=\"go()\">\n";
			echo "</td>\n";
		echo "</tr>\n";
	echo "</table>\n";
echo "</form>\n";

echo "</fieldset>\n";
echo "</td>\n";
echo "<td height=\"40\"></td>\n";		
echo "</tr>\n";

if ($error) {
	echo "<tr>\n";
		echo "<td></td>\n";	  
		echo "<td align=\"center\" height=\"40\" class=\"alerta-danger\">$mensaje_de_error</font>\n";   #Col. 2
		echo "<td></td>\n";
	echo "</tr>\n";
} elseif ($buscar and $resultado) {
	if ($aviso_registro_transfer) {
		echo "<tr>\n";
			echo "<td></td>\n"; 
			echo "<td align=\"center\" class=\"alerta-danger\">NO SE ENCUENTRO NINGUN INMUEBLE REGISTRADO BAJO ESTE NOMBRE, PERO HUBO UNA TRANSFERENCIA:</td>\n";
			echo "<td></td>\n";
		echo "</tr>\n";
	} else {
		echo "<tr>\n";
			echo "<td></td>\n"; 
			echo "<td align=\"left\">Registros encontrados:<b>$filas</b></td>\n";
			echo "<td></td>\n";
		echo "</tr>\n";
	}
	echo "<tr>\n";
	echo "<td></td>\n";                      
	echo "<td valign=\"top\" class=\"bodyText\">\n";

	echo "</td>\n";
	echo "<td></td>\n"; 		
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td></td>\n";                      
	echo "<td valign=\"top\" class=\"bodyText\">\n";
	echo "<div style=\"height:450px; overflow:auto\">\n";
	echo "<table width=\"100%\" border=\"0\" id=\"registros2\">\n";
		echo "<thead><tr>\n";
			if ($mod == 41) {
				echo "<th align=\"center\" width=\"20%\">$titulo1</th>\n";
				echo "<th align=\"center\" width=\"35%\">$titulo2</th>\n";
				echo "<th align=\"center\" width=\"38%\">$titulo3</th>\n";
			} elseif ($mod == 121) {
				echo "<th align=\"center\" width=\"15%\">$titulo1</th>\n";
				echo "<th align=\"center\" width=\"50%\">$titulo2</th>\n";
				echo "<th align=\"center\" width=\"28%\">$titulo3</th>\n";
			} else {
				echo "<th align=\"center\" width=\"15%\">$titulo1</th>\n";
				echo "<th align=\"center\" width=\"40%\">$titulo2</th>\n";
				echo "<th align=\"center\" width=\"38%\">$titulo3</th>\n";
			}
			echo "<th align=\"center\" width=\"7%\">Ver</th>\n";
		echo "<tr></thead>\n";
		echo "<tbody>\n";
		$i = $j = $k = 0;
		$m = 25;
		$show_color = false;
		while ($j < $filas) {
			if (!$show_color) {
				echo "<tr>\n";
				$show_color = true;
			} else {
				echo "<tr class=\"alt\">\n";
				$show_color = false;
			}
			echo "<td align=\"center\">$valor1[$j]</td>\n";
			if ($mod == 121) {
				echo "<td align=\"center\"> $valor2[$j]</td>\n";
			} else {
				echo "<td align=\"center\">$valor2[$j]</td>\n";
			}
			echo "<td align=\"center\">$valor3[$j]</td>\n";
			echo "<td align=\"center\">\n";
			echo "<a href=\"index.php?mod=$pag&$nombre_get=$valor_submit[$j]&db=$db&id=$session_id\"><input type=\"image\" name=\"ver\" src=\"$iconos/eye-solid-full.svg\" alt=\"Ver\" title=\"Ver\" width=\"17\" height=\"17\" border=\"0\"></a>\n";
			echo "</td>\n";
			echo "</tr>\n";
			$j++;
		} 	
		pg_free_result($result);
		echo "</tbody>\n";
	echo "</table>\n";
	echo "</div>\n";
	echo "</td>\n";
	echo "<td height=\"40\"></td>\n";	
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td></td>\n";                     
	echo "<td valign=\"top\" class=\"bodyText\">\n";

	echo "</td>\n";
	echo "<td></td>\n";  		
	echo "</tr>\n";

} elseif ($buscar and !$resultado) {
	echo "<h3  class=\"alerta-danger\>Busqueda sin resultado...</h3>\n";
	echo "<p>No se encontró el código catastral: $cod_cat,\n";
	echo "el padron municipal: $cod_pad, el nombre del\n";
	echo "títular: $nombre1 o el \n";
	echo "apellido del titular: $apellido1 en la base de datos</p>\n";
}
echo "<tr height=\"100%\"></tr>\n";
echo "</table>\n";
echo "<br /><br />\n";
echo "</td>\n";
?>