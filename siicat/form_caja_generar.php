<?php
if ($id_contrib > 0) {
   $nombre_form = get_contrib_nombre ($id_contrib);
} else $nombre_form = $nombre;

$monto_total_letra = numeros_a_letras ($monto_total);

$cajero = utf8_decode(get_username($session_id));

################################################################################
#----------------------------  GENERAR CODIGO QR   ----------------------------#
################################################################################	

include "C:/apache/htdocs/phpqrcode/qrlib.php";

$filen= "C:/apache/htdocs/tmp/test.png";
$tamanio = 12;
$level = "M";
$framSize = 3;
$contenido = $prop1." ".$tit_1ci." 12200";
QRcode::png($contenido, $filen, $level, $tamanio, $framSize);

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
$content = " 
<div align='left'>
<table border='1' width='100%' style='border:0px solid black; border-collapse:collapse; font-family: Arial; font-size: 8pt' bgcolor='#FFFFFF'><font face='Arial' size='2'>
   <tr>
      <td width='20%'>
         <table border='0' width='100%' style='font-family: Arial; font-size: 10pt' bgcolor='#FFFFFF'><font face='Arial' size='2'>
            <tr>
               <td width='20%' align='center'>
                  <img src='http://$server/$folder/css/$nomlog' alt='imagen' width='104' height='91' border='0'>
               </td>
            </tr>  							 
         </table>
      </td>	   

      <td align='center' valign='middle' width='60%'>
					<p>&nbsp</p>          <h3>GOBIERNO MUNICIPAL DE $municipio</h3>
          <h2>FORMULARIO UNICO DE CAJA</h2>
      </td>

      <td align='right' valign='top' width='20%'>
         $hora <a href='javascript:print(this.document)'>
         <img border='0' src='http://$server/$folder/graphics/printer.png' width='15' height='15'></a>	<br><br>
         <p align='center' style='font-family: Arial; font-size: 10pt'>FECHA: $fecha2 </p>
         <p align='center' style='font-family: Arial; font-size: 14pt'>$numero</p>
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
                  &nbspDetalle: $detalle  
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
               <td align='center' width='8%' style='font-family: Arial; font-size: 10pt'>Rubro</td> 
               <td align='center' width='55%' style='font-family: Arial; font-size: 10pt'>D e s c r i p c i o n</td>
               <td align='center' width='8%' style='font-family: Arial; font-size: 10pt'>Unidad</td>
               <td align='center' width='8%' style='font-family: Arial; font-size: 10pt'>Pre.Uni</td>
               <td align='center' width='5%' style='font-family: Arial; font-size: 10pt'>Cant.</td>	
               <td align='center' width='8%' style='font-family: Arial; font-size: 10pt'>Sup/Dist.</td>		
               <td align='center' width='8%' style='font-family: Arial; font-size: 10pt'>Total</td> 	 	 	 	    
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

               $content = $content."<br /><br />
               </td>
               <td align='right' valign='top' style='font-family: Arial; font-size: 10pt'>&nbsp <br /><br />";
               $i = 0;
               while ($i < $cant_de_items) {
                  $content = $content."
                  &nbsp $unidad_lista[$i] &nbsp <br />";			
                  $i++;
               }

               $content = $content."
                  </td>	 
                  <td align='right' valign='top' style='font-family: Arial; font-size: 10pt'>&nbsp <br /><br />";
               $i = 0;
               while ($i < $cant_de_items) {
                  $content = $content."
                  &nbsp $costo_lista[$i] &nbsp <br />";			
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
                  &nbsp $superf[$i] &nbsp <br />";			
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
	             <td align='left' colspan='6' style='font-family: Arial; font-size: 10pt'>&nbsp Son: $monto_total_letra 00/100 Bs.</td> 
	             <td align='right' style='font-family: Arial; font-size: 10pt'><b>$monto_total</b> &nbsp </td> 	  	 	 	    
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
                  <table border='1' width='100%' style='border-collapse:collapse;' bordercolor='#9b9b9b'>
                      <tr valign='top' height='60px'>
                        <td colspan='2' align='left' valign='top' style='font-family: Arial; font-size: 10pt'>Observaciones: $observacion</td> 
                     </tr 
                     <tr valign='top' height='10px'>
                        <td align='center' style='font-family: Arial; font-size: 10pt'>	
                           Nombre o Razon Social:
                        </td>                          
                        <td align='center' style='font-family: Arial; font-size: 10pt'>	
                           ADMINISTRACION
                        </td>     
                     </tr >
                     <tr valign='bottom'  height='90px'>
                        <td width='50%' align='center' style='font-family: Arial; font-size: 8pt'>	
                            $nombre_form
                        </td>
                        <td width='50%' align='center' style='font-family: Arial; font-size: 10pt'>
                            CAJERO
                        </td>                        
                     </tr>                     
                  </table>
               </td>

               <td width='60%' align='center' valign='top' style='font-family: Arial; font-size: 10pt'>
                   <table border='1' height='170px' width='100%' style='border-collapse:collapse;' bordercolor='#9b9b9b'>
                     <tr valign='top'>
                        <td align='center' valign='top' style='font-family: Arial; font-size: 10pt'>SELLO</td> 
                     </tr 
                  </table>	              
               </td>	
            </tr>
			</table>						 
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