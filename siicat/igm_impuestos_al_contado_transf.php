<?php
################################################################################
#                     EL SIGUIENTE CODIGO FUE CREADO POR:                      #
#         MERCATORGIS - SISTEMAS DE INFORMACION GEOGRAFICA Y DE CATASTRO       #
#     ***** http://www.mercatorgis.com  *****  info@mercatorgis.com *****      #
################################################################################

### ver siicat_impuestos_boleta_de_pago.php
$forma_pago_cancelado = false;
################################################################################
#------------------------------- FORMULARIO -----------------------------------#
################################################################################	
echo "<table border=\"0\" width=\"600px\">\n";
if ($registro_banco_existe) {
	########################################
	#--------- PAGOS REGISTRADOS  ---------#
	########################################
	echo "<tr>\n";
	echo "<td> &nbsp</td>\n";   #Col. 1  	 
	echo "<td valign=\"top\" height=\"40\">\n";   #Col. 2
	echo "<fieldset><legend>Pagos Registrados para la Gestion $gestion</legend>\n";
	echo "<table border=\"0\" width=\"100%\" id=\"registros2\">\n";   # 5 TColumnas
	echo "<tr>\n";
	echo "<td width=\"10%\" align=\"center\" class=\"bodyTextH\"><b>FECHA</b></td>\n";
	echo "<td width=\"30%\" align=\"center\" class=\"bodyTextH\"><b>LUGAR PAGO</b></td>\n";
	echo "<td width=\"24%\" align=\"center\" class=\"bodyTextH\"><b>NO. BOLETA</b></td>\n";
	echo "<td width=\"24%\" align=\"center\" class=\"bodyTextH\"><b>FOLIO</b></td>\n";
	echo "<td width=\"12%\" align=\"center\" class=\"bodyTextH\"><b>MONTO</b></td>\n";
	echo "</tr>\n";
	### MOSTRAR REGISTROS DEL BANCO ###	    	 	
	$i = 0;
	while ($i < $no_de_registros_banco) {
		echo "<tr>\n";
		echo "<td>$fech_pago_conban[$i]</td>\n";
		echo "<td>$nombre_banco_conban[$i]</td>\n";
		echo "<td>$no_boleta_banco_conban[$i]</td>\n";
		echo "<td>$folio_conban[$i]</td>\n";
		echo "<td>$monto_conban[$i]</td>\n";
		echo "</tr>\n";
		$i++;
	}
	echo "</table>\n";
	echo "</fieldset>\n";
	echo "</td>\n";
	echo "<td> &nbsp</td>\n";   #Col. 3
	echo "</tr>\n";
	########################################
	#-------- CALCULO ACTUALIZADO ---------# 
	########################################		
	echo "<tr height=\"30\">\n";
	echo "<td> &nbsp</td>\n";   #Col. 1  	 
	echo "<td align=\"center\">\n";   #Col. 2		 	
	echo "Monto Cancelado en la Gestión $gestion: <b> &nbsp&nbsp $monto_conban_total Bs.</b>\n";
	echo "</td>\n";
	echo "<td> &nbsp</td>\n";   #Col. 3
	echo "</tr>\n";
}
#if (!$registro_banco_existe) {	
if (($calcular_urbano) or ($calcular_rural)) {
	########################################
	#---------- VALOR EN LIBROS  ----------#
	########################################
	echo "<tr>\n";
	echo "<td> &nbsp</td>\n";   #Col. 1  	 
	echo "<td valign=\"top\" height=\"40\">\n";   #Col. 2
	echo "<fieldset><legend>Solo para Empresas</legend>\n";
	# if ($calcular_urbano) {
	echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=62&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
	echo "<table border=\"0\" width=\"100%\">\n";   # 5 TColumnas
	echo "<tr>\n";
	echo "<td width=\"1%\"> &nbsp </td>\n";   #TCol. 1 					
	echo "<td width=\"44%\" align=\"right\" class=\"bodyTextD\">Declaración de Valor en Libros (en Bs.): </td>\n"; #Col. 2-4
	echo "<td width=\"40%\" align=\"center\" class=\"bodyTextD\">\n";   #TCol. 4	  				 	
	echo "<input name=\"valor_en_libros\" id=\"form_anadir2\" class=\"navText\" maxlength=\"10\" value=\"$valor_lib\">\n";
	echo "</td>\n";
	echo "<td width=\"14%\" align=\"center\" class=\"bodyTextD\">\n";   #TCol. 4	
	if ($calcular_urbano) {
		echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";
	} elseif ($calcular_rural) {
		echo "<input name=\"id_predio_rural\" type=\"hidden\" value=\"$id_predio_rural\">\n";
	}
	echo "<input name=\"gestion\" type=\"hidden\" value=\"$gestion\">\n";
	echo "<input name=\"estatus\" type=\"hidden\" value=\"$estatus\">\n";
	echo "<input name=\"imp_neto\" type=\"hidden\" value=\"$imp_neto\">\n";
	if ($exencion_seleccionada) {
		echo "<input name=\"exen_selected\" type=\"hidden\" value=\"$exen_select\">\n";
	}
	if ($condonacion_seleccionada) {
		echo "<input name=\"condon_selected\" type=\"hidden\" value=\"$condon_select\">\n";
	}
	echo "<input name=\"vl\" type=\"submit\" class=\"smallText\" value=\"APLICAR\">\n";
	echo "</td>\n";
	echo "<td width=\"1%\"> &nbsp </td>\n";   #TCol. 5 		 	 				 				  	   
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	echo "</fieldset>\n";
	echo "</td>\n";
	echo "<td> &nbsp</td>\n";   #Col. 3
	echo "</tr>\n";
}
########################################
#------------ EXENCIONES  -------------#
########################################
echo "<tr>\n";
echo "<td> &nbsp</td>\n";   #Col. 1  	 
echo "<td valign=\"top\">\n";   #Col. 2
	echo "<fieldset><legend>Aplicar Exenciones</legend>\n";
		if ($calcular_urbano) {
			echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=62&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
		} elseif ($calcular_rural) {
			echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=52&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
		} elseif ($calcular_patente) {
			echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=108&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
		} elseif (($calcular_transfer_urbano) or ($calcular_transfer_rural)) {
			echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=69&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
		}
		echo "<table border=\"0\" width=\"100%\">\n";   # 5 TColumnas
		echo "<tr>\n";
		echo "<td width=\"1%\"> &nbsp </td>\n";   #TCol. 1 					
		echo "<td width=\"26%\" align=\"right\" class=\"bodyTextD\">Seleccionar Exención: </td>\n"; #Col. 2-4							
		echo "<td width=\"58%\" align=\"center\" class=\"bodyTextD\">\n";   #TCol. 4	  
		echo "<select class=\"navText\" name=\"exen_selected\" size=\"1\">\n";
		$i = 0;
		while ($i <= $check_exenciones) {
			if ($exen_numero[$i] == $exen_select) {
				echo "<option id=\"form0\" value=\"$exen_numero[$i]\" selected=\"selected\"> $exen[$i]</option>\n";
			} else {
				echo "<option id=\"form0\" value=\"$exen_numero[$i]\"> $exen[$i]</option>\n";
			}
			$i++;
		}
		echo "</select>\n";
		echo "</td>\n";
		echo "<td width=\"14%\" align=\"center\" valign=\"center\">\n";
		if (($calcular_urbano) or ($calcular_transfer_urbano)) {
			echo "<input name=\"id_inmu\" type=\"hidden\" class=\"smallText\" value=\"$id_inmu\">\n";
		} elseif (($calcular_rural) or ($calcular_transfer_rural)) {
			echo "                     <input name=\"id_predio_rural\" type=\"hidden\" class=\"smallText\" value=\"$id_predio_rural\">\n";
		} elseif ($calcular_patente) {
			echo "<input name=\"id_patente\" type=\"hidden\" class=\"smallText\" value=\"$id_patente\">\n";
		}
		echo "<input name=\"gestion\" type=\"hidden\" class=\"smallText\" value=\"$gestion\">\n";
		echo "<input name=\"estatus\" type=\"hidden\" class=\"smallText\" value=\"$estatus\">\n";
		echo "<input name=\"imp_neto\" type=\"hidden\" class=\"smallText\" value=\"$imp_neto\">\n";
		if ($valor_en_libros_ingresado) {
			echo "<input name=\"valor_en_libros\" type=\"hidden\" value=\"$valor_en_libros\">\n";
		}
		if ($condonacion_seleccionada) {
			echo "<input name=\"condon_selected\" type=\"hidden\" value=\"$condon_select\">\n";
		}
		if (($calcular_transfer_urbano) or ($calcular_transfer_rural)) {
			echo "<input name=\"modo_trans\" type=\"hidden\" class=\"smallText\" value=\"$modo_trans\">\n";
			echo "<input name=\"min_num\" type=\"hidden\" class=\"smallText\" value=\"$min_num\">\n";
			echo "<input name=\"not_nom\" type=\"hidden\" class=\"smallText\" value=\"$not_nom\">\n";
			echo "<input name=\"not_num\" type=\"hidden\" class=\"smallText\" value=\"$not_num\">\n";
			echo "<input name=\"not_cls\" type=\"hidden\" class=\"smallText\" value=\"$not_cls\">\n";
			echo "<input name=\"not_exp\" type=\"hidden\" class=\"smallText\" value=\"$not_exp\">\n";
			echo "<input name=\"min_val\" type=\"hidden\" class=\"smallText\" value=\"$min_val\">\n";
			echo "<input name=\"min_mon\" type=\"hidden\" class=\"smallText\" value=\"$min_mon\">\n";
			echo "<input name=\"min_fech\" type=\"hidden\" class=\"smallText\" value=\"$min_fech\">\n";
			echo "<input name=\"comprador\" type=\"hidden\" class=\"smallText\" value=\"$id_comp\">\n";
			echo "<input name=\"comprador2\" type=\"hidden\" class=\"smallText\" value=\"$id_comp2\">\n";
		}
		echo "<input name=\"exen\" type=\"submit\" class=\"smallText\" value=\"APLICAR\">\n";
		echo "</td>\n";
		echo "<td width=\"1%\"> &nbsp </td>\n";   #TCol. 5 		 	 
		echo "</tr>\n";
		echo "</table>\n";
	echo "</fieldset>\n";
