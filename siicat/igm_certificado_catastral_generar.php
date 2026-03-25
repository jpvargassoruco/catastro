<?php

#      Verificamos si existen filas 
$sql="SELECT cod_uv FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check_predio = pg_num_rows(pg_query($sql));		
#      Informaicon del predio  


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
### LEER DATOS DE PROPIETARIO DE INFO_INMU Y DATOS DEL PREDIO DE INFO_PREDIO

include "igm_planos_leer_datos.php";

/*
$sql="SELECT * FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result=pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$cod_pad =  $info['cod_pad'];
if ($cod_pad == "") { $cod_pad = "---"; }
$tit_1nom1 = utf8_decode(trim($info['tit_1nom1']));
$tit_1nom2 = utf8_decode(trim($info['tit_1nom2']));
$tit_1pat = utf8_decode(trim($info['tit_1pat']));
$tit_1mat = utf8_decode(trim($info['tit_1mat']));
$propietario1 = $tit_1nom1." ".$tit_1nom2." ".$tit_1pat." ".$tit_1mat;
$propietario1 = trim ($propietario1);
$tit_2nom1 = utf8_decode(trim($info['tit_2nom1']));
$tit_2nom2 = utf8_decode(trim($info['tit_2nom2']));
$tit_2pat = utf8_decode(trim($info['tit_2pat']));
$tit_2mat = utf8_decode(trim($info['tit_2mat']));
if ($tit_2pat != "") {
   $propietario2 = " Y ";
	 if ($tit_2nom1 != "") {
      $propietario2 = $propietario2." ".$tit_2nom1;
	 } 
	 if ($tit_2nom2 != "") {
      $propietario2 = $propietario2." ".$tit_2nom2;					
   }	
	 $propietario2 = $propietario2." ".$tit_2pat; 
	 if ($tit_2mat != "") {
      $propietario2 = $propietario2." ".$tit_2mat;
	 }
}
$tit_1ci = trim ($info['tit_1ci']);
if ($tit_1ci == "") { 
   $tit_1ci = "";
} else {
   $tit_1ci = "(C.I.: $tit_1ci)";
}
$tit_2ci = trim ($info['tit_2ci']);
if ($tit_2ci == "") { 
   $tit_2ci = "";
} else {
   $tit_2ci = "(C.I.: $tit_2ci)";
}
$prop_string = $propietario1." ".$tit_1ci." ".$propietario2." ".$tit_2ci;
$max_prop_stringlength1 = 104;
$max_prop_stringlength2 = 95;
if (strlen ($prop_string) > $max_prop_stringlength1) {
   $font_size_prop = "7pt";
} elseif (strlen ($prop_string) > $max_prop_stringlength2) {
   $font_size_prop = "8pt";
} else {
   $font_size_prop = "9pt";
}
$tit_cara = utf8_decode(abr($info['tit_cara']));
$der_num = utf8_decode(trim($info['der_num']));
if ($der_num == "") { $der_num = "---"; }
$der_fech = change_date($info['der_fech']);
if (($der_fech == "") OR ($der_fech == "01/01/1900")) { $der_fech = "---"; }
$adq_modo = utf8_decode(abr($info['adq_modo']));
if ($adq_modo == "") { $adq_modo = "-"; }
$adq_doc = utf8_decode($info['adq_doc']);
$max_ancho_column = 52;
if ($adq_doc == "") { 
   $adq_doc2 = "-";
	 $adq_doc1 = $adq_doc3 = "&nbsp";
} else {
   $i = $j = $max_ancho_column;
   if (strlen($adq_doc) > $i) {
      while (substr($adq_doc,$i,1) != " ") {
         $i--;
			   if ($i == 0) {
			      $adq_doc	= " ";
			   }
			}
	    $adq_doc1 = substr($adq_doc,0,$i);
			$adq_doc = substr($adq_doc,$i+1,strlen($adq_doc)-$i);
#echo "NUEVO STRING: $adq_doc<br>";
      $i = $j = $max_ancho_column;
      if (strlen($adq_doc) > $i) {
         while (substr($adq_doc,$i,1) != " ") {
            $i--;
			      if ($i == 0) {
			         $adq_doc	= " ";
			      }
			   }
	       $adq_doc2 = substr($adq_doc,0,$i);
			   $adq_doc = substr($adq_doc,$i+1,strlen($adq_doc)-$i);	
#echo "NUEVO STRING: $adq_doc<br>";
         $i = $j = $max_ancho_column;
         if (strlen($adq_doc) > $i) {
				    $adq_doc3 = substr($adq_doc,0,$i).".";
				 } else {			 
            $adq_doc3 = "$adq_doc";
				 }
	    } else {
	       $adq_doc2 = $adq_doc;
	       $adq_doc3 = "&nbsp";		
			}	 
	 } else {
      $adq_doc1 = "&nbsp";
	    $adq_doc2 = $adq_doc;
	    $adq_doc3 = "&nbsp";
   }
}
$adq_fech = change_date($info['adq_fech']);
if (($adq_fech == "") OR ($adq_fech == "01/01/1900")) { $adq_fech = "-"; }
$dir_tipo = $info['dir_tipo'];
if ($dir_tipo == "A") {
   $dir_tipo = "AV.";
} elseif ($dir_tipo == "C") {
   $dir_tipo = "C/";
} elseif ($dir_tipo == "P") {
   $dir_tipo = "P/";
}
$dir_nom = strtoupper(utf8_decode($info['dir_nom']));
$dir_num = $info['dir_num'];
$dir_edif = $info['dir_edif'];
$dir_bloq = $info['dir_bloq'];
$dir_piso = $info['dir_piso'];
$dir_apto = $info['dir_apto'];
$direccion = $dir_tipo." ".$dir_nom." ".$dir_num;
if ($dir_edif != "") {
   $direccion = $direccion.", EDIF. ".$dir_edif;
}
if ($dir_bloq != "") {
   $direccion = $direccion.", BLQ. ".$dir_bloq;
}
if ($dir_piso != "") {
   $direccion = $direccion.", PISO ".$dir_piso;
}
if ($dir_apto != "") {
   $direccion = $direccion.", APTO. ".$dir_apto;
}
if (strlen($direccion) > 44) {
   $direccion = substr($direccion,0,44).".";
}
$via_mat_act = $info['via_mat'];
$via_mat = STRTOUPPER (abr($info['via_mat']));
$ser_alc = $ser_alc_act = $info['ser_alc'];
$ser_agu = $ser_agu_act = $info['ser_agu'];
$ser_luz = $ser_luz_act = $info['ser_luz'];
$ser_tel = $ser_tel_act = $info['ser_tel'];
$ter_topo = $ter_topo_act = $info['ter_topo'];
$ter_topo = STRTOUPPER (abr($ter_topo));
$ter_sdoc = $info['ter_sdoc']; 
if ($ter_sdoc == "") { $ter_sdoc = "---"; }
$res_fech = change_date ($info['res_fech']);
if (($res_fech == "") OR ($res_fech == "01/01/1900")) { $res_fech = "---"; }
$res_obs = utf8_decode($info['res_obs']);
$i = $j = $max_ancho_column = 140;
if (strlen($res_obs) > $i) {
   while (substr($res_obs,$i,1) != " ") {
      $i--;
			if ($i == 0) {
			   $res_obs	= " ";
			} 
	 }
	 $observ_fila1 = substr($res_obs,0,$i);
	 $res_obs = substr($res_obs,$i+1,strlen($res_obs)-$i);
#echo "NUEVO STRING: $res_obs<br>";
      $i = $j = $max_ancho_column;
      if (strlen($res_obs) > $i) {
         while (substr($res_obs,$i,1) != " ") {
            $i--;
			      if ($i == 0) {
			         $res_obs	= " ";
			      }
			   }
	       $observ_fila2 = substr($res_obs,0,$i);
			   $res_obs = substr($res_obs,$i+1,strlen($res_obs)-$i);	
#echo "NUEVO STRING: $res_obs<br>";
         $i = $j = $max_ancho_column;
         if (strlen($res_obs) > $i) {
				    $observ_fila3 = substr($res_obs,0,$i).".";
				 } else {			 
            $observ_fila3 = "$res_obs";
				 }
	    } else {
	       $observ_fila2 = $res_obs;
	       $observ_fila3 = "&nbsp";		
			}	      
} else {
   $observ_fila1 = $res_obs;
   $observ_fila2 = "";
	 $observ_fila3 = "";
}
pg_free_result($result); 
*/
################################################################################
#-------------------------- DEFINIR ZONA DEL PREDIO ---------------------------#
################################################################################
$zona = get_zona_brujula ($cod_cat, $centro_del_pueblo_para_zonas_x, $centro_del_pueblo_para_zonas_y);
if ($zona == "SE")  { $zona = "SUR ESTE";
} elseif ($zona == "SO")  { $zona = "SUR OESTE";
} elseif ($zona == "NE")  { $zona = "NOR ESTE";
} elseif ($zona == "NO")  { $zona = "NOR OESTE"; 
}
################################################################################
#------------------------------- COLINDANTES ----------------------------------#
################################################################################	
$id_predio = get_id_predio ($cod_geo,$cod_uv,$cod_man,$cod_pred);
$sql="SELECT * FROM colindantes WHERE id_predio = '$id_predio'";
$check_col = pg_num_rows(pg_query($sql));
if ($check_col > 0 ) {	
      $result_col = pg_query($sql);
      $info_col = pg_fetch_array($result_col, null, PGSQL_ASSOC);
			$col_norte_nom = utf8_decode ($info_col['norte_nom']);
			if (strlen ($col_norte_nom) < 28) {
			   $font_size_norte =  "8pt";
			} elseif (strlen ($col_norte_nom) < 70) {
			   $font_size_norte =  "6pt";
			} elseif (strlen ($col_norte_nom) < 100) {
			   $font_size_norte =  "4pt";
			} else {
			   $col_norte_nom = substr($col_norte_nom,0,100);
			   $font_size_norte =  "4pt";
			}	
			$col_norte_med = utf8_decode ($info_col['norte_med']);
			if (strlen ($col_norte_med) < 16) {
			   $font_size_norte_med =  "8pt";
			} elseif (strlen ($col_norte_med) < 43) {
			   $font_size_norte_med =  "6pt";
			} elseif (strlen ($col_norte_med) < 60) {
			   $font_size_norte_med =  "4pt";
			} else {
			   $col_norte_med = substr($col_norte_med,0,60);
			   $font_size_norte_med =  "4pt";
			}					
			$col_sur_nom = utf8_decode ($info_col['sur_nom']);
			if (strlen ($col_sur_nom) < 28) {
			   $font_size_sur =  "8pt";
			} elseif (strlen ($col_sur_nom) < 70) {
			   $font_size_sur =  "6pt";
			} elseif (strlen ($col_sur_nom) < 120) {
			   $font_size_sur =  "4pt";
			} else {
			   $col_sur_nom = substr($col_sur_nom,0,120);
			   $font_size_sur =  "4pt";
			}					
			$col_sur_med = utf8_decode ($info_col['sur_med']);	
			if (strlen ($col_sur_med) < 16) {
			   $font_size_sur_med =  "8pt";
			} elseif (strlen ($col_sur_med) < 43) {
			   $font_size_sur_med =  "6pt";
			} elseif (strlen ($col_sur_med) < 60) {
			   $font_size_sur_med =  "4pt";
			} else {
			   $col_sur_med = substr($col_sur_med,0,60);
			   $font_size_sur_med =  "4pt";
			}				
			$col_este_nom = utf8_decode ($info_col['este_nom']);
			if (strlen ($col_este_nom) < 28) {
			   $font_size_este =  "8pt";
			} elseif (strlen ($col_este_nom) < 70) {
			   $font_size_este =  "6pt";
			} elseif (strlen ($col_este_nom) < 100) {
			   $font_size_este =  "4pt";
			} else {
			   $col_este_nom = substr($col_este_nom,0,100);
			   $font_size_este =  "4pt";
			}								
			$col_este_med = utf8_decode ($info_col['este_med']);
			if (strlen ($col_este_med) < 16) {
			   $font_size_este_med =  "8pt";
			} elseif (strlen ($col_este_med) < 43) {
			   $font_size_este_med =  "6pt";
			} elseif (strlen ($col_este_med) < 60) {
			   $font_size_este_med =  "4pt";
			} else {
			   $col_este_med = substr($col_este_med,0,60);
			   $font_size_este_med =  "4pt";
			}					
			$col_oeste_nom = utf8_decode ($info_col['oeste_nom']);
			if (strlen ($col_oeste_nom) < 28) {
			   $font_size_oeste =  "8pt";
			} elseif (strlen ($col_oeste_nom) < 70) {
			   $font_size_oeste =  "6pt";
			} elseif (strlen ($col_oeste_nom) < 100) {
			   $font_size_oeste =  "4pt";
			} else {
			   $col_oeste_nom = substr($col_oeste_nom,0,100);
			   $font_size_oeste =  "4pt";
			}							
			$col_oeste_med = utf8_decode ($info_col['oeste_med']);
			if (strlen ($col_oeste_med) < 16) {
			   $font_size_oeste_med =  "8pt";
			} elseif (strlen ($col_oeste_med) < 43) {
			   $font_size_oeste_med =  "6pt";
			} elseif (strlen ($col_oeste_med) < 60) {
			   $font_size_oeste_med =  "4pt";
			} else {
			   $col_oeste_med = substr($col_oeste_med,0,60);
			   $font_size_oeste_med =  "4pt";
			}									
			pg_free_result($result_col);
} else { 
   $col_norte_nom = $col_sur_nom = $col_este_nom = $col_oeste_nom = "---";	
   $col_norte_med = $col_sur_med = $col_este_med = $col_oeste_med = "---";
	 $font_size_norte = $font_size_sur = $font_size_este = $font_size_oeste = "8pt";
	 $font_size_norte_med = $font_size_sur_med = $font_size_este_med = $font_size_oeste_med = "8pt";	 
}		 
################################################################################
#------------------ INFORMACION Y AREA DE EDIFICACIONES -----------------------#
################################################################################	
$edi_area = 0;
$sql="SELECT edi_num, edi_piso, edi_tipo, edi_ano, edi_edo FROM info_edif WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' ORDER BY edi_num, edi_piso";
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
			} else {
			   $edi_edo[$j] = utf8_decode(abr($col_value));
				 $i = -1;			 
			}
			$i++;
   }
	 ########################################
   #----- CALCULAR AREA EDIFICACIONES ----#
   ########################################
	 $sql="SELECT area(the_geom) FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND edi_num = '$edi_num[$j]' AND edi_piso = '$edi_piso[$j]'";
   $check = pg_num_rows(pg_query($sql));
   if ($check == 0) {
	    $edi_area = $edi_area + 0;
		  $area_edif[$j] = 0;
   } else {
      $result_edif=pg_query($sql);
      $value_edif = pg_fetch_array($result_edif, null, PGSQL_ASSOC);		
			$edi_area = $edi_area + $value_edif['area'];	 			           
      $area_edif[$j]= ROUND($value_edif['area'],2);
			pg_free_result($result_edif); 	 	
	 }
	 $j++;	 
}
$edi_area = ROUND($edi_area,2);
pg_free_result($result);
########################################
#------- CALCULAR AREA PREDIO ---------#
########################################
$sql="SELECT area(the_geom) FROM predios_ocha WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result=pg_query($sql);
$value = pg_fetch_array($result, null, PGSQL_ASSOC);	 			           
$area= ROUND($value['area'],2); 
pg_free_result($result);
################################################################################
#----------------------------- TRADICION DE INMUEBLE --------------------------#
################################################################################	
$sql="SELECT tan_fech_ini, tan_fech_fin, tan_cara, tan_1id, tan_modo, tan_doc
      FROM transfer WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' ORDER BY tan_fech_ini ASC";		
