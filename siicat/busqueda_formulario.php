################################################################################
#---------------------- FORMULARIO DE BUSQUEDA PREDIOS  -----------------------#
################################################################################		
<td>
<table border="0" align="center" cellpadding="0" width="800px" height="100%">
<tr height="40px">
<td width="10%"> &nbsp</td> 	    
<td align="center" valign="center" height="40" width="70%" class="pageName"> 

<?php      
	if ($mod == 1) {
	    Buscar Predios 
	 } elseif ($mod == 41) {
	    Buscar Propiedad Rural 				
	 } elseif ($mod == 101) {
	    Buscar Patente 	 
	 } elseif ($mod == 111) {
	    Buscar Vehículo 	 
	 } elseif ($mod == 121) {
	    Buscar Contribuyente 	 
	 }   
?> 

</td>
<td width="20%"> &nbsp</td>   #Col. 3 			 
</tr>	 
<tr>
<td> &nbsp</td>   #Col. 1  
<td align="left"> &nbsp  #Col. 2 
</td>	
<td> &nbsp</td>   #Col. 3	  	 
</tr>	 
# Fila 2
<tr>  
<td height="40"> &nbsp</td>   #Col. 1                      
<td valign="top" class="bodyText">  #Col. 2	  
<fieldset><legend>Ingrese el atributo que quiere buscar</legend>

