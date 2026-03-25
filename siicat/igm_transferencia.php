<?php

$disabled = "disabled";
if (!isset($error_registro)) {
   $error_registro = false;
	 $fecha_banco = $no_boleta_banco = "";
}
################################################################################
#-------------------------- SELECCION DE IMPUESTO -----------------------------#
################################################################################	
$calcular_transfer_urbano = $calcular_transfer_rural = false;	
if ((isset($_POST["id_inmu"])) OR (isset($_GET["inmu"]))) {
	 $calcular_transfer_urbano = true;
	 $concepto = "TRANSFER URBANO";
	 $id_item = $id_inmu;
	 $tabla_transfer = "imp_transfer";
} elseif ((isset($_POST["id_predio_rural"])) OR (isset($_GET["idpr"]))) {

}
$error1 = $error2 = $error3 = $error4 = false;	

########################################
#------- MAS QUE DOS TITULARES  -------#
########################################
if (isset($_GET["tit"])) { 
   $tit = $_GET["tit"]; 
} elseif (!isset($tit)){
   $tit = 0;
}

################################################################################
#---------------------- CHEQUEAR POR PAGO PRELIQUIDACION ----------------------#
################################################################################	
$sql="SELECT fech_pago, nombre_banco, no_boleta_banco, folio FROM imp_control_banco WHERE concepto = '$concepto' AND id_item = '$id_item'";
$check_control_banco = pg_num_rows(pg_query($sql));
if ($check_control_banco > 0) {
	$registro_banco_existe = true;
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$fecha_pago_conban = change_date($info['fech_pago']);
	$nombre_banco_conban = $info['nombre_banco'];
	$no_boleta_banco_conban = $info['no_boleta_banco'];	 
	$folio_conban = $info['folio'];
	pg_free_result($result);	
	### DATOS DE LA PRE-LIQUIDACION
	$sql="SELECT fech_imp, total_a_pagar, min_fech FROM $tabla_transfer WHERE folio = '$folio_conban'";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$concepto_conban = "TRANSFER";
	$fech_imp_conban = change_date($info['fech_imp']);	
	$cuota_conban = $info['total_a_pagar'].".00";
	$min_fech_conban = $info['min_fech'];
	$fech_venc_conban = get_fecha_plus_dias_habiles ($min_fech_conban,10);	  	 
	pg_free_result($result);	
	### SI EL PAGO SE REALIZO FUERA DE LA FECHA DE VENCIMIENTO
	$fecha1 =  strtotime($fecha_pago_conban);
	$fecha2 = strtotime($fech_venc_conban);	 
   if ($fecha1 > $fecha2) {
      $conciliacion_de_pago = true; 			
   } else $conciliacion_de_pago = false; 
#echo "FECHA_PAGO: $fecha_pago_conban, FECHA_VENC: $fech_venc_conban<br />";  
} else {
   $registro_banco_existe = $conciliacion_de_pago = false;
}

########################################
#------ LEER DATOS DE INFO_INMU -------#
########################################
include "siicat_info_inmu_leer_datos.php";
########################################
#----- LEER DATOS DE INFO_PREDIO ------#
########################################	 
include "siicat_info_predio_leer_datos.php";
$direccion = get_predio_dir ($cod_geo,$cod_uv,$cod_man,$cod_pred);
$docu_texto = $adq_doc_texto;
########################################
#---------- AJUSTAR VALORES -----------#
########################################
$titular1_ant = get_contrib_nombre ($tit_1id); 
$titular2_ant = get_contrib_nombre ($tit_2id);   
$tit_1ci_ant  = get_contrib_ci ($tit_1id);
$tit_2ci_ant  = get_contrib_ci ($tit_2id);
$tit_cara_ant = $tit_cara;
$cod_pad_ant  = get_contrib_pmc ($tit_1id);
$adq_modo_ant = $adq_modo;
$adq_doc_ant  = $docu_texto;
$adq_mont_bs_ant = $adq_mont_bs;
$adq_mont_usd_ant = $adq_mont_usd; 
if (($adq_fech == "1900-01-01") OR ($adq_fech == "")) {
	$adq_fech_ant = "";
} else $adq_fech_ant = change_date ($adq_fech);
	$der_num_ant = $der_num_texto;
