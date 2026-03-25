<?php
################################################################################
#------------------ TABLA A: VALOR METRO CUADRADO POR ZONA --------------------#
################################################################################	

$sql="SELECT ufv,zona,asf,adq,cem,los,pdr,rip,trr,lad FROM imp_fact_zona WHERE gestion = '$gestion' ORDER BY zona";
$no_de_zonas = pg_num_rows(pg_query($sql));
if ($no_de_zonas == 0) {
				 #$gestion_ant = $gestion-1;
			   #$ufv = imp_getcoti ($gestion."-12-31","ufv");
				 #$ufv_ant = imp_getcoti($gestion_ant."-12-31","ufv");
				# $factor_ufv = $ufv/$ufv_ant;
				# $actualizar_tabla = actualizar_tabla ("imp_fact_zona", $gestion, $factor_ufv);
			  # $check_fact_zona = pg_num_rows(pg_query($sql));		 		   			 
}	else {
   $result = pg_query($sql);
   $i = $j = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
         foreach ($line as $col_value) {
	          if ($i == 0) { $ufv[$j] = $col_value; 	 
	          } elseif ($i == 1) { $zona[$j] = $col_value; 
			      } elseif ($i == 2) { $asf[$j] = $col_value; 
			      } elseif ($i == 3) { $adq[$j] = $col_value; 
			      } elseif ($i == 4) { $cem[$j] = $col_value; 
			      } elseif ($i == 5) { $los[$j] = $col_value; 
			      } elseif ($i == 6) { $pdr[$j] = $col_value; 
			      } elseif ($i == 7) { $rip[$j] = $col_value;
			      } elseif ($i == 8) { $trr[$j] = $col_value; 
			      } else { 
			         $lad[$j] = $col_value;
				       $i = -1;
			      }
			      $i++;
         }
	       $j++;
   }
} 			
pg_free_result($result);
################################################################################
#--------------------- TABLA B: INCLINACION DEL TERRENO -----------------------#
################################################################################	
$sql="SELECT * FROM imp_fact_inclinacion WHERE gestion = '$gestion'";
$check_incl = pg_num_rows(pg_query($sql));			
$result = pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$terreno[0] = $info['plano'];
$terreno[1] = $info['inclinado'];
$terreno[2] = $info['muy_inclinado'];
pg_free_result($result);	
################################################################################
#--------------------- TABLA C: EXISTENCIA DE SERVICIOS -----------------------#
################################################################################	
$sql="SELECT * FROM imp_fact_servicios WHERE gestion = '$gestion'";
$check_servicios = pg_num_rows(pg_query($sql));
$result = pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
if ($info['serv_luz'] == "") {
   $serv_luz = "-";
} else $serv_luz = $info['serv_luz'];
if ($info['serv_luz'] == "") {
   $serv_agu = "-";
} else $serv_agu = $info['serv_agua'];
if ($info['serv_alc'] == "") {
   $serv_alc = "-";
} else $serv_alc = $info['serv_alc'];
if ($info['serv_tel'] == "") {
   $serv_tel = "-";
} else $serv_tel = $info['serv_tel'];
if ($info['serv_min'] == "") {
   $serv_min = "-";
} else $serv_min = $info['serv_min'];
$serv_serv = $info['serv_serv']; 	
pg_free_result($result);
################################################################################
#-------------------- TABLAS D: VALUACION DE CONSTRUCCION ---------------------#
################################################################################	
$sql="SELECT * FROM imp_valua_viv_ph WHERE gestion = '$gestion'";
$result = pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
if ($info['lujoso'] == "") {
   $lujoso_ph = "-";
} else $lujoso_ph = $info['lujoso'];
if ($info['mbueno'] == "") {
   $mbueno_ph = "-";
} else $mbueno_ph = $info['mbueno'];
if ($info['bueno'] == "") {
   $bueno_ph = "-";
} else $bueno_ph = $info['bueno'];
if ($info['econo'] == "") {
   $econo_ph = "-";
} else $econo_ph = $info['econo'];
pg_free_result($result);	
$sql="SELECT * FROM imp_valua_viv_vf WHERE gestion = '$gestion'";
$result = pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$lujoso_vf = $info['lujoso'];
$mbueno_vf = $info['mbueno'];
$bueno_vf = $info['bueno'];
$econo_vf = $info['econo'];
$mecono_vf = $info['mecono'];
$margin_vf = $info['margin']; 	
pg_free_result($result); 			
################################################################################
#------------------- TABLA E: DEPRECIACION POR ANTIGUEDAD --------------------#
################################################################################	
$sql="SELECT antig, factor FROM imp_fact_deprec WHERE gestion = '$gestion' ORDER BY antig";
$check_fact_deprec = pg_num_rows(pg_query($sql));
$result = pg_query($sql);	
#$antig[0] = $factor[0] = 0;		
$i = 0;
$j = 0;
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {      
   foreach ($line as $col_value) { 
	    if ($i == 0) {
			   $antig[$j] = $col_value;    
		  } elseif ($i == 1) {
		     $factor[$j] = $col_value;	
				 $i = -1;
      }
			$i++;
	 }
   $j++;
}
$antig[$j] = "En adelante"; 	
pg_free_result($result);	
################################################################################
#------------------------- TABLA F: ESCALA IMPOSITIVA -------------------------#
################################################################################
$sql="SELECT monto, cuota, mas_porc, exced FROM imp_escala_imp WHERE gestion = '$gestion' ORDER BY monto";
$check_escala_imp = pg_num_rows(pg_query($sql));
if ($check_escala_imp == 0) {
   $monto[0] = $cuota[0] = $mas_porc[0] = $exced[0] =  $exced[1] ="-";
} else {
   $result = pg_query($sql);	
   $i = $j = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {      
            foreach ($line as $col_value) { 
			         if ($i == 0) {
				          $monto[$j] = $col_value;
				       } elseif ($i == 1) {
				          $cuota[$j] = $col_value;
				       } elseif ($i == 2) {
				          $mas_porc[$j] = ROUND ($col_value,2);								 			    
				       } elseif ($i == 3) {
				          $exced[$j] = $col_value;	
							    $i = -1;
				       }
						   $i++;
	          }
				    $j++;
   }
   $monto[$j] = $exced[$j] = "En adelante"; 	
   pg_free_result($result);
}  
################################################################################
#----------------------- TABLA G: FECHA DE VENCIMIENTO ------------------------#
################################################################################
$sql="SELECT fecha_venc, fecha_mod1, fecha_mod2, fecha_mod3 FROM imp_fecha_venc WHERE gestion = '$gestion'";
$fila_con_fechas = pg_num_rows(pg_query($sql));	
$result = pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
if ($info['fecha_venc'] == "") {
   $fecha_venc = "-";
} else $fecha_venc = change_date ($info['fecha_venc']);
if ($info['fecha_mod1'] == "") {
   $fecha_mod1 = "-";
} else $fecha_mod1 = change_date ($info['fecha_mod1']);	
if ($info['fecha_mod2'] == "") {
   $fecha_mod2 = "-";
} else $fecha_mod2 = change_date ($info['fecha_mod2']);	
if ($info['fecha_mod3'] == "") {
   $fecha_mod3 = "-";
} else $fecha_mod3 = change_date ($info['fecha_mod3']);							
pg_free_result($result);
################################################################################
#----------------------------------- NOTA -------------------------------------#
################################################################################	
#$sql="SELECT nota_list FROM imp_base";
#$result = pg_query($sql);
#$info = pg_fetch_array($result, null, PGSQL_ASSOC);
#$nota_listado_por_rubro = utf8_decode ($info['nota_list']);
#pg_free_result($result);	
################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	

