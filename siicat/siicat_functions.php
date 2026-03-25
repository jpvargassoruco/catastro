<?php
# error_reporting(E_ALL);


# LISTA DE FUNCIONES
# actualizar_tabla ($nombre_de_tabla, $gestion_nueva)
# change_date ($fecha)
# change_date_to_10char ($fecha)
# change_numero_to_8char ($numero)  
# check_cambios ($cod_geo,$cod_uv,$cod_man,$cod_pred,$cod_subl,$variable,$fecha)
# check_char($char)
# check_codcat($cod_cat)
# check_fecha($fecha,$dia_actual,$mes_actual,$ano_actual)   
# check_float($string)
# check_ip($ip)
# check_int($numero)
# check_session($session_id)
# check_string($string)
# check_usuario($user, $password)
# check_usuario_online($session_id)
# check_user_level($user_id)
# check_utm($xutm,$yutm)	
# destroy_session ($session_id)
# eliminate_null_from_string ($oldstring)
# generar_cod_pmc ($lugar_cat)
# get_abr ($col_nombre) 
# get_barrio ($cod_cat)
# get_ci_numeros($tit_ci)
# get_codcat($cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto)
# get_codcat_from_id_inmu($id_inmu) 
# get_codedi($cod_cat,$edi_num,$edi_piso)
# get_cod_uv_from_id_inmu ($id_inmu)
# get_cod_man_from_id_inmu ($id_inmu)
# get_cod_pred_from_id_inmu ($id_inmu)	
# get_cod_blq_from_id_inmu ($id_inmu)
# get_cod_piso_from_id_inmu ($id_inmu)
# get_cod_apto_from_id_inmu ($id_inmu)
# get_contrib_ci ($id_contrib)
# get_contrib_doc_exp ($con_ci)
# get_contrib_doc_num ($con_ci)
# get_contrib_doc_tipo ($con_ci)
# get_contrib_dom($id_contrib)
# get_contrib_id ($con_pat,$con_mat,$con_nom1,$con_nom2,$con_ci)
# get_contrib_id_new ()
# get_contrib_nombre($id_contrib)
# get_contrib_nombre_xid ($id_contrib)
# get_contrib_pmc ($id_contrib) {
# get_contrib_pmc_new()
# get_uv($cod_cat)
# get_man($cod_cat)
# get_pred($cod_cat)
# get_direccion_from_id_inmu ($id_inmu)
# get_edades($string,$edad_limite)
# get_factor ($cod_cat)
# get_id_inmu ($cod_geo,$cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto) 
# get_id_inmu_new () 
# get_id_predio ($cod_geo,$cod_uv,$cod_man,$cod_pred)
# get_id_predio_new ()
# get_linelen ($point_x1, $point_y1, $point_x2, $point_y2)
# get_material_de_via ($id_inmu)
# get_objectcode ($objeto)
# get_objectlinecode($objeto)
# get_patente_act_raz($id_patente)
# get_point_x ($point_x1, $point_y1, $point_x2, $point_y2, $porc_dist)
# get_point_y ($point_x1, $point_y1, $point_x2, $point_y2, $porc_dist)
# get_position4($point_x, $point_y, $centroid_x, $centroid_y)
# get_position8($point_x, $point_y, $centroid_x, $centroid_y, $xmin, $xmax, $ymin, $ymax) 
# get_predio_dir ($cod_geo,$cod_uv,$cod_man,$cod_pred)
# get_prop1_from_id_inmu ($id_inmu)
# get_prop2_from_id_inmu ($id_inmu)
# get_propx_from_id_inmu ($id_inmu)
# get_propietarios ($id_inmu) 
# get_rubro ($act_rub)
# get_strlen ($text)
# get_titular ($tit_1nom1,$tit_1nom2,$tit_1pat,$tit_1mat)
# get_userid ($session_id)
# get_username ($session_id)
# get_username2 ($user_id)
# get_uso ($id_inmu)
# get_vehcls ($veh_cls)
# get_zona ($id_inmu)
# get_zona_brujula ($cod_cat)
# imp_calidad_const($gestion,$line_media)
# imp_factor_deprec ($gestion, $edi_ano, $ano_actual)
# imp_factor_incl ($gestion, $ter_topo)
# imp_factor_serv ($gestion, $ser_agu, $ser_alc, $ser_luz, $ser_tel)
# imp_valorporm2_terr ($gestion, $zona, $via_mat)
# imp_getexen ($gestion, $avaluo_total)
# imp_getcoti ($fecha, $moneda)
# imp_tasa_taprufv ($fecha)
# imp_dias_de_mora ($fecha_venc[$j],$fecha)
# imp_multa_incum ($ufv_actual)
# monthconvert ($month)  
# numeros_a_letras ($numero)
# centavos ($numero)
# decimos ($numero)
# numeros ($numero)
# textconvert ($text)
# ucase ($text)

function actualizar_tabla ($nombre_de_tabla, $gestion_nueva, $factor_ufv) {
   $gestion_ant = $gestion_nueva-1;
   $sql="SELECT '' || array_to_string(ARRAY(SELECT '' || '' || c.column_name
         FROM information_schema.columns As c
         WHERE table_name = '$nombre_de_tabla'), ',') || ''";
	 $result = pg_query($sql);
	 $info = pg_fetch_array($result, null, PGSQL_ASSOC);
   $column_string = $info['?column?'];			 
   $sql="SELECT * FROM $nombre_de_tabla WHERE gestion = '$gestion_ant'";
	 $result = pg_query($sql);
	 if ($nombre_de_tabla == "imp_fact_zona") {
	    $round_value1 = 5;
			$round_value2 = 2;
	 }
	 if (($nombre_de_tabla == "imp_fact_servicios") OR ($nombre_de_tabla == "imp_fact_inclinacion"))  {
	    $round_value1 = $round_value2 = 2;
	 }	 
	  
	 if ($nombre_de_tabla == "imp_valua_viv_vf") {
	    $round_value1 = $round_value2 = 0;
	 }
	 if ($nombre_de_tabla == "imp_fact_deprec") {
	    $round_value1 = 0;
		$round_value2 = 3;
	 }	
   $i = $j = 0;
	 $value_string = "";
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) { 
			$start_string = true;
			$second_string = true;  
	    if ($nombre_de_tabla == "imp_fact_zona") {
	       $check_tabla_imp_fact_zona = true;
	    } else $check_tabla_imp_fact_zona = false;	
      foreach ($line as $col_value) {
			   if (strlen ($col_value) > 1) { 
				    if ($start_string) {
				       $col_value = $col_value+1;
				       $value_string = "'".$col_value."'";
						   $start_string = false;
				    } elseif ($second_string) {
						   $col_value = ROUND ($col_value * $factor_ufv,$round_value1);
						   $value_string = $value_string.",'".$col_value."'";
							 $second_string = false;			
						} elseif ($check_tabla_imp_fact_zona) {
               $value_string = $value_string.",'".$col_value."'";						   
						   $check_tabla_imp_fact_zona = false;					 
						} else {
						   $col_value = ROUND ($col_value * $factor_ufv,$round_value2);
						   $value_string = $value_string.",'".$col_value."'";
						}
				 } else $value_string = $value_string.",'".$col_value."'";		
	    }
#echo "VALUE_STRING: $value_string<br>"; 
			pg_query("INSERT INTO $nombre_de_tabla ($column_string) VALUES ($value_string)"); 
   }
	 if ($value_string == "") {
	    return 0;
	 } else {
	    return 1;
   }
}

function change_date ($fecha)
{
   $char = substr($fecha, 2, 1);
	 if ($char == "/") {
	    $dia_change = substr($fecha, 0, 2);  
	    $mes_change = substr($fecha, 3, 2);
	    $ano_change = substr($fecha, 6, 4);
			$fecha = $ano_change."-".$mes_change."-".$dia_change; 						 
	 } else {
	    $ano_change = substr($fecha, 0, 4);  
	    $mes_change = substr($fecha, 5, 2);
	    $dia_change = substr($fecha, 8, 2);
			$fecha = $dia_change."/".$mes_change."/".$ano_change; 	 
	 }
   return $fecha;
}


function get_fecha_plus_dias_habiles ($fecha,$dias_hab) {
   $i = 0;
	 while ($i < $dias_hab) {
	    $timestamp = strtotime($fecha.' + 1 day');
			$fecha = date('Y-m-d', $timestamp);
      $check = getdate($timestamp);
      if (($check['weekday'] == "Sunday") OR ($check['weekday'] == "Saturday")) {
	       $i--;	
      }
			$i++;
   } 
	 return $fecha;    
}


function change_date_to_10char ($fecha) 
{
   $stringlength = strlen($fecha);								 
   $i = $char_pos = 0;
	 $new_fecha = "";	  
	 while ($i <= strlen($fecha)) {	
      $char = substr($fecha, $i-1, 1);
			if ($char == "-") {
			   $separador = "-";
			}
			if ($char == "/") {
			   $separador = "/";
			}
			if (($char == "-") OR ($char == "/") OR ($i == strlen($fecha))) {
         $value = substr($fecha, $char_pos, $i-$char_pos);			   						  
#echo "Recortado: $value<br> \n";
         $value = (int)$value;  
         if ($value < 10) {
				    $value = "0".$value;
				 }
				 if ($new_fecha == "") {
				    $new_fecha = $value;    
				 } else $new_fecha = $new_fecha.$separador.$value;
				 $char_pos = $i;
			}  
			$i++;
   }
	 return $new_fecha;
}

function change_numero_to_8char ($numero)
{ 
   $numero = (int) $numero;
	 if ($numero < 10) {
	    $char = "0000000".$numero;
	 } elseif ($numero < 100) {
	    $char = "000000".$numero;								 
	 } elseif ($numero < 1000) {
	    $char = "00000".$numero;
	 } elseif ($numero < 10000) {
	    $char = "0000".$numero;
	 } elseif ($numero < 100000) {
	    $char = "000".$numero;
	 } elseif ($numero < 1000000) {
	    $char = "00".$numero;
	 } elseif ($numero < 10000000) {
	    $char = "0".$numero;
	 } else {
	    $char = $numero;	
	 }			
	 return $char;
}

function check_cambios ($cod_geo,$id_inmu,$variable,$fecha)
{
   $sql="SELECT fecha_cambio, variable, valor_ant FROM cambios 
	       WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' AND variable = '$variable' AND fecha_cambio >= '$fecha'
	       ORDER BY fecha_cambio ASC LIMIT 1";
   $check = pg_num_rows(pg_query($sql));
   if ($check > 0) {
      $result = pg_query($sql);
      $info = pg_fetch_array($result, null, PGSQL_ASSOC);
      $valor_ant = $info['valor_ant'];	
      pg_free_result($result);
			return $valor_ant;
   } else return -1;				
}

function check_char($char)
{
		#used in fes_load_shp.php line 112
		$allowed_char = array('±','‘','¡','©','³','º');							 
		$char_pos = 0;
		$charcheck = false;
		while ($char_pos < 6) {
       if ($char == $allowed_char[$char_pos]) {
			    return true;
					break;
			 } 	 	
	     $char_pos++; 
    }
		return false;
}

function check_codcat($cod_cat)
{ 
   global $max_strlen_uv;
   global $max_strlen_man;
   global $max_strlen_pred;
   $test1 = substr(trim ($cod_cat),$max_strlen_uv,1);
	 $pos = $max_strlen_uv + $max_strlen_man + 1;
   $test2 = substr(trim ($cod_cat),$pos,1);
	 $cod_length = $max_strlen_uv + 1 + $max_strlen_man + 1 + $max_strlen_pred;	
	 $test3 = strlen(trim ($cod_cat));
   $test4 = substr(trim ($cod_cat),0,$max_strlen_uv);
	 $pos1 = $max_strlen_uv + 1;
   $test5 = substr(trim ($cod_cat),$pos1,$max_strlen_man);
	 $pos2 = $max_strlen_uv + 1 + $max_strlen_man + 1;	
   $test6 = substr(trim ($cod_cat),$pos2,$max_strlen_pred);		 
   $test_uv = (int) substr(trim ($cod_cat),0,$max_strlen_uv);
   $test_man = (int) substr(trim ($cod_cat),$pos1,$max_strlen_man);	
   $test_pred = (int) substr(trim ($cod_cat),$pos2,$max_strlen_pred);
	 if ($max_strlen_uv == 1) {
	    $max_uv = 9;
	 } elseif ($max_strlen_uv == 2) {
	    $max_uv = 99;
	 } elseif ($max_strlen_uv == 3) {
	    $max_uv = 999;
	 } else {
	    $max_uv = 9999;
	 }
	 if ($max_strlen_man == 1) {
	    $max_man = 9;
	 } elseif ($max_strlen_man == 2) {
	    $max_man = 99;
	 } elseif ($max_strlen_man == 3) {
	    $max_man = 999;
	 } else {
	    $max_man = 9999;
	 }
	 if ($max_strlen_pred == 1) {
	    $max_pred = 9;
	 } elseif ($max_strlen_pred == 2) {
	    $max_pred = 99;
	 } elseif ($max_strlen_pred == 3) {
	    $max_pred = 999;
	 } else {
	    $max_pred = 9999;
	 }	 	   	  	  	  
	 if ($test1 != "-") {
	    return false;
	 } elseif ($test2 != "-") {
	    return false;
	 } elseif ($test3 != $cod_length) {
	    return false;
	 } elseif ((!check_int ($test4)) OR (!check_int ($test5)) OR (!check_int ($test6))) {
	    return false;
	 } elseif (($test_uv < 1) OR ($test_uv > $max_uv)) {
	    return false;
	 } elseif (($test_man < 1) OR ($test_man > $max_man)) {
	    return false;
	 } elseif (($test_pred < 1) OR ($test_pred > $max_pred)) {
	    return false;
	 } else {
	    return true;
   }
}
 
