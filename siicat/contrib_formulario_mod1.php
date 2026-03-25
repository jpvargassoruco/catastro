
<table border="0" align="center" cellpadding="0" width="800px" height="100%">
	<tr height="40px">
		<td width="5%"> &nbsp</td>  	    
		<td align="center" valign="center" height="40" width="90%" class="pageName">
		<?php 
			echo (isset($accion) && $accion == "Modificar") ? "Modificar Contribuyente" : "Registrar Contribuyente"; 
		?>
		</td>
		<td width="5%"> &nbsp</td> 	 
	</tr>
	<?php	
	// Ensure commonly used form variables are defined to avoid notices
	$__defaults = array('con_pmc'=>'0','pmc_ant'=>'','con_raz'=>'','con_nit'=>0,'con_pat'=>'','con_mat'=>'','con_nom1'=>'','con_nom2'=>'','dom_dpto'=>'','dom_ciu'=>'','dom_bar'=>'','dom_tipo'=>'','dom_nom'=>'','dom_num'=>'','dom_edif'=>'','dom_bloq'=>'','dom_piso'=>'','dom_apto'=>'','con_fech_nac'=>'','con_tel'=>'','med_agu'=>'','med_luz'=>'','con_obs'=>'','doc_tipo'=>'','doc_num'=>'','doc_exp'=>'','con_tipo'=>'','con_act'=>0);
	foreach ($__defaults as $k => $v) {
		if (!isset($$k)) $$k = $v;
	}
	echo "<form name=\"form1\" method=\"post\" action=\"index.php?mod=" . ((isset($accion) && $accion == 'Modificar') ? '124' : '122') . "&id=$session_id\" accept-charset=\"utf-8\">\n";
	
	##################################################
	#------------------- P.M.C. ---------------------#
	##################################################
	?>	
	<tr>
	<td> &nbsp</td>
	<td valign="top" height="40">
		<fieldset><legend>Padron Municipal</legend>
			<table border="0" width="100%">
				<tr style="height:5px">
					<td colspan="9" class="bodyText"></td>
				</tr>
                <tr>
                    <td align="center" width="23%" class="bodyTextH">Patron Municipal</td>
                    <td align="center" width="23%" class="bodyTextD" style="padding-right:10px;">
                        <input type="text" name="con_pmc" class="navText"
                            maxlength="15" disabled value="<?php echo $con_pmc ?>">
                    </td>

                    <td align="center" width="23%" class="bodyTextH">Padron Antiguo:</td>
                    <td align="center" width="23%" class="bodyTextD" style="padding-left:10px;">
                        <input type="text" name="pmc_ant" class="navText"
                            maxlength="15" value="<?php echo $pmc_ant ?>">
                    </td>
                </tr>
				<tr style="height:5px">
					<td colspan="9" class="bodyText"></td>
				</tr>
			</table>
		</fieldset>
	</td>
	<td> &nbsp</td>
	</tr>
<?php
	if ($error1) {
		echo "<tr>\n";
		echo "<td> &nbsp</td>\n";
		echo "<td align=\"center\" class=\"alerta alerta-danger\"> $mensaje_de_error1 </td>\n";
		echo "<td> &nbsp</td>\n";
		echo "</tr>\n";
	}
