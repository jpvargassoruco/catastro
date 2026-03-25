<?php
################################################################################
#-------------------------- VER SI EXISTE EL PREDIO ---------------------------#
################################################################################
$sql = "SELECT cod_uv FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check_predio = pg_num_rows(pg_query($sql));

################################################################################
#--------------------- SACAR INFORMACION DE LA BASE DE DATOS ------------------#
################################################################################	

# FOTOS
$fotos1 = preg_replace('/[^0-9]/', '', $cod_cat) . ".JPG";
$fotos2 = preg_replace('/[^0-9]/', '', $cod_cat) . "A.JPG";


if (file_exists("fotos/" . $fotos1)) {
} else {
	$fotos1 = "blanca.jpg";
}

if (file_exists("fotos/" . $fotos2)) {
} else {
	$fotos2 = "blanca.jpg";
}


# FUNCION GET_ZONA
$ben_zona = get_zona($id_inmu);
if ($ben_zona == "0") {
	$ben_zona = "-";
}
# FUNCION GET_BARRIO
$barrio = get_barrio($id_inmu);
if ($barrio == "0") {
	$barrio = "-";
}
include "siicat_planos_leer_datos.php";
################################################################################
#-------------------------- AJUSTAR FECHA FORMATO LARGO -----------------------#
################################################################################
if (empty($apr_plafec)) {
	$ano_aprpla = "XXXX";
	$mes_aprpla = "XX";
	$dia_aprpla = "XX";
} else {
	$ano_aprpla = substr($apr_plafec, -10, 4);
	$nmes = substr($apr_plafec, -5, 2);
	$mes_aprpla = monthconvert($nmes);
	$dia_aprpla = substr($apr_plafec, -2, 2);
}

################################################################################
#-------------------------- DEFINIR ZONA DEL PREDIO ---------------------------#
################################################################################
$zona = get_zona_brujula($cod_cat, $centro_del_pueblo_para_zonas_x, $centro_del_pueblo_para_zonas_y);
if ($zona === "SE") {
	$zona = "SUR ESTE";
} elseif ($zona === "SO") {
	$zona = "SUR OESTE";
} elseif ($zona === "SE") {
	$zona = "SUR ESTE";
} elseif ($zona === "NE") {
	$zona = "NOR ESTE";
} elseif ($zona === "NO") {
	$zona = "NOR OESTE";
} elseif ($zona === "N") {
	$zona = "NORTE";
} elseif ($zona === "S") {
	$zona = "SUR";
} elseif ($zona === "E") {
	$zona = "ESTE";
} elseif ($zona === "O") {
	$zona = "OESTE";
}

$prop_string = $propietario = get_propietarios_con_ci_from_id_inmu($id_inmu);

$prop_string = utf8_encode($prop_string);

if (strlen($prop_string) > 105) {
	$font_size_prop = "7pt";
} elseif (strlen($prop_string) > 95) {
	$font_size_prop = "8pt";
} else {
	$font_size_prop = "9pt";
}

