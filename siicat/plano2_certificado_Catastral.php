<?php

if (isset($_POST['factor'])) {
   $factor = $_POST['factor'];
} else {
   ########################################
   #------ LEER FACTOR ZOOM DE TABLA -----#
   ########################################	
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
########################################
#------------- FORMULARIO -------------#
########################################	
echo "<td>\n";
echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"770px\">\n";   # 3 Columnas
   echo "<tr height=\"40px\">\n";
      echo "<td width=\"20%\">\n";
      echo "&nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&id=$session_id#tab-4\" alt='' title='Volver a la pantalla anterior'>\n";		
      echo "<img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";	
      echo "</td>\n";  
      echo "<td width=\"50%\" align=\"center\" class=\"bodyText\">\n";
      echo "</td>\n"; 
      echo "<td width=\"30%\"> &nbsp</td>\n";
   echo "</tr>\n";	
   echo "<tr>\n";
      echo "<td valign=\"top\" colspan=\"3\">\n";
         include "plano2_catastral_certificado_generar.php";
         echo "<iframe  frameborder=\"0\" 
                        name=\"mapserver\" 
                        src=\"http://$server/tmp/pc2$cod_cat.html\" 
                        id=\"content\" 
                        width=\"750px\" 
                        height=\"1270px\" 
                        align=\"left\" 
                        scrolling=\"no\"  
                        noresize=\"no\" 
                        marginwidth=\"0\" 
                        marginheight=\"0\">\n";
         echo "</iframe>\n";	
      echo "</td>\n";	 
   echo "</tr>\n";		
echo "</table>\n";
echo "</td>\n";

?>