##################################################
#------------ NOMBRE DEL CONTRIBUYENTE ----------#
##################################################
?>
<tr>
	<td> &nbsp</td>		 
	<td valign="top" height="40">
	<fieldset><legend>Nombre del Contribuyente</legend>
		<table border="0" width="100%">
			<tr>
				<td align="right" colspan="7" class="bodyText"></td>
			</tr>
			<tr>
				<td width="1%"></td>
					<td align="center" width="19%" >Tipo de Contribuyente</td>
					<td align="center" width="20%" >
						<select name="con_tipo" size="1">
						<option value="PER" <?php if (isset($con_tipo) && $con_tipo == 'PER') echo 'selected="selected"'; ?>>Persona Natural</option>
						<option value="EMP" <?php if (isset($con_tipo) && $con_tipo == 'EMP') echo 'selected="selected"'; ?>>Persona Juridica</option>
						</select>
					</td>
				<td align="center" width="13%" class="bodyTextH">Razon Social</td>
				<td align="center" width="25%" class="bodyTextD">
				<input type="text" name="con_raz"  class="navText" maxlength="30" value="<?php echo $con_raz; ?>"</td>
				<td align="center" width="8%" class="bodyTextH">NIT</td>
				<td align="center" width="11%" class="bodyTextD">
				<input type="text" name="con_nit"  class="navText" maxlength="30" value="<?php echo $con_nit; ?>"></td>
			</tr>
		</table>

	<table border="0" width="100%">
		<tr>
			<td width="1%"></td>
			<td align="left" colspan="8" class="bodyText">
			Ingresar Apellido y Nombre del Contribuyente (si es empresa, ingresar el nombre del representante):
			</td>
		</tr>
		<tr>
			<td width="1%"></td>
			<td align="center" width="25%" class="bodyTextH">Apellido Paterno</td>
			<td align="center" width="25%" class="bodyTextH">Apellido Materno</td>
			<td align="center" width="23%" class="bodyTextH">1er Nombre</td>
			<td align="center" width="23%" class="bodyTextH">2ndo Nombre</td>
		</tr>
		<tr>
			<td width="1%"></td>
			<td align="center" class="bodyTextD"><input type="text" name="con_pat"  class="navText" value="<?php echo $con_pat ?>"></td>
			<td align="center" class="bodyTextD"><input type="text" name="con_mat"  class="navText" value="<?php echo $con_mat ?>"></td>
			<td align="center" class="bodyTextD"><input type="text" name="con_nom1" class="navText" value="<?php echo $con_nom1 ?>"></td>
			<td align="center" class="bodyTextD"><input type="text" name="con_nom2"  class="navText" value="<?php echo $con_nom2 ?>"></td>
		</tr>
	</table>
</fieldset>
</td>
<td> &nbsp</td>
</tr>
<?php

