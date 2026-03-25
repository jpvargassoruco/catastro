<?php

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
#--------------------------- TITULOS SEGUN RUBRO ------------------------------#
################################################################################		
	 if ($mod == 1) {
	    $pag = 5;
	    $var_submit = "id_inmu";
	    $titulo1 = "Código";
	    $titulo2 = "Propietario";
	    $titulo3 = "Dirección";
   } elseif ($mod == 41) {
	    $pag = 44;
		$var_submit = "id_predio_rural";
	    $titulo1 = "Código";
	    $titulo2 = "Nombre";
	    $titulo3 = "Propietario/Representante";			
   } elseif ($mod == 101) {
	    $pag = 103;
		$var_submit = "act_pat";
	    $titulo1 = "No. Patente";
	    $titulo2 = "Razon Social";
	    $titulo3 = "Propietario/Representante";
   } elseif ($mod == 111) {
	    $pag = 113;
		$var_submit = "veh_plc";	 
	    $titulo1 = "No. Patente";
	    $titulo2 = "Propietario";
	    $titulo3 = "Razon Social";
   } elseif ($mod == 121) {
	    $pag = 123;
		$var_submit = "con_pmc";	 
	    $titulo1 = "P.M.C.";
	    $titulo2 = "Contribuyente/Razon Social";
	    $titulo3 = "Documentación";
   }  	 
################################################################################
#----------------------------- BUSQUEDA 1 ENVIADO -----------------------------#
################################################################################		 
if ((isset($_POST["busqueda1"])) AND (($_POST["busqueda1"]) == "Buscar")) {
	 $buscar = true;
	 $i = 0;
	if (check_int($cod_uv)) {
		$busqueda_sql[$i] = "cod_uv = '$cod_uv'";
		$i++;
	} else $cod_uv = "";
	if (check_int($cod_man)) {
		$busqueda_sql[$i] = "cod_man = '$cod_man'";
		$i++;
	} else $cod_man = "";	 
	if (check_int($cod_pred)) {
		$busqueda_sql[$i] = "cod_pred = '$cod_pred'";
		$i++;
	} else $cod_pred = "";
	if ($cod_blq != "") {
		$busqueda_sql[$i] = "cod_blq = '$cod_blq'";
		$i++;
	} else $cod_blq = "";
	if ($cod_piso != "") {
		$busqueda_sql[$i] = "cod_piso = '$cod_piso'";
		$i++;
	} else $cod_piso = "";
	if ($cod_apto != "") {
		$busqueda_sql[$i] = "cod_apto = '$cod_apto'";
		$i++;
	} else $cod_apto = "";	 	 

	$no_de_criterios = $i; 
	########################################
	#--------- GENERAR SQL-STRING ---------#
	########################################
	if ($no_de_criterios > 0) {
		$where = $busqueda_sql[0];
		$i = 1;
		while ($i < $no_de_criterios) {
			$where = $where." AND ".$busqueda_sql[$i];
			$i++;
		}
	$sql="SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id
			FROM info_inmu 
			WHERE cod_geo = '$cod_geo' AND $where 
			ORDER BY cod_uv, cod_man, cod_pred";
		########################################
		#---------- CHEQUEAR TABLA ------------#
		########################################
		$check_integrity = pg_num_rows(pg_query($sql)); 				
		if ($check_integrity > 0 ) { 
			$resultado = true;
		} else {
			$error = true;
			$mensaje_de_error = "La búsqueda en la base de datos no tenía resultado."; 
			$resultado = false;
	    }		
	} else {
		$error = true;
		$mensaje_de_error = "Los valores ingresados para el Código no son válidos."; 
	    $resultado = false;				 
	 }	
} else {
   $cod_uv = $cod_man = $cod_pred = $cod_blq = $cod_piso = $cod_apto ="";
}
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
#echo "El char Número $i es un $char <br />\n";	 
				 if (($char == "\\") AND (!$string_entre_comillas)) {
				    $string_entre_comillas = true;
						$init = $init+2;
						$i++;
#echo "Activado String entre Comillas <br />\n";
				 } elseif ($char == "\\") {   				 
            $seg_string[$j] = substr($search_string, $init, $i-$init);
						$seg_string_entre_comillas[$j] = true;	
#echo "Recortado String entre comillas: $seg_string[$j]<br />\n";
						$j++;
						$init = $i+2;
						$string_entre_comillas = false;
						$i = $i+2;
				 } elseif ((($char == "+") OR ($char == "")) AND (!$string_entre_comillas)) {
				    $char_ant = trim(substr($search_string, $i-1, 1));
				    if (($char_ant != "") AND ($char_ant != "+") AND ($char_ant != "\"")) {
				       $seg_string[$j] = substr($search_string, $init, $i-$init);
							 $seg_string_entre_comillas[$j] = false;		
#echo "Recortado $seg_string[$j]<br />\n";
							 $j++;
						}
						$init = $i+1;
				 } 
				 $i++;
		  }
			if (strlen($search_string) == $init+($i-$init)) {
			   $seg_string[$j] = trim(substr($search_string, $init, $i-$init));
#echo "Recortado $seg_string[$j] <br />\n";				 
				 $seg_string_entre_comillas[$j] = false;
				 $j++;
		  }
