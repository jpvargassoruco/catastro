<?php

$mostrar = false; 
$resultado = false;
#$dos_resultados = false;
$error = false;
$predio_existe = false;
$geometria_existe = true;
#if (isset($_POST["search_string"])) {
#   $search_string = $_POST["search_string"];
#} else $search_string = "";
#$user_id = get_userid ($session_id);
################################################################################
#---------------------- BOTONES ANTERIOR Y POSTERIOR --------------------------#
################################################################################	

# YA ESTA INCLUIDO EN siicat_lista.php en INDEX.PHP


################################################################################
#---------------------------- BUSQUEDA TRANSMITIDA ----------------------------#
################################################################################	 
if (((isset($_POST["Submit"])) AND (($_POST["Submit"] == "Ver") OR ($_POST["Submit"] == "Volver"))) OR (isset($_GET["inmu"]))) {	 
   $mostrar = true;
	# $cod_cat = $_POST["cod_cat"];
	# $cod_uv = get_uv ($cod_cat); $cod_man = get_man($cod_cat);  $cod_pred = get_lote ($cod_cat); $cod_subl = get_subl ($cod_cat);  
}
################################################################################
#--------------------------- BUSQUEDA 1 TRANSMITIDA ---------------------------#
################################################################################	
if ((isset($_POST["busqueda1"])) AND ($_POST["busqueda1"] == "Buscar")) {
   $mostrar = true;
	 $cod_uv = $_POST["cod_uv"]; $cod_man = $_POST["cod_man"]; $cod_pred = $_POST["cod_pred"]; $cod_blq = $_POST["cod_blq"]; $cod_piso = $_POST["cod_piso"]; $cod_apto = $_POST["cod_apto"];
	 $cod_cat = get_codcat ($cod_uv,$cod_man,$cod_pred,$cod_blq,$cod_piso,$cod_apto);  
}

################################################################################
#--------------------- LEER DATOS DE INFO_PREDIO_RURAL ------------------------#
################################################################################	
   $id_predio_rural = $_POST['id_predio_rural'];  
   $sql="SELECT * FROM info_predio_rural WHERE id_predio_rural = '$id_predio_rural'";
   $check_info_predio = pg_num_rows(pg_query($sql));
	 $result_predio = pg_query($sql);
   $info_predio = pg_fetch_array($result_predio, null, PGSQL_ASSOC);	
	 $cod_geo = $info_predio['cod_geo']; 
	 $cod_poly = $info_predio['cod_poly'];
	 $cod_predio = $info_predio['predio'];
   $activo = $info_predio['activo'];
   $tipo_pred = $info_predio['tipo_pred'];
   $nom_pred = utf8_decode($info_predio['nom_pred']); 
   $tit_1id = $info_predio['tit_1id'];
   $tit_2id = $info_predio['tit_2id']; 
   $tit_xid = $info_predio['tit_xid']; 
   $docu = $info_predio['docu']; 
   $sup_sdoc = $info_predio['sup_sdoc']; 
   $observ = utf8_decode($info_predio['observ']);  
   pg_free_result($result_predio);
	 
	 $cod_cat = $cod_geo."-".$cod_poly."-".$cod_predio;
	 $direccion = $nom_pred;
	 $regimen = "Prop. Rural";
	 $titular1 = get_contrib_nombre ($tit_1id); 
	 $titular2 = get_contrib_nombre ($tit_2id);			   
	 $tit_1ci = get_contrib_ci ($tit_1id);
			if ($tit_1ci == "") {
			   $tit_1ci_texto = "-";
			} else $tit_1ci_texto = $tit_1ci;
			$tit_2ci = get_contrib_ci ($tit_2id);			 
	 
	$adq_modo_texto = $adq_doc_texto = $adq_fech_texto = $tit_cara_texto = $der_num_texto = $der_fech_texto = "-";
	 
