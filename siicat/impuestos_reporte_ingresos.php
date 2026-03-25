<?php
################################################################################
#                     EL SIGUIENTE CODIGO FUE CREADO POR:                      #
#         MERCATORGIS - SISTEMAS DE INFORMACION GEOGRAFICA Y DE CATASTRO       #
#     ***** http://www.mercatorgis.com  *****  info@mercatorgis.com *****      #
################################################################################

$accion = $submit_selected = "";
// default both dates to today's date instead of relying on $fecha2
$fecha_inicio = date('Y-m-d');
$fecha_final = date('Y-m-d');
$error = false;
# Pagos Individuales (PI), Ingresos Recibidos (IR), Entidades de Cobranza (EC), Boletas Impresas (BI), Boletas con Sello (BS), Montos Adeudados (MA)
$selected_pi = $selected_pi_ipbi = $selected_pi_ipa = $selected_pi_ipbi_trans = $selected_pi_ipa_trans = $selected_pi_pat = $selected_pi_tasas = $selected_pi_tasas2 = $selected_ir = $selected_ir2 = $selected_rr = $selected_rr1 = $selected_rr2 = $selected_ec = $selected_ma = $selected_bi = $selected_bs = "";
########################################
#---------- CHEQUEAR FECHAS -----------#
########################################	   
// With <input type="date"> we get a valid format; just verify the range
if (isset($_POST["fecha_inicio"])) {
	$fecha_inicio = $_POST["fecha_inicio"];
}
if (isset($_POST["fecha_final"])) {
	$fecha_final = $_POST["fecha_final"];
}
if (isset($_POST["fecha_inicio"]) && isset($_POST["fecha_final"])) {
	if ($fecha_inicio > $fecha_final) {
		$error = true;
		$mensaje_de_error = "Error: La fecha de inicio debe ser menor o igual a la fecha final!";
	}
}
########################################
#---------- TIPO DE REPORTE -----------#
########################################
if ((isset($_POST["tipo"])) AND (!$error)) {
	#$accion = "reportes";
	$tipo_reporte = $_POST["tipo"];
	$submit_selected = $_POST["submit"];
	if ($tipo_reporte == "MA") {
		$selected_ma = pg_escape_string('selected = "selected"');
		include "siicat_impuestos_reporte_montos_adeudados.php";
	}
	if ($tipo_reporte == "BI") {
		$selected_bi = pg_escape_string('selected = "selected"');
		include "siicat_impuestos_reporte_boletas_impresas.php";
	}
	if ($tipo_reporte == "PI") {
		$selected_pi = pg_escape_string('selected = "selected"');
		$titulo_reporte = "";
		include "siicat_impuestos_reporte_pagos_individuales.php";
	}
	if ($tipo_reporte == "PI_IPBI") {
		$selected_pi_ipbi = pg_escape_string('selected = "selected"');
		$titulo_reporte = "- IPBI";
		include "siicat_impuestos_reporte_pagos_individuales.php";
	}
	if ($tipo_reporte == "PI_IPA") {
		$selected_pi_ipa = pg_escape_string('selected = "selected"');
		$titulo_reporte = "- IPA";
		include "siicat_impuestos_reporte_pagos_individuales.php";
	}
	if ($tipo_reporte == "PI_IPBI_TRANS") {
		$selected_pi_ipbi_trans = pg_escape_string('selected = "selected"');
		$titulo_reporte = "- TRANSFERENCIA URBANA";
		include "impuestos_reporte_pagos_individuales.php";
	}
	if ($tipo_reporte == "PI_IPA_TRANS") {
		$selected_pi_ipa_trans = pg_escape_string('selected = "selected"');
		$titulo_reporte = "- TRANSFERENCIA RURAL";
		include "siicat_impuestos_reporte_pagos_individuales.php";
	}
	if ($tipo_reporte == "PI_PAT") {
		$selected_pi_pat = pg_escape_string('selected = "selected"');
		$titulo_reporte = "- PATENTES";
		include "siicat_impuestos_reporte_pagos_individuales.php";
	}
	if ($tipo_reporte == "PI_TASAS") {
		$selected_pi_tasas = pg_escape_string('selected = "selected"');
		$titulo_reporte = "- TASAS";
		include "siicat_impuestos_reporte_pagos_individuales.php";
	}
	if ($tipo_reporte == "PI_TASAS2") {
		$selected_pi_tasas2 = pg_escape_string('selected = "selected"');
		$titulo_reporte = "- TASAS (por fecha de impresi�n)";
		include "siicat_impuestos_reporte_pagos_individuales.php";
	}
	if ($tipo_reporte == "IR") {
		$selected_ir = pg_escape_string('selected = "selected"');
		$titulo_reporte = "LISTADO MAYORIZADO POR MODULO DE PAGO";
		include "siicat_impuestos_reporte_ingresos_recibidos.php";
	}
	if ($tipo_reporte == "IR2") {
		$selected_ir2 = pg_escape_string('selected = "selected"');
		$titulo_reporte = "LISTADO MAYORIZADO POR MODULO DE PAGO EN SIIM/SIICAT";
		include "siicat_impuestos_reporte_ingresos_recibidos_2.php";
	}
	if ($tipo_reporte == "RR") {
		$selected_rr = pg_escape_string('selected = "selected"');
		$titulo_reporte = "LISTADO MAYORIZADO POR RUBROS DE RECAUDACION";
		include "siicat_impuestos_reporte_rubros_de_recaudacion.php";
	}
	if ($tipo_reporte == "RR1") {
		$selected_rr1 = pg_escape_string('selected = "selected"');
		$titulo_reporte = "LISTADO MAYORIZADO POR RUBROS DE RECAUD. (SOLO SIIM)";
		include "siicat_impuestos_reporte_rubros_de_recaudacion.php";
	}
	if ($tipo_reporte == "RR2") {
		$selected_rr2 = pg_escape_string('selected = "selected"');
		$titulo_reporte = "LISTADO MAYORIZADO POR RUBROS DE RECAUD. (SOLO SIICAT)";
		include "siicat_impuestos_reporte_rubros_de_recaudacion.php";
	}
	if ($tipo_reporte == "BS") {
		$selected_bs = pg_escape_string('selected = "selected"');
		include "siicat_impuestos_reporte_boletas_con_sello.php";
	}
	if ($tipo_reporte == "EC") {
		$selected_ec = pg_escape_string('selected = "selected"');
		include "siicat_impuestos_reporte_pagos_ent_cob.php";
	}
	if ($submit_selected == "EXCEL") {
		if (($tipo_reporte == "RR") OR ($tipo_reporte == "RR1") OR ($tipo_reporte == "RR2")) {
			include "siicat_impuestos_reporte_rubros_de_recaudacion_excel.php";
		} elseif ($tipo_reporte == "IR") {
			include "siicat_impuestos_reporte_ingresos_recibidos_excel.php";
		} elseif ($tipo_reporte == "MA") {
			include "siicat_impuestos_reporte_montos_adeudados_excel.php";
		} elseif ($tipo_reporte == "BI") {
			include "siicat_impuestos_reporte_boletas_impresas_excel.php";
		} elseif (($tipo_reporte == "PI") OR ($tipo_reporte == "PI_IPBI") OR ($tipo_reporte == "PI_IPA") OR ($tipo_reporte == "PI_IPBI_TRANS") OR ($tipo_reporte == "PI_IPA_TRANS") OR ($tipo_reporte == "PI_PAT") OR ($tipo_reporte == "PI_TASAS") OR ($tipo_reporte == "PI_TASAS2")) {
			include "impuestos_reporte_pagos_individuales_excel.php";
		} elseif ($tipo_reporte == "BS") {
			include "siicat_impuestos_reporte_boletas_con_sello_excel.php";
		} elseif ($tipo_reporte == "EC") {
			include "siicat_impuestos_reporte_pagos_ent_cob_excel.php";
		}
	}
} else {
	// default to Pagos Transferencia Urbana when the form loads with no POST
	$selected_pi_ipbi_trans = pg_escape_string('selected = "selected"');
	$tipo_reporte = "PI_IPBI_TRANS";
}
########################################
#------------ CHEQUEAR USER -----------#
########################################	   
if (isset($_POST["user"])) {
	$usuario_reporte = $_POST["user"];
}
########################################
#--------- CHEQUEAR USUARIOS ----------#
########################################
$sql = "SELECT DISTINCT userid_reg FROM imp_control_banco ORDER BY userid_reg";
$check_usuarios = pg_num_rows(pg_query($sql));
if ($check_usuarios > 0) {
	$result = pg_query($sql);
	$i = 0;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		foreach ($line as $col_value) {
			$usuario_control[$i] = $col_value;
			$i++;
		}
	}
}
########################################
#--------- GENERAR ID-REPORTE ---------#
########################################
$f1 = change_date_to_ymd_10char($fecha_inicio);
$f2 = change_date_to_ymd_10char($fecha_final);
$id_reporte = $tipo_reporte . "_" . $f1 . "_" . $f2;
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	