if ($error2) {
	echo "<tr>\n";
	echo "<td></td>\n";
	echo "<td align=\"center\" class=\"alerta alerta-danger\"> $mensaje_de_error2 </td>\n";
	echo "<td></td>\n";
	echo "</tr>\n";
}
##################################################
#------- IDENTIFICACION DEL CONTRIBUYENTE -------#
##################################################
echo "<tr>\n";
echo "<td> &nbsp</td>\n";
echo "<td valign=\"top\" height=\"40\">\n";
echo "<fieldset><legend>Identificación del Contribuyente</legend>\n";
echo "<table border=\"0\" width=\"100%\">\n";
echo "<tr>\n";
echo "<td align=\"right\" colspan=\"6\" class=\"bodyText\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"center\" width=\"18%\" class=\"bodyTextH\">&nbsp Tipo de Identificación &nbsp</td>\n";
echo "<td align=\"center\" width=\"20%\" class=\"bodyTextD\">\n";
$doc_tipo = trim($doc_tipo);
$valores = get_abr('doc_tipo');
echo "<select class=\"navText\" name=\"doc_tipo\" size=\"1\">\n";
$i = 0;
foreach ($valores as $i => $j) {
	$texto = abr($valores[$i]);
	if ($valores[$i] == $doc_tipo) {
		echo "<option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n";
	} else {
		echo "<option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	}
	$i++;
}
echo "</select>\n";
echo "</td>\n";
echo "<td align=\"center\" width=\"18%\" class=\"bodyTextH\">No de Identificación</td>\n";
echo "<td align=\"center\" width=\"12%\" class=\"bodyTextD\">\n";
echo "<input type=\"text\" name=\"doc_num\" id=\"form_anadir1\" class=\"navText\" maxlength=\"15\" value=\"$doc_num\"></td>\n";   #Col. 5	
echo "<td align=\"center\" width=\"11%\" class=\"bodyTextH\">Expedido en</td>\n";
echo "<td align=\"center\" width=\"8%\" class=\"bodyTextD\">\n";
$valores = get_abr('doc_exp');
echo "<select class=\"navText\" name=\"doc_exp\" size=\"1\">\n";
$i = 0;
foreach ($valores as $i => $j) {
	$texto = abr($valores[$i]);
	if ($valores[$i] == $doc_exp) {
		echo "<option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n";
	} else {
		echo "<option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	}
	$i++;
}
echo "</select>\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</fieldset>\n";
echo "</td>\n";
echo "<td> &nbsp</td>\n";
echo "</tr>\n";
if ($error3) {
	echo "<tr>\n";
	echo "<td></td>\n";
	echo "<td align=\"center\" class=\"alerta alerta-danger\"> $mensaje_de_error3 </td>\n";
	echo "<td></td>\n";
	echo "</tr>\n";
}
##################################################
#------------------- DIRECCION ------------------#
##################################################
echo "<tr>\n";
echo "<td> &nbsp</td>\n";   #Col. 1 		  
echo "<td valign=\"top\" height=\"40\">\n";   #Col. 2 
echo "<fieldset><legend>Domicilio del Contribuyente</legend>\n";
echo "<table border=\"0\" width=\"100%\">\n";
echo "<tr>\n";
echo "<td align=\"right\" colspan=\"9\" class=\"bodyText\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"center\" width=\"13%\" class=\"bodyTextH\">Departamento</td>\n";
echo "<td align=\"center\" width=\"17%\" class=\"bodyTextD\">\n";
$dom_dpto = trim($dom_dpto);
$valores = get_abr('dom_dpto');
echo "<select class=\"navText\" name=\"dom_dpto\" size=\"1\">\n";
$i = 0;
foreach ($valores as $i => $j) {
	$texto = utf8_decode(abr($valores[$i]));
	if ($valores[$i] == $dom_dpto) {
		echo "<option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n";
	} else {
		echo "<option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	}
	$i++;
}
echo "</select>\n";
echo "</td>\n";
echo "<td align=\"center\" width=\"8%\" class=\"bodyTextH\">Ciudad</td>\n";
echo "<td align=\"center\" width=\"24%\" class=\"bodyTextD\">\n";
echo "<input type=\"text\" name=\"dom_ciu\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_ciu\" value=\"$dom_ciu\"></td>\n";
echo "<td width=\"1%\"></td>\n";
echo "<td align=\"center\" width=\"7%\" class=\"bodyTextH\">Barrio</td>\n";
echo "<td align=\"center\" width=\"29%\" class=\"bodyTextD\">\n";
echo "<input type=\"text\" name=\"dom_bar\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_bar\" value=\"$dom_bar\"></td>\n";
echo "<td width=\"1%\"></td>\n";
echo "</tr>\n";
echo "</table>\n";

echo "<table border=\"0\" width=\"100%\">\n";
echo "<tr>\n";
echo "<td align=\"right\" colspan=\"9\" class=\"bodyText\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"center\" width=\"10%\" class=\"bodyTextH\">Tipo</td>\n";   #Col. 2
echo "<td align=\"center\" width=\"10%\" class=\"bodyTextD\">\n";   #Col. 3
$valores = get_abr('dir_tipo');
echo "<select class=\"navText\" name=\"dom_tipo\" size=\"1\">\n";
$i = 0;
foreach ($valores as $i => $j) {
	$texto = utf8_decode(abr($valores[$i]));
	if ($valores[$i] == $dom_tipo) {
		echo "<option id=\"form0\" value=\"$valores[$i]\" selected=\"selected\"> $texto</option>\n";
	} else {
		echo "<option id=\"form0\" value=\"$valores[$i]\"> $texto</option>\n";
	}
	$i++;
}
echo "</select>\n";
echo "</td>\n";
echo "<td align=\"center\" width=\"8%\" class=\"bodyTextH\">Nombre</td>\n";
$dir_nom_texto = textconvert($dom_nom);
echo "<td align=\"center\" width=\"30%\" class=\"bodyTextD\">\n";
echo "<input type=\"text\" name=\"dom_nom\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_nom\" value=\"$dir_nom_texto\"></td>\n";
echo "<td align=\"center\" width=\"5%\" class=\"bodyTextH\">No</td>\n";
echo "<td align=\"center\" width=\"10%\" class=\"bodyTextD\">\n";
echo "<input type=\"text\" name=\"dom_num\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_num\" value=\"$dom_num\"></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "<table border=\"0\" width=\"100%\">\n";
echo "<tr>\n";
echo "<td align=\"right\" colspan=\"9\" class=\"bodyText\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"center\" width=\"7%\" class=\"bodyTextH\">Edificio</td>\n";
$dir_edif_texto = textconvert($dom_edif);
echo "<td align=\"center\" width=\"9%\" class=\"bodyTextD\">\n";
echo "<input type=\"text\" name=\"dom_edif\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_edif\" value=\"$dir_edif_texto\"></td>\n";
echo "<td align=\"center\" width=\"4%\" class=\"bodyTextH\">Bloque</td>\n";
echo "<td align=\"center\" width=\"3%\" class=\"bodyTextD\">\n";
echo "<input type=\"text\" name=\"dom_bloq\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_bloq\" value=\"$dom_bloq\"></td>\n";
echo "<td align=\"center\" width=\"4%\" class=\"bodyTextH\">Piso</td>\n";
echo "<td align=\"center\" width=\"3%\" class=\"bodyTextD\">\n";
echo "<input type=\"text\" name=\"dom_piso\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_piso\" value=\"$dom_piso\"></td>\n";
echo "<td align=\"center\" width=\"4%\" class=\"bodyTextH\">Apto.</td>\n";
echo "<td align=\"center\" width=\"3%\" class=\"bodyTextD\">\n";
echo "<input type=\"text\" name=\"dom_apto\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_apto\" value=\"$dom_apto\"></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</fieldset>\n";
echo "</td>\n";
echo "<td> &nbsp</td>\n";
echo "</tr>\n";
if ($error4) {
	echo "<tr>\n";
	echo "<td></td>\n";
	echo "<td align=\"center\" class=\"alerta alerta-danger\"> $mensaje_de_error4 </td>\n";
	echo "<td></td>\n";
	echo "</tr>\n";
}
// Normalize date for HTML5 date input (YYYY-MM-DD). Accepts DD/MM/YYYY, YYYY-MM-DD, DD-MM-YYYY or other parseable formats.
$con_fech_nac_input = "";
$tmp_date = trim($con_fech_nac);
if ($tmp_date != "" && $tmp_date != "1900-01-01") {
	if (strpos($tmp_date, "/") !== false) {
		$p = explode("/", $tmp_date);
		if (count($p) == 3) {
			$con_fech_nac_input = sprintf("%04d-%02d-%02d", $p[2], $p[1], $p[0]);
		}
	} elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tmp_date)) {
		$con_fech_nac_input = $tmp_date;
	} elseif (preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $tmp_date, $m)) {
		$con_fech_nac_input = $m[3] . "-" . $m[2] . "-" . $m[1];
	} else {
		$ts = strtotime($tmp_date);
		if ($ts !== false) $con_fech_nac_input = date("Y-m-d", $ts);
	}
}
echo "<!-- DEBUG: con_fech_nac raw='$con_fech_nac' input='$con_fech_nac_input' -->\n";
##################################################
#--------------- DATOS ADICIONALES --------------#
##################################################
echo "<tr>\n";
echo "<td> &nbsp</td>\n";   #Col. 1 			  	 
echo "<td valign=\"top\" height=\"40\">\n";   #Col.2 
echo "<fieldset><legend>Datos Adicionales</legend>\n";
echo "<table border=\"0\" width=\"100%\">\n";
    echo "<tr>\n";
    echo "<td align=\"right\" colspan=\"10\" class=\"bodyText\"></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td width=\"1%\"></td>\n";
    echo "<td align=\"center\" width=\"10%\" class=\"bodyTextH\">Fec.Nacimiento</td>\n";
    echo "<td align=\"center\" width=\"10%\" class=\"bodyTextD\">\n";
    echo "<input type=\"date\" name=\"con_fech_nac\" id=\"form_anadir1\" class=\"navText\" maxlength=\"20\" value=\"$con_fech_nac_input\"></td>\n";
    echo "<td width=\"1%\"></td>\n";
    echo "<td align=\"center\" width=\"10%\" class=\"bodyTextH\">Telefono(s)</td>\n";
    echo "<td align=\"center\" width=\"10%\" class=\"bodyTextD\">\n";
    echo "<input type=\"text\" name=\"con_tel\" id=\"form_anadir1\" class=\"navText\" maxlength=\"20\" value=\"$con_tel\"></td>\n";
    echo "<td width=\"1%\"></td>\n";
