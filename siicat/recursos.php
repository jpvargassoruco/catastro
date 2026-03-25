<?php

$anadir = $modificar = $borrar = $error = false;

################################################################################
#----------------- AÑADIR ITEM DETERMINAMOS EL ULTIMO -------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Añadir")) {
	$anadir = true;
	$accion = "Añadir";
	$show_button = false;

    $id_recurso_select = ""; 
    $detalle_select = ""; 
    $tipo_select  = ""; 
    $auxiliar_select = ""; 
    $costo_select = ""; 
    $porcentaje_select = "";    
}
################################################################################
#-------------------------------- BOTON MODIFICAR -----------------------------#
################################################################################	
elseif ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Modificar")) {
	$id_recurso_select = $_POST["id_recurso"];
	$modificar = true;
	$accion = "Modificar";
	$show_button = false;
	$sql="SELECT detalle, tipo, auxiliar, costo, porcentaje FROM recursos WHERE id_recurso='$id_recurso_select'";
    echo "$sql";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$detalle_select = $info['detalle'];	
	$tipo_select  = $info['tipo'];
	$auxiliar_select = $info["auxiliar"];
	$costo_select  = $info["costo"];
	$porcentaje_select   = $info["porcentaje"];	
	pg_free_result($result);	 
}
################################################################################
#--------------------------------- BOTON BORRAR -------------------------------#
################################################################################	
elseif ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Borrar")) {
	$id_recurso_select = $_POST["id_recurso"];
	$borrar = true;
	$accion = "Borrar";
	$show_button = false; 
}
################################################################################
#--------------------------------- RADIO-BUTTON -------------------------------#
################################################################################	 	 
elseif (($nivel == 4) OR ($nivel == 5)) {
	$show_button = true;
} else $show_button = false;
################################################################################
#------------------------------- AÑADIR SUB-NIVEL -----------------------------#
################################################################################	
if ((isset($_POST["accion"])) AND (($_POST["accion"] == "Añadir") OR ($_POST["accion"] == "Modificar"))) {
	 $recurso_select = trim($_POST["subnivel"]);
	 $detalle_select = trim($_POST["detalle"]);	 
	 $tipo_select = trim($_POST["tipo"]);
	 $auxiliar_select = trim($_POST["auxiliar"]);
	 $costo_select  = $_POST["costo"];
	 $porcentaje_select  = trim($_POST["porcentaje"]);

	 $check_strlen = strlen ($subnivel_select);	 
	 $no_rubro_select = substr ($subnivel_select,0,5);
	 $id_recurso_select = (int) substr ($subnivel_select,6,3);
	 $sql="SELECT id_recurso FROM recursos WHERE id_recurso = '$id_recurso_select'";
	 pg_query($sql);
	 $check_id_recurso = pg_num_rows(pg_query($sql));

	### CHEQUEAR LOS NUEVOS DATOS	 
	if ($recurso_select == "") {
		$error = true;
		$mensaje_de_error = "Error: Tiene que ingresar un código válido para el Sub-Nivel!";
	} elseif ($detalle_select == "") {
		$error = true;
		$mensaje_de_error = "Error: Tiene que ingresar una DESCRIPCION del nuevo Sub-Nivel!";
	} elseif ($tipo_select == "") {
		$error = true;
		$mensaje_de_error = "Error: Tiene que ingresar un MONTO para el nuevo Sub-Nivel!";
	} elseif ($auxiliar_select == "") {
		$error = true;
		$mensaje_de_error = "Error: Tiene que ingresar la UNIDAD!";
	} elseif ($costo_select == "") {
		$error = true;
		$mensaje_de_error = "Error: Tiene que ingresar la COSTO por UNIDAD!";		
	} elseif ($porcentaje_select  == "") {
		$error = true;
		$mensaje_de_error = "Error: Tiene que ingresar el tipo igual a APROBACION o REVISION!";					
	} elseif ($check_strlen != 5) {
		$error = true;
		$mensaje_de_error = "Error: El formato del código no es correcto. Tiene que usar 5 digitos para el rubro y 3 digitos para el Sub-Nivel, separados por un punto!";			
	} elseif ($no_rubro_select != $no_rubro) {
		$error = true;
		$mensaje_de_error = "Error: El código del rubro no es válido (debe empezar con $no_rubro)!";	
	} elseif ($id_recurso_select == "") {
		$error = true;
		$mensaje_de_error = "Error: El código del Sub-Nivel no es válido (debe ser un número mayor a 0)!";			
	} elseif (($check_id_recurso > 0) AND ($_POST["accion"] == "Añadir")) {
		$error = true;
		$mensaje_de_error = "Error: Ya existe un Sub-Nivel con el código '$id_recurso_select' en la base de datos!";									
	} elseif (!check_float($monto_select)) {
		$error = true;
		$mensaje_de_error = "Error: Tiene que ingresar un monto para el nuevo Sub-Nivel!";									
	}


	if (!$error) {
		if ($_POST["accion"] == "Añadir") {
			$accion_reg = utf8_encode("Sub-Nivel añadido");
			$sql = "INSERT INTO recursos (id_recurso, descrip, monto, unidad, costo, id_tra, tipo, rubro ) 
		            VALUES ('$id_recurso_select','$descrip_select','$monto_select','$unidad_select','$costo_select','$id_tra','$tipo_select','$no_rubro')";
			pg_query($sql);
		} else {
			$accion_reg = "Sub-Nivel modificado";
			$sql = "UPDATE recursos SET descrip = '$descrip_select', monto = '$monto_select', unidad = '$unidad_select', costo = '$costo_select', tipo = '$tipo_select'  
					WHERE id_recurso = '$id_recurso_select'";
			pg_query($sql);				 
		}
		### REGISTRO
		$username = get_username($session_id);
		$valor = "-";
		pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
				VALUES ('$username','$ip','$fecha','$hora','$accion_reg','$valor')");
	 } else {
	    $anadir = true;
	    $accion = "Añadir";
	    $show_button = false; 
	 }
}
################################################################################
#---------------------------- BORRAR SUB-NIVEL --------------------------------#
################################################################################	
if ((isset($_POST["accion"])) AND (($_POST["accion"]) == "Borrar")) {
	 $id_recurso_select = $_POST["id_recurso"];
   pg_query("DELETE FROM recursos WHERE id_recurso = '$id_recurso_select'");
	 ### REGISTRO
	 $username = get_username($session_id);
	 $accion_reg = "Sub-Nivel borrado";
	 $valor = "-";
	 pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		         VALUES ('$username','$ip','$fecha','$hora','$accion_reg','$valor')");	

}
################################################################################
#------------------------------- LEER TABLA  ----------------------------------#
################################################################################	
	 