echo "<td>\n";
echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" >\n";
	echo "<tr>\n";
	echo "<td width=\"5%\"> &nbsp</td>\n";     
	echo "<td align=\"center\" valign=\"center\" height=\"10\" width=\"80%\" class=\"pageName\">Reportes</td>\n";
	echo "<td width=\"10%\"> &nbsp</td>\n";  		 
	echo "</tr>\n";
	echo "<tr height=\"10px\">\n";
	echo "<td> &nbsp</td>\n";   
	echo "<td align=\"left\" valign=\"center\" height=\"10\">\n";
	echo "<fieldset><legend>Seleccione la fecha y el reporte</legend>\n";
	echo "<table border=\"0\" width=\"100%\">\n"; 
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=72&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";
			echo "<tr>\n";
				echo "<td align=\"left\" class=\"bodyTextD_Small\">Usuario:</td>\n";
				echo "<td class=\"bodyTextD_Small\" colspan=\"4\">Rango de Fecha:</td>\n";
				echo "<td> &nbsp</td>\n";	 
				echo "<td class=\"bodyTextD_Small\" colspan=\"1\">Reporte:</td>\n";
				echo "<td> Opciones</td>\n"; 		   
			echo "</tr>\n";

			echo "<tr>\n";

				echo "<td align=\"left\" width=\"14%\" class=\"bodyTextD_Small\">\n";   #Col. 1 		
					echo "<select class=\"navText\" name=\"user\" size=\"1\">\n";		
						echo "<option id=\"form0\" value=\"Todos\" selected = \"selected\"> Todos</option>\n";
						$i = 0;
						while ($i < $check_usuarios) {
						echo "<option id=\"form0\" value=\"$usuario_control[$i]\"> $usuario_control[$i]</option>\n"; 	 
						$i++;
						}
					echo "</select>\n";	
				echo "</td>\n"; 

				echo "<td align=\"left\" width=\"5%\" class=\"bodyTextD_Small\"> Del </td>\n"; 
				echo "<td align=\"left\" width=\"13%\" class=\"bodyTextD_Small\">\n";  
				echo "<input type=\"date\" name=\"fecha_inicio\" id=\"form_anadir0\" class=\"navText\" maxlength=\"10\" value=\"$fecha_inicio\">\n";
				echo "</td>\n";
				echo "<td align=\"left\" width=\"3%\" class=\"bodyTextD_Small\"> al </td>\n"; 
				echo "<td align=\"left\" width=\"13%\" class=\"bodyTextD_Small\">\n";	  
				echo "<input type=\"date\" name=\"fecha_final\" id=\"form_anadir0\" class=\"navText\" maxlength=\"10\" value=\"$fecha_final\">\n";
				echo "</td>\n";
				echo "<td width=\"2%\"> &nbsp</td>\n";  	
				echo "<td align=\"left\" width=\"34%\">\n"; 
					echo "<select class=\"navText\" name=\"tipo\" size=\"1\">\n";
						echo "<option id=\"form0\" value=\"RR\" $selected_rr> Pagos por Rubros de Recaudacion</option>\n";
						echo "<option id=\"form0\" value=\"RR1\" $selected_rr1> Pagos Rubros de Recaud. (solo SIIM)</option>\n";
						echo "<option id=\"form0\" value=\"RR2\" $selected_rr2> Pagos Rubros de Recaud. (solo SIICAT)</option>\n";
						echo "<option id=\"form0\" value=\"PI\" $selected_pi> Pagos Individuales Total </option>\n";
						echo "<option id=\"form0\" value=\"PI_IPBI\" $selected_pi_ipbi> Pagos IPBI</option>\n";
						echo "<option id=\"form0\" value=\"PI_IPA\" $selected_pi_ipa> Pagos IPA</option>\n";
						echo "<option id=\"form0\" value=\"PI_IPBI_TRANS\" $selected_pi_ipbi_trans> Pagos Transferencia Urbana</option>\n";
						echo "<option id=\"form0\" value=\"PI_IPA_TRANS\" $selected_pi_ipa_trans> Pagos Transferencia Rural</option>\n";
						echo "<option id=\"form0\" value=\"PI_PAT\" $selected_pi_pat> Pagos Patentes</option>\n";
						if (($db == "af") OR ($db == "br") OR ($db == "sc") OR ($db == "sf") OR ($db == "vi")) {
							echo "<option id=\"form0\" value=\"PI_TASAS\" $selected_pi_tasas> Pagos Tasas (por fecha de pago)</option>\n";
							echo "<option id=\"form0\" value=\"PI_TASAS2\" $selected_pi_tasas2> Pagos Tasas (por fecha de impresion)</option>\n";
						} else {
							echo "<option id=\"form0\" value=\"PI_TASAS\" $selected_pi_tasas> Pagos Tasas</option>\n";
						}
					echo "</select>\n";
				echo "</td>\n";
				echo "<td width=\"10%\">\n";
				echo "<input name=\"accion\" type=\"hidden\" class=\"smallText\" value=\"reportes\">\n";
				echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Ver\">\n";
				echo "</td>\n";
				echo "<td width=\"10%\">\n"; 	 		 
				echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"EXCEL\">\n";
				echo "</td>\n";
			echo "</tr>\n";
		echo "</form>\n";
	echo "</table>\n";
