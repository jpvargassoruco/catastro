<?php


if (isset($_GET['con'])) {
   $id_contrib = $_GET['con'];
}
########################################
#-------- SELECCIONAR INMUEBLES -------#
########################################	
$sql="SELECT id_inmu FROM info_inmu WHERE tit_1id = '$id_contrib' OR tit_2id = '$id_contrib'"; 
$check_inmuebles = pg_num_rows(pg_query($sql));
$i = 0;
if ($check_inmuebles > 0) {
	 $result=pg_query($sql);
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
	    foreach ($line as $col_value) {
			   $lista_inmuebles[$i] = $col_value;
				 $lista_codcat[$i] = get_codcat_from_id_inmu ($col_value);
				 $i++;   			
	    }
	 } 
}	
$numero_de_inmuebles = $i;
########################################
#--------- SELECCIONAR PATENTES -------#
########################################	
$sql="SELECT act_pat FROM patentes WHERE id_contrib = '$id_contrib'"; 
$check_patentes = pg_num_rows(pg_query($sql));
$i = 0;
if ($check_patentes > 0) {
	 $result=pg_query($sql);
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
	    foreach ($line as $col_value) {
			   $lista_patente[$i] = $col_value;
				 $lista_act_raz[$i] = get_patente_act_raz($col_value);
				 $i++;   			
	    }
	 } 	 
}	
$numero_de_patentes = $i;
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	
 	 echo "            <table width=\"100%\">\n";
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"left\" colspan=\"2\" class=\"bodyTextD\">&nbsp Inmuebles Urbanos</td>\n";			 				 
   echo "               </tr>\n";
	 if ($check_inmuebles == 0) {		 
 	    echo "               <tr>\n";				 
	    echo "                  <td align=\"left\" class=\"bodyTextD\">&nbsp</td>\n";		 			           
	    echo "                  <td align=\"left\" class=\"bodyTextD\">&nbsp No hay inmuebles registrados. &nbsp</td>\n";				 				 
	    echo "               </tr>\n";   
	 } else {
	    $i = 0;
			while ($i < $numero_de_inmuebles) {
			   $j = $i + 1;
 	       echo "               <tr>\n";				 
	       echo "                  <td align=\"left\" class=\"bodyTextD\">&nbsp</td>\n";		 			           
	       echo "                  <td align=\"left\" class=\"bodyTextD\">&nbsp Inmueble No. $j: Código Catastral $lista_codcat[$i] &nbsp</td>\n";				 				 
	       echo "               </tr>\n";			
			   $i++;
			}
	 }
 	 echo "               <tr>\n";				 
	 echo "                  <td colspan=\"2\">&nbsp</td>\n";		 			           			 				 
   echo "               </tr>\n";		 
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"left\" colspan=\"2\" class=\"bodyTextD\">&nbsp Inmuebles Rurales</td>\n";			 				 
   echo "               </tr>\n";		 
 	 echo "               <tr>\n";				 
	 echo "                  <td align=\"left\" class=\"bodyTextD\">&nbsp</td>\n";		 			           
	 echo "                  <td align=\"left\" class=\"bodyTextD\">&nbsp Sin registros. &nbsp</td>\n";				 				 
   echo "               </tr>\n";	
 	 echo "               <tr>\n";				 			           
	 echo "                  <td colspan=\"2\">&nbsp </td>\n";			 				 
   echo "               </tr>\n";		 	
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"left\" colspan=\"2\" class=\"bodyTextD\">&nbsp Actividades Economicas</td>\n";			 				 
   echo "               </tr>\n";	
	 if ($check_patentes == 0) {
 	    echo "               <tr>\n";				 
	    echo "                  <td align=\"right\" class=\"bodyTextD\">&nbsp</td>\n";		 			           
	    echo "                  <td align=\"left\" class=\"bodyTextD\">&nbsp Sin registros. &nbsp</td>\n";				 				 
      echo "               </tr>\n";		
	 } else {
	    $i = 0;
			while ($i < $numero_de_patentes) {
			   $j = $i + 1;
 	       echo "               <tr>\n";				 
	       echo "                  <td align=\"left\" class=\"bodyTextD\">&nbsp</td>\n";		 			           
	       echo "                  <td align=\"left\" class=\"bodyTextD\">&nbsp Patente No. $j: Razon Social $lista_act_raz[$i] &nbsp</td>\n";				 				 
	       echo "               </tr>\n";			
			   $i++;
			}
	 }   
	 echo "                  <td align=\"left\" width=\"15%\" class=\"bodyTextD\">&nbsp</td>\n";		 			           
	 echo "                  <td align=\"left\" width=\"85%\" class=\"bodyTextD\">&nbsp</td>\n";		 
 	 echo "            </table>\n";

?> 