################################################################################
#-------------------------- DEFINIR ZONA HOMOGENEA ----------------------------#
################################################################################	
# NECESITA COD_GEO, COD_UV, COD_MAN, COD_PRED
$ben_zona = get_zona ($id_inmu);
if ($ben_zona == "0") {
  $ben_zona = "NO DEF.";
}
################################################################################
#-------------------------- DEFINIR MATERIAL DE VIA ---------------------------#
################################################################################	
# NECESITA COD_GEO, COD_UV, COD_MAN, COD_PRED
$via_mat = get_material_de_via ($id_inmu);
if ($via_mat == "0") {
  $via_mat_texto = "NO DEF.";
} else $via_mat_texto = utf8_decode(abr($via_mat));
################################################################################
#-------------  PREPARAR Y GENERAR BARRA DE NAVEGACION E I-FRAME --------------#
################################################################################
#echo "COD_CAT: $cod_uv - $cod_man - $cod_pred, CHECK_INFO: $check_info_predio<br />"; 
 
   $resultado = true;
   include "siicat_lista_datos_rural.php";
################################################################################
#------------------ CHEQUEAR SI EL PREDIO ESTA ACTIVO -------------------------#
################################################################################	
$sql="SELECT activo FROM predios WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result_act = pg_query($sql);
$act = pg_fetch_array($result_act, null, PGSQL_ASSOC);
$activo = $act['activo'];

$activo = 1;

pg_free_result($result_act);
################################################################################
#------------------------------ CHEQUEAR GRAVAMEN -----------------------------#
################################################################################	
/*$sql="SELECT texto FROM gravamen WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
$check_gravamen = pg_num_rows(pg_query($sql)); 
if ($check_gravamen == 0) {
   $gravamen = false;
} else {
   $gravamen = true;
}*/$gravamen = false;
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################		
	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
	 # Fila 1
   echo "      <tr height=\"40px\">\n";
   echo "			    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=15&id=$session_id\" accept-charset=\"utf-8\">\n";		 
	 echo "         <td align=\"center\" valign=\"bottom\" width=\"15%\">\n";
   if ($resultado) {	
	    if ($gravamen) {
#	    echo "            <fieldset style=\"border-color: #ff0000;\"><font color=\"red\"><b>\n";
#			echo "            </fieldset>\n";
#      echo "       <img src=\"graphics/boton_gravamen.gif\" alt=\"Gravamen\" width=\"100\" height=\"30\" border=\"0\">\n";		
 	       echo "       <input type=\"image\" src=\"graphics/boton_gravamen.gif\" width=\"100\" height=\"30\" border=\"0\" name=\"gravamen\" value=\"Gravamen\">\n";		
	    } else {
#	    echo "            <fieldset style=\"border-color: lightgrey;\"><font color=\"grey\"><b> GRAVAMEN</b></fieldset>\n"; 
 	       echo "       <input type=\"image\" src=\"graphics/boton_gravamen.png\" width=\"100\" height=\"30\" border=\"0\" name=\"gravamen\" value=\"Gravamen\">\n";
	    }
	 } #END_OF_IF ($resultado)
   echo "            <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";		 	 
   echo "            <input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";	  
	 echo "         </td>\n";   #Col. 1 	 
	 echo "         </form>\n";   #Col. 1 		    
   echo "         <td align=\"center\" valign=\"center\" width=\"60%\" class=\"pageName\">\n"; 
   echo "            Datos del Inmueble\n";
	 if (($activo == 0) AND ($resultado)) {
	    echo "            <font color=\"red\"> - Archivo</font>\n";
	 }                          
   echo "         </td>\n";
	 echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";	