$filename = "C:/apache/htdocs/tmp/reporte".$user_id.".html";

################################################################################
#------------------------ PREPARAR CONTENIDO PARA IFRAME ----------------------#
################################################################################	
$content = " 
<div align='left'>
<table border='0' width='100%' height='500' style='font-family: Tahoma; font-size: 8pt' bgcolor='#FFFFFF'><font face='Tahoma' size='2'>
   <tr height='20px'>
      <td> &nbsp</td>
      <td align='right' valign='top' colspan='2'>
          <a href='javascript:print(this.document)'>
          <img border='0' src='http://$server/$folder/graphics/printer.png' width='20' height='20' title='Imprimir en hoja tamaño Carta'></a>
      </td>
   </tr>		 	 	 	  
	 <tr height='700px'>
	    <td width='5%'> &nbsp</td>
      <td align='center' valign='top' width='90%' bgcolor='#FFFFFF'>
			   <table border='0' width='100%' bgcolor='#FFFFFF' style='font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
				    <tr height='30'>
						   <td align='left'>&nbsp </td>
               <td align='left' valign='middle' colspan='2'>				 
                  <br /><br />SISTEMA INTEGRAL DE CATASTRO v.$version
							 </td>
               <td align='right' valign='middle'>				 
                  <br /><br />Fecha: $fecha2
							 </td>							 
							 <td align='left'>&nbsp </td>
						</tr>
				    <tr height='30'>
						   <td align='left'>&nbsp </td>
               <td align='left' valign='middle' colspan='2'>				 
                   GOBIERNO MUNICIPAL: $municipio, DISTRITO $distrito
							 </td>
               <td align='right' valign='middle'>				 
                  Hora: $hora
							 </td>								 
							 <td align='left'>&nbsp </td>
						</tr>	
				    <tr height='30'>
						   <td align='left'>&nbsp </td>
               <td align='center' valign='middle' colspan='3' style='font-family: Tahoma; font-size: 10pt; font-weight:bold;'>				 
                   REPORTE: Tablas Base [Bienes Inmuebles]
							 </td>
							 <td align='left'>&nbsp </td>
						</tr>	
				    <tr height='30'>
						   <td align='left'>&nbsp </td>
               <td align='center' valign='middle' colspan='3'>				 
                   GESTION $gestion
							 </td>
							 <td align='left'>&nbsp </td>
						</tr>			
						<tr height='20'>
						   <td width='5%'> &nbsp </td>							
						   <td width='35%'> &nbsp </td>	
							 <td width='38%'> &nbsp </td>	
							 <td width='17%'> &nbsp </td>	
						   <td width='5%'> &nbsp </td>								 						 				 							 						 
					  </tr> 																					
				    <tr height='20'>
						   <td> &nbsp </td>	
               <td align='left' colspan='3'>								
				          A) Valor Metro Cuadrado por Zonas y Material en Vias<br />
						   </td>
						   <td> &nbsp </td>								 
				    </tr> 						
						<tr height='20'>
						   <td> &nbsp </td>	
						   <td align='center' colspan='3'>
			            <table border='1' width='100%' bgcolor='#FFFFFF' style='border:1px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
				             <tr height='20'>
						            <td align='center' width='11%'> Zona </td>
											  <td align='center' width='11%'> Asfalto </td>
											  <td align='center' width='11%'> Adoquin </td>
											  <td align='center' width='11%'> Cemento </td>
											  <td align='center' width='11%'> Loseta </td>
											  <td align='center' width='11%'> Piedra </td>
											  <td align='center' width='11%'> Ripio </td>
											  <td align='center' width='11%'> Tierra </td>
											  <td align='center' width='12%'> Ladrillo </td>										     
										 </tr>";