$no_de_registros = pg_num_rows(pg_query($sql));
if ($no_de_registros > 0 ) {	
   $result = pg_query($sql);
	 $i = $j = 0; 
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {	
	       if ($i == 0) {
				    $tan_fech_ini[$j] = $fecha_trad_ini[$j] = change_date ($col_value);
	       } elseif ($i == 1) {
				    $tan_fech_fin[$j] = $fecha_trad_fin[$j] = change_date ($col_value);																	 			 
	       } elseif ($i == 2) {
				    $tit_cara_ant[$j] = $cara_trad[$j] = STRTOUPPER (abr($col_value));
				 } elseif ($i == 3) {
				    $titular1_ant[$j] = $tit1_trad[$j] = get_contrib_nombre($col_value);
						$tit_1ci_ant[$j] = $ci_trad[$j] = get_contrib_ci ($col_value);
				 } elseif ($i == 4) {
				    $tan_modo[$j] = utf8_decode($col_value);																								
				 } else {
            $tan_doc[$j] = utf8_decode($col_value);	
				    $i = -1;
				 }
         $i++;
	    }
			$j++;			 
   }
	 pg_free_result($result);
}

if ($no_de_registros < 3) {
   $fecha_trad_ini[2] = $fecha_trad_fin[2] = $tit1_trad[2] = $ci_trad[2] = $cara_trad[2] = "-";
	 if ($no_de_registros < 2) {
      $fecha_trad_ini[1] = $fecha_trad_fin[1] = $tit1_trad[1] = $ci_trad[1] = $cara_trad[1] = "-";
	    if ($no_de_registros < 1) {
         $fecha_trad_ini[0] = $fecha_trad_fin[0] = $tit1_trad[0] = $ci_trad[0] = $cara_trad[0] = "-";
			}
	 }
}
################################################################################
#------------------------------- AVALUO CATASTRAL -----------------------------#
################################################################################	
$sql="SELECT gestion,valor_t,valor_vi,avaluo_total FROM imp_pagados WHERE id_inmu = '$id_inmu' ORDER BY gestion DESC LIMIT 1";
$result=pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$avaluo_terreno = $info['valor_t'];
$avaluo_const = $info['valor_vi'];
$avaluo_total = $info['avaluo_total'];
pg_free_result($result);
################################################################################
#------------------------------ ULTIMO PAGO TRIBUT ----------------------------#
################################################################################	
$sql="SELECT gestion,avaluo_total,tit_1id,imp_neto,fech_imp,no_orden,sup_terr FROM imp_pagados WHERE id_inmu = '$id_inmu' AND fech_imp IS NOT NULL ORDER BY gestion DESC LIMIT 1";
$check = pg_num_rows(pg_query($sql));
if ($check == 0) {
   $sql="SELECT sistem, id, id_inmu_siim FROM siim_selected WHERE id_inmu = '$id_inmu' AND selected = '1'";	 
   $siim_selected_check = pg_num_rows(pg_query($sql));
	 if ($siim_selected_check == 0) {
	    $gestion = $titular_pagados = $avaluo_pagados = $imp_neto = $fech_imp = $no_orden = $sistema = "-";		 
	 } else {  
	    $result=pg_query($sql);
      $info = pg_fetch_array($result, null, PGSQL_ASSOC);		   	 
      $sistema = $info['sistem'];
      $id = $info['id'];
      $id_inmu_siim = $info['id_inmu_siim'];
			if ($sistema == "SIIM") {
			   $tabla_satliqin = "satliqin";
				 $tabla_satnombre = "satnombr";
			} else {
			   $tabla_satliqin = "satliqin_2";
				 $tabla_satnombre = "satnombr_2";			
			}				
      $sql="SELECT gestion, base_imp, imp_neto, pagado, cuota, control FROM $tabla_satliqin WHERE id = '$id' AND id_inmu = '$id_inmu_siim' AND pagado IS NOT NULL ORDER BY gestion DESC LIMIT 1";	 
      $siim_check = pg_num_rows(pg_query($sql));   
	    if ($siim_check == 0) {
	       $gestion = $titular_pagados = $forma_pago = $imp_neto = $fech_imp = $no_orden = $sistema = "-";	 
	    } else {
	       $result=pg_query($sql);
         $info = pg_fetch_array($result, null, PGSQL_ASSOC);   
         $gestion = $info['gestion']; 
         $avaluo_pagados = $info['base_imp'];
         $imp_neto = $info['imp_neto'];
         $fech_imp = $info['pagado'];
				 $fech_ano = substr($fech_imp, 0, 4);
				 $fech_mes = substr($fech_imp, 4, 2);
				 $fech_dia = substr($fech_imp, 6, 2);	 
         $fech_imp = $fech_dia."/".$fech_mes."/".$fech_ano; 					 			 
         $no_orden = $info['control'];
         pg_free_result($result);	       				 
         $sql="SELECT paterno, materno, nombre FROM $tabla_satnombre WHERE id = '$id'";	 			
         $satnombr_check = pg_num_rows(pg_query($sql));   
	       if ($satnombr_check == 0) {
	          $titular_pagados = "-";	 
	       } else {
	          $result=pg_query($sql);
            $info = pg_fetch_array($result, null, PGSQL_ASSOC);   
            $paterno = trim(utf8_decode($info['paterno'])); 
            $materno = trim(utf8_decode($info['materno']));
            $nombre = trim(utf8_decode($info['nombre']));	
            $titular_pagados = $paterno." ".$materno." ".$nombre;	
			   }																       
			}
	 }
} else {
   $sistema = "CATASTRO";
   $result=pg_query($sql);
   $info = pg_fetch_array($result, null, PGSQL_ASSOC);
   $gestion = $info['gestion']; 
	 $avaluo_pagados = $info['avaluo_total']; 
   $titular_pagados = get_contrib_nombre ($info['tit_1id']);
   $imp_neto = $info['imp_neto'];
   $fech_imp = change_date ($info['fech_imp']);
   $no_orden = $info['no_orden'];
	 $sup_terr = $info['sup_terr'];
   pg_free_result($result);
}
$max_strlen_titular_pag = 26;
if (strlen($titular_pagados) > $max_strlen_titular_pag) {
   $titular_pagados = substr($titular_pagados, 0, $max_strlen_titular_pag).".";
}
################################################################################
#-- CHEQUEAR SI EL ULTIMO PAGO ERA PARA LA SUPERFICIE TOTAL O SI HABIA FUSION -#
################################################################################
$check_fus = 0;
if (($check > 0) AND ($area != $sup_terr)) {
	 ### CHEQUEAR SI HAY UNA GEOMETRIA INACTIVA DENTRO DEL LA GEOMETRIA ACTUAL
   $sql="SELECT cod_uv,cod_man,cod_pred FROM predios WHERE st_within (the_geom, (SELECT the_geom FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred')) AND activo = '0' ORDER BY cod_uv,cod_man,cod_pred";
   $check_fus = pg_num_rows(pg_query($sql));
	 $result=pg_query($sql);
   $i = $j = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
			   if ($i == 0) {
            $cod_uv_temp = $col_value;
			   } elseif ($i == 1) {
            $cod_man_temp = $col_value;
				 } else {
            $cod_pred_temp = $col_value;				 						 
            $sql="SELECT gestion,valor_t,valor_vi,avaluo_total,tit_1id,imp_neto,fech_imp,no_orden,sup_terr FROM imp_pagados WHERE cod_cat = '$cod_cat_temp' AND fech_imp IS NOT NULL AND gestion = '$gestion'";
            $sistema_fus[$j] = "CATASTRO";
            $result_fus = pg_query($sql);
            $info_fus = pg_fetch_array($result_fus, null, PGSQL_ASSOC);
            $gestion_fus[$j] = $info_fus['gestion'];
				    $avaluo_terreno_fus[$j] = $info_fus['valor_t'];
				    $avaluo_const_fus[$j] = $info_fus['valor_vi'];
	          $avaluo_pagados_fus[$j] = $info_fus['avaluo_total']; 
            $titular_pagados_fus[$j] = trim(utf8_decode($info_fus['titular']));
            $imp_neto_fus[$j] = $info_fus['imp_neto'];
            $fech_imp_fus[$j] = change_date ($info_fus['fech_imp']);
            $no_orden_fus[$j] = $info_fus['no_orden'];
	          $sup_terr_fus[$j] = $info_fus['sup_terr'];
				    $avaluo_terreno = $avaluo_terreno + $avaluo_terreno_fus[$j];
            $avaluo_const = $avaluo_const + $avaluo_const_fus[$j];
            $avaluo_total = $avaluo_total + $avaluo_pagados_fus[$j];
            pg_free_result($result_fus);	
				    $j++;
						$i = -1;
			   }
				 $i++;
		  }
   }
	 if ($check_fus > 0) {	
	    $texto_para_observ = "Se ha fusionado el predio con otro predio después del pago de los impuestos de la gestión $gestion!";
      if ($observ_fila1 == "") {
	       $observ_fila1 = $texto_para_observ;
      } elseif ($observ_fila2 == "") {
	       $observ_fila2 = $texto_para_observ;
      } elseif ($observ_fila3 == "") {
	       $observ_fila3 = $texto_para_observ;				 		 
	    } else {
	       $observ_fila3 = $observ_fila3." ".$texto_para_observ;
	    }
   } 	 
}
################################################################################
#---------------- CHEQUEAR SI YA SE PUEDE CANCELAR LA GESTION -----------------#
################################################################################
$gestion_actual = $ano_actual-1;
$sql="SELECT gestion FROM imp_escala_imp WHERE gestion = '$gestion_actual'";
$check_imp_escala_imp = pg_num_rows(pg_query($sql));  
$sql="SELECT gestion FROM imp_fecha_venc WHERE gestion = '$gestion_actual'";
$check_imp_fecha_venc = pg_num_rows(pg_query($sql));	
if (($check_imp_escala_imp == 4) AND ($check_imp_fecha_venc == 1)) {
   $gestion_actual = $ano_actual -1;
} else { 
   $gestion_actual = $ano_actual -2;
}
################################################################################
#------------------- SOLO DEJAR IMPRIMIR SI TODO ESTA PAGADO ------------------#
################################################################################
$fecha_limite = $gestion_actual."-12-31";
$sql="SELECT id_inmu FROM cambios WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' AND variable = 'cod_cat' AND fecha_cambio > '$fecha_limite'";
$check_cambios = pg_num_rows(pg_query($sql));	
if ($check_cambios > 0) {
   $todo_pagado = true;
} else {
   $sql="SELECT gestion FROM imp_escala_imp WHERE gestion = '$gestion_actual'";
   $check_escala_imp = pg_num_rows(pg_query($sql));	
   if ($check_escala_imp > 0) {
      $gestion_que_tiene_haber_pagado = $gestion_actual;
   } else $gestion_que_tiene_haber_pagado = $gestion_actual-1;
   if ($gestion >= $gestion_que_tiene_haber_pagado) {
      $todo_pagado = true;
   } else $todo_pagado = false;
}
#echo "GESTION QUE TIENE QUE HABER CANCELADO: $gestion_que_tiene_haber_pagado<br>";
################################################################################
#--------------------------- VALORACION DEL TERRENO ---------------------------#
################################################################################	
include "igm_impuestos_calcular_terreno.php";
#echo " RESULTADO: $avaluo_terr_actual";
################################################################################
#------------------------ VALORACION DE EDIFICACIONES -------------------------#
################################################################################			
$sql="SELECT * FROM info_edif WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' ORDER BY edi_num, edi_piso ASC";
$no_de_edificaciones = pg_num_rows(pg_query($sql));
$avaluo_const_actual = 0;
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
#echo "LINE_VALUE: $line_value[$i], OBJETOS: $no_de_objetos_validos[$i] <br>";								 						 
				 }																																			
