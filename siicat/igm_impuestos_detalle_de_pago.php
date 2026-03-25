<?php
 
echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"840px\" height=\"550px\">\n";
echo "<tr>\n";
echo "<td valign=\"top\" colspan=\"3\">\n";   #Col. 1
echo "MONTO IMP.(Bs.): $monto_imp &nbsp&nbsp&nbsp&nbsp&nbsp INTERES(Bs.): $interes_bs &nbsp&nbsp&nbsp&nbsp&nbsp MANTENIMIENTO VALOR(Bs.): $mant_val_bs &nbsp&nbsp&nbsp&nbsp&nbsp MULTAS(Bs.): $multas_total_bs &nbsp&nbsp&nbsp&nbsp&nbsp DEUDA TRIB.(Bs.): $deuda_bs\n";
echo "</td>\n";	 
echo "</tr>\n";	  
echo "<tr>\n";
echo "<td valign=\"top\" colspan=\"3\">\n";   #Col. 1 
$imprimir_preliq = true;
if ($calcular_urbano) {
	include "siicat_impuestos_generar_preliquidacion1.php";
	echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/preliq$cod_cat.html\" id=\"mapserver\" width=\"840px\" height=\"550px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
	echo "</iframe>\n";
} elseif ($calcular_rural) {	  
	include "siicat_rural_generar_preliquid1.php";
	echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/preliq$cod_cat.html\" id=\"mapserver\" width=\"840px\" height=\"550px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
	echo "</iframe>\n";									
} elseif ($calcular_patente) { 
	include "siicat_patentes_generar_preliquidacion1.php";
	echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/preliq$id_patente.html\" id=\"mapserver\" width=\"840px\" height=\"550px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
	echo "</iframe>\n";					
} elseif (($calcular_transfer_urbano) OR ($calcular_transfer_rural)) {
	
	include "igm_impuestos_generar_preliquid_transfer1.php";
	echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/preliq$cod_cat.html\" id=\"mapserver\" width=\"840px\" height=\"550px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
	echo "</iframe>\n";					
}	
echo "</td>\n";	 
echo "</tr>\n";	 		
echo "</table>\n";	 
?>