if (($der_fech == "1900-01-01") OR ($der_fech == "")) {
	$der_fech_ant = "";
} else $der_fech_ant = change_date ($der_fech);			
########################################
#---------- VALORES DEFAULT  ----------#
########################################
if (((isset($_POST["submit"])) AND ($_POST["submit"] == "Transferencia")) OR (isset($_GET["tit"]))) {  
   $error1 = $error2 = $error3 = $error4 = $error_registro = false;
   $adq_doc_nuevo = $adq_mont_bs_nuevo = $adq_mont_usd_nuevo = $adq_fech_nuevo = "";
	 $der_num_nuevo = $der_fech_nuevo = "";
   $fecha_banco = $nombre_banco_form = $no_boleta_banco = "";	 
}	 

################################################################################
#----------------------- MENU SELECCION DE LUGAR DE PAGO ----------------------#
################################################################################	
$sql="SELECT numero, banco FROM imp_bancos ORDER BY numero";
$check_bancos = pg_num_rows(pg_query($sql));
$banco_numero[0] = 0;
$banco_nombre[0] = "---------------";
$banco_nombre[0] = "Seleccione donde paga";

if ($check_bancos > 0) {
	 $result=pg_query($sql);
	 $i = 0;
	 $j = 1;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
      foreach ($line as $col_value) {
			   if ($i == 0) {
				    $banco_numero[$j] = $col_value;
				 } else {
				    $banco_nombre[$j] = utf8_decode ($col_value);			
						$i = -1;
				 }
				 					
			   $i++;
			}
			$j++;
   } 
   pg_free_result($result); 	
} 
##################################################
#------------ LISTA DE CONTRIBUYENTES -----------#
##################################################
include "siicat_lista_contribuyentes.php";

################################################################################
#-------------------- LEER PRE-LIQUIDACIONES EXISTENTES -----------------------#
################################################################################	
$preliquid_exists = $nota_herencia = false;
$sql = "SELECT folio, min_fech, modo_trans, fech_imp_venc, total_a_pagar FROM imp_transfer WHERE id_inmu = '$id_inmu' AND estatus = 'PRELIQUID' ORDER BY folio";
$no_de_preliquid = pg_num_rows(pg_query($sql));
if ($no_de_preliquid > 0) {
   $preliquid_exists = true;
   $result = pg_query($sql);
   $i = $j = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
			if ($i == 0) {
				$folio_array[$j] = $col_value;
				$concepto_tabla[$j] = "TRANSFER URBANO";
				
			} elseif ($i == 1) {
				$min_fech_tabla[$j] = $col_value;
			} elseif ($i == 2) {
				if (($col_value == "HER") OR ($col_value == "OTR")) {
					$nota_herencia = true;
				}
				$modo_tabla[$j] = abr($col_value);
			} elseif ($i == 3) {
				$fech_venc_preliquid[$j] = $col_value;
			} else {
				$cuota_preliquid[$j] = $col_value;
				$i = -1;
			} 
			$i++;
		}
		$j++;
   } 
   pg_free_result($result);	 
}
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################			
echo "<td>\n";
echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";	 
echo "<tr>\n";
echo "<td width=\"5%\">\n";
echo "&nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&db=$db&id=$session_id#tab-9\">\n";
echo "<img border='0' src='http://$server/siicat/graphics/boton_atras.png'></a>\n";	
echo "</td>\n";  	 	    
echo "<td align=\"center\" valign=\"center\" width=\"90%\" class=\"pageName\">\n";
echo "Transferencia de Inmueble Urbano\n";
echo "</td>\n";
echo "<td width=\"5%\"> &nbsp</td>\n";   #Col. 3 			 
echo "</tr>\n";	
##################################################
#-------------- PROPIETARIO ANTERIOR ------------#
##################################################	 
echo "<tr>\n";  
echo "<td height=\"30\"> &nbsp</td>\n";   #Col. 1                       
echo "<td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	
echo "<fieldset style=\"border-color: lightgrey; background-color: #EEEEEE;\"><legend>Datos del Propietario Anterior</legend>\n";	 
echo "<table width=\"100%\" border=\"0\">\n";		# 3 Columnas	
	echo "<tr>\n";		
	echo "<td align=\"center\" width=\"10%\" class=\"bodyTextH\">Código</td>\n";
	echo "<td align=\"center\" width=\"45%\" class=\"bodyTextH\">Titular Principal</td>\n";
	echo "<td align=\"center\" width=\"12%\" class=\"bodyTextH\">P.M.C.</td>\n";
	echo "<td align=\"center\" width=\"30%\" class=\"bodyTextH\">Dirección</td>\n";			
	echo "</tr>\n";
	echo "<tr>\n";				 			           
	echo "<td align=\"center\" class=\"bodyTextD\">$cod_cat</td>\n";
	echo "<td align=\"center\" class=\"bodyTextD\">$titular1_ant</td>\n";
	echo "<td align=\"center\" class=\"bodyTextD\">$cod_pad_ant</td>\n";	 
	echo "<td align=\"center\" class=\"bodyTextD\">$direccion</td>\n";				 				 
	echo "</tr>\n";		  	  
