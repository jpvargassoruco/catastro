<?php

$error = $error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = $error8 = false;
$delete_file = false;
$accion = "";
#$cambio_de_codigo = false;

################################################################################	 
#-------------------------------- AÑADIR DATOS --------------------------------#
################################################################################	 
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "AÃ±adir Datos")) {
   $accion = "Añadir Datos";	 	 
}
################################################################################
#------------------------------- MODIFICAR DATOS ------------------------------#
################################################################################	 
if ((isset($_POST["submit"])) AND ($_POST["submit"] == "Modificar Datos")) {
	 $accion = "Modificar Datos";  
}
################################################################################
#------------------------------- CHEQUEAR DATOS -------------------------------#
################################################################################	 
if (isset($_POST["submit"])) {
   if ($_POST["submit"] == "AÃ±adir Datos") {
	    $cod_uv_nuevo = (int) trim($_POST["cod_uv_nuevo"]);
      $cod_man_nuevo = (int) trim($_POST["cod_man_nuevo"]); 
      $cod_pred_nuevo = (int) trim($_POST["cod_pred_nuevo"]);
      $cod_blq_nuevo = (int) trim($_POST["cod_blq_nuevo"]);
      $cod_piso_nuevo = (int) trim($_POST["cod_piso_nuevo"]);
      $cod_apto_nuevo = (int) trim($_POST["cod_apto_nuevo"]);
	    ### CHEQUEAR SI YA EXISTE UN INMUEBLE CON ESE CODIGO
	    $sql="SELECT id_inmu FROM info_inmu WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_nuevo' AND cod_man = '$cod_man_nuevo' AND cod_pred = '$cod_pred_nuevo' AND cod_blq = '$cod_blq_nuevo' AND cod_piso = '$cod_piso_nuevo' AND cod_apto = '$cod_apto_nuevo'";
      $check_codigo = pg_num_rows(pg_query($sql));
	    $error1 = true;			
	    if (($cod_uv_nuevo == "") OR ($cod_uv_nuevo == 0) OR (!check_int($cod_uv_nuevo))) {
	       $error = true;
			   $mensaje_de_error1 = "Error: El valor ingresado para la U.V. no es válido!";
	    } elseif (($cod_man_nuevo == "") OR ($cod_man_nuevo == 0) OR (!check_int($cod_man_nuevo))) {
	       $error = true;
			   $mensaje_de_error1 = "Error: El valor ingresado para la manzana no es válido!";
	    } elseif (($cod_pred_nuevo == "") OR (!check_int($cod_pred_nuevo))) {
	       $error = true;
			   $mensaje_de_error1 = "Error: El valor ingresado para el predio no es válido!";									 
	    } elseif ($check_codigo > 0) {
	       $error = true;
			   $mensaje_de_error1 = "Error: Ya existe un inmueble con ese código en la base de datos!";
      } else $error1 = false;
	 }	 
	 ########################################
	 #----- LEER DATOS TRANSMITIDOS --------#
	 ########################################		 
	# if ($accion != "Modificar Código") {		
	    $dir_tipo = $_POST["dir_tipo"];
	    $dir_nom = ucase(strtoupper(trim($_POST["dir_nom"]))); 
	    $dir_num = trim($_POST["dir_num"]);
	    $dir_edif = ucase(strtoupper(trim($_POST["dir_edif"])));
	    $dir_cond = ucase(strtoupper(trim($_POST["dir_cond"])));			
	    /*$error2 = true;	
	    if (get_strlen($dir_nom) > 23) {
	       $mensaje_de_error2 = "Error: No se permite más que 23 caractéres para el Nombre!";
	    } elseif (strlen($dir_num) > 5) {
	       $mensaje_de_error2 = "Error: No se permite más que 5 caractéres para el Número!";	 
	    } elseif (get_strlen($dir_edif) > 8) {
	       $mensaje_de_error2 = "Error: No se permite más que 8 caractéres para el Edificio!";	 
	    } elseif ((strlen($dir_bloq) > 2) OR (strlen($dir_piso) > 2)) {
	       $mensaje_de_error2 = "Error: No se permite más que 2 caractéres para Bloque o Piso!";
	    } elseif (strlen($dir_apto) > 4) {
	       $mensaje_de_error2 = "Error: No se permite más que 4 caractéres para Apartamento!";
	    } else $error2 = false; 	*/
			$error2 = false; 
      $tit_pers = $_POST["tit_pers"];
      $tit_cant = (int) trim($_POST["tit_cant"]);
      $tit_bene = (int) trim($_POST["tit_bene"]);
      $tit_cara = $_POST["tit_cara"];	 	  	 
      $tit_1id = $_POST["tit_1id"];
	    $tit_2id = $_POST["tit_2id"];
			$tit_xid = $tit_para_obs = "";
			if (isset($_POST["id_titx0"])) {
			   $tit_xid = $_POST["id_titx0"];
			   $nombre = get_contrib_nombre ($_POST["id_titx0"]);
				 $tit_para_obs = $nombre;
				 if (isset($_POST["id_titx1"])) {
				    $tit_xid = $tit_xid.", ".$_POST["id_titx1"];
			      $nombre = get_contrib_nombre ($_POST["id_titx1"]);
						$tit_para_obs = $tit_para_obs.", ".$nombre;
						if (isset($_POST["id_titx2"])) {
				       $tit_xid = $tit_xid.", ".$_POST["id_titx2"];						
			         $nombre = get_contrib_nombre ($_POST["id_titx2"]);
						   $tit_para_obs = $tit_para_obs.", ".$nombre;
						   if (isset($_POST["id_titx3"])) {
							 	  $tit_xid = $tit_xid.", ".$_POST["id_titx3"];
			            $nombre = get_contrib_nombre ($_POST["id_titx3"]);
						      $tit_para_obs = $tit_para_obs.", ".$nombre;
						      if (isset($_POST["id_titx4"])) {
										 $tit_xid = $tit_xid.", ".$_POST["id_titx4"];
			               $nombre = get_contrib_nombre ($_POST["id_titx4"]);
						         $tit_para_obs = $tit_para_obs.", ".$nombre;
						
				          }						
				       }						
				    }
				 }	
			}
#echo "TIT PARA OBS: $tit_para_obs<br />";			
	    $error3 = true;	
	    if ((!check_int($tit_cant)) OR (!check_int($tit_bene))) {
	       $mensaje_de_error3 = "Error: El valor para títulares o beneficiarios tiene que ser un número!";
	    } else {
			   $error3 = false; 
	    }	 
			$error4 = false;	 
	    $adq_modo = $_POST["adq_modo"];
      $adq_doc = ucase(strtoupper(trim($_POST["adq_doc"])));
	    $adq_fech = $adq_fech_temp = trim($_POST["adq_fech"]); 
	    if ($adq_fech == "") {
	       $adq_fech_temp = "1900-01-01";
      } 
	    $error5 = true; 
	    if (!check_fecha($adq_fech_temp,$dia_actual,$mes_actual,$ano_actual)){
	       $mensaje_de_error5 = "Error: La fecha ingresada no es válida o no tiene el formato correcto. Formatos válidos son DD/MM/AAAA o AAAA-MM-DD!";	 
	    } else $error5 = false;	
      $der_num = ucase(strtoupper(trim($_POST["der_num"])));
	    $der_fech = $der_fech_temp = trim($_POST["der_fech"]); 
	    if ($der_fech == "") {
	       $der_fech_temp = "1900-01-01";
      } 
	    $error6 = true;	
	    if (get_strlen($der_num) > 50){
	       $mensaje_de_error6 = "Error: No se permite más que 50 caractéres para el Número de Inscripción!";	 
	    } elseif (!check_fecha($der_fech_temp,$dia_actual,$mes_actual,$ano_actual)){
	       $mensaje_de_error6 = "Error: La fecha ingresada no es válida o no tiene el formato correcto. Formatos válidos son DD/MM/AAAA o AAAA-MM-DD!";	 
	    } else $error6 = false;		  
#	    $otr_ano = $_POST["otr_ano"];
#	    $otr_zona = $_POST["otr_zona"];	
      # DATOS DEL TERRENO
      $via_tipo = "";
	    $via_clas = "";
	    $via_uso = "";
	    $via_mat = "";
	    $ter_sdoc = ROUND((float) trim ($_POST["ter_sdoc"]),2);
	    $ter_topo = $_POST["ter_topo"];
	    $ter_form = $_POST["ter_form"];
      $ter_uso = $_POST["ter_uso"];						
	    $ter_eesp = $_POST["ter_eesp"];
	    $ter_san = $_POST["ter_san"];	
	    $ter_mur = $_POST["ter_mur"];					 
      $ser_alc = $_POST["ser_alc"];
	    $ser_agu = $_POST["ser_agu"];
	    $ser_luz = $_POST["ser_luz"];
	    $ser_tel = $_POST["ser_tel"];
	    $ser_gas = $_POST["ser_gas"];
#	    $ser_alu = $_POST["ser_alu"];
	    $ser_cab = $_POST["ser_cab"];	
	    $ter_ubi = $_POST["ter_ubi"];
	    $ter_nofr = $ter_nofr_temp = trim ($_POST["ter_nofr"]);
			if ($ter_nofr_temp == "") {
			   $ter_nofr_temp = 0;
			}
      $ter_fond = $ter_fond_ant = ROUND(trim($_POST["ter_fond"]),3);
			if ($ter_fond_ant == "") {
			   $ter_fond_ant = 0;
			}
	    $ter_fren = $ter_fren_ant = ROUND(trim ($_POST["ter_fren"]),3);
			if ($ter_fren_ant == "") {
			   $ter_fren_ant = 0;
			} 
      #$ter_fren_sdoc = trim ($_POST["ter_sdoc"]);		 
      # DATOS DEL INMUEBLE
			$adq_sdoc = ROUND((float) trim ($_POST["adq_sdoc"]),2);	
	    $cnx_agu = $_POST["cnx_agu"];
	    $cnx_luz = $_POST["cnx_luz"];
	    $cnx_tel = $_POST["cnx_tel"];									
      $cnx_alc = $_POST["cnx_alc"];
	    $cnx_cab = $_POST["cnx_cab"];
      $cnx_gas = $_POST["cnx_gas"];				
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
	    $error7 = true;	
	    if (!check_int($ter_nofr_temp)) {
	       $mensaje_de_error7 = "Error: El valor para los frentes tiene que ser un número!";	
	    } elseif ((!check_float($ter_fren_ant)) OR (!check_float($ter_fond_ant))) {
	       $mensaje_de_error7 = "Error: Solo se puede ingresar números para Frente y Fondo (usar PUNTO como separador de decimales)!";
	    } elseif (($ter_fren > 1000) OR ($ter_fond > 1000)) {
	       $mensaje_de_error7 = "Error: No se permite un valor más alto que 1000 m para Frente o Fondo!";	 
	    } elseif ($ter_sdoc > 1000000) {
	       $mensaje_de_error7 = "Error: No se permite un valor más alto que 1.000.000 m² para la Superficie!";	 
	    } else $error7 = false;	 
#echo "TER_FREN: $ter_fren_ant, TER_FOND: $ter_fond_ant<br />";		
			### COLINDANTES ###
			$col_norte_nom = trim($_POST['col_norte_nom']);			
			$col_norte_med = trim($_POST['col_norte_med']);
			$col_sur_nom = trim($_POST['col_sur_nom']);			
			$col_sur_med = trim($_POST['col_sur_med']);	
			$col_este_nom = trim($_POST['col_este_nom']);			
			$col_este_med = trim($_POST['col_este_med']);
			$col_oeste_nom = trim($_POST['col_oeste_nom']);			
			$col_oeste_med = trim($_POST['col_oeste_med']);					
			### CONTROL ###
	    $ctr_enc = ucase(strtoupper(trim($_POST["ctr_enc"])));
      $ctr_sup = ucase(strtoupper(trim($_POST["ctr_sup"])));
	    $ctr_fech = $ctr_fech_temp = trim($_POST["ctr_fech"]);	
	    if ($ctr_fech == "") {
	       $ctr_fech_temp = "1900-01-01";
      } 	  	 
	    $ctr_obs = ucase(strtoupper(trim($_POST["ctr_obs"])));
	    $error8 = true;	
	    if (!check_fecha($ctr_fech_temp,$dia_actual,$mes_actual,$ano_actual)){
	       $mensaje_de_error8 = "Error: La fecha ingresada no es válida o no tiene el formato correcto. Formatos válidos son DD/MM/AAAA o AAAA-MM-DD!";	 
	    } else $error8 = false;
			if (isset($_POST["registrar_cambios"])) {
			   $reg_checked = pg_escape_string('checked=\"checked\"');
			} else $reg_checked = "";
			### DATOS SOLO PASADOS
			$tipo_inmu = $_POST["tipo_inmu"];
			if ($_POST["ctr_x"] == "") {
			   $ctr_x = 0;
			} else $ctr_x = $_POST["ctr_x"];
			if ($_POST["ctr_y"] == "") {
			   $ctr_y = 0;
			} else $ctr_y = $_POST["ctr_y"];
			### DATOS NO USADOS DE MOMENTO
			$ter_ace = $ben_tipo = $ben_ano = $ben_por = "";
			$adq_mont = $val_lib = -1;
			
#echo "Error es: $error1,$error2,$error3,$error4,$error5,$error6,$error7,$error8<br>";   
  # }	 
	 ########################################
	 #---------- RELLENAR TABLAS -----------#
	 ########################################		
	 if ((!$error1) AND (!$error2) AND (!$error3) AND (!$error4) AND (!$error5) AND (!$error6) AND (!$error7) AND (!$error8)) {
#echo "Sin Error!!!<br>";

      /*
      if (isset($_POST["codigo"])) {
			   $cod_cat_nuevo = get_codcat ($cod_uv, $cod_man, $cod_lote, $cod_subl);
				 if ($delete_file) {
				    pg_query("DELETE FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'");	
				 }
				 #$cod_pad = '0';
#echo "$cod_cat,$cod_cat_nuevo, $cod_pad<br>";	
         if ((isset($_POST["codigo"])) AND ($_POST["codigo"] == "Mover")) {
				    pg_query("UPDATE info_predio SET cod_uv = '$cod_uv', cod_man = '$cod_man', cod_lote = '$cod_lote', cod_subl = '$cod_subl', 
				           cod_cat = '$cod_cat_nuevo' WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'");
				 } else {
            # COPIANDO INFO_PREDIO			 
				    pg_query("INSERT INTO info_predio SELECT '99-99-99-99', cod_uv, cod_man, cod_lote, cod_subl, cod_pad,
			      dir_tipo, dir_nom, dir_num, dir_edif, dir_bloq, dir_piso, dir_apto, tit_pers, tit_cant, tit_bene, 
				    tit_1pat, tit_1mat, tit_1nom1, tit_1nom2, tit_1ci, tit_1nit,
				    tit_2pat, tit_2mat, tit_2nom1, tit_2nom2, tit_2ci, tit_2nit, tit_cara,
				    dom_dpto, dom_ciu, dom_dir, der_num, der_fech, adq_modo, adq_doc, adq_fech,'0','NIN','0','0',	
				    via_tipo, via_clas, via_uso, via_mat,
            ser_alc, ser_agu,	ser_luz,	ser_tel, ser_gas,	ser_cab,
				    ter_topo, ter_form, ter_ubi, ter_fren, ter_fond, ter_nofr, ter_sdoc, ter_eesp,
				    esp_aac, esp_tas, esp_tae, esp_ser, esp_gar, esp_dep, mej_lav, mej_par, mej_hor, mej_pis, mej_otr,
				    ter_uso, ter_ace, ter_mur, ter_san, ctr_enc, ctr_sup, ctr_fech, ctr_obs	
				    FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'");
				    pg_query("UPDATE info_predio SET cod_uv = '$cod_uv_nuevo', cod_man = '$cod_man_nuevo', cod_lote = '$cod_lote_nuevo', 
				           cod_subl = '$cod_subl_nuevo' WHERE cod_geo = '99-99-99-99'");
			   }
				 # AÑADIR NUEVO CODIGO EN CODIGOS	
				 if ($delete_file) {
				    pg_query("DELETE FROM codigos WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_nuevo' AND cod_man = '$cod_man_nuevo' AND cod_lote = '$cod_lote_nuevo' AND cod_subl = '$cod_subl_nuevo'");	
				 }				 			    
				 pg_query("INSERT INTO codigos (cod_uv, cod_man, cod_lote, cod_subl, activo) VALUES ('$cod_uv_nuevo','$cod_man_nuevo','$cod_lote_nuevo','$cod_subl_nuevo','1')");
				 ########################################
	       #------------- SOLO MOVER -------------#
	       ########################################										 				 
				 if ((isset($_POST["codigo"])) AND ($_POST["codigo"] == "Mover")) {
				    # DESACTIVAR ANTIGUO CODIGO EN CODIGOS				    					 
		        #pg_query("UPDATE codigos SET activo = '0' WHERE cod_cat = '$cod_cat'");
		        pg_query("DELETE FROM codigos WHERE cod_cat = '$cod_cat'");										 
				    # CHEQUEAR SI EXISTE LA GEOMETRIA DEL PREDIO
			      $sql="SELECT cod_cat FROM predios WHERE cod_cat = '$cod_cat'";
            $check = pg_num_rows(pg_query($sql));
			      if ($check > 0) {		
						   # CAMBIAR TABLA PREDIOS	
							 pg_query("DELETE FROM predios WHERE cod_cat = '$cod_cat_nuevo'");
							 pg_query("UPDATE predios SET cod_cat = '$cod_cat_nuevo', cod_uv = '$cod_uv', cod_man = '$cod_man', 
							         cod_pred = '$cod_pred' WHERE cod_cat = '$cod_cat'");			
				       #pg_query("INSERT INTO predios SELECT '99-99-999', cod_uv, cod_man, cod_pred, activo, the_geom
							 #        FROM predios WHERE cod_cat = '$cod_cat'");
		           #pg_query("UPDATE predios SET cod_cat = '$cod_cat_nuevo', cod_uv = '$cod_uv', cod_man = '$cod_man', 
							 #        cod_pred = '$cod_pred' WHERE cod_cat = '99-99-999'");	
		           #pg_query("UPDATE predios SET activo = '0' WHERE cod_cat = '$cod_cat'");
							 
							 # CAMBIAR TABLA PREDIOS_OCHA
							 pg_query("DELETE FROM predios_ocha WHERE cod_cat = '$cod_cat_nuevo'");
		           pg_query("UPDATE predios_ocha SET cod_cat = '$cod_cat_nuevo', cod_uv = '$cod_uv', cod_man = '$cod_man', 
							         cod_pred = '$cod_pred' WHERE cod_cat = '$cod_cat'");	
		           # CAMBIAR TABLA PREDIOS_OCHA_ORIG
							 pg_query("DELETE FROM predios_ocha_orig WHERE cod_cat = '$cod_cat_nuevo'");
		           pg_query("UPDATE predios_ocha_orig SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat = '$cod_cat'");					 												 
						}
						# CHEQUEAR SI EXISTE UN REGISTRO EN IMP_PLAN_DE_PAGO
					  $sql="SELECT cod_cat FROM imp_plan_de_pago WHERE cod_cat = '$cod_cat'";
            $check = pg_num_rows(pg_query($sql));
			      if ($check > 0) {
							 pg_query("DELETE FROM imp_plan_de_pago WHERE cod_cat = '$cod_cat_nuevo'");
				       pg_query("UPDATE imp_plan_de_pago SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat = '$cod_cat'");
							 #pg_query("INSERT INTO edificaciones SELECT '99-99-999', edi_num, edi_piso, the_geom
				       #FROM edificaciones WHERE cod_cat = '$cod_cat' ORDER BY edi_num, edi_piso");
				       #pg_query("UPDATE edificaciones SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat = '99-99-999'");						
						}	
						# CHEQUEAR SI EXISTE UN REGISTRO EN IMP_PAGADOS
					  $sql="SELECT cod_cat FROM imp_pagados WHERE cod_cat = '$cod_cat' AND fech_imp IS NOT NULL";
            $check = pg_num_rows(pg_query($sql));
			      if ($check > 0) {
						   pg_query("DELETE FROM imp_pagados WHERE cod_cat = '$cod_cat_nuevo'");
				       pg_query("UPDATE imp_pagados SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat = '$cod_cat'");							
				       #pg_query("INSERT INTO imp_pagados SELECT '99-99-999', cod_pad, cod_pmc, no_inmu, gestion, forma_pago, ci_nit,
							 #tp_inmu, titular, dom_ciu, dom_dir, zona, via_mat, val_tab, sup_terr, fact_agu, fact_alc, fact_luz, fact_tel,
							 #fact_min, fact_incl, factor, valor_t, tp_viv, valcm2, sup_const, ant_const, fd_an, valor_vi, avaluo_total,
							 #tp_exen, monto_exen, base_imp, imp_neto, fech_venc, cotido, cotiufv, d10, mant_val, interes, mul_mora, deb_for, 
							 #san_adm, por_form, monto, descont, credito, sal_favor, cuota, exen_id, fech_imp, hora, usuario, control, no_orden
				       #FROM imp_pagados WHERE cod_cat = '$cod_cat' AND fech_imp IS NOT NULL ORDER BY gestion");
				       #pg_query("UPDATE imp_pagados SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat = '99-99-999'");					
						}
				    # CHEQUEAR SI EXISTEN REGISTROS EN CAMBIOS
				    $sql="SELECT cod_cat FROM cambios WHERE cod_cat = '$cod_cat'";
            $check = pg_num_rows(pg_query($sql));
			      if ($check > 0) {	
						   pg_query("DELETE FROM cambios WHERE cod_cat = '$cod_cat_nuevo'");
						   pg_query("UPDATE cambios SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat= '$cod_cat'");	
				 	     #pg_query("INSERT INTO cambios SELECT '99-99-999', fecha_cambio, variable, valor_ant
				       #          FROM cambios WHERE cod_cat = '$cod_cat'");
				       #pg_query("UPDATE cambios SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat= '99-99-999'");						
            }
				    # CHEQUEAR SI EXISTEN REGISTROS EN CAMBIOS - COLUMNA VALOR_ANT
				    $sql="SELECT cod_cat FROM cambios WHERE variable = 'cod_cat' AND valor_ant = '$cod_cat'";
            $check = pg_num_rows(pg_query($sql));
#echo $check;
			      if ($check > 0) {	
				       pg_query("UPDATE cambios SET valor_ant = '$cod_cat_nuevo' WHERE valor_ant = '$cod_cat'");						
				 	     #pg_query("INSERT INTO cambios SELECT cod_cat, fecha_cambio, variable, '99-99-999'
				       #          FROM cambios WHERE cod_cat = '$cod_cat'");
				       #pg_query("UPDATE cambios SET valor_ant = '$cod_cat_nuevo' WHERE valor_ant = '99-99-999'");						
            }	
				    # CHEQUEAR SI EXISTEN REGISTROS EN COLINDANTES
				    $sql="SELECT cod_cat FROM colindantes WHERE cod_cat = '$cod_cat'";
            $check = pg_num_rows(pg_query($sql));		
			      if ($check > 0) {	
						   pg_query("DELETE FROM colindantes WHERE cod_cat = '$cod_cat_nuevo'");
						   pg_query("UPDATE colindantes SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat= '$cod_cat'");
				 	     #pg_query("INSERT INTO colindantes SELECT '99-99-999', norte_nom, norte_med, sur_nom, sur_med, este_nom, este_med, oeste_nom, oeste_med
				       #          FROM colindantes WHERE cod_cat = '$cod_cat'");
				       #pg_query("UPDATE colindantes SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat= '99-99-999'");						
            }
			      # MOVER INFORMACION DE EDIFICACIONES	
						pg_query("DELETE FROM info_edif WHERE cod_cat = '$cod_cat_nuevo'");
						pg_query("UPDATE info_edif SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat = '$cod_cat'");
			      # MOVER TRANSFERENCIAS						
						
				    pg_query("UPDATE transfer SET cod_cat = '$cod_cat_nuevo', cod_cat_ant = '$cod_cat' WHERE cod_cat = '$cod_cat'");																																												
				 }
				 ########################################
	       #------------ MOVER Y COPIAR ----------#
	       ########################################					 
				 # CHEQUEAR SI EXISTE UN TRANSFER
         $sql="SELECT cod_cat FROM transfer WHERE cod_cat = '$cod_cat_nuevo'";
         $check1 = pg_num_rows(pg_query($sql));
				 if ($check1 > 0) {	
				    pg_query("DELETE FROM transfer WHERE cod_cat = '$cod_cat_nuevo'");   
				 }
				 $sql="SELECT cod_cat FROM transfer WHERE cod_cat = '$cod_cat'";
         $check2 = pg_num_rows(pg_query($sql));
			   if ($check2 > 0) {	
				    if ((isset($_POST["codigo"])) AND ($_POST["codigo"] == "Mover")) {				 
				       pg_query("UPDATE transfer SET cod_cat = '$cod_cat_nuevo', cod_cat_ant = '$cod_cat' WHERE cod_cat = '$cod_cat'");						
            } else {
				     #  pg_query("INSERT INTO info_edif SELECT cod_geo, '99-99-999', edi_num, edi_piso, edi_ubi, edi_tipo, edi_edo,
					   #  edi_ano, edi_cim, edi_est, edi_mur, edi_acab, edi_rvin, edi_rvex, edi_rvba, edi_rvco, edi_cest, edi_ctec,
						 #  edi_ciel, edi_coc, edi_ban, edi_carp, edi_elec
				     #  FROM info_edif WHERE cod_cat = '$cod_cat' ORDER BY edi_num, edi_piso");
				     #  pg_query("UPDATE info_edif SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat = '99-99-999'");							
						}
				 }					 
			   # CHEQUEAR SI EXISTE INFORMACION DE EDIFICACIONES
         $sql="SELECT cod_cat FROM info_edif WHERE cod_cat = '$cod_cat_nuevo'";
         $check1 = pg_num_rows(pg_query($sql));				 
				 $sql="SELECT cod_cat FROM info_edif WHERE cod_cat = '$cod_cat'";
         $check2 = pg_num_rows(pg_query($sql));
			   if (($check1 == 0) AND ($check2 > 0)) {
				    pg_query("INSERT INTO info_edif SELECT cod_geo, '99-99-999', edi_num, edi_piso, edi_ubi, edi_tipo, edi_edo,
					  edi_ano, edi_cim, edi_est, edi_mur, edi_acab, edi_rvin, edi_rvex, edi_rvba, edi_rvco, edi_cest, edi_ctec,
						edi_ciel, edi_coc, edi_ban, edi_carp, edi_elec
				    FROM info_edif WHERE cod_cat = '$cod_cat' ORDER BY edi_num, edi_piso");
				    pg_query("UPDATE info_edif SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat = '99-99-999'");						
				 }
				 # CHEQUEAR SI EXISTE GEOMETRIA DE EDIFICACIONES
         $sql="SELECT cod_cat FROM edificaciones WHERE cod_cat = '$cod_cat_nuevo'";
         $check1 = pg_num_rows(pg_query($sql));				 
				 $sql="SELECT cod_cat FROM edificaciones WHERE cod_cat = '$cod_cat'";
         $check = pg_num_rows(pg_query($sql));
			   if (($check1 == 0) AND ($check2 > 0)) {
				       pg_query("INSERT INTO edificaciones SELECT '99-99-999', edi_num, edi_piso, the_geom
				       FROM edificaciones WHERE cod_cat = '$cod_cat' ORDER BY edi_num, edi_piso");
				       pg_query("UPDATE edificaciones SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat = '99-99-999'");						
				 }				 	
				 # CHEQUEAR SI EXISTEN FOTOS
 				 $filename1 = "C:/apache/htdocs/$folder/fotos/".$cod_cat.".jpg";
				 $filename1_nuevo = "C:/apache/htdocs/$folder/fotos/".$cod_cat_nuevo.".jpg";
         $filename2 = "C:/apache/htdocs/$folder/fotos/".$cod_cat."-A.jpg";
				 $filename2_nuevo = "C:/apache/htdocs/$folder/fotos/".$cod_cat_nuevo."-A.jpg";
         if (file_exists($filename1)) {
						copy($filename1,$filename1_nuevo);
						if ((isset($_POST["codigo"])) AND ($_POST["codigo"] == "Mover")) {
						   unlink($filename1);
					  }
         }
         if (file_exists($filename2)) {
						copy($filename2,$filename2_nuevo);
						if ((isset($_POST["codigo"])) AND ($_POST["codigo"] == "Mover")) {
						   unlink($filename2);
					  }						
         }
				 # CHEQUEAR SI EXISTE UN GRAVAMEN
         $sql="SELECT cod_cat FROM gravamen WHERE cod_cat = '$cod_cat_nuevo'";
         $check1 = pg_num_rows(pg_query($sql));
				 if ($check1 > 0) {
				    pg_query("DELETE FROM gravamen WHERE cod_cat = '$cod_cat_nuevo'");
				 }
				 $sql="SELECT cod_cat FROM gravamen WHERE cod_cat = '$cod_cat'";
         $check2 = pg_num_rows(pg_query($sql));
			   if ($check2 > 0) {
				    if ((isset($_POST["codigo"])) AND ($_POST["codigo"] == "Mover")) {
						   pg_query("UPDATE gravamen SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat = '$cod_cat'");
						} else {	
				       pg_query("INSERT INTO gravamen SELECT '99-99-999', fecha, user_id, texto
				          FROM gravamen WHERE cod_cat = '$cod_cat'");
				       pg_query("UPDATE gravamen SET cod_cat = '$cod_cat_nuevo' WHERE cod_cat = '99-99-999'");	
						}					
         }					 				 
				 # INSERTAR CAMBIO EN CAMBIOS
				 if ((isset($_POST["codigo"])) AND ($_POST["codigo"] == "Copiar")) {
            pg_query("INSERT INTO cambios (cod_cat, fecha_cambio, variable, valor_ant) 
				           VALUES ('$cod_cat_nuevo', '$fecha', 'cod_cat', '$cod_cat')");					
			   }
				 $cod_cat = $cod_cat_nuevo;							 			 		 

				include "c:/apache/siicat/siicat_busqueda_resultado.php";	
			########################################################################## 
      } elseif ($accion == "Modificar Código") {    # --> if (isset($_POST["codigo"])) 
		#	   $nuevo_cod_cat = get_codcat($cod_uv, $cod_man, $cod_pred);
 	   #    pg_query("UPDATE info_predio SET cod_cat = '$nuevo_cod_cat' WHERE cod_cat = '$cod_cat'");			

     # if ($cambio_de_codigo) {
	       echo "<td>\n";
	       echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
         # Fila 1
         echo "      <tr height=\"40px\">\n";
	       echo "         <td width=\"15%\"> &nbsp</td>\n";   #Col. 1 	    
         echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n";  #Col. 2 
	       echo "            Modificar el Código del $predio\n";                          
         echo "         </td>\n";
	       echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 			 
         echo "      </tr>\n";
         # Fila 2			
         echo "			<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=50&id=$session_id\" accept-charset=\"utf-8\">\n";				
	       echo "      <tr height=\"80\">\n"; 	 
	       echo "         <td> &nbsp</td>\n";   #Col. 1                       
	       echo "         <td align=\"center\" class=\"bodyText\"><font color=\"orange\"><b>Aviso: Se está cambiando el Código Catastral del $predio! Tenga cuidado! Puede afectar a la geometría del predio y a los impuestos pagados! Verifique el resultado del cambio de código!</b></font></td>\n";  #Col. 2 
	       echo "         <td> &nbsp</td>\n";   #Col. 3 		
	       echo "      </tr>\n";
         # Fila 3					 
	       echo "      <tr height=\"40\">\n"; 	 
	       echo "         <td> &nbsp</td>\n";   #Col. 1                       
	       echo "         <td align=\"center\" class=\"bodyText\"><b>Tiene dos opciones:</b></td>\n";  #Col. 2 
	       echo "         <td> &nbsp</td>\n";   #Col. 3 		
	       echo "      </tr>\n";	
         # Fila 4					 
	       echo "      <tr>\n"; 	 
	       echo "         <td> &nbsp</td>\n";   #Col. 1                       
	       echo "         <td align=\"left\" class=\"bodyText\">RECODIFICAR: Se asigna un nuevo código a un predio. Todos los datos ajuntos (geometría, edificaciones, impuestos pagados, referencias) también reciben el nuevo código. El código antigua será borrado completamente. </td>\n";  #Col. 2 
	       echo "         <td> &nbsp</td>\n";   #Col. 3 		
	       echo "      </tr>\n";		
         # Fila 4					 
	       echo "      <tr>\n"; 	 
	       echo "         <td> &nbsp</td>\n";   #Col. 1                       
	       echo "         <td align=\"left\" class=\"bodyText\">COPIAR: Solo en caso de DIVISION DE PREDIO (si existen edificaciones tiene que verificar en cual de los nuevos predios se ubican)</td>\n";  #Col. 2 
	       echo "         <td> &nbsp</td>\n";   #Col. 3 		
	       echo "      </tr>\n";					 			 
         # Fila 4					 			 
	       echo "      <tr height=\"150\">\n"; 
	       echo "         <td> &nbsp</td>\n";   #Col. 1   	  
	       echo "         <td align=\"center\" valign=\"center\">\n";   #Col. 2 
#   echo "            <input type=\"submit\" name=\"submit\" value=\"Añadir un Inmueble\">\n";
	       echo "         <input type=\"image\" src=\"graphics/codigo_mover.png\" width=\"180\" height=\"135\" name=\"codigo\" value=\"Mover\" onmouseover=\"this.src='graphics/codigo_mover2.png';\" onmouseout=\"this.src='graphics/codigo_mover.png';\">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp\n";
	       echo "         <input type=\"image\" src=\"graphics/codigo_copiar.png\" width=\"180\" height=\"135\" name=\"codigo\" value=\"Copiar\" onmouseover=\"this.src='graphics/codigo_copiar2.png';\" onmouseout=\"this.src='graphics/codigo_copiar.png';\">\n";		 	 
         echo "         </td>\n";
	       echo "         <td> &nbsp</td>\n";   #Col. 3   	 
         echo "      </tr>\n";				 
	       # Fila 5			
	       echo "      <tr height=\"100%\">\n"; 	 
	       echo "         <td> &nbsp</td>\n";   #Col. 1                       
         echo "         <td align=\"center\" valign=\"top\">\n";  #Col. 2		
         echo "            <input type=\"hidden\" name=\"cod_cat\" value=\"$cod_cat\">\n";					 
         echo "            <input type=\"hidden\" name=\"cod_uv\" value=\"$cod_uv\">\n";				
         echo "            <input type=\"hidden\" name=\"cod_man\" value=\"$cod_man\">\n";	
         echo "            <input type=\"hidden\" name=\"cod_lote\" value=\"$cod_lote\">\n";	
         echo "            <input type=\"hidden\" name=\"cod_subl\" value=\"$cod_subl\">\n";					 		
         echo "            <input name=\"Submit\" type=\"submit\" class=\"smallText\" value=\"Volver\">\n";
         echo "         </td>\n";				 
         echo "         <td> &nbsp</td>\n";   #Col. 3 		
         echo "      </tr>\n";	
         echo "      </form>\n";
			   echo "   </table>\n";
			   echo "</td>\n";
			##########################################################################						
      } else {   # if (isset($_POST["codigo"])) --> elseif ($accion == "Modificar Código")
			
			*/
			
         if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "AÃ±adir Datos")) {
				    $cod_uv = $cod_uv_nuevo; $cod_man = $cod_man_nuevo; $cod_pred = $cod_pred_nuevo; $cod_blq = $cod_blq_nuevo; $cod_piso = $cod_piso_nuevo; $cod_apto = $cod_apto_nuevo;
				    # INSERTAR FILA EN INFO_PREDIO
						$id_predio = get_id_predio_new ();
#echo "ID_PREDIO NEW: $id_predio<br />";							
 	          pg_query("INSERT INTO info_predio (id_predio,cod_geo,cod_uv,cod_man,cod_pred) VALUES ('$id_predio','$cod_geo','$cod_uv_nuevo','$cod_man_nuevo','$cod_pred_nuevo')");
 	          # INSERTAR FILA EN INFO_INMU
						$id_inmu = get_id_inmu_new ();
#echo "ID_INMU NEW: $id_inmu<br />";	
					  $tipo_inmu = "SIN";
						pg_query("INSERT INTO info_inmu (id_inmu,tipo_inmu,cod_geo,cod_uv,cod_man,cod_pred,cod_blq,cod_piso,cod_apto) VALUES ('$id_inmu','$tipo_inmu','$cod_geo','$cod_uv_nuevo','$cod_man_nuevo','$cod_pred_nuevo','$cod_blq_nuevo','$cod_piso_nuevo','$cod_apto_nuevo')");						
 	          # INSERTAR FILA EN COLINDANTES 	          
						pg_query("INSERT INTO colindantes (id_predio) VALUES ('$id_predio')");	
			   }
         if ($accion == "Modificar Datos") {
				    if (isset($_POST["registrar_cambios"])) {
						   $registrar_cambios = true;
						} else $registrar_cambios = false;
			      ########################################
            #---- REGISTRAR CAMBIOS EN CAMBIOS ----#
            ########################################	
				    #$nuevo_cod_cat = get_codcat($cod_uv, $cod_man, $cod_pred);
				    $fecha_cambio = $fecha;
			      $sql="SELECT * FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
            $result2 = pg_query($sql);
            $info = pg_fetch_array($result2, null, PGSQL_ASSOC);	
	          $dir_tipo_old = $info['dir_tipo']; $dir_nom_old = $info['dir_nom']; $dir_num_old = $info['dir_num']; 
						$dir_edif_old = $info['dir_edif']; $dir_cond_old = $info['dir_cond']; $via_mat_old = $info['via_mat'];
            $ser_alc_old = $info['ser_alc']; $ser_agu_old = $info['ser_agu']; $ser_luz_old = $info['ser_luz']; $ser_tel_old = $info['ser_tel']; $ser_gas_old = $info['ser_gas']; $ser_cab_old = $info['ser_cab'];  
            $ter_uso_old = $info['ter_uso']; $ter_sdoc_old = ""; 
						$ter_form_old = $info['ter_form']; $ter_ubi_old = $info['ter_ubi'];  $ter_fren_old = $info['ter_fren'];
						$ter_fond_old = $info['ter_fond']; $ter_nofr_old = $info['ter_nofr'];$ter_san_old = $info['ter_san'];
						$ter_topo_old = $info['ter_topo']; $ter_mur_old = $info['ter_mur'];
            $ter_eesp_old = $info['ter_eesp']; $ter_ace =  $info['ter_ace'];
 /*
            $via_tipo_old = $info['via_tipo']; $via_clas_old = $info['via_clas']; $via_uso_old = $info['via_uso']; 
            $esp_aac_old = $info['esp_aac']; $esp_tas_old = $info['esp_tas']; $esp_tae_old = $info['esp_tae']; $esp_ser_old = $info['esp_ser']; $esp_gar_old = $info['esp_gar']; $esp_dep_old = $info['esp_dep'];
            $mej_lav_old = $info['mej_lav']; $mej_par_old = $info['mej_par']; $mej_hor_old = $info['mej_hor']; $mej_pis_old = $info['mej_pis']; $mej_otr_old = $info['mej_otr'];  
            $ctr_enc_old = textconvert($info['ctr_enc']); $ctr_sup_old = textconvert($info['ctr_sup']); $ctr_obs_old = $info['ctr_obs']; $ctr_fech_old = $info['ctr_fech'];
				  */ 
					pg_free_result($result2);
						
	       /*   $tit_pers_old = $info['tit_pers']; $tit_cant_old = $info['tit_cant']; $tit_bene_old = $info['tit_bene']; $tit_cara_old = $info['tit_cara']; $tit_1nom1_old = utf8_decode($info['tit_1nom1']); $tit_1nom2_old = utf8_decode($info['tit_1nom2']);
			      $tit_1pat_old = utf8_decode($info['tit_1pat']); $tit_1mat_old = utf8_decode($info['tit_1mat']); $tit_1nom1_old = utf8_decode($info['tit_1nom1']); $tit_1nom2_old = utf8_decode($info['tit_1nom2']); $tit_1ci_old = $info['tit_1ci']; $tit_1nit_old = $info['tit_1nit'];
			      $tit_2pat_old = utf8_decode($info['tit_2pat']); $tit_2mat_old = utf8_decode($info['tit_2mat']); $tit_2nom1_old = utf8_decode($info['tit_2nom1']); $tit_2nom2_old = utf8_decode($info['tit_2nom2']); $tit_2ci_old = $info['tit_2ci']; $tit_2nit_old = $info['tit_2nit'];
	          $dom_dpto_old = $info['dom_dpto']; $dom_ciu_old	= utf8_decode ($info['dom_ciu']); $dom_dir_old = utf8_decode ($info['dom_dir']); $adq_modo_old = $info['adq_modo']; $adq_doc_old = $info['adq_doc']; 
						$adq_fech_old = $info['adq_fech']; $der_num_old = $info['der_num']; 
						$der_fech_old = $info['der_fech'];				*/			
						
				    if ( (($dir_nom_old == "") AND ($tit_1pat_old == "") AND ($via_tipo_old == "")) OR (!$registrar_cambios)) {
				       #NO SE HA INGRESADO NINGUN DATO HASTA AHORA O NO SE HA MARCADO LA CASILLA --> No registrar los cambios
				    } else {			 
				       if ($dir_tipo_old != $dir_tipo) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','dir_tipo','$dir_tipo_old','$fecha_cambio')"); 
	             }
				       if ($dir_nom_old != $dir_nom) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu,, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','dir_nom','$dir_nom_old','$fecha_cambio')");				 
	             }
				       if ($dir_num_old != $dir_num) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu,, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','dir_num','$dir_num_old','$fecha_cambio')");		 
	             }
				       if ($dir_edif_old != $dir_edif) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','dir_edif','$dir_edif_old','$fecha_cambio')");			 
	             }
				       if ($dir_cond_old != $dir_cond) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','dir_bloq','$dir_bloq_old','$fecha_cambio')"); 
	             }	
				       if ($tit_pers_old != $tit_pers) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','tit_pers','$tit_pers_old','$fecha_cambio')");			 
	             }
				       if ($tit_cant_old != $tit_cant) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','tit_cant','$tit_cant_old','$fecha_cambio')");			 
	             }				 					 
				       if ($tit_bene_old != $tit_bene) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','tit_bene','$tit_bene_old','$fecha_cambio')");			 
	             }		
				       if ($tit_cara_old != $tit_cara) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','tit_cara','$tit_cara_old','$fecha_cambio')");			 
	             }					 	       		 				 
				       if ($adq_modo_old != $adq_modo) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','adq_modo','$adq_modo_old','$fecha_cambio')");			 
	             }		
				       if ($adq_doc_old != $adq_doc) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','adq_doc','$adq_doc_old','$fecha_cambio')");			 
	             }	
				       if ($adq_fech_old != $adq_fech) {
#echo "ADQ_FECH_OLD: $adq_fech_old, ADQ_FECH: $adq_fech<br>";								  
 	                #pg_query("INSERT INTO cambios (cod_cat, variable, valor_ant, fecha_cambio) VALUES ('$cod_cat','adq_fech','$adq_fech_old','$fecha_cambio')");			 
	             }				
				       if ($der_num_old != $der_num) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','der_num','$der_num_old','$fecha_cambio')");			 
	             }	
				       if ($der_fech_old != $der_fech) {
#echo "DER_FECH_OLD: $der_fech_old, DER_FECH: $der_fech<br>";							 
 	                #pg_query("INSERT INTO cambios (cod_cat, variable, valor_ant, fecha_cambio) VALUES ('$cod_cat','der_fech','$der_fech_old','$fecha_cambio')");			 
	             }					 				 	 			  
				       if ($via_mat_old != $via_mat) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','via_mat','$via_mat_old','$fecha_cambio')");			 
	             }					 
				       if ($ser_alc_old != $ser_alc) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','ser_alc','$ser_alc_old','$fecha_cambio')");		 
	             }
				       if ($ser_agu_old != $ser_agu) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','ser_agu','$ser_agu_old','$fecha_cambio')");		 
	             }	
				       if ($ser_luz_old != $ser_luz) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','ser_luz','$ser_luz_old','$fecha_cambio')"); 
	             }	
				       if ($ser_tel_old != $ser_tel) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','ser_tel','$ser_tel_old','$fecha_cambio')");		 
	             }	
				       if ($ser_gas_old != $ser_gas) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','ser_gas','$ser_gas_old','$fecha_cambio')");
	             }		
				       if ($ser_cab_old != $ser_cab) {
 	                pg_query("INSERT INTO cambios (cod_geo, id_inmu, variable, valor_ant, fecha_cambio) VALUES ('$cod_geo','$id_inmu','ser_cab','$ser_cab_old','$fecha_cambio')");
	             }	
				    } #END_OF_ELSE (YA SE HABIA INGRESADO DATOS)		 		 				 				 				 				 		 			
			   } #END_OF_IF --> ($accion == "Modificar Datos") 
			   ########################################
         #------------ AJUSTAR DATOS -----------#
         ########################################	
				 if ($ter_fren == "") {
				    $ter_fren = -1;
				 }
				 if ($ter_fond == "") {
				    $ter_fond = -1;
				 }		
				 if ($ter_nofr == "") {
				    $ter_nofr = -1;
				 }
				 if ($tit_para_obs != "") {
				    if ($ctr_obs == "") {
						   $ctr_obs = "El inmueble cuenta con mas de dos priopietarios. Los propietarios adicionales son: ".$tit_para_obs;
						} else {
						   $ctr_obs = $ctr_obs. "El inmueble cuenta con mas de dos priopietarios. Los propietarios adicionales son: ".$tit_para_obs;						
						}
				 }				 		 				 	
			   ########################################
         #----- RELLENAR TABLA INFO_PREDIO -----#
         ########################################	
				 $activo = 1;	
