<?php
################################################################################
#---------------------- ESCRIBIR FACTOR ZOOM EN TABLA -------------------------#
################################################################################	
if (isset($_POST['factor'])) {
    $factor = $_POST['factor'];
    $sql = "SELECT factor FROM plano_cat_zoom WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
    $check_zoom = pg_num_rows(pg_query($sql));
    if ($check_zoom > 0) {
        if ($factor == 1) {
            pg_query("DELETE FROM plano_cat_zoom WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");
        } else {
            pg_query("UPDATE plano_cat_zoom SET factor = '$factor' WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");
        }
    } elseif ($factor != 1) {
        pg_query("INSERT INTO plano_cat_zoom (cod_geo, id_inmu, factor) VALUES ('$cod_geo','$id_inmu','$factor')");
    }
}

########################################
#      Chequear si existen filas       #
########################################	
$sql = "SELECT cod_uv FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check_predio = pg_num_rows(pg_query($sql));
################################################################################
#---------------------- SELECCIONAR GEOMETRIA PARA IFRAME ---------------------#
################################################################################	
if ($check_predio > 0) {
    $predio_existe = true;
    $result1 = pg_query("SELECT (extent3d(the_geom)) FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
    $str_extent = pg_fetch_array($result1, null, PGSQL_ASSOC);
    $extent = $str_extent['extent3d'];
    #------------------------- Extraer coordenadas de EXTENT
    $xt_x = $xtent_x = array();
    $xt_y = $xtent_y = array();
    $x = 0;
    $z = 0;
    $i = 0;
    $j = 6;
    $longitud = strlen($extent);
    while ($i <= $longitud) {
        $char = substr($extent, $i, 1);
        if (($char == ' ') and ($z == 0)) {
            $xt_x[$x] = substr($extent, $j, $i - $j);
            $xtent_x[$x] = ROUND($xt_x[$x], 3);
            $j = $i + 1;
            $z = 1;
        } else if (($char == ' ') and ($z == 1)) {
            $xt_y[$x] = substr($extent, $j, $i - $j);
            $xtent_y[$x] = ROUND($xt_y[$x], 3);
            $j = $i + 3;
            $z = 0;
            $x++;
        }
        $i++;
    }

    $centerx = ($xtent_x[0] + $xtent_x[1]) / 2;
    $centery = ($xtent_y[0] + $xtent_y[1]) / 2;

    ########################################
    #------ LEER FACTOR ZOOM DE TABLA -----#
    ########################################	
    $sql = "SELECT factor FROM plano_cat_zoom WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
    $check_zoom = pg_num_rows(pg_query($sql));
    if ($check_zoom > 0) {
        $result = pg_query($sql);
        $info = pg_fetch_array($result, null, PGSQL_ASSOC);
        $factor_delta = $info['factor'];
        $factor_zoom = $factor_zoom_plano_catastral * $factor_delta;
        pg_free_result($result);
    } else {
        $factor_zoom = $factor_zoom_plano_catastral;    //2.5
    }
    # FACTOR ZOOM PARA PLANO DE UBICACION
    $ext_x = sqrt(($xtent_x[0] - $xtent_x[1]) * ($xtent_x[0] - $xtent_x[1]));
    $ext_y = sqrt(($xtent_y[0] - $xtent_y[1]) * ($xtent_y[0] - $xtent_y[1]));
    #echo "EXT X y es $ext_x, EXT Y y es $ext_y<br />\n";				 
    if (($ext_x > 110) or ($ext_y > 110)) {
        $factor_zoom1 = 5;
    } elseif (($ext_x > 90) or ($ext_y > 90)) {
        $factor_zoom1 = 12;
    } elseif (($ext_x > 70) or ($ext_y > 70)) {
        $factor_zoom1 = 7;
    } elseif (($ext_x > 50) or ($ext_y > 50)) {
        $factor_zoom1 = 8;
    } elseif (($ext_x > 30) or ($ext_y > 30)) {
        $factor_zoom1 = 10;
    } else {
        $factor_zoom = 3.2;
        $factor_zoom1 = 12;
    }

    if ($centerx - $xtent_x[0] > $centery - $xtent_y[0]) {
        $delta = ($centerx - $xtent_x[0]) * $factor_zoom;
        $delta1 = ($centerx - $xtent_x[0]) * $factor_zoom1;
    } else {
        $delta = ($centery - $xtent_y[0]) * $factor_zoom;
        $delta1 = ($centery - $xtent_y[0]) * $factor_zoom1;
    }


    $xmin = $centerx - $delta;
    $xmax = $centerx + $delta;
    $ymin = $centery - $delta;
    $ymax = $centery + $delta;

    //$delta1_y = $delta1 * 1.1;  // 20% más alto
    $xmin1 = $centerx - $delta1;
    $xmax1 = $centerx + $delta1;
    $ymin1 = $centery - $delta1; // más hacia abajo;
    $ymax1 = $centery + $delta1; // un poco hacia arriba;
    pg_free_result($result1);
    ########################################
    #   ENVIAR SELECCIONADO A TEMP-POLY    #
    ########################################		
    pg_query("DELETE FROM temp_poly WHERE user_id = '$user_id' OR user_id IS NULL");
    pg_query("INSERT INTO temp_poly (edi_num, edi_piso, the_geom) SELECT edi_num, edi_piso, the_geom FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
    pg_query("UPDATE temp_poly SET user_id = '$user_id', numero = 44 WHERE edi_num > '0' AND user_id IS NULL");
    pg_query("INSERT INTO temp_poly (cod_uv, cod_man, cod_pred, the_geom) SELECT cod_uv, cod_man, cod_pred, the_geom FROM predios_ocha WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
    pg_query("UPDATE temp_poly SET user_id = '$user_id', cod_cat = '$cod_cat', numero = 55 WHERE user_id IS NULL AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
    pg_query("INSERT INTO temp_poly (cod_uv, cod_man, cod_pred, the_geom) SELECT cod_uv, cod_man, cod_pred, the_geom FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
    pg_query("UPDATE temp_poly SET user_id = '$user_id', cod_cat = '$cod_cat', numero = 58 WHERE user_id IS NULL AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
}
################################################################################
#----------------------------- CALCULAR ESCALA --------------------------------#
################################################################################	
#Factor 0.63 generado por medicion en la impresion
$extension_real = 2 * $delta * 0.63;
$extension_en_papel = 0.185;
$escala = $extension_real / $extension_en_papel;
$escala = ROUND($escala / 100, 0) * 100;

if ($escala <= 500) {
    $mininterval = 50;
} elseif ($escala <= 1000) {
    $mininterval = 100;
} elseif ($escala <= 2000) {
    $mininterval = 200;
} elseif ($escala <= 3000) {
    $mininterval = 500;
} else {
    $mininterval = 1000;
}

$maxinterval = $mininterval;

# FUNCION GET_ZONA
$ben_zona = get_zona($id_inmu);
if ($ben_zona == "0") {
    $ben_zona = "-";
}
# FUNCION GET_BARRIO
$barrio = get_barrio($id_inmu);
if ($barrio == "0") {
    $barrio = "-";
}
### LEER DATOS DE PROPIETARIO DE INFO_INMU Y DATOS DEL PREDIO DE INFO_PREDIO

include "siicat_planos_leer_datos.php";

################################################################################
#-------------------------- DEFINIR ZONA DEL PREDIO ---------------------------#
################################################################################
$zona = get_zona_brujula($cod_cat, $centro_del_pueblo_para_zonas_x, $centro_del_pueblo_para_zonas_y);
if ($zona === "SE") {
    $zona = "SUR ESTE";
} elseif ($zona === "SO") {
    $zona = "SUR OESTE";
} elseif ($zona === "NE") {
    $zona = "NOR ESTE";
} elseif ($zona === "NO") {
    $zona = "NOR OESTE";
} elseif ($zona === "N") {
    $zona = "NORTE";
} elseif ($zona === "S") {
    $zona = "SUR";
} elseif ($zona === "E") {
    $zona = "ESTE";
} elseif ($zona === "O") {
    $zona = "OESTE";
}

################################################################################
#---------------------------------- NOTA --------------------------------------#
################################################################################
$sql = "SELECT nota_plano FROM imp_base";
$result_nota = pg_query($sql);
$info = pg_fetch_array($result_nota, null, PGSQL_ASSOC);
$nota_plano_catastral = utf8_decode($info['nota_plano']);
pg_free_result($result_nota);
########################################
#------- CALCULAR AREA PREDIO ---------#
########################################
$sql = "SELECT area(the_geom) FROM predios_ocha WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result = pg_query($sql);
$value = pg_fetch_array($result, null, PGSQL_ASSOC);
$area = ROUND($value['area'], 2);
pg_free_result($result);
########################################
#----- CALCULAR AREA EDIFICACIONES ----#
########################################
$sql = "SELECT area(the_geom) FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check = pg_num_rows(pg_query($sql));
if ($check == 0) {
    $edi_area = 0;
} else {
    $result = pg_query($sql);
    $edi_area = 0;
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        foreach ($line as $col_value) {
            $edi_area = $edi_area + $col_value;
        }
    } # END_OF_WHILE	
    $edi_area = ROUND($edi_area, 2);
    pg_free_result($result);
}
########################################
#------ COORDENADAS DE VERTICES -------#
########################################
$sql = "SELECT AsText(the_geom),npoints(the_geom) FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result = pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$coord_poly = $info['astext'];
$no_de_vertices = $info['npoints'] - 1;
pg_free_result($result);
include "siicat_extract_coordpoly.php";
################################################################################
#--------------------- DEFINIR POSICION Y ANGULO DE ETIQUETAS -----------------#
################################################################################
$result = pg_query("SELECT x(centroid(the_geom)),y(centroid(the_geom)) FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
$cstr = pg_fetch_array($result, null, PGSQL_ASSOC);
$centroid_x = $cstr['x'];
$centroid_y = $cstr['y'];
pg_free_result($result);
$i = 0;
while ($i < $no_de_vertices) {
    $dx = $point_x[$i] - $centroid_x;
    $dy = $point_y[$i] - $centroid_y;
    $angulo_rad = atan2($dy, $dx);                  // Ángulo en radianes
    $angulo_deg = rad2deg($angulo_rad);             // Convertir a grados
    if ($angulo_deg < 0)
        $angulo_deg += 360;        // Asegura valores positivos (0–360)


    $point_x[$i] = number_format($point_x[$i], 3, '.', '');
    $point_y[$i] = number_format($point_y[$i], 3, '.', '');
    $pos[$i] = get_position4($point_x[$i], $point_y[$i], $centroid_x, $centroid_y);
    $angulo[$i] = round($angulo_deg, 2);
    $i++;
}
################################################################################
#--------------------- ESCRIBIR PUNTOS EN TEMP_POINT --------------------------#
################################################################################
pg_query("DELETE FROM temp_point WHERE user_id = '$user_id'");
$i = 0;
while ($i < $no_de_vertices) {
    $no_de_punto = $i + 1;
    pg_query("INSERT INTO temp_point (user_id, cod_cat, text, pos, the_geom, angulo) 
     VALUES ('$user_id','$cod_cat','V$no_de_punto','$pos[$i]','{$esc1}$point_x[$i] $point_y[$i])','$angulo[$i]')");
    $i++;
}
################################################################################
#---------------------- ESCRIBIR LINEAS EN TEMP_LINE --------------------------#
################################################################################
pg_query("DELETE FROM temp_line WHERE user_id = '$user_id'");
$window_ext = $xmax - $xmin;
$min_value = 0.01 * $window_ext;
#echo "MIN_VALUE is $min_value m";	 
$i = $a = $b = $c = 0;
$j = 1;
while ($i <= $no_de_vertices - 1) {
    if ($i == $no_de_vertices - 1) {
        $j = 0;
    }
    $c = ROUND(get_linelen($point_x[$j], $point_y[$j], $point_x[$i], $point_y[$i]), 2);
    if ($c > $min_value) {
        $c = $c . "";
        pg_query("INSERT INTO temp_line (user_id, id, nombre , the_geom) VALUES ('$user_id','$j','$c', '{$esc2}$point_x[$i] $point_y[$i],$point_x[$j] $point_y[$j]{$esc3}')");
    }
    $i++;
    $j++;
}
pg_query("INSERT INTO temp_line SELECT '$user_id', '99' ,radio, the_geom FROM ochaves_linea WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");

################################################################################
#------------------------- CHEQUEAR POR COLINDANTES ---------------------------#
################################################################################
$sql = "SELECT cod_uv, cod_man, cod_pred FROM predios WHERE st_dwithin ((SELECT the_geom FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'),the_geom,1)
      AND activo = '1' AND NOT (cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred')";
$result = pg_query($sql);
$i = $j = 0;
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    foreach ($line as $col_value) {
        if ($i == 0) {
            $cod_uv_col = $col_value;
        } elseif ($i == 1) {
            $cod_man_col = $col_value;
        } else {
            $cod_pred_col = $col_value;
            #$col_cod[$j] = get_codcat ($cod_uv_col,$cod_man_col,$cod_pred_col,0,0,0);
            $col_cod[$j] = $cod_pred_col;
            $sql = "SELECT tit_1id FROM info_inmu WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_col' AND cod_man = '$cod_man_col' AND cod_pred = '$cod_pred_col'";
            $check = pg_num_rows(pg_query($sql));
            if ($check == 0) {
                $titular = "S/N";
            } elseif ($check == 1) {
                $result2 = pg_query($sql);
                $col_nom = pg_fetch_array($result2, null, PGSQL_ASSOC);
                $id_contrib = $col_nom['tit_1id'];
                pg_free_result($result2);
                $titular = get_contrib_nombre($id_contrib);
            } else {
                $titular = "Varios";
            }
            #SOLO MOSTRAR CODIGO EN EL PLANO
            $col_tit[$j] = $col_cod[$j];

            ########################################
            #----------- RELLENAR TABLA -----------#
            ########################################
            $result2 = pg_query("SELECT x(centroid(the_geom)),y(centroid(the_geom))
				                    FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_col' AND cod_man = '$cod_man_col' AND cod_pred = '$cod_pred_col'");
            $cstr = pg_fetch_array($result2, null, PGSQL_ASSOC);
            $col_centroid_x = $cstr['x'];
            $col_centroid_y = $cstr['y'];
            pg_free_result($result2);
            $result2 = pg_query("SELECT xmin(extent3d(the_geom)),xmax(extent3d(the_geom)),ymin(extent3d(the_geom)),ymax(extent3d(the_geom))
            FROM predios 
            WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
            $cstr = pg_fetch_array($result2, null, PGSQL_ASSOC);
            pg_free_result($result2);
            ########################################
            #------------ RELLENAR TABLA ----------#
            ########################################
            pg_query("INSERT INTO temp_poly (cod_uv,cod_man, cod_pred, the_geom) SELECT cod_uv, cod_man, cod_pred, the_geom FROM predios WHERE activo = '1' AND cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_col' AND cod_man = '$cod_man_col' AND cod_pred = '$cod_pred_col' AND cod_pred<>0");

            pg_query("UPDATE temp_poly SET user_id = '$user_id', cod_cat = '$col_cod[$j]', numero = '66', label = '$col_tit[$j]' WHERE user_id is NULL AND cod_uv = '$cod_uv_col' AND cod_man = '$cod_man_col' AND cod_pred = '$cod_pred_col'");
            $j++;
            $i = -1;
        }
        $i++;
    }
}
$no_de_colindantes = $j;
pg_free_result($result);
################################################################################
#--------------------- ESCRIBIR COLINDANTES EN TEMP_POLY ----------------------#
################################################################################
$i = 0;
pg_query("DELETE FROM temp_poly WHERE user_id = '$user_id' AND numero = '5' AND NOT (cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred')");
while ($i < $no_de_colindantes) {
    $cod_uv_col = get_uv($col_cod[$i]);
    $cod_man_col = get_man($col_cod[$i]);
    $cod_pred_col = get_pred($col_cod[$i]);
    pg_query("INSERT INTO temp_poly (cod_uv, cod_man, cod_pred, the_geom) SELECT cod_uv, cod_man, cod_pred, the_geom FROM predios WHERE activo = '1' AND cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_col' AND cod_man = '$cod_man_col' AND cod_pred = '$cod_pred_col'");
    pg_query("UPDATE temp_poly SET user_id = '$user_id', cod_cat = '$col_cod[$i]', numero = '5' WHERE user_id is NULL AND cod_uv = '$cod_uv_col' AND cod_man = '$cod_man_col' AND cod_pred = '$cod_pred_col'");
    $i++;
}
################################################################################
#--------------- SELECCIONAR MANZANOS PARA MAPA DE UBICACION ------------------#
################################################################################
$distancia_desde_predio = 150;
$sql = "SELECT cod_uv, cod_man, the_geom FROM manzanos WHERE st_dwithin ((SELECT the_geom FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'),the_geom,$distancia_desde_predio)";
$result = pg_query($sql);
$i = $j = 0;
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    foreach ($line as $col_value) {
        if ($i == 0) {
            $cod_uv_temp = $col_value;
        } elseif ($i == 1) {
            $cod_man_temp = $col_value;
        } else {
            $the_geom_temp = $col_value;
            $number_temp = 115;
            $label_temp = "U.V. " . $cod_uv_temp . "@MZ. " . $cod_man_temp;
            pg_query("INSERT INTO temp_poly (user_id, cod_uv, cod_man, numero, label, the_geom) VALUES ('$user_id','$cod_uv_temp','$cod_man_temp','$number_temp','$label_temp','$the_geom_temp')");
            $i = -1;
        }
        $i++;
    }
    $i = 0;
}
pg_free_result($result);
################################################################################
#------------------------------- GENERAR MAPFILE ------------------------------#
################################################################################	
$titulo1 = "GOBIERNO AUTONOMO MUNICIPAL DE " . $municipio . "<br> SECRETRIA MUNICIPAL DE PLANIFICACION TERRITORIAL <br> Y MEDIO AMBIENTE";
$propietario = utf8_decode($propietario);
//$propietario = strlen($propietario) > 85 ? substr($propietario, 0, 85) : $propietario;
$num_plano = str_pad($cod_pred, 6, "0", STR_PAD_LEFT);
include "planocatastral_generar_mapfile_cvert.php";
include "planocatastral_generar_mapfile_ubicacion.php";

################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	

$filename = "C:/apache/htdocs/tmp/pc" . $cod_cat . ".html";

################################################################################
#------------------------ PREPARAR CONTENIDO PARA IFRAME ----------------------#
################################################################################	
$content = " 
<div align='left'>
<table width='100%' colspan='10' style='
        border: 1px solid black;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 10px;
        font-family: Times New Roman;
        font-size: 8pt;'>

    <tr style='height: 200px;'>
        <td  width='15%' valign='top'>
            <table  width='100%' height='100%'>
                <tr align='center'>
                    <td align='center'>
                        <a href='javascript:print(this.document)'>
                        <img src='http://$server/$folder/css/$nomlog' alt='imagen' width='83' height='100%' border='0'>
                        </a>	
                    </td>
                </tr>
                <tr><td  align='center' style='font-family: Times New Roman; font-size: 8pt; '>FORM S.O.T.06/2025</td></tr> 
                <tr align='center'>  
                    <td align='center'>
                        <font style='font-family: Times New Roman; font-size: 14pt; color: red;'>No $num_plano</font><br />
                    </td> 
                </tr>  
                <tr><td  style='border-bottom: 1px solid black;'>&nbsp</td></tr>  
            </table>
        </td>
        <td  width='85%' valign='top'>
            <table width='100%' height='100%'>
                <tr align='center'>  
                    <td align='center' colspan='10' >
                        <font style='font-family: Times New Roman; letter-spacing: 1px; font-size: 14pt;'>$titulo1 </font>
                    </td>
                </tr>
                <tr align='center'>  
                    <td align='center' colspan='10'>					 
                        <b><font style='font-family: Times New Roman; font-size: 14pt; letter-spacing: 1px;'>PLANO DE UBICACION Y USO DE SUELO</font>
                    </td>
                </tr>   
                <tr>
                    <td align='left' colspan='10'  style='border-bottom: 1px solid black;' >
                        <font style='font-family: Times New Roman; font-size: 9pt; font-style: italic; font-weight: bold;'>&nbsp SOLICITANTE:</font>
                        <font style='font-family: Times New Roman; font-weight: bold; font-size: $font_size_prop;'>$propietario</font>
                    </td>					 				
                </tr>   
                <tr>
                    <td align='left' colspan='2' style='border-bottom: 1px solid black;'>
                        <font style='font-family: Times New Roman; font-size: 9pt; font-style: italic; font-weight: bold;'>&nbsp DISTRITO:</font>
                        <font style='font-family: Times New Roman; font-size: 8pt;'>&nbsp $cod_dis</font>
                    </td>
                    <td align='left' colspan='2' style='border-bottom: 1px solid black;'>
                        <font style='font-family: Times New Roman; font-size: 9pt; font-style: italic; font-weight: bold;'>&nbsp UBICACION:</font>
                        <font style='font-family: Times New Roman; font-size: 8pt;'>&nbsp $distrito</font>
                    </td>	
                    <td align='left' colspan='2' style='border-bottom: 1px solid black;'>
                        <font style='font-family: Times New Roman; font-size: 9pt; font-style: italic; font-weight: bold;'>&nbsp UPU:</font>
                        <font style='font-family: Times New Roman; font-size: 8pt;'>&nbsp </font>
                    </td>			
                    <td align='left' colspan='2' style='border-bottom: 1px solid black;'>
                        <font style='font-family: Times New Roman; font-size: 9pt; font-style: italic; font-weight: bold;'>&nbsp MANZANA:</font>
                        <font style='font-family: Times New Roman; font-size: 8pt;'>&nbsp $cod_man</font>
                    </td>	    
                    <td align='left' colspan='2'  style='border-bottom: 1px solid black;'>
                        <font style='font-family: Times New Roman; font-size: 9pt; font-style: italic; font-weight: bold;'>&nbsp LOTE:</font>
                        <font style='font-family: Times New Roman; font-size: 8pt;'>&nbsp $cod_pred</font>
                    </td>                                    							 				
                </tr>        
                
                
                <tr>
                    <td align='left' colspan='4' style='border-bottom: 1px solid black;'>
                        <font style='font-family: Times New Roman; font-size: 9pt; font-style: italic; font-weight: bold;'>&nbsp SUPERFICIE SEGUN MENSURA:</font>
                    </td>	
                    <td align='left' colspan='1' style='border-bottom: 1px solid black;'>
                        <font style='font-family: Times New Roman; font-size: 8pt;'>$area</font>
                    </td>                    
                    <td align='left' colspan='1' style='border-bottom: 1px solid black;'>
                        <font style='font-family: Times New Roman; font-size: 9pt; font-style: italic; font-weight: bold;'>&nbsp M2</font>
                    </td>                     
                    <td align='left' colspan='4' style='border-bottom: 1px solid black;'>
                        <font style='font-family: Times New Roman; font-size: 9pt; font-style: italic; font-weight: bold;'>&nbsp REGISTRO CATASTRAL:</font>
                        <font style='font-family: Times New Roman; font-size: 8pt;'>&nbsp </font>
                    </td>                    
                </tr>    
                    
                <tr>
                    <td align='left' colspan='4' style='border-bottom: 1px solid black;'>
                        <font style='font-family: Times New Roman; font-size: 9pt; font-style: italic; font-weight: bold;'>&nbsp SUPERFICIE SEGUN TITULO:     </font>
                    </td>	
                    <td align='left' colspan='1' style='border-bottom: 1px solid black;'>
                        <font style='font-family: Times New Roman; font-size: 8pt;'>$adq_sdoc </font>
                    </td>	                    
                    <td align='left' colspan='1' style='border-bottom: 1px solid black;'>
                    </td>                          
                    <td align='left' colspan='3' style='border-bottom: 1px solid black;'>
                        <font style='font-family: Times New Roman; font-size: 9pt; font-style: italic; font-weight: bold;'>&nbsp ESCALA:</font>
                        <font style='font-family: Times New Roman; font-size: 8pt;'>&nbsp 1 : $escala</font>
                    </td> 
                    <td align='left' colspan='1' style='border-bottom: 1px solid black;'>
                        <font style='font-family: Times New Roman; font-size: 9pt; font-style: italic; font-weight: bold;'>&nbsp VALOR:</font>
                        <font style='font-family: Times New Roman; font-size: 16pt; color:gris;'>&nbsp Bs. 20</font>
                    </td>                                       
                </tr>  
                    
            </table>
        </td>
    </tr>
    <tr>
        <td colspan='10' align='right' style='font-family: Arial; font-size: 8pt; font-weight: bold; padding-right: 20px;'>
            PROHIBIDA SU REPRODUCCION
        </td>
    </tr>
    <tr>
        <td colspan='2' align='center'  height='600px' width='100%'>	 
         <iframe frameborder='0' name='mapserver' src='http://$server/cgi-bin/mapserv.exe?map=c:/apache/siicat/mapa/planocatastral.map&program=http://$server/cgi-bin/mapserv.exe&zoomsize=2&layer=Predios&layer=Manzanos&layer=Puntos&layer=Calles&layer=grilla&imgext=$xmin $ymin $xmax $ymax&imgxy=700+550&mapext=&savequery=false&zoomdir=1&mode=map&imgbox=&imgsize=1400+1100&mapsize=1400+1100' 
         id='content' width='100%' height='100%' align='middle' valign='center' scrolling='no' noresize='no' marginwidth='0' marginheight='0'>
            </iframe>
        </td>
    </tr>

    <tr>
        <td align='center' colspan='10' >
            <table width='100%' height='200px' style='font-family: Times New Roman; font-size: 8pt; border-collapse: collapse;'>
                <tr>
                    <td width='30%' height='20px' align='left' style='font-weight: bold; border: 1px solid black;  letter-spacing: 3px;'>OBSERVACIONES</td>
                    <td width='35%' align='center' style='font-weight: bold;  border: 1px solid black;  letter-spacing: 3px;'>CROQUIS DE UBICACION</td>
                    <td width='35%' align='center' style='font-weight: bold;  border: 1px solid black;  letter-spacing: 2px;'>COORDENADAS UTM DATUM WGS-84</td>
                </tr>
                <tr>
                    <td valign='top'>	 
                        <table width='100%' height='100%' style='font-family: Times New Roman; font-size: 8pt;'>
                            <tr><td height='19px' align='center' style='border-bottom: 1px solid black; font-weight: bold;'>PLANO ELABORADO EN BASE A</td></tr>
                            <tr><td height='19px' align='center' style='border-bottom: 1px solid black; font-weight: bold;'>LEVANTAMIENTO TOPOGRAFICO</td></tr> 
                            <tr><td height='19px' align='center' style='border-bottom: 1px solid black; font-weight: bold;'>RED GEODESICA IGM SMPT-11</td></tr>                 
                            <tr><td height='19px' align='center' style='border-bottom: 1px solid black;'>&nbsp </td></tr> 
                            <tr><td height='19px' align='center' style='border-bottom: 1px solid black;'>&nbsp </td></tr> 
                            <tr><td height='19px' align='center' style='border-bottom: 1px solid black;'>&nbsp</td></tr>
                            <tr><td height='19px' align='center' style='border-bottom: 1px solid black;'>&nbsp</td></tr>  
                            <tr><td height='19px' align='center' style='border-bottom: 1px solid black;'>&nbsp</td></tr> 
                            <tr><td height='19px' align='center' style='border-bottom: 1px solid black;'>&nbsp</td></tr> 
                            <tr><td height='19px' align='center' style='border-bottom: 1px solid black;'>&nbsp</td></tr> 
                            <tr><td height='19px' align='center' style='border-bottom: 1px solid black;'>&nbsp</td></tr>   
                            <tr><td height='19px' align='center' style='border-bottom: 1px solid black;'>&nbsp</td></tr>         
                        </table>
                    </td>

                    <td align='center' align='center' style='border: 1px solid black; border-collapse: separate; '>
                        <iframe
                            frameborder='0'
                            name='mapserver'
                            src='http://$server/cgi-bin/mapserv.exe?map=c:/apache/siicat/mapa/siicat_planocatastral_ubicacion.map&program=http://$server/cgi-bin/mapserv.exe&layer=Predios&layer=Manzanos&layer=Calles&imgext=$mapfile_extent_reference&mapext=$mapfile_extent_reference&zoomdir=0&mode=map&imgsize=560+713&mapsize=560+713'
                            id='content'
                            height='300px'
                            width='240px'                           
                            align='middle'
                            valign='middle'
                            align='center'
                            scrolling='no'
                            noresize='no'
                            marginwidth='0'
                            marginheight='0'>
                        </iframe>
                    </td>	
        
                    <td valign='top'>
                        <table  border='1' width='100%' style='border: 1px solid black;  border-collapse: separate; border-spacing: 0; font-family: Times New Roman; font-size: 6pt'>
                            <tr>
                                <td width='8%' align='center'>ID</td>
                                <td width='20%' align='center'>ESTE</td>							 
                                <td width='21%' align='center'>NORTE </td>
                                <td width='8%' align='center'>ID </td>
                                <td width='20%' align='center'>ESTE </td>							 
                                <td width='21%' align='center'>NORTE </td>													 
                            </tr>
                            <tr height='90%'>
                                <td align='center' valign='top'>";
$max_filas = 10; // Max filas a m ostrar
$numeros = "V1";
$i = 1;
while (($i < $no_de_vertices) and ($i < $max_filas)) {
    $j = $i + 1;
    $numeros = $numeros . "<br />V$j";
    $i++;
}
$b = $no_de_vertices;
while (($b < $max_filas)) {
    $b = $b + 1;
    $numeros = $numeros . "<br />";
    $i++;
}
$content = $content . "
                                    $numeros

                                </td>
                                <td align='right' valign='top'>";
$coordenadas_x = "$point_x[0]";
$i = 1;
while (($i < $no_de_vertices) and ($i < $max_filas)) {
    $coordenadas_x = $coordenadas_x . "<br/>$point_x[$i]";
    $i++;
}
$content = $content . "
                                    $coordenadas_x											
                                </td>							 
                                <td align='right' valign='top'>";
$coordenadas_y = "$point_y[0]";
$i = 1;
while (($i < $no_de_vertices) and ($i < $max_filas)) {
    $coordenadas_y = $coordenadas_y . "<br />$point_y[$i]";
    $i++;
}
$content = $content . "
                                    $coordenadas_y	
                                </td>
                                <td align='center' valign='top'>";
$max_filas2 = $max_filas * 2;
if ($no_de_vertices > $max_filas) {
    $i = $max_filas + 1;
    $numeros2 = "P" . $i;
    while (($i < $no_de_vertices) and ($i < $max_filas2)) {
        $j = $i + 1;
        $numeros2 = $numeros2 . "<br />V$j";
        $i++;
    }
} else
    $numeros2 = "&nbsp";
$content = $content . "
                                    $numeros2													
                                </td>
                                <td align='right' valign='top'>";
if ($no_de_vertices > $max_filas) {
    $coordenadas_x2 = "&nbsp $point_x[$max_filas]";
    $i = $max_filas + 1;
    while (($i < $no_de_vertices) and ($i < $max_filas2)) {
        $coordenadas_x2 = $coordenadas_x2 . "<br />$point_x[$i]";
        $i++;
    }
} else
    $coordenadas_x2 = "&nbsp";
$content = $content . "
                                    $coordenadas_x2													
                                </td>
                                <td align='right' valign='top'>";
if ($no_de_vertices > $max_filas) {
    $coordenadas_y2 = "&nbsp $point_y[$max_filas]";
    $i = $max_filas + 1;
    while (($i < $no_de_vertices) and ($i < $max_filas2)) {
        $coordenadas_y2 = $coordenadas_y2 . "<br />$point_y[$i]";
        $i++;
    }
} else
    $coordenadas_y2 = "&nbsp";
$content = $content . "
                                    $coordenadas_y2	
                                </td>													 
                            </tr>
                        </table>	
                        <table width='100%' style='font-family: Times New Roman; font-size: 8pt; border-collapse: collapse; letter-spacing: 3px;'>
                            <tr ><td>&nbsp </td></tr>
                            <tr><td>&nbsp </td></tr>
                            <tr><td>&nbsp </td></tr>  
                            <tr><td>&nbsp </td></tr>  
                            <tr><td>&nbsp </td></tr>  
                            <tr><td>&nbsp </td></tr>              
                            <tr><td>&nbsp </td></tr> 
                            <tr><td>&nbsp </td></tr>       
                            <tr><td>&nbsp </td></tr>      
                            <tr><td>&nbsp </td></tr>             
                            <tr><td align='center' style='border-top: 1px solid black;'>FIRMA DEL RESPONSABLE</td></tr>
                            <tr><td align='center' > $fecha2</td></tr>
                            <tr><td align='center'>FECHA DE ELABORACION</td></tr>
                         </table>  
								 	
                        
                    </td>
                </tr>	
                

            </table>
        </td>
    </tr>
</table>            
</div>";
################################################################################
#------------------- CHEQUEAR SI SE PUEDE ABRIR EL ARCHIVO --------------------#
################################################################################	
if (!$handle = fopen($filename, 'w')) {
    $error = 2;
}
if (!fwrite($handle, $content)) {
    $error = 3;
}
fclose($handle);

?>