<?php

$error1 = $error2 = $calcular = $formulario = $busqueda1 = false;

if ((isset($_POST["submit"])) AND (($_POST["submit"] == "Calcular") OR ($_POST["submit"] == "Formulario"))) {
	if ($_POST["submit"] == "Formulario") {
		$formulario = true;
		$numero = 100000;
	}
	echo "Cantidad $cant_de_items";
	$calcular = true;
	$id_contrib = $_POST["id_contrib"];
	$nombre  = trim($_POST["nombre"]);				
	$detalle = $_POST["detalle"];
	$observacion = $_POST["observacion"];
	$item1 = $_POST["item1"];
	$item2 = $_POST["item2"];
	$item3 = $_POST["item3"];
	$item4 = $_POST["item4"];
	$item5 = $_POST["item5"];

	$cant1 = $_POST["cant1"];
	$cant2 = $_POST["cant2"];	
	$cant3 = $_POST["cant3"];
	$cant4 = $_POST["cant4"];
	$cant5 = $_POST["cant5"];

	$superf1= $_POST["superf1"];
	$superf2= $_POST["superf2"];
	$superf3= $_POST["superf3"];
	$superf4= $_POST["superf4"];
	$superf5= $_POST["superf5"];

	$perime1= $_POST["perime1"];
	$perime2= $_POST["perime2"];
	$perime3= $_POST["perime3"];
	$perime4= $_POST["perime4"];
	$perime5= $_POST["perime5"];

	$punto1= $_POST["punto1"];
	$punto2= $_POST["punto2"];
	$punto3= $_POST["punto3"];
	$punto4= $_POST["punto4"];
	$punto5= $_POST["punto5"];	

	if (($id_contrib == "") AND ($nombre	== "")) {		
		$error1 = true;
		$mensaje_de_error1 = "Error: Tiene que elegir un nombre de la lista o ingresar un nombre!";	
		$calcular = false;
	}	elseif (($item1 == "") AND ($item2 == "") AND ($item3 == "")) {
		$error2 = true;
		$mensaje_de_error2 = "Error: Tiene que elegir al menos un item de la lista!";	
		$calcular = false;		
	}	else {
		$rubro = $no_rubro;
		$nombre_rubro = "Tasas Administrativas";
		$monto_total = 0;
		$i = 0;

		if ($item1 != "") {

			$sql = "SELECT descrip, monto, unidad, costo  FROM tasas WHERE id_tasa = '$item1'";
			$result = pg_query($sql);
			$info = pg_fetch_array($result, null, PGSQL_ASSOC);
			$descrip_lista[$i] = $info['descrip'];	
			$monto_lista[$i]   = $info['monto'];
			$unidad_lista[$i]  = trim($info['unidad']);
			$costo_lista[$i]   = $info['costo'];

			pg_free_result($result);
			$cant_lista[$i] = $cant1;
			$perime[$i] = $perime1;
			if ($monto_lista[$i] == 0) {
				if ($unidad_lista[$i] == "m") {
					$monto_lista_total[$i] = number_format($costo_lista[$i] * $cant1 * $perime1,1,'.','');	
					$superf[$i] = $perime1;					
				} 
				if ($unidad_lista[$i] == "m2") {
					$monto_lista_total[$i] = number_format($costo_lista[$i] * $cant1 * $superf1,1,'.','');	
					$superf[$i] = $superf1;	
				}
				if ($unidad_lista[$i] == "pto") {
					$monto_lista_total[$i] = number_format($costo_lista[$i] * $cant1 * $punto1,1,'.','');	
					$superf[$i] = $punto1;
				}		
				$monto_total = $monto_total + $monto_lista_total[$i];		
			} else {
				$monto_lista_total[$i] = $monto_lista[$i] * $cant1;
				$monto_total = $monto_total + $monto_lista_total[$i];
			}
			$i++;
		}

		if ($item2 != "") {
			$sql = "SELECT descrip, monto, unidad, costo FROM tasas WHERE id_tasa = '$item2'";
			$result = pg_query($sql);
			$info = pg_fetch_array($result, null, PGSQL_ASSOC);
			$descrip_lista[$i] = $info['descrip'];	
			$monto_lista[$i]   = $info['monto'];
			$unidad_lista[$i]  = trim($info['unidad']);
			$costo_lista[$i]   = $info['costo'];			
			pg_free_result($result);	
			$cant_lista[$i] = $cant2;			
			$superf[$i] = $superf2;	
			$perime[$i] = $perime2;			
			if ($monto_lista[$i] == 0) {
				if ($unidad_lista[$i] == "m") {
					$monto_lista_total[$i] = number_format($costo_lista[$i] * $cant2 * $perime2,1,'.','');	
					$superf[$i] = $perime2;					
				} 
				if ($unidad_lista[$i] == "m2") {
					$monto_lista_total[$i] = number_format($costo_lista[$i] * $cant2 * $superf2,1,'.','');	
					$superf[$i] = $superf2;	
				}
				if ($unidad_lista[$i] == "pto") {
					$monto_lista_total[$i] = number_format($costo_lista[$i] * $cant2 * $punto2,1,'.','');	
					$superf[$i] = $punto2;
				}	
				$monto_total = $monto_total + $monto_lista_total[$i];			
			} else {
				$monto_lista_total[$i] = $monto_lista[$i] * $cant2;
				$monto_total = $monto_total + $monto_lista_total[$i];
			}					
			$i++;
		}	

		if ($item3 != "") {
			$sql = "SELECT descrip, monto, unidad, costo FROM tasas WHERE id_tasa = '$item3'";
			$result = pg_query($sql);
			$info = pg_fetch_array($result, null, PGSQL_ASSOC);
			$descrip_lista[$i] = $info['descrip'];	
			$monto_lista[$i]   = $info['monto'];
			$unidad_lista[$i]  = trim($info['unidad']);
			$costo_lista[$i]   = $info['costo'];			
			pg_free_result($result);	
			$cant_lista[$i] = $cant3;
			$superf[$i] = $superf3;
			$perime[$i] = $perime3;
			if ($monto_lista[$i] == 0) {
				if ($unidad_lista[$i] == "m") {
					$monto_lista_total[$i] = number_format($costo_lista[$i] * $cant3 * $perime3,1,'.','');	
					$superf[$i] = $perime3;					
				} 
				if ($unidad_lista[$i] == "m2") {
					$monto_lista_total[$i] = number_format($costo_lista[$i] * $cant3 * $superf3,1,'.','');	
					$superf[$i] = $superf3;	
				}
				if ($unidad_lista[$i] == "pto") {
					$monto_lista_total[$i] = number_format($costo_lista[$i] * $cant3 * $punto3,1,'.','');	
					$superf[$i] = $punto3;
				}	
				$monto_total = $monto_total + $monto_lista_total[$i];

			} else {
				$monto_lista_total[$i] = $monto_lista[$i] * $cant3;
				$monto_total = $monto_total + $monto_lista_total[$i];
			}					
			$i++;					
		}
		if ($item4 != "") {
			$sql = "SELECT descrip, monto, unidad, costo FROM tasas WHERE id_tasa = '$item4'";
			$result = pg_query($sql);
			$info = pg_fetch_array($result, null, PGSQL_ASSOC);
			$descrip_lista[$i] = $info['descrip'];	
			$monto_lista[$i]   = $info['monto'];
			$unidad_lista[$i]  = trim($info['unidad']);
			$costo_lista[$i]   = $info['costo'];			
			pg_free_result($result);	
			$cant_lista[$i] = $cant4;
			$superf[$i] = $superf4;
			$perime[$i] = $perime4;
			if ($monto_lista[$i] == 0) {
				if ($unidad_lista[$i] == "m") {
					$monto_lista_total[$i] = number_format($costo_lista[$i] * $cant4 * $perime4,1,'.','');	
					$superf[$i] = $perime4;					
				} 
				if ($unidad_lista[$i] == "m2") {
					$monto_lista_total[$i] = number_format($costo_lista[$i] * $cant4 * $superf4,1,'.','');	
					$superf[$i] = $superf3;	
				}
				if ($unidad_lista[$i] == "pto") {
					$monto_lista_total[$i] = number_format($costo_lista[$i] * $cant4 * $punto4,1,'.','');	
					$superf[$i] = $punto4;
				}	
				$monto_total = $monto_total + $monto_lista_total[$i];		
			} else {
				$monto_lista_total[$i] = $monto_lista[$i] * $cant4;
				$monto_total = $monto_total + $monto_lista_total[$i];
			}					
			$i++;
		}
		if ($item5 != "") {
			$sql = "SELECT descrip, monto, unidad, costo FROM tasas WHERE id_tasa = '$item5'";
			$result = pg_query($sql);
			$info = pg_fetch_array($result, null, PGSQL_ASSOC);
			$descrip_lista[$i] = $info['descrip'];	
			$monto_lista[$i]   = $info['monto'];
			$unidad_lista[$i]  = trim($info['unidad']);
			$costo_lista[$i]   = $info['costo'];			
			pg_free_result($result);	
			$cant_lista[$i] = $cant5;
			$superf[$i] = $superf5;
			$perime[$i] = $perime5;
			if ($monto_lista[$i] == 0) {
				if ($unidad_lista[$i] == "m") {
					$monto_lista_total[$i] = number_format($costo_lista[$i] * $cant5 * $perime5,1,'.','');	
					$superf[$i] = $perime5;					
				} 
				if ($unidad_lista[$i] == "m2") {
					$monto_lista_total[$i] = number_format($costo_lista[$i] * $cant5 * $superf5,1,'.','');	
					$superf[$i] = $superf3;	
				}
				if ($unidad_lista[$i] == "pto") {
					$monto_lista_total[$i] = number_format($costo_lista[$i] * $cant5 * $punto5,1,'.','');	
					$superf[$i] = $punto5;
				}	
				$monto_total = $monto_total + $monto_lista_total[$i];				
			} else {
				$monto_lista_total[$i] = $monto_lista[$i] * $cant5;
				$monto_total = $monto_total + $monto_lista_total[$i];
			}					
			$i++;
		}

		$cant_de_items = $i;			
		
		
	}
} else {

		$item1 = $item2 = $item3 = $item4 = $item5 = "default";
		$cant1 = $cant2 = $cant3 = $cant4 = $cant5 = "1";
		$detalle = $nombre = $observacion = "";			

}
$monto_total = number_format($monto_total,0,'.','');

