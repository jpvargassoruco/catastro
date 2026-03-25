<?php
$con_pmc = $info['con_pmc'];
$pmc_ant = $info['pmc_ant'];
$con_act = $info['con_act'];
$con_fech_ini = change_date($info['con_fech_ini']);
$con_fech_fin = $info['con_fech_fin'];	 
if ($con_fech_fin == "1900/01/01") {
	$con_fech_fin = "";
} else $con_fech_fin = change_date($con_fech_fin);
	$con_tipo = trim($info['con_tipo']);		
	$con_tipo_temp = abr ($con_tipo); 
	$con_raz = $info['con_raz'];
	$con_pat = $info['con_pat'];
	$con_mat = $info['con_mat'];
	$con_nom1 = $info['con_nom1'];
	$con_nom2 = $info['con_nom2'];	 
	$con_nit = $info['con_nit'];
	 if ($con_nit == "-1") {
	    $con_nit = "";
   }
	$con_tel = $info['con_tel'];			 
	$doc_tipo = $info['doc_tipo'];	 
	$doc_num = $info['doc_num'];
	$doc_exp = $info['doc_exp'];
	if ($doc_num == "") {
		$documentacion = "";
	} else $documentacion = $doc_tipo." ".$doc_num." ".$doc_exp;  
		$con_fech_nac = $info['con_fecnac'];	 
	
	if ($con_fech_nac == "1900-01-01") {
		$con_fech_nac = "";
	} else $con_fech_nac = change_date($con_fech_nac);	  

   $dom_dpto = $info['dom_dpto'];
   $dom_ciu = $info['dom_ciu'];
   $dom_bar = $info['dom_bar'];
   $dom_tipo = $info['dom_tipo'];
   $dom_nom = $info['dom_nom'];	
   $dom_num = $info['dom_num'];			 
   $dom_edif = $info['dom_edif'];	 
   $dom_bloq = $info['dom_bloq'];
   $dom_piso = $info['dom_piso'];	  
   $dom_apto = $info['dom_apto'];
	 $direccion = $dom_dpto;
	 if ($dom_ciu != "") {
	    $direccion = $direccion.",".$dom_ciu;
	 }
	 if ($dom_bar != "") {
	    $direccion = $direccion.",B/".$dom_bar;
	 }	
	 if ($dom_nom != "") {
		  if (($dom_tipo == "AV") OR ($dom_tipo == "PZ")) {    
	       $direccion = $direccion.",".$dom_tipo.".".$dom_nom;
		  } else  {    
	       $direccion = $direccion.",".$dom_tipo."/".$dom_nom;	
		  }	 			 
	 } 
	 if ($dom_num != "") {
	    $direccion = $direccion." #".$dom_num;
	 }		
	 if ($dom_edif != "") {
	    $direccion = $direccion.",EDF. ".$dom_edif;
	 }		
	 if ($dom_bloq != "") {
	    $direccion = $direccion.", BLQ. ".$dom_bloq;
	 }	
	 if ($dom_piso != "") {
	    $direccion = $direccion.", PISO ".$dom_piso;
	 }	
	 if ($dom_apto != "") {
	    $direccion = $direccion.", APTO. ".$dom_apto;
	 }				 		 	  
   $med_agu = $info['med_agu'];	  
   $med_luz = $info['med_luz'];	 	  
   $con_obs = $info['con_obs'];		
	
?>