echo "</form>\n";
echo "</td>\n";
echo "<td> &nbsp</td>\n";   #Col. 3
echo "</tr>\n";
if ($error_exencion) {
	echo "<tr>\n";
	echo "<td> &nbsp</td>\n";   #Col. 1  	 
	echo "<td align=\"center\">\n";   #Col. 2
	echo "<font color=\"red\"> Error: Solo puede seleccionar la exención 'PRESCRIPCION' cuando las útimas cinco gestiones está canceladas!";
	echo "</td>\n";
	echo "<td> &nbsp</td>\n";   #Col. 3
	echo "</tr>\n";
}
########################################
#-------- CONDONACION DE MULTA --------#
########################################				 
if ($show_condonacion) {
	# Fila 6	
	echo "<tr>\n";
	echo "<td> &nbsp</td>\n";   #Col. 1  	 
	echo "<td valign=\"top\">\n";   #Col. 2
	echo "<fieldset><legend>Condonación de Multas</legend>\n";
	if (($calcular_transfer_urbano) or ($calcular_transfer_rural)) {
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=69&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
	} else {
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=62&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
	}
	echo "<table border=\"0\" width=\"100%\">\n";   # 5 TColumnas
	echo "<tr>\n";
	echo "<td width=\"1%\"> &nbsp </td>\n";   #TCol. 1 					
	echo "<td width=\"30%\" align=\"right\" class=\"bodyTextD\">Seleccionar Resoluci�n: </td>\n"; #Col. 2-4							
	echo "<td width=\"54%\" align=\"center\" class=\"bodyTextD\">\n";   #TCol. 4	  
	echo "<select class=\"navText\" name=\"condon_selected\" size=\"1\">\n";
	$i = 0;
	while ($i <= $check_condonaciones) {
		if ($condon_numero[$i] == $condon_select) {
			echo "<option id=\"form0\" value=\"$condon_numero[$i]\" selected=\"selected\"> $condon[$i]</option>\n";
		} else {
			echo "<option id=\"form0\" value=\"$condon_numero[$i]\"> $condon[$i]</option>\n";
		}
		$i++;
	}
	echo "</select>\n";
	echo "</td>\n";
	echo "<td width=\"14%\" align=\"center\" valign=\"center\">\n";   #Col. 2-4	 
	if (($calcular_urbano) or ($calcular_transfer_urbano)) {
		echo "<input name=\"id_inmu\" type=\"hidden\" class=\"smallText\" value=\"$id_inmu\">\n";
	} elseif (($calcular_rural) or ($calcular_transfer_rural)) {
		echo "<input name=\"id_predio_rural\" type=\"hidden\" class=\"smallText\" value=\"$id_predio_rural\">\n";
	} elseif ($calcular_patente) {
		echo "<input name=\"id_patente\" type=\"hidden\" class=\"smallText\" value=\"$id_patente\">\n";
	}
	echo "<input name=\"gestion\" type=\"hidden\" value=\"$gestion\">\n";
	echo "<input name=\"estatus\" type=\"hidden\" value=\"$estatus\">\n";
	echo "<input name=\"imp_neto\" type=\"hidden\" value=\"$imp_neto\">\n";
	if ($valor_en_libros_ingresado) {
		echo "<input name=\"valor_en_libros\" type=\"hidden\" value=\"$valor_en_libros\">\n";
	}
	if ($exencion_seleccionada) {
		echo "<input name=\"exen_selected\" type=\"hidden\" value=\"$exen_select\">\n";
	}
	if (($calcular_transfer_urbano) or ($calcular_transfer_rural)) {
		echo "<input name=\"modo_trans\" type=\"hidden\" class=\"smallText\" value=\"$modo_trans\">\n";
		echo "<input name=\"min_num\" type=\"hidden\" class=\"smallText\" value=\"$min_num\">\n";
		echo "<input name=\"not_nom\" type=\"hidden\" class=\"smallText\" value=\"$not_nom\">\n";
		echo "<input name=\"not_num\" type=\"hidden\" class=\"smallText\" value=\"$not_num\">\n";
		echo "<input name=\"not_cls\" type=\"hidden\" class=\"smallText\" value=\"$not_cls\">\n";
		echo "<input name=\"not_exp\" type=\"hidden\" class=\"smallText\" value=\"$not_exp\">\n";
		echo "<input name=\"min_val\" type=\"hidden\" class=\"smallText\" value=\"$min_val\">\n";
		echo "<input name=\"min_mon\" type=\"hidden\" class=\"smallText\" value=\"$min_mon\">\n";
		echo "<input name=\"min_fech\" type=\"hidden\" class=\"smallText\" value=\"$min_fech\">\n";
		echo "<input name=\"comprador\" type=\"hidden\" class=\"smallText\" value=\"$id_comp\">\n";
		echo "<input name=\"comprador2\" type=\"hidden\" class=\"smallText\" value=\"$id_comp2\">\n";
	}
	echo "                     <input name=\"cond_multa\" type=\"submit\" class=\"smallText\" value=\"APLICAR\">\n";
	echo "                  </td>\n";
	echo "                  <td width=\"1%\"> &nbsp </td>\n";   #TCol. 5 		 	 
	echo "               </tr>\n";
	echo "            </table>\n";
	echo "         </fieldset>\n";
	echo "         </form>\n";
	echo "         </td>\n";
	echo "         <td> &nbsp</td>\n";   #Col. 3
	echo "      </tr>\n";
}
#}
########################################
#-------- DESCUENTO POR EDAD ----------# 
########################################
if ($descuento_por_edad) {
	echo "<tr height=\"30\">\n";
	echo "<td> &nbsp</td>\n";   #Col. 1  	 
	echo "<td align=\"center\">\n";   #Col. 2		 	
	echo "<font color=\"orange\"><b> SE APLICA UN DESCUENTO DE 20% AL IMPUESTO POR MAYOR DE EDAD!</b></font>\n";
	echo "</td>\n";
	echo "<td> &nbsp</td>\n";   #Col. 3
	echo "</tr>\n";
}
########################################
#-------- CALCULO ACTUALIZADO ---------# 
########################################		
echo "<tr height=\"30\">\n";
echo "<td> &nbsp</td>\n"; 	 
echo "<td align=\"center\">\n"; 
if (($calcular_transfer_urbano) or ($calcular_transfer_rural)) {
	echo "<b> Impuestos: $deuda_bs Bs.  &nbsp&nbsp&nbsp&nbsp&nbsp Multa: $multas_total_bs_exen Bs. &nbsp&nbsp&nbsp&nbsp&nbsp Rep.Form: $rep_form_exen Bs. &nbsp&nbsp&nbsp&nbsp&nbsp Deuda: $monto_a_pagar_temporal Bs.</b>\n";
} else {
	echo "<b> Monto: $monto_imp Bs. &nbsp&nbsp&nbsp&nbsp&nbsp Interes: $interes_red UFV &nbsp&nbsp&nbsp&nbsp&nbsp Multa: $multas_total UFV &nbsp&nbsp&nbsp&nbsp&nbsp Deuda: $deuda_bs Bs. &nbsp&nbsp&nbsp&nbsp&nbsp Rep.Form: $rep_form Bs.</b>\n";
}
echo "</td>\n";
echo "<td> &nbsp</td>\n"; 
echo "</tr>\n";
########################################
#-------- NUEVA PRELIQUIDACION --------# 
########################################				 					 
echo "<tr>\n";
echo "<td> &nbsp</td>\n"; 
echo "<td align=\"center\">\n";
if ($calcular_urbano) {
	echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=62&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
} elseif ($calcular_rural) {
	echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=52&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
} elseif (($calcular_transfer_urbano) or ($calcular_transfer_rural)) {
	echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=69&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
}
if (($calcular_urbano) or ($calcular_transfer_urbano)) {
	echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";
} elseif (($calcular_rural) or ($calcular_transfer_rural)) {
	echo "<input name=\"id_predio_rural\" type=\"hidden\" value=\"$id_predio_rural\">\n";
}
echo "<input name=\"gestion\" type=\"hidden\" value=\"$gestion\">\n";
echo "<input name=\"submit\" type=\"hidden\" value=\"Liquidar\">\n";
echo "<input name=\"estatus\" type=\"hidden\" value=\"$estatus\">\n";
echo "<input name=\"imp_neto\" type=\"hidden\" value=\"$imp_neto\">\n";
echo "<input name=\"fecha_venc_preliquid\" type=\"hidden\" value=\"$fecha_venc_preliquid\">\n";
if ($solo_empresa) {
	echo "<input name=\"solo_empresa\" type=\"hidden\" value=\"$valor_en_libros\">\n";
}
echo "<input name=\"exen_selected\" type=\"hidden\" value=\"$exen_select\">\n";
echo "<input name=\"condon_selected\" type=\"hidden\" value=\"$condon_select\">\n";
echo "<input name=\"descont_exen\" type=\"hidden\" value=\"$descont_exen\">\n";
if (($calcular_transfer_urbano) or ($calcular_transfer_rural)) {
	echo "<input name=\"modo_trans\" type=\"hidden\" class=\"smallText\" value=\"$modo_trans\">\n";
	echo "<input name=\"min_num\" type=\"hidden\" class=\"smallText\" value=\"$min_num\">\n";
	echo "<input name=\"not_nom\" type=\"hidden\" class=\"smallText\" value=\"$not_nom\">\n";
	echo "<input name=\"not_num\" type=\"hidden\" class=\"smallText\" value=\"$not_num\">\n";
	echo "<input name=\"not_cls\" type=\"hidden\" class=\"smallText\" value=\"$not_cls\">\n";
	echo "<input name=\"not_exp\" type=\"hidden\" class=\"smallText\" value=\"$not_exp\">\n";
	echo "<input name=\"min_val\" type=\"hidden\" class=\"smallText\" value=\"$min_val\">\n";
	echo "<input name=\"min_mon\" type=\"hidden\" class=\"smallText\" value=\"$min_mon\">\n";
	echo "<input name=\"min_fech\" type=\"hidden\" class=\"smallText\" value=\"$min_fech\">\n";
	echo "<input name=\"comprador\" type=\"hidden\" class=\"smallText\" value=\"$id_comp\">\n";
	echo "<input name=\"comprador2\" type=\"hidden\" class=\"smallText\" value=\"$id_comp2\">\n";

}