$ter_sdoc = 20;
if ($ter_sdoc == "") {
	$ter_sdoc = "---";
}
########################################
#------- CALCULAR AREA PREDIO ---------#
########################################
$sql = "SELECT area(the_geom) FROM predios_ocha WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result = pg_query($sql);
$value = pg_fetch_array($result, null, PGSQL_ASSOC);
$area = ROUND($value['area'], 2);
pg_free_result($result);
################################################################################
#---------------------------------- NOTA --------------------------------------#
################################################################################
$sql = "SELECT nota_plano FROM imp_base";
$result_nota = pg_query($sql);
$info = pg_fetch_array($result_nota, null, PGSQL_ASSOC);
$nota_plano_catastral = utf8_decode($info['nota_plano']);
pg_free_result($result_nota);
########################################
#----- CALCULAR AREA EDIFICACIONES ----#
########################################
$sql = "SELECT area(the_geom) FROM edificaciones WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$check = pg_num_rows(pg_query($sql));
if ($check == 0) {
	$edi_area = 0;
} else {
	$result = pg_query($sql);
	$edi_area = 0;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		foreach ($line as $col_value) {
			$edi_area = $edi_area + $col_value;
		}
	} # END_OF_WHILE	
	$edi_area = ROUND($edi_area, 2);
	pg_free_result($result);
}
################################################################################
#------------------------------- COLINDANTES ----------------------------------#
################################################################################	
$id_predio = get_id_predio($cod_geo, $cod_uv, $cod_man, $cod_pred);
$sql = "SELECT * FROM colindantes WHERE id_predio = '$id_predio'";
$check_col = pg_num_rows(pg_query($sql));
if ($check_col > 0) {
	$result_col = pg_query($sql);
	$info_col = pg_fetch_array($result_col, null, PGSQL_ASSOC);
	$col_norte_nom = utf8_decode($info_col['norte_nom']);

	if (strlen($col_norte_nom) < 110) {
		$font_size_norte = "8pt";
	} else
		$font_size_norte = "6pt";
	$col_norte_med = utf8_decode($info_col['norte_med']);
	$font_size_norte_med = "8pt";
	$col_sur_nom = utf8_decode($info_col['sur_nom']);

	if (strlen($col_sur_nom) < 110) {
		$font_size_sur = "8pt";
	} else
		$font_size_sur = "6pt";
	$col_sur_med = utf8_decode($info_col['sur_med']);
	$font_size_sur_med = "8pt";
	$col_este_nom = utf8_decode($info_col['este_nom']);

	if (strlen($col_este_nom) < 110) {
		$font_size_este = "8pt";
	} else
		$font_size_este = "6pt";
	$col_este_med = utf8_decode($info_col['este_med']);
	$font_size_este_med = "8pt";
	$col_oeste_nom = utf8_decode($info_col['oeste_nom']);

	if (strlen($col_oeste_nom) < 110) {
		$font_size_oeste = "8pt";
	} else
		$font_size_oeste = "6pt";
	$col_oeste_med = utf8_decode($info_col['oeste_med']);
	$noroes_nom = utf8_decode($info_col['noroes_nom']);
	$norest_nom = utf8_decode($info_col['norest_nom']);
	$suroes_nom = utf8_decode($info_col['suroes_nom']);
	$surest_nom = utf8_decode($info_col['surest_nom']);
	$noroes_med = utf8_decode($info_col['noroes_med']);
	$norest_med = utf8_decode($info_col['norest_med']);
	$suroes_med = utf8_decode($info_col['suroes_med']);
	$surest_med = utf8_decode($info_col['surest_med']);

	$font_size_oeste_med = "8pt";
	pg_free_result($result_col);

} else {
	$noroes_nom = $norest_nom = $suroes_nom = $surest_nom = "";
	$noroes_med = $norest_med = $suroes_med = $surest_med = "";
	$col_norte_nom = $col_sur_nom = $col_este_nom = $col_oeste_nom = "";
	$col_norte_med = $col_sur_med = $col_este_med = $col_oeste_med = "";

	$font_size_norte = $font_size_sur = $font_size_este = $font_size_oeste = "8pt";
	$font_size_norte_med = $font_size_sur_med = $font_size_este_med = $font_size_oeste_med = "8pt";
	$font_col_norte_nom = $font_col_sur_nom = $font_col_este_nom = $font_col_oeste_nom = "8pt";
	$font_col_norte_med = $font_col_sur_med = $font_col_este_med = $font_col_oeste_med = "8pt";
}
################################################################################
#------------------------------------ FECHA -----------------------------------#
################################################################################	
$nombre_mes = monthconvert($mes_actual);

$filename = "C:/apache/htdocs/tmp/us" . $cod_cat . ".html";
$filename1 = "C:/apache/htdocs/tmp/us" . $cod_cat . ".doc";
################################################################################
#------------------------ PREPARAR CONTENIDO PARA IFRAME ----------------------#
################################################################################	
$content = " 
<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<meta http-equiv='Content-Type' content='text/html; charset=utf8'>
	<title></title>
