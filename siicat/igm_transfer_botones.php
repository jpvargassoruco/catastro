<?php
$error1 = $check_boton = false;
###########################################
#-------- TRANSFERENCIA DE URBANA --------#
###########################################
$transfer_urbano = $transfer_rural = false;
if ((isset($_POST['id_inmu'])) or (isset($_GET['inmu'])) or (isset($_POST['cod_uv']))) {
	$transfer_urbano = true;
	$tabla_transfer = "transfer";
	$where_option = "id_inmu = '$id_inmu'";
	$tabla_imp_pagados = "imp_pagados";
}


########################################
#-------- SUBMIT VER TRADICION --------#
########################################
if (((isset($_POST['submit'])) and ($_POST['submit'] == "Ver Tradición")) or (isset($_POST["Rect_confirm_x"]))) {

	$accion = "Tradicion";
	$sql = "SELECT id, tan_fech_ini, tan_fech_fin, tan_modo, tan_doc, tan_1id FROM $tabla_transfer WHERE $where_option ORDER BY tan_fech_ini DESC";

	$no_de_registros = pg_num_rows(pg_query($sql));
	if ($no_de_registros > 0) {
		$result = pg_query($sql);
		$i = $j = 0;
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
			foreach ($line as $col_value) {
				if ($i == 0) {
					$id_transfer[$j] = $col_value;
				} elseif ($i == 1) {
					$adq_fech_ini[$j] = change_date($col_value);
				} elseif ($i == 2) {
					$adq_fech_fin[$j] = change_date($col_value);
				} elseif ($i == 3) {
					$adq_modo_ant[$j] = $col_value;
				} elseif ($i == 4) {
					$adq_doc_ant[$j] = $col_value;
				} else {
					$titular1_ant[$j] = get_contrib_nombre($col_value);
					$adq_fech_ant[$j] = change_date($col_value);
					$i = -1;
				}
				$i++;
			}
			$j++;
		}
	}
}
########################################
#------- SUBMIT PRE-LIQUIDACION -------#
########################################	
if (((isset($_POST['submit'])) and ($_POST['submit'] == "Pre-Liquidacion")) or ((isset($_GET['mod'])) and ($_GET['mod'] == "69"))) {
	########################################
	#----- CHEQUEAR SI EXISTEN DEUDAS -----#
	######################################## 		
	$deudas = false;
	########################################
	#------ CHEQUEAR SI FALTAN DATOS ------#
	######################################## 		
	$faltan_datos = false;
	if ((!$deudas) and (!$faltan_datos)) {
		include "siicat_lista_contribuyentes.php";
	}
	$accion = "Preliquidacion";
	if (!isset($_POST['ver'])) {
		$min_num = $not_nom = $not_num = $not_cls = $not_exp = $min_val = $min_fech_texto = "";
		$modo_trans = "CPV";
	}
}