#			$j--;
	    ########################################
	    #      CHEQUEAR TIPO DE STRING         #
	    ########################################	
			$i = $k = 0;
			$new_search_string = "";	
			while ($k < $j) {
			   $search_string = $seg_string[$k];
				 if ((substr($search_string, 2, 1) == "-") AND (substr($search_string, 5, 1) == "-") AND (strlen($search_string) == 9)) {
#echo "STRING $k ES UN COD_CAT !!! <br />\n";				    
						$search_type = "codcat";
						$busqueda_sql[$k] = "cod_cat = '$search_string'"; 				
				 } elseif ((substr($search_string, 2, 1) == "_") AND (substr($search_string, 5, 1) == "_") AND (strlen($search_string) == 9)) {
#echo "STRING $k ES COD_CAT, PERO USUARIO USO '_' EN VEZ DE '-' !!! <br />\n";	
				    $search_type = "codcat";
						$search_string = str_replace ("_", "-" , $search_string);
						$busqueda_sql[$k] = "cod_cat = '$search_string'";
				 } elseif (((substr($search_string, 1, 1) == "-") OR (substr($search_string, 1, 1) == "_")) AND (strlen($search_string) == 3)) {
#echo "STRING $k ES cod_uv + COD_MAN !!! <br />\n";
            $search_type = "codcat";
						$texto1 = substr($search_string, 0, 1);
						$texto2 = substr($search_string, 2, 1);
						$busqueda_sql[$k] = "cod_uv = '$texto1' AND cod_man = '$texto2'";
				 } elseif (((substr($search_string, 1, 1) == "-") OR (substr($search_string, 1, 1) == "_")) AND (strlen($search_string) == 4)) {
#echo "STRING $k ES cod_uv + COD_MAN !!! <br />\n";
            $search_type = "codcat";
						$texto1 = substr($search_string, 0, 1);
						$texto2 = substr($search_string, 2, 2);
						$busqueda_sql[$k] = "cod_uv = '$texto1' AND cod_man = '$texto2'";
				 } elseif (((substr($search_string, 2, 1) == "-") OR (substr($search_string, 2, 1) == "_")) AND (strlen($search_string) == 5)) {
#echo "STRING $k ES cod_uv + COD_MAN !!! <br />\n";
            $search_type = "codcat";
						$texto1 = (int) substr($search_string, 0, 2);
						$texto2 = (int) substr($search_string, 3, 2);
						$busqueda_sql[$k] = "cod_uv = '$texto1' AND cod_man = '$texto2'";
				 } elseif (check_int($search_string)) {
#echo "STRING $k SON SOLO NUMEROS !!! <br />\n";
            $search_type = "numeros";	
            if ($search_string > 100000) {
               $busqueda_sql[$k] = "tit_1ci ~* '$search_string' OR tit_2ci ~* '$search_string'";	 
				    } elseif ($search_string == 24) {					
						   $busqueda_sql[$k] = "cod_geo = '$cod_geo'";
						} elseif ((strlen($search_string) == 1) OR (strlen($search_string) == 2)) {
						   if (strlen($search_string) == 1) {
							    $search_string_largo = "0".$search_string; 
							 } else $search_string_largo = $search_string;
						   $busqueda_sql[$k] = "cod_uv = '$search_string' OR cod_uv = '$search_string_largo' OR cod_man = '$search_string' OR cod_man = '$search_string_largo'";
						} else {
						   $busqueda_sql[$k] = "cod_pred = '$search_string'";
						}		
				 } elseif ((strlen($search_string) > 3) AND ((substr($search_string, strlen($search_string)-3, 3) == "/01") 
				           OR (substr($search_string, strlen($search_string)-3, 3) == "/02") OR (substr($search_string, strlen($search_string)-3, 3) == "/03"))) {
#echo "STRING $k ES EL PADRON MUNICIPAL !!! <br />\n";	
            $search_type = "numeros";         
		        $busqueda_sql[$k] = "cod_pad ~* '$search_string'";
				 }  elseif ((strpos($search_string,"c/") !== false) OR (strpos($search_string,"C/") !== false) OR 
				            (strpos($search_string,"Av/") !== false) OR (strpos($search_string,"AV/") !== false)) {
#echo "STRING $k ES UNA DIRECCION !!! <br />\n";
            $search_type = "direccion";
            $pos = strpos($search_string,"/")+1;
            $texto = substr($search_string, $pos, strlen($search_string)-$pos);	          
						$busqueda_sql[$k] = "dir_nom ~* '$texto'";		 
				 } elseif ((strpos($search_string,"Calle") !== false) OR (strpos($search_string,"Avenida") !== false)
				   OR (strpos($search_string,"Av.") !== false) OR (strpos($search_string,"AV.") !== false)) {
#echo "STRING $k ES UNA DIRECCION !!! <br />\n";
            $search_type = "direccion";
            $pos = strpos($search_string," ")+1;
						if ($pos == 1) {
						   $search_string == "";							 							 
						   $busqueda_sql[$k] = "cod_geo = '$cod_geo'";   
						} else {
						   $texto1 = substr($search_string, 0, $pos-1);			
               $texto2 = substr($search_string, $pos, strlen($search_string)-$pos);      
						   $busqueda_sql[$k] = "dir_nom ~* '$texto2'";
						}
		 
				 } elseif (($search_string == "Calle") OR ($search_string == "calle") OR ($search_string == "avenida") OR ($search_string == "Avenida") 
				           OR ($search_string == "pasillo") OR ($search_string == "Pasillo") OR ($search_string == "plaza") OR ($search_string == "Plaza")){
#echo "STRING $k ES UNA DIRECCION 2 !!! <br />\n";
            $search_type = "direccion";
            $pos = strpos($search_string," ")+1;
						$texto1 = substr($search_string, 0, $pos-1);			
            $texto2 = substr($search_string, $pos, strlen($search_string)-$pos);      
						$busqueda_sql[$k] = "dir_nom ~* '$texto2'";

				 } else {
#echo "STRING $k ES UN NOMBRE  !!! <br />\n";
            $search_type = "nombre";					 
				    if (strpos($search_string," ") !== false) {
	             $pos = strpos($search_string," ")+1;
							 #$busqueda_columna[$i] = "tit_1nom1";
						   $texto1 = substr($search_string, 0, $pos-1);		
               $texto2 = substr($search_string, $pos, strlen($search_string)-$pos);		
							 						 					
						   $textconv1 = textconvert($texto1);
							 $textconv2 = textconvert($texto2);
						 #  $search_string = "'".$textconv1." ".$textconv2."'";	
							 $busqueda_sql[$k] = "(con_nom1 ~* '$texto1' AND con_pat ~* '$texto2') 
										 OR (con_pat ~* '$texto1' AND con_mat ~* '$texto2')";	
							# $busqueda_sql_trans[$k] = "(tan_1nom1 ~* '$texto1' AND tan_1pat ~* '$texto2') 
							#			 OR (tan_1pat ~* '$texto1' AND tan_1mat ~* '$texto2')";										 
            }	else {	
							 $busqueda_sql[$k] = "con_pat ~* '$search_string' OR con_mat ~* '$search_string' OR con_nom1 ~* '$search_string' OR con_nom2 ~* '$search_string'";	
							# $busqueda_sql_trans[$k] = "tan_1pat ~* '$search_string' OR tan_1mat ~* '$search_string' OR tan_1nom1 ~* '$search_string' OR tan_1nom2 ~* '$search_string'";	
						}	    
				 }
				 $new_search_string[$k] = $search_string;
				 $k++;	 			 	
			}
			$no_de_criterios = $k;
