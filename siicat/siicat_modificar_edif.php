<?php
   
#$esc= pg_escape_string('selected=\"selected\"');
if (isset($_POST["submit"])) {

   $error = $error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = false;	 

   ########################################
   #      Chequear si existen filas       #
   ########################################	
   $sql="SELECT * FROM info_edif WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' ORDER BY edi_num, edi_piso";
   $check_integrity = pg_num_rows(pg_query($sql));
   if ($check_integrity > 0 ) {	 
      $resultado = true;
	    $no_de_edificaciones = $check_integrity;
      ########################################
	    #    Verificar cual es el separador    #
	    ########################################	
      #$sql="SELECT * FROM plano_info WHERE prop_id='$prop_id' AND poly = '$poly'";
      $result = pg_query($sql);
   #   $info = pg_fetch_array($result, null, PGSQL_ASSOC);
	    $x = $i = 0;
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {		 			           
         foreach ($line as $col_value) {
			      if (($i == 0) OR ($i == 1) OR ($i == 2) OR ($i == 3)) { 	
						   $i++;
				    } elseif ($i == 4) { 	
	             $edi_num[$x] = $col_value;
						   $i++;
				    } elseif ($i == 5) { 	
	             $edi_piso[$x] = $col_value;
						   $i++;
				    } elseif ($i == 6) { 	
	             $edi_ubi[$x] = $col_value;
						   $i++;
				    } elseif ($i == 7) { 	
	             $edi_tipo[$x] = $col_value;
						   $i++;
				    } elseif ($i == 8) { 	
	             $edi_edo[$x] = $col_value;
						   $i++;
				    } elseif ($i == 9) { 	
	             $edi_ano[$x] = $col_value;
						   $i++;
				    } elseif ($i == 10) { 	
	             $edi_cim[$x] = $col_value;
						   $i++;
				    } elseif ($i == 11) { 	
	             $edi_est[$x] = $col_value;
						   $i++;
				    } elseif ($i == 12) { 	
	             $edi_mur[$x] = $col_value;
						   $i++;
						} elseif ($i == 13) { 	
	             $edi_acab[$x] = $col_value;
						   $i++;	 
				    } elseif ($i == 14) { 	
	             $edi_rvin[$x] = $col_value;
						   $i++;
				    } elseif ($i == 15) { 	
	             $edi_rvex[$x] = $col_value;
						   $i++;
				    } elseif ($i == 16) { 	
	             $edi_rvba[$x] = $col_value;
						   $i++;
				    } elseif ($i == 17) { 	
	             $edi_rvco[$x] = $col_value;
						   $i++;
				    } elseif ($i == 18) { 	
	             $edi_cest[$x] = $col_value;
						   $i++;
				    } elseif ($i == 19) { 	
	             $edi_ctec[$x] = $col_value;
						   $i++;
				    } elseif ($i == 20) { 	
	             $edi_ciel[$x] = $col_value;
						   $i++;
				    } elseif ($i == 21) { 	
	             $edi_coc[$x] = $col_value;
						   $i++;
				    } elseif ($i == 22) { 	
	             $edi_ban[$x] = $col_value;
						   $i++;
				    } elseif ($i == 23) { 	
	             $edi_carp[$x] = $col_value;
						   $i++;
				    } elseif ($i == 24) { 	
	             $edi_elec[$x] = $col_value;
						   $i = 0;
				    }
			   }
			   $x++;
      }	# END_OF_WHILE			 										
   }
}
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	

$accion = "Modificar";
$codigo_fijo = true;

include "siicat_form_edif.php";
	
?>