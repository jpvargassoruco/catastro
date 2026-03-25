<?php

if (isset($_POST["id_inmu"])) {   
	 $codigo_fijo = true;
}	elseif ((isset($_POST["cod_uv"])) AND (isset($_POST["cod_man"])) AND (isset($_POST["cod_pred"])) AND (isset($_POST["cod_blq"])) AND (isset($_POST["cod_piso"])) AND (isset($_POST["cod_apto"]))) {
	 $cod_uv = (int) $_POST["cod_uv"];
	 $cod_man = (int) $_POST["cod_man"];
	 $cod_pred = (int) $_POST["cod_pred"];
	 $cod_blq = $_POST["cod_blq"];
	 $cod_piso = $_POST["cod_piso"];
	 $cod_apto = $_POST["cod_apto"];	 	 
	 $cod_cat = get_codcat($cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto);
   $codigo_fijo = false;
} else $codigo_fijo = false;

if (isset($_POST["no_de_edificaciones"])) {		
   $no_de_edificaciones = $_POST["no_de_edificaciones"]+1;
} else $no_de_edificaciones = 1;

if (isset($_POST["accion"])) {		
   $accion = utf8_decode($_POST["accion"]);
} else $accion = "";

$error = $error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = false;
$error_codigo = false;
################################################################################
#----------------- AÑADIR EDIFICACIONES - VALORES INICIALES -------------------#
################################################################################	 
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "AÃ±adir Edificaciones")) {
	    $no_de_edificaciones = 1; 
	    $inicial = $no_de_edificaciones-1;
      $edi_num[$inicial] = 1;
      $edi_piso[$inicial] = 0;
      $edi_ubi = "";
      $edi_tipo[$inicial] = "CAS";
      $edi_edo[$inicial] = "REG";
      $edi_ano[$inicial] = $ano_actual;
      $edi_cim[$inicial] = $edi_est[$inicial] = $edi_mur[$inicial] = $edi_rvin[$inicial] = $edi_rvex[$inicial] =  "SIN";
      $edi_rvba[$inicial] = $edi_rvco[$inicial] = $edi_acab[$inicial] = $edi_cest[$inicial] = $edi_ctec[$inicial] = "SIN";
      $edi_ciel[$inicial] = $edi_coc[$inicial] = $edi_ban[$inicial] = $edi_carp[$inicial] = $edi_elec[$inicial] = "SIN";
}

################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	

$accion = "Añadir";

include "siicat_form_edif.php";
	
?>