if ((isset($_POST["busqueda1"])) AND (($_POST["busqueda1"]) == "Buscar")) {
    $cod_uv   = trim($_POST["cod_uv"]); 
    $cod_man  = trim($_POST["cod_man"]);
    $cod_pred = trim($_POST["cod_pred"]);
    $cod_blq  = trim($_POST["cod_blq"]);	
    $cod_piso = trim($_POST["cod_piso"]);
    $cod_apto = trim($_POST["cod_apto"]);

    ################################################################################
    #----------------------------- CHEQUEAR CODIGO --------------------------------#
    ################################################################################		
    if ((!check_int($cod_uv)) OR (!check_int($cod_man)) OR (!check_int($cod_pred))) {
        $error = true;
    } elseif (($cod_uv > 0) AND ($cod_man > 0) AND ($cod_pred > 0)  AND ($cod_blq == "") AND ($cod_piso == "") AND ($cod_apto == "")) {
     $cod_blq = $cod_piso = $cod_apto = 0;
    }
    
    ################################################################################
    #--------------------------- CHEQUEAR SI EXISTE -------------------------------#
    ################################################################################	
    if (!$error) {
        $sql = "SELECT id_inmu, cod_uv, cod_man, cod_pred, cod_blq, cod_piso, cod_apto, tit_1id, tit_2id, tit_3id   
                FROM info_inmu 
                WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND cod_blq = '$cod_blq' AND cod_piso = '$cod_piso' AND cod_apto = '$cod_apto'";
        $check = pg_num_rows(pg_query($sql)); 
		
        if ($check == 1) {
			
			$result = pg_query($sql);
            $datos = pg_fetch_array($result, null, PGSQL_ASSOC);
            $tit_1id = $datos['tit_1id'];
            $tit_2id = $datos['tit_2id'];
            $tit_3id = $datos['tit_3id'];
            $cod_cat = get_codcat ($cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto); 
            $nombre = trim(get_contrib_nombre($tit_1id));
			
            $observacion = "";

            if ($tit_2id != 0) {
                $observacion = "OTROS PROPIETARIOS: ".trim(get_contrib_nombre2($tit_2id)).", ".trim(get_contrib_nombre2($tit_3id));
            }
			
            pg_free_result($result);	
            $exist = true;
            $mod = 5;
            $id_inmu = get_id_inmu ($cod_geo, $cod_uv, $cod_man, $cod_pred, $cod_blq, $cod_piso, $cod_apto);	 
			########################################
			#---------- CHEQUEAR TABLA ------------#
			########################################
			$check_integrity = pg_num_rows(pg_query($sql)); 
			###############################################
			#---------- SUPERFICIE DEL PREDIO ------------#
			###############################################
			$superf = superf_predio($cod_geo,$cod_uv,$cod_man,$cod_pred);		
			$superf1= $superf;
			$superf2= $superf;
			$superf3= $superf;
			$superf4= $superf;
			$superf5= $superf;			
			$perime  = perimetro_predio($cod_geo,$cod_uv,$cod_man,$cod_pred);
			$perime1 = $perime;
			$perime2 = $perime;
			$perime3 = $perime;
			$perime4 = $perime;
			$perime5 = $perime;
			$punto  = nu_puntos_predio($cod_geo,$cod_uv,$cod_man,$cod_pred);
			$punto1 = $punto;
			$punto2 = $punto;
			$punto3 = $punto;
			$punto4 = $punto;
			$punto5 = $punto;

			$detalle = "SERVICIOS CORRESPONDE AL PREDIO: ".$cod_cat." SUPERFICIE DE ".$superf." m2, UN PERIMETRO DE ".$perime." m";
        } else	{
            $exist = false;
            $mod = 1;
        }
    } else {
        $exist = false;
        $mod = 1;
    }

}