if ($no_de_zonas == 0) {
   $content = $content."
				             <tr>
						            <td align='center' colspan='9'> No existen valores en la base de datos de la gestión seleccionada. </td>									     
										 </tr>";	  	
} else {
   $content = $content."							 
				             <tr>
						            <td align='center'> $zona[0]";
   $i = 1;
	 while ($i < $no_de_zonas) {
      $content = $content."	 
	       <br />$zona[$i]";
	    $i++;
	 }
   $content = $content."												
											  </td>
											  <td align='right'> $asf[0] &nbsp";
   $i = 1;
	 while ($i < $no_de_zonas) {
      $content = $content."	 
	       <br />$asf[$i] &nbsp";
	    $i++;
	 }
   $content = $content."													
												</td>
											  <td align='right'> $adq[0] &nbsp"; 
   $i = 1;
	 while ($i < $no_de_zonas) {
      $content = $content."	 
	       <br />$adq[$i] &nbsp";
	    $i++;
	 }
   $content = $content."													
												</td>
											  <td align='right'> $cem[0] &nbsp";
   $i = 1;
	 while ($i < $no_de_zonas) {
      $content = $content."	 
	       <br />$cem[$i] &nbsp";
	    $i++;
	 }
   $content = $content."												 
												</td>
											  <td align='right'> $los[0] &nbsp";
   $i = 1;
	 while ($i < $no_de_zonas) {
      $content = $content."	 
	       <br />$los[$i] &nbsp";
	    $i++;
	 }
   $content = $content."													
												</td>
											  <td align='right'> $pdr[0] &nbsp"; 
   $i = 1;
	 while ($i < $no_de_zonas) {
      $content = $content."	 
	       <br />$pdr[$i] &nbsp";
	    $i++;
	 }
   $content = $content."													
												</td>
											  <td align='right'> $rip[0] &nbsp"; 
   $i = 1;
	 while ($i < $no_de_zonas) {
      $content = $content."	 
	       <br />$rip[$i] &nbsp";
	    $i++;
	 }
   $content = $content."													
												</td>
											  <td align='right'> $trr[0] &nbsp"; 
   $i = 1;
	 while ($i < $no_de_zonas) {
      $content = $content."	 
	       <br />$trr[$i] &nbsp";
	    $i++;
	 }
   $content = $content."													
												</td>
											  <td align='right'> $lad[0] &nbsp";
   $i = 1;
	 while ($i < $no_de_zonas) {
      $content = $content."	 
	       <br />$lad[$i] &nbsp";
	    $i++;
	 }
   $content = $content."													
												</td>										     								     
										 </tr>";	
}									 
$content = $content." 										 										 									 
							    </table>	 
							 </td>	
						   <td> &nbsp </td>								 							 						 				 							 						 
					  </tr> 
				    <tr height='20'>
						   <td colspan='5'> &nbsp </td>
					  </tr> 							 						
				    <tr height='20'>
						   <td> &nbsp </td>	
               <td align='left'>								
				          B) Inclinación del Terreno
						   </td>
               <td align='left' colspan='2'>								
				          &nbsp&nbsp&nbsp&nbsp C) Existencia de Servicios
						   </td>							 
						   <td> &nbsp </td>								 
				    </tr>
						<tr height='20'>
						   <td> &nbsp </td>	
						   <td align='center'>
			            <table border='1' width='100%' bgcolor='#FFFFFF' style='border:1px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
				             <tr height='20'>
						            <td align='center' width='33%'> Plano </td>
											  <td align='center' width='34%'> Inclinado </td>
											  <td align='center' width='33%'> Muy Incl. </td>							     
										 </tr>
				             <tr>
						            <td align='right'> $terreno[0] &nbsp </td>
											  <td align='right'> $terreno[1] &nbsp </td>
											  <td align='right'> $terreno[2] &nbsp </td>							     
										 </tr>											 	
							    </table>		 
							 </td>	
						   <td align='center' colspan='2'>
			            <table border='1' width='90%' bgcolor='#FFFFFF' style='border:1px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
				             <tr height='20'>
						            <td align='center' width='20%'> Luz </td>
											  <td align='center' width='20%'> Agua </td>
											  <td align='center' width='20%'> Alcant. </td>
											  <td align='center' width='20%'> Telefono </td>	
											  <td align='center' width='20%'> Mínimo </td>								     
										 </tr>								 
				             <tr>
						            <td align='right'> $serv_luz &nbsp </td>
											  <td align='right'> $serv_agu &nbsp </td>
											  <td align='right'> $serv_alc &nbsp </td>
											  <td align='right'> $serv_tel &nbsp </td>
											  <td align='right'> $serv_min &nbsp </td>																																	     
										 </tr>											 	
							    </table>		 
							 </td>							 
						   <td> &nbsp </td>	
				    </tr>		
				    <tr height='20'>
						   <td colspan='5'> &nbsp </td>
					  </tr> 																	
				    <tr height='20'>
						   <td> &nbsp </td>	
               <td align='left' colspan='3'>								
				          D) Valuación de Construcciones
						   </td>
						   <td> &nbsp </td>								 
				    </tr>
				    <tr height='10'>
						   <td> &nbsp </td>	
               <td align='center'>								
				          Propiedad Horizontal
						   </td>
               <td align='center' colspan='2'>								
				          Vivienda Unifamiliar
						   </td>							 
						   <td> &nbsp </td>								 
				    </tr>						 						
						<tr height='20'>
						   <td> &nbsp </td>	
						   <td align='center'>
			            <table border='1' width='100%' bgcolor='#FFFFFF' style='border:1px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
				             <tr height='20'>
						            <td align='center' width='25%'> Lujoso </td>
											  <td align='center' width='25%'> M.Bueno </td>
											  <td align='center' width='25%'> Bueno </td>
											  <td align='center' width='25%'> Econ. </td>									     
										 </tr>
				             <tr>
						            <td align='right'> $lujoso_ph &nbsp </td>
											  <td align='right'> $mbueno_ph &nbsp </td>
											  <td align='right'> $bueno_ph &nbsp </td>
											  <td align='right'> $econo_ph &nbsp </td>									     
										 </tr>											 	
							    </table>		 
							 </td>	
						   <td align='center' colspan='2'>
			            <table border='1' width='100%' bgcolor='#FFFFFF' style='border:1px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
				             <tr height='20'>
						            <td align='center' width='16%'> Lujoso </td>
											  <td align='center' width='16%'> M.Bueno </td>
											  <td align='center' width='15%'> Bueno </td>
											  <td align='center' width='15%'> Econ. </td>	
											  <td align='center' width='16%'> M.Econ. </td>
											  <td align='center' width='16%'> Marginal </td>									     
										 </tr>
				             <tr>
						            <td align='right'> $lujoso_vf &nbsp </td>
											  <td align='right'> $mbueno_vf &nbsp </td>
											  <td align='right'> $bueno_vf &nbsp </td>
											  <td align='right'> $econo_vf &nbsp </td>
											  <td align='right'> $mecono_vf &nbsp </td>
											  <td align='right'> $margin_vf &nbsp </td>																																	     
										 </tr>											 	
							    </table>		 
							 </td>							 
						   <td> &nbsp </td>	
				    </tr>
				    <tr height='20'>
						   <td colspan='5'> &nbsp </td>
					  </tr> 						
				    <tr height='20'>
						   <td> &nbsp </td>	
               <td align='left'>								
				          E) Depreciación por Antiguedad
						   </td>
               <td align='left' colspan='2'>								
				          &nbsp F) Participación (Escala Impositiva)
						   </td>							 
						   <td> &nbsp </td>								 
				    </tr>							
						<tr>
						   <td> &nbsp </td>	
						   <td align='center'>
			            <table border='1' width='80%' bgcolor='#FFFFFF' style='border:1px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
				             <tr height='20'>
						            <td align='center' width='60%'> Edad de Construcción </td>
											  <td align='center' width='40%'> Factor </td>						     
										 </tr>
				             <tr>
						            <td align='right'> 0 a $antig[0] &nbsp ";
