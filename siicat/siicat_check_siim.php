<?php


################################################################################
#                        CHEQUEAR SI EXISTE INFO_PREDIO                        #
################################################################################	
$sql="SELECT cod_cat FROM info_predio WHERE cod_cat = '$cod_cat_temp'";
$check_info_predio = pg_num_rows(pg_query($sql));
################################################################################
#                         CHEQUEAR SI EXISTE INFO_PREDIO                       #
################################################################################	
if ($check_info_predio > 0 ) {	 
      $resultado = true;
			########################################
	    #   OBTENER DATOS DE LA BASE DE DATOS  #
	    ########################################	
      $sql="SELECT * FROM info_predio WHERE cod_cat = '$cod_cat_temp'";
      $result = pg_query($sql);
      $info = pg_fetch_array($result, null, PGSQL_ASSOC);
      $cod_pad = $info['cod_pad'];
      $cod_uv = $info['cod_uv'];
      $cod_man = $info['cod_man'];
      $cod_pred = $info['cod_pred'];													
      $dir_nom = $info['dir_nom'];			
      $tit_1nom1 = utf8_decode($info['tit_1nom1']);
			$tit_1nom2 = utf8_decode($info['tit_1nom2']);
			$tit_1pat = strtoupper(utf8_decode($info['tit_1pat']));
			$tit_1mat = strtoupper(utf8_decode($info['tit_1mat']));
			$titular1 = trim($tit_1nom1." ".$tit_1nom2." ".$tit_1pat." ".$tit_1mat);
			if ($titular1 == "") {
			   $titular1 = "-";
			}			
      $tit_2nom1 = utf8_decode($info['tit_2nom1']);
			$tit_2nom2 = utf8_decode($info['tit_2nom2']);
			$tit_2pat = strtoupper(utf8_decode($info['tit_2pat']));
			$tit_2mat = strtoupper(utf8_decode($info['tit_2mat']));
			$titular2 = $tit_2nom1." ".$tit_2nom2." ".$tit_2pat." ".$tit_2mat;     
			if (trim($titular2) == "") {
			   $titular2 = "-";
			}		
			$tit_1ci = trim($info['tit_1ci']);
			$tit_1ci = substr($tit_1ci,0,12);
			$tit_1nit = trim($info['tit_1nit']);
			if ($tit_1ci == "") {  
	      if ($tit_1nit == "") {
			     $texto_ci_nit1 = "-";
			  } else $texto_ci_nit1 = $tit_1nit;    
	    } else $texto_ci_nit1 = $tit_1ci; 
			$tit_2ci = trim($info['tit_2ci']);
			$tit_2ci = substr($tit_2ci,0,12);
			$tit_2nit = trim($info['tit_2nit']);
}
################################################################################
#------------------- CHEQUEAR REGISTROS EN SIMM-SATNOMBR ----------------------#
################################################################################	
$tit_1pat_temp = utf8_encode ($tit_1pat);
$tit_1mat_temp = utf8_encode ($tit_1mat);
$tit_1nom1_temp = utf8_encode ($tit_1nom1);
$length_codpad = strlen ($cod_pad);
$cod_pad_temp = substr($cod_pad,0,$length_codpad-3);
$cod_pad_ext = (int) substr($cod_pad,$length_codpad-2,2);
$tit_1ci_temp = "";
$i = 0;
while ($i < strlen($tit_1ci)) {
   $char = substr($tit_1ci,$i,1);
	 if (check_numeros($char)) {
	    $tit_1ci_temp = $tit_1ci_temp.$char;
	 }
	 $i++;
}
#echo "$tit_1ci_temp <br />";
$sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr WHERE documento ~* '$tit_1ci_temp'";
$check_satnombr = pg_num_rows(pg_query($sql));
if ($check_satnombr != 1) {
  $sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr WHERE paterno = '$tit_1pat_temp' AND materno = '$tit_1mat_temp' AND nombre ~* '$tit_1nom1_temp'";
  $check_satnombr = pg_num_rows(pg_query($sql));	
}
if ($check_satnombr != 1) { 
  $sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr WHERE id = '$cod_pad_temp'";
  $check_satnombr = pg_num_rows(pg_query($sql));	
}
########################################
#- NO HAY RESULTADO, PERO HAY TRANSFER-#
########################################	
if ($check_satnombr == 0) {
   $sql="SELECT tit_1pat_ant, tit_1mat_ant, tit_1nom1_ant, tit_1ci_ant, cod_pad_ant FROM transfer WHERE cod_cat = '$cod_cat_temp' ORDER BY adq_fech DESC";
	 $check_transfer = pg_num_rows(pg_query($sql));
	 if ($check_transfer > 0) {
	    $encontrado_trans = false;
	    $i = 0; 
	    $result_trans = pg_query($sql);
      while (($line = pg_fetch_array($result_trans, null, PGSQL_ASSOC)) AND (!$encontrado_trans)) {
         foreach ($line as $col_value) {	    
			      if ($i == 0) { 
						   $tit_1pat_temp = utf8_decode($col_value);
            } elseif ($i == 1) {
						   $tit_1mat_temp = utf8_decode($col_value);
            } elseif ($i == 2) {
						   $tit_1nom1_temp = utf8_decode($col_value);
            } elseif ($i == 3) {
						   $tit_1ci_trans = $col_value;
			         $tit_1ci_temp = "";
               $ix = 0;
               while ($ix < strlen($tit_1ci_trans)) {
                  $char = substr($tit_1ci_trans,$ix,1);
	                if (check_numeros($char)) {
	                   $tit_1ci_temp = $tit_1ci_temp.$char;
	                }
	                $ix++;
               }							  	
						} else {
						   $cod_pad_trans = $col_value;
			         $length_codpad = strlen ($cod_pad_trans);						
               $cod_pad_temp = substr($cod_pad_trans,0,$length_codpad-3);
               $sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr WHERE documento ~* '$tit_1ci_temp'";
               $check_satnombr = pg_num_rows(pg_query($sql));
               if ($check_satnombr != 1) {
                  $sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr WHERE paterno = '$tit_1pat_temp' AND materno = '$tit_1mat_temp' AND nombre ~* '$tit_1nom1_temp'";
                  $check_satnombr = pg_num_rows(pg_query($sql));	
               } else $encontrado_trans = true;
               if ($check_satnombr != 1) { 
                  $sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr WHERE id = '$cod_pad_temp'";
                  $check_satnombr = pg_num_rows(pg_query($sql));
									if ($check_satnombr == 1) {
									   $encontrado_trans = true;
									}	
               }
							 $i = -1;
					  }
						$i++;
				 }
	    } # END_OF_WHILE							
   }
}
#$sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr WHERE paterno = '$tit_1pat_temp'";
#$check_satnombr = pg_num_rows(pg_query($sql)); 
if ($check_satnombr > 0) {
   $i = $j = 0;
   $result_siim = pg_query($sql);	 
   while ($line = pg_fetch_array($result_siim, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
         if ($i == 0) { 
	          $siim_id[$j] = $col_value;
         } elseif ($i == 1) {
				    if (trim($col_value) == "") {
						   $siim_doc[$j] = "-";   
	          } else $siim_doc[$j] = $col_value;			
         } elseif ($i == 2) {
	          $siim_pat[$j] = utf8_decode ($col_value);		
         } elseif ($i == 3) {
	          $siim_mat[$j] = utf8_decode ($col_value);		
         } elseif ($i == 4) {
	          $siim_nom[$j] = utf8_decode ($col_value);		
			   } else {
			      $siim_calle[$j] = utf8_decode ($col_value);
				    $i = -1;
			   }
			   $i++;
	    }
			$j++;
   }
	 $tabla_satinmus = "satinmus";
   $tabla_satliqin = "satliqin";
	 pg_free_result($result_siim);
}
################################################################################
#------------------ CHEQUEAR REGISTROS EN SIMM-SATNOMBR_2 ---------------------#
################################################################################
if ($check_satnombr == 0) {
   $sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr_2 WHERE documento ~* '$tit_1ci_temp'";
   $check_satnombr = pg_num_rows(pg_query($sql)); 
   if ($check_satnombr != 1) {
      $sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr_2 WHERE paterno = '$tit_1pat_temp' AND materno = '$tit_1mat_temp' AND nombre ~* '$tit_1nom1_temp'";
      $check_satnombr = pg_num_rows(pg_query($sql));	
   }
#   if ($check_satnombr != 1) {
#      $sql="SELECT id, documento, paterno, materno, nombre, nombrecall FROM satnombr_2 WHERE id = '$cod_pad_temp'";
#      $check_satnombr = pg_num_rows(pg_query($sql));	
#   }
   $check_satnombr = pg_num_rows(pg_query($sql)); 
   if ($check_satnombr > 0) {
	    $aviso = true;
			$aviso_color = "red";
			$mensaje_de_aviso = "REGISTRO ENCONTRADO EN EL SIIM DE SAN CARLOS!";
	 }
   $i = $j = 0;
   $result_siim = pg_query($sql);	 
   while ($line = pg_fetch_array($result_siim, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
         if ($i == 0) { 
	          $siim_id[$j] = $col_value;
         } elseif ($i == 1) {
				    if (trim($col_value) == "") {
						   $siim_doc[$j] = "-";   
	          } else $siim_doc[$j] = $col_value;			
         } elseif ($i == 2) {
	          $siim_pat[$j] = utf8_decode ($col_value);		
         } elseif ($i == 3) {
	          $siim_mat[$j] = utf8_decode ($col_value);		
         } elseif ($i == 4) {
	          $siim_nom[$j] = utf8_decode ($col_value);		
			   } else {
			      $siim_calle[$j] = utf8_decode ($col_value);
				    $i = -1;
			   }
			   $i++;
	    }
			$j++;
   }
	 pg_free_result($result_siim);
	 $tabla_satinmus = "satinmus_2";
	 $tabla_satliqin = "satliqin_2";	 
}
################################################################################
#-------------------- CHEQUEAR REGISTROS EN SIMM-SATINMUS ---------------------#
################################################################################	
#$no_de_registros = 0;
$check_satinmus = 0;
if ($check_satnombr == 1) {
   $id_satinmus = $siim_id[0];
   $sql="SELECT id_inmu, zona, nombrecall, superficie, sup_const, ant_const FROM $tabla_satinmus WHERE id = '$id_satinmus' ORDER BY id_inmu ASC";
   $check_satinmus = pg_num_rows(pg_query($sql)); 
	 if ($check_satinmus == 0) {
	    $no_de_reg_satinmus = 0;
	    $satinmus_fila = 0;
	    $id_inmu_sat[$satinmus_fila] = "01";				    
		 /*	$i = 0;
			while ($i < $no_de_registros) {	    
         $id_inmu[$i] = "N.N.";
			   $zona[$i] = "N.N.";
			   $nombrecall[$i] = "N.N.";
			   $superficie[$i] = "N.N.";
			   $sup_const[$i] = "N.N.";
			   $ant_const[$i] = "N.N."; 
				 $i++;
			}	*/			 
	 }
   if ($check_satinmus == 1) {
	    $no_de_reg_satinmus = 1;
	    $satinmus_fila = 0;
	#    $id_inmu_temp[$satinmus_fila] = "01";	    
      $result_siim_satinmus = pg_query($sql);
      $info_siim_satinmus = pg_fetch_array($result_siim_satinmus, null, PGSQL_ASSOC);		
      $id_inmu_sat[0] = $info_siim_satinmus['id_inmu'];
			$zona_sat[0] = $info_siim_satinmus['zona'];
			$nombrecall_sat[0] = utf8_decode($info_siim_satinmus['nombrecall']);
			$sup_terr_sat[0] = $info_siim_satinmus['superficie'];
			$sup_const_sat[0] = $info_siim_satinmus['sup_const'];
			$ant_const_sat[0] = $info_siim_satinmus['ant_const'];
			pg_free_result($result_siim_satinmus);
	 }
   ########################################
   #---- SI EXISTE MAS QUE 1 REGISTRO-----#
   ########################################	 
	 if ($check_satinmus > 1) {
      $i = $k = 0;
      $result_siim = pg_query($sql);	 
      while ($line = pg_fetch_array($result_siim, null, PGSQL_ASSOC)) {
         foreach ($line as $col_value) {
            if ($i == 0) { 
						   $id_inmu_sat[$k] = $col_value;	
            } elseif ($i == 1) {
						   if (trim($col_value) == "") {
							    $zona_sat[$k] = "-";
	             } else $zona_sat[$k] = $col_value;	
			      } elseif ($i == 2) {
						   if (trim($col_value) == "") {
							    $nombrecall_sat[$k] = "-";
							 } else $nombrecall_sat[$k] = utf8_decode($col_value);										 							 		
			      } elseif ($i == 3) { 
	             $sup_terr_sat[$k] = $col_value;
							 $delta_sup_terr[$k] = SQRT (($ter_smen - $sup_terr_sat[$k]) * ($ter_smen - $sup_terr_sat[$k]));
#echo "TERRENO: $ter_smen, $sup_terr_sat[$k], $delta_sup_terr[$k]<br>";							 
			      } elseif ($i == 4) { 
	             $sup_const_sat[$k] = $col_value;	
							 $delta_sup_const[$k] = SQRT (($edi_area - $sup_const_sat[$k]) * ($edi_area - $sup_const_sat[$k]));
#echo "EDIF: $edi_area, $sup_const_sat[$k], $delta_sup_const[$k]<br>";								 								 								 		
			      } else {
						   $ant_const_sat[$k] = $col_value;
				       $i = -1;
			      }
			      $i++;
	       }
			   $k++;
      }
	    pg_free_result($result_siim);
      ########################################
      # DEFINIR CUAL ES EL REGISTRO CORRECTO #
      ########################################				
			$no_de_reg_satinmus = $k;
			if (($cod_pad != "") AND ($cod_pad_ext <= $no_de_reg_satinmus)) {  #TITULAR TIENE COD_PAD
			   $satinmus_fila = $cod_pad_ext-1;   
			} else {
			   $i = 0;
			   $valor_mas_peq = $delta_sup_terr[0];
			   if ($edi_area > 0) {
			      $edificaciones = true;
			   } else $edificaciones = false;
			   $satinmus_fila = 0;
			   while ($i < $no_de_reg_satinmus-1) {
			      $k = $i+1;			
			      if ($delta_sup_terr[$k] < $valor_mas_peq) {
				       $valor_mas_peq = $delta_sup_terr[$k];
						   $satinmus_fila = $k;
			      } elseif ($delta_sup_terr[$k] == $valor_mas_peq) {
				       if ($edificaciones) {
						      if (($sup_const_sat[$k-1] == 0) AND ($sup_const_sat[$k] > 0)) {
				             $valor_mas_peq = $delta_sup_terr[$k];
						         $satinmus_fila = $k;							 
							    }
						   }  
				    }
				    $i++;
			   }
			} # END_OF_ELSE (TITULAR TIENE COD_PAD)		
   }
}
################################################################################
#-------------------- CHEQUEAR REGISTROS EN SIMM-SATLIQIN ---------------------#
################################################################################	
$no_de_registros = 0;
if ($check_satnombr == 1) {
   $id_satliqin = $siim_id[0];
	 $id_inmu_satliqin = $id_inmu_sat[$satinmus_fila];
   $sql="SELECT gestion, tp_inmu, fd_an, imp_neto, monto, fech_venc, pagado, cuota FROM $tabla_satliqin WHERE id = '$id_satliqin' AND id_inmu = '$id_inmu_satliqin' AND pagado IS NOT NULL ORDER BY gestion ASC";
   $check_satliqin = pg_num_rows(pg_query($sql)); 
   if ($check_satliqin > 0) {
      $i = $j = 0;
      $result_siim_satliqin = pg_query($sql);	 
      while ($line = pg_fetch_array($result_siim_satliqin, null, PGSQL_ASSOC)) {
         foreach ($line as $col_value) {
            if ($i == 0) { 
						   if ($tabla_satliqin == "satliqin") {
						      $sistema[$j] = "SIIM";
							 } else $sistema[$j] = "SI_2";	
	             $gestion[$j] = $col_value;	
							 $siim_gestion[$j] = $col_value;	
            } elseif ($i == 1) { 
	             $edi_tipo[$j] = $col_value;			
            } elseif ($i == 2) { 
	             $edi_ano[$j] = (string) $col_value;									 										 
            } elseif ($i == 3) { 
	             $imp_neto[$j] = $col_value;							
	             $siim_imp_neto[$j] = $col_value;	
            } elseif ($i == 4) { 						
	             $monto[$j] = $col_value;								 
			      } elseif ($i == 5) { 
						   $ano_siim = substr($col_value,0,4);
							 $mes_siim = substr($col_value,4,2);
							 $dia_siim = substr($col_value,6,2);
	             $fecha_venc[$j] = $dia_siim."/".$mes_siim."/".$ano_siim;										 							 		
			      } elseif ($i == 6) { 
						   $ano_siim = substr($col_value,0,4);
							 $mes_siim = substr($col_value,4,2);
							 $dia_siim = substr($col_value,6,2);
	             $fecha_pagado[$j] = $dia_siim."/".$mes_siim."/".$ano_siim;									 		
			      } else {
						   $cuota[$j] = $col_value;
			         $siim_cuota[$j] = $col_value;
				       $i = -1;
			      }
			      $i++;
	       }
				 $forma_pago[$j] = $valor_por_m2_terr[$j] = $factores_terreno[$j] = $avaluo_terr[$j] = $calidad_const[$j] = $avaluo_const[$j] = $factor_deprec[$j] = $avaluo_total[$j] = 0;
				 $monto_exen[$j] = $cuota_fija[$j] = $tp_exen[$j] = $base_imp[$j] = $ufv_venc[$j] = $mant_valor[$j] = $interes[$j] = $monto[$j] = $d10[$j] = 0;
			   $j++;
				 $no_de_registros++;
      }
	    pg_free_result($result_siim_satliqin);
      $ultimo_ano_pagado = $gestion[$j-1];	
   } else {
	    $ultimo_ano_pagado = 0;
			$sistema[0] = "NIN";
	 }		 
}	 else $ultimo_ano_pagado = 0;
################################################################################
#----------------- RELLENAR FILAS CON EL REGISTRO CORRECTO --------------------#
################################################################################		 
if ($check_satinmus == 1) {
   $i = 0;
   while ($i <= $no_de_registros) {
      $id_inmu[$i] = $id_inmu_sat[0];
		  $zona[$i] = $zona_sat[0];
			$nombrecall[$i] = $nombrecall_sat[0];
			$sup_terr[$i] = $sup_terr_sat[0];
			$sup_const[$i] = $sup_const_sat[0];
			$ant_const[$i] = $ant_const_sat[0];
			$i++;
   }		
} elseif ($check_satinmus > 1) {
   $i = 0;
   while ($i <= $no_de_registros) {
      $id_inmu[$i] = $id_inmu_sat[$satinmus_fila];
      $zona[$i] = $zona_sat[$satinmus_fila];
      $nombrecall[$i] = $nombrecall_sat[$satinmus_fila];
      $sup_terr[$i] = $sup_terr_sat[$satinmus_fila];
      $sup_const[$i] = $sup_const_sat[$satinmus_fila];
      $ant_const[$i] = $ant_const_sat[$satinmus_fila];
      $i++;
   }
} 
########################################
#---- CHEQUEAR TABLA SIIM SELECTED ----#
########################################	
if ($check_satinmus >=1) {
   $sql="SELECT cod_cat FROM siim_selected WHERE cod_cat = '$cod_cat' AND sistem = '$sistema[0]' AND id = '$id_satliqin' AND id_inmu = '$id_inmu_satliqin' AND selected = '1'";
   $check_siim_selected = pg_num_rows(pg_query($sql));
   if ($check_siim_selected == 0) {
      $no_de_registros = $ultimo_ano_pagado = 0;
	    $satinmus_fila = 99;  
   }
} 	

?>
