<?php 
if ( isset($_GET['fn']) && isset($_GET['inmu']) && isset($_GET['id']) && isset($_GET['mod']) && $mod=36  ) {
	include "c:/apache/siicat/igm_aprobacion_plano.php";
	exit();
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
	 include "c:/apache/siicat/config/paria.php"; 
	 include "c:/apache/siicat/siicat_formulas.php";
	 include "c:/apache/siicat/function.php";
	 include "c:/apache/siicat/siicat_import_excel.php"; 
	 include "c:/apache/siicat/siicat_check_backup.php";
	 include "c:/apache/siicat/siicat_version.php";
	 	 	  
   $restore = false;  
	 
	 if (isset($_POST['cod_cat'])) {
	    $cod_cat = $_POST['cod_cat'];
	    $cod_uv = get_uv ($cod_cat); $cod_man = get_man($cod_cat);  $cod_pred = get_pred ($cod_cat);		
	 } else $cod_cat = "0";
	 
	 if ((isset($_POST['id_inmu'])) OR (isset($_GET['inmu']))) {
	    if (isset($_POST['id_inmu'])) {
			   $id_inmu = $_POST['id_inmu'];
		  } else $id_inmu = $_GET['inmu'];
	    $cod_uv = get_cod_uv_from_id_inmu ($id_inmu); $cod_man = get_cod_man_from_id_inmu($id_inmu);  $cod_pred = get_cod_pred_from_id_inmu ($id_inmu);
	    $cod_blq = get_cod_blq_from_id_inmu ($id_inmu); $cod_piso = get_cod_piso_from_id_inmu($id_inmu);  $cod_apto = get_cod_apto_from_id_inmu ($id_inmu);					
	    $cod_cat = get_codcat ($cod_uv,$cod_man,$cod_pred,0,0,0);
	 } else $id_inmu = "0";
	 
   if (isset($_POST["search_string"])) {
      $search_string = $_POST["search_string"];
   } else $search_string = ""; 
	 	 
   if (isset($_GET["ref"])) {   
	    $ref = $_GET["ref"];
   } else $ref = "0";	
	  
	 if (isset($_GET['iframe'])) {
	    $iframe = true;
	 } else $iframe = false;  
	 if ((isset($_POST["Submit"])) AND (($_POST["Submit"]) == "Lista")) {
	    include "c:/apache/siicat/siicat_lista.php";
			### GENERAR NUEVAMENTE COD_UV, COD_MAN, COD_PRED CON EL RESULTADO DE LISTA
	    $cod_uv = get_uv ($cod_cat); $cod_man = get_man($cod_cat);  $cod_pred = get_pred ($cod_cat);			
	 } 
	 if (isset($_GET['boleta'])) {
	    $gestion = $_GET['boleta'];	
      $boleta = true;
	 } else $boleta = false;
	 
	 if ((isset($_GET['mod'])) AND (isset($_GET['id'])) AND (check_session($_GET["id"]))) {
	    $session_id = $_GET['id'];
	 } elseif ((isset($_GET['mod'])) AND (isset($_POST["user_id"])) AND (isset($_POST["password"])) AND (check_usuario($_POST["user_id"],$_POST["password"]) > 0)) { 
      $session_id = create_session($_POST["user_id"],$ip);
      $nivel = check_usuario($_POST["user_id"],$_POST["password"]);		
   } elseif ((!isset($_GET['mod'])) AND (isset($_GET['id'])) AND (check_session($_GET["id"]))) {
	 	  $user_id = get_userid ($_GET['id']);			
			pg_query("DELETE FROM temp_poly WHERE user_id = '$user_id' OR user_id is NULL");
			pg_query("DELETE FROM temp_point WHERE user_id = '$user_id' OR user_id is NULL");
			pg_query("DELETE FROM temp_line WHERE user_id = '$user_id' OR user_id is NULL");		
	    $session_id = destroy_session($_GET["id"]);
	 } else {
	    $session_id = 0;
			#TEMPORAL
	    if (isset($_GET['mod'])) {
	       $mod = $_GET['mod'];
	    }			
	 }
#echo "Session_ID: $session_id";

	 if (check_session($session_id)) { 
			if ((isset($_POST["user_id"])) AND (isset($_POST["password"]))) {
				 $nivel = check_usuario($_POST["user_id"],$_POST["password"]);
			}	else {
			   $nivel = check_session($session_id);
				 $user_id = get_userid ($session_id); 
			}	 
	 }
	 if (isset($_GET['logout'])) { 
	    $cmd = "c:\\apache\\cgi-bin\\del-ms_tmp.bat"; 		 
	    #system($cmd);
	    exec($cmd);
	 }
################################################################################
#-------------------------------- FORMULARIO ----------------------------------#
################################################################################		 
include "header.php";
echo "<body>\n";
if (!check_session($session_id)) {
	# SESION NO INICIADA 
	include_once 'index2.html';		
} else {
	$i = 0;
	while ($i<150) {
		$sel[$i] = "";
		$flecha[$i] = "";
		$i++;
	}
	if (isset($_GET['mod'])) {
	    $mod = $_GET['mod'];
			if (($mod == 4) OR ($mod == 5) OR ($mod == 6) OR ($mod == 7) OR ($mod == 15) OR ($mod == 20) OR ($mod == 30) OR ($mod == 61) OR ($mod == 62) OR ($mod == 67)) {
			   $mod = 1;
			}
			if ($mod == 103) {
			   $mod = 101;
			}					
			if ($mod == 113) {
			   $mod = 111;
			}		
			if ($mod == 123) {
			   $mod = 121;
			}					
      $sel[$mod] = pg_escape_string('id="MenuIdToExpand" class="dhtmlgoodies_activeItem"');
			$flecha1 = pg_escape_string('<img src="http://');
			$flecha2 = pg_escape_string('/css/flecha.png">&nbsp');
			$flecha[$mod] = $flecha1.$server."/".$folder.$flecha2;
	 } else {
	    $mod = 0;
	 }

	###FORMULARIO 
	echo "<div id=\"mainContainer\">\n";
		echo "<div id=\"topBar\"><img src=\"http://$server/$folder/css/bannerigm.jpg\" width=\"190\" height=\"49\"></div>\n";
		include "titulo.php";	
		echo "<div id=\"leftMenu\">\n";  
		#	<!-- START OF MENU -->
		include_once "menu.php";
		#<--END OF MENU -->
		echo "<script type=\"text/javascript\">\n";
		echo "initSlideDownMenu();\n";
		echo "window.setTimeout(10);\n";	 
		echo "</script>\n";
		echo "</div>\n";
		echo "<div id=\"mainContent\">\n"; 
			include "c:/apache/siicat/igm_include.php";
			echo "<div class=\"clear\"></div>\n"; 
		echo "</div>\n";
		echo "</DIV>\n";	 
 } #END_OF_ELSE --> if (!check_session($session_id))
 echo "</body>\n";
 echo "</html>\n"; 
 
?>
