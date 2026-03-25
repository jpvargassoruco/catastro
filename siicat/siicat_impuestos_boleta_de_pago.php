<?php  

$boleta = false;
if (isset($_POST["gestion"])) {
   $gestion = $_POST["gestion"];
} elseif (isset($_GET["gestion"])) {
   $boleta = true;
   $gestion = $_GET["gestion"];
} else $gestion = 0;

if (isset($_POST["forma_pago"])) {
   $forma_pago = $_POST["forma_pago"];
} else $forma_pago = "CONTADO";

$sello = false;
$plan_de_pago = false;
$error = false;
$no_control = "";
$pago_al_contado = $plan_de_pago_submit = false;
$convalidar_pago = false;
$mostrar_botones = true;
$descont_exen = $porc_select = 0;
$exencion_seleccionada = false;
$valor_en_libros = "";
################################################################################
#---------------------------- CHEQUEAR POR IMPRESION --------------------------#
################################################################################	
$sql="SELECT max(control) FROM imp_pagados WHERE forma_pago='CONTADO'";
$check_tabla = pg_num_rows(pg_query($sql));

if ($check_tabla > 0) {
   $result = pg_query($sql);
   $info = pg_fetch_array($result, null, PGSQL_ASSOC);
   $no_control = $info['max']+1;
   }
   else {
   $no_control = 1;
}

$sql="SELECT exen_id, control, no_orden FROM imp_pagados WHERE gestion = '$gestion' AND no_orden != '0' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
$check_tabla = pg_num_rows(pg_query($sql));

if ($check_tabla > 0) {
	$sello = true;
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$exen_id_tab = $info['exen_id'];
	$control_tab = $info['control'];	   	 
	$no_orden_tab = $info['no_orden'];
	pg_free_result($result);
	$sql="SELECT cuota, control FROM imp_control WHERE no_orden = '$no_orden_tab'";
	$check_control = pg_num_rows(pg_query($sql));
	if ($check_control > 0) {
		$result = pg_query($sql);
		$info = pg_fetch_array($result, null, PGSQL_ASSOC);
		$cuota_imp = $info['cuota'];  	 
		$control_imp = $info['control'];
		pg_free_result($result);
		if ($control_tab == $control_imp) {
			$no_orden_sello = $no_orden_tab;
			$control_sello = $control_tab;
			$cuota_sello = $cuota_imp;
			$no_control  = $control_imp;
		} else $exen_id_tab = 0;
	} else {
		$cuota_imp = 0;
		$exen_id_tab = 0;
	}
} else $exen_id_tab = 0;
################################################################################
#---------------------- EXENCION APLICADA /NO APLICADA ------------------------#
################################################################################	
if (((isset($_POST["exen_selected"])) AND ($_POST["exen_selected"] > 0)) OR (($exen_id_tab > 0) AND (!isset($_POST["exen"]))) ) {
	$exencion_seleccionada = true;
	if (isset($_POST["exen_selected"])) {
		$exen_select = $_POST["exen_selected"];
	} else $exen_select = $exen_id_tab;
	$sql="SELECT descripcion, porcentaje FROM imp_exenciones WHERE numero = '$exen_select'";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$desc_select = $info['descripcion'];
	$porc_select = $info['porcentaje'];
	pg_free_result($result);
} else {
	 $exen_select = $porc_select = 0;
}
################################################################################
#--------------------- LEER IMP_NETO Y CALCULAR CUOTA -------------------------#
################################################################################	
$sql="SELECT imp_neto, cuota FROM imp_pagados WHERE gestion = '$gestion' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
$check = pg_num_rows(pg_query($sql));
$result = pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$imp_neto_tabla = $info['imp_neto'];
$cuota_tabla = $info['cuota'];