echo "</table>\n"; 
##################################################
#------ DOCUMENTACION PROPIETARIO ANTERIOR ------#
##################################################
echo "<table border=\"0\" width=\"100%\">\n";
	echo "<tr>\n";
		echo "<td align=\"right\" colspan=\"7\" class=\"bodyText\"></td>\n";
	echo "</tr>\n";	   
	echo "<tr>\n";  
		echo "<td width=\"1%\">&nbsp</td>\n"; 	                     
		echo "<td align=\"center\" width=\"16%\" class=\"bodyTextH\">Documentación</td>\n";   	  	    	
		echo "<td align=\"left\" width=\"62%\" class=\"bodyTextD\">&nbsp $adq_doc_ant</td>\n";
		echo "<td width=\"1%\">&nbsp</td>\n"; 
		echo "<td align=\"center\" width=\"8%\" class=\"bodyTextH\">Fecha</td>\n";
		echo "<td align=\"left\" width=\"11%\" class=\"bodyTextD\">&nbsp $adq_fech_ant</td>\n";	    
		echo "<td width=\"1%\">&nbsp</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
		echo "<td align=\"right\" colspan=\"7\" class=\"bodyText\"></td>\n"; 
	echo "</tr>\n";	   
	echo "<tr>\n";  	
		echo "<td></td>\n";	                      
		echo "<td align=\"center\" class=\"bodyTextH\">Número de DDRR</td>\n";   	  	    
		echo "<td align=\"left\" class=\"bodyTextD\">&nbsp $der_num_ant</td>\n"; 
		echo "<td></td>\n";   	 
		echo "<td align=\"center\" class=\"bodyTextH\">Fecha</td>\n";  
		echo "<td align=\"left\" class=\"bodyTextD\">&nbsp $der_fech_ant</td>\n"; 	 	    
		echo "<td></td>\n"; 
	echo "</tr>\n";
echo "</table>\n"; 
echo "</fieldset>\n";	 	 
echo "</td>\n";
echo "<td> &nbsp</td>\n";   #Col. 3   	  
echo "</tr>\n"; 	 

