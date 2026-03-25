<?php

$guardar = false;
$tabla_edif_rellenada = false;
$anadir_edif = $anadir_acteco = $anadir_vehic = false;
$borrar_edif = false;
$manual = false;
$tabla_rellenada = false;
$error = $error_csv = $error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = $error8 = $error_soe = false;	 
$errornumber = 0;
$errortext = array();
################################################################################
#------------------- INMUEBLES SUBIDOS CON ARCHIVO CSV ------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Subir")) { 
#   if (($_POST["select"]) == "Predios") { 
#   $accion = "AÃ±adir Inmuebles con archivo CSV";
	 $guardar = true;
   include "siicat_upload_csv.php";	
#echo "Error: $error <br />\n";	 
   ########################################
	 #    Verificar cual es el separador    #
	 ########################################	
	 if (!$error) {	
      $handle = fopen("c:/apache/htdocs/tmp/tmp.tmp", "r");
	    $char = fread($handle, 80);
#echo "CHAR es $char\n";
      $pos1 = strpos($char,",");
      $pos2 = strpos($char,";");
#echo "POS1 es $pos1,POS2 es $pos2.\n";		 			                                                   
      if ($pos2 === false) {               #---> USAR CUANDO EL RESULTADO ES VACIO
	       $separador = ",";		
	    } elseif ($pos1 === false) {		
		     $separador = ";";
	    } elseif ($pos1 < $pos2) {			    
		     $separador = ",";
			   $separador_encontrado = true;				
      } else $separador = ";";
	    fclose($handle);
	    ########################################
	    #----- LEER DATOS DE LA TABLA CSV -----#
	    ########################################
	    $handle = fopen("c:/apache/htdocs/tmp/tmp.tmp", "r");
	    $cantidad_de_filas = 0;
      $row = 1;
      $x = 0;
			if (($_POST["select"]) == "Predios") {
			   $col_check = $excel_cols;      #---> VARIABLE excel_cols está definido en siicat_constants.php
      } elseif (($_POST["select"]) == "Edificaciones") {			
			   $col_check = $excel_cols_edif;
      } elseif (($_POST["select"]) == "Actividades") {			
			   $col_check = $excel_cols_acteco;
      } elseif (($_POST["select"]) == "VehÃ­culos") {			
			   $col_check = $excel_cols_vehic;
			}				 				 	
      while (($data = fgetcsv($handle, 1000, $separador)) !== FALSE) {
         $num = count($data);
#echo "<p> $num fields in line $row: <br /></p>\n";
			   if ($num != $col_check) {    
#echo "<p> $num fields in line $row: <br /></p>\n";				    
			      $error = $error_csv = true;
						#$errornumber = 1;
				    $mensaje_de_error = "Error: El número de columnas en el archivo EXCEL actualmente es $num pero el sistema requiere $col_check. Por favor, revise su archivo EXCEL!"; 
						$errortext[$errornumber] = "Error: El número de columnas en fila $row actualmente es $num pero el sistema requiere $col_check. Por favor, revise su archivo EXCEL!";
            $errornumber++;	        
				 }
         $row++;
         if ($data[0] == $cod_geo) {
			      for ($c=0; $c < $num; $c++) {
						   if ($x == 0 ) {
                  $first_data_line = $row-1;							 
							 }
#echo $data[$c] . "<br />\n";
					     $valores_predio[$x]= utf8_encode($data[$c]);
#echo $valores_predio[$x] . "<br />\n";
					     $x++;
            }
			      $cantidad_de_filas++;
			   }
      }
      fclose($handle);
#echo "<p> CANTIDAD DE FILAS: $cantidad_de_filas, PRIMERA LINEA CON DATOS: $first_data_line<br /></p>\n";
   }
