<?php

$sql = "SELECT id_contrib, con_tipo, con_raz, con_pat, con_mat, con_nom1, con_nom2 FROM contribuyentes ORDER BY con_pat, con_mat, con_nom1, con_nom2 ASC";
$result = pg_query($sql);
$i = $j = 0;
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	foreach ($line as $col_value) {
		if ($i == 0) {
			$id_contrib_lista[$j] = $col_value;
			$id_contrib_lista2[$j] = $col_value;
		} elseif ($i == 1) {
			$con_tipo = $col_value;
		} elseif ($i == 2) {
			$con_raz = $col_value;
		} elseif ($i == 3) {
			$con_pat = $col_value;
		} elseif ($i == 4) {
			$con_mat = $col_value;
		} elseif ($i == 5) {
			$con_nom1 = $col_value;
		} elseif ($i == 6) {
			$con_nom2 = $col_value;
			if ($con_tipo == "EMP") {
				$contribuyente[$j] = $con_raz;
			} else {
				if ($con_nom1 == "") {
					$contribuyente[$j] = trim($con_pat . " " . $con_mat);
				} else {
					$contribuyente[$j] = trim($con_pat . " " . $con_mat . ", " . $con_nom1 . " " . $con_nom2);
				}
			}
			$i = -1;
		}
		$i++;
	}
	$j++;
}
$no_de_contribuyentes = $j;
pg_free_result($result);
?>