function check_fecha($fecha,$dia_actual,$mes_actual,$ano_actual)
{
   #AVERIGUAR SEPARADOR
	 $separador_encontrado = false;
   $pos1 = strpos($fecha,"/");
   $pos2 = strpos($fecha,"-");
#echo "POS de / es $pos1, POS de - es $pos2. \n";		 			                                                   
   if (($pos1 === false) AND ($pos2 === false)) {  #---> USAR CUANDO EL RESULTADO ES VACIO
			return false;	
	 } elseif (($pos1 > 0) AND ($pos2 > 0)) {               
      return false;		  
	 } elseif ($pos2 === false) {               
      $separador = "/";
			$pos = $pos1;
			$separador_encontrado = true;		
	 } else {		
		  $separador = "-";
			$pos = $pos2;
			$separador_encontrado = true;
	 }
# echo "El separador es: $separador.\n";	
   if ($separador_encontrado) {
	    if ($pos == 4) {
			   $formato = "AMD";
			} else $formato = "DMA";
		  $rellenado = $mes_rellenado = false;
		  $dia = $mes = $ano = "";
		  $i = 1;
		  while ($i <= strlen($fecha)) {	
			   $char = substr($fecha, $i-1, 1);
			   if ((check_int($char)) AND (!$rellenado)) {
			      if ($formato == "AMD") {
						   $ano = $ano.$char;
						} else $dia = $dia.$char;
			   } elseif (($char == $separador) AND (!$rellenado)) {
			      $rellenado = true;   
			   } elseif ((check_int($char)) AND (!$mes_rellenado)) { 
			      $mes = $mes.$char;	
			   } elseif (($char == $separador) AND (!$mes_rellenado)) {
			      $mes_rellenado = true;  
			   } elseif (check_int($char)) { 
			      if ($formato == "AMD") {
						   $dia = $dia.$char;
						} else $ano = $ano.$char;	
			   } else return false;
			   $i++; 
      }
# echo "DIA: $dia, MES: $mes, ANO: $ano\n";				
      if (($dia < 1) OR ($dia > 31) OR ($mes < 1) OR ($mes > 12) OR ($ano < 1900) OR ($ano > $ano_actual)
			 OR (($ano == $ano_actual) AND ($mes > $mes_actual)) OR (($ano == $ano_actual) AND ($mes == $mes_actual) AND ($dia > $dia_actual)) ) { 
         return false;
      } else {
        if (($mes == 2) AND  ($dia > 29)) {
			     return false;
			  } elseif ((($mes == 4) OR ($mes == 6) OR ($mes == 9) OR ($mes == 11)) AND  ($dia == 31)) {
			     return false;
			  } else return true;
      }
   } 
}

 function check_float($string)
 {
    if ($string === "") {
		   return false;
		   break;		
		}
 		$i = $z = 1;
    #$x = $j = $stringinit = 0;
		$punto = 0; 
		$allowed = array('0','1','2','3','4','5','6','7','8','9','.');
	  $stringlength = strlen($string);								 
#echo "Stringlength: $stringlength<br /> \n"; 	  
		while ($i <= strlen($string)) {	
			 $char = substr($string, $i-1, 1);
       if ($char == ".") {					 
			    $punto++;
			 } 			 
#echo "El char no $i es un $char \n";
			 $char_pos = 0;
			 $charcheck = false;
			 while ($char_pos < 11) {
           if ($char == $allowed[$char_pos]) {
							$charcheck = true;
					 } 	 	
					 
	         $char_pos++; 
       }
			 if (!$charcheck) {
			    return false;
					break;
			 }
			 $i++; 
    }
		if ($punto > 1) {		
       return false;
			 break;		
		}
#echo "Todo OK<br /> \n"; 	 	
		return true;
 }

 function check_ip($ip)
 {
		$sql="SELECT gid FROM fes_ips WHERE ip='$ip'";
    $check_ip = pg_num_rows(pg_query($sql)); 
    if ($check_ip == 1 ) { 
       return true;
    } else {
      return false;
    } 
 }

 function check_int($numero)
 {
 		if ($numero === "") {
		   return false;
		   break;		
		}
		$i = $z = 1;
		$allowed = array('0','1','2','3','4','5','6','7','8','9');
	  $stringlength = strlen($numero);								 	  
		while ($i <= strlen($numero)) {	
			 $char = substr($numero, $i-1, 1);
			 $char_pos = 0;
			 $charcheck = false;
			 while ($char_pos < 10) {
           if ($char == $allowed[$char_pos]) {
							$charcheck = true;
					 } 	 	
	         $char_pos++; 
       }
			 if (!$charcheck) {
			    return false;
					break;
			 }
			 $i++; 
    } 	
		return true;
 }
 
 function check_numeros_inc_coma($string)
 {
 		$i = $z = 1;
    #$x = $j = $stringinit = 0;
		#$initcheck = true; 
		$allowed = array('0','1','2','3','4','5','6','7','8','9',',');
	  $stringlength = strlen($string);								 
	  #echo "Stringlength: $stringlength<br /> \n"; 	  
		while ($i <= strlen($string)) {	
			 $char = substr($string, $i-1, 1);
		   #echo "El char no $i es un $char \n";
			 $char_pos = 0;
			 $charcheck = false;
			 while ($char_pos < 11) {
           if ($char == $allowed[$char_pos]) {
							$charcheck = true;
					 } 	 	
	         $char_pos++; 
       }
			 if (!$charcheck) {
			    return false;
					break;
			 }
			 $i++; 
    }
#echo "Todo OK<br /> \n"; 	 	
		return true;
 }
  
  function check_session($session_id)
 {
		$sql="SELECT nivel FROM usuarios WHERE session_id ='$session_id'";
    $check_sessionid = pg_num_rows(pg_query($sql)); 
    if ($check_sessionid == 1 ) {
 			 $result=pg_query($sql);
       $nivel_from_table = pg_fetch_array($result, null, PGSQL_ASSOC);
       $nivel = $nivel_from_table['nivel'];
	     pg_free_result($result);		    
       return $nivel;
    } else {
      return false;
    } 
 }
 
 function check_string($string)
 {
 		$i = $z = 1;
    #$x = $j = $stringinit = 0;
		#$initcheck = true; 
		$allowed = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','Ñ','O','P','Q','R','S','T','U','V','W','X','Y','Z',
		                 'a','b','c','d','e','f','g','h','i','j','k','l','m','n','ñ','o','p','q','r','s','t','u','v','w','x','y','z','-','_','+','.','/');
	  $stringlength = strlen($string);								 
	  #echo "Stringlength: $stringlength<br /> \n"; 	  
		while ($i <= strlen($string)) {	
			 $char = substr($string, $i-1, 1);
		   #echo "El char no $i es un $char \n";
			 $char_pos = 0;
			 $charcheck = false;
			 while ($char_pos < 69) {
           if ($char == $allowed[$char_pos]) {
							$charcheck = true;
					 } 	 	
	         $char_pos++; 
       }
			 if (!$charcheck) {
			    return false;
					break;
			 }
			 $i++; 
    }
		#echo "Todo OK<br /> \n"; 	 	
		return true;
 }
 
 function check_usuario($user, $password)
 {  
	  $md5_password = md5($password);
		$sql="SELECT nivel FROM usuarios WHERE user_id='$user' AND password='$md5_password'";
    $check_user = pg_num_rows(pg_query($sql)); 
    if ($check_user == 1 ) {
 			 $result=pg_query($sql);
       $nivel_from_table = pg_fetch_array($result, null, PGSQL_ASSOC);
       $nivel = $nivel_from_table['nivel'];
	     pg_free_result($result);		    
       return $nivel;
    } else {
      return 0;
    } 
 }
 