pg_free_result($result);
$imp_neto = $imp_neto_tabla;
########################################
#------ APLICAR VALOR EN LIBROS  ------#
########################################
$solo_empresa = false;
if ((isset($_POST["valor_en_libros"])) OR (isset($_POST["solo_empresa"]))) {
	if (isset($_POST["solo_empresa"])) {
		$valor_en_libros = $_POST["solo_empresa"]; 
	} else {
		$valor_en_libros = $_POST["valor_en_libros"];
	}
	if ($valor_en_libros > 0) {
		$solo_empresa = true; 
		$imp_neto_real = $imp_neto;
		$sql="SELECT * FROM imp_escala_imp WHERE gestion = '$gestion' AND exced <= '$valor_en_libros' ORDER BY mas_porc DESC LIMIT 1";
		$result_vl = pg_query($sql);
		$info_vl = pg_fetch_array($result_vl, null, PGSQL_ASSOC);				
		$cuota_fija = $info_vl['cuota'];
		$tp_exen = $info_vl['mas_porc'];
		$monto_exen = $info_vl['exced'];
		#echo "CUOTA_FIJA: $cuota_fija,$tp_exen,$monto_exen<br>";				 
		########################################
		#------------ BASE IMPONIBLE ----------#
		########################################	
		$base_imp = $valor_en_libros - $monto_exen;
		########################################
		#---------- MONTO DETERMINADO ---------#
		########################################					 
		$imp_neto = ROUND ($base_imp * $tp_exen/100,0) + $cuota_fija;
      pg_query("
      	UPDATE imp_pagados 
      	SET avaluo_total = '$valor_en_libros', tp_exen = '$tp_exen', monto_exen = '$monto_exen', base_imp = '$base_imp', imp_neto   = '$imp_neto' 
      	WHERE gestion    = '$gestion' AND cod_geo      = '$cod_geo' AND cod_uv  = '$cod_uv' AND cod_man   = '$cod_man' AND cod_lote = '$cod_lote' AND cod_subl = '$cod_subl'");
   }	 
}
########################################
#--------- APLICAR EXENCION  ----------#
########################################	
$descont_exen = ROUND($imp_neto*$porc_select/100,0);	
$imp_neto = $imp_neto - $descont_exen;
$imp_neto = ROUND ($imp_neto,0); 
if ($imp_neto >= 0) {
	$pago_al_contado = true;
	$no_cuota = 0;
	########################################
	#-------- DEFINIR FECHA VENC  ---------#
	########################################	
	$sql="SELECT * FROM imp_fecha_venc WHERE gestion = '$gestion'";
	$result_fecha_venc = pg_query($sql);
	$info_fecha_venc = pg_fetch_array($result_fecha_venc, null, PGSQL_ASSOC);
	$fecha_venc_1st = $info_fecha_venc['fecha_venc'];
	if ($fecha_venc_1st == "") {
		$fecha_venc = "-";
		$error = $error_fecha_venc = true;
		$mensaje_de_error = "Falta ingresar el 1er plazo de vencimiento para la gestión $gestion!";
	}	else {
		$fecha_venc = $fecha_venc_1st;
	}
	$fecha_mod1 = $info_fecha_venc['fecha_mod1'];
	$fecha_mod2 = $info_fecha_venc['fecha_mod2'];
	$fecha_mod3 = $info_fecha_venc['fecha_mod3'];						
	if ($fecha_mod3 != "") {
		$fecha_venc = $fecha_mod3;
	} elseif ($fecha_mod2 != "") {
		$fecha_venc = $fecha_mod2;
	} elseif ($fecha_mod1 != "") {
		$fecha_venc = $fecha_mod1;
	} 
	pg_free_result($result_fecha_venc);
	########################################
	#-------- DESCUENTO Y MULTAS  ---------#
	########################################	
	$sql="SELECT * FROM imp_base";
	$result_base = pg_query($sql);
	$info_base = pg_fetch_array($result_base, null, PGSQL_ASSOC);			  
	$descuento = $info_base['descuento'];
	$multa_mora = $info_base['multa_mora'];
	$multa_incum = 0;
	$multa_admin = $info_base['multa_admin'];		
	$por_form = $info_base['rep_form'];																				
	pg_free_result($result_base);							
	#echo "$fecha,$fecha_venc_1st,$fecha_venc<br>";	
	if ($fecha_venc == "-") {
		#echo "FECHA_VENC NO ES VALIDO!!!<br>";	
		$d10 = 0;
		$monto = 0;
		$usd_venc =  0;								 
		$ufv_venc =  0;		
		$mant_valor = $interes = 0;			
	} elseif ($fecha <= $fecha_venc_1st) {
		$d10 = ROUND ($imp_neto*$descuento/100,0);
		$monto = $imp_neto-$d10;
		$usd_venc =  imp_getcoti	($fecha,"usd");								 
		$ufv_venc =  imp_getcoti	($fecha,"ufv");		
		$mant_valor = $interes = 0;
	} elseif ($fecha <= $fecha_venc) {	
		$d10 = 0;
		$monto = $imp_neto-$d10;
		$usd_venc =  imp_getcoti	($fecha,"usd");								 
		$ufv_venc =  imp_getcoti	($fecha,"ufv");		
		$mant_valor = $interes = 0;			
	} else {
		#echo "FECHA > FECHA_VENC: $fecha,$fecha_venc[$j]<br>";			
		$d10 = 0;
		$usd_venc =  imp_getcoti	($fecha_venc,"usd");
		$usd_actual =  imp_getcoti	($fecha,"usd");				 
		$ufv_venc =  imp_getcoti	($fecha_venc,"ufv");
		$ufv_actual =  imp_getcoti	($fecha,"ufv");

		$mant_valor = calc_mant_valor($ufv_venc,$ufv_actual,$imp_neto);
		$imp_neto_act = $imp_neto + $mant_valor;
		$tasa_taprufv = imp_tasa_taprufv ($fecha);
		if ($tasa_taprufv == -1) {
			$timestamp = strtotime($fecha.' - 1 month');
			$fecha_ant = date('Y-m-d', $timestamp);
			$tasa_taprufv = imp_tasa_taprufv ($fecha_ant);
		if ($tasa_taprufv == -1) {
			$error_tapr = true;
			$timestamp = strtotime($fecha_ant.' - 1 month');
			$fecha_ant = date('Y-m-d', $timestamp);
			$tasa_taprufv = imp_tasa_taprufv ($fecha_ant);							   
		if ($tasa_taprufv == -1) {
			$error = true;
			$tasa_taprufv = 0;
		}
	}			
}			 
$no_dias_de_mora = imp_dias_de_mora ($fecha_venc,$fecha);		  
$interes = calc_interes($imp_neto_act, $tasa_taprufv, $no_dias_de_mora);
$multa_incum = imp_multa_incum ($imp_neto,$ufv_venc,$ufv_actual);	
$des_int = $interes + $multa_incum;						  			 		
$monto = $imp_neto + $mant_valor + $interes + $multa_mora + $multa_incum + $multa_admin;				 			 	
}	
$decont = $sal_favor = 0;

if ($imp_neto == 0) {
	$multa_incum = 0;
	$descuento = $imp_neto_tabla;
	$cuota = $por_form;
	} else {
	$cuota = $monto - $decont - $sal_favor + $por_form;	
}	

$total_a_pagar = $cuota; 					
} else $total_a_pagar = 0;				

################################################################################
#------------ SOLO MOSTRAR IMPRIMIR SELLO CUANDO COINCIDE EXENCION ------------#
################################################################################	
if ($exen_id_tab != $exen_select) {
   $sello = false;
}

################################################################################
#------------------------------- PAGO AL CONTADO ------------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND (($_POST["submit"] == "CONTADO") OR ($_POST["submit"] == "Liquidar"))) { 
	 #$plan_de_pago_aceptado = true;
	 $pago_al_contado = true;
	 if (isset($_POST["forma_pago"])) {
      $forma_pago = $_POST["forma_pago"];
   }
	 if (isset($_POST["no_cuota"])) {
      $no_cuota = $_POST["no_cuota"];
   } else $no_cuota = "";
}
################################################################################
#------------------------------- PLAN DE PAGO ------------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND ($_POST["submit"] == "Liquidar") AND (isset($_POST["forma_pago"])) AND ($_POST["forma_pago"] == "PLAN")) {
   $mostrar_botones = false;
}
################################################################################
#------------------------------- BOLETA IMPRESA ------------------------------#
################################################################################	
if ($boleta) {
	$sello = true;
	$pago_al_contado = true;
	$sql="SELECT por_form, monto, cuota, exen_id, fech_imp, hora, usuario, control, no_orden FROM imp_pagados WHERE gestion = '$gestion' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
	$result = pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$cuota_sello = $info['cuota'];
	$control_sello = $info['control'];		  	 
	$no_orden_sello = $info['no_orden'];
	pg_free_result($result); 

	$no_cuota = 0;
	 
#echo "GESTION: $gestion, CODIGO: $cod_cat, FORMA_PAGO: $forma_pago, EXEN_ID: $exen_id<br>";
} else $exen_id = 0;
################################################################################
#------------------------ MENU SELECCION DE EXENCIONES ------------------------#
################################################################################	
$sql="SELECT numero, descripcion, porcentaje FROM imp_exenciones ORDER BY numero";
$check_exenciones = pg_num_rows(pg_query($sql));
$exen_numero[0] = 0;
$exen[0] = "---------------";
$porcentaje[0] = 0;
if ($check_exenciones > 0) {
	 $result=pg_query($sql);
	 $i = 0;
	 $j = 1;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
      foreach ($line as $col_value) {
			   if ($i == 0) {
				    $exen_numero[$j] = $col_value;
         } elseif ($i == 1) {
				 		$descripcion = $col_value;
				 } else {
				    $porcentaje[$j] = $col_value;
						$exen[$j] = $descripcion." - ".$porcentaje[$j]." %";
						$i = -1;
				 }						
			   $i++;
			}
			$j++;
   } 
   pg_free_result($result); 	
}   
################################################################################
#-------------------------- IMPRIMIR BOLETA DE PAGO ---------------------------#
################################################################################	
if ((isset($_POST["imprimir"])) AND ($_POST["imprimir"] == "IMPRIMIR BOLETA")) {
	$boleta_de_pago = true;   
	$forma_pago = $_POST["forma_pago"];
	$exen_id = $_POST["exen_selected"];	 
	$descont_exen = $_POST["descont_exen"];
	$no_control = $_POST["no_control"];
	$total_a_pagar = $_POST["total_a_pagar"];	 
	if ((!check_int ($no_control)) OR ($no_control == "")) {
		$error = true;
		$mensaje_de_error = "Error: Tiene que ingresar el número de la boleta!";
		$boleta_de_pago = false;
	} else {
		$control = change_numero_to_8char ($no_control);
		$sql="SELECT no_orden FROM imp_control WHERE control = '$control' AND observ = 'SELLO'";
		$check_control = pg_num_rows(pg_query($sql));
		if ($check_control > 0) {
			$result = pg_query($sql);
         $info_orden = pg_fetch_array($result, null, PGSQL_ASSOC);
         $no_orden = $info_orden['no_orden'];	
			pg_free_result($result); 
			$error = true;
			$mensaje_de_error = "Error: Ya se registró un pago con ese número de boleta (con número de órden: $no_orden)";
			$boleta_de_pago = false;	
		}
	}
} else $boleta_de_pago = false;

