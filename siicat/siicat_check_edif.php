<?php

if (isset($_POST["id_inmu"])) {   
	 #$cod_cat = $_POST["cod_cat"];
	 #$cod_uv = get_uv($cod_cat);
	 #$cod_man = get_man($cod_cat);
	 #$cod_lote = get_lote($cod_cat);
	# $cod_subl = get_subl($cod_cat);	 
	 $codigo_fijo = true;
}	elseif ((isset($_POST["cod_uv"])) AND (isset($_POST["cod_man"])) AND (isset($_POST["cod_pred"])) ) {
	 $cod_uv = (int) $_POST["cod_uv"];
	 $cod_man = (int) $_POST["cod_man"];
	 $cod_pred = (int) $_POST["cod_pred"]; 
	# $cod_cat = getcodcat($cod_uv,$cod_man,$cod_lote,$cod_subl);
   $codigo_fijo = false;
} else $codigo_fijo = false;

#if (isset($_POST["search_string"])) {   
#	 $search_string = $_POST["search_string"];
#} else $search_string = "";

if (isset($_POST["no_de_edificaciones"])) {		
   $no_de_edificaciones = $_POST["no_de_edificaciones"]+1;
} else $no_de_edificaciones = 1;

if (isset($_POST["accion"])) {		
   $accion = utf8_decode($_POST["accion"]);
} else $accion = "";

