<?php

#$confirmado_borrar_todo = false;
#$cambio = "off";
$error = false;
$aviso_mas_inmuebles = $aviso_colindantes = $aviso_fotos = false;
########################################
#---------- BOTON MODIFICAR -----------#
########################################
if ((isset($_POST["submit"])) AND ($_POST["submit"] == "Modificar")) {
   $cod_uv_nuevo = trim($_POST["cod_uv_nuevo"]);
   $cod_man_nuevo = trim($_POST["cod_man_nuevo"]);
   $cod_pred_nuevo = trim($_POST["cod_pred_nuevo"]);
   $cod_blq_nuevo = trim($_POST["cod_blq_nuevo"]);
   $cod_piso_nuevo = trim($_POST["cod_piso_nuevo"]);
   $cod_apto_nuevo = trim($_POST["cod_apto_nuevo"]);
	 ### CHEQUEAR SI YA EXISTE UN INMUEBLE CON ESE CODIGO
	 $sql="SELECT id_inmu FROM info_inmu WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv_nuevo' AND cod_man = '$cod_man_nuevo' AND cod_pred = '$cod_pred_nuevo' AND cod_blq = '$cod_blq_nuevo' AND cod_piso = '$cod_piso_nuevo' AND cod_apto = '$cod_apto_nuevo'";
   $check_codigo = pg_num_rows(pg_query($sql));
	 ### CHEQUEAR SI ES EL MISMO CODIGO	
	 if (($cod_uv_nuevo == $cod_uv) AND ($cod_man_nuevo == $cod_man) AND ($cod_pred_nuevo == $cod_pred) AND ($cod_blq_nuevo == $cod_blq) AND ($cod_piso_nuevo == $cod_piso) AND ($cod_apto_nuevo == $cod_apto)) {		 
	    $error = true;
			$mensaje_de_error = "Error: Tiene que ingresar un código diferente al código existente!";
			$disabled = "";	
	 } elseif (($cod_uv_nuevo == "") OR ($cod_uv_nuevo == 0) OR (!check_int($cod_uv_nuevo))) {
	    $error = true;
			$mensaje_de_error = "Error: El valor ingresado para la U.V. no es válido!";
			$disabled = "";
	 } elseif (($cod_man_nuevo == "") OR ($cod_man_nuevo == 0) OR (!check_int($cod_man_nuevo))) {
	    $error = true;
			$mensaje_de_error = "Error: El valor ingresado para la manzana no es válido!";
			$disabled = "";
	 } elseif (($cod_pred_nuevo == "") OR (!check_int($cod_pred_nuevo))) {
	    $error = true;
			$mensaje_de_error = "Error: El valor ingresado para el predio no es válido!";
			$disabled = "";										 
	 } elseif ($check_codigo > 0) {
	    $error = true;
			$mensaje_de_error = "Error: Ya existe un inmueble con ese código en la base de datos!";
			$disabled = "";
   } else {
	    ### CHEQUEAR SI CAMBIA EL CODIGO DEL PREDIO
	    if (($cod_uv_nuevo != $cod_uv) OR ($cod_man_nuevo != $cod_man) OR ($cod_pred_nuevo != $cod_pred)) {
			   ### CHEQUEAR SI HAY MAS INMUEBLES EN EL PREDIO
	       $sql="SELECT id_inmu FROM info_inmu WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
         $check_inmu = pg_num_rows(pg_query($sql));	
	       if ($check_inmu > 1) {				 
	          $aviso_mas_inmuebles = true;
			      $mensaje_mas_inmuebles = "<b>Aviso:</b> Existen en total<b> $check_inmu inmuebles </b>en el predio. Por favor, verifique también la códificación de los otros inmuebles!";				 
				 }
				 ### CHEQUEAR SI EL PREDIO TIENE COLINDANTES
         $sql="SELECT cod_uv, cod_man, cod_pred FROM predios WHERE st_dwithin ((SELECT the_geom FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'),the_geom,1)
              AND activo = '1' AND NOT (cod_geo= '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred')";	
         $check_colindantes = pg_num_rows(pg_query($sql));
	       if ($check_colindantes > 0) {				 
	          $aviso_colindantes = true;
			      $mensaje_colindantes = "<b>Aviso:</b> El cambio de código afectará a <b>$check_colindantes predios </b>colindantes. Debe verificar si el sistema va a actualizar correctamente los colindantes de todos los inmuebles involucrados!";				 
				 }
				 ### CHEQUEAR SI EL PREDIO TIENE FOTOS
         $filename1 = "C:/apache/htdocs/$folder/fotos/".$cod_cat.".jpg";
         $filename2 = "C:/apache/htdocs/$folder/fotos/".$cod_cat."-A.jpg";
         $filename3 = "C:/apache/htdocs/$folder/fotos/".$cod_cat."-B.jpg";
         $filename4 = "C:/apache/htdocs/$folder/fotos/".$cod_cat."-C.jpg";
         $filename5 = "C:/apache/htdocs/$folder/fotos/".$cod_cat."-D.jpg";
         $filename6 = "C:/apache/htdocs/$folder/fotos/".$cod_cat."-E.jpg";				 				 				 				 
				 $check_fotos = 0;
				 if (file_exists($filename1)) {
				    $check_fotos++;
				    if (file_exists($filename2)) {
				       $check_fotos++;
				       if (file_exists($filename3)) {
				          $check_fotos++;
				          if (file_exists($filename4)) {
				             $check_fotos++;
				             if (file_exists($filename5)) {
				                $check_fotos++;
				                if (file_exists($filename6)) {
				                   $check_fotos++;		 
                        }																 
                     }																 
                  }																 
               }
            }
         }				 
	       if ($check_fotos > 0) {				 
	          $aviso_fotos = true;
			      $mensaje_fotos = "<b>Aviso:</b> El sistema cambiará el nombre de <b>$check_fotos foto(s) </b>del inmueble. Debe realizar después una copia de seguridad del sistema y una copia de seguridad de las fotos para asegurar ese cambio!";				 
				 }					 				 
			}
			$disabled = pg_escape_string('disabled=\"disabled\"');
	 }
} else {
   $cod_uv_nuevo = $cod_uv;
   $cod_man_nuevo = $cod_man;
   $cod_pred_nuevo = $cod_pred;
   $cod_blq_nuevo = $cod_blq;
   $cod_piso_nuevo = $cod_piso;
   $cod_apto_nuevo = $cod_apto;
	 $disabled = "";
}	 	
########################################
#----- CONFIRMAR MODIFICAR == SI ------#
########################################
if ((isset($_POST["codigo_nuevo"])) AND ($_POST["codigo_nuevo"] == "SI")) {
   $cod_uv_nuevo = trim($_POST["cod_uv_nuevo"]);
   $cod_man_nuevo = trim($_POST["cod_man_nuevo"]);
   $cod_pred_nuevo = trim($_POST["cod_pred_nuevo"]);
   $cod_blq_nuevo = trim($_POST["cod_blq_nuevo"]);
   $cod_piso_nuevo = trim($_POST["cod_piso_nuevo"]);
   $cod_apto_nuevo = trim($_POST["cod_apto_nuevo"]);
	 ### MODIFICAR INFO_INMU
	 $sql="UPDATE info_inmu SET cod_uv = '$cod_uv_nuevo',cod_man = '$cod_man_nuevo',cod_pred = '$cod_pred_nuevo',cod_blq = '$cod_blq_nuevo',cod_piso = '$cod_piso_nuevo',cod_apto = '$cod_apto_nuevo'
	       WHERE id_inmu = '$id_inmu'";
	 pg_query($sql);
	 ### MODIFICAR INFO_PREDIO
	 $sql="UPDATE info_predio SET cod_uv = '$cod_uv_nuevo',cod_man = '$cod_man_nuevo',cod_pred = '$cod_pred_nuevo'
	       WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";	 
	 pg_query($sql);
	 ### MODIFICAR PREDIOS
	 $sql="UPDATE predios SET cod_uv = '$cod_uv_nuevo',cod_man = '$cod_man_nuevo',cod_pred = '$cod_pred_nuevo'
	       WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";	 
	 pg_query($sql);
	 ### MODIFICAR PREDIOS_OCHA
	 $sql="UPDATE predios_ocha SET cod_uv = '$cod_uv_nuevo',cod_man = '$cod_man_nuevo',cod_pred = '$cod_pred_nuevo'
	       WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";	 
	 pg_query($sql);	
	 ### MODIFICAR PREDIOS_OCHA_ORIG
	 $sql="UPDATE predios_ocha_orig SET cod_uv = '$cod_uv_nuevo',cod_man = '$cod_man_nuevo',cod_pred = '$cod_pred_nuevo'
	       WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";	 
	 pg_query($sql);	
	 ### MODIFICAR OCHAVES_LINEA
	 $sql="UPDATE ochaves_linea SET cod_uv = '$cod_uv_nuevo',cod_man = '$cod_man_nuevo',cod_pred = '$cod_pred_nuevo'
	       WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";	 
	 pg_query($sql);	
	 ### MODIFICAR INFO_EDIF
	 $sql="UPDATE info_edif SET cod_uv = '$cod_uv_nuevo',cod_man = '$cod_man_nuevo',cod_pred = '$cod_pred_nuevo'
	       WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";	 
	 pg_query($sql);
	 ### MODIFICAR EDIFICACIONES
	 $sql="UPDATE edificaciones SET cod_uv = '$cod_uv_nuevo',cod_man = '$cod_man_nuevo',cod_pred = '$cod_pred_nuevo'
	       WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";	 
	 pg_query($sql);	 
	 ### MODIFICAR COLINDANTES	
	 $cod_cat_nuevo = get_codcat ($cod_uv_nuevo,$cod_man_nuevo,$cod_pred_nuevo,$cod_blq_nuevo,$cod_piso_nuevo,$cod_apto_nuevo);	  
	 $sql="SELECT cod_uv, cod_man, cod_pred FROM predios WHERE st_dwithin ((SELECT the_geom FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'),the_geom,1)
      AND activo = '1' AND NOT (cod_geo= '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred')";	
   $result = pg_query($sql);
   $i = $j = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {   
	       if ($i == 0) {
			      $cod_uv_temp = $col_value;
	       } elseif ($i == 1) {
			      $cod_man_temp = $col_value;
	       } else {
			      $cod_pred_temp = $col_value;		
	 	 	  	 	$sql2="SELECT id_predio FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
            $result2 = pg_query($sql2);
            $info = pg_fetch_array($result2, null, PGSQL_ASSOC);
            $id_predio = $info['id_predio'];														 
            pg_free_result($result2);
						$sql3="SELECT norte_nom, sur_nom, este_nom, oeste_nom FROM colindantes WHERE id_predio = '$id_predio'";	
            $result3 = pg_query($sql3);
            $info = pg_fetch_array($result3, null, PGSQL_ASSOC);
            $norte_nom = $info['norte_nom'];
            $sur_nom = $info['sur_nom'];
            $este_nom = $info['este_nom'];
            $oeste_nom = $info['oeste_nom'];																				 
            pg_free_result($result3);
            $norte_nom_nuevo = str_replace ("$cod_cat", "$cod_cat_nuevo" , $norte_nom);
            $sur_nom_nuevo = str_replace ("$cod_cat", "$cod_cat_nuevo" , $sur_nom);
            $este_nom_nuevo = str_replace ("$cod_cat", "$cod_cat_nuevo" , $este_nom);
            $oeste_nom_nuevo = str_replace ("$cod_cat", "$cod_cat_nuevo" , $oeste_nom);
	          $sql4="UPDATE colindantes SET norte_nom = '$norte_nom_nuevo',sur_nom = '$sur_nom_nuevo',este_nom = '$este_nom_nuevo',oeste_nom = '$oeste_nom_nuevo'
	                WHERE id_predio = '$id_predio'";	 
	          pg_query($sql4);
				 }
		  }
   }
	 ### MODIFICAR FOTOS
   $filename1 = "C:/apache/htdocs/$folder/fotos/".$cod_cat.".jpg";
	 $filename1_nuevo = "C:/apache/htdocs/$folder/fotos/".$cod_cat_nuevo.".jpg";
   $filename2 = "C:/apache/htdocs/$folder/fotos/".$cod_cat."-A.jpg";
   $filename2_nuevo = "C:/apache/htdocs/$folder/fotos/".$cod_cat_nuevo."-A.jpg";	 
   $filename3 = "C:/apache/htdocs/$folder/fotos/".$cod_cat."-B.jpg";
   $filename3_nuevo = "C:/apache/htdocs/$folder/fotos/".$cod_cat_nuevo."-B.jpg";	 
   $filename4 = "C:/apache/htdocs/$folder/fotos/".$cod_cat."-C.jpg";
   $filename4_nuevo = "C:/apache/htdocs/$folder/fotos/".$cod_cat_nuevo."-C.jpg";	 
   $filename5 = "C:/apache/htdocs/$folder/fotos/".$cod_cat."-D.jpg";
   $filename5_nuevo = "C:/apache/htdocs/$folder/fotos/".$cod_cat_nuevo."-D.jpg";	 
   $filename6 = "C:/apache/htdocs/$folder/fotos/".$cod_cat."-E.jpg";
   $filename6_nuevo = "C:/apache/htdocs/$folder/fotos/".$cod_cat_nuevo."-E.jpg";	 				 				 				 				 
   if (file_exists($filename1)) {
	    copy($filename1,$filename1_nuevo);  
      unlink($filename1);		
      if (file_exists($filename2)) {
	       copy($filename2,$filename2_nuevo);
				 unlink($filename2);
         if (file_exists($filename3)) {
	          copy($filename3,$filename3_nuevo);
						unlink($filename3);
            if (file_exists($filename4)) {
	             copy($filename4,$filename4_nuevo);
							 unlink($filename4);
               if (file_exists($filename5)) {
	                copy($filename5,$filename5_nuevo);
									unlink($filename5);
                  if (file_exists($filename6)) {
	                   copy($filename6,$filename6_nuevo);
										 unlink($filename6);
	                }					
	             }					
	          }					
	       }					
	    }				
	 }
	 ### MODIFICAR OBSERVACION EN INFO_INMU
	 $sql="SELECT ctr_obs FROM info_inmu WHERE id_inmu = '$id_inmu'";	
   $result = pg_query($sql);
   $info = pg_fetch_array($result, null, PGSQL_ASSOC);
   $ctr_obs = utf8_decode($info['ctr_obs']);																			 
   pg_free_result($result);
   $ctr_obs_nuevo = $ctr_obs." - El código del predio cambio en fecha $fecha2. El código anterior era $cod_cat.";
	 $ctr_obs_nuevo = utf8_encode($ctr_obs_nuevo);
   $sql="UPDATE info_inmu SET ctr_obs = '$ctr_obs_nuevo' WHERE id_inmu = '$id_inmu'";	 
   pg_query($sql);
	 ### REGISTRO
	 $accion = utf8_encode("Cambio de código");
	 $username = get_username($session_id);
	 pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		          VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");	
	 ### USAR EL NUEVO CODIGO
	 $cod_uv = $cod_uv_nuevo;
	 $cod_man = $cod_man_nuevo;
	 $cod_pred = $cod_pred_nuevo;
	 $cod_blq = $cod_blq_nuevo;
	 $cod_piso = $cod_piso_nuevo;
	 $cod_apto = $cod_apto_nuevo;	  
	 $cod_cat = $cod_cat_nuevo; 	 									 	   							
}	 	 
/*
########################################
#------  --------#
########################################	
if ((isset($_POST["confirmar"])) AND ($_POST["confirmar"] == "SI")) {
   $confirmado = true;
	 $username = get_username($session_id);
	 if ((isset($_POST["todo"])) AND ($_POST["todo"] == "on")) {
	    $confirmado_borrar_todo = true;
      $sql="DELETE FROM info_predio WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
	    pg_query($sql);	
      $sql="DELETE FROM codigos WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
	    pg_query($sql);																
			$accion = "Predio borrado completamente";
		  pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");						
   } elseif ((isset($_POST["info_predio"])) AND ($_POST["info_predio"] == "on")) {   
	    $cod_pad = "";
	    $dir_tipo = $dir_nom = $dir_num = $dir_edif = $dir_bloq = $dir_piso = $dir_apto = "";
	    $tit_pers = "";
      $tit_cant = $tit_bene = $tit_cara = $tit_1pat = $tit_1mat = $tit_1nom1 = $tit_1nom2 = $tit_1ci = $tit_1nit = "";
      $tit_2pat = $tit_2mat = $tit_2nom1 = $tit_2nom2 = $tit_2ci = $tit_2nit = "";	 
	    $dom_dpto = $dom_ciu = $dom_dir = "";
			$der_num = "";
      $adq_modo = $adq_doc = "";
	    $der_fech = $adq_fech = $ctr_fech = "1900-01-01";	 
#	 $otr_ano = $otr_zona = "";	
      $via_tipo = $via_clas = $via_uso = $via_mat = "";
      $ser_alc = $ser_agu = $ser_luz = $ser_tel = $ser_gas = $ser_cab = "";	  
	    $ter_topo = $ter_form = $ter_ubi = $ter_nofr = "";
      $ter_fond = $ter_fren = $ter_sdoc = $ter_eesp = "";
      $esp_aac = $esp_tas = $esp_tae = $esp_ser = $esp_gar = $esp_dep = "";
      $mej_lav = $mej_par = $mej_hor = $mej_pis = $mej_otr = "";
      $ter_uso = $ter_mur = $ter_san = "";
	    $ctr_enc = $ctr_obs = $ctr_sup = "";
      pg_query("UPDATE info_predio SET cod_pad = '$cod_pad',
			   dir_tipo = '$dir_tipo', dir_nom = '$dir_nom', dir_num = '$dir_num',
				 dir_edif = '$dir_edif', dir_bloq = '$dir_bloq', dir_piso = '$dir_piso', dir_apto = '$dir_apto',
				 tit_pers = '$tit_pers', tit_cant = '$tit_cant', tit_bene = '$tit_bene', 
				 tit_1pat = '$tit_1pat', tit_1mat = '$tit_1mat', tit_1nom1 = '$tit_1nom1', tit_1nom2 = '$tit_1nom2',
				 tit_1ci = '$tit_1ci', tit_1nit = '$tit_1nit',
				 tit_2pat = '$tit_2pat', tit_2mat = '$tit_2mat', tit_2nom1 = '$tit_2nom1', tit_2nom2 = '$tit_2nom2',
				 tit_2ci = '$tit_2ci', tit_2nit = '$tit_2nit', tit_cara = '$tit_cara',
				 dom_dpto = '$dom_dpto', dom_ciu = '$dom_ciu', dom_dir = '$dom_dir',	
				 der_num = '$der_num', der_fech = '$der_fech', adq_modo = '$adq_modo', adq_doc = '$adq_doc', adq_fech = '$adq_fech',	
				 via_tipo = '$via_tipo', via_clas = '$via_clas', via_uso = '$via_uso', via_mat = '$via_mat',
				 ser_alc	= '$ser_alc', ser_agu	= '$ser_agu',	ser_luz	= '$ser_luz',	ser_tel	= '$ser_tel',	
				 ser_gas	= '$ser_gas',	ser_alu	= '$ser_alu',	ser_cab	= '$ser_cab',
				 ter_topo = '$ter_topo', ter_form = '$ter_form', ter_ubi = '$ter_ubi', ter_fren = '$ter_fren', 
				 ter_fond = '$ter_fond', ter_nofr = '$ter_nofr', ter_sdoc = '$ter_sdoc', ter_eesp = '$ter_eesp',
				 esp_aac = '$esp_aac', esp_tas = '$esp_tas', esp_tae = '$esp_tae',
				 esp_ser = '$esp_ser', esp_gar = '$esp_gar', esp_dep = '$esp_dep', 
				 mej_lav = '$mej_lav', mej_par = '$mej_par', mej_hor = '$mej_hor', mej_pis = '$mej_pis', mej_otr = '$mej_otr',
				 ter_uso = '$ter_uso', ter_mur = '$ter_mur', ter_san = '$ter_san', ter_ace = 'SIN',
				 ctr_enc = '$ctr_enc', ctr_sup = '$ctr_sup', ctr_fech = '$ctr_fech', ctr_obs = '$ctr_obs'		  
			   WHERE cod_cat = '$cod_cat'");				
			
			if (!$confirmado_borrar_todo) {
			   $accion = "Datos de Predio borrados";
		     pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");
			}	
   }
   if (((isset($_POST["info_edif"])) AND ($_POST["info_edif"] == "on")) OR ($confirmado_borrar_todo))  {  
      $sql="DELETE FROM info_edif WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
	    pg_query($sql);
			if (!$confirmado_borrar_todo) {			
		     $accion = "Info Edificaciones borrado";
		     pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");
			}
	 }	
   if (((isset($_POST["geo_predio"])) AND ($_POST["geo_predio"] == "on")) OR ($confirmado_borrar_todo))  {   
      $sql="DELETE FROM predios WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
	    pg_query($sql);
      $sql="DELETE FROM predios_ocha WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
	    pg_query($sql);
      $sql="DELETE FROM predios_ocha_orig WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
	    pg_query($sql);		
			$sql="DELETE FROM ochaves_linea WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
			pg_query($sql);								
			if (!$confirmado_borrar_todo) {
			   $accion = "Geometría de Predio borrada";
			   $accion = utf8_encode ($accion);
		     pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");
			}
	 }	
   if (((isset($_POST["geo_edif"])) AND ($_POST["geo_edif"] == "on")) OR ($confirmado_borrar_todo))  { 
      $sql="DELETE FROM edificaciones WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
	    pg_query($sql);
			if (!$confirmado_borrar_todo) {
			   $accion = "Geometría de Edif. borrada";
			   $accion = utf8_encode ($accion);			
		     pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");
			}
	 }
	 if (((isset($_POST["fotos"])) AND ($_POST["fotos"] == "on")) OR ($confirmado_borrar_todo))  { 	
      $filename1 = "C:/apache/htdocs/$folder/fotos/".$cod_cat.".jpg";
      $filename2 = "C:/apache/htdocs/$folder/fotos/".$cod_cat."-A.jpg";
      if (file_exists($filename1)) {	 
	       unlink($filename1);
      }	   
      if (file_exists($filename2)) {   
         unlink($filename2);
			}
			if (!$confirmado_borrar_todo) {
		     $accion = "Foto(s) borrada(s)";
		     pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");
			}
	 }		 
   if (((isset($_POST["gravamen"])) AND ($_POST["gravamen"] == "on")) OR ($confirmado_borrar_todo))  { 
      $sql="DELETE FROM gravamen WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
	    pg_query($sql);
			if (!$confirmado_borrar_todo) {			
			   $accion = "Gravamen borrado";		
		     pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");
			}
	 }	
   if (((isset($_POST["imp_pagados"])) AND ($_POST["imp_pagados"] == "on")) OR ($confirmado_borrar_todo))  { 
      $sql="DELETE FROM imp_pagados WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
	    pg_query($sql);
			if (!$confirmado_borrar_todo) {
			   $accion = "Impuestos borrados";		
		     pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");
			}
	 }	
   if (((isset($_POST["imp_plan_de_pago"])) AND ($_POST["imp_plan_de_pago"] == "on")) OR ($confirmado_borrar_todo))  { 
      $sql="DELETE FROM imp_plan_de_pago WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
	    pg_query($sql);
			if (!$confirmado_borrar_todo) {
			   $accion = "Plan de pago borrado";		
		     pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");
			}
	 }
   if (((isset($_POST["cambios"])) AND ($_POST["cambios"] == "on")) OR ($confirmado_borrar_todo))  { 
      $sql="DELETE FROM cambios WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
	    pg_query($sql);
      if (!$confirmado_borrar_todo) {
			   $accion = "Cambios borrados";		
		     pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");
			}
	 }	
   if (((isset($_POST["colindantes"])) AND ($_POST["colindantes"] == "on")) OR ($confirmado_borrar_todo))  { 
      $sql="DELETE FROM colindantes WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
	    pg_query($sql);
      if (!$confirmado_borrar_todo) {
			   $accion = "Colindantes borrados";		
		     pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");
			}
	 }		 
   if (((isset($_POST["transfer"])) AND ($_POST["transfer"] == "on")) OR ($confirmado_borrar_todo))  { 
      $sql="DELETE FROM transfer WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
	    pg_query($sql);
      if (!$confirmado_borrar_todo) {
			   $accion = "Transferencias borradas";		
		     pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		            VALUES ('$username','$ip','$fecha','$hora','$accion','$cod_cat')");
			}
	 }		  		 
	 	  
} else $confirmado = false;

########################################
#------------ BOTON BORRAR ------------#
########################################	
if (isset($_POST["submit"])) { 
   $disabled_todo = $disabled_info_predio = $disabled_info_edif = $disabled_geo_predio = pg_escape_string('disabled=\"disabled\"');
   $disabled_geo_edif = $disabled_fotos = $disabled_gravamen = $disabled_imp_pagados = pg_escape_string('disabled=\"disabled\"');
	 $disabled_imp_plan_de_pago = $disabled_cambios = $disabled_colindantes = $disabled_transfer = pg_escape_string('disabled=\"disabled\"');
	 	 
   if ((isset($_POST["todo"])) AND ($_POST["todo"] == "on")) { 
	    $confirmado_borrar_todo = true;
	    $todo = $cambio = "on";
		  $color_todo = "red";	
	    $checked_todo = pg_escape_string('checked=\"checked\"');			
	 } else {
	    $todo = "off";
		  $color_todo = "";
	    $checked_todo = "";
	 }	 
   if ((isset($_POST["info_predio"])) AND ($_POST["info_predio"] == "on")) { 
	    $info_predio = $cambio = "on";
		  $color_info_predio = "red";	
	    $checked_info_predio = pg_escape_string('checked=\"checked\"');			
	 } else {
	    $info_predio = "off";
		  $color_info_predio = "";
	    $checked_info_predio = "";
	 }
   if ((isset($_POST["info_edif"])) AND ($_POST["info_edif"] == "on")) {
	    $info_edif = $cambio = "on"; 
		  $color_info_edif = "red";		
	    $checked_info_edif = pg_escape_string('checked=\"checked\"');	
	 } else {
	    $info_edif = "off";
		  $color_info_edif = "";		
	    $checked_info_edif = "";
	 } 
   if ((isset($_POST["geo_predio"])) AND ($_POST["geo_predio"] == "on")) { 
	    $geo_predio = $cambio = "on";
		  $color_geo_predio = "red";		
	    $checked_geo_predio = pg_escape_string('checked=\"checked\"');	
   } else {
	    $geo_predio = "off";
		  $color_geo_predio = "";	
	    $checked_geo_predio = "";
   }
	 if ((isset($_POST["geo_edif"])) AND ($_POST["geo_edif"] == "on")) {
	    $geo_edif = $cambio = "on";	 
		  $color_geo_edif = "red";	
	    $checked_geo_edif = pg_escape_string('checked=\"checked\"');	
   } else {
	    $geo_edif = "off";	
		  $color_geo_edif = "";		 
	    $checked_geo_edif = "";
   }
	 if ((isset($_POST["fotos"])) AND ($_POST["fotos"] == "on")) {
	    $fotos = $cambio = "on";	
		  $color_fotos = "red";		 
	    $checked_fotos = pg_escape_string('checked=\"checked\"');	
   } else {
	    $fotos = "off";
		  $color_fotos = "";	
	    $checked_fotos = "";	 
	 } 
	if ((isset($_POST["gravamen"])) AND ($_POST["gravamen"] == "on")) {
	    $gravamen = $cambio = "on";	
		  $color_gravamen = "red";		 
	    $checked_gravamen = pg_escape_string('checked=\"checked\"');	
   } else {
	    $gravamen = "off";
		  $color_gravamen = "";	
	    $checked_gravamen = "";	 
	 }
	 if ((isset($_POST["imp_pagados"])) AND ($_POST["imp_pagados"] == "on")) {
	    $imp_pagados = $cambio = "on";	
		  $color_imp_pagados = "red";		 
	    $checked_imp_pagados = pg_escape_string('checked=\"checked\"');	
   } else {
	    $imp_pagados = "off";
		  $color_imp_pagados = "";	
	    $checked_imp_pagados = "";	 
	 } 	
	 if ((isset($_POST["imp_plan_de_pago"])) AND ($_POST["imp_plan_de_pago"] == "on")) {
	    $imp_plan_de_pago = $cambio = "on";	
		  $color_imp_plan_de_pago = "red";		 
	    $checked_imp_plan_de_pago = pg_escape_string('checked=\"checked\"');	
   } else {
	    $imp_plan_de_pago = "off";
		  $color_imp_plan_de_pago = "";	
	    $checked_imp_plan_de_pago = "";	 
	 } 
	 if ((isset($_POST["cambios"])) AND ($_POST["cambios"] == "on")) {
	    $cambios = $cambio = "on";	
		  $color_cambios = "red";		 
	    $checked_cambios = pg_escape_string('checked=\"checked\"');	
   } else {
	    $cambios = "off";
		  $color_cambios = "";	
	    $checked_cambios = "";	 
	 }
	 if ((isset($_POST["colindantes"])) AND ($_POST["colindantes"] == "on")) {
	    $colindantes = $cambio = "on";	
		  $color_colindantes = "red";		 
	    $checked_colindantes = pg_escape_string('checked=\"checked\"');	
   } else {
	    $colindantes = "off";
		  $color_colindantes = "";	
	    $checked_colindantes = "";	 
	 }
	 if ((isset($_POST["transfer"])) AND ($_POST["transfer"] == "on")) {
	    $transfer = $cambio = "on";	
		  $color_transfer = "red";		 
	    $checked_transfer = pg_escape_string('checked=\"checked\"');	
   } else {
	    $transfer = "off";
		  $color_transfer = "";	
	    $checked_transfer = "";	 
	 }		 		 	 	  	
} 

if ($cambio == "off") {
   $color_todo = $color_info_predio = $color_info_edif = $color_geo_predio = $color_geo_edif = $color_fotos = $color_gravamen = $color_imp_pagados = $color_imp_plan_de_pago = $color_cambios = $color_colindantes = $color_transfer = "";
	 ########################################
   #---------------- TODO ----------------#
   ########################################   
   $disabled_todo = "";
   $checked_todo = "";		 
	 ########################################
   #------------- INFO PREDIO ------------#
   ########################################
   $sql="SELECT dir_tipo FROM info_predio WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
   $result = pg_query($sql);
   $info = pg_fetch_array($result, null, PGSQL_ASSOC);
   $dir_tipo = $info['dir_tipo'];														 
   pg_free_result($result);
	 if (($dir_tipo == "A")	OR ($dir_tipo == "C") OR ($dir_tipo == "P")) {	 	 
      $disabled_info_predio = "";
	 } else $disabled_info_predio = pg_escape_string('disabled=\"disabled\"');
   $checked_info_predio = "";	 
   ########################################
   #-- CHEQUEAR POR DATOS EDIFICACIONES --#
   ########################################		 
   $sql="SELECT cod_uv FROM info_edif WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
   $check_integrity = pg_num_rows(pg_query($sql));
   if ($check_integrity == 0 ) {
      $disabled_info_edif = pg_escape_string('disabled=\"disabled\"');
   }	else {
      $disabled_info_edif = "";
   }
	 $checked_info_edif = "";
   ########################################
   #--- CHEQUEAR POR GEOMETRIA PREDIOS ---#
   ########################################	
   $sql="SELECT cod_uv FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
   $check_integrity = pg_num_rows(pg_query($sql));
   if ($check_integrity == 0 ) {	
      $disabled_geo_predio = pg_escape_string('disabled=\"disabled\"');
   }	else {
      $disabled_geo_predio = "";
   }
	 $checked_geo_predio = "";
   ########################################
   # CHEQUEAR POR GEOMETRIA EDIFICACIONES #
   ########################################	
   $sql="SELECT cod_uv FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
   $check_integrity = pg_num_rows(pg_query($sql));
   if ($check_integrity == 0 ) {	
      $disabled_geo_edif = pg_escape_string('disabled=\"disabled\"');
   }	else {
      $disabled_geo_edif = "";
   }
	 $checked_geo_edif = "";	 
   ########################################
   #-------- CHEQUEAR POR FOTOS ----------#
   ########################################	
   $filename1 = "C:/apache/htdocs/$folder/fotos/".$cod_cat.".JPG";
   $filename2 = "C:/apache/htdocs/$folder/fotos/".$cod_cat."-A.JPG";
   if ((file_exists($filename1)) OR (file_exists($filename2))) {
	    $disabled_fotos = "";
   } else { 
	    $disabled_fotos = pg_escape_string('disabled=\"disabled\"');      	
   }
   $checked_fotos = ""; 	
   ########################################
   #------- CHEQUEAR POR GRAVAMEN --------#
   ########################################	
   $sql="SELECT id_inmu FROM gravamen WHERE id_inmu = '$id_inmu'";
   $check_integrity = pg_num_rows(pg_query($sql));
   if ($check_integrity == 0 ) {	
      $disabled_gravamen = pg_escape_string('disabled=\"disabled\"');
   }	else {
      $disabled_gravamen = "";
   }
	 $checked_gravamen = "";	 
   ########################################
   #------- CHEQUEAR POR IMPUESTOS -------#
   ########################################	
   $sql="SELECT id_inmu FROM imp_pagados WHERE id_inmu = '$id_inmu'";
   $check_integrity = pg_num_rows(pg_query($sql));
   if ($check_integrity == 0 ) {	
      $disabled_imp_pagados = pg_escape_string('disabled=\"disabled\"');
   }	else {
      $disabled_imp_pagados = "";
   }
	 $checked_imp_pagados = "";
   ########################################
   #----- CHEQUEAR POR PLAN DE PAGO ------#
   ########################################	
   $sql="SELECT cod_uv FROM imp_plan_de_pago WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
   $check_integrity = pg_num_rows(pg_query($sql));
   if ($check_integrity == 0 ) {	
      $disabled_imp_plan_de_pago = pg_escape_string('disabled=\"disabled\"');
   }	else {
      $disabled_imp_plan_de_pago = "";
   }
	 $checked_imp_plan_de_pago = "";
   ########################################
   #-------- CHEQUEAR POR CAMBIOS --------#
   ########################################	
   $sql="SELECT id_inmu FROM cambios WHERE id_inmu = '$id_inmu'";
   $check_integrity = pg_num_rows(pg_query($sql));
   if ($check_integrity == 0 ) {	
      $disabled_cambios = pg_escape_string('disabled=\"disabled\"');
   }	else {
      $disabled_cambios = "";
   }
	 $checked_cambios = "";
   ########################################
   #------ CHEQUEAR POR COLINDANTES ------#
   ########################################	
   $sql="SELECT cod_uv FROM colindantes WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
   $check_integrity = pg_num_rows(pg_query($sql));
   if ($check_integrity == 0 ) {	
      $disabled_colindantes = pg_escape_string('disabled=\"disabled\"');
   }	else {
      $disabled_colindantes = "";
   }
	 $checked_colindantes = "";
   ########################################
   #----- CHEQUEAR POR TRANSFERENCIAS ----#
   ########################################	
   $sql="SELECT id_inmu FROM transfer WHERE id_inmu = '$id_inmu'";
   $check_integrity = pg_num_rows(pg_query($sql));
   if ($check_integrity == 0 ) {	
      $disabled_transfer = pg_escape_string('disabled=\"disabled\"');
   }	else {
      $disabled_transfer = "";
   }
	 $checked_transfer = "";		 		 	  		 	 
}
*/