if ($resultado) {	  
   # Fila 2 
   $mod_lista = 5;
	 include "siicat_lista_formulario_rural.php";  
   # Fila 2
if ($activo == 1) {	 
	 echo "      <tr>\n";                       
	 echo "         <td valign=\"top\" height=\"180\" colspan=\"3\" class=\"bodyText\">\n";  #Col. 1+2+3	
	 echo "         <div id=\"tabs\">\n";
	 echo "            <ul>\n";
	 echo "               <li><a href=\"#tab-1\"><span>Datos</span></a></li>\n";
	 echo "               <li><a href=\"#tab-2\"><span>Geometría</span></a></li>\n";	
	 echo "               <li><a href=\"#tab-3\"><span>Planos</span></a></li>\n";
	 echo "               <li><a href=\"#tab-4\"><span>Modificar</span></a></li>\n";	
	 echo "               <li><a href=\"#tab-5\"><span>Borrar</span></a></li>\n";	 
	 echo "               <li><a href=\"#tab-6\"><span>Cambios</span></a></li>\n";
	 echo "               <li><a href=\"#tab-7\"><span>Transfer</span></a></li>\n";		  
	 echo "               <li><a href=\"#tab-8\"><span>Impuestos</span></a></li>\n";		   		 		 	  
	 echo "            </ul>\n";
	 echo "            <div id=\"tab-1\">\n";
include "siicat_ver_datos_rural.php";	 
 #       <p>First tab is active by default:</p>
 #       <pre><code>$('#example').tabs();</code></pre>
	 echo "            </div>\n";
	 echo "            <div id=\"tab-2\">\n";
include "siicat_ver_datos_rural.php";
	 echo "            </div>\n";
	 echo "            <div id=\"tab-3\">\n";
include "siicat_ver_datos_rural.php";
	 echo "            </div>\n";	 
	 echo "            <div id=\"tab-4\">\n";
include "siicat_ver_datos_rural.php";
	 echo "            </div>\n";	 
	 echo "            <div id=\"tab-5\">\n";
include "siicat_ver_datos_rural.php";	 
	 echo "            </div>\n";	
	 echo "            <div id=\"tab-6\">\n";
include "siicat_ver_datos_rural.php";	 
	 echo "            </div>\n";		
	 echo "            <div id=\"tab-7\">\n";
include "siicat_ver_datos_rural.php";	 
	 echo "            </div>\n";			 
	 echo "            <div id=\"tab-8\">\n";	 
include "siicat_ver_datos_rural.php";	
	 echo "            </div>\n";
		 			 		  	 
	 echo "         </div>\n";	 
	 echo "         </td>\n";	 	 	  	 
	 echo "      </tr>\n";
	 
} else {  # $activo == 0
	 echo "      <tr>\n";                       
	 echo "         <td valign=\"top\" height=\"180\" colspan=\"3\" class=\"bodyText\">\n";  #Col. 1+2+3	  
	 echo "         <fieldset><legend>Historial del Predio</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 8 
 	 echo "               <tr>\n";
	 echo "                  <td align=\"center\" colspan=\"12\">\n";
	 echo "                     <font color=\"red\">El Predio ya no está activado en la base de datos por causa de división de predio o asignación de un nuevo código!<br /><br /></font>\n";
	 echo "                  </td>\n";	                     
	 echo "               </tr>\n";	 
 	 echo "               <tr>\n";
	 echo "                  <td width=\"4%\"> &nbsp</td>\n";		 
	 echo "                  <td width=\"18%\" class=\"bodyTextD_Small\"> Predio en el archivo:</td>\n";		 
	 echo "                  <td align=\"center\" width=\"14%\" class=\"bodyTextH\">Fecha Adquisición</td>\n";	 
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";		 
	 echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextH\">Código</td>\n";
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"25%\" class=\"bodyTextH\">Propietario</td>\n";
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"14%\" class=\"bodyTextH\">Superficie</td>\n";
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">Activo</td>\n";
	 echo "                  <td width=\"4%\"> &nbsp</td>\n";	 		 	                     
	 echo "               </tr>\n";
 	 echo "               <tr>\n";
	 echo "                  <td> &nbsp</td>\n";	 
	 echo "                  <td> &nbsp</td>\n";		 
	 echo "                  <td align=\"center\" class=\"bodyTextD_Small\">$adq_fech</td>\n";	 	 
	 echo "                  <td> &nbsp</td>\n";		 
	 echo "                  <td align=\"center\" class=\"bodyTextD_Small\">$cod_cat</td>\n";
	 echo "                  <td> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" class=\"bodyTextD_Small\">$titular1</td>\n";
	 echo "                  <td> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" class=\"bodyTextD_Small\">$ter_smen m²</td>\n";
	 echo "                  <td> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" class=\"bodyTextD_Small\">NO</td>\n";
	 echo "                  <td> &nbsp</td>\n";	 	 	                     
	 echo "               </tr>\n"; 
 	 echo "               <tr>\n";
	 echo "                  <td align=\"center\" colspan=\"12\">\n";
	 echo "                     &nbsp\n";
	 echo "                  </td>\n";	                     
	 echo "               </tr>\n";	
   /*if ($no_de_intermed > 0) { 
	    echo "               <tr>\n";	
	    echo "                  <td> &nbsp</td>\n";	 	 
	    echo "                  <td class=\"bodyTextD_Small\"> Predios Intermedios:</td>\n";		 
	    echo "                  <td align=\"center\" class=\"bodyTextH\">Fecha Cambio</td>\n";	 	 
	    echo "                  <td> &nbsp</td>\n";		 
	    echo "                  <td align=\"center\" class=\"bodyTextH\">Código interm.</td>\n";
	    echo "                  <td> &nbsp</td>\n";	
	    echo "                  <td align=\"center\" class=\"bodyTextH\">Propietario interm.</td>\n";
	    echo "                  <td> &nbsp</td>\n";	
	    echo "                  <td align=\"center\" class=\"bodyTextH\">Superficie interm.</td>\n";
	    echo "                  <td> &nbsp</td>\n";
	    echo "                  <td align=\"center\" class=\"bodyTextH\">Activo</td>\n";
	    echo "                  <td> &nbsp</td>\n";				 		 	  	 	 
	    echo "               </tr>\n";
      $i = 0;
      while ($i < $no_de_intermed) {	 
 	       echo "               <tr>\n";
	       echo "                  <td> &nbsp</td>\n";		 
	       echo "                  <td> &nbsp</td>\n";		 
	       echo "                  <td align=\"center\" class=\"bodyTextD_Small\">$adq_fech_intermed[$i]</td>\n";	 	 
	       echo "                  <td> &nbsp</td>\n";		 
	       echo "                  <td align=\"center\" class=\"bodyTextD_Small\">$cod_cat_intermed[$i]</td>\n";
	       echo "                  <td> &nbsp</td>\n";	
	       echo "                  <td align=\"center\" class=\"bodyTextD_Small\">$titular1_intermed[$i]</td>\n";
	       echo "                  <td> &nbsp</td>\n";	
	       echo "                  <td align=\"center\" class=\"bodyTextD_Small\">$hist_area_intermed[$i] m²</td>\n";
	       echo "                  <td> &nbsp</td>\n";
	       echo "                  <td align=\"center\" class=\"bodyTextD_Small\">NO</td>\n";
	       echo "                  <td> &nbsp</td>\n";				 		 	                     
	       echo "               </tr>\n";
	       $i++;
      }
 	    echo "               <tr>\n";
	    echo "                  <td align=\"center\" colspan=\"12\">\n";
	    echo "                     &nbsp\n";
	    echo "                  </td>\n";	                     
	    echo "               </tr>\n";				
   } # END_OF_IF ($no_de_intermed > 0)	 	*/ 	 
	 echo "               <tr>\n";	
	 echo "                  <td> &nbsp</td>\n";	 	 
	 echo "                  <td class=\"bodyTextD_Small\"> Predio(s) actual(es):</td>\n";		 
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Fecha Cambio</td>\n";	 	 
	 echo "                  <td> &nbsp</td>\n";		 
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Código</td>\n";
	 echo "                  <td> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Propietario</td>\n";
	 echo "                  <td> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Superficie</td>\n";
	 echo "                  <td> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Activo</td>\n";
	 echo "                  <td> &nbsp</td>\n";		  		 	  	 	 
	 echo "               </tr>\n";
   $i = 0;
   while ($i < $no_de_herederos) {	 
 	    echo "               <tr>\n";
	    echo "                  <td> &nbsp</td>\n";		 
	    echo "                  <td> &nbsp</td>\n";		 
	    echo "                  <td align=\"center\" class=\"bodyTextD_Small\">$fecha_cambio_heredero[$i]</td>\n";	 	 
	    echo "                  <td> &nbsp</td>\n";		 
	    echo "                  <td align=\"center\" class=\"bodyTextD_Small\">$cod_cat_heredero[$i]</td>\n";
	    echo "                  <td> &nbsp</td>\n";	
	    echo "                  <td align=\"center\" class=\"bodyTextD_Small\">$titular1_heredero[$i]</td>\n";
	    echo "                  <td> &nbsp</td>\n";	
	    echo "                  <td align=\"center\" class=\"bodyTextD_Small\">$area_heredero[$i] m²</td>\n";
	    echo "                  <td> &nbsp</td>\n";
	    echo "                  <td align=\"center\" class=\"bodyTextD_Small\">$activo_heredero[$i]</td>\n";
	    echo "                  <td> &nbsp</td>\n";	 		 	                     
	    echo "               </tr>\n";
	    $i++;
   }
   echo "               <tr>\n";
   echo "                  <td align=\"center\" colspan=\"12\">\n";
   echo "                     &nbsp\n";
   echo "                  </td>\n";	                     
   echo "               </tr>\n";
	 /*
	 if (($nivel == 2) OR ($nivel == 4) OR ($nivel == 5)) {
      if ((isset($_POST["recuperar"])) AND ($_POST["recuperar"] == "Recuperar Predio")) {
         echo "               <tr>\n";
	       echo "			          <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id\" accept-charset=\"utf-8\">\n";	 				 
         echo "                  <td align=\"center\" colspan=\"12\">\n";
				 echo "  				 	          <font color=\"red\"> Realmente quiere recuperar el Predio Original? Todos los Predios Actuales se perderán! </font>\n";
	       echo "                     <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";
	       echo "                     <input name=\"no_de_intermed\" type=\"hidden\" value=\"$no_de_intermed\">\n";
	       echo "                     <input name=\"no_de_herederos\" type=\"hidden\" value=\"$no_de_herederos\">\n";
				 $i = 0;
				 while ($i < $no_de_herederos) {	
	          echo "                     <input name=\"cod_cat_heredero$i\" type=\"hidden\" value=\"$cod_cat_heredero[$i]\">\n";
				    $i++;
				 }					 		 	 				             		   	 
	       echo "                     <input name=\"recuperar\" type=\"submit\" class=\"smallText\" value=\"OK\">&nbsp&nbsp&nbsp\n";
	       echo "                     <input name=\"recuperar\" type=\"submit\" class=\"smallText\" value=\"No recuperar\">\n";				 	 
         echo "                  </td>\n";
			   echo "               </form>\n";				 	                     
         echo "               </tr>\n";
			} elseif (!$error) {	 	 
         echo "               <tr>\n";
	       echo "			          <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id\" accept-charset=\"utf-8\">\n";					 
         echo "                  <td align=\"right\" colspan=\"6\">\n";	 
	       echo "                     <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";		   	 
	       echo "                     <input name=\"recuperar\" type=\"submit\" class=\"smallText\" value=\"Recuperar Predio\">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp\n";	 
         echo "                  </td>\n";
			   echo "               </form>\n";	
	       echo "			          <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=70&id=$session_id\" accept-charset=\"utf-8\">\n";						 			 
         echo "                  <td align=\"left\" colspan=\"6\">\n";	 
	       echo "                     <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";		   	 
	       echo "                     <input name=\"recuperar\" type=\"submit\" class=\"smallText\" value=\"Borrar Predio\">\n";	 
         echo "                  </td>\n";	
			   echo "               </form>\n";					 				 	                     
         echo "               </tr>\n";
		  } else {
         echo "      <tr>\n"; 	 
	       echo "         <td align=\"center\" height=\"20\" colspan=\"3\">\n";   #Col. 1+2+3  	 			 
	       echo "         <font color=\"red\">$mensaje_de_error</font> <br />\n";				 	    
		     echo "         </td>\n"; 
         echo "      </tr>\n";
	    }
   }	 		  */	 	     		  
	 echo "            </table>\n"; 
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE 5-7 COLUMNAS
	 echo "               <tr>\n";
	 echo "                  <td width=\"5%\"></td>\n";   #Col. 1		 
	 if ($nivel > 1) {	 
	    echo "                  <td align=\"center\" width=\"15%\" class=\"bodyText\">\n";   #Col. 2	
	 } else {
	    echo "                  <td align=\"center\" width=\"22%\" class=\"bodyText\">\n";   #Col. 2	 
	 } 
   echo "			             <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=20&id=$session_id\" accept-charset=\"utf-8\">\n";
	 echo "                     <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";
	 echo "                     <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";		 	   	 
	 echo "                     <input name=\"nada\" type=\"submit\" class=\"smallText\" value=\"Edificaciones\">\n";
	 echo "                  </form>\n";  		 
	 echo "                  </td>\n";
	 if ($nivel > 1) {	 
	    echo "                  <td align=\"center\" width=\"15%\" class=\"bodyText\">\n";   #Col. 3	
	 } else {
	    echo "                  <td align=\"center\" width=\"23%\" class=\"bodyText\">\n";   #Col. 3	 
	 }  
   echo "			             <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=10&id=$session_id\" accept-charset=\"utf-8\">\n";	
	 echo "                     <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n"; 
	 echo "                     <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";	   	 
	 echo "                     <input name=\"volver\" type=\"submit\" class=\"smallText\" value=\"Ver Geometría\">\n";
   echo "                  </form>\n";  	 
	 echo "                  </td>\n";
	 if ($nivel > 1) {	 
	    echo "                  <td align=\"center\" width=\"15%\" class=\"bodyText\">\n";   #Col. 3	
	 } else {
	    echo "                  <td align=\"center\" width=\"23%\" class=\"bodyText\">\n";   #Col. 3	 
	 }  
   echo "			             <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=7&id=$session_id\" accept-charset=\"utf-8\">\n";
	 echo "                     <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n"; 
	 echo "                     <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";	   	 
	 echo "                     <input name=\"volver\" type=\"submit\" class=\"smallText\" value=\" Ver Fotos\">\n";	  	
	 echo "                  </form>\n"; 	 	 
	 echo "                  </td>\n";	
	 if ($nivel > 1) {		 
	    echo "                  <td align=\"center\" width=\"15%\" class=\"bodyText\">\n";   #Col. 3	
	 } else {
	    echo "                  <td align=\"center\" width=\"22%\" class=\"bodyText\">\n";   #Col. 3	 
	 }  
   echo "			             <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=55&id=$session_id\" accept-charset=\"utf-8\">\n";
	 echo "                     <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n"; 
	 echo "                     <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";	   	 
	 echo "                     <input name=\"volver\" type=\"submit\" class=\"smallText\" value=\"Cambios\">\n";	  	
	 echo "                  </form>\n"; 	 	 
	 echo "                  </td>\n";	 	 
   echo "               </tr>\n";	 
	 echo "            </table>\n"; 	 
	 echo "         </fieldset>\n";
	 echo "         </td>\n";	 	 	  	 
	 echo "      </tr>\n";	
	 if ($nivel > 1) {	
	    echo "      <tr>\n";                       
	    echo "         <td valign=\"top\" height=\"180\" colspan=\"3\" class=\"bodyText\">\n";  #Col. 1+2+3	  
	    echo "            <table border=\"0\" width=\"100%\">\n";   # 8 
      if ((isset($_POST["recuperar"])) AND ($_POST["recuperar"] == "Recuperar Predio")) {
         echo "               <tr>\n";
	       echo "			          <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id\" accept-charset=\"utf-8\">\n";	 				 
         echo "                  <td align=\"center\">\n";
				 echo "  				 	          <font color=\"red\"> Realmente quiere recuperar el Predio Original? Todos los Predios Actuales se perderán! </font>\n";
	       echo "                     <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";
	       echo "                     <input name=\"no_de_intermed\" type=\"hidden\" value=\"$no_de_intermed\">\n";
	       echo "                     <input name=\"no_de_herederos\" type=\"hidden\" value=\"$no_de_herederos\">\n";
				 $i = 0;
				 while ($i < $no_de_herederos) {	
	          echo "                     <input name=\"cod_cat_heredero$i\" type=\"hidden\" value=\"$cod_cat_heredero[$i]\">\n";
				    $i++;
				 }					 		 	 				             		   	 
	       echo "                     <input name=\"recuperar\" type=\"submit\" class=\"smallText\" value=\"OK\">&nbsp&nbsp&nbsp\n";
	       echo "                     <input name=\"recuperar\" type=\"submit\" class=\"smallText\" value=\"No recuperar\">\n";				 	 
         echo "                  </td>\n";
			   echo "               </form>\n";				 	                     
         echo "               </tr>\n";
			} elseif (!$error) {	 	 
         echo "               <tr>\n";			 
	       #echo "			          <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=5&id=$session_id\" accept-charset=\"utf-8\">\n";					 
         #echo "                  <td align=\"right\" width=\"50%\">\n";	 
	       #echo "                     <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";		   	 
	       #echo "                     <input name=\"recuperar\" type=\"submit\" class=\"smallText\" value=\"Recuperar Predio\">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp\n";	 
         #echo "                  </td>\n";
			   #echo "               </form>\n";	
	       echo "			          <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=70&id=$session_id\" accept-charset=\"utf-8\">\n";						 			 
         echo "                  <td align=\"left\" width=\"50%\">\n";	 
	       echo "                     <input name=\"cod_cat\" type=\"hidden\" value=\"$cod_cat\">\n";		   	 
	       echo "                     <input name=\"recuperar\" type=\"submit\" class=\"smallText\" value=\"Borrar Predio\">\n";	 
         echo "                  </td>\n";		
			   echo "               </form>\n";	 				 				 	                     
         echo "               </tr>\n";
		  } else {
         echo "      <tr>\n"; 	 
	       echo "         <td align=\"center\" height=\"20\">\n";   #Col. 1+2+3  	 			 
	       echo "         <font color=\"red\">$mensaje_de_error</font> <br />\n";				 	    
		     echo "         </td>\n"; 
         echo "      </tr>\n";
	    }
			echo "            </table>\n"; 
	    echo "         </td>\n";	 	 	  	 
	    echo "      </tr>\n";		
   }	  			  
} #END_OF_ELSE# $activo == 0 	

#	 echo "      <tr>\n"; 	 
#	 echo "         <td align=\"center\" colspan=\"3\">\n";   #COLUMNA 1+2+3  
#   if (isset($_POST["search_string"])) {	 
#	    echo "            <input name=\"busqueda\" type=\"hidden\" value=\"Buscar\">\n"; 
#	    echo "            <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";
#   }	   	 
#	 echo "            <input name=\"volver\" type=\"submit\" class=\"smallText\" value=\"Volver\">\n";	  
#	 echo "         </td>\n";
#	 echo "      </tr>\n"; 	 	 	 	 	  
#	 echo "      </form>\n";	  	 
	 #pg_free_result($result); 
	 } else { # IF (!$resultado) {
      echo "			<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=3&id=$session_id&iframe\" accept-charset=\"utf-8\">\n";	 
	    echo "      <tr>\n";	
	    echo "         <td align=\"center\" colspan=\"3\">\n"; #Col. 1+2+3
			echo "            <br /><font color=\"red\"> Error: No se encuentran ningunos datos con el código $cod_cat en la base de datos. <br />Posiblemente el $predio ha sido borrado, re-codificado o unido con otro $predio!</font>\n"; 
			echo "         </td>\n";    
	    echo "      </tr>\n";	
		  echo "      <tr>\n";
#			echo "         <td align=\"center\" colspan=\"3\"><br /><input type='button' class='smallText' value='Volver a buscar' onClick='javascript:history.back();' style='font-family: Tahoma; font-size: 10pt; font-weight: bold'>\n";
#			echo "         &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>\n";   #Col. 1-3	
#	    echo "         <td align=\"center\"><input name=\"accion\" type=\"submit\" class=\"smallText\" value=\"Añadir Información del Terreno\"></td>\n";   #Col. 3		 
	    echo "      </tr>\n";
	    echo "      </form>\n";							 	
	 }
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
	 echo "   <br />&nbsp;<br />\n";

?>