<?php  if ($mod == 41) { ?>
<form name="isc" method="post" action="index.php?mod=43&id=$session_id" accept-charset="utf-8"> 
    <table border="0" width="100%" cellspacing="0" cellpadding="0">  # TABLE 6 Columns
        <tr>
        <td> &nbsp</td> #TCol. 1
        <td align="left" colspan="2" class="bodyTextD">Pol�gono</td>						
        <td align="left" colspan="3" class="bodyTextD">Parcela</td>             
        </tr>  	 
        <tr>
        <td width="10%"> &nbsp</td> #TCol. 1
        <td align="left" width="10%" class="bodyTextD"> #TCol. 2
        <input name="cod_pol" type="text" class="navText" maxlength="$max_strlen_pol" value="$cod_pol">
        </td>
        <td width="2%"> &nbsp</td> #TCol. 3			
        <td align="left" width="10%" class="bodyTextD"> #TCol. 4
        <input name="cod_par" type="text" class="navText" maxlength="$max_strlen_par" value="$cod_par">
        </td>		
        <td width="2%"> &nbsp</td> #TCol. 5					
        <td width="66%">  #TCol. 6				
        <input name="busqueda1" type="submit" class="smallText" value="Buscar" onClick="go()">			
        </td>		
        </tr>  
    </table>		
</form>	 
<?php   } elseif ($mod == 101) { ?>
<form name="isc" method="post" action="index.php?mod=103&id=$session_id" accept-charset="utf-8"> 
    <table border="0" width="100%" cellspacing="0" cellpadding="0">  # TABLE 6 Columns
        <tr>
        <td> &nbsp</td> #TCol. 1
        <td align="left" colspan="2" class="bodyTextD">Número de Patente
        </td>		
        </tr>  	 
        <tr>
        <td width="10%"> &nbsp</td> #TCol. 1
        <td align="left" width="30%" class="bodyTextD">
        <input name="act_pat" type="text" class="navText" value="$act_pat">
        </td>
        <td width="60%">
        <input name="busqueda" type="submit" class="smallText" value="Buscar" onClick="go()">			
        </td> #TCol. 1			
        </tr>  
    </table> #TCol. 1			
</form>	 
<?php	 } elseif ($mod == 111) { ?>
<form name="isc" method="post" action="index.php?mod=113&id=$session_id" accept-charset="utf-8"> 
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
        <td> &nbsp</td> 
        <td align="left" colspan="2" class="bodyTextD">Número de Placa
        </td>		
        </tr>  	 
        <tr>
        <td width="10%"> &nbsp</td>
        <td align="left" width="30%" class="bodyTextD">
        <input name="veh_plc" type="text" class="navText" value="$veh_plc">
        </td>
        <td width="60%">
        <input name="busqueda" type="submit" class="smallText" value="Buscar" onClick="go()">			
        </td>		
        </tr>  
    </table>		
</form>
<?php } elseif ($mod == 121) { ?>
    <form name="isc" method="post" action="index.php?mod=123&id=$session_id" accept-charset="utf-8"> 
        <table border="0" width="100%" cellspacing="0" cellpadding="0">  # TABLE 6 Columns
            <tr>
            <td> &nbsp</td> #TCol. 1
            <td align="left" colspan="2" class="bodyTextD">Padrón Municipal (PMC)
            </td>		
            </tr>  	 
            <tr>
            <td width="10%"> &nbsp</td> #TCol. 1
            <td align="left" width="25%" class="bodyTextD">
            <input name="con_pmc" type="text" class="navText" maxlength="$max_strlen_pmc" value="$con_pmc">
            </td>
            <td width="65%">
            <input name="busqueda" type="submit" class="smallText" value="Buscar" onClick="go()">			
            </td> #TCol. 1			
            </tr>  
        </table> #TCol. 1			
    </form>					 
<?php } else { ?>
<form name="isc" method="post" action="index.php?mod=4&id=$session_id" accept-charset="utf-8"> 
    <table border="0" width="100%" cellspacing="0" cellpadding="0">  # TABLE 8 Columns
        <tr>
        <td> &nbsp</td> #TCol. 1
        <td align="left" colspan="2" class="bodyTextD">$uv_dist</td>
        <td align="left" colspan="2" class="bodyTextD">Mz.</td>
        <td align="left" colspan="2" class="bodyTextD">Pred.</td>
        <td align="left" colspan="2" class="bodyTextD">Blq.</td>
        <td align="left" colspan="2" class="bodyTextD">Piso</td>
        <td align="left" colspan="2" class="bodyTextD">Apto.</td>											
        <td align="left" colspan="1" class="bodyTextD"> &nbsp</td>            
        </tr>  	 
        <tr>
        <td width="10%"> &nbsp</td> #TCol. 1
        <td align="left" width="6%" class="bodyTextD">
        <input name="cod_uv" type="text" class="navText" maxlength="$max_strlen_uv" value="$cod_uv">
        </td>
        <td width="2%"> &nbsp</td> #TCol. 3		
        <td align="left" width="6%" class="bodyTextD">
        <input name="cod_man" type="text" class="navText" maxlength="$max_strlen_man" value="$cod_man">
        </td>	
        <td width="2%"> &nbsp</td> #TCol. 5						
        <td align="left" width="6%" class="bodyTextD">
        <input name="cod_pred" type="text" class="navText" maxlength="$max_strlen_pred" value="$cod_pred">
        </td>
        <td width="2%"> &nbsp</td> #TCol. 7			
        <td align="left" width="6%" class="bodyTextD">
        <input name="cod_blq" type="text" class="navText" maxlength="$max_strlen_blq" value="$cod_blq">
        </td>	
        <td width="2%"> &nbsp</td> #TCol. 7			
        <td align="left" width="6%" class="bodyTextD">
        <input name="cod_piso" type="text" class="navText" maxlength="$max_strlen_piso" value="$cod_piso">
        </td>	
        <td width="2%"> &nbsp</td> #TCol. 7			
        <td align="left" width="6%" class="bodyTextD">
        <input name="cod_apto" type="text" class="navText" maxlength="$max_strlen_apto" value="$cod_apto">
        </td>					
        <td width="2%"> &nbsp</td> #TCol. 7					
        <td width="40%">
        <input name="old_example" type="hidden" class="smallText" value="$example">
        <input name="old_stage2" type="hidden" class="smallText" value="$stage2">	 				
        <input name="busqueda1" type="submit" class="smallText" value="Buscar" onClick="go()">			
        </td> #TCol. 1			
        </tr>  
    </table> #TCol. 1			
</form>		 
<?php	}	 ?>
<form name="isc" method="post" action="index.php?mod=$mod&id=$session_id" accept-charset="utf-8">
    <table border="0" width="100%" cellspacing="0" cellpadding="0">  # TABLE 6 Columns	 
        <tr>
        <td width="10%"> &nbsp</td> #TCol. 1
        if ($mod == 1) {	 
        <td align="left" width="60%" class="bodyTextD">Nombre, Apellido, No. de Carnet, PMC y/o Dirección
        } elseif ($mod == 41) {
        <td align="left" width="60%" class="bodyTextD">Nombre de la Propiedad o del Propietario	
        } elseif ($mod == 101) {
        <td align="left" width="60%" class="bodyTextD">Razón Social, Propietario o NIT
        } elseif ($mod == 111) {
        <td align="left" width="60%" class="bodyTextD">Nombre de Propietario o No. de Carnet 
        } elseif ($mod == 121) {
        <td align="left" width="60%" class="bodyTextD">Nombre del Contribuyente o No. de Carnet 
        }
        <input name="search_string" type="text" class="navText" value="$search_string">
        </td>
        <td width="30%" valign="bottom"> 	 	 	 
        <input name="busqueda2" type="submit" class="smallText" value="Buscar" onClick="go()">	
        </td>
        </tr>  	 
    </table> 