#echo "Error: $error, $mensaje_de_error <br />\n";
   #############################################################################
   #--------------- INGRESAR PREDIOS CSV EN LA BASE DE DATOS-------------------#
   #############################################################################
   if (($_POST["select"]) == "Predios") { 
	    $id_inmu = 0; 
      $accion = "AÃ±adir Inmuebles con archivo CSV";	
include "siicat_set_values_contribuyentes.php";			 
      if (!$error) {		
		     $numero_de_elementos_en_el_string = $x+1;
		     $i = $j = $k = 0;
		     $pos_cod_uv = 1;$pos_cod_man = 2;$pos_cod_pred = 3;$pos_cod_blq = 4;$pos_cod_piso = 5;$pos_cod_apto = 6;		
#echo "    Cantidad_de_filas: $cantidad_de_filas<br /> \n";			
         while ($j < $cantidad_de_filas) {	
				    $error_fila = $j+$first_data_line;
	          $cod_uv = trim ($valores_predio[$pos_cod_uv]);
	          $cod_man = trim ($valores_predio[$pos_cod_man]);
	          $cod_pred = trim ($valores_predio[$pos_cod_pred]);						
	          $cod_blq = trim ($valores_predio[$pos_cod_blq]);
	          $cod_piso = trim ($valores_predio[$pos_cod_piso]);
	          $cod_apto = trim ($valores_predio[$pos_cod_apto]);		
	          ########################################
	          #----------- CHECK CODIGOS ------------#
	          ########################################
						if ((strlen($cod_uv)> $max_strlen_uv) OR (strlen($cod_man)> $max_strlen_man) OR (strlen($cod_pred)> $max_strlen_pred) OR
						   (strlen($cod_blq)> $max_strlen_blq) OR (strlen($cod_piso)> $max_strlen_piso) OR (strlen($cod_apto)> $max_strlen_apto)) {
			         $error = true;
						   $errortext[$errornumber] = "Error en la codificación de la fila $error_fila. Un código excede los digitos permitidos!";
               $errornumber++;						
            }
						########################################
	          #---------- GENERAR CODIGO ------------#
	          ########################################	
				    $cod_cat = get_codcat ($cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto);
#echo "    Cod_cat: $cod_cat, J: $j<br /> \n";						
	          ########################################
	          #---- CHECK SI PREDIO ESTA INACTIVO ---#
	          ########################################				 	
				    $sql="SELECT cod_uv FROM info_predio WHERE activo = '0' AND cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
            $check_predios = pg_num_rows(pg_query($sql));		 
            ### CHEQUEAR SI EXISTE UNA ENTRADA EN INFO_INMU Y SI CONTIENE DATOS
			      $sql="SELECT id_inmu FROM info_inmu WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND cod_blq = '$cod_blq' AND cod_piso = '$cod_piso' AND cod_apto = '$cod_apto'";
            $check = pg_num_rows(pg_query($sql));
				   # if ($check > 0) {
			     #    $error = true;
				   #    #$error_fila = $j+6;
						#   $errortext[$errornumber] = "Error en la columna E de la fila $error_fila. Ya existen datos del inmueble con el código $cod_cat en la base de datos. Tiene que borrar los datos del predio antes!";
           #    $errornumber++; 
						#	 $k = $k + $excel_cols;
			     # } elseif ($check_predios > 0) {
					  if ($check_predios > 0) {
			         $error = true;
				       #$error_fila = $j+6;				
			         $errortext[$errornumber] = "Error: Se ha producido un error en la columna E de la fila $error_fila. El predio con el código $cod_cat está desactivado en la base de datos. Tiene que activar o borrar el predio antes!";
               $errornumber++;
							 $k = $k + $excel_cols;
			      } else {
#echo "NO ERROR, J es $j !<br /> \n";	
	             ############################################################	
							 ### CHEQUEAR POR ID_INMU O GENERAR REGISTRO EN INFO_INMU ###							 
	             ############################################################
							 if ($check == 0) {				
						      ### GENERAR ID_INMU ###							 
							    $id_inmu = get_id_inmu_new ();								 
						      ### GENERAR REGISTRO EN INFO_INMU ###						 							 		   
		              pg_query("INSERT INTO info_inmu (id_inmu,cod_geo,cod_uv,cod_man,cod_pred,cod_blq,cod_piso,cod_apto) VALUES ('$id_inmu','$cod_geo','$cod_uv','$cod_man','$cod_pred','$cod_blq','$cod_piso','$cod_apto')");
#echo "Inmueble ingresado en INFO_INMU...<br /> \n";	
						   } else {
							    $result=pg_query($sql);
							 		$info = pg_fetch_array($result, null, PGSQL_ASSOC);
                  $id_inmu = $info['id_inmu'];
                  pg_free_result($result);							
							 }								 
	             ############################################################	
							 ### CHEQUEAR POR ID_PREDIO O GENERAR REGISTRO EN PREDIOS ###							 
	             ############################################################
						   $sql="SELECT id_predio FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
               $check = pg_num_rows(pg_query($sql));
#echo "$check SQL: $sql<br />\n";								 
							 if ($check == 0) {
							    $id_predio = get_id_predio_new ();
		              pg_query("INSERT INTO info_predio (id_predio,cod_geo,cod_uv,cod_man,cod_pred,activo) VALUES ('$id_predio','$cod_geo','$cod_uv','$cod_man','$cod_pred','2')");									
#echo "Informacion del inmueble ingresado en INFO_PREDIO...<br />\n";
							 } else {
							    $result=pg_query($sql);
							 		$info = pg_fetch_array($result, null, PGSQL_ASSOC);
                  $id_predio = $info['id_predio'];
                  pg_free_result($result);
#echo "Informacion del inmueble YA EXISTE en INFO_PREDIO...<br />\n";									
							 }		             
							 ######################################							 
							 ### PASAR LAS COLUMNAS UNA POR UNA ###
	             ######################################								 
	             while ($i < $excel_cols) {	 
		              $columna = get_column ($i);
					   		  $col_name = name_column ($i);
					   	    #$error_fila = $j+6;							 
		              $valor = trim($valores_predio[$k]);	
	                ################################									
									### PREPARAR ALGUNOS VALORES ###	
	                ################################									
							    if (($columna == "cod_uv") OR ($columna == "cod_man") OR ($columna == "cod_pred") OR ($columna == "tit_cant") OR ($columna == "ter_nofr") OR ($columna == "cod_proc") OR ($columna == "val_lib") OR ($columna == "soe_ing") OR ($columna == "tan_mont_usd")) {
							       if (($columna == "soe_ing") OR ($columna == "tan_mont_usd")) {
										    $valor = str_replace ("Bs.", "" , $valor);
												$valor = str_replace ("BS.", "" , $valor);
										    $valor = str_replace ("Bs", "" , $valor);
												$valor = str_replace ("BS", "" , $valor);
												$valor = trim ($valor);
										 }
										 if (($columna == "ter_nofr") AND ($valor === "")) {
										    # Si no hay un valor para Número de Frentes, se pone el valor 1
										    $valor = 1;
							       } elseif (($columna == "tit_cant") AND ($valor === "")) {
										    $valor = 0;												
										 } elseif ((($columna == "cod_proc") OR ($columna == "val_lib") OR ($columna == "soe_ing") OR ($columna == "tan_mont_usd")) AND ($valor === "")) {
#echo "$columna - $valor";										
									      $valor = -1; 
									   } elseif (!check_int($valor)) {
#echo "$columna - $valor";											
			                  $error = true;
				                #$error_fila = $j+6;				
			                  $errortext[$errornumber] = "Error en la columna $col_name de la fila $error_fila. El valor ingresado no es un número!";
                        $errornumber++;							
									   } else { 
									      $valor = (int) $valor;
									   }
                  }
							    if (($columna == "adq_sdoc") OR ($columna == "ter_fren") OR ($columna == "ter_fond")) {
									   if ($valor === "") {
										    $valor = 0;
										 }										
							       if (!check_float($valor)) {
#echo "$columna - $valor";						
			                  $error = true;
				                #$error_fila = $j+6;				
			                  $errortext[$errornumber] = "Error en la columna $col_name de la fila $error_fila. El valor ingresado no es un número con decimales (procure usar PUNTO para separador de decimales)!";
                        $errornumber++;							
									   } else { 
									      $valor = (float) $valor;										
									   }	 						 
                  }				 
							    if (($columna == "tit_1pat") OR ($columna == "tit_1mat") OR ($columna == "tit_1nom1") OR ($columna == "tit_1nom2")
							        OR ($columna == "tit_2pat") OR ($columna == "tit_2mat") OR ($columna == "tit_2nom1") OR ($columna == "tit_2nom2")) {
							       $valor = strtoupper (ucase($valor));
                  }
					        if ((($columna == "adq_fech") OR ($columna == "der_fech") OR ($columna == "tan_fech_ini") OR ($columna == "ctr_fech")) AND ($valor === "")){ 
					           $valor = $tan_fech_fin = "01/01/1900";
					        }	elseif ($columna == "adq_fech") {
									   $tan_fech_fin = $valor;
									}
									if (($columna == "soe_muj") OR ($columna == "soe_hom")) {
									   $valor = str_replace (".", "," , $valor);
									   if (!check_numeros_inc_coma(trim($valor))) {
			                  $error = true;
				                #$error_fila = $j+6;				
			                  $errortext[$errornumber] = "Error en la columna $col_name de la fila $error_fila. Se debe ingresar números separados por COMAS!";
                        $errornumber++;
                     }										 
									}	
									if ($columna == "ctr_x") {
									   if (($valor == "") OR ($valor === NULL)) {
										    $valor = 0;
									   } else $valor = $valor_x = trim(substr($valor,0,6));
									   if ((($valor_x < $minimo_permitido_x) OR ($valor_x > $maximo_permitido_x)) AND ($valor_x != "") AND ($valor_x != 0)) {							 
			                  $error = true;			
			                  $errortext[$errornumber] = "Error en la columna $col_name de la fila $error_fila. El valor está fuera de los limites permitidos!";
                        $errornumber++;
                     }
									}
									if ($columna == "ctr_y") {
									   if (($valor == "") OR ($valor === NULL)) {
										    $valor = 0;
									   } else $valor = $valor_y = trim(substr($valor,0,7));										 
										 if ((($valor_y < $minimo_permitido_y) OR ($valor_y > $maximo_permitido_y)) AND ($valor_y != "") AND ($valor_y != 0)) {
			                  $error = true;			
			                  $errortext[$errornumber] = "Error en la columna $col_name de la fila $error_fila. El valor está fuera de los limites permitidos!";
                        $errornumber++;
                     }											 
									}			
	                ###################################															
                  ### CHEQUEAR E INGRESAR VALORES ###	
	                ###################################										 							
					        if (!check_value($columna,$valor)) {
					           $error = true;				       
						         $errortext[$errornumber] = "Error en la columna $col_name de la fila $error_fila. El valor erróneo es: $valor";						 
                     $errornumber++;	
									} elseif ($columna == "cod_geo") {
									   # No hacer nada	
								  } elseif ($columna == "cod_pad")	{	 
										 $pmc_ant = $valor; 
					        } elseif ($columna == "cod_proc") {
							       if ($valor > 0) {
									      $proc = true; 
                        $id_proc = get_id_inmu ($cod_uv,$cod_man,$valor,0,0,0);
		                 #   pg_query("INSERT INTO transfer (cod_geo,cod_uv,cod_man,cod_pred,cod_proc) VALUES ('$cod_geo','$cod_uv','$cod_man','$cod_pred','$cod_proc')");					 
#echo "COD_PROC: Lote procede de otro lote!<br />";								    
									   } else {
										    $proc = false;
												$id_proc = "";
									   }
						      } elseif (($columna == "dir_tipo") OR ($columna == "dir_nom") OR ($columna == "dir_num")) { 
                     ### INGRESAR DIRECCION EN PREDIOS ###	 
										 if(pg_query("UPDATE info_predio SET $columna = '$valor' WHERE cod_geo = '$cod_geo' AND id_predio = '$id_predio'")) {						
									   } else {
									      $error = true;				       
						            $errortext[$errornumber] = "Error: Un valor tiene un formato incorrecto. Por favor, revise en la tabla EXCEL el valor de la columna $col_name, fila $error_fila.";
                        $errornumber++;
									   } 
					        } elseif (($columna == "tit_1pat") OR ($columna == "tit_2pat")) { 
					           $con_pat = $valor;
					        } elseif (($columna == "tit_1mat") OR ($columna == "tit_2mat")) { 
					           $con_mat = $valor;	
					        } elseif (($columna == "tit_1nom1") OR ($columna == "tit_2nom1")) { 
					           $con_nom1 = $valor;
					        } elseif (($columna == "tit_1nom2") OR ($columna == "tit_2nom2")) { 
					           $con_nom2 = $valor;	
					        } elseif (($columna == "tit_1ci") OR ($columna == "tit_2ci")) { 
					           $con_ci = $valor;
										 $doc_tipo = get_contrib_doc_tipo ($con_ci);
										 $doc_num = get_contrib_doc_num ($con_ci);
										 $doc_exp = get_contrib_doc_exp ($con_ci);
#echo "$con_ci, TIPO: $doc_tipo, NUM: $doc_num, EXP EN: $doc_exp!<br />";												 
										 $prop1_insertado = false;
											### CHEQUEAR SI SE INGRESO UN TITULAR ###
										 if ($con_pat != "") {
											   $prop1_existe = true;
											   ### SI EXISTE, SELECCIONAR ID DE CONTRIBUYENTE DE LA TABLA ###
												 $id_contrib = get_contrib_id ($con_pat,$con_mat,$con_nom1,$con_nom2,$doc_num);											 
												 if ($id_contrib == 0) {
										#		    pg_query("UPDATE info_inmu SET tit_1id = '$id_contrib' WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");
										     ### SI NO EXISTE, GENERAR ID E INGRESAR DATOS EN LA TABLA CONTRIBUYENTE ###	
											  # } else {
                            $id_contrib = get_contrib_id_new();
                            $con_pmc = get_contrib_pmc_new();
														$con_act = 1;	
														$con_fech_ini = $fecha;																																							
include "siicat_insert_into_contribuyentes.php";
                            $prop1_insertado = true;	
#echo "CONTRIBUYENTE ($con_pat,$con_mat,$con_nom1,$con_nom2,$con_ci) NO ENCONTRADO en tabla contrib --> ID_NUEVO: $id_contrib - PMC_NUEVO: $con_pmc<br />";																											 
											   } else {
#echo "CONTRIBUYENTE ($con_pat,$con_mat,$con_nom1,$con_nom2,$con_ci) se encuentra ya en la tabla contrib --> ID es: $id_contrib<br />";										 
												 }
											   if ($columna == "tit_1ci") {													 
											      pg_query("UPDATE info_inmu SET tit_1id = '$id_contrib' WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");
#echo "TIT_1CI-ID: $id_contrib<br />";														
											   } else {  # ($columna == "tit_2ci")											 
											      pg_query("UPDATE info_inmu SET tit_cant = '2', tit_2id = '$id_contrib' WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");	
#echo "EXISTEN MINIMO 2 CONTRIBUYENTES --> CANTIDAD DE TITULARES en info_inmu: 2, ID del segundo contribuyente: $id_contrib<br />";																									
											   }
										    
									   } elseif ($columna == "tit_1ci") {
										    $prop1_existe = false;
										    pg_query("UPDATE info_inmu SET tit_cant = '0', tit_1id = '0', tit_2id = '0' WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");												
										 } elseif (($columna == "tit_2ci") AND ($prop1_existe)) {
										    pg_query("UPDATE info_inmu SET tit_cant = '1' WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'");
#echo "SOLO EXISTE 1 CONTRIBUYENTE --> CANTIDAD DE TITULARES en info_inmu: 1<br />";													
									   }	
					        } elseif ($columna == "tit_1nit") { 
					           $con_nit = trim($valor);
										 if ($con_nit == "") {
										    $con_nit = 0;
										 }
#echo "NIT: $con_nit<br />";												 																																																																																	
					        } elseif ($columna == "dom_dpto") { 
                     $dom_dpto = $valor; 								
					        } elseif ($columna == "dom_ciu") { 
                     $dom_ciu = $valor;	
					        } elseif ($columna == "dom_tipo") { 
                     $dom_tipo = trim($valor);										 										 
							    } elseif ($columna == "dom_nom") {
                     $dom_nom = $valor;
					        } elseif ($columna == "dom_num") { 
                     $dom_num = $valor;
										 ### SI EXISTE CONTRIBUYENTE Y VALOR EN TABLA ESTA VACIO ###
				             if ($prop1_existe) {
										    $sql="SELECT con_nit, dom_dpto, dom_ciu, dom_tipo, dom_nom, dom_num FROM contribuyentes WHERE id_contrib = '$id_contrib'";
											  $result=pg_query($sql);
							 		      $info = pg_fetch_array($result, null, PGSQL_ASSOC);
                        $con_nit_temp = $info['con_nit'];$dom_dpto_temp = $info['dom_dpto'];$dom_ciu_temp = $info['dom_ciu'];
												$dom_tipo_temp = $info['dom_tipo'];$dom_nom_temp = $info['dom_nom'];$dom_num_temp = $info['dom_num'];
                        pg_free_result($result);
												$set_string = "con_act = 1";									
							          if (($con_nit == 1) AND ($con_nit_temp == "")) {
										       $set_string = $set_string.",con_nit = '$con_nit'";
												}
							          if (($dom_dpto != "") AND ($dom_dpto_temp == "")) {
										       $set_string = $set_string.",dom_dpto = '$dom_dpto'";
												}
							          if (($dom_ciu != "") AND ($dom_ciu_temp == "")) {
										       $set_string = $set_string.",dom_ciu = '$dom_ciu'";
												}	
							          if (($dom_tipo != "") AND ($dom_tipo_temp == "")) {
										       $set_string = $set_string.",dom_tipo = '$dom_tipo'";
												}
							          if (($dom_nom != "") AND ($dom_nom_temp == "")) {
										       $set_string = $set_string.",dom_nom = '$dom_nom'";
												}
							          if (($dom_num != "") AND ($dom_num_temp == "")) {
										       $set_string = $set_string.",dom_num = '$dom_num'";
												}																					
											  $sql = "UPDATE contribuyentes SET $set_string WHERE id_contrib = '$id_contrib'";
												pg_query($sql);
#echo "SQL: $sql<br />";
										 }	
									######################################	 								 
		              ### LEER DATOS PARA TABLA TRANSFER ###	
									######################################	
					        } elseif ($columna == "tan_pat") { 
include "siicat_set_values_contribuyentes.php";								
					           $tan_pat = $con_pat = $valor;
										 if (($tan_pat == "") AND ($proc)) { 
									      $error = true;				       
						            $errortext[$errornumber] = "Error en la fila $error_fila columna $col_name. Hubo un TRANSFER, porque el predio procede de otro predio, pero falta ingresar el campo APELL. PAT. del titular anterior";
                        $errornumber++;		
										 }
					        } elseif ($columna == "tan_mat") { 
					           $tan_mat = $con_mat = $valor;	
					        } elseif ($columna == "tan_nom1") { 
					           $tan_nom1 = $con_nom1 = $valor;
					        } elseif ($columna == "tan_nom2") { 
					           $tan_nom2 = $con_nom2 =$valor;	
					        } elseif ($columna == "tan_ci") { 
					           $tan_ci = $con_ci = $valor;	
					        } elseif ($columna == "tan_modo") { 
					           $tan_modo = $valor;	
					        } elseif ($columna == "tan_doc") { 
					           $tan_doc = $valor;
					        } elseif ($columna == "tan_fech_ini") { 
					           $tan_fech_ini = $valor;	
					        } elseif ($columna == "tan_mont_usd") { 
					           $tan_mont_usd = $valor;								 							
									   # TABLA TRANSFER: ERROR CUANDO LAS COLUMNAS TIENEN UN VALOR, PERO COLUMNA tan_pat ESTA VACIA 
									   if ($tan_pat !== "") { 
#echo "Buscar TRANSFER con ($tan_pat,$tan_mat,$tan_nom1,$tan_nom2,$tan_ci)<br />";											 
										    $tan_1id = get_contrib_id ($tan_pat,$tan_mat,$tan_nom1,$tan_nom2,$tan_ci);
												if ($tan_1id == 0) {
                            $id_contrib = $tan_1id = get_contrib_id_new();
                            $con_pmc = get_contrib_pmc_new();
														$con_act = 0;
														$con_fech_ini = $fecha;																											
include "siicat_insert_into_contribuyentes.php";												
												}
									      # TABLA TRANSFER: CHEQUEAR SI EXISTE YA LA MISMA TRANSFERENCIA 
							          $sql="SELECT id FROM transfer WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' AND tan_fech_ini = '$tan_fech_ini' AND tan_fech_fin = '$tan_fech_fin'";							 
#echo "$error_fila $sql<br />";
                        $check_tan = pg_num_rows(pg_query($sql));
							          if ($check_tan > 0) {												
									         $error = true;				       
						               $errortext[$errornumber] = "Error en la fila $error_fila. Ya existe la transferencia con esa fecha en la tabla TRANSFER.";
                           $errornumber++;
												} else {																										
												   $sql = "INSERT INTO transfer (cod_geo,id_inmu,id_proc,tan_fech_ini,tan_fech_fin,tan_modo,tan_doc,tan_mont_usd,tan_cant,tan_1id) 
													         VALUES ('$cod_geo','$id_inmu','$id_proc','$tan_fech_ini','$tan_fech_fin','$tan_modo','$tan_doc','$tan_mont_usd','1','$tan_1id')";
#echo "$sql<br />";													 
		                       if (!pg_query($sql)) {
									            $error = true;				       
						                  $errortext[$errornumber] = "Error en la fila $error_fila. Ocurrió un problema en insertar los datos de esta fila en la tabla TRANSFER.";
                              $errornumber++;													 
												   }
										    }																		
									   }
									} elseif ($columna == "via_mat") {
									   # No hacer nada											 	
		              ### INGRESAR DATOS EN TABLA PREDIOS ###											 									 																					    									
							    } elseif (($columna == "ter_ubi") OR ($columna == "ser_alc") OR ($columna == "ser_agu") OR ($columna == "ser_luz") OR ($columna == "ser_tel") OR ($columna == "ser_cab") OR ($columna == "ser_gas")
									          OR ($columna == "ter_uso") OR ($columna == "ter_form") OR ($columna == "ter_fren") OR ($columna == "ter_fond") OR ($columna == "ter_nofr") OR ($columna == "ter_san") OR ($columna == "ter_topo")
														OR ($columna == "ter_mur") OR ($columna == "ter_eesp") OR ($columna == "ter_ace")) { 										 
                     if(!pg_query("UPDATE info_predio SET $columna = '$valor' WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'")) {	
									      $error = true;				       
						            $errortext[$errornumber] = "Error: Un valor tiene un formato incorrecto. Por favor, revise en la tabla EXCEL el valor de la columna $col_name, fila $error_fila.";
                        $errornumber++;
									   } 										 
		              ### INGRESAR DATOS EN TABLA INFO_SOCIOECO ###											 									 																					    									
							    } elseif ($columna == "soe_est") { 
								     # TABLA INFO_SOCIOECO: CHEQUEAR SI EXISTEN YA DATOS DEL MISMO AÑO 
							       $sql="SELECT oid FROM info_socioeco WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu' AND soe_anio = '$ano_actual'";							 
                     $check_soe = pg_num_rows(pg_query($sql));
										 if ($check_soe > 0)  {
#echo "CHECK_SOE: $check_soe, SQL: $sql<br />";				 
									      $error = $error_soe = true;				       
						            $errortext[$errornumber] = "Error en la fila $error_fila. Ya existen datos socio-economicos de este año en la base de datos.";
                        $errornumber++;										 
										 } else {
		                    if (!pg_query("INSERT INTO info_socioeco (cod_geo,id_inmu,soe_anio,soe_est) VALUES ('$cod_geo','$id_inmu','1000','$valor')")) {
									         $error = $error_soe = true;				       
						               $errortext[$errornumber] = "Error en la fila $error_fila. Ya existe este registro en los datos socio-economicos.";
                           $errornumber++;													
												}
										 }
							    } elseif (($columna == "soe_ocu") OR ($columna == "soe_civ") OR ($columna == "soe_ing") OR ($columna == "soe_muj") OR ($columna == "soe_hom")) { 
                     if (!$error_soe) {
										    ### INGRESAR VALOR COLUMNA POR COLUMNA EN INFO_SOCIOECO ###	 
										    if(pg_query("UPDATE info_socioeco SET $columna = '$valor' WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'")) {						
									      } else {
									         $error = true;				       
						               $errortext[$errornumber] = "Error: Un valor tiene un formato incorrecto. Por favor, revise en la tabla EXCEL el valor de la columna $col_name, fila $error_fila.";
                           $errornumber++;
									      }
										 }  										 	
                  ### INGRESAR VALOR COLUMNA POR COLUMNA EN INFO_INMU ###									
									} elseif (!$error) {    		   
#		                 pg_query("UPDATE info_predio SET $columna = '$valor' WHERE cod_cat = '$cod_cat'");
                     if(pg_query("UPDATE info_inmu SET $columna = '$valor' WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND cod_blq = '$cod_blq' AND cod_piso = '$cod_piso' AND cod_apto = '$cod_apto'")) {						
									   } else {
									      $error = true;				       
						            $errortext[$errornumber] = "Error: Un valor tiene un formato incorrecto. Por favor, revise en la tabla EXCEL el valor de la columna $col_name, fila $error_fila.";
                        $errornumber++;
									   }   
					        }											
                  $i++;
					        $k++;
		           } #END_OF_WHILE ($i < $excel_cols) 
			         $i = 0;
					 		 
						   ########################################
	             #-------------- CODIGOS ---------------#
	             ########################################	
						 #  if (!$error) {			 	
				    #      $sql="SELECT cod_uv FROM codigos WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
            #      $check = pg_num_rows(pg_query($sql));
			      #      if ($check == 0) {
		        #         pg_query("INSERT INTO codigos (cod_geo,cod_uv,cod_man,cod_pred,cod_subl,activo) VALUES ('$cod_geo','$cod_uv','$cod_man','$cod_pred','$cod_subl','1')");
				    #      }
				    #   }			 
		        } #END_OF_ELSE 	--> IF ($check == 0)
		        $pos_cod_uv = $pos_cod_uv + $excel_cols;
			      $pos_cod_man = $pos_cod_man + $excel_cols;
			      $pos_cod_pred = $pos_cod_pred + $excel_cols;		
					  $pos_cod_blq = $pos_cod_blq + $excel_cols;	
	          $pos_cod_piso = $pos_cod_piso + $excel_cols;	
	          $pos_cod_apto = $pos_cod_apto + $excel_cols;						
			      $j++;
						if (!$error) { 
						   pg_query("UPDATE info_inmu SET tipo_inmu = 'TER' WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND cod_blq = '$cod_blq' AND cod_piso = '$cod_piso' AND cod_apto = '$cod_apto'");
						}						 			 					
         } #END_OF_WHILE ($j < $cantidad_de_filas)
		     if ($error) {
            # BORRAR LOS DATOS YA INGRESADOS EN LAS TABLAS CUANDO OCURRIO UN ERROR	
            pg_query("DELETE FROM info_inmu WHERE tipo_inmu IS NULL");
            pg_query("DELETE FROM info_predio WHERE activo = '2'");
            pg_query("DELETE FROM contribuyentes WHERE con_tipo = ''");												
            pg_query("DELETE FROM transfer WHERE tan_cara IS NULL");		
						pg_query("DELETE FROM info_socioeco WHERE soe_anio = '1000'");				
#echo "ERROR: 5 TABLAS RESTAURADAS (info_inmu, predios, contribuyentes, transfer, info_socioeco)!";
		     } else {
		        $tabla_rellenada = true;
			      $titulo = "Inmuebles";
						$no_de_registros = $j;
				    ### INGRESAR EN INFO_INMU TIPO DE INMUEBLE
				    #pg_query("UPDATE info_inmu SET tipo_inmu = 'TER' WHERE tipo_inmu IS NULL");
				    ### CAMBIAR EN INFO_PREDIO ACTIVO VALOR 2 EN 1
				    pg_query("UPDATE info_predio SET activo = '1' WHERE activo = '2'");
				    ### INGRESAR EN CONTRIBUYENTE TIPO DE CONTRIBUYENTE
				    pg_query("UPDATE contribuyentes SET con_tipo = 'PER' WHERE con_tipo = ''");						
				    ### INGRESAR EN TRANSFER CARACTER DE PROPIETARIO
				    pg_query("UPDATE transfer SET tan_cara = 'PRO' WHERE tan_cara IS NULL AND tan_der_fech > '1900-01-01' AND tan_der_num != ''");
				    pg_query("UPDATE transfer SET tan_cara = 'POS' WHERE tan_cara IS NULL");	
				    ### INGRESAR EN INFO_SOCIOECO AÑO DE ENCUESTA
				    pg_query("UPDATE info_socioeco SET soe_anio = '$ano_actual' WHERE soe_anio = '1000'");													
	          ########################################
	          #-------------- REGISTRO --------------#
	          ########################################
				    $username = get_username($session_id);
				    $valor = "$cantidad_de_filas registros";
		        pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		                  VALUES ('$username','$ip','$fecha','$hora','$accion','$valor')");				
				 } 
      } # END_OF_IF (!$error)	
   #############################################################################
   #---------------- EDIFICACIONES SUBIDOS CON ARCHIVO CSV --------------------#
   #############################################################################	
	 } elseif (($_POST["select"]) == "Edificaciones") {
	    $titulo = "Edificaciones";  
      $accion = "AÃ±adir Edificaciones con archivo CSV";    
      if (!$error) {  # SIN ERROR HASTA AQUI
         $numero_de_elementos_en_el_string = $x+1;
		     $i = $j = $k = 0;
		     $pos_cod_uv = 1;$pos_cod_man = 2;$pos_cod_pred = 3;$pos_cod_blq = 4;$pos_cod_piso = 5;$pos_cod_apto = 6;$pos_edi_num = 7;$pos_edi_piso = 8;					
#echo "    Cantidad_de_filas: $cantidad_de_filas<br /> \n";			
         while ($j < $cantidad_de_filas) {	
	          $cod_uv = trim ($valores_predio[$pos_cod_uv]);
	          $cod_man = trim ($valores_predio[$pos_cod_man]);
	          $cod_pred = trim ($valores_predio[$pos_cod_pred]);
	          $cod_blq = trim ($valores_predio[$pos_cod_blq]);
	          $cod_piso = trim ($valores_predio[$pos_cod_piso]);
	          $cod_apto = trim ($valores_predio[$pos_cod_apto]);							 
		        $edi_num = trim ($valores_predio[$pos_edi_num]);
		        $edi_piso = trim ($valores_predio[$pos_edi_piso]);
#						$cod_cat = get_codcat ($cod_uv,$cod_man,$cod_pred,$cod_subl);				 			 
#echo "    Cod_cat: $cod_cat, Unidad Constructiva: $edi_num, Número de Piso: $edi_piso <br /> \n";
		
				    if ((!check_int($cod_uv)) OR (!check_int($cod_man)) OR (!check_int($cod_pred))) {	 
			         $error = true;
				       $error_fila = $j+6;
						   $errortext[$errornumber] = "Error en la fila $error_fila. La codificación para Dist., Manzano y Predio tienen que ser números!";
               $errornumber++; 
				    } elseif ((!check_int($edi_num)) OR (!check_int($edi_piso))) {	 
			         $error = true;
				       $error_fila = $j+6;
						   $errortext[$errornumber] = "Error en la fila $error_fila. Los valores para la unidad constructiva y el piso tienen que ser números!";
               $errornumber++; 							 
			      } else {
						   #$id_inmu = get_id_inmu ($cod_geo,$cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto);
			         $sql="SELECT cod_uv FROM info_inmu WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
#echo "$sql<br />";
               $check1 = pg_num_rows(pg_query($sql));							 		
			         $sql="SELECT cod_uv FROM info_edif WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND edi_num = '$edi_num' AND edi_piso = '$edi_piso'";
               $check2 = pg_num_rows(pg_query($sql));
						   if (($check1 == 0) OR ($check2 > 0)) {
			            $error = true;
				          $error_fila = $j+6;
						      if ($check1 == 0) {
						         $errortext[$errornumber] = "Error en la fila $error_fila. No existe un registro en la tabla de los Inmuebles. Por favor, añadir el Inmueble antes que las Edificaciones!";
						      } else {		
			               $errortext[$errornumber] = "Error en la fila $error_fila. Ya existe una unidad constructiva con ese Número y Piso en el Predio ($cod_uv-$cod_man-$cod_pred-$edi_num-$edi_piso)!";
                  }
							    $errornumber++;
						   }
			      } 
						if (!$error) {
		           pg_query("INSERT INTO info_edif (cod_geo,cod_uv,cod_man,cod_pred,edi_num,edi_piso) VALUES ('$cod_geo','$cod_uv','$cod_man','$cod_pred','$edi_num','$edi_piso')");
						} else {
						   $error = false;
						}
	          while ($i < $excel_cols_edif) {	 
		           $columna = get_column_edif ($i);
		           $valor = trim($valores_predio[$k]);	
							 if ($columna == "edi_ano") {
							    if (trim($valor) == "") {
									   $valor = $ano_actual;
							    } elseif ((!check_int($valor)) OR ($valor < 1950) OR ($valor > $ano_actual)) {
					           $error = true;				 
						         $error_fila = $j+6;
						         $errortext[$errornumber] = "Error en la columna 'Año Constr.' de la fila $error_fila. El valor tiene que ser un año entre 1950 y $ano_actual";					 
                     $errornumber++;									
									}						
					     }
							 if (($columna == "cod_geo") OR ($columna == "cod_uv") OR ($columna == "cod_man") OR ($columna == "cod_pred") OR ($columna == "cod_blq") OR ($columna == "cod_piso") OR ($columna == "cod_apto")) {
							 # NO HACER NADA
							 } elseif (!check_value($columna,$valor)) {
					        $error = true;				 
						      $col_name = name_column ($i);
						      $error_fila = $j+6;
						      $errortext[$errornumber] = "Error en la columna $col_name de la fila $error_fila. El valor erróneo es: $valor";					 
                  $errornumber++;			 					 
					     } elseif (!$error) { 
							    $sql = "UPDATE info_edif SET $columna = '$valor' WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND edi_num = '$edi_num' AND edi_piso = '$edi_piso'";   
		              if (!pg_query($sql)) {			 
						         $col_name = name_column ($i);
						         $error_fila = $j+6;
						         $errortext[$errornumber] = "Error en la columna $col_name de la fila $error_fila. El valor erróneo es: $valor.";					 
                     $errornumber++;									
									}
					     }											
               $i++;
					     $k++;
		        } #END_OF_WHILE ($i < $excel_cols) 
			      $i = 0;
						$pos_cod_uv = $pos_cod_uv + $excel_cols_edif;
					  $pos_cod_man = $pos_cod_man + $excel_cols_edif;
					  $pos_cod_pred = $pos_cod_pred + $excel_cols_edif;
					  $pos_cod_blq = $pos_cod_blq + $excel_cols_edif;	
	          $pos_cod_piso = $pos_cod_piso + $excel_cols_edif;	
	          $pos_cod_apto = $pos_cod_apto + $excel_cols_edif;							
						$pos_edi_num = $pos_edi_num + $excel_cols_edif;
						$pos_edi_piso = $pos_edi_piso + $excel_cols_edif;											 
			      $j++;				
         } #END_OF_WHILE ($j < $cantidad_de_filas)
#echo "ERRORNUMBER: $errornumber <br />";
		     if ($errornumber == 0) {
				    $tabla_rellenada = true;			 
				    $anadir_edif = false;
						$no_de_registros = $j;
						$titulo = "Edificaciones";
				    ########################################
	          #-------------- REGISTRO --------------#
	          ########################################
				    $username = get_username($session_id);
				    $valor = "$cantidad_de_filas registros";
		        pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		                  VALUES ('$username','$ip','$fecha','$hora','$accion','$valor')");		
		     } else {
            # BORRAR LOS DATOS YA INGRESADOS EN LA TABLA info_edif CUANDO OCURRIO UN ERROR						 
				    $jj = 0;
		     $pos_cod_uv = 1;$pos_cod_man = 2;$pos_cod_pred = 3;$pos_cod_blq = 4;$pos_cod_piso = 5;$pos_cod_apto = 6;$pos_edi_num = 7;$pos_edi_piso = 8;
					  while ($j > $jj) {
	             $cod_uv = trim ($valores_predio[$pos_cod_uv]);
	             $cod_man = trim ($valores_predio[$pos_cod_man]);
	             $cod_pred = trim ($valores_predio[$pos_cod_pred]);	
	             $cod_blq = trim ($valores_predio[$pos_cod_blq]);
	             $cod_piso = trim ($valores_predio[$pos_cod_piso]);
	             $cod_apto = trim ($valores_predio[$pos_cod_apto]);							 						 
		           $edi_num = trim ($valores_predio[$pos_edi_num]);
		           $edi_piso = trim ($valores_predio[$pos_edi_piso]);
						   $cod_cat = get_codcat ($cod_uv,$cod_man,$cod_pred,0,0,0);	
				       if ((check_int($cod_uv)) AND (check_int($cod_man)) AND (check_int($cod_pred)) AND (check_int($edi_num)) AND (check_int($edi_piso))) {	
							 		#$id_inmu = get_id_inmu ($cod_geo,$cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto);					 						 
                  $sql="DELETE FROM info_edif WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND edi_num = '$edi_num' AND edi_piso = '$edi_piso'";
#echo "SQL: $sql <br />";									
									pg_query($sql);
						   }
							 $pos_cod_uv = $pos_cod_uv + $excel_cols_edif;
						   $pos_cod_man = $pos_cod_man + $excel_cols_edif;
							 $pos_cod_pred = $pos_cod_pred + $excel_cols_edif;
					     $pos_cod_blq = $pos_cod_blq + $excel_cols_edif;	
	             $pos_cod_piso = $pos_cod_piso + $excel_cols_edif;	
	             $pos_cod_apto = $pos_cod_apto + $excel_cols_edif;								 
						   $pos_edi_num = $pos_edi_num + $excel_cols_edif;
						   $pos_edi_piso = $pos_edi_piso + $excel_cols_edif;							 							 
						   $jj++;
					  }							
					  $i = $j = $k = 10000;	
#echo "ERROR: TABLA INFO_EDIF RESTAURADA!";			 
			   }	  
	    }
   #############################################################################
   #---------------- ACTIVIDADES SUBIDOS CON ARCHIVO CSV --------------------#
   #############################################################################	
	 } elseif (($_POST["select"]) == "Actividades") {
	    $titulo = "Actividades";  
      $accion = "AÃ±adir Actividades con archivo CSV";    
      if (!$error) {  # SIN ERROR HASTA AQUI
         $numero_de_elementos_en_el_string = $x+1;
		     $i = $j = $k = 0;
		     $pos_cod_uv = 1;$pos_cod_man = 2;$pos_cod_pred = 3;$pos_act_pat = 4;					
#echo "    Cantidad_de_filas: $cantidad_de_filas<br /> \n";			
         while ($j < $cantidad_de_filas) {	
	          $cod_uv = $valores_predio[$pos_cod_uv];
	          $cod_man = $valores_predio[$pos_cod_man];
	          $cod_pred = $valores_predio[$pos_cod_pred];	
						$cod_subl = 0;								
	          $act_pat = $valores_predio[$pos_act_pat];													  			 
#echo "    Cod_cat: $cod_cat, Unidad Constructiva: $edi_num, Número de Piso: $edi_piso <br /> \n";
			      $sql="SELECT cod_uv FROM acteco WHERE act_pat = '$act_pat'";
            $check1 = pg_num_rows(pg_query($sql));
			      $sql="SELECT cod_uv FROM info_predio WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
            $check2 = pg_num_rows(pg_query($sql));		
						if ($act_pat === "") {
			         $error = true;
				       $error_fila = $j+6;
						   $errortext[$errornumber] = "Error en la fila $error_fila. El campo del número de patente no puede quedar en blanco!";
               $errornumber++;							
				    }
						if ((!check_int($cod_uv)) OR (!check_int($cod_man)) OR (!check_int($cod_pred)) OR (!check_int($act_pat))) {	 
			         $error = true;
				       $error_fila = $j+6;
						   $errortext[$errornumber] = "Error: Se ha producido un error en la fila $error_fila. Las columnas de codificación tienen que ser números!";
               $errornumber++;	 
			      }
						if (($check1 > 0) OR ($check2 == 0)) {
			         $error = true;
				       $error_fila = $j+6;
						   if ($check2 == 0) {
						      $errortext[$errornumber] = "Error en la fila $error_fila. No existe ese código en la tabla de los Predios. Por favor, añadir el Predio antes de las Actividades!";
						   } else {		
			            $errortext[$errornumber] = "Error en la fila $error_fila. Ya existe una actividad económica con ese código en la base de datos!";
               }
               $errornumber++;						 
			      } 
						if (!$error) {
		           pg_query("INSERT INTO acteco (cod_uv, cod_man, cod_pred, cod_subl, act_pat) VALUES ('$cod_uv','$cod_man','$cod_pred','$cod_subl','$act_pat')");
	          }
				    while ($i < $excel_cols_acteco) {	 
		              $columna = get_column_acteco ($i);
		              $valor = trim($valores_predio[$k]);	
									# CHEQUEAR POR VALORES NO PERMITIDOS COMO "VALOR NULO" O TEXTO DONDE SE REQUIERE UN INTEGER
									if (($columna == "act_rub") AND ($valor == "0")){ 
					           $valor = "> 0 <";
									}							
									if ($columna == "act_nit") {
									   if ($valor === "") {
					              $valor = "-1";
										 } elseif (!check_int($valor)) {
			                  $error = true;
				                $error_fila = $j+6;				
			                  $errortext[$errornumber] = "Error en la columna H de la fila $error_fila. El valor para el NIT tiene que ser un número!";
                        $errornumber++;								 
										 }	 
									}												
									if (($columna == "act_fech") AND ($valor === "")){ 
					           $valor = "01/01/1900";
									}
									if ($columna == "act_sup") { 
									   if ($valor === "") {
					              $valor = "-1";
										 } elseif (!check_numeros($valor)) {
			                  $error = true;
				                $error_fila = $j+6;				
			                  $errortext[$errornumber] = "Error en la columna K de la fila $error_fila. El valor para la superficie tiene que ser un número (usar PUNTO como separador de decimales)!";
                        $errornumber++;								 
										 }	
									}												
									# INGRESAR VALORES EN LA TABLA
									if ($columna == "cod_geo") {
					        } elseif (!check_value($columna,$valor)) {
					           $error = true;				 
						         $col_name = name_column ($i);
						         $error_fila = $j+6;
						         $errortext[$errornumber] = "Error en la columna $col_name de la fila $error_fila. El valor erróneo es: $valor";
                     $errornumber++;
					        } elseif (!$error) { 	   
		                 pg_query("UPDATE acteco SET $columna = '$valor' WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND act_pat = '$act_pat'");
					        }											
                  $i++;
					        $k++;
		        } #END_OF_WHILE ($i < $excel_cols_acteco) 
			      $i = 0;
						$pos_cod_uv = $pos_cod_uv + $excel_cols_acteco;
					  $pos_cod_man = $pos_cod_man + $excel_cols_acteco;
					  $pos_cod_pred = $pos_cod_pred + $excel_cols_acteco;
						$pos_act_pat = $pos_act_pat + $excel_cols_acteco;									 
			      $j++;
		#        } #END_OF_ELSE 					
         } #END_OF_WHILE ($j < $cantidad_de_filas)
		     if (!$error) {
				    $tabla_rellenada = true;			 
				    $anadir_acteco = false;
				    ########################################
	          #               REGISTRO               #
	          ########################################
				    $username = get_username($session_id);
				    $cod_cat = "Varios";
		        pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		                  VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");		
		     } else {
            # BORRAR LOS DATOS YA INGRESADOS EN LA TABLA acteco CUANDO OCURRIO UN ERROR			
				    $jj = 0;
		        $pos_cod_uv = 1;$pos_cod_man = 2;$pos_cod_pred = 3;$pos_act_pat = 4;	
					  while ($j > $jj) {
	             $cod_uv = $valores_predio[$pos_cod_uv];
	             $cod_man = $valores_predio[$pos_cod_man];
	             $cod_pred = $valores_predio[$pos_cod_pred];							 
		           $act_pat = $valores_predio[$pos_act_pat];		
						   $sql ="DELETE FROM acteco WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND act_pat = '$act_pat'";		 
#echo "SQL: $sql <br />";
               pg_query($sql);
						   $pos_cod_uv = $pos_cod_uv + $excel_cols_acteco;
						   $pos_cod_man = $pos_cod_man + $excel_cols_acteco;
							 $pos_cod_pred = $pos_cod_pred + $excel_cols_acteco;
						   $pos_act_pat = $pos_act_pat + $excel_cols_acteco;				 							 
						   $jj++;
					  }							
					  $i = $j = $k = 10000;	
#echo "ERROR: TABLA ACTECO RESTAURADA!";					 
				 } 
	    }
   #############################################################################
   #---------------- VEHICULOS SUBIDOS CON ARCHIVO CSV --------------------#
   #############################################################################	
	 } elseif (($_POST["select"]) == "VehÃ­culos") {
#echo "VEHICULOS";	  
	    $titulo = "Vehículos";  
      $accion = "AÃ±adir Vehiculos con archivo CSV";    
      if (!$error) {  # SIN ERROR HASTA AQUI
         $numero_de_elementos_en_el_string = $x+1;
		     $i = $j = $k = 0;
		     $pos_cod_uv = 1;$pos_cod_man = 2;$pos_cod_pred = 3;$pos_veh_plc = 9;$pos_veh_pol = 10;			
#echo "    Cantidad_de_filas: $cantidad_de_filas<br /> \n";			
         while ($j < $cantidad_de_filas) {	
	          $cod_uv = $valores_predio[$pos_cod_uv];
	          $cod_man = $valores_predio[$pos_cod_man];
	          $cod_pred = $valores_predio[$pos_cod_pred];	
						$cod_subl = 0;								
	          $veh_plc = $valores_predio[$pos_veh_plc];		
						$veh_plc = strtoupper ($veh_plc);
						$veh_pol = $valores_predio[$pos_veh_pol];												  			 
#echo "    Cod_cat: $cod_cat, Unidad Constructiva: $edi_num, Número de Piso: $edi_piso <br /> \n";
		
						if (($veh_plc === "") AND ($veh_pol === "")) {
			         $error = true;
				       $error_fila = $j+6;
						   $errortext[$errornumber] = "Error en la fila $error_fila. Los campos de la placa actual y de la poliza no pueden quedar ambos en blanco!";
               $errornumber++;							
				    }
						if ((!check_int($cod_uv)) OR (!check_int($cod_man)) OR (!check_int($cod_pred)) OR (!check_int($veh_pol))) {	 
			         $error = true;
				       $error_fila = $j+6;
						   $errortext[$errornumber] = "Error: Se ha producido un error en la fila $error_fila. Las columnas de codificación y de la póliza tienen que ser números!";
               $errornumber++;	 
			      } else {
			         $sql="SELECT cod_uv FROM vehic WHERE veh_plc = '$veh_plc' AND veh_pol = '$veh_pol'";
               $check1 = pg_num_rows(pg_query($sql)); 
			         $sql="SELECT cod_uv FROM info_predio WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
               $check2 = pg_num_rows(pg_query($sql));
 	             if (($check1 > 0) OR ($check2 == 0)) {
			            $error = true;
				          $error_fila = $j+6;
									if ($check1 > 0){		
			               $errortext[$errornumber] = "Error en la fila $error_fila. Ya existe un vehículo con esa placa y número de póliza en la base de datos!";
                  }									
						      if ($check2 == 0) {
						         $errortext[$errornumber] = "Error en la fila $error_fila. No existe ese código en la tabla de los Predios. Por favor, añadir el Predio antes de los Vehículos!";
						      } 
                  $errornumber++;
						   }						 
			      } 
						if (!$error) {
		           pg_query("INSERT INTO vehic (cod_uv,cod_man,cod_pred,cod_subl,veh_plc,veh_pol) VALUES ('$cod_uv','$cod_man','$cod_pred','$cod_subl','$veh_plc','$veh_pol')");
	          }
				    while ($i < $excel_cols_vehic) {	 
		              $columna = get_column_vehic ($i);
		              $valor = trim($valores_predio[$k]);	
									$valor = strtoupper (ucase($valor));
									# CHEQUEAR POR VALORES NO PERMITIDOS COMO "VALOR NULO" O TEXTO DONDE SE REQUIERE UN INTEGER					
									if ($columna == "veh_ano") {
									   if ($valor === "") {
					              $valor = "-1";
										 } elseif (!check_int($valor)) {
			                  $error = true;
				                $error_fila = $j+6;				
			                  $errortext[$errornumber] = "Error en la columna O de la fila $error_fila. El valor para el Año tiene que ser un número!";
                        $errornumber++;								 
										 } elseif (($valor < 1900) OR ($valor > $ano_actual)) {
			                  $error = true;
				                $error_fila = $j+6;				
			                  $errortext[$errornumber] = "Error en la columna O de la fila $error_fila. El valor para el Año no puede ser menor a 1900 y mayor al año actual!";
                        $errornumber++;								 
										 }	 
									}
									if (($columna == "veh_cls") AND ($valor == "0")){ 
					           $valor = "> 0 <";
									}											
									if (($columna == "veh_cc") OR ($columna == "veh_pta") OR ($columna == "veh_plz") OR ($columna == "veh_val")) {
									   if ($valor === "") {
					              $valor = "-1";
										 } elseif (!check_int($valor)) {
			                  $error = true;
				                $error_fila = $j+6;	
												if ($columna == "veh_cc") {		
			                     $errortext[$errornumber] = "Error en la columna R de la fila $error_fila. El valor para la cilindrada (Cc.) tiene que ser un número (sin decimales)!";
                        } elseif ($columna == "veh_pta") {		
			                     $errortext[$errornumber] = "Error en la columna V de la fila $error_fila. El valor para las puertas tiene que ser un número!";
                        } elseif ($columna == "veh_plz") {		
			                     $errortext[$errornumber] = "Error en la columna X de la fila $error_fila. El valor para las plazas tiene que ser un número!";
                        } elseif ($columna == "veh_val") {		
			                     $errortext[$errornumber] = "Error en la columna AA de la fila $error_fila. El valor para el monto valuado tiene que ser un número (sin decimales)!";
                        }
												$errornumber++;								 
										 } 
									}																					
									if ($columna == "veh_tn") { 
									   if ($valor === "") {
					              $valor = "-1";
										 } elseif (!check_float($valor)) {
			                  $error = true;
				                $error_fila = $j+6;				
			                  $errortext[$errornumber] = "Error en la columna K de la fila $error_fila. El valor para la capacidad de carga (Tn) tiene que ser un número (usar PUNTO como separador de decimales)!";
                        $errornumber++;								 
										 }	
									}												
									# INGRESAR VALORES EN LA TABLA
									if ($columna == "cod_geo") {
					        } elseif (!check_value($columna,$valor)) {
					           $error = true;				 
						         $col_name = name_column ($i);
						         $error_fila = $j+6;
						         $errortext[$errornumber] = "Error en la columna $col_name de la fila $error_fila. El valor erróneo es: $valor";
                     $errornumber++;
					        } elseif (!$error) { 	   
		                 pg_query("UPDATE vehic SET $columna = '$valor' WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND veh_plc = '$veh_plc' AND veh_pol = '$veh_pol'");
					        }											
                  $i++;
					        $k++;
		        } #END_OF_WHILE ($i < $excel_cols) 
			      $i = 0;
						$pos_cod_uv = $pos_cod_uv + $excel_cols_vehic;
					  $pos_cod_man = $pos_cod_man + $excel_cols_vehic;
					  $pos_cod_pred = $pos_cod_pred + $excel_cols_vehic;
						$pos_veh_plc = $pos_veh_plc + $excel_cols_vehic;
						$pos_veh_pol = $pos_veh_pol + $excel_cols_vehic;									 
			      $j++;
		#        } #END_OF_ELSE 					
         } #END_OF_WHILE ($j < $cantidad_de_filas)
		     if (!$error) {
				    $tabla_rellenada = true;			 
				    $anadir_vehic = false;
				    ########################################
	          #               REGISTRO               #
	          ########################################
				    $username = get_username($session_id);
				    $var_reg = "Varios";
		        pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		                  VALUES ('$username','$ip','$fecha','$hora','$accion','$var_reg')");		
		     } else {
            # BORRAR LOS DATOS YA INGRESADOS EN LA TABLA acteco CUANDO OCURRIO UN ERROR			
				    $jj = 0;
		        $pos_cod_uv = 1;$pos_cod_man = 2;$pos_cod_pred = 3;$pos_veh_plc = 9;$pos_veh_pol = 10;		
					  while ($j > $jj) {
	             $cod_uv = $valores_predio[$pos_cod_uv];
	             $cod_man = $valores_predio[$pos_cod_man];
	             $cod_pred = $valores_predio[$pos_cod_pred];							 
	             $veh_plc = $valores_predio[$pos_veh_plc];	
							 $veh_plc = strtoupper ($veh_plc);	
						   $veh_pol = $valores_predio[$pos_veh_pol];	
						   $sql ="DELETE FROM vehic WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND veh_plc = '$veh_plc' AND veh_pol = '$veh_pol'";		 
#echo "SQL: $sql <br />";
               pg_query($sql);
						   $pos_cod_uv = $pos_cod_uv + $excel_cols_vehic;
					     $pos_cod_man = $pos_cod_man + $excel_cols_vehic;
					     $pos_cod_pred = $pos_cod_pred + $excel_cols_vehic;
						   $pos_veh_plc = $pos_veh_plc + $excel_cols_vehic;
						   $pos_veh_pol = $pos_veh_pol + $excel_cols_vehic;		 							 
						   $jj++;
					  }							
					  $i = $j = $k = 10000;	
#echo "ERROR: TABLA VEHIC RESTAURADA!";					 
				 } 
	    } 			 			
	 } 
}
################################################################################
#-------------------- PARA AÑADIR UN INMUEBLE MANUALMENTE ---------------------#
################################################################################	
if (((isset($_POST["submit_pred_x"])) AND (!isset($_POST["accion"]))) OR (isset($_GET["tit"]))){	  
   $manual = true;
	 $accion = "Añadir";	 
	 $titulo = "Inmueble";
   $cod_uv_nuevo = $cod_man_nuevo = $cod_pred_nuevo = $cod_blq_nuevo = $cod_piso_nuevo = $cod_apto_nuevo = "";
	 $dir_tipo = $dir_nom = $dir_num = $dir_edif = $dir_cond = "";
	 $tit_pers = "UNI";
   $tit_cant = $tit_bene = $tit_cara = "";
	 #$tit_1pat = $tit_1mat = $tit_1nom1 = $tit_1nom2 = $tit_1ci = $tit_1nit = "";
   #$tit_2pat = $tit_2mat = $tit_2nom1 = $tit_2nom2 = $tit_2ci = $tit_2nit = "";	
	 $tit_1id = $tit_2id = $tit_xid = 0;
	 $dom_dpto = "SCZ";
	 $dom_ciu = "Concepción";
	 $dom_dir = "";
   $adq_modo = "ADJ";
	 $adq_doc = "";
	 $adq_fech = $res_fech = $der_fech = "";	
	 $der_num = "";
	 # DATOS DEL TERRENO
	 $ter_sdoc = "0"; $ter_topo = "PLA"; $ter_form = "REG"; 
	 $ter_uso = "VIV"; $ter_eesp = "NIN"; $ter_san = "SIN"; $ter_mur = "SIN";
   $ser_alc = $ser_agu = $ser_luz = $ser_tel = $ser_gas = $ser_alu = $ser_cab = "NO";	  
	 $ter_ubi = "CEN"; $ter_nofr = "1"; $ter_fond = $ter_fren = "0";
	 # DATOS DEL INMUEBLE
	 $adq_sdoc = "0";
   $cnx_alc = $cnx_agu = $cnx_luz = $cnx_tel = $cnx_gas = $cnx_alu = $cnx_cab = "NO";	 
   $esp_aac = $esp_tas = $esp_tae = $esp_ser = $esp_gar = $esp_dep = "NO";
   $mej_lav = $mej_par = $mej_hor = $mej_pis = $mej_otr = "NO";
   # COLINDANTES
	 $col_norte_nom = $col_sur_nom = $col_este_nom = $col_oeste_nom = "";
	 $col_norte_med = $col_sur_med = $col_este_med = $col_oeste_med = "";	
	 # OBSERVACIONES	 
	 $res_enc = $res_obs = "";
   $res_sup = textconvert(get_username($session_id));
	 # VARIABLES NO USADOS
	 $ter_ace = $adq_mont = "";
	 $reg_checked = "";	  
}
################################################################################
#------------------- DATOS DEL INMUEBLE SUBIDOS MANUALMENTE -------------------#
################################################################################	
/*if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "AÃ±adir inmueble")) {	  
   $accion = "AÃ±adir Inmueble";   
	 $manual = true;
	 $titulo = "Inmueble";	 
	 $cod_uv = (int) trim($_POST["cod_uv"]);
	 $cod_uv_ant = (string) trim($_POST["cod_uv"]);	 
   $cod_man = (int) trim($_POST["cod_man"]);
   $cod_man_ant = (string) trim($_POST["cod_man"]);	 
   $cod_pred = (int) trim($_POST["cod_pred"]);
	 $cod_pred_ant = (string) trim($_POST["cod_pred"]);
   $cod_pad = trim($_POST["cod_pad"]); 
	 $error1 = true;	 	 
	 if (($cod_uv == "") OR ($cod_uv < 1) OR ($cod_uv > 99) OR (strlen($cod_uv) > 2) OR (!check_numeros($cod_uv_ant))) {
	    $cod_uv = trim($_POST["cod_uv"]); $cod_man = trim($_POST["cod_man"]);$cod_pred = trim($_POST["cod_pred"]);	 
	    $mensaje_de_error1 = "Error: El número de la Unidad Vecinal (U.Vd.) tiene que tener un valor entre 1 y 99!";
	 } elseif (($cod_man == "") OR ($cod_man < 1) OR ($cod_man > 99) OR (strlen($cod_man) > 2)OR  (!check_numeros($cod_man_ant))) {
	 	  $cod_man = trim($_POST["cod_man"]);$cod_pred = trim($_POST["cod_pred"]);	
	    $mensaje_de_error1 = "Error: El número del Manzano tiene que tener un valor entre 1 y 99!";
	 } elseif (($cod_pred == "") OR ($cod_pred < 1) OR ($cod_pred > 999) OR (strlen($cod_pred) > 3) OR (!check_numeros($cod_pred_ant))) {			
	# } elseif (($cod_pred < 1) OR ($cod_pred > 999) OR (strlen($cod_pred) > 3) OR (strlen($cod_pred) != strlen($cod_pred_ant))) {
	    $cod_pred = trim($_POST["cod_pred"]);
			$mensaje_de_error1 = "Error: El número del Predio tiene que tener un valor entre 1 y 999!";
	 } else {
	    $cod_cat = get_codcat($cod_uv,$cod_man,$cod_pred);
			$sql="SELECT cod_cat FROM info_predio WHERE cod_cat = '$cod_cat'";
      $check = pg_num_rows(pg_query($sql));
			if ($check > 0) {
			   $mensaje_de_error1 = "Error: Ya existe un predio con ese código en la base de datos!";
			} else $error1 = false;
   }
	 $dir_tipo = $_POST["dir_tipo"];
	 $dir_nom = ucase(strtoupper(trim($_POST["dir_nom"]))); 
	 $dir_num = trim($_POST["dir_num"]);
	 $dir_edif = ucase(strtoupper(trim($_POST["dir_edif"])));
	 $dir_bloq = trim($_POST["dir_bloq"]);
	 $dir_piso = trim($_POST["dir_piso"]);
	 $dir_apto = trim($_POST["dir_apto"]);
	 $error2 = true;	
	 if (get_strlen($dir_nom) > 23) {
	    $mensaje_de_error2 = "Error: No se permite más que 23 caractéres para el Nombre!";
	 } elseif (strlen($dir_num) > 5) {
	    $mensaje_de_error2 = "Error: No se permite más que 5 caractéres para el Número!";	 
	 } elseif (getstrlen($dir_edif) > 8) {
	    $mensaje_de_error2 = "Error: No se permite más que 8 caractéres para el Edificio!";	 
	 } elseif ((strlen($dir_bloq) > 2) OR (strlen($dir_piso) > 2)) {
	    $mensaje_de_error2 = "Error: No se permite más que 2 caractéres para Bloque o Piso!";
	 } elseif (strlen($dir_apto) > 3) {
	    $mensaje_de_error2 = "Error: No se permite más que 3 caractéres para Apartamento!";
	 } else $error2 = false; 	
   $tit_pers = $_POST["tit_pers"];
   $tit_cant = (int) trim($_POST["tit_cant"]);
   $tit_bene = (int) trim($_POST["tit_bene"]);
   $tit_cara = $_POST["tit_cara"];	 	  	 
   $tit_1pat = ucase(strtoupper(trim($_POST["tit_1pat"])));
	 $tit_1mat = ucase(strtoupper(trim($_POST["tit_1mat"])));
	 $tit_1nom1 = ucase(strtoupper(trim($_POST["tit_1nom1"])));
	 $tit_1nom2 = ucase(strtoupper(trim($_POST["tit_1nom2"])));
	 $tit_1ci = ucase(strtoupper(trim($_POST["tit_1ci"])));
	 $tit_1nit = trim($_POST["tit_1nit"]);
   $tit_2pat = ucase(strtoupper(trim($_POST["tit_2pat"])));
	 $tit_2mat = ucase(strtoupper(trim($_POST["tit_2mat"])));
	 $tit_2nom1 = ucase(strtoupper(trim($_POST["tit_2nom1"])));
	 $tit_2nom2 = ucase(strtoupper(trim($_POST["tit_2nom2"])));
	 $tit_2ci = ucase(strtoupper(trim($_POST["tit_2ci"])));
	 $tit_2nit = trim($_POST["tit_2nit"]);	
	 $error3 = true;	
	 if (($tit_cant > 15) OR ($tit_bene > 15) OR (!check_numeros($tit_cant)) OR (!check_numeros($tit_bene))) {
	    $mensaje_de_error3 = "Error: La cantidad de títulares o beneficiarios son 15 máximamente!";
	 } elseif ((get_strlen($tit_1pat) > 20) OR (get_strlen($tit_1mat) > 20) OR (get_strlen($tit_2pat) > 20) OR (get_strlen($tit_2mat) > 20)){
	    $mensaje_de_error3 = "Error: No se permite más que 20 caractéres para los Apellidos!";	 
	 } elseif ((get_strlen($tit_1nom1) > 15) OR (get_strlen($tit_1nom2) > 15) OR (get_strlen($tit_2nom1) > 15) OR (get_strlen($tit_2nom1) > 15)){
	    $mensaje_de_error3 = "Error: No se permite más que 15 caractéres para los Nombres!";	 
	 } elseif ((strlen($tit_1ci) > 12) OR (strlen($tit_1nit) > 12) OR (strlen($tit_2ci) > 12) OR (strlen($tit_2nit) > 12)){
	    $mensaje_de_error3 = "Error: No se permite más que 12 caractéres para el número C.I. o NIT!";
	 } elseif ((!check_numeros($tit_1nit)) OR (!check_numeros($tit_2nit))){
	    $mensaje_de_error3 = "Error: Letras no se permite para el número NIT, solamente números!";
	 } else {
	    if (($tit_1pat == "") AND ($tit_1mat == "") AND ($tit_1nom1 == "") AND ($tit_1nom2 == "") AND ($tit_1ci == "")){	  
				 $tit_1pat = $tit_2pat; $tit_1mat = $tit_2mat; $tit_1nom1 = $tit_2nom1; $tit_1nom2 = $tit_2nom2; $tit_1ci = $tit_2ci; $tit_1nit = $tit_2nit;
				 $tit_2pat = $tit_2mat = $tit_2nom1 = $tit_2nom2 = $tit_2ci = $tit_2nit = "";
			}
			$error3 = false; 
	 }	 
	 $dom_dpto = $_POST["dom_dpto"];	
	 $dom_ciu = ucase(strtoupper(trim($_POST["dom_ciu"])));
	 $dom_dir = ucase(strtoupper(trim($_POST["dom_dir"])));
	 $error4 = true;	
	 if (get_strlen($dom_ciu) > 23){
	    $mensaje_de_error4 = "Error: No se permite más que 23 caractéres para la Ciudad!";	 
	 } elseif (getstrlen($dom_dir) > 40){
	    $mensaje_de_error4 = "Error: No se permite más que 40 caractéres para la Dirección!";	 
	 } else $error4 = false;	 
	 $adq_modo = $_POST["adq_modo"];
   $adq_doc = ucase(strtoupper(trim($_POST["adq_doc"])));
	 $adq_fech = trim($_POST["adq_fech"]); 
	 $error5 = true;	
	 if (get_strlen($adq_doc) > 34){
	    $mensaje_de_error5 = "Error: No se permite más que 34 caractéres para el Documento!";	 
	 } elseif (!check_fecha($adq_fech,$date['year'])){
	    $mensaje_de_error5 = "Error: La fecha ingresada no es válida o no tiene el formato correcto (DD/MM/AAAA)!";	 
	 } else $error5 = false;	 
#	 $otr_ano = $_POST["otr_ano"];
#	 $otr_zona = $_POST["otr_zona"];	
   $via_tipo = $_POST["via_tipo"];
	 $via_clas = $_POST["via_clas"];
	 $via_uso = $_POST["via_uso"];
	 $via_mat = $_POST["via_mat"]; 
   $ser_alc = $_POST["ser_alc"];
	 $ser_agu = $_POST["ser_agu"];
	 $ser_luz = $_POST["ser_luz"];
	 $ser_tel = $_POST["ser_tel"];
	 $ser_gas = $_POST["ser_gas"];
	 $ser_alu = $_POST["ser_alu"];
	 $ser_cab = $_POST["ser_cab"];	
	 $ter_topo = $_POST["ter_topo"];
	 $ter_form = $_POST["ter_form"];
	 $ter_ubi = $_POST["ter_ubi"];
	 $ter_nofr = trim ($_POST["ter_nofr"]);
   $ter_fond = ROUND((float) trim ($_POST["ter_fond"]));
   $ter_fond_ant = trim ($_POST["ter_fond"]);	 
	 $ter_fren = ROUND((float) trim ($_POST["ter_fren"]));
   $ter_fren_ant = trim ($_POST["ter_fren"]);	 
	 $ter_sdoc = ROUND((float) trim ($_POST["ter_sdoc"]));
   $ter_fren_sdoc = trim ($_POST["ter_sdoc"]);		 
	 $ter_eesp = $_POST["ter_eesp"];
   $esp_aac = $_POST["esp_aac"];
	 $esp_tas = $_POST["esp_tas"];
	 $esp_tae = $_POST["esp_tae"];
	 $esp_ser = $_POST["esp_ser"];
	 $esp_gar = $_POST["esp_gar"];
	 $esp_dep = $_POST["esp_dep"];
   $mej_lav = $_POST["mej_lav"];
	 $mej_par = $_POST["mej_par"];
	 $mej_hor = $_POST["mej_hor"];
	 $mej_pis = $_POST["mej_pis"];
	 $mej_otr = $_POST["mej_otr"];
   $ter_uso = $_POST["ter_uso"];
	 $ter_mur = $_POST["ter_mur"];
	 $ter_san = $_POST["ter_san"];	
	 $error6 = true;	
	 if ((!check_numeros($ter_nofr)) OR (!check_numeros($ter_fren_ant))OR (!check_numeros($ter_fond_ant))) {
	    $ter_fren = $ter_fren_ant; $ter_fond = $ter_fond_ant;
	    $mensaje_de_error6 = "Error: Solo se puede ingresar números para Nº de Frentes, Frente y Fondo!";	 
	 } elseif ($ter_nofr > 9) {
	    $mensaje_de_error6 = "Error: No se permite un valor más alto que 9 para el Nº de Frentes!";	 
	 } elseif (($ter_fren > 1000) OR ($ter_fond > 1000)) {
	    $mensaje_de_error6 = "Error: No se permite un valor más alto que 1000m para Frente o Fondo!";	 
	 } elseif ($ter_sdoc > 1000000) {
	    $mensaje_de_error6 = "Error: No se permite un valor más alto que 1.000.000 m² para la Superficie!";	 
	 } else $error6 = false;	 
	 $res_enc = ucase(strtoupper(trim($_POST["res_enc"])));
   $res_sup = ucase(strtoupper(trim($_POST["res_sup"])));
	 $res_fech = trim($_POST["res_fech"]);	 	 
	 $res_obs = ucase(strtoupper(trim($_POST["res_obs"])));
	 $error7 = true;	
	 if ((get_strlen($res_enc) > 26) OR (get_strlen($res_sup) > 26)) {
	    $mensaje_de_error7 = "Error: No se permite más que 26 caractéres para el Encuestador o Responsable!";	 
	 } elseif (get_strlen($res_obs) > 81) {
	    $mensaje_de_error7 = "Error: No se permite más que 81 caractéres para las Observaciones!";	 
	 } elseif (!check_fecha($res_fech,$date['year'])){
	    $mensaje_de_error7 = "Error: La fecha ingresada no es válida o no tiene el formato correcto (DD/MM/AAAA)!";	 
	 } else $error7 = false;
	 ########################################
	 #          RELLENAR TABLA              #
	 ########################################		
	 if ((!$error1) AND (!$error2)AND (!$error3) AND (!$error4) AND (!$error5) AND (!$error6) AND (!$error7)) {
	    pg_query("INSERT INTO info_predio (cod_cat) VALUES ('$cod_cat')");
      pg_query("UPDATE info_predio SET cod_geo = 'cod_geo', cod_uv = '$cod_uv', cod_man = '$cod_man', cod_pred= '$cod_pred', cod_pad = '$cod_pad',
			   dir_tipo = '$dir_tipo', dir_nom = '$dir_nom', dir_num = '$dir_num',
				 dir_edif = '$dir_edif', dir_bloq = '$dir_bloq', dir_piso = '$dir_piso', dir_apto = '$dir_apto',
				 tit_pers = '$tit_pers', tit_cant = '$tit_cant', tit_bene = '$tit_bene', 
				 tit_1pat = '$tit_1pat', tit_1mat = '$tit_1mat', tit_1nom1 = '$tit_1nom1', tit_1nom2 = '$tit_1nom2',
				 tit_1ci = '$tit_1ci', tit_1nit = '$tit_1nit',
				 tit_2pat = '$tit_2pat', tit_2mat = '$tit_2mat', tit_2nom1 = '$tit_2nom1', tit_2nom2 = '$tit_2nom2',
				 tit_2ci = '$tit_2ci', tit_2nit = '$tit_2nit', tit_cara = '$tit_cara',
				 dom_dpto = '$dom_dpto', dom_ciu = '$dom_ciu', dom_dir = '$dom_dir',	
				 adq_modo = '$adq_modo', adq_doc = '$adq_doc', adq_fech = '$adq_fech',	
				 via_tipo = '$via_tipo', via_clas = '$via_clas', via_uso = '$via_uso', via_mat = '$via_mat',
				 ser_alc	= '$ser_alc', ser_agu	= '$ser_agu',	ser_luz	= '$ser_luz',	ser_tel	= '$ser_tel',	
				 ser_gas	= '$ser_gas',	ser_alu	= '$ser_alu',	ser_cab	= '$ser_cab',
				 ter_topo = '$ter_topo', ter_form = '$ter_form', ter_ubi = '$ter_ubi', ter_fren = '$ter_fren', 
				 ter_fond = '$ter_fond', ter_nofr = '$ter_nofr', ter_sdoc = '$ter_topo', ter_eesp = '$ter_eesp',
				 esp_aac = '$esp_aac', esp_tas = '$esp_tas', esp_tae = '$esp_tae',
				 esp_ser = '$esp_ser', esp_gar = '$esp_gar', esp_dep = '$esp_dep', 
				 mej_lav = '$mej_lav', mej_par = '$mej_par', mej_hor = '$mej_hor', mej_pis = '$mej_pis', mej_otr = '$mej_otr',
				 ter_uso = '$ter_uso', ter_mur = '$ter_mur', ter_san = '$ter_san',
				 res_enc = '$res_enc', res_sup = '$res_sup', res_fech = '$res_fech', res_obs = '$res_obs'		  
			   WHERE cod_cat = '$cod_cat'");
			$tabla_rellenada = true;	 
      ########################################
	    #---- CREAR REGISTRO EN TABLA FOTOS ---#
	    ########################################				 
		  $sql="SELECT cod_cat FROM fotos WHERE cod_cat = '$cod_cat'";
			$check = pg_num_rows(pg_query($sql));	
		  if ($check == 0) {
		     pg_query("INSERT INTO fotos (cod_cat, f1, f2) 
		      VALUES ('$cod_cat','FALSE','FALSE')");							 
			}				
      ########################################
	    #               REGISTRO               #
	    ########################################
		  $username = get_username($session_id);
		  pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		                  VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");				 
	 }    	  
} */
################################################################################
#-------------------- PARA AÑADIR EDIFICACIONES MANUALMENTE -------------------#
################################################################################	
if ((isset($_POST["submit_edif_x"])) AND (!isset($_POST["accion"]))) {
	 $cod_uv = $cod_man = $cod_pred  =  $cod_cat = "";
	 $manual = true;
	 $titulo = "Edificaciones";	 
	 $tabla_rellenada = true;
	 $anadir_edif = true;	
	 $no_de_edificaciones = 1; 
	 $inicial = $no_de_edificaciones-1;
   $edi_num[$inicial] = 1;
   $edi_piso[$inicial] = 1;
   $edi_ubi = "";
   $edi_tipo[$inicial] = "CAS";
   $edi_edo[$inicial] = "REG";
   $edi_ano[$inicial] = $ano_actual;
   $edi_cim[$inicial] = $edi_est[$inicial] = $edi_mur[$inicial] = $edi_rvin[$inicial] = $edi_rvex[$inicial] =  "SIN";
   $edi_rvba[$inicial] = $edi_rvco[$inicial] = $edi_acab[$inicial] = $edi_cest[$inicial] = $edi_ctec[$inicial] = "SIN";
   $edi_ciel[$inicial] = $edi_coc[$inicial] = $edi_ban[$inicial] = $edi_carp[$inicial] = $edi_elec[$inicial] = "SIN";
}
################################################################################
#-------------- PARA AÑADIR ACTIVIDADES ECONOMICAS MANUALMENTE ----------------#
################################################################################	
if ((isset($_POST["submit_acteco_x"])) AND (!isset($_POST["accion"]))) {
	 $cod_uv = $cod_man = $cod_pred = $cod_cat = "";
	 $manual = true;
	 $titulo = "Actividades";	 
	 $tabla_rellenada = true;
	 $anadir_acteco = true;	
	/* $no_de_edificaciones = 1; 
	 $inicial = $no_de_edificaciones-1;
   $edi_num[$inicial] = 1;
   $edi_piso[$inicial] = 1;
   $edi_ubi = "";
   $edi_tipo[$inicial] = "CAS";
   $edi_edo[$inicial] = "REG";
   $edi_ano[$inicial] = $ano_actual;
   $edi_cim[$inicial] = $edi_est[$inicial] = $edi_mur[$inicial] = $edi_rvin[$inicial] = $edi_rvex[$inicial] =  "SIN";
   $edi_rvba[$inicial] = $edi_rvco[$inicial] = $edi_acab[$inicial] = $edi_cest[$inicial] = $edi_ctec[$inicial] = "SIN";
   $edi_ciel[$inicial] = $edi_coc[$inicial] = $edi_ban[$inicial] = $edi_carp[$inicial] = $edi_elec[$inicial] = "SIN";  */
}
################################################################################
#------------------- PARA AÑADIR VEHICULOS MANUALMENTE ------------------------#
################################################################################	
if ((isset($_POST["submit_vehic_x"])) AND (!isset($_POST["accion"]))) {
	 $cod_uv = $cod_man = $cod_pred = $cod_subl = $cod_cat = "";
	 $manual = true;
	 $titulo = "Vehículos";	 
	 $tabla_rellenada = true;
	 $anadir_vehic = true;	
	/* $no_de_edificaciones = 1; 
	 $inicial = $no_de_edificaciones-1;
   $edi_num[$inicial] = 1;
   $edi_piso[$inicial] = 1;
   $edi_ubi = "";
   $edi_tipo[$inicial] = "CAS";
   $edi_edo[$inicial] = "REG";
   $edi_ano[$inicial] = $ano_actual;
   $edi_cim[$inicial] = $edi_est[$inicial] = $edi_mur[$inicial] = $edi_rvin[$inicial] = $edi_rvex[$inicial] =  "SIN";
   $edi_rvba[$inicial] = $edi_rvco[$inicial] = $edi_acab[$inicial] = $edi_cest[$inicial] = $edi_ctec[$inicial] = "SIN";
   $edi_ciel[$inicial] = $edi_coc[$inicial] = $edi_ban[$inicial] = $edi_carp[$inicial] = $edi_elec[$inicial] = "SIN";  */
}
	
