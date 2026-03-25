<?php

###############################
#----- AREA EDIFICACIONES ----#
###############################
$sup_const[$j] = $edi_area;
$fec_edi = $gestion[$j];
$sql = "SELECT sum(area(a.the_geom)) 
   FROM edificaciones  AS a
   INNER  JOIN (SELECT  * FROM info_edif WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND edi_ano<='$fec_edi') AS b
   ON a.cod_geo = b.cod_geo AND a.cod_uv = b.cod_uv AND a.cod_man = b.cod_man AND a.cod_pred = b.cod_pred AND a.edi_num = b.edi_num";
$result = pg_query($sql);
$suma_area_edif = pg_fetch_array($result, null, PGSQL_ASSOC);
$edi_area = $suma_area_edif['sum'];
$sup_const[$j] = ROUND($edi_area, 2);

########################################
#------ CANTIDAD DE EDIFICACIONES -----#
########################################		  
$sql = "SELECT * 
		   FROM info_edif 
		   WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND edi_ano<='$gestion'
		   ORDER BY edi_num ASC";
$no_de_edificaciones = pg_num_rows(pg_query($sql));


if ($no_de_edificaciones == 0) {
    $edi_tipo[$j] = "---";
    $ant_const[$j] = 0;
    $factor_deprec[$j] = 0;
    $calidad_const[$j] = 0;
    $avaluo_const[$j] = 0;
} else {
    ########################################
    #- VALUACION POR MATERIALES DE CONST.--#
    ########################################				
    $i = 0;
    $k = 0;
    $result_edif = pg_query($sql);
    while ($line = pg_fetch_array($result_edif, null, PGSQL_ASSOC)) {
        $line_value[$i] = $no_de_objetos_validos[$i] = 0;
        foreach ($line as $col_value) {
            $column_edif = get_column_edif($k);
            if ($column_edif == "edi_tipo") {
                $edi_tipo_temp[$i] = $col_value;
            }
            if ($column_edif == "edi_ano") {
                $edi_ano_temp[$i] = $col_value;
            }
            $sql = "SELECT valuacion FROM imp_valua_viv_materiales WHERE tipo = '$column_edif' AND material = '$col_value'";
            $check_valua = pg_num_rows(pg_query($sql));
            if ($check_valua > 0) {
                $result_valua = pg_query($sql);
                $info_valua = pg_fetch_array($result_valua, null, PGSQL_ASSOC);
                $valua_temp = trim($info_valua['valuacion']);
                if ($valua_temp == "margin") {
                    $line_value[$i] = $line_value[$i] + 1;
                } elseif ($valua_temp == "mecono") {
                    $line_value[$i] = $line_value[$i] + 2;
                } elseif ($valua_temp == "econo") {
                    $line_value[$i] = $line_value[$i] + 3;
                } elseif ($valua_temp == "bueno") {
                    $line_value[$i] = $line_value[$i] + 4;
                } elseif ($valua_temp == "mbueno") {
                    $line_value[$i] = $line_value[$i] + 5;
                } elseif ($valua_temp == "lujoso") {
                    $line_value[$i] = $line_value[$i] + 6;
                }
                $no_de_objetos_validos[$i]++;
            }
            $edi_temp[$i][$k] = $col_value;
            $k++;
        }
        $line_media[$i] = $line_value[$i] / $no_de_objetos_validos[$i];
        $k = 0;
        $i++;
    }

    ########################################
    #-------- CALCULAR PORCENTAJES --------#
    ########################################


    $avaluo_const[$j] = 0;
    $valuacion_balanceada[$j] = 0;
    $i = 0;
    $check_second = false;
    while ($i < $no_de_edificaciones) {
        $k = $i + 1;
        $sql = "SELECT area(a.the_geom) 
				FROM edificaciones  AS a
				INNER  JOIN (SELECT  * FROM info_edif WHERE edi_num = '$k' AND cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND edi_ano<='$fec_edi') AS b
				ON a.cod_geo = b.cod_geo AND a.cod_uv = b.cod_uv AND a.cod_man = b.cod_man AND a.cod_pred = b.cod_pred AND a.edi_num = b.edi_num";
        $check_area_edif = pg_num_rows(pg_query($sql));


        if ($check_area_edif > 0) {
            $result = pg_query($sql);
            $area_edif = pg_fetch_array($result, null, PGSQL_ASSOC);
            $area = $area_edif['area'];

            $area_edif_temp[$i] = ROUND($area_edif['area'], 2);
            $factor_edif[$i] = $area_edif_temp[$i] / $edi_area;
            $line_media_ajustada[$i] = $line_media[$i] * $factor_edif[$i];
            $valuacion_balanceada[$j] = $valuacion_balanceada[$j] + $line_media_ajustada[$i];
        } else
            $area_edif_temp[$i] = 0;
        $calidad_const_temp[$i] = imp_calidad_const($gestion[$j], $line_media[$i]);
        #echo "<br>AREA $edi_tipo_temp[$i]: $area_edif_temp[$i], VALUACION: $line_media[$i], CALIDAD CONTRUCION $calidad_const_temp[$i]   $gestion[$j] EDIFI_ANO $edi_ano_temp[$i]<br>";
        if ($calidad_const_temp[$i] == 0) {
            $error = true;
            $mensaje_de_error = "Error: Por favor, ingrese la cotización UFV del 31 de diciembre de $gestion[$j]!";
        } elseif ($calidad_const_temp[$i] == -1) {
            $error = true;
            $mensaje_de_error = "Error: La tabla 'A. Valuación de Construcciones' no tiene valores para la gestión $gestion[$j]. Por favor, revise la tabla!";
        }
        $factor_deprec_temp[$i] = imp_factor_deprec($gestion[$j], $edi_ano_temp[$i], $ano_actual);
        #echo "<br>GESTION $gestion[$j], ANO DE CONSTR $edi_ano_temp[$i], FECHA ACTUAL$ano_actual, VALOR DEVUELTO $factor_deprec_temp[$i]<br>";	
        $avaluo_const_temp[$i] = avaluo_const($calidad_const_temp[$i], $area_edif_temp[$i], $factor_deprec_temp[$i]);
        if ($check_second) {
            if ($avaluo_const_temp[$i] > $avaluo_const_mas_alto[$j]) {
                $edi_tipo[$j] = $edi_tipo_temp[$i];
                $ant_const[$j] = $edi_ano_temp[$i];
                $factor_deprec[$j] = $factor_deprec_temp[$i];
                $calidad_const[$j] = $calidad_const_temp[$i];
                $avaluo_const_mas_alto[$j] = $avaluo_const_temp[$i];
            }
        } else {
            $edi_tipo[$j] = $edi_tipo_temp[0];
            $ant_const[$j] = $edi_ano_temp[0];
            $factor_deprec[$j] = $factor_deprec_temp[0];
            $calidad_const[$j] = $calidad_const_temp[0];
            $avaluo_const_mas_alto[$j] = $avaluo_const_temp[0];
        }
        $avaluo_const[$j] = $avaluo_const[$j] + $avaluo_const_temp[$i];
        $check_second = true;
        $i++;
    }
}
$valor_vi = $avaluo_const[$j];

?>