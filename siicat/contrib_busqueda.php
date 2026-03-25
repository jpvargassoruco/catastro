<?php
################################################################################
#--------------------------- TITULOS SEGUN RUBRO ------------------------------#
################################################################################		
$pag = 123;
$var_submit = "con_pmc";
$titulo1 = "P.M.C.";
$titulo2 = "Contribuyente/Razon Social";
$titulo3 = "Documentación";

$buscar = false;
$error = false;
$resultado = false;
$search_string = $act_pat = $veh_plc = $cod_pol = $cod_par = $con_pmc = "";
$cod_cat = $cod_pad = $nombre = $apellido = $dir_nom = $uv_man = "";
$sql_existe = false;
$string_entre_comillas = false;
$datos_enviados = false;
$example = $stage2 = "";

################################################################################
#----------------------------- BUSQUEDA 2 ENVIADO -----------------------------#
################################################################################		 
if ((isset($_POST["busqueda2"])) AND (($_POST["busqueda2"]) == "Buscar")) {
	$buscar = true;
	$no_de_criterios = 0;
	$search_string = trim($_POST["search_string"]);

	########################################
	#    CHEQUAER SI SE LLENO EL CAMPO     #
	########################################  
	if ($search_string === "") {
		$error = true;
		$mensaje_de_error = "No se ha ingresado ningún dato!";
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

			if (($char == "\\") AND (!$string_entre_comillas)) {
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
			} elseif ((($char == "+") OR ($char == "")) AND (!$string_entre_comillas)) {
				$char_ant = trim(substr($search_string, $i - 1, 1));
				if (($char_ant != "") AND ($char_ant != "+") AND ($char_ant != "\"")) {
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
			if ((substr($search_string, 2, 1) == "-") AND (substr($search_string, 5, 1) == "-") AND (strlen($search_string) == 9)) {
				$search_type = "codcat";
				$busqueda_sql[$k] = "cod_cat = '$search_string'";
			} elseif ((substr($search_string, 2, 1) == "_") AND (substr($search_string, 5, 1) == "_") AND (strlen($search_string) == 9)) {
				$search_type = "codcat";
				$search_string = str_replace("_", "-", $search_string);
				$busqueda_sql[$k] = "cod_cat = '$search_string'";
			} elseif (((substr($search_string, 1, 1) == "-") OR (substr($search_string, 1, 1) == "_")) AND (strlen($search_string) == 3)) {
				$search_type = "codcat";
				$texto1 = substr($search_string, 0, 1);
				$texto2 = substr($search_string, 2, 1);
				$busqueda_sql[$k] = "cod_uv = '$texto1' AND cod_man = '$texto2'";
			} elseif (((substr($search_string, 1, 1) == "-") OR (substr($search_string, 1, 1) == "_")) AND (strlen($search_string) == 4)) {
				$search_type = "codcat";
				$texto1 = substr($search_string, 0, 1);
				$texto2 = substr($search_string, 2, 2);
				$busqueda_sql[$k] = "cod_uv = '$texto1' AND cod_man = '$texto2'";
			} elseif (((substr($search_string, 2, 1) == "-") OR (substr($search_string, 2, 1) == "_")) AND (strlen($search_string) == 5)) {
				$search_type = "codcat";
				$texto1 = (int) substr($search_string, 0, 2);
				$texto2 = (int) substr($search_string, 3, 2);
				$busqueda_sql[$k] = "cod_uv = '$texto1' AND cod_man = '$texto2'";
			} elseif (check_int($search_string)) {
				$search_type = "numeros";
				$busqueda_sql[$k] = "doc_num ~* '$search_string'";
			} elseif ((strlen($search_string) > 3) AND ((substr($search_string, strlen($search_string) - 3, 3) == "/01") OR (substr($search_string, strlen($search_string) - 3, 3) == "/02") OR (substr($search_string, strlen($search_string) - 3, 3) == "/03"))) {
				$search_type = "numeros";
				$busqueda_sql[$k] = "cod_pad ~* '$search_string'";
			} elseif ((strpos($search_string, "c/") !== false) OR (strpos($search_string, "C/") !== false) OR (strpos($search_string, "Av/") !== false) OR (strpos($search_string, "AV/") !== false)) {
				$search_type = "direccion";
				$pos = strpos($search_string, "/") + 1;
				$texto = substr($search_string, $pos, strlen($search_string) - $pos);
				$busqueda_sql[$k] = "dir_nom ~* '$texto'";
			} elseif ((strpos($search_string, "Calle") !== false) OR (strpos($search_string, "Avenida") !== false) OR (strpos($search_string, "Av.") !== false) OR (strpos($search_string, "AV.") !== false)) {
				$search_type = "direccion";
				$pos = strpos($search_string, " ") + 1;
				if ($pos == 1) {
					$search_string == "";
					$busqueda_sql[$k] = "cod_geo = '$cod_geo'";
				} else {
					$texto1 = substr($search_string, 0, $pos - 1);
					$texto2 = substr($search_string, $pos, strlen($search_string) - $pos);
					$busqueda_sql[$k] = "dir_nom ~* '$texto2'";
				}

			} elseif (($search_string == "Calle") OR ($search_string == "calle") OR ($search_string == "avenida") OR ($search_string == "Avenida") OR ($search_string == "pasillo") OR ($search_string == "Pasillo") OR ($search_string == "plaza") OR ($search_string == "Plaza")) {
				$search_type = "direccion";
				$pos = strpos($search_string, " ") + 1;
				$texto1 = substr($search_string, 0, $pos - 1);
				$texto2 = substr($search_string, $pos, strlen($search_string) - $pos);
				$busqueda_sql[$k] = "dir_nom ~* '$texto2'";
			} else {
				$search_type = "nombre";
				if (strpos($search_string, " ") !== false) {
					$pos = strpos($search_string, " ") + 1;
					$texto1 = substr($search_string, 0, $pos - 1);
					$texto2 = substr($search_string, $pos, strlen($search_string) - $pos);

					$textconv1 = textconvert($texto1);
					$textconv2 = textconvert($texto2);
					$busqueda_sql[$k] = "(con_nom1 ~* '$texto1' AND con_pat ~* '$texto2') OR (con_pat ~* '$texto1' AND con_mat ~* '$texto2')";
				} else {
					$busqueda_sql[$k] = "con_pat ~* '$search_string' OR con_mat ~* '$search_string' OR con_nom1 ~* '$search_string' OR con_nom2 ~* '$search_string' OR doc_num ~* '$search_string'";

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
		$i = 1;
		$k = 1;
		while ($i < $no_de_criterios) {
			$where = "(" . $where . ") AND (" . $busqueda_sql[$i] . ")";
			$i++;
		}
	
		########################################
		#------- BUSCAR STRING NOMBRE ---------#
		########################################
		if ($search_type == "nombre" or $search_type == "numeros") {
			$sql = "SELECT id_contrib FROM contribuyentes WHERE $where ORDER BY id_contrib";
			$check_contrib = pg_num_rows(pg_query($sql));
			if ($check_contrib == 1) {
				if ($mod == 121) { 
					$sql = "SELECT id_contrib, con_pmc, con_raz, con_nom1, con_nom2, con_pat, con_mat, 
	                   doc_tipo, doc_num, doc_exp FROM contribuyentes WHERE $where ORDER BY id_contrib";
				}
			} elseif ($check_contrib > 1) {

				if ($mod == 1) {  
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
					} 
					$where = $where . ")";
					$where_trans = $where_trans . ")";
					$sql = "SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id 
			            FROM info_inmu WHERE $where ORDER BY cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto";
				} elseif ($mod == 121) {
					$sql = "SELECT id_contrib, con_pmc, con_raz, con_nom1, con_nom2, con_pat, con_mat, 
	                   doc_tipo, doc_num, doc_exp FROM contribuyentes WHERE $where ORDER BY id_contrib";
				}
			} else {
				$sql = "SELECT id_inmu FROM info_inmu WHERE id_inmu = '-1'";
				$where_trans = "id_inmu = '-1'";

			}
			$check_integrity = pg_num_rows(pg_query($sql));
			##### CHEQUEAR TABLA TRANSFER #####
			if ($check_integrity > 0) {
				$resultado = true;
			} else {
				$sql = "SELECT DISTINCT id_inmu FROM transfer WHERE $where_trans ORDER BY id_inmu";
				$check_id = pg_num_rows(pg_query($sql));
				if ($check_id == 1) {
					$result = pg_query($sql);
					$info = pg_fetch_array($result, null, PGSQL_ASSOC);
					$id_inmu = $info['id_inmu'];
					pg_free_result($result);
					$sql = "SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id 
			         FROM info_inmu WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' ORDER BY cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto";
				}
			}
		} elseif ($search_type == "numero") {

		}
		$check_integrity = pg_num_rows(pg_query($sql));
		########################################
		#           STRING ENCONTRADO          #
		########################################			
		if ($check_integrity > 0) {
			$resultado = true;
		} else {
			$error = true;
			$mensaje_de_error = "La búsqueda en la base de datos no tenía resultado";
			$resultado = false;
		}
	}
}

################################################################################
#------------------------------ RELLENAR ARRAYS -------------------------------#
################################################################################	
if ($buscar) {
	$filas = pg_num_rows(pg_query($sql));
	$result = pg_query($sql);
	$i = $j = $k = 0;
	$m = 25;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		foreach ($line as $col_value) {
			$value[$i] = $col_value;
			$i++;
		}
		if ($mod == 121) {   ### CONTRIBUYENTES
			$valor_submit[$j] = $value[0];
			$valor1[$j] = $value[1];
			$texto1 = $value[3];
			$texto2 = $value[4];
			$texto3 = strtoupper(ucase($value[5]));
			$texto4 = strtoupper(ucase($value[6]));
			$propietario = $texto3 . " " . $texto4 . ", " . $texto1 . " " . $texto2;
			$valor2[$j] = $propietario;
			$texto1 = $value[7];
			$texto2 = $value[8];
			$texto3 = $value[9];
			$valor3[$j] = $texto1 . " " . $texto2 . " " . $texto3;
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
		$mensaje_de_error = "No se ha encontrado ningún registro en la base de datos";
		$resultado = false;
	}
}
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################
# estilos para mejorar la apariencia

echo "<style>
body { font-family: Arial, Helvetica, sans-serif; margin: 0; padding: 0; }
.container { max-width: 800px; margin: 0 auto; padding: 20px; }
.pageName { font-size: 24px; color: #6699cc; margin-bottom: 20px; text-align: center; }
fieldset { border: 1px solid #ccc; padding: 20px; margin: 20px 0; border-radius: 5px; }
legend { font-weight: bold; }
.form-group { display: flex; align-items: center; margin-bottom: 15px; }
.form-group label { width: 30%; font-weight: bold; margin-right: 10px; }
.form-group input[type=\"text\"] { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
.form-group input[type=\"submit\"] { padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
.form-group input[type=\"submit\"]:hover { background-color: #45a049; }
table { border-collapse: collapse; width: 100%; border: 1px solid #ddd; }
th, td { padding: 8px; text-align: center; border-bottom: 1px solid #ddd; }
th { background-color: #c6dbf1; font-weight: bold; font:12px Arial, Helvetica, sans-serif; color:#666666; }
tr:hover { background-color: #f5f5f5;}
.alt { background-color: #e8e6e6; }
.error { color: #721c24; font-weight: bold; padding: 10px; background-color: #f8d7da; border: 1px solid #ffcccc; border-radius: 4px; margin: 10px 0; border-left: 5px solid #f5c6cb; text-align: center  }
.results { margin-top: 20px; }
.scrollable { height: 400px; overflow-y: auto; border: 1px solid #ddd; }
</style>\n";


echo "<td>\n";
echo "<div class=\"container\">\n";
echo "<h1 class=\"pageName\">Buscar Contribuyente</h1>\n";
echo "<fieldset><legend>Ingrese el atributo por el que quiere buscar</legend>\n";
echo "<form name=\"isc\" method=\"post\" action=\"index.php?mod=123&id=$session_id\" accept-charset=\"utf-8\">\n";
echo "<div class=\"form-group\">\n";
echo "<label for=\"con_pmc\">Padrón Municipal (PMC)</label>\n";
echo "<input id=\"con_pmc\" name=\"con_pmc\" type=\"text\" maxlength=\"$max_strlen_pmc\" value=\"$con_pmc\">\n";
echo "<input name=\"busqueda\" type=\"submit\" value=\"Buscar\" onClick=\"go()\">\n";
echo "</div>\n";
echo "</form>\n";
echo "<form name=\"isc\" method=\"post\" action=\"index.php?mod=$mod&id=$session_id\" accept-charset=\"utf-8\">\n";
echo "<div class=\"form-group\">\n";
echo "<label for=\"search_string\">Nombre del Contribuyente o No. de Carnet</label>\n";
echo "<input id=\"search_string\" name=\"search_string\" type=\"text\" value=\"$search_string\">\n";
echo "<input name=\"busqueda2\" type=\"submit\" value=\"Buscar\" onClick=\"go()\">\n";
echo "</div>\n";
echo "</form>\n";
echo "</fieldset>\n";

if ($error) {
echo "<div class=\"error\">$mensaje_de_error</div>\n";
} elseif ($buscar AND $resultado) {
echo "<div class=\"results\">\n";
echo "<h2>Resultado de la búsqueda: $check_contrib</h2>\n";
echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=$pag&id=$session_id\" accept-charset=\"utf-8\">\n";
echo "<input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";
echo "<div style=\"margin-bottom: 10px; text-align: center;\">\n";
echo "<input name=\"Submit\" type=\"submit\" value=\"Ver\" />\n";
echo "</div>\n";
echo "<div class=\"scrollable\">\n";
echo "<table>\n";
echo "<thead>\n";
echo "<tr>\n";
echo "<th width=\"5%\">Seleccionar</th>\n";
echo "<th width=\"10%\">$titulo1</th>\n";
echo "<th width=\"60%\">$titulo2</th>\n";
echo "<th width=\"25%\">$titulo3</th>\n";
echo "</tr>\n";
echo "</thead>\n";
echo "<tbody>\n";
$i = 0;
while ($i < $filas) {
$class = ($i % 2 == 0) ? '' : 'alt';
echo "<tr class=\"$class\">\n";
if ($i == 0) {
echo "<td><input name=\"$var_submit\" value=\"$valor_submit[$i]\" type=\"radio\" checked=\"checked\"></td>\n";
} else {
echo "<td><input name=\"$var_submit\" value=\"$valor_submit[$i]\" type=\"radio\"></td>\n";
}
echo "<td>$valor1[$i]</td>\n";
echo "<td>$valor2[$i]</td>\n";
echo "<td>$valor3[$i]</td>\n";
echo "</tr>\n";
$i++;
}
echo "</tbody>\n";
echo "</table>\n";
echo "</div>\n";
echo "<div style=\"margin-top: 10px; text-align: center;\">\n";
echo "<input name=\"Submit\" type=\"submit\" value=\"Ver\" />\n";
echo "</div>\n";
echo "</form>\n";
echo "</div>\n";
} elseif ($buscar AND !$resultado) {
echo "<h3 style=\"color: red;\">Busqueda sin resultado...</h3>\n";
echo "<p>Código catastral no existe: $cod_cat,\n";
echo "el padron municipal: $cod_pad, el nombre del\n";
echo "títular: $nombre1 o el \n";
echo "apellido del titular: $apellido1 en la base de datos</p>\n";
}
echo "</div>\n";
echo "</td>\n";
?>