$i = 1;
while ($i < $check_fact_deprec) {
   $valor_temp = $antig[$i-1]+1;
   $content = $content."	 
	                      <br /> $valor_temp a $antig[$i] &nbsp"; 
   $i++;
}
$content = $content."
									      </td>
											  <td align='right'> $factor[0] &nbsp ";
$i = 1;
while ($i < $check_fact_deprec) {
   $content = $content."	 
	                      <br /> $factor[$i] &nbsp"; 
   $i++;
}												
$content = $content."												
												</td>						     
										 </tr>											 	
							    </table>		 
							 </td>	
						   <td align='center' valign='top' colspan='2'>
			            <table border='1' width='100%' bgcolor='#FFFFFF' style='border:1px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
				             <tr height='20'>
						            <td align='center' width='40%'> Monto de Valuación <br />(en Bs.) </td>										 
						            <td align='center' width='20%'> Cuota Fija </td>
											  <td align='center' width='20%'> Mas % </td>
											  <td align='center' width='20%'> Excedente </td>								     
										 </tr>
				             <tr>
						            <td align='right'> $monto[0] - $exced[1] &nbsp";
$i = 1;
while ($i < $check_escala_imp) {
   $ii = $i+1;
   $content = $content."	 
	                      <br /> $monto[$i] - $exced[$ii] &nbsp"; 
   $i++;
}
$content = $content."												 
											  </td>
											  <td align='right'> $cuota[0] &nbsp";