#echo "J es: $j, K es: $k, CRITERIOS: $no_de_criterios <br />\n";
			########################################
	    #        PREPARAR SEARCH-STRING        #
	    ########################################	
			$search_string = "";
			$i = 0;		
			while ($i < $j) {
			   if ($seg_string_entre_comillas[$i]) {
				    $search_string = trim($search_string." '".$new_search_string[$i]."' "); 
				 } else {
						$search_string = trim($search_string." ".$new_search_string[$i]); 
				 } 						
				 $i++;
			}
			$search_string = textconvert($search_string);
#echo "SEARCH-STRING: $search_string <br />\n";

	    ########################################
	    #---------- GENERAR BUSQUEDA ----------#
	    ########################################
      $where = $busqueda_sql[0];
				# $where_trans = $busqueda_sql_trans[0];
			$i = 1;
			$k = 1;
			while ($i < $no_de_criterios) {
				 $where = "(".$where.") AND (".$busqueda_sql[$i].")";
						#$where_trans = "(".$where_trans.") AND (".$busqueda_sql_trans[$i].")";
				 $i++;
			}
	    ########################################
	    #------- BUSCAR STRING NOMBRE ---------#
	    ########################################
			if ($search_type == "nombre") {							 
#echo "WHERE-Option: $where <br />\n";				 
				 $sql="SELECT id_contrib FROM contribuyentes WHERE $where ORDER BY id_contrib";
				 $check_contrib = pg_num_rows(pg_query($sql));			 
				 if ($check_contrib == 1) {
#echo "1 RESULTADO <br />\n";	
           	if ($mod == 1) {   ### CATASTRO URBANO		 
               $result=pg_query($sql);
               $info = pg_fetch_array($result, null, PGSQL_ASSOC);
               $id_contrib = $info['id_contrib'];				 
				 		   pg_free_result($result);
				       $sql="SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id 
			         FROM info_inmu WHERE cod_geo = '$cod_geo' AND (tit_1id = '$id_contrib' OR tit_2id = '$id_contrib') ORDER BY cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto";						
               $where_trans = "cod_geo = '$cod_geo' AND (tan_1id = '$id_contrib' OR tan_2id = '$id_contrib')";
				    } elseif ($mod == 121) {   ### CONTRIBUYENTES
				       $sql="SELECT id_contrib, con_pmc, con_raz, con_nom1, con_nom2, con_pat, con_mat, 
	                   doc_tipo, doc_num, doc_exp FROM contribuyentes WHERE $where ORDER BY id_contrib";						
						}
				 } elseif ($check_contrib > 1) {
#echo "MAS QUE 1 RESULTADO <br />\n";				 
					  if ($mod == 1) {   ### CATASTRO URBANO
						   $result=pg_query($sql);
						   $where = $where_trans = "cod_geo = '$cod_geo' AND ("; 
						   $i = 0; 
               while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {      
				          foreach ($line as $col_value) {			
	                   if ($i == 0) {
						            $where = $where."tit_1id = '".$col_value."'";
										    $where_trans = $where_trans."tan_1id = '".$col_value."'";
			                  $i++; 
                     } else {
			                  $where = $where." OR tit_1id ='".$col_value."'";
										    $where_trans = $where_trans." OR tan_1id ='".$col_value."'";
                     }	 
                  }
               } # END_OF_WHILE
						   $where = $where.")";
						   $where_trans = $where_trans.")";
#echo "WHERE-Option: $where <br />\n";
				       $sql="SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id 
			            FROM info_inmu WHERE $where ORDER BY cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto";	
				    } elseif ($mod == 121) {   ### CONTRIBUYENTES
				       $sql="SELECT id_contrib, con_pmc, con_raz, con_nom1, con_nom2, con_pat, con_mat, 
	                   doc_tipo, doc_num, doc_exp FROM contribuyentes WHERE $where ORDER BY id_contrib";						
						}					
			   } else {
				    $sql = "SELECT id_inmu FROM info_inmu WHERE id_inmu = '-1'";
						$where_trans = "id_inmu = '-1'";
#echo "ELSE ! <br />\n";				 
				 }				  
         $check_integrity = pg_num_rows(pg_query($sql)); 
	       ##### CHEQUEAR TABLA TRANSFER #####
         if ($check_integrity > 0 ) {	 
	          $resultado = true;
         } else {
#echo "WHERE_TRANS-Option: $where_trans <br />\n";	
				    $sql="SELECT DISTINCT id_inmu FROM transfer WHERE $where_trans ORDER BY id_inmu";
#echo "SQL: $sql <br />\n";								 
				    $check_id = pg_num_rows(pg_query($sql));
				    if ($check_id == 1) {
               $result=pg_query($sql);
               $info = pg_fetch_array($result, null, PGSQL_ASSOC);
               $id_inmu = $info['id_inmu'];				 
				 		   pg_free_result($result);
				       $sql="SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id 
			         FROM info_inmu WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' ORDER BY cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto";						
				    }	
				 }			
			########################################
	    #------ BUSCAR STRING DIRECCION -------#
	    ########################################
      } elseif ($search_type == "direccion") {		
				 $sql="SELECT cod_uv, cod_man, cod_pred FROM predios WHERE $where ORDER BY cod_uv, cod_man, cod_pred";
#echo "SQL_DIR: $sql <br />\n";				 
				 $check_inmu = pg_num_rows(pg_query($sql));
				 if ($check_inmu == 1) {
#echo "1 RESULTADO <br />\n";						 		 			
            $result=pg_query($sql);
            $info = pg_fetch_array($result, null, PGSQL_ASSOC);
            $cod_uv = $info['cod_uv'];
						$cod_man = $info['cod_man'];
						$cod_pred = $info['cod_pred'];				 
				 		pg_free_result($result);
				    $sql="SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id 
			            FROM info_inmu WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' 
									ORDER BY cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto";									
			   } elseif ($check_inmu > 1) {
				    $result=pg_query($sql);	
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
			                  $where = "(cod_geo = '$cod_geo' AND cod_uv = '".$cod_uv_temp."' AND cod_man = '".$cod_man_temp."' AND cod_pred = '".$cod_pred_temp."')";
												$j++;
										 } else {
			                  $where = $where." OR (cod_geo = '$cod_geo' AND cod_uv = '".$cod_uv_temp."' AND cod_man = '".$cod_man_temp."' AND cod_pred = '".$cod_pred_temp."')";								 
										 }
										 $i = -1;
                  }
									$i++;	 
               }
            } # END_OF_WHILE
				    $sql="SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id 
			            FROM info_inmu WHERE $where ORDER BY cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto";	