$error = $error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = false;
$error_codigo = false;
$tabla_edif_rellenada = false;
################################################################################
#--------------------------- AÑADIR EDIFICACIONES -----------------------------#
################################################################################	 
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "AÃ±adir Edificaciones")) {
#   $tabla_rellenada = true;
#    if (!isset($_POST["accion"])) {
#	    $cod_uv = $cod_man = $cod_pred = $cod_cat = "";
	   # $manual = true;
#   }
	 $accion = "Añadir";	  
	 include "c:/apache/siicat/siicat_form_edif.php";
}
################################################################################
#------------------------------- MODIFICAR DATOS ------------------------------#
################################################################################	 
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Modificar Edificaciones")) {
	 $accion = "Modificar"; 
   include "c:/apache/siicat/siicat_modificar_edif.php";
}
################################################################################
#------------------- PARA AÑADIR EDIFICACIONES MANUALMENTE --------------------#
################################################################################	
#if ((isset($_POST["accion"])) AND (($_POST["accion"]) == "AÃ±adir Edificaciones") AND (!isset($_POST["volver"]))) {	  
if (isset($_POST["edif"])) {  
   if ((isset($_POST["edif"])) AND ($_POST["edif"] == "AÃ±adir Edificaciones")) {
	    $accion = "Añadir";
	 }
	 ##########################################################
	 #$manual = true;
	 #$titulo = "Edificaciones";	 
	 #$tabla_rellenada = true;
	 #$anadir_edif = true;
	 #$cod_uv = $_POST["cod_uv"];
	 #$cod_man = $_POST["cod_man"];
	 #$cod_pred = $_POST["cod_pred"];
	 #$cod_subl = $_POST["cod_subl"];	 
	 #$cod_cat = get_codcat($cod_uv,$cod_man,$cod_lote,$cod_subl);
   #############################################################
	 if (isset($_POST["edif"])) {	
			$edif = $_POST["edif"];	
			$edif_check = substr($edif, 0, 13);	
		  if (($edif == "MÃ¡s Unidades Constructivas") OR ($edif_check == "Borrar Unidad")) {
	       $boton_mas_unidades = true;
			   $codigo_fijo = false;
	    } else $boton_mas_unidades = false;	
	 }
	 if ((!check_int($cod_uv)) OR (!check_int($cod_man)) OR (!check_int($cod_pred))) {
	    $error2 = true;
	    $mensaje_de_error2 = "Error: Solo se permite números para el código del $predio!";			
	 }		 
	 $sql="SELECT cod_uv FROM info_predio WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
   $check = pg_num_rows(pg_query($sql));	  
	 if (($check == 0) AND (!$boton_mas_unidades)) {
	    $error2 = true;
	    $mensaje_de_error2 = "Error: No existe ningún predio con ese código en la base de datos. Por favor, ingrese primero los datos del predio y después las edificaciones!";
	 }
   if (isset($_POST["edif"])) {	
	    if ($_POST["no_de_edificaciones"] == 0) {
         $edi_num = $edi_piso = $edi_ubi = $edi_tipo = $edi_edo = $edi_ano = $edi_cim = $edi_est = $edi_mur = "";
			   $edi_rvin = $edi_rvex = $edi_rvba = $edi_rvco = $edi_acab = $edi_cest = $edi_ctec = $edi_ciel = "";
			   $edi_coc = $edi_ban = $edi_carp = $edi_elec = "";	    
	    } else {
			   $edif = $_POST["edif"];	    	
         $edi_num = $_POST["edi_num"];
         $edi_piso = $_POST["edi_piso"];
         $edi_ubi = "";
         $edi_tipo = $_POST["edi_tipo"];
         $edi_edo = $_POST["edi_edo"];
         $edi_ano = $_POST["edi_ano"];
         $edi_cim = $_POST["edi_cim"];
	       $edi_est = $_POST["edi_est"];
	       $edi_mur = $_POST["edi_mur"];
	       $edi_rvin = $_POST["edi_rvin"];
	       $edi_rvex = $_POST["edi_rvex"];
	       $edi_rvba = $_POST["edi_rvba"];
	       $edi_rvco = $_POST["edi_rvco"];
			   $edi_acab = $_POST["edi_acab"];
         $edi_cest = $_POST["edi_cest"];
	       $edi_ctec = $_POST["edi_ctec"];
	       $edi_ciel = $_POST["edi_ciel"];
	       $edi_coc = $_POST["edi_coc"];
	       $edi_ban = $_POST["edi_ban"];
	       $edi_carp = $_POST["edi_carp"];
	       $edi_elec = $_POST["edi_elec"];
			   $edif_check = substr($edif, 0, 13);
			}
			########################################
	    #     BOTON BORRAR EDIFICACIONES       #
	    ########################################		
			if ($edif_check == "Borrar Unidad") {
			   $borrar_edif = true;
			   $digitos = strlen($edif)-14;
			   $edif_para_borrar = substr($edif, 14, $digitos);
#echo "CORTADO:$edif_check,DIGITOS:$digitos,PARA BORRAR:$edif_para_borrar";	
				 $i = $j = 0;
				 $no_de_edificaciones = $no_de_edificaciones-2;	
				# $edif_array = array ();				 
				 while ($i < $no_de_edificaciones) {
						if ($i+1 == $edif_para_borrar) {
						   $j++;    
						}
            $edi_num[$i] = $edi_num[$j];$edi_piso[$i] = $edi_piso[$j];$edi_tipo[$i] = $edi_tipo[$j];
						$edi_edo[$i] = $edi_edo[$j];$edi_ano[$i] = $edi_ano[$j];$edi_cim[$i] = $edi_cim[$j];
            $edi_est[$i] = $edi_est[$j];$edi_mur[$i] = $edi_mur[$j];$edi_rvin[$i] = $edi_rvin[$j];
						$edi_rvex[$i] = $edi_rvex[$j];$edi_rvba[$i] = $edi_rvba[$j];$edi_rvco[$i] = $edi_rvco[$j];
						$edi_acab[$i] = $edi_acab[$j];$edi_cest[$i] = $edi_cest[$j];$edi_ctec[$i] = $edi_ctec[$j];
						$edi_ciel[$i] = $edi_ciel[$j];$edi_coc[$i] = $edi_coc[$j];$edi_ban[$i] = $edi_ban[$j];
						$edi_carp[$i] = $edi_carp[$j];$edi_elec[$i] = $edi_elec[$j];
						$i++;
						$j++;
				 }		 
			}	
			########################################
	    #     BOTON AÑADIR EDIFICACIONES       #
	    ########################################		
			if (($edif == "AÃ±adir Edificaciones") OR ($edif == "Modificar Edificaciones")){
			   $i = 0;
				 while ($i < $no_de_edificaciones-1) {
				    $j = 0;
						$ii = $i+1;						
						if ($edi_piso[$i] > 0) {
						   $segundo_piso_en_el_aire = true;   
						} else {
						   $segundo_piso_en_el_aire = false;
						}
						while ($j < $no_de_edificaciones-1) {
						   if (($edi_num[$i] == $edi_num[$j]) AND ($i != $j)){
						      # CHEQUEAR SI HAY UNIDADES CON LA MISMA NUMERACION							 
							    if ($edi_piso[$i] == $edi_piso[$j]) {
						         $error1 = true;
									   $mensaje_de_error1 = "Error: La Unidad $ii tiene la misma numeración que la Unidad $i!";	          										
							    }
									# CHEQUEAR SI EL SEGUNDO PISO TIENE UN PISO DEBAJO
									if (($edi_piso[$i] > 1) AND ($edi_piso[$j] == $edi_piso[$i]-1)) {							 
							       $segundo_piso_en_el_aire = false;
									}								
							 }							 
							 $j++;
						} # END_OF_WHILE (CHEQUEO CON TODAS LAS UNIDADES $j)
						# CHEQUEAR SI EL AÑO ESTA CORRECTO
						if (($edi_ano[$i] < 1900) OR ($edi_ano[$i] > $ano_actual)) {
						   $error1 = true;	
               $mensaje_de_error1 = "Error: El Año de la Unidad Constructiva $ii no es correcto!";							
						}
						# CHEQUEAR SI HAY SEGUNDOS PISOS SIN PISO DEBAJO						
						if ($segundo_piso_en_el_aire) {
						   $error1 = true;	
               $mensaje_de_error1 = "Error: La Unidad Constructiva $ii no tiene piso inferior!";		
						}
						if (($edi_num[$i] < 1) OR ($edi_num[$i] > 99)) {
							 $error1 = true;	
               $mensaje_de_error1 = "Error: El número para la Edificación tiene que ser entre 1 y 99!";	
						}
						if (($edi_piso[$i] < 0) OR ($edi_piso[$i] > 99)) {
							 $error1 = true;	
               $mensaje_de_error1 = "Error: El número para el Piso tiene que ser entre 0 y 99!";	
						}						
				    $i++; 									
         } # END_OF_WHILE (CHEQUEO CON CADA UNIDAD $i)
				 if (($error1) OR ($error2)) {
				    $no_de_edificaciones = $no_de_edificaciones-1;
				 } else {
				    pg_query("DELETE FROM info_edif WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");
				    $i = 0;
				    while ($i < $no_de_edificaciones-1) {
               $edi_tipo[$i] = utf8_decode($edi_tipo[$i]);
						   pg_query("INSERT INTO info_edif (cod_geo,cod_uv,cod_man,cod_pred,edi_num,edi_piso,edi_tipo,edi_edo,edi_ano,edi_cim,edi_est,edi_mur,edi_rvin,edi_rvex,
		                  edi_rvba,edi_rvco,edi_acab,edi_cest,edi_ctec,edi_ciel,edi_coc,edi_ban,edi_carp,edi_elec)
							 VALUES ('$cod_geo','$cod_uv','$cod_man','$cod_pred','$edi_num[$i]','$edi_piso[$i]','$edi_tipo[$i]','$edi_edo[$i]','$edi_ano[$i]','$edi_cim[$i]','$edi_est[$i]','$edi_mur[$i]','$edi_rvin[$i]','$edi_rvex[$i]',
		                  '$edi_rvba[$i]','$edi_rvco[$i]','$edi_acab[$i]','$edi_cest[$i]','$edi_ctec[$i]','$edi_ciel[$i]','$edi_coc[$i]','$edi_ban[$i]','$edi_carp[$i]','$edi_elec[$i]')");
						   $i++;
					  }
						$tabla_edif_rellenada = true;
						#$anadir_edif = false;
            ########################################
	          #-------------- REGISTRO --------------#
	          ########################################
		        $username = get_username($session_id);
						$accion_enc = utf8_encode ($accion);
						$valor = $cod_cat;
		        pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		                  VALUES ('$username','$ip','$fecha','$hora','$accion_enc','$valor')");	
						#include "c:/apache/siicat/siicat_ver_edif.php";	
						include "c:/apache/siicat/siicat_busqueda_resultado.php";					
				 }		
			}			
   #	 $edi_ano[$inicial] = "HOLA";
	 }
	 ########################################
	 #   BOTON MAS UNIDADES CONSTRUCTIVAS   #
	 ########################################		 
	 if($edif == "MÃ¡s Unidades Constructivas") {  
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
   if (!$tabla_edif_rellenada) {
      include "c:/apache/siicat/siicat_form_edif.php";
   }
} #END_OF_IF
else { # VOLVER
  # include "c:/apache/siicat/siicat_ver_edif.php";
   include "c:/apache/siicat/siicat_busqueda_resultado.php";	 
}

?>