</head>
<body>
<table border='2' width='740px' height='161' style='border:2px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'><font face='Tahoma' size='2'>
	<tr>
		<td rowspan='2' width='50%'>
			<table border='0' width='100%' style='font-family: Tahoma; font-size: 9pt' bgcolor='#FFFFFF'><font face='Tahoma' size='2'>
				<tr>
					<td width='20%'>
						<img src='http://$server/$folder/css/banner_blanco.PNG' alt='imagen' width='101' height='101' border='0'>
					</td>
					<td width='80%' align='center'>
						<p>GOBIERNO AUTONOMO MUNICIPAL DE $municipio</p>
						<p>- Distrito $distrito_min -</p>
					</td>
				</tr>
			</table>
		</td>	   							 			 
		<td align='right' valign='top' width='50%'>
		    $fecha2 - $hora <a href='javascript:print(this.document)'>
		    <img border='0' src='http://$server/$folder/graphics/printer.png' width='15' height='15'></a>			 
		    <h1 align='center'>APROBACIÓN DE PLANO</h1>
		</td>
	</tr>
   <tr height='30'>
      <td align='center'>
         <font style='font-family: Tahoma; font-size: 10pt; font-weight:bold;'>G.A.M-VGDE–DOP–$pueabr4-$apr_pla/$ano_actua2</font>
      </td>		 				
   </tr>  

	<tr>
		<td align='center' valign='top' height='1050px' colspan='2' bgcolor='#FFFFFF'>
			<table border='0' width='100%' height='1050px' style='border:0px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'>
			<tr valign='top' height='10px'>
         	<td height='10px' align='center' colspan='5' style='font-family: Times New Roman; font-size: 12pt; font-weight:bold;'>COMUNICACIÓN INTERNA
            </td>				 
         </tr>

		<tr valign='top' height='10px'>
			<td></td>
			<td height='10px' colspan='2' width='40%' class='logText' style='font-family: Times New Roman; font-size: 12pt;'>A: $pueres2
			</td>	
			<td height='10px' colspan='3' width='60%' style='font-family: Times New Roman; font-size: 11pt;font-weight:bold;'>$puenom2
			</td>
		</tr>

			<tr valign='top' height='10px'>
				<td></td>
         	<td height='10px' colspan='2' style='font-family: Times New Roman; font-size: 12pt;'>A: $pueres3
            </td>	
            <td height='10px' colspan='3' style='font-family: Times New Roman; font-size: 11pt;font-weight:bold;'>$puenom3
            </td>
         </tr>

			<tr valign='top' height='10px'>
				<td></td>
         	<td height='10px' colspan='2' style='font-family: Times New Roman; font-size: 12pt;'>DE: $pueres4
            </td>	
            <td height='10px' colspan='3' style='font-family: Times New Roman; font-size: 11pt;font-weight:bold;'>$puenom4 
            </td>
         </tr>
 			<tr valign='top' height='10px'></tr>
 			<tr valign='top' height='10px'>
 				<td></td>
 				<td align='left' colspan='4' style='font-family: Times New Roman; font-size: 10pt; font-weight:bold;'>	
 				REF: APROBACIÓN DE PLANO DE UBICACIÓN Y EMISIÓN DE CERTIFICADO CATASTRAL:
 				<img src='http://$server/$folder/graphics/barra.PNG' alt='imagen' width='660' height='7' border='0'>
 				</td>
 			</tr>
 			<tr valign='top' height='10px'>
 				<td></td>
 				<td  style='font-family: Times New Roman; font-size: 12pt;'>	
 				De mi mayor consideración.
 				</td>
 			</tr>

 			<tr valign='top' height='10px'>
 				<td></td>
 				<td colspan='4' style='font-family: Times New Roman; font-size: 12pt;'>	
 				<P>$pueres4. RESPONSABLE DE TOPOGRAFÍA, dependiente de la Dirección de Obras Públicas del Gobierno Autónomo Municipal de Vallegrande, de acuerdo a la solicitud de aprobación de plano de ubicación y emisión de certificado catastral se informa lo siguiente.
 				</P>
 				</td>
 			</tr>

 			<tr height='10px'>
 				<td></td>
 				<td colspan='4' style='font-family: Times New Roman; font-size: 12pt; font-weight:bold;'>	
 				Análisis. -
 				</td>
 			</tr>

 			<tr valign='top' height='10px'>
 				<td></td>
 				<td colspan='4' style='font-family: Times New Roman; font-size: 12pt;'>	

 				<P>En fecha $dia_aprpla de $mes_aprpla de $ano_aprpla, siendo horas $apr_plahor, me dirigí al inmueble de los Sres. $prop_string a objeto de realizar la inspección y verificación de los datos del plano, además de poder realizar el levantamiento topográfico in si tú, donde se pudo evidenciar que los datos actuales son los siguientes.</P>
 				</td>
 			</tr>
			
 			<tr height='10px'>
 				<td></td>
 				<td colspan='4' style='font-family: Times New Roman; font-size: 12pt;'>	
 					El inmueble se encuentra ubicado en la zona $zona, $barrio;  Uv. $cod_uv, manzano $cod_man, Lote $cod_pred, $dir_zonurb el mismo que se encuentra ubicado sobre la $direccion, área urbana Municipio de $distrito_min.
 				</td>
 			</tr>

			<tr valign='top' height='10px'>
				<td></td>	
				<td colspan='4' style='font-family: Times New Roman; font-size: 12pt;'>	
					Se procedió a la verificación de los valores respecto al plano presentado:
				</td>
			</tr>
			<tr valign='top' height='5px'>
				<td></td>
			</tr>
			<tr>
				<td></td>	
				<td align='center' valign='top' colspan='5' style='font-family: Tahoma; font-size: 10pt;'>	 
				<table border='1' width='700px' style='border:1px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'>
					<tr valign='top'>
						<td align='center' colspan='5' style='font-family: Times New Roman; font-size: 12pt;'>						
						Datos del predio		
						</td>
					</tr>				
					<tr>
						<td width='25%' align='center'>SUP. SEGUN MENS.</td>
						<td width='25%' align='center'>SUP. SEGUN DOC.</td>
						<td width='25%' align='center'>SUP. EDIFICACION</td>
						<td width='25%' align='center'>MATERIL DE VIA</td>
					</tr>                     
					<tr>
						<td align='center'>$area m²</td>
						<td align='center'>$adq_sdoc m²</td>
						<td align='center'>$edi_area</td>
						<td align='center'>$via_mat</td>
					</tr>
				</table>
				</td>
				<td></td>
			</tr>

			<tr>
				<td></td>	
				<td align='center' valign='top' colspan='4' style='font-family: Tahoma; font-size: 10pt;'>	 
				<table border='1' width='100%' style='border:0px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'>       

					<tr valign='top'>
						<td align='center' colspan='3' style='font-family: Times New Roman; font-size: 12pt;'>						
						El predio cuenta con los siguientes colindantes			
						</td>
					</tr>";


