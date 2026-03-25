<?php
 $error = false;
################################################################################
#--------------------------- LEER CODIGO POSTED -------------------------------#
################################################################################		
#if ($form_codigo == 1) {
   $cod_uv = $cod_uv_form = trim($_POST["cod_uv"]); 
   $cod_man = $cod_man_form = trim($_POST["cod_man"]);
   $cod_pred = $cod_pred_form = trim($_POST["cod_pred"]);
   $cod_blq = $cod_blq_form = trim($_POST["cod_blq"]);	
   $cod_piso = $cod_piso_form = trim($_POST["cod_piso"]);
   $cod_apto = $cod_apto_form = trim($_POST["cod_apto"]);

################################################################################
#----------------------------- CHEQUEAR CODIGO --------------------------------#
################################################################################	
if ((isset($_POST["busqueda_alfa"])) AND ($_POST["busqueda_alfa"] == "REAL")) {
   if ($cod_man != "") {
      $cod_man = get_codigo_num ($cod_man);
	 }
	 if ($cod_pred != "") {
	    $cod_pred = get_codigo_num ($cod_pred);
	 }
}
#echo "CHECK L50 COD_MAN: $cod_man, COD_PRED: $cod_pred <br />\n";	
if ((!check_int($cod_uv)) OR (!check_int($cod_man)) OR (!check_int($cod_pred))) {
   $error = true;
   $mensaje_de_error = "La busqueda en la base de datos no tenia resultado."; 
	 $resultado = false;	 
} elseif (($cod_uv > 0) AND ($cod_man > 0) AND ($cod_pred > 0)  AND ($cod_blq == "") AND ($cod_piso == "") AND ($cod_apto == "")) {
   $cod_blq = $cod_piso = $cod_apto = 0;
}
################################################################################
#--------------------------- CHEQUEAR SI EXISTE -------------------------------#
################################################################################	
if (!$error) {
      $sql="SELECT id_inmu FROM info_inmu WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND cod_blq = '$cod_blq' AND cod_piso = '$cod_piso' AND cod_apto = '$cod_apto'";
      $check = pg_num_rows(pg_query($sql)); 
      if ($check == 1) {
         $exist = true;	
		     $mod = 5;		
		     $id_inmu = get_id_inmu ($cod_geo, $cod_uv, $cod_man, $cod_pred, $cod_blq, $cod_piso, $cod_apto);
				 $cod_cat = get_codcat ($cod_uv, $cod_man, $cod_pred, $cod_blq, $cod_piso, $cod_apto);	 
	    } else	{
         $exist = false;
		     $mod = 1;
      }
} else {
   $exist = false;
	 $mod = 1;
}

 if ($exist) {
   include "c:/apache/siicat/siicat_busqueda_resultado.php";
} else {
   include "c:/apache/siicat/busqueda.php";				 
}

?>