echo "<td align=\"center\" width=\"10%\" class=\"bodyTextH\">Activo</td>\n";
echo "<td align=\"center\" width=\"17%\" class=\"bodyTextD\">\n";
// Hidden field to ensure 0 is submitted when checkbox is unchecked
echo "<input type=\"hidden\" name=\"con_act\" value=\"0\">\n";
$checked = (($con_act == '1' || $con_act == 1) ? ' checked' : '');
echo "<input type=\"checkbox\" name=\"con_act\" id=\"con_act\" value=\"1\"$checked> Activo</td>\n";
    echo "<td width=\"1%\"></td>\n";
    echo "</tr>\n";
echo "</table>\n";
echo "</fieldset>\n";
echo "</td>\n";
echo "<td> &nbsp</td>\n";
echo "</tr>\n";
##################################################
#----------------- OBSERVACIONES ----------------#
##################################################
echo "<tr>\n";
echo "<td> &nbsp</td>\n";
echo "<td valign=\"top\" height=\"40\">\n";
echo "<fieldset><legend>Observaciones</legend>\n";
echo "<table border=\"0\" width=\"100%\">\n";
echo "<tr>\n";
echo "<td align=\"right\" colspan=\"4\" class=\"bodyText\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"1%\"></td>\n";
echo "<td align=\"center\" width=\"12%\" class=\"bodyTextH\">Observaciones</td>\n";
echo "<td align=\"center\" width=\"86%\" class=\"bodyTextD\">\n";
echo "<input type=\"text\" name=\"con_obs\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_obs\" value=\"$con_obs\"></td>\n";   #Col. 3  	 
echo "<td width=\"1%\"></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</fieldset>\n";
echo "</td>\n";
echo "<td> &nbsp</td>\n";
echo "</tr>\n";
if ($error5) {
	echo "<tr>\n";
	echo "<td></td>\n";
	echo "<td align=\"center\" class=\"alerta alerta-danger\"> $mensaje_de_error5 </td>\n";
	echo "<td></td>\n";
	echo "</tr>\n";
}
echo "<tr>\n";
	echo "<td align=\"center\" height=\"40\" colspan=\"3\">\n";
	if ($bottom ) {
		if (isset($accion) && $accion == "Modificar") {
			echo "<input name=\"id_contrib\" type=\"hidden\" value=\"$id_contrib\">\n";
			echo "<button name=\"submit\" type=\"submit\" value=\"Modificar\" class=\"custom-button\">Modificar</button>\n";
		}
	}
	echo "</td>\n";
echo "</tr>\n";		
?>

</form>
<tr height="100%"></tr>
</table>