$i = 1;
while ($i < $check_escala_imp) {
   $content = $content."	 
	                      <br /> $cuota[$i] &nbsp"; 
   $i++;
}
$content = $content."													 
											  </td>
											  <td align='right'> $mas_porc[0] &nbsp";
$i = 1;
while ($i < $check_escala_imp) {
   $content = $content."	 
	                      <br /> $mas_porc[$i] &nbsp"; 
   $i++;
}
$content = $content."												 
											  </td>	
											  <td align='right'> $exced[0] &nbsp";
$i = 1;
while ($i < $check_escala_imp) {
   $content = $content."	 
	                      <br /> $exced[$i] &nbsp"; 
   $i++;
}
$content = $content."												
											  </td>																																													     
										 </tr>										 	 											 	
							    </table>		 
							 </td>							 
						   <td> &nbsp </td>	
				    </tr>	
				    <tr height='20'>
						   <td colspan='5'> &nbsp </td>
					  </tr> 						
				    <tr height='20'>
						   <td> &nbsp </td>	
               <td align='left' colspan='3'>								
				          G) Plazos de Vencimiento
						   </td>						 
						   <td> &nbsp </td>								 
				    </tr>	
						<tr>
						   <td> &nbsp </td>	
						   <td align='center' colspan='3'>
			            <table border='1' width='80%' bgcolor='#FFFFFF' style='border:1px solid black; border-collapse:collapse; font-family: Tahoma; font-size: 8pt; font-weight:bold;'>
				             <tr height='20'>
						            <td align='center' width='25%'> Fecha Venc. RS </td>
											  <td align='center' width='25%'> Fecha Mod. 1 </td>	
						            <td align='center' width='25%'> Fecha Mod. 2 </td>
											  <td align='center' width='25%'> Fecha Mod. 3 </td>																	     
										 </tr>
				             <tr>
						            <td align='center'> $fecha_venc </td>
											  <td align='center'> $fecha_mod1 </td>	
						            <td align='center'> $fecha_mod2 </td>
											  <td align='center'> $fecha_mod3 </td>																	     
										 </tr>										 		
							    </table>		 
							 </td>							 
						   <td> &nbsp </td>	
				    </tr>										 																														 					
						<tr height='20' style='font-family: Tahoma; font-size: 8pt;'>					 					
						   <td align='left' colspan='5'> &nbsp </td>
					  </tr>							
																																		
         </table>				 			 
			</td>
	    <td width='5%'> &nbsp </td>			
   </tr> 								  	 
</table>
</div>";
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