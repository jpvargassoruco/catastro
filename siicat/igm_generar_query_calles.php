<?php 

################################################################################
#----------------------- RUTA Y NOMBRE PARA GRABAR ----------------------------#
################################################################################	

$filename = "C:/apache/catastro/mapa/igm_query_calles.htm";
 
################################################################################
#------------------- PREPARAR CONTENIDO PARA GRABAR ---------------------------#
################################################################################	
$content = "<!-- MapServer Template -->
<div align='center'><br /><br /><br />
   <form method='post' action='http://$server/$folder/index.php?mod=12&id=$session_id&iframe' accept-charset='utf-8'>  
   <table border='1' width='300px' height='200px' style='font-family: Tahoma; font-size: 10pt'><font face='Tahoma' size='2'>
      <tr height='20'>
         <td width='300px' colspan='3' bordercolor='#CCCCCC' bgcolor='#CCCCCC'>
            <p align='center'><b>Elemento seleccionado</b></p>
         </td>
      </tr>
		  <tr height='15'>	
         <td bordercolor='#CCCCCC' bgcolor='#E9E9E9'>
             &nbsp&nbsp&nbsp;Objeto
         </td>
         <td colspan='2' bordercolor='#CCCCCC' bgcolor='#E9E9E9'>
				 			 
            &nbsp;Calle
         </td>
     </tr>  
		 <tr height='15'>
        <td bordercolor='#CCCCCC' bgcolor='#E9E9E9'>
           &nbsp&nbsp&nbsp;ID 
        </td>
        <td colspan='2' bordercolor='#CCCCCC' bgcolor='#E9E9E9'>
            &nbsp;[observ]
        </td>
     </tr>
		 <tr height='15'>
        <td bordercolor='#CCCCCC' bgcolor='#E9E9E9'>
           &nbsp&nbsp&nbsp;Tipo 
        </td>
        <td align='center' colspan='2' bordercolor='#CCCCCC' bgcolor='#E9E9E9'>	
           <select name='tipo_calle' size='1'>
              <option id='form0' value='Avenida'> Avenida</option>
              <option id='form0' value='Calle' selected='selected'> Calle</option>
              <option id='form0' value='Pasaje'> Pasaje</option>
              <option id='form0' value='No Clasificado'> No Clasificado</option>							
           </select>						
        </td>
     </tr> 			  
		 <tr height='15'>
        <td bordercolor='#CCCCCC' bgcolor='#E9E9E9'>
           &nbsp&nbsp&nbsp;Nombre 
        </td>
        <td colspan='2' bordercolor='#CCCCCC' bgcolor='#E9E9E9'>
            &nbsp;<input type='text' name='new_description' id='form4' value='[descrip]'>
        </td>
     </tr> 	 	 
     <tr height='30'>
        <td align='center' valign='center' bordercolor='#CCCCCC' bgcolor='#E9E9E9'> 
	        <input type='submit' name='submit' value='Borrar objeto' style='font-family: Tahoma; font-size: 10pt; font-weight: bold'>			 
				</td> 
        <td colspan='2' align='center' valign='center' bordercolor='#CCCCCC' bgcolor='#E9E9E9'>
               
				<input type='hidden' name='object' value='Calle'>					
				<input type='hidden' name='object_id' value='[observ]'>		
				<input type='hidden' name='imgext' value='$xmin $ymin $xmax $ymax'>									
				<input type='hidden' name='session_id' value='$session_id'>							 
        <input type='hidden' name='ip' value='$ip'>
				<input type='hidden' name='logout_button' value='1'> 			
        <input type='submit' name='submit' value='Modificar objeto' style='font-family: Tahoma; font-size: 10pt; font-weight: bold'>
    			
     </td></tr>
     <tr height='30'>
        <td colspan='3' align='center' valign='center' bordercolor='#CCCCCC' bgcolor='#CCCCCC'> 		 
           <input type='button' value='Volver al plano' onClick='javascript:history.back();' style='font-family: Tahoma; font-size: 10pt; font-weight: bold'>
				</td> 
     </tr>				
 </table>    
 </form>	
</div>";
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