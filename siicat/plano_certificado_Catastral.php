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
?>
<td>
<table border="0" align="center" cellpadding="0" width="770px">
   <tr height="40px">
      <td width=\"20%\">

      <?php 
      echo "&nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&id=$session_id#tab-5\" alt='' title='Volver a la pantalla anterior'>\n";		
      echo "<img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";
      ?>

      </td> 
      <td width="50%" align="center" class="bodyText">
      </td>
      <td width="30%"> &nbsp</td>
   </tr>
   <tr>
      <td valign="top" colspan="3">
      <?php 
      include "plano_catastral_certificado_generar.php";
      echo "<iframe frameborder=\"0\" name=\"mapserver\" src=\"http://$server/tmp/pc$cod_cat.html\" id=\"content\" width=\"750px\" height=\"1140px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
      echo "</iframe>\n";
      ?>	
      </td>
   </tr>		
</table>
</td>