################################################################################
################################################################################
#-------------------------------- FORMULARIOS ---------------------------------#
################################################################################	
################################################################################
$checked_pred = $checked_edif = $checked_acteco = $checked_vehic = "";
if ((isset($_POST["select"])) AND (($_POST["select"]) == "Predios")) { 
   $checked_pred = pg_escape_string('checked=\"checked\"');
} elseif ((isset($_POST["select"])) AND (($_POST["select"]) == "Edificaciones")) { 	
   $checked_edif = pg_escape_string('checked=\"checked\"');
} elseif ((isset($_POST["select"])) AND (($_POST["select"]) == "Actividades")) { 	
   $checked_acteco = pg_escape_string('checked=\"checked\"');
} elseif ((isset($_POST["select"])) AND (($_POST["select"]) == "VehÃ­culos")) { 	
   $checked_vehic = pg_escape_string('checked=\"checked\"');
} else {
   $checked_pred = pg_escape_string('checked=\"checked\"');
}
################################################################################
#---------------------------- FORMULARIO INICIAL ------------------------------#
################################################################################	
if (!$manual) { # IF SUBIDO ARCHIVO CSV
   if (!$tabla_rellenada){  # IF TABLA NO RELLENADA
      echo "<td>\n";
      echo "  <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";	 
      # Fila 1
	    echo "      <tr height=\"40px\">\n";  
	    echo "         <td width=\"15%\"> &nbsp</td>\n";   #Col. 1 			
      echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n";  #Col.2
	    echo "            Añadir Información a la Base de Datos\n";                          
      echo "         </td>\n";
	    echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 	 
      echo "      </tr>\n";	
      # Fila 2
	    echo "      <form action=\"index.php?mod=3&id=$session_id\" method=\"post\" class=\"formular\" accept-charset=\"utf-8\">\n";
	    echo "      <tr height=\"160\">\n"; 
	    echo "         <td> &nbsp</td>\n";   #Col. 1   	  
	    echo "         <td align=\"center\" valign=\"center\">\n";   #Col. 2 
#   echo "            <input type=\"submit\" name=\"submit\" value=\"Añadir un Inmueble\">\n";
	    echo "            <input type=\"image\" src=\"graphics/anadir_predio.png\" width=\"100\" height=\"120\" name=\"submit_pred\" value=\"Añadir un Inmueble\">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp\n";	
	    echo "            <input type=\"image\" src=\"graphics/anadir_edificaciones.png\" width=\"100\" height=\"120\" name=\"submit_edif\" value=\"Añadir Edificaciones\">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp\n";	
#	    echo "            <input type=\"image\" src=\"graphics/anadir_act_eco.png\" width=\"100\" height=\"120\" name=\"submit_acteco\" value=\"Añadir Actividad Economica\">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp\n";	
#	    echo "            <input type=\"image\" src=\"graphics/anadir_vehic.png\" width=\"100\" height=\"120\" name=\"submit_vehic\" value=\"Añadir Vehículos\">\n";		 	 
      echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3   	 
      echo "      </tr>\n";
	    echo "      </form>\n";		  		 
      # Fila 3
	    echo "      <tr>\n"; 
	    echo "         <td height=\"20\" colspan=\"3\"> &nbsp</td>\n";   #Col. 1+2+3   	    	 
      echo "      </tr>\n";
	    # Fila 4
	    echo "      <tr height=\"40\">\n"; 
	    echo "         <td> &nbsp</td>\n";   #Col. 1   	  
	    echo "         <td align=\"center\" valign=\"center\">\n";   #Col. 2 
      echo "            También se puede ingresar los datos mediante una tabla EXCEL\n";	 
      echo "            guardandola como archivo CSV (delimitado por comas)(*.csv) &nbsp&nbsp\n";	
      echo "            <a href=\"http://$server/$folder/ejemplo/Ejemplo.xls\">Ver ejemplo EXCEL</a>\n";	 	 
      echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3   	 
      echo "      </tr>\n";			  
      # Fila 5
	    echo "      <tr>\n";  
	    echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	    echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	 
	    echo "         <fieldset><legend>Campo para subir un archivo CSV</legend>\n";	  
      echo "         <table border=\"0\" height=\"100%\" width=\"100%\" cellpadding=\"5\" class=\"header\">\n";
                        #TABLA1 fila	1 	
	    echo "            <form action=\"index.php?mod=3&id=$session_id\" method=\"post\" class=\"formular\" accept-charset=\"utf-8\" enctype=\"multipart/form-data\">\n";										 	 
      echo "            <tr height=\"24px\">\n"; 
#   echo "               <td align=\"right\" valign=\"top\" width=\"15%\" colspan=\"2\"> &nbsp</td>\n";  #TABLA1 col 1+2
      echo "               <td valign=\"top\" align=\"center\" width=\"100%\"  colspan=\"5\">\n";  #TABLA1 col 1-5  			
      echo "                  <input name=\"select\" value=\"Predios\" type=\"radio\" $checked_pred> Predios &nbsp&nbsp&nbsp&nbsp\n";
      echo "                  <input name=\"select\" value=\"Edificaciones\" type=\"radio\" $checked_edif> Edificaciones &nbsp&nbsp&nbsp&nbsp\n";
#      echo "                  <input name=\"select\" value=\"Actividades\" type=\"radio\" $checked_acteco> Actividades &nbsp&nbsp&nbsp&nbsp\n";
#      echo "                  <input name=\"select\" value=\"Vehículos\" type=\"radio\" $checked_vehic> Vehículos\n";
	    echo "               </td>\n";
# 	 echo "               <td align=\"right\" valign=\"top\"> &nbsp</td>\n";  #TABLA1 col 4+5 
      echo "            </tr>\n";	 
                        #TABLA1 fila	2 									 
      echo "            <tr height=\"34px\">\n";
      echo "               <td align=\"right\" valign=\"top\" width=\"5%\"> &nbsp</td>\n";  #TABLA1 col 1 
      echo "               <td align=\"right\" valign=\"center\" width=\"20%\">\n";  #TABLA1 col 2  
      echo "                  Archivo CSV:\n";
      echo "               </td>\n";
      echo "               <td align=\"left\" valign=\"center\" width=\"70%\">\n";  #TABLA1 col 3  
      echo "                  <input type=\"file\" name=\"file1\" id=\"form1\" accept=\"image/gif,image/jpg\">\n";
#	  echo "                  <label for=\"file\"></label>\n";
      echo "               </td>\n";
	    echo "               <td align=\"left\" valign=\"center\" width=\"5%\" colspan=\"2\">\n";   #TABLA1 col 4+5 
      echo "                 &nbsp\n";
      echo "               </td>\n";
      echo "            </tr>\n";
                        #TABLA1 fila 3	 	 
      echo "            <tr height=\"34px\">\n";	 
      echo "               <td align=\"center\" valign=\"center\" colspan=\"5\">\n";   #TABLA1 col 1-5 
      echo "                  <input type=\"submit\" name=\"submit\" value=\"Subir\" onClick=\"bildladen('ladestatus','hidden');\">\n";
#	 echo "                  <label for=\"Submit\"></label>\n"; 
      echo "               </td>\n";
      echo "            </tr>\n"; 	 
	    echo "            </form>\n";
      echo "         </table>\n";
      echo "         </fieldset>\n";
	    echo "         </td>\n";
	    echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 3   
      echo "      </tr>\n";
			if ($errornumber > 0) {	 
	       echo "      <tr>\n"; 
	       echo "         <td height=\"30\"> &nbsp</td>\n";   #Col. 1   	  
	       echo "         <td align=\"center\" valign=\"center\">\n";   #Col. 2 
				 if (($errornumber == 1) AND ($error_csv)) {
            echo "         <font color=\"red\">$mensaje_de_error</font> <br />\n";	
						$errornumber = 0;			  
				 } elseif ($errornumber == 1) {
            echo "         <font color=\"red\">Se ha detectado 1 error en la tabla EXCEL!</font> <br />\n";
				 } else {
            echo "         <font color=\"red\">Se han detectado $errornumber errores en la tabla EXCEL!</font> <br />\n";				 
				 }			 	 	    	   
	       echo "         </td>\n";
	       echo "         <td> &nbsp</td>\n";   #Col. 3   	 
         echo "      </tr>\n";
			   $i = 0;
			   while ($i < $errornumber) {
            $mensaje_de_error = $errortext[$i];	
						$error_count = $i+1;				
	          #Fila 6
	          echo "      <tr>\n"; 
	          echo "         <td height=\"20\"> &nbsp</td>\n";   #Col. 1   	  
	          echo "         <td align=\"left\" valign=\"center\">\n";   #Col. 2  
#      echo "         <font color=\"green\">Todas columnas se han llenado correctamente!</font> <br />\n";			 
            echo "         <font color=\"red\">#$error_count $mensaje_de_error</font> <br />\n";				 	    	   
	          echo "         </td>\n";
	          echo "         <td> &nbsp</td>\n";   #Col. 3   	 
            echo "      </tr>\n";
				    $i++;
				 }
	    } 
      # Ultima Fila 
      echo "      <tr height=\"100%\"></tr>\n";			 
      echo "   </table>\n";
      echo "   <br />&nbsp;<br />\n";
      echo "</td>\n";				 	  	 
	 } 
################################################################################
#------------------- FORMULARIO PARA CSV EDIFICACIONES-------------------------#
################################################################################		 
	 else {  # IF TABLA RELLENADA
      # Fila 1
      echo "<td>\n";
      echo "  <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";	 
      echo "      <tr height=\"40px\">\n";
	    echo "         <td width=\"15%\"> &nbsp</td>\n";   #Col. 1 	    
      echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n"; 
	    echo "            <br />Añadir $titulo con archivo CSV\n";                          
      echo "         </td>\n";
	    echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 			 
      echo "      </tr>\n";	     
			echo "			<form name=\"form1\" method=\"post\" action=\"index.php?mod=3&id=$session_id\" accept-charset=\"utf-8\">\n";	 
	    echo "      <tr height=\"40px\">\n";
	    echo "         <td width=\"15%\"> &nbsp</td>\n";   #Col. 1 	    
      echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"bodyTextD\">\n"; 
	    echo "            <br />La tabla de $titulo ha sido guardada en la base de datos con éxito!<br />\n";                          
      echo "         </td>\n";
	    echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 	
	    echo "      </tr>\n";	
	    echo "      <tr height=\"40px\">\n";
	    echo "         <td width=\"15%\"> &nbsp</td>\n";   #Col. 1 	    
      echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"bodyTextD\">\n"; 
	    echo "            <br />Se ingresó $no_de_registros registros en la base de datos.<br />\n";                          
      echo "         </td>\n";
	    echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 	
	    echo "      </tr>\n";				  	 
	    echo "      <tr>\n"; 	
	    echo "         <td width=\"15%\"> &nbsp</td>\n";   #Col. 1 	  
	    echo "         <td align=\"center\" height=\"40\">\n";   #Col. 2 	 
	    echo "         <input type=\"submit\" name=\"volver\" class=\"smallText\" value=\"Volver\">\n";	
			echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 	
	    echo "         </td>\n";
      echo "      </tr>\n";	
	    echo "      </form>\n";		
      # Ultima Fila 
      echo "      <tr height=\"100%\"></tr>\n";			 
      echo "   </table>\n";
      echo "   <br />&nbsp;<br />\n";
      echo "</td>\n";			  	 
	 } # END_OF_ELSE ($tabla_rellenada)
} else {   # IF SUBIDO ARCHIVO MANUALMENTE
################################################################################
#----------------- FORMULARIO PARA INMUEBLES MANUALMENTE ----------------------#
################################################################################	
	 if (!$tabla_rellenada) { 
      include "siicat_form_predio.php"; 	 
   } 
################################################################################
#--------------FORMULARIO PARA AÑADIR EDIFICACIONES MANUALMENTE ---------------#
################################################################################		 
	 elseif ($anadir_edif) {
	   $accion = "Añadir";
	   $tabla_rellenada = false;
	   include "siicat_anadir_edif.php";	 
	 }  
################################################################################
#------------------------ FORMULARIO PARA LAS OPCIONES ------------------------#
################################################################################			 
	 else {   #if ($tabla_rellenada) AND (!$anadir_edif)
	    include "C:/apache/siicat/siicat_ver_edif.php";
	 }
}	 

?>