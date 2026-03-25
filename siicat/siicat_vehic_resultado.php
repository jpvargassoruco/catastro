<?php

$vehic_existe = true;
########################################
#------- RESULTADO DE BUSQUEDA---------#
########################################	
if (isset($_POST["veh_plc"]) OR (isset($_GET["plc"]))) { 
   if (isset($_POST["veh_plc"])) {
      $veh_plc = $_POST["veh_plc"];
	 } else {
      $veh_plc = $_GET["plc"];	 
	 }
	 if  ($veh_plc != "") {
      ########################################
      #-------- LEER DATOS DE TABLA ---------#
      ########################################	
      $sql="SELECT * FROM vehic WHERE veh_plc = '$veh_plc'";
      $check_vehic = pg_num_rows(pg_query($sql)); 
      if ($check_vehic == 1) {	 
         $result=pg_query($sql);
         $info = pg_fetch_array($result, null, PGSQL_ASSOC);
         $cod_geo = $info['cod_geo'];
         $cod_uv = $info['cod_uv'];
         $cod_man = $info['cod_man'];
         $cod_lote = $info['cod_lote'];
         $cod_subl = $info['cod_subl'];
         $cod_cat = get_codcat($cod_uv,$cod_man,$cod_lote,$cod_subl);
				 $pad_mun = $info['pad_mun'];
         $veh_1pat = utf8_decode($info['veh_1pat']);
         $veh_1mat = utf8_decode($info['veh_1mat']);
         $veh_1nom1 = utf8_decode($info['veh_1nom1']);
         $veh_1nom2 = utf8_decode($info['veh_1nom2']);
				 $veh_prop = get_titular ($veh_1nom1,$veh_1nom2,$veh_1pat,$veh_1mat);
         $veh_1ci = $info['veh_1ci'];				 
				 
         $veh_plc = $info['veh_plc'];
				 $veh_pol = $info['veh_pol'];
         $veh_mrc = utf8_decode($info['veh_mrc']);
         $veh_mod = utf8_decode($info['veh_mod']);
         $veh_col = $info['veh_col'];
         $veh_ano = $info['veh_ano'];
         $veh_cls = $info['veh_cls'];	
				 $veh_cls = get_vehcls($veh_cls);
         $veh_proc = utf8_decode($info['veh_proc']);			 
         $veh_cc = $info['veh_cc'];
				 if (($veh_cc == "") OR ($veh_cc == "-1")) {
				    $veh_cc = "-";
				 }		 
         $veh_serv = $info['veh_serv'];
				 $veh_dob = $info['veh_dob'];
				 $veh_tur = $info['veh_tur'];
				 $veh_pta = $info['veh_pta'];				 				 
				 if ($info['veh_tn'] == "-1") {
				    $veh_tn = "-";
				 } else $veh_tn = $info['veh_tn'];
				 if ($info['veh_plz'] == "-1") {
				    $veh_plz = "-";
				 } else $veh_plz = $info['veh_plz'];				 
				 $veh_chs = $info['veh_chs'];				 
				 $veh_car = $info['veh_car'];	
				 if ($info['veh_val'] == "-1") {
				    $veh_val = "-";
				 } else $veh_val = $info['veh_val'];				 					 
     /*    $veh_dpto = $info['veh_dpto'];
         $veh_ciu = utf8_decode($info['veh_ciu']);
         $veh_dir = utf8_decode($info['veh_dir']);
				 if ($veh_ciu != "") {
				    $veh_dom = $veh_dpto." ".$veh_ciu;
						if ($veh_dir != "") {
						   $veh_dom = $veh_dpto." ".$veh_ciu." ".$veh_dir;
						} else $veh_dom = $veh_dpto." ".$veh_ciu;
				 } else $veh_dom = $veh_dpto;
		*/  
		     $veh_dom = "-";
			   pg_free_result($result);
      } else $vehic_existe = false;
   } else $vehic_existe = false;			
}

$distrito = "CONCEPCION";
$deuda_existe = true;
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	
	 echo "<td>\n";
	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
   # Fila 1
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"10%\">\n";  #Col. 1 
   echo "            &nbsp&nbsp <a href=\"index.php?mod=111&id=$session_id\">\n";		
#   echo "            <img border='1' src='http://$server/$folder/graphics/boton_atras.png' width='35' height='35'></a>\n"; 
   echo "            <img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";	
	 echo "         </td>\n";   	    
   echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n"; 
	 echo "            Vehículo\n";	                           
   echo "         </td>\n";
	 echo "         <td width=\30%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";	
