<?php

if ($id_contrib > 0) {
   $nombre_form = get_contrib_nombre ($id_contrib);
} else $nombre_form = $nombre;
$monto_total_letra = numeros_a_letras ($monto_total);
$cajero = utf8_decode(get_username($session_id));
################################################################################
#------------------------------------ FECHA -----------------------------------#
################################################################################	
$nombre_mes = monthconvert ($mes_actual);

################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	

$filename = "C:/apache/htdocs/tmp/fc".$numero.".html";

################################################################################
#------------------------ PREPARAR CONTENIDO PARA IFRAME ----------------------#
################################################################################	
#<table border='1' width='100%' height='161' style='font-family: Arial; font-size: 8pt' bgcolor='#FFFFFF'><font face='Arial' size='2'>
$content = " 
<div align='left'>
<table border='1' width='100%' style='border:0px solid black; border-collapse:collapse; font-family: Arial; font-size: 8pt' bgcolor='#FFFFFF'><font face='Arial' size='2'>
   <tr>
      <td width='35%'>
			   <table border='0' width='100%' style='font-family: Arial; font-size: 10pt' bgcolor='#FFFFFF'><font face='Arial' size='2'>
            <tr>
               <td width='20%'>
				          <img src='http://$server/$folder/css/banner_blanco.png' alt='imagen' width='104' height='91' border='0'>
               </td>
               <td width='80%' align='center'>
                  <p>GOBIERNO MUNICIPAL</p>
									<p> DE $municipio</p> 
               </td>
            </tr>  							 
         </table>
			</td>	   							 			 
      <td align='center' valign='middle' width='45%'>
					<p>&nbsp</p> 
          <h2>FORMULARIO UNICO DE CAJA</h2>
      </td>
      <td align='right' valign='top' width='20%'>
          <br />$fecha2 - $hora <a href='javascript:print(this.document)'>
          <img border='0' src='http://$server/$folder/graphics/printer.png' width='15' height='15'></a>			 
          <br /><br /><p align='center' style='font-family: Arial; font-size: 10pt'>Numero: $numero</p>
      </td>			
   </tr> 	 	 	  
	 <tr>
      <td align='center' valign='top' colspan='3' bgcolor='#FFFFFF'>
			   <table border='0' width='100%'>
						<tr>
               <td style='font-family: Arial; font-size: 1pt'> &nbsp </td>				 
            </tr>
         </table>				  
			   <table border='1' width='100%' style='border-collapse:collapse;'>
						<tr height='30px'>
               <td style='font-family: Arial; font-size: 10pt'>
                  &nbsp&nbsp Nombre o Razon Social: $nombre_form 
               </td>				 
            </tr>
         </table>	
			   <table border='0' width='100%'>
						<tr>
               <td style='font-family: Arial; font-size: 1pt'> &nbsp </td>				 
            </tr>
         </table>
			   <table border='1' width='100%' style='border-collapse:collapse;'>
						<tr height='30px'>
               <td style='font-family: Arial; font-size: 10pt'>
                  &nbsp&nbsp Detalle: $detalle  
               </td>				 
            </tr>
         </table>		
			   <table border='0' width='100%'>
						<tr>
               <td style='font-family: Arial; font-size: 1pt'> &nbsp </td>				 
            </tr>
         </table>				 
	       <table border='1' width='100%' style='border-collapse:collapse;'>  
	          <tr height='30px'>  	                     	  	 
               <td align='center' width='10%' style='font-family: Arial; font-size: 10pt'>Rubro</td> 
	             <td align='center' width='56%' style='font-family: Arial; font-size: 10pt'>D e s c r i p c i ó n</td>
	             <td align='center' width='12%' style='font-family: Arial; font-size: 10pt'>Monto</td>
	             <td align='center' width='10%' style='font-family: Arial; font-size: 10pt'>Cant.</td>
	             <td align='center' width='12%' style='font-family: Arial; font-size: 10pt'>Total</td>		 	 	 	 	    
	          </tr>
            <tr>  	                       	 
               <td align='right' valign='top' style='font-family: Arial; font-size: 10pt'><br /> $rubro &nbsp </td>
	             <td align='left' valign='top' style='font-family: Arial; font-size: 10pt'><br />&nbsp $nombre_rubro<br />";
$i = 0;
while ($i < $cant_de_items) {
   $content = $content."
	               &nbsp&nbsp&nbsp&nbsp&nbsp $descrip_lista[$i] <br />";			
	 $i++;
}
$content = $content." <br /><br />
		           </td>
	             <td align='right' valign='top' style='font-family: Arial; font-size: 10pt'>&nbsp <br /><br />";
$i = 0;
while ($i < $cant_de_items) {
   $content = $content."
	               &nbsp $monto_lista[$i] &nbsp <br />";			
	 $i++;
}
$content = $content."
	             </td>	 
	             <td align='right' valign='top' style='font-family: Arial; font-size: 10pt'>&nbsp <br /><br />";
$i = 0;
while ($i < $cant_de_items) {
   $content = $content."
	               &nbsp $cant_lista[$i] &nbsp <br />";			
	 $i++;
}
$content = $content."			
               </td>		
	             <td align='right' valign='top' style='font-family: Arial; font-size: 10pt'>&nbsp <br /><br />";		 	 
$i = 0;
while ($i < $cant_de_items) {
   $content = $content."
	               &nbsp $monto_lista_total[$i] &nbsp <br />";			
   $i++;
}
$content = $content."
			         </td> 	 	    
	          </tr>
	          <tr height='30px'>  	                       	 
	             <td align='left' colspan='4' style='font-family: Arial; font-size: 10pt'>&nbsp Son: $monto_total_letra 00/100 Bs.</td> 
	             <td align='right' style='font-family: Arial; font-size: 10pt'>$monto_total &nbsp </td> 	  	 	 	    
	          </tr>					
	       </table>		
			   <table border='0' width='100%'>
						<tr>
               <td style='font-family: Arial; font-size: 1pt'> &nbsp </td>				 
            </tr>
         </table>										 			 				 							
			   <table border='0' width='100%' height='175px' style='border:0px solid black; border-collapse:collapse; font-family: Arial; font-size: 8pt' bgcolor='#FFFFFF'>				 		    
						<tr>
               <td width='60%' align='center' valign='top' style='font-family: Arial; font-size: 10pt'>
							    <fieldset><legend>Observaciones</legend>
                     <br /><br /><br /> 
									</fieldset> 
               </td>
               <td width='40%' rowspan='2' align='center' valign='top' style='font-family: Arial; font-size: 10pt'>
							    <fieldset>
                     <br /><br /><br /><p valign='middle'>SELLO</p><br /><br /><br />
									</fieldset>									
               </td>							 				 
            </tr>
	          <tr valign='top' height='100%'>
               <td align='left' style='font-family: Arial; font-size: 10pt'>	
							    <fieldset>
                     <p valign='middle'>CAJERO: $cajero</p>
									</fieldset>
				       </td>
				    </tr> 															 					
         </table>		
			</td>						 
   </tr>								  	 
</table>
</div>";
################################################################################
#------------------- CHEQUEAR SI SE PUEDE ABRIR EL ARCHIVO --------------------#
################################################################################	
if (!$handle = fopen($filename, 'w')) {
   $error = 2; 
}
if (!fwrite($handle, $content)) {
   $error = 3; 
}
fclose($handle);

?>