#            $edi_temp[$i][$k] = $col_value; 	
         $k++; 
      } # END_OF_FOREACH	 
			$line_media[$i] = ROUND($line_value[$i]/$no_de_objetos_validos[$i],2); 
#echo "LINE_VALUE TOTAL $i: $line_value[$i], OBJETOS: $no_de_objetos_validos[$i], MEDIA: $line_media[$i] <br>";   		
      ########################################
      #----------- CALCULAR VALOR -----------#
      ########################################			
      $calidad_const[$i] = imp_calidad_const($gestion_actual,$line_media[$i]);
	    if ($calidad_const[$i] == 0) {
			   $avaluo_edif_separado[$i] = "-";
			}	else {
 	       $factor_deprec[$i] = imp_factor_deprec($gestion_actual,$edi_ano[$i],$ano_actual);	
			   $avaluo_edif_separado[$i] = avaluo_const($calidad_const[$i], $area_edif[$i], $factor_deprec[$i]);	
				 $avaluo_const_actual = $avaluo_const_actual + $avaluo_edif_separado[$i];		
			}	
#echo "CALIDAD CONST $gestion_actual : $calidad_const[$i], FACTOR_DEPREC: $factor_deprec[$i], AVALUO CONST: $avaluo_edif_separado[$i]<br>";								
		  $k = 0;
      $i++;				
   } # END_OF_WHILE	
}
################################################################################
#--------------------------- VALORACION DEL TERRENO ---------------------------#
################################################################################	

