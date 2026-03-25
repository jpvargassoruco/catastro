<?php

if (isset($_POST['factor'])) {
   $factor = $_POST['factor'];
} else {
	$sql="SELECT factor FROM plano_cat_zoom WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
	$check_zoom = pg_num_rows(pg_query($sql));		
	if ($check_zoom > 0) {
		$result=pg_query($sql);
		$info = pg_fetch_array($result, null, PGSQL_ASSOC);
		$factor = $info['factor'];
		pg_free_result($result);	
	} else {		 
		$factor = 1;
	}
}
###############################################
#------------- PLANO CATASTRAL 1 -------------#
###############################################	
 echo "<td>\n";
 echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"770px\">\n";   # 3 Columnas
	echo "<tr height=\"40px\">\n";
		echo "<td width=\"20%\">\n";  #Col. 1 
		echo "&nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&id=$session_id#tab-5\" alt='' title='Volver a la pantalla anterior'>\n";		
		echo "<img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";	
		echo "</td>\n";  
		echo "<td width=\"50%\" align=\"center\" class=\"bodyText\">\n";
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=301&inmu=$id_inmu&id=$session_id\">\n";
			echo "<fieldset><legend class=\"smallTextLeft\">Alejar/Acercar el mapa</legend>\n";
				echo "<table width=\"60%\" border=\"0\">\n";
					echo "<tr>\n";
					echo "<td width=\"35%\" align=\"left\">Factor Zoom:\n";	 
					echo "</td>\n";			 
					echo "<td width=\"35%\" align=\"left\" width=\"100%\" valign=\"top\">\n";  #Col 2
					echo "<select class=\"navText\" name=\"factor\" size=\"1\">\n";
					$zoom_array = array('0.5','0.5714285714','0.6666666666','0.7142857142','0.7692307692','0.8333333333','0.909090909','1','1.1111111111','1.25','1.4285714285','1.6666666666','2');
					$i = 0;
					while ($i < 13) {			
						$value = $zoom_array[$i];
							$value_text = ROUND (100/$value,0); 
						$value_text = $value_text." %";
						if ($factor == $value) {  
							echo "<option id=\"form0\" value=\"$value\" selected=\"selected\"> $value_text</option>\n";
						} else {
							echo "<option id=\"form0\" value=\"$value\"> $value_text</option>\n";	 
						}
						$i++; 
						}		  
					echo "</select>\n";
					echo "</td>\n";			 				
					echo "<td width=\"30%\" align=\"center\">\n";
					echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";	
					echo "<input name=\"Submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"OK\" alt='' title='Aplicar el factor zoom al plano catastral'>\n";
					echo "</td>\n";
					echo "</tr>\n";					 				
				echo "</table>\n";
			echo "</fieldset>\n";
		echo "</form>\n";
		echo "</td>\n"; 
		echo "<td width=\"30%\"> &nbsp</td>\n";   #Col. 3 			 
		echo "</tr>\n";	
		echo "<tr>\n";
			echo "<td valign=\"top\" colspan=\"3\">\n";   #Col. 1 	 
			include "plano_catastral_lote_generar.php";
			echo "<iframe frameborder=\"0\" name=\"mapserver\" src=\"http://$server/tmp/pc$cod_cat.html\" id=\"content\" width=\"750px\" height=\"1160px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
			echo "</iframe>\n";	
			echo "</td>\n";	 
		echo "</tr>\n";		
 	echo "</table>\n";
 echo "</td>\n";

?>