if ($noroes_nom != '') {
	$content = $content . "
				<tr>
					<td width='10%' align='center'>NOR-OES</td>
					<td width='50%' align='left'> &nbsp $noroes_nom</td>
					<td width='40%' align='left'> &nbsp $noroes_med</td>
				</tr>";
}

if ($col_norte_nom != '') {
	$content = $content . "
				<tr>
					<td width='10%' align='center'>NORTE</td>
					<td width='50%' align='left'> &nbsp $col_norte_nom</td>
					<td width='40%' align='left'> &nbsp $col_norte_med</td>
				</tr>";
}


if ($norest_nom != '') {
	$content = $content . "
				<tr>
					<td width='10%' align='center'>NOR-EST</td>
					<td width='50%' align='left'> &nbsp $norest_nom</td>
					<td width='40%' align='left'> &nbsp $norest_med</td>
				</tr>";
}

if ($suroes_nom != '') {
	$content = $content . "
				<tr>
					<td width='10%' align='center'>SUR-OES</td>
					<td width='50%' align='left'> &nbsp $suroes_nom</td>
					<td width='40%' align='left'> &nbsp $suroes_med</td>
				</tr>";
}
if ($col_sur_nom != '') {
	$content = $content . "
				<tr>
					<td align='center'>SUR</td>
					<td align='left'> &nbsp $col_sur_nom</td>
					<td align='left'> &nbsp $col_sur_med</td>
				</tr>";
}
if ($surest_nom != '') {
	$content = $content . "
				<tr>
					<td width='10%' align='center'>SUR-EST</td>
					<td width='50%' align='left'> &nbsp $surest_nom</td>
					<td width='40%' align='left'> &nbsp $surest_med</td>
				</tr> ";
}
if ($col_este_nom != '') {
	$content = $content . "
				<tr>
					<td align='center'>ESTE</td>
					<td align='left'> &nbsp $col_este_nom</td>
					<td align='left'> &nbsp $col_este_med</td>
				</tr>";
}