/*  function check_usuario_online($session_id)
 {
		$sql="SELECT * FROM usuarios WHERE session_id ='$session_id' AND online='1'";
    $check_user_online = pg_num_rows(pg_query($sql)); 
    if ($check_user_online == 1 ) { 
       return true;
    } else {
      return false;
    } 
 }*/

 function check_user_level($user_id)
 {
		$sql="SELECT nivel FROM usuarios WHERE user_id ='$user_id'";
 		$result=pg_query($sql);
    $nivel_from_table = pg_fetch_array($result, null, PGSQL_ASSOC);
    $nivel = $nivel_from_table['nivel'];
	  pg_free_result($result);		
    return $nivel; 
 }
 
 function check_utm($xutm,$yutm) { 
     global $minimo_permitido_x;
     global $maximo_permitido_x;
		 global $minimo_permitido_y;
     global $maximo_permitido_y;		                          
     if(empty($xutm) OR empty($yutm) OR ($xutm < $minimo_permitido_x) OR ($xutm > $maximo_permitido_x) OR ($yutm < $minimo_permitido_y) OR ($yutm > $maximo_permitido_y)) {
        return false;
		 }
		 else {   
		 return true;
		 }    
 }	
 
 function create_session ($user_id,$ip) {                         
    $date = getdate();
    $session_time = $date['0'];		
		$fecha =$date['year']."-".$date['mon']."-".$date['mday'];
    if ($date['hours'] > 0) {
       $hours = $date['hours']-1;
    } else {
       $hours = 23;
    }
		$hora = $hours.":".$date['minutes'].":".$date['seconds'];			
		$session_id = md5($user_id."@".$fecha."@".$hora);
		$sql="SELECT * FROM usuarios WHERE ip = '$ip'";
    $check = pg_num_rows(pg_query($sql));
		if ($check > 0) {
       pg_query("UPDATE usuarios SET online = '0', ip = '', session_time = '0', session_id = '' 
			           WHERE ip = '$ip'");
		}		
    pg_query("UPDATE usuarios SET online = '1', ip = '$ip', session_time = '$session_time', session_id = '$session_id'  
		             WHERE user_id = '$user_id'");		
		return $session_id;
 }	 
 
 function destroy_session ($session_id) {                         
    pg_query("UPDATE usuarios SET online = '0', ip = '', session_time = '0', session_id = ''  WHERE session_id = '$session_id'");
		return 0;
 }	 
 
 function eliminate_null_from_string ($oldstring) {
    $i = 0;
    $newstring = ""; 
    while ($i <= strlen($oldstring)) {
       $char = substr($oldstring, $i, 1);
	     if ($char != " ") {
          $newstring = $newstring.$char;
	     } 
	     $i++;   
    } #end_of_while	
 	  return $newstring;
 }

 function generar_cod_pmc ($lugar_cat)
 {
   if ($lugar_cat == "SC") {
	    $cod_pmc = 1;
	 } elseif ($lugar_cat == "BR") {
	    $cod_pmc = 20000;
			$codigo = false;
			while (!$codigo) {
         $sql="SELECT cod_pmc FROM info_predio WHERE cod_pmc = '$cod_pmc'";
         $check_pmc = pg_num_rows(pg_query($sql));
			   if ($check_pmc == 0) {
				    $codigo = true; 
				 } else {
				    $cod_pmc++;
				 }  			
      } 
	 } elseif ($lugar_cat == "SF") {
	    $cod_pmc = 40000;	
	 } elseif ($lugar_cat == "AF") {
	    $cod_pmc = 60000;
	 } elseif ($lugar_cat == "VI") {
	    $cod_pmc = 80000;	 
	 }
	 return $cod_pmc;	 	  
 }
 
 function get_abr ($col_nombre) 
 {
    # Función devuelve las abbreviaciones de la tabla info_predio_permitido
    $sql="SELECT num, permitido FROM info_permitido WHERE col_nombre = '$col_nombre'";
    $result = pg_query($sql);
    $info_tdv = pg_fetch_array($result, null, PGSQL_ASSOC);
		$valores = $info_tdv['permitido'];
    $aaa = array();
    $i = 0;
    $j = 0;
    $x = 0; 
    while ($i <= strlen($valores)) {
        $char = substr($valores, $i, 1);	
	      if (($char == ',') OR ($i == strlen($valores))) {
           $aaa[$x] = substr($valores,$j,$i-$j);	
		       $j=$i+1;			
			     $x++;
	      } 
	      $i++;   
    } #end_of_while
		return $aaa;
 }

 function get_barrio ($id_inmu) 
 {
      $cod_uv = get_cod_uv_from_id_inmu($id_inmu);
		  $cod_man = get_cod_man_from_id_inmu($id_inmu);
		  $cod_pred = get_cod_pred_from_id_inmu($id_inmu);
      $sql="SELECT nombre, ST_within((SELECT centroid(the_geom) FROM predios WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'),the_geom) FROM barrios";
      $i = 0;
			$result = pg_query($sql);
			$barrio_value = false;
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {				 	 			           
         foreach ($line as $col_value) {			 
				    if ($i == 0) {
						   $temp_barrio = trim ($col_value);
						} else {
				       if ($col_value == "t") {
							    $barrio = $temp_barrio;
									$barrio_value = true;
						   }
							 $i = -1;
						}
						$i++;
				 }
			}	
      pg_free_result($result); 		
 			if (!$barrio_value) {
				 return 0;
			} else {	
			   $barrio = strtoupper (utf8_decode ($barrio));
			   return $barrio;
			}
 }
  
 function get_codcat($cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto) {	
    GLOBAL $max_strlen_uv;    # U.V./Distrito
    GLOBAL $max_strlen_man;    # Manzano
    GLOBAL $max_strlen_pred;    # Predio
    GLOBAL $max_strlen_blq;    # Bloque/Sector
    GLOBAL $max_strlen_piso;    # Piso
    GLOBAL $max_strlen_apto;    # Apartamento
    ### U.V./DISTRITO ###
    if ($max_strlen_uv == 2) {
		   if (strlen($cod_uv) == 1) {
		      $cod_cat = "0".$cod_uv."-";		 
		   } else $cod_cat = $cod_uv."-";
    } elseif ($max_strlen_uv == 3) {
		   if (strlen($cod_uv) == 1) {
		      $cod_cat = "00".$cod_uv."-";
		   } elseif (strlen($cod_uv) == 2) {
		      $cod_cat = "0".$cod_uv."-";				 
		   } else $cod_cat = $cod_uv."-";
    } elseif ($max_strlen_uv == 4) {
		   if (strlen($cod_uv) == 1) {
		      $cod_cat = "000".$cod_uv."-";
		   } elseif (strlen($cod_uv) == 2) {
		      $cod_cat = "00".$cod_uv."-";		
		   } elseif (strlen($cod_uv) == 3) {
		      $cod_cat = "0".$cod_uv."-";									 
		   } else $cod_cat = $cod_uv."-";
		}			 			 
		### MANZANO ###
    if ($max_strlen_man == 2) {
		   if (strlen($cod_man) == 1) {
		      $cod_cat = $cod_cat."0".$cod_man."-";		 
		   } else $cod_cat = $cod_cat.$cod_man."-";
    } elseif ($max_strlen_man == 3) {
		   if (strlen($cod_man) == 1) {
		      $cod_cat = $cod_cat."00".$cod_man."-";
		   } elseif (strlen($cod_man) == 2) {
		      $cod_cat = $cod_cat."0".$cod_man."-";				 
		   } else $cod_cat = $cod_cat.$cod_man."-";
    } elseif ($max_strlen_man == 4) {
		   if (strlen($cod_man) == 1) {
		      $cod_cat = $cod_cat."000".$cod_man."-";
		   } elseif (strlen($cod_man) == 2) {
		      $cod_cat = $cod_cat."00".$cod_man."-";		
		   } elseif (strlen($cod_man) == 3) {
		      $cod_cat = $cod_cat."0".$cod_man."-";									 
		   } else $cod_cat =$cod_cat.$cod_man."-";
		}			
		### PREDIO ###
    if ($max_strlen_pred == 2) {
		   if (strlen($cod_pred) == 1) {
		      $cod_cat = $cod_cat."0".$cod_pred;		 
		   } else $cod_cat = $cod_cat.$cod_pred;
    } elseif ($max_strlen_pred == 3) {
		   if (strlen($cod_pred) == 1) {
		      $cod_cat = $cod_cat."00".$cod_pred;
		   } elseif (strlen($cod_pred) == 2) {
		      $cod_cat = $cod_cat."0".$cod_pred;				 
		   } else $cod_cat = $cod_cat.$cod_pred;
    } elseif ($max_strlen_pred == 4) {
		   if (strlen($cod_pred) == 1) {
		      $cod_cat = $cod_cat."000".$cod_pred;
		   } elseif (strlen($cod_pred) == 2) {
		      $cod_cat = $cod_cat."00".$cod_pred;		
		   } elseif (strlen($cod_pred) == 3) {
		      $cod_cat = $cod_cat."0".$cod_pred;									 
		   } else $cod_cat = $cod_cat.$cod_pred;
		}
	  ### EN CASO DE P.H. O CONDOMINIO ###			
		if (($cod_blq === "") OR ($cod_blq == "0") OR ($cod_blq == "00") OR ($cod_blq == "000") OR ($cod_blq == "0000")) {
		   return $cod_cat;
		} else {
		   ### BLOQUE ###
       if ($max_strlen_blq == 2) {
		      if (strlen($cod_blq) == 1) {
		         $cod_cat = $cod_cat."-0".$cod_blq."-";		 
		      } else $cod_cat = $cod_cat."-".$cod_blq."-";
       } elseif ($max_strlen_blq == 3) {
		      if (strlen($cod_blq) == 1) {
		         $cod_cat = $cod_cat."-00".$cod_blq."-";
		      } elseif (strlen($cod_blq) == 2) {
		         $cod_cat = $cod_cat."-0".$cod_blq."-";				 
		      } else $cod_cat = $cod_cat."-".$cod_blq."-";
       } elseif ($max_strlen_blq == 4) {
		      if (strlen($cod_blq) == 1) {
		        $cod_cat = $cod_cat."-000".$cod_blq."-";
		      } elseif (strlen($cod_blq) == 2) {
		         $cod_cat = $cod_cat."-00".$cod_blq."-";		
		      } elseif (strlen($cod_blq) == 3) {
		         $cod_cat = $cod_cat."-0".$cod_blq."-";									 
		      } else $cod_cat = $cod_cat."-".$cod_blq."-";
		   }
		   ### PISO ###
       if ($max_strlen_piso == 2) {
			    if ($cod_piso === "") {
			       $cod_cat = $cod_cat."00-";
		      } elseif (strlen($cod_piso) == 1) {
		         $cod_cat = $cod_cat."0".$cod_piso."-";		 
		      } else $cod_cat = $cod_cat.$cod_piso."-";
       } elseif ($max_strlen_piso == 3) {
			    if ($cod_piso === "") {
			       $cod_cat = $cod_cat."000-";			 
		      } elseif (strlen($cod_piso) == 1) {
		         $cod_cat = $cod_cat."00".$cod_piso."-";
		      } elseif (strlen($cod_piso) == 2) {
		         $cod_cat = $cod_cat."0".$cod_piso."-";				 
		      } else $cod_cat = $cod_cat.$cod_piso."-";
       } elseif ($max_strlen_piso == 4) {
			    if ($cod_piso === "") {
			       $cod_cat = $cod_cat."0000-";			 
		      } elseif (strlen($cod_piso) == 1) {
		         $cod_cat = $cod_cat."000".$cod_piso."-";
		      } elseif (strlen($cod_piso) == 2) {
		         $cod_cat = $cod_cat."00".$cod_piso."-";		
		      } elseif (strlen($cod_piso) == 3) {
		         $cod_cat = $cod_cat."0".$cod_piso."-";									 
		      } else $cod_cat = $cod_cat.$cod_piso."-";
		   }
		   ### APARTAMENTO ###		 
       if ($max_strlen_apto == 2) {
			    if ($cod_apto === "") {
			       $cod_cat = $cod_cat."00-";			 
		      } elseif (strlen($cod_apto) == 1) {
		         $cod_cat = $cod_cat."0".$cod_apto;		 
		      } else $cod_cat = $cod_cat.$cod_apto;
       } elseif ($max_strlen_apto == 3) {
			    if ($cod_apto === "") {
			       $cod_cat = $cod_cat."000-";				 
		      } elseif (strlen($cod_apto) == 1) {
		         $cod_cat = $cod_cat."00".$cod_apto;
		      } elseif (strlen($cod_apto) == 2) {
		         $cod_cat = $cod_cat."0".$cod_apto;				 
		      } else $cod_cat = $cod_cat.$cod_apto;
       } elseif ($max_strlen_apto == 4) {
			    if ($cod_apto === "") {
			       $cod_cat = $cod_cat."0000-";				 
		      } elseif (strlen($cod_apto) == 1) {
		        $cod_cat = $cod_cat."000".$cod_apto;
		      } elseif (strlen($cod_apto) == 2) {
		         $cod_cat = $cod_cat."00".$cod_apto;		
		      } elseif (strlen($cod_apto) == 3) {
		         $cod_cat = $cod_cat."0".$cod_apto;									 
		      } else $cod_cat = $cod_cat.$cod_apto;
		   }	
		   return $cod_cat;				 	 
		}
 }
 
 function get_codcat_from_id_inmu($id_inmu) {
    if (($id_inmu == 0) OR ($id_inmu == NULL)) {
       return "-";
		} else {
		   $cod_uv = get_cod_uv_from_id_inmu ($id_inmu); 
	     $cod_man = get_cod_man_from_id_inmu($id_inmu);
		   $cod_pred = get_cod_pred_from_id_inmu ($id_inmu);
	     $cod_blq = get_cod_blq_from_id_inmu ($id_inmu);
		   $cod_piso = get_cod_piso_from_id_inmu($id_inmu);
		   $cod_apto = get_cod_apto_from_id_inmu ($id_inmu);
		   $cod_cat = get_codcat($cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto);
		   return $cod_cat;
    }
 }	 
 
 function get_codedi($cod_cat,$edi_num,$edi_piso) {
    $cod_edi = $cod_cat;
		if ($edi_num < 10) {
		   $cod_edi = $cod_edi."-0".$edi_num."-";
		} else $cod_edi = $cod_edi."-".$edi_num."-";	
		if ($edi_piso < 10) {
		   $cod_edi = $cod_edi."0".$edi_piso;
		} else $cod_edi = $cod_edi.$edi_piso;
		return $cod_edi;
 }

 function get_cod_geo_from_id_inmu ($id_inmu) {
    if ($id_inmu == "0") {
		   return -1;
		} else {
       $sql="SELECT cod_geo FROM info_inmu WHERE id_inmu = '$id_inmu'";
       $result = pg_query($sql);
       $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $cod_geo = $info['cod_geo'];
       $cod_geo = trim($cod_geo);
       pg_free_result($result); 
       return $cod_geo;
    } 
 }


 function get_cod_uv_from_id_inmu ($id_inmu) {
    if ($id_inmu == "0") {
		   return -1;
		} else {
       $sql="SELECT cod_uv FROM info_inmu WHERE id_inmu = '$id_inmu'";
       $result = pg_query($sql);
       $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $cod_uv = $info['cod_uv'];
       pg_free_result($result); 
       return $cod_uv;
    } 
 }
 
 function get_cod_man_from_id_inmu ($id_inmu) {
    if ($id_inmu == "0") {
		   return -1;
		} else {
       $sql="SELECT cod_man FROM info_inmu WHERE id_inmu = '$id_inmu'";
       $result = pg_query($sql);
       $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $cod_man = $info['cod_man'];
       pg_free_result($result); 
       return $cod_man;
    } 
 }
 
 function get_cod_pred_from_id_inmu ($id_inmu) {
    if ($id_inmu == "0") {
		   return -1;
		} else {
       $sql="SELECT cod_pred FROM info_inmu WHERE id_inmu = '$id_inmu'";
       $result = pg_query($sql);
       $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $cod_pred = $info['cod_pred'];
       pg_free_result($result); 
       return $cod_pred;
    } 
 } 

 function get_cod_blq_from_id_inmu ($id_inmu) {
    if ($id_inmu == "0") {
		   return -1;
		} else {
       $sql="SELECT cod_blq FROM info_inmu WHERE id_inmu = '$id_inmu'";
       $result = pg_query($sql);
       $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $cod_blq = $info['cod_blq'];
       pg_free_result($result); 
       return $cod_blq;
    } 
 }
 
 function get_cod_piso_from_id_inmu ($id_inmu) {
    if ($id_inmu == "0") {
		   return -1;
		} else {
       $sql="SELECT cod_piso FROM info_inmu WHERE id_inmu = '$id_inmu'";
       $result = pg_query($sql);
       $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $cod_piso = $info['cod_piso'];
       pg_free_result($result); 
       return $cod_piso;
    } 
 } 
 
 function get_cod_apto_from_id_inmu ($id_inmu) {
    if ($id_inmu == "0") {
		   return -1;
		} else {
       $sql="SELECT cod_apto FROM info_inmu WHERE id_inmu = '$id_inmu'";
       $result = pg_query($sql);
       $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $cod_apto = $info['cod_apto'];
       pg_free_result($result); 
       return $cod_apto;
    } 
 } 
 
 function get_contrib_doc_exp ($con_ci) { 
   # $doc_num = get_ci_numeros ($con_ci); 
    $stringlength = strlen(trim($con_ci));
		$exp2 = substr($con_ci, $stringlength-2, 2);
		$exp3 = substr($con_ci, $stringlength-3, 3);
		if (check_value('doc_exp',$exp2)) {
       return $exp2;
		} elseif (check_value('dom_dpto',$exp3)) {
		   if ($exp3 == "BEN") { $exp3 = "BN"; }
		   if ($exp3 == "CBB") { $exp3 = "CB"; }
		   if ($exp3 == "CHU") { $exp3 = "CH"; }
		   if ($exp3 == "LPZ") { $exp3 = "LP"; }
		   if ($exp3 == "ORU") { $exp3 = "OR"; }
		   if ($exp3 == "PAN") { $exp3 = "PD"; }
		   if ($exp3 == "POT") { $exp3 = "PT"; }
		   if ($exp3 == "SCZ") { $exp3 = "SC"; }
		   if ($exp3 == "TAR") { $exp3 = "TJ"; }
		   if ($exp3 == "EXT") { $exp3 = "EX"; }			 			 			 			 			 			 			 
       return $exp3;
    } else {
		   return NULL;
		}
 }

function get_contrib_ci ($id_contrib) {

	if (($id_contrib == "0") OR ($id_contrib == '') OR ($id_contrib == NULL)) {
		return "-";
		} else {
		$sql="SELECT doc_tipo, doc_num, doc_exp FROM contribuyentes WHERE id_contrib = '$id_contrib'";
		$result = pg_query($sql);
		$info = pg_fetch_array($result, null, PGSQL_ASSOC);
		$doc_tipo = $info['doc_tipo'];
		$doc_num = $info['doc_num'];
		$doc_exp = $info['doc_exp'];			 			 
		pg_free_result($result);
		$contrib_ci = trim($doc_tipo." ".$doc_num." ".$doc_exp);
		if ($contrib_ci == "") {
			$contrib_ci = "-";
		}
		return $contrib_ci;
	} 
} 
 
function get_contrib_doc_num ($con_ci) { 
	$i = 0;
	$num_string = "";
	while ($i <= strlen($con_ci)) {
	 $char = substr($con_ci, $i, 1);	
		 if (check_int ($char)) {
		    $num_string = $num_string.$char;
		 }
	  $i++;   
	} #end_of_while
	$num_string = trim($num_string);
	return $num_string;
}
 
 function get_contrib_doc_tipo ($con_ci) { 
    $stringlength = strlen(trim($con_ci));
		$sub2 = substr($con_ci, 0, 2);
		$sub3 = substr($con_ci, 0, 3);
		$sub4 = substr($con_ci, 0, 4);
		$sub5 = substr($con_ci, 4, 1);
		$sub_fin2	= substr($con_ci, $stringlength-2, 2);	
		if (($sub2 == "CE") OR ($sub2 == "CI")) {
		   return $sub2;
		} elseif (($sub3 == "PAS") OR ($sub3 == "RUN")) {
		   return $sub3;
		} elseif ((check_int ($sub4)) AND ($sub5 == "-")) {
		   return "RUN";
		} elseif ((check_value('doc_exp',$sub_fin2)) AND ($stringlength < 11)) {
		   return "CI";			 
    } else {
		   return NULL;
		}
 } 
 
 function get_contrib_dom($id_contrib) { 
 		if (($id_contrib == '0') OR ($id_contrib == '') OR ($id_contrib === NULL)) {
		   return "-";   
    } else {
       $sql="SELECT dom_tipo, dom_nom, dom_num FROM contribuyentes WHERE id_contrib = '$id_contrib'";
       $result = pg_query($sql);
       $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $dom_tipo = $info['dom_tipo'];
       $dom_nom = utf8_decode($info['dom_nom']);
       $dom_num = $info['dom_num'];
       pg_free_result($result); 
       $domicilio = $dom_tipo." ".$dom_nom." ".$dom_num;	
       return $domicilio;
    }
 }

 function get_contrib_id ($con_pat,$con_mat,$con_nom1,$con_nom2,$doc_num) {
    #$doc_num = get_contrib_doc_num ($con_ci);
		if (($doc_num == "") OR ($doc_num == "0") OR ($doc_num === NULL)) {
       $sql="SELECT id_contrib FROM contribuyentes WHERE con_pat = '$con_pat' AND con_mat = '$con_mat' AND con_nom1 = '$con_nom1' AND con_nom2 = '$con_nom2'";
    } else {
       $sql="SELECT id_contrib FROM contribuyentes WHERE con_pat = '$con_pat' AND con_mat = '$con_mat' AND ((con_nom1 = '$con_nom1' AND con_nom2 = '$con_nom2') OR (doc_num = '$doc_num'))";		
		}
#echo "Primer SQL: $sql<br />";	
    $check = pg_num_rows(pg_query($sql));										
		if ($check == 1) {
       $result=pg_query($sql);
		   $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $id_contrib = $info['id_contrib'];
#echo "Contribuyente encontrado en la primera: $id_contrib<br />";			 
       pg_free_result($result);
		} else {	
		   if (($doc_num == "") OR ($doc_num == "0") OR ($doc_num === NULL)) {		
		      $sql="SELECT id_contrib FROM contribuyentes WHERE con_pat ~* '$con_pat' AND con_mat ~* '$con_mat' AND con_nom1 ~* '$con_nom1' AND con_nom2 ~* '$con_nom2'";
		   } else {
			    $sql="SELECT id_contrib FROM contribuyentes WHERE con_pat ~* '$con_pat' AND con_mat ~* '$con_mat' AND ((con_nom1 ~* '$con_nom1' AND con_nom2 ~* '$con_nom2') OR (doc_num ~* '$doc_num'))";
       }
#echo "Segundo SQL: $sql<br />";
       $check = pg_num_rows(pg_query($sql)); 
			 if ($check == 1) {
          $result=pg_query($sql);
		      $info = pg_fetch_array($result, null, PGSQL_ASSOC);
          $id_contrib = $info['id_contrib'];
#echo "Contribuyente encontrado en la segunda: $id_contrib<br />";					
          pg_free_result($result);
			 } else {
          $id_contrib = 0;			 
#echo "Contribuyente NO encontrado: $check resultados<br />";	
			 }	
		}
		return $id_contrib;	 
 }
 
 function get_contrib_id_new() {
    $sql="SELECT id_contrib FROM contribuyentes ORDER BY id_contrib DESC LIMIT 1";
    $check = pg_num_rows(pg_query($sql));
	  if ($check == 0) {
	     $id_contrib = 1;
	  } else {
	     $result=pg_query($sql);
			 $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $id_contrib = $info['id_contrib'];
       pg_free_result($result);
	     $id_contrib++;
    }
		return $id_contrib; 
 }

 function get_contrib_nombre($id_contrib) { 
	if (($id_contrib == '0') OR ($id_contrib == '') OR ($id_contrib === NULL)) {
		return "-";   
   } else {
		$sql="SELECT con_pat, con_mat, con_nom1, con_nom2 FROM contribuyentes WHERE id_contrib = '$id_contrib'";
		$result   = pg_query($sql);
		$info     = pg_fetch_array($result, null, PGSQL_ASSOC);
		$con_pat  = utf8_decode($info['con_pat']);
		$con_mat  = utf8_decode($info['con_mat']);
		$con_nom1 = utf8_decode($info['con_nom1']);
		$con_nom2 = utf8_decode($info['con_nom2']);
		pg_free_result($result);
		   if ($con_nom1 != "") {
		      $nom_prop = $con_nom1;
		      if ($con_nom2 != "") {
		        $nom_prop = $nom_prop." ".$con_nom2;
		      }			 
		   } else $nom_prop = "";
		   if ($con_pat != "") {
		      $nom_prop = trim($nom_prop." ".$con_pat);
		      if ($con_mat != "") {
		         $nom_prop = $nom_prop." ".$con_mat;
		      }			 
		   }
		   if (trim($nom_prop) == ""){
		      $nom_prop = "S/N";
		   }
		   return $nom_prop;
    }
 }
function get_contrib_nombre2($id_contrib) { 
	if (($id_contrib == '0') OR ($id_contrib == '') OR ($id_contrib === NULL)) {
		return "-";   
	} else {
		$sql="SELECT con_pat, con_mat, con_nom1, con_nom2, dom_nom, dom_num FROM contribuyentes WHERE id_contrib = '$id_contrib'";
		$result = pg_query($sql);
		$info = pg_fetch_array($result, null, PGSQL_ASSOC);
		$con_pat = trim(utf8_decode($info['con_pat']));
		$con_mat = trim(utf8_decode($info['con_mat']));
		$con_nom1 = trim(utf8_decode($info['con_nom1']));
		$con_nom2 = trim(utf8_decode($info['con_nom2']));
		pg_free_result($result);
	   if ($con_nom1 != "") {
	      $nom_prop = $con_nom1;
	      if ($con_nom2 != "") {
	        $nom_prop = $nom_prop." ".$con_nom2;
	      }			 
	   } else $nom_prop = "";
	   if ($con_pat != "") {
	      $nom_prop = trim($nom_prop." ".$con_pat);
	      if ($con_mat != "") {
	         $nom_prop = $nom_prop." ".$con_mat;
	      }			 
	   }
	   if (trim($nom_prop) == ""){
	      $nom_prop = "S/N";
	   }
	   return $nom_prop;
    }
 }

 function get_contrib_nombre_xid ($id_contrib) {
		if (($id_contrib == '0') OR ($id_contrib == '') OR ($id_contrib === NULL)) {
		   return "";   
    } else {		
		$pos1 = strpos($id_contrib,",");
		if ($pos1 > 0) {               							 
			    $x = $position = 0;
			    $i = 0;	  
		      while ($i <= strlen($id_contrib)) {	
			       $char = substr($id_contrib, $i, 1);		
	           if (($char == ',') OR ($i == strlen($id_contrib))) {
                $id[$x] = substr($id_contrib,$position,$i-$position);
		            $position=$i+1;			
			          $x++;
	           } 						 
	           $i++;   
          } #end_of_while
					$propietario = get_contrib_nombre	($id[0]);
			    $i = 1;
			    while ($i < $x) {
			       $prop = get_contrib_nombre	($id[$i]);
						 $propietario = $propietario.",".$prop;
             $i++;				       
			    }	
		   } else {
			    $propietario = get_contrib_nombre($id_contrib);
			 }
#echo "PROP_STRING: $propietario <br />";
			 return $propietario;	 
    }	
 }

 function get_contrib_pmc ($id_contrib) {
    $sql="SELECT con_pmc FROM contribuyentes WHERE id_contrib = '$id_contrib'";
    $check = pg_num_rows(pg_query($sql));
	  if ($check == 0) {
	     $con_pmc = "-";
	  } else {
		$result=pg_query($sql);
		$info = pg_fetch_array($result, null, PGSQL_ASSOC);
		$con_pmc = $info['con_pmc'];
		pg_free_result($result);
		$con_pmc;
    }
		return $con_pmc; 
 }
		
 function get_contrib_pmc_new() {
    $sql="SELECT con_pmc FROM contribuyentes ORDER BY con_pmc DESC LIMIT 1";
    $check = pg_num_rows(pg_query($sql));
	  if ($check == 0) {
	     $con_pmc = 1;
	  } else {
	     $result=pg_query($sql);
			 $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $con_pmc = $info['con_pmc'];
       pg_free_result($result);
	     $con_pmc++;
    }
		return $con_pmc; 
 }
  
 function get_uv($cod_cat) {
    GLOBAL $max_strlen_uv;
    $cod_uv = (int) substr ($cod_cat,0,$max_strlen_uv);
		return $cod_uv; 
 }
 
 function get_man($cod_cat) {
    GLOBAL $max_strlen_uv; GLOBAL $max_strlen_man;
    $cod_man = (int) substr ($cod_cat,$max_strlen_uv+1,$max_strlen_man); 
		return $cod_man;
 } 
 
 function get_pred($cod_cat) {
    GLOBAL $max_strlen_uv; GLOBAL $max_strlen_man; GLOBAL $max_strlen_pred; 
    $cod_pred = (int) substr ($cod_cat,$max_strlen_uv+1+$max_strlen_man+1,$max_strlen_pred);
		return $cod_pred; 
 }
 
 #function get_subl($cod_cat) {
 #   $cod_subl = (int) substr ($cod_cat,10,2); 
	#	return $cod_subl;
 #} 
  
 function get_coti_de_hoy ($fecha, $moneda)
 {
    $sql="SELECT $moneda FROM imp_cotizaciones WHERE fecha = '$fecha'";	
    $check_imp = pg_num_rows(pg_query($sql));
		if ($check_imp == 0) {
		   return 0;
		} else {			    
		   $result_imp = pg_query($sql);
       $info_imp = pg_fetch_array($result_imp, null, PGSQL_ASSOC);			  
       $coti = trim($info_imp[$moneda]);	
       return $coti;				
    }
 }
 function get_direccion_from_id_inmu ($id_inmu) {
    $sql="SELECT cod_geo, cod_uv, cod_man, cod_pred FROM info_inmu WHERE id_inmu = '$id_inmu'";
		$check_info = pg_num_rows(pg_query($sql));
		if ($check_info > 0) {
		   $result = pg_query($sql);
       $info_inmu = pg_fetch_array($result, null, PGSQL_ASSOC);	
       $cod_g = $info_inmu['cod_geo'];		  
       $cod_uv = $info_inmu['cod_uv'];
       $cod_man = $info_inmu['cod_man'];
       $cod_pred = $info_inmu['cod_pred'];			 			 
			 pg_free_result($result);
			 $sql="SELECT dir_tipo, dir_nom, dir_num FROM info_predio WHERE cod_geo = '$cod_g' AND  cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
       $result = pg_query($sql);
       $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $dir_tipo = trim($info['dir_tipo']);
       $dir_nom = utf8_decode($info['dir_nom']);
       $dir_num = $info['dir_num'];
       pg_free_result($result); 	 
			 if ($dir_tipo == "A") { 
          $dir_tipo = "Av.";
       } elseif ($dir_tipo == "C") {
          $dir_tipo = "C/";
       } elseif ($dir_tipo == "P") {
          $dir_tipo = "P/";
       } elseif ($dir_tipo == "PZ") {
          $dir_tipo = "Pza.";					
       } else $dir_tipo = "";
       $direccion = trim($dir_tipo." ".$dir_nom." ".$dir_num);	
       return $direccion;		
		} else {
		   return "-";
		}
 }
 
 function get_edades ($string,$edad_limite) {
    $i = 1;
		$number = 0;
		$position = 0;
		$counter = 0;
		while ($i <= strlen ($string)) {
			 $char = substr($string, $i-1, 1);
			 if (($char == ",") OR ($i == strlen ($string))) {
			    if ($i == strlen ($string)) {
					   $counter++;
					}
			    $edad = substr($string, $position, $counter);
#echo"STRING:$string; POS:$position; COUNTER:$counter; EDAD: $edad<br />";				
#echo "EDAD: $edad<br />";
					if ($edad < $edad_limite) {
					   $number++;
					}
					$position = $i;
					$counter = -1;	
			 }
			 $counter++;
		   $i++;
		}
#echo"NUMERO DE PERSONAS MENOR A $edad_limite: $number<br />";		
		return $number;
 }  
 
 function get_factor ($cod_cat) {
    $sql="SELECT factor FROM plano_cat_zoom WHERE cod_cat = '$cod_cat'";
    $check_zoom = pg_num_rows(pg_query($sql));		
    if ($check_zoom > 0) {
       $result=pg_query($sql);
       $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $factor = $info['factor'];
			 pg_free_result($result);
			 return $factor;					 
	  } else {			 
		   return 1;
	  } 
 }

 function get_id_inmu ($cod_geo,$cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto) {
    $sql="SELECT id_inmu FROM info_inmu WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND cod_blq = '$cod_blq' AND cod_piso = '$cod_piso' AND cod_apto = '$cod_apto'";
    $check_inmu = pg_num_rows(pg_query($sql));		
    if ($check_inmu == 1) {
       $result=pg_query($sql);
       $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $id_inmu = $info['id_inmu'];
			 pg_free_result($result);
			 return $id_inmu;					 
	  } else {			 
		   return 0;
	  }
 }

 function get_id_inmu_new () {
    $sql="SELECT id_inmu FROM info_inmu ORDER BY id_inmu DESC LIMIT 1";
    $check = pg_num_rows(pg_query($sql));
		if ($check == 0) {
		   $id_inmu = 1;
		} else {
		   $result=pg_query($sql);
			 $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $id_inmu = $info['id_inmu'];
       pg_free_result($result);
			 $id_inmu++;
    }
		return $id_inmu;	
 }
 
 function get_id_predio ($cod_geo,$cod_uv,$cod_man,$cod_pred) {
    $sql="SELECT id_predio FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' ";							 
    $check = pg_num_rows(pg_query($sql));
    if ($check == 0) {
	     $id_predio = 0;
		} else {
	     $result=pg_query($sql);
			 $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $id_predio = $info['id_predio'];
       pg_free_result($result);
    }
		return $id_predio;	 
 }
 
 function get_id_predio_new () {
    $sql="SELECT id_predio FROM info_predio ORDER BY id_predio DESC LIMIT 1";							 
    $check = pg_num_rows(pg_query($sql));
    if ($check == 0) {
	     $id_predio = 1;
		} else {
	     $result=pg_query($sql);
			 $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $id_predio = $info['id_predio'];
       pg_free_result($result);
			 $id_predio++;
    }
		return $id_predio;	
 } 
 
 function get_linelen ($point_x1, $point_y1, $point_x2, $point_y2) {
    $sql="SELECT ST_Length(ST_GeomFromText('LINESTRING($point_x1 $point_y1, $point_x2 $point_y2)'))AS dist";				 
    $result=pg_query($sql);				 
    $res_dis = pg_fetch_array($result, null, PGSQL_ASSOC);
    $dist = ROUND ($res_dis['dist']*1000,0)/1000;					 									 
    pg_free_result($result);
		return $dist;
 }

function get_material_de_via ($id_inmu) { 
$cod_uv = get_cod_uv_from_id_inmu($id_inmu);
$cod_man = get_cod_man_from_id_inmu($id_inmu);
$cod_pred = get_cod_pred_from_id_inmu($id_inmu);
$sql="SELECT material FROM material_de_via WHERE material ='ASF' AND ST_intersects((SELECT the_geom FROM predios WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'),the_geom)";	
$check_mat = pg_num_rows(pg_query($sql));

if ($check_mat == 1) {	
	return "ASF";
	} else {
	$sql="SELECT material FROM material_de_via WHERE material ='ADQ' AND ST_intersects((SELECT the_geom FROM predios WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'),the_geom)";
	$check_mat = pg_num_rows(pg_query($sql));
if ($check_mat == 1) {
	return "ADQ";
	} else {		
	$sql="SELECT material FROM material_de_via WHERE material ='TRR' AND ST_intersects((SELECT the_geom FROM predios WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'),the_geom)";
	$check_mat = pg_num_rows(pg_query($sql));
if ($check_mat == 1) {
	return "TRR";
	} else {
	$sql="SELECT material FROM material_de_via WHERE material ='CEM' AND ST_intersects((SELECT the_geom FROM predios WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'),the_geom)";      
	$check_mat = pg_num_rows(pg_query($sql));
if ($check_mat == 1) {
	return "CEM";
	} else {
	$sql="SELECT material FROM material_de_via WHERE material ='PIE' AND ST_intersects((SELECT the_geom FROM predios WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'),the_geom)";      
	$check_mat = pg_num_rows(pg_query($sql));
if ($check_mat == 1) {
	return "PIE";
	} else {
	return 0;
	}	 
					}	 
				}
		 }
  }
}


 function edg_material_de_via ($id_inmu) {
 	$sql="SELECT via_mat FROM info_predio WHERE id_predio = '$id_inmu'";				 
    $result=pg_query($sql);				 
    $info = pg_fetch_array($result, null, PGSQL_ASSOC);
    $dist = utf8_decode($info['via_mat']);					 									 
    pg_free_result($result);
	return $dist;
 }


 
 function get_patente_act_raz($id_patente) {
    $sql="SELECT act_raz FROM patentes WHERE id_patente = '$id_patente'";				 
    $result=pg_query($sql);				 
    $info = pg_fetch_array($result, null, PGSQL_ASSOC);
    $act_raz = utf8_decode($info['act_raz']);					 									 
    pg_free_result($result);
	return $act_raz;
 }
 
 function get_objectcode($objeto) {
    if ($objeto == "Medidor de agua") {		 
       return 30;						
    } elseif ($objeto == "Antena") {			 
       return 50;	
    } elseif ($objeto == "Cabina Tel.") {			 
       return 70;	
	  } elseif ($objeto == "Poste Electr.") {			 
       return 80;
	  } elseif ($objeto == "Poste Luz GPS") {			 
       return 81;			 	
    } elseif ($objeto == "Horno") {			 
       return 10;	
		} elseif ($objeto == "Noria") {			 
       return 40;	
	  } elseif ($objeto == "Letrina") {			 
       return 20;	
	  } elseif ($objeto == "Medidor Viento") {			 
       return 60;	
		} elseif ($objeto == "Punto Control") {			 
       return 90;																			
    }	elseif ($objeto == "Plaza") {	
			 return 15;	
    } elseif ($objeto == "Canal") {	
       return 25;	 							 			 
    } elseif ($objeto == "Limite") {
       return 35;
    }	 elseif ($objeto == "Red de Agua") {	
		   return 45;		 							 			 
    } elseif ($objeto == "Red de Luz") {
			 return 55;
    }	else return 0;
 }
 
 function get_point_x ($point_x1, $point_y1, $point_x2, $point_y2, $porc_dist) {
    $sql="SELECT x(ST_Line_Interpolate_Point(the_line, $porc_dist))
	        FROM (SELECT ST_GeomFromText('LINESTRING($point_x1 $point_y1, $point_x2 $point_y2)') As the_line) As foo";
    $result=pg_query($sql);				 
    $res_temp = pg_fetch_array($result, null, PGSQL_ASSOC);
    $temp_x = ROUND ($res_temp['x']*1000,0)/1000;				 									 
    pg_free_result($result);
		return $temp_x;
 }
 
 function get_point_y ($point_x1, $point_y1, $point_x2, $point_y2, $porc_dist) {
    $sql="SELECT y(ST_Line_Interpolate_Point(the_line, $porc_dist))
	        FROM (SELECT ST_GeomFromText('LINESTRING($point_x1 $point_y1, $point_x2 $point_y2)') As the_line) As foo";
    $result=pg_query($sql);				 
    $res_temp = pg_fetch_array($result, null, PGSQL_ASSOC);
    $temp_y = ROUND ($res_temp['y']*1000,0)/1000;					 									 
    pg_free_result($result);
		return $temp_y;
 }
  
  function get_objectlinecode($objeto) {
    if ($objeto == "Plaza") {		 
       return 15;						
    } elseif ($objeto == "Canal") {			 
       return 25;	
    } elseif ($objeto == "Limite") {			 
       return 35;	
	  } else return 0;
 }
 
 function get_position4($point_x, $point_y, $centroid_x, $centroid_y) {
    if ($point_x < $centroid_x) {
      if ($point_y < $centroid_y) { 
			   return "SO";
      } else {
			   return "NO";
      }
   } else {
      if ($point_y < $centroid_y) { 
			   return "SE";
      } else {
			   return "NE";
      }
   }
 }
 
  function get_position8($point_x, $point_y, $centroid_x, $centroid_y, $xmin, $xmax, $ymin, $ymax) {
    if ($point_x < $centroid_x) {
      if ($point_y < $centroid_y) {
			   if ($point_x > $xmin) {
				    return "SUR";
				 } elseif ($point_y > $ymin) {
			      return "OESTE";
				 } else return "SO";
      } else {
         if ($point_x > $xmin) {
				    return "NORTE";
				 } elseif ($point_y < $ymax) {
			      return "OESTE";
				 } else 
			   return "NO";
      }
   } else {
      if ($point_y < $centroid_y) { 
			   if ($point_x < $xmax) {
				    return "SUR";
				 } elseif ($point_y > $ymin) {
			      return "ESTE";
				 } else return "SE";
      } else {
			   if ($point_x < $xmax) {
				    return "NORTE";
				 } elseif ($point_y < $ymax) {
			      return "ESTE";
				 } else return "NE";
      }
   }
 }
 
 function get_predio_dir ($cod_geo,$cod_uv,$cod_man,$cod_pred) {
    $sql="SELECT dir_tipo, dir_nom, dir_num FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
    $result = pg_query($sql);
    $info = pg_fetch_array($result, null, PGSQL_ASSOC);
    $dir_tipo = trim(utf8_decode($info['dir_tipo']));
    $dir_nom = trim(utf8_decode($info['dir_nom']));
    $dir_num = trim(utf8_decode($info['dir_num']));
    pg_free_result($result);
		if ((trim($dir_tipo) == "") AND (trim($dir_nom) == "") AND (trim($dir_num) == "")) {
		   return "-";
		} else {
       if ($dir_tipo == "A") { $dir_tipo = "Av."; }
	     elseif ($dir_tipo == "C") { $dir_tipo = "C/"; }
	     elseif ($dir_tipo == "P") { $dir_tipo = "P/ "; }	
	     elseif ($dir_tipo == "PZ") { $dir_tipo = "Plz."; }	
		   if (trim($dir_nom) == "") {
		      $dir_nom = "SIN NOMBRE";
		   }  			 
		   if (trim($dir_num) == "") {
		      $dir_num = "S/N";
		   }    
		   $direccion = $dir_tipo." ".$dir_nom." ".$dir_num;
		   return $direccion;
    } 
 } 

 function get_prop1_from_id_inmu ($id_inmu) {
    $sql="SELECT tit_1id FROM info_inmu WHERE id_inmu = '$id_inmu'";
    $result=pg_query($sql);
    $info = pg_fetch_array($result, null, PGSQL_ASSOC);
    $tit_1id = trim ($info['tit_1id']);
    pg_free_result($result);
    $prop1 = get_contrib_nombre ($tit_1id);
		return $prop1;
 }

 function get_prop1_ci_from_id_inmu ($id_inmu) {
    $sql="SELECT tit_1id FROM info_inmu WHERE id_inmu = '$id_inmu'";
    $result=pg_query($sql);
    $info = pg_fetch_array($result, null, PGSQL_ASSOC);
    $tit_1id = trim ($info['tit_1id']);
    pg_free_result($result);
    $prop1_ci = get_contrib_ci ($tit_1id);
		return $prop1_ci;
 }
 
 function get_prop2_from_id_inmu ($id_inmu) {
    $sql="SELECT tit_2id FROM info_inmu WHERE id_inmu = '$id_inmu'";
    $result=pg_query($sql);
    $info = pg_fetch_array($result, null, PGSQL_ASSOC);
    $tit_2id = trim ($info['tit_2id']);
    pg_free_result($result);
    $prop2 = get_contrib_nombre ($tit_2id);
		return $prop2;
 }
 
 function get_prop2_ci_from_id_inmu ($id_inmu) {
    $sql="SELECT tit_2id FROM info_inmu WHERE id_inmu = '$id_inmu'";
    $result=pg_query($sql);
    $info = pg_fetch_array($result, null, PGSQL_ASSOC);
    $tit_2id = trim ($info['tit_2id']);
    pg_free_result($result);
    $prop2_ci = get_contrib_ci ($tit_2id);
		return $prop2_ci;
 } 
 
 function get_propx_from_id_inmu ($id_inmu) {
    $sql="SELECT tit_xid FROM info_inmu WHERE id_inmu = '$id_inmu'";
    $result=pg_query($sql);
    $info = pg_fetch_array($result, null, PGSQL_ASSOC);
    $tit_xid = trim ($info['tit_xid']);
    pg_free_result($result);
   # $propx = get_contrib_nombre ($tit_xid);
		return $tit_xid;
 } 

 function get_propietarios_from_id_inmu ($id_inmu) {
    $sql="SELECT tit_1id, tit_2id, tit_xid FROM info_inmu WHERE id_inmu = '$id_inmu'";
    $result=pg_query($sql);
    $info = pg_fetch_array($result, null, PGSQL_ASSOC);
    $tit_1id = trim ($info['tit_1id']);
    $tit_2id = trim ($info['tit_2id']);
    $tit_xid = trim ($info['tit_xid']);
    pg_free_result($result);
    $prop1 = get_contrib_nombre($tit_1id);
    $prop2 = get_contrib_nombre($tit_2id);
		$propx = get_contrib_nombre($tit_xid);
    if ($prop1 == "-") {
       $propietario = "S/N";
    } else {	
	     if ($prop2 != "-") {
          $propietario = $propietario." Y ".$prop2;			
       }
       if ($tit_xid != "") {
          $propietario = $propietario."*)";
       }
 
    }
		return $propietario;
 } 
 
 function get_propietarios_con_ci_from_id_inmu ($id_inmu) {
	$sql="SELECT tit_1id, tit_2id, tit_xid FROM info_inmu WHERE id_inmu = '$id_inmu'";
	$result=pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$tit_1id = trim ($info['tit_1id']);
	$tit_2id = trim ($info['tit_2id']);
	$tit_xid = trim ($info['tit_xid']);
	pg_free_result($result);
	$prop1 = get_contrib_nombre($tit_1id);
	$prop2 = get_contrib_nombre($tit_2id);
	$propx = get_contrib_nombre($tit_xid);
	$tit_1ci = get_contrib_ci ($tit_1id);
	$tit_2ci = get_contrib_ci ($tit_2id);

    if ($prop1 == "-") {
       $propietario = "S/N";
    } else {
       if ($tit_1ci == "-") { 
          $propietario = $prop1;
       } else {
          $propietario = $prop1." ($tit_1ci)";
       }	
	     if ($prop2 != "-") {
          $propietario = $propietario." Y ".$prop2;
          if ($tit_2ci != "-") { 
             $propietario = $propietario." ($tit_2ci)";
          } 			
       }
       if ($tit_xid != "") {
          $propietario = $propietario."*)";
       }
 
    }
		return $propietario;
 }
 
 function get_rubro ($id) {
    $sql="SELECT act_rub FROM patentes_rubro WHERE id = '$id'";
    $result=pg_query($sql);				 
    $info = pg_fetch_array($result, null, PGSQL_ASSOC);
    $act_rub = ($info['act_rub']);					 									 
    pg_free_result($result);
		return $act_rub; 
 }
 
 function get_strlen ($text) {
	  $text = utf8_encode($text);
		$text = str_replace ("ÃƒÂ‘", "N" ,$text);
		$text = str_replace ("ÃƒÂ", "A" ,$text);
		$text = str_replace ("ÃƒÂ‰", "E" , $text);	
		$text = str_replace ("ÃƒÂ", "I" , $text);						
		$text = str_replace ("ÃƒÂ“", "O" , $text);			
		$text = str_replace ("ÃƒÂš", "U" , $text);
		$text = str_replace ("ÃƒÂ±", "n" ,$text);
		$text = str_replace ("ÃƒÂ¡", "a" ,$text);
		$text = str_replace ("ÃƒÂ©", "e" , $text);	
		$text = str_replace ("ÃƒÂ­", "i" , $text);						
		$text = str_replace ("ÃƒÂ³", "o" , $text);			
		$text = str_replace ("ÃƒÂº", "u" , $text);				
		$stringlength = strlen ($text);
	  return $stringlength;
 } 
 
 function get_titular ($tit_1nom1,$tit_1nom2,$tit_1pat,$tit_1mat) {
    if ($tit_1pat != "") {
       if ($tit_1nom1 != "") {
	        $titular = $tit_1nom1;
			    if ($tit_1nom2 != "") {
			       $titular = $tit_1nom1." ".$tit_1nom2;
			    }
					if ($tit_1mat != "") {
					   $titular = $titular." ".$tit_1pat." ".$tit_1mat;
					} else $titular = $titular." ".$tit_1pat;
       } else {
					if ($tit_1mat != "") {
					   $titular = $tit_1pat." ".$tit_1mat;
					} else $titular = $tit_1pat; 
		   }
		} else $titular = "-";
		return $titular;
 }
 
 function get_userid ($session_id) {
 			 $result=pg_query("SELECT user_id FROM usuarios WHERE session_id = '$session_id'");
       $userid_from_table = pg_fetch_array($result, null, PGSQL_ASSOC);
       $userid = $userid_from_table['user_id'];
	     pg_free_result($result); 
			 return $userid;
 }
 
 function get_username ($session_id) {
 			 $result=pg_query("SELECT usuario FROM usuarios WHERE session_id = '$session_id'");
       $username_from_table = pg_fetch_array($result, null, PGSQL_ASSOC);
       $username = $username_from_table['usuario'];
	     pg_free_result($result); 
			 return $username;
 }
 
 function get_username2 ($user_id) {
 			 $result=pg_query("SELECT usuario FROM usuarios WHERE user_id = '$user_id'");
       $username_from_table = pg_fetch_array($result, null, PGSQL_ASSOC);
       $username = $username_from_table['usuario'];
	     pg_free_result($result); 
			 return $username;
 } 
 
 function get_uso ($id_inmu) { 
			$cod_uv = get_cod_uv_from_id_inmu($id_inmu);
			$cod_man = get_cod_man_from_id_inmu($id_inmu);
			$cod_pred = get_cod_pred_from_id_inmu($id_inmu);
      $sql="SELECT uso, ST_within((SELECT centroid(the_geom) FROM predios WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'),the_geom) FROM uso_de_suelo";
      $i = 0;
			$result = pg_query($sql);
			$uso_value = false;
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {				 	 			           
         foreach ($line as $col_value) {			 
				    if ($i == 0) {
						   $temp_uso = trim ($col_value);
						} else {
				       if ($col_value == "t") {
							    $uso = $temp_uso;
									$uso_value = true;
						   }
							 $i = -1;
						}
						$i++;
				 }
			}	
      pg_free_result($result); 		
 			if (!$uso_value) {
				 return 0;
			} else {	
			   return $uso;
			}
} 
 
 function get_vehcls ($veh_cls) {
    $sql="SELECT cls_name FROM vehic_clase WHERE veh_cls = '$veh_cls'";
    $result = pg_query($sql);				 
    $info = pg_fetch_array($result, null, PGSQL_ASSOC);
    $veh_cls = ($info['cls_name']);					 									 
    pg_free_result($result);
		return $veh_cls; 
 }
 
 function get_zona ($id_inmu) { 
 	$cod_geo  = get_cod_geo_from_id_inmu($id_inmu);
	$cod_uv   = get_cod_uv_from_id_inmu($id_inmu);
	$cod_man  = get_cod_man_from_id_inmu($id_inmu);
	$cod_pred = get_cod_pred_from_id_inmu($id_inmu);

	$sql="SELECT zona, ST_within((SELECT centroid(the_geom) FROM predios WHERE  cod_geo='$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'),the_geom) FROM zonas";

   $i = 0;
	$result = pg_query($sql);
	$zona_value = false;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {				 	 			           
      foreach ($line as $col_value) {	

			if ($i == 0) {
				$temp_zona = trim ($col_value);
			} else {

			   if ($col_value == "t") {
					$zona = $temp_zona;
					$zona_value = true;

				}
				$i = -1;
			}
			$i++;
			}
		}	
   	pg_free_result($result); 		
		if (!$zona_value) {
			 return 0;
		} else {	
		   return $zona;
	}
}
 
function get_zona_brujula ($cod_cat, $centro_del_pueblo_para_zonas_x, $centro_del_pueblo_para_zonas_y) {
	$cod_uv = get_uv ($cod_cat); $cod_man = get_man($cod_cat);  $cod_pred = get_pred ($cod_cat);	  
	$result=pg_query("SELECT x(centroid(the_geom)),y(centroid(the_geom)) FROM predios WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");  
	$cstr = pg_fetch_array($result, null, PGSQL_ASSOC);
	$centroid_x2 = $cstr['x'];
	$centroid_y2 = $cstr['y'];
	$x1 = $centro_del_pueblo_para_zonas_x;
	$y1 = $centro_del_pueblo_para_zonas_y;

   pg_free_result($result);
   $dx = $x1 - $centroid_x2;
   $dy = $y1 - $centroid_y2;
   $m  = abs($dx/$dy);
	$angulo = ATAN($m);
	$angulo = rad2deg($angulo);
	# CUADRANTE I
	if ($centroid_x2>$x1 && $centroid_y2>$y1) {
		$angulo = $angulo;
		if (($angulo >= 0) && ($angulo < 22.5)) {
			return 'N';
		}
		if (($angulo >= 22.5) && ($angulo < 67.5)) {
			return 'NE';
		}			
		if (($angulo >= 67.5) && ($angulo < 90.0)) {
			return 'E';
		}	
	}
	# CUADRANTE II
	if ($centroid_x2>$x1 && $centroid_y2<$y1) {
		$angulo = 180 - $angulo;
		if (($angulo >= 90) && ($angulo < 112.5)) {
			return 'E';
		}
		if (($angulo >= 112.5) && ($angulo < 157.5)) {
			return 'SE';
		}			
		if (($angulo >= 157.5) && ($angulo < 180.0)) {
			return 'S';
		}	

	}
	# CUADRANTE III
	if ($centroid_x2<$x1 && $centroid_y2<$y1) {
		$angulo = 180 + $angulo;
		if (($angulo >= 180) && ($angulo < 202.5)) {
			return 'S';
		}
		if (($angulo >= 202.5) && ($angulo < 247.5)) {
			return 'SO';
		}			
		if (($angulo >= 247.5) && ($angulo < 270.0)) {
			return 'O';
		}	

	}
	# CUADRANTE IV
	if ($centroid_x2 <$x1 && $centroid_y2> $y1) {
		$angulo = 360 - $angulo;
		if (($angulo >= 270) && ($angulo < 292.5)) {
			return 'O';
		}
		if (($angulo >= 292.5) && ($angulo < 337.5)) {
			return 'NO';
		}			
		if (($angulo >= 337.5) && ($angulo < 360.0)) {
			return 'N';
		}	
	}

}


 
function imp_calidad_const($gestion,$line_media) {
	$line_media = ROUND ($line_media,0);
	$sql="SELECT * FROM imp_valua_viv_vf WHERE gestion = '$gestion'";
	$check_filas = pg_num_rows(pg_query($sql));
	if ($check_filas == 0) {
		if ($gestion < 2003) {
			$gestion = 2003;
			$sql="SELECT * FROM imp_valua_viv_vf WHERE gestion = '$gestion'";
		} else {
			$fecha_ufv = $gestion."-12-31";
			$sql="SELECT ufv FROM imp_cotizaciones WHERE fecha = '$fecha_ufv'";
			$check_coti_nuevo = pg_num_rows(pg_query($sql));
			if ($check_coti_nuevo == 0) { 
			      return 0;
			} else {
				$result = pg_query($sql);
				$info_coti_nuevo = pg_fetch_array($result, null, PGSQL_ASSOC);			  
				$ufv_nuevo = trim($info_coti_nuevo['ufv']);					    
				$gestion_ant = $gestion-1;
				$fecha_ufv = $gestion_ant."-12-31";
				$sql="SELECT ufv FROM imp_cotizaciones WHERE fecha = '$fecha_ufv'";
				$check_coti_ant = pg_num_rows(pg_query($sql));
				if ($check_coti_ant == 0) {					
			         return 0;
				} else {
					$result = pg_query($sql);
					$info_coti_ant = pg_fetch_array($result, null, PGSQL_ASSOC);			  
					$ufv_ant = trim($info_coti_ant['ufv']);							
					$factor_ufv = $ufv_nuevo/$ufv_ant;						
					$actualizar = actualizar_tabla ("imp_valua_viv_vf", $gestion, $factor_ufv);
					if ($actualizar == 0) {
						return -1;
					} else {
						$sql="SELECT * FROM imp_valua_viv_vf WHERE gestion = '$gestion'";
				}								 
			}
		}
	}
}  

$result_valua_viv = pg_query($sql);
$info_valua_viv = pg_fetch_array($result_valua_viv, null, PGSQL_ASSOC);
if ($line_media == 1) {
	    $calida_const = $info_valua_viv['margin'];
	 }
	 if ($line_media == 2) {
	    $calida_const = $info_valua_viv['mecono'];
	 }
	 if ($line_media == 3) {
	    $calida_const = $info_valua_viv['econo'];
	 }
	 if ($line_media == 4) {
	    $calida_const = $info_valua_viv['bueno'];
	 } 
	 if ($line_media == 5) {
	    $calida_const = $info_valua_viv['mbueno'];
	 }
	 if ($line_media == 6) {
	    $calida_const = $info_valua_viv['lujoso'];
	 }	
	 pg_free_result($result_valua_viv);	 	
	 return $calida_const; 	 	   
}
 
function imp_factor_deprec ($gestion, $edi_ano, $ano_actual)
{ 
	$antig_casa = $ano_actual-$edi_ano;
	if ($antig_casa <= 5) { $antig_casa = 5;
		} elseif (($antig_casa > 5) AND ($antig_casa <= 10)) { $antig_casa = 10;
		} elseif (($antig_casa > 10) AND ($antig_casa <= 15)) { $antig_casa = 15;
		} elseif (($antig_casa > 15) AND ($antig_casa <= 20)) { $antig_casa = 20;
		} elseif (($antig_casa > 20) AND ($antig_casa <= 25)) { $antig_casa = 25;
		} elseif (($antig_casa > 25) AND ($antig_casa <= 30)) { $antig_casa = 30;
		} elseif (($antig_casa > 30) AND ($antig_casa <= 35)) { $antig_casa = 35;
		} elseif (($antig_casa > 35) AND ($antig_casa <= 40)) { $antig_casa = 40;
		} elseif (($antig_casa > 40) AND ($antig_casa <= 45)) { $antig_casa = 45;
		} elseif (($antig_casa > 45) AND ($antig_casa <= 50)) { $antig_casa = 50;	
		} elseif ($antig_casa > 50) { $antig_casa = 99;}	  	 	 	 	 	 	 	  
		$sql="SELECT factor FROM imp_fact_deprec WHERE gestion = '$gestion' AND antig = '$antig_casa'";
		$check_filas = pg_num_rows(pg_query($sql));
		if ($check_filas == 0) {
		   if ($gestion < 2005) {			
				$gestion = 2005;
				$sql="SELECT * FROM imp_fact_deprec WHERE gestion = '$gestion' AND antig = '$antig_casa'";			 
			} else {
				$actualizar = actualizar_tabla ("imp_fact_deprec", $gestion, 1);
		}	 	 
	}
	$result_factor_deprec = pg_query($sql);
	$info_factor_deprec = pg_fetch_array($result_factor_deprec, null, PGSQL_ASSOC);
	$factor_deprec = $info_factor_deprec['factor'];
	pg_free_result($result_factor_deprec);	 	
	return $factor_deprec; 	
}

function imp_factor_incl ($gestion, $ter_topo)
{	 		 
   $sql="SELECT * FROM imp_fact_inclinacion WHERE gestion = '$gestion'";
   $check_filas = pg_num_rows(pg_query($sql));
	 if ($check_filas == 0) {
	    if ($gestion < 2005) {
			   $gestion = 2005;
				 $sql="SELECT * FROM imp_fact_inclinacion WHERE gestion = '$gestion'";
			} else {
			   $actualizar = actualizar_tabla ("imp_fact_inclinacion", $gestion, 1);
			}
	 }
	 $result_inc = pg_query($sql);
   $info_inc = pg_fetch_array($result_inc, null, PGSQL_ASSOC);
	 if ($ter_topo == "PLA") {
	    $factor = $info_inc['plano'];
	 } elseif ($ter_topo == "SPL") {
	    $factor = $info_inc['semiplano'];
	 } elseif ($ter_topo == "INC") {
	    $factor = $info_inc['inclinado'];
	 } elseif ($ter_topo == "MIN") {
	    $factor = $info_inc['muy_inclinado'];
	 } elseif ($ter_topo == "BAR") {
	    $factor = $info_inc['barranco'];
	 } else $factor = -1;	
	 pg_free_result($result_inc);	 	
	 return $factor; 	 	 
}	 

function imp_factor_serv($gestion, $servicio, $valor)
{
	$sql="SELECT * FROM imp_fact_servicios WHERE gestion = '$gestion'";
	$check_filas = pg_num_rows(pg_query($sql));
	if ($check_filas == 0) {
		if ($gestion < 2005) {			
			   $gestion = 2005;
				 $sql="SELECT * FROM imp_fact_servicios WHERE gestion = '$gestion'";			 
			} else {
			   $actualizar = actualizar_tabla ("imp_fact_servicios", $gestion, 1);
		}	 	 
	} 
	$result = pg_query($sql);
	$info_serv = pg_fetch_array($result, null, PGSQL_ASSOC);
	
	if ($valor == "SI") {
		$factor = $info_serv[$servicio];
	} else $factor = 0;

	pg_free_result($result);
	return $factor; 	 
}	 

 
function imp_valorporm2_terr ($gestion, $zona, $via_mat)
{
	$sql="SELECT * FROM imp_fact_zona WHERE gestion = '$gestion' AND zona = '$zona'";
	$check_filas = pg_num_rows(pg_query($sql));  
	if ($check_filas == 0) {
		if ($gestion < 2003) {
			$gestion = 2003;
			$sql="SELECT * FROM imp_fact_zona WHERE gestion = '$gestion' AND zona = '$zona'";
			} else {
			$fecha_ufv = $gestion."-12-31";
			$sql="SELECT ufv FROM imp_cotizaciones WHERE fecha = '$fecha_ufv'";
         $check_coti_nuevo = pg_num_rows(pg_query($sql));
			if ($check_coti_nuevo == 0) { 
			      return 0;
				 } else {		 
			      $result = pg_query($sql);
            $info_coti_nuevo = pg_fetch_array($result, null, PGSQL_ASSOC);			  
            $ufv_nuevo = trim($info_coti_nuevo['ufv']);					    
				    $gestion_ant = $gestion-1;
						$fecha_ufv = $gestion_ant."-12-31";
						$sql="SELECT ufv FROM imp_cotizaciones WHERE fecha = '$fecha_ufv'";
            $check_coti_ant = pg_num_rows(pg_query($sql));
						if ($check_coti_ant == 0) {					
			         return 0;
						} else {
			         $result = pg_query($sql);
               $info_coti_ant = pg_fetch_array($result, null, PGSQL_ASSOC);			  
               $ufv_ant = trim($info_coti_ant['ufv']);							
               $factor_ufv = $ufv_nuevo/$ufv_ant;						
				       $actualizar = actualizar_tabla ("imp_fact_zona", $gestion, $factor_ufv);								 
						}
				 }
			}
	 }  
	 $result_avaterr = pg_query($sql);
   $info_avaterr = pg_fetch_array($result_avaterr, null, PGSQL_ASSOC);
	 if ($via_mat == "ASF") {
      $valor_por_m2 = $info_avaterr['asf'];
	 } elseif ($via_mat == "ADQ") {
      $valor_por_m2 = $info_avaterr['adq'];
	 } elseif ($via_mat == "CEM") {			
      $valor_por_m2 = $info_avaterr['cem'];
	 } elseif ($via_mat == "LOS") {	 
      $valor_por_m2 = $info_avaterr['los'];
	 } elseif ($via_mat == "PDR") {	 
      $valor_por_m2 = $info_avaterr['pdr'];
	 } elseif ($via_mat == "RIP") {	 
      $valor_por_m2 = $info_avaterr['rip'];
	 } elseif ($via_mat == "LAD") {	 
      $valor_por_m2 = $info_avaterr['lad'];			
	 } else {	 
      $valor_por_m2 = $info_avaterr['trr'];
	 }		 	 	 	 	 	 	
   pg_free_result($result_avaterr);	 	
	 return $valor_por_m2;
} 

function imp_getexen ($gestion, $avaluo_total)
{
   $sql="SELECT monto, cuota, mas_porc, exced FROM imp_escala_imp WHERE gestion = '$gestion' ORDER BY monto";
   $check_escala_imp = pg_num_rows(pg_query($sql));
   # NUEVA GESTION SIN DATOS
   if ($check_escala_imp == 0) {
		if ($gestion < 2003) {			
			$gestion_imp = 2003;
			$sql="SELECT * FROM imp_escala_imp WHERE gestion = '$gestion_imp' ORDER BY monto";			 
			} else {
			   return -1;
			}	 	 
	} 
   $no_de_filas = $check_escala_imp;	
   $result = pg_query($sql);	
   $i = $j = 0;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {      
		foreach ($line as $col_value) { 
			if ($i == 0) {
				$monto_temp[$j] = $col_value;
			} elseif ($i == 1) {
				$cuota_temp[$j] = $col_value;
			} elseif ($i == 2) {
				$mas_porc_temp[$j] = ROUND ($col_value,2);		    
			} elseif ($i == 3) {
			$exced_temp[$j] = $col_value;	
			$i = -1;
			}
			$i++;
		}
		$j++;
   }
	$monto_temp[$j] = 99999999;
	$hit = false; 	
	pg_free_result($result);
	$i = 0;
	while (!$hit) {	
		$k = $i+1;	    	 
      if ($avaluo_total < $monto_temp[$k]) {
			$cuota = $cuota_temp[$i];
			$mas_porc = $mas_porc_temp[$i];
			$exced = $exced_temp[$i];
			$hit = true;
		}
		$i++;
   }
	return $exced;
}
 
function imp_getcoti ($fecha, $moneda)
 {
    $sql="SELECT $moneda FROM imp_cotizaciones WHERE fecha = '$fecha'";	
		$i = 0;
		$menos_1_dia = true;
		$fecha1 = $fecha2 = $fecha;
		$coti = "";
		while (($coti == "") AND ($i < 200)) {
       $check_imp = pg_num_rows(pg_query($sql));
			 if ($check_imp > 0) {
			    $result_imp = pg_query($sql);
          $info_imp = pg_fetch_array($result_imp, null, PGSQL_ASSOC);			  
          $coti = trim($info_imp[$moneda]);	
#echo "FECHA DEL VALOR UFV: $fecha<br>";					
			 }	
			 if ($menos_1_dia) {	
	        $timestamp = strtotime($fecha1.' - 1 day'); 
					$fecha1 = date('Y-m-d', $timestamp);
					$fecha = $fecha1;
					$menos_1_dia = false;   
			 } else {
	        $timestamp = strtotime($fecha2.' + 1 day');
					$fecha2 = date('Y-m-d', $timestamp);
					$fecha = $fecha2;
					$menos_1_dia = true;   
			 }			 			
		   $sql="SELECT $moneda FROM imp_cotizaciones WHERE fecha = '$fecha'";					
			 $i++;
    }			 		
		return $coti; 
}

function imp_tasa_taprufv ($fecha)
{
	$ano_temp = substr ($fecha,0,4);
	$mes_temp = substr ($fecha,5,2);	
	$fecha_tapr = $ano_temp."-".$mes_temp."-01";
	$fecha_tapr = change_date_to_10char ($fecha_tapr);	 
	$sql="SELECT tapr_ufv FROM imp_cotizaciones WHERE fecha = '$fecha_tapr'";
	$check_imp = pg_num_rows(pg_query($sql));
	if ($check_imp > 0) {
      $result_imp = pg_query($sql);
      $info_imp = pg_fetch_array($result_imp, null, PGSQL_ASSOC);			  
      $tapr_ufv = trim($info_imp['tapr_ufv']);
			if ($tapr_ufv != NULL) {
			   return "$tapr_ufv";
			} else return -1;	
   } else return -1;		 				
}

function imp_dias_de_mora ($fecha_venc,$fecha)
{
   if($fecha_venc < $fecha) {
      $dateStart = $fecha_venc;
      $dateEnd = $fecha;
   } else {
      $dateStart = $fecha;
      $dateEnd = $fecha_venc;
   }
   $date1Timestamp = strtotime($dateStart);
   $date2Timestamp = strtotime($dateEnd);
   $dayDiff = ($date2Timestamp - $date1Timestamp)/(60*60*24);
   return floor ($dayDiff);				
}

function imp_multa_incum ($imp_neto,$ufv_ant,$ufv_actual)
{
   $sql="SELECT multa_incum FROM imp_base";
   $result_base = pg_query($sql);
   $info_base = pg_fetch_array($result_base, null, PGSQL_ASSOC);			  
   $multa_porc = $info_base['multa_incum'];	
	 $imp_neto_en_ufv_ant = $imp_neto/$ufv_ant;
	 $multa_incum_ufv = $imp_neto_en_ufv_ant * $multa_porc/100;
	 if ($multa_incum_ufv < 50) {
	    $multa_incum_ufv = 50;
	 } elseif ($multa_incum_ufv > 2400) {
	    $multa_incum_ufv = 2400;
	 }
   $multa_incum = ROUND ($multa_incum_ufv * $ufv_actual,0);	  
	 return $multa_incum;	 	  
}

function monthconvert ($month) {
		if ($month == "01") {
		   $monthname = "enero";
		} elseif ($month == "02") {
		   $monthname = "febrero";
		} elseif ($month == "03") {
		   $monthname = "marzo";
		} elseif ($month == "04") {
		   $monthname = "abril";
		} elseif ($month == "05") {
		   $monthname = "mayo";
		} elseif ($month == "06") {
		   $monthname = "junio";
		} elseif ($month == "07") {
		   $monthname = "julio";
		} elseif ($month == "08") {
		   $monthname = "agosto";
		} elseif ($month == "09") {
		   $monthname = "septiembre";
		} elseif ($month == "10") {
		   $monthname = "octubre";
		} elseif ($month == "11") {
		   $monthname = "noviembre";
		} elseif ($month == "12") {
		   $monthname = "diciembre";
		}	else {
			$monthname = "";
		}
	  return $monthname; 
 } 
 
 function numeros_a_letras ($numero) {
		if (($numero >= 0) AND ($numero <= 15)) {
		   $letras = numeros($numero);	   
		}		
		if (($numero > 15) AND ($numero <= 99)) {
		   $letras = decimos($numero);	   
		}
		if (($numero > 99) AND ($numero < 1000)) {	   
		   $letras = centavos($numero);	   
		}	
		if (($numero >= 1000) AND ($numero <= 99999)) {
			$miles =  FLOOR($numero/1000);		
			$numero = $numero - FLOOR($numero/1000)*1000;
			$letras = decimos($miles)." MIL ".centavos($numero);	   
		}	
	  if ($numero > 99999) {
		   $letras = $numero;	   
		}	
    return $letras;
}

function centavos ($numero) {
    $letras = "";
    if ($numero < 100) {$x = $numero;}
		elseif ($numero == 100) {$letras = "CIEN"; $x = 0;}
		elseif (($numero > 100) AND ($numero < 200)) {$letras = "CIENTO"; $x = $numero - 100; }
	  elseif (($numero >= 200) AND ($numero < 300)) {$letras = "DOSCIENTOS "; $x = $numero - 200; }
	  elseif (($numero >= 300) AND ($numero < 400)) {$letras = "TRESCIENTOS "; $x = $numero - 300; }
	  elseif (($numero >= 400) AND ($numero < 500)) {$letras = "CUATROCIENTOS "; $x = $numero - 400; }
	  elseif (($numero >= 500) AND ($numero < 600)) {$letras = "QUINIENTOS "; $x = $numero - 500; }	
	  elseif (($numero >= 600) AND ($numero < 700)) {$letras = "SEISCIENTOS "; $x = $numero - 600; }	
	  elseif (($numero >= 700) AND ($numero < 800)) {$letras = "SETECIENTOS "; $x = $numero - 700; }	
	  elseif (($numero >= 800) AND ($numero < 900)) {$letras = "OCHOCIENTOS "; $x = $numero - 800; }	
	  elseif (($numero >= 900) AND ($numero < 1000)) {$letras = "NOVECIENTOS "; $x = $numero - 900; }														
		if ($x > 0 ) {
		   $letras = $letras.decimos($x);
		}
		return $letras;
}

function decimos ($numero) {
		if (($numero >= 0) AND ($numero <= 15)) {
		   $letras = numeros($numero);
		}
		if (($numero > 15) AND ($numero < 20)) {
		   $letras = numeros($numero-10);	   
		   $letras = "DIECI".$letras;
		}
		if ($numero == 20) {$letras = "VEINTE";}
		if (($numero > 20) AND ($numero < 30)) {
			 $letras = numeros($numero-20);		   
		   $letras = "VEINTI".$letras;
		}														
		if ($numero == 30) {$letras = "TREINTA";}
		if (($numero > 30) AND ($numero < 40)) {		
			 $letras = numeros($numero-30);		   
		   $letras = "TREINTA Y ".$letras;
		}
		if ($numero == 40) {$letras = "CUARENTA";}
		if (($numero > 40) AND ($numero < 50)) {		
			 $letras = numeros($numero-40);		   
		   $letras = "CUARENTA Y ".$letras;
		}		
		if ($numero == 50) {$letras = "CINCUENTA";}
		if (($numero > 50) AND ($numero < 60)) {		
			 $letras = numeros($numero-50);		   
		   $letras = "CINCUENTA Y ".$letras;
		}
		if ($numero == 60) {$letras = "SESENTA";}
		if (($numero > 60) AND ($numero < 70)) {		
			 $letras = numeros($numero-60);		   
		   $letras = "SESENTA Y ".$letras;
		}
		if ($numero == 70) {$letras = "SETENTA";}
		if (($numero > 70) AND ($numero < 80)) {		
			 $letras = numeros($numero-70);		   
		   $letras = "SETENTA Y ".$letras;
		}		
		if ($numero == 80) {$letras = "OCHENTA";}
		if (($numero > 80) AND ($numero < 90)) {		
			 $letras = numeros($numero-80);		   
		   $letras = "OCHENTA Y ".$letras;
		}			
		if ($numero == 90) {$letras = "NOVENTA";}
		if (($numero > 90) AND ($numero < 100)) {		
			 $letras = numeros($numero-90);		   
		   $letras = "NOVENTA Y ".$letras;
		}								
    return $letras;
}

function numeros ($numero) {
	if ($numero == 0) {$letras = "CERO";}
	if ($numero == 1) {$letras = "UN";}
	if ($numero == 2) {$letras = "DOS";}
	if ($numero == 3) {$letras = "TRES";}
	if ($numero == 4) {$letras = "CUATRO";}
	if ($numero == 5) {$letras = "CINCO";}	
	if ($numero == 6) {$letras = "SEIS";}
	if ($numero == 7) {$letras = "SIETE";}
	if ($numero == 8) {$letras = "OCHO";}
	if ($numero == 9) {$letras = "NUEVE";}
	if ($numero == 10) {$letras = "DIEZ";}	
	if ($numero == 11) {$letras = "ONCE";}
	if ($numero == 12) {$letras = "DOCE";}
	if ($numero == 13) {$letras = "TRECE";}		
	if ($numero == 14) {$letras = "CATORCE";}
	if ($numero == 15) {$letras = "QUINCE";}		
	return $letras;
 }
 
 function textconvert ($text) {
	  $text = utf8_encode($text);
		$text = str_replace ("ÃƒÂ‘", "&Ntilde;" ,$text);
		$text = str_replace ("ÃƒÂ", "A" ,$text);
		$text = str_replace ("ÃƒÂ‰", "E" , $text);	
		$text = str_replace ("ÃƒÂ", "I" , $text);						
		$text = str_replace ("ÃƒÂ“", "O" , $text);			
		$text = str_replace ("ÃƒÂš", "U" , $text);		
		$text = str_replace ("ÃƒÂ±", "&ntilde;" ,$text);
		$text = str_replace ("ÃƒÂ¡", "&aacute;" ,$text);
		$text = str_replace ("ÃƒÂ©", "&eacute;" , $text);	
		$text = str_replace ("ÃƒÂ­", "&iacute;" , $text);						
		$text = str_replace ("ÃƒÂ³", "&oacute;" , $text);			
		$text = str_replace ("ÃƒÂº", "&uacute;" , $text);		
		$text = str_replace ("Âº", "º" , $text);
	  return $text; 
 }
 
 function textconvert2 ($text) {
		#$text = str_replace ("Ã±", "Ã‘" ,$text);
		$text = str_replace ("Ã±", "&Ntilde;" ,$text);		
		$text = str_replace ("Ã¡", "A" , $text);		
		$text = str_replace ("Ã©", "E" , $text);
		#$text = str_replace ("Ã­Â", "I" , $text);			
		$text = str_replace ("Ã³", "O" , $text);			
		$text = str_replace ("Ãº", "U" , $text);		
	  $text = str_replace ("Ã", "I" , $text);
		$text = str_replace ("Â", "" , $text);	
	  return $text; 
 }
 
 function textconvert3 ($text) {
		$text = str_replace ("Ã±", "&Ntilde;" ,$text);
		$text = str_replace ("Ã‘", "&Ntilde;" ,$text);
	  return $text; 
 }
  
 function textconvert_excel ($text) {
		$text = str_replace ("Ã±", "ñ" ,$text);
		$text = str_replace ("Ã‘", "Ñ" ,$text);
		$text = str_replace ("Ã¡", "á" , $text);	
		$text = str_replace ("Ã©", "é" , $text);		
		$text = str_replace ("Ã³", "ó" , $text);			
		$text = str_replace ("Ãº", "ú" , $text);
		$text = str_replace ("Ã­Â", "í" , $text);		
		$text = str_replace ("Ã-", "í" , $text);		
		$text = str_replace ("ÃÂ", "í" , $text);						
	  $text = str_replace ("Ã", "í" , $text);
		$text = str_replace ("Â", "",$text);				
	  return $text; 
 }
 
 function textconvert_foto ($text) {
		$text = str_replace ("Ã±", "n" ,$text);
		$text = str_replace ("Ã‘", "N" ,$text);
		$text = str_replace ("Ã¡", "a" , $text);	
		$text = str_replace ("Ã©", "e" , $text);		
		$text = str_replace ("Ã³", "o" , $text);			
		$text = str_replace ("Ãº", "u" , $text);
		$text = str_replace ("Ã­Â", "i" , $text);		
		$text = str_replace ("Ã­", "i" , $text);		
		$text = str_replace ("ÃÂ", "i" , $text);						
	  $text = str_replace ("Ã", "i" , $text);
		$text = str_replace ("Â", "",$text);				
	  return $text; 
 }
 
  function ucase ($text) {
	  $text = utf8_encode($text);
#		$text = str_replace ("ÃƒÂ‘", "&Ntilde;" ,$text);
		$text = str_replace ("ÃƒÂ", "A" ,$text);
		$text = str_replace ("ÃƒÂ‰", "E" , $text);	
		$text = str_replace ("ÃƒÂ", "I" , $text);						
		$text = str_replace ("ÃƒÂ“", "O" , $text);			
		$text = str_replace ("ÃƒÂš", "U" , $text);		
		$text = str_replace ("ÃƒÂ±", "ÃƒÂ‘" ,$text);
		$text = str_replace ("ÃƒÂ¡", "A" ,$text);
		$text = str_replace ("ÃƒÂ©", "E" , $text);	
		$text = str_replace ("ÃƒÂ­", "I" , $text);						
		$text = str_replace ("ÃƒÂ³", "O" , $text);			
		$text = str_replace ("ÃƒÂº", "U" , $text);
	  $text = utf8_decode($text);		
	  return $text; 
 }
 
 function check_numeros($string)
 {
 		$i = $z = 1;
    #$x = $j = $stringinit = 0;
		#$initcheck = true; 
		$allowed = array('0','1','2','3','4','5','6','7','8','9','.');
	  $stringlength = strlen($string);								 
	  #echo "Stringlength: $stringlength<br /> \n"; 	  
		while ($i <= strlen($string)) {	
			 $char = substr($string, $i-1, 1);
		   #echo "El char no $i es un $char \n";
			 $char_pos = 0;
			 $charcheck = false;
			 while ($char_pos < 11) {
           if ($char == $allowed[$char_pos]) {
							$charcheck = true;
					 } 	 	
	         $char_pos++; 
       }
			 if (!$charcheck) {
			    return false;
					break;
			 }
			 $i++; 
    }
#echo "Todo OK<br /> \n"; 	 	
		return true;
 } 




 function get_codcat_foto($cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto) {	
    GLOBAL $max_strlen_uv;    
    GLOBAL $max_strlen_man;   
    GLOBAL $max_strlen_pred;  
    GLOBAL $max_strlen_blq;   
    GLOBAL $max_strlen_piso;  
    GLOBAL $max_strlen_apto; 

	if ($max_strlen_uv == 2) {
		if (strlen($cod_uv) == 1) {
			$cod_cat = "0".$cod_uv;		 
		} else $cod_cat = $cod_uv;
		} elseif ($max_strlen_uv == 3) {
		if (strlen($cod_uv) == 1) {
			$cod_cat = "00".$cod_uv;
		} elseif (strlen($cod_uv) == 2) {
			$cod_cat = "0".$cod_uv;				 
		} else $cod_cat = $cod_uv;
		} elseif ($max_strlen_uv == 4) {
		if (strlen($cod_uv) == 1) {
			$cod_cat = "000".$cod_uv;
		} elseif (strlen($cod_uv) == 2) {
			$cod_cat = "00".$cod_uv;		
		} elseif (strlen($cod_uv) == 3) {
			$cod_cat = "0".$cod_uv;									 
		} else $cod_cat = $cod_uv;
	}			 			 
	### MANZANO ###
    if ($max_strlen_man == 2) {
		   if (strlen($cod_man) == 1) {
		      $cod_cat = $cod_cat."0".$cod_man;		 
		   } else $cod_cat = $cod_cat.$cod_man;
    } elseif ($max_strlen_man == 3) {
		   if (strlen($cod_man) == 1) {
		      $cod_cat = $cod_cat."00".$cod_man;
		   } elseif (strlen($cod_man) == 2) {
		      $cod_cat = $cod_cat."0".$cod_man;				 
		   } else $cod_cat = $cod_cat.$cod_man;
    } elseif ($max_strlen_man == 4) {
		   if (strlen($cod_man) == 1) {
		      $cod_cat = $cod_cat."000".$cod_man;
		   } elseif (strlen($cod_man) == 2) {
		      $cod_cat = $cod_cat."00".$cod_man;		
		   } elseif (strlen($cod_man) == 3) {
		      $cod_cat = $cod_cat."0".$cod_man;									 
		   } else $cod_cat =$cod_cat.$cod_man;
		}			
		### PREDIO ###
    if ($max_strlen_pred == 2) {
		   if (strlen($cod_pred) == 1) {
		      $cod_cat = $cod_cat."0".$cod_pred;		 
		   } else $cod_cat = $cod_cat.$cod_pred;
    } elseif ($max_strlen_pred == 3) {
		   if (strlen($cod_pred) == 1) {
		      $cod_cat = $cod_cat."00".$cod_pred;
		   } elseif (strlen($cod_pred) == 2) {
		      $cod_cat = $cod_cat."0".$cod_pred;				 
		   } else $cod_cat = $cod_cat.$cod_pred;
    } elseif ($max_strlen_pred == 4) {
		   if (strlen($cod_pred) == 1) {
		      $cod_cat = $cod_cat."000".$cod_pred;
		   } elseif (strlen($cod_pred) == 2) {
		      $cod_cat = $cod_cat."00".$cod_pred;		
		   } elseif (strlen($cod_pred) == 3) {
		      $cod_cat = $cod_cat."0".$cod_pred;									 
		   } else $cod_cat = $cod_cat.$cod_pred;
		}
	  ### EN CASO DE P.H. O CONDOMINIO ###			
		if (($cod_blq === "") OR ($cod_blq == "0") OR ($cod_blq == "00") OR ($cod_blq == "000") OR ($cod_blq == "0000")) {
		   return $cod_cat;
		} else {
		   ### BLOQUE ###
       if ($max_strlen_blq == 2) {
		      if (strlen($cod_blq) == 1) {
		         $cod_cat = $cod_cat."-0".$cod_blq."-";		 
		      } else $cod_cat = $cod_cat."-".$cod_blq."-";
       } elseif ($max_strlen_blq == 3) {
		      if (strlen($cod_blq) == 1) {
		         $cod_cat = $cod_cat."-00".$cod_blq."-";
		      } elseif (strlen($cod_blq) == 2) {
		         $cod_cat = $cod_cat."-0".$cod_blq."-";				 
		      } else $cod_cat = $cod_cat."-".$cod_blq."-";
       } elseif ($max_strlen_blq == 4) {
		      if (strlen($cod_blq) == 1) {
		        $cod_cat = $cod_cat."-000".$cod_blq."-";
		      } elseif (strlen($cod_blq) == 2) {
		         $cod_cat = $cod_cat."-00".$cod_blq."-";		
		      } elseif (strlen($cod_blq) == 3) {
		         $cod_cat = $cod_cat."-0".$cod_blq."-";									 
		      } else $cod_cat = $cod_cat."-".$cod_blq."-";
		   }
		   ### PISO ###
       if ($max_strlen_piso == 2) {
			    if ($cod_piso === "") {
			       $cod_cat = $cod_cat."00-";
		      } elseif (strlen($cod_piso) == 1) {
		         $cod_cat = $cod_cat."0".$cod_piso."-";		 
		      } else $cod_cat = $cod_cat.$cod_piso."-";
       } elseif ($max_strlen_piso == 3) {
			    if ($cod_piso === "") {
			       $cod_cat = $cod_cat."000-";			 
		      } elseif (strlen($cod_piso) == 1) {
		         $cod_cat = $cod_cat."00".$cod_piso."-";
		      } elseif (strlen($cod_piso) == 2) {
		         $cod_cat = $cod_cat."0".$cod_piso."-";				 
		      } else $cod_cat = $cod_cat.$cod_piso."-";
       } elseif ($max_strlen_piso == 4) {
			    if ($cod_piso === "") {
			       $cod_cat = $cod_cat."0000-";			 
		      } elseif (strlen($cod_piso) == 1) {
		         $cod_cat = $cod_cat."000".$cod_piso."-";
		      } elseif (strlen($cod_piso) == 2) {
		         $cod_cat = $cod_cat."00".$cod_piso."-";		
		      } elseif (strlen($cod_piso) == 3) {
		         $cod_cat = $cod_cat."0".$cod_piso."-";									 
		      } else $cod_cat = $cod_cat.$cod_piso."-";
		   }
		   ### APARTAMENTO ###		 
       if ($max_strlen_apto == 2) {
			    if ($cod_apto === "") {
			       $cod_cat = $cod_cat."00-";			 
		      } elseif (strlen($cod_apto) == 1) {
		         $cod_cat = $cod_cat."0".$cod_apto;		 
		      } else $cod_cat = $cod_cat.$cod_apto;
       } elseif ($max_strlen_apto == 3) {
			    if ($cod_apto === "") {
			       $cod_cat = $cod_cat."000-";				 
		      } elseif (strlen($cod_apto) == 1) {
		         $cod_cat = $cod_cat."00".$cod_apto;
		      } elseif (strlen($cod_apto) == 2) {
		         $cod_cat = $cod_cat."0".$cod_apto;				 
		      } else $cod_cat = $cod_cat.$cod_apto;
       } elseif ($max_strlen_apto == 4) {
			    if ($cod_apto === "") {
			       $cod_cat = $cod_cat."0000-";				 
		      } elseif (strlen($cod_apto) == 1) {
		        $cod_cat = $cod_cat."000".$cod_apto;
		      } elseif (strlen($cod_apto) == 2) {
		         $cod_cat = $cod_cat."00".$cod_apto;		
		      } elseif (strlen($cod_apto) == 3) {
		         $cod_cat = $cod_cat."0".$cod_apto;									 
		      } else $cod_cat = $cod_cat.$cod_apto;
		   }	
		   return $cod_cat;				 	 
		}
 }


function get_tit_1id_from_id_inmu ($id_inmu) {
    $sql="SELECT tit_1id FROM info_inmu WHERE id_inmu = '$id_inmu'";
    $result=pg_query($sql);
    $info = pg_fetch_array($result, null, PGSQL_ASSOC);
    $tit_1id = $info['tit_1id'];
		pg_free_result($result);
		return $tit_1id;
}


function change_date_to_ymd_10char ($fecha) 
{
   $stringlength = strlen($fecha);								 
   $i = $char_pos = 0;
	 $new_fecha = "";	  
	 while ($i <= strlen($fecha)) {	
      $char = substr($fecha, $i-1, 1);
			if ($char == "-") {
			   $separador = "-";
			}
			if ($char == "/") {
			   $separador = "/";
			}
			if (($char == "-") OR ($char == "/") OR ($i == strlen($fecha))) {
         $value = substr($fecha, $char_pos, $i-$char_pos);			   						  

         $value = (int)$value;  
         if ($value < 10) {
				    $value = "0".$value;
				 }
				 if ($new_fecha == "") {
				    $new_fecha = $value;    
				 } else $new_fecha = $new_fecha.$separador.$value;
				 $char_pos = $i;
			}  
			$i++;
   }
   $char = substr($new_fecha, 2, 1);
	 if ($char == "/") {
	    $dia_change = substr($new_fecha, 0, 2);  
	    $mes_change = substr($new_fecha, 3, 2);
	    $ano_change = substr($new_fecha, 6, 4);
			$new_fecha = $ano_change."-".$mes_change."-".$dia_change; 						 
	 }	 

	 return $new_fecha;
}


function imp_get_fecha_venc_1st ($gestion) {
   $sql="SELECT * FROM imp_fecha_venc WHERE gestion = '$gestion'";
   $check_fecha = pg_num_rows(pg_query($sql));
	 if ($check_fecha > 0) {	 
      $result_fecha_venc = pg_query($sql);
      $info_fecha_venc = pg_fetch_array($result_fecha_venc, null, PGSQL_ASSOC);
      $fecha_venc_1st = $info_fecha_venc['fecha_venc'];
			pg_free_result($result_fecha_venc);
			return $fecha_venc_1st;
   } else {
	    return -1;
	 }
} 


function get_tipo_inmu_from_id_inmu ($id_inmu) {
    $sql="SELECT tipo_inmu FROM info_inmu WHERE id_inmu = '$id_inmu'";
    $result=pg_query($sql);
    $info = pg_fetch_array($result, null, PGSQL_ASSOC);
    $tipo_inmu = $info['tipo_inmu'];
			if ($tipo_inmu == "PRE") {	
			   $regimen = "SOLO GEOMETRIA";
			} elseif ($tipo_inmu == "TER") {	
			   $regimen = "TERRENO";
			} elseif ($tipo_inmu == "CAS") {	
			   $regimen = "CASA";	
			} elseif ($tipo_inmu == "RUR") {	
			   $regimen = "PROPIEDAD RURAL";
			} elseif ($tipo_inmu == "PH") {	
			   $regimen = "PROP. HORIZONTAL";	
			} else {
			   $regimen = "-";
			}		 
		return $regimen;
}

function  get_prop1_from_id_predio_rural ($id_predio_rural) {
    $sql="SELECT tit_1id FROM info_predio_rural WHERE id_predio_rural = '$id_predio_rural'";
    $result=pg_query($sql);
    $info = pg_fetch_array($result, null, PGSQL_ASSOC);
    $tit_1id = trim ($info['tit_1id']);
    pg_free_result($result);
    $prop1 = get_contrib_nombre ($tit_1id);
		return $prop1;
}

function get_contrib_tipo ($id_contrib) {
    if ($id_contrib == "") {
		   $con_tipo = "-";
		} else {
       $sql="SELECT con_tipo FROM contribuyentes WHERE id_contrib = '$id_contrib'";
       $check = pg_num_rows(pg_query($sql));
	     if ($check == 0) {
	        $con_tipo = "-";
	     } else {
	        $result=pg_query($sql);
			    $info = pg_fetch_array($result, null, PGSQL_ASSOC);
          $con_tipo = $info['con_tipo'];
          pg_free_result($result);
					if ($con_tipo == "PER") {
					   $con_tipo = "NATURAL";
					} elseif ($con_tipo == "JUR") {
					   $con_tipo = "JURIDICO";
					} else $con_tipo = "-";
		   }
    }
		return $con_tipo; 
}

function get_contrib_edad($id_contrib) { 
	GLOBAL $fecha;	
	if (($id_contrib == '0') OR ($id_contrib == '') OR ($id_contrib === NULL)) {		
		return "0";    
	} else {
		$sql="SELECT con_fech_nac FROM contribuyentes WHERE id_contrib = '$id_contrib'";
		$result = pg_query($sql);
		$info = pg_fetch_array($result, null, PGSQL_ASSOC);
		$con_fech_nac = trim($info['con_fech_nac']);			 
		pg_free_result($result);
		if (($con_fech_nac == "1900-01-01") OR ($con_fech_nac == "")) {
			return 0;
		} else {		
			$ano_descuento = substr($fecha, 0, 4)-60;
			$mesydia_descuento = substr($fecha, 4, 6);
			$fecha_descuento = $ano_descuento.$mesydia_descuento;
			$date1Timestamp = strtotime($con_fech_nac);
			$date2Timestamp = strtotime($fecha_descuento);

			if ($date1Timestamp <= $date2Timestamp) {
				return 60;		 
			} else {
				return "0";
			}
			
		} 		
	}
}

function get_contrib_dom_dir_nom($id_contrib) {
 		if (($id_contrib == '0') OR ($id_contrib == '') OR ($id_contrib === NULL)) {
		   return "NO DEFINIDO";   
    } else {
       $sql="SELECT dom_nom FROM contribuyentes WHERE id_contrib = '$id_contrib'";
       $result = pg_query($sql);
       $info = pg_fetch_array($result, null, PGSQL_ASSOC);
       $dom_nom = utf8_decode($info['dom_nom']);
       pg_free_result($result);
			 return $dom_nom;
   }
}

function get_material_de_via_alt ($id_inmu) { 
	$cod_geo  = get_cod_geo_from_id_inmu($id_inmu);
	$cod_uv   = get_cod_uv_from_id_inmu($id_inmu);
	$cod_man  = get_cod_man_from_id_inmu($id_inmu);
	$cod_pred = get_cod_pred_from_id_inmu($id_inmu);
	$sql="SELECT via_mat FROM info_predio WHERE cod_geo = '$cod_geo' AND  cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
	#echo "FUNC L2028 $id_inmu, SQL: $sql<br/>";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);		
	$via_mat = trim($info['via_mat']);
	pg_free_result($result);
	if (($via_mat == "") OR ($via_mat == NULL)) {
		   return "0";
    } else return $via_mat;
}


function get_servicio ($id_inmu,$fecha,$servicio) { 
    $sql="SELECT valor_ant FROM cambios WHERE id_inmu = '$id_inmu'
			      AND variable = '$servicio' AND fecha_cambio > '$fecha' ORDER BY fecha_cambio LIMIT 1";
    $check_cambios = pg_num_rows(pg_query($sql)); 

    if ($check_cambios > 0) {
         $result_cambios = pg_query($sql);
         $info_cambios = pg_fetch_array($result_cambios, null, PGSQL_ASSOC);		
         $serv = $info_cambios['valor_ant'];
	       pg_free_result($result_cambios);		
    }	else {		
    	$cod_geo  = get_cod_geo_from_id_inmu($id_inmu);			
		$cod_uv = get_cod_uv_from_id_inmu($id_inmu);
		$cod_man = get_cod_man_from_id_inmu($id_inmu);
		$cod_pred = get_cod_pred_from_id_inmu($id_inmu);
		$sql="SELECT $servicio FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
		$result = pg_query($sql);
		$info = pg_fetch_array($result, null, PGSQL_ASSOC);	
		$serv = $info[$servicio];
		pg_free_result($result);
		if ($serv == "") { 
			$serv = "NO"; 	 
		}
	}
		return $serv;
}

?>