if (!$preliquid_exists) {
	echo "<tr height=\"20\">\n";	
	echo "<td align=\"center\"  colspan=\"3\">\n";
	echo "<b>Para realizar una transferencia primero debe cancelar la pre-liquidación!</b>\n";
	echo "</td>\n";																							
	echo "</tr>\n";	 
} else {
	echo "<tr height=\"20\">\n"; 	 
	echo "<td align=\"center\" colspan=\"3\">\n";   #Col. 1+2+3
	echo "Registrar pre-liquidación:\n";	 	 
	echo "</td>\n";	 
	echo "</tr>\n";	  
	echo "<form name=\"form1\" method=\"post\" action=\"index.php?mod=68&db=$db&id=$session_id\" accept-charset=\"utf-8\">\n";	 
	##################################################
	#----- MOSTRAR PRE-LIQUIDACIONES EXISTENTES -----#
	##################################################
	echo "<tr>\n"; 	 
	echo "<td height=\"30\"> &nbsp</td>\n";   #Col. 1  		 
	echo "<td valign=\"top\">\n";   #Col. 2
	echo "<fieldset><legend>Pre-Liquidaciones impresas</legend>\n";
	echo "<table border=\"0\" width=\"100%\">\n";                     #TABLE  6 Columnas
	echo "<tr>\n";		
	echo "<td> &nbsp </td>\n";   #TCol. 1
	echo "<td align=\"center\" class=\"bodyTextD\">\n";   #TCol. 2	
	echo "<table border=\"0\" width=\"100%\" id=\"registros2\">\n";   # 5 TColumnas
	echo "<tr>\n";	
	echo "<td width=\"4%\" class=\"bodyTextH\"> &nbsp </td>\n";
	echo "<td width=\"16%\" align=\"center\" class=\"bodyTextH\"><b>FOLIO</b></td>\n";
	echo "<td width=\"20%\" align=\"center\" class=\"bodyTextH\"><b>MODO TRANS.</b></td>\n";
	echo "<td width=\"20%\" align=\"center\" class=\"bodyTextH\"><b>FECHA MINUTA</b></td>\n";	
	echo "<td width=\"20%\" align=\"center\" class=\"bodyTextH\"><b>CUOTA</b></td>\n";
	echo "<td width=\"20%\" align=\"center\" class=\"bodyTextH\"><b>FECHA VENC.</b></td>\n";	
	echo "</tr>\n";  	 	
	$i = 0;
	while ($i < $no_de_preliquid) {							
		echo "                        <tr>\n";	
		$folio_texto = $folio_array[$i];
		if ($i == $no_de_preliquid-1) {
			echo "<td align=\"center\" valign=\"top\"><input name=\"folio_select\" value=\"$folio_texto\" type=\"radio\" checked=\"checked\"></td>\n"; 
		} else {
			echo "<td align=\"center\" valign=\"top\"><input name=\"folio_select\" value=\"$folio_texto\" type=\"radio\"></td>\n"; 
		}
		echo "<td>$folio_texto</td>\n";
		echo "<td>$modo_tabla[$i]</td>\n";
		$fecha_temp = change_date($min_fech_tabla[$i]);
		echo "<td>$fecha_temp</td>\n";	
		$cuota_temp = $cuota_preliquid[$i].".00";
		echo "<td>$cuota_temp</td>\n";
		$fecha_venc_temp = change_date($fech_venc_preliquid[$i]);
		echo "<td>$fecha_venc_temp</td>\n";							 																			
		echo "</tr>\n";	
		$i++;
	}
	echo "<tr>\n";
	echo "</table>\n";
	echo "</td>\n";				 				  	
	echo "<td> &nbsp </td>\n"; 
	echo "</tr>\n";	
	### ESPACIO ###								 	
	echo "<tr>\n";		
	echo "<td colspan=\"3\" style='font-family: Arial; font-size: 4pt'> &nbsp </td>\n";   #TCol. 2		
	echo "</tr>\n";
	echo "<tr>\n";		
	echo "<td colspan=\"3\" align=\"center\"><font color=\"orange\"> No debe rellenar los campos siguientes si la cuota de la preliquidación es 0! </font></td>\n";   #TCol. 2		
	echo "</tr>\n";	 
	### REGISTRO BANCO ###								 	
	echo "<tr>\n";			
	echo "<td> &nbsp </td>\n"; 
	echo "<td align=\"center\" class=\"bodyTextD\">\n"; 												
	echo "<table border=\"0\" width=\"100%\" id=\"registros2\">\n"; 
		echo "<tr>\n";	
		echo "<td width=\"20%\" align=\"center\" class=\"bodyTextH\"><b>FECHA PAGO</b></td>\n";
		echo "<td width=\"50%\" align=\"center\" class=\"bodyTextH\"><b>LUGAR DE PAGO (BANCO, ALCALDIA)</b></td>\n";
		echo "<td width=\"30%\" align=\"center\" class=\"bodyTextH\"><b>NO. DE BOLETA PAGO</b></td>\n";																		
		echo "</tr>\n";
		echo "<tr>\n";		 	
		### INGRESAR DATOS ###
        if (empty($fecha_banco) || trim($fecha_banco) === '') {
            $fecha_banco = date('d/m/Y'); // o date('Y-m-d') si tu formulario espera ISO
        }				 
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
		echo "<tr>\n";
	echo "</table>\n";
echo "</td>\n";				 				  	
echo "<td> &nbsp </td>\n";   #TCol. 3 
echo "</tr>\n";
### ERROR ###					
		 							
if ($error_registro) {
	echo "<tr>\n";																		
	echo "<td align=\"center\" colspan=\"3\" class=\"alert alert-danger\">$mensaje_de_error_registro</td>\n"; 
	echo "</tr>\n";
}							
	   ### BOTON REGISTRAR ###							 							
		echo "<tr>\n";																		
			echo "<td align=\"center\" colspan=\"3\">\n";
			echo "<input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";					
			echo "<input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";
			echo "<input name=\"cod_pad_ant\" type=\"hidden\" value=\"$cod_pad_ant\">\n";	
			echo "<input name=\"tit_1id_ant\" type=\"hidden\" value=\"$tit_1id\">\n";	
			echo "<input name=\"tit_2id_ant\" type=\"hidden\" value=\"$tit_2id\">\n";
			echo "<input name=\"tit_cara_ant\" type=\"hidden\" value=\"$tit_cara_ant\">\n";
			echo "<input name=\"der_num_ant\" type=\"hidden\" value=\"$der_num_ant\">\n";
			echo "<input name=\"der_fech_ant\" type=\"hidden\" value=\"$der_fech_ant\">\n";	
			echo "<input name=\"adq_modo_ant\" type=\"hidden\" value=\"$adq_modo_ant\">\n";
			echo "<input name=\"adq_doc_ant\" type=\"hidden\" value=\"$adq_doc_ant\">\n";	 
			echo "<input name=\"adq_fech_ant\" type=\"hidden\" value=\"$adq_fech_ant\">\n";
			echo "<input name=\"adq_mont_bs_ant\" type=\"hidden\" value=\"$adq_mont_bs_ant\">\n";	 
			echo "<input name=\"adq_mont_usd_ant\" type=\"hidden\" value=\"$adq_mont_usd_ant\">\n";	 				 	 	  	 	 	 	 	 	 		 	 	 
			echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Registrar Transferencia\">\n"; 										
			echo "</td>\n";		
		echo "</tr>\n";		
	echo "</table>\n"; 
	echo "</fieldset>\n";	 	 
	echo "</td>\n";
	echo "<td> &nbsp</td>\n";   #Col. 3   	  
	echo "</tr>\n";	
	echo "</form>\n";	
}
echo "<tr height=\"100%\"></tr>\n";			 
echo "</table>\n";
echo "<br />&nbsp;<br />\n";
echo "</td>\n";	  
?>