<?php
$no_de_uv = array();
$no_de_ninas = $no_de_ninos = $no_de_menores = array();
$no_de_mujeres = $no_de_hombres = $no_de_adultos = $no_total = array();
########################################
#----- NUMERO DE REGISTROS TOTAL ------#
########################################	
$sql="SELECT cod_uv FROM vehic";
$numero_de_registros = pg_num_rows(pg_query($sql)); 
/*
########################################
#------- LEER DATOS DE EDADES ---------#
########################################	
$sql="SELECT DISTINCT cod_uv FROM vehic";
$check_uv = pg_num_rows(pg_query($sql)); 
if ($check_uv == 0) {
   $data_existe = false;
	 $texto = "";
} else {
   $data_existe = true;
	 $result=pg_query($sql);
	 $j = 0; 
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
			   $no_de_uv[$j] = $col_value;		 
         $sql="SELECT veh_cls FROM info_predio WHERE cod_uv = '$col_value'";	
	       $result2=pg_query($sql);
	       $i = 0;
				 $numero_ninas = $numero_ninos = $numero_mujeres = $numero_hombres = 0; 
         while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
            foreach ($line2 as $col_value2) {	
						   if ($i == 0) {
							    $temp_ninas = get_edades ($col_value2,18);
									$numero_ninas = $numero_ninas + $temp_ninas;
							    $temp_mujeres = get_edades ($col_value2,150);
									$numero_mujeres = $numero_mujeres + $temp_mujeres;									
							 } else {
							 	  $temp_ninos = get_edades ($col_value2,18);
									$numero_ninos = $numero_ninos + $temp_ninos;	
							 	  $temp_hombres = get_edades ($col_value2,150);
									$numero_hombres = $numero_hombres + $temp_hombres;																		
							    $i=-1;
							 }
							 $i++;	
						}		  
				 }
				 $no_de_ninas[$j] = $numero_ninas;
				 $no_de_ninos[$j] = $numero_ninos;
				 $no_de_menores[$j] = $numero_ninas + $numero_ninos;
				 $no_de_mujeres[$j] = $numero_mujeres - $numero_ninas;
				 $no_de_hombres[$j] = $numero_hombres - $numero_ninos;
				 $no_de_adultos[$j] = $numero_mujeres + $numero_hombres - $numero_ninas - $numero_ninos;	
				 $no_total[$j] = $numero_mujeres + $numero_hombres;			 
      }
			$j++;
   } 
}
$numero_total_de_uvs = $j; */
########################################
#--------- CLASE DE VEHICULO ----------#
########################################	
$sql="SELECT veh_cls FROM vehic";
$check_vehic = pg_num_rows(pg_query($sql)); 
if ($check_vehic == 0) {
   $vehic_existe = false;
	 $check_class = 0;
} else {
   $vehic_existe = true;
	 $cant_por_clase = $class_name = array();
	 $sql2="SELECT cls_name FROM vehic_clase ORDER BY veh_cls";
   $check_class = pg_num_rows(pg_query($sql2)); 
#echo "CHECK_CLASS: $check_class<br>";		 
   $result2=pg_query($sql2);
	 $i = 0;
   while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
      foreach ($line2 as $col_value2) {	
#echo "$col_value2,";	 		
				 $class_name[$i] = utf8_decode ($col_value2);
	       $cant_por_clase[$i] = "0";
			   $i++;   
			}
   } 
	 # RELLENAR 4 FILAS DEL ARRAY MAS
	 $class_name[$i] = ""; $cant_por_clase[$i] = ""; $i++;
	 $class_name[$i] = ""; $cant_por_clase[$i] = ""; $i++;
	 $class_name[$i] = ""; $cant_por_clase[$i] = ""; $i++;
	 $class_name[$i] = ""; $cant_por_clase[$i] = "";	 	 	 	 
	 pg_free_result($result2); 	 
	 $result=pg_query($sql);
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
			   $array_class = $col_value -1;			
			   $array_value = (int) $cant_por_clase[$array_class];
         $array_value++;
			   $cant_por_clase[$array_class] = $array_value++;   
			}
   } 
	 pg_free_result($result); 	 
}
########################################
#---------- USO DE VEHICULO -----------#
########################################	
$sql="SELECT veh_serv FROM vehic";
$check_veh_serv = pg_num_rows(pg_query($sql)); 
if ($check_veh_serv == 0) {
   $veh_serv_existe = false;
} else {
   $veh_serv_existe = true;
   $particular = $oficial = $publico = $dipl = $otro = 0;
	 $result=pg_query($sql);
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
			   if ($col_value == "PAR") {
			      $particular++;	
				 } elseif ($col_value == "OCL") {
			      $oficial++;	
				 } elseif ($col_value == "PUB") {
			      $publico++;	
				 } elseif ($col_value == "DPL") {
			      $dipl++;																						 
         } else {
				    $otro++;
				 }
			}
   }
	 $suma_total_serv = $particular + $oficial + $publico + $dipl + $otro;
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
	 echo "            Estadísticas sobre los Vehículos\n";                          
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
	 ### TABLA CANTIDAD DE VEHICULOS ###
 	 echo "      <tr height=\"40\">\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1  
 	 echo "         <td align=\"left\" class=\"bodyTextD\"> CANTIDAD DE VEHICULOS POR CLASE\n";  #Col. 2   
	 echo "         </td>\n";	
	 echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	 echo "      </tr>\n";	
 	 echo "      <tr>\n";	
	 echo "         <td> &nbsp</td>\n"; 	 
	 echo "         <td align=\"center\">\n";   		   
 	 echo "      	     <table id=\"registros\">\n";
	 $i = $aa = 0; $bb = 1; $cc = 2; $dd = 3; $ee = 4;
	 while ($i < $check_class) {
 	    echo "               <tr>\n";
      echo "                  <th>$class_name[$aa]</th>\n";
 	    echo "                  <th>$class_name[$bb]</th>\n";
 	    echo "                  <th>$class_name[$cc]</th>\n";
 	    echo "                  <th>$class_name[$dd]</th>\n";
 	    echo "                  <th>$class_name[$ee]</th>\n"; 	 
 	    echo "               </tr>\n";
	    if ($vehic_existe) { 
         echo "               <tr class=\"total\">\n";
         echo "                  <td>$cant_por_clase[$aa]</td>\n";
 	       echo "                  <td>$cant_por_clase[$bb]</td>\n";
 	       echo "                  <td>$cant_por_clase[$cc]</td>\n";
 	       echo "                  <td>$cant_por_clase[$dd]</td>\n";	
 	       echo "                  <td>$cant_por_clase[$ee]</td>\n";							  
 	       echo "               </tr>\n";
	    }
	    $i = $i+5;
			$aa = $aa+5; $bb = $bb+5; $cc = $cc+5; $dd = $dd+5; $ee = $ee+5;
	 } 
	 if (!$vehic_existe) {
 	    echo "               <tr>\n"; 
 	    echo "                  <td align=\"center\" colspan=\"5\"> No hay registros en la base de datos!\n"; 
	    echo "                  </td>\n";	
	    echo "               </tr>\n";		 
	 }	 
 	 echo "            </table>\n";
 	 echo "         </td>\n";		 
	 echo "         <td> &nbsp</td>\n";   #Col. 3  
 	 echo "      </tr>\n";	 
 	 echo "      <tr height=\"20\">\n";
	 echo "         <td> &nbsp</td>\n";   
 	 echo "         <td align=\"right\" class=\"bodyTextD\"> Fecha de censo: Junio 2011\n"; 
	 echo "         </td>\n";	
	 echo "         <td> &nbsp</td>\n";     	 
	 echo "      </tr>\n";	
	 ### TABLA SERVICIO ###
 	 echo "      <tr height=\"40\">\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1  
 	 echo "         <td align=\"left\" class=\"bodyTextD\"> SERVICIO\n";  #Col. 2   
	 echo "         </td>\n";	
	 echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	 echo "      </tr>\n";	
 	 echo "      <tr>\n";	
	 echo "         <td> &nbsp</td>\n"; 	 
	 echo "         <td align=\"center\">\n";   		   
 	 echo "      	     <table id=\"registros\">\n";
 	 echo "               <tr>\n";
   echo "                  <th> Particular</th>\n";
 	 echo "                  <th> Oficial</th>\n";
 	 echo "                  <th> Público</th>\n";
 	 echo "                  <th> Diplomático</th>\n";
 	 echo "                  <th> Otro</th>\n";
 	 echo "                  <th> TOTAL</th>\n";	 	 
 	 echo "               </tr>\n";
	 if ($veh_serv_existe) { 
      echo "               <tr class=\"total\">\n";
      echo "                  <td>$particular</td>\n";
 	    echo "                  <td>$oficial</td>\n";
 	    echo "                  <td>$publico</td>\n";
 	    echo "                  <td>$dipl</td>\n";	
 	    echo "                  <td>$otro</td>\n";
 	    echo "                  <td>$suma_total_serv</td>\n";										  
 	    echo "               </tr>\n";
	 } else {
 	    echo "               <tr>\n"; 
 	    echo "                  <td align=\"center\" colspan=\"6\"> No hay registros en la base de datos!\n"; 
	    echo "                  </td>\n";	
	    echo "               </tr>\n";		 
	 }	 
 	 echo "            </table>\n";
 	 echo "         </td>\n";		 
	 echo "         <td> &nbsp</td>\n";   #Col. 3  
 	 echo "      </tr>\n";	 
 	 echo "      <tr height=\"20\">\n";
	 echo "         <td> &nbsp</td>\n";   
 	 echo "         <td align=\"right\" class=\"bodyTextD\"> Fecha de censo: Junio 2011\n"; 
	 echo "         </td>\n";	
	 echo "         <td> &nbsp</td>\n";     	 
	 echo "      </tr>\n";		  		 	
	 	 		  
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";	 
	 echo "   <br />&nbsp;<br />\n";
	 echo "</td>\n";	
	
?>