################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	
if ((isset($_POST["codigo_nuevo"])) AND ($_POST["codigo_nuevo"] == "SI")) {

include "siicat_busqueda_resultado.php";

} else {
	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
# Fila 1
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"10%\">\n";  #Col. 1 
   echo "            &nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&id=$session_id#tab-6\">\n";		
   echo "            <img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";	
	 echo "         </td>\n";	  	    
   echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"65%\" class=\"pageName\">\n"; 
	 echo "            Modificar Código\n";                          
   echo "         </td>\n";
	 echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n"; 
   echo "      <tr>\n"; 	
	 echo "         <td> &nbsp </td>\n";  #Col. 1 	  
   echo "         <td valign=\"top\" height=\"40\" colspan=\"1\">\n";   #Col. 1+2+3  
   echo "         <fieldset style=\"border-color: lightgrey; background-color: #EEEEEE;\"><legend>Códificación actual del Inmueble</legend>\n";
   echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  13 Columnas
   echo "               <tr>\n";
   echo "                  <td align=\"right\" colspan=\"13\" class=\"bodyText\"></td>\n";   #Col. 1	 
   echo "               </tr>\n";	   
   echo "               <tr>\n";  	                     
   echo "                  <td width=\"2%\"></td>\n";   #Col. 1	
   echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">U.V.</td>\n";   #Col. 2	    	  	 
   echo "                  <td align=\"left\" width=\"8%\" class=\"bodyTextD\">&nbsp $cod_uv</td>\n";   #Col. 3	    
   echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">Mz.</td>\n";   #Col. 5	  
   echo "                  <td align=\"left\" width=\"8%\" class=\"bodyTextD\">&nbsp $cod_man</td>\n";   #Col. 6	  	 
   echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">Predio</td>\n";   #Col. 8 
   echo "                  <td align=\"left\" width=\"8%\" class=\"bodyTextD\">&nbsp $cod_pred</td>\n";   #Col. 9	   	 
   echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextH\">Bloque</td>\n";   #Col. 11 
   echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextD\">&nbsp $cod_blq</td>\n";	 #Col. 12    	 
   echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextH\">Piso</td>\n";   #Col. 8 
   echo "                  <td align=\"left\" width=\"8%\" class=\"bodyTextD\">&nbsp $cod_piso</td>\n";   #Col. 9	   	 
   echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextH\">Apto.</td>\n";   #Col. 11 
   echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextD\">&nbsp $cod_apto</td>\n";	 #Col. 12 
   echo "                  <td width=\"2%\"></td>\n";   #Col. 13  	 	 	   	 	 	    
   echo "               </tr>\n";
   echo "            </table>\n"; 
   echo "         </fieldset>\n";	 	 
   echo "         </td>\n";
	 echo "         <td> &nbsp </td>\n";  #Col. 3 		  
   echo "      </tr>\n";
   echo "      <tr height=\"40px\">\n";
	 echo "         <td>&nbsp </td>\n";	  	    
   echo "         <td align=\"left\">\n"; 
	 echo "            Atención: Por favor, tomar en cuenta que la modificación del código de un inmueble no solo afecta el mismo inmueble (certificados, impuestos, fotos, etc.) sino también los inmuebles colindantes! \n";                          
   echo "         </td>\n";
	 echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";	 
   echo "      <tr>\n"; 	
	 echo "         <td> &nbsp </td>\n";  #Col. 1 	  
   echo "         <td valign=\"top\" height=\"40\" colspan=\"1\">\n";   #Col. 1+2+3
	 echo "					<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=8&id=$session_id\" accept-charset=\"utf-8\">\n";	   
   echo "         <fieldset><legend>Nueva Códificación</legend>\n";
   echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  13 Columnas
   echo "               <tr>\n";
   echo "                  <td align=\"right\" colspan=\"14\" class=\"bodyText\"></td>\n";   #Col. 1	 
   echo "               </tr>\n";	   
   echo "               <tr>\n";  	                     
   echo "                  <td width=\"2%\"></td>\n";   #Col. 1	
   echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">U.V.</td>\n";   #Col. 2	    	  	 
   echo "                  <td align=\"left\" width=\"8%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_uv_nuevo\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_uv\" value=\"$cod_uv_nuevo\" $disabled></td>\n";	   
   echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">Mz.</td>\n";   #Col. 4	  
   echo "                  <td align=\"left\" width=\"8%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_man_nuevo\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_man\" value=\"$cod_man_nuevo\" $disabled></td>\n";  	 
   echo "                  <td align=\"center\" width=\"8%\" class=\"bodyTextH\">Predio</td>\n";   #Col. 6 
   echo "                  <td align=\"left\" width=\"8%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_pred_nuevo\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_pred\" value=\"$cod_pred_nuevo\" $disabled></td>\n";   	 
   echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextH\">Bloque</td>\n";   #Col. 8 
   echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_blq_nuevo\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_blq\" value=\"$cod_blq_nuevo\" $disabled></td>\n";   	 
   echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextH\">Piso</td>\n";   #Col. 10 
   echo "                  <td align=\"left\" width=\"8%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_piso_nuevo\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_piso\" value=\"$cod_piso_nuevo\" $disabled></td>\n";	   	 
   echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextH\">Apto.</td>\n";   #Col. 12 
   echo "                  <td align=\"center\" width=\"10%\" class=\"bodyTextD\"><input type=\"text\" name=\"cod_apto_nuevo\" id=\"form_anadir2\" class=\"navText\" maxlength=\"$max_strlen_apto\" value=\"$cod_apto_nuevo\" $disabled></td>\n"; 
   echo "                  <td width=\"2%\"></td>\n";   #Col. 14  	 	 	   	 	 	    
   echo "               </tr>\n";
   if ((!isset($_POST["submit"])) OR ((isset($_POST["submit"])) AND ($_POST["submit"] != "Modificar"))  OR ($error)) {
   echo "               <tr height=\"40\">\n";
   echo "                  <td align=\"center\" colspan=\"14\">\n";	 
   echo "                     <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";		 
   echo "                     <input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n"; 			 		 
   echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Modificar\">\n";
   echo "                  </td>\n";            
	 echo "               </tr>\n";
	 }
	 if ($error) {
      echo "               <tr height=\"40\">\n"; 
 	    echo "                  <td align=\"center\" colspan=\"14\">\n";
			echo "                     <font color=\"red\"> $mensaje_de_error</font>\n"; 
	    echo "                  </td>\n";		  	 
	    echo "               </tr>\n";				
	 }
	 if ($aviso_mas_inmuebles) {
      echo "               <tr height=\"40\">\n"; 
 	    echo "                  <td align=\"center\" colspan=\"14\">\n";
			echo "                     <font color=\"grey\"> $mensaje_mas_inmuebles</font>\n"; 
	    echo "                  </td>\n";		  	 
	    echo "               </tr>\n";				
	 }	
	 if ($aviso_colindantes) {
      echo "               <tr height=\"40\">\n"; 
 	    echo "                  <td align=\"center\" colspan=\"14\">\n";
			echo "                     <font color=\"grey\"> $mensaje_colindantes</font>\n"; 
	    echo "                  </td>\n";		  	 
	    echo "               </tr>\n";				
	 }
	 if ($aviso_fotos) {
      echo "               <tr height=\"40\">\n"; 
 	    echo "                  <td align=\"center\" colspan=\"14\">\n";
			echo "                     <font color=\"grey\"> $mensaje_fotos</font>\n"; 
	    echo "                  </td>\n";		  	 
	    echo "               </tr>\n";				
	 }	 	
   if ((isset($_POST["submit"])) AND ($_POST["submit"] == "Modificar") AND (!$error)) {
      echo "               <tr>\n"; 
 	    echo "                  <td align=\"center\" colspan=\"14\">\n";
			echo "                     <font color=\"red\"> Está segura/o de cambiar el código del inmueble? &nbsp</font>\n"; 
      echo "                     <input name=\"codigo_nuevo\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"SI\">&nbsp&nbsp&nbsp\n";	
      echo "                     <input name=\"codigo_nuevo\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"NO\">\n";
      echo "                     <input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";			
      echo "                     <input name=\"cod_uv_nuevo\" type=\"hidden\" value=\"$cod_uv_nuevo\">\n";
      echo "                     <input name=\"cod_man_nuevo\" type=\"hidden\" value=\"$cod_man_nuevo\">\n";
      echo "                     <input name=\"cod_pred_nuevo\" type=\"hidden\" value=\"$cod_pred_nuevo\">\n";
      echo "                     <input name=\"cod_blq_nuevo\" type=\"hidden\" value=\"$cod_blq_nuevo\">\n";
      echo "                     <input name=\"cod_piso_nuevo\" type=\"hidden\" value=\"$cod_piso_nuevo\">\n";
      echo "                     <input name=\"cod_apto_nuevo\" type=\"hidden\" value=\"$cod_apto_nuevo\">\n";																							
	    echo "                  </td>\n";		  	 
	    echo "               </tr>\n";	
	 }	 	  			 	 	 
   echo "            </table>\n"; 
   echo "         </fieldset>\n";	 	 
	 echo "         </form>\n";	 
   echo "         </td>\n";
	 echo "         <td> &nbsp </td>\n";  #Col. 3 		  
   echo "      </tr>\n";	
	/*  
#	 if (!$confirmado) { 
   # Fila 2
	 echo "      <tr>\n";  
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	  
	 echo "					<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id#tab-8\" accept-charset=\"utf-8\">\n";
	 echo "         <fieldset><legend>Marque los objetos para borrar</legend>\n";
   echo "            <table width=\"100%\" border=\"0\">\n";
	 echo "               <tr>\n";
 	 echo "                  <td width=\"30%\"> &nbsp</td>\n";
	 echo "                  <td width=\"40%\" align=\"left\">\n"; 
	 echo "                     <input name=\"todo\" type=\"checkbox\" $checked_todo $disabled_todo><font color=\"$color_todo\"> TODOS LOS DATOS</font>\n";
	 echo "                  </td>\n";
	 echo "                  <td width=\"30%\"> &nbsp</td>\n";
	 echo "               </tr>\n"; 
	 echo "               <tr>\n";
 	 echo "                  <td width=\"30%\"> &nbsp</td>\n";
	 echo "                  <td width=\"40%\" align=\"left\">\n"; 
	 echo "                      -------------------------------------------------\n";
	 echo "                  </td>\n";
	 echo "                  <td width=\"30%\"> &nbsp</td>\n";
	 echo "               </tr>\n"; 	 	 
	 echo "               <tr>\n";
 	 echo "                  <td width=\"30%\"> &nbsp</td>\n";
	 echo "                  <td width=\"40%\" align=\"left\">\n"; 
	 echo "                     <input name=\"info_predio\" type=\"checkbox\" $checked_info_predio $disabled_info_predio><font color=\"$color_info_predio\"> Datos del $predio</font>\n";
	 echo "                  </td>\n";
	 echo "                  <td width=\"30%\"> &nbsp</td>\n";
	 echo "               </tr>\n"; 
	 echo "               <tr>\n";
 	 echo "                  <td align=\"right\"> &nbsp</td>\n";
	 echo "                  <td align=\"left\">\n"; 
	 echo "                     <input name=\"info_edif\" type=\"checkbox\" $checked_info_edif $disabled_info_edif><font color=\"$color_info_edif\"> Datos de Edificaciones</font>\n";
	 echo "                  </td>\n";
	 echo "                  <td> &nbsp</td>\n";
	 echo "               </tr>\n"; 	
	 echo "               <tr>\n";
 	 echo "                  <td align=\"right\"> &nbsp</td>\n";
	 echo "                  <td align=\"left\">\n"; 
	 echo "                     <input name=\"geo_predio\" type=\"checkbox\" $checked_geo_predio $disabled_geo_predio><font color=\"$color_geo_predio\"> Geometría del $predio</font>\n";
	 echo "                  </td>\n";
	 echo "                  <td> &nbsp</td>\n";
	 echo "               </tr>\n";	 
	 echo "               <tr>\n";
 	 echo "                  <td align=\"right\"> &nbsp</td>\n";
	 echo "                  <td align=\"left\">\n"; 
	 echo "                     <input name=\"geo_edif\" type=\"checkbox\" $checked_geo_edif $disabled_geo_edif><font color=\"$color_geo_edif\"> Geometría de las Edificaciones</font>\n";
	 echo "                  </td>\n";
	 echo "                  <td> &nbsp</td>\n";
	 echo "               </tr>\n";	 
	 echo "               <tr>\n";
 	 echo "                  <td align=\"right\"> &nbsp</td>\n";
	 echo "                  <td align=\"left\">\n"; 
	 echo "                     <input name=\"fotos\" type=\"checkbox\" $checked_fotos $disabled_fotos><font color=\"$color_fotos\"> Fotos</font>\n";
	 echo "                  </td>\n";
	 echo "                  <td> &nbsp</td>\n";
	 echo "               </tr>\n";
	 echo "               <tr>\n";
 	 echo "                  <td align=\"right\"> &nbsp</td>\n";
	 echo "                  <td align=\"left\">\n"; 
	 echo "                     <input name=\"gravamen\" type=\"checkbox\" $checked_gravamen $disabled_gravamen><font color=\"$color_gravamen\"> Gravamen</font>\n";
	 echo "                  </td>\n";
	 echo "                  <td> &nbsp</td>\n";
	 echo "               </tr>\n";
	 echo "               <tr>\n";
 	 echo "                  <td align=\"right\"> &nbsp</td>\n";
	 echo "                  <td align=\"left\">\n"; 
	 echo "                     <input name=\"imp_pagados\" type=\"checkbox\" $checked_imp_pagados $disabled_imp_pagados><font color=\"$color_imp_pagados\"> Impuestos pagados</font>\n";
	 echo "                  </td>\n";
	 echo "                  <td> &nbsp</td>\n";
	 echo "               </tr>\n";
	 echo "               <tr>\n";
 	 echo "                  <td align=\"right\"> &nbsp</td>\n";
	 echo "                  <td align=\"left\">\n"; 
	 echo "                     <input name=\"imp_plan_de_pago\" type=\"checkbox\" $checked_imp_plan_de_pago $disabled_imp_plan_de_pago><font color=\"$color_imp_plan_de_pago\"> Plan de Pago</font>\n";
	 echo "                  </td>\n";
	 echo "                  <td> &nbsp</td>\n";
	 echo "               </tr>\n";
	 echo "               <tr>\n";
 	 echo "                  <td align=\"right\"> &nbsp</td>\n";
	 echo "                  <td align=\"left\">\n"; 
	 echo "                     <input name=\"cambios\" type=\"checkbox\" $checked_cambios $disabled_cambios><font color=\"$color_cambios\"> Cambios</font>\n";
	 echo "                  </td>\n";
	 echo "                  <td> &nbsp</td>\n";
	 echo "               </tr>\n";
	 echo "               <tr>\n";
 	 echo "                  <td align=\"right\"> &nbsp</td>\n";
	 echo "                  <td align=\"left\">\n"; 
	 echo "                     <input name=\"colindantes\" type=\"checkbox\" $checked_colindantes $disabled_colindantes><font color=\"$color_colindantes\"> Colindantes</font>\n";
	 echo "                  </td>\n";
	 echo "                  <td> &nbsp</td>\n";
	 echo "               </tr>\n";
	 echo "               <tr>\n";
 	 echo "                  <td align=\"right\"> &nbsp</td>\n";
	 echo "                  <td align=\"left\">\n"; 
	 echo "                     <input name=\"transfer\" type=\"checkbox\" $checked_transfer $disabled_transfer><font color=\"$color_transfer\"> Transfer</font>\n";
	 echo "                  </td>\n";
	 echo "                  <td> &nbsp</td>\n";
	 echo "               </tr>\n";	 	 	 	 	 	 
	 if ((!isset($_POST["submit"])) OR ($cambio == "off")) {  
 	    echo "               <tr>\n";
 	    echo "                  <td> &nbsp</td>\n";
	    echo "                  <td align=\"center\">\n";	 
	    echo "                     <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";		 
	    echo "                     <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n"; 		
#	    echo "                     <input name=\"titular1\" type=\"hidden\" value=\"$titular1\">\n"; 
#	    echo "                     <input name=\"direccion\" type=\"hidden\" value=\"$direccion\">\n";		 		 
	    echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Borrar\">\n";
      echo "                  </td>\n";
	    echo "                  <td> &nbsp</td>\n";            
	    echo "               </tr>\n";				
	 } else {
 	    echo "      <tr>\n"; 
 	    echo "         <td align=\"center\" colspan=\"3\">\n";
			echo "            <font color=\"red\"> Está seguro de borrar los objetos seleccionados de la base de datos?</font>\n"; 
	    echo "         </td>\n";	  	 
	    echo "      </tr>\n";	 
 	    echo "      <tr>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 1  
 	    echo "         <td align=\"center\">\n"; 
			echo "            <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n"; 		
  		echo "            <input name=\"todo\" type=\"hidden\" value=\"$todo\">\n";
  		echo "            <input name=\"info_predio\" type=\"hidden\" value=\"$info_predio\">\n";
  		echo "            <input name=\"info_edif\" type=\"hidden\" value=\"$info_edif\">\n";
  		echo "            <input name=\"geo_predio\" type=\"hidden\" value=\"$geo_predio\">\n";
  		echo "            <input name=\"geo_edif\" type=\"hidden\" value=\"$geo_edif\">\n";	
  		echo "            <input name=\"fotos\" type=\"hidden\" value=\"$fotos\">\n";	
  		echo "            <input name=\"gravamen\" type=\"hidden\" value=\"$gravamen\">\n";
  		echo "            <input name=\"imp_pagados\" type=\"hidden\" value=\"$imp_pagados\">\n";
  		echo "            <input name=\"imp_plan_de_pago\" type=\"hidden\" value=\"$imp_plan_de_pago\">\n";	
  		echo "            <input name=\"cambios\" type=\"hidden\" value=\"$cambios\">\n";	
  		echo "            <input name=\"colindantes\" type=\"hidden\" value=\"$colindantes\">\n";	
  		echo "            <input name=\"transfer\" type=\"hidden\" value=\"$transfer\">\n";																					 							
			echo "            <input name=\"confirmar\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"SI\">\n"; 
 	    echo "            &nbsp&nbsp&nbsp&nbsp&nbsp<input name=\"confirmar\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"NO\">\n"; 			
	    echo "         </td>\n";	
	    echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	    echo "      </tr>\n"; 	 
	 }   
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";
	 echo "         </form>\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3   		
	 echo "      </tr>\n";
	 if (($cambio == "off") AND (isset($_POST["submit"]))) {
      echo "      <tr>\n"; 
 	    echo "         <td> &nbsp</td>\n";   #Col. 1
			echo "         <td align=\"center\">\n";   #Col. 2
			echo "            <font color=\"red\"> No ha elegido ningun objeto para borrar</font>\n"; 
	    echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3 				  	 
	    echo "      </tr>\n";				
	 }	 
	 if ($confirmado) {	 
      # PENULTIMA FILA
      echo "      <tr height=\"40px\">\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 1 	    
      echo "         <td align=\"center\">\n"; 
	    echo "            Los datos seleccionados han sido eliminados de la base de datos!\n";                          
      echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3 			 
      echo "      </tr>\n";		    
	 }   	 	 	  
	 echo "      </form>\n";		 */
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
	# echo "   <br />&nbsp;<br />\n";
#	 echo "</td>\n";	
}
?>