include "siicat_info_predio_update.php";	
			   ########################################
         #------ RELLENAR TABLA INFO_INMU ------#
         ########################################	
				 $activo = 1;		
include "siicat_info_inmu_update.php";					  	 
			   ########################################
         #----- RELLENAR TABLA COLINDANTES -----#
         ########################################
         if ($accion == "Modificar Datos") {
				    $id_predio = get_id_predio ($cod_geo,$cod_uv,$cod_man,$cod_pred);
				 }				 
				 if (($col_norte_nom	== '') AND ($col_sur_nom	== '')  AND ($col_este_nom	== '') AND ($col_oeste_nom	== '')) {
		        pg_query("DELETE FROM colindantes WHERE id_predio = '$id_predio'");
				 } else {
				    # CHEQUEAR SI EXISTEN REGISTROS EN COLINDANTES
				    $sql="SELECT id_predio FROM colindantes WHERE id_predio = '$id_predio'";
            $check = pg_num_rows(pg_query($sql));		
			      if ($check == 0) {	
				 	     pg_query("INSERT INTO colindantes (id_predio, norte_nom, norte_med, sur_nom, sur_med, este_nom, este_med, oeste_nom, oeste_med)
				                 VALUES ('$id_predio', '$col_norte_nom', '$col_norte_med', '$col_sur_nom', '$col_sur_med', '$col_este_nom', '$col_este_med', '$col_oeste_nom', '$col_oeste_med')");
						} else {						 
				       pg_query("UPDATE colindantes SET norte_nom = '$col_norte_nom', norte_med = '$col_norte_med', sur_nom = '$col_sur_nom', sur_med = '$col_sur_med', 
							           este_nom = '$col_este_nom', este_med = '$col_este_med', oeste_nom = '$col_oeste_nom', oeste_med = '$col_oeste_med' WHERE id_predio = '$id_predio'");					
            }		 
				 }			
				 $tabla_rellenada = true;
         ########################################
	       #--------------- REGISTRO -------------#
	       ########################################
		     $username = get_username($session_id);
				 $accion_reg = utf8_encode ($accion);
				 $cod_cat = get_codcat_from_id_inmu($id_inmu);
		     pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		                  VALUES ('$username','$ip','$fecha','$hora','$accion_reg','$cod_cat')");
			   include "c:/apache/siicat/siicat_busqueda_resultado.php";		 
				 
	#		} # END_OF_ELSE (ACCION NO ES CAMBIO DE CODIGO)
			
	 } else {  # ERROR AL INGRESAR LOS DATOS  
   
	    if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "AÃ±adir Datos")) {
#echo "ACCION --> Añadir Datos<br>";				
			   $accion = "Añadir";
				 $manual = true;
	       $tabla_rellenada = true;
         include "c:/apache/siicat/siicat_form_predio.php";
			} else {
			   $error = true;
			   include "c:/apache/siicat/siicat_modificar_datos.php";
			}
	 }	    	  
} else {
   include "c:/apache/siicat/siicat_busqueda_resultado.php";
}

?>