$avaluo_total_actual = $avaluo_terr_actual + $avaluo_const_actual;

################################################################################
#--------------------------------- GRAVAMEN -----------------------------------#
################################################################################
$sql="SELECT texto FROM gravamen WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";	 
$gravamen_check = pg_num_rows(pg_query($sql));   
if ($gravamen_check == 0) {
   $observ_fila4 = "&nbsp";

} else {
   $observ_fila4 = "EL PREDIO TIENE UN GRAVAMEN !"; 
}
################################################################################
#---------------------------------- NOTA --------------------------------------#
################################################################################
$sql="SELECT nota_cert FROM imp_base";
$result_nota = pg_query($sql);
$info = pg_fetch_array($result_nota, null, PGSQL_ASSOC);
$nota_certificado_catastral = utf8_decode ($info['nota_cert']);
pg_free_result($result_nota);	
###############################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	

$filename = "C:/apache/htdocs/tmp/cc".$cod_cat.".html";

################################################################################
#------------------------ PREPARAR CONTENIDO PARA IFRAME ----------------------#
################################################################################	 
$espacio_entre_tablas = 22;
$content = " 
<div align='left'>
<table border='1' width='100%' height='161' style='border:2px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'><font face='Tahoma' size='2'>
   <tr>
      <td rowspan='2' colspan='4'>
			    <table>
					   <tr>
						    <td width='20%'>
                   <img src='http://$server/$folder/css/banner_blanco_peq.png' alt='imagen' width='115' height='101' border='0'>
                </td>
								<td width='80%' align='center'>
								   <font style='font-family: Tahoma; font-size: 9pt;'>GOBIERNO MUNICIPAL DE $municipio</font><br />
									 <font style='font-family: Tahoma; font-size: 3pt;'>&nbsp</font><br />
									 <font style='font-family: Tahoma; font-size: 9pt;'>CONSEJO DEL PLAN REGULADOR</font><br />
									 <font style='font-family: Tahoma; font-size: 3pt;'>&nbsp</font><br />
									 <font style='font-family: Tahoma; font-size: 8pt;'>- DISTRITO $distrito -</font>
								</td> 
						 </tr>
					</table>
       </td>
       <td align='right' valign='top' colspan='4'>";