################################################################################
#------------------------------- FORMULARIO -----------------------------------#
################################################################################	 
if (!$formulario) {
	echo "<td>\n";
	echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";	 
		echo "<tr height=\"40px\">\n";
			echo "<td width=\"5%\"> &nbsp</td>\n";   #Col. 1 	    
			echo "<td align=\"center\" valign=\"center\" height=\"40\" width=\"90%\" class=\"pageName\">\n"; 
			echo "Generar Formulario Unico de Caja\n";                          
			echo "</td>\n";
			echo "<td width=\"10%\"> &nbsp</td>\n";   #Col. 3 			 
		echo "</tr>\n";
		echo "<tr height=\"5px\">\n";
		echo "<td colspan=\"3\"> &nbsp</td>\n";   #Col. 1-3
		echo "</tr>\n";	 	

		##################################################
		#------------ NOMBRE O RAZON SOCIAL -------------#
		##################################################
		echo "<tr>\n"; 	
		echo "<td> &nbsp</td>\n";   #Col. 1 	  
		echo "<td valign=\"top\" height=\"40\">\n";   #Col. 2
		echo "<fieldset><legend>Codigo del predio</legend>\n";
		echo "<form name=\"form1\" method=\"post\" action=\"index.php?mod=75&id=$session_id\" accept-charset=\"utf-8\">\n";		
		echo "<table border=\"0\" width=\"100%\" bordercolor=\"#fe2d04\" >\n";  
			echo "<tr>\n";
				echo "<td align=\"left\" colspan=\"1\" width=\"2%\"> &nbsp</td>\n"; #TCol. 1
				echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">$uv_dist</td>\n";
				echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Mz.</td>\n";
				echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Pred.</td>\n";
				echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Blq.</td>\n";
				echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Piso</td>\n";
				echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">Apto.</td>\n";	
				echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">&nbsp</td>\n";										    
			echo "</tr>\n";	  
			echo "<tr>\n";
				echo "<td width=\"2%\" align=\"left\" colspan=\"1\"> &nbsp</td>\n";

				echo "<td width=\"6%\" align=\"left\" class=\"bodyTextD\">\n";
					echo "<input name=\"cod_uv\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_uv\" value=\"$cod_uv\">\n";
				echo "</td>\n";
				echo "<td width=\"2%\"> &nbsp</td>\n";

				echo "<td  width=\"6%\" align=\"left\"class=\"bodyTextD\">\n";
					echo "<input name=\"cod_man\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_man\" value=\"$cod_man\">\n";
				echo "</td>\n";	
				echo "<td width=\"2%\"> &nbsp</td>\n";

				echo "<td width=\"6%\" align=\"left\" class=\"bodyTextD\">\n";
					echo "<input name=\"cod_pred\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_pred\" value=\"$cod_pred\">\n";
				echo "</td>\n";
				echo "<td width=\"2%\"> &nbsp</td>\n";
						
				echo "<td width=\"6%\" align=\"left\" class=\"bodyTextD\">\n";
					echo "<input name=\"cod_blq\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_blq\" value=\"$cod_blq\">\n";
				echo "</td>\n";	
				echo "<td width=\"2%\"> &nbsp</td>\n";
					
				echo "<td width=\"6%\" align=\"left\" class=\"bodyTextD\">\n";
					echo "<input name=\"cod_piso\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_piso\" value=\"$cod_piso\">\n";
				echo "</td>\n";	
				echo "<td width=\"2%\"> &nbsp</td>\n";
					
				echo "<td width=\"6%\" align=\"left\" class=\"bodyTextD\">\n";
					echo "<input name=\"cod_apto\" type=\"text\" class=\"navText\" maxlength=\"$max_strlen_apto\" value=\"$cod_apto\">\n";
				echo "</td>\n";					
				echo "<td width=\"2%\"> &nbsp</td>\n";
						
				echo "<td width=\"50%\">\n";
					echo "<input name=\"old_example\" type=\"hidden\" class=\"smallText\" value=\"$example\">\n";
					echo "<input name=\"old_stage2\" type=\"hidden\" class=\"smallText\" value=\"$stage2\">\n";	 				
					echo "<input name=\"busqueda1\" type=\"submit\" class=\"smallText\" value=\"Buscar\" onClick=\"go()\">\n";			
				echo "</td>\n"; #TCol. 1
			echo "</tr>\n";	  			 

		echo "</table>\n"; 
		echo "</form>\n";

		
		echo "</fieldset>\n";	 	 
		echo "</td>\n"; 
		echo "<td> &nbsp</td>\n";    
		echo "</tr>\n";

		if ($error1) {
			echo "<tr>\n"; 
			echo "<td> &nbsp</td>\n";  			 
			echo "<td align=\"center\" height=\"20\">\n"; 	 			 
			echo "<font color=\"red\">$mensaje_de_error1</font> <br />\n";				 	    
			echo "</td>\n"; 
			echo "<td> &nbsp</td>\n"; 		
			echo "</tr>\n";
		} 
    ##################################################
    #------------------- DETALLE --------------------#
    ##################################################
    echo "<tr>\n"; 	
		echo "<td> &nbsp</td>\n";
		echo "<td valign=\"top\" height=\"40\">\n"; 
			echo "<fieldset><legend>Nombre o Razon Social</legend>\n";
			echo "<form name=\"form1\" method=\"post\" action=\"index.php?mod=75&id=$session_id\" accept-charset=\"utf-8\">\n";	
				echo "<table border=\"0\" width=\"100%\">\n"; 
					echo "<tr>\n";
					echo "<td align=\"right\" class=\"bodyText\"></td>\n";	 
					echo "</tr>\n";	   
                    echo "<tr>\n";  	                     
                        echo "<td width=\"1%\"></td>\n";   	 
                        echo "<td align=\"center\" width=\"13%\" class=\"bodyTextH\">NOMBRE:</td>\n"; 
                        echo "<td align=\"center\" width=\"85%\" class=\"bodyTextD\"><input type=\"text\" name=\"nombre\" id=\"form_anadir1\" class=\"navText\" value=\"$nombre\"></td>\n";	 
                        echo "<td width=\"1%\"></td>\n";    	 	    
                    echo "</tr>\n";                    
					echo "<tr>\n";  	                     
						echo "<td width=\"1%\"></td>\n"; 	  	 
						echo "<td align=\"center\" width=\"13%\" class=\"bodyTextH\">DETALLE:</td>\n";   #Col. 7  
						echo "<td align=\"center\" width=\"85%\" class=\"bodyTextD\"><input type=\"text\" name=\"detalle\" id=\"form_anadir1\" class=\"navText\" value=\"$detalle\"></td>\n";   #Col. 8  	 
						echo "<td width=\"1%\"></td>\n"; 	 	 	    
					echo "</tr>\n";
					echo "<tr>\n";  	                     
						echo "<td width=\"1%\"></td>\n"; 	  	 
						echo "<td align=\"center\" width=\"13%\" class=\"bodyTextH\">OBSERVACION:</td>\n";   #Col. 7  
                        	
						echo "<td align=\"center\" width=\"85%\" class=\"bodyTextD\"><input type=\"text\" name=\"observacion\" id=\"form_anadir1\" class=\"navText\" value=\"$observacion\"></td>\n";   #Col. 8  	 
						echo "<td width=\"1%\"></td>\n"; 	 	 	    
					echo "</tr>\n";					
				echo "</table>\n"; 
			echo "</fieldset>\n";	 	 
		echo "</td>\n"; 
		echo "<td> &nbsp</td>\n"; 
	 echo "</tr>\n";

	if ($error2) {
		echo "<tr>\n"; 
		echo "<td> &nbsp</td>\n";   #Col. 1				 
		echo "<td align=\"center\" height=\"20\">\n";   #Col. 2  	 			 
		echo "<font color=\"red\">$mensaje_de_error2</font> <br />\n";				 	    
		echo "</td>\n"; 
		echo "<td> &nbsp</td>\n";   #Col. 3 			
		echo "</tr>\n";
	} 	 
	##################################################
	#--------------- BOTON CALCULAR -----------------#
	##################################################
	echo "<tr>\n"; 	
	echo "<td> &nbsp</td>\n";   #Col. 1   
	echo "<td align=\"center\" height=\"40\">\n";   #Col. 1+2+3  
	echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Calcular\">\n";
	echo "</td>\n"; 
	echo "<td> &nbsp</td>\n";   #Col. 3 	 
	echo "</tr>\n";	  
	##################################################
	#-------------------- MONTO ---------------------#
	##################################################
	if ($calcular) {
	    echo "<tr>\n"; 	
	    echo "<td> &nbsp</td>\n"; 
	    echo "<td valign=\"top\" height=\"40\">\n"; 
	    echo "<table border=\"1\" width=\"100%\" style=\"border-collapse:collapse;\">\n";
	    echo "<tr>\n";  	                     	  	 
			echo "<td align=\"center\" width=\"60%\" class=\"bodyTextD\">Descripción_1</td>\n";
			echo "<td align=\"center\" width=\"5%\" class=\"bodyTextD\">Unidad</td>\n";
			echo "<td align=\"center\" width=\"10%\" class=\"bodyTextD\">Pre.Uni</td>\n";
			echo "<td align=\"center\" width=\"5%\" class=\"bodyTextD\">Cant.</td>\n";
			echo "<td align=\"center\" width=\"10%\" class=\"bodyTextD\">Sup./Dist.</td>\n";			
			echo "<td align=\"center\" width=\"10%\" class=\"bodyTextD\">Total</td>\n";			 	 	 	 	    
	    echo "</tr>\n";

	    echo "<tr>\n";  	                     
			echo "<td align=\"left\" class=\"bodyTextD\">&nbsp \n"; 
			$i = 0;
			while ($i < $cant_de_items) {
				echo "&nbsp $descrip_lista[$i]<br />\n";			
				$i++;
			}
			echo "</td>\n";

			echo "<td align=\"right\" class=\"bodyTextD\">&nbsp \n";
			$i = 0;
			while ($i < $cant_de_items) {
				echo "&nbsp $unidad_lista[$i] &nbsp<br />\n";			
				$i++;
			}
			echo "</td>\n";			

			echo "<td align=\"right\" class=\"bodyTextD\">&nbsp \n";
			$i = 0;
			while ($i < $cant_de_items) {
				echo "&nbsp $costo_lista[$i] &nbsp<br />\n";			
				$i++;
			}
			echo "</td>\n";		

			echo "<td align=\"right\" class=\"bodyTextD\">&nbsp \n";
			$i = 0;
			while ($i < $cant_de_items) {
				echo "&nbsp $cant_lista[$i] &nbsp<br />\n";			
				$i++;
			}
			echo "</td>\n";			

			echo "<td align=\"right\" class=\"bodyTextD\">&nbsp \n";
			$i = 0;
			while ($i < $cant_de_items) {
				echo "&nbsp $superf[$i] &nbsp<br />\n";			
				$i++;
			}
			echo "</td>\n";	

			echo "<td align=\"right\" class=\"bodyTextD\">&nbsp \n";		 	 
			$i = 0;
			while ($i < $cant_de_items) {
				echo "&nbsp $monto_lista_total[$i] &nbsp<br />\n";			
				$i++;
			}
			echo "</td>\n"; 	

	    echo "</tr>\n";
	    echo "<tr>\n";  	                       	 
	    echo "<td align=\"left\" colspan=\"5\" class=\"bodyTextD\">&nbsp </td>\n";
	    echo "<td align=\"right\" class=\"bodyTextD\"><b>$monto_total &nbsp</b> </td>\n";	 	  	 	 	    
	    echo "</tr>\n";						
	    echo "</table>\n"; 
	    echo "</td>\n"; 
	    echo "<td> &nbsp</td>\n";   #Col. 3 	 
	    echo "</tr>\n";
 
	    echo "      <tr>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 1 			 	 
	    echo "         <td align=\"center\" height=\"40\">\n";   #Col. 2 			
	    echo "            <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Formulario\">\n";	
	    echo "         </td>\n";													
	    echo "         <td> &nbsp</td>\n";   
	    echo "      </tr>\n"; 
	}else{
		
	}  	
	
	include "form_caja_items.php";
	echo "</form>\n";	
	echo "<tr height=\"100%\"></tr>\n";			 
	echo "</table>\n";
	echo "</td>\n";	  
} else { 
	echo "<td>\n";
		echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"770px\">\n";   # 3 Columnas
			echo "<tr height=\"40px\">\n";
				echo "<td align=\"left\">\n";  #Col. 1 
				echo "&nbsp&nbsp <a href='javascript:history.back()'>\n";	
				echo "<img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";	
				echo "</td>\n";  
			echo "</tr>\n";	 

			$i = 0;

			echo "<tr>\n";
				echo "<td valign=\"top\">\n";   #Col. 1 
				include "form_caja_generar.php";
				echo "<iframe frameborder=\"0\" name=\"mapserver\" src=\"http://$server/tmp/fc$numero.html\" id=\"content\" width=\"750px\" height=\"1270px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
				echo "</iframe>\n";	
				echo "</td>\n";	 
			echo "</tr>\n";	 		
		echo "</table>\n";
	echo "</td>\n";
}
?>