if ($nivel > 2) {
	echo "<input name=\"preliquid\" type=\"submit\" class=\"smallText\" value=\"PRE-LIQUIDACION\">\n";
}
echo "</form>\n";
echo "</td>\n";
echo "<td> &nbsp</td>\n";   #Col. 3
echo "</tr>\n";

########################################
#----- REGISTRAR PRE-LIQUIDACION ------#
########################################
if (($preliquid_exists) and (!isset($_POST["modo_trans"]))) {
	echo "<tr>\n";
	echo "<td> &nbsp</td>\n";   #Col. 1  	 
	echo "<td valign=\"top\" height=\"40\">\n";   #Col. 2
	if (!$forma_pago_cancelado) {
		echo "<fieldset><legend>Registrar Pre-Liquidación</legend>\n";
	} else {
		echo "<fieldset><legend>Pre-Liquidación cancelada</legend>\n";
	}
	echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=62&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
	echo "<table border=\"0\" width=\"100%\">\n";   # 3 TColumnas 
	### PRE-LIQUIDACIONES IMPRESAS ###
	echo "<tr>\n";
	echo "<td> &nbsp </td>\n";   #TCol. 1
	echo "<td align=\"center\" class=\"bodyTextD\">\n";   #TCol. 2	
	echo "<table border=\"0\" width=\"100%\" id=\"registros2\">\n";   # 5 TColumnas
	echo "<tr>\n";
	echo "<td width=\"4%\" class=\"bodyTextH\"> &nbsp </td>\n";
	echo "<td width=\"16%\" align=\"center\" class=\"bodyTextH\"><b>FOLIO</b></td>\n";
	echo "<td width=\"20%\" align=\"center\" class=\"bodyTextH\"><b>CONCEPTO</b></td>\n";
	echo "<td width=\"20%\" align=\"center\" class=\"bodyTextH\"><b>FECHA IMP.</b></td>\n";
	echo "<td width=\"20%\" align=\"center\" class=\"bodyTextH\"><b>CUOTA</b></td>\n";
	echo "<td width=\"20%\" align=\"center\" class=\"bodyTextH\"><b>FECHA VENC.</b></td>\n";
	echo "</tr>\n";
	if (!$forma_pago_cancelado) {
		### MOSTRAR PRE-LIQUIDACIONES EMPRESAS ###	    	 	
		$i = 0;
		while ($i < $no_de_preliquid) {
			echo "<tr>\n";
			$folio_texto = $folio_array[$i];
			if ($i == $no_de_preliquid - 1) {
				echo "<td align=\"center\" valign=\"top\"><input name=\"folio_select\" value=\"$folio_texto\" type=\"radio\" checked=\"checked\"></td>\n";
			} else {
				echo "<td align=\"center\" valign=\"top\"><input name=\"folio_select\" value=\"$folio_texto\" type=\"radio\"></td>\n";
			}
			echo "<td>$folio_texto</td>\n";
			echo "<td>$concepto[$i]</td>\n";
			$fecha_imp_temp = change_date($fech_imp_preliquid[$i]);
			echo "<td>$fecha_imp_temp</td>\n";
			$cuota_temp = $cuota_preliquid[$i] . ".00";
			echo "<td>$cuota_temp</td>\n";
			$fecha_venc_temp = change_date($fech_venc_preliquid[$i]);
			echo "<td>$fecha_venc_temp</td>\n";
			echo "</tr>\n";
			$i++;
		}
	} else {
		### MOSTRAR PRE-LIQUIDACION REGISTRADA ###
		echo "<tr>\n";
		echo "<td> &nbsp</td>\n";
		echo "<td>$folio_conban</td>\n";
		echo "<td>$concepto_conban</td>\n";
		echo "<td>$fech_imp_conban</td>\n";
		echo "<td>$cuota_conban</td>\n";
		echo "<td>$fech_venc_conban</td>\n";
		echo "</tr>\n";
	}
	echo "<tr>\n";
	echo "</table>\n";
	echo "</td>\n";
	echo "<td> &nbsp </td>\n";   #TCol. 3 
	echo "</tr>\n";
	### ESPACIO ###								 	
	echo "<tr>\n";
	echo "<td colspan=\"3\" style='font-family: Arial; font-size: 4pt'> &nbsp </td>\n";   #TCol. 2		
	echo "</tr>\n";
	### REGISTRO BANCO ###								 	
	echo "<tr>\n";
	echo "<td> &nbsp </td>\n";   #TCol. 1
	echo "<td align=\"center\" class=\"bodyTextD\">\n";   #TCol. 2													
	echo "<table border=\"0\" width=\"100%\" id=\"registros2\">\n";   # 3 TColumnas
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"center\" class=\"bodyTextH\"><b>FECHA PAGO</b></td>\n";
	echo "<td width=\"50%\" align=\"center\" class=\"bodyTextH\"><b>LUGAR DE PAGO (BANCO, ALCALDIA)</b></td>\n";
	echo "<td width=\"30%\" align=\"center\" class=\"bodyTextH\"><b>NO. DE BOLETA PAGO</b></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	if (!$forma_pago_cancelado) {
		### INGRESAR DATOS ###		 
		echo "<td><input type=\"text\" name=\"fecha_banco\" id=\"form_anadir0\" class=\"navText\" value=\"$fecha_banco\"></td>\n";
		echo "<td>\n";
		echo "<select class=\"navText\" name=\"nombre_banco\" size=\"1\">\n";
		$i = 0;
		while ($i <= $check_bancos) {
			if ($banco_nombre[$i] == $banco_select) {
				echo "<option id=\"form0\" value=\"$banco_nombre[$i]\" selected=\"selected\"> $banco_nombre[$i]</option>\n";
			} else {
				echo "<option id=\"form0\" value=\"$banco_nombre[$i]\"> $banco_nombre[$i]</option>\n";
			}
			$i++;
		}
		echo "</select>\n";
		echo "</td>\n";
		echo "<td><input type=\"text\" name=\"no_boleta_banco\" id=\"form_anadir0\" class=\"navText\" value=\"$no_boleta_banco\"></td>\n";
	} else {
		### MOSTRAR DATOS INGRESADOS ###	 
		echo "<td>$fecha_pago_conban</td>\n";
		echo "<td>&nbsp $nombre_banco_conban</td>\n";
		echo "<td>&nbsp $no_boleta_banco_conban</td>\n";
	}
	echo "<tr>\n";
	echo "</table>\n";
	echo "</td>\n";
	echo "<td> &nbsp </td>\n";
	echo "</tr>\n";
	### ERROR ###							 							
	if ((isset($_POST["registro"])) and ($_POST["registro"] == "REGISTRAR") and (!$registro_valido)) {
		echo "<tr>\n";
		echo "<td align=\"center\" colspan=\"3\">\n";
		echo "<font color='red'>$mensaje_de_error_registro</font>\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
	### BOTON REGISTRAR ###	
	if (!$forma_pago_cancelado) {
		echo "<tr>\n";
		echo "<td align=\"center\" colspan=\"3\">\n";
		if ($calcular_urbano) {
			echo "<input name=\"id_inmu\" type=\"hidden\" class=\"smallText\" value=\"$id_inmu\">\n";
		} elseif ($calcular_rural) {
			echo "<input name=\"id_predio_rural\" type=\"hidden\" class=\"smallText\" value=\"$id_predio_rural\">\n";
		}
		echo "<input name=\"gestion\" type=\"hidden\" class=\"smallText\" value=\"$gestion\">\n";
		echo "<input name=\"registro\" type=\"submit\" class=\"smallText\" value=\"REGISTRAR\">\n";
		echo "</td>\n";
	}
	echo "</tr>\n";
	echo "</table>\n";
	echo "</fieldset>\n";
	echo "</form>\n";
	echo "</td>\n";
	echo "<td> &nbsp</td>\n";   #Col. 3
	echo "</tr>\n";
}
echo "</table>\n";
?>