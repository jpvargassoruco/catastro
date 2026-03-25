<?php

########################################
#      Chequear si existen filas       #
########################################	
$sql="SELECT cod_uv FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check_predio = pg_num_rows(pg_query($sql));		

################################################################################
#------------------------ INFORMACION DE INFO_PREDIO --------------------------#
################################################################################	
 
# FUNCION GET_ZONA
$ben_zona = get_zona ($id_inmu);
if ($ben_zona == "0") {			
   $ben_zona = "-";
}
# FUNCION GET_BARRIO
$barrio = get_barrio ($id_inmu);	
if ($barrio == "0") {			
   $barrio = "-";
}		

include "siicat_planos_leer_datos.php";


################################################################################
#-------------------------- DEFINIR ZONA DEL PREDIO ---------------------------#
################################################################################
$zona = get_zona_brujula ($cod_cat, $centro_del_pueblo_para_zonas_x, $centro_del_pueblo_para_zonas_y);

if ($zona === "SE")  { 
	$zona = "SUR ESTE";
	} elseif ($zona === "SO") { $zona = "SUR OESTE";
	} elseif ($zona === "NE") { $zona = "NOR ESTE";
	} elseif ($zona === "NO") { $zona = "NOR OESTE"; 
	} elseif ($zona === "N")  { $zona = "NORTE"; 
	} elseif ($zona === "S")  { $zona = "SUR"; 
	} elseif ($zona === "E")  { $zona = "ESTE"; 
	} elseif ($zona === "O")  { $zona = "OESTE"; 
}
################################################################################
#------------------------- INFORMACION DE INFO_EDIF ---------------------------#
################################################################################	
$sql="SELECT edi_num, edi_piso, edi_tipo, edi_ano, edi_edo 
		FROM info_edif 
		WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' 
		ORDER BY edi_num, edi_piso";
		
$no_de_edificaciones = pg_num_rows(pg_query($sql));
$result=pg_query($sql);
$i = $j = 0;
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
   foreach ($line as $col_value) {
	    if ($i == 0) {
			   $edi_num[$j] = $col_value;
			} elseif ($i == 1) {
			   $edi_piso[$j] = $col_value;
			} elseif ($i == 2) {
			   $edi_tipo[$j] = utf8_decode(abr($col_value));
			} elseif ($i == 3) {
				$edi_ano[$j] = $col_value;
				$ant = $ano_actual - $edi_ano[$j];
				if (($ant >= 0) AND ($ant <= 5)) {
					$antiguedad[$j] = "0 - 5 años";
				} elseif (($ant >= 6) AND ($ant <= 10)) {
					$antiguedad[$j] = "6 - 10 años";
				} elseif (($ant >= 11) AND ($ant <= 15)) {
					$antiguedad[$j] = "11 - 15 años";
				} elseif (($ant >= 16) AND ($ant <= 20)) {
					$antiguedad[$j] = "16 - 20 años";
				} elseif (($ant >= 21) AND ($ant <= 25)) {
					$antiguedad[$j] = "21 - 25 años";
				} elseif (($ant >= 26) AND ($ant <= 30)) {
					$antiguedad[$j] = "26 - 30 años";
				} elseif (($ant >= 31) AND ($ant <= 35)) {
					$antiguedad[$j] = "31 - 35 años";
				} elseif (($ant >= 36) AND ($ant <= 40)) {
					$antiguedad[$j] = "36 - 40 años";
				} elseif (($ant >= 41) AND ($ant <= 45)) {
					$antiguedad[$j] = "41 - 45 años";
				} elseif (($ant >= 46) AND ($ant <= 50)) {
					$antiguedad[$j] = "46 - 50 años";
				} else {
					$antiguedad[$j] = "> 51 años";
				} 
			} else {
			   $edi_edo[$j] = utf8_decode(abr($col_value));
				 $i = -1;
				 $j++;
			}
			$i++;
   }
}
pg_free_result($result);
########################################
#------- CALCULAR AREA PREDIO ---------#
########################################
$sql="SELECT area(the_geom) FROM predios_ocha WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result=pg_query($sql);
$value = pg_fetch_array($result, null, PGSQL_ASSOC);	 			           
$area= ROUND($value['area'],2); 
$area = number_format($area, 2, '.', '');
pg_free_result($result); 
########################################
#----- CALCULAR AREA EDIFICACIONES ----#
########################################
$sql="SELECT edi_num,edi_piso FROM info_edif WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' ORDER BY edi_num, edi_piso";
$check = pg_num_rows(pg_query($sql));
if ($check == 0) {
	 $edi_area = 0;
} else {
   $edi_area = 0;
   $result=pg_query($sql);
   $i = $j = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
      foreach ($line as $col_value) {
			   if ($i == 0) {
				    $edi_num_temp = $col_value;
				 } else {
				    $edi_piso_temp = $col_value;
						$sql2="SELECT area(the_geom) FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND edi_num = '$edi_num_temp' AND edi_piso = '$edi_piso_temp'";
						$check2 = pg_num_rows(pg_query($sql2));
            if ($check2 == 0) {
	             $edi_area = $edi_area + 0;
							 $area_edif[$j] = 0;
						} else {
						   $result2=pg_query($sql2);
               $value = pg_fetch_array($result2, null, PGSQL_ASSOC);	 			           
               $area_edif[$j]= ROUND($value['area'],2); 
               pg_free_result($result2);
							 $edi_area = $edi_area + $area_edif[$j];
						}
						$i = -1;
				 }
				 $i++;
			}
			$j++;
	 } 
}

