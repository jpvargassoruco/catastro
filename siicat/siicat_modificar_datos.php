<?php

if (isset($_POST["submit"])) {
} else {
   $error = $error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = $error8 = false;	 
   include "siicat_info_predio_leer_datos.php";
   include "siicat_info_inmu_leer_datos.php";		
	 $tit_bene = "";
	 $tit_pers = "UNI";  
   ########################################
	 #------- SUPERFICIE SEGUN DOC ---------#
   ########################################	
	 if (($tipo_inmu == "CAS") OR ($tipo_inmu == "TER")) {
	    if (($ter_sdoc == 0) OR ($ter_sdoc == "")) {
			   $ter_sdoc = $adq_sdoc;
		  } elseif (($adq_sdoc == 0) OR ($adq_sdoc == "")) {
			   $adq_sdoc = $ter_sdoc;
		  }					
	 } 
   ########################################
   #--- CHEQUEAR COLINDANTES EN TABLA ----#
   ########################################	
	 $id_predio = get_id_predio ($cod_geo,$cod_uv,$cod_man,$cod_pred);
   $sql="SELECT * FROM colindantes WHERE id_predio = '$id_predio'";
   $check_col = pg_num_rows(pg_query($sql));
   if ($check_col > 0 ) {	
      $result_col = pg_query($sql);
      $info_col = pg_fetch_array($result_col, null, PGSQL_ASSOC);
			$col_norte_nom = utf8_decode ($info_col['norte_nom']);			
			$col_norte_med = utf8_decode ($info_col['norte_med']);
			$col_sur_nom = utf8_decode ($info_col['sur_nom']);			
			$col_sur_med = utf8_decode ($info_col['sur_med']);	
			$col_este_nom = utf8_decode ($info_col['este_nom']);			
			$col_este_med = utf8_decode ($info_col['este_med']);
			$col_oeste_nom = utf8_decode ($info_col['oeste_nom']);			
			$col_oeste_med = utf8_decode ($info_col['oeste_med']);				
			pg_free_result($result_col);
   } else {
	 ########################################
	 #---- COLINDANTES SEGUN GEOMETRIA -----#
   ########################################
	 $colind = "Modificar";
	 include "siicat_anadir_colindantes.php";
   } #END_OF_ELSE  --> if ($check_col > 0 )
	 $reg_checked = "";
} # END_OF_ELSE (isset($_POST["submit"]))
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	

$accion = "Modificar";

include "siicat_form_predio.php";
	
?>