if ($todo_pagado) {	
  $content = $content."		 
          $fecha2 - $hora <a href='javascript:print(this.document)'>
          <img border='0' src='http://$server/$folder/graphics/printer.png' width='15' height='15' title='Imprimir en hoja tamaño Oficio'></a>";
} else {
  $content = $content."		
          <font color='red'>*** Para imprimir el certificado tiene que cancelar la gestion $gestion_que_tiene_haber_pagado *** &nbsp&nbsp&nbsp</font>";
}		
  $content = $content."				 
          <h1>CERTIFICADO CATASTRAL &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp </h1>
      </td>
   </tr>
   <tr height='30'>
      <td align='center' colspan='4'>
         <font style='font-family: Tahoma; font-size: 10pt; font-weight:bold;'>CODIGO : $cod_geo/$cod_cat</font>
      </td>		 				
   </tr>
   <tr height='20'>
      <td align='left' colspan='8'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp PROPIETARIO(S) : </font>
				 <font style='font-family: Tahoma; font-size: $font_size_prop;'>$propietario</font>
      </td>									 				
   </tr>
  <tr height='20'>
      <td align='left' colspan='3'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp DEPARTAMENTO : $depart</font>
      </td>
      <td align='left' colspan='3'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp PROVINCIA : $provincia</font>
      </td>	
      <td align='left' colspan='2'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp SECCION : $seccion</font>
      </td>										 				
   </tr>
   <tr height='20'>
      <td align='left' colspan='2'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp MUNICIPIO : $municipio</font>
      </td>	
      <td align='left' colspan='3'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp DISTRITO : $distrito</font>
      </td>					
      <td align='left' colspan='3'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp COMUNIDAD : $comunidad</font>
      </td>					 				
   </tr>	 	 
   <tr height='20'>
      <td align='left' colspan='2'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp ZONA : $zona</font>
      </td>
      <td align='left'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp U.V. : $cod_uv</font>
      </td>			
      <td align='left'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp MZ : $cod_man</font>
      </td>			
      <td align='left'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp PRED. : $cod_pred</font>
      </td>		
      <td align='left'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp BLQ : $cod_blq</font>
      </td>							
      <td align='left'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp PISO : $cod_piso</font>
      </td>		
      <td align='left'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp APTO : $cod_apto</font>
      </td>								 				
   </tr>	  
   <tr height='20'>
      <td align='left' colspan='2'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp BARRIO : $barrio</font>
      </td>	 
      <td align='left' colspan='3'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp SUP. SEGUN MENS. : $area m²</font>
      </td>
      <td align='left' colspan='3'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp SUP. SEGUN DOC. : $adq_sdoc m²</font>
      </td>													 				
   </tr>
   <tr height='20'>			
      <td align='left' colspan='8'>
         <font style='font-family: Tahoma; font-size: 9pt;'>&nbsp DIRECCION : $direccion</font>
      </td>							 				
   </tr>		 
   <tr height='$espacio_entre_tablas' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='bottom' colspan='8'>
         &nbsp DATOS DEL TERRENO :
      </td>					 				
   </tr>		 	 	 	  
   <tr height='20' style='font-family: Tahoma; font-size: 8pt; font-weight: bold'>					
	    <td align='center' width='12%'>
		     Z. Homog.
			</td>
		  <td align='center' width='13%'>
			   Material Vía
		  </td>
			<td align='center' width='12%'>
			   Agua
		  </td>							 
		  <td align='center' width='13%'>
		     Alcantarillado
			</td>	
		  <td align='center' width='12%'>
			   Luz
		  </td>							 
		  <td align='center' width='13%'>
			   Teléfono
			</td>	
			<td align='center' width='12%'>
			   Inclinación
			</td>
			<td align='center' width='13%'>
			   Superf. Mens.
			</td>							 			 							 						 
   </tr> 
   <tr height='20' style='font-family: Tahoma; font-size: 8pt;'>
	    <td align='center'>
			   $ben_zona
      </td>
			<td align='center'>
			   $via_mat
			</td>
		  <td align='center'>
		     $ser_agu
			</td>							 
	    <td align='center'>
			   $ser_alc
		  </td>	
		  <td align='center'>
		     $ser_luz
	    </td>							 
		  <td align='center'>
			   $ser_tel
		  </td>	
		  <td align='center'>
		     $ter_topo
			</td>
		  <td align='center'>
			   $area  m²
		  </td>							 						 							 						 
   </tr>	
  <tr height='$espacio_entre_tablas' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='bottom' colspan='8'>
         &nbsp COLINDANCIAS Y MEDIDAS :
      </td>					 				
   </tr>		 	 	 	  
   <tr height='20' style='font-family: Tahoma; font-size: 8pt; font-weight: bold'>					
	    <td align='center'>
		     Limites
			</td>
		  <td align='center' colspan='2'>
			   Colindantes
		  </td>						 
		  <td align='center'>
		     Medida s/mens
			</td>	
		  <td align='center'>
			   Limites
		  </td>							 
		  <td align='center' colspan='2'>
			   Colindantes
			</td>	
			<td align='center'>
			   Medida s/mens
			</td>							 			 							 						 
   </tr> 
   <tr height='20' style='font-family: Tahoma; font-size: 8pt;'>
	    <td align='center'>
			   NORTE
      </td>
			<td align='left' colspan='2' style='font-family: Tahoma; font-size: $font_size_norte;'>
			   &nbsp $col_norte_nom
			</td>						 
	    <td align='center' style='font-family: Tahoma; font-size: $font_size_norte_med;'>
			   $col_norte_med
		  </td>	
		  <td align='center'>
		     ESTE
	    </td>							 
		  <td align='left' colspan='2' style='font-family: Tahoma; font-size: $font_size_este;'>
			   &nbsp $col_este_nom
		  </td>	
		  <td align='center' style='font-family: Tahoma; font-size: $font_size_este_med;'>
			   $col_este_med
		  </td>							 						 							 						 
   </tr>	
   <tr height='20' style='font-family: Tahoma; font-size: 8pt;'>
	    <td align='center'>
			   SUR
      </td>
			<td align='left' colspan='2' style='font-family: Tahoma; font-size: $font_size_sur;'>
			   &nbsp $col_sur_nom
			</td>						 
	    <td align='center' style='font-family: Tahoma; font-size: $font_size_sur_med;'>
			   $col_sur_med
		  </td>	
		  <td align='center'>
		     OESTE
	    </td>							 
		  <td align='left' colspan='2' style='font-family: Tahoma; font-size: $font_size_oeste;'>
			   &nbsp $col_oeste_nom
		  </td>	
		  <td align='center' style='font-family: Tahoma; font-size: $font_size_oeste_med;'>
			   $col_oeste_med
		  </td>							 						 							 						 
   </tr>		 	 
   <tr height='$espacio_entre_tablas' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='bottom' colspan='8'>
         &nbsp DATOS DE LAS CONSTRUCCIONES :
      </td>					 				
   </tr>																	
	 <tr height='10' style='font-family: Tahoma; font-size: 8pt; font-weight: bold'>
	    <td align='center'>
		     Unidad
		  </td>
	    <td align='center'>
		     Piso
		  </td>
	    <td align='center'>
			   Tipo de Constr.
		  </td>
	    <td align='center'>
			   Año Const.
		  </td>							 
	    <td align='center'>
		     Estado visual
		  </td>	
	    <td align='center'>
		     Valuación
			</td>							 
	    <td align='center'>
			   Superf. Mens.
	    </td>	
	    <td align='center'>
			   Valor
		  </td>				 							 						 
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
      <td align='center'>
			   $edi_num[$i]
			</td>
      <td align='center'>
			   $edi_piso[$i]
		  </td>
      <td align='center'>
		     $edi_tipo[$i]
			</td>
      <td align='center'>
		     $edi_ano[$i]
			</td>							 
      <td align='center'>
			   $edi_edo[$i]
		  </td>	
      <td align='center'>
			   $line_media[$i]
		  </td>							 
      <td align='center'>
			   $area_edif[$i] m²
			</td>	
      <td align='center'>
			   $avaluo_edif_separado[$i]
			</td>					 							 						 
   </tr>";	
   $i++;
} 
$i = 0;
while ($i < $filas_vacias) {
  $content = $content."						
	 <tr height='14'>
      <td align='center'>
			   ---
			</td>
      <td align='center'>
			   ---
		  </td>
      <td align='center'>
		     ---
			</td>
      <td align='center'>
		     ---
			</td>							 
      <td align='center'>
			   ---
		  </td>	
      <td align='center'>
			   ---
		  </td>							 
      <td align='center'>
			   ---
			</td>	
      <td align='center'>
			   ---
			</td>					 							 						 
   </tr>";	
   $i++;
}
$content = $content."
	 <tr height='20'>
      <td align='left' colspan='5'>";
