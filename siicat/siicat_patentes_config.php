<?php
$act_pat_temp = $act_raz_temp = $act_prop_temp = $deuda_temp = array();
########################################
#----- NUMERO DE REGISTROS TOTAL ------#
########################################	
$sql="SELECT act_pat, act_raz, act_1pat, act_1mat, act_1nom1, act_1nom2 FROM patentes ORDER BY act_pat";
$numero_de_registros = pg_num_rows(pg_query($sql)); 
########################################
#------- LEER DATOS DE EDADES ---------#
########################################	
if ($numero_de_registros > 0) {
   $data_existe = true;
	 $result=pg_query($sql);
	 $i = $j = 0; 
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
			   if ($i == 0) {
				    $act_pat_temp[$j] = $col_value;
				 } elseif ($i == 1) {
				    $act_raz_temp[$j] = utf8_decode($col_value);
				 } elseif ($i == 2) {
				    $act_1pat = utf8_decode($col_value);
				 } elseif ($i == 3) {
				    $act_1mat = utf8_decode($col_value);												
				 } elseif ($i == 4) {
				    $act_1nom1 = utf8_decode($col_value);	
				 } else {
				    $act_1nom2 = utf8_decode($col_value);
						if ($act_1nom1 != "") {
						   if ($act_1nom2 != "") {
						      $act_prop_temp[$j] = $act_1nom1." ".$act_1nom2." ".$act_1pat." ".$act_1mat;
						   } else {
						      $act_prop_temp[$j] = $act_1nom1." ".$act_1pat." ".$act_1mat;							 
							 }
						} else {
						   $act_prop_temp[$j] = $act_1pat." ".$act_1mat;
						}
						$deuda_temp[$j] = 10*$j;
#echo "$j,$act_pat_temp[$j],$act_raz_temp[$j],$act_prop_temp[$j],$deuda_temp[$j]<br>";						
						$i = -1;
				 }
				 $i++;
			}
			$j++;
   }		

} else {
   $data_existe = false;
}
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	
	 echo "<td>\n";
	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
   # Fila 1
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"10%\"> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td width=\"70%\" align=\"center\" valign=\"center\" height=\"40\" class=\"pageName\">\n"; 
	 echo "            Listado de Patentes\n";                          
   echo "         </td>\n";
	 echo "         <td width=\"20%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";	
	 ### NUMERO DE REGISTROS ###
 	 echo "      <tr height=\"40\">\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1  
 	 echo "         <td align=\"center\" class=\"bodyTextD\"> NUMERO TOTAL DE REGISTROS: $numero_de_registros\n"; 
	 echo "         </td>\n";	
	 echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	 echo "      </tr>\n";	
	 ### TABLA ###
 	 echo "      <tr height=\"40\">\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1  
 	 echo "         <td align=\"left\" class=\"bodyTextD\"> PATENTES REGISTRADOS\n"; 
	 echo "         </td>\n";	
	 echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	 echo "      </tr>\n";	
	 echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td align=\"center\">\n";   #Col. 2    
 	 echo "      	     <table id=\"registros\">\n";  	 
 	 echo "               <tr>\n";
   echo "                  <th width=\"15%\">Patente</th>\n";
 	 echo "                  <th width=\"45%\">Contribuyente</th>\n";
 	 echo "                  <th width=\"28%\">Razon Social</th>\n";
 	 echo "                  <th width=\"12%\">Deuda</th>\n"; 	 	 
 	 echo "               </tr>\n";	
 	 echo "            </table>\n";
 	 echo "         </td>\n";		 
	 echo "         <td> &nbsp</td>\n";   #Col. 3  
 	 echo "      </tr>\n";	  
 	 echo "      <tr>\n";	
	 echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td align=\"center\">\n";   #Col. 2  
   echo "         <div style=\"height:400px; overflow:auto\">\n";	   
 	 echo "      	     <table id=\"registros\">\n";  	 
 	 #echo "               <tr>\n";
   #echo "                  <th>No. Patente</th>\n";
 	 #echo "                  <th>Contribuyente</th>\n";
 	 #echo "                  <th>Razon Social</th>\n";
 	 #echo "                  <th>Deuda Total</th>\n"; 	 	 
 	 #echo "               </tr>\n";
	 if ($data_existe) {
			$deuda_total = 0;
	    $i = 0;
			$show_color = false;
	    while ($i < $numero_de_registros) {
			   if (!$show_color){
			      echo "               <tr>\n";
						$show_color = true;
				 } else {
 	          echo "      <tr class=\"alt\">\n";	
						$show_color = false;		 
				 }
 	       echo "                  <td>\n";
				 echo "                     <a href=\"index.php?mod=103&pat=$act_pat_temp[$i]&id=$session_id\">$act_pat_temp[$i]</a>\n";
				 echo "                  </td>\n";
 	       echo "                  <td>$act_prop_temp[$i]</td>\n";
 	       echo "                  <td>$act_raz_temp[$i]</td>\n";
 	       echo "                  <td>$deuda_temp[$i]</td>\n";						  
 	       echo "               </tr>\n";
				 $deuda_total = $deuda_total + $deuda_temp[$i];
			   $i++;
			}
 	    echo "      <tr class=\"total\">\n";
 	    echo "         <td width=\"15%\">TOTAL</td>\n";
 	    echo "         <td width=\"45%\"> &nbsp</td>\n";
 	    echo "         <td width=\"30%\"> &nbsp</td>\n";	
 	    echo "         <td width=\"10%\">$deuda_total</td>\n";							 
 	    echo "      </tr>\n";		
	 } else {
 	    echo "      <tr>\n"; 
 	    echo "         <td align=\"center\" colspan=\"8\"> Aún no hay registros en la base de datos!\n"; 
	    echo "         </td>\n";	
	    echo "      </tr>\n";		 
	 }	   
 	 echo "            </table>\n";
	 echo "   </div>\n";	 
 	 echo "         </td>\n";		 
	 echo "         <td> &nbsp</td>\n";   #Col. 1  
 	 echo "      </tr>\n";	 
 # echo "      <tr height=\"20\">\n";
	# echo "         <td> &nbsp</td>\n";   #Col. 1  
 	# echo "         <td align=\"right\" class=\"bodyTextD\"> Fecha: $mes_actual/$ano_actual\n"; 
	# echo "         </td>\n";	
	# echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	# echo "      </tr>\n";	 
	 	 		  
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";	 
	 echo "   <br />&nbsp;<br />\n";
	 echo "</td>\n";	
	
?>