<?php

$no_de_uv = array();
$no_de_ninas = $no_de_ninos = $no_de_menores = array();
$no_de_mujeres = $no_de_hombres = $no_de_adultos = $no_total = array();
########################################
#----- NUMERO DE REGISTROS TOTAL ------#
########################################	
$sql="SELECT id_inmu FROM info_socioeco";
$numero_total_de_inmu = pg_num_rows(pg_query($sql));
$sql="SELECT id_inmu FROM info_socioeco WHERE soe_est = '' AND soe_ocu = '' AND soe_civ  = ''";
$numero_de_vacios = pg_num_rows(pg_query($sql));
$numero_de_registrados = $numero_total_de_inmu - $numero_de_vacios; 
$numero_de_registrados_perc = ROUND ($numero_de_registrados*100/$numero_total_de_inmu,1);
########################################
#------- LEER DATOS DE EDADES ---------#
########################################	
$registros_totales = $registros_vacios = $registros_edades_vacios = 0;
$sql="SELECT DISTINCT cod_uv FROM info_inmu ORDER BY cod_uv";
$check_inmu = pg_num_rows(pg_query($sql));
$j = 0;  
if ($check_inmu == 0) {
   $data_existe = false;
	 $texto = "";
} else {
   $data_existe = true;
	 $result=pg_query($sql);
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
			   $no_de_uv[$j] = $col_value;	
         $sql2="SELECT id_inmu FROM info_inmu WHERE cod_uv = '$col_value' ORDER BY id_inmu";					 	 
	       $result2=pg_query($sql2);
	       $i = 0;
				 $numero_ninas = $numero_ninos = $numero_mujeres = $numero_hombres = 0; 
         while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
            foreach ($line2 as $col_value2) {
						   $id_inmu = $col_value2;
               $sql3="SELECT soe_est,soe_ocu,soe_civ,soe_muj,soe_hom FROM info_socioeco WHERE id_inmu = '$id_inmu'";
							 $result3 = pg_query($sql3);
               $info = pg_fetch_array($result3, null, PGSQL_ASSOC);
               $soe_est = trim($info['soe_est']);
               $soe_ocu = trim($info['soe_ocu']);
               $soe_civ = trim($info['soe_civ']);							 						 
               $soe_muj = $info['soe_muj'];
               $soe_hom = $info['soe_hom'];
               pg_free_result($result3);		
							 $temp_ninas = get_edades ($soe_muj,18);			
               $numero_ninas = $numero_ninas + $temp_ninas;
				       $temp_mujeres = get_edades ($soe_muj,150);
							 $numero_mujeres = $numero_mujeres + $temp_mujeres;							 
							 $temp_ninos = get_edades ($soe_hom,18);
							 $numero_ninos = $numero_ninos + $temp_ninos;	
							 $temp_hombres = get_edades ($soe_hom,150);
							 $numero_hombres = $numero_hombres + $temp_hombres;	
							 $registros_totales++;						 
							 if ((($soe_est == "")  OR ($soe_est === NULL)) AND (($soe_ocu == "") OR ($soe_ocu === NULL))  AND (($soe_civ == "") OR ($soe_civ === NULL))) {
							    $registros_vacios++;
							 } else {	
							    if (($temp_mujeres == 0) AND ($temp_hombres == 0)) {	
							       $registros_edades_vacios++;
							    }	
						   }															
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
$registros_con_datos = $registros_totales - $registros_vacios;
$registros_con_datos_perc = ROUND ($registros_con_datos*100/$registros_totales,1);
$registros_edades_con_datos = $registros_con_datos - $registros_edades_vacios;
$registros_edades_con_datos_perc = ROUND ($registros_edades_con_datos*100/$registros_con_datos,1);
#echo "REGISTROS TOTALES: $registros_totales<br />";
#echo "REGISTROS VACIOS: $registros_vacios<br />";
#echo "REGISTROS CON DATOS: $registros_con_datos<br />";

$numero_total_de_uvs = $j;
########################################
#--------- GRADO DE ESTUDIOS ----------#
########################################	
$registros_est = 0;
$sql="SELECT soe_est FROM info_socioeco WHERE soe_est != ''";
$check_soe_est = pg_num_rows(pg_query($sql)); 
if ($check_soe_est == 0) {
   $soe_est_existe = false;
} else {
   $soe_est_existe = true;
   $basico = $intermedio = $medio = $univers = 0;
	 $result=pg_query($sql);
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
			   if ($col_value == 'GEB') {
			      $basico++;	
				 } elseif ($col_value == 'GEI') {
			      $intermedio++;	
				 } elseif ($col_value == 'GEM') {
			      $medio++;	
				 } elseif ($col_value == 'GEU') {
			      $univers++;														 
         }
				 $registros_est++;
			}
   } 
}
$registros_est_perc = ROUND ($registros_est*100/$registros_con_datos,1);
########################################
#--------- INGRESO PROMEDIO -----------#
########################################
$registros_ing = 0;
$sql="SELECT soe_ing FROM info_socioeco WHERE soe_ing != '-1'";
$check_soe_ing = pg_num_rows(pg_query($sql)); 
if ($check_soe_ing == 0) {
   $soe_ing_existe = false;
} else {
   $soe_ing_existe = true;
   $hasta500 = $hasta1000 = $hasta2000 = $hasta4000 = $hasta8000 = $masque8000 = $suma_total = 0;
	 $result=pg_query($sql);
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
			   if ($col_value < 500) {
			      $hasta500++;	
				 } elseif ($col_value < 1000) {
			      $hasta1000++;	
				 } elseif ($col_value < 2000) {
			      $hasta2000++;	
				 } elseif ($col_value < 4000) {
			      $hasta4000++;			
				 } elseif ($col_value < 8000) {
			      $hasta8000++;																			 
         } else {
				    $masque8000++;
				 }
				 $suma_total = $suma_total + $col_value;
				 $registros_ing++;
			}
   }
	 $promedio_total = ROUND ($suma_total/$check_soe_ing,0); 
}
$registros_ing_perc = ROUND ($registros_ing*100/$registros_con_datos,1);
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	
	 echo "<td>\n";
	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
   # Fila 1
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"10%\"> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td width=\"70%\" align=\"center\" valign=\"center\" height=\"40\" class=\"pageName\">\n"; 
	 echo "            Datos Socio-Económicos\n";                          
   echo "         </td>\n";
	 echo "         <td width=\"20%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";	
	 ### NUMERO DE REGISTROS ###
 	 echo "      <tr height=\"40\">\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1  
 	 echo "         <td align=\"center\" class=\"bodyTextD\"> NUMERO TOTAL DE INMUEBLES CON DATOS: $numero_de_registrados de $numero_total_de_inmu inmuebles ($numero_de_registrados_perc %)\n"; 
	 echo "         </td>\n";	
	 echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	 echo "      </tr>\n";	
	 ### TABLA INGRESO PROMEDIO ###
 	 echo "      <tr height=\"40\">\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1  
 	 echo "         <td align=\"left\" class=\"bodyTextD\"> INGRESO PROMEDIO MENSUAL DE LA FAMILIA (EN BS.)\n";  #Col. 2   
	 echo "         </td>\n";	
	 echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	 echo "      </tr>\n";	
 	 echo "      <tr>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1  
 	 echo "         <td align=\"center\"> REGISTROS: $registros_ing DE $registros_con_datos INMUEBLES CON DATOS ($registros_ing_perc %)\n";	  
	 echo "         </td>\n";	
	 echo "         <td> &nbsp</td>\n";   #Col. 3
	 echo "      </tr>\n";	 
 	 echo "      <tr>\n";	
	 echo "         <td> &nbsp</td>\n"; 	 
	 echo "         <td align=\"center\">\n";   		   
 	 echo "      	     <table id=\"registros\">\n";
 	 echo "               <tr>\n";
   echo "                  <th> < 500</th>\n";
 	 echo "                  <th> < 1000</th>\n";
 	 echo "                  <th> < 2000</th>\n";
 	 echo "                  <th> < 4000</th>\n";
 	 echo "                  <th> < 8000</th>\n";
 	 echo "                  <th> > 8000</th>\n";	 	 
 	 echo "                  <th>PROMEDIO</th>\n"; 	 
 	 echo "               </tr>\n";
	 if ($soe_ing_existe) { 
      echo "               <tr class=\"total\">\n";
      echo "                  <td>$hasta500</td>\n";
 	    echo "                  <td>$hasta1000</td>\n";
 	    echo "                  <td>$hasta2000</td>\n";
 	    echo "                  <td>$hasta4000</td>\n";	
 	    echo "                  <td>$hasta8000</td>\n";
 	    echo "                  <td>$masque8000</td>\n";	
 	    echo "                  <td>$promedio_total</td>\n";										  
 	    echo "               </tr>\n";
	 } else {
 	    echo "               <tr>\n"; 
 	    echo "                  <td align=\"center\" colspan=\"7\"> No hay registros en la base de datos!\n"; 
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
	 ### TABLA GRADO DE ESTUDIO ###
 	 echo "      <tr height=\"40\">\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1  
 	 echo "         <td align=\"left\" class=\"bodyTextD\"> GRADO DE ESTUDIO DE LA CABEZA DE FAMILIA\n";  #Col. 2   
	 echo "         </td>\n";	
	 echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	 echo "      </tr>\n";
 	 echo "      <tr>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1  
 	 echo "         <td align=\"center\"> REGISTROS: $registros_est DE $registros_con_datos INMUEBLES CON DATOS ($registros_est_perc %)\n";	  
	 echo "         </td>\n";	
	 echo "         <td> &nbsp</td>\n";   #Col. 3
	 echo "      </tr>\n";	 	
 	 echo "      <tr>\n";	
	 echo "         <td> &nbsp</td>\n"; 	 
	 echo "         <td align=\"center\">\n";   		   
 	 echo "      	     <table id=\"registros\">\n";
 	 echo "               <tr>\n";
   echo "                  <th>Básico</th>\n";
 	 echo "                  <th>Intermedio</th>\n";
 	 echo "                  <th>Medio</th>\n";
 	 echo "                  <th>Universitario</th>\n";
 	 echo "                  <th>TOTAL</th>\n"; 	 
 	 echo "               </tr>\n";
	 if ($soe_est_existe) { 
      echo "               <tr class=\"total\">\n";
      echo "                  <td>$basico</td>\n";
 	    echo "                  <td>$intermedio</td>\n";
 	    echo "                  <td>$medio</td>\n";
 	    echo "                  <td>$univers</td>\n";	
 	    echo "                  <td>$check_soe_est</td>\n";							  
 	    echo "               </tr>\n";
	 } else {
 	    echo "               <tr>\n"; 
 	    echo "                  <td align=\"left\" colspan=\"5\"> No hay registros en la base de datos!\n"; 
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
	 ### TABLA EDAD ###
 	 echo "      <tr height=\"40\">\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1  
 	 echo "         <td align=\"left\" class=\"bodyTextD\"> ESTRUCTURA DE EDAD DE LA POBLACION\n"; 
	 echo "         </td>\n";	
	 echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	 echo "      </tr>\n";
 	 echo "      <tr>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1  
 	 echo "         <td align=\"center\"> REGISTROS: $registros_edades_con_datos DE $registros_con_datos INMUEBLES CON DATOS ($registros_edades_con_datos_perc %)\n";	  
	 echo "         </td>\n";	
	 echo "         <td> &nbsp</td>\n";   #Col. 3
	 echo "      </tr>\n";	 	 	
 	 echo "      <tr>\n";	
	 echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	 echo "         <td align=\"center\">\n";   #Col. 2  		   
 	 echo "      	     <table id=\"registros\">\n";
 	 echo "               <tr>\n";
   echo "                  <th>U.V.</th>\n";
 	 echo "                  <th>Niñas</th>\n";
 	 echo "                  <th>Niños</th>\n";
 	 echo "                  <th>Total Menores</th>\n";
 	 echo "                  <th>Mujeres</th>\n";
 	 echo "                  <th>Hombres</th>\n";
 	 echo "                  <th>Total Adultos</th>\n";
 	 echo "                  <th>TOTAL</th>\n";	 	 	 
 	 echo "               </tr>\n";
	 if ($data_existe) {
	    $numero_total_de_ninas = $numero_total_de_ninos = $numero_total_de_menores = 0;
			$numero_total_de_mujeres = $numero_total_de_hombres = $numero_total_de_adultos = 0;
			$numero_total_total = 0;
	    $i = 0;
			$show_color = false;
	    while ($i < $numero_total_de_uvs) {
			   if (!$show_color){
			      echo "               <tr>\n";
						$show_color = true;
				 } else {
 	          echo "      <tr class=\"alt\">\n";	
						$show_color = false;		 
				 }
 	       echo "                  <td>$no_de_uv[$i]</td>\n";
 	       echo "                  <td>$no_de_ninas[$i]</td>\n";
 	       echo "                  <td>$no_de_ninos[$i]</td>\n";
 	       echo "                  <td>$no_de_menores[$i]</td>\n";	
 	       echo "                  <td>$no_de_mujeres[$i]</td>\n";
 	       echo "                  <td>$no_de_hombres[$i]</td>\n";
 	       echo "                  <td>$no_de_adultos[$i]</td>\n";	
				 echo "                  <td>$no_total[$i]</td>\n";					  
 	       echo "               </tr>\n";
				 $numero_total_de_ninas = $numero_total_de_ninas + $no_de_ninas[$i];
				 $numero_total_de_ninos = $numero_total_de_ninos + $no_de_ninos[$i];
				 $numero_total_de_menores = $numero_total_de_menores + $no_de_menores[$i];
				 $numero_total_de_mujeres = $numero_total_de_mujeres + $no_de_mujeres[$i];
				 $numero_total_de_hombres = $numero_total_de_hombres + $no_de_hombres[$i];
				 $numero_total_de_adultos = $numero_total_de_adultos + $no_de_adultos[$i];		
				 $numero_total_total = $numero_total_total + $no_total[$i]; 
			   $i++;
			}
 	    echo "      <tr class=\"total\">\n";
 	    echo "         <td>TOTAL</td>\n";
 	    echo "         <td>$numero_total_de_ninas</td>\n";
 	    echo "         <td>$numero_total_de_ninos</td>\n";
 	    echo "         <td>$numero_total_de_menores</td>\n";
 	    echo "         <td>$numero_total_de_mujeres</td>\n";
 	    echo "         <td>$numero_total_de_hombres</td>\n";
 	    echo "         <td>$numero_total_de_adultos</td>\n";	
 	    echo "         <td>$numero_total_total</td>\n";							 
 	    echo "      </tr>\n";
	 } else {
 	    echo "      <tr>\n"; 
 	    echo "         <td align=\"center\" colspan=\"8\"> Aún no hay registros en la base de datos!\n"; 
	    echo "         </td>\n";	
	    echo "      </tr>\n";		 
	 }	 
 	 echo "            </table>\n";
 	 echo "         </td>\n";		 
	 echo "         <td> &nbsp</td>\n";   #Col. 1  
 	 echo "      </tr>\n";	 
 	 echo "      <tr height=\"20\">\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1  
 	 echo "         <td align=\"right\" class=\"bodyTextD\"> Fecha de censo: Junio 2011\n"; 
	 echo "         </td>\n";	
	 echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	 echo "      </tr>\n";	 
	 	 		  
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";	 
	 echo "   <br />&nbsp;<br />\n";
	 echo "</td>\n";	
	
?>