if ($vehic_existe) {	 
   echo "      <tr>\n";  
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	 
	 echo "            <fieldset><legend>Datos del Vehículo</legend>\n";	 
	 echo "            <table width=\"100%\" border=\"0\">\n";		# 5 Columnas		
 	 echo "               <tr height=\"40\">\n";		
	 echo "                  <td align=\"left\" colspan=\"4\"><b>IDENTIFICACION</b></td>\n";	 	
	 echo "               </tr>\n";
 	 echo "               <tr>\n";				 			            	  
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Placa &nbsp</td>\n";
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $veh_plc</td>\n";		 			            
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Poliza &nbsp</td>\n";
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $veh_pol</td>\n";				 				 
   echo "               </tr>\n";	
 	 echo "               <tr>\n";				 			             
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Propietario &nbsp</td>\n";
	 echo "                  <td align=\"left\" colspan=\"3\" class=\"bodyTextH\">&nbsp $veh_prop</td>\n";				 				 
   echo "               </tr>\n";			 	 
 	 echo "               <tr>\n";				 			           	  
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Cod. Catastral &nbsp</td>\n";
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $cod_cat</td>\n";	
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Distrito &nbsp</td>\n"; 
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $distrito</td>\n";		 			 				 
   echo "               </tr>\n";	
 	 echo "               <tr>\n";				 			             
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Domicilio &nbsp</td>\n";
	 echo "                  <td align=\"left\" colspan=\"3\" class=\"bodyTextH\">&nbsp $veh_dom</td>\n";				 				 
   echo "               </tr>\n";		 		 	 	  	
 	 echo "               <tr height=\"40px\">\n";		
	 echo "                  <td align=\"left\" colspan=\"4\"><b>DATOS VEHICULO</b></td>\n";		
	 echo "               </tr>\n";
 	 echo "               <tr>\n";				 			           	  
	 echo "                  <td align=\"right\" width=\"22%\" class=\"bodyTextD\">Marca &nbsp</td>\n";
	 echo "                  <td align=\"left\" width=\"28%\" class=\"bodyTextH\">&nbsp $veh_mrc</td>\n";	 
	 echo "                  <td align=\"right\" width=\"22%\" class=\"bodyTextD\">Doble Tracción &nbsp</td>\n";
	 echo "                  <td align=\"left\" width=\"28%\" class=\"bodyTextH\">&nbsp $veh_dob</td>\n";	 			 				 
   echo "               </tr>\n";	
 	 echo "               <tr>\n";				 			            
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Modelo &nbsp</td>\n";
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $veh_mod</td>\n";
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Turbinado &nbsp</td>\n";
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $veh_tur</td>\n";	 				 				 
   echo "               </tr>\n";	
 	 echo "               <tr>\n";				 			             
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Color &nbsp</td>\n";
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $veh_col</td>\n";		
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Puertas &nbsp</td>\n";
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $veh_pta</td>\n";	 		 				 
   echo "               </tr>\n";	
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Año &nbsp</td>\n";
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $veh_ano</td>\n";		
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Tonelaje &nbsp</td>\n";
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $veh_tn</td>\n";		 				 
   echo "               </tr>\n";	
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Clase &nbsp</td>\n";
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $veh_cls</td>\n";		
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Plazas &nbsp</td>\n";
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $veh_plz</td>\n";	 		 				 
   echo "               </tr>\n";	 
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Procedencia &nbsp</td>\n";
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $veh_proc</td>\n";	 
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Chasis &nbsp</td>\n";
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $veh_chs</td>\n";	 			 				 
   echo "               </tr>\n";	
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Cc. &nbsp</td>\n";
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $veh_cc</td>\n";	 
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Car &nbsp</td>\n";
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $veh_car</td>\n";	 			 				 
   echo "               </tr>\n";	 	
 	 echo "               <tr>\n";				 			           
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Servicio &nbsp</td>\n";
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $veh_serv</td>\n"; 
	 echo "                  <td align=\"right\" class=\"bodyTextD\">Valor &nbsp</td>\n";
	 echo "                  <td align=\"left\" class=\"bodyTextH\">&nbsp $veh_val</td>\n";	 				 				 
   echo "               </tr>\n";		 			 				  	 	 	  
 	 echo "               <tr>\n";		
	 echo "                  <td align=\"left\" colspan=\"4\" height=\"30px\">&nbsp</td>\n";	
	 echo "               </tr>\n";
	 echo "            </table>\n";
 	 echo "            </fieldset>\n";
	 echo "         </td>\n";	
 	 echo "      </tr>\n"; 	 
} else { ### NO PLACA NO EXISTE

   echo "      <tr height=\"40\">\n";  
	 echo "         <td> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td align=\"center\" class=\"bodyText\">\n";  #Col. 2	
 	 echo "         <fieldset><legend>Datos de la vehividad Económica</legend>\n";	
	 echo "             NO SE HA REGISTRADO AUN LA PLACA INGRESADA! <br />POR FAVOR, REVISE EL NUMERO INGRESADO!"; 	 
	 echo "         </fieldset>\n";	 
	 echo "         <td> &nbsp</td>\n";  	 	 	 
 	 echo "         </td>\n";		 
 	 echo "      </tr>\n";  	 	 	   
}
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
#	 echo "   <br />&nbsp;<br />\n";
	 echo "</td>\n";	
	
?>