#############################################################################
#-------------------------------- FORMULARIO -------------------------------#
#############################################################################	
echo "<table border=\"0\" width=\"800px\">\n";
if ($activo == 1) {
	echo "<tr>\n";
	####################################
	#          PRE-LIQUIDACION         #
	####################################			 
	echo "<td align=\"center\" valign=\"top\" width=\"33%\">\n";   #Col. 1	
	if ($transfer_urbano) {
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&db=$db&id=$session_id#tab-9\" accept-charset=\"utf-8\">\n";
	} else {
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=45&db=$db&id=$session_id#tab-9\" accept-charset=\"utf-8\">\n";
	}
		echo "<fieldset><legend>Pre-Liquidacion</legend>\n";
		echo "<table border=\"0\" width=\"100%\">\n";
		echo "<tr>\n";
		echo "<td align=\"center\" width=\"33%\">\n";
		if ($transfer_urbano) {
			echo "<input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";
		} else {
			echo "<input type=\"hidden\" name=\"id_predio_rural\" value=\"$id_predio_rural\">\n";
		}
		echo "<input type=\"submit\" name=\"submit\" class=\"smallText\" value=\"Pre-Liquidacion\">\n";
		echo "<br /><br /> Calcular el impuesto a las transferencias.<br /><br /> \n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</fieldset>\n";
	echo "</form>\n";
	echo "</td>\n";
	####################################
	#     TRANSFERENCIA DEL PREDIO     #
	####################################	 	  
	echo "<td align=\"center\" valign=\"top\" width=\"34%\">\n";   #Col. 1	
	echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=67&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
	echo "<fieldset><legend>Transferencia</legend>\n";
	if ($accion == "Mandar Codigo") {
		echo "<table border=\"0\" width=\"100%\" bgcolor=\"#c6dbf1\">\n";
	} else {
		echo "<table border=\"0\" width=\"100%\">\n";
	}
	echo "<tr>\n";
	echo "<td align=\"center\" width=\"33%\">\n";   #Col. 1		
	if ($nivel == 1) {
		echo "No tiene el nivel de usuario para realizar una transferencia. \n";
		echo "<br /><br /> &nbsp\n";
	} else {
		if ($transfer_urbano) {
			echo "<input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";
		} else {
			echo "<input type=\"hidden\" name=\"id_predio_rural\" value=\"$id_predio_rural\">\n";
		}
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Transferencia\">\n";
		echo "<br /><br /> Usa esa opción para realizar una transferencia.<br /><br />\n";
	}
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</fieldset>\n";
	echo "</form>\n";
	echo "</td>\n";
	###########################  		  
	#   HISTORIAL DE PREDIO   #
	###########################
	echo "<td align=\"center\" valign=\"top\" width=\"33%\">\n";   #Col. 1	
	if ($transfer_urbano) {
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&db=$db&id=$session_id#tab-9\" accept-charset=\"utf-8\">\n";
	} else {
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=45&db=$db&id=$session_id#tab-9\" accept-charset=\"utf-8\">\n";
	}
	echo "<fieldset><legend>Tradición del Predio</legend>\n";
	echo "<table border=\"0\" width=\"100%\">\n";
	echo "<tr>\n";
	echo "<td align=\"center\" width=\"34%\">\n";   #Col. 1	
	if ($transfer_urbano) {
		echo "<input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";
	} else {
		echo "<input type=\"hidden\" name=\"id_predio_rural\" value=\"$id_predio_rural\">\n";
	}
	echo "<input type=\"submit\" name=\"submit\" class=\"smallText\" value=\"Ver Tradición\">\n";
	echo "<br /><br /> Aqui puede revisar el historial del Inmueble.<br /><br /> \n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</fieldset>\n";
	echo "</form>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}
if (($accion == "Tradicion") or ($activo == 0) or (isset($_POST["Rect_confirm_x"]))) {
	echo "<table border=\"0\" width=\"800px\">\n";
	echo "<tr>\n";
	echo "<td align=\"center\" colspan=\"3\"> &nbsp</td>\n";   #Col. 1-3 
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td height=\"40\"> &nbsp</td>\n";   #Col. 1  	 	 
	echo "<td valign=\"top\">\n";   #Col. 2
	if ($transfer_urbano) {
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&db=$db&id=$session_id#tab-9\" accept-charset=\"utf-8\">\n";
	} else {
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=45&db=$db&id=$session_id#tab-9\" accept-charset=\"utf-8\">\n";
	}
	echo "<fieldset><legend>Tradición del Inmueble</legend>\n";
	echo "<table border=\"0\" width=\"100%\">\n";
	echo "<tr>\n";
	echo "<td align=\"right\" colspan=\"11\" class=\"bodyText\"></td>\n";   #Col. 1	 
	echo "</tr>\n";
	if ($no_de_registros == 0) {
		echo "<tr>\n";
		echo "<td align=\"center\" colspan=\"13\" class=\"bodyTextD\">\n";   #Col. 2
		echo "<font color=\"black\"> No hay registros antiguos del $predio en la base de datos.</font>\n";
		echo "</td>\n";
		echo "</tr>\n";
	} else {
		echo "<tr>\n";
		echo "<td width=\"1%\"></td>\n";
		echo "<td align=\"center\" width=\"12%\" class=\"bodyTextH\">Desde Fecha</td>\n";
		echo "<td width=\"1%\"></td>\n";
		echo "<td align=\"center\" width=\"10%\" class=\"bodyTextH\">Hasta Fecha</td>\n";
		echo "<td width=\"1%\"></td>\n";
		echo "<td align=\"center\" width=\"45%\" class=\"bodyTextH\">Propietario</td>\n";
		echo "<td width=\"1%\"></td>\n";
		echo "<td align=\"center\" width=\"22%\" class=\"bodyTextH\">Modo Adquisición</td>\n";
		echo "<td width=\"1%\"></td>\n";
		if ($nivel > 3) {
			echo "<td align=\"center\" width=\"5%\" class=\"bodyTextH\">Rect.</td>\n";
		} else {
			echo "<td align=\"center\" width=\"5%\">&nbsp</td>\n";
		}
		echo "<td width=\"1%\"></td>\n";
		echo "</tr>\n";
		$i = 0;
		while ($i < $no_de_registros) {
			if ($i > 0) {
				echo "<tr>\n";
				echo "<td colspan=\"9\"><hr width=\"95%\"></td>\n";
				echo "</tr>\n";
			}
			echo "<tr>\n";
			echo "<td></td>\n";
			echo "<td align=\"center\" class=\"bodyTextD\">$adq_fech_ini[$i]</td>\n";
			echo "<td></td>\n";
			echo "<td align=\"center\" class=\"bodyTextD\">$adq_fech_fin[$i]</td>\n";
			echo "<td></td>\n";
			echo "<td align=\"center\" class=\"bodyTextD\">$titular1_ant[$i]</td>\n";
			echo "<td></td>\n";
			$texto = abr($adq_modo_ant[$i]);
			echo "<td align=\"center\" class=\"bodyTextD\">$texto</td>\n";
			echo "<td></td>\n";
			if (($nivel > 3) and (!$check_boton)) {
				echo "<td align=\"center\" class=\"bodyTextD\">\n";
				if ($transfer_urbano) {
					echo "<input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";
				} else {
					echo "<input type=\"hidden\" name=\"id_predio_rural\" value=\"$id_predio_rural\">\n";
				}
				echo "<input name=\"id_transfer\" type=\"hidden\" value=\"$id_transfer[$i]\">\n";
				echo "<input type=\"image\" src=\"../siicat/graphics/boton_rectificar.png\" width=\"12\" height=\"12\" class=\"smallText\" name=\"Rect_confirm\" value=\"Rect_confirm\">\n";
				echo "</td>\n";
				$check_boton = true;
			} else {
				echo "<td align=\"center\">&nbsp</td>\n";
			}
			echo "<td></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td colspan=\"5\"> &nbsp</td>\n";
			echo "<td colspan=\"3\">Documentación: $adq_doc_ant[$i] </td>\n";
			echo "<td colspan=\"3\"> &nbsp</td>\n";
			echo "</tr>\n";
			$i++;
		}
	}
	echo "</table>\n";
	echo "</fieldset>\n";
	echo "</form>\n";
	echo "</td>\n";
	echo "<td> &nbsp</td>\n";
	echo "</tr>\n";

	########################################
	#------------- RECTIFICADO ------------#
	########################################		 
	if (isset($_POST["Rect_confirm_x"])) {
		$id_transfer_select = $_POST["id_transfer"];
		if ($transfer_urbano) {
			echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&db=$db&id=$session_id#tab-9\" accept-charset=\"utf-8\">\n";
		} else {
			echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=45&db=$db&id=$session_id#tab-9\" accept-charset=\"utf-8\">\n";
		}
		echo "<tr>\n";
		echo "<td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3 	 
		echo "<font color=\"red\"><b> CUIDADO: Realmente quiere recuperar el propietario anterior (se perderán los datos de los propietarios actuales y se borrarán los pagos de transferencia realizados)?</b></font>\n";
		echo "<input name=\"rectificar_transfer\" type=\"submit\" class=\"smallText\" value=\"SI\">&nbsp&nbsp&nbsp&nbsp&nbsp\n";
		echo "<input name=\"rectificar_transfer\" type=\"submit\" class=\"smallText\" value=\"NO\">\n";
		echo "<input name=\"id_transfer\" type=\"hidden\" value=\"$id_transfer_select\">\n";
		if ($transfer_urbano) {
			echo "<input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";
		} else {
			echo "<input type=\"hidden\" name=\"id_predio_rural\" value=\"$id_predio_rural\">\n";
		}
		echo "</td>\n";
		echo "</tr>\n";
		echo "</form>\n";
	}

	echo "</table>\n";
} elseif ($accion == "Preliquidacion") {
	echo "<table border=\"0\" width=\"800px\">\n";
	if (($deudas) or ($faltan_datos)) {
		echo "<tr>\n";
		echo "<td align=\"center\" colspan=\"3\">\n";
		echo "<font color=\"red\"> $mensaje_de_error</font>\n";
		echo "</td>\n";
		echo "</tr>\n";
	} else {
		if ($db == "cc") {
			echo "<tr>\n";
			echo "<td align=\"center\" colspan=\"3\">\n";   #Col. 2	
			echo "<font color=\"orange\"> ATENCION: Debe verificar que el inmueble no tiene pagos de impuestos pendientes antes de la fecha de firma de minuta!</font>\n";
			echo "</td>\n";
			echo "</tr>\n";
		}
		echo "<tr>\n";
		echo "<td width=\"5%\">&nbsp</td>\n";   #Col. 1		  
		echo "<td align=\"center\" valign=\"top\" width=\"90%\">\n";   #Col. 2
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=69&db=$db&id=$session_id#tab-9\" accept-charset=\"utf-8\">\n";
		echo "<fieldset><legend>Pre-Liquidacion</legend>\n";
		echo "<table border=\"0\" width=\"800px\">\n";
		echo "<tr height=\"30\">\n";
			echo "<td align=\"right\" style='font-size: 10pt'>\n"; 
				echo "<b>Modo Adquisición:</b>\n";
			echo "</td>\n";
			echo "<td align=\"right\" colspan=\"2\" class=\"bodyText\">\n"; 	 
				$valores = get_abr('adq_modo');
				echo "<select class=\"navText\" name=\"modo_trans\" size=\"1\">\n";
					$i = 0;
					foreach ($valores as $i => $j) {
						$texto = abr($valores[$i]);
						if ($texto == "Otros") {
							$texto = "Otros (Sin cobro de Imp.)";
						}

						if ($valores[$i] == $modo_trans) {
							echo "<option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n";
						} else {
							echo "<option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
						}
						$i++;
					}
				echo "</select>\n";
			echo "</td>\n";
			echo "<td align=\"left\" colspan=\"4\">\n";
				echo "(Si es Herencia, los impuestos se debe cancelar en Impuestos Nacionales.) \n";
			echo "</td>\n";
		echo "</tr>\n";

		echo "<tr>\n";
			echo "<td align=\"right\" style='font-size: 10pt'>\n";
			echo "<b>No. de Minuta:</b>\n";
			echo "</td>\n";
			echo "<td align=\"left\" class=\"bodyTextD\">\n";
			echo "<input type=\"text\" name=\"min_num\" id=\"form_anadir1\" class=\"navText\" maxlength=\"12\" value=\"$min_num\">\n";
			echo "</td>\n";
			echo "<td align=\"right\" colspan=\"2\" style='font-size: 10pt'>\n";
			echo "<b>Nombre Notario:</b>\n";
			echo "</td>\n";
			echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">\n";
			echo "<input type=\"text\" name=\"not_nom\" id=\"form_anadir1\" class=\"navText\" maxlength=\"30\" value=\"$not_nom\">\n";
			echo "</td>\n";
		echo "</tr>\n";

		echo "<tr>\n";
			echo "<td align=\"right\" style='font-size: 10pt'>\n";
				echo "<b>No. Notario:</b>\n";
			echo "</td>\n";
			echo "<td align=\"left\" class=\"bodyTextD\">\n";
				echo "<input type=\"text\" name=\"not_num\" id=\"form_anadir1\" class=\"navText\" maxlength=\"4\" value=\"$not_num\">\n";
			echo "</td>\n";
			echo "<td align=\"right\" style='font-size: 10pt'>\n";
				echo "<b>Clase:</b>\n";
			echo "</td>\n";
			echo "<td align=\"left\" class=\"bodyTextD\">\n";
				echo "<input type=\"text\" name=\"not_cls\" id=\"form_anadir1\" class=\"navText\" maxlength=\"2\" value=\"$not_cls\">\n";
			echo "</td>\n";
			echo "<td align=\"right\" style='font-size: 10pt'>\n";
				echo "<b>Lugar de Expedición:</b>\n";
			echo "</td>\n";
			echo "<td align=\"left\" class=\"bodyTextD\">\n";
				echo "<input type=\"text\" name=\"not_exp\" id=\"form_anadir1\" class=\"navText\" maxlength=\"20\" value=\"$not_exp\">\n";
			echo "</td>\n";
		echo "</tr>\n";
		echo "<tr height=\"30\">\n";
			echo "<td width=\"20%\" align=\"right\" style='font-size: 10pt'>\n";
				echo "<b>Valor Minuta:</b>\n";
			echo "</td>\n";
			echo "<td width=\"12%\" align=\"left\" class=\"bodyTextD\">\n";
			echo "<input type=\"text\" name=\"min_val\" id=\"form_anadir1\" class=\"navText\" maxlength=\"8\" value=\"$min_val\">\n";
			echo "</td>\n";
			echo "<td width=\"8%\" align=\"center\" class=\"bodyTextD\">\n";
				echo "<input name=\"min_mon\" value=\"bs\" type=\"radio\" checked=\"checked\"> Bs.\n";
			echo "</td>\n";
			echo "<td width=\"8%\" align=\"center\" class=\"bodyTextD\">\n";
				echo "<input name=\"min_mon\" value=\"usd\" type=\"radio\"> USD\n";
			echo "</td>\n";
			echo "<td width=\"25%\" align=\"right\" style='font-size: 10pt'>\n";
			echo "<b>Fecha Firma Minuta:</b>\n";
			echo "</td>\n";
			echo "<td width=\"18%\" align=\"left\" class=\"bodyTextD\">\n";
			echo "<input type=\"text\" name=\"min_fech\" id=\"form_anadir1\" class=\"navText\" maxlength=\"10\" value=\"$min_fech_texto\">\n";
			echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
			echo "<td align=\"left\" colspan=\"4\">\n";
			echo "&nbsp&nbsp&nbsp&nbsp Seleccione el nuevo <b>comprador 1</b>:\n";
			echo "</td>\n";
			echo "<td align=\"left\" colspan=\"4\">\n";
			echo "&nbsp&nbsp&nbsp&nbsp Seleccione el nuevo <b>comprador 2</b>:\n";
			echo "</td>\n";			
		echo "</tr>\n";
		echo "<tr>\n";
			# COMPRADOR 1
			echo "<td align=\"right\" colspan=\"3\" class=\"bodyText\">\n";
				echo "<input type=\"text\" name=\"comprador1_carnet\" id=\"form_anadir1\" class=\"navText\" maxlength=\"14\" value=\"".htmlspecialchars($comprador1_carnet)."\" placeholder=\"Ingrese N° carnet\">\n";
				if (!empty($comprador1_name)) {
					echo "<br/>Nombre: <b>".htmlspecialchars($comprador1_name)."</b>\n";
					echo "<input type=\"hidden\" name=\"comprador\" value=\"".intval($comprador1_id)."\">\n";				
				} else {
					if (!empty($mensaje_de_error_comp1)) {
						echo "<br/><span style='color:red;'>".htmlspecialchars($mensaje_de_error_comp1)."</span>\n";
					} else {
						echo "<br/><span style='color:gray;'>Resultado aparecerá aquí</span>\n";
					}
				}
			echo "</td>\n";
			echo "<td align=\"right\" colspan=\"1\" class=\"bodyText\">\n";
				echo "&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;\n";
			echo "</td>\n";
			# COMPRADOR 2
			echo "<td align=\"right\" colspan=\"4\" class=\"bodyText\">\n";
				echo "<input type=\"text\" name=\"comprador2_carnet\" id=\"form_anadir1\" class=\"navText\" maxlength=\"14\" value=\"".htmlspecialchars($comprador2_carnet)."\" placeholder=\"Ingrese N° carnet\">\n";
				if (!empty($comprador2_name)) {
					echo "<br/>Nombre: <b>".htmlspecialchars($comprador2_name)."</b>\n";
					echo "<input type=\"hidden\" name=\"comprador1_name\" value=\"$comprador1_name\">\n";
					echo "<input type=\"hidden\" name=\"comprador2\" value=\"".intval($comprador2_id)."\">\n";		
				} else {
					if (!empty($mensaje_de_error_comp2)) {
						echo "<br/><span style='color:red;'>".htmlspecialchars($mensaje_de_error_comp2)."</span>\n";
					} else {
						echo "<br/><span style='color:gray;'>Resultado aparecerá aquí</span>\n";
					}
				}
			echo "</td>\n";

		echo "</tr>\n";
		echo "<tr height=\"30\">\n";
		echo "<td align=\"center\" colspan=\"7\">\n";
		echo "El impuesto es 3% sobre el Valor Catastral o Valor Minuta (el que fuere mayor), debiendo ser pagado dentro de los diez (l0) días hábiles posteriores a la fecha de firma minuta (según D.S. 24054).\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr height=\"40\">\n";
		echo "<td align=\"center\" colspan=\"7\">\n";
		if ($transfer_urbano) {
			echo "<input type=\"hidden\" name=\"id_inmu\" value=\"$id_inmu\">\n";
		} else {
			echo "<input type=\"hidden\" name=\"id_predio_rural\" value=\"$id_predio_rural\">\n";
		}
		echo "<input name=\"ver\" type=\"submit\" class=\"smallText\" value=\"Generar Boleta\">\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</fieldset>\n";
		echo "</form>\n";
		echo "</td>\n";
		echo "<td width=\"5%\">&nbsp</td>\n";
		echo "</tr>\n";
		if ((isset($_GET['mod'])) and ($_GET['mod'] == "69")) {
			echo "<tr>\n";
				echo "<td>&nbsp</td>\n";
				echo "<td align=\"center\" class=\"alert alert-danger\"> $mensaje_de_error_transfer</td>\n";  
				echo "<td>&nbsp</td>\n";
			echo "</tr>\n";
			if ($mensaje_de_ayuda_transfer!='') {
				echo "<tr>\n";
					echo "<td>&nbsp</td>\n";
					echo "<td align=\"center\" class=\"alert alert-info\"> $mensaje_de_ayuda_transfer</td>\n";  
					echo "<td>&nbsp</td>\n";
				echo "</tr>\n";
			}

		}
		
	}
	echo "</table>\n";
}
?>