################################################################################
#------------------------ VALORACION DE EDIFICACIONES -------------------------#
################################################################################			
$gestion_actual = $ano_actual-1;
$sql="SELECT * FROM info_edif WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' ORDER BY edi_num, edi_piso ASC";
$no_de_edificaciones = pg_num_rows(pg_query($sql));
if ($no_de_edificaciones > 0) {
	$i = $k = 0;
	$result_edif = pg_query($sql);
	while ($line = pg_fetch_array($result_edif, null, PGSQL_ASSOC)) {	
		$line_value[$i] = $no_de_objetos_validos[$i] = 0;				 	 			           
		foreach ($line as $col_value) {
			$column_edif = get_column_edif ($k);
			$sql="SELECT valuacion FROM imp_valua_viv_materiales WHERE tipo = '$column_edif' AND material = '$col_value'";				 
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
			$k++; 
		} 
		$line_media[$i] = ROUND($line_value[$i]/$no_de_objetos_validos[$i],2); 
		if ($line_media[$i] < 1.5) {
			$clase[$i] = "Marginal";
		} elseif ($line_media[$i] < 2.5) {
			$clase[$i] = "Muy Económico";
		} elseif ($line_media[$i] < 3.5) {
			$clase[$i] = "Económico";
		} elseif ($line_media[$i] < 4.5) {
			$clase[$i] = "Bueno";
		} elseif ($line_media[$i] < 5.5) {
			$clase[$i] = "Muy Bueno";
		}	 else {
			$clase[$i] = "Lujoso";
		}							
		$k = 0;
		$i++;				
	} 
}
################################################################################
#------------------------------- COLINDANTES ----------------------------------#
################################################################################	
$id_predio = get_id_predio ($cod_geo,$cod_uv,$cod_man,$cod_pred);
$sql="SELECT * FROM colindantes WHERE id_predio = '$id_inmu'";
$check_col = pg_num_rows(pg_query($sql));
if ($check_col > 0 ) {	
	$result_col = pg_query($sql);
	$info_col = pg_fetch_array($result_col, null, PGSQL_ASSOC);
	
	$col_norte_nom = utf8_decode ($info_col['norte_nom']);
	if (strlen ($col_norte_nom) < 116) {
		$font_size_norte =  "8pt";
	} else $font_size_norte =  "6pt";
		$col_norte_med  = utf8_decode ($info_col['norte_med']);
		$font_size_norte_med =  "8pt";

	$col_noroes_nom = utf8_decode ($info_col['noroes_nom']);
	if (strlen ($col_noroes_nom) < 116) {
		$font_size_noroes =  "8pt";
	} else $font_size_noroes =  "6pt";
		$col_noroes_med  = utf8_decode ($info_col['noroes_med']);
		$font_size_noroes_med =  "8pt";

	$col_norest_nom = utf8_decode ($info_col['norest_nom']);
	if (strlen ($col_norest_nom) < 116) {
		$font_size_norest =  "8pt";
	} else $font_size_norest =  "6pt";
		$col_norest_med  = utf8_decode ($info_col['norest_med']);
		$font_size_norest_med =  "8pt";

		$col_surest_nom = utf8_decode ($info_col['surest_nom']);
	if (strlen ($col_surest_nom) < 116) {
		$font_size_surest = "8pt";
	} else $font_size_surest = "6pt";							
		$col_surest_med = utf8_decode ($info_col['surest_med']);	
		$font_size_surest_med =  "8pt";	

		$col_sur_nom = utf8_decode ($info_col['sur_nom']);
	if (strlen ($col_sur_nom) < 116) {
		$font_size_sur = "8pt";
	} else $font_size_sur = "6pt";							
		$col_sur_med  = utf8_decode ($info_col['sur_med']);
		$font_size_sur_med =  "8pt";			

		$col_suroes_nom = utf8_decode ($info_col['suroes_nom']);
	if (strlen ($col_suroes_nom) < 116) {
		$font_size_suroes = "8pt";
	} else $font_size_suroes = "6pt";							
		$col_suroes_med = utf8_decode ($info_col['suroes_med']);	
		$font_size_suroes_med =  "8pt";

		$col_este_nom = utf8_decode ($info_col['este_nom']);
	if (strlen ($col_este_nom) < 116) {
		$font_size_este =  "8pt";
	} else $font_size_este =  "6pt";									
		$col_este_med = utf8_decode ($info_col['este_med']);
		$font_size_este_med =  "8pt";				

		$col_oeste_nom = utf8_decode ($info_col['oeste_nom']);
	if (strlen ($col_oeste_nom) < 116) {
		$font_size_oeste =  "8pt";
	} else $font_size_oeste =  "6pt";			
		$col_oeste_med  = utf8_decode ($info_col['oeste_med']);
		$font_size_oeste_med =  "8pt";							
###############################################################
	pg_free_result($result_col);
} else { 
	$col_norte_nom = $col_sur_nom = $col_este_nom = $col_oeste_nom = "";	
	$col_norte_med = $col_sur_med = $col_este_med = $col_oeste_med = "";
	$font_size_norte = $font_size_sur = $font_size_este = $font_size_oeste = "8pt";
	$font_size_norte_med = $font_size_sur_med = $font_size_este_med = $font_size_oeste_med = "8pt";	 
}	
if ($col_norte_nom == "") {
	$limite1 = "NORESTE";
	$Colind1 = $col_norest_nom;
	$Medida1 = $col_norest_med;

} else { 
	$limite1 = "NORTE";
	$Colind1 = $col_norte_nom;
	$Medida1 = $col_norte_med;

}
if ($col_sur_nom == "") {
	$limite2 = "NOROESTE";
	$Colind2 = $col_noroes_nom;
	$Medida2 = $col_noroes_med;

} else { 
	$limite2 = "SUD";
	$Colind2 = $col_sur_nom;
	$Medida2 = $col_sur_med;

}