if ($fila_activa) {
     $texto = "&nbsp --> El predio tiene en total $no_de_edif_real edificaciones.";
} else {
    $texto = "&nbsp";
}
$content = $content."$texto
			</td>
      <td align='center'>
	       <b>TOTAL</b>
		  </td>
      <td align='center'>
	       <b>$edi_area m²</b>
		  </td>
      <td align='center'>
	       <b>$avaluo_const_actual</b>
			</td>				 							 						 
   </tr>	
   <tr style='font-family: Tahoma; font-size: 7pt'>
      <td align='left' valign='center' colspan='8'>
         &nbsp Valuación de Construcciones: Marginal(< 1.5), Muy Económico(1.5-2.5), Económico(2.5-3.5), Bueno(3.5-4.5), Muy Bueno(4.5-5.5), Lujoso(> 5.5)
      </td>					 				
   </tr>	
   <tr height='$espacio_entre_tablas' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='bottom' colspan='8'>
         &nbsp DERECHO PROPIETARIO :
      </td>					 				
   </tr>	
	 <tr height='20' style='font-family: Tahoma; font-size: 8pt; font-weight: bold'>
	    <td align='center' colspan='2'>
		     Caracter Titularidad
		  </td>
	    <td align='center' colspan='2'>
		     Modo de Obtención
		  </td>
	    <td align='center'>
			   Fecha
		  </td>
	    <td align='center' colspan='3'>
			   Tipo de Documento
		  </td>							 	 							 						 
	 </tr>	
	 <tr height='20' style='font-family: Tahoma; font-size: 8pt'>
	    <td align='center' colspan='2'>
		     $tit_cara
		  </td>
	    <td align='center' colspan='2'>
		     $adq_modo
		  </td>
	    <td align='center'>
			   $adq_fech
		  </td>
	    <td align='center' colspan='3'>
			   $adq_doc1<br />
				 $adq_doc2<br />
				 $adq_doc3
		  </td>							 	 							 						 
	 </tr>
   <tr height='$espacio_entre_tablas' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='bottom' colspan='8'>
         &nbsp INSCRIPCION EN DERECHOS REALES :
      </td>					 				
   </tr>	
	 <tr height='20' style='font-family: Tahoma; font-size: 8pt;'>
	    <td align='left' colspan='5'>
		     &nbsp <b>Número de Registro :</b> &nbsp&nbsp $der_num
		  </td>
	    <td align='left' colspan='3'>
		     &nbsp <b>Fecha de Registro :</b> &nbsp&nbsp $der_fech
		  </td>					 	 							 						 
	 </tr>	
   <tr height='$espacio_entre_tablas' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='bottom' colspan='8'>
         &nbsp TRADICION DE INMUEBLE :
      </td>					 				
   </tr>	
	 <tr height='20' style='font-family: Tahoma; font-size: 8pt; font-weight: bold'>
	    <td align='center'>
			   Desde Fecha
		  </td>
	    <td align='center'>
			   Hasta Fecha
		  </td>					
	    <td align='center' colspan='3'>
		     Nombres Apellidos
		  </td>
	    <td align='center'>
		     Doc. Id.
		  </td>
	    <td align='center' colspan='2'>
			   Carácter Titularidad
		  </td>							 	 							 						 
	 </tr>	
	 <tr height='20' style='font-family: Tahoma; font-size: 8pt'>
	    <td align='center'>
			   $fecha_trad_ini[0]
		  </td>		
	    <td align='center'>
			   $fecha_trad_fin[0]
		  </td>				
	    <td align='center' colspan='3'>
		     $tit1_trad[0]
		  </td>
	    <td align='center'>
		     $ci_trad[0]
		  </td>
	    <td align='center' colspan='2'>
			   $cara_trad[0]
		  </td>						 	 							 						 
	 </tr>
	 <tr height='20' style='font-family: Tahoma; font-size: 8pt'>
	    <td align='center'>
			   $fecha_trad_ini[1]
		  </td>
	    <td align='center'>
			   $fecha_trad_fin[1]
		  </td>						
	    <td align='center' colspan='3'>
		     $tit1_trad[1]
		  </td>
	    <td align='center'>
		     $ci_trad[1]
		  </td>
	    <td align='center' colspan='2'>
			   $cara_trad[1]
		  </td>						 	 							 						 
	 </tr>
	 <tr height='20' style='font-family: Tahoma; font-size: 8pt'>
	    <td align='center'>
			   $fecha_trad_ini[2]
		  </td>		
	    <td align='center'>
			   $fecha_trad_fin[2]
		  </td>				
	    <td align='center' colspan='3'>
		     $tit1_trad[2]
		  </td>
	    <td align='center'>
		     $ci_trad[2]
		  </td>
	    <td align='center' colspan='2'>
			   $cara_trad[2]
		  </td>							 	 							 						 
	 </tr>	 	 	 	 	 	
   <tr height='$espacio_entre_tablas' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='bottom' colspan='8'>
         &nbsp AVALUO CATASTRAL :
      </td>					 				
   </tr>	
	 <tr height='20' style='font-family: Tahoma; font-size: 8pt; font-weight: bold'>
	    <td align='left' colspan='2'>
		     &nbsp Valor Terreno : &nbsp&nbsp $avaluo_terreno
		  </td>
	    <td align='left' colspan='3'>
		     &nbsp Valor Construcción : &nbsp&nbsp $avaluo_const
		  </td>
	    <td align='left' colspan='3'>
			   &nbsp Valor Total : &nbsp&nbsp $avaluo_total
		  </td>								 	 							 						 
	 </tr>	
   <tr height='$espacio_entre_tablas' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' valign='bottom' colspan='8'>
         &nbsp ULTIMO PAGO TRIBUTARIO :
      </td>					 				
   </tr>		 	 	 	  
   <tr height='20' style='font-family: Tahoma; font-size: 8pt; font-weight: bold'>					
	    <td align='center'>
		     Gestión
			</td>
		  <td align='center' colspan='2'>
			   Titular
		  </td>
			<td align='center'>
			   No. Orden
		  </td>		
		  <td align='center'>
			   Avaluo Total
			</td>									 
		  <td align='center'>
		     Importe Neto
			</td>	
		  <td align='center'>
			   Fecha Pago
		  </td>							 
			<td align='center'>
			   Sistema
			</td>					 			 							 						 
   </tr>";