echo "</fieldset>\n";
echo "</td>\n";
echo "<td> &nbsp</td>\n";  			 
echo "</tr>\n";
if ($error) {
	echo "<tr>\n";
	echo "<td> &nbsp</td>\n"; 				 
	echo "<td align=\"center\" class=\"bodyTextD\">\n";      
	echo "<font color=\"red\">$mensaje_de_error</font>\n";
	echo "</td>\n";
	echo "<td> &nbsp</td>\n";  		
	echo "</tr>\n";
} elseif ($submit_selected == "Ver") {
	echo "<tr>\n";
	echo "<td colspan=\"3\">\n";  	 
	echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"770px\">\n";   # 3 Columnas
	echo "<tr>\n";
	echo "<td valign=\"top\">\n";   #Col. 1 
	if (($tipo_reporte == "IR") OR ($tipo_reporte == "IR2") OR ($tipo_reporte == "RR") OR ($tipo_reporte == "RR1") OR ($tipo_reporte == "RR2")) {
		include "siicat_impuestos_reporte_ingresos_recibidos_ver.php";
	} elseif ($tipo_reporte == "MA") {
		include "siicat_impuestos_reporte_montos_adeudados_ver.php";
	} elseif ($tipo_reporte == "BI") {
		include "siicat_impuestos_reporte_boletas_impresas_ver.php";
	} elseif (($tipo_reporte == "PI") OR ($tipo_reporte == "PI_IPBI") OR ($tipo_reporte == "PI_IPA") OR ($tipo_reporte == "PI_IPBI_TRANS") OR ($tipo_reporte == "PI_IPA_TRANS") OR ($tipo_reporte == "PI_PAT") OR ($tipo_reporte == "PI_TASAS") OR ($tipo_reporte == "PI_TASAS2")) {

		include "siicat_impuestos_reporte_pagos_individuales_ver.php";
	} elseif ($tipo_reporte == "BS") {
		include "siicat_impuestos_reporte_boletas_con_sello_ver.php";
	} elseif ($tipo_reporte == "EC") {
		#include "siicat_impuestos_reporte_pagos_ent_cob_ver.php";
	}
	echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/reporte$id_reporte.html\" id=\"content\" width=\"750px\" height=\"750px\" align=\"left\" scrolling=\"yes\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
	echo "</iframe>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</td>\n";
	echo "</tr>\n";
} elseif ($submit_selected == "EXCEL") {
	echo "<tr>\n";
	echo "<td colspan=\"3\" align=\"center\">\n";  #Col. 1-3	
	echo "<br />Bajar <a href=\"http://$server/tmp/$reportfile\">aqui</a> el reporte en formato EXCEL.<br />\n";
	echo "</td>\n";
	echo "</tr>\n";
}
?>