################################################################################
#----------------------------- CONVALIDAR PAGO --------------------------------#
################################################################################	
if ((isset($_POST["submit"])) AND ($_POST["submit"] == "CONVALIDAR PAGO")) {
   $pago_al_contado = $plan_de_pago_submit = false;
	 $convalidar_pago = true;
	 $forma_pago = "CONVALIDAR";
	 $registrar = false;
	 $error_conv = false;
	 $disabled = "";
	 if ((isset($_POST["submit"])) AND (isset($_POST["no_orden"]))) {
	    $no_orden_conv = trim($_POST["no_orden"]);	
			if ($no_orden_conv == "") {
			   $error_conv = true;
				 $mensaje_de_error = "Error: Tiene que ingresar un número de orden!";
			} elseif (!check_int($no_orden_conv)) {
			   $error_conv = true;
				 $mensaje_de_error = "Error: El número de orden tiene que ser un número!";
			} elseif ($no_orden_conv > 10000000) {
			   $error_conv = true;
				 $mensaje_de_error = "Error: El número de orden no puede ser mayor a 10000000!";			
			}
	    $fech_imp_conv = trim($_POST["fech_imp"]);
			if ($fech_imp_conv == "") {
			   $error_conv = true;
				 $mensaje_de_error = "Error: Tiene que ingresar una fecha de pago!";
			} elseif (!check_fecha($fech_imp_conv,$dia_actual,$mes_actual,$ano_actual)) {
			   $error_conv = true;
				 $mensaje_de_error = "Error: El formato de la fecha de pago ingresado no es v�lido!";
			}			
			$cuota_conv = trim ($_POST["cuota"]);
			if ($cuota_conv == "") {
			   $error_conv = true;
				 $mensaje_de_error = "Error: Tiene que ingresar un monto pagado!";
			} elseif (!check_int($cuota_conv)) {
			   $error_conv = true;
				 $mensaje_de_error = "Error: El monto pagado tiene que ser un número (sin decimales)!";
			}					
	    $control_conv = trim ($_POST["control"]);
			if ($control_conv == "") {
			   $error_conv = true;
				 $mensaje_de_error = "Error: Tiene que ingresar un número de boleta!";
			} elseif (!check_int($control_conv)) {
			   $error_conv = true;
				 $mensaje_de_error = "Error: El número de boleta tiene que ser un número!";
			}			
			if (!$error_conv) {
			   $registrar = true;
				 $disabled = pg_escape_string('disabled=\"disabled\"');
			}
	 } else {
	    $no_orden_conv = $fech_imp_conv = $cuota_conv = $control_conv = "";
	 }		
}
################################################################################
#------------------------- FORMULARIO PARA IMPRIMIR ---------------------------#
################################################################################	
if ($boleta_de_pago) {	
	echo "<td>\n";
	echo "<table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"850px\" height=\"1220px\">\n";   # 3 Columnas
		echo "<tr height=\"40px\">\n";
			echo "<td width=\"15%\">\n";  #Col. 1 
				echo "&nbsp&nbsp <a href=\"index.php?mod=62&inmu=$id_inmu&id=$session_id\">\n";		
				echo "<img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";	
			echo "</td>\n";   	    
				echo "<td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n"; 
				echo "Boleta de Pago\n";                        
			echo "</td>\n";
			echo "<td width=\"25%\"> &nbsp</td>\n";   #Col. 3 			 
		echo "</tr>\n";	
		echo "<tr>\n";
			echo "<td valign=\"top\" colspan=\"3\">\n";   #Col. 1 	 
				include "siicat_impuestos_generar_boleta_de_pago.php";
				echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/boleta$cod_cat.html\" id=\"mapserver\" width=\"850px\" height=\"1220px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
				echo "</iframe>\n";	
			echo "</td>\n";	 
		echo "</tr>\n";	 		
	echo "</table>\n";
	echo "</td>\n";
} else {
################################################################################
#---------------------- SELECCION CONTADO/PLAN DE PAGO ------------------------#
################################################################################		
	echo "<td valign=\"top\">\n";
	echo "<table border=\"0\" align=\"center\" valign=\"top\" cellpadding=\"0\" width=\"750px\" height=\"100%\">\n";   # 3 Columnas	 
	# Fila 1
	echo "<tr height=\"20px\">\n";
	echo "<td width=\"10%\">\n";   #Col. 1 	
	echo "&nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&calc&id=$session_id#tab-10\">\n";		
	echo "<img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";	
	echo "</td>\n";	     
	echo "<td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n"; 
	echo "Liquidación de Impuestos\n";                          
	echo "</td>\n";
	echo "<td width=\"20%\"> &nbsp</td>\n";   #Col. 3 			 
	echo "</tr>\n";
	if ($mostrar_botones) {
	    echo "<tr height=\"10px\">\n";
	    echo "<td > &nbsp</td>\n";   #Col. 1 	  
	    echo "<td align=\"center\">\n";   #COLUMNA 2	
	    echo "<table border=\"0\" width=\"100%\">\n";   # 3 Columnas	    	 
	    echo "<tr>\n"; 
	    echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=62&id=$session_id\" accept-charset=\"utf-8\">\n";			
	    echo "<td align=\"right\" width=\"40%\">\n";   #Col. 1	
		if (isset($_POST["numero_exen"])) {
			echo "<input name=\"numero_exen\" type=\"hidden\" class=\"smallText\" value=\"$numero_exen\">\n";
		}
		echo "<input name=\"id_inmu\" type=\"hidden\" class=\"smallText\" value=\"$id_inmu\">\n";	
		echo "<input name=\"gestion\" type=\"hidden\" class=\"smallText\" value=\"$gestion\">\n";	  	  	
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"CONTADO\">\n";
		echo "</td>\n";	  
		echo "<td align=\"center\" width=\"10%\">\n";   #Col. 2	
		echo "&nbsp\n";	
		echo "</td>\n";
		echo "<td align=\"left\" width=\"50%\">\n";   #Col. 3	
		echo "<input name=\"total_a_pagar\" type=\"hidden\" class=\"smallText\" value=\"$total_a_pagar\">\n";	 	  	
		echo "&nbsp&nbsp&nbsp&nbsp&nbsp<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"CONVALIDAR PAGO\">\n";
		echo "</td>\n";			
		echo "</form>\n";					 	 
		echo "</tr>\n"; 			 	
		echo "</table>\n"; 	 
		echo "</td>\n";
		echo "<td> &nbsp</td>\n";		 	 	 	  	 
		echo "</tr>\n";	
		echo "<tr>\n";
		echo "<td colspan=\"3\"> &nbsp</td>\n";	 
		echo "</tr>\n";			
		if (($forma_pago == "CONTADO") OR ($forma_pago == "PLAN")) {
			echo "<tr>\n";
			echo "<td> &nbsp</td>\n"; 	 
			echo "<td valign=\"top\" height=\"40\">\n";   #Col. 2
			echo "<fieldset><legend>Solo para Empresas</legend>\n";
			echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=62&id=$session_id\" accept-charset=\"utf-8\">\n";	
				echo "<table border=\"0\" width=\"100%\">\n";   # 5 TColumnas
					echo "<tr>\n";		
					echo "<td width=\"1%\"> &nbsp </td>\n";   #TCol. 1 					
					echo "<td width=\"44%\" align=\"right\" class=\"bodyTextD\">Declaración de Valor en Libros: </td>\n"; #Col. 2-4
					echo "<td width=\"40%\" align=\"center\" class=\"bodyTextD\">\n";   #TCol. 4	  				 	
					echo "<input name=\"valor_en_libros\" id=\"form_anadir2\" class=\"navText\" maxlength=\"10\" value=\"$valor_en_libros\">\n";	 	
					echo "</td>\n";				 					
					echo "<td width=\"14%\" align=\"center\" class=\"bodyTextD\">\n";   #TCol. 4	
					echo "<input name=\"id_inmu\" type=\"hidden\" class=\"smallText\" value=\"$id_inmu\">\n";	
					echo "<input name=\"gestion\" type=\"hidden\" class=\"smallText\" value=\"$gestion\">\n";	
					echo "<input name=\"forma_pago\" type=\"hidden\" class=\"smallText\" value=\"$forma_pago\">\n";						
					echo "<input name=\"total_a_pagar\" type=\"hidden\" class=\"smallText\" value=\"$total_a_pagar\">\n";					 
					echo "<input name=\"vl\" type=\"submit\" class=\"smallText\" value=\"APLICAR\">\n";
					echo "</td>\n";
					echo "<td width=\"1%\"> &nbsp </td>\n"; 				 				  	   
					echo "</tr>\n";					 
				echo "</table>\n";  
			echo "</fieldset>\n";
			echo "</form>\n";
			echo "</td>\n";
			echo "</tr>\n";					 			
			echo "<tr>\n";
			echo "<td> &nbsp</td>\n"; 
			echo "<td valign=\"top\" height=\"40\">\n"; 
			echo "<fieldset><legend>Aplicar Exenciones</legend>\n";
			echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=62&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
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
			echo "<td width=\"14%\" align=\"center\" valign=\"center\">\n";   #Col. 2-4	 
			echo "<input name=\"id_inmu\" type=\"hidden\" class=\"smallText\" value=\"$id_inmu\">\n";	
			echo "<input name=\"gestion\" type=\"hidden\" class=\"smallText\" value=\"$gestion\">\n";	
			echo "<input name=\"forma_pago\" type=\"hidden\" class=\"smallText\" value=\"$forma_pago\">\n";
			echo "<input name=\"total_a_pagar\" type=\"hidden\" class=\"smallText\" value=\"$total_a_pagar\">\n";	
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
			}	
	 }	

	if ($pago_al_contado) {	   
		echo "<tr>\n";
		echo "<td> &nbsp</td>\n";   #Col. 1  	 
		echo "<td align=\"center\" valign=\"top\" height=\"40\">\n";   #Col. 2
		echo "<fieldset><legend>Imprimir Boleta 1</legend>\n"; 	   
		echo "<table border=\"0\" width=\"100%\">\n";   # 5 TColumnas
		if ($exencion_seleccionada) {
			echo "<tr>\n";		
			echo "<td> &nbsp </td>\n";   #TCol. 1 					
			echo "<td align=\"center\" class=\"bodyTextD\" colspan=\"3\">\n";
			echo "<font color=\"orange\">Aplicando Exención para $desc_select</font>\n"; #Col. 2-4	
			echo "</td>\n";
			echo "<td></td>\n";   #TCol. 5 	
			echo "</tr>\n";				
		}			
		echo "<tr>\n";		
		echo "<td> &nbsp </td>\n";   #TCol. 1 					
		echo "<td align=\"center\" class=\"bodyTextD\" colspan=\"3\">Monto a Pagar: $total_a_pagar Bs.</td>\n"; #Col. 2-4	
		echo "<td></td>\n";   #TCol. 5 	
		echo "</tr>\n";	

		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=62&boleta=$gestion&id=$session_id\" accept-charset=\"utf-8\">\n";							
			echo "<tr>\n";	
				echo "<td width=\"1%\"> &nbsp </td>\n";   #TCol. 1 
				echo "<td align=\"right\" width=\"70%\">\n";	 #TCol. 2 				
				echo "Por favor ingrese el número de la boleta que va a imprimir: \n";
				echo "</td>\n";										
				echo "<td align=\"left\" width=\"20%\">\n";				
				echo "<input name=\"no_control\" id=\"form_anadir2\" class=\"navText\" maxlength=\"8\" value=\"$no_control\">\n";
				echo "</td>\n";
				echo "<td width=\"8%\"> &nbsp </td>\n";	
				echo "<td width=\"1%\"> &nbsp </td>\n";			
				echo "</tr>\n";					
				echo "<tr>\n";
				echo "<td> &nbsp </td>\n"; 
				echo "<td align=\"center\" valign=\"center\" colspan=\"3\">\n";   #Col. 2-4	 
				echo "<input name=\"id_inmu\" type=\"hidden\" class=\"smallText\" value=\"$id_inmu\">\n";	
				echo "<input name=\"gestion\" type=\"hidden\" class=\"smallText\" value=\"$gestion\">\n";	
				echo "<input name=\"submit\" type=\"hidden\" class=\"smallText\" value=\"Liquidar\">\n";	
				echo "<input name=\"forma_pago\" type=\"hidden\" class=\"smallText\" value=\"$forma_pago\">\n";
				echo "<input name=\"no_cuota\" type=\"hidden\" class=\"smallText\" value=\"$no_cuota\">\n";								
				echo "<input name=\"total_a_pagar\" type=\"hidden\" class=\"smallText\" value=\"$total_a_pagar\">\n";
				echo "<input name=\"exen_selected\" type=\"hidden\" class=\"smallText\" value=\"$exen_select\">\n";		
				if ($solo_empresa) {			 				 
					echo "<input name=\"solo_empresa\" type=\"hidden\" class=\"smallText\" value=\"$valor_en_libros\">\n";													  			
				}
				echo "<input name=\"descont_exen\" type=\"hidden\" class=\"smallText\" value=\"$descont_exen\">\n";				  
				echo "<input name=\"imprimir\" type=\"submit\" class=\"smallText\" value=\"IMPRIMIR BOLETA\">\n"; 
				echo "</td>\n";
				echo "<td> &nbsp </td>\n";   #TCol. 5 		 	 
			echo "</tr>\n";
		echo "</form>\n";
		echo "</table>\n";  
		echo "</fieldset>\n";
		if ($error) {
			echo "<font color=\"red\">$mensaje_de_error</font>\n";
		}				 
		if ($sello) {		 			 
			echo "<fieldset><legend>Liquidar los Impuestos</legend>\n"; 	   
			echo "<table border=\"0\" width=\"100%\">\n";   # 3 TColumnas
				echo "<tr>\n";		
				echo "<td align=\"center\" colspan=\"3\">\n";   #TCol. 1-3
				echo "<b>Por favor, verifique los datos en la boleta antes de imprimir el sello:</b> \n";							
				echo "</td>\n";												
				echo "</tr>\n";
				echo "<tr height=\"25\">\n";		
				echo "<td align=\"center\" colspan=\"3\">\n";   #TCol. 1-3
				echo "Código Catastral: <b>$cod_cat</b> &nbsp&nbsp&nbsp&nbsp&nbsp Gestión: <b>$gestion</b> \n";	
				echo "</td>\n";												
				echo "</tr>\n";						
				echo "<tr>\n";		
				echo "<td align=\"right\" width=\"25%\"> &nbsp </td>\n";							
				echo "<td align=\"center\" width=\"50%\">\n";   #TCol. 1-3
				echo "<fieldset>\n";
				echo "<table border=\"0\" width=\"100%\">\n";   # 3 TColumnas									
				echo "<tr>\n";
				echo "<td align=\"right\" width=\"60%\">\n";									
				echo "<b>No. de Orden:</b>\n";	
				echo "</td>\n";  
				echo "<td align=\"left\" width=\"40%\">\n";		
				echo "$no_orden_sello\n";			   				
				echo "</td>\n";										
				echo "</tr>\n";
				echo "<tr>\n";
				echo "<td align=\"right\">\n";									
				echo "<b>Monto a Pagar:</b>\n";	
				echo "</td>\n";  
				echo "<td align=\"left\">\n";		
				echo "$cuota_sello Bs.\n";			   				
				echo "</td>\n";								
				echo "</tr>\n";
				echo "<tr>\n";
				echo "<td align=\"right\">\n";									
				echo "<b>Número de la Boleta:</b>\n";	
				echo "</td>\n";  
				echo "<td align=\"left\">\n";		
				echo "$control_sello\n";			   				
				echo "</td>\n";								
				echo "</tr>\n";		
			echo "</table>\n";	
			echo "</fieldset>\n";												
			echo "</td>\n";
			echo "<td align=\"right\" width=\"25%\"> &nbsp </td>\n";						
			echo "</tr>\n";
			echo "<tr height=\"25\">\n";	 	  	 	 	  	 	     
			echo "<td align=\"center\" colspan=\"3\">\n";   #TCol. 1-3
			echo "<font color=\"red\"> AVISO IMPORTANTE:</font><br /> Puede imprimir el sello una sola vez! Los datos quedar�n registrados en el sistema!\n";
			echo "</td>\n";
			echo "</tr>\n";																																	 						 
			echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=63&id=$session_id\" accept-charset=\"utf-8\">\n";			 				 
			echo "<tr>\n";	 
			echo "<td align=\"center\" valign=\"center\" colspan=\"3\">\n";   #Col. 1-3 
			echo "<input name=\"id_inmu\" type=\"hidden\" class=\"smallText\" value=\"$id_inmu\">\n";	
			echo "<input name=\"gestion\" type=\"hidden\" class=\"smallText\" value=\"$gestion\">\n";	
			echo "<input name=\"no_orden_sello\" type=\"hidden\" class=\"smallText\" value=\"$no_orden_sello\">\n";							
			echo "<input name=\"forma_pago_sello\" type=\"hidden\" class=\"smallText\" value=\"$forma_pago\">\n";
			echo "<input name=\"cuota_sello\" type=\"hidden\" class=\"smallText\" value=\"$cuota_sello\">\n";
			if (($cuota_sello == $cuota) AND ($cuota_sello != $cuota_tabla)) {
				echo "<input name=\"d10\" type=\"hidden\" class=\"smallText\" value=\"$d10\">\n";		
				echo "<input name=\"mant_val\" type=\"hidden\" class=\"smallText\" value=\"$mant_valor\">\n";										 
				echo "<input name=\"interes\" type=\"hidden\" class=\"smallText\" value=\"$interes\">\n";	
				echo "<input name=\"deb_for\" type=\"hidden\" class=\"smallText\" value=\"$multa_incum\">\n";
				echo "<input name=\"por_form\" type=\"hidden\" class=\"smallText\" value=\"$por_form\">\n";																								  			
				echo "<input name=\"monto\" type=\"hidden\" class=\"smallText\" value=\"$monto\">\n";
				echo "<input name=\"decont\" type=\"hidden\" class=\"smallText\" value=\"$descont_exen\">\n";
			}							
				echo "<input name=\"control_sello\" type=\"hidden\" class=\"smallText\" value=\"$control_sello\">\n";																			  
				echo "<input name=\"imprimir\" type=\"submit\" class=\"smallText\" value=\"IMPRIMIR SELLO\">\n";							 
				echo "</td>\n";	 	 
				echo "</tr>\n";
				echo "</form>\n";
				echo "</table>\n";  
				echo "</fieldset>\n";
         }  
			
	    echo "</td>\n";
	    echo "<td> &nbsp</td>\n"; 
	    echo "</tr>\n";					
   } elseif ($plan_de_pago_submit) {
		echo "<tr>\n";
		echo "<td> &nbsp</td>\n";  
		echo "<td valign=\"top\" height=\"40\">\n"; 
		if ($plan_de_pago_aceptado) {
			echo "<fieldset><legend>Datos Generales del Plan de Pago</legend>\n";			
		} else {
			echo "<fieldset><legend>Ingresar Datos para generar Plan de Pago</legend>\n";
		}
		echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=62&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
		echo "<table border=\"0\" width=\"100%\">\n"; 
		if ($exencion_seleccionada) {
			echo "<tr>\n";					
			echo "<td align=\"center\" class=\"bodyTextD\" colspan=\"4\">\n";
			echo "<font color=\"orange\">Aplicando Exención para $desc_select</font>\n"; 
			echo "</td>\n";
			echo "</tr>\n";				
		}					
		if ($plan_de_pago_aceptado) {		
			echo "<tr>\n";	 	 
			echo "<td align=\"right\" class=\"bodyTextD\">CODIGO DEL PREDIO:</td>\n"; 	 	 	 	  	 	     
			echo "<td align=\"center\" class=\"bodyTextD\">\n"; 
			echo "$cod_cat\n";
			echo "</td>\n";
			echo "<td align=\"right\" class=\"bodyTextD\">\n"; 
			echo "GESTION: $gestion\n";
			echo "</td>\n";
			echo "<td> &nbsp </td>\n"; 				 				  			   	     	  	 
			echo "</tr>\n";
			echo "<tr>\n";	 	 
			echo "<td align=\"right\" class=\"bodyTextD\">TITULAR:</td>\n";	 	 	 	  	 	     
			echo "<td align=\"left\" colspan=\"3\" class=\"bodyTextD\">\n";  #TCol. 2-4	
			echo "&nbsp $titular\n";
			echo "</td>\n";   	     	  	 
			echo "</tr>\n";				 
			}
	    echo "<tr>\n";	 	 
	    echo "<td align=\"right\" class=\"bodyTextD\">Monto a Pagar:</td>\n";   #TCol. 1 	 	 	 	  	 	     
	    echo "<td align=\"left\" colspan=\"2\" class=\"bodyTextD\">\n";  #TCol. 2-3	
			echo "&nbsp $total_a_pagar_exen Bs.\n";
			echo "</td>\n";   	     	  	 
	    echo "<td align=\"center\">\n";   #TCol. 4	
			echo "&nbsp\n";			  	  	 
	    echo "</td>\n";
	    echo "</tr>\n";
	    echo "<tr>\n";
	    echo "<td align=\"right\">\n";				
	    echo "Tasa de interes:\n";
			echo "</td>\n";  
	    echo "<td align=\"left\" colspan=\"3\">\n";
			if ($plan_de_pago_aceptado) {									
	       echo "&nbsp $tapr_ufv % (Tasa activa de paridad referencial en UFV actualizada +3)\n";
	    } else {
	       echo "<b>$tapr_ufv %</b> (Tasa activa de paridad referencial en UFV actualizada +3)\n";			
			}
			echo "</td>\n";			
	    echo "</tr>\n";									
	    echo "<tr>\n";	
	    echo "<td align=\"right\" width=\"25%\">\n";		 	 
	    echo "Cuota inicial:\n";
			echo "</td>\n";  
	    echo "<td align=\"center\" width=\"11%\">\n";
			if ($plan_de_pago_aceptado) {	
	       echo "                     $plan_cuota_inicial\n";			   				
			} else {
			   echo "                     <input name=\"cuota_inicial\" id=\"form_anadir2\" class=\"navText\" maxlength=\"4\" value=\"$plan_cuota_inicial\">\n";
	    }
			echo "                  </td>\n";
	    echo "                  <td align=\"left\" width=\"54%\">Bs.</td>\n";
			echo "                  <td align=\"left\" width=\"10%\"></td>\n";				
	    echo "               </tr>\n";		
	    echo "               <tr>\n";
	    echo "                  <td align=\"right\">\n";									
	    echo "                     Cantidad de Cuotas:\n";	
			echo "                  </td>\n";  
	    echo "                  <td align=\"center\">\n";		
			if ($plan_de_pago_aceptado) {	
	       echo "                     $plan_no_de_cuotas\n";			   				
			} else {			
			   echo "                     <input name=\"cuotas\" id=\"form_anadir2\" class=\"navText\" maxlength=\"2\" value=\"$plan_no_de_cuotas\">\n";
			}
			echo "                  </td>\n";
	    echo "                  <td colspan=\"2\"></td>\n";						
	    echo "               </tr>\n";		
	    echo "               <tr>\n";
	    echo "                  <td align=\"right\">\n";				
	    echo "                     Monto por Cuota:\n";
			echo "                  </td>\n";  
	    echo "                  <td align=\"center\">\n";	
			if ($plan_de_pago_aceptado) {	
	       echo "                     $plan_cuota\n";			   				
			} else {					
			   echo "                     <input name=\"plazo\" id=\"form_anadir2\" class=\"navText\" disabled=\"disabled\" value=\"$plan_cuota\">\n";
			}
			echo "                  </td>\n";
	    echo "                  <td align=\"left\" colspan=\"2\">Bs.</td>\n";			
	    echo "               </tr>\n";	
	    echo "               <tr>\n";				
	    echo "                  <td align=\"right\">\n";				
	    echo "                     Plazo total:\n";
			echo "                  </td>\n"; 			
	    echo "                  <td align=\"center\">\n";	
			$texto = "Meses";
			if ($plan_de_pago_aceptado) {	
	       echo "                     $plan_no_de_cuotas\n";
				 if ($plan_no_de_cuotas == 1) $texto = "Mes"; 			   				
			} else {					
			   echo "                     <input name=\"plazo\" id=\"form_anadir2\" class=\"navText\" disabled=\"disabled\" value=\"$plan_total\">\n";
			   if ($plan_total == 1) $texto = "Mes";	
			}
			echo "                  </td>\n";
	    echo "                  <td align=\"left\" colspan=\"2\">$texto</td>\n";			
	    echo "               </tr>\n";	
      if ($tasa_de_interes) {			
		echo "               <tr height=\"35\">\n";	
		echo "                  <td></td>\n";   #TCol. 1			 	  	 	 	  	 	     
		echo "                  <td align=\"left\" colspan=\"3\">\n"; #TCol. 2-4
		echo "                     <input name=\"id_inmu\" type=\"hidden\" class=\"smallText\" value=\"$id_inmu\">\n"; 
		echo "                     <input name=\"gestion\" type=\"hidden\" class=\"smallText\" value=\"$gestion\">\n";
		echo "                     <input name=\"exen_selected\" type=\"hidden\" class=\"smallText\" value=\"$exen_select\">\n";					 
		echo "                     <input name=\"exen_id\" type=\"hidden\" class=\"smallText\" value=\"$exen_select\">\n";				 			 
		echo "                     <input name=\"total_a_pagar\" type=\"hidden\" class=\"smallText\" value=\"$total_a_pagar\">\n";			
		echo "                     <input name=\"submit\" type=\"hidden\" class=\"smallText\" value=\"PLAN DE PAGO\">\n"; 							
		echo "                     <input name=\"calcular\" type=\"submit\" class=\"smallText\" value=\"Calcular\">\n";
		echo "                  </td>\n";  		
		echo "               </tr>\n";
			}
	    if ($error) {
		echo "               <tr>\n"; 	 
		echo "                  <td align=\"center\" height=\"20\" colspan=\"4\">\n";   #Col. 1-4 	 			 
		echo "                     <font color=\"red\">$mensaje_de_error</font> <br />\n";				 	    
		echo "                  </td>\n"; 
		echo "               </tr>\n";
	    }						     	  	 
	    echo "            </table>\n";  
	    echo "         </fieldset>\n";
	    echo "         </form>\n";
	    echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3
	    echo "      </tr>\n";
   }			
   if ($plan_de_pago)  {	
	    # Fila 2	 
	    echo "      <tr>\n";
      echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	    echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	    echo "         <fieldset><legend>Plan de Pago</legend>\n";
			if (!$plan_de_pago_aceptado) {			
         echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=62&id=$session_id\" accept-charset=\"utf-8\">\n";	 	   
	    }
			echo "            <table border=\"0\" width=\"100%\">\n";   # 11 TColumnas		
	    echo "               <tr>\n";	 	 
	    echo "                  <td width=\"1%\"> &nbsp </td>\n";   #TCol. 1 	 	 	 	  	 	     
	    echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">No.</td>\n";   #TCol. 2
	    echo "                  <td width=\"1%\"> &nbsp </td>\n";   #TCol. 3 				
	    echo "                  <td align=\"center\" width=\"34%\" class=\"bodyTextH\">Concepto</td>\n";   #TCol. 4
	    echo "                  <td width=\"1%\"> &nbsp </td>\n";   #TCol. 5 	
			echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextH\">Monto</td>\n";   #TCol. 6	
	    echo "                  <td width=\"1%\"> &nbsp </td>\n";   #TCol. 7 								 
	    echo "                  <td align=\"center\" width=\"16%\" class=\"bodyTextH\">Fecha Venc.</td>\n";   #TCol. 8	
	    echo "                  <td width=\"1%\"> &nbsp </td>\n";   #TCol. 9 	
			if ($plan_de_pago_aceptado) {
	       echo "                  <td align=\"center\" width=\"16%\" class=\"bodyTextH\">Fecha Pago</td>\n";   #TCol. 10		 
			   echo "                  <td width=\"1%\"> &nbsp </td>\n";   #TCol. 11
	       echo "                  <td align=\"center\" width=\"10%\">&nbsp </td>\n";   #TCol. 12					 
			} else {
	       echo "                  <td align=\"center\" width=\"14%\"> &nbsp </td>\n";   #TCol. 10
			   echo "                  <td width=\"1%\"> &nbsp </td>\n";   #TCol. 11
			   echo "                  <td width=\"10%\"> &nbsp </td>\n";   #TCol. 12				 				 
			}			
	    echo "                  <td width=\"1%\"> &nbsp </td>\n";   #TCol. 13 									
	    echo "                  </td>\n";
	    echo "               </tr>\n";
	    echo "               <tr>\n";	 	 
	    echo "                  <td> &nbsp </td>\n";   #TCol. 1 	 	 	 	  	 	     
	    echo "                  <td align=\"center\" class=\"bodyTextD\">1</td>\n";   #TCol. 2
	    echo "                  <td> &nbsp </td>\n";   #TCol. 3 				
	    echo "                  <td align=\"center\" class=\"bodyTextD\">Cuota Inicial</td>\n";   #TCol. 4
	    echo "                  <td> &nbsp </td>\n";   #TCol. 5 				
	    echo "                  <td align=\"center\" class=\"bodyTextD\">$plan_cuota_inicial</td>\n";   #TCol. 6		
	    echo "                  <td> &nbsp </td>\n";   #TCol. 7 								 
	    echo "                  <td align=\"center\" class=\"bodyTextD\">$fecha_venc[0]</td>\n";   #TCol. 8	
	    echo "                  <td> &nbsp </td>\n";   #TCol. 9	
			$boton_liquidar = true;	
			if ($plan_de_pago_aceptado) {
			   if (($fecha_pago[0] == "-") AND ($boton_liquidar)) {
				    echo "			            <form name=\"form1\" method=\"post\" action=\"index.php?mod=62&id=$session_id\" accept-charset=\"utf-8\">\n";	
	          echo "                  <td align=\"center\">\n";   #TCol. 10
	          echo "                     <input name=\"id_inmu\" type=\"hidden\" class=\"smallText\" value=\"$id_inmu\">\n";			
	          echo "                     <input name=\"gestion\" type=\"hidden\" class=\"smallText\" value=\"$gestion\">\n";	
	          echo "                     <input name=\"forma_pago\" type=\"hidden\" class=\"smallText\" value=\"PLAN\">\n";	
	          echo "                     <input name=\"no_cuota\" type=\"hidden\" class=\"smallText\" value=\"$no_cuota[0]\">\n";																		
	          echo "                     <input name=\"total_a_pagar\" type=\"hidden\" class=\"smallText\" value=\"$plan_cuota_inicial\">\n";			
	          echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Liquidar\">\n";
	          echo "                  </td>\n";	
	          echo "                  </form>\n";							
						$boton_liquidar = false;											
				 } else {
	          echo "                  <td align=\"center\" class=\"bodyTextD\">$fecha_pago[0]</td>\n";   #TCol. 10				 
				 }
	       echo "                  <td> &nbsp </td>\n";   #TCol. 11
         echo "			             <form name=\"form1\" method=\"post\" action=\"index.php?mod=61&id=$session_id\" accept-charset=\"utf-8\">\n";				 
			   echo "                  <td align=\"center\" class=\"bodyTextD\">\n";  #TCol. 12 	       
				 echo "                     <input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n"; 						
 	       echo "                     <input name=\"gestion\" type=\"hidden\" value=\"$gestion\">\n";
 	       echo "                     <input name=\"sistema\" type=\"hidden\" value=\"CAT\">\n";				 
	       echo "                     <input name=\"forma_pago\" type=\"hidden\" class=\"smallText\" value=\"PLAN\">\n";	
	       echo "                     <input name=\"no_cuota\" type=\"hidden\" class=\"smallText\" value=\"$no_cuota[0]\">\n";	
			   echo "                     <input name=\"cuota\" type=\"hidden\" class=\"smallText\" value=\"$plan_cuota_inicial\">\n";				 				 				 
         if ($fecha_pago[0] == "-") {      
 	          echo "                     <input name=\"boleta\" type=\"hidden\" value=\"AVISO\">\n";
 	          echo "                     <input type=\"image\" src=\"graphics/boton_aviso.png\" width=\"36\" height=\"12\" class=\"smallText\" name=\"submit\" value=\"Aviso\">\n";
			   } else {	 
 	          echo "                     <input name=\"boleta\" type=\"hidden\" value=\"BOLETA\">\n"; 
 	          echo "                     <input type=\"image\" src=\"graphics/boton_boleta.png\" width=\"36\" height=\"12\" class=\"smallText\" name=\"submit\" value=\"Boleta\">\n";	
		     }
	       echo "                  </td>\n";					 
				 echo "                  </form>\n";						 					 
			} else {
	       echo "                  <td colspan\"3\"> &nbsp </td>\n";   #TCol. 10-12			
			}				
	    echo "                  <td> &nbsp </td>\n";   #TCol. 13 	
	    echo "               </tr>\n";
			$i = 0;
			while ($i < $plan_no_de_cuotas) {
			   $k = $i+1;
			   $j = $i+2;
	       echo "               <tr>\n";	 	 
	       echo "                  <td> &nbsp </td>\n";   #TCol. 1 	 	 	 	  	 	     
	       echo "                  <td align=\"center\" class=\"bodyTextD\">$j</td>\n";   #TCol. 2
	       echo "                  <td> &nbsp </td>\n";   #TCol. 3 					 
	       echo "                  <td align=\"center\" class=\"bodyTextD\">Amortizaci�n e Intereses </td>\n";   #TCol. 4
	       echo "                  <td> &nbsp </td>\n";   #TCol. 5 						 
	       echo "                  <td align=\"center\" class=\"bodyTextD\">$plan_cuota</td>\n";   #TCol. 6	
	       echo "                  <td> &nbsp </td>\n";   #TCol. 7 						 					 
	       echo "                  <td align=\"center\" class=\"bodyTextD\">$fecha_venc[$k]</td>\n";   #TCol. 8	
	       echo "                  <td> &nbsp </td>\n";   #TCol. 9 
			   if ($plan_de_pago_aceptado) {
			      if (($fecha_pago[$k] == "-") AND ($boton_liquidar)) {
				       echo "			            <form name=\"form1\" method=\"post\" action=\"index.php?mod=62&id=$session_id\" accept-charset=\"utf-8\">\n";	
	             echo "                  <td align=\"center\">\n";   #TCol. 10
	             echo "                     <input name=\"id_inmu\" type=\"hidden\" class=\"smallText\" value=\"$id_inmu\">\n";			
	             echo "                     <input name=\"gestion\" type=\"hidden\" class=\"smallText\" value=\"$gestion\">\n";		
	             echo "                     <input name=\"forma_pago\" type=\"hidden\" class=\"smallText\" value=\"PLAN\">\n";	
						   echo "                     <input name=\"no_cuota\" type=\"hidden\" class=\"smallText\" value=\"$no_cuota[$k]\">\n";		 							 						
	             echo "                     <input name=\"total_a_pagar\" type=\"hidden\" class=\"smallText\" value=\"$plan_cuota\">\n";			
	             echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"Liquidar\">\n";
	             echo "                  </td>\n";	
	             echo "                  </form>\n";							
						   $boton_liquidar = false;	
			      }	else {
			         echo "                  <td align=\"center\" class=\"bodyTextD\">$fecha_pago[$k]</td>\n";   #TCol. 10			
            }			 
			      echo "                  <td> &nbsp </td>\n";   #TCol. 11
            echo "			            <form name=\"form1\" method=\"post\" action=\"index.php?mod=61&id=$session_id\" accept-charset=\"utf-8\">\n"; 
			      echo "                  <td align=\"center\" class=\"bodyTextD\">\n";  #TCol. 12
 	          echo "                     <input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n"; 						
 	          echo "                     <input name=\"gestion\" type=\"hidden\" value=\"$gestion\">\n";
 	          echo "                     <input name=\"sistema\" type=\"hidden\" value=\"CAT\">\n";							
	          echo "                     <input name=\"forma_pago\" type=\"hidden\" class=\"smallText\" value=\"PLAN\">\n";		
	          echo "                     <input name=\"no_cuota\" type=\"hidden\" class=\"smallText\" value=\"$no_cuota[$k]\">\n";	
	          echo "                     <input name=\"cuota\" type=\"hidden\" class=\"smallText\" value=\"$plan_cuota\">\n";																
            if ($fecha_pago[$k] == "-") {     
 	             echo "                     <input name=\"boleta\" type=\"hidden\" value=\"AVISO\">\n";
 	             echo "                     <input type=\"image\" src=\"graphics/boton_aviso.png\" width=\"36\" height=\"12\" class=\"smallText\" name=\"submit\" value=\"Aviso\">\n";
			      } else {	 
 	             echo "                     <input name=\"boleta\" type=\"hidden\" value=\"BOLETA\">\n"; 
 	             echo "                     <input type=\"image\" src=\"graphics/boton_boleta.png\" width=\"36\" height=\"12\" class=\"smallText\" name=\"submit\" value=\"Boleta\">\n";	
		        }				       
	          echo "                  </td>\n";	
	          echo "                  </form>\n";								
			   } else {
	          echo "                  <td colspan=\"3\"> &nbsp </td>\n";   #TCol. 10-12			
			   }	
	       echo "                  <td> &nbsp </td>\n";   #TCol. 13				 				 			
	       echo "               </tr>\n";
				 $i++;
			}	
			if (!$plan_de_pago_aceptado) {					
         echo "               <tr>\n";
#	       echo "                  <td> &nbsp </td>\n";   #TCol. 1 		 
	       echo "                  <td align=\"center\" colspan=\"9\">\n";   #Col. 1-6	 
	       echo "                     <input name=\"id_inmu\" type=\"hidden\" class=\"smallText\" value=\"$id_inmu\">\n"; 
         echo "                     <input name=\"gestion\" type=\"hidden\" class=\"smallText\" value=\"$gestion\">\n";				 
         echo "                     <input name=\"total_a_pagar\" type=\"hidden\" class=\"smallText\" value=\"$total_a_pagar\">\n";			
#	       echo "                     <input name=\"submit\" type=\"hidden\" class=\"smallText\" value=\"PLAN DE PAGO\">\n";			 	  
	       echo "                     <input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"ACEPTAR PLAN DE PAGO\">\n"; 
         echo "                  </td>\n";
	       echo "               <td> &nbsp </td>\n";   #TCol. 3 		 	 
         echo "               </tr>\n";
	       echo "         </form>\n";				 
			}	 
	    echo "            </table>\n";  
	    echo "         </fieldset>\n";
	    echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3
	    echo "      </tr>\n";	
	 }
   if ($convalidar_pago)  {	  
	    # Fila 2	 
	    echo "      <tr>\n";
      echo "         <td> &nbsp</td>\n";   #Col. 1  	 
	    echo "         <td valign=\"top\" height=\"40\">\n";   #Col. 2
	    echo "         <fieldset><legend>Convalidar Pago</legend>\n"; 	   
	    echo "            <table border=\"0\" width=\"100%\">\n";   # 3 TColumnas
      /*if ((isset($_POST["exen_select"])) AND ($_POST["exen_select"] > 0)) {
	       echo "               <tr>\n";		
	       echo "                  <td> &nbsp </td>\n";   #TCol. 1 					
         echo "                  <td align=\"center\" class=\"bodyTextD\" colspan=\"3\">\n";
				 echo "                     <font color=\"orange\">Aplicando Exenci�n para $desc_select</font>\n"; #Col. 2-4	
				 echo "                  </td>\n";
	       echo "                  <td></td>\n";   #TCol. 5 	
	       echo "               </tr>\n";				
			}		*/
			if ($registrar) {	
		     echo "			         <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=60&calc&id=$session_id\" accept-charset=\"utf-8\">\n";				
	    } else {
		     echo "			         <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=62&id=$session_id\" accept-charset=\"utf-8\">\n";				
			} 
			echo "               <tr>\n";
	    echo "                  <td align=\"right\" width=\"40%\">\n";	#TCol. 3								
	    echo "                     número de Orden: &nbsp\n";	
			echo "                  </td>\n";  
	    echo "                  <td align=\"left\" width=\"30%\">\n";		#TCol. 3
			echo "                     <input name=\"no_orden\" id=\"form_anadir2\" class=\"navText\" maxlength=\"8\" value=\"$no_orden_conv\" $disabled>\n";
			echo "                  </td>\n";
	    echo "                  <td width=\"30%\"> &nbsp</td>\n";   #TCol. 3 								
	    echo "               </tr>\n";
			echo "               <tr>\n";
	    echo "                  <td align=\"right\">\n";	#TCol. 3								
	    echo "                     Fecha de Pago: &nbsp\n";	
			echo "                  </td>\n";  
	    echo "                  <td align=\"left\">\n";		#TCol. 3
			echo "                     <input name=\"fech_imp\" id=\"form_anadir2\" class=\"navText\" maxlength=\"10\" value=\"$fech_imp_conv\" $disabled>\n";
			echo "                  </td>\n";
	    echo "                  <td> &nbsp</td>\n";   #TCol. 3 								
	    echo "               </tr>\n";
	    echo "               <tr>\n";
	    echo "                  <td align=\"right\">\n";									
	    echo "                     Monto pagado (en Bs.): &nbsp\n";	
			echo "                  </td>\n";  
	    echo "                  <td align=\"left\">\n";		
			echo "                     <input name=\"cuota\" id=\"form_anadir2\" class=\"navText\" maxlength=\"5\" value=\"$cuota_conv\" $disabled>\n";
			echo "                  </td>\n";
	    echo "                  <td> &nbsp</td>\n";   #TCol. 3 									
	    echo "               </tr>\n";	
	    echo "               <tr>\n";
	    echo "                  <td align=\"right\">\n";									
	    echo "                     número de Boleta: &nbsp\n";	
			echo "                  </td>\n";  
	    echo "                  <td align=\"left\">\n";		
			echo "                     <input name=\"control\" id=\"form_anadir2\" class=\"navText\" maxlength=\"8\" value=\"$control_conv\" $disabled>\n";
			echo "                  </td>\n";
	    echo "                  <td> &nbsp</td>\n";   #TCol. 3 									
	    echo "               </tr>\n";
	if ($error_conv) {
		echo "               <tr>\n";
		echo "                  <td align=\"center\" colspan=\"3\">\n";									
		echo "                     <font color=\"red\"> $mensaje_de_error </font>\n";	
		echo "                  </td>\n";  							
		echo "               </tr>\n";
	}

	echo "<tr>\n";	 
	echo "<td align=\"center\" valign=\"center\" colspan=\"3\">\n";   #Col. 1-2	 
	echo "<input name=\"id_inmu\" type=\"hidden\" class=\"smallText\" value=\"$id_inmu\">\n";	
	echo "<input name=\"gestion\" type=\"hidden\" class=\"smallText\" value=\"$gestion\">\n";	
	echo "<input name=\"total_a_pagar\" type=\"hidden\" class=\"smallText\" value=\"$total_a_pagar\">\n";			
	if ($registrar) {
		echo "<input name=\"no_orden\" type=\"hidden\" class=\"smallText\" value=\"$no_orden_conv\">\n";				
		echo "<input name=\"fech_imp\" type=\"hidden\" class=\"smallText\" value=\"$fech_imp_conv\">\n";				
		echo "<input name=\"cuota\" type=\"hidden\" class=\"smallText\" value=\"$cuota_conv\">\n";				
		echo "<input name=\"control\" type=\"hidden\" class=\"smallText\" value=\"$control_conv\">\n";							 			  	
		echo "<input name=\"Registrar\" type=\"submit\" class=\"smallText\" value=\"REGISTRAR\">\n";	
	} else {
		echo "<input name=\"submit\" type=\"submit\" class=\"smallText\" value=\"CONVALIDAR PAGO\">\n";			
	}
		echo "</td>\n";	 	 
		echo "</tr>\n";
		echo "</form>\n";
		echo "</table>\n";  
		echo "</fieldset>\n";
		echo "</td>\n";
		echo "<td> &nbsp</td>\n";   #Col. 3
		echo "</tr>\n";					 				 
	}	 	
	echo "<tr height=\"100%\">\n";
	echo "<td colspan=\"3\"> &nbsp</td>\n";   #Col. 1-3 	 
	echo "</tr>\n";		  	 
	echo "</table>\n";
	echo "</td>\n";	

} 	 		
?>
