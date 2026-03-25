<?php

#$accion = "base_legal";
$anadir = $modificar = $borrar = $error = false;
$no_rubro = "15000";
$su_rubro = "15100";
$nom_rubro = "TASAS, DERECHO Y OTROS INGRESOS";
$nom_su_rubro = "Tasas";
$id_tra = 3;

################################################################################
#----------------- AÑADIR ITEM DETERMINAMOS EL ULTIMO -------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Añadir")) {
	$anadir = true;
	$accion = "Añadir";
	$show_button = false;
	$descrip_select = $monto_select = "";
	$unidad_select = "m";
	$costo_select = "";
	$tipo_select = "APROBACION"; 
	### GENERAR NUEVO SUB-NIVEL	 
	$sql="SELECT id_tasa FROM tasas ORDER BY id_tasa DESC LIMIT 1";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$id_tasa_ult = $info['id_tasa'];	
	pg_free_result($result);	
	$id_tasa_ult++;
	if ($id_tasa_ult < 10) {	 
		$subnivel_select = $no_rubro.".00".$id_tasa_ult;
	} elseif ($id_tasa_ult < 100) {	 
		$subnivel_select = $no_rubro.".0".$id_tasa_ult;
	} else {
		$subnivel_select = $no_rubro.".".$id_tasa_ult;
	}
	$id_tasa_select = 0;
}
################################################################################
#-------------------------------- BOTON MODIFICAR -----------------------------#
################################################################################	
elseif ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Modificar")) {
	$id_tasa_select = $_POST["id_tasa"];
	$modificar = true;
	$accion = "Modificar";
	$show_button = false;
	$sql="SELECT descrip, monto, unidad, costo, tipo FROM tasas WHERE id_tasa='$id_tasa_select'";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$descrip_select = $info['descrip'];	
	$monto_select  = $info['monto'];
	$unidad_select = $info["unidad"];
	$costo_select  = $info["costo"];
	$tipo_select   = $info["tipo"];	
	pg_free_result($result);	 
	if ($id_tasa_select < 10) {	 
		$subnivel_select = $no_rubro.".00".$id_tasa_select;
	} elseif ($id_tasa_select < 100) {	 
		$subnivel_select = $no_rubro.".0".$id_tasa_select;
	} else {
		$subnivel_select = $no_rubro.".".$id_tasa_select;
	}	 
}
################################################################################
#--------------------------------- BOTON BORRAR -------------------------------#
################################################################################	
elseif ((isset($_POST["submit"])) AND (($_POST["submit"]) == "Borrar")) {
	$id_tasa_select = $_POST["id_tasa"];
	$borrar = true;
	$accion = "Borrar";
	$show_button = false;
	if ($id_tasa_select < 10) {	 
		$subnivel_select = $no_rubro.".00".$id_tasa_select;
	} elseif ($id_tasa_select < 100) {	 
		$subnivel_select = $no_rubro.".0".$id_tasa_select;
	} else {
		$subnivel_select = $no_rubro.".".$id_tasa_select;
	}	 
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
	 $subnivel_select = trim($_POST["subnivel"]);
	 $descrip_select  = trim($_POST["descrip"]);	 
	 $monto_select    = trim($_POST["monto"]);
	 $unidad_select   = trim($_POST["unidad"]);
	 $costo_select    = $_POST["costo"];
	 $tipo_select     = trim($_POST["tipo"]);
	 $check_strlen    = strlen ($subnivel_select);	 
	 $no_rubro_select = substr ($subnivel_select,0,5);
	 $id_tasa_select  = (int) substr ($subnivel_select,6,3);
	 $sql="SELECT id_tasa FROM tasas WHERE id_tasa = '$id_tasa_select'";
	 pg_query($sql);
	 $check_id_tasa = pg_num_rows(pg_query($sql));

	### CHEQUEAR LOS NUEVOS DATOS	 
	if ($subnivel_select == "") {
		$error = true;
		$mensaje_de_error = "Error: Tiene que ingresar un código válido para el Sub-Nivel!";
	} elseif ($descrip_select == "") {
		$error = true;
		$mensaje_de_error = "Error: Tiene que ingresar una DESCRIPCION del nuevo Sub-Nivel!";
	} elseif ($monto_select == "") {
		$error = true;
		$mensaje_de_error = "Error: Tiene que ingresar un MONTO para el nuevo Sub-Nivel!";
	} elseif ($unidad_select == "") {
		$error = true;
		$mensaje_de_error = "Error: Tiene que ingresar la UNIDAD!";
	} elseif ($costo_select == "") {
		$error = true;
		$mensaje_de_error = "Error: Tiene que ingresar la COSTO por UNIDAD!";							
	} elseif ($check_strlen != 9) {
		$error = true;
		$mensaje_de_error = "Error: El formato del código no es correcto. Tiene que usar 5 digitos para el rubro y 3 digitos para el Sub-Nivel, separados por un punto!";			
	} elseif ($no_rubro_select != $no_rubro) {
		$error = true;
		$mensaje_de_error = "Error: El código del rubro no es válido (debe empezar con $no_rubro)!";	
	} elseif ($id_tasa_select == "") {
		$error = true;
		$mensaje_de_error = "Error: El código del Sub-Nivel no es válido (debe ser un número mayor a 0)!";			
	} elseif (($check_id_tasa > 0) AND ($_POST["accion"] == "Añadir")) {
		$error = true;
		$mensaje_de_error = "Error: Ya existe un Sub-Nivel con el código '$id_tasa_select' en la base de datos!";									
	} elseif (!check_float($monto_select)) {
		$error = true;
		$mensaje_de_error = "Error: Tiene que ingresar un monto para el nuevo Sub-Nivel!";									
	}


	if (!$error) {
		if ($_POST["accion"] == "Añadir") {
			$accion_reg = utf8_encode("Sub-Nivel añadido");
			$sql = "INSERT INTO tasas (id_tasa, descrip, monto, unidad, costo, id_tra, tipo, rubro ) 
		            VALUES ('$id_tasa_select','$descrip_select','$monto_select','$unidad_select','$costo_select','$id_tra','$tipo_select','$no_rubro')";
			pg_query($sql);
		} else {
			$accion_reg = "Sub-Nivel modificado";
			$sql = "UPDATE tasas SET descrip = '$descrip_select', monto = '$monto_select', unidad = '$unidad_select', costo = '$costo_select', tipo = '$tipo_select'  
					WHERE id_tasa = '$id_tasa_select'";
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
	 $id_tasa_select = $_POST["id_tasa"];
   pg_query("DELETE FROM tasas WHERE id_tasa = '$id_tasa_select'");
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
	 
$sql="SELECT id_tasa, descrip, monto, unidad, costo, tipo FROM tasas ORDER BY id_tasa";
$no_de_subniveles = pg_num_rows(pg_query($sql));
$result = pg_query($sql);
$i = $j = 0;
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	foreach ($line as $col_value) {
		if ($i == 0) { 
			$id_tasa_lista[$j] = $col_value;
			if ($col_value < 10) {
				$subnivel_lista[$j] = ".00".$col_value;
			} elseif ($col_value < 100) {
				$subnivel_lista[$j] = ".0".$col_value;
			} else {
				$subnivel_lista[$j] = ".".$col_value;
			}
		} elseif ($i == 1) { 
			$descrip_lista[$j] = $col_value; 	 
		} elseif ($i == 2) {
			$monto_lista[$j] = $col_value;
		} elseif ($i == 3) {
			$unidad_lista[$j] = $col_value;
		} elseif ($i == 4) {
			$costo_lista[$j] = $col_value;						
		} else { 
			$tipo_lista[$j] = $col_value;
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
			echo "<td width=\"8%\"> &nbsp</td>\n";     
			echo "<td align=\"center\" valign=\"center\" height=\"40\" width=\"70%\" class=\"pageName\">\n"; 
			echo "Tasas Administrativas\n";                          
			echo "</td>\n";
			echo "<td width=\"8%\"> &nbsp</td>\n"; 
		echo "</tr>\n";	
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=77&id=$session_id\" accept-charset=\"utf-8\">\n";
			echo "<tr>\n";
				echo "<td> &nbsp</td>\n"; 
				echo "<td align=\"left\" class=\"bodyTextD\"> $no_rubro $nom_rubro </td>\n"; 
				echo "<td> &nbsp</td>\n"; 	 
			echo "</tr>\n";
			echo "<tr>\n";
				echo "<td> &nbsp</td>\n";
				echo "<td align=\"left\" class=\"bodyTextD\"> $su_rubro $nom_su_rubro</td>\n"; 
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
					echo "<th>Monto</th>\n";	 	 
					echo "<th>Uni.</th>\n";
					echo "<th>Costo</th>\n";
					echo "<th>Tipo</th>\n";
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
						echo "<td><input name=\"id_tasa\" value=\"$id_tasa_lista[$i]\" type=\"radio\" checked=\"checked\"></td>\n";
						$k++;
					} else {
						echo "<td><input name=\"id_tasa\" value=\"$id_tasa_lista[$i]\" type=\"radio\"></td>\n";					 
					}	 
				} else {
				if ($id_tasa_select == $id_tasa_lista[$i]) {
					echo "<td><font color=\"red\"><b> > &nbsp </b></font></td>\n";
				} else {
					echo "<td> &nbsp&nbsp&nbsp&nbsp&nbsp </td>\n";
				} 
				}				 
				echo "<td>$subnivel_lista[$i]</td>\n";
				echo "<td class=\"alt\">$descrip_lista[$i]</td>\n";
				echo "<td>$monto_lista[$i] &nbsp</td>\n";	
				echo "<td>$unidad_lista[$i] &nbsp</td>\n";
				echo "<td>$costo_lista[$i] &nbsp</td>\n";
				echo "<td>$tipo_lista[$i] &nbsp</td>\n";		 			  
				echo "</tr>\n";
				$i++;
			}
	 } else {
 	    echo "<tr>\n"; 
 	    echo "<td align=\"center\" colspan=\"4\"> No hay registros en la base de datos!\n"; 
	    echo "</td>\n";	
	    echo "</tr>\n";		 
	 }	 
	echo "            </table>\n";
	echo "         </td>\n";		 
	echo "         <td> &nbsp</td>\n";   #Col. 1  
	echo "      </tr>\n";		 
	echo "      <tr height=\"10px\">\n";
	echo "         <td colspan=\"3\"> &nbsp</td>\n";   #Col. 1 	    		 
	echo "      </tr>\n";	 
	if (($nivel == 4) OR ($nivel == 5)) {
	    if ((!$anadir) AND (!$modificar) AND (!$borrar)) {
			# Fila 5						 
			echo "      <tr height=\"30px\">\n"; 
			echo "         <td> &nbsp</td>\n";   #Col. 1  
			echo "         <td align=\"center\">\n";   #Col. 2				   	
			echo "            <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Añadir\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";
			echo "            <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Modificar\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";				 
			echo "            <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Borrar\">\n";				 
			echo "         </td>\n";
			echo "         <td> &nbsp</td>\n";   #Col. 3				 	   	 
			echo "      </tr>\n";					 
			} else {
				if (($anadir) OR ($modificar)) {
				echo "      <tr height=\"30px\">\n";			
				echo "         <td> &nbsp</td>\n";   #Col. 1 	
				echo "         <td align=\"left\">\n";  #Col. 2 
				echo "            <table border=\"0\" width=\"100%\">\n";    # 3 Columnas	
				echo "               <tr>\n";
				echo "                  <td align=\"center\" colspan=\"5\" class=\"bodyTextD_Small\"> $accion Sub-Nivel</td>\n";   #Col. 1 
				echo "               </tr>\n";				  
				echo "               <tr>\n";
				echo "                  <td align=\"left\" width=\"5%\"> &nbsp </td>\n";   #Col. 1 					
				echo "                  <td align=\"left\" width=\"20%\"> No. Sub-Nivel:</td>\n";   #Col. 2 
				echo "                  <td align=\"left\" width=\"60%\"  colspan=\"2\" > descripción:</td>\n";   #Col. 3				 				 
				echo "                  <td align=\"left\" width=\"15%\"> Monto (en Bs.):</td>\n";   #Col. 4 
				echo "               </tr>\n";
				echo "               <tr>\n";
				echo "                  <td> &nbsp </td>\n";   #Col. 1						
				echo "                  <td align=\"left\">\n";   #Col. 2 		
				echo "                     <input type=\"text\" name=\"subnivel\" id=\"form_anadir0\" class=\"navText\" value=\"$subnivel_select\">\n";
				echo "                  </td>\n";
				echo "                  <td align=\"left\" colspan=\"2\" >\n";   #Col. 3 				 
				echo "                     <input type=\"text\" name=\"descrip\" id=\"form_anadir0\" class=\"navText\" value=\"$descrip_select\">\n";					 
				echo "                  </td>\n";
				echo "                  <td align=\"left\">\n";   #Col. 4 				 
				echo "                     <input type=\"text\" name=\"monto\" id=\"form_anadir0\" class=\"navText\" value=\"$monto_select\">\n";					 
				echo "                  </td>\n";				 				 
				echo "               </tr>\n";	

				echo "               <tr>\n";
				echo "                  <td> &nbsp </td>\n";   #Col. 1						
				echo "                  <td align=\"left\">\n";   #Col. 2 		
				echo "                     <input type=\"text\" name=\"unidad\" id=\"form_anadir0\" class=\"navText\" value=\"$unidad_select\">\n";
				echo "                  </td>\n";
				echo "                  <td align=\"left\" >Costo:</td>\n";   #Col. 4 
				echo "                  <td align=\"left\">\n";   #Col. 3 				 
				echo "                     <input type=\"text\" name=\"costo\" id=\"form_anadir0\" class=\"navText\" value=\"$costo_select\">\n";					 
				echo "                  </td>\n";
				echo "                  <td align=\"left\">\n";   #Col. 4 				 
				echo "                     <input type=\"text\" name=\"tipo\" id=\"form_anadir0\" class=\"navText\" value=\"$tipo_select\">\n";					 
				echo "                  </td>\n";				 				 
				echo "               </tr>\n";	
				
				echo "               <tr>\n";				
				echo "                  <td align=\"left\" width=\"100%\"  colspan=\"5\"> &nbsp </td>\n";   #Col. 4
				echo "               </tr>\n";

				echo "               <tr>\n";
				echo "                  <td align=\"center\" colspan=\"5\">\n";  #Col. 1-4		 										   		   		 
				echo "                     <input type=\"submit\" name=\"accion\" class=\"smallText\" value=\"$accion\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";
				echo "                     <input type=\"submit\" name=\"accion\" class=\"smallText\" value=\"No $accion\">\n";
				echo "                  </td>\n";			 
				echo "               </tr>\n";
				echo "            </table>\n";				 				 			 								   		 		  		 
				echo "         </td>\n";
				echo "         <td> &nbsp</td>\n";   #Col. 3 					 
				echo "      </tr>\n";
				if ($error) {
				echo "      <tr height=\"30px\">\n";			
				echo "         <td> &nbsp</td>\n";   #Col. 1 	
				echo "         <td align=\"center\">\n";  #Col. 2 
				echo "            <font color=\"red\"> $mensaje_de_error</font>\n";
				echo "         </td>\n";
				echo "         <td> &nbsp</td>\n";   #Col. 3 							 
				echo "      </tr>\n";	
			}
			   } else {
				echo "      <tr height=\"30px\">\n";			
				echo "         <td> &nbsp</td>\n";   #Col. 1 	
				echo "         <td align=\"left\">\n";  #Col. 2 	
				echo "            <font color=\"red\">Está segura/o que quiere borrar el Sub-Nivel '$subnivel_select'? </font>&nbsp&nbsp&nbsp\n";  		 
				echo "            <input type=\"submit\" name=\"accion\" class=\"smallText\" value=\"Borrar\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";
				echo "            <input type=\"submit\" name=\"accion\" class=\"smallText\" value=\"No Borrar\">\n";
				echo "            <input type=\"hidden\" name=\"id_tasa\" value=\"$id_tasa_select\">\n";							
				echo "         </td>\n";	
				echo "         <td> &nbsp</td>\n"; 
				echo "      </tr>\n";													 				 
				 }		 							
	    }	
		  echo "      </form>\n";		 
	 }
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
	 echo "   <br />&nbsp;<br />\n";
	 echo "</td>\n";
?>