if ($col_oeste_nom != '') {
	$content = $content . "
				<tr>
					<td align='center'>OESTE</td>
					<td align='left'> &nbsp $col_oeste_nom</td>
					<td align='left'> &nbsp $col_oeste_med</td>
				</tr>";
}

$content = $content . "
				</table>
				</td>
			</tr>


			<tr valign='top'>
				<td></td>	
				<td align='center' valign='top' colspan='4' style='font-family: Tahoma; font-size: 10pt;'>	 
				<table border='1' width='700px' style='border:0px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'>
					<tr valign='top'>
						<td align='center' colspan='4' style='font-family: Times New Roman; font-size: 12pt;'>						
						Fotografías del predio		
						</td>
					</tr>				
                 
					<tr>
						<td align='center' height='210px' width='320px'>
							<img border=0 src='http://$server/$folder/fotos/$fotos1' height='210px' width='320px' alt='Image resize'>
						</td>
						<td align='center' height='210px' width='320px'>
							<img border=0 src='http://$server/$folder/fotos/$fotos2' height='210px' width='320px' alt='Image resize'>
						</td>
					</tr>
				</table>
				</td>
				<td></td>
			</tr>

 			<tr valign='top' height='100px'>
 				<td></td>
 				<td colspan='4' style='font-family: Times New Roman; font-size: 12pt;'>	
 				Observaciones:
 				$apr_plaobs
 				</td>
 			</tr>

 			<tr valign='top' height='10px'>
 				<td></td>
 				<td colspan='4' style='font-family: Times New Roman; font-size: 12pt;'>	
 				</td>
 			</tr>

 			<tr valign='top' height='10px'>
 				<td></td>
 				<td colspan='4' style='font-family: Times New Roman; font-size: 12pt;'>	
 				<P>Es cuanto certifico en honor a la verdad.</P>
 				</td>
 			</tr>

			<tr valign='top' height='30px'></tr>

			<tr valign='bottom'>
				<td width='10%'></td>	
				<td width='27%' align='center'>...........................................</td>
				<td width='26%'></td>
            <td width='27%' align='center'>............................................</td>
			   <td width='10%'></td>								 
			</tr>
			<tr>
				<td>&nbsp</td>	
			</tr>

			<tr>
         	<td>&nbsp</td>				 
         </tr>																	 					
         </table>		
			</td>						 
   </tr>								  	 
</table>

</body>
</html>"
;

################################################################################
#------------------- CHEQUEAR SI SE PUEDE ABRIR EL ARCHIVO --------------------#
################################################################################	
if (!$handle = fopen($filename, 'w')) {
	$error = 2;
}
if (!fwrite($handle, $content)) {
	$error = 3;
}

fclose($handle);

?>