if($check_fus == 0) {
   $content = $content." 
   <tr height='20' style='font-family: Tahoma; font-size: 8pt;'>";
} else {
   $content = $content." 
   <tr height='14' style='font-family: Tahoma; font-size: 8pt;'>";
}
$content = $content." 
	    <td align='center'>
		     $gestion
			</td>
		  <td align='center' colspan='2'>
			   $titular_pagados
		  </td>
			<td align='center'>
			   $no_orden
		  </td>	
		  <td align='center'>
			   $avaluo_pagados
			</td>										 
		  <td align='center'>
		     $imp_neto
			</td>	
		  <td align='center'>
			   $fech_imp
		  </td>							 
			<td align='center'>
			   $sistema
			</td>					 						 							 						 
   </tr>";
$i = 0;
while ($i < $check_fus) {
   $content = $content."
   <tr height='14' style='font-family: Tahoma; font-size: 8pt;'>
	    <td align='center'>
		     $gestion_fus[$i]
			</td>
		  <td align='center' colspan='2'>
			   $titular_pagados_fus[$i]
		  </td>
			<td align='center'>
			   $no_orden_fus[$i]
		  </td>	
		  <td align='center'>
			   $avaluo_pagados_fus[$i]
			</td>										 
		  <td align='center'>
		     $imp_neto_fus[$i]
			</td>	
		  <td align='center'>
			   $fech_imp_fus[$i]
		  </td>							 
			<td align='center'>
			   $sistema_fus[$i]
			</td>					 						 							 						 
   </tr>";
	 $i++;
}		
$content = $content."	
   <tr height='80' style='font-family: Tahoma; font-size: 8pt'>
      <td align='left' colspan='8'>
         &nbsp <b>OBSERVACIONES :</b><br />
				 &nbsp $observ_fila1<br />
				 &nbsp $observ_fila2<br />
				 &nbsp $observ_fila3<br />
				 <div align='center' style='font-family: Tahoma; font-size: 10pt; font-weight: bold'> 
				    $observ_fila4
				 </div>				 				 			 
      </td>					 				
   </tr>			   		  	
   <tr height='40'>              							 
      <td colspan='8' style='font-family: Tahoma; font-size: 6pt'>
         &nbsp NOTA:<br />&nbsp $nota_certificado_catastral
      </td>
   </tr> 		 
   <tr height='100' style='font-family: Tahoma; font-size: 10pt'>              							 
      <td align='center' valign='top' colspan='4'>
         APROBACION O.T.P.R.
      </td>
      <td align='left' valign='top' colspan='4'>
         &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp ALCALDIA DE $distrito<br />
         <img border='0' src='http://$server/$folder/graphics/marca_de_agua.png' width='15' height='62'>
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