<?php

################################################################################
#----------------------- RUTA Y NOMBRE PARA GRABAR ----------------------------#
################################################################################	

$filename = "C:/apache/siicat/mapa/siicat_query_predios.htm";

################################################################################
#-------------------------------- PERMISOS ------------------------------------#
################################################################################	
if ($nivel == 1) {
   $ancho_columna = "100%";
} else $ancho_columna = "50%";

################################################################################
#------------------- PREPARAR CONTENIDO PARA GRABAR ---------------------------#
################################################################################	
$content = "<!-- MapServer Template -->
<div align='center'>
  <br>
	<table border='1' width='350' height='370' style='font-family: Tahoma; font-size: 10pt'><font face='Tahoma' size='2'>
     <tr>
        <td align='center' height='22' colspan='2' bordercolor='#CCCCCC' bgcolor='#E9E9E9'>
           <b>Resultado de consulta: Predio [cod_cat]</b></font>
        </td>
     </tr>
     <tr>
        <td bordercolor='#CCCCCC' bgcolor='#E9E9E9' colspan='2'>
           <img border='0' src='http://".$server."/".$folder."/fotos/[cod_cat].JPG' width='100%' height='250'>
        </td>
     </tr>  	 
     <tr>
        <td width='$ancho_columna' height='35' bordercolor='#CCCCCC' bgcolor='#CCCCCC'>
           <p align='center'><b>
			     <a href='http://".$server."/".$folder."/index.php?mod=35&cod_cat=[cod_cat]&id=".$session_id."&iframe'>
              <img border='0' src='http://".$server."/".$folder."/graphics/boton_reporte_predial.png' width='120' height='22'></a></b>
			     </a></b>
	      </td>";
if ($nivel > 1) {
   $content = $content." 
        <td width='50%' height='35' bordercolor='#CCCCCC' bgcolor='#CCCCCC'>
           <p align='center'><b>
			     <a href='http://".$server."/".$folder."/index.php?mod=30&cod_cat=[cod_cat]&id=".$session_id."&iframe'>
              <img border='0' src='http://".$server."/".$folder."/graphics/boton_plano_catastral.png' width='120' height='22'></a></b>
			     </a></b>
	      </td>";
}
$content = $content."						
     </tr>
     <tr>
        <td width='180' height='35' bordercolor='#CCCCCC' bgcolor='#CCCCCC' colspan='2'>
           <p align='center'><b>
           <input type='button' value='atrás' onClick='javascript:history.back();' style='font-family: Arial; color:#666666; font-size: 10pt; font-weight: bold'>
			     </b> 
	      </td>	
     </tr>		 
  </table>
</div>
";
################################################################################
#------------------- CHEQUEAR SI SE PUEDE ABRIR EL ARCHIVO --------------------#
################################################################################	
if (!$handle = fopen($filename, "w")) {
   $error = 2; 
}
if (!fwrite($handle, $content)) {
   $error = 3; 
}
fclose($handle);

?>