$sql="SELECT id_recurso, detalle, tipo, auxiliar, costo, porcentaje FROM recursos ORDER BY id_recurso";
$no_de_subniveles = pg_num_rows(pg_query($sql));
$result = pg_query($sql);
$i = $j = 0;
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	foreach ($line as $col_value) {
		if ($i == 0) { 
			$id_recurso_lista[$j] = $col_value;
		} elseif ($i == 1) { 
			$detalle_lista[$j] = $col_value; 	 
		} elseif ($i == 2) {
			$tipo_lista[$j] = $col_value;
		} elseif ($i == 3) {
			$auxiliar_lista[$j] = $col_value;
		} elseif ($i == 4) {
			$costo_lista[$j] = $col_value;						
		} else { 
			$porcentaje_lista[$j] = $col_value;
			$i = -1;		
		}
		$i++;
	}
	$j++;
} 			
pg_free_result($result);	
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	
	
echo "<td>\n";
echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";
    echo "<tr height=\"40px\">\n";
        echo "<td width=\"5%\"> &nbsp</td>\n";     
        echo "<td align=\"center\" valign=\"center\" height=\"40\" width=\"70%\" class=\"pageName\">\n"; 
        echo "recursos Administrativas\n";                          
        echo "</td>\n";
        echo "<td width=\"20%\"> &nbsp</td>\n"; 
    echo "</tr>\n";	
    echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=78&id=$session_id\" accept-charset=\"utf-8\">\n";
        echo "<tr height=\"40\">\n";
            echo "<td> &nbsp</td>\n"; 
            echo "<td align=\"left\" class=\"bodyTextD\"> LISTADO  $no_rubro $nom_rubro \n"; 
            echo "</td>\n";	
            echo "<td> &nbsp</td>\n"; 	 
        echo "</tr>\n"; 	 	
        echo "<tr>\n";	
        echo "<td> &nbsp</td>\n";
        echo "<td align=\"center\">\n";	   
        echo "<table id=\"registros\">\n";
            echo "<tr>\n";  
            echo "<th> &nbsp&nbsp&nbsp&nbsp&nbsp </th>\n";	 
            echo "<th>id</th>\n";
            echo "<th>descripción</th>\n";
            echo "<th>Tipo</th>\n";	 	 
            echo "<th>Auxiliar</th>\n";
            echo "<th>Costo</th>\n";
            echo "<th>Porcentaje</th>\n";
            echo "</tr>\n";
			if ($no_de_subniveles > 0) {
			$i = $k = 0;
			$show_color = false;
			while ($i < $no_de_subniveles) {
				if (!$show_color){
					echo "<tr>\n";
					$show_color = true;
				} else {
					echo "<tr class=\"alt\">\n";	
					$show_color = false;		 
				}	 
				if ($show_button) {
					if ($k == 0){
						echo "<td><input name=\"id_recurso\" value=\"$id_recurso_lista[$i]\" type=\"radio\" checked=\"checked\"></td>\n";
						$k++;
					} else {
						echo "<td><input name=\"id_recurso\" value=\"$id_recurso_lista[$i]\" type=\"radio\"></td>\n";					 
					}	 
				} else {
				if ($id_recurso_select == $id_recurso_lista[$i]) {
					echo "<td><font color=\"red\"><b> > &nbsp </b></font></td>\n";
				} else {
					echo "<td> &nbsp&nbsp&nbsp&nbsp&nbsp </td>\n";
				} 
				}
				echo "<td>$id_recurso_lista[$i]</td>\n";
				echo "<td class=\"alt\">$detalle_lista[$i]</td>\n";
				echo "<td>$tipo_lista[$i] &nbsp</td>\n";	
				echo "<td>$auxiliar_lista[$i] &nbsp</td>\n";
				echo "<td>$costo_lista[$i] &nbsp</td>\n";
				echo "<td>$porcentaje_lista[$i] &nbsp</td>\n";		 			  
				echo "</tr>\n";
				$i++;
			}
	 } else {
 	    echo "<tr>\n"; 
 	    echo "<td align=\"center\" colspan=\"4\"> No hay registros en la base de datos!\n"; 
	    echo "</td>\n";	
	    echo "</tr>\n";		 
	 }	 
	echo "</table>\n";
	echo "</td>\n";		 
	echo "<td> &nbsp</td>\n";   #Col. 1  
	echo "</tr>\n";		 
	echo "<tr height=\"10px\">\n";
	echo "<td colspan=\"3\"> &nbsp</td>\n";   #Col. 1 	    		 
	echo "</tr>\n";	 
	if (($nivel == 4) OR ($nivel == 5)) {
	    if ((!$anadir) AND (!$modificar) AND (!$borrar)) {
			# Fila 5						 
			echo "<tr height=\"30px\">\n"; 
			echo "<td> &nbsp</td>\n";   #Col. 1  
			echo "<td align=\"center\">\n";   #Col. 2				   	
			echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Añadir\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";
			echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Modificar\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";				 
			echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Borrar\">\n";				 
			echo "</td>\n";
			echo "<td> &nbsp</td>\n";   #Col. 3				 	   	 
			echo "</tr>\n";					 
			} else {
				if (($anadir) OR ($modificar)) {
				echo "<tr height=\"30px\">\n";			
				echo "<td> &nbsp</td>\n"; 	
				echo "<td>\n";
				echo "<table border=\"0\" width=\"100%\">\n";
                    echo "<tr>\n";
                        echo "<td width=\"100%\" align=\"center\" colspan=\"9\" class=\"bodyTextD_Small\"> $accion Sub-Nivel</td>\n";
                    echo "</tr>\n";				  
                    echo "<tr>\n";
                        echo "<td width=\"5%\"  colspan=\"1\"> &nbsp </td>\n";			
                        echo "<td width=\"15%\" colspan=\"2\"> id_recurso: </td>\n";
                        echo "<td width=\"80%\" colspan=\"6\"> Nombre:</td>\n";		 				 
                    echo "</tr>\n";

                    echo "<tr>\n";
                        echo "<td width=\"5%\" colspan=\"1\"> &nbsp </td>\n";					
                        echo "<td width=\"15%\" colspan=\"2\">\n";		
                            echo "<input type=\"text\" name=\"id_recurso\" id=\"form_anadir0\" class=\"navText\" value=\"$id_recurso_select\">\n";
                        echo "</td>\n";
                        echo "<td  width=\"80%\" colspan=\"6\">\n";			 
                            echo "<input type=\"text\" name=\"detalle\" id=\"form_anadir0\" class=\"navText\" value=\"$detalle_select\">\n";					 
                        echo "</td>\n";	 				 
                    echo "</tr>\n";	

                    echo "<tr>\n";
                        echo "<td width=\"5%\" colspan=\"1\"> &nbsp </td>\n";		
                        echo "<td width=\"5%\" colspan=\"1\"> Tipo: </td>\n";				
                        echo "<td width=\"10%\" colspan=\"1\">\n";	
                            echo "<input type=\"text\" name=\"tipo\" id=\"form_anadir0\" class=\"navText\" value=\"$tipo_select\">\n";
                        echo "</td>\n";

                        echo "<td width=\"10%\" colspan=\"1\">Auxiliar:</td>\n"; 
                        echo "<td width=\"15%\" colspan=\"1\">\n"; 			 
                            echo "<input type=\"text\" name=\"auxiliar\" id=\"form_anadir0\" class=\"navText\" value=\"$auxiliar_select\">\n";					 
                        echo "</td>\n";
                        
                        echo "<td>Costo:</td>\n"; 
                        echo "<td width=\"15%\" colspan=\"1\">\n";		 
                            echo "<input type=\"text\" name=\"costo\" id=\"form_anadir0\" class=\"navText\" value=\"$costo_select\">\n";					 
                        echo "</td>\n";
                        
                        echo "<td>Porcentaje:</td>\n";	                        
                        echo "<td width=\"15%\" colspan=\"1\">\n"; 				 
                            echo "<input type=\"text\" name=\"porcentaje\" id=\"form_anadir0\" class=\"navText\" value=\"$porcentaje_select\">\n";					 
                        echo "</td>\n";				 				 
                    echo "</tr>\n";	
                    
                    echo "<tr>\n";				
                        echo "<td width=\"100%\"  colspan=\"9\"> &nbsp </td>\n";   #Col. 4
                    echo "</tr>\n";

                    echo "<tr>\n";
                        echo "<td align=\"center\" colspan=\"9\">\n";  #Col. 1-4		 										   		   		 
                            echo "<input type=\"submit\" name=\"accion\" class=\"smallText\" value=\"$accion\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";
                            echo "<input type=\"submit\" name=\"accion\" class=\"smallText\" value=\"No $accion\">\n";
                        echo "</td>\n";			 
                    echo "</tr>\n";
				echo "</table>\n";
				echo "</td>\n";


				echo "<td> &nbsp</td>\n";   #Col. 3 					 
				echo "</tr>\n";
				if ($error) {
                    echo "<tr height=\"30px\">\n";			
                    echo "<td> &nbsp</td>\n";   #Col. 1 	
                    echo "<td align=\"center\">\n";  #Col. 2 
                    echo "<font color=\"red\"> $mensaje_de_error</font>\n";
                    echo "</td>\n";
                    echo "<td> &nbsp</td>\n";   #Col. 3 							 
                    echo "</tr>\n";	
			    }
            } else {
				echo "<tr height=\"30px\">\n";			
				echo "<td> &nbsp</td>\n";   #Col. 1 	
				echo "<td align=\"left\">\n";  #Col. 2 	
				echo "<font color=\"red\">Est� segura/o que quiere borrar el Sub-Nivel '$subnivel_select'? </font>&nbsp&nbsp&nbsp\n";  		 
				echo "<input type=\"submit\" name=\"accion\" class=\"smallText\" value=\"Borrar\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";
				echo "<input type=\"submit\" name=\"accion\" class=\"smallText\" value=\"No Borrar\">\n";
				echo "<input type=\"hidden\" name=\"id_recurso\" value=\"$id_recurso_select\">\n";							
				echo "</td>\n";	
				echo "<td> &nbsp</td>\n"; 
				echo "</tr>\n";													 				 
				 }		 							
	    }	
		  echo "</form>\n";		 
	 }
	 echo "<tr height=\"100%\"></tr>\n";			 
	 echo "</table>\n";
	 echo "<br />&nbsp;<br />\n";
	 echo "</td>\n";
?>
