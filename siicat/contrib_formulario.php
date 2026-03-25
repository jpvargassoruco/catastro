
<table border="0" align="center" cellpadding="0" width="800px" height="100%">
	<tr height="40px">
		<td width="5%"> &nbsp</td>  	    
		<td align="center" valign="center" height="40" width="90%" class="pageName">
		Registrar Contribuyente
		</td>
		<td width="5%"> &nbsp</td> 	 
	</tr>
	<?php	
	echo "<form name=\"form1\" method=\"post\" action=\"index.php?mod=122&id=$session_id\" accept-charset=\"utf-8\">\n";
	
	##################################################
	#------------------- P.M.C. ---------------------#
	##################################################
	?>	
	<tr>
	<td> &nbsp</td>
	<td valign="top" height="40">
		<fieldset><legend>Padron Municipal</legend>
			<table border="0" width="100%">
				<tr>
					<td align="right" colspan="9" class="bodyText"></td>
				</tr>
				<tr>
					<td width="1%"></td>
					<td align="center" width="24%" class="bodyTextH">Patron Municipal</td>
					<td align="center" width="25%" class="bodyTextD">
					<input type="text" name="con_pmc" id="form_anadir1" class="navText" maxlength="$max_strlen_pmc" value="<?php echo $con_pmc ?>"></td>
					<td width="1%"></td>
					<td align="center" width="24%" class="bodyTextH">Padron Antiguo:</td>
					<td align="center" width="24%" class="bodyTextD">
					<input type="text" name="pmc_ant" id="form_anadir1" class="navText" maxlength="$max_strlen_pmc" value="<?php echo $pmc_ant ?>"></td>
					<td width="1%"></td>
				</tr>
				<tr>
					<td align="center" colspan="9" class="bodyText">
					Si deja en blanco el campo del Patron Municipal el sistema asignará un número correlativo!
					</td>
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
							<option value="PER">Persona Natural</option>
							<option value="EMP">Persona Juridica</option>
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
echo "<select class=\"navText\" name=\"dir_tipo\" size=\"1\">\n";
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
echo "<input type=\"text\" name=\"dir_nom\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_nom\" value=\"$dir_nom_texto\"></td>\n";
echo "<td align=\"center\" width=\"5%\" class=\"bodyTextH\">No</td>\n";
echo "<td align=\"center\" width=\"10%\" class=\"bodyTextD\">\n";
echo "<input type=\"text\" name=\"dir_num\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_num\" value=\"$dom_num\"></td>\n";
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
echo "<input type=\"text\" name=\"dir_edif\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_edif\" value=\"$dom_edif_texto\"></td>\n";
echo "<td align=\"center\" width=\"4%\" class=\"bodyTextH\">Bloque</td>\n";
echo "<td align=\"center\" width=\"3%\" class=\"bodyTextD\">\n";
echo "<input type=\"text\" name=\"dir_bloq\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_bloq\" value=\"$dom_bloq\"></td>\n";
echo "<td align=\"center\" width=\"4%\" class=\"bodyTextH\">Piso</td>\n";
echo "<td align=\"center\" width=\"3%\" class=\"bodyTextD\">\n";
echo "<input type=\"text\" name=\"dir_piso\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_piso\" value=\"$dom_piso\"></td>\n";
echo "<td align=\"center\" width=\"4%\" class=\"bodyTextH\">Apto.</td>\n";
echo "<td align=\"center\" width=\"3%\" class=\"bodyTextD\">\n";
echo "<input type=\"text\" name=\"dir_apto\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_dir_apto\" value=\"$dom_apto\"></td>\n";
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
echo "<input type=\"date\" name=\"con_fecnac\" id=\"form_anadir1\" class=\"navText\" maxlength=\"20\" value=\"$con_fecnac\"></td>\n";
echo "<td width=\"1%\"></td>\n";
echo "<td align=\"center\" width=\"10%\" class=\"bodyTextH\">Telefono(s)</td>\n";
echo "<td align=\"center\" width=\"10%\" class=\"bodyTextD\">\n";
echo "<input type=\"text\" name=\"con_tel\" id=\"form_anadir1\" class=\"navText\" maxlength=\"20\" value=\"$con_tel\"></td>\n";
echo "<td width=\"1%\"></td>\n";
echo "<td align=\"center\" width=\"10%\" class=\"bodyTextH\">Actividad </td>\n";
echo "<td align=\"center\" width=\"17%\" class=\"bodyTextD\">\n";
echo "<input type=\"text\" name=\"med_agu\" id=\"form_anadir1\" class=\"navText\" maxlength=\"10\" value=\"$act_con\"></td>\n";
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
		echo "<button name=\"submit\" type=\"submit\" value=\"Registrar\" class=\"custom-button\">Registrar</button>\n";
	}
	echo "</td>\n";
echo "</tr>\n";		
?>

</form>
<tr height="100%"></tr>
</table>
