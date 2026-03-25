<?php
  
 # OBTENER ABREVIACION
 function abr($abreviacion) {
    $abreviacion = trim ($abreviacion);
    $sql="SELECT frase FROM abreviaciones WHERE abr = '$abreviacion'";
    $check_existence = pg_num_rows(pg_query($sql));
    if ($check_existence > 0 ) {
	       $result = pg_query($sql);
         $info_temp = pg_fetch_array($result, null, PGSQL_ASSOC);
         $frase = $info_temp['frase'];
				 pg_free_result($result);	
         return $frase;	       				
	    } else return $abreviacion;				   
 }
 
 # CHEQUEAR SI EL VALOR ESTA PERMITIDO 
 function check_value($columna,$valor) {
   if ($valor === "") {
	    return true;
	 } else {
      $sql="SELECT permitido FROM info_permitido WHERE col_nombre = '$columna'";
      $check_existence = pg_num_rows(pg_query($sql));
      if ($check_existence > 0 ) {	 
	       $result = pg_query($sql);
         $info_temp = pg_fetch_array($result, null, PGSQL_ASSOC);
         $cadena = $info_temp['permitido'];
			   $pos = strpos($cadena,$valor);				 
#echo " CADENA:$cadena, VAL:$valor, POSITION EN LA CADENA:$pos<br />";
	       pg_free_result($result);				 
			   if ($pos === false) {
			      return false;
			   } else return true;	  
	    } else return true; 
   }
 } 
 
 # COLUMNAS TABLA PREDIOS
 function get_column ($no_de_columna) {
    # 95 columnas, ultima columna es CQ		
		# (en el array aki abajo hay 10 codigos por fila)				
 		$columnas = array('cod_geo','cod_uv','cod_man','cod_pred','cod_blq','cod_piso','cod_apto','cod_proc','cod_pad','dir_tipo',
		                  'dir_nom','dir_num','tit_cant','tit_1pat','tit_1mat','tit_1nom1','tit_1nom2','tit_1ci','tit_1nit','tit_2pat',
											'tit_2mat','tit_2nom1','tit_2nom2','tit_2ci','dom_dpto','dom_ciu','dom_tipo','dom_nom','dom_num','der_num',
											'der_fech','adq_sdoc','adq_modo','adq_doc','adq_fech','tan_pat','tan_mat','tan_nom1','tan_nom2','tan_ci',
											'tan_modo','tan_doc','tan_fech_ini','tan_mont_usd','val_lib','ben_tipo','ben_ano','ben_por','via_mat','ter_ubi',
											'ser_alc','ser_agu','ser_luz','ser_tel','ser_cab','ser_gas','ter_form','ter_fren','ter_fond','ter_nofr',
											'ter_san','ter_topo','ter_mur','cnx_alc','cnx_agu','cnx_luz','cnx_tel','cnx_cab','cnx_gas','ter_eesp',
											'esp_aac','esp_tas','esp_tae','esp_ser','esp_gar','esp_dep','mej_lav','mej_par','mej_hor','mej_pis',
											'mej_otr','ter_uso','ter_ace','soe_est','soe_ocu','soe_civ','soe_ing','soe_muj','soe_hom','ctr_x',
											'ctr_y','ctr_enc','ctr_sup','ctr_fech','ctr_obs');
		return $columnas[$no_de_columna];								   
 }
 
 # COLUMNAS TABLA EDIFICACIONES 
 function get_column_edif ($no_de_columna) {
$columnas = array('cod_geo','cod_uv','cod_man','cod_pred','edi_num','edi_piso','edi_ubi','edi_tipo','edi_edo','edi_ano','edi_cim','edi_est','edi_mur','edi_acab','edi_rvin','edi_rvex','edi_rvba','edi_rvco',
			'edi_cest','edi_ctec','edi_ciel','edi_coc','edi_ban','edi_carp','edi_elec','edi_blo','edi_val','edi_esp');	
		return $columnas[$no_de_columna];								   
 } 

 # COLUMNAS TABLA ACTIVIDAD ECONOMICA
 function get_column_acteco ($no_de_columna) {
    # 19 columnas, ultima columna es S (10 codigos por fila en el array)
 		$columnas = array('cod_geo','cod_uv','cod_man','cod_lote','act_pat','act_rub','act_raz','act_nit','act_tel','act_fech',
		                  'act_sup','act_1pat','act_1mat','act_1nom1','act_1nom2','act_1ci','act_dpto','act_ciu','act_dir');	
		return $columnas[$no_de_columna];								   
 }  
 
 # COLUMNAS TABLA VEHICULOS
 function get_column_vehic ($no_de_columna) {
    # 27 columnas, ultima columna es AA (10 codigos por fila en el array)
 		$columnas = array('cod_geo','cod_uv','cod_man','cod_lote','veh_1pat','veh_1mat','veh_1nom1','veh_1nom2','veh_1ci','veh_plc',
		                  'veh_pol','veh_mrc','veh_mod','veh_col','veh_ano','veh_cls','veh_proc','veh_cc','veh_serv','veh_dob',
		                  'veh_tur','veh_pta','veh_tn','veh_plz','veh_chs','veh_car','veh_val');	
		return $columnas[$no_de_columna];								   
 }  
 
 # ENCONTRAR COLUMNA EN CASO DE ERROR
 function name_column ($no_de_columna) {
 		$columnas = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		if ($no_de_columna < 26) {
		   return $columnas[$no_de_columna];   
		} else {
		   $primero = floor($no_de_columna/26);
			 $segundo = $no_de_columna -($primero * 26);
			 return $columnas[$primero-1].$columnas[$segundo];
		}   						   
 } 
 
?>