if ($col_este_nom == "") {
	$limite3 = "SUDESTE";
	$Colind3 = $col_surest_nom;
	$Medida3 = $col_surest_med;

} else { 
	$limite3 = "ESTE";
	$Colind3 = $col_este_nom;
	$Medida3 = $col_este_med;

}

if ($col_oeste_nom == "") {
	$limite4 = "SUDOESTE";
	$Colind4 = $col_suroes_nom;
	$Medida4 = $col_suroes_med;

} else { 
	$limite4 = "OESTE";
	$Colind4 = $col_oeste_nom;
	$Medida4 = $col_oeste_med;

}
################################################################################
#--------------------------------- GRAVAMEN -----------------------------------#
################################################################################
$sql="SELECT texto FROM gravamen WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";	 
$gravamen_check = pg_num_rows(pg_query($sql));   
if ($gravamen_check == 0) {
   $observ_fila = "&nbsp";

} else {
   $observ_fila = "EL PREDIO TIENE UN GRAVAMEN !"; 
}	 
################################################################################
#---------------------------------- NOTA --------------------------------------#
################################################################################
$sql="SELECT nota_tec FROM imp_base";
$result_nota = pg_query($sql);
$info = pg_fetch_array($result_nota, null, PGSQL_ASSOC);
$nota_informe_tecnico = utf8_decode ($info['nota_tec']);
pg_free_result($result_nota);	
################################################################################
#---------------------- SELECCIONAR GEOMETRIA PARA IFRAME ---------------------#
################################################################################	
      $sql="SELECT cod_uv FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
      $check_predio = pg_num_rows(pg_query($sql));
      if ($check_predio > 0) {
			   $geometria_existe = true;
         $result1=pg_query("SELECT (extent3d(the_geom)) FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
         $str_extent = pg_fetch_array($result1, null, PGSQL_ASSOC);
         $extent = $str_extent['extent3d'];
         #------------------------- Extraer coordenadas de EXTENT
         $xt_x = $xtent_x = array();
         $xt_y = $xtent_y = array();
         $x =0;
         $z = 0;
         $i =0;
         $j = 6; 
         while ($i <= strlen($extent)) {
            $char = substr($extent, $i, 1);
	          if (($char == ' ') AND ($z == 0)) {
               $xt_x[$x] = substr($extent,$j,$i-$j);
			         $xtent_x[$x] =ROUND($xt_x[$x],2);
			         $j=$i+1;
			         $z = 1;
#			echo "$point_x[$x]<br /> ";
	          } else if (($char == ' ') AND ($z == 1)) {
               $xt_y[$x] = substr($extent,$j,$i-$j);
			         $xtent_y[$x] =ROUND($xt_y[$x],2);		
		           $j=$i+3;			
			         $z = 0;
#			echo "$point_y[$x]<br /> ";
			         $x++;
	          } 
#  echo "$char $i $j<br />";
	          $i++;   
        }
        $centerx = ($xtent_x[0] + $xtent_x[1])/2;
        $centery = ($xtent_y[0] + $xtent_y[1])/2;
        # FACTOR ZOOM PARA PLANO DE UBICACION
        $ext_x = sqrt(($xtent_x[0] - $xtent_x[1])*($xtent_x[0] - $xtent_x[1]));
        $ext_y = sqrt(($xtent_y[0] - $xtent_y[1])*($xtent_y[0] - $xtent_y[1]));				 
		if (($ext_x > 110) OR ($ext_y > 110)) {
						$factor_zoom1 = 5;
         } elseif (($ext_x > 90) OR ($ext_y > 90)) {
						$factor_zoom1 = 6;
         } elseif (($ext_x > 70) OR ($ext_y > 70)) {
						$factor_zoom1 = 7;					
         } elseif (($ext_x > 50) OR ($ext_y > 50)) {		 		 
				   $factor_zoom1 = 8;
         } elseif (($ext_x > 30) OR ($ext_y > 30)) {		 		 
				   $factor_zoom1 = 10;					 
				 } else {
	          #$factor_zoom = 3.2;
						$factor_zoom1 = 12;
         }  				 
         if ($centerx-$xtent_x[0] > $centery-$xtent_y[0]) {
#            $delta = ($centerx-$xtent_x[0])* $factor_zoom;
						$delta1 = ($centerx-$xtent_x[0])* $factor_zoom1; 
#echo "DELTA viene de X y es $delta<br />\n";
         } else {
#            $delta = ($centery-$xtent_y[0])* $factor_zoom;
						$delta1 = ($centery-$xtent_y[0])* $factor_zoom1;

         }

         $xmin1=$centerx- $delta1;
         $xmax1=$centerx+ $delta1; 
         $ymin1=$centery- $delta1;
         $ymax1=$centery+ $delta1;				 	
				 pg_free_result($result1);
		 ########################################
         #   ENVIAR SELECCIONADO A TEMP-POLY    #
         ########################################		
		pg_query("DELETE FROM temp_poly WHERE user_id = '$user_id' OR user_id IS NULL");				 
		pg_query("INSERT INTO temp_poly (edi_num, edi_piso, the_geom) SELECT edi_num, edi_piso, the_geom FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
		pg_query("UPDATE temp_poly SET user_id = '$user_id', numero = 44 WHERE edi_num > '0' AND user_id IS NULL");				 
		pg_query("INSERT INTO temp_poly (cod_uv, cod_man, cod_pred, the_geom) SELECT cod_uv, cod_man, cod_pred, the_geom FROM predios_ocha WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
		pg_query("UPDATE temp_poly SET cod_cat = '$cod_cat', user_id = '$user_id', numero = 55 WHERE user_id IS NULL AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
      } else $geometria_existe = false;
################################################################################
#------------------------- GENERAR PLANO DE UBICACION -------------------------#
################################################################################	
if ($geometria_existe) {
   include "siicat_generar_mapfile_planocatastral_ubicacion.php";
}

################################################################################
#------------------------       CONTROL DE No C.E.       ----------------------#
################################################################################
$sql = "SELECT * FROM docu_control WHERE id_doc = 1";
$result = pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$nro_doc = $info['nro_doc'] + 1;
$nro_doc = str_pad($nro_doc, 6, "0", STR_PAD_LEFT);
$titulo5 = utf8_decode($info['titulo1']);
########################################
#-------   MANZANA ANTERIOR   ---------#
########################################
$sql="SELECT man_ant FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result=pg_query($sql);
if (!$result) {
    $cod_ant_man = $cod_man_c;
} else {
    $value = pg_fetch_array($result, null, PGSQL_ASSOC);	 			           
    $cod_ant_man= trim($value['man_ant']); 
    if ($cod_ant_man==''){
        $cod_ant_man = $cod_man_c;
    }
}
################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	
$cod_uvcero = str_pad($cod_uv, 2, "0", STR_PAD_LEFT);
$cod_macero = str_pad($cod_man, 4, "0", STR_PAD_LEFT);
$cod_prcero = str_pad($cod_pred, 3, "0", STR_PAD_LEFT);

if (empty($cod_pr_ddrr)) {
    $cod_pr_ddrr = $cod_pred;
}


$name_foto = get_codcat_foto($cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto);
$name_foto = "http://$server/$folder/fotos/".$name_foto.".jpg";

$filename = "C:/apache/htdocs/tmp/empadronamiento".$cod_cat.".html";

################################################################################
#------------------------ PREPARAR CONTENIDO PARA IFRAME ----------------------#
################################################################################	
$propietario = utf8_encode($propietario);
$direccion = utf8_encode($direccion);

$espacio_entre_tablas = 30;
$content = "
<div align='left'>
<table border='0' cellpadding='1px' width='100%' height='50' style='table-layout:fixed; border-collapse:collapse; font-family: Times New Roman; font-size: 9pt' bgcolor='#FFFFFF'><font face='Tahoma' size='2'>
	<tr>
		<td colspan='2'>
			<img src='http://$server/$folder/css/$nomlog' alt='imagen' width='115' height='101' border='0'>
		</td>
        <td colspan='8' align='center'>
			<font style='font-family: Tahoma; font-size: 11pt;'>
            GOBIERNO AUTONOMO MUNICIPAL DE $municipio UNIDAD DE ORDENAMIENTO TERRITORIAL Y CATASTRO URBANO
			</font>
        </td>
		<td colspan='2'>
			<img src='http://$server/$folder/css/bolivia.png' alt='imagen' width='115' height='101' border='0'>
		</td>		
    </tr>
	<tr>
        <td colspan='12' align='center'>
			<font style='font-family: Times New Roman; font-size: 18pt; font-weight:bold;'>FORMULARIO DE REGISTRO CATASTRAL</font>
        </td>
	</tr>
	<tr>
        <td colspan='2' align='left'>
			<font style='font-family: Times New Roman; font-size: 9pt;'>No INMUEBLE:</font>
        </td>
        <td colspan='2' align='center' bgcolor= '#ccd1d1' >
			<font style='font-family: Times New Roman; font-size: 10pt;'>$id_inmu</font>
        </td>	
        <td colspan='2' align='right'>
			<font style='font-family: Times New Roman; font-size: 9pt;'>PMC:</font>
        </td>		
        <td colspan='2' align='center' bgcolor= '#ccd1d1'>
			<font style='font-family: Times New Roman; font-size: 10pt;' >$tit_1id</font>
        </td>
        <td colspan='2' align='right'>
			<font style='font-family: Times New Roman; font-size: 9pt;'>Formulario No :</font>
        </td>
        <td colspan='2' align='left' >
			<font style='font-family: Times New Roman; font-size: 9pt;'>&nbsp $nro_doc/$ano_actual</font>
        </td>										
	</tr>
	<tr>
        <td colspan='8' align='center'>
			<font style='font-family: Times New Roman; font-size: 9pt; font-weight:bold;'>C O D I G O  &nbsp C A T A S T R A L</font>
        </td>
        <td colspan='2' align='right'>
			<font style='font-family: Times New Roman; font-size: 9pt;'>Fecha :</font>
        </td>
        <td colspan='2' align='left'>
			<font style='font-family: Times New Roman; font-size: 9pt;'>&nbsp $fecha2</font>
        </td>
	</tr>
	<tr>
		<td colspan='8'>
			<table border='1' cellpadding='1px' width='100%' style='table-layout:fixed; font-family: Times New Roman; font-size: 9pt' bgcolor='#FFFFFF'><font face='Tahoma' size='2'>
				<tr>
					<td colspan='1' align='center' bgcolor= '#ababab'> 
						<font style='font-family: Times New Roman; font-size: 14pt;'>$cod_dep</font>
					</td>
					<td colspan='1' align='center' bgcolor= '#ababab'  padding='1px'>
						<font style='font-family: Times New Roman; font-size: 14pt;'>$cod_mun</font>
					</td>
					<td colspan='1' align='center' bgcolor= '#ababab'>
						<font style='font-family: Times New Roman; font-size: 14pt;'>$cod_dis</font>
					</td>
					<td colspan='1' align='center' bgcolor= '#05cbfc '>
						<font style='font-family: Times New Roman; font-size: 14pt;'>$cod_uvcero</font>
					</td>		
					<td colspan='1' align='center' bgcolor= '#05cbfc'>
						<font style='font-family: Times New Roman; font-size: 14pt;'>$cod_macero</font>
					</td>		
					<td colspan='1' align='center' bgcolor= '#05cbfc'>
						<font style='font-family: Times New Roman; font-size: 14pt;' >$cod_prcero</font>
					</td>
					<td colspan='1' align='center' bgcolor= '#05cbfc'>
						<font style='font-family: Times New Roman; font-size: 14pt;'>000</font>
					</td>
					<td colspan='1' align='center' bgcolor= '#05cbfc'>
						<font style='font-family: Times New Roman; font-size: 14pt;'>000</font>
					</td>		

				<tr>	
			</table>		
		</td>
		<td colspan='2' align='right'>
			<font style='font-family: Times New Roman; font-size: 9pt;'>Codigo :</font>
		</td>
		<td colspan='2' align='left' >
			<font style='font-family: Times New Roman; font-size: 9pt;'>&nbsp &nbsp &nbsp</font>
		</td>								
	</tr>
	<tr>
        <td colspan='1' align='center'>
			<font style='font-family: Times New Roman; font-size: 6pt;'>DEPTO.</font>
        </td>
        <td colspan='1' align='center'>
			<font style='font-family: Times New Roman; font-size: 6pt;'>MUNICIPIO</font>
        </td>
        <td colspan='1' align='center'>
			<font style='font-family: Times New Roman; font-size: 6pt;'>C. URBANO</font>
        </td>
        <td colspan='1' align='center'>
			<font style='font-family: Times New Roman; font-size: 6pt;'>DIST. - CAT.</font>
        </td>		
        <td colspan='1' align='center'>
			<font style='font-family: Times New Roman; font-size: 6pt;'>MANZANO</font>
        </td>		
        <td colspan='1' align='center'>
			<font style='font-family: Times New Roman; font-size: 6pt;' >LOTE</font>
        </td>
        <td colspan='1' align='center'>
			<font style='font-family: Times New Roman; font-size: 6pt;'>P. H.</font>
        </td>
        <td colspan='1' align='center' >
			<font style='font-family: Times New Roman; font-size: 6pt;'>P. H.</font>
        </td>		
        <td colspan='2' align='right'>
			<font style='font-family: Times New Roman; font-size: 8pt;'>User :</font>
        </td>
        <td colspan='2' align='left' >
			<font style='font-family: Times New Roman; font-size: 8pt;'>$user_id</font>
        </td>											
	</tr>	
	    <td colspan='2' align='left' >
			<font style='font-family: Times New Roman; font-size: 6pt;'>&nbsp &nbsp &nbsp</font>
        </td>											
	<tr>
</table>	

<table width='100%' style='border-style: solid; border-top-width: 2px; border-right-width: 2px; border-bottom-width: 0px; border-left-width: 2px'>
	<tr height='25'>
		<td colspan='12' align='left' style='border-style: solid; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 1px; border-left-width: 0px'> 
			<font style='font-family: Times New Roman; font-size: 12pt; font-weight:bold;'>1 INFORMACION DEL PROPIETARIO:</font>
		</td>
	</tr>
	<tr>
		<td colspan='12'> &nbsp </td>
	</tr>	
	<tr>
		<td colspan='1' align='center'> 
			<font style='font-family: Times New Roman; font-size: 12pt;'>1)</font>
		</td>				
		<td colspan='9' align='left' bgcolor= '#e1e0e0'> 
			<font style='font-family: Times New Roman; font-size: 12pt;'>$prop1</font>
		</td>
		<td colspan='1' align='center'> 
			<font style='font-family: Times New Roman; font-size: 12pt;'>$con_tipo</font>
		</td>				
		<td colspan='1' align='center' bgcolor= '#e1e0e0'>
			<font style='font-family: Times New Roman; font-size: 12pt;'>$tit_1ci</font>
		</td>		
	<tr>
	<tr>
		<td colspan='1' align='center'> 
			<font style='font-family: Times New Roman; font-size: 12pt;'>2)</font>
		</td>						
		<td colspan='9' align='left' bgcolor= '#e1e0e0'> 
			<font style='font-family: Times New Roman; font-size: 12pt;'>$prop2</font>
		</td>
		<td colspan='1' align='center'> 
			<font style='font-family: Times New Roman; font-size: 12pt;'>C.I.</font>
		</td>						
		<td colspan='1' align='center' bgcolor= '#e1e0e0'>
			<font style='font-family: Times New Roman; font-size: 12pt;'>$tit_2ci</font>
		</td>		
	<tr>	

</table>

<table width='100%' style='border-style: solid; border-top-width: 0px; border-right-width: 2px; border-bottom-width: 0px; border-left-width: 2px'>
	<tr height='25'>
		<td colspan='12' align='left' style='border-style: solid; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 1px; border-left-width: 0px'>
			<font style='font-family: Times New Roman; font-size: 12pt; font-weight:bold;'>2. INFORMACION LEGAL (Antecedente Dominial):</font>
		</td>
	</tr>
	<tr>
		<td colspan='5' align='center' bgcolor= '#e1e0e0'> 
			<font style='font-family: Times New Roman; font-size: 11pt;'>&nbsp &nbsp</font>
		</td>				
		<td colspan='4' align='center' bgcolor= '#e1e0e0'> 
			<font style='font-family: Times New Roman; font-size: 11pt;'>$matriculaA</font>
		</td>
		<td colspan='2' align='center' bgcolor= '#e1e0e0'> 
			<font style='font-family: Times New Roman; font-size: 11pt;'>$dd_rr</font>
		</td>			
		<td colspan='1' align='center' bgcolor= '#e1e0e0'> 
			<font style='font-family: Times New Roman; font-size: 11pt;'>$area m2</font>
		</td>					
	<tr>
	<tr>
		<td colspan='5' align='center'> 
			<font style='font-family: Times New Roman; font-size: 12pt;'>Propietario anterior</font>
		</td>				
		<td colspan='4' align='center'> 
			<font style='font-family: Times New Roman; font-size: 12pt;'>Matricula</font>
		</td>
		<td colspan='2' align='center'> 
			<font style='font-family: Times New Roman; font-size: 12pt;'>Fecha DDRR</font>
		</td>
		<td colspan='1' align='center'> 
			<font style='font-family: Times New Roman; font-size: 12pt;'>Sup.Seg.Doc</font>
		</td>					
	<tr>	
</table>				
							
<table width='100%' style='border-style: solid; border-top-width: 0px; border-right-width: 2px; border-bottom-width: 0px; border-left-width: 2px'>
	<tr height='25'>
		<td colspan='12' align='left' style='border-style: solid; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 1px; border-left-width: 0px'>
			<font style='font-family: Times New Roman; font-size: 12pt; font-weight:bold;'>3. DATOS DE UBICACION DEL INMUEBLE:</font>
		</td>
	</tr>
	<tr>
		<td colspan='4' align='left'> 
			<font style='font-family: Times New Roman; font-size: 11pt;'>Departamento.:</font>
		</td>				
		<td colspan='4'  align='left'> 
			<font style='font-family: Times New Roman; font-size: 11pt;'>$depart</font>
		</td>					
		<td colspan='4' rowspan='7' align='center'>
			<img border='0' src='$name_foto'height='150' width='100%' >
		</td>			
	</tr>
	<tr>
		<td colspan='4' align='left'><font style='font-family: Times New Roman; font-size: 12pt;'>Provincia</font></td>				
		<td colspan='4' align='left'><font style='font-family: Times New Roman; font-size: 12pt;'>$provincia</font></td>					
	</tr>	
	<tr>
		<td colspan='4' align='left'><font style='font-family: Times New Roman; font-size: 12pt;'>Municipio:</font></td>				
		<td colspan='4' align='left'><font style='font-family: Times New Roman; font-size: 12pt;'>$municipio</font></td>		
	</tr>
	<tr>
		<td colspan='4' align='left'><font style='font-family: Times New Roman; font-size: 12pt;'>Urbanizacion:</font></td>				
		<td colspan='4' align='left'><font style='font-family: Times New Roman; font-size: 12pt;'>$dir_zonurb</font></td>		
	</tr>	
	<tr>
		<td colspan='4' align='left'><font style='font-family: Times New Roman; font-size: 12pt;'>Dist. Catastral:</font></td>				
		<td colspan='4' align='left'><font style='font-family: Times New Roman; font-size: 12pt;'>$cod_uvcero</font></td>				
	</tr>	
	<tr>
		<td colspan='4' align='left'><font style='font-family: Times New Roman; font-size: 12pt;'>Manzana:</font></td>				
		<td colspan='4' align='left'><font style='font-family: Times New Roman; font-size: 12pt;'>$cod_ant_man</font></td>				
	</tr>
	<tr>
		<td colspan='4' align='left'><font style='font-family: Times New Roman; font-size: 12pt;'>Lote:</font></td>				
		<td colspan='4' align='left'><font style='font-family: Times New Roman; font-size: 12pt;'>$cod_pr_ddrr</font></td>				
	</tr>					
</table>

<table width='100%' style='border-style: solid; border-top-width: 0px; border-right-width: 2px; border-bottom-width: 0px; border-left-width: 2px'>
	<tr height='25'>
		<td colspan='12' align='left' style='border-style: solid; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 1px; border-left-width: 0px'>
			<font style='font-family: Times New Roman; font-size: 12pt; font-weight:bold;'>4. DESCRIPCCION DEL PREDIO</font>
		</td>
	</tr>
	<tr>
		<td colspan='1'  align='left' bgcolor= '#bfbfbf'><font style='font-family: Times New Roman; font-size: 10pt;'>Calle:</font></td>				
		<td colspan='11' align='left' bgcolor= '#d8d8d8'><font style='font-family: Times New Roman; font-size: 10pt;'>$direccion BARRIO: $barrio</font></td>								
	</tr>
	<tr>
		<td colspan='3' align='left' bgcolor= '#bfbfbf'><font style='font-family: Times New Roman; font-size: 10pt;'>Sup. de Lote:</font></td>				
		<td colspan='3' align='left' bgcolor= '#d8d8d8'><font style='font-family: Times New Roman; font-size: 10pt;'>$area m²</font></td>	
		<td colspan='3' align='left' bgcolor= '#bfbfbf'><font style='font-family: Times New Roman; font-size: 10pt;'>Sup. Con. total:</font></td>				
		<td colspan='3' align='left' bgcolor= '#d8d8d8'><font style='font-family: Times New Roman; font-size: 10pt;'>$edi_area m²</font></td>						
	</tr>	
	<tr>
		<td colspan='2' align='left' bgcolor= '#bfbfbf'><font style='font-family: Times New Roman; font-size: 9pt;'>Zona Tributaria:</font></td>				
		<td colspan='2' align='left' bgcolor= '#d8d8d8'><font style='font-family: Times New Roman; font-size: 9pt;'>$ben_zona</font></td>
		<td colspan='2' align='left' bgcolor= '#bfbfbf'><font style='font-family: Times New Roman; font-size: 9pt;'>Coef. Topografico:</font></td>				
		<td colspan='2' align='left' bgcolor= '#d8d8d8'><font style='font-family: Times New Roman; font-size: 9pt;'>$ter_topo</font></td>
		<td colspan='2' align='left' bgcolor= '#bfbfbf'><font style='font-family: Times New Roman; font-size: 9pt;'>Coef. Ubicacion:</font></td>				
		<td colspan='2' align='left' bgcolor= '#d8d8d8'><font style='font-family: Times New Roman; font-size: 9pt;'>$ter_ubi</font></td>						
	</tr>
	<tr>
		<td colspan='2' align='left' bgcolor= '#bfbfbf'><font style='font-family: Times New Roman; font-size: 9pt;'>Material de Vía:</font></td>				
		<td colspan='2' align='left' bgcolor= '#d8d8d8'><font style='font-family: Times New Roman; font-size: 9pt;'>$via_mat</font></td>
		<td colspan='2' align='left' bgcolor= '#bfbfbf'><font style='font-family: Times New Roman; font-size: 9t;'>Coef. Forma:</font></td>				
		<td colspan='2' align='left' bgcolor= '#d8d8d8'><font style='font-family: Times New Roman; font-size: 9pt;'>$ter_form</font></td>
		<td colspan='2' align='left' bgcolor= '#bfbfbf'><font style='font-family: Times New Roman; font-size: 9pt;'>Coef. Frente/Fondo:</font></td>				
		<td colspan='2' align='left' bgcolor= '#d8d8d8'><font style='font-family: Times New Roman; font-size: 9pt;'>1</font></td>			
	</tr>	
	<tr>
		<td colspan='12' align='left'><font style='font-family: Times New Roman; font-size: 12pt;'></td>							
	</tr>			
</table>

<table border='1' width='100%' style='border:1px solid black;  border-collapse:collapse; border-top-width: 1px; border-right-width: 2px; border-bottom-width: 0px; border-left-width: 2px'>
	<tr height='25'>
		<td colspan='12' align='left' style='border-style: solid; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 1px; border-left-width: 0px'>
			<font style='font-family: Times New Roman; font-size: 12pt; font-weight:bold;'>5. COLINDANCIAS</font>
		</td>
	</tr>
	<tr height='25' style='font-family: Times New Roman; font-size: 11pt;'>
		<td colspan='1'><b>&nbsp $limite1</b></td>
		<td colspan='5'>&nbsp$Colind1</td>						 
		<td colspan='1'><b>&nbsp $limite2</b></td>							 
		<td colspan='5'>&nbsp$Colind2</td>	
	</tr> 

	<tr height='25' style='font-family: Times New Roman; font-size: 11pt;'>
	    <td colspan='1'><b>&nbsp $limite3</b></td>
		<td colspan='5'>&nbsp $Colind3</td>						 
		<td colspan='1'><b>&nbsp $limite4</b></td>							 
		<td colspan='5'>&nbsp $Colind4</td>	
	</tr> 	 
</table>


<table border='1' width='100%' height='161' style='border:1px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt'>
	
	<tr height='25'>
		<td colspan='8'><font style='font-family: Times New Roman; font-size: 12pt; font-weight:bold;'>6. CARACTERISTICAS DE LAS CONSTRUCCIONES</font></td>					 				
	</tr>																	
	<tr height='10' style='font-family: Tahoma; font-size: 8pt; font-weight: bold'>
		<td align='center'>No. Blo.</td>
		<td align='center'>Piso</td>
		<td align='center'>Tipo de Const.</td>
		<td align='center'>Ano Constr.</td>							 
		<td align='center'>Estado Visual</td>	
		<td align='center'>Valuacion</td>							 
		<td align='center'>Clase</td>	
		<td align='center'>Superf. Mens.</td>				 							 						 
	</tr>";
$i = 0;
if ($no_de_edificaciones > 8) {
   $fila_activa = true;
   $no_de_edif_real = $no_de_edificaciones;
   $no_de_edificaciones = 8;
} else $fila_activa = false;
$filas_vacias = 8 - $no_de_edificaciones;
while ($i < $no_de_edificaciones) {
	$content = $content."						
	<tr height='20'>
		<td align='center'>$edi_num[$i]</td>
		<td align='center'>$edi_piso[$i]</td>
		<td align='center'>$edi_tipo[$i]</td>
		<td align='center'>$edi_ano[$i]</td>							 
		<td align='center'>$edi_edo[$i]</td>	
		<td align='center'>$line_media[$i]</td>							 
		<td align='center'>$clase[$i]</td>	
	<td align='center'>$area_edif[$i] m²</td>					 							 						 
	</tr>";	
	$i++;
} 
$i = 0;
while ($i < $filas_vacias) {
	$content = $content."						
	<tr height='20'>
		<td align='center'>---</td>
		<td align='center'>---</td>
		<td align='center'>---</td>
		<td align='center'>---</td>							 
		<td align='center'>---</td>	
		<td align='center'>---</td>							 
		<td align='center'>---</td>	
		<td align='center'>---</td>					 							 						 
	</tr>";	
   $i++;
}
$content = $content."
	<tr height='20'>
		<td align='left' colspan='6'>";
		if ($fila_activa) {
			$texto = "&nbsp --> El predio tiene en total $no_de_edif_real edificaciones.";
		} else {
			$texto = "&nbsp";
		}
		$content = $content."$texto
		</td>
		<td align='center'><b>TOTAL</b></td>
		<td align='center'><b>$edi_area</b></td>		 							 						 
	</tr>		
</table>


<table width='100%' height='95' style='border:1px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt'>
	<tr  valign='top'>
		<td width='30%'>
			<table width='100%' height='95'>
				<tr>
					<td colspan='3'><font style='font-family: Times New Roman; font-size: 12pt; font-weight:bold;'>7. SERVICIOS BASICOS:</font></td>
				</tr>
				<tr style='font-family: Tahoma; font-size: 8pt;'>							
					<td colspan='3'>&nbsp</td> 				 			 							 						 
				</tr> 				 	 	 	  
				<tr style='font-family: Tahoma; font-size: 8pt;'>					
					<td>&nbsp Agua Potable</td>							 
					<td align='center' bgcolor= '#d8d8d8'>$ser_agu</td>		
					<td></td> 					 			 							 						 
				</tr> 
				<tr style='font-family: Tahoma; font-size: 8pt;'>
					<td>&nbsp Alcantarillado</td>
					<td align='center' bgcolor= '#d8d8d8'>$ser_alc</td>
					<td>&nbsp</td>						 						 						 							 						 
				</tr>	   
				<tr style='font-family: Tahoma; font-size: 8pt;'>
					<td>&nbsp Energia Electrica</td>
					<td align='center' bgcolor= '#d8d8d8'>$ser_luz</td>
					<td>&nbsp</td>							 						 						 							 						 
				</tr>
				<tr style='font-family: Tahoma; font-size: 8pt;'>
					<td>&nbsp Telefono</td>
					<td align='center' bgcolor= '#d8d8d8'>$ser_tel</td>
					<td>&nbsp</td>							 						 						 							 						 
				</tr>					
				<tr style='font-family: Tahoma; font-size: 8pt;'>
					<td>&nbsp Alumbrado Publico</td>
					<td align='center' bgcolor= '#d8d8d8'>$ser_alu_pub</td>
					<td>&nbsp</td>							 						 						 							 						 
				</tr>
				<tr style='font-family: Tahoma; font-size: 8pt;'>
					<td>&nbsp Gas Domiciliario</td>
					<td align='center' bgcolor= '#d8d8d8'>$ser_gas</td>
					<td>&nbsp</td>							 						 						 							 						 
				</tr>	
			</table>			 	 	 	  
		</td>
	

		<td width='70%'>
			<table border='0' bgcolor= '#d8d8d8' width='100%' height='95' style='border:1px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt'>
				<tr>
					<td colspan='3'  padding-top='3'>
						<p  >
						En mi calidad de sujeto pasivo y/o tercero responsable, declaro que la informacion proporcionada en la determinacion del I.M.P.B.I. 
						son veraces por lo que juro a la exactitud mediante la presente declaracion, además el Gobierno Autónomo Municipal de $municipio 
						en el ejercicio de sus funciones y atribuciones podra realizar inspecciones y fiscalizaciones segun corresponda.
						</p>	
					</td>
				</tr>
				<tr height='50'>
					<td colspan='3'>&nbsp</td>
				</tr>						
				<tr>
					<td colspan='1' align='center'>----------------------------------------------</td>
					<td colspan='1' align='center'>&nbsp</td>
					<td colspan='1' align='center'>----------------------------------------------</td>
				</tr>
				<tr>
					<td colspan='1' align='center'>Representante Legal</td>
					<td colspan='1' align='center'>&nbsp</td>
					<td colspan='1' align='center'>Firma Profesional</td>
				</tr>										
				<tr>
					<td colspan='3' align='center'>$fecha2 - $hora <a href='javascript:print(this.document)'>
					<img border='0' src='http://$server/$folder/graphics/printer.png' width='15' height='15' title='Imprimir en hoja tamano Oficio'></a>
					</td> 
				</tr>   
			</table>
		</td>		
		
	</tr>
</table>



</div>
</body>";
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