<?php

if ( isset($_GET['fn']) && isset($_GET['inmu']) && isset($_GET['id']) && isset($_GET['mod']) && $mod=36  ) {
	//echo "Generar Word ";
	$date = date("d-m-Y i:s");			
	header("Content-Type: application/vnd.msword");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("content-disposition: attachment;filename=".$date.".doc");
	
	$filename = "C:/apache/htdocs/tmp/us".$_GET['fn'].".html";
	$myFile = fopen($filename, "r") or die("Error al abrir el archivo");
	echo fread($myFile, filesize($filename));
	exit();
}

echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"770px\">\n";
echo "<tr height=\"40px\">\n";
	echo "<td width=\"20%\">\n";  
		echo "<a href=\"index.php?mod=5&inmu=$id_inmu&id=$session_id#tab-4\" alt='' title='Volver a la pantalla anterior'>\n";
		echo "<img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";	
	echo "</td>\n"; 

	echo "<td width=\"77%\" align=\"right\" class=\"bodyText\">\n";
		echo "<a href=\"index.php?mod=36&inmu=$id_inmu&id=$session_id&fn=$cod_cat\" alt='' title='Exportar documento' target=\"_blank\">\n";		
?>
		<button name="export" class="btn btn-primary"><span class="glyphicon glyphicon-export"></span> Exportar </button>

<?php

	echo "</a></td>\n"; 

	echo "<td width=\"3%\">\n"; 
	echo "</td>\n";
echo "</tr>\n";	
echo "</table>\n";

echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"770px\">\n";
echo "<tr>\n";
	echo "<td valign=\"top\">\n";
		include "igm_aprobacion_plano_generar.php";
		echo "<iframe frameborder=\"0\" name=\"mapserver\" src=\"http://$server/tmp/us$cod_cat.html\" id=\"content\" width=\"750px\" height=\"1270px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
		echo "</iframe>\n";	
	echo "</td>\n";	 
echo "</tr>\n";	 		
echo "</table>\n";
?>