#echo "SQL_DIR: $sql <br />\n";														
			   } else {
				    $sql = "SELECT id_inmu FROM info_inmu WHERE id_inmu = '-1'";
#echo "ELSE ! <br />\n";				 
				 }	
			}
      $check_integrity = pg_num_rows(pg_query($sql)); 			
			########################################
	    #           STRING ENCONTRADO          #
	    ########################################			
			if ($check_integrity > 0 ) { 
			   $resultado = true;
			} else {
		     $error = true;
			   $mensaje_de_error = "La búsqueda en la base de datos no tenía resultado"; 
	       $resultado = false;
	    }			
   } # END_OF_ELSE (search_string != "")			  	 
}
################################################################################
#----------------------------- BUSQUEDA 3 ENVIADO -----------------------------#
################################################################################		 
if ((isset($_POST["busqueda3"])) AND (($_POST["busqueda3"]) == "Listado Completo")) {
    $buscar = true;
    if ($mod == 1) {   ### CATASTRO URBANO
        $pag = 5;
        $var_submit = "id_inmu";
        $sql="SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id 
                FROM info_inmu 
                WHERE cod_geo = '$cod_geo' 
                ORDER BY cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto";
    } elseif ($mod == 41) {   ### CATASTRO RURAL ### 
        $pag = 44;
        $var_submit = "id_predio_rural";
        $sql="SELECT id_predio_rural, cod_geo, cod_poly, cod_predio, nom_pred, tit_1id FROM info_predio_rural ORDER BY nom_pred";						
   } elseif ($mod == 101) {   ### PATENTES ###
	    $pag = 103;
			$var_submit = "id_patente";
			$sql="SELECT id_patente, id_contrib, id_inmu, act_pat, act_raz FROM patentes WHERE cod_geo = '$cod_geo' ORDER BY act_pat";
   } elseif ($mod == 111) {   ### VEHICULOS
	    $pag = 113;
			$var_submit = "veh_plc";	 
			$sql="SELECT cod_uv, cod_man, cod_pred, veh_1nom1, veh_1nom2, veh_1pat, veh_1mat, 
	            veh_plc, veh_mrc, veh_mod FROM vehic WHERE cod_geo = '$cod_geo' ORDER BY veh_plc";
   } elseif ($mod == 121) {   ### CONTRIBUYENTES
	    $pag = 123;
			$var_submit = "id_contrib"; 
			$sql="SELECT id_contrib, con_pmc, con_raz, con_nom1, con_nom2, con_pat, con_mat, 
	            doc_tipo, doc_num, doc_exp FROM contribuyentes ORDER BY con_pat, con_mat, con_nom1, con_nom2 ASC";
   }  
}	 
################################################################################
#------------------------------ RELLENAR ARRAYS -------------------------------#
################################################################################	
if ($buscar) {
	 $filas = pg_num_rows(pg_query($sql));
	 $result=pg_query($sql);
	 $i = $j = $k = 0;		
	 $m = 25;
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
        foreach ($line as $col_value) {						
            $value[$i] = $col_value;
            $i++; 
        }
        if ($mod == 1) {   ### CATASTRO URBANO				
            $valor_submit[$j] = $value[0];		
            $valor1[$j] = get_codcat ($value[1],$value[2],$value[3],$value[4],$value[5],$value[6]);	 
            $valor2[$j] = get_contrib_nombre ($value[7]);	
            $valor3[$j] = get_predio_dir ($cod_geo,$value[1],$value[2],$value[3]);	
        } elseif ($mod == 41) {   ### CATASTRO RURAL				 
            $valor_submit[$j] = $value[0];		
            $valor1[$j] = $value[1]."-".$value[2]."-".$value[3];	
            $valor2[$j] = $value[4];				  
            $valor3[$j] = get_contrib_nombre ($value[5]);			 
        } elseif ($mod == 101) {   ### PATENTES
            # "No. Patente";"Razon Social";"Propietario";
            $valor_submit[$j] = $value[0];
            $valor1[$j] = $value[3];
            $valor2[$j] = $value[4];
            $valor3[$j] = get_contrib_nombre ($value[1]);				
        } elseif ($mod == 111) {   ### VEHICULOS
            $valor_submit[$j] = $value[7];
            $valor1[$j] = $value[7];
            $valor2[$j] = $propietario;
            $texto1 = $value[8];
            $texto2 = $value[9];				 
            $valor3[$j] = $texto1." ".$texto2;					
        } elseif ($mod == 121) {   ### CONTRIBUYENTES
            $valor_submit[$j] = $value[0];
            $valor1[$j] = $value[1];
            $texto1 = $value[3];
            $texto2 = $value[4];		 
            $texto3 = strtoupper (ucase($value[5]));
            $texto4 = strtoupper (ucase($value[6]));					
            $propietario = $texto3." ".$texto4.", ".$texto1." ".$texto2;					 
            $valor2[$j] = $propietario;
            $texto1 = $value[7];				 
            $texto2 = $value[8];
            $texto3 = $value[9];				 
            $valor3[$j] = $texto1." ".$texto2." ".$texto3;				
        }	 	 	

        $i = 0;
        $j++;
        $k++;			 
    }
    ### CHEQUEAR SI EXISTEN REGISTROS EN LA BASE DE DATOS
    $check_integrity = pg_num_rows(pg_query($sql)); 
    if ($check_integrity > 0 ) {	 
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
echo "<div class=\"row\">\n";	
	echo "<div  class=\"col-0 col-md-1\"></div>\n";
	echo "<div  class=\"col-12 col-md-10\">\n";
		if ($mod == 1) {
			echo "<h5 class=\"pageName pt-2\">Buscar Predios</h5>\n"; 
		} elseif ($mod == 41) {
			echo "<h5 class=\"pageName pt-2\">Buscar Propiedad Rural</h5>\n";			
		} elseif ($mod == 101) {
			echo "<h5 class=\"pageName pt-2\">Buscar Patente</h5>\n"; 
		} elseif ($mod == 111) {
			echo "<h5 class=\"pageName pt-2\">Buscar Vehículo</h5>\n";	 
		} elseif ($mod == 121) {
			echo "<h5 class=\"pageName pt-2\">Buscar Contribuyente</h5>\n";	 			 	 
		}
	echo "</div>\n";  
	echo "<div  class=\"col-0 col-md-1\"></div>\n";
echo "</div>\n";  	 	 



echo "<div class=\"row\">\n"; 
	echo "<div  class=\"col-0 col-md-1\"></div>\n";


	echo "<div  class=\"col-12 col-md-10\">\n";
	echo "<fieldset class=\"border p-2\" ><legend class=\"float-none w-auto px-3\">Ingrese el atributo que quiere buscar</legend>\n";
	if ($mod == 41) {
		echo "<form name=\"isc\" method=\"post\" action=\"index.php?mod=43&id=$session_id\" accept-charset=\"utf-8\">\n"; 
		echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";
		echo "<tr>\n";
		echo "<td> &nbsp</td>\n"; #TCol. 1
		echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Polígono</td>\n";						
		echo "<td align=\"left\" colspan=\"3\" class=\"bodyTextD\">Parcela</td>\n";             
		echo "</tr>\n";  	 
		echo "<tr>\n";
		echo "<td width=\"10%\"> &nbsp</td>\n"; #TCol. 1
		echo "<td align=\"left\" width=\"10%\" class=\"bodyTextD\">\n"; #TCol. 2
		echo "<input name=\"cod_pol\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_pol\" value=\"$cod_pol\">\n";
		echo "</td>\n";
		echo "<td width=\"2%\"> &nbsp</td>\n"; #TCol. 3			
		echo "<td align=\"left\" width=\"10%\" class=\"bodyTextD\">\n"; #TCol. 4
		echo "<input name=\"cod_par\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_par\" value=\"$cod_par\">\n";
		echo "</td>\n";		
		echo "<td width=\"2%\"> &nbsp</td>\n"; #TCol. 5					
		echo "<td width=\"66%\">\n";  #TCol. 6				
		echo "<input name=\"busqueda1\" type=\"submit\" class=\"smallText\" value=\"Buscar\" onClick=\"go()\">\n";			
		echo "</td>\n";		
		echo "</tr>\n";  
		echo "</table>\n";		
		echo "</form>\n";	 
	} elseif ($mod == 101) {
		echo "<form name=\"isc\" method=\"post\" action=\"index.php?mod=103&id=$session_id\" accept-charset=\"utf-8\">\n"; 
		echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";
		echo "<tr>\n";
		echo "<td> &nbsp</td>\n"; #TCol. 1
		echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Número de Patente\n";
		echo "</td>\n";		
		echo "</tr>\n";  	 
		echo "<tr>\n";
		echo "<td width=\"10%\"> &nbsp</td>\n"; #TCol. 1
		echo "<td align=\"left\" width=\"30%\" class=\"bodyTextD\">\n";
		echo "<input name=\"act_pat\" type=\"text\" class=\"navText\" value=\"$act_pat\">\n";
		echo "</td>\n";
		echo "<td width=\"60%\">\n";
		echo "<input name=\"busqueda\" type=\"submit\" class=\"smallText\" value=\"Buscar\" onClick=\"go()\">\n";			
		echo "</td>\n"; #TCol. 1			
		echo "</tr>\n";  
		echo "</table>\n"; #TCol. 1			
		echo "</form>\n";	 
	} elseif ($mod == 111) {
		echo "<form name=\"isc\" method=\"post\" action=\"index.php?mod=113&id=$session_id\" accept-charset=\"utf-8\">\n"; 
		echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";
		echo "<tr>\n";
		echo "<td> &nbsp</td>\n"; #TCol. 1
		echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Número de Placa\n";
		echo "</td>\n";		
		echo "</tr>\n";  	 
		echo "<tr>\n";
		echo "<td width=\"10%\"> &nbsp</td>\n"; #TCol. 1
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
		echo "<form name=\"isc\" method=\"post\" action=\"index.php?mod=123&id=$session_id\" accept-charset=\"utf-8\">\n"; 
		echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";  # 
		echo "<tr>\n";
		echo "<td> &nbsp</td>\n"; #TCol. 1
		echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Padrón Municipal (PMC)\n";
		echo "</td>\n";		
		echo "</tr>\n";  	 
		echo "<tr>\n";
		echo "<td width=\"10%\"> &nbsp</td>\n"; #TCol. 1
		echo "<td align=\"left\" width=\"25%\" class=\"bodyTextD\">\n";
		echo "<input name=\"con_pmc\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_pmc\" value=\"$con_pmc\">\n";
		echo "</td>\n";
		echo "<td width=\"65%\">\n";
		echo "<input name=\"busqueda\" type=\"submit\" class=\"smallText\" value=\"Buscar\" onClick=\"go()\">\n";			
		echo "</td>\n"; #TCol. 1			
		echo "</tr>\n";  
		echo "</table>\n"; #TCol. 1			
		echo "</form>\n";					 
	} else {
		?>
		<form name="isc" method="post" action="index.php?mod=<?php echo "4&id=$session_id"; ?>" accept-charset="utf-8">
			<div class="row">
				<div class="col-0 col-md-2"></div>

				<div class="col-4  col-md-1">
					<input name="cod_uv" type="text" class="form-control" maxlength="$max_strlen_uv" placeholder="<?php echo "$uv_dist"; ?>"  value="<?php echo $cod_uv;?>">
					<label for="floatingInput"><?php echo "$uv_dist"; ?></label>
				</div>
				<div class="col-4  col-md-1">
					<input name="cod_man" type="text" class="form-control" maxlength="$max_strlen_man" placeholder="Mz."  value="<?php echo $cod_man;?>">
					<label for="floatingInput"><?php echo "Mz."; ?></label>
				</div>
				<div class="col-4 col-md-1">
					<input name="cod_pred" type="text" class="form-control" maxlength="$max_strlen_pred"  placeholder="Pred." value="<?php echo $cod_pred;?>">
					<label for="floatingInput"><?php echo "Pred."; ?></label>
				</div>
				<div class="col-4 col-md-1">
					<input name="cod_blq" type="text" class="form-control" maxlength="$max_strlen_blq"  placeholder="Blq."  value="<?php echo $cod_blq;?>">
					<label for="floatingInput"><?php echo "Blq."; ?></label>
				</div>                       
				<div class="col-4 col-md-1">
					<input name="cod_piso" type="text" class="form-control" maxlength="$max_strlen_piso"  placeholder="Piso" value="<?php echo $cod_piso;?>">
					<label for="floatingInput"><?php echo "Piso"; ?></label>
				</div>
				<div class="col-4 col-md-1">
					<input name="cod_apto" type="text" class="form-control" maxlength="$max_strlen_apto"  placeholder="Apto." value="<?php echo $cod_apto;?>">
					<label for="floatingInput"><?php echo "Apto."; ?></label>
				</div>
				<div class="col-12 col-md-2">
					<input name="old_example" type="hidden" class="form-control" value="$example">
					<input name="old_stage2" type="hidden" class="form-control" value="$stage2">
					<input name="busqueda1" type="submit" class="form-control" value="Buscar" onClick="go()">
				</div> 

				<div class="col-0  col-md-2"></div>   
			</div>
		</form>
		
		
<?php 
	} 

if ($mod == 1) {	 
	$titulo_bus ="Nombre, Apellido, No. de Carnet, PMC y/o Dirección";
} elseif ($mod == 41) {
	$titulo_bus="Nombre de la Propiedad o del Propietario";	
} elseif ($mod == 101) {
	$titulo_bus="Razón Social, Propietario o NIT";
} elseif ($mod == 111) {
	$titulo_bus="Nombre de Propietario o No. de Carnet";
} elseif ($mod == 121) {
	$titulo_bus="Nombre del Contribuyente o No. de Carnet";
}
?>
	
	<form name="isc" method="post" action="index.php?mod=<?php echo "$mod"; ?>&id=<?php echo "$session_id";?>" accept-charset="utf-8">
		<div class="row pt-3">
			<div class="col-0 col-md-2"></div>
			<div class="col-12  col-md-6">
				<input name="search_string" type="text" class="form-control" placeholder="<?php echo $titulo_bus;?>"  value="<?php echo $search_string;?>">
				<label for="floatingInput"></label>
			</div>
			<div class="col-12  col-md-2">
				<input name="busqueda2" type="submit" class="form-control" value="Buscar" onClick="go()">
			</div>		
			<div class="col-0 col-md-2"></div>
		</div>	
	</form>

<?php

	if ($error) {
		
		echo "<div class=\"row pt-4 p-2\">\n";
			echo "<div class=\"col-0 col-md-2\"></div>\n";
			echo "<div class=\"col-12  col-md-8 alert alert-danger\" role=\"alert\">$mensaje_de_error</div>\n";
			echo "<div class=\"col-0 col-md-2\"></div>\n";
		echo "</div>\n";		

	



		
	} elseif ($buscar AND $resultado) {	
		

	echo "<div class=\"row\">\n";
		echo "<div class=\"col-lg-12 col-md-12\">\n";
			echo "<div class=\"table-responsive\">\n";
				echo "<div id=\"table-wrapper\" class=\"dataTables_wrapper dt-dootstrap5 no-footer\" >\n";
					echo "<div id=\"row\">\n";			
						echo "<div class=\"col-lg-6 col-md-6 col-sm-12\">\n";
							echo "<div id=\"table_length\" class=\"dataTables_length\" >\n";
								echo "<label>Mostrar</label>\n";
								echo "<select name=\"table_length\" class=\"custom-select custom-select-sm form-control-sm\">\n";
								echo "<option value=\"10\">10</option>\n";
								echo "<option value=\"25\">25</option>\n";
								echo "<option value=\"50\">50</option>\n";
								echo "<option value=\"100\">100</option>\n";
								echo "</select>\n";
								echo "<label>Registros</label>\n";
						echo "</div>\n";
						echo "<div class=\"col-lg-6 col-md-6 col-sm-12\">\n";
						echo "</div>\n";
					echo "</div>\n";	

					echo "<div id=\"row\">\n";		
						echo "<div class=\"col-lg-12 col-md-12 col-sm-12\">\n";
							echo "<table class=\"table table-striped table-bordered dataTable no-footer\" id=\"table\" role=\"grid\" aria-describedby=\"table_info\">\n";
								echo "<thead class=\"table-primary\">\n";
									echo "<tr role=\"row\">\n";
										echo "<th class=\"sorting_asc\" tabindex=\"0\" aria-controls=\"table\" rowspan=\"1\" colspan=\"1\"
											style=\"width: 80.5167px;\" aria-sort=\"ascending\"
											aria-label=\"ID: Activar orden de columna desendente\">CODIGO</th>\n";
										echo "<th class=\"sorting\" tabindex=\"0\" aria-controls=\"table\" rowspan=\"1\" colspan=\"1\"
											style=\"width: 277.5px;\"
											aria-label=\"NOMBRE: Activar orden de columna ascendente\">PROPIETARIO</th>\n";
										echo "<th class=\"sorting\" tabindex=\"0\" aria-controls=\"table\" rowspan=\"1\" colspan=\"1\"
											style=\"width: 409.617px;\"
											aria-label=\"CORREO: Activar orden de columna ascendente\">DIRECCION</th>\n";
										echo "<th class=\"sorting\" tabindex=\"0\" aria-controls=\"table\" rowspan=\"1\" colspan=\"1\"
											style=\"width: 208.5px;\"
											aria-label=\"ACCIONES: Activar orden de columna ascendente\">ACCIONES</th>\n";
									echo "</tr>\n";
								echo "</thead>\n";
							echo "<tbody>\n";

                            $i = $j = $k = 0;		
                            $m = 25;
                            $show_color = false;
                                while ($j < $filas) { 
                                    echo "<tr>\n";
                                    echo "<td> $valor1[$j]</td>\n";
                                    echo "<td> $valor2[$j]</td>\n";
                                    echo "<td> $valor3[$j]</td>\n";
                                                   echo "<td>\n";
                                        echo "<a href=\"editar_usuario.php?id=$data['idusuario']\" class=\"btn btn-success\"><i class=\"fas fa-edit\"></i> Editar</a>\n";
                                        echo "<form action=\"eliminar_usuario.php?id=$data['idusuario']\" method=\"post\" class=\"confirmar d-inline\">\n";
                                        echo "<button class=\"btn btn-danger\" type=\"submit\"><i class=\"fas fa-trash-alt\"></i> </button>\n";
                                        echo "</form>\n";
                                        echo "</td>\n";
                             
                                    echo "</tr>\n";
                                    $j++;
                                }



							echo "</tbody>\n";
							
							echo "</div>\n";	
						echo "</div>\n";
					echo "</div>\n";
	




		echo "<div class=\"table-responsive\">\n";
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=$pag&id=$session_id\" accept-charset=\"utf-8\">\n";	 
		
			echo "<table class=\"table\" >\n";
				echo "<thead class=\"table-primary\">\n";
					echo "<tr>\n";
						echo "<th scope=\"col\" align=\"center\">\n";
						echo "<input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";			
						echo "<input name=\"Submit\" type=\"submit\" class=\"form-control\" id=\"Submit\" value=\"Ver\" />\n";
						echo "</th>\n";
						if ($mod == 41) {							
							echo "<th scope=\"col\"  align=\"center\" >$titulo1</th>\n";
							echo "<th scope=\"col\"  align=\"center\" >$titulo2</th>\n";
						} else {
							echo "<th scope=\"col\"  align=\"center\" >$titulo1</th>\n";
							echo "<th scope=\"col\"  align=\"center\" >$titulo2</th>\n";
						}		

						echo "<th align=\"center\" scope=\"col\" >$titulo3</th>\n";			
					echo "</tr>\n";
				echo "</thead>\n";
				echo "<tbody>\n";
				echo "<tr>\n";
					echo "<td> &nbsp</td>\n";                     
					echo "<td valign=\"top\" class=\"bodyText\">\n"; 
					echo "<div style=\"height:400px; overflow:auto\">\n";				
					echo "<table width=\"100%\" border=\"0\" id=\"registros2\">\n";							
				$i = $j = $k = 0;		
				$m = 25;
				$show_color = false;
				while ($j < $filas) {
					if (!$show_color){
						echo "<tr>\n";
						$show_color = true;
					} else {
						echo "<tr class=\"alt\">\n";	
						$show_color = false;		 
					}   

					if ($j == 0) {	 
						echo "<td align=\"center\"><input name=\"$var_submit\" value=\"$valor_submit[$j]\" type=\"radio\" checked=\"checked\"></td>\n"; 
					} else {
						echo "<td align=\"center\"><input name=\"$var_submit\" value=\"$valor_submit[$j]\" type=\"radio\"></td>\n"; 				 
					}

					echo "<td align=\"center\">$valor1[$j]</td>\n";

					if ($mod == 121) {	
						echo "<td align=\"center\">&nbsp $valor2[$j]</td>\n";
					} else {
						echo "<td align=\"center\">$valor2[$j]</td>\n";
					}

					echo "<td align=\"center\">$valor3[$j]</td>\n";				 				 
					echo "</tr>\n";	
					$j++;
					$k++;
					if (($k == 5525) AND ($filas - $m > 10)) {	
						echo "<tr>\n";
						echo "<td align=\"center\" class=\"bodyTextH\">\n";		
						echo "<input name=\"Submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Ver\" />\n";
						echo "</td>\n";			
						echo "<td align=\"center\" class=\"bodyTextH\">$titulo1</td>\n";
						echo "<td align=\"center\" class=\"bodyTextH\">$titulo2</td>\n";
						echo "<td align=\"center\" class=\"bodyTextH\">$titulo3</td>\n";			
						echo "</tr>\n";
						$m = $m + $k;
						$k = 0;		
					}
				}			
				pg_free_result($result);		
				echo "<tr>\n";
				echo "<td width=\"7%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";	

				if ($mod == 41) {		
					echo "<td width=\"20%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";			
					echo "<td width=\"35%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";
				} else {
					echo "<td width=\"15%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";			
					echo "<td width=\"40%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";
				}
		
		echo "<td width=\"38%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";			
		echo "</tr>\n";	
		echo "</tbody>\n";		
		echo "</table>\n"; 
		echo "</div>\n";									
		echo "</td>\n";
		echo "<td height=\"40\"> &nbsp</td>\n";   #Col. 3   		
		echo "</tr>\n";	
		echo "<tr>\n";  	
		echo "<td> &nbsp</td>\n";   #Col. 1                       
		echo "<td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	
		echo "<table width=\"100%\" border=\"0\">\n";
		echo "<tr>\n";
		echo "<td align=\"center\" width=\"7%\" class=\"bodyTextH\">\n";
		echo "<input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";			
		echo "<input name=\"Submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Ver\" />\n";
		echo "</td>\n";			
		echo "<td align=\"center\" width=\"15%\" class=\"bodyTextH\">$titulo1</td>\n";
		echo "<td align=\"center\" width=\"40%\" class=\"bodyTextH\">$titulo2</td>\n";
		echo "<td align=\"center\" width=\"38%\" class=\"bodyTextH\">$titulo3</td>\n";			
		echo "</tr>\n";
		echo "</table>\n"; 						
		echo "</td>\n";
		echo "<td> &nbsp</td>\n";   #Col. 3   		
		echo "</tr>\n";							  
		echo "</form>\n";	
			
	} elseif ($buscar AND !$resultado) {
		echo "<h3><font color=\"red\">Busqueda sin resultado...</font></h3>\n";	
		echo "<p>Código catastral no existe: $cod_cat,\n";
		echo "el padron municipal: $cod_pad, el nombre del\n";	
		echo "títular: $nombre1 o el \n";
		echo "apellido del titular: $apellido1 en la base de datos</p>\n";	         		 
	}		 

	echo "</div>\n";
	echo "</div>\n";	

?>