</form>
<form id="form1" name="form1" method="post" action="index.php?mod=$mod&id=$session_id" accept-charset="utf-8">
    <table border="0" width="100%" cellspacing="0" cellpadding="0">  # TABLE 3 Columns	 	 
        <tr height="35">
        <td>&nbsp</td>			
        <td align="center">
        <input name="busqueda3" type="submit" class="smallText" id="Submit" value="Listado Completo" />	
        </td>
        <td>&nbsp</td>								
        </tr>				
    </table> 
</form>			 	 	 
</fieldset>
</td>
<td height="40"> &nbsp</td>   #Col. 3   		
</tr>	
<tr>
<td> &nbsp</td>   #Col. 1		  
<td align="center" height="40" class="error"><?=$mensaje_de_error ?></font> 
<td> &nbsp</td>   #Col. 3
</tr>	


<?php	 
	 if ($error) {
        # Fila 2a
           echo "      <tr>\n";
       echo "         <td> &nbsp</td>\n";   #Col. 1		  
       echo "         <td align=\"center\" height=\"40\" class=\"error\">$mensaje_de_error</font>\n";   #Col. 2
       echo "         <td> &nbsp</td>\n";   #Col. 3
       echo "      </tr>\n";			 
   } elseif ($buscar AND $resultado) {	
       # Fila 2a
       echo "      <tr>\n";
       echo "         <td> &nbsp</td>\n";   #Col. 1		  
       echo "         <td align=\"left\">Resultado de la búsqueda:</td>\n";   #Col. 2
       echo "         <td> &nbsp</td>\n";   #Col. 3
       echo "      </tr>\n";				            
       # Fila 2b		
       echo "		  <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=$pag&id=$session_id\" accept-charset=\"utf-8\">\n";	 
       echo "      <tr>\n";  	
       echo "         <td> &nbsp</td>\n";   #Col. 1                       
       echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	
       echo "            <table width=\"100%\" border=\"0\">\n";
       echo "               <tr>\n";
       echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextH\">\n";
       echo "                     <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";			
       echo "                     <input name=\"Submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Ver\" />\n";
       echo "                	</td>\n";
       if ($mod == 41) {							
          echo "                  <td align=\"center\" width=\"20%\" class=\"bodyTextH\">$titulo1</td>\n";
          echo "                  <td align=\"center\" width=\"35%\" class=\"bodyTextH\">$titulo2</td>\n";
           } else {
          echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">$titulo1</td>\n";
          echo "                  <td align=\"center\" width=\"40%\" class=\"bodyTextH\">$titulo2</td>\n";
         }						
       echo "                  <td align=\"center\" width=\"38%\" class=\"bodyTextH\">$titulo3</td>\n";			
       echo "               </tr>\n";
       echo "            </table>\n"; 						
       echo "         </td>\n";
       echo "         <td> &nbsp</td>\n";   #Col. 3   		
       echo "      </tr>\n";
       echo "      <tr>\n";  	
       echo "         <td> &nbsp</td>\n";   #Col. 1                       
       echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	
     echo "            <div style=\"height:400px; overflow:auto\">\n";				
       echo "            <table width=\"100%\" border=\"0\" id=\"registros2\">\n";							
           $i = $j = $k = 0;		
           $m = 25;
           $show_color = false;
     while ($j < $filas) {
              if (!$show_color){
                 echo "               <tr>\n";
                       $show_color = true;
                } else {
              echo "      <tr class=\"alt\">\n";	
                       $show_color = false;		 
                }    		 
                if ($j == 0) {	 
           echo "                  <td align=\"center\"><input name=\"$var_submit\" value=\"$valor_submit[$j]\" type=\"radio\" checked=\"checked\"></td>\n"; 
          } else {
           echo "                  <td align=\"center\"><input name=\"$var_submit\" value=\"$valor_submit[$j]\" type=\"radio\"></td>\n"; 				 
                }
                echo "                  <td align=\"center\">$valor1[$j]</td>\n";
                if ($mod == 121) {	
             echo "                  <td align=\"center\">&nbsp $valor2[$j]</td>\n";
                } else {
                   echo "                  <td align=\"center\">$valor2[$j]</td>\n";
                }
          echo "                  <td align=\"center\">$valor3[$j]</td>\n";				 				 
        echo "               </tr>\n";	
#				 echo "OUTPUT: $output[$j], CODIGO: $codigo[$j] <br />\n";				 
          $j++;
                $k++;
              if (($k == 5525) AND ($filas - $m > 10)) {	
              echo "               <tr>\n";
             echo "                  <td align=\"center\" class=\"bodyTextH\">\n";		
           echo "                     <input name=\"Submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Ver\" />\n";
                 echo "                	</td>\n";			
             echo "                  <td align=\"center\" class=\"bodyTextH\">$titulo1</td>\n";
             echo "                  <td align=\"center\" class=\"bodyTextH\">$titulo2</td>\n";
             echo "                  <td align=\"center\" class=\"bodyTextH\">$titulo3</td>\n";			
             echo "               </tr>\n";
                       $m = $m + $k;
                       $k = 0;		
                }
     } # END_OF_WHILE			
     pg_free_result($result);		
           echo "               <tr>\n";
       echo "                  <td width=\"7%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";	
           if ($mod == 41) {		
          echo "                  <td width=\"20%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";			
          echo "                  <td width=\"35%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";
           } else {
          echo "                  <td width=\"15%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";			
          echo "                  <td width=\"40%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";
           }
       echo "                  <td width=\"38%\" style='font-family: Arial; font-size: 3pt'> &nbsp</td>\n";			
     echo "               </tr>\n";			
       echo "            </table>\n"; 
       echo "            </div>\n";									
       echo "         </td>\n";
       echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 3   		
       echo "      </tr>\n";	
       echo "      <tr>\n";  	
       echo "         <td> &nbsp</td>\n";   #Col. 1                       
       echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	
       echo "            <table width=\"100%\" border=\"0\">\n";
        echo "               <tr>\n";
       echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextH\">\n";
       echo "                     <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";			
     echo "                     <input name=\"Submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Ver\" />\n";
           echo "                	</td>\n";			
       echo "                  <td align=\"center\" width=\"15%\" class=\"bodyTextH\">$titulo1</td>\n";
       echo "                  <td align=\"center\" width=\"40%\" class=\"bodyTextH\">$titulo2</td>\n";
       echo "                  <td align=\"center\" width=\"38%\" class=\"bodyTextH\">$titulo3</td>\n";			
       echo "               </tr>\n";
       echo "            </table>\n"; 						
       echo "         </td>\n";
       echo "         <td> &nbsp</td>\n";   #Col. 3   		
       echo "      </tr>\n";							  
           echo "      </form>\n";	
           
   } elseif ($buscar AND !$resultado) {
       echo "<h3><font color=\"red\">Busqueda sin resultado...</font></h3>\n";	
       echo "<p>Código catastral no existe: $cod_cat,\n";
       echo "el padron municipal: $cod_pad, el nombre del\n";	
       echo "títular: $nombre1 o el \n";
       echo "apellido del titular: $apellido1 en la base de datos</p>\n";	         		 
   }		 
?>
	# Ultima Fila
	      <tr height="100%"></tr>			 
	   </table>
	   <br />&nbsp;<br />
	</td>